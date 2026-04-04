CREATE TABLE `subscription_renewal_reminders` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `subscription_id` INT UNSIGNED NOT NULL,
    `user_id` INT UNSIGNED NOT NULL,
    `reminder_type` VARCHAR(20) NOT NULL,
    `target_period_end` DATETIME NOT NULL,
    `recipient_email` VARCHAR(255) DEFAULT NULL,
    `status` ENUM('sent', 'failed', 'skipped') NOT NULL DEFAULT 'sent',
    `error_message` TEXT DEFAULT NULL,
    `sent_at` DATETIME DEFAULT NULL,
    `created_at` DATETIME DEFAULT NULL,
    `updated_at` DATETIME DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `subscription_renewal_reminders_subscription_id_index` (`subscription_id`),
    KEY `subscription_renewal_reminders_user_id_index` (`user_id`),
    KEY `subscription_renewal_reminders_type_period_index` (`reminder_type`, `target_period_end`),
    UNIQUE KEY `uniq_subscription_reminder_window` (`subscription_id`, `reminder_type`, `target_period_end`),
    CONSTRAINT `subscription_renewal_reminders_subscription_fk`
        FOREIGN KEY (`subscription_id`) REFERENCES `tutor_subscriptions` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `subscription_renewal_reminders_user_fk`
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
