<?php

namespace App\Controllers;

use App\Models\JapanApplicationAccessModel;
use App\Models\JapanApplicationModel;

class Japan extends BaseController
{
    private const DEFAULT_APPLICATION_FEE = 10000;
    private const DEFAULT_PROCESSING_FEE = 350000;
    private const ACCESS_SESSION_KEY = 'japan_application_access_token';

    protected JapanApplicationModel $applicationModel;
    protected JapanApplicationAccessModel $applicationAccessModel;

    public function __construct()
    {
        $this->applicationModel = new JapanApplicationModel();
        $this->applicationAccessModel = new JapanApplicationAccessModel();
        helper(['form', 'url', 'site_settings']);
    }

    public function index()
    {
        $this->ensureTables();

        return view('pages/teach_in_japan', [
            'title' => 'Teach English in Japan - TutorConnect Malawi',
            'description' => 'Apply for English teaching opportunities in Japan through TutorConnect Malawi.',
            'applicationFee' => $this->getApplicationFee(),
            'processingFee' => $this->getProcessingFee(),
            'applicationsOpen' => $this->isJapanApplicationsOpen(),
            'applicationsClosedMessage' => $this->getJapanApplicationsClosedMessage(),
        ]);
    }

    public function application()
    {
        $this->ensureTables();

        $txRef = trim((string) $this->request->getGet('tx_ref'));
        $accessRecord = $this->resolvePaymentAccess($txRef);
        $viewState = 'unlock';
        $submittedApplication = null;

        if ($accessRecord) {
            if (($accessRecord['access_status'] ?? '') === 'submitted') {
                $submittedApplication = $this->resolveSubmittedApplication($accessRecord);

                if ($submittedApplication) {
                    $viewState = 'submitted';
                } else {
                    $this->applicationAccessModel->update($accessRecord['id'], [
                        'access_status' => 'active',
                        'application_id' => null,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);

                    $accessRecord = $this->applicationAccessModel->find($accessRecord['id']) ?? array_merge($accessRecord, [
                        'access_status' => 'active',
                        'application_id' => null,
                    ]);
                    $viewState = 'form';
                }
            } else {
                $viewState = 'form';
            }
        }

        $viewData = [
            'title' => 'Apply to Teach in Japan - TutorConnect Malawi',
            'description' => 'Submit your Japan teaching opportunity application through TutorConnect Malawi.',
            'viewState' => $viewState,
            'accessRecord' => $accessRecord,
            'submittedApplication' => $submittedApplication,
            'applicationFee' => $this->getApplicationFee(),
            'processingFee' => $this->getProcessingFee(),
            'payChanguPublicKey' => $this->getPayChanguPublicKey(),
            'applicationsOpen' => $this->isJapanApplicationsOpen(),
            'applicationsClosedMessage' => $this->getJapanApplicationsClosedMessage(),
        ];

        // If applications are closed AND the visitor has no verified access, show a closed page.
        if (!$this->isJapanApplicationsOpen() && !$accessRecord) {
            return view('pages/teach_in_japan_closed', $viewData);
        }

        if ($viewState === 'unlock') {
            return view('pages/teach_in_japan_unlock', $viewData);
        }

        return view('pages/teach_in_japan_apply', $viewData);
    }

    public function initiatePayment()
    {
        $this->ensureTables();

        if (strtolower($this->request->getMethod()) !== 'post') {
            return $this->response->setStatusCode(405)->setJSON([
                'success' => false,
                'message' => 'Invalid request method.',
            ]);
        }

        if (!$this->isJapanApplicationsOpen()) {
            return $this->response->setStatusCode(403)->setJSON([
                'success' => false,
                'message' => $this->getJapanApplicationsClosedMessage(),
            ]);
        }

        $fullName = trim((string) $this->request->getPost('payer_full_name'));
        $email = $this->normalizeEmail((string) $this->request->getPost('payer_email'));
        $phone = trim((string) $this->request->getPost('payer_phone'));
        $termsAccepted = (bool) $this->request->getPost('unlock_terms');

        $validation = \Config\Services::validation();
        $validation->setRules([
            'payer_full_name' => 'required|min_length[5]|max_length[255]',
            'payer_email' => 'required|valid_email|max_length[150]',
            'payer_phone' => 'required|min_length[8]|max_length[30]',
        ]);

        $errors = [];
        if (!$validation->run($this->request->getPost())) {
            $errors = $validation->getErrors();
        }

        if (!$termsAccepted) {
            $errors['unlock_terms'] = 'You must confirm the application fee terms before continuing.';
        }

        if ($errors !== []) {
            return $this->response->setStatusCode(422)->setJSON([
                'success' => false,
                'message' => reset($errors) ?: 'Please complete the required payment details.',
                'errors' => $errors,
            ]);
        }

        // Only reuse a verified payment if the applicant has NOT submitted yet.
        // Once submitted, the fee is considered consumed for that application flow.
        $existingVerified = $this->applicationAccessModel->findLatestVerifiedByEmail($email);
        if ($existingVerified && ($existingVerified['access_status'] ?? '') !== 'submitted') {
            $this->grantPaymentAccess($existingVerified);

            return $this->response->setJSON([
                'success' => true,
                'already_paid' => true,
                'redirect' => site_url('teach-in-japan/apply'),
                'message' => 'Payment already confirmed. You can continue your application now.',
            ]);
        }

        $txRef = $this->generatePaymentReference();
        $accessToken = bin2hex(random_bytes(32));

        $record = [
            'tx_ref' => $txRef,
            'full_name' => $fullName,
            'email' => $email,
            'phone' => $phone,
            'amount' => $this->getApplicationFee(),
            'currency' => 'MWK',
            'payment_method' => 'paychangu',
            'payment_status' => 'pending',
            'access_status' => 'pending',
            'access_token' => $accessToken,
            'paid_at' => null,
            'last_accessed_at' => null,
            'application_id' => null,
        ];

        $recordId = $this->applicationAccessModel->insert($record);
        if (!$recordId) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Unable to start payment right now. Please try again.',
            ]);
        }

        $savedRecord = $this->applicationAccessModel->find($recordId);

        return $this->response->setJSON([
            'success' => true,
            'paychangu_config' => $this->buildPayChanguConfig($savedRecord),
        ]);
    }

    public function restoreAccess()
    {
        $this->ensureTables();

        if (strtolower($this->request->getMethod()) !== 'post') {
            return redirect()->to(site_url('teach-in-japan/apply'));
        }

        $email = $this->normalizeEmail((string) $this->request->getPost('restore_email'));
        if ($email === '') {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Enter the email address used for the application payment.');
        }

        $record = $this->applicationAccessModel->findLatestVerifiedByEmail($email);
        if (!$record) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'We could not find a confirmed Japan application payment for that email address.');
        }

        $this->grantPaymentAccess($record);

        return redirect()->to(site_url('teach-in-japan/apply'))
            ->with('success', ($record['access_status'] ?? '') === 'submitted'
                ? 'Payment restored. Your application has already been submitted.'
                : 'Payment restored. You can continue filling in the application form.');
    }

    public function resetAccess()
    {
        $this->ensureTables();
        session()->remove(self::ACCESS_SESSION_KEY);

        return redirect()->to(site_url('teach-in-japan/apply'))
            ->with('info', 'You can now start a new Japan application payment and submission.');
    }

    public function status()
    {
        $this->ensureTables();

        $reference = '';
        $email = '';

        if (strtolower($this->request->getMethod()) === 'post') {
            $reference = trim((string) $this->request->getPost('application_reference'));
            $email = $this->normalizeEmail((string) $this->request->getPost('email'));
        } else {
            $reference = trim((string) $this->request->getGet('application_reference'));
            $email = $this->normalizeEmail((string) $this->request->getGet('email'));
        }

        $results = [];
        $error = null;
        $paymentAccessRecord = null;
        $paymentNotice = null;

        if ($reference !== '') {
            $record = $this->applicationModel->where('application_reference', $reference)->first();
            if ($record) {
                $results[] = $record;
            } else {
                $error = 'No application was found for that reference.';
            }
        } elseif ($email !== '') {
            $results = $this->applicationModel
                ->where('email', $email)
                ->orderBy('submitted_at', 'DESC')
                ->limit(10)
                ->find();

            if (!$results) {
                $paymentAccessRecord = $this->applicationAccessModel
                    ->where('email', $email)
                    ->orderBy('paid_at', 'DESC')
                    ->orderBy('created_at', 'DESC')
                    ->first();

                if ($paymentAccessRecord) {
                    $paymentStatus = strtolower(trim((string) ($paymentAccessRecord['payment_status'] ?? '')));
                    $accessStatus = strtolower(trim((string) ($paymentAccessRecord['access_status'] ?? '')));

                    if ($paymentStatus === 'verified' && in_array($accessStatus, ['active', 'submitted'], true)) {
                        $paymentNotice = $accessStatus === 'submitted'
                            ? 'We found a confirmed payment for this email. If you already completed the form on another device or session, restore access below to reopen that paid application flow.'
                            : 'We found a confirmed payment for this email, but the Japan application form has not been submitted yet. Restore access below to continue without paying again.';
                    } elseif ($paymentStatus === 'pending') {
                        $paymentNotice = 'We found a Japan application payment attempt for this email, but it is still pending confirmation. If you already paid, wait a moment and then try Restore Access below or contact support with your PayChangu reference.';
                    } elseif ($paymentStatus === 'failed') {
                        $paymentNotice = 'We found a Japan application payment attempt for this email, but it is marked as failed. If you were charged anyway, contact support with your PayChangu reference.';
                    } else {
                        $paymentNotice = 'We found a Japan application payment record for this email. If you already paid, use Restore Access below to continue.';
                    }
                } else {
                    $error = 'No application was found for that email.';
                }
            }
        }

        return view('pages/teach_in_japan_status', [
            'title' => 'Check Japan Application Status - TutorConnect Malawi',
            'description' => 'Check the status of your Japan application by reference or email.',
            'reference' => $reference,
            'email' => $email,
            'results' => $results,
            'error' => $error,
            'paymentAccessRecord' => $paymentAccessRecord,
            'paymentNotice' => $paymentNotice,
            'applicationsOpen' => $this->isJapanApplicationsOpen(),
            'applicationsClosedMessage' => $this->getJapanApplicationsClosedMessage(),
        ]);
    }

    public function checkPaymentStatus()
    {
        $this->ensureTables();

        $txRef = trim((string) $this->request->getPost('tx_ref'));
        if ($txRef === '') {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Missing transaction reference.',
            ]);
        }

        $record = $this->applicationAccessModel->where('tx_ref', $txRef)->first();
        if (!$record) {
            return $this->response->setStatusCode(404)->setJSON([
                'status' => 'error',
                'message' => 'Payment record not found.',
            ]);
        }

        if ($this->hasUnlockedAccess($record)) {
            $this->grantPaymentAccess($record);
            return $this->response->setJSON([
                'status' => 'verified',
                'access_status' => $record['access_status'],
                'redirect' => site_url('teach-in-japan/apply'),
            ]);
        }

        $record = $this->attemptFinalizeAccessPayment($record, null, false, false);
        if ($this->hasUnlockedAccess($record)) {
            $this->grantPaymentAccess($record);
            return $this->response->setJSON([
                'status' => 'verified',
                'access_status' => $record['access_status'],
                'redirect' => site_url('teach-in-japan/apply'),
            ]);
        }

        if (($record['payment_status'] ?? '') === 'failed') {
            return $this->response->setJSON([
                'status' => 'failed',
                'message' => 'Payment was not successful. Please try again.',
            ]);
        }

        return $this->response->setJSON([
            'status' => 'pending',
            'message' => 'Payment is still being confirmed.',
        ]);
    }

    public function paymentReturn()
    {
        $this->ensureTables();

        $txRef = trim((string) $this->request->getGet('tx_ref'));
        if ($txRef === '') {
            return redirect()->to(site_url('teach-in-japan/apply'))
                ->with('error', 'Invalid payment reference. Please try again.');
        }

        $record = $this->applicationAccessModel->where('tx_ref', $txRef)->first();
        if (!$record) {
            return redirect()->to(site_url('teach-in-japan/apply'))
                ->with('error', 'We could not find that payment record. Please contact support if you were charged.');
        }

        if (!$this->hasUnlockedAccess($record)) {
            // Only trust the redirect flow in test mode. Live payments must be verified
            // by webhook or API verification before form access is unlocked.
            $record = $this->attemptFinalizeAccessPayment($record, null, $this->isPayChanguTestMode(), false);
        }

        if ($this->hasUnlockedAccess($record)) {
            $this->grantPaymentAccess($record);

            return redirect()->to(site_url('teach-in-japan/apply'))
                ->with('success', ($record['access_status'] ?? '') === 'submitted'
                    ? 'Payment confirmed. Your application has already been submitted.'
                    : 'Payment confirmed. You can now complete your Japan application.');
        }

        if (($record['payment_status'] ?? '') === 'failed') {
            return redirect()->to(site_url('teach-in-japan/apply'))
                ->with('error', 'Your payment could not be confirmed. Please try again or restore access using your email if you have already paid.');
        }

        return redirect()->to(site_url('teach-in-japan/apply'))
            ->with('info', 'We are still confirming your payment. If you already paid, use the restore access option below.');
    }

    public function paymentCallback()
    {
        $this->ensureTables();

        $method = strtolower($this->request->getMethod());
        $txRef = null;
        $webhookStatus = null;

        if ($method === 'post') {
            $json = $this->request->getJSON();
            $txRef = trim((string) ($json->tx_ref ?? ''));
            $webhookStatus = trim((string) ($json->status ?? ''));
        } elseif ($method === 'get') {
            $txRef = trim((string) $this->request->getGet('tx_ref'));
            $webhookStatus = trim((string) $this->request->getGet('status'));

            if ($txRef === '') {
                return $this->response->setStatusCode(200)->setJSON([
                    'status' => 'success',
                    'message' => 'Webhook endpoint is active.',
                ]);
            }
        }

        if ($txRef === '') {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Missing transaction reference.',
            ]);
        }

        $record = $this->applicationAccessModel->where('tx_ref', $txRef)->first();
        if (!$record) {
            if ($method === 'get') {
                return redirect()->to(site_url('teach-in-japan/apply'))
                    ->with('error', 'Invalid payment reference.');
            }

            return $this->response->setStatusCode(404)->setJSON([
                'status' => 'error',
                'message' => 'Payment record not found.',
            ]);
        }

        if ($this->hasUnlockedAccess($record)) {
            if ($method === 'get') {
                return redirect()->to(site_url('teach-in-japan/payment/return?tx_ref=' . rawurlencode($txRef)));
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Already processed.',
            ]);
        }

        $record = $this->attemptFinalizeAccessPayment($record, $webhookStatus, false, true);

        if ($method === 'get') {
            return redirect()->to(site_url('teach-in-japan/payment/return?tx_ref=' . rawurlencode($txRef)));
        }

        return $this->response->setJSON([
            'status' => $this->hasUnlockedAccess($record) ? 'success' : (($record['payment_status'] ?? '') === 'failed' ? 'failed' : 'pending'),
            'message' => $this->hasUnlockedAccess($record) ? 'Payment processed successfully.' : 'Payment update received.',
        ]);
    }

    public function submit()
    {
        if (strtolower($this->request->getMethod()) !== 'post') {
            return redirect()->to(site_url('teach-in-japan/apply'));
        }

        $this->ensureTables();
        $accessRecord = $this->resolvePaymentAccess();

        if (!$accessRecord || !$this->hasUnlockedAccess($accessRecord)) {
            return redirect()->to(site_url('teach-in-japan/apply'))
                ->with('error', 'Please complete the application payment first before accessing this form.');
        }

        if (($accessRecord['access_status'] ?? '') === 'submitted') {
            $submittedApplication = $this->resolveSubmittedApplication($accessRecord);

            if (!$submittedApplication) {
                $this->applicationAccessModel->update($accessRecord['id'], [
                    'access_status' => 'active',
                    'application_id' => null,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            } else {
                return redirect()->to(site_url('teach-in-japan/apply'))
                    ->with('success', 'Your application has already been received under reference ' . ($submittedApplication['application_reference'] ?? 'N/A') . '.');
            }
        }

        $accessRecord = $this->resolvePaymentAccess();
        if (!$accessRecord || !$this->hasUnlockedAccess($accessRecord)) {
            return redirect()->to(site_url('teach-in-japan/apply'))
                ->with('error', 'Please reopen your application access and try again.');
        }

        $postData = $this->request->getPost();
        $errors = $this->validateApplication($postData);
        $submittedEmail = $this->normalizeEmail((string) ($postData['email'] ?? ''));

        if ($submittedEmail !== $this->normalizeEmail((string) ($accessRecord['email'] ?? ''))) {
            $errors['email'] = 'Use the same email address that was used for the application payment.';
        }

        if ($errors !== []) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Please correct the form errors before submitting your application.')
                ->with('application_errors', array_values($errors));
        }

        $documentsAlreadyShared = $this->request->getPost('documents_already_shared') ? 1 : 0;
        $hasTeachingCertificate = ($this->request->getPost('has_teaching_certificate') ?? '') === 'yes';
        $reference = $this->generateReference();
        $degreeDocumentPath = null;
        $transcriptDocumentPath = null;
        $passportCopyPath = null;
        $teachingCertificatePath = null;
        $cvDocumentPath = null;

        try {
            $degreeDocumentPath = $this->uploadApplicationFile(
                'degree_document',
                'documents',
                'degree_' . $reference,
                ['pdf', 'jpg', 'jpeg', 'png', 'webp'],
                10 * 1024 * 1024,
                !$documentsAlreadyShared
            );

            $transcriptDocumentPath = $this->uploadApplicationFile(
                'transcript_document',
                'documents',
                'transcript_' . $reference,
                ['pdf', 'jpg', 'jpeg', 'png', 'webp'],
                10 * 1024 * 1024,
                false
            );

            $passportCopyPath = $this->uploadApplicationFile(
                'passport_copy',
                'documents',
                'passport_' . $reference,
                ['pdf', 'jpg', 'jpeg', 'png', 'webp'],
                10 * 1024 * 1024,
                !$documentsAlreadyShared
            );

            $teachingCertificatePath = $this->uploadApplicationFile(
                'teaching_certificate_document',
                'documents',
                'teaching_certificate_' . $reference,
                ['pdf', 'jpg', 'jpeg', 'png', 'webp'],
                10 * 1024 * 1024,
                $hasTeachingCertificate && !$documentsAlreadyShared
            );

            $cvDocumentPath = $this->uploadApplicationFile(
                'cv_document',
                'documents',
                'cv_' . $reference,
                ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'webp'],
                10 * 1024 * 1024,
                !$documentsAlreadyShared
            );
        } catch (\RuntimeException $e) {
            $this->cleanupUploadedFiles([
                $degreeDocumentPath,
                $transcriptDocumentPath,
                $passportCopyPath,
                $teachingCertificatePath,
                $cvDocumentPath,
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }

        $dateOfBirth = (string) $this->request->getPost('date_of_birth');
        $age = $this->calculateAge($dateOfBirth);
        $referees = $this->extractReferees();

        $data = [
            'application_reference' => $reference,
            'full_name' => trim((string) $this->request->getPost('full_name')),
            'email' => $submittedEmail,
            'phone' => trim((string) $this->request->getPost('phone')),
            'nationality' => trim((string) $this->request->getPost('nationality')),
            'gender' => (string) $this->request->getPost('gender'),
            'date_of_birth' => $dateOfBirth,
            'age' => $age,
            'current_address' => trim((string) $this->request->getPost('current_address')),
            'has_valid_passport' => 1,
            'passport_number' => trim((string) $this->request->getPost('passport_number')),
            'passport_expiry_date' => $this->request->getPost('passport_expiry_date') ?: null,
            'willing_to_renew_passport' => ($this->request->getPost('willing_to_renew_passport') ?? '') === 'yes' ? 1 : 0,
            'highest_qualification' => trim((string) $this->request->getPost('highest_qualification')),
            'degree_obtained' => trim((string) $this->request->getPost('degree_obtained')),
            'field_of_study' => trim((string) $this->request->getPost('field_of_study')),
            'institution_name' => trim((string) $this->request->getPost('institution_name')),
            'year_of_completion' => (int) $this->request->getPost('year_of_completion'),
            'has_teaching_certificate' => $hasTeachingCertificate ? 1 : 0,
            'teaching_certificate_details' => trim((string) $this->request->getPost('teaching_certificate_details')),
            'has_teaching_experience' => ($this->request->getPost('has_teaching_experience') ?? '') === 'yes' ? 1 : 0,
            'teaching_experience_location' => trim((string) $this->request->getPost('teaching_experience_location')),
            'subjects_taught' => trim((string) $this->request->getPost('subjects_taught')),
            'level_taught' => trim((string) $this->request->getPost('level_taught')),
            'teaching_experience_duration' => trim((string) $this->request->getPost('teaching_experience_duration')),
            'documents_already_shared' => $documentsAlreadyShared,
            'shared_documents_note' => trim((string) $this->request->getPost('shared_documents_note')),
            'financial_readiness_json' => json_encode([
                'aware_ticket_or_accommodation_optional' => ($this->request->getPost('aware_ticket_or_accommodation_optional') ?? '') === 'yes',
                'willing_to_pay_air_ticket' => ($this->request->getPost('willing_to_pay_air_ticket') ?? '') === 'yes',
                'willing_to_arrange_accommodation' => ($this->request->getPost('willing_to_arrange_accommodation') ?? '') === 'yes',
            ], JSON_UNESCAPED_SLASHES),
            'referees_json' => json_encode($referees, JSON_UNESCAPED_SLASHES),
            'declarations_json' => json_encode([
                'referee_consent' => (bool) $this->request->getPost('referee_consent'),
                'confirm_age_range' => (bool) $this->request->getPost('confirm_age_range'),
                'confirm_valid_passport' => (bool) $this->request->getPost('confirm_valid_passport'),
                'acknowledge_application_fee' => (bool) $this->request->getPost('acknowledge_application_fee'),
                'acknowledge_processing_fee' => (bool) $this->request->getPost('acknowledge_processing_fee'),
                'consent_data_sharing' => (bool) $this->request->getPost('consent_data_sharing'),
                'confirm_truthfulness' => (bool) $this->request->getPost('confirm_truthfulness'),
                'confirm_voluntary_participation' => (bool) $this->request->getPost('confirm_voluntary_participation'),
                'signature_name' => trim((string) $this->request->getPost('signature_name')),
                'signature_date' => (string) $this->request->getPost('signature_date'),
            ], JSON_UNESCAPED_SLASHES),
            'degree_document_path' => $degreeDocumentPath,
            'transcript_document_path' => $transcriptDocumentPath,
            'passport_copy_path' => $passportCopyPath,
            'teaching_certificate_path' => $teachingCertificatePath,
            'cv_document_path' => $cvDocumentPath,
            'application_fee_amount' => $this->getApplicationFee(),
            'processing_fee_amount' => $this->getProcessingFee(),
            'status' => 'submitted',
            'submitted_at' => date('Y-m-d H:i:s'),
        ];

        $applicationId = $this->applicationModel->insert($data);
        if (!$applicationId) {
            $this->cleanupUploadedFiles([
                $degreeDocumentPath,
                $transcriptDocumentPath,
                $passportCopyPath,
                $teachingCertificatePath,
                $cvDocumentPath,
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Your application could not be saved right now. Please try again.');
        }

        $this->applicationAccessModel->update($accessRecord['id'], [
            'access_status' => 'submitted',
            'application_id' => $applicationId,
            'last_accessed_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(site_url('teach-in-japan/apply'))
            ->with('success', 'Application submitted successfully. Your reference is ' . $reference . '. We will review it within 48 hours.');
    }

    private function ensureTables(): void
    {
        try {
            $this->applicationModel->ensureTable();
            $this->applicationAccessModel->ensureTable();
        } catch (\Throwable $e) {
            log_message('error', 'Unable to ensure japan application tables: ' . $e->getMessage());
        }
    }

    private function validateApplication(array $postData): array
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'full_name' => 'required|min_length[5]|max_length[255]',
            'date_of_birth' => 'required|valid_date[Y-m-d]',
            'gender' => 'required|in_list[Male,Female,Other,Prefer not to say]',
            'nationality' => 'required|max_length[100]',
            'phone' => 'required|min_length[8]|max_length[30]',
            'email' => 'required|valid_email|max_length[150]',
            'current_address' => 'required|min_length[10]|max_length[1000]',
            'passport_number' => 'required|max_length[100]',
            'passport_expiry_date' => 'required|valid_date[Y-m-d]',
            'willing_to_renew_passport' => 'required|in_list[yes,no]',
            'highest_qualification' => 'required|max_length[150]',
            'degree_obtained' => 'required|max_length[150]',
            'field_of_study' => 'required|max_length[150]',
            'institution_name' => 'required|max_length[255]',
            'year_of_completion' => 'required|integer',
            'has_teaching_certificate' => 'required|in_list[yes,no]',
            'has_teaching_experience' => 'required|in_list[yes,no]',
            'aware_ticket_or_accommodation_optional' => 'required|in_list[yes,no]',
            'willing_to_pay_air_ticket' => 'required|in_list[yes,no]',
            'willing_to_arrange_accommodation' => 'required|in_list[yes,no]',
            'signature_name' => 'required|min_length[5]|max_length[255]',
            'signature_date' => 'required|valid_date[Y-m-d]',
        ]);

        $errors = [];
        if (!$validation->run($postData)) {
            $errors = $validation->getErrors();
        }

        try {
            $age = $this->calculateAge((string) ($postData['date_of_birth'] ?? ''));
            if ($age < 20 || $age > 40) {
                $errors['age'] = 'Applicants must be between 20 and 40 years old.';
            }
        } catch (\Throwable $e) {
            $errors['date_of_birth'] = 'Please provide a valid date of birth.';
        }

        if (($postData['has_valid_passport'] ?? '') !== 'yes') {
            $errors['has_valid_passport'] = 'A valid passport is required for this opportunity.';
        }

        if ((int) ($postData['year_of_completion'] ?? 0) < 1900) {
            $errors['year_of_completion'] = 'Please provide a valid year of completion.';
        }

        if (($postData['has_teaching_certificate'] ?? '') === 'yes'
            && trim((string) ($postData['teaching_certificate_details'] ?? '')) === '') {
            $errors['teaching_certificate_details'] = 'Please specify your teaching certificate details.';
        }

        if (($postData['has_teaching_experience'] ?? '') === 'yes'
            && trim((string) ($postData['teaching_experience_location'] ?? '')) === '') {
            $errors['teaching_experience_location'] = 'Please tell us where you have taught.';
        }

        $declarationFields = [
            'referee_consent' => 'You must consent to referee verification.',
            'confirm_age_range' => 'You must confirm the age requirement.',
            'confirm_valid_passport' => 'You must confirm the passport requirement.',
            'acknowledge_application_fee' => 'You must acknowledge the MK ' . number_format((float) $this->getApplicationFee(), 0) . ' application fee.',
            'acknowledge_processing_fee' => 'You must acknowledge the processing fee terms.',
            'consent_data_sharing' => 'You must consent to data sharing for placement.',
            'confirm_truthfulness' => 'You must confirm that your information is truthful.',
            'confirm_voluntary_participation' => 'You must confirm voluntary participation.',
        ];

        foreach ($declarationFields as $field => $message) {
            if (!$this->request->getPost($field)) {
                $errors[$field] = $message;
            }
        }

        $refereeTypes = $this->request->getPost('referee_type') ?? [];
        $refereeNames = $this->request->getPost('referee_name') ?? [];
        $refereePhones = $this->request->getPost('referee_phone') ?? [];
        $refereeEmails = $this->request->getPost('referee_email') ?? [];

        for ($i = 0; $i < 3; $i++) {
            if (trim((string) ($refereeNames[$i] ?? '')) === ''
                || trim((string) ($refereePhones[$i] ?? '')) === ''
                || trim((string) ($refereeEmails[$i] ?? '')) === '') {
                $errors['referees'] = 'All three referees are required.';
                break;
            }
        }

        if (!in_array('relative', array_map('strtolower', $refereeTypes), true)) {
            $errors['referee_relative'] = 'At least one referee must be marked as a relative.';
        }

        return $errors;
    }

    private function extractReferees(): array
    {
        $types = $this->request->getPost('referee_type') ?? [];
        $relationshipTitles = $this->request->getPost('referee_relationship') ?? [];
        $names = $this->request->getPost('referee_name') ?? [];
        $organisations = $this->request->getPost('referee_organisation') ?? [];
        $phones = $this->request->getPost('referee_phone') ?? [];
        $emails = $this->request->getPost('referee_email') ?? [];

        $referees = [];
        for ($i = 0; $i < 3; $i++) {
            $referees[] = [
                'type' => trim((string) ($types[$i] ?? '')),
                'relationship_title' => trim((string) ($relationshipTitles[$i] ?? '')),
                'full_name' => trim((string) ($names[$i] ?? '')),
                'organisation' => trim((string) ($organisations[$i] ?? '')),
                'phone' => trim((string) ($phones[$i] ?? '')),
                'email' => trim((string) ($emails[$i] ?? '')),
            ];
        }

        return $referees;
    }

    private function calculateAge(string $dateOfBirth): int
    {
        $dob = new \DateTimeImmutable($dateOfBirth);
        return $dob->diff(new \DateTimeImmutable('now'))->y;
    }

    private function uploadApplicationFile(
        string $field,
        string $subDirectory,
        string $prefix,
        array $allowedExtensions,
        int $maxBytes,
        bool $required
    ): ?string {
        $file = $this->request->getFile($field);

        if (!$file || $file->getError() === UPLOAD_ERR_NO_FILE) {
            if ($required) {
                throw new \RuntimeException('Please upload the required file for ' . str_replace('_', ' ', $field) . '.');
            }

            return null;
        }

        if (!$file->isValid() || $file->hasMoved()) {
            throw new \RuntimeException('There was a problem reading the uploaded file for ' . str_replace('_', ' ', $field) . '.');
        }

        $extension = strtolower((string) $file->getClientExtension());
        if (!in_array($extension, $allowedExtensions, true)) {
            throw new \RuntimeException('Invalid file type uploaded for ' . str_replace('_', ' ', $field) . '.');
        }

        if ($file->getSize() > $maxBytes) {
            throw new \RuntimeException('The uploaded file for ' . str_replace('_', ' ', $field) . ' is too large.');
        }

        $targetDirectory = ROOTPATH . 'public/uploads/japan_applications/' . trim($subDirectory, '/\\');
        if (!is_dir($targetDirectory)) {
            mkdir($targetDirectory, 0755, true);
        }

        $filename = $prefix . '_' . date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
        $file->move($targetDirectory, $filename);

        return 'uploads/japan_applications/' . trim($subDirectory, '/\\') . '/' . $filename;
    }

    private function generateReference(): string
    {
        $referenceLookupModel = new JapanApplicationModel();

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $reference = 'JP-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));

            if (!$referenceLookupModel->where('application_reference', $reference)->first()) {
                return $reference;
            }
        }

        return 'JP-' . date('YmdHis') . '-' . strtoupper(bin2hex(random_bytes(4)));
    }

    private function generatePaymentReference(): string
    {
        for ($attempt = 0; $attempt < 5; $attempt++) {
            $txRef = 'JAP-' . date('YmdHis') . '-' . strtoupper(bin2hex(random_bytes(4)));
            if (!$this->applicationAccessModel->where('tx_ref', $txRef)->first()) {
                return $txRef;
            }
        }

        return 'JAP-' . date('YmdHis') . '-' . strtoupper(bin2hex(random_bytes(6)));
    }

    private function cleanupUploadedFiles(array $paths): void
    {
        foreach ($paths as $path) {
            if (!$path) {
                continue;
            }

            $fullPath = ROOTPATH . 'public/' . ltrim(str_replace('\\', '/', $path), '/');
            if (is_file($fullPath)) {
                @unlink($fullPath);
            }
        }
    }

    private function normalizeEmail(string $email): string
    {
        return strtolower(trim($email));
    }

    private function splitFullName(string $fullName): array
    {
        $parts = preg_split('/\s+/', trim($fullName)) ?: [];
        $firstName = $parts[0] ?? 'Japan';
        $lastName = count($parts) > 1 ? implode(' ', array_slice($parts, 1)) : 'Applicant';

        return [$firstName, $lastName];
    }

    private function resolveSubmittedApplication(array $accessRecord): ?array
    {
        $applicationId = (int) ($accessRecord['application_id'] ?? 0);
        if ($applicationId > 0) {
            $application = $this->applicationModel->find($applicationId);
            if ($application && $this->normalizeEmail((string) ($application['email'] ?? '')) === $this->normalizeEmail((string) ($accessRecord['email'] ?? ''))) {
                return $application;
            }
        }

        return null;
    }

    private function getPayChanguPublicKey(): string
    {
        return getenv('PAYCHANGU_PUBLIC_KEY') ?: 'PUB-TEST-MB33j3iotOje4NXksN3UxQh8D9vZDYTk';
    }

    private function isPayChanguTestMode(): bool
    {
        return stripos($this->getPayChanguPublicKey(), 'PUB-TEST-') === 0;
    }

    private function buildPayChanguConfig(array $record): array
    {
        [$firstName, $lastName] = $this->splitFullName((string) ($record['full_name'] ?? 'Japan Applicant'));

        return [
            'public_key' => $this->getPayChanguPublicKey(),
            'tx_ref' => $record['tx_ref'],
            'amount' => (float) ($record['amount'] ?? $this->getApplicationFee()),
            'currency' => $record['currency'] ?? 'MWK',
            'callback_url' => base_url('teach-in-japan/payment/callback'),
            'return_url' => base_url('teach-in-japan/payment/return'),
            'customer' => [
                'email' => $record['email'],
                'first_name' => $firstName,
                'last_name' => $lastName,
            ],
            'customizations' => [
                'title' => 'TutorConnect Malawi - Japan Application Fee',
                'description' => 'Non-refundable application fee for the Japan teaching opportunity',
                'logo' => base_url('favicon.ico'),
            ],
            'meta' => [
                'product' => 'japan_application_unlock',
                'email' => $record['email'],
                'phone' => $record['phone'] ?? '',
            ],
        ];
    }

    private function getApplicationFee(): int
    {
        $raw = function_exists('site_setting')
            ? (site_setting('japan_application_fee', (string) self::DEFAULT_APPLICATION_FEE) ?? (string) self::DEFAULT_APPLICATION_FEE)
            : (string) self::DEFAULT_APPLICATION_FEE;

        $value = (int) preg_replace('/[^0-9]/', '', (string) $raw);
        return $value > 0 ? $value : self::DEFAULT_APPLICATION_FEE;
    }

    private function getProcessingFee(): int
    {
        $raw = function_exists('site_setting')
            ? (site_setting('japan_processing_fee', (string) self::DEFAULT_PROCESSING_FEE) ?? (string) self::DEFAULT_PROCESSING_FEE)
            : (string) self::DEFAULT_PROCESSING_FEE;

        $value = (int) preg_replace('/[^0-9]/', '', (string) $raw);
        return $value > 0 ? $value : self::DEFAULT_PROCESSING_FEE;
    }

    private function isJapanApplicationsOpen(): bool
    {
        $raw = strtolower(trim((string) site_setting('japan_applications_open', '1')));
        return in_array($raw, ['1', 'true', 'yes', 'on'], true);
    }

    private function getJapanApplicationsClosedMessage(): string
    {
        $message = trim((string) site_setting(
            'japan_applications_closed_message',
            'Japan applications are currently closed. Please check back soon or contact support.'
        ));

        return $message !== ''
            ? $message
            : 'Japan applications are currently closed. Please check back soon or contact support.';
    }

    private function hasUnlockedAccess(array $record): bool
    {
        return ($record['payment_status'] ?? '') === 'verified'
            && in_array($record['access_status'] ?? '', ['active', 'submitted'], true);
    }

    private function grantPaymentAccess(array $record): void
    {
        session()->set(self::ACCESS_SESSION_KEY, $record['access_token']);

        $this->applicationAccessModel->update($record['id'], [
            'last_accessed_at' => date('Y-m-d H:i:s'),
        ]);
    }

    private function resolvePaymentAccess(string $txRef = ''): ?array
    {
        if ($txRef !== '') {
            $record = $this->applicationAccessModel->where('tx_ref', $txRef)->first();
            if ($record && $this->hasUnlockedAccess($record)) {
                $this->grantPaymentAccess($record);
                return $this->applicationAccessModel->find($record['id']);
            }
        }

        $accessToken = trim((string) session()->get(self::ACCESS_SESSION_KEY));
        if ($accessToken === '') {
            return null;
        }

        $record = $this->applicationAccessModel->findActiveByToken($accessToken);
        if (!$record) {
            session()->remove(self::ACCESS_SESSION_KEY);
            return null;
        }

        $this->applicationAccessModel->update($record['id'], [
            'last_accessed_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->applicationAccessModel->find($record['id']);
    }

    private function attemptFinalizeAccessPayment(
        array $record,
        ?string $webhookStatus = null,
        bool $trustRedirect = false,
        bool $markFailure = false
    ): array {
        if ($this->hasUnlockedAccess($record)) {
            return $record;
        }

        $paychangu = \Config\Services::paychangu();
        $apiResult = $paychangu->verifyPayment((string) $record['tx_ref']);

        $webhookStatus = strtolower(trim((string) $webhookStatus));
        $webhookSuccess = $webhookStatus === 'success';
        $apiSuccess = $paychangu->isSuccessfulVerification(
            $apiResult,
            (string) ($record['tx_ref'] ?? ''),
            (string) ($record['currency'] ?? 'MWK'),
            isset($record['amount']) ? (float) $record['amount'] : null
        );
        $fallbackSuccess = $this->isPayChanguTestMode() && $apiResult === null && !empty($record['tx_ref']) && $webhookStatus !== 'failed';
        $redirectSuccess = $trustRedirect && !empty($record['tx_ref']) && $webhookStatus !== 'failed';

        if ($webhookSuccess || $apiSuccess || $fallbackSuccess || $redirectSuccess) {
            $this->applicationAccessModel->update($record['id'], [
                'payment_status' => 'verified',
                'access_status' => ($record['access_status'] ?? '') === 'submitted' ? 'submitted' : 'active',
                'paid_at' => $record['paid_at'] ?: date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            return $this->applicationAccessModel->find($record['id']) ?? $record;
        }

        if ($markFailure && $webhookStatus === 'failed') {
            $this->applicationAccessModel->update($record['id'], [
                'payment_status' => 'failed',
                'access_status' => 'failed',
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            return $this->applicationAccessModel->find($record['id']) ?? $record;
        }

        return $record;
    }
}
