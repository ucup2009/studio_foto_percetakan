-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20250914.f72491a1c0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 23, 2026 at 05:39 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `oppasstudio`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `id_booking` int NOT NULL,
  `id_user` int NOT NULL,
  `id_paket` int NOT NULL,
  `id_fotografer` int DEFAULT NULL,
  `tanggal` date NOT NULL,
  `jam` time NOT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `catatan` text,
  `status` enum('menunggu','dikonfirmasi','selesai') DEFAULT 'menunggu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`id_booking`, `id_user`, `id_paket`, `id_fotografer`, `tanggal`, `jam`, `lokasi`, `catatan`, `status`) VALUES
(31, 10, 1, 2, '2026-05-05', '13:36:00', '', 'df', 'menunggu'),
(33, 10, 5, 3, '2026-06-01', '20:40:00', '', 'cdv dvdv', 'menunggu');

-- --------------------------------------------------------

--
-- Table structure for table `fotografer`
--

CREATE TABLE `fotografer` (
  `id_fotografer` int NOT NULL,
  `id_user` int NOT NULL,
  `spesialisasi` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `fotografer`
--

INSERT INTO `fotografer` (`id_fotografer`, `id_user`, `spesialisasi`) VALUES
(2, 11, 'studio'),
(3, 12, 'detgvrew tg ');

-- --------------------------------------------------------

--
-- Table structure for table `galeri`
--

CREATE TABLE `galeri` (
  `id_galeri` int NOT NULL,
  `judul` varchar(100) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `deskripsi` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `galeri`
--

INSERT INTO `galeri` (`id_galeri`, `judul`, `foto`, `kategori`, `deskripsi`, `created_at`) VALUES
(15, 'Untitled Asset', '6a0d420e39eb3.jpg', 'portrait', '', '2026-05-20 05:09:34'),
(16, 'Untitled Asset', '6a0d421ddae10.jpg', 'fashion', '', '2026-05-20 05:09:49'),
(17, 'Untitled Asset', '6a0d422d16afb.jpg', 'portrait', '', '2026-05-20 05:10:05'),
(18, 'Untitled Asset', '6a0d51c0d3b98.jpg', 'fashion', '', '2026-05-20 06:16:32'),
(19, 'Untitled Asset', '6a0d530c0f545.jpg', 'fashion', '', '2026-05-20 06:22:04'),
(20, 'Untitled Asset', '6a0d5319851e2.jpg', 'fashion', '', '2026-05-20 06:22:17'),
(21, 'Untitled Asset', '6a0d533124e3b.jpg', 'portrait', '', '2026-05-20 06:22:41');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal`
--

CREATE TABLE `jadwal` (
  `id_jadwal` int NOT NULL,
  `id_booking` int NOT NULL,
  `id_fotografer` int NOT NULL,
  `tanggal` date DEFAULT NULL,
  `jam` time DEFAULT NULL,
  `status` enum('terjadwal','selesai') DEFAULT 'terjadwal'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jadwal`
--

INSERT INTO `jadwal` (`id_jadwal`, `id_booking`, `id_fotografer`, `tanggal`, `jam`, `status`) VALUES
(9, 31, 2, '2026-05-05', '13:36:00', 'terjadwal'),
(11, 33, 3, '2026-06-01', '20:40:00', 'terjadwal');

-- --------------------------------------------------------

--
-- Table structure for table `layanan_cetak`
--

CREATE TABLE `layanan_cetak` (
  `id_layanan` int NOT NULL,
  `nama_layanan` varchar(100) NOT NULL,
  `harga` int NOT NULL,
  `deskripsi` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `layanan_cetak`
--

INSERT INTO `layanan_cetak` (`id_layanan`, `nama_layanan`, `harga`, `deskripsi`) VALUES
(1, 'Cetak Foto 12R', 50000, 'Cetak kualitas tinggi bingkai kayu'),
(2, 'Makeup Artist (MUA)', 350000, 'Makeup natural untuk sesi foto'),
(3, 'Tambah Background', 75000, 'Ganti latar belakang digital'),
(4, 'Sewa Kostum Adat', 150000, 'Pilihan baju adat nusantara');

-- --------------------------------------------------------

--
-- Table structure for table `paket`
--

CREATE TABLE `paket` (
  `id_paket` int NOT NULL,
  `nama_paket` varchar(100) NOT NULL,
  `harga` int NOT NULL,
  `deskripsi` text,
  `durasi` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status_paket` enum('aktif','nonaktif') DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `paket`
--

INSERT INTO `paket` (`id_paket`, `nama_paket`, `harga`, `deskripsi`, `durasi`, `created_at`, `status_paket`) VALUES
(1, 'foto pernikahan', 20000, 'fevfvfvfv', '55', '2026-04-20 03:22:23', 'nonaktif'),
(2, 'Foto Prewedding', 1500000, 'Paket outdoor 2 lokasi, termasuk makeup.', '300', '2026-04-21 01:00:00', 'aktif'),
(3, 'Foto Wisuda', 500000, 'Sesi studio maksimal 5 orang + 1 foto cetak 12R.', '60', '2026-04-21 02:30:00', 'aktif'),
(4, 'Paket Cinematic', 3000000, 'Video highlight durasi 3 menit resolusi 4K.', '480', '2026-04-21 04:15:00', 'aktif'),
(5, 'Foto Produk', 250000, 'Sesi foto katalog produk, harga per 5 item.', '90', '2026-04-21 06:00:00', 'aktif'),
(6, 'Dokumentasi Event', 2000000, 'Liputan foto dan video acara ulang tahun/seminar.', '360', '2026-04-21 07:45:00', 'aktif');

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int NOT NULL,
  `id_booking` int DEFAULT NULL,
  `id_pesanan` int DEFAULT NULL,
  `metode` varchar(50) DEFAULT NULL,
  `total` int DEFAULT NULL,
  `status` enum('belum bayar','sudah bayar') DEFAULT 'belum bayar',
  `tanggal_bayar` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pesanan_cetak`
--

CREATE TABLE `pesanan_cetak` (
  `id_pesanan` int NOT NULL,
  `id_user` int NOT NULL,
  `id_layanan` int NOT NULL,
  `jumlah` int DEFAULT '1',
  `file_desain` varchar(255) DEFAULT NULL,
  `catatan` text,
  `tanggal_pesan` date DEFAULT NULL,
  `status` enum('menunggu','diproses','selesai') DEFAULT 'menunggu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pesanan_cetak`
--

INSERT INTO `pesanan_cetak` (`id_pesanan`, `id_user`, `id_layanan`, `jumlah`, `file_desain`, `catatan`, `tanggal_pesan`, `status`) VALUES
(2, 2, 1, 1, '6a0bae69a36b0.JPG', 'wgthgtrhgt', '2026-05-19', 'selesai'),
(18, 1, 1, 1, '6a0d50a34a03b.JPG', 'fgreg', '2026-05-20', 'selesai');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `role` enum('admin','fotografer','pelanggan') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `nama`, `email`, `password`, `no_hp`, `role`, `created_at`) VALUES
(1, 'yusuf', 'yusufaray81@gmail.com', 'admin123', '4364758', 'admin', '2026-04-04 03:28:18'),
(2, 'acho', 'acho@gmail.com', 'aco123', '675467', 'pelanggan', '2026-04-20 01:19:46'),
(8, 'yusuf', 'yusuf@gmail.com', '12345678', '081337470942', 'pelanggan', '2026-04-25 00:49:14'),
(10, 'akbar', 'akbar@gmail.com', 'akbar123', '6281267047497', 'pelanggan', '2026-05-19 01:03:11'),
(11, 'rohan', 'rohan@gmail.com', 'rohan123', '6281267047497', 'fotografer', '2026-05-20 23:46:17'),
(12, 'muhaimin', 'imin@gmail.com', 'imin123', '535565', 'fotografer', '2026-05-23 02:05:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id_booking`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_paket` (`id_paket`);

--
-- Indexes for table `fotografer`
--
ALTER TABLE `fotografer`
  ADD PRIMARY KEY (`id_fotografer`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `galeri`
--
ALTER TABLE `galeri`
  ADD PRIMARY KEY (`id_galeri`);

--
-- Indexes for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id_jadwal`),
  ADD KEY `id_booking` (`id_booking`),
  ADD KEY `id_fotografer` (`id_fotografer`);

--
-- Indexes for table `layanan_cetak`
--
ALTER TABLE `layanan_cetak`
  ADD PRIMARY KEY (`id_layanan`);

--
-- Indexes for table `paket`
--
ALTER TABLE `paket`
  ADD PRIMARY KEY (`id_paket`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `id_booking` (`id_booking`),
  ADD KEY `id_pesanan` (`id_pesanan`);

--
-- Indexes for table `pesanan_cetak`
--
ALTER TABLE `pesanan_cetak`
  ADD PRIMARY KEY (`id_pesanan`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_layanan` (`id_layanan`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `id_booking` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `fotografer`
--
ALTER TABLE `fotografer`
  MODIFY `id_fotografer` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `galeri`
--
ALTER TABLE `galeri`
  MODIFY `id_galeri` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id_jadwal` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `layanan_cetak`
--
ALTER TABLE `layanan_cetak`
  MODIFY `id_layanan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `paket`
--
ALTER TABLE `paket`
  MODIFY `id_paket` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pesanan_cetak`
--
ALTER TABLE `pesanan_cetak`
  MODIFY `id_pesanan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`id_paket`) REFERENCES `paket` (`id_paket`);

--
-- Constraints for table `fotografer`
--
ALTER TABLE `fotografer`
  ADD CONSTRAINT `fotografer_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Constraints for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD CONSTRAINT `jadwal_ibfk_1` FOREIGN KEY (`id_booking`) REFERENCES `booking` (`id_booking`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `jadwal_ibfk_2` FOREIGN KEY (`id_fotografer`) REFERENCES `fotografer` (`id_fotografer`);

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`id_booking`) REFERENCES `booking` (`id_booking`),
  ADD CONSTRAINT `pembayaran_ibfk_2` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan_cetak` (`id_pesanan`);

--
-- Constraints for table `pesanan_cetak`
--
ALTER TABLE `pesanan_cetak`
  ADD CONSTRAINT `pesanan_cetak_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `pesanan_cetak_ibfk_2` FOREIGN KEY (`id_layanan`) REFERENCES `layanan_cetak` (`id_layanan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
