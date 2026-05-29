<?= $this->extend('layouts/admin') ?>

<?php $active_page = 'dashboard'; ?>

<?= $this->section('content') ?>

<?php
    $perPage = (int) ($per_page ?? 10);
    $currentPage = (int) ($current_page ?? 1);
    $totalPages = (int) ($total_pages ?? 1);
    $totalActivities = (int) ($total_activities ?? 0);
    $pageStart = (int) ($page_start ?? 0);
    $pageEnd = (int) ($page_end ?? 0);
    $perPageOptions = $per_page_options ?? [10, 25, 50];

    $activityPageUrl = static function (int $page, int $perPage): string {
        return base_url('admin/activity') . '?' . http_build_query([
            'page' => $page,
            'per_page' => $perPage,
        ]);
    };
?>

<div class="header-bar activity-header-bar">
    <div>
        <h1 class="page-title">Activity Feed</h1>
        <p class="page-subtitle">Review tutor registrations, uni tutor registrations, and subscription activity.</p>
    </div>
    <a href="<?= base_url('admin/dashboard') ?>" class="activity-back-link">
        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
    </a>
</div>

<div class="content-card activity-toolbar">
    <div>
        <div class="activity-toolbar-label">Showing</div>
        <div class="activity-toolbar-value">
            <?= number_format($pageStart) ?>-<?= number_format($pageEnd) ?> of <?= number_format($totalActivities) ?> activities
        </div>
    </div>
    <form method="get" action="<?= base_url('admin/activity') ?>" class="activity-per-page-form">
        <input type="hidden" name="page" value="1">
        <label for="activityPerPage">Rows per page</label>
        <select id="activityPerPage" name="per_page" class="form-select" onchange="this.form.submit()">
            <?php foreach ($perPageOptions as $option): ?>
                <option value="<?= (int) $option ?>" <?= $perPage === (int) $option ? 'selected' : '' ?>>
                    <?= (int) $option ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
</div>

<div class="content-card activity-feed-card">
    <?php if (!empty($activities)): ?>
        <div class="activity-feed-list">
            <?php foreach ($activities as $index => $activity): ?>
                <?php
                    $itemNumber = $pageStart + $index;
                    $activityType = (string) ($activity['type'] ?? '');
                    $typeClass = match ($activityType) {
                        'user_registration_university_tutor', 'university_subscription' => 'activity-type-university',
                        'subscription' => 'activity-type-subscription',
                        default => 'activity-type-standard',
                    };
                ?>
                <div class="activity-feed-item">
                    <div class="activity-item-number"><?= number_format($itemNumber) ?></div>
                    <div class="activity-item-icon <?= esc($typeClass) ?>">
                        <?= esc($activity['icon'] ?? 'i') ?>
                    </div>
                    <div class="activity-item-copy">
                        <div class="activity-item-title"><?= esc($activity['title'] ?? 'Activity') ?></div>
                        <div class="activity-item-description"><?= esc($activity['description'] ?? '') ?></div>
                    </div>
                    <div class="activity-item-time">
                        <strong><?= esc($activity['time'] ?? '') ?></strong>
                        <span><?= esc($activity['date'] ?? '') ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="activity-empty-state">
            <i class="fas fa-inbox"></i>
            <h3>No activity yet</h3>
            <p>New registrations and subscription updates will appear here.</p>
        </div>
    <?php endif; ?>
</div>

<?php if ($totalPages > 1): ?>
    <div class="content-card activity-pagination-card">
        <div class="activity-pagination-summary">
            Page <?= number_format($currentPage) ?> of <?= number_format($totalPages) ?>
        </div>
        <div class="activity-pagination">
            <a class="activity-page-link <?= $currentPage <= 1 ? 'disabled' : '' ?>" href="<?= $currentPage > 1 ? esc($activityPageUrl($currentPage - 1, $perPage)) : '#' ?>">
                Previous
            </a>

            <?php
                $startPage = max(1, $currentPage - 2);
                $endPage = min($totalPages, $currentPage + 2);
            ?>
            <?php for ($page = $startPage; $page <= $endPage; $page++): ?>
                <a class="activity-page-link <?= $page === $currentPage ? 'active' : '' ?>" href="<?= esc($activityPageUrl($page, $perPage)) ?>">
                    <?= number_format($page) ?>
                </a>
            <?php endfor; ?>

            <a class="activity-page-link <?= $currentPage >= $totalPages ? 'disabled' : '' ?>" href="<?= $currentPage < $totalPages ? esc($activityPageUrl($currentPage + 1, $perPage)) : '#' ?>">
                Next
            </a>
        </div>
    </div>
