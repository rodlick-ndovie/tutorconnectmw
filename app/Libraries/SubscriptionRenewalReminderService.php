<?php

namespace App\Libraries;

use App\Models\SubscriptionRenewalReminderModel;
use App\Models\TutorSubscriptionModel;
use Config\Database;

class SubscriptionRenewalReminderService
{
    private const REMINDER_DEFINITIONS = [
        '5_days' => [
            'seconds' => 432000,
            'rank' => 1,
            'subject' => 'Your TutorConnect subscription expires in 5 days',
            'headline' => 'Renew in the next 5 days to keep your tutor profile active.',
            'label' => '5 days',
        ],
        '2_days' => [
            'seconds' => 172800,
            'rank' => 2,
            'subject' => 'Reminder: your TutorConnect subscription expires in 2 days',
            'headline' => 'Only 2 days left before your subscription expires.',
            'label' => '2 days',
        ],
        '6_hours' => [
            'seconds' => 21600,
            'rank' => 3,
            'subject' => 'Final reminder: your TutorConnect subscription expires in 6 hours',
            'headline' => 'Final reminder: your subscription expires in 6 hours.',
            'label' => '6 hours',
        ],
    ];

    private TutorSubscriptionModel $subscriptionModel;
    private SubscriptionRenewalReminderModel $reminderModel;

    public function __construct(
        ?TutorSubscriptionModel $subscriptionModel = null,
        ?SubscriptionRenewalReminderModel $reminderModel = null
    ) {
        helper('url');

        $this->subscriptionModel = $subscriptionModel ?? new TutorSubscriptionModel();
        $this->reminderModel = $reminderModel ?? new SubscriptionRenewalReminderModel();
    }

    public function sendDueReminders(bool $dryRun = false): array
    {
        $this->ensureReminderTableExists();

        $nowTs = time();
        $now = date('Y-m-d H:i:s', $nowTs);
        $maxReminderSeconds = max(array_column(self::REMINDER_DEFINITIONS, 'seconds'));
        $scanUntil = date('Y-m-d H:i:s', $nowTs + $maxReminderSeconds);

        $this->subscriptionModel->markExpiredSubscriptions();

        $subscriptions = $this->subscriptionModel
            ->select('tutor_subscriptions.*, users.first_name, users.last_name, users.username, users.email, subscription_plans.name as plan_name, subscription_plans.price_monthly')
            ->join('users', 'users.id = tutor_subscriptions.user_id')
            ->join('subscription_plans', 'subscription_plans.id = tutor_subscriptions.plan_id', 'left')
            ->where('tutor_subscriptions.status', 'active')
            ->where('tutor_subscriptions.current_period_start <=', $now)
            ->where('tutor_subscriptions.current_period_end >', $now)
            ->where('tutor_subscriptions.current_period_end <=', $scanUntil)
            ->where('users.role', 'trainer')
            ->where('users.is_active', 1)
            ->where('users.email !=', '')
            ->orderBy('tutor_subscriptions.current_period_end', 'ASC')
            ->findAll();

        $summary = [
            'checked' => count($subscriptions),
            'due' => 0,
            'sent' => 0,
            'failed' => 0,
            'skipped' => 0,
            'queued_renewals' => 0,
            'items' => [],
        ];

        foreach ($subscriptions as $subscription) {
            if ($this->hasQueuedRenewal($subscription, $now)) {
                $summary['queued_renewals']++;
                $summary['skipped']++;
                continue;
            }

            $reminderType = $this->determineDueReminderType($subscription, $nowTs);
            if ($reminderType === null) {
                continue;
            }

            $summary['due']++;

            if ($dryRun) {
                $summary['items'][] = [
                    'subscription_id' => $subscription['id'],
                    'user_id' => $subscription['user_id'],
                    'email' => $subscription['email'],
                    'name' => trim(($subscription['first_name'] ?? '') . ' ' . ($subscription['last_name'] ?? '')),
                    'plan_name' => $subscription['plan_name'] ?? 'Plan',
                    'reminder_type' => $reminderType,
                    'expires_at' => $subscription['current_period_end'],
                ];
                continue;
            }

            $result = $this->sendReminder($subscription, $reminderType);
            $summary['items'][] = $result;

            if ($result['success']) {
                $summary['sent']++;
            } else {
                $summary['failed']++;
            }
        }

        return $summary;
    }

