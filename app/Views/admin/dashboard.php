<?= $this->extend('layouts/admin') ?>

<?php $active_page = 'dashboard'; ?>

<?= $this->section('content') ?>

<!-- Header -->
<div class="header-bar">
    <div>
        <h1 class="page-title"><?php echo $dashboard_title ?? 'Admin Dashboard'; ?></h1>
        <p class="page-subtitle">Welcome back, <?php echo $user['first_name'] ?? $user['username']; ?>! Here's what's happening with your platform.</p>
    </div>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6, #1e40af);">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-number"><?php echo number_format($stats['total_users'] ?? 0); ?></div>
        <div class="stat-label">Total Users</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
            <i class="fas fa-chalkboard-teacher"></i>
        </div>
        <div class="stat-number"><?php echo number_format($stats['verified_tutors'] ?? 0); ?></div>
        <div class="stat-label">Verified Tutors</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
            <i class="fas fa-star"></i>
        </div>
        <div class="stat-number"><?php echo number_format($stats['avg_rating'] ?? 0, 1); ?></div>
        <div class="stat-label">Avg Rating</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-number"><?php echo number_format($stats['pending_verification'] ?? 0); ?></div>
        <div class="stat-label">Pending Review</div>
    </div>
</div>

<!-- Money Overview (separate) -->
<div class="content-card" style="margin-top: 24px;">
    <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 18px; flex-wrap: wrap;">
        <div>
            <h3 style="margin: 0; font-size: 20px; font-weight: 700; color: var(--text-dark);">Money Overview</h3>
            <p style="margin: 6px 0 0; color: var(--text-light); line-height: 1.5;">
                A clean snapshot of revenue signals. <span class="text-muted">MRR is recurring revenue from active subscriptions.</span>
            </p>
        </div>
        <div style="text-align: right; min-width: 260px;">
            <div style="font-size: 12px; color: var(--text-light); text-transform: uppercase; letter-spacing: 0.5px;">Total Revenue (MRR + Japan Fees)</div>
            <div style="font-size: 30px; font-weight: 850; color: var(--text-dark); margin-top: 2px;">MK <?php echo number_format($stats['total_revenue'] ?? 0); ?></div>
            <div style="font-size: 12px; color: var(--text-light); margin-top: 4px;">
                Updated: <?= date('M d, Y H:i') ?>
            </div>
        </div>
    </div>

    <div style="height: 1px; background: rgba(0,0,0,0.06); margin: 14px 0;"></div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px;">
        <div style="background: var(--bg-secondary); border: 1px solid rgba(0,0,0,0.06); border-radius: var(--border-radius-lg); padding: 14px;">
            <div style="display:flex; align-items:center; justify-content:space-between; gap: 12px;">
                <div style="display:flex; align-items:center; gap: 12px;">
                    <div class="stat-icon" style="margin: 0; width: 36px; height: 36px; border-radius: 12px; background: linear-gradient(135deg, #10b981, #059669);">
                        <i class="fas fa-repeat"></i>
                    </div>
                    <div>
                        <div style="font-size: 12px; color: var(--text-light); text-transform: uppercase; letter-spacing: 0.5px;">Active Subscription MRR</div>
                        <div style="font-size: 22px; font-weight: 800; color: var(--text-dark); margin-top: 2px;">MK <?php echo number_format($stats['subscription_revenue'] ?? 0); ?></div>
                    </div>
                </div>
                <a href="<?php echo base_url('admin/tutor-subscriptions'); ?>" style="font-size: 12px; text-decoration: none; font-weight: 600;">Details</a>
            </div>
            <div style="font-size: 12px; color: var(--text-light); margin-top: 8px;">Active subscriptions only.</div>
        </div>

        <div style="background: var(--bg-secondary); border: 1px solid rgba(0,0,0,0.06); border-radius: var(--border-radius-lg); padding: 14px;">
            <div style="display:flex; align-items:center; justify-content:space-between; gap: 12px;">
                <div style="display:flex; align-items:center; gap: 12px;">
                    <div class="stat-icon" style="margin: 0; width: 36px; height: 36px; border-radius: 12px; background: linear-gradient(135deg, #f59e0b, #d97706);">
                        <i class="fas fa-plane-departure"></i>
                    </div>
                    <div>
                        <div style="font-size: 12px; color: var(--text-light); text-transform: uppercase; letter-spacing: 0.5px;">Japan Application Fees</div>
                        <div style="font-size: 22px; font-weight: 800; color: var(--text-dark); margin-top: 2px;">MK <?php echo number_format($stats['japan_application_fees_total'] ?? 0); ?></div>
                    </div>
                </div>
                <a href="<?php echo base_url('admin/japan-payments'); ?>" style="font-size: 12px; text-decoration: none; font-weight: 600;">Details</a>
            </div>
            <div style="font-size: 12px; color: var(--text-light); margin-top: 8px;">
                Verified payments: <strong><?php echo number_format($stats['japan_verified_payments_total'] ?? 0); ?></strong>
                | Submitted applications: <strong><?php echo number_format($stats['japan_applications_total'] ?? 0); ?></strong>
            </div>
        </div>

        <div style="background: var(--bg-secondary); border: 1px solid rgba(0,0,0,0.06); border-radius: var(--border-radius-lg); padding: 14px;">
            <div style="display:flex; align-items:center; justify-content:space-between; gap: 12px;">
                <div style="display:flex; align-items:center; gap: 12px;">
                    <div class="stat-icon" style="margin: 0; width: 36px; height: 36px; border-radius: 12px; background: linear-gradient(135deg, #8b5cf6, #6d28d9);">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <div>
                        <div style="font-size: 12px; color: var(--text-light); text-transform: uppercase; letter-spacing: 0.5px;">Verified Subscription Payments</div>
                        <div style="font-size: 22px; font-weight: 800; color: var(--text-dark); margin-top: 2px;">MK <?php echo number_format($stats['paychangu_revenue'] ?? 0); ?></div>
                    </div>
                </div>
                <!-- Removed repeated link (same destination as MRR details) -->
            </div>
            <div style="font-size: 12px; color: var(--text-light); margin-top: 8px;">Verified payments only.</div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 32px;">
    <a href="<?php echo base_url('admin/users'); ?>" class="content-card" style="text-decoration: none; color: inherit; transition: all 0.3s ease;">
        <div style="display: flex; align-items: center; margin-bottom: 16px;">
            <div style="width: 48px; height: 48px; border-radius: 16px; background: linear-gradient(135deg, var(--admin-primary), var(--admin-secondary)); color: white; display: flex; align-items: center; justify-content: center; font-size: 20px; margin-right: 16px;">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <div style="font-size: 18px; font-weight: 600; color: var(--text-dark); margin-bottom: 4px;">Manage Users</div>
                <div style="font-size: 14px; color: var(--text-light); margin: 0;">View and manage all platform users</div>
            </div>
        </div>
    </a>
    <a href="<?php echo base_url('admin/trainers'); ?>" class="content-card" style="text-decoration: none; color: inherit; transition: all 0.3s ease;">
        <div style="display: flex; align-items: center; margin-bottom: 16px;">
            <div style="width: 48px; height: 48px; border-radius: 16px; background: linear-gradient(135deg, var(--admin-primary), var(--admin-secondary)); color: white; display: flex; align-items: center; justify-content: center; font-size: 20px; margin-right: 16px;">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <div>
                <div style="font-size: 18px; font-weight: 600; color: var(--text-dark); margin-bottom: 4px;">Tutor Management</div>
                <div style="font-size: 14px; color: var(--text-light); margin: 0;">Manage and approve tutors</div>
            </div>
        </div>
    </a>
    <a href="<?php echo base_url('admin/settings'); ?>" class="content-card" style="text-decoration: none; color: inherit; transition: all 0.3s ease;">
        <div style="display: flex; align-items: center; margin-bottom: 16px;">
            <div style="width: 48px; height: 48px; border-radius: 16px; background: linear-gradient(135deg, var(--admin-primary), var(--admin-secondary)); color: white; display: flex; align-items: center; justify-content: center; font-size: 20px; margin-right: 16px;">
                <i class="fas fa-cog"></i>
            </div>
            <div>
                <div style="font-size: 18px; font-weight: 600; color: var(--text-dark); margin-bottom: 4px;">System Settings</div>
                <div style="font-size: 14px; color: var(--text-light); margin: 0;">Configure platform settings</div>
            </div>
        </div>
    </a>
