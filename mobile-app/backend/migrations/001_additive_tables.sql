-- TutorConnect mobile API — additive tables only.
-- These are owned by the API and ignored by the PHP website. No existing
-- table or column is altered, so the live site is unaffected.

-- Saved/favorite tutors (the heart icon in the mobile UI; no web equivalent).
CREATE TABLE IF NOT EXISTS `favorites` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `tutor_id` int UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_user_tutor` (`user_id`, `tutor_id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_tutor` (`tutor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Expo push notification device tokens.
CREATE TABLE IF NOT EXISTS `device_tokens` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `expo_push_token` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `platform` enum('ios','android','web') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_token` (`expo_push_token`),
  KEY `idx_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Refresh tokens for mobile JWT sessions (hashed; supports rotation + revoke).
CREATE TABLE IF NOT EXISTS `refresh_tokens` (
  `id` char(36) COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `token_hash` char(64) COLLATE utf8mb4_general_ci NOT NULL,
  `device_info` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `expires_at` datetime NOT NULL,
  `revoked_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_hash` (`token_hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- In-app notification feed (mirrors what we also send via push/email).
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `title` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `body` text COLLATE utf8mb4_general_ci,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `read_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_read` (`user_id`, `read_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
