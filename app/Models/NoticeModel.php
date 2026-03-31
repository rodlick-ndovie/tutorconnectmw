<?php

namespace App\Models;

use CodeIgniter\Model;

class NoticeModel extends Model
{
    protected $table = 'notices';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'school_name',
        'school_type',
        'phone',
        'email',
        'notice_type',
        'notice_title',
        'notice_content',
        'attached_image',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'views_count',
        'created_by_user'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'school_name' => 'required|min_length[3]|max_length[255]',
        'school_type' => 'required|max_length[100]',
        'phone' => 'required|min_length[9]|max_length[20]',
        'email' => 'required|valid_email|max_length[255]',
        'notice_type' => 'required|in_list[Vacancy,Notice,Announcement]',
        'notice_title' => 'required|min_length[5]|max_length[255]',
        'notice_content' => 'required|min_length[20]'
    ];

    protected $validationMessages = [
        'school_name' => [
            'required' => 'School name is required',
            'min_length' => 'School name must be at least 3 characters'
        ],
        'email' => [
            'required' => 'Email address is required',
            'valid_email' => 'Please provide a valid email address'
        ],
        'notice_content' => [
            'required' => 'Notice content is required',
            'min_length' => 'Notice content must be at least 20 characters'
        ]
    ];

    /**
     * Get all approved notices for public display
     */
    public function getApprovedNotices($limit = null)
    {
        $builder = $this->where('status', 'approved')
                        ->orderBy('approved_at', 'DESC');

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->findAll();
    }

    /**
     * Get recent notices for scrolling display
     */
    public function getRecentNotices($limit = 10)
    {
        return $this->where('status', 'approved')
                    ->orderBy('approved_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get pending notices for admin approval
     */
    public function getPendingNotices()
    {
        return $this->where('status', 'pending')
                    ->orderBy('created_at', 'ASC')
                    ->findAll();
    }

    /**
     * Get notices by type
     */
    public function getNoticesByType($type, $limit = null)
    {
        $builder = $this->where('status', 'approved')
                        ->where('notice_type', $type)
                        ->orderBy('approved_at', 'DESC');

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->findAll();
    }

    /**
     * Approve a notice
     */
    public function approveNotice($noticeId, $adminId)
    {
        return $this->update($noticeId, [
            'status' => 'approved',
            'approved_by' => $adminId,
            'approved_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Reject a notice
     */
    public function rejectNotice($noticeId, $reason = null)
    {
        return $this->update($noticeId, [
            'status' => 'rejected',
            'rejection_reason' => $reason
        ]);
    }

    /**
     * Increment view count
     */
    public function incrementViews($noticeId)
    {
        $this->set('views_count', 'views_count + 1', false)
             ->where('id', $noticeId)
             ->update();
    }

    /**
     * Get notices created by a specific user (trainer)
     */
    public function getNoticesByUser($userId)
    {
        return $this->where('created_by_user', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get statistics
     */
    public function getStatistics()
    {
        return [
            'total' => $this->countAll(),
            'approved' => $this->where('status', 'approved')->countAllResults(),
            'pending' => $this->where('status', 'pending')->countAllResults(),
            'rejected' => $this->where('status', 'rejected')->countAllResults()
        ];
    }
}
