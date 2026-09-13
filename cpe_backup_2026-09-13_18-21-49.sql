-- CPE-3B Automated Database Backup
-- Generated: 2026-09-13 18:21:49

DROP TABLE IF EXISTS `tbl_admins`;
CREATE TABLE `tbl_admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `tbl_admins` VALUES ('1', 'john', '$2y$12$Q5UMZo2GeUlW3huiCgepOu4N0W9hu81MD12yGE0TKJoRuKHFyx3Ha', '2026-04-01 05:31:07');
INSERT INTO `tbl_admins` VALUES ('2', 'ekang', '$2y$12$sGnYIDzvrq3Uw3ILK/fo6Oa.x4/3iWVMKBrYfoB197O0tA75O.YSa', '2026-04-10 22:50:03');


DROP TABLE IF EXISTS `tbl_announcements`;
CREATE TABLE `tbl_announcements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `due_date` date DEFAULT NULL,
  `due_time` time DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `status` enum('active','archived') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`),
  KEY `subject_id` (`subject_id`),
  KEY `idx_due_date` (`due_date`),
  KEY `idx_subject_status` (`subject_id`,`status`),
  CONSTRAINT `tbl_announcements_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `tbl_admins` (`id`) ON DELETE SET NULL,
  CONSTRAINT `tbl_announcements_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `tbl_subjects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `tbl_announcements` VALUES ('29', '1', '9', 'CPE-3B', 'Class Schedule', NULL, NULL, NULL, 'assets/uploads/1789293785_ClassSchedule1stSemester.png', 'active', '2026-09-13 18:01:43', '2026-09-13 18:03:06');
INSERT INTO `tbl_announcements` VALUES ('30', '1', '10', 'Lecture', 'Boolean Forms and Universal Gates Realization', '2026-09-14', '07:00:00', NULL, 'assets/uploads/1789294410_Module1-Lesson4BooleanFormsandUniversalGatesRealization.pdf', 'active', '2026-09-13 18:13:30', '2026-09-13 18:13:30');


DROP TABLE IF EXISTS `tbl_audit_log`;
CREATE TABLE `tbl_audit_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) DEFAULT NULL,
  `announcement_id` int(11) DEFAULT NULL,
  `deleted_record_id` int(11) DEFAULT NULL,
  `action` enum('created','updated','archived','restored','hard_deleted','subject_created','subject_updated','subject_archived','subject_restored','subject_deleted') NOT NULL,
  `old_value` text DEFAULT NULL,
  `new_value` text DEFAULT NULL,
  `changed_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`),
  KEY `idx_changed_at` (`changed_at`),
  KEY `idx_action` (`action`),
  CONSTRAINT `tbl_audit_log_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `tbl_admins` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=168 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `tbl_audit_log` VALUES ('124', '1', '2', NULL, 'archived', NULL, '\"{\\\"id\\\":2,\\\"code\\\":\\\"CONTWO\\\",\\\"name\\\":\\\"The Contemporary World\\\",\\\"professor\\\":\\\"Dr. Arnold A.\\\",\\\"schedule\\\":\\\"M 1 PM - 4 PM\\\",\\\"color_theme\\\":\\\"bg-contwo\\\",\\\"status\\\":\\\"active\\\",\\\"created_at\\\":\\\"2026-04-01 05:31:07\\\"}\"', '2026-09-13 17:17:04');
