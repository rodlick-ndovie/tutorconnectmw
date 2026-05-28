-- Equivalent SQL for:
-- app/Database/Migrations/2026-05-19-000001_AddPortalTypeToSubscriptionPlans.php

-- Add features if it does not already exist.
SET @sql = (
    SELECT IF(
        EXISTS(
            SELECT 1
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'subscription_plans'
              AND COLUMN_NAME = 'features'
        ),
        'SELECT ''features already exists''',
        'ALTER TABLE `subscription_plans` ADD COLUMN `features` TEXT NULL AFTER `price_monthly`'
    )
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add portal_type if it does not already exist.
SET @sql = (
    SELECT IF(
        EXISTS(
            SELECT 1
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'subscription_plans'
              AND COLUMN_NAME = 'portal_type'
        ),
        'SELECT ''portal_type already exists''',
        'ALTER TABLE `subscription_plans` ADD COLUMN `portal_type` VARCHAR(30) NOT NULL DEFAULT ''trainer'' AFTER `features`'
    )
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Keep existing legacy plans in the regular tutor portal.
UPDATE `subscription_plans`
SET `portal_type` = 'trainer'
WHERE `portal_type` IS NULL OR `portal_type` = '';

-- Rollback SQL if needed:
-- ALTER TABLE `subscription_plans` DROP COLUMN `portal_type`;
-- ALTER TABLE `subscription_plans` DROP COLUMN `features`;
