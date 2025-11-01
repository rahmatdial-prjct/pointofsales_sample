<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Transaction;
use App\Models\ReturnTransaction;
use App\Models\Branch;
use App\Models\Product;
use App\Services\FinancialService;
use Carbon\Carbon;

class ReportController extends Controller
{
    protected $financialService;

    public function __construct(FinancialService $financialService)
    {
        $this->financialService = $financialService;
    }

    public function integratedReport(Request $request)
    {
        $user = Auth::user();
        $role = $user->role;

        $branches = [];
        $branchId = null;

        if ($role === 'director') {
            $branches = Branch::with('users')->get();
            $branchId = $request->input('branch_id', $branches->first() ? $branches->first()->id : null);
        } elseif ($role === 'manager') {
            $branchId = $user->branch_id;
            $branches = Branch::where('id', $branchId)->with('users')->get();
        } elseif ($role === 'employee') {
            $branchId = $user->branch_id;
            $branches = Branch::where('id', $branchId)->with('users')->get();
        } else {
            abort(403, 'Unauthorized');
        }

        // Get period filter with more options
        $period = $request->input('period', 'this_month');

        // Calculate date range based on period
        switch ($period) {
            case 'today':
                $startDate = now()->startOfDay();
                $endDate = now()->endOfDay();
                $groupBy = 'day';
                break;
            case 'yesterday':
                $startDate = now()->subDay()->startOfDay();
                $endDate = now()->subDay()->endOfDay();
                $groupBy = 'day';
                break;
            case 'this_week':
                $startDate = now()->startOfWeek();
                $endDate = now()->endOfWeek();
                $groupBy = 'day';
                break;
            case 'last_week':
                $startDate = now()->subWeek()->startOfWeek();
                $endDate = now()->subWeek()->endOfWeek();
                $groupBy = 'day';
                break;
            case 'this_month':
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
                $groupBy = 'week';
                break;
            case 'last_month':
                $startDate = now()->subMonth()->startOfMonth();
                $endDate = now()->subMonth()->endOfMonth();
                $groupBy = 'week';
                break;
            case 'this_year':
                $startDate = now()->startOfYear();
                $endDate = now()->endOfYear();
                $groupBy = 'month';
                break;
            case 'last_year':
                $startDate = now()->subYear()->startOfYear();
                $endDate = now()->subYear()->endOfYear();
                $groupBy = 'month';
                break;
            default:
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
                $groupBy = 'week';
                $period = 'this_month';
        }

        // Get comprehensive financial data
        $financialData = $this->getFinancialData($branchId, $startDate, $endDate, $groupBy, $role, $user);
        $performanceData = $this->getPerformanceData($branchId, $startDate, $endDate, $groupBy, $role, $user);
        $inventoryData = $this->getInventoryData($branchId, $role);
        $returnData = $this->getReturnData($branchId, $startDate, $endDate, $role);
        return view('reports.integrated', compact(
            'branches', 'branchId', 'period', 'role',
            'financialData', 'performanceData', 'inventoryData', 'returnData'
        ));
    }

    private function getFinancialData($branchId, $startDate, $endDate, $groupBy, $role, $user)
    {
        $query = Transaction::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($role === 'director' && $branchId) {
            $query->where('branch_id', $branchId);
        } elseif ($role === 'manager') {
            $query->where('branch_id', $user->branch_id);
        } elseif ($role === 'employee') {
            $query->where('user_id', $user->id);
        }

        // Get net revenue calculation using FinancialService (consistent with dashboard)
        $netRevenueData = $this->financialService->calculateNetRevenue(
            $branchId ?: $user->branch_id,
            $startDate,
            $endDate
        );

        // Group transactions by period
        $transactions = $query->get();
        $groupedData = $transactions->groupBy(function ($item) use ($groupBy) {
            return $this->formatDateByGroup($item->created_at, $groupBy);
        });

        $periodTotals = [];
        foreach ($groupedData as $period => $items) {
            $periodTotals[$period] = [
                'gross_sales' => $items->sum('total_amount'),
                'transaction_count' => $items->count(),
                'avg_transaction' => $items->avg('total_amount'),
            ];
        }

        // Get payment method breakdown
        $paymentMethods = $transactions->groupBy('payment_method')
            ->map(function ($items, $method) {
                return [
                    'count' => $items->count(),
                    'total' => $items->sum('total_amount'),
                    'percentage' => 0 // Will be calculated in view
                ];
            });

        // Get period totals for chart
        $periodTotals = $this->getPeriodTotals($transactions, $groupBy, $startDate, $endDate);

        return [
            'net_revenue' => $netRevenueData['net_revenue'], // Use FinancialService calculation (consistent with dashboard)
            'total_sales' => $netRevenueData['total_sales'], // Gross sales from FinancialService
            'total_returns' => $netRevenueData['total_returns'], // Returns from FinancialService
            'period_totals' => $periodTotals,
            'payment_methods' => $paymentMethods,
            'total_transactions' => $transactions->count(),
            'avg_transaction_value' => $transactions->avg('total_amount') ?: 0,
        ];
    }

