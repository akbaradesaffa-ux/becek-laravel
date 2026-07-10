-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 10 Jul 2026 pada 13.53
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_becek_laravel`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_07_02_025827_create_fasilitas_table', 1),
(5, '2026_07_02_025827_create_lokasis_table', 1),
(6, '2026_07_02_025831_create_tb_lokasi_fasilitas_table', 1),
(7, '2026_07_09_000001_create_tb_favorit_table', 2),
(8, '2026_07_09_000002_add_email_to_tb_user', 3),
(9, '2026_07_09_000003_add_operasional_area_to_tb_lokasi', 4),
(10, '2026_07_09_000004_create_tb_lokasi_foto_table', 4),
(11, '2026_07_09_000005_add_recommendation_to_tb_lokasi', 5);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_fasilitas`
--

CREATE TABLE `tb_fasilitas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_fasilitas` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tb_fasilitas`
--

INSERT INTO `tb_fasilitas` (`id`, `nama_fasilitas`) VALUES
(2, 'AC'),
(7, 'Indoor'),
(1, 'LIFE MUSIC'),
(4, 'Outdoor'),
(5, 'Semi Outdoor');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_favorit`
--

CREATE TABLE `tb_favorit` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `lokasi_id` bigint(20) UNSIGNED NOT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_lokasi`
--

CREATE TABLE `tb_lokasi` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_lokasi` varchar(10) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `kategori` enum('Cafe','Warkop') NOT NULL,
  `rentang_harga` varchar(100) NOT NULL,
  `link_google_maps` text NOT NULL,
  `jalur_foto` varchar(255) NOT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT current_timestamp(),
  `area` varchar(120) DEFAULT NULL,
  `hari_operasional` varchar(120) DEFAULT NULL,
  `jam_buka` time DEFAULT NULL,
  `jam_tutup` time DEFAULT NULL,
  `is_recommended` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tb_lokasi`
--

INSERT INTO `tb_lokasi` (`id`, `kode_lokasi`, `nama`, `kategori`, `rentang_harga`, `link_google_maps`, `jalur_foto`, `dibuat_pada`, `area`, `hari_operasional`, `jam_buka`, `jam_tutup`, `is_recommended`) VALUES
(7, '#920', 'DOBRO', 'Cafe', '20k', 'https://maps.app.goo.gl/cwES4hWE2smE8jey9', '1783592186_dobro.jpeg', '2026-07-09 10:16:26', NULL, NULL, NULL, NULL, 0),
(8, '#506', 'Warkop Tegar', 'Warkop', '5k', 'https://maps.app.goo.gl/qkmk3CxnSDtEsso56', '1783592280_tegar.webp', '2026-07-09 10:18:00', NULL, NULL, NULL, NULL, 0),
(9, '#514', 'WARJON', 'Warkop', '5K', 'https://maps.app.goo.gl/VBUh4LTHu1vhWmaW7', '1783592381_warjo.jpeg', '2026-07-09 10:19:41', NULL, NULL, NULL, NULL, 0),
(10, '#343', 'Kopilikasi.', 'Cafe', '25', 'https://maps.app.goo.gl/amQuUeL2nyPK5HAw5', '1783592457_kopilikasi.jpeg', '2026-07-09 10:20:57', NULL, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_lokasi_fasilitas`
--

