<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $branchId = $user->branch_id;

        // Build query with filters
        $query = Transaction::where('user_id', $user->id)
            ->orWhere('employee_id', $user->id)
            ->with(['items.product', 'branch', 'user']);

        // Date filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get summary statistics
        $totalSales = Transaction::where('user_id', $user->id)
            ->where('status', 'completed')
            ->sum('total_amount');

        $todaySales = Transaction::where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereDate('created_at', today())
            ->sum('total_amount');

        $monthSales = Transaction::where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        return view('employee.transactions.index', compact(
            'transactions',
            'totalSales',
            'todaySales',
            'monthSales'
        ));
    }

    public function create()
    {
        // Ensure user is employee
        if (!Auth::check() || !Auth::user()->isEmployee()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $branchId = Auth::user()->branch_id;

        // Get products with categories for better organization
        $products = Product::with('category')
            ->where('branch_id', $branchId)
            ->where('is_active', true)
            ->where('stock', '>', 0) // Only show products with stock
            ->orderBy('name')
            ->get();

        // Get categories for filtering
        $categories = Category::whereHas('products', function($query) use ($branchId) {
            $query->where('branch_id', $branchId)
                  ->where('is_active', true)
                  ->where('stock', '>', 0);
        })->orderBy('name')->get();

        return view('employee.transactions.create', compact('products', 'categories'));
    }

    public function show(Transaction $transaction)
    {
        // Ensure user can only view their own transactions
        if ($transaction->user_id !== Auth::id() && $transaction->employee_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $transaction->load(['items.product', 'branch', 'user']);

        return view('employee.transactions.show', compact('transaction'));
    }

    public function store(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isEmployee()) {
            abort(403, 'Unauthorized access.');
        }

        $branchId = Auth::user()->branch_id;

        // Debug: Log incoming request data
        \Log::info('Transaction store request data:', [
            'all_data' => $request->all(),
            'items' => $request->input('items'),
            'customer_name' => $request->input('customer_name'),
            'payment_method' => $request->input('payment_method')
        ]);

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'transaction_date' => 'nullable|date|before_or_equal:today',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.discount' => 'nullable|numeric|min:0|max:80', // Max 80% discount
            'payment_method' => 'required|in:cash,transfer,qris,other',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            // Set transaction date (use provided date or current date)
            $transactionDate = $validated['transaction_date'] ?
                \Carbon\Carbon::parse($validated['transaction_date']) :
                \Carbon\Carbon::now();

            $transaction = new Transaction();
            $transaction->branch_id = $branchId;
            $transaction->user_id = Auth::id();
            $transaction->employee_id = Auth::id();
            $transaction->customer_name = $validated['customer_name'];
            $transaction->payment_method = $validated['payment_method'];
            $transaction->notes = $validated['notes'] ?? null;
            $transaction->invoice_number = 'INV-' . $branchId . '-' . $transactionDate->format('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $transaction->status = 'completed';
            $transaction->subtotal = 0; // will calculate below
            $transaction->discount_percentage = 0; // default discount percentage
            $transaction->discount_amount = 0; // will calculate below
            $transaction->total = 0; // will calculate below (original total field)
            $transaction->total_amount = 0; // will calculate below (additional total_amount field)

            // Set custom timestamps if transaction_date is provided
            if ($validated['transaction_date']) {
                $transaction->created_at = $transactionDate;
                $transaction->updated_at = $transactionDate;
            }

            $transaction->save();

            $totalAmount = 0;
            $totalDiscount = 0;

            foreach ($validated['items'] as $item) {
                $product = Product::where('id', $item['product_id'])
                    ->where('branch_id', $branchId)
                    ->where('is_active', true)
                    ->first();

                if (!$product) {
                    throw new \Exception("Product not found or not available in this branch.");
                }

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for product: {$product->name}. Available: {$product->stock}, Requested: {$item['quantity']}");
                }

                // Additional validation: Check for negative stock after transaction
                if (($product->stock - $item['quantity']) < 0) {
                    throw new \Exception("Transaction would result in negative stock for product: {$product->name}");
                }

                $discount = $item['discount'] ?? 0;
                if ($discount > 80) {
                    throw new \Exception("Discount cannot exceed 80% for product: {$product->name}");
                }

                $price = $product->base_price;
                $discountAmount = ($price * $discount) / 100;
                $finalPrice = $price - $discountAmount;
                $subtotal = $finalPrice * $item['quantity'];

                $transactionItem = new TransactionItem();
                $transactionItem->transaction_id = $transaction->id;
                $transactionItem->product_id = $item['product_id'];
                $transactionItem->quantity = $item['quantity'];
                $transactionItem->price = $price;
                $transactionItem->discount_percentage = $discount;
                $transactionItem->discount_amount = $discountAmount * $item['quantity'];
                $transactionItem->subtotal = $subtotal;
                $transactionItem->save();

                // Update product stock with logging
                $oldStock = $product->stock;
                $product->stock -= $item['quantity'];
                $product->save();

                // Log stock change for monitoring
                \Log::info('Stock updated', [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'old_stock' => $oldStock,
                    'new_stock' => $product->stock,
                    'quantity_sold' => $item['quantity'],
                    'transaction_id' => $transaction->id,
                    'user_id' => Auth::id()
                ]);

                $totalAmount += $subtotal;
                $totalDiscount += $discountAmount * $item['quantity'];
            }



            $transaction->subtotal = $totalAmount + $totalDiscount;
            $transaction->discount_percentage = 0; // Overall discount percentage if needed
            $transaction->discount_amount = $totalDiscount;
            $transaction->total = $totalAmount; // Original total field
            $transaction->total_amount = $totalAmount; // Additional total_amount field
            $transaction->save();

            DB::commit();

            return redirect()->route('employee.transactions.invoice', $transaction)
                ->with('success', 'Transaction recorded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Failed to record transaction: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Generate digital invoice for transaction
     */
    public function generateInvoice(Transaction $transaction)
    {
        if (!Auth::check() || !Auth::user()->isEmployee()) {
            abort(403, 'Unauthorized access.');
        }

        // Ensure employee can only view invoices from their branch
        if ($transaction->branch_id !== Auth::user()->branch_id) {
            abort(403, 'Unauthorized access.');
        }

        $transaction->load(['items.product', 'branch', 'employee']);

        return view('employee.transactions.invoice', compact('transaction'));
    }


}
