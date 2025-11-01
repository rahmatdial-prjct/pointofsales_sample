<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function __construct()
    {
        // Middleware removed from here as it is applied in routes/web.php
    }

    /**
     * Display a listing of the products for the manager's branch.
     */
    public function index()
    {
        $branchId = Auth::user()->branch_id;
        $products = Product::where('branch_id', $branchId)
            ->with('category')
            ->paginate(15);

        // Get categories with proper null checking and validation
        $categories = Category::whereNotNull('name')
            ->whereNotNull('id')
            ->where('name', '!=', '')
            ->orderBy('name')
            ->get()
            ->filter(function($category) {
                return $category &&
                       is_object($category) &&
                       property_exists($category, 'id') &&
                       property_exists($category, 'name') &&
                       !empty($category->name);
            });

        return view('manager.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Category::all();
        return view('manager.products.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $branchId = Auth::user()->branch_id;

        $validated = $request->validate([
            'sku' => 'required|string|max:255|unique:products,sku',
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'base_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $product = new Product([
            'sku' => $validated['sku'],
            'name' => $validated['name'],
            'category_id' => $validated['category_id'],
            'base_price' => $validated['base_price'],
            'stock' => $validated['stock'],
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);
        $product->branch_id = $branchId;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('product_images', 'public');
            $product->image = $imagePath;
        }

        $product->save();

        return redirect()->route('manager.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $this->authorize('manage', $product);

        $categories = Category::all();
        return view('manager.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $this->authorize('manage', $product);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'base_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $data = [
            'name' => $validated['name'],
            'category_id' => $validated['category_id'],
            'base_price' => $validated['base_price'],
            'stock' => $validated['stock'],
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'] ?? $product->is_active,
        ];

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('product_images', 'public');
            $data['image'] = $imagePath;
        }

        $product->update($data);

        return redirect()->route('manager.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        $this->authorize('manage', $product);

        $product->delete();

        return redirect()->route('manager.products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
