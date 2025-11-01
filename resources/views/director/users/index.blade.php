@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="page-title">
            <h1>Manajemen Pengguna</h1>
            <p>Kelola manajer dan pegawai di seluruh cabang</p>
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
        <!-- Action Header -->
        <div class="action-header">
            <div class="filters">
                <!-- Filters will be added here -->
            </div>
            <a href="{{ route('director.users.create') }}" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Tambah Pengguna Baru
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
                <div class="stat-icon users">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $users->total() }}</div>
                    <div class="stat-label">Total Pengguna</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon managers">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $users->where('role', 'manager')->count() }}</div>
                    <div class="stat-label">Manajer</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon employees">
                    <i class="fas fa-user"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $users->where('role', 'employee')->count() }}</div>
                    <div class="stat-label">Pegawai</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon branches">
                    <i class="fas fa-building"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $users->whereNotNull('branch_id')->groupBy('branch_id')->count() }}</div>
                    <div class="stat-label">Cabang Aktif</div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card">
            <div class="card-header">
                <h2 class="heading-secondary">Filter Pengguna</h2>
            </div>
            <div class="card-body">
                <div class="filter-form">
                    <div class="filter-group">
                        <select id="roleFilter" class="form-control">
                            <option value="">Semua Role</option>
                            <option value="manager">Manajer</option>
                            <option value="employee">Pegawai</option>
                        </select>
                        <select id="branchFilter" class="form-control">
                            <option value="">Semua Cabang</option>
                            @foreach($users->whereNotNull('branch_id')->unique('branch_id') as $user)
                                @if($user->branch)
                                    <option value="{{ $user->branch->name }}">{{ $user->branch->name }}</option>
                                @endif
                            @endforeach
                        </select>
                        <input type="text" id="searchInput" placeholder="Cari nama atau email..." class="form-control">
                        <a href="{{ route('director.export.users') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-download"></i> Export CSV
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="card">
            <div class="card-header">
                <h2 class="heading-secondary">Daftar Pengguna</h2>
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Pengguna</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Cabang</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr class="table-row" data-role="{{ $user->role }}" data-branch="{{ $user->branch ? $user->branch->name : '' }}">
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar">
                                        @if($user->role === 'manager')
                                            <i class="fas fa-user-tie"></i>
                                        @else
                                            <i class="fas fa-user"></i>
                                        @endif
                                    </div>
                                    <div class="user-details">
                                        <h4>{{ $user->name }}</h4>
                                        <span class="user-id">ID: {{ $user->id }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="email-info">
                                    <i class="fas fa-envelope"></i>
                                    <span>{{ $user->email }}</span>
                                </div>
                            </td>
                            <td>
                                @if($user->role === 'manager')
                                    <span class="badge badge-primary">
                                        <i class="fas fa-user-tie"></i>
                                        Manajer
                                    </span>
                                @elseif($user->role === 'employee')
                                    <span class="badge badge-info">
                                        <i class="fas fa-user"></i>
                                        Pegawai
                                    </span>
                                @else
                                    <span class="badge badge-gray">
                                        <i class="fas fa-question"></i>
                                        {{ ucfirst($user->role) }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($user->branch)
                                    <div class="branch-info">
                                        <i class="fas fa-building"></i>
                                        <span>{{ $user->branch->name }}</span>
                                    </div>
                                @else
                                    <span class="badge badge-warning">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        Tidak ada cabang
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($user->is_active ?? true)
                                    <span class="badge badge-success">
                                        <i class="fas fa-check-circle"></i>
                                        Aktif
                                    </span>
                                @else
                                    <span class="badge badge-danger">
                                        <i class="fas fa-times-circle"></i>
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('director.users.edit', $user) }}"
                                       class="btn btn-sm btn-warning"
                                       title="Edit Pengguna">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button"
                                            class="btn btn-sm btn-danger"
                                            onclick="confirmDelete({{ $user->id }}, '{{ $user->name }}')"
                                            title="Hapus Pengguna">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="empty-state">
                                <div class="empty-content">
                                    <i class="fas fa-users"></i>
                                    <h3>Belum ada pengguna</h3>
                                    <p>Mulai dengan menambahkan pengguna pertama</p>
                                    <a href="{{ route('director.users.create') }}" class="btn btn-primary">
                                        <i class="fas fa-user-plus"></i>
                                        Tambah Pengguna
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
                <div class="pagination-wrapper">
                    {{ $users->links() }}
                </div>
            @endif
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
                <p>Apakah Anda yakin ingin menghapus pengguna <strong id="userName"></strong>?</p>
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
        // Filter and search functionality
        function filterUsers() {
            const roleFilter = document.getElementById('roleFilter').value.toLowerCase();
            const branchFilter = document.getElementById('branchFilter').value.toLowerCase();
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const tableRows = document.querySelectorAll('.table-row');

            tableRows.forEach(row => {
                const role = row.getAttribute('data-role').toLowerCase();
                const branch = row.getAttribute('data-branch').toLowerCase();
                const text = row.textContent.toLowerCase();

                const roleMatch = !roleFilter || role === roleFilter;
                const branchMatch = !branchFilter || branch.includes(branchFilter);
                const searchMatch = !searchTerm || text.includes(searchTerm);

                if (roleMatch && branchMatch && searchMatch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Event listeners for filters
        document.getElementById('roleFilter').addEventListener('change', filterUsers);
        document.getElementById('branchFilter').addEventListener('change', filterUsers);
        document.getElementById('searchInput').addEventListener('keyup', filterUsers);

        // Delete confirmation modal
        function confirmDelete(userId, userName) {
            document.getElementById('userName').textContent = userName;
            document.getElementById('deleteForm').action = `/director/users/${userId}`;
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

        // Export functionality


        // Initialize tooltips
        document.querySelectorAll('[title]').forEach(element => {
            element.addEventListener('mouseenter', function() {
                const title = this.getAttribute('title');
                if (title) {
                    const tooltip = document.createElement('div');
                    tooltip.className = 'tooltip-content';
                    tooltip.textContent = title;
                    this.appendChild(tooltip);
                }
            });

            element.addEventListener('mouseleave', function() {
                const tooltip = this.querySelector('.tooltip-content');
                if (tooltip) {
                    tooltip.remove();
                }
            });
        });
    </script>
@endsection