INSERT INTO `tbl_audit_log` VALUES ('125', '1', '4', NULL, 'archived', NULL, '\"{\\\"id\\\":4,\\\"code\\\":\\\"ECENG\\\",\\\"name\\\":\\\"Fundamentals of Electronic Circuits\\\",\\\"professor\\\":\\\"Engr. Nelson D.\\\",\\\"schedule\\\":\\\"T\\\\/TH 10 AM - 1 PM\\\",\\\"color_theme\\\":\\\"bg-eceng\\\",\\\"status\\\":\\\"active\\\",\\\"created_at\\\":\\\"2026-04-01 05:31:07\\\"}\"', '2026-09-13 17:17:07');
INSERT INTO `tbl_audit_log` VALUES ('126', '1', '3', NULL, 'archived', NULL, '\"{\\\"id\\\":3,\\\"code\\\":\\\"ENECO\\\",\\\"name\\\":\\\"Engineering Economy\\\",\\\"professor\\\":\\\"Engr. Allen Y.\\\",\\\"schedule\\\":\\\"M 5 PM - 8 PM\\\",\\\"color_theme\\\":\\\"bg-eneco\\\",\\\"status\\\":\\\"active\\\",\\\"created_at\\\":\\\"2026-04-01 05:31:07\\\"}\"', '2026-09-13 17:17:09');
INSERT INTO `tbl_audit_log` VALUES ('127', '1', '6', NULL, 'archived', NULL, '\"{\\\"id\\\":6,\\\"code\\\":\\\"NUMERICAL\\\",\\\"name\\\":\\\"Numerical Methods\\\",\\\"professor\\\":\\\"Engr. Bernard F.\\\",\\\"schedule\\\":\\\"W 10 AM - 1 PM\\\",\\\"color_theme\\\":\\\"bg-numerical\\\",\\\"status\\\":\\\"active\\\",\\\"created_at\\\":\\\"2026-04-01 05:31:07\\\"}\"', '2026-09-13 17:17:13');
INSERT INTO `tbl_audit_log` VALUES ('128', '1', '8', NULL, 'archived', NULL, '\"{\\\"id\\\":8,\\\"code\\\":\\\"PEHEF2\\\",\\\"name\\\":\\\"Physical Activity Towards Health and Fitness II\\\",\\\"professor\\\":\\\"Prof. Angela B.\\\",\\\"schedule\\\":\\\"TH 7 AM - 9 AM\\\",\\\"color_theme\\\":\\\"bg-pehef2\\\",\\\"status\\\":\\\"active\\\",\\\"created_at\\\":\\\"2026-04-01 05:31:07\\\"}\"', '2026-09-13 17:17:16');
INSERT INTO `tbl_audit_log` VALUES ('129', '1', '7', NULL, 'archived', NULL, '\"{\\\"id\\\":7,\\\"code\\\":\\\"RIZAL\\\",\\\"name\\\":\\\"Life and Works of Rizal\\\",\\\"professor\\\":\\\"Prof. Matthew N.\\\",\\\"schedule\\\":\\\"W 2 PM - 5 PM\\\",\\\"color_theme\\\":\\\"bg-rizal\\\",\\\"status\\\":\\\"active\\\",\\\"created_at\\\":\\\"2026-04-01 05:31:07\\\"}\"', '2026-09-13 17:17:18');
INSERT INTO `tbl_audit_log` VALUES ('130', '1', '1', NULL, 'archived', NULL, '\"{\\\"id\\\":1,\\\"code\\\":\\\"SCIETS\\\",\\\"name\\\":\\\"Science, Technology and Society\\\",\\\"professor\\\":\\\"Engr. Jose L.\\\",\\\"schedule\\\":\\\"M 9 AM - 12 PM\\\",\\\"color_theme\\\":\\\"bg-sciets\\\",\\\"status\\\":\\\"active\\\",\\\"created_at\\\":\\\"2026-04-01 05:31:07\\\"}\"', '2026-09-13 17:17:21');
INSERT INTO `tbl_audit_log` VALUES ('131', '1', '5', NULL, 'archived', NULL, '\"{\\\"id\\\":5,\\\"code\\\":\\\"SOFTDES\\\",\\\"name\\\":\\\"Software Design\\\",\\\"professor\\\":\\\"Engr. Jane A.\\\",\\\"schedule\\\":\\\"T\\\\/TH 2:30 PM - 5:30 PM\\\",\\\"color_theme\\\":\\\"bg-softdes\\\",\\\"status\\\":\\\"active\\\",\\\"created_at\\\":\\\"2026-04-01 05:31:07\\\"}\"', '2026-09-13 17:17:23');
INSERT INTO `tbl_audit_log` VALUES ('132', '1', '26', NULL, 'archived', NULL, '\"{\\\"id\\\":26,\\\"admin_id\\\":1,\\\"subject_id\\\":4,\\\"title\\\":\\\"Topics\\\",\\\"content\\\":\\\"1. Fundamentals of DC circuits \\\\u2714\\\\ufe0f\\\\r\\\\n2. Diodes \\\\u2714\\\\ufe0f\\\\r\\\\n3. Introduction to transistors \\\\u2714\\\\ufe0f\\\\r\\\\n4. The transistor switch \\\\u2714\\\\ufe0f\\\\r\\\\n5. Fundamentals of AC circuits \\\\u2714\\\\ufe0f\\\\r\\\\n6. Filters \\\\u2714\\\\ufe0f\\\\r\\\\n7. Resonant circuits \\\\u2714\\\\ufe0f\\\\r\\\\n8. Transistor amplifiers\\\\r\\\\n9. Oscillators\\\\r\\\\n10. The transformer\\\\r\\\\n11. Power supply circuits\\\",\\\"due_date\\\":null,\\\"due_time\\\":null,\\\"end_date\\\":null,\\\"status\\\":\\\"active\\\",\\\"created_at\\\":\\\"2026-04-07 05:08:32\\\",\\\"updated_at\\\":\\\"2026-04-07 05:16:00\\\"}\"', '2026-09-13 17:17:46');
INSERT INTO `tbl_audit_log` VALUES ('133', '1', '13', NULL, 'archived', NULL, '\"{\\\"id\\\":13,\\\"admin_id\\\":1,\\\"subject_id\\\":3,\\\"title\\\":\\\"Notebook\\\",\\\"content\\\":\\\"Provide a notebook for additional points.\\\",\\\"due_date\\\":null,\\\"due_time\\\":null,\\\"end_date\\\":null,\\\"status\\\":\\\"active\\\",\\\"created_at\\\":\\\"2026-04-01 07:44:17\\\",\\\"updated_at\\\":\\\"2026-04-01 07:44:17\\\"}\"', '2026-09-13 17:17:48');
INSERT INTO `tbl_audit_log` VALUES ('134', '1', '28', NULL, 'archived', NULL, '\"{\\\"id\\\":28,\\\"admin_id\\\":1,\\\"subject_id\\\":9,\\\"title\\\":\\\"FTF Modality\\\",\\\"content\\\":\\\"April 8\\\\u201318\\\",\\\"due_date\\\":null,\\\"due_time\\\":null,\\\"end_date\\\":null,\\\"status\\\":\\\"active\\\",\\\"created_at\\\":\\\"2026-04-08 04:04:01\\\",\\\"updated_at\\\":\\\"2026-04-08 04:05:11\\\"}\"', '2026-09-13 17:17:51');
INSERT INTO `tbl_audit_log` VALUES ('135', '1', '15', NULL, 'archived', NULL, '\"{\\\"id\\\":15,\\\"admin_id\\\":1,\\\"subject_id\\\":9,\\\"title\\\":\\\"Books:\\\",\\\"content\\\":\\\"SCIETS - 320\\\\u20b1\\\\r\\\\nCONTWO - 300\\\\u20b1\\\\r\\\\nRIZAL - 360\\\\u20b1\\\\r\\\\nPEHEF2\\\\/INDAYOG - 350\\\\u20b1\\\",\\\"due_date\\\":null,\\\"due_time\\\":null,\\\"end_date\\\":null,\\\"status\\\":\\\"active\\\",\\\"created_at\\\":\\\"2026-04-01 07:48:12\\\",\\\"updated_at\\\":\\\"2026-04-01 07:48:12\\\"}\"', '2026-09-13 17:17:54');
INSERT INTO `tbl_audit_log` VALUES ('136', '1', '16', NULL, 'archived', NULL, '\"{\\\"id\\\":16,\\\"admin_id\\\":1,\\\"subject_id\\\":8,\\\"title\\\":\\\"Study and master executing the:\\\",\\\"content\\\":\\\"Polka sa Nayon\\\",\\\"due_date\\\":null,\\\"due_time\\\":null,\\\"end_date\\\":null,\\\"status\\\":\\\"active\\\",\\\"created_at\\\":\\\"2026-04-01 07:48:35\\\",\\\"updated_at\\\":\\\"2026-04-01 18:57:34\\\"}\"', '2026-09-13 17:17:55');
INSERT INTO `tbl_audit_log` VALUES ('137', '1', NULL, '24', 'hard_deleted', '\"{\\\"id\\\":24,\\\"admin_id\\\":1,\\\"subject_id\\\":2,\\\"title\\\":\\\"Quiz\\\",\\\"content\\\":\\\"Coverage: Lesson 7\\\",\\\"due_date\\\":\\\"2026-04-06\\\",\\\"due_time\\\":\\\"15:00:00\\\",\\\"end_date\\\":null,\\\"status\\\":\\\"archived\\\",\\\"created_at\\\":\\\"2026-04-05 04:26:34\\\",\\\"updated_at\\\":\\\"2026-04-11 00:22:44\\\"}\"', NULL, '2026-09-13 17:18:01');
INSERT INTO `tbl_audit_log` VALUES ('138', '1', NULL, '14', 'hard_deleted', '\"{\\\"id\\\":14,\\\"admin_id\\\":1,\\\"subject_id\\\":8,\\\"title\\\":\\\"Submission\\\",\\\"content\\\":\\\"Pgs. 13, 14, 15, 25, 26, 27\\\",\\\"due_date\\\":\\\"2026-04-08\\\",\\\"due_time\\\":\\\"22:00:00\\\",\\\"end_date\\\":null,\\\"status\\\":\\\"archived\\\",\\\"created_at\\\":\\\"2026-04-01 07:45:56\\\",\\\"updated_at\\\":\\\"2026-04-08 18:39:43\\\"}\"', NULL, '2026-09-13 17:18:05');
INSERT INTO `tbl_audit_log` VALUES ('139', '1', NULL, '27', 'hard_deleted', '\"{\\\"id\\\":27,\\\"admin_id\\\":1,\\\"subject_id\\\":7,\\\"title\\\":\\\"Midterm Exam\\\",\\\"content\\\":\\\"For those who don\'t have books.\\\",\\\"due_date\\\":\\\"2026-04-08\\\",\\\"due_time\\\":\\\"14:00:00\\\",\\\"end_date\\\":null,\\\"status\\\":\\\"archived\\\",\\\"created_at\\\":\\\"2026-04-08 02:01:00\\\",\\\"updated_at\\\":\\\"2026-04-10 01:11:52\\\"}\"', NULL, '2026-09-13 17:18:09');
INSERT INTO `tbl_audit_log` VALUES ('140', '1', NULL, '12', 'hard_deleted', '\"{\\\"id\\\":12,\\\"admin_id\\\":1,\\\"subject_id\\\":7,\\\"title\\\":\\\"Submission\\\",\\\"content\\\":\\\"Chapter 1 to 3\\\",\\\"due_date\\\":\\\"2026-04-08\\\",\\\"due_time\\\":\\\"14:00:00\\\",\\\"end_date\\\":null,\\\"status\\\":\\\"archived\\\",\\\"created_at\\\":\\\"2026-04-01 07:39:24\\\",\\\"updated_at\\\":\\\"2026-04-08 18:39:47\\\"}\"', NULL, '2026-09-13 17:18:12');
INSERT INTO `tbl_audit_log` VALUES ('141', '1', NULL, '4', 'hard_deleted', '\"{\\\"id\\\":4,\\\"admin_id\\\":1,\\\"subject_id\\\":1,\\\"title\\\":\\\"Submission\\\",\\\"content\\\":\\\"Chapter 1\\\",\\\"due_date\\\":\\\"2026-04-08\\\",\\\"due_time\\\":\\\"10:00:00\\\",\\\"end_date\\\":null,\\\"status\\\":\\\"archived\\\",\\\"created_at\\\":\\\"2026-04-01 07:26:39\\\",\\\"updated_at\\\":\\\"2026-04-08 18:39:49\\\"}\"', NULL, '2026-09-13 17:18:16');
INSERT INTO `tbl_audit_log` VALUES ('142', '1', NULL, '25', 'hard_deleted', '\"{\\\"id\\\":25,\\\"admin_id\\\":1,\\\"subject_id\\\":9,\\\"title\\\":\\\"Holiday - Araw ng Kagitingan\\\",\\\"content\\\":\\\"Malamang walang pasok\\\",\\\"due_date\\\":\\\"2026-04-09\\\",\\\"due_time\\\":null,\\\"end_date\\\":null,\\\"status\\\":\\\"archived\\\",\\\"created_at\\\":\\\"2026-04-06 21:42:14\\\",\\\"updated_at\\\":\\\"2026-04-10 22:07:42\\\"}\"', NULL, '2026-09-13 17:18:19');
INSERT INTO `tbl_audit_log` VALUES ('143', '1', NULL, '5', 'hard_deleted', '\"{\\\"id\\\":5,\\\"admin_id\\\":1,\\\"subject_id\\\":2,\\\"title\\\":\\\"Midterm Exam\\\",\\\"content\\\":\\\"Coverage: Lesson 1 to 5\\\",\\\"due_date\\\":\\\"2026-04-13\\\",\\\"due_time\\\":\\\"13:00:00\\\",\\\"end_date\\\":null,\\\"status\\\":\\\"archived\\\",\\\"created_at\\\":\\\"2026-04-01 07:28:07\\\",\\\"updated_at\\\":\\\"2026-09-13 17:15:34\\\"}\"', NULL, '2026-09-13 17:18:24');
INSERT INTO `tbl_audit_log` VALUES ('144', '1', NULL, '6', 'hard_deleted', '\"{\\\"id\\\":6,\\\"admin_id\\\":1,\\\"subject_id\\\":3,\\\"title\\\":\\\"Quiz 1\\\",\\\"content\\\":\\\"Coverage: \\\\r\\\\n1. Simple Interest\\\\r\\\\n2. Compound Interest\\\\r\\\\n3. Effective Rate of Interest\\\\r\\\\n4. Equation of Value\\\\r\\\\n5. Discrete Payment\\\\r\\\\n6. Continuous Compounding Interest\\\\r\\\\n7. Banker s Discount\\\",\\\"due_date\\\":\\\"2026-04-13\\\",\\\"due_time\\\":\\\"17:00:00\\\",\\\"end_date\\\":null,\\\"status\\\":\\\"archived\\\",\\\"created_at\\\":\\\"2026-04-01 07:31:32\\\",\\\"updated_at\\\":\\\"2026-09-13 17:15:34\\\"}\"', NULL, '2026-09-13 17:18:26');
INSERT INTO `tbl_audit_log` VALUES ('145', '1', NULL, '1', 'hard_deleted', '\"{\\\"id\\\":1,\\\"admin_id\\\":1,\\\"subject_id\\\":1,\\\"title\\\":\\\"Reporting\\\",\\\"content\\\":\\\"Chapter 7\\\",\\\"due_date\\\":\\\"2026-04-13\\\",\\\"due_time\\\":\\\"07:00:00\\\",\\\"end_date\\\":null,\\\"status\\\":\\\"archived\\\",\\\"created_at\\\":\\\"2026-04-01 05:37:49\\\",\\\"updated_at\\\":\\\"2026-09-13 17:15:34\\\"}\"', NULL, '2026-09-13 17:18:29');
INSERT INTO `tbl_audit_log` VALUES ('146', '1', NULL, '2', 'hard_deleted', '\"{\\\"id\\\":2,\\\"admin_id\\\":1,\\\"subject_id\\\":4,\\\"title\\\":\\\"Midterm Review\\\",\\\"content\\\":\\\"Topics to be review: Transistors and etc.\\\",\\\"due_date\\\":\\\"2026-04-14\\\",\\\"due_time\\\":\\\"10:30:00\\\",\\\"end_date\\\":null,\\\"status\\\":\\\"archived\\\",\\\"created_at\\\":\\\"2026-04-01 05:57:52\\\",\\\"updated_at\\\":\\\"2026-09-13 17:15:34\\\"}\"', NULL, '2026-09-13 17:18:32');
INSERT INTO `tbl_audit_log` VALUES ('147', '1', NULL, '8', 'hard_deleted', '\"{\\\"id\\\":8,\\\"admin_id\\\":1,\\\"subject_id\\\":5,\\\"title\\\":\\\"Presentation\\\",\\\"content\\\":\\\"Topic 4\\\",\\\"due_date\\\":\\\"2026-04-14\\\",\\\"due_time\\\":\\\"14:30:00\\\",\\\"end_date\\\":null,\\\"status\\\":\\\"archived\\\",\\\"created_at\\\":\\\"2026-04-01 07:34:48\\\",\\\"updated_at\\\":\\\"2026-09-13 17:15:34\\\"}\"', NULL, '2026-09-13 17:18:35');
INSERT INTO `tbl_audit_log` VALUES ('148', '1', NULL, '21', 'hard_deleted', '\"{\\\"id\\\":21,\\\"admin_id\\\":1,\\\"subject_id\\\":6,\\\"title\\\":\\\"Seatwork 2\\\",\\\"content\\\":\\\"Coverage: Chapter 3\\\",\\\"due_date\\\":\\\"2026-04-15\\\",\\\"due_time\\\":\\\"10:00:00\\\",\\\"end_date\\\":null,\\\"status\\\":\\\"archived\\\",\\\"created_at\\\":\\\"2026-04-01 18:52:43\\\",\\\"updated_at\\\":\\\"2026-09-13 17:15:34\\\"}\"', NULL, '2026-09-13 17:18:38');
INSERT INTO `tbl_audit_log` VALUES ('149', '1', NULL, '9', 'hard_deleted', '\"{\\\"id\\\":9,\\\"admin_id\\\":1,\\\"subject_id\\\":6,\\\"title\\\":\\\"Discussion\\\",\\\"content\\\":\\\"Final topic.\\\",\\\"due_date\\\":\\\"2026-04-15\\\",\\\"due_time\\\":\\\"10:00:00\\\",\\\"end_date\\\":null,\\\"status\\\":\\\"archived\\\",\\\"created_at\\\":\\\"2026-04-01 07:35:33\\\",\\\"updated_at\\\":\\\"2026-09-13 17:15:34\\\"}\"', NULL, '2026-09-13 17:18:41');
INSERT INTO `tbl_audit_log` VALUES ('150', '1', NULL, '10', 'hard_deleted', '\"{\\\"id\\\":10,\\\"admin_id\\\":1,\\\"subject_id\\\":7,\\\"title\\\":\\\"Reporting\\\",\\\"content\\\":\\\"Chapter 6\\\",\\\"due_date\\\":\\\"2026-04-15\\\",\\\"due_time\\\":\\\"14:00:00\\\",\\\"end_date\\\":null,\\\"status\\\":\\\"archived\\\",\\\"created_at\\\":\\\"2026-04-01 07:38:29\\\",\\\"updated_at\\\":\\\"2026-09-13 17:15:34\\\"}\"', NULL, '2026-09-13 17:18:43');
INSERT INTO `tbl_audit_log` VALUES ('151', '1', NULL, '26', 'hard_deleted', '\"{\\\"id\\\":26,\\\"admin_id\\\":1,\\\"subject_id\\\":4,\\\"title\\\":\\\"Topics\\\",\\\"content\\\":\\\"1. Fundamentals of DC circuits \\\\u2714\\\\ufe0f\\\\r\\\\n2. Diodes \\\\u2714\\\\ufe0f\\\\r\\\\n3. Introduction to transistors \\\\u2714\\\\ufe0f\\\\r\\\\n4. The transistor switch \\\\u2714\\\\ufe0f\\\\r\\\\n5. Fundamentals of AC circuits \\\\u2714\\\\ufe0f\\\\r\\\\n6. Filters \\\\u2714\\\\ufe0f\\\\r\\\\n7. Resonant circuits \\\\u2714\\\\ufe0f\\\\r\\\\n8. Transistor amplifiers\\\\r\\\\n9. Oscillators\\\\r\\\\n10. The transformer\\\\r\\\\n11. Power supply circuits\\\",\\\"due_date\\\":null,\\\"due_time\\\":null,\\\"end_date\\\":null,\\\"status\\\":\\\"archived\\\",\\\"created_at\\\":\\\"2026-04-07 05:08:32\\\",\\\"updated_at\\\":\\\"2026-09-13 17:17:46\\\"}\"', NULL, '2026-09-13 17:18:45');
INSERT INTO `tbl_audit_log` VALUES ('152', '1', NULL, '13', 'hard_deleted', '\"{\\\"id\\\":13,\\\"admin_id\\\":1,\\\"subject_id\\\":3,\\\"title\\\":\\\"Notebook\\\",\\\"content\\\":\\\"Provide a notebook for additional points.\\\",\\\"due_date\\\":null,\\\"due_time\\\":null,\\\"end_date\\\":null,\\\"status\\\":\\\"archived\\\",\\\"created_at\\\":\\\"2026-04-01 07:44:17\\\",\\\"updated_at\\\":\\\"2026-09-13 17:17:48\\\"}\"', NULL, '2026-09-13 17:18:48');
INSERT INTO `tbl_audit_log` VALUES ('153', '1', NULL, '28', 'hard_deleted', '\"{\\\"id\\\":28,\\\"admin_id\\\":1,\\\"subject_id\\\":9,\\\"title\\\":\\\"FTF Modality\\\",\\\"content\\\":\\\"April 8\\\\u201318\\\",\\\"due_date\\\":null,\\\"due_time\\\":null,\\\"end_date\\\":null,\\\"status\\\":\\\"archived\\\",\\\"created_at\\\":\\\"2026-04-08 04:04:01\\\",\\\"updated_at\\\":\\\"2026-09-13 17:17:51\\\"}\"', NULL, '2026-09-13 17:18:51');
INSERT INTO `tbl_audit_log` VALUES ('154', '1', NULL, '15', 'hard_deleted', '\"{\\\"id\\\":15,\\\"admin_id\\\":1,\\\"subject_id\\\":9,\\\"title\\\":\\\"Books:\\\",\\\"content\\\":\\\"SCIETS - 320\\\\u20b1\\\\r\\\\nCONTWO - 300\\\\u20b1\\\\r\\\\nRIZAL - 360\\\\u20b1\\\\r\\\\nPEHEF2\\\\/INDAYOG - 350\\\\u20b1\\\",\\\"due_date\\\":null,\\\"due_time\\\":null,\\\"end_date\\\":null,\\\"status\\\":\\\"archived\\\",\\\"created_at\\\":\\\"2026-04-01 07:48:12\\\",\\\"updated_at\\\":\\\"2026-09-13 17:17:54\\\"}\"', NULL, '2026-09-13 17:18:54');
INSERT INTO `tbl_audit_log` VALUES ('155', '1', NULL, '16', 'hard_deleted', '\"{\\\"id\\\":16,\\\"admin_id\\\":1,\\\"subject_id\\\":8,\\\"title\\\":\\\"Study and master executing the:\\\",\\\"content\\\":\\\"Polka sa Nayon\\\",\\\"due_date\\\":null,\\\"due_time\\\":null,\\\"end_date\\\":null,\\\"status\\\":\\\"archived\\\",\\\"created_at\\\":\\\"2026-04-01 07:48:35\\\",\\\"updated_at\\\":\\\"2026-09-13 17:17:55\\\"}\"', NULL, '2026-09-13 17:18:57');
INSERT INTO `tbl_audit_log` VALUES ('156', '1', '29', NULL, 'created', NULL, '{\"id\":29,\"admin_id\":1,\"subject_id\":9,\"title\":\"safasas\",\"content\":\"sadasasffsa\",\"due_date\":null,\"due_time\":null,\"end_date\":null,\"file_path\":null,\"status\":\"active\",\"created_at\":\"2026-09-13 18:01:43\",\"updated_at\":\"2026-09-13 18:01:43\"}', '2026-09-13 18:01:43');
INSERT INTO `tbl_audit_log` VALUES ('157', '1', '29', NULL, 'updated', '{\"id\":29,\"admin_id\":1,\"subject_id\":9,\"title\":\"safasas\",\"content\":\"sadasasffsa\",\"due_date\":null,\"due_time\":null,\"end_date\":null,\"file_path\":null,\"status\":\"active\",\"created_at\":\"2026-09-13 18:01:43\",\"updated_at\":\"2026-09-13 18:01:43\"}', '{\"id\":29,\"admin_id\":1,\"subject_id\":9,\"title\":\"CPE-3B\",\"content\":\"Class Schedule\",\"due_date\":null,\"due_time\":null,\"end_date\":null,\"file_path\":\"assets/uploads/1789293785_ClassSchedule1stSemester.png\",\"status\":\"active\",\"created_at\":\"2026-09-13 18:01:43\",\"updated_at\":\"2026-09-13 18:03:06\"}', '2026-09-13 18:03:06');
INSERT INTO `tbl_audit_log` VALUES ('158', '1', '10', NULL, 'created', NULL, '\"Added subject: CPENG311\"', '2026-09-13 18:04:32');
INSERT INTO `tbl_audit_log` VALUES ('159', '1', '11', NULL, 'created', NULL, '\"Added subject: CPENG312\"', '2026-09-13 18:06:22');
INSERT INTO `tbl_audit_log` VALUES ('160', '1', '10', NULL, 'updated', '\"{\\\"id\\\":10,\\\"code\\\":\\\"CPENG311\\\",\\\"name\\\":\\\"Logic Circuits and Design\\\",\\\"professor\\\":\\\"Engr. A.U.\\\",\\\"schedule\\\":\\\"M\\\\/W 7:00 AM - 9:00 AM\\\",\\\"color_theme\\\":\\\"bg-sciets\\\",\\\"status\\\":\\\"active\\\",\\\"created_at\\\":\\\"2026-09-13 18:04:32\\\"}\"', '\"{\\\"id\\\":10,\\\"code\\\":\\\"CPENG311\\\",\\\"name\\\":\\\"Logic Circuits and Design\\\",\\\"professor\\\":\\\"Engr. A.U.\\\",\\\"schedule\\\":\\\"M\\\\/W 7:00 AM - 10:00 AM\\\",\\\"color_theme\\\":\\\"bg-sciets\\\",\\\"status\\\":\\\"active\\\",\\\"created_at\\\":\\\"2026-09-13 18:04:32\\\"}\"', '2026-09-13 18:06:33');
INSERT INTO `tbl_audit_log` VALUES ('161', '1', '12', NULL, 'created', NULL, '\"Added subject: CPENG313\"', '2026-09-13 18:07:09');
INSERT INTO `tbl_audit_log` VALUES ('162', '1', '13', NULL, 'created', NULL, '\"Added subject: CPENG314\"', '2026-09-13 18:07:59');
INSERT INTO `tbl_audit_log` VALUES ('163', '1', '14', NULL, 'created', NULL, '\"Added subject: CPENG315\"', '2026-09-13 18:08:32');
INSERT INTO `tbl_audit_log` VALUES ('164', '1', '15', NULL, 'created', NULL, '\"Added subject: CPEELEC1\"', '2026-09-13 18:09:15');
INSERT INTO `tbl_audit_log` VALUES ('165', '1', '16', NULL, 'created', NULL, '\"Added subject: CENENVIR\"', '2026-09-13 18:10:04');
INSERT INTO `tbl_audit_log` VALUES ('166', '1', '17', NULL, 'created', NULL, '\"Added subject: MXSIGSEN\"', '2026-09-13 18:10:30');
INSERT INTO `tbl_audit_log` VALUES ('167', '1', '30', NULL, 'created', NULL, '{\"id\":30,\"admin_id\":1,\"subject_id\":10,\"title\":\"Lecture\",\"content\":\"Boolean Forms and Universal Gates Realization\",\"due_date\":\"2026-09-14\",\"due_time\":\"07:00:00\",\"end_date\":null,\"file_path\":\"assets/uploads/1789294410_Module1-Lesson4BooleanFormsandUniversalGatesRealization.pdf\",\"status\":\"active\",\"created_at\":\"2026-09-13 18:13:30\",\"updated_at\":\"2026-09-13 18:13:30\"}', '2026-09-13 18:13:30');


