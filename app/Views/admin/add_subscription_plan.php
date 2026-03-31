<?= $this->extend('layouts/admin') ?>

<?php $active_page = 'subscriptions'; ?>
<?php $title = $title ?? 'Add Subscription Plan - TutorConnect Malawi'; ?>

<?= $this->section('content') ?>
    <!-- Header -->
    <div class="header-bar">
        <div>
            <h1 class="page-title">Add New Subscription Plan</h1>
            <p class="page-subtitle">Create a new subscription plan for tutors</p>
        </div>
        <a href="<?= site_url('admin/subscriptions') ?>" class="btn-admin btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Plans
        </a>
    </div>

    <!-- Form Card -->
    <div class="content-card">
        <form method="POST" action="<?= site_url('admin/subscriptions/create') ?>" id="planForm">
            <?= csrf_field() ?>

            <div class="row">
                <div class="col-md-8">
                    <!-- Basic Information -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Basic Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Plan Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                        <div class="form-text">A descriptive name for this subscription plan</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="price_monthly" class="form-label">Monthly Price (MWK) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="price_monthly" name="price_monthly" min="0" step="0.01" required>
                                        <div class="form-text">Price in Malawian Kwacha per month</div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Describe what this plan includes..."></textarea>
                                <div class="form-text">Optional description shown to tutors</div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sort_order" class="form-label">Sort Order</label>
                                        <input type="number" class="form-control" id="sort_order" name="sort_order" min="0" value="0">
                                        <div class="form-text">Lower numbers appear first in the list</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="district_spotlight_days" class="form-label">District Spotlight Days</label>
                                        <input type="number" class="form-control" id="district_spotlight_days" name="district_spotlight_days" min="0" value="0">
                                        <div class="form-text">Number of days for district spotlight feature</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Limits and Features -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-sliders-h me-2"></i>Limits & Features</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="max_profile_views" class="form-label">Max Profile Views <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="max_profile_views" name="max_profile_views" min="0" required>
                                        <div class="form-text">Enter 0 for unlimited</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="max_clicks" class="form-label">Max Contact Clicks <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="max_clicks" name="max_clicks" min="0" required>
                                        <div class="form-text">Enter 0 for unlimited</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="max_subjects" class="form-label">Max Subjects <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="max_subjects" name="max_subjects" min="0" required>
                                        <div class="form-text">Enter 0 for unlimited</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="badge_level" class="form-label">Badge Level</label>
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
                                        <label for="search_ranking" class="form-label">Search Ranking</label>
                                        <select class="form-control" name="search_ranking" id="search_ranking">
                                            <option value="low">Low</option>
                                            <option value="normal">Normal</option>
                                            <option value="priority">Priority</option>
                                            <option value="top">Top</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Features -->
                            <div class="mb-3">
                                <label class="form-label">Features & Permissions</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="show_whatsapp" name="show_whatsapp" value="1">
                                            <label class="form-check-label" for="show_whatsapp">
                                                <i class="fab fa-whatsapp me-1 text-success"></i>Show WhatsApp contact
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="email_marketing_access" name="email_marketing_access" value="1">
                                            <label class="form-check-label" for="email_marketing_access">
                                                <i class="fas fa-envelope me-1 text-info"></i>Email marketing access
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="allow_video_upload" name="allow_video_upload" value="1">
                                            <label class="form-check-label" for="allow_video_upload">
                                                <i class="fas fa-video me-1 text-warning"></i>Allow video uploads
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="allow_pdf_upload" name="allow_pdf_upload" value="1">
                                            <label class="form-check-label" for="allow_pdf_upload">
                                                <i class="fas fa-file-pdf me-1 text-danger"></i>Allow PDF uploads
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="allow_video_solution" name="allow_video_solution" value="1">
                                            <label class="form-check-label" for="allow_video_solution">
                                                <i class="fas fa-play-circle me-1 text-primary"></i>Allow video solutions
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="allow_announcements" name="allow_announcements" value="1">
                                            <label class="form-check-label" for="allow_announcements">
                                                <i class="fas fa-bullhorn me-1 text-secondary"></i>Allow announcements
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Status & Actions -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Settings</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                                    <label class="form-check-label" for="is_active">
                                        <strong>Active Plan</strong>
                                    </label>
                                    <div class="form-text">Make this plan available to tutors</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Card -->
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-eye me-2"></i>Plan Preview</h5>
                        </div>
                        <div class="card-body">
                            <div id="planPreview">
                                <div class="text-center mb-3">
                                    <h6 id="previewName">Plan Name</h6>
                                    <div class="h4 text-primary mb-0" id="previewPrice">MK 0/month</div>
                                </div>
                                <div class="small text-muted mb-2" id="previewDescription">Plan description will appear here</div>
                                <hr>
                                <div class="small">
                                    <div id="previewLimits">
                                        <strong>Limits:</strong><br>
                                        • Profile Views: <span id="previewViews">-</span><br>
                                        • Contact Clicks: <span id="previewClicks">-</span><br>
                                        • Subjects: <span id="previewSubjects">-</span>
                                    </div>
                                    <div id="previewFeatures" class="mt-2">
                                        <strong>Features:</strong><br>
                                        <span id="featuresList">None selected</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body text-end">
                            <a href="<?= site_url('admin/subscriptions') ?>" class="btn btn-secondary me-2">
                                <i class="fas fa-times me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Create Plan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