    private function getPerformanceData($branchId, $startDate, $endDate, $groupBy, $role, $user)
    {
        if ($role === 'employee') {
            // Employee performance
            $transactions = Transaction::where('user_id', $user->id)
                ->where('status', 'completed')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();

            return [
                'employee_stats' => [
                    'total_sales' => $transactions->sum('total_amount'),
                    'transaction_count' => $transactions->count(),
                    'avg_per_transaction' => $transactions->avg('total_amount') ?: 0,
                ],
                'daily_performance' => $transactions->groupBy(function ($item) {
                    return $item->created_at->format('Y-m-d');
                })->map(function ($items) {
                    return $items->sum('total_amount');
                }),
            ];
        } else {
            // Branch/company performance
            $query = Transaction::where('status', 'completed')
                ->whereBetween('created_at', [$startDate, $endDate]);

            if ($role === 'manager') {
                $query->where('branch_id', $user->branch_id);
            } elseif ($role === 'director' && $branchId) {
                $query->where('branch_id', $branchId);
            }

            // Top performing employees
            $topEmployees = $query->select('user_id', DB::raw('COUNT(*) as transaction_count'),
                                         DB::raw('SUM(total_amount) as total_sales'))
                ->groupBy('user_id')
                ->orderBy('total_sales', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($item) {
                    $user = User::find($item->user_id);
                    $item->user = $user;
                    return $item;
                });

            // Product performance
            $topProducts = DB::table('transaction_items')
                ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
                ->join('products', 'transaction_items.product_id', '=', 'products.id')
                ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                ->where('transactions.status', 'completed')
                ->whereBetween('transactions.created_at', [$startDate, $endDate])
                ->when($role === 'manager', function ($q) use ($user) {
                    return $q->where('transactions.branch_id', $user->branch_id);
                })
                ->when($role === 'director' && $branchId, function ($q) use ($branchId) {
                    return $q->where('transactions.branch_id', $branchId);
                })
                ->select('products.id', 'products.name', 'products.sku', 'products.image', 'products.base_price',
                        'categories.name as category_name',
                        DB::raw('SUM(transaction_items.quantity) as total_quantity'),
                        DB::raw('SUM(transaction_items.subtotal) as total_revenue'))
                ->groupBy('products.id', 'products.name', 'products.sku', 'products.image', 'products.base_price', 'categories.name')
                ->orderBy('total_revenue', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($item) {
                    // Create a mock category object for consistency with the view
                    $category = new \stdClass();
                    $category->name = $item->category_name ?? 'Tanpa Kategori';
                    $item->category = $category;
                    return $item;
                });

            return [
                'top_employees' => $topEmployees,
                'top_products' => $topProducts,
                'branch_performance' => $this->getBranchComparison($role, $user, $startDate, $endDate),
            ];
        }
    }

    private function getInventoryData($branchId, $role)
    {
        if ($role === 'employee') {
            return null; // Employees don't need inventory data
        }

        $query = Product::with('category');

        if ($role === 'manager') {
            $query->where('branch_id', auth()->user()->branch_id);
        } elseif ($role === 'director' && $branchId) {
            $query->where('branch_id', $branchId);
        }

        $products = $query->get();

        return [
            'total_products' => $products->count(),
            'total_stock_value' => $products->sum(function ($product) {
                return $product->stock * $product->base_price;
            }),
        ];
    }

