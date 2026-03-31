<?= $this->extend('layouts/admin') ?>

<?php $active_page = 'users'; ?>
<?php $title = $title ?? 'User Management - TutorConnect Malawi'; ?>

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
        $total = array_sum($role_data);
        foreach ($role_data as $role => $count) {
            $percentage = $total > 0 ? round(($count / $total) * 100, 1) : 0;
            ?>
            <div style="text-align: center;">
                <div style="font-size: 24px; font-weight: 700; color: var(--admin-primary); margin-bottom: 4px;"><?php echo $percentage; ?>%</div>
                <div style="font-size: 12px; color: var(--text-light); font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;"><?php echo ucfirst(rtrim($role, 's')); ?>s</div>
            </div>
            <?php
        }
        ?>
    </div>
</div>

<!-- Users Table -->
<div class="content-card">
    <div style="overflow-x: auto;">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th style="background-color: var(--bg-dropdown); border: none; font-weight: 600; color: var(--text-dark); padding: 16px;">User</th>
                    <th style="background-color: var(--bg-dropdown); border: none; font-weight: 600; color: var(--text-dark); padding: 16px;">Role</th>
                    <th style="background-color: var(--bg-dropdown); border: none; font-weight: 600; color: var(--text-dark); padding: 16px;">Status</th>
                    <th style="background-color: var(--bg-dropdown); border: none; font-weight: 600; color: var(--text-dark); padding: 16px;">Joined</th>
                    <th style="background-color: var(--bg-dropdown); border: none; font-weight: 600; color: var(--text-dark); padding: 16px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($all_users ?? [] as $user): ?>
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, var(--admin-primary), var(--admin-secondary)); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                                <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                            </div>
                            <div>
                                <h6 style="margin: 0; font-size: 14px; font-weight: 600; color: var(--text-dark);"><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></h6>
                                <div style="font-size: 12px; color: var(--text-light);"><?php echo $user['email']; ?></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span style="padding: 4px 12px; border-radius: 12px; font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;" class="bg-primary text-white">
                            <?php echo ucfirst($user['role']); ?>
                        </span>
                    </td>
                    <td>
                        <span style="padding: 4px 12px; border-radius: 12px; font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;" class="<?php echo $user['is_active'] ? 'bg-success text-white' : 'bg-danger text-white'; ?>">
                            <?php echo $user['is_active'] ? 'Active' : 'Inactive'; ?>
                        </span>
                    </td>
                    <td><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                    <td>
                        <div style="display: flex; gap: 4px;">
                            <a href="#" style="padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; text-decoration: none; background-color: rgba(59, 130, 246, 0.1); color: #3b82f6; border: none;" class="btn-action">View</a>
                            <a href="#" style="padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; text-decoration: none; background-color: rgba(245, 158, 11, 0.1); color: #f59e0b; border: none;" class="btn-action">Edit</a>
                            <a href="#" style="padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; text-decoration: none; background-color: rgba(239, 68, 68, 0.1); color: #ef4444; border: none;" class="btn-action">Delete</a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

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
                        <span style="padding: 3px 8px; border-radius: 20px; font-size: 9px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;" class="bg-primary text-white">
                            <?php echo ucfirst($user['role']); ?>
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

.btn-action:hover {
    opacity: 0.8;
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
