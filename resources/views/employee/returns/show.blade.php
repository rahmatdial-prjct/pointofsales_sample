@extends('layouts.app')

@section('content')
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="page-title">
            <h1>Detail Retur #{{ $return->id }}</h1>
            <p>Informasi lengkap permintaan retur</p>
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
        <div class="back-button">
            <a href="{{ route('employee.returns.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="return-details">
            <!-- Return Status -->
            <div class="status-card">
                <div class="status-icon status-{{ $return->status }}">
                    @if($return->status === 'menunggu')
                        <i class="fas fa-clock"></i>
                    @elseif($return->status === 'disetujui')
                        <i class="fas fa-check-circle"></i>
                    @else
                        <i class="fas fa-times-circle"></i>
                    @endif
                </div>
                <div class="status-content">
                    <h3>Status:
                        @if($return->status === 'menunggu')
                            Menunggu Persetujuan
                        @elseif($return->status === 'disetujui')
                            Disetujui
                        @else
                            Ditolak
                        @endif
                    </h3>
                    <p>
                        @if($return->status === 'menunggu')
                            Permintaan retur Anda sedang menunggu persetujuan dari manager.
                        @elseif($return->status === 'disetujui')
                            Permintaan retur Anda telah disetujui. Stock telah diupdate.
                        @else
                            Permintaan retur Anda ditolak oleh manager.
                        @endif
                    </p>
                </div>
            </div>

            <!-- Return Information -->
            <div class="card">
                <div class="card-header">
                    <h3>Informasi Retur</h3>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <label>ID Retur:</label>
                            <span>#{{ $return->id }}</span>
                        </div>
                        <div class="info-item">
                            <label>Tanggal Retur:</label>
                            <span>{{ $return->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="info-item">
                            <label>Total Refund:</label>
                            <span class="amount">Rp {{ number_format($return->total, 0, ',', '.') }}</span>
                        </div>
                        <div class="info-item">
                            <label>Cabang:</label>
                            <span>{{ $return->branch->name ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <label>Alasan:</label>
                            <span>{{ $return->reason }}</span>
                        </div>
                        @if($return->approved_by)
                            <div class="info-item">
                                <label>Diproses oleh:</label>
                                <span>{{ $return->approver->name ?? 'N/A' }}</span>
                            </div>
                            <div class="info-item">
                                <label>Tanggal Diproses:</label>
                                <span>{{ $return->approved_at ? $return->approved_at->format('d/m/Y H:i') : 'N/A' }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Return Items -->
            <div class="card">
                <div class="card-header">
                    <h3>Item yang Diretur</h3>
                </div>
                <div class="card-body">
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Quantity</th>
                                    <th>Harga Satuan</th>
                                    <th>Kondisi</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($return->returnItems as $item)
                                    <tr>
                                        <td>{{ $item->product->name ?? 'N/A' }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="condition-badge condition-{{ $item->condition }}">
                                                @if($item->condition === 'good')
                                                    Baik
                                                @elseif($item->condition === 'damaged')
                                                    Rusak
                                                @else
                                                    Cacat
                                                @endif
                                            </span>
                                        </td>
                                        <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="total-row">
                                    <td colspan="4"><strong>Total Refund:</strong></td>
                                    <td><strong>Rp {{ number_format($return->total, 0, ',', '.') }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .back-button {
            margin-bottom: 1rem;
        }

        .return-details {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .status-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .status-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .status-pending {
            background: #f59e0b;
        }

        .status-approved {
            background: #10b981;
        }

        .status-rejected {
            background: #ef4444;
        }

        .status-content h3 {
            margin: 0 0 0.5rem 0;
            color: #1f2937;
        }

        .status-content p {
            margin: 0;
            color: #6b7280;
        }

        .card-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .info-item label {
            font-weight: 600;
            color: #374151;
            font-size: 0.875rem;
        }

        .info-item span {
            color: #1f2937;
        }

        .amount {
            font-weight: 600;
            color: #059669;
            font-size: 1.125rem;
        }

        .condition-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .condition-good {
            background: #d1fae5;
            color: #065f46;
        }

        .condition-damaged {
            background: #fed7aa;
            color: #9a3412;
        }

        .condition-defective {
            background: #fee2e2;
            color: #991b1b;
        }

        .total-row {
            background: #f9fafb;
            font-weight: 600;
        }

        .table-container {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        .table th {
            background: #f9fafb;
            font-weight: 600;
            color: #374151;
        }
    </style>
@endsection
