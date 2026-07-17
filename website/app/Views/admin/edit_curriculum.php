<?= $this->extend('layouts/admin') ?>

<?php $active_page = 'curriculum'; ?>
<?php $title = $title ?? 'Edit Curriculum Subject - TutorConnect Malawi'; ?>

<?= $this->section('content') ?>
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">Edit Curriculum Subject</h1>
            <p class="text-muted">Update curriculum subject information</p>
        </div>
    </div>

    <!-- Edit Subject Form -->
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Subject Information</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= site_url('admin/curriculum/update/' . $subject['id']) ?>">
                        <?= csrf_field() ?>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="curriculum" class="form-label">Curriculum *</label>
                                <select name="curriculum" id="curriculum" class="form-select" required>
                                    <option value="">Select Curriculum</option>
                                    <?php foreach ($curricula as $curriculum): ?>
                                        <option value="<?= $curriculum ?>" <?= $subject['curriculum'] === $curriculum ? 'selected' : '' ?>>
                                            <?= $curriculum ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text">Choose the curriculum this subject belongs to</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="subject_category" class="form-label">Subject Category</label>
                                <select name="subject_category" id="subject_category" class="form-select">
                                    <option value="">Select Category (Optional)</option>
                                    <?php foreach ($subject_categories as $category): ?>
                                        <option value="<?= $category ?>" <?= $subject['subject_category'] === $category ? 'selected' : '' ?>>
                                            <?= $category ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text">Categorize the subject for better organization</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="level_name" class="form-label">Education Level *</label>
                            <input type="text" class="form-control" id="level_name" name="level_name"
                                   value="<?= esc($subject['level_name']) ?>"
                                   placeholder="e.g., Primary: Standards 1–8, Key Stage 4 (Years 10–11)" required>
                            <div class="form-text">Specify the education level or grade this subject is taught at</div>
                        </div>

                        <div class="mb-3">
                            <label for="subject_name" class="form-label">Subject Name *</label>
                            <input type="text" class="form-control" id="subject_name" name="subject_name"
                                   value="<?= esc($subject['subject_name']) ?>"
                                   placeholder="e.g., Mathematics, English Language, Biology" required>
                            <div class="form-text">The name of the subject</div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                       <?= $subject['is_active'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="is_active">
                                    Active - Make this subject available for tutors to select
                                </label>
                            </div>
                            <div class="form-text">Inactive subjects won't appear in tutor registration forms</div>
                        </div>

                        <!-- Preview Section -->
                        <div class="mb-4 p-3 bg-light rounded">
                            <h6 class="text-primary mb-2">Preview</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted">Curriculum:</small>
                                    <div id="preview-curriculum" class="badge bg-primary"><?= esc($subject['curriculum']) ?></div>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">Level:</small>
                                    <div id="preview-level"><strong><?= esc($subject['level_name']) ?></strong></div>
                                </div>
                            </div>
                            <div class="mt-2">
                                <small class="text-muted">Subject:</small>
                                <div id="preview-subject"><?= esc($subject['subject_name']) ?></div>
                            </div>
                            <div class="mt-2">
                                <small class="text-muted">Category:</small>
                                <span id="preview-category" class="badge bg-secondary">
                                    <?= esc($subject['subject_category'] ?: 'General') ?>
                                </span>
                            </div>
                        </div>

                        <!-- Subject Statistics -->
                        <div class="mb-4 p-3 bg-info bg-opacity-10 border border-info rounded">
                            <h6 class="text-info mb-2">
                                <i class="fas fa-chart-bar me-2"></i>Subject Statistics
                            </h6>
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="h4 mb-0 text-info">0</div>
                                    <small class="text-muted">Active Tutors</small>
                                </div>
                                <div class="col-4">
                                    <div class="h4 mb-0 text-info">0</div>
                                    <small class="text-muted">Bookings</small>
                                </div>
                                <div class="col-4">
                                    <div class="h4 mb-0 text-info">
                                        <span class="badge bg-<?= $subject['is_active'] ? 'success' : 'danger' ?>">
                                            <?= $subject['is_active'] ? 'Active' : 'Inactive' ?>
                                        </span>
                                    </small>
                                </div>
                            </div>
                            <div class="mt-2">
                                <small class="text-muted">
                                    Created: <?= date('M j, Y', strtotime($subject['created_at'])) ?>
                                    <?php if ($subject['updated_at'] && $subject['updated_at'] !== $subject['created_at']): ?>
                                        | Last Updated: <?= date('M j, Y', strtotime($subject['updated_at'])) ?>
                                    <?php endif; ?>
                                </small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?= site_url('admin/curriculum') ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Curriculum
                            </a>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update Subject
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Usage Information -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>Important Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-info-circle me-2"></i>Considerations when editing subjects:</h6>
                        <ul class="mb-0">
                            <li><strong>Curriculum changes</strong> may affect existing tutor profiles and search results</li>
                            <li><strong>Level name changes</strong> will update how this subject appears in groupings</li>
                            <li><strong>Subject name changes</strong> will affect how tutors and students see this subject</li>
                            <li><strong>Deactivating subjects</strong> will hide them from new tutor registrations but won't affect existing tutors</li>
                            <li><strong>Category changes</strong> will update how subjects are filtered and organized</li>
                        </ul>
                    </div>

                    <?php if (!$subject['is_active']): ?>
                        <div class="alert alert-info">
                            <strong>This subject is currently inactive.</strong> It won't appear in tutor registration forms or search results.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
// Update preview in real-time
$(document).ready(function() {
    function updatePreview() {
        const curriculum = $('#curriculum').val() || '-';
        const level = $('#level_name').val() || '-';
        const subject = $('#subject_name').val() || '-';
        const category = $('#subject_category').val() || 'General';

        $('#preview-curriculum').text(curriculum);
        $('#preview-level').html('<strong>' + level + '</strong>');
        $('#preview-subject').text(subject);
        $('#preview-category').text(category);

        // Update badge class based on category
        const categoryBadge = $('#preview-category');
        categoryBadge.removeClass('bg-primary bg-secondary bg-success bg-info bg-warning bg-danger');
        if (category === 'Mathematics') categoryBadge.addClass('bg-primary');
        else if (category === 'Science') categoryBadge.addClass('bg-success');
        else if (category === 'Language') categoryBadge.addClass('bg-info');
        else if (category === 'Arts') categoryBadge.addClass('bg-warning');
        else if (category === 'Technology') categoryBadge.addClass('bg-danger');
        else categoryBadge.addClass('bg-secondary');
    }

    // Bind events to form inputs
    $('#curriculum, #level_name, #subject_name, #subject_category').on('input change', updatePreview);

    // Initialize preview
    updatePreview();
});
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>
