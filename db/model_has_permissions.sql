-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 27, 2025 at 04:30 PM
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
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_permissions`
--

INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 3),
(1, 'App\\Models\\User', 6),
(2, 'App\\Models\\User', 3),
(3, 'App\\Models\\User', 3),
(4, 'App\\Models\\User', 3),
(5, 'App\\Models\\User', 3),
(6, 'App\\Models\\User', 3),
(7, 'App\\Models\\User', 3),
(7, 'App\\Models\\User', 6),
(8, 'App\\Models\\User', 3),
(9, 'App\\Models\\User', 3),
(10, 'App\\Models\\User', 3),
(11, 'App\\Models\\User', 3),
(12, 'App\\Models\\User', 3),
(13, 'App\\Models\\User', 3),
(14, 'App\\Models\\User', 3),
(15, 'App\\Models\\User', 3),
(16, 'App\\Models\\User', 3),
(17, 'App\\Models\\User', 3),
(18, 'App\\Models\\User', 3),
(19, 'App\\Models\\User', 3),
(19, 'App\\Models\\User', 6),
(20, 'App\\Models\\User', 3),
(20, 'App\\Models\\User', 6),
(21, 'App\\Models\\User', 3),
(21, 'App\\Models\\User', 6),
(22, 'App\\Models\\User', 3),
(23, 'App\\Models\\User', 3),
(24, 'App\\Models\\User', 3),
(24, 'App\\Models\\User', 6),
(25, 'App\\Models\\User', 3),
(25, 'App\\Models\\User', 6),
(26, 'App\\Models\\User', 3),
(27, 'App\\Models\\User', 3),
(28, 'App\\Models\\User', 3),
(29, 'App\\Models\\User', 3),
(30, 'App\\Models\\User', 3),
(31, 'App\\Models\\User', 3),
(32, 'App\\Models\\User', 3),
(33, 'App\\Models\\User', 3),
(34, 'App\\Models\\User', 3),
(35, 'App\\Models\\User', 3),
(36, 'App\\Models\\User', 3),
(37, 'App\\Models\\User', 3),
(38, 'App\\Models\\User', 3),
(39, 'App\\Models\\User', 3),
(40, 'App\\Models\\User', 3),
(41, 'App\\Models\\User', 3),
(42, 'App\\Models\\User', 3),
(43, 'App\\Models\\User', 3),
(44, 'App\\Models\\User', 3),
(45, 'App\\Models\\User', 3),
(46, 'App\\Models\\User', 3),
(47, 'App\\Models\\User', 3),
(48, 'App\\Models\\User', 3),
(49, 'App\\Models\\User', 3),
(50, 'App\\Models\\User', 3),
(51, 'App\\Models\\User', 3),
(52, 'App\\Models\\User', 3),
(53, 'App\\Models\\User', 3),
(54, 'App\\Models\\User', 3),
(55, 'App\\Models\\User', 3),
(56, 'App\\Models\\User', 3),
(57, 'App\\Models\\User', 3),
(58, 'App\\Models\\User', 3),
(59, 'App\\Models\\User', 3),
(60, 'App\\Models\\User', 3),
(61, 'App\\Models\\User', 3),
(62, 'App\\Models\\User', 3),
(63, 'App\\Models\\User', 3),
(64, 'App\\Models\\User', 3),
(65, 'App\\Models\\User', 3),
(66, 'App\\Models\\User', 3),
(67, 'App\\Models\\User', 3),
(68, 'App\\Models\\User', 3),
(69, 'App\\Models\\User', 3),
(70, 'App\\Models\\User', 3),
(71, 'App\\Models\\User', 3),
(72, 'App\\Models\\User', 3),
(73, 'App\\Models\\User', 3),
(74, 'App\\Models\\User', 3),
(75, 'App\\Models\\User', 3),
(76, 'App\\Models\\User', 3),
(77, 'App\\Models\\User', 3),
(78, 'App\\Models\\User', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
