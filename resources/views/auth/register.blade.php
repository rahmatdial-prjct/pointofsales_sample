@extends('layouts.auth')

@section('content')
<div class="auth-container" id="auth-container">
    <div class="auth-card">
        <div class="text-center">
            <h2>Daftar Akun Baru</h2>
            <p>
                Atau
                <a href="{{ route('login') }}" class="link">masuk ke akun Anda</a>
            </p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input id="name" name="name" type="text" required class="form-control" placeholder="Nama lengkap" value="{{ old('name') }}">
            </div>
            <div class="form-group">
                <label for="email">Alamat Email</label>
                <input id="email" name="email" type="email" required class="form-control" placeholder="Alamat email" value="{{ old('email') }}">
            </div>
            <div class="form-group">
                <label for="password">Kata Sandi</label>
                <input id="password" name="password" type="password" required class="form-control" placeholder="Kata sandi">
            </div>
            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required class="form-control" placeholder="Konfirmasi kata sandi">
            </div>

            <div class="form-group">
                <label class="block text-sm font-medium text-gray-700">Pilih Peran Anda</label>
                <div class="role-selection" id="role-selection">
                    <input type="hidden" name="role" id="selected_role">
                    <button type="button" class="btn btn-role" data-role="manager">
                         Manajer
                    </button>
                    <button type="button" class="btn btn-role" data-role="employee">
                         Pegawai
                    </button>
                    <button type="button" class="btn btn-role" data-role="director" id="director-role-btn" style="display:none;">
                         Direktur
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label for="branch_id" class="block text-sm font-medium text-gray-700">Pilih Cabang</label>
                <select name="branch_id" id="branch_id" class="form-control" required>
                    <option value="">-- Pilih Cabang --</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary w-full" style="background-color: #3b82f6; color: white; font-weight: 600; padding: 0.75rem; border-radius: 0.375rem; border: none;">
                    Daftar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const roleButtons = document.querySelectorAll('.btn-role');
    const selectedRoleInput = document.getElementById('selected_role');
    const directorRoleBtn = document.getElementById('director-role-btn');
    const authContainer = document.getElementById('auth-container');

    roleButtons.forEach(button => {
        button.addEventListener('click', function() {
            roleButtons.forEach(btn => btn.classList.remove('selected'));
            this.classList.add('selected');
            selectedRoleInput.value = this.dataset.role;
        });
    });

    let clickCount = 0;
    authContainer.addEventListener('click', function(event) {
        // Only count clicks on empty area (not on buttons or inputs)
        if (event.target === authContainer) {
            clickCount++;
            if (clickCount === 3) {
                directorRoleBtn.style.display = 'inline-block';
            } else if (clickCount > 3) {
                directorRoleBtn.style.display = 'none';
            }
        }
    });
</script>
@endsection
