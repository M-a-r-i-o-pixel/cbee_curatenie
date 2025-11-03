-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql200.infinityfree.com
-- Generation Time: Nov 03, 2025 at 05:55 AM
-- Server version: 11.4.7-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_40320594_curatienie_cbee`
--

-- --------------------------------------------------------

--
-- Table structure for table `schimburi`
--

CREATE TABLE `schimburi` (
  `id` int(11) NOT NULL,
  `from_user_id` int(11) NOT NULL,
  `to_user_id` int(11) NOT NULL,
  `status` enum('pending','accepted','declined') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(100) NOT NULL,
  `nume_complet` varchar(100) NOT NULL,
  `parola` varchar(100) NOT NULL,
  `data` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nume_complet`, `parola`, `data`) VALUES
(7, 'Bulzan Timotei', '$2y$10$o.jYwrhBW/b/VOqylYh1MO/QeMaL0184rcxG27J7giMqGfIsLHwaW', '2025-11-05'),
(6, 'Crucinina Alexandra', '$2y$10$lmqcFWPXEOk4.bdtoYOKqekvf3C3Dv0VUY4tRVRCS47.onFgSbF1e', '2025-11-19'),
(5, 'Hutu Alexandru Mario', '$2y$10$bn5D6I2VX2Ietw5knKvqmupBFIxigWTJfHZetUGvWI6oGNyG.0t.G', '2025-11-04'),
(8, 'bodearm@gmail.com', '$2y$10$Y0rgnElW5GpMIHSCKlcr5eNldZsida78tDnHMUuf/Yk0CW4QifkMC', '2025-11-16'),
(9, 'Hjggfgf', '$2y$10$wLOfTjddYFfMJ5N16aDGw.Ois0Zputi3Bl5xFFZY4as4jxLHFv3j2', '2025-11-26'),
(10, 'Bodea Roxana', '$2y$10$4fv5XvF705snPHtnKsTq9OF52m7BObEVGLyUhIZoQ9jm8/Da.8wbm', '2025-11-08'),
(11, 'Sara Daniela', '$2y$10$O53KKZMRQL765SXzITnx1eFfvVHnGDMmFQNwnaX1GleSgZeX.yPmS', '2025-11-20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `schimburi`
--
ALTER TABLE `schimburi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `from_user_id` (`from_user_id`),
  ADD KEY `to_user_id` (`to_user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `schimburi`
--
ALTER TABLE `schimburi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
