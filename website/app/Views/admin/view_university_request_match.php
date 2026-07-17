<?= $this->extend('layouts/admin') ?>

<?php $active_page = 'university_request_matches'; ?>
<?php $title = $title ?? 'University Request Match Details - TutorConnect Malawi'; ?>

<?php
$request = $request ?? [];
$matchedTutors = $matchedTutors ?? [];
$acceptances = $acceptances ?? [];

$formatDate = static function ($date): string {
    return !empty($date) ? date('M d, Y H:i', strtotime((string) $date)) : 'Unknown';
};

$formatMoney = static function ($amount): string {
    if ($amount === null || $amount === '' || (float) $amount <= 0) {
        return 'Not set';
    }

    return 'MWK ' . number_format((float) $amount, 0);
};

$formatServices = static function (array $services, int $limit = 6): string {
    if ($services === []) {
        return 'Not specified';
    }

    $visible = array_slice($services, 0, $limit);
    $label = implode(', ', $visible);
    $remaining = count($services) - count($visible);

    return $remaining > 0 ? $label . ' +' . $remaining . ' more' : $label;
};
?>

<?= $this->section('content') ?>

<div class="header-bar">
    <div>
        <h1 class="page-title"><?= esc($request['reference_code'] ?? 'University Request') ?></h1>
        <p class="page-subtitle">Matched university tutors, acceptance status, and follow-up details.</p>
    </div>
    <a href="<?= site_url('admin/university-request-matches') ?>" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Requests
    </a>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #0f766e, #115e59);">
            <i class="fas fa-user-graduate"></i>
        </div>
        <div class="stat-number"><?= number_format($matchedNowCount ?? count($matchedTutors)) ?></div>
        <div class="stat-label">Matched Now</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #2563eb, #1d4ed8);">
            <i class="fas fa-filter"></i>
        </div>
        <div class="stat-number"><?= number_format((int) ($request['matched_tutor_count'] ?? 0)) ?></div>
        <div class="stat-label">Matched On Send</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #7c3aed, #6d28d9);">
            <i class="fas fa-paper-plane"></i>
        </div>
        <div class="stat-number"><?= number_format((int) ($request['emailed_tutor_count'] ?? 0)) ?></div>
        <div class="stat-label">Emails Sent</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #16a34a, #15803d);">
            <i class="fas fa-handshake"></i>
        </div>
        <div class="stat-number"><?= number_format(count($acceptances)) ?></div>
        <div class="stat-label">Accepted</div>
    </div>
</div>

<div class="content-card">
    <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-3">
        <div>
            <h4 class="mb-1">Request Summary</h4>
            <p class="text-muted mb-0">This is the request used to select and notify matching university tutors.</p>
        </div>
        <span class="badge <?= ($request['status'] ?? 'open') === 'open' ? 'bg-success' : 'bg-secondary' ?>">
            <?= esc(ucfirst((string) ($request['status'] ?? 'open'))) ?>
        </span>
    </div>

    <div class="request-summary-grid">
        <div class="summary-cell">
            <div class="summary-label">Requester</div>
            <div class="summary-value"><?= esc($request['full_name'] ?? 'Not provided') ?></div>
        </div>
        <div class="summary-cell">
            <div class="summary-label">Contact</div>
            <div class="summary-value">
                <a href="mailto:<?= esc($request['email'] ?? '') ?>"><?= esc($request['email'] ?? 'No email') ?></a>
                <div><a href="tel:<?= esc($request['phone'] ?? '') ?>"><?= esc($request['phone'] ?? 'No phone') ?></a></div>
            </div>
        </div>
        <div class="summary-cell">
            <div class="summary-label">Institution</div>
            <div class="summary-value"><?= esc($request['institution'] ?? 'Not provided') ?></div>
        </div>
        <div class="summary-cell">
            <div class="summary-label">Service Category</div>
            <div class="summary-value"><?= esc($request['service_category'] ?? 'Not set') ?></div>
        </div>
        <div class="summary-cell">
            <div class="summary-label">Topic</div>
            <div class="summary-value"><?= esc($request['topic'] ?? 'Not set') ?></div>
        </div>
        <div class="summary-cell">
            <div class="summary-label">Mode</div>
            <div class="summary-value"><?= esc($request['delivery_mode'] ?? 'Not set') ?></div>
        </div>
        <div class="summary-cell">
            <div class="summary-label">Location</div>
            <div class="summary-value"><?= esc($request['city_location'] ?? 'Not set') ?></div>
        </div>
        <div class="summary-cell">
            <div class="summary-label">Preferred Schedule</div>
            <div class="summary-value">
                <?= esc($request['preferred_date'] ?? 'Date not set') ?>
                <div class="text-muted small"><?= esc($request['preferred_time'] ?? 'Time not set') ?></div>
            </div>
        </div>
        <div class="summary-cell">
            <div class="summary-label">Budget</div>
            <div class="summary-value"><?= esc($request['budget_range'] ?? 'Not set') ?></div>
        </div>
        <div class="summary-cell">
            <div class="summary-label">Submitted</div>
            <div class="summary-value"><?= esc($formatDate($request['created_at'] ?? null)) ?></div>
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
            <h4 class="mb-1">Matched University Tutors</h4>
            <p class="text-muted mb-0">Only approved university tutors with active plans are shown here.</p>
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
                        <th>Service Areas</th>
                        <th>Active Plan</th>
                        <th>Response</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($matchedTutors as $tutor): ?>
                        <tr>
                            <td style="min-width: 210px;">
                                <a class="fw-semibold text-decoration-none" href="<?= site_url('admin/view-university-college-tutor/' . ($tutor['id'] ?? 0)) ?>">
                                    <?= esc($tutor['full_name'] ?? ('Tutor #' . ($tutor['id'] ?? ''))) ?>
                                </a>
                                <div class="text-muted small"><?= esc($tutor['reference_code'] ?? '') ?></div>
                            </td>
                            <td style="min-width: 220px;">
                                <a href="mailto:<?= esc($tutor['email'] ?? '') ?>"><?= esc($tutor['email'] ?? 'No email') ?></a>
                                <div class="text-muted small">Phone: <?= esc($tutor['phone'] ?? 'Not provided') ?></div>
                            </td>
                            <td>
                                <div class="fw-semibold"><?= esc($tutor['city_location'] ?? 'Not set') ?></div>
                                <span class="badge bg-light text-dark border mt-1"><?= esc($tutor['teaching_mode'] ?? 'Not set') ?></span>
                            </td>
                            <td style="min-width: 260px;">
                                <?php if (!empty($tutor['service_area_groups'])): ?>
                                    <?php foreach ($tutor['service_area_groups'] as $group => $services): ?>
                                        <div class="service-group">
                                            <span><?= esc($group) ?></span>
                                            <small><?= esc($formatServices($services, 4)) ?></small>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span class="small text-muted"><?= esc($formatServices($tutor['service_areas'] ?? [])) ?></span>
                                <?php endif; ?>
                            </td>
                            <td style="min-width: 180px;">
                                <div class="fw-semibold"><?= esc($tutor['plan_name'] ?? 'Active plan') ?></div>
                                <div class="text-muted small"><?= esc(ucfirst((string) ($tutor['search_ranking'] ?? 'standard'))) ?> ranking</div>
                                <div class="text-muted small"><?= esc($formatMoney($tutor['plan_price'] ?? null)) ?></div>
                                <div class="text-muted small">
                                    <?= !empty($tutor['current_period_end']) ? 'Expires ' . esc(date('M d, Y', strtotime((string) $tutor['current_period_end']))) : 'Active' ?>
                                </div>
                            </td>
                            <td>
                                <?php if (!empty($tutor['has_accepted'])): ?>
                                    <span class="badge bg-success">Accepted</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Not accepted</span>
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
            <p>No approved university tutors with active plans currently match this request.</p>
        </div>
    <?php endif; ?>
