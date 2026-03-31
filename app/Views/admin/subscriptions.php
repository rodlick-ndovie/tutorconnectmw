<?= $this->extend('layouts/admin') ?>

<?php $active_page = 'subscriptions'; ?>
<?php $title = $title ?? 'Subscription Plans Management - TutorConnect Malawi'; ?>

<?= $this->section('content') ?>
    <!-- Header -->
    <div class="header-bar">
        <div>
            <h1 class="page-title">Subscription Plans Management</h1>
            <p class="page-subtitle">Manage subscription plans available to tutors</p>
        </div>
        <a href="<?= site_url('admin/subscriptions/add') ?>" class="btn-admin">
            <i class="fas fa-plus me-2"></i>Add New Plan
        </a>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6, #1e40af);">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-number"><?php echo number_format($total_plans); ?></div>
            <div class="stat-label">Total Plans</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-number"><?php echo number_format($active_plans); ?></div>
            <div class="stat-label">Active Plans</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                <i class="fas fa-pause-circle"></i>
            </div>
            <div class="stat-number"><?php echo number_format($total_plans - $active_plans); ?></div>
            <div class="stat-label">Inactive Plans</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6, #6d28d9);">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="stat-number">MK <?php echo number_format($stats['total_revenue_potential']); ?></div>
            <div class="stat-label">Monthly Revenue</div>
        </div>
    </div>

    <!-- Content Card -->
    <div class="content-card">

    <!-- Plans Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Subscription Plans</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="plansTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Price (MWK)</th>
                                    <th>Limits</th>
                                    <th>Features</th>
                                    <th>Status</th>
                                    <th>Sort Order</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($plans as $plan): ?>
                                    <tr>
                                        <td>
                                            <strong><?= esc($plan['name']) ?></strong>
                                        </td>
                                        <td>
                                            <?= esc($plan['description']) ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">MK<?= number_format($plan['price_monthly']) ?>/month</span>
                                        </td>
                                        <td>
                                            <small>
                                                <strong>Views:</strong> <?= $plan['max_profile_views'] == 0 ? 'Unlimited' : number_format($plan['max_profile_views']) ?><br>
                                                <strong>Clicks:</strong> <?= $plan['max_clicks'] == 0 ? 'Unlimited' : number_format($plan['max_clicks']) ?><br>
                                                <strong>Subjects:</strong> <?= $plan['max_subjects'] == 0 ? 'Unlimited' : $plan['max_subjects'] ?>
                                            </small>
                                        </td>
                                        <td>
                                            <small>
                                                <?php if ($plan['show_whatsapp']): ?>
                                                    <span class="badge bg-success me-1">WhatsApp</span>
                                                <?php endif; ?>
                                                <?php if ($plan['email_marketing_access']): ?>
                                                    <span class="badge bg-info me-1">Email</span>
                                                <?php endif; ?>
                                                <?php if ($plan['allow_video_upload']): ?>
                                                    <span class="badge bg-warning me-1">Video</span>
                                                <?php endif; ?>
                                                <?php if ($plan['allow_pdf_upload']): ?>
                                                    <span class="badge bg-secondary me-1">PDF</span>
                                                <?php endif; ?>
                                                <?php if ($plan['allow_video_solution']): ?>
                                                    <span class="badge bg-danger me-1">Video Sol</span>
                                                <?php endif; ?>
                                                <?php if ($plan['allow_announcements']): ?>
                                                    <span class="badge bg-primary">Announce</span>
                                                <?php endif; ?>
                                                <?php if (!$plan['show_whatsapp'] && !$plan['email_marketing_access'] && !$plan['allow_video_upload'] && !$plan['allow_pdf_upload'] && !$plan['allow_announcements']): ?>
                                                    <em class="text-muted">No features</em>
                                                <?php endif; ?>
                                            </small>
                                        </td>
                                        <td>
                                            <?php if ($plan['is_active']): ?>
                                                <span class="badge bg-success">Active</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?= $plan['sort_order'] ?>
                                        </td>
                                        <td>
                                            <a href="<?= site_url('admin/subscriptions/edit/' . $plan['id']) ?>" class="btn btn-sm btn-primary me-1" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="<?= site_url('admin/subscriptions/delete/' . $plan['id']) ?>" class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this plan?')">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
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
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 8px;
    font-weight: 600;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.btn-danger {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
    border: none;
    border-radius: 8px;
    font-weight: 600;
}

.btn-danger:hover {
    background: linear-gradient(135deg, #ff5252 0%, #e74c3c 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(255, 107, 107, 0.3);
}

.modal-content {
    border-radius: 12px;
    border: none;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
}

.modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px 12px 0 0;
    border: none;
}

