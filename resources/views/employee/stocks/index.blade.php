@extends('layouts.app')

@section('content')
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="page-title">
            <h1>Stok Produk</h1>
            <p>Informasi stok produk di cabang Anda</p>
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
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $totalProducts ?? 0 }}</div>
                    <div class="stat-label">Total Produk</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon low">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $lowStockCount ?? 0 }}</div>
                    <div class="stat-label">Stok Rendah</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon out">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $outOfStockCount ?? 0 }}</div>
                    <div class="stat-label">Habis</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon value">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">Rp {{ number_format($totalStockValue ?? 0, 0, ',', '.') }}</div>
                    <div class="stat-label">Nilai Stok</div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="action-header">
            <div class="filters">
                <form method="GET" class="filter-form">
                    <div class="filter-group">
                        <select name="category" id="categoryFilter" class="form-control">
                            <option value="">Semua Kategori</option>
                        </select>
                        <select name="stock_level" class="form-control">
                            <option value="">Semua Level</option>
                            <option value="out" {{ request('stock_level') === 'out' ? 'selected' : '' }}>Habis (0)</option>
                            <option value="low" {{ request('stock_level') === 'low' ? 'selected' : '' }}>Rendah (≤10)</option>
                            <option value="medium" {{ request('stock_level') === 'medium' ? 'selected' : '' }}>Sedang (11-50)</option>
                            <option value="high" {{ request('stock_level') === 'high' ? 'selected' : '' }}>Tinggi (>50)</option>
                        </select>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk/SKU..." class="form-control">
                        <button type="submit" class="btn btn-secondary btn-sm">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        @if(request()->hasAny(['category', 'stock_level', 'search']))
                            <a href="{{ route('employee.stocks.index') }}" class="btn btn-outline btn-sm">
                                <i class="fas fa-times"></i> Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Products Stock Table -->
        <div class="card">
            <div class="card-header">
                <h2 class="heading-secondary">Daftar Stok Produk</h2>
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Kategori</th>
                                <th>SKU</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                            <tr>
                                <td>
                                    <div class="product-info">
                                        <div class="product-name">{{ $product->name }}</div>
                                        @if($product->description)
                                            <div class="product-desc">{{ Str::limit($product->description, 50) }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="category-badge">
                                        {{ $product->category->name ?? 'Tanpa Kategori' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="sku-code">{{ $product->sku }}</span>
                                </td>
                                <td>
                                    <div class="price">Rp {{ number_format($product->base_price, 0, ',', '.') }}</div>
                                </td>
                                <td>
                                    <div class="stock-info">
                                        <span class="stock-badge {{ $product->stock <= 10 ? 'low' : ($product->stock == 0 ? 'out' : 'normal') }}">
                                            {{ $product->stock }} unit
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    @if($product->stock == 0)
                                        <span class="status-badge out">
                                            <i class="fas fa-times-circle"></i> Habis
                                        </span>
                                    @elseif($product->stock <= 10)
                                        <span class="status-badge low">
                                            <i class="fas fa-exclamation-triangle"></i> Rendah
                                        </span>
                                    @else
                                        <span class="status-badge available">
                                            <i class="fas fa-check-circle"></i> Tersedia
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">
                                    <div class="empty-state">
                                        <i class="fas fa-boxes"></i>
                                        <p>Belum ada produk</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Pagination -->
            @if($products->hasPages())
            <div class="pagination-wrapper">
                {{ $products->links() }}
            </div>
            @endif
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

        /* Container and Layout */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* Stats Grid */
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

        .stat-card:nth-child(1) {
            --card-color: #3b82f6;
            --card-color-light: #60a5fa;
        }
        .stat-card:nth-child(2) {
            --card-color: #f59e0b;
            --card-color-light: #fbbf24;
        }
        .stat-card:nth-child(3) {
            --card-color: #ef4444;
            --card-color-light: #f87171;
        }
        .stat-card:nth-child(4) {
            --card-color: #10b981;
            --card-color-light: #34d399;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
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

        /* Action Header and Filters */
        .action-header {
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .filters {
            flex: 1;
        }

        .filter-form {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .filter-group {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .filter-group input,
        .filter-group select {
            padding: 0.5rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 0.875rem;
            min-width: 150px;
        }

        /* Buttons */
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

        /* Card Styling */
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

        /* Table Styling */
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

        /* Product Info */
        .product-info {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .product-name {
            font-weight: 600;
            color: #1f2937;
        }

        .product-desc {
            font-size: 0.75rem;
            color: #9ca3af;
        }

        /* Category Badge */
        .category-badge {
            padding: 0.25rem 0.75rem;
            background: #e5e7eb;
            color: #374151;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        /* SKU Code */
        .sku-code {
            background: linear-gradient(135deg, #e2e8f0 0%, #f1f5f9 100%);
            color: #374151;
            padding: 0.5rem 1rem;
            border-radius: 12px;
            font-family: 'Courier New', monospace;
            font-weight: 600;
            font-size: 0.9rem;
        }

        /* Price */
        .price {
            font-weight: 600;
            color: #059669;
            font-size: 0.875rem;
        }

        /* Stock Badge */
        .stock-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
            text-align: center;
        }

        .stock-badge.normal {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            color: #1e40af;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.2);
        }

        .stock-badge.low {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
            box-shadow: 0 2px 8px rgba(245, 158, 11, 0.2);
        }

        .stock-badge.out {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.2);
        }

        /* Status Badge */
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .status-badge.available {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.2);
        }

        .status-badge.low {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
            box-shadow: 0 2px 8px rgba(245, 158, 11, 0.2);
        }

        .status-badge.out {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.2);
        }

        /* Empty State */
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

        /* Pagination */
        .pagination-wrapper {
            padding: 1rem 2rem;
            background: #f8fafc;
            border-top: 1px solid #e5e7eb;
        }

        .text-center {
            text-align: center;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .action-header {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-form {
                flex-direction: column;
            }

            .filter-group {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-group input,
            .filter-group select {
                min-width: auto;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Load categories dynamically to avoid IDE errors
            const categoriesData = {!! json_encode($categories ?? []) !!};
            const categoryFilter = document.getElementById('categoryFilter');

            if (categoriesData && categoriesData.length > 0) {
                categoriesData.forEach(function(category) {
                    if (category && category.id && category.name) {
                        const option = document.createElement('option');
                        option.value = category.id;
                        option.textContent = category.name;
                        if ('{{ request("category") }}' == category.id) {
                            option.selected = true;
                        }
                        categoryFilter.appendChild(option);
                    }
                });
            }
        });
    </script>
@endsection
