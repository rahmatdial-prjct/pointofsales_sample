@extends('layouts.app')

@section('content')
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="page-title">
            <h1>Edit Kategori</h1>
            <p>Ubah informasi kategori {{ $category->name }}</p>
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
            <a href="{{ route('manager.categories.index') }}">Kategori</a>
            <span class="separator">/</span>
            <span class="current">Edit {{ $category->name }}</span>
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

        <!-- Category Edit Form -->
        <div class="card">
            <div class="card-header">
                <h2 class="heading-secondary">Edit Kategori</h2>
                <div class="category-info">
                    <span class="info-badge">
                        <i class="fas fa-box"></i>
                        {{ $category->products_count ?? 0 }} produk
                    </span>
                </div>
            </div>

            <form action="{{ route('manager.categories.update', $category) }}" method="POST" class="category-form">
                @csrf
                @method('PUT')

                <div class="form-section">
                    <div class="form-group">
                        <label for="name" class="form-label">
                            <i class="fas fa-tag"></i>
                            Nama Kategori
                            <span class="required">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}"
                               class="form-input" placeholder="Masukkan nama kategori" required>
                        <small class="form-help">
                            <i class="fas fa-info-circle"></i>
                            Mengubah nama kategori akan mempengaruhi semua produk dalam kategori ini
                        </small>
                    </div>

                    <div class="current-info">
                        <h3 class="info-title">Informasi Saat Ini</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="info-label">Slug:</span>
                                <code class="info-value">{{ $category->slug }}</code>
                            </div>

                            <div class="info-item">
                                <span class="info-label">Jumlah produk:</span>
                                <span class="info-value">{{ $category->products_count ?? 0 }} produk</span>
                            </div>
                        </div>
                    </div>

                    <div class="category-preview">
                        <h3 class="preview-title">Preview Kategori</h3>
                        <div class="preview-card">
                            <div class="preview-icon">
                                <i class="fas fa-folder"></i>
                            </div>
                            <div class="preview-content">
                                <div class="preview-name" id="previewName">{{ $category->name }}</div>
                                <div class="preview-desc">Kategori untuk mengelompokkan produk</div>
                                <div class="preview-slug" id="previewSlug">{{ $category->slug }}</div>
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
                    <a href="{{ route('manager.categories.index') }}" class="btn btn-secondary">
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

        .category-info {
            display: flex;
            align-items: center;
        }

        .info-badge {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: #e5e7eb;
            color: #374151;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .category-form {
            padding: 2rem;
        }

        .form-section {
            display: flex;
            flex-direction: column;
            gap: 2rem;
            margin-bottom: 2rem;
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

        .form-input {
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-help {
            font-size: 0.75rem;
            color: #6b7280;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .current-info {
            background: #f8fafc;
            padding: 1.5rem;
            border-radius: 0.5rem;
            border: 1px solid #e5e7eb;
        }

        .info-title {
            font-size: 1rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 1rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .info-label {
            font-size: 0.75rem;
            color: #6b7280;
            font-weight: 500;
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

        .category-preview {
            background: #f8fafc;
            padding: 1.5rem;
            border-radius: 0.5rem;
            border: 1px solid #e5e7eb;
        }

        .preview-title {
            font-size: 1rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 1rem;
        }

        .preview-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            background: white;
            padding: 1rem;
            border-radius: 0.5rem;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .preview-icon {
            width: 40px;
            height: 40px;
            background: #3b82f6;
            color: white;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .preview-content {
            flex: 1;
        }

        .preview-name {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }

        .preview-desc {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 0.25rem;
        }

        .preview-slug {
            font-size: 0.75rem;
            color: #9ca3af;
            font-family: monospace;
            background: #f3f4f6;
            padding: 0.125rem 0.375rem;
            border-radius: 0.25rem;
            display: inline-block;
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
            .category-form {
                padding: 1rem;
            }

            .form-actions {
                flex-direction: column;
            }

            .preview-card {
                flex-direction: column;
                text-align: center;
            }

            .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <script>
        // Live preview of category name
        document.getElementById('name').addEventListener('input', function(e) {
            const previewName = document.getElementById('previewName');
            const previewSlug = document.getElementById('previewSlug');
            const value = e.target.value.trim();

            previewName.textContent = value || '{{ $category->name }}';

            // Generate slug preview
            const slug = value.toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim('-');

            previewSlug.textContent = slug || '{{ $category->slug }}';
        });

        // Auto-capitalize first letter
        document.getElementById('name').addEventListener('input', function(e) {
            const value = e.target.value;
            if (value.length === 1) {
                e.target.value = value.charAt(0).toUpperCase();
            }
        });
    </script>
@endsection
