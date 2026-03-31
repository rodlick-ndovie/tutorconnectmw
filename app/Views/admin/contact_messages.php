<?= $this->extend('layouts/admin') ?>

<?php $active_page = 'messages'; ?>
<?php $title = $title ?? 'Contact Messages'; ?>

<?= $this->section('content') ?>
    <!-- Header -->
    <div class="header-bar">
        <div>
            <h1 class="page-title">Contact Messages</h1>
            <p class="page-subtitle">Manage and respond to user inquiries</p>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6, #1e40af);">
                <i class="fas fa-envelope"></i>
            </div>
            <div class="stat-number"><?php echo number_format($totalCount); ?></div>
            <div class="stat-label">Total Messages</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-number"><?php echo number_format($unreadCount); ?></div>
            <div class="stat-label">Unread Messages</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-number"><?php echo number_format($totalCount - $unreadCount); ?></div>
            <div class="stat-label">Read Messages</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-number"><?php echo $totalCount > 0 ? 'Active' : 'None'; ?></div>
            <div class="stat-label">Status</div>
        </div>
    </div>

    <!-- Content Card -->
    <div class="content-card">
        <?php if (!empty($messages)): ?>
            <div class="table-responsive">
                <table class="table table-bordered" id="messagesTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Subject</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($messages as $msg): ?>
                            <tr class="<?= $msg['is_read'] ? '' : 'table-warning' ?>">
                                <td>
                                    <?php if (!$msg['is_read']): ?>
                                        <span class="badge bg-danger">New</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">Read</span>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?= esc($msg['name']) ?></strong></td>
                                <td><a href="mailto:<?= esc($msg['email']) ?>" class="text-decoration-none"><?= esc($msg['email']) ?></a></td>
                                <td><?= esc(substr($msg['subject'], 0, 40)) . (strlen($msg['subject']) > 40 ? '...' : '') ?></td>
                                <td><?= date('M d, Y H:i', strtotime($msg['created_at'])) ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= site_url('admin/view-message/' . $msg['id']) ?>" class="btn btn-outline-primary" title="View Message">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger" onclick="deleteMessage(<?= $msg['id'] ?>)" title="Delete Message">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($pager): ?>
                <div style="margin-top: 20px; text-align: center;">
                    <?= $pager->links() ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div style="text-align: center; padding: 60px 20px;">
                <div style="font-size: 48px; color: var(--text-light); margin-bottom: 20px;">
                    <i class="fas fa-envelope"></i>
                </div>
                <h3 style="color: var(--text-light); margin-bottom: 16px;">No Messages Yet</h3>
                <p style="color: var(--text-light); margin-bottom: 24px;">Contact messages from users will appear here.</p>
            </div>
        <?php endif; ?>
    </div>

<script>
function deleteMessage(id) {
    if (confirm('Are you sure you want to delete this message?')) {
        fetch(`<?= site_url('admin/delete-message') ?>/${id}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        });
    }
}
</script>

<?= $this->endSection() ?>
