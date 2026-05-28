<?= $this->extend('layouts/admin') ?>

<?php
$active_page = 'university_tutors';
$title = $title ?? 'University Tutors Management - TutorConnect Malawi';
$tutors = $tutors ?? [];
$allTutors = $all_tutors ?? $tutors;
$filters = $filters ?? ['q' => '', 'status' => '', 'review' => '', 'per_page' => 10];
$pager = $pager ?? ['current_page' => 1, 'per_page' => 10, 'total' => count($tutors), 'total_pages' => 1];
$totalTutors = count($allTutors);
$approvedTutors = count(array_filter($allTutors, static fn(array $tutor): bool => ($tutor['status'] ?? '') === 'approved'));
$readyForReview = count(array_filter($allTutors, static fn(array $tutor): bool => !empty($tutor['is_ready_for_review'])));
$linkedAccounts = count(array_filter($allTutors, static fn(array $tutor): bool => !empty($tutor['linked_user'])));
$currentPage = (int) ($pager['current_page'] ?? 1);
$totalPages = (int) ($pager['total_pages'] ?? 1);
$perPage = (int) ($filters['per_page'] ?? 10);
$resultTotal = (int) ($pager['total'] ?? count($tutors));
$baseQuery = array_filter([
    'q' => $filters['q'] ?? '',
    'status' => $filters['status'] ?? '',
    'review' => $filters['review'] ?? '',
    'per_page' => $perPage,
], static fn($value): bool => $value !== '' && $value !== null);
?>

<?= $this->section('content') ?>

<div class="header-bar">
    <div>
        <h1 class="page-title">University Tutor Management</h1>
        <p class="page-subtitle">Manage tutors registered through the university and college portal separately from the main tutor portal</p>
    </div>
    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
        <a class="btn-admin" href="<?= site_url('admin/export-university-college-tutors-excel') ?>" style="background: linear-gradient(135deg, #059669, #10b981);">
            <i class="fas fa-file-excel me-2"></i>Export Excel
        </a>
        <a class="btn-admin" href="<?= site_url('admin/export-university-college-tutors-pdf') ?>" style="background: linear-gradient(135deg, #dc2626, #ef4444);">
            <i class="fas fa-file-pdf me-2"></i>Export PDF
        </a>
        <a class="btn-admin" href="<?= site_url('admin/trainers') ?>" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8);">
            <i class="fas fa-chalkboard-teacher me-2"></i>Regular Tutors
        </a>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #4f46e5, #4338ca);">
            <i class="fas fa-university"></i>
        </div>
        <div class="stat-number"><?= number_format($totalTutors) ?></div>
        <div class="stat-label">University Tutors</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
            <i class="fas fa-user-check"></i>
        </div>
        <div class="stat-number"><?= number_format($approvedTutors) ?></div>
        <div class="stat-label">Approved</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
            <i class="fas fa-clipboard-check"></i>
        </div>
        <div class="stat-number"><?= number_format($readyForReview) ?></div>
        <div class="stat-label">Ready for Review</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #06b6d4, #0891b2);">
            <i class="fas fa-link"></i>
        </div>
        <div class="stat-number"><?= number_format($linkedAccounts) ?></div>
        <div class="stat-label">Linked Accounts</div>
    </div>
</div>

