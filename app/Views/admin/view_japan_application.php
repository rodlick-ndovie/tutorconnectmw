<?= $this->extend('layouts/admin') ?>

<?php $active_page = 'japan_applications'; ?>
<?php $title = $title ?? 'Japan Application Details'; ?>
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

$status = $application['status'] ?? 'submitted';
$statusClasses = [
    'submitted' => 'bg-warning text-dark',
    'under_review' => 'bg-primary',
    'approved' => 'bg-success',
    'rejected' => 'bg-danger',
    'forwarded_to_japan' => 'bg-info text-dark',
    'interview_scheduled' => 'bg-secondary',
    'placed' => 'bg-success',
];
$statusLabel = ucwords(str_replace('_', ' ', (string) $status));
$statusBadge = $statusClasses[$status] ?? 'bg-secondary';

$submittedAt = !empty($application['submitted_at']) ? date('M d, Y H:i', strtotime($application['submitted_at'])) : 'N/A';
$fullName = (string) ($application['full_name'] ?? 'Japan Applicant');
$reference = (string) ($application['application_reference'] ?? 'N/A');
$email = (string) ($application['email'] ?? '');
$phone = (string) ($application['phone'] ?? '');
$mailTo = $email !== '' ? ('mailto:' . rawurlencode($email)) : '#';
$telTo = $phone !== '' ? ('tel:' . preg_replace('/[^0-9+]/', '', $phone)) : '#';
?>

