# Update Search Admin dan Rekomendasi Tempat

## Fitur baru

- Menambahkan fitur search di halaman **Manage Location**.
  - Bisa mencari nama tempat, kode lokasi, kategori, area, estimasi harga, hari operasional, dan fasilitas.
- Menambahkan fitur search di halaman **Manage Users**.
  - Bisa mencari nama, email, username lama jika masih ada, dan role.
- Menambahkan status **Rekomendasi** pada lokasi.
  - Admin dapat menandai atau menghapus rekomendasi langsung dari tabel Manage Location memakai tombol bintang.
  - Field rekomendasi juga tersedia di modal tambah/edit lokasi.
- Dashboard user sekarang menampilkan tempat yang ditandai sebagai rekomendasi.
  - Jika belum ada lokasi yang ditandai rekomendasi, dashboard otomatis menampilkan lokasi terbaru.
- Card tempat menampilkan badge **Rekomendasi** untuk lokasi yang dibintangi admin.

## Perubahan teknis

- Menambahkan kolom `is_recommended` pada tabel `tb_lokasi` melalui migration baru.
- Menambahkan route `admin.lokasi.recommendation.toggle`.
- Menyesuaikan controller lokasi, user, dan dashboard.
- Menambahkan style search admin, tombol bintang rekomendasi, dan badge rekomendasi.

## Perintah setelah copy patch

```bash
php artisan migrate
php artisan optimize:clear
php artisan serve
```
