<?php

namespace App\Controllers;

use App\Models\ResourceModel;

class Resources extends BaseController
{
    protected $pastPapersModel;
    protected $tutorVideosModel;
    protected $resourceModel;

    public function __construct()
    {
        helper(['form', 'url']);
        $this->pastPapersModel = new \App\Models\PastPapersModel();
        $this->tutorVideosModel = new \App\Models\TutorVideosModel();
        $this->resourceModel = new ResourceModel();
    }

    // Unified Resources Page with filtering
    public function index()
    {
        $resourceType = $this->request->getGet('type') ?? 'all'; // 'all', 'papers', 'videos'

        $data = [
            'title' => 'Resources - TutorConnect Malawi',
            'resource_type' => $resourceType,
        ];

        // Get filter parameters
        $filters = [
            'exam_body' => $this->request->getGet('exam_body'),
            'exam_level' => $this->request->getGet('exam_level'),
            'subject' => $this->request->getGet('subject'),
            'year' => $this->request->getGet('year'),
            'search' => $this->request->getGet('search'),
        ];

        // Get data based on resource type
        if ($resourceType === 'papers' || $resourceType === 'all') {
            $data['papers'] = $this->pastPapersModel->getFilteredPapersWithUploader($filters);
        }

        if ($resourceType === 'videos' || $resourceType === 'all') {
            $data['videos'] = $this->tutorVideosModel->getApprovedVideos($filters);
            $data['featured_videos'] = $this->tutorVideosModel->getFeaturedVideos();
        }

        // Get filter options for both resources
        $data['filters'] = $filters;
        $data['filter_options'] = array_merge(
            $this->pastPapersModel->getFilterOptions(),
            $this->tutorVideosModel->getFilterOptions()
        );

        return view('resources/index', $data);
    }

    // Past Papers Page
    public function pastPapers()
    {
        $filters = [
            'exam_body' => $this->request->getGet('exam_body'),
            'exam_level' => $this->request->getGet('exam_level'),
            'subject' => $this->request->getGet('subject'),
            'year' => $this->request->getGet('year'),
            'search' => $this->request->getGet('search'),
        ];

        $data = [
            'title' => 'Past Papers - TutorConnect Malawi',
            'papers' => $this->pastPapersModel->getFilteredPapersWithUploader($filters),
            'filters' => $filters,
            'filter_options' => $this->pastPapersModel->getFilterOptions()
        ];

        return view('resources/past_papers', $data);
    }

    // Video Solutions Page
    public function videoSolutions()
    {
        $filters = [
            'exam_body' => $this->request->getGet('exam_body'),
            'subject' => $this->request->getGet('subject'),
            'search' => $this->request->getGet('search'),
        ];

        $data = [
            'title' => 'Video Solutions - TutorConnect Malawi',
            'videos' => $this->tutorVideosModel->getApprovedVideos($filters),
            'featured_videos' => $this->tutorVideosModel->getFeaturedVideos(),
            'filters' => $filters,
            'filter_options' => $this->tutorVideosModel->getFilterOptions()
        ];

        return view('resources/video_solutions', $data);
    }

    // Individual Video View
    public function viewVideo($videoId)
    {
        $video = $this->tutorVideosModel->select('tutor_videos.*, users.first_name, users.last_name, users.email, users.role')
                                       ->join('users', 'users.id = tutor_videos.tutor_id')
                                       ->where('tutor_videos.id', $videoId)
                                       ->where('tutor_videos.status', 'approved')
                                       ->first();

        if (!$video) {
            return redirect()->to('/resources')->with('error', 'Video not found.');
        }

        // Increment view count
        $this->tutorVideosModel->incrementViewCount($videoId);

        // Get tutor's other videos
        $tutorVideos = $this->tutorVideosModel->getVideosByTutor($video['tutor_id']);
        $otherVideos = array_filter($tutorVideos, function($v) use ($videoId) {
            return $v['id'] != $videoId;
        });

        // Determine if video was added by admin or tutor
        $addedByAdmin = in_array($video['role'] ?? '', ['admin', 'sub-admin'], true);

        // Check if current user is logged in as admin or trainer
        $userRole = session()->get('role');
        $isStaff = !empty($userRole) && in_array($userRole, ['admin', 'sub-admin', 'trainer'], true);

        $data = [
            'title' => $video['title'] . ' - Resources',
            'video' => $video,
            'other_videos' => array_slice($otherVideos, 0, 4),
            'isStaff' => $isStaff,
            'addedByAdmin' => $addedByAdmin,
        ];

        return view('resources/view_video', $data);
    }

