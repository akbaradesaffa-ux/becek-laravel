<nav class="navbar">
    <a href="{{ route('admin.dashboard') }}" class="brand-logo-link">
        <img src="{{ asset('images/logo-becek.png') }}" alt="Logo BECEK" class="brand-logo-img">        <span class="brand-logo-text">BECEK</span>
    </a>
    <ul class="nav-menu">
        <li class="{{ $activePage === 'admin_dashboard' ? 'active' : '' }}"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="{{ $activePage === 'admin_lokasi' ? 'active' : '' }}"><a href="{{ route('admin.lokasi') }}">Kelola Lokasi</a></li>
        <li class="{{ $activePage === 'admin_users' ? 'active' : '' }}"><a href="{{ route('admin.users') }}">Kelola Pengguna</a></li>
        <li class="{{ $activePage === 'admin_reports' ? 'active' : '' }}"><a href="{{ route('admin.reports') }}">Laporan</a></li>
    </ul>
    <div class="user-profile">
        <span>Halo, <strong>{{ $namaLogin }}</strong></span>
        <button class="btn-logout" onclick="window.location.href='{{ route('logout') }}'">Sign Out</button>
    </div>
</nav>