<?= $this->section('content') ?>
<div class="header-bar">
    <div>
        <h1 class="page-title">Japan Application Details</h1>
        <p class="page-subtitle">
            <strong><?= esc($fullName) ?></strong>
            <span class="text-muted">• Ref: <?= esc($reference) ?> • Submitted: <?= esc($submittedAt) ?></span>
            <span class="badge <?= esc($statusBadge) ?>" style="margin-left: 10px;"><?= esc($statusLabel) ?></span>
        </p>
    </div>
    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
        <a href="<?= site_url('admin/japan-applications/' . $application['id'] . '/pdf') ?>" class="btn-admin" style="background: linear-gradient(135deg, #dc2626, #ef4444);">
            <i class="fas fa-file-pdf me-2"></i>Download PDF
        </a>
        <a href="<?= site_url('admin/japan-applications') ?>" class="btn-admin" style="background: linear-gradient(135deg, #64748b, #334155);">
            <i class="fas fa-arrow-left me-2"></i>Back
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="content-card">
            <h3 style="margin: 0 0 12px; font-size: 18px; font-weight: 700; color: var(--text-dark);">
                <i class="fas fa-user me-2"></i>Applicant & Contact
            </h3>
            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded">
                                        <div class="text-muted small">Email</div>
                                        <div class="fw-semibold">
                                            <?= esc($email ?: 'N/A') ?>
                                            <?php if ($email !== ''): ?>
                                                <a class="ms-2 small" href="<?= esc($mailTo) ?>">Send</a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded">
                                        <div class="text-muted small">Phone</div>
                                        <div class="fw-semibold">
                                            <?= esc($phone ?: 'N/A') ?>
                                            <?php if ($phone !== ''): ?>
                                                <a class="ms-2 small" href="<?= esc($telTo) ?>">Call</a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 bg-light rounded">
                                        <div class="text-muted small">Date of Birth</div>
                                        <div class="fw-semibold"><?= esc($application['date_of_birth'] ?? 'N/A') ?></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 bg-light rounded">
                                        <div class="text-muted small">Nationality</div>
                                        <div class="fw-semibold"><?= esc($application['nationality'] ?? 'N/A') ?></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 bg-light rounded">
                                        <div class="text-muted small">Gender</div>
                                        <div class="fw-semibold"><?= esc($application['gender'] ?? 'N/A') ?></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 bg-light rounded">
                                        <div class="text-muted small">Age</div>
                                        <div class="fw-semibold"><?= esc($application['age'] ?? 'N/A') ?></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 bg-light rounded">
                                        <div class="text-muted small">Valid Passport</div>
                                        <div class="fw-semibold"><?= !empty($application['has_valid_passport']) ? 'Yes' : 'No' ?></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 bg-light rounded">
                                        <div class="text-muted small">Willing to Renew</div>
                                        <div class="fw-semibold"><?= !empty($application['willing_to_renew_passport']) ? 'Yes' : 'No' ?></div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="p-3 bg-light rounded">
                                        <div class="text-muted small">Current Address</div>
                                        <div class="fw-semibold"><?= nl2br(esc($application['current_address'] ?? '')) ?></div>
                                    </div>
                                </div>
            </div>
        </div>

        <div class="content-card">
            <h3 style="margin: 0 0 12px; font-size: 18px; font-weight: 700; color: var(--text-dark);">
                <i class="fas fa-passport me-2"></i>Passport
            </h3>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded">
                        <div class="text-muted small">Passport Number</div>
                        <div class="fw-semibold"><?= esc($application['passport_number'] ?? 'N/A') ?></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded">
                        <div class="text-muted small">Passport Expiry</div>
                        <div class="fw-semibold"><?= esc($application['passport_expiry_date'] ?? 'N/A') ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-card">
            <h3 style="margin: 0 0 12px; font-size: 18px; font-weight: 700; color: var(--text-dark);">
                <i class="fas fa-graduation-cap me-2"></i>Education
            </h3>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded">
                        <div class="text-muted small">Highest Qualification</div>
                        <div class="fw-semibold"><?= esc($application['highest_qualification'] ?? 'N/A') ?></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded">
                        <div class="text-muted small">Degree Obtained</div>
                        <div class="fw-semibold"><?= esc($application['degree_obtained'] ?? 'N/A') ?></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded">
                        <div class="text-muted small">Field of Study</div>
                        <div class="fw-semibold"><?= esc($application['field_of_study'] ?? 'N/A') ?></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded">
                        <div class="text-muted small">Institution</div>
                        <div class="fw-semibold"><?= esc($application['institution_name'] ?? 'N/A') ?></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded">
                        <div class="text-muted small">Year of Completion</div>
                        <div class="fw-semibold"><?= esc($application['year_of_completion'] ?? 'N/A') ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-card">
            <h3 style="margin: 0 0 12px; font-size: 18px; font-weight: 700; color: var(--text-dark);">
                <i class="fas fa-chalkboard-teacher me-2"></i>Teaching Background
            </h3>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded">
                        <div class="text-muted small">Has Teaching Certificate</div>
                        <div class="fw-semibold"><?= !empty($application['has_teaching_certificate']) ? 'Yes' : 'No' ?></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded">
                        <div class="text-muted small">Certificate Details</div>
                        <div class="fw-semibold"><?= esc($application['teaching_certificate_details'] ?: 'N/A') ?></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded">
                        <div class="text-muted small">Has Teaching Experience</div>
                        <div class="fw-semibold"><?= !empty($application['has_teaching_experience']) ? 'Yes' : 'No' ?></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded">
                        <div class="text-muted small">Experience Duration</div>
                        <div class="fw-semibold"><?= esc($application['teaching_experience_duration'] ?: 'N/A') ?></div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="p-3 bg-light rounded">
                        <div class="text-muted small">Where Taught</div>
                        <div class="fw-semibold"><?= esc($application['teaching_experience_location'] ?: 'N/A') ?></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded">
                        <div class="text-muted small">Subjects Taught</div>
                        <div class="fw-semibold"><?= esc($application['subjects_taught'] ?: 'N/A') ?></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded">
                        <div class="text-muted small">Level Taught</div>
                        <div class="fw-semibold"><?= esc($application['level_taught'] ?: 'N/A') ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-card">
            <h3 style="margin: 0 0 12px; font-size: 18px; font-weight: 700; color: var(--text-dark);">
                <i class="fas fa-coins me-2"></i>Financial Readiness
            </h3>
            <div class="row g-3">
                <?php foreach ($financialReadinessLabels as $key => $label): ?>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded h-100">
                            <div class="text-muted small"><?= esc($label) ?></div>
                            <div class="fw-semibold"><?= !empty($application['financial_readiness'][$key]) ? 'Yes' : 'No' ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="content-card">
            <h3 style="margin: 0 0 12px; font-size: 18px; font-weight: 700; color: var(--text-dark);">
                <i class="fas fa-users me-2"></i>Referees
            </h3>
            <?php if (!empty($application['referees'])): ?>
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead>
                            <tr class="table-light">
                                <th style="width: 50px;">No.</th>
                                <th>Type</th>
                                <th>Relationship</th>
                                <th>Name</th>
                                <th>Organisation</th>
                                <th>Phone</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; foreach ($application['referees'] as $ref): ?>
                                <tr>
                                    <td class="fw-semibold"><?= esc((string) $i) ?></td>
                                    <td><?= esc($ref['type'] ?? 'N/A') ?></td>
                                    <td><?= esc($ref['relationship_title'] ?? 'N/A') ?></td>
                                    <td><?= esc($ref['full_name'] ?? 'N/A') ?></td>
                                    <td><?= esc($ref['organisation'] ?? 'N/A') ?></td>
                                    <td><?= esc($ref['phone'] ?? 'N/A') ?></td>
                                    <td><?= esc($ref['email'] ?? 'N/A') ?></td>
                                </tr>
                            <?php $i++; endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-muted">No referees found on this record.</div>
            <?php endif; ?>
        </div>

        <div class="content-card">
            <h3 style="margin: 0 0 12px; font-size: 18px; font-weight: 700; color: var(--text-dark);">
                <i class="fas fa-paperclip me-2"></i>Supporting Files
            </h3>
            <?php if (!empty($application['documents_already_shared'])): ?>
                <div class="alert alert-info">
                    Applicant indicated that supporting documents were already shared with TutorConnect Malawi.
                    <?php if (!empty($application['shared_documents_note'])): ?>
                        <div class="mt-2"><strong>Note:</strong> <?= esc($application['shared_documents_note']) ?></div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php
            $fileLinks = [
                'Degree / Transcript' => $application['degree_document_path'] ?? null,
                'Extra Transcript' => $application['transcript_document_path'] ?? null,
                'Passport Copy' => $application['passport_copy_path'] ?? null,
                'CV / Resume' => $application['cv_document_path'] ?? null,
                'Teaching Certificate' => $application['teaching_certificate_path'] ?? null,
            ];
            ?>
            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead>
                        <tr class="table-light">
                            <th>Document</th>
                            <th>Status</th>
                            <th>Link</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($fileLinks as $label => $path): ?>
                            <tr>
                                <td class="fw-semibold"><?= esc($label) ?></td>
                                <td>
                                    <?php if (!empty($path)): ?>
                                        <span class="badge bg-success">Uploaded</span>
                                    <?php elseif (!empty($application['documents_already_shared'])): ?>
                                        <span class="badge bg-info text-dark">Previously Shared</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Not Provided</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($path)): ?>
                                        <a href="<?= base_url($path) ?>" target="_blank" rel="noopener">Open file</a>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="content-card">
            <h3 style="margin: 0 0 12px; font-size: 18px; font-weight: 700; color: var(--text-dark);">
                <i class="fas fa-clipboard-check me-2"></i>Declarations
            </h3>
            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead>
                        <tr class="table-light">
                            <th style="width: 70px;">No.</th>
                            <th>Declaration</th>
                            <th style="width: 180px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; foreach ($declarationLabels as $key => $label): ?>
                            <tr>
                                <td class="fw-semibold"><?= esc((string) $i) ?></td>
                                <td><?= esc($label) ?></td>
                                <td><?= !empty($application['declarations'][$key]) ? '<span class="badge bg-success">Confirmed</span>' : '<span class="badge bg-secondary">Not confirmed</span>' ?></td>
                            </tr>
                        <?php $i++; endforeach; ?>
                        <tr>
                            <td class="fw-semibold">—</td>
                            <td>Signature Name</td>
                            <td><?= esc($application['declarations']['signature_name'] ?? 'N/A') ?></td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">—</td>
                            <td>Signature Date</td>
                            <td><?= esc($application['declarations']['signature_date'] ?? 'N/A') ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="content-card">
            <h3 style="margin: 0 0 12px; font-size: 18px; font-weight: 700; color: var(--text-dark);">
                <i class="fas fa-sliders me-2"></i>Status & Notes
            </h3>
            <form method="post" action="<?= site_url('admin/japan-applications/update/' . $application['id']) ?>">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Application Status</label>
                    <select name="status" class="form-control" required>
                        <?php foreach ($statusOptions as $value => $label): ?>
                            <option value="<?= esc($value) ?>" <?= ($application['status'] ?? '') === $value ? 'selected' : '' ?>><?= esc($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Admin Notes</label>
                    <textarea name="admin_notes" rows="7" class="form-control" placeholder="Add screening notes, next steps, or interview updates"><?= esc($application['admin_notes'] ?? '') ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-save me-2"></i>Save Update
                </button>
            </form>
        </div>

        <div class="content-card">
            <h3 style="margin: 0 0 12px; font-size: 18px; font-weight: 700; color: var(--text-dark);">
                <i class="fas fa-circle-info me-2"></i>Quick Summary
            </h3>
            <div class="d-grid gap-2">
                <div class="p-3 bg-light rounded">
                    <div class="text-muted small">Current Status</div>
                    <div class="fw-semibold"><?= esc($statusLabel) ?></div>
                </div>
                <div class="p-3 bg-light rounded">
                    <div class="text-muted small">Submitted</div>
                    <div class="fw-semibold"><?= esc($submittedAt) ?></div>
                </div>
                <div class="p-3 bg-light rounded">
                    <div class="text-muted small">Application Fee</div>
                    <div class="fw-semibold">MK <?= number_format((float) ($application['application_fee_amount'] ?? 10000), 2) ?></div>
                </div>
                <div class="p-3 bg-light rounded">
                    <div class="text-muted small">Processing Fee</div>
                    <div class="fw-semibold">MK <?= number_format((float) ($application['processing_fee_amount'] ?? 350000), 2) ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
