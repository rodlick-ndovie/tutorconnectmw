ALTER TABLE `past_papers`
ADD COLUMN `is_paid` TINYINT(1) NOT NULL DEFAULT 0 AFTER `is_active`,
ADD COLUMN `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00 AFTER `is_paid`;

CREATE TABLE `past_paper_purchases` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `past_paper_id` INT(11) UNSIGNED NOT NULL,
  `user_id` INT(11) UNSIGNED DEFAULT NULL,
  `tx_ref` VARCHAR(80) NOT NULL,
  `buyer_name` VARCHAR(255) NOT NULL,
  `buyer_email` VARCHAR(150) NOT NULL,
  `buyer_phone` VARCHAR(30) DEFAULT NULL,
  `amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `currency` VARCHAR(10) NOT NULL DEFAULT 'MWK',
  `payment_method` VARCHAR(30) NOT NULL DEFAULT 'paychangu',
  `payment_status` VARCHAR(30) NOT NULL DEFAULT 'pending',
  `access_token` VARCHAR(80) NOT NULL,
  `paid_at` DATETIME DEFAULT NULL,
  `download_granted_at` DATETIME DEFAULT NULL,
  `last_downloaded_at` DATETIME DEFAULT NULL,
  `download_count` INT(11) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ux_past_paper_purchases_tx_ref` (`tx_ref`),
  UNIQUE KEY `ux_past_paper_purchases_access_token` (`access_token`),
  KEY `idx_past_paper_purchases_past_paper_id` (`past_paper_id`),
  KEY `idx_past_paper_purchases_user_id` (`user_id`),
  KEY `idx_past_paper_purchases_buyer_email` (`buyer_email`),
  KEY `idx_past_paper_purchases_payment_status` (`payment_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
