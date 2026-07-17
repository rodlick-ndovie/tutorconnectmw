-- TutorConnect Malawi
-- University firm account updates
-- Generated for the migrations added/updated on 2026-05-29.
--
-- Safe to run more than once on MySQL/MariaDB.
-- It covers:
-- 1. app/Database/Migrations/2026-05-29-000001_AddAccountTypeToUniversityCollegeTutors.php
-- 2. app/Database/Migrations/2026-05-29-000002_AddFirmUniversityPlan.php
-- 3. The Firm plan added to app/Database/Migrations/2026-05-19-000002_AddDefaultUniversityPlans.php

START TRANSACTION;

-- Prerequisite columns used by university subscription plans.
-- These are normally handled by 2026-05-19-000001_AddPortalTypeToSubscriptionPlans.php,
-- but are included here so the firm plan SQL can run on older databases too.
SET @sql := (
    SELECT IF(
        COUNT(*) = 0,
        'ALTER TABLE `subscription_plans` ADD COLUMN `features` TEXT NULL AFTER `price_monthly`',
        'SELECT 1'
    )
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'subscription_plans'
      AND COLUMN_NAME = 'features'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql := (
    SELECT IF(
        COUNT(*) = 0,
        'ALTER TABLE `subscription_plans` ADD COLUMN `portal_type` VARCHAR(30) NOT NULL DEFAULT ''trainer'' AFTER `features`',
        'SELECT 1'
    )
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'subscription_plans'
      AND COLUMN_NAME = 'portal_type'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add account type for university/college tutor profiles.
SET @sql := (
    SELECT IF(
        COUNT(*) = 0,
        'ALTER TABLE `university_college_tutors` ADD COLUMN `account_type` VARCHAR(20) NOT NULL DEFAULT ''individual'' AFTER `user_id`',
        'SELECT 1'
    )
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'university_college_tutors'
      AND COLUMN_NAME = 'account_type'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Backfill older rows explicitly.
UPDATE `university_college_tutors`
SET `account_type` = 'individual'
WHERE `account_type` IS NULL OR `account_type` = '';

-- Keep the university default plans aligned with the updated migration.
UPDATE `subscription_plans`
SET
    `description` = 'New tutors joining the platform',
    `price_monthly` = 2000.00,
    `features` = '["Profile listing in university portal","Core visibility for new tutors","Access to subscription management"]',
    `is_active` = 1,
    `sort_order` = 1,
    `updated_at` = NOW()
WHERE `portal_type` = 'university'
  AND `name` = 'Basic';

INSERT INTO `subscription_plans`
    (`name`, `description`, `price_monthly`, `features`, `portal_type`, `is_active`, `sort_order`, `created_at`, `updated_at`)
SELECT
    'Basic',
    'New tutors joining the platform',
    2000.00,
    '["Profile listing in university portal","Core visibility for new tutors","Access to subscription management"]',
    'university',
    1,
    1,
    NOW(),
    NOW()
WHERE NOT EXISTS (
    SELECT 1 FROM `subscription_plans`
    WHERE `portal_type` = 'university'
      AND `name` = 'Basic'
);

UPDATE `subscription_plans`
SET
    `description` = 'Active tutors seeking more visibility',
    `price_monthly` = 5000.00,
    `features` = '["Enhanced visibility in university portal","Improved placement over Basic","Higher exposure to potential clients"]',
    `is_active` = 1,
    `sort_order` = 2,
    `updated_at` = NOW()
WHERE `portal_type` = 'university'
  AND `name` = 'Standard';

INSERT INTO `subscription_plans`
    (`name`, `description`, `price_monthly`, `features`, `portal_type`, `is_active`, `sort_order`, `created_at`, `updated_at`)
SELECT
    'Standard',
    'Active tutors seeking more visibility',
    5000.00,
    '["Enhanced visibility in university portal","Improved placement over Basic","Higher exposure to potential clients"]',
    'university',
    1,
    2,
    NOW(),
    NOW()
WHERE NOT EXISTS (
    SELECT 1 FROM `subscription_plans`
    WHERE `portal_type` = 'university'
      AND `name` = 'Standard'
);

UPDATE `subscription_plans`
SET
    `description` = 'Highly active tutors requiring priority placement and enhanced exposure',
    `price_monthly` = 10000.00,
    `features` = '["Priority placement for qualified tutors","Maximum exposure in university portal","Best rank among university plans"]',
    `is_active` = 1,
    `sort_order` = 3,
    `updated_at` = NOW()
WHERE `portal_type` = 'university'
  AND `name` = 'Premium';

INSERT INTO `subscription_plans`
    (`name`, `description`, `price_monthly`, `features`, `portal_type`, `is_active`, `sort_order`, `created_at`, `updated_at`)
SELECT
    'Premium',
    'Highly active tutors requiring priority placement and enhanced exposure',
    10000.00,
    '["Priority placement for qualified tutors","Maximum exposure in university portal","Best rank among university plans"]',
    'university',
    1,
    3,
    NOW(),
    NOW()
WHERE NOT EXISTS (
    SELECT 1 FROM `subscription_plans`
    WHERE `portal_type` = 'university'
      AND `name` = 'Premium'
);

-- Firm-only university plan: MWK 15,000.
UPDATE `subscription_plans`
SET
    `description` = 'Firm-only university support subscription',
    `price_monthly` = 15000.00,
    `features` = '["Approved firm listing in the university portal","Company logo display","Business certificate verification","Priority eligibility for institutional support requests","Firm profile placement in university support listings"]',
    `is_active` = 1,
    `sort_order` = 4,
    `updated_at` = NOW()
WHERE `portal_type` = 'university'
  AND `name` = 'Firm';

INSERT INTO `subscription_plans`
    (`name`, `description`, `price_monthly`, `features`, `portal_type`, `is_active`, `sort_order`, `created_at`, `updated_at`)
SELECT
    'Firm',
    'Firm-only university support subscription',
    15000.00,
    '["Approved firm listing in the university portal","Company logo display","Business certificate verification","Priority eligibility for institutional support requests","Firm profile placement in university support listings"]',
    'university',
    1,
    4,
    NOW(),
    NOW()
WHERE NOT EXISTS (
    SELECT 1 FROM `subscription_plans`
    WHERE `portal_type` = 'university'
      AND `name` = 'Firm'
);

COMMIT;
