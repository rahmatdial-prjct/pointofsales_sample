@extends('layouts.app')

@section('content')
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="page-title">
            <h1>Riwayat Transaksi</h1>
            <p>Daftar semua transaksi yang Anda buat</p>
        </div>
        <div class="user-info">
            <div class="user-details">
                <div class="name">{{ Auth::user()->name }}</div>
                <div class="role">
                    @if(Auth::user()->role === 'director')
                        Direktur
                    @elseif(Auth::user()->role === 'manager')
                        Manajer
                    @elseif(Auth::user()->role === 'employee')
                        Pegawai
                    @else
                        {{ ucfirst(Auth::user()->role) }}
                    @endif
                </div>
            </div>
            <div class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
        </div>
    </div>

    <div class="container">
        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon today">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">Rp {{ number_format($todaySales ?? 0, 0, ',', '.') }}</div>
                    <div class="stat-label">Penjualan Hari Ini</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon month">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">Rp {{ number_format($monthSales ?? 0, 0, ',', '.') }}</div>
                    <div class="stat-label">Penjualan Bulan Ini</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon total">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">Rp {{ number_format($totalSales ?? 0, 0, ',', '.') }}</div>
                    <div class="stat-label">Total Penjualan</div>
                </div>
            </div>
        </div>

        <!-- Action Header with Filters -->
        <div class="action-header">
            <div class="filters">
                <form method="GET" class="filter-form">
                    <div class="filter-group">
                        <input type="date" name="date_from" value="{{ request('date_from') }}" placeholder="Dari Tanggal" class="form-control">
                        <input type="date" name="date_to" value="{{ request('date_to') }}" placeholder="Sampai Tanggal" class="form-control">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor invoice atau nama pelanggan..." class="form-control" id="searchInput">
                        <button type="submit" class="btn btn-secondary btn-sm">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        @if(request()->hasAny(['date_from', 'date_to', 'search']))
                            <a href="{{ route('employee.transactions.index') }}" class="btn btn-outline btn-sm">
                                <i class="fas fa-times"></i> Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>
            <a href="{{ route('employee.transactions.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Transaksi Baru
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Transactions Table -->
        <div class="card">
            <div class="card-header">
                <h2 class="heading-secondary">Daftar Transaksi</h2>
            </div>
            
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nomor Invoice</th>
                            <th>Pelanggan</th>
                            <th>Jumlah Item</th>
                            <th>Total</th>
                            <th>Pembayaran</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                            <tr class="transaction-row">
                                <td>
                                    <div class="invoice-info">
                                        <div class="invoice-number">{{ $transaction->invoice_number }}</div>
                                    </div>
                                </td>
                                <td class="customer-name">{{ $transaction->customer_name ?? 'Pelanggan Umum' }}</td>
                                <td>
                                    <div class="items-count">
                                        {{ $transaction->items->count() }} produk
                                    </div>
                                </td>
                                <td>
                                    <div class="amount">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</div>
                                </td>
                                <td>
                                    <span class="payment-method">
                                        @if($transaction->payment_method === 'cash')
                                            Tunai
                                        @elseif($transaction->payment_method === 'credit')
                                            Kredit
                                        @else
                                            {{ ucfirst($transaction->payment_method) }}
                                        @endif
                                    </span>
                                </td>
                                <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('employee.transactions.show', $transaction) }}" class="btn btn-sm btn-primary" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('employee.transactions.invoice', $transaction) }}" class="btn btn-sm btn-secondary" title="Invoice" target="_blank">
                                            <i class="fas fa-file-invoice"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    <div class="empty-state">
                                        <i class="fas fa-receipt"></i>
                                        <p>Belum ada transaksi</p>
                                        <a href="{{ route('employee.transactions.create') }}" class="btn btn-primary">
                                            Buat Transaksi Pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination-wrapper">
                {{ $transactions->links() }}
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

        /* Modern Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            gap: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--card-color) 0%, var(--card-color-light) 100%);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
        }

        .stat-card:nth-child(1) {
            --card-color: #10b981;
            --card-color-light: #34d399;
        }
        .stat-card:nth-child(2) {
            --card-color: #3b82f6;
            --card-color-light: #60a5fa;
        }
        .stat-card:nth-child(3) {
            --card-color: #8b5cf6;
            --card-color-light: #a78bfa;
        }

        .stat-icon {
            width: 70px;
            height: 70px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            color: white;
            background: linear-gradient(135deg, var(--card-color) 0%, var(--card-color-light) 100%);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        }

        .stat-content {
            flex: 1;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            color: #1f2937;
            line-height: 1;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-label {
            color: #6b7280;
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }



        .invoice-info {
            display: flex;
            flex-direction: column;
        }

        .invoice-number {
            font-weight: 600;
            color: #1f2937;
        }

        .items-count {
            font-size: 0.875rem;
            color: #6b7280;
        }

        .amount {
            font-weight: 600;
            color: #059669;
        }

        .payment-method {
            padding: 0.25rem 0.75rem;
            background: #f3f4f6;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 500;
            color: #374151;
        }

        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .badge-success {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }

        .empty-state {
            padding: 3rem 1rem;
            text-align: center;
            color: #6b7280;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #d1d5db;
        }

        .empty-state p {
            margin-bottom: 1rem;
            font-size: 1.125rem;
        }

        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 0.375rem;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
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

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
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

        .btn-outline {
            background: transparent;
            color: #6b7280;
            border: 1px solid #d1d5db;
        }

        .btn-outline:hover {
            background: #f9fafb;
            color: #374151;
            border-color: #9ca3af;
        }

        /* Modern Card Styling */
        .card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            transition: all 0.3s ease;
            margin-bottom: 2rem;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
        }

        .card-header {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            padding: 1.5rem 2rem;
            border-bottom: 1px solid rgba(226, 232, 240, 0.5);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .heading-secondary {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
            background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .table-container {
            overflow-x: auto;
            border-radius: 12px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 12px;
            overflow: hidden;
        }

        .table th {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: #374151;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e5e7eb;
        }

        .table td {
            padding: 1rem;
            border-bottom: 1px solid #f3f4f6;
            color: #1f2937;
        }

        .table tbody tr:hover {
            background: #f9fafb;
        }

        .text-center {
            text-align: center;
        }

        /* Filter Form Styling */
        .filter-form {
            margin-bottom: 2rem;
        }

        .filter-group {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .form-control {
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            background: white;
        }

        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .action-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .filters {
            flex: 1;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .filter-group {
                flex-direction: column;
                align-items: stretch;
            }

            .action-header {
                flex-direction: column;
                align-items: stretch;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Real-time search functionality
            const searchInput = document.getElementById('searchInput');
            const transactionRows = document.querySelectorAll('.transaction-row');

            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    const searchTerm = e.target.value.toLowerCase().trim();

                    transactionRows.forEach(row => {
                        const invoiceNumber = row.querySelector('.invoice-number').textContent.toLowerCase();
                        const customerName = row.querySelector('.customer-name').textContent.toLowerCase();

                        if (invoiceNumber.includes(searchTerm) || customerName.includes(searchTerm)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    // Show/hide empty state
                    const visibleRows = Array.from(transactionRows).filter(row => row.style.display !== 'none');
                    const emptyState = document.querySelector('.empty-state');

                    if (visibleRows.length === 0 && searchTerm !== '') {
                        // Create temporary "no results" message if it doesn't exist
                        let noResultsRow = document.getElementById('no-results-row');
                        if (!noResultsRow) {
                            noResultsRow = document.createElement('tr');
                            noResultsRow.id = 'no-results-row';
                            noResultsRow.innerHTML = `
                                <td colspan="7" class="text-center">
                                    <div class="empty-state">
                                        <i class="fas fa-search"></i>
                                        <p>Tidak ada transaksi yang cocok dengan pencarian "${searchTerm}"</p>
                                    </div>
                                </td>
                            `;
                            document.querySelector('tbody').appendChild(noResultsRow);
                        } else {
                            noResultsRow.querySelector('p').textContent = `Tidak ada transaksi yang cocok dengan pencarian "${searchTerm}"`;
                            noResultsRow.style.display = '';
                        }
                    } else {
                        const noResultsRow = document.getElementById('no-results-row');
                        if (noResultsRow) {
                            noResultsRow.style.display = 'none';
                        }
                    }
                });
            }
        });
    </script>
@endsection
