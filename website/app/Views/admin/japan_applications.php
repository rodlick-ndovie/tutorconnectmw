<?= $this->extend('layouts/admin') ?>

<?php $active_page = 'japan_applications'; ?>
<?php $title = $title ?? 'Japan Applications'; ?>

<?= $this->section('content') ?>
<div class="header-bar">
    <div>
        <h1 class="page-title">Japan Applications</h1>
        <p class="page-subtitle">Review and manage Teach in Japan submissions</p>
    </div>
    <div style="display: flex; gap: 12px;">
        <a class="btn-admin" href="<?= site_url('admin/japan-applications/export') . (!empty($statusFilter) || !empty($searchQuery) ? ('?' . http_build_query(['status' => $statusFilter, 'q' => $searchQuery])) : '') ?>" style="background: linear-gradient(135deg, #059669, #10b981);">
            <i class="fas fa-file-excel me-2"></i>Export Excel
        </a>
        <a class="btn-admin" href="<?= site_url('admin/japan-applications/export-pdf') . (!empty($statusFilter) || !empty($searchQuery) ? ('?' . http_build_query(['status' => $statusFilter, 'q' => $searchQuery])) : '') ?>" style="background: linear-gradient(135deg, #dc2626, #ef4444);">
            <i class="fas fa-file-pdf me-2"></i>Export PDF
        </a>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8);"><i class="fas fa-file-signature"></i></div>
        <div class="stat-number"><?= number_format($statusCounts['total'] ?? 0) ?></div>
        <div class="stat-label">Total Applications</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);"><i class="fas fa-clock"></i></div>
        <div class="stat-number"><?= number_format($statusCounts['submitted'] ?? 0) ?></div>
        <div class="stat-label">Submitted</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6, #6d28d9);"><i class="fas fa-user-check"></i></div>
        <div class="stat-number"><?= number_format($statusCounts['under_review'] ?? 0) ?></div>
        <div class="stat-label">Under Review</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669);"><i class="fas fa-plane-departure"></i></div>
        <div class="stat-number"><?= number_format($statusCounts['approved'] ?? 0) ?></div>
        <div class="stat-label">Approved</div>
    </div>
</div>

<div class="content-card">
    <form method="get" action="<?= site_url('admin/japan-applications') ?>" class="row g-3 align-items-end" style="margin-bottom: 24px;">
        <div class="col-md-5">
            <label class="form-label">Search</label>
            <input type="text" name="q" value="<?= esc($searchQuery ?? '') ?>" class="form-control" placeholder="Reference, name, email, phone">
        </div>
        <div class="col-md-4">
            <label class="form-label">Status</label>
            <select name="status" class="form-control">
                <option value="">All statuses</option>
                <?php foreach (['submitted' => 'Submitted', 'under_review' => 'Under Review', 'approved' => 'Approved', 'rejected' => 'Rejected', 'forwarded_to_japan' => 'Forwarded to Japan Agent', 'interview_scheduled' => 'Interview Scheduled', 'placed' => 'Placed'] as $value => $label): ?>
                    <option value="<?= esc($value) ?>" <?= ($statusFilter ?? '') === $value ? 'selected' : '' ?>><?= esc($label) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter me-2"></i>Filter</button>
        </div>
    </form>

    <?php if (!empty($applications)): ?>
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Applicant</th>
                        <th>Nationality</th>
                        <th>Contact</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($applications as $application): ?>
                        <tr>
                            <td><strong><?= esc($application['application_reference']) ?></strong></td>
                            <td>
                                <div><strong><?= esc($application['full_name']) ?></strong></div>
                                <small class="text-muted"><?= esc($application['degree_obtained'] ?? '') ?></small>
                            </td>
                            <td><?= esc($application['nationality'] ?? 'N/A') ?></td>
                            <td>
                                <div><?= esc($application['email']) ?></div>
                                <small class="text-muted"><?= esc($application['phone'] ?? '') ?></small>
                            </td>
                            <td>
                                <?php
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
                                ?>
                                <span class="badge <?= esc($statusClasses[$status] ?? 'bg-secondary') ?>"><?= esc(ucwords(str_replace('_', ' ', $status))) ?></span>
                            </td>
                            <td><?= !empty($application['submitted_at']) ? date('M d, Y H:i', strtotime($application['submitted_at'])) : '-' ?></td>
                            <td>
                                <a href="<?= site_url('admin/japan-applications/' . $application['id']) ?>" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?= site_url('admin/japan-applications/' . $application['id'] . '/pdf') ?>" class="btn btn-outline-danger btn-sm">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if ($pager): ?>
            <div style="margin-top: 20px; text-align: center;">
                <?= $pager->links() ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div style="text-align: center; padding: 60px 20px;">
            <div style="font-size: 48px; color: var(--text-light); margin-bottom: 20px;"><i class="fas fa-plane-departure"></i></div>
            <h3 style="color: var(--text-light); margin-bottom: 16px;">No Japan Applications Yet</h3>
            <p style="color: var(--text-light); margin-bottom: 24px;">New Teach in Japan submissions will appear here.</p>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
