<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransactionController extends Controller
{
    /**
     * Display a listing of orders with filters and summary stats.
     */
    public function index(Request $request): View
    {
        $query = Order::with(['user', 'items.modifiers'])->latest();

        // Search by order number, customer name, or cashier
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by Date Range
        $period = $request->input('period', 'all');
        if ($period === 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($period === 'week') {
            $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($period === 'month') {
            $query->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year);
        } elseif ($startDate = $request->input('start_date')) {
            $endDate = $request->input('end_date') ?: Carbon::today()->toDateString();
            $query->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ]);
        }

        // Filter by Payment Method
        if ($paymentMethod = $request->input('payment_method')) {
            if ($paymentMethod !== 'all') {
                $query->where('payment_method', $paymentMethod);
            }
        }

        // Filter by Status
        if ($status = $request->input('status')) {
            if ($status !== 'all') {
                $query->where('status', $status);
            }
        }

        // Calculate summary metrics for current filter
        $statsQuery = clone $query;
        $totalRevenue = (float) $statsQuery->where('status', 'completed')->sum('total');
        $totalOrders = (int) $statsQuery->count();
        $cashlessOrders = (int) (clone $statsQuery)->where('payment_method', '!=', 'cash')->count();
        $cashlessPercentage = $totalOrders > 0 ? round(($cashlessOrders / $totalOrders) * 100, 1) : 0;
        $avgTicket = $totalOrders > 0 ? round($totalRevenue / $totalOrders) : 0;

        $orders = $query->paginate(15)->withQueryString();
        $cashier = auth()->user() ?? User::first();

        return view('transactions.index', compact(
            'orders',
            'totalRevenue',
            'totalOrders',
            'cashlessPercentage',
            'avgTicket',
            'cashier'
        ));
    }

    /**
     * Display full order details in JSON format for the modal.
     */
    public function show(Order $order): JsonResponse
    {
        $order->load(['user', 'items.modifiers']);

        return response()->json([
            'order' => $order,
        ]);
    }

    /**
     * Render the thermal receipt view for printing.
     */
    public function receipt(Order $order): View
    {
        $order->load(['user', 'items.modifiers']);

        return view('transactions.receipt', compact('order'));
    }
}