CREATE TABLE `tb_lokasi_fasilitas` (
  `lokasi_id` bigint(20) UNSIGNED NOT NULL,
  `fasilitas_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tb_lokasi_fasilitas`
--

INSERT INTO `tb_lokasi_fasilitas` (`lokasi_id`, `fasilitas_id`) VALUES
(7, 2),
(7, 4),
(7, 5),
(7, 7),
(8, 5),
(9, 1),
(9, 5),
(10, 2),
(10, 5),
(10, 7);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_lokasi_foto`
--

CREATE TABLE `tb_lokasi_foto` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lokasi_id` bigint(20) UNSIGNED NOT NULL,
  `jalur_foto` varchar(255) NOT NULL,
  `urutan` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `dibuat_pada` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tb_lokasi_foto`
--

INSERT INTO `tb_lokasi_foto` (`id`, `lokasi_id`, `jalur_foto`, `urutan`, `dibuat_pada`) VALUES
(1, 7, '1783606059_6a4fab2b29718_dobro.jpeg', 1, '2026-07-09 14:07:39');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_user`
--

CREATE TABLE `tb_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_lengkap` varchar(150) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `status_role` varchar(50) NOT NULL DEFAULT 'User',
  `dibuat_pada` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tb_user`
--

INSERT INTO `tb_user` (`id`, `nama_lengkap`, `email`, `username`, `password`, `status_role`, `dibuat_pada`) VALUES
(12, 'Ade Saffa Akbar', 'akbaradesaffa@gmail.com', 'akbaradesaffa@gmail.com', '$2y$10$JmhcgbSYNmjKtBRKNHFbIO./4cE//cDYp0F8A45sArYxy6K2Rrcoa', 'Administrator', '2026-07-09 13:03:16'),
(13, 'Elka', 'elka@gmail.com', 'elka@gmail.com', '$2y$10$0AtGtgdOM615HRbM4kTXAOP.oAgIA8/jAaXWGZwPOABNs1KLIelHa', 'User', '2026-07-09 13:58:05');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tb_fasilitas`
--
ALTER TABLE `tb_fasilitas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tb_fasilitas_nama_fasilitas_unique` (`nama_fasilitas`);

--
-- Indeks untuk tabel `tb_favorit`
--
ALTER TABLE `tb_favorit`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tb_favorit_user_id_lokasi_id_unique` (`user_id`,`lokasi_id`),
  ADD KEY `tb_favorit_lokasi_id_foreign` (`lokasi_id`);

--
-- Indeks untuk tabel `tb_lokasi`
--
ALTER TABLE `tb_lokasi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tb_lokasi_kode_lokasi_unique` (`kode_lokasi`);

--
-- Indeks untuk tabel `tb_lokasi_fasilitas`
--
ALTER TABLE `tb_lokasi_fasilitas`
  ADD PRIMARY KEY (`lokasi_id`,`fasilitas_id`),
  ADD KEY `tb_lokasi_fasilitas_fasilitas_id_foreign` (`fasilitas_id`);

--
-- Indeks untuk tabel `tb_lokasi_foto`
--
ALTER TABLE `tb_lokasi_foto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tb_lokasi_foto_lokasi_id_foreign` (`lokasi_id`);

--
-- Indeks untuk tabel `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tb_user_username_unique` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `tb_fasilitas`
--
ALTER TABLE `tb_fasilitas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `tb_favorit`
--
ALTER TABLE `tb_favorit`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `tb_lokasi`
--
ALTER TABLE `tb_lokasi`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `tb_lokasi_foto`
--
ALTER TABLE `tb_lokasi_foto`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `tb_favorit`
--
ALTER TABLE `tb_favorit`
  ADD CONSTRAINT `tb_favorit_lokasi_id_foreign` FOREIGN KEY (`lokasi_id`) REFERENCES `tb_lokasi` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tb_favorit_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `tb_user` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tb_lokasi_fasilitas`
--
ALTER TABLE `tb_lokasi_fasilitas`
  ADD CONSTRAINT `tb_lokasi_fasilitas_fasilitas_id_foreign` FOREIGN KEY (`fasilitas_id`) REFERENCES `tb_fasilitas` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_lokasi_fasilitas_lokasi_id_foreign` FOREIGN KEY (`lokasi_id`) REFERENCES `tb_lokasi` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tb_lokasi_foto`
--
ALTER TABLE `tb_lokasi_foto`
  ADD CONSTRAINT `tb_lokasi_foto_lokasi_id_foreign` FOREIGN KEY (`lokasi_id`) REFERENCES `tb_lokasi` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
