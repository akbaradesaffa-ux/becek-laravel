<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    <title>BECEK | Dashboard User</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=becek-logo-clean-20260709">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}?v=becek-logo-clean-20260709">
    <link rel="stylesheet" href="{{ asset('css/becek-theme.css') }}?v=academic-footer-20260716">
    <link rel="stylesheet" href="{{ asset('css/page-transition.css') }}?v=theme-toggle-20260709">
</head>
<body>
    @include('partials.navbar_user', ['activePage' => $activePage, 'namaLengkap' => $namaLengkap])

    <main class="page-container">
        <header class="hero hero-center">
            <p class="eyebrow">Direktori Kopi Bekasi</p>
            <h1>Hello,<br><span>Coffee Lovers!</span></h1>
            <p>Panduan kurasi kopi terbaik di jantung Kota Patriot.</p>
        </header>

        <section class="stats-grid" aria-label="Statistik tempat">
            <div class="stat-card">
                <div class="stat-icon">☕</div>
                <strong>{{ $totalCafe }}</strong>
                <span>Total Cafe</span>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🏪</div>
                <strong>{{ $totalWarkop }}</strong>
                <span>Total Warkop</span>
            </div>
        </section>

        <section class="section-heading-row">
            <div>
                <p class="eyebrow muted">Pilihan Tempat</p>
                <h2>{{ $hasRecommendedPlaces ? 'Rekomendasi Hari Ini' : 'Pilihan Terbaru' }}</h2>
                <p class="section-subtitle">{{ $hasRecommendedPlaces ? 'Dipilih langsung dari halaman Manage Location.' : 'Belum ada tempat yang ditandai rekomendasi, jadi sistem menampilkan data terbaru.' }}</p>
            </div>
            <a href="{{ route('explore') }}" class="link-more">Lihat Semua →</a>
        </section>

        <section>
            <div id="cafeGrid" class="grid">
                @forelse ($lokasi as $item)
                    @include('partials.cafe_card', ['item' => $item, 'favoriteIds' => $favoriteIds])
                @empty
                    <div class="empty-state">
                        <div>☕</div>
                        <h3>Belum ada tempat</h3>
                        <p>Data cafe dan warkop belum tersedia.</p>
                    </div>
                @endforelse
            </div>
        </section>

        <section class="bottom-banner">
            <div class="bottom-banner-overlay"></div>
            <div class="bottom-banner-content">
                <p class="eyebrow">Komunitas Kopi</p>
                <h2>Temukan dan simpan tempat favoritmu.</h2>
                <a href="{{ route('explore') }}">Mulai Explore →</a>
            </div>
        </section>
    </main>
    @include('partials.footer_user')

    <script src="{{ asset('js/page-transition.js') }}"></script>
    <script src="{{ asset('js/becek-theme-toggle.js') }}?v=no-footer-logo-20260709"></script>
    <script src="{{ asset('js/becek-user.js') }}"></script>
</body>
</html>
