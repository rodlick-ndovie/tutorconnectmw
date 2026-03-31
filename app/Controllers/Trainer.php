<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\TutorModel;
use App\Models\CurriculumSubjectsModel;

class Trainer extends BaseController
{
    protected $userModel;
    protected $tutorModel;
    protected $subscriptionPlanModel;
    protected $tutorSubscriptionModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->tutorModel = new TutorModel();
        helper(['form', 'url']);

        // Profile photo upload constants
        if (!defined('PROFILE_UPLOAD_PATH')) {
            define('PROFILE_UPLOAD_PATH', ROOTPATH . 'public/uploads/profile_photos/');
        }
        if (!defined('PROFILE_COVER_UPLOAD_PATH')) {
            define('PROFILE_COVER_UPLOAD_PATH', ROOTPATH . 'public/uploads/profile_covers/');
        }
        if (!defined('PROFILE_MAX_SIZE')) {
            define('PROFILE_MAX_SIZE', 5 * 1024 * 1024); // 5MB
        }
        if (!defined('PROFILE_ALLOWED_TYPES')) {
            define('PROFILE_ALLOWED_TYPES', 'jpg,jpeg,png,gif');
        }

        // Bio video upload constants
        if (!defined('BIO_VIDEO_UPLOAD_PATH')) {
            define('BIO_VIDEO_UPLOAD_PATH', ROOTPATH . 'public/uploads/videos/');
        }
        if (!defined('BIO_VIDEO_MAX_SIZE')) {
            define('BIO_VIDEO_MAX_SIZE', 50 * 1024 * 1024); // 50MB
        }
        if (!defined('BIO_VIDEO_ALLOWED_TYPES')) {
            define('BIO_VIDEO_ALLOWED_TYPES', 'mp4,mov,avi,webm');
        }
        if (!defined('BIO_VIDEO_MAX_DURATION')) {
            define('BIO_VIDEO_MAX_DURATION', 60); // 60 seconds
        }

