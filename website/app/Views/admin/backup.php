<?= $this->extend('layouts/admin') ?>

<?php $active_page = 'backup'; ?>
<?php $title = $title ?? 'Database Backup - TutorConnect Malawi'; ?>

<?= $this->section('content') ?>
    <!-- Header -->
    <div class="header-bar">
        <div>
            <h1 class="page-title">Database Backup Management</h1>
            <p class="page-subtitle">Create and manage database backups for data security</p>
        </div>
        <button type="button" class="btn-admin" id="createBackupBtn">
            <i class="fas fa-database me-2"></i>Create Backup
        </button>
    </div>

    <!-- Database Info Card -->
    <div class="content-card">
        <div class="row">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6, #1e40af);">
                        <i class="fas fa-server"></i>
                    </div>
                    <div class="stat-number"><?php echo htmlspecialchars($db_info['database']); ?></div>
                    <div class="stat-label">Database Name</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                        <i class="fas fa-file-archive"></i>
                    </div>
                    <div class="stat-number"><?php echo number_format($db_info['total_backups']); ?></div>
                    <div class="stat-label">Total Backups</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                        <i class="fas fa-folder"></i>
                    </div>
                    <div class="stat-number"><?php echo htmlspecialchars(basename($db_info['backup_dir'])); ?></div>
                    <div class="stat-label">Backup Directory</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Backups Table -->
    <div class="content-card">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-history me-2"></i>Available Backups
                </h6>
            </div>
            <div class="card-body">
                <?php if (empty($backups)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-database fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No backups found</h5>
                        <p class="text-muted">Create your first backup to secure your data</p>
                        <button type="button" class="btn btn-primary" id="createFirstBackupBtn">
                            <i class="fas fa-plus me-2"></i>Create First Backup
                        </button>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="backupsTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Filename</th>
                                    <th>Size</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($backups as $backup): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars($backup['filename']); ?></strong>
                                            <br>
                                            <small class="text-muted"><?php echo htmlspecialchars($backup['filename']); ?></small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info"><?php echo htmlspecialchars($backup['size_human']); ?></span>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($backup['created']); ?>
                                            <br>
                                            <small class="text-muted"><?php echo date('l, F j, Y', strtotime($backup['created'])); ?></small>
                                        </td>
                                        <td>
                                            <a href="<?php echo site_url('admin/backup/download/' . urlencode($backup['filename'])); ?>"
                                               class="btn btn-sm btn-success me-1"
                                               title="Download">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            <button type="button"
                                                    class="btn btn-sm btn-danger delete-backup-btn"
                                                    data-filename="<?php echo htmlspecialchars($backup['filename']); ?>"
                                                    title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>



<style>
.btn-sm {
    padding: 8px 16px;
    font-size: 14px;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px 12px 0 0 !important;
    border: none;
}

.badge {
    font-size: 11px;
    padding: 4px 8px;
    border-radius: 6px;
    font-weight: 600;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.table tbody td {
    padding: 15px 12px;
    vertical-align: middle;
    border-bottom: 1px solid #f1f3f4;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
}

.btn-success {
    background: linear-gradient(135deg, #10b981, #059669);
    border: none;
}

.btn-success:hover {
    background: linear-gradient(135deg, #059669, #047857);
}

.btn-danger {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    border: none;
}

.btn-danger:hover {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
}

.text-primary {
    color: #667eea !important;
}

.text-success {
    color: #10b981 !important;
}

.text-warning {
    color: #f59e0b !important;
}

.text-danger {
    color: #ef4444 !important;
}

.text-info {
    color: #3b82f6 !important;
}
</style>

<!-- Create Backup Modal -->
<div class="modal fade" id="createBackupModal" tabindex="-1" aria-labelledby="createBackupModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createBackupModalLabel">
                    <i class="fas fa-database me-2"></i>Create Database Backup
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Backup Process:</strong> This will create a complete backup of your database including all tables, data, and structure.
                </div>

                <div id="backupProgress" style="display: none;">
                    <div class="mb-3">
                        <label class="form-label">Backup Progress</label>
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated"
                                 role="progressbar"
                                 style="width: 0%"
                                 id="backupProgressBar"></div>
                        </div>
                    </div>
                    <div id="backupStatus" class="text-muted">Initializing backup...</div>
                </div>

                <div id="backupResult" style="display: none;">
                    <div class="alert" id="backupResultAlert"></div>
                    <div id="backupDetails"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="closeBackupModalBtn">
                    <i class="fas fa-times me-1"></i>Close
                </button>
                <button type="button" class="btn btn-primary" id="startBackupBtn">
                    <i class="fas fa-play me-1"></i>Start Backup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Backup Modal -->
<div class="modal fade" id="deleteBackupModal" tabindex="-1" aria-labelledby="deleteBackupModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteBackupModalLabel">
                    <i class="fas fa-trash me-2"></i>Delete Backup
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> This action cannot be undone. The backup file will be permanently deleted.
                </div>
                <p>Are you sure you want to delete the backup file:</p>
                <p class="text-danger fw-bold" id="deleteFilename"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="fas fa-trash me-1"></i>Delete Backup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Backup functionality
document.addEventListener('DOMContentLoaded', function() {
    const createBackupBtn = document.getElementById('createBackupBtn');
    const createFirstBackupBtn = document.getElementById('createFirstBackupBtn');
    const startBackupBtn = document.getElementById('startBackupBtn');
    const closeBackupModalBtn = document.getElementById('closeBackupModalBtn');
    const deleteBackupModal = new bootstrap.Modal(document.getElementById('deleteBackupModal'));
    const createBackupModal = new bootstrap.Modal(document.getElementById('createBackupModal'));

    let backupFilenameToDelete = '';

    // Open create backup modal
    [createBackupBtn, createFirstBackupBtn].forEach(btn => {
        if (btn) {
            btn.addEventListener('click', function() {
                createBackupModal.show();
                resetBackupModal();
            });
        }
    });

    // Start backup process
    startBackupBtn.addEventListener('click', function() {
        startBackupProcess();
    });

    // Close backup modal
    closeBackupModalBtn.addEventListener('click', function() {
        createBackupModal.hide();
        setTimeout(() => {
            resetBackupModal();
        }, 500);
    });

    // Delete backup buttons
    document.querySelectorAll('.delete-backup-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            backupFilenameToDelete = this.getAttribute('data-filename');
            document.getElementById('deleteFilename').textContent = backupFilenameToDelete;
            deleteBackupModal.show();
        });
    });

    // Confirm delete backup
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        deleteBackupFile(backupFilenameToDelete);
    });

    function resetBackupModal() {
        document.getElementById('backupProgress').style.display = 'none';
        document.getElementById('backupResult').style.display = 'none';
        document.getElementById('backupProgressBar').style.width = '0%';
        document.getElementById('backupStatus').textContent = 'Initializing backup...';
        startBackupBtn.disabled = false;
        startBackupBtn.innerHTML = '<i class="fas fa-play me-1"></i>Start Backup';
    }

    function startBackupProcess() {
        // Show progress
        document.getElementById('backupProgress').style.display = 'block';
        document.getElementById('backupResult').style.display = 'none';
        startBackupBtn.disabled = true;
        startBackupBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Creating Backup...';

        // Update progress
        updateProgress(25, 'Connecting to database...');

        setTimeout(() => updateProgress(50, 'Extracting table structures...'), 500);
        setTimeout(() => updateProgress(75, 'Dumping data...'), 1000);

        // Make AJAX request
        setTimeout(() => {
            fetch('<?php echo site_url('admin/backup/create'); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '<?php echo csrf_hash(); ?>'
                }
            })
            .then(response => response.json())
            .then(data => {
                updateProgress(100, 'Backup completed!');

                setTimeout(() => {
                    document.getElementById('backupProgress').style.display = 'none';
                    document.getElementById('backupResult').style.display = 'block';

                    const resultAlert = document.getElementById('backupResultAlert');
                    const resultDetails = document.getElementById('backupDetails');

                    if (data.success) {
                        resultAlert.className = 'alert alert-success';
                        resultAlert.innerHTML = '<i class="fas fa-check-circle me-2"></i>' + data.message;

                        resultDetails.innerHTML = `
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <strong>Filename:</strong> ${data.filename}
                                </div>
                                <div class="col-md-6">
                                    <strong>Size:</strong> ${data.size}
                                </div>
                                <div class="col-md-12 mt-2">
                                    <strong>Created:</strong> ${data.created}
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="<?php echo site_url('admin/backup/download/'); ?>${encodeURIComponent(data.filename)}"
                                   class="btn btn-success btn-sm me-2">
                                    <i class="fas fa-download me-1"></i>Download Backup
                                </a>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="location.reload()">
                                    <i class="fas fa-refresh me-1"></i>Refresh List
                                </button>
                            </div>
                        `;

                        // Change close button to "View Backups"
                        closeBackupModalBtn.innerHTML = '<i class="fas fa-list me-1"></i>View Backups';
                        closeBackupModalBtn.onclick = function() {
                            createBackupModal.hide();
                            setTimeout(() => location.reload(), 500);
                        };
                    } else {
                        resultAlert.className = 'alert alert-danger';
                        resultAlert.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>' + data.message;
                        startBackupBtn.disabled = false;
                        startBackupBtn.innerHTML = '<i class="fas fa-play me-1"></i>Try Again';
                    }
                }, 500);
            })
            .catch(error => {
                updateProgress(100, 'Error occurred!');
                setTimeout(() => {
                    document.getElementById('backupProgress').style.display = 'none';
                    document.getElementById('backupResult').style.display = 'block';

                    const resultAlert = document.getElementById('backupResultAlert');
                    resultAlert.className = 'alert alert-danger';
                    resultAlert.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>Network error occurred. Please try again.';

                    startBackupBtn.disabled = false;
                    startBackupBtn.innerHTML = '<i class="fas fa-play me-1"></i>Try Again';
                }, 500);

                console.error('Backup error:', error);
            });
        }, 1500);
    }

    function updateProgress(percent, status) {
        document.getElementById('backupProgressBar').style.width = percent + '%';
        document.getElementById('backupStatus').textContent = status;
    }

    function deleteBackupFile(filename) {
        fetch('<?php echo site_url('admin/backup/delete/'); ?>' + encodeURIComponent(filename), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '<?php echo csrf_hash(); ?>'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                deleteBackupModal.hide();
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            showNotification('Failed to delete backup file', 'error');
            console.error('Delete error:', error);
        });
    }
});

// Initialize DataTables if available
$(document).ready(function() {
    if ($.fn.DataTable && document.getElementById('backupsTable')) {
        $('#backupsTable').DataTable({
            "order": [[2, 'desc']], // Sort by created date (newest first)
            "pageLength": 10,
            "responsive": true
        });
    }
});
</script>

<?= $this->endSection() ?>            }
