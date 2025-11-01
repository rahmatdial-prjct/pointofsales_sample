@extends('layouts.app')

@section('content')
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="page-title">
            <h1>Retur Barang</h1>
            <p>Daftar permintaan retur yang telah dibuat</p>
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
        <!-- Action Button -->
        <div class="action-header">
            <a href="{{ route('employee.returns.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Buat Retur Baru
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Returns Table -->
        <div class="card">
            <div class="card-header">
                <h2 class="heading-secondary">Daftar Retur Saya</h2>
            </div>
            
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tanggal</th>
                            <th>Total Refund</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($returns as $return)
                            <tr>
                                <td>#{{ $return->id }}</td>
                                <td>{{ $return->created_at->format('d/m/Y H:i') }}</td>
                                <td>Rp {{ number_format($return->total, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge badge-{{ $return->status === 'menunggu' ? 'warning' : ($return->status === 'disetujui' ? 'success' : 'danger') }}">
                                        @if($return->status === 'menunggu')
                                            Menunggu Persetujuan
                                        @elseif($return->status === 'disetujui')
                                            Disetujui
                                        @else
                                            Ditolak
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('employee.returns.show', $return) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">
                                    <div class="empty-state">
                                        <i class="fas fa-inbox"></i>
                                        <p>Belum ada retur yang dibuat</p>
                                        <a href="{{ route('employee.returns.create') }}" class="btn btn-primary">
                                            Buat Retur Pertama
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
                {{ $returns->links() }}
            </div>
        </div>
    </div>

    <style>
        .action-header {
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: flex-end;
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

        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
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
