-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 08, 2025 at 08:12 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `reusemart`
--

-- --------------------------------------------------------

--
-- Table structure for table `alamat_pembelis`
--

CREATE TABLE `alamat_pembelis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_pembeli` bigint(20) UNSIGNED NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `barangs`
--

CREATE TABLE `barangs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_penitip` bigint(20) UNSIGNED NOT NULL,
  `id_kategori` bigint(20) UNSIGNED NOT NULL,
  `tgl_penitipan` date NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `harga_barang` double NOT NULL,
  `deskripsi` varchar(255) NOT NULL,
  `status_garansi` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail_barangs`
--

CREATE TABLE `detail_barangs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_barang` bigint(20) UNSIGNED NOT NULL,
  `status_barang` varchar(255) NOT NULL,
  `jumlah_barang` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail_pengirimans`
--

CREATE TABLE `detail_pengirimans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `status_pengiriman` varchar(255) NOT NULL,
  `metode_pengiriman` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail_transaksi_penjualans`
--

CREATE TABLE `detail_transaksi_penjualans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_transaksi_penjualan` bigint(20) UNSIGNED NOT NULL,
  `id_pegawai` bigint(20) UNSIGNED NOT NULL,
  `status_pembelian` varchar(255) NOT NULL,
  `verifikasi_pembayaran` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
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
-- Table structure for table `jabatans`
--

CREATE TABLE `jabatans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
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
-- Table structure for table `job_batches`
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
-- Table structure for table `kategori_barangs`
--

CREATE TABLE `kategori_barangs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_kategori` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `merchandises`
--

CREATE TABLE `merchandises` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `jenis_merchandise` varchar(255) NOT NULL,
  `nilai_point` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_04_24_011155_create_jabatans_table', 1),
(5, '2025_04_24_011233_create_pegawais_table', 1),
(6, '2025_04_24_011426_create_penitips_table', 1),
(7, '2025_04_24_011500_create_kategori_barangs_table', 1),
(8, '2025_04_24_011700_create_barangs_table', 1),
(9, '2025_04_24_011709_create_organisasis_table', 1),
(10, '2025_04_24_011748_create_detail_barangs_table', 1),
(11, '2025_04_24_011845_create_transaksi_donasis_table', 1),
(12, '2025_04_24_011929_create_pembelis_table', 1),
(13, '2025_04_24_012010_create_alamat_pembelis_table', 1),
(14, '2025_04_24_012606_create_transaksi_penitipans_table', 1),
(15, '2025_04_24_012650_create_merchandises_table', 1),
(16, '2025_04_24_012714_create_penukaran_merchandises_table', 1),
(17, '2025_04_24_100000_create_detail_pengirimans_table', 1),
(18, '2025_04_24_100500_create_transaksi_penjualans_table', 1),
(19, '2025_04_24_100501_create_detail_transaksi_penjualans_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `organisasis`
--

CREATE TABLE `organisasis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `permintaan` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `no_hp` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pegawais`
--

CREATE TABLE `pegawais` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_jabatan` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gaji` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembelis`
--

CREATE TABLE `pembelis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_pembeli` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `no_hp` varchar(255) NOT NULL,
  `point` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penitips`
--

CREATE TABLE `penitips` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_penitip` varchar(255) NOT NULL,
  `no_ktp` varchar(255) NOT NULL,
  `saldo` double NOT NULL,
  `point` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `badge` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penukaran_merchandises`
--

CREATE TABLE `penukaran_merchandises` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_pembeli` bigint(20) UNSIGNED NOT NULL,
  `id_merhandise` bigint(20) UNSIGNED NOT NULL,
  `id_pegawai` bigint(20) UNSIGNED NOT NULL,
  `tanggal_penukaran` date NOT NULL,
  `status` varchar(255) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('JeC41WWOGlOthEtNGfgkcAf8Ch8kzZnjKTvcFqW6', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:138.0) Gecko/20100101 Firefox/138.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRG1Tbnk3OHRuU0JCQVdOdXY5R21kOWhuZ3NQVnFSR285c3Y0ZWFHZSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1746684099);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_donasis`
--

CREATE TABLE `transaksi_donasis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_organisasi` bigint(20) UNSIGNED NOT NULL,
  `nama_penitip` varchar(255) NOT NULL,
  `tgl_transaksi` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_penitipans`