    // Download past paper
    public function downloadPaper($paperId)
    {
        $paper = $this->pastPapersModel->find($paperId);

        if (!$paper || !$paper['is_active']) {
            return redirect()->to('/resources')->with('error', 'Paper not found or unavailable.');
        }

        // Prevent duplicate counts using session tracking
        $session = session();
        $lastDownload = $session->get('last_download_' . $paperId);
        $now = time();

        // Only increment if this paper wasn't downloaded in the last 10 seconds
        if (!$lastDownload || ($now - $lastDownload) > 10) {
            $this->pastPapersModel->incrementDownloadCount($paperId);
            $session->set('last_download_' . $paperId, $now);
        }

        // Normalize file paths from both legacy (writable) and public storage
        $fileUrl = $paper['file_url'] ?? '';
        $localPath = '';

        if (str_contains($fileUrl, 'writable/uploads/past_papers/')) {
            $relative = str_replace(base_url() . '/', '', $fileUrl);
            $relative = str_replace('writable/', '', $relative);
            $localPath = WRITEPATH . str_replace(['..', '//'], ['', '/'], $relative);
        } elseif (str_contains($fileUrl, '/uploads/past_papers/')) {
            $relative = ltrim(parse_url($fileUrl, PHP_URL_PATH), '/');
            $localPath = FCPATH . $relative;
        }

        if ($localPath && file_exists($localPath)) {
            $downloadName = basename($localPath);
            return $this->response->download($localPath, null)->setFileName($downloadName);
        }

        // Fallback to redirect if direct file not found locally
        return redirect()->to($fileUrl ?: '/resources')->with('error', 'File missing on server.');
    }

    // Upload Past Papers (for tutors)
    public function upload()
    {
        // Check if user is logged in and is a trainer
        $userId = session()->get('user_id');
        if (!$userId || session()->get('role') !== 'trainer') {
            return redirect()->to('/login')->with('error', 'Access denied.');
        }

        // Check subscription for PDF upload capability
        $subscriptionModel = new \App\Models\TutorSubscriptionModel();
        $subscription = $subscriptionModel->getSubscriptionWithPlan($userId);

        if (!$subscription || ($subscription['allow_pdf_upload'] ?? 0) == 0) {
            return redirect()->to('trainer/subscription')->with('error', 'You need a subscription that allows PDF uploads.');
        }

        $data = [
            'title' => 'Upload Past Papers - TutorConnect Malawi',
        ];

        // Load curriculum subjects for dropdowns
        $curriculumSubjectsModel = new \App\Models\CurriculumSubjectsModel();
        $curricula = $curriculumSubjectsModel->getCurricula();
        $groupedSubjects = $curriculumSubjectsModel->getSubjectNamesGrouped();

        // Format curriculum options
        $curriculumLabels = [
            'MANEB' => 'MANEB (Malawi National Curriculum)',
            'GCSE' => 'GCSE (General Certificate of Secondary Education)',
            'Cambridge' => 'Cambridge (Cambridge International Curriculum)',
        ];

        $availableCurricula = [];
        foreach ($curricula as $row) {
            $key = $row['curriculum'];
            $availableCurricula[$key] = $curriculumLabels[$key] ?? $key;
        }

        // Get levels by curriculum
        $levelsByCurriculum = [];
        foreach ($groupedSubjects as $curriculumKey => $levels) {
            $levelsByCurriculum[$curriculumKey] = array_keys($levels);
        }

        $data['available_curricula'] = $availableCurricula;
        $data['curriculum_subjects'] = $groupedSubjects;
        $data['levels_by_curriculum'] = $levelsByCurriculum;

        return view('trainer/upload_past_papers', $data);
    }

