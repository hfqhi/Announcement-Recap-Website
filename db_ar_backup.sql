-- --------------------------------------------------------
-- Server version:               8.0.45-0ubuntu0.24.04.1 - (Ubuntu)
-- Server OS:                    Linux
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table tbl_admins
CREATE TABLE IF NOT EXISTS `tbl_admins` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table tbl_admins
INSERT INTO `tbl_admins` (`id`, `username`, `password_hash`, `created_at`) VALUES
    (1, 'john', '$2y$12$Q5UMZo2GeUlW3huiCgepOu4N0W9hu81MD12yGE0TKJoRuKHFyx3Ha', '2026-03-31 14:31:07'),
    (2, 'ekang', '$2y$12$sGnYIDzvrq3Uw3ILK/fo6Oa.x4/3iWVMKBrYfoB197O0tA75O.YSa', '2026-04-10 07:50:03');

-- Dumping structure for table tbl_subjects
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

-- Dumping data for table tbl_subjects
INSERT INTO `tbl_subjects` (`id`, `code`, `name`, `professor`, `schedule`, `color_theme`, `status`, `created_at`) VALUES
    (1, 'SCIETS', 'Science, Technology and Society', 'Engr. Jose L.', 'M 9 AM - 12 PM', 'bg-sciets', 'active', '2026-03-31 14:31:07'),
    (2, 'CONTWO', 'The Contemporary World', 'Dr. Arnold A.', 'M 1 PM - 4 PM', 'bg-contwo', 'active', '2026-03-31 14:31:07'),
    (3, 'ENECO', 'Engineering Economy', 'Engr. Allen Y.', 'M 5 PM - 8 PM', 'bg-eneco', 'active', '2026-03-31 14:31:07'),
    (4, 'ECENG', 'Fundamentals of Electronic Circuits', 'Engr. Nelson D.', 'T/TH 10 AM - 1 PM', 'bg-eceng', 'active', '2026-03-31 14:31:07'),
    (5, 'SOFTDES', 'Software Design', 'Engr. Jane A.', 'T/TH 2:30 PM - 5:30 PM', 'bg-softdes', 'active', '2026-03-31 14:31:07'),
    (6, 'NUMERICAL', 'Numerical Methods', 'Engr. Bernard F.', 'W 10 AM - 1 PM', 'bg-numerical', 'active', '2026-03-31 14:31:07'),
    (7, 'RIZAL', 'Life and Works of Rizal', 'Prof. Matthew N.', 'W 2 PM - 5 PM', 'bg-rizal', 'active', '2026-03-31 14:31:07'),
    (8, 'PEHEF2', 'Physical Activity Towards Health and Fitness II', 'Prof. Angela B.', 'TH 7 AM - 9 AM', 'bg-pehef2', 'active', '2026-03-31 14:31:07'),
    (9, 'OTHER', 'General Announcements', 'N/A', 'N/A', 'bg-other', 'active', '2026-03-31 14:31:07');

