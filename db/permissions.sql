-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 27, 2025 at 04:29 PM
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
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'patrouilles.view', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(2, 'patrouilles.create', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(3, 'patrouilles.edit', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(4, 'patrouilles.delete', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(5, 'patrouilles.export', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(6, 'patrouilles.import', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(7, 'rapports.view', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(8, 'rapports.create', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(9, 'rapports.edit', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(10, 'rapports.delete', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(11, 'rapports.export', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(12, 'rapports.import', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(13, 'sites.view', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(14, 'sites.create', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(15, 'sites.edit', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(16, 'sites.delete', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(17, 'sites.export', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(18, 'sites.import', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(19, 'agents.view', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(20, 'agents.create', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(21, 'agents.edit', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(22, 'agents.delete', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(23, 'agents.export', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(24, 'agents.import', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(25, 'presences.view', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(26, 'presences.create', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(27, 'presences.edit', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(28, 'presences.delete', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(29, 'presences.export', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(30, 'presences.import', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(31, 'requetes.view', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(32, 'requetes.create', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(33, 'requetes.edit', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(34, 'requetes.delete', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(35, 'requetes.export', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(36, 'requetes.import', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(37, 'planning.view', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(38, 'planning.create', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(39, 'planning.edit', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(40, 'planning.delete', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(41, 'planning.export', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(42, 'planning.import', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(43, 'rh.view', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(44, 'rh.create', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(45, 'rh.edit', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(46, 'rh.delete', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(47, 'rh.export', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(48, 'rh.import', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(49, 'communiques.view', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(50, 'communiques.create', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(51, 'communiques.edit', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(52, 'communiques.delete', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(53, 'communiques.export', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(54, 'communiques.import', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(55, 'signalements.view', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(56, 'signalements.create', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(57, 'signalements.edit', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(58, 'signalements.delete', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(59, 'signalements.export', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(60, 'signalements.import', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(61, 'configurations.view', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(62, 'configurations.create', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(63, 'configurations.edit', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(64, 'configurations.delete', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(65, 'configurations.export', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(66, 'configurations.import', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(67, 'utilisateurs.view', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(68, 'utilisateurs.create', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(69, 'utilisateurs.edit', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(70, 'utilisateurs.delete', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(71, 'utilisateurs.export', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(72, 'utilisateurs.import', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(73, 'logs.view', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(74, 'logs.create', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(75, 'logs.edit', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(76, 'logs.delete', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(77, 'logs.export', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04'),
(78, 'logs.import', 'web', '2025-10-06 23:35:04', '2025-10-06 23:35:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
