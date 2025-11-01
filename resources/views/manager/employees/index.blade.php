@extends('layouts.app')

@section('content')
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="page-title">
            <h1>Manajemen Pegawai</h1>
            <p>Kelola data pegawai di cabang Anda</p>
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
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $employees->total() }}</div>
                    <div class="stat-label">Total Pegawai</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon active">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $employees->where('is_active', true)->count() }}</div>
                    <div class="stat-label">Pegawai Aktif</div>
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

        <!-- Action Button -->
        <div class="action-header">
            <a href="{{ route('manager.employees.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Pegawai Baru
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Employees Table -->
        <div class="card">
            <div class="card-header">
                <h2 class="heading-secondary">Daftar Pegawai</h2>
            </div>

            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Bergabung</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $employee)
                            <tr>
                                <td>
                                    <div class="employee-info">
                                        <div class="employee-name">{{ $employee->name }}</div>
                                        <div class="employee-role">
                                            @if($employee->role === 'director')
                                                Direktur
                                            @elseif($employee->role === 'manager')
                                                Manajer
                                            @elseif($employee->role === 'employee')
                                                Pegawai
                                            @else
                                                {{ ucfirst($employee->role) }}
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $employee->email }}</td>
                                <td>
                                    <span class="badge badge-{{ $employee->is_active ? 'success' : 'danger' }}">
                                        {{ $employee->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td>{{ $employee->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('manager.employees.edit', $employee) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('manager.employees.destroy', $employee) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus pegawai ini?')">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">
                                    <div class="empty-state">
                                        <i class="fas fa-users"></i>
                                        <p>Belum ada pegawai yang terdaftar</p>
                                        <a href="{{ route('manager.employees.create') }}" class="btn btn-primary">
                                            Tambah Pegawai Pertama
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
                {{ $employees->links() }}
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

        .stat-icon.active {
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
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: flex-end;
        }

        .employee-info {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .employee-name {
            font-weight: 600;
            color: #1f2937;
        }

        .employee-role {
            font-size: 0.875rem;
            color: #6b7280;
        }

        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .badge-success {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
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
            color: #d1d5db;
        }

        .empty-state p {
            margin-bottom: 1rem;
            font-size: 1.125rem;
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

        .text-center {
            text-align: center;
        }
    </style>
@endsection
