-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 30, 2024 at 05:29 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `demo`
--

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `log` text NOT NULL,
  `datetime` text NOT NULL,
  `token` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `log`, `datetime`, `token`) VALUES
(1, 'Sample log message', '2024-07-30 12:00:00', '3b408cb48548b5037822c10eb0976b3cbf2cee3bf9c708796bf03941fbecd80f'),
(2, 'Sample log message', '2024-07-30 12:00:00', '3b408cb48548b5037822c10eb0976b3cbf2cee3bf9c708796bf03941fbecd80f'),
(3, 'Sample log message', '2024-07-30 12:00:00', '3b408cb48548b5037822c10eb0976b3cbf2cee3bf9c708796bf03941fbecd80f'),
(4, 'Sample log message', '2024-07-30 12:00:00', '3b408cb48548b5037822c10eb0976b3cbf2cee3bf9c708796bf03941fbecd80f'),
(5, 'User admin logged in successfully.', '2024-07-30 08:16:46', '3b408cb48548b5037822c10eb0976b3cbf2cee3bf9c708796bf03941fbecd80f'),
(6, 'User admin logged in successfully.', '2024-07-30 08:19:16', '3b408cb48548b5037822c10eb0976b3cbf2cee3bf9c708796bf03941fbecd80f'),
(7, 'User admin logged in successfully.', '2024-07-30 08:19:55', '3b408cb48548b5037822c10eb0976b3cbf2cee3bf9c708796bf03941fbecd80f'),
(8, 'User admin logged in successfully.', '2024-07-30 08:21:56', '3b408cb48548b5037822c10eb0976b3cbf2cee3bf9c708796bf03941fbecd80f'),
(9, 'User admin logged in successfully.', '2024-07-30 08:23:29', '3b408cb48548b5037822c10eb0976b3cbf2cee3bf9c708796bf03941fbecd80f'),
(10, 'User admin logged in successfully.', '2024-07-30 08:24:17', '3b408cb48548b5037822c10eb0976b3cbf2cee3bf9c708796bf03941fbecd80f'),
(11, 'User admin logged in successfully.', '2024-07-30 08:26:34', '3b408cb48548b5037822c10eb0976b3cbf2cee3bf9c708796bf03941fbecd80f'),
(12, 'User admin logged in successfully.', '2024-07-30 08:27:24', '3b408cb48548b5037822c10eb0976b3cbf2cee3bf9c708796bf03941fbecd80f');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `from` int(11) NOT NULL,
  `to` int(11) NOT NULL,
  `type` text NOT NULL,
  `amount` int(11) NOT NULL,
  `datetime` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `from`, `to`, `type`, `amount`, `datetime`) VALUES
(2, 1, 2, 'transfer', 100, '2024-07-30 12:00:00'),
(3, 1, 2, 'transfer', 100, '2024-07-30 12:00:00'),
(4, 1, 2, 'transfer', 100, '2024-07-30 12:00:00'),
(7, 22, 26, 'debit', 5000, '2024-07-30 08:09:32');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` text NOT NULL,
  `token` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `token`) VALUES
(2, 'John Doe1', '3b408cb48548b5037822c10eb0976b3cbf2cee3bf9c708796bf03941fbecd80f'),
(15, 'momer', 'f3755b1a1cf6264b5defc03e870acacb41fd71f24f76ad5865bda6fb4da196d8');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
