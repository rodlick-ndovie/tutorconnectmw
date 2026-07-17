-- Japan application payment access table
-- Run this script inside the target TutorConnect database, for example: tutorconnectmw

CREATE TABLE IF NOT EXISTS `japan_application_access` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `tx_ref` VARCHAR(80) NOT NULL,
    `full_name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(150) NOT NULL,
    `phone` VARCHAR(30) DEFAULT NULL,
    `amount` DECIMAL(10,2) NOT NULL DEFAULT 10000.00,
    `currency` VARCHAR(10) NOT NULL DEFAULT 'MWK',
    `payment_method` VARCHAR(30) NOT NULL DEFAULT 'paychangu',
    `payment_status` VARCHAR(30) NOT NULL DEFAULT 'pending',
    `access_status` VARCHAR(30) NOT NULL DEFAULT 'pending',
    `access_token` VARCHAR(80) NOT NULL,
    `paid_at` DATETIME DEFAULT NULL,
    `last_accessed_at` DATETIME DEFAULT NULL,
    `application_id` INT(11) UNSIGNED DEFAULT NULL,
    `created_at` DATETIME DEFAULT NULL,
    `updated_at` DATETIME DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_japan_application_access_tx_ref` (`tx_ref`),
    UNIQUE KEY `uq_japan_application_access_token` (`access_token`),
    KEY `idx_japan_application_access_email` (`email`),
    KEY `idx_japan_application_access_payment_status` (`payment_status`),
    KEY `idx_japan_application_access_status` (`access_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
