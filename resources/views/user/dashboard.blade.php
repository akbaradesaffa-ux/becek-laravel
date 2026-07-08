<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BECEK | Dashboard User</title>
    <link rel="stylesheet" href="{{ asset('css/stylepage2.css') }}">
</head>
<body>
    @include('partials.navbar_user', ['activePage' => $activePage, 'namaLengkap' => $namaLengkap])

    <main class="container">
        <header class="hero">
            <h1>Hello, Coffee Lovers!</h1>
            <p>Panduan kurasi kopi terbaik di jantung Kota Patriot.</p>
        </header>

        <section>
            <div id="cafeGrid" class="grid">
                @foreach ($lokasi as $item)
                <a href="/lokasi/{{ $item->id }}" class="cafe-card-link" style="text-decoration: none; color: inherit;">
                    <div class="cafe-card" data-nama="{{ strtolower($item->nama) }}">
                        <div class="card-img-wrapper">
                            <img src="{{ asset('uploads/' . $item->jalur_foto) }}" alt="{{ $item->nama }}">
                            <span class="category-badge">{{ $item->kategori }}</span>
                        </div>
                        <div class="info">
                            <h3>{{ $item->nama }}</h3>
                            <p class="price">{{ $item->rentang_harga }}</p>
                            <p class="facilities">📌 {{ $item->fasilitas_string }}</p>
                            <span class="btn-detail-card">Lihat Detail →</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </section>
    </main>

    <footer class="main-footer" style="text-align: center; margin-top: 50px; padding: 20px; color: #64748b; font-size: 0.9rem;">
        <p>&copy; 2026 BECEK &bull; Hak Cipta Dilindungi.</p>
    </footer>
</body>
</html>