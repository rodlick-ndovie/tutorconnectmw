<?php

namespace App\Commands;

use App\Libraries\SubscriptionRenewalReminderService;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class SendSubscriptionRenewalReminders extends BaseCommand
{
    protected $group = 'Subscriptions';
    protected $name = 'subscriptions:send-renewal-reminders';
    protected $description = 'Send automatic subscription renewal reminder emails for 5 days, 2 days, and 6 hours before expiry.';
    protected $usage = 'subscriptions:send-renewal-reminders [--dry-run]';
    protected $options = [
        '--dry-run' => 'Preview due reminders without sending any emails.',
    ];

    public function run(array $params)
    {
        $dryRun = CLI::getOption('dry-run') !== null || in_array('--dry-run', $params, true);
        $service = new SubscriptionRenewalReminderService();

        CLI::write('Subscription renewal reminder job started at ' . date('Y-m-d H:i:s'), 'yellow');
        CLI::write($dryRun ? 'Mode: DRY RUN' : 'Mode: LIVE SEND', $dryRun ? 'cyan' : 'green');
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
        CLI::write('Subscription renewal reminder job finished.', 'yellow');
    }
}
