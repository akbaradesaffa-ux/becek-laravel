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
    <title>BECEK | Explore Bekasi</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=becek-logo-clean-20260709">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}?v=becek-logo-clean-20260709">
    <link rel="stylesheet" href="{{ asset('css/becek-theme.css') }}?v=simple-footer-20260709">
    <link rel="stylesheet" href="{{ asset('css/page-transition.css') }}?v=advanced-20260709">
</head>
<body>
    @include('partials.navbar_user', ['activePage' => $activePage, 'namaLengkap' => $namaLengkap])

    <main class="page-container">
        <header class="hero hero-center compact">
            <p class="eyebrow">Temukan Tempat Favoritmu</p>
            <h1>Explore <span>Bekasi</span></h1>
            <p>Temukan setiap sudut kopi terbaik di Kota Patriot.</p>
        </header>

        <section class="explore-tools" aria-label="Filter tempat">
            <div class="search-wrapper explore-search">
                <span class="search-icon">⌕</span>
                <input type="text" id="searchInput" class="search-box" placeholder="Cari nama tempat, area, atau harga...">
            </div>

            <div class="filter-row">
                <div class="filter-group">
                    <span class="filter-label">Kategori</span>
                    <div class="filter-container">
                        <button class="filter-btn active" data-filter-type="kategori" data-filter="all">Semua</button>
                        @foreach ($categories as $cat)
                            @if (!empty($cat))
                                <button class="filter-btn" data-filter-type="kategori" data-filter="{{ strtolower($cat) }}">{{ $cat }}</button>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="filter-group">
                    <span class="filter-label">Area</span>
                    <div class="filter-container">
                        <button class="filter-btn active" data-filter-type="area" data-filter="all">Semua Area</button>
                        @foreach ($areas as $area)
                            @if (!empty($area))
                                <button class="filter-btn" data-filter-type="area" data-filter="{{ strtolower($area) }}">{{ $area }}</button>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="filter-group filter-group-wide">
                    <span class="filter-label">Fasilitas</span>
                    <div class="filter-container">
                        <button class="filter-btn fas-btn active" data-filter-type="fasilitas" data-filter="all">Semua Fasilitas</button>
                        @foreach ($fasilitasMaster as $fRow)
                            <button class="filter-btn fas-btn" data-filter-type="fasilitas" data-filter="{{ strtolower($fRow->nama_fasilitas) }}">
                                {{ $fRow->nama_fasilitas }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <p class="result-count" id="resultCount">Menampilkan {{ $lokasi->count() }} dari {{ $lokasi->count() }} tempat</p>

        <section>
            <div id="cafeGrid" class="grid">
                @forelse ($lokasi as $item)
                    @include('partials.cafe_card', ['item' => $item, 'favoriteIds' => $favoriteIds])
                @empty
                    <div class="empty-state">
                        <div>☕</div>
                        <h3>Tidak ada data</h3>
                        <p>Data cafe dan warkop belum tersedia.</p>
                    </div>
                @endforelse
            </div>
            <div class="empty-state hidden" id="noResultState">
                <div>☕</div>
                <h3>Tidak ada tempat yang cocok</h3>
                <p>Coba ubah kata kunci, kategori, atau fasilitas.</p>
            </div>
        </section>
    </main>
    @include('partials.footer_user')

    <script src="{{ asset('js/page-transition.js') }}"></script>
    <script src="{{ asset('js/becek-theme-toggle.js') }}?v=no-footer-logo-20260709"></script>
    <script src="{{ asset('js/becek-user.js') }}?v=recommend-search-20260709"></script>
</body>
</html>
