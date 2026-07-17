<?php

namespace App\Commands;

use App\Libraries\SubscriptionRenewalReminderService;
use App\Models\TutorSubscriptionModel;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

class SendSubscriptionRenewalReminders extends BaseCommand
{
    protected $group = 'Subscriptions';
    protected $name = 'subscriptions:send-renewal-reminders';
    protected $description = 'Expire due subscriptions, clean stale pending payments, and send renewal reminder emails.';
    protected $usage = 'subscriptions:send-renewal-reminders [--dry-run]';
    protected $options = [
        '--dry-run' => 'Preview expiry cleanup and due reminders without changing records or sending emails.',
    ];

    public function run(array $params)
    {
        $dryRun = CLI::getOption('dry-run') !== null || in_array('--dry-run', $params, true);
        $subscriptionModel = new TutorSubscriptionModel();
        $service = new SubscriptionRenewalReminderService();
        $now = date('Y-m-d H:i:s');
        $pendingTimeoutMinutes = max(10, min((int) (getenv('PAYMENT_PENDING_TIMEOUT_MINUTES') ?: 45), 1440));

        CLI::write('Subscription maintenance job started at ' . $now, 'yellow');
        CLI::write($dryRun ? 'Mode: DRY RUN' : 'Mode: LIVE UPDATE', $dryRun ? 'cyan' : 'green');
        CLI::newLine();

        $this->runExpiryCleanup($subscriptionModel, $pendingTimeoutMinutes, $dryRun);
        CLI::newLine();

        try {
            $summary = $service->sendDueReminders($dryRun);
        } catch (\Throwable $e) {
            CLI::error($e->getMessage());
            return;
        }

        CLI::write('Checked: ' . $summary['checked'], 'white');
        CLI::write('Due: ' . $summary['due'], 'white');
        CLI::write('Sent: ' . $summary['sent'], 'green');
        CLI::write('Failed: ' . $summary['failed'], $summary['failed'] > 0 ? 'red' : 'white');
        CLI::write('Skipped: ' . $summary['skipped'], 'white');
        CLI::write('Queued renewals ignored: ' . $summary['queued_renewals'], 'white');

        if (!empty($summary['items'])) {
            CLI::newLine();
            CLI::write('Reminder details:', 'yellow');

            foreach ($summary['items'] as $item) {
                $status = !empty($item['success']) ? 'SENT' : ($dryRun ? 'DUE' : 'FAILED');
                $color = !empty($item['success']) ? 'green' : ($dryRun ? 'cyan' : 'red');
                $line = sprintf(
                    '[%s] subscription #%d | user #%d | %s | %s | expires %s',
                    $status,
                    (int) $item['subscription_id'],
                    (int) $item['user_id'],
                    $item['reminder_type'],
                    $item['email'],
                    $item['expires_at']
                );

                CLI::write($line, $color);

                if (!$dryRun && !empty($item['message']) && empty($item['success'])) {
                    CLI::write('  Reason: ' . $item['message'], 'red');
                }
            }
        }

        CLI::newLine();
        CLI::write('Subscription maintenance job finished.', 'yellow');
    }

    private function runExpiryCleanup(TutorSubscriptionModel $subscriptionModel, int $pendingTimeoutMinutes, bool $dryRun): void
    {
        $db = Database::connect();
        $now = date('Y-m-d H:i:s');
        $pendingCutoff = date('Y-m-d H:i:s', strtotime('-' . $pendingTimeoutMinutes . ' minutes'));

        $expiredCandidates = $this->getExpiredCandidates($db, $now);
        $stalePendingCandidates = $this->getStalePendingCandidates($db, $pendingCutoff);

        CLI::write('Expiry cleanup:', 'yellow');
        $this->writePortalSummary('  Due to expire', $expiredCandidates);
        $this->writePortalSummary('  Stale pending PayChangu attempts', $stalePendingCandidates);

        if ($dryRun) {
            CLI::write('  No expiry records changed in dry-run mode.', 'cyan');
            return;
        }

        $expiredCount = $subscriptionModel->markExpiredSubscriptions();
        $failedPendingCount = $subscriptionModel->markStalePendingPayments(null, $pendingTimeoutMinutes);

        CLI::write('  Expired subscriptions updated: ' . $expiredCount, $expiredCount > 0 ? 'green' : 'white');
        CLI::write('  Stale pending payments marked failed: ' . $failedPendingCount, $failedPendingCount > 0 ? 'green' : 'white');
    }

    private function getExpiredCandidates($db, string $now): array
    {
        return $db->table('tutor_subscriptions ts')
            ->select('ts.id, ts.user_id, ts.current_period_end, u.email, sp.portal_type, sp.name AS plan_name')
            ->join('users u', 'u.id = ts.user_id', 'left')
            ->join('subscription_plans sp', 'sp.id = ts.plan_id', 'left')
            ->where('ts.status', 'active')
            ->where('ts.current_period_end <', $now)
            ->orderBy('ts.current_period_end', 'ASC')
            ->get()
            ->getResultArray();
    }

    private function getStalePendingCandidates($db, string $pendingCutoff): array
    {
        return $db->table('tutor_subscriptions ts')
            ->select('ts.id, ts.user_id, ts.created_at, u.email, sp.portal_type, sp.name AS plan_name')
            ->join('users u', 'u.id = ts.user_id', 'left')
            ->join('subscription_plans sp', 'sp.id = ts.plan_id', 'left')
            ->where('ts.status', 'pending')
            ->where('ts.payment_status', 'pending')
            ->where('ts.payment_method', 'paychangu')
            ->where('ts.created_at <', $pendingCutoff)
            ->orderBy('ts.created_at', 'ASC')
            ->get()
            ->getResultArray();
    }

    private function writePortalSummary(string $label, array $rows): void
    {
        $regular = 0;
        $university = 0;

        foreach ($rows as $row) {
            if (strtolower((string) ($row['portal_type'] ?? 'trainer')) === 'university') {
                $university++;
            } else {
                $regular++;
            }
        }

        CLI::write($label . ': ' . count($rows), count($rows) > 0 ? 'yellow' : 'white');
        CLI::write('    Regular tutors: ' . $regular, 'white');
        CLI::write('    University tutors: ' . $university, 'white');
    }
}