    private function hasQueuedRenewal(array $subscription, string $now): bool
    {
        $futureSubscription = $this->subscriptionModel
            ->where('user_id', (int) $subscription['user_id'])
            ->where('status', 'active')
            ->where('id !=', (int) $subscription['id'])
            ->where('current_period_start >', $now)
            ->where('current_period_start <=', $subscription['current_period_end'])
            ->first();

        return $futureSubscription !== null;
    }

    private function determineDueReminderType(array $subscription, int $nowTs): ?string
    {
        $expiryTs = strtotime((string) ($subscription['current_period_end'] ?? ''));
        if ($expiryTs === false) {
            return null;
        }

        foreach ($this->getReminderTypesByUrgency() as $reminderType) {
            $definition = self::REMINDER_DEFINITIONS[$reminderType];
            $triggerTs = $expiryTs - $definition['seconds'];

            if ($nowTs < $triggerTs) {
                continue;
            }

            if ($this->reminderModel->isHandled((int) $subscription['id'], $reminderType, $subscription['current_period_end'])) {
                continue;
            }

            return $reminderType;
        }

        return null;
    }

    private function sendReminder(array $subscription, string $reminderType): array
    {
        $payload = $this->buildEmailPayload($subscription, $reminderType);
        $emailConfig = config('Email');
        $emailService = \Config\Services::email();
        $now = date('Y-m-d H:i:s');

        $result = [
            'success' => false,
            'subscription_id' => $subscription['id'],
            'user_id' => $subscription['user_id'],
            'email' => $subscription['email'],
            'reminder_type' => $reminderType,
            'expires_at' => $subscription['current_period_end'],
        ];

        try {
            $emailService->setFrom($emailConfig->fromEmail, $emailConfig->fromName);
            $emailService->setTo($subscription['email']);
            $emailService->setSubject($payload['subject']);
            $emailService->setMessage($payload['html']);
            $emailService->setAltMessage($payload['text']);

            if ($emailService->send(false)) {
                $this->reminderModel->logReminder([
                    'subscription_id' => (int) $subscription['id'],
                    'user_id' => (int) $subscription['user_id'],
                    'reminder_type' => $reminderType,
                    'target_period_end' => $subscription['current_period_end'],
                    'recipient_email' => $subscription['email'],
                    'status' => 'sent',
                    'error_message' => null,
                    'sent_at' => $now,
                ]);

                $this->markLessUrgentRemindersAsSkipped($subscription, $reminderType);

                $result['success'] = true;
                $result['message'] = 'Reminder sent';

                return $result;
            }

            $debugOutput = trim(strip_tags($emailService->printDebugger(['headers', 'subject'])));

            $this->reminderModel->logReminder([
                'subscription_id' => (int) $subscription['id'],
                'user_id' => (int) $subscription['user_id'],
                'reminder_type' => $reminderType,
                'target_period_end' => $subscription['current_period_end'],
                'recipient_email' => $subscription['email'],
                'status' => 'failed',
                'error_message' => $debugOutput ?: 'Email sending failed.',
                'sent_at' => null,
            ]);

            $result['message'] = $debugOutput ?: 'Email sending failed.';
        } catch (\Throwable $e) {
            $this->reminderModel->logReminder([
                'subscription_id' => (int) $subscription['id'],
                'user_id' => (int) $subscription['user_id'],
                'reminder_type' => $reminderType,
                'target_period_end' => $subscription['current_period_end'],
                'recipient_email' => $subscription['email'],
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'sent_at' => null,
            ]);

            $result['message'] = $e->getMessage();
        }

        return $result;
    }

    private function markLessUrgentRemindersAsSkipped(array $subscription, string $sentReminderType): void
    {
        $sentRank = self::REMINDER_DEFINITIONS[$sentReminderType]['rank'];

        foreach (self::REMINDER_DEFINITIONS as $reminderType => $definition) {
            if ($definition['rank'] >= $sentRank) {
                continue;
            }

            $existing = $this->reminderModel->findReminder(
                (int) $subscription['id'],
                $reminderType,
                $subscription['current_period_end']
            );

            if ($existing && $existing['status'] === 'sent') {
                continue;
            }

            $this->reminderModel->logReminder([
                'subscription_id' => (int) $subscription['id'],
                'user_id' => (int) $subscription['user_id'],
                'reminder_type' => $reminderType,
                'target_period_end' => $subscription['current_period_end'],
                'recipient_email' => $subscription['email'],
                'status' => 'skipped',
                'error_message' => 'Skipped because a more urgent reminder was already sent.',
                'sent_at' => null,
            ]);
        }
    }

