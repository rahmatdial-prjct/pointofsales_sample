<?php
declare(strict_types=1);

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Transaction;
use App\Services\FinancialService;

class ReportController extends Controller
{
    protected $financialService;

    public function __construct(FinancialService $financialService)
    {
        $this->financialService = $financialService;
    }

    public function keuangan(Request $request)
    {
        $branchId = Auth::user()->branch_id;

        $startDate = $request->input('start_date', now()->subDays(30)->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        // Calculate net revenue (gross sales - approved returns)
        $financialData = $this->financialService->calculateNetRevenue(
            $branchId,
            $startDate,
            $endDate
        );

        // Get transactions for chart data
        $transactions = Transaction::where('branch_id', $branchId)
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at')
            ->get();

        return view('manager.reports.keuangan', compact(
            'financialData',
            'startDate',
            'endDate',
            'transactions'
        ));
    }
}

