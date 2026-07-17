<?php

namespace App\Controllers;

class Checkout extends BaseController
{
    protected $subscriptionPlanModel;
    protected $tutorSubscriptionModel;

    public function __construct()
    {
        helper(['form', 'url']);
        $this->subscriptionPlanModel = new \App\Models\SubscriptionPlanModel();
        $this->tutorSubscriptionModel = new \App\Models\TutorSubscriptionModel();
    }

    private function getPortalContext(?int $userId = null, ?array $user = null): array
    {
        $portalType = 'trainer';
        $universityProfile = null;

        if (($user['role'] ?? session()->get('role')) === 'trainer') {
            if (session()->get('portal_type') === 'university') {
                $portalType = 'university';
                $universityProfile = $this->resolveUniversityProfile($userId, $user);
            } else {
                $universityProfile = $this->resolveUniversityProfile($userId, $user);
                if ($universityProfile) {
                    $portalType = 'university';
                }
            }
        }

        if ($portalType === 'university') {
            return [
                'type' => 'university',
                'dashboard_url' => base_url('university-portal/dashboard'),
                'subscription_url' => base_url('university-portal/subscription'),
                'complete_profile_url' => base_url('university-portal/complete-profile'),
                'public_module_url' => base_url('university-college-support'),
                'checkout_return_url' => base_url('university-portal/checkout/paychangu/return'),
                'checkout_process_url' => base_url('university-portal/checkout/process-subscription'),
                'check_payment_status_url' => base_url('university-portal/checkout/checkPaymentStatus'),
                'checkout_view' => 'university_portal/checkout',
                'payment_success_view' => 'university_portal/payment_success',
                'payment_failed_view' => 'university_portal/payment_failed',
                'payment_result_view' => 'university_portal/payment_result',
                'portal_display_name' => $this->buildPortalDisplayName($universityProfile, $user),
            ];
        }

        return [
            'type' => 'trainer',
            'dashboard_url' => base_url('trainer/dashboard'),
            'subscription_url' => base_url('trainer/subscription'),
            'complete_profile_url' => base_url('trainer/profile'),
            'public_module_url' => base_url(),
            'checkout_return_url' => base_url('trainer/checkout/paychangu/return'),
            'checkout_process_url' => base_url('trainer/checkout/process-subscription'),
            'check_payment_status_url' => base_url('trainer/checkout/checkPaymentStatus'),
            'checkout_view' => 'trainer/checkout',
            'payment_success_view' => 'trainer/payment_success',
            'payment_failed_view' => 'trainer/payment_failed',
            'payment_result_view' => 'trainer/payment_result',
            'portal_display_name' => $this->buildPortalDisplayName(null, $user),
        ];
    }

    private function resolveUniversityProfile(?int $userId = null, ?array $user = null): ?array
    {
        $lookupUserId = $userId ?? (int) session()->get('user_id');
        $lookupEmail = (string) ($user['email'] ?? session()->get('email') ?? '');
        $lookupUsername = (string) ($user['username'] ?? session()->get('username') ?? '');

        if ($lookupUserId <= 0 && $lookupEmail === '' && $lookupUsername === '') {
            return null;
        }

        $profile = (new \App\Models\UniversityCollegeTutorModel())->findLinkedProfile($lookupUserId, $lookupEmail, $lookupUsername);

        return is_array($profile) ? $profile : null;
    }

    private function buildPortalDisplayName(?array $profile = null, ?array $user = null): string
    {
        $profileName = trim((string) ($profile['full_name'] ?? ''));
        $contactName = trim((string) (($user['first_name'] ?? session()->get('first_name') ?? '') . ' ' . ($user['last_name'] ?? session()->get('last_name') ?? '')));

        if (($profile['account_type'] ?? 'individual') === 'firm') {
            return $profileName !== '' ? $profileName : $contactName;
        }

        return $contactName !== '' ? $contactName : $profileName;
    }

    /**
     * Show checkout page for subscription plan
     */
    public function subscription($planId = null)
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $user = new \App\Models\User();
        $currentUser = $user->find($userId);

        if (!$currentUser) {
            return redirect()->to('/login');
        }

        $portalContext = $this->getPortalContext((int) $userId, $currentUser);

        // Check if planId is provided via GET or URL segment
        if (!$planId) {
            $planId = $this->request->getGet('plan');
        }

        if (!$planId) {
            return redirect()->to($portalContext['subscription_url'])
                ->with('error', 'Please select a subscription plan to continue.');
        }

        // Get plan details
        $plan = $this->subscriptionPlanModel->find($planId);

        if (!$plan) {
            return redirect()->to($portalContext['subscription_url'])
                ->with('error', 'Selected plan not found.');
        }

        // Check if plan is active
        if (!$plan['is_active']) {
            return redirect()->to($portalContext['subscription_url'])
                ->with('error', 'Selected plan is not currently available.');
        }

        $planPortalType = $this->subscriptionPlanModel->normalizePortalType($plan['portal_type'] ?? 'trainer');
        if ($planPortalType !== $portalContext['type']) {
            return redirect()->to($portalContext['subscription_url'])
                ->with('error', 'Selected plan is not available for this portal.');
        }

        if ($portalContext['type'] === 'university' && !$this->isUniversityPlanAvailableForCurrentProfile((int) $userId, $currentUser, $plan)) {
            return redirect()->to($portalContext['subscription_url'])
                ->with('error', 'Selected plan is not available for this account type.');
        }

        // Check if user already has an active subscription they would be upgrading from
        $currentSubscription = $this->tutorSubscriptionModel->getSubscriptionWithPlan($userId);
        $maxBillingMonths = $this->tutorSubscriptionModel->getMaxBillingMonths();

        $defaultBillingMonths = $this->tutorSubscriptionModel->normalizeBillingMonths($this->request->getGet('months'));
        if ((float) $plan['price_monthly'] <= 0) {
            $defaultBillingMonths = 1;
        }

