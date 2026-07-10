@php
    $favoriteIds = $favoriteIds ?? [];
    $isFavorite = in_array((int) $item->id, $favoriteIds, true);
    $fasilitasText = $item->fasilitas_string ?: '-';
    $statusClass = $item->status_operasional === 'Buka sekarang' ? 'open' : 'closed';
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
            <p class="price">{{ $item->rentang_harga }}</p>
            <p class="hours">🕒 {{ $item->hari_operasional ?: 'Hari belum diatur' }} &bull; {{ $item->jam_operasional_label }}</p>
            <p class="facilities">📌 {{ $fasilitasText }}</p>
            <a href="{{ route('detail', $item->id) }}" class="btn-detail-card">Lihat Detail →</a>
        </div>
    </div>
</article>
