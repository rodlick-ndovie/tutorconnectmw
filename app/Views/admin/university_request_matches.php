<?= $this->extend('layouts/admin') ?>

<?php $active_page = 'university_request_matches'; ?>
<?php $title = $title ?? 'University Request Matches - TutorConnect Malawi'; ?>

<?php
$stats = $stats ?? [];
$requests = $requests ?? [];

$statusBadge = static function (string $status): string {
    return match ($status) {
        'open' => 'bg-success',
        'closed' => 'bg-secondary',
        'cancelled' => 'bg-danger',
        default => 'bg-info',
    };
};

$formatDate = static function ($date): string {
    return !empty($date) ? date('M d, Y H:i', strtotime((string) $date)) : 'Unknown';
};
?>

<?= $this->section('content') ?>

<div class="header-bar">
    <div>
        <h1 class="page-title">University Requests</h1>
        <p class="page-subtitle">Track academic support requests, matched university tutors, and follow-up activity.</p>
    </div>
    <a class="btn-admin" href="<?= site_url('request-tutor?type=university') ?>" target="_blank" rel="noopener">
        <i class="fas fa-plus me-2"></i>Open Request Form
    </a>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #0f766e, #115e59);">
            <i class="fas fa-list-check"></i>
        </div>
        <div class="stat-number"><?= number_format($stats['total_count'] ?? 0) ?></div>
        <div class="stat-label">Total Requests</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #16a34a, #15803d);">
            <i class="fas fa-door-open"></i>
        </div>
        <div class="stat-number"><?= number_format($stats['open_count'] ?? 0) ?></div>
        <div class="stat-label">Open Requests</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #2563eb, #1d4ed8);">
            <i class="fas fa-user-graduate"></i>
        </div>
        <div class="stat-number"><?= number_format($stats['matched_total'] ?? 0) ?></div>
        <div class="stat-label">Tutors Matched</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #7c3aed, #6d28d9);">
            <i class="fas fa-paper-plane"></i>
        </div>
        <div class="stat-number"><?= number_format($stats['emailed_total'] ?? 0) ?></div>
        <div class="stat-label">Emails Sent</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #d97706, #b45309);">
            <i class="fas fa-handshake"></i>
        </div>
        <div class="stat-number"><?= number_format($stats['accepted_total'] ?? 0) ?></div>
        <div class="stat-label">Tutor Acceptances</div>
    </div>
</div>

<div class="content-card">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h4 class="mb-1">Submitted University Requests</h4>
            <p class="text-muted mb-0">Open a request to see matched tutors, acceptances, and contact details for follow-up.</p>
        </div>
        <a href="<?= site_url('admin/university-lecture-requests') ?>" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-file-export me-1"></i>Legacy Export View
        </a>
    </div>

    <?php if (!empty($requests)): ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle admin-uni-table">
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Request</th>
                        <th>Requester</th>
                        <th>Location & Mode</th>
                        <th>Broadcast</th>
                        <th>Accepted</th>
                        <th>Submitted</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($requests as $request): ?>
                        <tr>
                            <td>
                                <strong><?= esc($request['reference_code'] ?? ('#' . ($request['id'] ?? ''))) ?></strong>
                                <div class="mt-2">
                                    <span class="badge <?= $statusBadge((string) ($request['status'] ?? 'open')) ?>">
                                        <?= esc(ucfirst((string) ($request['status'] ?? 'open'))) ?>
                                    </span>
                                </div>
                            </td>
                            <td style="min-width: 270px;">
                                <div class="fw-semibold"><?= esc($request['service_category'] ?? 'Academic support') ?></div>
                                <div class="text-muted small"><?= esc($request['topic'] ?? 'Topic not specified') ?></div>
                                <div class="text-muted small"><?= esc($request['institution'] ?? 'Institution not provided') ?></div>
                            </td>
                            <td style="min-width: 210px;">
                                <div class="fw-semibold"><?= esc($request['full_name'] ?? 'Not provided') ?></div>
                                <a class="small" href="mailto:<?= esc($request['email'] ?? '') ?>"><?= esc($request['email'] ?? 'No email') ?></a>
                                <div class="text-muted small"><?= esc($request['phone'] ?? 'No phone') ?></div>
                            </td>
                            <td>
                                <div class="fw-semibold"><?= esc($request['city_location'] ?? 'Not set') ?></div>
                                <span class="badge bg-light text-dark border mt-1"><?= esc($request['delivery_mode'] ?? 'Not set') ?></span>
                            </td>
                            <td>
                                <div><strong><?= number_format((int) ($request['matched_tutor_count'] ?? 0)) ?></strong> matched</div>
                                <div class="text-muted small"><?= number_format((int) ($request['emailed_tutor_count'] ?? 0)) ?> emailed</div>
                            </td>
                            <td style="min-width: 170px;">
                                <span class="badge bg-primary"><?= number_format((int) ($request['accepted_tutor_count'] ?? 0)) ?></span>
                                <?php if (!empty($request['accepted_tutor_names'])): ?>
                                    <div class="text-muted small mt-1"><?= esc($request['accepted_tutor_names']) ?></div>
                                <?php else: ?>
                                    <div class="text-muted small mt-1">No acceptance yet</div>
                                <?php endif; ?>
                            </td>
                            <td><?= esc($formatDate($request['created_at'] ?? null)) ?></td>
                            <td class="text-end">
                                <a href="<?= site_url('admin/university-request-matches/' . ($request['id'] ?? 0)) ?>" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i>View Matches
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if (!empty($pager)): ?>
            <div class="mt-3 text-center">
                <?= $pager->links() ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon"><i class="fas fa-building-columns"></i></div>
            <h3>No University Requests Yet</h3>
            <p>Requests from the university support form will appear here with their matched tutors.</p>
        </div>
    <?php endif; ?>
</div>

<style>
.admin-uni-table td {
    vertical-align: middle;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-icon {
    color: var(--text-light);
    font-size: 48px;
    margin-bottom: 20px;
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
