@extends('layouts.app')

@section('title', 'Dashboard Direktur')

@section('content')
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="page-title">
            <h1>Dashboard Direktur</h1>
            <p>Ringkasan kinerja seluruh cabang toko</p>
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
                <div class="stat-icon branches">
                    <i class="fas fa-store"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $totalBranches ?? 0 }}</div>
                    <div class="stat-label">Total Cabang</div>
                    <div class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> Aktif
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon sales">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">Rp {{ number_format($totalNetRevenue ?? 0, 0, ',', '.') }}</div>
                    <div class="stat-label">Pendapatan Bersih</div>
                    <div class="stat-change">
                        <i class="fas fa-calculator"></i> Kotor - Retur
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon gross">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">Rp {{ number_format($totalGrossRevenue ?? 0, 0, ',', '.') }}</div>
                    <div class="stat-label">Pendapatan Kotor</div>
                    <div class="stat-change">
                        <i class="fas fa-chart-bar"></i> Total Penjualan
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon returns">
                    <i class="fas fa-undo"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">Rp {{ number_format($totalReturns ?? 0, 0, ',', '.') }}</div>
                    <div class="stat-label">Total Retur</div>
                    <div class="stat-change">
                        <i class="fas fa-exclamation-triangle"></i> Retur Diterima
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon transactions">
                    <i class="fas fa-receipt"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $recentTransactions->count() }}</div>
                    <div class="stat-label">Transaksi Terbaru</div>
                    <div class="stat-change">
                        <i class="fas fa-clock"></i> 5 Terakhir
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon performance">
                    <i class="fas fa-trophy"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ number_format(($totalNetRevenue ?? 0) / max($totalBranches ?? 1, 1), 0, ',', '.') }}</div>
                    <div class="stat-label">Rata-rata Bersih per Cabang</div>
                    <div class="stat-change">
                        <i class="fas fa-calculator"></i> Performance
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <h2 class="heading-secondary">Aksi Cepat</h2>
            <div class="action-grid">
                <a href="{{ route('director.branches.index') }}" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="action-content">
                        <div class="action-title">Kelola Cabang</div>
                        <div class="action-desc">Tambah dan kelola cabang toko</div>
                    </div>
                </a>

                <a href="{{ route('director.users.index') }}" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="action-content">
                        <div class="action-title">Kelola Pengguna</div>
                        <div class="action-desc">Manajemen user dan role</div>
                    </div>
                </a>

                <a href="{{ route('director.reports.integrated') }}" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <div class="action-content">
                        <div class="action-title">Laporan Terintegrasi</div>
                        <div class="action-desc">Analisis lengkap semua aspek bisnis</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="card">
            <div class="card-header">
                <h2 class="heading-secondary">Aktivitas Terbaru</h2>
                <a href="{{ route('director.reports.integrated') }}" class="btn btn-secondary btn-sm">Lihat Semua</a>
            </div>
            <div class="card-body">
                <div class="activity-list">
                    @forelse ($recentTransactions as $transaction)
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Transaksi Baru</div>
                            <div class="activity-desc">
                                Invoice: {{ $transaction->invoice_number ?? '#' . $transaction->id }}
                                di {{ $transaction->branch ? $transaction->branch->name : 'Unknown Branch' }}
                            </div>
                            <div class="activity-amount">Rp {{ number_format($transaction->total_amount ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <div class="activity-time">{{ $transaction->created_at->diffForHumans() }}</div>
                    </div>
                    @empty
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p>Belum ada aktivitas terbaru</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Top Employees Section -->
        <div class="card">
            <div class="card-header">
                <h2 class="heading-secondary">🏆 Pegawai Terbaik Bulan Ini (Semua Cabang)</h2>
            </div>
            <div class="card-body">
                @if($topEmployees->count() > 0)
                <div class="employees-grid">
                    @foreach($topEmployees as $index => $employee)
                    <div class="employee-card rank-{{ $index + 1 }}">
                        <div class="employee-rank">
                            @if($index === 0)
                                <i class="fas fa-crown"></i>
                            @elseif($index === 1)
                                <i class="fas fa-medal"></i>
                            @else
                                <i class="fas fa-award"></i>
                            @endif
                            <span class="rank-number">#{{ $index + 1 }}</span>
                        </div>
                        <div class="employee-info">
                            <div class="employee-avatar">
                                {{ strtoupper(substr($employee->name, 0, 2)) }}
                            </div>
                            <div class="employee-details">
                                <div class="employee-name">{{ $employee->name }}</div>
                                <div class="employee-branch">{{ $employee->branch->name ?? 'Unknown Branch' }}</div>
                                <div class="employee-stats">
                                    <div class="stat-item">
                                        <span class="stat-value">{{ $employee->transaction_count }}</span>
                                        <span class="stat-label">Transaksi</span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="stat-value">Rp {{ number_format($employee->total_sales ?? 0, 0, ',', '.') }}</span>
                                        <span class="stat-label">Total Penjualan</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="empty-state">
                    <i class="fas fa-users"></i>
                    <p>Belum ada data penjualan pegawai bulan ini</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        /* Top Employees Styling */
        .employees-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 1.5rem;
        }

        .employee-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid rgba(226, 232, 240, 0.3);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .employee-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--rank-color);
        }

        .employee-card.rank-1 {
            --rank-color: linear-gradient(90deg, #fbbf24 0%, #f59e0b 100%);
            border-color: #fbbf24;
            box-shadow: 0 8px 24px rgba(251, 191, 36, 0.2);
        }

        .employee-card.rank-2 {
            --rank-color: linear-gradient(90deg, #9ca3af 0%, #6b7280 100%);
            border-color: #9ca3af;
            box-shadow: 0 6px 20px rgba(156, 163, 175, 0.15);
        }

        .employee-card.rank-3 {
            --rank-color: linear-gradient(90deg, #cd7c2f 0%, #a16207 100%);
            border-color: #cd7c2f;
            box-shadow: 0 4px 16px rgba(205, 124, 47, 0.15);
        }

        .employee-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.1);
        }

        .employee-rank {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
            font-size: 1.1rem;
            font-weight: 700;
        }

        .employee-card.rank-1 .employee-rank {
            color: #f59e0b;
        }

        .employee-card.rank-2 .employee-rank {
            color: #6b7280;
        }

        .employee-card.rank-3 .employee-rank {
            color: #a16207;
        }

        .rank-number {
            font-size: 0.9rem;
            font-weight: 600;
        }

        .employee-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .employee-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.2rem;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .employee-details {
            flex: 1;
        }

        .employee-name {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }

        .employee-branch {
            font-size: 0.85rem;
            color: #6b7280;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .employee-stats {
            display: flex;
            gap: 1rem;
        }

        .stat-item {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .stat-value {
            font-size: 0.9rem;
            font-weight: 700;
            color: #059669;
        }

        .stat-label {
            font-size: 0.75rem;
            color: #6b7280;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: #9ca3af;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-state p {
            font-size: 1.1rem;
            font-weight: 500;
        }
    </style>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> {{-- Include Chart.js library --}}
    @vite(['resources/js/director-dashboard-charts.js']) {{-- Include dashboard specific script --}}
@endpush
