<?= $this->extend('layouts/admin') ?>

<?php $active_page = 'users'; ?>
<?php $title = $title ?? 'User Management - TutorConnect Malawi'; ?>
<?php $currentAdminId = (int) session()->get('user_id'); ?>

<?= $this->section('content') ?>

<!-- Header -->
<div class="header-bar">
    <div>
        <h1 class="page-title">User Management</h1>
        <p class="page-subtitle">Manage all users in the system</p>
    </div>
    <div style="display: flex; gap: 12px;">
        <a class="btn-admin" href="<?= site_url('admin/users/export') ?>" style="background: linear-gradient(135deg, #059669, #10b981);">
            <i class="fas fa-file-excel me-2"></i>Export Excel
        </a>
        <a class="btn-admin" href="<?= site_url('admin/users/export-pdf') ?>" style="background: linear-gradient(135deg, #dc2626, #ef4444);">
            <i class="fas fa-file-pdf me-2"></i>Export PDF
        </a>
        <button class="btn-admin" data-bs-toggle="modal" data-bs-target="#addAdminModal" style="background: linear-gradient(135deg, #2C3E50, #34495E);">
            <i class="fas fa-user-shield me-2"></i>Add Admin
        </button>
        <button class="btn-admin" data-bs-toggle="modal" data-bs-target="#addTrainerModal">
            <i class="fas fa-chalkboard-teacher me-2"></i>Add Trainer
        </button>
    </div>
</div>

<!-- Stats Overview -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6, #1e40af);">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-number"><?php echo $total_users ?? 150; ?></div>
        <div class="stat-label">Total Users</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-number"><?php echo $active_users ?? 142; ?></div>
        <div class="stat-label">Active Users</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
            <i class="fas fa-times-circle"></i>
        </div>
        <div class="stat-number"><?php echo $inactive_users ?? 8; ?></div>
        <div class="stat-label">Inactive Users</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-number"><?php echo count($recent_users ?? []); ?></div>
        <div class="stat-label">Recent Signups</div>
    </div>
</div>

<!-- User Role Distribution -->
<div class="content-card">
    <h3 style="margin-bottom: 16px; font-size: 18px; font-weight: 600; color: var(--text-dark);">User Distribution by Role</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 16px; margin-top: 16px;">
        <?php
        $role_data = $user_roles ?? ['students' => 89, 'trainers' => 25, 'admins' => 5, 'customers' => 31];
        $role_labels = [
            'students' => 'Students',
            'trainers' => 'Tutors',
            'university_tutors' => 'Uni Tutors',
            'admins' => 'Admins',
            'customers' => 'Customers',
        ];
        $total = array_sum($role_data);
        foreach ($role_data as $role => $count) {
            $percentage = $total > 0 ? round(($count / $total) * 100, 1) : 0;
            $roleLabel = $role_labels[$role] ?? ucwords(str_replace('_', ' ', $role));
            ?>
            <div style="text-align: center;">
                <div style="font-size: 24px; font-weight: 700; color: var(--admin-primary); margin-bottom: 4px;"><?php echo $percentage; ?>%</div>
                <div style="font-size: 12px; color: var(--text-light); font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;"><?php echo esc($roleLabel); ?></div>
            </div>
            <?php
        }
        ?>
    </div>
</div>

