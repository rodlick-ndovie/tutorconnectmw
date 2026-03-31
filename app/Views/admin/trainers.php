<?= $this->extend('layouts/admin') ?>

<?php $active_page = 'trainers'; ?>
<?php $title = $title ?? 'Tutor Management - TutorConnect Malawi'; ?>

<?= $this->section('content') ?>

<!-- Header -->
<div class="header-bar">
    <div>
        <h1 class="page-title">Tutor Management</h1>
        <p class="page-subtitle">Manage verified tutors and their profiles</p>
    </div>
    <div style="display: flex; gap: 12px;">
        <a class="btn-admin" href="<?= site_url('admin/trainers/export') ?>" style="background: linear-gradient(135deg, #059669, #10b981);">
            <i class="fas fa-file-excel me-2"></i>Export Excel
        </a>
        <a class="btn-admin" href="<?= site_url('admin/trainers/export-pdf') ?>" style="background: linear-gradient(135deg, #dc2626, #ef4444);">
            <i class="fas fa-file-pdf me-2"></i>Export PDF
        </a>
        <a href="#" class="btn-admin">
            <i class="fas fa-user-plus me-2"></i>Invite Tutor
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6, #1e40af);">
            <i class="fas fa-chalkboard-teacher"></i>
        </div>
        <div class="stat-number"><?php echo number_format($stats['total_trainers'] ?? 0); ?></div>
        <div class="stat-label">Total Tutors</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
            <i class="fas fa-user-check"></i>
        </div>
        <div class="stat-number"><?php echo number_format($stats['approved_trainers'] ?? 0); ?></div>
        <div class="stat-label">Approved</div>
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

