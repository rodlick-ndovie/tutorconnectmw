<?php

namespace App\Controllers;

use App\Models\NoticeModel;
use CodeIgniter\Controller;

class Notice extends Controller
{
    protected $noticeModel;

    public function __construct()
    {
        $this->noticeModel = new NoticeModel();
        helper(['form', 'url']);
    }

    /**
     * Display all approved notices
     */
    public function index()
    {
        // Get filter from query string
        $type = $this->request->getGet('type');

        // Get filtered or all notices
        if ($type) {
            $notices = $this->noticeModel->getNoticesByType($type);
        } else {
            $notices = $this->noticeModel->getApprovedNotices();
        }

        $data = [
            'title' => 'School Notices & Announcements',
            'notices' => $notices,
            'currentType' => $type
        ];

        return view('notice/index', $data);
    }

    /**
     * Show form to create new notice (public)
     */
    public function create()
    {
        $data = [
            'title' => 'Post a Notice',
            'validation' => \Config\Services::validation()
        ];

        return view('notice/create', $data);
    }

    /**
     * Show trainer notice management dashboard
     */
    public function trainerIndex()
    {
        // Check if user is logged in and is a trainer
        if (!session()->get('user_id') || session()->get('role') !== 'trainer') {
            return redirect()->to('/login')->with('error', 'Access denied');
        }

        $userId = session()->get('user_id');

        // Check if trainer has announcement permission
        $subscriptionModel = new \App\Models\TutorSubscriptionModel();
        $subscription = $subscriptionModel->getSubscriptionWithPlan($userId);

        if (!$subscription || !$subscription['allow_announcements']) {
            return redirect()->to('/trainer/dashboard')
                ->with('error', 'Your current plan does not include announcement access. Please upgrade your plan.');
        }

        // Get trainer's notices
        $notices = $this->noticeModel->getNoticesByUser($userId);

        $data = [
            'title' => 'School Announcements - Management',
            'notices' => $notices,
            'subscription' => $subscription
        ];

        return view('trainer/notices/index', $data);
    }

    /**
     * Show form to create new notice for trainers
     */
    public function trainerCreate()
    {
        // Check if user is logged in and is a trainer
        if (!session()->get('user_id') || session()->get('role') !== 'trainer') {
            return redirect()->to('/login')->with('error', 'Access denied');
        }

        $userId = session()->get('user_id');

        // Check if trainer has announcement permission
        $subscriptionModel = new \App\Models\TutorSubscriptionModel();
        $subscription = $subscriptionModel->getSubscriptionWithPlan($userId);

        if (!$subscription || !$subscription['allow_announcements']) {
            return redirect()->to('/trainer/dashboard')
                ->with('error', 'Your current plan does not include announcement access. Please upgrade your plan.');
        }

        $data = [
            'title' => 'Create School Announcement',
            'validation' => \Config\Services::validation(),
            'subscription' => $subscription
        ];

        return view('trainer/notices/create', $data);
    }

