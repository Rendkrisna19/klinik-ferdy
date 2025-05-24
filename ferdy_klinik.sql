-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 24, 2025 at 03:10 PM
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
-- Database: `ferdy_klinik`
--

-- --------------------------------------------------------

--
-- Table structure for table `dokter`
--

CREATE TABLE `dokter` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `spesialis` varchar(50) DEFAULT NULL,
  `no_telepon` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `alamat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dokter`
--

INSERT INTO `dokter` (`id`, `nama`, `spesialis`, `no_telepon`, `email`, `alamat`) VALUES
(1, 'Muhammad Rendy Krisna', 'gigi', '085764133658', 'muhammadrendykrisna@gmail.com', 'kuala tanjung');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_dokter`
--

CREATE TABLE `jadwal_dokter` (
  `id` int(11) NOT NULL,
  `dokter_id` int(11) NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu') NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadwal_dokter`
--

INSERT INTO `jadwal_dokter` (`id`, `dokter_id`, `hari`, `jam_mulai`, `jam_selesai`) VALUES
(10, 19, 'Rabu', '21:54:00', '18:49:00'),
(11, 19, 'Kamis', '17:53:00', '23:55:00'),
(13, 19, 'Senin', '18:28:00', '21:24:00');

-- --------------------------------------------------------

--
-- Table structure for table `pasien`
--

CREATE TABLE `pasien` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` text DEFAULT NULL,
  `no_telp` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pasien`
--

INSERT INTO `pasien` (`id`, `nama`, `alamat`, `no_telp`) VALUES
(1, 'Rendy Krisna', 'kuala tanjung', '085764133658');

-- --------------------------------------------------------

--
-- Table structure for table `pendaftaran`
--

