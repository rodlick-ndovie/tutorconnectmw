<?php

namespace App\Controllers;

use App\Models\TutorModel;
use App\Models\ContactMessageModel;
use App\Models\JapanApplicationModel;
use App\Models\JapanApplicationAccessModel;

class Dashboard extends BaseController
{
    public function index()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        // For trainers, enforce document upload completion
        if (session()->get('role') === 'trainer') {
            $userId = session()->get('user_id');
            $userModel = $this->getUserModel();
            $user = $userModel->find($userId);

            if ($user) {
                // If user has completed registration, allow access to dashboard
                $registrationCompleted = (bool) ($user['registration_completed'] ?? 0);
                if ($registrationCompleted) {
                    // User has submitted their profile, allow dashboard access
                    // The trainerDashboard method will handle the approval status
                } else {
                    // Check if they still need to complete documents
                    $documents = json_decode($user['verification_documents'], true);
                    $needsDocuments = empty($documents) || !is_array($documents);

                    if (!$needsDocuments) {
                        $requiredDocs = ['national_id', 'academic_certificates', 'police_clearance', 'teaching_qualification'];
                        $uploadedTypes = array_column($documents, 'document_type');

                        foreach ($requiredDocs as $reqDoc) {
                            if (!in_array($reqDoc, $uploadedTypes)) {
                                $needsDocuments = true;
                                break;
                            }
                        }
                    }

                    $needsProfilePicture = empty($user['profile_picture']);

                    if ($needsDocuments || $needsProfilePicture) {
                        return redirect()->to('trainer/dashboard/complete-profile')
                            ->with('info', 'Please upload required documents to access your dashboard.');
                    }
                }
            }
        }

        $data['title'] = 'Dashboard - TutorConnect Malawi';
        $data['user'] = [
            'username' => session()->get('username'),
            'role' => session()->get('role'),
            'first_name' => session()->get('first_name'),
            'last_name' => session()->get('last_name'),
            'email' => session()->get('email'),
            'tutor_status' => $user['tutor_status'] ?? null,
            'is_verified' => $user['is_verified'] ?? 0,
        ];

        $role = $data['user']['role'];

