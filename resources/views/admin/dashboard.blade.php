<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        (function () {
            try {
                var savedTheme = localStorage.getItem('becek-theme') || 'dark';
                document.documentElement.setAttribute('data-theme', savedTheme === 'light' ? 'light' : 'dark');
            } catch (error) {
                document.documentElement.setAttribute('data-theme', 'dark');
            }
            document.documentElement.classList.add('page-transition-active');
        })();
    </script>
    <title>Dashboard Admin - BECEK</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=becek-logo-clean-20260709">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}?v=becek-logo-clean-20260709">
    <link rel="stylesheet" href="{{ asset('css/becek-admin.css') }}?v=academic-footer-20260716">
    <link rel="stylesheet" href="{{ asset('css/page-transition.css') }}?v=advanced-20260709">
</head>
<body>
@include('partials.navbar_admin', [
    'activePage'   => $activePage,
    'namaLogin'    => $namaLogin,
    'namaLengkap'  => $namaLogin,
    'initial'      => $initial
])

    <main class="main-content page-enter">
        <section class="welcome-banner">
            <p class="eyebrow">Panel Admin</p>
            <h1>Selamat Datang Admin! 👋</h1>
            <p>Manajemen data komunitas kopi Bekasi hari ini.</p>
        </section>

        <div class="stats-grid stats-grid-wide">
            <div class="stat-card">
                <div class="stat-icon">📍</div>
                <div class="stat-info">
                    <h3>{{ $countLokasi }}</h3>
                    <p>Total Lokasi</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon purple">☕</div>
                <div class="stat-info">
                    <h3>{{ $countCafe }}</h3>
                    <p>Total Cafe</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green">🫖</div>
                <div class="stat-info">
                    <h3>{{ $countWarkop }}</h3>
                    <p>Total Warkop</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🧩</div>
                <div class="stat-info">
                    <h3>{{ $countFasilitas }}</h3>
                    <p>Total Fasilitas</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon purple">👥</div>
                <div class="stat-info">
                    <h3>{{ $countUser }}</h3>
                    <p>Total User</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green">★</div>
                <div class="stat-info">
                    <h3>{{ $countFavorit }}</h3>
                    <p>Total Favorit</p>
                </div>
            </div>
        </div>

        <h2 class="section-title">Navigasi Manajemen Data</h2>
        <div class="action-grid">
            <div class="action-card" onclick="window.location.href='{{ route('admin.lokasi') }}'">
                <div class="action-icon">📍</div>
                <div class="action-info">
                    <h3>Manage Locations</h3>
                    <p>Tambah, edit, hapus, kelola foto, jam operasional, area, harga, dan fasilitas tempat.</p>
                    <span class="action-btn">Kelola Lokasi &rarr;</span>
                </div>
            </div>

            <div class="action-card" onclick="window.location.href='{{ route('admin.users') }}'">
                <div class="action-icon purple">👥</div>
                <div class="action-info">
                    <h3>Manage Users</h3>
                    <p>Tambah, edit, hapus, dan atur role user atau administrator secara langsung.</p>
                    <span class="action-btn">Kelola Pengguna &rarr;</span>
                </div>
            </div>

            <div class="action-card" onclick="window.location.href='{{ route('admin.reports') }}'">
                <div class="action-icon green">📊</div>
                <div class="action-info">
                    <h3>Laporan & Statistik</h3>
                    <p>Lihat ringkasan data lokasi dan distribusi kategori Cafe serta Warkop.</p>
                    <span class="action-btn">Lihat Laporan &rarr;</span>
                </div>
            </div>
        </div>

        <div class="admin-two-column">
            <section class="mini-panel">
                <div class="mini-panel-header">
                    <div>
                        <p class="eyebrow">Monitoring</p>
                        <h2>Lokasi Terbaru</h2>
                    </div>
                    <a href="{{ route('admin.lokasi') }}" class="mini-link">Lihat semua</a>
                </div>

                <div class="mini-list">
                    @forelse($latestLocations as $item)
                        <div class="mini-list-item">
                            <img src="{{ asset('uploads/' . $item->jalur_foto) }}" alt="{{ $item->nama }}">
                            <div>
                                <strong>{{ $item->nama }}</strong>
                                <span>{{ $item->kategori }} &bull; {{ $item->area ?: 'Area belum diisi' }}</span>
                            </div>
                            <em>{{ $item->hari_operasional_label }} &bull; {{ $item->jam_operasional_label }}</em>
                        </div>
                    @empty
                        <p class="empty-mini">Belum ada lokasi.</p>
                    @endforelse
                </div>
            </section>

            <section class="mini-panel">
                <div class="mini-panel-header">
                    <div>
                        <p class="eyebrow">Favorit</p>
                        <h2>Paling Banyak Disimpan</h2>
                    </div>
                    <a href="{{ route('admin.reports') }}" class="mini-link">Laporan</a>
                </div>

                <div class="mini-list">
                    @forelse($topLocations as $item)
                        <div class="mini-list-item no-photo">
                            <div class="mini-rank">★</div>
                            <div>
                                <strong>{{ $item->nama }}</strong>
                                <span>{{ $item->kategori }} &bull; {{ $item->area ?: 'Area belum diisi' }}</span>
                            </div>
                            <em>{{ $item->favorites_count }} favorit</em>
                        </div>
                    @empty
                        <p class="empty-mini">Belum ada data favorit.</p>
                    @endforelse
                </div>
            </section>
        </div>
    </main>

    @include('partials.footer_admin')

    <script src="{{ asset('js/page-transition.js') }}"></script>
    <script src="{{ asset('js/becek-theme-toggle.js') }}?v=no-footer-logo-20260709"></script>
    <script src="{{ asset('js/jspage6.js') }}"></script>
</body>
</html>
