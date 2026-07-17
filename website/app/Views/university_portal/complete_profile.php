<?php
$errors = session('errors') ?? [];
$wizardReturnStep = session('wizard_return_step');
$wizardErrorStep = session('wizard_error_step');
$showIntro = service('request')->getGet('intro') === '1';

$normalizeList = static function ($value): array {
    if (is_array($value)) {
        return array_values(array_filter(array_map(static fn ($item) => trim((string) $item), $value), static fn ($item) => $item !== ''));
    }

    $stringValue = trim((string) $value);
    if ($stringValue === '') {
        return [];
    }

    return [$stringValue];
};

$fieldError = static function (string $field) use ($errors): string {
    $message = $errors[$field] ?? '';

    if (is_array($message)) {
        $message = reset($message);
    }

    return trim((string) $message);
};

$selectedServiceAreas = $normalizeList(old('service_areas') ?? ($selected_service_areas ?? []));
$selectedDays = $normalizeList(old('available_days') ?? ($selected_days ?? []));
$selectedPreferredTimes = $normalizeList(old('preferred_times') ?? ($selected_preferred_times ?? []));
$workStatus = old('work_status', $profile['work_status'] ?? '');
$certificationFiles = $normalizeList($certification_files ?? []);
$isFirm = ($profile['account_type'] ?? 'individual') === 'firm';
$profileImageLabel = $isFirm ? 'Company Logo' : 'Profile Picture';
$profileImageHint = $isFirm ? 'Upload a clear JPG or PNG company logo.' : 'Upload a clear JPG or PNG profile image.';
$profileImageCurrentLabel = $isFirm ? 'Current company logo on record' : 'Current profile picture on record';
$identityDocumentLabel = $isFirm ? 'Business Registration Certificate' : 'National ID';
$identityDocumentHint = $isFirm ? 'Upload the business registration certificate as an image or PDF copy.' : 'Upload a scan or image file, or a PDF copy.';
$identityDocumentCurrentLabel = $isFirm ? 'Current business certificate on record' : 'Current national ID file on record';
$certificationLabel = $isFirm ? 'Business / Professional Certificates' : 'Academic Certifications / Transcript';
$certificationCurrentLabel = $isFirm ? 'Current business certificate file' : 'Current certification file';
$yearLabel = $isFirm ? 'Year Established / Registered' : 'Year of Study or Graduation';
$bioLabel = $isFirm ? 'Company Profile' : 'Short Competency Profile / Bio';
$institutionsLabel = $isFirm ? 'Institutional / Business Background' : 'Institutions';

