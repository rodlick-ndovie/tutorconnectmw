<?php

namespace App\Controllers;

use App\Models\TutorModel;
use App\Models\User;

class Home extends BaseController
{
    public function index()
    {
        log_message('info', '=== HOME CONTROLLER INDEX ===');

        try {
            $tutorModel = new TutorModel();
            $db = \Config\Database::connect();
            $usageTrackingModel = new \App\Models\UsageTrackingModel();

            // Get recent verified tutors instead of requiring "most searched"
            $recentTutors = $this->getRecentActiveTutors(6);
            $featured = $tutorModel->getFeatured(4);

            // Get usage statistics for homepage display
            $totalProfileViews = $usageTrackingModel->getTotalUsageCount('profile_views');
            $totalContactClicks = $usageTrackingModel->getTotalUsageCount('clicks');
            $totalActiveTutors = $db->table('users')
                                   ->where('role', 'trainer')
                                   ->where('tutor_status', 'approved')
                                   ->where('is_verified', 1)
                                   ->where('is_active', 1)
                                   ->countAllResults();

            // Get unique filter options from database for search form
            // Districts - get distinct districts from active tutors
            $districts = $db->table('users')
                           ->select('district')
                           ->where('role', 'trainer')
                           ->where('tutor_status', 'approved')
                           ->where('is_verified', 1)
                           ->where('is_active', 1)
                           ->where('district !=', '')
                           ->groupBy('district')
                           ->orderBy('district', 'ASC')
                           ->get()
                           ->getResultArray();

            // Get unique curricula from structured_subjects JSON field
            $structuredResults = $db->table('users')
                              ->select('structured_subjects')
                              ->where('role', 'trainer')
                              ->where('tutor_status', 'approved')
                              ->where('is_verified', 1)
                              ->where('is_active', 1)
                              ->where('structured_subjects !=', '')
                              ->get()
                              ->getResultArray();

            $curricula = [];
            $curriculumSubjects = []; // Map curricula to their subjects
            foreach ($structuredResults as $row) {
                if (!empty($row['structured_subjects'])) {
                    $decoded = json_decode($row['structured_subjects'], true);
                    if (is_array($decoded)) {
                        foreach ($decoded as $curriculumKey => $curriculumData) {
                            if (!empty($curriculumKey) && !in_array($curriculumKey, $curricula)) {
                                $curricula[] = $curriculumKey;
                            }

                            // Collect subjects for this curriculum
                            if (!isset($curriculumSubjects[$curriculumKey])) {
                                $curriculumSubjects[$curriculumKey] = [];
                            }

                            if (isset($curriculumData['levels']) && is_array($curriculumData['levels'])) {
                                foreach ($curriculumData['levels'] as $levelName => $levelSubjects) {
                                    if (is_array($levelSubjects)) {
                                        foreach ($levelSubjects as $subject) {
                                            if (!empty($subject) && !in_array($subject, $curriculumSubjects[$curriculumKey])) {
                                                $curriculumSubjects[$curriculumKey][] = $subject;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            sort($curricula);

            // Sort subjects within each curriculum
            foreach ($curriculumSubjects as $curriculum => $subjects) {
                sort($curriculumSubjects[$curriculum]);
            }

            // Get unique subjects from curricula results (same data as above)
            $subjects = [];
            foreach ($curriculumSubjects as $curriculumSubs) {
                foreach ($curriculumSubs as $subject) {
                    if (!empty($subject) && !in_array($subject, $subjects)) {
                        $subjects[] = $subject;
                    }
                }
            }
            sort($subjects);

            log_message('info', 'Home page data - Recent tutors: ' . count($recentTutors) . ', Featured: ' . count($featured));

            $data = [
                'mostSearched' => $recentTutors, // Show recent tutors instead of just most searched
                'featured' => $featured,
                'districts' => array_column($districts, 'district'),
                'curricula' => $curricula,
                'subjects' => $subjects,
                'curriculumSubjects' => $curriculumSubjects, // Add curriculum-to-subjects mapping
                'usage_stats' => [
                    'total_profile_views' => $totalProfileViews,
                    'total_contact_clicks' => $totalContactClicks,
                    'total_active_tutors' => $totalActiveTutors
                ],
                'title' => 'TutorConnect Malawi - Find Verified Tutors',
            ];
        } catch (\Exception $e) {
            log_message('error', 'Home controller error: ' . $e->getMessage());
            // If database isn't ready, show empty data
            $data = [
                'mostSearched' => [],
                'featured' => [],
                'districts' => [],
                'curricula' => [],
                'subjects' => [],
                'usage_stats' => [
                    'total_profile_views' => 0,
                    'total_contact_clicks' => 0,
                    'total_active_tutors' => 0
                ],
                'title' => 'TutorConnect Malawi - Find Verified Tutors',
            ];
        }

        return view('home/index', $data);
    }

    // Get recent active tutors for homepage display with subscription ranking
    private function getRecentActiveTutors($limit = 6)
    {
        $db = \Config\Database::connect();

        // Use raw query to ensure joins work correctly
        $query = $db->table('users')
                   ->select('users.*, tutor_subscriptions.plan_id, subscription_plans.name as plan_name, subscription_plans.search_ranking, subscription_plans.badge_level, subscription_plans.max_subjects')
                   ->join('tutor_subscriptions', 'tutor_subscriptions.user_id = users.id', 'left')
                   ->join('subscription_plans', 'subscription_plans.id = tutor_subscriptions.plan_id', 'left')
                   ->where('users.role', 'trainer')
                   ->where('users.tutor_status', 'approved')
                   ->where('users.is_verified', 1)
                   ->where('users.is_active', 1)
                   ->where('tutor_subscriptions.status', 'active')
                   ->where('tutor_subscriptions.current_period_start <=', date('Y-m-d H:i:s'))
                   ->where('tutor_subscriptions.current_period_end >=', date('Y-m-d H:i:s'))
                            ->orderBy("CASE subscription_plans.badge_level
                                WHEN 'master' THEN 5
                                WHEN 'expert' THEN 4
                                WHEN 'advanced' THEN 3
                                WHEN 'intermediate' THEN 2
                                WHEN 'beginner' THEN 1
                                ELSE 0 END", 'DESC') // Master → Expert → Advanced → Intermediate → Beginner
                   ->orderBy('users.created_at', 'DESC') // Then by newest
                   ->groupBy('users.id')
                   ->limit($limit)
                   ->get();

        $tutors = $query->getResultArray();

        log_message('info', 'Home controller - Retrieved tutors: ' . count($tutors) . ' with subscription ranking');

        // Extract subjects from structured_subjects for each tutor and apply max_subjects limit
        foreach ($tutors as &$tutor) {
            // Decode the structured_subjects JSON field
            $structuredSubjectsRaw = $tutor['structured_subjects'] ?? '[]';
            $structuredSubjects = is_string($structuredSubjectsRaw) ? json_decode($structuredSubjectsRaw, true) : $structuredSubjectsRaw;
            $structuredSubjects = is_array($structuredSubjects) ? $structuredSubjects : [];

            $subjects = [];

            // Extract all subjects from structured data
            foreach ($structuredSubjects as $curriculum => $curriculumData) {
                if (isset($curriculumData['levels']) && is_array($curriculumData['levels'])) {
                    foreach ($curriculumData['levels'] as $levelName => $levelSubjects) {
                        if (is_array($levelSubjects)) {
                            foreach ($levelSubjects as $subject) {
                                if (!empty($subject) && !in_array($subject, $subjects)) {
                                    $subjects[] = $subject;
                                }
                            }
                        }
                    }
                }
            }

            // Apply max_subjects limit from subscription plan
            $maxSubjects = (int) ($tutor['max_subjects'] ?? 3);
            if (count($subjects) > $maxSubjects) {
                $subjects = array_slice($subjects, 0, $maxSubjects);
            }

            $tutor['structured_subjects_array'] = $subjects;

            // Set default values for subscription features
            $tutor['search_ranking'] = $tutor['search_ranking'] ?? 'low';
            $tutor['badge_level'] = $tutor['badge_level'] ?? 'none';
            $tutor['max_subjects'] = $maxSubjects;
        }

        return $tutors;
    }

    public function howItWorks()
    {
        $data = [
            'title' => 'How It Works - TutorConnect Malawi',
            'description' => 'Learn how TutorConnect Malawi connects tutors with students.'
        ];

        return view('pages/how-it-works', $data);
    }

    public function pricing()
    {
        // Load subscription plans from database
        try {
            $subscriptionPlanModel = new \App\Models\SubscriptionPlanModel();
            $pricing_plans = $subscriptionPlanModel->getActivePlans();
        } catch (\Exception $e) {
            // If database isn't ready, use empty array
            $pricing_plans = [];
        }

        $data = [
            'title' => 'Pricing - TutorConnect Malawi',
            'description' => 'Choose from our tutor subscription plans',
            'pricing_plans' => $pricing_plans
        ];

        return view('pages/pricing', $data);
    }

    public function findTutors()
    {
        $tutorModel = new TutorModel();
        $db = \Config\Database::connect();

        // Get filters from GET parameters
        $filters = [
            'name' => $this->request->getGet('name'),
            'district' => $this->request->getGet('district'),
            'curriculum' => $this->request->getGet('curriculum'),
            'subject' => $this->request->getGet('subject'),
            'level' => $this->request->getGet('level'),
            'teaching_mode' => $this->request->getGet('teaching_mode'),
            'sort_by' => $this->request->getGet('sort_by') ?? 'rating'
        ];

        $tutors = $tutorModel->searchTutors(array_filter($filters));

        // Get unique filter options from database
        // Districts - get distinct districts from active tutors
        $districts = $db->table('users')
                       ->select('district')
                       ->where('role', 'trainer')
                       ->where('tutor_status', 'approved')
                       ->where('is_verified', 1)
                       ->where('is_active', 1)
                       ->where('district !=', '')
                       ->groupBy('district')
                       ->orderBy('district', 'ASC')
                       ->get()
                       ->getResultArray();

        // Teaching modes - get distinct values
        $teachingModes = $db->table('users')
                           ->select('teaching_mode')
                           ->where('role', 'trainer')
                           ->where('tutor_status', 'approved')
                           ->where('is_verified', 1)
                           ->where('is_active', 1)
                           ->where('teaching_mode !=', '')
                           ->groupBy('teaching_mode')
                           ->get()
                           ->getResultArray();

        // Get unique curricula from structured_subjects JSON field
        $structuredResults = $db->table('users')
                              ->select('structured_subjects')
                              ->where('role', 'trainer')
                              ->where('tutor_status', 'approved')
                              ->where('is_verified', 1)
                              ->where('is_active', 1)
                              ->where('structured_subjects !=', '')
                              ->get()
                              ->getResultArray();

        $curricula = [];
        $subjects = [];
        $levels = [];
        foreach ($structuredResults as $row) {
            if (!empty($row['structured_subjects'])) {
                $decoded = json_decode($row['structured_subjects'], true);
                if (is_array($decoded)) {
                    foreach ($decoded as $curriculumKey => $curriculumData) {
                        if (!empty($curriculumKey) && !in_array($curriculumKey, $curricula)) {
                            $curricula[] = $curriculumKey;
                        }

                        if (isset($curriculumData['levels']) && is_array($curriculumData['levels'])) {
                            foreach ($curriculumData['levels'] as $levelName => $levelSubjects) {
                                if (!empty($levelName) && !in_array($levelName, $levels)) {
                                    $levels[] = $levelName;
                                }

                                if (is_array($levelSubjects)) {
                                    foreach ($levelSubjects as $subject) {
                                        if (!empty($subject) && !in_array($subject, $subjects)) {
                                            $subjects[] = $subject;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        sort($curricula);
        sort($subjects);
        sort($levels);

        $data = [
            'tutors' => $tutors,
            'filters' => $filters,
            'districts' => array_column($districts, 'district'),
            'curricula' => $curricula,
            'subjects' => $subjects,
            'levels' => $levels,
            'teachingModes' => array_column($teachingModes, 'teaching_mode'),
            'totalCount' => count($tutors),
            'title' => 'Find Tutors - TutorConnect Malawi',
            'description' => 'Search and find verified tutors across Malawi.'
        ];

        return view('pages/find-tutors', $data);
    }

    public function tutorProfile($identifier)
    {
        $tutorModel = new TutorModel();
        $usageTrackingModel = new \App\Models\UsageTrackingModel();
        $tutorSubscriptionModel = new \App\Models\TutorSubscriptionModel();

        // Try to find tutor by username first (SEO-friendly URLs)
        $tutor = $tutorModel->getWithAllRelationsByUsername($identifier);

        // If not found by username, try to find by ID for backward compatibility
        if (!$tutor) {
            $tutor = $tutorModel->getWithAllRelations($identifier);
        }

        if (!$tutor) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Get the actual tutor ID for use in database operations
        $tutorId = $tutor['id'];

        // Get tutor's current active subscription
        $currentSubscription = $tutorSubscriptionModel->getActiveSubscription($tutorId);

        // Check if tutor has an active subscription
        $hasActiveSubscription = $currentSubscription && $currentSubscription['status'] === 'active';

        // Get subscription plan details
        $subscriptionPlan = null;
        if ($hasActiveSubscription) {
            $subscriptionPlanModel = new \App\Models\SubscriptionPlanModel();
            $subscriptionPlan = $subscriptionPlanModel->find($currentSubscription['plan_id']);
        }

        // Check profile view limits
        $canViewProfile = true;
        $viewLimitReached = false;
        $visitorIp = $this->request->getIPAddress();

        if ($subscriptionPlan && isset($subscriptionPlan['max_profile_views'])) {
            $maxViews = (int) $subscriptionPlan['max_profile_views'];
            if ($maxViews > 0) {
                $currentViews = $usageTrackingModel->getUsageCount($tutorId, 'profile_views');
                if ($currentViews >= $maxViews) {
                    $viewLimitReached = true;
                    $canViewProfile = false;
                }
            }
        }

        // Record profile view only if within limits and IP not already counted this month
        if ($canViewProfile) {
            // Get current billing period
            $period = $usageTrackingModel->getCurrentBillingPeriod();

            // Check if this IP has already viewed this profile this month
            $existingView = $usageTrackingModel->where('user_id', $tutorId)
                                              ->where('metric_type', 'profile_views')
                                              ->where('tracked_at >=', $period['start'])
                                              ->where('tracked_at <=', $period['end'])
                                              ->where('JSON_UNQUOTE(JSON_EXTRACT(metadata, "$.visitor_ip"))', $visitorIp)
                                              ->first();

            if (!$existingView) {
                $usageTrackingModel->recordUsage($tutorId, 'profile_views', 1, null, [
                    'visitor_ip' => $visitorIp,
                    'user_agent' => $this->request->getUserAgent(),
                    'referrer' => $this->request->getServer('HTTP_REFERER') ?? null
                ]);
            }
        }

        // Get total view count
        $viewCount = $usageTrackingModel->getUsageCount($tutorId, 'profile_views');

        // Get reviews for this tutor (limited by subscription plan)
        $db = \Config\Database::connect();
        $reviewsQuery = $db->table('reviews')
                           ->where('tutor_id', $tutorId)
                           ->orderBy('created_at', 'DESC');

        // Limit reviews display based on subscription plan
        if ($subscriptionPlan && isset($subscriptionPlan['max_reviews_display'])) {
            $maxReviews = (int) $subscriptionPlan['max_reviews_display'];
            if ($maxReviews > 0) {
                $reviewsQuery->limit($maxReviews);
            }
        }

        $reviews = $reviewsQuery->get()->getResultArray();

        // Determine allowed features based on subscription plan
        // Use the direct database fields from subscription_plans table
        $planFeatures = [
            'show_whatsapp' => false,
            'allow_video_upload' => false,
            'allow_pdf_upload' => false,
            'email_marketing_access' => false,
            'allow_announcements' => false,
            'badge_level' => 'none',
            'max_subjects' => 3, // Default
            'max_profile_views' => 0, // 0 = unlimited
            'max_reviews' => 10, // Default max reviews to display
            'max_clicks' => 0, // 0 = unlimited
            'max_messages' => 0, // 0 = unlimited
            'search_ranking' => 'low',
            'district_spotlight_days' => 0
        ];

        if ($subscriptionPlan) {
            // Use direct database fields
            $planFeatures['show_whatsapp'] = (bool) ($subscriptionPlan['show_whatsapp'] ?? false);
            $planFeatures['allow_video_upload'] = (bool) ($subscriptionPlan['allow_video_upload'] ?? false);
            $planFeatures['allow_pdf_upload'] = (bool) ($subscriptionPlan['allow_pdf_upload'] ?? false);
            $planFeatures['email_marketing_access'] = (bool) ($subscriptionPlan['email_marketing_access'] ?? false);
            $planFeatures['allow_announcements'] = (bool) ($subscriptionPlan['allow_announcements'] ?? false);
            $planFeatures['badge_level'] = $subscriptionPlan['badge_level'] ?? 'none';
            $planFeatures['max_subjects'] = (int) ($subscriptionPlan['max_subjects'] ?? 3);
            $planFeatures['max_profile_views'] = (int) ($subscriptionPlan['max_profile_views'] ?? 0);
            $planFeatures['max_reviews'] = (int) ($subscriptionPlan['max_reviews'] ?? 10);
            $planFeatures['max_clicks'] = (int) ($subscriptionPlan['max_clicks'] ?? 0);
            $planFeatures['max_messages'] = (int) ($subscriptionPlan['max_messages'] ?? 0);
            $planFeatures['search_ranking'] = $subscriptionPlan['search_ranking'] ?? 'low';
            $planFeatures['district_spotlight_days'] = (int) ($subscriptionPlan['district_spotlight_days'] ?? 0);

            // Fallback to max_students if max_subjects is not set
            if ($planFeatures['max_subjects'] <= 0) {
                $planFeatures['max_subjects'] = (int) ($subscriptionPlan['max_students'] ?? 3);
            }
        }

        // Limit subjects display based on plan
        // Use structured_subjects for tutor subjects
        $tutorSubjects = [];
        if (!empty($tutor['structured_subjects'])) {
            $structured = is_array($tutor['structured_subjects']) ? $tutor['structured_subjects'] : json_decode($tutor['structured_subjects'], true);
            if (is_array($structured)) {
                foreach ($structured as $curriculumKey => $curriculumData) {
                    if (isset($curriculumData['levels']) && is_array($curriculumData['levels'])) {
                        foreach ($curriculumData['levels'] as $levelName => $levelSubjects) {
                            if (is_array($levelSubjects)) {
                                foreach ($levelSubjects as $subject) {
                                    if (!empty($subject) && !in_array($subject, $tutorSubjects)) {
                                        $tutorSubjects[] = $subject;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        if (count($tutorSubjects) > $planFeatures['max_subjects']) {
            $tutorSubjects = array_slice($tutorSubjects, 0, $planFeatures['max_subjects']);
        }

        // Check if profile should show upgrade prompts
        $showUpgradePrompts = !$hasActiveSubscription || $subscriptionPlan['price_monthly'] < 1500; // Show for free/basic plans

        $data = [
            'tutor' => $tutor,
            'reviews' => $reviews,
            'profile_views' => $viewCount,
            'has_active_subscription' => $hasActiveSubscription,
            'subscription_plan' => $subscriptionPlan,
            'current_subscription' => $currentSubscription,
            'can_view_profile' => $canViewProfile,
            'view_limit_reached' => $viewLimitReached,
            'plan_features' => $planFeatures,
            'show_upgrade_prompts' => $showUpgradePrompts,
            'title' => ($tutor['user']['first_name'] ?? 'Unknown') . ' ' . ($tutor['user']['last_name'] ?? 'Tutor') . ' - TutorConnect Malawi',
            'description' => $tutor['bio'] ?? 'Tutor profile on TutorConnect Malawi',
            'hourly_rate' => $tutor['hourly_rate'] ?? 0,
            // 'tutor_curricula' and 'tutor_levels' are deprecated, use structured_subjects only
            'tutor_subjects' => $tutorSubjects,
        ];

        return view('pages/tutor-profile-professional', $data);
    }

    public function login()
    {
        $data = [
            'title' => 'Login - TutorConnect Malawi'
        ];
        return view('auth/login', $data);
    }

    public function register()
    {
        $data = [
            'title' => 'Register - TutorConnect Malawi'
        ];
        return view('auth/register', $data);
    }

    // Legal pages
    public function privacyPolicy()
    {
        $data = [
            'title' => 'Privacy Policy - TutorConnect Malawi',
        ];

        return view('legal/privacy_policy', $data);
    }

    public function termsOfService()
    {
        $data = [
            'title' => 'Terms of Service - TutorConnect Malawi',
        ];

        return view('legal/terms_of_service', $data);
    }

    public function refundPolicy()
    {
        $data = [
            'title' => 'Refund Policy - TutorConnect Malawi',
        ];

        return view('legal/refund_policy', $data);
    }

    public function verificationProcess()
    {
        $data = [
            'title' => 'Verification Process - TutorConnect Malawi',
        ];

        return view('legal/verification_process', $data);
    }

    public function childSafeguarding()
    {
        $data = [
            'title' => 'Child Safeguarding Policy - TutorConnect Malawi',
        ];

        return view('legal/child_safeguarding', $data);
    }



    // Handle review submission
    public function submitReview()
    {
        log_message('info', '=== SUBMIT REVIEW METHOD CALLED ===');

        if (strtolower($this->request->getMethod()) != 'post') {
            log_message('error', 'Method not POST: ' . $this->request->getMethod());
            return redirect()->back();
        }

        // Check if it's an AJAX request
        $isAjax = $this->request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest';
        log_message('info', 'Is AJAX request: ' . ($isAjax ? 'yes' : 'no'));

        // Log all POST data
        $postData = $this->request->getPost();
        log_message('info', 'POST data received: ' . json_encode($postData));

        $rules = [
            'tutor_id' => 'required|integer',
            'rating' => 'required|numeric|greater_than[0]|less_than_equal_to[5]',
            'reviewer_name' => 'required|max_length[100]',
            'reviewer_email' => 'permit_empty|max_length[150]|valid_email',
            'comment' => 'permit_empty|max_length[1000]', // Made optional for simplified reviews
            'is_anonymous' => 'permit_empty'
        ];

        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            log_message('error', 'Validation failed: ' . json_encode($errors));
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Please correct the following errors:',
                    'errors' => $errors
                ]);
            }
            return redirect()->back()->withInput()->with('review_errors', $errors);
        }

        $db = \Config\Database::connect();
        log_message('info', 'Database connection established');

        // Public reviews are completely independent - no booking relationship at all!
        $reviewData = [
            'tutor_id' => $this->request->getPost('tutor_id'),
            'reviewer_name' => $this->request->getPost('reviewer_name'),
            'rating' => (float) $this->request->getPost('rating'), // Ensure it's stored as decimal
            'comment' => $this->request->getPost('comment') ?: null, // Handle empty comments
            'is_anonymous' => $this->request->getPost('is_anonymous') ? 1 : 0,
            'created_at' => date('Y-m-d H:i:s')
        ];

        log_message('info', 'Prepared review data: ' . json_encode($reviewData));

        try {
            $result = $db->table('reviews')->insert($reviewData);
            log_message('info', 'Insert result: ' . ($result ? 'success' : 'failed'));

            if ($result) {
                $insertId = $db->insertID();
                log_message('info', 'Review inserted with ID: ' . $insertId);

                // Update tutor's rating and review count
                $this->updateTutorRating($this->request->getPost('tutor_id'));

                $message = 'Thank you for your review! It has been submitted successfully.';
                if ($isAjax) {
                    return $this->response->setJSON(['success' => true, 'message' => $message]);
                }
                return redirect()->back()->with('review_success', $message);
            } else {
                log_message('error', 'Insert returned false. Last query: ' . $db->getLastQuery());
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception during insert: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
        }

        $errorMessage = 'Failed to submit review. Please try again.';
        log_message('error', 'Returning error: ' . $errorMessage);
        if ($isAjax) {
            return $this->response->setJSON(['success' => false, 'message' => $errorMessage]);
        }

        return redirect()->back()->withInput()->with('review_error', $errorMessage);
    }



    // Update tutor's average rating and review count
    private function updateTutorRating($tutorId)
    {
        $db = \Config\Database::connect();

        // Get average rating and count
        $result = $db->table('reviews')
                    ->select('AVG(rating) as avg_rating, COUNT(*) as review_count')
                    ->where('tutor_id', $tutorId)
                    ->get()
                    ->getRow();

        if ($result) {
            // Update tutor record
            $db->table('users')
               ->where('id', $tutorId)
               ->update([
                   'rating' => round($result->avg_rating, 1),
                   'review_count' => $result->review_count,
                   'updated_at' => date('Y-m-d H:i:s')
               ]);
        }
    }



    /**
     * Track contact button clicks for subscription plan enforcement
     */
    public function trackContactClick()
    {
        log_message('info', '=== TRACK CONTACT CLICK STARTED ===');

        // Handle both AJAX and regular POST requests (for synchronous XMLHttpRequest)
        $isAjax = $this->request->isAJAX() || $this->request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest';
        log_message('info', 'Request type: ' . ($isAjax ? 'AJAX' : 'Regular POST'));

        // Log all request data
        $allPost = $this->request->getPost();
        $rawInput = file_get_contents('php://input');
        log_message('info', 'POST data: ' . json_encode($allPost));
        log_message('info', 'Raw input: ' . $rawInput);

        // For synchronous XMLHttpRequest, data might be in raw input
        if (!$isAjax && $this->request->getMethod() === 'POST') {
            // Parse raw POST data
            parse_str($rawInput, $postData);
            $tutorId = $postData['tutor_id'] ?? null;
            $contactType = $postData['contact_type'] ?? null;
            log_message('info', 'Parsed from raw input: tutor_id=' . $tutorId . ', contact_type=' . $contactType);
        } else {
            $tutorId = $this->request->getPost('tutor_id');
            $contactType = $this->request->getPost('contact_type');
            log_message('info', 'From getPost: tutor_id=' . $tutorId . ', contact_type=' . $contactType);
        }

        if (!$tutorId || !$contactType) {
            log_message('error', 'Track contact click - Missing parameters: tutor_id=' . $tutorId . ', contact_type=' . $contactType);
            return $this->response->setJSON(['success' => false, 'message' => 'Missing required parameters']);
        }

        log_message('info', 'Track contact click - Starting for tutor_id=' . $tutorId . ', contact_type=' . $contactType);

        // All contact button clicks (whatsapp, phone, email, message) are stored as "Contact Clicks"
        log_message('info', 'Contact click will be recorded as metric_type="clicks" for all contact types');

        // Check if tutor has active subscription
        $tutorSubscriptionModel = new \App\Models\TutorSubscriptionModel();
        $currentSubscription = $tutorSubscriptionModel->getActiveSubscription($tutorId);

        if (!$currentSubscription) {
            return $this->response->setJSON(['success' => false, 'message' => 'No active subscription']);
        }

        // Get subscription plan details
        $subscriptionPlanModel = new \App\Models\SubscriptionPlanModel();
        $subscriptionPlan = $subscriptionPlanModel->find($currentSubscription['plan_id']);

        if (!$subscriptionPlan) {
            return $this->response->setJSON(['success' => false, 'message' => 'Subscription plan not found']);
        }

        // Check if user has exceeded click limit
        $usageTrackingModel = new \App\Models\UsageTrackingModel();
        $clickLimit = (int) ($subscriptionPlan['max_clicks'] ?? 0);

        if ($clickLimit > 0) {
            $currentClicks = $usageTrackingModel->getUsageCount($tutorId, 'clicks');
            if ($currentClicks >= $clickLimit) {
                return $this->response->setJSON(['success' => false, 'message' => 'Click limit exceeded']);
            }
        }

        // Get current billing period and check if this IP has already clicked this month
        $period = $usageTrackingModel->getCurrentBillingPeriod();
        $visitorIp = $this->request->getIPAddress();

        // Check if this IP has already clicked for this contact type this month
        $existingClick = $usageTrackingModel->where('user_id', $tutorId)
                                           ->where('metric_type', 'contact_clicks')
                                           ->where('tracked_at >=', $period['start'])
                                           ->where('tracked_at <=', $period['end'])
                                           ->where('JSON_UNQUOTE(JSON_EXTRACT(metadata, "$.contact_type"))', $contactType)
                                           ->where('JSON_UNQUOTE(JSON_EXTRACT(metadata, "$.ip_address"))', $visitorIp)
                                           ->first();

        if ($existingClick) {
            // IP has already clicked for this contact type this month, don't record again
            return $this->response->setJSON(['success' => true, 'message' => 'Click already tracked']);
        }

        // Track the click
        $result = $usageTrackingModel->recordUsage($tutorId, 'contact_clicks', 1, null, [
            'contact_type' => $contactType,
            'user_agent' => $this->request->getUserAgent(),
            'ip_address' => $visitorIp,
            'referrer' => $this->request->getServer('HTTP_REFERER') ?? null
        ]);

        if ($result) {
            return $this->response->setJSON(['success' => true, 'message' => 'Click tracked successfully']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to track click']);
        }
    }

    /**
     * Send message to tutor and track it
     */
    public function sendMessage()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }

        // Get all POST data for debugging
        $allPostData = $this->request->getPost();
        log_message('info', 'sendMessage - All POST data received: ' . json_encode($allPostData));

        $tutorId = $this->request->getPost('tutor_id');
        $tutorEmail = $this->request->getPost('tutor_email');
        $senderName = $this->request->getPost('sender_name');
        $senderEmail = $this->request->getPost('sender_email');
        $subject = $this->request->getPost('subject');
        $message = $this->request->getPost('message');
        $contactPreference = $this->request->getPost('contact_preference');
        $contactDetail = $this->request->getPost('contact_detail');

        // Detailed validation logging
        log_message('info', 'sendMessage - Validation check:');
        log_message('info', '  tutorId: ' . ($tutorId ? 'OK' : 'MISSING'));
        log_message('info', '  tutorEmail: ' . ($tutorEmail ? 'OK' : 'MISSING'));
        log_message('info', '  senderName: ' . ($senderName ? 'OK (' . $senderName . ')' : 'MISSING'));
        log_message('info', '  senderEmail: ' . ($senderEmail ? 'OK (' . $senderEmail . ')' : 'MISSING'));
        log_message('info', '  subject: ' . ($subject ? 'OK (' . $subject . ')' : 'MISSING'));
        log_message('info', '  message: ' . ($message ? 'OK (' . substr($message, 0, 50) . '...)' : 'MISSING'));
        log_message('info', '  contactPreference: ' . ($contactPreference ? 'OK (' . $contactPreference . ')' : 'MISSING'));
        log_message('info', '  contactDetail: ' . ($contactDetail ? 'OK (' . $contactDetail . ')' : 'MISSING'));

        if (!$tutorId || !$tutorEmail || !$senderName || !$senderEmail || !$subject || !$message || !$contactPreference || !$contactDetail) {
            log_message('error', 'sendMessage - Validation failed: Missing required fields');
            return $this->response->setJSON(['success' => false, 'message' => 'Missing required fields. Check logs for details.']);
        }

        // Get tutor details for personalized email
        $tutorModel = new \App\Models\TutorModel();
        $tutor = $tutorModel->find($tutorId);

        if (!$tutor) {
            return $this->response->setJSON(['success' => false, 'message' => 'Tutor not found']);
        }

        // Send email to tutor using CodeIgniter SMTP (same config as Auth controller)
        $email = \Config\Services::email();

        // Use same configuration as Auth controller for OTP emails
        $siteSettingModel = new \App\Models\SiteSettingModel();
        $companyName = $siteSettingModel->getValue('company_name', 'TutorConnect Malawi');
        $contactEmail = $siteSettingModel->getValue('contact_email', 'info@uprisemw.com');

        // Debug: Log email configuration
        log_message('info', 'Email config - From: ' . $contactEmail . ', Host: ' . $email->SMTPHost . ', User: ' . $email->SMTPUser);

        $email->setFrom($contactEmail, $companyName);
        $email->setReplyTo($senderEmail, $senderName);
        $email->setTo($tutorEmail);
        $email->setSubject($companyName . ': ' . $subject);
        $email->setMailType('html');

        // Create professional HTML email template
        $emailContent = "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>{$companyName} - New Student Message</title>
            <style>
                body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; background-color: #f8f9fa; margin: 0; padding: 0; }
                .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
                .header { background: linear-gradient(135deg, #E55C0D, #C0392B); padding: 30px 20px; text-align: center; color: white; }
                .logo { max-width: 200px; height: auto; }
                .content { padding: 30px 20px; }
                .footer { background: #f8f9fa; padding: 20px; text-align: center; border-top: 1px solid #dee2e6; }
                .btn { display: inline-block; background: #E55C0D; color: white; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: 600; margin: 10px 0; }
                .btn:hover { background: #C0392B; }
                .highlight { background: linear-gradient(135deg, #FFF3CD, #FFEAA7); padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #E55C0D; }
                .message-box { background: #f8f9fa; border: 2px solid #E55C0D; padding: 20px; border-radius: 8px; margin: 20px 0; }
                .contact-info { background: #e8f5e8; border: 1px solid #4CAF50; padding: 15px; border-radius: 8px; margin: 15px 0; }
                h1, h2, h3 { color: #2C3E50; margin-top: 0; }
                p { margin-bottom: 15px; }
                .social-links a { margin: 0 10px; color: #666; text-decoration: none; }
                .social-links a:hover { color: #E55C0D; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>{$companyName}</h1>
                    <p>Professional Tutoring Platform</p>
                </div>
                <div class='content'>
                    <h2>📬 New Student Message</h2>

                    <p>Hello <strong>" . esc($tutor['first_name'] . ' ' . $tutor['last_name']) . "</strong>,</p>

                    <p>You have received a new message from a potential student through the {$companyName} platform. Here's the complete message:</p>

                    <div class='highlight'>
                        <h3 style='margin-top: 0; color: #2C3E50;'>📝 Message Details</h3>
                        <p><strong>From:</strong> " . esc($senderName) . "</p>
                        <p><strong>Subject:</strong> " . esc($subject) . "</p>
                        <p><strong>Preferred Contact Method:</strong> " . esc($contactPreference) . "</p>
                    </div>

                    <div class='message-box'>
                        <h3 style='margin-top: 0; color: #2C3E50;'>💬 Message Content</h3>
                        <p style='white-space: pre-line;'>" . nl2br(esc($message)) . "</p>
                    </div>

                    <div class='contact-info'>
                        <h3 style='margin-top: 0; color: #2C3E50;'>📞 How to Respond</h3>
                        <p>The student prefers to be contacted via <strong>" . esc($contactPreference) . "</strong>.</p>
                        <p><strong>Student's Contact:</strong> " . esc($contactDetail) . "</p>
                        <p>Please respond using their preferred method to ensure the best communication experience.</p>
                    </div>

                    <div style='text-align: center; margin: 30px 0;'>
                        <a href='mailto:" . esc($contactDetail) . "?subject=Re: " . esc($subject) . "' class='btn'>📧 Reply to Student</a>
                    </div>

                    <div class='highlight'>
                        <h3 style='margin-top: 0; color: #2C3E50;'>💡 Pro Tip</h3>
                        <p style='margin-bottom: 0; color: #555;'>Responding promptly to student inquiries increases your chances of securing tutoring opportunities!</p>
                    </div>

                    <p>Thank you for being part of the {$companyName} community. We're here to help you connect with students who need your expertise.</p>

                    <p>Best regards,<br>The {$companyName} Team</p>
                </div>
                <div class='footer'>
                    <p><strong>{$companyName}</strong> - Connecting Tutors & Students Across Malawi</p>
                    <p>© " . date('Y') . " {$companyName}. All rights reserved.</p>
                    <div style='margin-top: 15px;'>
                        <a href='#' style='margin: 0 10px; color: #1877F2; text-decoration: none;'>Facebook</a> |
                        <a href='#' style='margin: 0 10px; color: #1DA1F2; text-decoration: none;'>Twitter</a>
                    </div>
                </div>
            </div>
        </body>
        </html>
        ";

        $email->setMessage($emailContent);

        // Send the email
        if ($email->send()) {
            log_message('info', 'Email sent successfully to: ' . $tutorEmail);

            // Track the message in usage_tracking table
            $usageTrackingModel = new \App\Models\UsageTrackingModel();

            $result = $usageTrackingModel->recordUsage($tutorId, 'messages', 1, null, [
                'sender_name' => $senderName,
                'sender_email' => $senderEmail,
                'subject' => $subject,
                'contact_preference' => $contactPreference,
                'ip_address' => $this->request->getIPAddress(),
                'user_agent' => $this->request->getUserAgent()
            ]);

            if ($result) {
                log_message('info', 'Message tracked successfully in database');
                return $this->response->setJSON(['success' => true, 'message' => 'Message sent successfully!']);
            } else {
                log_message('error', 'Email sent but tracking failed');
                return $this->response->setJSON(['success' => false, 'message' => 'Email sent but tracking failed']);
            }
        } else {
            $error = $email->printDebugger(['headers']);
            log_message('error', 'Failed to send email. Debug: ' . $error);

            // Provide user-friendly error message
            $userMessage = 'Failed to send email. ';
            if (strpos($error, 'authentication failed') !== false) {
                $userMessage .= 'Email authentication failed - please check email settings.';
            } elseif (strpos($error, 'connection') !== false) {
                $userMessage .= 'Could not connect to email server.';
            } else {
                $userMessage .= 'Please try again or contact support.';
            }

            return $this->response->setJSON(['success' => false, 'message' => $userMessage]);
        }
    }

    public function about()
    {
        $data = [
            'title' => 'About Us - TutorConnect Malawi',
            'description' => 'Learn more about TutorConnect Malawi.'
        ];

        return view('home/about', $data);
    }
}
