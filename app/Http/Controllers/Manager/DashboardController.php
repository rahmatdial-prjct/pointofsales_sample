<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\ReturnTransaction;
use App\Models\User;
use App\Services\FinancialService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $financialService;

    public function __construct(FinancialService $financialService)
    {
        $this->financialService = $financialService;
    }

    public function index(Request $request)
    {
        $user = Auth::user()->load('branch');
        $branch = $user->branch;



        // Get period filter
        $periode = $request->get('periode', 'bulan_ini');
        $today = now();

        // Calculate date range based on period
        switch ($periode) {
            case 'hari_ini':
                $startDate = $today->copy()->startOfDay();
                $endDate = $today->copy()->endOfDay();
                $periodeLabel = 'Hari Ini';
                break;
            case 'minggu_ini':
                $startDate = $today->copy()->startOfWeek();
                $endDate = $today->copy()->endOfWeek();
                $periodeLabel = 'Minggu Ini';
                break;
            case 'bulan_ini':
                $startDate = $today->copy()->startOfMonth();
                $endDate = $today->copy()->endOfMonth();
                $periodeLabel = 'Bulan Ini';
                break;
            case 'bulan_kemarin':
                $startDate = $today->copy()->subMonth()->startOfMonth();
                $endDate = $today->copy()->subMonth()->endOfMonth();
                $periodeLabel = 'Bulan Kemarin';
                break;
            default:
                $startDate = $today->copy()->startOfMonth();
                $endDate = $today->copy()->endOfMonth();
                $periodeLabel = 'Bulan Ini';
        }

        // Calculate net revenue for selected period
        $periodFinancial = $this->financialService->calculateNetRevenue(
            $branch->id,
            $startDate,
            $endDate
        );
        $periodSales = $periodFinancial['net_revenue'];
        $periodGrossSales = $periodFinancial['total_sales'];
        $periodReturns = $periodFinancial['total_returns'];

        // Calculate net revenue for today (for comparison)
        $todayFinancial = $this->financialService->calculateNetRevenue(
            $branch->id,
            today(),
            today()
        );
        $todaySales = $todayFinancial['net_revenue'];
        $todayGrossSales = $todayFinancial['total_sales'];
        $todayReturns = $todayFinancial['total_returns'];

        // Calculate net revenue for this month (for comparison)
        $monthFinancial = $this->financialService->calculateNetRevenue(
            $branch->id,
            now()->startOfMonth(),
            now()->endOfMonth()
        );
        $monthSales = $monthFinancial['net_revenue'];
        $monthGrossSales = $monthFinancial['total_sales'];
        $monthReturns = $monthFinancial['total_returns'];

        $totalProducts = Product::where('branch_id', $branch->id)->count();

        $activeProducts = Product::where('branch_id', $branch->id)
            ->where('is_active', true)
            ->count();

        $totalEmployees = User::where('branch_id', $branch->id)
            ->where('role', 'employee')
            ->count();

        // Pending returns for selected period
        $pendingReturns = ReturnTransaction::where('branch_id', $branch->id)
            ->where('status', 'menunggu') // Updated to Indonesian status
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $recentTransactions = Transaction::where('branch_id', $branch->id)
            ->with(['user'])
            ->where('status', 'completed')
            ->latest()
            ->take(5)
            ->get();

        $recentTransactionsCount = $recentTransactions->count();

        // Get top performing employees
        $topEmployees = User::where('branch_id', $branch->id)
            ->where('role', 'employee')
            ->withCount(['transactions as transaction_count' => function($query) {
                $query->where('status', 'completed')
                      ->whereMonth('created_at', now()->month);
            }])
            ->withSum(['transactions as total_sales' => function($query) {
                $query->where('status', 'completed')
                      ->whereMonth('created_at', now()->month);
            }], 'total_amount')
            ->orderBy('total_sales', 'desc')
            ->take(3)
            ->get();

        return view('manager.dashboard', compact(
            'todaySales',
            'todayGrossSales',
            'todayReturns',
            'monthSales',
            'monthGrossSales',
            'monthReturns',
            'periodSales',
            'periodGrossSales',
            'periodReturns',
            'periode',
            'periodeLabel',
            'totalProducts',
            'activeProducts',
            'totalEmployees',
            'recentTransactionsCount',
            'recentTransactions',
            'pendingReturns',
            'topEmployees'
        ));
    }

}