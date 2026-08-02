@php
    $favoriteIds = $favoriteIds ?? [];
    $isFavorite = in_array((int) $item->id, $favoriteIds, true);
    $fasilitasText = $item->fasilitas_string ?: '-';
    $statusClass = $item->status_operasional === 'Buka sekarang' ? 'open' : 'closed';
    $showDistance = $showDistance ?? false;
    $showDirections = $showDirections ?? false;
    $distanceKm = $item->getAttribute('distance_km');
    $distanceLabel = null;

    if ($showDistance && $distanceKm !== null) {
        $distanceLabel = $distanceKm < 1
            ? round($distanceKm * 1000) . ' m'
            : number_format($distanceKm, 1, ',', '.') . ' km';
    }
@endphp

<article class="cafe-card-wrap"
         data-kategori="{{ strtolower($item->kategori) }}"
         data-area="{{ strtolower($item->area ?? '') }}"
         data-harga="{{ strtolower($item->rentang_harga) }}"
         data-fasilitas="{{ strtolower($fasilitasText) }}">
    <div class="cafe-card" data-nama="{{ strtolower($item->nama) }}">
        <div class="card-img-wrapper">
            <a href="{{ route('detail', $item->id) }}" class="card-image-link" aria-label="Lihat detail {{ $item->nama }}">
                <img src="{{ asset('uploads/' . $item->jalur_foto) }}" alt="{{ $item->nama }}">
            </a>
            <span class="category-badge">{{ strtoupper($item->kategori) }}</span>
            @if(!empty($item->is_recommended))
                <span class="recommend-badge">★ Rekomendasi</span>
            @endif
            <button type="button"
                    class="favorite-btn {{ $isFavorite ? 'active' : '' }}"
                    data-favorite-button
                    data-url="{{ route('favorites.toggle', $item->id) }}"
                    aria-label="{{ $isFavorite ? 'Hapus dari favorit' : 'Tambah ke favorit' }}">
                ★
            </button>
        </div>

        <div class="info">
            <a href="{{ route('detail', $item->id) }}" class="cafe-title-link">
                <h3>{{ $item->nama }}</h3>
            </a>
            <div class="card-meta-line">
                <span>📍 {{ $item->area ?: 'Area belum diisi' }}</span>
                <span class="status-mini {{ $statusClass }}">{{ $item->status_operasional }}</span>
            </div>
            @if($distanceLabel)
                <div class="distance-highlight">
                    <span>⌖</span>
                    <strong>{{ $distanceLabel }}</strong> dari lokasi Anda
                </div>
            @endif
            <p class="price">{{ $item->rentang_harga }}</p>
            <p class="hours">🕒 Hari ini ({{ $item->hari_operasional_label }}) &bull; {{ $item->jam_operasional_label }}</p>
            <p class="facilities">📌 {{ $fasilitasText }}</p>
            <div class="card-action-row">
                <a href="{{ route('detail', $item->id) }}" class="btn-detail-card">Lihat Detail →</a>
                @if($showDirections && $item->has_coordinates)
                    <a href="{{ $item->maps_direction_url }}" target="_blank" rel="noopener noreferrer" class="btn-route-card">Buka Rute ↗</a>
                @endif
            </div>
        </div>
    </div>
</article>
