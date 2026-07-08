<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Lokasi & Fasilitas - BECEK</title>
    <link rel="stylesheet" href="{{ asset('css/stylepage7.css') }}">
</head>
<body>

    @include('partials.navbar_admin', ['activePage' => $activePage, 'namaLogin' => $namaLogin, 'initial' => $initial])

    <main class="main-content">

        @if ($status === 'success')
            <script>alert('Fasilitas master baru berhasil ditambahkan!'); window.location.href='{{ route('admin.lokasi') }}';</script>
        @elseif ($status === 'exists')
            <script>alert('Gagal! Nama fasilitas tersebut sudah terdaftar sebelumnya.'); window.location.href='{{ route('admin.lokasi') }}';</script>
        @endif

        <div class="header-actions">
            <div class="header-title">
                <h1>Daftar Lokasi Warkop & Cafe</h1>
                <p>Kelola data lokasi, kategori, harga, dan fasilitas standarisasi.</p>
            </div>
            <button class="btn-add" onclick="openModal()">+ Tambah Lokasi Baru</button>
        </div>

        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Foto</th>
                        <th>Nama Tempat</th>
                        <th>Kategori</th>
                        <th>Rentang Harga</th>
                        <th>Fasilitas</th>
                        <th>Peta Google</th>
                        <th style="text-align: right;">Edit</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($lokasiList as $lokasi)
                    <tr>
                        <td><strong class="text-primary-code">{{ $lokasi->kode_lokasi }}</strong></td>
                        <td>
                            <img src="{{ asset('uploads/' . $lokasi->jalur_foto) }}" alt="Foto" class="img-preview">
                        </td>
                        <td><strong>{{ $lokasi->nama }}</strong></td>
                        <td><span class="badge-category">{{ $lokasi->kategori }}</span></td>
                        <td>{{ $lokasi->rentang_harga }}</td>
                        <td class="col-fasilitas">{{ $lokasi->fasilitas_string ?: '-' }}</td>
                        <td>
                            <a href="{{ $lokasi->link_google_maps }}" target="_blank" class="link-maps-btn">🌐 Lihat Peta</a>
                        </td>
                        <td style="text-align: right;">
                            <a href="{{ route('admin.lokasi.delete', $lokasi->id) }}" class="btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus lokasi ini?')">Hapus</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" style="text-align:center; color:var(--text-muted);">Belum ada data lokasi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <br><br><hr class="section-divider"><br>

        <div class="header-actions">
            <div class="header-title">
                <h2>Daftar Fasilitas</h2>
                <p>Daftar  opsi fasilitas.</p>
            </div>

            <form action="{{ route('admin.fasilitas.store') }}" method="POST" class="form-inline-master">
                @csrf
                <input type="text" name="nama_fasilitas_baru" class="form-control" placeholder="Nama fasilitas baru (Contoh: Outdoor Area)" required>
                <button type="submit" class="btn-add">+ Tambah</button>
            </form>
        </div>

        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama Fasilitas</th>
                        <th style="text-align: right;">Edit</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($fasilitasList as $fac)
                    <tr>
                        <td><strong>{{ $fac->nama_fasilitas }}</strong></td>
                        <td style="text-align: right;">
                            <a href="{{ route('admin.fasilitas.delete', $fac->id) }}" class="btn-delete" onclick="return confirm('Menghapus master fasilitas ini dapat berdampak pada relasi data tempat. Lanjutkan?')">Hapus</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="2" style="text-align:center; color:var(--text-muted);">Belum ada data master fasilitas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </main>

    <div class="modal-overlay" id="locationModal">
        <div class="modal-box">
            <h3>Tambah Lokasi Baru</h3>
            <form action="{{ route('admin.lokasi.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <label class="modal-label">Nama Lokasi</label>
                <input type="text" name="nama" class="form-control" placeholder="Masukkan nama cafe/warkop" required>

                <label class="modal-label">Kategori</label>
                <select name="kategori" class="form-control" required>
                    <option value="" disabled selected>-- Pilih Kategori --</option>
                    <option value="Cafe">Cafe</option>
                    <option value="Warkop">Warkop</option>
                </select>

                <label class="modal-label">Harga Mulai</label>
                <input type="text" name="harga" class="form-control" placeholder="Contoh: Rp 10k - Rp 40k" required>

                <label class="modal-label">Link Google Maps</label>
                <input type="url" name="link_maps" class="form-control" placeholder="http://maps.google.com/..." required>

                <label class="modal-label">Foto Lokasi</label>
                <input type="file" name="foto" class="form-control" accept="image/*" required>

                <label class="modal-label">Fasilitas (Multi Choose)</label>
                <div class="checkbox-group">
                    @forelse ($fasilitasCheckbox as $cbFac)
                    <label class="checkbox-label">
                        <input type="checkbox" name="fasilitas[]" value="{{ $cbFac->nama_fasilitas }}">
                        {{ $cbFac->nama_fasilitas }}
                    </label>
                    @empty
                    <p style="font-size:0.85rem; color:var(--text-muted);">Isi data master fasilitas terlebih dahulu.</p>
                    @endforelse
                </div>

                <div class="modal-buttons">
                    <button type="button" class="btn-cancel" onclick="closeModal()">Batal</button>
                    <button type="submit" class="btn-submit">Simpan Lokasi</button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/jspage7.js') }}"></script>
</body>
</html>