CREATE TABLE `pendaftaran` (
  `id` int(11) NOT NULL,
  `pasien_id` int(11) NOT NULL,
  `dokter_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `keluhan` text NOT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pendaftaran`
--

INSERT INTO `pendaftaran` (`id`, `pasien_id`, `dokter_id`, `tanggal`, `keluhan`, `status`, `created_at`) VALUES
(3, 21, 1, '2025-05-21', 'sakit sakit', 'pending', '2025-05-21 10:06:29'),
(5, 21, 19, '2025-05-21', 'skaosos', 'diterima', '2025-05-21 10:29:45'),
(6, 21, 19, '2025-05-21', 'sakit kepala', 'diterima', '2025-05-21 15:58:49'),
(7, 22, 19, '2025-05-21', 'sakit kepala', 'diterima', '2025-05-21 11:00:53'),
(8, 21, 19, '2025-05-22', 'demam', 'menunggu', '2025-05-22 06:14:03'),
(9, 24, 19, '2025-05-23', 'demam', 'pending', '2025-05-23 10:48:01'),
(10, 27, 19, '2025-05-24', 'eeee', 'diterima', '2025-05-23 22:49:51'),
(11, 27, 19, '2025-05-24', 'demam', 'menunggu', '2025-05-24 04:10:42'),
(12, 28, 19, '2025-05-24', 'sakit kepala 2 hari yang lalu', 'diterima', '2025-05-24 07:47:20'),
(13, 29, 19, '2025-05-24', 'demam 3 hari yg lalu', 'diterima', '2025-05-24 07:54:14');

-- --------------------------------------------------------

--
-- Table structure for table `rekam_medis`
--

CREATE TABLE `rekam_medis` (
  `id` int(11) NOT NULL,
  `pendaftaran_id` int(11) DEFAULT NULL,
  `dokter_id` int(11) NOT NULL,
  `pasien_id` int(11) NOT NULL,
  `diagnosa` text NOT NULL,
  `tindakan` text NOT NULL,
  `resep` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rekam_medis`
--

INSERT INTO `rekam_medis` (`id`, `pendaftaran_id`, `dokter_id`, `pasien_id`, `diagnosa`, `tindakan`, `resep`, `created_at`, `updated_at`) VALUES
(2, NULL, 19, 21, 'f33', 'fff', 'fff', '2025-05-21 23:02:28', '2025-05-23 18:42:59'),
(3, NULL, 19, 21, 'sakit ', 'ddd', 'dd', '2025-05-22 10:22:24', '2025-05-22 10:22:24'),
(4, NULL, 19, 27, 'sakit', 'diberi obat', 'obat penurun demam', '2025-05-24 10:54:14', '2025-05-24 10:54:14'),
(5, NULL, 19, 29, 'sakit pilet', 'diberi obat', 'sanmol,', '2025-05-24 19:55:48', '2025-05-24 19:55:48');

-- --------------------------------------------------------

--
-- Table structure for table `tagihan_pembayaran`
--

CREATE TABLE `tagihan_pembayaran` (
  `id` int(11) NOT NULL,
  `pasien_id` int(11) NOT NULL,
  `nominal` decimal(15,2) NOT NULL,
  `status` enum('pending','lunas') DEFAULT 'pending',
  `bukti_transfer` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tagihan_pembayaran`
--

INSERT INTO `tagihan_pembayaran` (`id`, `pasien_id`, `nominal`, `status`, `bukti_transfer`, `created_at`, `updated_at`) VALUES
(3, 24, 300000.00, 'pending', NULL, '2025-05-23 17:54:45', '2025-05-23 17:54:45'),
(4, 27, 200000.00, 'pending', NULL, '2025-05-24 10:57:44', '2025-05-24 10:57:44'),
(5, 24, 400000.00, 'pending', NULL, '2025-05-24 19:28:14', '2025-05-24 19:28:14'),
(6, 29, 300000.00, 'lunas', 'bukti_6_1748091511.png', '2025-05-24 19:57:38', '2025-05-24 19:59:07');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','dokter','resepsionis','pasien') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(19, 'dokter ferdy', 'dokter@gmail.com', '$2y$10$Br23QrukC.3A8Yy7uvMQzO2LmU1IJHb5XaFF4wbfGJ4C9tqoKbmJe', 'dokter', '2025-05-21 13:33:32'),
(20, 'resepsionisjo', 'resepsionis@gmail.com', '$2y$10$oEqHHiawyVDZAS9HsmvchumTXHt/fS51GMKn8zMOTB4SKlhY5VzZW', 'resepsionis', '2025-05-21 13:33:45'),
(21, 'muhammad ferdy', 'pasien@gmail.com', '$2y$10$NsIq6GatpyYKzfGG45O9GeadTAeFniMeDPGhDWBY8d6jqxA51hbj.', 'pasien', '2025-05-21 13:33:59'),
(22, 'admin ferdy', 'admin@gmail.com', '$2y$10$Z/hdp0WyJfjCoyuukTu2xu04.lj8t0UZ5vvQL6rrbuzXF2z26ZGfS', 'admin', '2025-05-21 13:34:12'),
(23, 'admin2', 'admin2@gmail.com', '$2y$10$xF7IMjtlSja1omkobIw9uOX3ky.j3gsAoJXXHFWJzQr07uDI2A0hu', 'admin', '2025-05-21 16:37:21'),
(24, 'Ferdy sambo', 'ferdy@gmail.com', '123', 'pasien', '2025-05-23 02:52:14'),
(25, 'admin4', 'admin4@gmail.com', '$2y$10$j0BrpVcC8azb7HOcAJ0L8OiBOMaqTWCPVk3VbSLPbBnTcNpBFkwVC', 'admin', '2025-05-23 10:09:26'),
(27, 'Ferdyu', 'Ferdyu@gmail.com', '$2y$10$Eb53L9bIPSvlQO2bwzZOLusRKRAQ00JAYvDC99bXfdAT6FcctNUeS', 'pasien', '2025-05-24 03:39:40'),
(28, 'ferdy pasien', 'ferdiansyah@gmail.com', '$2y$10$hCCjstLJk0EfqI/n4L71Q.OPKx1nhG.Cp7/O3zgHOedGxWgYNOkq2', 'pasien', '2025-05-24 12:46:35'),
(29, 'randy karna', 'randy@gmail.com', '$2y$10$RJwz9TooPkjwQjmuM3eMIeof6/OjBL5/WgibAtrzfcRJ8Ko0hM1KW', 'pasien', '2025-05-24 12:53:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dokter`
--
ALTER TABLE `dokter`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jadwal_dokter`
--
ALTER TABLE `jadwal_dokter`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dokter_id` (`dokter_id`);

--
-- Indexes for table `pasien`
--
ALTER TABLE `pasien`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pendaftaran`
--
ALTER TABLE `pendaftaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dokter_id` (`dokter_id`),
  ADD KEY `pendaftaran_ibfk_1` (`pasien_id`);

--
-- Indexes for table `rekam_medis`
--
ALTER TABLE `rekam_medis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pendaftaran_id` (`pendaftaran_id`),
  ADD KEY `dokter_id` (`dokter_id`),
  ADD KEY `pasien_id` (`pasien_id`);

--
-- Indexes for table `tagihan_pembayaran`
--
ALTER TABLE `tagihan_pembayaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pasien_id` (`pasien_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dokter`
--
ALTER TABLE `dokter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jadwal_dokter`
--
ALTER TABLE `jadwal_dokter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `pasien`
--
ALTER TABLE `pasien`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pendaftaran`
--
ALTER TABLE `pendaftaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `rekam_medis`
--
ALTER TABLE `rekam_medis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tagihan_pembayaran`
--
ALTER TABLE `tagihan_pembayaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jadwal_dokter`
--
ALTER TABLE `jadwal_dokter`
  ADD CONSTRAINT `jadwal_dokter_ibfk_1` FOREIGN KEY (`dokter_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pendaftaran`
--
ALTER TABLE `pendaftaran`
  ADD CONSTRAINT `pendaftaran_ibfk_1` FOREIGN KEY (`pasien_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `rekam_medis`
--
ALTER TABLE `rekam_medis`
  ADD CONSTRAINT `rekam_medis_ibfk_1` FOREIGN KEY (`pendaftaran_id`) REFERENCES `pendaftaran` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rekam_medis_ibfk_2` FOREIGN KEY (`dokter_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rekam_medis_ibfk_3` FOREIGN KEY (`pasien_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tagihan_pembayaran`
--
ALTER TABLE `tagihan_pembayaran`
  ADD CONSTRAINT `tagihan_pembayaran_ibfk_1` FOREIGN KEY (`pasien_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
