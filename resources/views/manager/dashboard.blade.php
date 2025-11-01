@extends('layouts.app')

@section('content')
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="page-title">
            <h1>Beranda Manajer</h1>
            <p>Ringkasan kinerja cabang toko Anda</p>
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
        <!-- Period Filter -->
        <div class="filter-section">
            <div class="filter-card">
                <div class="filter-header">
                    <h3>📊 Filter Periode</h3>
                    <span class="current-period">{{ $periodeLabel }}</span>
                </div>
                <form method="GET" action="{{ route('manager.dashboard') }}" class="filter-form">
                    <div class="filter-options">
                        <button type="submit" name="periode" value="hari_ini"
                                class="filter-btn {{ $periode === 'hari_ini' ? 'active' : '' }}">
                            <i class="fas fa-calendar-day"></i>
                            Hari Ini
                        </button>
                        <button type="submit" name="periode" value="minggu_ini"
                                class="filter-btn {{ $periode === 'minggu_ini' ? 'active' : '' }}">
                            <i class="fas fa-calendar-week"></i>
                            Minggu Ini
                        </button>
                        <button type="submit" name="periode" value="bulan_ini"
                                class="filter-btn {{ $periode === 'bulan_ini' ? 'active' : '' }}">
                            <i class="fas fa-calendar"></i>
                            Bulan Ini
                        </button>
                        <button type="submit" name="periode" value="bulan_kemarin"
                                class="filter-btn {{ $periode === 'bulan_kemarin' ? 'active' : '' }}">
                            <i class="fas fa-calendar-minus"></i>
                            Bulan Kemarin
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon sales">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">Rp {{ number_format($periodSales, 0, ',', '.') }}</div>
                    <div class="stat-label">Pendapatan Bersih</div>
                    <div class="stat-change">
                        @if($periodReturns > 0)
                            <i class="fas fa-arrow-down"></i> Setelah dikurangi retur
                        @else
                            <i class="fas fa-info-circle"></i> Belum ada retur dalam periode ini
                        @endif
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon gross-sales">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">Rp {{ number_format($periodGrossSales, 0, ',', '.') }}</div>
                    <div class="stat-label">Penjualan Kotor</div>
                    <div class="stat-change">
                        @if($periodReturns > 0)
                            <i class="fas fa-arrow-up"></i> Sebelum dikurangi retur
                        @else
                            <i class="fas fa-info-circle"></i> Belum ada retur dalam periode ini
                        @endif
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon products">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $totalProducts }}</div>
                    <div class="stat-label">Total Produk</div>
                    <div class="stat-change positive">
                        <i class="fas fa-check-circle"></i> {{ $activeProducts }} Aktif
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon employees">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $totalEmployees }}</div>
                    <div class="stat-label">Total Pegawai</div>
                    <div class="stat-change">
                        <i class="fas fa-user-check"></i> Di Cabang Ini
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon returns">
                    <i class="fas fa-undo"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $pendingReturns }}</div>
                    <div class="stat-label">Retur Pending {{ $periodeLabel }}</div>
                    <div class="stat-change {{ $pendingReturns > 0 ? 'negative' : 'positive' }}">
                        <i class="fas fa-{{ $pendingReturns > 0 ? 'clock' : 'check' }}"></i>
                        {{ $pendingReturns > 0 ? 'Perlu Approval' : 'Semua Clear' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <h2 class="heading-secondary">Aksi Cepat</h2>
            <div class="action-grid">
                <a href="{{ route('manager.products.index') }}" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="action-content">
                        <div class="action-title">Kelola Produk</div>
                        <div class="action-desc">Manajemen stok dan produk</div>
                    </div>
                </a>

                <a href="{{ route('manager.employees.index') }}" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="action-content">
                        <div class="action-title">Kelola Pegawai</div>
                        <div class="action-desc">Manajemen pegawai cabang</div>
                    </div>
                </a>

                <a href="{{ route('manager.returns.index') }}" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-undo"></i>
                    </div>
                    <div class="action-content">
                        <div class="action-title">Kelola Retur</div>
                        <div class="action-desc">Proses return dan refund</div>
                    </div>
                </a>

                <a href="{{ route('manager.reports.integrated') }}" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <div class="action-content">
                        <div class="action-title">Laporan Terintegrasi</div>
                        <div class="action-desc">Analisis lengkap semua aspek cabang</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="card">
            <div class="card-header">
                <h2 class="heading-secondary">Transaksi Terbaru</h2>
                <a href="{{ route('manager.transactions.index') }}" class="btn btn-secondary btn-sm">Lihat Semua</a>
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Faktur</th>
                                <th>Pelanggan</th>
                                <th>Pegawai</th>
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
                                <td>{{ $transaction->user->name ?? 'Unknown' }}</td>
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
                                <td colspan="6" class="text-center">
                                    <div class="empty-state">
                                        <i class="fas fa-receipt"></i>
                                        <p>Belum ada transaksi hari ini</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Branch Performance Summary -->
        <div class="card">
            <div class="card-header">
                <h2 class="heading-secondary">📊 Ringkasan Performa Cabang</h2>
            </div>
            <div class="card-body">
                <div class="performance-grid">
                    <div class="performance-item">
                        <div class="performance-icon sales">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="performance-content">
                            <div class="performance-title">Pendapatan Bersih Hari Ini</div>
                            <div class="performance-value">Rp {{ number_format($todaySales, 0, ',', '.') }}</div>
                            <div class="performance-desc">Penjualan kotor dikurangi retur</div>
                        </div>
                    </div>

                    <div class="performance-item">
                        <div class="performance-icon products">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="performance-content">
                            <div class="performance-title">Produk Aktif</div>
                            <div class="performance-value">{{ $activeProducts }} / {{ $totalProducts }}</div>
                            <div class="performance-desc">Produk siap dijual</div>
                        </div>
                    </div>

                    <div class="performance-item">
                        <div class="performance-icon team">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="performance-content">
                            <div class="performance-title">Tim Cabang</div>
                            <div class="performance-value">{{ $totalEmployees }} Pegawai</div>
                            <div class="performance-desc">Siap melayani pelanggan</div>
                        </div>
                    </div>

                    @if($pendingReturns > 0)
                    <div class="performance-item alert">
                        <div class="performance-icon returns">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="performance-content">
                            <div class="performance-title">Return Pending</div>
                            <div class="performance-value">{{ $pendingReturns }} Item</div>
                            <div class="performance-desc">
                                <a href="{{ route('manager.returns.index') }}" class="action-link">
                                    Perlu approval segera
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Top Employees Section -->
        <div class="card">
            <div class="card-header">
                <h2 class="heading-secondary">🏆 Pegawai Terbaik Bulan Ini</h2>
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

        /* Filter Section */
        .filter-section {
            margin-bottom: 2rem;
        }

        .filter-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(226, 232, 240, 0.3);
        }

        .filter-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .filter-header h3 {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
        }

        .current-period {
            background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .filter-form {
            margin: 0;
        }

        .filter-options {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .filter-btn {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #64748b;
            padding: 0.75rem 1.25rem;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none !important;
        }

        .filter-btn:hover {
            background: #e2e8f0;
            border-color: #cbd5e1;
            color: #475569;
            transform: translateY(-1px);
        }

        .filter-btn.active {
            background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
            border-color: #3b82f6;
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .filter-btn.active:hover {
            background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
            transform: translateY(-1px);
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
        .stat-card:nth-child(4) {
            --card-color: #f59e0b;
            --card-color-light: #fbbf24;
        }
        .stat-card:nth-child(5) {
            --card-color: #ef4444;
            --card-color-light: #f87171;
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
        }

        .stat-change.positive {
            color: #059669;
            background: rgba(16, 185, 129, 0.1);
        }
        .stat-change.negative {
            color: #dc2626;
            background: rgba(239, 68, 68, 0.1);
        }
        .stat-change:not(.positive):not(.negative) {
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

        .action-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            text-decoration: none !important;
            color: inherit;
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
            color: #1f2937;
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }

        .action-desc {
            font-size: 0.9rem;
            color: #6b7280;
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

        /* Modern Performance Grid */
        .performance-grid {
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

        .performance-item.alert {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-color: #f59e0b;
            box-shadow: 0 4px 16px rgba(245, 158, 11, 0.2);
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

        .performance-icon.sales {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        .performance-icon.products {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        }
        .performance-icon.team {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        }
        .performance-icon.returns {
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

        .action-link {
            color: #dc2626;
            text-decoration: none !important;
            font-weight: 600;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            background: rgba(220, 38, 38, 0.1);
            transition: all 0.3s ease;
        }

        .action-link:hover {
            color: #991b1b;
            background: rgba(220, 38, 38, 0.2);
            text-decoration: none !important;
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
    </style>
@endsection
