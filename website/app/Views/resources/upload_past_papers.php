<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#E55C0D">
    <title>Upload Past Papers - TutorConnect Malawi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #E55C0D;
            --secondary-color: #C94609;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --accent-color: #0ea5e9;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --bg-primary: #f8fafc;
            --bg-secondary: #ffffff;
            --bg-accent: #f0f9ff;
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --border-radius: 16px;
            --border-radius-lg: 20px;
        }

        * { box-sizing: border-box; }

        body {
            background: var(--bg-primary);
            font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'SF Pro Text', 'Segoe UI', Roboto, sans-serif;
            margin: 0;
            padding: 0;
        }

        .app-container {
            width: 100%;
            max-width: 480px;
            margin: 0 auto;
            min-height: 100vh;
            background: var(--bg-primary);
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
        }

        @media (min-width: 768px) {
            .app-container { max-width: 100%; box-shadow: none; }
        }

        .status-bar { height: 0; background: var(--bg-secondary); border-bottom: 1px solid rgba(0,0,0,0.1); position: sticky; top: 0; z-index: 100; }
        @media (min-width: 768px) { .status-bar { display: none; } }
        @media (max-width: 767px) { .status-bar { display: none; } }

        .main-content {
            padding: 16px;
            padding-bottom: 100px; /* Space for bottom nav */
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
            max-width: 100%;
            background: var(--bg-secondary);
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            gap: 8px;
            padding: 10px 14px;
            z-index: 100;
        }

        @media (min-width: 768px) {
            .bottom-nav { gap: 12px; }
        }

        .nav-item {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: var(--text-light);
            transition: all 0.3s ease;
            padding: 6px 4px;
            border-radius: 12px;
        }

        .nav-item:hover {
            background: rgba(0, 0, 0, 0.05);
        }

        .nav-item.active {
            color: var(--primary-color);
        }

        .navbar {
            background: var(--bg-secondary);
            border-bottom: 1px solid rgba(0,0,0,0.1);
            padding: 12px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 99;
        }

        .nav-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-dark);
            margin: 0;
        }

        .nav-button {
            background: none;
            border: none;
            color: var(--primary-color);
            font-size: 16px;
            padding: 8px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .nav-button:hover {
            background: rgba(5, 150, 105, 0.1);
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 16px;
        }

        .content {
            padding: 20px 16px;
            padding-bottom: 100px;
        }

        .card {
            background: var(--bg-secondary);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            margin-bottom: 16px;
            overflow: hidden;
        }

        .card-header {
            padding: 16px;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-dark);
            margin: 0;
        }

        .card-content {
            padding: 16px;
        }

        .page-header {
            margin-bottom: 20px;
        }

        .page-title {
            font-size: 26px;
            font-weight: 800;
            color: var(--text-dark);
            margin: 0;
        }

        .page-subtitle {
            color: var(--text-light);
            margin: 6px 0 0;
            font-size: 14px;
        }

        .upload-form {
            background: var(--bg-secondary);
            border-radius: var(--border-radius);
            padding: 2rem;
            box-shadow: var(--shadow);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-control, .form-select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid rgba(0,0,0,0.1);
            border-radius: 8px;
            font-size: 1rem;
            background: var(--bg-secondary);
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(229, 92, 13, 0.1);
        }

        .file-upload-area {
            border: 2px dashed rgba(0,0,0,0.2);
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            background: var(--bg-primary);
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
        }

        .file-upload-area:hover {
            border-color: var(--primary-color);
            background: rgba(229, 92, 13, 0.05);
        }

        .file-upload-area.dragover {
            border-color: var(--primary-color);
            background: rgba(229, 92, 13, 0.1);
        }

        .file-upload-icon {
            font-size: 3rem;
            color: var(--text-light);
            margin-bottom: 1rem;
        }

        .file-upload-text {
            font-size: 1.1rem;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .file-upload-hint {
            font-size: 0.9rem;
            color: var(--text-light);
        }

        .file-input {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .btn-primary {
            background: var(--primary-color);
            border: none;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-primary:hover {
            background: var(--secondary-color);
            transform: translateY(-1px);
        }

        .btn-primary:disabled {
            background: var(--text-light);
            cursor: not-allowed;
            transform: none;
        }

        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }

        .toast {
            background: var(--bg-secondary);
            border-radius: var(--border-radius);
            padding: 1rem;
            margin-bottom: 0.5rem;
            box-shadow: var(--shadow);
            border-left: 4px solid var(--primary-color);
            max-width: 300px;
        }

        .toast.success { border-left-color: var(--success-color); }
        .toast.error { border-left-color: var(--danger-color); }
        .toast.warning { border-left-color: var(--warning-color); }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .loading-content {
            background: var(--bg-secondary);
            padding: 2rem;
            border-radius: var(--border-radius);
            text-align: center;
            box-shadow: var(--shadow);
        }

        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid rgba(0,0,0,0.1);
            border-left-color: var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .error-message {
            color: var(--danger-color);
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .success-message {
            color: var(--success-color);
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        @media (max-width: 480px) {
            .upload-form {
                padding: 1.5rem;
            }

            .file-upload-area {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Mobile Status Bar Simulation -->
        <div class="status-bar"></div>

        <!-- Navigation Bar -->
        <nav class="navbar">
            <div class="d-flex align-items-center justify-content-between w-100 px-3">
                <div class="d-flex align-items-center">
                    <h1 class="nav-title mb-0 me-3">Upload</h1>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn p-0 border-0 bg-transparent nav-button" onclick="window.location.href='<?php echo base_url('logout'); ?>'">
                        <i class="fas fa-sign-out-alt text-muted" style="font-size: 20px;"></i>
                    </button>
                    <div class="avatar">
                        <?= strtoupper(substr(session()->get('first_name') ?? 'T', 0, 1)) ?>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <div class="page-header">
                <h1 class="page-title">Upload Past Papers</h1>
                <p class="page-subtitle">Share educational resources with students</p>
            </div>

            <!-- Upload Form -->
            <div class="upload-form">
                <form id="uploadForm" enctype="multipart/form-data">
                    <!-- Exam Body -->
                    <div class="form-group">
                        <label for="exam_body" class="form-label">Exam Body *</label>
                        <select id="exam_body" name="exam_body" class="form-select" required>
                            <option value="">Select Exam Body</option>
                            <?php foreach ($available_curricula as $key => $label): ?>
                                <option value="<?= esc($key) ?>"><?= esc($label) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div id="exam_body_error" class="error-message"></div>
                    </div>

                    <!-- Exam Level -->
                    <div class="form-group">
                        <label for="exam_level" class="form-label">Exam Level *</label>
                        <select id="exam_level" name="exam_level" class="form-select" required disabled>
                            <option value="">Select Exam Level</option>
                        </select>
                        <div id="exam_level_error" class="error-message"></div>
                    </div>

                    <!-- Subject -->
                    <div class="form-group">
                        <label for="subject" class="form-label">Subject *</label>
                        <select id="subject" name="subject" class="form-select" required disabled>
                            <option value="">Select Subject</option>
                        </select>
                        <div id="subject_error" class="error-message"></div>
                    </div>

                    <!-- Year -->
                    <div class="form-group">
                        <label for="year" class="form-label">Year *</label>
                        <input type="number" id="year" name="year" class="form-control"
                               placeholder="e.g., 2025" min="2000" max="<?= date('Y') + 1 ?>" required>
                        <div id="year_error" class="error-message"></div>
                    </div>

                    <!-- Paper Title -->
                    <div class="form-group">
                        <label for="paper_title" class="form-label">Paper Title</label>
                        <input type="text" id="paper_title" name="paper_title" class="form-control"
                               placeholder="e.g., Paper 3" maxlength="255">
                        <div id="paper_title_error" class="error-message"></div>
                    </div>

                    <!-- Paper Code -->
                    <div class="form-group">
                        <label for="paper_code" class="form-label">Paper Code</label>
                        <input type="text" id="paper_code" name="paper_code" class="form-control"
                               placeholder="e.g., 344" maxlength="50">
                        <div id="paper_code_error" class="error-message"></div>
                    </div>

                    <!-- Copyright Notice -->
                    <div class="form-group">
                        <label for="copyright_notice" class="form-label">Copyright Notice</label>
                        <textarea id="copyright_notice" name="copyright_notice" class="form-control"
                                  rows="3" placeholder="Add any copyright or attribution information" maxlength="1000"></textarea>
                        <div id="copyright_notice_error" class="error-message"></div>
                    </div>

                    <!-- File Upload -->
                    <div class="form-group">
                        <label class="form-label">PDF File *</label>
                        <div class="file-upload-area" id="fileUploadArea">
                            <div class="file-upload-icon">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <div class="file-upload-text">Click to upload or drag and drop</div>
                            <div class="file-upload-hint">PDF files only (max 50MB)</div>
                            <input type="file" id="pdf_file" name="pdf_file" class="file-input" accept=".pdf" required>
                        </div>
                        <div id="pdf_file_error" class="error-message"></div>
                        <div id="file_info" class="success-message" style="display: none;"></div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="submitBtn" class="btn-primary">
                        <i class="fas fa-upload me-2"></i>Upload Past Paper
                    </button>
                </form>
            </div>
        </main>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-content">
            <div class="loading-spinner"></div>
            <h4>Uploading...</h4>
            <p>Please wait while we process your file.</p>
        </div>
    </div>

    <!-- Bottom Navigation -->
    <nav class="bottom-nav">
        <a href="<?= site_url('trainer/dashboard') ?>" class="nav-item">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
        <a href="<?= site_url('trainer/subjects') ?>" class="nav-item">
            <i class="fas fa-book"></i>
            <span>Subjects</span>
        </a>
        <a href="<?= site_url('trainer/profile') ?>" class="nav-item">
            <i class="fas fa-user"></i>
            <span>Profile</span>
        </a>
        <a href="<?= site_url('trainer/subscription') ?>" class="nav-item">
            <i class="fas fa-crown"></i>
            <span>Premium</span>
        </a>
    </nav>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Curriculum and subject data
        const curriculumSubjects = <?= json_encode($curriculum_subjects) ?>;
        const levelsByCurriculum = <?= json_encode($levels_by_curriculum) ?>;

        document.addEventListener('DOMContentLoaded', function() {
            const examBodySelect = document.getElementById('exam_body');
            const examLevelSelect = document.getElementById('exam_level');
            const subjectSelect = document.getElementById('subject');
            const fileUploadArea = document.getElementById('fileUploadArea');
            const fileInput = document.getElementById('pdf_file');
            const fileInfo = document.getElementById('file_info');
            const uploadForm = document.getElementById('uploadForm');
            const submitBtn = document.getElementById('submitBtn');

            // Exam body change handler
            examBodySelect.addEventListener('change', function() {
                const selectedCurriculum = this.value;
                examLevelSelect.innerHTML = '<option value="">Select Exam Level</option>';
                subjectSelect.innerHTML = '<option value="">Select Subject</option>';

                if (selectedCurriculum && levelsByCurriculum[selectedCurriculum]) {
                    examLevelSelect.disabled = false;
                    levelsByCurriculum[selectedCurriculum].forEach(level => {
                        const option = document.createElement('option');
                        option.value = level;
                        option.textContent = level;
                        examLevelSelect.appendChild(option);
                    });
                } else {
                    examLevelSelect.disabled = true;
                    subjectSelect.disabled = true;
                }
            });

            // Exam level change handler
            examLevelSelect.addEventListener('change', function() {
                const selectedCurriculum = examBodySelect.value;
                const selectedLevel = this.value;
                subjectSelect.innerHTML = '<option value="">Select Subject</option>';

                if (selectedCurriculum && selectedLevel && curriculumSubjects[selectedCurriculum] &&
                    curriculumSubjects[selectedCurriculum][selectedLevel]) {
                    subjectSelect.disabled = false;
                    const subjects = curriculumSubjects[selectedCurriculum][selectedLevel];

                    // Handle both array and object formats
                    if (Array.isArray(subjects)) {
                        subjects.forEach(subject => {
                            const option = document.createElement('option');
                            option.value = subject;
                            option.textContent = subject;
                            subjectSelect.appendChild(option);
                        });
                    } else if (typeof subjects === 'object') {
                        // If it's an object, get the values
                        Object.values(subjects).forEach(subject => {
                            const option = document.createElement('option');
                            option.value = subject;
                            option.textContent = subject;
                            subjectSelect.appendChild(option);
                        });
                    }
                } else {
                    subjectSelect.disabled = true;
                }
            });

            // File upload handlers
            fileUploadArea.addEventListener('click', () => fileInput.click());

            fileUploadArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('dragover');
            });

            fileUploadArea.addEventListener('dragleave', function(e) {
                e.preventDefault();
                this.classList.remove('dragover');
            });

            fileUploadArea.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('dragover');
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    fileInput.files = files;
                    handleFileSelect(files[0]);
                }
            });

            fileInput.addEventListener('change', function() {
                if (this.files.length > 0) {
                    handleFileSelect(this.files[0]);
                }
            });

            function handleFileSelect(file) {
                if (file.type !== 'application/pdf') {
                    showToast('error', 'Invalid File', 'Please select a PDF file.');
                    fileInput.value = '';
                    return;
                }

                if (file.size > 50 * 1024 * 1024) { // 50MB
                    showToast('error', 'File Too Large', 'File size must be less than 50MB.');
                    fileInput.value = '';
                    return;
                }

                const fileSize = (file.size / 1024 / 1024).toFixed(2) + ' MB';
                fileInfo.textContent = `Selected: ${file.name} (${fileSize})`;
                fileInfo.style.display = 'block';
            }

            // Form submission
            uploadForm.addEventListener('submit', function(e) {
                e.preventDefault();

                if (!validateForm()) {
                    return;
                }

                const formData = new FormData(this);
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Uploading...';

                document.getElementById('loadingOverlay').style.display = 'flex';

                fetch('<?= site_url('upload-paper') ?>', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('loadingOverlay').style.display = 'none';

                    if (data.success) {
                        showToast('success', 'Success!', data.message);
                        setTimeout(() => {
                            window.location.href = data.redirect || '<?= site_url('resources') ?>';
                        }, 2000);
                    } else {
                        showToast('error', 'Upload Failed', data.message);
                        if (data.errors) {
                            displayValidationErrors(data.errors);
                        }
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fas fa-upload me-2"></i>Upload Past Paper';
                    }
                })
                .catch(error => {
                    document.getElementById('loadingOverlay').style.display = 'none';
                    console.error('Upload error:', error);
                    showToast('error', 'Network Error', 'Please check your connection and try again.');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-upload me-2"></i>Upload Past Paper';
                });
            });

            function validateForm() {
                let isValid = true;
                clearValidationErrors();

                // Check required fields
                const requiredFields = ['exam_body', 'exam_level', 'subject', 'year'];
                requiredFields.forEach(field => {
                    const element = document.getElementById(field);
                    if (!element.value.trim()) {
                        showFieldError(field, 'This field is required');
                        isValid = false;
                    }
                });

                // Check file
                if (!fileInput.files.length) {
                    showFieldError('pdf_file', 'Please select a PDF file');
                    isValid = false;
                }

                return isValid;
            }

            function showFieldError(fieldId, message) {
                const errorElement = document.getElementById(fieldId + '_error');
                if (errorElement) {
                    errorElement.textContent = message;
                }
            }

            function clearValidationErrors() {
                const errorElements = document.querySelectorAll('.error-message');
                errorElements.forEach(element => {
                    element.textContent = '';
                });
            }

            function displayValidationErrors(errors) {
                Object.keys(errors).forEach(field => {
                    const errorElement = document.getElementById(field + '_error');
                    if (errorElement) {
                        errorElement.textContent = errors[field];
                    }
                });
            }

            function showToast(type, title, message) {
                const container = document.getElementById('toastContainer');
                const toast = document.createElement('div');
                toast.className = `toast ${type}`;

                const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
                const iconColor = type === 'success' ? 'var(--success-color)' : type === 'error' ? 'var(--danger-color)' : 'var(--warning-color)';

                toast.innerHTML = `
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <i class="fas ${iconClass}" style="color: ${iconColor}; font-size: 1.25rem;"></i>
                        <div>
                            <div style="font-weight: 600; color: var(--text-dark);">${title}</div>
                            <div style="font-size: 0.875rem; color: var(--text-light);">${message}</div>
                        </div>
                    </div>
                `;

                container.appendChild(toast);

                // Auto remove after 5 seconds
                setTimeout(() => {
                    toast.style.opacity = '0';
                    setTimeout(() => toast.remove(), 300);
                }, 5000);
            }
        });
    </script>
</body>
</html>
