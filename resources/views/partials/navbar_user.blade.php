@php
    $isAuthenticated = $isAuthenticated ?? (bool) session('id_user');
    $displayName = $namaLengkap ?? session('nama_lengkap', 'Pengunjung');
    $displayEmail = session('email');
    $dashboardHref = $isAuthenticated ? route('dashboard') : route('login');
    $exploreHref = $isAuthenticated ? route('explore') : route('login');
@endphp

<nav class="navbar">
    <div class="navbar-inner">
        <a href="{{ $isAuthenticated ? route('dashboard') : route('about') }}" class="brand-logo-link" aria-label="BECEK">
            <img src="{{ asset('images/logo-becek.png') . '?v=logo-baru-20260709' }}" alt="Logo BECEK" class="brand-logo-img">
            <span class="brand-logo-text">BECEK</span>
        </a>

        <button type="button" class="mobile-menu-button" data-mobile-menu-button aria-label="Buka menu navigasi">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <div class="navbar-menu" data-mobile-menu>
            <ul class="nav-menu">
                <li class="{{ $activePage === 'dashboard' ? 'active' : '' }}"><a href="{{ $dashboardHref }}">Dashboard</a></li>
                <li class="{{ $activePage === 'explore' ? 'active' : '' }}"><a href="{{ $exploreHref }}">Explore</a></li>
                <li class="{{ $activePage === 'about' ? 'active' : '' }}"><a href="{{ route('about') }}">About Us</a></li>
            </ul>

            @if($isAuthenticated)
                <div class="profile-dropdown" data-profile-dropdown>
                    <button type="button" class="profile-trigger" data-profile-trigger aria-haspopup="true" aria-expanded="false">
                        <span class="profile-avatar">{{ strtoupper(substr($displayName, 0, 1)) }}</span>
                        <span>Profil</span>
                        <span class="chevron">⌄</span>
                    </button>

                    <div class="profile-menu" data-profile-menu>
                        <div class="profile-menu-header">
                            <small>Masuk sebagai</small>
                            <strong>{{ $displayName }}</strong>
                            @if($displayEmail)
                                <small>{{ $displayEmail }}</small>
                            @endif
                        </div>
                        <button type="button" class="profile-menu-item theme-menu-toggle theme-switch-row" data-theme-toggle>
                            <span class="theme-switch-copy">
                                <span data-theme-label>Ganti Tema</span>
                                <small data-theme-status>Mode gelap aktif</small>
                            </span>
                            <span class="theme-switch" aria-hidden="true">
                                <span class="theme-switch-thumb" data-theme-icon>🌙</span>
                            </span>
                        </button>
                        <a href="{{ route('favorites.index') }}" class="profile-menu-item">★ Tempat Favorit</a>
                        <button type="button" class="profile-menu-item danger" data-delete-account>Hapus Akun</button>
                        <a href="{{ route('logout') }}" class="profile-menu-item logout-item">Sign Out</a>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="profile-trigger guest-login-link">Masuk</a>
            @endif
        </div>
    </div>
</nav>

@if($isAuthenticated)
    <form id="deleteAccountForm" action="{{ route('account.destroy') }}" method="POST" class="hidden-form">
        @csrf
        <input type="hidden" name="password" id="deleteAccountPassword">
    </form>
@endif

<nav class="mobile-bottom-nav" aria-label="Navigasi utama mobile">
    <a href="{{ $dashboardHref }}" class="mobile-bottom-item {{ $activePage === 'dashboard' ? 'active' : '' }}">
        <span class="mobile-bottom-icon">⌂</span>
        <span>Dashboard</span>
    </a>
    <a href="{{ $exploreHref }}" class="mobile-bottom-item {{ $activePage === 'explore' ? 'active' : '' }}">
        <span class="mobile-bottom-icon">⌕</span>
        <span>Explore</span>
    </a>
    <a href="{{ route('about') }}" class="mobile-bottom-item {{ $activePage === 'about' ? 'active' : '' }}">
        <span class="mobile-bottom-icon">i</span>
        <span>About</span>
    </a>
</nav>

@if(session('account_error'))
    <script>alert('{{ session('account_error') }}');</script>
@endif