    // Process Past Paper Upload
    public function processUpload()
    {
        // Check authentication and subscription
        $userId = session()->get('user_id');
        if (!$userId || session()->get('role') !== 'trainer') {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied.']);
        }

        $subscriptionModel = new \App\Models\TutorSubscriptionModel();
        $subscription = $subscriptionModel->getSubscriptionWithPlan($userId);

        if (!$subscription || ($subscription['allow_pdf_upload'] ?? 0) == 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Subscription required for PDF uploads.']);
        }

        // Validate form data
        $rules = [
            'exam_body' => 'required|in_list[MANEB,Cambridge,GCSE]',
            'exam_level' => 'required|max_length[50]',
            'subject' => 'required|max_length[100]',
            'year' => 'required|integer|greater_than[2000]|less_than_equal_to[' . (date('Y') + 1) . ']',
            'paper_title' => 'permit_empty|max_length[255]',
            'paper_code' => 'permit_empty|max_length[50]',
            'copyright_notice' => 'permit_empty|max_length[1000]',
            'pdf_file' => 'uploaded[pdf_file]|max_size[pdf_file,51200]|ext_in[pdf_file,pdf]|mime_in[pdf_file,application/pdf]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $this->validator->getErrors()
            ]);
        }

        // Handle file upload
        $file = $this->request->getFile('pdf_file');
        if (!$file->isValid()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid file upload.']);
        }

        // Create upload directory if it doesn't exist
        $uploadPath = FCPATH . 'uploads/past_papers/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Generate unique filename
        $extension = $file->getExtension();
        $newName = time() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;

        // Move file to upload directory
        if (!$file->move($uploadPath, $newName)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to save file.']);
        }

        // Get file size
        $fileSize = $file->getSize();
        $fileSizeFormatted = $this->formatFileSize($fileSize);

        // Prepare data for database
        $paperData = [
            'exam_body' => $this->request->getPost('exam_body'),
            'exam_level' => $this->request->getPost('exam_level'),
            'subject' => $this->request->getPost('subject'),
            'year' => (int) $this->request->getPost('year'),
            'paper_title' => $this->request->getPost('paper_title') ?: null,
            'paper_code' => $this->request->getPost('paper_code') ?: null,
            'file_url' => 'uploads/past_papers/' . $newName,
            'file_size' => $fileSizeFormatted,
            'copyright_notice' => $this->request->getPost('copyright_notice') ?: null,
            'is_active' => 1,
            'uploaded_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Save to database
        if ($this->pastPapersModel->insert($paperData)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Past paper uploaded successfully!',
                'redirect' => base_url('resources')
            ]);
        }

        // Clean up uploaded file if database insert failed
        if (file_exists($uploadPath . $newName)) {
            unlink($uploadPath . $newName);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Failed to save paper details.']);
    }

    // Helper method to format file size
    private function formatFileSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    // Track contact clicks from video pages (similar to profile views)
    public function trackContactClick()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $tutorId = $this->request->getPost('tutor_id');
        $contactType = $this->request->getPost('contact_type') ?: 'unknown';
        $referenceType = $this->request->getPost('reference_type') ?: 'video';
        $referenceId = $this->request->getPost('reference_id');

        // Validate required fields
        if (!$tutorId || !is_numeric($tutorId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid tutor ID'
            ]);
        }

        try {
            // Get visitor IP for tracking unique clicks
            $visitorIp = $this->request->getIPAddress();

            // Prepare metadata for tracking
            $metadata = [
                'visitor_ip' => $visitorIp,
                'contact_type' => $contactType,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'user_agent' => $this->request->getUserAgent()->getAgentString(),
                'timestamp' => date('Y-m-d H:i:s')
            ];

            // Record the contact click using UsageTrackingModel
            $usageTrackingModel = new \App\Models\UsageTrackingModel();
            log_message('info', 'Resources trackContactClick - About to record usage:');
            log_message('info', '  tutorId: ' . $tutorId);
            log_message('info', '  metricType: contact_clicks');
            log_message('info', '  value: 1');
            log_message('info', '  referenceId: ' . $referenceId);
            log_message('info', '  metadata keys: ' . implode(', ', array_keys($metadata)));

            $result = $usageTrackingModel->recordUsage(
                $tutorId,
                'contact_clicks', // metric_type - matches ENUM in database
                1, // metric_value
                $referenceId, // reference_id (video ID)
                $metadata
            );

            log_message('info', 'Resources trackContactClick - recordUsage result: ' . ($result ? 'SUCCESS (ID: ' . $result . ')' : 'FAILED'));

            if ($result) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Contact click tracked successfully'
                ]);
            } else {
                log_message('error', 'Failed to record contact click for tutor ' . $tutorId . ', contact type: ' . $contactType);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to track contact click'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Exception in trackContactClick: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error occurred'
            ]);
        }
    }
}
