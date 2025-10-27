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
-- Table structure for table `supervision_agents`
--

CREATE TABLE `supervision_agents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supervision_id` bigint(20) UNSIGNED NOT NULL,
  `agent_id` bigint(20) UNSIGNED NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `supervision_agents`
--

INSERT INTO `supervision_agents` (`id`, `supervision_id`, `agent_id`, `photo`, `comment`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 'http://192.168.200.9:8000/uploads/supervisions/agents/agent_68e83571240f5.jpg', NULL, '2025-10-09 22:21:37', '2025-10-09 22:21:37'),
(2, 1, 2, 'http://192.168.200.9:8000/uploads/supervisions/agents/agent_68e8357127ab2.jpg', NULL, '2025-10-09 22:21:37', '2025-10-09 22:21:37'),
(3, 2, 3, 'http://192.168.200.9:8000/uploads/supervisions/agents/agent_68e8605656d45.jpg', NULL, '2025-10-10 01:24:38', '2025-10-10 01:24:38'),
(4, 2, 2, 'http://192.168.200.9:8000/uploads/supervisions/agents/agent_68e860565a959.jpg', NULL, '2025-10-10 01:24:38', '2025-10-10 01:24:38'),
(5, 2, 3, 'http://192.168.200.9:8000/uploads/supervisions/agents/agent_68e8607350647.jpg', NULL, '2025-10-10 01:25:07', '2025-10-10 01:25:07'),
(6, 2, 2, 'http://192.168.200.9:8000/uploads/supervisions/agents/agent_68e8607352519.jpg', NULL, '2025-10-10 01:25:07', '2025-10-10 01:25:07'),
(7, 3, 3, 'http://192.168.200.9:8000/uploads/supervisions/agents/agent_68e8656798656.jpg', NULL, '2025-10-10 01:46:15', '2025-10-10 01:46:15'),
(8, 3, 2, 'http://192.168.200.9:8000/uploads/supervisions/agents/agent_68e865679ba45.jpg', NULL, '2025-10-10 01:46:15', '2025-10-10 01:46:15'),
(9, 4, 3, 'http://192.168.200.9:8000/uploads/supervisions/agents/agent_68e866d0ba5ba.jpg', NULL, '2025-10-10 01:52:16', '2025-10-10 01:52:16'),
(10, 4, 2, 'http://192.168.200.9:8000/uploads/supervisions/agents/agent_68e866d0bd5b1.jpg', NULL, '2025-10-10 01:52:16', '2025-10-10 01:52:16'),
(11, 5, 3, 'http://192.168.200.9:8000/uploads/supervisions/agents/agent_68e867909f23f.jpg', NULL, '2025-10-10 01:55:28', '2025-10-10 01:55:28'),
(12, 5, 2, 'http://192.168.200.9:8000/uploads/supervisions/agents/agent_68e86790a15b9.jpg', NULL, '2025-10-10 01:55:28', '2025-10-10 01:55:28'),
(13, 6, 3, 'http://192.168.200.9:8000/uploads/supervisions/agents/agent_68e867e459a5c.jpg', NULL, '2025-10-10 01:56:52', '2025-10-10 01:56:52'),
(14, 6, 2, 'http://192.168.200.9:8000/uploads/supervisions/agents/agent_68e867e45bf85.jpg', NULL, '2025-10-10 01:56:52', '2025-10-10 01:56:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `supervision_agents`
--
ALTER TABLE `supervision_agents`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `supervision_agents`
--
ALTER TABLE `supervision_agents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