$fullName = trim((string) ($profile['full_name'] ?? ''));
if ($fullName === '') {
    $fullName = trim((string) (($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')));
}
if ($fullName === '') {
    $fullName = (string) ($user['username'] ?? ($isFirm ? 'University Firm' : 'University Tutor'));
}

$initial = strtoupper(substr($fullName, 0, 1));
$status = (string) ($profile['status'] ?? 'draft');
$statusMap = [
    'approved' => ['class' => 'status-success', 'label' => 'Approved'],
    'pending_review' => ['class' => 'status-warning', 'label' => 'Pending Review'],
    'rejected' => ['class' => 'status-danger', 'label' => 'Updates Required'],
    'draft' => ['class' => 'status-info', 'label' => 'Draft'],
];
$statusConfig = $statusMap[$status] ?? $statusMap['draft'];

$stepFieldMap = [
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

$normalizeStep = static function ($step, ?int $default = null): ?int {
    if (!is_numeric($step)) {
        return $default;
    }

    $step = (int) $step;

    if ($step < 1 || $step > 5) {
        return $default;
    }

    return $step;
};

$stepTitles = [
    1 => $isFirm ? 'Firm Profile' : 'Academic Profile',
    2 => 'Institutions and Rates',
    3 => 'Service Areas and Availability',
    4 => 'Verification Documents',
    5 => 'Review and Submit',
];

$errorStepSummaries = [];
foreach ($errors as $field => $message) {
    $message = is_array($message) ? reset($message) : $message;
    $step = $stepFieldMap[$field] ?? $normalizeStep($wizardErrorStep, 5);
    $errorStepSummaries[$step][] = trim((string) $message);
}
ksort($errorStepSummaries);

$initialStep = $normalizeStep($wizardReturnStep);
if ($initialStep === null) {
    foreach (array_keys($errors) as $field) {
        if (!isset($stepFieldMap[$field])) {
            continue;
        }

        $initialStep = $initialStep === null
            ? $stepFieldMap[$field]
            : min($initialStep, $stepFieldMap[$field]);
    }
}

$initialStep = $initialStep ?? 1;

$hasSavedProgress = trim((string) ($profile['year_of_study_or_graduation'] ?? '')) !== ''
    || trim((string) ($profile['bio'] ?? '')) !== ''
    || trim((string) ($profile['teaching_mode'] ?? '')) !== ''
    || trim((string) ($profile['city_location'] ?? '')) !== ''
    || $selectedServiceAreas !== []
    || $selectedDays !== []
    || $selectedPreferredTimes !== []
    || trim((string) ($profile['profile_picture'] ?? '')) !== ''
    || trim((string) ($profile['national_id_file'] ?? '')) !== ''
    || $certificationFiles !== [];

$shouldAutoStart = !$showIntro && (!empty($errors) || !empty(session('error')) || $hasSavedProgress);

$serviceCount = count($selectedServiceAreas);
$availabilityCount = count($selectedDays);
$institutionCount = count($normalizeList(explode(PHP_EOL, (string) old('institutions', $institutions_text ?? ''))));
$profilePicturePath = trim((string) ($profile['profile_picture'] ?? ''));
$nationalIdPath = trim((string) ($profile['national_id_file'] ?? ''));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Complete University Profile') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #e55c0d;
            --secondary-color: #c94609;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --accent-color: #0ea5e9;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --bg-primary: #f8fafc;
            --bg-secondary: #ffffff;
            --bg-accent: #f0f9ff;
            --border-color: rgba(15, 23, 42, 0.08);
            --shadow: 0 1px 3px rgba(15, 23, 42, 0.08), 0 1px 2px rgba(15, 23, 42, 0.04);
            --shadow-lg: 0 14px 30px rgba(15, 23, 42, 0.08);
            --border-radius: 16px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            background: var(--bg-primary);
            color: var(--text-dark);
            font-family: -apple-system, BlinkMacSystemFont, "SF Pro Display", "Segoe UI", Roboto, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .app-container {
            width: 100%;
            max-width: 480px;
            min-height: 100vh;
            margin: 0 auto;
            background: var(--bg-primary);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
        }

        @media (min-width: 768px) {
            .app-container {
                max-width: 100%;
                box-shadow: none;
            }
        }

        .status-bar {
            height: 44px;
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        @media (min-width: 768px) {
            .status-bar {
                display: none;
            }
        }

        .navbar {
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border-color);
            padding: 0;
            height: 68px;
            position: sticky;
            top: 44px;
            z-index: 100;
        }

        @media (min-width: 768px) {
            .navbar {
                top: 0;
                height: 80px;
            }
        }

        .screen {
            padding: 20px;
            padding-bottom: 112px;
            max-width: 1160px;
            margin: 0 auto;
        }

        @media (min-width: 768px) {
            .screen {
                padding: 32px 40px 132px;
            }
        }

        .icon-button {
            width: 40px;
            height: 40px;
            border: 0;
            border-radius: 12px;
            background: transparent;
            color: var(--text-dark);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s ease;
        }

        .icon-button:hover {
            background: var(--bg-primary);
        }

        .avatar {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1rem;
        }

        .step-content,
        .progress-container {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
        }

        .progress-container {
            padding: 20px;
            margin-bottom: 18px;
        }

        .step-content {
            padding: 22px;
            margin-bottom: 18px;
        }

        @media (min-width: 768px) {
            .progress-container,
            .step-content {
                padding: 28px;
            }
        }

        .step-title {
            margin: 0 0 8px;
            font-size: 1.45rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        .step-description {
            margin: 0 0 22px;
            color: var(--text-light);
            line-height: 1.6;
        }

        .steps-indicator {
            display: flex;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 16px;
            position: relative;
        }

        .steps-indicator::before {
            content: "";
            position: absolute;
            top: 19px;
            left: 28px;
            right: 28px;
            height: 2px;
            background: #e5e7eb;
            z-index: 0;
        }

        .step {
            flex: 1;
            text-align: center;
            position: relative;
            z-index: 1;
            cursor: pointer;
        }

        .step-circle {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            margin: 0 auto 8px;
            background: #e5e7eb;
            color: #9ca3af;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            transition: all 0.2s ease;
        }

        .step.active .step-circle {
            background: var(--primary-color);
            color: #fff;
            box-shadow: 0 0 0 4px rgba(229, 92, 13, 0.18);
        }

        .step.completed .step-circle {
            background: var(--success-color);
            color: #fff;
        }

        .step-label {
            font-size: 11px;
            color: var(--text-light);
            font-weight: 600;
            line-height: 1.3;
        }

        .step.active .step-label {
            color: var(--primary-color);
        }

        .progress-meta {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: center;
            margin-bottom: 14px;
            font-size: 0.9rem;
        }

        .progress-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 12px;
            border-radius: 999px;
            font-weight: 700;
            font-size: 0.84rem;
        }

        .status-success {
            background: #ecfdf5;
            color: #047857;
        }

        .status-warning {
            background: #fffbeb;
            color: #b45309;
        }

        .status-danger {
            background: #fef2f2;
            color: #b91c1c;
        }

        .status-info {
            background: #eff6ff;
            color: #1d4ed8;
        }

        .progress-bar-container {
            height: 8px;
            background: #e5e7eb;
            border-radius: 999px;
            overflow: hidden;
        }

        .progress-bar-fill {
            height: 100%;
            width: 0;
            background: linear-gradient(90deg, var(--primary-color), var(--success-color));
            transition: width 0.25s ease;
        }

        .hidden {
            display: none !important;
        }

        .animate-step {
            animation: fadeInUp 0.35s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .prep-list,
        .detail-grid,
        .rate-grid,
        .choice-grid,
        .review-grid,
        .summary-grid {
            display: grid;
            gap: 16px;
        }

        .prep-item,
        .summary-band,
        .info-band,
        .review-list,
        .category-block,
        .inline-alert {
            border: 1px solid var(--border-color);
            border-radius: 14px;
            background: #fff;
        }

        .prep-item,
        .summary-band,
        .info-band,
        .category-block,
        .review-list {
            padding: 16px;
        }

        .prep-item {
            display: flex;
            gap: 14px;
            align-items: flex-start;
        }

        .prep-icon,
        .pill-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(229, 92, 13, 0.12);
            color: var(--primary-color);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .summary-band {
            display: grid;
            gap: 12px;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #fff7ed, #ffffff);
        }

        .summary-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .summary-item-label,
        .review-label {
            color: var(--text-light);
            font-size: 0.8rem;
            margin-bottom: 4px;
        }

        .summary-item-value,
        .review-value {
            font-size: 0.96rem;
            font-weight: 600;
            line-height: 1.4;
            word-break: break-word;
        }

        .detail-grid,
        .rate-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .field-group {
            margin-bottom: 18px;
        }

        .field-group:last-child {
            margin-bottom: 0;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.92rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .required {
            color: var(--danger-color);
        }

        .optional {
            color: var(--text-light);
            font-size: 0.84rem;
            font-weight: 500;
        }

        .field,
        .field-select,
        textarea {
            width: 100%;
            border: 1px solid rgba(15, 23, 42, 0.12);
            border-radius: 12px;
            padding: 12px 14px;
            font: inherit;
            background: #fff;
            color: var(--text-dark);
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .field:focus,
        .field-select:focus,
        textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(229, 92, 13, 0.12);
        }

        .field[readonly] {
            background: #f8fafc;
            color: var(--text-light);
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        .field-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23334155' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 14px center;
            background-size: 14px 10px;
            padding-right: 40px;
        }

        .help-text {
            margin-top: 6px;
            color: var(--text-light);
            font-size: 0.84rem;
            line-height: 1.55;
        }

        .danger-text {
            margin-top: 6px;
            color: #b91c1c;
            font-size: 0.84rem;
            line-height: 1.5;
        }

        .inline-alert {
            padding: 14px 16px;
            margin-bottom: 18px;
            color: #b45309;
            background: #fffbeb;
            border-color: #fcd34d;
            display: none;
        }

        .inline-alert.show {
            display: block;
        }

        .choice-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .choice-item {
            position: relative;
        }

        .choice-input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .choice-label {
            min-height: 54px;
            width: 100%;
            border: 1px solid rgba(15, 23, 42, 0.12);
            border-radius: 12px;
            padding: 12px 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-size: 0.92rem;
            font-weight: 600;
            color: var(--text-dark);
            background: #fff;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .choice-input:checked + .choice-label {
            border-color: var(--primary-color);
            background: rgba(229, 92, 13, 0.08);
            color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(229, 92, 13, 0.1);
        }

        .selected-items {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 12px;
        }

        .selected-item {
            padding: 6px 12px;
            border-radius: 999px;
            background: rgba(229, 92, 13, 0.1);
            color: var(--primary-color);
            font-size: 0.82rem;
            font-weight: 700;
        }

        .category-block {
            margin-bottom: 14px;
        }

        .category-block:last-child {
            margin-bottom: 0;
        }

        .category-title {
            margin: 0 0 12px;
            font-size: 0.96rem;
            font-weight: 700;
        }

        .upload-grid {
            display: grid;
            gap: 14px;
        }

        .upload-item {
            display: block;
            padding: 18px;
            border: 2px dashed rgba(15, 23, 42, 0.12);
            border-radius: 14px;
            background: #fff;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .upload-item:hover {
            border-color: var(--primary-color);
            background: rgba(229, 92, 13, 0.04);
        }

        .upload-item.has-file {
            border-style: solid;
            border-color: var(--success-color);
            background: rgba(16, 185, 129, 0.06);
        }

        .upload-shell {
            display: flex;
            gap: 14px;
            align-items: flex-start;
        }

        .upload-icon {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            background: rgba(229, 92, 13, 0.12);
            color: var(--primary-color);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .upload-item.has-file .upload-icon {
            background: rgba(16, 185, 129, 0.16);
            color: var(--success-color);
        }

        .upload-title {
            font-size: 0.96rem;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .upload-hint {
            color: var(--text-light);
            font-size: 0.84rem;
            line-height: 1.5;
        }

        .upload-preview,
        .file-links {
            margin-top: 10px;
            display: grid;
            gap: 8px;
        }

        .file-link,
        .upload-preview span {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.88rem;
            word-break: break-word;
        }

        .info-band {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            padding: 14px 16px;
            background: linear-gradient(135deg, #fff5f0, #fffdfa);
            margin-bottom: 20px;
        }

        .button-group {
            display: flex;
            gap: 12px;
            margin-top: 26px;
        }

        .btn-step,
        .btn-outline-step {
            min-height: 48px;
            padding: 12px 18px;
            border-radius: 12px;
            border: 0;
            font-weight: 700;
            font-size: 0.96rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            flex: 1;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .btn-step {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: #fff;
            box-shadow: 0 10px 24px rgba(229, 92, 13, 0.18);
        }

        .btn-step:hover {
            transform: translateY(-1px);
        }

        .btn-outline-step {
            background: #fff;
            color: var(--text-dark);
            border: 1px solid rgba(15, 23, 42, 0.12);
        }

        .review-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            margin-bottom: 16px;
        }

        .review-list {
            display: grid;
            gap: 12px;
        }

        .review-list.full-span {
            grid-column: 1 / -1;
        }

        .review-section-title {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0 0 2px;
            color: var(--text-dark);
            font-size: 0.98rem;
            font-weight: 800;
        }

        .review-row {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: flex-start;
        }

        .review-row + .review-row {
            padding-top: 12px;
            border-top: 1px solid rgba(15, 23, 42, 0.08);
        }

        .review-row > div {
            min-width: 0;
        }

        .review-stack {
            display: grid;
            gap: 8px;
        }

        .review-chip-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .review-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            max-width: 100%;
            border-radius: 999px;
            padding: 7px 11px;
            background: #f8fafc;
            border: 1px solid rgba(15, 23, 42, 0.08);
            color: var(--text-dark);
            font-size: 0.83rem;
            font-weight: 700;
            line-height: 1.25;
        }

        .review-text-list {
            display: grid;
            gap: 8px;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .review-text-list li {
            padding: 10px 12px;
            border-radius: 10px;
            background: #f8fafc;
            color: var(--text-dark);
            line-height: 1.45;
            word-break: break-word;
        }

        .review-empty {
            color: var(--text-light);
            font-weight: 500;
        }

        .tag-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 999px;
            padding: 7px 11px;
            font-size: 0.82rem;
            font-weight: 700;
        }

        .tag-success {
            background: #ecfdf5;
            color: #047857;
        }

        .tag-warning {
            background: #fffbeb;
            color: #b45309;
        }

        .tag-danger {
            background: #fef2f2;
            color: #b91c1c;
        }

        .flash-alert {
            border-radius: 14px;
            border: 1px solid transparent;
            padding: 14px 16px;
            margin-bottom: 18px;
            font-size: 0.92rem;
            line-height: 1.55;
        }

        .flash-danger {
            background: #fef2f2;
            border-color: #fecaca;
            color: #b91c1c;
        }

        .flash-info {
            background: #eff6ff;
            border-color: #bfdbfe;
            color: #1d4ed8;
        }

        .error-summary {
            border-radius: 14px;
            border: 1px solid #fed7aa;
            background: #fff7ed;
            padding: 16px;
            margin-bottom: 18px;
        }

        .error-summary-title {
            font-weight: 700;
            margin-bottom: 10px;
            color: #9a3412;
        }

        .error-step-block + .error-step-block {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid rgba(154, 52, 18, 0.12);
        }

        .error-step-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 0;
            border-radius: 999px;
            padding: 8px 12px;
            background: rgba(229, 92, 13, 0.12);
            color: #9a3412;
            font-size: 0.82rem;
            font-weight: 700;
            margin-bottom: 10px;
            cursor: pointer;
        }

        .error-message-list {
            margin: 0;
            padding-left: 18px;
            color: #9a3412;
            font-size: 0.88rem;
            line-height: 1.6;
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 480px;
            background: var(--bg-secondary);
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: space-around;
            padding: 8px 0 20px;
            z-index: 120;
        }

        @media (min-width: 768px) {
            .bottom-nav {
                max-width: 100%;
                left: 0;
                transform: none;
            }
        }

        .nav-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: var(--text-light);
            padding: 8px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .nav-item.active,
        .nav-item:hover {
            color: var(--primary-color);
            background: rgba(229, 92, 13, 0.06);
        }

        .nav-item i {
            font-size: 1.1rem;
            margin-bottom: 4px;
        }

        @media (max-width: 900px) {
            .detail-grid,
            .rate-grid,
            .choice-grid,
            .review-grid,
            .summary-grid {
                grid-template-columns: 1fr;
            }

            .review-row {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="app-container">
        <div class="status-bar"></div>

        <header class="navbar">
            <div class="px-4 py-3 d-flex justify-content-between align-items-center w-100">
                <div class="d-flex align-items-center">
                    <div class="avatar me-3"><?= esc($initial) ?></div>
                    <div>
                        <div class="fw-semibold"><?= esc($fullName) ?></div>
                        <small class="text-muted">University Profile Setup</small>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="icon-button" onclick="window.location.href='<?= site_url('university-portal/dashboard') ?>'">
                        <i class="fas fa-house"></i>
                    </button>
                    <button type="button" class="icon-button" onclick="window.location.href='<?= site_url('logout') ?>'">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
            </div>
        </header>

        <div class="screen">
            <?php if (session('error')): ?>
                <div class="flash-alert flash-danger"><?= esc(session('error')) ?></div>
            <?php endif; ?>

            <?php if (!empty($profile_completion_gaps) && $status !== 'approved'): ?>
                <div class="flash-alert flash-info">
                    Admin approval depends on the full profile submission. Complete each step, upload the supporting files, and then submit the application for review.
                </div>
            <?php endif; ?>

            <div
                id="requirementsBlock"
                class="step-content<?= $shouldAutoStart ? ' d-none' : '' ?>"
                style="background: linear-gradient(135deg, #fff5f0 0%, #fffdfa 100%); border-left: 4px solid var(--primary-color);"
            >
                <h2 class="step-title" style="color: var(--primary-color);">Before You Begin</h2>
                <p class="step-description"><?= $isFirm ? 'Complete your university firm profile in a few guided steps. Your submission helps the admin team verify your business background, service areas, availability, rates, and supporting documents before approval.' : 'Complete your university professional profile in a few guided steps. Your submission helps the admin team verify your academic background, service areas, availability, rates, and supporting documents before approval.' ?></p>

                <div class="prep-list">
                    <div class="prep-item">
                        <div class="prep-icon"><i class="fas fa-user-graduate"></i></div>
                        <div>
                            <div class="fw-semibold mb-1"><?= $isFirm ? 'Firm Profile' : 'Academic Profile' ?></div>
                            <div class="text-muted small"><?= $isFirm ? 'Provide your registration year, location, preferred support mode, work status, and a concise company profile.' : 'Provide your study or graduation year, location, preferred teaching mode, work status, and a concise professional bio.' ?></div>
                        </div>
                    </div>

                    <div class="prep-item">
                        <div class="prep-icon"><i class="fas fa-book-open"></i></div>
                        <div>
                            <div class="fw-semibold mb-1"><?= $isFirm ? 'Background and Rates' : 'Institutions and Rates' ?></div>
                            <div class="text-muted small"><?= $isFirm ? 'List the business or institutional background that supports your services and add the rates the team should consider for university-level assignments.' : 'List the institutions you support and add the rates the team should consider for university-level assignments.' ?></div>
                        </div>
                    </div>

                    <div class="prep-item">
                        <div class="prep-icon"><i class="fas fa-calendar-check"></i></div>
                        <div>
                            <div class="fw-semibold mb-1">Service Areas and Availability</div>
                            <div class="text-muted small">Select your academic support services, available days, preferred times, and reference contacts where applicable.</div>
                        </div>
                    </div>

                    <div class="prep-item">
                        <div class="prep-icon"><i class="fas fa-id-card"></i></div>
                        <div>
                            <div class="fw-semibold mb-1">Verification Documents</div>
                            <div class="text-muted small"><?= $isFirm ? 'Upload a clear company logo, business registration certificate, and supporting business or professional certificates for verification.' : 'Upload a clear profile photo, national ID, and academic certifications or transcript for verification.' ?></div>
                        </div>
                    </div>
                </div>

                <div class="info-band mt-4">
                    <div class="pill-icon"><i class="fas fa-circle-info"></i></div>
                    <div>
                        <div class="fw-semibold mb-1">Review Process</div>
                        <div class="text-muted small">Submitting your profile sends it for admin review. Approval is completed after your details and documents have been verified.</div>
                    </div>
                </div>

                <div class="button-group">
                    <a href="<?= site_url('university-portal/dashboard') ?>" class="btn-outline-step">
                        <i class="fas fa-arrow-left"></i>
                        <span>Back</span>
                    </a>
                    <button type="button" class="btn-step" onclick="startWizard(1)">
                        <i class="fas fa-rocket"></i>
                        <span>Start Profile</span>
                    </button>
                </div>
            </div>

            <div id="progressContainer" class="progress-container<?= $shouldAutoStart ? '' : ' d-none' ?>">
                <div class="progress-meta">
                    <div>
                        <div class="fw-semibold">Application Setup</div>
                        <div class="text-muted small">Step <span id="currentStepText"><?= (int) $initialStep ?></span> of 5</div>
                    </div>
                    <span class="progress-badge <?= esc($statusConfig['class']) ?>"><?= esc($statusConfig['label']) ?></span>
                </div>

                <div class="steps-indicator">
                    <div class="step" data-step="1">
                        <div class="step-circle">1</div>
                        <div class="step-label">Profile</div>
                    </div>
                    <div class="step" data-step="2">
                        <div class="step-circle">2</div>
                        <div class="step-label">Rates</div>
                    </div>
                    <div class="step" data-step="3">
                        <div class="step-circle">3</div>
                        <div class="step-label">Availability</div>
                    </div>
                    <div class="step" data-step="4">
                        <div class="step-circle">4</div>
                        <div class="step-label">Documents</div>
                    </div>
                    <div class="step" data-step="5">
                        <div class="step-circle">5</div>
                        <div class="step-label">Review</div>
                    </div>
                </div>

                <div class="progress-bar-container">
                    <div class="progress-bar-fill" id="progressBar"></div>
                </div>
            </div>

            <form id="profileWizardForm" method="post" action="<?= site_url('university-portal/complete-profile') ?>" enctype="multipart/form-data" novalidate>
                <?= csrf_field() ?>
                <input type="hidden" id="wizard_step" name="wizard_step" value="<?= (int) $initialStep ?>">

                <section class="step-content wizard-step hidden" id="step1">
                    <h2 class="step-title"><?= $isFirm ? 'Firm Profile' : 'Academic Profile' ?></h2>
                    <p class="step-description"><?= $isFirm ? 'Start with the core details the admin team uses to verify your firm and place it correctly in the portal.' : 'Start with the core details the admin team uses to verify your university profile and place you correctly in the portal.' ?></p>

                    <div class="inline-alert" id="step1Alert"></div>

                    <div class="summary-band">
                        <div class="summary-grid">
                            <div>
                                <div class="summary-item-label">First Name</div>
                                <div class="summary-item-value"><?= esc($user['first_name'] ?? '-') ?></div>
                            </div>
                            <div>
                                <div class="summary-item-label">Last Name</div>
                                <div class="summary-item-value"><?= esc($user['last_name'] ?? '-') ?></div>
                            </div>
                            <div>
                                <div class="summary-item-label">Email Address</div>
                                <div class="summary-item-value"><?= esc($profile['email'] ?? ($user['email'] ?? '-')) ?></div>
                            </div>
                            <div>
                                <div class="summary-item-label">Phone Number</div>
                                <div class="summary-item-value"><?= esc($profile['phone'] ?? ($user['phone'] ?? '-')) ?></div>
                            </div>
                            <div>
                                <div class="summary-item-label">Username</div>
                                <div class="summary-item-value"><?= esc($user['username'] ?? '-') ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="detail-grid">
                        <div class="field-group">
                            <label class="form-label" for="year_of_study_or_graduation"><?= esc($yearLabel) ?> <span class="required">*</span></label>
                            <input class="field" id="year_of_study_or_graduation" name="year_of_study_or_graduation" value="<?= esc(old('year_of_study_or_graduation', $profile['year_of_study_or_graduation'] ?? '')) ?>">
                            <?php if ($fieldError('year_of_study_or_graduation') !== ''): ?><div class="danger-text"><?= esc($fieldError('year_of_study_or_graduation')) ?></div><?php endif; ?>
                        </div>

                        <div class="field-group">
                            <label class="form-label" for="teaching_mode">Preferred Teaching Mode <span class="required">*</span></label>
                            <select class="field-select" id="teaching_mode" name="teaching_mode">
                                <option value="">Select teaching mode</option>
                                <?php foreach (['Online', 'Physical', 'Both'] as $mode): ?>
                                    <option value="<?= esc($mode) ?>" <?= old('teaching_mode', $profile['teaching_mode'] ?? '') === $mode ? 'selected' : '' ?>><?= esc($mode) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if ($fieldError('teaching_mode') !== ''): ?><div class="danger-text"><?= esc($fieldError('teaching_mode')) ?></div><?php endif; ?>
                        </div>

                        <div class="field-group">
                            <label class="form-label" for="city_location">City / Location <span class="required">*</span></label>
                            <input class="field" id="city_location" name="city_location" value="<?= esc(old('city_location', $profile['city_location'] ?? '')) ?>">
                            <?php if ($fieldError('city_location') !== ''): ?><div class="danger-text"><?= esc($fieldError('city_location')) ?></div><?php endif; ?>
                        </div>

                        <?php if ($isFirm): ?>
                            <input type="hidden" id="work_status" name="work_status" value="">
                        <?php else: ?>
                            <div class="field-group">
                                <label class="form-label" for="work_status">Currently Employed?</label>
                                <select class="field-select" id="work_status" name="work_status">
                                    <option value="">Select employment status</option>
                                    <option value="Employed" <?= $workStatus === 'Employed' ? 'selected' : '' ?>>Yes</option>
                                    <option value="Not Employed" <?= $workStatus === 'Not Employed' ? 'selected' : '' ?>>No</option>
                                </select>
                                <?php if ($fieldError('work_status') !== ''): ?><div class="danger-text"><?= esc($fieldError('work_status')) ?></div><?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="detail-grid hidden" id="employerFields" aria-hidden="<?= (!$isFirm && $workStatus === 'Employed') ? 'false' : 'true' ?>">
                        <div class="field-group">
                            <label class="form-label" for="employer_name">Employer / Institution Name <span class="required">*</span></label>
                            <input class="field" id="employer_name" name="employer_name" value="<?= esc(old('employer_name', $profile['employer_name'] ?? '')) ?>">
                            <div class="help-text">Required only when Currently Employed is Yes.</div>
                            <?php if ($workStatus === 'Employed' && $fieldError('employer_name') !== ''): ?><div class="danger-text"><?= esc($fieldError('employer_name')) ?></div><?php endif; ?>
                        </div>

                        <div class="field-group">
                            <label class="form-label" for="employer_contact">Employer Contact <span class="required">*</span></label>
                            <input class="field" id="employer_contact" name="employer_contact" value="<?= esc(old('employer_contact', $profile['employer_contact'] ?? '')) ?>">
                            <div class="help-text">Required only when Currently Employed is Yes.</div>
                            <?php if ($workStatus === 'Employed' && $fieldError('employer_contact') !== ''): ?><div class="danger-text"><?= esc($fieldError('employer_contact')) ?></div><?php endif; ?>
                        </div>
                    </div>

                    <div class="field-group">
                        <label class="form-label" for="bio"><?= esc($bioLabel) ?> <span class="required">*</span></label>
                        <textarea id="bio" name="bio"><?= esc(old('bio', $profile['bio'] ?? '')) ?></textarea>
                        <div class="help-text">Minimum 40 characters. This is the summary the admin team uses when reviewing your academic support profile.</div>
                        <?php if ($fieldError('bio') !== ''): ?><div class="danger-text"><?= esc($fieldError('bio')) ?></div><?php endif; ?>
                    </div>

                    <div class="button-group">
                        <button type="button" class="btn-outline-step" onclick="goToRequirements()">
                            <i class="fas fa-arrow-left"></i>
                            <span>Back</span>
                        </button>
                        <button type="button" class="btn-step" onclick="nextStep()">
                            <span>Continue</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </section>

                <section class="step-content wizard-step hidden" id="step2">
                    <h2 class="step-title">Institutions and Rates</h2>
                    <p class="step-description">List your academic background and the professional rates the team can use when assigning university support work.</p>

                    <div class="inline-alert" id="step2Alert"></div>

                    <div class="field-group">
                        <label class="form-label" for="institutions"><?= esc($institutionsLabel) ?> <span class="required">*</span></label>
                        <textarea id="institutions" name="institutions"><?= esc(old('institutions', $institutions_text ?? '')) ?></textarea>
                        <div class="help-text">Enter one institution per line. Include current or completed university and college study.</div>
                        <?php if ($fieldError('institutions') !== ''): ?><div class="danger-text"><?= esc($fieldError('institutions')) ?></div><?php endif; ?>
                    </div>

                    <div class="info-band">
                        <div class="pill-icon"><i class="fas fa-briefcase"></i></div>
                        <div>
                            <div class="fw-semibold mb-1">University Professional Rates</div>
                            <div class="text-muted small">Set the rates you want the university support team to use for academic consultations, specialist tutoring, dissertation guidance, and exam preparation. Dissertation pricing is for guidance and technical support only, not completing student work.</div>
                        </div>
                    </div>

                    <div class="rate-grid">
                        <div class="field-group">
                            <label class="form-label" for="hourly_rate">Hourly Rate (MWK)</label>
                            <input class="field" id="hourly_rate" name="hourly_rate" value="<?= esc(old('hourly_rate', $profile['hourly_rate'] ?? '')) ?>">
                            <?php if ($fieldError('hourly_rate') !== ''): ?><div class="danger-text"><?= esc($fieldError('hourly_rate')) ?></div><?php endif; ?>
                        </div>

                        <div class="field-group">
                            <label class="form-label" for="consultation_package_rate">Consultation Package Rate (MWK)</label>
                            <input class="field" id="consultation_package_rate" name="consultation_package_rate" value="<?= esc(old('consultation_package_rate', $profile['consultation_package_rate'] ?? '')) ?>">
                            <?php if ($fieldError('consultation_package_rate') !== ''): ?><div class="danger-text"><?= esc($fieldError('consultation_package_rate')) ?></div><?php endif; ?>
                        </div>

                        <div class="field-group">
                            <label class="form-label" for="dissertation_package_rate">Dissertation Package Rate (MWK)</label>
                            <input class="field" id="dissertation_package_rate" name="dissertation_package_rate" value="<?= esc(old('dissertation_package_rate', $profile['dissertation_package_rate'] ?? '')) ?>">
                            <?php if ($fieldError('dissertation_package_rate') !== ''): ?><div class="danger-text"><?= esc($fieldError('dissertation_package_rate')) ?></div><?php endif; ?>
                        </div>

                        <div class="field-group">
                            <label class="form-label" for="exam_preparation_rate">Exam Preparation Rate (MWK)</label>
                            <input class="field" id="exam_preparation_rate" name="exam_preparation_rate" value="<?= esc(old('exam_preparation_rate', $profile['exam_preparation_rate'] ?? '')) ?>">
                            <?php if ($fieldError('exam_preparation_rate') !== ''): ?><div class="danger-text"><?= esc($fieldError('exam_preparation_rate')) ?></div><?php endif; ?>
                        </div>
                    </div>

                    <div class="button-group">
                        <button type="button" class="btn-outline-step" onclick="previousStep()">
                            <i class="fas fa-arrow-left"></i>
                            <span>Back</span>
                        </button>
                        <button type="button" class="btn-step" onclick="nextStep()">
                            <span>Continue</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </section>

                <section class="step-content wizard-step hidden" id="step3">
                    <h2 class="step-title">Service Areas and Availability</h2>
                    <p class="step-description">Choose the support categories you handle and the time windows you can reliably commit to.</p>

                    <div class="inline-alert" id="step3Alert"></div>

                    <div class="field-group">
                        <label class="form-label">Service Areas <span class="required">*</span></label>
                        <?php foreach ($service_categories as $category => $services): ?>
                            <div class="category-block">
                                <h3 class="category-title"><?= esc($category) ?></h3>
                                <div class="choice-grid">
                                    <?php foreach ($services as $index => $service): ?>
                                        <?php $serviceId = 'service_' . md5($category . '_' . $service . '_' . $index); ?>
                                        <div class="choice-item">
                                            <input
                                                class="choice-input service-area-checkbox"
                                                type="checkbox"
                                                id="<?= esc($serviceId) ?>"
                                                name="service_areas[]"
                                                value="<?= esc($service) ?>"
                                                <?= in_array($service, $selectedServiceAreas, true) ? 'checked' : '' ?>
                                            >
                                            <label class="choice-label" for="<?= esc($serviceId) ?>"><?= esc($service) ?></label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <div class="selected-items" id="serviceAreaChips"></div>
                        <?php if ($fieldError('service_areas') !== ''): ?><div class="danger-text"><?= esc($fieldError('service_areas')) ?></div><?php endif; ?>
                    </div>

                    <div class="field-group">
                        <label class="form-label">Available Days <span class="required">*</span></label>
                        <div class="choice-grid">
                            <?php foreach ($availability_days as $index => $day): ?>
                                <?php $dayId = 'day_' . $index; ?>
                                <div class="choice-item">
                                    <input
                                        class="choice-input availability-checkbox"
                                        type="checkbox"
                                        id="<?= esc($dayId) ?>"
                                        name="available_days[]"
                                        value="<?= esc($day) ?>"
                                        <?= in_array($day, $selectedDays, true) ? 'checked' : '' ?>
                                    >
                                    <label class="choice-label" for="<?= esc($dayId) ?>"><?= esc($day) ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="selected-items" id="availabilityChips"></div>
                        <?php if ($fieldError('available_days') !== ''): ?><div class="danger-text"><?= esc($fieldError('available_days')) ?></div><?php endif; ?>
                    </div>

                    <div class="field-group">
                        <label class="form-label">Preferred Teaching Times <span class="required">*</span></label>
                        <div class="choice-grid">
                            <?php foreach (($preferred_time_options ?? []) as $index => $timeOption): ?>
                                <?php $timeId = 'preferred_time_' . $index; ?>
                                <div class="choice-item">
                                    <input
                                        class="choice-input preferred-time-checkbox"
                                        type="checkbox"
                                        id="<?= esc($timeId) ?>"
                                        name="preferred_times[]"
                                        value="<?= esc($timeOption) ?>"
                                        <?= in_array($timeOption, $selectedPreferredTimes, true) ? 'checked' : '' ?>
                                    >
                                    <label class="choice-label" for="<?= esc($timeId) ?>"><?= esc($timeOption) ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="selected-items" id="preferredTimeChips"></div>
                        <div class="help-text">Choose the time blocks you can reliably support. Select all that apply.</div>
                        <?php if ($fieldError('preferred_times') !== ''): ?><div class="danger-text"><?= esc($fieldError('preferred_times')) ?></div><?php endif; ?>
                    </div>

                    <div class="field-group">
                        <label class="form-label" for="references">References <span class="optional">Optional</span></label>
                        <textarea id="references" name="references"><?= esc(old('references', $references_text ?? '')) ?></textarea>
                        <div class="help-text">References are optional. If included, provide at least three contacts, one per line.</div>
                        <?php if ($fieldError('references') !== ''): ?><div class="danger-text"><?= esc($fieldError('references')) ?></div><?php endif; ?>
                    </div>

                    <div class="button-group">
                        <button type="button" class="btn-outline-step" onclick="previousStep()">
                            <i class="fas fa-arrow-left"></i>
                            <span>Back</span>
                        </button>
                        <button type="button" class="btn-step" onclick="nextStep()">
                            <span>Continue</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </section>

                <section class="step-content wizard-step hidden" id="step4">
                    <h2 class="step-title">Verification Documents</h2>
                    <p class="step-description">Upload the files the admin team must review before approving the university account and unlocking the subscription stage.</p>

                    <div class="inline-alert" id="step4Alert"></div>

                    <div class="upload-grid">
                        <label class="upload-item<?= $profilePicturePath !== '' ? ' has-file' : '' ?>" id="profilePictureCard">
                            <input type="file" id="profile_picture" name="profile_picture" accept=".jpg,.jpeg,.png" class="d-none" data-card="profilePictureCard" data-preview="profilePicturePreview" data-hint="profilePictureHint">
                            <div class="upload-shell">
                                <div class="upload-icon"><i class="fas fa-camera"></i></div>
                                <div class="w-100">
                                    <div class="upload-title"><?= esc($profileImageLabel) ?> <span class="required">*</span></div>
                                    <div class="upload-hint" id="profilePictureHint"><?= esc($profileImageHint) ?></div>
                                    <div class="upload-preview" id="profilePicturePreview">
                                        <?php if ($profilePicturePath !== ''): ?>
                                            <a class="file-link" href="<?= base_url($profilePicturePath) ?>" target="_blank"><?= esc($profileImageCurrentLabel) ?></a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </label>
                        <?php if ($fieldError('profile_picture') !== ''): ?><div class="danger-text"><?= esc($fieldError('profile_picture')) ?></div><?php endif; ?>

                        <label class="upload-item<?= $nationalIdPath !== '' ? ' has-file' : '' ?>" id="nationalIdCard">
                            <input type="file" id="national_id_file" name="national_id_file" accept=".jpg,.jpeg,.png,.pdf" class="d-none" data-card="nationalIdCard" data-preview="nationalIdPreview" data-hint="nationalIdHint">
                            <div class="upload-shell">
                                <div class="upload-icon"><i class="fas fa-id-card"></i></div>
                                <div class="w-100">
                                    <div class="upload-title"><?= esc($identityDocumentLabel) ?> <span class="required">*</span></div>
                                    <div class="upload-hint" id="nationalIdHint"><?= esc($identityDocumentHint) ?></div>
                                    <div class="upload-preview" id="nationalIdPreview">
                                        <?php if ($nationalIdPath !== ''): ?>
                                            <a class="file-link" href="<?= base_url($nationalIdPath) ?>" target="_blank"><?= esc($identityDocumentCurrentLabel) ?></a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </label>
                        <?php if ($fieldError('national_id_file') !== ''): ?><div class="danger-text"><?= esc($fieldError('national_id_file')) ?></div><?php endif; ?>

                        <label class="upload-item<?= $certificationFiles !== [] ? ' has-file' : '' ?>" id="certificationCard">
                            <input type="file" id="certification_files" name="certification_files[]" accept=".jpg,.jpeg,.png,.pdf" multiple class="d-none" data-card="certificationCard" data-preview="certificationPreview" data-hint="certificationHint">
                            <div class="upload-shell">
                                <div class="upload-icon"><i class="fas fa-file-lines"></i></div>
                                <div class="w-100">
                                    <div class="upload-title"><?= esc($certificationLabel) ?> <span class="required">*</span></div>
                                    <div class="upload-hint" id="certificationHint">You can keep up to 4 image or PDF files in total.</div>
                                    <div class="upload-preview" id="certificationPreview">
                                        <?php if ($certificationFiles !== []): ?>
                                            <?php foreach ($certificationFiles as $index => $filePath): ?>
                                                <a class="file-link" href="<?= base_url($filePath) ?>" target="_blank"><?= esc($certificationCurrentLabel) ?> <?= $index + 1 ?></a>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </label>
                        <?php if ($fieldError('certification_files') !== ''): ?><div class="danger-text"><?= esc($fieldError('certification_files')) ?></div><?php endif; ?>
                    </div>

                    <div class="info-band mt-4">
                        <div class="pill-icon"><i class="fas fa-shield-check"></i></div>
                        <div>
                            <div class="fw-semibold mb-1">Admin review package</div>
                            <div class="text-muted small">The files uploaded here stay tied to the university profile so the admin can review the full submission before approval.</div>
                        </div>
                    </div>

                    <div class="button-group">
                        <button type="button" class="btn-outline-step" onclick="previousStep()">
                            <i class="fas fa-arrow-left"></i>
                            <span>Back</span>
                        </button>
                        <button type="button" class="btn-step" onclick="nextStep()">
                            <span>Continue</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </section>

                <section class="step-content wizard-step hidden" id="step5">
                    <h2 class="step-title">Review and Submit</h2>
                    <p class="step-description">Check the live summary below, then submit the full university application for admin review. Approval is separate from account creation, and subscription follows after approval.</p>

                    <div class="inline-alert" id="step5Alert"></div>

                    <?php if (!empty($errorStepSummaries)): ?>
                        <div class="error-summary">
                            <div class="error-summary-title">We could not submit the profile yet. Review the items below and jump straight to the step that needs updating.</div>

                            <?php foreach ($errorStepSummaries as $stepNumber => $messages): ?>
                                <div class="error-step-block">
                                    <button type="button" class="error-step-button" data-jump-step="<?= (int) $stepNumber ?>">
                                        <i class="fas fa-arrow-up-right-from-square"></i>
                                        <span>Open Step <?= (int) $stepNumber ?>: <?= esc($stepTitles[$stepNumber] ?? 'Profile Step') ?></span>
                                    </button>
                                    <ul class="error-message-list">
                                        <?php foreach ($messages as $message): ?>
                                            <li><?= esc($message) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <div class="review-grid">
                        <div class="review-list">
                            <h3 class="review-section-title"><i class="fas fa-id-card"></i> Profile Details</h3>
                            <div class="review-row">
                                <div>
                                    <div class="review-label">Profile status</div>
                                    <div class="review-value"><?= esc($statusConfig['label']) ?></div>
                                </div>
                                <div class="tag-list" id="reviewCompletenessBadge"></div>
                            </div>
                            <div class="review-row">
                                <div>
                                    <div class="review-label">Full Name</div>
                                    <div class="review-value"><?= esc($fullName) ?></div>
                                </div>
                                <div>
                                    <div class="review-label">Username</div>
                                    <div class="review-value"><?= esc($user['username'] ?? '-') ?></div>
                                </div>
                            </div>
                            <div class="review-row">
                                <div>
                                    <div class="review-label">Email Address</div>
                                    <div class="review-value"><?= esc($profile['email'] ?? ($user['email'] ?? '-')) ?></div>
                                </div>
                                <div>
                                    <div class="review-label">Phone Number</div>
                                    <div class="review-value"><?= esc($profile['phone'] ?? ($user['phone'] ?? '-')) ?></div>
                                </div>
                            </div>
                            <div class="review-row">
                                <div>
                                    <div class="review-label">Preferred Teaching Mode</div>
                                    <div class="review-value" id="reviewTeachingMode">-</div>
                                </div>
                                <div>
                                    <div class="review-label">City / Location</div>
                                    <div class="review-value" id="reviewCity">-</div>
                                </div>
                            </div>
                            <?php if (!$isFirm): ?>
                            <div class="review-row">
                                <div>
                                    <div class="review-label">Currently Employed?</div>
                                    <div class="review-value" id="reviewWorkStatus">-</div>
                                </div>
                                <div>
                                    <div class="review-label"><?= esc($yearLabel) ?></div>
                                    <div class="review-value" id="reviewYear">-</div>
                                </div>
                            </div>
                            <?php endif; ?>
                            <div class="review-row" id="reviewEmployerRow">
                                <div>
                                    <div class="review-label">Employer / Institution</div>
                                    <div class="review-value" id="reviewEmployerName">-</div>
                                </div>
                                <div>
                                    <div class="review-label">Employer Contact</div>
                                    <div class="review-value" id="reviewEmployerContact">-</div>
                                </div>
                            </div>
                        </div>

                        <div class="review-list">
                            <h3 class="review-section-title"><i class="fas fa-money-check-dollar"></i> Rates</h3>
                            <div class="review-row">
                                <div>
                                    <div class="review-label">Hourly Rate</div>
                                    <div class="review-value" id="reviewHourlyRate">Not provided</div>
                                </div>
                                <div>
                                    <div class="review-label">Consultation Package</div>
                                    <div class="review-value" id="reviewConsultationRate">Not provided</div>
                                </div>
                            </div>
                            <div class="review-row">
                                <div>
                                    <div class="review-label">Dissertation Package</div>
                                    <div class="review-value" id="reviewDissertationRate">Not provided</div>
                                </div>
                                <div>
                                    <div class="review-label">Exam Preparation</div>
                                    <div class="review-value" id="reviewExamRate">Not provided</div>
                                </div>
                            </div>
                            <div class="review-row">
                                <div>
                                    <div class="review-label">Documents</div>
                                    <div class="review-value" id="reviewDocumentState">Checking files...</div>
                                </div>
                                <div>
                                    <div class="review-label">References</div>
                                    <div class="review-value" id="reviewReferenceState">-</div>
                                </div>
                            </div>
                        </div>

                        <div class="review-list full-span">
                            <h3 class="review-section-title"><i class="fas fa-building-columns"></i> <?= esc($institutionsLabel) ?></h3>
                            <ul class="review-text-list" id="reviewInstitutionsList"></ul>
                        </div>

                        <div class="review-list full-span">
                            <h3 class="review-section-title"><i class="fas fa-layer-group"></i> Service Areas</h3>
                            <div class="review-chip-list" id="reviewServiceList"></div>
                        </div>

                        <div class="review-list full-span">
                            <h3 class="review-section-title"><i class="fas fa-calendar-check"></i> Availability</h3>
                            <div class="review-stack">
                                <div>
                                    <div class="review-label">Available Days</div>
                                    <div class="review-chip-list" id="reviewAvailableDaysList"></div>
                                </div>
                                <div>
                                    <div class="review-label">Preferred Teaching Times</div>
                                    <div class="review-chip-list" id="reviewPreferredTimesList"></div>
                                </div>
                            </div>
                        </div>

                        <div class="review-list full-span">
                            <h3 class="review-section-title"><i class="fas fa-user-check"></i> References</h3>
                            <ul class="review-text-list" id="reviewReferencesList"></ul>
                        </div>
                    </div>

                    <?php if (!empty($profile_completion_gaps)): ?>
                        <div class="review-list mb-3">
                            <div class="review-label">Current saved gaps before this submission</div>
                            <div class="tag-list">
                                <?php foreach ($profile_completion_gaps as $gap): ?>
                                    <span class="tag tag-warning"><i class="fas fa-circle-exclamation"></i><?= esc($gap) ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="info-band">
                        <div class="pill-icon"><i class="fas fa-paper-plane"></i></div>
                        <div>
                            <div class="fw-semibold mb-1">Submission note</div>
                            <div class="text-muted small">Once submitted, the university record moves into admin review unless it was already approved. After approval, the subscription flow stays in the university portal and uses the same backend payment rules as tutor accounts.</div>
                        </div>
                    </div>

                    <div class="button-group">
                        <button type="button" class="btn-outline-step" onclick="previousStep()">
                            <i class="fas fa-arrow-left"></i>
                            <span>Back</span>
                        </button>
                        <button type="submit" class="btn-step">
                            <i class="fas fa-paper-plane"></i>
                            <span>Save and Submit</span>
                        </button>
                    </div>
                </section>
            </form>
        </div>

        <nav class="bottom-nav">
            <a href="<?= site_url('university-portal/dashboard') ?>" class="nav-item">
                <i class="fas fa-house"></i>
                <span>Home</span>
            </a>
            <a href="<?= site_url('university-portal/complete-profile') ?>" class="nav-item active">
                <i class="fas fa-user"></i>
                <span>Profile</span>
            </a>
            <a href="<?= site_url('university-portal/subscription') ?>" class="nav-item">
                <i class="fas fa-crown"></i>
                <span>Premium</span>
            </a>
            <a href="<?= site_url('logout') ?>" class="nav-item">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </nav>
    </div>

    <script>
        const totalSteps = 5;
        let currentStep = <?= $shouldAutoStart ? (int) $initialStep : 0 ?>;

        const draftSaveUrl = '<?= site_url('university-portal/complete-profile/draft') ?>';
        const existingFiles = {
            profilePicture: <?= $profilePicturePath !== '' ? 'true' : 'false' ?>,
            nationalId: <?= $nationalIdPath !== '' ? 'true' : 'false' ?>,
            certifications: <?= count($certificationFiles) ?>
        };
        const documentLabels = {
            profileImageRequired: <?= json_encode($isFirm ? 'Upload a company logo before continuing.' : 'Upload a profile picture before continuing.') ?>,
            identityRequired: <?= json_encode($isFirm ? 'Upload the business registration certificate before continuing.' : 'Upload the national ID file before continuing.') ?>,
            certificateRequired: <?= json_encode($isFirm ? 'Upload at least one supporting business or professional certificate.' : 'Upload at least one academic certification or transcript.') ?>
        };

        const requirementsBlock = document.getElementById('requirementsBlock');
        const progressContainer = document.getElementById('progressContainer');
        const progressBar = document.getElementById('progressBar');
        const currentStepText = document.getElementById('currentStepText');
        const stepElements = Array.from(document.querySelectorAll('.wizard-step'));
        const stepIndicators = Array.from(document.querySelectorAll('.steps-indicator .step'));
        const workStatusField = document.getElementById('work_status');
        const employerFields = document.getElementById('employerFields');
        const employerNameField = document.getElementById('employer_name');
        const employerContactField = document.getElementById('employer_contact');
        const form = document.getElementById('profileWizardForm');
        const wizardStepField = document.getElementById('wizard_step');

        function syncEmployerFields(clearWhenNotEmployed = false) {
            if (!workStatusField || !employerFields) {
                return;
            }

            const isEmployed = workStatusField.value === 'Employed';
            employerFields.classList.toggle('hidden', !isEmployed);
            employerFields.setAttribute('aria-hidden', isEmployed ? 'false' : 'true');

            if (employerNameField) {
                employerNameField.required = isEmployed;
                employerNameField.disabled = !isEmployed;
                if (!isEmployed && clearWhenNotEmployed) {
                    employerNameField.value = '';
                }
            }

            if (employerContactField) {
                employerContactField.required = isEmployed;
                employerContactField.disabled = !isEmployed;
                if (!isEmployed && clearWhenNotEmployed) {
                    employerContactField.value = '';
                }
            }
        }

        function updateIndicators(stepNumber) {
            stepIndicators.forEach((indicator, index) => {
                const step = index + 1;
                indicator.classList.remove('active', 'completed');

                const circle = indicator.querySelector('.step-circle');
                circle.innerHTML = String(step);

                if (step < stepNumber) {
                    indicator.classList.add('completed');
                    circle.innerHTML = '<i class="fas fa-check"></i>';
                } else if (step === stepNumber) {
                    indicator.classList.add('active');
                }
            });

            if (currentStepText) {
                currentStepText.textContent = stepNumber > 0 ? String(stepNumber) : '1';
            }

            if (progressBar) {
                const progress = stepNumber > 0 ? (stepNumber / totalSteps) * 100 : 0;
                progressBar.style.width = progress + '%';
            }
        }

        function hideAllSteps() {
            stepElements.forEach((step) => step.classList.add('hidden'));
        }

        function showStep(stepNumber) {
            const target = document.getElementById('step' + stepNumber);
            if (!target) {
                return;
            }

            currentStep = stepNumber;
            if (wizardStepField) {
                wizardStepField.value = String(stepNumber);
            }
            hideAllSteps();
            target.classList.remove('hidden');
            target.classList.add('animate-step');
            window.setTimeout(() => target.classList.remove('animate-step'), 350);

            updateIndicators(stepNumber);
            renderSelectedChips();
            renderReviewState();

            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function startWizard(stepNumber = 1) {
            if (requirementsBlock) {
                requirementsBlock.classList.add('d-none');
            }

            if (progressContainer) {
                progressContainer.classList.remove('d-none');
            }

            showStep(stepNumber);
        }

        function goToRequirements() {
            hideAllSteps();
            currentStep = 0;
            if (wizardStepField) {
                wizardStepField.value = '1';
            }
            updateIndicators(0);

            if (progressContainer) {
                progressContainer.classList.add('d-none');
            }

            if (requirementsBlock) {
                requirementsBlock.classList.remove('d-none');
            }

            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        async function previousStep() {
            if (currentStep > 1) {
                const saved = await saveDraftStep(currentStep);
                if (!saved) {
                    return;
                }

                showStep(currentStep - 1);
            }
        }

        async function saveDraftStep(stepNumber) {
            if (!form || !draftSaveUrl) {
                return true;
            }

            syncEmployerFields(true);

            if (wizardStepField) {
                wizardStepField.value = String(stepNumber);
            }

            const formData = new FormData(form);
            formData.set('wizard_step', String(stepNumber));

            try {
                const response = await fetch(draftSaveUrl, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const payload = await response.json().catch(() => ({}));

                if (!response.ok || !payload.success) {
                    setStepAlert(stepNumber, payload.message || 'We could not save this step. Please review the details and try again.');
                    return false;
                }

                if (payload.files) {
                    existingFiles.profilePicture = Boolean(payload.files.profile_picture);
                    existingFiles.nationalId = Boolean(payload.files.national_id);
                    existingFiles.certifications = Number(payload.files.certifications || 0);

                    ['profile_picture', 'national_id_file', 'certification_files'].forEach((id) => {
                        const input = document.getElementById(id);
                        if (input) {
                            input.value = '';
                        }
                    });
                }

                setStepAlert(stepNumber, '');
                renderReviewState();
                return true;
            } catch (error) {
                setStepAlert(stepNumber, 'We could not save this step right now. Please check your connection and try again.');
                return false;
            }
        }

        async function nextStep() {
            if (!validateStep(currentStep)) {
                return;
            }

            setStepAlert(currentStep, 'Saving draft...');
            const saved = await saveDraftStep(currentStep);
            if (!saved) {
                return;
            }

            if (currentStep < totalSteps) {
                showStep(currentStep + 1);
            }
        }

        function setStepAlert(stepNumber, message) {
            const alertBox = document.getElementById('step' + stepNumber + 'Alert');
            if (!alertBox) {
                return;
            }

            if (!message) {
                alertBox.textContent = '';
                alertBox.classList.remove('show');
                return;
            }

            alertBox.textContent = message;
            alertBox.classList.add('show');
        }

        function getLines(value) {
            return String(value || '')
                .split(/\r?\n/)
                .map((line) => line.trim())
                .filter((line) => line !== '');
        }

        function getCheckedValues(selector) {
            return Array.from(document.querySelectorAll(selector))
                .filter((input) => input.checked)
                .map((input) => input.value);
        }

        function validateStep(stepNumber, silent = false) {
            const fail = (message) => {
                if (!silent) {
                    setStepAlert(stepNumber, message);
                }

                return false;
            };

            if (!silent) {
                setStepAlert(stepNumber, '');
            }

            if (stepNumber === 1) {
                const year = document.getElementById('year_of_study_or_graduation').value.trim();
                const teachingMode = document.getElementById('teaching_mode').value.trim();
                const city = document.getElementById('city_location').value.trim();
                const bio = document.getElementById('bio').value.trim();
                const workStatus = document.getElementById('work_status').value.trim();
                const employerName = document.getElementById('employer_name').value.trim();
                const employerContact = document.getElementById('employer_contact').value.trim();

                if (!year) {
                    return fail('Add the year of study or graduation before continuing.');
                }

                if (!teachingMode) {
                    return fail('Select the teaching mode before continuing.');
                }

                if (!city) {
                    return fail('Add the city or location before continuing.');
                }

                if (bio.length < 40) {
                    return fail('Your bio must be at least 40 characters.');
                }

                if (workStatus === 'Employed' && !employerName) {
                    return fail('Add the employer or institution name for employed status.');
                }

                if (workStatus === 'Employed' && !employerContact) {
                    return fail('Add the employer contact for employed status.');
                }

                return true;
            }

            if (stepNumber === 2) {
                const institutions = getLines(document.getElementById('institutions').value);

                if (institutions.length === 0) {
                    return fail('Add at least one institution before continuing.');
                }

                return true;
            }

            if (stepNumber === 3) {
                const serviceAreas = getCheckedValues('.service-area-checkbox');
                const availableDays = getCheckedValues('.availability-checkbox');
                const preferredTimes = getCheckedValues('.preferred-time-checkbox');
                const references = getLines(document.getElementById('references').value);

                if (serviceAreas.length === 0) {
                    return fail('Select at least one service area before continuing.');
                }

                if (availableDays.length === 0) {
                    return fail('Select at least one available day before continuing.');
                }

                if (preferredTimes.length === 0) {
                    return fail('Select at least one preferred teaching time before continuing.');
                }

                if (references.length > 0 && references.length < 3) {
                    return fail('References are optional. If included, please provide at least three contacts, one per line.');
                }

                return true;
            }

            if (stepNumber === 4) {
                const hasProfilePicture = existingFiles.profilePicture || document.getElementById('profile_picture').files.length > 0;
                const hasNationalId = existingFiles.nationalId || document.getElementById('national_id_file').files.length > 0;
                const uploadedCertifications = document.getElementById('certification_files').files.length;
                const totalCertifications = existingFiles.certifications + uploadedCertifications;

                if (!hasProfilePicture) {
                    return fail(documentLabels.profileImageRequired);
                }

                if (!hasNationalId) {
                    return fail(documentLabels.identityRequired);
                }

                if (totalCertifications === 0) {
                    return fail(documentLabels.certificateRequired);
                }

                if (totalCertifications > 4) {
                    return fail('You can keep up to 4 certification files in total.');
                }

                return true;
            }

            return true;
        }

        function updateChipGroup(selector, targetId) {
            const container = document.getElementById(targetId);
            if (!container) {
                return;
            }

            const values = getCheckedValues(selector);
            container.innerHTML = '';

            values.forEach((value) => {
                const chip = document.createElement('span');
                chip.className = 'selected-item';
                chip.textContent = value;
                container.appendChild(chip);
            });
        }

        function renderReviewTextList(targetId, values, emptyText) {
            const container = document.getElementById(targetId);
            if (!container) {
                return;
            }

            container.innerHTML = '';

            if (!values.length) {
                const emptyItem = document.createElement('li');
                emptyItem.className = 'review-empty';
                emptyItem.textContent = emptyText;
                container.appendChild(emptyItem);
                return;
            }

            values.forEach((value) => {
                const item = document.createElement('li');
                item.textContent = value;
                container.appendChild(item);
            });
        }

        function renderReviewChipList(targetId, values, emptyText) {
            const container = document.getElementById(targetId);
            if (!container) {
                return;
            }

            container.innerHTML = '';

            if (!values.length) {
                const empty = document.createElement('span');
                empty.className = 'review-empty';
                empty.textContent = emptyText;
                container.appendChild(empty);
                return;
            }

            values.forEach((value) => {
                const chip = document.createElement('span');
                chip.className = 'review-chip';
                chip.textContent = value;
                container.appendChild(chip);
            });
        }

        function formatRate(value) {
            const cleaned = String(value || '').replace(/,/g, '').trim();
            if (!cleaned) {
                return 'Not provided';
            }

            const numeric = Number(cleaned);
            if (!Number.isFinite(numeric)) {
                return cleaned;
            }

            return 'MWK ' + numeric.toLocaleString(undefined, {
                minimumFractionDigits: numeric % 1 === 0 ? 0 : 2,
                maximumFractionDigits: 2
            });
        }

        function renderSelectedChips() {
            updateChipGroup('.service-area-checkbox', 'serviceAreaChips');
            updateChipGroup('.availability-checkbox', 'availabilityChips');
            updateChipGroup('.preferred-time-checkbox', 'preferredTimeChips');
        }

        function renderReviewState() {
            const teachingMode = document.getElementById('teaching_mode').value.trim();
            const city = document.getElementById('city_location').value.trim();
            const workStatus = document.getElementById('work_status').value.trim();
            const employerName = document.getElementById('employer_name').value.trim();
            const employerContact = document.getElementById('employer_contact').value.trim();
            const year = document.getElementById('year_of_study_or_graduation').value.trim();
            const institutions = getLines(document.getElementById('institutions').value);
            const serviceAreas = getCheckedValues('.service-area-checkbox');
            const availableDays = getCheckedValues('.availability-checkbox');
            const preferredTimes = getCheckedValues('.preferred-time-checkbox');
            const references = getLines(document.getElementById('references').value);
            const documentState = validateStep(4, true) ? 'Ready for admin review' : 'Documents still missing';
            const hourlyRate = document.getElementById('hourly_rate').value;
            const consultationRate = document.getElementById('consultation_package_rate').value;
            const dissertationRate = document.getElementById('dissertation_package_rate').value;
            const examRate = document.getElementById('exam_preparation_rate').value;

            const setText = (id, value) => {
                const element = document.getElementById(id);
                if (element) {
                    element.textContent = value;
                }
            };

            setText('reviewTeachingMode', teachingMode || '-');
            setText('reviewCity', city || '-');
            setText('reviewWorkStatus', workStatus === 'Employed' ? 'Yes' : (workStatus === 'Not Employed' ? 'No' : 'Not specified'));
            setText('reviewYear', year || '-');
            setText('reviewEmployerName', employerName || '-');
            setText('reviewEmployerContact', employerContact || '-');
            setText('reviewHourlyRate', formatRate(hourlyRate));
            setText('reviewConsultationRate', formatRate(consultationRate));
            setText('reviewDissertationRate', formatRate(dissertationRate));
            setText('reviewExamRate', formatRate(examRate));
            setText('reviewDocumentState', documentState);
            setText('reviewReferenceState', references.length > 0 ? references.length + ' provided' : 'Not provided');

            const employerRow = document.getElementById('reviewEmployerRow');
            if (employerRow) {
                employerRow.classList.toggle('hidden', workStatus !== 'Employed');
            }

            renderReviewTextList('reviewInstitutionsList', institutions, 'No institutions listed yet.');
            renderReviewChipList('reviewServiceList', serviceAreas, 'No service areas selected yet.');
            renderReviewChipList('reviewAvailableDaysList', availableDays, 'No available days selected yet.');
            renderReviewChipList('reviewPreferredTimesList', preferredTimes, 'No preferred teaching times selected yet.');
            renderReviewTextList('reviewReferencesList', references, 'No references provided. References are optional unless you choose to include them.');

            const completenessBadge = document.getElementById('reviewCompletenessBadge');
            if (completenessBadge) {
                const step1Ready = validateStep(1, true);
                const step2Ready = validateStep(2, true);
                const step3Ready = validateStep(3, true);
                const step4Ready = validateStep(4, true);
                const isReady = step1Ready && step2Ready && step3Ready && step4Ready;

                completenessBadge.innerHTML = isReady
                    ? '<span class="tag tag-success"><i class="fas fa-check"></i>Ready to submit</span>'
                    : '<span class="tag tag-warning"><i class="fas fa-circle-exclamation"></i>Complete all required fields</span>';
            }
        }

        function wireUploadInput(inputId, existingCountKey = null) {
            const input = document.getElementById(inputId);
            if (!input) {
                return;
            }

            input.addEventListener('change', function () {
                const card = document.getElementById(this.dataset.card);
                const preview = document.getElementById(this.dataset.preview);
                const hint = document.getElementById(this.dataset.hint);

                if (card) {
                    const hasFiles = this.files && this.files.length > 0;
                    const keepExisting = existingCountKey === 'certifications'
                        ? existingFiles.certifications > 0
                        : Boolean(existingFiles[existingCountKey]);
                    card.classList.toggle('has-file', hasFiles || keepExisting);
                }

                if (preview) {
                    preview.innerHTML = '';

                    if (this.files && this.files.length > 0) {
                        Array.from(this.files).forEach((file) => {
                            const row = document.createElement('span');
                            row.textContent = file.name;
                            preview.appendChild(row);
                        });
                    }
                }

                if (hint && this.files && this.files.length > 0) {
                    hint.textContent = this.files.length > 1
                        ? this.files.length + ' files selected'
                        : '1 file selected';
                }

                renderReviewState();
            });
        }

        wireUploadInput('profile_picture', 'profilePicture');
        wireUploadInput('national_id_file', 'nationalId');
        wireUploadInput('certification_files', 'certifications');

        if (workStatusField) {
            workStatusField.addEventListener('change', () => {
                syncEmployerFields(true);
                renderReviewState();
            });
        }

        [
            'year_of_study_or_graduation',
            'teaching_mode',
            'city_location',
            'bio',
            'employer_name',
            'employer_contact',
            'institutions',
            'references',
            'hourly_rate',
            'consultation_package_rate',
            'dissertation_package_rate',
            'exam_preparation_rate'
        ].forEach((id) => {
            const field = document.getElementById(id);
            if (field) {
                field.addEventListener('input', renderReviewState);
                field.addEventListener('change', renderReviewState);
            }
        });

        document.querySelectorAll('.service-area-checkbox, .availability-checkbox, .preferred-time-checkbox').forEach((input) => {
            input.addEventListener('change', () => {
                renderSelectedChips();
                renderReviewState();
            });
        });

        stepIndicators.forEach((indicator) => {
            indicator.addEventListener('click', async () => {
                const targetStep = Number(indicator.dataset.step || '1');
                if (currentStep <= 0) {
                    startWizard(targetStep);
                    return;
                }

                if (targetStep === currentStep) {
                    return;
                }

                if (targetStep > currentStep && !validateStep(currentStep)) {
                    return;
                }

                if (targetStep > currentStep) {
                    setStepAlert(currentStep, 'Saving draft...');
                }

                const saved = await saveDraftStep(currentStep);
                if (!saved) {
                    return;
                }

                showStep(targetStep);
            });
        });

        document.querySelectorAll('[data-jump-step]').forEach((button) => {
            button.addEventListener('click', () => {
                showStep(Number(button.dataset.jumpStep || '1'));
            });
        });

        form.addEventListener('submit', function (event) {
            syncEmployerFields(true);

            if (wizardStepField) {
                wizardStepField.value = String(currentStep > 0 ? currentStep : 5);
            }

            const allValid = validateStep(1) && validateStep(2) && validateStep(3) && validateStep(4);

            if (!allValid) {
                event.preventDefault();
                if (!validateStep(1, true)) {
                    showStep(1);
                } else if (!validateStep(2, true)) {
                    showStep(2);
                } else if (!validateStep(3, true)) {
                    showStep(3);
                } else {
                    showStep(4);
                }
            }
        });

        syncEmployerFields(false);
        renderSelectedChips();
        renderReviewState();

        if (currentStep > 0) {
            startWizard(currentStep);
        } else {
            updateIndicators(0);
        }
    </script>
</body>
</html>
