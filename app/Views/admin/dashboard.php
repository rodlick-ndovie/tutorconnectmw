<?= $this->extend('layouts/admin') ?>

<?php $active_page = 'dashboard'; ?>

<?= $this->section('content') ?>

<div class="header-bar">
    <div>
        <h1 class="page-title"><?php echo $dashboard_title ?? 'Admin Dashboard'; ?></h1>
        <p class="page-subtitle">Welcome back, <?php echo $user['first_name'] ?? $user['username']; ?>! Here's what's happening with your platform.</p>
    </div>
</div>

<div class="stats-grid dashboard-stats-grid">
    <div class="stat-card dashboard-stat-card">
        <div class="dashboard-stat-top">
            <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6, #1e40af); color: white;">
                <i class="fas fa-users"></i>
            </div>
            <span class="dashboard-stat-chip">Platform</span>
        </div>
        <div>
            <div class="stat-number"><?php echo number_format($stats['total_users'] ?? 0); ?></div>
            <div class="stat-label">Total Users</div>
        </div>
        <div class="dashboard-stat-note">Registered accounts across the whole platform.</div>
    </div>

    <div class="stat-card dashboard-stat-card">
        <div class="dashboard-stat-top">
            <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669); color: white;">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <span class="dashboard-stat-chip">Tutors</span>
        </div>
        <div>
            <div class="stat-number"><?php echo number_format($stats['verified_tutors'] ?? 0); ?></div>
            <div class="stat-label">Verified Tutors</div>
        </div>
        <div class="dashboard-stat-note">Tutors approved and visible on the platform.</div>
    </div>

    <div class="stat-card dashboard-stat-card">
        <div class="dashboard-stat-top">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706); color: white;">
                <i class="fas fa-star"></i>
            </div>
            <span class="dashboard-stat-chip">Quality</span>
        </div>
        <div>
            <div class="stat-number"><?php echo number_format($stats['avg_rating'] ?? 0, 1); ?></div>
            <div class="stat-label">Avg Rating</div>
        </div>
        <div class="dashboard-stat-note">Average tutor rating based on current platform data.</div>
    </div>

    <div class="stat-card dashboard-stat-card">
        <div class="dashboard-stat-top">
            <div class="stat-icon" style="background: linear-gradient(135deg, #ef4444, #dc2626); color: white;">
                <i class="fas fa-clock"></i>
            </div>
            <span class="dashboard-stat-chip">Queue</span>
        </div>
        <div>
            <div class="stat-number"><?php echo number_format($stats['pending_verification'] ?? 0); ?></div>
            <div class="stat-label">Pending Review</div>
        </div>
        <div class="dashboard-stat-note">Trainer profiles still waiting for admin action.</div>
    </div>
</div>

