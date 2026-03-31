<?php

namespace App\Models;

use CodeIgniter\Model;

class SubscriptionPlanModel extends Model
{
    protected $table            = 'subscription_plans';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name', 'description', 'price_monthly', 'max_profile_views', 'max_clicks',
        'max_subjects', 'district_spotlight_days', 'badge_level', 'search_ranking',
        'show_whatsapp', 'email_marketing_access', 'allow_video_upload', 'allow_pdf_upload',
        'allow_announcements', 'allow_video_solution', 'is_active', 'sort_order'
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
        'name' => 'required|max_length[100]',
        'price_monthly' => 'required|decimal',
        'is_active' => 'required|in_list[0,1]',
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

    // Get active plans ordered by sort_order
    public function getActivePlans()
    {
        return $this->where('is_active', 1)->orderBy('sort_order', 'ASC')->findAll();
    }

    // Get plan with features as array
    public function getPlanWithFeatures($id)
    {
        $plan = $this->find($id);
        if ($plan && $plan['features']) {
            $plan['features_array'] = json_decode($plan['features'], true) ?? [];
        }
        return $plan;
    }
}
