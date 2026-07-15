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
    <title>About Us | BECEK</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=becek-logo-clean-20260709">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}?v=becek-logo-clean-20260709">
    <link rel="stylesheet" href="{{ asset('css/becek-theme.css') }}?v=academic-footer-20260716">
    <link rel="stylesheet" href="{{ asset('css/page-transition.css') }}?v=theme-toggle-20260709">
</head>
<body>
    @include('partials.navbar_user', ['activePage' => $activePage, 'namaLengkap' => $namaLengkap])

    <main class="page-container">
        <header class="hero hero-center compact about-hero">
            <p class="eyebrow">About Platform</p>
            <h1>About <span>Us</span></h1>
            <p>Direktori Digital Eksplorasi Coffee Shop Terbaik di Jantung Kota Bekasi.</p>
        </header>

        <section class="about-grid">
            <article class="about-card">
                <div class="about-icon">☕</div>
                <h2>Siapa Kami?</h2>
                <p>BECEK adalah platform kurasi digital mandiri yang membantu para pencinta kopi menemukan tempat produktivitas, diskusi, maupun bersantai di area Kota Patriot dengan data fasilitas yang transparan dan terverifikasi.</p>
            </article>
            <article class="about-card">
                <div class="about-icon">🎯</div>
                <h2>Misi Kami</h2>
                <p>BECEK mempermudah pencarian ruang kumpul komunitas, meningkatkan visibilitas pelaku UMKM kopi lokal, dan membangun ekosistem referensi tempat nongkrong yang valid di area Bekasi.</p>
            </article>
            <article class="about-card">
                <div class="about-icon">🌟</div>
                <h2>Kenapa BECEK?</h2>
                <p>BECEK tidak sekadar menampilkan daftar nama. Platform ini berfokus pada kualitas data, filter yang cepat, tampilan yang bersih, dan detail informasi esensial sebelum pengguna datang ke lokasi.</p>
            </article>
        </section>

        <section class="quote-block">
            <div class="quote-mark">“</div>
            <p>Setiap tempat kopi punya cerita. BECEK hadir untuk membantu kamu menemukannya dengan lebih mudah.</p>
            <span>Tim BECEK</span>
        </section>
</main>
    @include('partials.footer_user')

    <script src="{{ asset('js/page-transition.js') }}"></script>
    <script src="{{ asset('js/becek-theme-toggle.js') }}?v=no-footer-logo-20260709"></script>
    <script src="{{ asset('js/becek-user.js') }}"></script>
</body>
</html>
