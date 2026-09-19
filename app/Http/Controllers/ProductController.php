<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Modifier;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of products with filters.
     */
    public function index(Request $request): View
    {
        $query = Product::with(['category', 'modifiers'])->latest();

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        }

        if ($categoryId = $request->input('category_id')) {
            if ($categoryId !== 'all') {
                $query->where('category_id', $categoryId);
            }
        }

        if ($status = $request->input('status')) {
            if ($status === 'available') {
                $query->where('is_available', true);
            } elseif ($status === 'out_of_stock') {
                $query->where('is_available', false);
            }
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $modifiers = Modifier::where('is_active', true)->orderBy('name')->get();
        $cashier = auth()->user() ?? User::first();

        $totalProductsCount = Product::count();
        $availableCount = Product::where('is_available', true)->count();
        $outOfStockCount = Product::where('is_available', false)->count();

        return view('products.index', compact(
            'products',
            'categories',
            'modifiers',
            'cashier',
            'totalProductsCount',
            'availableCount',
            'outOfStockCount'
        ));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $counter = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        $imagePath = $validated['image'] ?? 'https://images.unsplash.com/photo-1509785307050-d4066910ec1e?w=500&auto=format&fit=crop&q=80';
        if ($request->hasFile('image')) {
            $imagePath = '/storage/'.$request->file('image')->store('products', 'public');
        }

        $product = Product::create([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'image' => $imagePath,
            'has_temperature' => $request->boolean('has_temperature'),
            'is_available' => $request->boolean('is_available', true),
        ]);

        if (! empty($validated['modifiers'])) {
            $product->modifiers()->sync($validated['modifiers']);
        }

        return redirect()->route('products.index')->with('success', "Menu {$product->name} berhasil ditambahkan!");
    }

    /**
     * Update the specified product in storage.
     */
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $validated = $request->validated();

        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            $imagePath = '/storage/'.$request->file('image')->store('products', 'public');
        } elseif (! empty($validated['image'])) {
            $imagePath = $validated['image'];
        }

        $product->update([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'image' => $imagePath,
            'has_temperature' => $request->boolean('has_temperature'),
            'is_available' => $request->boolean('is_available'),
        ]);

        if (isset($validated['modifiers'])) {
            $product->modifiers()->sync($validated['modifiers']);
        } else {
            $product->modifiers()->sync([]);
        }

        return redirect()->route('products.index')->with('success', "Menu {$product->name} berhasil diperbarui!");
    }

    /**
     * Toggle product availability status.
     */
    public function toggleStatus(Request $request, Product $product): JsonResponse|RedirectResponse
    {
        $product->is_available = ! $product->is_available;
        $product->save();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_available' => $product->is_available,
                'message' => 'Status ketersediaan berhasil diubah.',
            ]);
        }

        return back()->with('success', "Status {$product->name} berhasil diubah.");
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $name = $product->name;
        $product->delete();

        return redirect()->route('products.index')->with('success', "Menu {$name} berhasil dihapus.");
    }
}
