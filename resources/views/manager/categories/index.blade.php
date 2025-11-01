@extends('layouts.app')

@section('content')
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="page-title">
            <h1>Manajemen Kategori</h1>
            <p>Kelola kategori produk untuk mengorganisir inventori</p>
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
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-tags"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $categories->total() }}</div>
                    <div class="stat-label">Total Kategori</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon products">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $categories->sum('products_count') ?? 0 }}</div>
                    <div class="stat-label">Total Produk</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon branch">
                    <i class="fas fa-store"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ Auth::user()->branch->name ?? 'N/A' }}</div>
                    <div class="stat-label">Cabang</div>
                </div>
            </div>
        </div>

        <!-- Action Header -->
        <div class="action-header">
            <div class="search-filter">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Cari kategori..." id="searchCategory">
                </div>
            </div>
            <a href="{{ route('manager.categories.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Kategori
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

        <!-- Categories Grid -->
        <div class="card">
            <div class="card-header">
                <h2 class="heading-secondary">Daftar Kategori</h2>
                <div class="view-toggle">
                    <button class="view-btn active" data-view="grid">
                        <i class="fas fa-th-large"></i>
                    </button>
                    <button class="view-btn" data-view="table">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
            </div>

            <!-- Grid View -->
            <div class="categories-grid" id="gridView">
                @forelse ($categories as $category)
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-folder"></i>
                        </div>
                        <div class="category-info">
                            <h3 class="category-name">{{ $category->name }}</h3>
                            <p class="category-slug">{{ $category->slug }}</p>
                            <div class="category-stats">
                                <span class="product-count">
                                    <i class="fas fa-box"></i>
                                    {{ $category->products_count ?? 0 }} produk
                                </span>
                            </div>
                        </div>
                        <div class="category-actions">
                            <a href="{{ route('manager.categories.edit', $category) }}" class="btn btn-sm btn-primary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('manager.categories.destroy', $category) }}" method="POST" class="inline-form" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-tags"></i>
                        <p>Belum ada kategori</p>
                        <a href="{{ route('manager.categories.create') }}" class="btn btn-primary">
                            Tambah Kategori Pertama
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Table View -->
            <div class="table-container" id="tableView" style="display: none;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Kategori</th>
                            <th>Slug</th>
                            <th>Jumlah Produk</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                        <tr>
                            <td>
                                <div class="category-info-table">
                                    <div class="category-icon-small">
                                        <i class="fas fa-folder"></i>
                                    </div>
                                    <div>
                                        <div class="category-name">{{ $category->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <code class="slug-code">{{ $category->slug }}</code>
                            </td>
                            <td>
                                <span class="product-count-badge">
                                    {{ $category->products_count ?? 0 }} produk
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('manager.categories.edit', $category) }}" class="btn btn-sm btn-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('manager.categories.destroy', $category) }}" method="POST" class="inline-form" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
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
                            <td colspan="4" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-tags"></i>
                                    <p>Belum ada kategori</p>
                                    <a href="{{ route('manager.categories.create') }}" class="btn btn-primary">
                                        Tambah Kategori Pertama
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
                {{ $categories->links() }}
            </div>
        </div>
    </div>

    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #3b82f6;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .stat-icon.products {
            background: #10b981;
        }

        .stat-icon.branch {
            background: #8b5cf6;
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: bold;
            color: #1f2937;
        }

        .stat-label {
            color: #6b7280;
            font-size: 0.875rem;
        }

        .action-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            gap: 1rem;
        }

        .search-filter {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .search-box {
            position: relative;
            min-width: 300px;
        }

        .search-box i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
        }

        .search-box input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 0.875rem;
        }

        .view-toggle {
            display: flex;
            gap: 0.5rem;
        }

        .view-btn {
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            background: white;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .view-btn.active {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
            padding: 1.5rem;
        }

        .category-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 1.5rem;
            transition: all 0.2s;
            position: relative;
        }

        .category-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .category-icon {
            width: 60px;
            height: 60px;
            background: #3b82f6;
            color: white;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .category-name {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .category-slug {
            font-size: 0.875rem;
            color: #6b7280;
            font-family: monospace;
            background: #f3f4f6;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            margin-bottom: 1rem;
        }

        .category-stats {
            margin-bottom: 1rem;
        }

        .product-count {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: #6b7280;
        }

        .category-actions {
            display: flex;
            gap: 0.5rem;
            justify-content: flex-end;
        }

        .category-info-table {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .category-icon-small {
            width: 32px;
            height: 32px;
            background: #3b82f6;
            color: white;
            border-radius: 0.375rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
        }

        .slug-code {
            background: #f3f4f6;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-family: monospace;
            font-size: 0.875rem;
            color: #374151;
        }

        .product-count-badge {
            background: #e5e7eb;
            color: #374151;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
        }

        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }

        .inline-form {
            display: inline;
        }

        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .empty-state {
            padding: 3rem 1rem;
            text-align: center;
            color: #6b7280;
            grid-column: 1 / -1;
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

        @media (max-width: 768px) {
            .action-header {
                flex-direction: column;
                align-items: stretch;
            }

            .search-filter {
                flex-direction: column;
            }

            .search-box {
                min-width: auto;
            }

            .categories-grid {
                grid-template-columns: 1fr;
                padding: 1rem;
            }
        }
    </style>

    <script>
        // View toggle functionality
        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const view = this.dataset.view;

                // Update active button
                document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                // Toggle views
                if (view === 'grid') {
                    document.getElementById('gridView').style.display = 'grid';
                    document.getElementById('tableView').style.display = 'none';
                } else {
                    document.getElementById('gridView').style.display = 'none';
                    document.getElementById('tableView').style.display = 'block';
                }
            });
        });

        // Search functionality
        document.getElementById('searchCategory').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const categoryCards = document.querySelectorAll('.category-card');
            const tableRows = document.querySelectorAll('tbody tr');

            // Filter grid view
            categoryCards.forEach(card => {
                const categoryName = card.querySelector('.category-name').textContent.toLowerCase();
                const categorySlug = card.querySelector('.category-slug').textContent.toLowerCase();

                if (categoryName.includes(searchTerm) || categorySlug.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });

            // Filter table view
            tableRows.forEach(row => {
                if (row.querySelector('.empty-state')) return;

                const categoryName = row.querySelector('.category-name')?.textContent.toLowerCase() || '';
                const categorySlug = row.querySelector('.slug-code')?.textContent.toLowerCase() || '';

                if (categoryName.includes(searchTerm) || categorySlug.includes(searchTerm)) {
                    row.style.display = 'table-row';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
@endsection
