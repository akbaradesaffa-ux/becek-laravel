# BECEK Laravel

BECEK adalah aplikasi direktori café dan warkop Bekasi berbasis Laravel 12.

## Persyaratan

- PHP 8.2 atau lebih baru
- Ekstensi PHP: `mbstring`, `pdo_mysql`, `openssl`, `fileinfo`
- Untuk test PHPUnit: `dom`, `xml`, dan `xmlwriter`
- Composer
- MySQL/MariaDB
- Node.js dan npm (hanya diperlukan bila aset frontend ingin dibangun ulang)

## Instalasi lokal

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Buat database MySQL bernama `db_becek_laravel`, lalu sesuaikan `DB_USERNAME` dan `DB_PASSWORD` pada `.env`.

```bash
php artisan migrate --seed
php artisan serve
```

Buka `http://127.0.0.1:8000`.

## Akun administrator awal

Nilai akun awal dibaca dari `.env`:

```env
BECEK_ADMIN_EMAIL=admin@becek.local
BECEK_ADMIN_PASSWORD=
```

Isi `BECEK_ADMIN_PASSWORD` sebelum menjalankan seeder. Bila dibiarkan kosong, seeder membuat password acak dan menampilkannya satu kali di terminal. Simpan password tersebut dan segera ganti setelah login.

## Menjalankan test

```bash
php artisan test
```

## Perbaikan keamanan pada versi ini

- Session ID diregenerasi setelah login.
- Logout hanya menerima `POST` dan menghapus session dengan aman.
- Penghapusan data hanya menerima `DELETE`.
- Login dibatasi lima percobaan gagal per menit.
- Password minimal delapan karakter dan selalu disimpan sebagai hash.
- Migration otomatis mengubah password plaintext lama menjadi hash.
- Upload gambar dibatasi pada JPG, PNG, dan WebP serta memakai nama acak.
- Header keamanan dasar ditambahkan ke response web.
- File `.env`, `.git`, dan `vendor` tidak disertakan dalam paket distribusi bersih.

## Fitur café dan warkop terdekat

Versi ini menyediakan halaman **Terdekat** tanpa Google Maps API berbayar. Sistem menggunakan:

- Geolocation API dari browser untuk membaca posisi pengguna.
- Latitude dan longitude yang disimpan pada data lokasi.
- Rumus Haversine di Laravel untuk menghitung jarak lurus.
- Google Maps URL untuk tombol rute, tanpa API key.

Setelah memperbarui project, jalankan migration:

```bash
php artisan migrate
```

Kemudian buka **Admin → Manage Location**, edit setiap tempat, dan isi koordinatnya. Tombol **Ambil dari Link Maps** dapat membaca link Google Maps lengkap yang mengandung pola `@latitude,longitude`. Link pendek `maps.app.goo.gl` biasanya tidak memuat koordinat, sehingga koordinat perlu diisi manual atau menggunakan link lengkap.

Akses lokasi browser hanya berjalan pada `localhost` atau website produksi yang menggunakan HTTPS. Posisi pengguna dipakai untuk perhitungan pada halaman tersebut dan tidak disimpan sebagai data akun.
