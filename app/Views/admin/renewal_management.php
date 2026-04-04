<?= $this->extend('layouts/admin') ?>

<?php $active_page = 'renewal_management'; ?>
<?php
$renewalSubscriptions = $renewal_subscriptions ?? [];
$reminderHistory = $reminder_history ?? [];
$sentReminderHistory = array_values(array_filter($reminderHistory, static function ($row) {
    return ($row['status'] ?? '') === 'sent';
}));
?>

<?= $this->section('content') ?>

<div class="header-bar">
    <div>
        <h1 class="page-title">Renewal Management</h1>
        <p class="page-subtitle">Track subscriptions nearing expiry, expired tutor plans, and every renewal alert already sent.</p>
    </div>

    <div class="renewal-page-actions">
        <a href="<?= base_url('admin/tutor-subscriptions'); ?>" class="btn-admin">
            <i class="fas fa-layer-group"></i>
            <span>Open Subscriptions</span>
        </a>
    </div>
</div>

<?php if (empty($reminder_table_exists)): ?>
    <div class="renewal-page-notice warning">
        <div class="renewal-page-notice-icon">
            <i class="fas fa-triangle-exclamation"></i>
        </div>
        <div>
            <div class="renewal-page-notice-title">Reminder log table not found</div>
            <div class="renewal-page-notice-copy">Renewal alerts can still be sent by the command, but this page cannot show alert history until the `subscription_renewal_reminders` table exists on this server.</div>
        </div>
    </div>
<?php endif; ?>

<div class="renewal-stats-grid">
    <div class="renewal-stat-card danger">
        <div class="renewal-stat-icon">
            <i class="fas fa-clock"></i>
        </div>
        <div class="renewal-stat-number"><?= number_format($stats['expired_count'] ?? 0); ?></div>
        <div class="renewal-stat-label">Expired Plans</div>
        <div class="renewal-stat-copy">Tutors who already need renewal follow-up.</div>
    </div>

    <div class="renewal-stat-card warning">
        <div class="renewal-stat-icon">
            <i class="fas fa-hourglass-half"></i>
        </div>
        <div class="renewal-stat-number"><?= number_format($stats['due_soon_count'] ?? 0); ?></div>
        <div class="renewal-stat-label">Due Soon</div>
        <div class="renewal-stat-copy">Active plans ending within the next 5 days.</div>
    </div>

    <div class="renewal-stat-card success">
        <div class="renewal-stat-icon">
            <i class="fas fa-envelope-circle-check"></i>
        </div>
        <div class="renewal-stat-number"><?= number_format($stats['alerts_sent_count'] ?? 0); ?></div>
        <div class="renewal-stat-label">Alerts Sent</div>
        <div class="renewal-stat-copy">Logged email reminders successfully delivered.</div>
    </div>

    <div class="renewal-stat-card neutral">
        <div class="renewal-stat-icon">
            <i class="fas fa-circle-exclamation"></i>
        </div>
        <div class="renewal-stat-number"><?= number_format($stats['alerts_failed_count'] ?? 0); ?></div>
        <div class="renewal-stat-label">Failed Alerts</div>
        <div class="renewal-stat-copy">Reminder attempts that still need attention.</div>
    </div>
</div>

