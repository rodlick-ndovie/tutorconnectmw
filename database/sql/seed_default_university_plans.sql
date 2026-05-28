-- Equivalent SQL for:
-- app/Database/Migrations/2026-05-19-000002_AddDefaultUniversityPlans.php

SET @now = NOW();

UPDATE `subscription_plans`
SET
  `description` = 'New tutors joining the platform',
  `price_monthly` = 2000.00,
  `features` = '["Profile listing in university portal","Core visibility for new tutors","Access to subscription management"]',
  `is_active` = 1,
  `sort_order` = 1,
  `updated_at` = @now
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
  @now,
  @now
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
  `updated_at` = @now
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
  @now,
  @now
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
  `updated_at` = @now
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
  @now,
  @now
WHERE NOT EXISTS (
  SELECT 1 FROM `subscription_plans`
  WHERE `portal_type` = 'university'
    AND `name` = 'Premium'
);

-- Rollback SQL if needed:
-- DELETE FROM `subscription_plans`
-- WHERE `portal_type` = 'university'
--   AND `name` IN ('Basic', 'Standard', 'Premium');