</div>

<div class="content-card">
    <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-3">
        <div>
            <h4 class="mb-1">Accepted Tutors for Follow-up</h4>
            <p class="text-muted mb-0">Tutors appear here after accepting the request from the email link.</p>
        </div>
        <span class="badge bg-success"><?= number_format(count($acceptances)) ?> accepted</span>
    </div>

    <?php if (!empty($acceptances)): ?>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Tutor</th>
                        <th>Contact</th>
                        <th>Location & Mode</th>
                        <th>Status</th>
                        <th>Accepted At</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($acceptances as $acceptance): ?>
                        <tr>
                            <td class="fw-semibold"><?= esc($acceptance['full_name'] ?? 'University Tutor') ?></td>
                            <td>
                                <a href="mailto:<?= esc($acceptance['email'] ?? '') ?>"><?= esc($acceptance['email'] ?? 'No email') ?></a>
                                <div class="text-muted small">Phone: <?= esc($acceptance['phone'] ?? 'Not provided') ?></div>
                            </td>
                            <td>
                                <div><?= esc($acceptance['city_location'] ?? 'Not set') ?></div>
                                <span class="badge bg-light text-dark border mt-1"><?= esc($acceptance['teaching_mode'] ?? 'Not set') ?></span>
                            </td>
                            <td><span class="badge bg-success"><?= esc(ucfirst((string) ($acceptance['status'] ?? 'accepted'))) ?></span></td>
                            <td><?= esc($formatDate($acceptance['accepted_at'] ?? $acceptance['created_at'] ?? null)) ?></td>
                            <td class="text-end">
                                <a class="btn btn-outline-primary btn-sm" href="<?= site_url('admin/view-university-college-tutor/' . ($acceptance['tutor_id'] ?? 0)) ?>">
                                    <i class="fas fa-eye me-1"></i>View Tutor
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-state compact">
            <div class="empty-icon"><i class="fas fa-inbox"></i></div>
            <h3>No Tutor Acceptance Yet</h3>
            <p>When a matched university tutor accepts from the email, admin can follow up from this section.</p>
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
    background: #fff;
}

.summary-label {
    color: #6b7280;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: .04em;
    margin-bottom: 6px;
    text-transform: uppercase;
}

.summary-value {
    color: #111827;
    font-weight: 600;
    overflow-wrap: anywhere;
}

.notes-box {
    margin-top: 14px;
}

.notes-box p {
    margin: 0;
    color: #374151;
}

.service-group {
    border-left: 3px solid var(--primary-color);
    padding-left: 10px;
    margin-bottom: 8px;
}

.service-group span {
    display: block;
    font-weight: 700;
    color: #111827;
}

.service-group small {
    color: #6b7280;
}

.empty-state {
    text-align: center;
    padding: 56px 20px;
}

.empty-state.compact {
    padding: 36px 20px;
}

.empty-icon {
    color: var(--text-light);
    font-size: 42px;
    margin-bottom: 16px;
}

.empty-state h3 {
    color: var(--text-dark);
    margin-bottom: 8px;
}

.empty-state p {
    color: var(--text-light);
    margin: 0;
}
</style>

<?= $this->endSection() ?>
