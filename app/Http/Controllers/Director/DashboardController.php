<?php

namespace App\Http\Controllers\Director;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use App\Services\FinancialService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $financialService;

    public function __construct(FinancialService $financialService)
    {
        $this->financialService = $financialService;
    }

    public function index(Request $request)
    {
        $totalBranches = Branch::count();

        // Calculate net revenue (gross sales - approved returns) for all branches
        $totalNetRevenue = 0;
        $totalGrossRevenue = 0;
        $totalReturns = 0;

        $branches = Branch::all();
        foreach ($branches as $branch) {
            $branchRevenue = $this->financialService->calculateNetRevenue($branch->id);
            $totalGrossRevenue += $branchRevenue['total_sales'];
            $totalReturns += $branchRevenue['total_returns'];
            $totalNetRevenue += $branchRevenue['net_revenue'];
        }

        $recentTransactions = Transaction::with('branch')
            ->where('status', 'completed')
            ->latest()
            ->take(5)
            ->get();

        // Get all branches for dropdown
        $branches = Branch::all();

        // Get selected branch_id from request or default to first branch
        $branchId = $request->input('branch_id', $branches->first() ? $branches->first()->id : null);

        $reportData = null;

        if ($branchId) {
            $branch = Branch::find($branchId);

            if ($branch) {
                // Default date range: last 30 days
                $startDate = $request->input('start_date', \Carbon\Carbon::now()->subDays(30)->toDateString());
                $endDate = $request->input('end_date', \Carbon\Carbon::now()->toDateString());

                // Filter transactions by branch and date range
                $transactions = Transaction::where('branch_id', $branch->id)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->get();

                // Summary statistics
                $totalSalesBranch = $transactions->sum('total_amount');
                $totalTransactions = $transactions->count();
                $averageSalesPerDay = $totalTransactions > 0 ? $totalSalesBranch / $totalTransactions : 0;

                $reportData = [
                    'branch' => $branch,
                    'transactions' => $transactions,
                    'totalSales' => $totalSalesBranch,
                    'totalTransactions' => $totalTransactions,
                    'averageSalesPerDay' => $averageSalesPerDay,
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                ];
            }
        }

        // Get top performing employees across all branches
        $topEmployees = User::where('role', 'employee')
            ->withCount(['transactions as transaction_count' => function($query) {
                $query->where('status', 'completed')
                      ->whereMonth('created_at', now()->month);
            }])
            ->withSum(['transactions as total_sales' => function($query) {
                $query->where('status', 'completed')
                      ->whereMonth('created_at', now()->month);
            }], 'total_amount')
            ->with('branch')
            ->orderBy('total_sales', 'desc')
            ->take(5)
            ->get();

        return view('director.dashboard', compact(
            'totalBranches',
            'totalNetRevenue',
            'totalGrossRevenue',
            'totalReturns',
            'recentTransactions',
            'branches',
            'reportData',
            'topEmployees'
        ));
    }
} 