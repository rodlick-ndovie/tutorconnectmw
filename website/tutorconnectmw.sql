-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 09, 2026 at 05:26 PM
-- Server version: 8.4.7
-- PHP Version: 8.5.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tutorconnectmw`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

DROP TABLE IF EXISTS `announcements`;
CREATE TABLE IF NOT EXISTS `announcements` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `content` text COLLATE utf8mb4_general_ci NOT NULL,
  `announcement_type` enum('school_services','exam_prep','tuition_services','other') COLLATE utf8mb4_general_ci DEFAULT 'tuition_services',
  `target_audience` enum('schools','ministry','parents','general') COLLATE utf8mb4_general_ci DEFAULT 'general',
  `posted_by` int UNSIGNED NOT NULL,
  `district` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `contact_info` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_approved` tinyint(1) DEFAULT '0',
  `approved_by` int UNSIGNED DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  `views_count` int UNSIGNED DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `announcement_type` (`announcement_type`),
  KEY `target_audience` (`target_audience`),
  KEY `posted_by` (`posted_by`),
  KEY `district` (`district`),
  KEY `is_approved` (`is_approved`),
  KEY `announcements_ibfk_2` (`approved_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
CREATE TABLE IF NOT EXISTS `bookings` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `reference_code` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `tutor_id` int UNSIGNED NOT NULL,
  `client_id` int UNSIGNED DEFAULT NULL,
  `parent_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `parent_phone` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `parent_email` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `client_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `client_level` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `subjects_needed` text COLLATE utf8mb4_general_ci,
  `booking_date` date NOT NULL,
  `booking_time` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `duration` decimal(4,2) DEFAULT '1.00' COMMENT 'Session duration in hours',
  `session_type` enum('trial','package','one-off','inquiry','daily','weekly','bi-weekly','monthly') COLLATE utf8mb4_general_ci DEFAULT 'one-off',
  `amount` decimal(10,2) DEFAULT '0.00',
  `teaching_mode` enum('online','in-person') COLLATE utf8mb4_general_ci DEFAULT 'online' COMMENT 'Preferred teaching mode: online or in-person',
  `currency` varchar(10) COLLATE utf8mb4_general_ci DEFAULT 'MWK',
  `status` enum('pending','confirmed','completed','cancelled','rescheduled','inquiry') COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `payment_status` enum('pending','paid','refunded','failed') COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `notes` text COLLATE utf8mb4_general_ci,
  `meeting_link` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reference_code` (`reference_code`),
  KEY `tutor_id` (`tutor_id`),
  KEY `booking_date` (`booking_date`),
  KEY `status` (`status`),
  KEY `payment_status` (`payment_status`),
  KEY `idx_bookings_reference_code` (`reference_code`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `subject` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `message` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `service` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `email` (`email`),
  KEY `is_read` (`is_read`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `phone`, `subject`, `message`, `service`, `is_read`, `created_at`, `updated_at`) VALUES
(2, 'rodlick ndovie', 'rodlick.ndovie@gmail.com', '0996687622', 'hello', '\r\nThank you for contacting us! We\'ll respond within 24 hours.\r\nThank you for contacting us! We\'ll respond within 24 hours.\r\nThank you for contacting us! We\'ll respond within 24 hours.\r\nThank you for contacting us! We\'ll respond within 24 hours.\r\nThank you for contacting us! We\'ll respond within 24 hours.\r\nThank you for contacting us! We\'ll respond within 24 hours.\r\nThank you for contacting us! We\'ll respond within 24 hours.\r\nThank you for contacting us! We\'ll respond within 24 hours.\r\nThank you for contacting us! We\'ll respond within 24 hours.\r\nThank you for contacting us! We\'ll respond within 24 hours.\r\nThank you for contacting us! We\'ll respond within 24 hours.', NULL, 1, '2026-01-02 05:26:28', '2026-01-02 05:35:18');

-- --------------------------------------------------------

--
-- Table structure for table `curriculum_subjects`
--

DROP TABLE IF EXISTS `curriculum_subjects`;
CREATE TABLE IF NOT EXISTS `curriculum_subjects` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `curriculum` enum('MANEB','GCSE','Cambridge','ABEKA','Montessori','Languages','Rafiki') COLLATE utf8mb4_general_ci NOT NULL,
  `level_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `subject_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `subject_category` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `curriculum_level` (`curriculum`,`level_name`),
  KEY `subject_name` (`subject_name`)
) ENGINE=InnoDB AUTO_INCREMENT=555 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `curriculum_subjects`
--

INSERT INTO `curriculum_subjects` (`id`, `curriculum`, `level_name`, `subject_name`, `subject_category`, `is_active`, `created_at`, `updated_at`) VALUES
(367, 'MANEB', 'Primary: Standards 1–8', 'Chichewa', 'Language', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(368, 'MANEB', 'Primary: Standards 1–8', 'English', 'Language', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(369, 'MANEB', 'Primary: Standards 1–8', 'Mathematics', 'Mathematics', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(370, 'MANEB', 'Primary: Standards 1–8', 'Expressive Arts', 'Arts', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(371, 'MANEB', 'Primary: Standards 1–8', 'Life Skills', 'Social Studies', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(372, 'MANEB', 'Primary: Standards 1–8', 'Social and Environmental Sciences', 'Social Studies', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(373, 'MANEB', 'Primary: Standards 1–8', 'Science and Technology', 'Science', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(374, 'MANEB', 'Primary: Standards 1–8', 'Agriculture', 'Agriculture', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(375, 'MANEB', 'Primary: Standards 1–8', 'Bible Knowledge / Religious Education', 'Religion', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(376, 'MANEB', 'Secondary: Forms 1–4', 'English Language', 'Language', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(377, 'MANEB', 'Secondary: Forms 1–4', 'Mathematics', 'Mathematics', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(378, 'MANEB', 'Secondary: Forms 1–4', 'Chichewa', 'Language', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(379, 'MANEB', 'Secondary: Forms 1–4', 'Social Studies', 'Social Studies', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(380, 'MANEB', 'Secondary: Forms 1–4', 'Biology', 'Science', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(381, 'MANEB', 'Secondary: Forms 1–4', 'Physics', 'Science', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(382, 'MANEB', 'Secondary: Forms 1–4', 'Chemistry', 'Science', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(383, 'MANEB', 'Secondary: Forms 1–4', 'Geography', 'Social Studies', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(384, 'MANEB', 'Secondary: Forms 1–4', 'History', 'Social Studies', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(385, 'MANEB', 'Secondary: Forms 1–4', 'Agriculture', 'Agriculture', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(386, 'MANEB', 'Secondary: Forms 1–4', 'Computer Studies', 'Technology', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(387, 'MANEB', 'Secondary: Forms 1–4', 'Business Studies', 'Business', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(388, 'MANEB', 'Secondary: Forms 1–4', 'Life Skills', 'Social Studies', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(389, 'MANEB', 'Secondary: Forms 1–4', 'French', 'Language', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(390, 'MANEB', 'Secondary: Forms 1–4', 'Additional Mathematics', 'Mathematics', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(391, 'MANEB', 'Secondary: Forms 1–4', 'Religious and Moral Education', 'Religion', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(392, 'MANEB', 'Secondary: Forms 1–4', 'Performing Arts', 'Arts', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(393, 'MANEB', 'Secondary: Forms 1–4', 'Woodwork', 'Technology', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(394, 'GCSE', 'Key Stage 4 (Years 10–11)', 'English Language', 'Language', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(395, 'GCSE', 'Key Stage 4 (Years 10–11)', 'English Literature', 'Language', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(396, 'GCSE', 'Key Stage 4 (Years 10–11)', 'Mathematics', 'Mathematics', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(397, 'GCSE', 'Key Stage 4 (Years 10–11)', 'Combined Science', 'Science', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(398, 'GCSE', 'Key Stage 4 (Years 10–11)', 'Biology', 'Science', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(399, 'GCSE', 'Key Stage 4 (Years 10–11)', 'Chemistry', 'Science', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(400, 'GCSE', 'Key Stage 4 (Years 10–11)', 'Physics', 'Science', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(401, 'GCSE', 'Key Stage 4 (Years 10–11)', 'Art and Design', 'Arts', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(402, 'GCSE', 'Key Stage 4 (Years 10–11)', 'Ancient Languages', 'Language', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(403, 'GCSE', 'Key Stage 4 (Years 10–11)', 'Citizenship Studies', 'Social Studies', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(404, 'GCSE', 'Key Stage 4 (Years 10–11)', 'Computer Science', 'Technology', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(405, 'GCSE', 'Key Stage 4 (Years 10–11)', 'Dance', 'Arts', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(406, 'GCSE', 'Key Stage 4 (Years 10–11)', 'Drama', 'Arts', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(407, 'GCSE', 'Key Stage 4 (Years 10–11)', 'Food Preparation and Nutrition', 'Technology', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(408, 'GCSE', 'Key Stage 4 (Years 10–11)', 'Geography', 'Social Studies', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(409, 'GCSE', 'Key Stage 4 (Years 10–11)', 'History', 'Social Studies', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(410, 'GCSE', 'Key Stage 4 (Years 10–11)', 'Modern Foreign Languages', 'Language', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(411, 'GCSE', 'Key Stage 4 (Years 10–11)', 'Music', 'Arts', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(412, 'GCSE', 'Key Stage 4 (Years 10–11)', 'Physical Education', 'Sports', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(413, 'GCSE', 'Key Stage 4 (Years 10–11)', 'Religious Studies', 'Religion', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(414, 'GCSE', 'Key Stage 4 (Years 10–11)', 'Ancient History', 'Social Studies', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(415, 'GCSE', 'Key Stage 4 (Years 10–11)', 'Astronomy', 'Science', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(416, 'GCSE', 'Key Stage 4 (Years 10–11)', 'Business', 'Business', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(417, 'GCSE', 'Key Stage 4 (Years 10–11)', 'Classical Civilisation', 'Social Studies', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(418, 'GCSE', 'Key Stage 4 (Years 10–11)', 'Design and Technology', 'Technology', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(419, 'GCSE', 'Key Stage 4 (Years 10–11)', 'Economics', 'Business', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(420, 'GCSE', 'Key Stage 4 (Years 10–11)', 'Engineering', 'Technology', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(421, 'GCSE', 'Key Stage 4 (Years 10–11)', 'Film Studies', 'Arts', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(422, 'GCSE', 'Key Stage 4 (Years 10–11)', 'Media Studies', 'Arts', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(423, 'GCSE', 'Key Stage 4 (Years 10–11)', 'Psychology', 'Social Studies', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(424, 'GCSE', 'Key Stage 4 (Years 10–11)', 'Sociology', 'Social Studies', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(425, 'GCSE', 'Key Stage 4 (Years 10–11)', 'Statistics', 'Mathematics', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(426, 'GCSE', 'Key Stage 4 (Years 10–11)', 'British Sign Language', 'Language', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(427, 'Cambridge', 'Cambridge Primary (Grades 1–6)', 'Art & Design', 'Arts', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(428, 'Cambridge', 'Cambridge Primary (Grades 1–6)', 'Computing', 'Technology', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(429, 'Cambridge', 'Cambridge Primary (Grades 1–6)', 'Digital Literacy', 'Technology', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(430, 'Cambridge', 'Cambridge Primary (Grades 1–6)', 'English', 'Language', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(431, 'Cambridge', 'Cambridge Primary (Grades 1–6)', 'English as a Second Language', 'Language', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(432, 'Cambridge', 'Cambridge Primary (Grades 1–6)', 'Global Perspectives', 'Social Studies', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(433, 'Cambridge', 'Cambridge Primary (Grades 1–6)', 'Humanities', 'Social Studies', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(434, 'Cambridge', 'Cambridge Primary (Grades 1–6)', 'Mathematics', 'Mathematics', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(435, 'Cambridge', 'Cambridge Primary (Grades 1–6)', 'Modern Foreign Language', 'Language', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(436, 'Cambridge', 'Cambridge Primary (Grades 1–6)', 'Music', 'Arts', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(437, 'Cambridge', 'Cambridge Primary (Grades 1–6)', 'Physical Education', 'Sports', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(438, 'Cambridge', 'Cambridge Primary (Grades 1–6)', 'Science', 'Science', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(439, 'Cambridge', 'Cambridge Primary (Grades 1–6)', 'Wellbeing', 'Social Studies', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(440, 'Cambridge', 'Cambridge Lower Secondary (Grades 7–9)', 'Art & Design', 'Arts', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(441, 'Cambridge', 'Cambridge Lower Secondary (Grades 7–9)', 'Digital Literacy', 'Technology', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(442, 'Cambridge', 'Cambridge Lower Secondary (Grades 7–9)', 'English', 'Language', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(443, 'Cambridge', 'Cambridge Lower Secondary (Grades 7–9)', 'English as a Second Language', 'Language', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(444, 'Cambridge', 'Cambridge Lower Secondary (Grades 7–9)', 'Global Perspectives', 'Social Studies', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(445, 'Cambridge', 'Cambridge Lower Secondary (Grades 7–9)', 'Mathematics', 'Mathematics', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(446, 'Cambridge', 'Cambridge Lower Secondary (Grades 7–9)', 'Music', 'Arts', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(447, 'Cambridge', 'Cambridge Lower Secondary (Grades 7–9)', 'Physical Education', 'Sports', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(448, 'Cambridge', 'Cambridge Lower Secondary (Grades 7–9)', 'Science', 'Science', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(449, 'Cambridge', 'Cambridge Lower Secondary (Grades 7–9)', 'Wellbeing', 'Social Studies', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(450, 'Cambridge', 'Cambridge Lower Secondary (Grades 7–9)', 'Modern Foreign Languages', 'Language', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(451, 'Cambridge', 'Cambridge IGCSE (Grades 10–11)', 'English (First Language)', 'Language', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(452, 'Cambridge', 'Cambridge IGCSE (Grades 10–11)', 'English (Second Language)', 'Language', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(453, 'Cambridge', 'Cambridge IGCSE (Grades 10–11)', 'Mathematics', 'Mathematics', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(454, 'Cambridge', 'Cambridge IGCSE (Grades 10–11)', 'Additional Mathematics', 'Mathematics', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(455, 'Cambridge', 'Cambridge IGCSE (Grades 10–11)', 'Biology', 'Science', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(456, 'Cambridge', 'Cambridge IGCSE (Grades 10–11)', 'Chemistry', 'Science', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(457, 'Cambridge', 'Cambridge IGCSE (Grades 10–11)', 'Physics', 'Science', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(458, 'Cambridge', 'Cambridge IGCSE (Grades 10–11)', 'Combined Science', 'Science', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(459, 'Cambridge', 'Cambridge IGCSE (Grades 10–11)', 'Co-ordinated Science', 'Science', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(460, 'Cambridge', 'Cambridge IGCSE (Grades 10–11)', 'Accounting', 'Business', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(461, 'Cambridge', 'Cambridge IGCSE (Grades 10–11)', 'Business Studies', 'Business', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(462, 'Cambridge', 'Cambridge IGCSE (Grades 10–11)', 'Economics', 'Business', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(463, 'Cambridge', 'Cambridge IGCSE (Grades 10–11)', 'Geography', 'Social Studies', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(464, 'Cambridge', 'Cambridge IGCSE (Grades 10–11)', 'History', 'Social Studies', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(465, 'Cambridge', 'Cambridge IGCSE (Grades 10–11)', 'Art & Design', 'Arts', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(466, 'Cambridge', 'Cambridge IGCSE (Grades 10–11)', 'Music', 'Arts', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(467, 'Cambridge', 'Cambridge IGCSE (Grades 10–11)', 'Drama', 'Arts', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(468, 'Cambridge', 'Cambridge IGCSE (Grades 10–11)', 'Physical Education', 'Sports', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(469, 'Cambridge', 'Cambridge IGCSE (Grades 10–11)', 'Computer Science', 'Technology', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(470, 'Cambridge', 'Cambridge IGCSE (Grades 10–11)', 'ICT', 'Technology', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(471, 'Cambridge', 'Cambridge IGCSE (Grades 10–11)', 'Global Perspectives', 'Social Studies', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(472, 'Cambridge', 'Cambridge IGCSE (Grades 10–11)', 'Environmental Management', 'Science', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(473, 'Cambridge', 'Cambridge IGCSE (Grades 10–11)', 'Agriculture', 'Agriculture', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(474, 'Cambridge', 'Cambridge IGCSE (Grades 10–11)', 'Food & Nutrition', 'Technology', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(475, 'Cambridge', 'Cambridge IGCSE (Grades 10–11)', 'Arabic', 'Language', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(476, 'Cambridge', 'Cambridge IGCSE (Grades 10–11)', 'Chinese', 'Language', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(477, 'Cambridge', 'Cambridge IGCSE (Grades 10–11)', 'French', 'Language', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(478, 'Cambridge', 'Cambridge IGCSE (Grades 10–11)', 'German', 'Language', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(479, 'Cambridge', 'Cambridge IGCSE (Grades 10–11)', 'Spanish', 'Language', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(480, 'Cambridge', 'Cambridge IGCSE (Grades 10–11)', 'Travel & Tourism', 'Business', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(481, 'Cambridge', 'Cambridge AS/A Level (Grades 12–13)', 'English Language', 'Language', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(482, 'Cambridge', 'Cambridge AS/A Level (Grades 12–13)', 'Literature in English', 'Language', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(483, 'Cambridge', 'Cambridge AS/A Level (Grades 12–13)', 'Mathematics', 'Mathematics', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(484, 'Cambridge', 'Cambridge AS/A Level (Grades 12–13)', 'Further Mathematics', 'Mathematics', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(485, 'Cambridge', 'Cambridge AS/A Level (Grades 12–13)', 'Biology', 'Science', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(486, 'Cambridge', 'Cambridge AS/A Level (Grades 12–13)', 'Chemistry', 'Science', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(487, 'Cambridge', 'Cambridge AS/A Level (Grades 12–13)', 'Physics', 'Science', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(488, 'Cambridge', 'Cambridge AS/A Level (Grades 12–13)', 'Computer Science', 'Technology', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(489, 'Cambridge', 'Cambridge AS/A Level (Grades 12–13)', 'Accounting', 'Business', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(490, 'Cambridge', 'Cambridge AS/A Level (Grades 12–13)', 'Business', 'Business', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(491, 'Cambridge', 'Cambridge AS/A Level (Grades 12–13)', 'Economics', 'Business', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(492, 'Cambridge', 'Cambridge AS/A Level (Grades 12–13)', 'Geography', 'Social Studies', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(493, 'Cambridge', 'Cambridge AS/A Level (Grades 12–13)', 'History', 'Social Studies', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(494, 'Cambridge', 'Cambridge AS/A Level (Grades 12–13)', 'Psychology', 'Social Studies', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(495, 'Cambridge', 'Cambridge AS/A Level (Grades 12–13)', 'Sociology', 'Social Studies', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(496, 'Cambridge', 'Cambridge AS/A Level (Grades 12–13)', 'Art & Design', 'Arts', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(497, 'Cambridge', 'Cambridge AS/A Level (Grades 12–13)', 'Design & Technology', 'Technology', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(498, 'Cambridge', 'Cambridge AS/A Level (Grades 12–13)', 'Music', 'Arts', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(499, 'Cambridge', 'Cambridge AS/A Level (Grades 12–13)', 'Physical Education', 'Sports', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(500, 'Cambridge', 'Cambridge AS/A Level (Grades 12–13)', 'Global Perspectives & Research', 'Social Studies', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(501, 'Cambridge', 'Cambridge AS/A Level (Grades 12–13)', 'French', 'Language', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(502, 'Cambridge', 'Cambridge AS/A Level (Grades 12–13)', 'Spanish', 'Language', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(503, 'Cambridge', 'Cambridge AS/A Level (Grades 12–13)', 'German', 'Language', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(504, 'Cambridge', 'Cambridge AS/A Level (Grades 12–13)', 'Chinese', 'Language', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(505, 'Cambridge', 'Cambridge AS/A Level (Grades 12–13)', 'Arabic', 'Language', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(506, 'Cambridge', 'Cambridge AS/A Level (Grades 12–13)', 'Marine Science', 'Science', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(507, 'Cambridge', 'Cambridge AS/A Level (Grades 12–13)', 'Environmental Management', 'Science', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(508, 'Cambridge', 'Cambridge AS/A Level (Grades 12–13)', 'Law', 'Social Studies', 1, '2025-12-30 00:56:27', '2025-12-30 00:56:27'),
(509, 'ABEKA', 'Kindergarten', 'Phonics & Reading', 'Language', 1, '2026-01-06 16:06:33', '2026-01-06 16:06:33'),
(510, 'ABEKA', 'Kindergarten', 'Mathematics', 'Mathematics', 1, '2026-01-06 16:06:33', '2026-01-06 16:06:33'),
(511, 'ABEKA', 'Kindergarten', 'Bible', 'Religion', 1, '2026-01-06 16:06:33', '2026-01-06 16:06:33'),
(512, 'ABEKA', 'Primary (Grades 1–6)', 'English', 'Language', 1, '2026-01-06 16:06:33', '2026-01-06 16:06:33'),
(513, 'ABEKA', 'Primary (Grades 1–6)', 'Mathematics', 'Mathematics', 1, '2026-01-06 16:06:33', '2026-01-06 16:06:33'),
(514, 'ABEKA', 'Primary (Grades 1–6)', 'Science', 'Science', 1, '2026-01-06 16:06:33', '2026-01-06 16:06:33'),
(515, 'ABEKA', 'Primary (Grades 1–6)', 'Social Studies', 'Social Studies', 1, '2026-01-06 16:06:33', '2026-01-06 16:06:33'),
(516, 'ABEKA', 'Primary (Grades 1–6)', 'Bible', 'Religion', 1, '2026-01-06 16:06:33', '2026-01-06 16:06:33'),
(517, 'ABEKA', 'Secondary (Grades 7–12)', 'English', 'Language', 1, '2026-01-06 16:06:33', '2026-01-06 16:06:33'),
(518, 'ABEKA', 'Secondary (Grades 7–12)', 'Mathematics', 'Mathematics', 1, '2026-01-06 16:06:33', '2026-01-06 16:06:33'),
(519, 'ABEKA', 'Secondary (Grades 7–12)', 'Biology', 'Science', 1, '2026-01-06 16:06:33', '2026-01-06 16:06:33'),
(520, 'ABEKA', 'Secondary (Grades 7–12)', 'Chemistry', 'Science', 1, '2026-01-06 16:06:33', '2026-01-06 16:06:33'),
(521, 'ABEKA', 'Secondary (Grades 7–12)', 'Physics', 'Science', 1, '2026-01-06 16:06:33', '2026-01-06 16:06:33'),
(522, 'ABEKA', 'Secondary (Grades 7–12)', 'Bible Doctrine', 'Religion', 1, '2026-01-06 16:06:33', '2026-01-06 16:06:33'),
(523, 'Montessori', 'Early Childhood (Ages 2–6)', 'Practical Life', 'Life Skills', 1, '2026-01-06 16:07:19', '2026-01-06 16:07:19'),
(524, 'Montessori', 'Early Childhood (Ages 2–6)', 'Sensorial Activities', 'Cognitive Skills', 1, '2026-01-06 16:07:19', '2026-01-06 16:07:19'),
(525, 'Montessori', 'Early Childhood (Ages 2–6)', 'Language Development', 'Language', 1, '2026-01-06 16:07:19', '2026-01-06 16:07:19'),
(526, 'Montessori', 'Early Childhood (Ages 2–6)', 'Mathematics Concepts', 'Mathematics', 1, '2026-01-06 16:07:19', '2026-01-06 16:07:19'),
(527, 'Montessori', 'Lower Elementary (Ages 6–9)', 'Mathematics', 'Mathematics', 1, '2026-01-06 16:07:19', '2026-01-06 16:07:19'),
(528, 'Montessori', 'Lower Elementary (Ages 6–9)', 'Language Arts', 'Language', 1, '2026-01-06 16:07:19', '2026-01-06 16:07:19'),
(529, 'Montessori', 'Lower Elementary (Ages 6–9)', 'Cultural Studies', 'Social Studies', 1, '2026-01-06 16:07:19', '2026-01-06 16:07:19'),
(530, 'Montessori', 'Upper Elementary (Ages 9–12)', 'Advanced Mathematics', 'Mathematics', 1, '2026-01-06 16:07:19', '2026-01-06 16:07:19'),
(531, 'Montessori', 'Upper Elementary (Ages 9–12)', 'Science Exploration', 'Science', 1, '2026-01-06 16:07:19', '2026-01-06 16:07:19'),
(532, 'Montessori', 'Upper Elementary (Ages 9–12)', 'Geography & History', 'Social Studies', 1, '2026-01-06 16:07:19', '2026-01-06 16:07:19'),
(533, 'Languages', 'All Levels', 'French', 'Language', 1, '2026-01-06 16:07:51', '2026-01-06 16:07:51'),
(534, 'Languages', 'All Levels', 'Spanish', 'Language', 1, '2026-01-06 16:07:51', '2026-01-06 16:07:51'),
(535, 'Languages', 'All Levels', 'German', 'Language', 1, '2026-01-06 16:07:51', '2026-01-06 16:07:51'),
(536, 'Languages', 'All Levels', 'Chinese (Mandarin)', 'Language', 1, '2026-01-06 16:07:51', '2026-01-06 16:07:51'),
(537, 'Languages', 'All Levels', 'Swahili', 'Language', 1, '2026-01-06 16:07:51', '2026-01-06 16:07:51'),
(538, 'Languages', 'All Levels', 'Arabic', 'Language', 1, '2026-01-06 16:07:51', '2026-01-06 16:07:51'),
(539, 'Languages', 'All Levels', 'Portuguese', 'Language', 1, '2026-01-06 16:07:51', '2026-01-06 16:07:51'),
(540, 'ABEKA', 'Primary (Grades 1–6)', 'Letters and Sounds', 'Language', 1, '2026-01-09 11:53:44', '2026-01-09 11:53:44'),
(541, 'ABEKA', 'Primary (Grades 1–6)', 'Number Skills / Arithmetic', 'Mathematics', 1, '2026-01-09 11:53:44', '2026-01-09 11:53:44'),
(542, 'ABEKA', 'Primary (Grades 1–6)', 'Reading and Comprehension', 'Language', 1, '2026-01-09 11:53:44', '2026-01-09 11:53:44'),
(543, 'ABEKA', 'Primary (Grades 1–6)', 'Health, Safety and Manners', 'Life Skills', 1, '2026-01-09 11:53:44', '2026-01-09 11:53:44'),
(544, 'ABEKA', 'Primary (Grades 1–6)', 'God\'s World', 'Science', 1, '2026-01-09 11:53:44', '2026-01-09 11:53:44'),
(545, 'ABEKA', 'Primary (Grades 1–6)', 'Handwriting', 'Language', 1, '2026-01-09 11:53:44', '2026-01-09 11:53:44'),
(546, 'ABEKA', 'Primary (Grades 1–6)', 'God\'s Gift of Language', 'Language', 1, '2026-01-09 11:53:44', '2026-01-09 11:53:44'),
(547, 'ABEKA', 'Primary (Grades 1–6)', 'Spellings', 'Language', 1, '2026-01-09 11:53:44', '2026-01-09 11:53:44'),
(548, 'Rafiki', 'Primary', 'Language Arts (English)', 'Language', 1, '2026-01-09 11:54:21', '2026-01-09 11:54:21'),
(549, 'Rafiki', 'Primary', 'Science', 'Science', 1, '2026-01-09 11:54:21', '2026-01-09 11:54:21'),
(550, 'Rafiki', 'Primary', 'Art and Design', 'Arts', 1, '2026-01-09 11:54:21', '2026-01-09 11:54:21'),
(551, 'Rafiki', 'Primary', 'Mathematics', 'Mathematics', 1, '2026-01-09 11:54:21', '2026-01-09 11:54:21'),
(552, 'Rafiki', 'Primary', 'Social Studies', 'Social Studies', 1, '2026-01-09 11:54:21', '2026-01-09 11:54:21'),
(553, 'Rafiki', 'Primary', 'Character Development', 'Life Skills', 1, '2026-01-09 11:54:21', '2026-01-09 11:54:21'),
(554, 'Rafiki', 'Primary', 'Bible', 'Religion', 1, '2026-01-09 11:54:21', '2026-01-09 11:54:21');

-- --------------------------------------------------------

--
-- Table structure for table `education_levels`
--

DROP TABLE IF EXISTS `education_levels`;
CREATE TABLE IF NOT EXISTS `education_levels` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `category` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `level_order` int DEFAULT '0',
  `description` text COLLATE utf8mb4_general_ci,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `category` (`category`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

DROP TABLE IF EXISTS `enrollments`;
CREATE TABLE IF NOT EXISTS `enrollments` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` int UNSIGNED NOT NULL,
  `tutor_id` int UNSIGNED NOT NULL,
  `service_id` int UNSIGNED DEFAULT NULL,
  `status` enum('pending','active','completed','cancelled') COLLATE utf8mb4_general_ci DEFAULT 'active',
  `enrolled_at` datetime DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `tutor_id` (`tutor_id`),
  KEY `service_id` (`service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `japan_application_access`
--

DROP TABLE IF EXISTS `japan_application_access`;
CREATE TABLE IF NOT EXISTS `japan_application_access` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `tx_ref` varchar(80) COLLATE utf8mb4_general_ci NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '10000.00',
  `currency` varchar(10) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'MWK',
  `payment_method` varchar(30) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'paychangu',
  `payment_status` varchar(30) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `access_status` varchar(30) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `access_token` varchar(80) COLLATE utf8mb4_general_ci NOT NULL,
  `paid_at` datetime DEFAULT NULL,
  `last_accessed_at` datetime DEFAULT NULL,
  `application_id` int UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tx_ref` (`tx_ref`),
  UNIQUE KEY `access_token` (`access_token`),
  KEY `email` (`email`),
  KEY `payment_status` (`payment_status`),
  KEY `access_status` (`access_status`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `japan_application_access`
--

INSERT INTO `japan_application_access` (`id`, `tx_ref`, `full_name`, `email`, `phone`, `amount`, `currency`, `payment_method`, `payment_status`, `access_status`, `access_token`, `paid_at`, `last_accessed_at`, `application_id`, `created_at`, `updated_at`) VALUES
(17, 'JAP-20260331080701-EB328CD0', 'Rodlick Ndovie', 'rodlick.ndovie@gmail.com', '+265996687622', 40000.00, 'MWK', 'paychangu', 'verified', 'active', 'ecc9c3b9683f95a2e1f662c05730fd14246be25293c22310bec1f3f5ec6b6d78', '2026-03-31 08:07:35', '2026-03-31 08:18:07', NULL, '2026-03-31 08:07:01', '2026-03-31 08:18:07');

-- --------------------------------------------------------

--
-- Table structure for table `japan_teaching_applications`
--

DROP TABLE IF EXISTS `japan_teaching_applications`;
CREATE TABLE IF NOT EXISTS `japan_teaching_applications` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `application_reference` varchar(40) COLLATE utf8mb4_general_ci NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nationality` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `gender` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `age` int DEFAULT NULL,
  `current_address` text COLLATE utf8mb4_general_ci,
  `has_valid_passport` tinyint(1) NOT NULL DEFAULT '0',
  `passport_number` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `passport_expiry_date` date DEFAULT NULL,
  `willing_to_renew_passport` tinyint(1) NOT NULL DEFAULT '0',
  `highest_qualification` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `degree_obtained` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `field_of_study` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `institution_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `year_of_completion` int DEFAULT NULL,
  `has_teaching_certificate` tinyint(1) NOT NULL DEFAULT '0',
  `teaching_certificate_details` text COLLATE utf8mb4_general_ci,
  `has_teaching_experience` tinyint(1) NOT NULL DEFAULT '0',
  `teaching_experience_location` text COLLATE utf8mb4_general_ci,
  `subjects_taught` text COLLATE utf8mb4_general_ci,
  `level_taught` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `teaching_experience_duration` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `documents_already_shared` tinyint(1) NOT NULL DEFAULT '0',
  `shared_documents_note` text COLLATE utf8mb4_general_ci,
  `financial_readiness_json` longtext COLLATE utf8mb4_general_ci,
  `referees_json` longtext COLLATE utf8mb4_general_ci,
  `declarations_json` longtext COLLATE utf8mb4_general_ci,
  `degree_document_path` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `transcript_document_path` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `passport_copy_path` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `teaching_certificate_path` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cv_document_path` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `intro_video_path` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `application_fee_amount` decimal(10,2) NOT NULL DEFAULT '10000.00',
  `processing_fee_amount` decimal(10,2) NOT NULL DEFAULT '350000.00',
  `status` varchar(30) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'submitted',
  `admin_notes` text COLLATE utf8mb4_general_ci,
  `submitted_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `application_reference` (`application_reference`),
  KEY `email` (`email`),
  KEY `status` (`status`),
  KEY `submitted_at` (`submitted_at`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `sender_id` int UNSIGNED NOT NULL,
  `receiver_id` int UNSIGNED NOT NULL,
  `booking_id` int UNSIGNED DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_general_ci NOT NULL,
  `message_type` enum('inquiry','booking_request','confirmation','reschedule','cancellation','feedback','general') COLLATE utf8mb4_general_ci DEFAULT 'general',
  `is_read` tinyint(1) DEFAULT '0',
  `is_system_message` tinyint(1) DEFAULT '0',
  `parent_message_id` int UNSIGNED DEFAULT NULL,
  `sent_at` datetime NOT NULL,
  `read_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sender_id` (`sender_id`),
  KEY `receiver_id` (`receiver_id`),
  KEY `booking_id` (`booking_id`),
  KEY `is_read` (`is_read`),
  KEY `sent_at` (`sent_at`),
  KEY `sender_receiver` (`sender_id`,`receiver_id`),
  KEY `messages_ibfk_4` (`parent_message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `version` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `class` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `group` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `namespace` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `time` int NOT NULL,
  `batch` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notices`
--

DROP TABLE IF EXISTS `notices`;
CREATE TABLE IF NOT EXISTS `notices` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `school_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Primary, Secondary, University, etc.',
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notice_type` enum('Vacancy','Notice','Announcement') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Notice',
  `notice_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notice_content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `attached_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Path to uploaded notice image/document',
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `approved_by` int UNSIGNED DEFAULT NULL COMMENT 'Admin user ID who approved',
  `approved_at` datetime DEFAULT NULL,
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `views_count` int NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by_user` int UNSIGNED DEFAULT NULL COMMENT 'User ID who created this notice',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `notice_type` (`notice_type`),
  KEY `created_at` (`created_at`),
  KEY `idx_created_by_user` (`created_by_user`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notices`
--

INSERT INTO `notices` (`id`, `school_name`, `school_type`, `phone`, `email`, `notice_type`, `notice_title`, `notice_content`, `attached_image`, `status`, `approved_by`, `approved_at`, `rejection_reason`, `views_count`, `created_at`, `updated_at`, `created_by_user`) VALUES
(16, 'Phoenix School', 'Primary School', '0000000000', 'admin@phoenix.mw', 'Vacancy', 'WE ARE HIRING', 'Physical Education and Swimming Teacher, Reception Class Teacher and Physical Education and Swimming Teacher \r\n\r\nFor information and to apply, visit: phoenixschoolmalawi.com/vacancies\r\nFor an application form, email admin@phoenix.mw', NULL, 'approved', NULL, '2026-01-16 14:45:56', NULL, 25, '2026-01-16 14:45:26', '2026-03-19 23:41:21', NULL),
(17, 'TutorConnect Malawi', 'Technical/Vocational School', '0992313978', 'info@tutorconnectmw.com', 'Announcement', 'PARTNERSHIP WITH FIRMS IN CHINA AND JAPAN', 'Exciting news! TutorConnect Malawi is now partnering with leading teacher recruitment agencies in China and Japan. These agencies will use our platform to connect directly with talented teachers for interviews and international opportunities.By registering on TutorConnect Malawi, you open the door to teaching positions not just in Malawi, but around the world. Don’t miss your chance to take your career global!\r\n\r\nHaving trouble registering? Reach out to us on WhatsApp at +265 99 231 3978 for instant support.\r\nRegister today and let the world discover your teaching talent!', NULL, 'approved', NULL, '2026-01-22 10:27:02', NULL, 71, '2026-01-22 10:25:53', '2026-03-25 09:06:30', NULL),
(18, 'TutorConnect Malawi', 'Secondary School', '0992313978', 'info@tutorconnectmw.com', 'Vacancy', 'ENGLISH TEACHERS & DEGREE HOLDERS WANTED', 'TutorConnect Malawi is inviting professional teachers and degree holders with a passion for teaching English as a Second Language to express interest in teaching opportunities in Japan; applicants must hold a valid passport and be willing to relocate, and eligible candidates will be guided through the application and preparation process, for more information call/whatsapp: 0992313978', NULL, 'approved', NULL, '2026-02-01 10:43:48', NULL, 34, '2026-02-01 10:42:40', '2026-03-25 10:02:17', NULL),
(19, 'Holmes Junior Academy', 'Primary School', '0980607766', 'info@tutorconnectmw.com', 'Vacancy', 'Primary Teachers Vacancy', 'Holmes Junior Academy is seeking a qualified Teachers for Primary Grade 1-5 .\r\n\r\nRequirements:\r\n- Fluency in English (spoken and written)  \r\n- Prior experience teaching an international syllabus  \r\n- Strong classroom management and communication skills  \r\n- Ability to engage young learners with creativity and care  \r\n\r\nDetails:\r\n- Location: Holmes Junior Academy, Lilongwe Area 18 A, Gomani Road \r\n- Grade Level: 1,2,3,4,5\r\n\r\nInterested candidates are invited to walk in interviews. This Friday 20th February.', NULL, 'approved', NULL, '2026-02-16 10:12:04', NULL, 12, '2026-02-16 10:11:10', '2026-03-23 22:15:49', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `past_papers`
--

DROP TABLE IF EXISTS `past_papers`;
CREATE TABLE IF NOT EXISTS `past_papers` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `exam_body` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `exam_level` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` int NOT NULL,
  `paper_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paper_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_url` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `download_count` int UNSIGNED DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `is_paid` tinyint(1) NOT NULL DEFAULT '0',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `uploaded_at` datetime DEFAULT NULL,
  `copyright_notice` text COLLATE utf8mb4_unicode_ci,
  `uploaded_by` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_uploaded_by` (`uploaded_by`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `past_papers`
--

INSERT INTO `past_papers` (`id`, `exam_body`, `exam_level`, `subject`, `year`, `paper_title`, `paper_code`, `file_url`, `file_size`, `download_count`, `is_active`, `is_paid`, `price`, `uploaded_at`, `copyright_notice`, `uploaded_by`, `created_at`, `updated_at`) VALUES
(13, 'MANEB', 'Primary: Standards 1–8', 'English', 2024, 'English Past papers', '', 'writable/uploads/past_papers/1768766507_bf2b01e2c31b83103969.pdf', '613.78 KB', 138, 1, 1, 1000.00, NULL, 'This is not TutorConnect Malawi material.This material belongs to MANED', 0, '2026-01-18 20:01:47', '2026-04-05 17:14:43'),
(14, 'MANEB', 'Primary: Standards 1–8', 'Mathematics', 2013, 'Mathematics', '', 'writable/uploads/past_papers/1768766604_072194ee204da8e72df9.pdf', '4.75 MB', 62, 1, 1, 200.00, NULL, '', 0, '2026-01-18 20:03:25', '2026-04-05 17:18:06'),
(15, 'MANEB', 'Primary: Standards 1–8', 'Mathematics', 2018, 'Mathematics', '', 'writable/uploads/past_papers/1768766662_8065e6ee1e2360b9686d.pdf', '4.64 MB', 39, 1, 0, 0.00, NULL, '', 0, '2026-01-18 20:04:23', '2026-03-24 12:03:57'),
(16, 'MANEB', 'Primary: Standards 1–8', 'Mathematics', 2019, 'Mathematics', '', 'writable/uploads/past_papers/1768766709_33d1cd76ddcbb2d17be2.pdf', '3.27 MB', 62, 1, 0, 0.00, NULL, '', 0, '2026-01-18 20:05:09', '2026-03-25 08:36:30'),
(17, 'MANEB', 'Primary: Standards 1–8', 'Life Skills', 2020, 'ARTs and Life Skills', '', 'writable/uploads/past_papers/1768766773_b91f4d879f8191f1d8a3.pdf', '5.32 MB', 47, 1, 0, 0.00, NULL, '', 0, '2026-01-18 20:06:14', '2026-03-25 08:35:34'),
(18, 'MANEB', 'Primary: Standards 1–8', 'Mathematics', 2020, 'Mathematics', '', 'writable/uploads/past_papers/1768836641_e99aef1808a03d5bd454.pdf', '4.18 MB', 48, 1, 0, 0.00, NULL, '', 0, '2026-01-19 15:30:42', '2026-03-24 12:03:42'),
(19, 'MANEB', 'Primary: Standards 1–8', 'Science and Technology', 2020, 'Primary Science', '', 'writable/uploads/past_papers/1768836731_31ab54e66b70057e7e49.pdf', '4.6 MB', 47, 1, 0, 0.00, NULL, '', 0, '2026-01-19 15:32:12', '2026-03-25 08:36:09'),
(20, 'MANEB', 'Primary: Standards 1–8', 'Bible Knowledge / Religious Education', 2020, 'Social and Religious Studies', '', 'writable/uploads/past_papers/1768836985_4315984c89bce8a66f43.pdf', '5.65 MB', 50, 1, 0, 0.00, NULL, '', 0, '2026-01-19 15:36:26', '2026-03-25 08:34:56'),
(21, 'MANEB', 'Primary: Standards 1–8', 'Chichewa', 2021, 'Chichewa Mock-Exam', '', 'writable/uploads/past_papers/1768837034_e16347b390fa84f80cd8.pdf', '166.5 KB', 41, 1, 0, 0.00, NULL, '', 0, '2026-01-19 15:37:14', '2026-03-25 08:34:19'),
(22, 'MANEB', 'Primary: Standards 1–8', 'Chichewa', 2021, 'Chichewa Mock-Exam (Malomo Zone)', '', 'writable/uploads/past_papers/1768837107_9c6bd92d162c17af5c07.pdf', '530.03 KB', 37, 1, 0, 0.00, NULL, '', 0, '2026-01-19 15:38:27', '2026-03-24 09:32:48'),
(23, 'MANEB', 'Primary: Standards 1–8', 'Chichewa', 2022, 'Chichewa Mock-Exam (Sambanyenje Zone)', '', 'writable/uploads/past_papers/1768837192_370b909d08d789edb873.pdf', '380.72 KB', 49, 1, 0, 0.00, NULL, '', 0, '2026-01-19 15:39:52', '2026-03-25 08:33:59'),
(24, 'MANEB', 'Primary: Standards 1–8', 'Expressive Arts', 2024, 'ARTS and LIFE SKILLS', '', 'writable/uploads/past_papers/1768837248_84a7d72bb6824750ce5c.pdf', '662.64 KB', 104, 1, 0, 0.00, NULL, '', 0, '2026-01-19 15:40:48', '2026-03-24 17:24:50'),
(25, 'MANEB', 'Primary: Standards 1–8', 'Mathematics', 2024, 'Mathematics', '', 'writable/uploads/past_papers/1768837320_68278771b8d7eda59d5d.pdf', '743.41 KB', 54, 1, 0, 0.00, NULL, '', 0, '2026-01-19 15:42:00', '2026-03-24 09:31:39'),
(26, 'MANEB', 'Primary: Standards 1–8', 'Chichewa', 2024, 'Chichewa', '', 'writable/uploads/past_papers/1768837553_2589cf2f9ec7ac079c1a.pdf', '2.28 MB', 83, 1, 0, 0.00, NULL, '', 0, '2026-01-19 15:45:53', '2026-03-25 08:17:29'),
(27, 'MANEB', 'Primary: Standards 1–8', 'English', 2024, 'English', '', 'writable/uploads/past_papers/1768837606_78fe4caa7a4c80e8a8de.pdf', '357.43 KB', 71, 1, 0, 0.00, NULL, '', 0, '2026-01-19 15:46:46', '2026-03-25 08:21:20'),
(28, 'MANEB', 'Primary: Standards 1–8', 'Science and Technology', 2024, 'Primary Sciences', '', 'writable/uploads/past_papers/1768837674_31fcc42b633bd6282574.pdf', '2.33 MB', 68, 1, 0, 0.00, NULL, '', 0, '2026-01-19 15:47:54', '2026-03-25 08:23:40'),
(29, 'MANEB', 'Primary: Standards 1–8', 'Social and Environmental Sciences', 2024, 'Social and Religious Studies', '', 'writable/uploads/past_papers/1768837740_ccfa64bc10df029a18c5.pdf', '668.68 KB', 57, 1, 1, 400.00, NULL, '', 0, '2026-01-19 15:49:00', '2026-04-04 18:58:13'),
(30, 'MANEB', 'Primary: Standards 1–8', 'Chichewa', 2024, 'Chichewa - SNOW WHITE MOK EXAM', '', 'writable/uploads/past_papers/1768837820_0792da9a72a6f605a981.pdf', '346.51 KB', 49, 1, 1, 20000.00, NULL, '', 0, '2026-01-19 15:50:20', '2026-04-04 18:48:20'),
(31, 'MANEB', 'Primary: Standards 1–8', 'Life Skills', 2024, 'ARTS AND LIFE SKILLS- PAST PAPERS', '', 'writable/uploads/past_papers/1768837898_922cb824a6c46954ed5f.pdf', '1.83 MB', 71, 1, 0, 0.00, NULL, '', 0, '2026-01-19 15:51:38', '2026-03-25 08:21:42'),
(32, 'MANEB', 'Primary: Standards 1–8', 'Science and Technology', 2024, 'Primary Science - Past Papers', '', 'writable/uploads/past_papers/1768837987_1f2d34032c24485c5061.pdf', '1.78 MB', 66, 1, 0, 0.00, NULL, '', 0, '2026-01-19 15:53:07', '2026-03-25 08:33:06'),
(33, 'MANEB', 'Primary: Standards 1–8', 'Mathematics', 2024, 'Standard 5 Mathematics', '', 'writable/uploads/past_papers/1768838070_5876869ae048fa7e92d9.pdf', '7.84 MB', 54, 1, 0, 0.00, NULL, '', 0, '2026-01-19 15:54:31', '2026-03-24 14:38:12'),
(34, 'MANEB', 'Primary: Standards 1–8', 'Mathematics', 2025, 'Mathematics Teachers Book Standard 8 Q&A', '', 'writable/uploads/past_papers/1770790471_b3c48fe5fceec3745f1b.pdf', '1.68 MB', 85, 1, 0, 0.00, NULL, 'This is not our book, we are sharing for public access', 0, '2026-02-11 06:14:31', '2026-03-25 08:16:41'),
(35, 'Cambridge', 'Cambridge Lower Secondary (Grades 7–9)', 'Mathematics', 2025, 'Progression Test', NULL, 'uploads/past_papers/1771268283_2edfc5daf90b4882.pdf', '1.06 MB', 65, 1, 0, 0.00, '2026-02-16 18:58:03', NULL, 158, '2026-02-16 18:58:03', '2026-03-24 18:08:07'),
(36, 'MANEB', 'Primary: Standards 1–8', 'Chichewa', 2021, 'Progression Testhkabhaas', '', 'writable/uploads/past_papers/1775306756_4b3c33cea258aca4562e.pdf', '1.37 MB', 5, 1, 1, 500.00, NULL, '', 0, '2026-04-04 12:45:56', '2026-04-04 18:51:47'),
(37, 'MANEB', 'Primary: Standards 1–8', 'Bible Knowledge / Religious Education', 2011, 'Progression Test', '', 'writable/uploads/past_papers/1775307705_fe4c64b30e1f5d6b536c.pdf', '187.9 KB', 3, 1, 1, 5000.00, NULL, '', 0, '2026-04-04 13:01:45', '2026-04-04 18:38:27');

-- --------------------------------------------------------

--
-- Table structure for table `past_paper_purchases`
--

DROP TABLE IF EXISTS `past_paper_purchases`;
CREATE TABLE IF NOT EXISTS `past_paper_purchases` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `past_paper_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `tx_ref` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `buyer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `buyer_email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `buyer_phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `currency` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'MWK',
  `payment_method` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'paychangu',
  `payment_status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `access_token` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paid_at` datetime DEFAULT NULL,
  `download_granted_at` datetime DEFAULT NULL,
  `last_downloaded_at` datetime DEFAULT NULL,
  `download_count` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ux_past_paper_purchases_tx_ref` (`tx_ref`),
  UNIQUE KEY `ux_past_paper_purchases_access_token` (`access_token`),
  KEY `idx_past_paper_purchases_past_paper_id` (`past_paper_id`),
  KEY `idx_past_paper_purchases_user_id` (`user_id`),
  KEY `idx_past_paper_purchases_buyer_email` (`buyer_email`),
  KEY `idx_past_paper_purchases_payment_status` (`payment_status`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `past_paper_purchases`
--

INSERT INTO `past_paper_purchases` (`id`, `past_paper_id`, `user_id`, `tx_ref`, `buyer_name`, `buyer_email`, `buyer_phone`, `amount`, `currency`, `payment_method`, `payment_status`, `access_token`, `paid_at`, `download_granted_at`, `last_downloaded_at`, `download_count`, `created_at`, `updated_at`) VALUES
(1, 36, NULL, 'PP-36-1775308927-fb1c5b85', 'Rodlick Ndovie', 'rodlick.ndovie@gmail.com', '0996687622', 500.00, 'MWK', 'paychangu', 'pending', '2aa2843bc0a489b56196cc93011c2118938fa052509ed5f19488981b988f5c03', NULL, NULL, NULL, 0, '2026-04-04 13:22:07', '2026-04-04 13:22:07'),
(2, 36, NULL, 'PP-36-1775309388-6c95f414', 'Rodlick Ndovie', 'rodlick.ndovie@gmail.com', '0996687622', 500.00, 'MWK', 'paychangu', 'pending', '15ddd930f415f7d055af21fff2e2e9c2d6f8412eb8ea1b44401f60ef8eab41d6', NULL, NULL, NULL, 0, '2026-04-04 13:29:48', '2026-04-04 13:29:48'),
(3, 36, NULL, 'PP-36-1775315958-cb2a3564', 'Rodlick Ndovie', 'rodlick.ndovie@gmail.com', '0996687622', 500.00, 'MWK', 'paychangu', 'pending', '7c89a582a44f7dcadc9cf843bdfe4f006f5346afd9403e3f9e815ea7b1af7abb', NULL, NULL, NULL, 0, '2026-04-04 15:19:18', '2026-04-04 15:19:18'),
(4, 36, NULL, 'PP-36-1775325972-36f93c97', 'Rodlick Ndovie', 'rodlick.ndovie@gmail.com', '0996687622', 500.00, 'MWK', 'paychangu', 'pending', 'fe1cb0cece8ef1b13606d2c51f27bd6d3cb8b089c79d8691d5313f6f10bf7068', NULL, NULL, NULL, 0, '2026-04-04 18:06:12', '2026-04-04 18:06:12'),
(5, 36, NULL, 'PP-36-1775326775-89e531d6', 'Rodlick Ndovie', 'rodlick.ndovie@gmail.com', '0996687622', 500.00, 'MWK', 'paychangu', 'pending', '5d99d83698cfd3f8c9ea91f2d399ab364c0bfd4b08a965096a37e87aa3bb6d2c', NULL, NULL, NULL, 0, '2026-04-04 18:19:35', '2026-04-04 18:19:35'),
(6, 36, NULL, 'PP-36-1775327145-62ba5d7a', 'Rodlick Ndovie', 'rodlick.ndovie@gmail.com', '0996687622', 500.00, 'MWK', 'paychangu', 'verified', 'bde6ed3a22dfceb61c340aac3c33226786d1801395fd69cb7bbd81bc5bd558ac', '2026-04-04 18:26:21', '2026-04-04 18:26:21', '2026-04-04 18:26:21', 1, '2026-04-04 18:25:45', '2026-04-04 18:26:21'),
(7, 37, NULL, 'PP-37-1775327770-7528a0c2', 'Rodlick Ndovie', 'rodlick.ndovie@gmail.com', '0996687622', 5000.00, 'MWK', 'paychangu', 'verified', '1f795071dee3f5d32a4c41c9c050218d0716412297b715dd4f2842b01c7445db', '2026-04-04 18:36:41', '2026-04-04 18:36:41', '2026-04-04 18:38:27', 3, '2026-04-04 18:36:10', '2026-04-04 18:38:27'),
(8, 36, NULL, 'PP-36-1775328673-f296e23b', 'Rodlick Banda', 'rodlickndovie7@gmail.com', '0996687627', 500.00, 'MWK', 'paychangu', 'verified', '17dd2e713ca8703eb1b037a6974537a00d7d409dacee5c4a25d2e6547c7e5f14', '2026-04-04 18:51:46', '2026-04-04 18:51:46', '2026-04-04 18:51:47', 1, '2026-04-04 18:51:13', '2026-04-04 18:51:47'),
(9, 29, NULL, 'PP-29-1775328752-d911e8a3', 'Rodlick Banda', 'rodlickndovie7@gmail.com', '0996687627', 400.00, 'MWK', 'paychangu', 'verified', 'a47ffccad364c9940a13a811e2da9a7fefa5d29996b8e7fe6243f19d7eed7dd7', '2026-04-04 18:53:12', '2026-04-04 18:53:12', '2026-04-04 18:58:13', 2, '2026-04-04 18:52:32', '2026-04-04 18:58:13'),
(10, 30, NULL, 'PP-30-1775332770-8f068409', 'Rodlick Ndovie', 'rodlick.ndovie@gmail.com', '0996687622', 20000.00, 'MWK', 'paychangu', 'pending', 'c9ca35708a1ffa1f7cb4c355bb3d29ac85da315b0a2f1f5c5685e79fdab8de92', NULL, NULL, NULL, 0, '2026-04-04 19:59:30', '2026-04-04 19:59:30'),
(11, 30, NULL, 'PP-30-1775334336-05fbcb62', 'Rodlick Ndovie', 'rodlick.ndovie@gmail.com', '0996687622', 20000.00, 'MWK', 'paychangu', 'pending', '37815aab767e9d994859f9c7424d46c183da3bdb1c6ed5024905a6b13f6f5963', NULL, NULL, NULL, 0, '2026-04-04 20:25:36', '2026-04-04 20:25:36'),
(12, 30, NULL, 'PP-30-1775335147-3f07f46b', 'Rodlick Ndovie', 'rodlick.ndovie@gmail.com', '0996687622', 20000.00, 'MWK', 'paychangu', 'pending', '9d6007383c2ed64f3291b28be532d587caf2b89bbb798f702e706cf400dc2d70', NULL, NULL, NULL, 0, '2026-04-04 20:39:07', '2026-04-04 20:39:07'),
(13, 30, NULL, 'PP-30-1775335660-a11a4f8b', 'Rodlick Ndovie', 'rodlick.ndovie@gmail.com', '0996687622', 20000.00, 'MWK', 'paychangu', 'pending', 'd895fd82456ffc2b28478d4fcaafdb52cf7d4a32b9fabd49ffb08c330d704b96', NULL, NULL, NULL, 0, '2026-04-04 20:47:40', '2026-04-04 20:47:40'),
(14, 30, NULL, 'PP-30-1775335750-87b8a923', 'Rodlick Ndovie', 'rodlick.ndovie@gmail.com', '0996687622', 20000.00, 'MWK', 'paychangu', 'pending', '775670024e25f06edb3b394008567206f557c0ec7d00ecae2e567c3caf4df04c', NULL, NULL, NULL, 0, '2026-04-04 20:49:10', '2026-04-04 20:49:10'),
(15, 30, NULL, 'PP-30-1775335759-ee41e5b4', 'Rodlick Ndovie', 'rodlick.ndovie@gmail.com', '0996687622', 20000.00, 'MWK', 'paychangu', 'pending', '74f83d56c22a60f33c750541d48458a2dbcefa118c33600cb08a99d3c8f7e7e6', NULL, NULL, NULL, 0, '2026-04-04 20:49:19', '2026-04-04 20:49:19'),
(16, 30, NULL, 'PP-30-1775335783-3715e73c', 'Rodlick Ndovie', 'rodlick.ndovie@gmail.com', '0996687622', 20000.00, 'MWK', 'paychangu', 'pending', '550789a28bee876b755cc0858929092f79413428625f4368d7cd08baaadf9bc8', NULL, NULL, NULL, 0, '2026-04-04 20:49:43', '2026-04-04 20:49:43'),
(17, 30, NULL, 'PP-30-1775335889-29455404', 'Rodlick Ndovie', 'rodlick.ndovie@gmail.com', '0996687622', 20000.00, 'MWK', 'paychangu', 'pending', 'd0d36cf31bae5890f76efae2bdefde4e6160fe6c2c97f1ba7d04a7f661b535b4', NULL, NULL, NULL, 0, '2026-04-04 20:51:29', '2026-04-04 20:51:29'),
(18, 30, NULL, 'PP-30-1775335966-30e53953', 'Rodlick Ndovie', 'rodlick.ndovie@gmail.com', '0996687622', 20000.00, 'MWK', 'paychangu', 'pending', 'b0f53bd1c5fbab1a6e01f32ff74557486d443b015990cdae0e523e1b1771c3c6', NULL, NULL, NULL, 0, '2026-04-04 20:52:46', '2026-04-04 20:52:46'),
(19, 30, NULL, 'PP-30-1775338060-7d10a1da', 'Rodlick Ndovie', 'rodlick.ndovie@gmail.com', '0996687622', 20000.00, 'MWK', 'paychangu', 'pending', 'c048427c4afe85bcf6b13d3e063f4740c6e5f86a0da7900cb12e8ffe0fc1025e', NULL, NULL, NULL, 0, '2026-04-04 21:27:40', '2026-04-04 21:27:40'),
(20, 30, NULL, 'PP-30-1775338910-aa6a6e03', 'Rodlick Ndovie', 'rodlick.ndovie@gmail.com', '0996687622', 20000.00, 'MWK', 'paychangu', 'pending', '436e6481c5678b4a14c68c2cc8c6179311203c31186070d58670e9ad7d10b855', NULL, NULL, NULL, 0, '2026-04-04 21:41:50', '2026-04-04 21:41:50');

-- --------------------------------------------------------

--
-- Table structure for table `profile_views`
--

DROP TABLE IF EXISTS `profile_views`;
CREATE TABLE IF NOT EXISTS `profile_views` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `visitor_ip` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `viewed_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profile_views`
--

INSERT INTO `profile_views` (`id`, `user_id`, `visitor_ip`, `viewed_at`) VALUES
(1, 50, '::1', '2026-01-01 08:33:51'),
(2, 1, '::1', '2026-01-02 06:10:41'),
(3, 51, '::1', '2026-01-02 11:37:01'),
(4, 52, '::1', '2026-01-03 00:38:51');

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

DROP TABLE IF EXISTS `resources`;
CREATE TABLE IF NOT EXISTS `resources` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `resource_type` enum('video','past_paper') COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `curriculum` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'MANEB, Cambridge, IB, etc',
  `grade_level` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` int DEFAULT NULL COMMENT 'For past papers',
  `paper_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Paper 1, Paper 2, Mock, etc',
  `video_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'YouTube or video URL',
  `video_thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video_duration` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'e.g., 15:30',
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'For past paper PDFs',
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size` int DEFAULT NULL COMMENT 'File size in bytes',
  `description` text COLLATE utf8mb4_unicode_ci,
  `tags` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Comma-separated tags',
  `uploaded_by` int DEFAULT NULL COMMENT 'User ID of uploader',
  `is_approved` tinyint(1) DEFAULT '0',
  `is_featured` tinyint(1) DEFAULT '0',
  `view_count` int DEFAULT '0',
  `download_count` int DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_resource_type` (`resource_type`),
  KEY `idx_subject` (`subject`),
  KEY `idx_curriculum` (`curriculum`),
  KEY `idx_grade_level` (`grade_level`),
  KEY `idx_year` (`year`),
  KEY `idx_is_approved` (`is_approved`),
  KEY `idx_is_featured` (`is_featured`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resource_leads`
--

DROP TABLE IF EXISTS `resource_leads`;
CREATE TABLE IF NOT EXISTS `resource_leads` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `past_paper_id` int UNSIGNED DEFAULT NULL,
  `source` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'past_papers',
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `email` (`email`),
  KEY `past_paper_id` (`past_paper_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `tutor_id` int UNSIGNED NOT NULL COMMENT 'Which tutor is being reviewed',
  `reviewer_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Name of the reviewer',
  `rating` decimal(2,1) NOT NULL COMMENT 'Rating 1-5 stars',
  `comment` text COLLATE utf8mb4_general_ci COMMENT 'Optional review comment',
  `is_anonymous` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=show name, 1=anonymous',
  `created_at` datetime NOT NULL COMMENT 'When review was submitted',
  PRIMARY KEY (`id`),
  KEY `idx_tutor_id` (`tutor_id`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_rating` (`rating`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `tutor_id`, `reviewer_name`, `rating`, `comment`, `is_anonymous`, `created_at`) VALUES
(15, 74, 'Testing Review ', 3.0, 'Testing Review Comment', 0, '2026-01-05 16:23:43'),
(16, 88, 'Testing Review ', 5.0, 'Testing Testing ', 1, '2026-01-07 11:04:27'),
(17, 88, 'Testing Review 2', 3.0, 'Hel', 1, '2026-01-07 11:05:14'),
(18, 88, 'Testing Review 3', 1.0, 'Testing ', 1, '2026-01-07 11:05:43'),
(19, 88, 'Testing Review 4', 5.0, 'Test ', 1, '2026-01-07 11:06:09'),
(20, 88, 'Testing Review Final', 2.0, 'Jey', 1, '2026-01-07 11:06:35'),
(21, 88, 'Testing Review 5', 5.0, 'Hey', 1, '2026-01-07 11:07:12'),
(22, 106, 'Testing Review 2', 5.0, 'Hey', 1, '2026-01-31 16:39:23'),
(23, 158, 'Ephraim', 5.0, 'Best one I have had', 0, '2026-02-06 17:57:20'),
(24, 158, 'Nandwa ', 5.0, NULL, 1, '2026-02-06 20:29:59'),
(25, 158, 'David Lungu ', 5.0, NULL, 1, '2026-02-06 21:10:08'),
(26, 158, 'simeon', 5.0, NULL, 0, '2026-02-07 05:02:39'),
(27, 158, 'Daniella', 5.0, NULL, 0, '2026-02-07 05:03:05'),
(28, 158, 'prince', 5.0, NULL, 1, '2026-02-07 05:03:30'),
(29, 158, 'William', 5.0, NULL, 1, '2026-02-09 05:56:48'),
(30, 158, 'Tama', 5.0, NULL, 0, '2026-02-10 06:52:38'),
(31, 176, 'Emmy Banda', 5.0, 'Grace Munga has been teaching my 8-year-old twin boys reading skills, and I am very pleased with the progress. She is patient, encouraging, and effective—my children have become more confident and fluent readers under her guidance. I highly recommend her as a dedicated and skilled educator.', 1, '2026-02-17 08:33:51'),
(32, 107, 'Mercy Banda', 5.0, 'This teacher is amazing! I couldn\'t believe seeing my daughter improve with her performance within  a short period of time....if your looking for your child\'s tutor, I recommend Teacher Moloco. Thank you for your services and please keep it up with my daughter.', 0, '2026-03-05 06:09:21');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
CREATE TABLE IF NOT EXISTS `services` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `service_type` enum('tutoring_package','consultation','course_material','homework_help','exam_prep','professional_dev','other') COLLATE utf8mb4_general_ci DEFAULT 'tutoring_package',
  `categorization` varchar(100) COLLATE utf8mb4_general_ci DEFAULT 'General',
  `base_price` decimal(10,2) DEFAULT '0.00',
  `currency` varchar(10) COLLATE utf8mb4_general_ci DEFAULT 'MWK',
  `pricing_type` enum('fixed','hourly','package') COLLATE utf8mb4_general_ci DEFAULT 'fixed',
  `duration_hours` decimal(5,2) DEFAULT NULL,
  `max_participants` int DEFAULT '1',
  `features` text COLLATE utf8mb4_general_ci,
  `is_active` tinyint(1) DEFAULT '1',
  `sort_order` int DEFAULT '0',
  `created_by` int UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_categories`
--

DROP TABLE IF EXISTS `service_categories`;
CREATE TABLE IF NOT EXISTS `service_categories` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `parent_category` int UNSIGNED DEFAULT NULL,
  `sort_order` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_category` (`parent_category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `booking_id` int UNSIGNED DEFAULT NULL,
  `student_id` int UNSIGNED NOT NULL,
  `tutor_id` int UNSIGNED NOT NULL,
  `service_id` int UNSIGNED DEFAULT NULL,
  `session_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `actual_duration` int DEFAULT NULL,
  `status` enum('scheduled','in_progress','completed','cancelled','no_show') COLLATE utf8mb4_general_ci DEFAULT 'scheduled',
  `amount` decimal(10,2) DEFAULT '0.00',
  `currency` varchar(3) COLLATE utf8mb4_general_ci DEFAULT 'MWK',
  `payment_status` enum('pending','paid','refunded','failed') COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `notes` text COLLATE utf8mb4_general_ci,
  `student_feedback` text COLLATE utf8mb4_general_ci,
  `tutor_notes` text COLLATE utf8mb4_general_ci,
  `started_at` datetime DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_id` (`booking_id`),
  KEY `student_id` (`student_id`),
  KEY `tutor_id` (`tutor_id`),
  KEY `service_id` (`service_id`),
  KEY `session_date` (`session_date`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

DROP TABLE IF EXISTS `site_settings`;
CREATE TABLE IF NOT EXISTS `site_settings` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(191) COLLATE utf8mb4_general_ci NOT NULL,
  `setting_value` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_site_settings_setting_key` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `created_at`, `updated_at`) VALUES
(1, 'site_name', 'TutorConnect Malawi', '2026-01-01 20:41:08', '2026-03-30 09:18:45'),
(2, 'contact_email', 'info@tutorconnectmw.com', '2026-01-01 20:41:08', '2026-03-30 09:18:45'),
(3, 'support_phone', '+265 992 313 978', '2026-01-01 20:41:08', '2026-03-30 09:18:45'),
(4, 'support_address', 'C/O Visual Space Consulting, Bingu National Stadium, E16, Gulliver, Lilongwe', '2026-01-01 20:41:08', '2026-03-30 09:18:45'),
(5, 'timezone', 'Africa/Blantyre', '2026-01-01 20:41:08', '2026-03-30 09:18:45'),
(6, 'social_facebook_url', '', '2026-01-01 20:41:08', '2026-03-30 09:18:45'),
(7, 'social_twitter_url', '', '2026-01-01 20:41:08', '2026-03-30 09:18:45'),
(8, 'social_instagram_url', '', '2026-01-01 20:41:08', '2026-03-30 09:18:45'),
(9, 'site_logo', 'site-logo-20260101205103-7ff29532.png', '2026-01-01 20:42:21', '2026-01-01 20:51:03'),
(10, 'site_favicon', 'site-favicon-20260101213733-ee723bbc.jpeg', '2026-01-01 21:37:33', '2026-01-01 21:37:33'),
(11, 'japan_application_fee', '40000', '2026-03-26 13:59:29', '2026-03-30 09:18:45'),
(12, 'japan_processing_fee', '350000', '2026-03-26 13:59:29', '2026-03-30 09:18:45'),
(13, 'japan_applications_open', '1', '2026-03-26 14:30:55', '2026-03-30 09:18:45'),
(14, 'japan_applications_closed_message', 'Japan applications are currently closed. Please check back soon or contact support for help.', '2026-03-26 14:30:55', '2026-03-30 09:18:45');

-- --------------------------------------------------------

--
-- Table structure for table `subject_categories`
--

DROP TABLE IF EXISTS `subject_categories`;
CREATE TABLE IF NOT EXISTS `subject_categories` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `category` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `sort_order` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `category` (`category`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_plans`
--

DROP TABLE IF EXISTS `subscription_plans`;
CREATE TABLE IF NOT EXISTS `subscription_plans` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price_monthly` decimal(10,2) NOT NULL DEFAULT '0.00',
  `badge_level` enum('none','beginner','intermediate','advanced','expert','master') COLLATE utf8mb4_unicode_ci DEFAULT 'none',
  `search_ranking` enum('low','normal','priority','top') COLLATE utf8mb4_unicode_ci DEFAULT 'low',
  `district_spotlight_days` int DEFAULT '0',
  `max_profile_views` int DEFAULT '0',
  `max_clicks` int DEFAULT '0',
  `max_subjects` int DEFAULT '0',
  `max_reviews` int DEFAULT '0',
  `max_messages` int DEFAULT '0',
  `show_whatsapp` tinyint(1) DEFAULT '0',
  `email_marketing_access` tinyint(1) DEFAULT '0',
  `allow_video_upload` tinyint(1) DEFAULT '0',
  `allow_pdf_upload` tinyint(1) DEFAULT '0',
  `allow_announcements` tinyint(1) DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `sort_order` int DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `allow_video_solution` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `is_active` (`is_active`),
  KEY `sort_order` (`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscription_plans`
--

INSERT INTO `subscription_plans` (`id`, `name`, `description`, `price_monthly`, `badge_level`, `search_ranking`, `district_spotlight_days`, `max_profile_views`, `max_clicks`, `max_subjects`, `max_reviews`, `max_messages`, `show_whatsapp`, `email_marketing_access`, `allow_video_upload`, `allow_pdf_upload`, `allow_announcements`, `is_active`, `sort_order`, `created_at`, `updated_at`, `allow_video_solution`) VALUES
(1, 'Free Trial', 'Limited trial plan for new tutors to explore the platform.', 0.00, 'none', 'low', 0, 50, 20, 2, 5, 20, 0, 0, 0, 0, 0, 1, 0, '2026-01-03 04:05:30', '2026-01-10 09:42:31', 0),
(2, 'Basic', 'Starter plan for tutors building visibility and trust.', 2000.00, 'none', 'normal', 0, 100, 100, 2, 20, 100, 1, 1, 0, 0, 0, 1, 1, '2026-01-03 04:05:30', '2026-01-13 09:37:55', 0),
(3, 'Standard', 'Growth plan for active tutors seeking higher engagement.', 3000.00, 'none', 'priority', 7, 1000, 500, 4, 100, 500, 1, 0, 0, 1, 1, 1, 2, '2026-01-03 04:05:30', '2026-01-13 09:38:50', 1),
(4, 'Premium', 'Top-tier plan offering maximum exposure and advanced tools.', 5000.00, 'expert', 'top', 30, 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, 1, 3, '2026-01-03 04:05:30', '2026-01-06 02:23:50', 1);

-- --------------------------------------------------------

--
-- Table structure for table `subscription_renewal_reminders`
--

DROP TABLE IF EXISTS `subscription_renewal_reminders`;
CREATE TABLE IF NOT EXISTS `subscription_renewal_reminders` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `subscription_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `reminder_type` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `target_period_end` datetime NOT NULL,
  `recipient_email` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('sent','failed','skipped') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'sent',
  `error_message` text COLLATE utf8mb4_general_ci,
  `sent_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_subscription_reminder_window` (`subscription_id`,`reminder_type`,`target_period_end`),
  KEY `subscription_renewal_reminders_subscription_id_index` (`subscription_id`),
  KEY `subscription_renewal_reminders_user_id_index` (`user_id`),
  KEY `subscription_renewal_reminders_type_period_index` (`reminder_type`,`target_period_end`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subscription_renewal_reminders`
--

INSERT INTO `subscription_renewal_reminders` (`id`, `subscription_id`, `user_id`, `reminder_type`, `target_period_end`, `recipient_email`, `status`, `error_message`, `sent_at`, `created_at`, `updated_at`) VALUES
(1, 177, 107, '2_days', '2026-04-05 05:48:19', 'molocomonica063@gmail.com', 'sent', NULL, '2026-04-04 10:51:46', '2026-04-04 10:51:56', '2026-04-04 10:51:56'),
(2, 177, 107, '5_days', '2026-04-05 05:48:19', 'molocomonica063@gmail.com', 'skipped', 'Skipped because a more urgent reminder was already sent.', NULL, '2026-04-04 10:51:56', '2026-04-04 10:51:56'),
(3, 180, 107, '2_days', '2026-04-05 05:53:19', 'molocomonica063@gmail.com', 'sent', NULL, '2026-04-04 10:51:56', '2026-04-04 10:52:04', '2026-04-04 10:52:04'),
(4, 180, 107, '5_days', '2026-04-05 05:53:19', 'molocomonica063@gmail.com', 'skipped', 'Skipped because a more urgent reminder was already sent.', NULL, '2026-04-04 10:52:04', '2026-04-04 10:52:04');

-- --------------------------------------------------------

--
-- Table structure for table `tutors`
--

DROP TABLE IF EXISTS `tutors`;
CREATE TABLE IF NOT EXISTS `tutors` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `district` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `area` varchar(100) COLLATE utf8mb4_general_ci DEFAULT '',
  `experience_years` int DEFAULT '1',
  `rate` decimal(8,2) NOT NULL,
  `rate_type` enum('per session','per week','per month') COLLATE utf8mb4_general_ci DEFAULT 'per session',
  `teaching_mode` enum('Online Only','Physical Only','Both Online & Physical') COLLATE utf8mb4_general_ci DEFAULT 'Both Online & Physical',
  `bio` text COLLATE utf8mb4_general_ci,
  `bio_video` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cv_file` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `training_papers` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `whatsapp_number` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `best_call_time` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'Morning (8AM-12PM)',
  `is_verified` tinyint(1) DEFAULT '0',
  `is_employed` tinyint(1) DEFAULT '0',
  `school_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `subscription_plan` enum('Basic','Standard','Premium') COLLATE utf8mb4_general_ci DEFAULT 'Basic',
  `subscription_expires_at` date DEFAULT NULL,
  `rating` decimal(2,1) DEFAULT '0.0',
  `review_count` int DEFAULT '0',
  `search_count` int DEFAULT '0',
  `featured` tinyint(1) DEFAULT '0',
  `status` enum('pending','active','suspended','inactive') COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `district` (`district`),
  KEY `status` (`status`),
  KEY `is_verified` (`is_verified`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tutor_availability`
--

DROP TABLE IF EXISTS `tutor_availability`;
CREATE TABLE IF NOT EXISTS `tutor_availability` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `tutor_id` int UNSIGNED NOT NULL,
  `day_of_week` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') COLLATE utf8mb4_general_ci NOT NULL,
  `time_slot` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tutor_id` (`tutor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tutor_certificates`
--

DROP TABLE IF EXISTS `tutor_certificates`;
CREATE TABLE IF NOT EXISTS `tutor_certificates` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `tutor_id` int UNSIGNED NOT NULL,
  `certificate_name` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tutor_id` (`tutor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tutor_curricula`
--

DROP TABLE IF EXISTS `tutor_curricula`;
CREATE TABLE IF NOT EXISTS `tutor_curricula` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `tutor_id` int UNSIGNED NOT NULL,
  `curriculum_name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tutor_id` (`tutor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tutor_levels`
--

DROP TABLE IF EXISTS `tutor_levels`;
CREATE TABLE IF NOT EXISTS `tutor_levels` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `tutor_id` int UNSIGNED NOT NULL,
  `level_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tutor_id` (`tutor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tutor_messages`
--

DROP TABLE IF EXISTS `tutor_messages`;
CREATE TABLE IF NOT EXISTS `tutor_messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `conversation_id` varchar(50) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Unique identifier for conversation thread',
  `sender_id` int NOT NULL COMMENT 'User ID of message sender',
  `receiver_id` int NOT NULL COMMENT 'User ID of message receiver',
  `booking_id` int DEFAULT NULL COMMENT 'Optional link to booking/inquiry',
  `message` text COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Message content',
  `message_type` enum('text','image','file','system') COLLATE utf8mb4_general_ci DEFAULT 'text' COMMENT 'Type of message',
  `attachment_url` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'URL/path to attachment file',
  `attachment_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Original filename of attachment',
  `attachment_size` int DEFAULT NULL COMMENT 'File size in bytes',
  `is_read` tinyint(1) DEFAULT '0' COMMENT 'Whether message has been read by receiver',
  `read_at` datetime DEFAULT NULL COMMENT 'When message was read',
  `sent_at` datetime NOT NULL COMMENT 'When message was sent',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `conversation_id` (`conversation_id`),
  KEY `sender_id` (`sender_id`),
  KEY `receiver_id` (`receiver_id`),
  KEY `booking_id` (`booking_id`),
  KEY `is_read` (`is_read`),
  KEY `sent_at` (`sent_at`),
  KEY `conversation_sender` (`conversation_id`,`sender_id`),
  KEY `conversation_receiver` (`conversation_id`,`receiver_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tutor_messages`
--

INSERT INTO `tutor_messages` (`id`, `conversation_id`, `sender_id`, `receiver_id`, `booking_id`, `message`, `message_type`, `attachment_url`, `attachment_name`, `attachment_size`, `is_read`, `read_at`, `sent_at`, `created_at`, `updated_at`) VALUES
(1, '1_50', 1, 50, NULL, 'Hello! This is a test message from admin to trainer.', 'text', NULL, NULL, NULL, 0, NULL, '2026-01-02 23:52:50', '2026-01-02 23:52:50', '2026-01-02 23:52:50');

-- --------------------------------------------------------

--
-- Table structure for table `tutor_references`
--

DROP TABLE IF EXISTS `tutor_references`;
CREATE TABLE IF NOT EXISTS `tutor_references` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `tutor_id` int UNSIGNED NOT NULL,
  `referee_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `position` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tutor_id` (`tutor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tutor_sessions`
--

DROP TABLE IF EXISTS `tutor_sessions`;
CREATE TABLE IF NOT EXISTS `tutor_sessions` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `tutor_id` int UNSIGNED NOT NULL,
  `student_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `parent_email` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `parent_phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `subject` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `grade_level` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `session_date` date NOT NULL,
  `session_time` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `duration_hours` decimal(4,2) DEFAULT '1.00',
  `status` enum('scheduled','completed','cancelled','rescheduled','in_progress') COLLATE utf8mb4_general_ci DEFAULT 'scheduled',
  `session_type` enum('one-time','recurring') COLLATE utf8mb4_general_ci DEFAULT 'one-time',
  `recurrence_pattern` enum('daily','weekly','bi-weekly','monthly') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `recurrence_end_date` date DEFAULT NULL,
  `notes` text COLLATE utf8mb4_general_ci,
  `reminder_sent` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tutor_id` (`tutor_id`),
  KEY `parent_email` (`parent_email`),
  KEY `session_date` (`session_date`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tutor_subjects`
--

DROP TABLE IF EXISTS `tutor_subjects`;
CREATE TABLE IF NOT EXISTS `tutor_subjects` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `tutor_id` int UNSIGNED NOT NULL,
  `subject_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tutor_id` (`tutor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tutor_subscriptions`
--

DROP TABLE IF EXISTS `tutor_subscriptions`;
CREATE TABLE IF NOT EXISTS `tutor_subscriptions` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `plan_id` int UNSIGNED NOT NULL,
  `billing_months` int UNSIGNED NOT NULL DEFAULT '1',
  `status` enum('active','inactive','cancelled','expired','pending') COLLATE utf8mb4_general_ci DEFAULT 'active',
  `current_period_start` datetime NOT NULL,
  `current_period_end` datetime NOT NULL,
  `trial_end` datetime DEFAULT NULL,
  `cancel_at_period_end` tinyint(1) DEFAULT '0',
  `payment_method` enum('bank_transfer','mobile_money','card_payment','cash') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `payment_reference` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `payment_amount` decimal(15,2) DEFAULT NULL,
  `payment_date` datetime DEFAULT NULL,
  `payment_status` enum('pending','verified','rejected') COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `payment_proof_file` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `terms_accepted` tinyint(1) NOT NULL DEFAULT '0',
  `upgrading_from` int UNSIGNED DEFAULT NULL COMMENT 'Reference to the subscription being upgraded from',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `plan_id` (`plan_id`),
  KEY `status` (`status`),
  KEY `current_period_end` (`current_period_end`)
) ENGINE=InnoDB AUTO_INCREMENT=190 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tutor_subscriptions`
--

INSERT INTO `tutor_subscriptions` (`id`, `user_id`, `plan_id`, `billing_months`, `status`, `current_period_start`, `current_period_end`, `trial_end`, `cancel_at_period_end`, `payment_method`, `payment_reference`, `payment_amount`, `payment_date`, `payment_status`, `payment_proof_file`, `created_at`, `updated_at`, `terms_accepted`, `upgrading_from`) VALUES
(149, 94, 1, 1, 'cancelled', '2026-01-07 17:57:26', '2026-02-06 17:57:26', '2026-02-06 17:57:26', 0, '', NULL, 0.00, '2026-01-07 17:57:26', 'verified', NULL, '2026-01-07 17:57:26', '2026-01-10 12:48:59', 0, NULL),
(150, 97, 1, 1, 'expired', '2026-01-07 18:44:59', '2026-02-06 18:44:59', '2026-02-06 18:44:59', 0, '', NULL, 0.00, '2026-01-07 18:44:59', 'verified', NULL, '2026-01-07 18:44:59', '2026-04-04 10:51:46', 0, NULL),
(151, 103, 1, 1, 'expired', '2026-01-09 07:09:42', '2026-02-08 07:09:42', '2026-02-08 07:09:42', 0, '', NULL, 0.00, '2026-01-09 07:09:42', 'verified', NULL, '2026-01-09 07:09:42', '2026-04-04 10:51:46', 0, NULL),
(152, 106, 1, 1, 'cancelled', '2026-01-10 10:14:12', '2026-02-09 10:14:12', '2026-02-09 10:14:12', 0, '', NULL, 0.00, '2026-01-10 10:14:12', 'verified', NULL, '2026-01-10 10:14:12', '2026-01-10 12:01:03', 0, NULL),
(153, 106, 2, 1, 'pending', '2026-01-10 10:15:59', '2026-02-09 10:15:59', NULL, 0, '', 'TXN-106-1768040159-696226df45694', 50.00, '2026-01-10 10:15:59', 'pending', NULL, '2026-01-10 10:15:59', '2026-01-10 10:15:59', 1, 152),
(154, 106, 2, 1, 'expired', '2026-01-10 12:00:21', '2026-02-10 12:00:21', NULL, 0, '', 'TXN-106-1768046421-69623f55567c4', 50.00, '2026-01-10 12:00:21', 'verified', NULL, '2026-01-10 12:00:21', '2026-04-04 09:54:15', 1, 152),
(155, 94, 2, 1, 'expired', '2026-01-10 12:47:57', '2026-02-10 12:47:57', NULL, 0, '', 'TXN-94-1768049277-69624a7d54391', 50.00, '2026-01-10 12:47:57', 'verified', NULL, '2026-01-10 12:47:57', '2026-04-04 10:51:46', 1, 149),
(156, 113, 1, 1, 'expired', '2026-01-13 16:30:08', '2026-02-12 16:30:08', '2026-02-12 16:30:08', 0, '', NULL, 0.00, '2026-01-13 16:30:08', 'verified', NULL, '2026-01-13 16:30:08', '2026-04-04 10:51:46', 0, NULL),
(157, 114, 1, 1, 'expired', '2026-01-13 17:50:55', '2026-02-12 17:50:55', '2026-02-12 17:50:55', 0, '', NULL, 0.00, '2026-01-13 17:50:55', 'verified', NULL, '2026-01-13 17:50:55', '2026-04-04 10:51:46', 0, NULL),
(158, 107, 1, 1, 'cancelled', '2026-01-13 18:50:38', '2026-02-12 18:50:38', '2026-02-12 18:50:38', 0, '', NULL, 0.00, '2026-01-13 18:50:38', 'verified', NULL, '2026-01-13 18:50:38', '2026-03-05 06:02:34', 0, NULL),
(159, 121, 1, 1, 'expired', '2026-01-18 06:22:33', '2026-02-17 06:22:33', '2026-02-17 06:22:33', 0, '', NULL, 0.00, '2026-01-18 06:22:33', 'verified', NULL, '2026-01-18 06:22:33', '2026-04-04 10:51:46', 0, NULL),
(160, 123, 1, 1, 'expired', '2026-01-21 14:08:55', '2026-02-20 14:08:55', '2026-02-20 14:08:55', 0, '', NULL, 0.00, '2026-01-21 14:08:55', 'verified', NULL, '2026-01-21 14:08:55', '2026-04-04 10:51:46', 0, NULL),
(161, 126, 1, 1, 'expired', '2026-01-22 10:04:52', '2026-02-21 10:04:52', '2026-02-21 10:04:52', 0, '', NULL, 0.00, '2026-01-22 10:04:52', 'verified', NULL, '2026-01-22 10:04:52', '2026-04-04 10:51:46', 0, NULL),
(162, 117, 1, 1, 'expired', '2026-01-27 17:26:03', '2026-02-26 17:26:03', '2026-02-26 17:26:03', 0, '', NULL, 0.00, '2026-01-27 17:26:03', 'verified', NULL, '2026-01-27 17:26:03', '2026-04-04 10:51:46', 0, NULL),
(163, 140, 1, 1, 'expired', '2026-01-31 12:52:20', '2026-03-02 12:52:20', '2026-03-02 12:52:20', 0, '', NULL, 0.00, '2026-01-31 12:52:20', 'verified', NULL, '2026-01-31 12:52:20', '2026-04-04 10:51:46', 0, NULL),
(164, 153, 1, 1, 'expired', '2026-02-05 13:28:28', '2026-03-07 13:28:28', '2026-03-07 13:28:28', 0, '', NULL, 0.00, '2026-02-05 13:28:28', 'verified', NULL, '2026-02-05 13:27:42', '2026-04-04 10:51:46', 0, NULL),
(165, 155, 1, 1, 'expired', '2026-02-05 19:12:17', '2026-03-07 19:12:17', '2026-03-07 19:12:17', 0, '', NULL, 0.00, '2026-02-05 19:12:17', 'verified', NULL, '2026-02-05 19:12:17', '2026-04-04 10:51:46', 0, NULL),
(166, 158, 1, 1, 'cancelled', '2026-02-06 14:52:30', '2026-03-08 14:52:30', '2026-03-08 14:52:30', 0, '', NULL, 0.00, '2026-02-06 14:52:30', 'verified', NULL, '2026-02-06 14:52:30', '2026-02-06 17:34:33', 0, NULL),
(167, 158, 2, 1, 'cancelled', '2026-02-06 17:32:11', '2026-03-06 17:32:11', NULL, 0, '', 'TXN-158-1770399131-6986259b7edfd', 2000.00, '2026-02-06 17:32:11', 'verified', NULL, '2026-02-06 17:32:11', '2026-02-06 17:46:06', 1, 166),
(168, 158, 4, 1, 'cancelled', '2026-02-06 17:38:06', '2026-03-06 17:38:06', NULL, 0, '', 'TXN-158-1770399486-698626fe30777', 5000.00, '2026-02-06 17:38:06', 'verified', NULL, '2026-02-06 17:38:06', '2026-03-12 05:33:17', 1, 167),
(169, 161, 1, 1, 'expired', '2026-02-08 07:09:41', '2026-03-10 07:09:41', '2026-03-10 07:09:41', 0, '', NULL, 0.00, '2026-02-08 07:09:41', 'verified', NULL, '2026-02-08 07:09:41', '2026-04-04 10:51:46', 0, NULL),
(170, 165, 1, 1, 'expired', '2026-02-10 10:03:12', '2026-03-12 10:03:12', '2026-03-12 10:03:12', 0, '', NULL, 0.00, '2026-02-10 10:03:12', 'verified', NULL, '2026-02-10 10:03:12', '2026-04-04 10:51:46', 0, NULL),
(171, 175, 1, 1, 'expired', '2026-02-14 10:13:24', '2026-03-16 10:13:24', '2026-03-16 10:13:24', 0, '', NULL, 0.00, '2026-02-14 10:13:24', 'verified', NULL, '2026-02-14 10:13:24', '2026-04-04 10:51:46', 0, NULL),
(172, 176, 1, 1, 'cancelled', '2026-02-15 10:40:48', '2026-03-17 10:40:48', '2026-03-17 10:40:48', 0, '', NULL, 0.00, '2026-02-15 10:40:48', 'verified', NULL, '2026-02-15 10:40:48', '2026-02-15 11:11:37', 0, NULL),
(173, 176, 4, 1, 'expired', '2026-02-15 11:09:48', '2026-03-15 11:09:48', NULL, 0, '', 'TXN-176-1771153788-6991a97c420e8', 5000.00, '2026-02-15 11:09:48', 'verified', NULL, '2026-02-15 11:09:48', '2026-04-04 10:51:46', 1, 172),
(174, 184, 1, 1, 'expired', '2026-02-16 16:17:44', '2026-03-18 16:17:44', '2026-03-18 16:17:44', 0, '', NULL, 0.00, '2026-02-16 16:17:44', 'verified', NULL, '2026-02-16 16:17:44', '2026-04-04 10:51:46', 0, NULL),
(175, 147, 1, 1, 'cancelled', '2026-03-03 06:42:18', '2026-04-02 06:42:18', '2026-04-02 06:42:18', 0, '', NULL, 0.00, '2026-03-03 06:42:18', 'verified', NULL, '2026-03-03 06:42:18', '2026-03-03 06:47:37', 0, NULL),
(176, 147, 4, 1, 'expired', '2026-03-03 06:46:51', '2026-04-03 06:46:51', NULL, 0, '', 'TXN-147-1772520411-69a683dba62cf', 5000.00, '2026-03-03 06:46:51', 'verified', NULL, '2026-03-03 06:46:51', '2026-04-04 10:51:46', 1, 175),
(177, 107, 3, 1, 'expired', '2026-03-05 05:48:19', '2026-04-05 05:48:19', NULL, 0, '', 'TXN-107-1772689699-69a91923eedf9', 3000.00, '2026-03-05 05:48:19', 'verified', NULL, '2026-03-05 05:48:19', '2026-04-05 17:08:35', 1, 158),
(178, 107, 4, 1, 'pending', '2026-03-05 05:49:53', '2026-04-04 05:49:53', NULL, 0, '', 'TXN-107-1772689793-69a9198171595', 5000.00, '2026-03-05 05:49:53', 'pending', NULL, '2026-03-05 05:49:53', '2026-03-05 05:49:53', 1, 177),
(179, 107, 4, 1, 'pending', '2026-03-05 05:51:31', '2026-04-04 05:51:31', NULL, 0, '', 'TXN-107-1772689891-69a919e3af1c8', 5000.00, '2026-03-05 05:51:31', 'pending', NULL, '2026-03-05 05:51:31', '2026-03-05 05:51:31', 1, 177),
(180, 107, 4, 1, 'expired', '2026-03-05 05:53:19', '2026-04-05 05:53:19', NULL, 0, '', 'TXN-107-1772689999-69a91a4f74df6', 5000.00, '2026-03-05 05:53:19', 'verified', NULL, '2026-03-05 05:53:19', '2026-04-05 17:08:35', 1, 177),
(181, 158, 2, 1, 'cancelled', '2026-03-12 05:33:06', '2026-04-12 05:33:06', NULL, 0, '', 'TXN-158-1773293586-69b25012cdbfd', 2000.00, '2026-03-12 05:33:06', 'verified', NULL, '2026-03-12 05:33:06', '2026-03-12 05:34:24', 1, 168),
(182, 158, 4, 1, 'active', '2026-03-12 05:33:51', '2026-04-12 05:33:51', NULL, 0, '', 'TXN-158-1773293631-69b2503f4771c', 5000.00, '2026-03-12 05:33:51', 'verified', NULL, '2026-03-12 05:33:51', '2026-03-12 05:34:24', 1, 181),
(183, 207, 1, 1, 'active', '2026-03-14 11:34:41', '2026-04-13 11:34:41', '2026-04-13 11:34:41', 0, '', NULL, 0.00, '2026-03-14 11:34:41', 'verified', NULL, '2026-03-14 11:34:41', '2026-03-14 11:34:41', 0, NULL),
(184, 173, 1, 1, 'active', '2026-03-14 12:11:21', '2026-04-13 12:11:21', '2026-04-13 12:11:21', 0, '', NULL, 0.00, '2026-03-14 12:11:21', 'verified', NULL, '2026-03-14 12:11:21', '2026-03-14 12:11:21', 0, NULL),
(185, 183, 1, 1, 'active', '2026-03-15 08:11:31', '2026-04-14 08:11:31', '2026-04-14 08:11:31', 0, '', NULL, 0.00, '2026-03-15 08:11:31', 'verified', NULL, '2026-03-15 08:11:31', '2026-03-15 08:11:31', 0, NULL),
(186, 106, 4, 1, 'pending', '2026-03-25 13:36:44', '2026-04-24 13:36:44', NULL, 0, '', 'TXN-106-1774445804-69c3e4ec39bfc', 5000.00, '2026-03-25 13:36:44', 'pending', NULL, '2026-03-25 13:36:44', '2026-03-25 13:36:44', 1, 154),
(187, 106, 4, 1, 'pending', '2026-03-25 13:38:19', '2026-04-24 13:38:19', NULL, 0, '', 'TXN-106-1774445899-69c3e54b99685', 5000.00, '2026-03-25 13:38:19', 'pending', NULL, '2026-03-25 13:38:19', '2026-03-25 13:38:19', 1, 154),
(188, 106, 4, 1, 'pending', '2026-03-25 16:36:54', '2026-04-24 16:36:54', NULL, 0, '', 'TXN-106-1774456614-69c40f261c098', 5000.00, '2026-03-25 16:36:54', 'pending', NULL, '2026-03-25 16:36:54', '2026-03-25 16:36:54', 1, 154),
(189, 106, 2, 3, 'active', '2026-04-04 10:26:50', '2026-07-04 10:26:50', NULL, 0, '', 'TXN-106-1775298410-69d0e76a6bccf', 6000.00, '2026-04-04 10:26:50', 'verified', NULL, '2026-04-04 10:26:50', '2026-04-04 10:27:39', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tutor_videos`
--

DROP TABLE IF EXISTS `tutor_videos`;
CREATE TABLE IF NOT EXISTS `tutor_videos` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `tutor_id` int UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `video_embed_code` text COLLATE utf8mb4_general_ci COMMENT 'YouTube/Vimeo embed iframe code',
  `video_platform` enum('youtube','vimeo') COLLATE utf8mb4_general_ci DEFAULT 'youtube',
  `video_id` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Platform unique video ID',
  `exam_body` enum('MANEB','Cambridge','GCSE','IELTS','Other') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `subject` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `topic` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `problem_year` int DEFAULT NULL,
  `status` enum('pending_review','approved','rejected') COLLATE utf8mb4_general_ci DEFAULT 'pending_review',
  `featured_level` enum('none','standard','premium_featured') COLLATE utf8mb4_general_ci DEFAULT 'none',
  `view_count` int UNSIGNED DEFAULT '0',
  `submitted_at` datetime DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `rejection_reason` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_tutor_id` (`tutor_id`),
  KEY `idx_subject` (`subject`),
  KEY `idx_exam_body` (`exam_body`),
  KEY `idx_status` (`status`),
  KEY `idx_featured_level` (`featured_level`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usage_tracking`
--

DROP TABLE IF EXISTS `usage_tracking`;
CREATE TABLE IF NOT EXISTS `usage_tracking` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `metric_type` enum('profile_views','contact_clicks','messages') COLLATE utf8mb4_general_ci NOT NULL,
  `metric_value` int NOT NULL DEFAULT '1',
  `reference_id` int UNSIGNED DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `tracked_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `period_start` date NOT NULL,
  `period_end` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_tracked_at` (`tracked_at`),
  KEY `idx_period` (`period_start`,`period_end`),
  KEY `idx_user_metric_period` (`user_id`,`metric_type`,`period_start`)
) ;

--
-- Dumping data for table `usage_tracking`
--

INSERT INTO `usage_tracking` (`id`, `user_id`, `metric_type`, `metric_value`, `reference_id`, `metadata`, `tracked_at`, `period_start`, `period_end`) VALUES
(523, 97, 'profile_views', 1, NULL, '{\"visitor_ip\":\"105.234.176.85\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-01-07 18:54:24', '2026-01-01', '2026-01-31'),
(524, 97, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.7.230\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-01-07 18:55:16', '2026-01-01', '2026-01-31'),
(525, 97, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.110.232\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-01-07 19:51:43', '2026-01-01', '2026-01-31'),
(526, 97, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.104.94\",\"user_agent\":{},\"referrer\":null}', '2026-01-07 19:53:18', '2026-01-01', '2026-01-31'),
(528, 97, 'profile_views', 1, NULL, '{\"visitor_ip\":\"216.234.217.160\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-01-08 04:26:27', '2026-01-01', '2026-01-31'),
(534, 97, 'profile_views', 1, NULL, '{\"visitor_ip\":\"129.205.24.184\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors?name=&district=&curriculum=&subject=&level=&teaching_mode=both&sort_by=rating\"}', '2026-01-08 05:27:44', '2026-01-01', '2026-01-31'),
(536, 97, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.11.7\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-01-08 10:01:45', '2026-01-01', '2026-01-31'),
(538, 97, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.64.10.87\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-01-08 11:41:06', '2026-01-01', '2026-01-31'),
(539, 97, 'profile_views', 1, NULL, '{\"visitor_ip\":\"41.47.58.80\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors?district=Blantyre&curriculum=Cambridge&subject=Mathematics\"}', '2026-01-08 16:43:53', '2026-01-01', '2026-01-31'),
(540, 97, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.92.97\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-01-08 16:46:29', '2026-01-01', '2026-01-31'),
(543, 97, 'profile_views', 1, NULL, '{\"visitor_ip\":\"216.234.217.134\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-01-08 19:23:13', '2026-01-01', '2026-01-31'),
(544, 97, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.117.163\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-01-08 19:29:52', '2026-01-01', '2026-01-31'),
(546, 97, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.9.91\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-01-09 09:28:07', '2026-01-01', '2026-01-31'),
(547, 97, 'profile_views', 1, NULL, '{\"visitor_ip\":\"46.210.163.75\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-01-10 11:42:08', '2026-01-01', '2026-01-31'),
(548, 97, 'profile_views', 1, NULL, '{\"visitor_ip\":\"66.249.93.229\",\"user_agent\":{},\"referrer\":null}', '2026-01-10 11:42:11', '2026-01-01', '2026-01-31'),
(549, 97, 'profile_views', 1, NULL, '{\"visitor_ip\":\"74.125.210.196\",\"user_agent\":{},\"referrer\":null}', '2026-01-10 11:42:12', '2026-01-01', '2026-01-31'),
(550, 103, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.4.188\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-01-10 17:14:12', '2026-01-01', '2026-01-31'),
(551, 103, 'profile_views', 1, NULL, '{\"visitor_ip\":\"66.249.74.106\",\"user_agent\":{},\"referrer\":null}', '2026-01-11 11:27:28', '2026-01-01', '2026-01-31'),
(553, 94, 'profile_views', 1, NULL, '{\"visitor_ip\":\"66.249.74.108\",\"user_agent\":{},\"referrer\":null}', '2026-01-11 11:48:42', '2026-01-01', '2026-01-31'),
(554, 97, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.9.187\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-01-11 18:20:06', '2026-01-01', '2026-01-31'),
(555, 97, 'profile_views', 1, NULL, '{\"visitor_ip\":\"157.55.39.204\",\"user_agent\":{},\"referrer\":null}', '2026-01-12 01:25:59', '2026-01-01', '2026-01-31'),
(556, 103, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.118.128\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-01-12 19:49:04', '2026-01-01', '2026-01-31'),
(557, 113, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.113.243\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-01-13 16:38:45', '2026-01-01', '2026-01-31'),
(558, 114, 'profile_views', 1, NULL, '{\"visitor_ip\":\"105.234.182.67\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-01-13 18:15:54', '2026-01-01', '2026-01-31'),
(559, 114, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.4.4\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-01-13 18:53:40', '2026-01-01', '2026-01-31'),
(560, 97, 'profile_views', 1, NULL, '{\"visitor_ip\":\"105.234.181.100\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors?name=&district=&curriculum=&subject=&level=&teaching_mode=&sort_by=rating\"}', '2026-01-13 19:01:43', '2026-01-01', '2026-01-31'),
(562, 114, 'profile_views', 1, NULL, '{\"visitor_ip\":\"182.69.178.224\",\"user_agent\":{},\"referrer\":\"http:\\/\\/tutorconnectmw.com\"}', '2026-01-13 20:58:22', '2026-01-01', '2026-01-31'),
(564, 97, 'profile_views', 1, NULL, '{\"visitor_ip\":\"40.77.167.154\",\"user_agent\":{},\"referrer\":null}', '2026-01-16 06:27:42', '2026-01-01', '2026-01-31'),
(566, 114, 'profile_views', 1, NULL, '{\"visitor_ip\":\"122.162.145.86\",\"user_agent\":{},\"referrer\":\"http:\\/\\/tutorconnectmw.com\"}', '2026-01-16 11:01:48', '2026-01-01', '2026-01-31'),
(567, 97, 'profile_views', 1, NULL, '{\"visitor_ip\":\"52.167.144.18\",\"user_agent\":{},\"referrer\":null}', '2026-01-16 17:41:43', '2026-01-01', '2026-01-31'),
(568, 114, 'profile_views', 1, NULL, '{\"visitor_ip\":\"52.167.144.202\",\"user_agent\":{},\"referrer\":null}', '2026-01-17 09:49:45', '2026-01-01', '2026-01-31'),
(569, 114, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.101.122\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-01-17 10:54:19', '2026-01-01', '2026-01-31'),
(570, 103, 'profile_views', 1, NULL, '{\"visitor_ip\":\"105.12.4.128\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-01-17 14:49:04', '2026-01-01', '2026-01-31'),
(571, 113, 'profile_views', 1, NULL, '{\"visitor_ip\":\"52.167.144.171\",\"user_agent\":{},\"referrer\":null}', '2026-01-17 14:53:25', '2026-01-01', '2026-01-31'),
(572, 103, 'profile_views', 1, NULL, '{\"visitor_ip\":\"105.234.176.39\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-01-18 05:01:07', '2026-01-01', '2026-01-31'),
(573, 121, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.107.216\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-01-18 18:41:38', '2026-01-01', '2026-01-31'),
(574, 114, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.107.216\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-01-18 18:41:46', '2026-01-01', '2026-01-31'),
(575, 121, 'profile_views', 1, NULL, '{\"visitor_ip\":\"105.234.181.113\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-01-18 18:49:08', '2026-01-01', '2026-01-31'),
(576, 114, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.3.82\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-01-18 18:49:46', '2026-01-01', '2026-01-31'),
(578, 113, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.3.82\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-01-18 18:53:10', '2026-01-01', '2026-01-31'),
(580, 97, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.112.122\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-01-18 19:20:52', '2026-01-01', '2026-01-31'),
(581, 113, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.90.155\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-01-18 19:29:01', '2026-01-01', '2026-01-31'),
(582, 114, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.90.155\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-01-18 19:31:29', '2026-01-01', '2026-01-31'),
(583, 121, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.113.75\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors?district=Blantyre&curriculum=MANEB&subject=Science+and+Technology\"}', '2026-01-18 20:12:13', '2026-01-01', '2026-01-31'),
(584, 121, 'profile_views', 1, NULL, '{\"visitor_ip\":\"45.215.249.245\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-01-18 20:44:53', '2026-01-01', '2026-01-31'),
(585, 97, 'profile_views', 1, NULL, '{\"visitor_ip\":\"73.141.5.18\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors?name=&district=&curriculum=&subject=&level=&teaching_mode=&sort_by=rating\"}', '2026-01-18 23:33:28', '2026-01-01', '2026-01-31'),
(586, 97, 'profile_views', 1, NULL, '{\"visitor_ip\":\"207.46.13.87\",\"user_agent\":{},\"referrer\":null}', '2026-01-18 23:36:26', '2026-01-01', '2026-01-31'),
(587, 97, 'profile_views', 1, NULL, '{\"visitor_ip\":\"157.55.39.192\",\"user_agent\":{},\"referrer\":null}', '2026-01-18 23:50:39', '2026-01-01', '2026-01-31'),
(588, 121, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.96.176\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-01-19 02:07:42', '2026-01-01', '2026-01-31'),
(589, 114, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.2.145\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors?district=Lilongwe&curriculum=MANEB\"}', '2026-01-19 06:51:41', '2026-01-01', '2026-01-31'),
(591, 97, 'profile_views', 1, NULL, '{\"visitor_ip\":\"207.46.13.86\",\"user_agent\":{},\"referrer\":null}', '2026-01-19 07:03:08', '2026-01-01', '2026-01-31'),
(592, 114, 'profile_views', 1, NULL, '{\"visitor_ip\":\"105.234.181.16\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-01-19 07:29:34', '2026-01-01', '2026-01-31'),
(594, 97, 'profile_views', 1, NULL, '{\"visitor_ip\":\"207.46.13.154\",\"user_agent\":{},\"referrer\":null}', '2026-01-19 20:48:03', '2026-01-01', '2026-01-31'),
(596, 97, 'profile_views', 1, NULL, '{\"visitor_ip\":\"157.55.39.203\",\"user_agent\":{},\"referrer\":null}', '2026-01-20 14:21:26', '2026-01-01', '2026-01-31'),
(597, 121, 'profile_views', 1, NULL, '{\"visitor_ip\":\"192.36.24.172\",\"user_agent\":{},\"referrer\":null}', '2026-01-21 06:20:02', '2026-01-01', '2026-01-31'),
(598, 113, 'profile_views', 1, NULL, '{\"visitor_ip\":\"122.162.145.201\",\"user_agent\":{},\"referrer\":\"http:\\/\\/tutorconnectmw.com\"}', '2026-01-21 09:28:57', '2026-01-01', '2026-01-31'),
(599, 113, 'profile_views', 1, NULL, '{\"visitor_ip\":\"40.77.167.241\",\"user_agent\":{},\"referrer\":null}', '2026-01-21 10:16:57', '2026-01-01', '2026-01-31'),
(600, 97, 'profile_views', 1, NULL, '{\"visitor_ip\":\"40.77.167.255\",\"user_agent\":{},\"referrer\":null}', '2026-01-21 13:05:05', '2026-01-01', '2026-01-31'),
(602, 123, 'profile_views', 1, NULL, '{\"visitor_ip\":\"105.234.178.187\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-01-21 14:27:44', '2026-01-01', '2026-01-31'),
(603, 114, 'profile_views', 1, NULL, '{\"visitor_ip\":\"105.234.179.230\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-01-21 14:49:14', '2026-01-01', '2026-01-31'),
(604, 114, 'profile_views', 1, NULL, '{\"visitor_ip\":\"52.167.144.188\",\"user_agent\":{},\"referrer\":null}', '2026-01-21 23:16:07', '2026-01-01', '2026-01-31'),
(605, 113, 'profile_views', 1, NULL, '{\"visitor_ip\":\"45.118.167.94\",\"user_agent\":{},\"referrer\":\"http:\\/\\/tutorconnectmw.com\"}', '2026-01-22 04:05:18', '2026-01-01', '2026-01-31'),
(606, 97, 'profile_views', 1, NULL, '{\"visitor_ip\":\"52.167.144.210\",\"user_agent\":{},\"referrer\":null}', '2026-01-22 05:11:46', '2026-01-01', '2026-01-31'),
(607, 97, 'profile_views', 1, NULL, '{\"visitor_ip\":\"52.167.144.25\",\"user_agent\":{},\"referrer\":null}', '2026-01-22 06:03:41', '2026-01-01', '2026-01-31'),
(609, 97, 'profile_views', 1, NULL, '{\"visitor_ip\":\"216.234.217.31\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-01-22 08:48:10', '2026-01-01', '2026-01-31'),
(610, 97, 'profile_views', 1, NULL, '{\"visitor_ip\":\"52.167.144.225\",\"user_agent\":{},\"referrer\":null}', '2026-01-22 09:03:57', '2026-01-01', '2026-01-31'),
(611, 113, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.11.50\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-01-22 15:07:07', '2026-01-01', '2026-01-31'),
(612, 113, 'profile_views', 1, NULL, '{\"visitor_ip\":\"223.233.73.128\",\"user_agent\":{},\"referrer\":\"http:\\/\\/tutorconnectmw.com\"}', '2026-01-22 18:54:47', '2026-01-01', '2026-01-31'),
(613, 113, 'profile_views', 1, NULL, '{\"visitor_ip\":\"52.167.144.236\",\"user_agent\":{},\"referrer\":null}', '2026-01-23 03:56:14', '2026-01-01', '2026-01-31'),
(615, 121, 'profile_views', 1, NULL, '{\"visitor_ip\":\"216.234.217.224\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-01-23 11:14:28', '2026-01-01', '2026-01-31'),
(617, 94, 'profile_views', 1, NULL, '{\"visitor_ip\":\"52.167.144.203\",\"user_agent\":{},\"referrer\":null}', '2026-01-23 16:15:29', '2026-01-01', '2026-01-31'),
(618, 114, 'profile_views', 1, NULL, '{\"visitor_ip\":\"40.77.167.235\",\"user_agent\":{},\"referrer\":null}', '2026-01-23 17:00:18', '2026-01-01', '2026-01-31'),
(620, 97, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.110.167\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors?name=&district=Blantyre&curriculum=MANEB&subject=Mathematics&level=&teaching_mode=both&sort_by=rating\"}', '2026-01-24 04:44:42', '2026-01-01', '2026-01-31'),
(621, 97, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.11.8\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-01-24 06:42:43', '2026-01-01', '2026-01-31'),
(623, 121, 'profile_views', 1, NULL, '{\"visitor_ip\":\"192.36.109.102\",\"user_agent\":{},\"referrer\":null}', '2026-01-24 07:02:55', '2026-01-01', '2026-01-31'),
(624, 126, 'profile_views', 1, NULL, '{\"visitor_ip\":\"105.234.177.4\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-01-24 07:26:14', '2026-01-01', '2026-01-31'),
(625, 123, 'profile_views', 1, NULL, '{\"visitor_ip\":\"105.234.174.139\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-01-24 07:26:20', '2026-01-01', '2026-01-31'),
(626, 126, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.106.136\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-01-24 07:27:48', '2026-01-01', '2026-01-31'),
(627, 123, 'profile_views', 1, NULL, '{\"visitor_ip\":\"105.234.177.4\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-01-24 08:23:35', '2026-01-01', '2026-01-31'),
(628, 121, 'profile_views', 1, NULL, '{\"visitor_ip\":\"105.234.177.4\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-01-24 08:24:35', '2026-01-01', '2026-01-31'),
(629, 123, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.111.75\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-01-24 18:06:18', '2026-01-01', '2026-01-31'),
(630, 123, 'profile_views', 1, NULL, '{\"visitor_ip\":\"52.167.144.206\",\"user_agent\":{},\"referrer\":null}', '2026-01-24 20:25:23', '2026-01-01', '2026-01-31'),
(631, 113, 'profile_views', 1, NULL, '{\"visitor_ip\":\"40.77.167.67\",\"user_agent\":{},\"referrer\":null}', '2026-01-24 22:00:32', '2026-01-01', '2026-01-31'),
(632, 126, 'profile_views', 1, NULL, '{\"visitor_ip\":\"85.208.96.204\",\"user_agent\":{},\"referrer\":null}', '2026-01-25 08:20:33', '2026-01-01', '2026-01-31'),
(633, 114, 'profile_views', 1, NULL, '{\"visitor_ip\":\"85.208.96.202\",\"user_agent\":{},\"referrer\":null}', '2026-01-25 12:36:13', '2026-01-01', '2026-01-31'),
(634, 113, 'profile_views', 1, NULL, '{\"visitor_ip\":\"185.191.171.16\",\"user_agent\":{},\"referrer\":null}', '2026-01-25 13:20:15', '2026-01-01', '2026-01-31'),
(635, 121, 'profile_views', 1, NULL, '{\"visitor_ip\":\"185.191.171.5\",\"user_agent\":{},\"referrer\":null}', '2026-01-25 22:22:49', '2026-01-01', '2026-01-31'),
(636, 121, 'profile_views', 1, NULL, '{\"visitor_ip\":\"93.158.90.30\",\"user_agent\":{},\"referrer\":null}', '2026-01-27 03:08:40', '2026-01-01', '2026-01-31'),
(637, 114, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.4.204\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-01-27 09:59:38', '2026-01-01', '2026-01-31'),
(638, 123, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.107.220\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-01-27 18:29:29', '2026-01-01', '2026-01-31'),
(639, 123, 'contact_clicks', 1, NULL, '{\"visitor_ip\":\"102.70.107.220\",\"contact_type\":\"email\",\"reference_type\":\"video\",\"reference_id\":null,\"user_agent\":\"Mozilla\\/5.0 (Linux; Android 10; K) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/144.0.0.0 Mobile Safari\\/537.36\",\"timestamp\":\"2026-01-27 18:30:10\"}', '2026-01-27 18:30:10', '2026-01-01', '2026-01-31'),
(640, 94, 'profile_views', 1, NULL, '{\"visitor_ip\":\"185.191.171.2\",\"user_agent\":{},\"referrer\":null}', '2026-01-28 01:46:35', '2026-01-01', '2026-01-31'),
(641, 103, 'profile_views', 1, NULL, '{\"visitor_ip\":\"185.191.171.17\",\"user_agent\":{},\"referrer\":null}', '2026-01-28 06:23:06', '2026-01-01', '2026-01-31'),
(643, 97, 'profile_views', 1, NULL, '{\"visitor_ip\":\"185.191.171.13\",\"user_agent\":{},\"referrer\":null}', '2026-01-28 10:29:43', '2026-01-01', '2026-01-31'),
(644, 113, 'profile_views', 1, NULL, '{\"visitor_ip\":\"223.233.71.32\",\"user_agent\":{},\"referrer\":\"http:\\/\\/tutorconnectmw.com\"}', '2026-01-28 14:10:34', '2026-01-01', '2026-01-31'),
(645, 123, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.107.69\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-01-29 11:09:05', '2026-01-01', '2026-01-31'),
(646, 113, 'profile_views', 1, NULL, '{\"visitor_ip\":\"105.234.182.128\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-01-29 12:01:34', '2026-01-01', '2026-01-31'),
(647, 126, 'profile_views', 1, NULL, '{\"visitor_ip\":\"82.80.249.156\",\"user_agent\":{},\"referrer\":\"http:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-01-29 13:43:41', '2026-01-01', '2026-01-31'),
(648, 123, 'profile_views', 1, NULL, '{\"visitor_ip\":\"82.80.249.156\",\"user_agent\":{},\"referrer\":\"http:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-01-29 13:43:41', '2026-01-01', '2026-01-31'),
(649, 121, 'profile_views', 1, NULL, '{\"visitor_ip\":\"82.80.249.156\",\"user_agent\":{},\"referrer\":\"http:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-01-29 13:43:43', '2026-01-01', '2026-01-31'),
(650, 114, 'profile_views', 1, NULL, '{\"visitor_ip\":\"82.80.249.156\",\"user_agent\":{},\"referrer\":\"http:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-01-29 13:43:44', '2026-01-01', '2026-01-31'),
(651, 117, 'profile_views', 1, NULL, '{\"visitor_ip\":\"82.80.249.156\",\"user_agent\":{},\"referrer\":\"http:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-01-29 13:43:44', '2026-01-01', '2026-01-31'),
(652, 113, 'profile_views', 1, NULL, '{\"visitor_ip\":\"82.80.249.156\",\"user_agent\":{},\"referrer\":\"http:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-01-29 13:43:45', '2026-01-01', '2026-01-31'),
(653, 97, 'profile_views', 1, NULL, '{\"visitor_ip\":\"82.80.249.156\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-01-29 13:44:05', '2026-01-01', '2026-01-31'),
(655, 94, 'profile_views', 1, NULL, '{\"visitor_ip\":\"82.80.249.156\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-01-29 13:44:05', '2026-01-01', '2026-01-31'),
(656, 103, 'profile_views', 1, NULL, '{\"visitor_ip\":\"82.80.249.156\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-01-29 13:44:06', '2026-01-01', '2026-01-31'),
(658, 121, 'profile_views', 1, NULL, '{\"visitor_ip\":\"5.198.253.239\",\"user_agent\":{},\"referrer\":null}', '2026-01-29 14:17:02', '2026-01-01', '2026-01-31'),
(659, 113, 'profile_views', 1, NULL, '{\"visitor_ip\":\"66.249.65.44\",\"user_agent\":{},\"referrer\":null}', '2026-01-29 14:45:38', '2026-01-01', '2026-01-31'),
(660, 117, 'profile_views', 1, NULL, '{\"visitor_ip\":\"52.167.144.202\",\"user_agent\":{},\"referrer\":null}', '2026-01-29 18:14:07', '2026-01-01', '2026-01-31'),
(661, 126, 'profile_views', 1, NULL, '{\"visitor_ip\":\"129.140.0.29\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-01-29 20:00:02', '2026-01-01', '2026-01-31'),
(662, 126, 'profile_views', 1, NULL, '{\"visitor_ip\":\"40.77.167.235\",\"user_agent\":{},\"referrer\":null}', '2026-01-30 08:06:47', '2026-01-01', '2026-01-31'),
(663, 114, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.2.250\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-01-30 08:22:26', '2026-01-01', '2026-01-31'),
(664, 123, 'profile_views', 1, NULL, '{\"visitor_ip\":\"105.234.162.4\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-01-30 12:28:07', '2026-01-01', '2026-01-31'),
(665, 123, 'profile_views', 1, NULL, '{\"visitor_ip\":\"40.77.167.13\",\"user_agent\":{},\"referrer\":null}', '2026-01-30 12:36:58', '2026-01-01', '2026-01-31'),
(666, 117, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.100.93\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-01-30 12:38:22', '2026-01-01', '2026-01-31'),
(667, 117, 'contact_clicks', 1, NULL, '{\"visitor_ip\":\"102.70.100.93\",\"contact_type\":\"phone\",\"reference_type\":\"video\",\"reference_id\":null,\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/144.0.0.0 Safari\\/537.36\",\"timestamp\":\"2026-01-30 12:38:28\"}', '2026-01-30 12:38:28', '2026-01-01', '2026-01-31'),
(668, 121, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.99.124\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-01-30 14:56:02', '2026-01-01', '2026-01-31'),
(669, 117, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.101.207\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-01-30 18:09:20', '2026-01-01', '2026-01-31'),
(670, 117, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.6.188\",\"user_agent\":{},\"referrer\":null}', '2026-01-31 05:07:28', '2026-01-01', '2026-01-31'),
(671, 117, 'profile_views', 1, NULL, '{\"visitor_ip\":\"66.249.93.229\",\"user_agent\":{},\"referrer\":null}', '2026-01-31 05:07:29', '2026-01-01', '2026-01-31'),
(672, 117, 'profile_views', 1, NULL, '{\"visitor_ip\":\"66.249.93.230\",\"user_agent\":{},\"referrer\":null}', '2026-01-31 05:07:29', '2026-01-01', '2026-01-31'),
(673, 126, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.104.144\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/?fbclid=IwZXh0bgNhZW0CMTEAc3J0YwZhcHBfaWQPMjc1MjU0NjkyNTk4Mjc5AAEeWA9O9hL980CgPEqoBith18lTmkwLrQncmAAWmIaY2t45wIcEm1E9RuUN7kg_aem_mE-KlOuJGWzPiOwHYj9oUQ\"}', '2026-01-31 06:24:46', '2026-01-01', '2026-01-31'),
(676, 121, 'profile_views', 1, NULL, '{\"visitor_ip\":\"194.132.201.114\",\"user_agent\":{},\"referrer\":null}', '2026-01-31 21:18:25', '2026-01-01', '2026-01-31'),
(677, 121, 'profile_views', 1, NULL, '{\"visitor_ip\":\"192.176.127.46\",\"user_agent\":{},\"referrer\":null}', '2026-01-31 22:53:53', '2026-01-01', '2026-01-31'),
(678, 103, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.11.42\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-02-01 06:35:34', '2026-02-01', '2026-02-28'),
(680, 140, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.11.42\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-02-01 06:36:56', '2026-02-01', '2026-02-28'),
(681, 123, 'profile_views', 1, NULL, '{\"visitor_ip\":\"40.77.167.58\",\"user_agent\":{},\"referrer\":null}', '2026-02-01 13:31:34', '2026-02-01', '2026-02-28'),
(682, 140, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.11.88\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-01 15:17:34', '2026-02-01', '2026-02-28'),
(683, 140, 'profile_views', 1, NULL, '{\"visitor_ip\":\"105.234.180.13\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-02 12:58:59', '2026-02-01', '2026-02-28'),
(684, 123, 'profile_views', 1, NULL, '{\"visitor_ip\":\"105.234.180.13\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-02 13:03:21', '2026-02-01', '2026-02-28'),
(685, 126, 'profile_views', 1, NULL, '{\"visitor_ip\":\"105.234.178.216\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-02 14:34:20', '2026-02-01', '2026-02-28'),
(686, 126, 'profile_views', 1, NULL, '{\"visitor_ip\":\"40.77.167.71\",\"user_agent\":{},\"referrer\":null}', '2026-02-02 16:14:17', '2026-02-01', '2026-02-28'),
(687, 140, 'profile_views', 1, NULL, '{\"visitor_ip\":\"52.167.144.172\",\"user_agent\":{},\"referrer\":null}', '2026-02-02 17:50:19', '2026-02-01', '2026-02-28'),
(688, 126, 'profile_views', 1, NULL, '{\"visitor_ip\":\"185.191.171.2\",\"user_agent\":{},\"referrer\":null}', '2026-02-02 20:01:19', '2026-02-01', '2026-02-28'),
(689, 113, 'profile_views', 1, NULL, '{\"visitor_ip\":\"185.191.171.6\",\"user_agent\":{},\"referrer\":null}', '2026-02-02 22:07:58', '2026-02-01', '2026-02-28'),
(690, 114, 'profile_views', 1, NULL, '{\"visitor_ip\":\"85.208.96.196\",\"user_agent\":{},\"referrer\":null}', '2026-02-03 01:53:58', '2026-02-01', '2026-02-28'),
(691, 123, 'profile_views', 1, NULL, '{\"visitor_ip\":\"40.77.167.71\",\"user_agent\":{},\"referrer\":null}', '2026-02-03 04:41:59', '2026-02-01', '2026-02-28'),
(692, 113, 'profile_views', 1, NULL, '{\"visitor_ip\":\"40.77.167.9\",\"user_agent\":{},\"referrer\":null}', '2026-02-03 05:03:42', '2026-02-01', '2026-02-28'),
(693, 123, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.6.121\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-03 07:30:56', '2026-02-01', '2026-02-28'),
(694, 123, 'contact_clicks', 1, NULL, '{\"visitor_ip\":\"137.115.6.121\",\"contact_type\":\"message\",\"reference_type\":\"video\",\"reference_id\":null,\"user_agent\":\"Mozilla\\/5.0 (Linux; Android 10; K) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/124.0.0.0 Safari\\/537.36\",\"timestamp\":\"2026-02-03 07:53:15\"}', '2026-02-03 07:53:15', '2026-02-01', '2026-02-28'),
(695, 123, 'messages', 1, NULL, '{\"sender_name\":\"Step up\",\"sender_email\":\"chinseualbert@gmail.com\",\"subject\":\"All\",\"contact_preference\":\"whatsapp\",\"ip_address\":\"137.115.6.121\",\"user_agent\":{}}', '2026-02-03 07:53:19', '2026-02-01', '2026-02-28'),
(696, 123, 'contact_clicks', 1, NULL, '{\"visitor_ip\":\"137.115.6.121\",\"contact_type\":\"phone\",\"reference_type\":\"video\",\"reference_id\":null,\"user_agent\":\"Mozilla\\/5.0 (Linux; Android 10; K) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/124.0.0.0 Safari\\/537.36\",\"timestamp\":\"2026-02-03 08:01:54\"}', '2026-02-03 08:01:54', '2026-02-01', '2026-02-28'),
(697, 140, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.6.121\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-03 08:02:50', '2026-02-01', '2026-02-28'),
(698, 121, 'profile_views', 1, NULL, '{\"visitor_ip\":\"185.191.171.6\",\"user_agent\":{},\"referrer\":null}', '2026-02-03 09:47:23', '2026-02-01', '2026-02-28'),
(700, 123, 'profile_views', 1, NULL, '{\"visitor_ip\":\"40.77.167.126\",\"user_agent\":{},\"referrer\":null}', '2026-02-03 18:00:39', '2026-02-01', '2026-02-28'),
(701, 121, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.93.211\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-03 19:47:56', '2026-02-01', '2026-02-28'),
(702, 121, 'profile_views', 1, NULL, '{\"visitor_ip\":\"66.249.93.229\",\"user_agent\":{},\"referrer\":null}', '2026-02-03 19:48:00', '2026-02-01', '2026-02-28'),
(703, 121, 'profile_views', 1, NULL, '{\"visitor_ip\":\"74.125.210.196\",\"user_agent\":{},\"referrer\":null}', '2026-02-03 19:48:01', '2026-02-01', '2026-02-28'),
(704, 126, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.93.211\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-03 19:49:46', '2026-02-01', '2026-02-28'),
(705, 126, 'profile_views', 1, NULL, '{\"visitor_ip\":\"66.249.93.230\",\"user_agent\":{},\"referrer\":null}', '2026-02-03 19:49:50', '2026-02-01', '2026-02-28'),
(706, 126, 'profile_views', 1, NULL, '{\"visitor_ip\":\"74.125.210.197\",\"user_agent\":{},\"referrer\":null}', '2026-02-03 19:49:51', '2026-02-01', '2026-02-28'),
(707, 126, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.92.15\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-03 22:41:18', '2026-02-01', '2026-02-28'),
(708, 140, 'profile_views', 1, NULL, '{\"visitor_ip\":\"40.77.167.58\",\"user_agent\":{},\"referrer\":null}', '2026-02-04 05:16:35', '2026-02-01', '2026-02-28'),
(709, 121, 'profile_views', 1, NULL, '{\"visitor_ip\":\"40.77.167.9\",\"user_agent\":{},\"referrer\":null}', '2026-02-04 12:24:45', '2026-02-01', '2026-02-28'),
(710, 113, 'profile_views', 1, NULL, '{\"visitor_ip\":\"40.77.167.7\",\"user_agent\":{},\"referrer\":null}', '2026-02-04 18:59:48', '2026-02-01', '2026-02-28'),
(711, 140, 'profile_views', 1, NULL, '{\"visitor_ip\":\"40.77.167.67\",\"user_agent\":{},\"referrer\":null}', '2026-02-04 23:11:46', '2026-02-01', '2026-02-28'),
(712, 97, 'profile_views', 1, NULL, '{\"visitor_ip\":\"40.77.167.30\",\"user_agent\":{},\"referrer\":null}', '2026-02-05 02:49:58', '2026-02-01', '2026-02-28'),
(714, 97, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.94.93\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-02-05 05:41:44', '2026-02-01', '2026-02-28'),
(715, 114, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.94.93\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-02-05 05:42:25', '2026-02-01', '2026-02-28'),
(717, 140, 'profile_views', 1, NULL, '{\"visitor_ip\":\"185.191.171.11\",\"user_agent\":{},\"referrer\":null}', '2026-02-05 11:30:35', '2026-02-01', '2026-02-28'),
(719, 126, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.2.153\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/?fbclid=IwZXh0bgNhZW0CMTEAc3J0YwZhcHBfaWQPMjc1MjU0NjkyNTk4Mjc5AAEegdViByrCwi2VM361M3DUNXVH2hOYidO3QlzJCfWx_zdW2KCJ0GZJUwcCUqg_aem_QlACiaXfdnH66eObuuITbw\"}', '2026-02-05 16:50:52', '2026-02-01', '2026-02-28'),
(721, 94, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.91.235\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-02-05 17:04:55', '2026-02-01', '2026-02-28'),
(722, 153, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.91.235\",\"user_agent\":{},\"referrer\":\"http:\\/\\/tutorconnectmw.com\\/\"}', '2026-02-05 17:06:31', '2026-02-01', '2026-02-28'),
(723, 126, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.91.235\",\"user_agent\":{},\"referrer\":\"http:\\/\\/tutorconnectmw.com\\/\"}', '2026-02-05 17:08:08', '2026-02-01', '2026-02-28'),
(724, 153, 'contact_clicks', 1, NULL, '{\"visitor_ip\":\"102.70.91.235\",\"contact_type\":\"phone\",\"reference_type\":\"video\",\"reference_id\":null,\"user_agent\":\"Mozilla\\/5.0 (Linux; Android 13; ANY-NX1 Build\\/HONORANY-N01; wv) AppleWebKit\\/537.36 (KHTML, like Gecko) Version\\/4.0 Chrome\\/144.0.7559.59 Mobile Safari\\/537.36[FBAN\\/EMA;FBLC\\/en_US;FBAV\\/490.1.0.14.109;FBCX\\/modulariab;]\",\"timestamp\":\"2026-02-05 17:10:50\"}', '2026-02-05 17:10:50', '2026-02-01', '2026-02-28'),
(725, 140, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.91.235\",\"user_agent\":{},\"referrer\":\"http:\\/\\/tutorconnectmw.com\\/\"}', '2026-02-05 17:14:07', '2026-02-01', '2026-02-28'),
(726, 117, 'profile_views', 1, NULL, '{\"visitor_ip\":\"185.191.171.4\",\"user_agent\":{},\"referrer\":null}', '2026-02-05 18:21:03', '2026-02-01', '2026-02-28'),
(727, 94, 'profile_views', 1, NULL, '{\"visitor_ip\":\"105.234.174.43\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-02-05 19:14:39', '2026-02-01', '2026-02-28'),
(728, 113, 'profile_views', 1, NULL, '{\"visitor_ip\":\"105.234.174.43\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-02-05 19:16:29', '2026-02-01', '2026-02-28'),
(729, 113, 'profile_views', 1, NULL, '{\"visitor_ip\":\"66.249.93.231\",\"user_agent\":{},\"referrer\":null}', '2026-02-05 19:16:33', '2026-02-01', '2026-02-28'),
(730, 113, 'profile_views', 1, NULL, '{\"visitor_ip\":\"74.125.210.197\",\"user_agent\":{},\"referrer\":null}', '2026-02-05 19:16:34', '2026-02-01', '2026-02-28'),
(731, 113, 'profile_views', 1, NULL, '{\"visitor_ip\":\"64.233.172.233\",\"user_agent\":{},\"referrer\":null}', '2026-02-05 19:16:34', '2026-02-01', '2026-02-28'),
(732, 97, 'profile_views', 1, NULL, '{\"visitor_ip\":\"185.191.171.3\",\"user_agent\":{},\"referrer\":null}', '2026-02-05 21:48:37', '2026-02-01', '2026-02-28'),
(733, 140, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.0.78\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-06 06:55:48', '2026-02-01', '2026-02-28'),
(734, 153, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.93.77\",\"user_agent\":{},\"referrer\":\"http:\\/\\/tutorconnectmw.com\\/\"}', '2026-02-06 06:59:08', '2026-02-01', '2026-02-28'),
(735, 140, 'profile_views', 1, NULL, '{\"visitor_ip\":\"66.249.74.165\",\"user_agent\":{},\"referrer\":null}', '2026-02-06 07:06:11', '2026-02-01', '2026-02-28'),
(736, 155, 'profile_views', 1, NULL, '{\"visitor_ip\":\"66.249.74.166\",\"user_agent\":{},\"referrer\":null}', '2026-02-06 07:28:17', '2026-02-01', '2026-02-28'),
(737, 155, 'profile_views', 1, NULL, '{\"visitor_ip\":\"66.249.74.167\",\"user_agent\":{},\"referrer\":null}', '2026-02-06 07:35:47', '2026-02-01', '2026-02-28'),
(738, 153, 'profile_views', 1, NULL, '{\"visitor_ip\":\"66.249.74.167\",\"user_agent\":{},\"referrer\":null}', '2026-02-06 08:05:05', '2026-02-01', '2026-02-28'),
(739, 97, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.9.85\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-02-06 09:33:46', '2026-02-01', '2026-02-28'),
(740, 155, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.106.50\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-06 10:23:18', '2026-02-01', '2026-02-28'),
(752, 140, 'profile_views', 1, NULL, '{\"visitor_ip\":\"40.77.167.144\",\"user_agent\":{},\"referrer\":null}', '2026-02-06 21:57:33', '2026-02-01', '2026-02-28'),
(753, 103, 'profile_views', 1, NULL, '{\"visitor_ip\":\"40.77.167.247\",\"user_agent\":{},\"referrer\":null}', '2026-02-06 22:30:25', '2026-02-01', '2026-02-28'),
(754, 114, 'profile_views', 1, NULL, '{\"visitor_ip\":\"40.77.167.33\",\"user_agent\":{},\"referrer\":null}', '2026-02-06 22:39:57', '2026-02-01', '2026-02-28'),
(759, 126, 'profile_views', 1, NULL, '{\"visitor_ip\":\"185.142.228.151\",\"user_agent\":{},\"referrer\":null}', '2026-02-07 08:06:36', '2026-02-01', '2026-02-28'),
(761, 114, 'profile_views', 1, NULL, '{\"visitor_ip\":\"40.77.167.116\",\"user_agent\":{},\"referrer\":null}', '2026-02-07 16:12:05', '2026-02-01', '2026-02-28'),
(764, 153, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.104.178\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-07 17:50:38', '2026-02-01', '2026-02-28'),
(766, 155, 'profile_views', 1, NULL, '{\"visitor_ip\":\"52.167.144.230\",\"user_agent\":{},\"referrer\":null}', '2026-02-07 20:57:49', '2026-02-01', '2026-02-28'),
(767, 153, 'profile_views', 1, NULL, '{\"visitor_ip\":\"52.167.144.208\",\"user_agent\":{},\"referrer\":null}', '2026-02-07 21:21:18', '2026-02-01', '2026-02-28'),
(770, 155, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.91.154\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-08 05:28:05', '2026-02-01', '2026-02-28'),
(772, 155, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.2.158\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-08 06:20:57', '2026-02-01', '2026-02-28'),
(773, 153, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.2.158\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-08 06:21:35', '2026-02-01', '2026-02-28'),
(774, 161, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.11.206\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-08 08:26:44', '2026-02-01', '2026-02-28'),
(775, 161, 'profile_views', 1, NULL, '{\"visitor_ip\":\"66.249.93.230\",\"user_agent\":{},\"referrer\":null}', '2026-02-08 08:26:49', '2026-02-01', '2026-02-28'),
(776, 161, 'profile_views', 1, NULL, '{\"visitor_ip\":\"74.125.210.197\",\"user_agent\":{},\"referrer\":null}', '2026-02-08 08:26:50', '2026-02-01', '2026-02-28'),
(777, 161, 'profile_views', 1, NULL, '{\"visitor_ip\":\"74.125.210.196\",\"user_agent\":{},\"referrer\":null}', '2026-02-08 08:26:50', '2026-02-01', '2026-02-28'),
(779, 161, 'profile_views', 1, NULL, '{\"visitor_ip\":\"105.234.181.133\",\"user_agent\":{},\"referrer\":null}', '2026-02-08 10:53:29', '2026-02-01', '2026-02-28'),
(780, 126, 'profile_views', 1, NULL, '{\"visitor_ip\":\"52.167.144.213\",\"user_agent\":{},\"referrer\":null}', '2026-02-08 13:09:54', '2026-02-01', '2026-02-28'),
(782, 126, 'profile_views', 1, NULL, '{\"visitor_ip\":\"66.249.74.166\",\"user_agent\":{},\"referrer\":null}', '2026-02-08 15:56:03', '2026-02-01', '2026-02-28'),
(785, 161, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.118.34\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-02-08 17:42:20', '2026-02-01', '2026-02-28'),
(786, 161, 'profile_views', 1, NULL, '{\"visitor_ip\":\"66.249.74.167\",\"user_agent\":{},\"referrer\":null}', '2026-02-08 17:45:42', '2026-02-01', '2026-02-28'),
(787, 161, 'profile_views', 1, NULL, '{\"visitor_ip\":\"66.249.74.166\",\"user_agent\":{},\"referrer\":null}', '2026-02-08 17:46:02', '2026-02-01', '2026-02-28'),
(789, 153, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.95.215\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/?fbclid=IwZXh0bgNhZW0CMTEAc3J0YwZhcHBfaWQPMjc1MjU0NjkyNTk4Mjc5AAEezkHq0KPkEG0qBqohU6eNiWRAR4HgwMgbLfpBkD0MHOK7FodHJJuZeFP1R3Y_aem__yqXrw7u5xxw5egaYFReLw\"}', '2026-02-08 18:59:30', '2026-02-01', '2026-02-28'),
(791, 161, 'profile_views', 1, NULL, '{\"visitor_ip\":\"105.234.181.139\",\"user_agent\":{},\"referrer\":null}', '2026-02-08 21:05:12', '2026-02-01', '2026-02-28'),
(792, 161, 'contact_clicks', 1, NULL, '{\"visitor_ip\":\"105.234.181.139\",\"contact_type\":\"email\",\"reference_type\":\"video\",\"reference_id\":null,\"user_agent\":\"Mozilla\\/5.0 (Linux; Android 10; K) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/144.0.0.0 Mobile Safari\\/537.36\",\"timestamp\":\"2026-02-08 21:07:16\"}', '2026-02-08 21:07:16', '2026-02-01', '2026-02-28'),
(793, 140, 'profile_views', 1, NULL, '{\"visitor_ip\":\"52.167.144.142\",\"user_agent\":{},\"referrer\":null}', '2026-02-08 23:50:27', '2026-02-01', '2026-02-28'),
(794, 155, 'profile_views', 1, NULL, '{\"visitor_ip\":\"40.77.167.123\",\"user_agent\":{},\"referrer\":null}', '2026-02-09 03:10:55', '2026-02-01', '2026-02-28'),
(795, 114, 'profile_views', 1, NULL, '{\"visitor_ip\":\"40.77.167.17\",\"user_agent\":{},\"referrer\":null}', '2026-02-09 03:12:55', '2026-02-01', '2026-02-28'),
(797, 153, 'profile_views', 1, NULL, '{\"visitor_ip\":\"52.167.144.170\",\"user_agent\":{},\"referrer\":null}', '2026-02-09 03:20:22', '2026-02-01', '2026-02-28'),
(799, 140, 'profile_views', 1, NULL, '{\"visitor_ip\":\"40.77.167.12\",\"user_agent\":{},\"referrer\":null}', '2026-02-09 07:35:29', '2026-02-01', '2026-02-28'),
(801, 126, 'profile_views', 1, NULL, '{\"visitor_ip\":\"192.121.155.26\",\"user_agent\":{},\"referrer\":null}', '2026-02-09 11:58:20', '2026-02-01', '2026-02-28'),
(803, 161, 'profile_views', 1, NULL, '{\"visitor_ip\":\"157.55.39.204\",\"user_agent\":{},\"referrer\":null}', '2026-02-10 06:18:49', '2026-02-01', '2026-02-28'),
(805, 161, 'profile_views', 1, NULL, '{\"visitor_ip\":\"68.49.222.86\",\"user_agent\":{},\"referrer\":null}', '2026-02-10 15:41:51', '2026-02-01', '2026-02-28'),
(806, 161, 'profile_views', 1, NULL, '{\"visitor_ip\":\"185.147.140.235\",\"user_agent\":{},\"referrer\":null}', '2026-02-10 15:43:42', '2026-02-01', '2026-02-28'),
(807, 155, 'profile_views', 1, NULL, '{\"visitor_ip\":\"85.208.96.195\",\"user_agent\":{},\"referrer\":null}', '2026-02-10 19:26:56', '2026-02-01', '2026-02-28'),
(809, 153, 'profile_views', 1, NULL, '{\"visitor_ip\":\"85.208.96.209\",\"user_agent\":{},\"referrer\":null}', '2026-02-11 05:59:45', '2026-02-01', '2026-02-28'),
(810, 113, 'profile_views', 1, NULL, '{\"visitor_ip\":\"85.208.96.199\",\"user_agent\":{},\"referrer\":null}', '2026-02-11 06:51:15', '2026-02-01', '2026-02-28'),
(811, 126, 'profile_views', 1, NULL, '{\"visitor_ip\":\"185.191.171.15\",\"user_agent\":{},\"referrer\":null}', '2026-02-11 07:31:59', '2026-02-01', '2026-02-28'),
(812, 114, 'profile_views', 1, NULL, '{\"visitor_ip\":\"185.191.171.17\",\"user_agent\":{},\"referrer\":null}', '2026-02-11 13:49:55', '2026-02-01', '2026-02-28'),
(821, 161, 'profile_views', 1, NULL, '{\"visitor_ip\":\"185.192.69.157\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.google.com\\/\"}', '2026-02-11 18:46:53', '2026-02-01', '2026-02-28'),
(822, 113, 'profile_views', 1, NULL, '{\"visitor_ip\":\"52.167.144.18\",\"user_agent\":{},\"referrer\":null}', '2026-02-11 19:41:42', '2026-02-01', '2026-02-28'),
(825, 140, 'profile_views', 1, NULL, '{\"visitor_ip\":\"159.138.156.176\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/tutor\\/Vincent\"}', '2026-02-12 11:46:07', '2026-02-01', '2026-02-28'),
(826, 153, 'profile_views', 1, NULL, '{\"visitor_ip\":\"173.252.107.113\",\"user_agent\":{},\"referrer\":null}', '2026-02-12 14:17:12', '2026-02-01', '2026-02-28'),
(827, 155, 'profile_views', 1, NULL, '{\"visitor_ip\":\"173.252.79.43\",\"user_agent\":{},\"referrer\":null}', '2026-02-12 14:17:12', '2026-02-01', '2026-02-28'),
(828, 153, 'profile_views', 1, NULL, '{\"visitor_ip\":\"49.232.129.225\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/tutor\\/gome\"}', '2026-02-13 12:35:48', '2026-02-01', '2026-02-28'),
(831, 165, 'profile_views', 1, NULL, '{\"visitor_ip\":\"85.208.96.207\",\"user_agent\":{},\"referrer\":null}', '2026-02-13 18:28:20', '2026-02-01', '2026-02-28'),
(832, 117, 'profile_views', 1, NULL, '{\"visitor_ip\":\"85.208.96.196\",\"user_agent\":{},\"referrer\":null}', '2026-02-13 18:33:19', '2026-02-01', '2026-02-28'),
(834, 126, 'profile_views', 1, NULL, '{\"visitor_ip\":\"43.134.229.41\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/tutor\\/mgawamkandawire\"}', '2026-02-13 20:44:17', '2026-02-01', '2026-02-28'),
(835, 94, 'profile_views', 1, NULL, '{\"visitor_ip\":\"185.191.171.12\",\"user_agent\":{},\"referrer\":null}', '2026-02-13 21:56:21', '2026-02-01', '2026-02-28'),
(836, 117, 'profile_views', 1, NULL, '{\"visitor_ip\":\"189.68.121.221\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-13 22:46:44', '2026-02-01', '2026-02-28'),
(837, 97, 'profile_views', 1, NULL, '{\"visitor_ip\":\"185.191.171.11\",\"user_agent\":{},\"referrer\":null}', '2026-02-13 23:20:51', '2026-02-01', '2026-02-28'),
(838, 140, 'profile_views', 1, NULL, '{\"visitor_ip\":\"185.191.171.19\",\"user_agent\":{},\"referrer\":null}', '2026-02-13 23:24:43', '2026-02-01', '2026-02-28'),
(839, 123, 'profile_views', 1, NULL, '{\"visitor_ip\":\"103.152.102.192\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-13 23:53:46', '2026-02-01', '2026-02-28'),
(840, 153, 'profile_views', 1, NULL, '{\"visitor_ip\":\"45.187.48.252\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-14 00:24:05', '2026-02-01', '2026-02-28'),
(841, 94, 'profile_views', 1, NULL, '{\"visitor_ip\":\"200.189.27.108\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-14 00:52:10', '2026-02-01', '2026-02-28'),
(843, 140, 'profile_views', 1, NULL, '{\"visitor_ip\":\"189.229.24.149\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-14 03:20:36', '2026-02-01', '2026-02-28'),
(844, 155, 'profile_views', 1, NULL, '{\"visitor_ip\":\"169.224.100.122\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-14 03:32:04', '2026-02-01', '2026-02-28'),
(845, 103, 'profile_views', 1, NULL, '{\"visitor_ip\":\"85.208.96.205\",\"user_agent\":{},\"referrer\":null}', '2026-02-14 05:08:26', '2026-02-01', '2026-02-28'),
(849, 165, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.11.111\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-02-14 07:02:36', '2026-02-01', '2026-02-28'),
(850, 165, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.106.119\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-14 07:12:00', '2026-02-01', '2026-02-28'),
(851, 161, 'profile_views', 1, NULL, '{\"visitor_ip\":\"117.103.119.156\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-14 07:40:13', '2026-02-01', '2026-02-28'),
(852, 126, 'profile_views', 1, NULL, '{\"visitor_ip\":\"190.247.66.58\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-14 09:40:32', '2026-02-01', '2026-02-28'),
(853, 175, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.119.183\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-02-14 10:23:04', '2026-02-01', '2026-02-28'),
(854, 175, 'profile_views', 1, NULL, '{\"visitor_ip\":\"66.249.93.231\",\"user_agent\":{},\"referrer\":null}', '2026-02-14 10:23:09', '2026-02-01', '2026-02-28'),
(855, 175, 'profile_views', 1, NULL, '{\"visitor_ip\":\"66.249.83.135\",\"user_agent\":{},\"referrer\":null}', '2026-02-14 10:23:10', '2026-02-01', '2026-02-28'),
(856, 175, 'profile_views', 1, NULL, '{\"visitor_ip\":\"66.249.83.134\",\"user_agent\":{},\"referrer\":null}', '2026-02-14 10:23:10', '2026-02-01', '2026-02-28'),
(859, 161, 'profile_views', 1, NULL, '{\"visitor_ip\":\"34.186.142.22\",\"user_agent\":{},\"referrer\":null}', '2026-02-14 11:53:18', '2026-02-01', '2026-02-28'),
(860, 113, 'profile_views', 1, NULL, '{\"visitor_ip\":\"66.9.166.218\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-14 14:14:07', '2026-02-01', '2026-02-28'),
(861, 155, 'profile_views', 1, NULL, '{\"visitor_ip\":\"116.204.79.156\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/tutor\\/_t_i_w_o_1\"}', '2026-02-15 17:38:48', '2026-02-01', '2026-02-28'),
(863, 123, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.10.136\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-02-15 19:50:12', '2026-02-01', '2026-02-28'),
(864, 114, 'profile_views', 1, NULL, '{\"visitor_ip\":\"52.167.144.142\",\"user_agent\":{},\"referrer\":null}', '2026-02-15 22:19:28', '2026-02-01', '2026-02-28'),
(865, 114, 'profile_views', 1, NULL, '{\"visitor_ip\":\"156.199.120.154\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-16 00:40:27', '2026-02-01', '2026-02-28'),
(867, 175, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.94.13\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-16 06:03:02', '2026-02-01', '2026-02-28'),
(868, 126, 'profile_views', 1, NULL, '{\"visitor_ip\":\"37.100.156.68\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/tutor\\/mgawamkandawire\"}', '2026-02-16 11:12:47', '2026-02-01', '2026-02-28'),
(869, 155, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.92.96\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-16 12:10:05', '2026-02-01', '2026-02-28'),
(870, 126, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.92.96\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-02-16 12:12:27', '2026-02-01', '2026-02-28'),
(871, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.92.96\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-02-16 12:13:25', '2026-02-01', '2026-02-28'),
(872, 140, 'profile_views', 1, NULL, '{\"visitor_ip\":\"1.92.199.100\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/tutor\\/Vincent\"}', '2026-02-16 12:24:17', '2026-02-01', '2026-02-28'),
(874, 153, 'profile_views', 1, NULL, '{\"visitor_ip\":\"189.1.244.36\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/tutor\\/gome\"}', '2026-02-16 14:48:27', '2026-02-01', '2026-02-28'),
(875, 175, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.95.18\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-02-16 18:05:17', '2026-02-01', '2026-02-28');
INSERT INTO `usage_tracking` (`id`, `user_id`, `metric_type`, `metric_value`, `reference_id`, `metadata`, `tracked_at`, `period_start`, `period_end`) VALUES
(879, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.95.18\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-16 20:12:01', '2026-02-01', '2026-02-28'),
(881, 175, 'profile_views', 1, NULL, '{\"visitor_ip\":\"173.252.79.112\",\"user_agent\":{},\"referrer\":null}', '2026-02-17 07:24:42', '2026-02-01', '2026-02-28'),
(882, 165, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.5.159\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/?hl=en-GB\"}', '2026-02-17 07:32:13', '2026-02-01', '2026-02-28'),
(883, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"105.234.177.244\",\"user_agent\":{},\"referrer\":\"https:\\/\\/tutorconnectmw.com\\/\"}', '2026-02-17 08:27:23', '2026-02-01', '2026-02-28'),
(885, 176, 'contact_clicks', 1, NULL, '{\"visitor_ip\":\"105.234.177.244\",\"contact_type\":\"email\",\"reference_type\":\"video\",\"reference_id\":null,\"user_agent\":\"Mozilla\\/5.0 (iPhone; CPU iPhone OS 16_7_12 like Mac OS X) AppleWebKit\\/605.1.15 (KHTML, like Gecko) Version\\/16.6.1 Mobile\\/15E148 Safari\\/604.1\",\"timestamp\":\"2026-02-17 08:41:19\"}', '2026-02-17 08:41:19', '2026-02-01', '2026-02-28'),
(886, 161, 'profile_views', 1, NULL, '{\"visitor_ip\":\"31.13.115.114\",\"user_agent\":{},\"referrer\":null}', '2026-02-17 09:50:29', '2026-02-01', '2026-02-28'),
(887, 184, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.88.86\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-17 12:25:21', '2026-02-01', '2026-02-28'),
(888, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.88.86\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-17 12:26:26', '2026-02-01', '2026-02-28'),
(889, 123, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.88.86\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-02-17 12:27:23', '2026-02-01', '2026-02-28'),
(891, 165, 'profile_views', 1, NULL, '{\"visitor_ip\":\"105.234.178.240\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-02-18 04:00:36', '2026-02-01', '2026-02-28'),
(892, 165, 'profile_views', 1, NULL, '{\"visitor_ip\":\"66.249.93.230\",\"user_agent\":{},\"referrer\":null}', '2026-02-18 04:00:39', '2026-02-01', '2026-02-28'),
(893, 165, 'profile_views', 1, NULL, '{\"visitor_ip\":\"66.102.9.170\",\"user_agent\":{},\"referrer\":null}', '2026-02-18 04:00:40', '2026-02-01', '2026-02-28'),
(894, 165, 'profile_views', 1, NULL, '{\"visitor_ip\":\"66.102.9.172\",\"user_agent\":{},\"referrer\":null}', '2026-02-18 04:00:40', '2026-02-01', '2026-02-28'),
(895, 175, 'profile_views', 1, NULL, '{\"visitor_ip\":\"105.234.178.240\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-02-18 04:03:09', '2026-02-01', '2026-02-28'),
(896, 175, 'profile_views', 1, NULL, '{\"visitor_ip\":\"66.249.93.229\",\"user_agent\":{},\"referrer\":null}', '2026-02-18 04:03:13', '2026-02-01', '2026-02-28'),
(897, 184, 'profile_views', 1, NULL, '{\"visitor_ip\":\"105.234.178.240\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-02-18 04:03:21', '2026-02-01', '2026-02-28'),
(898, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.3.49\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-18 15:07:52', '2026-02-01', '2026-02-28'),
(899, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"66.249.93.229\",\"user_agent\":{},\"referrer\":null}', '2026-02-18 15:07:56', '2026-02-01', '2026-02-28'),
(900, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"66.249.93.230\",\"user_agent\":{},\"referrer\":null}', '2026-02-18 15:07:57', '2026-02-01', '2026-02-28'),
(903, 121, 'profile_views', 1, NULL, '{\"visitor_ip\":\"121.37.110.3\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/tutor\\/chimwemwemalefulah\"}', '2026-02-18 19:26:30', '2026-02-01', '2026-02-28'),
(904, 161, 'profile_views', 1, NULL, '{\"visitor_ip\":\"113.44.112.101\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/tutor\\/Isaac55\"}', '2026-02-18 20:00:10', '2026-02-01', '2026-02-28'),
(905, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"113.44.99.221\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/tutor\\/Munga\"}', '2026-02-18 20:34:30', '2026-02-01', '2026-02-28'),
(907, 175, 'profile_views', 1, NULL, '{\"visitor_ip\":\"113.44.103.189\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/tutor\\/Madamkute\"}', '2026-02-18 22:51:40', '2026-02-01', '2026-02-28'),
(908, 123, 'profile_views', 1, NULL, '{\"visitor_ip\":\"121.37.101.182\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/tutor\\/mercymkwala\"}', '2026-02-18 23:26:10', '2026-02-01', '2026-02-28'),
(909, 117, 'profile_views', 1, NULL, '{\"visitor_ip\":\"116.204.109.34\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/tutor\\/teacher1\"}', '2026-02-19 00:01:42', '2026-02-01', '2026-02-28'),
(910, 184, 'profile_views', 1, NULL, '{\"visitor_ip\":\"185.191.171.1\",\"user_agent\":{},\"referrer\":null}', '2026-02-19 00:32:29', '2026-02-01', '2026-02-28'),
(911, 165, 'profile_views', 1, NULL, '{\"visitor_ip\":\"113.44.122.110\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/tutor\\/Kaunda\"}', '2026-02-19 02:17:40', '2026-02-01', '2026-02-28'),
(912, 175, 'profile_views', 1, NULL, '{\"visitor_ip\":\"185.191.171.11\",\"user_agent\":{},\"referrer\":null}', '2026-02-19 02:39:57', '2026-02-01', '2026-02-28'),
(914, 155, 'profile_views', 1, NULL, '{\"visitor_ip\":\"85.208.96.193\",\"user_agent\":{},\"referrer\":null}', '2026-02-19 05:59:25', '2026-02-01', '2026-02-28'),
(916, 121, 'profile_views', 1, NULL, '{\"visitor_ip\":\"185.191.171.2\",\"user_agent\":{},\"referrer\":null}', '2026-02-19 12:23:56', '2026-02-01', '2026-02-28'),
(917, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.110.42\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-19 12:59:12', '2026-02-01', '2026-02-28'),
(918, 153, 'profile_views', 1, NULL, '{\"visitor_ip\":\"116.204.45.168\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/tutor\\/gome\"}', '2026-02-19 13:08:50', '2026-02-01', '2026-02-28'),
(919, 153, 'profile_views', 1, NULL, '{\"visitor_ip\":\"185.191.171.14\",\"user_agent\":{},\"referrer\":null}', '2026-02-19 13:39:28', '2026-02-01', '2026-02-28'),
(920, 155, 'profile_views', 1, NULL, '{\"visitor_ip\":\"116.204.12.170\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/tutor\\/_t_i_w_o_1\"}', '2026-02-19 13:43:01', '2026-02-01', '2026-02-28'),
(921, 140, 'profile_views', 1, NULL, '{\"visitor_ip\":\"116.204.24.57\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/tutor\\/Vincent\"}', '2026-02-19 14:51:41', '2026-02-01', '2026-02-28'),
(922, 184, 'profile_views', 1, NULL, '{\"visitor_ip\":\"93.158.90.152\",\"user_agent\":{},\"referrer\":null}', '2026-02-19 15:13:41', '2026-02-01', '2026-02-28'),
(923, 184, 'profile_views', 1, NULL, '{\"visitor_ip\":\"116.204.99.121\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/tutor\\/VincentLikongwe\"}', '2026-02-19 20:53:20', '2026-02-01', '2026-02-28'),
(924, 126, 'profile_views', 1, NULL, '{\"visitor_ip\":\"116.204.79.156\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/tutor\\/mgawamkandawire\"}', '2026-02-19 23:20:01', '2026-02-01', '2026-02-28'),
(925, 153, 'profile_views', 1, NULL, '{\"visitor_ip\":\"1.92.198.67\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/tutor\\/gome\"}', '2026-02-20 12:52:23', '2026-02-01', '2026-02-28'),
(926, 184, 'profile_views', 1, NULL, '{\"visitor_ip\":\"66.249.66.166\",\"user_agent\":{},\"referrer\":null}', '2026-02-20 15:34:27', '2026-02-01', '2026-02-28'),
(927, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.90.14\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-20 15:36:24', '2026-02-01', '2026-02-28'),
(928, 184, 'profile_views', 1, NULL, '{\"visitor_ip\":\"116.204.3.75\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/tutor\\/VincentLikongwe\"}', '2026-02-20 16:33:52', '2026-02-01', '2026-02-28'),
(929, 184, 'profile_views', 1, NULL, '{\"visitor_ip\":\"66.249.66.75\",\"user_agent\":{},\"referrer\":null}', '2026-02-20 16:36:57', '2026-02-01', '2026-02-28'),
(930, 140, 'profile_views', 1, NULL, '{\"visitor_ip\":\"121.37.109.242\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/tutor\\/Vincent\"}', '2026-02-20 18:47:40', '2026-02-01', '2026-02-28'),
(931, 121, 'profile_views', 1, NULL, '{\"visitor_ip\":\"1.92.198.67\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/tutor\\/chimwemwemalefulah\"}', '2026-02-20 19:21:10', '2026-02-01', '2026-02-28'),
(932, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.0.13\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-20 20:44:13', '2026-02-01', '2026-02-28'),
(933, 165, 'profile_views', 1, NULL, '{\"visitor_ip\":\"116.204.36.248\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/tutor\\/Kaunda\"}', '2026-02-20 21:01:30', '2026-02-01', '2026-02-28'),
(934, 175, 'profile_views', 1, NULL, '{\"visitor_ip\":\"113.44.111.126\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/tutor\\/Madamkute\"}', '2026-02-21 00:22:30', '2026-02-01', '2026-02-28'),
(935, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"121.37.99.68\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/tutor\\/Munga\"}', '2026-02-21 02:03:00', '2026-02-01', '2026-02-28'),
(936, 126, 'profile_views', 1, NULL, '{\"visitor_ip\":\"121.37.110.82\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/tutor\\/mgawamkandawire\"}', '2026-02-21 03:43:30', '2026-02-01', '2026-02-28'),
(937, 117, 'profile_views', 1, NULL, '{\"visitor_ip\":\"116.204.105.213\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/tutor\\/teacher1\"}', '2026-02-21 05:23:51', '2026-02-01', '2026-02-28'),
(938, 123, 'profile_views', 1, NULL, '{\"visitor_ip\":\"1.92.205.209\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/tutor\\/mercymkwala\"}', '2026-02-21 06:30:50', '2026-02-01', '2026-02-28'),
(939, 161, 'profile_views', 1, NULL, '{\"visitor_ip\":\"1.92.213.194\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/tutor\\/Isaac55\"}', '2026-02-21 08:44:50', '2026-02-01', '2026-02-28'),
(941, 153, 'profile_views', 1, NULL, '{\"visitor_ip\":\"116.204.104.114\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/tutor\\/gome\"}', '2026-02-21 12:05:50', '2026-02-01', '2026-02-28'),
(942, 94, 'profile_views', 1, NULL, '{\"visitor_ip\":\"40.77.167.47\",\"user_agent\":{},\"referrer\":null}', '2026-02-21 12:49:52', '2026-02-01', '2026-02-28'),
(943, 126, 'profile_views', 1, NULL, '{\"visitor_ip\":\"40.77.167.13\",\"user_agent\":{},\"referrer\":null}', '2026-02-21 13:57:45', '2026-02-01', '2026-02-28'),
(944, 165, 'profile_views', 1, NULL, '{\"visitor_ip\":\"85.208.96.205\",\"user_agent\":{},\"referrer\":null}', '2026-02-21 20:40:55', '2026-02-01', '2026-02-28'),
(947, 117, 'profile_views', 1, NULL, '{\"visitor_ip\":\"85.208.96.198\",\"user_agent\":{},\"referrer\":null}', '2026-02-22 05:09:46', '2026-02-01', '2026-02-28'),
(948, 94, 'profile_views', 1, NULL, '{\"visitor_ip\":\"185.191.171.18\",\"user_agent\":{},\"referrer\":null}', '2026-02-22 07:01:43', '2026-02-01', '2026-02-28'),
(949, 140, 'profile_views', 1, NULL, '{\"visitor_ip\":\"85.208.96.208\",\"user_agent\":{},\"referrer\":null}', '2026-02-22 09:47:24', '2026-02-01', '2026-02-28'),
(950, 184, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.5.33\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-02-22 14:19:13', '2026-02-01', '2026-02-28'),
(951, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.92.239\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-22 19:00:28', '2026-02-01', '2026-02-28'),
(952, 113, 'profile_views', 1, NULL, '{\"visitor_ip\":\"185.191.171.9\",\"user_agent\":{},\"referrer\":null}', '2026-02-23 12:27:06', '2026-02-01', '2026-02-28'),
(953, 126, 'profile_views', 1, NULL, '{\"visitor_ip\":\"85.208.96.206\",\"user_agent\":{},\"referrer\":null}', '2026-02-23 18:30:50', '2026-02-01', '2026-02-28'),
(954, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.3.217\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-23 19:33:27', '2026-02-01', '2026-02-28'),
(955, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"66.249.93.231\",\"user_agent\":{},\"referrer\":null}', '2026-02-23 19:33:29', '2026-02-01', '2026-02-28'),
(956, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"74.125.210.197\",\"user_agent\":{},\"referrer\":null}', '2026-02-23 19:33:31', '2026-02-01', '2026-02-28'),
(957, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"74.125.210.196\",\"user_agent\":{},\"referrer\":null}', '2026-02-23 19:33:31', '2026-02-01', '2026-02-28'),
(958, 184, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.3.217\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-23 19:34:19', '2026-02-01', '2026-02-28'),
(959, 165, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.3.217\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-23 19:34:47', '2026-02-01', '2026-02-28'),
(960, 114, 'profile_views', 1, NULL, '{\"visitor_ip\":\"185.191.171.3\",\"user_agent\":{},\"referrer\":null}', '2026-02-23 22:22:57', '2026-02-01', '2026-02-28'),
(961, 123, 'profile_views', 1, NULL, '{\"visitor_ip\":\"185.191.171.8\",\"user_agent\":{},\"referrer\":null}', '2026-02-24 12:16:10', '2026-02-01', '2026-02-28'),
(962, 140, 'profile_views', 1, NULL, '{\"visitor_ip\":\"52.167.144.17\",\"user_agent\":{},\"referrer\":null}', '2026-02-24 12:31:14', '2026-02-01', '2026-02-28'),
(963, 153, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.0.9\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors?name=&district=&curriculum=&subject=&level=&teaching_mode=&sort_by=rating\"}', '2026-02-24 14:53:31', '2026-02-01', '2026-02-28'),
(964, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.4.77\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/?fbclid=IwZXh0bgNhZW0CMTEAc3J0YwZhcHBfaWQPMjc1MjU0NjkyNTk4Mjc5AAEeyFPHOFb2DX5AgfUC7iuDAlkefDO9nIKgSSEnqmFEpzNCLCNmm-dQXBBz_l4_aem_E32bctdz2sfMFQ_mzrtuSA\"}', '2026-02-25 08:56:43', '2026-02-01', '2026-02-28'),
(965, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.89.209\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-25 11:38:16', '2026-02-01', '2026-02-28'),
(966, 184, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.89.209\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-25 11:39:00', '2026-02-01', '2026-02-28'),
(967, 175, 'profile_views', 1, NULL, '{\"visitor_ip\":\"216.234.217.191\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-25 12:42:59', '2026-02-01', '2026-02-28'),
(968, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"216.234.217.191\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-25 12:44:47', '2026-02-01', '2026-02-28'),
(969, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.110.144\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-25 13:23:09', '2026-02-01', '2026-02-28'),
(972, 184, 'profile_views', 1, NULL, '{\"visitor_ip\":\"93.158.91.244\",\"user_agent\":{},\"referrer\":null}', '2026-02-25 20:44:22', '2026-02-01', '2026-02-28'),
(973, 97, 'profile_views', 1, NULL, '{\"visitor_ip\":\"185.191.171.4\",\"user_agent\":{},\"referrer\":null}', '2026-02-26 02:33:30', '2026-02-01', '2026-02-28'),
(974, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.118.227\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-26 11:01:46', '2026-02-01', '2026-02-28'),
(975, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.117.252\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-02-26 16:13:36', '2026-02-01', '2026-02-28'),
(978, 175, 'profile_views', 1, NULL, '{\"visitor_ip\":\"185.191.171.4\",\"user_agent\":{},\"referrer\":null}', '2026-02-27 08:16:41', '2026-02-01', '2026-02-28'),
(979, 184, 'profile_views', 1, NULL, '{\"visitor_ip\":\"85.208.96.211\",\"user_agent\":{},\"referrer\":null}', '2026-02-27 08:42:08', '2026-02-01', '2026-02-28'),
(981, 155, 'profile_views', 1, NULL, '{\"visitor_ip\":\"85.208.96.211\",\"user_agent\":{},\"referrer\":null}', '2026-02-27 16:25:31', '2026-02-01', '2026-02-28'),
(982, 153, 'profile_views', 1, NULL, '{\"visitor_ip\":\"185.191.171.2\",\"user_agent\":{},\"referrer\":null}', '2026-02-27 20:51:48', '2026-02-01', '2026-02-28'),
(983, 123, 'profile_views', 1, NULL, '{\"visitor_ip\":\"40.77.167.70\",\"user_agent\":{},\"referrer\":null}', '2026-02-28 09:24:16', '2026-02-01', '2026-02-28'),
(985, 161, 'profile_views', 1, NULL, '{\"visitor_ip\":\"183.207.45.98\",\"user_agent\":{},\"referrer\":null}', '2026-03-01 02:57:03', '2026-03-01', '2026-03-31'),
(986, 165, 'profile_views', 1, NULL, '{\"visitor_ip\":\"183.207.45.98\",\"user_agent\":{},\"referrer\":null}', '2026-03-01 02:57:04', '2026-03-01', '2026-03-31'),
(987, 175, 'profile_views', 1, NULL, '{\"visitor_ip\":\"183.207.45.98\",\"user_agent\":{},\"referrer\":null}', '2026-03-01 02:57:04', '2026-03-01', '2026-03-31'),
(988, 184, 'profile_views', 1, NULL, '{\"visitor_ip\":\"183.207.45.98\",\"user_agent\":{},\"referrer\":null}', '2026-03-01 02:57:04', '2026-03-01', '2026-03-31'),
(990, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"183.207.45.98\",\"user_agent\":{},\"referrer\":null}', '2026-03-01 02:57:05', '2026-03-01', '2026-03-31'),
(991, 155, 'profile_views', 1, NULL, '{\"visitor_ip\":\"183.207.45.98\",\"user_agent\":{},\"referrer\":null}', '2026-03-01 02:57:18', '2026-03-01', '2026-03-31'),
(992, 153, 'profile_views', 1, NULL, '{\"visitor_ip\":\"183.207.45.98\",\"user_agent\":{},\"referrer\":null}', '2026-03-01 02:57:19', '2026-03-01', '2026-03-31'),
(993, 140, 'profile_views', 1, NULL, '{\"visitor_ip\":\"183.207.45.98\",\"user_agent\":{},\"referrer\":null}', '2026-03-01 02:57:19', '2026-03-01', '2026-03-31'),
(994, 184, 'profile_views', 1, NULL, '{\"visitor_ip\":\"113.44.107.150\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/tutor\\/VincentLikongwe\"}', '2026-03-01 16:19:11', '2026-03-01', '2026-03-31'),
(995, 165, 'profile_views', 1, NULL, '{\"visitor_ip\":\"85.208.96.199\",\"user_agent\":{},\"referrer\":null}', '2026-03-01 23:27:51', '2026-03-01', '2026-03-31'),
(997, 161, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.3.9\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors?district=Lilongwe\"}', '2026-03-02 15:22:27', '2026-03-01', '2026-03-31'),
(998, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.3.9\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors?district=Lilongwe\"}', '2026-03-02 15:42:08', '2026-03-01', '2026-03-31'),
(999, 117, 'profile_views', 1, NULL, '{\"visitor_ip\":\"40.77.167.47\",\"user_agent\":{},\"referrer\":null}', '2026-03-02 22:09:03', '2026-03-01', '2026-03-31'),
(1000, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.2.38\",\"user_agent\":{},\"referrer\":\"https:\\/\\/tutorconnectmw.com\\/\"}', '2026-03-03 06:35:20', '2026-03-01', '2026-03-31'),
(1001, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.91.47\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-03-03 09:47:06', '2026-03-01', '2026-03-31'),
(1002, 121, 'profile_views', 1, NULL, '{\"visitor_ip\":\"85.208.96.202\",\"user_agent\":{},\"referrer\":null}', '2026-03-03 16:08:32', '2026-03-01', '2026-03-31'),
(1003, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.104.163\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-03-03 22:21:12', '2026-03-01', '2026-03-31'),
(1005, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"105.234.180.6\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-03-04 15:31:20', '2026-03-01', '2026-03-31'),
(1006, 161, 'profile_views', 1, NULL, '{\"visitor_ip\":\"1.92.223.174\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/tutor\\/Isaac55\"}', '2026-03-04 16:13:26', '2026-03-01', '2026-03-31'),
(1007, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.10.29\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-03-04 16:48:18', '2026-03-01', '2026-03-31'),
(1008, 184, 'profile_views', 1, NULL, '{\"visitor_ip\":\"80.248.225.154\",\"user_agent\":{},\"referrer\":null}', '2026-03-04 17:37:18', '2026-03-01', '2026-03-31'),
(1009, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"105.234.179.71\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-03-05 02:42:57', '2026-03-01', '2026-03-31'),
(1010, 107, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.98.95\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-03-05 06:03:43', '2026-03-01', '2026-03-31'),
(1011, 107, 'profile_views', 1, NULL, '{\"visitor_ip\":\"66.249.93.227\",\"user_agent\":{},\"referrer\":null}', '2026-03-05 06:03:47', '2026-03-01', '2026-03-31'),
(1012, 107, 'profile_views', 1, NULL, '{\"visitor_ip\":\"66.249.83.128\",\"user_agent\":{},\"referrer\":null}', '2026-03-05 06:03:48', '2026-03-01', '2026-03-31'),
(1013, 107, 'profile_views', 1, NULL, '{\"visitor_ip\":\"66.249.83.130\",\"user_agent\":{},\"referrer\":null}', '2026-03-05 06:03:48', '2026-03-01', '2026-03-31'),
(1014, 107, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.99.104\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-03-05 16:27:42', '2026-03-01', '2026-03-31'),
(1016, 165, 'profile_views', 1, NULL, '{\"visitor_ip\":\"105.234.176.32\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-03-05 17:54:33', '2026-03-01', '2026-03-31'),
(1018, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.10.131\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-03-06 04:16:03', '2026-03-01', '2026-03-31'),
(1019, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.96.109\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-03-06 05:19:01', '2026-03-01', '2026-03-31'),
(1020, 117, 'profile_views', 1, NULL, '{\"visitor_ip\":\"85.208.96.203\",\"user_agent\":{},\"referrer\":null}', '2026-03-06 12:36:50', '2026-03-01', '2026-03-31'),
(1021, 94, 'profile_views', 1, NULL, '{\"visitor_ip\":\"85.208.96.200\",\"user_agent\":{},\"referrer\":null}', '2026-03-06 12:41:40', '2026-03-01', '2026-03-31'),
(1022, 140, 'profile_views', 1, NULL, '{\"visitor_ip\":\"185.191.171.1\",\"user_agent\":{},\"referrer\":null}', '2026-03-06 16:51:24', '2026-03-01', '2026-03-31'),
(1023, 103, 'profile_views', 1, NULL, '{\"visitor_ip\":\"185.191.171.5\",\"user_agent\":{},\"referrer\":null}', '2026-03-07 00:21:55', '2026-03-01', '2026-03-31'),
(1024, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.118.33\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-03-07 04:38:15', '2026-03-01', '2026-03-31'),
(1025, 175, 'profile_views', 1, NULL, '{\"visitor_ip\":\"185.191.171.10\",\"user_agent\":{},\"referrer\":null}', '2026-03-07 13:47:22', '2026-03-01', '2026-03-31'),
(1026, 184, 'profile_views', 1, NULL, '{\"visitor_ip\":\"85.208.96.199\",\"user_agent\":{},\"referrer\":null}', '2026-03-07 17:49:08', '2026-03-01', '2026-03-31'),
(1027, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"105.234.181.24\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-03-07 22:14:10', '2026-03-01', '2026-03-31'),
(1028, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.2.16\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-03-08 05:11:12', '2026-03-01', '2026-03-31'),
(1029, 107, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.2.16\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-03-08 05:12:08', '2026-03-01', '2026-03-31'),
(1030, 107, 'profile_views', 1, NULL, '{\"visitor_ip\":\"66.249.81.99\",\"user_agent\":{},\"referrer\":null}', '2026-03-08 05:12:14', '2026-03-01', '2026-03-31'),
(1031, 107, 'profile_views', 1, NULL, '{\"visitor_ip\":\"66.102.9.231\",\"user_agent\":{},\"referrer\":null}', '2026-03-08 05:12:14', '2026-03-01', '2026-03-31'),
(1032, 107, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.105.192\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-03-08 10:46:08', '2026-03-01', '2026-03-31'),
(1033, 107, 'contact_clicks', 1, NULL, '{\"visitor_ip\":\"102.70.105.192\",\"contact_type\":\"whatsapp\",\"reference_type\":\"video\",\"reference_id\":null,\"user_agent\":\"Mozilla\\/5.0 (Linux; Android 10; K) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/145.0.0.0 Mobile Safari\\/537.36\",\"timestamp\":\"2026-03-08 10:46:15\"}', '2026-03-08 10:46:15', '2026-03-01', '2026-03-31'),
(1034, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.91.101\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-03-08 13:43:43', '2026-03-01', '2026-03-31'),
(1035, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.11.189\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-03-08 18:40:59', '2026-03-01', '2026-03-31'),
(1036, 121, 'profile_views', 1, NULL, '{\"visitor_ip\":\"52.167.144.212\",\"user_agent\":{},\"referrer\":null}', '2026-03-08 20:10:36', '2026-03-01', '2026-03-31'),
(1037, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"105.234.180.235\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-03-09 06:20:08', '2026-03-01', '2026-03-31'),
(1038, 161, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.5.173\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-03-09 06:23:15', '2026-03-01', '2026-03-31'),
(1041, 107, 'profile_views', 1, NULL, '{\"visitor_ip\":\"185.191.171.11\",\"user_agent\":{},\"referrer\":null}', '2026-03-10 19:33:15', '2026-03-01', '2026-03-31'),
(1042, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"40.77.167.30\",\"user_agent\":{},\"referrer\":null}', '2026-03-11 00:39:18', '2026-03-01', '2026-03-31'),
(1043, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"105.234.178.49\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-03-11 02:19:04', '2026-03-01', '2026-03-31'),
(1046, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.5.55\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-03-11 14:57:01', '2026-03-01', '2026-03-31'),
(1047, 113, 'profile_views', 1, NULL, '{\"visitor_ip\":\"85.208.96.200\",\"user_agent\":{},\"referrer\":null}', '2026-03-11 16:39:37', '2026-03-01', '2026-03-31'),
(1048, 184, 'profile_views', 1, NULL, '{\"visitor_ip\":\"34.96.39.246\",\"user_agent\":{},\"referrer\":null}', '2026-03-11 19:15:34', '2026-03-01', '2026-03-31'),
(1049, 184, 'profile_views', 1, NULL, '{\"visitor_ip\":\"193.233.221.53\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\"}', '2026-03-11 19:23:56', '2026-03-01', '2026-03-31'),
(1050, 126, 'profile_views', 1, NULL, '{\"visitor_ip\":\"185.191.171.15\",\"user_agent\":{},\"referrer\":null}', '2026-03-11 19:38:07', '2026-03-01', '2026-03-31'),
(1051, 153, 'profile_views', 1, NULL, '{\"visitor_ip\":\"185.191.171.10\",\"user_agent\":{},\"referrer\":null}', '2026-03-12 01:06:03', '2026-03-01', '2026-03-31'),
(1052, 155, 'profile_views', 1, NULL, '{\"visitor_ip\":\"185.191.171.10\",\"user_agent\":{},\"referrer\":null}', '2026-03-12 01:21:27', '2026-03-01', '2026-03-31'),
(1053, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"105.234.181.160\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-03-12 03:13:18', '2026-03-01', '2026-03-31'),
(1054, 114, 'profile_views', 1, NULL, '{\"visitor_ip\":\"85.208.96.210\",\"user_agent\":{},\"referrer\":null}', '2026-03-12 03:53:07', '2026-03-01', '2026-03-31'),
(1055, 161, 'profile_views', 1, NULL, '{\"visitor_ip\":\"85.208.96.195\",\"user_agent\":{},\"referrer\":null}', '2026-03-12 11:48:53', '2026-03-01', '2026-03-31'),
(1056, 147, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.101.194\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-03-12 14:25:30', '2026-03-01', '2026-03-31'),
(1057, 147, 'profile_views', 1, NULL, '{\"visitor_ip\":\"66.249.83.128\",\"user_agent\":{},\"referrer\":null}', '2026-03-12 14:25:31', '2026-03-01', '2026-03-31'),
(1058, 147, 'profile_views', 1, NULL, '{\"visitor_ip\":\"66.249.83.130\",\"user_agent\":{},\"referrer\":null}', '2026-03-12 14:25:32', '2026-03-01', '2026-03-31'),
(1059, 123, 'profile_views', 1, NULL, '{\"visitor_ip\":\"85.208.96.200\",\"user_agent\":{},\"referrer\":null}', '2026-03-12 18:08:48', '2026-03-01', '2026-03-31'),
(1060, 107, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.99.92\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors?district=Lilongwe&curriculum=MANEB&subject=Physics\"}', '2026-03-13 07:16:32', '2026-03-01', '2026-03-31'),
(1061, 107, 'profile_views', 1, NULL, '{\"visitor_ip\":\"105.234.174.248\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors?district=Lilongwe&curriculum=MANEB&subject=Chichewa\"}', '2026-03-13 12:16:23', '2026-03-01', '2026-03-31'),
(1062, 107, 'profile_views', 1, NULL, '{\"visitor_ip\":\"66.249.93.229\",\"user_agent\":{},\"referrer\":null}', '2026-03-13 12:16:25', '2026-03-01', '2026-03-31'),
(1063, 107, 'profile_views', 1, NULL, '{\"visitor_ip\":\"74.125.210.194\",\"user_agent\":{},\"referrer\":null}', '2026-03-13 12:16:26', '2026-03-01', '2026-03-31'),
(1064, 147, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.0.13\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/?fbclid=IwZXh0bgNhZW0CMTEAc3J0YwZhcHBfaWQPMjc1MjU0NjkyNTk4Mjc5AAEeJpdVN0PQRIhR0N_VmCR0mDnSY16jIMuNa_3iaTh65w_m4aN5fsaFBeqQTuM_aem_oV5gh1i8BRwHIZO_veLHpw\"}', '2026-03-13 12:23:45', '2026-03-01', '2026-03-31'),
(1065, 165, 'profile_views', 1, NULL, '{\"visitor_ip\":\"85.208.96.212\",\"user_agent\":{},\"referrer\":null}', '2026-03-14 03:08:42', '2026-03-01', '2026-03-31'),
(1066, 113, 'profile_views', 1, NULL, '{\"visitor_ip\":\"40.77.167.57\",\"user_agent\":{},\"referrer\":null}', '2026-03-14 03:16:34', '2026-03-01', '2026-03-31'),
(1067, 184, 'profile_views', 1, NULL, '{\"visitor_ip\":\"136.113.239.24\",\"user_agent\":{},\"referrer\":null}', '2026-03-14 03:26:47', '2026-03-01', '2026-03-31'),
(1068, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.99.92\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-03-14 06:43:15', '2026-03-01', '2026-03-31'),
(1069, 97, 'profile_views', 1, NULL, '{\"visitor_ip\":\"185.191.171.16\",\"user_agent\":{},\"referrer\":null}', '2026-03-14 07:45:41', '2026-03-01', '2026-03-31'),
(1070, 175, 'profile_views', 1, NULL, '{\"visitor_ip\":\"52.167.144.233\",\"user_agent\":{},\"referrer\":null}', '2026-03-14 11:28:43', '2026-03-01', '2026-03-31'),
(1071, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"35.197.33.69\",\"user_agent\":{},\"referrer\":null}', '2026-03-14 19:53:09', '2026-03-01', '2026-03-31'),
(1072, 158, 'profile_views', 1, NULL, '{\"visitor_ip\":\"105.234.180.126\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-03-15 08:29:03', '2026-03-01', '2026-03-31'),
(1073, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"105.234.180.126\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-03-15 08:29:07', '2026-03-01', '2026-03-31'),
(1074, 176, 'profile_views', 1, NULL, '{\"visitor_ip\":\"105.234.176.94\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-03-15 14:04:53', '2026-03-01', '2026-03-31'),
(1075, 107, 'profile_views', 1, NULL, '{\"visitor_ip\":\"105.234.177.226\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-03-15 17:20:59', '2026-03-01', '2026-03-31'),
(1076, 165, 'profile_views', 1, NULL, '{\"visitor_ip\":\"52.167.144.190\",\"user_agent\":{},\"referrer\":null}', '2026-03-15 17:30:54', '2026-03-01', '2026-03-31'),
(1077, 158, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.95.110\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-03-16 05:25:45', '2026-03-01', '2026-03-31'),
(1078, 158, 'profile_views', 1, NULL, '{\"visitor_ip\":\"102.70.92.29\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/\"}', '2026-03-16 09:25:12', '2026-03-01', '2026-03-31'),
(1079, 158, 'profile_views', 1, NULL, '{\"visitor_ip\":\"105.234.177.155\",\"user_agent\":{},\"referrer\":null}', '2026-03-16 12:31:22', '2026-03-01', '2026-03-31'),
(1080, 175, 'profile_views', 1, NULL, '{\"visitor_ip\":\"85.208.96.209\",\"user_agent\":{},\"referrer\":null}', '2026-03-19 14:10:49', '2026-03-01', '2026-03-31'),
(1081, 161, 'profile_views', 1, NULL, '{\"visitor_ip\":\"66.249.66.192\",\"user_agent\":{},\"referrer\":null}', '2026-03-19 17:47:15', '2026-03-01', '2026-03-31'),
(1082, 121, 'profile_views', 1, NULL, '{\"visitor_ip\":\"85.208.96.196\",\"user_agent\":{},\"referrer\":null}', '2026-03-19 22:24:16', '2026-03-01', '2026-03-31'),
(1083, 184, 'profile_views', 1, NULL, '{\"visitor_ip\":\"85.208.96.209\",\"user_agent\":{},\"referrer\":null}', '2026-03-19 23:07:56', '2026-03-01', '2026-03-31'),
(1085, 161, 'profile_views', 1, NULL, '{\"visitor_ip\":\"17.241.219.237\",\"user_agent\":{},\"referrer\":null}', '2026-03-20 09:41:47', '2026-03-01', '2026-03-31'),
(1086, 161, 'profile_views', 1, NULL, '{\"visitor_ip\":\"85.208.96.203\",\"user_agent\":{},\"referrer\":null}', '2026-03-20 16:44:43', '2026-03-01', '2026-03-31'),
(1087, 123, 'profile_views', 1, NULL, '{\"visitor_ip\":\"85.208.96.201\",\"user_agent\":{},\"referrer\":null}', '2026-03-20 21:25:16', '2026-03-01', '2026-03-31'),
(1088, 158, 'profile_views', 1, NULL, '{\"visitor_ip\":\"105.234.179.195\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-03-21 04:03:26', '2026-03-01', '2026-03-31'),
(1089, 158, 'profile_views', 1, NULL, '{\"visitor_ip\":\"17.22.237.241\",\"user_agent\":{},\"referrer\":null}', '2026-03-21 06:12:07', '2026-03-01', '2026-03-31'),
(1090, 158, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.10.77\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/?fbclid=IwY2xjawQrPiZleHRuA2FlbQIxMABicmlkETEyTWtzZ2VWOE5xcDd4T1NDc3J0YwZhcHBfaWQQMjIyMDM5MTc4ODIwMDg5MgABHqKDtw4rj1WWGaSG_WJlF4IebV9zPU4ZXpBlXrPU3RiCayMnVKq0WbzIdnKH_aem_VvTDKah6MXRj8GY4Eg-T2A\"}', '2026-03-21 09:05:12', '2026-03-01', '2026-03-31'),
(1092, 165, 'profile_views', 1, NULL, '{\"visitor_ip\":\"17.246.23.119\",\"user_agent\":{},\"referrer\":null}', '2026-03-22 09:00:20', '2026-03-01', '2026-03-31'),
(1093, 94, 'profile_views', 1, NULL, '{\"visitor_ip\":\"85.208.96.201\",\"user_agent\":{},\"referrer\":null}', '2026-03-22 15:50:25', '2026-03-01', '2026-03-31'),
(1094, 117, 'profile_views', 1, NULL, '{\"visitor_ip\":\"85.208.96.193\",\"user_agent\":{},\"referrer\":null}', '2026-03-22 16:25:05', '2026-03-01', '2026-03-31'),
(1095, 107, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.10.209\",\"user_agent\":{},\"referrer\":\"https:\\/\\/www.tutorconnectmw.com\\/find-tutors\"}', '2026-03-22 16:45:23', '2026-03-01', '2026-03-31'),
(1096, 140, 'profile_views', 1, NULL, '{\"visitor_ip\":\"85.208.96.210\",\"user_agent\":{},\"referrer\":null}', '2026-03-22 19:52:35', '2026-03-01', '2026-03-31'),
(1097, 184, 'profile_views', 1, NULL, '{\"visitor_ip\":\"17.246.19.48\",\"user_agent\":{},\"referrer\":null}', '2026-03-23 03:48:13', '2026-03-01', '2026-03-31'),
(1098, 103, 'profile_views', 1, NULL, '{\"visitor_ip\":\"85.208.96.210\",\"user_agent\":{},\"referrer\":null}', '2026-03-23 09:17:17', '2026-03-01', '2026-03-31'),
(1099, 173, 'profile_views', 1, NULL, '{\"visitor_ip\":\"17.22.245.66\",\"user_agent\":{},\"referrer\":null}', '2026-03-23 11:11:49', '2026-03-01', '2026-03-31'),
(1100, 184, 'profile_views', 1, NULL, '{\"visitor_ip\":\"66.249.66.65\",\"user_agent\":{},\"referrer\":null}', '2026-03-23 18:34:31', '2026-03-01', '2026-03-31'),
(1101, 207, 'profile_views', 1, NULL, '{\"visitor_ip\":\"17.241.75.220\",\"user_agent\":{},\"referrer\":null}', '2026-03-25 11:41:35', '2026-03-01', '2026-03-31'),
(1102, 158, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.2.78\",\"user_agent\":{},\"referrer\":null}', '2026-03-25 12:14:59', '2026-03-01', '2026-03-31'),
(1103, 158, 'profile_views', 1, NULL, '{\"visitor_ip\":\"137.115.4.11\",\"user_agent\":{},\"referrer\":null}', '2026-03-25 12:37:50', '2026-03-01', '2026-03-31'),
(1104, 213, 'profile_views', 1, NULL, '{\"visitor_ip\":\"::1\",\"user_agent\":{},\"referrer\":null}', '2026-03-25 13:17:24', '2026-03-01', '2026-03-31');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'customer' COMMENT 'admin, sub-admin, trainer, customer',
  `first_name` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `gender` enum('Male','Female','Other','Prefer not to say') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `last_name` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email_verified_at` datetime DEFAULT NULL,
  `otp_code` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `otp_expires_at` datetime DEFAULT NULL,
  `reset_token` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `reset_expires_at` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `profile_picture` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cover_photo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `district` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `experience_years` int DEFAULT '1',
  `teaching_mode` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'Both Online & Physical',
  `bio` text COLLATE utf8mb4_general_ci,
  `bio_video` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Path to bio video file',
  `whatsapp_number` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone_visible` tinyint(1) DEFAULT '1',
  `email_visible` tinyint(1) DEFAULT '0',
  `best_call_time` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'Morning (8AM-12PM)',
  `preferred_contact_method` varchar(20) COLLATE utf8mb4_general_ci DEFAULT 'phone',
  `is_verified` tinyint(1) DEFAULT '0',
  `registration_completed` tinyint(1) DEFAULT '0',
  `terms_accepted` tinyint(1) NOT NULL DEFAULT '0',
  `is_employed` tinyint(1) DEFAULT '0',
  `school_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `subscription_plan` varchar(20) COLLATE utf8mb4_general_ci DEFAULT 'Basic',
  `subscription_expires_at` date DEFAULT NULL,
  `rating` decimal(2,1) DEFAULT '0.0',
  `review_count` int DEFAULT '0',
  `search_count` int DEFAULT '0',
  `featured` tinyint(1) DEFAULT '0',
  `tutor_status` varchar(20) COLLATE utf8mb4_general_ci DEFAULT 'pending' COMMENT 'pending, active, suspended, inactive',
  `verification_documents` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `availability` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `structured_subjects` text COLLATE utf8mb4_general_ci COMMENT 'JSON data storing subjects organized by curriculum and level',
  `resubmission_token` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `resubmission_token_expires` datetime DEFAULT NULL,
  `resubmission_special_docs` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `role` (`role`),
  KEY `is_active` (`is_active`),
  KEY `tutor_status` (`tutor_status`),
  KEY `district` (`district`),
  KEY `is_verified` (`is_verified`)
) ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `role`, `first_name`, `gender`, `last_name`, `phone`, `email_verified_at`, `otp_code`, `otp_expires_at`, `reset_token`, `reset_expires_at`, `is_active`, `profile_picture`, `cover_photo`, `approved_at`, `created_at`, `updated_at`, `deleted_at`, `district`, `location`, `experience_years`, `teaching_mode`, `bio`, `bio_video`, `whatsapp_number`, `phone_visible`, `email_visible`, `best_call_time`, `preferred_contact_method`, `is_verified`, `registration_completed`, `terms_accepted`, `is_employed`, `school_name`, `subscription_plan`, `subscription_expires_at`, `rating`, `review_count`, `search_count`, `featured`, `tutor_status`, `verification_documents`, `availability`, `structured_subjects`, `resubmission_token`, `resubmission_token_expires`, `resubmission_special_docs`) VALUES
(1, 'admin', 'admin@tutorconnectmw.com', '$argon2id$v=19$m=65536,t=4,p=1$bmpDeDUzNUx2TjN2U2xGcw$+O7bbvxgCaGJ0CNl9aG5Q/dlCvyU8oYq4kVOkhYMK4U', 'admin', 'System', NULL, 'Administrator', '+265999999999', '2025-12-24 14:00:05', NULL, NULL, 'f7065bdeec6e57e0104a7a02ac9e217025daaecb29f0e91cfa65eef44712cb2e', '2026-01-30 16:20:30', 1, NULL, NULL, NULL, '2025-12-24 14:00:05', '2026-01-30 15:20:30', NULL, NULL, NULL, 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 0, NULL, 'Basic', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(92, 'gift', 'giftbenala4@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$aEFlNDkzUUxEZE1YcDdNQQ$suoz6xC7PbJlCid7p6O0LoEHzKWHG0OGQGmUQoHDJkI', 'trainer', 'Gift', 'Male', 'Benala', '0881542241', '2026-01-07 15:02:58', NULL, NULL, NULL, NULL, 1, 'uploads/profile_photos/profile_92_1767802821.jpg', 'uploads/profile_photos/cover_92_1767802820.jpg', NULL, '2026-01-07 15:00:29', '2026-01-07 18:14:56', '2026-01-07 18:14:56', 'Phalombe', 'Phalombe boma', 7, 'both', 'am a teacher who enjoy teaching using different learner centered methodologies ', NULL, '881542241', 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 1, 0, 1, NULL, 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', '[{\"document_type\":\"national_id\",\"file_path\":\"uploads\\/documents\\/national_id_92_1767802821_5329.jpg\",\"original_filename\":\"17678026188251543132384291762236.jpg\",\"uploaded_at\":\"2026-01-07 16:20:21\"},{\"document_type\":\"academic_certificates\",\"file_path\":\"uploads\\/documents\\/academic_certificates_92_1767802821_4126.jpg\",\"original_filename\":\"17678026417671565726275825315646.jpg\",\"uploaded_at\":\"2026-01-07 16:20:22\"},{\"document_type\":\"teaching_qualification\",\"file_path\":\"uploads\\/documents\\/teaching_qualification_92_1767802822_7172.jpg\",\"original_filename\":\"17678027640014704386480863987697.jpg\",\"uploaded_at\":\"2026-01-07 16:20:23\"}]', '{\"days\":[\"Monday\",\"Tuesday\",\"Wednesday\",\"Thursday\",\"Friday\",\"Saturday\",\"Sunday\"],\"times\":[\"Morning (8AM-12PM)\",\"Evening (5PM-9PM)\"]}', NULL, NULL, NULL, NULL),
(94, 'Stanley', 'skalyati@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$RU5CSlRKSHpXSmJwbDNNSQ$FoPNTJ/lAUhMqOxk66gHrbpNLQVHTQ9PEwgBNQ05hus', 'trainer', 'Stanley', 'Male', 'Kalyati', '0992313978', '2026-01-07 17:36:03', NULL, NULL, '9baeff72c88722b0ff0a3f3c2ce643e5f3170d72160e59b6d9b6e329c33f3f0d', '2026-01-09 14:38:47', 1, 'uploads/profile_photos/profile_94_1767808498.jpg', 'uploads/profile_photos/cover_94_1767808498.png', '2026-01-07 17:57:21', '2026-01-07 17:35:27', '2026-04-04 10:51:46', NULL, 'Chiradzulu', 'Chiradzulu Boma', 3, 'both', 'Hello, I am Jane Doe, a dedicated Mathematics and Science teacher with over 5 years of experience teaching both online and in-person. I specialize in the Malawi National Curriculum and IGCSE programs, helping students excel in exams and develop critical thinking skills. My teaching approach is interactive and student-centered, ensuring concepts are understood and applied effectively. I offer flexible scheduling to suit individual learning needs and am passionate about empowering students to reach their full academic potential. I hold a Bachelor’s Degree in Education from the University of Malawi and a TESOL certification.\"', NULL, '992313978', 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 1, 1, 1, 'Chiradzulu Primary School', 'Basic', '2026-02-10', 0.0, 0, 0, 0, 'approved', '[{\"document_type\":\"national_id\",\"file_path\":\"uploads\\/documents\\/national_id_94_1767808498_4205.png\",\"original_filename\":\"Stan birthday 1.png\",\"uploaded_at\":\"2026-01-07 17:54:59\"},{\"document_type\":\"academic_certificates\",\"file_path\":\"uploads\\/documents\\/academic_certificates_94_1767808499_1009.png\",\"original_filename\":\"Underwater.png\",\"uploaded_at\":\"2026-01-07 17:54:59\"},{\"document_type\":\"teaching_qualification\",\"file_path\":\"uploads\\/documents\\/teaching_qualification_94_1767808499_7885.png\",\"original_filename\":\"Nomsa Nano.png\",\"uploaded_at\":\"2026-01-07 17:54:59\"}]', '{\"days\":[\"Saturday\",\"Sunday\"],\"times\":[\"Evening (5PM-9PM)\"]}', '{\"MANEB\":{\"levels\":{\"Primary: Standards 1\\u20138\":[\"Life Skills\"]}},\"GCSE\":{\"levels\":{\"Key Stage 4 (Years 10\\u201311)\":[\"Drama\"]}},\"Montessori\":{\"levels\":{\"Lower Elementary (Ages 6\\u20139)\":[\"Mathematics\"]}}}', NULL, NULL, NULL),
(97, 'Carolinekadwala1', 'carolinekadwala@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$cEMvcnR4cFdoemJDY0dBTg$jEm1KU1yQTzJ2BQXaNmKi4qRzK+4m08In2zBXeVIhjk', 'trainer', 'Caroline', 'Female', 'Kadwala', '0885843869', '2026-01-07 18:27:06', NULL, NULL, NULL, NULL, 1, 'uploads/profile_photos/profile_97_1767810683.jpeg', 'uploads/profile_photos/cover_97_1767810683.jpeg', '2026-01-07 18:44:36', '2026-01-07 18:25:58', '2026-04-04 10:51:46', NULL, 'Blantyre', 'Chiwembe', 5, 'both', 'Passionate educator with 5 years of experience in igniting curiosity in students. I specialize in making complex concepts simple and fun, focusing on interactive learning and real-world applications 🌟. Let\'s grow together! ', NULL, '885843869', 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 1, 0, 1, NULL, 'Free Trial', '2026-02-06', 0.0, 0, 0, 0, 'approved', '[{\"document_type\":\"national_id\",\"file_path\":\"uploads\\/documents\\/national_id_97_1767810683_7803.jpg\",\"original_filename\":\"Screenshot_2026-01-07-20-19-39-552_com.google.android.apps.photosgo.jpg\",\"uploaded_at\":\"2026-01-07 18:31:23\"},{\"document_type\":\"academic_certificates\",\"file_path\":\"uploads\\/documents\\/academic_certificates_97_1767810683_3814.jpg\",\"original_filename\":\"IMG_20250215_074022.jpg\",\"uploaded_at\":\"2026-01-07 18:31:23\"},{\"document_type\":\"teaching_qualification\",\"file_path\":\"uploads\\/documents\\/teaching_qualification_97_1767810683_5308.jpg\",\"original_filename\":\"IMG_20250215_074022.jpg\",\"uploaded_at\":\"2026-01-07 18:31:24\"}]', '{\"days\":[\"Friday\",\"Saturday\",\"Sunday\"],\"times\":[\"Afternoon (12PM-5PM)\"]}', '{\"MANEB\":{\"levels\":{\"Primary: Standards 1\\u20138\":[\"English\",\"Mathematics\"]}},\"Cambridge\":{\"levels\":{\"Cambridge Primary (Grades 1\\u20136)\":[\"English\",\"Mathematics\"]}}}', NULL, NULL, NULL),
(103, 'PatrickKasamba', 'patrickkasamba85@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$aE9TMWdkQXlaZ1k0Wmc5Lg$mD2RDjVeaJGK55QDsqG/sVsBii68IlSGb94OAh6jHU0', 'trainer', 'Patrick', 'Male', 'Kasamba', '0881836183', '2026-01-08 19:01:56', NULL, NULL, NULL, NULL, 1, 'uploads/profile_photos/profile_103_1767900462.jpg', 'uploads/profile_photos/cover_103_1767900462.jpg', '2026-01-09 07:09:41', '2026-01-08 18:55:28', '2026-04-04 10:51:46', NULL, 'Zomba', 'Sadzi', 5, 'both', 'I am a registered teacher with the Teachers’ Council of Malawi and hold a Bachelor of Science in Mathematical Sciences Education (Statistics and Computing)\r\nfrom the Malawi University of Business and Applied Sciences. I am a born-again Christian who believes that teaching is a divine calling and a platform for discipleship. I am passionate about integrating biblical truth into academic instruction, nurturing learners who are not only intellectually capable but also spiritually grounded.\r\n\r\nI have experience teaching Mathematics, Science, ICT, and Computer Studies at both primary and secondary school levels. At Robert Blake Secondary School, I helped an underperforming Mathematics class improve its MSCE results by 25% within one academic year through learner-centered teaching, continuous assessment, and academic mentoring. My teaching approach aligns with both national and cambridge learner outcomes, emphasizing critical thinking, problem-solving, independent learning, clear communication, and application of knowledge to real-world contexts. I am fluent and accurate in spoken and written English and confident in delivering content to international standards.', NULL, '881836183', 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 1, 0, 0, NULL, 'Free Trial', '2026-02-08', 0.0, 0, 0, 0, 'approved', '[{\"document_type\":\"national_id\",\"file_path\":\"uploads\\/documents\\/national_id_103_1767900462_8997.pdf\",\"original_filename\":\"PATRICK KASAMBA ID.pdf\",\"uploaded_at\":\"2026-01-08 19:27:42\"},{\"document_type\":\"academic_certificates\",\"file_path\":\"uploads\\/documents\\/academic_certificates_103_1767900462_3291.pdf\",\"original_filename\":\"PATRICK KASAMBA BACHELORS CERTIFICATE.pdf\",\"uploaded_at\":\"2026-01-08 19:27:42\"},{\"document_type\":\"teaching_qualification\",\"file_path\":\"uploads\\/documents\\/teaching_qualification_103_1767900462_9240.pdf\",\"original_filename\":\"PATRICK KASAMBA_TCM CERTIFICATE.pdf\",\"uploaded_at\":\"2026-01-08 19:27:43\"}]', '{\"days\":[\"Monday\",\"Tuesday\",\"Wednesday\",\"Thursday\",\"Friday\"],\"times\":[\"Morning (8AM-12PM)\",\"Afternoon (12PM-5PM)\"]}', '{\"MANEB\":{\"levels\":{\"Primary: Standards 1\\u20138\":[\"Mathematics\",\"Science and Technology\"]}}}', NULL, NULL, NULL),
(106, 'rodlick', 'rodlick.ndovie@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$M2pmeTFqdmdWTXlRWWFhTw$k8nkBFa1vmhYd0Glfvo6fuu4nwaxKzsg3YvVWBAZJHM', 'trainer', 'Rodlick', 'Male', 'Ndovie', '0996687622', '2026-01-09 14:03:53', NULL, NULL, NULL, NULL, 1, 'uploads/profile_photos/profile_106_1767967610.jpg', 'uploads/profile_photos/cover_106_1767967610.jpg', '2026-01-10 10:14:08', '2026-01-09 14:03:07', '2026-04-04 10:27:39', NULL, 'Lilongwe', 'Area 18B', 10, 'both', 'testing bio', NULL, '996687633', 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 1, 1, 0, NULL, 'Basic', '2026-07-04', 5.0, 1, 0, 0, 'approved', '[{\"document_type\":\"national_id\",\"file_path\":\"uploads\\/documents\\/national_id_106_1767967610_2332.pdf\",\"original_filename\":\"quotation_Q20250007.pdf\",\"uploaded_at\":\"2026-01-09 14:06:50\"},{\"document_type\":\"academic_certificates\",\"file_path\":\"uploads\\/documents\\/academic_certificates_106_1767967610_2903.pdf\",\"original_filename\":\"Olympus Report (1).pdf\",\"uploaded_at\":\"2026-01-09 14:06:50\"},{\"document_type\":\"teaching_qualification\",\"file_path\":\"uploads\\/documents\\/teaching_qualification_106_1767967610_4414.pdf\",\"original_filename\":\"Olympus Report (1).pdf\",\"uploaded_at\":\"2026-01-09 14:06:50\"},{\"document_type\":\"police_clearance\",\"file_path\":\"uploads\\/documents\\/police_clearance_106_1767967610_7142.pdf\",\"original_filename\":\"quotation_Q20250007.pdf\",\"uploaded_at\":\"2026-01-09 14:06:50\"}]', '{\"days\":[\"Wednesday\",\"Friday\"],\"times\":[\"Afternoon (12PM-5PM)\",\"Evening (5PM-9PM)\"]}', NULL, NULL, NULL, NULL),
(107, 'Moloco', 'molocomonica063@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$RmpOSW1HY2lLSC5OUnRiVw$6L2M+6TmFXauieGnMge6cUBLwKNE1L82QeAgmmDIdhM', 'trainer', 'Monica', 'Female', 'Moloco', '0884185768', '2026-01-10 04:05:47', NULL, NULL, NULL, NULL, 1, 'uploads/profile_photos/profile_107_1768330039.jpg', 'uploads/profile_photos/cover_107_1768330039.jpg', '2026-01-13 18:50:36', '2026-01-10 04:01:20', '2026-04-05 17:08:35', NULL, 'Lilongwe', 'Area 12/Kauma', 7, '', 'Miss Monica Moloco is an enthusiastic teacher of Chichewa,  English, Phonics, and other core subjects, committed to nurturing young learners. Within just 21 days of instruction, students often show remarkable progress—from improved phonics recognition and reading fluency to greater confidence in class participation. By combining creativity, structured lessons, and individualized support, Miss Monica Moloco inspires learners to achieve measurable growth in a short time while laying the foundation for long-term success. Miss Monica Moloco also works with children with Special Education Needs. She is a registered teacher by the Teachers Council of Malawi.', NULL, '884185768', 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 1, 1, 1, '', 'Premium', '2026-04-05', 5.0, 1, 0, 0, 'approved', '[{\"document_type\":\"national_id\",\"file_path\":\"uploads\\/documents\\/national_id_107_1768330039_6935.pdf\",\"original_filename\":\"ID.pdf\",\"uploaded_at\":\"2026-01-13 18:47:20\"},{\"document_type\":\"academic_certificates\",\"file_path\":\"uploads\\/documents\\/academic_certificates_107_1768330040_4341.pdf\",\"original_filename\":\"Certificates.pdf\",\"uploaded_at\":\"2026-01-13 18:47:21\"},{\"document_type\":\"teaching_qualification\",\"file_path\":\"uploads\\/documents\\/teaching_qualification_107_1768330041_2983.pdf\",\"original_filename\":\"Certificates.pdf\",\"uploaded_at\":\"2026-01-13 18:47:22\"}]', '{\"days\":[\"Monday\",\"Tuesday\",\"Wednesday\",\"Thursday\",\"Friday\",\"Saturday\"],\"times\":[\"Morning (8AM-12PM)\",\"Afternoon (12PM-5PM)\"]}', '{\"MANEB\":{\"levels\":{\"Primary: Standards 1\\u20138\":[\"Agriculture\",\"Chichewa\",\"English\",\"Expressive Arts\",\"Life Skills\",\"Mathematics\",\"Science and Technology\",\"Social and Environmental Sciences\"],\"Secondary: Forms 1\\u20134\":[\"Additional Mathematics\",\"Agriculture\",\"Biology\",\"Chichewa\",\"Computer Studies\",\"English Language\",\"Geography\",\"History\",\"Life Skills\",\"Mathematics\",\"Performing Arts\",\"Physics\",\"Religious and Moral Education\",\"Social Studies\"]}},\"GCSE\":{\"levels\":{\"Key Stage 4 (Years 10\\u201311)\":[\"Ancient Languages\",\"Art and Design\",\"Biology\",\"Computer Science\",\"Dance\",\"Drama\",\"English Language\",\"English Literature\",\"Food Preparation and Nutrition\",\"Geography\",\"History\",\"Mathematics\",\"Music\",\"Physical Education\",\"Physics\",\"Psychology\",\"Religious Studies\",\"Sociology\"]}},\"Cambridge\":{\"levels\":{\"Cambridge AS\\/A Level (Grades 12\\u201313)\":[\"Biology\",\"English Language\",\"Literature in English\",\"Music\"],\"Cambridge IGCSE (Grades 10\\u201311)\":[\"Drama\",\"English (First Language)\",\"English (Second Language)\",\"Food & Nutrition\"],\"Cambridge Lower Secondary (Grades 7\\u20139)\":[\"English\",\"English as a Second Language\",\"Mathematics\",\"Music\",\"Physical Education\",\"Science\"],\"Cambridge Primary (Grades 1\\u20136)\":[\"Art & Design\",\"Computing\",\"Digital Literacy\",\"English\",\"English as a Second Language\",\"Global Perspectives\",\"Humanities\",\"Mathematics\",\"Music\",\"Physical Education\",\"Science\",\"Wellbeing\"]}},\"ABEKA\":{\"levels\":{\"Kindergarten\":[\"Bible\",\"Mathematics\",\"Phonics & Reading\"],\"Primary (Grades 1\\u20136)\":[\"Bible\",\"English\",\"God\'s Gift of Language\",\"God\'s World\",\"Handwriting\",\"Health, Safety and Manners\",\"Letters and Sounds\",\"Mathematics\",\"Number Skills \\/ Arithmetic\",\"Reading and Comprehension\",\"Science\",\"Social Studies\",\"Spellings\"],\"Secondary (Grades 7\\u201312)\":[\"Bible Doctrine\",\"Biology\",\"English\",\"Mathematics\"]}},\"Montessori\":{\"levels\":{\"Early Childhood (Ages 2\\u20136)\":[\"Language Development\",\"Mathematics Concepts\",\"Sensorial Activities\"],\"Lower Elementary (Ages 6\\u20139)\":[\"Language Arts\",\"Mathematics\"]}},\"Rafiki\":{\"levels\":{\"Primary\":[\"Character Development\",\"Language Arts (English)\"]}}}', NULL, NULL, NULL),
(108, 'VBQ7G63S', 'fountainmbingwani43@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$NDliUFNEeEh5YlBUNTRHbg$WaLmB6P5I9AGoBiIUT7NI5F1yZSH3Ge57UfX1RanGoQ', 'trainer', 'Fountain', 'Male', 'Mathews Mbingwani', '0881312839', NULL, '628586', '2026-01-10 17:17:52', NULL, NULL, 0, NULL, NULL, NULL, '2026-01-10 17:02:52', '2026-01-10 17:25:19', '2026-01-10 17:25:19', 'Lilongwe', 'Area 12', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 0, 0, 0, 0, NULL, 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(113, 'GiftBenala', 'olc004524@must.ac.mw', '$argon2id$v=19$m=65536,t=4,p=1$ekp3Ry5oTXlPLm04aWY5OA$ONURBPyOmAvOiLuwzzZT/zHRunhr3Oj/MHC2bF9UMHY', 'trainer', 'Gift G', 'Male', 'Benala', '0881542241', '2026-01-13 16:14:47', NULL, NULL, NULL, NULL, 1, 'uploads/profile_photos/profile_113_1768321705.jpeg', 'uploads/profile_photos/cover_113_1768321705.jpeg', '2026-01-13 16:29:57', '2026-01-13 16:12:06', '2026-04-04 10:51:46', NULL, 'Phalombe', 'Phalombe Boma', 7, 'both', '.I am a qualified teacher by professional with over 7 years of teaching experience in both Chichewa and English. Teaching style: No boring ! We\'ll vibe with the subject, break it down, and make it stick . Expect interactive sessions, real-life examples,', NULL, '881542241', 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 1, 0, 1, 'Phalombe Primary School', 'Free Trial', '2026-02-12', 0.0, 0, 0, 0, 'approved', '[{\"document_type\":\"national_id\",\"file_path\":\"uploads\\/documents\\/national_id_113_1768321705_5498.jpeg\",\"original_filename\":\"National ID.jpeg\",\"uploaded_at\":\"2026-01-13 16:28:25\"},{\"document_type\":\"academic_certificates\",\"file_path\":\"uploads\\/documents\\/academic_certificates_113_1768321705_8555.jpeg\",\"original_filename\":\"MSCE.jpeg\",\"uploaded_at\":\"2026-01-13 16:28:25\"},{\"document_type\":\"teaching_qualification\",\"file_path\":\"uploads\\/documents\\/teaching_qualification_113_1768321705_6383.jpeg\",\"original_filename\":\"Teaching certificate.jpeg\",\"uploaded_at\":\"2026-01-13 16:28:25\"}]', '{\"days\":[\"Monday\",\"Tuesday\",\"Wednesday\",\"Thursday\",\"Friday\",\"Saturday\",\"Sunday\"],\"times\":[\"Afternoon (12PM-5PM)\"]}', '{\"MANEB\":{\"levels\":{\"Primary: Standards 1\\u20138\":[\"Chichewa\",\"English\"]}}}', NULL, NULL, NULL),
(114, 'Fountain', 'stanley.kalyati@visualspacemw.com', '$argon2id$v=19$m=65536,t=4,p=1$QVZkWE9CYlpqeHVHZXhIVg$DkPPIQbl4ajCESWldL8f8gvc1Y+XyRTNEna0Fp7fmCQ', 'trainer', 'Fountain Mathews', 'Male', 'Mbingwani', '+265881312839', '2026-01-13 17:26:58', NULL, NULL, NULL, NULL, 1, 'uploads/profile_photos/profile_114_1768326584.jpeg', 'uploads/profile_photos/cover_114_1768326584.jpeg', '2026-01-13 17:50:55', '2026-01-13 17:25:58', '2026-04-04 10:51:46', NULL, 'Lilongwe', 'Area 12', 8, 'both', 'Highly experienced English language educator with over 8 years of teaching expertise in Cambridge International, International Baccalaureate, and Maneb curricula. Proven track record of driving academic excellence and fostering a love for language and literature. Passionate about empowering students to achieve their full potential and igniting a curiosity that lasts a lifetime. Skilled in crafting engaging lessons that blend global perspectives with local context to make learning more impactful.', NULL, '881312839', 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 1, 0, 1, 'Access High School', 'Free Trial', '2026-02-12', 0.0, 0, 0, 0, 'approved', '[{\"document_type\":\"national_id\",\"file_path\":\"uploads\\/documents\\/national_id_114_1768326584_8525.jpeg\",\"original_filename\":\"National ID.jpeg\",\"uploaded_at\":\"2026-01-13 17:49:44\"},{\"document_type\":\"academic_certificates\",\"file_path\":\"uploads\\/documents\\/academic_certificates_114_1768326584_3558.jpeg\",\"original_filename\":\"MSCE.jpeg\",\"uploaded_at\":\"2026-01-13 17:49:44\"},{\"document_type\":\"teaching_qualification\",\"file_path\":\"uploads\\/documents\\/teaching_qualification_114_1768326584_1901.jpeg\",\"original_filename\":\"Diploma.jpeg\",\"uploaded_at\":\"2026-01-13 17:49:44\"}]', '{\"days\":[\"Monday\",\"Tuesday\",\"Wednesday\",\"Thursday\",\"Friday\",\"Saturday\",\"Sunday\"],\"times\":[\"Afternoon (12PM-5PM)\"]}', '{\"Cambridge\":{\"levels\":{\"Cambridge AS\\/A Level (Grades 12\\u201313)\":[\"English Language\",\"Literature in English\"]}}}', NULL, NULL, NULL),
(115, 'Zealf', '4bellezema@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$SG1XTlJCVnovd0ttOG9sZw$IQXNAlsdkmyqMnId5SRdXP4uzH7SMvbQAUFraEcXKrU', 'trainer', 'Zema', 'Female', 'Alfazema', '+265999131120', '2026-01-16 10:19:02', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-01-16 10:17:42', '2026-03-14 11:42:56', '2026-03-14 11:42:56', 'Lilongwe', 'Area 49', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 1, 'Lilongwe Girls Secondary school', 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(116, 'Sir_Khalied', 'khalibwina@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$eGUuZEFTTHdlYy5WcUhlVA$sds8VoebrIdqzaMfWHPBChDzapt3VpiwKRPZFF0MkXE', 'trainer', 'Khalibwina', 'Male', 'Williams', '0884796691', '2026-01-16 11:00:02', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-01-16 10:58:24', '2026-03-14 11:43:16', '2026-03-14 11:43:16', 'Balaka', 'Balaka Town', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 1, NULL, 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(117, 'teacher1', 'kondwanichristopher112@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$ZkdqMTdNVUZkalE4VjB2Vg$WpXrkS7pkMKNBODRJ5nOkrNHUR1pt1lKIOPDbjZtmjg', 'trainer', 'Kondwani T', 'Male', 'Christopher', '0992410140', '2026-01-17 10:47:29', NULL, NULL, NULL, NULL, 1, 'uploads/profile_photos/profile_117_1769536313.jpg', 'uploads/profile_photos/cover_117_1768686037.jpg', '2026-01-27 17:26:02', '2026-01-17 10:46:28', '2026-04-04 10:51:46', NULL, 'Lilongwe', 'Area 24', 2, '', 'I persued Bachelor\'s degree in education French at Mzuzu university where i majored French and English with Geography as a minor. I have experience in teaching both primary and secondary sections ', NULL, '992410140', 1, 1, 'Afternoon (12PM-5PM)', 'phone', 1, 1, 0, 0, '', 'Free Trial', '2026-02-26', 0.0, 0, 0, 0, 'approved', '[{\"document_type\":\"national_id\",\"file_path\":\"uploads\\/documents\\/national_id_117_1768686037_8132.jpg\",\"original_filename\":\"IMG_20180101_020341_743.jpg\",\"uploaded_at\":\"2026-01-17 21:40:38\"},{\"document_type\":\"academic_certificates\",\"file_path\":\"uploads\\/documents\\/academic_certificates_117_1768686038_4168.jpg\",\"original_filename\":\"IMG_20180101_020530_098~2.jpg\",\"uploaded_at\":\"2026-01-17 21:40:38\"},{\"document_type\":\"teaching_qualification\",\"file_path\":\"uploads\\/documents\\/teaching_qualification_117_1768686038_7626.pdf\",\"original_filename\":\"DocScanner Jul 30, 2025 14-27.pdf\",\"uploaded_at\":\"2026-01-17 21:40:38\"}]', '{\"days\":[\"Monday\",\"Tuesday\",\"Wednesday\",\"Thursday\",\"Friday\"],\"times\":[\"Evening (5PM-9PM)\"]}', '{\"GCSE\":{\"levels\":{\"Key Stage 4 (Years 10\\u201311)\":[\"English Literature\"]}},\"Cambridge\":{\"levels\":{\"Cambridge AS\\/A Level (Grades 12\\u201313)\":[\"French\"]}}}', NULL, NULL, NULL),
(118, 'Christopherndalama1', 'christopherndalama11@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$UGM2RmZFN3g4NU1iaTJJRQ$oKWo8rK3IRmtc4+VZ00KdCp78HyS5L2RtJnouVixTcw', 'trainer', 'Christopher', 'Male', 'Ndalama', '0881839016', '2026-01-17 14:02:55', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-01-17 14:02:16', '2026-03-14 11:43:29', '2026-03-14 11:43:29', 'Thyolo', 'Chipendo Trading', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 1, 'Nantchefu', 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(119, 'ZebronChipate', 'chipatezebron@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$Sy9jbWNaSjV4QWczYjQ4Yg$OQG+wzzYvrRMMzgNOKBSUN8ykwkrcQZrGfH5K9Wo1RI', 'trainer', 'Zebron', 'Male', 'Chipate', '0998829933', '2026-01-17 18:09:10', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-01-17 18:08:31', '2026-03-14 11:48:07', '2026-03-14 11:48:07', 'Chiradzulu', 'Mbulumbudzi', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 1, NULL, 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(121, 'chimwemwemalefulah', 'chimwemwemalefula34@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$S1lKTWZ4L290UFE4OVV1dQ$FyPc18Nskv9hAzoEph5E6Bq2gBtuznkxiCRfFc0rlT8', 'trainer', 'CHIMWEMWE', 'Male', 'MALEFULA', '0996729666', '2026-01-17 21:02:25', NULL, NULL, NULL, NULL, 1, 'uploads/profile_photos/profile_121_1768713345.jpg', 'uploads/profile_photos/cover_121_1768713345.jpg', '2026-01-18 06:22:30', '2026-01-17 21:01:19', '2026-04-04 10:51:46', NULL, 'Blantyre', 'Kanjedza', 5, '', 'Demonstration, Focus Group and discussion, Diploma and certificate in Education', NULL, '881632089', 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 1, 0, 1, 'Chichiri Primary School', 'Free Trial', '2026-02-17', 0.0, 0, 0, 0, 'approved', '[{\"document_type\":\"national_id\",\"file_path\":\"uploads\\/documents\\/national_id_121_1768713345_7418.jpg\",\"original_filename\":\"20231101_124222.jpg\",\"uploaded_at\":\"2026-01-18 05:15:46\"},{\"document_type\":\"academic_certificates\",\"file_path\":\"uploads\\/documents\\/academic_certificates_121_1768713346_9123.pdf\",\"original_filename\":\"diploma.pdf\",\"uploaded_at\":\"2026-01-18 05:15:46\"},{\"document_type\":\"teaching_qualification\",\"file_path\":\"uploads\\/documents\\/teaching_qualification_121_1768713346_2758.pdf\",\"original_filename\":\"Complete_English_grammar.pdf\",\"uploaded_at\":\"2026-01-18 05:15:47\"},{\"document_type\":\"police_clearance\",\"file_path\":\"uploads\\/documents\\/police_clearance_121_1768713347_3174.pdf\",\"original_filename\":\"Complete_English_grammar.pdf\",\"uploaded_at\":\"2026-01-18 05:15:47\"}]', '{\"days\":[\"Monday\",\"Tuesday\",\"Wednesday\",\"Thursday\",\"Sunday\"],\"times\":[\"Evening (5PM-9PM)\"]}', '{\"MANEB\":{\"levels\":{\"Primary: Standards 1\\u20138\":[\"Mathematics\",\"Science and Technology\"]}}}', NULL, NULL, NULL),
(122, 'Derbie2002', 'derbiesaopa@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$dFJ5YUdOYWdlZm5Iby92Tg$91nTqgQujvoiNX3wd/WdpPKSyEOCyrGiagHDHbZPxx0', 'trainer', 'Derbie', 'Female', 'Saopa', '0995263123', '2026-01-19 14:16:37', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-01-19 14:15:50', '2026-03-14 11:48:34', '2026-03-14 11:48:34', 'Zomba', 'Mulunguzi', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 0, NULL, 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(123, 'mercymkwala', 'mercymkwala2@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$TjkvTGFoTXRXVy91VjdoTw$XSLJwzic9jgugfrM+ZMyNwgxw/ClwCeHagwook+6las', 'trainer', 'Mercy', 'Female', 'Mkwala', '0882580414', '2026-01-21 13:49:51', NULL, NULL, NULL, NULL, 1, 'uploads/profile_photos/profile_123_1769004315.jpeg', 'uploads/profile_photos/cover_123_1769004315.jpeg', '2026-01-21 14:08:36', '2026-01-21 13:47:20', '2026-04-04 10:51:46', NULL, 'Lilongwe', 'Area 2', 7, 'both', 'I am an experienced educator passionate about making learning fun and engaging. My teaching style focuses on simple explanations and practical examples so students can understand and apply concepts easily. I hold qualifications in education and have years of experience helping students succeed in various subjects.', NULL, '882580414', 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 1, 0, 1, 'Sapitwa  International School', 'Free Trial', '2026-02-20', 0.0, 0, 0, 0, 'approved', '[{\"document_type\":\"national_id\",\"file_path\":\"uploads\\/documents\\/national_id_123_1769004315_2173.pdf\",\"original_filename\":\"id.pdf\",\"uploaded_at\":\"2026-01-21 14:05:15\"},{\"document_type\":\"academic_certificates\",\"file_path\":\"uploads\\/documents\\/academic_certificates_123_1769004315_4086.pdf\",\"original_filename\":\"2-5.pdf\",\"uploaded_at\":\"2026-01-21 14:05:16\"},{\"document_type\":\"teaching_qualification\",\"file_path\":\"uploads\\/documents\\/teaching_qualification_123_1769004316_1843.pdf\",\"original_filename\":\"6-8.pdf\",\"uploaded_at\":\"2026-01-21 14:05:16\"}]', '{\"days\":[\"Monday\",\"Wednesday\",\"Friday\",\"Sunday\"],\"times\":[\"Afternoon (12PM-5PM)\"]}', '{\"Cambridge\":{\"levels\":{\"Cambridge Lower Secondary (Grades 7\\u20139)\":[\"English\",\"Science\"]}}}', NULL, NULL, NULL),
(124, 'Nebertben', 'nebertben@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$TDMyUHZaS0xZay9mZWtkSQ$uyH/iHOTpyNfmUGV/oHtpt67G5XzueyUGD9aWMzsKC0', 'trainer', 'Nebert', 'Male', 'Banda', '0884161883', '2026-01-22 06:47:28', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-01-22 06:45:54', '2026-03-14 11:48:46', '2026-03-14 11:48:46', 'Lilongwe', 'Likuni', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 1, 'Likuni Boy\'s Secondary School', 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(125, 'Miss_Emie21', 'emildakumwenda@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$dndBNG8uRmQ0czlEd01vWQ$bNt2Y+384RoKxKKgnuOCd2jv3o9Pugib/PA5krOdUPs', 'trainer', 'Emilda', 'Female', 'Kumwenda', '0994295577', '2026-01-22 07:21:56', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-01-22 07:21:04', '2026-03-14 11:48:58', '2026-03-14 11:48:58', 'Lilongwe', 'Area 18', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 0, NULL, 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(126, 'mgawamkandawire', 'mgawamkandawire@yahoo.com', '$argon2id$v=19$m=65536,t=4,p=1$NWVyQ3Zub3V2RDRscXNJSQ$TR41Vinl2pC58XeSjfuU2TA5/M5khDjrK89QOAH0CXY', 'trainer', 'Mgawa', 'Male', 'Mkandawire', '0997956391', '2026-01-22 09:29:21', NULL, NULL, NULL, NULL, 1, 'uploads/profile_photos/profile_126_1769074446.png', 'uploads/profile_photos/cover_126_1769074446.png', '2026-01-22 10:04:48', '2026-01-22 09:28:44', '2026-04-04 10:51:46', NULL, 'Lilongwe', 'Lilongwe', 15, 'both', 'University of Cambridge graduate, Chartered Chemical Engineer by training and profession with a passion for Mathematics and Sciences. Over a Decade’s experience as a tutor for IGCSE, MYP, A-Level, IB, SAT, GMAT and University standards. \r\nSuccessfully prepared students for end of school exams and entry into Ivy league and Russel Group universities. Professional qualifications assessor for the Institution of Chemical Engineers\r\n', NULL, '880091422', 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 1, 0, 1, 'MM Consult', 'Free Trial', '2026-02-21', 0.0, 0, 0, 0, 'approved', '[{\"document_type\":\"national_id\",\"file_path\":\"uploads\\/documents\\/national_id_126_1769074446_2592.jpg\",\"original_filename\":\"Mgawa Mkandawire National ID (2).jpg\",\"uploaded_at\":\"2026-01-22 09:34:06\"},{\"document_type\":\"academic_certificates\",\"file_path\":\"uploads\\/documents\\/academic_certificates_126_1769074446_9720.pdf\",\"original_filename\":\"MASTERS CERTIFICATE (1).PDF\",\"uploaded_at\":\"2026-01-22 09:34:06\"},{\"document_type\":\"teaching_qualification\",\"file_path\":\"uploads\\/documents\\/teaching_qualification_126_1769074446_7225.pdf\",\"original_filename\":\"BACHELORS CERTIFICAT (1).PDF\",\"uploaded_at\":\"2026-01-22 09:34:06\"},{\"document_type\":\"police_clearance\",\"file_path\":\"uploads\\/documents\\/police_clearance_126_1769074446_4949.pdf\",\"original_filename\":\"Police+Background+Clearance.pdf\",\"uploaded_at\":\"2026-01-22 09:34:06\"}]', '{\"days\":[\"Monday\",\"Tuesday\",\"Wednesday\",\"Thursday\",\"Friday\",\"Saturday\",\"Sunday\"],\"times\":[\"Morning (8AM-12PM)\",\"Afternoon (12PM-5PM)\",\"Evening (5PM-9PM)\"]}', '{\"Cambridge\":{\"levels\":{\"Cambridge AS\\/A Level (Grades 12\\u201313)\":[\"Further Mathematics\",\"Physics\"]}}}', NULL, NULL, NULL),
(132, 'Josephy', 'mtemajosephy2@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$YlQ2SnQ1b3dka3paNVVnMA$tOC8WiR1HDfoxJUe0GlHG4yP2pXxfdxP54FddSIcDko', 'trainer', 'josephy', 'Male', 'mtema', '0886920821', '2026-01-24 20:11:12', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-01-24 20:08:17', '2026-01-27 17:27:46', '2026-01-27 17:27:46', 'Nkhotakota', 'Dwangwa', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 0, NULL, 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(133, 'Robin', 'robinkuchombo@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$UE9GcDJCZ0Q0ZlM3R0Rwcw$TpZjSzdyKEOWkPvSeXE3eWBmoQJHA6gS5omD5cqWbDk', 'trainer', 'Robin', 'Male', 'Kuchombo', '+265997237761', '2026-01-24 22:57:40', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-01-24 22:57:12', '2026-01-27 17:27:33', '2026-01-27 17:27:33', 'Blantyre', 'Manja', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 1, 'Revenue Appeals Tribunal', 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(134, 'Romary', 'romarynnensa@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$eWJFQWtkU1ZhR01PR1dvWg$Pk7wPOomOzAEQHx4W39Bf/veAoldJ30QHtdMFMFWU/4', 'trainer', 'Rosemary', 'Female', 'Kumwenda', '0991026345', '2026-01-28 04:08:49', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-01-28 04:07:27', '2026-03-14 11:51:46', '2026-03-14 11:51:46', 'Lilongwe', 'Area 49 New Guilliver', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 1, NULL, 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(135, 'Success', 'saccessmasauli@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$MDMvbDZId2RicGEvazRtaQ$Z25hMbFZkvIK2m6L7v+htOiMm+NrJyaCgijAyP2vCtU', 'trainer', 'Success', 'Male', 'Masauli', '0990150238', '2026-01-29 12:18:31', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-01-29 12:17:52', '2026-03-14 11:52:09', '2026-03-14 11:52:09', 'Zomba', 'Matawale', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 1, NULL, 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(136, 'Shalom_Manda', 'mandashalom03@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$OHAwdjl1SWloQmxjNHQzNA$67oMSRRhpZZXneRvCR5H+aezrRL6rAmq+jYVp8QL+oY', 'trainer', 'Shalom', 'Female', 'Manda', '0889894450', '2026-01-30 11:05:57', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-01-30 11:05:07', '2026-03-14 11:52:24', '2026-03-14 11:52:24', 'Mzimba', 'Mzuzu', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 1, NULL, 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(137, 'Mizz_Uzeni', 'uzkaime@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$cEFKWFRKb1c2VnJZZVJXNA$fsYAncqucqHO3XsI+hirDZ9/5AScaUbcdzGn9XMyQtw', 'trainer', 'Uzeni Audrey', 'Female', 'Kaime', '+265888607852', '2026-01-30 13:29:56', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-01-30 13:28:48', '2026-03-14 11:52:42', '2026-03-14 11:52:42', 'Blantyre', 'Chigumula', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 1, 'Phoenix International primary school', 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(138, 'TheCodingWiz', 'thecodingwiz@proton.me', '$argon2id$v=19$m=65536,t=4,p=1$T2h0d3A3TVdXT21sZFJZcw$bpeeUrF91icJymW7kbhrWkFeLMYZzXF4dpu21u6F7JU', 'trainer', 'System', 'Male', 'Account', '0880000000', '2026-01-30 14:50:18', NULL, NULL, '7832079aa82bbb98062831b2e8ce7a768f64e9092a4730261b16b3e446d52ef9', '2026-01-30 16:33:18', 1, 'uploads/profile_photos/profile_138_1769785035.jpg', 'uploads/profile_photos/cover_138_1769785035.jpg', NULL, '2026-01-30 14:48:55', '2026-01-31 12:53:34', '2026-01-31 12:53:34', 'Mzimba', 'Mzuzu', 2, 'both', 'I\'m the greatest Math and Physics teacher you\'ll ever meet.', NULL, '880000000', 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 1, 0, 0, NULL, 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', '[{\"document_type\":\"national_id\",\"file_path\":\"uploads\\/documents\\/national_id_138_1769785035_8240.jpg\",\"original_filename\":\"622008902_1194173589597533_7057194710332692845_n.jpg\",\"uploaded_at\":\"2026-01-30 14:57:15\"},{\"document_type\":\"academic_certificates\",\"file_path\":\"uploads\\/documents\\/academic_certificates_138_1769785035_4091.jpg\",\"original_filename\":\"622008902_1194173589597533_7057194710332692845_n.jpg\",\"uploaded_at\":\"2026-01-30 14:57:15\"},{\"document_type\":\"teaching_qualification\",\"file_path\":\"uploads\\/documents\\/teaching_qualification_138_1769785035_9557.jpg\",\"original_filename\":\"622008902_1194173589597533_7057194710332692845_n.jpg\",\"uploaded_at\":\"2026-01-30 14:57:15\"}]', '{\"days\":[],\"times\":[]}', NULL, NULL, NULL, NULL),
(140, 'Vincent', 'matambovincent7@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$RlVjZnp1ajJvSTZNTDVieg$4q/416CKwVUlCU8IdJ75g2yZKVJ6uzKCSIPCpJsKGd8', 'trainer', 'Vincent', 'Male', 'Matambo', '0997613888', '2026-01-31 08:55:01', NULL, NULL, NULL, NULL, 1, 'uploads/profile_photos/profile_140_1769850141.jpg', 'uploads/profile_photos/cover_140_1769850141.jpg', '2026-01-31 12:52:16', '2026-01-31 08:54:14', '2026-04-04 10:51:46', NULL, 'Lilongwe', 'Area 25c', 1, 'both', 'I am a Computer Science graduate with 1 year of teaching experience, offering instruction both online and in person. I specialize in explaining technical concepts in a clear, structured, and practical manner, making complex topics easy to understand. My teaching approach emphasizes hands-on learning, real-world examples, and adaptability to different learning styles and paces. I strive to create a supportive and engaging environment that helps learners build strong problem-solving skills and confidence in technology.', NULL, '997613888', 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 1, 0, 0, NULL, 'Free Trial', '2026-03-02', 0.0, 0, 0, 0, 'approved', '[{\"document_type\":\"national_id\",\"file_path\":\"uploads\\/documents\\/national_id_140_1769850142_2413.jpg\",\"original_filename\":\"WhatsApp Image 2025-04-09 at 12.07.18_728c8933.jpg\",\"uploaded_at\":\"2026-01-31 09:02:22\"},{\"document_type\":\"academic_certificates\",\"file_path\":\"uploads\\/documents\\/academic_certificates_140_1769850142_5346.pdf\",\"original_filename\":\"Vincent Matambo Degree.pdf\",\"uploaded_at\":\"2026-01-31 09:02:22\"},{\"document_type\":\"teaching_qualification\",\"file_path\":\"uploads\\/documents\\/teaching_qualification_140_1769850142_3859.pdf\",\"original_filename\":\"Vincent Matambo Degree.pdf\",\"uploaded_at\":\"2026-01-31 09:02:23\"}]', '{\"days\":[\"Monday\",\"Tuesday\",\"Wednesday\",\"Thursday\",\"Friday\"],\"times\":[\"Afternoon (12PM-5PM)\"]}', NULL, NULL, NULL, NULL),
(142, 'Mr_H_LOTI98', 'lotihope@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$SVNTeEx4UmNzRWFMa1BHaQ$9gasH+spadAwMQVDHzu7jAKTYbOoM9uZ1Jc6nExE3Vw', 'trainer', 'Hope', 'Male', 'Loti', '0993126733', '2026-02-02 12:37:34', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-02-02 12:31:00', '2026-03-14 12:05:15', '2026-03-14 12:05:15', 'Lilongwe', 'Area 18B', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 1, 'Kasupe Ministries Secondary School', 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(143, 'Wisdom2002', 'chibisawisdom@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$bmx1NUgvWlRNYzV3VkxCZw$H+ucNK0dhzOO+/kjJvYH6we2kljJHwbWO9BIO15gSak', 'trainer', 'Wisdom', 'Male', 'Chibisa', '0899972033', '2026-02-02 12:38:54', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-02-02 12:37:53', '2026-03-14 12:05:33', '2026-03-14 12:05:33', 'Lilongwe', 'Bunda turnoff', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 1, NULL, 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(145, 'Winnie_04', 'winniwa@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$WFp1YUVyLzVxRmlpQ21Ncg$drh3sxxIs/2SrgsP5nEifPOEJylyayzp1e51oCkyhXA', 'trainer', 'Winniwa', 'Female', 'Bendulo', '0995742960', NULL, '295009', '2026-02-03 07:46:12', NULL, NULL, 0, NULL, NULL, NULL, '2026-02-03 07:31:12', '2026-03-14 12:05:47', '2026-03-14 12:05:47', 'Lilongwe', 'Likuni', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 0, 0, 0, 1, 'Good Shepherd International school', 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(146, 'Love', 'lovenessbwanali5@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$aTBPSjNkSzdDMmdBWXZwOQ$QZX/myiA3U3hBDHa+1sUsHVH779PSAtkiTTdTiKkG5M', 'trainer', 'Loveness', 'Female', 'Bwanali', '0995441413', '2026-02-03 09:44:05', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-02-03 09:43:03', '2026-03-14 12:06:02', '2026-03-14 12:06:02', 'Blantyre', 'Ci', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 1, 'Startsmart', 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(147, 'Ochunga3', 'athiyachunga2@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$N2xidC5vNmdNeHZJVVdEdA$6bgWoqt2BlvM6z0pcqeskWwPbI0GcqzoZqVLpKlYrlc', 'trainer', 'Tiyamike', 'Male', 'Chunga', '0999121007', '2026-02-03 12:43:43', NULL, NULL, NULL, NULL, 1, 'uploads/profile_photos/profile_147_1772519175.jpg', 'uploads/profile_photos/cover_147_1772519175.jpg', '2026-03-03 06:42:13', '2026-02-03 12:42:17', '2026-04-04 10:51:46', NULL, 'Lilongwe', 'Likuni', 4, 'both', 'I am a qualified secondary school teacher specializing in Mathematics, Physics, Computer Studies and Chemistry. I hold a Bachelor’s degree in Education (Computer Science major, Physics minor) and have over 4 years of teaching and tutoring experience.\r\nI focus on helping students understand concepts step-by-step rather than memorizing answers. My lessons improve problem-solving skills, exam technique, and confidence — especially for learners who find science subjects challenging.\r\nI assist with homework, revision, and exam preparation (MSCE/IGCSE level), including past paper practice and targeted weak-area support.\r\nI offer both online and in-person lessons. My goal is simple: with the right guidance and practice, every student can improve', NULL, '999121007', 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 1, 1, 1, 'Mbinzi SECONDARY SCHOOL', 'Premium', '2026-04-03', 0.0, 0, 0, 0, 'approved', '[{\"document_type\":\"national_id\",\"file_path\":\"uploads\\/documents\\/national_id_147_1772519974.jpeg\",\"original_filename\":\"ID Card 17725199435951.jpeg\",\"uploaded_at\":\"2026-03-03 06:39:34\"},{\"document_type\":\"academic_certificates\",\"file_path\":\"uploads\\/documents\\/academic_certificates_147_1772519175_6171.jpeg\",\"original_filename\":\"degree 17725189942031.jpeg\",\"uploaded_at\":\"2026-03-03 06:26:15\"},{\"document_type\":\"teaching_qualification\",\"file_path\":\"uploads\\/documents\\/teaching_qualification_147_1772519175_6574.jpg\",\"original_filename\":\"17725188130662925128281377284468.jpg\",\"uploaded_at\":\"2026-03-03 06:26:15\"},{\"document_type\":\"police_clearance\",\"file_path\":\"uploads\\/documents\\/police_clearance_147_1772519175_8992.jpeg\",\"original_filename\":\"degree 17725189942031.jpeg\",\"uploaded_at\":\"2026-03-03 06:26:15\"}]', '{\"days\":[\"Monday\",\"Tuesday\",\"Wednesday\",\"Thursday\",\"Friday\",\"Saturday\",\"Sunday\"],\"times\":[\"Morning (8AM-12PM)\",\"Afternoon (12PM-5PM)\",\"Evening (5PM-9PM)\"]}', '{\"MANEB\":{\"levels\":{\"Primary: Standards 1\\u20138\":[\"Mathematics\",\"Science and Technology\"],\"Secondary: Forms 1\\u20134\":[\"Additional Mathematics\",\"Biology\",\"Chemistry\",\"Computer Studies\",\"Mathematics\",\"Physics\"]}},\"GCSE\":{\"levels\":{\"Key Stage 4 (Years 10\\u201311)\":[\"Biology\",\"Chemistry\",\"Computer Science\",\"Mathematics\",\"Physics\"]}},\"Cambridge\":{\"levels\":{\"Cambridge AS\\/A Level (Grades 12\\u201313)\":[\"Biology\",\"Chemistry\",\"Computer Science\",\"Further Mathematics\",\"Mathematics\",\"Physics\"],\"Cambridge IGCSE (Grades 10\\u201311)\":[\"Additional Mathematics\",\"Computer Science\",\"ICT\",\"Mathematics\",\"Physics\"],\"Cambridge Lower Secondary (Grades 7\\u20139)\":[\"Mathematics\",\"Science\"],\"Cambridge Primary (Grades 1\\u20136)\":[\"Mathematics\",\"Science\"]}},\"ABEKA\":{\"levels\":{\"Primary (Grades 1\\u20136)\":[\"Mathematics\",\"Science\"],\"Secondary (Grades 7\\u201312)\":[\"Biology\",\"Chemistry\",\"Mathematics\",\"Physics\"]}},\"Montessori\":{\"levels\":{\"Upper Elementary (Ages 9\\u201312)\":[\"Advanced Mathematics\",\"Science Exploration\"]}}}', NULL, NULL, NULL),
(148, 'ChifundoKathamalo99', 'chifundoceciliakathamalo@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$UHhCeW9FdnJFUDNsdElSRA$BdoJ2gzqZpmFE9KH/k72mBv8m22DsAV+BoZTs2OqTQs', 'trainer', 'Chifundo', 'Female', 'Kathamalo', '+265880764946', NULL, '683852', '2026-02-03 20:48:29', NULL, NULL, 0, NULL, NULL, NULL, '2026-02-03 20:33:29', '2026-03-14 12:06:19', '2026-03-14 12:06:19', 'Blantyre', 'Chirimba', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 0, 0, 0, 1, NULL, 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(149, 'rodgerskugona', 'rkugona@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$QXEydUFua1IxMUw0SEtmVQ$8H71fALbsML8dbpaADp6ITMo2VW6O+W0T0qHW1+hm8c', 'trainer', 'Rodgers', 'Male', 'Kugona', '0886444400', '2026-02-03 20:57:01', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-02-03 20:55:55', '2026-03-14 12:06:35', '2026-03-14 12:06:35', 'Lilongwe', 'Area 23', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 1, 'Ministry of education, Mcheuka secondary achool', 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(151, '025TCM30333471', 'benesbright19@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$MmlURHYuc0pJMlJsYnEuSQ$72dK9InmtgE5eZBlaG7lRtkqn+0PaEU1cgzBBr+sFYU', 'trainer', 'Bright', 'Male', 'Benes', '0999021190', '2026-02-04 14:33:16', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-02-04 14:32:00', '2026-03-14 12:06:54', '2026-03-14 12:06:54', 'Lilongwe', '6 miles', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 1, NULL, 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(152, 'joseph', 'josephmabangwe09@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$YUxxdU1LQzdqQnZ3VTVEdw$B4GnGIQAZ2vph3T95uRazKFFca7NkNwqmK2+tQyZZa4', 'trainer', 'Joseph', 'Male', 'Mabangwe', '0889887182', '2026-02-04 14:54:34', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-02-04 14:51:25', '2026-03-14 12:07:10', '2026-03-14 12:07:10', 'Mzimba', 'Mzuzu', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 0, NULL, 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(153, 'gome', 'gmsiska.msiska80@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$VEFuWFBtWmxGRnpUV2hPTA$hqVyGsrS2DQk0pv4PcsJW0q3W9pooPh5iFu9cnNJ9TM', 'trainer', 'Gomezgani', 'Male', 'Msiska', '0881512402', '2026-02-05 05:25:10', NULL, NULL, NULL, NULL, 1, 'uploads/profile_photos/profile_153_1770296862.jpeg', 'uploads/profile_photos/cover_153_1770296862.jpeg', '2026-02-05 13:27:42', '2026-02-05 05:24:26', '2026-04-04 10:51:46', NULL, 'Mangochi', 'mangochi', 8, '', 'I am Gomezgani Msiska, a qualified teacher with a Degree in Education sciences and over 8 years of experience teaching Mathematics and Physics. I have a strong record of achieving over 90% pass rates by making complex concepts simple and easy to understand. My online lessons are interactive, learner-centered, and tailored to individual needs. I focus on building confidence, problem-solving skills, and exam success in a supportive learning environment.', 'uploads/videos/intro_video_153_1770296862.mp4', '881512402', 1, 1, 'Morning (8AM-12PM)', 'phone', 1, 1, 0, 1, 'ST Monica Girls Secondary School', 'Free Trial', '2026-03-07', 0.0, 0, 0, 0, 'approved', '[{\"document_type\":\"national_id\",\"file_path\":\"uploads\\/documents\\/national_id_153_1770296864_8313.jpeg\",\"original_filename\":\"ID.jpeg\",\"uploaded_at\":\"2026-02-05 13:07:44\"},{\"document_type\":\"academic_certificates\",\"file_path\":\"uploads\\/documents\\/academic_certificates_153_1770296864_1657.jpg\",\"original_filename\":\"Gomezgani\'s degree.jpg\",\"uploaded_at\":\"2026-02-05 13:07:44\"},{\"document_type\":\"teaching_qualification\",\"file_path\":\"uploads\\/documents\\/teaching_qualification_153_1770296864_1983.jpg\",\"original_filename\":\"gomezgani diploma.jpg\",\"uploaded_at\":\"2026-02-05 13:07:44\"}]', '{\"days\":[\"Tuesday\",\"Wednesday\",\"Thursday\",\"Friday\",\"Saturday\",\"Sunday\"],\"times\":[\"Afternoon (12PM-5PM)\",\"Evening (5PM-9PM)\"]}', '{\"MANEB\":{\"levels\":{\"Secondary: Forms 1\\u20134\":[\"Mathematics\",\"Physics\"]}}}', NULL, NULL, NULL),
(154, 'Ellen2016', 'lindafrankbanda@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$cDVvWlRKdkl5dUZpdm9ZWg$Jvapu4AYDm/UPoPJeVuREgO7BKDmsn7H9Uzg09kq238', 'trainer', 'Lindah', 'Female', 'Banda', '+265882875967', '2026-02-05 13:07:49', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-02-05 13:06:03', '2026-03-14 12:07:25', '2026-03-14 12:07:25', 'Nkhotakota', 'Sani', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 1, 'Ministry of Education', 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `role`, `first_name`, `gender`, `last_name`, `phone`, `email_verified_at`, `otp_code`, `otp_expires_at`, `reset_token`, `reset_expires_at`, `is_active`, `profile_picture`, `cover_photo`, `approved_at`, `created_at`, `updated_at`, `deleted_at`, `district`, `location`, `experience_years`, `teaching_mode`, `bio`, `bio_video`, `whatsapp_number`, `phone_visible`, `email_visible`, `best_call_time`, `preferred_contact_method`, `is_verified`, `registration_completed`, `terms_accepted`, `is_employed`, `school_name`, `subscription_plan`, `subscription_expires_at`, `rating`, `review_count`, `search_count`, `featured`, `tutor_status`, `verification_documents`, `availability`, `structured_subjects`, `resubmission_token`, `resubmission_token_expires`, `resubmission_special_docs`) VALUES
(155, '_t_i_w_o_1', 'tiwongegumboakileni@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$OHAweFpQZ2VGUnBFbnhGQg$YUwMQuV+LrqO4uAfOpbo2vgB12cDarY5hRDtZYU88cc', 'trainer', 'Tiwonge', 'Female', 'Gumbo', '0986615893', '2026-02-05 14:09:38', NULL, NULL, NULL, NULL, 1, 'uploads/profile_photos/profile_155_1770301865.jpg', 'uploads/profile_photos/cover_155_1770301864.jpg', '2026-02-05 19:12:12', '2026-02-05 14:08:48', '2026-04-04 10:51:46', NULL, 'Lilongwe', 'Area 24', 9, 'both', 'Learning is always in steps', 'uploads/videos/intro_video_155_1770301865.mp4', '990058355', 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 1, 0, 1, NULL, 'Free Trial', '2026-03-07', 0.0, 0, 0, 0, 'approved', '[{\"document_type\":\"national_id\",\"file_path\":\"uploads\\/documents\\/national_id_155_1770301870_4813.jpg\",\"original_filename\":\"IMG_20260204_175715_118.jpg\",\"uploaded_at\":\"2026-02-05 14:31:10\"},{\"document_type\":\"academic_certificates\",\"file_path\":\"uploads\\/documents\\/academic_certificates_155_1770301870_2910.jpg\",\"original_filename\":\"17703017711661658166886491536092.jpg\",\"uploaded_at\":\"2026-02-05 14:31:11\"},{\"document_type\":\"teaching_qualification\",\"file_path\":\"uploads\\/documents\\/teaching_qualification_155_1770301871_3039.jpg\",\"original_filename\":\"17703017977733505144028838155556.jpg\",\"uploaded_at\":\"2026-02-05 14:31:12\"}]', '{\"days\":[\"Monday\",\"Wednesday\",\"Friday\",\"Saturday\"],\"times\":[\"Afternoon (12PM-5PM)\"]}', NULL, '960d17b15d433fc21e4f351a1b87b87c96769c4c387e3d46dd763e7e28e52c6f', '2026-02-06 00:09:41', '[\"intro_video\"]'),
(156, 'Standford', 'standfordngoma606@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$ZnZkYjFJQnkvMEhaUUJXYg$8GTG2SHJue1CTjn+xBdxN3u85qBgtfUrc7Tk+gAzpH0', 'trainer', 'Dr Standford Henderson', 'Male', 'Ngoma', '0991786946', '2026-02-05 15:55:51', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-02-05 15:54:52', '2026-03-14 12:07:42', '2026-03-14 12:07:42', 'Mzimba', 'Jenda', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 0, NULL, 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(157, 'Henrie', 'komwahenry@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$MFZDeUN6bExqRlJhNTdGbg$hb2I3mJXEGv/nmofoPsJN3iUszCYIM1fLGBD6LBHvXI', 'trainer', 'Henry', 'Male', 'Komwa', '0884671265', NULL, '368029', '2026-02-05 17:16:24', NULL, NULL, 0, NULL, NULL, NULL, '2026-02-05 16:59:44', '2026-03-14 12:07:58', '2026-03-14 12:07:58', 'Lilongwe', 'Area 10', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 0, 0, 0, 0, NULL, 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(158, 'Edusphere', 'samuelkandodobanda@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$ajhPUGs1L1VLNHE4Z2Ezag$0hx6P7mNq9QdcUnIURY4xeDIcYZgAkTHZLu6Q6fDbtY', 'trainer', 'Samuel Kandodo', 'Male', 'Banda', '0883076884', '2026-02-06 10:51:34', NULL, NULL, NULL, NULL, 1, 'uploads/profile_photos/profile_158_1770375639.jpeg', 'uploads/profile_covers/cover_158_1770402033.jpeg', '2026-02-06 14:52:28', '2026-02-06 10:50:43', '2026-03-12 05:33:51', NULL, 'Blantyre', 'Blantyre', 2, '', 'Results-driven Mathematics and Science tutor with strong experience teaching secondary/High school learners. Trained at the University of Malawi,  specialises in helping students improve performance through clear explanations, structured lessons, and continuous assessment. My teaching approach focuses on understanding, discipline, and measurable progress.', NULL, '997323746', 1, 1, 'Morning (8AM-12PM)', 'whatsapp', 1, 1, 1, 0, '', 'Premium', NULL, 5.0, 8, 0, 0, 'approved', '[{\"document_type\":\"national_id\",\"file_path\":\"uploads\\/documents\\/national_id_158_1770375639_1584.pdf\",\"original_filename\":\"SAMUEL BANDA PASSPORT.pdf\",\"uploaded_at\":\"2026-02-06 11:00:40\"},{\"document_type\":\"academic_certificates\",\"file_path\":\"uploads\\/documents\\/academic_certificates_158_1770375640_1061.jpg\",\"original_filename\":\"SAMUEL BANDA RECOMMENDATION LETTER.jpg\",\"uploaded_at\":\"2026-02-06 11:00:40\"},{\"document_type\":\"teaching_qualification\",\"file_path\":\"uploads\\/documents\\/teaching_qualification_158_1770375640_9059.jpg\",\"original_filename\":\"SAMUEL BANDA RECOMMENDATION LETTER.jpg\",\"uploaded_at\":\"2026-02-06 11:00:40\"}]', '{\"days\":[\"Monday\",\"Tuesday\",\"Wednesday\",\"Thursday\",\"Friday\",\"Saturday\",\"Sunday\"],\"times\":[\"Morning (8AM-12PM)\",\"Afternoon (12PM-5PM)\",\"Evening (5PM-9PM)\"]}', '{\"MANEB\":{\"levels\":{\"Primary: Standards 1\\u20138\":[\"Mathematics\"],\"Secondary: Forms 1\\u20134\":[\"Additional Mathematics\",\"Biology\",\"Chemistry\",\"Mathematics\",\"Physics\"]}},\"GCSE\":{\"levels\":{\"Key Stage 4 (Years 10\\u201311)\":[\"Chemistry\",\"Physics\"]}},\"Cambridge\":{\"levels\":{\"Cambridge AS\\/A Level (Grades 12\\u201313)\":[\"Biology\",\"Chemistry\",\"Further Mathematics\",\"Mathematics\",\"Physics\"],\"Cambridge IGCSE (Grades 10\\u201311)\":[\"Additional Mathematics\",\"Biology\",\"Chemistry\",\"Combined Science\",\"Mathematics\",\"Physics\"],\"Cambridge Lower Secondary (Grades 7\\u20139)\":[\"Mathematics\",\"Science\"],\"Cambridge Primary (Grades 1\\u20136)\":[\"Mathematics\",\"Science\"]}},\"ABEKA\":{\"levels\":{\"Primary (Grades 1\\u20136)\":[\"Handwriting\",\"Mathematics\",\"Number Skills \\/ Arithmetic\",\"Science\"],\"Secondary (Grades 7\\u201312)\":[\"Biology\",\"Chemistry\",\"Mathematics\",\"Physics\"]}}}', NULL, NULL, NULL),
(160, 'SHEZZIEH91', 'sharonmaziya7@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$ZlRtVjYyRENzajZuZUNMWA$sehcgbMYVYz1fUv+tHiUXG8gMBYPUnTIwWyrcDeO+6I', 'trainer', 'Sharon', 'Female', 'Mkumba', '0888049177', '2026-02-06 14:40:19', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-02-06 14:39:31', '2026-03-14 12:08:14', '2026-03-14 12:08:14', 'Machinga', 'Chabwera', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 1, 'St Theresa primary school', 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(161, 'Isaac55', 'isaacmwanza1299@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$Q3VmQzdUb2NucEJmVUkuLg$9TKCbCJ8I9ikzUIsqtUw11rknDQgpFJ1bSA+UcpO8JQ', 'trainer', 'Isaac', 'Male', 'Mwanza', '0880632105', '2026-02-08 05:05:01', NULL, NULL, NULL, NULL, 1, 'uploads/profile_photos/profile_161_1770528527.jpg', 'uploads/profile_photos/cover_161_1770528527.jpg', '2026-02-08 07:09:39', '2026-02-08 05:03:20', '2026-04-04 10:51:46', NULL, 'Lilongwe', 'Lilongwe', 2, '', 'Dedicated educator with 2 years of experience fostering a positive learning environment. Expert at developing engaging lessons that cater to diverse learning styles and abilities. ', NULL, '994576113', 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 1, 0, 1, '', 'Free Trial', '2026-03-10', 0.0, 0, 0, 0, 'approved', '[{\"document_type\":\"national_id\",\"file_path\":\"uploads\\/documents\\/national_id_161_1770528527_5713.jpg\",\"original_filename\":\"Passport.jpg\",\"uploaded_at\":\"2026-02-08 05:28:47\"},{\"document_type\":\"academic_certificates\",\"file_path\":\"uploads\\/documents\\/academic_certificates_161_1770528527_6058.png\",\"original_filename\":\"Screenshot_20260208-071610.png\",\"uploaded_at\":\"2026-02-08 05:28:47\"},{\"document_type\":\"teaching_qualification\",\"file_path\":\"uploads\\/documents\\/teaching_qualification_161_1770528527_3340.png\",\"original_filename\":\"Screenshot_20260208-071610.png\",\"uploaded_at\":\"2026-02-08 05:28:47\"}]', '{\"days\":[\"Monday\",\"Tuesday\",\"Wednesday\",\"Thursday\",\"Friday\",\"Saturday\"],\"times\":[\"Morning (8AM-12PM)\",\"Afternoon (12PM-5PM)\",\"Evening (5PM-9PM)\"]}', '{\"MANEB\":{\"levels\":{\"Secondary: Forms 1\\u20134\":[\"Chemistry\"]}},\"Languages\":{\"levels\":{\"All Levels\":[\"Chinese (Mandarin)\"]}}}', NULL, NULL, NULL),
(162, 'Miss_katimba', 'laureenprudencekatimba@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$Yk9reDUyWld1NFZ4R0lzcg$eTki9wu+s/AfAtWKIspcxeZ5vEcV/OEJX7LIevoM7IQ', 'trainer', 'Laureen Prudence', 'Female', 'Katimba', '0884534834', '2026-02-08 07:06:55', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-02-08 07:06:04', '2026-03-14 12:08:26', '2026-03-14 12:08:26', 'Blantyre', 'Machinjiri', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 1, 'Precious Private Academy', 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(163, 'MrsM_tutorconnect01', 'chawanangwa.mzumara@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$OERGdjl3TW95aXVRblVLMg$wNQowJHZtCJ6zddV0KCl5+QiaCDKf3R7gD2l5+VWy5E', 'trainer', 'Chawanangwa', 'Female', 'Mzumara', '0992439179', '2026-02-08 13:27:40', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-02-08 13:26:13', '2026-03-14 12:08:43', '2026-03-14 12:08:43', 'Blantyre', 'Soche/Naperi/Nancholi/Mpemba', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 1, 'Mustard Seed Homeschool', 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(164, 'MissTamara', 'tamarakazr@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$ZmN4MXRqdDNUd1NTWi5lMg$nWCXiFvhfhE9qsKQfqyh+NjOHL7NAhWlSqPti10c1Dg', 'trainer', 'Tamara', 'Female', 'Kamtema', '+265990617026', '2026-02-09 05:54:08', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-02-09 05:52:36', '2026-03-14 12:09:00', '2026-03-14 12:09:00', 'Lilongwe', 'Area 47', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 1, 'Acacia Academy', 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(165, 'Kaunda', 'kennyfotefolo@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$M1h6c1dFOHROWkp1YUJYRA$ut6ek0YuOwGao/sLEmNTHX0FRcIamnzQJHyvb7gQ+Wo', 'trainer', 'Kenneth', 'Male', 'Kaunda', '0885170725', '2026-02-10 07:34:35', NULL, NULL, NULL, NULL, 1, 'uploads/profile_photos/profile_165_1770709447.jpg', 'uploads/profile_photos/cover_165_1770709447.jpg', '2026-02-10 10:03:04', '2026-02-10 07:34:03', '2026-04-04 10:51:46', NULL, 'Blantyre', 'Mbayani', 2, 'both', 'A holder of Bachelor of Arts in Education (English) with a minor in Special Needs Education, I prefer to use both teacher centered and learner centered approaches when teaching ', NULL, '885170725', 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 1, 0, 0, NULL, 'Free Trial', '2026-03-12', 0.0, 0, 0, 0, 'approved', '[{\"document_type\":\"national_id\",\"file_path\":\"uploads\\/documents\\/national_id_165_1770709447_5307.jpg\",\"original_filename\":\"inbound6882397761283586634.jpg\",\"uploaded_at\":\"2026-02-10 07:44:07\"},{\"document_type\":\"academic_certificates\",\"file_path\":\"uploads\\/documents\\/academic_certificates_165_1770709447_3931.pdf\",\"original_filename\":\"inbound5366006923004114037.pdf\",\"uploaded_at\":\"2026-02-10 07:44:07\"},{\"document_type\":\"teaching_qualification\",\"file_path\":\"uploads\\/documents\\/teaching_qualification_165_1770709447_7568.pdf\",\"original_filename\":\"inbound3212425477191820685.pdf\",\"uploaded_at\":\"2026-02-10 07:44:07\"}]', '{\"days\":[\"Monday\",\"Tuesday\",\"Wednesday\",\"Thursday\",\"Friday\",\"Sunday\"],\"times\":[\"Afternoon (12PM-5PM)\",\"Evening (5PM-9PM)\"]}', '{\"MANEB\":{\"levels\":{\"Secondary: Forms 1\\u20134\":[\"English Language\"]}},\"GCSE\":{\"levels\":{\"Key Stage 4 (Years 10\\u201311)\":[\"English Literature\"]}}}', NULL, NULL, NULL),
(166, 'Daniel', 'danchisokola@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$eUN1MEN4ZUQxM0JaMktyUg$yMkYSp8ifNh9vmKiDYzVXcvMjAssohLeWehHGRIq19Y', 'trainer', 'Daniel', 'Male', 'Banda', '0993273738', NULL, '862218', '2026-02-11 09:36:29', NULL, NULL, 0, NULL, NULL, NULL, '2026-02-11 09:20:12', '2026-03-14 12:09:23', '2026-03-14 12:09:23', 'Lilongwe', 'Area 25', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 0, 0, 0, 0, NULL, 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(168, 'DavidNyirenda', 'sillznyirendah@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$NHVjU0d4UHBjVTlvUFVoZw$3yR2C1jaMMXPM1bMNLB1t++I56G9kCdhHCH1EN3+MPk', 'trainer', 'David', 'Male', 'Nyirenda', '+265992748785', '2026-02-11 17:04:52', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-02-11 17:03:44', '2026-03-14 12:09:51', '2026-03-14 12:09:51', 'Mzimba', 'Geisha Mzuzu', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 0, NULL, 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(171, 'Klement2026', 'clementj.chinguwo@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$ZXRDNVJ3bUdEZ3AzY2xScw$ciU2vZ/6MbxsaFmm9K5MLGJ5llxyHSXRRY4r9meWAWg', 'trainer', 'Clement Justin', 'Male', 'Chinguwo', '+265999284038', '2026-02-13 19:10:01', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-02-13 19:09:16', '2026-03-14 12:10:08', '2026-03-14 12:10:08', 'Lilongwe', 'Area 25', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 0, NULL, 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(172, 'Grace', 'novakuma96@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$dnhKdWw1YUlFZkdsdURnSg$+8mirq85EBMbM0lw7iRxUwDvjTnVNc2z69jAlU+Kzik', 'trainer', 'Grace', 'Female', 'Kumakanga', '0994898832', NULL, '462937', '2026-02-13 19:39:12', NULL, NULL, 0, NULL, NULL, NULL, '2026-02-13 19:24:12', '2026-03-14 12:10:21', '2026-03-14 12:10:21', 'Zomba', 'Malosa', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 0, 0, 0, 1, NULL, 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(173, 'davie921', 'lungudavid921@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$bmFNNjBaRGlhUDg0VmExSA$FscDkgtSyWp/HKRinQKYVsYv5Qsg0RCAEOb+MtXrdZY', 'trainer', 'David', 'Male', 'Lungu', '+265992897193', '2026-02-14 05:28:49', NULL, NULL, NULL, NULL, 1, 'uploads/profile_photos/profile_173_1771251583.jpg', 'uploads/profile_photos/cover_173_1771251582.png', '2026-03-14 12:11:17', '2026-02-14 05:26:08', '2026-03-25 12:34:49', NULL, 'Blantyre', 'Limbe', 2, '', 'I\'m a flexible tutor with bachelors of education (chemistry) from University of Malawi dedicated to helping students reach their full potential. My teaching style adapts to each student\'s unique learning style and pace. My goal is to build confidence and understanding, one step at a time. Passionate about empowering students to reach their full potential.', NULL, '992897193', 1, 1, 'Morning (8AM-12PM)', 'phone', 1, 1, 0, 1, 'Vineyard Academy', 'Free Trial', NULL, 0.0, 0, 0, 0, 'approved', '[{\"document_type\":\"national_id\",\"file_path\":\"uploads\\/documents\\/national_id_173_1771251583_1159.jpg\",\"original_filename\":\"IMG-20260214-WA0006.jpg\",\"uploaded_at\":\"2026-02-16 14:19:43\"},{\"document_type\":\"academic_certificates\",\"file_path\":\"uploads\\/documents\\/academic_certificates_173_1771251583_9271.pdf\",\"original_filename\":\"REF. LETTER CHEM.pdf\",\"uploaded_at\":\"2026-02-16 14:19:43\"},{\"document_type\":\"teaching_qualification\",\"file_path\":\"uploads\\/documents\\/teaching_qualification_173_1771251583_8748.pdf\",\"original_filename\":\"reference .pdf\",\"uploaded_at\":\"2026-02-16 14:19:43\"}]', '{\"days\":[\"Monday\",\"Tuesday\",\"Wednesday\",\"Thursday\",\"Friday\",\"Saturday\",\"Sunday\"],\"times\":[\"Morning (8AM-12PM)\",\"Afternoon (12PM-5PM)\"]}', '{\"MANEB\":{\"levels\":{\"Secondary: Forms 1\\u20134\":[\"Chemistry\",\"Physics\"]}}}', NULL, NULL, NULL),
(174, 'Yami_KT', 'yamiktore@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$RjdtQjRKdlBGLklWdXlGOQ$ZXc+/8g39TIdpxcdGvpk/ys6QTuBrxdM9/RARAqMpc8', 'trainer', 'Yamikani', 'Male', 'Kambalametore', '991807848', '2026-02-14 07:04:50', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-02-14 07:04:01', '2026-03-15 05:35:00', '2026-03-15 05:35:00', 'Lilongwe', 'New Gulliver', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 1, 'Dzoole secondary school', 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(175, 'Madamkute', 'madalitsomkute797@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$VHZ1S1dHZDRtdlg0VVdaNg$NRvT5vP8nFWujN14rqGwkcDfC0Kc7jqTh1cj73HlaUI', 'trainer', 'Madalitso', 'Female', 'Mkute', '0997395038', '2026-02-14 07:21:52', NULL, NULL, NULL, NULL, 1, 'uploads/profile_photos/profile_175_1771055411.jpg', 'uploads/profile_photos/cover_175_1771055411.jpg', '2026-02-14 10:13:19', '2026-02-14 07:20:52', '2026-04-04 10:51:46', NULL, 'Blantyre', 'Manase', 1, 'in-person', 'Am a passionate qualified professional teacher with a Bachelor of Education in Chemistry (with Mathematics as a minor) from the University of Malawi. I have been teaching Chemistry and Mathematics for close to a year.', NULL, '997395038', 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 1, 0, 0, NULL, 'Free Trial', '2026-03-16', 0.0, 0, 0, 0, 'approved', '[{\"document_type\":\"national_id\",\"file_path\":\"uploads\\/documents\\/national_id_175_1771055411_9581.jpg\",\"original_filename\":\"1771054412914416287854928290089.jpg\",\"uploaded_at\":\"2026-02-14 07:50:11\"},{\"document_type\":\"academic_certificates\",\"file_path\":\"uploads\\/documents\\/academic_certificates_175_1771055411_5332.jpg\",\"original_filename\":\"IMG-20260214-WA0085.jpg\",\"uploaded_at\":\"2026-02-14 07:50:11\"},{\"document_type\":\"teaching_qualification\",\"file_path\":\"uploads\\/documents\\/teaching_qualification_175_1771055411_7230.pdf\",\"original_filename\":\"025TCM30330259_Indexing_Certificate (1) (1).pdf\",\"uploaded_at\":\"2026-02-14 07:50:11\"}]', '{\"days\":[\"Monday\",\"Tuesday\",\"Wednesday\",\"Thursday\",\"Friday\"],\"times\":[\"Morning (8AM-12PM)\",\"Afternoon (12PM-5PM)\"]}', '{\"MANEB\":{\"levels\":{\"Secondary: Forms 1\\u20134\":[\"Chemistry\",\"Mathematics\"]}}}', NULL, NULL, NULL),
(176, 'Munga', 'mungagrace396@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$MjNkcmlJSmFsYUhCeC8xNQ$KPr+iUW7A0eNhCUbFUcFmAYxQS4Wd1q4gebVyJOtg/g', 'trainer', 'Grace', 'Female', 'Munga', '0990010730', '2026-02-15 08:22:59', NULL, NULL, NULL, NULL, 1, 'uploads/profile_photos/profile_176_1771150186.jpeg', 'uploads/profile_photos/cover_176_1771150186.jpeg', '2026-02-15 10:40:36', '2026-02-15 08:22:02', '2026-04-04 10:51:46', NULL, 'Lilongwe', 'Area 49 Dubai', 4, 'both', 'I am an experienced educator specializing in French language instruction and the Cambridge Primary curriculum. With over 4 years of teaching experience, I have successfully guided learners both online and in-person, helping them build strong foundations in language, humanities, and academic excellence.\r\n\r\nMy teaching style is student-centered, blending clarity, empathy, and practical application. In French, I focus on communication, grammar, and cultural appreciation, while in Cambridge subjects I emphasize inquiry-based learning and critical thinking. I design lessons that are engaging, structured, and adapted to each learner’s needs, ensuring confidence and curiosity alongside academic success.\r\n\r\nBeyond the classroom, I have worked as a liaison officer with the Football Association of Malawi for four years, managing and translating for national teams and clubs across Africa in French, Swahili, and English. This experience has strengthened my ability to connect across cultures and languages, a skill I bring into my teaching to enrich students’ learning journeys.\r\n\r\nI am passionate about curriculum design and holistic education, and I believe in nurturing learners to become resilient, thoughtful individuals. My goal is to inspire students to excel academically while equipping them with skills that extend beyond the classroom.\r\n', 'uploads/videos/intro_video_176_1771150186.mov', '990010730', 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 1, 1, 1, NULL, 'Free Trial', '2026-03-17', 5.0, 1, 0, 0, 'approved', '[{\"document_type\":\"national_id\",\"file_path\":\"uploads\\/documents\\/national_id_176_1771150191_7600.jpeg\",\"original_filename\":\"IMG_5976.jpeg\",\"uploaded_at\":\"2026-02-15 10:09:51\"},{\"document_type\":\"academic_certificates\",\"file_path\":\"uploads\\/documents\\/academic_certificates_176_1771150191_5671.jpeg\",\"original_filename\":\"IMG_5968.jpeg\",\"uploaded_at\":\"2026-02-15 10:09:51\"},{\"document_type\":\"teaching_qualification\",\"file_path\":\"uploads\\/documents\\/teaching_qualification_176_1771150191_9639.jpg\",\"original_filename\":\"image.jpg\",\"uploaded_at\":\"2026-02-15 10:09:51\"}]', '{\"days\":[\"Monday\",\"Tuesday\",\"Wednesday\",\"Thursday\",\"Friday\",\"Saturday\",\"Sunday\"],\"times\":[\"Morning (8AM-12PM)\",\"Afternoon (12PM-5PM)\",\"Evening (5PM-9PM)\"]}', '{\"MANEB\":{\"levels\":{\"Primary: Standards 1\\u20138\":[\"Agriculture\",\"English\",\"Mathematics\"],\"Secondary: Forms 1\\u20134\":[\"Business Studies\",\"Computer Studies\",\"French\",\"Mathematics\"]}},\"GCSE\":{\"levels\":{\"Key Stage 4 (Years 10\\u201311)\":[\"Business\",\"Computer Science\",\"Economics\",\"Statistics\"]}},\"Cambridge\":{\"levels\":{\"Cambridge AS\\/A Level (Grades 12\\u201313)\":[\"Accounting\",\"Business\",\"Computer Science\",\"Economics\",\"French\"],\"Cambridge IGCSE (Grades 10\\u201311)\":[\"Accounting\",\"Business Studies\",\"Economics\",\"French\"],\"Cambridge Primary (Grades 1\\u20136)\":[\"Computing\",\"English\",\"Humanities\",\"Mathematics\",\"Modern Foreign Language\",\"Science\"]}},\"ABEKA\":{\"levels\":{\"Kindergarten\":[\"Bible\",\"Mathematics\",\"Phonics & Reading\"],\"Primary (Grades 1\\u20136)\":[\"Bible\",\"English\",\"Handwriting\",\"Letters and Sounds\",\"Mathematics\",\"Number Skills \\/ Arithmetic\",\"Reading and Comprehension\",\"Science\",\"Spellings\"]}},\"Languages\":{\"levels\":{\"All Levels\":[\"French\",\"Swahili\"]}}}', NULL, NULL, NULL),
(177, 'rafqanta', 'rkantande96@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$eVNSQjJrd3Y5RHd3NldCbw$TkOwJ7ZODku7/z+uNKYOSEU9XM//sV3OQEWKKV/Bip8', 'trainer', 'Ralph', 'Male', 'Kantande', '+265986584859', '2026-02-15 08:47:35', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-02-15 08:46:56', '2026-03-15 05:35:17', '2026-03-15 05:35:17', 'Lilongwe', 'Area 25', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 0, NULL, 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(178, 'kululangaeunice', 'kululangaeunice1@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$WHF1dEhlWkF3aWRRQy4vag$MFwkgLzpxU5JcVix3+l71JMO1cg7vHqzGsDU56+AqU0', 'trainer', 'Eunice', 'Female', 'Kululanga', '0999168680', '2026-02-15 09:26:56', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-02-15 09:25:06', '2026-03-15 05:35:52', '2026-03-15 05:35:52', 'Lilongwe', 'Area25', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 0, NULL, 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(180, 'CBGT', 'mackbandar@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$WGpnaTNpL0UweGVOVnVObQ$6a0kqnKG992XA3FN2iF6hBfKBPdVjk0nRkMkN+CwAsY', 'trainer', 'Chifuniro', 'Male', 'Bandar', '993078411', '2026-02-15 18:46:25', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-02-15 18:42:56', '2026-03-15 08:09:44', '2026-03-15 08:09:44', 'Lilongwe', 'Area50', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 0, NULL, 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(181, 'Samson_2026', 'kaisisamson35@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$Zms5RWtlVUs2Uy5ZbEtXYg$mVk2HKQtWR8LObXKVJckSSHt6ZrjletCHN4gujYb4/Q', 'trainer', 'SAMSON', 'Male', 'KAISI', '+265997155648', '2026-02-16 06:18:33', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-02-16 06:17:43', '2026-03-15 08:10:02', '2026-03-15 08:10:02', 'Chiradzulu', 'Nguludi Turnoff', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 0, NULL, 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(182, 'Miss_Meek01', 'meeknessmatumbo@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$LzNiWk1rTGhPOHVjcGNUVA$mE42pFzbl2DBKdREXwyYkK4RCEf5F7PsXpzleaF408I', 'trainer', 'Meekness', 'Female', 'Matumbo', '0996195780', '2026-02-16 07:02:16', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-02-16 06:33:58', '2026-03-15 08:10:19', '2026-03-15 08:10:19', 'Blantyre', 'Chileka', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 1, 'Kalibu Academy', 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(183, 'Jackie', 'mwayikamperewera@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$bGN0aXBTN09qczh5cDUvWA$4T6tsueVzku/EIXp0loRadK2w6QGPUo7U+29dnoiO2w', 'trainer', 'Mwayi', 'Female', 'Kamperewera', '0991326566', '2026-02-16 06:39:57', NULL, NULL, NULL, NULL, 1, 'uploads/profile_photos/profile_183_1772858854.jpg', 'uploads/profile_photos/cover_183_1772858854.jpg', '2026-03-15 08:11:26', '2026-02-16 06:39:09', '2026-03-15 08:14:38', NULL, 'Zomba', 'Matawale', 3, 'both', 'I am a language teacher. I teach Chichewa and English. I hold a Bachelor\'s degree.', NULL, '991326566', 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 1, 0, 0, NULL, 'Free Trial', NULL, 0.0, 0, 0, 0, 'approved', '[{\"document_type\":\"national_id\",\"file_path\":\"uploads\\/documents\\/national_id_183_1772858854_2804.jpg\",\"original_filename\":\"IMG_20260217_164520.jpg\",\"uploaded_at\":\"2026-03-07 04:47:34\"},{\"document_type\":\"academic_certificates\",\"file_path\":\"uploads\\/documents\\/academic_certificates_183_1772858854_7629.jpg\",\"original_filename\":\"IMG_20260216_145214.jpg\",\"uploaded_at\":\"2026-03-07 04:47:35\"},{\"document_type\":\"teaching_qualification\",\"file_path\":\"uploads\\/documents\\/teaching_qualification_183_1772858855_1322.jpg\",\"original_filename\":\"1772858779832863521509145763740.jpg\",\"uploaded_at\":\"2026-03-07 04:47:36\"}]', '{\"days\":[\"Monday\",\"Tuesday\",\"Wednesday\",\"Thursday\",\"Friday\"],\"times\":[\"Afternoon (12PM-5PM)\"]}', '{\"MANEB\":{\"levels\":{\"Secondary: Forms 1\\u20134\":[\"Chichewa\",\"English Language\"]}}}', NULL, NULL, NULL),
(184, 'VincentLikongwe', 'vincentlikongwe@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$Y0oySXd2RDVpMEt5UDZNUg$xSveeq0tuNPbtdIoajtd/F1c3jRtgSfYCmqDt69OMgw', 'trainer', 'Vincent', 'Male', 'Likongwe', '+265886861687', '2026-02-16 11:41:26', NULL, NULL, NULL, NULL, 1, 'uploads/profile_photos/profile_184_1771243537.png', 'uploads/profile_photos/cover_184_1771243537.png', '2026-02-16 16:17:40', '2026-02-16 11:40:28', '2026-04-04 10:51:46', NULL, 'Blantyre', 'Nancholi', 5, 'Both Online & Physical', 'I am an Economics graduate with a strong academic background and a passion for teaching.\r\n\r\nI tutor Mathematics, Statistics Businesses, Accounting, Physics,Chemistry and Biology. I explain concepts in a simple and clear way to help students understand better and improve their performance.\r\n\r\nI am patient, supportive, and committed to helping learners build confidence and succeed in their studies.', NULL, '+265886861687', 1, 1, 'Morning (8AM-12PM)', 'whatsapp', 1, 1, 0, 0, '', 'Free Trial', '2026-03-18', 0.0, 0, 0, 0, 'approved', '[{\"document_type\":\"national_id\",\"file_path\":\"uploads\\/documents\\/national_id_184_1771243537_3964.pdf\",\"original_filename\":\"Vincent Likongwe ID.pdf\",\"uploaded_at\":\"2026-02-16 12:05:37\"},{\"document_type\":\"academic_certificates\",\"file_path\":\"uploads\\/documents\\/academic_certificates_184_1771243537_4854.pdf\",\"original_filename\":\"Degree_.pdf\",\"uploaded_at\":\"2026-02-16 12:05:37\"},{\"document_type\":\"teaching_qualification\",\"file_path\":\"uploads\\/documents\\/teaching_qualification_184_1771243537_2552.pdf\",\"original_filename\":\"Academic_Transcript_.pdf\",\"uploaded_at\":\"2026-02-16 12:05:38\"}]', '{\"days\":[\"Monday\",\"Tuesday\",\"Wednesday\",\"Thursday\",\"Friday\",\"Sunday\"],\"times\":[\"Morning (8AM-12PM)\",\"Afternoon (12PM-5PM)\",\"Evening (5PM-9PM)\"]}', '{\"MANEB\":{\"levels\":{\"Secondary: Forms 1\\u20134\":[\"Mathematics\"]}},\"GCSE\":{\"levels\":{\"Key Stage 4 (Years 10\\u201311)\":[\"Business\"]}}}', NULL, NULL, NULL),
(186, 'Pemba', 'pembamoses@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$MnlzUGo4RE5lMk9HMEpNbw$0CGEEoZVk7X+fblWK/x/R1Kaw98HIrtFP6sbdzRyBko', 'trainer', 'Moses', 'Male', 'Pemba', '0994221166', '2026-02-19 09:39:05', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-02-19 09:38:14', '2026-03-15 08:13:15', '2026-03-15 08:13:15', 'Lilongwe', 'Nambuma', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 0, NULL, 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(187, 'Marsart', 'tmagret54@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$Ty83Wng1SHhHU28uRDB3dQ$rFTu0ZKRkKAy6Je72minrngvMZrznCqfwTEhsOFvOs0', 'trainer', 'Margret', 'Female', 'Tembo', '+265882426150', '2026-02-19 12:41:03', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-02-19 12:39:50', '2026-03-15 08:13:29', '2026-03-15 08:13:29', 'Lilongwe', 'Area 36', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 1, 'Oakland school', 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(188, 'Peace18', 'peacemtonga1@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$NWcySGpBVWEzNmVFaTR2cQ$VwaOubn1RE6tEX/vcKwDdL1GlJD3b7Xek5ypoiqDgA4', 'trainer', 'Peace', 'Male', 'Mtonga', '0890245955', '2026-02-21 09:40:59', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-02-21 09:39:46', '2026-03-15 08:13:44', '2026-03-15 08:13:44', 'Rumphi', 'Staff quarters', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 0, NULL, 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(189, 'Blessings', 'btech21-bseba@mubas.ac.mw', '$argon2id$v=19$m=65536,t=4,p=1$WVNiSzZYdmhINkQ3b1RHRQ$L288jt7exIy/4qU5mRJCzv/vFLvYl9UZ/toH4cVBj14', 'trainer', 'Blessings', 'Male', 'Seba', '+265993867686', '2026-02-22 02:08:18', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-02-22 02:05:32', '2026-03-15 08:13:57', '2026-03-15 08:13:57', 'Lilongwe', 'Mpenu', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 1, 'Machine LEA primary school', 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(190, 'MbeweAlinafe', 'alinafegmbewe@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$Y3UuS2NKc1lOMTVYTWxPZg$n6pymjNMeEgvMJ7Plr6AZKZOSl1UDKSC+hSJU10XYaE', 'trainer', 'Alinafe', 'Female', 'Mbewe', '0993878460', '2026-02-22 05:47:52', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-02-22 05:46:36', '2026-03-15 08:14:10', '2026-03-15 08:14:10', 'Rumphi', 'Rumphi/Mzuzu', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 0, NULL, 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(192, 'Chris95', 'chrispinrichardbanda@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$ZWpDVkRrZnhldDhva1dSZQ$zjWsFzFOQZ6IJ5KLVUj0iQxC2EnEFKWZaAZTLiUU7p0', 'trainer', 'Chrispin', 'Male', 'Banda', '0994159975', '2026-02-22 18:18:50', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-02-22 18:16:59', '2026-03-14 11:42:18', '2026-03-14 11:42:18', 'Blantyre', 'Mbayani', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 1, 'Namiwawa CDSS', 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(193, '123joana', 'joanamalinga@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$eHJXTkg4UEljZTNZWkZSbA$7ZcKVHbl+c8jP+fQKdAWB/lzKtMpzwu/gY1jzGNsbrA', 'trainer', 'joana', 'Female', 'malinga', '0889918746', NULL, '217606', '2026-02-23 19:45:39', NULL, NULL, 0, NULL, NULL, NULL, '2026-02-23 19:26:05', '2026-03-14 11:41:40', '2026-03-14 11:41:40', 'Machinga', 'liwonde', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 0, 0, 0, 0, NULL, 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(194, 'Praise_Ziba', 'zibapraise99@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$Z0ZZU0lSWmV1cnRIdDd3SA$UdQmFgK4kgknnri9MPAe/ikLFUH38ZVt1oRrdNmjqQY', 'trainer', 'Praise', 'Female', 'Ziba', '+265994404888', '2026-02-24 06:24:38', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-02-24 06:21:35', '2026-03-14 11:41:12', '2026-03-14 11:41:12', 'Mzimba', 'Mzuzu', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 0, NULL, 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(196, 'HanifaFuleya26', 'hanifahfuleya@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$N2ZZRWV6dUlGT1pyb3cwaQ$fff5p8kq462JEInJe6bJpAWiPWUoGfJ9nBfNYUDe2kA', 'trainer', 'HANIFA IREEN', 'Female', 'FULEYA', '0981931929', '2026-03-01 14:49:41', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-03-01 14:48:59', '2026-03-14 11:40:41', '2026-03-14 11:40:41', 'Mzimba', 'Mzuzu', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 0, NULL, 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(197, 'MScott', 'michelleascott04@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$RnN4NFVJdFNGWnVUQXB2cw$qcLrnjS4C2UDI6X36wFG/EHu/cFnqnkkSsDH2h36vCk', 'trainer', 'Michelle', 'Female', 'Scott', '0981208805', '2026-03-02 06:44:26', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-03-02 06:43:27', '2026-03-14 11:40:18', '2026-03-14 11:40:18', 'Lilongwe', 'Area 25', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 0, NULL, 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(198, 'Aaron162', 'aaronchipiko@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$L2FaWm5NNXNudDJoai9rRw$+Z4teBUuTcg0d2WOTEsnZpuMjX2bebZQtgmc0oR8r8o', 'trainer', 'Aaron', 'Male', 'Chipiko', '0999917695', '2026-03-03 09:07:09', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-03-03 09:03:21', '2026-03-14 11:40:02', '2026-03-14 11:40:02', 'Lilongwe', 'Area23', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 0, NULL, 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(199, 'Philadelphia', 'chikondi.pamdera@cunima.ac.mw', '$argon2id$v=19$m=65536,t=4,p=1$MmRRWjVCM0htdkVWM0ZKQg$H+yaQ0Qk+Q1toTShb4BXqIcRLdrgw9kp3jO5W3wogCM', 'trainer', 'Chikondi', 'Female', 'Pamdera', '0883266691', '2026-03-03 09:41:05', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-03-03 09:39:39', '2026-03-14 11:39:30', '2026-03-14 11:39:30', 'Chikwawa', 'Dyeratu', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 0, NULL, 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(200, 'Emmanuelchapita', 'emmanuelchapita@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$cjR3ZXVKL1lmakZ5RmlXSA$LwvRGm9hH57n+L0j6wcn3WoTiAMTK1NM4Z+cUuniAFI', 'trainer', 'Emmanuel', 'Male', 'Chapita', '0881650439', '2026-03-03 09:58:50', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-03-03 09:57:26', '2026-03-14 11:39:16', '2026-03-14 11:39:16', 'Blantyre', 'Bangwe', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 0, NULL, 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(201, 'Cynthia_C26', 'cyndiechilima@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$SlI5ZlZOWFo0QW54UHFCZw$MtWefhhDthzkImFlfR/nWHIbhjUinFxICnn4PnAVmGE', 'trainer', 'Cynthia', 'Female', 'Chilimampunga', '+265998895091', '2026-03-03 17:21:35', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-03-03 17:20:21', '2026-03-14 11:39:00', '2026-03-14 11:39:00', 'Lilongwe', 'Area 49', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 1, 'Good Shepherd International School', 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(202, 'amussah', 'allexmussah@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$ZFdPdEFFMjM5dlhISjZETA$myrAhBM1NTWLEP/utuGgbSfulocS1UgUF4usMYLZ7FE', 'trainer', 'Alexander', 'Male', 'Mussah', '0884463625', '2026-03-03 22:25:03', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-03-03 22:24:27', '2026-03-14 11:38:35', '2026-03-14 11:38:35', 'Karonga', 'Karonga Town', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 0, 'Worl', 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(203, 'robertsulumba', 'robertsulumbah@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$VERxcDhuR3ZjVnZJYVRsbA$mg+7f3wfweoL7oBZ2+O2Xgk/PSvaPJbL1TY4zZJxtM0', 'trainer', 'Robert', 'Male', 'Sulumba', '0888149145', '2026-03-05 12:38:53', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-03-05 12:36:58', '2026-03-14 11:38:13', '2026-03-14 11:38:13', 'Chikwawa', 'Kanyongolo Village', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 1, 'Kanyongolo Catholic Primary School', 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(204, 'kazembekaombe', 'kazembekaombe@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$VGd1WFdTeG1ER2FwM1Frcw$Yt4kOtkmmaeue039z/HderDxH0LxJPV2NNQTNREEvv8', 'trainer', 'Kazembe', 'Male', 'Kaombe', '0888103794', '2026-03-05 17:35:25', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-03-05 17:34:07', '2026-03-14 11:37:57', '2026-03-14 11:37:57', 'Blantyre', 'Blantyre', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 1, NULL, 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(205, 'Ms_Ella265', 'tayamikaellachibwana@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$VmpWU1pqbUFOZ3hlN0I2Vw$RwlwzzphCT4Y+FZTekafJIv/62nZp5LI9iTGfvG4tSw', 'trainer', 'Tayamika Ellah', 'Female', 'Chibwana', '0981810083', '2026-03-05 19:15:35', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-03-05 19:14:53', '2026-03-14 11:37:24', '2026-03-14 11:37:24', 'Zomba', 'Mulunguzi', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 1, 'Mat Academy', 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(206, 'RichardGawani', 'chikugawani@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$ODZLdEIySXdJZS9HcU9Qcg$wyrdlLirC/bhGJ0TiFWLQ72kIhTUvf/og4bWqVCJjj4', 'trainer', 'Richard', 'Male', 'Gawani', '0986274383', '2026-03-06 03:43:06', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-03-06 03:41:04', '2026-03-14 11:36:47', '2026-03-14 11:36:47', 'Lilongwe', 'Area 46', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 0, NULL, 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(207, 'FelixPhuka95', 'felixphukaphuka0@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$bHA0MGx1YlBndkpOaDZyQQ$u4KOf7OrrQB6jQ6oQiq6k2x6DMjDHUVXxhVcy7guwUQ', 'trainer', 'Felix', 'Male', 'Phukaphuka', '0882441324', '2026-03-06 05:02:08', NULL, NULL, NULL, NULL, 1, 'uploads/profile_photos/profile_207_1772774298.jpeg', 'uploads/profile_photos/cover_207_1772774297.jpeg', '2026-03-14 11:34:38', '2026-03-06 05:00:40', '2026-03-14 11:34:38', NULL, 'Lilongwe', 'Area 25', 2, 'both', 'I am a tutor with bachelors degree of Education, English language and literature. ', NULL, '882441324', 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 1, 0, 0, NULL, 'Free Trial', NULL, 0.0, 0, 0, 0, 'approved', '[{\"document_type\":\"national_id\",\"file_path\":\"uploads\\/documents\\/national_id_207_1772774298_6036.jpg\",\"original_filename\":\"image.jpg\",\"uploaded_at\":\"2026-03-06 05:18:18\"},{\"document_type\":\"academic_certificates\",\"file_path\":\"uploads\\/documents\\/academic_certificates_207_1772774298_2426.pdf\",\"original_filename\":\"Felix Phukaphuka Degree.pdf\",\"uploaded_at\":\"2026-03-06 05:18:18\"},{\"document_type\":\"teaching_qualification\",\"file_path\":\"uploads\\/documents\\/teaching_qualification_207_1772774298_9333.pdf\",\"original_filename\":\"TCM REGISTRATION (FELIX PHUKAPHUKA).pdf\",\"uploaded_at\":\"2026-03-06 05:18:19\"}]', '{\"days\":[\"Monday\",\"Tuesday\",\"Thursday\",\"Friday\",\"Saturday\"],\"times\":[\"Afternoon (12PM-5PM)\",\"Evening (5PM-9PM)\"]}', NULL, NULL, NULL, NULL),
(209, 'TeacherBritt', 'nyabawab@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$OWpkQkJJeE5FaFBUVFRkRg$GTmyHBA89GNtEI/Mxm3etlU34bnsiwakLdDh+Y3uAn8', 'trainer', 'BRIDGET', 'Female', 'NYABAWA', '0999337679', '2026-03-06 19:50:13', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-03-06 19:49:22', '2026-03-14 11:33:49', '2026-03-14 11:33:49', 'Lilongwe', '51', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 1, NULL, 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(210, 'Joshua', 'joshuaguta1@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$eHNacjV4QmlQMy41RjdtQQ$poz6hUJ/ucWS1OUL9xx4Tc9M0i8kV/Mw03nS4SBKMt8', 'trainer', 'Joshua', 'Male', 'Guta', '0993313063', '2026-03-08 06:58:19', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-03-08 06:56:32', '2026-03-14 11:33:34', '2026-03-14 11:33:34', 'Lilongwe', '49', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 0, NULL, 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(212, 'Fletcher', 'bandafletcher0@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$amEyYXVEOC95VXRkTkxZUA$hFgjxRQqio5inOHdB+OOoBcZ5pAQOs0gUF88uGhGBlo', 'trainer', 'Fletcher', 'Male', 'Banda', '0994377770', '2026-03-09 14:21:59', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-03-09 14:20:54', '2026-03-14 11:33:08', '2026-03-14 11:33:08', 'Lilongwe', 'Area 25', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 0, NULL, 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(213, 'Anderson', 'kafatsa.anderson@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$ajFFTUlvTDhFLkhQbkVSMw$EC1kBPiHnoiOcOIYuqQ+KV4SFo1VvJR+yUTvtjsR/yg', 'trainer', 'ANDERSON', 'Male', 'KAFATSA', '0885280484', '2026-03-09 21:47:09', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-03-09 21:45:38', '2026-03-14 11:32:54', '2026-03-14 11:32:54', 'Lilongwe', 'Mitundu', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 1, 'Malawi Government', 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(214, 'nota', 'ruthscotch@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$bG1ReFNFUHhNS1BMQkFzZQ$3ouhCRP3l90A0DqZyCf6V0b55M2YoRCLZnABKf35CJ0', 'trainer', 'George', 'Male', 'Nota', '0880595547', NULL, '685527', '2026-03-10 06:59:02', NULL, NULL, 0, NULL, NULL, NULL, '2026-03-10 06:43:46', '2026-03-14 11:32:34', '2026-03-14 11:32:34', 'Mulanje', 'Mulanje', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 0, 0, 0, 1, 'Kabichi cdss', 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(215, 'HMwakyelu2026', 'hmwakyelu@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$aVUwblBWcXREcnJmb1luNw$prm1ke6TX12wNvGjXJbAoyHeLI90PlXe5e4VV+cdH9g', 'trainer', 'Harnet', 'Male', 'Mwakyelu', '0992919495', '2026-03-10 11:45:02', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-03-10 11:44:29', '2026-03-14 11:31:24', '2026-03-14 11:31:24', 'Lilongwe', 'Area 23', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 0, NULL, 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(216, 'ALAB', 'alabmasanje@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$ckJFV1dNdDhkajFTRzdPaA$PuKC0kimPXddZThbvyfVxPNLXPlUSyhW2DTw5Tx+d+8', 'trainer', 'ALAB', 'Male', 'MASANJE', '0999071566', '2026-03-11 14:42:35', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-03-11 14:41:43', '2026-03-14 11:32:19', '2026-03-14 11:32:19', 'Blantyre', 'MACHINJIRI', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 1, NULL, 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL),
(217, 'SiSha', 'shambalopasiphey@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$NHlGYWFVNElGOVpoUVZSZw$046qw6+wxyffvwbXvY7tbA/+zhg9mpAvXLqak5y8nuE', 'trainer', 'Siphey', 'Female', 'Shambalopa', '0999000327', '2026-03-12 19:11:53', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2026-03-12 19:11:11', '2026-03-14 11:32:05', '2026-03-14 11:32:05', 'Lilongwe', '6 miles', 1, 'Both Online & Physical', NULL, NULL, NULL, 1, 0, 'Morning (8AM-12PM)', 'phone', 1, 0, 0, 0, NULL, 'Free Trial', NULL, 0.0, 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_ibfk_1` FOREIGN KEY (`posted_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `announcements_ibfk_2` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`tutor_id`) REFERENCES `tutors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`tutor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `enrollments_ibfk_3` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `messages_ibfk_3` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `messages_ibfk_4` FOREIGN KEY (`parent_message_id`) REFERENCES `messages` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `notices`
--
ALTER TABLE `notices`
  ADD CONSTRAINT `fk_notices_created_by_user` FOREIGN KEY (`created_by_user`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `resource_leads`
--
ALTER TABLE `resource_leads`
  ADD CONSTRAINT `resource_leads_ibfk_1` FOREIGN KEY (`past_paper_id`) REFERENCES `past_papers` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `service_categories`
--
ALTER TABLE `service_categories`
  ADD CONSTRAINT `service_categories_ibfk_1` FOREIGN KEY (`parent_category`) REFERENCES `service_categories` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `sessions_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sessions_ibfk_3` FOREIGN KEY (`tutor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sessions_ibfk_4` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `subscription_renewal_reminders`
--
ALTER TABLE `subscription_renewal_reminders`
  ADD CONSTRAINT `subscription_renewal_reminders_subscription_fk` FOREIGN KEY (`subscription_id`) REFERENCES `tutor_subscriptions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `subscription_renewal_reminders_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tutors`
--
ALTER TABLE `tutors`
  ADD CONSTRAINT `tutors_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tutor_availability`
--
ALTER TABLE `tutor_availability`
  ADD CONSTRAINT `tutor_availability_ibfk_1` FOREIGN KEY (`tutor_id`) REFERENCES `tutors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tutor_certificates`
--
ALTER TABLE `tutor_certificates`
  ADD CONSTRAINT `tutor_certificates_ibfk_1` FOREIGN KEY (`tutor_id`) REFERENCES `tutors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tutor_curricula`
--
ALTER TABLE `tutor_curricula`
  ADD CONSTRAINT `tutor_curricula_ibfk_1` FOREIGN KEY (`tutor_id`) REFERENCES `tutors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tutor_levels`
--
ALTER TABLE `tutor_levels`
  ADD CONSTRAINT `tutor_levels_ibfk_1` FOREIGN KEY (`tutor_id`) REFERENCES `tutors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tutor_references`
--
ALTER TABLE `tutor_references`
  ADD CONSTRAINT `tutor_references_ibfk_1` FOREIGN KEY (`tutor_id`) REFERENCES `tutors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tutor_sessions`
--
ALTER TABLE `tutor_sessions`
  ADD CONSTRAINT `tutor_sessions_ibfk_1` FOREIGN KEY (`tutor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tutor_subjects`
--
ALTER TABLE `tutor_subjects`
  ADD CONSTRAINT `tutor_subjects_ibfk_1` FOREIGN KEY (`tutor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tutor_subscriptions`
--
ALTER TABLE `tutor_subscriptions`
  ADD CONSTRAINT `tutor_subscriptions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tutor_subscriptions_ibfk_2` FOREIGN KEY (`plan_id`) REFERENCES `subscription_plans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tutor_videos`
--
ALTER TABLE `tutor_videos`
  ADD CONSTRAINT `tutor_videos_ibfk_1` FOREIGN KEY (`tutor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `usage_tracking`
--
ALTER TABLE `usage_tracking`
  ADD CONSTRAINT `usage_tracking_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
