<?php

namespace App\Models;

use CodeIgniter\Model;

class TutorSubscriptionModel extends Model
{
    protected $table            = 'tutor_subscriptions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id', 'plan_id', 'status', 'current_period_start', 'current_period_end',
        'trial_end', 'cancel_at_period_end', 'payment_method', 'payment_reference',
        'payment_amount', 'payment_date', 'payment_status', 'terms_accepted', 'payment_proof_file',
        'upgrading_from', 'billing_months'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'user_id' => 'required|integer',
        'plan_id' => 'required|integer',
        'status' => 'required|in_list[active,inactive,cancelled,expired,pending]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    private function now(): string
    {
        return date('Y-m-d H:i:s');
    }

    public function normalizeBillingMonths($months): int
    {
        $months = (int) $months;

        if ($months < 1) {
            return 1;
        }

        return min($months, 120);
    }

    public function calculatePeriodEnd(string $startDate, int $billingMonths = 1): string
    {
        $billingMonths = $this->normalizeBillingMonths($billingMonths);

        return date('Y-m-d H:i:s', strtotime($startDate . ' +' . $billingMonths . ' month'));
    }

    public function markExpiredSubscriptions(?int $userId = null): int
    {
        $now = $this->now();
        $builder = $this->builder();

        $builder->where('status', 'active')
                ->where('current_period_end <', $now);

        if ($userId !== null) {
            $builder->where('user_id', $userId);
        }

        $expiredSubscriptions = $builder->get()->getResultArray();

        if (empty($expiredSubscriptions)) {
            return 0;
        }

        $expiredIds = array_column($expiredSubscriptions, 'id');
        $expiredUserIds = array_unique(array_map('intval', array_column($expiredSubscriptions, 'user_id')));

        $this->builder()
            ->whereIn('id', $expiredIds)
            ->update([
                'status' => 'expired',
                'updated_at' => $now,
            ]);

        foreach ($expiredUserIds as $expiredUserId) {
            $this->syncUserSubscriptionState($expiredUserId, false);
        }

        return count($expiredIds);
    }

    public function getLatestActiveSubscription($userId, ?int $planId = null)
    {
        $userId = (int) $userId;
        $this->markExpiredSubscriptions($userId);

        $query = $this->where('user_id', $userId)
                      ->where('status', 'active');

        if ($planId !== null) {
            $query->where('plan_id', $planId);
        }

        return $query->orderBy('current_period_end', 'DESC')
                     ->orderBy('current_period_start', 'DESC')
                     ->orderBy('id', 'DESC')
                     ->first();
    }

    // Get active subscription for a user
    public function getActiveSubscription($userId)
    {
        $userId = (int) $userId;
        $this->markExpiredSubscriptions($userId);
        $now = $this->now();

        return $this->where('user_id', $userId)
                   ->where('status', 'active')
                   ->where('current_period_start <=', $now)
                   ->where('current_period_end >=', $now)
                   ->orderBy('current_period_end', 'DESC')
                   ->orderBy('id', 'DESC')
                   ->first();
    }

    // Get subscription with plan details
    public function getSubscriptionWithPlan($userId)
    {
        $userId = (int) $userId;
        $this->markExpiredSubscriptions($userId);
        $now = $this->now();

        return $this->select('tutor_subscriptions.*, subscription_plans.name as plan_name, subscription_plans.price_monthly, subscription_plans.max_profile_views, subscription_plans.max_clicks, subscription_plans.max_subjects, subscription_plans.max_reviews, subscription_plans.max_messages, subscription_plans.show_whatsapp, subscription_plans.email_marketing_access, subscription_plans.allow_video_upload, subscription_plans.allow_pdf_upload, subscription_plans.allow_video_solution, subscription_plans.allow_announcements, subscription_plans.badge_level, subscription_plans.search_ranking, subscription_plans.district_spotlight_days')
                   ->join('subscription_plans', 'subscription_plans.id = tutor_subscriptions.plan_id')
                   ->where('tutor_subscriptions.user_id', $userId)
                   ->where('tutor_subscriptions.status', 'active')
                   ->where('tutor_subscriptions.current_period_start <=', $now)
                   ->where('tutor_subscriptions.current_period_end >=', $now)
                   ->orderBy('tutor_subscriptions.current_period_end', 'DESC')
                   ->first();
    }

