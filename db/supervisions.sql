-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 27, 2025 at 04:28 PM
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
-- Database: `salama`
--

-- --------------------------------------------------------

--
-- Table structure for table `supervisions`
--

CREATE TABLE `supervisions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supervisor_id` bigint(20) UNSIGNED NOT NULL,
  `site_id` bigint(20) UNSIGNED NOT NULL,
  `started_at` timestamp NULL DEFAULT NULL,
  `ended_at` timestamp NULL DEFAULT NULL,
  `general_comment` varchar(255) DEFAULT NULL,
  `photo_debut` varchar(255) DEFAULT NULL,
  `photo_fin` varchar(255) DEFAULT NULL,
  `latlng` varchar(255) DEFAULT NULL,
  `distance` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'actif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `supervisions`
--

INSERT INTO `supervisions` (`id`, `supervisor_id`, `site_id`, `started_at`, `ended_at`, `general_comment`, `photo_debut`, `photo_fin`, `latlng`, `distance`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2025-10-09 22:20:57', '2025-10-09 22:21:37', NULL, 'http://192.168.200.9:8000/uploads/supervisions/supervision_68e8354943642.jpg', 'http://192.168.200.9:8000/uploads/supervisions/supervision_68e8357122b20.jpg', '-4.339881,15.3498927', '284', 'actif', '2025-10-09 22:20:57', '2025-10-09 22:21:37'),
(2, 1, 1, '2025-10-10 01:15:16', '2025-10-10 01:25:07', NULL, 'http://192.168.200.9:8000/uploads/supervisions/supervision_68e85e24bd937.jpg', 'http://192.168.200.9:8000/uploads/supervisions/supervision_68e860734f479.jpg', '-4.339881,15.3498927', '284', 'actif', '2025-10-10 01:15:16', '2025-10-10 01:25:07'),
(3, 1, 1, '2025-10-10 01:44:09', '2025-10-10 01:46:15', NULL, 'http://192.168.200.9:8000/uploads/supervisions/supervision_68e864e9b2f92.jpg', 'http://192.168.200.9:8000/uploads/supervisions/supervision_68e865679711b.jpg', '-4.339881,15.3498927', '284', 'actif', '2025-10-10 01:44:09', '2025-10-10 01:46:15'),
(4, 1, 1, '2025-10-10 01:50:27', '2025-10-10 01:52:16', NULL, 'http://192.168.200.9:8000/uploads/supervisions/supervision_68e866632203e.jpg', 'http://192.168.200.9:8000/uploads/supervisions/supervision_68e866d0b82c0.jpg', '-4.339631,15.3469651', '53', 'actif', '2025-10-10 01:50:27', '2025-10-10 01:52:16'),
(5, 1, 1, '2025-10-10 01:53:30', '2025-10-10 01:55:28', NULL, 'http://192.168.200.9:8000/uploads/supervisions/supervision_68e8671aeaec5.jpg', 'http://192.168.200.9:8000/uploads/supervisions/supervision_68e867909e091.jpg', '-4.339631,15.3469651', '53', 'actif', '2025-10-10 01:53:30', '2025-10-10 01:55:28'),
(6, 1, 1, '2025-10-10 01:56:07', '2025-10-10 01:56:52', NULL, 'http://192.168.200.9:8000/uploads/supervisions/supervision_68e867b769686.jpg', 'http://192.168.200.9:8000/uploads/supervisions/supervision_68e867e458c71.jpg', '-4.339881,15.3498927', '284', 'actif', '2025-10-10 01:56:07', '2025-10-10 01:56:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `supervisions`
--
ALTER TABLE `supervisions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `supervisions`
--
ALTER TABLE `supervisions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
