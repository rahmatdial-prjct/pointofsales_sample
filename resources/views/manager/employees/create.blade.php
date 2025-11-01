@extends('layouts.app')

@section('content')
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="page-title">
            <h1>Tambah Pegawai Baru</h1>
            <p>Daftarkan pegawai baru untuk cabang Anda</p>
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
            <a href="{{ route('manager.employees.index') }}">Pegawai</a>
            <span class="separator">/</span>
            <span class="current">Tambah Baru</span>
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

        <!-- Employee Form -->
        <div class="card">
            <div class="card-header">
                <h2 class="heading-secondary">Informasi Pegawai</h2>
                <p class="text-muted">Lengkapi semua informasi pegawai dengan benar</p>
            </div>

            <form action="{{ route('manager.employees.store') }}" method="POST" class="employee-form">
                @csrf

                <div class="form-grid">
                    <!-- Personal Information -->
                    <div class="form-section">
                        <h3 class="section-title">Informasi Personal</h3>

                        <div class="form-group">
                            <label for="name" class="form-label">
                                <i class="fas fa-user"></i>
                                Nama Lengkap
                                <span class="required">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}"
                                   class="form-input" placeholder="Masukkan nama lengkap" required>
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i>
                                Email
                                <span class="required">*</span>
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                   class="form-input" placeholder="nama@email.com" required>
                            <small class="form-help">
                                <i class="fas fa-info-circle"></i>
                                Email akan digunakan untuk login ke sistem
                            </small>
                        </div>

                        <div class="branch-info">
                            <div class="info-item">
                                <span class="info-label">Cabang:</span>
                                <span class="info-value">{{ Auth::user()->branch->name ?? 'Tidak ada' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Role:</span>
                                <span class="info-value">Pegawai</span>
                            </div>
                        </div>
                    </div>

                    <!-- Security & Status -->
                    <div class="form-section">
                        <h3 class="section-title">Keamanan & Status</h3>

                        <div class="form-group">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock"></i>
                                Password
                                <span class="required">*</span>
                            </label>
                            <input type="password" name="password" id="password" class="form-input"
                                   placeholder="Minimal 8 karakter" required>
                            <small class="form-help">
                                <i class="fas fa-shield-alt"></i>
                                Minimal 8 karakter, kombinasi huruf dan angka
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">
                                <i class="fas fa-lock"></i>
                                Konfirmasi Password
                                <span class="required">*</span>
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="form-input" placeholder="Ulangi password" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-toggle-on"></i>
                                Status Pegawai
                            </label>
                            <div class="status-options">
                                <label class="status-option selected">
                                    <input type="radio" name="is_active" value="1" checked>
                                    <div class="status-card active">
                                        <i class="fas fa-user-check"></i>
                                        <div class="status-info">
                                            <div class="status-title">Aktif</div>
                                            <div class="status-desc">Pegawai dapat login dan bekerja</div>
                                        </div>
                                    </div>
                                </label>

                                <label class="status-option">
                                    <input type="radio" name="is_active" value="0">
                                    <div class="status-card inactive">
                                        <i class="fas fa-user-times"></i>
                                        <div class="status-info">
                                            <div class="status-title">Tidak Aktif</div>
                                            <div class="status-desc">Pegawai tidak dapat login</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Simpan Pegawai
                    </button>
                    <a href="{{ route('manager.employees.index') }}" class="btn btn-secondary">
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

        .employee-form {
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

        .section-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e5e7eb;
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

        .branch-info {
            background: #f8fafc;
            padding: 1rem;
            border-radius: 0.5rem;
            border: 1px solid #e5e7eb;
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

        .status-options {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .status-option {
            cursor: pointer;
        }

        .status-option input[type="radio"] {
            display: none;
        }

        .status-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 0.5rem;
            transition: all 0.2s;
        }

        .status-card:hover {
            border-color: #3b82f6;
            background: #f8fafc;
        }

        .status-option input[type="radio"]:checked + .status-card {
            border-color: #3b82f6;
            background: #eff6ff;
        }

        .status-card.active {
            border-color: #10b981;
        }

        .status-option input[type="radio"]:checked + .status-card.active {
            border-color: #10b981;
            background: #ecfdf5;
        }

        .status-card.inactive {
            border-color: #ef4444;
        }

        .status-option input[type="radio"]:checked + .status-card.inactive {
            border-color: #ef4444;
            background: #fef2f2;
        }

        .status-card i {
            font-size: 1.5rem;
            color: #6b7280;
        }

        .status-card.active i {
            color: #10b981;
        }

        .status-card.inactive i {
            color: #ef4444;
        }

        .status-info {
            flex: 1;
        }

        .status-title {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }

        .status-desc {
            font-size: 0.875rem;
            color: #6b7280;
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

        .text-muted {
            color: #6b7280;
            font-size: 0.875rem;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .employee-form {
                padding: 1rem;
            }

            .form-actions {
                flex-direction: column;
            }
        }
    </style>

    <script>
        // Password confirmation validation
        document.getElementById('password_confirmation').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmation = this.value;

            if (password && confirmation && password !== confirmation) {
                this.setCustomValidity('Password tidak cocok');
            } else {
                this.setCustomValidity('');
            }
        });

        // Password strength indicator
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const helpText = this.nextElementSibling;

            if (password.length < 8) {
                helpText.style.color = '#ef4444';
                helpText.innerHTML = '<i class="fas fa-times-circle"></i> Password minimal 8 karakter';
            } else if (password.length >= 8 && /^(?=.*[a-zA-Z])(?=.*\d)/.test(password)) {
                helpText.style.color = '#10b981';
                helpText.innerHTML = '<i class="fas fa-check-circle"></i> Password kuat';
            } else {
                helpText.style.color = '#f59e0b';
                helpText.innerHTML = '<i class="fas fa-exclamation-circle"></i> Tambahkan angka untuk password yang lebih kuat';
            }
        });

        // Auto-capitalize name
        document.getElementById('name').addEventListener('input', function(e) {
            const words = e.target.value.split(' ');
            const capitalizedWords = words.map(word =>
                word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()
            );
            e.target.value = capitalizedWords.join(' ');
        });
    </script>
@endsection
