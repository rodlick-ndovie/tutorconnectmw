<?php

namespace App\Models;

use CodeIgniter\Model;

class TutorVideosModel extends Model
{
    protected $table            = 'tutor_videos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'tutor_id', 'title', 'description', 'video_embed_code', 'video_platform',
        'video_id', 'exam_body', 'subject', 'topic', 'problem_year', 'status',
        'featured_level', 'view_count', 'submitted_at', 'approved_at', 'rejection_reason'
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

    // Custom timestamp field
    protected $submittedAt = 'submitted_at';

    // Validation
    protected $validationRules      = [
        'tutor_id' => 'required|integer',
        'title' => 'required|max_length[255]',
        'description' => 'permit_empty',
        'video_embed_code' => 'required',
        'video_platform' => 'required|in_list[youtube,vimeo]',
        'video_id' => 'required|max_length[100]',
        'exam_body' => 'permit_empty|max_length[100]',
        'subject' => 'permit_empty|max_length[100]',
        'topic' => 'permit_empty|max_length[200]',
        'problem_year' => 'permit_empty|integer',
        'status' => 'permit_empty|in_list[pending_review,approved,rejected]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['setSubmittedAt'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    // Set submitted_at timestamp
    protected function setSubmittedAt(array $data)
    {
        if (!isset($data['data']['submitted_at'])) {
            $data['data']['submitted_at'] = date('Y-m-d H:i:s');
        }
        return $data;
    }

    // Get videos for admin review
    public function getPendingVideos()
    {
        return $this->select('tutor_videos.*, users.first_name, users.last_name, users.email')
                   ->join('users', 'users.id = tutor_videos.tutor_id')
                   ->where('tutor_videos.status', 'pending_review')
                   ->orderBy('tutor_videos.submitted_at', 'ASC')
                   ->findAll();
    }

    // Get approved videos for public display
    public function getApprovedVideos($filters = [])
    {
        $builder = $this->select('tutor_videos.*, users.first_name, users.last_name')
                       ->join('users', 'users.id = tutor_videos.tutor_id')
                       ->where('tutor_videos.status', 'approved');

        if (!empty($filters['subject'])) {
            $builder->where('tutor_videos.subject', $filters['subject']);
        }

        if (!empty($filters['exam_body'])) {
            $builder->where('tutor_videos.exam_body', $filters['exam_body']);
        }

        return $builder->orderBy('tutor_videos.featured_level', 'DESC')
                      ->orderBy('tutor_videos.view_count', 'DESC')
                      ->orderBy('tutor_videos.created_at', 'DESC')
                      ->findAll();
    }

    // Get featured videos for carousel
    public function getFeaturedVideos()
    {
        return $this->select('tutor_videos.*, users.first_name, users.last_name')
                   ->join('users', 'users.id = tutor_videos.tutor_id')
                   ->where('tutor_videos.status', 'approved')
                   ->where('tutor_videos.featured_level', 'premium_featured')
                   ->orderBy('tutor_videos.view_count', 'DESC')
                   ->findAll();
    }

    // Get videos by tutor
    public function getVideosByTutor($tutorId)
    {
        return $this->where('tutor_id', $tutorId)
                   ->where('status', 'approved')
                   ->orderBy('created_at', 'DESC')
                   ->findAll();
    }

    // Increment view count
    public function incrementViewCount($videoId)
    {
        return $this->where('id', $videoId)->set('view_count', 'view_count + 1', false)->update();
    }

    // Approve video
    public function approveVideo($videoId, $featuredLevel = 'standard')
    {
        return $this->update($videoId, [
            'status' => 'approved',
            'featured_level' => $featuredLevel,
            'approved_at' => date('Y-m-d H:i:s')
        ]);
    }

    // Reject video
    public function rejectVideo($videoId)
    {
        return $this->update($videoId, [
            'status' => 'rejected'
        ]);
    }

    // Get filter options
    public function getFilterOptions()
    {
        return [
            'subjects' => $this->distinct()->select('subject')->where('status', 'approved')->findAll(),
            'exam_bodies' => $this->distinct()->select('exam_body')->where('status', 'approved')->findAll(),
        ];
    }

    // Extract video ID and generate embed code from URL
    public function processVideoUrl($url)
    {
        $videoData = [
            'platform' => null,
            'video_id' => null,
            'embed_code' => null
        ];

        // YouTube URL patterns
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]+)/', $url, $matches)) {
            $videoData['platform'] = 'youtube';
            $videoData['video_id'] = $matches[1];
            $videoData['embed_code'] = 'https://www.youtube.com/embed/' . $matches[1];
        }
        // Vimeo URL patterns
        elseif (preg_match('/vimeo\.com\/(\d+)/', $url, $matches)) {
            $videoData['platform'] = 'vimeo';
            $videoData['video_id'] = $matches[1];
            $videoData['embed_code'] = 'https://player.vimeo.com/video/' . $matches[1];
        }

        return $videoData;
    }
}