</div>


<!-- Recent Activity -->
<div class="content-card">
    <h3 style="font-size: 20px; font-weight: 600; color: var(--text-dark); margin-bottom: 20px;">Recent Activity</h3>

    <?php if (!empty($recent_activity)): ?>
        <?php foreach ($recent_activity as $index => $activity): ?>
            <div style="margin-bottom: 16px; padding: 16px 0; <?php echo ($index < count($recent_activity) - 1) ? 'border-bottom: 1px solid rgba(0, 0, 0, 0.05);' : ''; ?>">
                <div style="display: flex; align-items: center;">
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, var(--admin-primary), var(--admin-secondary)); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 16px; margin-right: 16px;">
                        <?php echo esc($activity['icon']); ?>
                    </div>
                    <div>
                        <div style="font-size: 14px; font-weight: 500; color: var(--text-dark); margin-bottom: 2px;">
                            <?php echo esc($activity['title']); ?>
                        </div>
                        <div style="font-size: 13px; color: var(--text-light);">
                            <?php echo esc($activity['description']); ?>
                        </div>
                        <div style="font-size: 11px; color: var(--text-light); opacity: 0.8; margin-top: 2px;">
                            <?php echo esc($activity['time']); ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <!-- Fallback for no recent activity -->
        <div style="text-align: center; padding: 40px 20px; color: var(--text-light);">
            <i class="fas fa-inbox fa-3x mb-3" style="opacity: 0.5;"></i>
            <p>No recent activity to display</p>
            <small>Activity will appear here as users interact with the platform</small>
        </div>
    <?php endif; ?>
</div>

<style>
.content-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px -5px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

a.content-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px -5px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}
</style>

<?= $this->endSection() ?>
