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
    <title>Tempat Favorit | BECEK</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=becek-logo-clean-20260709">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}?v=becek-logo-clean-20260709">
    <link rel="stylesheet" href="{{ asset('css/becek-theme.css') }}?v=academic-footer-20260716">
    <link rel="stylesheet" href="{{ asset('css/page-transition.css') }}?v=theme-toggle-20260709">
</head>
<body>
    @include('partials.navbar_user', ['activePage' => $activePage, 'namaLengkap' => $namaLengkap])

    <main class="page-container">
        <header class="hero hero-center compact">
            <p class="eyebrow">Koleksi Pribadi</p>
            <h1>Tempat <span>Favorit</span></h1>
            <p>Daftar cafe dan warkop yang sudah kamu simpan.</p>
        </header>

        <section>
            <div id="cafeGrid" class="grid">
                @forelse ($lokasi as $item)
                    @include('partials.cafe_card', ['item' => $item, 'favoriteIds' => $favoriteIds])
                @empty
                    <div class="empty-state">
                        <div>★</div>
                        <h3>Belum ada tempat favorit</h3>
                        <p>Tekan ikon bintang di kartu tempat untuk menyimpannya ke daftar favorit.</p>
                        <a href="{{ route('explore') }}" class="btn-empty">Cari Tempat</a>
                    </div>
                @endforelse
            </div>
        </section>
    </main>
    @include('partials.footer_user')

    <script src="{{ asset('js/page-transition.js') }}"></script>
    <script src="{{ asset('js/becek-theme-toggle.js') }}?v=no-footer-logo-20260709"></script>
    <script src="{{ asset('js/becek-user.js') }}"></script>
</body>
</html>