    // Get all subscriptions for admin
    public function getAllWithDetails()
    {
        $this->markExpiredSubscriptions();

        return $this->select('tutor_subscriptions.*, users.first_name, users.last_name, users.email, users.district, subscription_plans.name as plan_name, subscription_plans.price_monthly')
                   ->join('users', 'users.id = tutor_subscriptions.user_id')
                   ->join('subscription_plans', 'subscription_plans.id = tutor_subscriptions.plan_id')
                   ->orderBy('tutor_subscriptions.current_period_end', 'DESC')
                   ->orderBy('tutor_subscriptions.created_at', 'DESC')
                   ->findAll();
    }

    // Get billing history for a specific user
    public function getBillingHistory($userId)
    {
        $userId = (int) $userId;
        $this->markExpiredSubscriptions($userId);

        return $this->select('tutor_subscriptions.*, subscription_plans.name as plan_name, subscription_plans.price_monthly')
                   ->join('subscription_plans', 'subscription_plans.id = tutor_subscriptions.plan_id')
                   ->where('tutor_subscriptions.user_id', $userId)
                   ->orderBy('tutor_subscriptions.current_period_end', 'DESC')
                   ->orderBy('tutor_subscriptions.created_at', 'DESC')
                   ->findAll();
    }

    public function syncUserSubscriptionState(int $userId, bool $markExpired = true): void
    {
        if ($markExpired) {
            $this->markExpiredSubscriptions($userId);
        }

        $userModel = new \App\Models\User();

        $latestActiveSubscription = $this->select('tutor_subscriptions.current_period_end, subscription_plans.name as plan_name')
            ->join('subscription_plans', 'subscription_plans.id = tutor_subscriptions.plan_id', 'left')
            ->where('tutor_subscriptions.user_id', $userId)
            ->where('tutor_subscriptions.status', 'active')
            ->orderBy('tutor_subscriptions.current_period_end', 'DESC')
            ->orderBy('tutor_subscriptions.current_period_start', 'DESC')
            ->orderBy('tutor_subscriptions.id', 'DESC')
            ->first();

        $latestSubscription = $latestActiveSubscription;

        if (!$latestSubscription) {
            $latestSubscription = $this->select('tutor_subscriptions.current_period_end, subscription_plans.name as plan_name')
                ->join('subscription_plans', 'subscription_plans.id = tutor_subscriptions.plan_id', 'left')
                ->where('tutor_subscriptions.user_id', $userId)
                ->orderBy('tutor_subscriptions.current_period_end', 'DESC')
                ->orderBy('tutor_subscriptions.id', 'DESC')
                ->first();
        }

        $userModel->update($userId, [
            'subscription_plan' => $latestSubscription['plan_name'] ?? null,
            'subscription_expires_at' => $latestSubscription['current_period_end'] ?? null,
        ]);
    }

    public function activateSubscription(int $subscriptionId, ?string $activationDate = null): ?array
    {
        $subscription = $this->find($subscriptionId);

        if (!$subscription) {
            return null;
        }

        $userId = (int) $subscription['user_id'];
        $this->markExpiredSubscriptions($userId);

        $now = $this->now();
        $billingMonths = $this->normalizeBillingMonths($subscription['billing_months'] ?? 1);
        $activationDate = $activationDate ?: ($subscription['payment_date'] ?: $now);
        $newPeriodStart = $activationDate;
        $isScheduledRenewal = false;

        if (!empty($subscription['upgrading_from'])) {
            $linkedSubscription = $this->find((int) $subscription['upgrading_from']);
            $linkedStillActive = $linkedSubscription
                && $linkedSubscription['status'] === 'active'
                && strtotime((string) $linkedSubscription['current_period_end']) >= strtotime($activationDate);
            $samePlan = $linkedSubscription
                && (int) $linkedSubscription['plan_id'] === (int) $subscription['plan_id'];

            if ($linkedStillActive && $samePlan) {
                $newPeriodStart = $linkedSubscription['current_period_end'];
                $isScheduledRenewal = strtotime($newPeriodStart) > strtotime($now);
            }
        }

        $newPeriodEnd = $this->calculatePeriodEnd($newPeriodStart, $billingMonths);

        $this->update($subscriptionId, [
            'status' => 'active',
            'payment_status' => 'verified',
            'current_period_start' => $newPeriodStart,
            'current_period_end' => $newPeriodEnd,
            'updated_at' => $now,
        ]);

        if (!$isScheduledRenewal) {
            $this->builder()
                ->where('user_id', $userId)
                ->where('status', 'active')
                ->where('id !=', $subscriptionId)
                ->update([
                    'status' => 'cancelled',
                    'updated_at' => $now,
                ]);
        }

        $this->syncUserSubscriptionState($userId, false);

        return $this->find($subscriptionId);
    }
}
