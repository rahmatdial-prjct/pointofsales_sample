<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\ReturnTransaction;
use App\Models\ReturnItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{
    public function index()
    {
        if (!Auth::check() || !Auth::user()->isEmployee()) {
            abort(403, 'Unauthorized access.');
        }

        $branchId = Auth::user()->branch_id;
        $returns = ReturnTransaction::where('branch_id', $branchId)
            ->where('user_id', Auth::id())
            ->with(['returnItems.product', 'user', 'approver'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('employee.returns.index', compact('returns'));
    }

    public function create(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isEmployee()) {
            abort(403, 'Unauthorized access.');
        }

        $branchId = Auth::user()->branch_id;
        $transactionId = $request->query('transaction_id');
        $products = collect();
        $selectedTransaction = null;

        // Get recent transactions for return reference
        $recentTransactions = Transaction::where('branch_id', $branchId)
            ->where('status', 'completed')
            ->with(['items.product'])
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get();

        if ($transactionId) {
            $selectedTransaction = $recentTransactions->where('id', (int)$transactionId)->first();
            if ($selectedTransaction) {
                // Only products from the selected transaction
                $products = $selectedTransaction->items->map(function($item) {
                    return $item->product;
                })->filter()->unique('id')->values();
            }
        } else {
            // All products for general return
            $products = Product::where('branch_id', $branchId)
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        }

        return view('employee.returns.create', compact('products', 'recentTransactions', 'selectedTransaction'));
    }

    public function store(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isEmployee()) {
            abort(403, 'Unauthorized access.');
        }

        $branchId = Auth::user()->branch_id;

        $validated = $request->validate([
            'transaction_id' => 'nullable|exists:transactions,id',
            'reason' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.condition' => 'required|in:good,damaged,defective',
        ]);

        // If no transaction_id provided, create a general return
        if (!isset($validated['transaction_id'])) {
            $validated['transaction_id'] = null;
        }

        DB::beginTransaction();

        try {
            $originalTransaction = null;

            // Validate transaction if provided
            if ($validated['transaction_id']) {
                $originalTransaction = Transaction::where('id', $validated['transaction_id'])
                    ->where('branch_id', $branchId)
                    ->first();

                if (!$originalTransaction) {
                    throw new \Exception('Transaksi tidak ditemukan atau tidak berasal dari cabang ini.');
                }
            }

            // Generate return number
            $returnNumber = 'RET-' . date('Ymd') . '-' . str_pad(ReturnTransaction::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

            $returnTransaction = new ReturnTransaction();
            $returnTransaction->return_number = $returnNumber;
            $returnTransaction->branch_id = $branchId;
            $returnTransaction->user_id = Auth::id();
            $returnTransaction->transaction_id = $validated['transaction_id'];
            $returnTransaction->reason = $validated['reason'];
            $returnTransaction->status = 'menunggu'; // was 'pending'
            $returnTransaction->save();

            $totalRefundAmount = 0;
            foreach ($validated['items'] as $item) {
                $product = Product::find($item['product_id']);
                $refundAmount = 0;
                $price = 0;

                if ($originalTransaction) {
                    // Get original transaction item to calculate proper refund
                    $originalItem = TransactionItem::where('transaction_id', $validated['transaction_id'])
                        ->where('product_id', $item['product_id'])
                        ->first();

                    if (!$originalItem) {
                        throw new \Exception('Produk tidak ditemukan pada transaksi asli.');
                    }

                    if ($item['quantity'] > $originalItem->quantity) {
                        throw new \Exception('Jumlah retur tidak boleh melebihi jumlah pembelian asli.');
                    }

                    // Calculate refund amount based on original price with discount
                    $originalPriceWithDiscount = $originalItem->price - ($originalItem->discount_amount / $originalItem->quantity);
                    $refundAmount = $originalPriceWithDiscount * $item['quantity'];
                    $price = $originalPriceWithDiscount;
                } else {
                    // General return without specific transaction
                    $price = $product->base_price;
                    $refundAmount = $price * $item['quantity'];
                }

                $returnItem = new ReturnItem();
                $returnItem->return_transaction_id = $returnTransaction->id;
                $returnItem->product_id = $item['product_id'];
                $returnItem->quantity = $item['quantity'];
                $returnItem->price = $price;
                $returnItem->subtotal = $refundAmount;
                $returnItem->reason = $validated['reason'];
                $returnItem->condition = $item['condition'];
                $returnItem->save();

                $totalRefundAmount += $refundAmount;
            }

            $returnTransaction->total = $totalRefundAmount;
            $returnTransaction->save();

            DB::commit();

            return redirect()->route('employee.returns.index')
                ->with('success', 'Permintaan retur berhasil diajukan. Menunggu persetujuan manajer.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Gagal membuat permintaan retur: ' . $e->getMessage())->withInput();
        }
    }



    /**
     * Display the specified return request
     */
    public function show(ReturnTransaction $return)
    {
        if (!Auth::check() || !Auth::user()->isEmployee()) {
            abort(403, 'Unauthorized access.');
        }

        // Ensure employee can only view their own returns
        if ($return->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $return->load(['returnItems.product', 'branch']);

        return view('employee.returns.show', compact('return'));
    }
}
