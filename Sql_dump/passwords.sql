-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 05, 2025 at 12:51 PM
-- Server version: 11.7.2-MariaDB
-- PHP Version: 8.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `loginweb`
--

-- --------------------------------------------------------

--
-- Table structure for table `passwords`
--

CREATE TABLE `passwords` (
  `entry_id` int(11) NOT NULL COMMENT 'Unique identifier for each vault entry',
  `user_id` int(11) NOT NULL COMMENT 'Reference to the user who owns this entry ',
  `service_name` varchar(255) DEFAULT NULL COMMENT 'Name of the service (e.g., Gmail, Amazon)',
  `login_name` varchar(255) DEFAULT NULL COMMENT 'Username for the service',
  `password_encrypted` varchar(255) DEFAULT NULL COMMENT 'Encrypted password for the service',
  `url` varchar(255) DEFAULT NULL COMMENT 'URL link to the service ',
  `notes` text DEFAULT NULL COMMENT 'Additional notes for the entry (optional)',
  `created_at` timestamp NULL DEFAULT current_timestamp() COMMENT 'Timestamp of entry creation',
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores user vault entries, including credentials for various services';

--
-- Dumping data for table `passwords`
--

INSERT INTO `passwords` (`entry_id`, `user_id`, `service_name`, `login_name`, `password_encrypted`, `url`, `notes`, `created_at`, `updated_at`) VALUES
(18, 15, 'google.com', 'dar3', '0EUZktlbl8wOEC8jOtqUNw==', NULL, 'This is my password  for google.com!', '2025-02-03 22:45:51', '2025-03-05 12:34:28'),
(19, 15, 'qwerty.com', 'daria3', 'HW1u8FxHW6tGfbp9xkJo/w==', NULL, 'Here is my password for qwerty.com', '2025-02-03 22:46:58', '2025-03-05 00:29:25'),
(21, 15, 'www.aaa.bbb', 'd3', 'HMjmYcMHvaVfNnvRosxJNw==', NULL, 'And this is the password for aaa.bbb', '2025-02-03 22:53:29', '2025-03-05 00:29:40'),
(22, 16, 'www.nescol.ac.uk', 'daria4', 'r0maFeon3xPs2fBRA95L5w==', NULL, 'This is my password for college', '2025-02-03 23:00:07', '2025-03-05 00:30:05'),
(23, 16, 'abc.com', 'd4', '/nHrSBDOF3j1+b7hX9oTTQ==', NULL, 'Classified!! This is my password for abc.com', '2025-02-03 23:00:44', '2025-03-05 00:30:44'),
(25, 15, 'www.nescol.ac.uk', 'd3334', 'WCXT4E2INF+ljzDboBsr9g==', NULL, 'This is my comment about password to nescol website', '2025-02-26 10:49:27', '2025-03-05 00:31:05');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `passwords`
--
ALTER TABLE `passwords`
  ADD PRIMARY KEY (`entry_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `passwords`
--
ALTER TABLE `passwords`
  MODIFY `entry_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for each vault entry', AUTO_INCREMENT=28;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `passwords`
--
ALTER TABLE `passwords`
  ADD CONSTRAINT `passwords_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
