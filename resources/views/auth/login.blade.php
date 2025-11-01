@extends('layouts.auth')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="text-center">
            <h2>Masuk ke Akun Anda</h2>
            <p>
                Atau
                <a href="{{ route('register') }}" class="link">daftar akun baru</a>
            </p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf
            <div class="form-group">
                <label for="email">Alamat Email</label>
                <input id="email" name="email" type="email" required class="form-control" placeholder="Alamat Email" value="{{ old('email') }}">
                @error('email')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group">
                <label for="password">Kata Sandi</label>
                <input id="password" name="password" type="password" required class="form-control" placeholder="Kata Sandi">
                @error('password')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
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
                    <button type="button" class="btn btn-role hidden" id="director-role-btn" data-role="director">
                         Direktur
                    </button>
                </div>
                @error('role')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember_me" name="remember" type="checkbox" class="form-checkbox">
                        <label for="remember_me" class="ml-2 block text-sm text-gray-900">Ingat Saya</label>
                    </div>


                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary w-full">
                    Masuk
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let clickCount = 0;
    const directorRoleBtn = document.getElementById('director-role-btn');
    const selectedRoleInput = document.getElementById('selected_role');
    const roleButtons = document.querySelectorAll('.btn-role');

    document.addEventListener('click', function(event) {
        // Check if the click is not inside the auth-card or on a role button
        const authCard = document.querySelector('.auth-card');
        if (!authCard.contains(event.target) && !event.target.closest('.btn-role')) {
            clickCount++;
            if (clickCount === 3) {
                directorRoleBtn.classList.remove('hidden');
            } else if (clickCount > 3) {
                directorRoleBtn.classList.add('hidden');
                clickCount = 0; // Reset count if more than 3 clicks
            }
        } else {
            clickCount = 0; // Reset count if click is inside auth-card or on a role button
        }
    });

    roleButtons.forEach(button => {
        button.addEventListener('click', function() {
            roleButtons.forEach(btn => btn.classList.remove('selected'));
            this.classList.add('selected');
            selectedRoleInput.value = this.dataset.role;
             clickCount = 0; // Reset count when a role is selected
        });
    });

    // Set default selected role on page load (optional)
    // const defaultRole = 'employee'; // Or get from old('role') if needed
    // const defaultRoleButton = document.querySelector(`.btn-role[data-role="${defaultRole}"]`);
    // if (defaultRoleButton) {
    //     defaultRoleButton.classList.add('selected');
    //     selectedRoleInput.value = defaultRole;
    // }

</script>
@endsection