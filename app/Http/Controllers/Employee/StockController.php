<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isEmployee()) {
            abort(403, 'Unauthorized access.');
        }

        $branchId = Auth::user()->branch_id;

        // Build query for products (since we're using products table for stock)
        $query = Product::with('category')
            ->where('branch_id', $branchId)
            ->where('is_active', true);

        // Category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Stock level filter
        if ($request->filled('stock_level')) {
            switch ($request->stock_level) {
                case 'low':
                    $query->where('stock', '<=', 10);
                    break;
                case 'medium':
                    $query->whereBetween('stock', [11, 50]);
                    break;
                case 'high':
                    $query->where('stock', '>', 50);
                    break;
                case 'out':
                    $query->where('stock', 0);
                    break;
            }
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        $products = $query->orderBy('name')->paginate(20);

        // Get categories for filter
        $categories = Category::whereHas('products', function($query) use ($branchId) {
            $query->where('branch_id', $branchId)->where('is_active', true);
        })->orderBy('name')->get();

        // Get stock statistics
        $totalProducts = Product::where('branch_id', $branchId)->where('is_active', true)->count();
        $lowStockCount = Product::where('branch_id', $branchId)->where('is_active', true)->where('stock', '<=', 10)->count();
        $outOfStockCount = Product::where('branch_id', $branchId)->where('is_active', true)->where('stock', 0)->count();
        $totalStockValue = Product::where('branch_id', $branchId)->where('is_active', true)->sum(\DB::raw('stock * base_price'));

        return view('employee.stocks.index', compact(
            'products',
            'categories',
            'totalProducts',
            'lowStockCount',
            'outOfStockCount',
            'totalStockValue'
        ));
    }
}
