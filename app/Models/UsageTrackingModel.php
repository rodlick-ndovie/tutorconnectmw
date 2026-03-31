<?php

namespace App\Models;

use CodeIgniter\Model;

class UsageTrackingModel extends Model
{
    protected $table            = 'usage_tracking';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields    = [
        'user_id',
        'metric_type', // 'profile_views', 'clicks', 'messages', 'video_uploads', 'pdf_uploads', 'announcements', 'reviews', 'subjects'
        'metric_value', // Usually 1 for increments
        'reference_id', // Optional: booking_id, message_id, etc.
        'metadata', // JSON field for additional data
        'tracked_at',
        'period_start', // Start of billing period
        'period_end' // End of billing period
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
     * Record usage for a specific metric
     */
    public function recordUsage($userId, $metricType, $value = 1, $referenceId = null, $metadata = null)
    {
        log_message('info', 'UsageTrackingModel recordUsage called:');
        log_message('info', '  userId: ' . $userId);
        log_message('info', '  metricType: ' . $metricType);
        log_message('info', '  value: ' . $value);
        log_message('info', '  referenceId: ' . $referenceId);
        log_message('info', '  metadata type: ' . gettype($metadata));

        if (is_array($metadata)) {
            log_message('info', '  metadata keys: ' . implode(', ', array_keys($metadata)));
        }

        // Get current billing period
        $period = $this->getCurrentBillingPeriod();

        $data = [
            'user_id' => $userId,
            'metric_type' => $metricType,
            'metric_value' => $value,
            'reference_id' => $referenceId,
            'metadata' => $metadata ? json_encode($metadata) : null,
            'tracked_at' => date('Y-m-d H:i:s'),
            'period_start' => $period['start'],
            'period_end' => $period['end']
        ];

        log_message('info', 'UsageTrackingModel about to insert data:');
        log_message('info', '  data[metric_type]: ' . $data['metric_type']);
        log_message('info', '  data[metric_value]: ' . $data['metric_value']);

        $result = $this->insert($data);

        log_message('info', 'UsageTrackingModel insert result: ' . ($result ? 'SUCCESS (ID: ' . $result . ')' : 'FAILED'));

        return $result;
    }

    /**
     * Get usage count for a user within current billing period
     * Counts unique IP addresses for profile_views and clicks, total for other metrics
     */
    public function getUsageCount($userId, $metricType, $periodStart = null, $periodEnd = null)
    {
        if (!$periodStart || !$periodEnd) {
            $period = $this->getCurrentBillingPeriod();
            $periodStart = $period['start'];
            $periodEnd = $period['end'];
        }

        // For profile_views and contact_clicks, count unique IP addresses
        if (in_array($metricType, ['profile_views', 'contact_clicks'])) {
            return $this->where('user_id', $userId)
                       ->where('metric_type', $metricType)
                       ->where('tracked_at >=', $periodStart)
                       ->where('tracked_at <=', $periodEnd)
                       ->select('JSON_UNQUOTE(JSON_EXTRACT(metadata, "$.visitor_ip")) as visitor_ip')
                       ->distinct()
                       ->countAllResults();
        }

        // For messages, use sum of values (total messages sent)
        if ($metricType === 'messages') {
            return $this->where('user_id', $userId)
                       ->where('metric_type', $metricType)
                       ->where('tracked_at >=', $periodStart)
                       ->where('tracked_at <=', $periodEnd)
                       ->selectSum('metric_value')
                       ->first()['metric_value'] ?? 0;
        }

        // For other metrics (reviews, uploads), use sum of values
        return $this->where('user_id', $userId)
                   ->where('metric_type', $metricType)
                   ->where('tracked_at >=', $periodStart)
                   ->where('tracked_at <=', $periodEnd)
                   ->selectSum('metric_value')
                   ->first()['metric_value'] ?? 0;
    }

    /**
     * Get detailed usage breakdown for a user
     */
    public function getUsageBreakdown($userId, $metricType = null, $periodStart = null, $periodEnd = null)
    {
        if (!$periodStart || !$periodEnd) {
            $period = $this->getCurrentBillingPeriod();
            $periodStart = $period['start'];
            $periodEnd = $period['end'];
        }

        $query = $this->where('user_id', $userId)
                     ->where('tracked_at >=', $periodStart)
                     ->where('tracked_at <=', $periodEnd);

        if ($metricType) {
            $query->where('metric_type', $metricType);
        }

        return $query->orderBy('tracked_at', 'DESC')
                    ->findAll();
    }

    /**
     * Check if user has exceeded their plan limit
     */
    public function hasExceededLimit($userId, $metricType, $limit)
    {
        if ($limit == 0) {
            return false; // 0 = unlimited
        }

        $currentUsage = $this->getUsageCount($userId, $metricType);
        return $currentUsage >= $limit;
    }

    /**
     * Get current billing period (monthly)
     */
    public function getCurrentBillingPeriod()
    {
        $now = time();
        $currentMonth = date('m', $now);
        $currentYear = date('Y', $now);

        $start = date('Y-m-01 00:00:00', $now); // First day of current month
        $end = date('Y-m-t 23:59:59', $now); // Last day of current month

        return [
            'start' => $start,
            'end' => $end
        ];
    }

    /**
     * Get usage statistics for dashboard
     * Matches subscription plan column names: max_profile_views, max_clicks, max_subjects, max_reviews, max_messages, max_video_uploads, max_pdf_uploads, max_announcements
     */
    public function getUsageStats($userId)
    {
        $period = $this->getCurrentBillingPeriod();

        $stats = [];

        // Get counts for each metric type (matches subscription plan columns)
        $metricTypes = [
            'profile_views',  // max_profile_views
            'contact_clicks', // max_clicks
            'subjects',       // max_subjects
            'reviews',        // max_reviews
            'messages',       // max_messages
            'video_uploads',  // max_video_uploads
            'pdf_uploads',    // max_pdf_uploads
            'announcements'   // max_announcements
        ];

        foreach ($metricTypes as $type) {
            $stats[$type] = [
                'current' => $this->getUsageCount($userId, $type, $period['start'], $period['end']),
                'period_start' => $period['start'],
                'period_end' => $period['end']
            ];
        }

        return $stats;
    }

    /**
     * Get usage statistics for a specific billing period
     * Used when subscription has custom billing periods (after plan changes)
     */
    public function getUsageStatsForPeriod($userId, $periodStart, $periodEnd)
    {
        $stats = [];

        // Get counts for each metric type (matches subscription plan columns)
        $metricTypes = [
            'profile_views',  // max_profile_views
            'contact_clicks', // max_clicks
            'subjects',       // max_subjects
            'reviews',        // max_reviews
            'messages',       // max_messages
            'video_uploads',  // max_video_uploads
            'pdf_uploads',    // max_pdf_uploads
            'announcements'   // max_announcements
        ];

        foreach ($metricTypes as $type) {
            $stats[$type] = [
                'current' => $this->getUsageCount($userId, $type, $periodStart, $periodEnd),
                'period_start' => $periodStart,
                'period_end' => $periodEnd
            ];
        }

        return $stats;
    }

    /**
     * Get total usage count across all users for a specific metric type
     */
    public function getTotalUsageCount($metricType, $periodStart = null, $periodEnd = null)
    {
        if (!$periodStart || !$periodEnd) {
            $period = $this->getCurrentBillingPeriod();
            $periodStart = $period['start'];
            $periodEnd = $period['end'];
        }

        // For profile_views and contact_clicks, count unique IP addresses
        if (in_array($metricType, ['profile_views', 'contact_clicks'])) {
            return $this->where('metric_type', $metricType)
                       ->where('tracked_at >=', $periodStart)
                       ->where('tracked_at <=', $periodEnd)
                       ->select('JSON_UNQUOTE(JSON_EXTRACT(metadata, "$.visitor_ip")) as visitor_ip')
                       ->distinct()
                       ->countAllResults();
        }

        // For messages, use sum of values (total messages sent)
        if ($metricType === 'messages') {
            return $this->where('metric_type', $metricType)
                       ->where('tracked_at >=', $periodStart)
                       ->where('tracked_at <=', $periodEnd)
                       ->selectSum('metric_value')
                       ->first()['metric_value'] ?? 0;
        }

        // For other metrics (reviews, uploads), use sum of values
        return $this->where('metric_type', $metricType)
                   ->where('tracked_at >=', $periodStart)
                   ->where('tracked_at <=', $periodEnd)
                   ->selectSum('metric_value')
                   ->first()['metric_value'] ?? 0;
    }

    /**
     * Clean up old usage data (keep last 12 months)
     */
    public function cleanupOldData($monthsToKeep = 12)
    {
        $cutoffDate = date('Y-m-d H:i:s', strtotime("-{$monthsToKeep} months"));

        return $this->where('tracked_at <', $cutoffDate)
                   ->delete();
    }
}
