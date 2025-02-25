-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 25, 2025 at 01:30 PM
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
-- Database: `loginweb`
--

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `release_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `media`
--

INSERT INTO `media` (`id`, `title`, `release_date`, `created_at`, `username`) VALUES
(1, 'The Baby in the Basket', '2025-02-17', '2025-02-17 20:06:08', 'shona'),
(2, 'Spermageddon', '2025-02-21', '2025-02-17 20:31:09', 'shona'),
(3, 'Cleaner', '2025-02-19', '2025-02-17 20:31:12', 'shona'),
(4, 'A Minecraft Movie', '2025-04-02', '2025-02-19 10:30:21', 'shona');

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `title` varchar(120) NOT NULL,
  `content` varchar(1000) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `noteID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`title`, `content`, `timestamp`, `noteID`) VALUES
('testing the notes', 'TESTING NOTES', '2025-01-20 14:40:21', 1),
('DSD', 'FSFSDFS', '2025-01-20 14:48:28', 2),
('testing auto timestamp', 'testing the timestamp', '2025-01-27 10:24:31', 3),
('another note', 'one more note', '2025-01-27 10:36:10', 4),
('new notes', 'new note', '2025-01-27 10:40:24', 5),
('save note test', 'save test', '2025-01-27 10:41:46', 6);

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
(18, 15, 'google.com', 'dar3', '4oW8GWxbAVL31+bAwjbG/g==', NULL, NULL, '2025-02-03 22:45:51', '2025-02-03 22:45:51'),
(19, 15, 'qwerty.com', 'daria3', 'HW1u8FxHW6tGfbp9xkJo/w==', NULL, NULL, '2025-02-03 22:46:58', '2025-02-03 22:46:58'),
(21, 15, 'www.aaa.bbb', 'd3', 'DwZdm9lMkqWnoyORRXT+cw==', NULL, NULL, '2025-02-03 22:53:29', '2025-02-03 22:53:29'),
(22, 16, 'www.nescol.ac.uk', 'daria4', 'r0maFeon3xPs2fBRA95L5w==', NULL, NULL, '2025-02-03 23:00:07', '2025-02-03 23:00:07'),
(23, 16, 'abc.com', 'd4', '/nHrSBDOF3j1+b7hX9oTTQ==', NULL, NULL, '2025-02-03 23:00:44', '2025-02-03 23:00:44');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `taskID` int(11) NOT NULL,
  `task` varchar(100) DEFAULT NULL,
  `details` varchar(255) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `dueDate` date DEFAULT NULL,
  `saveDate` date DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`taskID`, `task`, `details`, `username`, `dueDate`, `saveDate`) VALUES
(1, 'test task', 'a new task', '', '2025-02-01', '2025-01-27'),
(2, 'tasks add january', '', '', '2025-01-17', '2025-01-27'),
(3, 'another one', 'asdas', '', '2025-01-30', '2025-01-27'),
(4, 'todays task', 'add stuff to dash', 'shona', '2025-02-11', '2025-02-11'),
(5, 'todays task', 'add extra stuff', 'shona', '2025-02-12', '2025-02-11'),
(6, 'work on the website', '', 'shona', '2025-02-11', '2025-02-11'),
(7, 'team meeting', 'get ready for the meeting', 'shona', '2025-02-19', '2025-02-19');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password_hash`, `email`) VALUES
(1, 'john_doe', '$2y$10$eW5CZLpGb63C2vvz0S0bWuv9rf1B8lks5XzKL0cPS3I/9FyJ4Wo5a', 'john@example.com'),
(2, 'jane_smith', '$2y$10$2fvjRf3/LNOC/EYIEo9YuO3Q.EGyhiuM1lzQ/E2ms9jfhYXAs7P.i', 'jane@example.com'),
(3, 'admin_user', '$2y$10$Xp/6PCJj5SnZnT8ZzUwGVeW.DiSnCnZ1MS/tdVghbN/M1F2gHkncO', 'admin@example.com'),
(4, 'guest_user', '$2y$10$nH3kWfKZx9XpOBESZZ9v3OTlH2gEbJfJxyOZIq9bVf2k1xE7efUQG', 'guest@example.com'),
(5, 'testuser', '$2y$10$i.X1yfsE6IK5Vs7Uf.TtSO9l0BwcN/gmddAYptHskjF/cmtr.Wp2K', 'testuser@gmail.com'),
(7, 'testuser2', '$2y$10$EsKk/VwdEoSfp9Q2Gyxol.7.h5Mv1869l4fEuUi5VxUq3ySqTHc6q', 'testuser2@gmail.com'),
(8, 'bob', '$2y$10$YQ8uAN65yfh1K41ni9sj5.aNuwHeMJl703ajc4WHcPIgIBLgsGk4e', 'bongo@bongo.com'),
(9, 'testusernew', '$2y$10$.wPM1M9rLmgkWC/946PSr.9MPPb1C7mxQBF5N3vkXZe.qNqAFJdWK', 'test@test.com'),
(11, 'blue', '$2y$10$fOQUBLeHfBVZ/Fp6wkTkHOHaJ0K8051IzOA5EN/md0xpTqZqBv62m', 'steel@steel.com'),
(12, 'shona', '$2y$10$bc2njZu1ql630GNMoUKaZ.328vD9xHFoJsOH7PVYYV9WQQ/X1KTJO', 'shona@shona.com'),
(13, 'daria', '$2y$10$0JIvzyOWEZP3jphNeVZXv.hz0NJEq94MlHkZQ.bI5EV7NEO/xzWYu', 'daria@qwerty.com'),
(14, 'daria2', '$2y$10$IR1PpUGFw7SD6ZkEL3evQuKMCK/qsr54mrDr1185IZyM1sOEY8hrW', 'daria2@qwerty.com'),
(15, 'daria3', '$2y$10$lh7wqKTpw5P8Qsth68MbyOuGVBxgQyc3lTVeYZY.4XtIbXDJSYJwq', 'daria3@qwerty.com'),
(16, 'daria4', '$2y$10$07ZOxsSkp6MZftxu2/WzK./6rYT5wAyz/.bG2JNfjhmfYTmaHrwNm', 'daria4@qwerty.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`noteID`);

--
-- Indexes for table `passwords`
--
ALTER TABLE `passwords`
  ADD PRIMARY KEY (`entry_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`taskID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `noteID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `passwords`
--
ALTER TABLE `passwords`
  MODIFY `entry_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for each vault entry', AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `taskID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

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