<div class="content-card dashboard-section-card revenue-report-card">
    <div class="money-overview-header">
        <div>
            <div class="section-eyebrow">Revenue Reporting</div>
            <h3 class="section-title">Professional Pricing Overview</h3>
            <p class="section-subtitle">
                A clear view of every section where the platform receives payments.
            </p>
        </div>

        <div class="money-total-card">
            <div class="money-total-label">Total Revenue</div>
            <div class="money-total-value">MK <?php echo number_format($stats['total_revenue'] ?? 0); ?></div>
            <div class="money-total-meta">Verified income from all paid sections</div>
            <div class="money-total-updated">Updated: <?= date('M d, Y H:i') ?></div>
        </div>
    </div>

    <div class="revenue-report-table-wrap">
        <table class="table revenue-report-table align-middle">
            <thead>
                <tr>
                    <th>Revenue Section</th>
                    <th>Professional Price Type</th>
                    <th>Activity</th>
                    <th class="text-end">Revenue</th>
                    <th class="text-end">Manage</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="revenue-source">
                            <span class="revenue-source-icon"><i class="fas fa-repeat"></i></span>
                            <div>
                                <strong>Regular Tutor Subscriptions</strong>
                                <small>Recurring tutor plan payments</small>
                            </div>
                        </div>
                    </td>
                    <td>Professional tutor plan pricing</td>
                    <td>
                        <strong><?= number_format($stats['tutor_active_subscriptions'] ?? 0) ?></strong> active subscriptions
                    </td>
                    <td class="text-end revenue-amount">MK <?= number_format($stats['tutor_subscription_revenue'] ?? 0) ?></td>
                    <td class="text-end"><a href="<?= base_url('admin/tutor-subscriptions') ?>" class="revenue-metric-link">View</a></td>
                </tr>
                <tr>
                    <td>
                        <div class="revenue-source">
                            <span class="revenue-source-icon"><i class="fas fa-building-columns"></i></span>
                            <div>
                                <strong>University Tutor Subscriptions</strong>
                                <small>Recurring university support plan payments</small>
                            </div>
                        </div>
                    </td>
                    <td>Professional university plan pricing</td>
                    <td>
                        <strong><?= number_format($stats['university_active_subscriptions'] ?? 0) ?></strong> active subscriptions
                    </td>
                    <td class="text-end revenue-amount">MK <?= number_format($stats['university_subscription_revenue'] ?? 0) ?></td>
                    <td class="text-end"><a href="<?= base_url('admin/university-tutor-subscriptions') ?>" class="revenue-metric-link">View</a></td>
                </tr>
                <tr>
                    <td>
                        <div class="revenue-source">
                            <span class="revenue-source-icon"><i class="fas fa-plane-departure"></i></span>
                            <div>
                                <strong>Teach in Japan Applications</strong>
                                <small>Application access payments</small>
                            </div>
                        </div>
                    </td>
                    <td>Professional application fee</td>
                    <td>
                        <strong><?= number_format($stats['japan_verified_payments_total'] ?? 0) ?></strong> verified
                        <span class="text-muted">/ <?= number_format($stats['japan_applications_total'] ?? 0) ?> submitted</span>
                    </td>
                    <td class="text-end revenue-amount">MK <?= number_format($stats['japan_application_fees_total'] ?? 0) ?></td>
                    <td class="text-end"><a href="<?= base_url('admin/japan-payments') ?>" class="revenue-metric-link">View</a></td>
                </tr>
                <tr>
                    <td>
                        <div class="revenue-source">
                            <span class="revenue-source-icon"><i class="fas fa-file-invoice-dollar"></i></span>
                            <div>
                                <strong>Paid Past Papers</strong>
                                <small>Resource purchase payments</small>
                            </div>
                        </div>
                    </td>
                    <td>Professional resource pricing</td>
                    <td>
                        <strong><?= number_format($stats['past_paper_verified_purchases_total'] ?? 0) ?></strong> purchases
                        <span class="text-muted">/ <?= number_format($stats['paid_past_papers_total'] ?? 0) ?> paid papers</span>
                    </td>
                    <td class="text-end revenue-amount">MK <?= number_format($stats['past_paper_income_total'] ?? 0) ?></td>
                    <td class="text-end"><a href="<?= base_url('admin/past-paper-payments') ?>" class="revenue-metric-link">View</a></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2">Verified subscription payments processed</td>
                    <td>PayChangu confirmed transactions</td>
                    <td class="text-end revenue-amount">MK <?= number_format($stats['paychangu_revenue'] ?? 0) ?></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="dashboard-section-heading">
    <div>
        <div class="section-eyebrow">Operations</div>
        <h3 class="section-title">Quick Actions</h3>
        <p class="section-subtitle">Shortcuts for the admin tasks you reach for most often.</p>
    </div>
</div>