.modal-header .btn-close {
    filter: invert(1);
}

.form-check-input:checked {
    background-color: #667eea;
    border-color: #667eea;
}

.form-check-input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter,
.dataTables_wrapper .dataTables_info,
.dataTables_wrapper .dataTables_paginate {
    color: #6c757d;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    color: #6c757d !important;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    margin: 0 2px;
    padding: 6px 12px;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    color: #667eea !important;
    border-color: #667eea;
    background: rgba(102, 126, 234, 0.1);
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    color: white !important;
    border-color: #667eea;
    background: #667eea;
}

.stats-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 4px 20px rgba(102, 126, 234, 0.2);
}

.stats-card .stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    margin-bottom: 15px;
}

.stats-card .stat-number {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 5px;
}

.stats-card .stat-label {
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    opacity: 0.9;
}

.table-responsive {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.table {
    margin-bottom: 0;
}

.table thead th {
    background: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    color: #495057;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 15px 12px;
}

.table tbody td {
    padding: 15px 12px;
    vertical-align: middle;
    border-bottom: 1px solid #f1f3f4;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
}

.badge {
    font-size: 11px;
    padding: 4px 8px;
    border-radius: 6px;
    font-weight: 600;
}

.btn {
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.card-body {
    padding: 25px;
}

.modal-body {
    padding: 25px;
}

.form-label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}

.form-control {
    border-radius: 8px;
    border: 2px solid #e5e7eb;
    padding: 10px 15px;
    font-size: 14px;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-check-label {
    font-weight: 500;
    color: #4b5563;
    margin-left: 8px;
}

.alert {
    border-radius: 8px;
    border: none;
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

.bg-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

.bg-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
}

.bg-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
}

.bg-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
}

.bg-info {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important;
}
</style>

