# QA Report — Fitur Terdekat

## Pemeriksaan yang berhasil

- Syntax PHP: 42 file pada folder `app`, `database`, `routes`, dan `tests`.
- Kompilasi dan syntax hasil Blade: 17 view.
- Syntax JavaScript: `becek-nearby.js`, `jspage7.js`, dan `becek-user.js`.
- Registrasi route Laravel: 29 route, termasuk `GET /terdekat`.
- Uji fungsi Haversine: selisih lintang 0,0005° menghasilkan sekitar 0,0556 km.
- Uji accessor model: status koordinat, label koordinat, dan Google Maps Directions URL.

## Test otomatis yang ditambahkan

`tests/Feature/NearbyLocationTest.php` menguji:

- halaman terdekat memerlukan autentikasi;
- lokasi diurutkan berdasarkan jarak;
- tempat di luar radius tidak ditampilkan;
- filter kategori Cafe/Warkop;
- tombol rute ditampilkan pada hasil.

## Keterbatasan lingkungan pemeriksaan

PHPUnit tidak dapat dijalankan pada container pemeriksaan karena ekstensi PHP `dom`, `mbstring`, `xml`, `xmlwriter`, dan `pdo_sqlite` tidak tersedia. Jalankan `php artisan test` pada perangkat yang memenuhi persyaratan di README.
