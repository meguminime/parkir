-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 11, 2026 at 07:11 AM
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
-- Database: `parkir`
--

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
(4, '2026_06_08_003207_create_locations_table', 1),
(5, '2026_06_08_003239_create_vehicle_types_table', 1),
(6, '2026_06_08_003305_create_transactions_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `parkir_locations`
--

CREATE TABLE `parkir_locations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `location_name` varchar(100) NOT NULL,
  `max_motorcycle` int(11) NOT NULL,
  `max_car` int(11) NOT NULL,
  `max_other` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `parkir_locations`
--

INSERT INTO `parkir_locations` (`id`, `location_name`, `max_motorcycle`, `max_car`, `max_other`, `created_at`, `updated_at`) VALUES
(10, 'Gedung A', 5, 5, 3, '2026-06-08 12:35:17', '2026-06-08 12:42:38'),
(11, 'Gedung B', 4, 4, 3, '2026-06-08 12:35:46', '2026-06-08 12:41:52'),
(12, 'Gedung C', 6, 6, 6, '2026-06-08 12:36:03', '2026-06-08 12:36:03');

-- --------------------------------------------------------

--
-- Table structure for table `parkir_transactions`
--

CREATE TABLE `parkir_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_lokasi` bigint(20) UNSIGNED NOT NULL,
  `no_tiket` varchar(255) NOT NULL,
  `no_polisi` varchar(15) DEFAULT NULL,
  `id_jenis` bigint(20) UNSIGNED NOT NULL,
  `masuk` datetime NOT NULL,
  `keluar` datetime DEFAULT NULL,
  `perjam_pertama` int(11) NOT NULL,
  `perjam_berikutnya` int(11) NOT NULL,
  `max_perhari` int(11) NOT NULL,
  `total_jam` int(11) DEFAULT NULL,
  `total_bayar` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `parkir_transactions`
--

INSERT INTO `parkir_transactions` (`id`, `id_lokasi`, `no_tiket`, `no_polisi`, `id_jenis`, `masuk`, `keluar`, `perjam_pertama`, `perjam_berikutnya`, `max_perhari`, `total_jam`, `total_bayar`, `created_at`, `updated_at`) VALUES
(20, 10, '202606081939399', '-', 12, '2026-06-08 19:39:39', '2026-06-08 19:42:38', 3000, 2000, 10000, 3, 5000, '2026-06-08 12:39:39', '2026-06-08 12:42:38'),
(21, 11, '202606081939504', '-', 13, '2026-06-08 19:39:50', '2026-06-08 19:41:52', 5000, 3000, 10000, 2, 8000, '2026-06-08 12:39:50', '2026-06-08 12:41:52');

-- --------------------------------------------------------

--
-- Table structure for table `parkir_vehicle_types`
--

CREATE TABLE `parkir_vehicle_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `jenis` enum('motorcycle','car','other') NOT NULL,
  `perjam_pertama` int(11) NOT NULL,
  `perjam_berikutnya` int(11) NOT NULL,
  `max_perhari` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `parkir_vehicle_types`
--

INSERT INTO `parkir_vehicle_types` (`id`, `jenis`, `perjam_pertama`, `perjam_berikutnya`, `max_perhari`, `created_at`, `updated_at`) VALUES
(12, 'motorcycle', 3000, 2000, 10000, '2026-06-08 12:37:37', '2026-06-08 12:37:37'),
(13, 'car', 5000, 3000, 10000, '2026-06-08 12:37:52', '2026-06-08 12:37:52'),
(14, 'other', 8000, 5000, 15000, '2026-06-08 12:38:08', '2026-06-08 12:38:08');

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
('1NfDBxUNLx4KYVep3EXGY6qItUzGrFcOLJBLXSsx', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaXROdjJaclkxM2pFaWZhY0JIZFhRTU13UFh0S2dhUmlBd0lBOUNBZyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC90cmFuc2FjdGlvbnMiO3M6NToicm91dGUiO3M6MTg6InRyYW5zYWN0aW9ucy5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1780922558),
('Kr5ubDdBUmTakPzMv4IQSwqzT3zQt8nLDHk2PtHO', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.123.0 Chrome/148.0.7778.97 Electron/42.2.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaFlOcGJyeFc3TmZGcUVrMERWTU9FdWx3ZEFaMU9sa1FQR091RVZxUCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC90cmFuc2FjdGlvbnMiO3M6NToicm91dGUiO3M6MTg6InRyYW5zYWN0aW9ucy5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1780919700);

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
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parkir_locations`
--
ALTER TABLE `parkir_locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parkir_transactions`
--
ALTER TABLE `parkir_transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `parkir_transactions_no_tiket_unique` (`no_tiket`),
  ADD KEY `parkir_transactions_id_lokasi_foreign` (`id_lokasi`),
  ADD KEY `parkir_transactions_id_jenis_foreign` (`id_jenis`);

--
-- Indexes for table `parkir_vehicle_types`
--
ALTER TABLE `parkir_vehicle_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

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
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `parkir_locations`
--
ALTER TABLE `parkir_locations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `parkir_transactions`
--
ALTER TABLE `parkir_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `parkir_vehicle_types`
--
ALTER TABLE `parkir_vehicle_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `parkir_transactions`
--
ALTER TABLE `parkir_transactions`
  ADD CONSTRAINT `parkir_transactions_id_jenis_foreign` FOREIGN KEY (`id_jenis`) REFERENCES `parkir_vehicle_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `parkir_transactions_id_lokasi_foreign` FOREIGN KEY (`id_lokasi`) REFERENCES `parkir_locations` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
