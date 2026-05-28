<?php

namespace App\Controllers;

use App\Models\SubscriptionPlanModel;
use App\Models\TutorSubscriptionModel;
use App\Models\UniversityCollegeTutorModel;
use App\Models\User;

class UniversityPortal extends BaseController
{
    private const SERVICE_CATEGORIES = [
        'Research & Dissertation Support' => [
            'Methodology guidance',
            'Data cleaning',
            'Proposal structuring',
            'Referencing support',
            'Data analysis interpretation',
        ],
        'ICT & Programming' => [
            'Python',
            'Java',
            'Web Development',
            'Database Systems',
            'Microsoft Excel',
            'Power BI Basics',
            'Data Science Basics',
            'Introduction to AI Tools',
            'Data Visualization',
        ],
        'Accounting & Finance' => [
            'Financial Accounting',
            'Cost Accounting',
            'Taxation Basics',
            'Economics',
            'Finance',
        ],
        'Mathematics' => [
            'Calculus',
            'Algebra',
            'Engineering Mathematics',
            'Business Mathematics',
        ],
        'Statistics & Data Analysis' => [
            'SPSS',
            'STATA',
            'Excel Data Analysis',
            'Quantitative Methods',
            'Research Methods',
            'Data Analysis for Dissertations',
        ],
    ];

    private const AVAILABILITY_DAYS = [
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday',
        'Sunday',
    ];

    private const PREFERRED_TIME_OPTIONS = [
        'Early mornings',
        'Weekday mornings',
        'Weekday afternoons',
        'Weekday evenings',
        'Saturday mornings',
        'Saturday afternoons',
        'Sunday afternoons',
        'Flexible by appointment',
    ];

    protected $userModel;
    protected $universityTutorModel;
    protected $tutorSubscriptionModel;
    protected $subscriptionPlanModel;

    public function __construct()
    {
        helper(['url', 'form']);
        $this->userModel = new User();
        $this->universityTutorModel = new UniversityCollegeTutorModel();
        $this->tutorSubscriptionModel = new TutorSubscriptionModel();
        $this->subscriptionPlanModel = new SubscriptionPlanModel();
    }

    public function dashboard()
    {
        $context = $this->getPortalContext();
        if (!is_array($context)) {
            return $context;
        }

        $user = $context['user'];
        $profile = $context['profile'];
        $currentSubscription = $this->tutorSubscriptionModel->getSubscriptionWithPlan((int) $user['id']);
        $profileCompletionGaps = $this->universityTutorModel->getProfileCompletionGaps($profile);
        $profileReadyForReview = $profileCompletionGaps === [];
        $canAccessSubscription = $this->canAccessSubscription($user, $profile);
        $applicationStatus = $this->buildApplicationStatus($profile);
        $subscriptionCountData = $this->buildPortalSubscriptionCounts();

        $data = [
            'title' => 'University Portal - TutorConnect Malawi',
            'user' => $user,
            'profile' => $profile,
            'application_status' => $applicationStatus,
            'linked_account' => [
                'email_verified' => !empty($user['email_verified_at']) || (int) ($user['is_verified'] ?? 0) === 1,
                'is_active' => (int) ($user['is_active'] ?? 0) === 1,
                'tutor_status' => $user['tutor_status'] ?? 'pending',
                'subscription_plan' => $currentSubscription['plan_name'] ?? ($user['subscription_plan'] ?? 'Pending Selection'),
            ],
            'institutions' => $this->decodeList($profile['institutions_json'] ?? null),
            'service_areas' => $this->decodeList($profile['service_areas_json'] ?? null),
            'references' => $this->decodeList($profile['references_json'] ?? null),
            'available_days' => $this->decodeList($profile['available_days_json'] ?? null),
            'preferred_times' => $this->decodeList($profile['preferred_times_json'] ?? null),
            'profile_completion_gaps' => $profileCompletionGaps,
            'profile_ready_for_review' => $profileReadyForReview,
            'profile_completion_url' => site_url('university-portal/complete-profile'),
            'subscription_url' => site_url('university-portal/subscription'),
            'can_access_subscription' => $canAccessSubscription,
            'current_subscription' => $currentSubscription,
            'subscription_counts' => $subscriptionCountData['summary'],
            'next_steps' => $this->buildNextSteps($user, $profile, $canAccessSubscription, $currentSubscription),
        ];

        return view('university_portal/dashboard', $data);
    }

