<?= $this->extend('layouts/admin') ?>

<?php $active_page = 'parent_requests'; ?>
<?php $title = $title ?? 'Parent Request Details - TutorConnect Malawi'; ?>

<?php
$request = $request ?? [];
$matchedTutors = $matchedTutors ?? [];
$applications = $applications ?? [];

$formatSubjects = static function (array $subjects, int $limit = 7): string {
    if (empty($subjects)) {
        return 'Not specified';
    }

    $visible = array_slice($subjects, 0, $limit);
    $label = implode(', ', $visible);
    $remaining = count($subjects) - count($visible);

    return $remaining > 0 ? $label . ' +' . $remaining . ' more' : $label;
};

$formatName = static function (array $tutor): string {
    $name = trim((string) ($tutor['first_name'] ?? '') . ' ' . (string) ($tutor['last_name'] ?? ''));

    return $name !== '' ? $name : 'Tutor #' . ($tutor['tutor_id'] ?? $tutor['id'] ?? '');
};

$formatDate = static function ($date): string {
    return !empty($date) ? date('M d, Y H:i', strtotime((string) $date)) : 'Unknown';
};
?>

<?= $this->section('content') ?>

<div class="header-bar">
    <div>
        <h1 class="page-title"><?= esc($request['reference_code'] ?? 'Parent Request') ?></h1>
        <p class="page-subtitle">Matched users and tutor applications for this parent request.</p>
    </div>
    <a href="<?= site_url('admin/parent-requests') ?>" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Requests
    </a>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
            <i class="fas fa-user-check"></i>
        </div>
        <div class="stat-number"><?= number_format($matchedNowCount ?? count($matchedTutors)) ?></div>
        <div class="stat-label">Matched Now</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6, #1e40af);">
            <i class="fas fa-filter"></i>
        </div>
        <div class="stat-number"><?= number_format((int) ($request['matched_tutor_count'] ?? 0)) ?></div>
        <div class="stat-label">Matched On Send</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #6366f1, #4338ca);">
            <i class="fas fa-paper-plane"></i>
        </div>
        <div class="stat-number"><?= number_format((int) ($request['emailed_tutor_count'] ?? 0)) ?></div>
        <div class="stat-label">Emails Sent</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
            <i class="fas fa-handshake"></i>
        </div>
        <div class="stat-number"><?= number_format(count($applications)) ?></div>
        <div class="stat-label">Applications</div>
    </div>
</div>

<div class="content-card">
    <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-3">
        <div>
            <h4 class="mb-1">Request Summary</h4>
            <p class="text-muted mb-0">This is the information tutors receive in the broadcast email.</p>
        </div>
        <span class="badge <?= ($request['status'] ?? 'open') === 'open' ? 'bg-success' : 'bg-secondary' ?>">
            <?= esc(ucfirst((string) ($request['status'] ?? 'open'))) ?>
        </span>
    </div>

    <div class="request-summary-grid">
        <div class="summary-cell">
            <div class="summary-label">Curriculum</div>
            <div class="summary-value"><?= esc($request['curriculum'] ?? 'Not set') ?></div>
        </div>
        <div class="summary-cell">
            <div class="summary-label">Grade / Class</div>
            <div class="summary-value"><?= esc($request['grade_class'] ?? 'Not set') ?></div>
        </div>
        <div class="summary-cell">
            <div class="summary-label">Subject(s)</div>
            <div class="summary-value"><?= esc($formatSubjects($request['subjects'] ?? [], 10)) ?></div>
        </div>
        <div class="summary-cell">
            <div class="summary-label">District</div>
            <div class="summary-value"><?= esc($request['district'] ?? 'Not set') ?></div>
        </div>
        <div class="summary-cell">
            <div class="summary-label">Specific Location</div>
            <div class="summary-value"><?= esc($request['specific_location'] ?? 'Not set') ?></div>
        </div>
        <div class="summary-cell">
            <div class="summary-label">Mode</div>
            <div class="summary-value"><?= esc($request['mode_label'] ?? 'Not set') ?></div>
        </div>
        <div class="summary-cell">
            <div class="summary-label">Budget</div>
            <div class="summary-value"><?= esc($request['budget_label'] ?? 'Not set') ?></div>
        </div>
        <div class="summary-cell">
            <div class="summary-label">Parent Contact</div>
            <div class="summary-value">
                <a href="tel:<?= esc($request['parent_phone'] ?? '') ?>"><?= esc($request['parent_phone'] ?? 'No phone') ?></a>
                <div><a href="mailto:<?= esc($request['parent_email'] ?? '') ?>"><?= esc($request['parent_email'] ?? 'No email') ?></a></div>
            </div>
        </div>
    </div>

    <?php if (!empty($request['notes'])): ?>
        <div class="notes-box">
            <div class="summary-label">Notes / Special Requirements</div>
            <p><?= nl2br(esc($request['notes'])) ?></p>
        </div>
    <?php endif; ?>
</div>

