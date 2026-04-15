<?php

namespace App\Controllers;

use App\Libraries\ParentRequestMatcher;
use App\Models\CurriculumSubjectsModel;
use App\Models\ParentRequestApplicationModel;
use App\Models\ParentRequestModel;
use App\Models\SiteSettingModel;
use App\Models\User;

class ParentRequests extends BaseController
{
    private const BUDGET_OPTIONS = [
        '5000-10000-week' => [
            'min' => 5000,
            'max' => 10000,
            'period' => 'week',
            'label' => 'MWK 5,000 - 10,000 per week',
        ],
        '10000-20000-week' => [
            'min' => 10000,
            'max' => 20000,
            'period' => 'week',
            'label' => 'MWK 10,000 - 20,000 per week',
        ],
        '20000-50000-month' => [
            'min' => 20000,
            'max' => 50000,
            'period' => 'month',
            'label' => 'MWK 20,000 - 50,000 per month',
        ],
        '50000-100000-month' => [
            'min' => 50000,
            'max' => 100000,
            'period' => 'month',
            'label' => 'MWK 50,000 - 100,000 per month',
        ],
        '100000-200000-month' => [
            'min' => 100000,
            'max' => 200000,
            'period' => 'month',
            'label' => 'MWK 100,000 - 200,000 per month',
        ],
    ];

    public function index()
    {
        $data = $this->getFormData();
        $data['title'] = 'Request a Teacher - TutorConnect Malawi';
        $data['description'] = 'Submit a parent request and reach matching verified teachers on TutorConnect Malawi.';

        return view('parent_requests/form', $data);
    }

    public function store()
    {
        if (strtolower($this->request->getMethod()) !== 'post') {
            return redirect()->to(site_url('request-teacher'));
        }

        $postData = $this->request->getPost();
        $subjects = $this->normalizeSubjects($postData['subjects'] ?? []);
        $budgetKey = (string) ($postData['budget_range'] ?? '');
        $budget = self::BUDGET_OPTIONS[$budgetKey] ?? null;

        $validation = \Config\Services::validation();
        $validation->setRules([
            'curriculum' => 'required|max_length[50]',
            'grade_class' => 'required|max_length[100]',
            'district' => 'required|max_length[50]',
            'specific_location' => 'required|max_length[255]',
            'mode' => 'required|in_list[online,physical]',
            'budget_range' => 'required',
            'notes' => 'permit_empty|max_length[2000]',
            'parent_phone' => 'required|min_length[8]|max_length[30]',
            'parent_email' => 'required|valid_email|max_length[150]',
        ]);

        if (!$validation->run($postData)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        $errors = [];

        if (empty($subjects)) {
            $errors['subjects'] = 'Select at least one subject.';
        }

        if (!$budget) {
            $errors['budget_range'] = 'Select a valid budget range.';
        }

        if (!$this->subjectsBelongToSelection((string) $postData['curriculum'], (string) $postData['grade_class'], $subjects)) {
            $errors['subjects'] = 'Select subjects that match the chosen curriculum and grade/class.';
        }

        if (!empty($errors)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $errors);
        }

        $requestModel = new ParentRequestModel();
        $referenceCode = $this->generateReferenceCode($requestModel);

        $requestData = [
            'reference_code' => $referenceCode,
            'curriculum' => trim((string) $postData['curriculum']),
            'grade_class' => trim((string) $postData['grade_class']),
            'subjects_json' => json_encode(array_values($subjects)),
            'district' => trim((string) $postData['district']),
            'specific_location' => trim((string) $postData['specific_location']),
            'mode' => trim((string) $postData['mode']),
            'budget_min' => $budget['min'],
            'budget_max' => $budget['max'],
            'budget_period' => $budget['period'],
            'notes' => trim((string) ($postData['notes'] ?? '')),
            'parent_phone' => trim((string) $postData['parent_phone']),
            'parent_email' => strtolower(trim((string) $postData['parent_email'])),
            'status' => 'open',
            'matched_tutor_count' => 0,
            'emailed_tutor_count' => 0,
        ];

        try {
            $requestId = $requestModel->insert($requestData);

            if (!$requestId) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'We could not save your request. Please try again.');
            }