    /**
     * Store trainer's notice (auto-approved)
     */
    public function trainerStore()
    {
        // Debug: Log that method was called
        log_message('info', 'trainerStore method called');

        // Check if user is logged in and is a trainer
        if (!session()->get('user_id') || session()->get('role') !== 'trainer') {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied']);
        }

        $userId = session()->get('user_id');

        // Check if trainer has announcement permission
        $subscriptionModel = new \App\Models\TutorSubscriptionModel();
        $subscription = $subscriptionModel->getSubscriptionWithPlan($userId);

        if (!$subscription || !$subscription['allow_announcements']) {
            return $this->response->setJSON(['success' => false, 'message' => 'Your current plan does not include announcement access']);
        }

        // Debug: Log form submission
        log_message('info', 'Notice creation form submitted - User ID: ' . $userId . ', POST data: ' . json_encode($this->request->getPost()));

        // Get user info first (needed for validation)
        $userModel = new \App\Models\User();
        $user = $userModel->find($userId);

        if (!$user) {
            log_message('error', 'User not found - User ID: ' . $userId);
            return redirect()->back()->with('error', 'User profile not found. Please try again.');
        }

        // Create custom validation rules for trainer forms (phone and email are optional since they come from user profile)
        $trainerValidationRules = [
            'school_name' => 'required|min_length[3]|max_length[255]',
            'school_type' => 'required|max_length[100]',
            'notice_type' => 'required|in_list[Vacancy,Notice,Announcement]',
            'notice_title' => 'required|min_length[5]|max_length[255]',
            'notice_content' => 'required|min_length[20]'
        ];

        // Validate input
        $validation = \Config\Services::validation();

        if (!$this->validate($trainerValidationRules)) {
            log_message('error', 'Notice validation failed - User ID: ' . $userId . ', Errors: ' . json_encode($this->validator->getErrors()));
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // User already loaded above for validation

        // Prepare data - auto-approved for premium users
        $data = [
            'school_name' => $this->request->getPost('school_name'),
            'school_type' => $this->request->getPost('school_type'),
            'phone' => $user['phone'] ?? $this->request->getPost('phone'),
            'email' => $user['email'],
            'notice_type' => $this->request->getPost('notice_type'),
            'notice_title' => $this->request->getPost('notice_title'),
            'notice_content' => $this->request->getPost('notice_content'),
            'attached_image' => null, // No image upload for premium users
            'status' => 'approved', // Auto-approved for premium users
            'approved_by' => $userId, // Self-approved
            'approved_at' => date('Y-m-d H:i:s'),
            'created_by_user' => $userId // Track who created it
        ];

        // Save to database
        if ($this->noticeModel->insert($data)) {
            // Log successful notice creation
            log_message('info', 'School announcement created successfully - User ID: ' . $userId . ', Title: ' . $data['notice_title'] . ', School: ' . $data['school_name']);

            return redirect()->to('trainer/notices')
                           ->with('message', 'School announcement created successfully!');
        } else {
            // Log failed notice creation
            log_message('error', 'Failed to create school announcement - User ID: ' . $userId . ', Title: ' . $data['notice_title']);

            return redirect()->back()->withInput()
                           ->with('error', 'Failed to create announcement. Please try again.');
        }
    }

    /**
     * Edit trainer's notice
     */
    public function trainerEdit($id)
    {
        // Check if user is logged in and is a trainer
        if (!session()->get('user_id') || session()->get('role') !== 'trainer') {
            return redirect()->to('/login')->with('error', 'Access denied');
        }

        $userId = session()->get('user_id');

        // Get notice and check ownership
        $notice = $this->noticeModel->find($id);

        if (!$notice || $notice['created_by_user'] != $userId) {
            return redirect()->to('/trainer/notices')
                ->with('error', 'Notice not found or access denied');
        }

        $data = [
            'title' => 'Edit School Announcement',
            'notice' => $notice,
            'validation' => \Config\Services::validation()
        ];

        return view('trainer/notices/edit', $data);
    }

    /**
     * Update trainer's notice
     */
    public function trainerUpdate($id)
    {
        // Check if user is logged in and is a trainer
        if (!session()->get('user_id') || session()->get('role') !== 'trainer') {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied']);
        }

        $userId = session()->get('user_id');

        // Get notice and check ownership
        $notice = $this->noticeModel->find($id);

        if (!$notice || $notice['created_by_user'] != $userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Notice not found or access denied']);
        }

        // Create custom validation rules for trainer forms (phone and email are optional since they come from user profile)
        $trainerValidationRules = [
            'school_name' => 'required|min_length[3]|max_length[255]',
            'school_type' => 'required|max_length[100]',
            'notice_type' => 'required|in_list[Vacancy,Notice,Announcement]',
            'notice_title' => 'required|min_length[5]|max_length[255]',
            'notice_content' => 'required|min_length[20]'
        ];

        // Validate input
        $validation = \Config\Services::validation();

        if (!$this->validate($trainerValidationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Prepare data
        $data = [
            'school_name' => $this->request->getPost('school_name'),
            'school_type' => $this->request->getPost('school_type'),
            'notice_type' => $this->request->getPost('notice_type'),
            'notice_title' => $this->request->getPost('notice_title'),
            'notice_content' => $this->request->getPost('notice_content')
        ];
        // No image upload handling for premium users

        // Update notice
        if ($this->noticeModel->update($id, $data)) {
            // Log successful notice update
            log_message('info', 'School announcement updated successfully - User ID: ' . $userId . ', Notice ID: ' . $id . ', Title: ' . $data['notice_title']);

            return redirect()->to('trainer/notices')
                           ->with('message', 'School announcement updated successfully!');
        } else {
            // Log failed notice update
            log_message('error', 'Failed to update school announcement - User ID: ' . $userId . ', Notice ID: ' . $id);

            return redirect()->back()->withInput()
                           ->with('error', 'Failed to update announcement. Please try again.');
        }
    }

    /**
     * Delete trainer's notice
     */
    public function trainerDelete($id)
    {
        // Check if user is logged in and is a trainer
        if (!session()->get('user_id') || session()->get('role') !== 'trainer') {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied']);
        }

        $userId = session()->get('user_id');

        // Get notice and check ownership
        $notice = $this->noticeModel->find($id);

        if (!$notice || $notice['created_by_user'] != $userId) {
            return redirect()->to('/trainer/notices')
                ->with('error', 'Notice not found or access denied');
        }

        // No image deletion needed for premium users

        // Delete notice
        if ($this->noticeModel->delete($id)) {
            // Log successful notice deletion
            log_message('info', 'School announcement deleted successfully - User ID: ' . $userId . ', Notice ID: ' . $id . ', Title: ' . $notice['notice_title']);

            return redirect()->to('trainer/notices')
                           ->with('message', 'School announcement deleted successfully!');
        } else {
            // Log failed notice deletion
            log_message('error', 'Failed to delete school announcement - User ID: ' . $userId . ', Notice ID: ' . $id);

            return redirect()->back()->withInput()
                           ->with('error', 'Failed to delete announcement. Please try again.');
        }
    }

    /**
     * Store submitted notice
     */
    public function store()
    {
        // Honeypot protection - check if bot field is filled
        if (!empty($this->request->getPost('website'))) {
            // Silently fail for bots
            return redirect()->to('notice/success');
        }

        // Validate input
        $validation = \Config\Services::validation();

        if (!$this->validate($this->noticeModel->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Prepare data
        $data = [
            'school_name' => $this->request->getPost('school_name'),
            'school_type' => $this->request->getPost('school_type'),
            'phone' => $this->request->getPost('phone'),
            'email' => $this->request->getPost('email'),
            'notice_type' => $this->request->getPost('notice_type'),
            'notice_title' => $this->request->getPost('notice_title'),
            'notice_content' => $this->request->getPost('notice_content'),
            'attached_image' => null,
            'status' => 'pending'
        ];

        // Save to database
        if ($this->noticeModel->insert($data)) {
            // Log successful public notice submission
            log_message('info', 'Public notice submitted successfully - Title: ' . $data['notice_title'] . ', School: ' . $data['school_name'] . ', Email: ' . $data['email']);

            return redirect()->to('notice/success')
                           ->with('message', 'Your notice has been submitted successfully and is pending approval.');
        } else {
            // Log failed public notice submission
            log_message('error', 'Failed to submit public notice - Title: ' . $data['notice_title'] . ', Email: ' . $data['email']);

            return redirect()->back()->withInput()
                           ->with('error', 'Failed to submit notice. Please try again.');
        }
    }

    /**
     * Success page after submission
     */
    public function success()
    {
        $data = [
            'title' => 'Notice Submitted Successfully'
        ];

        return view('notice/success', $data);
    }

    /**
     * View single notice details
     */
    public function view($id)
    {
        $notice = $this->noticeModel->find($id);

        if (!$notice || $notice['status'] !== 'approved') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Increment view count
        $this->noticeModel->incrementViews($id);

        $data = [
            'title' => $notice['notice_title'],
            'notice' => $notice
        ];

        return view('notice/view', $data);
    }

    /**
     * Admin: List all notices (redirect for backward compatibility)
     */
    public function adminPending()
    {
        return redirect()->to('notice/admin/all?status=pending');
    }

    /**
     * Admin: List all notices
     */
    public function adminAll()
    {
        // Check admin authentication
        if (session()->get('role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Unauthorized access');
        }

        $status = $this->request->getGet('status') ?? 'all';

        if ($status === 'all') {
            $notices = $this->noticeModel->orderBy('created_at', 'DESC')->findAll();
        } else {
            $notices = $this->noticeModel->where('status', $status)->orderBy('created_at', 'DESC')->findAll();
        }

        $data = [
            'title' => 'School Notices - Admin',
            'active_page' => 'notices',
            'notices' => $notices,
            'statistics' => $this->noticeModel->getStatistics(),
            'current_status' => $status
        ];

        return view('notice/admin_all', $data);
    }

    /**
     * Admin: Approve a notice
     */
    public function approve($id)
    {
        // Check admin authentication
        if (session()->get('role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Unauthorized access');
        }

        $adminId = session()->get('id');

        if ($this->noticeModel->approveNotice($id, $adminId)) {
            return redirect()->back()->with('message', 'Notice approved successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to approve notice');
        }
    }

    /**
     * Admin: Reject a notice
     */
    public function reject($id)
    {
        // Check admin authentication
        if (session()->get('role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Unauthorized access');
        }

        $reason = $this->request->getPost('rejection_reason');

        if ($this->noticeModel->rejectNotice($id, $reason)) {
            return redirect()->back()->with('message', 'Notice rejected');
        } else {
            return redirect()->back()->with('error', 'Failed to reject notice');
        }
    }

    /**
     * Admin: Delete a notice
     */
    public function delete($id)
    {
        // Check admin authentication
        if (session()->get('role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Unauthorized access');
        }

        if ($this->noticeModel->delete($id)) {
            return redirect()->back()->with('message', 'Notice deleted successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to delete notice');
        }
    }
}