-- Dumping structure for table tbl_announcements
CREATE TABLE IF NOT EXISTS `tbl_announcements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `admin_id` int DEFAULT NULL,
  `subject_id` int DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `due_date` date DEFAULT NULL,
  `due_time` time DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('active','archived') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`),
  KEY `subject_id` (`subject_id`),
  KEY `idx_due_date` (`due_date`),
  KEY `idx_subject_status` (`subject_id`,`status`),
  CONSTRAINT `tbl_announcements_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `tbl_admins` (`id`) ON DELETE SET NULL,
  CONSTRAINT `tbl_announcements_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `tbl_subjects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table tbl_announcements
INSERT INTO `tbl_announcements` (`id`, `admin_id`, `subject_id`, `title`, `content`, `due_date`, `due_time`, `end_date`, `status`, `created_at`, `updated_at`) VALUES
    (1, 1, 1, 'Reporting', 'Chapter 7', '2026-04-13', '07:00:00', NULL, 'active', '2026-03-31 14:37:49', '2026-04-06 06:45:20'),
    (2, 1, 4, 'Midterm Review', 'Topics to be review: Transistors and etc.', '2026-04-14', '10:30:00', NULL, 'active', '2026-03-31 14:57:52', '2026-04-07 04:01:02'),
    (4, 1, 1, 'Submission', 'Chapter 1', '2026-04-08', '10:00:00', NULL, 'archived', '2026-03-31 16:26:39', '2026-04-08 03:39:49'),
    (5, 1, 2, 'Midterm Exam', 'Coverage: Lesson 1 to 5', '2026-04-13', '13:00:00', NULL, 'active', '2026-03-31 16:28:07', '2026-04-10 09:24:51'),
    (6, 1, 3, 'Quiz 1', 'Coverage: \r\n1. Simple Interest\r\n2. Compound Interest\r\n3. Effective Rate of Interest\r\n4. Equation of Value\r\n5. Discrete Payment\r\n6. Continuous Compounding Interest\r\n7. Banker s Discount', '2026-04-13', '17:00:00', NULL, 'active', '2026-03-31 16:31:32', '2026-04-06 10:21:18'),
    (8, 1, 5, 'Presentation', 'Topic 4', '2026-04-14', '14:30:00', NULL, 'active', '2026-03-31 16:34:48', '2026-04-07 04:01:16'),
    (9, 1, 6, 'Discussion', 'Final topic.', '2026-04-15', '10:00:00', NULL, 'active', '2026-03-31 16:35:33', '2026-04-08 03:39:36'),
    (10, 1, 7, 'Reporting', 'Chapter 6', '2026-04-15', '14:00:00', NULL, 'active', '2026-03-31 16:38:29', '2026-04-07 11:01:13'),
    (12, 1, 7, 'Submission', 'Chapter 1 to 3', '2026-04-08', '14:00:00', NULL, 'archived', '2026-03-31 16:39:24', '2026-04-08 03:39:47'),
    (13, 1, 3, 'Notebook', 'Provide a notebook for additional points.', NULL, NULL, NULL, 'active', '2026-03-31 16:44:17', '2026-03-31 16:44:17'),
    (14, 1, 8, 'Submission', 'Pgs. 13, 14, 15, 25, 26, 27', '2026-04-08', '22:00:00', NULL, 'archived', '2026-03-31 16:45:56', '2026-04-08 03:39:43'),
    (15, 1, 9, 'Books:', 'SCIETS - 320₱\r\nCONTWO - 300₱\r\nRIZAL - 360₱\r\nPEHEF2/INDAYOG - 350₱', NULL, NULL, NULL, 'active', '2026-03-31 16:48:12', '2026-03-31 16:48:12'),
    (16, 1, 8, 'Study and master executing the:', 'Polka sa Nayon', NULL, NULL, NULL, 'active', '2026-03-31 16:48:35', '2026-04-01 03:57:34'),
    (21, 1, 6, 'Seatwork 2', 'Coverage: Chapter 3', '2026-04-15', '10:00:00', NULL, 'active', '2026-04-01 03:52:43', '2026-04-08 03:39:05'),
    (24, 1, 2, 'Quiz', 'Coverage: Lesson 7', '2026-04-06', '15:00:00', NULL, 'archived', '2026-04-04 13:26:34', '2026-04-10 09:22:44'),
    (25, 1, 9, 'Holiday - Araw ng Kagitingan', 'Malamang walang pasok', '2026-04-09', NULL, NULL, 'archived', '2026-04-06 06:42:14', '2026-04-10 07:07:42'),
    (26, 1, 4, 'Topics', '1. Fundamentals of DC circuits ✔️\r\n2. Diodes ✔️\r\n3. Introduction to transistors ✔️\r\n4. The transistor switch ✔️\r\n5. Fundamentals of AC circuits ✔️\r\n6. Filters ✔️\r\n7. Resonant circuits ✔️\r\n8. Transistor amplifiers\r\n9. Oscillators\r\n10. The transformer\r\n11. Power supply circuits', NULL, NULL, NULL, 'active', '2026-04-06 14:08:32', '2026-04-06 14:16:00'),
    (27, 1, 7, 'Midterm Exam', 'For those who don\'t have books.', '2026-04-08', '14:00:00', NULL, 'archived', '2026-04-07 11:01:00', '2026-04-09 10:11:52'),
    (28, 1, 9, 'FTF Modality', 'April 8–18', NULL, NULL, NULL, 'active', '2026-04-07 13:04:01', '2026-04-07 13:05:11');

-- Dumping structure for table tbl_audit_log
CREATE TABLE IF NOT EXISTS `tbl_audit_log` (
  `id` int NOT NULL AUTO_INCREMENT,
  `admin_id` int DEFAULT NULL,
  `announcement_id` int DEFAULT NULL,
  `deleted_record_id` int DEFAULT NULL,
  `action` enum('created','updated','archived','restored','hard_deleted','subject_created','subject_updated','subject_archived','subject_restored','subject_deleted') NOT NULL,
  `old_value` text,
  `new_value` text,
  `changed_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`),
  KEY `idx_changed_at` (`changed_at`),
  KEY `idx_action` (`action`),
  CONSTRAINT `tbl_audit_log_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `tbl_admins` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=124 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table tbl_audit_log (Skipped bulky data for brevity, system will regenerate naturally)

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;