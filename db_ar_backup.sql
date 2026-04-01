-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.45-0ubuntu0.24.04.1 - (Ubuntu)
-- Server OS:                    Linux
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for db_announcement_system
CREATE DATABASE IF NOT EXISTS `db_announcement_system` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `db_announcement_system`;

-- Dumping structure for table db_announcement_system.tbl_admins
CREATE TABLE IF NOT EXISTS `tbl_admins` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_announcement_system.tbl_admins: ~0 rows (approximately)
INSERT INTO `tbl_admins` (`id`, `username`, `password_hash`, `created_at`) VALUES
	(1, 'admin', '$2y$10$gwfp5E25nYfDync77HZQ0umD18NFX9iwr0z43oXH5W9.tgz4/BOdu', '2026-03-31 14:31:07');

-- Dumping structure for table db_announcement_system.tbl_announcements
CREATE TABLE IF NOT EXISTS `tbl_announcements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `admin_id` int DEFAULT NULL,
  `subject_id` int DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `due_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('active','archived') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`),
  KEY `subject_id` (`subject_id`),
  CONSTRAINT `tbl_announcements_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `tbl_admins` (`id`) ON DELETE SET NULL,
  CONSTRAINT `tbl_announcements_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `tbl_subjects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_announcement_system.tbl_announcements: ~15 rows (approximately)
INSERT INTO `tbl_announcements` (`id`, `admin_id`, `subject_id`, `title`, `content`, `due_date`, `end_date`, `status`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 'Reporting', 'Chapter 6', '2026-04-06', NULL, 'active', '2026-03-31 14:37:49', '2026-03-31 16:25:41'),
	(2, 1, 4, 'Midterm Review', 'Bali i-rereview lang tayo ni sir', '2026-04-07', NULL, 'active', '2026-03-31 14:57:52', '2026-03-31 14:57:52'),
	(4, 1, 1, 'Submission', 'Chapter 1 and 2', '2026-04-06', NULL, 'active', '2026-03-31 16:26:39', '2026-03-31 16:26:39'),
	(5, 1, 2, 'Midterm Exam', 'Coverage: Chapter 1 to 5', '2026-04-06', NULL, 'active', '2026-03-31 16:28:07', '2026-03-31 16:28:07'),
	(6, 1, 3, 'Quiz 1', 'Coverage: \r\n1. Simple Interest\r\n2. Compound Interest\r\n3. Effective Rate of Interest\r\n4. Equation of Value\r\n5. Discrete Payment\r\n6. Continuous Compounding Interest\r\n7. Banker s Discount', '2026-04-06', NULL, 'active', '2026-03-31 16:31:32', '2026-03-31 16:42:19'),
	(8, 1, 5, 'Presentation', 'Topic 4', '2026-04-02', NULL, 'active', '2026-03-31 16:34:48', '2026-03-31 16:34:48'),
	(9, 1, 6, 'Quiz 1', 'Coverage: Chapter 1 and 2', '2026-04-08', NULL, 'active', '2026-03-31 16:35:33', '2026-03-31 16:36:12'),
	(10, 1, 7, 'Reporting', 'Chapter 6', '2026-04-08', NULL, 'active', '2026-03-31 16:38:29', '2026-04-01 07:50:47'),
	(12, 1, 7, 'Submission', 'Chapter 1 to 3', '2026-04-08', NULL, 'active', '2026-03-31 16:39:24', '2026-04-01 06:14:55'),
	(13, 1, 3, 'Notebook', 'Provide a notebook for additional points.', NULL, NULL, 'active', '2026-03-31 16:44:17', '2026-03-31 16:44:17'),
	(14, 1, 8, 'Submission', 'Pgs. 13, 14, 15, 25, 26, 27', '2026-04-09', NULL, 'active', '2026-03-31 16:45:56', '2026-03-31 16:45:56'),
	(15, 1, 9, 'Books:', 'SCIETS - 320₱\r\nCONTWO - 300₱\r\nRIZAL - 360₱\r\nPEHEF2/INDAYOG - 350₱', NULL, NULL, 'active', '2026-03-31 16:48:12', '2026-03-31 16:48:12'),
	(16, 1, 8, 'Study and master executing the:', 'Polka sa Nayon', NULL, NULL, 'active', '2026-03-31 16:48:35', '2026-04-01 03:57:34'),
	(21, 1, 6, 'Seatwork 2', 'Coverage: Chapter 3', '2026-04-08', NULL, 'active', '2026-04-01 03:52:43', '2026-04-01 03:52:43'),
	(22, 1, 2, 'Submission', 'Chapter 1 to 3', '2026-04-08', NULL, 'active', '2026-04-01 06:10:43', '2026-04-01 06:10:43');

