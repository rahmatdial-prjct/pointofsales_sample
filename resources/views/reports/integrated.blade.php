@extends('layouts.app')

@section('content')
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="page-title">
            <h1>Laporan Terintegrasi</h1>
            <p>
                Dashboard analisis kinerja dan keuangan {{ $role === 'director' ? 'perusahaan' : ($role === 'manager' ? 'cabang' : 'personal') }} -
                @switch($period ?? 'this_month')
                    @case('today')
                        <strong>Hari Ini</strong>
                        @break
                    @case('yesterday')
                        <strong>Kemarin</strong>
                        @break
                    @case('this_week')
                        <strong>Minggu Ini</strong>
                        @break
                    @case('last_week')
                        <strong>Minggu Lalu</strong>
                        @break
                    @case('this_month')
                        <strong>Bulan Ini</strong>
                        @break
                    @case('last_month')
                        <strong>Bulan Lalu</strong>
                        @break
                    @case('this_year')
                        <strong>Tahun Ini</strong>
                        @break
                    @case('last_year')
                        <strong>Tahun Lalu</strong>
                        @break
                    @default
                        <strong>Bulan Ini</strong>
                @endswitch
            </p>
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
        <!-- Filter Section -->
        <div class="card mb-6">
            <div class="card-header">
                <h2 class="heading-secondary">Filter Laporan</h2>
            </div>
            <div class="card-body">
                    <div class="filter-grid">
                        @if($role === 'director')
                        <div class="form-group">
                            <label for="branch_id">Cabang</label>
                            <select id="branch_id" name="branch_id" class="form-control">
                                <option value="">Semua Cabang</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ $branchId == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div class="form-group">
                            <label>Periode Laporan</label>
                            <div class="period-buttons">
                                <a href="{{ route(auth()->user()->role . '.reports.integrated', ['period' => 'today'] + request()->except('period')) }}"
                                   class="period-btn {{ request('period') == 'today' ? 'active' : '' }}">
                                    Hari Ini
                                </a>
                                <a href="{{ route(auth()->user()->role . '.reports.integrated', ['period' => 'yesterday'] + request()->except('period')) }}"
                                   class="period-btn {{ request('period') == 'yesterday' ? 'active' : '' }}">
                                    Kemarin
                                </a>
                                <a href="{{ route(auth()->user()->role . '.reports.integrated', ['period' => 'this_week'] + request()->except('period')) }}"
                                   class="period-btn {{ request('period') == 'this_week' ? 'active' : '' }}">
                                    Minggu Ini
                                </a>
                                <a href="{{ route(auth()->user()->role . '.reports.integrated', ['period' => 'last_week'] + request()->except('period')) }}"
                                   class="period-btn {{ request('period') == 'last_week' ? 'active' : '' }}">
                                    Minggu Lalu
                                </a>
                                <a href="{{ route(auth()->user()->role . '.reports.integrated', ['period' => 'this_month'] + request()->except('period')) }}"
                                   class="period-btn {{ request('period', 'this_month') == 'this_month' ? 'active' : '' }}">
                                    Bulan Ini
                                </a>
                                <a href="{{ route(auth()->user()->role . '.reports.integrated', ['period' => 'last_month'] + request()->except('period')) }}"
                                   class="period-btn {{ request('period') == 'last_month' ? 'active' : '' }}">
                                    Bulan Lalu
                                </a>
                                <a href="{{ route(auth()->user()->role . '.reports.integrated', ['period' => 'this_year'] + request()->except('period')) }}"
                                   class="period-btn {{ request('period') == 'this_year' ? 'active' : '' }}">
                                    Tahun Ini
                                </a>
                                <a href="{{ route(auth()->user()->role . '.reports.integrated', ['period' => 'last_year'] + request()->except('period')) }}"
                                   class="period-btn {{ request('period') == 'last_year' ? 'active' : '' }}">
                                    Tahun Lalu
                                </a>
                            </div>
                        </div>
                    </div>
            </div>
        </div>

        <!-- Key Metrics Overview -->
        <div class="metrics-grid">
            <!-- Financial Metrics -->
            <div class="metric-card revenue">
                <div class="metric-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="metric-content">
                    <div class="metric-value">Rp {{ number_format($financialData['net_revenue'] ?? 0, 0, ',', '.') }}</div>
                    <div class="metric-label">Pendapatan Bersih</div>
                    <div class="metric-change">
                        @if(isset($financialData['total_returns']) && $financialData['total_returns'] > 0)
                            <i class="fas fa-info-circle"></i>
                            <small>Sudah dikurangi return: Rp {{ number_format($financialData['total_returns'], 0, ',', '.') }}</small>
                        @else
                            <i class="fas fa-check-circle"></i>
                            <small>Belum ada retur dalam periode ini</small>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Gross Sales -->
            <div class="metric-card sales">
                <div class="metric-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="metric-content">
                    <div class="metric-value">Rp {{ number_format($financialData['total_sales'] ?? 0, 0, ',', '.') }}</div>
                    <div class="metric-label">Penjualan Kotor</div>
                    <div class="metric-change positive">
                        <i class="fas fa-arrow-up"></i>
                        Sebelum dikurangi retur
                    </div>
                </div>
            </div>

            <div class="metric-card transactions">
                <div class="metric-icon">
                    <i class="fas fa-receipt"></i>
                </div>
                <div class="metric-content">
                    <div class="metric-value">{{ number_format($financialData['total_transactions'] ?? 0) }}</div>
                    <div class="metric-label">Total Transaksi</div>
                    <div class="metric-change">
                        Avg: Rp {{ number_format($financialData['avg_transaction_value'] ?? 0, 0, ',', '.') }}
                    </div>
                </div>
            </div>

            @if($returnData)
            <div class="metric-card returns">
                <div class="metric-icon">
                    <i class="fas fa-undo"></i>
                </div>
                <div class="metric-content">
                    <div class="metric-value">{{ $returnData['total_returns'] ?? 0 }}</div>
                    <div class="metric-label">Total Return</div>
                    <div class="metric-change">
                        <i class="fas fa-info-circle"></i>
                        Rp {{ number_format($returnData['total_return_value'] ?? 0, 0, ',', '.') }}
                    </div>
                </div>
            </div>
            @endif

            @if($returnData && $returnData['approved_returns'] > 0)
            <div class="metric-card returns-approved">
                <div class="metric-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="metric-content">
                    <div class="metric-value">{{ $returnData['approved_returns'] ?? 0 }}</div>
                    <div class="metric-label">Return Approved</div>
                    <div class="metric-change negative">
                        <i class="fas fa-minus-circle"></i>
                        Rp {{ number_format($returnData['approved_return_value'] ?? 0, 0, ',', '.') }}
                    </div>
                </div>
            </div>
            @endif

            @if($inventoryData)
            <div class="metric-card inventory">
                <div class="metric-icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="metric-content">
                    <div class="metric-value">{{ $inventoryData['total_products'] ?? 0 }}</div>
                    <div class="metric-label">Total Produk</div>
                    <div class="metric-change positive">
                        <i class="fas fa-check-circle"></i> Tersedia
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Charts Section -->
        <div class="charts-grid">
            <!-- Revenue Trend Chart -->
            <div class="card">
                <div class="card-header">
                    <h3 class="heading-tertiary">Tren Pendapatan</h3>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="300"></canvas>
                </div>
            </div>

            <!-- Payment Methods Chart -->
            <div class="card">
                <div class="card-header">
                    <h3 class="heading-tertiary">Metode Pembayaran</h3>
                </div>
                <div class="card-body">
                    <canvas id="paymentChart" height="300"></canvas>
                </div>
            </div>
        </div>

        @if($role !== 'employee')
        <!-- Top Products Section -->
        <div class="card">
            <div class="card-header">
                <h3 class="heading-tertiary">🏆 Produk Terlaris</h3>
            </div>
            <div class="card-body">
                @if(isset($performanceData['top_products']) && count($performanceData['top_products']) > 0)
                <div class="top-products-grid">
                    @foreach($performanceData['top_products'] as $index => $product)
                    <div class="product-card rank-{{ $index + 1 }}">
                        <div class="product-rank">
                            @if($index === 0)
                                <i class="fas fa-crown"></i>
                            @elseif($index === 1)
                                <i class="fas fa-medal"></i>
                            @else
                                <i class="fas fa-award"></i>
                            @endif
                            <span class="rank-number">#{{ $index + 1 }}</span>
                        </div>
                        <div class="product-content">
                            <div class="product-image">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                                @else
                                    <div class="no-image">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="product-details">
                                <div class="product-name">{{ $product->name }}</div>
                                <div class="product-sku">SKU: {{ $product->sku ?? 'N/A' }}</div>
                                <div class="product-category">{{ $product->category->name ?? 'Tanpa Kategori' }}</div>
                                <div class="product-stats">
                                    <div class="stat-item">
                                        <span class="stat-value">{{ $product->total_quantity }}</span>
                                        <span class="stat-label">Unit Terjual</span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="stat-value">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</span>
                                        <span class="stat-label">Total Pendapatan</span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="stat-value">Rp {{ number_format($product->base_price ?? 0, 0, ',', '.') }}</span>
                                        <span class="stat-label">Harga Satuan</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="empty-state">
                    <i class="fas fa-box-open"></i>
                    <p>Belum ada data produk terlaris untuk periode ini</p>
                </div>
                @endif
            </div>
        </div>
        @endif

        @if($role === 'employee')
        <!-- Employee Personal Performance -->
        <div class="card">
            <div class="card-header">
                <h3 class="heading-tertiary">Kinerja Personal Anda</h3>
            </div>
            <div class="card-body">
                <div class="employee-performance-grid">
                    <div class="performance-stat">
                        <div class="stat-value">Rp {{ number_format($performanceData['employee_stats']['total_sales'], 0, ',', '.') }}</div>
                        <div class="stat-label">Total Penjualan</div>
                    </div>
                    <div class="performance-stat">
                        <div class="stat-value">{{ $performanceData['employee_stats']['transaction_count'] }}</div>
                        <div class="stat-label">Jumlah Transaksi</div>
                    </div>
                    <div class="performance-stat">
                        <div class="stat-value">Rp {{ number_format($performanceData['employee_stats']['avg_per_transaction'], 0, ',', '.') }}</div>
                        <div class="stat-label">Rata-rata per Transaksi</div>
                    </div>
                </div>

                <div class="daily-performance-chart">
                    <canvas id="dailyPerformanceChart" height="200"></canvas>
                </div>
            </div>
        </div>
        @endif



        @if($role === 'director' && $performanceData['branch_performance'])
        <!-- Branch Comparison -->
        <div class="card">
            <div class="card-header">
                <h3 class="heading-tertiary">Perbandingan Cabang</h3>
            </div>
            <div class="card-body">
                <div class="branch-comparison">
                    @foreach($performanceData['branch_performance'] as $branch)
                    <div class="branch-item">
                        <div class="branch-info">
                            <div class="branch-name">{{ $branch['name'] }}</div>
                            <div class="branch-stats">{{ $branch['transaction_count'] }} transaksi</div>
                        </div>
                        <div class="branch-revenue">
                            Rp {{ number_format($branch['total_sales'], 0, ',', '.') }}
                        </div>
                        <div class="branch-avg">
                            Avg: Rp {{ number_format($branch['avg_transaction'], 0, ',', '.') }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Revenue Trend Chart
        @if($financialData['period_totals'])
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueData = @json($financialData['period_totals']);
        const revenueLabels = Object.keys(revenueData);
        const revenueValues = Object.values(revenueData).map(item => item.gross_sales);

        // Calculate dynamic Y-axis range
        const maxValue = Math.max(...revenueValues);
        const minValue = Math.min(...revenueValues);
        const range = maxValue - minValue;
        const padding = range * 0.1; // 10% padding

        // Smart Y-axis calculation for UMKM
        let suggestedMin = Math.max(0, minValue - padding);
        let suggestedMax = maxValue + padding;

        // Round to nice numbers for better readability
        if (suggestedMax > 1000000) {
            suggestedMax = Math.ceil(suggestedMax / 100000) * 100000; // Round to nearest 100k
            suggestedMin = Math.floor(suggestedMin / 100000) * 100000;
        } else if (suggestedMax > 100000) {
            suggestedMax = Math.ceil(suggestedMax / 10000) * 10000; // Round to nearest 10k
            suggestedMin = Math.floor(suggestedMin / 10000) * 10000;
        } else if (suggestedMax > 10000) {
            suggestedMax = Math.ceil(suggestedMax / 1000) * 1000; // Round to nearest 1k
            suggestedMin = Math.floor(suggestedMin / 1000) * 1000;
        }

        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: revenueLabels,
                datasets: [{
                    label: 'Pendapatan Kotor',
                    data: revenueValues,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Pendapatan: Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        min: suggestedMin,
                        max: suggestedMax,
                        ticks: {
                            callback: function(value) {
                                // Format currency with smart abbreviations for UMKM
                                if (value >= 1000000) {
                                    return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                                } else if (value >= 1000) {
                                    return 'Rp ' + (value / 1000).toFixed(0) + 'K';
                                } else {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            },
                            maxTicksLimit: 6 // Limit number of ticks for cleaner look
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
        @endif

        // Payment Methods Chart
        @if($financialData['payment_methods'])
        const paymentCtx = document.getElementById('paymentChart').getContext('2d');
        const paymentData = @json($financialData['payment_methods']);
        const paymentLabels = Object.keys(paymentData).map(method => {
            const methodNames = {
                'cash': 'Tunai',
                'transfer': 'Transfer',
                'qris': 'QRIS',
                'other': 'Lainnya'
            };
            return methodNames[method] || method;
        });
        const paymentValues = Object.values(paymentData).map(item => item.total);

        new Chart(paymentCtx, {
            type: 'doughnut',
            data: {
                labels: paymentLabels,
                datasets: [{
                    data: paymentValues,
                    backgroundColor: [
                        '#10b981',
                        '#3b82f6',
                        '#8b5cf6',
                        '#f59e0b'
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': Rp ' + context.parsed.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
        @endif

        @if($role === 'employee' && $performanceData['daily_performance'])
        // Employee Daily Performance Chart
        const dailyCtx = document.getElementById('dailyPerformanceChart').getContext('2d');
        const dailyData = @json($performanceData['daily_performance']);
        const dailyLabels = Object.keys(dailyData);
        const dailyValues = Object.values(dailyData);

        // Calculate dynamic Y-axis for employee performance
        const dailyMaxValue = Math.max(...dailyValues);
        const dailyMinValue = Math.min(...dailyValues);
        const dailyRange = dailyMaxValue - dailyMinValue;
        const dailyPadding = dailyRange * 0.1;

        let dailySuggestedMin = Math.max(0, dailyMinValue - dailyPadding);
        let dailySuggestedMax = dailyMaxValue + dailyPadding;

        // Round to nice numbers
        if (dailySuggestedMax > 100000) {
            dailySuggestedMax = Math.ceil(dailySuggestedMax / 10000) * 10000;
            dailySuggestedMin = Math.floor(dailySuggestedMin / 10000) * 10000;
        } else if (dailySuggestedMax > 10000) {
            dailySuggestedMax = Math.ceil(dailySuggestedMax / 1000) * 1000;
            dailySuggestedMin = Math.floor(dailySuggestedMin / 1000) * 1000;
        }

        new Chart(dailyCtx, {
            type: 'bar',
            data: {
                labels: dailyLabels,
                datasets: [{
                    label: 'Penjualan Harian',
                    data: dailyValues,
                    backgroundColor: '#10b981',
                    borderColor: '#059669',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Penjualan: Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        min: dailySuggestedMin,
                        max: dailySuggestedMax,
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) {
                                    return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                                } else if (value >= 1000) {
                                    return 'Rp ' + (value / 1000).toFixed(0) + 'K';
                                } else {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            },
                            maxTicksLimit: 5
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
        @endif
    });
    </script>

    <style>
        /* Remove all underlines globally */
        * {
            text-decoration: none !important;
        }

        a, button, .btn, .nav-item, .action-link {
            text-decoration: none !important;
        }

        a:hover, button:hover, .btn:hover, .nav-item:hover, .action-link:hover {
            text-decoration: none !important;
        }

        /* Modern Filter Section */
        .filter-form {
            background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
            padding: 2rem;
            border-radius: 16px;
            border: 1px solid rgba(59, 130, 246, 0.1);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .period-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 8px;
        }

        .period-btn {
            padding: 6px 12px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            color: #64748b;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .period-btn:hover {
            background: #e2e8f0;
            color: #475569;
            text-decoration: none;
        }

        .period-btn.active {
            background: #3b82f6;
            border-color: #3b82f6;
            color: white;
        }

        .period-btn.active:hover {
            background: #2563eb;
            border-color: #2563eb;
            color: white;
        }

        .form-group {
            position: relative;
        }

        .form-group label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.75rem;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control {
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 0.875rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        }

        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        .filter-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            align-items: center;
        }

        .btn {
            padding: 0.875rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
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

        /* Modern Metrics Grid */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .metric-card {
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

        .metric-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--card-color) 0%, var(--card-color-light) 100%);
        }

        .metric-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
        }

        .metric-card.revenue {
            --card-color: #3b82f6;
            --card-color-light: #60a5fa;
        }
        .metric-card.sales {
            --card-color: #10b981;
            --card-color-light: #34d399;
        }
        .metric-card.transactions {
            --card-color: #059669;
            --card-color-light: #10b981;
        }
        .metric-card.returns {
            --card-color: #f59e0b;
            --card-color-light: #fbbf24;
        }
        .metric-card.returns-approved {
            --card-color: #ef4444;
            --card-color-light: #f87171;
        }
        .metric-card.inventory {
            --card-color: #8b5cf6;
            --card-color-light: #a78bfa;
        }

        .metric-icon {
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

        .metric-content {
            flex: 1;
        }

        .metric-value {
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

        .metric-label {
            color: #6b7280;
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .metric-change {
            font-size: 0.8rem;
            margin-top: 0.25rem;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            font-weight: 500;
        }

        .metric-change.positive {
            color: #059669;
            background: rgba(16, 185, 129, 0.1);
        }
        .metric-change.negative {
            color: #dc2626;
            background: rgba(239, 68, 68, 0.1);
        }

        /* Modern Charts Grid */
        .charts-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            margin-bottom: 3rem;
        }

        @media (max-width: 1024px) {
            .charts-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .charts-grid {
                grid-template-columns: 1fr;
            }
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
        }

        .heading-tertiary {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .card-body {
            padding: 2rem;
        }

        .performance-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        @media (max-width: 768px) {
            .performance-grid {
                grid-template-columns: 1fr;
            }
        }

        .performance-section {
            background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
            padding: 2rem;
            border-radius: 16px;
            border: 1px solid rgba(226, 232, 240, 0.5);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
        }

        .section-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .employee-list, .product-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .employee-item, .product-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.5rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(226, 232, 240, 0.3);
            transition: all 0.3s ease;
        }

        .employee-item:hover, .product-item:hover {
            transform: translateX(4px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }

        .employee-info, .product-info {
            flex: 1;
        }

        .employee-name, .product-name {
            font-weight: 600;
            color: #1f2937;
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }

        .employee-stats, .product-stats {
            font-size: 0.875rem;
            color: #6b7280;
        }

        .employee-revenue, .product-revenue {
            font-weight: 700;
            color: #059669;
            font-size: 1.1rem;
        }

        .employee-performance-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .performance-stat {
            text-align: center;
            padding: 2rem 1rem;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 16px;
            border: 1px solid rgba(226, 232, 240, 0.3);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .performance-stat:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .performance-stat .stat-value {
            font-size: 2rem;
            font-weight: 800;
            color: #1f2937;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .performance-stat .stat-label {
            color: #6b7280;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .return-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .return-stat {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            padding: 1.5rem;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 16px;
            border: 1px solid rgba(226, 232, 240, 0.3);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .return-stat:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .return-stat .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .return-stat .stat-icon.pending {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }
        .return-stat .stat-icon.approved {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        .return-stat .stat-icon.rejected {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }

        .return-stat .stat-icon.total-value {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        }

        .return-stat .stat-icon.current-month {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
        }

        .return-stat .stat-content {
            flex: 1;
        }

        .return-stat .stat-number {
            font-size: 1.5rem;
            font-weight: 800;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }

        .return-stat .stat-label {
            color: #6b7280;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .return-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .return-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.5rem;
            background: white;
            border-radius: 12px;
            border: 1px solid rgba(226, 232, 240, 0.3);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .return-item:hover {
            transform: translateX(4px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }

        .return-info {
            flex: 1;
        }

        .return-number {
            font-weight: 600;
            color: #1f2937;
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }

        .return-date {
            font-size: 0.875rem;
            color: #6b7280;
        }

        .return-amount {
            font-weight: 700;
            color: #059669;
            font-size: 1.1rem;
            margin-right: 1rem;
        }

        .return-employee {
            font-size: 0.8rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }

        .return-status {
            min-width: 80px;
        }

        /* Header Info */
        .header-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .total-returns {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        /* Empty State */
        .empty-return-state {
            text-align: center;
            padding: 3rem 2rem;
            background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
            border-radius: 16px;
            border: 2px dashed #e2e8f0;
        }

        .empty-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
        }

        .empty-return-state h4 {
            color: #374151;
            margin-bottom: 0.5rem;
            font-size: 1.25rem;
        }

        .empty-return-state p {
            color: #6b7280;
            margin-bottom: 1.5rem;
        }

        .empty-actions {
            display: flex;
            justify-content: center;
            gap: 1rem;
        }

        .branch-comparison {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .branch-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 16px;
            border: 1px solid rgba(226, 232, 240, 0.3);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .branch-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .branch-info {
            flex: 1;
        }

        .branch-name {
            font-weight: 700;
            color: #1f2937;
            font-size: 1.1rem;
            margin-bottom: 0.25rem;
        }

        .branch-stats {
            font-size: 0.875rem;
            color: #6b7280;
        }

        .branch-revenue {
            font-weight: 700;
            color: #059669;
            font-size: 1.25rem;
            margin-right: 1rem;
        }

        .branch-avg {
            font-size: 0.875rem;
            color: #6b7280;
            font-weight: 500;
        }

        .no-data {
            text-align: center;
            color: #6b7280;
            font-style: italic;
            padding: 3rem;
            background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
            border-radius: 12px;
            border: 2px dashed #e2e8f0;
        }

        .badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
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

        .badge-danger {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.2);
        }

        /* Container and Layout Improvements */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        .mb-6 {
            margin-bottom: 3rem;
        }

        /* Top Products Styling */
        .top-products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
        }

        .product-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 20px;
            padding: 2rem;
            border: 1px solid rgba(226, 232, 240, 0.3);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .product-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--rank-color);
        }

        .product-card.rank-1 {
            --rank-color: linear-gradient(90deg, #fbbf24 0%, #f59e0b 100%);
            border-color: #fbbf24;
            box-shadow: 0 12px 40px rgba(251, 191, 36, 0.2);
        }

        .product-card.rank-2 {
            --rank-color: linear-gradient(90deg, #9ca3af 0%, #6b7280 100%);
            border-color: #9ca3af;
            box-shadow: 0 10px 32px rgba(156, 163, 175, 0.15);
        }

        .product-card.rank-3 {
            --rank-color: linear-gradient(90deg, #cd7c2f 0%, #a16207 100%);
            border-color: #cd7c2f;
            box-shadow: 0 8px 24px rgba(205, 124, 47, 0.15);
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 16px 48px rgba(0, 0, 0, 0.12);
        }

        .product-rank {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            font-size: 1.2rem;
            font-weight: 700;
        }

        .product-card.rank-1 .product-rank {
            color: #f59e0b;
        }

        .product-card.rank-2 .product-rank {
            color: #6b7280;
        }

        .product-card.rank-3 .product-rank {
            color: #a16207;
        }

        .rank-number {
            font-size: 1rem;
            font-weight: 600;
        }

        .product-content {
            display: flex;
            gap: 1.5rem;
            align-items: flex-start;
        }

        .product-image {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            overflow: hidden;
            flex-shrink: 0;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .no-image {
            color: #9ca3af;
            font-size: 2rem;
        }

        .product-details {
            flex: 1;
        }

        .product-name {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
            line-height: 1.3;
        }

        .product-sku {
            font-size: 0.85rem;
            color: #6b7280;
            font-family: 'Courier New', monospace;
            margin-bottom: 0.25rem;
        }

        .product-category {
            font-size: 0.85rem;
            color: #8b5cf6;
            font-weight: 500;
            margin-bottom: 1rem;
        }

        .product-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 1rem;
        }

        .stat-item {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .stat-value {
            font-size: 1rem;
            font-weight: 700;
            color: #059669;
            margin-bottom: 0.25rem;
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
            padding: 4rem 2rem;
            color: #9ca3af;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            opacity: 0.5;
        }

        .empty-state p {
            font-size: 1.2rem;
            font-weight: 500;
        }

        /* Responsive Design */
        @media (max-width: 640px) {
            .container {
                padding: 1rem;
            }

            .filter-grid {
                grid-template-columns: 1fr;
            }

            .metrics-grid {
                grid-template-columns: 1fr;
            }

            .employee-performance-grid {
                grid-template-columns: 1fr;
            }

            .return-stats-grid {
                grid-template-columns: 1fr;
            }

            .top-products-grid {
                grid-template-columns: 1fr;
            }

            .product-content {
                flex-direction: column;
                text-align: center;
            }

            .product-image {
                align-self: center;
            }

            .product-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection
