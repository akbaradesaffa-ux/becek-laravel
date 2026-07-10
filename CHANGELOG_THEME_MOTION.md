# Theme Motion Update

Update ini menambahkan animasi transisi untuk fitur Tema Gelap/Terang.

## Perubahan

- Menambahkan efek smooth transition saat user mengganti tema.
- Menambahkan efek radial wash ringan dari posisi tombol tema.
- Transisi berlaku untuk halaman user dan admin.
- Pilihan tema tetap tersimpan di localStorage.
- Animasi otomatis dinonaktifkan jika browser memakai `prefers-reduced-motion`.
- Cache CSS dan JS diperbarui dengan versi `theme-motion-20260709`.

## File yang berubah

- `public/js/becek-theme-toggle.js`
- `public/css/becek-theme.css`
- `public/css/becek-admin.css`
- beberapa file Blade untuk update cache-busting asset.
