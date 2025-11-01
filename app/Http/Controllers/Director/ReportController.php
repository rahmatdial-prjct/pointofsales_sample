<?php

namespace App\Http\Controllers\Director;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{


    public function keuangan(Request $request)
    {
        // Get all branches for dropdown
        $branches = Branch::all();

        // Get selected branch_id from request or default to first branch
        $branchId = $request->input('branch_id', $branches->first() ? $branches->first()->id : null);

        // Default date range: last 30 days
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->toDateString());

        $reportData = null;

        if ($branchId) {
            $branch = Branch::find($branchId);

            if ($branch) {
                // Filter transactions by branch and date range
                $transactions = Transaction::where('branch_id', $branch->id)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->get();

                // Calculate financial metrics using FinancialService
                $financialData = app(FinancialService::class)->calculateNetRevenue(
                    $branchId,
                    $startDate,
                    $endDate
                );

                $totalGrossIncome = $financialData['total_sales'];
                $totalReturns = $financialData['total_returns'];
                $netRevenue = $financialData['net_revenue'];
                $returnRate = $financialData['return_rate'];

                // Prepare data for line chart: daily income
                $dailyIncome = [];
                foreach ($transactions as $transaction) {
                    $date = $transaction->created_at->format('Y-m-d');
                    if (!isset($dailyIncome[$date])) {
                        $dailyIncome[$date] = 0;
                    }
                    $dailyIncome[$date] += $transaction->total_amount;
                }

                $reportData = [
                    'branch' => $branch,
                    'transactions' => $transactions,
                    'totalGrossIncome' => $totalGrossIncome,
                    'totalReturns' => $totalReturns,
                    'netRevenue' => $netRevenue,
                    'returnRate' => $returnRate,
                    'dailyIncome' => $dailyIncome,
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                ];
            }
        }

        return view('director.reports.keuangan', compact('reportData', 'branches', 'startDate', 'endDate', 'branchId'));
    }
}
