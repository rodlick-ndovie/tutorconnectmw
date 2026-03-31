<?= $this->extend('layouts/admin') ?>

<?php $active_page = 'library'; ?>
<?php $title = $title ?? 'Add Resource'; ?>

<?= $this->section('content') ?>

<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 text-gray-800"><i class="fas fa-plus mr-2"></i>Add New Resource</h1>
        </div>
        <div class="col-md-4 text-right">
            <a href="<?= site_url('admin/library') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Back to Library
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body">
                    <form action="<?= site_url('admin/library/add') ?>" method="POST" enctype="multipart/form-data" id="resourceForm">
                        <?= csrf_field() ?>

                        <!-- Resource Type -->
                        <div class="form-group">
                            <label for="resource_type">Resource Type *</label>
                            <select name="resource_type" id="resource_type" class="form-control" required onchange="toggleResourceFields()">
                                <option value="">Select Type</option>
                                <option value="video" <?= old('resource_type') === 'video' ? 'selected' : '' ?>>Video Solution</option>
                                <option value="past_paper" <?= old('resource_type') === 'past_paper' ? 'selected' : '' ?>>Past Paper</option>
                            </select>
                        </div>

                        <!-- Title -->
                        <div class="form-group">
                            <label for="title">Title *</label>
                            <input type="text" name="title" id="title" class="form-control" value="<?= old('title') ?>" required maxlength="255">
                        </div>

                        <!-- Subject -->
                        <div class="form-group">
                            <label for="subject">Subject *</label>
                            <input type="text" name="subject" id="subject" class="form-control" value="<?= old('subject') ?>" required maxlength="100">
                        </div>

                        <!-- Curriculum -->
                        <div class="form-group">
                            <label for="curriculum">Curriculum *</label>
                            <select name="curriculum" id="curriculum" class="form-control" required>
                                <option value="">Select Curriculum</option>
                                <option value="MANEB" <?= old('curriculum') === 'MANEB' ? 'selected' : '' ?>>MANEB</option>
                                <option value="Cambridge" <?= old('curriculum') === 'Cambridge' ? 'selected' : '' ?>>Cambridge</option>
                                <option value="IB" <?= old('curriculum') === 'IB' ? 'selected' : '' ?>>IB</option>
                            </select>
                        </div>

                        <!-- Grade Level -->
                        <div class="form-group">
                            <label for="grade_level">Grade Level *</label>
                            <select name="grade_level" id="grade_level" class="form-control" required>
                                <option value="">Select Grade</option>
                                <option value="Standard 8" <?= old('grade_level') === 'Standard 8' ? 'selected' : '' ?>>Standard 8</option>
                                <option value="Form 1" <?= old('grade_level') === 'Form 1' ? 'selected' : '' ?>>Form 1</option>
                                <option value="Form 2" <?= old('grade_level') === 'Form 2' ? 'selected' : '' ?>>Form 2</option>
                                <option value="Form 3" <?= old('grade_level') === 'Form 3' ? 'selected' : '' ?>>Form 3</option>
                                <option value="Form 4" <?= old('grade_level') === 'Form 4' ? 'selected' : '' ?>>Form 4</option>
                            </select>
                        </div>

                        <!-- Year (for past papers) -->
                        <div class="form-group" id="year_field">
                            <label for="year">Year</label>
                            <input type="number" name="year" id="year" class="form-control" value="<?= old('year') ?>" min="2000" max="2030">
                        </div>

                        <!-- Paper Type (for past papers) -->
                        <div class="form-group" id="paper_type_field">
                            <label for="paper_type">Paper Type</label>
                            <input type="text" name="paper_type" id="paper_type" class="form-control" value="<?= old('paper_type') ?>" placeholder="e.g., Paper 1, Paper 2, Mock">
                        </div>

                        <!-- Video URL (for videos) -->
                        <div class="form-group" id="video_url_field" style="display: none;">
                            <label for="video_url">Video URL *</label>
                            <input type="url" name="video_url" id="video_url" class="form-control" value="<?= old('video_url') ?>" placeholder="https://www.youtube.com/watch?v=...">
                            <small class="form-text text-muted">Enter YouTube or video URL</small>
                        </div>

                        <!-- Video Thumbnail (for videos) -->
                        <div class="form-group" id="video_thumbnail_field" style="display: none;">
                            <label for="video_thumbnail">Video Thumbnail URL</label>
                            <input type="url" name="video_thumbnail" id="video_thumbnail" class="form-control" value="<?= old('video_thumbnail') ?>">
                        </div>

                        <!-- Video Duration (for videos) -->
                        <div class="form-group" id="video_duration_field" style="display: none;">
                            <label for="video_duration">Duration</label>
                            <input type="text" name="video_duration" id="video_duration" class="form-control" value="<?= old('video_duration') ?>" placeholder="e.g., 15:30">
                        </div>

                        <!-- File Upload (for past papers) -->
                        <div class="form-group" id="file_field" style="display: none;">
                            <label for="file">Upload PDF File *</label>
                            <input type="file" name="file" id="file" class="form-control-file" accept=".pdf">
                            <small class="form-text text-muted">Maximum file size: 10MB. PDF only.</small>
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="4" maxlength="2000"><?= old('description') ?></textarea>
                        </div>

                        <!-- Tags -->
                        <div class="form-group">
                            <label for="tags">Tags</label>
                            <input type="text" name="tags" id="tags" class="form-control" value="<?= old('tags') ?>" placeholder="Comma-separated tags">
                        </div>

                        <!-- Featured -->
                        <div class="form-check mb-3">
                            <input type="checkbox" name="is_featured" id="is_featured" class="form-check-input" value="1" <?= old('is_featured') ? 'checked' : '' ?>>
                            <label for="is_featured" class="form-check-label">Mark as Featured</label>
                        </div>

                        <hr>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>Add Resource
                        </button>
                        <a href="<?= site_url('admin/library') ?>" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-info-circle mr-2"></i>Tips</h6>
                </div>
                <div class="card-body">
                    <h6>Video Solutions:</h6>
                    <ul class="small">
                        <li>Enter YouTube URL or direct video link</li>
                        <li>Add clear, descriptive titles</li>
                        <li>Specify subject and grade level</li>
                    </ul>

                    <h6 class="mt-3">Past Papers:</h6>
                    <ul class="small">
                        <li>Upload PDF files only (max 10MB)</li>
                        <li>Include year and paper type</li>
                        <li>Use clear naming convention</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function toggleResourceFields() {
    const resourceType = document.getElementById('resource_type').value;
    
    // Video fields
    const videoFields = ['video_url_field', 'video_thumbnail_field', 'video_duration_field'];
    // Past paper fields
    const paperFields = ['file_field'];
    
    if (resourceType === 'video') {
        videoFields.forEach(id => {
            document.getElementById(id).style.display = 'block';
            if (id === 'video_url_field') {
                document.getElementById('video_url').required = true;
            }
        });
        paperFields.forEach(id => {
            document.getElementById(id).style.display = 'none';
            if (id === 'file_field') {
                document.getElementById('file').required = false;
            }
        });
    } else if (resourceType === 'past_paper') {
        paperFields.forEach(id => {
            document.getElementById(id).style.display = 'block';
            if (id === 'file_field') {
                document.getElementById('file').required = true;
            }
        });
        videoFields.forEach(id => {
            document.getElementById(id).style.display = 'none';
            if (id === 'video_url_field') {
                document.getElementById('video_url').required = false;
            }
        });
    } else {
        [...videoFields, ...paperFields].forEach(id => {
            document.getElementById(id).style.display = 'none';
        });
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleResourceFields();
});
</script>
<?= $this->endSection() ?>
