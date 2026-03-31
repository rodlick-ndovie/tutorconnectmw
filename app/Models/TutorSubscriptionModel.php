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
        'upgrading_from'
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

    // Get active subscription for a user
    public function getActiveSubscription($userId)
    {
        return $this->where('user_id', $userId)
                   ->where('status', 'active')
                   ->first();
    }

    // Get subscription with plan details
    public function getSubscriptionWithPlan($userId)
    {
        return $this->select('tutor_subscriptions.*, subscription_plans.name as plan_name, subscription_plans.price_monthly, subscription_plans.max_profile_views, subscription_plans.max_clicks, subscription_plans.max_subjects, subscription_plans.max_reviews, subscription_plans.max_messages, subscription_plans.show_whatsapp, subscription_plans.email_marketing_access, subscription_plans.allow_video_upload, subscription_plans.allow_pdf_upload, subscription_plans.allow_video_solution, subscription_plans.allow_announcements, subscription_plans.badge_level, subscription_plans.search_ranking, subscription_plans.district_spotlight_days')
                   ->join('subscription_plans', 'subscription_plans.id = tutor_subscriptions.plan_id')
                   ->where('tutor_subscriptions.user_id', $userId)
                   ->where('tutor_subscriptions.status', 'active')
                   ->first();
    }

    // Get all subscriptions for admin
    public function getAllWithDetails()
    {
        return $this->select('tutor_subscriptions.*, users.first_name, users.last_name, users.email, users.district, subscription_plans.name as plan_name, subscription_plans.price_monthly')
                   ->join('users', 'users.id = tutor_subscriptions.user_id')
                   ->join('subscription_plans', 'subscription_plans.id = tutor_subscriptions.plan_id')
                   ->orderBy('tutor_subscriptions.created_at', 'DESC')
                   ->findAll();
    }

    // Get billing history for a specific user
    public function getBillingHistory($userId)
    {
        return $this->select('tutor_subscriptions.*, subscription_plans.name as plan_name, subscription_plans.price_monthly')
                   ->join('subscription_plans', 'subscription_plans.id = tutor_subscriptions.plan_id')
                   ->where('tutor_subscriptions.user_id', $userId)
                   ->where('tutor_subscriptions.status', 'active')
                   ->orderBy('tutor_subscriptions.created_at', 'DESC')
                   ->findAll();
    }
}