    public function completeProfile()
    {
        $context = $this->getPortalContext();
        if (!is_array($context)) {
            return $context;
        }

        $user = $context['user'];
        $profile = $context['profile'];

        return view('university_portal/complete_profile', [
            'title' => 'Complete University Profile - TutorConnect Malawi',
            'user' => $user,
            'profile' => $profile,
            'service_categories' => self::SERVICE_CATEGORIES,
            'availability_days' => self::AVAILABILITY_DAYS,
            'preferred_time_options' => self::PREFERRED_TIME_OPTIONS,
            'institutions_text' => implode(PHP_EOL, $this->decodeList($profile['institutions_json'] ?? null)),
            'references_text' => implode(PHP_EOL, $this->decodeList($profile['references_json'] ?? null)),
            'selected_preferred_times' => $this->decodeList($profile['preferred_times_json'] ?? null),
            'selected_service_areas' => $this->decodeList($profile['service_areas_json'] ?? null),
            'selected_days' => $this->decodeList($profile['available_days_json'] ?? null),
            'certification_files' => $this->decodeList($profile['certification_files_json'] ?? null),
            'profile_completion_gaps' => $this->universityTutorModel->getProfileCompletionGaps($profile),
        ]);
    }

    public function saveProfile()
    {
        if (strtolower($this->request->getMethod()) !== 'post') {
            return redirect()->to('university-portal/complete-profile');
        }

        $context = $this->getPortalContext();
        if (!is_array($context)) {
            return $context;
        }

        $user = $context['user'];
        $profile = $context['profile'];
        $postData = $this->request->getPost();
        $returnStep = $this->normalizeWizardStep($postData['wizard_step'] ?? 5);
        $workStatus = trim((string) ($postData['work_status'] ?? ''));
        $isEmployed = $workStatus === 'Employed';
        $employerName = $isEmployed ? trim((string) ($postData['employer_name'] ?? '')) : '';
        $employerContact = $isEmployed ? trim((string) ($postData['employer_contact'] ?? '')) : '';

        $institutions = $this->normalizeTextareaEntries($postData['institutions'] ?? '', 4);
        $references = $this->normalizeTextareaEntries($postData['references'] ?? '', 6);
        $preferredTimes = $this->normalizePreferredTimes($postData['preferred_times'] ?? []);
        $serviceAreas = $this->normalizeServiceAreas($postData['service_areas'] ?? []);
        $availabilityDays = $this->normalizeLimitedEntries($postData['available_days'] ?? [], 7);

        $validation = \Config\Services::validation();
        $validation->setRules([
            'year_of_study_or_graduation' => 'required|max_length[50]',
            'bio' => 'required|min_length[40]|max_length[2000]',
            'teaching_mode' => 'required|in_list[Online,Physical,Both]',
            'city_location' => 'required|max_length[150]',
            'work_status' => 'permit_empty|in_list[Employed,Not Employed]',
            'employer_name' => 'permit_empty|max_length[150]',
            'employer_contact' => 'permit_empty|max_length[100]',
            'hourly_rate' => 'permit_empty|decimal',
            'consultation_package_rate' => 'permit_empty|decimal',
            'dissertation_package_rate' => 'permit_empty|decimal',
            'exam_preparation_rate' => 'permit_empty|decimal',
        ]);

        $errors = [];
        if (!$validation->run($postData)) {
            $errors = $validation->getErrors();
        }

        if ($institutions === []) {
            $errors['institutions'] = 'Add at least one institution attended or currently attending.';
        }

        if ($serviceAreas === []) {
            $errors['service_areas'] = 'Select at least one service area.';
        }

        if ($availabilityDays === []) {
            $errors['available_days'] = 'Select at least one available day.';
        }

        if ($preferredTimes === []) {
            $errors['preferred_times'] = 'Select at least one preferred teaching time.';
        }

        if ($references !== [] && count($references) < 3) {
            $errors['references'] = 'References are optional. If included, please provide at least three contacts, one per line.';
        }

        if ($isEmployed && $employerName === '') {
            $errors['employer_name'] = 'Employer name is required when employed.';
        }

        if ($isEmployed && $employerContact === '') {
            $errors['employer_contact'] = 'Employer contact is required when employed.';
        }

        $profilePicture = $this->request->getFile('profile_picture');
        $nationalIdFile = $this->request->getFile('national_id_file');
        $certificationFiles = $this->request->getFileMultiple('certification_files');

        $hasExistingProfilePicture = trim((string) ($profile['profile_picture'] ?? '')) !== '';
        $hasExistingNationalId = trim((string) ($profile['national_id_file'] ?? '')) !== '';
        $existingCertificationPaths = $this->decodeList($profile['certification_files_json'] ?? null);

        if (!$hasExistingProfilePicture && !$this->isValidUpload($profilePicture)) {
            $errors['profile_picture'] = 'Profile picture is required.';
        }

        if ($this->isValidUpload($profilePicture) && !in_array($this->getUploadedExtension($profilePicture), ['jpg', 'jpeg', 'png'], true)) {
            $errors['profile_picture'] = 'Profile picture must be a JPG or PNG image.';
        }

        if (!$hasExistingNationalId && !$this->isValidUpload($nationalIdFile)) {
            $errors['national_id_file'] = 'National ID file is required.';
        }

        if ($this->isValidUpload($nationalIdFile) && !in_array($this->getUploadedExtension($nationalIdFile), ['jpg', 'jpeg', 'png', 'pdf'], true)) {
            $errors['national_id_file'] = 'National ID must be an image or PDF file.';
        }

        $validCertificationFiles = [];
        if (is_array($certificationFiles)) {
            foreach ($certificationFiles as $file) {
                if ($this->isValidUpload($file)) {
                    if (!in_array($this->getUploadedExtension($file), ['jpg', 'jpeg', 'png', 'pdf'], true)) {
                        $errors['certification_files'] = 'Certification files must be image or PDF files.';
                        continue;
                    }

                    $validCertificationFiles[] = $file;
                }
            }
        }

        if ($existingCertificationPaths === [] && $validCertificationFiles === []) {
            $errors['certification_files'] = 'Upload at least one academic transcript or certification file.';
        }

        if (count($existingCertificationPaths) + count($validCertificationFiles) > 4) {
            $errors['certification_files'] = 'You can keep up to 4 certification files in total.';
        }

        if ($errors !== []) {
            return redirect()
                ->to('university-portal/complete-profile')
                ->withInput()
                ->with('errors', $errors)
                ->with('wizard_return_step', $returnStep)
                ->with('wizard_error_step', $this->resolveWizardErrorStep(array_keys($errors)));
        }

        try {
            $profilePicturePath = $hasExistingProfilePicture ? $profile['profile_picture'] : '';
            if ($this->isValidUpload($profilePicture)) {
                $profilePicturePath = $this->moveUploadedFile($profilePicture, 'public/uploads/university_college/profile_pictures');
            }

            $nationalIdPath = $hasExistingNationalId ? $profile['national_id_file'] : '';
            if ($this->isValidUpload($nationalIdFile)) {
                $nationalIdPath = $this->moveUploadedFile($nationalIdFile, 'public/uploads/university_college/national_ids');
            }

            $certificationPaths = $existingCertificationPaths;
            foreach ($validCertificationFiles as $file) {
                $certificationPaths[] = $this->moveUploadedFile($file, 'public/uploads/university_college/certifications');
            }
            $certificationPaths = array_slice(array_values(array_unique($certificationPaths)), 0, 4);

            $wasApproved = (($profile['status'] ?? '') === 'approved')
                || in_array((string) ($user['tutor_status'] ?? ''), ['approved', 'active'], true);

            $newProfileStatus = $wasApproved ? 'approved' : 'pending_review';
            $newTutorStatus = $wasApproved ? ((string) ($user['tutor_status'] ?? 'approved') ?: 'approved') : 'pending';

            $this->universityTutorModel->update((int) $profile['id'], [
                'profile_picture' => $profilePicturePath,
                'national_id_file' => $nationalIdPath,
                'certification_files_json' => json_encode($certificationPaths),
                'institutions_json' => json_encode($institutions),
                'specializations_json' => json_encode([]),
                'service_areas_json' => json_encode($serviceAreas),
                'year_of_study_or_graduation' => trim((string) $postData['year_of_study_or_graduation']),
                'bio' => trim((string) $postData['bio']),
                'references_json' => json_encode($references),
                'work_status' => $workStatus ?: null,
                'employer_name' => $employerName ?: null,
                'employer_contact' => $employerContact ?: null,
                'available_days_json' => json_encode($availabilityDays),
                'preferred_times_json' => json_encode($preferredTimes),
                'teaching_mode' => trim((string) $postData['teaching_mode']),
                'city_location' => trim((string) $postData['city_location']),
                'hourly_rate' => $this->normalizeDecimal($postData['hourly_rate'] ?? null),
                'consultation_package_rate' => $this->normalizeDecimal($postData['consultation_package_rate'] ?? null),
                'dissertation_package_rate' => $this->normalizeDecimal($postData['dissertation_package_rate'] ?? null),
                'exam_preparation_rate' => $this->normalizeDecimal($postData['exam_preparation_rate'] ?? null),
                'status' => $newProfileStatus,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            $this->userModel->update((int) $user['id'], [
                'phone' => $profile['phone'] ?? ($user['phone'] ?? null),
                'district' => trim((string) $postData['city_location']),
                'location' => trim((string) $postData['city_location']),
                'teaching_mode' => trim((string) $postData['teaching_mode']),
                'bio' => trim((string) $postData['bio']),
                'is_employed' => $isEmployed ? 1 : 0,
                'school_name' => $employerName ?: null,
                'registration_completed' => 1,
                'tutor_status' => $newTutorStatus,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            $message = $wasApproved
                ? 'University profile updated successfully.'
                : 'University profile submitted successfully. The admin team will review your full details before approval.';

            return redirect()->to('university-portal/dashboard')->with('success', $message);
        } catch (\Throwable $e) {
            log_message('error', 'University portal profile save failed: ' . $e->getMessage());

            return redirect()
                ->to('university-portal/complete-profile')
                ->withInput()
                ->with('error', 'We could not save your university profile right now. Please try again.')
                ->with('wizard_return_step', $returnStep);
        }
    }

    public function saveProfileDraft()
    {
        if (strtolower($this->request->getMethod()) !== 'post') {
            return $this->response->setStatusCode(405)->setJSON([
                'success' => false,
                'message' => 'Draft saving requires a valid profile update request.',
            ]);
        }

        $context = $this->getPortalContext();
        if (!is_array($context)) {
            return $this->response->setStatusCode(401)->setJSON([
                'success' => false,
                'message' => 'Your session has expired. Please sign in again before saving this profile.',
            ]);
        }

        $user = $context['user'];
        $profile = $context['profile'];
        $postData = $this->request->getPost();
        $returnStep = $this->normalizeWizardStep($postData['wizard_step'] ?? 1, 1);

        try {
            $draftErrors = $this->persistProfileDraft($user, $profile, $postData);
            if ($draftErrors !== []) {
                return $this->response->setStatusCode(422)->setJSON([
                    'success' => false,
                    'message' => reset($draftErrors) ?: 'Review the highlighted information before saving this step.',
                    'errors' => $draftErrors,
                ]);
            }

            $savedProfile = $this->universityTutorModel->find((int) $profile['id']) ?: $profile;
            $savedCertifications = $this->decodeList($savedProfile['certification_files_json'] ?? null);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Draft saved.',
                'next_step' => min($returnStep + 1, 5),
                'files' => [
                    'profile_picture' => trim((string) ($savedProfile['profile_picture'] ?? '')) !== '',
                    'national_id' => trim((string) ($savedProfile['national_id_file'] ?? '')) !== '',
                    'certifications' => count($savedCertifications),
                ],
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'University portal draft save failed: ' . $e->getMessage());

            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'We could not save this step right now. Please try again.',
            ]);
        }
    }

    public function subscription()
    {
        $context = $this->getPortalContext();
        if (!is_array($context)) {
            return $context;
        }

        $user = $context['user'];
        $profile = $context['profile'];

        if (!$this->canAccessSubscription($user, $profile)) {
            return redirect()->to('university-portal/dashboard')->with('info', 'Admin approval is required before you can choose a subscription plan.');
        }

        $this->ensureDefaultUniversityPlans();

        $subscriptionCountData = $this->buildPortalSubscriptionCounts();
        $availablePlans = $this->subscriptionPlanModel->getActivePlansForPortal('university');
        $availablePlans = $this->attachPlanSubscriptionCounts($availablePlans, $subscriptionCountData['plans']);

        return view('university_portal/subscription', [
            'title' => 'University Subscription Plans - TutorConnect Malawi',
            'user' => $user,
            'profile' => $profile,
            'available_plans' => $availablePlans,
            'current_subscription' => $this->tutorSubscriptionModel->getSubscriptionWithPlan((int) $user['id']),
            'subscription_counts' => $subscriptionCountData['summary'],
            'max_billing_months' => $this->tutorSubscriptionModel->getMaxBillingMonths(),
            'checkout_base_url' => base_url('university-portal/checkout/subscription/'),
            'dashboard_url' => base_url('university-portal/dashboard'),
            'complete_profile_url' => base_url('university-portal/complete-profile'),
            'public_module_url' => base_url('university-college-support'),
        ]);
    }

    private function ensureDefaultUniversityPlans(): void
    {
        $now = date('Y-m-d H:i:s');

        $plans = [
            [
                'name' => 'Basic',
                'description' => 'New tutors joining the platform',
                'price_monthly' => 2000.00,
                'features' => json_encode([
                    'Approved university tutor profile listing',
                    'Standard request matching eligibility',
                    'Basic placement after Standard and Premium tutors',
                ]),
                'max_profile_views' => 100,
                'max_clicks' => 20,
                'max_subjects' => 5,
                'badge_level' => 'beginner',
                'search_ranking' => 'low',
                'show_whatsapp' => 0,
                'email_marketing_access' => 0,
                'allow_video_upload' => 0,
                'allow_pdf_upload' => 0,
                'allow_announcements' => 0,
                'allow_video_solution' => 0,
                'portal_type' => 'university',
                'is_active' => 1,
                'sort_order' => 1,
            ],
            [
                'name' => 'Standard',
                'description' => 'Active tutors seeking more visibility',
                'price_monthly' => 5000.00,
                'features' => json_encode([
                    'Enhanced university tutor profile listing',
                    'Higher placement than Basic in public listings',
                    'Higher priority in matching order',
                    'WhatsApp contact visibility where enabled',
                ]),
                'max_profile_views' => 500,
                'max_clicks' => 100,
                'max_subjects' => 10,
                'badge_level' => 'expert',
                'search_ranking' => 'priority',
                'show_whatsapp' => 1,
                'email_marketing_access' => 0,
                'allow_video_upload' => 0,
                'allow_pdf_upload' => 0,
                'allow_announcements' => 0,
                'allow_video_solution' => 0,
                'portal_type' => 'university',
                'is_active' => 1,
                'sort_order' => 2,
            ],
            [
                'name' => 'Premium',
                'description' => 'Highly active tutors requiring priority placement and enhanced exposure',
                'price_monthly' => 10000.00,
                'features' => json_encode([
                    'Top university tutor profile placement',
                    'Highest priority in matching order',
                    'Maximum exposure in the university support module',
                    'WhatsApp contact visibility where enabled',
                    'Premium profile media capability',
                ]),
                'max_profile_views' => 0,
                'max_clicks' => 0,
                'max_subjects' => 20,
                'badge_level' => 'master',
                'search_ranking' => 'top',
                'show_whatsapp' => 1,
                'email_marketing_access' => 1,
                'allow_video_upload' => 1,
                'allow_pdf_upload' => 0,
                'allow_announcements' => 0,
                'allow_video_solution' => 0,
                'portal_type' => 'university',
                'is_active' => 1,
                'sort_order' => 3,
            ],
        ];

        foreach ($plans as $planData) {
            $existing = $this->subscriptionPlanModel
                ->where('portal_type', 'university')
                ->where('name', $planData['name'])
                ->first();

            if ($existing) {
                $this->subscriptionPlanModel->update((int) $existing['id'], array_merge($planData, [
                    'updated_at' => $now,
                ]));
                continue;
            }

            $this->subscriptionPlanModel->insert(array_merge($planData, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }
    }

    private function getPortalContext()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        $userId = (int) session()->get('user_id');
        $user = $this->userModel->find($userId);

        if (!$user) {
            session()->destroy();
            return redirect()->to('/login')->with('error', 'User not found.');
        }

        if (($user['role'] ?? '') !== 'trainer') {
            if (($user['role'] ?? '') === 'admin') {
                return redirect()->to('admin/dashboard');
            }

            return redirect()->to('dashboard');
        }

        $profile = $this->resolveLinkedProfile($user);

        if (!$profile) {
            session()->set([
                'portal_type' => 'trainer',
                'university_tutor_id' => null,
                'university_reference_code' => null,
            ]);

            return redirect()->to('trainer/dashboard');
        }

        $this->primePortalSession($profile);

        return [
            'user' => $user,
            'profile' => $profile,
        ];
    }

    private function resolveLinkedProfile(array $user): ?array
    {
        return $this->universityTutorModel->findLinkedProfile(
            (int) ($user['id'] ?? 0),
            (string) ($user['email'] ?? ''),
            (string) ($user['username'] ?? '')
        );
    }

    private function primePortalSession(array $profile): void
    {
        session()->set([
            'portal_type' => 'university',
            'university_tutor_id' => $profile['id'] ?? null,
            'university_reference_code' => $profile['reference_code'] ?? null,
        ]);
    }

    private function decodeList($value): array
    {
        return $this->universityTutorModel->decodeJsonList($value);
    }

    private function buildApplicationStatus(array $profile): array
    {
        $status = (string) ($profile['status'] ?? 'draft');

        if ($status === 'approved') {
            return [
                'label' => 'Approved',
                'tone' => 'success',
                'message' => 'Your university tutor profile has been approved.',
            ];
        }

        if ($status === 'rejected') {
            return [
                'label' => 'Updates Required',
                'tone' => 'danger',
                'message' => 'Your last submission was rejected. Update your profile and submit it again for review.',
            ];
        }

        if (!$this->universityTutorModel->isProfileReadyForReview($profile)) {
            return [
                'label' => 'Profile Incomplete',
                'tone' => 'warning',
                'message' => 'Complete the remaining university profile details before the admin team can review your application.',
            ];
        }

        return [
            'label' => 'Pending Review',
            'tone' => 'warning',
            'message' => 'Your submitted university profile is under review by the admin team.',
        ];
    }

    private function buildNextSteps(array $user, array $profile, bool $canAccessSubscription, ?array $currentSubscription = null): array
    {
        $steps = [];

        if (empty($user['email_verified_at']) && (int) ($user['is_verified'] ?? 0) !== 1) {
            $steps[] = 'Verify your email address before using the full university portal.';
        }

        $profileCompletionGaps = $this->universityTutorModel->getProfileCompletionGaps($profile);
        if ($profileCompletionGaps !== []) {
            $steps[] = 'Complete the remaining university profile details and submit them for admin review.';
        } elseif (($profile['status'] ?? '') === 'rejected') {
            $steps[] = 'Review your university profile, update any required details, and submit it again for admin approval.';
        } elseif (($profile['status'] ?? '') === 'pending_review') {
            $steps[] = 'Wait for admin review of your submitted university profile.';
        }

        if (($profile['status'] ?? '') === 'approved' && !$currentSubscription && $canAccessSubscription) {
            $steps[] = 'Choose a subscription plan to activate the same payment flow used in the tutor portal.';
        }

        if (($profile['status'] ?? '') === 'approved' && $currentSubscription) {
            $steps[] = 'Keep your university profile and subscription details up to date so the team can match you to the right work.';
        }

        if ($steps === []) {
            $steps[] = 'Your portal is active. Review your details and keep your profile information current.';
        }

        return $steps;
    }

    private function canAccessSubscription(array $user, array $profile): bool
    {
        if (($profile['status'] ?? '') !== 'approved') {
            return false;
        }

        if ((int) ($user['is_active'] ?? 0) !== 1) {
            return false;
        }

        if (empty($user['email_verified_at']) && (int) ($user['is_verified'] ?? 0) !== 1) {
            return false;
        }

        return in_array((string) ($user['tutor_status'] ?? ''), ['approved', 'active'], true);
    }

    private function buildPortalSubscriptionCounts(): array
    {
        $universityLookup = $this->buildUniversityTutorLookup();
        $summary = [
            'total_subscriptions' => 0,
            'active_subscriptions' => 0,
            'regular_subscriptions' => 0,
            'regular_active_subscriptions' => 0,
            'university_subscriptions' => 0,
            'university_active_subscriptions' => 0,
        ];
        $plans = [];
        $now = time();

        foreach ($this->tutorSubscriptionModel->getAllWithDetails() as $subscription) {
            $planId = (int) ($subscription['plan_id'] ?? 0);
            $isUniversity = $this->isUniversitySubscription($subscription, $universityLookup);
            $isActive = $this->isCurrentSubscription($subscription, $now);

            if (!isset($plans[$planId])) {
                $plans[$planId] = [
                    'total_subscriptions' => 0,
                    'active_subscriptions' => 0,
                    'regular_subscriptions' => 0,
                    'regular_active_subscriptions' => 0,
                    'university_subscriptions' => 0,
                    'university_active_subscriptions' => 0,
                ];
            }

            $summary['total_subscriptions']++;
            $plans[$planId]['total_subscriptions']++;

            if ($isActive) {
                $summary['active_subscriptions']++;
                $plans[$planId]['active_subscriptions']++;
            }

            if ($isUniversity) {
                $summary['university_subscriptions']++;
                $plans[$planId]['university_subscriptions']++;

                if ($isActive) {
                    $summary['university_active_subscriptions']++;
                    $plans[$planId]['university_active_subscriptions']++;
                }
            } else {
                $summary['regular_subscriptions']++;
                $plans[$planId]['regular_subscriptions']++;

                if ($isActive) {
                    $summary['regular_active_subscriptions']++;
                    $plans[$planId]['regular_active_subscriptions']++;
                }
            }
        }

        return [
            'summary' => $summary,
            'plans' => $plans,
        ];
    }

    private function attachPlanSubscriptionCounts(array $plans, array $countsByPlan): array
    {
        $emptyCounts = [
            'total_subscriptions' => 0,
            'active_subscriptions' => 0,
            'regular_subscriptions' => 0,
            'regular_active_subscriptions' => 0,
            'university_subscriptions' => 0,
            'university_active_subscriptions' => 0,
        ];

        foreach ($plans as &$plan) {
            $planId = (int) ($plan['id'] ?? 0);
            $plan['subscription_counts'] = $countsByPlan[$planId] ?? $emptyCounts;
        }
        unset($plan);

        return $plans;
    }

    private function buildUniversityTutorLookup(): array
    {
        $lookup = [
            'user_ids' => [],
            'emails' => [],
        ];

        foreach ($this->universityTutorModel->findAll() as $profile) {
            $userId = (int) ($profile['user_id'] ?? 0);
            if ($userId > 0) {
                $lookup['user_ids'][$userId] = true;
            }

            $email = strtolower(trim((string) ($profile['email'] ?? '')));
            if ($email !== '') {
                $lookup['emails'][$email] = true;
            }
        }

        return $lookup;
    }

    private function isUniversitySubscription(array $subscription, array $universityLookup): bool
    {
        $userId = (int) ($subscription['user_id'] ?? 0);
        if ($userId > 0 && isset($universityLookup['user_ids'][$userId])) {
            return true;
        }

        $email = strtolower(trim((string) ($subscription['email'] ?? '')));

        return $email !== '' && isset($universityLookup['emails'][$email]);
    }

    private function isCurrentSubscription(array $subscription, int $now): bool
    {
        if (($subscription['status'] ?? '') !== 'active') {
            return false;
        }

        $startTime = !empty($subscription['current_period_start']) ? strtotime((string) $subscription['current_period_start']) : null;
        $endTime = !empty($subscription['current_period_end']) ? strtotime((string) $subscription['current_period_end']) : null;

        if ($startTime && $startTime > $now) {
            return false;
        }

        return $endTime === null || $endTime >= $now;
    }

    private function normalizeTextareaEntries($value, int $max): array
    {
        $lines = preg_split('/\r\n|\r|\n/', (string) $value) ?: [];

        return $this->normalizeLimitedEntries($lines, $max);
    }

    private function persistProfileDraft(array $user, array $profile, array $postData): array
    {
        $errors = [];
        $workStatus = trim((string) ($postData['work_status'] ?? ''));
        $isEmployed = $workStatus === 'Employed';
        $employerName = $isEmployed ? trim((string) ($postData['employer_name'] ?? '')) : '';
        $employerContact = $isEmployed ? trim((string) ($postData['employer_contact'] ?? '')) : '';
        $references = $this->normalizeTextareaEntries($postData['references'] ?? '', 6);

        if ($workStatus !== '' && !in_array($workStatus, ['Employed', 'Not Employed'], true)) {
            $errors['work_status'] = 'Select a valid employment status.';
        }

        if ($references !== [] && count($references) < 3) {
            $errors['references'] = 'References are optional. If included, please provide at least three contacts, one per line.';
        }

        $profilePicture = $this->request->getFile('profile_picture');
        $nationalIdFile = $this->request->getFile('national_id_file');
        $certificationFiles = $this->request->getFileMultiple('certification_files');

        if ($this->isValidUpload($profilePicture) && !in_array($this->getUploadedExtension($profilePicture), ['jpg', 'jpeg', 'png'], true)) {
            $errors['profile_picture'] = 'Profile picture must be a JPG or PNG image.';
        }

        if ($this->isValidUpload($nationalIdFile) && !in_array($this->getUploadedExtension($nationalIdFile), ['jpg', 'jpeg', 'png', 'pdf'], true)) {
            $errors['national_id_file'] = 'National ID must be an image or PDF file.';
        }

        $validCertificationFiles = [];
        if (is_array($certificationFiles)) {
            foreach ($certificationFiles as $file) {
                if (!$this->isValidUpload($file)) {
                    continue;
                }

                if (!in_array($this->getUploadedExtension($file), ['jpg', 'jpeg', 'png', 'pdf'], true)) {
                    $errors['certification_files'] = 'Certification files must be image or PDF files.';
                    continue;
                }

                $validCertificationFiles[] = $file;
            }
        }

        $existingCertificationPaths = $this->decodeList($profile['certification_files_json'] ?? null);
        if (count($existingCertificationPaths) + count($validCertificationFiles) > 4) {
            $errors['certification_files'] = 'You can keep up to 4 certification files in total.';
        }

        if ($errors !== []) {
            return $errors;
        }

        $profilePicturePath = trim((string) ($profile['profile_picture'] ?? ''));
        if ($this->isValidUpload($profilePicture)) {
            $profilePicturePath = $this->moveUploadedFile($profilePicture, 'public/uploads/university_college/profile_pictures');
        }

        $nationalIdPath = trim((string) ($profile['national_id_file'] ?? ''));
        if ($this->isValidUpload($nationalIdFile)) {
            $nationalIdPath = $this->moveUploadedFile($nationalIdFile, 'public/uploads/university_college/national_ids');
        }

        $certificationPaths = $existingCertificationPaths;
        foreach ($validCertificationFiles as $file) {
            $certificationPaths[] = $this->moveUploadedFile($file, 'public/uploads/university_college/certifications');
        }
        $certificationPaths = array_slice(array_values(array_unique($certificationPaths)), 0, 4);

        $status = (string) ($profile['status'] ?? 'draft');
        if (!in_array($status, ['approved', 'pending_review'], true)) {
            $status = 'draft';
        }

        $this->universityTutorModel->update((int) $profile['id'], [
            'profile_picture' => $profilePicturePath ?: null,
            'national_id_file' => $nationalIdPath ?: null,
            'certification_files_json' => json_encode($certificationPaths),
            'institutions_json' => json_encode($this->normalizeTextareaEntries($postData['institutions'] ?? '', 4)),
            'specializations_json' => json_encode([]),
            'service_areas_json' => json_encode($this->normalizeServiceAreas($postData['service_areas'] ?? [])),
            'year_of_study_or_graduation' => trim((string) ($postData['year_of_study_or_graduation'] ?? '')) ?: null,
            'bio' => trim((string) ($postData['bio'] ?? '')) ?: null,
            'references_json' => json_encode($references),
            'work_status' => $workStatus ?: null,
            'employer_name' => $employerName ?: null,
            'employer_contact' => $employerContact ?: null,
            'available_days_json' => json_encode($this->normalizeLimitedEntries($postData['available_days'] ?? [], 7)),
            'preferred_times_json' => json_encode($this->normalizePreferredTimes($postData['preferred_times'] ?? [])),
            'teaching_mode' => trim((string) ($postData['teaching_mode'] ?? '')) ?: null,
            'city_location' => trim((string) ($postData['city_location'] ?? '')) ?: null,
            'hourly_rate' => $this->normalizeDecimal($postData['hourly_rate'] ?? null),
            'consultation_package_rate' => $this->normalizeDecimal($postData['consultation_package_rate'] ?? null),
            'dissertation_package_rate' => $this->normalizeDecimal($postData['dissertation_package_rate'] ?? null),
            'exam_preparation_rate' => $this->normalizeDecimal($postData['exam_preparation_rate'] ?? null),
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $this->userModel->update((int) $user['id'], [
            'district' => trim((string) ($postData['city_location'] ?? '')) ?: null,
            'location' => trim((string) ($postData['city_location'] ?? '')) ?: null,
            'teaching_mode' => trim((string) ($postData['teaching_mode'] ?? '')) ?: null,
            'bio' => trim((string) ($postData['bio'] ?? '')) ?: null,
            'is_employed' => $isEmployed ? 1 : 0,
            'school_name' => $employerName ?: null,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return [];
    }

    private function normalizeLimitedEntries($entries, int $max): array
    {
        if (!is_array($entries)) {
            $entries = [$entries];
        }

        $entries = array_map(static fn ($item) => trim((string) $item), $entries);
        $entries = array_filter($entries, static fn ($item) => $item !== '');

        return array_slice(array_values(array_unique($entries)), 0, $max);
    }

    private function normalizeServiceAreas($entries): array
    {
        $allowed = [];
        foreach (self::SERVICE_CATEGORIES as $services) {
            foreach ($services as $service) {
                $allowed[] = $service;
            }
        }

        $normalized = $this->normalizeLimitedEntries($entries, 20);

        return array_values(array_filter($normalized, static fn ($item) => in_array($item, $allowed, true)));
    }

    private function normalizePreferredTimes($entries): array
    {
        $normalized = $this->normalizeLimitedEntries($entries, 8);

        return array_values(array_filter($normalized, static fn ($item) => in_array($item, self::PREFERRED_TIME_OPTIONS, true)));
    }

    private function normalizeDecimal($value): ?float
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        return (float) $value;
    }

    private function isValidUpload($file): bool
    {
        return $file && $file->isValid() && !$file->hasMoved();
    }

    private function getUploadedExtension($file): string
    {
        if (!$file) {
            return '';
        }

        $clientExtension = strtolower(trim((string) $file->getClientExtension()));
        if ($clientExtension !== '') {
            return $clientExtension;
        }

        $guessedExtension = strtolower(trim((string) $file->guessExtension()));

        return $guessedExtension;
    }

    private function normalizeWizardStep($step, int $default = 5): int
    {
        $step = (int) $step;

        if ($step < 1 || $step > 5) {
            return $default;
        }

        return $step;
    }

    private function resolveWizardErrorStep(array $fields): int
    {
        $stepMap = [
            'year_of_study_or_graduation' => 1,
            'bio' => 1,
            'teaching_mode' => 1,
            'city_location' => 1,
            'work_status' => 1,
            'employer_name' => 1,
            'employer_contact' => 1,
            'institutions' => 2,
            'hourly_rate' => 2,
            'consultation_package_rate' => 2,
            'dissertation_package_rate' => 2,
            'exam_preparation_rate' => 2,
            'service_areas' => 3,
            'available_days' => 3,
            'preferred_times' => 3,
            'references' => 3,
            'profile_picture' => 4,
            'national_id_file' => 4,
            'certification_files' => 4,
        ];

        $step = 5;
        foreach ($fields as $field) {
            if (!isset($stepMap[$field])) {
                continue;
            }

            $step = min($step, $stepMap[$field]);
        }

        return $step;
    }

    private function moveUploadedFile($file, string $relativeDir): string
    {
        $absoluteDir = ROOTPATH . $relativeDir;
        if (!is_dir($absoluteDir)) {
            mkdir($absoluteDir, 0755, true);
        }

        $name = $file->getRandomName();
        $file->move($absoluteDir, $name, true);

        return str_replace('public/', '', trim($relativeDir, '/')) . '/' . $name;
    }
}
