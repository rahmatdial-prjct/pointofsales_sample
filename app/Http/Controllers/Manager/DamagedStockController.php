<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\DamagedStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class DamagedStockController extends Controller
{
    public function __construct()
    {
        // Middleware applied in routes/web.php
    }

    /**
     * Display a listing of damaged stock items for manager's branch
     */
    public function index(Request $request)
    {
        $branchId = Auth::user()->branch_id;
        $periode = $request->get('periode', 'bulan_ini');
        $today = Carbon::today();
        $start = $end = null;

        switch ($periode) {
            case 'hari_ini':
                $start = $today;
                $end = $today->copy()->endOfDay();
                $periodeLabel = 'Hari Ini';
                break;
            case 'minggu_ini':
                $start = $today->copy()->startOfWeek();
                $end = $today->copy()->endOfWeek();
                $periodeLabel = 'Minggu Ini';
                break;
            case 'bulan_ini':
                $start = $today->copy()->startOfMonth();
                $end = $today->copy()->endOfMonth();
                $periodeLabel = 'Bulan Ini';
                break;
            case 'tahun_ini':
                $start = $today->copy()->startOfYear();
                $end = $today->copy()->endOfYear();
                $periodeLabel = 'Tahun Ini';
                break;
            case 'minggu_kemarin':
                $start = $today->copy()->subWeek()->startOfWeek();
                $end = $today->copy()->subWeek()->endOfWeek();
                $periodeLabel = 'Minggu Kemarin';
                break;
            case 'bulan_kemarin':
                $start = $today->copy()->subMonth()->startOfMonth();
                $end = $today->copy()->subMonth()->endOfMonth();
                $periodeLabel = 'Bulan Kemarin';
                break;
            case 'tahun_kemarin':
                $start = $today->copy()->subYear()->startOfYear();
                $end = $today->copy()->subYear()->endOfYear();
                $periodeLabel = 'Tahun Kemarin';
                break;
            default:
                $start = $today->copy()->startOfMonth();
                $end = $today->copy()->endOfMonth();
                $periodeLabel = 'Bulan Ini';
        }

        $damagedStocks = DamagedStock::where('branch_id', $branchId)
            ->whereBetween('created_at', [$start, $end])
            ->with(['product', 'returnItem'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $pendingCount = DamagedStock::where('branch_id', $branchId)
            ->where('action_taken', null)
            ->whereBetween('created_at', [$start, $end])
            ->count();

        $totalDamagedValue = DamagedStock::where('branch_id', $branchId)
            ->whereBetween('created_at', [$start, $end])
            ->with('returnItem')
            ->get()
            ->sum(function($item) {
                return $item->returnItem->subtotal ?? 0;
            });

        return view('manager.damaged-stock.index', compact('damagedStocks', 'pendingCount', 'totalDamagedValue', 'periode', 'periodeLabel'));
    }

    /**
     * Display the specified damaged stock item
     */
    public function show(DamagedStock $damagedStock)
    {
        // Ensure manager can only view damaged stock from their branch
        if ($damagedStock->branch_id !== Auth::user()->branch_id) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $damagedStock->load(['product', 'returnItem.returnTransaction', 'disposedBy']);

        return view('manager.damaged-stock.show', compact('damagedStock'));
    }

    /**
     * Take action on damaged stock (repair, dispose, return to supplier)
     */
    public function takeAction(Request $request, DamagedStock $damagedStock)
    {
        \Log::info('takeAction called', ['damagedStock' => $damagedStock->id, 'request' => $request->all()]);

        // Ensure manager can only take action on damaged stock from their branch
        if ($damagedStock->branch_id !== Auth::user()->branch_id) {
            \Log::warning('Unauthorized access attempt in takeAction', ['user_id' => Auth::id(), 'damagedStock' => $damagedStock->id]);
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'action_taken' => 'required|in:repair,dispose,return_to_supplier',
            'notes' => 'sometimes|nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            $damagedStock->action_taken = $validated['action_taken'];
            $damagedStock->notes = $validated['notes'] ?? null;
            
            if ($validated['action_taken'] === 'dispose') {
                $damagedStock->disposed_at = now();
                $damagedStock->disposed_by = Auth::id();
            } elseif ($validated['action_taken'] === 'repair') {
                // If repaired, add back to sellable stock
                $product = $damagedStock->product;
                $product->stock += $damagedStock->quantity;
                $product->save();
                
                $damagedStock->disposed_at = now();
                $damagedStock->disposed_by = Auth::id();
            }
            
            $damagedStock->save();

            DB::commit();

            \Log::info('takeAction success', ['damagedStock' => $damagedStock->id, 'action_taken' => $damagedStock->action_taken]);

            return redirect()->route('manager.damaged-stock.index')
                ->with('success', 'Tindakan berhasil dilakukan pada barang rusak.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('takeAction failed', ['error' => $e->getMessage(), 'damagedStock' => $damagedStock->id]);
            return back()->withErrors(['error' => 'Gagal melakukan tindakan: ' . $e->getMessage()]);
        }
    }

    /**
     * Get damaged stock statistics for dashboard
     */
    public function getStats()
    {
        $branchId = Auth::user()->branch_id;
        
        return [
            'pending_count' => DamagedStock::where('branch_id', $branchId)->pending()->count(),
            'total_damaged_value' => DamagedStock::where('branch_id', $branchId)
                ->join('return_items', 'damaged_stocks.return_item_id', '=', 'return_items.id')
                ->sum('return_items.subtotal'),
            'disposed_count' => DamagedStock::where('branch_id', $branchId)->disposed()->count(),
        ];
    }
}
