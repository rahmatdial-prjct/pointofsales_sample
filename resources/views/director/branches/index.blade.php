@extends('layouts.app')

@section('title', 'Manajemen Cabang')

@section('content')
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="page-title">
            <h1>Manajemen Cabang</h1>
            <p>Kelola dan pantau semua cabang toko</p>
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
                <div class="search-box">
                    <input type="text" placeholder="Cari cabang..." id="searchInput" class="form-control">
                    <i class="fas fa-search"></i>
                </div>
            </div>
            <a href="{{ route('director.export.branches') }}" class="btn btn-secondary">
                <i class="fas fa-download"></i> Export CSV
            </a>
            <a href="{{ route('director.branches.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Cabang Baru
            </a>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success">
                <div class="alert-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="alert-content">
                    <h4>Berhasil!</h4>
                    <p>{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon branches">
                    <i class="fas fa-building"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $branches->total() }}</div>
                    <div class="stat-label">Total Cabang</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon areas">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $branches->where('operational_area', '!=', null)->count() }}</div>
                    <div class="stat-label">Wilayah Aktif</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon contacts">
                    <i class="fas fa-phone"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $branches->where('phone', '!=', null)->count() }}</div>
                    <div class="stat-label">Dengan Kontak</div>
                </div>
            </div>
        </div>

        <!-- Branches Table -->
        <div class="card">
            <div class="card-header">
                <h2 class="heading-secondary">Daftar Cabang</h2>
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama Cabang</th>
                                <th>Alamat</th>
                                <th>Wilayah</th>
                                <th>Kontak</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    <tbody>
                            @forelse($branches as $branch)
                            <tr class="table-row">
                                <td>
                                    <div class="branch-info">
                                        <div class="branch-name">{{ $branch->name }}</div>
                                        <div class="branch-id">ID: {{ $branch->id }}</div>
                                    </div>
                                </td>
                                <td>{{ Str::limit($branch->address, 50) }}</td>
                                <td>
                                    @if($branch->operational_area)
                                        <span class="location-badge">{{ $branch->operational_area }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="contact-info">
                                        @if($branch->phone)
                                            <div class="contact-item">
                                                <i class="fas fa-phone"></i> {{ $branch->phone }}
                                            </div>
                                        @endif
                                        @if($branch->email)
                                            <div class="contact-item">
                                                <i class="fas fa-envelope"></i> {{ $branch->email }}
                                            </div>
                                        @endif
                                        @if(!$branch->phone && !$branch->email)
                                            <span class="text-muted">Tidak ada kontak</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('director.branches.edit', $branch) }}" class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete({{ $branch->id }}, '{{ $branch->name }}')" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">
                                    <div class="empty-state">
                                        <i class="fas fa-store"></i>
                                        <p>Belum ada cabang</p>
                                        <a href="{{ route('director.branches.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Tambah Cabang
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($branches->hasPages())
                    <div class="pagination-wrapper">
                        {{ $branches->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>
                    <i class="fas fa-exclamation-triangle"></i>
                    Konfirmasi Hapus
                </h3>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus cabang <strong id="branchName"></strong>?</p>
                <p class="warning-text">Tindakan ini tidak dapat dibatalkan!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i>
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('.table-row');

            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Delete confirmation modal
        function confirmDelete(branchId, branchName) {
            document.getElementById('branchName').textContent = branchName;
            document.getElementById('deleteForm').action = `/director/branches/${branchId}`;
            document.getElementById('deleteModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('deleteModal');
            if (event.target === modal) {
                closeModal();
            }
        }

        // Tooltip functionality
        document.querySelectorAll('.tooltip').forEach(tooltip => {
            tooltip.addEventListener('mouseenter', function() {
                const title = this.getAttribute('title');
                const tooltipDiv = document.createElement('div');
                tooltipDiv.className = 'tooltip-content';
                tooltipDiv.textContent = title;
                this.appendChild(tooltipDiv);
            });

            tooltip.addEventListener('mouseleave', function() {
                const tooltipContent = this.querySelector('.tooltip-content');
                if (tooltipContent) {
                    tooltipContent.remove();
                }
            });
        });
    </script>

    <style>
        .branch-info {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .branch-name {
            font-weight: 600;
            color: #1f2937;
        }

        .branch-id {
            font-size: 0.75rem;
            color: #6b7280;
        }

        .location-badge {
            padding: 0.25rem 0.75rem;
            background: #e5e7eb;
            color: #374151;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
        }

        .contact-item i {
            color: #6b7280;
            width: 12px;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
        }

        .text-muted {
            color: #9ca3af;
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