-- Dumping structure for table db_announcement_system.tbl_audit_log
CREATE TABLE IF NOT EXISTS `tbl_audit_log` (
  `id` int NOT NULL AUTO_INCREMENT,
  `admin_id` int DEFAULT NULL,
  `announcement_id` int DEFAULT NULL,
  `action` varchar(50) NOT NULL,
  `old_value` text,
  `new_value` text,
  `changed_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`),
  CONSTRAINT `tbl_audit_log_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `tbl_admins` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_announcement_system.tbl_audit_log: ~58 rows (approximately)
INSERT INTO `tbl_audit_log` (`id`, `admin_id`, `announcement_id`, `action`, `old_value`, `new_value`, `changed_at`) VALUES
	(1, 1, 1, 'created', NULL, '{"subject_id":"1","title":"Reporting","content":"Chapter 6","due_date":"2026-04-01"}', '2026-03-31 14:37:49'),
	(2, 1, 1, 'updated', '{"id":1,"admin_id":1,"subject_id":1,"title":"Reporting","content":"Chapter 6","due_date":"2026-04-01","status":"active","created_at":"2026-03-31 22:37:49","updated_at":"2026-03-31 22:37:49"}', '{"subject_id":"1","title":"Reporting","content":"Chapter 6","due_date":"2026-03-31"}', '2026-03-31 14:38:27'),
	(3, 1, 1, 'archived', NULL, NULL, '2026-03-31 14:43:19'),
	(4, 1, 1, 'restored', NULL, NULL, '2026-03-31 14:43:38'),
	(5, 1, 1, 'archived', NULL, NULL, '2026-03-31 14:43:43'),
	(6, 1, 1, 'restored', NULL, NULL, '2026-03-31 14:50:50'),
	(7, 1, 2, 'created', NULL, '{"subject_id":"4","title":"Midterm Review","content":"Bali i-rereview lang tayo ni sir","due_date":"2026-04-07"}', '2026-03-31 14:57:52'),
	(8, 1, 3, 'created', NULL, '{"subject_id":"8","title":"Dance","content":"Study and master executing the:\\r\\n1. Fundamental Positions of Arms and Feet\\r\\na. Arms\\r\\nb. Feet\\r\\nc. Combination\\r\\n2. Basic Steps in 2\\/4 measure\\r\\n-Touch Step\\r\\n-Bleking\\r\\n-Close Step\\r\\n-Hop Step\\r\\n-Brush Step\\r\\n-Swing Step\\r\\n-Slide Step\\r\\n3. Basic Steps in 3\\/4 measure\\r\\n-Waltz\\r\\n-Cross Waltz \\r\\n-Waltz Balance\\r\\n-Mazurka\\r\\n-Redoba","due_date":""}', '2026-03-31 15:59:30'),
	(9, 1, 3, 'archived', NULL, NULL, '2026-03-31 16:04:09'),
	(10, 1, NULL, 'permanently deleted (ID:3)', NULL, NULL, '2026-03-31 16:22:22'),
	(11, 1, 1, 'updated', '{"id":1,"admin_id":1,"subject_id":1,"title":"Reporting","content":"Chapter 6","due_date":"2026-03-31","end_date":null,"status":"active","created_at":"2026-03-31 22:37:49","updated_at":"2026-03-31 22:50:50"}', '{"subject_id":"1","title":"Reporting","content":"Chapter 6","due_date":"2026-04-06","end_date":""}', '2026-03-31 16:25:41'),
	(12, 1, 4, 'created', NULL, '{"subject_id":"1","title":"Submission","content":"Chapter 1 and 2","due_date":"2026-04-06","end_date":""}', '2026-03-31 16:26:39'),
	(13, 1, 5, 'created', NULL, '{"subject_id":"2","title":"Midterm Exam","content":"Coverage: Chapter 1 to 5","due_date":"2026-04-06","end_date":""}', '2026-03-31 16:28:07'),
	(14, 1, 6, 'created', NULL, '{"subject_id":"3","title":"Quiz 1","content":"Coverage: Simple\\/Compound Interest, Effective Rate of Interest, Equation of Value, Discrete Payment, Continuous Compounding Interest, and Banker\'s Discount\\r\\n","due_date":"2026-04-06","end_date":""}', '2026-03-31 16:31:32'),
	(15, 1, 6, 'updated', '{"id":6,"admin_id":1,"subject_id":3,"title":"Quiz 1","content":"Coverage: Simple\\/Compound Interest, Effective Rate of Interest, Equation of Value, Discrete Payment, Continuous Compounding Interest, and Banker&#039;s Discount","due_date":"2026-04-06","end_date":null,"status":"active","created_at":"2026-04-01 00:31:32","updated_at":"2026-04-01 00:31:32"}', '{"subject_id":"3","title":"Quiz 1","content":"Coverage: Simple\\/Compound Interest, Effective Rate of Interest, Equation of Value, Discrete Payment, Continuous Compounding Interest, and Banker\'s Discount","due_date":"2026-04-06","end_date":""}', '2026-03-31 16:32:32'),
	(16, 1, 7, 'created', NULL, '{"subject_id":"9","title":"Midterm Week","content":"Hell week shit","due_date":"2026-04-06","end_date":"2026-04-09"}', '2026-03-31 16:33:14'),
	(17, 1, 8, 'created', NULL, '{"subject_id":"5","title":"Presentation","content":"Topic 4","due_date":"2026-04-02","end_date":""}', '2026-03-31 16:34:48'),
	(18, 1, 9, 'created', NULL, '{"subject_id":"6","title":"Quiz 1","content":"Coverage: Chapter 1 and 2","due_date":"2026-04-06","end_date":""}', '2026-03-31 16:35:33'),
	(19, 1, 9, 'updated', '{"id":9,"admin_id":1,"subject_id":6,"title":"Quiz 1","content":"Coverage: Chapter 1 and 2","due_date":"2026-04-06","end_date":null,"status":"active","created_at":"2026-04-01 00:35:33","updated_at":"2026-04-01 00:35:33"}', '{"subject_id":"6","title":"Quiz 1","content":"Coverage: Chapter 1 and 2","due_date":"2026-04-08","end_date":""}', '2026-03-31 16:36:12'),
	(20, 1, 6, 'updated', '{"id":6,"admin_id":1,"subject_id":3,"title":"Quiz 1","content":"Coverage: Simple\\/Compound Interest, Effective Rate of Interest, Equation of Value, Discrete Payment, Continuous Compounding Interest, and Banker&#039;s Discount","due_date":"2026-04-06","end_date":null,"status":"active","created_at":"2026-04-01 00:31:32","updated_at":"2026-04-01 00:31:32"}', '{"subject_id":"3","title":"Quiz 1","content":"Coverage: Simple\\/Compound Interest, Effective Rate of Interest, Equation of Value, Discrete Payment, Continuous Compounding Interest, and Banker&#039;s Discount","due_date":"2026-04-06","end_date":"2026-04-10"}', '2026-03-31 16:36:43'),
	(21, 1, 6, 'updated', '{"id":6,"admin_id":1,"subject_id":3,"title":"Quiz 1","content":"Coverage: Simple\\/Compound Interest, Effective Rate of Interest, Equation of Value, Discrete Payment, Continuous Compounding Interest, and Banker&amp;#039;s Discount","due_date":"2026-04-06","end_date":"2026-04-10","status":"active","created_at":"2026-04-01 00:31:32","updated_at":"2026-04-01 00:36:43"}', '{"subject_id":"3","title":"Quiz 1","content":"Coverage: Simple\\/Compound Interest, Effective Rate of Interest, Equation of Value, Discrete Payment, Continuous Compounding Interest, and Banker&amp;#039;s Discount","due_date":"2026-04-06","end_date":""}', '2026-03-31 16:37:16'),
	(22, 1, 10, 'created', NULL, '{"subject_id":"7","title":"Reporting","content":"Chapter 6","due_date":"2026-04-01","end_date":""}', '2026-03-31 16:38:29'),
	(23, 1, 11, 'created', NULL, '{"subject_id":"2","title":"Submission","content":"Chapter 1 to 3","due_date":"2026-04-08","end_date":""}', '2026-03-31 16:38:50'),
	(24, 1, 12, 'created', NULL, '{"subject_id":"7","title":"Submission","content":"Chapter 1 to 3","due_date":"2026-04-08","end_date":""}', '2026-03-31 16:39:24'),
	(25, 1, 11, 'archived', NULL, NULL, '2026-03-31 16:39:45'),
	(26, 1, NULL, 'permanently deleted (ID:11)', NULL, NULL, '2026-03-31 16:39:47'),
	(27, 1, 6, 'updated', '{"id":6,"admin_id":1,"subject_id":3,"title":"Quiz 1","content":"Coverage: Simple\\/Compound Interest, Effective Rate of Interest, Equation of Value, Discrete Payment, Continuous Compounding Interest, and Banker&amp;amp;#039;s Discount","due_date":"2026-04-06","end_date":null,"status":"active","created_at":"2026-04-01 00:31:32","updated_at":"2026-04-01 00:37:16"}', '{"subject_id":"3","title":"Quiz 1","content":"Coverage: Simple\\/Compound Interest, Effective Rate of Interest, Equation of Value, Discrete Payment, Continuous Compounding Interest, and Banker\'s Discount","due_date":"2026-04-06","end_date":""}', '2026-03-31 16:40:14'),
	(28, 1, 6, 'updated', '{"id":6,"admin_id":1,"subject_id":3,"title":"Quiz 1","content":"Coverage: Simple\\/Compound Interest, Effective Rate of Interest, Equation of Value, Discrete Payment, Continuous Compounding Interest, and Banker&#039;s Discount","due_date":"2026-04-06","end_date":null,"status":"active","created_at":"2026-04-01 00:31:32","updated_at":"2026-04-01 00:40:14"}', '{"subject_id":"3","title":"Quiz 1","content":"Coverage: Simple\\/Compound Interest, Effective Rate of Interest, Equation of Value, Discrete Payment, Continuous Compounding Interest, and Banker s Discount","due_date":"2026-04-06","end_date":""}', '2026-03-31 16:40:42'),
	(29, 1, 6, 'updated', '{"id":6,"admin_id":1,"subject_id":3,"title":"Quiz 1","content":"Coverage: Simple\\/Compound Interest, Effective Rate of Interest, Equation of Value, Discrete Payment, Continuous Compounding Interest, and Banker s Discount","due_date":"2026-04-06","end_date":null,"status":"active","created_at":"2026-04-01 00:31:32","updated_at":"2026-04-01 00:40:42"}', '{"subject_id":"3","title":"Quiz 1","content":"Coverage: Simple\\/Compound Interest\\r\\nEffective Rate of Interest\\r\\nEquation of Value\\r\\nDiscrete Payment\\r\\nContinuous Compounding Interest\\r\\nBanker s Discount","due_date":"2026-04-06","end_date":""}', '2026-03-31 16:41:23'),
	(30, 1, 6, 'updated', '{"id":6,"admin_id":1,"subject_id":3,"title":"Quiz 1","content":"Coverage: Simple\\/Compound Interest\\r\\nEffective Rate of Interest\\r\\nEquation of Value\\r\\nDiscrete Payment\\r\\nContinuous Compounding Interest\\r\\nBanker s Discount","due_date":"2026-04-06","end_date":null,"status":"active","created_at":"2026-04-01 00:31:32","updated_at":"2026-04-01 00:41:23"}', '{"subject_id":"3","title":"Quiz 1","content":"Coverage: \\r\\n1. Simple Interest\\r\\n2. Compound Interest\\r\\n3. Effective Rate of Interest\\r\\n4. Equation of Value\\r\\n5. Discrete Payment\\r\\n6. Continuous Compounding Interest\\r\\n7. Banker s Discount","due_date":"2026-04-06","end_date":""}', '2026-03-31 16:42:19'),
	(31, 1, 13, 'created', NULL, '{"subject_id":"3","title":"Notebook","content":"Provide a notebook for additional points.","due_date":"","end_date":""}', '2026-03-31 16:44:17'),
	(32, 1, 14, 'created', NULL, '{"subject_id":"8","title":"Submission","content":"Pgs. 13, 14, 15, 25, 26, 27","due_date":"2026-04-09","end_date":""}', '2026-03-31 16:45:56'),
	(33, 1, 15, 'created', NULL, '{"subject_id":"9","title":"Books:","content":"SCIETS - 320\\u20b1\\r\\nCONTWO - 300\\u20b1\\r\\nRIZAL - 360\\u20b1\\r\\nPEHEF2\\/INDAYOG - 350\\u20b1","due_date":"","end_date":""}', '2026-03-31 16:48:12'),
	(34, 1, 16, 'created', NULL, '{"subject_id":"8","title":"Study and master executing the:","content":"1. Fundamental Positions of Arms and Feet\\r\\na. Arms\\r\\nb. Feet\\r\\nc. Combination\\r\\n2. Basic Steps in 2\\/4 measure\\r\\n-Touch Step\\r\\n-Bleking\\r\\n-Close Step\\r\\n-Hop Step\\r\\n-Brush Step\\r\\n-Swing Step\\r\\n-Slide Step\\r\\n3. Basic Steps in 3\\/4 measure\\r\\n-Waltz\\r\\n-Cross Waltz \\r\\n-Waltz Balance\\r\\n-Mazurka\\r\\n-Redoba","due_date":"","end_date":""}', '2026-03-31 16:48:35'),
	(35, 1, 10, 'archived', NULL, NULL, '2026-03-31 17:56:32'),
	(36, 1, 10, 'restored', NULL, NULL, '2026-03-31 17:56:37'),
	(37, 1, 17, 'created', NULL, NULL, '2026-03-31 22:13:30'),
	(38, 1, 17, 'archived', NULL, NULL, '2026-03-31 22:13:39'),
	(39, 1, NULL, 'permanently deleted (ID:17)', NULL, NULL, '2026-03-31 22:13:45'),
	(40, 1, 18, 'created', NULL, NULL, '2026-03-31 22:14:29'),
	(41, 1, 18, 'archived', NULL, NULL, '2026-03-31 22:14:53'),
	(42, 1, 19, 'created', NULL, NULL, '2026-03-31 22:15:26'),
	(43, 1, 19, 'archived', NULL, NULL, '2026-03-31 22:23:31'),
	(44, 1, 20, 'created', NULL, NULL, '2026-03-31 22:24:14'),
	(45, 1, 20, 'archived', NULL, NULL, '2026-03-31 22:24:32'),
	(46, 1, NULL, 'permanently deleted (ID:19)', NULL, NULL, '2026-03-31 22:24:36'),
	(47, 1, NULL, 'permanently deleted (ID:18)', NULL, NULL, '2026-03-31 22:24:46'),
	(48, 1, NULL, 'permanently deleted (ID:20)', NULL, NULL, '2026-03-31 22:24:51'),
	(49, 1, 21, 'created', NULL, NULL, '2026-04-01 03:52:43'),
	(50, 1, 16, 'updated', NULL, NULL, '2026-04-01 03:57:34'),
	(51, 1, 7, 'archived', NULL, NULL, '2026-04-01 05:43:52'),
	(52, 1, NULL, 'permanently deleted (ID:7)', NULL, NULL, '2026-04-01 05:43:56'),
	(53, 1, 12, 'archived', NULL, NULL, '2026-04-01 06:10:05'),
	(54, 1, 12, 'restored', NULL, NULL, '2026-04-01 06:10:08'),
	(55, 1, 12, 'archived', NULL, NULL, '2026-04-01 06:10:27'),
	(56, 1, 22, 'created', NULL, NULL, '2026-04-01 06:10:43'),
	(57, 1, 12, 'restored', NULL, NULL, '2026-04-01 06:14:55'),
	(58, 1, 10, 'updated', NULL, NULL, '2026-04-01 07:50:47');

-- Dumping structure for table db_announcement_system.tbl_subjects
CREATE TABLE IF NOT EXISTS `tbl_subjects` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `professor` varchar(100) DEFAULT NULL,
  `schedule` varchar(100) DEFAULT NULL,
  `color_theme` varchar(50) DEFAULT 'bg-other',
  `status` enum('active','archived') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_announcement_system.tbl_subjects: ~9 rows (approximately)
INSERT INTO `tbl_subjects` (`id`, `code`, `name`, `professor`, `schedule`, `color_theme`, `status`, `created_at`) VALUES
	(1, 'SCIETS', 'Science, Technology and Society', 'Jose L.', 'M 10 AM - 1 PM', 'bg-sciets', 'active', '2026-03-31 14:31:07'),
	(2, 'CONTWO', 'The Contemporary World', 'Arnold A.', 'M 1 PM - 4 PM', 'bg-contwo', 'active', '2026-03-31 14:31:07'),
	(3, 'ENECO', 'Engineering Economy', 'Allen Y.', 'M 5 PM - 8 PM', 'bg-eneco', 'active', '2026-03-31 14:31:07'),
	(4, 'ECENG', 'Fundamentals of Electronic Circuits', 'Nelson D.', 'T/TH 10 AM - 1 PM', 'bg-eceng', 'active', '2026-03-31 14:31:07'),
	(5, 'SOFTDES', 'Software Design', 'Jane A.', 'T/TH 2:30 PM - 5:30 PM', 'bg-softdes', 'active', '2026-03-31 14:31:07'),
	(6, 'NUMERICAL', 'Numerical Methods', 'Bernard F.', 'W 10 AM - 1 PM', 'bg-numerical', 'active', '2026-03-31 14:31:07'),
	(7, 'RIZAL', 'Life and Works of Rizal', 'Matthew N.', 'W 2 PM - 5 PM', 'bg-rizal', 'active', '2026-03-31 14:31:07'),
	(8, 'PEHEF2', 'Physical Activity Towards Health and Fitness II', 'Angela B.', 'TH 7 AM - 9 AM', 'bg-pehef2', 'active', '2026-03-31 14:31:07'),
	(9, 'OTHER', 'General Announcements', 'N/A', 'N/A', 'bg-other', 'active', '2026-03-31 14:31:07');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
