<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class ExportController extends Controller
{
    /**
     * Export users to CSV
     */
    public function exportUsers(Request $request)
    {
        $users = User::with('branch')->get();
        
        $filename = 'daftar_pengguna_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // Headers
            fputcsv($file, [
                'Nama',
                'Email', 
                'Role',
                'Cabang',
                'Status',
                'Tanggal Bergabung'
            ]);
            
            // Data
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->name,
                    $user->email,
                    ucfirst($user->role),
                    $user->branch ? $user->branch->name : 'Tidak ada',
                    $user->is_active ? 'Aktif' : 'Tidak Aktif',
                    $user->created_at->format('d/m/Y H:i')
                ]);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }
    
    /**
     * Export branches to CSV
     */
    public function exportBranches(Request $request)
    {
        $branches = Branch::withCount(['users', 'transactions'])->get();
        
        $filename = 'daftar_cabang_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($branches) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // Headers
            fputcsv($file, [
                'Nama Cabang',
                'Wilayah Operasional',
                'Alamat',
                'Telepon',
                'Email',
                'Jumlah Pegawai',
                'Total Transaksi',
                'Tanggal Dibuat'
            ]);
            
            // Data
            foreach ($branches as $branch) {
                fputcsv($file, [
                    $branch->name,
                    $branch->operational_area ?? '-',
                    $branch->address,
                    $branch->phone ?? '-',
                    $branch->email ?? '-',
                    $branch->users_count,
                    $branch->transactions_count,
                    $branch->created_at->format('d/m/Y H:i')
                ]);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }
    
    /**
     * Export products to CSV (for managers)
     */
    public function exportProducts(Request $request)
    {
        $branchId = Auth::user()->branch_id;
        $products = Product::where('branch_id', $branchId)
            ->with('category')
            ->get();
        
        $filename = 'daftar_produk_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($products) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // Headers
            fputcsv($file, [
                'SKU',
                'Nama Produk',
                'Kategori',
                'Harga',
                'Stok',
                'Status',
                'Deskripsi',
                'Tanggal Dibuat'
            ]);
            
            // Data
            foreach ($products as $product) {
                fputcsv($file, [
                    $product->sku,
                    $product->name,
                    $product->category ? $product->category->name : '-',
                    'Rp ' . number_format($product->base_price, 0, ',', '.'),
                    $product->stock,
                    $product->is_active ? 'Aktif' : 'Tidak Aktif',
                    $product->description ?? '-',
                    $product->created_at->format('d/m/Y H:i')
                ]);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }
    
    /**
     * Export transactions to CSV
     */
    public function exportTransactions(Request $request)
    {
        $user = Auth::user();
        
        // Build query based on user role
        $query = Transaction::with(['user', 'branch', 'transactionItems.product']);
        
        if ($user->role === 'manager') {
            $query->where('branch_id', $user->branch_id);
        } elseif ($user->role === 'employee') {
            $query->where('user_id', $user->id);
        }
        
        // Apply date filters if provided
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        $transactions = $query->latest()->get();
        
        $filename = 'daftar_transaksi_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // Headers
            fputcsv($file, [
                'No. Faktur',
                'Tanggal',
                'Pelanggan',
                'Pegawai',
                'Cabang',
                'Total Amount',
                'Metode Pembayaran',
                'Status',
                'Catatan'
            ]);
            
            // Data
            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->invoice_number,
                    $transaction->created_at->format('d/m/Y H:i'),
                    $transaction->customer_name ?? 'Pelanggan Langsung',
                    $transaction->user ? $transaction->user->name : '-',
                    $transaction->branch ? $transaction->branch->name : '-',
                    'Rp ' . number_format($transaction->total_amount, 0, ',', '.'),
                    ucfirst($transaction->payment_method),
                    ucfirst($transaction->status),
                    $transaction->notes ?? '-'
                ]);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }
}
