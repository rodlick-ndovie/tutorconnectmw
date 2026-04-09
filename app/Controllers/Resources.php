<?php

namespace App\Controllers;

use App\Models\PastPaperPurchaseModel;
use App\Models\ResourceModel;
use App\Models\User;

class Resources extends BaseController
{
    protected $pastPapersModel;
    protected $pastPaperPurchaseModel;
    protected $tutorVideosModel;
    protected $resourceModel;
    private const PAST_PAPER_ACCESS_SESSION_KEY = 'paid_past_paper_access_tokens';

    public function __construct()
    {
        helper(['form', 'url']);
        $this->pastPapersModel = new \App\Models\PastPapersModel();
        $this->pastPaperPurchaseModel = new PastPaperPurchaseModel();
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
            'papers' => [],
            'videos' => [],
            'featured_videos' => [],
            'papers_pagination' => [
                'page_param' => 'papers_page',
                'current_page' => 1,
                'per_page' => 0,
                'total_items' => 0,
                'total_pages' => 0,
                'start_item' => 0,
                'end_item' => 0,
            ],
            'videos_pagination' => [
                'page_param' => 'videos_page',
                'current_page' => 1,
                'per_page' => 0,
                'total_items' => 0,
                'total_pages' => 0,
                'start_item' => 0,
                'end_item' => 0,
            ],
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
            $paperResults = $this->pastPapersModel->getFilteredPapersWithUploader($filters);
            $paperPagination = $this->paginateItems(
                $paperResults,
                $resourceType === 'all' ? 6 : 12,
                'papers_page'
            );

            $data['papers'] = $paperPagination['items'];
            $data['papers_pagination'] = $paperPagination['meta'];
        }

        if ($resourceType === 'videos' || $resourceType === 'all') {
            $videoResults = $this->tutorVideosModel->getApprovedVideos($filters);
            $videoPagination = $this->paginateItems(
                $videoResults,
                $resourceType === 'all' ? 8 : 12,
                'videos_page'
            );

            $data['videos'] = $videoPagination['items'];
            $data['videos_pagination'] = $videoPagination['meta'];
            $data['featured_videos'] = $this->tutorVideosModel->getFeaturedVideos();
        }

        // Get filter options for both resources
        $data['filters'] = $filters;
        $data['filter_options'] = array_merge(
            $this->pastPapersModel->getFilterOptions(),
            $this->tutorVideosModel->getFilterOptions()
        );

        $data = $this->attachPastPaperPaymentData($data, $data['papers'] ?? []);

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

        $data = $this->attachPastPaperPaymentData($data, $data['papers']);

