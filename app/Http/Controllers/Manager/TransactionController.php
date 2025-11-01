<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function __construct()
    {
        // Middleware applied in routes/web.php
    }

    /**
     * Display a listing of transactions for manager's branch
     */
    public function index(Request $request)
    {
        $branchId = Auth::user()->branch_id;
        
        $query = Transaction::where('branch_id', $branchId)
            ->with(['employee', 'items.product']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search by invoice number or customer name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(15);

        // Statistics
        $totalTransactions = Transaction::where('branch_id', $branchId)->count();
        $completedTransactions = Transaction::where('branch_id', $branchId)
            ->where('status', 'completed')->count();
        $totalRevenue = Transaction::where('branch_id', $branchId)
            ->where('status', 'completed')
            ->sum('total_amount');
        $todayRevenue = Transaction::where('branch_id', $branchId)
            ->where('status', 'completed')
            ->whereDate('created_at', today())
            ->sum('total_amount');

        return view('manager.transactions.index', compact(
            'transactions',
            'totalTransactions',
            'completedTransactions',
            'totalRevenue',
            'todayRevenue'
        ));
    }

    /**
     * Display the specified transaction
     */
    public function show(Transaction $transaction)
    {
        // Check if transaction belongs to manager's branch
        if ($transaction->branch_id !== Auth::user()->branch_id) {
            abort(403, 'Unauthorized access to this transaction.');
        }

        $transaction->load(['employee', 'items.product', 'branch']);

        return view('manager.transactions.show', compact('transaction'));
    }
}
