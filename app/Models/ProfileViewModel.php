<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfileViewModel extends Model
{
    protected $table            = 'profile_views';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields    = [
        'user_id',
        'visitor_ip',
        'viewed_at'
    ];

    protected $useTimestamps = false;

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

    /**
     * Record a profile view from a visitor IP
     * Prevents duplicate counts from same IP within 24 hours
     */
    public function recordView($userId, $visitorIp)
    {
        // Check if this IP has already viewed this profile today
        $today = date('Y-m-d 00:00:00');
        $existingView = $this->where('user_id', $userId)
                            ->where('visitor_ip', $visitorIp)
                            ->where('viewed_at >=', $today)
                            ->first();

        // Only record if not already viewed today
        if (!$existingView) {
            $this->insert([
                'user_id' => $userId,
                'visitor_ip' => $visitorIp,
                'viewed_at' => date('Y-m-d H:i:s')
            ]);
            return true;
        }
        return false;
    }

    /**
     * Get the total number of unique views for a tutor
     */
    public function getViewCount($userId)
    {
        return $this->where('user_id', $userId)
                   ->countAllResults();
    }

    /**
     * Get views by IP and user (for analytics)
     */
    public function getViewsByIp($userId)
    {
        return $this->select('visitor_ip, COUNT(*) as view_count, MAX(viewed_at) as last_viewed')
                   ->where('user_id', $userId)
                   ->groupBy('visitor_ip')
                   ->orderBy('last_viewed', 'DESC')
                   ->get()
                   ->getResultArray();
    }
}
