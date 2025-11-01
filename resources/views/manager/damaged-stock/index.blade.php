@extends('layouts.app')

@section('content')
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="page-title">
            <h1>Barang Rusak</h1>
            <p>Kelola barang rusak/cacat dari retur</p>
        </div>
        <div class="user-info">
            <div class="user-details">
                <div class="name">{{ Auth::user()->name }}</div>
                <div class="role">{{ Auth::user()->role }}</div>
            </div>
            <div class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
        </div>
    </div>

    <div class="container">
        <!-- Stats Cards -->
        <form method="GET" class="mb-3">
            <div style="display: flex; gap: 1rem; align-items: center;">
                <label for="periode" style="font-weight:600;">Periode:</label>
                <select name="periode" id="periode" onchange="this.form.submit()" style="padding:0.5rem 1rem; border-radius:8px;">
                    <option value="hari_ini" {{ $periode == 'hari_ini' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="minggu_ini" {{ $periode == 'minggu_ini' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="bulan_ini" {{ $periode == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="tahun_ini" {{ $periode == 'tahun_ini' ? 'selected' : '' }}>Tahun Ini</option>
                    <option value="minggu_kemarin" {{ $periode == 'minggu_kemarin' ? 'selected' : '' }}>Minggu Kemarin</option>
                    <option value="bulan_kemarin" {{ $periode == 'bulan_kemarin' ? 'selected' : '' }}>Bulan Kemarin</option>
                    <option value="tahun_kemarin" {{ $periode == 'tahun_kemarin' ? 'selected' : '' }}>Tahun Kemarin</option>
                </select>
                <span style="font-size:0.95rem; color:#6b7280;">Menampilkan data: <b>{{ $periodeLabel }}</b></span>
            </div>
        </form>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon pending">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $pendingCount }}</div>
                    <div class="stat-label">Menunggu Tindakan</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon value">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">Rp {{ number_format($totalDamagedValue, 0, ',', '.') }}</div>
                    <div class="stat-label">Total Nilai Rusak ({{ $periodeLabel }})</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon total">
                    <i class="fas fa-list"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $damagedStocks->total() }}</div>
                    <div class="stat-label">Total Barang</div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        <!-- Damaged Stock Table -->
        <div class="card">
            <div class="card-header">
                <h2 class="heading-secondary">Daftar Barang Rusak</h2>
            </div>
            
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Quantity</th>
                            <th>Kondisi</th>
                            <th>Nilai</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($damagedStocks as $item)
                            <tr>
                                <td>
                                    <div class="product-info">
                                        <div class="product-name">{{ $item->product->name ?? 'N/A' }}</div>
                                        <div class="product-sku">SKU: {{ $item->product->sku ?? 'N/A' }}</div>
                                    </div>
                                </td>
                                <td>{{ $item->quantity }}</td>
                                <td>
                                    <span class="condition-badge condition-{{ $item->condition }}">
                                        {{ $item->condition === 'damaged' ? 'Rusak' : ($item->condition === 'defective' ? 'Cacat' : ($item->condition === 'good' ? 'Baik' : ucfirst($item->condition))) }}
                                    </span>
                                </td>
                                <td>Rp {{ number_format($item->returnItem->subtotal ?? 0, 0, ',', '.') }}</td>
                                <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                <td>
                                    @if($item->action_taken == 'dispose')
                                        <span class="badge badge-success">Dibuang</span>
                                    @elseif($item->action_taken == 'repair')
                                        <span class="badge badge-info">Diperbaiki</span>
                                    @elseif($item->action_taken == 'return_to_supplier')
                                        <span class="badge badge-primary">Dikembalikan ke Supplier</span>
                                    @else
                                        <span class="badge badge-warning">Menunggu</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('manager.damaged-stock.show', $item) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                        @if(!$item->action_taken)
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                                                    <i class="fas fa-cog"></i> Tindakan
                                                </button>
                                                <div class="dropdown-menu">
                                                    <form action="{{ route('manager.damaged-stock.action', $item) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        <input type="hidden" name="action_taken" value="repair">
                                                        <button type="submit" class="dropdown-item" onclick="return confirm('Perbaiki item ini? Stok akan ditambahkan kembali.')">
                                                            <i class="fas fa-wrench"></i> Perbaiki
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('manager.damaged-stock.action', $item) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        <input type="hidden" name="action_taken" value="dispose">
                                                        <button type="submit" class="dropdown-item" onclick="return confirm('Buang item ini?')">
                                                            <i class="fas fa-trash"></i> Buang
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('manager.damaged-stock.action', $item) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        <input type="hidden" name="action_taken" value="return_to_supplier">
                                                        <button type="submit" class="dropdown-item" onclick="return confirm('Kembalikan ke supplier?')">
                                                            <i class="fas fa-undo"></i> Kembalikan ke Supplier
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    <div class="empty-state">
                                        <i class="fas fa-check-circle"></i>
                                        <p>Tidak ada barang rusak</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination-wrapper">
                {{ $damagedStocks->links() }}
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
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .stat-icon.pending {
            background: #f59e0b;
        }

        .stat-icon.value {
            background: #ef4444;
        }

        .stat-icon.total {
            background: #6b7280;
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
            font-size: 0.875rem;
            color: #6b7280;
        }

        .condition-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .condition-damaged {
            background: #fed7aa;
            color: #9a3412;
        }

        .condition-defective {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-success {
            background: #d1fae5;
            color: #065f46;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            background: white;
            min-width: 160px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            border-radius: 0.375rem;
            z-index: 1;
            border: 1px solid #e5e7eb;
        }

        .dropdown:hover .dropdown-menu {
            display: block;
        }

        .dropdown-item {
            display: block;
            width: 100%;
            padding: 0.5rem 1rem;
            text-decoration: none;
            color: #374151;
            border: none;
            background: none;
            text-align: left;
            cursor: pointer;
        }

        .dropdown-item:hover {
            background: #f3f4f6;
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
            color: #10b981;
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

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }
    </style>
@endsection