            $requestData['id'] = (int) $requestId;

            $qualifiedTutors = $this->findQualifiedTutors($requestData);
            $emailedCount = $this->broadcastRequestToTutors($requestData, $qualifiedTutors);

            $requestModel->update($requestId, [
                'matched_tutor_count' => count($qualifiedTutors),
                'emailed_tutor_count' => $emailedCount,
            ]);

            $this->notifyAdminOfRequest($requestData, count($qualifiedTutors), $emailedCount);

            return redirect()->to(site_url('request-teacher/success/' . $referenceCode));
        } catch (\Throwable $e) {
            log_message('error', 'Parent request submission failed: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Something went wrong while submitting your request. Please try again.');
        }
    }

    public function success(string $referenceCode)
    {
        $request = (new ParentRequestModel())->findByReference($referenceCode);

        if (!$request) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $request['subjects'] = $this->decodeSubjects($request['subjects_json'] ?? '[]');

        return view('parent_requests/success', [
            'title' => 'Request Submitted - TutorConnect Malawi',
            'request' => $request,
            'budgetLabel' => $this->formatBudget($request),
            'modeLabel' => $this->formatMode((string) $request['mode']),
        ]);
    }

    public function apply(string $referenceCode)
    {
        $request = (new ParentRequestModel())->findByReference($referenceCode);
        $tutorId = (int) $this->request->getGet('tutor');
        $token = (string) $this->request->getGet('token');

        if (!$request || $tutorId <= 0 || !$this->isValidApplyToken((int) $request['id'], $tutorId, $token)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $request['subjects'] = $this->decodeSubjects($request['subjects_json'] ?? '[]');

        if (($request['status'] ?? 'open') !== 'open') {
            return view('parent_requests/apply_success', [
                'title' => 'Request Closed - TutorConnect Malawi',
                'request' => $request,
                'tutor' => null,
                'budgetLabel' => $this->formatBudget($request),
                'modeLabel' => $this->formatMode((string) $request['mode']),
                'alreadyApplied' => false,
                'closed' => true,
            ]);
        }

        $tutor = (new User())->find($tutorId);

        if (!$tutor || !$this->isTutorQualifiedForRequest($request, $tutor)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $applicationModel = new ParentRequestApplicationModel();
        $existing = $applicationModel->findExisting((int) $request['id'], $tutorId);
        $alreadyApplied = (bool) $existing;

        if (!$existing) {
            $applicationModel->insert([
                'parent_request_id' => (int) $request['id'],
                'tutor_id' => $tutorId,
                'tutor_email' => $tutor['email'],
                'status' => 'applied',
                'applied_at' => date('Y-m-d H:i:s'),
            ]);

            $this->notifyParentOfTutorApplication($request, $tutor);
        }

        return view('parent_requests/apply_success', [
            'title' => 'Application Sent - TutorConnect Malawi',
            'request' => $request,
            'tutor' => $tutor,
            'budgetLabel' => $this->formatBudget($request),
            'modeLabel' => $this->formatMode((string) $request['mode']),
            'alreadyApplied' => $alreadyApplied,
            'closed' => false,
        ]);
    }

    private function getFormData(): array
    {
        $curriculumSubjectsModel = new CurriculumSubjectsModel();
        $curriculumSubjects = [];
        $curricula = [];

        try {
            $curriculumSubjects = $curriculumSubjectsModel->getSubjectNamesGrouped();
            $curricula = array_keys($curriculumSubjects);
            sort($curricula);
        } catch (\Throwable $e) {
            log_message('error', 'Failed loading curriculum subjects for parent request form: ' . $e->getMessage());
        }

        return [
            'curricula' => $curricula,
            'curriculumSubjects' => $curriculumSubjects,
            'districts' => $this->getDistrictOptions(),
            'budgetOptions' => self::BUDGET_OPTIONS,
        ];
    }

    private function getDistrictOptions(): array
    {
        $districts = [
            'Balaka', 'Blantyre', 'Chikwawa', 'Chiradzulu', 'Chitipa', 'Dedza', 'Dowa', 'Karonga',
            'Kasungu', 'Likoma', 'Lilongwe', 'Machinga', 'Mangochi', 'Mchinji', 'Mulanje', 'Mwanza',
            'Mzimba', 'Neno', 'Nkhata Bay', 'Nkhotakota', 'Nsanje', 'Ntcheu', 'Ntchisi', 'Phalombe',
            'Rumphi', 'Salima', 'Thyolo', 'Zomba',
        ];

        try {
            $rows = \Config\Database::connect()
                ->table('users')
                ->select('district')
                ->where('role', 'trainer')
                ->where('is_active', 1)
                ->where('deleted_at', null)
                ->whereIn('tutor_status', ['approved', 'active'])
                ->where('district !=', '')
                ->groupBy('district')
                ->get()
                ->getResultArray();

            $districts = array_unique(array_merge($districts, array_column($rows, 'district')));
            sort($districts);
        } catch (\Throwable $e) {
            log_message('error', 'Failed loading tutor districts for parent request form: ' . $e->getMessage());
        }

        return $districts;
    }

    private function normalizeSubjects($subjects): array
    {
        if (!is_array($subjects)) {
            $subjects = [$subjects];
        }

        $subjects = array_map(static fn ($subject) => trim((string) $subject), $subjects);
        $subjects = array_filter($subjects, static fn ($subject) => $subject !== '');

        return array_values(array_unique($subjects));
    }

    private function subjectsBelongToSelection(string $curriculum, string $gradeClass, array $subjects): bool
    {
        $curriculumSubjects = (new CurriculumSubjectsModel())->getSubjectNamesGrouped();
        $availableSubjects = $curriculumSubjects[$curriculum][$gradeClass] ?? [];

        if (empty($availableSubjects)) {
            return false;
        }

        $availableLookup = array_map([$this, 'normalizeComparable'], $availableSubjects);

        foreach ($subjects as $subject) {
            if (!in_array($this->normalizeComparable($subject), $availableLookup, true)) {
                return false;
            }
        }

        return true;
    }

    private function generateReferenceCode(ParentRequestModel $requestModel): string
    {
        do {
            $referenceCode = 'REQ-' . date('ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
        } while ($requestModel->where('reference_code', $referenceCode)->first());

        return $referenceCode;
    }

    private function findQualifiedTutors(array $request): array
    {
        return (new ParentRequestMatcher())->findQualifiedTutors($request);
    }

    private function isTutorQualifiedForRequest(array $request, array $tutor): bool
    {
        return (new ParentRequestMatcher())->isTutorQualifiedForRequest($request, $tutor);
    }

    private function broadcastRequestToTutors(array $request, array $tutors): int
    {
        if (empty($tutors)) {
            return 0;
        }

        $sentCount = 0;
        $emailConfig = config('Email');

        foreach ($tutors as $tutor) {
            try {
                $email = \Config\Services::email();
                $email->setFrom($emailConfig->fromEmail, $emailConfig->fromName);
                $email->setTo($tutor['email']);
                $email->setSubject('New matching parent request - ' . $request['reference_code']);
                $email->setMessage($this->buildTutorRequestEmailHtml($request, $tutor));
                $email->setAltMessage($this->buildTutorRequestEmailText($request, $tutor));

                if ($email->send(false)) {
                    $sentCount++;
                } else {
                    log_message('error', 'Parent request email failed for tutor ' . $tutor['id'] . ': ' . trim(strip_tags($email->printDebugger(['headers', 'subject']))));
                }
            } catch (\Throwable $e) {
                log_message('error', 'Parent request email exception for tutor ' . ($tutor['id'] ?? 'unknown') . ': ' . $e->getMessage());
            }
        }

        return $sentCount;
    }

    private function buildTutorRequestEmailHtml(array $request, array $tutor): string
    {
        $applyUrl = $this->buildApplyUrl((int) $request['id'], (int) $tutor['id'], (string) $request['reference_code']);
        $subjects = implode(', ', $this->decodeSubjects($request['subjects_json'] ?? '[]'));
        $notes = trim((string) ($request['notes'] ?? ''));
        $companyName = $this->companyName();

        return '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Parent Request</title>
</head>
<body style="margin:0;padding:0;background:#f6f7f9;font-family:Arial,sans-serif;color:#1f2937;">
    <div style="max-width:640px;margin:0 auto;background:#ffffff;">
        <div style="background:#E55C0D;color:#ffffff;padding:24px;">
            <h1 style="margin:0;font-size:24px;">New matching parent request</h1>
            <p style="margin:8px 0 0;">' . esc($companyName) . '</p>
        </div>
        <div style="padding:24px;">
            <p>Hello ' . esc($tutor['first_name'] ?? 'Teacher') . ',</p>
            <p>A parent has submitted a request that matches your TutorConnect Malawi profile.</p>
            <table style="width:100%;border-collapse:collapse;margin:18px 0;">
                <tr><td style="padding:8px;border-bottom:1px solid #e5e7eb;font-weight:bold;">Reference</td><td style="padding:8px;border-bottom:1px solid #e5e7eb;">' . esc($request['reference_code']) . '</td></tr>
                <tr><td style="padding:8px;border-bottom:1px solid #e5e7eb;font-weight:bold;">Curriculum</td><td style="padding:8px;border-bottom:1px solid #e5e7eb;">' . esc($request['curriculum']) . '</td></tr>
                <tr><td style="padding:8px;border-bottom:1px solid #e5e7eb;font-weight:bold;">Grade / Class</td><td style="padding:8px;border-bottom:1px solid #e5e7eb;">' . esc($request['grade_class']) . '</td></tr>
                <tr><td style="padding:8px;border-bottom:1px solid #e5e7eb;font-weight:bold;">Subject(s)</td><td style="padding:8px;border-bottom:1px solid #e5e7eb;">' . esc($subjects) . '</td></tr>
                <tr><td style="padding:8px;border-bottom:1px solid #e5e7eb;font-weight:bold;">Location</td><td style="padding:8px;border-bottom:1px solid #e5e7eb;">' . esc($request['district'] . ', ' . $request['specific_location']) . '</td></tr>
                <tr><td style="padding:8px;border-bottom:1px solid #e5e7eb;font-weight:bold;">Mode</td><td style="padding:8px;border-bottom:1px solid #e5e7eb;">' . esc($this->formatMode((string) $request['mode'])) . '</td></tr>
                <tr><td style="padding:8px;border-bottom:1px solid #e5e7eb;font-weight:bold;">Budget</td><td style="padding:8px;border-bottom:1px solid #e5e7eb;">' . esc($this->formatBudget($request)) . '</td></tr>
            </table>
            ' . ($notes !== '' ? '<div style="background:#fff7ed;border:1px solid #fed7aa;padding:14px;margin:18px 0;"><strong>Notes:</strong><br>' . nl2br(esc($notes)) . '</div>' : '') . '
            <p>If this is a good fit, click below. The parent will receive your TutorConnect profile and registered contact details.</p>
            <p style="margin:28px 0;"><a href="' . esc($applyUrl) . '" style="background:#E55C0D;color:#ffffff;text-decoration:none;padding:12px 18px;border-radius:6px;font-weight:bold;">Apply for this request</a></p>
            <p style="font-size:13px;color:#6b7280;">You received this because your active paid subscription, subjects, and teaching preferences match this request.</p>
        </div>
    </div>
</body>
</html>';
    }

    private function buildTutorRequestEmailText(array $request, array $tutor): string
    {
        $applyUrl = $this->buildApplyUrl((int) $request['id'], (int) $tutor['id'], (string) $request['reference_code']);
        $subjects = implode(', ', $this->decodeSubjects($request['subjects_json'] ?? '[]'));

        return "New matching parent request\n\n"
            . "Reference: {$request['reference_code']}\n"
            . "Curriculum: {$request['curriculum']}\n"
            . "Grade / Class: {$request['grade_class']}\n"
            . "Subject(s): {$subjects}\n"
            . "Location: {$request['district']}, {$request['specific_location']}\n"
            . "Mode: " . $this->formatMode((string) $request['mode']) . "\n"
            . "Budget: " . $this->formatBudget($request) . "\n"
            . "Notes: " . trim((string) ($request['notes'] ?? '')) . "\n\n"
            . "Apply for this request: {$applyUrl}\n\n"
            . "You received this because your active paid subscription, subjects, and teaching preferences match this request.";
    }

    private function notifyParentOfTutorApplication(array $request, array $tutor): void
    {
        try {
            $emailConfig = config('Email');
            $email = \Config\Services::email();
            $companyName = $this->companyName();
            $profileUrl = site_url('tutor/' . (!empty($tutor['username']) ? $tutor['username'] : $tutor['id']));

            $email->setFrom($emailConfig->fromEmail, $emailConfig->fromName);
            $email->setReplyTo($tutor['email'], trim(($tutor['first_name'] ?? '') . ' ' . ($tutor['last_name'] ?? '')));
            $email->setTo($request['parent_email']);
            $email->setSubject('A teacher applied for your request - ' . $request['reference_code']);

            $html = '<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Teacher Application</title></head>
<body style="margin:0;padding:0;background:#f6f7f9;font-family:Arial,sans-serif;color:#1f2937;">
    <div style="max-width:640px;margin:0 auto;background:#ffffff;">
        <div style="background:#E55C0D;color:#ffffff;padding:24px;">
            <h1 style="margin:0;font-size:24px;">A teacher applied</h1>
            <p style="margin:8px 0 0;">' . esc($companyName) . '</p>
        </div>
        <div style="padding:24px;">
            <p>A verified teacher has applied for your request <strong>' . esc($request['reference_code']) . '</strong>.</p>
            <div style="background:#f9fafb;border:1px solid #e5e7eb;padding:16px;margin:18px 0;">
                <p><strong>Name:</strong> ' . esc(trim(($tutor['first_name'] ?? '') . ' ' . ($tutor['last_name'] ?? ''))) . '</p>
                <p><strong>Email:</strong> ' . esc($tutor['email']) . '</p>
                <p><strong>Phone:</strong> ' . esc($tutor['phone'] ?? 'Not provided') . '</p>
                <p><strong>WhatsApp:</strong> ' . esc($tutor['whatsapp_number'] ?? 'Not provided') . '</p>
                <p><strong>Location:</strong> ' . esc(trim(($tutor['district'] ?? '') . ', ' . ($tutor['location'] ?? ''), ', ')) . '</p>
            </div>
            <p style="margin:24px 0;"><a href="' . esc($profileUrl) . '" style="background:#E55C0D;color:#ffffff;text-decoration:none;padding:12px 18px;border-radius:6px;font-weight:bold;">View teacher profile</a></p>
        </div>
    </div>
</body>
</html>';

            $text = "A verified teacher applied for your request {$request['reference_code']}.\n\n"
                . 'Name: ' . trim(($tutor['first_name'] ?? '') . ' ' . ($tutor['last_name'] ?? '')) . "\n"
                . 'Email: ' . ($tutor['email'] ?? '') . "\n"
                . 'Phone: ' . ($tutor['phone'] ?? 'Not provided') . "\n"
                . 'WhatsApp: ' . ($tutor['whatsapp_number'] ?? 'Not provided') . "\n"
                . 'Profile: ' . $profileUrl . "\n";

            $email->setMessage($html);
            $email->setAltMessage($text);
            $email->send(false);
        } catch (\Throwable $e) {
            log_message('error', 'Failed notifying parent of tutor application: ' . $e->getMessage());
        }
    }

    private function notifyAdminOfRequest(array $request, int $matchedCount, int $emailedCount): void
    {
        try {
            $siteSettings = new SiteSettingModel();
            $adminEmail = getenv('ADMIN_EMAIL') ?: $siteSettings->getValue('contact_email', 'info@tutorconnectmw.com');

            if (!$adminEmail) {
                return;
            }

            $emailConfig = config('Email');
            $email = \Config\Services::email();
            $subjects = implode(', ', $this->decodeSubjects($request['subjects_json'] ?? '[]'));

            $email->setFrom($emailConfig->fromEmail, $emailConfig->fromName);
            $email->setTo($adminEmail);
            $email->setSubject('New parent request - ' . $request['reference_code']);
            $email->setMessage(
                '<p>A new parent request was submitted.</p>'
                . '<p><strong>Reference:</strong> ' . esc($request['reference_code']) . '</p>'
                . '<p><strong>Curriculum:</strong> ' . esc($request['curriculum']) . '</p>'
                . '<p><strong>Grade / Class:</strong> ' . esc($request['grade_class']) . '</p>'
                . '<p><strong>Subject(s):</strong> ' . esc($subjects) . '</p>'
                . '<p><strong>Location:</strong> ' . esc($request['district'] . ', ' . $request['specific_location']) . '</p>'
                . '<p><strong>Mode:</strong> ' . esc($this->formatMode((string) $request['mode'])) . '</p>'
                . '<p><strong>Budget:</strong> ' . esc($this->formatBudget($request)) . '</p>'
                . '<p><strong>Parent contact:</strong> ' . esc($request['parent_phone'] . ' / ' . $request['parent_email']) . '</p>'
                . '<p><strong>Matched tutors:</strong> ' . $matchedCount . '<br><strong>Email sent:</strong> ' . $emailedCount . '</p>'
            );
            $email->setAltMessage(
                "New parent request {$request['reference_code']}\n"
                . "Subject(s): {$subjects}\n"
                . "Location: {$request['district']}, {$request['specific_location']}\n"
                . 'Budget: ' . $this->formatBudget($request) . "\n"
                . "Parent contact: {$request['parent_phone']} / {$request['parent_email']}\n"
                . "Matched tutors: {$matchedCount}\nEmail sent: {$emailedCount}"
            );
            $email->send(false);
        } catch (\Throwable $e) {
            log_message('error', 'Failed notifying admin of parent request: ' . $e->getMessage());
        }
    }

    private function buildApplyUrl(int $requestId, int $tutorId, string $referenceCode): string
    {
        return site_url('parent-requests/apply/' . rawurlencode($referenceCode))
            . '?tutor=' . $tutorId
            . '&token=' . $this->makeApplyToken($requestId, $tutorId);
    }

    private function makeApplyToken(int $requestId, int $tutorId): string
    {
        return hash_hmac('sha256', $requestId . '|' . $tutorId, $this->tokenSecret());
    }

    private function isValidApplyToken(int $requestId, int $tutorId, string $token): bool
    {
        return hash_equals($this->makeApplyToken($requestId, $tutorId), $token);
    }

    private function tokenSecret(): string
    {
        $key = (string) (config('Encryption')->key ?? '');

        return $key !== '' ? $key : (string) (getenv('app.baseURL') ?: FCPATH);
    }

    private function decodeSubjects(string $subjectsJson): array
    {
        $subjects = json_decode($subjectsJson, true);

        return is_array($subjects) ? array_values(array_filter($subjects)) : [];
    }

    private function formatBudget(array $request): string
    {
        return 'MWK ' . number_format((float) $request['budget_min'], 0)
            . ' - ' . number_format((float) $request['budget_max'], 0)
            . ' per ' . $request['budget_period'];
    }

    private function formatMode(string $mode): string
    {
        return $mode === 'physical' ? 'Physical' : 'Online';
    }

    private function normalizeComparable(string $value): string
    {
        return strtolower(trim(preg_replace('/\s+/', ' ', $value) ?? ''));
    }

    private function companyName(): string
    {
        try {
            return (new SiteSettingModel())->getValue('company_name', 'TutorConnect Malawi');
        } catch (\Throwable $e) {
            return 'TutorConnect Malawi';
        }
    }
}
