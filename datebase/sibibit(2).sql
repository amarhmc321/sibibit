-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 22, 2026 at 08:59 AM
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
-- Database: `sibibit`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_bibit`
--

CREATE TABLE `tbl_bibit` (
  `id_bibit` int(11) UNSIGNED NOT NULL,
  `nama_bibit` varchar(100) NOT NULL,
  `stock` int(11) NOT NULL,
  `jml_per_hektar` int(11) NOT NULL DEFAULT 100,
  `satuan` varchar(50) NOT NULL DEFAULT 'pohon',
  `tgl_ketersedian` datetime NOT NULL DEFAULT current_timestamp(),
  `foto` varchar(255) NOT NULL DEFAULT 'default.jpg',
  `desk` text NOT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_bibit`
--

INSERT INTO `tbl_bibit` (`id_bibit`, `nama_bibit`, `stock`, `tgl_ketersedian`, `foto`, `desk`, `status`, `created_at`) VALUES
(1, 'Kakao', 0, '2026-08-16 21:23:03', 'default.jpg', 'Bibit kakao unggul siap tanam untuk hasil panen yang lebih maksimal', 0, '2026-08-16 21:23:03'),
(2, 'Kelapa', 1000, '2026-08-16 21:53:32', '1787030186_6a83eaaa185d2.png', 'Varian karet klon unggul tahan penyakit.', 0, '2026-08-16 21:53:32'),
(3, 'Teskon', 0, '2026-08-18 12:40:12', 'default.jpg', 'Teskon', 1, '2026-08-18 12:40:12'),
(4, 'shja', 0, '2026-08-18 13:10:09', '1787029809_6a83e931e0779.png', 'sjja', 1, '2026-08-18 13:10:09'),
(5, 'tgg', 0, '2026-08-18 13:23:49', '1787030629_6a83ec6551125.png', 'ghjh', 1, '2026-08-18 13:23:49');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_desa`
--

CREATE TABLE `tbl_desa` (
  `id_desa` int(11) UNSIGNED NOT NULL,
  `id_kecamatan` int(11) UNSIGNED NOT NULL,
  `nama_desa` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_desa`
--

INSERT INTO `tbl_desa` (`id_desa`, `id_kecamatan`, `nama_desa`) VALUES
(1, 1, 'Anawoi'),
(2, 1, 'Popalia'),
(3, 3, 'Oko Oko'),
(4, 3, 'Pelambua');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_form`
--

CREATE TABLE `tbl_form` (
  `id_form` int(11) UNSIGNED NOT NULL,
  `id_bibit` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_form`
--

