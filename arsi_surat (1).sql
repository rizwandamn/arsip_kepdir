-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 16, 2024 at 08:51 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `arsi_surat`
--

-- --------------------------------------------------------

--
-- Table structure for table `dokumen`
--

CREATE TABLE `dokumen` (
  `id_dokumen` int NOT NULL,
  `title` varchar(100) NOT NULL,
  `deskripsi` text NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `kategori` enum('pendidikan','penelitian','pengabdian','lainnya') NOT NULL,
  `jenis` enum('surat_keputusan','surat_tugas') NOT NULL,
  `no_surat` varchar(100) NOT NULL,
  `tanggal_surat` date NOT NULL,
  `tahun_akademik` enum('ganjil','genap') NOT NULL,
  `uploaded_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_marked` tinyint DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `dokumen`
--

INSERT INTO `dokumen` (`id_dokumen`, `title`, `deskripsi`, `file_path`, `kategori`, `jenis`, `no_surat`, `tanggal_surat`, `tahun_akademik`, `uploaded_by`, `created_at`, `updated_at`, `is_marked`) VALUES
(2, 'a', 'b', 'uploads/RAPAT UMUM DOP.pdf', 'pendidikan', 'surat_tugas', '2', '2024-10-09', 'ganjil', 3, '2024-10-09 15:05:36', '2024-10-09 17:32:19', 1),
(4, 'b', 'c', 'uploads/UTS Etika Profesi 5C-2024-2025.pdf', 'penelitian', 'surat_keputusan', '3', '2024-10-14', 'ganjil', 3, '2024-10-09 18:01:37', '2024-10-14 13:30:53', 0);

-- --------------------------------------------------------

--
-- Table structure for table `marked_dokumen`
--

CREATE TABLE `marked_dokumen` (
  `id_tandai` int NOT NULL,
  `id_user` int DEFAULT NULL,
  `id_dokumen` int DEFAULT NULL,
  `marked_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `marked_dokumen`
--

INSERT INTO `marked_dokumen` (`id_tandai`, `id_user`, `id_dokumen`, `marked_at`) VALUES
(18, 4, 4, '2024-10-14 16:57:50');

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id_user` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','dosen') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id_user`, `username`, `password`, `role`) VALUES
(1, 'admin1', 'password_admin', 'admin'),
(2, 'dosen1', 'password_dosen', 'dosen'),
(3, 'admin2', '$2y$10$/mAc.uLzz6o8VY7XcCPy0OYBZVZEZ/RbrGoYLL0G9gsSX0n7L7HXW', 'admin'),
(4, 'dosen2', '$2y$10$B1LkhrA0USfP6VtPyQxvy.WuZmdBH8t1i0qXT0I7NKIc/fO7T2Ama', 'dosen');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dokumen`
--
ALTER TABLE `dokumen`
  ADD PRIMARY KEY (`id_dokumen`),
  ADD KEY `fk_uploaded_by` (`uploaded_by`);

--
-- Indexes for table `marked_dokumen`
--
ALTER TABLE `marked_dokumen`
  ADD PRIMARY KEY (`id_tandai`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dokumen`
--
ALTER TABLE `dokumen`
  MODIFY `id_dokumen` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `marked_dokumen`
--
ALTER TABLE `marked_dokumen`
  MODIFY `id_tandai` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dokumen`
--
ALTER TABLE `dokumen`
  ADD CONSTRAINT `fk_uploaded_by` FOREIGN KEY (`uploaded_by`) REFERENCES `pengguna` (`id_user`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