DROP TABLE IF EXISTS `tbl_subjects`;
CREATE TABLE `tbl_subjects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `professor` varchar(100) DEFAULT NULL,
  `schedule` varchar(100) DEFAULT NULL,
  `color_theme` varchar(50) DEFAULT 'bg-other',
  `status` enum('active','archived') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `tbl_subjects` VALUES ('1', 'SCIETS', 'Science, Technology and Society', 'Engr. Jose L.', 'M 9 AM - 12 PM', 'bg-sciets', 'archived', '2026-04-01 05:31:07');
INSERT INTO `tbl_subjects` VALUES ('2', 'CONTWO', 'The Contemporary World', 'Dr. Arnold A.', 'M 1 PM - 4 PM', 'bg-contwo', 'archived', '2026-04-01 05:31:07');
INSERT INTO `tbl_subjects` VALUES ('3', 'ENECO', 'Engineering Economy', 'Engr. Allen Y.', 'M 5 PM - 8 PM', 'bg-eneco', 'archived', '2026-04-01 05:31:07');
INSERT INTO `tbl_subjects` VALUES ('4', 'ECENG', 'Fundamentals of Electronic Circuits', 'Engr. Nelson D.', 'T/TH 10 AM - 1 PM', 'bg-eceng', 'archived', '2026-04-01 05:31:07');
INSERT INTO `tbl_subjects` VALUES ('5', 'SOFTDES', 'Software Design', 'Engr. Jane A.', 'T/TH 2:30 PM - 5:30 PM', 'bg-softdes', 'archived', '2026-04-01 05:31:07');
INSERT INTO `tbl_subjects` VALUES ('6', 'NUMERICAL', 'Numerical Methods', 'Engr. Bernard F.', 'W 10 AM - 1 PM', 'bg-numerical', 'archived', '2026-04-01 05:31:07');
INSERT INTO `tbl_subjects` VALUES ('7', 'RIZAL', 'Life and Works of Rizal', 'Prof. Matthew N.', 'W 2 PM - 5 PM', 'bg-rizal', 'archived', '2026-04-01 05:31:07');
INSERT INTO `tbl_subjects` VALUES ('8', 'PEHEF2', 'Physical Activity Towards Health and Fitness II', 'Prof. Angela B.', 'TH 7 AM - 9 AM', 'bg-pehef2', 'archived', '2026-04-01 05:31:07');
INSERT INTO `tbl_subjects` VALUES ('9', 'OTHER', 'General Announcements', 'N/A', 'N/A', 'bg-other', 'active', '2026-04-01 05:31:07');
INSERT INTO `tbl_subjects` VALUES ('10', 'CPENG311', 'Logic Circuits and Design', 'Engr. A.U.', 'M/W 7:00 AM - 10:00 AM', 'bg-sciets', 'active', '2026-09-13 18:04:32');
INSERT INTO `tbl_subjects` VALUES ('11', 'CPENG312', 'Data and Digital Communications', 'Engr. J.F.', 'T/TH 10:00 A.M - 11:30 A.M', 'bg-contwo', 'active', '2026-09-13 18:06:22');
INSERT INTO `tbl_subjects` VALUES ('12', 'CPENG313', 'CPE Drafting & Design', 'Engr. B.F.', 'M/W 7:00 A.M - 10:00 A.M', 'bg-eneco', 'active', '2026-09-13 18:07:09');
INSERT INTO `tbl_subjects` VALUES ('13', 'CPENG314', 'Operating Systems', 'Engr. M.F.', 'T/TH 1:00 P.M - 4:00 P.M', 'bg-eceng', 'active', '2026-09-13 18:07:59');
INSERT INTO `tbl_subjects` VALUES ('14', 'CPENG315', 'Discrete Mathematics', 'Engr. B.F.', 'M/W 1:00 P.M - 2:30 P.M', 'bg-softdes', 'active', '2026-09-13 18:08:32');
INSERT INTO `tbl_subjects` VALUES ('15', 'CPEELEC1', 'Cognate/Elective Course 1', 'Engr. M.F.', 'M/W 2:30 P.M - 4:00 P.M', 'bg-numerical', 'active', '2026-09-13 18:09:15');
INSERT INTO `tbl_subjects` VALUES ('16', 'CENENVIR', 'Environmental Science and Engineering', 'Engr. J.A.', 'T/TH 4:00 P.M - 5:30 P.M', 'bg-rizal', 'active', '2026-09-13 18:10:04');
INSERT INTO `tbl_subjects` VALUES ('17', 'MXSIGSEN', 'Fundamentals of Mixed Signal & Sensors', 'Engr. B.F.', 'M/W 10:00 A.M - 11:30 A.M', 'bg-pehef2', 'active', '2026-09-13 18:10:30');


