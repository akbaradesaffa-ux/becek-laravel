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
    <title>BECEK | Cafe & Warkop Terdekat</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=becek-logo-clean-20260709">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}?v=becek-logo-clean-20260709">
    <link rel="stylesheet" href="{{ asset('css/becek-theme.css') }}?v=nearby-20260802">
    <link rel="stylesheet" href="{{ asset('css/page-transition.css') }}?v=nearby-20260802">
</head>
<body>
    @include('partials.navbar_user', ['activePage' => $activePage, 'namaLengkap' => $namaLengkap])

    <main class="page-container">
        <header class="hero hero-center compact nearby-hero">
            <p class="eyebrow">Pencarian Berbasis Lokasi</p>
            <h1>Tempat <span>Terdekat</span></h1>
            <p>Temukan cafe dan warkop dalam radius pilihanmu, tanpa Google Maps API berbayar.</p>
        </header>

        <section class="nearby-control-card" aria-labelledby="nearbyControlTitle">
            <div class="nearby-control-copy">
                <span class="nearby-pin" aria-hidden="true">⌖</span>
                <div>
                    <p class="eyebrow muted">Lokasi Perangkat</p>
                    <h2 id="nearbyControlTitle">{{ $hasUserLocation ? 'Lokasi berhasil digunakan' : 'Aktifkan lokasi untuk mulai' }}</h2>
                    <p id="nearbyStatus" role="status" aria-live="polite">
                        @if($hasUserLocation)
                            Menampilkan tempat terdekat dalam radius {{ $radius }} km. Lokasi tidak disimpan sebagai data akun.
                        @else
                            Browser akan meminta izin lokasi. Fitur ini bekerja melalui HTTPS atau localhost.
                        @endif
                    </p>
                </div>
            </div>

            <button type="button" class="nearby-location-button" id="useCurrentLocation">
                <span aria-hidden="true">📍</span>
                <span>{{ $hasUserLocation ? 'Perbarui Lokasi' : 'Gunakan Lokasi Saya' }}</span>
            </button>

            <form action="{{ route('nearby') }}" method="GET" id="nearbyFilterForm" class="nearby-filter-form">
                <input type="hidden" name="latitude" id="nearbyLatitude" value="{{ $latitude }}">
                <input type="hidden" name="longitude" id="nearbyLongitude" value="{{ $longitude }}">

                <label>
                    <span>Radius pencarian</span>
                    <select name="radius" class="nearby-select">
                        @foreach([1, 3, 5, 10, 25] as $radiusOption)
                            <option value="{{ $radiusOption }}" @selected($radius === $radiusOption)>{{ $radiusOption }} km</option>
                        @endforeach
                    </select>
                </label>

                <label>
                    <span>Kategori</span>
                    <select name="kategori" class="nearby-select">
                        <option value="" @selected($selectedCategory === '')>Cafe & Warkop</option>
                        <option value="Cafe" @selected($selectedCategory === 'Cafe')>Cafe</option>
                        <option value="Warkop" @selected($selectedCategory === 'Warkop')>Warkop</option>
                    </select>
                </label>

                <button type="submit" class="nearby-apply-button" @disabled(!$hasUserLocation)>Terapkan Filter</button>
            </form>
        </section>

        @if($errors->any())
            <div class="nearby-message error" role="alert">
                {{ $errors->first() }} Silakan aktifkan lokasi kembali.
            </div>
        @endif

        @if(!$hasUserLocation)
            <section class="nearby-empty-state">
                <div class="nearby-empty-icon">📍</div>
                <h2>Lokasi perangkat belum aktif</h2>
                <p>Tekan <strong>Gunakan Lokasi Saya</strong>, lalu pilih <strong>Izinkan</strong> pada permintaan browser.</p>
                @if($locationsWithCoordinates === 0)
                    <p class="nearby-admin-note">Belum ada cafe/warkop yang memiliki koordinat. Admin perlu mengisi latitude dan longitude pada menu Manage Location.</p>
                @else
                    <p class="nearby-admin-note">{{ $locationsWithCoordinates }} tempat sudah siap dihitung jaraknya.</p>
                @endif
            </section>
        @else
            <section class="section-heading-row nearby-results-heading">
                <div>
                    <p class="eyebrow muted">Diurutkan dari yang terdekat</p>
                    <h2>{{ $lokasi->total() }} tempat ditemukan</h2>
                    <p class="section-subtitle">
                        Radius {{ $radius }} km{{ $selectedCategory !== '' ? ' · ' . $selectedCategory : ' · Semua kategori' }}.
                    </p>
                </div>
                <a href="{{ route('explore') }}" class="link-more">Lihat Semua Tempat →</a>
            </section>

            @if($lokasi->count())
                <section>
                    <div class="grid nearby-grid">
                        @foreach($lokasi as $item)
                            @include('partials.cafe_card', [
                                'item' => $item,
                                'favoriteIds' => $favoriteIds,
                                'showDistance' => true,
                                'showDirections' => true,
                            ])
                        @endforeach
                    </div>

                    @if($lokasi->hasPages())
                        <div class="nearby-pagination">
                            @if($lokasi->onFirstPage())
                                <span class="disabled">← Sebelumnya</span>
                            @else
                                <a href="{{ $lokasi->previousPageUrl() }}">← Sebelumnya</a>
                            @endif

                            <span>Halaman {{ $lokasi->currentPage() }} dari {{ $lokasi->lastPage() }}</span>

                            @if($lokasi->hasMorePages())
                                <a href="{{ $lokasi->nextPageUrl() }}">Berikutnya →</a>
                            @else
                                <span class="disabled">Berikutnya →</span>
                            @endif
                        </div>
                    @endif
                </section>
            @else
                <section class="nearby-empty-state compact-empty">
                    <div class="nearby-empty-icon">☕</div>
                    <h2>Belum ada tempat dalam radius ini</h2>
                    <p>Perbesar radius pencarian atau pilih semua kategori.</p>
                </section>
            @endif
        @endif
    </main>

    @include('partials.footer_user')

    <script src="{{ asset('js/page-transition.js') }}"></script>
    <script src="{{ asset('js/becek-theme-toggle.js') }}?v=nearby-20260802"></script>
    <script src="{{ asset('js/becek-user.js') }}?v=nearby-20260802"></script>
    <script src="{{ asset('js/becek-nearby.js') }}?v=nearby-20260802"></script>
</body>
</html>