<style>
.btn-admin {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    transition: all 0.3s ease;
}

.btn-admin:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    color: white;
    text-decoration: none;
}

.btn-admin.btn-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
}

.btn-admin.btn-secondary:hover {
    background: linear-gradient(135deg, #5a6268 0%, #495057 100%);
}

.content-card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    margin-bottom: 20px;
}

.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px 12px 0 0 !important;
    border: none;
    padding: 15px 20px;
}

.card-header h5 {
    color: white;
    margin: 0;
    font-weight: 600;
}

.card-body {
    padding: 20px;
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

.form-text {
    font-size: 12px;
    color: #6b7280;
}

.form-check-input:checked {
    background-color: #667eea;
    border-color: #667eea;
}

.form-check-input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.form-check-label {
    font-weight: 500;
    color: #4b5563;
    margin-left: 8px;
    cursor: pointer;
}

.btn {
    border-radius: 8px;
    font-weight: 600;
    padding: 10px 20px;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
}

.btn-secondary {
    background: #6c757d;
    border: none;
}

.btn-secondary:hover {
    background: #5a6268;
}

.text-danger {
    color: #ef4444 !important;
}

#planPreview {
    font-size: 14px;
}

#planPreview h6 {
    color: #374151;
    font-weight: 600;
}

#featuresList {
    color: #6b7280;
}
</style>

<script>
// Update preview as user types
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('planForm');
    const inputs = form.querySelectorAll('input, textarea, select');

    inputs.forEach(input => {
        input.addEventListener('input', updatePreview);
        input.addEventListener('change', updatePreview);
    });

    updatePreview(); // Initial update
});

function updatePreview() {
    // Update name
    const name = document.getElementById('name').value || 'Plan Name';
    document.getElementById('previewName').textContent = name;

    // Update price
    const price = document.getElementById('price_monthly').value || '0';
    document.getElementById('previewPrice').textContent = 'MK ' + parseFloat(price).toLocaleString() + '/month';

    // Update description
    const description = document.getElementById('description').value || 'Plan description will appear here';
    document.getElementById('previewDescription').textContent = description;

    // Update limits
    const views = document.getElementById('max_profile_views').value || '0';
    const clicks = document.getElementById('max_clicks').value || '0';
    const subjects = document.getElementById('max_subjects').value || '0';

    document.getElementById('previewViews').textContent = views === '0' ? 'Unlimited' : views;
    document.getElementById('previewClicks').textContent = clicks === '0' ? 'Unlimited' : clicks;
    document.getElementById('previewSubjects').textContent = subjects === '0' ? 'Unlimited' : subjects;

    // Update features
    const features = [];
    const checkboxes = [
        { id: 'show_whatsapp', label: 'WhatsApp' },
        { id: 'email_marketing_access', label: 'Email Marketing' },
        { id: 'allow_video_upload', label: 'Video Upload' },
        { id: 'allow_pdf_upload', label: 'PDF Upload' },
        { id: 'allow_video_solution', label: 'Video Solutions' },
        { id: 'allow_announcements', label: 'Announcements' }
    ];

    checkboxes.forEach(cb => {
        const checkbox = document.getElementById(cb.id);
        if (checkbox && checkbox.checked) {
            features.push(cb.label);
        }
    });

    document.getElementById('featuresList').textContent = features.length > 0 ? features.join(', ') : 'None selected';
}

// Form validation
document.getElementById('planForm').addEventListener('submit', function(e) {
    const requiredFields = ['name', 'price_monthly', 'max_profile_views', 'max_clicks', 'max_subjects'];
    let isValid = true;

    requiredFields.forEach(field => {
        const element = document.getElementById(field);
        if (!element.value.trim()) {
            element.classList.add('is-invalid');
            isValid = false;
        } else {
            element.classList.remove('is-invalid');
        }
    });

    if (!isValid) {
        e.preventDefault();
        alert('Please fill in all required fields.');
        return false;
    }
});
</script>

<?= $this->endSection() ?>