<div class="renewal-content-grid">
    <section class="content-card renewal-panel">
        <div class="renewal-panel-head">
            <div>
                <div class="renewal-panel-eyebrow">Action Queue</div>
                <h3 class="renewal-panel-title">Renewal Follow-up List</h3>
                <p class="renewal-panel-subtitle">Everything that needs renewal attention now, including alerts already sent for the current expiry window.</p>
            </div>
        </div>

        <?php if (!empty($renewalSubscriptions)): ?>
            <div class="renewal-table-wrap">
                <table class="renewal-table">
                    <thead>
                        <tr>
                            <th>Tutor</th>
                            <th>Plan</th>
                            <th>Status</th>
                            <th>Expiry</th>
                            <th>Alert Progress</th>
                            <th>Last Alert</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($renewalSubscriptions as $subscription): ?>
                            <?php
                            $statusClass = ($subscription['renewal_bucket'] ?? '') === 'expired' ? 'expired' : 'due-soon';
                            $sentLabels = $subscription['sent_reminders'] ?? [];
                            $failedLabels = $subscription['failed_reminders'] ?? [];
                            $skippedLabels = $subscription['skipped_reminders'] ?? [];
                            ?>
                            <tr>
                                <td>
                                    <div class="renewal-person-name"><?= esc($subscription['trainer_name'] ?? 'Unknown'); ?></div>
                                    <div class="renewal-person-meta">
                                        <?= esc($subscription['email'] ?? ''); ?>
                                        <?php if (!empty($subscription['phone'])): ?>
                                            <span class="renewal-inline-divider">|</span>
                                            <?= esc($subscription['phone']); ?>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="renewal-plan-name"><?= esc($subscription['plan_name'] ?? 'Plan'); ?></div>
                                    <div class="renewal-plan-price">
                                        <?php if (!empty($subscription['price_monthly'])): ?>
                                            MK <?= number_format((float) $subscription['price_monthly']); ?>/month
                                        <?php else: ?>
                                            Pricing not available
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="renewal-status-badge <?= esc($statusClass); ?>">
                                        <?= ($subscription['renewal_bucket'] ?? '') === 'expired' ? 'Expired' : 'Due Soon'; ?>
                                    </span>
                                    <div class="renewal-status-copy"><?= esc($subscription['time_remaining_label'] ?? ''); ?></div>
                                </td>
                                <td>
                                    <div class="renewal-date"><?= !empty($subscription['current_period_end']) ? date('M d, Y', strtotime($subscription['current_period_end'])) : 'N/A'; ?></div>
                                    <div class="renewal-date-meta"><?= !empty($subscription['current_period_end']) ? date('H:i', strtotime($subscription['current_period_end'])) : ''; ?></div>
                                </td>
                                <td>
                                    <?php if (!empty($sentLabels)): ?>
                                        <div class="renewal-chip-row">
                                            <?php foreach ($sentLabels as $label): ?>
                                                <span class="renewal-chip success"><?= esc($label); ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="renewal-muted">No sent alert yet</div>
                                    <?php endif; ?>

                                    <?php if (!empty($failedLabels)): ?>
                                        <div class="renewal-chip-row renewal-chip-row-spaced">
                                            <?php foreach ($failedLabels as $label): ?>
                                                <span class="renewal-chip danger"><?= esc($label); ?> failed</span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($skippedLabels)): ?>
                                        <div class="renewal-chip-row renewal-chip-row-spaced">
                                            <?php foreach ($skippedLabels as $label): ?>
                                                <span class="renewal-chip neutral"><?= esc($label); ?> skipped</span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($subscription['last_alert_sent_at'])): ?>
                                        <div class="renewal-date"><?= date('M d, Y', strtotime($subscription['last_alert_sent_at'])); ?></div>
                                        <div class="renewal-date-meta"><?= date('H:i', strtotime($subscription['last_alert_sent_at'])); ?></div>
                                    <?php else: ?>
                                        <div class="renewal-muted">Not sent yet</div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('admin/tutor-subscriptions'); ?>" class="renewal-table-link">Review</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="renewal-empty-state">
                <div class="renewal-empty-icon">
                    <i class="fas fa-circle-check"></i>
                </div>
                <h4>No renewals need attention right now</h4>
                <p>There are no expired subscriptions and no active plans inside the next 5-day reminder window.</p>
            </div>
        <?php endif; ?>
    </section>

    <section class="content-card renewal-panel">
        <div class="renewal-panel-head">
            <div>
                <div class="renewal-panel-eyebrow">Communication Log</div>
                <h3 class="renewal-panel-title">Sent Renewal Alerts</h3>
                <p class="renewal-panel-subtitle">A history of reminder emails already recorded for tutor subscription renewals.</p>
            </div>
            <div class="renewal-panel-meta-pill"><?= number_format(count($sentReminderHistory)); ?> sent</div>
        </div>

        <?php if (!empty($reminderHistory)): ?>
            <div class="renewal-table-wrap">
                <table class="renewal-table">
                    <thead>
                        <tr>
                            <th>Tutor</th>
                            <th>Plan</th>
                            <th>Reminder</th>
                            <th>Status</th>
                            <th>Recipient</th>
                            <th>Sent At</th>
                            <th>Target Expiry</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reminderHistory as $reminder): ?>
                            <tr>
                                <td>
                                    <div class="renewal-person-name"><?= esc($reminder['trainer_name'] ?? 'Unknown'); ?></div>
                                </td>
                                <td>
                                    <div class="renewal-plan-name"><?= esc($reminder['plan_name'] ?? 'Plan'); ?></div>
                                </td>
                                <td>
                                    <span class="renewal-chip neutral"><?= esc($reminder['reminder_type'] ?? 'Reminder'); ?></span>
                                </td>
                                <td>
                                    <span class="renewal-status-badge history-<?= esc($reminder['status'] ?? 'sent'); ?>">
                                        <?= ucfirst(esc($reminder['status'] ?? 'sent')); ?>
                                    </span>
                                    <?php if (!empty($reminder['error_message'])): ?>
                                        <div class="renewal-status-copy"><?= esc($reminder['error_message']); ?></div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="renewal-person-meta"><?= esc($reminder['email'] ?? ''); ?></div>
                                </td>
                                <td>
                                    <?php if (!empty($reminder['sent_at'])): ?>
                                        <div class="renewal-date"><?= date('M d, Y', strtotime($reminder['sent_at'])); ?></div>
                                        <div class="renewal-date-meta"><?= date('H:i', strtotime($reminder['sent_at'])); ?></div>
                                    <?php else: ?>
                                        <div class="renewal-muted">Not sent</div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="renewal-date"><?= !empty($reminder['target_period_end']) ? date('M d, Y', strtotime($reminder['target_period_end'])) : 'N/A'; ?></div>
                                    <div class="renewal-date-meta"><?= !empty($reminder['target_period_end']) ? date('H:i', strtotime($reminder['target_period_end'])) : ''; ?></div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="renewal-empty-state compact">
                <div class="renewal-empty-icon">
                    <i class="fas fa-envelope-open-text"></i>
                </div>
                <h4>No reminder history yet</h4>
                <p>Once the renewal reminder command sends emails, they will be logged here automatically.</p>
            </div>
        <?php endif; ?>
    </section>
