<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\ReturnTransaction;
use App\Models\Product;
use App\Models\DamagedStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{
    public function __construct()
    {
        // Middleware applied in routes/web.php
    }

    /**
     * Display a listing of return requests for manager's branch
     */
    public function index()
    {
        $branchId = Auth::user()->branch_id;
        
        $returns = ReturnTransaction::where('branch_id', $branchId)
            ->with(['employee', 'returnItems.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $pendingCount = ReturnTransaction::where('branch_id', $branchId)
            ->where('status', 'menunggu') // was 'pending'
            ->count();

        return view('manager.returns.index', compact('returns', 'pendingCount'));
    }

    /**
     * Display the specified return request
     */
    public function show(ReturnTransaction $return)
    {
        // Ensure manager can only view returns from their branch
        if ($return->branch_id !== Auth::user()->branch_id) {
            abort(403, 'Unauthorized access.');
        }

        $return->load(['employee', 'returnItems.product', 'branch']);

        return view('manager.returns.show', compact('return'));
    }

    /**
     * Approve a return request and update stock
     */
    public function approve(ReturnTransaction $return)
    {
        // Ensure manager can only approve returns from their branch
        if ($return->branch_id !== Auth::user()->branch_id) {
            abort(403, 'Unauthorized access.');
        }

        if ($return->status !== 'menunggu') { // was 'pending'
            return back()->withErrors(['error' => 'Permintaan retur tidak dalam status menunggu persetujuan.']);
        }

        DB::beginTransaction();

        try {
            // Update return status
            $return->status = 'disetujui'; // was 'approved'
            $return->approved_by = Auth::id();
            $return->approved_at = now();
            $return->save();

            // Update stock and financial records for each returned item
            foreach ($return->returnItems as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    // Only add to sellable stock if condition is 'good'
                    if ($item->condition === 'good') {
                        $oldStock = $product->stock;
                        $product->stock += $item->quantity;
                        $product->save();

                        // Log stock change for monitoring
                        \Log::info('Stock returned (good condition)', [
                            'product_id' => $product->id,
                            'product_name' => $product->name,
                            'old_stock' => $oldStock,
                            'new_stock' => $product->stock,
                            'quantity_returned' => $item->quantity,
                            'return_id' => $return->id,
                            'approved_by' => Auth::id()
                        ]);
                    } else {
                        // Track damaged/defective items separately
                        DamagedStock::create([
                            'branch_id' => $return->branch_id,
                            'product_id' => $item->product_id,
                            'return_item_id' => $item->id,
                            'quantity' => $item->quantity,
                            'condition' => $item->condition,
                            'reason' => $item->reason,
                        ]);
                    }
                }
            }

            // Update original transaction's financial impact
            $originalTransaction = $return->transaction;
            if ($originalTransaction) {
                // Reduce the total amount of original transaction
                $originalTransaction->total_amount -= $return->total;
                $originalTransaction->save();
            }

            DB::commit();

            return redirect()->route('manager.returns.index')
                ->with('success', 'Permintaan retur berhasil disetujui. Stok telah diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyetujui retur: ' . $e->getMessage()]);
        }
    }

    /**
     * Reject a return request
     */
    public function reject(ReturnTransaction $return)
    {
        // Ensure manager can only reject returns from their branch
        if ($return->branch_id !== Auth::user()->branch_id) {
            abort(403, 'Unauthorized access.');
        }

        if ($return->status !== 'menunggu') { // was 'pending'
            return back()->withErrors(['error' => 'Permintaan retur tidak dalam status menunggu persetujuan.']);
        }

        try {
            $return->status = 'ditolak'; // was 'rejected'
            $return->approved_by = Auth::id();
            $return->approved_at = now();
            $return->save();

            return redirect()->route('manager.returns.index')
                ->with('success', 'Permintaan retur berhasil ditolak.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menolak retur: ' . $e->getMessage()]);
        }
    }
}
