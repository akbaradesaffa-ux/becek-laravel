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
    <title>{{ $lokasi->nama }} | BECEK</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=becek-logo-clean-20260709">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}?v=becek-logo-clean-20260709">
    <link rel="stylesheet" href="{{ asset('css/becek-theme.css') }}?v=academic-footer-20260716">
    <link rel="stylesheet" href="{{ asset('css/stylepage4.css') }}?v=daily-operating-hours-20260715">
    <link rel="stylesheet" href="{{ asset('css/page-transition.css') }}?v=advanced-20260709">
</head>
<body class="detail-page">
    @include('partials.navbar_user', ['activePage' => 'explore', 'namaLengkap' => $namaLengkap ?? session('nama_lengkap', 'Pengguna')])

    <main class="detail-main page-enter">
        <a href="{{ route('explore') }}" class="btn-back">← Kembali ke Explore</a>

        <section class="detail-shell">
            @php
                $detailPhotoCount = 1 + $lokasi->fotos->count();
            @endphp
            <article class="detail-media-card detail-gallery-card" data-detail-carousel tabindex="0" aria-label="Galeri foto {{ $lokasi->nama }}">
                <div class="detail-carousel-track" data-carousel-track>
                    <figure class="detail-carousel-slide active" data-carousel-slide aria-hidden="false">
                        <img src="{{ asset('uploads/' . $lokasi->jalur_foto) }}" alt="Foto utama {{ $lokasi->nama }}" draggable="false">
                    </figure>
                    @foreach($lokasi->fotos as $foto)
                        <figure class="detail-carousel-slide" data-carousel-slide aria-hidden="true">
                            <img src="{{ asset('uploads/' . $foto->jalur_foto) }}" alt="Foto tambahan {{ $loop->iteration }} {{ $lokasi->nama }}" loading="lazy" draggable="false">
                        </figure>
                    @endforeach
                </div>

                @if($detailPhotoCount > 1)
                    <button type="button" class="detail-carousel-nav detail-carousel-prev" data-carousel-prev aria-label="Lihat foto sebelumnya">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="m15 18-6-6 6-6"></path>
                        </svg>
                    </button>
                    <button type="button" class="detail-carousel-nav detail-carousel-next" data-carousel-next aria-label="Lihat foto berikutnya">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="m9 18 6-6-6-6"></path>
                        </svg>
                    </button>

                    <div class="detail-carousel-status" aria-live="polite">
                        <span data-carousel-current>1</span>
                        <span>/</span>
                        <span>{{ $detailPhotoCount }}</span>
                    </div>

                    <div class="detail-carousel-dots" role="tablist" aria-label="Pilih foto">
                        @for($photoIndex = 0; $photoIndex < $detailPhotoCount; $photoIndex++)
                            <button type="button"
                                    class="detail-carousel-dot {{ $photoIndex === 0 ? 'active' : '' }}"
                                    data-carousel-dot="{{ $photoIndex }}"
                                    aria-label="Tampilkan foto {{ $photoIndex + 1 }}"
                                    aria-selected="{{ $photoIndex === 0 ? 'true' : 'false' }}"></button>
                        @endfor
                    </div>
                @endif

                <div class="detail-floating-title">
                    <span class="detail-category">{{ strtoupper($lokasi->kategori) }}</span>
                    <h1>{{ $lokasi->nama }}</h1>
                </div>
            </article>

            <aside class="detail-info-card">
                <p class="eyebrow">Detail Tempat</p>
                <h2>Informasi lokasi pilihanmu</h2>

                <div class="info-row">
                    <strong>Kategori</strong>
                    <span>{{ $lokasi->kategori }}</span>
                </div>

                <div class="info-row">
                    <strong>Area</strong>
                    <span>{{ $lokasi->area ?: 'Area belum diisi' }}</span>
                </div>

                <div class="info-row">
                    <strong>Estimasi Harga</strong>
                    <span>{{ $lokasi->rentang_harga }}</span>
                </div>

                <div class="info-row">
                    <strong>Operasional</strong>
                    <div>
                        <span class="status-detail {{ $lokasi->status_operasional === 'Buka sekarang' ? 'open' : 'closed' }}">{{ $lokasi->status_operasional }}</span>
                        <p class="detail-small-text">Hari ini ({{ $lokasi->hari_operasional_label }}) &bull; {{ $lokasi->jam_operasional_label }}</p>
                        <div class="operational-schedule-list">
                            @foreach($lokasi->ringkasan_jadwal_operasional as $scheduleLine)
                                <span class="operational-schedule-item">{{ $scheduleLine }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="info-row">
                    <strong>Fasilitas</strong>
                    <div class="facility-pill-list">
                        @forelse($lokasi->fasilitas as $fac)
                            <span class="facility-pill">{{ $fac->nama_fasilitas }}</span>
                        @empty
                            <span style="color:var(--muted);">Belum ada fasilitas terdaftar.</span>
                        @endforelse
                    </div>
                </div>

                <div class="detail-actions">
                    <a href="{{ $lokasi->link_google_maps }}" target="_blank" class="btn-maps">Buka Google Maps ↗</a>
                    @php
                        $isFavorited = in_array((int) $lokasi->id, $favoriteIds ?? [], true);
                    @endphp
                    <button type="button"
                            class="detail-favorite-button {{ $isFavorited ? 'active' : '' }}"
                            data-favorite-button
                            data-url="{{ route('favorites.toggle', $lokasi->id) }}"
                            aria-label="{{ $isFavorited ? 'Hapus dari favorit' : 'Tambah ke favorit' }}">
                        ★
                    </button>
                </div>

                <p class="detail-note">Gunakan tombol Google Maps untuk membuka lokasi asli. Simpan tempat ini ke favorit dengan menekan ikon bintang.</p>
            </aside>
        </section>

    </main>
    @include('partials.footer_user')

    <script src="{{ asset('js/page-transition.js') }}"></script>
    <script src="{{ asset('js/becek-theme-toggle.js') }}?v=no-footer-logo-20260709"></script>
    <script src="{{ asset('js/becek-user.js') }}?v=advanced-20260709"></script>
    <script src="{{ asset('js/becek-detail-carousel.js') }}?v=carousel-20260715"></script>
</body>
</html>
