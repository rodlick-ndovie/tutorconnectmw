<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\TutorModel;
use App\Models\SiteSettingModel;

class Auth extends BaseController
{
    protected $userModel;
    protected $tutorModel;
    protected $siteSettingModel;
    protected $db;

    /**
     * Get site setting value
     */
    private function getSiteSetting(string $key, string $default = ''): string
    {
        try {
            return $this->siteSettingModel->getValue($key, $default);
        } catch (\Exception $e) {
            return $default;
        }
    }

    /**
     * Generate professional HTML email template
     */
    private function generateEmailTemplate(string $content, string $subject = '', bool $showFooter = true): string
    {
        $companyName = $this->getSiteSetting('company_name', 'TutorConnect Malawi');
        $logoUrl = $this->getSiteSetting('logo_url', base_url('assets/images/logo.png'));
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
                <p style='margin-top: 20px; color: #999;'>© " . date('Y') . " {$companyName}. All rights reserved.</p>
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
                .logo { max-width: 200px; height: auto; }
                .content { padding: 30px 20px; }
                .footer { background: #f8f9fa; padding: 20px; text-align: center; border-top: 1px solid #dee2e6; }
                .btn { display: inline-block; background: #E55C0D; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: 600; margin: 10px 0; }
                .btn:hover { background: #C0392B; }
                .highlight { background: linear-gradient(135deg, #FFF3CD, #FFEAA7); padding: 20px; border-radius: 6px; margin: 20px 0; border-left: 4px solid #E55C0D; }
                .code-box { background: #f8f9fa; border: 2px dashed #E55C0D; padding: 20px; text-align: center; border-radius: 6px; margin: 20px 0; }
                .code { font-size: 24px; font-weight: bold; color: #E55C0D; letter-spacing: 3px; font-family: 'Courier New', monospace; }
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

    public function __construct()
    {
        $this->userModel = new User();
        $this->tutorModel = new TutorModel();
        $this->siteSettingModel = new SiteSettingModel();
        $this->db = \Config\Database::connect();
    }

    public function register()
    {
        if (session()->get('user_id')) {
            return redirect()->to('/trainer/dashboard');
        }

        // Clear old registration data if not in an active registration session
        // Only keep data if coming from back button or step navigation
        $fromBack = $this->request->getGet('back');
        if (!$fromBack && !session()->get('registration_step')) {
            session()->remove('registration_data');
            session()->remove('registration_step');
            // Also clear any old flash messages
            session()->remove('success');
            session()->remove('error');
        }

        // Get current step from session or default to 1
        $step = session()->get('registration_step') ?? 1;

        $data['title'] = 'Register - TutorConnect Malawi';
        $data['step'] = $step;
        $data['form_data'] = session()->get('registration_data') ?? [];

        return view('auth/register', $data);
    }

    public function registerStep1()
    {
        // Get form data for step 1
        $data = [
            'first_name' => trim($this->request->getPost('first_name')),
            'last_name' => trim($this->request->getPost('last_name')),
            'email' => strtolower(trim($this->request->getPost('email'))),
            'phone' => trim($this->request->getPost('phone')),
            'gender' => $this->request->getPost('gender'),
            'district' => $this->request->getPost('district'),
            'location' => trim($this->request->getPost('location')),
            'is_employed' => $this->request->getPost('is_employed'),
            'school_name' => trim($this->request->getPost('school_name'))
        ];

        // Validation for step 1
        $errors = [];

        if (empty($data['first_name'])) $errors['first_name'] = 'First name is required';
        if (empty($data['last_name'])) $errors['last_name'] = 'Last name is required';
        if (empty($data['email'])) $errors['email'] = 'Email is required';
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Invalid email format';
        if (empty($data['phone'])) $errors['phone'] = 'Phone number is required';
        if (empty($data['gender'])) $errors['gender'] = 'Gender is required';
        if (empty($data['district'])) $errors['district'] = 'District is required';
        if (empty($data['location'])) $errors['location'] = 'Location is required';

        // Check for existing email (only among trainers)
        $existingUser = $this->userModel->where('email', $data['email'])->where('role', 'trainer')->first();
        if ($existingUser) {
            $errors['email'] = 'Email address already registered';
        }

        // Check for existing phone (only among trainers)
        $existingPhone = $this->userModel->where('phone', $data['phone'])->where('role', 'trainer')->first();
        if ($existingPhone) {
            $errors['phone'] = 'Phone number already registered';
        }

        if (!empty($errors)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $errors,
                    'message' => 'Please correct the validation errors.'
                ]);
            }
            return redirect()->back()->with('errors', $errors)->withInput();
        }

        // Save step 1 data to session
        session()->set('registration_data', $data);
        session()->set('registration_step', 2);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Step 1 completed',
                'next_step' => 2
            ]);
        }

        return redirect()->to('register')->with('success', 'Step 1 completed. Please continue.');
    }

    public function registerStep2()
    {
        // Get step 1 data from session
        $step1Data = session()->get('registration_data');

        if (!$step1Data) {
            return redirect()->to('register')->with('error', 'Please complete step 1 first');
        }

        // Get form data for step 2
        $step2Data = [
            'username' => trim($this->request->getPost('username')),
            'password' => $this->request->getPost('password'),
            'confirm_password' => $this->request->getPost('confirm_password')
        ];

        // Validation for step 2
        $errors = [];

        if (empty($step2Data['username'])) $errors['username'] = 'Username is required';
        if (empty($step2Data['password'])) {
            $errors['password'] = 'Password is required';
        } else {
            if (strlen($step2Data['password']) < 8) {
                $errors['password'] = 'Password must be at least 8 characters';
            } elseif (!preg_match('/\d/', $step2Data['password'])) {
                $errors['password'] = 'Password must contain at least one number';
            }
        }
        if ($step2Data['password'] !== $step2Data['confirm_password']) {
            $errors['confirm_password'] = 'Passwords do not match';
        }

        // Check for existing username (only among trainers)
        $existingUsername = $this->userModel->where('username', $step2Data['username'])->where('role', 'trainer')->first();
        if ($existingUsername) {
            $errors['username'] = 'Username already taken';
        }

        if (!empty($errors)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $errors,
                    'message' => 'Please correct the validation errors.'
                ]);
            }
            return redirect()->back()->with('errors', $errors)->withInput();
        }

