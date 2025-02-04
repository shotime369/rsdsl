/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.6.2-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: loginweb
-- ------------------------------------------------------
-- Server version	11.6.2-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `notes`
--

DROP TABLE IF EXISTS `notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notes` (
  `title` varchar(120) NOT NULL,
  `content` varchar(1000) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `noteID` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`noteID`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notes`
--

LOCK TABLES `notes` WRITE;
/*!40000 ALTER TABLE `notes` DISABLE KEYS */;
INSERT INTO `notes` VALUES
('testing the notes','TESTING NOTES','2025-01-20 14:40:21',1),
('DSD','FSFSDFS','2025-01-20 14:48:28',2),
('testing auto timestamp','testing the timestamp','2025-01-27 10:24:31',3),
('another note','one more note','2025-01-27 10:36:10',4),
('new notes','new note','2025-01-27 10:40:24',5),
('save note test','save test','2025-01-27 10:41:46',6);
/*!40000 ALTER TABLE `notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `passwords`
--

DROP TABLE IF EXISTS `passwords`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `passwords` (
  `entry_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for each vault entry',
  `user_id` int(11) NOT NULL COMMENT 'Reference to the user who owns this entry ',
  `service_name` varchar(255) DEFAULT NULL COMMENT 'Name of the service (e.g., Gmail, Amazon)',
  `login_name` varchar(255) DEFAULT NULL COMMENT 'Username for the service',
  `password_encrypted` varchar(255) DEFAULT NULL COMMENT 'Encrypted password for the service',
  `url` varchar(255) DEFAULT NULL COMMENT 'URL link to the service ',
  `notes` text DEFAULT NULL COMMENT 'Additional notes for the entry (optional)',
  `created_at` timestamp NULL DEFAULT current_timestamp() COMMENT 'Timestamp of entry creation',
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`entry_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `passwords_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores user vault entries, including credentials for various services';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `passwords`
--

LOCK TABLES `passwords` WRITE;
/*!40000 ALTER TABLE `passwords` DISABLE KEYS */;
INSERT INTO `passwords` VALUES
(18,15,'google.com','dar3','4oW8GWxbAVL31+bAwjbG/g==',NULL,NULL,'2025-02-03 22:45:51','2025-02-03 22:45:51'),
(19,15,'qwerty.com','daria3','HW1u8FxHW6tGfbp9xkJo/w==',NULL,NULL,'2025-02-03 22:46:58','2025-02-03 22:46:58'),
(21,15,'www.aaa.bbb','d3','DwZdm9lMkqWnoyORRXT+cw==',NULL,NULL,'2025-02-03 22:53:29','2025-02-03 22:53:29'),
(22,16,'www.nescol.ac.uk','daria4','r0maFeon3xPs2fBRA95L5w==',NULL,NULL,'2025-02-03 23:00:07','2025-02-03 23:00:07'),
(23,16,'abc.com','d4','/nHrSBDOF3j1+b7hX9oTTQ==',NULL,NULL,'2025-02-03 23:00:44','2025-02-03 23:00:44');
/*!40000 ALTER TABLE `passwords` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tasks` (
  `taskID` int(11) NOT NULL AUTO_INCREMENT,
  `task` varchar(100) DEFAULT NULL,
  `details` varchar(255) DEFAULT NULL,
  `dueDate` date DEFAULT NULL,
  `saveDate` date DEFAULT current_timestamp(),
  PRIMARY KEY (`taskID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tasks`
--

LOCK TABLES `tasks` WRITE;
/*!40000 ALTER TABLE `tasks` DISABLE KEYS */;
INSERT INTO `tasks` VALUES
(1,'test task','a new task','2025-02-01','2025-01-27'),
(2,'tasks add january','','2025-01-17','2025-01-27'),
(3,'another one','asdas','2025-01-30','2025-01-27');
/*!40000 ALTER TABLE `tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'john_doe','$2y$10$eW5CZLpGb63C2vvz0S0bWuv9rf1B8lks5XzKL0cPS3I/9FyJ4Wo5a','john@example.com'),
(2,'jane_smith','$2y$10$2fvjRf3/LNOC/EYIEo9YuO3Q.EGyhiuM1lzQ/E2ms9jfhYXAs7P.i','jane@example.com'),
(3,'admin_user','$2y$10$Xp/6PCJj5SnZnT8ZzUwGVeW.DiSnCnZ1MS/tdVghbN/M1F2gHkncO','admin@example.com'),
(4,'guest_user','$2y$10$nH3kWfKZx9XpOBESZZ9v3OTlH2gEbJfJxyOZIq9bVf2k1xE7efUQG','guest@example.com'),
(5,'testuser','$2y$10$i.X1yfsE6IK5Vs7Uf.TtSO9l0BwcN/gmddAYptHskjF/cmtr.Wp2K','testuser@gmail.com'),
(7,'testuser2','$2y$10$EsKk/VwdEoSfp9Q2Gyxol.7.h5Mv1869l4fEuUi5VxUq3ySqTHc6q','testuser2@gmail.com'),
(8,'bob','$2y$10$YQ8uAN65yfh1K41ni9sj5.aNuwHeMJl703ajc4WHcPIgIBLgsGk4e','bongo@bongo.com'),
(9,'testusernew','$2y$10$.wPM1M9rLmgkWC/946PSr.9MPPb1C7mxQBF5N3vkXZe.qNqAFJdWK','test@test.com'),
(11,'blue','$2y$10$fOQUBLeHfBVZ/Fp6wkTkHOHaJ0K8051IzOA5EN/md0xpTqZqBv62m','steel@steel.com'),
(12,'shona','$2y$10$bc2njZu1ql630GNMoUKaZ.328vD9xHFoJsOH7PVYYV9WQQ/X1KTJO','shona@shona.com'),
(13,'daria','$2y$10$0JIvzyOWEZP3jphNeVZXv.hz0NJEq94MlHkZQ.bI5EV7NEO/xzWYu','daria@qwerty.com'),
(14,'daria2','$2y$10$IR1PpUGFw7SD6ZkEL3evQuKMCK/qsr54mrDr1185IZyM1sOEY8hrW','daria2@qwerty.com'),
(15,'daria3','$2y$10$lh7wqKTpw5P8Qsth68MbyOuGVBxgQyc3lTVeYZY.4XtIbXDJSYJwq','daria3@qwerty.com'),
(16,'daria4','$2y$10$07ZOxsSkp6MZftxu2/WzK./6rYT5wAyz/.bG2JNfjhmfYTmaHrwNm','daria4@qwerty.com');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2025-02-04 15:01:40
