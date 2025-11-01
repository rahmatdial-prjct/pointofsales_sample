<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {

        $user = Auth::user();

        // Get employee's performance data
        $todaySales = Transaction::where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereDate('created_at', today())
            ->sum('total_amount');

        $monthSales = Transaction::where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        $totalTransactions = Transaction::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        $recentTransactions = Transaction::where('user_id', $user->id)
            ->where('status', 'completed')
            ->latest()
            ->take(5)
            ->get();

        // Get branch info
        $branchInfo = $user->branch;

        // Get today's transaction count
        $todayTransactions = Transaction::where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereDate('created_at', today())
            ->count();

        return view('employee.dashboard', compact(
            'todaySales',
            'monthSales',
            'totalTransactions',
            'todayTransactions',
            'recentTransactions',
            'branchInfo'
        ));
    }
} 