# Ringkasan Perbaikan

Tanggal: 2 Agustus 2026

1. Menghapus route penghapusan berbasis GET.
2. Mengubah logout menjadi POST dengan CSRF.
3. Mengubah hapus akun menjadi DELETE dengan verifikasi password hash.
4. Menambahkan regenerasi dan invalidasi session.
5. Menambahkan rate limit login.
6. Menormalisasi email menjadi huruf kecil.
7. Menaikkan password minimum dari 6 menjadi 8 karakter.
8. Menambahkan migration untuk meng-hash password lama yang masih plaintext.
9. Melindungi admin aktif dan administrator terakhir dari penghapusan.
10. Memperbaiki penghapusan fasilitas yang masih memiliki relasi lokasi.
11. Memperketat validasi upload dan mengacak nama file.
12. Menambahkan header keamanan dan test fitur dasar.
13. Menyesuaikan timezone aplikasi ke Asia/Jakarta.
