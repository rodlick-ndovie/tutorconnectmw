<?php

namespace App\Controllers;

use App\Models\SiteSettingModel;
use App\Models\UniversityCollegeTutorModel;
use App\Models\UniversityLectureRequestApplicationModel;
use App\Models\UniversityLectureRequestModel;
use App\Models\User;

class UniversityCollegeSupport extends BaseController
{
    protected $userModel;
    protected $siteSettingModel;
    protected $db;

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
        'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday',
    ];

    private const TEACHING_MODES = ['Online', 'Physical', 'Both'];
    private const WORK_STATUS_OPTIONS = ['Employed', 'Not Employed'];
    private const ACCOUNT_TYPE_OPTIONS = ['individual', 'firm'];
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
    private const SUBSCRIPTION_PLANS = [
        ['name' => 'Basic', 'fee' => 'MK2,000', 'for' => 'New tutors joining the platform'],
        ['name' => 'Standard', 'fee' => 'MK5,000', 'for' => 'Active tutors seeking more visibility'],
        ['name' => 'Premium', 'fee' => 'MK10,000', 'for' => 'Highly active tutors requiring priority placement and enhanced exposure'],
    ];

    private const PRICING_GUIDELINES = [
        'Hourly Tutoring: MK5,000 - MK20,000',
        'Consultation Package: MK20,000 - MK50,000',
        'Dissertation Support Package: MK50,000 - MK100,000',
        'Exam Preparation Package: MK40,000 - MK80,000',
    ];

    public function __construct()
    {
        helper(['form', 'url']);
        $this->userModel = new User();
        $this->siteSettingModel = new SiteSettingModel();
        $this->db = \Config\Database::connect();
    }

    private function getAuthenticatedPortalUrl(): string
    {
        $userId = (int) session()->get('user_id');

        if ($userId <= 0) {
            return site_url('login');
        }

        $user = $this->userModel->find($userId);

        if (!$user) {
            return site_url('login');
        }

        if (($user['role'] ?? '') === 'admin') {
            return site_url('admin/dashboard');
        }

        if (($user['role'] ?? '') !== 'trainer') {
            return site_url('dashboard');
        }

        if (session()->get('portal_type') === 'university') {
            return site_url('university-portal/dashboard');
        }

        $universityTutorModel = new UniversityCollegeTutorModel();
        $profile = $universityTutorModel->findLinkedProfile(
            (int) ($user['id'] ?? 0),
            (string) ($user['email'] ?? ''),
            (string) ($user['username'] ?? '')
        );

        if ($profile) {
            session()->set([
                'portal_type' => 'university',
                'university_tutor_id' => $profile['id'] ?? null,
                'university_reference_code' => $profile['reference_code'] ?? null,
            ]);

            return site_url('university-portal/dashboard');
        }

        session()->set([
            'portal_type' => 'trainer',
            'university_tutor_id' => null,
            'university_reference_code' => null,
        ]);

        return site_url('trainer/dashboard');
    }

    private function getSiteSetting(string $key, string $default = ''): string
    {
        try {
            return $this->siteSettingModel->getValue($key, $default);
        } catch (\Throwable $e) {
            return $default;
        }
    }

    private function generateEmailTemplate(string $content, string $subject = '', bool $showFooter = true): string
    {
        $companyName = $this->getSiteSetting('company_name', 'TutorConnect Malawi');
        $contactEmail = $this->getSiteSetting('contact_email', 'info@tutorconnectmalawi.com');
        $supportPhone = $this->getSiteSetting('support_phone', '+265 123 456 789');
        $websiteUrl = $this->getSiteSetting('website_url', base_url());
        $facebookUrl = $this->getSiteSetting('facebook_url', '#');
        $twitterUrl = $this->getSiteSetting('twitter_url', '#');

        $footer = $showFooter ? "
            <div style='margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; text-align: center; color: #666; font-size: 12px;'>
                <p><strong>{$companyName}</strong></p>
                <p>Email: <a href='mailto:{$contactEmail}' style='color: #E55C0D;'>{$contactEmail}</a> | Phone: {$supportPhone}</p>
                <p>Website: <a href='{$websiteUrl}' style='color: #E55C0D;'>{$websiteUrl}</a></p>
                <div style='margin-top: 15px;'>
                    <a href='{$facebookUrl}' style='margin: 0 10px; color: #1877F2; text-decoration: none;'>Facebook</a> |
                    <a href='{$twitterUrl}' style='margin: 0 10px; color: #1DA1F2; text-decoration: none;'>Twitter</a>
                </div>
                <p style='margin-top: 20px; color: #999;'>&copy; " . date('Y') . " {$companyName}. All rights reserved.</p>
            </div>
        " : '';

        return "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>{$subject}</title>
            <style>
                body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; background-color: #f8f9fa; margin: 0; padding: 0; }
                .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
                .header { background: linear-gradient(135deg, #E55C0D, #C0392B); padding: 30px 20px; text-align: center; color: white; }
                .content { padding: 30px 20px; }
                .btn { display: inline-block; background: #E55C0D; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: 600; margin: 10px 0; }
                .highlight { background: linear-gradient(135deg, #FFF3CD, #FFEAA7); padding: 20px; border-radius: 6px; margin: 20px 0; border-left: 4px solid #E55C0D; }
                .code-box { background: #f8f9fa; border: 2px dashed #E55C0D; padding: 20px; text-align: center; border-radius: 6px; margin: 20px 0; }
                .code { font-size: 24px; font-weight: bold; color: #E55C0D; letter-spacing: 3px; font-family: 'Courier New', monospace; }
                h1, h2, h3 { color: #2C3E50; margin-top: 0; }
                p { margin-bottom: 15px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>{$companyName}</h1>
                    <p>Connecting Tutors & Students Across Malawi</p>
                </div>
                <div class='content'>
                    {$content}
                </div>
                {$footer}
            </div>
        </body>
        </html>
        ";
    }

    private function verificationSecret(): string
    {
        $key = (string) (config('Encryption')->key ?? '');

        return $key !== '' ? $key : (string) (getenv('app.baseURL') ?: FCPATH);
    }

    private function makeVerificationToken(string $email, string $otpCode, string $otpExpiresAt): string
    {
        return hash_hmac('sha256', strtolower(trim($email)) . '|' . $otpCode . '|' . $otpExpiresAt, $this->verificationSecret());
    }

    private function buildVerificationLink(string $email, string $otpCode, string $otpExpiresAt): string
    {
        $token = $this->makeVerificationToken($email, $otpCode, $otpExpiresAt);

        return base_url('verify-email?email=' . rawurlencode($email) . '&token=' . rawurlencode($token));
    }

    private function sendVerificationEmail(array $userData, string $firstName): void
    {
        try {
            $emailService = \Config\Services::email();
            $emailService->setFrom($this->getSiteSetting('contact_email', 'info@tutorconnectmw.com'), $this->getSiteSetting('company_name', 'TutorConnect Malawi'));
            $emailService->setTo((string) $userData['email']);
            $emailService->setSubject($this->getSiteSetting('company_name', 'TutorConnect Malawi') . ' - Email Verification');
            $emailService->setMailType('html');

            $verificationLink = $this->buildVerificationLink(
                (string) $userData['email'],
                (string) $userData['otp_code'],
                (string) $userData['otp_expires_at']
            );

            $content = "
                <h2>Welcome to " . $this->getSiteSetting('company_name', 'TutorConnect Malawi') . ", {$firstName}!</h2>

                <p>Thank you for registering as a university or college tutor. Please verify your email to activate your account.</p>

                <div class='code-box'>
                    <p style='margin: 0 0 10px 0; color: #666;'>Your verification code:</p>
                    <div class='code'>{$userData['otp_code']}</div>
                    <p style='margin: 10px 0 0 0; font-size: 14px; color: #666;'>Valid for 15 minutes</p>
                </div>

                <div style='text-align: center; margin: 30px 0;'>
                    <a href='{$verificationLink}' class='btn'>Verify My Email</a>
                </div>

                <p style='text-align: center; color: #666; font-size: 14px;'>
                    Or copy and paste this link: <br>
                    <a href='{$verificationLink}' style='color: #E55C0D; word-break: break-all;'>{$verificationLink}</a>
                </p>

                <div class='highlight'>
                    <h3 style='margin-top: 0; color: #2C3E50;'>What happens next?</h3>
                    <ul style='color: #555;'>
                        <li>Your account will be activated after email verification</li>
                        <li>You can continue with tutor profile completion</li>
                        <li>Admin will review your university module registration</li>
                        <li>You can start preparing your academic support profile</li>
                    </ul>
                </div>

                <p>If you didn't create this account, please ignore this email.</p>
            ";

            $emailService->setMessage($this->generateEmailTemplate($content, $this->getSiteSetting('company_name', 'TutorConnect Malawi') . ' - Email Verification'));

            if ($emailService->send(false)) {
                log_message('info', 'University tutor verification email sent to: ' . $userData['email']);
            } else {
                log_message('error', 'Failed to send university tutor verification email to: ' . $userData['email']);
                log_message('error', 'Email debug: ' . $emailService->printDebugger(['headers']));
            }
        } catch (\Throwable $e) {
            log_message('error', 'University tutor email service error: ' . $e->getMessage());
        }
    }

    public function index()
    {
        $this->ensureTables();
        $approvedTutors = $this->getApprovedUniversityTutors(12);

        return view('university_college/index', [
            'title' => 'University & College Support - TutorConnect Malawi',
            'serviceCategories' => self::SERVICE_CATEGORIES,
            'subscriptionPlans' => self::SUBSCRIPTION_PLANS,
            'pricingGuidelines' => self::PRICING_GUIDELINES,
            'approvedTutors' => $approvedTutors,
        ]);
    }

    public function register()
    {
        if (session()->get('user_id')) {
            return redirect()->to($this->getAuthenticatedPortalUrl());
        }

        $this->ensureTables();

        $fromBack = $this->request->getGet('back');
        if ($fromBack) {
            session()->set('uc_registration_step', 1);
        }

        if (!$fromBack && !session()->get('uc_registration_step')) {
            session()->remove('uc_registration_data');
            session()->remove('uc_registration_step');
            session()->remove('success');
            session()->remove('error');
        }

        $step = session()->get('uc_registration_step') ?? 1;

        return view('university_college/register', [
            'title' => 'Register - University & College Support',
            'step' => $step,
            'form_data' => session()->get('uc_registration_data') ?? [],
            'teachingModes' => self::TEACHING_MODES,
            'workStatusOptions' => self::WORK_STATUS_OPTIONS,
            'accountTypeOptions' => self::ACCOUNT_TYPE_OPTIONS,
        ]);
    }

    public function checkEmail()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['exists' => false]);
        }

        $json = $this->request->getJSON();
        $email = strtolower(trim((string) ($json->email ?? '')));

        if ($email === '') {
            return $this->response->setJSON(['exists' => false]);
        }

        $this->ensureTables();

        $exists = (new User())->withDeleted()
            ->where('email', $email)
            ->first() !== null;

        if (!$exists) {
            $exists = (new UniversityCollegeTutorModel())
                ->where('email', $email)
                ->first() !== null;
        }

        return $this->response->setJSON(['exists' => $exists]);
    }

    public function checkPhone()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['exists' => false]);
        }

        $json = $this->request->getJSON();
        $phone = trim((string) ($json->phone ?? ''));

        if ($phone === '') {
            return $this->response->setJSON(['exists' => false]);
        }

        $this->ensureTables();

        $exists = (new User())->withDeleted()
            ->where('phone', $phone)
            ->first() !== null;

        if (!$exists) {
            $exists = (new UniversityCollegeTutorModel())
                ->where('phone', $phone)
                ->first() !== null;
        }

        return $this->response->setJSON(['exists' => $exists]);
    }

    public function checkUsername()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['exists' => false]);
        }

        $json = $this->request->getJSON();
        $username = trim((string) ($json->username ?? ''));

        if ($username === '') {
            return $this->response->setJSON(['exists' => false]);
        }

        $this->ensureTables();

        $exists = (new User())->withDeleted()
            ->where('username', $username)
            ->first() !== null;

        if (!$exists) {
            $exists = (new UniversityCollegeTutorModel())
                ->where('username', $username)
                ->first() !== null;
        }

        return $this->response->setJSON(['exists' => $exists]);
    }

    public function registerStep1()
    {
        $data = [
            'account_type' => trim((string) $this->request->getPost('account_type')) ?: 'individual',
            'firm_name' => trim((string) $this->request->getPost('firm_name')),
            'first_name' => trim((string) $this->request->getPost('first_name')),
            'last_name' => trim((string) $this->request->getPost('last_name')),
            'email' => strtolower(trim((string) $this->request->getPost('email'))),
            'phone' => trim((string) $this->request->getPost('phone')),
            'year_of_study_or_graduation' => trim((string) $this->request->getPost('year_of_study_or_graduation')),
            'bio' => trim((string) $this->request->getPost('bio')),
            'teaching_mode' => trim((string) $this->request->getPost('teaching_mode')),
            'city_location' => trim((string) $this->request->getPost('city_location')),
            'work_status' => trim((string) $this->request->getPost('work_status')),
            'employer_name' => trim((string) $this->request->getPost('employer_name')),
            'employer_contact' => trim((string) $this->request->getPost('employer_contact')),
        ];

        $errors = [];

        if (!in_array($data['account_type'], self::ACCOUNT_TYPE_OPTIONS, true)) {
            $errors['account_type'] = 'Select a valid account type';
        }

        if ($data['account_type'] === 'firm') {
            $data['work_status'] = '';
            $data['employer_name'] = '';
            $data['employer_contact'] = '';
        }

        if ($data['account_type'] === 'firm' && $data['firm_name'] === '') {
            $errors['firm_name'] = 'Firm or company name is required';
        }

        if ($data['first_name'] === '') {
            $errors['first_name'] = $data['account_type'] === 'firm' ? 'Contact first name is required' : 'First name is required';
        }
        if ($data['last_name'] === '') {
            $errors['last_name'] = $data['account_type'] === 'firm' ? 'Contact last name is required' : 'Last name is required';
        }
        if ($data['email'] === '') {
            $errors['email'] = 'Email is required';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format';
        }
        if ($data['phone'] === '') {
            $errors['phone'] = 'Phone number is required';
        } elseif (!preg_match('/^(\+265|0)?[1-9][0-9]{7,8}$/', $data['phone'])) {
            $errors['phone'] = 'Please enter a valid Malawi phone number';
        }
        if ($data['year_of_study_or_graduation'] === '') {
            $errors['year_of_study_or_graduation'] = $data['account_type'] === 'firm' ? 'Year established or registration year is required' : 'Year of study or graduation is required';
        }
        if ($data['bio'] === '' || mb_strlen($data['bio']) < 40) {
            $errors['bio'] = $data['account_type'] === 'firm' ? 'Company profile is required and must be at least 40 characters' : 'Bio is required and must be at least 40 characters';
        }
        if ($data['teaching_mode'] === '' || !in_array($data['teaching_mode'], self::TEACHING_MODES, true)) {
            $errors['teaching_mode'] = 'Teaching mode is required';
        }
        if ($data['city_location'] === '') {
            $errors['city_location'] = 'City or location is required';
        }
        if ($data['work_status'] !== '' && !in_array($data['work_status'], self::WORK_STATUS_OPTIONS, true)) {
            $errors['work_status'] = 'Invalid work status selected';
        }
        if ($data['work_status'] === 'Employed' && $data['employer_name'] === '') {
            $errors['employer_name'] = 'Employer name is required when employed';
        }
        if ($data['work_status'] === 'Employed' && $data['employer_contact'] === '') {
            $errors['employer_contact'] = 'Employer contact is required when employed';
        }

        $universityTutorModel = new UniversityCollegeTutorModel();
        $existingUserByEmail = (new User())->withDeleted()->where('email', $data['email'])->first();
        $existingRecordByEmail = $universityTutorModel->where('email', $data['email'])->first();
        if ($existingUserByEmail || $existingRecordByEmail) {
            $errors['email'] = 'Email address already registered';
        }

        $existingUserByPhone = (new User())->withDeleted()->where('phone', $data['phone'])->first();
        $existingRecordByPhone = $universityTutorModel->where('phone', $data['phone'])->first();
        if ($existingUserByPhone || $existingRecordByPhone) {
            $errors['phone'] = 'Phone number already registered';
        }

        if (!empty($errors)) {
            session()->set('uc_registration_step', 1);

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $errors,
                    'step' => 1,
                    'message' => 'Please correct the validation errors.',
                ]);
            }

            return redirect()->to('university-college-support/register')->withInput()->with('errors', $errors);
        }

        session()->set('uc_registration_data', $data);
        session()->set('uc_registration_step', 2);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Step 1 completed',
                'next_step' => 2,
            ]);
        }

        return redirect()->to('university-college-support/register')->with('success', 'Step 1 completed. Please continue.');
    }

    public function registerStep2()
    {
        $step1Data = session()->get('uc_registration_data');

        if (!$step1Data) {
            return redirect()->to('university-college-support/register')->with('error', 'Please complete step 1 first');
        }

        $step2Data = [
            'username' => trim((string) $this->request->getPost('username')),
            'password' => (string) $this->request->getPost('password'),
            'confirm_password' => (string) $this->request->getPost('confirm_password'),
            'accept_terms' => $this->request->getPost('accept_terms'),
        ];

        $errors = [];

        if ($step2Data['username'] === '') {
            $errors['username'] = 'Username is required';
        } elseif (!preg_match('/^[A-Za-z0-9_]{4,20}$/', $step2Data['username'])) {
            $errors['username'] = 'Username must be 4-20 characters and use letters, numbers, or underscore only';
        }

        if ($step2Data['password'] === '') {
            $errors['password'] = 'Password is required';
        } elseif (strlen($step2Data['password']) < 8) {
            $errors['password'] = 'Password must be at least 8 characters';
        } elseif (!preg_match('/\d/', $step2Data['password'])) {
            $errors['password'] = 'Password must contain at least one number';
        }

        if ($step2Data['password'] !== $step2Data['confirm_password']) {
            $errors['confirm_password'] = 'Passwords do not match';
        }

        if (empty($step2Data['accept_terms'])) {
            $errors['accept_terms'] = 'You must agree to the terms and conditions';
        }

        $universityTutorModel = new UniversityCollegeTutorModel();
        $existingUserByUsername = (new User())->withDeleted()->where('username', $step2Data['username'])->first();
        $existingRecordByUsername = $universityTutorModel->where('username', $step2Data['username'])->first();
        if ($existingUserByUsername || $existingRecordByUsername) {
            $errors['username'] = 'Username already taken';
        }

        $existingUserByEmail = (new User())->withDeleted()->where('email', $step1Data['email'])->first();
        $existingRecordByEmail = $universityTutorModel->where('email', $step1Data['email'])->first();
        if ($existingUserByEmail || $existingRecordByEmail) {
            $errors['email'] = 'Email address already registered';
        }

        $existingUserByPhone = (new User())->withDeleted()->where('phone', $step1Data['phone'])->first();
        $existingRecordByPhone = $universityTutorModel->where('phone', $step1Data['phone'])->first();
        if ($existingUserByPhone || $existingRecordByPhone) {
            $errors['phone'] = 'Phone number already registered';
        }

        if (!empty($errors)) {
            $returnStep = (isset($errors['email']) || isset($errors['phone'])) ? 1 : 2;
            session()->set('uc_registration_step', $returnStep);

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $errors,
                    'step' => $returnStep,
                    'message' => 'Please correct the validation errors.',
                ]);
            }

            return redirect()->to('university-college-support/register')->withInput()->with('errors', $errors);
        }

        $completeData = array_merge($step1Data, $step2Data);
        $isFirmAccount = ($completeData['account_type'] ?? 'individual') === 'firm';
        $fullName = $isFirmAccount
            ? trim((string) ($completeData['firm_name'] ?? ''))
            : trim(($completeData['first_name'] ?? '') . ' ' . ($completeData['last_name'] ?? ''));
        $otpCode = (string) random_int(100000, 999999);
        $otpExpiresAt = date('Y-m-d H:i:s', strtotime('+15 minutes'));
        $referenceCode = $this->generateReferenceCode($universityTutorModel, 'UCT');
        $isEmployed = !$isFirmAccount && ($completeData['work_status'] ?? '') === 'Employed' ? 1 : 0;

        $userData = [
            'username' => $completeData['username'],
            'email' => $completeData['email'],
            'password' => $completeData['password'],
            'first_name' => $completeData['first_name'],
            'last_name' => $completeData['last_name'],
            'phone' => $completeData['phone'],
            'district' => $completeData['city_location'],
            'location' => $completeData['city_location'],
            'teaching_mode' => $completeData['teaching_mode'],
            'bio' => $completeData['bio'],
            'is_employed' => $isEmployed,
            'school_name' => $isFirmAccount ? null : ($completeData['employer_name'] ?: null),
            'role' => 'trainer',
            'is_active' => 0,
            'registration_completed' => 0,
            'is_verified' => 0,
            'tutor_status' => 'pending',
            'subscription_plan' => 'Pending Selection',
            'terms_accepted' => 1,
            'otp_code' => $otpCode,
            'otp_expires_at' => $otpExpiresAt,
        ];

        try {
            $this->db->transBegin();

            $this->userModel->skipValidation(true);
            $userId = $this->userModel->insert($userData);
            $this->userModel->skipValidation(false);

            if (!$userId) {
                throw new \RuntimeException('Could not create tutor user account.');
            }

            $saved = $universityTutorModel->insert([
                'user_id' => (int) $userId,
                'account_type' => in_array($completeData['account_type'] ?? 'individual', self::ACCOUNT_TYPE_OPTIONS, true) ? $completeData['account_type'] : 'individual',
                'username' => trim((string) $completeData['username']),
                'reference_code' => $referenceCode,
                'full_name' => $fullName,
                'email' => strtolower(trim((string) $completeData['email'])),
                'phone' => trim((string) $completeData['phone']),
                'profile_picture' => '',
                'national_id_file' => '',
                'certification_files_json' => json_encode([]),
                'institutions_json' => json_encode([]),
                'specializations_json' => json_encode([]),
                'service_areas_json' => json_encode([]),
                'year_of_study_or_graduation' => trim((string) $completeData['year_of_study_or_graduation']),
                'bio' => trim((string) $completeData['bio']),
                'references_json' => json_encode([]),
                'work_status' => $isFirmAccount ? null : (trim((string) ($completeData['work_status'] ?? '')) ?: null),
                'employer_name' => $isFirmAccount ? null : (trim((string) ($completeData['employer_name'] ?? '')) ?: null),
                'employer_contact' => $isFirmAccount ? null : (trim((string) ($completeData['employer_contact'] ?? '')) ?: null),
                'available_days_json' => json_encode([]),
                'preferred_times_json' => json_encode([]),
                'teaching_mode' => trim((string) $completeData['teaching_mode']),
                'city_location' => trim((string) $completeData['city_location']),
                'hourly_rate' => null,
                'consultation_package_rate' => null,
                'dissertation_package_rate' => null,
                'exam_preparation_rate' => null,
                'subscription_plan' => 'Pending Selection',
                'status' => 'draft',
            ]);

            if (!$saved) {
                throw new \RuntimeException('Could not create university tutor profile.');
            }

            if ($this->db->transStatus() === false) {
                throw new \RuntimeException('Database transaction failed during university tutor registration.');
            }

            $this->db->transCommit();
        } catch (\Throwable $e) {
            $this->userModel->skipValidation(false);
            $this->db->transRollback();

            log_message('error', 'University tutor registration exception: ' . $e->getMessage());
            log_message('error', 'University tutor registration trace: ' . $e->getTraceAsString());

            $message = 'An error occurred during registration. Please try again.';
            if (stripos($e->getMessage(), 'Duplicate entry') !== false) {
                $message = 'This email, phone number, or username is already registered. Please use different account details.';
            }

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $message,
                ]);
            }

            return redirect()->back()->withInput()->with('error', $message);
        }

        session()->remove('uc_registration_data');
        session()->remove('uc_registration_step');

        log_message('info', 'New university tutor registered: ' . $completeData['email'] . ' (Reference: ' . $referenceCode . ')');
        $this->sendVerificationEmail([
            'email' => $completeData['email'],
            'otp_code' => $otpCode,
            'otp_expires_at' => $otpExpiresAt,
        ], (string) $completeData['first_name']);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Registration successful! Please check your email for verification code.',
                'redirect' => base_url('verify-email?email=' . urlencode($completeData['email'])),
            ]);
        }

        return redirect()->to('verify-email?email=' . urlencode($completeData['email']))
            ->with('success', 'Registration successful! Please check your email for verification code.');
    }

    public function registerBack()
    {
        session()->set('uc_registration_step', 1);

        return redirect()->to('university-college-support/register?back=1');
    }

    public function storeRegistration()
    {
        $this->ensureTables();

        if (strtolower($this->request->getMethod()) !== 'post') {
            return redirect()->to(site_url('university-college-support/register'));
        }

        $postData = $this->request->getPost();
        $institutions = $this->normalizeLimitedEntries($postData['institutions'] ?? [], 4);
        $serviceAreas = $this->normalizeServiceAreas($postData['service_areas'] ?? []);
        $availabilityDays = $this->normalizeLimitedEntries($postData['available_days'] ?? [], 7);
        $preferredTimes = $this->normalizePreferredTimes($postData['preferred_times'] ?? []);
        $references = $this->normalizeLimitedEntries($postData['references'] ?? [], 6);

        $validation = \Config\Services::validation();
        $validation->setRules([
            'full_name' => 'required|min_length[5]|max_length[150]',
            'email' => 'required|valid_email|max_length[150]',
            'phone' => 'required|min_length[8]|max_length[30]',
            'year_of_study_or_graduation' => 'required|max_length[50]',
            'bio' => 'required|min_length[40]|max_length[2000]',
            'teaching_mode' => 'required|in_list[Online,Physical,Both]',
            'city_location' => 'required|max_length[150]',
            'work_status' => 'permit_empty|in_list[Employed,Not Employed]',
            'employer_name' => 'permit_empty|max_length[150]',
            'employer_contact' => 'permit_empty|max_length[100]',
            'subscription_plan' => 'permit_empty|in_list[Basic,Standard,Premium]',
            'hourly_rate' => 'permit_empty|decimal',
            'consultation_package_rate' => 'permit_empty|decimal',
            'dissertation_package_rate' => 'permit_empty|decimal',
            'exam_preparation_rate' => 'permit_empty|decimal',
        ]);

        $errors = [];
        if (!$validation->run($postData)) {
            $errors = $validation->getErrors();
        }

        if (empty($institutions)) {
            $errors['institutions'] = 'Add at least one institution attended/currently attending.';
        }

        if (empty($serviceAreas)) {
            $errors['service_areas'] = 'Select at least one service area.';
        }

        if (empty($availabilityDays)) {
            $errors['available_days'] = 'Select at least one available day.';
        }

        if (empty($preferredTimes)) {
            $errors['preferred_times'] = 'Select at least one preferred teaching time.';
        }

        if (!empty($references) && count($references) < 3) {
            $errors['references'] = 'If you provide references, add at least 3.';
        }

        $profilePicture = $this->request->getFile('profile_picture');
        $nationalIdFile = $this->request->getFile('national_id_file');
        $certificationFiles = $this->request->getFileMultiple('certification_files');

        if (!$profilePicture || !$profilePicture->isValid()) {
            $errors['profile_picture'] = 'Profile picture is required.';
        }

        if (!$nationalIdFile || !$nationalIdFile->isValid()) {
            $errors['national_id_file'] = 'National ID file is required.';
        }

        $validCertificationFiles = [];
        if (is_array($certificationFiles)) {
            foreach ($certificationFiles as $file) {
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $validCertificationFiles[] = $file;
                }
            }
        }

        if (count($validCertificationFiles) < 1) {
            $errors['certification_files'] = 'Upload at least one academic transcript or certification file.';
        } elseif (count($validCertificationFiles) > 4) {
            $errors['certification_files'] = 'You can upload up to 4 certification files.';
        }

        if (!empty($errors)) {
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        try {
            $profilePath = $this->moveUploadedFile($profilePicture, 'public/uploads/university_college/profile_pictures');
            $nationalIdPath = $this->moveUploadedFile($nationalIdFile, 'public/uploads/university_college/national_ids');

            $certificationPaths = [];
            foreach ($validCertificationFiles as $file) {
                $certificationPaths[] = $this->moveUploadedFile($file, 'public/uploads/university_college/certifications');
            }

            $model = new UniversityCollegeTutorModel();
            $referenceCode = $this->generateReferenceCode($model, 'UCT');

            $saved = $model->insert([
                'account_type' => 'individual',
                'reference_code' => $referenceCode,
                'full_name' => trim((string) $postData['full_name']),
                'email' => strtolower(trim((string) $postData['email'])),
                'phone' => trim((string) $postData['phone']),
                'profile_picture' => $profilePath,
                'national_id_file' => $nationalIdPath,
                'certification_files_json' => json_encode($certificationPaths),
                'institutions_json' => json_encode($institutions),
                'specializations_json' => json_encode([]),
                'service_areas_json' => json_encode($serviceAreas),
                'year_of_study_or_graduation' => trim((string) $postData['year_of_study_or_graduation']),
                'bio' => trim((string) $postData['bio']),
                'references_json' => json_encode($references),
                'work_status' => trim((string) ($postData['work_status'] ?? '')) ?: null,
                'employer_name' => trim((string) ($postData['employer_name'] ?? '')) ?: null,
                'employer_contact' => trim((string) ($postData['employer_contact'] ?? '')) ?: null,
                'available_days_json' => json_encode($availabilityDays),
                'preferred_times_json' => json_encode($preferredTimes),
                'teaching_mode' => trim((string) $postData['teaching_mode']),
                'city_location' => trim((string) $postData['city_location']),
                'hourly_rate' => $this->normalizeDecimal($postData['hourly_rate'] ?? null),
                'consultation_package_rate' => $this->normalizeDecimal($postData['consultation_package_rate'] ?? null),
                'dissertation_package_rate' => $this->normalizeDecimal($postData['dissertation_package_rate'] ?? null),
                'exam_preparation_rate' => $this->normalizeDecimal($postData['exam_preparation_rate'] ?? null),
                'subscription_plan' => trim((string) ($postData['subscription_plan'] ?? 'Basic')) ?: 'Basic',
                'status' => 'pending_review',
            ]);

            if (!$saved) {
                return redirect()->back()->withInput()->with('error', 'Could not submit registration right now. Please try again.');
            }

            return redirect()->to(site_url('university-college-support/register/success/' . $referenceCode));
        } catch (\Throwable $e) {
            log_message('error', 'University tutor registration failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'An unexpected error occurred while submitting registration.');
        }
    }

    public function registrationSuccess(string $referenceCode)
    {
        $this->ensureTables();

        $record = (new UniversityCollegeTutorModel())->findByReference($referenceCode);

        if (!$record) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('university_college/success', [
            'title' => 'Registration Submitted - TutorConnect Malawi',
            'record' => $record,
        ]);
    }

    public function requestLecture()
    {
        return redirect()->to(site_url('request-tutor?type=university'));
    }

    public function storeLectureRequest()
    {
        $this->ensureTables();

        if (strtolower($this->request->getMethod()) !== 'post') {
            return redirect()->to(site_url('request-tutor?type=university'));
        }

        $postData = $this->request->getPost();
        if (($postData['requester_role'] ?? '') === 'teacher') {
            return redirect()->back()
                ->withInput()
                ->with('error', 'This request page is for parents and learners. Teachers should create a provider account instead.');
        }

        $serviceCategory = trim((string) ($postData['service_category'] ?? ''));
        $topic = trim((string) ($postData['topic'] ?? ''));
        $customTopic = trim((string) ($postData['custom_topic'] ?? ''));

        if ($topic === '__other__') {
            $topic = $customTopic;
            $postData['topic'] = $topic;
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'full_name' => 'required|min_length[5]|max_length[150]',
            'email' => 'required|valid_email|max_length[150]',
            'phone' => 'required|min_length[8]|max_length[30]',
            'institution' => 'required|max_length[150]',
            'service_category' => 'required|max_length[80]',
            'topic' => 'required|max_length[255]',
            'custom_topic' => 'permit_empty|max_length[255]',
            'delivery_mode' => 'required|in_list[Online,Physical,Both]',
            'city_location' => 'required|max_length[150]',
            'preferred_date' => 'permit_empty|valid_date',
            'preferred_time' => 'permit_empty|max_length[50]',
            'budget_range' => 'permit_empty|max_length[100]',
            'notes' => 'permit_empty|max_length[2000]',
        ]);

        if (!$validation->run($postData)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        if (!isset(self::SERVICE_CATEGORIES[$serviceCategory])) {
            return redirect()->back()->withInput()->with('errors', [
                'service_category' => 'Please select a valid service category.',
            ]);
        }

        $allowedTopics = self::SERVICE_CATEGORIES[$serviceCategory];
        if ($topic === '' || (!in_array($topic, $allowedTopics, true) && $customTopic === '')) {
            return redirect()->back()->withInput()->with('errors', [
                'topic' => 'Please select a topic from the selected category or enter a specific topic.',
            ]);
        }

        $model = new UniversityLectureRequestModel();
        $referenceCode = $this->generateReferenceCode($model, 'LEC');

        $requestData = [
            'reference_code' => $referenceCode,
            'full_name' => trim((string) $postData['full_name']),
            'email' => strtolower(trim((string) $postData['email'])),
            'phone' => trim((string) $postData['phone']),
            'institution' => trim((string) $postData['institution']),
            'service_category' => $serviceCategory,
            'topic' => $topic,
            'delivery_mode' => trim((string) $postData['delivery_mode']),
            'city_location' => trim((string) $postData['city_location']),
            'preferred_date' => trim((string) ($postData['preferred_date'] ?? '')) ?: null,
            'preferred_time' => trim((string) ($postData['preferred_time'] ?? '')) ?: null,
            'budget_range' => trim((string) ($postData['budget_range'] ?? '')) ?: null,
            'notes' => trim((string) ($postData['notes'] ?? '')) ?: null,
            'status' => 'open',
            'matched_tutor_count' => 0,
            'emailed_tutor_count' => 0,
        ];

        try {
            $requestId = $model->insert($requestData);

            if (!$requestId) {
                return redirect()->back()->withInput()->with('error', 'Could not submit academic support request right now. Please try again.');
            }

            $requestData['id'] = (int) $requestId;

            $qualifiedTutors = $this->findQualifiedUniversityTutors($requestData);
            $emailedCount = $this->broadcastUniversityRequestToTutors($requestData, $qualifiedTutors);

            $model->update($requestId, [
                'matched_tutor_count' => count($qualifiedTutors),
                'emailed_tutor_count' => $emailedCount,
            ]);

            $requestData['matched_tutor_count'] = count($qualifiedTutors);
            $requestData['emailed_tutor_count'] = $emailedCount;

            $this->notifyUniversityRequesterOfSubmission($requestData);
            $this->notifyAdminOfUniversityLectureRequest($requestData, count($qualifiedTutors), $emailedCount);

            return redirect()->to(site_url('university-college-support/request-lecture/success/' . $referenceCode));
        } catch (\Throwable $e) {
            log_message('error', 'University support request submission failed: ' . $e->getMessage());

            return redirect()->back()->withInput()->with('error', 'Something went wrong while submitting your request. Please try again.');
        }
    }

    public function lectureRequestSuccess(string $referenceCode)
    {
        $this->ensureTables();

        $record = (new UniversityLectureRequestModel())->findByReference($referenceCode);

        if (!$record) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('university_college/request_lecture_success', [
            'title' => 'Lecture Request Submitted - TutorConnect Malawi',
            'record' => $record,
        ]);
    }

    public function acceptLectureRequest(string $referenceCode)
    {
        $this->ensureTables();

        $request = (new UniversityLectureRequestModel())->findByReference($referenceCode);
        $tutorId = (int) $this->request->getGet('tutor');
        $token = (string) $this->request->getGet('token');

        if (!$request || $tutorId <= 0 || !$this->isValidUniversityAcceptToken((int) $request['id'], $tutorId, $token)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if (($request['status'] ?? 'open') !== 'open') {
            return view('university_college/request_accept_success', [
                'title' => 'Request Closed - TutorConnect Malawi',
                'request' => $request,
                'tutor' => null,
                'alreadyAccepted' => false,
                'closed' => true,
            ]);
        }

        $tutor = (new UniversityCollegeTutorModel())
            ->where('id', $tutorId)
            ->where('status', 'approved')
            ->first();

        if (!$tutor || !$this->universityTutorMatchesRequest($request, $tutor)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $applicationModel = new UniversityLectureRequestApplicationModel();
        $existing = $applicationModel->findExisting((int) $request['id'], $tutorId);
        $alreadyAccepted = (bool) $existing;

        if (!$existing) {
            $applicationModel->insert([
                'university_lecture_request_id' => (int) $request['id'],
                'university_tutor_id' => $tutorId,
                'tutor_email' => $tutor['email'],
                'status' => 'accepted',
                'accepted_at' => date('Y-m-d H:i:s'),
            ]);

            $this->notifyUniversityRequesterOfTutorAcceptance($request, $tutor);
        }

        return view('university_college/request_accept_success', [
            'title' => 'Request Accepted - TutorConnect Malawi',
            'request' => $request,
            'tutor' => $tutor,
            'alreadyAccepted' => $alreadyAccepted,
            'closed' => false,
        ]);
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
        foreach (self::SERVICE_CATEGORIES as $category => $services) {
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

    private function generateReferenceCode($model, string $prefix): string
    {
        do {
            $referenceCode = $prefix . '-' . date('ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
        } while ($model->where('reference_code', $referenceCode)->first());

        return $referenceCode;
    }

    private function findQualifiedUniversityTutors(array $request): array
    {
        $now = date('Y-m-d H:i:s');
        $tutors = \Config\Database::connect()
            ->table('university_college_tutors uct')
            ->select('uct.*, sp.name AS plan_name, sp.search_ranking, sp.badge_level, sp.sort_order AS plan_sort_order, sp.price_monthly AS plan_price')
            ->join('tutor_subscriptions ts', 'ts.user_id = uct.user_id', 'inner')
            ->join('subscription_plans sp', 'sp.id = ts.plan_id', 'left')
            ->where('uct.status', 'approved')
            ->where('ts.status', 'active')
            ->where('ts.current_period_start <=', $now)
            ->where('ts.current_period_end >=', $now)
            ->groupBy('uct.id')
            ->orderBy($this->universityPlanPrioritySql(), 'DESC', false)
            ->orderBy('sp.sort_order', 'DESC')
            ->orderBy('sp.price_monthly', 'DESC')
            ->orderBy('uct.updated_at', 'DESC')
            ->limit(100)
            ->get()
            ->getResultArray();

        return array_values(array_filter($tutors, fn (array $tutor): bool => $this->universityTutorMatchesRequest($request, $tutor)));
    }

    private function getApprovedUniversityTutors(int $limit = 12): array
    {
        $now = date('Y-m-d H:i:s');
        $model = new UniversityCollegeTutorModel();
        $tutors = \Config\Database::connect()
            ->table('university_college_tutors uct')
            ->select('uct.*, sp.name AS plan_name, sp.search_ranking, sp.badge_level')
            ->join('tutor_subscriptions ts', 'ts.user_id = uct.user_id', 'inner')
            ->join('subscription_plans sp', 'sp.id = ts.plan_id', 'left')
            ->where('uct.status', 'approved')
            ->where('ts.status', 'active')
            ->where('ts.current_period_start <=', $now)
            ->where('ts.current_period_end >=', $now)
            ->groupBy('uct.id')
            ->orderBy($this->universityPlanPrioritySql(), 'DESC', false)
            ->orderBy('sp.sort_order', 'DESC')
            ->orderBy('sp.price_monthly', 'DESC')
            ->orderBy('uct.updated_at', 'DESC')
            ->orderBy('uct.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();

        foreach ($tutors as &$tutor) {
            $tutor['institutions_list'] = $model->decodeJsonList($tutor['institutions_json'] ?? null);
            $tutor['service_areas_list'] = $model->decodeJsonList($tutor['service_areas_json'] ?? null);
        }
        unset($tutor);

        return $tutors;
    }

    private function universityPlanPrioritySql(): string
    {
        return "CASE
            WHEN LOWER(COALESCE(sp.search_ranking, '')) IN ('top', 'highest') THEN 5
            WHEN LOWER(COALESCE(sp.search_ranking, '')) IN ('priority', 'high') THEN 4
            WHEN LOWER(COALESCE(sp.badge_level, '')) IN ('master', 'premium') THEN 4
            WHEN LOWER(COALESCE(sp.badge_level, '')) IN ('expert', 'standard') THEN 3
            WHEN LOWER(COALESCE(sp.search_ranking, '')) IN ('normal', 'medium') THEN 2
            WHEN LOWER(COALESCE(sp.name, '')) = 'premium' THEN 4
            WHEN LOWER(COALESCE(sp.name, '')) = 'standard' THEN 3
            WHEN LOWER(COALESCE(sp.name, '')) = 'basic' THEN 1
            ELSE 0
        END";
    }

    private function universityTutorMatchesRequest(array $request, array $tutor): bool
    {
        if (($tutor['status'] ?? '') !== 'approved' || !$this->universityTutorHasActivePlan($tutor)) {
            return false;
        }

        $requestMode = strtolower(trim((string) ($request['delivery_mode'] ?? '')));
        $tutorMode = strtolower(trim((string) ($tutor['teaching_mode'] ?? '')));

        if ($requestMode !== '' && $tutorMode !== '' && $requestMode !== 'both' && $tutorMode !== 'both' && $requestMode !== $tutorMode) {
            return false;
        }

        $topic = trim((string) ($request['topic'] ?? ''));
        $serviceCategory = trim((string) ($request['service_category'] ?? ''));
        $tutorServiceAreas = $this->decodeUniversityJsonList($tutor['service_areas_json'] ?? '[]');

        if ($topic !== '' && in_array($topic, $tutorServiceAreas, true)) {
            return true;
        }

        $categoryServices = self::SERVICE_CATEGORIES[$serviceCategory] ?? [];

        if ($categoryServices === []) {
            return false;
        }

        return array_intersect($tutorServiceAreas, $categoryServices) !== [];
    }

    private function universityTutorHasActivePlan(array $tutor): bool
    {
        $userId = (int) ($tutor['user_id'] ?? 0);

        if ($userId <= 0) {
            return false;
        }

        $now = date('Y-m-d H:i:s');

        return (bool) \Config\Database::connect()
            ->table('tutor_subscriptions')
            ->where('user_id', $userId)
            ->where('status', 'active')
            ->where('current_period_start <=', $now)
            ->where('current_period_end >=', $now)
            ->limit(1)
            ->get()
            ->getRowArray();
    }

    private function broadcastUniversityRequestToTutors(array $request, array $tutors): int
    {
        if (empty($tutors)) {
            return 0;
        }

        $sentCount = 0;
        $emailConfig = config('Email');

        foreach ($tutors as $tutor) {
            try {
                $email = \Config\Services::email(null, false);
                $email->setFrom($emailConfig->fromEmail, $emailConfig->fromName);
                $email->setReplyTo($request['email'], $request['full_name']);
                $email->setTo($tutor['email']);
                $email->setSubject('New university support request - ' . $request['reference_code']);
                $email->setMessage($this->buildUniversityRequestTutorEmailHtml($request, $tutor));
                $email->setAltMessage($this->buildUniversityRequestTutorEmailText($request, $tutor));

                if ($email->send(false)) {
                    $sentCount++;
                } else {
                    log_message('error', 'University support request email failed for tutor ' . ($tutor['id'] ?? 'unknown') . ': ' . trim(strip_tags($email->printDebugger(['headers', 'subject']))));
                }
            } catch (\Throwable $e) {
                log_message('error', 'University support request email exception for tutor ' . ($tutor['id'] ?? 'unknown') . ': ' . $e->getMessage());
            }
        }

        return $sentCount;
    }

    private function notifyUniversityRequesterOfSubmission(array $request): void
    {
        try {
            $requesterEmail = trim((string) ($request['email'] ?? ''));

            if ($requesterEmail === '') {
                return;
            }

            $emailConfig = config('Email');
            $email = \Config\Services::email(null, false);
            $email->setFrom($emailConfig->fromEmail, $emailConfig->fromName);
            $email->setTo($requesterEmail);
            $email->setSubject('We received your academic support request - ' . $request['reference_code']);
            $email->setMailType('html');
            $email->setMessage($this->buildUniversityRequesterConfirmationHtml($request));
            $email->setAltMessage($this->buildUniversityRequesterConfirmationText($request));
            $email->send(false);
        } catch (\Throwable $e) {
            log_message('error', 'Failed sending university support request confirmation: ' . $e->getMessage());
        }
    }

    private function buildUniversityRequestTutorEmailHtml(array $request, array $tutor): string
    {
        $companyName = $this->companyName();
        $notes = trim((string) ($request['notes'] ?? ''));
        $acceptUrl = $this->buildUniversityAcceptUrl((int) $request['id'], (int) $tutor['id'], (string) $request['reference_code']);

        return '<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>New University Support Request</title></head>
<body style="margin:0;padding:0;background:#f6f7f9;font-family:Arial,sans-serif;color:#1f2937;">
    <div style="max-width:640px;margin:0 auto;background:#ffffff;">
        <div style="background:#E55C0D;color:#ffffff;padding:24px;">
            <h1 style="margin:0;font-size:24px;">New university support request</h1>
            <p style="margin:8px 0 0;">' . esc($companyName) . '</p>
        </div>
        <div style="padding:24px;">
            <p>Hello ' . esc($tutor['full_name'] ?? 'Tutor') . ',</p>
            <p>A learner submitted a request that matches your University & College Support profile.</p>
            <table style="width:100%;border-collapse:collapse;margin:18px 0;">
                <tr><td style="padding:8px;border-bottom:1px solid #e5e7eb;font-weight:bold;">Reference</td><td style="padding:8px;border-bottom:1px solid #e5e7eb;">' . esc($request['reference_code']) . '</td></tr>
                <tr><td style="padding:8px;border-bottom:1px solid #e5e7eb;font-weight:bold;">Institution</td><td style="padding:8px;border-bottom:1px solid #e5e7eb;">' . esc($request['institution']) . '</td></tr>
                <tr><td style="padding:8px;border-bottom:1px solid #e5e7eb;font-weight:bold;">Service category</td><td style="padding:8px;border-bottom:1px solid #e5e7eb;">' . esc($request['service_category']) . '</td></tr>
                <tr><td style="padding:8px;border-bottom:1px solid #e5e7eb;font-weight:bold;">Topic</td><td style="padding:8px;border-bottom:1px solid #e5e7eb;">' . esc($request['topic']) . '</td></tr>
                <tr><td style="padding:8px;border-bottom:1px solid #e5e7eb;font-weight:bold;">Mode</td><td style="padding:8px;border-bottom:1px solid #e5e7eb;">' . esc($this->formatUniversityDeliveryMode((string) $request['delivery_mode'])) . '</td></tr>
                <tr><td style="padding:8px;border-bottom:1px solid #e5e7eb;font-weight:bold;">Location</td><td style="padding:8px;border-bottom:1px solid #e5e7eb;">' . esc($request['city_location']) . '</td></tr>
                <tr><td style="padding:8px;border-bottom:1px solid #e5e7eb;font-weight:bold;">Preferred date</td><td style="padding:8px;border-bottom:1px solid #e5e7eb;">' . esc($request['preferred_date'] ?: 'Flexible') . '</td></tr>
                <tr><td style="padding:8px;border-bottom:1px solid #e5e7eb;font-weight:bold;">Preferred time</td><td style="padding:8px;border-bottom:1px solid #e5e7eb;">' . esc($request['preferred_time'] ?: 'Flexible') . '</td></tr>
                <tr><td style="padding:8px;border-bottom:1px solid #e5e7eb;font-weight:bold;">Learner contact</td><td style="padding:8px;border-bottom:1px solid #e5e7eb;">' . esc($request['phone'] . ' / ' . $request['email']) . '</td></tr>
            </table>
            ' . ($notes !== '' ? '<div style="background:#fff7ed;border:1px solid #fed7aa;padding:14px;margin:18px 0;"><strong>Additional notes:</strong><br>' . nl2br(esc($notes)) . '</div>' : '') . '
            <p>If you are available, accept this request below. The learner will receive your registered contact details and can follow up directly.</p>
            <p style="margin:28px 0;"><a href="' . esc($acceptUrl) . '" style="background:#E55C0D;color:#ffffff;text-decoration:none;padding:12px 18px;border-radius:6px;font-weight:bold;">Accept this request</a></p>
            <p style="font-size:13px;color:#6b7280;">You received this because your approved profile service areas and teaching mode match this request.</p>
        </div>
    </div>
</body>
</html>';
    }

    private function buildUniversityRequestTutorEmailText(array $request, array $tutor): string
    {
        $acceptUrl = $this->buildUniversityAcceptUrl((int) $request['id'], (int) $tutor['id'], (string) $request['reference_code']);

        return "New university support request\n\n"
            . "Reference: {$request['reference_code']}\n"
            . "Institution: {$request['institution']}\n"
            . "Service category: {$request['service_category']}\n"
            . "Topic: {$request['topic']}\n"
            . 'Mode: ' . $this->formatUniversityDeliveryMode((string) $request['delivery_mode']) . "\n"
            . "Location: {$request['city_location']}\n"
            . 'Preferred date: ' . ($request['preferred_date'] ?: 'Flexible') . "\n"
            . 'Preferred time: ' . ($request['preferred_time'] ?: 'Flexible') . "\n"
            . "Learner contact: {$request['phone']} / {$request['email']}\n"
            . "Notes: " . trim((string) ($request['notes'] ?? '')) . "\n\n"
            . "Accept this request: {$acceptUrl}\n";
    }

    private function notifyUniversityRequesterOfTutorAcceptance(array $request, array $tutor): void
    {
        try {
            $requesterEmail = trim((string) ($request['email'] ?? ''));

            if ($requesterEmail === '') {
                return;
            }

            $emailConfig = config('Email');
            $email = \Config\Services::email(null, false);
            $tutorName = trim((string) ($tutor['full_name'] ?? 'University Tutor'));
            $tutorEmail = trim((string) ($tutor['email'] ?? ''));
            $tutorPhone = trim((string) ($tutor['phone'] ?? ''));
            $profileUrl = site_url('university-tutor/' . (int) ($tutor['id'] ?? 0));

            $email->setFrom($emailConfig->fromEmail, $emailConfig->fromName);
            $email->setReplyTo($tutorEmail, $tutorName);
            $email->setTo($requesterEmail);
            $email->setSubject('A university tutor accepted your request - ' . $request['reference_code']);
            $email->setMailType('html');
            $content = "
                <h2>A university tutor accepted your request</h2>
                <p>An approved university specialist has accepted your academic support request.</p>

                <div class='highlight'>
                    <h3 style='margin-top: 0; color: #2C3E50;'>Request Summary</h3>
                    <p><strong>Reference:</strong> " . esc($request['reference_code']) . "</p>
                    <p><strong>Topic:</strong> " . esc($request['topic']) . "</p>
                    <p><strong>Service Category:</strong> " . esc($request['service_category']) . "</p>
                </div>

                <div class='highlight'>
                    <h3 style='margin-top: 0; color: #2C3E50;'>Tutor Details</h3>
                    <p><strong>Name:</strong> " . esc($tutorName) . "</p>
                    <p><strong>Email:</strong> " . esc($tutorEmail !== '' ? $tutorEmail : 'Not provided') . "</p>
                    <p><strong>Phone:</strong> " . esc($tutorPhone !== '' ? $tutorPhone : 'Not provided') . "</p>
                </div>

                <div style='text-align: center; margin: 30px 0;'>
                    <a href='" . esc($profileUrl) . "' class='btn'>View Tutor Profile</a>
                </div>

                <p>Please contact the tutor directly using the details above, or keep this email for follow-up with our team.</p>
            ";
            $email->setMessage($this->generateEmailTemplate($content, 'University Tutor Accepted Your Request'));
            $email->setAltMessage(
                "A university tutor accepted your request {$request['reference_code']}.\n\n"
                . "Tutor: {$tutorName}\n"
                . 'Email: ' . ($tutorEmail !== '' ? $tutorEmail : 'Not provided') . "\n"
                . 'Phone: ' . ($tutorPhone !== '' ? $tutorPhone : 'Not provided') . "\n"
                . "Topic: {$request['topic']}\n"
                . "Profile: {$profileUrl}\n"
            );
            $email->send(false);
        } catch (\Throwable $e) {
            log_message('error', 'Failed notifying requester of university tutor acceptance: ' . $e->getMessage());
        }
    }

    private function buildUniversityRequesterConfirmationHtml(array $request): string
    {
        $content = "
            <h2>Academic support request received</h2>
            <p>Hello " . esc($request['full_name'] ?? 'there') . ",</p>
            <p>Thank you for submitting your academic support request. We have received it and shared it with suitable approved university tutors where a match is available.</p>

            <div class='code-box'>
                <p style='margin: 0 0 10px 0; color: #666;'>Your reference code</p>
                <div class='code'>" . esc($request['reference_code']) . "</div>
            </div>

            <div class='highlight'>
                <h3 style='margin-top: 0; color: #2C3E50;'>Request Details</h3>
                <p><strong>Service Category:</strong> " . esc($request['service_category']) . "</p>
                <p><strong>Topic:</strong> " . esc($request['topic']) . "</p>
                <p><strong>Mode:</strong> " . esc($this->formatUniversityDeliveryMode((string) $request['delivery_mode'])) . "</p>
                <p><strong>Location:</strong> " . esc($request['city_location'] ?? '') . "</p>
            </div>

            <div class='highlight'>
                <h3 style='margin-top: 0; color: #2C3E50;'>What happens next?</h3>
                <ul style='color: #555; padding-left: 20px;'>
                    <li>Approved matching tutors are notified about your request.</li>
                    <li>A tutor may accept the request and contact you using the details provided.</li>
                    <li>Please keep your reference code for follow-up with our team.</li>
                </ul>
            </div>
        ";

        return $this->generateEmailTemplate($content, 'Academic Support Request Received');
    }

    private function buildUniversityRequesterConfirmationText(array $request): string
    {
        return "Request received\n\n"
            . "Hello " . ($request['full_name'] ?? 'there') . ",\n\n"
            . "Thank you for submitting your academic support request. We have received it and shared it with suitable approved university tutors where a match is available.\n\n"
            . "Reference: {$request['reference_code']}\n"
            . "Service category: {$request['service_category']}\n"
            . "Topic: {$request['topic']}\n"
            . 'Mode: ' . $this->formatUniversityDeliveryMode((string) $request['delivery_mode']) . "\n\n"
            . "Please keep your reference code for any follow-up with our team.";
    }

    private function notifyAdminOfUniversityLectureRequest(array $request, int $matchedCount, int $emailedCount): void
    {
        try {
            $adminEmail = getenv('ADMIN_EMAIL') ?: $this->siteSettingModel->getValue('contact_email', 'info@tutorconnectmw.com');

            if (!$adminEmail) {
                return;
            }

            $emailConfig = config('Email');
            $email = \Config\Services::email(null, false);
            $email->setFrom($emailConfig->fromEmail, $emailConfig->fromName);
            $email->setTo($adminEmail);
            $email->setSubject('New university support request - ' . $request['reference_code']);
            $email->setMessage(
                '<p>A new University & College Support request was submitted.</p>'
                . '<p><strong>Reference:</strong> ' . esc($request['reference_code']) . '</p>'
                . '<p><strong>Institution:</strong> ' . esc($request['institution']) . '</p>'
                . '<p><strong>Service category:</strong> ' . esc($request['service_category']) . '</p>'
                . '<p><strong>Topic:</strong> ' . esc($request['topic']) . '</p>'
                . '<p><strong>Mode:</strong> ' . esc($this->formatUniversityDeliveryMode((string) $request['delivery_mode'])) . '</p>'
                . '<p><strong>Location:</strong> ' . esc($request['city_location']) . '</p>'
                . '<p><strong>Learner contact:</strong> ' . esc($request['phone'] . ' / ' . $request['email']) . '</p>'
                . '<p><strong>Matched tutors:</strong> ' . $matchedCount . '<br><strong>Email sent:</strong> ' . $emailedCount . '</p>'
            );
            $email->setAltMessage(
                "New university support request {$request['reference_code']}\n"
                . "Institution: {$request['institution']}\n"
                . "Service category: {$request['service_category']}\n"
                . "Topic: {$request['topic']}\n"
                . 'Mode: ' . $this->formatUniversityDeliveryMode((string) $request['delivery_mode']) . "\n"
                . "Location: {$request['city_location']}\n"
                . "Learner contact: {$request['phone']} / {$request['email']}\n"
                . "Matched tutors: {$matchedCount}\nEmail sent: {$emailedCount}"
            );
            $email->send(false);
        } catch (\Throwable $e) {
            log_message('error', 'Failed notifying admin of university support request: ' . $e->getMessage());
        }
    }

    private function decodeUniversityJsonList($value): array
    {
        $decoded = json_decode((string) $value, true);

        if (!is_array($decoded)) {
            return [];
        }

        return array_values(array_filter(array_map(static fn ($item) => trim((string) $item), $decoded), static fn ($item) => $item !== ''));
    }

    private function formatUniversityDeliveryMode(string $mode): string
    {
        return $mode === 'Both' ? 'Online or physical' : $mode;
    }

    private function buildUniversityAcceptUrl(int $requestId, int $tutorId, string $referenceCode): string
    {
        return site_url('university-requests/accept/' . rawurlencode($referenceCode))
            . '?tutor=' . $tutorId
            . '&token=' . $this->makeUniversityAcceptToken($requestId, $tutorId);
    }

    private function makeUniversityAcceptToken(int $requestId, int $tutorId): string
    {
        return hash_hmac('sha256', 'university-request|' . $requestId . '|' . $tutorId, $this->universityAcceptTokenSecret());
    }

    private function isValidUniversityAcceptToken(int $requestId, int $tutorId, string $token): bool
    {
        return hash_equals($this->makeUniversityAcceptToken($requestId, $tutorId), $token);
    }

    private function universityAcceptTokenSecret(): string
    {
        $key = (string) (config('Encryption')->key ?? '');

        return $key !== '' ? $key : (string) (getenv('app.baseURL') ?: FCPATH);
    }

    private function companyName(): string
    {
        return $this->siteSettingModel->getValue('company_name', 'TutorConnect Malawi');
    }

    private function ensureTables(): void
    {
        $db = \Config\Database::connect();
        $forge = \Config\Database::forge();

        if (!$db->tableExists('university_college_tutors')) {
            $forge->addField([
                'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
                'user_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
                'account_type' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'individual'],
                'username' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
                'reference_code' => ['type' => 'VARCHAR', 'constraint' => 30],
                'full_name' => ['type' => 'VARCHAR', 'constraint' => 150],
                'email' => ['type' => 'VARCHAR', 'constraint' => 150],
                'phone' => ['type' => 'VARCHAR', 'constraint' => 30],
                'profile_picture' => ['type' => 'VARCHAR', 'constraint' => 255],
                'national_id_file' => ['type' => 'VARCHAR', 'constraint' => 255],
                'certification_files_json' => ['type' => 'TEXT'],
                'institutions_json' => ['type' => 'TEXT'],
                'specializations_json' => ['type' => 'TEXT'],
                'service_areas_json' => ['type' => 'TEXT'],
                'year_of_study_or_graduation' => ['type' => 'VARCHAR', 'constraint' => 50],
                'bio' => ['type' => 'TEXT'],
                'references_json' => ['type' => 'TEXT', 'null' => true],
                'work_status' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
                'employer_name' => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
                'employer_contact' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
                'available_days_json' => ['type' => 'TEXT'],
                'preferred_times_json' => ['type' => 'TEXT'],
                'teaching_mode' => ['type' => 'VARCHAR', 'constraint' => 20],
                'city_location' => ['type' => 'VARCHAR', 'constraint' => 150],
                'hourly_rate' => ['type' => 'DECIMAL', 'constraint' => '12,2', 'null' => true],
                'consultation_package_rate' => ['type' => 'DECIMAL', 'constraint' => '12,2', 'null' => true],
                'dissertation_package_rate' => ['type' => 'DECIMAL', 'constraint' => '12,2', 'null' => true],
                'exam_preparation_rate' => ['type' => 'DECIMAL', 'constraint' => '12,2', 'null' => true],
                'subscription_plan' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'Basic'],
                'status' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'pending_review'],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
                'updated_at' => ['type' => 'DATETIME', 'null' => true],
            ]);

            $forge->addKey('id', true);
            $forge->addKey('user_id');
            $forge->addKey('username');
            $forge->addUniqueKey('reference_code');
            $forge->addKey('email');
            $forge->addKey('phone');
            $forge->addKey('teaching_mode');
            $forge->addKey('status');
            $forge->createTable('university_college_tutors', true);
        } else {
            $missingColumns = [];

            if (!$db->fieldExists('user_id', 'university_college_tutors')) {
                $missingColumns['user_id'] = ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true];
            }

            if (!$db->fieldExists('account_type', 'university_college_tutors')) {
                $missingColumns['account_type'] = ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'individual'];
            }

            if (!$db->fieldExists('username', 'university_college_tutors')) {
                $missingColumns['username'] = ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true];
            }

            if ($missingColumns !== []) {
                $forge->addColumn('university_college_tutors', $missingColumns);
            }
        }

        if (!$db->tableExists('university_lecture_requests')) {
            $forge->addField([
                'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
                'reference_code' => ['type' => 'VARCHAR', 'constraint' => 30],
                'full_name' => ['type' => 'VARCHAR', 'constraint' => 150],
                'email' => ['type' => 'VARCHAR', 'constraint' => 150],
                'phone' => ['type' => 'VARCHAR', 'constraint' => 30],
                'institution' => ['type' => 'VARCHAR', 'constraint' => 150],
                'service_category' => ['type' => 'VARCHAR', 'constraint' => 80],
                'topic' => ['type' => 'VARCHAR', 'constraint' => 255],
                'delivery_mode' => ['type' => 'VARCHAR', 'constraint' => 20],
                'city_location' => ['type' => 'VARCHAR', 'constraint' => 150],
                'preferred_date' => ['type' => 'DATE', 'null' => true],
                'preferred_time' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
                'budget_range' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
                'notes' => ['type' => 'TEXT', 'null' => true],
                'status' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'open'],
                'matched_tutor_count' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'default' => 0],
                'emailed_tutor_count' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'default' => 0],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
                'updated_at' => ['type' => 'DATETIME', 'null' => true],
            ]);

            $forge->addKey('id', true);
            $forge->addUniqueKey('reference_code');
            $forge->addKey('service_category');
            $forge->addKey('delivery_mode');
            $forge->addKey('status');
            $forge->createTable('university_lecture_requests', true);
        } else {
            $missingLectureColumns = [];

            if (!$db->fieldExists('matched_tutor_count', 'university_lecture_requests')) {
                $missingLectureColumns['matched_tutor_count'] = ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'default' => 0];
            }

            if (!$db->fieldExists('emailed_tutor_count', 'university_lecture_requests')) {
                $missingLectureColumns['emailed_tutor_count'] = ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'default' => 0];
            }

            if ($missingLectureColumns !== []) {
                $forge->addColumn('university_lecture_requests', $missingLectureColumns);
            }
        }

        if (!$db->tableExists('university_lecture_request_applications')) {
            $forge->addField([
                'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
                'university_lecture_request_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
                'university_tutor_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
                'tutor_email' => ['type' => 'VARCHAR', 'constraint' => 150],
                'status' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'accepted'],
                'accepted_at' => ['type' => 'DATETIME', 'null' => true],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
                'updated_at' => ['type' => 'DATETIME', 'null' => true],
            ]);

            $forge->addKey('id', true);
            $forge->addKey('university_lecture_request_id');
            $forge->addKey('university_tutor_id');
            $forge->addKey('status');
            $forge->addUniqueKey(['university_lecture_request_id', 'university_tutor_id']);
            $forge->createTable('university_lecture_request_applications', true);
        }
    }
}