    private function getReturnData($branchId, $startDate, $endDate, $role)
    {
        // Build base query with proper date filtering
        $query = ReturnTransaction::whereBetween('created_at', [$startDate, $endDate]);

        // Apply role-based filtering
        if ($role === 'manager') {
            $query->where('branch_id', auth()->user()->branch_id);
        } elseif ($role === 'director' && $branchId) {
            $query->where('branch_id', $branchId);
        } elseif ($role === 'employee') {
            $query->where('user_id', auth()->id());
        }

        // Get returns with proper relationships
        $returns = $query->with(['returnItems.product', 'employee'])->get();

        // Get approved returns for accurate calculations (consistent with FinancialService)
        $approvedReturns = $returns->where('status', 'disetujui');

        // Calculate total return value (all returns for display)
        $totalReturnValue = $returns->sum(function ($return) {
            if ($return->total > 0) {
                return $return->total;
            }
            return $return->returnItems->sum('subtotal');
        });

        // Calculate approved return value (for net revenue calculation)
        $totalApprovedReturnValue = $approvedReturns->sum(function ($return) {
            if ($return->total > 0) {
                return $return->total;
            }
            return $return->returnItems->sum('subtotal');
        });

        $result = [
            'total_returns' => $returns->count(),
            'total_return_value' => $totalReturnValue,
            'approved_return_value' => $totalApprovedReturnValue, // For net revenue calculation
            'pending_returns' => $returns->where('status', 'pending')->count(),
            'approved_returns' => $returns->where('status', 'disetujui')->count(),
            'rejected_returns' => $returns->where('status', 'ditolak')->count(),
            'return_rate' => $this->calculateReturnRate($branchId, $startDate, $endDate, $role),
        ];

        return $result;
    }

    private function getBranchComparison($role, $user, $startDate, $endDate)
    {
        if ($role !== 'director') {
            return null;
        }

        return Branch::with(['transactions' => function ($query) use ($startDate, $endDate) {
            $query->where('status', 'completed')
                  ->whereBetween('created_at', [$startDate, $endDate]);
        }])->get()->map(function ($branch) {
            return [
                'name' => $branch->name,
                'total_sales' => $branch->transactions->sum('total_amount'),
                'transaction_count' => $branch->transactions->count(),
                'avg_transaction' => $branch->transactions->avg('total_amount') ?: 0,
            ];
        })->sortByDesc('total_sales');
    }

    private function calculateReturnRate($branchId, $startDate, $endDate, $role)
    {
        $salesQuery = Transaction::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate]);

        $returnsQuery = ReturnTransaction::where('status', 'disetujui')
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($role === 'manager') {
            $salesQuery->where('branch_id', auth()->user()->branch_id);
            $returnsQuery->where('branch_id', auth()->user()->branch_id);
        } elseif ($role === 'director' && $branchId) {
            $salesQuery->where('branch_id', $branchId);
            $returnsQuery->where('branch_id', $branchId);
        } elseif ($role === 'employee') {
            $salesQuery->where('user_id', auth()->id());
            $returnsQuery->where('user_id', auth()->id());
        }

        $totalSales = $salesQuery->sum('total_amount');

        // Calculate total returns properly
        $returns = $returnsQuery->with('returnItems')->get();
        $totalReturns = $returns->sum(function ($return) {
            if ($return->total > 0) {
                return $return->total;
            }
            return $return->returnItems->sum('subtotal');
        });

        return $totalSales > 0 ? ($totalReturns / $totalSales) * 100 : 0;
    }

    private function getPeriodTotals($transactions, $groupBy, $startDate, $endDate)
    {
        if ($transactions->isEmpty()) {
            return [];
        }

        $periodTotals = [];

        // Group transactions by period
        $grouped = $transactions->groupBy(function ($transaction) use ($groupBy) {
            return $this->formatDateByGroup($transaction->created_at, $groupBy);
        });

        // Fill in missing periods with zero values
        $current = $startDate->copy();
        while ($current <= $endDate) {
            $key = $this->formatDateByGroup($current, $groupBy);
            $periodTotals[$key] = [
                'gross_sales' => $grouped->get($key, collect())->sum('total_amount'),
                'transaction_count' => $grouped->get($key, collect())->count(),
            ];

            // Increment based on groupBy
            switch ($groupBy) {
                case 'day':
                    $current->addDay();
                    break;
                case 'week':
                    $current->addWeek();
                    break;
                case 'month':
                    $current->addMonth();
                    break;
                case 'year':
                    $current->addYear();
                    break;
            }
        }

        return $periodTotals;
    }

    private function formatDateByGroup($date, $groupBy)
    {
        switch ($groupBy) {
            case 'day':
                return $date->format('d M'); // 16 Jun
            case 'month':
                return $date->format('M Y'); // Jun 2025
            case 'year':
                return $date->format('Y'); // 2025
            case 'week':
            default:
                // Format: "16-22 Jun" (start-end of week)
                $startOfWeek = $date->copy()->startOfWeek();
                $endOfWeek = $date->copy()->endOfWeek();

                if ($startOfWeek->format('M') === $endOfWeek->format('M')) {
                    // Same month: "16-22 Jun"
                    return $startOfWeek->format('d') . '-' . $endOfWeek->format('d M');
                } else {
                    // Different months: "30 May-5 Jun"
                    return $startOfWeek->format('d M') . '-' . $endOfWeek->format('d M');
                }
        }
    }
}
