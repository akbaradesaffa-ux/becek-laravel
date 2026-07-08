<nav class="navbar">
    <a href="{{ route('dashboard') }}" class="brand-logo-link">
        <img src="{{ asset('images/logo-becek.png') }}" alt="Logo BECEK" class="brand-logo-img">        <span class="brand-logo-text">BECEK</span>
    </a>
    <ul class="nav-menu">
        <li class="{{ $activePage === 'dashboard' ? 'active' : '' }}"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="{{ $activePage === 'explore' ? 'active' : '' }}"><a href="{{ route('explore') }}">Explore</a></li>
        <li class="{{ $activePage === 'about' ? 'active' : '' }}"><a href="{{ route('about') }}">Tentang Kami</a></li>
    </ul>
    <div class="user-profile">
        <span>Halo, <strong>{{ $namaLengkap }}</strong></span>
        <button class="btn-logout" onclick="window.location.href='{{ route('logout') }}'">Sign Out</button>
    </div>
</nav>