        return view('resources/past_papers', $data);
    }

    public function pastPaperCheckout($paperId)
    {
        $paper = $this->pastPapersModel
            ->select('past_papers.*, users.first_name, users.last_name')
            ->join('users', 'users.id = past_papers.uploaded_by', 'left')
            ->where('past_papers.id', $paperId)
            ->first();

        if (!$paper || !$paper['is_active']) {
            return redirect()->to(site_url('resources/past-papers'))
                ->with('error', 'Past paper not found or unavailable.');
        }

        if (!$this->paperRequiresPayment($paper) || $this->canBypassPastPaperPayment($paper)) {
            return redirect()->to($this->buildPastPaperDownloadUrl((int) $paper['id']));
        }

        $currentUser = $this->getCurrentUserSummary();
        $accessiblePaperIds = $this->getAccessiblePaidPaperIds([$paper], $currentUser);

        if (in_array((int) $paper['id'], $accessiblePaperIds, true)) {
            $purchase = $this->resolveVerifiedPurchaseAccess($paper);
            return redirect()->to($this->buildPastPaperDownloadUrl((int) $paper['id'], (string) ($purchase['tx_ref'] ?? '')));
        }

        return view('resources/past_paper_checkout', [
            'title' => 'Pay for Past Paper - TutorConnect Malawi',
            'paper' => array_merge($paper, [
                'price' => $this->normalizeMoneyAmount($paper['price'] ?? 0),
            ]),
            'current_user' => $currentUser,
        ]);
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

        $purchaseAccess = null;

        if ($this->paperRequiresPayment($paper) && !$this->canBypassPastPaperPayment($paper)) {
            $purchaseAccess = $this->resolveVerifiedPurchaseAccess(
                $paper,
                trim((string) $this->request->getGet('tx_ref'))
            );

            if (!$purchaseAccess) {
                return redirect()->to(site_url('resources/past-papers/pay/' . (int) $paperId))
                    ->with('error', 'This past paper requires payment before download.');
            }
        }

        // Prevent duplicate counts using session tracking
        $session = session();
        $lastDownload = $session->get('last_download_' . $paperId);
        $now = time();

        // Only increment if this paper wasn't downloaded in the last 10 seconds
        if (!$lastDownload || ($now - $lastDownload) > 10) {
            $this->pastPapersModel->incrementDownloadCount($paperId);

            if ($purchaseAccess) {
                $this->pastPaperPurchaseModel->incrementGrantedDownloadCount((int) $purchaseAccess['id']);
            }

            $session->set('last_download_' . $paperId, $now);
        }

        // Normalize file paths from both legacy (writable) and public storage
        $fileUrl = trim((string) ($paper['file_url'] ?? ''));
        $normalizedPath = ltrim((string) (parse_url($fileUrl, PHP_URL_PATH) ?: $fileUrl), '/');
        $localPath = '';

        if ($normalizedPath !== '' && str_contains($normalizedPath, 'writable/uploads/past_papers/')) {
            $relative = str_replace(base_url() . '/', '', $fileUrl);
            $relative = str_replace('writable/', '', $relative);
            $localPath = WRITEPATH . str_replace(['..', '//'], ['', '/'], $relative);
        } elseif ($normalizedPath !== '' && str_contains($normalizedPath, 'uploads/past_papers/')) {
            $localPath = FCPATH . str_replace(['..', '//'], ['', '/'], $normalizedPath);
        }

        if ($localPath && file_exists($localPath)) {
            $downloadName = basename($localPath);
            return $this->response->download($localPath, null)->setFileName($downloadName);
        }

        // Fallback to redirect if direct file not found locally
        return redirect()->to($fileUrl ?: '/resources')->with('error', 'File missing on server.');
    }

    public function restorePastPaperAccess()
    {
        if (strtolower($this->request->getMethod()) !== 'post') {
            return redirect()->to(site_url('resources/past-papers'));
        }

        $paperId = (int) $this->request->getPost('paper_id');
        $email = $this->normalizeEmail((string) $this->request->getPost('restore_email'));

        if ($paperId <= 0) {
            return redirect()->to(site_url('resources/past-papers'))
                ->with('error', 'Past paper not found.');
        }

        if ($email === null) {
            return redirect()->to(site_url('resources/past-papers/pay/' . $paperId))
                ->withInput()
                ->with('error', 'Enter the email address used for the paid download.');
        }

        $paper = $this->pastPapersModel->find($paperId);
        if (!$paper || !$paper['is_active']) {
            return redirect()->to(site_url('resources/past-papers'))
                ->with('error', 'Past paper not found or unavailable.');
        }

        $purchase = $this->pastPaperPurchaseModel->findLatestVerifiedByEmail($paperId, $email);
        if (!$purchase) {
            return redirect()->to(site_url('resources/past-papers/pay/' . $paperId))
                ->withInput()
                ->with('error', 'We could not find a confirmed payment for that email address on this past paper.');
        }

        $this->grantPastPaperAccess($purchase);

        return redirect()->to($this->buildPastPaperDownloadUrl($paperId, (string) $purchase['tx_ref']))
            ->with('success', 'Payment restored. Your download is starting now.');
    }

    public function initiatePastPaperPayment()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON([
                'success' => false,
                'message' => 'Invalid request method.',
            ]);
        }

        $paperId = (int) $this->request->getPost('paper_id');
        $paper = $this->pastPapersModel->find($paperId);

        if (!$paper || !$paper['is_active']) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Past paper not found or unavailable.',
            ]);
        }

        if (!$this->paperRequiresPayment($paper) || $this->canBypassPastPaperPayment($paper)) {
            return $this->response->setJSON([
                'success' => true,
                'already_paid' => true,
                'redirect' => $this->buildPastPaperDownloadUrl((int) $paper['id']),
                'download_url' => $this->buildPastPaperDownloadUrl((int) $paper['id']),
                'message' => 'This paper is already available to your account.',
            ]);
        }

        $currentUser = $this->getCurrentUserSummary();
        $fullName = trim((string) ($this->request->getPost('buyer_full_name') ?: ($currentUser['full_name'] ?? '')));
        $email = $this->normalizeEmail((string) ($this->request->getPost('buyer_email') ?: ($currentUser['email'] ?? '')));
        $phone = trim((string) ($this->request->getPost('buyer_phone') ?: ($currentUser['phone'] ?? '')));
        $termsAccepted = (bool) $this->request->getPost('paper_terms');

        $validation = \Config\Services::validation();
        $validation->setRules([
            'buyer_full_name' => 'required|min_length[5]|max_length[255]',
            'buyer_email' => 'required|valid_email|max_length[150]',
            'buyer_phone' => 'required|min_length[8]|max_length[30]',
        ]);

        $payload = [
            'buyer_full_name' => $fullName,
            'buyer_email' => $email,
            'buyer_phone' => $phone,
        ];

        $errors = [];
        if (!$validation->run($payload)) {
            $errors = $validation->getErrors();
        }

        if (!$termsAccepted) {
            $errors['paper_terms'] = 'Confirm the paid download terms before continuing.';
        }

        if ($errors !== []) {
            return $this->response->setStatusCode(422)->setJSON([
                'success' => false,
                'message' => reset($errors) ?: 'Please complete the payment form.',
                'errors' => $errors,
            ]);
        }

        $existingVerified = $this->pastPaperPurchaseModel->findLatestVerifiedAccess(
            $paperId,
            $currentUser['id'] ?: null,
            $email,
            $this->getPastPaperAccessTokens()[$paperId] ?? null
        );

        if (!$existingVerified && $email !== null) {
            $existingVerified = $this->pastPaperPurchaseModel->findLatestVerifiedByEmail($paperId, $email);
        }

        if ($existingVerified) {
            $this->grantPastPaperAccess($existingVerified);

            return $this->response->setJSON([
                'success' => true,
                'already_paid' => true,
                'redirect' => $this->buildPastPaperDownloadUrl($paperId, (string) $existingVerified['tx_ref']),
                'success_url' => $this->buildPastPaperSuccessUrl((string) $existingVerified['tx_ref']),
                'download_url' => $this->buildPastPaperDownloadUrl($paperId, (string) $existingVerified['tx_ref']),
                'message' => 'Payment already confirmed for this paper.',
            ]);
        }

        $price = $this->normalizeMoneyAmount($paper['price'] ?? 0);
        if ($price <= 0) {
            return $this->response->setStatusCode(422)->setJSON([
                'success' => false,
                'message' => 'This paper is marked as paid, but no valid price is configured yet.',
            ]);
        }

        if ($this->getPayChanguPublicKey() === '' || $this->getPayChanguSecretKey() === '') {
            log_message('error', 'Past paper payment setup missing PAYCHANGU_PUBLIC_KEY or PAYCHANGU_SECRET_KEY in environment.');

            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Payment gateway is not configured correctly on this server. Please update the PayChangu keys in the environment file.',
            ]);
        }

        $txRef = 'PP-' . $paperId . '-' . time() . '-' . bin2hex(random_bytes(4));
        $accessToken = bin2hex(random_bytes(32));

        $purchaseId = $this->pastPaperPurchaseModel->insert([
            'past_paper_id' => $paperId,
            'user_id' => $currentUser['id'] ?: null,
            'tx_ref' => $txRef,
            'buyer_name' => $fullName,
            'buyer_email' => $email,
            'buyer_phone' => $phone !== '' ? $phone : null,
            'amount' => $price,
            'currency' => 'MWK',
            'payment_method' => 'paychangu',
            'payment_status' => 'pending',
            'access_token' => $accessToken,
            'download_count' => 0,
        ]);

        if (!$purchaseId) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Unable to start payment right now. Please try again.',
            ]);
        }

        $purchase = $this->pastPaperPurchaseModel->find($purchaseId);
        $payChanguConfig = $this->buildPastPaperPayChanguConfig($purchase, $paper);
        $configError = $this->validatePayChanguCheckoutConfig($payChanguConfig);

        if ($configError !== null) {
            log_message('error', 'Past paper PayChangu config invalid: ' . $configError . ' | context=' . json_encode([
                'paper_id' => $paperId,
                'tx_ref' => $payChanguConfig['tx_ref'] ?? null,
                'callback_url' => $payChanguConfig['callback_url'] ?? null,
                'return_url' => $payChanguConfig['return_url'] ?? null,
                'customizations' => $payChanguConfig['customizations'] ?? null,
                'host' => (string) ($this->request->getServer('HTTP_HOST') ?: $this->request->getHeaderLine('Host')),
                'base_url' => base_url(),
                'site_url' => site_url(),
            ]));

            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => $configError,
            ]);
        }

        log_message('info', 'Past paper PayChangu config prepared: ' . json_encode([
            'paper_id' => $paperId,
            'tx_ref' => $payChanguConfig['tx_ref'] ?? null,
            'amount' => $payChanguConfig['amount'] ?? null,
            'currency' => $payChanguConfig['currency'] ?? null,
            'callback_url' => $payChanguConfig['callback_url'] ?? null,
            'return_url' => $payChanguConfig['return_url'] ?? null,
            'customizations' => $payChanguConfig['customizations'] ?? null,
            'public_key_prefix' => substr((string) ($payChanguConfig['public_key'] ?? ''), 0, 12),
        ]));

        return $this->response->setJSON([
            'success' => true,
            'paychangu_config' => $payChanguConfig,
        ]);
    }

    public function checkPastPaperPaymentStatus()
    {
        $txRef = trim((string) $this->request->getPost('tx_ref'));
        if ($txRef === '') {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Missing transaction reference.',
            ]);
        }

        $purchase = $this->pastPaperPurchaseModel->findByTxRef($txRef);
        if (!$purchase) {
            return $this->response->setStatusCode(404)->setJSON([
                'status' => 'error',
                'message' => 'Payment record not found.',
            ]);
        }

        if ($this->isVerifiedPastPaperPurchase($purchase)) {
            $this->grantPastPaperAccess($purchase);

            return $this->response->setJSON([
                'status' => 'verified',
                'redirect' => $this->buildPastPaperDownloadUrl((int) $purchase['past_paper_id'], (string) $purchase['tx_ref']),
                'success_url' => $this->buildPastPaperSuccessUrl((string) $purchase['tx_ref']),
                'download_url' => $this->buildPastPaperDownloadUrl((int) $purchase['past_paper_id'], (string) $purchase['tx_ref']),
            ]);
        }

        $purchase = $this->attemptFinalizePastPaperPayment($purchase, null, false, false);

        if ($this->isVerifiedPastPaperPurchase($purchase)) {
            $this->grantPastPaperAccess($purchase);

            return $this->response->setJSON([
                'status' => 'verified',
                'redirect' => $this->buildPastPaperDownloadUrl((int) $purchase['past_paper_id'], (string) $purchase['tx_ref']),
                'success_url' => $this->buildPastPaperSuccessUrl((string) $purchase['tx_ref']),
                'download_url' => $this->buildPastPaperDownloadUrl((int) $purchase['past_paper_id'], (string) $purchase['tx_ref']),
            ]);
        }

        if (($purchase['payment_status'] ?? '') === 'failed') {
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

    public function pastPaperPaymentReturn()
    {
        $txRef = trim((string) $this->request->getGet('tx_ref'));
        if ($txRef === '') {
            return redirect()->to(site_url('resources/past-papers'))
                ->with('error', 'Invalid payment reference. Please try again.');
        }

        $purchase = $this->pastPaperPurchaseModel->findByTxRef($txRef);
        if (!$purchase) {
            return redirect()->to(site_url('resources/past-papers'))
                ->with('error', 'We could not find that payment record. Please contact support if you were charged.');
        }

        if (!$this->isVerifiedPastPaperPurchase($purchase)) {
            $purchase = $this->attemptFinalizePastPaperPayment($purchase, null, $this->isPayChanguTestMode(), false);
        }

        if ($this->isVerifiedPastPaperPurchase($purchase)) {
            $this->grantPastPaperAccess($purchase);

            return redirect()->to($this->buildPastPaperDownloadUrl((int) $purchase['past_paper_id'], (string) $purchase['tx_ref']))
                ->with('success', 'Payment confirmed. Your download is starting now.');
        }

        if (($purchase['payment_status'] ?? '') === 'failed') {
            return redirect()->to(site_url('resources/past-papers'))
                ->with('error', 'Your payment could not be confirmed. Please try again.');
        }

        return redirect()->to(site_url('resources/past-papers'))
            ->with('info', 'We are still confirming your payment. If you already paid, try the download again in a moment using the same email.');
    }

    public function pastPaperPaymentSuccess()
    {
        $txRef = trim((string) $this->request->getGet('tx_ref'));
        if ($txRef === '') {
            return redirect()->to(site_url('resources/past-papers'))
                ->with('error', 'Invalid payment reference. Please try again.');
        }

        $purchase = $this->pastPaperPurchaseModel->findByTxRef($txRef);
        if (!$purchase) {
            return redirect()->to(site_url('resources/past-papers'))
                ->with('error', 'We could not find that payment record. Please contact support if you were charged.');
        }

        if (!$this->isVerifiedPastPaperPurchase($purchase)) {
            $purchase = $this->attemptFinalizePastPaperPayment($purchase, null, $this->isPayChanguTestMode(), false);
        }

        if (!$this->isVerifiedPastPaperPurchase($purchase)) {
            if (($purchase['payment_status'] ?? '') === 'failed') {
                return redirect()->to(site_url('resources/past-papers'))
                    ->with('error', 'Your payment could not be confirmed. Please try again.');
            }

            return redirect()->to(site_url('resources/past-papers'))
                ->with('info', 'We are still confirming your payment. If you already paid, try the download again in a moment using the same email.');
        }

        $this->grantPastPaperAccess($purchase);

        $paper = $this->pastPapersModel
            ->select('past_papers.*, users.first_name, users.last_name')
            ->join('users', 'users.id = past_papers.uploaded_by', 'left')
            ->where('past_papers.id', $purchase['past_paper_id'])
            ->first();

        if (!$paper) {
            return redirect()->to(site_url('resources/past-papers'))
                ->with('error', 'The paper was paid for, but we could not load its details.');
        }

        return view('resources/past_paper_success', [
            'title' => 'Payment Successful - TutorConnect Malawi',
            'paper' => array_merge($paper, [
                'price' => $this->normalizeMoneyAmount($purchase['amount'] ?? ($paper['price'] ?? 0)),
            ]),
            'purchase' => $purchase,
            'download_url' => $this->buildPastPaperDownloadUrl((int) $purchase['past_paper_id'], (string) $purchase['tx_ref']),
        ]);
    }

    public function pastPaperPaymentCallback()
    {
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
                    'message' => 'Past paper payment callback is active.',
                ]);
            }
        }

        if ($txRef === '') {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Missing transaction reference.',
            ]);
        }

        $purchase = $this->pastPaperPurchaseModel->findByTxRef($txRef);
        if (!$purchase) {
            if ($method === 'get') {
                return redirect()->to(site_url('resources/past-papers'))
                    ->with('error', 'Invalid payment reference.');
            }

            return $this->response->setStatusCode(404)->setJSON([
                'status' => 'error',
                'message' => 'Payment record not found.',
            ]);
        }

        if ($this->isVerifiedPastPaperPurchase($purchase)) {
            if ($method === 'get') {
                return redirect()->to(site_url('resources/past-papers/payment/return?tx_ref=' . rawurlencode($txRef)));
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Already processed.',
            ]);
        }

        $purchase = $this->attemptFinalizePastPaperPayment($purchase, $webhookStatus, false, true);

        if ($method === 'get') {
            return redirect()->to(site_url('resources/past-papers/payment/return?tx_ref=' . rawurlencode($txRef)));
        }

        return $this->response->setJSON([
            'status' => $this->isVerifiedPastPaperPurchase($purchase)
                ? 'success'
                : (($purchase['payment_status'] ?? '') === 'failed' ? 'failed' : 'pending'),
            'message' => $this->isVerifiedPastPaperPurchase($purchase)
                ? 'Payment processed successfully.'
                : 'Payment update received.',
        ]);
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

    private function paginateItems(array $items, int $perPage, string $pageParam): array
    {
        $perPage = max(1, $perPage);
        $totalItems = count($items);
        $totalPages = $totalItems > 0 ? (int) ceil($totalItems / $perPage) : 0;
        $currentPage = max(1, (int) $this->request->getGet($pageParam));

        if ($totalPages > 0 && $currentPage > $totalPages) {
            $currentPage = $totalPages;
        }

        $offset = ($currentPage - 1) * $perPage;

        return [
            'items' => array_slice($items, $offset, $perPage),
            'meta' => [
                'page_param' => $pageParam,
                'current_page' => $currentPage,
                'per_page' => $perPage,
                'total_items' => $totalItems,
                'total_pages' => $totalPages,
                'start_item' => $totalItems > 0 ? $offset + 1 : 0,
                'end_item' => $totalItems > 0 ? min($offset + $perPage, $totalItems) : 0,
            ],
        ];
    }

    private function attachPastPaperPaymentData(array $data, array $papers): array
    {
        $currentUser = $data['current_user'] ?? $this->getCurrentUserSummary();
        $accessiblePaperIds = $this->getAccessiblePaidPaperIds($papers, $currentUser);

        foreach ($papers as &$paper) {
            $paper['is_paid'] = $this->paperRequiresPayment($paper) ? 1 : 0;
            $paper['price'] = $this->normalizeMoneyAmount($paper['price'] ?? 0);
            $paper['has_paid_access'] = in_array((int) $paper['id'], $accessiblePaperIds, true)
                || $this->canBypassPastPaperPayment($paper, $currentUser);
        }
        unset($paper);

        $data['current_user'] = $currentUser;
        $data['papers'] = $papers;
        $data['purchased_paper_ids'] = $accessiblePaperIds;
        $data['paper_checkout_catalog'] = $this->buildPaperCheckoutCatalog($papers);

        return $data;
    }

    private function buildPaperCheckoutCatalog(array $papers): array
    {
        $catalog = [];

        foreach ($papers as $paper) {
            $paperId = (int) ($paper['id'] ?? 0);
            if ($paperId <= 0) {
                continue;
            }

            $catalog[$paperId] = [
                'id' => $paperId,
                'title' => $paper['paper_title'] ?? 'Past Paper',
                'is_paid' => $this->paperRequiresPayment($paper),
                'price' => $this->normalizeMoneyAmount($paper['price'] ?? 0),
                'formatted_price' => number_format($this->normalizeMoneyAmount($paper['price'] ?? 0), 0),
                'has_access' => !empty($paper['has_paid_access']),
                'download_url' => $this->buildPastPaperDownloadUrl($paperId),
            ];
        }

        return $catalog;
    }

    private function getAccessiblePaidPaperIds(array $papers, array $currentUser): array
    {
        $paidPaperIds = [];
        foreach ($papers as $paper) {
            if ($this->paperRequiresPayment($paper) && !$this->canBypassPastPaperPayment($paper, $currentUser)) {
                $paidPaperIds[] = (int) $paper['id'];
            }
        }

        if ($paidPaperIds === []) {
            return [];
        }

        $tokens = [];
        foreach ($this->getPastPaperAccessTokens() as $paperId => $token) {
            if (in_array((int) $paperId, $paidPaperIds, true)) {
                $tokens[] = (string) $token;
            }
        }

        return $this->pastPaperPurchaseModel->getAccessiblePaperIds(
            $paidPaperIds,
            $currentUser['id'] ?: null,
            $currentUser['email'] ?: null,
            $tokens
        );
    }

    private function getCurrentUserSummary(): array
    {
        $userId = (int) session()->get('user_id');
        $role = trim((string) session()->get('role'));

        if ($userId <= 0) {
            return [
                'id' => null,
                'role' => $role !== '' ? $role : null,
                'full_name' => '',
                'email' => '',
                'phone' => '',
            ];
        }

        $user = (new User())->find($userId);
        if (!$user) {
            return [
                'id' => $userId,
                'role' => $role !== '' ? $role : null,
                'full_name' => '',
                'email' => '',
                'phone' => '',
            ];
        }

        $fullName = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));
        if ($fullName === '') {
            $fullName = trim((string) ($user['username'] ?? ''));
        }

        return [
            'id' => (int) $user['id'],
            'role' => $role !== '' ? $role : ($user['role'] ?? null),
            'full_name' => $fullName,
            'email' => $this->normalizeEmail((string) ($user['email'] ?? '')) ?? '',
            'phone' => trim((string) ($user['phone'] ?? '')),
        ];
    }

    private function paperRequiresPayment(array $paper): bool
    {
        return (int) ($paper['is_paid'] ?? 0) === 1
            && $this->normalizeMoneyAmount($paper['price'] ?? 0) > 0;
    }

    private function canBypassPastPaperPayment(array $paper, ?array $currentUser = null): bool
    {
        if (!$this->paperRequiresPayment($paper)) {
            return true;
        }

        $currentUser ??= $this->getCurrentUserSummary();

        if (!empty($currentUser['role']) && in_array($currentUser['role'], ['admin', 'sub-admin'], true)) {
            return true;
        }

        return !empty($currentUser['id'])
            && !empty($paper['uploaded_by'])
            && (int) $paper['uploaded_by'] === (int) $currentUser['id'];
    }

    private function resolveVerifiedPurchaseAccess(array $paper, string $txRef = ''): ?array
    {
        if (!$this->paperRequiresPayment($paper)) {
            return null;
        }

        if ($txRef !== '') {
            $purchase = $this->pastPaperPurchaseModel->findByTxRefForPaper($txRef, (int) $paper['id']);

            if ($purchase && $this->isVerifiedPastPaperPurchase($purchase)) {
                $this->grantPastPaperAccess($purchase);
                return $purchase;
            }
        }

        $currentUser = $this->getCurrentUserSummary();
        $accessTokens = $this->getPastPaperAccessTokens();
        $purchase = $this->pastPaperPurchaseModel->findLatestVerifiedAccess(
            (int) $paper['id'],
            $currentUser['id'] ?: null,
            $currentUser['email'] ?: null,
            $accessTokens[(int) $paper['id']] ?? null
        );

        if ($purchase) {
            $this->grantPastPaperAccess($purchase);
        }

        return $purchase;
    }

    private function grantPastPaperAccess(array $purchase): void
    {
        $paperId = (int) ($purchase['past_paper_id'] ?? 0);
        $accessToken = trim((string) ($purchase['access_token'] ?? ''));

        if ($paperId <= 0 || $accessToken === '') {
            return;
        }

        $tokens = $this->getPastPaperAccessTokens();
        $tokens[$paperId] = $accessToken;
        session()->set(self::PAST_PAPER_ACCESS_SESSION_KEY, $tokens);
    }

    private function getPastPaperAccessTokens(): array
    {
        $tokens = session()->get(self::PAST_PAPER_ACCESS_SESSION_KEY);
        return is_array($tokens) ? $tokens : [];
    }

    private function buildPastPaperPayChanguConfig(array $purchase, array $paper): array
    {
        [$firstName, $lastName] = $this->splitFullName((string) ($purchase['buyer_name'] ?? 'Past Paper Buyer'));

        return [
            'public_key' => $this->getPayChanguPublicKey(),
            'tx_ref' => $purchase['tx_ref'],
            'amount' => (float) ($purchase['amount'] ?? 0),
            'currency' => $purchase['currency'] ?? 'MWK',
            'callback_url' => base_url('resources/past-papers/payment/callback'),
            'return_url' => base_url('resources/past-papers/payment/return'),
            'customer' => [
                'email' => $purchase['buyer_email'],
                'first_name' => $firstName,
                'last_name' => $lastName,
            ],
            'customizations' => [
                'title' => 'TutorConnect Malawi - Paid Past Paper',
                'description' => 'Download access for ' . ($paper['paper_title'] ?? 'Past Paper'),
                'logo' => base_url('favicon.ico'),
            ],
            'meta' => [
                'product' => 'paid_past_paper',
                'paper_id' => (int) ($paper['id'] ?? 0),
                'paper_title' => $paper['paper_title'] ?? 'Past Paper',
            ],
        ];
    }

    private function validatePayChanguCheckoutConfig(array $config): ?string
    {
        $publicKey = trim((string) ($config['public_key'] ?? ''));
        $txRef = trim((string) ($config['tx_ref'] ?? ''));
        $amount = (float) ($config['amount'] ?? 0);
        $callbackUrl = trim((string) ($config['callback_url'] ?? ''));
        $returnUrl = trim((string) ($config['return_url'] ?? ''));
        $customizations = $config['customizations'] ?? [];
        $title = trim((string) ($customizations['title'] ?? ''));
        $description = trim((string) ($customizations['description'] ?? ''));
        $logoUrl = trim((string) ($customizations['logo'] ?? ''));
        $environment = strtolower(trim((string) env('CI_ENVIRONMENT', ENVIRONMENT)));
        $allowLocalUrls = in_array($environment, ['development', 'testing'], true);

        if ($publicKey === '') {
            return 'PayChangu public key is missing in the environment configuration.';
        }

        if (!preg_match('/^pub-(live|test)-/i', $publicKey)) {
            return 'PayChangu public key format looks invalid.';
        }

        if ($txRef === '') {
            return 'Payment reference is missing for this past paper checkout.';
        }

        if ($amount <= 0) {
            return 'Paid past paper amount is invalid. Please confirm the paper price in admin.';
        }

        if ($title === '' || $description === '') {
            return 'PayChangu customization details are incomplete.';
        }

        foreach ([
            'callback URL' => $callbackUrl,
            'return URL' => $returnUrl,
            'logo URL' => $logoUrl,
        ] as $label => $url) {
            if ($url === '') {
                return 'PayChangu ' . strtolower($label) . ' is missing.';
            }

            $host = strtolower((string) parse_url($url, PHP_URL_HOST));
            $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));

            if (in_array($host, ['', 'localhost', '127.0.0.1'], true)) {
                if ($allowLocalUrls) {
                    continue;
                }

                return 'PayChangu ' . strtolower($label) . ' is pointing to localhost instead of the live site.';
            }

            if ($scheme !== 'https' && !in_array($host, ['localhost', '127.0.0.1'], true)) {
                return 'PayChangu ' . strtolower($label) . ' must use HTTPS on the live server.';
            }
        }

        return null;
    }

    private function attemptFinalizePastPaperPayment(
        array $purchase,
        ?string $webhookStatus = null,
        bool $trustRedirect = false,
        bool $markFailure = false
    ): array {
        if ($this->isVerifiedPastPaperPurchase($purchase)) {
            return $purchase;
        }

        $paychangu = \Config\Services::paychangu();
        $apiResult = $paychangu->verifyPayment((string) $purchase['tx_ref']);

        $webhookStatus = strtolower(trim((string) $webhookStatus));
        $webhookSuccess = $webhookStatus === 'success';
        $apiSuccess = $paychangu->isSuccessfulVerification(
            $apiResult,
            (string) ($purchase['tx_ref'] ?? ''),
            (string) ($purchase['currency'] ?? 'MWK'),
            isset($purchase['amount']) ? (float) $purchase['amount'] : null
        );
        $fallbackSuccess = $this->isPayChanguTestMode() && $apiResult === null && !empty($purchase['tx_ref']) && $webhookStatus !== 'failed';
        $redirectSuccess = $trustRedirect && !empty($purchase['tx_ref']) && $webhookStatus !== 'failed';

        if ($webhookSuccess || $apiSuccess || $fallbackSuccess || $redirectSuccess) {
            $this->pastPaperPurchaseModel->update($purchase['id'], [
                'payment_status' => 'verified',
                'paid_at' => $purchase['paid_at'] ?: date('Y-m-d H:i:s'),
                'download_granted_at' => $purchase['download_granted_at'] ?: date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            return $this->pastPaperPurchaseModel->find($purchase['id']) ?? $purchase;
        }

        if ($markFailure && $webhookStatus === 'failed') {
            $this->pastPaperPurchaseModel->update($purchase['id'], [
                'payment_status' => 'failed',
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            return $this->pastPaperPurchaseModel->find($purchase['id']) ?? $purchase;
        }

        return $purchase;
    }

    private function isVerifiedPastPaperPurchase(array $purchase): bool
    {
        return ($purchase['payment_status'] ?? '') === 'verified';
    }

    private function buildPastPaperDownloadUrl(int $paperId, string $txRef = ''): string
    {
        $url = base_url('resources/past-papers/download/' . $paperId);

        if ($txRef !== '') {
            $url .= '?tx_ref=' . rawurlencode($txRef);
        }

        return $url;
    }

    private function buildPastPaperSuccessUrl(string $txRef): string
    {
        return base_url('resources/past-papers/payment/success?tx_ref=' . rawurlencode($txRef));
    }

    private function buildPublicFacingUrl(string $path): string
    {
        $path = ltrim($path, '/');
        $payChanguPublicBaseUrl = rtrim((string) env('PAYCHANGU_PUBLIC_BASE_URL', ''), '/');
        if ($payChanguPublicBaseUrl !== '') {
            $overrideHost = strtolower((string) parse_url($payChanguPublicBaseUrl, PHP_URL_HOST));
            $overrideScheme = strtolower((string) parse_url($payChanguPublicBaseUrl, PHP_URL_SCHEME));

            if ($overrideHost !== '' && in_array($overrideScheme, ['http', 'https'], true)) {
                return $payChanguPublicBaseUrl . '/' . $path;
            }
        }

        $envBaseUrl = rtrim((string) env('app.baseURL', ''), '/');
        if ($envBaseUrl !== '') {
            $envHost = strtolower((string) parse_url($envBaseUrl, PHP_URL_HOST));
            $envScheme = strtolower((string) parse_url($envBaseUrl, PHP_URL_SCHEME));

            if (!in_array($envHost, ['', 'localhost', '127.0.0.1'], true) && in_array($envScheme, ['http', 'https'], true)) {
                return $envBaseUrl . '/' . $path;
            }
        }

        $appConfig = config(\Config\App::class);
        $configBaseUrl = rtrim((string) ($appConfig->baseURL ?? ''), '/');
        if ($configBaseUrl !== '') {
            $configHost = strtolower((string) parse_url($configBaseUrl, PHP_URL_HOST));
            $configScheme = strtolower((string) parse_url($configBaseUrl, PHP_URL_SCHEME));

            if (!in_array($configHost, ['', 'localhost', '127.0.0.1'], true) && in_array($configScheme, ['http', 'https'], true)) {
                return $configBaseUrl . '/' . $path;
            }
        }

        $configuredUrl = site_url($path);
        $configuredHost = strtolower((string) parse_url($configuredUrl, PHP_URL_HOST));

        if (!in_array($configuredHost, ['', 'localhost', '127.0.0.1'], true)) {
            return $configuredUrl;
        }

        $currentHost = trim((string) (
            $this->request->getHeaderLine('X-Forwarded-Host')
            ?: $this->request->getServer('HTTP_X_FORWARDED_HOST')
            ?: $this->request->getServer('HTTP_HOST')
            ?: $this->request->getHeaderLine('Host')
        ));
        $currentHost = trim($currentHost);

        if (str_contains($currentHost, ',')) {
            $currentHost = trim((string) explode(',', $currentHost)[0]);
        }

        if ($currentHost === '' || in_array(strtolower($currentHost), ['localhost', '127.0.0.1'], true)) {
            return $configuredUrl;
        }

        $forwardedProto = strtolower(trim((string) $this->request->getHeaderLine('X-Forwarded-Proto')));
        $scheme = in_array($forwardedProto, ['http', 'https'], true)
            ? $forwardedProto
            : ($this->request->isSecure() ? 'https' : 'https');

        return rtrim($scheme . '://' . $currentHost, '/') . '/' . $path;
    }

    private function splitFullName(string $fullName): array
    {
        $parts = preg_split('/\s+/', trim($fullName)) ?: [];
        $firstName = $parts[0] ?? 'TutorConnect';
        $lastName = count($parts) > 1 ? implode(' ', array_slice($parts, 1)) : 'User';

        return [$firstName, $lastName];
    }

    private function getPayChanguPublicKey(): string
    {
        return trim((string) getenv('PAYCHANGU_PUBLIC_KEY'));
    }

    private function getPayChanguSecretKey(): string
    {
        return trim((string) getenv('PAYCHANGU_SECRET_KEY'));
    }

    private function isPayChanguTestMode(): bool
    {
        return stripos($this->getPayChanguPublicKey(), 'PUB-TEST-') === 0;
    }

    private function normalizeEmail(string $email): ?string
    {
        $normalized = strtolower(trim($email));
        return $normalized !== '' ? $normalized : null;
    }

    private function normalizeMoneyAmount($amount): float
    {
        if (is_string($amount)) {
            $amount = preg_replace('/[^0-9.]/', '', $amount);
        }

        return round((float) $amount, 2);
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