<!-- Create Plan Modal -->
<div class="modal fade" id="createPlanModal" tabindex="-1" aria-labelledby="createPlanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createPlanModalLabel">Create New Subscription Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" id="planForm" action="<?= site_url('admin/subscriptions/create') ?>">
                <?= csrf_field() ?>
                <input type="hidden" id="plan_id" name="plan_id" value="">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Plan Name *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price_monthly" class="form-label">Monthly Price (MWK) *</label>
                                <input type="number" class="form-control" id="price_monthly" name="price_monthly" min="0" step="0.01" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="max_profile_views" class="form-label">Max Profile Views *</label>
                                <input type="number" class="form-control" id="max_profile_views" name="max_profile_views" min="0" required>
                                <div class="form-text">Enter 0 for unlimited</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="max_clicks" class="form-label">Max Contact Clicks *</label>
                                <input type="number" class="form-control" id="max_clicks" name="max_clicks" min="0" required>
                                <div class="form-text">Enter 0 for unlimited</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="max_subjects" class="form-label">Max Subjects *</label>
                                <input type="number" class="form-control" id="max_subjects" name="max_subjects" min="0" required>
                                <div class="form-text">Enter 0 for unlimited</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="sort_order" class="form-label">Sort Order</label>
                                <input type="number" class="form-control" id="sort_order" name="sort_order" min="0" value="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="district_spotlight_days" class="form-label">District Spotlight Days</label>
                                <input type="number" class="form-control" id="district_spotlight_days" name="district_spotlight_days" min="0" value="0">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Badge Level</label>
                                <select class="form-control" name="badge_level" id="badge_level">
                                    <option value="none">None</option>
                                    <option value="trial">Trial</option>
                                    <option value="verified">Verified</option>
                                    <option value="featured">Featured</option>
                                    <option value="top_rated">Top Rated</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Search Ranking</label>
                                <select class="form-control" name="search_ranking" id="search_ranking">
                                    <option value="low">Low</option>
                                    <option value="normal">Normal</option>
                                    <option value="priority">Priority</option>
                                    <option value="top">Top</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Features & Permissions</label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="show_whatsapp" name="show_whatsapp" value="1">
                                    <label class="form-check-label" for="show_whatsapp">
                                        Show WhatsApp contact
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="email_marketing_access" name="email_marketing_access" value="1">
                                    <label class="form-check-label" for="email_marketing_access">
                                        Email marketing access
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="allow_video_upload" name="allow_video_upload" value="1">
                                    <label class="form-check-label" for="allow_video_upload">
                                        Allow video uploads
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="allow_pdf_upload" name="allow_pdf_upload" value="1">
                                    <label class="form-check-label" for="allow_pdf_upload">
                                        Allow PDF uploads
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="allow_video_solution" name="allow_video_solution" value="1">
                                    <label class="form-check-label" for="allow_video_solution">
                                        Allow video solutions
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="allow_announcements" name="allow_announcements" value="1">
                                    <label class="form-check-label" for="allow_announcements">
                                        Allow announcements
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                            <label class="form-check-label" for="is_active">
                                Active - Make this plan available to tutors
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Plan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Plan Modal (Populated via JavaScript) -->
<div class="modal fade" id="editPlanModal" tabindex="-1" aria-labelledby="editPlanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPlanModalLabel">Edit Subscription Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" id="editPlanForm">
                <?= csrf_field() ?>
                <div class="modal-body" id="editPlanModalBody">
                    <!-- Populated via JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Plan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editPlan(planId) {
    // Fetch plan data and populate the modal
    fetch('<?= site_url('admin/subscriptions/get/') ?>' + planId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const plan = data.plan;

                // Populate modal fields
                document.getElementById('plan_id').value = plan.id;
                document.getElementById('name').value = plan.name;
                document.getElementById('price_monthly').value = plan.price_monthly;
                document.getElementById('description').value = plan.description || '';
                document.getElementById('max_profile_views').value = plan.max_profile_views;
                document.getElementById('max_clicks').value = plan.max_clicks;
                document.getElementById('max_subjects').value = plan.max_subjects;
                document.getElementById('sort_order').value = plan.sort_order;
                document.getElementById('district_spotlight_days').value = plan.district_spotlight_days || 0;

                // Set badge level
                const badgeSelect = document.getElementById('badge_level');
                if (badgeSelect) {
                    badgeSelect.value = plan.badge_level || 'none';
                }

                // Set search ranking
                const rankingSelect = document.getElementById('search_ranking');
                if (rankingSelect) {
                    rankingSelect.value = plan.search_ranking || 'low';
                }

                // Set feature checkboxes
                document.getElementById('show_whatsapp').checked = plan.show_whatsapp == 1;
                document.getElementById('email_marketing_access').checked = plan.email_marketing_access == 1;
                document.getElementById('allow_video_upload').checked = plan.allow_video_upload == 1;
                document.getElementById('allow_pdf_upload').checked = plan.allow_pdf_upload == 1;
                document.getElementById('allow_video_solution').checked = plan.allow_video_solution == 1;
                document.getElementById('allow_announcements').checked = plan.allow_announcements == 1;

                // Set active status
                document.getElementById('is_active').checked = plan.is_active == 1;

                // Change modal title and form action
                document.getElementById('createPlanModalLabel').textContent = 'Edit Subscription Plan';
                const form = document.getElementById('createPlanModal').querySelector('form');
                form.action = '<?= site_url('admin/subscriptions/update/') ?>' + planId;
                form.method = 'POST';

                // Change submit button text
                document.querySelector('#createPlanModal .btn-primary').textContent = 'Update Plan';

                // Remove any existing method field
                const existingMethodField = form.querySelector('input[name="_method"]');
                if (existingMethodField) {
                    existingMethodField.remove();
                }

                console.log('Form action set to:', form.action);

                // Show modal
                const modal = new bootstrap.Modal(document.getElementById('createPlanModal'));
                modal.show();
            } else {
                console.error('API returned error:', data.message);
                showNotification('Failed to load plan data: ' + (data.message || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            console.error('Error loading plan data:', error);
            showNotification('Error loading plan data. Please try again.', 'error');
        });
}

// Reset modal form when closed
$('#createPlanModal').on('hidden.bs.modal', function () {
    console.log('Resetting modal form');

    // Reset form action and title
    document.getElementById('createPlanModalLabel').textContent = 'Create New Subscription Plan';
    const form = document.getElementById('createPlanModal').querySelector('form');
    form.action = '<?= site_url('admin/subscriptions/create') ?>';
    form.method = 'POST';

    // Reset submit button text
    document.querySelector('#createPlanModal .btn-primary').textContent = 'Create Plan';

    // Clear form fields
    form.reset();

    // Reset hidden plan_id
    document.getElementById('plan_id').value = '';

    // Remove any extra method fields
    const methodField = form.querySelector('input[name="_method"]');
    if (methodField) {
        methodField.remove();
    }

    console.log('Modal reset complete');
});

// Initialize DataTables
$(document).ready(function() {
    if ($.fn.DataTable) {
        $('#plansTable').DataTable({
            "order": [[6, 'asc']], // Sort by sort order
            "pageLength": 10
        });
    }
});
</script>

<?= $this->endSection() ?>