    private function buildEmailPayload(array $subscription, string $reminderType): array
    {
        $definition = self::REMINDER_DEFINITIONS[$reminderType];
        $name = trim((string) (($subscription['first_name'] ?? '') . ' ' . ($subscription['last_name'] ?? '')));
        $name = $name !== '' ? $name : ($subscription['username'] ?? 'Tutor');
        $planName = $subscription['plan_name'] ?? 'Tutor plan';
        $renewUrl = base_url('trainer/subscription');
        $expiryDate = !empty($subscription['current_period_end'])
            ? date('M j, Y \a\t g:i A', strtotime($subscription['current_period_end']))
            : 'your expiry date';
        $price = isset($subscription['price_monthly']) ? 'MWK ' . number_format((float) $subscription['price_monthly'], 0) . '/month' : 'your current rate';

        $html = "
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>{$definition['subject']}</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8fafc; color: #334155; margin: 0; padding: 0; }
        .container { max-width: 620px; margin: 24px auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 12px 32px rgba(15, 23, 42, 0.08); }
        .header { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; padding: 32px 28px; }
        .content { padding: 28px; }
        .badge { display: inline-block; background: rgba(255,255,255,0.18); border: 1px solid rgba(255,255,255,0.28); border-radius: 999px; padding: 8px 14px; font-size: 13px; font-weight: 700; margin-bottom: 14px; }
        .summary { background: #fff7ed; border: 1px solid #fdba74; border-radius: 14px; padding: 18px; margin: 22px 0; }
        .summary-row { margin: 10px 0; }
        .summary-label { color: #9a3412; font-weight: 700; }
        .cta { display: inline-block; margin-top: 24px; background: #1d4ed8; color: #fff !important; text-decoration: none; padding: 14px 28px; border-radius: 12px; font-weight: 700; }
        .footer { padding: 22px 28px; background: #f8fafc; color: #64748b; font-size: 13px; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <div class='badge'>Renewal Reminder</div>
            <h1 style='margin: 0 0 10px; font-size: 28px;'>Subscription Expiring Soon</h1>
            <p style='margin: 0; font-size: 16px; line-height: 1.6;'>{$definition['headline']}</p>
        </div>
        <div class='content'>
            <p>Hello {$name},</p>
            <p>Your <strong>{$planName}</strong> subscription is scheduled to expire on <strong>{$expiryDate}</strong>.</p>
            <p>To keep your tutor profile visible and your paid features active without interruption, please renew before the expiry time.</p>

            <div class='summary'>
                <div class='summary-row'><span class='summary-label'>Plan:</span> {$planName}</div>
                <div class='summary-row'><span class='summary-label'>Current rate:</span> {$price}</div>
                <div class='summary-row'><span class='summary-label'>Reminder window:</span> {$definition['label']} before expiry</div>
                <div class='summary-row'><span class='summary-label'>Expires on:</span> {$expiryDate}</div>
            </div>

            <a href='{$renewUrl}' class='cta'>Renew Subscription</a>

            <p style='margin-top: 24px;'>If you already renewed and can see a new future subscription period in your account, you can ignore this email.</p>
        </div>
        <div class='footer'>
            TutorConnect Malawi<br>
            Need help? Reply to this email or contact support.
        </div>
    </div>
</body>
</html>";

        $text = "TutorConnect Malawi Subscription Renewal Reminder

Hello {$name},

Your {$planName} subscription expires on {$expiryDate}.

Reminder window: {$definition['label']} before expiry
Current rate: {$price}

Renew here: {$renewUrl}

If you already renewed and can see a new future subscription period in your account, you can ignore this email.";

        return [
            'subject' => $definition['subject'],
            'html' => $html,
            'text' => $text,
        ];
    }

    private function getReminderTypesByUrgency(): array
    {
        $definitions = self::REMINDER_DEFINITIONS;

        uasort($definitions, static function (array $left, array $right): int {
            return $right['rank'] <=> $left['rank'];
        });

        return array_keys($definitions);
    }

    private function ensureReminderTableExists(): void
    {
        $db = Database::connect();

        if (!$db->tableExists('subscription_renewal_reminders')) {
            throw new \RuntimeException('subscription_renewal_reminders table not found. Run the migration or SQL file first.');
        }
    }
}