<!-- Tutor Cards -->
<?php if (!empty($trainers)): ?>
<div class="content-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">All Tutors</h4>
        <div class="d-flex gap-2">
            <select class="form-select" style="width: 150px;">
                <option>All Status</option>
                <option>Verified</option>
                <option>Pending</option>
                <option>Suspended</option>
            </select>
            <input type="text" class="form-control" placeholder="Search tutors..." style="width: 250px;">
        </div>
    </div>

    <?php foreach ($trainers as $tutor): ?>
    <div style="display: flex; align-items: center; padding: 20px; margin-bottom: 16px; background: var(--bg-secondary); border-radius: var(--border-radius); box-shadow: var(--shadow); border: 1px solid rgba(0, 0, 0, 0.05);">
        <?php if (!empty($tutor['profile_picture'])): ?>
            <div style="width: 60px; height: 60px; border-radius: 50%; overflow: hidden; margin-right: 16px; flex-shrink: 0; border: 2px solid var(--admin-primary);">
                <img src="<?php echo base_url($tutor['profile_picture']); ?>" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;"
                     onerror="this.onerror=null; this.parentElement.style.display='none'; this.parentElement.nextElementSibling.style.display='flex';">
            </div>
        <?php endif; ?>
        <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, var(--admin-primary), var(--admin-secondary)); color: white; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: 600; margin-right: 16px; flex-shrink: 0; <?php echo !empty($tutor['profile_picture']) ? 'display: none;' : ''; ?>">
            <?php echo strtoupper(substr($tutor['first_name'], 0, 1) . substr($tutor['last_name'], 0, 1)); ?>
        </div>
        <div style="flex: 1;">
            <div style="font-size: 18px; font-weight: 600; color: var(--text-dark); margin-bottom: 4px;">
                <?php echo esc($tutor['first_name'] . ' ' . $tutor['last_name']); ?>
            </div>
            <div style="color: var(--text-light); font-size: 14px; margin-bottom: 4px;">
                <?php echo esc($tutor['teaching_mode'] ?? 'Not specified'); ?> • <?php echo esc($tutor['experience_years'] ?? '0'); ?> years experience
            </div>
            <div style="display: flex; gap: 16px; margin-top: 8px; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; font-size: 12px; color: var(--text-light);">
                    <i class="fas fa-star me-1" style="color: #f59e0b;"></i>
                    <?php echo number_format($tutor['rating'] ?? 0, 1); ?> rating
                </div>
                <div style="display: flex; align-items: center; font-size: 12px; color: var(--text-light);">
                    <i class="fas fa-comments me-1"></i>
                    <?php echo number_format($tutor['review_count'] ?? 0); ?> reviews
                </div>
                <div style="display: flex; align-items: center; font-size: 12px; color: var(--text-light);">
                    <i class="fas fa-search me-1"></i>
                    <?php echo number_format($tutor['search_count'] ?? 0); ?> views
                </div>
                <div style="display: flex; align-items: center; font-size: 12px; color: var(--text-light);">
                    <i class="fas fa-map-marker-alt me-1"></i>
                    <?php echo esc($tutor['district'] ?? 'Not specified'); ?>
                </div>
            </div>
        </div>
        <div style="margin-left: 16px;">
            <?php if ($tutor['is_active'] == 0): ?>
                <span style="display: inline-block; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; margin-bottom: 8px;" class="bg-danger text-white">Suspended</span>
            <?php elseif (($tutor['tutor_status'] ?? '') == 'approved'): ?>
                <span style="display: inline-block; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; margin-bottom: 8px;" class="bg-success text-white">Approved</span>
            <?php elseif (($tutor['tutor_status'] ?? '') == 'pending'): ?>
                <span style="display: inline-block; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; margin-bottom: 8px;" class="bg-warning text-white">Pending Review</span>
            <?php elseif (($tutor['tutor_status'] ?? '') == 'rejected'): ?>
                <span style="display: inline-block; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; margin-bottom: 8px;" class="bg-danger text-white">Rejected</span>
            <?php else: ?>
                <span style="display: inline-block; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; margin-bottom: 8px;" class="bg-secondary text-white">No Status: <?php echo esc($tutor['tutor_status'] ?? 'null'); ?></span>
            <?php endif; ?>
                <div style="display: flex; gap: 8px;">
                    <button class="btn btn-outline-primary btn-sm" title="View Profile" onclick="viewTutor(<?php echo $tutor['id']; ?>)">
                        <i class="fas fa-eye"></i>
                    </button>

                    <!-- Suspend/Activate buttons for approved tutors -->
                    <?php if (($tutor['tutor_status'] ?? '') == 'approved' || ($tutor['tutor_status'] ?? '') == 'active'): ?>
                        <?php if ($tutor['is_active'] == 1): ?>
                            <button class="btn btn-outline-danger btn-sm" title="Suspend Tutor" onclick="suspendTutor(<?php echo $tutor['id']; ?>)">
                                <i class="fas fa-ban"></i>
                            </button>
                        <?php else: ?>
                            <button class="btn btn-outline-success btn-sm" title="Activate Tutor" onclick="activateTutor(<?php echo $tutor['id']; ?>)">
                                <i class="fas fa-check"></i>
                            </button>
                        <?php endif; ?>
                    <?php endif; ?>

                    <!-- Delete button for all tutors -->
                    <div class="dropdown">
                        <button class="btn btn-outline-dark btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Delete Options">
                            <i class="fas fa-trash"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><button class="dropdown-item" onclick="deleteTutor(<?php echo $tutor['id']; ?>, '<?php echo esc($tutor['first_name'] . ' ' . $tutor['last_name']); ?>')">
                                <i class="fas fa-ban me-2"></i>Soft Delete (Recoverable)
                            </button></li>
                            <li><button class="dropdown-item text-danger" onclick="deleteTutorPermanently(<?php echo $tutor['id']; ?>, '<?php echo esc($tutor['first_name'] . ' ' . $tutor['last_name']); ?>')">
                                <i class="fas fa-trash-alt me-2"></i>Permanent Delete (No Recovery)
                            </button></li>
                        </ul>
                    </div>
                </div>
        </div>
    </div>
    <?php endforeach; ?>

</div>
<?php else: ?>
<div class="content-card">
    <div style="text-align: center; padding: 60px 20px;">
        <div style="font-size: 48px; color: var(--text-light); margin-bottom: 20px;">
            <i class="fas fa-chalkboard-teacher"></i>
        </div>
        <h3 style="color: var(--text-light); margin-bottom: 16px;">No Tutors Yet</h3>
        <p style="color: var(--text-light); margin-bottom: 24px;">Get started by inviting qualified tutors to join your platform.</p>
        <a href="#" class="btn btn-admin">
            <i class="fas fa-user-plus me-2"></i>Invite First Tutor
        </a>
    </div>
</div>
<?php endif; ?>

<style>
.btn-sm {
    padding: 8px 16px;
    font-size: 14px;
}
</style>

<script>
function viewTutor(tutorId) {
    window.location.href = `<?= base_url('admin/trainers/view') ?>/${tutorId}`;
}





function suspendTutor(tutorId) {
    if (confirm('Are you sure you want to suspend this tutor? They will lose access to the platform temporarily.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `<?= base_url('admin/trainers/suspend') ?>/${tutorId}`;
        form.style.display = 'none';

        // Get CSRF token from meta tag
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = 'csrf_test_name';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);

        document.body.appendChild(form);
        form.submit();
    }
}

function activateTutor(tutorId) {
    if (confirm('Are you sure you want to activate this tutor? They will regain access to the platform.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `<?= base_url('admin/trainers/activate') ?>/${tutorId}`;
        form.style.display = 'none';

        // Get CSRF token from meta tag
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = 'csrf_test_name';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);

        document.body.appendChild(form);
        form.submit();
    }
}

