@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="main-content">
    <div class="page-header">
        <div class="header-content">
            <h1 class="page-title">Detail Transaksi</h1>
            <p class="page-subtitle">{{ $transaction->invoice_number }}</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('manager.transactions.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="transaction-detail">
        <!-- Transaction Info -->
        <div class="card">
            <div class="card-header">
                <h2>Informasi Transaksi</h2>
                <span class="status-badge status-{{ $transaction->status }}">
                    {{ $transaction->status === 'completed' ? 'Selesai' : ($transaction->status === 'pending' ? 'Menunggu' : ($transaction->status === 'cancelled' ? 'Dibatalkan' : ucfirst($transaction->status))) }}
                </span>
            </div>
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <label>No. Invoice</label>
                        <value>{{ $transaction->invoice_number }}</value>
                    </div>
                    <div class="info-item">
                        <label>Tanggal</label>
                        <value>{{ $transaction->created_at->format('d/m/Y H:i:s') }}</value>
                    </div>
                    <div class="info-item">
                        <label>Pelanggan</label>
                        <value>{{ $transaction->customer_name ?? 'Tidak ada' }}</value>
                    </div>
                    <div class="info-item">
                        <label>Pegawai</label>
                        <value>{{ $transaction->employee->name ?? 'Tidak ada' }}</value>
                    </div>
                    <div class="info-item">
                        <label>Cabang</label>
                        <value>{{ $transaction->branch->name ?? 'Tidak ada' }}</value>
                    </div>
                    <div class="info-item">
                        <label>Metode Pembayaran</label>
                        <value>{{ ucfirst($transaction->payment_method) }}</value>
                    </div>
                </div>
                @if($transaction->notes)
                    <div class="notes-section">
                        <label>Catatan</label>
                        <div class="notes-content">{{ $transaction->notes }}</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Transaction Items -->
        <div class="card">
            <div class="card-header">
                <h2>Item Transaksi</h2>
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>SKU</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Diskon</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaction->items as $item)
                                <tr>
                                    <td>
                                        <div class="product-info">
                                            <div class="product-name">{{ $item->product->name ?? 'Tidak ada' }}</div>
                                        </div>
                                    </td>
                                    <td>{{ $item->product->sku ?? 'Tidak ada' }}</td>
                                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>
                                        @if($item->discount_amount > 0)
                                            {{ $item->discount_percentage }}% 
                                            (Rp {{ number_format($item->discount_amount, 0, ',', '.') }})
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <strong>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</strong>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Transaction Summary -->
        <div class="card">
            <div class="card-header">
                <h2>Ringkasan Pembayaran</h2>
            </div>
            <div class="card-body">
                <div class="summary-table">
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="summary-row">
                        <span>Diskon</span>
                        <span>- Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total</span>
                        <span>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.transaction-detail {
    max-width: 1000px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.info-item label {
    font-size: 12px;
    color: #718096;
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.info-item value {
    font-size: 16px;
    color: #2d3748;
    font-weight: 500;
}

.notes-section {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #e2e8f0;
}

.notes-section label {
    font-size: 12px;
    color: #718096;
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 0.5px;
    display: block;
    margin-bottom: 10px;
}

.notes-content {
    background: #f7fafc;
    padding: 15px;
    border-radius: 8px;
    color: #4a5568;
    line-height: 1.6;
}

.product-info {
    display: flex;
    flex-direction: column;
}

.product-name {
    font-weight: 600;
    color: #2d3748;
}

.summary-table {
    max-width: 400px;
    margin-left: auto;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 12px 0;
    border-bottom: 1px solid #e2e8f0;
}

.summary-row.total {
    border-bottom: none;
    border-top: 2px solid #2d3748;
    font-weight: 700;
    font-size: 18px;
    color: #2d3748;
}

.status-badge {
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.status-pending {
    background: #fef3cd;
    color: #856404;
}

.status-completed {
    background: #d1ecf1;
    color: #0c5460;
}

.status-cancelled {
    background: #f8d7da;
    color: #721c24;
}
</style>
@endsection
