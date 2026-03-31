<?= $this->extend('layouts/admin') ?>

<?php $active_page = 'curriculum'; ?>
<?php $title = $title ?? 'Add Curriculum Subject - TutorConnect Malawi'; ?>

<?= $this->section('content') ?>
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">Add Curriculum Subject</h1>
            <p class="text-muted">Create a new curriculum subject for tutoring</p>
        </div>
    </div>

    <!-- Add Subject Form -->
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Subject Information</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= site_url('admin/curriculum/create') ?>">
                        <?= csrf_field() ?>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="curriculum" class="form-label">Curriculum *</label>
                                <select name="curriculum" id="curriculum" class="form-select" required>
                                    <option value="">Select Curriculum</option>
                                    <?php foreach ($curricula as $curriculum): ?>
                                        <option value="<?= $curriculum ?>"><?= $curriculum ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text">Choose the curriculum this subject belongs to</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="subject_category" class="form-label">Subject Category</label>
                                <select name="subject_category" id="subject_category" class="form-select">
                                    <option value="">Select Category (Optional)</option>
                                    <?php foreach ($subject_categories as $category): ?>
                                        <option value="<?= $category ?>"><?= $category ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text">Categorize the subject for better organization</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="level_name" class="form-label">Education Level *</label>
                            <input type="text" class="form-control" id="level_name" name="level_name"
                                   placeholder="e.g., Primary: Standards 1–8, Key Stage 4 (Years 10–11)" required>
                            <div class="form-text">Specify the education level or grade this subject is taught at</div>
                        </div>

                        <div class="mb-3">
                            <label for="subject_name" class="form-label">Subject Name *</label>
                            <input type="text" class="form-control" id="subject_name" name="subject_name"
                                   placeholder="e.g., Mathematics, English Language, Biology" required>
                            <div class="form-text">The name of the subject</div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
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
                                    <div id="preview-curriculum" class="badge bg-primary">-</div>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">Level:</small>
                                    <div id="preview-level"><strong>-</strong></div>
                                </div>
                            </div>
                            <div class="mt-2">
                                <small class="text-muted">Subject:</small>
                                <div id="preview-subject">-</div>
                            </div>
                            <div class="mt-2">
                                <small class="text-muted">Category:</small>
                                <span id="preview-category" class="badge bg-secondary">General</span>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?= site_url('admin/curriculum') ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Curriculum
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Create Subject
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Add Suggestions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Quick Add Suggestions</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Common subjects by curriculum:</p>

                    <div class="accordion" id="suggestionsAccordion">
                        <!-- MANEB Suggestions -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#manebSuggestions">
                                    MANEB (Malawi National Curriculum)
                                </button>
                            </h2>
                            <div id="manebSuggestions" class="accordion-collapse collapse" data-bs-parent="#suggestionsAccordion">
                                <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Primary Level</h6>
                                            <small class="text-muted">Chichewa, English, Mathematics, Expressive Arts, Life Skills, Social and Environmental Sciences, Science and Technology, Agriculture</small>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Secondary Level</h6>
                                            <small class="text-muted">English Language, Mathematics, Chichewa, Social Studies, Biology, Physics, Chemistry, Geography, History, Agriculture, Computer Studies</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- GCSE Suggestions -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#gcseSuggestions">
                                    GCSE (UK)
                                </button>
                            </h2>
                            <div id="gcseSuggestions" class="accordion-collapse collapse" data-bs-parent="#suggestionsAccordion">
                                <div class="accordion-body">
                                    <h6>Key Stage 4 Subjects</h6>
                                    <small class="text-muted">English Language, English Literature, Mathematics, Combined Science, Biology, Chemistry, Physics, Art and Design, Computer Science, Geography, History, Modern Foreign Languages, Music, Physical Education, Religious Studies</small>
                                </div>
                            </div>
                        </div>

                        <!-- Cambridge Suggestions -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#cambridgeSuggestions">
                                    Cambridge International
                                </button>
                            </h2>
                            <div id="cambridgeSuggestions" class="accordion-collapse collapse" data-bs-parent="#suggestionsAccordion">
                                <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <h6>Primary</h6>
                                            <small class="text-muted">English, Mathematics, Science, Art & Design, Computing, Global Perspectives</small>
                                        </div>
                                        <div class="col-md-4">
                                            <h6>Lower Secondary</h6>
                                            <small class="text-muted">English, Mathematics, Science, Art & Design, Digital Literacy, Global Perspectives</small>
                                        </div>
                                        <div class="col-md-4">
                                            <h6>Upper Secondary</h6>
                                            <small class="text-muted">English, Mathematics, Biology, Chemistry, Physics, Computer Science, Accounting, Business Studies, Geography, History</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