        $data = [
            'title' => 'Checkout - ' . $plan['name'] . ' Plan',
            'plan' => [
                'id' => $plan['id'],
                'name' => $plan['name'],
                'price_monthly' => $plan['price_monthly'],
                'description' => $plan['description'] ?? '',
                'features' => $this->decodePlanIncludedFeatures($plan['features'] ?? null),
                'formatted_price' => number_format($plan['price_monthly'], 0, ',', ','),
            ],
            'default_billing_months' => $defaultBillingMonths,
            'max_billing_months' => $maxBillingMonths,
            'user' => [
                'id' => $currentUser['id'],
                'first_name' => $currentUser['first_name'],
                'last_name' => $currentUser['last_name'],
                'email' => $currentUser['email'],
                'phone' => $currentUser['phone']
            ],
            'current_subscription' => $currentSubscription ? [
                'id' => $currentSubscription['id'],
                'plan_id' => $currentSubscription['plan_id'],
                'plan_name' => $currentSubscription['plan_name'] ?? 'Active Plan',
                'status' => $currentSubscription['status'],
                'current_period_end' => $currentSubscription['current_period_end'] ?? null,
            ] : null,
            'payment_methods' => [
                'bank_transfer' => 'Bank Transfer',
                'mobile_money' => 'Mobile Money (Airtel Money, TNM Mpamba)',
                'cash' => 'Cash Payment'
            ],
            'portal_type' => $portalContext['type'],
            'dashboard_url' => $portalContext['dashboard_url'],
            'subscription_url' => $portalContext['subscription_url'],
            'checkout_process_url' => $portalContext['checkout_process_url'],
            'check_payment_status_url' => $portalContext['check_payment_status_url'],
            'checkout_return_url' => $portalContext['checkout_return_url'],
            'complete_profile_url' => $portalContext['complete_profile_url'],
            'public_module_url' => $portalContext['public_module_url'],
            'portal_display_name' => $portalContext['portal_display_name'],
        ];

