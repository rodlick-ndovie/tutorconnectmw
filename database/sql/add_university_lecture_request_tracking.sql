-- Equivalent SQL for:
-- app/Database/Migrations/2026-05-19-000003_AddUniversityLectureRequestTracking.php

-- Add matched_tutor_count if it does not already exist.
SET @sql = (
    SELECT IF(
        EXISTS(
            SELECT 1
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'university_lecture_requests'
              AND COLUMN_NAME = 'matched_tutor_count'
        ),
        'SELECT ''matched_tutor_count already exists''',
        'ALTER TABLE `university_lecture_requests` ADD COLUMN `matched_tutor_count` INT UNSIGNED NOT NULL DEFAULT 0 AFTER `status`'
    )
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add emailed_tutor_count if it does not already exist.
SET @sql = (
    SELECT IF(
        EXISTS(
            SELECT 1
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'university_lecture_requests'
              AND COLUMN_NAME = 'emailed_tutor_count'
        ),
        'SELECT ''emailed_tutor_count already exists''',
        'ALTER TABLE `university_lecture_requests` ADD COLUMN `emailed_tutor_count` INT UNSIGNED NOT NULL DEFAULT 0 AFTER `matched_tutor_count`'
    )
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

CREATE TABLE IF NOT EXISTS `university_lecture_request_applications` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `university_lecture_request_id` int unsigned NOT NULL,
  `university_tutor_id` int unsigned NOT NULL,
  `tutor_email` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `status` varchar(30) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'accepted',
  `accepted_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `university_request_tutor` (`university_lecture_request_id`, `university_tutor_id`),
  KEY `university_lecture_request_id` (`university_lecture_request_id`),
  KEY `university_tutor_id` (`university_tutor_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Rollback SQL if needed:
-- DROP TABLE IF EXISTS `university_lecture_request_applications`;
-- ALTER TABLE `university_lecture_requests` DROP COLUMN `emailed_tutor_count`;
-- ALTER TABLE `university_lecture_requests` DROP COLUMN `matched_tutor_count`;
