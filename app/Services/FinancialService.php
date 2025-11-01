<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\ReturnTransaction;
use App\Models\Branch;
use Carbon\Carbon;

class FinancialService
{
    /**
     * Calculate net revenue for a branch within date range
     * (Total Sales - Total Returns)
     */
    public function calculateNetRevenue($branchId, $startDate = null, $endDate = null)
    {
        $startDate = $startDate ?? Carbon::now()->startOfMonth();
        $endDate = $endDate ?? Carbon::now()->endOfMonth();

        // Total sales
        $totalSales = Transaction::where('branch_id', $branchId)
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount');

        // Total approved returns - calculate from return_items if total is 0
        $totalReturns = ReturnTransaction::where('branch_id', $branchId)
            ->where('status', 'disetujui') // Updated to Indonesian status
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->sum(function ($return) {
                // If total is already calculated, use it
                if ($return->total > 0) {
                    return $return->total;
                }
                // Otherwise calculate from return items
                return $return->returnItems->sum('subtotal');
            });

        return [
            'total_sales' => $totalSales,
            'total_returns' => $totalReturns,
            'net_revenue' => $totalSales - $totalReturns,
            'return_rate' => $totalSales > 0 ? ($totalReturns / $totalSales) * 100 : 0,
        ];
    }

    /**
     * Calculate financial impact of a return
     */
    public function calculateReturnImpact(ReturnTransaction $return)
    {
        $originalTransaction = $return->transaction;
        
        if (!$originalTransaction) {
            return null;
        }

        return [
            'original_amount' => $originalTransaction->total_amount,
            'return_amount' => $return->total,
            'impact_percentage' => ($return->total / $originalTransaction->total_amount) * 100,
            'remaining_amount' => $originalTransaction->total_amount - $return->total,
        ];
    }

    /**
     * Get financial summary for manager dashboard
     */
    public function getManagerFinancialSummary($branchId)
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        
        // Today's figures
        $todayRevenue = $this->calculateNetRevenue($branchId, $today, $today);
        
        // This month's figures
        $monthRevenue = $this->calculateNetRevenue($branchId, $thisMonth);
        
        // Pending returns value
        $pendingReturnsValue = ReturnTransaction::where('branch_id', $branchId)
            ->where('status', 'menunggu') // Updated to Indonesian status
            ->sum('total');

        return [
            'today' => $todayRevenue,
            'month' => $monthRevenue,
            'pending_returns_value' => $pendingReturnsValue,
        ];
    }

    /**
     * Calculate damaged stock financial impact
     */
    public function calculateDamagedStockImpact($branchId, $startDate = null, $endDate = null)
    {
        $startDate = $startDate ?? Carbon::now()->startOfMonth();
        $endDate = $endDate ?? Carbon::now()->endOfMonth();

        $damagedValue = \App\Models\DamagedStock::where('branch_id', $branchId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->join('return_items', 'damaged_stocks.return_item_id', '=', 'return_items.id')
            ->sum('return_items.subtotal');

        return $damagedValue;
    }

    /**
     * Generate financial report data
     */
    public function generateFinancialReport($branchId, $startDate, $endDate)
    {
        $netRevenue = $this->calculateNetRevenue($branchId, $startDate, $endDate);
        $damagedValue = $this->calculateDamagedStockImpact($branchId, $startDate, $endDate);

        // Transaction count
        $transactionCount = Transaction::where('branch_id', $branchId)
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Return count
        $returnCount = ReturnTransaction::where('branch_id', $branchId)
            ->where('status', 'disetujui') // Updated to Indonesian status
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Average transaction value
        $avgTransactionValue = $transactionCount > 0 ? $netRevenue['total_sales'] / $transactionCount : 0;

        return [
            'revenue' => $netRevenue,
            'damaged_value' => $damagedValue,
            'transaction_count' => $transactionCount,
            'return_count' => $returnCount,
            'avg_transaction_value' => $avgTransactionValue,
            'total_financial_impact' => $netRevenue['net_revenue'] - $damagedValue,
        ];
    }
}