<?php if (!empty($allTutors)): ?>
<div class="content-card">
    <div style="display: flex; justify-content: space-between; align-items: center; gap: 16px; margin-bottom: 20px; flex-wrap: wrap;">
        <div>
            <h4 style="margin: 0 0 4px; color: var(--text-dark);">All University Tutors</h4>
            <p style="margin: 0; color: var(--text-light);">Showing <?= number_format($resultTotal) ?> matching university and college portal submission(s).</p>
        </div>
    </div>

    <form method="get" action="<?= site_url('admin/university-college-tutors') ?>" class="row g-3 align-items-end mb-4">
        <div class="col-lg-4 col-md-6">
            <label class="form-label small fw-semibold text-muted">Search</label>
            <input type="search" name="q" class="form-control" placeholder="Name, email, phone, reference..." value="<?= esc($filters['q'] ?? '') ?>">
        </div>
        <div class="col-lg-2 col-md-6">
            <label class="form-label small fw-semibold text-muted">Status</label>
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <?php foreach (['draft' => 'Draft', 'pending_review' => 'Pending Review', 'approved' => 'Approved', 'rejected' => 'Rejected'] as $value => $label): ?>
                    <option value="<?= esc($value) ?>" <?= ($filters['status'] ?? '') === $value ? 'selected' : '' ?>><?= esc($label) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-lg-2 col-md-6">
            <label class="form-label small fw-semibold text-muted">Profile Review</label>
            <select name="review" class="form-select">
                <option value="">All Reviews</option>
                <option value="ready" <?= ($filters['review'] ?? '') === 'ready' ? 'selected' : '' ?>>Ready</option>
                <option value="incomplete" <?= ($filters['review'] ?? '') === 'incomplete' ? 'selected' : '' ?>>Incomplete</option>
            </select>
        </div>
        <div class="col-lg-2 col-md-6">
            <label class="form-label small fw-semibold text-muted">Per Page</label>
            <select name="per_page" class="form-select">
                <?php foreach ([10, 25, 50, 100] as $option): ?>
                    <option value="<?= $option ?>" <?= $perPage === $option ? 'selected' : '' ?>><?= $option ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-lg-2 col-md-12 d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-fill">
                <i class="fas fa-filter me-1"></i>Filter
            </button>
            <a href="<?= site_url('admin/university-college-tutors') ?>" class="btn btn-outline-secondary">Reset</a>
        </div>
    </form>

    <?php if (!empty($tutors)): ?>
    <div style="overflow-x: auto;">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Profile Review</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tutors as $index => $tutor): ?>
                    <?php
                    $status = (string) ($tutor['status'] ?? 'draft');
                    $statusLabel = ucwords(str_replace('_', ' ', $status));
                    $isReady = !empty($tutor['is_ready_for_review']);
                    ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td>
                            <div style="font-weight: 600; color: var(--text-dark);"><?= esc($tutor['full_name'] ?? '-') ?></div>
                            <div style="font-size: 12px; color: var(--text-light);"><?= esc($tutor['phone'] ?? '-') ?></div>
                        </td>
                        <td><?= esc($tutor['email'] ?? '-') ?></td>
                        <td>
                            <?php if ($status === 'approved'): ?>
                                <span class="badge bg-success"><?= esc($statusLabel) ?></span>
                            <?php elseif ($status === 'rejected'): ?>
                                <span class="badge bg-danger"><?= esc($statusLabel) ?></span>
                            <?php elseif ($status === 'submitted' || $status === 'under_review'): ?>
                                <span class="badge bg-primary"><?= esc($statusLabel) ?></span>
                            <?php else: ?>
                                <span class="badge bg-secondary"><?= esc($statusLabel) ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($isReady): ?>
                                <span class="badge bg-success">Ready for Review</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">Incomplete</span>
                                <div style="font-size: 12px; color: var(--text-light); margin-top: 4px;"><?= esc((string) count($tutor['completion_gaps'] ?? [])) ?> missing item(s)</div>
                            <?php endif; ?>
                        </td>
                        <td><?= !empty($tutor['created_at']) ? esc(date('M j, Y', strtotime($tutor['created_at']))) : '-' ?></td>
                        <td>
                            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                <a href="<?= site_url('admin/view-university-college-tutor/' . (int) $tutor['id']) ?>" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if ($status !== 'approved' && $isReady): ?>
                                    <a href="<?= site_url('admin/approve-university-college-tutor/' . (int) $tutor['id']) ?>" class="btn btn-outline-success btn-sm">
                                        <i class="fas fa-check"></i>
                                    </a>
                                <?php endif; ?>
                                <?php if ($status !== 'rejected' && $isReady): ?>
                                    <a href="<?= site_url('admin/reject-university-college-tutor/' . (int) $tutor['id']) ?>" class="btn btn-outline-danger btn-sm">
                                        <i class="fas fa-times"></i>
                                    </a>
                                <?php endif; ?>
                                <form
                                    method="post"
                                    action="<?= site_url('admin/delete-university-college-tutor/' . (int) $tutor['id']) ?>"
                                    class="d-inline"
                                    onsubmit="return confirm('Delete this university tutor account? This removes the profile, linked login account, subscriptions, and uploaded files.');"
                                >
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete account">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if ($totalPages > 1): ?>
        <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap mt-4">
            <div class="text-muted small">
                Page <?= number_format($currentPage) ?> of <?= number_format($totalPages) ?>
            </div>
            <nav aria-label="University tutors pagination">
                <ul class="pagination mb-0">
                    <?php
                    $startPage = max(1, $currentPage - 2);
                    $endPage = min($totalPages, $currentPage + 2);
                    ?>
                    <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                        <?php $prevQuery = array_merge($baseQuery, ['page' => max(1, $currentPage - 1)]); ?>
                        <a class="page-link" href="<?= site_url('admin/university-college-tutors') . '?' . http_build_query($prevQuery) ?>">Previous</a>
                    </li>
                    <?php for ($pageNumber = $startPage; $pageNumber <= $endPage; $pageNumber++): ?>
                        <?php $pageQuery = array_merge($baseQuery, ['page' => $pageNumber]); ?>
                        <li class="page-item <?= $pageNumber === $currentPage ? 'active' : '' ?>">
                            <a class="page-link" href="<?= site_url('admin/university-college-tutors') . '?' . http_build_query($pageQuery) ?>"><?= $pageNumber ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                        <?php $nextQuery = array_merge($baseQuery, ['page' => min($totalPages, $currentPage + 1)]); ?>
                        <a class="page-link" href="<?= site_url('admin/university-college-tutors') . '?' . http_build_query($nextQuery) ?>">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    <?php endif; ?>
    <?php else: ?>
        <div style="text-align: center; padding: 48px 20px;">
            <div style="font-size: 42px; color: var(--text-light); margin-bottom: 14px;">
                <i class="fas fa-magnifying-glass"></i>
            </div>
            <h3 style="color: var(--text-light); margin-bottom: 10px;">No Matching University Tutors</h3>
            <p style="color: var(--text-light); margin-bottom: 0;">Try adjusting the search or filter options.</p>
        </div>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php if (empty($allTutors)): ?>
<div class="content-card">
    <div style="text-align: center; padding: 56px 20px;">
        <div style="font-size: 48px; color: var(--text-light); margin-bottom: 16px;">
            <i class="fas fa-university"></i>
        </div>
        <h3 style="color: var(--text-light); margin-bottom: 12px;">No University Tutors Yet</h3>
        <p style="color: var(--text-light); margin-bottom: 0;">University and college tutor submissions will appear here once they register.</p>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>