        switch ($role) {
            case 'admin':
                return $this->adminDashboard($data);
            case 'trainer':
                return $this->trainerDashboard($data);
            case 'customer':
                $data['dashboard_title'] = 'Customer Dashboard';
                $data['sections'] = [
                    ['title' => 'Book Services', 'link' => '/services', 'icon' => 'cog'],
                    ['title' => 'My Orders', 'link' => '/customer/orders', 'icon' => 'shopping-cart'],
                    ['title' => 'Support', 'link' => '/customer/support', 'icon' => 'help-circle'],
                ];
                return view('trainer/customer', $data);
            default:
                $data['dashboard_title'] = 'Dashboard';
                $data['sections'] = [];
                return view('trainer/default', $data);
        }
    }

    private function adminDashboard($data)
    {
        $data['dashboard_title'] = 'Administrator Dashboard';

        $userModel = $this->getUserModel();
        $tutorModel = new TutorModel();
        $contactMessageModel = new ContactMessageModel();
        $db = \Config\Database::connect();
        $japanApplicationModel = new JapanApplicationModel();
        $japanApplicationAccessModel = new JapanApplicationAccessModel();

        // Basic user statistics
        $data['stats'] = [
            'total_users' => $userModel->countAllResults(),
            'total_trainers' => $userModel->where('role', 'trainer')->countAllResults(),
            'active_users' => $userModel->where('is_active', 1)->countAllResults(),
        ];

        // Tutor-specific stats from consolidated users table
        $allTrainers = $userModel->where('role', 'trainer')->findAll();
        $data['stats']['total_tutors'] = count($allTrainers);
        $data['stats']['verified_tutors'] = count(array_filter($allTrainers, fn($trainer) => ($trainer['is_verified'] ?? 0) == 1 && in_array($trainer['tutor_status'] ?? '', ['active', 'approved'])));
        $data['stats']['avg_tutor_rating'] = count($allTrainers) > 0 ? round(array_sum(array_column($allTrainers, 'rating')) / count($allTrainers), 1) : 0;
        $data['stats']['avg_rating'] = $data['stats']['avg_tutor_rating'];

        // Expose unread contact messages for the admin sidebar badge
        $data['unreadMessageCount'] = $contactMessageModel->getUnreadCount();

        // Count pending trainers (tutor_status = 'pending')
        $data['stats']['pending_verification'] = $userModel
            ->where('role', 'trainer')
            ->where('tutor_status', 'pending')
            ->countAllResults();

        // Booking statistics removed
        $data['stats']['total_bookings'] = 0;
        $data['stats']['confirmed_bookings'] = 0;
        $data['stats']['pending_bookings'] = 0;

        // Set revenue to 0 for now (rate column doesn't exist)
        $data['stats']['total_revenue'] = 0;
        $data['stats']['monthly_revenue'] = 0;

        // Subscription statistics and revenue
        try {
            $totalSubscriptions = $db->table('tutor_subscriptions')->countAllResults();
            $activeSubscriptions = $db->table('tutor_subscriptions')
                ->where('status', 'active')
                ->where('current_period_end >=', date('Y-m-d H:i:s'))
                ->countAllResults();

            $data['stats']['total_subscriptions'] = $totalSubscriptions;
            $data['stats']['active_subscriptions'] = $activeSubscriptions;

            // Calculate subscription revenue (monthly recurring revenue)
            $subscriptionRevenue = $db->table('tutor_subscriptions ts')
                ->select('SUM(sp.price_monthly) as subscription_revenue')
                ->join('subscription_plans sp', 'sp.id = ts.plan_id')
                ->where('ts.status', 'active')
                ->where('ts.current_period_end >=', date('Y-m-d H:i:s'))
                ->get()
                ->getRow();

            $data['stats']['subscription_revenue'] = $subscriptionRevenue && $subscriptionRevenue->subscription_revenue ?
                (int)$subscriptionRevenue->subscription_revenue : 0;

            // Add subscription revenue to total revenue for dashboard display
            $data['stats']['total_revenue'] += $data['stats']['subscription_revenue'];

            // Calculate total PayChangu revenue (all verified subscription payments)
            $paychanguRevenue = $db->table('tutor_subscriptions ts')
                ->select('SUM(sp.price_monthly) as paychangu_revenue')
                ->join('subscription_plans sp', 'sp.id = ts.plan_id')
                ->where('ts.payment_status', 'verified')
                ->where('ts.payment_amount >', 0)
                ->get()
                ->getRow();

            $data['stats']['paychangu_revenue'] = $paychanguRevenue && $paychanguRevenue->paychangu_revenue ?
                (int)$paychanguRevenue->paychangu_revenue : 0;

        } catch (\Exception $e) {
            $data['stats']['total_subscriptions'] = 0;
            $data['stats']['active_subscriptions'] = 0;
            $data['stats']['subscription_revenue'] = 0;
            $data['stats']['paychangu_revenue'] = 0;
        }

        // Japan application fee totals (Teach in Japan)
        try {
            $japanApplicationModel->ensureTable();
            $japanApplicationAccessModel->ensureTable();

            $applicationsRow = $db->table('japan_teaching_applications')
                ->select('COUNT(*) as total_applications')
                ->get()
                ->getRow();

            $paymentsRow = $db->table('japan_application_access')
                ->select('
                    SUM(CASE WHEN payment_status = "verified" THEN amount ELSE 0 END) as verified_amount_total,
                    SUM(CASE WHEN payment_status = "verified" THEN 1 ELSE 0 END) as verified_payments_total
                ')
                ->get()
                ->getRow();

            $data['stats']['japan_applications_total'] = $applicationsRow ? (int) ($applicationsRow->total_applications ?? 0) : 0;
            $data['stats']['japan_verified_payments_total'] = $paymentsRow ? (int) ($paymentsRow->verified_payments_total ?? 0) : 0;
            $data['stats']['japan_application_fees_total'] = $paymentsRow && $paymentsRow->verified_amount_total !== null
                ? (int) $paymentsRow->verified_amount_total
                : 0;

            // Add Japan application fees into total revenue for display
            $data['stats']['total_revenue'] += $data['stats']['japan_application_fees_total'];
        } catch (\Throwable $e) {
            $data['stats']['japan_applications_total'] = 0;
            $data['stats']['japan_verified_payments_total'] = 0;
            $data['stats']['japan_application_fees_total'] = 0;
        }

        // Recent users (last 5)
        $data['recent_users'] = $userModel->orderBy('created_at', 'DESC')->limit(5)->find();

        // Verified tutors with real details
        $data['verified_tutors'] = $userModel
            ->where('role', 'trainer')
            ->where('is_verified', 1)
            ->whereIn('tutor_status', ['active', 'approved'])
            ->orderBy('rating', 'DESC')
            ->findAll();

        // Recent activity (real data from multiple tables)
        $data['recent_activity'] = $this->getRecentActivity();

        return view('admin/dashboard', $data);
    }

    private function trainerDashboard($data)
    {
        $data['dashboard_title'] = 'Trainer Dashboard';

        // Add trainer-specific data
        $userId = session()->get('user_id');
        $user = $this->getUserModel()->find($userId) ?? [];



        // Build a simple status object for the view so trainers see submission state
        $tutorStatus = $user['tutor_status'] ?? 'pending';
        $registrationCompleted = (bool) ($user['registration_completed'] ?? 0);
        $isVerified = (int) ($user['is_verified'] ?? 0);

        $data['profile_status'] = [
            'state' => 'incomplete',
            'title' => 'Profile not started',
            'message' => 'Finish your profile to access the dashboard.',
            'variant' => 'warning'
        ];

        if ($registrationCompleted) {
            if ($tutorStatus === 'pending' || $isVerified !== 1) {
                $data['profile_status'] = [
                    'state' => 'submitted',
                    'title' => 'Profile submitted',
                    'message' => 'We are reviewing your documents. You will be notified once approved.',
                    'variant' => 'warning'
                ];
            } elseif (in_array($tutorStatus, ['approved', 'active'], true) && $isVerified === 1) {
                $data['profile_status'] = [
                    'state' => 'approved',
                    'title' => 'Profile approved',
                    'message' => 'You are verified and can start teaching.',
                    'variant' => 'success'
                ];
            } elseif ($tutorStatus === 'rejected') {
                $data['profile_status'] = [
                    'state' => 'rejected',
                    'title' => 'Profile needs updates',
                    'message' => 'Your submission was rejected. Please contact support or resubmit required details.',
                    'variant' => 'danger'
                ];
            }
        }

        $data['tutor_status'] = $tutorStatus;
        $data['registration_completed'] = $registrationCompleted;



        if (!$user || !$user['is_active']) {
            $data['subscription_status'] = 'waiting_admin_approval';
            $data['has_subscription'] = false;
            return view('trainer/dashboard', $data);
        }

        if ($tutorStatus === 'pending') {
            // Tutor profile exists but waiting for admin approval - show dashboard with pending status
            $data['subscription_status'] = 'waiting_admin_approval';
            $data['has_subscription'] = false;
            $data['is_pending_approval'] = true;
            $data['pending_message'] = 'Your account is pending admin approval. You can subscribe to plans once approved.';
            $data['available_plans'] = $this->getAvailablePlans();
        } elseif ($tutorStatus === 'approved' || $tutorStatus === 'active') {
            // Tutor is approved/active, check verification and subscription
            if ($isVerified != 1) {
                // Tutor approved but not fully verified yet - show dashboard with pending verification
                $data['subscription_status'] = 'waiting_verification';
                $data['has_subscription'] = false;
                $data['is_pending_approval'] = false;
                $data['pending_message'] = 'Your account is approved but pending final verification.';
                $data['available_plans'] = $this->getAvailablePlans();
            } else {
                // Tutor is fully approved and verified - check current subscription
                $subscriptionModel = new \App\Models\TutorSubscriptionModel();
                $subscription = $subscriptionModel->getSubscriptionWithPlan($userId);

                if (!$subscription) {
                    // No active subscription - redirect to subscription page
                    return redirect()->to('trainer/subscription')->with('info', 'Please select a subscription plan to start accepting bookings.');
                } else {
                // Has active subscription - show full dashboard
                    $data['subscription_status'] = 'subscribed';
                    $data['has_subscription'] = true;
                    $data['available_plans'] = $this->getAvailablePlans();
                    $data['current_subscription'] = $subscription;
                    $data['is_pending_approval'] = false;

                    // DEBUG LOGGING
                    error_log("DASHBOARD LOAD - User: $userId, PDF: " . ($subscription['allow_pdf_upload'] ?? 'null') . ", Video Solution: " . ($subscription['allow_video_solution'] ?? 'null') . ", Announcements: " . ($subscription['allow_announcements'] ?? 'null'));

                    // Get plan details for video submission access control
                    $planModel = new \App\Models\SubscriptionPlanModel();
                    $plan = $planModel->find($subscription['plan_id']);
                    $data['subscription'] = $subscription;
                    $data['plan'] = $plan;

                    // Calculate review count and average rating from reviews table
                    $db = \Config\Database::connect();

                    // Get review count
                    $reviewCountQuery = $db->table('reviews')
                        ->selectCount('id', 'review_count')
                        ->where('tutor_id', $userId)
                        ->get();
                    $reviewCountResult = $reviewCountQuery->getRow();
                    $data['review_count'] = $reviewCountResult ? (int) $reviewCountResult->review_count : 0;

                    // Get average rating
                    $ratingQuery = $db->table('reviews')
                        ->selectAvg('rating', 'avg_rating')
                        ->where('tutor_id', $userId)
                        ->get();
                    $ratingResult = $ratingQuery->getRow();
                    $data['average_rating'] = $ratingResult ? (float) $ratingResult->avg_rating : 0.0;

                    // Load usage tracking stats using subscription's billing period
                    $usageTrackingModel = new \App\Models\UsageTrackingModel();
                    $subscriptionPeriod = [
                        'start' => $subscription['current_period_start'],
                        'end' => $subscription['current_period_end']
                    ];
                    $data['usage_stats'] = $usageTrackingModel->getUsageStatsForPeriod($userId, $subscriptionPeriod['start'], $subscriptionPeriod['end']);
                }
            }
        } else {
            // Tutor rejected or in unknown state - show dashboard with status message
            $data['subscription_status'] = 'account_issue';
            $data['has_subscription'] = false;
            $data['is_pending_approval'] = false;
            $data['pending_message'] = 'Your account status is under review. Please contact support for assistance.';
            $data['available_plans'] = [];
        }

        return view('trainer/dashboard', $data);
    }

    /**
     * Calculate real statistics for trainer dashboard
     * Falls back to requested values if tables don't exist yet
     */
    private function calculateTrainerStats($userId)
    {
        try {
            // Load models
            $db = \Config\Database::connect();
            $userModel = $this->getUserModel();

            // 2. Calculate total courses/subjects taught by tutor
            // Count total courses/subjects from structured_subjects column only
            $totalCourses = 0;
            $user = $this->getUserModel()->find($userId);
            if ($user && !empty($user['structured_subjects'])) {
                $structuredSubjects = json_decode($user['structured_subjects'], true);
                if (is_array($structuredSubjects)) {
                    foreach ($structuredSubjects as $curriculum) {
                        if (isset($curriculum['levels']) && is_array($curriculum['levels'])) {
                            $totalCourses += count($curriculum['levels']);
                        }
                    }
                }
            }

            // 4. Calculate average rating from student reviews
            try {
                // Use the reviews table linked to bookings (current schema)
                $averageRating = $db->table('reviews')
                    ->select('AVG(rating) as avg_rating')
                    ->join('bookings', 'bookings.id = reviews.booking_id')
                    ->where('bookings.tutor_id', $userId)
                    ->get()
                    ->getRow();

                $rating = $averageRating && $averageRating->avg_rating ?
                    round(($averageRating->avg_rating / 5) * 100, 0) : 95;
            } catch (\Exception $e) {
                $rating = 95; // Table doesn't exist or other error - use requested value
            }

            return [
                'my_students' => 0, // Removed student data
                'my_courses' => (int) $totalCourses,
                'pending_assignments' => 0, // Removed assignments data
                'earnings' => '0',
                'avg_score' => $rating
            ];

        } catch (\Exception $e) {
            // Database error - return zeros (no artificial data)
            return [
                'my_students' => 0, // Removed student data
                'my_courses' => (int) $totalCourses ?? 0,
                'pending_assignments' => 0,
                'earnings' => '0',
                'avg_score' => 0
            ];
        }
    }

    /**
     * Assign a trial subscription to tutors (kept for potential future use)
     */
    private function assignTrialSubscription($userId)
    {
        try {
            $db = \Config\Database::connect();

            // Check if student trial plan exists and is active
            $trialPlan = $db->table('subscription_plans')
                ->where('name', 'Student Trial')
                ->where('is_active', 1)
                ->get()
                ->getRow();

            if (!$trialPlan) {
                log_message('error', 'Trial subscription plan not found or inactive');
                return null;
            }

            // Check if user already has any subscription record to avoid duplicates
            $existingSubscription = $db->table('tutor_subscriptions')
                ->where('user_id', $userId)
                ->where('plan_id', $trialPlan->id)
                ->get()
                ->getRow();

            if ($existingSubscription) {
                // Update existing trial subscription if it's not active
                if ($existingSubscription->status !== 'active') {
                    $db->table('tutor_subscriptions')
                        ->where('id', $existingSubscription->id)
                        ->update([
                            'status' => 'active',
                            'current_period_start' => date('Y-m-d H:i:s'),
                            'current_period_end' => date('Y-m-d H:i:s', strtotime('+30 days')),
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);

                    log_message('info', 'Reactivated trial subscription for user ID: ' . $userId);
                    return json_decode(json_encode($existingSubscription), true);
                }

                return json_decode(json_encode($existingSubscription), true);
            }

            // Create new trial subscription (if manual assignment is desired)
            $subscriptionData = [
                'user_id' => $userId,
                'plan_id' => $trialPlan->id, // Fixed field access
                'status' => 'active',
                'current_period_start' => date('Y-m-d H:i:s'),
                'current_period_end' => date('Y-m-d H:i:s', strtotime('+30 days')),
                'trial_end' => date('Y-m-d H:i:s', strtotime('+30 days')),
                'cancel_at_period_end' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $inserted = $db->table('tutor_subscriptions')->insert($subscriptionData);

            if ($inserted) {
                log_message('info', 'Manually assigned trial subscription to tutor user ID: ' . $userId);

                $newSub = $db->table('tutor_subscriptions')
                    ->where('id', $db->insertID())
                    ->get()
                    ->getRow();

                return json_decode(json_encode($newSub), true);
            } else {
                log_message('error', 'Failed to insert trial subscription for user ID: ' . $userId);
                return null;
            }

        } catch (\Exception $e) {
            log_message('error', 'Error assigning trial subscription to user ID ' . $userId . ': ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get available subscription plans for display in dashboard
     */
    private function getAvailablePlans()
    {
        try {
            $db = \Config\Database::connect();

            // Get all active plans, ordered by sort_order, then price
            $plans = $db->table('subscription_plans')
                ->where('is_active', 1)
                ->orderBy('sort_order', 'ASC')
                ->orderBy('price_monthly', 'ASC')
                ->get()
                ->getResultArray();

            // Format plans for display
            foreach ($plans as &$plan) {
                // Format features as array if stored as JSON
                if (isset($plan['features']) && !empty($plan['features'])) {
                    if (is_string($plan['features'])) {
                        // Try to decode JSON
                        $decoded = json_decode($plan['features'], true);
                        if ($decoded !== null) {
                            $plan['features'] = $decoded;
                        } else {
                            // If not JSON, try to parse as comma-separated
                            $plan['features'] = array_map('trim', explode(',', $plan['features']));
                        }
                    } elseif (!is_array($plan['features'])) {
                        $plan['features'] = [$plan['features']];
                    }
                } else {
                    $plan['features'] = ['Basic tutoring features'];
                }

                // Format price for display
                $plan['formatted_price'] = number_format($plan['price_monthly'], 0, ',', ',');

                // Add subscription URL - redirect to checkout
                $plan['subscribe_url'] = base_url('trainer/checkout/subscription/' . $plan['id']);
            }

            return $plans;

        } catch (\Exception $e) {
            log_message('error', 'Error fetching available plans: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get recent activity from multiple tables for admin dashboard
     */
    private function getRecentActivity()
    {
        $db = \Config\Database::connect();
        $activities = [];

        try {
            // Recent user registrations
            $recentUsers = $db->table('users')
                ->select('id, username, first_name, last_name, role, created_at')
                ->orderBy('created_at', 'DESC')
                ->limit(3)
                ->get()
                ->getResultArray();

            foreach ($recentUsers as $user) {
                $activities[] = [
                    'type' => 'user_registration',
                    'icon' => substr($user['first_name'], 0, 1) ?: substr($user['username'], 0, 1),
                    'title' => 'New ' . ucfirst($user['role']) . ' Registration',
                    'description' => $user['first_name'] . ' ' . $user['last_name'] . ' joined the platform',
                    'time' => $this->timeAgo($user['created_at']),
                    'timestamp' => strtotime($user['created_at'])
                ];
            }





            // Recent subscriptions
            $recentSubscriptions = $db->table('tutor_subscriptions ts')
                ->select('ts.created_at, u.first_name, u.last_name, sp.name as plan_name')
                ->join('users u', 'u.id = ts.user_id')
                ->join('subscription_plans sp', 'sp.id = ts.plan_id')
                ->where('ts.status', 'active')
                ->orderBy('ts.created_at', 'DESC')
                ->limit(2)
                ->get()
                ->getResultArray();

            foreach ($recentSubscriptions as $subscription) {
                $activities[] = [
                    'type' => 'subscription',
                    'icon' => '💳',
                    'title' => 'Subscription Activated',
                    'description' => $subscription['first_name'] . ' ' . $subscription['last_name'] . ' activated ' . $subscription['plan_name'] . ' plan',
                    'time' => $this->timeAgo($subscription['created_at']),
                    'timestamp' => strtotime($subscription['created_at'])
                ];
            }

        } catch (\Exception $e) {
            // If tables don't exist yet, return some fallback activities
            $activities = [
                [
                    'type' => 'system',
                    'icon' => 'ℹ️',
                    'title' => 'System Initialized',
                    'description' => 'TutorConnect Malawi platform is ready',
                    'time' => 'Just now',
                    'timestamp' => time()
                ]
            ];
        }

        // Sort activities by timestamp (most recent first) and limit to 6
        usort($activities, function($a, $b) {
            return $b['timestamp'] <=> $a['timestamp'];
        });

        return array_slice($activities, 0, 6);
    }

    /**
     * Convert timestamp to human-readable time ago format
     */
    private function timeAgo($datetime)
    {
        $timestamp = strtotime($datetime);
        $now = time();
        $diff = $now - $timestamp;

        if ($diff < 60) {
            return 'Just now';
        } elseif ($diff < 3600) {
            $minutes = floor($diff / 60);
            return $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
        } elseif ($diff < 604800) {
            $days = floor($diff / 86400);
            return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
        } else {
            return date('M j, Y', $timestamp);
        }
    }

    private function convertIniToBytes($val)
    {
        $val = trim($val);
        $last = strtolower($val[strlen($val) - 1] ?? '');
        $num = (int) $val;

        switch ($last) {
            case 'g':
                $num *= 1024;
            // no break
            case 'm':
                $num *= 1024;
            // no break
            case 'k':
                $num *= 1024;
        }

        return $num;
    }

    private function getUserModel()
    {
        return new \App\Models\User();
    }

    public function completeProfile()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        $userModel = $this->getUserModel();
        $user = $userModel->find(session()->get('user_id'));

        if (!$user) {
            return redirect()->to('/login')->with('error', 'User not found');
        }

        $data['title'] = 'Complete Your Profile - TutorConnect Malawi';
        $data['user'] = $user;

        // Check which documents are missing
        $documents = json_decode($user['verification_documents'], true) ?? [];
        $uploadedTypes = is_array($documents) ? array_column($documents, 'document_type') : [];

        $data['required_documents'] = [
            'national_id' => [
                'label' => 'National ID',
                'uploaded' => in_array('national_id', $uploadedTypes),
                'icon' => 'fa-id-card'
            ],
            'academic_certificates' => [
                'label' => 'Academic Certificates',
                'uploaded' => in_array('academic_certificates', $uploadedTypes),
                'icon' => 'fa-graduation-cap'
            ],
            'police_clearance' => [
                'label' => 'Police Clearance',
                'uploaded' => in_array('police_clearance', $uploadedTypes),
                'icon' => 'fa-shield-alt'
            ]
        ];

        $data['has_profile_picture'] = !empty($user['profile_picture']);

        return view('trainer/complete_profile', $data);
    }

    public function uploadDocuments()
    {
        if (!session()->get('user_id')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please login first'
            ]);
        }

        $userModel = $this->getUserModel();
        $userId = session()->get('user_id');
        $user = $userModel->find($userId);

        if (!$user) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        // Get existing documents
        $existingDocs = json_decode($user['verification_documents'], true) ?? [];

        $uploadedFiles = [];
        $errors = [];

        // Handle file uploads
        $files = $this->request->getFiles();

        foreach ($files['documents'] as $file) {
            if ($file->isValid() && !$file->hasMoved()) {
                $docType = $this->request->getPost('doc_type_' . $file->getName());

                // Validate file type
                if (!in_array($file->getExtension(), ['jpg', 'jpeg', 'png', 'pdf'])) {
                    $errors[] = 'Invalid file type for ' . $file->getName();
                    continue;
                }

                // Validate file size (5MB max)
                if ($file->getSize() > 5 * 1024 * 1024) {
                    $errors[] = $file->getName() . ' exceeds 5MB limit';
                    continue;
                }

                // Generate unique filename
                $newName = $docType . '_' . time() . '_' . rand(1000, 9999) . '.' . $file->getExtension();
                $file->move(WRITEPATH . '../public/uploads/documents', $newName);

                $uploadedFiles[] = [
                    'document_type' => $docType,
                    'file_path' => 'uploads/documents/' . $newName,
                    'original_filename' => $file->getClientName(),
                    'uploaded_at' => date('Y-m-d H:i:s')
                ];
            }
        }

        // Handle profile picture
        $profilePic = $this->request->getFile('profile_picture');
        if ($profilePic && $profilePic->isValid() && !$profilePic->hasMoved()) {
            if (!in_array($profilePic->getExtension(), ['jpg', 'jpeg', 'png'])) {
                $errors[] = 'Invalid profile picture type';
            } elseif ($profilePic->getSize() > 2 * 1024 * 1024) {
                $errors[] = 'Profile picture exceeds 2MB limit';
            } else {
                $newName = 'profile_' . $userId . '_' . time() . '.' . $profilePic->getExtension();
                $targetPath = ROOTPATH . 'public/uploads/profile_photos';

                if (!is_dir($targetPath)) {
                    mkdir($targetPath, 0755, true);
                }

                $profilePic->move($targetPath, $newName);
                $userModel->update($userId, ['profile_picture' => 'uploads/profile_photos/' . $newName]);
            }
        }

        // Merge with existing documents (avoid duplicates)
        if (!empty($uploadedFiles)) {
            foreach ($uploadedFiles as $newDoc) {
                // Remove old document of same type
                $existingDocs = array_filter($existingDocs, function($doc) use ($newDoc) {
                    return $doc['document_type'] !== $newDoc['document_type'];
                });
                $existingDocs[] = $newDoc;
            }

            $userModel->update($userId, [
                'verification_documents' => json_encode(array_values($existingDocs))
            ]);
        }

        if (!empty($errors)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Some files failed to upload',
                'errors' => $errors
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Documents uploaded successfully!',
            'redirect' => base_url('trainer/dashboard')
        ]);
    }

    /**
     * Submit complete profile - handles all 6 steps of profile completion
     */
    public function submitCompleteProfile()
    {
        try {
            $method = $this->request->getMethod(true); // upper-case
            if ($method !== 'POST') {
                log_message('error', 'Complete profile rejected: invalid method {method}', ['method' => $method]);
                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'message' => 'Invalid request type'
                ]);
            }

            $userId = session()->get('user_id');
            if (!$userId) {
                return $this->response->setStatusCode(401)->setJSON([
                    'success' => false,
                    'message' => 'Not authenticated'
                ]);
            }

            $userModel = $this->getUserModel();
            $errors = [];

            // Debug: capture incoming fields/files and PHP upload limits
            $contentLength = (int) ($this->request->getHeaderLine('Content-Length') ?: 0);
            $postMaxBytes = $this->convertIniToBytes(ini_get('post_max_size'));
            $uploadMaxBytes = $this->convertIniToBytes(ini_get('upload_max_filesize'));

            log_message('info', 'Complete profile submit for user {userId}. Method {method}. Content-Length {len}. post_max_size {pmax}. upload_max_filesize {umax}. Fields: {fields}. Files: {files}', [
                'userId' => $userId,
                'method' => $method,
                'len' => $contentLength,
                'pmax' => $postMaxBytes,
                'umax' => $uploadMaxBytes,
                'fields' => json_encode($this->request->getPost()),
                'files' => json_encode(array_keys($this->request->getFiles() ?? []))
            ]);

            // If post body likely exceeded PHP limits, bail early with clear message
            if ($contentLength > 0 && $postMaxBytes > 0 && $contentLength > $postMaxBytes && empty($this->request->getPost()) && empty($this->request->getFiles())) {
                log_message('error', 'Complete profile rejected: payload {len} exceeds post_max_size {max}', [
                    'len' => $contentLength,
                    'max' => $postMaxBytes
                ]);

                return $this->response->setStatusCode(413)->setJSON([
                    'success' => false,
                    'message' => 'Upload too large. Please keep total upload under server limit.',
                    'hint' => 'Increase PHP post_max_size/upload_max_filesize or reduce file sizes.'
                ]);
            }

        // Step 1: Teaching Information
        $teaching_mode = $this->request->getPost('teaching_mode');
        $experience_years = $this->request->getPost('experience_years');
        $bio = $this->request->getPost('bio');

        if (empty($teaching_mode) || !isset($experience_years) || trim($experience_years) === '' || empty($bio)) {
            $errors[] = 'Teaching information is incomplete';
        }

        // Step 2: Cover Photo
        $coverPhoto = $this->request->getFile('cover_photo');
        $coverPhotoPath = null;

        if ($coverPhoto && $coverPhoto->isValid() && !$coverPhoto->hasMoved()) {
            if (!in_array($coverPhoto->getExtension(), ['jpg', 'jpeg', 'png', 'gif'])) {
                $errors[] = 'Invalid cover photo type. Only JPG, PNG, GIF allowed.';
            } elseif ($coverPhoto->getSize() > 10 * 1024 * 1024) {
                $errors[] = 'Cover photo exceeds 10MB limit';
            } else {
                $newName = 'cover_' . $userId . '_' . time() . '.' . $coverPhoto->getExtension();
                $coverPhoto->move(WRITEPATH . '../public/uploads/profile_photos', $newName);
                $coverPhotoPath = 'uploads/profile_photos/' . $newName;
            }
        }

        // Step 3: Profile Picture
        $profilePicture = $this->request->getFile('profile_picture');
        $profilePicturePath = null;

        if ($profilePicture && $profilePicture->isValid() && !$profilePicture->hasMoved()) {
            if (!in_array($profilePicture->getExtension(), ['jpg', 'jpeg', 'png', 'gif'])) {
                $errors[] = 'Invalid profile picture type. Only JPG, PNG, GIF allowed.';
            } elseif ($profilePicture->getSize() > 5 * 1024 * 1024) {
                $errors[] = 'Profile picture exceeds 5MB limit';
            } else {
                $newName = 'profile_' . $userId . '_' . time() . '.' . $profilePicture->getExtension();
                $profilePicture->move(WRITEPATH . '../public/uploads/profile_photos', $newName);
                $profilePicturePath = 'uploads/profile_photos/' . $newName;
            }
        } else {
            $errors[] = 'Profile picture is required';
        }

        // Subjects step removed - no longer required

        // Step 5: Introduction Video (Optional)
        $introVideo = $this->request->getFile('intro_video');
        $introVideoPath = null;

        if ($introVideo && $introVideo->isValid() && !$introVideo->hasMoved()) {
            $videoExt = strtolower($introVideo->getExtension());
            if (!in_array($videoExt, ['mp4', 'webm', 'ogv', 'avi', 'mov'])) {
                $errors[] = 'Invalid video type. Only MP4, WebM, OGV, AVI, MOV allowed. (Got: ' . $videoExt . ')';
            } elseif ($introVideo->getSize() > 50 * 1024 * 1024) {
                $errors[] = 'Video exceeds 50MB limit';
            } else {
                // Increase memory limit for video processing
                $currentMemoryLimit = ini_get('memory_limit');
                ini_set('memory_limit', '512M'); // Temporarily increase for video processing

                // Set execution time limit for large file processing
                $currentTimeLimit = ini_set('max_execution_time', 600); // 10 minutes for large uploads

                try {
                    $newName = 'intro_video_' . $userId . '_' . time() . '.' . $videoExt;
                    $targetPath = WRITEPATH . '../public/uploads/videos';

                    // Ensure upload directory exists
                    if (!is_dir($targetPath)) {
                        mkdir($targetPath, 0755, true);
                    }

                    // Move file directly without additional processing
                    $introVideo->move($targetPath, $newName);
                    $introVideoPath = 'uploads/videos/' . $newName;

                    // Log successful upload
                    log_message('info', 'Video uploaded successfully: ' . $newName . ' (' . number_format($introVideo->getSize() / 1024 / 1024, 2) . 'MB)');

                } catch (\Exception $e) {
                    $errors[] = 'Failed to upload video: ' . $e->getMessage();
                    log_message('error', 'Video upload failed: ' . $e->getMessage());
                } finally {
                    // Restore original memory limit
                    ini_set('memory_limit', $currentMemoryLimit);
                    if ($currentTimeLimit !== false) {
                        ini_set('max_execution_time', $currentTimeLimit);
                    }
                }
            }
        }

        // Step 6: Contact & Availability
        $whatsappNumber = $this->request->getPost('whatsapp_number');
        $availabilityData = $this->request->getPost('availability');
        $availabilityArray = [];

        if ($availabilityData) {
            $availabilityArray = json_decode($availabilityData, true);
        }

        // Step 7: Documents
        $documents = [];
        $requiredDocs = ['national_id', 'academic_certificates', 'teaching_qualification'];
        $optionalDocs = ['police_clearance'];

        // Temporarily increase memory limit for document processing
        $currentMemoryLimit = ini_get('memory_limit');
        ini_set('memory_limit', '384M'); // Increase for multiple document uploads

        try {
            // Process required documents
            foreach ($requiredDocs as $docType) {
                $file = $this->request->getFile($docType);

                if (!$file || !$file->isValid() || $file->hasMoved()) {
                    $errors[] = ucfirst(str_replace('_', ' ', $docType)) . ' is required';
                    continue;
                }

                if (!in_array($file->getExtension(), ['pdf', 'jpg', 'jpeg', 'png'])) {
                    $errors[] = ucfirst(str_replace('_', ' ', $docType)) . ' must be PDF or image file';
                    continue;
                }

                if ($file->getSize() > 10 * 1024 * 1024) {
                    $errors[] = ucfirst(str_replace('_', ' ', $docType)) . ' exceeds 10MB limit';
                    continue;
                }

                $newName = $docType . '_' . $userId . '_' . time() . '_' . rand(1000, 9999) . '.' . $file->getExtension();
                $targetPath = WRITEPATH . '../public/uploads/documents';

                // Ensure upload directory exists
                if (!is_dir($targetPath)) {
                    mkdir($targetPath, 0755, true);
                }

                $file->move($targetPath, $newName);

                $documents[] = [
                    'document_type' => $docType,
                    'file_path' => 'uploads/documents/' . $newName,
                    'original_filename' => $file->getClientName(),
                    'uploaded_at' => date('Y-m-d H:i:s')
                ];

                log_message('info', 'Document uploaded: ' . $docType . ' (' . number_format($file->getSize() / 1024 / 1024, 2) . 'MB)');
            }

            // Process optional documents
            foreach ($optionalDocs as $docType) {
                $file = $this->request->getFile($docType);

                if ($file && $file->isValid() && !$file->hasMoved()) {
                    if (!in_array($file->getExtension(), ['pdf', 'jpg', 'jpeg', 'png'])) {
                        $errors[] = ucfirst(str_replace('_', ' ', $docType)) . ' must be PDF or image file';
                        continue;
                    }

                    if ($file->getSize() > 10 * 1024 * 1024) {
                        $errors[] = ucfirst(str_replace('_', ' ', $docType)) . ' exceeds 10MB limit';
                        continue;
                    }

                    $newName = $docType . '_' . $userId . '_' . time() . '_' . rand(1000, 9999) . '.' . $file->getExtension();
                    $targetPath = WRITEPATH . '../public/uploads/documents';

                    // Ensure upload directory exists
                    if (!is_dir($targetPath)) {
                        mkdir($targetPath, 0755, true);
                    }

                    $file->move($targetPath, $newName);

                    $documents[] = [
                        'document_type' => $docType,
                        'file_path' => 'uploads/documents/' . $newName,
                        'original_filename' => $file->getClientName(),
                        'uploaded_at' => date('Y-m-d H:i:s')
                    ];

                    log_message('info', 'Optional document uploaded: ' . $docType . ' (' . number_format($file->getSize() / 1024 / 1024, 2) . 'MB)');
                }
            }

        } catch (\Exception $e) {
            $errors[] = 'Failed to upload documents: ' . $e->getMessage();
            log_message('error', 'Document upload failed: ' . $e->getMessage());
        } finally {
            // Restore original memory limit
            ini_set('memory_limit', $currentMemoryLimit);
        }

            if (!empty($errors)) {
                log_message('error', 'Complete profile validation failed for user {userId}: {errors}', [
                    'userId' => $userId,
                    'errors' => json_encode($errors)
                ]);

                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $errors,
                    'debug' => [
                        'teaching_mode' => $teaching_mode,
                        'experience_years' => $experience_years,
                        'bio' => substr($bio ?? '', 0, 50),
                        'intro_video' => $introVideo ? 'present' : 'missing'
                    ]
                ]);
            }

        // All validation passed, update user profile
        // Subjects step removed - no structured subjects data needed

        $updateData = [
            'teaching_mode' => $teaching_mode,
            'experience_years' => (int) $experience_years,
            'bio' => $bio,
            'verification_documents' => json_encode($documents),
            'whatsapp_number' => $whatsappNumber,
            'availability' => json_encode($availabilityArray),
            'registration_completed' => 1
        ];

        if ($introVideoPath) {
            $updateData['bio_video'] = $introVideoPath;
        }

        if ($coverPhotoPath) {
            $updateData['cover_photo'] = $coverPhotoPath;
        }

        if ($profilePicturePath) {
            $updateData['profile_picture'] = $profilePicturePath;
        }

        $userModel->update($userId, $updateData);

        // Subjects data is now stored ONLY in structured_subjects column
        // No longer saving to separate tutor_subjects table

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Profile submitted successfully!',
                'redirect' => base_url('trainer/dashboard')
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'Complete profile submit exception: {msg} @ {file}:{line}', [
                'msg' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Server error while submitting profile. Please try again.',
            ]);
        }
    }
}
