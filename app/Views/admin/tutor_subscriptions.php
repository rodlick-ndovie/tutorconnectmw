<?= $this->extend('layouts/admin') ?>

<?php $active_page = 'tutor_subscriptions'; ?>
<?php $title = $title ?? 'Tutor Subscriptions - TutorConnect Malawi'; ?>
<?php
$expiredSubscriptions = array_values(array_filter($subscriptions ?? [], static function ($subscription) {
    return strtolower((string) ($subscription['display_status'] ?? $subscription['status'] ?? '')) === 'expired';
}));
$expiredPreview = array_slice($expiredSubscriptions, 0, 5);
?>

<?= $this->section('content') ?>

<style>
:root {
    --primary: #6366f1;
    --primary-dark: #4f46e5;
    --primary-light: #818cf8;
    --secondary: #06b6d4;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
    --dark: #1e293b;
    --gray-50: #f8fafc;
    --gray-100: #f1f5f9;
    --gray-200: #e2e8f0;
    --gray-300: #cbd5e1;
    --gray-400: #94a3b8;
    --gray-500: #64748b;
    --gray-600: #475569;
    --gray-700: #334155;
    --border-radius: 16px;
    --border-radius-sm: 12px;
    --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    --card-shadow-hover: 0 10px 25px rgba(0, 0, 0, 0.12);
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: var(--border-radius);
    padding: 1.75rem;
    box-shadow: var(--card-shadow);
    border: 1px solid var(--gray-200);
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--card-shadow-hover);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: var(--primary);
}

.stat-card.success::before { background: var(--success); }
.stat-card.warning::before { background: var(--warning); }
.stat-card.info::before { background: var(--info, #06b6d4); }

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: var(--border-radius);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin-bottom: 1rem;
    color: white;
}

.stat-card .stat-icon { background: var(--primary); }
.stat-card.success .stat-icon { background: var(--success); }
.stat-card.warning .stat-icon { background: var(--warning); }
.stat-card.info .stat-icon { background: var(--info, #06b6d4); }

.stat-number {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--dark);
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.95rem;
    color: var(--gray-600);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.action-buttons {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
    flex-wrap: wrap;
}

.btn-admin {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    border: none;
    border-radius: var(--border-radius-sm);
    color: white;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    text-decoration: none;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-admin:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(99, 102, 241, 0.3);
    color: white;
}

.btn-admin.secondary {
    background: var(--gray-200);
    color: var(--gray-700);
}

.btn-admin.secondary:hover {
    background: var(--gray-300);
    color: var(--gray-800);
}

.renewal-banner {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.12), rgba(217, 119, 6, 0.06));
    border: 1px solid rgba(245, 158, 11, 0.25);
    border-left: 5px solid var(--warning);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.renewal-banner-list {
    display: grid;
    gap: 0.75rem;
    margin-top: 1rem;
}

.renewal-banner-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 0.9rem 1rem;
    background: rgba(255, 255, 255, 0.7);
    border: 1px solid rgba(245, 158, 11, 0.18);
    border-radius: 14px;
    flex-wrap: wrap;
}

.renewal-banner-meta {
    color: var(--gray-600);
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.renewal-banner-link {
    color: #b45309;
    font-size: 0.875rem;
    font-weight: 700;
    text-decoration: none;
}

.renewal-banner-link:hover {
    color: #92400e;
}

.data-table-container {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--card-shadow);
    border: 1px solid var(--gray-200);
    overflow: hidden;
}

.data-table-header {
    padding: 1.5rem 2rem;
    border-bottom: 1px solid var(--gray-200);
    background: linear-gradient(135deg, var(--gray-50), white);
}

.data-table-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--dark);
    margin: 0;
}

.data-table-subtitle {
    color: var(--gray-600);
    margin: 0.25rem 0 0;
}

.data-table-content {
    padding: 0;
}

.subscription-table {
    width: 100%;
    border-collapse: collapse;
}

.subscription-table th {
    background: var(--gray-50);
    color: var(--gray-700);
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 1rem 1.5rem;
    text-align: left;
    border-bottom: 1px solid var(--gray-200);
}

.subscription-table td {
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--gray-100);
    vertical-align: middle;
}

.subscription-table tbody tr:hover {
    background: var(--gray-50);
}

.tutor-name {
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 0.25rem;
}

