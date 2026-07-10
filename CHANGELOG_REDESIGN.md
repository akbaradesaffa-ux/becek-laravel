# BECEK Redesign Changelog

## Update Advanced Features

Fitur yang ditambahkan:

- Jam operasional tempat:
  - area/kawasan
  - hari operasional
  - jam buka
  - jam tutup
  - status buka/tutup otomatis berdasarkan waktu Jakarta
- Foto lebih dari satu:
  - foto utama tetap ada
  - foto tambahan memakai tabel `tb_lokasi_foto`
  - galeri foto di halaman detail tempat
  - hapus foto tambahan dari halaman admin lokasi
- Estimasi harga tetap dipertahankan dan ditampilkan lebih jelas di card serta detail tempat.
- Preview gambar saat admin upload foto utama dan foto tambahan.
- Dashboard admin diperkuat:
  - total lokasi
  - total cafe
  - total warkop
  - total fasilitas
  - total user
  - total favorit
  - lokasi terbaru
  - lokasi paling banyak difavoritkan
- Validasi hapus data:
  - hapus lokasi via form DELETE + confirm
  - hapus user via form DELETE + confirm
  - hapus fasilitas via form DELETE + confirm
  - hapus akun user wajib input password
- Proteksi admin:
  - route admin memakai middleware `admin.only`
  - route user memakai middleware `session.auth`
- Filter Explore ditambah:
  - kategori
  - area/kawasan
  - fasilitas
  - pencarian berdasarkan nama, kategori, area, harga, dan fasilitas
- CRUD Manage Users:
  - tambah user
  - edit user
  - hapus user
  - atur role User/Administrator
  - password edit boleh dikosongkan jika tidak diganti
- CRUD Manage Locations:
  - tambah lokasi
  - edit lokasi
  - hapus lokasi
  - kelola area, estimasi harga, jam operasional, Google Maps, foto utama, foto tambahan, dan fasilitas

Command setelah patch:

```bash
php artisan migrate
php artisan optimize:clear
php artisan serve
```
