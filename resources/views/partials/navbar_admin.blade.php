<nav class="navbar">
    <a href="{{ route('admin.dashboard') }}" class="brand-logo-link">
        <img src="{{ asset('images/logo-becek.png') . '?v=logo-baru-20260709' }}" alt="Logo BECEK" class="brand-logo-img">
        <span class="brand-logo-text">BECEK</span>
        <span class="admin-badge">ADMIN</span>
    </a>

    <ul class="nav-menu">
        <li class="{{ $activePage === 'admin_dashboard' ? 'active' : '' }}"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="{{ $activePage === 'admin_lokasi' ? 'active' : '' }}"><a href="{{ route('admin.lokasi') }}">Kelola Lokasi</a></li>
        <li class="{{ $activePage === 'admin_users' ? 'active' : '' }}"><a href="{{ route('admin.users') }}">Kelola Pengguna</a></li>
        <li class="{{ $activePage === 'admin_reports' ? 'active' : '' }}"><a href="{{ route('admin.reports') }}">Laporan</a></li>
    </ul>

    <div class="user-profile">
        <span>Halo, <strong>{{ $namaLogin }}</strong></span>
        <button type="button" class="admin-theme-toggle" data-theme-toggle>
            <span class="admin-theme-icon" aria-hidden="true">
                <svg class="admin-theme-svg admin-theme-svg-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="4"></circle>
                    <path d="M12 2v2M12 20v2M4.93 4.93l1.42 1.42M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.42-1.42M17.66 6.34l1.41-1.41"></path>
                </svg>
                <svg class="admin-theme-svg admin-theme-svg-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                </svg>
            </span>
            <span data-theme-label>Tema Terang</span>
        </button>
        <button class="btn-logout" onclick="window.location.href='{{ route('logout') }}'">Sign Out</button>
    </div>
</nav>

<nav class="admin-mobile-bottom-nav" aria-label="Navigasi admin mobile">
    <a href="{{ route('admin.dashboard') }}"
       class="admin-mobile-bottom-item {{ $activePage === 'admin_dashboard' ? 'active' : '' }}"
       @if($activePage === 'admin_dashboard') aria-current="page" @endif>
        <span class="admin-mobile-bottom-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 10.5 12 3l9 7.5"></path>
                <path d="M5 9.5V21h14V9.5"></path>
                <path d="M9 21v-7h6v7"></path>
            </svg>
        </span>
        <span>Dashboard</span>
    </a>

    <a href="{{ route('admin.lokasi') }}"
       class="admin-mobile-bottom-item {{ $activePage === 'admin_lokasi' ? 'active' : '' }}"
       @if($activePage === 'admin_lokasi') aria-current="page" @endif>
        <span class="admin-mobile-bottom-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"></path>
                <circle cx="12" cy="10" r="2.5"></circle>
            </svg>
        </span>
        <span>Lokasi</span>
    </a>

    <a href="{{ route('admin.users') }}"
       class="admin-mobile-bottom-item {{ $activePage === 'admin_users' ? 'active' : '' }}"
       @if($activePage === 'admin_users') aria-current="page" @endif>
        <span class="admin-mobile-bottom-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
        </span>
        <span>Pengguna</span>
    </a>

    <a href="{{ route('admin.reports') }}"
       class="admin-mobile-bottom-item {{ $activePage === 'admin_reports' ? 'active' : '' }}"
       @if($activePage === 'admin_reports') aria-current="page" @endif>
        <span class="admin-mobile-bottom-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 19V9"></path>
                <path d="M10 19V5"></path>
                <path d="M16 19v-7"></path>
                <path d="M22 19V2"></path>
            </svg>
        </span>
        <span>Laporan</span>
    </a>
</nav>