<div class="quick-actions-grid">
    <a href="<?php echo base_url('admin/users'); ?>" class="content-card quick-action-card">
        <div class="quick-action-top">
            <div class="quick-action-icon">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <div class="quick-action-title">Manage Users</div>
                <div class="quick-action-subtitle">View and manage all platform users.</div>
            </div>
        </div>
        <div class="quick-action-foot">Open the full user directory and account controls.</div>
    </a>

    <a href="<?php echo base_url('admin/trainers'); ?>" class="content-card quick-action-card">
        <div class="quick-action-top">
            <div class="quick-action-icon">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <div>
                <div class="quick-action-title">Tutor Management</div>
                <div class="quick-action-subtitle">Manage approvals, visibility, and tutor profiles.</div>
            </div>
        </div>
        <div class="quick-action-foot">Review tutor records and approval workflow.</div>
    </a>

    <a href="<?php echo base_url('admin/settings'); ?>" class="content-card quick-action-card">
        <div class="quick-action-top">
            <div class="quick-action-icon">
                <i class="fas fa-cog"></i>
            </div>
            <div>
                <div class="quick-action-title">System Settings</div>
                <div class="quick-action-subtitle">Configure platform settings and site-wide behavior.</div>
            </div>
        </div>
        <div class="quick-action-foot">Update configuration, branding, and support details.</div>
    </a>
</div>

<div class="content-card dashboard-section-card">
    <div class="recent-activity-header">
        <div>
            <div class="section-eyebrow">Live Feed</div>
            <h3 class="section-title">Recent Activity</h3>
            <p class="section-subtitle">Latest platform actions and updates from across the system.</p>
        </div>
        <a href="<?= base_url('admin/activity') ?>" class="activity-view-all-link">
            View All <i class="fas fa-arrow-right ms-1"></i>
        </a>
    </div>

    <?php if (!empty($recent_activity)): ?>
        <div class="recent-activity-list">
            <?php foreach ($recent_activity as $index => $activity): ?>
                <div class="recent-activity-item <?php echo ($index < count($recent_activity) - 1) ? 'recent-activity-item-bordered' : ''; ?>">
                    <div class="recent-activity-row">
                        <div class="recent-activity-icon">
                            <?php echo esc($activity['icon']); ?>
                        </div>
                        <div class="recent-activity-copy">
                            <div class="recent-activity-title"><?php echo esc($activity['title']); ?></div>
                            <div class="recent-activity-description"><?php echo esc($activity['description']); ?></div>
                            <div class="recent-activity-time"><?php echo esc($activity['time']); ?></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="dashboard-empty-state">
            <i class="fas fa-inbox fa-3x mb-3" style="opacity: 0.5;"></i>
            <p>No recent activity to display</p>
            <small>Activity will appear here as users interact with the platform.</small>
        </div>
    <?php endif; ?>
</div>

<style>
.dashboard-section-card {
    overflow: hidden;
}

.section-eyebrow {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    color: var(--admin-primary);
    margin-bottom: 8px;
}

.section-title {
    margin: 0;
    font-size: 22px;
    font-weight: 700;
    color: var(--text-dark);
}

.section-subtitle {
    margin: 6px 0 0;
    color: var(--text-light);
    line-height: 1.6;
}

.dashboard-stats-grid {
    align-items: stretch;
}

.dashboard-stat-card {
    min-height: 196px;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    text-align: left;
    gap: 18px;
    transition: all 0.3s ease;
}

.dashboard-stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px -5px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

.dashboard-stat-top {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.dashboard-stat-card .stat-icon {
    margin: 0;
    width: 48px;
    height: 48px;
    border-radius: 16px;
    font-size: 18px;
    flex-shrink: 0;
}

.dashboard-stat-card .stat-number {
    font-size: 30px;
    margin-bottom: 6px;
}

.dashboard-stat-card .stat-label {
    font-size: 13px;
}

.dashboard-stat-chip {
    display: inline-flex;
    align-items: center;
    padding: 6px 10px;
    border-radius: 999px;
    background: rgba(30, 64, 175, 0.08);
    color: var(--admin-primary);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
}

.dashboard-stat-note {
    margin-top: auto;
    font-size: 13px;
    color: var(--text-light);
    line-height: 1.6;
}

.renewal-alert-card {
    border-left: 5px solid #f59e0b;
}

.renewal-alert-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 18px;
    flex-wrap: wrap;
    margin-bottom: 18px;
}

.renewal-alert-title-wrap {
    display: flex;
    align-items: flex-start;
    gap: 14px;
}

