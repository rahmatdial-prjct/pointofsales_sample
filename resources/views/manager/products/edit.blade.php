@extends('layouts.app')

@section('content')
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="page-title">
            <h1>Edit Produk</h1>
            <p>Ubah informasi produk {{ $product->name }}</p>
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
            <a href="{{ route('manager.products.index') }}">Produk</a>
            <span class="separator">/</span>
            <span class="current">Edit {{ $product->name }}</span>
        </nav>

        @if ($errors->any())
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <div>
                    <strong>Terjadi kesalahan:</strong>
                    <ul class="error-list">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Product Form -->
        <div class="card">
            <div class="card-header">
                <h2 class="heading-secondary">Edit Produk</h2>
                <div class="product-status">
                    <span class="status-badge {{ $product->is_active ? 'active' : 'inactive' }}">
                        {{ $product->is_active ? 'Aktif' : 'Tidak Aktif' }}
                    </span>
                </div>
            </div>

            <form action="{{ route('manager.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="product-form">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    <!-- Basic Information -->
                    <div class="form-section">
                        <h3 class="section-title">Informasi Dasar</h3>

                        <div class="current-info">
                            <div class="info-item">
                                <span class="info-label">SKU:</span>
                                <code class="info-value">{{ $product->sku }}</code>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Dibuat:</span>
                                <span class="info-value">{{ $product->created_at->format('d M Y') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name" class="form-label">
                                <i class="fas fa-tag"></i>
                                Nama Produk
                                <span class="required">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}"
                                   class="form-input" placeholder="Masukkan nama produk" required>
                        </div>

                        <div class="form-group">
                            <label for="category_id" class="form-label">
                                <i class="fas fa-folder"></i>
                                Kategori
                                <span class="required">*</span>
                            </label>
                            <select name="category_id" id="category_id" class="form-select" required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left"></i>
                                Deskripsi
                            </label>
                            <textarea name="description" id="description" class="form-textarea"
                                      rows="4" placeholder="Deskripsi produk (opsional)">{{ old('description', $product->description) }}</textarea>
                        </div>
                    </div>

                    <!-- Pricing & Stock -->
                    <div class="form-section">
                        <h3 class="section-title">Harga & Stok</h3>

                        <div class="form-group">
                            <label for="base_price" class="form-label">
                                <i class="fas fa-money-bill"></i>
                                Harga Dasar
                                <span class="required">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-prefix">Rp</span>
                                <input type="number" name="base_price" id="base_price" value="{{ old('base_price', $product->base_price) }}"
                                       class="form-input" min="0" step="1" placeholder="0" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="stock" class="form-label">
                                <i class="fas fa-boxes"></i>
                                Stok
                                <span class="required">*</span>
                            </label>
                            <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}"
                                   class="form-input" min="0" placeholder="0" required>
                            <small class="form-help">Jumlah stok yang tersedia</small>
                        </div>

                        <div class="form-group">
                            <label for="is_active" class="form-label">
                                <i class="fas fa-toggle-on"></i>
                                Status Produk
                            </label>
                            <div class="radio-group">
                                <label class="radio-option">
                                    <input type="radio" name="is_active" value="1" {{ old('is_active', $product->is_active) == '1' ? 'checked' : '' }}>
                                    <span class="radio-label">Aktif</span>
                                    <small>Produk dapat dijual</small>
                                </label>
                                <label class="radio-option">
                                    <input type="radio" name="is_active" value="0" {{ old('is_active', $product->is_active) == '0' ? 'checked' : '' }}>
                                    <span class="radio-label">Tidak Aktif</span>
                                    <small>Produk tidak dapat dijual</small>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Product Image -->
                    <div class="form-section full-width">
                        <h3 class="section-title">Gambar Produk</h3>

                        @if($product->image)
                            <div class="current-image">
                                <h4 class="image-title">Gambar Saat Ini</h4>
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="current-img">
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="image" class="form-label">
                                <i class="fas fa-image"></i>
                                {{ $product->image ? 'Ganti Gambar' : 'Upload Gambar' }}
                            </label>
                            <div class="file-upload">
                                <input type="file" name="image" id="image" class="file-input" accept="image/*">
                                <div class="file-upload-area">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p>Klik untuk upload atau drag & drop</p>
                                    <small>Format: JPG, PNG, GIF (Max: 2MB)</small>
                                </div>
                                <div class="image-preview" id="imagePreview" style="display: none;">
                                    <img id="previewImg" src="" alt="Preview">
                                    <button type="button" class="remove-image" onclick="removeImage()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('manager.products.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <style>
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
        }

        .breadcrumb a {
            color: #6b7280;
            text-decoration: none;
        }

        .breadcrumb a:hover {
            color: #3b82f6;
        }

        .breadcrumb .separator {
            color: #d1d5db;
        }

        .breadcrumb .current {
            color: #1f2937;
            font-weight: 500;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .product-status {
            display: flex;
            align-items: center;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .status-badge.active {
            background: #d1fae5;
            color: #065f46;
        }

        .status-badge.inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        .product-form {
            padding: 2rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .form-section {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .form-section.full-width {
            grid-column: 1 / -1;
        }

        .section-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e5e7eb;
        }

        .current-info {
            background: #f8fafc;
            padding: 1rem;
            border-radius: 0.5rem;
            border: 1px solid #e5e7eb;
            margin-bottom: 1rem;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
        }

        .info-item:not(:last-child) {
            border-bottom: 1px solid #e5e7eb;
        }

        .info-label {
            font-weight: 500;
            color: #6b7280;
            font-size: 0.875rem;
        }

        .info-value {
            font-weight: 600;
            color: #1f2937;
        }

        .info-value code {
            background: #f3f4f6;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-family: monospace;
            font-size: 0.875rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            color: #374151;
            font-size: 0.875rem;
        }

        .form-label i {
            color: #6b7280;
        }

        .required {
            color: #ef4444;
        }

        .form-input, .form-select, .form-textarea {
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .input-group {
            display: flex;
            align-items: center;
        }

        .input-prefix {
            padding: 0.75rem 1rem;
            background: #f3f4f6;
            border: 1px solid #d1d5db;
            border-right: none;
            border-radius: 0.5rem 0 0 0.5rem;
            font-weight: 500;
            color: #6b7280;
        }

        .input-group .form-input {
            border-radius: 0 0.5rem 0.5rem 0;
        }

        .form-help {
            font-size: 0.75rem;
            color: #6b7280;
        }

        .radio-group {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .radio-option {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: border-color 0.2s, background-color 0.2s;
        }

        .radio-option:hover {
            border-color: #3b82f6;
            background: #f8fafc;
        }

        .radio-option input[type="radio"] {
            margin: 0;
        }

        .radio-option input[type="radio"]:checked + .radio-label {
            color: #3b82f6;
            font-weight: 600;
        }

        .radio-label {
            font-weight: 500;
            color: #374151;
        }

        .radio-option small {
            display: block;
            color: #6b7280;
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }

        .current-image {
            margin-bottom: 1.5rem;
        }

        .image-title {
            font-size: 1rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.75rem;
        }

        .current-img {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 0.5rem;
            border: 1px solid #e5e7eb;
        }

        .file-upload {
            position: relative;
        }

        .file-input {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .file-upload-area {
            border: 2px dashed #d1d5db;
            border-radius: 0.5rem;
            padding: 2rem;
            text-align: center;
            transition: border-color 0.2s, background-color 0.2s;
        }

        .file-upload-area:hover {
            border-color: #3b82f6;
            background: #f8fafc;
        }

        .file-upload-area i {
            font-size: 2rem;
            color: #9ca3af;
            margin-bottom: 1rem;
        }

        .file-upload-area p {
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .file-upload-area small {
            color: #6b7280;
        }

        .image-preview {
            position: relative;
            display: inline-block;
            margin-top: 1rem;
        }

        .image-preview img {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 0.5rem;
            border: 1px solid #e5e7eb;
        }

        .remove-image {
            position: absolute;
            top: -0.5rem;
            right: -0.5rem;
            width: 2rem;
            height: 2rem;
            background: #ef4444;
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            padding-top: 2rem;
            border-top: 1px solid #e5e7eb;
        }

        .alert {
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .error-list {
            margin: 0.5rem 0 0 0;
            padding-left: 1rem;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .product-form {
                padding: 1rem;
            }

            .form-actions {
                flex-direction: column;
            }

            .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
        }
    </style>

    <script>
        // Image preview functionality
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImg').src = e.target.result;
                    document.getElementById('imagePreview').style.display = 'block';
                    document.querySelector('.file-upload-area').style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        });

        function removeImage() {
            document.getElementById('image').value = '';
            document.getElementById('imagePreview').style.display = 'none';
            document.querySelector('.file-upload-area').style.display = 'block';
        }

        // Format price input - allow manual input without auto-rounding
        document.getElementById('base_price').addEventListener('input', function(e) {
            // Only allow numbers, no formatting or rounding
            let value = e.target.value.replace(/[^\d]/g, '');
            e.target.value = value;
        });
    </script>
@endsection