<?php endif; ?>

<style>
.activity-header-bar {
    gap: 16px;
    flex-wrap: wrap;
}

.activity-back-link {
    align-items: center;
    border: 1px solid rgba(30, 64, 175, 0.18);
    border-radius: 12px;
    color: var(--admin-primary);
    display: inline-flex;
    font-size: 13px;
    font-weight: 700;
    padding: 10px 14px;
    text-decoration: none;
}

.activity-back-link:hover {
    background: rgba(30, 64, 175, 0.08);
    color: var(--admin-primary);
}

.activity-toolbar {
    align-items: center;
    display: flex;
    justify-content: space-between;
    gap: 18px;
    flex-wrap: wrap;
}

.activity-toolbar-label {
    color: var(--text-light);
    font-size: 12px;
    font-weight: 800;
    letter-spacing: 0.08em;
    text-transform: uppercase;
}

.activity-toolbar-value {
    color: var(--text-dark);
    font-size: 18px;
    font-weight: 800;
    margin-top: 3px;
}

.activity-per-page-form {
    align-items: center;
    display: flex;
    gap: 10px;
}

.activity-per-page-form label {
    color: var(--text-light);
    font-size: 13px;
    font-weight: 700;
    white-space: nowrap;
}

.activity-per-page-form .form-select {
    min-width: 92px;
}

.activity-feed-card {
    padding: 0;
    overflow: hidden;
}

.activity-feed-list {
    display: grid;
}

.activity-feed-item {
    align-items: center;
    border-bottom: 1px solid rgba(15, 23, 42, 0.06);
    display: grid;
    grid-template-columns: 52px 48px minmax(0, 1fr) minmax(160px, auto);
    gap: 16px;
    padding: 18px 24px;
}

.activity-feed-item:last-child {
    border-bottom: 0;
}

.activity-item-number {
    color: var(--text-light);
    font-size: 13px;
    font-weight: 800;
}

.activity-item-icon {
    align-items: center;
    border-radius: 14px;
    color: white;
    display: inline-flex;
    font-size: 15px;
    font-weight: 800;
    height: 42px;
    justify-content: center;
    width: 42px;
}

.activity-type-standard {
    background: linear-gradient(135deg, #1e40af, #1e293b);
}

.activity-type-university {
    background: linear-gradient(135deg, #E55C0D, #b94308);
}

.activity-type-subscription {
    background: linear-gradient(135deg, #059669, #047857);
}

.activity-item-copy {
    min-width: 0;
}

.activity-item-title {
    color: var(--text-dark);
    font-size: 15px;
    font-weight: 800;
}

.activity-item-description {
    color: var(--text-light);
    font-size: 13px;
    line-height: 1.55;
    margin-top: 4px;
}

.activity-item-time {
    color: var(--text-light);
    font-size: 12px;
    text-align: right;
}

.activity-item-time strong,
.activity-item-time span {
    display: block;
}

.activity-item-time strong {
    color: var(--text-dark);
    font-size: 13px;
}

.activity-empty-state {
    color: var(--text-light);
    padding: 56px 20px;
    text-align: center;
}

.activity-empty-state i {
    font-size: 34px;
    opacity: 0.55;
}

.activity-empty-state h3 {
    color: var(--text-dark);
    font-size: 20px;
    font-weight: 800;
    margin: 14px 0 6px;
}

.activity-empty-state p {
    margin: 0;
}

.activity-pagination-card {
    align-items: center;
    display: flex;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
}

.activity-pagination-summary {
    color: var(--text-light);
    font-size: 13px;
    font-weight: 700;
}

.activity-pagination {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.activity-page-link {
    border: 1px solid rgba(15, 23, 42, 0.12);
    border-radius: 10px;
    color: var(--text-dark);
    font-size: 13px;
    font-weight: 800;
    min-width: 40px;
    padding: 9px 12px;
    text-align: center;
    text-decoration: none;
}

.activity-page-link.active {
    background: var(--admin-primary);
    border-color: var(--admin-primary);
    color: white;
}

.activity-page-link.disabled {
    color: var(--text-light);
    opacity: 0.5;
    pointer-events: none;
}

.activity-page-link:hover {
    border-color: var(--admin-primary);
    color: var(--admin-primary);
}

.activity-page-link.active:hover {
    color: white;
}

@media (max-width: 768px) {
    .activity-feed-item {
        align-items: flex-start;
        grid-template-columns: 40px 42px minmax(0, 1fr);
        gap: 12px;
        padding: 16px;
    }

    .activity-item-time {
        grid-column: 3;
        text-align: left;
    }
}
</style>

<?= $this->endSection() ?>