</div>

<style>
.renewal-page-actions {
    display: flex;
    align-items: center;
    gap: 12px;
}

.renewal-page-notice {
    display: flex;
    gap: 16px;
    align-items: flex-start;
    background: #ffffff;
    border-radius: 18px;
    padding: 18px 20px;
    margin-bottom: 24px;
    box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.renewal-page-notice.warning {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.08), rgba(255, 255, 255, 0.96));
}

.renewal-page-notice-icon {
    width: 44px;
    height: 44px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: #ffffff;
    font-size: 18px;
    flex-shrink: 0;
}

.renewal-page-notice-title {
    font-size: 16px;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 4px;
}

.renewal-page-notice-copy {
    color: var(--text-light);
    line-height: 1.6;
}

.renewal-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-bottom: 28px;
}

.renewal-stat-card {
    position: relative;
    overflow: hidden;
    min-height: 190px;
    border-radius: 20px;
    background: #ffffff;
    border: 1px solid rgba(148, 163, 184, 0.18);
    box-shadow: 0 14px 32px rgba(15, 23, 42, 0.06);
    padding: 22px;
}

.renewal-stat-card::before {
    content: '';
    position: absolute;
    inset: 0 auto 0 0;
    width: 4px;
    background: #1e40af;
}

.renewal-stat-card.danger::before { background: #ef4444; }
.renewal-stat-card.warning::before { background: #f59e0b; }
.renewal-stat-card.success::before { background: #10b981; }
.renewal-stat-card.neutral::before { background: #64748b; }

.renewal-stat-icon {
    width: 54px;
    height: 54px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 18px;
    font-size: 20px;
    color: #ffffff;
    background: linear-gradient(135deg, #1e40af, #1e293b);
}

.renewal-stat-card.danger .renewal-stat-icon { background: linear-gradient(135deg, #ef4444, #b91c1c); }
.renewal-stat-card.warning .renewal-stat-icon { background: linear-gradient(135deg, #f59e0b, #b45309); }
.renewal-stat-card.success .renewal-stat-icon { background: linear-gradient(135deg, #10b981, #047857); }
.renewal-stat-card.neutral .renewal-stat-icon { background: linear-gradient(135deg, #64748b, #334155); }

.renewal-stat-number {
    font-size: 34px;
    font-weight: 800;
    color: var(--text-dark);
    line-height: 1;
    margin-bottom: 10px;
}

.renewal-stat-label {
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #475569;
    margin-bottom: 8px;
}

.renewal-stat-copy {
    color: var(--text-light);
    line-height: 1.6;
    font-size: 14px;
}

.renewal-content-grid {
    display: grid;
    gap: 24px;
}

.renewal-panel {
    padding: 0;
    overflow: hidden;
    border: 1px solid rgba(148, 163, 184, 0.16);
    box-shadow: 0 18px 38px rgba(15, 23, 42, 0.05);
}

.renewal-panel-head {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
    padding: 24px 24px 18px;
    border-bottom: 1px solid rgba(148, 163, 184, 0.14);
    background: linear-gradient(180deg, rgba(255, 255, 255, 1), rgba(248, 250, 252, 1));
}

.renewal-panel-eyebrow {
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #64748b;
    margin-bottom: 6px;
}

.renewal-panel-title {
    margin: 0;
    font-size: 24px;
    font-weight: 800;
    color: var(--text-dark);
}

.renewal-panel-subtitle {
    margin: 8px 0 0;
    color: var(--text-light);
    line-height: 1.6;
    max-width: 760px;
}

.renewal-panel-meta-pill {
    padding: 10px 14px;
    border-radius: 999px;
    background: rgba(30, 64, 175, 0.08);
    color: #1e40af;
    font-size: 13px;
    font-weight: 700;
    white-space: nowrap;
}

.renewal-table-wrap {
    overflow-x: auto;
}

.renewal-table {
    width: 100%;
    border-collapse: collapse;
}

.renewal-table th,
.renewal-table td {
    padding: 16px 24px;
    text-align: left;
    vertical-align: top;
    border-bottom: 1px solid rgba(226, 232, 240, 0.9);
}

.renewal-table th {
    font-size: 12px;
    font-weight: 800;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #64748b;
    background: #f8fafc;
}

.renewal-table tbody tr:hover {
    background: rgba(248, 250, 252, 0.82);
}

.renewal-person-name,
.renewal-plan-name,
.renewal-date {
    color: var(--text-dark);
    font-weight: 700;
}

.renewal-person-meta,
.renewal-plan-price,
.renewal-date-meta,
.renewal-status-copy,
.renewal-muted {
    color: var(--text-light);
    font-size: 13px;
    margin-top: 4px;
    line-height: 1.5;
}

.renewal-inline-divider {
    margin: 0 6px;
}

.renewal-status-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 92px;
    padding: 8px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 800;
    letter-spacing: 0.03em;
}

.renewal-status-badge.expired {
    background: rgba(239, 68, 68, 0.12);
    color: #b91c1c;
}

.renewal-status-badge.due-soon {
    background: rgba(245, 158, 11, 0.14);
    color: #b45309;
}

.renewal-status-badge.history-sent {
    background: rgba(16, 185, 129, 0.14);
    color: #047857;
}

.renewal-status-badge.history-failed {
    background: rgba(239, 68, 68, 0.12);
    color: #b91c1c;
}

.renewal-status-badge.history-skipped {
    background: rgba(100, 116, 139, 0.14);
    color: #475569;
}

.renewal-chip-row {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.renewal-chip-row-spaced {
    margin-top: 8px;
}

.renewal-chip {
    display: inline-flex;
    align-items: center;
    padding: 6px 10px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
    line-height: 1;
}

.renewal-chip.success {
    background: rgba(16, 185, 129, 0.12);
    color: #047857;
}

.renewal-chip.danger {
    background: rgba(239, 68, 68, 0.12);
    color: #b91c1c;
}

.renewal-chip.neutral {
    background: rgba(148, 163, 184, 0.16);
    color: #334155;
}

.renewal-table-link {
    display: inline-flex;
    align-items: center;
    color: #1e40af;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
}

.renewal-table-link:hover {
    color: #1d4ed8;
}

.renewal-empty-state {
    text-align: center;
    padding: 42px 24px 46px;
}

.renewal-empty-state.compact {
    padding: 34px 24px 38px;
}

.renewal-empty-icon {
    width: 64px;
    height: 64px;
    margin: 0 auto 16px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(30, 64, 175, 0.08);
    color: #1e40af;
    font-size: 24px;
}

.renewal-empty-state h4 {
    margin: 0 0 8px;
    color: var(--text-dark);
    font-size: 20px;
    font-weight: 800;
}

.renewal-empty-state p {
    margin: 0;
    color: var(--text-light);
    line-height: 1.6;
}

@media (max-width: 900px) {
    .header-bar {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }

    .renewal-panel-head {
        flex-direction: column;
        align-items: flex-start;
    }
}

@media (max-width: 768px) {
    .renewal-table th,
    .renewal-table td {
        padding: 14px 16px;
    }

    .renewal-stat-card {
        min-height: auto;
    }
}
</style>

<?= $this->endSection() ?>