.tutor-email {
    color: var(--gray-600);
    font-size: 0.875rem;
}

.plan-badge {
    background: var(--primary);
    color: white;
    padding: 0.375rem 0.75rem;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-badge {
    padding: 0.375rem 0.75rem;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

.status-badge.active { background: var(--success); color: white; }
.status-badge.inactive { background: var(--gray-400); color: white; }
.status-badge.pending { background: var(--warning); color: white; }
.status-badge.cancelled { background: var(--danger); color: white; }
.status-badge.expired { background: var(--gray-500); color: white; }
.status-badge.scheduled { background: var(--secondary); color: white; }

.period-text {
    color: var(--gray-600);
    font-size: 0.875rem;
}

.price-text {
    font-weight: 700;
    color: var(--dark);
    font-size: 1.1rem;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.btn-action {
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

.btn-action:hover {
    transform: translateY(-1px);
}

.btn-action.primary { background: var(--primary); color: white; }
.btn-action.success { background: var(--success); color: white; }
.btn-action.warning { background: var(--warning); color: white; }
.btn-action.danger { background: var(--danger); color: white; }
.btn-action.info { background: var(--secondary); color: white; }

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }

    .stat-card {
        padding: 1.25rem;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        font-size: 1.25rem;
    }

    .stat-number {
        font-size: 2rem;
    }

    .action-buttons {
        flex-direction: column;
    }

    .btn-admin {
        width: 100%;
        justify-content: center;
    }

    .subscription-table th,
    .subscription-table td {
        padding: 0.75rem 1rem;
    }

    .data-table-header {
        padding: 1rem 1.5rem;
    }
}
</style>

<!-- Page Header -->
<div class="mb-4">
    <h1 class="h2 mb-2 text-gray-800 font-weight-bold">Tutor Subscriptions Management</h1>
    <p class="text-muted mb-4">Monitor and manage tutor subscription assignments, payments, and status</p>
</div>

<?php if (!empty($expiredSubscriptions)): ?>
<div class="renewal-banner">
    <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
        <div>
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <div style="width: 44px; height: 44px; border-radius: 14px; background: linear-gradient(135deg, #f59e0b, #d97706); color: white; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div>
                    <h2 style="margin: 0; font-size: 1.15rem; font-weight: 700; color: var(--dark);">Renewal Needed</h2>
                    <p style="margin: 0.2rem 0 0; color: var(--gray-600);">
                        <?= number_format(count($expiredSubscriptions)) ?> tutor subscription<?= count($expiredSubscriptions) === 1 ? '' : 's' ?> expired and may need follow-up or renewal.
                    </p>
                </div>
            </div>

            <div class="renewal-banner-list">
                <?php foreach ($expiredPreview as $expiredSubscription): ?>
                    <div class="renewal-banner-item">
                        <div>
                            <div class="tutor-name" style="margin-bottom: 0;">
                                <?= esc(trim(($expiredSubscription['first_name'] ?? '') . ' ' . ($expiredSubscription['last_name'] ?? ''))) ?>
                                <span style="font-weight: 600; color: #b45309;">• <?= esc($expiredSubscription['plan_name'] ?? 'Plan') ?></span>
                            </div>
                            <div class="renewal-banner-meta">
                                <?= esc($expiredSubscription['email'] ?? '') ?> | Expired on <?= !empty($expiredSubscription['current_period_end']) ? date('M d, Y', strtotime($expiredSubscription['current_period_end'])) : 'N/A' ?>
                            </div>
                        </div>
                        <a href="#subscriptionsTable" class="renewal-banner-link">Renewal message</a>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if (count($expiredSubscriptions) > count($expiredPreview)): ?>
                <div style="margin-top: 0.75rem; color: var(--gray-600); font-size: 0.875rem;">
                    Showing latest <?= count($expiredPreview) ?> expired subscriptions.
                </div>
            <?php endif; ?>
        </div>

        <a href="<?= site_url('admin/tutor-subscriptions') ?>" class="btn-admin" style="align-self: center;">
            <i class="fas fa-sync-alt"></i>
            <span>Review Renewals</span>
        </a>
    </div>
</div>
<?php endif; ?>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-receipt"></i>
        </div>
        <div class="stat-number"><?= $total_subscriptions ?></div>
        <div class="stat-label">Total Subscriptions</div>
    </div>

    <div class="stat-card success">
        <div class="stat-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-number"><?= $active_subscriptions ?></div>
        <div class="stat-label">Active Subscriptions</div>
    </div>

    <div class="stat-card warning">
        <div class="stat-icon">
            <i class="fas fa-pause-circle"></i>
        </div>
        <div class="stat-number"><?= $stats['inactive_subscriptions'] ?? 0 ?></div>
        <div class="stat-label">Inactive Subscriptions</div>
    </div>

    <div class="stat-card info">
        <div class="stat-icon">
            <i class="fas fa-money-bill-wave"></i>
        </div>
        <div class="stat-number">MK<?= number_format($stats['total_monthly_revenue'] ?? 0) ?></div>
        <div class="stat-label">Monthly Revenue</div>
    </div>
</div>

<!-- Action Buttons -->
<div class="action-buttons">
    <button type="button" class="btn-admin" data-bs-toggle="modal" data-bs-target="#assignSubscriptionModal">
        <i class="fas fa-user-plus"></i>
        <span>Assign Subscription</span>
    </button>
    <a href="<?= site_url('admin/subscriptions') ?>" class="btn-admin secondary">
        <i class="fas fa-dollar-sign"></i>
        <span>Manage Plans</span>
    </a>
</div>

<!-- Subscriptions Table -->
<div class="data-table-container">
    <div class="data-table-header">
        <h2 class="data-table-title">Tutor Subscriptions</h2>
        <p class="data-table-subtitle">Complete overview of all tutor subscription assignments and status</p>
    </div>
    <div class="data-table-content">
        <table class="subscription-table" id="subscriptionsTable">
            <thead>
                <tr>
                    <th style="width: 60px;">#</th>
                    <th>Tutor</th>
                    <th>Email</th>
                    <th>Plan</th>
                    <th>Status</th>
                    <th>Current Period</th>
                    <th>Monthly Fee</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $counter = 1; ?>
                <?php foreach ($subscriptions as $subscription): ?>
                    <?php $displayStatus = strtolower($subscription['display_status'] ?? $subscription['status']); ?>
                    <tr>
                        <td class="text-center">
                            <span class="badge bg-secondary fw-bold" style="font-size: 0.8rem; padding: 0.375rem 0.5rem; border-radius: 20px;"><?= $counter++ ?></span>
                        </td>
                        <td>
                            <div class="tutor-name"><?= esc($subscription['first_name'] . ' ' . $subscription['last_name']) ?></div>
                        </td>
                        <td>
                            <a href="mailto:<?= esc($subscription['email']) ?>" class="text-decoration-none tutor-email">
                                <?= esc($subscription['email']) ?>
                            </a>
                        </td>
                        <td>
                            <span class="plan-badge"><?= esc($subscription['plan_name']) ?></span>
                        </td>
                        <td>
                            <span class="status-badge <?= esc($displayStatus) ?>">
                                <?php if ($displayStatus === 'pending'): ?>
                                    <i class="fas fa-clock"></i>
                                <?php elseif ($displayStatus === 'scheduled'): ?>
                                    <i class="fas fa-calendar-alt"></i>
                                <?php endif; ?>
                                <?= ucfirst($displayStatus) ?>
                            </span>
                            <?php if ($displayStatus === 'expired'): ?>
                                <div class="period-text" style="margin-top: 6px; color: #b45309; font-weight: 600;">
                                    Renewal needed
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="period-text">
                                <?= date('M j, Y', strtotime($subscription['current_period_start'])) ?> -
                                <?= date('M j, Y', strtotime($subscription['current_period_end'])) ?>
                            </div>
                            <?php if (!empty($subscription['billing_months']) && (int) $subscription['billing_months'] > 1): ?>
                                <div class="period-text" style="margin-top: 4px;">
                                    <?= (int) $subscription['billing_months'] ?> months paid
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="price-text">MK<?= number_format($subscription['price_monthly']) ?></div>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <?php if ($displayStatus === 'pending'): ?>
                                    <!-- Approval buttons for pending subscriptions -->
                                    <button class="btn-action success" onclick="approveSubscription(<?= $subscription['id'] ?>)" title="Approve Payment">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn-action warning" onclick="viewPaymentProof(<?= $subscription['id'] ?>)" title="View Payment Proof">
                                        <i class="fas fa-file-invoice-dollar"></i>
                                    </button>
                                <?php else: ?>
                                    <!-- Regular action buttons -->
                                    <button class="btn-action primary" onclick="editSubscription(<?= $subscription['id'] ?>)" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-action info" onclick="updateStatus(<?= $subscription['id'] ?>, '<?= $subscription['status'] ?>')" title="Update Status">
                                        <i class="fas fa-toggle-on"></i>
                                    </button>
                                <?php endif; ?>
                                <form method="POST" action="<?= site_url('admin/tutor-subscriptions/remove/' . $subscription['id']) ?>" class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to remove this subscription?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn-action danger" title="Remove">
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
                </div>
            </div>
        </div>
    </div>

    <!-- Subscription History/Timeline (Optional Enhancement) -->
    <?php if (!empty($subscriptions)): ?>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Subscription Activity</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <?php foreach (array_slice($subscriptions, 0, 5) as $subscription): ?>
                            <div class="timeline-item">
                                <div class="timeline-marker bg-primary"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">
                                        Subscription to <?= esc($subscription['plan_name']) ?> assigned
                                    </h6>
                                    <p class="timeline-text mb-1">
                                        <strong><?= esc($subscription['first_name'] . ' ' . $subscription['last_name']) ?></strong>
                                        - MK<?= number_format($subscription['price_monthly']) ?>/month
                                    </p>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar"></i> <?= date('M j, Y \a\t g:i A', strtotime($subscription['created_at'])) ?>
                                    </small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Assign Subscription Modal -->
<div class="modal fade" id="assignSubscriptionModal" tabindex="-1" aria-labelledby="assignSubscriptionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignSubscriptionModalLabel">Assign Subscription to Tutor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="<?= site_url('admin/tutor-subscriptions/assign') ?>">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="user_id" class="form-label">Select Tutor *</label>
                        <select class="form-select" id="user_id" name="user_id" required>
                            <option value="">Choose a tutor...</option>
                            <?php
                            // Get tutors who don't have active subscriptions
                            $tutorModel = new \App\Models\TutorModel();
                            $userModel = new \App\Models\User();
                            $existingSubscriptions = array_column($subscriptions, 'user_id');

                            $tutors = $tutorModel->findAll();
                            $availableTutors = [];

                            foreach ($tutors as $tutor) {
                                if (!in_array($tutor['id'], $existingSubscriptions)) {
                                    $user = $userModel->find($tutor['id']);
                                    if ($user) {
                                        $availableTutors[] = $user;
                                    }
                                }
                            }

                            foreach ($availableTutors as $user):
                            ?>
                                <option value="<?= $user['id'] ?>">
                                    <?= esc($user['first_name'] . ' ' . $user['last_name']) ?> (<?= esc($user['email']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text">Only tutors without active subscriptions are shown</div>
                    </div>
                    <div class="mb-3">
                        <label for="plan_id" class="form-label">Subscription Plan *</label>
                        <select class="form-select" id="plan_id" name="plan_id" required>
                            <option value="">Choose a plan...</option>
                            <?php foreach ($available_plans as $plan): ?>
                                <?php if ($plan['is_active']): ?>
                                    <option value="<?= $plan['id'] ?>">
                                        <?= esc($plan['name']) ?> - MK<?= number_format($plan['price_monthly']) ?>/month
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Subscription will start immediately and bill monthly from the current date.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign Subscription</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateStatusModalLabel">Update Subscription Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" id="updateStatusForm">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">New Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="expired">Expired</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    padding-bottom: 1.5rem;
}

.timeline-marker {
    position: absolute;
    left: -30px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #e9ecef;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: -24px;
    top: 12px;
    width: 2px;
    height: calc(100% - 12px);
    background: #e9ecef;
}

.timeline-title {
    margin-bottom: 0.25rem;
    font-size: 0.95rem;
}

.timeline-text {
    color: #6c757d;
    font-size: 0.875rem;
}
</style>

<script>
function editSubscription(subscriptionId) {
    // Redirect to subscription edit/details page
    window.location.href = '<?= site_url('admin/tutor-subscriptions/edit/') ?>' + subscriptionId;
}

function updateStatus(subscriptionId, currentStatus) {
    // Set the form action and populate current status
    document.getElementById('updateStatusForm').action = '<?= site_url('admin/tutor-subscriptions/update-status/') ?>' + subscriptionId;
    document.getElementById('status').value = currentStatus;

    // Show the modal
    const modal = new bootstrap.Modal(document.getElementById('updateStatusModal'));
    modal.show();
}

function approveSubscription(subscriptionId) {
    if (confirm('Are you sure you want to approve this subscription payment? The subscription will become active immediately.')) {
        // Create a form to submit the approval
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= site_url('admin/tutor-subscriptions/update-status/') ?>' + subscriptionId;

        // Add CSRF token
        const csrfField = document.createElement('input');
        csrfField.type = 'hidden';
        csrfField.name = '<?= csrf_token() ?>';
        csrfField.value = '<?= csrf_hash() ?>';
        form.appendChild(csrfField);

        // Add status field
        const statusField = document.createElement('input');
        statusField.type = 'hidden';
        statusField.name = 'status';
        statusField.value = 'active';
        form.appendChild(statusField);

        // Submit the form
        document.body.appendChild(form);
        form.submit();
    }
}

function viewPaymentProof(subscriptionId) {
    // Show loading modal
    const modalHtml = `
        <div class="modal fade" id="proofModal" tabindex="-1" aria-labelledby="proofModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="proofModalLabel">
                            <i class="fas fa-file-invoice-dollar mr-2"></i>
                            Payment Proof Review
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Loading payment proof...</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Remove existing modal if present
    const existingModal = document.getElementById('proofModal');
    if (existingModal) {
        existingModal.remove();
    }

    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', modalHtml);

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('proofModal'));
    modal.show();

    // Load proof content via AJAX
    fetch('<?= site_url('admin/tutor-subscriptions/view-proof/') ?>' + subscriptionId, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(data => {
        // Extract the content from the full HTML response
        const parser = new DOMParser();
        const doc = parser.parseFromString(data, 'text/html');

        // Get the main content
        const mainContent = doc.querySelector('.main-content');
        const modalBody = document.querySelector('#proofModal .modal-body');
        const modalTitle = document.querySelector('#proofModal .modal-title');

        if (mainContent && modalBody) {
            // Update modal title
            if (modalTitle) {
                modalTitle.innerHTML = '<i class="fas fa-file-invoice-dollar mr-2"></i>Payment Proof Review';
            }

            // Update modal body with proof content
            modalBody.innerHTML = mainContent.innerHTML;

            // Add action buttons to modal footer
            const modalFooter = document.querySelector('#proofModal .modal-footer');
            if (modalFooter) {
                const subscriptionInfo = modalBody.querySelector('.info-card');
                let subscriptionId = null;
                if (subscriptionInfo) {
                    // Extract subscription ID from forms in the content
                    const approveForm = modalBody.querySelector('form[action*="update-status"]');
                    if (approveForm) {
                        const actionUrl = approveForm.getAttribute('action');
                        const urlParts = actionUrl.split('/');
                        subscriptionId = urlParts[urlParts.length - 1];
                    }
                }

                if (subscriptionId) {
                    modalFooter.innerHTML = `
                        <form method="POST" action="<?= site_url('admin/tutor-subscriptions/update-status/') ?>${subscriptionId}" style="display: inline;">
                            <input type="hidden" name="status" value="active">
                            <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to approve this payment? The subscription will become active immediately.')">
                                <i class="fas fa-check"></i> Approve Payment
                            </button>
                        </form>
                        <form method="POST" action="<?= site_url('admin/tutor-subscriptions/update-status/') ?>${subscriptionId}" style="display: inline;">
                            <input type="hidden" name="status" value="cancelled">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to reject this payment? The subscription will be cancelled.')">
                                <i class="fas fa-times"></i> Reject Payment
                            </button>
                        </form>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    `;
                }
            }
        } else {
            modalBody.innerHTML = '<div class="alert alert-danger">Failed to load payment proof. Please try again.</div>';
        }
    })
    .catch(error => {
        console.error('Error loading proof:', error);
        const modalBody = document.querySelector('#proofModal .modal-body');
        if (modalBody) {
            modalBody.innerHTML = '<div class="alert alert-danger">Error loading payment proof. Please try again.</div>';
        }
    });
}

// Initialize DataTables
$(document).ready(function() {
    if ($.fn.DataTable) {
        $('#subscriptionsTable').DataTable({
            "order": [[4, 'desc']], // Sort by current period (descending)
            "pageLength": 25
        });
    }
});
</script>

<?= $this->endSection() ?>
