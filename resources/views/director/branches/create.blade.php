@extends('layouts.app')

@section('title', 'Tambah Cabang Baru')

@section('content')
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="page-title">
            <h1>Tambah Cabang Baru</h1>
            <p>Form untuk menambahkan cabang toko baru</p>
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
            <a href="{{ route('director.branches.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Cabang
            </a>
        </div>

        @if ($errors->any())
            <div class="alert alert-error">
                <div class="alert-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="alert-content">
                    <h4>Terjadi Kesalahan!</h4>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Form Card -->
        <div class="card">
            <div class="card-header">
                <h2 class="heading-secondary">
                    <i class="fas fa-store"></i>
                    Informasi Cabang Baru
                </h2>
            </div>
            <div class="card-body">
                <form action="{{ route('director.branches.store') }}" method="POST" class="branch-form">
                    @csrf

                    <div class="form-grid">
                        <!-- Informasi Dasar Cabang -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-store"></i>
                                Informasi Dasar
                            </h3>

                            <div class="form-group">
                                <label for="name" class="form-label">
                                    <i class="fas fa-building"></i>
                                    Nama Cabang
                                    <span class="required">*</span>
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}"
                                       required class="form-input" placeholder="Masukkan nama cabang">
                                <small class="form-help">Nama cabang akan ditampilkan di sistem</small>
                            </div>

                            <div class="form-group">
                                <label for="operational_area" class="form-label">
                                    <i class="fas fa-map-marker-alt"></i>
                                    Wilayah Operasional
                                </label>
                                <input type="text" name="operational_area" id="operational_area"
                                       value="{{ old('operational_area') }}"
                                       class="form-input" placeholder="Contoh: Jakarta Pusat">
                                <small class="form-help">Area geografis operasional cabang</small>
                            </div>
                        </div>

                        <!-- Informasi Kontak -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-address-book"></i>
                                Informasi Kontak
                            </h3>

                            <div class="form-group">
                                <label for="phone" class="form-label">
                                    <i class="fas fa-phone"></i>
                                    Telepon
                                </label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                       class="form-input" placeholder="Contoh: 021-12345678">
                                <small class="form-help">Nomor telepon untuk dihubungi</small>
                            </div>

                            <div class="form-group">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope"></i>
                                    Email
                                </label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}"
                                       class="form-input" placeholder="cabang@email.com">
                                <small class="form-help">Email resmi cabang</small>
                            </div>
                        </div>

                        <!-- Alamat Lengkap (Full Width) -->
                        <div class="form-section full-width">
                            <h3 class="section-title">
                                <i class="fas fa-map"></i>
                                Alamat Lengkap
                            </h3>

                            <div class="form-group">
                                <label for="address" class="form-label">
                                    <i class="fas fa-map-marked-alt"></i>
                                    Alamat Lengkap
                                    <span class="required">*</span>
                                </label>
                                <textarea name="address" id="address" required class="form-input" rows="4"
                                          placeholder="Masukkan alamat lengkap cabang termasuk kode pos">{{ old('address') }}</textarea>
                                <small class="form-help">Alamat lengkap termasuk jalan, nomor, kelurahan, kecamatan, kota, dan kode pos</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <span class="btn-text">
                                <i class="fas fa-save"></i> Simpan Cabang
                            </span>
                            <span class="btn-loading" style="display: none;">
                                <i class="fas fa-spinner fa-spin"></i> Menyimpan...
                            </span>
                        </button>
                        <a href="{{ route('director.branches.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .back-button {
            margin-bottom: 1.5rem;
        }

        .branch-form {
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
            color: #6b7280;
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
            width: 16px;
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
            background: white;
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

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            padding-top: 2rem;
            border-top: 1px solid #e5e7eb;
        }

        .btn-loading {
            display: none;
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        @media (max-width: 768px) {
            .branch-form {
                padding: 1rem;
            }

            .form-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .form-actions {
                flex-direction: column-reverse;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoading = submitBtn.querySelector('.btn-loading');

            form.addEventListener('submit', function() {
                // Show loading state
                btnText.style.display = 'none';
                btnLoading.style.display = 'inline-flex';
                submitBtn.disabled = true;

                // Disable all form inputs
                const inputs = form.querySelectorAll('input, select, textarea');
                inputs.forEach(input => input.disabled = true);
            });

            // Real-time validation feedback
            const requiredInputs = form.querySelectorAll('input[required], textarea[required]');
            requiredInputs.forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.value.trim() === '') {
                        this.style.borderColor = '#ef4444';
                    } else {
                        this.style.borderColor = '#10b981';
                    }
                });

                input.addEventListener('input', function() {
                    if (this.value.trim() !== '') {
                        this.style.borderColor = '#10b981';
                    }
                });
            });
        });
    </script>
@endsection
