<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutOrderRequest;
use App\Models\Category;
use App\Models\Modifier;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemModifier;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PosController extends Controller
{
    /**
     * Display the POS cashier interface.
     */
    public function index(Request $request): View
    {
        $categories = Category::where('is_active', true)
            ->withCount(['products' => function ($query) {
                $query->where('is_available', true);
            }])
            ->get();

        $products = Product::where('is_available', true)
            ->with(['category', 'modifiers' => function ($query) {
                $query->where('is_active', true);
            }])
            ->orderBy('name')
            ->get();

        $cashier = auth()->user() ?? User::first();

        // Calculate next order sequence for today
        $todayStr = Carbon::today()->format('Ymd');
        $todayOrderCount = Order::whereDate('created_at', Carbon::today())->count();
        $nextOrderNumber = 'KS-'.$todayStr.'-'.str_pad((string) ($todayOrderCount + 1), 4, '0', STR_PAD_LEFT);

        return view('pos.index', compact('categories', 'products', 'cashier', 'nextOrderNumber'));
    }

    /**
     * Process POS checkout with strict server-side price calculation and DB transaction.
     */
    public function checkout(CheckoutOrderRequest $request): JsonResponse
    {
        $validated = $request->validated();

        return DB::transaction(function () use ($validated) {
            $cashier = auth()->user() ?? User::first();
            $itemsData = $validated['items'];

            $calculatedSubtotal = 0;
            $preparedItems = [];

            // Fetch product IDs to minimize queries
            $productIds = collect($itemsData)->pluck('product_id')->unique();
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

            // Fetch all modifier IDs
            $allModifierIds = collect($itemsData)
                ->pluck('modifiers')
                ->flatten()
                ->filter()
                ->unique();

            $modifiers = Modifier::whereIn('id', $allModifierIds)
                ->where('is_active', true)
                ->get()
                ->keyBy('id');

            foreach ($itemsData as $item) {
                $product = $products->get($item['product_id']);

                if (! $product) {
                    throw ValidationException::withMessages([
                        'items' => 'Produk tidak ditemukan.',
                    ]);
                }

                if (! $product->is_available) {
                    throw ValidationException::withMessages([
                        'items' => "Produk {$product->name} saat ini sedang habis.",
                    ]);
                }

                $quantity = (int) $item['quantity'];
                $basePrice = (float) $product->price;
                $modifierTotal = 0;
                $itemModifiers = [];

                if (! empty($item['modifiers']) && is_array($item['modifiers'])) {
                    foreach ($item['modifiers'] as $modId) {
                        $modifier = $modifiers->get($modId);
                        if ($modifier) {
                            $modifierTotal += (float) $modifier->price;
                            $itemModifiers[] = [
                                'modifier_id' => $modifier->id,
                                'modifier_name' => $modifier->name,
                                'price' => (float) $modifier->price,
                            ];
                        }
                    }
                }

                $unitPrice = $basePrice + $modifierTotal;
                $lineSubtotal = $unitPrice * $quantity;
                $calculatedSubtotal += $lineSubtotal;

                $preparedItems[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $lineSubtotal,
                    'temperature' => $item['temperature'] ?? null,
                    'notes' => $item['notes'] ?? null,
                    'modifiers' => $itemModifiers,
                ];
            }

            // Calculations
            $discount = min($calculatedSubtotal, (float) ($validated['discount'] ?? 0));
            $taxable = max(0, $calculatedSubtotal - $discount);
            $tax = round($taxable * 0.10); // 10% PPN
            $serviceCharge = round($taxable * 0.02); // 2% Service Charge
            $grandTotal = $taxable + $tax + $serviceCharge;

            $paymentMethod = $validated['payment_method'];
            $paymentAmount = (float) $validated['payment_amount'];

            if ($paymentMethod === 'cash' && $paymentAmount < $grandTotal) {
                throw ValidationException::withMessages([
                    'payment_amount' => 'Uang tunai yang dibayar kurang dari total tagihan (Rp '.number_format($grandTotal, 0, ',', '.').').',
                ]);
            }

            $changeAmount = max(0, $paymentAmount - $grandTotal);

            // Generate unique order number
            $todayStr = Carbon::today()->format('Ymd');
            $countToday = Order::whereDate('created_at', Carbon::today())->count();
            $orderNumber = 'KS-'.$todayStr.'-'.str_pad((string) ($countToday + 1), 4, '0', STR_PAD_LEFT);

            // Ensure uniqueness
            $counter = 1;
            while (Order::where('order_number', $orderNumber)->exists()) {
                $orderNumber = 'KS-'.$todayStr.'-'.str_pad((string) ($countToday + 1 + $counter), 4, '0', STR_PAD_LEFT);
                $counter++;
            }

            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $cashier?->id,
                'customer_name' => $validated['customer_name'] ?: 'Pelanggan Umum',
                'order_type' => $validated['order_type'],
                'subtotal' => $calculatedSubtotal,
                'discount' => $discount,
                'tax' => $tax,
                'service_charge' => $serviceCharge,
                'total' => $grandTotal,
                'payment_method' => $paymentMethod,
                'payment_amount' => $paymentAmount,
                'change_amount' => $changeAmount,
                'status' => 'completed',
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($preparedItems as $prepItem) {
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $prepItem['product_id'],
                    'product_name' => $prepItem['product_name'],
                    'quantity' => $prepItem['quantity'],
                    'unit_price' => $prepItem['unit_price'],
                    'subtotal' => $prepItem['subtotal'],
                    'temperature' => $prepItem['temperature'],
                    'notes' => $prepItem['notes'],
                ]);

                foreach ($prepItem['modifiers'] as $prepMod) {
                    OrderItemModifier::create([
                        'order_item_id' => $orderItem->id,
                        'modifier_id' => $prepMod['modifier_id'],
                        'modifier_name' => $prepMod['modifier_name'],
                        'price' => $prepMod['price'],
                    ]);
                }
            }

            $order->load(['items.modifiers', 'user']);

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan.',
                'order' => $order,
                'receipt_url' => route('transactions.receipt', $order->id),
            ]);
        });
    }
}
