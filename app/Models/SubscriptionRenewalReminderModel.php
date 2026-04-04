<?php

namespace App\Models;

use CodeIgniter\Model;

class SubscriptionRenewalReminderModel extends Model
{
    protected $table            = 'subscription_renewal_reminders';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'subscription_id',
        'user_id',
        'reminder_type',
        'target_period_end',
        'recipient_email',
        'status',
        'error_message',
        'sent_at',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function findReminder(int $subscriptionId, string $reminderType, string $targetPeriodEnd): ?array
    {
        return $this->where('subscription_id', $subscriptionId)
            ->where('reminder_type', $reminderType)
            ->where('target_period_end', $targetPeriodEnd)
            ->first();
    }

    public function isHandled(int $subscriptionId, string $reminderType, string $targetPeriodEnd): bool
    {
        $reminder = $this->findReminder($subscriptionId, $reminderType, $targetPeriodEnd);

        if (!$reminder) {
            return false;
        }

        return in_array($reminder['status'], ['sent', 'skipped'], true);
    }

    public function logReminder(array $data): bool
    {
        $existing = $this->findReminder(
            (int) $data['subscription_id'],
            (string) $data['reminder_type'],
            (string) $data['target_period_end']
        );

        if ($existing) {
            $data['id'] = $existing['id'];
        }

        return (bool) $this->save($data);
    }
}