        // Merge step 1 and step 2 data
        $completeData = array_merge($step1Data, $step2Data);

        try {
            // Prepare user data for insertion
            $userData = [
                'username' => $completeData['username'],
                'email' => $completeData['email'],
                'password' => $completeData['password'], // Let the model's beforeInsert callback hash it
                'first_name' => $completeData['first_name'],
                'last_name' => $completeData['last_name'],
                'phone' => $completeData['phone'],
                'gender' => $completeData['gender'],
                'district' => $completeData['district'],
                'location' => $completeData['location'],
                'is_employed' => (int)$completeData['is_employed'],
                'school_name' => $completeData['school_name'] ?: null,
                'role' => 'trainer',
                'is_active' => 0,
                'registration_completed' => 0,
                'is_verified' => 0,
                'tutor_status' => 'pending',
                'subscription_plan' => 'Free Trial'
            ];

            // Generate OTP for email verification
            $userData['otp_code'] = rand(100000, 999999);
            $userData['otp_expires_at'] = date('Y-m-d H:i:s', strtotime('+15 minutes'));

            // Insert user - skip validation since we already validated above
            $this->userModel->skipValidation(true);
            $userId = $this->userModel->insert($userData);
            $this->userModel->skipValidation(false);

            if (!$userId) {
                log_message('error', 'Registration failed: ' . json_encode($this->userModel->errors()));
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Registration failed. Please try again.'
                    ]);
                }
                return redirect()->back()->with('error', 'Registration failed. Please try again.')->withInput();
            }

            // Clear registration session data
            session()->remove('registration_data');
            session()->remove('registration_step');

            log_message('info', 'New tutor registered: ' . $completeData['email'] . ' (ID: ' . $userId . ')');
            log_message('info', 'OTP code for ' . $completeData['email'] . ': ' . $userData['otp_code']);

            // Try to send email verification
            try {
                $emailService = \Config\Services::email();
                $emailService->setFrom($this->getSiteSetting('contact_email', 'info@tutorconnectmw.com'), $this->getSiteSetting('company_name', 'TutorConnect Malawi'));
                $emailService->setTo($completeData['email']);
                $emailService->setSubject($this->getSiteSetting('company_name', 'TutorConnect Malawi') . ' - Email Verification');
                $emailService->setMailType('html');

                // Create verification link
                $verificationLink = 'http://localhost/tutorconnectmw/verify-email?email=' . urlencode($completeData['email']) . '&code=' . $userData['otp_code'];

                $content = "
                    <h2>Welcome to " . $this->getSiteSetting('company_name', 'TutorConnect Malawi') . ", {$completeData['first_name']}!</h2>

                    <p>Thank you for registering as a tutor. We're excited to have you join our community of educators across Malawi.</p>

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
                            <li>Your account will be activated</li>
                            <li>You can complete your tutor profile</li>
                            <li>Upload verification documents</li>
                            <li>Start connecting with students</li>
                        </ul>
                    </div>

                    <p>If you didn't create this account, please ignore this email.</p>
                ";

                $emailService->setMessage($this->generateEmailTemplate($content, $this->getSiteSetting('company_name', 'TutorConnect Malawi') . ' - Email Verification'));

                if ($emailService->send(false)) {
                    log_message('info', 'Verification email sent to: ' . $completeData['email']);
                } else {
                    log_message('error', 'Failed to send verification email to: ' . $completeData['email']);
                    log_message('error', 'Email debug: ' . $emailService->printDebugger(['headers']));
                }
            } catch (\Exception $emailException) {
                log_message('error', 'Email service error: ' . $emailException->getMessage());
                log_message('error', 'Email trace: ' . $emailException->getTraceAsString());
            }

            // Success response
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Registration successful! Please check your email for verification code.',
                    'redirect' => base_url('verify-email?email=' . urlencode($completeData['email']))
                ]);
            }

            return redirect()->to('verify-email?email=' . urlencode($completeData['email']))
                ->with('success', 'Registration successful! Please check your email for verification code.');

        } catch (\Exception $e) {
            log_message('error', 'Registration exception: ' . $e->getMessage());
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'An error occurred during registration. Please try again.'
                ]);
            }
            return redirect()->back()->with('error', 'An error occurred during registration. Please try again.')->withInput();
        }
    }

    public function registerBack()
    {
        // Go back to step 1
        session()->set('registration_step', 1);
        return redirect()->to('register?back=1');
    }

    public function attemptLogin()
    {
        $rules = [
            'login' => 'required',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please provide both username/email and password.'
            ]);
        }

        $login = $this->request->getPost('login');
        $password = $this->request->getPost('password');

        // Try to find user by username or email (allow both trainer and admin roles)
        $user = $this->userModel
            ->groupStart()
                ->where('role', 'trainer')
                ->orWhere('role', 'admin')
            ->groupEnd()
            ->groupStart()
                ->where('username', $login)
                ->orWhere('email', $login)
            ->groupEnd()
            ->first();

        if (!$user) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid credentials. Please try again.'
            ]);
        }

        // Check if email is verified
        if (!$user['is_verified']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please verify your email address before logging in.'
            ]);
        }

        // Verify password
        if (!password_verify($password, $user['password_hash'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid credentials. Please try again.'
            ]);
        }

        // Check if account is active
        if (!$user['is_active']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Your account is currently inactive. Please contact support.'
            ]);
        }

        // Set session data
        $sessionData = [
            'user_id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'role' => $user['role'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'is_logged_in' => true
        ];

        session()->set($sessionData);

        log_message('info', 'User logged in: ' . $user['email']);

        // For trainers, check if documents need to be uploaded (first time only)
        if ($user['role'] === 'trainer') {
            // Check if user needs to complete profile documentation
            $needsDocuments = false;
            $documents = json_decode($user['verification_documents'], true);

            if (empty($documents) || !is_array($documents)) {
                $needsDocuments = true;
            } else {
                // Check if all required documents are uploaded
                $requiredDocs = ['national_id', 'academic_certificates', 'police_clearance'];
                $uploadedTypes = array_column($documents, 'document_type');

                foreach ($requiredDocs as $reqDoc) {
                    if (!in_array($reqDoc, $uploadedTypes)) {
                        $needsDocuments = true;
                        break;
                    }
                }
            }

            // Check if profile picture is uploaded
            $needsProfilePicture = empty($user['profile_picture']);

            // Redirect to complete-profile only if documents are missing (first time setup)
            if ($needsDocuments || $needsProfilePicture) {
                $redirectUrl = base_url('trainer/dashboard/complete-profile');
            } else {
                $redirectUrl = base_url('trainer/dashboard');
            }
        } else if ($user['role'] === 'admin') {
            // For admins, redirect to admin dashboard
            $redirectUrl = base_url('admin/dashboard');
        } else {
            // For other roles, go to general dashboard
            $redirectUrl = base_url('dashboard');
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Login successful!',
            'redirect' => $redirectUrl
        ]);
    }

    public function verifyEmail()
    {
        $email = $this->request->getGet('email');
        $code = $this->request->getGet('code');

        if (!$email) {
            return redirect()->to('/register')->with('error', 'Email parameter is required');
        }

        $user = $this->userModel->where('email', $email)->first();

        if (!$user) {
            return redirect()->to('/register')->with('error', 'User not found');
        }

        if ($user['is_verified']) {
            return redirect()->to('/login')->with('success', 'Email already verified. Please login.');
        }

        // If code is provided in URL, auto-verify
        if ($code) {
            // Check if OTP is valid and not expired
            if ($user['otp_code'] != $code) {
                return redirect()->to('verify-email?email=' . urlencode($email))
                    ->with('error', 'Invalid verification code');
            }

            if (strtotime($user['otp_expires_at']) < time()) {
                return redirect()->to('verify-email?email=' . urlencode($email))
                    ->with('error', 'Verification code has expired. Please request a new one.');
            }

            // Update user as verified
            $this->userModel->update($user['id'], [
                'is_verified' => 1,
                'is_active' => 1,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'otp_code' => null,
                'otp_expires_at' => null
            ]);

            log_message('info', 'Email verified via link for user: ' . $email);

            // Send welcome email (professional HTML template)
            try {
                $emailService = \Config\Services::email();
                $emailService->setFrom($this->getSiteSetting('contact_email', 'info@tutorconnectmw.com'), $this->getSiteSetting('company_name', 'TutorConnect Malawi'));
                $emailService->setTo($user['email']);
                $emailService->setSubject($this->getSiteSetting('company_name', 'TutorConnect Malawi') . ' - Welcome!');
                $emailService->setMailType('html');

                $content = "
                    <h2>Welcome to " . $this->getSiteSetting('company_name', 'TutorConnect Malawi') . ", {$user['first_name']}!</h2>

                    <p>Congratulations! Your email has been successfully verified and your tutor account is now fully activated.</p>

                    <div class='highlight'>
                        <h3 style='margin-top: 0; color: #2C3E50;'>🎉 Your Account is Ready!</h3>
                        <p>You now have full access to our platform. Here's what you can do to get started:</p>
                    </div>

                    <h3 style='color: #2C3E50;'>Next Steps:</h3>
                    <ul style='color: #555;'>
                        <li><strong>Complete Your Profile:</strong> Add your bio, qualifications, and teaching experience</li>
                        <li><strong>Upload Documents:</strong> Submit your ID, certificates, and clearances for verification</li>
                        <li><strong>Set Availability:</strong> Define your teaching subjects, rates, and available times</li>
                        <li><strong>Start Teaching:</strong> Connect with students and begin your tutoring journey</li>
                    </ul>

                    <div style='text-align: center; margin: 30px 0;'>
                        <a href='" . base_url('trainer/dashboard/complete-profile') . "' class='btn'>Complete Your Profile</a>
                    </div>

                    <div style='background: #f8f9fa; padding: 20px; border-radius: 6px; margin: 20px 0;'>
                        <h4 style='margin-top: 0; color: #2C3E50;'>💡 Pro Tip</h4>
                        <p style='margin-bottom: 0; color: #666;'>Tutors with complete profiles and verified documents get 10x more student inquiries!</p>
                    </div>

                    <p>Need help getting started? Our support team is here to assist you.</p>

                    <div style='text-align: center; margin: 30px 0;'>
                        <a href='" . base_url('login') . "' class='btn'>Login to Your Account</a>
                    </div>
                ";

                $emailService->setMessage($this->generateEmailTemplate($content, $this->getSiteSetting('company_name', 'TutorConnect Malawi') . ' - Welcome!'));

                log_message('info', 'About to send welcome email to: ' . $user['email']);
                $sendResult = $emailService->send(false);
                log_message('info', 'Welcome email send() result: ' . ($sendResult ? 'true' : 'false'));

                if ($sendResult) {
                    log_message('info', 'Welcome email sent successfully to: ' . $user['email']);
                } else {
                    log_message('error', 'Failed to send welcome email to: ' . $user['email']);
                    $debugInfo = $emailService->printDebugger(['headers', 'subject', 'body']);
                    log_message('error', 'Email debug info: ' . $debugInfo);
                }
            } catch (\Exception $emailException) {
                log_message('error', 'Welcome email exception: ' . $emailException->getMessage());
                log_message('error', 'Email trace: ' . $emailException->getTraceAsString());
            }

            // Clear any old session messages before setting the new verification success message
            session()->remove('success');
            session()->remove('error');

            return redirect()->to('/login')->with('success', 'Email verified successfully! You can now login to complete your profile.');
        }

        $data = [
            'title' => 'Verify Email - TutorConnect Malawi',
            'email' => $email,
            'user' => $user
        ];

        return view('auth/verify_email', $data);
    }

    public function confirmEmail()
    {
        $email = $this->request->getPost('email');
        $otp = $this->request->getPost('otp');

        if (!$email || !$otp) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Email and OTP are required'
                ]);
            }
            return redirect()->back()->with('error', 'Email and OTP are required');
        }

        $user = $this->userModel->where('email', $email)->first();

        if (!$user) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'User not found'
                ]);
            }
            return redirect()->to('/register')->with('error', 'User not found');
        }

        if ($user['is_verified']) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Email already verified. Please login.',
                    'redirect' => base_url('login')
                ]);
            }
            return redirect()->to('/login')->with('success', 'Email already verified. Please login.');
        }

        // Check if OTP is valid and not expired
        if ($user['otp_code'] != $otp) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid verification code'
                ]);
            }
            return redirect()->back()->with('error', 'Invalid verification code');
        }

        if (strtotime($user['otp_expires_at']) < time()) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Verification code has expired. Please request a new one.'
                ]);
            }
            return redirect()->back()->with('error', 'Verification code has expired. Please request a new one.');
        }

        try {
            // Update user as verified
            $this->userModel->update($user['id'], [
                'is_verified' => 1,
                'is_active' => 1,
                'otp_code' => null,
                'otp_expires_at' => null,
                'email_verified_at' => date('Y-m-d H:i:s')
            ]);

            log_message('info', 'Email verified for user: ' . $email);

            // Send welcome email (professional HTML template)
            try {
                $emailService = \Config\Services::email();
                $emailService->setFrom($this->getSiteSetting('contact_email', 'info@tutorconnectmw.com'), $this->getSiteSetting('company_name', 'TutorConnect Malawi'));
                $emailService->setTo($user['email']);
                $emailService->setSubject($this->getSiteSetting('company_name', 'TutorConnect Malawi') . ' - Welcome!');
                $emailService->setMailType('html');

                $content = "
                    <h2>Welcome to " . $this->getSiteSetting('company_name', 'TutorConnect Malawi') . ", {$user['first_name']}!</h2>

                    <p>Congratulations! Your email has been successfully verified and your tutor account is now fully activated.</p>

                    <div class='highlight'>
                        <h3 style='margin-top: 0; color: #2C3E50;'>🎉 Your Account is Ready!</h3>
                        <p>You now have full access to our platform. Here's what you can do to get started:</p>
                    </div>

                    <h3 style='color: #2C3E50;'>Next Steps:</h3>
                    <ul style='color: #555;'>
                        <li><strong>Complete Your Profile:</strong> Add your bio, qualifications, and teaching experience</li>
                        <li><strong>Upload Documents:</strong> Submit your ID, certificates, and clearances for verification</li>
                        <li><strong>Set Availability:</strong> Define your teaching subjects, rates, and available times</li>
                        <li><strong>Start Teaching:</strong> Connect with students and begin your tutoring journey</li>
                    </ul>

                    <div style='text-align: center; margin: 30px 0;'>
                        <a href='" . base_url('trainer/dashboard/complete-profile') . "' class='btn'>Complete Your Profile</a>
                    </div>

                    <div style='background: #f8f9fa; padding: 20px; border-radius: 6px; margin: 20px 0;'>
                        <h4 style='margin-top: 0; color: #2C3E50;'>💡 Pro Tip</h4>
                        <p style='margin-bottom: 0; color: #666;'>Tutors with complete profiles and verified documents get 10x more student inquiries!</p>
                    </div>

                    <p>Need help getting started? Our support team is here to assist you.</p>

                    <div style='text-align: center; margin: 30px 0;'>
                        <a href='" . base_url('login') . "' class='btn'>Login to Your Account</a>
                    </div>
                ";

                $emailService->setMessage($this->generateEmailTemplate($content, $this->getSiteSetting('company_name', 'TutorConnect Malawi') . ' - Welcome!'));

                log_message('info', 'About to send welcome email to: ' . $user['email']);
                $sendResult = $emailService->send(false);
                log_message('info', 'Welcome email send() result: ' . ($sendResult ? 'true' : 'false'));

                if ($sendResult) {
                    log_message('info', 'Welcome email sent successfully to: ' . $user['email']);
                } else {
                    log_message('error', 'Failed to send welcome email to: ' . $user['email']);
                    $debugInfo = $emailService->printDebugger(['headers', 'subject', 'body']);
                    log_message('error', 'Email debug info: ' . $debugInfo);
                }
            } catch (\Exception $emailException) {
                log_message('error', 'Welcome email exception: ' . $emailException->getMessage());
                log_message('error', 'Email trace: ' . $emailException->getTraceAsString());
            }

            // Clear any old session messages before setting the new verification success message
            session()->remove('success');
            session()->remove('error');

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Email verified successfully! Redirecting to login...',
                    'redirect' => base_url('login')
                ]);
            }

            return redirect()->to('/login')->with('success', 'Email verified successfully! You can now login to your account.');

        } catch (\Exception $e) {
            log_message('error', 'Email verification failed: ' . $e->getMessage());

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Verification failed. Please try again.'
                ]);
            }
            return redirect()->back()->with('error', 'Verification failed. Please try again.');
        }
    }

    public function resendOtp()
    {
        $email = $this->request->getPost('email');

        if (!$email) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Email is required'
            ]);
        }

        $user = $this->userModel->where('email', $email)->first();

        if (!$user) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        if ($user['is_verified']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Email already verified'
            ]);
        }

        // Generate new OTP
        $newOtp = rand(100000, 999999);
        $expiresAt = date('Y-m-d H:i:s', strtotime('+15 minutes'));

        try {
            // Update user with new OTP
            $this->userModel->update($user['id'], [
                'otp_code' => $newOtp,
                'otp_expires_at' => $expiresAt
            ]);

            // Send email
            try {
                $emailService = \Config\Services::email();
                $emailService->setFrom($this->getSiteSetting('contact_email', 'info@tutorconnectmw.com'), $this->getSiteSetting('company_name', 'TutorConnect Malawi'));
                $emailService->setTo($email);
                $emailService->setSubject($this->getSiteSetting('company_name', 'TutorConnect Malawi') . ' - New Verification Code');
                $emailService->setMailType('html');

                $content = "
                    <h2>New Verification Code</h2>

                    <p>Hello {$user['first_name']},</p>

                    <p>You requested a new verification code for your TutorConnect Malawi account.</p>

                    <div class='code-box'>
                        <p style='margin: 0 0 10px 0; color: #666;'>Your new verification code:</p>
                        <div class='code'>{$newOtp}</div>
                        <p style='margin: 10px 0 0 0; font-size: 14px; color: #666;'>Valid for 15 minutes</p>
                    </div>

                    <p>If you didn't request this code, please ignore this email.</p>

                    <p>Best regards,<br>TutorConnect Malawi Team</p>
                ";

                $emailService->setMessage($this->generateEmailTemplate($content, $this->getSiteSetting('company_name', 'TutorConnect Malawi') . ' - New Verification Code'));

                if ($emailService->send(false)) {
                    log_message('info', 'New OTP sent to: ' . $email . ' (OTP: ' . $newOtp . ')');
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'New verification code sent to your email'
                    ]);
                } else {
                    log_message('warning', 'Failed to send new OTP email to: ' . $email);
                    log_message('warning', 'Email debug: ' . $emailService->printDebugger(['headers']));
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Failed to send email. Please try again.'
                    ]);
                }
            } catch (\Exception $emailException) {
                log_message('warning', 'Email service error: ' . $emailException->getMessage());
                log_message('warning', 'Email trace: ' . $emailException->getTraceAsString());
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Email service not configured. Please contact support.'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Resend OTP failed: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to generate new code. Please try again.'
            ]);
        }
    }

    public function forgotPassword()
    {
        if (session()->get('user_id')) {
            return redirect()->to('/trainer/dashboard');
        }

        $data['title'] = 'Forgot Password - TutorConnect Malawi';
        return view('auth/forgot_password', $data);
    }

    public function requestPasswordReset()
    {
        $email = $this->request->getPost('email');

        if (!$email) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Email is required'
                ]);
            }
            return redirect()->back()->with('error', 'Email is required');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid email format'
                ]);
            }
            return redirect()->back()->with('error', 'Invalid email format');
        }

        $user = $this->userModel->where('email', $email)->first();

        if (!$user) {
            // Don't reveal if email exists or not for security
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'If an account with this email exists, a password reset link has been sent.'
                ]);
            }
            return redirect()->back()->with('success', 'If an account with this email exists, a password reset link has been sent.');
        }

        // Generate reset token
        $resetToken = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

        try {
            // Update user with reset token
            $this->userModel->update($user['id'], [
                'reset_token' => $resetToken,
                'reset_expires_at' => $expiresAt
            ]);

            // Send reset email
            try {
                $emailService = \Config\Services::email();
                $emailService->setFrom($this->getSiteSetting('contact_email', 'info@tutorconnectmw.com'), $this->getSiteSetting('company_name', 'TutorConnect Malawi'));
                $emailService->setTo($user['email']);
                $emailService->setSubject($this->getSiteSetting('company_name', 'TutorConnect Malawi') . ' - Password Reset');
                $emailService->setMailType('html');

                $resetLink = base_url('reset-password?token=' . $resetToken);

                $content = "
                    <h2>Password Reset Request</h2>

                    <p>Hello {$user['first_name']},</p>

                    <p>You requested to reset your password for your TutorConnect Malawi account.</p>

                    <div class='highlight'>
                        <p style='margin: 0; color: #2C3E50;'><strong>Click the button below to reset your password:</strong></p>
                    </div>

                    <div style='text-align: center; margin: 30px 0;'>
                        <a href='{$resetLink}' class='btn'>Reset My Password</a>
                    </div>

                    <p style='text-align: center; color: #666; font-size: 14px;'>
                        Or copy and paste this link: <br>
                        <a href='{$resetLink}' style='color: #E55C0D; word-break: break-all;'>{$resetLink}</a>
                    </p>

                    <div class='code-box'>
                        <p style='margin: 0 0 10px 0; color: #666; font-size: 14px;'>This link will expire in 1 hour for security reasons.</p>
                    </div>

                    <p>If you didn't request this password reset, please ignore this email. Your password will remain unchanged.</p>

                    <p>Best regards,<br>TutorConnect Malawi Team</p>
                ";

                $emailService->setMessage($this->generateEmailTemplate($content, $this->getSiteSetting('company_name', 'TutorConnect Malawi') . ' - Password Reset'));

                if ($emailService->send(false)) {
                    log_message('info', 'Password reset email sent to: ' . $user['email']);
                    if ($this->request->isAJAX()) {
                        return $this->response->setJSON([
                            'success' => true,
                            'message' => 'Password reset link has been sent to your email'
                        ]);
                    }
                    return redirect()->back()->with('success', 'Password reset link has been sent to your email');
                } else {
                    log_message('error', 'Failed to send password reset email to: ' . $user['email']);
                    log_message('error', 'Email debug: ' . $emailService->printDebugger(['headers']));
                    if ($this->request->isAJAX()) {
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Failed to send email. Please try again.'
                        ]);
                    }
                    return redirect()->back()->with('error', 'Failed to send email. Please try again.');
                }
            } catch (\Exception $emailException) {
                log_message('error', 'Password reset email exception: ' . $emailException->getMessage());
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Email service not configured. Please contact support.'
                    ]);
                }
                return redirect()->back()->with('error', 'Email service not configured. Please contact support.');
            }

        } catch (\Exception $e) {
            log_message('error', 'Password reset token generation failed: ' . $e->getMessage());
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to process request. Please try again.'
                ]);
            }
            return redirect()->back()->with('error', 'Failed to process request. Please try again.');
        }
    }

    public function resetPassword()
    {
        $token = $this->request->getGet('token');

        if (!$token) {
            return redirect()->to('/login')->with('error', 'Invalid reset link');
        }

        $user = $this->userModel->where('reset_token', $token)->first();

        if (!$user) {
            return redirect()->to('/login')->with('error', 'Invalid or expired reset link');
        }

        // Check if token is expired
        if (strtotime($user['reset_expires_at']) < time()) {
            return redirect()->to('/login')->with('error', 'Reset link has expired. Please request a new one.');
        }

        $data = [
            'title' => 'Reset Password - TutorConnect Malawi',
            'token' => $token,
            'user' => $user
        ];

        return view('auth/reset_password', $data);
    }

    public function updatePassword()
    {
        $token = $this->request->getPost('token');
        $password = $this->request->getPost('password');
        $confirmPassword = $this->request->getPost('confirm_password');

        // Check if this is an AJAX request
        $isAjax = $this->request->isAJAX();

        if (!$token) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid reset link'
                ]);
            }
            return redirect()->to('/login')->with('error', 'Invalid reset link');
        }

        $user = $this->userModel->where('reset_token', $token)->first();

        if (!$user) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid or expired reset link'
                ]);
            }
            return redirect()->to('/login')->with('error', 'Invalid or expired reset link');
        }

        // Check if token is expired
        if (strtotime($user['reset_expires_at']) < time()) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Reset link has expired. Please request a new one.'
                ]);
            }
            return redirect()->to('/login')->with('error', 'Reset link has expired. Please request a new one.');
        }

        // Validate password
        if (empty($password)) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Password is required'
                ]);
            }
            return redirect()->back()->with('error', 'Password is required');
        }

        if (strlen($password) < 8) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Password must be at least 8 characters'
                ]);
            }
            return redirect()->back()->with('error', 'Password must be at least 8 characters');
        }

        if (!preg_match('/\d/', $password)) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Password must contain at least one number'
                ]);
            }
            return redirect()->back()->with('error', 'Password must contain at least one number');
        }

        if ($password !== $confirmPassword) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Passwords do not match'
                ]);
            }
            return redirect()->back()->with('error', 'Passwords do not match');
        }

        try {
            // Update password and clear reset token
            $this->userModel->update($user['id'], [
                'password' => $password, // Model will hash it
                'reset_token' => null,
                'reset_expires_at' => null
            ]);

            log_message('info', 'Password reset successful for user: ' . $user['email']);

            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Password has been reset successfully. You can now login with your new password.',
                    'redirect' => base_url('login')
                ]);
            }

            return redirect()->to('/login')->with('success', 'Password has been reset successfully. You can now login with your new password.');

        } catch (\Exception $e) {
            log_message('error', 'Password reset failed: ' . $e->getMessage());
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to reset password. Please try again.'
                ]);
            }
            return redirect()->back()->with('error', 'Failed to reset password. Please try again.');
        }
    }

    /**
     * Display all database tables and their data
     */
    public function showAllData()
    {
        try {
            $output = "=== TUTORCONNECT MALAWI DATABASE ===\n\n";
            $output .= "Database: " . $this->db->database . "\n";
            $output .= "Host: " . $this->db->hostname . "\n";
            $output .= "Connected: ✅\n\n";

            // Get all tables
            $tables = $this->db->listTables();
            $output .= "Total Tables: " . count($tables) . "\n";
            $output .= "Tables: " . implode(', ', $tables) . "\n\n";

            // Loop through each table and show data
            foreach ($tables as $table) {
                $output .= "\n" . str_repeat("=", 80) . "\n";
                $output .= "TABLE: {$table}\n";
                $output .= str_repeat("=", 80) . "\n";

                // Get record count
                $count = $this->db->table($table)->countAllResults(false);
                $output .= "Records: {$count}\n\n";

                // Get column info
                $fields = $this->db->getFieldData($table);
                $output .= "Columns: ";
                $columnNames = [];
                foreach ($fields as $field) {
                    $columnNames[] = $field->name . " (" . $field->type . ")";
                }
                $output .= implode(', ', $columnNames) . "\n\n";

                // Get all records
                $records = $this->db->table($table)->get()->getResultArray();

                if (empty($records)) {
                    $output .= "No records found.\n";
                } else {
                    foreach ($records as $index => $record) {
                        $output .= "Record #" . ($index + 1) . ":\n";
                        foreach ($record as $key => $value) {
                            // Mask password fields
                            if (strpos(strtolower($key), 'password') !== false && $value) {
                                $value = '*** HIDDEN ***';
                            }
                            $output .= "  - {$key}: " . var_export($value, true) . "\n";
                        }
                        $output .= "\n";
                    }
                }
            }

            // Return as plain text
            return $this->response
                ->setContentType('text/plain')
                ->setBody($output);

        } catch (\Exception $e) {
            return $this->response
                ->setContentType('text/plain')
                ->setBody("ERROR: " . $e->getMessage());
        }
    }

    /**
     * Test Registration Workflow
     */
    public function testRegistration()
    {
        $output = "=== REGISTRATION WORKFLOW TEST ===\n\n";

        try {
            // Step 1: Check database connection
            $output .= "✓ Step 1: Database Connection\n";
            $output .= "  Database: " . $this->db->database . "\n";
            $output .= "  Status: Connected ✅\n\n";

            // Step 2: Check if users table exists
            $output .= "✓ Step 2: Users Table Check\n";
            if ($this->db->tableExists('users')) {
                $fields = $this->db->getFieldNames('users');
                $output .= "  Table exists: ✅\n";
                $output .= "  Columns: " . implode(', ', $fields) . "\n";
                $userCount = $this->db->table('users')->countAllResults();
                $output .= "  Current users: {$userCount}\n\n";
            } else {
                $output .= "  Table exists: ❌ MISSING!\n\n";
                return $this->response->setContentType('text/plain')->setBody($output);
            }

            // Step 3: Test User Model
            $output .= "✓ Step 3: User Model Test\n";
            try {
                $testUser = $this->userModel->first();
                $output .= "  Model working: ✅\n";
                $output .= "  Can query users: ✅\n\n";
            } catch (\Exception $e) {
                $output .= "  Model error: ❌ " . $e->getMessage() . "\n\n";
            }

            // Step 4: List existing users
            $output .= "✓ Step 4: Existing Users\n";
            $users = $this->userModel->findAll();
            if (empty($users)) {
                $output .= "  No users registered yet.\n\n";
            } else {
                foreach ($users as $user) {
                    $output .= "  - ID: {$user['id']}\n";
                    $output .= "    Username: {$user['username']}\n";
                    $output .= "    Email: {$user['email']}\n";
                    $output .= "    Name: {$user['first_name']} {$user['last_name']}\n";
                    $output .= "    Role: {$user['role']}\n";
                    $output .= "    Verified: " . ($user['is_verified'] ? '✅ Yes' : '❌ No') . "\n";
                    $output .= "    Active: " . ($user['is_active'] ? '✅ Yes' : '❌ No') . "\n";
                    $output .= "    OTP: " . ($user['otp_code'] ?? 'None') . "\n";
                    $output .= "    Created: {$user['created_at']}\n\n";
                }
            }

            // Step 5: Test password hashing
            $output .= "✓ Step 5: Password Hashing Test\n";
            $testPassword = 'TestPassword123';
            $hashed = password_hash($testPassword, PASSWORD_ARGON2ID);
            $verified = password_verify($testPassword, $hashed);
            $output .= "  Hash algorithm: PASSWORD_ARGON2ID\n";
            $output .= "  Test password: {$testPassword}\n";
            $output .= "  Hashed: " . substr($hashed, 0, 50) . "...\n";
            $output .= "  Verification: " . ($verified ? '✅ Working' : '❌ Failed') . "\n\n";

            // Step 6: Check required fields
            $output .= "✓ Step 6: Required Registration Fields\n";
            $requiredFields = [
                'first_name', 'last_name', 'email', 'username', 'phone',
                'gender', 'district', 'location', 'password'
            ];
            $output .= "  Required: " . implode(', ', $requiredFields) . "\n\n";

            // Step 7: Check email service configuration
            $output .= "✓ Step 7: Email Service Check\n";
            try {
                $emailService = \Config\Services::email();
                $output .= "  Email service: ✅ Available\n";
                $output .= "  Note: Actual sending may require SMTP configuration\n\n";
            } catch (\Exception $e) {
                $output .= "  Email service: ⚠️ Not configured\n";
                $output .= "  OTP will be logged only\n\n";
            }

            // Step 8: Registration endpoints
            $output .= "✓ Step 8: Registration Endpoints\n";
            $output .= "  Registration Page: " . base_url('register') . "\n";
            $output .= "  Submit Registration: POST to " . base_url('register') . "\n";
            $output .= "  Verify Email: " . base_url('verify-email') . "\n";
            $output .= "  Confirm Email: POST to " . base_url('confirm-email') . "\n";
            $output .= "  Resend OTP: POST to " . base_url('resend-otp') . "\n\n";

            // Step 9: Registration workflow
            $output .= "✓ Step 9: Registration Workflow\n";
            $output .= "  1. User fills registration form\n";
            $output .= "  2. Validation checks (email, username, password)\n";
            $output .= "  3. Password hashed with Argon2ID\n";
            $output .= "  4. User inserted with is_verified=0, is_active=0\n";
            $output .= "  5. Generate 6-digit OTP (expires in 15 minutes)\n";
            $output .= "  6. Send verification email (or log OTP)\n";
            $output .= "  7. Redirect to verify-email page\n";
            $output .= "  8. User enters OTP\n";
            $output .= "  9. Verify OTP and activate account\n";
            $output .= "  10. Redirect to login\n\n";

            $output .= "=== SYSTEM STATUS: ✅ READY FOR REGISTRATION ===\n\n";

            $output .= "🚀 Next Steps:\n";
            $output .= "1. Visit: " . base_url('register') . "\n";
            $output .= "2. Fill out the registration form\n";
            $output .= "3. Check logs for OTP code (since email may not be configured)\n";
            $output .= "4. Verify email with OTP\n";
            $output .= "5. Login to your account\n";

            return $this->response->setContentType('text/plain')->setBody($output);

        } catch (\Exception $e) {
            $output .= "\n❌ ERROR: " . $e->getMessage() . "\n";
            $output .= "Stack trace:\n" . $e->getTraceAsString();
            return $this->response->setContentType('text/plain')->setBody($output);
        }
    }

    /**
     * Logout user (works for both trainers and admins)
     */
    public function logout()
    {
        $role = session()->get('role');

        // Destroy session
        session()->destroy();

        log_message('info', 'User logged out');

        // Redirect based on role
        if ($role === 'admin') {
            return redirect()->to('/login')->with('success', 'You have been logged out successfully.');
        }

        return redirect()->to('/')->with('success', 'You have been logged out successfully.');
    }
}