<!-- Users Table -->
<div class="content-card">
    <?php
        $filters = $filters ?? ['q' => '', 'role' => '', 'status' => '', 'portal' => '', 'per_page' => 10];
        $pager = $pager ?? ['current_page' => 1, 'per_page' => 10, 'total' => count($all_users ?? []), 'total_pages' => 1, 'offset' => 0];
        $paginationQuery = $pagination_query ?? [];
        $fromRecord = ($pager['total'] ?? 0) > 0 ? ((int) ($pager['offset'] ?? 0) + 1) : 0;
        $toRecord = min((int) ($pager['total'] ?? 0), (int) ($pager['offset'] ?? 0) + count($all_users ?? []));
    ?>
    <form method="get" action="<?= site_url('admin/users') ?>" class="user-filter-bar">
        <div class="filter-field filter-search">
            <label for="userSearch">Search</label>
            <input type="text" id="userSearch" name="q" value="<?= esc($filters['q'] ?? '') ?>" placeholder="Name, email, username, phone">
        </div>
        <div class="filter-field">
            <label for="roleFilter">Role</label>
            <select id="roleFilter" name="role">
                <option value="">All roles</option>
                <?php foreach (['admin' => 'Admin', 'sub-admin' => 'Sub Admin', 'trainer' => 'Trainer', 'customer' => 'Customer'] as $roleValue => $roleText): ?>
                    <option value="<?= esc($roleValue) ?>" <?= ($filters['role'] ?? '') === $roleValue ? 'selected' : '' ?>><?= esc($roleText) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="filter-field">
            <label for="statusFilter">Status</label>
            <select id="statusFilter" name="status">
                <option value="">All statuses</option>
                <option value="active" <?= ($filters['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
        <div class="filter-field">
            <label for="portalFilter">Portal</label>
            <select id="portalFilter" name="portal">
                <option value="">All portals</option>
                <option value="main" <?= ($filters['portal'] ?? '') === 'main' ? 'selected' : '' ?>>Main portal</option>
                <option value="university" <?= ($filters['portal'] ?? '') === 'university' ? 'selected' : '' ?>>University portal</option>
            </select>
        </div>
        <div class="filter-field">
            <label for="perPageFilter">Rows</label>
            <select id="perPageFilter" name="per_page">
                <?php foreach ([10, 25, 50, 100] as $perPageOption): ?>
                    <option value="<?= $perPageOption ?>" <?= (int) ($filters['per_page'] ?? 10) === $perPageOption ? 'selected' : '' ?>><?= $perPageOption ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="filter-actions">
            <button type="submit" class="btn-filter-primary"><i class="fas fa-filter me-1"></i>Apply</button>
            <a href="<?= site_url('admin/users') ?>" class="btn-filter-reset">Reset</a>
        </div>
    </form>

    <div class="table-meta">
        <div>Showing <?= number_format($fromRecord) ?>-<?= number_format($toRecord) ?> of <?= number_format((int) ($pager['total'] ?? 0)) ?> users</div>
        <div>Page <?= (int) ($pager['current_page'] ?? 1) ?> of <?= (int) ($pager['total_pages'] ?? 1) ?></div>
    </div>

    <div style="overflow-x: auto;">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th style="background-color: var(--bg-dropdown); border: none; font-weight: 600; color: var(--text-dark); padding: 16px;">#</th>
                    <th style="background-color: var(--bg-dropdown); border: none; font-weight: 600; color: var(--text-dark); padding: 16px;">User</th>
                    <th style="background-color: var(--bg-dropdown); border: none; font-weight: 600; color: var(--text-dark); padding: 16px;">Role</th>
                    <th style="background-color: var(--bg-dropdown); border: none; font-weight: 600; color: var(--text-dark); padding: 16px;">Status</th>
                    <th style="background-color: var(--bg-dropdown); border: none; font-weight: 600; color: var(--text-dark); padding: 16px;">Joined</th>
                    <th style="background-color: var(--bg-dropdown); border: none; font-weight: 600; color: var(--text-dark); padding: 16px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($all_users)): ?>
                <?php foreach ($all_users ?? [] as $rowIndex => $user): ?>
                <?php
                    $userId = (int) ($user['id'] ?? 0);
                    $isUniversityTutor = ($user['admin_portal_type'] ?? '') === 'university';
                    $roleLabel = $user['admin_role_label'] ?? ucfirst($user['role'] ?? 'user');
                    $roleBadgeClass = $isUniversityTutor ? 'bg-info text-dark' : 'bg-primary text-white';
                    $profileUrl = '';
                    if ($isUniversityTutor && !empty($user['linked_university_tutor_id'])) {
                        $profileUrl = site_url('admin/view-university-college-tutor/' . (int) $user['linked_university_tutor_id']);
                    } elseif (($user['role'] ?? '') === 'trainer') {
                        $profileUrl = site_url('admin/trainers/view/' . $userId);
                    }
                    $userFullName = trim((string) (($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')));
                    $userFullName = $userFullName !== '' ? $userFullName : (string) ($user['username'] ?? 'User');
                    $isSelf = $userId === $currentAdminId;
                ?>
                <tr>
                    <td style="font-weight: 700; color: var(--text-light);"><?= number_format((int) ($pager['offset'] ?? 0) + $rowIndex + 1) ?></td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, var(--admin-primary), var(--admin-secondary)); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                                <?php echo esc(strtoupper(substr((string) ($user['username'] ?? 'U'), 0, 1))); ?>
                            </div>
                            <div>
                                <h6 style="margin: 0; font-size: 14px; font-weight: 600; color: var(--text-dark);"><?= esc($userFullName) ?></h6>
                                <div style="font-size: 12px; color: var(--text-light);"><?= esc($user['email'] ?? '') ?></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span style="padding: 4px 12px; border-radius: 12px; font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;" class="<?= $roleBadgeClass ?>">
                            <?php echo esc($roleLabel); ?>
                        </span>
                    </td>
                    <td>
                        <span style="padding: 4px 12px; border-radius: 12px; font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;" class="<?php echo $user['is_active'] ? 'bg-success text-white' : 'bg-danger text-white'; ?>">
                            <?php echo $user['is_active'] ? 'Active' : 'Inactive'; ?>
                        </span>
                    </td>
                    <td><?php echo !empty($user['created_at']) ? date('M j, Y', strtotime($user['created_at'])) : '-'; ?></td>
                    <td>
                        <div class="user-actions">
                            <button type="button" data-bs-toggle="modal" data-bs-target="#viewUserModal<?= $userId ?>" class="btn-action action-view">View</button>
                            <button type="button" data-bs-toggle="modal" data-bs-target="#editUserModal<?= $userId ?>" class="btn-action action-edit">Edit</button>
                            <form method="post" action="<?= site_url('admin/users/toggle-status/' . $userId) ?>" class="d-inline">
                                <button type="submit" class="btn-action <?= !empty($user['is_active']) ? 'action-warning' : 'action-success' ?>" <?= $isSelf ? 'disabled title="You cannot deactivate your own account"' : '' ?>>
                                    <?= !empty($user['is_active']) ? 'Deactivate' : 'Activate' ?>
                                </button>
                            </form>
                            <form method="post" action="<?= site_url('admin/users/delete/' . $userId) ?>" class="d-inline" onsubmit="return confirm('Delete this user account? This will also remove linked subscriptions and any linked uni tutor profile.');">
                                <button type="submit" class="btn-action action-delete" <?= $isSelf ? 'disabled title="You cannot delete your own account"' : '' ?>>Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="empty-users">
                            <div class="empty-users-icon"><i class="fas fa-search"></i></div>
                            <div class="empty-users-title">No users match those filters</div>
                            <div class="empty-users-text">Adjust the search or reset the filters to view all users.</div>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ((int) ($pager['total_pages'] ?? 1) > 1): ?>
        <div class="pagination-bar">
            <?php
                $currentPage = (int) ($pager['current_page'] ?? 1);
                $totalPages = (int) ($pager['total_pages'] ?? 1);
                $pageWindowStart = max(1, $currentPage - 2);
                $pageWindowEnd = min($totalPages, $currentPage + 2);
                $pageUrl = static function (int $page) use ($paginationQuery): string {
                    return site_url('admin/users') . '?' . http_build_query(array_merge($paginationQuery, ['page' => $page]));
                };
            ?>
            <a class="pager-btn <?= $currentPage <= 1 ? 'disabled' : '' ?>" href="<?= $currentPage <= 1 ? '#' : esc($pageUrl($currentPage - 1)) ?>">Previous</a>
            <?php if ($pageWindowStart > 1): ?>
                <a class="pager-btn" href="<?= esc($pageUrl(1)) ?>">1</a>
                <?php if ($pageWindowStart > 2): ?><span class="pager-gap">...</span><?php endif; ?>
            <?php endif; ?>
            <?php for ($pageNumber = $pageWindowStart; $pageNumber <= $pageWindowEnd; $pageNumber++): ?>
                <a class="pager-btn <?= $pageNumber === $currentPage ? 'active' : '' ?>" href="<?= esc($pageUrl($pageNumber)) ?>"><?= $pageNumber ?></a>
            <?php endfor; ?>
            <?php if ($pageWindowEnd < $totalPages): ?>
                <?php if ($pageWindowEnd < $totalPages - 1): ?><span class="pager-gap">...</span><?php endif; ?>
                <a class="pager-btn" href="<?= esc($pageUrl($totalPages)) ?>"><?= $totalPages ?></a>
            <?php endif; ?>
            <a class="pager-btn <?= $currentPage >= $totalPages ? 'disabled' : '' ?>" href="<?= $currentPage >= $totalPages ? '#' : esc($pageUrl($currentPage + 1)) ?>">Next</a>
        </div>
    <?php endif; ?>
</div>

<?php foreach ($all_users ?? [] as $modalUser): ?>
    <?php
        $modalUserId = (int) ($modalUser['id'] ?? 0);
        $modalIsUniversityTutor = ($modalUser['admin_portal_type'] ?? '') === 'university';
        $modalRoleLabel = $modalUser['admin_role_label'] ?? ucfirst($modalUser['role'] ?? 'user');
        $modalProfileUrl = '';
        if ($modalIsUniversityTutor && !empty($modalUser['linked_university_tutor_id'])) {
            $modalProfileUrl = site_url('admin/view-university-college-tutor/' . (int) $modalUser['linked_university_tutor_id']);
        } elseif (($modalUser['role'] ?? '') === 'trainer') {
            $modalProfileUrl = site_url('admin/trainers/view/' . $modalUserId);
        }
        $modalFullName = trim((string) (($modalUser['first_name'] ?? '') . ' ' . ($modalUser['last_name'] ?? '')));
        $modalFullName = $modalFullName !== '' ? $modalFullName : (string) ($modalUser['username'] ?? 'User');
        $modalIsSelf = $modalUserId === $currentAdminId;
    ?>
    <div class="modal fade" id="viewUserModal<?= $modalUserId ?>" tabindex="-1" aria-labelledby="viewUserModalLabel<?= $modalUserId ?>" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewUserModalLabel<?= $modalUserId ?>">User Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="detail-label">Name</div>
                            <div class="detail-value"><?= esc($modalFullName) ?></div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-label">Username</div>
                            <div class="detail-value"><?= esc($modalUser['username'] ?? '-') ?></div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-label">Email</div>
                            <div class="detail-value"><?= esc($modalUser['email'] ?? '-') ?></div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-label">Phone</div>
                            <div class="detail-value"><?= esc($modalUser['phone'] ?? '-') ?></div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-label">Role</div>
                            <div class="detail-value"><?= esc($modalRoleLabel) ?></div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-label">Status</div>
                            <div class="detail-value"><?= !empty($modalUser['is_active']) ? 'Active' : 'Inactive' ?></div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-label">Joined</div>
                            <div class="detail-value"><?= !empty($modalUser['created_at']) ? esc(date('M j, Y H:i', strtotime($modalUser['created_at']))) : '-' ?></div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-label">Portal</div>
                            <div class="detail-value"><?= esc($modalUser['admin_portal_type'] ?? 'main') ?></div>
                        </div>
                    </div>
                    <?php if ($modalProfileUrl !== ''): ?>
                        <div class="mt-4">
                            <a href="<?= esc($modalProfileUrl) ?>" class="btn btn-sm btn-primary">Open Profile Record</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editUserModal<?= $modalUserId ?>" tabindex="-1" aria-labelledby="editUserModalLabel<?= $modalUserId ?>" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="post" action="<?= site_url('admin/users/update/' . $modalUserId) ?>" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel<?= $modalUserId ?>">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">First Name</label>
                            <input type="text" class="form-control" name="first_name" value="<?= esc($modalUser['first_name'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name</label>
                            <input type="text" class="form-control" name="last_name" value="<?= esc($modalUser['last_name'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" value="<?= esc($modalUser['email'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" value="<?= esc($modalUser['username'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone" value="<?= esc($modalUser['phone'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role</label>
                            <select class="form-control" name="role" required <?= $modalIsSelf ? 'disabled' : '' ?>>
                                <?php foreach (['admin' => 'Admin', 'sub-admin' => 'Sub Admin', 'trainer' => 'Trainer', 'customer' => 'Customer'] as $roleValue => $roleText): ?>
                                    <option value="<?= esc($roleValue) ?>" <?= ($modalUser['role'] ?? '') === $roleValue ? 'selected' : '' ?>><?= esc($roleText) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if ($modalIsSelf): ?>
                                <input type="hidden" name="role" value="<?= esc($modalUser['role'] ?? 'admin') ?>">
                                <div class="form-text">You cannot change your own admin role from this page.</div>
                            <?php endif; ?>
                        </div>
                        <div class="col-12">
                            <label class="form-check">
                                <input type="checkbox" class="form-check-input" name="is_active" value="1" <?= !empty($modalUser['is_active']) ? 'checked' : '' ?> <?= $modalIsSelf ? 'disabled' : '' ?>>
                                <span class="form-check-label">Account is active</span>
                            </label>
                            <?php if ($modalIsSelf): ?>
                                <input type="hidden" name="is_active" value="1">
                                <div class="form-text">You cannot deactivate your own account.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
<?php endforeach; ?>

<!-- Recent Users Section -->
<div class="content-card">
    <div style="margin-bottom: 20px;">
        <h3 style="margin: 0; font-size: 20px; font-weight: 600; color: var(--text-dark);">Recent Users</h3>
        <p style="margin: 0; font-size: 14px; color: var(--text-light);">Latest user registrations</p>
    </div>

    <div style="overflow-x: auto;">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th style="background-color: var(--bg-dropdown); border: none; font-weight: 600; color: var(--text-dark); padding: 16px;">User</th>
                    <th style="background-color: var(--bg-dropdown); border: none; font-weight: 600; color: var(--text-dark); padding: 16px;">Role</th>
                    <th style="background-color: var(--bg-dropdown); border: none; font-weight: 600; color: var(--text-dark); padding: 16px;">Joined</th>
                    <th style="background-color: var(--bg-dropdown); border: none; font-weight: 600; color: var(--text-dark); padding: 16px;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recent_users ?? [] as $user): ?>
                <?php
                    $recentIsUniversityTutor = ($user['admin_portal_type'] ?? '') === 'university';
                    $recentRoleLabel = $user['admin_role_label'] ?? ucfirst($user['role'] ?? 'user');
                    $recentRoleBadgeClass = $recentIsUniversityTutor ? 'bg-info text-dark' : 'bg-primary text-white';
                ?>
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, var(--admin-primary), var(--admin-secondary)); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 12px;">
                                <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                            </div>
                            <div>
                                <h6 style="margin: 0; font-size: 13px; font-weight: 600; color: var(--text-dark);"><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></h6>
                                <div style="font-size: 11px; color: var(--text-light);"><?php echo $user['email']; ?></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span style="padding: 3px 8px; border-radius: 20px; font-size: 9px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;" class="<?= $recentRoleBadgeClass ?>">
                            <?php echo esc($recentRoleLabel); ?>
                        </span>
                    </td>
                    <td><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                    <td>
                        <span style="padding: 3px 8px; border-radius: 20px; font-size: 9px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;" class="<?php echo $user['is_active'] ? 'bg-success text-white' : 'bg-danger text-white'; ?>">
                            <?php echo $user['is_active'] ? 'Active' : 'Inactive'; ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
.table:hover tbody tr {
    background-color: rgba(0, 0, 0, 0.02);
}

.user-filter-bar {
    display: grid;
    grid-template-columns: minmax(220px, 1.4fr) repeat(4, minmax(130px, 0.7fr)) auto;
    gap: 12px;
    align-items: end;
    margin-bottom: 16px;
}

.filter-field {
    display: grid;
    gap: 6px;
}

.filter-field label {
    color: var(--text-light);
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.filter-field input,
.filter-field select {
    width: 100%;
    border: 1px solid rgba(15, 23, 42, 0.12);
    border-radius: 8px;
    padding: 9px 11px;
    color: var(--text-dark);
    background: #fff;
    font-size: 14px;
}

.filter-actions {
    display: flex;
    gap: 8px;
    align-items: center;
}

.btn-filter-primary,
.btn-filter-reset {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 38px;
    border-radius: 8px;
    padding: 8px 14px;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    border: 0;
    white-space: nowrap;
}

.btn-filter-primary {
    background: var(--admin-primary);
    color: #fff;
}

.btn-filter-reset {
    background: #f8fafc;
    color: var(--text-dark);
    border: 1px solid rgba(15, 23, 42, 0.1);
}

.table-meta,
.pagination-bar {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
}

.table-meta {
    margin-bottom: 12px;
    color: var(--text-light);
    font-size: 13px;
    font-weight: 600;
}

.pagination-bar {
    justify-content: flex-end;
    margin-top: 16px;
}

.pager-btn,
.pager-gap {
    min-width: 38px;
    min-height: 38px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    padding: 8px 12px;
    border: 1px solid rgba(15, 23, 42, 0.1);
    background: #fff;
    color: var(--text-dark);
    text-decoration: none;
    font-size: 13px;
    font-weight: 700;
}

.pager-btn.active {
    background: var(--admin-primary);
    border-color: var(--admin-primary);
    color: #fff;
}

.pager-btn.disabled {
    color: var(--text-light);
    pointer-events: none;
    opacity: 0.55;
}

.empty-users {
    display: grid;
    gap: 8px;
    justify-items: center;
}

.empty-users-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #f8fafc;
    color: var(--text-light);
}

.empty-users-title {
    font-weight: 800;
    color: var(--text-dark);
}

.empty-users-text {
    color: var(--text-light);
    font-size: 14px;
}

.user-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.btn-action {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    border: 0;
    line-height: 1.2;
    cursor: pointer;
}

.btn-action:hover {
    opacity: 0.8;
}

.btn-action:disabled {
    cursor: not-allowed;
    opacity: 0.45;
}

.action-view {
    background-color: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
}

.action-edit {
    background-color: rgba(245, 158, 11, 0.1);
    color: #f59e0b;
}

.action-warning {
    background-color: rgba(217, 119, 6, 0.12);
    color: #b45309;
}

.action-success {
    background-color: rgba(16, 185, 129, 0.12);
    color: #047857;
}

.action-delete {
    background-color: rgba(239, 68, 68, 0.1);
    color: #ef4444;
}

.detail-label {
    color: var(--text-light);
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.detail-value {
    margin-top: 4px;
    color: var(--text-dark);
    font-weight: 600;
    word-break: break-word;
}

@media (max-width: 1100px) {
    .user-filter-bar {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .filter-search,
    .filter-actions {
        grid-column: 1 / -1;
    }
}

@media (max-width: 640px) {
    .user-filter-bar {
        grid-template-columns: 1fr;
    }

    .filter-search,
    .filter-actions {
        grid-column: auto;
    }
}
</style>

<!-- Add Admin Modal -->
<div class="modal fade" id="addAdminModal" tabindex="-1" aria-labelledby="addAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #2C3E50, #34495E); color: white;">
                <h5 class="modal-title" id="addAdminModalLabel"><i class="fas fa-user-shield me-2"></i>Add New Admin</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addAdminForm">
                    <div class="mb-3">
                        <label for="admin_first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="admin_first_name" name="first_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="admin_last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="admin_last_name" name="last_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="admin_email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="admin_email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="admin_username" class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="admin_username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="admin_password" class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="admin_password" name="password" required minlength="8">
                        <small class="text-muted">Minimum 8 characters</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitAdmin()">Create Admin</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Trainer Modal -->
<div class="modal fade" id="addTrainerModal" tabindex="-1" aria-labelledby="addTrainerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #E74C3C, #C0392B); color: white;">
                <h5 class="modal-title" id="addTrainerModalLabel"><i class="fas fa-chalkboard-teacher me-2"></i>Add New Trainer</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addTrainerForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="trainer_first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="trainer_first_name" name="first_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="trainer_last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="trainer_last_name" name="last_name" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="trainer_email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="trainer_email" name="email" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="trainer_phone" class="form-label">Phone <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="trainer_phone" name="phone" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="trainer_username" class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="trainer_username" name="username" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="trainer_password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="trainer_password" name="password" required minlength="8">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="trainer_district" class="form-label">District <span class="text-danger">*</span></label>
                            <select class="form-control" id="trainer_district" name="district" required>
                                <option value="">Select District</option>
                                <option value="Blantyre">Blantyre</option>
                                <option value="Lilongwe">Lilongwe</option>
                                <option value="Mzuzu">Mzuzu</option>
                                <option value="Zomba">Zomba</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="trainer_gender" class="form-label">Gender <span class="text-danger">*</span></label>
                            <select class="form-control" id="trainer_gender" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="trainer_location" class="form-label">Location/Area <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="trainer_location" name="location" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitTrainer()">Create Trainer</button>
            </div>
        </div>
    </div>
</div>

<script>
// Real-time validation for Admin form
let adminEmailTimeout, adminUsernameTimeout;

document.addEventListener('DOMContentLoaded', function() {
    // Admin email validation
    const adminEmail = document.getElementById('admin_email');
    if (adminEmail) {
        adminEmail.addEventListener('input', function() {
            clearTimeout(adminEmailTimeout);
            const value = this.value.trim();

            if (value.length > 0) {
                adminEmailTimeout = setTimeout(() => {
                    checkAdminField('email', value, this);
                }, 500);
            }
        });
    }

    // Admin username validation
    const adminUsername = document.getElementById('admin_username');
    if (adminUsername) {
        adminUsername.addEventListener('input', function() {
            clearTimeout(adminUsernameTimeout);
            const value = this.value.trim();

            if (value.length >= 3) {
                adminUsernameTimeout = setTimeout(() => {
                    checkAdminField('username', value, this);
                }, 500);
            }
        });
    }

    // Trainer email validation
    const trainerEmail = document.getElementById('trainer_email');
    if (trainerEmail) {
        trainerEmail.addEventListener('input', function() {
            clearTimeout(window.trainerEmailTimeout);
            const value = this.value.trim();

            if (value.length > 0) {
                window.trainerEmailTimeout = setTimeout(() => {
                    checkTrainerField('email', value, this);
                }, 500);
            }
        });
    }

    // Trainer username validation
    const trainerUsername = document.getElementById('trainer_username');
    if (trainerUsername) {
        trainerUsername.addEventListener('input', function() {
            clearTimeout(window.trainerUsernameTimeout);
            const value = this.value.trim();

            if (value.length >= 3) {
                window.trainerUsernameTimeout = setTimeout(() => {
                    checkTrainerField('username', value, this);
                }, 500);
            }
        });
    }

    // Trainer phone validation
    const trainerPhone = document.getElementById('trainer_phone');
    if (trainerPhone) {
        trainerPhone.addEventListener('input', function() {
            clearTimeout(window.trainerPhoneTimeout);
            const value = this.value.trim();

            if (value.length >= 9) {
                window.trainerPhoneTimeout = setTimeout(() => {
                    checkTrainerField('phone', value, this);
                }, 500);
            }
        });
    }
});

function checkAdminField(field, value, inputElement) {
    const feedbackId = 'admin_' + field + '_feedback';
    let feedback = document.getElementById(feedbackId);

    if (!feedback) {
        feedback = document.createElement('div');
        feedback.id = feedbackId;
        feedback.className = 'invalid-feedback';
        feedback.style.display = 'block';
        inputElement.parentNode.appendChild(feedback);
    }

    fetch('<?php echo base_url('admin/check-field'); ?>', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({field: field, value: value})
    })
    .then(response => response.json())
    .then(data => {
        if (data.exists) {
            inputElement.classList.add('is-invalid');
            inputElement.classList.remove('is-valid');
            feedback.textContent = data.message;
        } else {
            inputElement.classList.remove('is-invalid');
            inputElement.classList.add('is-valid');
            feedback.textContent = '';
        }
    });
}

function checkTrainerField(field, value, inputElement) {
    const feedbackId = 'trainer_' + field + '_feedback';
    let feedback = document.getElementById(feedbackId);

    if (!feedback) {
        feedback = document.createElement('div');
        feedback.id = feedbackId;
        feedback.className = 'invalid-feedback';
        feedback.style.display = 'block';
        inputElement.parentNode.appendChild(feedback);
    }

    fetch('<?php echo base_url('admin/check-field'); ?>', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({field: field, value: value})
    })
    .then(response => response.json())
    .then(data => {
        if (data.exists) {
            inputElement.classList.add('is-invalid');
            inputElement.classList.remove('is-valid');
            feedback.textContent = data.message;
        } else {
            inputElement.classList.remove('is-invalid');
            inputElement.classList.add('is-valid');
            feedback.textContent = '';
        }
    });
}

function submitAdmin() {
    const form = document.getElementById('addAdminForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    // Check if there are any invalid fields
    if (form.querySelector('.is-invalid')) {
        alert('Please fix the validation errors before submitting');
        return;
    }

    const formData = new FormData(form);
    const btn = event.target;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating...';

    fetch('<?php echo base_url('admin/create-admin'); ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Admin created successfully!');
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to create admin'));
            btn.disabled = false;
            btn.innerHTML = 'Create Admin';
        }
    })
    .catch(error => {
        alert('Error: ' + error.message);
        btn.disabled = false;
        btn.innerHTML = 'Create Admin';
    });
}

function submitTrainer() {
    const form = document.getElementById('addTrainerForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    // Check if there are any invalid fields
    if (form.querySelector('.is-invalid')) {
        alert('Please fix the validation errors before submitting');
        return;
    }

    const formData = new FormData(form);
    const btn = event.target;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating...';

    fetch('<?php echo base_url('admin/create-trainer'); ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Trainer created successfully! They can now complete their profile.');
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to create trainer'));
            btn.disabled = false;
            btn.innerHTML = 'Create Trainer';
        }
    })
    .catch(error => {
        alert('Error: ' + error.message);
        btn.disabled = false;
        btn.innerHTML = 'Create Trainer';
    });
}
</script>

<?= $this->endSection() ?>
