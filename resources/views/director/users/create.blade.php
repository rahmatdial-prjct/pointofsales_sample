@extends('layouts.app')

@section('title', 'Tambah Pengguna Baru')

@section('content')
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="page-title">
            <h1>Tambah Pengguna Baru</h1>
            <p>Form untuk menambahkan Manajer atau Pegawai baru</p>
        </div>
        <div class="user-info">
            <div class="user-details">
                <div class="name">{{ Auth::user()->name }}</div>
                <div class="role">{{ Auth::user()->role === 'director' ? 'Direktur' : (Auth::user()->role === 'manager' ? 'Manajer' : 'Pegawai') }}</div>
            </div>
            <div class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
        </div>
    </div>

    <div class="container">
        <!-- Back Button -->
        <div class="back-button">
            <a href="{{ route('director.users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pengguna
            </a>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <div class="alert-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="alert-content">
                    <h4>Terdapat kesalahan pada form:</h4>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Form Card -->
        <div class="form-card">
            <div class="form-header">
                <h3 class="form-title">
                    <i class="fas fa-users"></i>
                    Informasi Pengguna
                </h3>
                <p class="form-subtitle">Lengkapi data pengguna dengan benar</p>
            </div>

            <form action="{{ route('director.users.store') }}" method="POST" class="form-body">
                @csrf

                <div class="form-grid">
                    <!-- Informasi Personal -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="fas fa-user"></i>
                            Informasi Personal
                        </h3>

                        <div class="form-group">
                            <label for="name" class="form-label">
                                <i class="fas fa-user"></i>
                                Nama Lengkap
                                <span class="required">*</span>
                            </label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   value="{{ old('name') }}"
                                   required
                                   class="form-input"
                                   placeholder="Masukkan nama lengkap" />
                            <small class="form-help">Nama lengkap pengguna sesuai identitas</small>
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i>
                                Email
                                <span class="required">*</span>
                            </label>
                            <input type="email"
                                   name="email"
                                   id="email"
                                   value="{{ old('email') }}"
                                   required
                                   class="form-input"
                                   placeholder="user@example.com" />
                            <small class="form-help">Email akan digunakan untuk login ke sistem</small>
                        </div>
                    </div>

                    <!-- Informasi Akun -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="fas fa-cog"></i>
                            Informasi Akun
                        </h3>

                        <div class="form-group">
                            <label for="role" class="form-label">
                                <i class="fas fa-user-tag"></i>
                                Role
                                <span class="required">*</span>
                            </label>
                            <select name="role" id="role" required class="form-select">
                                <option value="">Pilih Role</option>
                                <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>
                                    Manajer
                                </option>
                                <option value="employee" {{ old('role') == 'employee' ? 'selected' : '' }}>
                                    Pegawai
                                </option>
                            </select>
                            <small class="form-help">Tentukan level akses pengguna</small>
                        </div>

                        <div class="form-group">
                            <label for="branch_id" class="form-label">
                                <i class="fas fa-building"></i>
                                Cabang
                            </label>
                            <select name="branch_id" id="branch_id" class="form-select">
                                <option value="">Pilih Cabang (Opsional)</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-help">Cabang tempat pengguna bertugas</small>
                        </div>
                    </div>

                    <!-- Keamanan Akun (Full Width) -->
                    <div class="form-section full-width">
                        <h3 class="section-title">
                            <i class="fas fa-shield-alt"></i>
                            Keamanan Akun
                        </h3>

                        <div class="password-grid">
                            <div class="form-group">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock"></i>
                                    Password
                                    <span class="required">*</span>
                                </label>
                                <div class="password-input-wrapper">
                                    <input type="password"
                                           name="password"
                                           id="password"
                                           required
                                           class="form-input"
                                           placeholder="Minimal 8 karakter" />
                                    <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                        <i class="fas fa-eye" id="password-eye"></i>
                                    </button>
                                </div>
                                <small class="form-help">Password minimal 8 karakter, kombinasi huruf dan angka</small>
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation" class="form-label">
                                    <i class="fas fa-lock"></i>
                                    Konfirmasi Password
                                    <span class="required">*</span>
                                </label>
                                <div class="password-input-wrapper">
                                    <input type="password"
                                           name="password_confirmation"
                                           id="password_confirmation"
                                           required
                                           class="form-input"
                                           placeholder="Ulangi password" />
                                    <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                                        <i class="fas fa-eye" id="password_confirmation-eye"></i>
                                    </button>
                                </div>
                                <small class="form-help">Masukkan ulang password untuk konfirmasi</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Simpan Pengguna
                    </button>
                    <a href="{{ route('director.users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 2rem;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .page-title i {
            color: #3b82f6;
        }

        .page-subtitle {
            color: #6b7280;
            margin: 0.5rem 0 0 0;
            font-size: 1rem;
        }

        .header-actions {
            flex-shrink: 0;
        }

        .alert {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            display: flex;
            gap: 1rem;
        }

        .alert-danger {
            background: #fef2f2;
            border-color: #fecaca;
        }

        .alert-icon {
            color: #dc2626;
            font-size: 1.25rem;
            flex-shrink: 0;
            margin-top: 0.125rem;
        }

        .alert-content h4 {
            color: #dc2626;
            font-weight: 600;
            margin: 0 0 0.5rem 0;
        }

        .alert-content ul {
            margin: 0;
            padding-left: 1.25rem;
            color: #7f1d1d;
        }

        .form-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05), 0 10px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .form-header {
            background: linear-gradient(135deg, #87ceeb 0%, #4fc3f7 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .form-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            color: white !important;
        }

        .form-subtitle {
            margin: 0.5rem 0 0 0;
            opacity: 0.9;
            color: white !important;
        }

        .form-body {
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
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-title i {
            color: #3b82f6;
        }

        .password-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
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
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .form-label i {
            color: #6b7280;
            width: 16px;
        }

        .required {
            color: #ef4444;
        }

        .form-help {
            font-size: 0.75rem;
            color: #6b7280;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .form-input, .form-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.2s ease;
            background: #f9fafb;
        }

        /* Specific styling for select dropdowns only */
        .form-select, select.form-select {
            appearance: none !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6,9 12,15 18,9'%3e%3c/polyline%3e%3c/svg%3e") !important;
            background-position: right 0.75rem center !important;
            background-repeat: no-repeat !important;
            background-size: 1.5em 1.5em !important;
            padding-right: 2.5rem !important;
        }

        .form-input:focus, .form-select:focus {
            outline: none;
            border-color: #3b82f6;
            background: white;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .password-input-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6b7280;
            cursor: pointer;
            padding: 0.25rem;
            border-radius: 4px;
            transition: color 0.2s ease;
        }

        .password-toggle:hover {
            color: #374151;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.875rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #87ceeb 0%, #4fc3f7 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(135, 206, 235, 0.4);
        }

        .btn-secondary {
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #d1d5db;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
            transform: translateY(-1px);
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .header-content {
                flex-direction: column;
                gap: 1rem;
            }

            .form-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .password-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .form-actions {
                flex-direction: column-reverse;
            }

            .page-title {
                font-size: 1.5rem;
            }
        }
    </style>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const eye = document.getElementById(inputId + '-eye');

            if (input.type === 'password') {
                input.type = 'text';
                eye.classList.remove('fa-eye');
                eye.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                eye.classList.remove('fa-eye-slash');
                eye.classList.add('fa-eye');
            }
        }
    </script>
