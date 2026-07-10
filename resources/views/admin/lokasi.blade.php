<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <title>Manajemen Lokasi & Fasilitas - BECEK</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=becek-logo-clean-20260709">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}?v=becek-logo-clean-20260709">
    <link rel="stylesheet" href="{{ asset('css/becek-admin.css') }}?v=location-action-clean-20260709">
    <link rel="stylesheet" href="{{ asset('css/page-transition.css') }}?v=advanced-20260709">
</head>
<body>

    @include('partials.navbar_admin', ['activePage' => $activePage, 'namaLogin' => $namaLogin, 'initial' => $initial])

    <main class="main-content page-enter">
        @if ($status === 'success')
            <script>alert('Fasilitas master baru berhasil ditambahkan!'); window.location.href='{{ route('admin.lokasi') }}';</script>
        @elseif ($status === 'exists')
            <script>alert('Gagal! Nama fasilitas tersebut sudah terdaftar sebelumnya.'); window.location.href='{{ route('admin.lokasi') }}';</script>
        @endif

        @if (session('success'))
            <div class="alert-box success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert-box danger">{{ $errors->first() }}</div>
        @endif

        <div class="header-actions">
            <div class="header-title">
                <p class="eyebrow">Manajemen Tempat</p>
                <h1>Daftar Lokasi Warkop & Cafe</h1>
                <p>Kelola lokasi, area, harga, jam operasional, foto, link Google Maps, fasilitas, dan status rekomendasi tempat.</p>
            </div>
            <button class="btn-add" onclick="openCreateLocationModal()">+ Tambah Lokasi Baru</button>
        </div>


        <form action="{{ route('admin.lokasi') }}" method="GET" class="admin-search-panel" role="search">
            <div class="admin-search-field">
                <span>⌕</span>
                <input type="search" name="q" value="{{ $search }}" placeholder="Cari nama, kode, kategori, area, harga, jam, atau fasilitas...">
            </div>
            <button type="submit" class="btn-add">Cari</button>
            @if(!empty($search))
                <a href="{{ route('admin.lokasi') }}" class="btn-reset-search">Reset</a>
            @endif
        </form>

        <p class="admin-result-note">Menampilkan {{ $lokasiList->count() }} lokasi{{ !empty($search) ? ' untuk pencarian: ' . $search : '' }}.</p>

        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Rekomendasi</th>
                        <th>Foto</th>
                        <th>Nama Tempat</th>
                        <th>Kategori</th>
                        <th>Area</th>
                        <th>Estimasi Harga</th>
                        <th>Operasional</th>
                        <th>Fasilitas</th>
                        <th>Foto Tambahan</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($lokasiList as $lokasi)
                    <tr>
                        <td><strong class="text-primary-code">{{ $lokasi->kode_lokasi }}</strong></td>
                        <td>
                            <form action="{{ route('admin.lokasi.recommendation.toggle', $lokasi->id) }}" method="POST" class="inline-recommend-form" title="{{ $lokasi->is_recommended ? 'Hapus dari rekomendasi' : 'Jadikan rekomendasi' }}">
                                @csrf
                                <button type="submit" class="btn-star-toggle {{ $lokasi->is_recommended ? 'active' : '' }}" aria-label="{{ $lokasi->is_recommended ? 'Hapus dari rekomendasi' : 'Jadikan rekomendasi' }}">★</button>
                            </form>
                        </td>
                        <td>
                            <img src="{{ asset('uploads/' . $lokasi->jalur_foto) }}" alt="Foto {{ $lokasi->nama }}" class="img-preview">
                        </td>
                        <td>
                            <strong>{{ $lokasi->nama }}</strong><br>
                            <a href="{{ $lokasi->link_google_maps }}" target="_blank" class="link-maps-btn small">↗ Maps</a>
                        </td>
                        <td>
                            <span class="badge-category {{ strtolower($lokasi->kategori) === 'warkop' ? 'warkop' : '' }}">{{ $lokasi->kategori }}</span>
                        </td>
                        <td>{{ $lokasi->area ?: '-' }}</td>
                        <td>{{ $lokasi->rentang_harga }}</td>
                        <td>
                            <span class="status-pill {{ $lokasi->status_operasional === 'Buka sekarang' ? 'open' : 'closed' }}">{{ $lokasi->status_operasional }}</span><br>
                            <small class="muted-small">{{ $lokasi->hari_operasional ?: 'Hari belum diatur' }} &bull; {{ $lokasi->jam_operasional_label }}</small>
                        </td>
                        <td class="col-fasilitas">
                            @if($lokasi->fasilitas->count())
                                <div class="facility-list">
                                    @foreach($lokasi->fasilitas as $fac)
                                        <span class="facility-chip">{{ $fac->nama_fasilitas }}</span>
                                    @endforeach
                                </div>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <div class="photo-count">{{ $lokasi->fotos->count() }} foto</div>
                        </td>
                        <td style="text-align: right;">
                            <div class="action-inline">
                                <button type="button"
                                        class="btn-view"
                                        onclick="openLocationDetailModal(this)"
                                        data-kode="{{ e($lokasi->kode_lokasi) }}"
                                        data-nama="{{ e($lokasi->nama) }}"
                                        data-kategori="{{ e($lokasi->kategori) }}"
                                        data-area="{{ e($lokasi->area ?: '-') }}"
                                        data-harga="{{ e($lokasi->rentang_harga) }}"
                                        data-hari="{{ e($lokasi->hari_operasional ?: 'Hari belum diatur') }}"
                                        data-jam="{{ e($lokasi->jam_operasional_label) }}"
                                        data-status="{{ e($lokasi->status_operasional) }}"
                                        data-maps="{{ e($lokasi->link_google_maps) }}"
                                        data-rekomendasi="{{ $lokasi->is_recommended ? 'Ya' : 'Tidak' }}"
                                        data-favorit="{{ $lokasi->favorites_count ?? 0 }}"
                                        data-foto="{{ asset('uploads/' . $lokasi->jalur_foto) }}"
                                        data-foto-tambahan="{{ $lokasi->fotos->count() }}"
                                        data-fasilitas="{{ e($lokasi->fasilitas->pluck('nama_fasilitas')->join(', ') ?: '-') }}">
                                    Detail
                                </button>
                                <button type="button"
                                        class="btn-edit"
                                        onclick="openEditLocationModal(this)"
                                        data-id="{{ $lokasi->id }}"
                                        data-update-url="{{ route('admin.lokasi.update', $lokasi->id) }}"
                                        data-nama="{{ e($lokasi->nama) }}"
                                        data-kategori="{{ $lokasi->kategori }}"
                                        data-area="{{ e($lokasi->area) }}"
                                        data-harga="{{ e($lokasi->rentang_harga) }}"
                                        data-hari="{{ e($lokasi->hari_operasional) }}"
                                        data-jam-buka="{{ $lokasi->jam_buka ? substr($lokasi->jam_buka, 0, 5) : '' }}"
                                        data-jam-tutup="{{ $lokasi->jam_tutup ? substr($lokasi->jam_tutup, 0, 5) : '' }}"
                                        data-maps="{{ e($lokasi->link_google_maps) }}"
                                        data-rekomendasi="{{ $lokasi->is_recommended ? '1' : '0' }}"
                                        data-fasilitas="{{ $lokasi->fasilitas->pluck('id')->implode(',') }}">
                                    Edit
                                </button>
                                <form action="{{ route('admin.lokasi.delete', $lokasi->id) }}" method="POST" class="inline-delete-form" onsubmit="return confirmAdminDelete('Hapus lokasi {{ e($lokasi->nama) }} beserta foto dan relasinya?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="11" class="empty-row">Belum ada data lokasi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <hr class="section-divider">

        <div class="header-actions">
            <div class="header-title">
                <p class="eyebrow">Master Data</p>
                <h2>Daftar Fasilitas</h2>
                <p>Kelola opsi fasilitas yang dapat dipilih saat menambahkan atau mengedit tempat.</p>
            </div>

            <form action="{{ route('admin.fasilitas.store') }}" method="POST" class="form-inline-master">
                @csrf
                <input type="text" name="nama_fasilitas_baru" class="form-control" placeholder="Nama fasilitas baru" required>
                <button type="submit" class="btn-add">+ Tambah</button>
            </form>
        </div>

        <div class="data-table-wrapper">
            <table class="data-table compact-table">
                <thead>
                    <tr>
                        <th>Nama Fasilitas</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($fasilitasList as $fac)
                    <tr>
                        <td><strong>{{ $fac->nama_fasilitas }}</strong></td>
                        <td style="text-align: right;">
                            <form action="{{ route('admin.fasilitas.delete', $fac->id) }}" method="POST" class="inline-delete-form" onsubmit="return confirmAdminDelete('Menghapus master fasilitas ini dapat berdampak pada relasi data tempat. Lanjutkan?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="2" class="empty-row">Belum ada data master fasilitas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <hr class="section-divider">

        <div class="header-title">
            <p class="eyebrow">Galeri Tempat</p>
            <h2>Foto Tambahan Lokasi</h2>
            <p>Hapus foto tambahan yang sudah tidak dipakai. Foto utama diubah melalui tombol edit lokasi.</p>
        </div>

        @php $extraFotoCount = $lokasiList->sum(fn($item) => $item->fotos->count()); @endphp
        <div class="gallery-admin-grid">
            @if($extraFotoCount > 0)
                @foreach($lokasiList as $lokasi)
                    @foreach($lokasi->fotos as $foto)
                    <div class="gallery-admin-card">
                        <img src="{{ asset('uploads/' . $foto->jalur_foto) }}" alt="Foto tambahan {{ $lokasi->nama }}">
                        <div>
                            <strong>{{ $lokasi->nama }}</strong>
                            <form action="{{ route('admin.lokasi.foto.delete', $foto->id) }}" method="POST" onsubmit="return confirmAdminDelete('Hapus foto tambahan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete small-delete">Hapus Foto</button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                @endforeach
            @else
                <p class="empty-mini">Belum ada foto tambahan.</p>
            @endif
        </div>
    </main>

    <div class="modal-overlay" id="locationDetailModal">
        <div class="modal-box modal-box-wide location-detail-modal">
            <div class="detail-modal-head">
                <div>
                    <p class="eyebrow">Detail Lokasi</p>
                    <h3 id="detailNama">Detail Lokasi</h3>
                    <p id="detailKode" class="detail-code">-</p>
                </div>
                <button type="button" class="btn-close-modal" onclick="closeLocationDetailModal()" aria-label="Tutup detail">×</button>
            </div>

            <img src="" alt="Foto lokasi" id="detailFoto" class="detail-location-photo">

            <div class="location-detail-grid">
                <div class="detail-info-card"><span>Kategori</span><strong id="detailKategori">-</strong></div>
                <div class="detail-info-card"><span>Area</span><strong id="detailArea">-</strong></div>
                <div class="detail-info-card"><span>Estimasi Harga</span><strong id="detailHarga">-</strong></div>
                <div class="detail-info-card"><span>Status</span><strong id="detailStatus">-</strong></div>
                <div class="detail-info-card"><span>Hari Operasional</span><strong id="detailHari">-</strong></div>
                <div class="detail-info-card"><span>Jam Operasional</span><strong id="detailJam">-</strong></div>
                <div class="detail-info-card"><span>Rekomendasi</span><strong id="detailRekomendasi">-</strong></div>
                <div class="detail-info-card"><span>Total Favorit</span><strong id="detailFavorit">0</strong></div>
                <div class="detail-info-card"><span>Foto Tambahan</span><strong id="detailFotoTambahan">0</strong></div>
                <div class="detail-info-card detail-info-wide"><span>Fasilitas</span><strong id="detailFasilitas">-</strong></div>
            </div>

            <div class="modal-buttons detail-actions">
                <a href="#" target="_blank" rel="noopener" class="btn-submit" id="detailMapsLink">Buka Google Maps</a>
                <button type="button" class="btn-cancel" onclick="closeLocationDetailModal()">Tutup</button>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="locationModal">
        <div class="modal-box modal-box-wide">
            <h3 id="locationModalTitle">Tambah Lokasi Baru</h3>
            <form id="locationForm" action="{{ route('admin.lokasi.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="locationMethod" value="POST" disabled>

                <div class="modal-grid-2">
                    <div>
                        <label class="modal-label">Nama Lokasi</label>
                        <input type="text" name="nama" id="lokasiNama" class="form-control" placeholder="Masukkan nama cafe/warkop" required>
                    </div>
                    <div>
                        <label class="modal-label">Kategori</label>
                        <select name="kategori" id="lokasiKategori" class="form-control" required>
                            <option value="" disabled selected>-- Pilih Kategori --</option>
                            <option value="Cafe">Cafe</option>
                            <option value="Warkop">Warkop</option>
                        </select>
                    </div>
                    <div>
                        <label class="modal-label">Area/Kawasan</label>
                        <input type="text" name="area" id="lokasiArea" class="form-control" placeholder="Contoh: Bekasi Timur">
                    </div>
                    <div>
                        <label class="modal-label">Estimasi Harga</label>
                        <input type="text" name="harga" id="lokasiHarga" class="form-control" placeholder="Contoh: Rp 10k - Rp 40k" required>
                    </div>
                    <div class="recommend-field">
                        <label class="modal-label">Rekomendasi</label>
                        <label class="switch-check">
                            <input type="checkbox" name="is_recommended" id="lokasiRekomendasi" value="1">
                            <span>★ Tampilkan sebagai rekomendasi di dashboard</span>
                        </label>
                    </div>
                    <div>
                        <label class="modal-label">Hari Operasional</label>
                        <input type="text" name="hari_operasional" id="lokasiHari" class="form-control" placeholder="Contoh: Senin - Minggu">
                    </div>
                    <div class="time-row">
                        <div>
                            <label class="modal-label">Jam Buka</label>
                            <input type="time" name="jam_buka" id="lokasiJamBuka" class="form-control">
                        </div>
                        <div>
                            <label class="modal-label">Jam Tutup</label>
                            <input type="time" name="jam_tutup" id="lokasiJamTutup" class="form-control">
                        </div>
                    </div>
                </div>

                <label class="modal-label">Link Google Maps</label>
                <input type="url" name="link_maps" id="lokasiMaps" class="form-control" placeholder="https://maps.google.com/..." required>

                <label class="modal-label">Foto Utama</label>
                <input type="file" name="foto" id="lokasiFoto" class="form-control" accept="image/*" required>
                <div id="mainPhotoPreview" class="photo-preview-row"></div>
                <small class="muted-small">Saat edit, kosongkan foto utama kalau tidak ingin mengganti gambar utama.</small>

                <label class="modal-label">Foto Tambahan</label>
                <input type="file" name="foto_tambahan[]" id="lokasiFotoTambahan" class="form-control" accept="image/*" multiple>
                <div id="extraPhotoPreview" class="photo-preview-row"></div>

                <label class="modal-label">Fasilitas</label>
                <div class="checkbox-group">
                    @forelse ($fasilitasCheckbox as $cbFac)
                    <label class="checkbox-label">
                        <input type="checkbox" name="fasilitas_ids[]" value="{{ $cbFac->id }}" data-facility-checkbox>
                        {{ $cbFac->nama_fasilitas }}
                    </label>
                    @empty
                    <p style="font-size:0.85rem; color:var(--admin-muted);">Isi data master fasilitas terlebih dahulu.</p>
                    @endforelse
                </div>

                <div class="modal-buttons">
                    <button type="button" class="btn-cancel" onclick="closeLocationModal()">Batal</button>
                    <button type="submit" class="btn-submit" id="locationSubmitButton">Simpan Lokasi</button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/page-transition.js') }}"></script>
    <script src="{{ asset('js/becek-theme-toggle.js') }}?v=no-footer-logo-20260709"></script>
    <script src="{{ asset('js/jspage7.js') }}?v=location-action-clean-20260709"></script>
</body>
</html>
