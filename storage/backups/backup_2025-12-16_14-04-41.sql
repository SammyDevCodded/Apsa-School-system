-- School ERP Database Backup
-- Generated on: 2025-12-16 14:04:41


-- Table structure for table `academic_years`
CREATE TABLE `academic_years` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_current` tinyint(1) DEFAULT '0',
  `status` enum('active','inactive') DEFAULT 'active',
  `term` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `academic_years`
INSERT INTO `academic_years` VALUES ('3', '2026-2027', '2026-09-01', '2027-06-30', '0', 'inactive', NULL, '2025-10-22 13:09:16', '2025-10-22 13:14:37');
INSERT INTO `academic_years` VALUES ('2', '2025-2026', '2025-09-01', '2026-06-30', '1', 'active', '1st Term', '2025-10-12 03:11:22', '2025-10-22 13:54:27');


-- Table structure for table `attendance`
CREATE TABLE `attendance` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_id` int NOT NULL,
  `date` date NOT NULL,
  `status` enum('present','absent','late') DEFAULT 'present',
  `remarks` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_student_date` (`student_id`,`date`)
) ENGINE=MyISAM AUTO_INCREMENT=189 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `attendance`
INSERT INTO `attendance` VALUES ('1', '1', '2025-09-12', 'late', 'Arrived 10 minutes late', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('2', '2', '2025-09-12', 'late', 'Arrived 10 minutes late', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('3', '3', '2025-09-12', 'absent', 'Sick leave', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('4', '1', '2025-09-15', 'absent', 'Sick leave', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('5', '2', '2025-09-15', 'late', 'Arrived 10 minutes late', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('6', '3', '2025-09-15', 'present', '', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('7', '1', '2025-09-16', 'absent', 'Sick leave', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('8', '2', '2025-09-16', 'absent', 'Sick leave', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('9', '3', '2025-09-16', 'absent', 'Sick leave', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('10', '1', '2025-09-17', 'present', '', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('11', '2', '2025-09-17', 'absent', 'Sick leave', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('12', '3', '2025-09-17', 'absent', 'Family emergency', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('13', '1', '2025-09-18', 'absent', 'Family emergency', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('14', '2', '2025-09-18', 'absent', 'Sick leave', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('15', '3', '2025-09-18', 'late', 'Arrived 10 minutes late', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('16', '1', '2025-09-19', 'present', '', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('17', '2', '2025-09-19', 'absent', 'Sick leave', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('18', '3', '2025-09-19', 'present', '', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('19', '1', '2025-09-22', 'late', 'Arrived 10 minutes late', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('20', '2', '2025-09-22', 'late', 'Arrived 10 minutes late', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('21', '3', '2025-09-22', 'absent', 'Sick leave', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('22', '1', '2025-09-23', 'late', 'Arrived 10 minutes late', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('23', '2', '2025-09-23', 'late', 'Arrived 10 minutes late', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('24', '3', '2025-09-23', 'absent', 'Family emergency', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('25', '1', '2025-09-24', 'late', 'Arrived 10 minutes late', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('26', '2', '2025-09-24', 'present', '', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('27', '3', '2025-09-24', 'absent', 'Sick leave', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('28', '1', '2025-09-25', 'present', '', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('29', '2', '2025-09-25', 'late', 'Arrived 10 minutes late', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('30', '3', '2025-09-25', 'present', '', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('31', '1', '2025-09-26', 'absent', 'Sick leave', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('32', '2', '2025-09-26', 'present', '', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('33', '3', '2025-09-26', 'present', '', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('34', '1', '2025-09-29', 'absent', 'Family emergency', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('35', '2', '2025-09-29', 'absent', 'Sick leave', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('36', '3', '2025-09-29', 'absent', 'Family emergency', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('37', '1', '2025-09-30', 'present', '', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('38', '2', '2025-09-30', 'present', '', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('39', '3', '2025-09-30', 'absent', 'Family emergency', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('40', '1', '2025-10-01', 'present', '', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('41', '2', '2025-10-01', 'present', '', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('42', '3', '2025-10-01', 'late', 'Arrived 10 minutes late', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('43', '1', '2025-10-02', 'absent', 'Sick leave', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('44', '2', '2025-10-02', 'absent', 'Family emergency', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('45', '3', '2025-10-02', 'late', 'Arrived 10 minutes late', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('46', '1', '2025-10-03', 'late', 'Arrived 10 minutes late', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('47', '2', '2025-10-03', 'present', '', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('48', '3', '2025-10-03', 'late', 'Arrived 10 minutes late', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('49', '1', '2025-10-06', 'absent', 'Sick leave', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('50', '2', '2025-10-06', 'absent', 'Family emergency', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('51', '3', '2025-10-06', 'present', '', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('52', '1', '2025-10-07', 'absent', 'Sick leave', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('53', '2', '2025-10-07', 'late', 'Arrived 10 minutes late', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('54', '3', '2025-10-07', 'absent', 'Sick leave', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('55', '1', '2025-10-08', 'late', 'Arrived 10 minutes late', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('56', '2', '2025-10-08', 'absent', 'Family emergency', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('57', '3', '2025-10-08', 'absent', 'Sick leave', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('58', '1', '2025-10-09', 'absent', 'Sick leave', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('59', '2', '2025-10-09', 'present', '', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('60', '3', '2025-10-09', 'absent', 'Sick leave', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('61', '1', '2025-10-10', 'present', '', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('62', '2', '2025-10-10', 'present', '', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('63', '3', '2025-10-10', 'absent', 'Sick leave', '2025-10-12 02:30:26', '2025-10-12 02:30:26');
INSERT INTO `attendance` VALUES ('64', '1', '2025-10-13', 'present', '', '2025-10-13 21:23:54', '2025-10-13 21:23:54');
INSERT INTO `attendance` VALUES ('65', '2', '2025-10-13', 'absent', '', '2025-10-13 21:23:54', '2025-10-13 21:23:54');
INSERT INTO `attendance` VALUES ('66', '3', '2025-10-13', 'present', '', '2025-10-13 21:23:54', '2025-10-13 21:23:54');
INSERT INTO `attendance` VALUES ('67', '4', '2025-10-17', 'present', 'very early', '2025-10-17 14:08:56', '2025-10-17 14:08:56');
INSERT INTO `attendance` VALUES ('68', '5', '2025-10-17', 'present', 'Early', '2025-10-17 14:08:56', '2025-10-17 14:08:56');
INSERT INTO `attendance` VALUES ('69', '6', '2025-10-17', 'present', 'Early', '2025-10-17 14:08:56', '2025-10-17 14:08:56');
INSERT INTO `attendance` VALUES ('70', '7', '2025-10-17', 'present', 'good', '2025-10-17 14:08:56', '2025-10-17 14:08:56');
INSERT INTO `attendance` VALUES ('71', '8', '2025-10-17', 'present', 'on time', '2025-10-17 14:08:56', '2025-10-17 14:08:56');
INSERT INTO `attendance` VALUES ('72', '10', '2025-10-17', 'present', 'keep up', '2025-10-17 14:08:56', '2025-10-17 14:08:56');
INSERT INTO `attendance` VALUES ('73', '4', '2025-10-16', 'absent', '', '2025-10-17 14:46:27', '2025-10-17 14:46:27');
INSERT INTO `attendance` VALUES ('74', '5', '2025-10-16', 'present', '', '2025-10-17 14:46:27', '2025-10-17 14:46:27');
INSERT INTO `attendance` VALUES ('75', '6', '2025-10-16', 'late', '', '2025-10-17 14:46:27', '2025-10-17 14:46:27');
INSERT INTO `attendance` VALUES ('76', '7', '2025-10-16', 'present', '', '2025-10-17 14:46:27', '2025-10-17 14:46:27');
INSERT INTO `attendance` VALUES ('77', '8', '2025-10-16', 'absent', '', '2025-10-17 14:46:27', '2025-10-17 14:46:27');
INSERT INTO `attendance` VALUES ('78', '10', '2025-10-16', 'present', '', '2025-10-17 14:46:27', '2025-10-17 14:46:27');
INSERT INTO `attendance` VALUES ('79', '4', '2025-10-20', 'present', '', '2025-10-20 00:57:06', '2025-10-20 00:57:06');
INSERT INTO `attendance` VALUES ('80', '5', '2025-10-20', 'present', '', '2025-10-20 00:57:06', '2025-10-20 00:57:06');
INSERT INTO `attendance` VALUES ('81', '6', '2025-10-20', 'present', '', '2025-10-20 00:57:06', '2025-10-20 00:57:06');
INSERT INTO `attendance` VALUES ('82', '7', '2025-10-20', 'present', '', '2025-10-20 00:57:06', '2025-10-20 00:57:06');
INSERT INTO `attendance` VALUES ('83', '8', '2025-10-20', 'present', '', '2025-10-20 00:57:06', '2025-10-20 00:57:06');
INSERT INTO `attendance` VALUES ('84', '10', '2025-10-20', 'present', '', '2025-10-20 00:57:06', '2025-10-20 00:57:06');
INSERT INTO `attendance` VALUES ('85', '1', '2025-10-20', 'present', '', '2025-10-20 00:57:19', '2025-10-20 00:57:19');
INSERT INTO `attendance` VALUES ('86', '2', '2025-10-20', 'present', '', '2025-10-20 00:57:26', '2025-10-20 00:57:26');
INSERT INTO `attendance` VALUES ('87', '3', '2025-10-20', 'present', '', '2025-10-20 00:57:34', '2025-10-20 00:57:34');
INSERT INTO `attendance` VALUES ('88', '11', '2025-10-20', 'present', '', '2025-10-20 00:57:34', '2025-10-20 00:57:34');
INSERT INTO `attendance` VALUES ('89', '1', '2025-10-23', 'present', '', '2025-10-23 22:15:01', '2025-10-23 22:15:01');
INSERT INTO `attendance` VALUES ('90', '12', '2025-10-23', 'present', '', '2025-10-23 22:15:01', '2025-10-23 22:15:01');
INSERT INTO `attendance` VALUES ('91', '21', '2025-10-23', 'present', '', '2025-10-23 22:15:01', '2025-10-23 22:15:01');
INSERT INTO `attendance` VALUES ('92', '35', '2025-10-23', 'present', '', '2025-10-23 22:15:01', '2025-10-23 22:15:01');
INSERT INTO `attendance` VALUES ('93', '49', '2025-10-23', 'present', '', '2025-10-23 22:15:01', '2025-10-23 22:15:01');
INSERT INTO `attendance` VALUES ('94', '63', '2025-10-23', 'present', '', '2025-10-23 22:15:01', '2025-10-23 22:15:01');
INSERT INTO `attendance` VALUES ('95', '77', '2025-10-23', 'present', '', '2025-10-23 22:15:01', '2025-10-23 22:15:01');
INSERT INTO `attendance` VALUES ('96', '91', '2025-10-23', 'present', '', '2025-10-23 22:15:01', '2025-10-23 22:15:01');
INSERT INTO `attendance` VALUES ('97', '105', '2025-10-23', 'present', '', '2025-10-23 22:15:01', '2025-10-23 22:15:01');
INSERT INTO `attendance` VALUES ('98', '2', '2025-10-23', 'present', '', '2025-10-23 22:15:28', '2025-10-23 22:15:28');
INSERT INTO `attendance` VALUES ('99', '13', '2025-10-23', 'present', '', '2025-10-23 22:15:28', '2025-10-23 22:15:28');
INSERT INTO `attendance` VALUES ('100', '22', '2025-10-23', 'present', '', '2025-10-23 22:15:28', '2025-10-23 22:15:28');
INSERT INTO `attendance` VALUES ('101', '36', '2025-10-23', 'present', '', '2025-10-23 22:15:28', '2025-10-23 22:15:28');
INSERT INTO `attendance` VALUES ('102', '50', '2025-10-23', 'present', '', '2025-10-23 22:15:28', '2025-10-23 22:15:28');
INSERT INTO `attendance` VALUES ('103', '64', '2025-10-23', 'present', '', '2025-10-23 22:15:28', '2025-10-23 22:15:28');
INSERT INTO `attendance` VALUES ('104', '78', '2025-10-23', 'present', '', '2025-10-23 22:15:28', '2025-10-23 22:15:28');
INSERT INTO `attendance` VALUES ('105', '92', '2025-10-23', 'present', '', '2025-10-23 22:15:28', '2025-10-23 22:15:28');
INSERT INTO `attendance` VALUES ('106', '106', '2025-10-23', 'present', '', '2025-10-23 22:15:28', '2025-10-23 22:15:28');
INSERT INTO `attendance` VALUES ('107', '3', '2025-11-02', 'present', '', '2025-11-02 15:25:15', '2025-11-02 15:25:15');
INSERT INTO `attendance` VALUES ('108', '11', '2025-11-02', 'absent', '', '2025-11-02 15:25:15', '2025-11-02 15:25:15');
INSERT INTO `attendance` VALUES ('109', '14', '2025-11-02', 'present', '', '2025-11-02 15:25:15', '2025-11-02 15:25:15');
INSERT INTO `attendance` VALUES ('110', '23', '2025-11-02', 'late', '', '2025-11-02 15:25:15', '2025-11-02 15:25:15');
INSERT INTO `attendance` VALUES ('111', '37', '2025-11-02', 'present', '', '2025-11-02 15:25:15', '2025-11-02 15:25:15');
INSERT INTO `attendance` VALUES ('112', '51', '2025-11-02', 'present', '', '2025-11-02 15:25:15', '2025-11-02 15:25:15');
INSERT INTO `attendance` VALUES ('113', '65', '2025-11-02', 'present', '', '2025-11-02 15:25:15', '2025-11-02 15:25:15');
INSERT INTO `attendance` VALUES ('114', '79', '2025-11-02', 'present', '', '2025-11-02 15:25:15', '2025-11-02 15:25:15');
INSERT INTO `attendance` VALUES ('115', '93', '2025-11-02', 'present', '', '2025-11-02 15:25:15', '2025-11-02 15:25:15');
INSERT INTO `attendance` VALUES ('116', '107', '2025-11-02', 'absent', '', '2025-11-02 15:25:15', '2025-11-02 15:25:15');
INSERT INTO `attendance` VALUES ('117', '112', '2025-11-03', 'present', '', '2025-11-03 01:03:15', '2025-11-03 01:03:15');
INSERT INTO `attendance` VALUES ('118', '113', '2025-11-03', 'present', '', '2025-11-03 01:03:15', '2025-11-03 01:03:15');
INSERT INTO `attendance` VALUES ('119', '114', '2025-11-03', 'absent', '', '2025-11-03 01:03:15', '2025-11-03 01:03:15');
INSERT INTO `attendance` VALUES ('120', '4', '2025-11-03', 'present', '', '2025-11-03 01:04:26', '2025-11-03 01:04:26');
INSERT INTO `attendance` VALUES ('121', '5', '2025-11-03', 'present', '', '2025-11-03 01:04:26', '2025-11-03 01:04:26');
INSERT INTO `attendance` VALUES ('122', '6', '2025-11-03', 'present', '', '2025-11-03 01:04:26', '2025-11-03 01:04:26');
INSERT INTO `attendance` VALUES ('123', '7', '2025-11-03', 'present', '', '2025-11-03 01:04:26', '2025-11-03 01:04:26');
INSERT INTO `attendance` VALUES ('124', '8', '2025-11-03', 'present', '', '2025-11-03 01:04:26', '2025-11-03 01:04:26');
INSERT INTO `attendance` VALUES ('125', '10', '2025-11-03', 'present', '', '2025-11-03 01:04:26', '2025-11-03 01:04:26');
INSERT INTO `attendance` VALUES ('126', '33', '2025-11-03', 'present', '', '2025-11-03 01:04:26', '2025-11-03 01:04:26');
INSERT INTO `attendance` VALUES ('127', '34', '2025-11-03', 'late', '', '2025-11-03 01:04:26', '2025-11-03 01:04:26');
INSERT INTO `attendance` VALUES ('128', '47', '2025-11-03', 'present', '', '2025-11-03 01:04:27', '2025-11-03 01:04:27');
INSERT INTO `attendance` VALUES ('129', '48', '2025-11-03', 'absent', '', '2025-11-03 01:04:27', '2025-11-03 01:04:27');
INSERT INTO `attendance` VALUES ('130', '61', '2025-11-03', 'present', '', '2025-11-03 01:04:27', '2025-11-03 01:04:27');
INSERT INTO `attendance` VALUES ('131', '62', '2025-11-03', 'present', '', '2025-11-03 01:04:27', '2025-11-03 01:04:27');
INSERT INTO `attendance` VALUES ('132', '75', '2025-11-03', 'present', '', '2025-11-03 01:04:27', '2025-11-03 01:04:27');
INSERT INTO `attendance` VALUES ('133', '76', '2025-11-03', 'present', '', '2025-11-03 01:04:27', '2025-11-03 01:04:27');
INSERT INTO `attendance` VALUES ('134', '89', '2025-11-03', 'present', '', '2025-11-03 01:04:27', '2025-11-03 01:04:27');
INSERT INTO `attendance` VALUES ('135', '90', '2025-11-03', 'present', '', '2025-11-03 01:04:27', '2025-11-03 01:04:27');
INSERT INTO `attendance` VALUES ('136', '103', '2025-11-03', 'present', '', '2025-11-03 01:04:27', '2025-11-03 01:04:27');
INSERT INTO `attendance` VALUES ('137', '104', '2025-11-03', 'absent', '', '2025-11-03 01:04:27', '2025-11-03 01:04:27');
INSERT INTO `attendance` VALUES ('138', '4', '2025-10-03', 'present', '', '2025-11-03 01:12:51', '2025-11-03 01:12:51');
INSERT INTO `attendance` VALUES ('139', '5', '2025-10-03', 'present', '', '2025-11-03 01:12:51', '2025-11-03 01:12:51');
INSERT INTO `attendance` VALUES ('140', '6', '2025-10-03', 'present', '', '2025-11-03 01:12:51', '2025-11-03 01:12:51');
INSERT INTO `attendance` VALUES ('141', '7', '2025-10-03', 'present', '', '2025-11-03 01:12:51', '2025-11-03 01:12:51');
INSERT INTO `attendance` VALUES ('142', '8', '2025-10-03', 'present', '', '2025-11-03 01:12:51', '2025-11-03 01:12:51');
INSERT INTO `attendance` VALUES ('143', '10', '2025-10-03', 'late', '', '2025-11-03 01:12:51', '2025-11-03 01:12:51');
INSERT INTO `attendance` VALUES ('144', '33', '2025-10-03', 'present', '', '2025-11-03 01:12:51', '2025-11-03 01:12:51');
INSERT INTO `attendance` VALUES ('145', '34', '2025-10-03', 'present', '', '2025-11-03 01:12:51', '2025-11-03 01:12:51');
INSERT INTO `attendance` VALUES ('146', '47', '2025-10-03', 'present', '', '2025-11-03 01:12:51', '2025-11-03 01:12:51');
INSERT INTO `attendance` VALUES ('147', '48', '2025-10-03', 'present', '', '2025-11-03 01:12:51', '2025-11-03 01:12:51');
INSERT INTO `attendance` VALUES ('148', '61', '2025-10-03', 'present', '', '2025-11-03 01:12:51', '2025-11-03 01:12:51');
INSERT INTO `attendance` VALUES ('149', '62', '2025-10-03', 'present', '', '2025-11-03 01:12:51', '2025-11-03 01:12:51');
INSERT INTO `attendance` VALUES ('150', '75', '2025-10-03', 'late', '', '2025-11-03 01:12:51', '2025-11-03 01:12:51');
INSERT INTO `attendance` VALUES ('151', '76', '2025-10-03', 'present', '', '2025-11-03 01:12:51', '2025-11-03 01:12:51');
INSERT INTO `attendance` VALUES ('152', '89', '2025-10-03', 'present', '', '2025-11-03 01:12:51', '2025-11-03 01:12:51');
INSERT INTO `attendance` VALUES ('153', '90', '2025-10-03', 'present', '', '2025-11-03 01:12:51', '2025-11-03 01:12:51');
INSERT INTO `attendance` VALUES ('154', '103', '2025-10-03', 'present', '', '2025-11-03 01:12:51', '2025-11-03 01:12:51');
INSERT INTO `attendance` VALUES ('155', '104', '2025-10-03', 'late', '', '2025-11-03 01:12:51', '2025-11-03 01:12:51');
INSERT INTO `attendance` VALUES ('156', '32', '2025-09-05', 'absent', '', '2025-11-03 01:14:53', '2025-11-03 01:14:53');
INSERT INTO `attendance` VALUES ('157', '46', '2025-09-05', 'present', '', '2025-11-03 01:14:53', '2025-11-03 01:14:53');
INSERT INTO `attendance` VALUES ('158', '60', '2025-09-05', 'present', '', '2025-11-03 01:14:53', '2025-11-03 01:14:53');
INSERT INTO `attendance` VALUES ('159', '74', '2025-09-05', 'present', '', '2025-11-03 01:14:53', '2025-11-03 01:14:53');
INSERT INTO `attendance` VALUES ('160', '88', '2025-09-05', 'present', '', '2025-11-03 01:14:53', '2025-11-03 01:14:53');
INSERT INTO `attendance` VALUES ('161', '102', '2025-09-05', 'present', '', '2025-11-03 01:14:53', '2025-11-03 01:14:53');
INSERT INTO `attendance` VALUES ('162', '30', '2025-11-07', 'absent', '', '2025-11-03 01:16:53', '2025-11-03 01:16:53');
INSERT INTO `attendance` VALUES ('163', '44', '2025-11-07', 'absent', '', '2025-11-03 01:16:53', '2025-11-03 01:16:53');
INSERT INTO `attendance` VALUES ('164', '58', '2025-11-07', 'present', '', '2025-11-03 01:16:53', '2025-11-03 01:16:53');
INSERT INTO `attendance` VALUES ('165', '72', '2025-11-07', 'present', '', '2025-11-03 01:16:53', '2025-11-03 01:16:53');
INSERT INTO `attendance` VALUES ('166', '86', '2025-11-07', 'present', '', '2025-11-03 01:16:53', '2025-11-03 01:16:53');
INSERT INTO `attendance` VALUES ('167', '100', '2025-11-07', 'present', '', '2025-11-03 01:16:53', '2025-11-03 01:16:53');
INSERT INTO `attendance` VALUES ('168', '112', '2025-11-04', 'present', '', '2025-11-04 10:20:28', '2025-11-04 10:20:28');
INSERT INTO `attendance` VALUES ('169', '113', '2025-11-04', 'present', '', '2025-11-04 10:20:28', '2025-11-04 10:20:28');
INSERT INTO `attendance` VALUES ('170', '114', '2025-11-04', 'present', '', '2025-11-04 10:20:28', '2025-11-04 10:20:28');
INSERT INTO `attendance` VALUES ('171', '4', '2025-11-04', 'present', '', '2025-11-04 10:20:53', '2025-11-04 10:20:53');
INSERT INTO `attendance` VALUES ('172', '5', '2025-11-04', 'present', '', '2025-11-04 10:20:53', '2025-11-04 10:20:53');
INSERT INTO `attendance` VALUES ('173', '6', '2025-11-04', 'present', '', '2025-11-04 10:20:53', '2025-11-04 10:20:53');
INSERT INTO `attendance` VALUES ('174', '7', '2025-11-04', 'present', '', '2025-11-04 10:20:53', '2025-11-04 10:20:53');
INSERT INTO `attendance` VALUES ('175', '8', '2025-11-04', 'present', '', '2025-11-04 10:20:53', '2025-11-04 10:20:53');
INSERT INTO `attendance` VALUES ('176', '10', '2025-11-04', 'present', '', '2025-11-04 10:20:53', '2025-11-04 10:20:53');
INSERT INTO `attendance` VALUES ('177', '33', '2025-11-04', 'absent', '', '2025-11-04 10:20:53', '2025-11-04 10:20:53');
INSERT INTO `attendance` VALUES ('178', '34', '2025-11-04', 'present', '', '2025-11-04 10:20:53', '2025-11-04 10:20:53');
INSERT INTO `attendance` VALUES ('179', '47', '2025-11-04', 'present', '', '2025-11-04 10:20:53', '2025-11-04 10:20:53');
INSERT INTO `attendance` VALUES ('180', '48', '2025-11-04', 'present', '', '2025-11-04 10:20:53', '2025-11-04 10:20:53');
INSERT INTO `attendance` VALUES ('181', '61', '2025-11-04', 'present', '', '2025-11-04 10:20:53', '2025-11-04 10:20:53');
INSERT INTO `attendance` VALUES ('182', '62', '2025-11-04', 'present', '', '2025-11-04 10:20:53', '2025-11-04 10:20:53');
INSERT INTO `attendance` VALUES ('183', '75', '2025-11-04', 'absent', '', '2025-11-04 10:20:53', '2025-11-04 10:20:53');
INSERT INTO `attendance` VALUES ('184', '76', '2025-11-04', 'present', '', '2025-11-04 10:20:53', '2025-11-04 10:20:53');
INSERT INTO `attendance` VALUES ('185', '89', '2025-11-04', 'absent', '', '2025-11-04 10:20:53', '2025-11-04 10:20:53');
INSERT INTO `attendance` VALUES ('186', '90', '2025-11-04', 'present', '', '2025-11-04 10:20:53', '2025-11-04 10:20:53');
INSERT INTO `attendance` VALUES ('187', '103', '2025-11-04', 'present', '', '2025-11-04 10:20:53', '2025-11-04 10:20:53');
INSERT INTO `attendance` VALUES ('188', '104', '2025-11-04', 'present', '', '2025-11-04 10:20:53', '2025-11-04 10:20:53');


-- Table structure for table `audit_logs`
CREATE TABLE `audit_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `action` varchar(50) NOT NULL,
  `table_name` varchar(100) NOT NULL,
  `record_id` int DEFAULT NULL,
  `old_values` text,
  `new_values` text,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `academic_year_id` int DEFAULT NULL,
  `term` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `fk_audit_logs_academic_year_id` (`academic_year_id`)
) ENGINE=MyISAM AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `audit_logs`
INSERT INTO `audit_logs` VALUES ('1', '2', 'create', 'students', '4', NULL, '{\"admission_no\":\"BED\\/151023\",\"first_name\":\"sam\",\"last_name\":\"sam\",\"dob\":\"\",\"gender\":\"male\",\"class_id\":\"13\",\"guardian_name\":\"YaYa\",\"guardian_phone\":\"5657575\",\"address\":\"opeikumah\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, NULL, '2025-10-14 00:27:24');
INSERT INTO `audit_logs` VALUES ('2', '2', 'create', 'students', '5', NULL, '{\"admission_no\":\"151023\",\"first_name\":\"Afful\",\"last_name\":\"Brown\",\"dob\":\"2010-06-15\",\"gender\":\"male\",\"class_id\":\"13\",\"guardian_name\":\"YaYa\",\"guardian_phone\":\"5657575\",\"address\":\"opeikumah\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, NULL, '2025-10-14 00:50:09');
INSERT INTO `audit_logs` VALUES ('3', '2', 'update', 'students', '5', '{\"id\":5,\"user_id\":null,\"admission_no\":\"151023\",\"first_name\":\"Afful\",\"last_name\":\"Brown\",\"dob\":\"2010-06-15\",\"gender\":\"male\",\"class_id\":13,\"guardian_name\":\"YaYa\",\"guardian_phone\":\"5657575\",\"address\":\"opeikumah\",\"medical_info\":null,\"created_at\":\"2025-10-14 00:50:09\",\"updated_at\":\"2025-10-14 00:50:09\",\"profile_picture\":null}', '{\"admission_no\":\"151023\",\"first_name\":\"Afful\",\"last_name\":\"Br\",\"dob\":\"2010-06-15\",\"gender\":\"male\",\"class_id\":\"13\",\"guardian_name\":\"YaYa\",\"guardian_phone\":\"5657575\",\"address\":\"opeikumah\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, NULL, '2025-10-14 01:04:01');
INSERT INTO `audit_logs` VALUES ('4', '2', 'update', 'students', '5', '{\"id\":5,\"user_id\":null,\"admission_no\":\"151023\",\"first_name\":\"Afful\",\"last_name\":\"Br\",\"dob\":\"2010-06-15\",\"gender\":\"male\",\"class_id\":13,\"guardian_name\":\"YaYa\",\"guardian_phone\":\"5657575\",\"address\":\"opeikumah\",\"medical_info\":null,\"created_at\":\"2025-10-14 00:50:09\",\"updated_at\":\"2025-10-14 01:04:01\",\"profile_picture\":null}', '{\"admission_no\":\"151023\",\"first_name\":\"Afful\",\"last_name\":\"Br\",\"dob\":\"2010-06-15\",\"gender\":\"male\",\"class_id\":\"13\",\"guardian_name\":\"YaYa\",\"guardian_phone\":\"5657575\",\"address\":\"opeikumah\",\"profile_picture\":\"student_1760403855_68eda18fa64e3.png\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, NULL, '2025-10-14 01:04:15');
INSERT INTO `audit_logs` VALUES ('5', '2', 'update', 'students', '5', '{\"id\":5,\"user_id\":null,\"admission_no\":\"151023\",\"first_name\":\"Afful\",\"last_name\":\"Br\",\"dob\":\"2010-06-15\",\"gender\":\"male\",\"class_id\":13,\"guardian_name\":\"YaYa\",\"guardian_phone\":\"5657575\",\"address\":\"opeikumah\",\"medical_info\":null,\"created_at\":\"2025-10-14 00:50:09\",\"updated_at\":\"2025-10-14 01:04:15\",\"profile_picture\":\"student_1760403855_68eda18fa64e3.png\"}', '{\"admission_no\":\"151023\",\"first_name\":\"Afful\",\"last_name\":\"Br\",\"dob\":\"2010-06-15\",\"gender\":\"male\",\"class_id\":\"13\",\"guardian_name\":\"YaYa\",\"guardian_phone\":\"5657575\",\"address\":\"opeikumah\",\"profile_picture\":\"student_1760470187_68eea4abd370a.jpeg\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, NULL, '2025-10-14 19:29:47');
INSERT INTO `audit_logs` VALUES ('6', '2', 'create', 'students', '6', NULL, '{\"admission_no\":\"15102378\",\"first_name\":\"Akwesi\",\"last_name\":\"Brown\",\"dob\":\"2010-06-15\",\"gender\":\"female\",\"class_id\":\"13\",\"guardian_name\":\"YaYa\",\"guardian_phone\":\"5657575\",\"address\":\"\",\"profile_picture\":\"student_1760470245_68eea4e508084.jpeg\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, NULL, '2025-10-14 19:30:45');
INSERT INTO `audit_logs` VALUES ('7', '2', 'update', 'students', '6', '{\"id\":6,\"user_id\":null,\"admission_no\":\"15102378\",\"first_name\":\"Akwesi\",\"last_name\":\"Brown\",\"dob\":\"2010-06-15\",\"gender\":\"female\",\"class_id\":13,\"guardian_name\":\"YaYa\",\"guardian_phone\":\"5657575\",\"address\":\"\",\"medical_info\":null,\"created_at\":\"2025-10-14 19:30:45\",\"updated_at\":\"2025-10-14 19:30:45\",\"profile_picture\":\"student_1760470245_68eea4e508084.jpeg\"}', '{\"admission_no\":\"15102378\",\"first_name\":\"Akwesi\",\"last_name\":\"Brown\",\"dob\":\"2010-06-15\",\"gender\":\"female\",\"class_id\":\"13\",\"guardian_name\":\"YaYa\",\"guardian_phone\":\"5657575\",\"address\":\"\",\"profile_picture\":\"student_1760470733_68eea6cdb74e0.jpeg\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, NULL, '2025-10-14 19:38:53');
INSERT INTO `audit_logs` VALUES ('8', '2', 'update', 'students', '6', '{\"id\":6,\"user_id\":null,\"admission_no\":\"15102378\",\"first_name\":\"Akwesi\",\"last_name\":\"Brown\",\"dob\":\"2010-06-15\",\"gender\":\"female\",\"class_id\":13,\"guardian_name\":\"YaYa\",\"guardian_phone\":\"5657575\",\"address\":\"\",\"medical_info\":null,\"created_at\":\"2025-10-14 19:30:45\",\"updated_at\":\"2025-10-14 19:38:53\",\"profile_picture\":\"student_1760470733_68eea6cdb74e0.jpeg\"}', '{\"admission_no\":\"15102378\",\"first_name\":\"Akwesi\",\"last_name\":\"Brown\",\"dob\":\"2010-06-15\",\"gender\":\"female\",\"class_id\":\"13\",\"guardian_name\":\"YaYa\",\"guardian_phone\":\"5657575\",\"address\":\"\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, NULL, '2025-10-14 19:39:16');
INSERT INTO `audit_logs` VALUES ('9', '2', 'create', 'students', '7', NULL, '{\"admission_no\":\"15102378s4\",\"first_name\":\"IKe\",\"last_name\":\"Brown Edem\",\"dob\":\"2010-06-15\",\"gender\":\"male\",\"class_id\":\"\",\"guardian_name\":\"YaYa\",\"guardian_phone\":\"5657575\",\"address\":\"\",\"profile_picture\":\"student_1760470904_68eea77842134.jpeg\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, NULL, '2025-10-14 19:41:44');
INSERT INTO `audit_logs` VALUES ('10', '2', 'update', 'students', '7', '{\"id\":7,\"user_id\":null,\"admission_no\":\"15102378s4\",\"first_name\":\"IKe\",\"last_name\":\"Brown Edem\",\"dob\":\"2010-06-15\",\"gender\":\"male\",\"class_id\":0,\"guardian_name\":\"YaYa\",\"guardian_phone\":\"5657575\",\"address\":\"\",\"medical_info\":null,\"created_at\":\"2025-10-14 19:41:44\",\"updated_at\":\"2025-10-14 19:41:44\",\"profile_picture\":\"student_1760470904_68eea77842134.jpeg\"}', '{\"admission_no\":\"15102378s4\",\"first_name\":\"IKe\",\"last_name\":\"Brown Edem\",\"dob\":\"2010-06-15\",\"gender\":\"male\",\"class_id\":\"\",\"guardian_name\":\"YaYa\",\"guardian_phone\":\"5657575\",\"address\":\"\",\"profile_picture\":\"student_1760471975_68eeaba72c8a7.jpeg\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, NULL, '2025-10-14 19:59:35');
INSERT INTO `audit_logs` VALUES ('11', '2', 'update', 'students', '7', '{\"id\":7,\"user_id\":null,\"admission_no\":\"15102378s4\",\"first_name\":\"IKe\",\"last_name\":\"Brown Edem\",\"dob\":\"2010-06-15\",\"gender\":\"male\",\"class_id\":0,\"guardian_name\":\"YaYa\",\"guardian_phone\":\"5657575\",\"address\":\"\",\"medical_info\":null,\"created_at\":\"2025-10-14 19:41:44\",\"updated_at\":\"2025-10-14 19:59:35\",\"profile_picture\":\"student_1760471975_68eeaba72c8a7.jpeg\"}', '{\"admission_no\":\"15102378s4\",\"first_name\":\"IKe\",\"last_name\":\"Brown Edem\",\"dob\":\"2010-06-15\",\"gender\":\"male\",\"class_id\":\"13\",\"guardian_name\":\"YaYa\",\"guardian_phone\":\"5657575\",\"address\":\"\",\"profile_picture\":\"student_1760472327_68eead07a885e.jpeg\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, NULL, '2025-10-14 20:05:27');
INSERT INTO `audit_logs` VALUES ('12', '2', 'update', 'students', '6', '{\"id\":6,\"user_id\":null,\"admission_no\":\"15102378\",\"first_name\":\"Akwesi\",\"last_name\":\"Brown\",\"dob\":\"2010-06-15\",\"gender\":\"female\",\"class_id\":13,\"guardian_name\":\"YaYa\",\"guardian_phone\":\"5657575\",\"address\":\"\",\"medical_info\":null,\"created_at\":\"2025-10-14 19:30:45\",\"updated_at\":\"2025-10-14 19:38:53\",\"profile_picture\":\"student_1760470733_68eea6cdb74e0.jpeg\"}', '{\"admission_no\":\"15102378\",\"first_name\":\"Akwesi\",\"last_name\":\"Brown\",\"dob\":\"2010-06-15\",\"gender\":\"female\",\"class_id\":\"13\",\"guardian_name\":\"YaYa\",\"guardian_phone\":\"5657575\",\"address\":\"\",\"profile_picture\":\"student_1760472665_68eeae593c922.jpeg\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, NULL, '2025-10-14 20:11:05');
INSERT INTO `audit_logs` VALUES ('13', '2', 'update', 'students', '7', '{\"id\":7,\"user_id\":null,\"admission_no\":\"15102378s4\",\"first_name\":\"IKe\",\"last_name\":\"Brown Edem\",\"dob\":\"2010-06-15\",\"gender\":\"male\",\"class_id\":13,\"guardian_name\":\"YaYa\",\"guardian_phone\":\"5657575\",\"address\":\"\",\"medical_info\":null,\"created_at\":\"2025-10-14 19:41:44\",\"updated_at\":\"2025-10-14 20:05:27\",\"profile_picture\":\"student_1760472327_68eead07a885e.jpeg\"}', '{\"admission_no\":\"15102378s4\",\"first_name\":\"IKe\",\"last_name\":\"Brown Edem\",\"dob\":\"2010-06-15\",\"gender\":\"male\",\"class_id\":\"13\",\"guardian_name\":\"YaYa\",\"guardian_phone\":\"5657575\",\"address\":\"\",\"profile_picture\":\"student_1760472961_68eeaf81259bb.jpeg\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, NULL, '2025-10-14 20:16:01');
INSERT INTO `audit_logs` VALUES ('14', '2', 'create', 'students', '8', NULL, '{\"admission_no\":\"15102378s4r\",\"first_name\":\"mina\",\"last_name\":\"Brown Edem\",\"dob\":\"2010-06-15\",\"gender\":\"male\",\"class_id\":\"\",\"guardian_name\":\"YaYa\",\"guardian_phone\":\"5657575\",\"address\":\"\",\"profile_picture\":\"student_1760473016_68eeafb8c2af9.jpeg\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, NULL, '2025-10-14 20:16:56');
INSERT INTO `audit_logs` VALUES ('15', '2', 'update', 'students', '8', '{\"id\":8,\"user_id\":null,\"admission_no\":\"15102378s4r\",\"first_name\":\"mina\",\"last_name\":\"Brown Edem\",\"dob\":\"2010-06-15\",\"gender\":\"male\",\"class_id\":0,\"guardian_name\":\"YaYa\",\"guardian_phone\":\"5657575\",\"address\":\"\",\"medical_info\":null,\"created_at\":\"2025-10-14 20:16:56\",\"updated_at\":\"2025-10-14 20:16:56\",\"profile_picture\":\"student_1760473016_68eeafb8c2af9.jpeg\"}', '{\"admission_no\":\"15102378s4r\",\"first_name\":\"mina\",\"last_name\":\"Brown Edem\",\"dob\":\"2010-06-15\",\"gender\":\"male\",\"class_id\":\"\",\"guardian_name\":\"YaYa\",\"guardian_phone\":\"5657575\",\"address\":\"\",\"profile_picture\":\"student_1760473084_68eeaffc22f98.jpeg\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, NULL, '2025-10-14 20:18:04');
INSERT INTO `audit_logs` VALUES ('16', '2', 'update', 'students', '4', '{\"id\":4,\"user_id\":null,\"admission_no\":\"BED\\/151023\",\"first_name\":\"sam\",\"last_name\":\"sam\",\"dob\":\"0000-00-00\",\"gender\":\"male\",\"class_id\":13,\"guardian_name\":\"YaYa\",\"guardian_phone\":\"5657575\",\"address\":\"opeikumah\",\"medical_info\":null,\"created_at\":\"2025-10-14 00:27:24\",\"updated_at\":\"2025-10-14 00:27:24\",\"profile_picture\":null}', '{\"admission_no\":\"BED\\/151023\",\"first_name\":\"sam\",\"last_name\":\"sam\",\"dob\":\"\",\"gender\":\"male\",\"class_id\":\"13\",\"guardian_name\":\"YaYa\",\"guardian_phone\":\"5657575\",\"address\":\"opeikumah\",\"profile_picture\":\"student_1760678807_68f1d39710eac.webp\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, NULL, '2025-10-17 05:26:47');
INSERT INTO `audit_logs` VALUES ('17', '2', 'update', 'students', '1', '{\"id\":1,\"user_id\":null,\"admission_no\":\"STU001\",\"first_name\":\"John\",\"last_name\":\"Doe\",\"dob\":\"2010-05-15\",\"gender\":\"male\",\"class_id\":1,\"guardian_name\":\"Robert Doe\",\"guardian_phone\":\"+1234567890\",\"address\":\"123 Main St, City\",\"medical_info\":null,\"created_at\":\"2025-10-12 02:04:36\",\"updated_at\":\"2025-10-12 02:04:36\",\"profile_picture\":null,\"student_category\":\"regular_day\",\"student_category_details\":null}', '{\"admission_no\":\"EPI-082848\",\"first_name\":\"John\",\"last_name\":\"Doe\",\"dob\":\"2010-05-15\",\"gender\":\"male\",\"class_id\":\"1\",\"guardian_name\":\"Robert Doe\",\"guardian_phone\":\"+1234567890\",\"address\":\"123 Main St, City\",\"student_category\":\"regular_day\",\"student_category_details\":\"\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, NULL, '2025-10-17 08:29:20');
INSERT INTO `audit_logs` VALUES ('18', '2', 'update', 'students', '8', '{\"id\":8,\"user_id\":null,\"admission_no\":\"15102378s4r\",\"first_name\":\"mina\",\"last_name\":\"Brown Edem\",\"dob\":\"2010-06-15\",\"gender\":\"male\",\"class_id\":0,\"guardian_name\":\"YaYa\",\"guardian_phone\":\"5657575\",\"address\":\"\",\"medical_info\":null,\"created_at\":\"2025-10-14 20:16:56\",\"updated_at\":\"2025-10-14 20:18:04\",\"profile_picture\":\"student_1760473084_68eeaffc22f98.jpeg\",\"admission_date\":null,\"student_category\":\"regular_day\",\"student_category_details\":null}', '{\"admission_no\":\"15102378s4r\",\"first_name\":\"mina\",\"last_name\":\"Brown Edem\",\"dob\":\"2010-06-15\",\"gender\":\"male\",\"class_id\":\"\",\"guardian_name\":\"YaYa\",\"guardian_phone\":\"5657575\",\"address\":\"\",\"student_category\":\"regular_day\",\"student_category_details\":\"\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, NULL, '2025-10-17 09:58:16');
INSERT INTO `audit_logs` VALUES ('19', '2', 'update', 'students', '8', '{\"id\":8,\"user_id\":null,\"admission_no\":\"15102378s4r\",\"first_name\":\"mina\",\"last_name\":\"Brown Edem\",\"dob\":\"2010-06-15\",\"gender\":\"male\",\"class_id\":0,\"guardian_name\":\"YaYa\",\"guardian_phone\":\"5657575\",\"address\":\"\",\"medical_info\":null,\"created_at\":\"2025-10-14 20:16:56\",\"updated_at\":\"2025-10-17 09:58:16\",\"profile_picture\":\"student_1760473084_68eeaffc22f98.jpeg\",\"admission_date\":null,\"academic_year_id\":null,\"student_category\":\"regular_day\",\"student_category_details\":\"\"}', '{\"admission_no\":\"15102378s4r\",\"first_name\":\"mina\",\"last_name\":\"Brown Edem\",\"dob\":\"2010-06-15\",\"gender\":\"male\",\"class_id\":\"\",\"guardian_name\":\"YaYa\",\"guardian_phone\":\"5657575\",\"address\":\"\",\"student_category\":\"regular_day\",\"student_category_details\":\"\",\"admission_date\":\"2025-10-16\",\"academic_year_id\":2}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, NULL, '2025-10-17 10:22:16');
INSERT INTO `audit_logs` VALUES ('20', '2', 'update', 'students', '8', '{\"id\":8,\"user_id\":null,\"admission_no\":\"15102378s4r\",\"first_name\":\"mina\",\"last_name\":\"Brown Edem\",\"dob\":\"2010-06-15\",\"gender\":\"male\",\"class_id\":0,\"guardian_name\":\"YaYa\",\"guardian_phone\":\"5657575\",\"address\":\"\",\"medical_info\":null,\"created_at\":\"2025-10-14 20:16:56\",\"updated_at\":\"2025-10-17 10:22:16\",\"profile_picture\":\"student_1760473084_68eeaffc22f98.jpeg\",\"admission_date\":\"2025-10-16\",\"academic_year_id\":2,\"student_category\":\"regular_day\",\"student_category_details\":\"\"}', '{\"admission_no\":\"15102378s4r\",\"first_name\":\"mina\",\"last_name\":\"Brown Edem\",\"dob\":\"2010-06-15\",\"gender\":\"male\",\"class_id\":\"13\",\"guardian_name\":\"YaYa\",\"guardian_phone\":\"5657575\",\"address\":\"\",\"student_category\":\"regular_day\",\"student_category_details\":\"\",\"admission_date\":\"2025-10-16\",\"academic_year_id\":2}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, NULL, '2025-10-17 10:22:51');
INSERT INTO `audit_logs` VALUES ('21', '2', 'create', 'students', '10', NULL, '{\"admission_no\":\"EPI-105526\",\"first_name\":\"Kwame\",\"last_name\":\"Mintah\",\"dob\":\"2011-02-17\",\"gender\":\"male\",\"class_id\":\"13\",\"guardian_name\":\"Mr Mintah\",\"guardian_phone\":\"5657575\",\"address\":\"opeikumah\",\"student_category\":\"regular_day\",\"student_category_details\":\"\",\"admission_date\":\"2025-10-17\",\"academic_year_id\":2}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, NULL, '2025-10-17 10:57:10');
INSERT INTO `audit_logs` VALUES ('22', '2', 'create', 'students', '11', NULL, '{\"admission_no\":\"EPI-232937\",\"first_name\":\"Effia\",\"last_name\":\"Odo\",\"dob\":\"2015-02-03\",\"gender\":\"male\",\"class_id\":\"3\",\"guardian_name\":\"Robert Doe\",\"guardian_phone\":\"909458096\",\"address\":\"\",\"student_category\":\"regular_boarding\",\"student_category_details\":\"\",\"admission_date\":\"2025-10-19\",\"academic_year_id\":2,\"profile_picture\":\"student_1760916771_68f575233c0c5.jpg\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, NULL, '2025-10-19 23:32:51');
INSERT INTO `audit_logs` VALUES ('23', '2', 'update', 'students', '11', '{\"id\":11,\"user_id\":null,\"admission_no\":\"EPI-232937\",\"first_name\":\"Effia\",\"last_name\":\"Odo\",\"dob\":\"2015-02-03\",\"gender\":\"male\",\"class_id\":3,\"guardian_name\":\"Robert Doe\",\"guardian_phone\":\"909458096\",\"address\":\"\",\"medical_info\":null,\"created_at\":\"2025-10-19 23:32:51\",\"updated_at\":\"2025-10-19 23:32:51\",\"profile_picture\":\"student_1760916771_68f575233c0c5.jpg\",\"admission_date\":\"2025-10-19\",\"academic_year_id\":2,\"student_category\":\"regular_boarding\",\"student_category_details\":\"\"}', '{\"admission_no\":\"EPI-232937\",\"first_name\":\"Effum\",\"last_name\":\"Odoum\",\"dob\":\"2015-02-03\",\"gender\":\"male\",\"class_id\":\"3\",\"guardian_name\":\"Robert Doe\",\"guardian_phone\":\"909458096\",\"address\":\"\",\"student_category\":\"regular_boarding\",\"student_category_details\":\"\",\"admission_date\":\"2025-10-19\",\"academic_year_id\":2}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, NULL, '2025-10-19 23:33:45');
INSERT INTO `audit_logs` VALUES ('24', '2', 'create', 'students', '112', '\"Created student: Micheal Woani\"', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, NULL, '2025-10-24 12:12:37');
INSERT INTO `audit_logs` VALUES ('25', '2', 'create', 'students', '113', '\"Created student: Kwame  addie\"', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, NULL, '2025-10-24 12:14:51');
INSERT INTO `audit_logs` VALUES ('26', '2', 'create', 'students', '114', '\"Created student: Effia addie\"', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, NULL, '2025-10-24 12:16:36');
INSERT INTO `audit_logs` VALUES ('27', '2', 'create', 'students', '115', '\"Created student: Alred Koti\"', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, NULL, '2025-10-29 22:36:47');
INSERT INTO `audit_logs` VALUES ('28', '2', 'create', 'students', '116', '\"Created student: Nyiram Bantam\"', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, NULL, '2025-10-29 22:38:04');
INSERT INTO `audit_logs` VALUES ('29', '2', 'create', 'students', '117', '\"Created student: Amu Waton\"', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, NULL, '2025-10-29 22:40:04');
INSERT INTO `audit_logs` VALUES ('30', '2', 'create', 'students', '118', '\"Created student: YAYA KK\"', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, NULL, '2025-10-29 22:59:19');
INSERT INTO `audit_logs` VALUES ('31', '2', 'create', 'students', '119', '\"Created student: josh amet\"', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, NULL, '2025-10-29 23:23:26');
INSERT INTO `audit_logs` VALUES ('32', '2', 'create', 'students', '120', '\"Created student: Efiram Amu\"', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, NULL, '2025-10-30 12:13:22');
INSERT INTO `audit_logs` VALUES ('33', '2', 'create', 'students', '121', '\"Created student: Offin Berima\"', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', NULL, NULL, '2025-11-04 13:25:14');
INSERT INTO `audit_logs` VALUES ('34', '2', 'create', 'students', '122', '\"Created student: Emefa Vida kk\"', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', NULL, NULL, '2025-11-13 00:59:16');
INSERT INTO `audit_logs` VALUES ('35', '2', 'update', 'students', '122', '\"Updated student: Emefa Vida kk\"', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', NULL, NULL, '2025-11-22 09:36:45');
INSERT INTO `audit_logs` VALUES ('36', '2', 'update', 'students', '122', '\"Updated student: Emefa Vida kk\"', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', NULL, NULL, '2025-11-22 09:37:51');
INSERT INTO `audit_logs` VALUES ('37', '2', 'update', 'students', '118', '\"Updated student: YAYA KK\"', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', NULL, NULL, '2025-11-22 09:42:09');
INSERT INTO `audit_logs` VALUES ('38', '2', 'delete', 'students', '124', '\"Deleted student: Albert Eisntin\"', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2', '1st Term', '2025-12-04 16:47:44');
INSERT INTO `audit_logs` VALUES ('39', '2', 'create', 'fees', '21', NULL, '{\"name\":\"Tuition Fee\",\"amount\":\"3000\",\"type\":\"tuition\",\"class_id\":null,\"description\":\"\",\"academic_year_id\":2}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2', '1st Term', '2025-12-04 17:08:46');
INSERT INTO `audit_logs` VALUES ('40', '2', 'create', 'payments', NULL, NULL, '{\"student_id\":\"125\",\"method\":\"cash\",\"date\":\"2025-12-05\",\"payment_count\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2', '1st Term', '2025-12-05 00:23:47');
INSERT INTO `audit_logs` VALUES ('41', '2', 'create', 'payments', NULL, NULL, '{\"student_id\":\"76\",\"method\":\"cash\",\"date\":\"2025-12-05\",\"payment_count\":2}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2', '1st Term', '2025-12-05 00:37:01');
INSERT INTO `audit_logs` VALUES ('42', '2', 'create', 'payments', NULL, NULL, '{\"student_id\":\"89\",\"method\":\"cash\",\"date\":\"2025-12-05\",\"payment_count\":3}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2', '1st Term', '2025-12-05 00:58:09');
INSERT INTO `audit_logs` VALUES ('43', '2', 'create', 'payments', NULL, NULL, '{\"student_id\":\"76\",\"method\":\"cash\",\"date\":\"2025-12-05\",\"payment_count\":2}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2', '1st Term', '2025-12-05 01:08:59');
INSERT INTO `audit_logs` VALUES ('44', '2', 'create', 'payments', NULL, NULL, '{\"student_id\":\"125\",\"method\":\"cash\",\"date\":\"2025-12-05\",\"payment_count\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2', '1st Term', '2025-12-05 01:44:07');
INSERT INTO `audit_logs` VALUES ('45', '2', 'create', 'students', '126', '\"Created student: AKUBI ANNA\"', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2', '1st Term', '2025-12-05 01:47:42');
INSERT INTO `audit_logs` VALUES ('46', '2', 'update', 'students', '126', '\"Updated student: AKUBI ANNA\"', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2', '1st Term', '2025-12-05 01:48:29');
INSERT INTO `audit_logs` VALUES ('47', '2', 'assign_students', 'fees', '21', NULL, '{\"assigned_students\":[\"126\"]}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2', '1st Term', '2025-12-05 02:14:02');
INSERT INTO `audit_logs` VALUES ('48', '2', 'create', 'payments', NULL, NULL, '{\"student_id\":\"126\",\"method\":\"cash\",\"date\":\"2025-12-05\",\"payment_count\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2', '1st Term', '2025-12-05 02:17:11');
INSERT INTO `audit_logs` VALUES ('49', '2', 'create', 'payments', NULL, NULL, '{\"student_id\":\"89\",\"method\":\"cash\",\"date\":\"2025-12-05\",\"payment_count\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2', '1st Term', '2025-12-05 03:00:58');
INSERT INTO `audit_logs` VALUES ('50', '2', 'create', 'payments', NULL, NULL, '{\"student_id\":\"33\",\"method\":\"cash\",\"date\":\"2025-12-06\",\"payment_count\":3}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2', '1st Term', '2025-12-06 10:46:16');
INSERT INTO `audit_logs` VALUES ('51', '2', 'create', 'payments', NULL, NULL, '{\"student_id\":\"48\",\"method\":\"cash\",\"date\":\"2025-12-06\",\"payment_count\":2}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2', '1st Term', '2025-12-06 10:52:47');
INSERT INTO `audit_logs` VALUES ('52', '2', 'create', 'payments', NULL, NULL, '{\"student_id\":\"48\",\"method\":\"cash\",\"date\":\"2025-12-06\",\"payment_count\":2}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2', '1st Term', '2025-12-06 11:27:18');
INSERT INTO `audit_logs` VALUES ('53', '2', 'create', 'payments', NULL, NULL, '{\"student_id\":\"33\",\"method\":\"cash\",\"date\":\"2025-12-06\",\"payment_count\":3}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2', '1st Term', '2025-12-06 11:28:07');
INSERT INTO `audit_logs` VALUES ('54', '2', 'create', 'students', '127', '\"Created student: Sammy  Dain\"', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2', '1st Term', '2025-12-06 13:50:08');
INSERT INTO `audit_logs` VALUES ('55', '2', 'assign_students', 'fees', '20', NULL, '{\"assigned_students\":[\"127\"]}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2', '1st Term', '2025-12-06 13:53:12');
INSERT INTO `audit_logs` VALUES ('56', '2', 'create', 'payments', NULL, NULL, '{\"student_id\":\"127\",\"method\":\"cash\",\"date\":\"2025-12-06\",\"payment_count\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2', '1st Term', '2025-12-06 14:54:44');


-- Table structure for table `class_subject_assignments`
CREATE TABLE `class_subject_assignments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `class_id` int NOT NULL,
  `subject_id` int NOT NULL,
  `student_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_class_subject_student` (`class_id`,`subject_id`,`student_id`),
  KEY `subject_id` (`subject_id`),
  KEY `student_id` (`student_id`)
) ENGINE=MyISAM AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `class_subject_assignments`
INSERT INTO `class_subject_assignments` VALUES ('35', '14', '13', NULL, '2025-10-24 12:23:20', '2025-10-24 12:23:20');
INSERT INTO `class_subject_assignments` VALUES ('34', '14', '12', NULL, '2025-10-24 12:23:20', '2025-10-24 12:23:20');
INSERT INTO `class_subject_assignments` VALUES ('39', '15', '15', NULL, '2025-10-29 21:13:14', '2025-10-29 21:13:14');
INSERT INTO `class_subject_assignments` VALUES ('38', '15', '14', NULL, '2025-10-29 21:12:20', '2025-10-29 21:12:20');
INSERT INTO `class_subject_assignments` VALUES ('40', '15', '16', NULL, '2025-10-29 21:14:37', '2025-10-29 21:14:37');
INSERT INTO `class_subject_assignments` VALUES ('41', '15', '17', NULL, '2025-10-29 21:15:39', '2025-10-29 21:15:39');
INSERT INTO `class_subject_assignments` VALUES ('33', '14', '11', NULL, '2025-10-24 12:23:20', '2025-10-24 12:23:20');
INSERT INTO `class_subject_assignments` VALUES ('31', '14', '9', NULL, '2025-10-24 12:23:20', '2025-10-24 12:23:20');
INSERT INTO `class_subject_assignments` VALUES ('32', '14', '10', NULL, '2025-10-24 12:23:20', '2025-10-24 12:23:20');
INSERT INTO `class_subject_assignments` VALUES ('30', '14', '8', NULL, '2025-10-24 12:23:20', '2025-10-24 12:23:20');
INSERT INTO `class_subject_assignments` VALUES ('29', '14', '7', NULL, '2025-10-24 12:23:20', '2025-10-24 12:23:20');
INSERT INTO `class_subject_assignments` VALUES ('27', '13', '12', NULL, '2025-10-24 12:20:55', '2025-10-24 12:20:55');
INSERT INTO `class_subject_assignments` VALUES ('28', '13', '13', NULL, '2025-10-24 12:21:48', '2025-10-24 12:21:48');
INSERT INTO `class_subject_assignments` VALUES ('42', '16', '18', NULL, '2025-10-29 23:26:52', '2025-10-29 23:26:52');
INSERT INTO `class_subject_assignments` VALUES ('43', '16', '19', NULL, '2025-10-29 23:27:19', '2025-10-29 23:27:19');
INSERT INTO `class_subject_assignments` VALUES ('44', '16', '20', NULL, '2025-10-29 23:28:21', '2025-10-29 23:28:21');


-- Table structure for table `classes`
CREATE TABLE `classes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `level` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `capacity` int DEFAULT '30',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `classes`
INSERT INTO `classes` VALUES ('1', 'Grade 1', 'Elementary', '2025-10-12 02:03:07', '2025-10-12 02:03:07', '30');
INSERT INTO `classes` VALUES ('2', 'Grade 2', 'Elementary', '2025-10-12 02:03:07', '2025-10-12 02:03:07', '30');
INSERT INTO `classes` VALUES ('3', 'Grade 3', 'Elementary', '2025-10-12 02:03:07', '2025-10-12 02:03:07', '30');
INSERT INTO `classes` VALUES ('4', 'Grade 4', 'Elementary', '2025-10-12 02:03:07', '2025-10-12 02:03:07', '30');
INSERT INTO `classes` VALUES ('5', 'Grade 5', 'Elementary', '2025-10-12 02:03:07', '2025-10-12 02:03:07', '30');
INSERT INTO `classes` VALUES ('6', 'Grade 6', 'Middle School', '2025-10-12 02:03:07', '2025-10-12 02:03:07', '30');
INSERT INTO `classes` VALUES ('7', 'Grade 7', 'Middle School', '2025-10-12 02:03:07', '2025-10-12 02:03:07', '30');
INSERT INTO `classes` VALUES ('8', 'Grade 8', 'Middle School', '2025-10-12 02:03:07', '2025-10-12 02:03:07', '30');
INSERT INTO `classes` VALUES ('9', 'Grade 9', 'High School', '2025-10-12 02:03:07', '2025-10-12 02:03:07', '30');
INSERT INTO `classes` VALUES ('10', 'Grade 10', 'High School', '2025-10-18 08:51:46', '2025-10-18 08:51:46', '30');
INSERT INTO `classes` VALUES ('11', 'Grade 11', 'High School', '2025-10-12 02:03:07', '2025-10-12 02:03:07', '30');
INSERT INTO `classes` VALUES ('12', 'Grade 12', 'High School', '2025-10-12 02:03:07', '2025-10-12 02:03:07', '30');
INSERT INTO `classes` VALUES ('13', 'JHS 3', 'High School', '2025-10-13 23:24:56', '2025-10-13 23:24:56', '30');
INSERT INTO `classes` VALUES ('14', 'JHS 2', 'Junior High', '2025-10-24 11:15:36', '2025-10-24 11:15:36', '30');
INSERT INTO `classes` VALUES ('15', 'J.H.S 1', 'Junior High', '2025-10-29 21:09:55', '2025-10-29 21:09:55', '30');
INSERT INTO `classes` VALUES ('16', 'J.H.S 1', 'A', '2025-10-29 23:21:54', '2025-10-29 23:21:54', '30');


-- Table structure for table `exam_classes`
CREATE TABLE `exam_classes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `exam_id` int NOT NULL,
  `class_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_exam_class` (`exam_id`,`class_id`),
  KEY `class_id` (`class_id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `exam_classes`
INSERT INTO `exam_classes` VALUES ('1', '10', '13', '2025-10-28 10:48:56', '2025-10-28 10:48:56');
INSERT INTO `exam_classes` VALUES ('2', '11', '13', '2025-10-28 10:51:20', '2025-10-28 10:51:20');
INSERT INTO `exam_classes` VALUES ('3', '11', '14', '2025-10-28 10:51:20', '2025-10-28 10:51:20');
INSERT INTO `exam_classes` VALUES ('4', '12', '13', '2025-10-28 11:04:31', '2025-10-28 11:04:31');
INSERT INTO `exam_classes` VALUES ('5', '12', '14', '2025-10-28 11:04:31', '2025-10-28 11:04:31');
INSERT INTO `exam_classes` VALUES ('6', '13', '13', '2025-10-28 11:10:13', '2025-10-28 11:10:13');
INSERT INTO `exam_classes` VALUES ('7', '13', '14', '2025-10-28 11:10:13', '2025-10-28 11:10:13');
INSERT INTO `exam_classes` VALUES ('8', '14', '13', '2025-10-28 11:14:06', '2025-10-28 11:14:06');
INSERT INTO `exam_classes` VALUES ('9', '17', '6', '2025-10-28 11:22:51', '2025-10-28 11:22:51');
INSERT INTO `exam_classes` VALUES ('10', '17', '14', '2025-10-28 11:22:51', '2025-10-28 11:22:51');
INSERT INTO `exam_classes` VALUES ('11', '18', '13', '2025-10-28 11:53:21', '2025-10-28 11:53:21');
INSERT INTO `exam_classes` VALUES ('12', '18', '14', '2025-10-28 11:53:21', '2025-10-28 11:53:21');
INSERT INTO `exam_classes` VALUES ('13', '19', '14', '2025-10-28 11:54:27', '2025-10-28 11:54:27');
INSERT INTO `exam_classes` VALUES ('14', '20', '13', '2025-10-29 21:18:11', '2025-10-29 21:18:11');
INSERT INTO `exam_classes` VALUES ('15', '20', '14', '2025-10-29 21:18:11', '2025-10-29 21:18:11');
INSERT INTO `exam_classes` VALUES ('16', '20', '15', '2025-10-29 21:18:11', '2025-10-29 21:18:11');
INSERT INTO `exam_classes` VALUES ('17', '21', '14', '2025-10-29 23:24:30', '2025-10-29 23:24:30');
INSERT INTO `exam_classes` VALUES ('18', '21', '15', '2025-10-29 23:24:30', '2025-10-29 23:24:30');
INSERT INTO `exam_classes` VALUES ('19', '21', '16', '2025-10-29 23:24:30', '2025-10-29 23:24:30');
INSERT INTO `exam_classes` VALUES ('20', '22', '13', '2025-11-25 18:47:40', '2025-11-25 18:47:40');
INSERT INTO `exam_classes` VALUES ('21', '22', '14', '2025-11-25 18:47:40', '2025-11-25 18:47:40');
INSERT INTO `exam_classes` VALUES ('22', '22', '15', '2025-11-25 18:47:40', '2025-11-25 18:47:40');


-- Table structure for table `exam_results`
CREATE TABLE `exam_results` (
  `id` int NOT NULL AUTO_INCREMENT,
  `exam_id` int NOT NULL,
  `student_id` int NOT NULL,
  `subject_id` int NOT NULL,
  `marks` decimal(5,2) NOT NULL,
  `grade` varchar(5) NOT NULL,
  `remark` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_exam_student_subject` (`exam_id`,`student_id`,`subject_id`),
  KEY `student_id` (`student_id`),
  KEY `subject_id` (`subject_id`)
) ENGINE=MyISAM AUTO_INCREMENT=251 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `exam_results`
INSERT INTO `exam_results` VALUES ('1', '1', '1', '1', '70.00', '1', NULL, '2025-10-12 02:41:26', '2025-10-22 17:52:13');
INSERT INTO `exam_results` VALUES ('2', '1', '1', '2', '56.00', 'D', NULL, '2025-10-12 02:41:26', '2025-10-12 02:41:26');
INSERT INTO `exam_results` VALUES ('3', '1', '1', '3', '57.00', 'D', NULL, '2025-10-12 02:41:26', '2025-10-12 02:41:26');
INSERT INTO `exam_results` VALUES ('4', '1', '1', '4', '66.00', 'C', NULL, '2025-10-12 02:41:26', '2025-10-12 02:41:26');
INSERT INTO `exam_results` VALUES ('5', '1', '2', '1', '87.00', 'A', NULL, '2025-10-12 02:41:26', '2025-10-12 02:41:26');
INSERT INTO `exam_results` VALUES ('6', '1', '2', '2', '48.00', 'F', NULL, '2025-10-12 02:41:26', '2025-10-12 02:41:26');
INSERT INTO `exam_results` VALUES ('7', '1', '2', '3', '95.00', 'A+', NULL, '2025-10-12 02:41:26', '2025-10-12 02:41:26');
INSERT INTO `exam_results` VALUES ('8', '1', '2', '4', '80.00', 'A', NULL, '2025-10-12 02:41:26', '2025-10-12 02:41:26');
INSERT INTO `exam_results` VALUES ('9', '1', '3', '1', '96.00', 'A+', NULL, '2025-10-12 02:41:26', '2025-10-12 02:41:26');
INSERT INTO `exam_results` VALUES ('10', '1', '3', '2', '78.00', 'B', NULL, '2025-10-12 02:41:26', '2025-10-12 02:41:26');
INSERT INTO `exam_results` VALUES ('11', '1', '3', '3', '96.00', 'A+', NULL, '2025-10-12 02:41:26', '2025-10-12 02:41:26');
INSERT INTO `exam_results` VALUES ('12', '1', '3', '4', '99.00', 'A+', NULL, '2025-10-12 02:41:26', '2025-10-12 02:41:26');
INSERT INTO `exam_results` VALUES ('13', '2', '1', '1', '87.00', '1', NULL, '2025-10-12 02:41:26', '2025-10-18 17:23:36');
INSERT INTO `exam_results` VALUES ('14', '2', '1', '2', '88.00', 'A', NULL, '2025-10-12 02:41:26', '2025-10-12 02:41:26');
INSERT INTO `exam_results` VALUES ('15', '2', '1', '3', '85.00', 'A', NULL, '2025-10-12 02:41:26', '2025-10-12 02:41:26');
INSERT INTO `exam_results` VALUES ('16', '2', '1', '4', '94.00', 'A+', NULL, '2025-10-12 02:41:26', '2025-10-12 02:41:26');
INSERT INTO `exam_results` VALUES ('17', '2', '2', '1', '57.00', 'D', NULL, '2025-10-12 02:41:26', '2025-10-12 02:41:26');
INSERT INTO `exam_results` VALUES ('18', '2', '2', '2', '58.00', 'D', NULL, '2025-10-12 02:41:26', '2025-10-12 02:41:26');
INSERT INTO `exam_results` VALUES ('19', '2', '2', '3', '82.00', 'A', NULL, '2025-10-12 02:41:26', '2025-10-12 02:41:26');
INSERT INTO `exam_results` VALUES ('20', '2', '2', '4', '63.00', 'C', NULL, '2025-10-12 02:41:26', '2025-10-12 02:41:26');
INSERT INTO `exam_results` VALUES ('21', '2', '3', '1', '54.00', 'D', NULL, '2025-10-12 02:41:26', '2025-10-12 02:41:26');
INSERT INTO `exam_results` VALUES ('22', '2', '3', '2', '49.00', 'F', NULL, '2025-10-12 02:41:26', '2025-10-12 02:41:26');
INSERT INTO `exam_results` VALUES ('23', '2', '3', '3', '94.00', 'A+', NULL, '2025-10-12 02:41:26', '2025-10-12 02:41:26');
INSERT INTO `exam_results` VALUES ('24', '2', '3', '4', '44.00', 'F', NULL, '2025-10-12 02:41:26', '2025-10-12 02:41:26');
INSERT INTO `exam_results` VALUES ('25', '3', '1', '1', '97.00', 'A+', NULL, '2025-10-12 02:41:26', '2025-10-12 02:41:26');
INSERT INTO `exam_results` VALUES ('26', '3', '1', '2', '70.00', '1', NULL, '2025-10-12 02:41:26', '2025-10-18 17:18:29');
INSERT INTO `exam_results` VALUES ('27', '3', '1', '3', '42.00', 'F', NULL, '2025-10-12 02:41:26', '2025-10-12 02:41:26');
INSERT INTO `exam_results` VALUES ('28', '3', '1', '4', '58.00', 'D', NULL, '2025-10-12 02:41:26', '2025-10-12 02:41:26');
INSERT INTO `exam_results` VALUES ('29', '3', '2', '1', '51.00', 'D', NULL, '2025-10-12 02:41:26', '2025-10-12 02:41:26');
INSERT INTO `exam_results` VALUES ('30', '3', '2', '2', '85.00', 'A', NULL, '2025-10-12 02:41:26', '2025-10-12 02:41:26');
INSERT INTO `exam_results` VALUES ('31', '3', '2', '3', '97.00', 'A+', NULL, '2025-10-12 02:41:26', '2025-10-12 02:41:26');
INSERT INTO `exam_results` VALUES ('32', '3', '2', '4', '50.00', 'D', NULL, '2025-10-12 02:41:26', '2025-10-12 02:41:26');
INSERT INTO `exam_results` VALUES ('33', '3', '3', '1', '41.00', 'F', NULL, '2025-10-12 02:41:26', '2025-10-12 02:41:26');
INSERT INTO `exam_results` VALUES ('34', '3', '3', '2', '87.00', 'A', NULL, '2025-10-12 02:41:26', '2025-10-12 02:41:26');
INSERT INTO `exam_results` VALUES ('35', '3', '3', '3', '94.00', 'A+', NULL, '2025-10-12 02:41:26', '2025-10-12 02:41:26');
INSERT INTO `exam_results` VALUES ('36', '3', '3', '4', '88.00', 'A', NULL, '2025-10-12 02:41:26', '2025-10-12 02:41:26');
INSERT INTO `exam_results` VALUES ('37', '4', '5', '5', '79.00', '2', NULL, '2025-10-18 15:18:22', '2025-10-18 15:18:22');
INSERT INTO `exam_results` VALUES ('38', '4', '6', '5', '90.00', '1', NULL, '2025-10-18 15:18:22', '2025-10-18 15:18:22');
INSERT INTO `exam_results` VALUES ('39', '4', '7', '5', '48.00', '5', NULL, '2025-10-18 15:18:22', '2025-10-18 15:18:22');
INSERT INTO `exam_results` VALUES ('40', '4', '8', '5', '100.00', '1', NULL, '2025-10-18 15:18:22', '2025-10-18 15:18:22');
INSERT INTO `exam_results` VALUES ('41', '4', '10', '5', '89.00', '1', NULL, '2025-10-18 15:18:22', '2025-10-18 15:18:22');
INSERT INTO `exam_results` VALUES ('42', '4', '4', '5', '67.00', '3', NULL, '2025-10-18 15:18:22', '2025-10-18 15:18:22');
INSERT INTO `exam_results` VALUES ('43', '4', '5', '1', '80.00', '1', NULL, '2025-10-18 16:08:40', '2025-10-22 17:54:03');
INSERT INTO `exam_results` VALUES ('44', '4', '6', '1', '45.00', '5', NULL, '2025-10-18 16:08:40', '2025-10-22 17:54:03');
INSERT INTO `exam_results` VALUES ('45', '4', '7', '1', '78.00', '2', NULL, '2025-10-18 16:08:40', '2025-10-22 17:54:03');
INSERT INTO `exam_results` VALUES ('46', '4', '8', '1', '59.00', '4', NULL, '2025-10-18 16:08:40', '2025-10-22 17:54:03');
INSERT INTO `exam_results` VALUES ('47', '4', '10', '1', '60.00', '3', NULL, '2025-10-18 16:08:40', '2025-10-22 17:54:03');
INSERT INTO `exam_results` VALUES ('48', '4', '4', '1', '69.00', '3', NULL, '2025-10-18 16:08:40', '2025-10-22 17:54:03');
INSERT INTO `exam_results` VALUES ('49', '2', '63', '5', '98.00', '1', NULL, '2025-10-22 20:50:00', '2025-10-22 20:50:00');
INSERT INTO `exam_results` VALUES ('50', '2', '77', '5', '86.00', '1', NULL, '2025-10-22 20:50:00', '2025-10-22 20:50:00');
INSERT INTO `exam_results` VALUES ('51', '2', '21', '5', '78.00', '1', NULL, '2025-10-22 20:50:00', '2025-10-22 20:50:00');
INSERT INTO `exam_results` VALUES ('52', '2', '12', '5', '90.00', '1', NULL, '2025-10-22 20:50:00', '2025-10-22 20:50:00');
INSERT INTO `exam_results` VALUES ('53', '2', '91', '5', '70.00', '1', NULL, '2025-10-22 20:50:00', '2025-10-22 20:50:00');
INSERT INTO `exam_results` VALUES ('54', '2', '49', '5', '65.00', '2', NULL, '2025-10-22 20:50:00', '2025-10-22 20:50:00');
INSERT INTO `exam_results` VALUES ('55', '2', '1', '5', '59.00', '2', NULL, '2025-10-22 20:50:00', '2025-10-22 20:50:00');
INSERT INTO `exam_results` VALUES ('56', '2', '35', '5', '76.00', '1', NULL, '2025-10-22 20:50:00', '2025-10-22 20:50:00');
INSERT INTO `exam_results` VALUES ('57', '2', '105', '5', '45.00', '3', NULL, '2025-10-22 20:50:00', '2025-10-22 20:50:00');
INSERT INTO `exam_results` VALUES ('58', '1', '1', '5', '88.00', 'A', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('59', '1', '1', '6', '86.00', 'A', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('60', '1', '2', '5', '75.00', 'B', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('61', '1', '2', '6', '76.00', 'B', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('62', '1', '3', '5', '91.00', 'A+', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('63', '1', '3', '6', '46.00', 'F', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('64', '2', '1', '6', '76.00', 'B', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('65', '2', '2', '5', '67.00', 'C', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('66', '2', '2', '6', '66.00', 'C', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('67', '2', '3', '5', '56.00', 'D', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('68', '2', '3', '6', '82.00', 'A', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('69', '3', '1', '5', '80.00', 'A', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('70', '3', '1', '6', '55.00', 'D', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('71', '3', '2', '5', '89.00', 'A', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('72', '3', '2', '6', '40.00', 'F', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('73', '3', '3', '5', '71.00', 'B', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('74', '3', '3', '6', '85.00', 'A', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('75', '4', '1', '1', '97.00', 'A+', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('76', '4', '1', '2', '69.00', 'C', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('77', '4', '1', '3', '65.00', 'C', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('78', '4', '1', '4', '56.00', 'D', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('79', '4', '1', '5', '46.00', 'F', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('80', '4', '1', '6', '67.00', 'C', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('81', '4', '2', '1', '90.00', 'A+', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('82', '4', '2', '2', '100.00', 'A+', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('83', '4', '2', '3', '74.00', 'B', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('84', '4', '2', '4', '64.00', 'C', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('85', '4', '2', '5', '94.00', 'A+', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('86', '4', '2', '6', '91.00', 'A+', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('87', '4', '3', '1', '52.00', 'D', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('88', '4', '3', '2', '67.00', 'C', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('89', '4', '3', '3', '99.00', 'A+', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('90', '4', '3', '4', '88.00', 'A', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('91', '4', '3', '5', '91.00', 'A+', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('92', '4', '3', '6', '61.00', 'C', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('93', '5', '1', '1', '77.00', 'B', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('94', '5', '1', '2', '56.00', 'D', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('95', '5', '1', '3', '76.00', 'B', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('96', '5', '1', '4', '42.00', 'F', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('97', '5', '1', '5', '62.00', 'C', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('98', '5', '1', '6', '52.00', 'D', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('99', '5', '2', '1', '72.00', 'B', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('100', '5', '2', '2', '77.00', 'B', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('101', '5', '2', '3', '64.00', 'C', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('102', '5', '2', '4', '66.00', 'C', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('103', '5', '2', '5', '42.00', 'F', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('104', '5', '2', '6', '87.00', 'A', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('105', '5', '3', '1', '98.00', 'A+', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('106', '5', '3', '2', '74.00', 'B', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('107', '5', '3', '3', '48.00', 'F', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('108', '5', '3', '4', '97.00', 'A+', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('109', '5', '3', '5', '56.00', 'D', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('110', '5', '3', '6', '92.00', 'A+', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('111', '6', '1', '1', '79.00', 'B', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('112', '6', '1', '2', '50.00', 'D', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('113', '6', '1', '3', '68.00', 'C', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('114', '6', '1', '4', '96.00', 'A+', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('115', '6', '1', '5', '63.00', 'C', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('116', '6', '1', '6', '83.00', 'A', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('117', '6', '2', '1', '75.00', 'B', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('118', '6', '2', '2', '84.00', 'A', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('119', '6', '2', '3', '41.00', 'F', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('120', '6', '2', '4', '97.00', 'A+', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('121', '6', '2', '5', '93.00', 'A+', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('122', '6', '2', '6', '76.00', 'B', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('123', '6', '3', '1', '80.00', 'A', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('124', '6', '3', '2', '97.00', 'A+', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('125', '6', '3', '3', '83.00', 'A', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('126', '6', '3', '4', '71.00', 'B', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('127', '6', '3', '5', '80.00', 'A', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('128', '6', '3', '6', '80.00', 'A', NULL, '2025-10-23 20:36:54', '2025-10-23 20:36:54');
INSERT INTO `exam_results` VALUES ('129', '7', '114', '7', '80.00', '6', 'Fail', '2025-10-24 15:59:08', '2025-10-24 15:59:08');
INSERT INTO `exam_results` VALUES ('130', '19', '114', '7', '68.00', '3', 'good', '2025-10-28 17:14:27', '2025-10-28 17:14:27');
INSERT INTO `exam_results` VALUES ('131', '19', '113', '7', '80.00', '1', 'Excellent', '2025-10-28 17:14:27', '2025-10-28 17:14:27');
INSERT INTO `exam_results` VALUES ('132', '19', '112', '7', '78.00', '2', 'Very Good', '2025-10-28 17:14:27', '2025-10-28 17:14:27');
INSERT INTO `exam_results` VALUES ('133', '17', '114', '11', '79.00', '2', 'Very Good', '2025-10-28 17:23:29', '2025-10-29 19:11:14');
INSERT INTO `exam_results` VALUES ('134', '17', '113', '11', '89.00', '1', 'Excellent', '2025-10-28 17:23:29', '2025-10-29 19:11:14');
INSERT INTO `exam_results` VALUES ('135', '17', '112', '11', '46.00', '5', 'Poor', '2025-10-28 17:23:29', '2025-10-29 19:11:14');
INSERT INTO `exam_results` VALUES ('136', '18', '114', '10', '87.00', '1', 'Excellent', '2025-10-28 17:29:52', '2025-10-28 17:29:52');
INSERT INTO `exam_results` VALUES ('137', '18', '113', '10', '59.00', '4', 'Fair', '2025-10-28 17:29:52', '2025-10-28 17:29:52');
INSERT INTO `exam_results` VALUES ('138', '18', '112', '10', '68.00', '3', 'good', '2025-10-28 17:29:52', '2025-10-28 17:29:52');
INSERT INTO `exam_results` VALUES ('139', '18', '76', '12', '66.80', '3', 'good', '2025-10-28 17:37:32', '2025-10-28 17:37:32');
INSERT INTO `exam_results` VALUES ('140', '18', '89', '12', '89.00', '1', 'Excellent', '2025-10-28 17:37:32', '2025-10-28 17:37:32');
INSERT INTO `exam_results` VALUES ('141', '18', '48', '12', '56.00', '4', 'Fair', '2025-10-28 17:37:32', '2025-10-28 17:37:32');
INSERT INTO `exam_results` VALUES ('142', '18', '33', '12', '86.00', '1', 'Excellent', '2025-10-28 17:37:32', '2025-10-28 17:37:32');
INSERT INTO `exam_results` VALUES ('143', '18', '75', '12', '98.00', '1', 'Excellent', '2025-10-28 17:37:32', '2025-10-28 17:37:32');
INSERT INTO `exam_results` VALUES ('144', '18', '103', '12', '89.00', '1', 'Excellent', '2025-10-28 17:37:32', '2025-10-28 17:37:32');
INSERT INTO `exam_results` VALUES ('145', '18', '61', '12', '50.00', '4', 'Fair', '2025-10-28 17:37:32', '2025-10-28 17:37:32');
INSERT INTO `exam_results` VALUES ('146', '18', '34', '12', '87.00', '1', 'Excellent', '2025-10-28 17:37:32', '2025-10-28 17:37:32');
INSERT INTO `exam_results` VALUES ('147', '18', '90', '12', '68.00', '3', 'good', '2025-10-28 17:37:32', '2025-10-28 17:37:32');
INSERT INTO `exam_results` VALUES ('148', '18', '104', '12', '79.00', '2', 'Very Good', '2025-10-28 17:37:32', '2025-10-28 17:37:32');
INSERT INTO `exam_results` VALUES ('149', '18', '5', '12', '79.00', '2', 'Very Good', '2025-10-28 17:37:32', '2025-10-28 17:37:32');
INSERT INTO `exam_results` VALUES ('150', '18', '6', '12', '79.00', '2', 'Very Good', '2025-10-28 17:37:32', '2025-10-28 17:37:32');
INSERT INTO `exam_results` VALUES ('151', '18', '7', '12', '80.00', '1', 'Excellent', '2025-10-28 17:37:32', '2025-10-28 17:37:32');
INSERT INTO `exam_results` VALUES ('152', '18', '8', '12', '89.00', '1', 'Excellent', '2025-10-28 17:37:32', '2025-10-28 17:37:32');
INSERT INTO `exam_results` VALUES ('153', '18', '47', '12', '80.00', '1', 'Excellent', '2025-10-28 17:37:32', '2025-10-28 17:37:32');
INSERT INTO `exam_results` VALUES ('154', '18', '10', '12', '78.00', '2', 'Very Good', '2025-10-28 17:37:32', '2025-10-28 17:37:32');
INSERT INTO `exam_results` VALUES ('155', '18', '62', '12', '67.00', '3', 'good', '2025-10-28 17:37:32', '2025-10-28 17:37:32');
INSERT INTO `exam_results` VALUES ('156', '18', '4', '12', '69.00', '3', 'good', '2025-10-28 17:37:32', '2025-10-28 17:37:32');
INSERT INTO `exam_results` VALUES ('157', '17', '114', '10', '65.00', '3', 'good', '2025-10-29 19:10:02', '2025-10-29 19:10:02');
INSERT INTO `exam_results` VALUES ('158', '17', '113', '10', '79.00', '2', 'Very Good', '2025-10-29 19:10:02', '2025-10-29 19:10:02');
INSERT INTO `exam_results` VALUES ('159', '17', '112', '10', '88.00', '1', 'Excellent', '2025-10-29 19:10:02', '2025-10-29 19:10:02');
INSERT INTO `exam_results` VALUES ('160', '17', '114', '12', '56.00', '4', 'Fair', '2025-10-29 19:12:13', '2025-10-29 19:12:13');
INSERT INTO `exam_results` VALUES ('161', '17', '113', '12', '65.00', '3', 'good', '2025-10-29 19:12:13', '2025-10-29 19:12:13');
INSERT INTO `exam_results` VALUES ('162', '17', '112', '12', '90.00', '1', 'Excellent', '2025-10-29 19:12:13', '2025-10-29 19:12:13');
INSERT INTO `exam_results` VALUES ('163', '17', '114', '13', '65.00', '3', 'good', '2025-10-29 19:12:59', '2025-10-29 19:12:59');
INSERT INTO `exam_results` VALUES ('164', '17', '113', '13', '81.00', '1', 'Excellent', '2025-10-29 19:12:59', '2025-10-29 19:12:59');
INSERT INTO `exam_results` VALUES ('165', '17', '112', '13', '77.00', '2', 'Very Good', '2025-10-29 19:12:59', '2025-10-29 19:12:59');
INSERT INTO `exam_results` VALUES ('166', '19', '114', '11', '76.00', '2', 'Very Good', '2025-10-29 19:40:35', '2025-10-29 19:40:35');
INSERT INTO `exam_results` VALUES ('167', '19', '113', '11', '95.00', '1', 'Excellent', '2025-10-29 19:40:35', '2025-10-29 19:40:35');
INSERT INTO `exam_results` VALUES ('168', '19', '112', '11', '15.00', '6', 'Fail', '2025-10-29 19:40:35', '2025-10-29 19:40:35');
INSERT INTO `exam_results` VALUES ('169', '19', '114', '10', '78.00', '2', 'Very Good', '2025-10-29 19:47:58', '2025-10-29 19:47:58');
INSERT INTO `exam_results` VALUES ('170', '19', '113', '10', '49.00', '5', 'Poor', '2025-10-29 19:47:58', '2025-10-29 19:47:58');
INSERT INTO `exam_results` VALUES ('171', '19', '112', '10', '85.00', '1', 'Excellent', '2025-10-29 19:47:58', '2025-10-29 19:47:58');
INSERT INTO `exam_results` VALUES ('172', '19', '114', '8', '79.00', '2', 'Very Good', '2025-10-29 19:49:03', '2025-10-29 19:49:03');
INSERT INTO `exam_results` VALUES ('173', '19', '113', '8', '85.00', '1', 'Excellent', '2025-10-29 19:49:03', '2025-10-29 19:49:03');
INSERT INTO `exam_results` VALUES ('174', '19', '112', '8', '70.00', '2', 'Very Good', '2025-10-29 19:49:03', '2025-10-29 19:49:03');
INSERT INTO `exam_results` VALUES ('175', '19', '114', '9', '79.00', '2', 'Very Good', '2025-10-29 19:50:01', '2025-10-29 19:50:01');
INSERT INTO `exam_results` VALUES ('176', '19', '113', '9', '85.00', '1', 'Excellent', '2025-10-29 19:50:01', '2025-10-29 19:50:01');
INSERT INTO `exam_results` VALUES ('177', '19', '112', '9', '50.00', '4', 'Fair', '2025-10-29 19:50:01', '2025-10-29 19:50:01');
INSERT INTO `exam_results` VALUES ('178', '19', '114', '12', '75.00', '2', 'Very Good', '2025-10-29 19:51:41', '2025-10-29 19:51:41');
INSERT INTO `exam_results` VALUES ('179', '19', '113', '12', '80.00', '1', 'Excellent', '2025-10-29 19:51:41', '2025-10-29 19:51:41');
INSERT INTO `exam_results` VALUES ('180', '19', '112', '12', '68.00', '3', 'good', '2025-10-29 19:51:41', '2025-10-29 19:51:41');
INSERT INTO `exam_results` VALUES ('181', '19', '114', '13', '78.00', '2', 'Very Good', '2025-10-29 19:52:26', '2025-10-29 19:52:26');
INSERT INTO `exam_results` VALUES ('182', '19', '113', '13', '69.00', '3', 'good', '2025-10-29 19:52:26', '2025-10-29 19:52:26');
INSERT INTO `exam_results` VALUES ('183', '19', '112', '13', '81.00', '1', 'Excellent', '2025-10-29 19:52:26', '2025-10-29 19:52:26');
INSERT INTO `exam_results` VALUES ('184', '17', '114', '7', '85.00', '1', 'Excellent', '2025-10-29 20:48:48', '2025-10-29 20:48:48');
INSERT INTO `exam_results` VALUES ('185', '17', '113', '7', '65.00', '3', 'good', '2025-10-29 20:48:48', '2025-10-29 20:48:48');
INSERT INTO `exam_results` VALUES ('186', '17', '112', '7', '70.00', '2', 'Very Good', '2025-10-29 20:48:48', '2025-10-29 20:48:48');
INSERT INTO `exam_results` VALUES ('187', '18', '114', '7', '78.00', '2', 'Very Good', '2025-10-29 20:51:48', '2025-10-29 20:54:48');
INSERT INTO `exam_results` VALUES ('188', '18', '113', '7', '95.00', '1', 'Excellent', '2025-10-29 20:51:48', '2025-10-29 20:54:48');
INSERT INTO `exam_results` VALUES ('189', '18', '112', '7', '85.00', '1', 'Excellent', '2025-10-29 20:51:48', '2025-10-29 20:54:48');
INSERT INTO `exam_results` VALUES ('190', '18', '76', '13', '85.00', '1', 'Excellent', '2025-10-29 20:58:03', '2025-10-29 20:58:03');
INSERT INTO `exam_results` VALUES ('191', '18', '89', '13', '65.00', '3', 'good', '2025-10-29 20:58:03', '2025-10-29 20:58:03');
INSERT INTO `exam_results` VALUES ('192', '18', '48', '13', '95.00', '1', 'Excellent', '2025-10-29 20:58:03', '2025-10-29 20:58:03');
INSERT INTO `exam_results` VALUES ('193', '18', '33', '13', '79.00', '2', 'Very Good', '2025-10-29 20:58:03', '2025-10-29 20:58:03');
INSERT INTO `exam_results` VALUES ('194', '18', '75', '13', '84.00', '1', 'Excellent', '2025-10-29 20:58:03', '2025-10-29 20:58:03');
INSERT INTO `exam_results` VALUES ('195', '18', '103', '13', '94.00', '1', 'Excellent', '2025-10-29 20:58:03', '2025-10-29 20:58:03');
INSERT INTO `exam_results` VALUES ('196', '18', '61', '13', '71.00', '2', 'Very Good', '2025-10-29 20:58:03', '2025-10-29 20:58:03');
INSERT INTO `exam_results` VALUES ('197', '18', '34', '13', '85.00', '1', 'Excellent', '2025-10-29 20:58:03', '2025-10-29 20:58:03');
INSERT INTO `exam_results` VALUES ('198', '18', '90', '13', '85.00', '1', 'Excellent', '2025-10-29 20:58:03', '2025-10-29 20:58:03');
INSERT INTO `exam_results` VALUES ('199', '18', '104', '13', '65.00', '3', 'good', '2025-10-29 20:58:03', '2025-10-29 20:58:03');
INSERT INTO `exam_results` VALUES ('200', '18', '5', '13', '77.00', '2', 'Very Good', '2025-10-29 20:58:03', '2025-10-29 20:58:03');
INSERT INTO `exam_results` VALUES ('201', '18', '6', '13', '66.00', '3', 'good', '2025-10-29 20:58:03', '2025-10-29 20:58:03');
INSERT INTO `exam_results` VALUES ('202', '18', '7', '13', '79.00', '2', 'Very Good', '2025-10-29 20:58:03', '2025-10-29 20:58:03');
INSERT INTO `exam_results` VALUES ('203', '18', '8', '13', '95.00', '1', 'Excellent', '2025-10-29 20:58:03', '2025-10-29 20:58:03');
INSERT INTO `exam_results` VALUES ('204', '18', '47', '13', '85.00', '1', 'Excellent', '2025-10-29 20:58:04', '2025-10-29 20:58:04');
INSERT INTO `exam_results` VALUES ('205', '18', '10', '13', '84.00', '1', 'Excellent', '2025-10-29 20:58:04', '2025-10-29 20:58:04');
INSERT INTO `exam_results` VALUES ('206', '18', '62', '13', '54.00', '4', 'Fair', '2025-10-29 20:58:04', '2025-10-29 20:58:04');
INSERT INTO `exam_results` VALUES ('207', '18', '4', '13', '84.00', '1', 'Excellent', '2025-10-29 20:58:04', '2025-10-29 20:58:04');
INSERT INTO `exam_results` VALUES ('208', '20', '116', '14', '95.00', '1', 'Excellent', '2025-10-29 22:41:04', '2025-10-29 22:54:13');
INSERT INTO `exam_results` VALUES ('209', '20', '115', '14', '59.00', '4', 'Fair', '2025-10-29 22:41:04', '2025-10-29 22:54:13');
INSERT INTO `exam_results` VALUES ('210', '20', '117', '14', '70.00', '2', 'Very Good', '2025-10-29 22:41:04', '2025-10-29 22:54:13');
INSERT INTO `exam_results` VALUES ('211', '20', '118', '16', '75.00', '2', 'Very Good', '2025-10-29 23:00:30', '2025-10-29 23:00:30');
INSERT INTO `exam_results` VALUES ('212', '20', '116', '16', '85.00', '1', 'Excellent', '2025-10-29 23:20:25', '2025-10-29 23:20:25');
INSERT INTO `exam_results` VALUES ('213', '20', '115', '16', '95.00', '1', 'Excellent', '2025-10-29 23:20:25', '2025-10-29 23:20:25');
INSERT INTO `exam_results` VALUES ('214', '20', '117', '16', '77.00', '2', 'Very Good', '2025-10-29 23:20:25', '2025-10-29 23:20:25');
INSERT INTO `exam_results` VALUES ('215', '21', '119', '18', '78.00', '2', 'Very Good', '2025-10-29 23:29:06', '2025-10-29 23:29:06');
INSERT INTO `exam_results` VALUES ('216', '20', '116', '15', '62.00', '3', 'good', '2025-10-30 01:25:41', '2025-10-30 01:25:41');
INSERT INTO `exam_results` VALUES ('217', '20', '118', '15', '89.00', '1', 'Excellent', '2025-10-30 01:25:41', '2025-10-30 01:25:41');
INSERT INTO `exam_results` VALUES ('218', '20', '115', '15', '45.00', '5', 'Poor', '2025-10-30 01:25:41', '2025-10-30 01:25:41');
INSERT INTO `exam_results` VALUES ('219', '20', '117', '15', '88.00', '1', 'Excellent', '2025-10-30 01:25:41', '2025-10-30 01:25:41');
INSERT INTO `exam_results` VALUES ('220', '21', '114', '7', '95.00', '1', 'Excellent', '2025-10-30 10:29:42', '2025-10-30 10:29:42');
INSERT INTO `exam_results` VALUES ('221', '21', '113', '7', '65.00', '3', 'good', '2025-10-30 10:29:42', '2025-10-30 10:29:42');
INSERT INTO `exam_results` VALUES ('222', '21', '112', '7', '85.00', '1', 'Excellent', '2025-10-30 10:29:42', '2025-10-30 10:29:42');
INSERT INTO `exam_results` VALUES ('223', '21', '114', '11', '84.00', '1', 'Excellent', '2025-10-30 10:32:36', '2025-10-30 10:32:36');
INSERT INTO `exam_results` VALUES ('224', '21', '113', '11', '65.00', '3', 'good', '2025-10-30 10:32:36', '2025-10-30 10:32:36');
INSERT INTO `exam_results` VALUES ('225', '21', '112', '11', '95.00', '1', 'Excellent', '2025-10-30 10:32:36', '2025-10-30 10:32:36');
INSERT INTO `exam_results` VALUES ('226', '21', '114', '10', '46.00', '5', 'Poor', '2025-10-30 10:33:31', '2025-10-30 10:33:31');
INSERT INTO `exam_results` VALUES ('227', '21', '113', '10', '59.00', '4', 'Fair', '2025-10-30 10:33:31', '2025-10-30 10:33:31');
INSERT INTO `exam_results` VALUES ('228', '21', '112', '10', '85.00', '1', 'Excellent', '2025-10-30 10:33:31', '2025-10-30 10:33:31');
INSERT INTO `exam_results` VALUES ('229', '21', '114', '12', '85.00', '1', 'Excellent', '2025-10-30 10:35:00', '2025-10-30 10:35:00');
INSERT INTO `exam_results` VALUES ('230', '21', '113', '12', '89.00', '1', 'Excellent', '2025-10-30 10:35:00', '2025-10-30 10:35:00');
INSERT INTO `exam_results` VALUES ('231', '21', '112', '12', '70.00', '2', 'Very Good', '2025-10-30 10:35:00', '2025-10-30 10:35:00');
INSERT INTO `exam_results` VALUES ('232', '21', '114', '13', '78.00', '2', 'Very Good', '2025-10-30 10:35:37', '2025-10-30 10:35:37');
INSERT INTO `exam_results` VALUES ('233', '21', '113', '13', '85.00', '1', 'Excellent', '2025-10-30 10:35:37', '2025-10-30 10:35:37');
INSERT INTO `exam_results` VALUES ('234', '21', '112', '13', '55.00', '4', 'Fair', '2025-10-30 10:35:37', '2025-10-30 10:35:37');
INSERT INTO `exam_results` VALUES ('235', '22', '116', '14', '80.00', '1', 'Excellent', '2025-11-25 18:51:19', '2025-11-25 18:51:19');
INSERT INTO `exam_results` VALUES ('236', '22', '118', '14', '50.00', '4', 'Fair', '2025-11-25 18:51:19', '2025-11-25 18:51:19');
INSERT INTO `exam_results` VALUES ('237', '22', '115', '14', '67.00', '3', 'good', '2025-11-25 18:51:19', '2025-11-25 18:51:19');
INSERT INTO `exam_results` VALUES ('238', '22', '117', '14', '90.00', '1', 'Excellent', '2025-11-25 18:51:19', '2025-11-25 18:51:19');
INSERT INTO `exam_results` VALUES ('239', '22', '116', '16', '60.00', '3', 'good', '2025-11-25 18:52:23', '2025-11-25 18:52:23');
INSERT INTO `exam_results` VALUES ('240', '22', '118', '16', '45.00', '5', 'Poor', '2025-11-25 18:52:23', '2025-11-25 18:52:23');
INSERT INTO `exam_results` VALUES ('241', '22', '115', '16', '79.00', '2', 'Very Good', '2025-11-25 18:52:23', '2025-11-25 18:52:23');
INSERT INTO `exam_results` VALUES ('242', '22', '117', '16', '88.00', '1', 'Excellent', '2025-11-25 18:52:23', '2025-11-25 18:52:23');
INSERT INTO `exam_results` VALUES ('243', '22', '116', '15', '90.00', '1', 'Excellent', '2025-11-25 18:53:53', '2025-11-25 18:53:53');
INSERT INTO `exam_results` VALUES ('244', '22', '118', '15', '83.00', '1', 'Excellent', '2025-11-25 18:53:53', '2025-11-25 18:53:53');
INSERT INTO `exam_results` VALUES ('245', '22', '115', '15', '46.00', '5', 'Poor', '2025-11-25 18:53:53', '2025-11-25 18:53:53');
INSERT INTO `exam_results` VALUES ('246', '22', '117', '15', '66.00', '3', 'good', '2025-11-25 18:53:53', '2025-11-25 18:53:53');
INSERT INTO `exam_results` VALUES ('247', '22', '116', '17', '98.00', '1', 'Excellent', '2025-11-25 18:54:36', '2025-11-25 18:54:36');
INSERT INTO `exam_results` VALUES ('248', '22', '118', '17', '65.00', '3', 'good', '2025-11-25 18:54:36', '2025-11-25 18:54:36');
INSERT INTO `exam_results` VALUES ('249', '22', '115', '17', '45.00', '5', 'Poor', '2025-11-25 18:54:36', '2025-11-25 18:54:36');
INSERT INTO `exam_results` VALUES ('250', '22', '117', '17', '89.00', '1', 'Excellent', '2025-11-25 18:54:36', '2025-11-25 18:54:36');


-- Table structure for table `exams`
CREATE TABLE `exams` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `term` enum('1st Term','2nd Term','3rd Term') NOT NULL,
  `class_id` int NOT NULL,
  `academic_year_id` int DEFAULT NULL,
  `date` date NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `grading_scale_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `class_id` (`class_id`),
  KEY `fk_exams_academic_year` (`academic_year_id`),
  KEY `fk_exams_grading_scale` (`grading_scale_id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `exams`
INSERT INTO `exams` VALUES ('18', 'Final Exam', '1st Term', '13', '2', '2025-10-28', '', '2025-10-28 11:53:21', '2025-10-29 18:54:22', '1');
INSERT INTO `exams` VALUES ('19', 'TERm', '1st Term', '14', '2', '2025-10-28', '', '2025-10-28 11:54:27', '2025-10-28 11:54:27', NULL);
INSERT INTO `exams` VALUES ('17', 'Midterm Exam', '1st Term', '6', '2', '2025-10-28', '', '2025-10-28 11:22:51', '2025-10-28 11:22:51', NULL);
INSERT INTO `exams` VALUES ('20', 'End OF TERM', '1st Term', '13', '2', '2025-10-29', '', '2025-10-29 21:18:11', '2025-10-29 21:18:11', '1');
INSERT INTO `exams` VALUES ('21', 'PROMOTIONAL EXAM', '1st Term', '14', '2', '2025-10-29', '', '2025-10-29 23:24:30', '2025-10-29 23:24:30', '1');
INSERT INTO `exams` VALUES ('22', 'MID-TERM 2025', '1st Term', '13', '2', '2025-11-20', 'EKUBAN', '2025-11-25 18:47:40', '2025-11-25 18:47:40', '1');


-- Table structure for table `fee_assignments`
CREATE TABLE `fee_assignments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fee_id` int NOT NULL,
  `student_id` int NOT NULL,
  `assigned_date` date DEFAULT (curdate()),
  `status` enum('active','cancelled') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_fee_student` (`fee_id`,`student_id`),
  KEY `student_id` (`student_id`)
) ENGINE=MyISAM AUTO_INCREMENT=278 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `fee_assignments`
INSERT INTO `fee_assignments` VALUES ('1', '1', '1', '2025-11-04', 'active', '2025-11-04 10:58:14', '2025-11-04 10:58:14');
INSERT INTO `fee_assignments` VALUES ('2', '6', '116', '2025-11-04', 'active', '2025-11-04 11:10:37', '2025-11-04 11:10:37');
INSERT INTO `fee_assignments` VALUES ('3', '6', '118', '2025-11-04', 'active', '2025-11-04 11:10:37', '2025-11-04 11:10:37');
INSERT INTO `fee_assignments` VALUES ('4', '6', '115', '2025-11-04', 'active', '2025-11-04 11:10:37', '2025-11-04 11:10:37');
INSERT INTO `fee_assignments` VALUES ('5', '6', '117', '2025-11-04', 'active', '2025-11-04 11:10:37', '2025-11-04 11:10:37');
INSERT INTO `fee_assignments` VALUES ('6', '7', '76', '2025-11-04', 'active', '2025-11-04 11:12:32', '2025-11-04 11:12:32');
INSERT INTO `fee_assignments` VALUES ('7', '7', '89', '2025-11-04', 'active', '2025-11-04 11:12:32', '2025-11-04 11:12:32');
INSERT INTO `fee_assignments` VALUES ('8', '7', '48', '2025-11-04', 'active', '2025-11-04 11:12:32', '2025-11-04 11:12:32');
INSERT INTO `fee_assignments` VALUES ('9', '7', '33', '2025-11-04', 'active', '2025-11-04 11:12:32', '2025-11-04 11:12:32');
INSERT INTO `fee_assignments` VALUES ('10', '7', '75', '2025-11-04', 'active', '2025-11-04 11:12:32', '2025-11-04 11:12:32');
INSERT INTO `fee_assignments` VALUES ('11', '7', '103', '2025-11-04', 'active', '2025-11-04 11:12:32', '2025-11-04 11:12:32');
INSERT INTO `fee_assignments` VALUES ('12', '7', '61', '2025-11-04', 'active', '2025-11-04 11:12:32', '2025-11-04 11:12:32');
INSERT INTO `fee_assignments` VALUES ('13', '7', '34', '2025-11-04', 'active', '2025-11-04 11:12:32', '2025-11-04 11:12:32');
INSERT INTO `fee_assignments` VALUES ('14', '7', '90', '2025-11-04', 'active', '2025-11-04 11:12:32', '2025-11-04 11:12:32');
INSERT INTO `fee_assignments` VALUES ('15', '7', '104', '2025-11-04', 'active', '2025-11-04 11:12:32', '2025-11-04 11:12:32');
INSERT INTO `fee_assignments` VALUES ('16', '7', '5', '2025-11-04', 'active', '2025-11-04 11:12:32', '2025-11-04 11:12:32');
INSERT INTO `fee_assignments` VALUES ('17', '7', '6', '2025-11-04', 'active', '2025-11-04 11:12:32', '2025-11-04 11:12:32');
INSERT INTO `fee_assignments` VALUES ('18', '7', '7', '2025-11-04', 'active', '2025-11-04 11:12:32', '2025-11-04 11:12:32');
INSERT INTO `fee_assignments` VALUES ('19', '7', '8', '2025-11-04', 'active', '2025-11-04 11:12:32', '2025-11-04 11:12:32');
INSERT INTO `fee_assignments` VALUES ('20', '7', '47', '2025-11-04', 'active', '2025-11-04 11:12:32', '2025-11-04 11:12:32');
INSERT INTO `fee_assignments` VALUES ('21', '7', '10', '2025-11-04', 'active', '2025-11-04 11:12:32', '2025-11-04 11:12:32');
INSERT INTO `fee_assignments` VALUES ('22', '7', '62', '2025-11-04', 'active', '2025-11-04 11:12:32', '2025-11-04 11:12:32');
INSERT INTO `fee_assignments` VALUES ('23', '7', '4', '2025-11-04', 'active', '2025-11-04 11:12:32', '2025-11-04 11:12:32');
INSERT INTO `fee_assignments` VALUES ('24', '8', '76', '2025-11-04', 'active', '2025-11-04 11:21:15', '2025-11-04 11:21:15');
INSERT INTO `fee_assignments` VALUES ('25', '8', '89', '2025-11-04', 'active', '2025-11-04 11:21:15', '2025-11-04 11:21:15');
INSERT INTO `fee_assignments` VALUES ('26', '8', '48', '2025-11-04', 'active', '2025-11-04 11:21:15', '2025-11-04 11:21:15');
INSERT INTO `fee_assignments` VALUES ('27', '8', '33', '2025-11-04', 'active', '2025-11-04 11:21:15', '2025-11-04 11:21:15');
INSERT INTO `fee_assignments` VALUES ('28', '8', '75', '2025-11-04', 'active', '2025-11-04 11:21:15', '2025-11-04 11:21:15');
INSERT INTO `fee_assignments` VALUES ('29', '8', '103', '2025-11-04', 'active', '2025-11-04 11:21:15', '2025-11-04 11:21:15');
INSERT INTO `fee_assignments` VALUES ('30', '8', '61', '2025-11-04', 'active', '2025-11-04 11:21:15', '2025-11-04 11:21:15');
INSERT INTO `fee_assignments` VALUES ('31', '8', '34', '2025-11-04', 'active', '2025-11-04 11:21:15', '2025-11-04 11:21:15');
INSERT INTO `fee_assignments` VALUES ('32', '8', '90', '2025-11-04', 'active', '2025-11-04 11:21:15', '2025-11-04 11:21:15');
INSERT INTO `fee_assignments` VALUES ('33', '8', '104', '2025-11-04', 'active', '2025-11-04 11:21:15', '2025-11-04 11:21:15');
INSERT INTO `fee_assignments` VALUES ('34', '8', '5', '2025-11-04', 'active', '2025-11-04 11:21:15', '2025-11-04 11:21:15');
INSERT INTO `fee_assignments` VALUES ('35', '8', '6', '2025-11-04', 'active', '2025-11-04 11:21:15', '2025-11-04 11:21:15');
INSERT INTO `fee_assignments` VALUES ('36', '8', '7', '2025-11-04', 'active', '2025-11-04 11:21:15', '2025-11-04 11:21:15');
INSERT INTO `fee_assignments` VALUES ('37', '8', '8', '2025-11-04', 'active', '2025-11-04 11:21:15', '2025-11-04 11:21:15');
INSERT INTO `fee_assignments` VALUES ('38', '8', '47', '2025-11-04', 'active', '2025-11-04 11:21:15', '2025-11-04 11:21:15');
INSERT INTO `fee_assignments` VALUES ('39', '8', '10', '2025-11-04', 'active', '2025-11-04 11:21:15', '2025-11-04 11:21:15');
INSERT INTO `fee_assignments` VALUES ('40', '8', '62', '2025-11-04', 'active', '2025-11-04 11:21:15', '2025-11-04 11:21:15');
INSERT INTO `fee_assignments` VALUES ('41', '8', '4', '2025-11-04', 'active', '2025-11-04 11:21:15', '2025-11-04 11:21:15');
INSERT INTO `fee_assignments` VALUES ('42', '8', '114', '2025-11-04', 'active', '2025-11-04 11:21:15', '2025-11-04 11:21:15');
INSERT INTO `fee_assignments` VALUES ('43', '8', '113', '2025-11-04', 'active', '2025-11-04 11:21:15', '2025-11-04 11:21:15');
INSERT INTO `fee_assignments` VALUES ('44', '8', '112', '2025-11-04', 'active', '2025-11-04 11:21:15', '2025-11-04 11:21:15');
INSERT INTO `fee_assignments` VALUES ('45', '8', '1', '2025-11-04', 'active', '2025-11-04 11:24:15', '2025-11-04 11:24:15');
INSERT INTO `fee_assignments` VALUES ('46', '8', '2', '2025-11-04', 'active', '2025-11-04 11:24:15', '2025-11-04 11:24:15');
INSERT INTO `fee_assignments` VALUES ('47', '8', '3', '2025-11-04', 'active', '2025-11-04 11:24:15', '2025-11-04 11:24:15');
INSERT INTO `fee_assignments` VALUES ('48', '9', '10', '2025-11-04', 'active', '2025-11-04 12:53:27', '2025-11-04 12:53:27');
INSERT INTO `fee_assignments` VALUES ('49', '9', '11', '2025-11-04', 'active', '2025-11-04 12:53:27', '2025-11-04 12:53:27');
INSERT INTO `fee_assignments` VALUES ('50', '9', '12', '2025-11-04', 'active', '2025-11-04 12:53:27', '2025-11-04 12:53:27');
INSERT INTO `fee_assignments` VALUES ('51', '9', '13', '2025-11-04', 'active', '2025-11-04 12:53:27', '2025-11-04 12:53:27');
INSERT INTO `fee_assignments` VALUES ('52', '9', '14', '2025-11-04', 'active', '2025-11-04 12:53:27', '2025-11-04 12:53:27');
INSERT INTO `fee_assignments` VALUES ('53', '9', '15', '2025-11-04', 'active', '2025-11-04 12:53:27', '2025-11-04 12:53:27');
INSERT INTO `fee_assignments` VALUES ('54', '10', '76', '2025-11-04', 'active', '2025-11-04 13:22:03', '2025-11-04 13:22:03');
INSERT INTO `fee_assignments` VALUES ('55', '10', '89', '2025-11-04', 'active', '2025-11-04 13:22:03', '2025-11-04 13:22:03');
INSERT INTO `fee_assignments` VALUES ('56', '10', '48', '2025-11-04', 'active', '2025-11-04 13:22:03', '2025-11-04 13:22:03');
INSERT INTO `fee_assignments` VALUES ('57', '10', '33', '2025-11-04', 'active', '2025-11-04 13:22:03', '2025-11-04 13:22:03');
INSERT INTO `fee_assignments` VALUES ('58', '10', '75', '2025-11-04', 'active', '2025-11-04 13:22:03', '2025-11-04 13:22:03');
INSERT INTO `fee_assignments` VALUES ('59', '10', '103', '2025-11-04', 'active', '2025-11-04 13:22:03', '2025-11-04 13:22:03');
INSERT INTO `fee_assignments` VALUES ('60', '10', '61', '2025-11-04', 'active', '2025-11-04 13:22:03', '2025-11-04 13:22:03');
INSERT INTO `fee_assignments` VALUES ('61', '10', '34', '2025-11-04', 'active', '2025-11-04 13:22:03', '2025-11-04 13:22:03');
INSERT INTO `fee_assignments` VALUES ('62', '10', '90', '2025-11-04', 'active', '2025-11-04 13:22:03', '2025-11-04 13:22:03');
INSERT INTO `fee_assignments` VALUES ('63', '10', '104', '2025-11-04', 'active', '2025-11-04 13:22:03', '2025-11-04 13:22:03');
INSERT INTO `fee_assignments` VALUES ('64', '10', '5', '2025-11-04', 'active', '2025-11-04 13:22:03', '2025-11-04 13:22:03');
INSERT INTO `fee_assignments` VALUES ('65', '10', '6', '2025-11-04', 'active', '2025-11-04 13:22:03', '2025-11-04 13:22:03');
INSERT INTO `fee_assignments` VALUES ('66', '10', '7', '2025-11-04', 'active', '2025-11-04 13:22:03', '2025-11-04 13:22:03');
INSERT INTO `fee_assignments` VALUES ('67', '10', '8', '2025-11-04', 'active', '2025-11-04 13:22:03', '2025-11-04 13:22:03');
INSERT INTO `fee_assignments` VALUES ('68', '10', '47', '2025-11-04', 'active', '2025-11-04 13:22:03', '2025-11-04 13:22:03');
INSERT INTO `fee_assignments` VALUES ('69', '10', '10', '2025-11-04', 'active', '2025-11-04 13:22:03', '2025-11-04 13:22:03');
INSERT INTO `fee_assignments` VALUES ('70', '10', '62', '2025-11-04', 'active', '2025-11-04 13:22:03', '2025-11-04 13:22:03');
INSERT INTO `fee_assignments` VALUES ('71', '10', '4', '2025-11-04', 'active', '2025-11-04 13:22:03', '2025-11-04 13:22:03');
INSERT INTO `fee_assignments` VALUES ('72', '10', '114', '2025-11-04', 'active', '2025-11-04 13:22:03', '2025-11-04 13:22:03');
INSERT INTO `fee_assignments` VALUES ('73', '10', '113', '2025-11-04', 'active', '2025-11-04 13:22:03', '2025-11-04 13:22:03');
INSERT INTO `fee_assignments` VALUES ('74', '10', '112', '2025-11-04', 'active', '2025-11-04 13:22:03', '2025-11-04 13:22:03');
INSERT INTO `fee_assignments` VALUES ('75', '10', '121', '2025-11-04', 'active', '2025-11-04 13:34:38', '2025-11-04 13:34:38');
INSERT INTO `fee_assignments` VALUES ('76', '11', '76', '2025-11-06', 'active', '2025-11-06 23:09:57', '2025-11-06 23:09:57');
INSERT INTO `fee_assignments` VALUES ('77', '11', '89', '2025-11-06', 'active', '2025-11-06 23:09:57', '2025-11-06 23:09:57');
INSERT INTO `fee_assignments` VALUES ('78', '11', '48', '2025-11-06', 'active', '2025-11-06 23:09:57', '2025-11-06 23:09:57');
INSERT INTO `fee_assignments` VALUES ('79', '11', '33', '2025-11-06', 'active', '2025-11-06 23:09:57', '2025-11-06 23:09:57');
INSERT INTO `fee_assignments` VALUES ('80', '11', '75', '2025-11-06', 'active', '2025-11-06 23:09:57', '2025-11-06 23:09:57');
INSERT INTO `fee_assignments` VALUES ('81', '11', '103', '2025-11-06', 'active', '2025-11-06 23:09:57', '2025-11-06 23:09:57');
INSERT INTO `fee_assignments` VALUES ('82', '11', '61', '2025-11-06', 'active', '2025-11-06 23:09:57', '2025-11-06 23:09:57');
INSERT INTO `fee_assignments` VALUES ('83', '11', '34', '2025-11-06', 'active', '2025-11-06 23:09:57', '2025-11-06 23:09:57');
INSERT INTO `fee_assignments` VALUES ('84', '11', '90', '2025-11-06', 'active', '2025-11-06 23:09:57', '2025-11-06 23:09:57');
INSERT INTO `fee_assignments` VALUES ('85', '11', '104', '2025-11-06', 'active', '2025-11-06 23:09:57', '2025-11-06 23:09:57');
INSERT INTO `fee_assignments` VALUES ('86', '11', '5', '2025-11-06', 'active', '2025-11-06 23:09:57', '2025-11-06 23:09:57');
INSERT INTO `fee_assignments` VALUES ('87', '11', '6', '2025-11-06', 'active', '2025-11-06 23:09:57', '2025-11-06 23:09:57');
INSERT INTO `fee_assignments` VALUES ('88', '11', '7', '2025-11-06', 'active', '2025-11-06 23:09:57', '2025-11-06 23:09:57');
INSERT INTO `fee_assignments` VALUES ('89', '11', '8', '2025-11-06', 'active', '2025-11-06 23:09:57', '2025-11-06 23:09:57');
INSERT INTO `fee_assignments` VALUES ('90', '11', '47', '2025-11-06', 'active', '2025-11-06 23:09:57', '2025-11-06 23:09:57');
INSERT INTO `fee_assignments` VALUES ('91', '11', '10', '2025-11-06', 'active', '2025-11-06 23:09:57', '2025-11-06 23:09:57');
INSERT INTO `fee_assignments` VALUES ('92', '11', '62', '2025-11-06', 'active', '2025-11-06 23:09:57', '2025-11-06 23:09:57');
INSERT INTO `fee_assignments` VALUES ('93', '11', '4', '2025-11-06', 'active', '2025-11-06 23:09:57', '2025-11-06 23:09:57');
INSERT INTO `fee_assignments` VALUES ('94', '11', '114', '2025-11-06', 'active', '2025-11-06 23:09:57', '2025-11-06 23:09:57');
INSERT INTO `fee_assignments` VALUES ('95', '11', '113', '2025-11-06', 'active', '2025-11-06 23:09:57', '2025-11-06 23:09:57');
INSERT INTO `fee_assignments` VALUES ('96', '11', '121', '2025-11-06', 'active', '2025-11-06 23:09:57', '2025-11-06 23:09:57');
INSERT INTO `fee_assignments` VALUES ('97', '11', '112', '2025-11-06', 'active', '2025-11-06 23:09:57', '2025-11-06 23:09:57');
INSERT INTO `fee_assignments` VALUES ('98', '12', '76', '2025-11-06', 'active', '2025-11-06 23:10:44', '2025-11-06 23:10:44');
INSERT INTO `fee_assignments` VALUES ('99', '12', '89', '2025-11-06', 'active', '2025-11-06 23:10:44', '2025-11-06 23:10:44');
INSERT INTO `fee_assignments` VALUES ('100', '12', '48', '2025-11-06', 'active', '2025-11-06 23:10:44', '2025-11-06 23:10:44');
INSERT INTO `fee_assignments` VALUES ('101', '12', '33', '2025-11-06', 'active', '2025-11-06 23:10:44', '2025-11-06 23:10:44');
INSERT INTO `fee_assignments` VALUES ('102', '12', '75', '2025-11-06', 'active', '2025-11-06 23:10:44', '2025-11-06 23:10:44');
INSERT INTO `fee_assignments` VALUES ('103', '12', '103', '2025-11-06', 'active', '2025-11-06 23:10:44', '2025-11-06 23:10:44');
INSERT INTO `fee_assignments` VALUES ('104', '12', '61', '2025-11-06', 'active', '2025-11-06 23:10:44', '2025-11-06 23:10:44');
INSERT INTO `fee_assignments` VALUES ('105', '12', '34', '2025-11-06', 'active', '2025-11-06 23:10:44', '2025-11-06 23:10:44');
INSERT INTO `fee_assignments` VALUES ('106', '12', '90', '2025-11-06', 'active', '2025-11-06 23:10:44', '2025-11-06 23:10:44');
INSERT INTO `fee_assignments` VALUES ('107', '12', '104', '2025-11-06', 'active', '2025-11-06 23:10:44', '2025-11-06 23:10:44');
INSERT INTO `fee_assignments` VALUES ('108', '12', '5', '2025-11-06', 'active', '2025-11-06 23:10:44', '2025-11-06 23:10:44');
INSERT INTO `fee_assignments` VALUES ('109', '12', '6', '2025-11-06', 'active', '2025-11-06 23:10:44', '2025-11-06 23:10:44');
INSERT INTO `fee_assignments` VALUES ('110', '12', '7', '2025-11-06', 'active', '2025-11-06 23:10:44', '2025-11-06 23:10:44');
INSERT INTO `fee_assignments` VALUES ('111', '12', '8', '2025-11-06', 'active', '2025-11-06 23:10:44', '2025-11-06 23:10:44');
INSERT INTO `fee_assignments` VALUES ('112', '12', '47', '2025-11-06', 'active', '2025-11-06 23:10:44', '2025-11-06 23:10:44');
INSERT INTO `fee_assignments` VALUES ('113', '12', '10', '2025-11-06', 'active', '2025-11-06 23:10:44', '2025-11-06 23:10:44');
INSERT INTO `fee_assignments` VALUES ('114', '12', '62', '2025-11-06', 'active', '2025-11-06 23:10:44', '2025-11-06 23:10:44');
INSERT INTO `fee_assignments` VALUES ('115', '12', '4', '2025-11-06', 'active', '2025-11-06 23:10:44', '2025-11-06 23:10:44');
INSERT INTO `fee_assignments` VALUES ('116', '12', '114', '2025-11-06', 'active', '2025-11-06 23:10:44', '2025-11-06 23:10:44');
INSERT INTO `fee_assignments` VALUES ('117', '12', '113', '2025-11-06', 'active', '2025-11-06 23:10:44', '2025-11-06 23:10:44');
INSERT INTO `fee_assignments` VALUES ('118', '12', '121', '2025-11-06', 'active', '2025-11-06 23:10:44', '2025-11-06 23:10:44');
INSERT INTO `fee_assignments` VALUES ('119', '12', '112', '2025-11-06', 'active', '2025-11-06 23:10:44', '2025-11-06 23:10:44');
INSERT INTO `fee_assignments` VALUES ('120', '13', '76', '2025-11-06', 'active', '2025-11-06 23:11:31', '2025-11-06 23:11:31');
INSERT INTO `fee_assignments` VALUES ('121', '13', '89', '2025-11-06', 'active', '2025-11-06 23:11:31', '2025-11-06 23:11:31');
INSERT INTO `fee_assignments` VALUES ('122', '13', '48', '2025-11-06', 'active', '2025-11-06 23:11:31', '2025-11-06 23:11:31');
INSERT INTO `fee_assignments` VALUES ('123', '13', '33', '2025-11-06', 'active', '2025-11-06 23:11:31', '2025-11-06 23:11:31');
INSERT INTO `fee_assignments` VALUES ('124', '13', '75', '2025-11-06', 'active', '2025-11-06 23:11:31', '2025-11-06 23:11:31');
INSERT INTO `fee_assignments` VALUES ('125', '13', '103', '2025-11-06', 'active', '2025-11-06 23:11:31', '2025-11-06 23:11:31');
INSERT INTO `fee_assignments` VALUES ('126', '13', '61', '2025-11-06', 'active', '2025-11-06 23:11:31', '2025-11-06 23:11:31');
INSERT INTO `fee_assignments` VALUES ('127', '13', '34', '2025-11-06', 'active', '2025-11-06 23:11:31', '2025-11-06 23:11:31');
INSERT INTO `fee_assignments` VALUES ('128', '13', '90', '2025-11-06', 'active', '2025-11-06 23:11:31', '2025-11-06 23:11:31');
INSERT INTO `fee_assignments` VALUES ('129', '13', '104', '2025-11-06', 'active', '2025-11-06 23:11:31', '2025-11-06 23:11:31');
INSERT INTO `fee_assignments` VALUES ('130', '13', '5', '2025-11-06', 'active', '2025-11-06 23:11:31', '2025-11-06 23:11:31');
INSERT INTO `fee_assignments` VALUES ('131', '13', '6', '2025-11-06', 'active', '2025-11-06 23:11:31', '2025-11-06 23:11:31');
INSERT INTO `fee_assignments` VALUES ('132', '13', '7', '2025-11-06', 'active', '2025-11-06 23:11:31', '2025-11-06 23:11:31');
INSERT INTO `fee_assignments` VALUES ('133', '13', '8', '2025-11-06', 'active', '2025-11-06 23:11:31', '2025-11-06 23:11:31');
INSERT INTO `fee_assignments` VALUES ('134', '13', '47', '2025-11-06', 'active', '2025-11-06 23:11:31', '2025-11-06 23:11:31');
INSERT INTO `fee_assignments` VALUES ('135', '13', '10', '2025-11-06', 'active', '2025-11-06 23:11:31', '2025-11-06 23:11:31');
INSERT INTO `fee_assignments` VALUES ('136', '13', '62', '2025-11-06', 'active', '2025-11-06 23:11:31', '2025-11-06 23:11:31');
INSERT INTO `fee_assignments` VALUES ('137', '13', '4', '2025-11-06', 'active', '2025-11-06 23:11:31', '2025-11-06 23:11:31');
INSERT INTO `fee_assignments` VALUES ('138', '13', '114', '2025-11-06', 'active', '2025-11-06 23:11:31', '2025-11-06 23:11:31');
INSERT INTO `fee_assignments` VALUES ('139', '13', '113', '2025-11-06', 'active', '2025-11-06 23:11:31', '2025-11-06 23:11:31');
INSERT INTO `fee_assignments` VALUES ('140', '13', '121', '2025-11-06', 'active', '2025-11-06 23:11:31', '2025-11-06 23:11:31');
INSERT INTO `fee_assignments` VALUES ('141', '13', '112', '2025-11-06', 'active', '2025-11-06 23:11:31', '2025-11-06 23:11:31');
INSERT INTO `fee_assignments` VALUES ('142', '14', '76', '2025-11-06', 'active', '2025-11-06 23:48:07', '2025-11-06 23:48:07');
INSERT INTO `fee_assignments` VALUES ('143', '14', '89', '2025-11-06', 'active', '2025-11-06 23:48:07', '2025-11-06 23:48:07');
INSERT INTO `fee_assignments` VALUES ('144', '14', '48', '2025-11-06', 'active', '2025-11-06 23:48:07', '2025-11-06 23:48:07');
INSERT INTO `fee_assignments` VALUES ('145', '14', '33', '2025-11-06', 'active', '2025-11-06 23:48:07', '2025-11-06 23:48:07');
INSERT INTO `fee_assignments` VALUES ('146', '14', '75', '2025-11-06', 'active', '2025-11-06 23:48:07', '2025-11-06 23:48:07');
INSERT INTO `fee_assignments` VALUES ('147', '14', '103', '2025-11-06', 'active', '2025-11-06 23:48:07', '2025-11-06 23:48:07');
INSERT INTO `fee_assignments` VALUES ('148', '14', '61', '2025-11-06', 'active', '2025-11-06 23:48:07', '2025-11-06 23:48:07');
INSERT INTO `fee_assignments` VALUES ('149', '14', '34', '2025-11-06', 'active', '2025-11-06 23:48:07', '2025-11-06 23:48:07');
INSERT INTO `fee_assignments` VALUES ('150', '14', '90', '2025-11-06', 'active', '2025-11-06 23:48:07', '2025-11-06 23:48:07');
INSERT INTO `fee_assignments` VALUES ('151', '14', '104', '2025-11-06', 'active', '2025-11-06 23:48:07', '2025-11-06 23:48:07');
INSERT INTO `fee_assignments` VALUES ('152', '14', '5', '2025-11-06', 'active', '2025-11-06 23:48:07', '2025-11-06 23:48:07');
INSERT INTO `fee_assignments` VALUES ('153', '14', '6', '2025-11-06', 'active', '2025-11-06 23:48:07', '2025-11-06 23:48:07');
INSERT INTO `fee_assignments` VALUES ('154', '14', '7', '2025-11-06', 'active', '2025-11-06 23:48:07', '2025-11-06 23:48:07');
INSERT INTO `fee_assignments` VALUES ('155', '14', '8', '2025-11-06', 'active', '2025-11-06 23:48:07', '2025-11-06 23:48:07');
INSERT INTO `fee_assignments` VALUES ('156', '14', '47', '2025-11-06', 'active', '2025-11-06 23:48:07', '2025-11-06 23:48:07');
INSERT INTO `fee_assignments` VALUES ('157', '14', '10', '2025-11-06', 'active', '2025-11-06 23:48:07', '2025-11-06 23:48:07');
INSERT INTO `fee_assignments` VALUES ('158', '14', '62', '2025-11-06', 'active', '2025-11-06 23:48:07', '2025-11-06 23:48:07');
INSERT INTO `fee_assignments` VALUES ('159', '14', '4', '2025-11-06', 'active', '2025-11-06 23:48:07', '2025-11-06 23:48:07');
INSERT INTO `fee_assignments` VALUES ('160', '14', '114', '2025-11-06', 'active', '2025-11-06 23:48:07', '2025-11-06 23:48:07');
INSERT INTO `fee_assignments` VALUES ('161', '14', '113', '2025-11-06', 'active', '2025-11-06 23:48:07', '2025-11-06 23:48:07');
INSERT INTO `fee_assignments` VALUES ('162', '14', '121', '2025-11-06', 'active', '2025-11-06 23:48:07', '2025-11-06 23:48:07');
INSERT INTO `fee_assignments` VALUES ('163', '14', '112', '2025-11-06', 'active', '2025-11-06 23:48:07', '2025-11-06 23:48:07');
INSERT INTO `fee_assignments` VALUES ('164', '15', '63', '2025-11-13', 'active', '2025-11-13 01:11:43', '2025-11-13 01:11:43');
INSERT INTO `fee_assignments` VALUES ('165', '15', '77', '2025-11-13', 'active', '2025-11-13 01:11:43', '2025-11-13 01:11:43');
INSERT INTO `fee_assignments` VALUES ('166', '15', '21', '2025-11-13', 'active', '2025-11-13 01:11:43', '2025-11-13 01:11:43');
INSERT INTO `fee_assignments` VALUES ('167', '15', '12', '2025-11-13', 'active', '2025-11-13 01:11:43', '2025-11-13 01:11:43');
INSERT INTO `fee_assignments` VALUES ('168', '15', '91', '2025-11-13', 'active', '2025-11-13 01:11:43', '2025-11-13 01:11:43');
INSERT INTO `fee_assignments` VALUES ('169', '15', '49', '2025-11-13', 'active', '2025-11-13 01:11:43', '2025-11-13 01:11:43');
INSERT INTO `fee_assignments` VALUES ('170', '15', '1', '2025-11-13', 'active', '2025-11-13 01:11:43', '2025-11-13 01:11:43');
INSERT INTO `fee_assignments` VALUES ('171', '15', '35', '2025-11-13', 'active', '2025-11-13 01:11:43', '2025-11-13 01:11:43');
INSERT INTO `fee_assignments` VALUES ('172', '15', '105', '2025-11-13', 'active', '2025-11-13 01:11:43', '2025-11-13 01:11:43');
INSERT INTO `fee_assignments` VALUES ('173', '15', '122', '2025-11-13', 'active', '2025-11-13 01:11:43', '2025-11-13 01:11:43');
INSERT INTO `fee_assignments` VALUES ('174', '15', '50', '2025-11-13', 'active', '2025-11-13 01:11:43', '2025-11-13 01:11:43');
INSERT INTO `fee_assignments` VALUES ('175', '15', '13', '2025-11-13', 'active', '2025-11-13 01:11:43', '2025-11-13 01:11:43');
INSERT INTO `fee_assignments` VALUES ('176', '15', '78', '2025-11-13', 'active', '2025-11-13 01:11:43', '2025-11-13 01:11:43');
INSERT INTO `fee_assignments` VALUES ('177', '15', '92', '2025-11-13', 'active', '2025-11-13 01:11:43', '2025-11-13 01:11:43');
INSERT INTO `fee_assignments` VALUES ('178', '15', '36', '2025-11-13', 'active', '2025-11-13 01:11:43', '2025-11-13 01:11:43');
INSERT INTO `fee_assignments` VALUES ('179', '15', '106', '2025-11-13', 'active', '2025-11-13 01:11:43', '2025-11-13 01:11:43');
INSERT INTO `fee_assignments` VALUES ('180', '15', '22', '2025-11-13', 'active', '2025-11-13 01:11:43', '2025-11-13 01:11:43');
INSERT INTO `fee_assignments` VALUES ('181', '15', '64', '2025-11-13', 'active', '2025-11-13 01:11:43', '2025-11-13 01:11:43');
INSERT INTO `fee_assignments` VALUES ('182', '15', '2', '2025-11-13', 'active', '2025-11-13 01:11:43', '2025-11-13 01:11:43');
INSERT INTO `fee_assignments` VALUES ('183', '16', '63', '2025-11-13', 'active', '2025-11-13 01:12:29', '2025-11-13 01:12:29');
INSERT INTO `fee_assignments` VALUES ('184', '16', '77', '2025-11-13', 'active', '2025-11-13 01:12:29', '2025-11-13 01:12:29');
INSERT INTO `fee_assignments` VALUES ('185', '16', '21', '2025-11-13', 'active', '2025-11-13 01:12:29', '2025-11-13 01:12:29');
INSERT INTO `fee_assignments` VALUES ('186', '16', '12', '2025-11-13', 'active', '2025-11-13 01:12:29', '2025-11-13 01:12:29');
INSERT INTO `fee_assignments` VALUES ('187', '16', '91', '2025-11-13', 'active', '2025-11-13 01:12:29', '2025-11-13 01:12:29');
INSERT INTO `fee_assignments` VALUES ('188', '16', '49', '2025-11-13', 'active', '2025-11-13 01:12:29', '2025-11-13 01:12:29');
INSERT INTO `fee_assignments` VALUES ('189', '16', '1', '2025-11-13', 'active', '2025-11-13 01:12:29', '2025-11-13 01:12:29');
INSERT INTO `fee_assignments` VALUES ('190', '16', '35', '2025-11-13', 'active', '2025-11-13 01:12:29', '2025-11-13 01:12:29');
INSERT INTO `fee_assignments` VALUES ('191', '16', '105', '2025-11-13', 'active', '2025-11-13 01:12:29', '2025-11-13 01:12:29');
INSERT INTO `fee_assignments` VALUES ('192', '16', '122', '2025-11-13', 'active', '2025-11-13 01:12:29', '2025-11-13 01:12:29');
INSERT INTO `fee_assignments` VALUES ('193', '16', '50', '2025-11-13', 'active', '2025-11-13 01:12:29', '2025-11-13 01:12:29');
INSERT INTO `fee_assignments` VALUES ('194', '16', '13', '2025-11-13', 'active', '2025-11-13 01:12:29', '2025-11-13 01:12:29');
INSERT INTO `fee_assignments` VALUES ('195', '16', '78', '2025-11-13', 'active', '2025-11-13 01:12:29', '2025-11-13 01:12:29');
INSERT INTO `fee_assignments` VALUES ('196', '16', '92', '2025-11-13', 'active', '2025-11-13 01:12:29', '2025-11-13 01:12:29');
INSERT INTO `fee_assignments` VALUES ('197', '16', '36', '2025-11-13', 'active', '2025-11-13 01:12:29', '2025-11-13 01:12:29');
INSERT INTO `fee_assignments` VALUES ('198', '16', '106', '2025-11-13', 'active', '2025-11-13 01:12:29', '2025-11-13 01:12:29');
INSERT INTO `fee_assignments` VALUES ('199', '16', '22', '2025-11-13', 'active', '2025-11-13 01:12:29', '2025-11-13 01:12:29');
INSERT INTO `fee_assignments` VALUES ('200', '16', '64', '2025-11-13', 'active', '2025-11-13 01:12:29', '2025-11-13 01:12:29');
INSERT INTO `fee_assignments` VALUES ('201', '16', '2', '2025-11-13', 'active', '2025-11-13 01:12:29', '2025-11-13 01:12:29');
INSERT INTO `fee_assignments` VALUES ('202', '17', '114', '2025-11-15', 'active', '2025-11-15 23:10:46', '2025-11-15 23:10:46');
INSERT INTO `fee_assignments` VALUES ('203', '17', '113', '2025-11-15', 'active', '2025-11-15 23:10:46', '2025-11-15 23:10:46');
INSERT INTO `fee_assignments` VALUES ('204', '17', '121', '2025-11-15', 'active', '2025-11-15 23:10:46', '2025-11-15 23:10:46');
INSERT INTO `fee_assignments` VALUES ('205', '17', '112', '2025-11-15', 'active', '2025-11-15 23:10:46', '2025-11-15 23:10:46');
INSERT INTO `fee_assignments` VALUES ('206', '18', '76', '2025-11-15', 'active', '2025-11-15 23:11:23', '2025-11-15 23:11:23');
INSERT INTO `fee_assignments` VALUES ('207', '18', '89', '2025-11-15', 'active', '2025-11-15 23:11:23', '2025-11-15 23:11:23');
INSERT INTO `fee_assignments` VALUES ('208', '18', '48', '2025-11-15', 'active', '2025-11-15 23:11:23', '2025-11-15 23:11:23');
INSERT INTO `fee_assignments` VALUES ('209', '18', '33', '2025-11-15', 'active', '2025-11-15 23:11:23', '2025-11-15 23:11:23');
INSERT INTO `fee_assignments` VALUES ('210', '18', '75', '2025-11-15', 'active', '2025-11-15 23:11:23', '2025-11-15 23:11:23');
INSERT INTO `fee_assignments` VALUES ('211', '18', '103', '2025-11-15', 'active', '2025-11-15 23:11:23', '2025-11-15 23:11:23');
INSERT INTO `fee_assignments` VALUES ('212', '18', '61', '2025-11-15', 'active', '2025-11-15 23:11:23', '2025-11-15 23:11:23');
INSERT INTO `fee_assignments` VALUES ('213', '18', '34', '2025-11-15', 'active', '2025-11-15 23:11:23', '2025-11-15 23:11:23');
INSERT INTO `fee_assignments` VALUES ('214', '18', '90', '2025-11-15', 'active', '2025-11-15 23:11:23', '2025-11-15 23:11:23');
INSERT INTO `fee_assignments` VALUES ('215', '18', '104', '2025-11-15', 'active', '2025-11-15 23:11:23', '2025-11-15 23:11:23');
INSERT INTO `fee_assignments` VALUES ('216', '18', '5', '2025-11-15', 'active', '2025-11-15 23:11:23', '2025-11-15 23:11:23');
INSERT INTO `fee_assignments` VALUES ('217', '18', '6', '2025-11-15', 'active', '2025-11-15 23:11:23', '2025-11-15 23:11:23');
INSERT INTO `fee_assignments` VALUES ('218', '18', '7', '2025-11-15', 'active', '2025-11-15 23:11:23', '2025-11-15 23:11:23');
INSERT INTO `fee_assignments` VALUES ('219', '18', '8', '2025-11-15', 'active', '2025-11-15 23:11:23', '2025-11-15 23:11:23');
INSERT INTO `fee_assignments` VALUES ('220', '18', '47', '2025-11-15', 'active', '2025-11-15 23:11:23', '2025-11-15 23:11:23');
INSERT INTO `fee_assignments` VALUES ('221', '18', '10', '2025-11-15', 'active', '2025-11-15 23:11:23', '2025-11-15 23:11:23');
INSERT INTO `fee_assignments` VALUES ('222', '18', '62', '2025-11-15', 'active', '2025-11-15 23:11:23', '2025-11-15 23:11:23');
INSERT INTO `fee_assignments` VALUES ('223', '18', '4', '2025-11-15', 'active', '2025-11-15 23:11:23', '2025-11-15 23:11:23');
INSERT INTO `fee_assignments` VALUES ('224', '18', '114', '2025-11-15', 'active', '2025-11-15 23:11:23', '2025-11-15 23:11:23');
INSERT INTO `fee_assignments` VALUES ('225', '18', '113', '2025-11-15', 'active', '2025-11-15 23:11:23', '2025-11-15 23:11:23');
INSERT INTO `fee_assignments` VALUES ('226', '18', '121', '2025-11-15', 'active', '2025-11-15 23:11:23', '2025-11-15 23:11:23');
INSERT INTO `fee_assignments` VALUES ('227', '18', '112', '2025-11-15', 'active', '2025-11-15 23:11:23', '2025-11-15 23:11:23');
INSERT INTO `fee_assignments` VALUES ('228', '19', '76', '2025-11-15', 'active', '2025-11-15 23:11:54', '2025-11-15 23:11:54');
INSERT INTO `fee_assignments` VALUES ('229', '19', '89', '2025-11-15', 'active', '2025-11-15 23:11:54', '2025-11-15 23:11:54');
INSERT INTO `fee_assignments` VALUES ('230', '19', '48', '2025-11-15', 'active', '2025-11-15 23:11:54', '2025-11-15 23:11:54');
INSERT INTO `fee_assignments` VALUES ('231', '19', '33', '2025-11-15', 'active', '2025-11-15 23:11:54', '2025-11-15 23:11:54');
INSERT INTO `fee_assignments` VALUES ('232', '19', '75', '2025-11-15', 'active', '2025-11-15 23:11:54', '2025-11-15 23:11:54');
INSERT INTO `fee_assignments` VALUES ('233', '19', '103', '2025-11-15', 'active', '2025-11-15 23:11:54', '2025-11-15 23:11:54');
INSERT INTO `fee_assignments` VALUES ('234', '19', '61', '2025-11-15', 'active', '2025-11-15 23:11:54', '2025-11-15 23:11:54');
INSERT INTO `fee_assignments` VALUES ('235', '19', '34', '2025-11-15', 'active', '2025-11-15 23:11:54', '2025-11-15 23:11:54');
INSERT INTO `fee_assignments` VALUES ('236', '19', '90', '2025-11-15', 'active', '2025-11-15 23:11:54', '2025-11-15 23:11:54');
INSERT INTO `fee_assignments` VALUES ('237', '19', '104', '2025-11-15', 'active', '2025-11-15 23:11:54', '2025-11-15 23:11:54');
INSERT INTO `fee_assignments` VALUES ('238', '19', '5', '2025-11-15', 'active', '2025-11-15 23:11:54', '2025-11-15 23:11:54');
INSERT INTO `fee_assignments` VALUES ('239', '19', '6', '2025-11-15', 'active', '2025-11-15 23:11:54', '2025-11-15 23:11:54');
INSERT INTO `fee_assignments` VALUES ('240', '19', '7', '2025-11-15', 'active', '2025-11-15 23:11:54', '2025-11-15 23:11:54');
INSERT INTO `fee_assignments` VALUES ('241', '19', '8', '2025-11-15', 'active', '2025-11-15 23:11:54', '2025-11-15 23:11:54');
INSERT INTO `fee_assignments` VALUES ('242', '19', '47', '2025-11-15', 'active', '2025-11-15 23:11:54', '2025-11-15 23:11:54');
INSERT INTO `fee_assignments` VALUES ('243', '19', '10', '2025-11-15', 'active', '2025-11-15 23:11:54', '2025-11-15 23:11:54');
INSERT INTO `fee_assignments` VALUES ('244', '19', '62', '2025-11-15', 'active', '2025-11-15 23:11:54', '2025-11-15 23:11:54');
INSERT INTO `fee_assignments` VALUES ('245', '19', '4', '2025-11-15', 'active', '2025-11-15 23:11:54', '2025-11-15 23:11:54');
INSERT INTO `fee_assignments` VALUES ('246', '19', '114', '2025-11-15', 'active', '2025-11-15 23:11:54', '2025-11-15 23:11:54');
INSERT INTO `fee_assignments` VALUES ('247', '19', '113', '2025-11-15', 'active', '2025-11-15 23:11:54', '2025-11-15 23:11:54');
INSERT INTO `fee_assignments` VALUES ('248', '19', '121', '2025-11-15', 'active', '2025-11-15 23:11:54', '2025-11-15 23:11:54');
INSERT INTO `fee_assignments` VALUES ('249', '19', '112', '2025-11-15', 'active', '2025-11-15 23:11:54', '2025-11-15 23:11:54');
INSERT INTO `fee_assignments` VALUES ('250', '20', '76', '2025-11-23', 'active', '2025-11-23 13:21:38', '2025-11-23 13:21:38');
INSERT INTO `fee_assignments` VALUES ('251', '20', '89', '2025-11-23', 'active', '2025-11-23 13:21:38', '2025-11-23 13:21:38');
INSERT INTO `fee_assignments` VALUES ('252', '20', '48', '2025-11-23', 'active', '2025-11-23 13:21:38', '2025-11-23 13:21:38');
INSERT INTO `fee_assignments` VALUES ('253', '20', '33', '2025-11-23', 'active', '2025-11-23 13:21:38', '2025-11-23 13:21:38');
INSERT INTO `fee_assignments` VALUES ('254', '20', '75', '2025-11-23', 'active', '2025-11-23 13:21:38', '2025-11-23 13:21:38');
INSERT INTO `fee_assignments` VALUES ('255', '20', '103', '2025-11-23', 'active', '2025-11-23 13:21:38', '2025-11-23 13:21:38');
INSERT INTO `fee_assignments` VALUES ('256', '20', '61', '2025-11-23', 'active', '2025-11-23 13:21:38', '2025-11-23 13:21:38');
INSERT INTO `fee_assignments` VALUES ('257', '20', '34', '2025-11-23', 'active', '2025-11-23 13:21:38', '2025-11-23 13:21:38');
INSERT INTO `fee_assignments` VALUES ('258', '20', '90', '2025-11-23', 'active', '2025-11-23 13:21:38', '2025-11-23 13:21:38');
INSERT INTO `fee_assignments` VALUES ('259', '20', '104', '2025-11-23', 'active', '2025-11-23 13:21:38', '2025-11-23 13:21:38');
INSERT INTO `fee_assignments` VALUES ('260', '20', '5', '2025-11-23', 'active', '2025-11-23 13:21:38', '2025-11-23 13:21:38');
INSERT INTO `fee_assignments` VALUES ('261', '20', '6', '2025-11-23', 'active', '2025-11-23 13:21:38', '2025-11-23 13:21:38');
INSERT INTO `fee_assignments` VALUES ('262', '20', '8', '2025-11-23', 'active', '2025-11-23 13:21:38', '2025-11-23 13:21:38');
INSERT INTO `fee_assignments` VALUES ('263', '20', '10', '2025-11-23', 'active', '2025-11-23 13:21:38', '2025-11-23 13:21:38');
INSERT INTO `fee_assignments` VALUES ('264', '20', '62', '2025-11-23', 'active', '2025-11-23 13:21:38', '2025-11-23 13:21:38');
INSERT INTO `fee_assignments` VALUES ('265', '20', '4', '2025-11-23', 'active', '2025-11-23 13:21:38', '2025-11-23 13:21:38');
INSERT INTO `fee_assignments` VALUES ('266', '20', '114', '2025-11-23', 'active', '2025-11-23 13:21:38', '2025-11-23 13:21:38');
INSERT INTO `fee_assignments` VALUES ('267', '20', '113', '2025-11-23', 'active', '2025-11-23 13:21:38', '2025-11-23 13:21:38');
INSERT INTO `fee_assignments` VALUES ('268', '20', '112', '2025-11-23', 'active', '2025-11-23 13:21:38', '2025-11-23 13:21:38');
INSERT INTO `fee_assignments` VALUES ('269', '21', '116', '2025-12-04', 'active', '2025-12-04 17:08:46', '2025-12-04 17:08:46');
INSERT INTO `fee_assignments` VALUES ('270', '21', '125', '2025-12-04', 'active', '2025-12-04 17:08:46', '2025-12-04 17:08:46');
INSERT INTO `fee_assignments` VALUES ('271', '21', '118', '2025-12-04', 'active', '2025-12-04 17:08:46', '2025-12-04 17:08:46');
INSERT INTO `fee_assignments` VALUES ('272', '21', '115', '2025-12-04', 'active', '2025-12-04 17:08:46', '2025-12-04 17:08:46');
INSERT INTO `fee_assignments` VALUES ('273', '21', '117', '2025-12-04', 'active', '2025-12-04 17:08:46', '2025-12-04 17:08:46');
INSERT INTO `fee_assignments` VALUES ('274', '21', '119', '2025-12-04', 'active', '2025-12-04 17:08:46', '2025-12-04 17:08:46');
INSERT INTO `fee_assignments` VALUES ('275', '21', '120', '2025-12-04', 'active', '2025-12-04 17:08:46', '2025-12-04 17:08:46');
INSERT INTO `fee_assignments` VALUES ('276', '21', '126', '2025-12-05', 'active', '2025-12-05 02:14:02', '2025-12-05 02:14:02');
INSERT INTO `fee_assignments` VALUES ('277', '20', '127', '2025-12-06', 'active', '2025-12-06 13:53:11', '2025-12-06 13:53:11');


-- Table structure for table `fees`
CREATE TABLE `fees` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `type` enum('tuition','transport','feeding','other') DEFAULT 'other',
  `class_id` int DEFAULT NULL,
  `original_classes` text,
  `academic_year_id` int DEFAULT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `class_id` (`class_id`),
  KEY `fk_fees_academic_year_id` (`academic_year_id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `fees`
INSERT INTO `fees` VALUES ('17', 'Tuition', '5000.00', 'tuition', NULL, '[\"14\"]', NULL, '', '2025-11-15 23:10:46', '2025-11-15 23:10:46');
INSERT INTO `fees` VALUES ('18', 'transpiort jss', '500.00', 'transport', NULL, '[\"13\",\"14\"]', NULL, '', '2025-11-15 23:11:23', '2025-11-15 23:11:23');
INSERT INTO `fees` VALUES ('19', 'Feeding Fee', '780.00', 'feeding', NULL, '[\"13\",\"14\"]', NULL, '', '2025-11-15 23:11:54', '2025-11-15 23:11:54');
INSERT INTO `fees` VALUES ('20', 'OPEIKUMAH', '300.00', 'transport', NULL, '[\"13\",\"14\"]', NULL, '', '2025-11-23 13:21:38', '2025-11-23 13:21:38');
INSERT INTO `fees` VALUES ('21', 'Tuition Fee', '3000.00', 'tuition', NULL, '[\"15\",\"16\"]', '2', '', '2025-12-04 17:08:46', '2025-12-04 17:08:46');


-- Table structure for table `grading_rules`
CREATE TABLE `grading_rules` (
  `id` int NOT NULL AUTO_INCREMENT,
  `scale_id` int NOT NULL,
  `min_score` decimal(5,2) NOT NULL,
  `max_score` decimal(5,2) NOT NULL,
  `grade` varchar(10) NOT NULL,
  `remark` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `scale_id` (`scale_id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `grading_rules`
INSERT INTO `grading_rules` VALUES ('4', '1', '70.00', '79.00', '2', 'Very Good', '2025-10-18 14:24:14', '2025-10-18 14:24:14');
INSERT INTO `grading_rules` VALUES ('3', '1', '80.00', '100.00', '1', 'Excellent', '2025-10-18 14:23:21', '2025-10-18 14:23:21');
INSERT INTO `grading_rules` VALUES ('5', '1', '60.00', '69.00', '3', 'good', '2025-10-18 14:25:49', '2025-10-18 14:25:49');
INSERT INTO `grading_rules` VALUES ('6', '1', '50.00', '59.00', '4', 'Fair', '2025-10-18 14:26:55', '2025-10-18 14:26:55');
INSERT INTO `grading_rules` VALUES ('7', '1', '40.00', '49.00', '5', 'Poor', '2025-10-18 14:28:07', '2025-10-18 14:28:07');
INSERT INTO `grading_rules` VALUES ('8', '1', '0.00', '39.00', '6', 'Fail', '2025-10-18 14:28:47', '2025-10-18 14:28:47');
INSERT INTO `grading_rules` VALUES ('9', '2', '70.00', '100.00', '1', 'Excellent', '2025-10-18 14:30:45', '2025-10-18 14:30:45');
INSERT INTO `grading_rules` VALUES ('10', '2', '50.00', '69.00', '2', 'Verry Good', '2025-10-18 14:31:26', '2025-10-18 14:31:26');
INSERT INTO `grading_rules` VALUES ('11', '2', '40.00', '49.00', '3', 'good', '2025-10-18 14:32:23', '2025-10-18 14:32:23');
INSERT INTO `grading_rules` VALUES ('12', '2', '0.00', '39.00', '4', 'Fair', '2025-10-18 14:33:16', '2025-10-18 14:33:16');


-- Table structure for table `grading_scales`
CREATE TABLE `grading_scales` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `grading_type` enum('numeric','letter') NOT NULL DEFAULT 'numeric',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `grading_scales`
INSERT INTO `grading_scales` VALUES ('1', 'JSH', 'numeric', '2025-10-18 14:14:44', '2025-10-18 14:14:44');
INSERT INTO `grading_scales` VALUES ('2', 'PRIMARY', 'numeric', '2025-10-18 14:29:49', '2025-10-18 14:29:49');


-- Table structure for table `notifications`
CREATE TABLE `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `message` text NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `related_id` int DEFAULT NULL,
  `related_type` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `notifications`
INSERT INTO `notifications` VALUES ('1', '2', 'Created student: sam sam', 'student', '1', '4', 'student', '2025-10-14 00:27:24', '2025-10-14 00:27:39');
INSERT INTO `notifications` VALUES ('2', '2', 'Created student: Afful Brown', 'student', '1', '5', 'student', '2025-10-14 00:50:09', '2025-10-14 00:50:32');
INSERT INTO `notifications` VALUES ('3', '2', 'Updated student: Afful Br', 'student', '1', '5', 'student', '2025-10-14 01:04:01', '2025-10-14 01:04:37');
INSERT INTO `notifications` VALUES ('4', '2', 'Updated student: Afful Br', 'student', '1', '5', 'student', '2025-10-14 01:04:15', '2025-10-14 01:04:37');
INSERT INTO `notifications` VALUES ('5', '2', 'Updated student: Afful Br', 'student', '1', '5', 'student', '2025-10-14 19:29:47', '2025-10-14 19:29:59');
INSERT INTO `notifications` VALUES ('6', '2', 'Created student: Akwesi Brown', 'student', '1', '6', 'student', '2025-10-14 19:30:45', '2025-10-14 19:39:27');
INSERT INTO `notifications` VALUES ('7', '2', 'Updated student: Akwesi Brown', 'student', '1', '6', 'student', '2025-10-14 19:38:53', '2025-10-14 19:39:27');
INSERT INTO `notifications` VALUES ('8', '2', 'Updated student: Akwesi Brown', 'student', '1', '6', 'student', '2025-10-14 19:39:16', '2025-10-14 19:39:27');
INSERT INTO `notifications` VALUES ('9', '2', 'Created student: IKe Brown Edem', 'student', '1', '7', 'student', '2025-10-14 19:41:44', '2025-10-14 20:11:09');
INSERT INTO `notifications` VALUES ('10', '2', 'Updated student: IKe Brown Edem', 'student', '1', '7', 'student', '2025-10-14 19:59:35', '2025-10-14 20:11:09');
INSERT INTO `notifications` VALUES ('11', '2', 'Updated student: IKe Brown Edem', 'student', '1', '7', 'student', '2025-10-14 20:05:27', '2025-10-14 20:11:09');
INSERT INTO `notifications` VALUES ('12', '2', 'Updated student: Akwesi Brown', 'student', '1', '6', 'student', '2025-10-14 20:11:05', '2025-10-14 20:11:09');
INSERT INTO `notifications` VALUES ('13', '2', 'Updated student: IKe Brown Edem', 'student', '1', '7', 'student', '2025-10-14 20:16:01', '2025-10-17 05:27:16');
INSERT INTO `notifications` VALUES ('14', '2', 'Created student: mina Brown Edem', 'student', '1', '8', 'student', '2025-10-14 20:16:56', '2025-10-17 05:27:16');
INSERT INTO `notifications` VALUES ('15', '2', 'Updated student: mina Brown Edem', 'student', '1', '8', 'student', '2025-10-14 20:18:04', '2025-10-17 05:27:16');
INSERT INTO `notifications` VALUES ('16', '2', 'Updated student: sam sam', 'student', '1', '4', 'student', '2025-10-17 05:26:47', '2025-10-17 05:27:16');
INSERT INTO `notifications` VALUES ('17', '2', 'Updated student: John Doe', 'student', '1', '1', 'student', '2025-10-17 08:29:20', '2025-10-17 10:24:34');
INSERT INTO `notifications` VALUES ('18', '2', 'Updated student: mina Brown Edem', 'student', '1', '8', 'student', '2025-10-17 09:58:16', '2025-10-17 10:24:34');
INSERT INTO `notifications` VALUES ('19', '2', 'Updated student: mina Brown Edem', 'student', '1', '8', 'student', '2025-10-17 10:22:17', '2025-10-17 10:24:34');
INSERT INTO `notifications` VALUES ('20', '2', 'Updated student: mina Brown Edem', 'student', '1', '8', 'student', '2025-10-17 10:22:51', '2025-10-17 10:24:34');
INSERT INTO `notifications` VALUES ('21', '2', 'Created student: Kwame Mintah', 'student', '1', '10', 'student', '2025-10-17 10:57:10', '2025-10-17 10:59:53');
INSERT INTO `notifications` VALUES ('22', '2', 'Created student: Effia Odo', 'student', '1', '11', 'student', '2025-10-19 23:32:51', '2025-10-19 23:33:13');
INSERT INTO `notifications` VALUES ('23', '2', 'Updated student: Effum Odoum', 'student', '1', '11', 'student', '2025-10-19 23:33:45', '2025-10-22 09:19:46');
INSERT INTO `notifications` VALUES ('24', '2', 'Created student: Micheal Woani', 'student', '1', '112', 'student', '2025-10-24 12:12:37', '2025-10-27 22:06:44');
INSERT INTO `notifications` VALUES ('25', '2', 'Created student: Kwame  addie', 'student', '1', '113', 'student', '2025-10-24 12:14:51', '2025-10-27 22:06:44');
INSERT INTO `notifications` VALUES ('26', '2', 'Created student: Effia addie', 'student', '1', '114', 'student', '2025-10-24 12:16:36', '2025-10-27 22:06:44');
INSERT INTO `notifications` VALUES ('27', '2', 'Created student: Alred Koti', 'student', '1', '115', 'student', '2025-10-29 22:36:47', '2025-10-30 11:23:36');
INSERT INTO `notifications` VALUES ('28', '2', 'Created student: Nyiram Bantam', 'student', '1', '116', 'student', '2025-10-29 22:38:04', '2025-10-30 11:23:36');
INSERT INTO `notifications` VALUES ('29', '2', 'Created student: Amu Waton', 'student', '1', '117', 'student', '2025-10-29 22:40:04', '2025-10-30 11:23:36');
INSERT INTO `notifications` VALUES ('30', '2', 'Created student: YAYA KK', 'student', '1', '118', 'student', '2025-10-29 22:59:19', '2025-10-30 11:23:36');
INSERT INTO `notifications` VALUES ('31', '2', 'Created student: josh amet', 'student', '1', '119', 'student', '2025-10-29 23:23:26', '2025-10-30 11:23:36');
INSERT INTO `notifications` VALUES ('32', '2', 'Created student: Efiram Amu', 'student', '1', '120', 'student', '2025-10-30 12:13:22', '2025-10-30 12:23:53');
INSERT INTO `notifications` VALUES ('33', '2', 'Created student: Offin Berima', 'student', '1', '121', 'student', '2025-11-04 13:25:14', '2025-11-04 13:43:57');
INSERT INTO `notifications` VALUES ('34', '2', 'Created student: Emefa Vida kk', 'student', '1', '122', 'student', '2025-11-13 00:59:16', '2025-11-19 14:56:20');
INSERT INTO `notifications` VALUES ('35', '2', 'Updated student: Emefa Vida kk', 'student', '1', '122', 'student', '2025-11-22 09:36:45', '2025-11-25 19:28:01');
INSERT INTO `notifications` VALUES ('36', '2', 'Updated student: Emefa Vida kk', 'student', '1', '122', 'student', '2025-11-22 09:37:51', '2025-11-25 19:28:01');
INSERT INTO `notifications` VALUES ('37', '2', 'Updated student: YAYA KK', 'student', '1', '118', 'student', '2025-11-22 09:42:09', '2025-11-25 19:28:01');
INSERT INTO `notifications` VALUES ('38', '2', 'Deleted student: Albert Eisntin', 'student', '1', '124', 'student', '2025-12-04 16:47:44', '2025-12-16 11:59:21');
INSERT INTO `notifications` VALUES ('39', '2', 'Created student: AKUBI ANNA', 'student', '1', '126', 'student', '2025-12-05 01:47:42', '2025-12-16 11:59:21');
INSERT INTO `notifications` VALUES ('40', '2', 'Updated student: AKUBI ANNA', 'student', '1', '126', 'student', '2025-12-05 01:48:29', '2025-12-16 11:59:21');
INSERT INTO `notifications` VALUES ('41', '2', 'Created student: Sammy  Dain', 'student', '1', '127', 'student', '2025-12-06 13:50:08', '2025-12-16 11:59:21');
INSERT INTO `notifications` VALUES ('42', '1', 'Test notification for dropdown implementation', 'test', '0', NULL, NULL, '2025-12-16 12:43:36', '2025-12-16 12:43:36');
INSERT INTO `notifications` VALUES ('43', '1', 'Second test notification for dropdown implementation', 'info', '0', NULL, NULL, '2025-12-16 12:43:55', '2025-12-16 12:43:55');
INSERT INTO `notifications` VALUES ('44', '1', 'Third test notification (already read)', 'warning', '1', NULL, NULL, '2025-12-16 12:44:09', '2025-12-16 12:44:09');


-- Table structure for table `payments`
CREATE TABLE `payments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `transaction_id` varchar(50) DEFAULT NULL,
  `student_id` int NOT NULL,
  `fee_id` int DEFAULT NULL,
  `academic_year_id` int DEFAULT NULL,
  `term` varchar(50) DEFAULT NULL,
  `cash_payer_name` varchar(100) DEFAULT NULL,
  `cash_payer_phone` varchar(20) DEFAULT NULL,
  `mobile_money_sender_name` varchar(100) DEFAULT NULL,
  `mobile_money_sender_number` varchar(20) DEFAULT NULL,
  `mobile_money_reference` varchar(100) DEFAULT NULL,
  `bank_transfer_sender_bank` varchar(100) DEFAULT NULL,
  `bank_transfer_invoice_number` varchar(100) DEFAULT NULL,
  `bank_transfer_details` text,
  `cheque_bank` varchar(100) DEFAULT NULL,
  `cheque_number` varchar(50) DEFAULT NULL,
  `cheque_details` text,
  `amount` decimal(10,2) NOT NULL,
  `method` enum('cash','cheque','bank_transfer','mobile_money','other') DEFAULT NULL,
  `date` date NOT NULL,
  `remarks` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `fk_payments_fee_id` (`fee_id`),
  KEY `fk_payments_academic_year_id` (`academic_year_id`),
  KEY `idx_transaction_id` (`transaction_id`)
) ENGINE=MyISAM AUTO_INCREMENT=135 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `payments`
INSERT INTO `payments` VALUES ('1', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '100.00', 'cash', '2025-10-13', '', '2025-10-13 21:19:07', '2025-10-13 21:19:07');
INSERT INTO `payments` VALUES ('2', NULL, '121', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '480.00', 'cash', '2025-11-07', 'Payment for Offin Berima by kwame 09876543', '2025-11-07 00:45:27', '2025-11-07 00:45:27');
INSERT INTO `payments` VALUES ('3', NULL, '121', '11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2140.00', 'cash', '2025-11-07', 'Payment for Offin Berima by kwame 09876543', '2025-11-07 01:15:19', '2025-11-07 01:15:19');
INSERT INTO `payments` VALUES ('4', NULL, '121', '11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '120.00', 'cash', '2025-11-07', 'Payment for Offin Berima', '2025-11-07 01:18:47', '2025-11-07 01:18:47');
INSERT INTO `payments` VALUES ('5', NULL, '121', '11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '500.00', 'cash', '2025-11-07', 'Payment for Offin Berima', '2025-11-07 01:27:45', '2025-11-07 01:27:45');
INSERT INTO `payments` VALUES ('6', NULL, '114', '11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '550.00', 'cash', '2025-11-07', 'Payment for Effia addie', '2025-11-07 01:32:15', '2025-11-07 01:32:15');
INSERT INTO `payments` VALUES ('7', NULL, '1', '1', '2', '1st Term', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '3000.00', 'cash', '2025-11-13', '', '2025-11-13 00:49:13', '2025-11-13 00:49:13');
INSERT INTO `payments` VALUES ('8', NULL, '1', '1', '2', '1st Term', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '700.00', 'cheque', '2025-11-13', '', '2025-11-13 00:57:51', '2025-11-13 00:57:51');
INSERT INTO `payments` VALUES ('9', NULL, '122', '15', '2', '1st Term', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1800.00', 'cash', '2025-11-13', '', '2025-11-13 01:14:13', '2025-11-13 01:14:13');
INSERT INTO `payments` VALUES ('10', NULL, '122', '16', '2', '1st Term', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '150.00', 'cash', '2025-11-13', '', '2025-11-13 01:14:13', '2025-11-13 01:14:13');
INSERT INTO `payments` VALUES ('11', NULL, '122', '15', '2', '1st Term', 'kwam', '0987876545', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '200.00', 'cash', '2025-11-13', '', '2025-11-13 02:31:16', '2025-11-13 02:31:16');
INSERT INTO `payments` VALUES ('12', NULL, '122', '16', '2', '1st Term', 'kwam', '0987876545', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '50.00', 'cash', '2025-11-13', '', '2025-11-13 02:31:16', '2025-11-13 02:31:16');
INSERT INTO `payments` VALUES ('13', NULL, '1', '15', '2', '1st Term', NULL, NULL, 'kwame', '0786564', 'John Doe (EPI-082848)', NULL, NULL, NULL, NULL, NULL, NULL, '1800.00', 'mobile_money', '2025-11-13', '', '2025-11-13 14:01:51', '2025-11-13 14:01:51');
INSERT INTO `payments` VALUES ('14', NULL, '1', '16', '2', '1st Term', NULL, NULL, 'kwame', '0786564', 'John Doe (EPI-082848)', NULL, NULL, NULL, NULL, NULL, NULL, '150.00', 'mobile_money', '2025-11-13', '', '2025-11-13 14:01:51', '2025-11-13 14:01:51');
INSERT INTO `payments` VALUES ('15', NULL, '114', '11', '2', '1st Term', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1000.00', 'cash', '2025-11-15', '', '2025-11-15 22:57:43', '2025-11-15 22:57:43');
INSERT INTO `payments` VALUES ('16', NULL, '114', '13', '2', '1st Term', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '200.00', 'cash', '2025-11-15', '', '2025-11-15 22:57:43', '2025-11-15 22:57:43');
INSERT INTO `payments` VALUES ('17', NULL, '114', '14', '2', '1st Term', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '600.00', 'cash', '2025-11-15', '', '2025-11-15 22:57:43', '2025-11-15 22:57:43');
INSERT INTO `payments` VALUES ('18', NULL, '114', '17', '2', '1st Term', 'grad', '0987656453', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2500.00', 'cash', '2025-11-15', 'thats ll', '2025-11-15 23:13:43', '2025-11-15 23:13:43');
INSERT INTO `payments` VALUES ('19', NULL, '114', '18', '2', '1st Term', 'grad', '0987656453', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '200.00', 'cash', '2025-11-15', 'thats ll', '2025-11-15 23:13:43', '2025-11-15 23:13:43');
INSERT INTO `payments` VALUES ('20', NULL, '114', '19', '2', '1st Term', 'grad', '0987656453', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '180.00', 'cash', '2025-11-15', 'thats ll', '2025-11-15 23:13:43', '2025-11-15 23:13:43');
INSERT INTO `payments` VALUES ('21', NULL, '113', '17', '2', '1st Term', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2800.00', 'cash', '2025-11-16', '', '2025-11-16 00:22:30', '2025-11-16 00:22:30');
INSERT INTO `payments` VALUES ('22', NULL, '113', '18', '2', '1st Term', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '300.00', 'cash', '2025-11-16', '', '2025-11-16 00:22:31', '2025-11-16 00:22:31');
INSERT INTO `payments` VALUES ('23', NULL, '113', '19', '2', '1st Term', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '250.00', 'cash', '2025-11-16', '', '2025-11-16 00:22:31', '2025-11-16 00:22:31');
INSERT INTO `payments` VALUES ('24', NULL, '114', '17', NULL, NULL, 'da', '06543765', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '500.00', 'cash', '2025-11-16', 'ghf', '2025-11-16 01:08:51', '2025-11-16 01:08:51');
INSERT INTO `payments` VALUES ('25', NULL, '114', '18', NULL, NULL, 'da', '06543765', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '100.00', 'cash', '2025-11-16', 'ghf', '2025-11-16 01:08:51', '2025-11-16 01:08:51');
INSERT INTO `payments` VALUES ('26', NULL, '114', '19', NULL, NULL, 'da', '06543765', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '180.00', 'cash', '2025-11-16', 'ghf', '2025-11-16 01:08:51', '2025-11-16 01:08:51');
INSERT INTO `payments` VALUES ('27', NULL, '114', '17', NULL, NULL, 'da', '06543765', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '500.00', 'cash', '2025-11-16', 'ghf', '2025-11-16 01:12:48', '2025-11-16 01:12:48');
INSERT INTO `payments` VALUES ('28', NULL, '114', '18', NULL, NULL, 'da', '06543765', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '100.00', 'cash', '2025-11-16', 'ghf', '2025-11-16 01:12:48', '2025-11-16 01:12:48');
INSERT INTO `payments` VALUES ('29', NULL, '114', '19', NULL, NULL, 'da', '06543765', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '180.00', 'cash', '2025-11-16', 'ghf', '2025-11-16 01:12:48', '2025-11-16 01:12:48');
INSERT INTO `payments` VALUES ('30', NULL, '114', '17', NULL, NULL, 'da', '06543765', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '500.00', 'cash', '2025-11-16', 'ghf', '2025-11-16 01:13:04', '2025-11-16 01:13:04');
INSERT INTO `payments` VALUES ('31', NULL, '114', '18', NULL, NULL, 'da', '06543765', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '100.00', 'cash', '2025-11-16', 'ghf', '2025-11-16 01:13:04', '2025-11-16 01:13:04');
INSERT INTO `payments` VALUES ('32', NULL, '114', '19', NULL, NULL, 'da', '06543765', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '180.00', 'cash', '2025-11-16', 'ghf', '2025-11-16 01:13:04', '2025-11-16 01:13:04');
INSERT INTO `payments` VALUES ('33', NULL, '114', '17', NULL, NULL, 'da', '06543765', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '500.00', 'cash', '2025-11-16', 'ghf', '2025-11-16 01:17:59', '2025-11-16 01:17:59');
INSERT INTO `payments` VALUES ('34', NULL, '114', '18', NULL, NULL, 'da', '06543765', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '100.00', 'cash', '2025-11-16', 'ghf', '2025-11-16 01:17:59', '2025-11-16 01:17:59');
INSERT INTO `payments` VALUES ('35', NULL, '114', '19', NULL, NULL, 'da', '06543765', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '180.00', 'cash', '2025-11-16', 'ghf', '2025-11-16 01:17:59', '2025-11-16 01:17:59');
INSERT INTO `payments` VALUES ('36', NULL, '113', '17', NULL, NULL, 'kk', '201540213120', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '200.00', 'cash', '2025-11-18', '', '2025-11-18 00:23:49', '2025-11-18 00:23:49');
INSERT INTO `payments` VALUES ('37', NULL, '113', '18', NULL, NULL, 'kk', '201540213120', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '100.00', 'cash', '2025-11-18', '', '2025-11-18 00:23:49', '2025-11-18 00:23:49');
INSERT INTO `payments` VALUES ('38', NULL, '113', '19', NULL, NULL, 'kk', '201540213120', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '130.00', 'cash', '2025-11-18', '', '2025-11-18 00:23:49', '2025-11-18 00:23:49');
INSERT INTO `payments` VALUES ('39', NULL, '113', '17', NULL, NULL, 'kk', '201540213120', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '200.00', 'cash', '2025-11-18', '', '2025-11-18 00:23:56', '2025-11-18 00:23:56');
INSERT INTO `payments` VALUES ('40', NULL, '113', '18', NULL, NULL, 'kk', '201540213120', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '100.00', 'cash', '2025-11-18', '', '2025-11-18 00:23:56', '2025-11-18 00:23:56');
INSERT INTO `payments` VALUES ('41', NULL, '113', '19', NULL, NULL, 'kk', '201540213120', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '130.00', 'cash', '2025-11-18', '', '2025-11-18 00:23:56', '2025-11-18 00:23:56');
INSERT INTO `payments` VALUES ('42', NULL, '113', '17', NULL, NULL, 'kk', '201540213120', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '200.00', 'cash', '2025-11-18', '', '2025-11-18 00:24:02', '2025-11-18 00:24:02');
INSERT INTO `payments` VALUES ('43', NULL, '113', '18', NULL, NULL, 'kk', '201540213120', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '100.00', 'cash', '2025-11-18', '', '2025-11-18 00:24:02', '2025-11-18 00:24:02');
INSERT INTO `payments` VALUES ('44', NULL, '113', '19', NULL, NULL, 'kk', '201540213120', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '130.00', 'cash', '2025-11-18', '', '2025-11-18 00:24:02', '2025-11-18 00:24:02');
INSERT INTO `payments` VALUES ('45', NULL, '113', '17', NULL, NULL, 'kk', '201540213120', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '200.00', 'cash', '2025-11-18', '', '2025-11-18 00:24:10', '2025-11-18 00:24:10');
INSERT INTO `payments` VALUES ('46', NULL, '113', '18', NULL, NULL, 'kk', '201540213120', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '100.00', 'cash', '2025-11-18', '', '2025-11-18 00:24:10', '2025-11-18 00:24:10');
INSERT INTO `payments` VALUES ('47', NULL, '113', '19', NULL, NULL, 'kk', '201540213120', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '130.00', 'cash', '2025-11-18', '', '2025-11-18 00:24:10', '2025-11-18 00:24:10');
INSERT INTO `payments` VALUES ('48', NULL, '113', '17', NULL, NULL, 'kk', '201540213120', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '200.00', 'cash', '2025-11-18', '', '2025-11-18 00:52:49', '2025-11-18 00:52:49');
INSERT INTO `payments` VALUES ('49', NULL, '113', '18', NULL, NULL, 'kk', '201540213120', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '100.00', 'cash', '2025-11-18', '', '2025-11-18 00:52:49', '2025-11-18 00:52:49');
INSERT INTO `payments` VALUES ('50', NULL, '113', '19', NULL, NULL, 'kk', '201540213120', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '130.00', 'cash', '2025-11-18', '', '2025-11-18 00:52:49', '2025-11-18 00:52:49');
INSERT INTO `payments` VALUES ('51', NULL, '76', '18', NULL, NULL, 'kj', '089786756', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '200.00', 'cash', '2025-11-18', 'vx', '2025-11-18 00:56:11', '2025-11-18 00:56:11');
INSERT INTO `payments` VALUES ('52', NULL, '76', '19', NULL, NULL, 'kj', '089786756', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '180.00', 'cash', '2025-11-18', 'vx', '2025-11-18 00:56:11', '2025-11-18 00:56:11');
INSERT INTO `payments` VALUES ('53', NULL, '112', '17', NULL, NULL, NULL, NULL, 'gfnx', '09786754534', 'Micheal Woani (EPI-121118)', NULL, NULL, NULL, NULL, NULL, NULL, '1000.00', 'mobile_money', '2025-11-18', '', '2025-11-18 00:58:12', '2025-11-18 00:58:12');
INSERT INTO `payments` VALUES ('54', NULL, '112', '18', NULL, NULL, NULL, NULL, 'gfnx', '09786754534', 'Micheal Woani (EPI-121118)', NULL, NULL, NULL, NULL, NULL, NULL, '200.00', 'mobile_money', '2025-11-18', '', '2025-11-18 00:58:12', '2025-11-18 00:58:12');
INSERT INTO `payments` VALUES ('55', NULL, '112', '19', NULL, NULL, NULL, NULL, 'gfnx', '09786754534', 'Micheal Woani (EPI-121118)', NULL, NULL, NULL, NULL, NULL, NULL, '280.00', 'mobile_money', '2025-11-18', '', '2025-11-18 00:58:12', '2025-11-18 00:58:12');
INSERT INTO `payments` VALUES ('56', NULL, '112', '17', NULL, NULL, NULL, NULL, 'dfbsdgs', '097876564534', 'Micheal Woani (EPI-121118)', NULL, NULL, NULL, NULL, NULL, NULL, '200.00', 'mobile_money', '2025-11-18', '', '2025-11-18 00:59:22', '2025-11-18 00:59:22');
INSERT INTO `payments` VALUES ('57', NULL, '112', '18', NULL, NULL, NULL, NULL, 'dfbsdgs', '097876564534', 'Micheal Woani (EPI-121118)', NULL, NULL, NULL, NULL, NULL, NULL, '150.00', 'mobile_money', '2025-11-18', '', '2025-11-18 00:59:22', '2025-11-18 00:59:22');
INSERT INTO `payments` VALUES ('58', NULL, '112', '19', NULL, NULL, NULL, NULL, 'dfbsdgs', '097876564534', 'Micheal Woani (EPI-121118)', NULL, NULL, NULL, NULL, NULL, NULL, '180.00', 'mobile_money', '2025-11-18', '', '2025-11-18 00:59:22', '2025-11-18 00:59:22');
INSERT INTO `payments` VALUES ('59', NULL, '112', '18', NULL, NULL, NULL, NULL, NULL, NULL, 'Micheal Woani (EPI-121118)', NULL, NULL, NULL, NULL, NULL, NULL, '150.00', 'mobile_money', '2025-11-18', '', '2025-11-18 01:13:55', '2025-11-18 01:13:55');
INSERT INTO `payments` VALUES ('60', NULL, '112', '19', NULL, NULL, NULL, NULL, NULL, NULL, 'Micheal Woani (EPI-121118)', NULL, NULL, NULL, NULL, NULL, NULL, '320.00', 'mobile_money', '2025-11-18', '', '2025-11-18 01:13:55', '2025-11-18 01:13:55');
INSERT INTO `payments` VALUES ('61', NULL, '112', '17', NULL, NULL, NULL, NULL, 'kjhgfdas', '09876543', 'Micheal Woani (EPI-121118)', NULL, NULL, NULL, NULL, NULL, NULL, '1800.00', 'mobile_money', '2025-11-18', '', '2025-11-18 01:39:48', '2025-11-18 01:39:48');
INSERT INTO `payments` VALUES ('62', NULL, '121', '17', NULL, NULL, NULL, NULL, 'dghjv', '098764532', 'Offin Berima (EPI-132442)', NULL, NULL, NULL, NULL, NULL, NULL, '1000.00', 'mobile_money', '2025-11-18', '', '2025-11-18 01:54:22', '2025-11-18 01:54:22');
INSERT INTO `payments` VALUES ('63', NULL, '121', '18', NULL, NULL, NULL, NULL, 'dghjv', '098764532', 'Offin Berima (EPI-132442)', NULL, NULL, NULL, NULL, NULL, NULL, '200.00', 'mobile_money', '2025-11-18', '', '2025-11-18 01:54:22', '2025-11-18 01:54:22');
INSERT INTO `payments` VALUES ('64', NULL, '121', '19', NULL, NULL, NULL, NULL, 'dghjv', '098764532', 'Offin Berima (EPI-132442)', NULL, NULL, NULL, NULL, NULL, NULL, '780.00', 'mobile_money', '2025-11-18', '', '2025-11-18 01:54:22', '2025-11-18 01:54:22');
INSERT INTO `payments` VALUES ('65', NULL, '121', '18', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '200.00', 'cash', '2025-11-18', '', '2025-11-18 03:04:30', '2025-11-18 03:04:30');
INSERT INTO `payments` VALUES ('66', NULL, '112', '17', NULL, NULL, 'fgds', '0976543', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '150.00', 'cash', '2025-11-19', '', '2025-11-19 00:42:59', '2025-11-19 00:42:59');
INSERT INTO `payments` VALUES ('67', NULL, '76', '19', NULL, NULL, 'cashier', '0985764534', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '80.00', 'cash', '2025-11-19', 'good', '2025-11-19 01:06:55', '2025-11-19 01:06:55');
INSERT INTO `payments` VALUES ('68', NULL, '76', '18', NULL, NULL, 'cashier', '0985764534', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '200.00', 'cash', '2025-11-19', 'good', '2025-11-19 01:06:55', '2025-11-19 01:06:55');
INSERT INTO `payments` VALUES ('69', NULL, '76', '19', NULL, NULL, 'sam', 'safgh', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '120.00', 'cash', '2025-11-19', 'good', '2025-11-19 01:18:09', '2025-11-19 01:18:09');
INSERT INTO `payments` VALUES ('70', NULL, '76', '18', NULL, NULL, 'sam', 'safgh', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '50.00', 'cash', '2025-11-19', 'good', '2025-11-19 01:18:09', '2025-11-19 01:18:09');
INSERT INTO `payments` VALUES ('71', NULL, '89', '19', NULL, NULL, 'sadfbnn', '0765432', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '280.00', 'cash', '2025-11-19', 'good', '2025-11-19 01:33:24', '2025-11-19 01:33:24');
INSERT INTO `payments` VALUES ('72', NULL, '89', '18', NULL, NULL, 'sadfbnn', '0765432', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '250.00', 'cash', '2025-11-19', 'good', '2025-11-19 01:33:24', '2025-11-19 01:33:24');
INSERT INTO `payments` VALUES ('73', NULL, '62', '19', NULL, NULL, 'fd', '08765432', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '180.00', 'cash', '2025-11-19', 'gf', '2025-11-19 02:08:38', '2025-11-19 02:08:38');
INSERT INTO `payments` VALUES ('74', NULL, '62', '18', NULL, NULL, 'fd', '08765432', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '250.00', 'cash', '2025-11-19', 'gf', '2025-11-19 02:08:38', '2025-11-19 02:08:38');
INSERT INTO `payments` VALUES ('75', NULL, '34', '19', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '280.00', 'cash', '2025-11-19', '', '2025-11-19 02:32:13', '2025-11-19 02:32:13');
INSERT INTO `payments` VALUES ('76', NULL, '34', '18', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '200.00', 'cash', '2025-11-19', '', '2025-11-19 02:32:13', '2025-11-19 02:32:13');
INSERT INTO `payments` VALUES ('77', NULL, '48', '19', NULL, NULL, 'sam', '097865432', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '180.00', 'cash', '2025-11-19', 'fd', '2025-11-19 03:13:10', '2025-11-19 03:13:10');
INSERT INTO `payments` VALUES ('78', NULL, '48', '18', NULL, NULL, 'sam', '097865432', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '200.00', 'cash', '2025-11-19', 'fd', '2025-11-19 03:13:10', '2025-11-19 03:13:10');
INSERT INTO `payments` VALUES ('79', NULL, '48', '19', NULL, NULL, 'sam', '087654', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '100.00', 'cash', '2025-11-19', 'kj', '2025-11-19 03:40:58', '2025-11-19 03:40:58');
INSERT INTO `payments` VALUES ('80', NULL, '48', '18', NULL, NULL, 'sam', '087654', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '150.00', 'cash', '2025-11-19', 'kj', '2025-11-19 03:40:58', '2025-11-19 03:40:58');
INSERT INTO `payments` VALUES ('81', NULL, '48', '19', NULL, NULL, 'sas09875643', '-0987654', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '100.00', 'cash', '2025-11-19', 'good', '2025-11-19 03:49:40', '2025-11-19 03:49:40');
INSERT INTO `payments` VALUES ('82', NULL, '48', '18', NULL, NULL, 'sas09875643', '-0987654', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '50.00', 'cash', '2025-11-19', 'good', '2025-11-19 03:49:40', '2025-11-19 03:49:40');
INSERT INTO `payments` VALUES ('83', NULL, '5', '19', NULL, NULL, 'sam', '00987865433', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '180.00', 'cash', '2025-11-19', 'ssa', '2025-11-19 04:10:19', '2025-11-19 04:10:19');
INSERT INTO `payments` VALUES ('84', NULL, '5', '18', NULL, NULL, 'sam', '00987865433', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '200.00', 'cash', '2025-11-19', 'ssa', '2025-11-19 04:10:19', '2025-11-19 04:10:19');
INSERT INTO `payments` VALUES ('85', NULL, '5', '19', NULL, NULL, 'sam', '09876544', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '100.00', 'cash', '2025-11-19', 'cs', '2025-11-19 04:11:39', '2025-11-19 04:11:39');
INSERT INTO `payments` VALUES ('86', NULL, '5', '18', NULL, NULL, 'sam', '09876544', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '50.00', 'cash', '2025-11-19', 'cs', '2025-11-19 04:11:39', '2025-11-19 04:11:39');
INSERT INTO `payments` VALUES ('87', NULL, '34', '19', NULL, NULL, 'sam', '09867564532', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '100.00', 'cash', '2025-11-19', 'sa', '2025-11-19 04:25:14', '2025-11-19 04:25:14');
INSERT INTO `payments` VALUES ('88', NULL, '34', '18', NULL, NULL, 'sam', '09867564532', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '150.00', 'cash', '2025-11-19', 'sa', '2025-11-19 04:25:14', '2025-11-19 04:25:14');
INSERT INTO `payments` VALUES ('89', NULL, '103', '19', NULL, NULL, 'SAM', '90765643', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '280.00', 'cash', '2025-11-19', '', '2025-11-19 04:36:44', '2025-11-19 04:36:44');
INSERT INTO `payments` VALUES ('90', NULL, '103', '18', NULL, NULL, 'SAM', '90765643', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '200.00', 'cash', '2025-11-19', '', '2025-11-19 04:36:44', '2025-11-19 04:36:44');
INSERT INTO `payments` VALUES ('91', NULL, '61', '19', NULL, NULL, '09786', 'kjhj', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '280.00', 'cash', '2025-11-19', '', '2025-11-19 04:50:46', '2025-11-19 04:50:46');
INSERT INTO `payments` VALUES ('92', NULL, '61', '18', NULL, NULL, '09786', 'kjhj', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '100.00', 'cash', '2025-11-19', '', '2025-11-19 04:50:46', '2025-11-19 04:50:46');
INSERT INTO `payments` VALUES ('93', NULL, '6', '19', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '280.00', 'cash', '2025-11-19', '', '2025-11-19 05:00:59', '2025-11-19 05:00:59');
INSERT INTO `payments` VALUES ('94', NULL, '6', '18', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '100.00', 'cash', '2025-11-19', '', '2025-11-19 05:00:59', '2025-11-19 05:00:59');
INSERT INTO `payments` VALUES ('95', NULL, '48', '19', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '100.00', 'cash', '2025-11-19', '', '2025-11-19 05:08:24', '2025-11-19 05:08:24');
INSERT INTO `payments` VALUES ('96', NULL, '48', '18', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '100.00', 'cash', '2025-11-19', '', '2025-11-19 05:08:24', '2025-11-19 05:08:24');
INSERT INTO `payments` VALUES ('97', NULL, '10', '19', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '180.00', 'cash', '2025-11-19', '', '2025-11-19 05:15:47', '2025-11-19 05:15:47');
INSERT INTO `payments` VALUES ('98', NULL, '10', '18', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '150.00', 'cash', '2025-11-19', '', '2025-11-19 05:15:47', '2025-11-19 05:15:47');
INSERT INTO `payments` VALUES ('99', NULL, '34', '19', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '100.00', 'cash', '2025-11-19', '', '2025-11-19 05:26:14', '2025-11-19 05:26:14');
INSERT INTO `payments` VALUES ('100', NULL, '34', '18', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '50.00', 'cash', '2025-11-19', '', '2025-11-19 05:26:14', '2025-11-19 05:26:14');
INSERT INTO `payments` VALUES ('101', NULL, '34', '19', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '100.00', 'cash', '2025-11-19', '', '2025-11-19 05:52:36', '2025-11-19 05:52:36');
INSERT INTO `payments` VALUES ('102', 'txn_691d9dd8dfc104.50308751', '48', '19', '2', '1st Term', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '150.00', 'cash', '2025-11-19', '', '2025-11-19 10:37:12', '2025-11-19 10:37:12');
INSERT INTO `payments` VALUES ('103', NULL, '76', '19', NULL, NULL, 'sddfh', '0987765', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '200.00', 'cash', '2025-11-19', 'kljhg', '2025-11-19 14:40:06', '2025-11-19 14:40:06');
INSERT INTO `payments` VALUES ('104', NULL, '76', '18', NULL, NULL, 'sddfh', '0987765', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '50.00', 'cash', '2025-11-19', 'kljhg', '2025-11-19 14:40:06', '2025-11-19 14:40:06');
INSERT INTO `payments` VALUES ('105', NULL, '33', '19', NULL, NULL, NULL, NULL, 'sam', '0987654', 'Afia Amponsah (STU0022)', NULL, NULL, NULL, NULL, NULL, NULL, '180.00', 'mobile_money', '2025-11-19', 'good', '2025-11-19 15:47:48', '2025-11-19 15:47:48');
INSERT INTO `payments` VALUES ('106', NULL, '33', '18', NULL, NULL, NULL, NULL, 'sam', '0987654', 'Afia Amponsah (STU0022)', NULL, NULL, NULL, NULL, NULL, NULL, '300.00', 'mobile_money', '2025-11-19', 'good', '2025-11-19 15:47:48', '2025-11-19 15:47:48');
INSERT INTO `payments` VALUES ('107', NULL, '7', '19', NULL, NULL, 'sam', '0987653423', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '780.00', 'cash', '2025-11-19', 'saa', '2025-11-19 19:27:31', '2025-11-19 19:27:31');
INSERT INTO `payments` VALUES ('108', NULL, '7', '18', NULL, NULL, 'sam', '0987653423', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '250.00', 'cash', '2025-11-19', 'saa', '2025-11-19 19:27:31', '2025-11-19 19:27:31');
INSERT INTO `payments` VALUES ('109', NULL, '8', '19', NULL, NULL, 'sam', '09876543', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '280.00', 'cash', '2025-11-19', 'sav', '2025-11-19 20:01:08', '2025-11-19 20:01:08');
INSERT INTO `payments` VALUES ('110', NULL, '8', '18', NULL, NULL, 'sam', '09876543', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '150.00', 'cash', '2025-11-19', 'sav', '2025-11-19 20:01:08', '2025-11-19 20:01:08');
INSERT INTO `payments` VALUES ('111', NULL, '112', '20', NULL, NULL, 'sam', '0987534523', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '200.00', 'cash', '2025-11-23', 'fully paid', '2025-11-23 13:27:13', '2025-11-23 13:27:13');
INSERT INTO `payments` VALUES ('112', NULL, '112', '17', NULL, NULL, 'sam', '0987534523', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1850.00', 'cash', '2025-11-23', 'fully paid', '2025-11-23 13:27:13', '2025-11-23 13:27:13');
INSERT INTO `payments` VALUES ('113', NULL, '125', '21', '2', NULL, 'samn', '564646456', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2000.00', 'cash', '2025-12-05', 'good', '2025-12-05 00:23:46', '2025-12-05 00:23:46');
INSERT INTO `payments` VALUES ('114', NULL, '76', '20', '2', NULL, 'sam', '0976453423', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '100.00', 'cash', '2025-12-05', 'samm', '2025-12-05 00:37:01', '2025-12-05 00:37:01');
INSERT INTO `payments` VALUES ('115', NULL, '76', '19', '2', NULL, 'sam', '0976453423', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '100.00', 'cash', '2025-12-05', 'samm', '2025-12-05 00:37:01', '2025-12-05 00:37:01');
INSERT INTO `payments` VALUES ('116', NULL, '89', '20', '2', '1st Term', 'sabn', '007687657', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '300.00', 'cash', '2025-12-05', 'ki', '2025-12-05 00:58:09', '2025-12-05 00:58:09');
INSERT INTO `payments` VALUES ('117', NULL, '89', '18', '2', '1st Term', 'sabn', '007687657', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '250.00', 'cash', '2025-12-05', 'ki', '2025-12-05 00:58:09', '2025-12-05 00:58:09');
INSERT INTO `payments` VALUES ('118', NULL, '89', '19', '2', '1st Term', 'sabn', '007687657', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '200.00', 'cash', '2025-12-05', 'ki', '2025-12-05 00:58:09', '2025-12-05 00:58:09');
INSERT INTO `payments` VALUES ('119', NULL, '76', '20', '2', '1st Term', 'as', '0775453432', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '200.00', 'cash', '2025-12-05', 'klk', '2025-12-05 01:08:59', '2025-12-05 01:08:59');
INSERT INTO `payments` VALUES ('120', NULL, '76', '19', '2', '1st Term', 'as', '0775453432', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '100.00', 'cash', '2025-12-05', 'klk', '2025-12-05 01:08:59', '2025-12-05 01:08:59');
INSERT INTO `payments` VALUES ('121', NULL, '125', '21', '2', '1st Term', 'sam', '06754634', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1000.00', 'cash', '2025-12-05', 'sa', '2025-12-05 01:44:07', '2025-12-05 01:44:07');
INSERT INTO `payments` VALUES ('122', NULL, '126', '21', '2', '1st Term', 'Sammjh', '097856453434', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '900.00', 'cash', '2025-12-05', 'kl', '2025-12-05 02:17:11', '2025-12-05 02:17:11');
INSERT INTO `payments` VALUES ('123', NULL, '89', '19', '2', '1st Term', 'pokpoipo', '4442452', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '200.00', 'cash', '2025-12-05', '', '2025-12-05 03:00:58', '2025-12-05 03:00:58');
INSERT INTO `payments` VALUES ('124', NULL, '33', '20', '2', '1st Term', 'asf', '078657564', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '100.00', 'cash', '2025-12-06', 'kjlk', '2025-12-06 10:46:16', '2025-12-06 10:46:16');
INSERT INTO `payments` VALUES ('125', NULL, '33', '18', '2', '1st Term', 'asf', '078657564', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '100.00', 'cash', '2025-12-06', 'kjlk', '2025-12-06 10:46:16', '2025-12-06 10:46:16');
INSERT INTO `payments` VALUES ('126', NULL, '33', '19', '2', '1st Term', 'asf', '078657564', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '200.00', 'cash', '2025-12-06', 'kjlk', '2025-12-06 10:46:16', '2025-12-06 10:46:16');
INSERT INTO `payments` VALUES ('127', NULL, '48', '20', '2', '1st Term', 'da', '07785645', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '100.00', 'cash', '2025-12-06', 'gfcf', '2025-12-06 10:52:47', '2025-12-06 10:52:47');
INSERT INTO `payments` VALUES ('128', NULL, '48', '19', '2', '1st Term', 'da', '07785645', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '50.00', 'cash', '2025-12-06', 'gfcf', '2025-12-06 10:52:47', '2025-12-06 10:52:47');
INSERT INTO `payments` VALUES ('129', NULL, '48', '20', '2', '1st Term', 'sa', '978867567', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '100.00', 'cash', '2025-12-06', 'gngbn', '2025-12-06 11:27:18', '2025-12-06 11:27:18');
INSERT INTO `payments` VALUES ('130', NULL, '48', '19', '2', '1st Term', 'sa', '978867567', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '100.00', 'cash', '2025-12-06', 'gngbn', '2025-12-06 11:27:18', '2025-12-06 11:27:18');
INSERT INTO `payments` VALUES ('131', NULL, '33', '20', '2', '1st Term', 'sasg', '087867', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '100.00', 'cash', '2025-12-06', 'jgh', '2025-12-06 11:28:07', '2025-12-06 11:28:07');
INSERT INTO `payments` VALUES ('132', NULL, '33', '18', '2', '1st Term', 'sasg', '087867', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '100.00', 'cash', '2025-12-06', 'jgh', '2025-12-06 11:28:07', '2025-12-06 11:28:07');
INSERT INTO `payments` VALUES ('133', NULL, '33', '19', '2', '1st Term', 'sasg', '087867', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '100.00', 'cash', '2025-12-06', 'jgh', '2025-12-06 11:28:07', '2025-12-06 11:28:07');
INSERT INTO `payments` VALUES ('134', NULL, '127', '20', '2', '1st Term', 'sdaj', '0868765', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '100.00', 'cash', '2025-12-06', 'dsf', '2025-12-06 14:54:44', '2025-12-06 14:54:44');


-- Table structure for table `permissions`
CREATE TABLE `permissions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `permissions`
INSERT INTO `permissions` VALUES ('1', 'view_students', 'View students', '2025-10-12 02:58:57');
INSERT INTO `permissions` VALUES ('2', 'create_students', 'Create students', '2025-10-12 02:58:57');
INSERT INTO `permissions` VALUES ('3', 'edit_students', 'Edit students', '2025-10-12 02:58:57');
INSERT INTO `permissions` VALUES ('4', 'delete_students', 'Delete students', '2025-10-12 02:58:57');
INSERT INTO `permissions` VALUES ('5', 'view_staff', 'View staff', '2025-10-12 02:58:57');
INSERT INTO `permissions` VALUES ('6', 'create_staff', 'Create staff', '2025-10-12 02:58:57');
INSERT INTO `permissions` VALUES ('7', 'edit_staff', 'Edit staff', '2025-10-12 02:58:57');
INSERT INTO `permissions` VALUES ('8', 'delete_staff', 'Delete staff', '2025-10-12 02:58:57');
INSERT INTO `permissions` VALUES ('9', 'view_academic_years', 'View academic years', '2025-10-12 02:58:57');
INSERT INTO `permissions` VALUES ('10', 'create_academic_years', 'Create academic years', '2025-10-12 02:58:57');
INSERT INTO `permissions` VALUES ('11', 'edit_academic_years', 'Edit academic years', '2025-10-12 02:58:57');
INSERT INTO `permissions` VALUES ('12', 'delete_academic_years', 'Delete academic years', '2025-10-12 02:58:57');
INSERT INTO `permissions` VALUES ('13', 'view_timetables', 'View timetables', '2025-10-12 02:58:57');
INSERT INTO `permissions` VALUES ('14', 'create_timetables', 'Create timetables', '2025-10-12 02:58:57');
INSERT INTO `permissions` VALUES ('15', 'edit_timetables', 'Edit timetables', '2025-10-12 02:58:57');
INSERT INTO `permissions` VALUES ('16', 'delete_timetables', 'Delete timetables', '2025-10-12 02:58:57');
INSERT INTO `permissions` VALUES ('17', 'view_reports', 'View reports', '2025-10-12 02:58:57');
INSERT INTO `permissions` VALUES ('18', 'export_reports', 'Export reports', '2025-10-12 02:58:57');
INSERT INTO `permissions` VALUES ('19', 'view_audit_logs', 'View audit logs', '2025-10-12 02:58:57');
INSERT INTO `permissions` VALUES ('20', 'view_backups', 'View backups', '2025-10-12 02:58:57');
INSERT INTO `permissions` VALUES ('21', 'create_backups', 'Create backups', '2025-10-12 02:58:57');
INSERT INTO `permissions` VALUES ('22', 'download_backups', 'Download backups', '2025-10-12 02:58:57');
INSERT INTO `permissions` VALUES ('23', 'delete_backups', 'Delete backups', '2025-10-12 02:58:57');


-- Table structure for table `receipts`
CREATE TABLE `receipts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `payment_id` int NOT NULL,
  `receipt_data` json NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `payment_id` (`payment_id`)
) ENGINE=MyISAM AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `receipts`
INSERT INTO `receipts` VALUES ('1', '62', '{\"payment\": {\"id\": 62, \"date\": \"2025-11-18\", \"term\": null, \"amount\": \"1000.00\", \"fee_id\": 17, \"method\": \"mobile_money\", \"remarks\": \"\", \"fee_name\": \"Tuition\", \"created_at\": \"2025-11-18 01:54:22\", \"student_id\": 121, \"updated_at\": \"2025-11-18 01:54:22\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"cash_payer_name\": null, \"academic_year_id\": null, \"cash_payer_phone\": null, \"bank_transfer_details\": null, \"mobile_money_reference\": \"Offin Berima (EPI-132442)\", \"mobile_money_sender_name\": \"dghjv\", \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": \"098764532\", \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 121, \"dob\": \"2011-07-04\", \"gender\": \"male\", \"address\": \"234/9kk accra\", \"user_id\": null, \"class_id\": 14, \"last_name\": \"Berima\", \"created_at\": \"2025-11-04 13:25:14\", \"first_name\": \"Offin\", \"updated_at\": \"2025-11-04 13:25:14\", \"admission_no\": \"EPI-132442\", \"medical_info\": \"\", \"guardian_name\": \"Mrs. Nyarko Berima\", \"admission_date\": \"2025-11-04\", \"guardian_phone\": \"03523223\", \"profile_picture\": null, \"academic_year_id\": 2, \"student_category\": \"regular_day\", \"student_category_details\": \"\"}, \"generated_at\": \"2025-11-18 01:54:22\", \"method_label\": \"Mobile Money\", \"payment_date\": \"Nov 18, 2025\", \"method_details\": {\"reference\": \"Offin Berima (EPI-132442)\", \"sender_name\": \"dghjv\", \"sender_number\": \"098764532\"}}', '2025-11-18 01:54:22', '2025-11-18 01:54:22');
INSERT INTO `receipts` VALUES ('2', '63', '{\"payment\": {\"id\": 63, \"date\": \"2025-11-18\", \"term\": null, \"amount\": \"200.00\", \"fee_id\": 18, \"method\": \"mobile_money\", \"remarks\": \"\", \"fee_name\": \"transpiort jss\", \"created_at\": \"2025-11-18 01:54:22\", \"student_id\": 121, \"updated_at\": \"2025-11-18 01:54:22\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"cash_payer_name\": null, \"academic_year_id\": null, \"cash_payer_phone\": null, \"bank_transfer_details\": null, \"mobile_money_reference\": \"Offin Berima (EPI-132442)\", \"mobile_money_sender_name\": \"dghjv\", \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": \"098764532\", \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 121, \"dob\": \"2011-07-04\", \"gender\": \"male\", \"address\": \"234/9kk accra\", \"user_id\": null, \"class_id\": 14, \"last_name\": \"Berima\", \"created_at\": \"2025-11-04 13:25:14\", \"first_name\": \"Offin\", \"updated_at\": \"2025-11-04 13:25:14\", \"admission_no\": \"EPI-132442\", \"medical_info\": \"\", \"guardian_name\": \"Mrs. Nyarko Berima\", \"admission_date\": \"2025-11-04\", \"guardian_phone\": \"03523223\", \"profile_picture\": null, \"academic_year_id\": 2, \"student_category\": \"regular_day\", \"student_category_details\": \"\"}, \"generated_at\": \"2025-11-18 01:54:22\", \"method_label\": \"Mobile Money\", \"payment_date\": \"Nov 18, 2025\", \"method_details\": {\"reference\": \"Offin Berima (EPI-132442)\", \"sender_name\": \"dghjv\", \"sender_number\": \"098764532\"}}', '2025-11-18 01:54:22', '2025-11-18 01:54:22');
INSERT INTO `receipts` VALUES ('3', '64', '{\"payment\": {\"id\": 64, \"date\": \"2025-11-18\", \"term\": null, \"amount\": \"780.00\", \"fee_id\": 19, \"method\": \"mobile_money\", \"remarks\": \"\", \"fee_name\": \"Feeding Fee\", \"created_at\": \"2025-11-18 01:54:22\", \"student_id\": 121, \"updated_at\": \"2025-11-18 01:54:22\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"cash_payer_name\": null, \"academic_year_id\": null, \"cash_payer_phone\": null, \"bank_transfer_details\": null, \"mobile_money_reference\": \"Offin Berima (EPI-132442)\", \"mobile_money_sender_name\": \"dghjv\", \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": \"098764532\", \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 121, \"dob\": \"2011-07-04\", \"gender\": \"male\", \"address\": \"234/9kk accra\", \"user_id\": null, \"class_id\": 14, \"last_name\": \"Berima\", \"created_at\": \"2025-11-04 13:25:14\", \"first_name\": \"Offin\", \"updated_at\": \"2025-11-04 13:25:14\", \"admission_no\": \"EPI-132442\", \"medical_info\": \"\", \"guardian_name\": \"Mrs. Nyarko Berima\", \"admission_date\": \"2025-11-04\", \"guardian_phone\": \"03523223\", \"profile_picture\": null, \"academic_year_id\": 2, \"student_category\": \"regular_day\", \"student_category_details\": \"\"}, \"generated_at\": \"2025-11-18 01:54:22\", \"method_label\": \"Mobile Money\", \"payment_date\": \"Nov 18, 2025\", \"method_details\": {\"reference\": \"Offin Berima (EPI-132442)\", \"sender_name\": \"dghjv\", \"sender_number\": \"098764532\"}}', '2025-11-18 01:54:22', '2025-11-18 01:54:22');
INSERT INTO `receipts` VALUES ('4', '65', '{\"payment\": {\"id\": 65, \"date\": \"2025-11-18\", \"term\": null, \"amount\": \"200.00\", \"fee_id\": 18, \"method\": \"cash\", \"remarks\": \"\", \"created_at\": \"2025-11-18 03:04:30\", \"student_id\": 121, \"updated_at\": \"2025-11-18 03:04:30\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": null, \"academic_year_id\": null, \"cash_payer_phone\": null, \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 121, \"dob\": \"2011-07-04\", \"gender\": \"male\", \"address\": \"234/9kk accra\", \"user_id\": null, \"class_id\": 14, \"last_name\": \"Berima\", \"created_at\": \"2025-11-04 13:25:14\", \"first_name\": \"Offin\", \"updated_at\": \"2025-11-04 13:25:14\", \"admission_no\": \"EPI-132442\", \"medical_info\": \"\", \"guardian_name\": \"Mrs. Nyarko Berima\", \"admission_date\": \"2025-11-04\", \"guardian_phone\": \"03523223\", \"profile_picture\": null, \"academic_year_id\": 2, \"student_category\": \"regular_day\", \"student_category_details\": \"\"}, \"generated_at\": \"2025-11-18 03:04:30\", \"method_label\": \"Cash\", \"payment_date\": \"Nov 18, 2025\", \"method_details\": []}', '2025-11-18 03:04:30', '2025-11-18 03:04:30');
INSERT INTO `receipts` VALUES ('5', '66', '{\"payment\": {\"id\": 66, \"date\": \"2025-11-19\", \"term\": null, \"amount\": \"150.00\", \"fee_id\": 17, \"method\": \"cash\", \"remarks\": \"\", \"created_at\": \"2025-11-19 00:42:59\", \"student_id\": 112, \"updated_at\": \"2025-11-19 00:42:59\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"fgds\", \"academic_year_id\": null, \"cash_payer_phone\": \"0976543\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 112, \"dob\": \"2007-11-23\", \"gender\": \"\", \"address\": \"234/9kk accra\", \"user_id\": null, \"class_id\": 14, \"last_name\": \"Woani\", \"created_at\": \"2025-10-24 12:12:37\", \"first_name\": \"Micheal\", \"updated_at\": \"2025-10-24 12:12:37\", \"admission_no\": \"EPI-121118\", \"medical_info\": \"\", \"guardian_name\": \"Mr. Appiah\", \"admission_date\": \"2025-10-24\", \"guardian_phone\": \"+233501927075\", \"profile_picture\": null, \"academic_year_id\": 2, \"student_category\": \"regular_boarding\", \"student_category_details\": \"\"}, \"generated_at\": \"2025-11-19 00:42:59\", \"method_label\": \"Cash\", \"payment_date\": \"Nov 19, 2025\", \"method_details\": {\"payer_name\": \"fgds\", \"payer_phone\": \"0976543\"}}', '2025-11-19 00:42:59', '2025-11-19 00:42:59');
INSERT INTO `receipts` VALUES ('6', '67', '{\"payment\": {\"id\": 67, \"date\": \"2025-11-19\", \"term\": null, \"amount\": \"80.00\", \"fee_id\": 19, \"method\": \"cash\", \"remarks\": \"good\", \"created_at\": \"2025-11-19 01:06:55\", \"student_id\": 76, \"updated_at\": \"2025-11-19 01:06:55\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"cashier\", \"academic_year_id\": null, \"cash_payer_phone\": \"0985764534\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 76, \"dob\": \"0000-00-00\", \"gender\": \"male\", \"address\": \"456 Oak Ave\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Adjei\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"Kofi\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0065\", \"medical_info\": null, \"guardian_name\": \"Mr. Boateng\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233588406574\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_day\", \"student_category_details\": null}, \"generated_at\": \"2025-11-19 01:06:55\", \"method_label\": \"Cash\", \"payment_date\": \"Nov 19, 2025\", \"method_details\": {\"payer_name\": \"cashier\", \"payer_phone\": \"0985764534\"}}', '2025-11-19 01:06:55', '2025-11-19 01:06:55');
INSERT INTO `receipts` VALUES ('7', '68', '{\"payment\": {\"id\": 68, \"date\": \"2025-11-19\", \"term\": null, \"amount\": \"200.00\", \"fee_id\": 18, \"method\": \"cash\", \"remarks\": \"good\", \"created_at\": \"2025-11-19 01:06:55\", \"student_id\": 76, \"updated_at\": \"2025-11-19 01:06:55\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"cashier\", \"academic_year_id\": null, \"cash_payer_phone\": \"0985764534\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 76, \"dob\": \"0000-00-00\", \"gender\": \"male\", \"address\": \"456 Oak Ave\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Adjei\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"Kofi\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0065\", \"medical_info\": null, \"guardian_name\": \"Mr. Boateng\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233588406574\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_day\", \"student_category_details\": null}, \"generated_at\": \"2025-11-19 01:06:55\", \"method_label\": \"Cash\", \"payment_date\": \"Nov 19, 2025\", \"method_details\": {\"payer_name\": \"cashier\", \"payer_phone\": \"0985764534\"}}', '2025-11-19 01:06:55', '2025-11-19 01:06:55');
INSERT INTO `receipts` VALUES ('8', '69', '{\"payment\": {\"id\": 69, \"date\": \"2025-11-19\", \"term\": null, \"amount\": \"120.00\", \"fee_id\": 19, \"method\": \"cash\", \"remarks\": \"good\", \"created_at\": \"2025-11-19 01:18:09\", \"student_id\": 76, \"updated_at\": \"2025-11-19 01:18:09\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"sam\", \"academic_year_id\": null, \"cash_payer_phone\": \"safgh\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 76, \"dob\": \"0000-00-00\", \"gender\": \"male\", \"address\": \"456 Oak Ave\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Adjei\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"Kofi\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0065\", \"medical_info\": null, \"guardian_name\": \"Mr. Boateng\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233588406574\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_day\", \"student_category_details\": null}, \"generated_at\": \"2025-11-19 01:18:09\", \"method_label\": \"Cash\", \"payment_date\": \"Nov 19, 2025\", \"method_details\": {\"payer_name\": \"sam\", \"payer_phone\": \"safgh\"}}', '2025-11-19 01:18:09', '2025-11-19 01:18:09');
INSERT INTO `receipts` VALUES ('9', '70', '{\"payment\": {\"id\": 70, \"date\": \"2025-11-19\", \"term\": null, \"amount\": \"50.00\", \"fee_id\": 18, \"method\": \"cash\", \"remarks\": \"good\", \"created_at\": \"2025-11-19 01:18:09\", \"student_id\": 76, \"updated_at\": \"2025-11-19 01:18:09\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"sam\", \"academic_year_id\": null, \"cash_payer_phone\": \"safgh\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 76, \"dob\": \"0000-00-00\", \"gender\": \"male\", \"address\": \"456 Oak Ave\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Adjei\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"Kofi\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0065\", \"medical_info\": null, \"guardian_name\": \"Mr. Boateng\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233588406574\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_day\", \"student_category_details\": null}, \"generated_at\": \"2025-11-19 01:18:09\", \"method_label\": \"Cash\", \"payment_date\": \"Nov 19, 2025\", \"method_details\": {\"payer_name\": \"sam\", \"payer_phone\": \"safgh\"}}', '2025-11-19 01:18:09', '2025-11-19 01:18:09');
INSERT INTO `receipts` VALUES ('10', '71', '{\"payment\": {\"id\": 71, \"date\": \"2025-11-19\", \"term\": null, \"amount\": \"280.00\", \"fee_id\": 19, \"method\": \"cash\", \"remarks\": \"good\", \"created_at\": \"2025-11-19 01:33:24\", \"student_id\": 89, \"updated_at\": \"2025-11-19 01:33:24\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"sadfbnn\", \"academic_year_id\": null, \"cash_payer_phone\": \"0765432\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 89, \"dob\": \"0000-00-00\", \"gender\": \"male\", \"address\": \"123 Main St\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Adjei\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"Yaw\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0078\", \"medical_info\": null, \"guardian_name\": \"Mr. Boadu\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233352593474\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_boarding\", \"student_category_details\": null}, \"generated_at\": \"2025-11-19 01:33:24\", \"method_label\": \"Cash\", \"payment_date\": \"Nov 19, 2025\", \"method_details\": {\"payer_name\": \"sadfbnn\", \"payer_phone\": \"0765432\"}}', '2025-11-19 01:33:24', '2025-11-19 01:33:24');
INSERT INTO `receipts` VALUES ('11', '72', '{\"payment\": {\"id\": 72, \"date\": \"2025-11-19\", \"term\": null, \"amount\": \"250.00\", \"fee_id\": 18, \"method\": \"cash\", \"remarks\": \"good\", \"created_at\": \"2025-11-19 01:33:24\", \"student_id\": 89, \"updated_at\": \"2025-11-19 01:33:24\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"sadfbnn\", \"academic_year_id\": null, \"cash_payer_phone\": \"0765432\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 89, \"dob\": \"0000-00-00\", \"gender\": \"male\", \"address\": \"123 Main St\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Adjei\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"Yaw\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0078\", \"medical_info\": null, \"guardian_name\": \"Mr. Boadu\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233352593474\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_boarding\", \"student_category_details\": null}, \"generated_at\": \"2025-11-19 01:33:24\", \"method_label\": \"Cash\", \"payment_date\": \"Nov 19, 2025\", \"method_details\": {\"payer_name\": \"sadfbnn\", \"payer_phone\": \"0765432\"}}', '2025-11-19 01:33:24', '2025-11-19 01:33:24');
INSERT INTO `receipts` VALUES ('12', '73', '{\"payment\": {\"id\": 73, \"date\": \"2025-11-19\", \"term\": null, \"amount\": \"180.00\", \"fee_id\": 19, \"method\": \"cash\", \"remarks\": \"gf\", \"created_at\": \"2025-11-19 02:08:38\", \"student_id\": 62, \"updated_at\": \"2025-11-19 02:08:38\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"fd\", \"academic_year_id\": null, \"cash_payer_phone\": \"08765432\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 62, \"dob\": \"0000-00-00\", \"gender\": \"female\", \"address\": \"456 Oak Ave\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Owusu\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"Abena\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0051\", \"medical_info\": null, \"guardian_name\": \"Mrs. Asante\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233350006730\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_day\", \"student_category_details\": null}, \"generated_at\": \"2025-11-19 02:08:38\", \"method_label\": \"Cash\", \"payment_date\": \"Nov 19, 2025\", \"method_details\": {\"payer_name\": \"fd\", \"payer_phone\": \"08765432\"}}', '2025-11-19 02:08:38', '2025-11-19 02:08:38');
INSERT INTO `receipts` VALUES ('13', '74', '{\"payment\": {\"id\": 74, \"date\": \"2025-11-19\", \"term\": null, \"amount\": \"250.00\", \"fee_id\": 18, \"method\": \"cash\", \"remarks\": \"gf\", \"created_at\": \"2025-11-19 02:08:38\", \"student_id\": 62, \"updated_at\": \"2025-11-19 02:08:38\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"fd\", \"academic_year_id\": null, \"cash_payer_phone\": \"08765432\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 62, \"dob\": \"0000-00-00\", \"gender\": \"female\", \"address\": \"456 Oak Ave\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Owusu\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"Abena\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0051\", \"medical_info\": null, \"guardian_name\": \"Mrs. Asante\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233350006730\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_day\", \"student_category_details\": null}, \"generated_at\": \"2025-11-19 02:08:38\", \"method_label\": \"Cash\", \"payment_date\": \"Nov 19, 2025\", \"method_details\": {\"payer_name\": \"fd\", \"payer_phone\": \"08765432\"}}', '2025-11-19 02:08:38', '2025-11-19 02:08:38');
INSERT INTO `receipts` VALUES ('14', '75', '{\"payment\": {\"id\": 75, \"date\": \"2025-11-19\", \"term\": null, \"amount\": \"280.00\", \"fee_id\": 19, \"method\": \"cash\", \"remarks\": \"\", \"created_at\": \"2025-11-19 02:32:13\", \"student_id\": 34, \"updated_at\": \"2025-11-19 02:32:13\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": null, \"academic_year_id\": null, \"cash_payer_phone\": null, \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 34, \"dob\": \"0000-00-00\", \"gender\": \"male\", \"address\": \"123 Main St\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Boadu\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"John\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0023\", \"medical_info\": null, \"guardian_name\": \"Mrs. Amponsah\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233384220631\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_day\", \"student_category_details\": null}, \"generated_at\": \"2025-11-19 02:32:13\", \"method_label\": \"Cash\", \"payment_date\": \"Nov 19, 2025\", \"method_details\": []}', '2025-11-19 02:32:13', '2025-11-19 02:32:13');
INSERT INTO `receipts` VALUES ('15', '76', '{\"payment\": {\"id\": 76, \"date\": \"2025-11-19\", \"term\": null, \"amount\": \"200.00\", \"fee_id\": 18, \"method\": \"cash\", \"remarks\": \"\", \"created_at\": \"2025-11-19 02:32:13\", \"student_id\": 34, \"updated_at\": \"2025-11-19 02:32:13\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": null, \"academic_year_id\": null, \"cash_payer_phone\": null, \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 34, \"dob\": \"0000-00-00\", \"gender\": \"male\", \"address\": \"123 Main St\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Boadu\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"John\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0023\", \"medical_info\": null, \"guardian_name\": \"Mrs. Amponsah\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233384220631\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_day\", \"student_category_details\": null}, \"generated_at\": \"2025-11-19 02:32:13\", \"method_label\": \"Cash\", \"payment_date\": \"Nov 19, 2025\", \"method_details\": []}', '2025-11-19 02:32:13', '2025-11-19 02:32:13');
INSERT INTO `receipts` VALUES ('16', '77', '{\"payment\": {\"id\": 77, \"date\": \"2025-11-19\", \"term\": null, \"amount\": \"180.00\", \"fee_id\": 19, \"method\": \"cash\", \"remarks\": \"fd\", \"created_at\": \"2025-11-19 03:13:10\", \"student_id\": 48, \"updated_at\": \"2025-11-19 03:13:10\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"sam\", \"academic_year_id\": null, \"cash_payer_phone\": \"097865432\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 48, \"dob\": \"0000-00-00\", \"gender\": \"female\", \"address\": \"456 Oak Ave\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Amponsah\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"Adwoa\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0037\", \"medical_info\": null, \"guardian_name\": \"Mr. Mensah\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233320679366\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_day\", \"student_category_details\": null}, \"generated_at\": \"2025-11-19 03:13:10\", \"method_label\": \"Cash\", \"payment_date\": \"Nov 19, 2025\", \"method_details\": {\"payer_name\": \"sam\", \"payer_phone\": \"097865432\"}}', '2025-11-19 03:13:10', '2025-11-19 03:13:10');
INSERT INTO `receipts` VALUES ('17', '78', '{\"payment\": {\"id\": 78, \"date\": \"2025-11-19\", \"term\": null, \"amount\": \"200.00\", \"fee_id\": 18, \"method\": \"cash\", \"remarks\": \"fd\", \"created_at\": \"2025-11-19 03:13:10\", \"student_id\": 48, \"updated_at\": \"2025-11-19 03:13:10\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"sam\", \"academic_year_id\": null, \"cash_payer_phone\": \"097865432\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 48, \"dob\": \"0000-00-00\", \"gender\": \"female\", \"address\": \"456 Oak Ave\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Amponsah\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"Adwoa\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0037\", \"medical_info\": null, \"guardian_name\": \"Mr. Mensah\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233320679366\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_day\", \"student_category_details\": null}, \"generated_at\": \"2025-11-19 03:13:10\", \"method_label\": \"Cash\", \"payment_date\": \"Nov 19, 2025\", \"method_details\": {\"payer_name\": \"sam\", \"payer_phone\": \"097865432\"}}', '2025-11-19 03:13:10', '2025-11-19 03:13:10');
INSERT INTO `receipts` VALUES ('18', '79', '{\"payment\": {\"id\": 79, \"date\": \"2025-11-19\", \"term\": null, \"amount\": \"100.00\", \"fee_id\": 19, \"method\": \"cash\", \"remarks\": \"kj\", \"created_at\": \"2025-11-19 03:40:58\", \"student_id\": 48, \"updated_at\": \"2025-11-19 03:40:58\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"sam\", \"academic_year_id\": null, \"cash_payer_phone\": \"087654\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 48, \"dob\": \"0000-00-00\", \"gender\": \"female\", \"address\": \"456 Oak Ave\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Amponsah\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"Adwoa\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0037\", \"medical_info\": null, \"guardian_name\": \"Mr. Mensah\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233320679366\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_day\", \"student_category_details\": null}, \"generated_at\": \"2025-11-19 03:40:58\", \"method_label\": \"Cash\", \"payment_date\": \"Nov 19, 2025\", \"method_details\": {\"payer_name\": \"sam\", \"payer_phone\": \"087654\"}}', '2025-11-19 03:40:58', '2025-11-19 03:40:58');
INSERT INTO `receipts` VALUES ('19', '80', '{\"payment\": {\"id\": 80, \"date\": \"2025-11-19\", \"term\": null, \"amount\": \"150.00\", \"fee_id\": 18, \"method\": \"cash\", \"remarks\": \"kj\", \"created_at\": \"2025-11-19 03:40:58\", \"student_id\": 48, \"updated_at\": \"2025-11-19 03:40:58\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"sam\", \"academic_year_id\": null, \"cash_payer_phone\": \"087654\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 48, \"dob\": \"0000-00-00\", \"gender\": \"female\", \"address\": \"456 Oak Ave\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Amponsah\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"Adwoa\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0037\", \"medical_info\": null, \"guardian_name\": \"Mr. Mensah\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233320679366\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_day\", \"student_category_details\": null}, \"generated_at\": \"2025-11-19 03:40:58\", \"method_label\": \"Cash\", \"payment_date\": \"Nov 19, 2025\", \"method_details\": {\"payer_name\": \"sam\", \"payer_phone\": \"087654\"}}', '2025-11-19 03:40:58', '2025-11-19 03:40:58');
INSERT INTO `receipts` VALUES ('20', '81', '{\"payment\": {\"id\": 81, \"date\": \"2025-11-19\", \"term\": null, \"amount\": \"100.00\", \"fee_id\": 19, \"method\": \"cash\", \"remarks\": \"good\", \"created_at\": \"2025-11-19 03:49:40\", \"student_id\": 48, \"updated_at\": \"2025-11-19 03:49:40\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"sas09875643\", \"academic_year_id\": null, \"cash_payer_phone\": \"-0987654\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 48, \"dob\": \"0000-00-00\", \"gender\": \"female\", \"address\": \"456 Oak Ave\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Amponsah\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"Adwoa\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0037\", \"medical_info\": null, \"guardian_name\": \"Mr. Mensah\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233320679366\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_day\", \"student_category_details\": null}, \"generated_at\": \"2025-11-19 03:49:40\", \"method_label\": \"Cash\", \"payment_date\": \"Nov 19, 2025\", \"method_details\": {\"payer_name\": \"sas09875643\", \"payer_phone\": \"-0987654\"}}', '2025-11-19 03:49:40', '2025-11-19 03:49:40');
INSERT INTO `receipts` VALUES ('21', '82', '{\"payment\": {\"id\": 82, \"date\": \"2025-11-19\", \"term\": null, \"amount\": \"50.00\", \"fee_id\": 18, \"method\": \"cash\", \"remarks\": \"good\", \"created_at\": \"2025-11-19 03:49:40\", \"student_id\": 48, \"updated_at\": \"2025-11-19 03:49:40\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"sas09875643\", \"academic_year_id\": null, \"cash_payer_phone\": \"-0987654\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 48, \"dob\": \"0000-00-00\", \"gender\": \"female\", \"address\": \"456 Oak Ave\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Amponsah\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"Adwoa\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0037\", \"medical_info\": null, \"guardian_name\": \"Mr. Mensah\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233320679366\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_day\", \"student_category_details\": null}, \"generated_at\": \"2025-11-19 03:49:40\", \"method_label\": \"Cash\", \"payment_date\": \"Nov 19, 2025\", \"method_details\": {\"payer_name\": \"sas09875643\", \"payer_phone\": \"-0987654\"}}', '2025-11-19 03:49:40', '2025-11-19 03:49:40');
INSERT INTO `receipts` VALUES ('22', '83', '{\"payment\": {\"id\": 83, \"date\": \"2025-11-19\", \"term\": null, \"amount\": \"180.00\", \"fee_id\": 19, \"method\": \"cash\", \"remarks\": \"ssa\", \"created_at\": \"2025-11-19 04:10:19\", \"student_id\": 5, \"updated_at\": \"2025-11-19 04:10:19\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"sam\", \"academic_year_id\": null, \"cash_payer_phone\": \"00987865433\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 5, \"dob\": \"2010-06-15\", \"gender\": \"male\", \"address\": \"opeikumah\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Br\", \"created_at\": \"2025-10-14 00:50:09\", \"first_name\": \"Afful\", \"updated_at\": \"2025-10-14 19:29:47\", \"admission_no\": \"151023\", \"medical_info\": null, \"guardian_name\": \"YaYa\", \"admission_date\": null, \"guardian_phone\": \"5657575\", \"profile_picture\": \"student_1760470187_68eea4abd370a.jpeg\", \"academic_year_id\": null, \"student_category\": \"regular_day\", \"student_category_details\": null}, \"generated_at\": \"2025-11-19 04:10:19\", \"method_label\": \"Cash\", \"payment_date\": \"Nov 19, 2025\", \"method_details\": {\"payer_name\": \"sam\", \"payer_phone\": \"00987865433\"}}', '2025-11-19 04:10:19', '2025-11-19 04:10:19');
INSERT INTO `receipts` VALUES ('23', '84', '{\"payment\": {\"id\": 84, \"date\": \"2025-11-19\", \"term\": null, \"amount\": \"200.00\", \"fee_id\": 18, \"method\": \"cash\", \"remarks\": \"ssa\", \"created_at\": \"2025-11-19 04:10:19\", \"student_id\": 5, \"updated_at\": \"2025-11-19 04:10:19\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"sam\", \"academic_year_id\": null, \"cash_payer_phone\": \"00987865433\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 5, \"dob\": \"2010-06-15\", \"gender\": \"male\", \"address\": \"opeikumah\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Br\", \"created_at\": \"2025-10-14 00:50:09\", \"first_name\": \"Afful\", \"updated_at\": \"2025-10-14 19:29:47\", \"admission_no\": \"151023\", \"medical_info\": null, \"guardian_name\": \"YaYa\", \"admission_date\": null, \"guardian_phone\": \"5657575\", \"profile_picture\": \"student_1760470187_68eea4abd370a.jpeg\", \"academic_year_id\": null, \"student_category\": \"regular_day\", \"student_category_details\": null}, \"generated_at\": \"2025-11-19 04:10:19\", \"method_label\": \"Cash\", \"payment_date\": \"Nov 19, 2025\", \"method_details\": {\"payer_name\": \"sam\", \"payer_phone\": \"00987865433\"}}', '2025-11-19 04:10:19', '2025-11-19 04:10:19');
INSERT INTO `receipts` VALUES ('24', '85', '{\"payment\": {\"id\": 85, \"date\": \"2025-11-19\", \"term\": null, \"amount\": \"100.00\", \"fee_id\": 19, \"method\": \"cash\", \"remarks\": \"cs\", \"created_at\": \"2025-11-19 04:11:39\", \"student_id\": 5, \"updated_at\": \"2025-11-19 04:11:39\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"sam\", \"academic_year_id\": null, \"cash_payer_phone\": \"09876544\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 5, \"dob\": \"2010-06-15\", \"gender\": \"male\", \"address\": \"opeikumah\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Br\", \"created_at\": \"2025-10-14 00:50:09\", \"first_name\": \"Afful\", \"updated_at\": \"2025-10-14 19:29:47\", \"admission_no\": \"151023\", \"medical_info\": null, \"guardian_name\": \"YaYa\", \"admission_date\": null, \"guardian_phone\": \"5657575\", \"profile_picture\": \"student_1760470187_68eea4abd370a.jpeg\", \"academic_year_id\": null, \"student_category\": \"regular_day\", \"student_category_details\": null}, \"generated_at\": \"2025-11-19 04:11:39\", \"method_label\": \"Cash\", \"payment_date\": \"Nov 19, 2025\", \"method_details\": {\"payer_name\": \"sam\", \"payer_phone\": \"09876544\"}}', '2025-11-19 04:11:39', '2025-11-19 04:11:39');
INSERT INTO `receipts` VALUES ('25', '86', '{\"payment\": {\"id\": 86, \"date\": \"2025-11-19\", \"term\": null, \"amount\": \"50.00\", \"fee_id\": 18, \"method\": \"cash\", \"remarks\": \"cs\", \"created_at\": \"2025-11-19 04:11:39\", \"student_id\": 5, \"updated_at\": \"2025-11-19 04:11:39\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"sam\", \"academic_year_id\": null, \"cash_payer_phone\": \"09876544\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 5, \"dob\": \"2010-06-15\", \"gender\": \"male\", \"address\": \"opeikumah\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Br\", \"created_at\": \"2025-10-14 00:50:09\", \"first_name\": \"Afful\", \"updated_at\": \"2025-10-14 19:29:47\", \"admission_no\": \"151023\", \"medical_info\": null, \"guardian_name\": \"YaYa\", \"admission_date\": null, \"guardian_phone\": \"5657575\", \"profile_picture\": \"student_1760470187_68eea4abd370a.jpeg\", \"academic_year_id\": null, \"student_category\": \"regular_day\", \"student_category_details\": null}, \"generated_at\": \"2025-11-19 04:11:39\", \"method_label\": \"Cash\", \"payment_date\": \"Nov 19, 2025\", \"method_details\": {\"payer_name\": \"sam\", \"payer_phone\": \"09876544\"}}', '2025-11-19 04:11:39', '2025-11-19 04:11:39');
INSERT INTO `receipts` VALUES ('26', '87', '{\"payment\": {\"id\": 87, \"date\": \"2025-11-19\", \"term\": null, \"amount\": \"100.00\", \"fee_id\": 19, \"method\": \"cash\", \"remarks\": \"sa\", \"created_at\": \"2025-11-19 04:25:14\", \"student_id\": 34, \"updated_at\": \"2025-11-19 04:25:14\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"sam\", \"academic_year_id\": null, \"cash_payer_phone\": \"09867564532\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 34, \"dob\": \"0000-00-00\", \"gender\": \"male\", \"address\": \"123 Main St\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Boadu\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"John\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0023\", \"medical_info\": null, \"guardian_name\": \"Mrs. Amponsah\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233384220631\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_day\", \"student_category_details\": null}, \"generated_at\": \"2025-11-19 04:25:14\", \"method_label\": \"Cash\", \"payment_date\": \"Nov 19, 2025\", \"method_details\": {\"payer_name\": \"sam\", \"payer_phone\": \"09867564532\"}}', '2025-11-19 04:25:14', '2025-11-19 04:25:14');
INSERT INTO `receipts` VALUES ('27', '88', '{\"payment\": {\"id\": 88, \"date\": \"2025-11-19\", \"term\": null, \"amount\": \"150.00\", \"fee_id\": 18, \"method\": \"cash\", \"remarks\": \"sa\", \"created_at\": \"2025-11-19 04:25:14\", \"student_id\": 34, \"updated_at\": \"2025-11-19 04:25:14\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"sam\", \"academic_year_id\": null, \"cash_payer_phone\": \"09867564532\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 34, \"dob\": \"0000-00-00\", \"gender\": \"male\", \"address\": \"123 Main St\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Boadu\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"John\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0023\", \"medical_info\": null, \"guardian_name\": \"Mrs. Amponsah\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233384220631\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_day\", \"student_category_details\": null}, \"generated_at\": \"2025-11-19 04:25:14\", \"method_label\": \"Cash\", \"payment_date\": \"Nov 19, 2025\", \"method_details\": {\"payer_name\": \"sam\", \"payer_phone\": \"09867564532\"}}', '2025-11-19 04:25:14', '2025-11-19 04:25:14');
INSERT INTO `receipts` VALUES ('28', '89', '{\"payment\": {\"id\": 89, \"date\": \"2025-11-19\", \"term\": null, \"amount\": \"280.00\", \"fee_id\": 19, \"method\": \"cash\", \"remarks\": \"\", \"created_at\": \"2025-11-19 04:36:44\", \"student_id\": 103, \"updated_at\": \"2025-11-19 04:36:44\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"SAM\", \"academic_year_id\": null, \"cash_payer_phone\": \"90765643\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 103, \"dob\": \"0000-00-00\", \"gender\": \"female\", \"address\": \"123 Main St\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Asante\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"Evelyn\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0092\", \"medical_info\": null, \"guardian_name\": \"Mr. Boadu\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233534017576\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_boarding\", \"student_category_details\": null}, \"generated_at\": \"2025-11-19 04:36:44\", \"method_label\": \"Cash\", \"payment_date\": \"Nov 19, 2025\", \"method_details\": {\"payer_name\": \"SAM\", \"payer_phone\": \"90765643\"}}', '2025-11-19 04:36:44', '2025-11-19 04:36:44');
INSERT INTO `receipts` VALUES ('29', '90', '{\"payment\": {\"id\": 90, \"date\": \"2025-11-19\", \"term\": null, \"amount\": \"200.00\", \"fee_id\": 18, \"method\": \"cash\", \"remarks\": \"\", \"created_at\": \"2025-11-19 04:36:44\", \"student_id\": 103, \"updated_at\": \"2025-11-19 04:36:44\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"SAM\", \"academic_year_id\": null, \"cash_payer_phone\": \"90765643\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 103, \"dob\": \"0000-00-00\", \"gender\": \"female\", \"address\": \"123 Main St\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Asante\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"Evelyn\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0092\", \"medical_info\": null, \"guardian_name\": \"Mr. Boadu\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233534017576\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_boarding\", \"student_category_details\": null}, \"generated_at\": \"2025-11-19 04:36:44\", \"method_label\": \"Cash\", \"payment_date\": \"Nov 19, 2025\", \"method_details\": {\"payer_name\": \"SAM\", \"payer_phone\": \"90765643\"}}', '2025-11-19 04:36:44', '2025-11-19 04:36:44');
INSERT INTO `receipts` VALUES ('30', '91', '{\"payment\": {\"id\": 91, \"date\": \"2025-11-19\", \"term\": null, \"amount\": \"280.00\", \"fee_id\": 19, \"method\": \"cash\", \"remarks\": \"\", \"created_at\": \"2025-11-19 04:50:46\", \"student_id\": 61, \"updated_at\": \"2025-11-19 04:50:46\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"09786\", \"academic_year_id\": null, \"cash_payer_phone\": \"kjhj\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 61, \"dob\": \"0000-00-00\", \"gender\": \"male\", \"address\": \"123 Main St\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Asante\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"Kweku\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0050\", \"medical_info\": null, \"guardian_name\": \"Mr. Mensah\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233461872271\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_boarding\", \"student_category_details\": null}, \"generated_at\": \"2025-11-19 04:50:46\", \"method_label\": \"Cash\", \"payment_date\": \"Nov 19, 2025\", \"method_details\": {\"payer_name\": \"09786\", \"payer_phone\": \"kjhj\"}}', '2025-11-19 04:50:46', '2025-11-19 04:50:46');
INSERT INTO `receipts` VALUES ('31', '92', '{\"payment\": {\"id\": 92, \"date\": \"2025-11-19\", \"term\": null, \"amount\": \"100.00\", \"fee_id\": 18, \"method\": \"cash\", \"remarks\": \"\", \"created_at\": \"2025-11-19 04:50:46\", \"student_id\": 61, \"updated_at\": \"2025-11-19 04:50:46\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"09786\", \"academic_year_id\": null, \"cash_payer_phone\": \"kjhj\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 61, \"dob\": \"0000-00-00\", \"gender\": \"male\", \"address\": \"123 Main St\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Asante\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"Kweku\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0050\", \"medical_info\": null, \"guardian_name\": \"Mr. Mensah\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233461872271\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_boarding\", \"student_category_details\": null}, \"generated_at\": \"2025-11-19 04:50:46\", \"method_label\": \"Cash\", \"payment_date\": \"Nov 19, 2025\", \"method_details\": {\"payer_name\": \"09786\", \"payer_phone\": \"kjhj\"}}', '2025-11-19 04:50:46', '2025-11-19 04:50:46');
INSERT INTO `receipts` VALUES ('32', '93', '{\"payment\": {\"id\": 93, \"date\": \"2025-11-19\", \"term\": null, \"amount\": \"280.00\", \"fee_id\": 19, \"method\": \"cash\", \"remarks\": \"\", \"created_at\": \"2025-11-19 05:00:59\", \"student_id\": 6, \"updated_at\": \"2025-11-19 05:00:59\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": null, \"academic_year_id\": null, \"cash_payer_phone\": null, \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 6, \"dob\": \"2010-06-15\", \"gender\": \"female\", \"address\": \"\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Brown\", \"created_at\": \"2025-10-14 19:30:45\", \"first_name\": \"Akwesi\", \"updated_at\": \"2025-10-14 20:11:05\", \"admission_no\": \"15102378\", \"medical_info\": null, \"guardian_name\": \"YaYa\", \"admission_date\": null, \"guardian_phone\": \"5657575\", \"profile_picture\": \"student_1760472665_68eeae593c922.jpeg\", \"academic_year_id\": null, \"student_category\": \"regular_day\", \"student_category_details\": null}, \"generated_at\": \"2025-11-19 05:00:59\", \"method_label\": \"Cash\", \"payment_date\": \"Nov 19, 2025\", \"method_details\": []}', '2025-11-19 05:00:59', '2025-11-19 05:00:59');
INSERT INTO `receipts` VALUES ('33', '94', '{\"payment\": {\"id\": 94, \"date\": \"2025-11-19\", \"term\": null, \"amount\": \"100.00\", \"fee_id\": 18, \"method\": \"cash\", \"remarks\": \"\", \"created_at\": \"2025-11-19 05:00:59\", \"student_id\": 6, \"updated_at\": \"2025-11-19 05:00:59\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": null, \"academic_year_id\": null, \"cash_payer_phone\": null, \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 6, \"dob\": \"2010-06-15\", \"gender\": \"female\", \"address\": \"\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Brown\", \"created_at\": \"2025-10-14 19:30:45\", \"first_name\": \"Akwesi\", \"updated_at\": \"2025-10-14 20:11:05\", \"admission_no\": \"15102378\", \"medical_info\": null, \"guardian_name\": \"YaYa\", \"admission_date\": null, \"guardian_phone\": \"5657575\", \"profile_picture\": \"student_1760472665_68eeae593c922.jpeg\", \"academic_year_id\": null, \"student_category\": \"regular_day\", \"student_category_details\": null}, \"generated_at\": \"2025-11-19 05:00:59\", \"method_label\": \"Cash\", \"payment_date\": \"Nov 19, 2025\", \"method_details\": []}', '2025-11-19 05:00:59', '2025-11-19 05:00:59');
INSERT INTO `receipts` VALUES ('34', '95', '{\"payment\": {\"id\": 95, \"date\": \"2025-11-19\", \"term\": null, \"amount\": \"100.00\", \"fee_id\": 19, \"method\": \"cash\", \"remarks\": \"\", \"created_at\": \"2025-11-19 05:08:24\", \"student_id\": 48, \"updated_at\": \"2025-11-19 05:08:24\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": null, \"academic_year_id\": null, \"cash_payer_phone\": null, \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 48, \"dob\": \"0000-00-00\", \"gender\": \"female\", \"address\": \"456 Oak Ave\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Amponsah\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"Adwoa\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0037\", \"medical_info\": null, \"guardian_name\": \"Mr. Mensah\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233320679366\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_day\", \"student_category_details\": null}, \"generated_at\": \"2025-11-19 05:08:24\", \"method_label\": \"Cash\", \"payment_date\": \"Nov 19, 2025\", \"method_details\": []}', '2025-11-19 05:08:24', '2025-11-19 05:08:24');
INSERT INTO `receipts` VALUES ('35', '96', '{\"payment\": {\"id\": 96, \"date\": \"2025-11-19\", \"term\": null, \"amount\": \"100.00\", \"fee_id\": 18, \"method\": \"cash\", \"remarks\": \"\", \"created_at\": \"2025-11-19 05:08:24\", \"student_id\": 48, \"updated_at\": \"2025-11-19 05:08:24\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": null, \"academic_year_id\": null, \"cash_payer_phone\": null, \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 48, \"dob\": \"0000-00-00\", \"gender\": \"female\", \"address\": \"456 Oak Ave\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Amponsah\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"Adwoa\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0037\", \"medical_info\": null, \"guardian_name\": \"Mr. Mensah\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233320679366\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_day\", \"student_category_details\": null}, \"generated_at\": \"2025-11-19 05:08:24\", \"method_label\": \"Cash\", \"payment_date\": \"Nov 19, 2025\", \"method_details\": []}', '2025-11-19 05:08:24', '2025-11-19 05:08:24');
INSERT INTO `receipts` VALUES ('36', '97', '{\"payment\": {\"id\": 97, \"date\": \"2025-11-19\", \"term\": null, \"amount\": \"180.00\", \"fee_id\": 19, \"method\": \"cash\", \"remarks\": \"\", \"created_at\": \"2025-11-19 05:15:47\", \"student_id\": 10, \"updated_at\": \"2025-11-19 05:15:47\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": null, \"academic_year_id\": null, \"cash_payer_phone\": null, \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 10, \"dob\": \"2011-02-17\", \"gender\": \"male\", \"address\": \"opeikumah\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Mintah\", \"created_at\": \"2025-10-17 10:57:10\", \"first_name\": \"Kwame\", \"updated_at\": \"2025-10-17 10:57:10\", \"admission_no\": \"EPI-105526\", \"medical_info\": null, \"guardian_name\": \"Mr Mintah\", \"admission_date\": \"2025-10-17\", \"guardian_phone\": \"5657575\", \"profile_picture\": null, \"academic_year_id\": 2, \"student_category\": \"regular_day\", \"student_category_details\": \"\"}, \"generated_at\": \"2025-11-19 05:15:47\", \"method_label\": \"Cash\", \"payment_date\": \"Nov 19, 2025\", \"method_details\": []}', '2025-11-19 05:15:47', '2025-11-19 05:15:47');
INSERT INTO `receipts` VALUES ('37', '98', '{\"payment\": {\"id\": 98, \"date\": \"2025-11-19\", \"term\": null, \"amount\": \"150.00\", \"fee_id\": 18, \"method\": \"cash\", \"remarks\": \"\", \"created_at\": \"2025-11-19 05:15:47\", \"student_id\": 10, \"updated_at\": \"2025-11-19 05:15:47\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": null, \"academic_year_id\": null, \"cash_payer_phone\": null, \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 10, \"dob\": \"2011-02-17\", \"gender\": \"male\", \"address\": \"opeikumah\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Mintah\", \"created_at\": \"2025-10-17 10:57:10\", \"first_name\": \"Kwame\", \"updated_at\": \"2025-10-17 10:57:10\", \"admission_no\": \"EPI-105526\", \"medical_info\": null, \"guardian_name\": \"Mr Mintah\", \"admission_date\": \"2025-10-17\", \"guardian_phone\": \"5657575\", \"profile_picture\": null, \"academic_year_id\": 2, \"student_category\": \"regular_day\", \"student_category_details\": \"\"}, \"generated_at\": \"2025-11-19 05:15:47\", \"method_label\": \"Cash\", \"payment_date\": \"Nov 19, 2025\", \"method_details\": []}', '2025-11-19 05:15:47', '2025-11-19 05:15:47');
INSERT INTO `receipts` VALUES ('38', '99', '{\"payment\": {\"id\": 99, \"date\": \"2025-11-19\", \"term\": null, \"amount\": \"100.00\", \"fee_id\": 19, \"method\": \"cash\", \"remarks\": \"\", \"created_at\": \"2025-11-19 05:26:14\", \"student_id\": 34, \"updated_at\": \"2025-11-19 05:26:14\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": null, \"academic_year_id\": null, \"cash_payer_phone\": null, \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 34, \"dob\": \"0000-00-00\", \"gender\": \"male\", \"address\": \"123 Main St\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Boadu\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"John\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0023\", \"medical_info\": null, \"guardian_name\": \"Mrs. Amponsah\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233384220631\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_day\", \"student_category_details\": null}, \"generated_at\": \"2025-11-19 05:26:14\", \"method_label\": \"Cash\", \"payment_date\": \"Nov 19, 2025\", \"method_details\": []}', '2025-11-19 05:26:14', '2025-11-19 05:26:14');
INSERT INTO `receipts` VALUES ('39', '100', '{\"payment\": {\"id\": 100, \"date\": \"2025-11-19\", \"term\": null, \"amount\": \"50.00\", \"fee_id\": 18, \"method\": \"cash\", \"remarks\": \"\", \"created_at\": \"2025-11-19 05:26:14\", \"student_id\": 34, \"updated_at\": \"2025-11-19 05:26:14\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": null, \"academic_year_id\": null, \"cash_payer_phone\": null, \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 34, \"dob\": \"0000-00-00\", \"gender\": \"male\", \"address\": \"123 Main St\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Boadu\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"John\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0023\", \"medical_info\": null, \"guardian_name\": \"Mrs. Amponsah\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233384220631\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_day\", \"student_category_details\": null}, \"generated_at\": \"2025-11-19 05:26:14\", \"method_label\": \"Cash\", \"payment_date\": \"Nov 19, 2025\", \"method_details\": []}', '2025-11-19 05:26:14', '2025-11-19 05:26:14');
INSERT INTO `receipts` VALUES ('40', '101', '{\"payment\": {\"id\": 101, \"date\": \"2025-11-19\", \"term\": null, \"amount\": \"100.00\", \"fee_id\": 19, \"method\": \"cash\", \"remarks\": \"\", \"created_at\": \"2025-11-19 05:52:36\", \"student_id\": 34, \"updated_at\": \"2025-11-19 05:52:36\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": null, \"academic_year_id\": null, \"cash_payer_phone\": null, \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 34, \"dob\": \"0000-00-00\", \"gender\": \"male\", \"address\": \"123 Main St\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Boadu\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"John\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0023\", \"medical_info\": null, \"guardian_name\": \"Mrs. Amponsah\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233384220631\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_day\", \"student_category_details\": null}, \"generated_at\": \"2025-11-19 05:52:36\", \"method_label\": \"Cash\", \"payment_date\": \"Nov 19, 2025\", \"method_details\": []}', '2025-11-19 05:52:36', '2025-11-19 05:52:36');
INSERT INTO `receipts` VALUES ('41', '103', '{\"payment\": {\"id\": 103, \"date\": \"2025-11-19\", \"term\": null, \"amount\": \"200.00\", \"fee_id\": 19, \"method\": \"cash\", \"remarks\": \"kljhg\", \"created_at\": \"2025-11-19 14:40:06\", \"student_id\": 76, \"updated_at\": \"2025-11-19 14:40:06\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"sddfh\", \"academic_year_id\": null, \"cash_payer_phone\": \"0987765\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 76, \"dob\": \"0000-00-00\", \"gender\": \"male\", \"address\": \"456 Oak Ave\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Adjei\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"Kofi\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0065\", \"medical_info\": null, \"guardian_name\": \"Mr. Boateng\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233588406574\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_day\", \"student_category_details\": null}, \"generated_at\": \"2025-11-19 14:40:06\", \"method_label\": \"Cash\", \"payment_date\": \"Nov 19, 2025\", \"method_details\": {\"payer_name\": \"sddfh\", \"payer_phone\": \"0987765\"}}', '2025-11-19 14:40:06', '2025-11-19 14:40:06');
INSERT INTO `receipts` VALUES ('42', '104', '{\"payment\": {\"id\": 104, \"date\": \"2025-11-19\", \"term\": null, \"amount\": \"50.00\", \"fee_id\": 18, \"method\": \"cash\", \"remarks\": \"kljhg\", \"created_at\": \"2025-11-19 14:40:06\", \"student_id\": 76, \"updated_at\": \"2025-11-19 14:40:06\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"sddfh\", \"academic_year_id\": null, \"cash_payer_phone\": \"0987765\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 76, \"dob\": \"0000-00-00\", \"gender\": \"male\", \"address\": \"456 Oak Ave\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Adjei\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"Kofi\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0065\", \"medical_info\": null, \"guardian_name\": \"Mr. Boateng\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233588406574\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_day\", \"student_category_details\": null}, \"generated_at\": \"2025-11-19 14:40:06\", \"method_label\": \"Cash\", \"payment_date\": \"Nov 19, 2025\", \"method_details\": {\"payer_name\": \"sddfh\", \"payer_phone\": \"0987765\"}}', '2025-11-19 14:40:06', '2025-11-19 14:40:06');
INSERT INTO `receipts` VALUES ('43', '105', '{\"payment\": {\"id\": 105, \"date\": \"2025-11-19\", \"term\": null, \"amount\": \"180.00\", \"fee_id\": 19, \"method\": \"mobile_money\", \"remarks\": \"good\", \"created_at\": \"2025-11-19 15:47:48\", \"student_id\": 33, \"updated_at\": \"2025-11-19 15:47:48\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": null, \"academic_year_id\": null, \"cash_payer_phone\": null, \"bank_transfer_details\": null, \"mobile_money_reference\": \"Afia Amponsah (STU0022)\", \"mobile_money_sender_name\": \"sam\", \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": \"0987654\", \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 33, \"dob\": \"0000-00-00\", \"gender\": \"female\", \"address\": \"456 Oak Ave\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Amponsah\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"Afia\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0022\", \"medical_info\": null, \"guardian_name\": \"Mr. Adjei\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233229107205\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_boarding\", \"student_category_details\": null}, \"generated_at\": \"2025-11-19 15:47:48\", \"method_label\": \"Mobile Money\", \"payment_date\": \"Nov 19, 2025\", \"method_details\": {\"reference\": \"Afia Amponsah (STU0022)\", \"sender_name\": \"sam\", \"sender_number\": \"0987654\"}}', '2025-11-19 15:47:48', '2025-11-19 15:47:48');
INSERT INTO `receipts` VALUES ('44', '106', '{\"payment\": {\"id\": 106, \"date\": \"2025-11-19\", \"term\": null, \"amount\": \"300.00\", \"fee_id\": 18, \"method\": \"mobile_money\", \"remarks\": \"good\", \"created_at\": \"2025-11-19 15:47:48\", \"student_id\": 33, \"updated_at\": \"2025-11-19 15:47:48\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": null, \"academic_year_id\": null, \"cash_payer_phone\": null, \"bank_transfer_details\": null, \"mobile_money_reference\": \"Afia Amponsah (STU0022)\", \"mobile_money_sender_name\": \"sam\", \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": \"0987654\", \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 33, \"dob\": \"0000-00-00\", \"gender\": \"female\", \"address\": \"456 Oak Ave\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Amponsah\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"Afia\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0022\", \"medical_info\": null, \"guardian_name\": \"Mr. Adjei\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233229107205\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_boarding\", \"student_category_details\": null}, \"generated_at\": \"2025-11-19 15:47:48\", \"method_label\": \"Mobile Money\", \"payment_date\": \"Nov 19, 2025\", \"method_details\": {\"reference\": \"Afia Amponsah (STU0022)\", \"sender_name\": \"sam\", \"sender_number\": \"0987654\"}}', '2025-11-19 15:47:48', '2025-11-19 15:47:48');
INSERT INTO `receipts` VALUES ('45', '107', '{\"payment\": {\"id\": 107, \"date\": \"2025-11-19\", \"term\": null, \"amount\": \"780.00\", \"fee_id\": 19, \"method\": \"cash\", \"remarks\": \"saa\", \"created_at\": \"2025-11-19 19:27:31\", \"student_id\": 7, \"updated_at\": \"2025-11-19 19:27:31\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"sam\", \"academic_year_id\": null, \"cash_payer_phone\": \"0987653423\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 7, \"dob\": \"2010-06-15\", \"gender\": \"male\", \"address\": \"\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Brown Edem\", \"created_at\": \"2025-10-14 19:41:44\", \"first_name\": \"IKe\", \"updated_at\": \"2025-10-14 20:16:01\", \"admission_no\": \"15102378s4\", \"medical_info\": null, \"guardian_name\": \"YaYa\", \"admission_date\": null, \"guardian_phone\": \"5657575\", \"profile_picture\": \"student_1760472961_68eeaf81259bb.jpeg\", \"academic_year_id\": null, \"student_category\": \"regular_day\", \"student_category_details\": null}, \"generated_at\": \"2025-11-19 19:27:31\", \"method_label\": \"Cash\", \"payment_date\": \"Nov 19, 2025\", \"method_details\": {\"payer_name\": \"sam\", \"payer_phone\": \"0987653423\"}}', '2025-11-19 19:27:31', '2025-11-19 19:27:31');
INSERT INTO `receipts` VALUES ('46', '108', '{\"payment\": {\"id\": 108, \"date\": \"2025-11-19\", \"term\": null, \"amount\": \"250.00\", \"fee_id\": 18, \"method\": \"cash\", \"remarks\": \"saa\", \"created_at\": \"2025-11-19 19:27:31\", \"student_id\": 7, \"updated_at\": \"2025-11-19 19:27:31\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"sam\", \"academic_year_id\": null, \"cash_payer_phone\": \"0987653423\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 7, \"dob\": \"2010-06-15\", \"gender\": \"male\", \"address\": \"\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Brown Edem\", \"created_at\": \"2025-10-14 19:41:44\", \"first_name\": \"IKe\", \"updated_at\": \"2025-10-14 20:16:01\", \"admission_no\": \"15102378s4\", \"medical_info\": null, \"guardian_name\": \"YaYa\", \"admission_date\": null, \"guardian_phone\": \"5657575\", \"profile_picture\": \"student_1760472961_68eeaf81259bb.jpeg\", \"academic_year_id\": null, \"student_category\": \"regular_day\", \"student_category_details\": null}, \"generated_at\": \"2025-11-19 19:27:31\", \"method_label\": \"Cash\", \"payment_date\": \"Nov 19, 2025\", \"method_details\": {\"payer_name\": \"sam\", \"payer_phone\": \"0987653423\"}}', '2025-11-19 19:27:31', '2025-11-19 19:27:31');
INSERT INTO `receipts` VALUES ('47', '109', '{\"payment\": {\"id\": 109, \"date\": \"2025-11-19\", \"term\": null, \"amount\": \"280.00\", \"fee_id\": 19, \"method\": \"cash\", \"remarks\": \"sav\", \"created_at\": \"2025-11-19 20:01:08\", \"student_id\": 8, \"updated_at\": \"2025-11-19 20:01:08\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"sam\", \"academic_year_id\": null, \"cash_payer_phone\": \"09876543\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 8, \"dob\": \"2010-06-15\", \"gender\": \"male\", \"address\": \"\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Brown Edem\", \"created_at\": \"2025-10-14 20:16:56\", \"first_name\": \"mina\", \"updated_at\": \"2025-10-17 10:22:51\", \"admission_no\": \"15102378s4r\", \"medical_info\": null, \"guardian_name\": \"YaYa\", \"admission_date\": \"2025-10-16\", \"guardian_phone\": \"5657575\", \"profile_picture\": \"student_1760473084_68eeaffc22f98.jpeg\", \"academic_year_id\": 2, \"student_category\": \"regular_day\", \"student_category_details\": \"\"}, \"generated_at\": \"2025-11-19 20:01:08\", \"method_label\": \"Cash\", \"payment_date\": \"Nov 19, 2025\", \"method_details\": {\"payer_name\": \"sam\", \"payer_phone\": \"09876543\"}}', '2025-11-19 20:01:08', '2025-11-19 20:01:08');
INSERT INTO `receipts` VALUES ('48', '110', '{\"payment\": {\"id\": 110, \"date\": \"2025-11-19\", \"term\": null, \"amount\": \"150.00\", \"fee_id\": 18, \"method\": \"cash\", \"remarks\": \"sav\", \"created_at\": \"2025-11-19 20:01:08\", \"student_id\": 8, \"updated_at\": \"2025-11-19 20:01:08\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"sam\", \"academic_year_id\": null, \"cash_payer_phone\": \"09876543\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 8, \"dob\": \"2010-06-15\", \"gender\": \"male\", \"address\": \"\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Brown Edem\", \"created_at\": \"2025-10-14 20:16:56\", \"first_name\": \"mina\", \"updated_at\": \"2025-10-17 10:22:51\", \"admission_no\": \"15102378s4r\", \"medical_info\": null, \"guardian_name\": \"YaYa\", \"admission_date\": \"2025-10-16\", \"guardian_phone\": \"5657575\", \"profile_picture\": \"student_1760473084_68eeaffc22f98.jpeg\", \"academic_year_id\": 2, \"student_category\": \"regular_day\", \"student_category_details\": \"\"}, \"generated_at\": \"2025-11-19 20:01:08\", \"method_label\": \"Cash\", \"payment_date\": \"Nov 19, 2025\", \"method_details\": {\"payer_name\": \"sam\", \"payer_phone\": \"09876543\"}}', '2025-11-19 20:01:08', '2025-11-19 20:01:08');
INSERT INTO `receipts` VALUES ('49', '111', '{\"payment\": {\"id\": 111, \"date\": \"2025-11-23\", \"term\": null, \"amount\": \"200.00\", \"fee_id\": 20, \"method\": \"cash\", \"remarks\": \"fully paid\", \"created_at\": \"2025-11-23 13:27:13\", \"student_id\": 112, \"updated_at\": \"2025-11-23 13:27:13\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"sam\", \"academic_year_id\": null, \"cash_payer_phone\": \"0987534523\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 112, \"dob\": \"2007-11-23\", \"gender\": \"\", \"address\": \"234/9kk accra\", \"user_id\": null, \"class_id\": 14, \"last_name\": \"Woani\", \"created_at\": \"2025-10-24 12:12:37\", \"first_name\": \"Micheal\", \"updated_at\": \"2025-10-24 12:12:37\", \"admission_no\": \"EPI-121118\", \"medical_info\": \"\", \"guardian_name\": \"Mr. Appiah\", \"admission_date\": \"2025-10-24\", \"guardian_phone\": \"+233501927075\", \"profile_picture\": null, \"academic_year_id\": 2, \"student_category\": \"regular_boarding\", \"student_category_details\": \"\"}, \"generated_at\": \"2025-11-23 13:27:13\", \"method_label\": \"Cash\", \"payment_date\": \"Nov 23, 2025\", \"method_details\": {\"payer_name\": \"sam\", \"payer_phone\": \"0987534523\"}}', '2025-11-23 13:27:13', '2025-11-23 13:27:13');
INSERT INTO `receipts` VALUES ('50', '112', '{\"payment\": {\"id\": 112, \"date\": \"2025-11-23\", \"term\": null, \"amount\": \"1850.00\", \"fee_id\": 17, \"method\": \"cash\", \"remarks\": \"fully paid\", \"created_at\": \"2025-11-23 13:27:13\", \"student_id\": 112, \"updated_at\": \"2025-11-23 13:27:13\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"sam\", \"academic_year_id\": null, \"cash_payer_phone\": \"0987534523\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 112, \"dob\": \"2007-11-23\", \"gender\": \"\", \"address\": \"234/9kk accra\", \"user_id\": null, \"class_id\": 14, \"last_name\": \"Woani\", \"created_at\": \"2025-10-24 12:12:37\", \"first_name\": \"Micheal\", \"updated_at\": \"2025-10-24 12:12:37\", \"admission_no\": \"EPI-121118\", \"medical_info\": \"\", \"guardian_name\": \"Mr. Appiah\", \"admission_date\": \"2025-10-24\", \"guardian_phone\": \"+233501927075\", \"profile_picture\": null, \"academic_year_id\": 2, \"student_category\": \"regular_boarding\", \"student_category_details\": \"\"}, \"generated_at\": \"2025-11-23 13:27:13\", \"method_label\": \"Cash\", \"payment_date\": \"Nov 23, 2025\", \"method_details\": {\"payer_name\": \"sam\", \"payer_phone\": \"0987534523\"}}', '2025-11-23 13:27:13', '2025-11-23 13:27:13');
INSERT INTO `receipts` VALUES ('51', '113', '{\"payment\": {\"id\": 113, \"date\": \"2025-12-05\", \"term\": null, \"amount\": \"2000.00\", \"fee_id\": 21, \"method\": \"cash\", \"remarks\": \"good\", \"created_at\": \"2025-12-05 00:23:46\", \"student_id\": 125, \"updated_at\": \"2025-12-05 00:23:46\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"samn\", \"academic_year_id\": 2, \"cash_payer_phone\": \"564646456\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 125, \"dob\": \"2005-11-16\", \"gender\": \"male\", \"address\": \"SK 234kaSOA\", \"user_id\": null, \"class_id\": 15, \"last_name\": \"Eisntin\", \"created_at\": \"2025-12-04 16:30:07\", \"first_name\": \"Albert\", \"updated_at\": \"2025-12-04 16:30:07\", \"admission_no\": \"EPI-162949\", \"medical_info\": \"Alergic to peanut butter\", \"guardian_name\": \"Mr science\", \"admission_date\": \"2025-12-04\", \"guardian_phone\": \"08976867857\", \"profile_picture\": \"student_1764865807_6931b70f153bf.png\", \"academic_year_id\": 2, \"student_category\": \"regular_day\", \"student_category_details\": \"\"}, \"generated_at\": \"2025-12-05 00:23:46\", \"method_label\": \"Cash\", \"payment_date\": \"Dec 5, 2025\", \"method_details\": {\"payer_name\": \"samn\", \"payer_phone\": \"564646456\"}}', '2025-12-05 00:23:46', '2025-12-05 00:23:46');
INSERT INTO `receipts` VALUES ('52', '114', '{\"payment\": {\"id\": 114, \"date\": \"2025-12-05\", \"term\": null, \"amount\": \"100.00\", \"fee_id\": 20, \"method\": \"cash\", \"remarks\": \"samm\", \"created_at\": \"2025-12-05 00:37:01\", \"student_id\": 76, \"updated_at\": \"2025-12-05 00:37:01\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"sam\", \"academic_year_id\": 2, \"cash_payer_phone\": \"0976453423\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 76, \"dob\": \"0000-00-00\", \"gender\": \"male\", \"address\": \"456 Oak Ave\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Adjei\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"Kofi\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0065\", \"medical_info\": null, \"guardian_name\": \"Mr. Boateng\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233588406574\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_day\", \"student_category_details\": null}, \"generated_at\": \"2025-12-05 00:37:01\", \"method_label\": \"Cash\", \"payment_date\": \"Dec 5, 2025\", \"method_details\": {\"payer_name\": \"sam\", \"payer_phone\": \"0976453423\"}}', '2025-12-05 00:37:01', '2025-12-05 00:37:01');
INSERT INTO `receipts` VALUES ('53', '115', '{\"payment\": {\"id\": 115, \"date\": \"2025-12-05\", \"term\": null, \"amount\": \"100.00\", \"fee_id\": 19, \"method\": \"cash\", \"remarks\": \"samm\", \"created_at\": \"2025-12-05 00:37:01\", \"student_id\": 76, \"updated_at\": \"2025-12-05 00:37:01\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"sam\", \"academic_year_id\": 2, \"cash_payer_phone\": \"0976453423\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 76, \"dob\": \"0000-00-00\", \"gender\": \"male\", \"address\": \"456 Oak Ave\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Adjei\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"Kofi\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0065\", \"medical_info\": null, \"guardian_name\": \"Mr. Boateng\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233588406574\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_day\", \"student_category_details\": null}, \"generated_at\": \"2025-12-05 00:37:01\", \"method_label\": \"Cash\", \"payment_date\": \"Dec 5, 2025\", \"method_details\": {\"payer_name\": \"sam\", \"payer_phone\": \"0976453423\"}}', '2025-12-05 00:37:01', '2025-12-05 00:37:01');
INSERT INTO `receipts` VALUES ('54', '116', '{\"payment\": {\"id\": 116, \"date\": \"2025-12-05\", \"term\": \"1st Term\", \"amount\": \"300.00\", \"fee_id\": 20, \"method\": \"cash\", \"remarks\": \"ki\", \"created_at\": \"2025-12-05 00:58:09\", \"student_id\": 89, \"updated_at\": \"2025-12-05 00:58:09\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"sabn\", \"academic_year_id\": 2, \"cash_payer_phone\": \"007687657\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 89, \"dob\": \"0000-00-00\", \"gender\": \"male\", \"address\": \"123 Main St\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Adjei\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"Yaw\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0078\", \"medical_info\": null, \"guardian_name\": \"Mr. Boadu\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233352593474\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_boarding\", \"student_category_details\": null}, \"generated_at\": \"2025-12-05 00:58:09\", \"method_label\": \"Cash\", \"payment_date\": \"Dec 5, 2025\", \"method_details\": {\"payer_name\": \"sabn\", \"payer_phone\": \"007687657\"}}', '2025-12-05 00:58:09', '2025-12-05 00:58:09');
INSERT INTO `receipts` VALUES ('55', '117', '{\"payment\": {\"id\": 117, \"date\": \"2025-12-05\", \"term\": \"1st Term\", \"amount\": \"250.00\", \"fee_id\": 18, \"method\": \"cash\", \"remarks\": \"ki\", \"created_at\": \"2025-12-05 00:58:09\", \"student_id\": 89, \"updated_at\": \"2025-12-05 00:58:09\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"sabn\", \"academic_year_id\": 2, \"cash_payer_phone\": \"007687657\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 89, \"dob\": \"0000-00-00\", \"gender\": \"male\", \"address\": \"123 Main St\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Adjei\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"Yaw\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0078\", \"medical_info\": null, \"guardian_name\": \"Mr. Boadu\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233352593474\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_boarding\", \"student_category_details\": null}, \"generated_at\": \"2025-12-05 00:58:09\", \"method_label\": \"Cash\", \"payment_date\": \"Dec 5, 2025\", \"method_details\": {\"payer_name\": \"sabn\", \"payer_phone\": \"007687657\"}}', '2025-12-05 00:58:09', '2025-12-05 00:58:09');
INSERT INTO `receipts` VALUES ('56', '118', '{\"payment\": {\"id\": 118, \"date\": \"2025-12-05\", \"term\": \"1st Term\", \"amount\": \"200.00\", \"fee_id\": 19, \"method\": \"cash\", \"remarks\": \"ki\", \"created_at\": \"2025-12-05 00:58:09\", \"student_id\": 89, \"updated_at\": \"2025-12-05 00:58:09\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"sabn\", \"academic_year_id\": 2, \"cash_payer_phone\": \"007687657\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 89, \"dob\": \"0000-00-00\", \"gender\": \"male\", \"address\": \"123 Main St\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Adjei\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"Yaw\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0078\", \"medical_info\": null, \"guardian_name\": \"Mr. Boadu\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233352593474\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_boarding\", \"student_category_details\": null}, \"generated_at\": \"2025-12-05 00:58:09\", \"method_label\": \"Cash\", \"payment_date\": \"Dec 5, 2025\", \"method_details\": {\"payer_name\": \"sabn\", \"payer_phone\": \"007687657\"}}', '2025-12-05 00:58:09', '2025-12-05 00:58:09');
INSERT INTO `receipts` VALUES ('57', '119', '{\"payment\": {\"id\": 119, \"date\": \"2025-12-05\", \"term\": \"1st Term\", \"amount\": \"200.00\", \"fee_id\": 20, \"method\": \"cash\", \"remarks\": \"klk\", \"created_at\": \"2025-12-05 01:08:59\", \"student_id\": 76, \"updated_at\": \"2025-12-05 01:08:59\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"as\", \"academic_year_id\": 2, \"cash_payer_phone\": \"0775453432\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 76, \"dob\": \"0000-00-00\", \"gender\": \"male\", \"address\": \"456 Oak Ave\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Adjei\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"Kofi\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0065\", \"medical_info\": null, \"guardian_name\": \"Mr. Boateng\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233588406574\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_day\", \"student_category_details\": null}, \"generated_at\": \"2025-12-05 01:08:59\", \"method_label\": \"Cash\", \"payment_date\": \"Dec 5, 2025\", \"method_details\": {\"payer_name\": \"as\", \"payer_phone\": \"0775453432\"}}', '2025-12-05 01:08:59', '2025-12-05 01:08:59');
INSERT INTO `receipts` VALUES ('58', '120', '{\"payment\": {\"id\": 120, \"date\": \"2025-12-05\", \"term\": \"1st Term\", \"amount\": \"100.00\", \"fee_id\": 19, \"method\": \"cash\", \"remarks\": \"klk\", \"created_at\": \"2025-12-05 01:08:59\", \"student_id\": 76, \"updated_at\": \"2025-12-05 01:08:59\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"as\", \"academic_year_id\": 2, \"cash_payer_phone\": \"0775453432\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 76, \"dob\": \"0000-00-00\", \"gender\": \"male\", \"address\": \"456 Oak Ave\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Adjei\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"Kofi\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0065\", \"medical_info\": null, \"guardian_name\": \"Mr. Boateng\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233588406574\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_day\", \"student_category_details\": null}, \"generated_at\": \"2025-12-05 01:08:59\", \"method_label\": \"Cash\", \"payment_date\": \"Dec 5, 2025\", \"method_details\": {\"payer_name\": \"as\", \"payer_phone\": \"0775453432\"}}', '2025-12-05 01:08:59', '2025-12-05 01:08:59');
INSERT INTO `receipts` VALUES ('59', '121', '{\"payment\": {\"id\": 121, \"date\": \"2025-12-05\", \"term\": \"1st Term\", \"amount\": \"1000.00\", \"fee_id\": 21, \"method\": \"cash\", \"remarks\": \"sa\", \"created_at\": \"2025-12-05 01:44:07\", \"student_id\": 125, \"updated_at\": \"2025-12-05 01:44:07\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"sam\", \"academic_year_id\": 2, \"cash_payer_phone\": \"06754634\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 125, \"dob\": \"2005-11-16\", \"gender\": \"male\", \"address\": \"SK 234kaSOA\", \"user_id\": null, \"class_id\": 15, \"last_name\": \"Eisntin\", \"created_at\": \"2025-12-04 16:30:07\", \"first_name\": \"Albert\", \"updated_at\": \"2025-12-04 16:30:07\", \"admission_no\": \"EPI-162949\", \"medical_info\": \"Alergic to peanut butter\", \"guardian_name\": \"Mr science\", \"admission_date\": \"2025-12-04\", \"guardian_phone\": \"08976867857\", \"profile_picture\": \"student_1764865807_6931b70f153bf.png\", \"academic_year_id\": 2, \"student_category\": \"regular_day\", \"student_category_details\": \"\"}, \"generated_at\": \"2025-12-05 01:44:07\", \"method_label\": \"Cash\", \"payment_date\": \"Dec 5, 2025\", \"method_details\": {\"payer_name\": \"sam\", \"payer_phone\": \"06754634\"}}', '2025-12-05 01:44:07', '2025-12-05 01:44:07');
INSERT INTO `receipts` VALUES ('60', '122', '{\"payment\": {\"id\": 122, \"date\": \"2025-12-05\", \"term\": \"1st Term\", \"amount\": \"900.00\", \"fee_id\": 21, \"method\": \"cash\", \"remarks\": \"kl\", \"created_at\": \"2025-12-05 02:17:11\", \"student_id\": 126, \"updated_at\": \"2025-12-05 02:17:11\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"Sammjh\", \"academic_year_id\": 2, \"cash_payer_phone\": \"097856453434\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 126, \"dob\": \"2006-10-11\", \"gender\": \"female\", \"address\": \"234/9kk accra\", \"user_id\": null, \"class_id\": 16, \"last_name\": \"ANNA\", \"created_at\": \"2025-12-05 01:47:42\", \"first_name\": \"AKUBI\", \"updated_at\": \"2025-12-05 01:48:29\", \"admission_no\": \"EPI-014648\", \"medical_info\": \"\", \"guardian_name\": \"Mrs. Nyarko\", \"admission_date\": \"2025-12-05\", \"guardian_phone\": \"03523223\", \"profile_picture\": \"student_1764899262_693239be10323.png\", \"academic_year_id\": 2, \"student_category\": \"regular_day\", \"student_category_details\": \"\"}, \"generated_at\": \"2025-12-05 02:17:11\", \"method_label\": \"Cash\", \"payment_date\": \"Dec 5, 2025\", \"method_details\": {\"payer_name\": \"Sammjh\", \"payer_phone\": \"097856453434\"}}', '2025-12-05 02:17:11', '2025-12-05 02:17:11');
INSERT INTO `receipts` VALUES ('61', '123', '{\"payment\": {\"id\": 123, \"date\": \"2025-12-05\", \"term\": \"1st Term\", \"amount\": \"200.00\", \"fee_id\": 19, \"method\": \"cash\", \"remarks\": \"\", \"created_at\": \"2025-12-05 03:00:58\", \"student_id\": 89, \"updated_at\": \"2025-12-05 03:00:58\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"pokpoipo\", \"academic_year_id\": 2, \"cash_payer_phone\": \"4442452\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 89, \"dob\": \"0000-00-00\", \"gender\": \"male\", \"address\": \"123 Main St\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Adjei\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"Yaw\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0078\", \"medical_info\": null, \"guardian_name\": \"Mr. Boadu\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233352593474\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_boarding\", \"student_category_details\": null}, \"generated_at\": \"2025-12-05 03:00:58\", \"method_label\": \"Cash\", \"payment_date\": \"Dec 5, 2025\", \"method_details\": {\"payer_name\": \"pokpoipo\", \"payer_phone\": \"4442452\"}}', '2025-12-05 03:00:58', '2025-12-05 03:00:58');
INSERT INTO `receipts` VALUES ('62', '124', '{\"payment\": {\"id\": 124, \"date\": \"2025-12-06\", \"term\": \"1st Term\", \"amount\": \"100.00\", \"fee_id\": 20, \"method\": \"cash\", \"remarks\": \"kjlk\", \"created_at\": \"2025-12-06 10:46:16\", \"student_id\": 33, \"updated_at\": \"2025-12-06 10:46:16\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"asf\", \"academic_year_id\": 2, \"cash_payer_phone\": \"078657564\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 33, \"dob\": \"0000-00-00\", \"gender\": \"female\", \"address\": \"456 Oak Ave\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Amponsah\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"Afia\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0022\", \"medical_info\": null, \"guardian_name\": \"Mr. Adjei\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233229107205\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_boarding\", \"student_category_details\": null}, \"generated_at\": \"2025-12-06 10:46:16\", \"method_label\": \"Cash\", \"payment_date\": \"Dec 6, 2025\", \"method_details\": {\"payer_name\": \"asf\", \"payer_phone\": \"078657564\"}}', '2025-12-06 10:46:16', '2025-12-06 10:46:16');
INSERT INTO `receipts` VALUES ('63', '125', '{\"payment\": {\"id\": 125, \"date\": \"2025-12-06\", \"term\": \"1st Term\", \"amount\": \"100.00\", \"fee_id\": 18, \"method\": \"cash\", \"remarks\": \"kjlk\", \"created_at\": \"2025-12-06 10:46:16\", \"student_id\": 33, \"updated_at\": \"2025-12-06 10:46:16\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"asf\", \"academic_year_id\": 2, \"cash_payer_phone\": \"078657564\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 33, \"dob\": \"0000-00-00\", \"gender\": \"female\", \"address\": \"456 Oak Ave\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Amponsah\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"Afia\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0022\", \"medical_info\": null, \"guardian_name\": \"Mr. Adjei\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233229107205\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_boarding\", \"student_category_details\": null}, \"generated_at\": \"2025-12-06 10:46:16\", \"method_label\": \"Cash\", \"payment_date\": \"Dec 6, 2025\", \"method_details\": {\"payer_name\": \"asf\", \"payer_phone\": \"078657564\"}}', '2025-12-06 10:46:16', '2025-12-06 10:46:16');
INSERT INTO `receipts` VALUES ('64', '126', '{\"payment\": {\"id\": 126, \"date\": \"2025-12-06\", \"term\": \"1st Term\", \"amount\": \"200.00\", \"fee_id\": 19, \"method\": \"cash\", \"remarks\": \"kjlk\", \"created_at\": \"2025-12-06 10:46:16\", \"student_id\": 33, \"updated_at\": \"2025-12-06 10:46:16\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"asf\", \"academic_year_id\": 2, \"cash_payer_phone\": \"078657564\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 33, \"dob\": \"0000-00-00\", \"gender\": \"female\", \"address\": \"456 Oak Ave\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Amponsah\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"Afia\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0022\", \"medical_info\": null, \"guardian_name\": \"Mr. Adjei\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233229107205\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_boarding\", \"student_category_details\": null}, \"generated_at\": \"2025-12-06 10:46:16\", \"method_label\": \"Cash\", \"payment_date\": \"Dec 6, 2025\", \"method_details\": {\"payer_name\": \"asf\", \"payer_phone\": \"078657564\"}}', '2025-12-06 10:46:16', '2025-12-06 10:46:16');
INSERT INTO `receipts` VALUES ('65', '127', '{\"payment\": {\"id\": 127, \"date\": \"2025-12-06\", \"term\": \"1st Term\", \"amount\": \"100.00\", \"fee_id\": 20, \"method\": \"cash\", \"remarks\": \"gfcf\", \"created_at\": \"2025-12-06 10:52:47\", \"student_id\": 48, \"updated_at\": \"2025-12-06 10:52:47\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"da\", \"academic_year_id\": 2, \"cash_payer_phone\": \"07785645\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 48, \"dob\": \"0000-00-00\", \"gender\": \"female\", \"address\": \"456 Oak Ave\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Amponsah\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"Adwoa\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0037\", \"medical_info\": null, \"guardian_name\": \"Mr. Mensah\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233320679366\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_day\", \"student_category_details\": null}, \"generated_at\": \"2025-12-06 10:52:47\", \"method_label\": \"Cash\", \"payment_date\": \"Dec 6, 2025\", \"method_details\": {\"payer_name\": \"da\", \"payer_phone\": \"07785645\"}}', '2025-12-06 10:52:47', '2025-12-06 10:52:47');
INSERT INTO `receipts` VALUES ('66', '128', '{\"payment\": {\"id\": 128, \"date\": \"2025-12-06\", \"term\": \"1st Term\", \"amount\": \"50.00\", \"fee_id\": 19, \"method\": \"cash\", \"remarks\": \"gfcf\", \"created_at\": \"2025-12-06 10:52:47\", \"student_id\": 48, \"updated_at\": \"2025-12-06 10:52:47\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"da\", \"academic_year_id\": 2, \"cash_payer_phone\": \"07785645\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 48, \"dob\": \"0000-00-00\", \"gender\": \"female\", \"address\": \"456 Oak Ave\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Amponsah\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"Adwoa\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0037\", \"medical_info\": null, \"guardian_name\": \"Mr. Mensah\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233320679366\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_day\", \"student_category_details\": null}, \"generated_at\": \"2025-12-06 10:52:47\", \"method_label\": \"Cash\", \"payment_date\": \"Dec 6, 2025\", \"method_details\": {\"payer_name\": \"da\", \"payer_phone\": \"07785645\"}}', '2025-12-06 10:52:47', '2025-12-06 10:52:47');
INSERT INTO `receipts` VALUES ('67', '129', '{\"payment\": {\"id\": 129, \"date\": \"2025-12-06\", \"term\": \"1st Term\", \"amount\": \"100.00\", \"fee_id\": 20, \"method\": \"cash\", \"remarks\": \"gngbn\", \"created_at\": \"2025-12-06 11:27:18\", \"student_id\": 48, \"updated_at\": \"2025-12-06 11:27:18\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"sa\", \"academic_year_id\": 2, \"cash_payer_phone\": \"978867567\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 48, \"dob\": \"0000-00-00\", \"gender\": \"female\", \"address\": \"456 Oak Ave\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Amponsah\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"Adwoa\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0037\", \"medical_info\": null, \"guardian_name\": \"Mr. Mensah\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233320679366\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_day\", \"student_category_details\": null}, \"generated_at\": \"2025-12-06 11:27:18\", \"method_label\": \"Cash\", \"payment_date\": \"Dec 6, 2025\", \"method_details\": {\"payer_name\": \"sa\", \"payer_phone\": \"978867567\"}}', '2025-12-06 11:27:18', '2025-12-06 11:27:18');
INSERT INTO `receipts` VALUES ('68', '130', '{\"payment\": {\"id\": 130, \"date\": \"2025-12-06\", \"term\": \"1st Term\", \"amount\": \"100.00\", \"fee_id\": 19, \"method\": \"cash\", \"remarks\": \"gngbn\", \"created_at\": \"2025-12-06 11:27:18\", \"student_id\": 48, \"updated_at\": \"2025-12-06 11:27:18\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"sa\", \"academic_year_id\": 2, \"cash_payer_phone\": \"978867567\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 48, \"dob\": \"0000-00-00\", \"gender\": \"female\", \"address\": \"456 Oak Ave\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Amponsah\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"Adwoa\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0037\", \"medical_info\": null, \"guardian_name\": \"Mr. Mensah\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233320679366\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_day\", \"student_category_details\": null}, \"generated_at\": \"2025-12-06 11:27:18\", \"method_label\": \"Cash\", \"payment_date\": \"Dec 6, 2025\", \"method_details\": {\"payer_name\": \"sa\", \"payer_phone\": \"978867567\"}}', '2025-12-06 11:27:18', '2025-12-06 11:27:18');
INSERT INTO `receipts` VALUES ('69', '131', '{\"payment\": {\"id\": 131, \"date\": \"2025-12-06\", \"term\": \"1st Term\", \"amount\": \"100.00\", \"fee_id\": 20, \"method\": \"cash\", \"remarks\": \"jgh\", \"created_at\": \"2025-12-06 11:28:07\", \"student_id\": 33, \"updated_at\": \"2025-12-06 11:28:07\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"sasg\", \"academic_year_id\": 2, \"cash_payer_phone\": \"087867\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 33, \"dob\": \"0000-00-00\", \"gender\": \"female\", \"address\": \"456 Oak Ave\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Amponsah\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"Afia\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0022\", \"medical_info\": null, \"guardian_name\": \"Mr. Adjei\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233229107205\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_boarding\", \"student_category_details\": null}, \"generated_at\": \"2025-12-06 11:28:07\", \"method_label\": \"Cash\", \"payment_date\": \"Dec 6, 2025\", \"method_details\": {\"payer_name\": \"sasg\", \"payer_phone\": \"087867\"}}', '2025-12-06 11:28:07', '2025-12-06 11:28:07');
INSERT INTO `receipts` VALUES ('70', '132', '{\"payment\": {\"id\": 132, \"date\": \"2025-12-06\", \"term\": \"1st Term\", \"amount\": \"100.00\", \"fee_id\": 18, \"method\": \"cash\", \"remarks\": \"jgh\", \"created_at\": \"2025-12-06 11:28:07\", \"student_id\": 33, \"updated_at\": \"2025-12-06 11:28:07\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"sasg\", \"academic_year_id\": 2, \"cash_payer_phone\": \"087867\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 33, \"dob\": \"0000-00-00\", \"gender\": \"female\", \"address\": \"456 Oak Ave\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Amponsah\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"Afia\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0022\", \"medical_info\": null, \"guardian_name\": \"Mr. Adjei\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233229107205\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_boarding\", \"student_category_details\": null}, \"generated_at\": \"2025-12-06 11:28:07\", \"method_label\": \"Cash\", \"payment_date\": \"Dec 6, 2025\", \"method_details\": {\"payer_name\": \"sasg\", \"payer_phone\": \"087867\"}}', '2025-12-06 11:28:07', '2025-12-06 11:28:07');
INSERT INTO `receipts` VALUES ('71', '133', '{\"payment\": {\"id\": 133, \"date\": \"2025-12-06\", \"term\": \"1st Term\", \"amount\": \"100.00\", \"fee_id\": 19, \"method\": \"cash\", \"remarks\": \"jgh\", \"created_at\": \"2025-12-06 11:28:07\", \"student_id\": 33, \"updated_at\": \"2025-12-06 11:28:07\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"sasg\", \"academic_year_id\": 2, \"cash_payer_phone\": \"087867\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 33, \"dob\": \"0000-00-00\", \"gender\": \"female\", \"address\": \"456 Oak Ave\", \"user_id\": null, \"class_id\": 13, \"last_name\": \"Amponsah\", \"created_at\": \"2025-10-22 20:07:23\", \"first_name\": \"Afia\", \"updated_at\": \"2025-10-22 20:07:23\", \"admission_no\": \"STU0022\", \"medical_info\": null, \"guardian_name\": \"Mr. Adjei\", \"admission_date\": \"0000-00-00\", \"guardian_phone\": \"+233229107205\", \"profile_picture\": null, \"academic_year_id\": 1, \"student_category\": \"regular_boarding\", \"student_category_details\": null}, \"generated_at\": \"2025-12-06 11:28:07\", \"method_label\": \"Cash\", \"payment_date\": \"Dec 6, 2025\", \"method_details\": {\"payer_name\": \"sasg\", \"payer_phone\": \"087867\"}}', '2025-12-06 11:28:07', '2025-12-06 11:28:07');
INSERT INTO `receipts` VALUES ('72', '134', '{\"payment\": {\"id\": 134, \"date\": \"2025-12-06\", \"term\": \"1st Term\", \"amount\": \"100.00\", \"fee_id\": 20, \"method\": \"cash\", \"remarks\": \"dsf\", \"created_at\": \"2025-12-06 14:54:44\", \"student_id\": 127, \"updated_at\": \"2025-12-06 14:54:44\", \"cheque_bank\": null, \"cheque_number\": null, \"cheque_details\": null, \"transaction_id\": null, \"cash_payer_name\": \"sdaj\", \"academic_year_id\": 2, \"cash_payer_phone\": \"0868765\", \"bank_transfer_details\": null, \"mobile_money_reference\": null, \"mobile_money_sender_name\": null, \"bank_transfer_sender_bank\": null, \"mobile_money_sender_number\": null, \"bank_transfer_invoice_number\": null}, \"student\": {\"id\": 127, \"dob\": \"2003-02-13\", \"gender\": \"male\", \"address\": \"Rest viey172\", \"user_id\": null, \"class_id\": 14, \"last_name\": \" Dain\", \"created_at\": \"2025-12-06 13:50:08\", \"first_name\": \"Sammy\", \"updated_at\": \"2025-12-06 13:50:08\", \"admission_no\": \"EPI-134914\", \"medical_info\": \"\", \"guardian_name\": \"Mr Kamir\", \"admission_date\": \"2025-12-06\", \"guardian_phone\": \"03523223\", \"profile_picture\": \"student_1765029008_69343490af728.png\", \"academic_year_id\": 2, \"student_category\": \"regular_day\", \"student_category_details\": \"\"}, \"generated_at\": \"2025-12-06 14:54:44\", \"method_label\": \"Cash\", \"payment_date\": \"Dec 6, 2025\", \"method_details\": {\"payer_name\": \"sdaj\", \"payer_phone\": \"0868765\"}}', '2025-12-06 14:54:44', '2025-12-06 14:54:44');


-- Table structure for table `report_card_settings`
CREATE TABLE `report_card_settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `logo_position` enum('top-left','top-center','top-right','bottom-left','bottom-center','bottom-right') DEFAULT 'top-left',
  `show_school_name` tinyint(1) DEFAULT '1',
  `show_school_address` tinyint(1) DEFAULT '1',
  `custom_school_address` text,
  `show_school_logo` tinyint(1) DEFAULT '1',
  `show_student_photo` tinyint(1) DEFAULT '1',
  `show_grading_scale` tinyint(1) DEFAULT '1',
  `show_attendance` tinyint(1) DEFAULT '1',
  `show_comments` tinyint(1) DEFAULT '1',
  `show_signatures` tinyint(1) DEFAULT '1',
  `header_font_size` int DEFAULT '16',
  `body_font_size` int DEFAULT '12',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `show_class_score` tinyint(1) DEFAULT '1',
  `show_teacher_signature` tinyint(1) DEFAULT '1',
  `show_headteacher_signature` tinyint(1) DEFAULT '1',
  `show_parent_signature` tinyint(1) DEFAULT '1',
  `show_date_of_birth` tinyint(1) DEFAULT '1',
  `show_exam_score` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `report_card_settings`
INSERT INTO `report_card_settings` VALUES ('1', 'top-left', '1', '1', 'KASOA , OPEIKUMAH ROAD', '1', '0', '0', '1', '1', '1', '16', '12', '2025-10-20 02:25:59', '2025-11-15 22:44:49', '0', '1', '1', '1', '0', '0');


-- Table structure for table `role_permissions`
CREATE TABLE `role_permissions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `role_id` int NOT NULL,
  `permission_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_role_permission` (`role_id`,`permission_id`),
  KEY `permission_id` (`permission_id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `role_permissions`
INSERT INTO `role_permissions` VALUES ('1', '1', '1', '2025-10-12 02:58:57');
INSERT INTO `role_permissions` VALUES ('2', '1', '2', '2025-10-12 02:58:57');
INSERT INTO `role_permissions` VALUES ('3', '1', '3', '2025-10-12 02:58:57');
INSERT INTO `role_permissions` VALUES ('4', '1', '4', '2025-10-12 02:58:57');
INSERT INTO `role_permissions` VALUES ('5', '1', '5', '2025-10-12 02:58:57');
INSERT INTO `role_permissions` VALUES ('6', '1', '6', '2025-10-12 02:58:57');
INSERT INTO `role_permissions` VALUES ('7', '1', '7', '2025-10-12 02:58:57');
INSERT INTO `role_permissions` VALUES ('8', '1', '8', '2025-10-12 02:58:57');
INSERT INTO `role_permissions` VALUES ('9', '1', '9', '2025-10-12 02:58:57');
INSERT INTO `role_permissions` VALUES ('10', '1', '10', '2025-10-12 02:58:57');
INSERT INTO `role_permissions` VALUES ('11', '1', '11', '2025-10-12 02:58:57');
INSERT INTO `role_permissions` VALUES ('12', '1', '12', '2025-10-12 02:58:57');
INSERT INTO `role_permissions` VALUES ('13', '1', '13', '2025-10-12 02:58:57');
INSERT INTO `role_permissions` VALUES ('14', '1', '14', '2025-10-12 02:58:57');
INSERT INTO `role_permissions` VALUES ('15', '1', '15', '2025-10-12 02:58:57');
INSERT INTO `role_permissions` VALUES ('16', '1', '16', '2025-10-12 02:58:57');
INSERT INTO `role_permissions` VALUES ('17', '1', '17', '2025-10-12 02:58:57');
INSERT INTO `role_permissions` VALUES ('18', '1', '18', '2025-10-12 02:58:57');
INSERT INTO `role_permissions` VALUES ('19', '1', '19', '2025-10-12 02:58:57');
INSERT INTO `role_permissions` VALUES ('20', '1', '20', '2025-10-12 02:58:57');
INSERT INTO `role_permissions` VALUES ('21', '1', '21', '2025-10-12 02:58:57');
INSERT INTO `role_permissions` VALUES ('22', '1', '22', '2025-10-12 02:58:57');
INSERT INTO `role_permissions` VALUES ('23', '1', '23', '2025-10-12 02:58:57');


-- Table structure for table `roles`
CREATE TABLE `roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `roles`
INSERT INTO `roles` VALUES ('1', 'admin', 'Administrator with full access', '2025-10-12 01:58:11');
INSERT INTO `roles` VALUES ('2', 'user', 'Regular user with limited access', '2025-10-12 01:58:11');
INSERT INTO `roles` VALUES ('3', 'super_admin', 'Super Administrator with ultimate access', '2025-10-13 21:58:41');
INSERT INTO `roles` VALUES ('4', 'accountant', 'Financial Accountant with access to financial records and reports', '2025-10-13 22:58:07');
INSERT INTO `roles` VALUES ('5', 'teacher', 'Teacher with access to student records, grades, and class management', '2025-10-13 22:58:07');


-- Table structure for table `settings`
CREATE TABLE `settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `school_name` varchar(255) NOT NULL DEFAULT 'Futuristic School',
  `school_address` text,
  `school_logo` varchar(255) DEFAULT NULL,
  `currency_code` varchar(3) NOT NULL DEFAULT 'GHS',
  `currency_symbol` varchar(10) NOT NULL DEFAULT 'GH₵',
  `watermark_type` enum('none','logo','name','both') NOT NULL DEFAULT 'none',
  `watermark_position` enum('top-left','top-center','top-right','middle-left','center','middle-right','bottom-left','bottom-center','bottom-right') NOT NULL DEFAULT 'center',
  `watermark_transparency` tinyint NOT NULL DEFAULT '20',
  `student_admission_prefix` varchar(10) DEFAULT 'EPI',
  `staff_employee_prefix` varchar(10) DEFAULT 'StID',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `settings`
INSERT INTO `settings` VALUES ('1', 'ONUA FRANCIS SCHOOL', '', '/storage/uploads/logo_1761789988_pngtree-school-logo-design-template-png-image_9104626.png', 'GHS', 'GH₵', 'none', 'center', '20', 'EPI', 'StID', '2025-10-13 21:44:26', '2025-11-19 04:26:05');


-- Table structure for table `staff`
CREATE TABLE `staff` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `employee_id` varchar(20) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `position` varchar(100) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `subject_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_id` (`employee_id`),
  KEY `user_id` (`user_id`),
  KEY `fk_staff_subject` (`subject_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `staff`
INSERT INTO `staff` VALUES ('1', NULL, 'EMP001', 'David', 'Wilson', 'Principal', 'Administration', 'david.wilson@school.edu', '+1234567893', '2015-09-01', '75000.00', 'active', NULL, '2025-10-12 02:11:02', '2025-10-12 02:11:02');
INSERT INTO `staff` VALUES ('2', NULL, 'EMP002', 'Sarah', 'Johnson', 'Vice Principal', 'Administration', 'sarah.johnson@school.edu', '+1234567894', '2018-09-01', '65000.00', 'active', NULL, '2025-10-12 02:11:02', '2025-10-12 02:11:02');
INSERT INTO `staff` VALUES ('3', NULL, 'EMP003', 'Michael', 'Brown', 'Math Teacher', 'Academics', 'michael.brown@school.edu', '+1234567895', '2020-09-01', '55000.00', 'active', NULL, '2025-10-12 02:11:02', '2025-10-12 02:11:02');
INSERT INTO `staff` VALUES ('4', NULL, 'EMP006', 'Michael', 'Aniin', 'Teaching', 'Academics', 'michael.brown@school.edu', '+1234567895', '2020-09-01', '55000.00', 'active', NULL, '2025-10-13 23:53:17', '2025-10-13 23:53:17');
INSERT INTO `staff` VALUES ('5', NULL, 'EMP005', 'Ofei', 'Aniin', 'Teacher', 'Academics', 'michael.brown@school.edu', '+1234567895', '2020-09-01', '55000.00', 'active', NULL, '2025-10-14 00:24:04', '2025-10-14 00:24:04');
INSERT INTO `staff` VALUES ('6', NULL, 'StID-221442', 'Samuel', 'K Jnr', 'Teacher', 'Mathematics', 'SDASDSDA@gmail.com', '5657575', '2025-10-17', '5100.00', 'active', '0', '2025-10-19 22:17:25', '2025-10-19 22:17:25');
INSERT INTO `staff` VALUES ('7', NULL, 'StID-225006', 'Jayson', 'Mark', 'Teacher', '', 'jsjjsjsjs@gmail.com', '2244141', '0000-00-00', '3000.00', 'active', NULL, '2025-10-19 22:51:04', '2025-10-19 22:51:04');


-- Table structure for table `staff_subjects`
CREATE TABLE `staff_subjects` (
  `id` int NOT NULL AUTO_INCREMENT,
  `staff_id` int NOT NULL,
  `subject_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_staff_subject` (`staff_id`,`subject_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `staff_subjects`
INSERT INTO `staff_subjects` VALUES ('1', '7', '2', '2025-10-19 22:51:04', '2025-10-19 22:51:04');
INSERT INTO `staff_subjects` VALUES ('2', '7', '3', '2025-10-19 22:51:04', '2025-10-19 22:51:04');
INSERT INTO `staff_subjects` VALUES ('3', '6', '6', '2025-10-22 12:08:52', '2025-10-22 12:08:52');
INSERT INTO `staff_subjects` VALUES ('4', '6', '7', '2025-10-24 11:58:12', '2025-10-24 11:58:12');
INSERT INTO `staff_subjects` VALUES ('5', '3', '8', '2025-10-24 12:17:50', '2025-10-24 12:17:50');
INSERT INTO `staff_subjects` VALUES ('6', '3', '9', '2025-10-24 12:18:53', '2025-10-24 12:18:53');
INSERT INTO `staff_subjects` VALUES ('7', '7', '10', '2025-10-24 12:19:44', '2025-10-24 12:19:44');
INSERT INTO `staff_subjects` VALUES ('8', '7', '11', '2025-10-24 12:20:24', '2025-10-24 12:20:24');
INSERT INTO `staff_subjects` VALUES ('9', '5', '12', '2025-10-24 12:20:55', '2025-10-24 12:20:55');
INSERT INTO `staff_subjects` VALUES ('10', '5', '13', '2025-10-24 12:21:48', '2025-10-24 12:21:48');
INSERT INTO `staff_subjects` VALUES ('11', '3', '14', '2025-10-29 21:12:20', '2025-10-29 21:12:20');
INSERT INTO `staff_subjects` VALUES ('12', '7', '15', '2025-10-29 21:13:14', '2025-10-29 21:13:14');
INSERT INTO `staff_subjects` VALUES ('13', '7', '16', '2025-10-29 21:14:37', '2025-10-29 21:14:37');
INSERT INTO `staff_subjects` VALUES ('14', '5', '17', '2025-10-29 21:15:39', '2025-10-29 21:15:39');
INSERT INTO `staff_subjects` VALUES ('15', '5', '18', '2025-10-29 23:26:52', '2025-10-29 23:26:52');
INSERT INTO `staff_subjects` VALUES ('16', '3', '19', '2025-10-29 23:27:19', '2025-10-29 23:27:19');
INSERT INTO `staff_subjects` VALUES ('17', '7', '20', '2025-10-29 23:28:21', '2025-10-29 23:28:21');


-- Table structure for table `students`
CREATE TABLE `students` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `admission_no` varchar(20) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `dob` date DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `class_id` int DEFAULT NULL,
  `guardian_name` varchar(100) DEFAULT NULL,
  `guardian_phone` varchar(20) DEFAULT NULL,
  `address` text,
  `medical_info` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `profile_picture` varchar(255) DEFAULT NULL,
  `admission_date` date DEFAULT NULL,
  `academic_year_id` int DEFAULT NULL,
  `student_category` enum('regular_day','regular_boarding','international') NOT NULL DEFAULT 'regular_day',
  `student_category_details` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admission_no` (`admission_no`),
  KEY `user_id` (`user_id`),
  KEY `fk_students_academic_year` (`academic_year_id`)
) ENGINE=MyISAM AUTO_INCREMENT=128 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `students`
INSERT INTO `students` VALUES ('1', NULL, 'EPI-082848', 'John', 'Doe', '1999-12-31', 'male', '1', 'Robert Doe', '+1234567890', '123 Main St, City', '', '2025-10-12 02:04:36', '2025-10-30 12:38:13', NULL, '2021-09-01', '1', 'regular_day', '');
INSERT INTO `students` VALUES ('2', NULL, 'STU002', 'Jane', 'Smith', '2011-08-22', 'female', '2', 'Mary Smith', '+1234567891', '456 Oak Ave, City', NULL, '2025-10-12 02:04:36', '2025-10-12 02:04:36', NULL, NULL, NULL, 'regular_day', NULL);
INSERT INTO `students` VALUES ('3', NULL, 'STU003', 'Michael', 'Johnson', '2009-12-10', 'male', '3', 'David Johnson', '+1234567892', '789 Pine Rd, City', NULL, '2025-10-12 02:04:36', '2025-10-12 02:04:36', NULL, NULL, NULL, 'regular_day', NULL);
INSERT INTO `students` VALUES ('4', NULL, 'BED/151023', 'sam', 'sam', '0000-00-00', 'male', '13', 'YaYa', '5657575', 'opeikumah', NULL, '2025-10-14 00:27:24', '2025-10-17 05:26:47', 'student_1760678807_68f1d39710eac.webp', NULL, NULL, 'regular_day', NULL);
INSERT INTO `students` VALUES ('5', NULL, '151023', 'Afful', 'Br', '2010-06-15', 'male', '13', 'YaYa', '5657575', 'opeikumah', NULL, '2025-10-14 00:50:09', '2025-10-14 19:29:47', 'student_1760470187_68eea4abd370a.jpeg', NULL, NULL, 'regular_day', NULL);
INSERT INTO `students` VALUES ('6', NULL, '15102378', 'Akwesi', 'Brown', '2010-06-15', 'female', '13', 'YaYa', '5657575', '', NULL, '2025-10-14 19:30:45', '2025-10-14 20:11:05', 'student_1760472665_68eeae593c922.jpeg', NULL, NULL, 'regular_day', NULL);
INSERT INTO `students` VALUES ('7', NULL, '15102378s4', 'IKe', 'Brown Edem', '2010-06-15', 'male', '13', 'YaYa', '5657575', '', NULL, '2025-10-14 19:41:44', '2025-10-14 20:16:01', 'student_1760472961_68eeaf81259bb.jpeg', NULL, NULL, 'regular_day', NULL);
INSERT INTO `students` VALUES ('8', NULL, '15102378s4r', 'mina', 'Brown Edem', '2010-06-15', 'male', '13', 'YaYa', '5657575', '', NULL, '2025-10-14 20:16:56', '2025-10-17 10:22:51', 'student_1760473084_68eeaffc22f98.jpeg', '2025-10-16', '2', 'regular_day', '');
INSERT INTO `students` VALUES ('10', NULL, 'EPI-105526', 'Kwame', 'Mintah', '2011-02-17', 'male', '13', 'Mr Mintah', '5657575', 'opeikumah', NULL, '2025-10-17 10:57:10', '2025-10-17 10:57:10', NULL, '2025-10-17', '2', 'regular_day', '');
INSERT INTO `students` VALUES ('11', NULL, 'EPI-232937', 'Effum', 'Odoum', '2015-02-03', 'male', '3', 'Robert Doe', '909458096', '', NULL, '2025-10-19 23:32:51', '2025-10-19 23:33:45', 'student_1760916771_68f575233c0c5.jpg', '2025-10-19', '2', 'regular_boarding', '');
INSERT INTO `students` VALUES ('12', NULL, 'STU0001', 'Kofi', 'Boadu', '0000-00-00', 'male', '1', 'Mr. Appiah', '+233303624556', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('13', NULL, 'STU0002', 'Kwame', 'Amponsah', '0000-00-00', 'male', '2', 'Mrs. Asante', '+233328955300', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('14', NULL, 'STU0003', 'Ama', 'Appiah', '0000-00-00', 'female', '3', 'Mrs. Asante', '+233435102664', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('15', NULL, 'STU0004', 'Mary', 'Asante', '0000-00-00', 'female', '4', 'Mr. Mensah', '+233587285374', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('16', NULL, 'STU0005', 'Emmanuel', 'Boateng', '0000-00-00', 'male', '5', 'Mrs. Asante', '+233574570050', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('17', NULL, 'STU0006', 'Evelyn', 'Amponsah', '0000-00-00', 'female', '6', 'Mrs. Asante', '+233345302212', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('18', NULL, 'STU0007', 'Kojo', 'Adjei', '0000-00-00', 'male', '7', 'Mrs. Ofori', '+233480473078', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('19', NULL, 'STU0008', 'Adwoa', 'Ofori', '0000-00-00', 'female', '8', 'Mr. Appiah', '+233387982081', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('20', NULL, 'STU0009', 'John', 'Nyarko', '0000-00-00', 'male', '9', 'Mr. Mensah', '+233253858601', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('21', NULL, 'STU0010', 'Kofi', 'Appiah', '0000-00-00', 'male', '1', 'Mrs. Owusu', '+233315722591', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('22', NULL, 'STU0011', 'Evelyn', 'Nyarko', '0000-00-00', 'female', '2', 'Mrs. Nyarko', '+233265031380', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('23', NULL, 'STU0012', 'Evelyn', 'Nyarko', '0000-00-00', 'female', '3', 'Mr. Appiah', '+233501927075', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('24', NULL, 'STU0013', 'Yaw', 'Appiah', '0000-00-00', 'male', '4', 'Mrs. Asante', '+233363085117', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('25', NULL, 'STU0014', 'Akua', 'Adjei', '2044-06-03', 'female', '5', 'Mrs. Ofori', '+233391513711', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('26', NULL, 'STU0015', 'Akosua', 'Owusu', '0000-00-00', 'female', '6', 'Mr. Appiah', '+233257090446', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('27', NULL, 'STU0016', 'Akua', 'Ofori', '0000-00-00', 'female', '7', 'Mr. Mensah', '+233376215894', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('28', NULL, 'STU0017', 'Grace', 'Boadu', '0000-00-00', 'female', '8', 'Mr. Boadu', '+233557201260', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('29', NULL, 'STU0018', 'Kojo', 'Ofori', '0000-00-00', 'male', '9', 'Mrs. Nyarko', '+233338994274', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('30', NULL, 'STU0019', 'Kweku', 'Asante', '0000-00-00', 'male', '10', 'Mr. Boadu', '+233546833793', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('31', NULL, 'STU0020', 'Esi', 'Appiah', '0000-00-00', 'female', '11', 'Mrs. Nyarko', '+233352322355', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('32', NULL, 'STU0021', 'Abena', 'Boadu', '0000-00-00', 'female', '12', 'Mr. Boadu', '+233208544340', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('33', NULL, 'STU0022', 'Afia', 'Amponsah', '0000-00-00', 'female', '13', 'Mr. Adjei', '+233229107205', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('34', NULL, 'STU0023', 'John', 'Boadu', '0000-00-00', 'male', '13', 'Mrs. Amponsah', '+233384220631', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('35', NULL, 'STU0024', 'Daniel', 'Mensah', '0000-00-00', 'male', '1', 'Mr. Boadu', '+233399768257', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('36', NULL, 'STU0025', 'Akosua', 'Boateng', '0000-00-00', 'female', '2', 'Mr. Boateng', '+233228359635', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('37', NULL, 'STU0026', 'John', 'Amponsah', '0000-00-00', 'male', '3', 'Mr. Boateng', '+233494006962', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('38', NULL, 'STU0027', 'Ama', 'Adjei', '0000-00-00', 'female', '4', 'Mr. Appiah', '+233485203524', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('39', NULL, 'STU0028', 'Michael', 'Boadu', '0000-00-00', 'male', '5', 'Mrs. Ofori', '+233388349719', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('40', NULL, 'STU0029', 'Akosua', 'Boadu', '0000-00-00', 'female', '6', 'Mrs. Owusu', '+233522760488', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('41', NULL, 'STU0030', 'Kwame', 'Amponsah', '0000-00-00', 'male', '7', 'Mrs. Asante', '+233408641985', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('42', NULL, 'STU0031', 'Michael', 'Adjei', '0000-00-00', 'male', '8', 'Mrs. Asante', '+233482006389', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('43', NULL, 'STU0032', 'Adwoa', 'Boadu', '0000-00-00', 'female', '9', 'Mrs. Amponsah', '+233588256776', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('44', NULL, 'STU0033', 'Akua', 'Nyarko', '2044-06-03', 'female', '10', 'Mrs. Ofori', '+233201620139', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('45', NULL, 'STU0034', 'Akosua', 'Amponsah', '0000-00-00', 'female', '11', 'Mr. Adjei', '+233347459315', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('46', NULL, 'STU0035', 'Yaw', 'Adjei', '0000-00-00', 'male', '12', 'Mrs. Amponsah', '+233285220207', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('47', NULL, 'STU0036', 'Afia', 'Mensah', '0000-00-00', 'female', '13', 'Mrs. Nyarko', '+233216095183', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('48', NULL, 'STU0037', 'Adwoa', 'Amponsah', '0000-00-00', 'female', '13', 'Mr. Mensah', '+233320679366', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('49', NULL, 'STU0038', 'Afia', 'Boateng', '0000-00-00', 'female', '1', 'Mr. Adjei', '+233414638464', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('50', NULL, 'STU0039', 'Ama', 'Amponsah', '0000-00-00', 'female', '2', 'Mr. Boadu', '+233222302778', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('51', NULL, 'STU0040', 'Akosua', 'Ofori', '0000-00-00', 'female', '3', 'Mrs. Owusu', '+233305750143', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('52', NULL, 'STU0041', 'Esi', 'Mensah', '0000-00-00', 'female', '4', 'Mrs. Ofori', '+233538210855', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('53', NULL, 'STU0042', 'Michael', 'Nyarko', '0000-00-00', 'male', '5', 'Mrs. Amponsah', '+233245727193', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('54', NULL, 'STU0043', 'Kofi', 'Boateng', '0000-00-00', 'male', '6', 'Mr. Boateng', '+233508967092', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('55', NULL, 'STU0044', 'Evelyn', 'Asante', '0000-00-00', 'female', '7', 'Mr. Boadu', '+233330196419', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('56', NULL, 'STU0045', 'Afia', 'Boateng', '0000-00-00', 'female', '8', 'Mrs. Owusu', '+233392088186', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('57', NULL, 'STU0046', 'Akua', 'Ofori', '0000-00-00', 'female', '9', 'Mr. Adjei', '+233539429780', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('58', NULL, 'STU0047', 'Kweku', 'Mensah', '0000-00-00', 'male', '10', 'Mrs. Ofori', '+233528029400', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('59', NULL, 'STU0048', 'Ama', 'Adjei', '0000-00-00', 'female', '11', 'Mr. Boadu', '+233355840903', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('60', NULL, 'STU0049', 'Akua', 'Asante', '0000-00-00', 'female', '12', 'Mrs. Nyarko', '+233269186706', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('61', NULL, 'STU0050', 'Kweku', 'Asante', '0000-00-00', 'male', '13', 'Mr. Mensah', '+233461872271', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('62', NULL, 'STU0051', 'Abena', 'Owusu', '0000-00-00', 'female', '13', 'Mrs. Asante', '+233350006730', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('63', NULL, 'STU0052', 'Kojo', 'Adjei', '2044-06-03', 'male', '1', 'Mr. Adjei', '+233506672969', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('64', NULL, 'STU0053', 'Ama', 'Ofori', '0000-00-00', 'female', '2', 'Mrs. Amponsah', '+233211780121', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('65', NULL, 'STU0054', 'Kweku', 'Amponsah', '0000-00-00', 'male', '3', 'Mr. Appiah', '+233538900722', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('66', NULL, 'STU0055', 'Ama', 'Appiah', '0000-00-00', 'female', '4', 'Mrs. Ofori', '+233392903549', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('67', NULL, 'STU0056', 'Grace', 'Adjei', '0000-00-00', 'female', '5', 'Mr. Mensah', '+233572243396', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('68', NULL, 'STU0057', 'Abena', 'Ofori', '0000-00-00', 'female', '6', 'Mr. Boateng', '+233360931606', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('69', NULL, 'STU0058', 'Kwabena', 'Mensah', '0000-00-00', 'male', '7', 'Mr. Mensah', '+233468389589', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('70', NULL, 'STU0059', 'Mary', 'Appiah', '0000-00-00', 'female', '8', 'Mrs. Amponsah', '+233548527452', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('71', NULL, 'STU0060', 'Esi', 'Amponsah', '0000-00-00', 'female', '9', 'Mrs. Ofori', '+233234325832', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('72', NULL, 'STU0061', 'Kojo', 'Boateng', '0000-00-00', 'male', '10', 'Mr. Appiah', '+233234999460', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('73', NULL, 'STU0062', 'Akua', 'Owusu', '0000-00-00', 'female', '11', 'Mr. Boadu', '+233315672913', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('74', NULL, 'STU0063', 'Esi', 'Amponsah', '0000-00-00', 'female', '12', 'Mr. Mensah', '+233243469176', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('75', NULL, 'STU0064', 'Akua', 'Appiah', '0000-00-00', 'female', '13', 'Mr. Adjei', '+233519840520', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('76', NULL, 'STU0065', 'Kofi', 'Adjei', '0000-00-00', 'male', '13', 'Mr. Boateng', '+233588406574', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('77', NULL, 'STU0066', 'Michael', 'Adjei', '0000-00-00', 'male', '1', 'Mrs. Amponsah', '+233490951435', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('78', NULL, 'STU0067', 'Kofi', 'Asante', '0000-00-00', 'male', '2', 'Mrs. Ofori', '+233380194100', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('79', NULL, 'STU0068', 'Ama', 'Asante', '0000-00-00', 'female', '3', 'Mrs. Asante', '+233530610701', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('80', NULL, 'STU0069', 'Yaw', 'Amponsah', '0000-00-00', 'male', '4', 'Mr. Appiah', '+233455507157', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('81', NULL, 'STU0070', 'Kweku', 'Boateng', '0000-00-00', 'male', '5', 'Mrs. Nyarko', '+233443672293', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('82', NULL, 'STU0071', 'Kofi', 'Amponsah', '2044-06-03', 'male', '6', 'Mr. Boadu', '+233223410588', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('83', NULL, 'STU0072', 'Kwame', 'Appiah', '0000-00-00', 'male', '7', 'Mrs. Nyarko', '+233594764097', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('84', NULL, 'STU0073', 'Akua', 'Appiah', '0000-00-00', 'female', '8', 'Mrs. Ofori', '+233370458325', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('85', NULL, 'STU0074', 'John', 'Adjei', '0000-00-00', 'male', '9', 'Mr. Appiah', '+233251400134', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('86', NULL, 'STU0075', 'Emmanuel', 'Mensah', '0000-00-00', 'male', '10', 'Mrs. Asante', '+233211097745', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('87', NULL, 'STU0076', 'John', 'Mensah', '0000-00-00', 'male', '11', 'Mr. Boateng', '+233385970669', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('88', NULL, 'STU0077', 'Yaw', 'Ofori', '0000-00-00', 'male', '12', 'Mr. Boadu', '+233523988889', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('89', NULL, 'STU0078', 'Yaw', 'Adjei', '0000-00-00', 'male', '13', 'Mr. Boadu', '+233352593474', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('90', NULL, 'STU0079', 'Michael', 'Boateng', '0000-00-00', 'male', '13', 'Mr. Appiah', '+233396304121', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('91', NULL, 'STU0080', 'Adwoa', 'Boateng', '0000-00-00', 'female', '1', 'Mr. Appiah', '+233450885403', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('92', NULL, 'STU0081', 'Yaw', 'Boadu', '0000-00-00', 'male', '2', 'Mr. Boateng', '+233503301550', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('93', NULL, 'STU0082', 'Esi', 'Mensah', '0000-00-00', 'female', '3', 'Mr. Appiah', '+233433005281', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('94', NULL, 'STU0083', 'Adwoa', 'Adjei', '0000-00-00', 'female', '4', 'Mr. Appiah', '+233439511638', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('95', NULL, 'STU0084', 'Daniel', 'Nyarko', '0000-00-00', 'male', '5', 'Mr. Adjei', '+233379247896', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('96', NULL, 'STU0085', 'Kweku', 'Boadu', '0000-00-00', 'male', '6', 'Mr. Boateng', '+233201465292', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('97', NULL, 'STU0086', 'Yaw', 'Asante', '0000-00-00', 'male', '7', 'Mr. Appiah', '+233394237964', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('98', NULL, 'STU0087', 'Kweku', 'Owusu', '0000-00-00', 'male', '8', 'Mrs. Amponsah', '+233527355860', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('99', NULL, 'STU0088', 'Akua', 'Appiah', '0000-00-00', 'female', '9', 'Mrs. Owusu', '+233580525990', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('100', NULL, 'STU0089', 'Kojo', 'Mensah', '0000-00-00', 'male', '10', 'Mr. Appiah', '+233246708892', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('101', NULL, 'STU0090', 'Emmanuel', 'Appiah', '2044-06-03', 'male', '11', 'Mrs. Asante', '+233527540903', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('102', NULL, 'STU0091', 'Michael', 'Adjei', '0000-00-00', 'male', '12', 'Mrs. Nyarko', '+233450287066', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('103', NULL, 'STU0092', 'Evelyn', 'Asante', '0000-00-00', 'female', '13', 'Mr. Boadu', '+233534017576', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('104', NULL, 'STU0093', 'Yaw', 'Boateng', '0000-00-00', 'male', '13', 'Mrs. Asante', '+233256748764', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('105', NULL, 'STU0094', 'Grace', 'Mensah', '0000-00-00', 'female', '1', 'Mr. Mensah', '+233347600861', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('106', NULL, 'STU0095', 'Kojo', 'Boateng', '0000-00-00', 'male', '2', 'Mrs. Nyarko', '+233353168956', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('107', NULL, 'STU0096', 'Akosua', 'Ofori', '0000-00-00', 'female', '3', 'Mrs. Owusu', '+233371701865', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('108', NULL, 'STU0097', 'Kwame', 'Adjei', '0000-00-00', 'male', '4', 'Mrs. Amponsah', '+233258684014', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('109', NULL, 'STU0098', 'Grace', 'Nyarko', '0000-00-00', 'female', '5', 'Mr. Adjei', '+233435715146', '123 Main St', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('110', NULL, 'STU0099', 'Kwabena', 'Asante', '0000-00-00', 'male', '6', 'Mr. Mensah', '+233214299011', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_day', NULL);
INSERT INTO `students` VALUES ('111', NULL, 'STU0100', 'Abena', 'Boadu', '0000-00-00', 'female', '7', 'Mr. Boadu', '+233336569537', '456 Oak Ave', NULL, '2025-10-22 20:07:23', '2025-10-22 20:07:23', NULL, '0000-00-00', '1', 'regular_boarding', NULL);
INSERT INTO `students` VALUES ('112', NULL, 'EPI-121118', 'Micheal', 'Woani', '2007-11-23', '', '14', 'Mr. Appiah', '+233501927075', '234/9kk accra', '', '2025-10-24 12:12:37', '2025-10-24 12:12:37', NULL, '2025-10-24', '2', 'regular_boarding', '');
INSERT INTO `students` VALUES ('113', NULL, 'EPI-121340', 'Kwame ', 'addie', '2009-07-08', 'male', '14', 'Mr Mintah', '0505708146', '', '', '2025-10-24 12:14:51', '2025-10-24 12:14:51', NULL, '2025-10-24', '2', 'regular_day', '');
INSERT INTO `students` VALUES ('114', NULL, 'EPI-121521', 'Effia', 'addie', '2013-02-27', 'female', '14', '', '0505708146', 'Rest viey172', 'ADHD', '2025-10-24 12:16:36', '2025-10-24 12:16:36', NULL, '2025-10-24', '2', 'regular_day', '');
INSERT INTO `students` VALUES ('115', NULL, 'EPI-223546', 'Alred', 'Koti', '2010-10-29', 'male', '15', 'Missa aban Koti', '+233352322355', '', '', '2025-10-29 22:36:47', '2025-10-29 22:36:47', 'student_1761777407_690296ffbf1b6.jpg', '2025-10-29', '2', 'regular_day', '');
INSERT INTO `students` VALUES ('116', NULL, 'EPI-223701', 'Nyiram', 'Bantam', '2016-10-29', 'female', '15', 'Mr Bantam', '0352322355', '', '', '2025-10-29 22:38:04', '2025-10-29 22:38:04', 'student_1761777484_6902974c8f49d.jpeg', '2025-10-29', '2', 'regular_day', '');
INSERT INTO `students` VALUES ('117', NULL, 'EPI-223852', 'Amu', 'Waton', '2014-02-28', 'male', '15', 'maton bubdu', '0505708146', 'Rest viey172', '', '2025-10-29 22:40:04', '2025-10-29 22:40:04', 'student_1761777604_690297c49fb77.jpg', '2025-10-29', '2', 'regular_day', '');
INSERT INTO `students` VALUES ('118', NULL, 'EPI-225843', 'YAYA', 'KK', '2025-10-16', 'male', '15', 'YaYa', '03523223', '234/9kk accra', '', '2025-10-29 22:59:19', '2025-11-22 09:42:09', NULL, '2025-10-29', '2', 'regular_day', '');
INSERT INTO `students` VALUES ('119', NULL, 'EPI-232252', 'josh', 'amet', '2008-02-29', '', '16', 'YaYa', '03523223', '', '', '2025-10-29 23:23:26', '2025-10-29 23:23:26', NULL, '2025-10-29', '2', 'regular_day', '');
INSERT INTO `students` VALUES ('120', NULL, 'EPI-121153', 'Efiram', 'Amu', '2011-07-30', '', '16', 'Mr Kamir', '09876543', '', '', '2025-10-30 12:13:22', '2025-10-30 12:13:22', NULL, '2025-10-30', '2', 'regular_boarding', '');
INSERT INTO `students` VALUES ('121', NULL, 'EPI-132442', 'Offin', 'Berima', '2011-07-04', 'male', '14', 'Mrs. Nyarko Berima', '03523223', '234/9kk accra', '', '2025-11-04 13:25:14', '2025-11-04 13:25:14', NULL, '2025-11-04', '2', 'regular_day', '');
INSERT INTO `students` VALUES ('122', NULL, 'EPI-005831', 'Emefa', 'Vida kk', '2010-02-02', 'male', '1', 'SAVIOLA', '0987651112', '', '', '2025-11-13 00:59:16', '2025-11-22 09:37:51', NULL, '2025-11-13', '2', 'regular_day', '');
INSERT INTO `students` VALUES ('127', NULL, 'EPI-134914', 'Sammy', ' Dain', '2003-02-13', 'male', '14', 'Mr Kamir', '03523223', 'Rest viey172', '', '2025-12-06 13:50:08', '2025-12-06 13:50:08', 'student_1765029008_69343490af728.png', '2025-12-06', '2', 'regular_day', '');
INSERT INTO `students` VALUES ('126', NULL, 'EPI-014648', 'AKUBI', 'ANNA', '2006-10-11', 'female', '16', 'Mrs. Nyarko', '03523223', '234/9kk accra', '', '2025-12-05 01:47:42', '2025-12-05 01:48:29', 'student_1764899262_693239be10323.png', '2025-12-05', '2', 'regular_day', '');
INSERT INTO `students` VALUES ('125', NULL, 'EPI-162949', 'Albert', 'Eisntin', '2005-11-16', 'male', '15', 'Mr science', '08976867857', 'SK 234kaSOA', 'Alergic to peanut butter', '2025-12-04 16:30:07', '2025-12-04 16:30:07', 'student_1764865807_6931b70f153bf.png', '2025-12-04', '2', 'regular_day', '');


-- Table structure for table `subjects`
CREATE TABLE `subjects` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `code` varchar(20) NOT NULL,
  `class_id` int DEFAULT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `class_id` (`class_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `subjects`
INSERT INTO `subjects` VALUES ('1', 'Mathematics', 'MATH101', '1', 'Basic mathematics concepts for Grade 1 students', '2025-10-12 02:13:54', '2025-10-12 02:13:54');
INSERT INTO `subjects` VALUES ('2', 'English Language', 'ENG101', '1', 'English language fundamentals for Grade 1 students', '2025-10-12 02:13:54', '2025-10-12 02:13:54');
INSERT INTO `subjects` VALUES ('3', 'Science', 'SCI101', '1', 'Introduction to science for Grade 1 students', '2025-10-12 02:13:54', '2025-10-12 02:13:54');
INSERT INTO `subjects` VALUES ('4', 'Social Studies', 'SOC101', '1', 'Social studies curriculum for Grade 1 students', '2025-10-12 02:13:54', '2025-10-12 02:13:54');
INSERT INTO `subjects` VALUES ('5', 'RME', 'RMEJHS', '13', '', '2025-10-18 13:09:54', '2025-10-18 13:09:54');
INSERT INTO `subjects` VALUES ('6', 'ict', 'bc1', '13', '', '2025-10-22 12:08:52', '2025-10-22 12:08:52');
INSERT INTO `subjects` VALUES ('7', 'Basic Design Technology', 'BDTJ2', '14', '', '2025-10-24 11:58:12', '2025-10-24 11:58:12');
INSERT INTO `subjects` VALUES ('8', 'Pre-Tech', 'PTjh2', '14', '', '2025-10-24 12:17:50', '2025-10-24 12:17:50');
INSERT INTO `subjects` VALUES ('9', 'Pre-Vocation', 'PVjhs2', '14', '', '2025-10-24 12:18:53', '2025-10-24 12:18:53');
INSERT INTO `subjects` VALUES ('10', 'Mathematics', 'MathsJHS2', '14', '', '2025-10-24 12:19:44', '2025-10-24 12:19:44');
INSERT INTO `subjects` VALUES ('11', 'English Language', 'ENGjhs2', '14', '', '2025-10-24 12:20:24', '2025-10-24 12:20:24');
INSERT INTO `subjects` VALUES ('12', 'Science', 'SCIjhs2', '13', '', '2025-10-24 12:20:54', '2025-10-24 12:20:54');
INSERT INTO `subjects` VALUES ('13', 'Social Studies', 'SOCjhs2', '13', '', '2025-10-24 12:21:48', '2025-10-24 12:21:48');
INSERT INTO `subjects` VALUES ('14', 'ENGLISH LANGUAGE', 'ENGJHS1', '15', '', '2025-10-29 21:12:20', '2025-10-29 21:12:20');
INSERT INTO `subjects` VALUES ('15', 'MATHEMATICS', 'MATHJHS1', '15', '', '2025-10-29 21:13:14', '2025-10-29 21:13:14');
INSERT INTO `subjects` VALUES ('16', 'INTEGRATED SCIENCE', 'SCIJHS1', '15', '', '2025-10-29 21:14:37', '2025-10-29 21:14:37');
INSERT INTO `subjects` VALUES ('17', 'SOCIAL STUDIES', 'SOCJHS1', '15', '', '2025-10-29 21:15:39', '2025-10-29 21:15:39');
INSERT INTO `subjects` VALUES ('18', 'English', 'Enghjs1a', '16', '', '2025-10-29 23:26:52', '2025-10-29 23:26:52');
INSERT INTO `subjects` VALUES ('19', 'Maths', 'matjhs1a', '16', '', '2025-10-29 23:27:19', '2025-10-29 23:27:19');
INSERT INTO `subjects` VALUES ('20', 'Sciece', 'Scijhs1a', '16', '', '2025-10-29 23:28:21', '2025-10-29 23:28:21');


-- Table structure for table `timetables`
CREATE TABLE `timetables` (
  `id` int NOT NULL AUTO_INCREMENT,
  `class_id` int NOT NULL,
  `subject_id` int NOT NULL,
  `staff_id` int NOT NULL,
  `day_of_week` enum('monday','tuesday','wednesday','thursday','friday','saturday','sunday') NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `room` varchar(50) DEFAULT NULL,
  `academic_year_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_class_day_time` (`class_id`,`day_of_week`,`start_time`,`end_time`),
  KEY `subject_id` (`subject_id`),
  KEY `staff_id` (`staff_id`),
  KEY `academic_year_id` (`academic_year_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `timetables`
INSERT INTO `timetables` VALUES ('1', '13', '6', '6', 'monday', '10:09:00', '11:09:00', 'lab', '2', '2025-10-22 12:10:01', '2025-10-22 13:26:16');
INSERT INTO `timetables` VALUES ('2', '14', '7', '6', 'monday', '21:26:00', '22:26:00', '41', '2', '2025-11-15 21:26:29', '2025-11-15 21:26:29');


-- Table structure for table `users`
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `role_id` int DEFAULT '2',
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `users`
INSERT INTO `users` VALUES ('1', '2', 'admin', '$2y$10$rhwUE./8VMz3/y0d0TbMpOWYEojbIP67qjKnl4YL721R37nVtG1YG', 'School Admin', 'Administrator', 'admin@example.com', '0987654', 'active', '2025-12-16 13:58:03', '2025-10-12 01:58:11', '2025-12-16 13:58:03');
INSERT INTO `users` VALUES ('2', '3', 'superadmin', '$2y$10$HVd3QG3g.sy.gfZBEAwDKejqmj//zwrobZaRXQz4o6YOk2nX37iNm', 'Super', 'Administrator', 'superadmin@futuristic.edu.gh', '+233123456789', 'active', '2025-12-16 13:59:35', '2025-10-13 21:58:41', '2025-12-16 13:59:35');

