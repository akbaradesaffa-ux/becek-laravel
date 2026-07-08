<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BECEK | Explore Bekasi</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo-becek.jpg') }}">
    <link rel="stylesheet" href="{{ asset('css/stylepage3.css') }}">
</head>
<body>
    @include('partials.navbar_user', ['activePage' => $activePage, 'namaLengkap' => $namaLengkap])

    <main class="container">
        <header class="hero">
            <h1>Explore Bekasi</h1>
            <p>Temukan setiap sudut kopi terbaik di Kota Patriot.</p>
        </header>

        <section class="filter-search-wrapper">
            <div class="search-wrapper">
                <input type="text" id="searchInput" class="search-box" placeholder="Cari nama cafe pilihanmu...">
            </div>

            <div class="filter-group">
                <span class="filter-label">Kategori:</span>
                <div class="filter-container">
                    <button class="filter-btn active" data-filter-type="kategori" data-filter="all">☕ Semua</button>
                    @foreach ($categories as $cat)
                        @if (!empty($cat))
                        <button class="filter-btn" data-filter-type="kategori" data-filter="{{ strtolower($cat) }}">{{ $cat }}</button>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="filter-group">
                <span class="filter-label">Fasilitas:</span>
                <div class="filter-container">
                    <button class="filter-btn fas-btn active" data-filter-type="fasilitas" data-filter="all">✨ Semua Fasilitas</button>
                    @foreach ($fasilitasMaster as $fRow)
                        <button class="filter-btn fas-btn" data-filter-type="fasilitas" data-filter="{{ strtolower($fRow->nama_fasilitas) }}">
                            {{ $fRow->nama_fasilitas }}
                        </button>
                    @endforeach
                </div>
            </div>
        </section>

        <section>
            <div id="cafeGrid" class="grid">
                @foreach ($lokasi as $item)
                <a href="/lokasi/{{ $item->id }}"
                   class="cafe-card-link"
                   data-kategori="{{ strtolower($item->kategori) }}"
                   data-fasilitas="{{ strtolower($item->fasilitas_string) }}"
                   style="text-decoration: none; color: inherit;">
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

    <script src="{{ asset('js/jspage3.js') }}"></script>
</body>
</html>