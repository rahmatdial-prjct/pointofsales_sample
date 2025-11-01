@extends('layouts.app')

@section('title', 'Manajemen Transaksi')

@section('content')
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="page-title">
            <h1>Manajemen Transaksi</h1>
            <p>Kelola dan pantau semua transaksi di cabang Anda</p>
        </div>
        <div class="user-info">
            <div class="user-details">
                <div class="name">{{ Auth::user()->name }}</div>
                <div class="role">{{ ucfirst(Auth::user()->role) }}</div>
            </div>
            <div class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
        </div>
    </div>

<div class="container">

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon total">
                    <i class="fas fa-receipt"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ number_format($totalTransactions) }}</div>
                    <div class="stat-label">Total Transaksi</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon completed">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ number_format($completedTransactions) }}</div>
                    <div class="stat-label">Transaksi Selesai</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon revenue">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                    <div class="stat-label">Total Pendapatan</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon today">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</div>
                    <div class="stat-label">Pendapatan Hari Ini</div>
                </div>
            </div>
        </div>

        <!-- Action Header with Filters -->
        <div class="action-header">
            <div class="filters">
                <form method="GET" action="{{ route('manager.transactions.index') }}" class="filter-form">
                    <div class="filter-group">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari invoice/customer..." class="form-control">
                        <select name="status" class="form-control">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" placeholder="Dari Tanggal" class="form-control">
                        <input type="date" name="date_to" value="{{ request('date_to') }}" placeholder="Sampai Tanggal" class="form-control">
                        <button type="submit" class="btn btn-secondary btn-sm">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                            <a href="{{ route('manager.transactions.index') }}" class="btn btn-outline btn-sm">
                                <i class="fas fa-times"></i> Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>
            <div class="action-buttons">
                <a href="{{ route('manager.export.transactions', request()->query()) }}" class="btn btn-secondary">
                    <i class="fas fa-download"></i> Export CSV
                </a>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="card">
            <div class="card-header">
                <h2 class="heading-secondary">Daftar Transaksi</h2>
            </div>
            <div class="card-body">
            @if($transactions->count() > 0)
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No. Invoice</th>
                                <th>Pelanggan</th>
                                <th>Pegawai</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $transaction)
                                <tr>
                                    <td>
                                        <strong>{{ $transaction->invoice_number }}</strong>
                                    </td>
                                    <td>{{ $transaction->customer_name ?? 'Tidak ada' }}</td>
                                    <td>{{ $transaction->employee->name ?? 'Tidak ada' }}</td>
                                    <td>
                                        <strong>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</strong>
                                    </td>
                                    <td>
                                        <span class="status-badge status-{{ $transaction->status }}">
                                            @if($transaction->status === 'pending')
                                                Menunggu
                                            @elseif($transaction->status === 'completed')
                                                Selesai
                                            @elseif($transaction->status === 'cancelled')
                                                Dibatalkan
                                            @else
                                                {{ ucfirst($transaction->status) }}
                                            @endif
                                        </span>
                                    </td>
                                    <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('manager.transactions.show', $transaction) }}" class="btn btn-sm btn-primary" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="pagination-wrapper">
                    {{ $transactions->appends(request()->query())->links() }}
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-receipt"></i>
                    <p>Belum Ada Transaksi</p>
                    <p>Transaksi akan muncul di sini setelah pegawai melakukan penjualan.</p>
                </div>
            @endif
            </div>
        </div>
    </div>

<style>
.status-badge {
    padding: 4px 12px;
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

.action-buttons {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
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
</style>
@endsection
