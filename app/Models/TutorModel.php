<?php

namespace App\Models;

use CodeIgniter\Model;

class TutorModel extends Model
{
    protected $table            = 'users';  // Changed from 'tutors' to 'users' for consolidated tutor data
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields    = [
        'user_id', 'district', 'area', 'experience_years', 'rate', 'rate_type',
        'hourly_rate', 'teaching_mode', 'bio', 'bio_video', 'cv_file', 'training_papers',
        'national_id_file', 'academic_certificates_files', 'police_clearance_file', 'reference_files',
        'whatsapp_number', 'phone_visible', 'email_visible', 'best_call_time',
        'preferred_contact_method', 'is_verified', 'registration_completed', 'is_employed',
        'school_name', 'subscription_plan', 'subscription_expires_at',
        'rating', 'review_count', 'search_count', 'featured', 'status',
        'curriculum', 'subjects', 'education_levels', 'availability',
        'created_at', 'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    // Relationships - Updated for users table
    public function withUser()
    {
        // Since we're already in users table, just return self
        return $this;
    }

    public function withSubjects()
    {
        // Since subjects are stored as JSON in users table, no join needed
        return $this;
    }

    public function getWithAllRelations($id)
    {
        $tutor = $this->find($id);

        if (!$tutor) return null;

        // Tutor data is already in the users table, just format it
        $tutor['user'] = $tutor; // User data is the same as tutor data

        // Parse JSON fields from users table
        $tutor['subjects'] = json_decode($tutor['subjects'] ?? '[]', true) ?: [];
        $tutor['levels'] = json_decode($tutor['education_levels'] ?? '[]', true) ?: [];
        $tutor['curricula'] = json_decode($tutor['curriculum'] ?? '[]', true) ?: [];
        $tutor['structured_subjects'] = json_decode($tutor['structured_subjects'] ?? '[]', true) ?: [];
        $tutor['certificates'] = []; // Not stored separately in users table
        $tutor['references'] = []; // Not stored separately in users table
        $tutor['availability'] = json_decode($tutor['availability'] ?? '[]', true) ?: [];

        return $tutor;
    }

    public function getWithAllRelationsByUsername($username)
    {
        $tutor = $this->where('username', $username)->first();

        if (!$tutor) return null;

        // Tutor data is already in the users table, just format it
        $tutor['user'] = $tutor; // User data is the same as tutor data

        // Parse JSON fields from users table
        $tutor['subjects'] = json_decode($tutor['subjects'] ?? '[]', true) ?: [];
        $tutor['levels'] = json_decode($tutor['education_levels'] ?? '[]', true) ?: [];
        $tutor['curricula'] = json_decode($tutor['curriculum'] ?? '[]', true) ?: [];
        $tutor['structured_subjects'] = json_decode($tutor['structured_subjects'] ?? '[]', true) ?: [];
        $tutor['certificates'] = []; // Not stored separately in users table
        $tutor['references'] = []; // Not stored separately in users table
        $tutor['availability'] = json_decode($tutor['availability'] ?? '[]', true) ?: [];

        return $tutor;
    }

    // Search tutors with filters (ONLY VERIFIED & ACTIVE TUTORS WITH VALID SUBSCRIPTIONS)
    public function searchTutors($filters = [])
    {
        log_message('info', '=== SEARCH TUTORS DEBUG ===');
        log_message('info', 'Filters: ' . json_encode($filters));

        $builder = $this->select('users.*')
                       ->join('tutor_subscriptions', 'tutor_subscriptions.user_id = users.id', 'left')
                       ->where('users.role', 'trainer')           // Only trainers
                       ->where('users.tutor_status', 'approved')   // Approved tutors
                       ->where('users.is_verified', 1)           // Must be verified
                       ->where('users.is_active', 1)             // User account must be active
                       ->where('tutor_subscriptions.status', 'active')
                       ->where('tutor_subscriptions.current_period_end >=', date('Y-m-d H:i:s'))
                       ->groupBy('users.id'); // Prevent duplicate results from joins

        // Name search
        if (!empty($filters['name'])) {
            $builder->like('CONCAT(users.first_name, " ", users.last_name)', $filters['name']);
        }

        // District filter
        if (!empty($filters['district'])) {
            $builder->where('users.district', $filters['district']);
        }

        // Subject filter (JSON structured_subjects field)
        if (!empty($filters['subject'])) {
            $builder->like('users.structured_subjects', '"' . $filters['subject'] . '"');
        }

        // Education level filter (JSON field)
        if (!empty($filters['level'])) {
            $builder->like('users.education_levels', '"' . $filters['level'] . '"');
        }

        // Teaching mode filter
        if (!empty($filters['teaching_mode'])) {
            $builder->where('users.teaching_mode', $filters['teaching_mode']);
        }

        // Sort options
        $sortBy = $filters['sort_by'] ?? 'rating';
        switch($sortBy) {
            case 'experience':
                $builder->orderBy('users.experience_years', 'DESC');
                break;
            case 'rating':
            default:
                $builder->orderBy('users.rating', 'DESC');
                break;
        }

        $results = $builder->findAll();
        log_message('info', 'Search results count: ' . count($results));

        // Debug: Check what tutors exist in database
        $allTutors = $this->select('users.id, users.first_name, users.last_name, users.tutor_status, users.is_verified, users.is_active, tutor_subscriptions.status as sub_status, tutor_subscriptions.current_period_end')
                         ->join('tutor_subscriptions', 'tutor_subscriptions.user_id = users.id', 'left')
                         ->where('users.role', 'trainer')
                         ->findAll();

        log_message('info', 'All tutors in database: ' . count($allTutors));
        foreach ($allTutors as $tutor) {
            $subscriptionValid = ($tutor['sub_status'] == 'active' && strtotime($tutor['current_period_end']) >= time());
            $isEligible = ($tutor['tutor_status'] == 'active' && $tutor['is_verified'] == 1 && $tutor['is_active'] == 1 && $subscriptionValid);
            log_message('info', sprintf(
                'Tutor %d (%s %s): tutor_status=%s, is_verified=%s, is_active=%s, sub_status=%s, sub_valid=%s, ELIGIBLE=%s',
                $tutor['id'], $tutor['first_name'], $tutor['last_name'],
                $tutor['tutor_status'], $tutor['is_verified'], $tutor['is_active'],
                $tutor['sub_status'], $subscriptionValid ? 'YES' : 'NO',
                $isEligible ? 'YES' : 'NO'
            ));
        }

        return $results;
    }

    private function applyPriceFilterUsers($builder, $priceRange)
    {
        switch($priceRange) {
            case 'Under 10,000 MK':
                $builder->where('users.hourly_rate <', 10000);
                break;
            case '10,000-20,000 MK':
                $builder->where('users.hourly_rate >=', 10000)->where('users.hourly_rate <=', 20000);
                break;
            case '20,000-30,000 MK':
                $builder->where('users.hourly_rate >', 20000)->where('users.hourly_rate <=', 30000);
                break;
            case '30,000-40,000 MK':
                $builder->where('users.hourly_rate >', 30000)->where('users.hourly_rate <=', 40000);
                break;
            case '40,000+ MK':
                $builder->where('users.hourly_rate >', 40000);
                break;
        }
    }

    // Get most searched tutors (ONLY VERIFIED & ACTIVE TUTORS WITH VALID SUBSCRIPTIONS)
    public function getMostSearched($limit = 3)
    {
        log_message('info', '=== GET MOST SEARCHED DEBUG ===');

        try {
            // First check what tutors exist that meet basic criteria
            $allEligible = $this->select('users.id, users.search_count, users.first_name, users.last_name, users.featured')
                               ->join('tutor_subscriptions', 'tutor_subscriptions.user_id = users.id', 'left')
                               ->where('users.role', 'trainer')
                               ->where('users.tutor_status', 'active')
                               ->where('users.is_verified', 1)
                               ->where('users.is_active', 1)
                               ->where('tutor_subscriptions.status', 'active')
                               ->where('tutor_subscriptions.current_period_end >=', date('Y-m-d H:i:s'))
                               ->findAll();

            log_message('info', 'Total eligible tutors (active, verified, paid): ' . count($allEligible));
            foreach ($allEligible as $tutor) {
                log_message('info', sprintf('Eligible tutor %d: %s %s, search_count=%d, featured=%d',
                    $tutor['id'], $tutor['first_name'], $tutor['last_name'], $tutor['search_count'], $tutor['featured']));
            }

            $results = $this->select('users.*')
                           ->join('tutor_subscriptions', 'tutor_subscriptions.user_id = users.id', 'left')
                           ->where('users.role', 'trainer')
                           ->where('users.tutor_status', 'active')
                           ->where('users.is_verified', 1)
                           ->where('users.is_active', 1)
                           ->where('tutor_subscriptions.status', 'active')
                           ->where('tutor_subscriptions.current_period_end >=', date('Y-m-d H:i:s'))
                           ->where('users.search_count >', 0)
                           ->orderBy('users.search_count', 'DESC')
                           ->groupBy('users.id')
                           ->findAll($limit);

            log_message('info', 'Most searched results (search_count > 0): ' . count($results));
            return $results;
        } catch (\Exception $e) {
            log_message('error', 'Error in getMostSearched: ' . $e->getMessage());
            return [];
        }
    }

    // Get featured tutors (ONLY VERIFIED & ACTIVE TUTORS WITH VALID SUBSCRIPTIONS)
    public function getFeatured($limit = 4)
    {
        log_message('info', '=== GET FEATURED DEBUG ===');

        try {
            // Get top-rated tutors with reviews, ordered by rating and review count
            // Include subscription plan data for badge levels
            $results = $this->select('users.*, tutor_subscriptions.plan_id, subscription_plans.name as plan_name, subscription_plans.search_ranking, subscription_plans.badge_level, subscription_plans.max_subjects')
                           ->join('tutor_subscriptions', 'tutor_subscriptions.user_id = users.id', 'left')
                           ->join('subscription_plans', 'subscription_plans.id = tutor_subscriptions.plan_id', 'left')
                           ->where('users.role', 'trainer')
                           ->where('users.is_verified', 1)
                           ->where('users.is_active', 1)
                           ->where('users.rating >=', 4.0)  // Only tutors with 4+ rating
                           ->where('users.review_count >=', 1)  // At least 1 review
                           ->where('tutor_subscriptions.status', 'active')
                           ->where('tutor_subscriptions.current_period_end >=', date('Y-m-d H:i:s'))
                           ->orderBy('users.rating', 'DESC')
                           ->orderBy('users.review_count', 'DESC')
                           ->groupBy('users.id')
                           ->findAll($limit);

            log_message('info', 'Featured tutors (rating >= 4.0 with reviews): ' . count($results));
            foreach ($results as $tutor) {
                log_message('info', sprintf('Featured tutor %d: %s %s, rating=%.1f, reviews=%d, badge_level=%s',
                    $tutor['id'], $tutor['first_name'], $tutor['last_name'], $tutor['rating'], $tutor['review_count'], $tutor['badge_level'] ?? 'none'));
            }

            return $results;
        } catch (\Exception $e) {
            log_message('error', 'Error in getFeatured: ' . $e->getMessage());
            return [];
        }
    }

    // Increment search count
    public function incrementSearchCount($tutorId)
    {
        $this->set('search_count', 'search_count + 1', false)
             ->update($tutorId);
    }
}