.renewal-alert-icon {
    width: 44px;
    height: 44px;
    border-radius: 14px;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

.renewal-alert-btn {
    align-self: center;
}

.renewal-alert-list {
    display: grid;
    gap: 12px;
}

.renewal-alert-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    background: rgba(245, 158, 11, 0.08);
    border: 1px solid rgba(245, 158, 11, 0.18);
    border-radius: 16px;
    padding: 14px 16px;
    flex-wrap: wrap;
}

.renewal-alert-item-title {
    font-weight: 700;
    color: var(--text-dark);
}

.renewal-alert-plan {
    font-weight: 600;
    color: #b45309;
}

.renewal-alert-item-meta,
.renewal-alert-footnote {
    font-size: 13px;
    color: var(--text-light);
    margin-top: 4px;
}

.renewal-alert-link {
    text-decoration: none;
    font-size: 13px;
    font-weight: 700;
    color: #b45309;
}

.renewal-alert-link:hover {
    color: #92400e;
}

.money-overview-card {
    margin-top: 24px;
}

.revenue-report-card {
    margin-top: 24px;
}

.money-overview-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 18px;
}

.money-total-card {
    min-width: 290px;
    padding: 20px 22px;
    border-radius: 20px;
    background: linear-gradient(135deg, #0f172a, #1e3a8a);
    color: white;
    box-shadow: 0 18px 32px rgba(15, 23, 42, 0.18);
}

.money-total-label {
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.12em;
    color: rgba(255, 255, 255, 0.72);
}

.money-total-value {
    font-size: 32px;
    font-weight: 800;
    margin-top: 8px;
    line-height: 1.1;
}

.money-total-meta,
.money-total-updated {
    font-size: 12px;
    color: rgba(255, 255, 255, 0.72);
}

.money-total-meta {
    margin-top: 8px;
}

.money-total-updated {
    margin-top: 6px;
}

.money-overview-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(235px, 1fr));
    gap: 16px;
}

.revenue-metric-card {
    background: var(--bg-secondary);
    border: 1px solid rgba(0, 0, 0, 0.06);
    border-radius: var(--border-radius-lg);
    padding: 18px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    gap: 16px;
    min-height: 172px;
}

.revenue-metric-head {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
}

.revenue-metric-meta {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.revenue-icon {
    width: 42px !important;
    height: 42px !important;
    border-radius: 14px !important;
}

.revenue-metric-label {
    font-size: 12px;
    color: var(--text-light);
    text-transform: uppercase;
    letter-spacing: 0.08em;
}

.revenue-metric-value {
    font-size: 24px;
    font-weight: 800;
    color: var(--text-dark);
    margin-top: 4px;
    line-height: 1.2;
}

.revenue-metric-link {
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    white-space: nowrap;
}

.revenue-metric-link-muted {
    color: var(--text-light);
}

.revenue-metric-foot {
    font-size: 13px;
    color: var(--text-light);
    line-height: 1.6;
}

.revenue-metric-divider {
    margin: 0 6px;
    color: rgba(107, 114, 128, 0.7);
}

.revenue-report-table-wrap {
    overflow-x: auto;
}

.revenue-report-table {
    margin-bottom: 0;
    min-width: 860px;
}

.revenue-report-table thead th {
    border-bottom: 1px solid rgba(15, 23, 42, 0.08);
    color: #475569;
    font-size: 12px;
    font-weight: 800;
    letter-spacing: 0.08em;
    padding: 14px 12px;
    text-transform: uppercase;
}

.revenue-report-table tbody td {
    border-bottom: 1px solid rgba(15, 23, 42, 0.06);
    color: #334155;
    padding: 16px 12px;
}

.revenue-report-table tbody tr:last-child td {
    border-bottom: 0;
}

.revenue-report-table tfoot td {
    background: rgba(15, 23, 42, 0.03);
    border-top: 1px solid rgba(15, 23, 42, 0.08);
    color: #475569;
    font-size: 13px;
    font-weight: 700;
    padding: 14px 12px;
}

.revenue-source {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 245px;
}

.revenue-source-icon {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    background: rgba(30, 64, 175, 0.08);
    color: var(--admin-primary);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.revenue-source strong {
    display: block;
    color: #0f172a;
    font-size: 14px;
}

.revenue-source small {
    display: block;
    color: #64748b;
    margin-top: 3px;
}

.revenue-amount {
    color: #0f172a !important;
    font-size: 16px;
    font-weight: 800;
    white-space: nowrap;
}

.dashboard-section-heading {
    margin-bottom: 18px;
}

.reporting-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 18px;
    margin-bottom: 24px;
}

.reporting-card {
    margin-bottom: 0;
    padding: 22px;
    border: 1px solid rgba(15, 23, 42, 0.08);
    box-shadow: none;
}

.reporting-card-head {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
    padding-bottom: 16px;
    margin-bottom: 14px;
    border-bottom: 1px solid rgba(15, 23, 42, 0.08);
}

.reporting-label {
    color: var(--admin-primary);
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    margin-bottom: 6px;
}

.reporting-card h4 {
    margin: 0;
    color: var(--text-dark);
    font-size: 18px;
    font-weight: 800;
}

.reporting-icon {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    background: rgba(30, 64, 175, 0.08);
    color: var(--admin-primary);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.reporting-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    padding: 11px 0;
    border-bottom: 1px solid rgba(15, 23, 42, 0.06);
    color: var(--text-light);
    font-size: 14px;
}

.reporting-row:last-child {
    border-bottom: 0;
    padding-bottom: 0;
}

.reporting-row strong {
    color: var(--text-dark);
    font-size: 18px;
    white-space: nowrap;
}

.quick-actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 32px;
}

