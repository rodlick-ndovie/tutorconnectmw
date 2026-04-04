-- Equivalent SQL for:
-- app/Database/Migrations/2026-04-04-000001_AddBillingMonthsToTutorSubscriptions.php

-- Add billing_months if it does not already exist
SET @sql = (
    SELECT IF(
        EXISTS(
            SELECT 1
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'tutor_subscriptions'
              AND COLUMN_NAME = 'billing_months'
        ),
        'SELECT ''billing_months already exists''',
        'ALTER TABLE `tutor_subscriptions` ADD COLUMN `billing_months` INT UNSIGNED NOT NULL DEFAULT 1 AFTER `plan_id`'
    )
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add upgrading_from if it does not already exist
SET @sql = (
    SELECT IF(
        EXISTS(
            SELECT 1
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'tutor_subscriptions'
              AND COLUMN_NAME = 'upgrading_from'
        ),
        'SELECT ''upgrading_from already exists''',
        'ALTER TABLE `tutor_subscriptions` ADD COLUMN `upgrading_from` INT UNSIGNED NULL DEFAULT NULL AFTER `payment_proof_file`'
    )
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Rollback SQL if needed:
-- ALTER TABLE `tutor_subscriptions` DROP COLUMN `billing_months`;
-- ALTER TABLE `tutor_subscriptions` DROP COLUMN `upgrading_from`;
