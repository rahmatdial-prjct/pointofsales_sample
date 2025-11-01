@extends('layouts.app')

@section('content')
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="page-title">
            <h1>Detail Transaksi</h1>
            <p>Informasi lengkap transaksi {{ $transaction->invoice_number }}</p>
        </div>
        <div class="action-buttons">
            <a href="{{ route('employee.transactions.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('employee.transactions.invoice', $transaction) }}" class="btn btn-primary" target="_blank">
                <i class="fas fa-print"></i> Cetak Faktur
            </a>
        </div>
    </div>

    <div class="container">
        <!-- Transaction Info Card -->
        <div class="card">
            <div class="card-header">
                <h2 class="heading-secondary">Informasi Transaksi</h2>
                <span class="status-badge {{ $transaction->status === 'completed' ? 'success' : 'warning' }}">
                    {{ $transaction->status === 'completed' ? 'Selesai' : ($transaction->status === 'pending' ? 'Menunggu' : ucfirst($transaction->status)) }}
                </span>
            </div>
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <label>Nomor Invoice</label>
                        <value>{{ $transaction->invoice_number }}</value>
                    </div>
                    <div class="info-item">
                        <label>Pelanggan</label>
                        <value>{{ $transaction->customer_name }}</value>
                    </div>
                    <div class="info-item">
                        <label>Tanggal</label>
                        <value>{{ $transaction->created_at->format('d/m/Y H:i') }}</value>
                    </div>
                    <div class="info-item">
                        <label>Metode Pembayaran</label>
                        <value>{{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}</value>
                    </div>
                    <div class="info-item">
                        <label>Cabang</label>
                        <value>{{ $transaction->branch->name ?? 'Tidak diketahui' }}</value>
                    </div>
                    <div class="info-item">
                        <label>Pegawai</label>
                        <value>{{ $transaction->user->name ?? 'Tidak diketahui' }}</value>
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
                <h2 class="heading-secondary">Item Transaksi</h2>
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Produk</th>
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
                                        <div class="product-name">{{ $item->product->name }}</div>
                                        <div class="product-sku">SKU: {{ $item->product->sku }}</div>
                                    </div>
                                </td>
                                <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>
                                    @if($item->discount_percentage > 0)
                                        {{ $item->discount_percentage }}%
                                        <small>(Rp {{ number_format($item->discount_amount, 0, ',', '.') }})</small>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
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
                <h2 class="heading-secondary">Ringkasan Pembayaran</h2>
            </div>
            <div class="card-body">
                <div class="summary-grid">
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($transaction->subtotal ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="summary-row">
                        <span>Total Diskon</span>
                        <span>- Rp {{ number_format($transaction->discount_amount ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total Bayar</span>
                        <span>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Remove all underlines globally */
        * {
            text-decoration: none !important;
        }

        a, button, .btn, .nav-item, .action-link, .action-card {
            text-decoration: none !important;
        }

        a:hover, button:hover, .btn:hover, .nav-item:hover, .action-link:hover, .action-card:hover {
            text-decoration: none !important;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .info-item label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-item value {
            font-size: 1rem;
            font-weight: 500;
            color: #1f2937;
        }

        .notes-section {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
        }

        .notes-section label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
            display: block;
        }

        .notes-content {
            background: #f9fafb;
            padding: 1rem;
            border-radius: 8px;
            color: #374151;
            font-style: italic;
        }

        .product-info {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .product-name {
            font-weight: 600;
            color: #1f2937;
        }

        .product-sku {
            font-size: 0.75rem;
            color: #6b7280;
        }

        .summary-grid {
            max-width: 400px;
            margin-left: auto;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .summary-row.total {
            border-bottom: none;
            border-top: 2px solid #1f2937;
            font-weight: 700;
            font-size: 1.125rem;
            color: #1f2937;
            margin-top: 0.5rem;
            padding-top: 1rem;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-badge.success {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.2);
        }

        .status-badge.warning {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
            box-shadow: 0 2px 8px rgba(245, 158, 11, 0.2);
        }

        .btn {
            text-decoration: none !important;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            padding: 0.75rem 1.5rem;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.3);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
            color: white;
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, #4b5563 0%, #374151 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(107, 114, 128, 0.3);
        }
    </style>
@endsection
