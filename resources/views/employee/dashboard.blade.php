@extends('layouts.app')

@section('content')
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="page-title">
            <h1>Beranda Pegawai</h1>
            <p>Ringkasan aktivitas dan kinerja Anda</p>
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
                    <div class="stat-value">Rp {{ number_format($todaySales, 0, ',', '.') }}</div>
                    <div class="stat-label">Penjualan Hari Ini</div>
                    <div class="stat-change">
                        <i class="fas fa-clock"></i> {{ date('d M Y') }}
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon month">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">Rp {{ number_format($monthSales, 0, ',', '.') }}</div>
                    <div class="stat-label">Penjualan Bulan Ini</div>
                    <div class="stat-change">
                        <i class="fas fa-calendar"></i> {{ date('M Y') }}
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon transactions">
                    <i class="fas fa-receipt"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $todayTransactions }}</div>
                    <div class="stat-label">Transaksi Hari Ini</div>
                    <div class="stat-change">
                        <i class="fas fa-clock"></i> {{ date('d M Y') }}
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon branch">
                    <i class="fas fa-building"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $branchInfo->name ?? 'N/A' }}</div>
                    <div class="stat-label">Cabang Kerja</div>
                    <div class="stat-change">
                        <i class="fas fa-map-marker-alt"></i> {{ $branchInfo->operational_area ?? 'Area tidak tersedia' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <h2 class="heading-secondary">Aksi Cepat</h2>
            <div class="action-grid">
                <a href="{{ route('employee.transactions.create') }}" class="action-card primary">
                    <div class="action-icon">
                        <i class="fas fa-plus"></i>
                    </div>
                    <div class="action-content">
                        <div class="action-title">Transaksi Baru</div>
                        <div class="action-desc">Buat transaksi penjualan baru</div>
                    </div>
                </a>

                <a href="{{ route('employee.returns.create') }}" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-undo"></i>
                    </div>
                    <div class="action-content">
                        <div class="action-title">Proses Retur</div>
                        <div class="action-desc">Ajukan retur barang</div>
                    </div>
                </a>

                <a href="{{ route('employee.reports.integrated') }}" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div class="action-content">
                        <div class="action-title">Laporan Kinerja</div>
                        <div class="action-desc">Lihat performa penjualan Anda</div>
                    </div>
                </a>

                <a href="{{ route('employee.transactions.index') }}" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-list"></i>
                    </div>
                    <div class="action-content">
                        <div class="action-title">Riwayat Transaksi</div>
                        <div class="action-desc">Lihat semua transaksi Anda</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="card">
            <div class="card-header">
                <h2 class="heading-secondary">Transaksi Terbaru Anda</h2>
                <a href="{{ route('employee.transactions.index') }}" class="btn btn-secondary btn-sm">Lihat Semua</a>
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Faktur</th>
                                <th>Pelanggan</th>
                                <th>Total</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentTransactions as $transaction)
                            <tr>
                                <td>
                                    <div class="invoice-info">
                                        <div class="invoice-number">{{ $transaction->invoice_number }}</div>
                                    </div>
                                </td>
                                <td>{{ $transaction->customer_name ?? 'Pelanggan Langsung' }}</td>
                                <td>
                                    <div class="amount">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</div>
                                </td>
                                <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <span class="badge badge-{{ $transaction->status === 'completed' ? 'success' : 'warning' }}">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">
                                    <div class="empty-state">
                                        <i class="fas fa-receipt"></i>
                                        <p>Belum ada transaksi hari ini</p>
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
            </div>
        </div>

        <!-- Personal Performance Summary -->
        <div class="card">
            <div class="card-header">
                <h2 class="heading-secondary">📈 Ringkasan Kinerja Personal</h2>
            </div>
            <div class="card-body">
                <div class="performance-summary">
                    <div class="performance-item">
                        <div class="performance-icon today">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <div class="performance-content">
                            <div class="performance-title">Target Hari Ini</div>
                            <div class="performance-value">{{ $todayTransactions }} Transaksi</div>
                            <div class="performance-desc">Penjualan: Rp {{ number_format($todaySales, 0, ',', '.') }}</div>
                        </div>
                    </div>

                    <div class="performance-item">
                        <div class="performance-icon month">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="performance-content">
                            <div class="performance-title">Performa Bulan Ini</div>
                            <div class="performance-value">Rp {{ number_format($monthSales, 0, ',', '.') }}</div>
                            <div class="performance-desc">Rata-rata: Rp {{ number_format($todayTransactions > 0 ? $monthSales / max($todayTransactions, 1) : 0, 0, ',', '.') }} per transaksi</div>
                        </div>
                    </div>

                    <div class="performance-item">
                        <div class="performance-icon branch">
                            <i class="fas fa-building"></i>
                        </div>
                        <div class="performance-content">
                            <div class="performance-title">Lokasi Kerja</div>
                            <div class="performance-value">{{ $branchInfo->name ?? 'N/A' }}</div>
                            <div class="performance-desc">{{ $branchInfo->address ?? 'Alamat tidak tersedia' }}</div>
                        </div>
                    </div>

                    <div class="performance-item">
                        <div class="performance-icon achievement">
                            <i class="fas fa-target"></i>
                        </div>
                        <div class="performance-content">
                            <div class="performance-title">Target Harian</div>
                            <div class="performance-value">
                                {{ $todayTransactions }}/10 Transaksi
                                @if($todayTransactions >= 10)
                                    <span class="achievement-badge">✓ Tercapai</span>
                                @elseif($todayTransactions >= 7)
                                    <span class="progress-badge">Hampir Tercapai</span>
                                @else
                                    <span class="pending-badge">{{ 10 - $todayTransactions }} lagi</span>
                                @endif
                            </div>
                            <div class="performance-desc">
                                @if($todayTransactions >= 10)
                                    Excellent! Target harian tercapai
                                @elseif($todayTransactions >= 7)
                                    Bagus! Tinggal sedikit lagi
                                @elseif($todayTransactions >= 3)
                                    Keep going! Masih ada waktu
                                @else
                                    Mari mulai transaksi hari ini
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Metrics -->
        <div class="card">
            <div class="card-header">
                <h2 class="heading-secondary">📊 Metrik Kinerja</h2>
            </div>
            <div class="card-body">
                <div class="metrics-grid">
                    <div class="metric-card">
                        <div class="metric-icon efficiency">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="metric-content">
                            <div class="metric-title">Efisiensi Waktu</div>
                            <div class="metric-value">
                                @if($todayTransactions > 0)
                                    {{ round(8 / max($todayTransactions, 1), 1) }} jam/transaksi
                                @else
                                    - jam/transaksi
                                @endif
                            </div>
                            <div class="metric-desc">Rata-rata waktu per transaksi</div>
                        </div>
                    </div>

                    <div class="metric-card">
                        <div class="metric-icon revenue">
                            <i class="fas fa-coins"></i>
                        </div>
                        <div class="metric-content">
                            <div class="metric-title">Nilai Rata-rata</div>
                            <div class="metric-value">
                                @if($todayTransactions > 0)
                                    Rp {{ number_format($todaySales / $todayTransactions, 0, ',', '.') }}
                                @else
                                    Rp 0
                                @endif
                            </div>
                            <div class="metric-desc">Per transaksi hari ini</div>
                        </div>
                    </div>

                    <div class="metric-card">
                        <div class="metric-icon growth">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <div class="metric-content">
                            <div class="metric-title">Produktivitas</div>
                            <div class="metric-value">
                                @php
                                    $productivity = $todayTransactions > 0 ? round(($todayTransactions / 10) * 100) : 0;
                                @endphp
                                {{ $productivity }}%
                            </div>
                            <div class="metric-desc">Dari target harian (10 transaksi)</div>
                        </div>
                    </div>

                    <div class="metric-card">
                        <div class="metric-icon ranking">
                            <i class="fas fa-medal"></i>
                        </div>
                        <div class="metric-content">
                            <div class="metric-title">Level Kinerja</div>
                            <div class="metric-value">
                                @if($todayTransactions >= 15)
                                    Excellent
                                @elseif($todayTransactions >= 10)
                                    Good
                                @elseif($todayTransactions >= 5)
                                    Average
                                @else
                                    Starter
                                @endif
                            </div>
                            <div class="metric-desc">Berdasarkan transaksi hari ini</div>
                        </div>
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
            --card-color: #f59e0b;
            --card-color-light: #fbbf24;
        }
        .stat-card:nth-child(4) {
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

        .stat-change {
            font-size: 0.8rem;
            margin-top: 0.25rem;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            font-weight: 500;
            color: #6b7280;
            background: rgba(107, 114, 128, 0.1);
        }

        /* Modern Quick Actions */
        .quick-actions {
            margin-bottom: 3rem;
        }

        .heading-secondary {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .action-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-top: 1.5rem;
        }

        .action-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            gap: 1.5rem;
            text-decoration: none !important;
            color: inherit;
            transition: all 0.3s ease;
            border: 1px solid rgba(226, 232, 240, 0.3);
            position: relative;
            overflow: hidden;
        }

        .action-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .action-card:hover::before {
            left: 100%;
        }

        .action-card.primary {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            box-shadow: 0 8px 24px rgba(59, 130, 246, 0.3);
        }

        .action-card.primary .action-icon {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .action-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            text-decoration: none !important;
            color: inherit;
        }

        .action-card.primary:hover {
            color: white;
            box-shadow: 0 12px 32px rgba(59, 130, 246, 0.4);
        }

        .action-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .action-content {
            flex: 1;
        }

        .action-title {
            font-weight: 700;
            color: inherit;
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }

        .action-desc {
            font-size: 0.9rem;
            opacity: 0.8;
            font-weight: 500;
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

        .card-body {
            padding: 2rem;
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
            padding: 1rem 1.5rem;
            font-weight: 600;
            color: #374151;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        .table td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            color: #374151;
        }

        .table tr:hover {
            background: #f8fafc;
        }

        .invoice-info {
            display: flex;
            flex-direction: column;
        }

        .invoice-number {
            font-weight: 600;
            color: #1f2937;
        }

        .amount {
            font-weight: 700;
            color: #059669;
            font-size: 1.1rem;
        }

        .badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-success {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.2);
        }

        .badge-warning {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
            box-shadow: 0 2px 8px rgba(245, 158, 11, 0.2);
        }

        .badge-info {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            color: #1e40af;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.2);
        }

        /* Modern Performance Summary */
        .performance-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .performance-item {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            padding: 2rem;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 16px;
            border: 1px solid rgba(226, 232, 240, 0.3);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .performance-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .performance-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .performance-icon.today {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        .performance-icon.month {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        }
        .performance-icon.branch {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        }
        .performance-icon.achievement {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .performance-content {
            flex: 1;
        }

        .performance-title {
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }

        .performance-value {
            font-size: 1.5rem;
            font-weight: 800;
            color: #059669;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .performance-desc {
            font-size: 0.9rem;
            color: #6b7280;
            font-weight: 500;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #6b7280;
            background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
            border-radius: 16px;
            border: 2px dashed #e2e8f0;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            color: #d1d5db;
        }

        .empty-state p {
            margin-bottom: 1rem;
        }

        .text-center {
            text-align: center;
        }

        /* Button Styling */
        .btn {
            text-decoration: none !important;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
            color: white;
            padding: 0.5rem 1rem;
            border: none;
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, #4b5563 0%, #374151 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(107, 114, 128, 0.3);
        }

        .btn-sm {
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
        }

        /* Achievement Badges */
        .achievement-badge {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 0.5rem;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .progress-badge {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 0.5rem;
        }

        .pending-badge {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 0.5rem;
        }

        /* Metrics Grid */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .metric-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            padding: 1.5rem;
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            gap: 1rem;
            border: 1px solid rgba(226, 232, 240, 0.3);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .metric-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, var(--metric-color) 0%, var(--metric-color-light) 100%);
        }

        .metric-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .metric-card:nth-child(1) {
            --metric-color: #3b82f6;
            --metric-color-light: #60a5fa;
        }
        .metric-card:nth-child(2) {
            --metric-color: #10b981;
            --metric-color-light: #34d399;
        }
        .metric-card:nth-child(3) {
            --metric-color: #f59e0b;
            --metric-color-light: #fbbf24;
        }
        .metric-card:nth-child(4) {
            --metric-color: #8b5cf6;
            --metric-color-light: #a78bfa;
        }

        .metric-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: white;
            background: linear-gradient(135deg, var(--metric-color) 0%, var(--metric-color-light) 100%);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .metric-content {
            flex: 1;
        }

        .metric-title {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.25rem;
            font-size: 0.9rem;
        }

        .metric-value {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }

        .metric-desc {
            font-size: 0.8rem;
            color: #6b7280;
            font-weight: 500;
        }
    </style>
@endsection
