<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Resubmission - TutorConnect Malawi</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
        :root { --primary: #E74C3C; --secondary: #2C3E50; --accent: #34495E; --gray: #666; --success: #27ae60; --warning: #f1c40f; --danger: #e74c3c; }
        * { box-sizing: border-box; }
        body, html { height: 100%; margin: 0; font-family: 'Inter', sans-serif; background: white; }

        .resubmission-wrapper {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        /* Left: Professional Message */
        .image-side {
            background: linear-gradient(rgba(231,76,60,0.95), rgba(231,76,60,0.98)),
                        url('https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=1200&h=800&fit=crop&crop=center') center/cover;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            padding: 2rem;
        }
        .image-side h1 {
            font-size: 2.1rem;
            font-weight: 800;
            margin-bottom: 1rem;
            line-height: 1.2;
        }
        .image-side p {
            font-size: 1.05rem;
            max-width: 340px;
            opacity: 0.96;
            font-weight: 500;
        }

        /* Right: Modern Form */
        .form-side {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            background: white;
        }
        .form-box {
            width: 100%;
            max-width: 420px;
        }

        /* TutorConnect + Malawi */
        .logo {
            font-size: 1.8rem;
            font-weight: 800;
            text-align: center;
            margin-bottom: 1rem;
        }
        .logo span:first-child { color: var(--primary); }
        .logo span:last-child { color: #000; }

        /* Modern File Upload Styling */
        .file-upload-wrapper {
            position: relative;
            width: 100%;
            margin-bottom: 1.3rem;
        }
        .file-upload-area {
            border: 2px dashed #e2e8f0;
            border-radius: 12px;
            padding: 2rem 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: rgba(231, 76, 60, 0.02);
            position: relative;
            overflow: hidden;
        }
        .file-upload-area:hover {
            border-color: var(--primary);
            background: rgba(231, 76, 60, 0.05);
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(231, 76, 60, 0.15);
        }
        .file-upload-area.dragover {
            border-color: var(--primary);
            background: rgba(231, 76, 60, 0.08);
            box-shadow: 0 6px 20px rgba(231, 76, 60, 0.2);
        }
        .file-upload-area.success {
            border-color: var(--success);
            background: rgba(39, 174, 96, 0.05);
        }
        .file-upload-icon {
            font-size: 2.2rem;
            color: var(--gray);
            margin-bottom: 0.8rem;
            transition: color 0.3s ease;
        }
        .file-upload-area:hover .file-upload-icon {
            color: var(--primary);
        }
        .file-upload-text {
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 0.3rem;
            font-size: 1.1rem;
        }
        .file-upload-hint {
            font-size: 0.85rem;
            color: var(--gray);
        }
        .file-upload-area.success .file-upload-icon { color: var(--success); }
        .file-upload-area.success .file-upload-hint { color: var(--success); }

        /* File input and progress */
        .file-input {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        .file-name {
            font-size: 0.85rem;
            color: var(--secondary);
            font-weight: 600;
            margin-top: 0.8rem;
            background: rgba(39, 174, 96, 0.1);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            display: none;
        }
        .file-progress {
            margin-top: 1rem;
            display: none;
        }
        .progress-bar {
            height: 6px;
            background: var(--primary);
            border-radius: 3px;
            transition: width 0.3s ease;
        }

        /* Submit Button */
        .btn-primary {
            background: var(--primary);
            border: none;
            border-radius: 12px;
            padding: 0.8rem 2rem;
            font-weight: 600;
            font-size: 0.95rem;
            width: 100%;
            margin-top: 1rem;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(231, 76, 60, 0.3);
        }

        /* Back Link */
        .back-link {
            text-align: center;
            margin-top: 1.5rem;
            color: var(--gray);
            text-decoration: none;
            font-weight: 500;
        }
        .back-link:hover { color: var(--primary); }

        /* Alert Styling */
        .notice-alert {
            background: rgba(52, 152, 219, 0.1);
            border: 1px solid rgba(52, 152, 219, 0.3);
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        .notice-alert .alert-icon {
            color: #3498db;
            font-size: 1.2rem;
            margin-right: 0.5rem;
        }

        /* Requirement text */
        .requirement-text {
            font-size: 0.8rem;
            color: var(--gray);
            margin-top: 0.5rem;
            font-style: italic;
        }

        @media (max-width: 991px) {
            .resubmission-wrapper {
                grid-template-columns: 1fr;
                min-height: auto;
            }
            .image-side { display: none; }
            .form-side { padding: 2rem 1rem; }
            .form-box { max-width: 100%; }
        }
    </style>
</head>
<body>

<div class="resubmission-wrapper">

    <!-- Left: Professional Message -->
    <div class="image-side d-none d-lg-flex">
        <div>
            <h1>Update Your Documents</h1>
            <p>Our verification team needs corrected documents. Upload them below and get back to tutoring!</p>
        </div>
    </div>

    <!-- Right: Modern Upload Form -->
    <div class="form-side">
        <div class="form-box">
            <div class="logo"><span>TutorConnect</span> <span>Malawi</span></div>

            <h6 class="text-center fw-semibold mb-1">Document Resubmission</h6>
            <p class="text-center text-muted mb-3" style="font-size: 0.85rem;">Our team found some issues with your documents</p>

            <div class="notice-alert">
                <div class="d-flex align-items-start">
                    <i class="fas fa-info-circle alert-icon mt-1"></i>
                    <div>
                        <strong>Important:</strong> Please upload corrected documents for the items listed below. Our verification team will review them within 24-48 hours.
                    </div>
                </div>
            </div>

            <form id="resubmissionForm" enctype="multipart/form-data">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>" />

                <h6 class="mb-3"><i class="fas fa-user me-2 text-primary"></i>Your Information</h6>
                <div class="mb-4 p-3 rounded" style="background: rgba(231, 76, 60, 0.02); border: 1px solid rgba(231, 76, 60, 0.1);">
                    <div class="row text-center">
                        <div class="col-6">
                            <small class="text-muted d-block">Name</small>
                            <strong><?php echo esc($user['first_name'] . ' ' . $user['last_name']); ?></strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Email</small>
                            <strong><?php echo esc($user['email']); ?></strong>
                        </div>
                    </div>
                </div>

                <h6 class="mb-3"><i class="fas fa-file-upload me-2 text-warning"></i>Documents Needing Resubmission</h6>

                <?php foreach ($documents_needing_resubmission as $index => $doc): ?>
                    <div class="file-upload-wrapper">
                        <div class="file-upload-area" data-doc-type="<?php echo esc($doc['type']); ?>" data-required="<?php echo $doc['required'] ?? true ? 'true' : 'false'; ?>">
                            <input type="file" class="file-input" name="<?php echo esc($doc['type']); ?>" accept="image/*,.pdf,.doc,.docx"
                                   <?php echo ($doc['required'] ?? true) ? 'required' : ''; ?> />

                            <div class="file-upload-icon">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <div class="file-upload-text">
                                <?php echo esc($doc['name']); ?> <?php echo ($doc['required'] ?? true) ? '<span style="color: var(--danger);">*</span>' : '(Optional)'; ?>
                            </div>
                            <div class="file-upload-hint">
                                Click to browse or drag & drop
                                <br><small>Max: 5MB | JPG, PNG, PDF, DOC, DOCX</small>
                            </div>
                        </div>
                        <div class="file-name" id="file-name-<?php echo esc($doc['type']); ?>"></div>
                        <div class="file-progress">
                            <div class="progress">
                                <div class="progress-bar" style="width: 0%"></div>
                            </div>
                        </div>

                        <?php if (!empty($doc['message'])): ?>
                        <div class="requirement-text">
                            <i class="fas fa-exclamation-triangle text-warning me-1"></i>
                            <strong>Comments:</strong> <?php echo esc($doc['message']); ?>
                        </div>
                        <?php endif; ?>

                        <?php
                        $requirements = [
                            'profile_photo' => 'Clear, professional photo on plain background',
                            'national_id' => 'Both sides of ID/Passport clearly visible',
                            'police_clearance' => 'Official certificate within last 6 months',
                            'academic_certificates' => 'Certified copies with degree qualifications',
                            'references' => 'Reference letters from employers/institutions',
                            'cv' => 'Detailed teaching experience & qualifications'
                        ];
                        if (isset($requirements[$doc['type']])):
                        ?>
                        <div class="requirement-text">
                            <i class="fas fa-info-circle text-primary me-1"></i>
                            <strong>Requirements:</strong> <?php echo esc($requirements[$doc['type']]); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <span id="submitBtnText"><i class="fas fa-upload me-2"></i>Submit Resubmission</span>
                    <span id="submitSpinner" class="d-none"><i class="fas fa-spinner fa-spin me-2"></i>Uploading...</span>
                </button>

                <div id="alertContainer"></div>

                <a href="<?php echo base_url(); ?>" class="back-link">
                    <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
                </a>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check if already submitted recently and disable form
    const currentTime = new Date();
    const lastSubmissionTime = localStorage.getItem('documentResubmissionLastSubmit');

    // Check if there are pending resubmission requests (always allow these regardless of cooldown)
    const hasPendingResubmissions = <?php echo !empty($documents_needing_resubmission) ? 'true' : 'false'; ?>;

    // Clear localStorage cooldown if there are new pending requests from admin
    if (hasPendingResubmissions) {
        localStorage.removeItem('documentResubmissionLastSubmit');
    }

    // Only apply cooldown if there's no pending admin request
    if (!hasPendingResubmissions && lastSubmissionTime) {
        const timeDiff = currentTime.getTime() - parseInt(lastSubmissionTime);
        const hoursDiff = timeDiff / (1000 * 60 * 60); // Convert to hours

        // Allow one submission every 24 hours
        if (hoursDiff < 24) {
            disableFormAfterSubmission();
            showAlert('You have already submitted documents recently. Next submission allowed in ' + Math.ceil(24 - hoursDiff) + ' hours.', 'info');
        }
    }

    // File upload functionality
    const fileUploadAreas = document.querySelectorAll('.file-upload-area');

    fileUploadAreas.forEach(area => {
        const fileInput = area.querySelector('.file-input');
        const fileNameDisplay = area.closest('.file-upload-wrapper').querySelector('.file-name');
        const progress = area.closest('.file-upload-wrapper').querySelector('.file-progress');

        // Click to trigger file input
        area.addEventListener('click', () => {
            if (fileInput.disabled) return; // Don't allow clicking if form is disabled
            fileInput.click();
        });

        // Drag and drop functionality
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            area.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            area.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            area.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            area.classList.add('dragover');
        }

        function unhighlight(e) {
            area.classList.remove('dragover');
        }

        area.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;

            if (files.length > 0) {
                handleFiles(files, area);
            }
        }

        // Handle file selection
        fileInput.addEventListener('change', function(e) {
            if (this.files.length > 0) {
                handleFiles(this.files, area);
            }
        });

        function handleFiles(files, area) {
            const file = files[0];
            validateAndProcessFile(file, area);
        }

        function validateAndProcessFile(file, area) {
            const maxSize = 5 * 1024 * 1024; // 5MB
            const allowedTypes = [
                'image/jpeg', 'image/jpg', 'image/png', 'image/gif',
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            ];

            // Validate file type
            if (!allowedTypes.includes(file.type)) {
                showAlert('Invalid file type. Please select JPG, PNG, PDF, DOC, or DOCX.', 'danger');
                resetFileArea(area);
                return;
            }

            // Validate file size
            if (file.size > maxSize) {
                showAlert('File size too large. Maximum size is 5MB.', 'danger');
                resetFileArea(area);
                return;
            }

            // File is valid - show success state
            showFileSelected(file, area);
        }

        function showFileSelected(file, area) {
            area.classList.add('success');
            const fileNameDisplay = area.closest('.file-upload-wrapper').querySelector('.file-name');
            fileNameDisplay.textContent = file.name;
            fileNameDisplay.style.display = 'block';
        }

        function resetFileArea(area) {
            area.classList.remove('success');
            const fileNameDisplay = area.closest('.file-upload-wrapper').querySelector('.file-name');
            fileNameDisplay.style.display = 'none';
            const fileInput = area.querySelector('.file-input');
            fileInput.value = '';
        }
    });

    // Form submission
    const form = document.getElementById('resubmissionForm');
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Validate required fields
        const requiredFileAreas = document.querySelectorAll('.file-upload-area[data-required="true"]');
        let allFilesUploaded = true;
        let missingFiles = [];

        requiredFileAreas.forEach(area => {
            const fileInput = area.querySelector('.file-input');
            if (!fileInput.files || fileInput.files.length === 0) {
                area.style.borderColor = 'var(--danger)';
                area.style.background = 'rgba(231, 76, 60, 0.1)';
                const docName = area.querySelector('.file-upload-text').textContent.split(' *')[0];
                missingFiles.push(docName);
                allFilesUploaded = false;
            } else {
                area.style.borderColor = 'var(--success)';
                area.style.background = 'rgba(39, 174, 96, 0.1)';
            }
        });

        if (!allFilesUploaded) {
            showAlert('Please upload all required documents:\n\n• ' + missingFiles.join('\n• '), 'danger');
            return;
        }

        // Show loading state
        const btnText = document.getElementById('submitBtnText');
        const spinner = document.getElementById('submitSpinner');
        submitBtn.disabled = true;
        btnText.classList.add('d-none');
        spinner.classList.remove('d-none');

        const formData = new FormData(form);

        fetch(`<?= base_url('document-resubmission/submit') ?>`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Store submission time in localStorage
                localStorage.setItem('documentResubmissionLastSubmit', new Date().getTime());

                showAlert('Success! ' + data.message, 'success');

                // Disable form after successful submission
                disableFormAfterSubmission();

                // Hide the redirect code since form is disabled
                // setTimeout(() => {
                //     location.href = '<?= base_url() ?>';
                // }, 2000);

            } else {
                showAlert('Error: ' + (data.message || 'Failed to submit documents'), 'danger');
                submitBtn.disabled = false;
                btnText.classList.remove('d-none');
                spinner.classList.add('d-none');
            }
        })
        .catch(error => {
            console.error('Upload error:', error);
            showAlert('An error occurred while uploading. Please try again.', 'danger');
            submitBtn.disabled = false;
            btnText.classList.remove('d-none');
            spinner.classList.add('d-none');
        });
    });

    // Function to disable form after successful submission
    function disableFormAfterSubmission() {
        // Disable all file inputs
        const fileInputs = document.querySelectorAll('.file-input');
        fileInputs.forEach(input => {
            input.disabled = true;
        });

        // Disable submit button
        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('submitBtnText');
        const spinner = document.getElementById('submitSpinner');
        submitBtn.disabled = true;
        btnText.classList.add('d-none');
        spinner.classList.remove('d-none');

        // Change file upload areas to disabled state
        const fileUploadAreas = document.querySelectorAll('.file-upload-area');
        fileUploadAreas.forEach(area => {
            area.style.cursor = 'not-allowed';
            area.style.opacity = '0.6';
            area.classList.remove('success');
            area.classList.add('disabled');
        });

        // Remove click handlers
        fileUploadAreas.forEach(area => {
            area.replaceWith(area.cloneNode(true)); // This removes all event listeners
        });

        // Add disabled styling
        const disabledStyle = document.createElement('style');
        disabledStyle.textContent = `
            .file-upload-area.disabled {
                background: rgba(149, 165, 166, 0.1);
                border-color: #bdc3c7;
            }
            .file-upload-area.disabled:hover {
                transform: none;
                box-shadow: none;
                border-color: #bdc3c7;
            }
            .file-upload-area.disabled .file-upload-text,
            .file-upload-area.disabled .file-upload-hint {
                color: #7f8c8d;
            }
        `;
        document.head.appendChild(disabledStyle);
    }

    // Alert function
    function showAlert(message, type = 'info') {
        const alertContainer = document.getElementById('alertContainer');

        // Clear existing alerts
        alertContainer.innerHTML = '';

        const alertClass = type === 'danger' ? 'danger' : type === 'success' ? 'success' : 'info';
        const alertHtml = `
            <div class="alert alert-${alertClass} alert-dismissible fade show" role="alert">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'danger' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
                ${message.replace(/\n/g, '<br>')}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;

        alertContainer.innerHTML = alertHtml;
    }
});
</script>
</body>
</html>
