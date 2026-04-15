<?= $this->extend('layouts/admin') ?>

<?php $active_page = 'parent_requests'; ?>
<?php $title = $title ?? 'Parent Requests - TutorConnect Malawi'; ?>

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

$formatSubjects = static function (array $subjects): string {
    if (empty($subjects)) {
        return 'Not specified';
    }

    $visible = array_slice($subjects, 0, 4);
    $label = implode(', ', $visible);
    $remaining = count($subjects) - count($visible);

    return $remaining > 0 ? $label . ' +' . $remaining . ' more' : $label;
};
?>

<?= $this->section('content') ?>

<div class="header-bar">
    <div>
        <h1 class="page-title">Parent Requests</h1>
        <p class="page-subtitle">Track requests submitted by parents and review matched tutors.</p>
    </div>
    <a class="btn-admin" href="<?= site_url('request-teacher') ?>" target="_blank" rel="noopener">
        <i class="fas fa-plus me-2"></i>Open Request Form
    </a>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6, #1e40af);">
            <i class="fas fa-list-check"></i>
        </div>
        <div class="stat-number"><?= number_format($stats['total_count'] ?? 0) ?></div>
        <div class="stat-label">Total Requests</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
            <i class="fas fa-door-open"></i>
        </div>
        <div class="stat-number"><?= number_format($stats['open_count'] ?? 0) ?></div>
        <div class="stat-label">Open Requests</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
            <i class="fas fa-user-check"></i>
        </div>
        <div class="stat-number"><?= number_format($stats['matched_total'] ?? 0) ?></div>
        <div class="stat-label">Tutors Matched</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #6366f1, #4338ca);">
            <i class="fas fa-paper-plane"></i>
        </div>
        <div class="stat-number"><?= number_format($stats['emailed_total'] ?? 0) ?></div>
        <div class="stat-label">Emails Sent</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
            <i class="fas fa-handshake"></i>
        </div>
        <div class="stat-number"><?= number_format($stats['application_total'] ?? 0) ?></div>
        <div class="stat-label">Tutor Applications</div>
    </div>
</div>

<div class="content-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Submitted Parent Requests</h4>
            <p class="text-muted mb-0">Open a request to see the matched users and application activity.</p>
        </div>
    </div>

    <?php if (!empty($requests)): ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Request</th>
                        <th>Location</th>
                        <th>Budget</th>
                        <th>Broadcast</th>
                        <th>Applications</th>
                        <th>Submitted</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($requests as $request): ?>
                        <tr>
                            <td>
                                <strong><?= esc($request['reference_code']) ?></strong>
                                <div class="mt-2">
                                    <span class="badge <?= $statusBadge((string) ($request['status'] ?? 'open')) ?>">
                                        <?= esc(ucfirst((string) ($request['status'] ?? 'open'))) ?>
                                    </span>
                                </div>
                            </td>
                            <td style="min-width: 260px;">
                                <div class="fw-semibold"><?= esc(($request['curriculum'] ?? '') . ' - ' . ($request['grade_class'] ?? '')) ?></div>
                                <div class="text-muted small"><?= esc($formatSubjects($request['subjects'] ?? [])) ?></div>
                            </td>
                            <td>
                                <div class="fw-semibold"><?= esc($request['district'] ?? 'Not set') ?></div>
                                <div class="text-muted small"><?= esc($request['specific_location'] ?? '') ?></div>
                                <span class="badge bg-light text-dark border mt-1"><?= esc($request['mode_label'] ?? '') ?></span>
                            </td>
                            <td><?= esc($request['budget_label'] ?? 'Not set') ?></td>
                            <td>
                                <div><strong><?= number_format((int) ($request['matched_tutor_count'] ?? 0)) ?></strong> matched</div>
                                <div class="text-muted small"><?= number_format((int) ($request['emailed_tutor_count'] ?? 0)) ?> emailed</div>
                            </td>
                            <td>
                                <span class="badge bg-primary"><?= number_format((int) ($request['application_count'] ?? 0)) ?></span>
                            </td>
                            <td>
                                <?= !empty($request['created_at']) ? esc(date('M d, Y H:i', strtotime($request['created_at']))) : 'Unknown' ?>
                            </td>
                            <td class="text-end">
                                <a href="<?= site_url('admin/parent-requests/' . $request['id']) ?>" class="btn btn-outline-primary btn-sm">
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
            <div class="empty-icon"><i class="fas fa-list-check"></i></div>
            <h3>No Parent Requests Yet</h3>
            <p>Requests submitted from the parent form will appear here.</p>
        </div>
    <?php endif; ?>
</div>

<style>
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