        // Load subscription models
        $this->subscriptionPlanModel = new \App\Models\SubscriptionPlanModel();
        $this->tutorSubscriptionModel = new \App\Models\TutorSubscriptionModel();
    }

    private function ensureUploadDirectory(string $path): void
    {
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
    }

    // Helper methods for feature extraction
    private function extractClientLimit($features)
    {
        foreach ($features as $feature) {
            if (preg_match('/up to (\d+) clients/i', $feature, $matches)) {
                return 'Up to ' . $matches[1] . ' clients';
            }
            if (preg_match('/(\d+) clients/i', $feature, $matches)) {
                return 'Up to ' . $matches[1] . ' clients';
            }
        }
        return 'Limited clients';
    }

    private function extractSessionLimit($features)
    {
        foreach ($features as $feature) {
            if (preg_match('/(\d+) free session/i', $feature, $matches)) {
                return 'Up to ' . $matches[1] . ' sessions';
            }
            if (preg_match('/unlimited sessions/i', $feature)) {
                return 'Unlimited sessions';
            }
        }
        return 'Limited sessions';
    }

    private function hasFeature($features, $keywords)
    {
        foreach ($features as $feature) {
            foreach ($keywords as $keyword) {
                if (stripos($feature, $keyword) !== false) {
                    return true;
                }
            }
        }
        return false;
    }

    // Filter method to check admin approval and subscription for premium features
    private function requireSubscriptionForFeature($redirectTo = 'trainer/subscription')
    {
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        // First check if user is active and email verified
        if (!$user || !$user['is_active'] || !$user['email_verified_at']) {
            return redirect()->to('trainer/dashboard')->with('error', 'Please verify your email first.');
        }

        // Check if tutor application is under review (pending status)
        if ($user['tutor_status'] === 'pending') {
            return redirect()->to('trainer/dashboard')->with('info', 'Your application is under review. Only profile access is available until approved.');
        }

        // Check if user is approved and verified
        if ($user['tutor_status'] !== 'approved' && $user['tutor_status'] !== 'active') {
            return redirect()->to('trainer/dashboard')->with('error', 'Your account status prevents access to this feature.');
        }

        if (($user['is_verified'] ?? 0) != 1) {
            return redirect()->to('trainer/dashboard')->with('info', 'Your account is approved but pending final verification.');
        }

        // Check active subscription
        $subscription = $this->tutorSubscriptionModel->getActiveSubscription($userId);
        if (!$subscription) {
            return redirect()->to($redirectTo)->with('info', 'Please subscribe to access this feature.');
        }

        return null; // No redirect needed
    }

    // Check subscription status helper
    private function checkSubscriptionStatus($userId)
    {
        $user = $this->userModel->find($userId);
        if (!$user || !$user['is_active']) {
            return 'waiting_admin_approval';
        }

        $subscription = $this->tutorSubscriptionModel->getActiveSubscription($userId);
        return $subscription ? 'subscribed' : 'no_subscription';
    }

    // Check if user has active subscription
    private function requireSubscription($userId)
    {
        $user = $this->userModel->find($userId);

        // First check if user is activated (admin approved for tutors)
        if (!$user || !$user['is_active']) {
            return false;
        }

        // Then check subscription
        $subscription = $this->tutorSubscriptionModel->getActiveSubscription($userId);
        return $subscription !== null;
    }

    // Public methods for Dashboard controller access
    public function checkSubscriptionStatusPrivate($userId)
    {
        return $this->checkSubscriptionStatus($userId);
    }

    public function requireSubscriptionPrivate($userId)
    {
        return $this->requireSubscription($userId);
    }

    // Helper method to determine student status based on latest booking status
    private function getStudentStatus($latestBookingStatus)
    {
        switch ($latestBookingStatus) {
            case 'confirmed':
                return 'active';
            case 'completed':
                return 'completed';
            case 'cancelled':
                return 'inactive';
            case 'pending':
                return 'pending';
            default:
                return 'inactive';
        }
    }

    // Helper methods for structured subjects data extraction
    private function extractSubjectsFromStructured($structuredSubjects)
    {
        $subjects = [];
        if (is_array($structuredSubjects)) {
            foreach ($structuredSubjects as $curriculum => $curriculumData) {
                if (isset($curriculumData['levels']) && is_array($curriculumData['levels'])) {
                    foreach ($curriculumData['levels'] as $level => $levelSubjects) {
                        if (is_array($levelSubjects)) {
                            $subjects = array_merge($subjects, $levelSubjects);
                        }
                    }
                }
            }
        }
        return array_values(array_unique($subjects));
    }

    private function extractLevelsFromStructured($structuredSubjects)
    {
        $levels = [];
        if (is_array($structuredSubjects)) {
            foreach ($structuredSubjects as $curriculum => $curriculumData) {
                if (isset($curriculumData['levels']) && is_array($curriculumData['levels'])) {
                    $levels = array_merge($levels, array_keys($curriculumData['levels']));
                }
            }
        }
        return array_values(array_unique($levels));
    }

    private function extractCurriculaFromStructured($structuredSubjects)
    {
        return is_array($structuredSubjects) ? array_keys($structuredSubjects) : [];
    }

    public function courses()
    {
        $data = [
            'title' => 'My Courses - TutorConnect Malawi',
        ];

        // Get user from session
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        $data['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'email' => $user['email'],
            'role' => $user['role'],
        ];

        // Mock data for courses taught by this trainer
        $data['taught_courses'] = [
            [
                'id' => 1,
                'title' => 'Advanced JavaScript',
                'description' => 'Master advanced JavaScript concepts including closures, prototypes, and asynchronous programming.',
                'enrolled_students' => 45,
                'total_lessons' => 20,
                'completion_rate' => 78,
                'average_rating' => 4.8,
                'status' => 'active',
                'image' => 'javascript.png'
            ],
            [
                'id' => 2,
                'title' => 'Database Design',
                'description' => 'Learn database design principles, normalization, and SQL query optimization.',
                'enrolled_students' => 32,
                'total_lessons' => 15,
                'completion_rate' => 65,
                'average_rating' => 4.6,
                'status' => 'active',
                'image' => 'database.png'
            ],
            [
                'id' => 3,
                'title' => 'HTML & CSS Fundamentals',
                'description' => 'Build strong foundations in HTML5 and CSS3 with modern web development practices.',
                'enrolled_students' => 67,
                'total_lessons' => 12,
                'completion_rate' => 92,
                'average_rating' => 4.9,
                'status' => 'active',
                'image' => 'html-css.png'
            ]
        ];

        return view('trainer/courses', $data);
    }



    public function profile()
    {
        $data = [
            'title' => 'My Profile - TutorConnect Malawi',
        ];

        // Get user from session
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        // All tutor data is now consolidated in the users table
        $data['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'email' => $user['email'],
            'role' => $user['role'],
            'phone' => $user['phone'] ?: '',
            'profile_picture' => $user['profile_picture'] ?? null,
            'bio' => $user['bio'] ?? '',
            'district' => $user['district'] ?? '',
            'location' => $user['location'] ?? '',
            'best_call_time' => $user['best_call_time'] ?? '',
            'availability' => $user['availability'] ?? '',
            'availability_notes' => $user['availability_notes'] ?? '',
            'whatsapp_number' => $user['whatsapp_number'] ?? '',
            'experience_years' => $user['experience_years'] ?? '',
            'teaching_mode' => $user['teaching_mode'] ?? '',
            'hourly_rate' => $user['hourly_rate'] ?? $user['rate'] ?? '',
            // Extract subjects from structured_subjects data
            'structured_subjects' => json_decode($user['structured_subjects'] ?? '[]', true) ?? [],
            'subjects' => $this->extractSubjectsFromStructured(json_decode($user['structured_subjects'] ?? '[]', true) ?? []),
            'education_levels' => $this->extractLevelsFromStructured(json_decode($user['structured_subjects'] ?? '[]', true) ?? []),
            'phone_visible' => $user['phone_visible'] ?? 1,
            'email_visible' => $user['email_visible'] ?? 0,
            'preferred_contact_method' => $user['preferred_contact_method'] ?? 'phone',
            'best_call_time' => $user['best_call_time'] ?? '',
            'is_employed' => $user['is_employed'] ?? 0,
            'school_name' => $user['school_name'] ?? '',
            'join_date' => date('M j, Y', strtotime($user['created_at'] ?? 'now')),
            'last_login' => 'Today',
            'courses_taught' => 3,
            'total_clients' => 144,
            'avg_course_rating' => 4.8,
            'completion_rate' => 78
        ];

        // Load dynamic subject categories and education levels
        $subjectModel = new \App\Models\SubjectCategoryModel();
        $educationModel = new \App\Models\EducationLevelModel();

        $data['all_subjects'] = $subjectModel->where('is_active', 1)->orderBy('category', 'ASC')->orderBy('sort_order', 'ASC')->findAll();
        $data['all_education_levels'] = $educationModel->where('is_active', 1)->orderBy('category', 'ASC')->orderBy('level_order', 'ASC')->findAll();

        // Group subjects by category for better display
        $data['subjects_by_category'] = [];
        foreach ($data['all_subjects'] as $subject) {
            $data['subjects_by_category'][$subject['category']][] = $subject;
        }

        // Group education levels by category
        $data['education_by_category'] = [];
        foreach ($data['all_education_levels'] as $level) {
            $data['education_by_category'][$level['category']][] = $level;
        }

        // Add stats for profile completion
        $data['stats'] = [
            'students' => 0,
            'sessions' => 0,
            'response_rate' => 0
        ];

        $data['profile_completion'] = 65; // Calculate based on filled fields

        $data['subscription_status'] = $this->checkSubscriptionStatus($userId);

        return view('trainer/profile', $data);
    }

    public function subjects()
    {
        $data = [
            'title' => 'Subjects - TutorConnect Malawi',
        ];

        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId) ?? [];

        $data['user'] = $user;

        // Parse structured subjects data
        $structuredSubjects = json_decode($user['structured_subjects'] ?? '[]', true) ?? [];

        // Extract flat arrays from structured data for backward compatibility
        $data['selected_subjects'] = $this->extractSubjectsFromStructured($structuredSubjects);
        $data['selected_levels'] = $this->extractLevelsFromStructured($structuredSubjects);
        $data['selected_curricula'] = $this->extractCurriculaFromStructured($structuredSubjects);

        // Get current subscription with plan details
        $currentSubscription = $this->tutorSubscriptionModel->getSubscriptionWithPlan($userId);

        if ($currentSubscription) {
            $data['plan'] = [
                'name' => $currentSubscription['plan_name'],
                'description' => $currentSubscription['plan_description'] ?? '',
            ];
        }

        // SUBJECTS SOURCE: Load subjects ONLY from curriculum_subjects table
        // This ensures all displayed subjects come from the predefined curriculum database
        $curriculumSubjectsModel = new CurriculumSubjectsModel();

        $curriculaRows = $curriculumSubjectsModel->getCurricula();
        $curriculumLabels = [
            'MANEB' => 'MANEB (Malawi National Curriculum)',
            'GCSE' => 'GCSE (General Certificate of Secondary Education)',
            'Cambridge' => 'Cambridge (Cambridge International Curriculum)',
        ];

        $availableCurricula = [];
        foreach ($curriculaRows as $row) {
            $key = $row['curriculum'];
            $availableCurricula[$key] = $curriculumLabels[$key] ?? $key;
        }

        // Get all subjects grouped by curriculum and level FROM curriculum_subjects TABLE
        $groupedSubjects = $curriculumSubjectsModel->getSubjectsGrouped();

        $levelsByCurriculum = [];
        foreach ($groupedSubjects as $curriculumKey => $levels) {
            $levelsByCurriculum[$curriculumKey] = array_keys($levels);
        }

        $data['available_curricula'] = $availableCurricula;
        $data['curriculum_subjects'] = $groupedSubjects;
        $data['levels_by_curriculum'] = $levelsByCurriculum;

        // Check subscription status for plan limits display
        $subscriptionStatus = $this->checkSubscriptionStatus($userId);
        $data['subscription_status'] = $subscriptionStatus;

        // Get current subscription details if subscribed
        if ($subscriptionStatus === 'subscribed') {
            $data['current_subscription'] = $this->tutorSubscriptionModel->getSubscriptionWithPlan($userId);
        }

        return view('trainer/subjects', $data);
    }

    public function updateSubjects()
    {
        $userId = session()->get('user_id');
        $subjects = $this->request->getPost('subjects');
        log_message('debug', 'Subjects POST data: ' . print_r($subjects, true));

        if (!$userId) {
            log_message('error', 'No user ID in session');
            return redirect()->back()->with('error', 'Session expired.');
        }

        if (!is_array($subjects)) {
            $subjects = [];
        }

        // Build structured_subjects array
        $structuredSubjects = [];
        foreach ($subjects as $subject) {
            $parts = explode('|', $subject);
            $curriculum = $parts[0] ?? '';
            $level = $parts[1] ?? '';
            $subjectName = $parts[2] ?? '';
            if ($curriculum && $level && $subjectName) {
                if (!isset($structuredSubjects[$curriculum])) {
                    $structuredSubjects[$curriculum] = ['levels' => []];
                }
                if (!isset($structuredSubjects[$curriculum]['levels'][$level])) {
                    $structuredSubjects[$curriculum]['levels'][$level] = [];
                }
                if (!in_array($subjectName, $structuredSubjects[$curriculum]['levels'][$level])) {
                    $structuredSubjects[$curriculum]['levels'][$level][] = $subjectName;
                }
            }
        }

        try {
            $this->userModel->update($userId, [
                'structured_subjects' => json_encode($structuredSubjects),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            log_message('info', "Subjects updated for user_id: $userId in users.structured_subjects");
            return redirect()->to('trainer/subjects')->with('success', 'Subjects updated successfully.');
        } catch (\Exception $e) {
            log_message('error', 'Failed to update structured_subjects for user_id: ' . $userId . ' Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update subjects.');
        }
    }

    public function announcements()
    {
        // Redirect to the new notices functionality
        return redirect()->to('trainer/notices');
    }

    // Update personal information
    public function updatePersonal()
    {
        try {
            log_message('info', '=== trainer updatePersonal called ===');
            log_message('debug', 'updatePersonal POST data: ' . json_encode($this->request->getPost()));

            if ($this->request->getMethod() != 'post') {
                return redirect()->to('trainer/profile');
            }

            $userId = session()->get('user_id');
            log_message('info', 'updatePersonal userId: ' . $userId);
            $user = $this->userModel->find($userId);
            log_message('info', 'updatePersonal user found: ' . ($user ? 'yes' : 'no'));

            $rules = [
                'first_name' => 'required|max_length[50]',
                'last_name' => 'required|max_length[50]',
                'email' => 'required|valid_email|max_length[100]',
                'phone' => 'permit_empty|max_length[20]',
                'bio' => 'permit_empty|max_length[1000]',
            ];

            if (!$this->validate($rules)) {
                log_message('error', 'updatePersonal validation failed: ' . json_encode($this->validator->getErrors()));
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Please correct the errors in the form.',
                        'errors' => $this->validator->getErrors()
                    ]);
                }
                return redirect()->back()->with('error', 'Please correct the errors in the form.')->withInput();
            }

            $updateData = [
                'first_name' => $this->request->getPost('first_name'),
                'last_name' => $this->request->getPost('last_name'),
                'email' => $this->request->getPost('email'),
                'phone' => $this->request->getPost('phone'),
                'bio' => $this->request->getPost('bio'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            if ($this->userModel->update($userId, $updateData)) {
                session()->set([
                    'first_name' => $updateData['first_name'],
                    'last_name' => $updateData['last_name'],
                ]);
                log_message('info', 'updatePersonal success for user_id: ' . $userId);
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Personal information updated successfully!'
                    ]);
                }
                return redirect()->to('trainer/profile')->with('success', 'Personal information updated successfully!');
            }

            log_message('error', 'updatePersonal failed for user_id: ' . $userId . ', data: ' . json_encode($updateData));
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update personal information.'
                ]);
            }
            return redirect()->back()->with('error', 'Failed to update personal information.');
        } catch (\Exception $e) {
            log_message('error', 'Exception in updatePersonal: ' . $e->getMessage());
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'An error occurred while updating personal information.'
                ]);
            }
            return redirect()->back()->with('error', 'An error occurred while updating personal information.');
        }
    }

    // Update professional information
    public function updateProfessional()
    {
        log_message('debug', 'updateProfessional POST data: ' . json_encode($this->request->getPost()));
        log_message('info', '=== trainer updateProfessional called ===');

        if ($this->request->getMethod() != 'post') {
            return redirect()->to('trainer/profile');
        }

        $userId = session()->get('user_id');
        $isAjax = $this->request->isAJAX() || $this->request->getHeaderLine('Accept') === 'application/json';
        log_message('info', 'updateProfessional isAjax: ' . ($isAjax ? 'true' : 'false') . ', Accept header: ' . $this->request->getHeaderLine('Accept'));

        // AJAX/JSON update for main form (including availability)
        if ($isAjax) {
            log_message('info', 'updateProfessional AJAX request, userId: ' . $userId);
            if (!$userId) {
                log_message('error', 'updateProfessional: No userId');
                return $this->response->setStatusCode(401)->setJSON([
                    'success' => false,
                    'message' => 'Not authenticated'
                ]);
            }

            $updateData = [
                'district' => $this->request->getPost('district'),
                'teaching_mode' => $this->request->getPost('teaching_mode'),
                'experience_years' => $this->request->getPost('experience_years') ?: null,
                'hourly_rate' => $this->request->getPost('hourly_rate') ?: null,
                'whatsapp_number' => $this->request->getPost('whatsapp_number'),
                'is_employed' => $this->request->getPost('is_employed') ? 1 : 0,
                'school_name' => $this->request->getPost('school_name'),
                'updated_at' => date('Y-m-d H:i:s'),
                'location' => $this->request->getPost('location'),
                'bio' => $this->request->getPost('bio'),
                'best_call_time' => $this->request->getPost('best_call_time'),
            ];

            // Handle availability (days and times)
            $days = $this->request->getPost('days');
            $times = $this->request->getPost('times');
            $updateData['availability'] = json_encode([
                'days' => is_array($days) ? $days : [],
                'times' => is_array($times) ? $times : [],
            ]);

            // Handle cover photo upload (AJAX or form)
            if ($this->request->getFile('cover_photo')) {
                $coverPhoto = $this->request->getFile('cover_photo');
                if ($coverPhoto && $coverPhoto->isValid() && !$coverPhoto->hasMoved()) {
                    $coverPhotoName = 'cover_' . $userId . '_' . time() . '.' . $coverPhoto->getExtension();
                    $this->ensureUploadDirectory(PROFILE_COVER_UPLOAD_PATH);
                    $coverPhoto->move(PROFILE_COVER_UPLOAD_PATH, $coverPhotoName);
                    $updateData['cover_photo'] = 'uploads/profile_covers/' . $coverPhotoName;
                }
            }

            // Handle profile picture upload (AJAX or form)
            if ($this->request->getFile('profile_picture')) {
                $profilePicture = $this->request->getFile('profile_picture');
                if ($profilePicture && $profilePicture->isValid() && !$profilePicture->hasMoved()) {
                    $profilePictureName = 'profile_' . $userId . '_' . time() . '.' . $profilePicture->getExtension();
                    $this->ensureUploadDirectory(PROFILE_UPLOAD_PATH);
                    $profilePicture->move(PROFILE_UPLOAD_PATH, $profilePictureName);
                    $updateData['profile_picture'] = 'uploads/profile_photos/' . $profilePictureName;
                }
            }
            log_message('info', 'updateProfessional attempting database update for user_id: ' . $userId);
            log_message('debug', 'updateProfessional updateData: ' . json_encode($updateData));
            try {
                $success = $this->userModel->update($userId, $updateData);
                log_message('info', 'updateProfessional database update result: ' . ($success ? 'success' : 'failed'));
                if ($success) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Professional information updated successfully!',
                        'data' => $updateData
                    ]);
                } else {
                    log_message('error', 'updateProfessional failed for user_id: ' . $userId . ', data: ' . json_encode($updateData));
                    return $this->response->setStatusCode(500)->setJSON([
                        'success' => false,
                        'message' => 'Failed to update professional information.'
                    ]);
                }
            } catch (\Exception $e) {
                log_message('error', 'updateProfessional database update exception: ' . $e->getMessage());
                return $this->response->setStatusCode(500)->setJSON([
                    'success' => false,
                    'message' => 'Database error occurred while updating professional information.'
                ]);
            }
        }

        $rules = [
            'district' => 'permit_empty|max_length[50]',
            'teaching_mode' => 'permit_empty|in_list[Online Only,In-Person Only,Both]',
            'experience_years' => 'permit_empty|numeric|greater_than_equal_to[0]',
            'hourly_rate' => 'permit_empty|numeric|greater_than_equal_to[0]',
            'whatsapp_number' => 'permit_empty|max_length[20]',
            'school_name' => 'permit_empty|max_length[100]',
        ];

        if (!$this->validate($rules)) {
            log_message('error', 'updateProfessional validation failed: ' . json_encode($this->validator->getErrors()));
            return redirect()->back()->with('error', 'Please correct the errors in the form.')->withInput();
        }

        $updateData = [
            'district' => $this->request->getPost('district'),
            'teaching_mode' => $this->request->getPost('teaching_mode'),
            'experience_years' => $this->request->getPost('experience_years') ?: null,
            'hourly_rate' => $this->request->getPost('hourly_rate') ?: null,
            'whatsapp_number' => $this->request->getPost('whatsapp_number'),
            'is_employed' => $this->request->getPost('is_employed') ? 1 : 0,
            'school_name' => $this->request->getPost('school_name'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Handle cover photo upload
        $coverPhoto = $this->request->getFile('cover_photo');
        if ($coverPhoto && $coverPhoto->isValid() && !$coverPhoto->hasMoved()) {
            $coverPhotoName = 'cover_' . $userId . '_' . time() . '.' . $coverPhoto->getExtension();
            $this->ensureUploadDirectory(PROFILE_COVER_UPLOAD_PATH);
            $coverPhoto->move(PROFILE_COVER_UPLOAD_PATH, $coverPhotoName);
            $updateData['cover_photo'] = 'uploads/profile_covers/' . $coverPhotoName;
        }

        if ($this->userModel->update($userId, $updateData)) {
            return redirect()->to('trainer/profile')->with('success', 'Professional information updated successfully!');
        }

        return redirect()->back()->with('error', 'Failed to update professional information.');
    }

    // Update preferences
    public function updatePreferences()
    {
        log_message('info', '=== trainer updatePreferences called ===');

        if ($this->request->getMethod() != 'post') {
            return redirect()->to('trainer/profile');
        }

        $userId = session()->get('user_id');

        $updateData = [
            'phone_visible' => $this->request->getPost('phone_visible') ? 1 : 0,
            'email_visible' => $this->request->getPost('email_visible') ? 1 : 0,
            'preferred_contact_method' => $this->request->getPost('preferred_contact_method'),
            'best_call_time' => $this->request->getPost('best_call_time'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($this->userModel->update($userId, $updateData)) {
            return redirect()->to('trainer/profile')->with('success', 'Preferences updated successfully!');
        }

        return redirect()->back()->with('error', 'Failed to update preferences.');
    }

    // Change password
    public function changePassword()
    {
        log_message('debug', 'changePassword POST data: ' . json_encode($this->request->getPost()));
        log_message('info', '=== trainer changePassword called ===');

        if ($this->request->getMethod() != 'post') {
            return redirect()->to('trainer/profile');
        }

        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        $rules = [
            'current_password' => 'required',
            'new_password' => 'required|min_length[8]',
            'confirm_password' => 'required|matches[new_password]',
        ];

        if (!$this->validate($rules)) {
            log_message('error', 'changePassword validation failed: ' . json_encode($this->validator->getErrors()));
            return redirect()->back()->with('error', 'Please correct the errors in the form.')->withInput();
        }

        // Verify current password
        if (!password_verify($this->request->getPost('current_password'), $user['password'])) {
            log_message('error', 'changePassword failed: incorrect current password for user_id: ' . $userId);
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }

        // Update password
        $newPassword = password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT);

        if ($this->userModel->update($userId, [
            'password' => $newPassword,
            'updated_at' => date('Y-m-d H:i:s')
        ])) {
            log_message('info', 'Trainer ' . $userId . ' changed password successfully');
            return redirect()->to('trainer/profile')->with('success', 'Password changed successfully!');
        }

        return redirect()->back()->with('error', 'Failed to change password.');
    }

    public function reports()
    {
        $data = [
            'title' => 'Reports & Insights - TutorConnect Malawi',
        ];

        // Get user from session
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        $data['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'email' => $user['email'],
            'role' => $user['role'],
        ];

        return view('trainer/reports', $data);
    }

    // Video Submission - Subscription Tier Access Control
    public function submitVideo()
    {
        $userId = session()->get('user_id');

        // Check if user has active subscription
        $subscriptionModel = new \App\Models\TutorSubscriptionModel();
        $subscription = $subscriptionModel->getActiveSubscription($userId);

        if (!$subscription) {
            return redirect()->to('trainer/subscription')->with('error', 'You need an active subscription to submit video solutions.');
        }

        // Get subscription plan details
        $planModel = new \App\Models\SubscriptionPlanModel();
        $plan = $planModel->find($subscription['plan_id']);

        // Check if plan allows video submission (Standard or Premium)
        if (!in_array(strtolower($plan['name']), ['standard', 'premium'])) {
            return redirect()->to('trainer/dashboard')->with('error', 'Your current plan does not support video submissions.');
        }

        $data = [
            'title' => 'Submit Video Solution - TutorConnect Malawi',
            'subscription' => $subscription,
            'plan' => $plan,
        ];

        return view('trainer/submit_video', $data);
    }

    // Process Video Submission
    public function processVideoSubmission()
    {
        $userId = session()->get('user_id');

        // Check subscription access
        $subscriptionModel = new \App\Models\TutorSubscriptionModel();
        $subscription = $subscriptionModel->getActiveSubscription($userId);

        if (!$subscription) {
            return $this->response->setJSON(['success' => false, 'message' => 'Subscription required.']);
        }

        $planModel = new \App\Models\SubscriptionPlanModel();
        $plan = $planModel->find($subscription['plan_id']);

        if (!in_array(strtolower($plan['name']), ['standard', 'premium'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Your plan does not support video submissions.']);
        }

        // Validate input
        $rules = [
            'title' => 'required|max_length[255]',
            'description' => 'permit_empty',
            'video_url' => 'required|valid_url',
            'exam_body' => 'required|in_list[MANEB,Cambridge,GCSE,Other]',
            'subject' => 'required|max_length[100]',
            'topic' => 'permit_empty|max_length[255]',
            'problem_year' => 'permit_empty|integer|greater_than[2000]|less_than_equal_to[2030]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please correct the form errors.',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $videoUrl = $this->request->getPost('video_url');

        // Process video URL to extract platform and video ID
        $tutorVideosModel = new \App\Models\TutorVideosModel();
        $videoData = $tutorVideosModel->processVideoUrl($videoUrl);

        if (!$videoData['platform'] || !$videoData['video_id']) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid video URL. Please provide a valid YouTube or Vimeo URL.']);
        }

        // Check submission limits based on plan
        $existingVideos = $tutorVideosModel->where('tutor_id', $userId)
                                          ->where('status !=', 'rejected')
                                          ->countAllResults();

        $maxVideos = (strtolower($plan['name']) === 'premium') ? 4 : 1;

        if ($existingVideos >= $maxVideos) {
            return $this->response->setJSON(['success' => false, 'message' => "Your {$plan['name']} plan allows maximum {$maxVideos} approved videos. Please wait for admin approval or upgrade your plan."]);
        }

        // Create video record
        $videoData = [
            'tutor_id' => $userId,
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'video_embed_code' => $videoData['embed_code'],
            'video_platform' => $videoData['platform'],
            'video_id' => $videoData['video_id'],
            'exam_body' => $this->request->getPost('exam_body'),
            'subject' => $this->request->getPost('subject'),
            'topic' => $this->request->getPost('topic'),
            'problem_year' => $this->request->getPost('problem_year'),
            'status' => 'pending_review',
            'featured_level' => 'none', // Will be set by admin on approval
        ];

        if ($tutorVideosModel->insert($videoData)) {
            // Send notification email to admin
            $this->notifyAdminNewVideoSubmission($userId, $videoData);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Video submitted successfully! It will be reviewed by our team and published if approved.'
            ]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Failed to submit video. Please try again.']);
    }

    // Notify admin about new video submission
    private function notifyAdminNewVideoSubmission($tutorId, $videoData)
    {
        try {
            $tutor = $this->userModel->find($tutorId);

            if (!$tutor) return;

            $emailService = \Config\Services::email();

            $adminEmail = getenv('ADMIN_EMAIL') ?: 'info@tutorconnectmw.com';

            $emailService->setFrom('info@tutorconnectmw.com', 'TutorConnect Malawi');
            $emailService->setTo($adminEmail);
            $emailService->setSubject('🎥 New Video Solution Submitted - ' . $tutor['first_name'] . ' ' . $tutor['last_name']);

            $reviewUrl = base_url('admin/video-queue');

            $htmlMessage = "
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>New Video Submission</title>
    <style>
        .email-container { max-width: 600px; margin: 0 auto; font-family: Arial, sans-serif; }
        .header { background: linear-gradient(135deg, #ff6b35, #f7931e); color: white; padding: 30px; text-align: center; }
        .content { padding: 30px; background-color: #ffffff; }
        .video-info { background: #EBF4FF; border: 1px solid #3B82F6; padding: 20px; margin: 20px 0; border-radius: 8px; }
        .action-button { background: #10b981; color: white; padding: 15px 30px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block; margin: 20px 0; }
        .action-button:hover { background-color: #059669; }
        .footer { background-color: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class='email-container'>
        <div class='header'>
            <h1>🎥 New Video Solution</h1>
            <p>Review required for publication</p>
        </div>
        <div class='content'>
            <h2>Video Submission Details</h2>

            <div class='video-info'>
                <h3>Tutor Information:</h3>
                <p><strong>Name:</strong> " . htmlspecialchars($tutor['first_name'] . ' ' . $tutor['last_name']) . "</p>
                <p><strong>Email:</strong> " . htmlspecialchars($tutor['email']) . "</p>
                <p><strong>Phone:</strong> " . htmlspecialchars($tutor['phone'] ?: 'Not provided') . "</p>
            </div>

            <div style='background: #f8f9fa; padding: 15px; border-radius: 6px; margin: 15px 0;'>
                <h3>Video Details:</h3>
                <p><strong>Title:</strong> " . htmlspecialchars($videoData['title']) . "</p>
                <p><strong>Platform:</strong> " . htmlspecialchars(ucfirst($videoData['video_platform'])) . "</p>
                <p><strong>Exam Body:</strong> " . htmlspecialchars($videoData['exam_body']) . "</p>
                <p><strong>Subject:</strong> " . htmlspecialchars($videoData['subject']) . "</p>
                <p><strong>Topic:</strong> " . htmlspecialchars($videoData['topic'] ?: 'Not specified') . "</p>
                <p><strong>Problem Year:</strong> " . htmlspecialchars($videoData['problem_year'] ?: 'Not specified') . "</p>
            </div>

            <div style='background: #fff3cd; border: 2px solid #ffc107; padding: 15px; border-radius: 8px; margin: 20px 0;'>
                <strong>⚠️ Action Required:</strong><br>
                Please review this video submission and either approve or reject it in the admin panel.
            </div>

            <a href='" . htmlspecialchars($reviewUrl) . "' class='action-button'>Review Video Submissions →</a>

            <p><em>The video is currently pending review and will not be visible to students until approved.</em></p>
        </div>
        <div class='footer'>
            <p>&copy; 2025 TutorConnect Malawi. All rights reserved.<br>
            info@tutorconnectmw.com | Lilongwe, Malawi | +265 992 313 978</p>
        </div>
    </div>
</body>
</html>";

            $plainMessage = "New Video Submission - TutorConnect Malawi

Tutor: {$tutor['first_name']} {$tutor['last_name']}
Email: {$tutor['email']}

Video Details:
Title: {$videoData['title']}
Platform: " . ucfirst($videoData['video_platform']) . "
Exam Body: {$videoData['exam_body']}
Subject: {$videoData['subject']}
Topic: {$videoData['topic']}
Problem Year: {$videoData['problem_year']}

Action Required: Please review this video in the admin panel at " . $reviewUrl . "

---
TutorConnect Malawi
info@tutorconnectmw.com | +265 992 313 978";

            $emailService->setMessage($htmlMessage);
            $emailService->setAltMessage($plainMessage);

            if ($emailService->send()) {
                log_message('info', 'Admin notification sent for new video submission from tutor ID: ' . $tutorId);
            } else {
                log_message('error', 'Failed to send admin notification for video submission from tutor ID: ' . $tutorId);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error sending admin notification for video submission: ' . $e->getMessage());
        }
    }

    public function editProfile()
    {
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        // Check if user is active and approved
        if (!$user ||
            !$user['is_active'] ||
            !$user['email_verified_at'] ||
            $user['tutor_status'] === 'pending' ||
            ($user['tutor_status'] !== 'approved' && $user['tutor_status'] !== 'active') ||
            ($user['is_verified'] ?? 0) != 1) {
            return redirect()->to('trainer/dashboard')->with('error', 'Access denied.');
        }

        $data = [
            'title' => 'Edit Profile - TutorConnect Malawi',
            'user' => $user,
        ];

        // Load district options (Malawi districts)
        $data['districts'] = [
            'Balaka', 'Blantyre', 'Chikwawa', 'Chiradzulu', 'Chitipa', 'Dedza', 'Dowa', 'Karonga',
            'Kasungu', 'Likoma', 'Lilongwe', 'Machinga', 'Mangochi', 'Mchinji', 'Mulanje', 'Mwanza',
            'Mzimba', 'Nkhata Bay', 'Nkhotakota', 'Nsanje', 'Ntcheu', 'Ntchisi', 'Phalombe', 'Rumphi',
            'Salima', 'Thyolo', 'Zomba'
        ];

        return view('trainer/edit_profile', $data);
    }

    public function updateProfile()
    {
        log_message('info', '=== updateProfile called ===');
        $userId = session()->get('user_id');
        log_message('info', 'updateProfile userId: ' . $userId);

        $user = $this->userModel->find($userId);
        log_message('info', 'updateProfile user found: ' . ($user ? 'yes' : 'no'));

        if (!$user) {
            log_message('error', 'updateProfile: User not found for userId: ' . $userId);
            return redirect()->to('trainer/dashboard')->with('error', 'User not found.');
        }

        $rules = [
            'first_name' => 'required|max_length[50]',
            'last_name' => 'required|max_length[50]',
            'email' => 'required|valid_email|max_length[150]|is_unique[users.email,id,' . $userId . ']',
            'phone' => 'permit_empty|max_length[20]',
            'district' => 'permit_empty|max_length[50]',
            'location' => 'permit_empty|max_length[255]',
            'bio' => 'permit_empty|max_length[1000]',
            'experience_years' => 'permit_empty|numeric|greater_than_equal_to[0]',
            'teaching_mode' => 'permit_empty|in_list[Online Only,In-Person Only,Both Online & Physical]',
            'whatsapp_number' => 'permit_empty|max_length[20]',
            'is_employed' => 'permit_empty|in_list[0,1]',
            'school_name' => 'permit_empty|max_length[100]',
            'phone_visible' => 'permit_empty|in_list[0,1]',
            'email_visible' => 'permit_empty|in_list[0,1]',
            'preferred_contact_method' => 'permit_empty|in_list[phone,email,whatsapp]',
            'best_call_time' => 'permit_empty|in_list[Morning (8AM-12PM),Afternoon (12PM-5PM),Evening (5PM-9PM)]',
        ];

        if (!$this->validate($rules)) {
            log_message('error', 'updateProfile validation failed: ' . json_encode($this->validator->getErrors()));
            return redirect()->back()->with('error', 'Please correct the errors in the form.')->withInput()->with('errors', $this->validator->getErrors());
        }

        $updateData = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'district' => $this->request->getPost('district'),
            'location' => $this->request->getPost('location'),
            'bio' => $this->request->getPost('bio'),
            'experience_years' => $this->request->getPost('experience_years') ? (int)$this->request->getPost('experience_years') : null,
            'teaching_mode' => $this->request->getPost('teaching_mode'),
            'whatsapp_number' => $this->request->getPost('whatsapp_number'),
            'is_employed' => $this->request->getPost('is_employed') ? 1 : 0,
            'school_name' => $this->request->getPost('school_name'),
            'phone_visible' => $this->request->getPost('phone_visible') ? 1 : 0,
            'email_visible' => $this->request->getPost('email_visible') ? 1 : 0,
            'preferred_contact_method' => $this->request->getPost('preferred_contact_method'),
            'best_call_time' => $this->request->getPost('best_call_time'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        log_message('info', 'updateProfile updateData prepared: ' . json_encode($updateData));

        // Handle availability (days and times)
        $days = $this->request->getPost('days');
        $times = $this->request->getPost('times');
        if (is_array($days) && is_array($times)) {
            $updateData['availability'] = json_encode([
                'days' => $days,
                'times' => $times,
            ]);
            log_message('info', 'updateProfile availability set: ' . $updateData['availability']);
        }

        // Handle cover photo upload
        $coverPhoto = $this->request->getFile('cover_photo');
        if ($coverPhoto && $coverPhoto->isValid() && !$coverPhoto->hasMoved()) {
            log_message('info', 'updateProfile cover photo upload detected');
            $coverPhotoName = 'cover_' . $userId . '_' . time() . '.' . $coverPhoto->getExtension();
            $coverPhotoPath = PROFILE_COVER_UPLOAD_PATH . $coverPhotoName;
            log_message('info', 'updateProfile cover photo path: ' . $coverPhotoPath);

            $this->ensureUploadDirectory(PROFILE_COVER_UPLOAD_PATH);

            if ($coverPhoto->move(PROFILE_COVER_UPLOAD_PATH, $coverPhotoName)) {
                $updateData['cover_photo'] = 'uploads/profile_covers/' . $coverPhotoName;
                log_message('info', 'updateProfile cover photo moved successfully');
            } else {
                log_message('error', 'updateProfile cover photo move failed');
            }
        }

        // Handle profile picture upload
        $profilePicture = $this->request->getFile('profile_picture');
        if ($profilePicture && $profilePicture->isValid() && !$profilePicture->hasMoved()) {
            log_message('info', 'updateProfile profile picture upload detected');
            $profilePictureName = 'profile_' . $userId . '_' . time() . '.' . $profilePicture->getExtension();
            $profilePicturePath = PROFILE_UPLOAD_PATH . $profilePictureName;
            log_message('info', 'updateProfile profile picture path: ' . $profilePicturePath);

            $this->ensureUploadDirectory(PROFILE_UPLOAD_PATH);

            if ($profilePicture->move(PROFILE_UPLOAD_PATH, $profilePictureName)) {
                $updateData['profile_picture'] = 'uploads/profile_photos/' . $profilePictureName;
                log_message('info', 'updateProfile profile picture moved successfully');
            } else {
                log_message('error', 'updateProfile profile picture move failed');
            }
        }

        // Handle bio video upload
        $bioVideo = $this->request->getFile('bio_video');
        if ($bioVideo && $bioVideo->isValid() && !$bioVideo->hasMoved()) {
            log_message('info', 'updateProfile bio video upload detected');

            // Validate video duration and size
            if ($bioVideo->getSize() > BIO_VIDEO_MAX_SIZE) {
                log_message('error', 'updateProfile bio video size exceeds limit: ' . $bioVideo->getSize());
                return redirect()->back()->with('error', 'Bio video file size exceeds 50MB limit.');
            }

            $bioVideoName = 'intro_video_' . $userId . '_' . time() . '.' . $bioVideo->getExtension();
            $bioVideoPath = BIO_VIDEO_UPLOAD_PATH . $bioVideoName;
            log_message('info', 'updateProfile bio video path: ' . $bioVideoPath);

            $this->ensureUploadDirectory(BIO_VIDEO_UPLOAD_PATH);

            if ($bioVideo->move(BIO_VIDEO_UPLOAD_PATH, $bioVideoName)) {
                $updateData['bio_video'] = 'uploads/videos/' . $bioVideoName;
                log_message('info', 'updateProfile bio video moved successfully');
            } else {
                log_message('error', 'updateProfile bio video move failed');
            }
        }

        log_message('info', 'updateProfile final updateData: ' . json_encode($updateData));
        log_message('info', 'updateProfile attempting database update for userId: ' . $userId);

        try {
            log_message('info', 'updateProfile about to call userModel->update');

            $result = $this->userModel->update($userId, $updateData);

            log_message('info', 'updateProfile database update result: ' . ($result ? 'success' : 'failed'));

            // Check for database errors if update failed
            if (!$result) {
                // Try to get database error using db connection
                $db = \Config\Database::connect();
                $dbError = $db->error();
                log_message('error', 'updateProfile database error: ' . json_encode($dbError));
                log_message('error', 'updateProfile database update failed for userId: ' . $userId . ', error: ' . ($dbError['message'] ?? 'unknown'));

                // Also check if there's a specific model error
                if ($this->userModel->errors()) {
                    log_message('error', 'updateProfile model errors: ' . json_encode($this->userModel->errors()));
                }

                return redirect()->back()->with('error', 'Failed to update profile. Database error: ' . ($dbError['message'] ?? 'Unknown error'));
            }

            if ($result) {
                // Update session data
                session()->set([
                    'first_name' => $updateData['first_name'],
                    'last_name' => $updateData['last_name'],
                ]);

                log_message('info', 'updateProfile completed successfully for userId: ' . $userId);
                return redirect()->to('trainer/profile')->with('success', 'Profile updated successfully!');
            }
        } catch (\Exception $e) {
            log_message('error', 'updateProfile exception: ' . $e->getMessage());
            log_message('error', 'updateProfile exception trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Failed to update profile due to a system error: ' . $e->getMessage());
        }
    }

    public function subscription()
    {
        log_message('debug', 'Trainer::subscription() called'); // Add log for method entry

        $userId = session()->get('user_id');
        log_message('debug', 'User ID: ' . print_r($userId, true));

        $user = $this->userModel->find($userId);
        log_message('debug', 'User: ' . print_r($user, true));

        // Get all active plans - explicitly select all fields
        $available_plans = $this->subscriptionPlanModel->select('*')->where('is_active', 1)->orderBy('sort_order', 'ASC')->findAll();
        log_message('debug', 'Available plans: ' . print_r($available_plans, true));

        // Debug: Check features field for each plan
        foreach ($available_plans as $plan) {
            log_message('debug', 'Plan ' . $plan['name'] . ' features: "' . ($plan['features'] ?? 'NOT_SET') . '" (length: ' . strlen($plan['features'] ?? '') . ')');
            log_message('debug', 'Plan keys: ' . implode(', ', array_keys($plan)));
        }

        // Get current subscription
        $current_subscription = $this->tutorSubscriptionModel->getActiveSubscription($userId);
        log_message('debug', 'Current subscription: ' . print_r($current_subscription, true));

        $data = [
            'title' => 'Subscription Plans - TutorConnect Malawi',
            'user' => $user,
            'available_plans' => $available_plans,
            'current_subscription' => $current_subscription,
        ];

        log_message('debug', 'Subscription view data: ' . print_r($data, true));

        return view('trainer/subscription', $data);
    }

    // Upload Resources (Past Papers)
    public function uploadResources()
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

    // Process Resource Upload
    public function processResourceUpload()
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
            'uploaded_by' => $userId,
            'is_active' => 1,
            'uploaded_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Save to database
        $pastPapersModel = new \App\Models\PastPapersModel();
        if ($pastPapersModel->insert($paperData)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Past paper uploaded successfully!',
                'redirect' => base_url('trainer/resources/my-papers')
            ]);
        }

        // Clean up uploaded file if database insert failed
        if (file_exists($uploadPath . $newName)) {
            unlink($uploadPath . $newName);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Failed to save paper details.']);
    }

    // View My Past Papers
    public function myPapers()
    {
        // Check if user is logged in and is a trainer
        $userId = session()->get('user_id');
        if (!$userId || session()->get('role') !== 'trainer') {
            return redirect()->to('/login')->with('error', 'Access denied.');
        }

        $data = [
            'title' => 'My Past Papers - TutorConnect Malawi',
        ];

        // Load curriculum subjects for dropdowns (for upload form)
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

        // Get tutor's uploaded papers
        $pastPapersModel = new \App\Models\PastPapersModel();
        $data['papers'] = $pastPapersModel->where('uploaded_by', $userId)
                                         ->where('is_active', 1)
                                         ->orderBy('created_at', 'DESC')
                                         ->findAll();

        // Get download stats
        $totalDownloads = 0;
        $totalPapers = count($data['papers']);
        foreach ($data['papers'] as $paper) {
            $totalDownloads += (int) ($paper['download_count'] ?? 0);
        }
        $data['total_papers'] = $totalPapers;
        $data['total_downloads'] = $totalDownloads;

        return view('trainer/my_past_papers', $data);
    }

    // Upload Video Solutions
    public function uploadVideoSolutions()
    {
        // Check if user is logged in and is a trainer
        $userId = session()->get('user_id');
        if (!$userId || session()->get('role') !== 'trainer') {
            return redirect()->to('/login')->with('error', 'Access denied.');
        }

        // Check subscription for video solution upload capability
        $subscriptionModel = new \App\Models\TutorSubscriptionModel();
        $subscription = $subscriptionModel->getSubscriptionWithPlan($userId);

        if (!$subscription || ($subscription['allow_video_solution'] ?? 0) == 0) {
            return redirect()->to('trainer/subscription')->with('error', 'You need a subscription that allows video solution uploads.');
        }

        $data = [
            'title' => 'Upload Video Solutions - TutorConnect Malawi',
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

        return view('trainer/upload_video_solutions', $data);
    }

    // Process Video Solution Upload
    public function processVideoSolutionUpload()
    {
        // Check authentication and subscription
        $userId = session()->get('user_id');
        if (!$userId || session()->get('role') !== 'trainer') {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied.']);
        }

        $subscriptionModel = new \App\Models\TutorSubscriptionModel();
        $subscription = $subscriptionModel->getSubscriptionWithPlan($userId);

        if (!$subscription || ($subscription['allow_video_solution'] ?? 0) == 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Subscription required for video solution uploads.']);
        }

        // Validate form data
        $rules = [
            'exam_body' => 'required|in_list[MANEB,Cambridge,GCSE,IELTS,Other]',
            'subject' => 'required|max_length[100]',
            'title' => 'required|max_length[255]',
            'description' => 'permit_empty|max_length[1000]',
            'video_url' => 'required|valid_url',
            'topic' => 'permit_empty|max_length[255]',
            'problem_year' => 'permit_empty|integer|greater_than[2000]|less_than_equal_to[' . (date('Y') + 1) . ']',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $this->validator->getErrors()
            ]);
        }

        // Process video URL - handle both URLs and full iframe HTML
        $videoUrl = $this->request->getPost('video_url');
        $tutorVideosModel = new \App\Models\TutorVideosModel();

        // Check if it's a full iframe HTML
        if (stripos($videoUrl, '<iframe') !== false) {
            $embedCode = $videoUrl;
            $platform = 'youtube'; // Default, will be overridden if Vimeo detected
            $videoId = '';

            // Try to extract platform from iframe
            if (stripos($videoUrl, 'vimeo.com') !== false) {
                $platform = 'vimeo';
            }
        } else {
            // Process as regular URL
            $videoData = $tutorVideosModel->processVideoUrl($videoUrl);

            if (!$videoData['platform'] || !$videoData['video_id']) {
                return $this->response->setJSON(['success' => false, 'message' => 'Invalid video URL. Please provide a valid YouTube or Vimeo URL, or paste the full iframe embed code.']);
            }

            $embedCode = $videoData['embed_code'];
            $platform = $videoData['platform'];
            $videoId = $videoData['video_id'];
        }

        // Check upload limits based on plan
        $existingVideos = $tutorVideosModel->where('tutor_id', $userId)
                                          ->where('status !=', 'rejected')
                                          ->countAllResults();

        $maxVideos = (strtolower($subscription['plan_name']) === 'premium') ? 10 : 3;

        if ($existingVideos >= $maxVideos) {
            return $this->response->setJSON(['success' => false, 'message' => "Your {$subscription['plan_name']} plan allows maximum {$maxVideos} video solutions. Please wait for admin approval or upgrade your plan."]);
        }

        // Prepare data for database
        $videoSolutionData = [
            'tutor_id' => $userId,
            'exam_body' => $this->request->getPost('exam_body'),
            'subject' => $this->request->getPost('subject'),
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'video_embed_code' => $embedCode,
            'video_platform' => $platform,
            'video_id' => $videoId,
            'topic' => $this->request->getPost('topic'),
            'problem_year' => $this->request->getPost('problem_year'),
            'status' => 'pending_review',
            'featured_level' => 'none',
            'submitted_at' => date('Y-m-d H:i:s'),
        ];

        // Save to database
        if ($tutorVideosModel->insert($videoSolutionData)) {
            // Send notification email to admin
            $this->notifyAdminVideoSolutionSubmission($userId, $videoSolutionData);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Video solution uploaded successfully! It will be reviewed by our team.',
                'redirect' => base_url('trainer/resources/my-video-solutions')
            ]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Failed to save video solution details.']);
    }

    // View My Video Solutions
    public function myVideoSolutions()
    {
        // Check if user is logged in and is a trainer
        $userId = session()->get('user_id');
        if (!$userId || session()->get('role') !== 'trainer') {
            return redirect()->to('/login')->with('error', 'Access denied.');
        }

        $data = [
            'title' => 'My Video Solutions - TutorConnect Malawi',
        ];

        // Load curriculum subjects for dropdowns (for upload form)
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

        // Get tutor's uploaded video solutions
        $tutorVideosModel = new \App\Models\TutorVideosModel();
        $data['videos'] = $tutorVideosModel->where('tutor_id', $userId)
                                          ->orderBy('created_at', 'DESC')
                                          ->findAll();

        // Get video stats
        $totalViews = 0;
        $totalVideos = count($data['videos']);
        $approvedVideos = 0;
        $pendingVideos = 0;

        foreach ($data['videos'] as $video) {
            $totalViews += (int) ($video['view_count'] ?? 0);
            if ($video['status'] === 'approved') {
                $approvedVideos++;
            } elseif ($video['status'] === 'pending_review') {
                $pendingVideos++;
            }
        }

        $data['total_videos'] = $totalVideos;
        $data['total_views'] = $totalViews;
        $data['approved_videos'] = $approvedVideos;
        $data['pending_videos'] = $pendingVideos;

        return view('trainer/my_video_solutions', $data);
    }

    // Notify admin about video solution submission
    private function notifyAdminVideoSolutionSubmission($tutorId, $videoData)
    {
        try {
            $tutor = $this->userModel->find($tutorId);

            if (!$tutor) return;

            $emailService = \Config\Services::email();

            $adminEmail = getenv('ADMIN_EMAIL') ?: 'info@tutorconnectmw.com';

            $emailService->setFrom('info@tutorconnectmw.com', 'TutorConnect Malawi');
            $emailService->setSubject('🎥 New Video Solution Submitted - ' . $tutor['first_name'] . ' ' . $tutor['last_name']);

            $reviewUrl = base_url('admin/video-queue');

            $htmlMessage = "
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>New Video Solution Submission</title>
    <style>
        .email-container { max-width: 600px; margin: 0 auto; font-family: Arial, sans-serif; }
        .header { background: linear-gradient(135deg, #ff6b35, #f7931e); color: white; padding: 30px; text-align: center; }
        .content { padding: 30px; background-color: #ffffff; }
        .video-info { background: #EBF4FF; border: 1px solid #3B82F6; padding: 20px; margin: 20px 0; border-radius: 8px; }
        .action-button { background: #10b981; color: white; padding: 15px 30px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block; margin: 20px 0; }
        .action-button:hover { background-color: #059669; }
        .footer { background-color: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class='email-container'>
        <div class='header'>
            <h1>🎥 New Video Solution</h1>
            <p>Review required for publication</p>
        </div>
        <div class='content'>
            <h2>Video Solution Submission Details</h2>

            <div class='video-info'>
                <h3>Tutor Information:</h3>
                <p><strong>Name:</strong> " . htmlspecialchars($tutor['first_name'] . ' ' . $tutor['last_name']) . "</p>
                <p><strong>Email:</strong> " . htmlspecialchars($tutor['email']) . "</p>
                <p><strong>Phone:</strong> " . htmlspecialchars($tutor['phone'] ?: 'Not provided') . "</p>
            </div>

            <div style='background: #f8f9fa; padding: 15px; border-radius: 6px; margin: 15px 0;'>
                <h3>Video Solution Details:</h3>
                <p><strong>Title:</strong> " . htmlspecialchars($videoData['title']) . "</p>
                <p><strong>Platform:</strong> " . htmlspecialchars(ucfirst($videoData['video_platform'])) . "</p>
                <p><strong>Exam Body:</strong> " . htmlspecialchars($videoData['exam_body']) . "</p>
                <p><strong>Subject:</strong> " . htmlspecialchars($videoData['subject']) . "</p>
                <p><strong>Topic:</strong> " . htmlspecialchars($videoData['topic'] ?: 'Not specified') . "</p>
                <p><strong>Problem Year:</strong> " . htmlspecialchars($videoData['problem_year'] ?: 'Not specified') . "</p>
            </div>

            <div style='background: #fff3cd; border: 2px solid #ffc107; padding: 15px; border-radius: 8px; margin: 20px 0;'>
                <strong>⚠️ Action Required:</strong><br>
                Please review this video solution submission and either approve or reject it in the admin panel.
            </div>

            <a href='" . htmlspecialchars($reviewUrl) . "' class='action-button'>Review Video Solutions →</a>

            <p><em>The video solution is currently pending review and will not be visible to students until approved.</em></p>
        </div>
        <div class='footer'>
            <p>&copy; 2025 TutorConnect Malawi. All rights reserved.<br>
            info@tutorconnectmw.com | Lilongwe, Malawi | +265 992 313 978</p>
        </div>
    </div>
</body>
</html>";

            $plainMessage = "New Video Solution Submission - TutorConnect Malawi

Tutor: {$tutor['first_name']} {$tutor['last_name']}
Email: {$tutor['email']}

Video Solution Details:
Title: {$videoData['title']}
Platform: " . ucfirst($videoData['video_platform']) . "
Exam Body: {$videoData['exam_body']}
Subject: {$videoData['subject']}
Topic: {$videoData['topic']}
Problem Year: {$videoData['problem_year']}

Action Required: Please review this video solution in the admin panel at " . $reviewUrl . "

---
TutorConnect Malawi
info@tutorconnectmw.com | +265 992 313 978";

            $emailService->setMessage($htmlMessage);
            $emailService->setAltMessage($plainMessage);

            if ($emailService->send()) {
                log_message('info', 'Admin notification sent for new video solution submission from tutor ID: ' . $tutorId);
            } else {
                log_message('error', 'Failed to send admin notification for video solution submission from tutor ID: ' . $tutorId);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error sending admin notification for video solution submission: ' . $e->getMessage());
        }
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
}