<div class="content-card">
    <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-3">
        <div>
            <h4 class="mb-1">Matched Tutors</h4>
            <p class="text-muted mb-0">These tutors currently match the subject, mode, location, active status, and paid subscription rules.</p>
        </div>
        <span class="badge bg-primary"><?= number_format(count($matchedTutors)) ?> matched</span>
    </div>

    <?php if (!empty($matchedTutors)): ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Tutor</th>
                        <th>Contact</th>
                        <th>Location & Mode</th>
                        <th>Subjects</th>
                        <th>Subscription</th>
                        <th>Response</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($matchedTutors as $tutor): ?>
                        <tr>
                            <td style="min-width: 190px;">
                                <a class="fw-semibold text-decoration-none" href="<?= site_url('admin/trainers/view/' . $tutor['id']) ?>">
                                    <?= esc($formatName($tutor)) ?>
                                </a>
                                <div class="text-muted small">@<?= esc($tutor['username'] ?? 'no-username') ?></div>
                            </td>
                            <td style="min-width: 220px;">
                                <a href="mailto:<?= esc($tutor['email'] ?? '') ?>"><?= esc($tutor['email'] ?? 'No email') ?></a>
                                <div class="text-muted small">Phone: <?= esc($tutor['phone'] ?? 'Not provided') ?></div>
                                <div class="text-muted small">WhatsApp: <?= esc($tutor['whatsapp_number'] ?? 'Not provided') ?></div>
                            </td>
                            <td>
                                <div class="fw-semibold"><?= esc($tutor['district'] ?? 'Not set') ?></div>
                                <div class="text-muted small"><?= esc($tutor['location'] ?? '') ?></div>
                                <span class="badge bg-light text-dark border mt-1"><?= esc($tutor['teaching_mode'] ?? 'Not set') ?></span>
                            </td>
                            <td style="min-width: 240px;">
                                <span class="small"><?= esc($formatSubjects($tutor['subjects'] ?? [])) ?></span>
                            </td>
                            <td>
                                <div class="fw-semibold"><?= esc($tutor['subscription_plan'] ?? 'Paid') ?></div>
                                <div class="text-muted small">
                                    <?= !empty($tutor['subscription_expires_at']) ? 'Expires ' . esc(date('M d, Y', strtotime($tutor['subscription_expires_at']))) : 'Active paid subscription' ?>
                                </div>
                            </td>
                            <td>
                                <?php if (!empty($tutor['has_applied'])): ?>
                                    <span class="badge bg-success">Applied</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Not applied</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-state compact">
            <div class="empty-icon"><i class="fas fa-user-slash"></i></div>
            <h3>No Current Matches</h3>
            <p>No active paid tutors currently match this request.</p>
        </div>
    <?php endif; ?>
</div>

<div class="content-card">
    <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-3">
        <div>
            <h4 class="mb-1">Applications Received</h4>
            <p class="text-muted mb-0">Tutors are listed here after clicking Apply for this request from their email.</p>
        </div>
        <span class="badge bg-success"><?= number_format(count($applications)) ?> applied</span>
    </div>

    <?php if (!empty($applications)): ?>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Tutor</th>
                        <th>Email Used</th>
                        <th>Phone</th>
                        <th>Location</th>
                        <th>Subjects</th>
                        <th>Applied At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($applications as $application): ?>
                        <tr>
                            <td>
                                <a class="fw-semibold text-decoration-none" href="<?= site_url('admin/trainers/view/' . $application['tutor_id']) ?>">
                                    <?= esc($formatName($application)) ?>
                                </a>
                                <div class="text-muted small">@<?= esc($application['username'] ?? 'no-username') ?></div>
                            </td>
                            <td>
                                <a href="mailto:<?= esc($application['tutor_email'] ?? $application['current_email'] ?? '') ?>">
                                    <?= esc($application['tutor_email'] ?? $application['current_email'] ?? 'No email') ?>
                                </a>
                            </td>
                            <td>
                                <?= esc($application['phone'] ?? 'Not provided') ?>
                                <div class="text-muted small">WhatsApp: <?= esc($application['whatsapp_number'] ?? 'Not provided') ?></div>
                            </td>
                            <td>
                                <?= esc(trim((string) ($application['district'] ?? '') . ', ' . (string) ($application['location'] ?? ''), ', ')) ?>
                                <div class="text-muted small"><?= esc($application['teaching_mode'] ?? 'Not set') ?></div>
                            </td>
                            <td style="min-width: 220px;">
                                <span class="small"><?= esc($formatSubjects($application['subjects'] ?? [])) ?></span>
                            </td>
                            <td><?= esc($formatDate($application['applied_at'] ?? $application['created_at'] ?? null)) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-state compact">
            <div class="empty-icon"><i class="fas fa-inbox"></i></div>
            <h3>No Applications Yet</h3>
            <p>When a matched tutor applies, their details will appear here.</p>
        </div>
    <?php endif; ?>
</div>

<style>
.request-summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 14px;
}

.summary-cell,
.notes-box {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 14px;
    background: #ffffff;
}

.summary-label {
    color: var(--text-light);
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.4px;
    margin-bottom: 5px;
    text-transform: uppercase;
}

.summary-value {
    color: var(--text-dark);
    font-weight: 600;
}

.notes-box {
    margin-top: 14px;
}

.notes-box p {
    margin: 0;
    color: var(--text-dark);
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-state.compact {
    padding: 38px 20px;
}

.empty-icon {
    color: var(--text-light);
    font-size: 42px;
    margin-bottom: 16px;
}

.empty-state h3 {
    color: var(--text-dark);
    margin-bottom: 10px;
}

.empty-state p {
    color: var(--text-light);
    margin: 0;
}
</style>

<?= $this->endSection() ?>
