<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#3b82f6">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="CATAT">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <title>@yield('title', 'CATAT')</title>

    <!-- Flash Messages for Toast -->
    @if(session('success'))
        <meta name="flash-success" content="{{ session('success') }}">
    @endif
    @if(session('error'))
        <meta name="flash-error" content="{{ session('error') }}">
    @endif
    @if(session('warning'))
        <meta name="flash-warning" content="{{ session('warning') }}">
    @endif
    @if(session('info'))
        <meta name="flash-info" content="{{ session('info') }}">
    @endif
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="{{ asset('js/toast.js') }}"></script>
    <script src="{{ asset('js/mobile.js') }}"></script>
    @stack('styles')
    @stack('scripts')
</head>
<body>
    <!-- Sidebar Navigation -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('login') }}" class="logo">
                <img src="{{ asset('logo.png') }}" alt="CATAT Logo" style="height: 160px; width: auto;" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <div style="display: none; color: white; font-size: 24px; font-weight: 700; text-align: center; padding: 20px;">
                    <i class="fas fa-clipboard-list" style="font-size: 48px; margin-bottom: 10px; display: block;"></i>
                    CATAT
                </div>
            </a>

            <!-- Welcome Message -->
            @if(auth()->check())
            <div class="welcome-message">
                <div style="color: white; font-size: 13px; font-weight: 600; text-shadow: 0 1px 2px rgb(171, 161, 161); line-height: 1.4; text-align: center;">
                    Selamat Datang<br>
                    <span style="font-size: 15px; font-weight: 700;">{{ Auth::user()->name }}</span><br>
                    <span style="font-size: 11px; opacity: 0.9;">
                        Di Website
                        @if(Auth::user()->role === 'director')
                            Direktur
                        @elseif(Auth::user()->role === 'manager')
                            Manajer
                        @elseif(Auth::user()->role === 'employee')
                            Pegawai
                        @else
                            {{ ucfirst(Auth::user()->role) }}
                        @endif
                    </span>
                </div>
            </div>
            @endif
        </div>

        <ul class="nav-list">
            @if(auth()->check() && auth()->user()->isDirector())
                <a href="{{ route('director.dashboard') }}" class="nav-item {{ request()->routeIs('director.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span class="nav-text">Beranda</span>
                </a>
                <a href="{{ route('director.branches.index') }}" class="nav-item {{ request()->routeIs('director.branches.*') ? 'active' : '' }}">
                    <i class="fas fa-store"></i>
                    <span class="nav-text">Manajemen Cabang</span>
                </a>
                <a href="{{ route('director.users.index') }}" class="nav-item {{ request()->routeIs('director.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span class="nav-text">Manajemen Akun</span>
                </a>
                <a href="{{ route('director.reports.integrated') }}" class="nav-item {{ request()->routeIs('director.reports.integrated') ? 'active' : '' }}">
                    <i class="fas fa-layer-group"></i>
                    <span class="nav-text">Laporan Terintegrasi</span>
                </a>
            @elseif(auth()->check() && auth()->user()->isManager())
                <a href="{{ route('manager.dashboard') }}" class="nav-item {{ request()->routeIs('manager.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span class="nav-text">Beranda</span>
                </a>
                <a href="{{ route('manager.products.index') }}" class="nav-item {{ request()->routeIs('manager.products.*') ? 'active' : '' }}">
                    <i class="fas fa-box"></i>
                    <span class="nav-text">Manajemen Produk</span>
                </a>
                <a href="{{ route('manager.categories.index') }}" class="nav-item {{ request()->routeIs('manager.categories.*') ? 'active' : '' }}">
                    <i class="fas fa-tags"></i>
                    <span class="nav-text">Manajemen Kategori</span>
                </a>
                <a href="{{ route('manager.employees.index') }}" class="nav-item {{ request()->routeIs('manager.employees.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span class="nav-text">Manajemen Akun</span>
                </a>
                <a href="{{ route('manager.returns.index') }}" class="nav-item {{ request()->routeIs('manager.returns.*') ? 'active' : '' }}">
                    <i class="fas fa-undo"></i>
                    <span class="nav-text">Persetujuan Retur</span>
                </a>
                <a href="{{ route('manager.damaged-stock.index') }}" class="nav-item {{ request()->routeIs('manager.damaged-stock.*') ? 'active' : '' }}">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span class="nav-text">Barang Rusak</span>
                </a>
                <a href="{{ route('manager.reports.integrated') }}" class="nav-item {{ request()->routeIs('manager.reports.integrated') ? 'active' : '' }}">
                    <i class="fas fa-layer-group"></i>
                    <span class="nav-text">Laporan Terintegrasi</span>
                </a>
            @elseif(auth()->check() && auth()->user()->isEmployee())
                <a href="{{ route('employee.dashboard') }}" class="nav-item {{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span class="nav-text">Beranda</span>
                </a>
                <a href="{{ route('employee.transactions.create') }}" class="nav-item {{ request()->routeIs('employee.transactions.*') ? 'active' : '' }}">
                    <i class="fas fa-cash-register"></i>
                    <span class="nav-text">Transaksi Penjualan</span>
                </a>
                <a href="{{ route('employee.returns.index') }}" class="nav-item {{ request()->routeIs('employee.returns.*') ? 'active' : '' }}">
                    <i class="fas fa-undo"></i>
                    <span class="nav-text">Retur Barang</span>
                </a>
                <a href="{{ route('employee.stocks.index') }}" class="nav-item {{ request()->routeIs('employee.stocks.*') ? 'active' : '' }}">
                    <i class="fas fa-boxes"></i>
                    <span class="nav-text">Stok Produk</span>
                </a>
            @endif

            <a href="{{ route('logout') }}" class="nav-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i>
                <span class="nav-text">Keluar</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        {{-- Top Bar - Moved to individual views --}}
        {{-- Page-specific Content --}}
        @yield('content')
    </main>

    {{-- Include global scripts or page-specific scripts here if needed --}}
    <script>
        // Auto-refresh CSRF token every 30 minutes to prevent expiration
        setInterval(function() {
            fetch('/csrf-token')
                .then(response => response.json())
                .then(data => {
                    const metaTag = document.querySelector('meta[name="csrf-token"]');
                    if (metaTag) {
                        metaTag.setAttribute('content', data.token);
                    }
                    // Update all CSRF token inputs
                    document.querySelectorAll('input[name="_token"]').forEach(input => {
                        input.value = data.token;
                    });
                })
                .catch(error => {
                    console.log('CSRF token refresh failed:', error);
                });
        }, 30 * 60 * 1000); // 30 minutes

        // Handle logout form submission with better error handling
        document.addEventListener('DOMContentLoaded', function() {
            const logoutForm = document.getElementById('logout-form');
            if (logoutForm) {
                logoutForm.addEventListener('submit', function(e) {
                    // If CSRF token is missing or expired, redirect to login
                    const csrfTokenInput = logoutForm.querySelector('input[name="_token"]');
                    if (!csrfTokenInput || !csrfTokenInput.value) {
                        e.preventDefault();
                        window.location.href = '{{ route("login") }}';
                        return false;
                    }
                });
            }

            // Handle logout link click with fallback
            const logoutLink = document.querySelector('a[href="{{ route("logout") }}"]');
            if (logoutLink) {
                logoutLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = document.getElementById('logout-form');
                    if (form) {
                        form.submit();
                    } else {
                        // Fallback: redirect to logout GET route
                        window.location.href = '{{ route("logout.get") }}';
                    }
                });
            }
        });
    </script>
</body>
</html>