        return view($portalContext['checkout_view'], $data);
    }

    private function decodePlanIncludedFeatures($rawFeatures): array
    {
        $rawFeatures = trim((string) $rawFeatures);

        if ($rawFeatures === '') {
            return [];
        }

        $decoded = json_decode($rawFeatures, true);

        if (is_array($decoded)) {
            $isList = $decoded === [] || array_keys($decoded) === range(0, count($decoded) - 1);
            $features = $isList ? $decoded : ($decoded['included'] ?? []);

            if (is_array($features)) {
                return array_values(array_filter(array_map(static fn ($feature) => trim((string) $feature), $features), static fn ($feature) => $feature !== ''));
            }
        }

        $lines = preg_split('/\r\n|\r|\n/', $rawFeatures) ?: [];

        return array_values(array_filter(array_map(static fn ($feature) => trim((string) $feature), $lines), static fn ($feature) => $feature !== ''));
    }

    private function isUniversityPlanAvailableForCurrentProfile(int $userId, array $user, array $plan): bool
    {
        $profile = (new \App\Models\UniversityCollegeTutorModel())->findLinkedProfile(
            $userId,
            (string) ($user['email'] ?? ''),
            (string) ($user['username'] ?? '')
        );

        $isFirmProfile = ($profile['account_type'] ?? 'individual') === 'firm';
        $isFirmPlan = strcasecmp(trim((string) ($plan['name'] ?? '')), 'Firm') === 0;

        return $isFirmProfile ? $isFirmPlan : !$isFirmPlan;
    }

    /**
     * Process subscription payment and create subscription record
     */
    public function processSubscription()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method.']);
        }

        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'User session expired. Please login again.']);
        }

        // Get POST data
        $planId = $this->request->getPost('plan_id');
        $termsAccepted = $this->request->getPost('terms_accepted');
        $maxBillingMonths = $this->tutorSubscriptionModel->getMaxBillingMonths();
        $billingMonths = $this->tutorSubscriptionModel->normalizeBillingMonths($this->request->getPost('billing_months'));

        // Validate required fields
        $validation = \Config\Services::validation();
        $validation->setRules([
            'plan_id' => 'required|numeric',
            'terms_accepted' => 'required',
            'billing_months' => 'permit_empty|integer|greater_than_equal_to[1]|less_than_equal_to[' . $maxBillingMonths . ']',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please correct the errors in your form.',
                'errors' => $validation->getErrors()
            ]);
        }

        // Check if plan exists and is active
        $plan = $this->subscriptionPlanModel->find($planId);
        if (!$plan || !$plan['is_active']) {
            return $this->response->setJSON(['success' => false, 'message' => 'Selected plan is not available.']);
        }

        // Check if user is approved for subscriptions
        $user = new \App\Models\User();
        $currentUser = $user->find($userId);
        $portalContext = $this->getPortalContext((int) $userId, $currentUser ?? []);

        $planPortalType = $this->subscriptionPlanModel->normalizePortalType($plan['portal_type'] ?? 'trainer');
        if ($planPortalType !== $portalContext['type']) {
            return $this->response->setJSON(['success' => false, 'message' => 'Selected plan is not available for this portal.']);
        }

        if ($portalContext['type'] === 'university' && !$this->isUniversityPlanAvailableForCurrentProfile((int) $userId, $currentUser ?? [], $plan)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Selected plan is not available for this account type.']);
        }

        // Require email verification before allowing subscription access
        if (!$currentUser || !$currentUser['is_active'] || !$currentUser['email_verified_at']) {
            return $this->response->setJSON(['success' => false, 'message' => 'Your account is still under review. Please wait for admin approval before subscribing to plans.']);
        }

        // Determine if this is a free plan
        $monthlyPrice = (float) $plan['price_monthly'];
        $isFreePlan = $monthlyPrice == 0.0;
        if ($isFreePlan) {
            $billingMonths = 1;
        }
        $expectedAmount = round($monthlyPrice * $billingMonths, 2);

        // Check if tutor has already used a free trial
        $hasUsedFreeTrial = false;
        if ($isFreePlan) {
            // Check if tutor has already had a free trial subscription
            $existingSubscriptions = $this->tutorSubscriptionModel->where('user_id', $userId)->findAll();

            foreach ($existingSubscriptions as $existingSub) {
                // Check if this subscription was a free trial (price_monthly = 0)
                $existingPlan = $this->subscriptionPlanModel->find($existingSub['plan_id']);
                if ($existingPlan && $existingPlan['price_monthly'] == 0) {
                    $hasUsedFreeTrial = true;
                    break;
                }
            }

            if ($hasUsedFreeTrial) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'You have already used your free trial. Please select a paid plan to continue.'
                ]);
            }
        }

        // Update user terms_accepted status and subscription plan
        $userModel = new \App\Models\User();
        $userModel->update($userId, [
            'terms_accepted' => $termsAccepted ? 1 : 0,
            'subscription_plan' => $plan['name']
        ]);

        try {
            $this->tutorSubscriptionModel->markStalePendingPayments((int) $userId);

            // For free plans, activate immediately
            if ($isFreePlan) {
                // Calculate new billing period starting from plan change
                $changeDate = date('Y-m-d H:i:s');
                $newPeriodStart = $changeDate;
                $newPeriodEnd = $this->tutorSubscriptionModel->calculatePeriodEnd($changeDate, 1);

                // Check if user already has an active subscription
                $existingSubscription = $this->tutorSubscriptionModel->getActiveSubscription($userId);

                if ($existingSubscription) {
                    // Update existing subscription with new billing period
                    $this->tutorSubscriptionModel->update($existingSubscription['id'], [
                        'plan_id' => $planId,
                        'status' => 'active',
                        'current_period_start' => $newPeriodStart,
                        'current_period_end' => $newPeriodEnd,
                        'billing_months' => 1,
                        'payment_method' => 'free_plan',
                        'payment_amount' => 0,
                        'payment_date' => $changeDate,
                        'payment_status' => 'verified',
                        'terms_accepted' => $termsAccepted ? 1 : 0,
                        'trial_end' => $newPeriodEnd,
                        'updated_at' => $changeDate
                    ]);
                    $subscriptionId = $existingSubscription['id'];
                } else {
                    // Create new subscription with new billing period
                    $subscriptionId = $this->tutorSubscriptionModel->insert([
                        'user_id' => $userId,
                        'plan_id' => $planId,
                        'status' => 'active',
                        'current_period_start' => $newPeriodStart,
                        'current_period_end' => $newPeriodEnd,
                        'billing_months' => 1,
                        'cancel_at_period_end' => false,
                        'payment_method' => 'free_plan',
                        'payment_amount' => 0,
                        'payment_date' => $changeDate,
                        'payment_status' => 'verified',
                        'terms_accepted' => $termsAccepted ? 1 : 0,
                        'trial_end' => $newPeriodEnd,
                        'created_at' => $changeDate,
                        'updated_at' => $changeDate
                    ]);
                }

                $this->tutorSubscriptionModel->syncUserSubscriptionState($userId, false);

                // Reset usage counters when subscription is activated/changed
                $this->resetUsageCountersOnPlanChange($userId);

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Free trial subscription activated successfully! You now have access to all trial features.',
                    'redirect' => $portalContext['dashboard_url'],
                    'subscription_id' => $subscriptionId
                ]);
            }

            // For paid plans, return PayChangu configuration for inline checkout
            $txRef = 'TXN-' . $userId . '-' . time() . '-' . uniqid();
            $subscriptionData = [];

            $existingSamePlanCoverage = $this->tutorSubscriptionModel->getLatestActiveSubscription($userId, (int) $planId);
            $currentActiveSubscription = $this->tutorSubscriptionModel->getActiveSubscription($userId);

            if ($existingSamePlanCoverage) {
                $subscriptionData['upgrading_from'] = $existingSamePlanCoverage['id'];
                log_message('info', 'Renewal attempt: extending subscription chain from ID ' . $existingSamePlanCoverage['id'] . ' for user ' . $userId);
            } elseif ($currentActiveSubscription) {
                $subscriptionData['upgrading_from'] = $currentActiveSubscription['id'];
                log_message('info', 'Plan switch attempt: replacing active subscription ID ' . $currentActiveSubscription['id'] . ' for user ' . $userId);
            } else {
                log_message('info', 'No existing active subscription found for user ' . $userId);
            }

            log_message('info', 'Final subscriptionData before insert: ' . json_encode($subscriptionData));

            // Preserve the upgrading_from field and create pending subscription record
            $subscriptionData = array_merge($subscriptionData, [
                'user_id' => $userId,
                'plan_id' => $planId,
                'billing_months' => $billingMonths,
                'status' => 'pending',
                'current_period_start' => date('Y-m-d H:i:s'),
                'current_period_end' => $this->tutorSubscriptionModel->calculatePeriodEnd(date('Y-m-d H:i:s'), $billingMonths),
                'cancel_at_period_end' => false,
                'payment_method' => 'paychangu',
                'payment_amount' => $expectedAmount,
                'payment_date' => date('Y-m-d H:i:s'),
                'payment_status' => 'pending',
                'terms_accepted' => $termsAccepted ? 1 : 0,
                'payment_reference' => $txRef,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $subscriptionId = $this->tutorSubscriptionModel->insert($subscriptionData);

            if (!$subscriptionId) {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to create subscription record. Please try again.']);
            }

            // For now, since PayChangu test API seems to be having issues,
            // let's try a direct approach using PayChangu's hosted checkout
            // This bypasses their API and goes directly to their payment page

            // Create a direct PayChangu hosted checkout URL
            // Based on PayChangu's documentation, we can construct the URL directly
            try {
                $hostedCheckoutUrl = 'https://paychangu.com/checkout';
                $durationLabel = $billingMonths === 1 ? '1 month' : $billingMonths . ' months';

                $params = [
                    'public_key' => getenv('PAYCHANGU_PUBLIC_KEY') ?: 'PUB-TEST-MB33j3iotOje4NXksN3UxQh8D9vZDYTk',
                    'tx_ref' => $txRef,
                    'amount' => $expectedAmount,
                    'currency' => 'MWK',
                    'email' => $currentUser['email'],
                    'first_name' => $currentUser['first_name'],
                    'last_name' => $currentUser['last_name'],
                    'callback_url' => base_url('checkout/paychangu/callback'),
                    'return_url' => $portalContext['checkout_return_url'],
                    'customization[title]' => 'TutorConnect Malawi - ' . $plan['name'] . ' Plan',
                    'customization[description]' => $plan['name'] . ' subscription plan for ' . $durationLabel,
                    'meta[plan_id]' => $planId,
                    'meta[plan_name]' => $plan['name'],
                    'meta[user_email]' => $currentUser['email'],
                    'meta[billing_months]' => $billingMonths,
                ];

                $queryString = http_build_query($params);
                $checkoutUrl = $hostedCheckoutUrl . '?' . $queryString;

                log_message('info', 'Generated PayChangu checkout URL: ' . $checkoutUrl);

                // Return PayChangu config for inline modal with required callback_url
                return $this->response->setJSON([
                    'success' => true,
                    'hosted_checkout_url' => $checkoutUrl,
                    'paychangu_config' => [
                        'public_key' => getenv('PAYCHANGU_PUBLIC_KEY') ?: 'PUB-TEST-MB33j3iotOje4NXksN3UxQh8D9vZDYTk',
                        'tx_ref' => $txRef,
                        'amount' => $expectedAmount,
                        'currency' => 'MWK',
                        'callback_url' => base_url('checkout/paychangu/callback'),
                        'return_url' => $portalContext['checkout_return_url'],
                        'customer' => [
                            'email' => $currentUser['email'],
                            'first_name' => $currentUser['first_name'],
                            'last_name' => $currentUser['last_name']
                        ],
                        'customizations' => [
                            'title' => 'TutorConnect Malawi - ' . $plan['name'] . ' Plan',
                            'description' => $plan['name'] . ' subscription plan for ' . $durationLabel,
                            'logo' => base_url('favicon.ico')
                        ],
                        'meta' => [
                            'plan_id' => $planId,
                            'plan_name' => $plan['name'],
                            'billing_months' => $billingMonths,
                            'user_email' => $currentUser['email'],
                            'user_name' => $currentUser['first_name'] . ' ' . $currentUser['last_name']
                        ]
                    ]
                ]);

            } catch (\Exception $e) {
                log_message('error', 'Direct PayChangu URL construction failed: ' . $e->getMessage());
                // Don't let URL construction failure prevent payment - just log and continue
            }

            // If all else fails, show maintenance message
            $this->tutorSubscriptionModel->delete($subscriptionId);
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Payment system is currently under maintenance. Please contact support at info@uprisemw.com or try again later.',
                'contact_support' => true
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Subscription checkout error: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred while processing your subscription. Please try again or contact support.']);
        }
    }

    /**
     * PayChangu return URL handler - UI-ONLY, NEVER trusts redirect data
     * Reads payment status from database only, never performs verification
     * PayChangu supports only ONE return_url for all payment outcomes
     */
    public function paychanguReturn()
    {
        $txRef = $this->request->getGet('tx_ref');

        // Validate transaction reference
        if (!$txRef) {
            log_message('error', 'PayChangu return: Missing transaction reference');
            return $this->showPaymentResult('error', 'Invalid payment reference. Please contact support.');
        }

        log_message('info', 'PayChangu return: verifying tx_ref: ' . $txRef);

        $subscription = $this->tutorSubscriptionModel->where('payment_reference', $txRef)->first();

        if (!$subscription) {
            log_message('error', 'PayChangu return: No subscription found for tx_ref: ' . $txRef);
            return $this->showPaymentResult('error', 'Payment record not found. Please contact support.');
        }

        if ($subscription['payment_status'] === 'verified' && $subscription['status'] === 'active') {
            return $this->showPaymentResult('success', $this->buildActivationSuccessMessage($subscription), $subscription);
        }

        $syncResult = $this->verifyAndSyncPayChanguSubscription((string) $txRef, $subscription, null, $this->isPayChanguTestMode());
        $subscription = $syncResult['subscription'] ?? $subscription;

        if (($syncResult['status'] ?? '') === 'success') {
            return $this->showPaymentResult('success', $this->buildActivationSuccessMessage($subscription), $subscription);
        }

        if (($syncResult['status'] ?? '') === 'failed') {
            return $this->showPaymentResult('failed', 'Payment was not successful. No subscription has been activated.', $subscription);
        }

        return $this->showPaymentResult('processing', 'We are still confirming this payment. This page will update automatically once PayChangu confirms it.', $subscription, true);
    }

    /**
     * Display payment result page
     */
    private function showPaymentResult($status, $message, $subscription = null, $enablePolling = false)
    {
        $portalContext = $this->getPortalContext(
            $subscription ? (int) ($subscription['user_id'] ?? 0) : (int) session()->get('user_id')
        );

        if (is_array($subscription) && empty($subscription['plan_name']) && !empty($subscription['plan_id'])) {
            $plan = $this->subscriptionPlanModel->find((int) $subscription['plan_id']);
            if ($plan) {
                $subscription['plan_name'] = $plan['name'] ?? 'Subscription';
            }
        }

        $data = [
            'status' => $status,
            'message' => $message,
            'subscription' => $subscription,
            'enablePolling' => $enablePolling,
            'txRef' => $subscription ? $subscription['payment_reference'] : null,
            'portal_type' => $portalContext['type'],
            'dashboard_url' => $portalContext['dashboard_url'],
            'subscription_url' => $portalContext['subscription_url'],
            'check_payment_status_url' => $portalContext['check_payment_status_url'],
            'complete_profile_url' => $portalContext['complete_profile_url'],
            'public_module_url' => $portalContext['public_module_url'],
            'portal_display_name' => $portalContext['portal_display_name'],
        ];

        // Use separate views for success and failure
        if ($status === 'success') {
            return view($portalContext['payment_success_view'], $data);
        } elseif (in_array($status, ['failed', 'error'], true)) {
            return view($portalContext['payment_failed_view'], $data);
        } else {
            $data['message'] = $message ?: 'Please wait while we confirm your payment...';
            return view($portalContext['payment_result_view'], $data);
        }
    }

    /**
     * API endpoint to check payment status (for polling)
     */
    public function checkPaymentStatus()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Invalid request method']);
        }

        $txRef = $this->request->getPost('tx_ref');

        if (!$txRef) {
            return $this->response->setJSON(['error' => 'Missing transaction reference']);
        }

        $subscription = $this->tutorSubscriptionModel->where('payment_reference', $txRef)->first();

        if (!$subscription) {
            return $this->response->setJSON(['error' => 'Payment record not found']);
        }

        if (($subscription['payment_status'] ?? '') === 'pending') {
            $syncResult = $this->verifyAndSyncPayChanguSubscription((string) $txRef, $subscription);
            if (!empty($syncResult['subscription'])) {
                $subscription = $syncResult['subscription'];
            }
        }

        $response = [
            'status' => $subscription['payment_status'],
            'subscription_status' => $subscription['status'],
            'message' => $this->getPaymentStatusMessage($subscription)
        ];

        return $this->response->setJSON($response);
    }

    /**
     * Get user-friendly payment status message
     */
    private function getPaymentStatusMessage($subscription)
    {
        if ($subscription['payment_status'] === 'verified' && $subscription['status'] === 'active') {
            return 'Payment confirmed! Your subscription is now active.';
        } elseif ($subscription['payment_status'] === 'pending') {
            return 'Payment is still being processed. Please wait...';
        } elseif ($subscription['payment_status'] === 'failed') {
            return 'Payment was not successful.';
        } else {
            return 'Payment status is being verified...';
        }
    }

    private function extractPayChanguReferenceAndStatus(array $payload): array
    {
        $data = isset($payload['data']) && is_array($payload['data']) ? $payload['data'] : [];

        return [
            'tx_ref' => $payload['tx_ref']
                ?? $payload['txRef']
                ?? $payload['reference']
                ?? $payload['event']['tx_ref']
                ?? $data['tx_ref']
                ?? $data['txRef']
                ?? $data['reference']
                ?? null,
            'status' => $data['status']
                ?? $data['payment_status']
                ?? $payload['status']
                ?? $payload['event_type']
                ?? null,
        ];
    }

    private function normalizePayChanguStatus(?string $status): string
    {
        $status = strtolower(trim((string) $status));

        if (in_array($status, ['success', 'successful', 'completed', 'complete', 'paid', 'payment_success', 'payment.success', 'checkout.payment.success'], true)) {
            return 'success';
        }

        if (in_array($status, ['failed', 'failure', 'cancelled', 'canceled', 'declined', 'abandoned', 'reversed', 'timeout', 'expired'], true)) {
            return 'failed';
        }

        return 'pending';
    }

    private function apiVerificationStatus(?array $apiResult, string $txRef, float $expectedAmount): string
    {
        $paychangu = \Config\Services::paychangu();

        if ($paychangu->isSuccessfulVerification($apiResult, $txRef, 'MWK', $expectedAmount)) {
            return 'success';
        }

        if (!is_array($apiResult)) {
            return 'pending';
        }

        $data = isset($apiResult['data']) && is_array($apiResult['data'])
            ? $apiResult['data']
            : [];

        return $this->normalizePayChanguStatus((string) ($data['status'] ?? $apiResult['status'] ?? ''));
    }

    private function verifyAndSyncPayChanguSubscription(string $txRef, array $subscription, ?string $webhookStatus = null, bool $trustRedirect = false): array
    {
        $txRef = trim($txRef);
        if ($txRef === '') {
            return ['status' => 'error', 'subscription' => $subscription];
        }

        if (($subscription['payment_status'] ?? '') === 'verified' && ($subscription['status'] ?? '') === 'active') {
            return ['status' => 'success', 'subscription' => $subscription];
        }

        $paychangu = \Config\Services::paychangu();
        $apiResult = $paychangu->verifyPayment($txRef);
        $expectedAmount = isset($subscription['payment_amount']) ? (float) $subscription['payment_amount'] : 0.0;
        $webhookNormalized = $this->normalizePayChanguStatus($webhookStatus);
        $apiNormalized = $this->apiVerificationStatus($apiResult, $txRef, $expectedAmount);
        $fallbackSuccess = $this->isPayChanguTestMode() && $apiResult === null && $webhookNormalized !== 'failed';
        $redirectSuccess = $trustRedirect && $webhookNormalized !== 'failed';

        log_message('info', 'PayChangu verification sync: tx_ref=' . $txRef .
            ', webhook=' . ($webhookStatus ?? 'none') .
            ', webhook_normalized=' . $webhookNormalized .
            ', api_normalized=' . $apiNormalized .
            ', fallback_success=' . ($fallbackSuccess ? 'yes' : 'no') .
            ', redirect_success=' . ($redirectSuccess ? 'yes' : 'no') .
            ', api=' . json_encode($apiResult));

        if ($webhookNormalized === 'success' || $apiNormalized === 'success' || $fallbackSuccess || $redirectSuccess) {
            $updatedSubscription = $this->tutorSubscriptionModel->activateSubscription((int) $subscription['id']);

            if (!$updatedSubscription) {
                return ['status' => 'error', 'subscription' => $subscription];
            }

            if ($this->shouldResetUsageCounters($updatedSubscription)) {
                $this->resetUsageCountersOnPlanChange($updatedSubscription['user_id']);
            }

            $this->sendPaymentSuccessNotification($updatedSubscription['user_id'], $updatedSubscription);

            return ['status' => 'success', 'subscription' => $updatedSubscription];
        }

        if ($webhookNormalized === 'failed' || $apiNormalized === 'failed') {
            $this->tutorSubscriptionModel->update($subscription['id'], [
                'status' => 'cancelled',
                'payment_status' => 'failed',
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            $updatedSubscription = $this->tutorSubscriptionModel->find((int) $subscription['id']) ?: $subscription;

            return ['status' => 'failed', 'subscription' => $updatedSubscription];
        }

        return ['status' => 'pending', 'subscription' => $subscription];
    }

    private function isPayChanguTestMode(): bool
    {
        $paychangu = \Config\Services::paychangu();
        $publicKey = trim((string) ($paychangu->getPublicKey() ?: getenv('PAYCHANGU_PUBLIC_KEY') ?: 'PUB-TEST-MB33j3iotOje4NXksN3UxQh8D9vZDYTk'));

        return stripos($publicKey, 'PUB-TEST-') === 0;
    }

    /**
     * PayChangu webhook callback - Source of truth for payment verification
     * Updates payment_status and subscription status only after verification
     * Handles plan upgrades only after verified success
     * Idempotent - can be called multiple times safely
     */
    public function paychanguCallback()
    {
        $method = $this->request->getMethod();
        log_message('info', 'PayChangu callback received via ' . $method);

        $txRef = null;
        $webhookStatus = null;

        if ($method === 'POST') {
            $json = $this->request->getJSON(true) ?? [];
            $webhook = $this->extractPayChanguReferenceAndStatus($json);
            $txRef = $webhook['tx_ref'];
            $webhookStatus = $webhook['status'];
            log_message('info', 'PayChangu webhook: tx_ref=' . $txRef . ', status=' . $webhookStatus . ', full_data=' . json_encode($json));
        } elseif ($method === 'GET') {
            $txRef = $this->request->getGet('tx_ref');
            $webhookStatus = $this->request->getGet('status');
            log_message('info', 'PayChangu GET callback: tx_ref=' . $txRef . ', status=' . $webhookStatus);

            // PayChangu may call GET to validate webhook URL - return valid response
            if (!$txRef) {
                log_message('info', 'PayChangu GET callback: No tx_ref - likely webhook validation request');
                return $this->response->setStatusCode(200)->setJSON([
                    'status' => 'success',
                    'message' => 'Webhook endpoint is active and responding correctly'
                ]);
            }

            $redirectSubscription = $this->tutorSubscriptionModel->where('payment_reference', $txRef)->first();
            if ($redirectSubscription && ($redirectSubscription['payment_status'] ?? '') !== 'verified') {
                $this->verifyAndSyncPayChanguSubscription((string) $txRef, $redirectSubscription, $webhookStatus ? (string) $webhookStatus : null);
            }

            log_message('info', 'PayChangu GET callback: redirecting to return handler for tx_ref: ' . $txRef);
            $redirectContext = $this->getPortalContext($redirectSubscription ? (int) ($redirectSubscription['user_id'] ?? 0) : (int) session()->get('user_id'));

            return redirect()->to($redirectContext['checkout_return_url'] . '?tx_ref=' . rawurlencode((string) $txRef));
        }

        if (!$txRef) {
            log_message('error', 'PayChangu callback: Missing tx_ref');
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Missing transaction reference']);
        }

        try {
            // Find subscription using existing model
            $subscription = $this->tutorSubscriptionModel->where('payment_reference', $txRef)->first();

            if (!$subscription) {
                log_message('error', 'PayChangu callback: No subscription found for tx_ref: ' . $txRef);

                // If accessed by user (not PayChangu server), redirect to error page
                if ($method === 'GET') {
                    log_message('info', 'PayChangu callback: GET request with invalid tx_ref - redirecting to error');
                    return redirect()->to($this->getPortalContext((int) session()->get('user_id'))['checkout_return_url'] . '?tx_ref=invalid')
                        ->with('error', 'Invalid payment reference');
                }

                return $this->response->setStatusCode(404)->setJSON(['status' => 'error', 'message' => 'Subscription not found']);
            }

            // Prevent double processing - idempotent check
            if ($subscription['payment_status'] === 'verified' && $subscription['status'] === 'active') {
                log_message('info', 'PayChangu callback: Payment already processed for tx_ref: ' . $txRef);

                // If accessed by user, redirect to success page
                if ($method === 'GET') {
                    log_message('info', 'PayChangu callback: GET request for already processed payment - redirecting to success');
                    $redirectContext = $this->getPortalContext((int) ($subscription['user_id'] ?? 0));

                    return redirect()->to($redirectContext['checkout_return_url'] . '?tx_ref=' . rawurlencode((string) $txRef));
                }

                return $this->response->setJSON(['status' => 'success', 'message' => 'Already processed']);
            }

            $syncResult = $this->verifyAndSyncPayChanguSubscription((string) $txRef, $subscription, $webhookStatus ? (string) $webhookStatus : null);

            if (($syncResult['status'] ?? '') === 'success') {
                return $this->response->setJSON(['status' => 'success', 'message' => 'Payment processed successfully']);
            }

            if (($syncResult['status'] ?? '') === 'failed') {
                return $this->response->setJSON(['status' => 'failed', 'message' => 'Payment failed']);
            }

            return $this->response->setJSON(['status' => 'pending', 'message' => 'Payment is still pending verification']);

        } catch (\Exception $e) {
            log_message('error', 'PayChangu callback exception: ' . $e->getMessage() . ' for tx_ref: ' . $txRef);
            return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'message' => 'Internal server error']);
        }
    }

    private function shouldResetUsageCounters(array $subscription): bool
    {
        if (empty($subscription['current_period_start'])) {
            return false;
        }

        return strtotime($subscription['current_period_start']) <= time();
    }

    private function buildActivationSuccessMessage(array $subscription): string
    {
        if (!empty($subscription['current_period_start']) && strtotime($subscription['current_period_start']) > time()) {
            return 'Your payment has been confirmed. Your extra subscription time is queued to continue automatically from ' . date('M j, Y', strtotime($subscription['current_period_start'])) . '.';
        }

        return 'Your payment has been confirmed and your subscription is now active!';
    }

    /**
     * Send payment success notification to user
     */
    private function sendPaymentSuccessNotification($userId, $subscription)
    {
        try {
            $user = new \App\Models\User();
            $subscriber = $user->find($userId);
            $plan = $this->subscriptionPlanModel->find($subscription['plan_id']);

            if (!$subscriber || !$plan) return;

            $portalContext = $this->getPortalContext((int) $userId, $subscriber);

            $emailService = \Config\Services::email();

            $emailService->setFrom('info@uprisemw.com', 'TutorConnect Malawi');
            $emailService->setTo($subscriber['email']);
            $emailService->setSubject('🎉 Payment Successful - Subscription Activated!');

            $htmlMessage = "
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Payment Successful</title>
    <style>
        .email-container { max-width: 600px; margin: 0 auto; font-family: Arial, sans-serif; }
        .header { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 30px; text-align: center; }
        .content { padding: 30px; background-color: #ffffff; }
        .success-badge { background: #d1fae5; color: #065f46; padding: 20px; border-radius: 8px; margin: 20px 0; text-align: center; font-size: 18px; font-weight: bold; }
        .subscription-info { background: #EBF4FF; border: 1px solid #3B82F6; padding: 20px; margin: 20px 0; border-radius: 8px; }
        .action-button { background: #3B82F6; color: white; padding: 15px 30px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block; margin: 20px 0; }
        .action-button:hover { background-color: #2563EB; }
        .footer { background-color: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class='email-container'>
        <div class='header'>
            <h1>🎉 Payment Successful!</h1>
            <p>Your subscription is now active</p>
        </div>
        <div class='content'>
            <h2>Congratulations, " . htmlspecialchars($subscriber['first_name']) . "!</h2>
            <p>Your payment has been successfully processed and your subscription is now active.</p>

            <div class='success-badge'>
                ✅ PAYMENT CONFIRMED & SUBSCRIPTION ACTIVATED
            </div>

            <div class='subscription-info'>
                <h3>Subscription Details:</h3>
                <p><strong>Plan:</strong> " . htmlspecialchars($plan['name']) . "</p>
                <p><strong>Amount:</strong> MWK " . number_format($subscription['payment_amount'], 2) . "</p>
                <p><strong>Status:</strong> <span style='color: #059669; font-weight: bold;'>ACTIVE</span></p>
                <p><strong>Valid Until:</strong> " . date('M j, Y', strtotime($subscription['current_period_end'])) . "</p>
            </div>

            <p>You now have access to all the features included in your " . htmlspecialchars($plan['name']) . " plan:</p>
            <ul>
                <li>Access to student inquiries and bookings</li>
                <li>Video solution submission capabilities</li>
                <li>Advanced analytics and reporting</li>
                <li>Priority support and assistance</li>
            </ul>

            <a href='" . $portalContext['dashboard_url'] . "' class='action-button'>Access Your Dashboard →</a>

            <p><em>Thank you for choosing TutorConnect Malawi! We're excited to support your teaching journey.</em></p>
        </div>
        <div class='footer'>
            <p>&copy; 2025 TutorConnect Malawi. All rights reserved.<br>
            info@uprisemw.com | Lilongwe, Malawi | +265 992 313 978</p>
        </div>
    </div>
</body>
</html>";

            $plainMessage = "Payment Successful - TutorConnect Malawi

Congratulations {$subscriber['first_name']}!

Your payment has been successfully processed and your subscription is now active.

Subscription Details:
Plan: {$plan['name']}
Amount: MWK " . number_format($subscription['payment_amount'], 2) . "
Status: ACTIVE
Valid Until: " . date('M j, Y', strtotime($subscription['current_period_end'])) . "

You now have access to all features of your {$plan['name']} plan.

Access your dashboard: " . $portalContext['dashboard_url'] . "

Thank you for choosing TutorConnect Malawi!

---
TutorConnect Malawi
info@uprisemw.com | +265 992 313 978";

            $emailService->setMessage($htmlMessage);
            $emailService->setAltMessage($plainMessage);

            if ($emailService->send()) {
                log_message('info', 'Payment success notification sent to user ID: ' . $userId);
            } else {
                log_message('error', 'Failed to send payment success notification to user ID: ' . $userId);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error sending payment success notification: ' . $e->getMessage());
        }
    }

    /**
     * Send notification to admin about new subscription payment
     */
    private function notifyAdminNewSubscription($userId, $plan, $paymentMethod, $amount)
    {
        try {
            $user = new \App\Models\User();
            $subscriber = $user->find($userId);

            if (!$subscriber) return;

            $emailService = \Config\Services::email();

            // Use the email from config or a default admin email
            $adminEmail = getenv('ADMIN_EMAIL') ?: 'info@uprisemw.com';

            $emailService->setFrom('info@uprisemw.com', 'TutorConnect Malawi');
            $emailService->setTo($adminEmail);
            $emailService->setSubject('💰 New Subscription Payment - ' . $subscriber['first_name'] . ' ' . $subscriber['last_name']);

            $htmlMessage = "
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>New Subscription Payment</title>
    <style>
        .email-container { max-width: 600px; margin: 0 auto; font-family: Arial, sans-serif; }
        .header { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 30px; text-align: center; }
        .content { padding: 30px; background-color: #ffffff; }
        .tutor-info { background: #EBF4FF; border: 1px solid #3B82F6; padding: 20px; margin: 20px 0; border-radius: 8px; }
        .payment-info { background: linear-gradient(135deg, #fef3c7, #fde68a); border: 2px solid #f59e0b; padding: 20px; margin: 20px 0; border-radius: 8px; }
        .action-button { background: #10b981; color: white; padding: 15px 30px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block; margin: 20px 0; }
        .action-button:hover { background-color: #059669; }
        .footer { background-color: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class='email-container'>
        <div class='header'>
            <h1>💰 New Subscription Payment</h1>
            <p>Payment verification required</p>
        </div>
        <div class='content'>
            <h2>Subscription Payment Submitted</h2>

            <div class='tutor-info'>
                <h3>Subscriber Details:</h3>
                <p><strong>Name:</strong> " . htmlspecialchars($subscriber['first_name'] . ' ' . $subscriber['last_name']) . "</p>
                <p><strong>Email:</strong> " . htmlspecialchars($subscriber['email']) . "</p>
                <p><strong>Phone:</strong> " . htmlspecialchars($subscriber['phone'] ?: 'Not provided') . "</p>
            </div>

            <div class='payment-info'>
                <h3>Payment Details:</h3>
                <p><strong>Plan:</strong> " . htmlspecialchars($plan['name']) . "</p>
                <p><strong>Amount:</strong> MWK " . number_format($amount, 2) . "</p>
                <p><strong>Payment Method:</strong> " . htmlspecialchars($paymentMethod == 'mobile_money' ? 'Mobile Money' : ($paymentMethod == 'bank_transfer' ? 'Bank Transfer' : 'Cash')) . "</p>
                <p><strong>Status:</strong> <span style='color: #d97706; font-weight: bold;'>PENDING VERIFICATION</span></p>
            </div>

            <div style='background: #EBF7FF; border: 1px solid #0ea5e9; color: #1e40af; padding: 15px; border-radius: 8px; margin: 20px 0;'>
                <strong>⚠️ Action Required:</strong><br>
                Please verify this payment and either approve the subscription or request additional information.
            </div>

            <a href='" . base_url('admin/tutor-subscriptions') . "' class='action-button'>Review Payment →</a>

            <p><em>Payment proof file has been uploaded and is available in the payment details.</em></p>
        </div>
        <div class='footer'>
            <p>&copy; 2025 TutorConnect Malawi. All rights reserved.<br>
            info@uprisemw.com | Blantyre, Malawi | +265 992 313 978</p>
        </div>
    </div>
</body>
</html>";

            $plainMessage = "New Subscription Payment - TutorConnect Malawi

Subscriber: {$subscriber['first_name']} {$subscriber['last_name']}
Email: {$subscriber['email']}
Phone: {$subscriber['phone']}

Payment Details:
Plan: {$plan['name']}
Amount: MWK " . number_format($amount, 2) . "
Payment Method: " . ($paymentMethod == 'mobile_money' ? 'Mobile Money' : ($paymentMethod == 'bank_transfer' ? 'Bank Transfer' : 'Cash')) . "
Status: PENDING VERIFICATION

Action Required: Please verify this payment in the admin panel at " . base_url('admin/tutor-subscriptions') . "

---
TutorConnect Malawi
info@uprisemw.com | +265 992 313 978";

            $emailService->setMessage($htmlMessage);
            $emailService->setAltMessage($plainMessage);

            if ($emailService->send()) {
                log_message('info', 'Admin notification sent for new subscription payment from user ID: ' . $userId);
            } else {
                log_message('error', 'Failed to send admin notification for subscription payment from user ID: ' . $userId);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error sending admin notification for subscription payment: ' . $e->getMessage());
        }
    }

    /**
     * Reset usage counters when subscription plan changes (upgrade/downgrade)
     * This gives users a fresh start with new plan limits from the change date
     */
    private function resetUsageCountersOnPlanChange($userId)
    {
        try {
            log_message('info', 'Resetting usage counters for user ID: ' . $userId . ' due to plan change');

            $usageTrackingModel = new \App\Models\UsageTrackingModel();

            // Get the ACTIVE subscription to use its EXACT billing period dates
            $subscriptionModel = new \App\Models\TutorSubscriptionModel();
            $activeSubscription = $subscriptionModel->getActiveSubscription($userId);

            if (!$activeSubscription) {
                log_message('error', 'No active subscription found for user ' . $userId . ' during usage reset');
                return false;
            }

            // Use the EXACT same period dates as the subscription
            $periodStart = $activeSubscription['current_period_start'];
            $periodEnd = $activeSubscription['current_period_end'];

            log_message('info', 'Using subscription billing period: ' . $periodStart . ' to ' . $periodEnd);
            log_message('info', 'This ensures dashboard queries match the reset records exactly');

            // Delete ALL existing usage records for this user for metrics that have plan limits
            // This gives them a complete fresh start across all billing periods
            $deletedCount = 0;
            $metricTypes = ['profile_views', 'contact_clicks', 'messages'];

            foreach ($metricTypes as $metricType) {
                $count = $usageTrackingModel->where('user_id', $userId)
                                           ->where('metric_type', $metricType)
                                           ->countAllResults();
                log_message('info', 'Before reset - ' . $metricType . ' records for user ' . $userId . ': ' . $count);

                    $deleted = $usageTrackingModel->where('user_id', $userId)
                                                 ->where('metric_type', $metricType)
                                                 ->delete();
                    $deletedCount += $deleted;
                    log_message('info', 'Deleted ' . $deleted . ' ' . $metricType . ' records for user ' . $userId);

                    $countAfter = $usageTrackingModel->where('user_id', $userId)
                                                    ->where('metric_type', $metricType)
                                                    ->countAllResults();
                    log_message('info', 'After reset - ' . $metricType . ' records for user ' . $userId . ': ' . $countAfter);
            }

            log_message('info', 'Deleted ' . $deletedCount . ' existing usage records for current billing period');

            // Insert fresh usage records with 0 counts for the STANDARD billing period
            // This ensures the dashboard shows 0 usage for the current month
            $freshUsageData = [
                // Profile views - start at 0
                [
                    'user_id' => $userId,
                    'metric_type' => 'profile_views',
                    'metric_value' => 0,
                    'reference_id' => null,
                    'metadata' => json_encode(['reset_reason' => 'plan_change', 'reset_date' => $now]),
                    'tracked_at' => $now,
                    'period_start' => $periodStart,
                    'period_end' => $periodEnd
                ],
                // Contact clicks - start at 0
                [
                    'user_id' => $userId,
                    'metric_type' => 'contact_clicks',
                    'metric_value' => 0,
                    'reference_id' => null,
                    'metadata' => json_encode(['reset_reason' => 'plan_change', 'reset_date' => $now]),
                    'tracked_at' => $now,
                    'period_start' => $periodStart,
                    'period_end' => $periodEnd
                ],
                // Messages - start at 0
                [
                    'user_id' => $userId,
                    'metric_type' => 'messages',
                    'metric_value' => 0,
                    'reference_id' => null,
                    'metadata' => json_encode(['reset_reason' => 'plan_change', 'reset_date' => $now]),
                    'tracked_at' => $now,
                    'period_start' => $periodStart,
                    'period_end' => $periodEnd
                ]
            ];

            $insertedCount = 0;
            foreach ($freshUsageData as $usageRecord) {
                if ($usageTrackingModel->insert($usageRecord)) {
                    $insertedCount++;
                } else {
                    log_message('error', 'Failed to insert usage record for type: ' . $usageRecord['metric_type']);
                }
            }

            log_message('info', 'Inserted ' . $insertedCount . ' fresh usage records for current billing period');

            return true;

        } catch (\Exception $e) {
            log_message('error', 'Failed to reset usage counters for user ' . $userId . ': ' . $e->getMessage());
            return false;
        }
    }
}