--

CREATE TABLE `transaksi_penitipans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_penitip` bigint(20) UNSIGNED NOT NULL,
  `tgl_penitipan` datetime NOT NULL,
  `status_penitipan` varchar(255) NOT NULL,
  `durasi_penitipan` int(11) NOT NULL,
  `batas_akhir` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_penjualans`
--

CREATE TABLE `transaksi_penjualans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_pengiriman` bigint(20) UNSIGNED NOT NULL,
  `id_pembeli` bigint(20) UNSIGNED NOT NULL,
  `total_harga_pembelian` double NOT NULL,
  `alamat_pengiriman` varchar(255) NOT NULL,
  `ongkir` double NOT NULL,
  `bukti_pembayaran` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alamat_pembelis`
--
ALTER TABLE `alamat_pembelis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `alamat_pembelis_id_pembeli_foreign` (`id_pembeli`);

--
-- Indexes for table `barangs`
--
ALTER TABLE `barangs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `barangs_id_penitip_foreign` (`id_penitip`),
  ADD KEY `barangs_id_kategori_foreign` (`id_kategori`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `detail_barangs`
--
ALTER TABLE `detail_barangs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detail_barangs_id_barang_foreign` (`id_barang`);

--
-- Indexes for table `detail_pengirimans`
--
ALTER TABLE `detail_pengirimans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `detail_transaksi_penjualans`
--
ALTER TABLE `detail_transaksi_penjualans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detail_transaksi_penjualans_id_transaksi_penjualan_foreign` (`id_transaksi_penjualan`),
  ADD KEY `detail_transaksi_penjualans_id_pegawai_foreign` (`id_pegawai`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jabatans`
--
ALTER TABLE `jabatans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kategori_barangs`
--
ALTER TABLE `kategori_barangs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `merchandises`
--
ALTER TABLE `merchandises`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organisasis`
--
ALTER TABLE `organisasis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pegawais`
--
ALTER TABLE `pegawais`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pegawais_id_jabatan_foreign` (`id_jabatan`);

--
-- Indexes for table `pembelis`
--
ALTER TABLE `pembelis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `penitips`
--
ALTER TABLE `penitips`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `penukaran_merchandises`
--
ALTER TABLE `penukaran_merchandises`
  ADD PRIMARY KEY (`id`),
  ADD KEY `penukaran_merchandises_id_pembeli_foreign` (`id_pembeli`),
  ADD KEY `penukaran_merchandises_id_merhandise_foreign` (`id_merhandise`),
  ADD KEY `penukaran_merchandises_id_pegawai_foreign` (`id_pegawai`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `transaksi_donasis`
--
ALTER TABLE `transaksi_donasis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaksi_donasis_id_organisasi_foreign` (`id_organisasi`);

--
-- Indexes for table `transaksi_penitipans`
--
ALTER TABLE `transaksi_penitipans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaksi_penitipans_id_penitip_foreign` (`id_penitip`);

--
-- Indexes for table `transaksi_penjualans`
--
ALTER TABLE `transaksi_penjualans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaksi_penjualans_id_pengiriman_foreign` (`id_pengiriman`),
  ADD KEY `transaksi_penjualans_id_pembeli_foreign` (`id_pembeli`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alamat_pembelis`
--
ALTER TABLE `alamat_pembelis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `barangs`
--
ALTER TABLE `barangs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detail_barangs`
--
ALTER TABLE `detail_barangs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detail_pengirimans`
--
ALTER TABLE `detail_pengirimans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detail_transaksi_penjualans`
--
ALTER TABLE `detail_transaksi_penjualans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jabatans`
--
ALTER TABLE `jabatans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategori_barangs`
--
ALTER TABLE `kategori_barangs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `merchandises`
--
ALTER TABLE `merchandises`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `organisasis`
--
ALTER TABLE `organisasis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pegawais`
--
ALTER TABLE `pegawais`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pembelis`
--
ALTER TABLE `pembelis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `penitips`
--
ALTER TABLE `penitips`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `penukaran_merchandises`
--
ALTER TABLE `penukaran_merchandises`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaksi_donasis`
--
ALTER TABLE `transaksi_donasis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaksi_penitipans`
--
ALTER TABLE `transaksi_penitipans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaksi_penjualans`
--
ALTER TABLE `transaksi_penjualans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `alamat_pembelis`
--
ALTER TABLE `alamat_pembelis`
  ADD CONSTRAINT `alamat_pembelis_id_pembeli_foreign` FOREIGN KEY (`id_pembeli`) REFERENCES `pembelis` (`id`);

--
-- Constraints for table `barangs`
--
ALTER TABLE `barangs`
  ADD CONSTRAINT `barangs_id_kategori_foreign` FOREIGN KEY (`id_kategori`) REFERENCES `kategori_barangs` (`id`),
  ADD CONSTRAINT `barangs_id_penitip_foreign` FOREIGN KEY (`id_penitip`) REFERENCES `penitips` (`id`);

--
-- Constraints for table `detail_barangs`
--
ALTER TABLE `detail_barangs`
  ADD CONSTRAINT `detail_barangs_id_barang_foreign` FOREIGN KEY (`id_barang`) REFERENCES `barangs` (`id`);

--
-- Constraints for table `detail_transaksi_penjualans`
--
ALTER TABLE `detail_transaksi_penjualans`
  ADD CONSTRAINT `detail_transaksi_penjualans_id_pegawai_foreign` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawais` (`id`),
  ADD CONSTRAINT `detail_transaksi_penjualans_id_transaksi_penjualan_foreign` FOREIGN KEY (`id_transaksi_penjualan`) REFERENCES `transaksi_penjualans` (`id`);

--
-- Constraints for table `pegawais`
--
ALTER TABLE `pegawais`
  ADD CONSTRAINT `pegawais_id_jabatan_foreign` FOREIGN KEY (`id_jabatan`) REFERENCES `jabatans` (`id`);

--
-- Constraints for table `penukaran_merchandises`
--
ALTER TABLE `penukaran_merchandises`
  ADD CONSTRAINT `penukaran_merchandises_id_merhandise_foreign` FOREIGN KEY (`id_merhandise`) REFERENCES `merchandises` (`id`),
  ADD CONSTRAINT `penukaran_merchandises_id_pegawai_foreign` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawais` (`id`),
  ADD CONSTRAINT `penukaran_merchandises_id_pembeli_foreign` FOREIGN KEY (`id_pembeli`) REFERENCES `pembelis` (`id`);

--
-- Constraints for table `transaksi_donasis`
--
ALTER TABLE `transaksi_donasis`
  ADD CONSTRAINT `transaksi_donasis_id_organisasi_foreign` FOREIGN KEY (`id_organisasi`) REFERENCES `organisasis` (`id`);

--
-- Constraints for table `transaksi_penitipans`
--
ALTER TABLE `transaksi_penitipans`
  ADD CONSTRAINT `transaksi_penitipans_id_penitip_foreign` FOREIGN KEY (`id_penitip`) REFERENCES `penitips` (`id`);

--
-- Constraints for table `transaksi_penjualans`
--
ALTER TABLE `transaksi_penjualans`
  ADD CONSTRAINT `transaksi_penjualans_id_pembeli_foreign` FOREIGN KEY (`id_pembeli`) REFERENCES `pembelis` (`id`),
  ADD CONSTRAINT `transaksi_penjualans_id_pengiriman_foreign` FOREIGN KEY (`id_pengiriman`) REFERENCES `detail_pengirimans` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