INSERT INTO `tbl_form` (`id_form`, `id_bibit`) VALUES
(1, 1),
(2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_groups`
--

CREATE TABLE `tbl_groups` (
  `id_group` int(11) UNSIGNED NOT NULL,
  `id_desa` int(11) UNSIGNED DEFAULT NULL,
  `nama_kelompok` varchar(100) NOT NULL,
  `id_leader` int(11) UNSIGNED DEFAULT NULL,
  `komoditas` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_groups`
--

INSERT INTO `tbl_groups` (`id_group`, `id_desa`, `nama_kelompok`, `id_leader`, `komoditas`, `created_at`) VALUES
(1, 2, 'Akatsuki', 2, 'Pecinta Kukang', '2026-07-26 14:35:13'),
(2, 1, 'Pasobis', 5, 'Ganja', '2026-07-26 14:35:13'),
(5, 3, 'Anonim', 3, 'Barang Ilegal', '2026-07-28 10:25:03');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_group_details`
--

CREATE TABLE `tbl_group_details` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_user` int(11) UNSIGNED DEFAULT NULL,
  `id_group` int(11) UNSIGNED DEFAULT NULL,
  `id_lahan` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_kecamatan`
--

CREATE TABLE `tbl_kecamatan` (
  `id_kecamatan` int(11) UNSIGNED NOT NULL,
  `nama_kecamatan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_kecamatan`
--

INSERT INTO `tbl_kecamatan` (`id_kecamatan`, `nama_kecamatan`) VALUES
(1, 'Tanggetada'),
(2, 'Baula'),
(3, 'Pomalaa');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_lahan`
--

CREATE TABLE `tbl_lahan` (
  `id_lahan` int(11) UNSIGNED NOT NULL,
  `id_petani` int(11) UNSIGNED DEFAULT NULL,
  `id_desa` int(11) UNSIGNED DEFAULT NULL,
  `id_group` int(11) UNSIGNED DEFAULT NULL,
  `longitude` decimal(11,6) NOT NULL,
  `latitude` decimal(10,6) NOT NULL,
  `luas_lahan` float NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_lahan`
--

INSERT INTO `tbl_lahan` (`id_lahan`, `id_petani`, `id_desa`, `id_group`, `longitude`, `latitude`, `luas_lahan`, `created_at`) VALUES
(1, 2, 1, 1, 121.654145, -4.041125, 10, '2026-07-28 10:37:39'),
(2, 3, 4, 1, 121.626665, -4.063042, 12, '2026-07-28 10:37:39'),
(3, 5, 1, 2, 0.000000, 0.000000, 8, '2026-07-28 10:37:39'),
(4, 6, 2, 2, 0.000000, 0.000000, 4, '2026-07-28 10:37:39'),
(8, 14, 2, 1, 121.520200, -4.334200, 12, '2026-08-03 12:02:36');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notifikasi`
--

CREATE TABLE `tbl_notifikasi` (
  `id_notifikasi` int(11) NOT NULL,
  `message` text NOT NULL,
  `type` enum('Pending','Approved') NOT NULL DEFAULT 'Pending',
  `id_ref` int(11) UNSIGNED NOT NULL,
  `id_target` int(11) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_notifikasi`
--

INSERT INTO `tbl_notifikasi` (`id_notifikasi`, `message`, `type`, `id_ref`, `id_target`, `created_at`, `is_read`) VALUES
(23, 'Pengajuan Baru dengan Judul <b>ssh</b> dengan ID kelomok 1', 'Pending', 18, 0, '2026-07-30 19:29:45', 0),
(24, 'Pengajuan Andah Dengan ID 8 telah ditinjau', 'Approved', 8, 0, '2026-08-03 11:52:44', 0),
(25, 'Pengajuan Andah Dengan ID 8 telah ditinjau', 'Approved', 8, 0, '2026-08-03 11:54:27', 0),
(26, 'Pengajuan Baru dengan Judul <b>whhw</b> dengan ID kelomok 1', 'Pending', 19, 0, '2026-08-18 11:08:20', 0),
(27, 'Pengajuan Baru dengan Judul <b>agah</b> dengan ID kelomok 1', 'Pending', 20, 0, '2026-08-18 16:46:17', 0),
(28, 'Pengajuan Andah Dengan ID 20 telah ditinjau', 'Approved', 20, 0, '2026-08-19 21:41:57', 0),
(29, 'Pengajuan Andah Dengan ID 20 telah ditinjau', 'Approved', 20, 0, '2026-08-22 14:00:39', 0),
(30, 'Pengajuan Baru dengan Judul <b>agah</b> dengan ID kelomok 1', 'Pending', 21, 0, '2026-08-22 14:46:45', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pelaksanaan`
--

CREATE TABLE `tbl_pelaksanaan` (
  `id` int(11) UNSIGNED NOT NULL,
  `step1_start` date NOT NULL DEFAULT current_timestamp(),
  `step2_start` date NOT NULL DEFAULT current_timestamp(),
  `step3_start` date NOT NULL DEFAULT current_timestamp(),
  `step4_start` date NOT NULL DEFAULT current_timestamp(),
  `step1_end` date NOT NULL DEFAULT current_timestamp(),
  `step2_end` date NOT NULL DEFAULT current_timestamp(),
  `step3_end` date NOT NULL DEFAULT current_timestamp(),
  `step4_end` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_pelaksanaan`
--

INSERT INTO `tbl_pelaksanaan` (`id`, `step1_start`, `step2_start`, `step3_start`, `step4_start`, `step1_end`, `step2_end`, `step3_end`, `step4_end`) VALUES
(1, '2026-08-17', '2026-08-24', '2026-08-31', '2026-09-02', '2026-08-20', '2026-08-30', '2026-09-01', '2026-10-07');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pengajuan`
--

CREATE TABLE `tbl_pengajuan` (
  `id_pengajuan` int(11) UNSIGNED NOT NULL,
  `id_desa` int(11) UNSIGNED DEFAULT NULL,
  `id_group` int(11) UNSIGNED DEFAULT NULL,
  `id_petani` int(11) UNSIGNED DEFAULT NULL,
  `id_bibit` int(10) UNSIGNED NOT NULL,
  `tgl_pengajuan` date NOT NULL DEFAULT current_timestamp(),
  `tgl_penyaluran` date NOT NULL DEFAULT current_timestamp(),
  `judul` varchar(100) NOT NULL,
  `file_proposal` text NOT NULL,
  `jml_bantuan` int(10) UNSIGNED NOT NULL,
  `catatan` text NOT NULL,
  `s_pengajuan` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_pengajuan`
--

INSERT INTO `tbl_pengajuan` (`id_pengajuan`, `id_desa`, `id_group`, `id_petani`, `id_bibit`, `tgl_pengajuan`, `tgl_penyaluran`, `judul`, `file_proposal`, `jml_bantuan`, `catatan`, `s_pengajuan`) VALUES
(20, NULL, 1, 2, 4, '2026-08-18', '2026-08-22', 'agah', '1787042777_6a841bd9276a3.pdf', 0, 'good', 0),
(21, NULL, 1, 2, 3, '2026-08-22', '2026-08-31', 'agah', '1787381205_6a8945d5158cc.pdf', 0, '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_petani`
--

CREATE TABLE `tbl_petani` (
  `id_petani` int(11) UNSIGNED NOT NULL,
  `id_desa` int(11) UNSIGNED DEFAULT NULL,
  `nama_petani` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `jekel` enum('L','P') NOT NULL DEFAULT 'L',
  `kontak` varchar(16) NOT NULL,
  `nik` varchar(16) NOT NULL,
  `foto` varchar(255) NOT NULL DEFAULT 'default.png',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_petani`
--

INSERT INTO `tbl_petani` (`id_petani`, `id_desa`, `nama_petani`, `alamat`, `jekel`, `kontak`, `nik`, `foto`, `created_at`) VALUES
(2, 2, 'laade', 'Dusun I', 'L', '089', '0899', 'default.png', '2026-07-26 14:42:12'),
(3, 2, 'Amhar', '', 'L', '', '', 'default.png', '2026-07-26 14:42:12'),
(4, 2, 'Amar', '', 'L', '', '', 'default.png', '2026-07-26 14:42:12'),
(5, 1, 'Vebri', '', 'L', '', '', 'default.png', '2026-07-26 14:42:12'),
(6, 1, 'Yandexzz', '', 'L', '', '', 'default.png', '2026-07-26 14:42:12'),
(14, 3, 'zali', '', 'L', '980', '', 'default.png', '2026-08-03 12:00:32');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `id_user` int(11) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `level` tinyint(1) UNSIGNED NOT NULL DEFAULT 3,
  `record` datetime DEFAULT NULL,
  `logout` datetime DEFAULT NULL,
  `s_aktif` tinyint(1) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`id_user`, `username`, `password`, `level`, `record`, `logout`, `s_aktif`) VALUES
(1, 'Admin', '$2y$10$W6gEQx1p0LU5aBqiZn16yeVshuakhsEfzqy1s4dyiVcfqahUnVpS6', 1, '2026-08-22 14:53:59', '2026-08-22 14:54:56', 1),
(2, 'Petani001', '$2y$10$tGBJbuMcBJs6lgB5n.PcPOiJ7Dynph5XVmtVDFAC9.MiMV.3Mq/JW', 3, '2026-08-22 14:55:01', '2026-08-22 14:53:53', 1),
(3, 'Petani002', '', 3, NULL, NULL, 1),
(4, 'Petani003', '', 3, NULL, NULL, 1),
(5, 'Petani004', '', 3, NULL, NULL, 1),
(6, 'Petani005', '', 3, NULL, NULL, 1),
(7, 'Petani006', '', 3, NULL, NULL, 1),
(8, 'Kadis', '$2y$10$8.CDY02tfr8hYPxs0jMB4.2dTUUh.Zw.9yepaSyDYE06M7ecX2NIK', 2, '2026-08-19 21:46:28', '2026-08-19 22:02:56', 1),
(14, 'Petani007', '$2y$10$NRdSu4uGh.KxPhjL40tsAuugCHBOqi69l8E1hvf1Qe.7GOCcFeSnO', 3, NULL, NULL, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_bibit`
--
ALTER TABLE `tbl_bibit`
  ADD PRIMARY KEY (`id_bibit`);

--
-- Indexes for table `tbl_desa`
--
ALTER TABLE `tbl_desa`
  ADD PRIMARY KEY (`id_desa`),
  ADD KEY `id_kecamatan` (`id_kecamatan`);

--
-- Indexes for table `tbl_form`
--
ALTER TABLE `tbl_form`
  ADD PRIMARY KEY (`id_form`),
  ADD KEY `id_bibit` (`id_bibit`);

--
-- Indexes for table `tbl_groups`
--
ALTER TABLE `tbl_groups`
  ADD PRIMARY KEY (`id_group`),
  ADD KEY `id_leader` (`id_leader`),
  ADD KEY `id_desa` (`id_desa`);

--
-- Indexes for table `tbl_group_details`
--
ALTER TABLE `tbl_group_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_group` (`id_group`),
  ADD KEY `id_lahan` (`id_lahan`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `tbl_kecamatan`
--
ALTER TABLE `tbl_kecamatan`
  ADD PRIMARY KEY (`id_kecamatan`);

--
-- Indexes for table `tbl_lahan`
--
ALTER TABLE `tbl_lahan`
  ADD PRIMARY KEY (`id_lahan`),
  ADD KEY `id_user` (`id_petani`),
  ADD KEY `id_group` (`id_group`),
  ADD KEY `id_desa` (`id_desa`);

--
-- Indexes for table `tbl_notifikasi`
--
ALTER TABLE `tbl_notifikasi`
  ADD PRIMARY KEY (`id_notifikasi`),
  ADD KEY `is_read` (`is_read`),
  ADD KEY `id_ref` (`id_ref`),
  ADD KEY `id_target` (`id_target`);

--
-- Indexes for table `tbl_pelaksanaan`
--
ALTER TABLE `tbl_pelaksanaan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_pengajuan`
--
ALTER TABLE `tbl_pengajuan`
  ADD PRIMARY KEY (`id_pengajuan`),
  ADD KEY `id_group` (`id_group`),
  ADD KEY `id_user` (`id_petani`),
  ADD KEY `id_desa` (`id_desa`),
  ADD KEY `s_pengajuan` (`s_pengajuan`),
  ADD KEY `id_bibit` (`id_bibit`);

--
-- Indexes for table `tbl_petani`
--
ALTER TABLE `tbl_petani`
  ADD PRIMARY KEY (`id_petani`),
  ADD KEY `id_desa` (`id_desa`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_bibit`
--
ALTER TABLE `tbl_bibit`
  MODIFY `id_bibit` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_desa`
--
ALTER TABLE `tbl_desa`
  MODIFY `id_desa` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_form`
--
ALTER TABLE `tbl_form`
  MODIFY `id_form` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_groups`
--
ALTER TABLE `tbl_groups`
  MODIFY `id_group` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_group_details`
--
ALTER TABLE `tbl_group_details`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_kecamatan`
--
ALTER TABLE `tbl_kecamatan`
  MODIFY `id_kecamatan` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_lahan`
--
ALTER TABLE `tbl_lahan`
  MODIFY `id_lahan` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_notifikasi`
--
ALTER TABLE `tbl_notifikasi`
  MODIFY `id_notifikasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `tbl_pelaksanaan`
--
ALTER TABLE `tbl_pelaksanaan`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_pengajuan`
--
ALTER TABLE `tbl_pengajuan`
  MODIFY `id_pengajuan` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tbl_petani`
--
ALTER TABLE `tbl_petani`
  MODIFY `id_petani` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id_user` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_desa`
--
ALTER TABLE `tbl_desa`
  ADD CONSTRAINT `tbl_desa_ibfk_1` FOREIGN KEY (`id_kecamatan`) REFERENCES `tbl_kecamatan` (`id_kecamatan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_form`
--
ALTER TABLE `tbl_form`
  ADD CONSTRAINT `tbl_form_ibfk_1` FOREIGN KEY (`id_bibit`) REFERENCES `tbl_bibit` (`id_bibit`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_groups`
--
ALTER TABLE `tbl_groups`
  ADD CONSTRAINT `tbl_groups_ibfk_1` FOREIGN KEY (`id_desa`) REFERENCES `tbl_desa` (`id_desa`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_groups_ibfk_2` FOREIGN KEY (`id_leader`) REFERENCES `tbl_petani` (`id_petani`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_group_details`
--
ALTER TABLE `tbl_group_details`
  ADD CONSTRAINT `tbl_group_details_ibfk_1` FOREIGN KEY (`id_group`) REFERENCES `tbl_groups` (`id_group`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_group_details_ibfk_2` FOREIGN KEY (`id_lahan`) REFERENCES `tbl_lahan` (`id_lahan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_group_details_ibfk_3` FOREIGN KEY (`id_user`) REFERENCES `tbl_petani` (`id_petani`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_lahan`
--
ALTER TABLE `tbl_lahan`
  ADD CONSTRAINT `tbl_lahan_ibfk_1` FOREIGN KEY (`id_petani`) REFERENCES `tbl_petani` (`id_petani`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_lahan_ibfk_2` FOREIGN KEY (`id_group`) REFERENCES `tbl_groups` (`id_group`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_lahan_ibfk_3` FOREIGN KEY (`id_desa`) REFERENCES `tbl_desa` (`id_desa`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_pengajuan`
--
ALTER TABLE `tbl_pengajuan`
  ADD CONSTRAINT `tbl_pengajuan_ibfk_1` FOREIGN KEY (`id_desa`) REFERENCES `tbl_desa` (`id_desa`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_pengajuan_ibfk_2` FOREIGN KEY (`id_group`) REFERENCES `tbl_groups` (`id_group`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_pengajuan_ibfk_3` FOREIGN KEY (`id_petani`) REFERENCES `tbl_petani` (`id_petani`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_pengajuan_ibfk_4` FOREIGN KEY (`id_bibit`) REFERENCES `tbl_bibit` (`id_bibit`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_petani`
--
ALTER TABLE `tbl_petani`
  ADD CONSTRAINT `tbl_petani_ibfk_1` FOREIGN KEY (`id_petani`) REFERENCES `tbl_users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_petani_ibfk_2` FOREIGN KEY (`id_desa`) REFERENCES `tbl_desa` (`id_desa`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
