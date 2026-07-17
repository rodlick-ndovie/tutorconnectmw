-- Expire the subscription for rodlick.ndovie@gmail.com (user_id = 106)
-- Run this SQL to set the active subscription as expired for testing

UPDATE tutorconnectmw.tutor_subscriptions 
SET status = 'expired', 
    current_period_end = '2026-04-09 23:59:59'
WHERE user_id = 106 
  AND status = 'active'
ORDER BY id DESC 
LIMIT 1;

-- Verify the change
SELECT id, user_id, status, current_period_start, current_period_end 
FROM tutorconnectmw.tutor_subscriptions 
WHERE user_id = 106 
ORDER BY id DESC 
LIMIT 5;