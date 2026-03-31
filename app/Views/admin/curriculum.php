<?= $this->extend('layouts/admin') ?>

<?php $active_page = 'curriculum'; ?>
<?php $title = $title ?? 'Curriculum Management - TutorConnect Malawi'; ?>

<?= $this->section('content') ?>
    <!-- Header -->
    <div class="header-bar">
        <div>
            <h1 class="page-title">Curriculum Management</h1>
            <p class="page-subtitle">Manage curriculum subjects and levels for tutoring</p>
        </div>
        <a href="<?= site_url('admin/curriculum/add') ?>" class="btn-admin">
            <i class="fas fa-plus me-2"></i>Add Subject
        </a>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6, #1e40af);">
                <i class="fas fa-book"></i>
            </div>
            <div class="stat-number"><?php echo number_format($stats['total_subjects']); ?></div>
            <div class="stat-label">Total Subjects</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-number"><?php echo number_format($stats['active_subjects']); ?></div>
            <div class="stat-label">Active Subjects</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="stat-number"><?php echo number_format($stats['curricula_count']); ?></div>
            <div class="stat-label">Curricula</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                <i class="fas fa-pause-circle"></i>
            </div>
            <div class="stat-number"><?php echo number_format($stats['total_subjects'] - $stats['active_subjects']); ?></div>
            <div class="stat-label">Inactive Subjects</div>
        </div>
    </div>

    <!-- Content Card -->
    <div class="content-card">
        <!-- Filters Section -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; padding: 20px; background: rgba(0, 0, 0, 0.02); border-radius: 12px;">
            <div style="display: flex; gap: 16px; align-items: center;">
                <form method="GET" action="<?= site_url('admin/curriculum') ?>" style="display: flex; gap: 12px; align-items: center;">
                    <select name="curriculum" class="form-control" style="width: 150px;">
                        <option value="">All Curricula</option>
                        <?php foreach ($curricula as $curriculum): ?>
                            <option value="<?= $curriculum ?>" <?= ($current_filters['curriculum'] ?? '') === $curriculum ? 'selected' : '' ?>>
                                <?= $curriculum ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <select name="subject_category" class="form-control" style="width: 150px;">
                        <option value="">All Categories</option>
                        <?php foreach ($subject_categories as $category): ?>
                            <option value="<?= $category ?>" <?= ($current_filters['subject_category'] ?? '') === $category ? 'selected' : '' ?>>
                                <?= $category ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <select name="level_name" class="form-control" style="width: 150px;">
                        <option value="">All Levels</option>
                        <?php foreach ($level_names as $level): ?>
                            <option value="<?= $level['level_name'] ?>" <?= ($current_filters['level_name'] ?? '') === $level['level_name'] ? 'selected' : '' ?>>
                                <?= $level['level_name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn btn-outline-primary">Filter</button>
                    <a href="<?= site_url('admin/curriculum') ?>" class="btn btn-outline-secondary">Clear</a>
                </form>
            </div>
            <div style="display: flex; gap: 12px;">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bulkActionModal">
                    <i class="fas fa-tasks me-2"></i>Bulk Actions
                </button>
                <div class="dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-download me-2"></i>Export
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="exportData('csv')"><i class="fas fa-file-csv me-2"></i>CSV</a></li>
                        <li><a class="dropdown-item" href="#" onclick="exportData('excel')"><i class="fas fa-file-excel me-2"></i>Excel</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Curriculum Subjects Table -->
        <div class="table-responsive">
            <table class="table table-bordered" id="curriculumTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th width="3%">
                            <input type="checkbox" id="selectAll" class="form-check-input">
                        </th>
                        <th>Curriculum</th>
                        <th>Level</th>
                        <th>Subject</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th width="15%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($curriculum_subjects as $subject): ?>
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input subject-checkbox" value="<?= $subject['id'] ?>">
                            </td>
                            <td>
                                <span class="badge bg-primary"><?= esc($subject['curriculum']) ?></span>
                            </td>
                            <td>
                                <strong><?= esc($subject['level_name']) ?></strong>
                            </td>
                            <td>
                                <?= esc($subject['subject_name']) ?>
                            </td>
                            <td>
                                <span class="badge bg-secondary"><?= esc($subject['subject_category'] ?: 'General') ?></span>
                            </td>
                            <td>
                                <?php if ($subject['is_active']): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="<?= site_url('admin/curriculum/edit/' . $subject['id']) ?>" class="btn btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="<?= site_url('admin/curriculum/toggle-status/' . $subject['id']) ?>" class="d-inline">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-outline-warning" title="<?= $subject['is_active'] ? 'Deactivate' : 'Activate' ?>">
                                            <i class="fas fa-<?= $subject['is_active'] ? 'pause' : 'play' ?>"></i>
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-outline-danger" onclick="deleteSubject(<?= $subject['id'] ?>, '<?= esc($subject['subject_name']) ?>')" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

<script>
function deleteSubject(subjectId, subjectName) {
    document.getElementById('delete-subject-name').textContent = subjectName;
    document.getElementById('delete-form').action = '<?= site_url('admin/curriculum/delete/') ?>' + subjectId;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

function exportData(format) {
    // Simple export functionality
    const table = document.getElementById('curriculumTable');
    let csv = [];

    // Get headers
    const headers = [];
    for (let i = 1; i < table.rows[0].cells.length - 1; i++) { // Skip checkbox and actions columns
        headers.push(table.rows[0].cells[i].textContent.trim());
    }
    csv.push(headers.join(','));

    // Get data rows
    for (let i = 1; i < table.rows.length; i++) {
        const row = [];
        for (let j = 1; j < table.rows[i].cells.length - 1; j++) { // Skip checkbox and actions columns
            let cellText = table.rows[i].cells[j].textContent.trim();
            // Remove HTML tags and badges
            cellText = cellText.replace(/<[^>]*>/g, '').trim();
            row.push('"' + cellText.replace(/"/g, '""') + '"');
        }
        csv.push(row.join(','));
    }

    const csvContent = csv.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');

    if (link.download !== undefined) {
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', 'curriculum_subjects.' + format);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
}

// Handle bulk actions
$(document).ready(function() {
    // Initialize DataTables
    if ($.fn.DataTable) {
        $('#curriculumTable').DataTable({
            "order": [[1, 'asc'], [2, 'asc'], [3, 'asc']],
            "pageLength": 25,
            "columnDefs": [
                { "orderable": false, "targets": [0, 6] } // Disable sorting for checkbox and actions columns
            ]
        });
    }

    // Handle select all checkbox
    $('#selectAll').on('change', function() {
        $('.subject-checkbox').prop('checked', $(this).prop('checked'));
        updateSelectedCount();
    });

    // Handle individual checkboxes
    $(document).on('change', '.subject-checkbox', function() {
        updateSelectedCount();
    });

    function updateSelectedCount() {
        const selectedCount = $('.subject-checkbox:checked').length;
        $('#selected-count').text(selectedCount + ' subjects selected');
        $('#bulk-submit-btn').prop('disabled', selectedCount === 0);

        // Update the hidden inputs for bulk form
        const bulkForm = $('#bulkActionModal form');
        bulkForm.find('input[name="subject_ids[]"]').remove(); // Remove existing
        $('.subject-checkbox:checked').each(function() {
            bulkForm.append('<input type="hidden" name="subject_ids[]" value="' + $(this).val() + '">');
        });
    }

    // Handle bulk action modal
    $('#bulkActionModal').on('show.bs.modal', function() {
        updateSelectedCount();
    });
});
</script>

<!-- Bulk Action Modal -->
<div class="modal fade" id="bulkActionModal" tabindex="-1" aria-labelledby="bulkActionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkActionModalLabel">Bulk Actions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="<?= site_url('admin/curriculum/bulk-action') ?>">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="bulk_action" class="form-label">Select Action</label>
                        <select name="action" id="bulk_action" class="form-select" required>
                            <option value="">Choose an action...</option>
                            <option value="activate">Activate Selected</option>
                            <option value="deactivate">Deactivate Selected</option>
                            <option value="delete">Delete Selected</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <div id="selected-count">0 subjects selected</div>
                    </div>
                    <div class="alert alert-warning">
                        <strong>Warning:</strong> This action cannot be undone. Please review your selection carefully.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="bulk-submit-btn" disabled>Apply Action</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete the subject "<span id="delete-subject-name"></span>"?
                <div class="alert alert-danger mt-3">
                    <strong>Warning:</strong> This action cannot be undone and may affect existing tutor profiles and bookings.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" id="delete-form" class="d-inline">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger">Delete Subject</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
