@extends('layouts.app')

@section('title', 'Detail Stok Rusak')

@section('content')
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="page-title">
            <h1>Detail Stok Rusak</h1>
            <p>Informasi lengkap item stok yang rusak</p>
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
        <!-- Breadcrumb -->
        <nav class="breadcrumb">
            <a href="{{ route('manager.dashboard') }}">Dashboard</a>
            <span class="separator">/</span>
            <a href="{{ route('manager.damaged-stock.index') }}">Stok Rusak</a>
            <span class="separator">/</span>
            <span class="current">Detail #{{ $damagedStock->id }}</span>
        </nav>

        <!-- Header Actions -->
        <div class="action-header">
            <div class="status-info">
                @if($damagedStock->action_taken)
                    <span class="status-badge status-{{ $damagedStock->action_taken }}">
                        @switch($damagedStock->action_taken)
                            @case('repair')
                                <i class="fas fa-tools"></i> Diperbaiki
                                @break
                            @case('dispose')
                                <i class="fas fa-trash"></i> Dibuang
                                @break
                            @case('return_to_supplier')
                                <i class="fas fa-undo"></i> Dikembalikan ke Supplier
                                @break
                            @default
                                {{ ucfirst($damagedStock->action_taken) }}
                        @endswitch
                    </span>
                @else
                    <span class="status-badge status-pending">
                        <i class="fas fa-clock"></i> Menunggu Tindakan
                    </span>
                @endif
            </div>
            <a href="{{ route('manager.damaged-stock.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <!-- Main Content Grid -->
        <div class="content-grid">
            <!-- Damaged Stock Info Card -->
            <div class="info-card">
                <div class="card-header">
                    <h3><i class="fas fa-exclamation-triangle"></i> Informasi Stok Rusak</h3>
                </div>
                <div class="card-content">
                    <div class="info-grid">
                        <div class="info-item">
                            <label>ID Stok Rusak</label>
                            <span class="value">#{{ $damagedStock->id }}</span>
                        </div>
                        <div class="info-item">
                            <label>Tanggal Dilaporkan</label>
                            <span class="value">{{ $damagedStock->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="info-item">
                            <label>Jumlah Rusak</label>
                            <span class="value highlight">{{ $damagedStock->quantity }} unit</span>
                        </div>
                        @if($damagedStock->disposed_at)
                            <div class="info-item">
                                <label>Tanggal Tindakan</label>
                                <span class="value">{{ $damagedStock->disposed_at->format('d/m/Y H:i') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Product Information Card -->
            <div class="info-card">
                <div class="card-header">
                    <h3><i class="fas fa-box"></i> Informasi Produk</h3>
                </div>
                <div class="card-content">
                    <div class="product-display">
                        @if($damagedStock->product->image)
                            <div class="product-image">
                                <img src="{{ asset('storage/' . $damagedStock->product->image) }}"
                                     alt="{{ $damagedStock->product->name }}">
                            </div>
                        @else
                            <div class="product-image no-image">
                                <i class="fas fa-image"></i>
                                <span>Tidak ada gambar</span>
                            </div>
                        @endif
                        <div class="product-info">
                            <h4>{{ $damagedStock->product->name }}</h4>
                            <div class="product-meta">
                                <div class="meta-row">
                                    <span class="meta-label">SKU</span>
                                    <span class="meta-value">{{ $damagedStock->product->sku }}</span>
                                </div>
                                <div class="meta-row">
                                    <span class="meta-label">Kategori</span>
                                    <span class="meta-value">{{ $damagedStock->product->category->name ?? 'Tidak ada' }}</span>
                                </div>
                                <div class="meta-row">
                                    <span class="meta-label">Harga Dasar</span>
                                    <span class="meta-value">Rp {{ number_format($damagedStock->product->price ?? 0, 0, ',', '.') }}</span>
                                </div>
                                <div class="meta-row">
                                    <span class="meta-label">Stok Saat Ini</span>
                                    <span class="meta-value">{{ $damagedStock->product->stock }} unit</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Return Information -->
        @if($damagedStock->returnItem)
            <div class="info-card">
                <div class="card-header">
                    <h3><i class="fas fa-undo"></i> Informasi Retur</h3>
                </div>
                <div class="card-content">
                    <div class="info-grid">
                        <div class="info-item">
                            <label>No. Retur</label>
                            <span class="value">{{ $damagedStock->returnItem->returnTransaction->return_number ?? 'Tidak ada' }}</span>
                        </div>
                        <div class="info-item">
                            <label>Tanggal Retur</label>
                            <span class="value">{{ $damagedStock->returnItem->returnTransaction->created_at->format('d/m/Y H:i') ?? 'Tidak ada' }}</span>
                        </div>
                        <div class="info-item">
                            <label>Alasan Retur</label>
                            <span class="value">{{ $damagedStock->returnItem->returnTransaction->reason ?? 'Tidak ada' }}</span>
                        </div>
                        <div class="info-item">
                            <label>Nilai Kerugian</label>
                            <span class="value highlight">Rp {{ number_format($damagedStock->returnItem->subtotal ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Action Notes -->
        @if($damagedStock->notes)
            <div class="info-card">
                <div class="card-header">
                    <h3><i class="fas fa-sticky-note"></i> Catatan Tindakan</h3>
                </div>
                <div class="card-content">
                    <div class="notes-content">
                        {{ $damagedStock->notes }}
                    </div>
                    @if($damagedStock->disposedBy)
                        <div class="notes-meta">
                            <small><i class="fas fa-user"></i> Ditangani oleh: {{ $damagedStock->disposedBy->name }}</small>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Action Form -->
        @if(!$damagedStock->action_taken)
            <div class="info-card action-card">
                <div class="card-header">
                    <h3><i class="fas fa-cogs"></i> Ambil Tindakan</h3>
                </div>
                <div class="card-content">
                    <form action="{{ route('manager.damaged-stock.action', $damagedStock) }}" method="POST" class="action-form">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Pilih Tindakan</label>
                            <div class="action-options">
                                <label class="action-option">
                                    <input type="radio" name="action_taken" value="repair" required>
                                    <div class="option-content">
                                        <div class="option-icon repair">
                                            <i class="fas fa-tools"></i>
                                        </div>
                                        <div class="option-text">
                                            <span class="option-title">Perbaiki</span>
                                            <small>Item dapat diperbaiki dan dikembalikan ke stok</small>
                                        </div>
                                    </div>
                                </label>
                                <label class="action-option">
                                    <input type="radio" name="action_taken" value="dispose" required>
                                    <div class="option-content">
                                        <div class="option-icon dispose">
                                            <i class="fas fa-trash"></i>
                                        </div>
                                        <div class="option-text">
                                            <span class="option-title">Buang</span>
                                            <small>Item tidak dapat diperbaiki dan harus dibuang</small>
                                        </div>
                                    </div>
                                </label>
                                <label class="action-option">
                                    <input type="radio" name="action_taken" value="return_to_supplier" required>
                                    <div class="option-content">
                                        <div class="option-icon return">
                                            <i class="fas fa-undo"></i>
                                        </div>
                                        <div class="option-text">
                                            <span class="option-title">Kembalikan ke Supplier</span>
                                            <small>Item dikembalikan ke supplier untuk penggantian</small>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="notes" class="form-label">Catatan (Opsional)</label>
                            <textarea name="notes" id="notes" rows="4" class="form-control"
                                      placeholder="Tambahkan catatan tentang tindakan yang diambil..."></textarea>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check"></i> Konfirmasi Tindakan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
/* Top Bar Styles */
.top-bar {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: -20px -20px 0 -20px;
}

.page-title h1 {
    margin: 0;
    font-size: 24px;
    font-weight: 600;
    color: white;
}

.page-title p {
    margin: 5px 0 0 0;
    opacity: 0.9;
    font-size: 14px;
    color: white;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.user-details {
    text-align: right;
}

.user-details .name {
    font-weight: 600;
    font-size: 14px;
    color: white;
}

.user-details .role {
    font-size: 12px;
    opacity: 0.8;
    color: white;
}

.avatar {
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 14px;
    color: white;
}

/* Container and Layout */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 30px 20px;
}

/* Breadcrumb */
.breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 30px;
    font-size: 14px;
}

.breadcrumb a {
    color: #3b82f6;
    text-decoration: none;
}

.breadcrumb a:hover {
    text-decoration: underline;
}

.breadcrumb .separator {
    color: #9ca3af;
}

.breadcrumb .current {
    color: #6b7280;
    font-weight: 500;
}

/* Action Header */
.action-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding: 20px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
}

.status-pending {
    background: #fef3cd;
    color: #92400e;
}

.status-repair {
    background: #d1fae5;
    color: #065f46;
}

.status-dispose {
    background: #fee2e2;
    color: #991b1b;
}

.status-return_to_supplier {
    background: #e0e7ff;
    color: #3730a3;
}

/* Content Grid */
.content-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 25px;
}

/* Info Cards */
.info-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.card-header {
    background: #f8fafc;
    padding: 20px;
    border-bottom: 1px solid #e2e8f0;
}

.card-header h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: #1f2937;
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-header i {
    color: #3b82f6;
}

.card-content {
    padding: 25px;
}

/* Info Grid */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.info-item label {
    font-size: 12px;
    color: #6b7280;
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.info-item .value {
    font-size: 16px;
    color: #1f2937;
    font-weight: 500;
}

.info-item .value.highlight {
    color: #dc2626;
    font-weight: 600;
}

/* Product Display */
.product-display {
    display: flex;
    gap: 25px;
    align-items: flex-start;
}

.product-image {
    flex-shrink: 0;
}

.product-image img {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
}

.product-image.no-image {
    width: 120px;
    height: 120px;
    background: #f3f4f6;
    border: 2px dashed #d1d5db;
    border-radius: 12px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #9ca3af;
}

.product-image.no-image i {
    font-size: 24px;
    margin-bottom: 8px;
}

.product-image.no-image span {
    font-size: 12px;
}

.product-info h4 {
    margin: 0 0 15px 0;
    font-size: 20px;
    font-weight: 600;
    color: #1f2937;
}

.product-meta {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.meta-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #f3f4f6;
}

.meta-row:last-child {
    border-bottom: none;
}

.meta-label {
    font-weight: 500;
    color: #6b7280;
    min-width: 100px;
}

.meta-value {
    color: #1f2937;
    font-weight: 500;
}

/* Notes */
.notes-content {
    background: #f8fafc;
    padding: 20px;
    border-radius: 8px;
    color: #4b5563;
    line-height: 1.6;
    margin-bottom: 15px;
    border-left: 4px solid #3b82f6;
}

.notes-meta {
    text-align: right;
    color: #6b7280;
    font-size: 14px;
}

.notes-meta i {
    margin-right: 5px;
}

/* Action Form */
.action-card {
    border: 2px solid #e5e7eb;
}

.action-options {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-bottom: 25px;
}

.action-option {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 20px;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    background: white;
}

.action-option:hover {
    border-color: #3b82f6;
    background: #f8fafc;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
}

.action-option input[type="radio"] {
    margin: 0;
    transform: scale(1.2);
}

.action-option input[type="radio"]:checked {
    accent-color: #3b82f6;
}

.action-option:has(input[type="radio"]:checked) {
    border-color: #3b82f6;
    background: #eff6ff;
}

.option-content {
    display: flex;
    align-items: center;
    gap: 15px;
    flex: 1;
}

.option-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
}

.option-icon.repair {
    background: linear-gradient(135deg, #10b981, #059669);
}

.option-icon.dispose {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}

.option-icon.return {
    background: linear-gradient(135deg, #8b5cf6, #7c3aed);
}

.option-text {
    flex: 1;
}

.option-title {
    display: block;
    font-weight: 600;
    font-size: 16px;
    color: #1f2937;
    margin-bottom: 4px;
}

.option-text small {
    color: #6b7280;
    line-height: 1.4;
    font-size: 13px;
}

.form-group {
    margin-bottom: 25px;
}

.form-label {
    display: block;
    font-weight: 600;
    color: #374151;
    margin-bottom: 10px;
    font-size: 14px;
}

.form-control {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.3s ease;
    resize: vertical;
}

.form-control:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    padding-top: 25px;
    border-top: 1px solid #e5e7eb;
}

.btn {
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-primary {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
}

.btn-secondary {
    background: #6b7280;
    color: white;
}

.btn-secondary:hover {
    background: #4b5563;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(107, 114, 128, 0.4);
}

/* Responsive Design */
@media (max-width: 768px) {
    .top-bar {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }

    .action-header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }

    .product-display {
        flex-direction: column;
        text-align: center;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }

    .meta-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }

    .container {
        padding: 20px 15px;
    }
}
</style>
@endsection