.quick-actions-grid .quick-action-card {
    margin-bottom: 0;
}

.quick-action-card {
    text-decoration: none;
    color: inherit;
    display: flex;
    flex-direction: column;
    gap: 18px;
    height: 100%;
    transition: all 0.3s ease;
}

.quick-action-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px -5px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

.quick-action-top {
    display: flex;
    align-items: flex-start;
    gap: 16px;
}

.quick-action-icon {
    width: 52px;
    height: 52px;
    border-radius: 18px;
    background: linear-gradient(135deg, var(--admin-primary), var(--admin-secondary));
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}

.quick-action-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 6px;
}

.quick-action-subtitle,
.quick-action-foot {
    font-size: 14px;
    color: var(--text-light);
    line-height: 1.6;
}

.quick-action-foot {
    margin-top: auto;
    padding-top: 14px;
    border-top: 1px solid rgba(0, 0, 0, 0.06);
}

.recent-activity-header {
    margin-bottom: 10px;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
}

.activity-view-all-link {
    align-self: center;
    border: 1px solid rgba(30, 64, 175, 0.18);
    border-radius: 10px;
    color: var(--admin-primary);
    font-size: 13px;
    font-weight: 700;
    padding: 9px 13px;
    text-decoration: none;
    transition: all 0.2s ease;
}

.activity-view-all-link:hover {
    background: rgba(30, 64, 175, 0.08);
    color: var(--admin-primary);
}

.recent-activity-list {
    display: grid;
}

.recent-activity-item {
    padding: 18px 0;
}

.recent-activity-item-bordered {
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.recent-activity-row {
    display: flex;
    align-items: flex-start;
    gap: 16px;
}

.recent-activity-icon {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--admin-primary), var(--admin-secondary));
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 16px;
    flex-shrink: 0;
}

.recent-activity-copy {
    min-width: 0;
}

.recent-activity-title {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 4px;
}

.recent-activity-description {
    font-size: 13px;
    color: var(--text-light);
    line-height: 1.6;
}

.recent-activity-time {
    font-size: 11px;
    color: var(--text-light);
    opacity: 0.85;
    margin-top: 4px;
}

.dashboard-empty-state {
    text-align: center;
    padding: 44px 20px;
    color: var(--text-light);
}

@media (max-width: 768px) {
    .reporting-grid {
        grid-template-columns: 1fr;
    }

    .money-total-card {
        width: 100%;
        min-width: 0;
    }

    .renewal-alert-btn {
        width: 100%;
        justify-content: center;
        text-align: center;
    }

    .renewal-alert-meta-divider,
    .revenue-metric-divider {
        display: none;
    }

    .renewal-alert-item-meta {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
}
</style>

<?= $this->endSection() ?>
