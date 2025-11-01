@extends('layouts.app')

@section('content')
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="page-title">
            <h1>Manajemen Produk</h1>
            <p>Kelola produk dan stok di cabang Anda</p>
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
        <!-- Action Header -->
        <div class="action-header">
            <div class="filters">
                <div class="filter-group">
                    <input type="text" placeholder="Cari produk..." id="searchProduct" class="form-control">
                    <select class="form-control" id="categoryFilter">
                        <option value="">Semua Kategori</option>
                        @if(isset($categories) && count($categories) > 0)
                            @foreach($categories as $category)
                                @if($category && isset($category->id) && isset($category->name))
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endif
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <a href="{{ route('manager.export.products') }}" class="btn btn-secondary">
                <i class="fas fa-download"></i> Export CSV
            </a>
            <a href="{{ route('manager.products.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Produk
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- Products Grid -->
        <div class="card">
            <div class="card-header">
                <h2 class="heading-secondary">Daftar Produk</h2>
                <div class="stats-summary">
                    <span class="stat-item">
                        <i class="fas fa-box"></i>
                        {{ $products->total() }} Total Produk
                    </span>
                </div>
            </div>

            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Gambar</th>
                            <th>Informasi Produk</th>
                            <th>SKU</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                        <tr>
                            <td>
                                <div class="product-image">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                                    @else
                                        <div class="no-image">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="product-info">
                                    <div class="product-name">{{ $product->name }}</div>
                                    @if($product->description)
                                        <div class="product-desc">{{ Str::limit($product->description, 50) }}</div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="sku-info">
                                    <span class="sku-badge">{{ $product->sku }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="category-badge">
                                    {{ $product->category->name ?? 'Tanpa Kategori' }}
                                </span>
                            </td>
                            <td>
                                <div class="price-info">
                                    <div class="price">Rp {{ number_format($product->base_price, 0, ',', '.') }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="stock-info">
                                    <span class="stock-badge">
                                        {{ $product->stock }} unit
                                    </span>
                                </div>
                            </td>
                            <td>
                                <span class="status-badge {{ $product->is_active ? 'active' : 'inactive' }}">
                                    {{ $product->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('manager.products.edit', $product) }}" class="btn btn-sm btn-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('manager.products.destroy', $product) }}" method="POST" class="inline-form" onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-box-open"></i>
                                    <p>Belum ada produk</p>
                                    <a href="{{ route('manager.products.create') }}" class="btn btn-primary">
                                        Tambah Produk Pertama
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
                {{ $products->links() }}
            </div>
        </div>

    </div>

    <style>
        .stats-summary {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: #6b7280;
        }

        .product-image {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f3f4f6;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .no-image {
            color: #9ca3af;
            font-size: 1.5rem;
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

        .product-desc {
            font-size: 0.75rem;
            color: #9ca3af;
        }

        .sku-info {
            text-align: center;
        }

        .sku-badge {
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            color: #374151;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
            font-family: 'Courier New', monospace;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 8px rgba(107, 114, 128, 0.1);
            border: 1px solid #d1d5db;
        }

        .category-badge {
            padding: 0.25rem 0.75rem;
            background: #e5e7eb;
            color: #374151;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .price-info {
            text-align: right;
        }

        .price {
            font-weight: 600;
            color: #059669;
            font-size: 0.875rem;
        }

        .stock-info {
            text-align: center;
        }

        .stock-badge {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            color: #1e40af;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.2);
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-badge.active {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.2);
        }

        .status-badge.inactive {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.2);
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
        }

        .inline-form {
            display: inline;
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

        .text-center {
            text-align: center;
        }
    </style>
@endsection
