<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami | BECEK</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo-becek.jpg') }}">
    <link rel="stylesheet" href="{{ asset('css/stylepage5.css') }}">
</head>
<body>
    @include('partials.navbar_user', ['activePage' => $activePage, 'namaLengkap' => $namaLengkap])

    <main class="about-container">
        <header class="hero-about">
            <h1>Tentang <span class="accent-text">BECEK</span></h1>
            <p>Direktori Digital Eksplorasi Coffee Shop Terbaik di Jantung Kota Bekasi.</p>
        </header>

        <section class="content-grid">
            <div class="about-card">
                <div class="card-icon">✨</div>
                <h2>Siapa Kami?</h2>
                <p>BECEK adalah platform kurasi digital mandiri yang membantu para pencinta kopi menemukan tempat terbaik untuk produktivitas, diskusi, maupun bersantai di area Kota Patriot dengan menyuguhkan data fasilitas yang transparan dan akurat.</p>
            </div>
            <div class="about-card">
                <div class="card-icon">📍</div>
                <h2>Misi Kami</h2>
                <p>Mempermudah pencarian titik ruang kumpul komunitas, menaikkan visibilitas pelaku UMKM industri hilir kopi lokal, serta membangun ekosistem referensi yang solid di area Bekasi.</p>
            </div>
            <div class="about-card">
                <div class="card-icon">☕</div>
                <h2>Kenapa BECEK?</h2>
                <p>Kami tidak sekadar menampilkan deretan nama. BECEK berfokus pada visualisasi tata letak yang bersih, filter fungsionalitas yang cepat, serta detail informasi esensial yang paling dicari sebelum Anda melangkahkan kaki.</p>
            </div>
        </section>
    </main>

    <footer class="about-footer">
        <p>&copy; 2026 BECEK &bull; Hak Cipta Dilindungi.</p>
    </footer>
</body>
</html>