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
            <a href="{{ route('manager.returns.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="return-details">
            <!-- Return Information -->
            <div class="card">
                <div class="card-header">
                    <h3>Informasi Retur</h3>
                    <span class="badge badge-{{ $return->status === 'pending' ? 'warning' : ($return->status === 'approved' ? 'success' : 'danger') }}">
                        {{
                            $return->status === 'approved' ? 'Disetujui' :
                            ($return->status === 'rejected' ? 'Ditolak' :
                            ucfirst($return->status))
                        }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <label>ID Retur:</label>
                            <span>#{{ $return->id }}</span>
                        </div>
                        <div class="info-item">
                            <label>Pegawai:</label>
                            <span>{{ $return->employee->name ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <label>Cabang:</label>
                            <span>{{ $return->branch->name ?? 'N/A' }}</span>
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
                            <label>Alasan:</label>
                            <span>{{ $return->reason }}</span>
                        </div>
                        @if($return->approved_by)
                            <div class="info-item">
                                <label>Disetujui oleh:</label>
                                <span>{{ $return->approver->name ?? 'N/A' }}</span>
                            </div>
                            <div class="info-item">
                                <label>Tanggal Approval:</label>
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
                                            {{
                                                $item->condition === 'damaged' ? 'Rusak' :
                                                ($item->condition === 'defective' ? 'Cacat' :
                                                ucfirst($item->condition))
                                            }}
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

            <!-- Action Buttons -->
            @if($return->status === 'pending')
                <div class="action-section">
                    <div class="action-buttons">
                        <form action="{{ route('manager.returns.approve', $return) }}" method="POST" style="display: inline;">
                            @csrf
                                                <button type="submit" class="btn btn-success" onclick="return confirm('Approve retur ini? Stock akan diupdate sesuai kondisi barang.')">
                                                    <i class="fas fa-check"></i> Setujui Retur
                                                </button>
                        </form>
                        <form action="{{ route('manager.returns.reject', $return) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Reject retur ini?')">
                                <i class="fas fa-times"></i> Tolak Retur
                            </button>
                        </form>
                    </div>
                </div>
            @endif
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

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
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

        .action-section {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
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