function deleteTutorPermanently(tutorId, tutorName) {
    if (confirm(`⚠️ DANGER: This will PERMANENTLY DELETE ${tutorName} from the database!\n\nThis action CANNOT be undone and will:\n• Delete all profile data\n• Remove all uploaded files\n• Delete all videos and documents\n• Cancel all subscriptions\n• Remove tutor account completely\n\nAre you absolutely sure?`)) {
        // Get CSRF token from meta tag
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        if (!csrfMeta) {
            showNotification('CSRF token not found. Please refresh the page and try again.', 'error');
            return;
        }

        const csrfToken = csrfMeta.getAttribute('content');

        // Create XMLHttpRequest for permanent deletion
        const xhr = new XMLHttpRequest();
        xhr.open('POST', `<?= base_url('admin/trainers/delete-permanently') ?>/${tutorId}`, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                console.log('XHR Status:', xhr.status);
                console.log('XHR Response:', xhr.responseText);

                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        console.log('Parsed response:', response);

                        if (response.success) {
                            showNotification('Tutor permanently deleted from database!', 'success');
                            setTimeout(() => window.location.reload(), 1500);
                        } else {
                            showNotification('Error: ' + (response.message || 'Unknown error'), 'error');
                        }
                    } catch (e) {
                        // Check if response contains success indicators
                        if (xhr.responseText.includes('success') ||
                            xhr.responseText.includes('deleted') ||
                            xhr.responseText.includes('Tutor account permanently deleted')) {
                            showNotification('Tutor permanently deleted from database!', 'success');
                            setTimeout(() => window.location.reload(), 1500);
                        } else {
                            showNotification('Error: Unable to permanently delete tutor.', 'error');
                        }
                    }
                } else if (xhr.status === 302) {
                    console.log('Received redirect, following...');
                    showNotification('Tutor permanently deleted from database!', 'success');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    console.error('HTTP Error:', xhr.status, xhr.responseText);
                    showNotification('HTTP Error: ' + xhr.status + '. Please try again.', 'error');
                }
            }
        };

        // Send CSRF token as form data
        const data = 'csrf_test_name=' + encodeURIComponent(csrfToken);
        xhr.send(data);
    }
}

function deleteTutor(tutorId, tutorName) {
    if (confirm(`Are you sure you want to SOFT DELETE ${tutorName}?\n\nThis action will:\n• Mark tutor as deleted\n• Tutor cannot log in anymore\n• Preserve all data for potential recovery\n\nThe tutor can be RESTORED later if needed.`)) {
        // Get CSRF token from meta tag
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        if (!csrfMeta) {
            showNotification('CSRF token not found. Please refresh the page and try again.', 'error');
            return;
        }

        const csrfToken = csrfMeta.getAttribute('content');

        // Create XMLHttpRequest for more reliable POST request
        const xhr = new XMLHttpRequest();
        xhr.open('POST', `<?= base_url('admin/trainers/delete') ?>/${tutorId}`, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                console.log('XHR Status:', xhr.status);
                console.log('XHR Response:', xhr.responseText);

                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        console.log('Parsed response:', response);

                        if (response.success) {
                            showNotification('Tutor deleted successfully!', 'success');
                            setTimeout(() => window.location.reload(), 1500);
                        } else {
                            showNotification('Error: ' + (response.message || 'Unknown error'), 'error');
                        }
                    } catch (e) {
                        // If response is not JSON, check if it's a redirect (302) or error page
                        console.log('Response is not JSON, checking for redirects...');

                        // Check if response contains success indicators
                        if (xhr.responseText.includes('success') ||
                            xhr.responseText.includes('deleted') ||
                            xhr.responseText.includes('Tutor account permanently deleted')) {
                            showNotification('Tutor deleted successfully!', 'success');
                            setTimeout(() => window.location.reload(), 1500);
                        } else if (xhr.responseText.includes('Cannot delete tutor with active subscription')) {
                            showNotification('Cannot delete tutor with active subscription. Cancel subscription first.', 'error');
                        } else {
                            showNotification('Error: Unable to delete tutor. Please try again.', 'error');
                        }
                    }
                } else if (xhr.status === 302) {
                    // Handle redirect (success case for regular form submissions)
                    console.log('Received redirect, following...');
                    showNotification('Tutor deleted successfully!', 'success');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    console.error('HTTP Error:', xhr.status, xhr.responseText);
                    showNotification('HTTP Error: ' + xhr.status + '. Please try again.', 'error');
                }
            }
        };

        // Send CSRF token as form data
        const data = 'csrf_test_name=' + encodeURIComponent(csrfToken);
        xhr.send(data);
    }
}
</script>

<?= $this->endSection() ?>
