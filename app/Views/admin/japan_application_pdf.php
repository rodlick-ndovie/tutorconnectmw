<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Japan Application</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 11px; color: #111827; }
        .header { padding: 10px 0; border-bottom: 2px solid #e5e7eb; margin-bottom: 14px; }
        .title { font-size: 16px; font-weight: 700; margin: 0; }
        .subtitle { margin: 4px 0 0; color: #6b7280; }
        h2 { font-size: 13px; margin: 16px 0 8px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 7px 9px; border: 1px solid #e5e7eb; vertical-align: top; }
        th { background: #f9fafb; width: 28%; }
        .muted { color: #6b7280; }
        .small { font-size: 10px; }
        .section { margin-top: 12px; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 999px; background: #eef2ff; }
    </style>
</head>
<body>
<?php
$application = $application ?? [];
$ref = (string) ($application['application_reference'] ?? '');
$siteName = function_exists('site_setting') ? (site_setting('site_name', 'TutorConnect Malawi') ?? 'TutorConnect Malawi') : 'TutorConnect Malawi';
$supportPhone = function_exists('site_setting') ? (site_setting('support_phone', '+265 992 313 978') ?? '+265 992 313 978') : '+265 992 313 978';
$supportEmail = function_exists('site_setting') ? (site_setting('contact_email', 'info@tutorconnectmw.com') ?? 'info@tutorconnectmw.com') : 'info@tutorconnectmw.com';
$supportAddress = function_exists('site_setting') ? (site_setting('support_address', '') ?? '') : '';
$siteLogo = function_exists('site_setting') ? (site_setting('site_logo', '') ?? '') : '';

$logoDataUri = '';
if ($siteLogo !== '') {
    $logoPath = ROOTPATH . 'public/uploads/' . ltrim($siteLogo, '/\\');
    if (is_file($logoPath)) {
        $ext = strtolower(pathinfo($logoPath, PATHINFO_EXTENSION));
        $mime = match ($ext) {
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'svg' => 'image/svg+xml',
            'webp' => 'image/webp',
            default => ''
        };
        if ($mime !== '') {
            $logoDataUri = 'data:' . $mime . ';base64,' . base64_encode((string) file_get_contents($logoPath));
        }
    }
}
?>

<div class="header">
    <div style="display: table; width: 100%;">
        <div style="display: table-cell; vertical-align: middle;">
            <p class="title">Teach in Japan Application</p>
            <p class="subtitle">
                Reference: <strong><?= esc($ref !== '' ? $ref : 'N/A') ?></strong>
                <span class="muted">| Generated: <?= esc(date('Y-m-d H:i:s')) ?></span>
            </p>
            <p class="subtitle">
                <strong><?= esc($siteName) ?></strong>
                <span class="muted">| <?= esc($supportPhone) ?> | <?= esc($supportEmail) ?><?= $supportAddress !== '' ? ' | ' . esc($supportAddress) : '' ?></span>
            </p>
        </div>
        <div style="display: table-cell; text-align: right; vertical-align: middle;">
            <?php if ($logoDataUri !== ''): ?>
                <img src="<?= esc($logoDataUri) ?>" alt="<?= esc($siteName) ?>" style="height: 40px;">
            <?php else: ?>
                <span class="muted"><?= esc($siteName) ?></span>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="section">
    <h2>Applicant Details</h2>
    <table>
        <tr><th>Full Name</th><td><?= esc((string) ($application['full_name'] ?? '')) ?></td></tr>
        <tr><th>Email</th><td><?= esc((string) ($application['email'] ?? '')) ?></td></tr>
        <tr><th>Phone</th><td><?= esc((string) ($application['phone'] ?? '')) ?></td></tr>
        <tr><th>Nationality</th><td><?= esc((string) ($application['nationality'] ?? '')) ?></td></tr>
        <tr><th>Gender</th><td><?= esc((string) ($application['gender'] ?? '')) ?></td></tr>
        <tr><th>Date of Birth</th><td><?= esc((string) ($application['date_of_birth'] ?? '')) ?></td></tr>
        <tr><th>Age</th><td><?= esc((string) ($application['age'] ?? '')) ?></td></tr>
        <tr><th>Current Address</th><td><?= nl2br(esc((string) ($application['current_address'] ?? ''))) ?></td></tr>
    </table>
</div>

<div class="section">
    <h2>Passport & Education</h2>
    <table>
        <tr><th>Passport Number</th><td><?= esc((string) ($application['passport_number'] ?? '')) ?></td></tr>
        <tr><th>Passport Expiry</th><td><?= esc((string) ($application['passport_expiry_date'] ?? '')) ?></td></tr>
        <tr><th>Highest Qualification</th><td><?= esc((string) ($application['highest_qualification'] ?? '')) ?></td></tr>
        <tr><th>Degree Obtained</th><td><?= esc((string) ($application['degree_obtained'] ?? '')) ?></td></tr>
        <tr><th>Field of Study</th><td><?= esc((string) ($application['field_of_study'] ?? '')) ?></td></tr>
        <tr><th>Institution</th><td><?= esc((string) ($application['institution_name'] ?? '')) ?></td></tr>
        <tr><th>Year of Completion</th><td><?= esc((string) ($application['year_of_completion'] ?? '')) ?></td></tr>
    </table>
</div>

<div class="section">
    <h2>Teaching Background</h2>
    <table>
        <tr><th>Has Teaching Certificate</th><td><?= !empty($application['has_teaching_certificate']) ? 'Yes' : 'No' ?></td></tr>
        <tr><th>Certificate Details</th><td><?= esc((string) ($application['teaching_certificate_details'] ?? '')) ?></td></tr>
        <tr><th>Has Teaching Experience</th><td><?= !empty($application['has_teaching_experience']) ? 'Yes' : 'No' ?></td></tr>
        <tr><th>Where Taught</th><td><?= esc((string) ($application['teaching_experience_location'] ?? '')) ?></td></tr>
        <tr><th>Subjects Taught</th><td><?= esc((string) ($application['subjects_taught'] ?? '')) ?></td></tr>
        <tr><th>Level Taught</th><td><?= esc((string) ($application['level_taught'] ?? '')) ?></td></tr>
        <tr><th>Duration</th><td><?= esc((string) ($application['teaching_experience_duration'] ?? '')) ?></td></tr>
    </table>
</div>

<div class="section">
    <h2>Submission</h2>
    <table>
        <tr>
            <th>Status</th>
            <td><span class="badge"><?= esc(ucwords(str_replace('_', ' ', (string) ($application['status'] ?? 'submitted')))) ?></span></td>
        </tr>
        <tr><th>Submitted At</th><td><?= esc((string) ($application['submitted_at'] ?? '')) ?></td></tr>
        <tr><th>Admin Notes</th><td><?= nl2br(esc((string) ($application['admin_notes'] ?? ''))) ?></td></tr>
    </table>
</div>

<div class="section">
    <h2>Supporting Files (paths)</h2>
    <p class="muted small">These are saved paths from the application record.</p>
    <table>
        <tr><th>Degree / Transcript</th><td><?= esc((string) ($application['degree_document_path'] ?? '')) ?></td></tr>
        <tr><th>Extra Transcript</th><td><?= esc((string) ($application['transcript_document_path'] ?? '')) ?></td></tr>
        <tr><th>Passport Copy</th><td><?= esc((string) ($application['passport_copy_path'] ?? '')) ?></td></tr>
        <tr><th>CV / Resume</th><td><?= esc((string) ($application['cv_document_path'] ?? '')) ?></td></tr>
        <tr><th>Teaching Certificate</th><td><?= esc((string) ($application['teaching_certificate_path'] ?? '')) ?></td></tr>
    </table>
</div>

<?php
$financialReadinessLabels = [
    'aware_ticket_or_accommodation_optional' => 'Understands flights or accommodation may not be provided',
    'willing_to_pay_air_ticket' => 'Willing to pay for own air ticket if required',
    'willing_to_arrange_accommodation' => 'Willing to arrange accommodation if required',
];
$declarationLabels = [
    'referee_consent' => 'Consented to referee verification',
    'confirm_age_range' => 'Confirmed age requirement',
    'confirm_valid_passport' => 'Confirmed valid passport',
    'acknowledge_application_fee' => 'Accepted MK 10,000 non-refundable application fee',
    'acknowledge_processing_fee' => 'Accepted processing fee terms',
    'consent_data_sharing' => 'Consented to placement data sharing',
    'confirm_truthfulness' => 'Confirmed information is true and correct',
    'confirm_voluntary_participation' => 'Confirmed voluntary participation',
];
$financial = $application['financial_readiness'] ?? [];
$referees = $application['referees'] ?? [];
$declarations = $application['declarations'] ?? [];
?>

<div class="section">
    <h2>Financial Readiness</h2>
    <table>
        <?php foreach ($financialReadinessLabels as $key => $label): ?>
            <tr>
                <th><?= esc($label) ?></th>
                <td><?= !empty($financial[$key]) ? 'Yes' : 'No' ?></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <th>Documents already shared</th>
            <td><?= !empty($application['documents_already_shared']) ? 'Yes' : 'No' ?></td>
        </tr>
        <tr>
            <th>Shared documents note</th>
            <td><?= nl2br(esc((string) ($application['shared_documents_note'] ?? ''))) ?></td>
        </tr>
    </table>
</div>

<div class="section">
    <h2>Referees</h2>
    <?php if (!empty($referees)): ?>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 18%;">Type</th>
                    <th style="width: 22%;">Relationship / Position</th>
                    <th style="width: 22%;">Name</th>
                    <th style="width: 18%;">Phone</th>
                    <th style="width: 20%;">Email</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                <?php foreach ($referees as $ref): ?>
                    <tr>
                        <td><?= esc((string) $i) ?></td>
                        <td><?= esc((string) ($ref['type'] ?? '')) ?></td>
                        <td><?= esc((string) ($ref['relationship_title'] ?? '')) ?></td>
                        <td>
                            <?= esc((string) ($ref['full_name'] ?? '')) ?>
                            <?php if (!empty($ref['organisation'])): ?>
                                <div class="muted small"><?= esc((string) ($ref['organisation'] ?? '')) ?></div>
                            <?php endif; ?>
                        </td>
                        <td><?= esc((string) ($ref['phone'] ?? '')) ?></td>
                        <td><?= esc((string) ($ref['email'] ?? '')) ?></td>
                    </tr>
                    <?php $i++; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="muted">No referees found on this record.</p>
    <?php endif; ?>
</div>

<div class="section">
    <h2>Declarations</h2>
    <table>
        <?php $i = 1; ?>
        <?php foreach ($declarationLabels as $key => $label): ?>
            <tr>
                <th><?= esc((string) $i) ?>. <?= esc($label) ?></th>
                <td><?= !empty($declarations[$key]) ? 'Confirmed' : 'Not confirmed' ?></td>
            </tr>
            <?php $i++; ?>
        <?php endforeach; ?>
        <tr>
            <th>Signature Name</th>
            <td><?= esc((string) ($declarations['signature_name'] ?? '')) ?></td>
        </tr>
        <tr>
            <th>Signature Date</th>
            <td><?= esc((string) ($declarations['signature_date'] ?? '')) ?></td>
        </tr>
    </table>
</div>

</body>
</html>

