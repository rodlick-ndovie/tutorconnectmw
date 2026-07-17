<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#E55C0D">
    <title>Upload Video Solutions - TutorConnect Malawi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #E55C0D;
            --secondary-color: #C94609;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --bg-primary: #f8fafc;
            --bg-secondary: #ffffff;
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --border-radius: 16px;
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

        .main-content { padding: 16px; padding-bottom: 100px; }

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

        .card-content { padding: 16px; }

        .form-group { margin-bottom: 1rem; }

        .form-control {
            border-radius: 8px;
            border: 1px solid rgba(0,0,0,0.1);
            padding: 12px;
            font-size: 16px;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(229, 92, 13, 0.1);
        }

        .btn-primary {
            background: var(--primary-color);
            border: none;
            border-radius: 8px;
            padding: 12px 24px;
            font-weight: 500;
        }

        .btn-primary:hover {
            background: var(--secondary-color);
        }

        .alert {
            border-radius: 8px;
            border: none;
        }

        .video-preview {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 16px;
            margin-top: 16px;
            border: 1px solid rgba(0,0,0,0.1);
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
            max-width: 100%;
            background: var(--bg-secondary);
            border-top: 1px solid rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            gap: 8px;
            padding: 10px 14px;
            z-index: 100;
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

        .nav-item:hover { background: rgba(0, 0, 0, 0.05); }
        .nav-item.active { color: var(--primary-color); }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Navigation Bar -->
        <nav class="navbar">
            <div class="d-flex align-items-center justify-content-between w-100 px-3">
                <button class="nav-button" onclick="window.history.back()">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <h1 class="nav-title mb-0">Upload Video Solutions</h1>
                <div style="width: 40px;"></div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Success/Error Messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= esc(session()->getFlashdata('success')) ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?= esc(session()->getFlashdata('error')) ?>
                </div>
            <?php endif; ?>

            <!-- Upload Form -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-video text-danger me-2"></i>
                        Upload New Video Solution
                    </h3>
                </div>
                <div class="card-content">
                    <form id="videoUploadForm" enctype="multipart/form-data">
                        <?= csrf_field() ?>

                        <!-- Video URL -->
                        <div class="form-group">
                            <label for="video_url" class="form-label">
                                <i class="fas fa-link me-1"></i>
                                Video URL <span class="text-danger">*</span>
                            </label>
                            <input type="url" class="form-control" id="video_url" name="video_url"
                                   placeholder="https://youtube.com/watch?v=..." required>
                            <small class="form-text text-muted">
                                Paste the full YouTube or Vimeo video URL
                            </small>
                        </div>

                        <!-- Title -->
                        <div class="form-group">
                            <label for="title" class="form-label">
                                <i class="fas fa-heading me-1"></i>
                                Video Title <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="title" name="title"
                                   placeholder="e.g., Solving Quadratic Equations Step by Step" required>
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left me-1"></i>
                                Description
                            </label>
                            <textarea class="form-control" id="description" name="description" rows="3"
                                      placeholder="Brief description of what this video covers..."></textarea>
                        </div>

                        <!-- Curriculum -->
                        <div class="form-group">
                            <label for="exam_body" class="form-label">
                                <i class="fas fa-graduation-cap me-1"></i>
                                Curriculum <span class="text-danger">*</span>
                            </label>
                            <select class="form-control" id="exam_body" name="exam_body" required>
                                <option value="">Select Curriculum</option>
                                <option value="MANEB">MANEB (Malawi National Curriculum)</option>
                                <option value="Cambridge">Cambridge (Cambridge International Curriculum)</option>
                                <option value="GCSE">GCSE (General Certificate of Secondary Education)</option>
                                <option value="IELTS">IELTS (International English Language Testing System)</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <!-- Subject -->
                        <div class="form-group">
                            <label for="subject" class="form-label">
                                <i class="fas fa-book me-1"></i>
                                Subject <span class="text-danger">*</span>
                            </label>
                            <select class="form-control" id="subject" name="subject" required>
                                <option value="">Select Curriculum First</option>
                            </select>
                        </div>

                        <!-- Topic (Optional) -->
                        <div class="form-group">
                            <label for="topic" class="form-label">
                                <i class="fas fa-tag me-1"></i>
                                Topic
                            </label>
                            <input type="text" class="form-control" id="topic" name="topic"
                                   placeholder="e.g., Linear Equations, Chemical Reactions">
                        </div>

                        <!-- Problem Year (Optional) -->
                        <div class="form-group">
                            <label for="problem_year" class="form-label">
                                <i class="fas fa-calendar me-1"></i>
                                Problem Year
                            </label>
                            <input type="number" class="form-control" id="problem_year" name="problem_year"
                                   min="2000" max="2030" placeholder="2023">
                        </div>

                        <!-- Video Preview -->
                        <div id="videoPreview" class="video-preview" style="display: none;">
                            <h6><i class="fas fa-eye me-1"></i>Video Preview</h6>
                            <div id="videoEmbed"></div>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary w-100" id="submitBtn">
                                <i class="fas fa-upload me-2"></i>
                                Upload Video Solution
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Card -->
            <div class="card">
                <div class="card-content">
                    <h6 class="mb-3">
                        <i class="fas fa-info-circle text-info me-1"></i>
                        Video Upload Guidelines
                    </h6>
                    <ul class="list-unstyled mb-0 small text-muted">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Supported platforms: YouTube, Vimeo
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Clear, educational content only
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Step-by-step problem solving
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Videos will be reviewed before publishing
                        </li>
                    </ul>
                </div>
            </div>
        </main>

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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Video URL validation and preview
        document.getElementById('video_url').addEventListener('input', function() {
            const url = this.value.trim();
            const previewDiv = document.getElementById('videoPreview');
            const embedDiv = document.getElementById('videoEmbed');

            if (url) {
                // Basic URL validation
                try {
                    const urlObj = new URL(url);
                    if (urlObj.hostname.includes('youtube.com') || urlObj.hostname.includes('youtu.be') ||
                        urlObj.hostname.includes('vimeo.com')) {

                        // Generate embed code (simplified preview)
                        let embedCode = '';
                        if (urlObj.hostname.includes('youtube.com') || urlObj.hostname.includes('youtu.be')) {
                            embedCode = '<div class="text-center text-success"><i class="fas fa-check-circle fa-2x"></i><br><small>YouTube video detected</small></div>';
                        } else if (urlObj.hostname.includes('vimeo.com')) {
                            embedCode = '<div class="text-center text-success"><i class="fas fa-check-circle fa-2x"></i><br><small>Vimeo video detected</small></div>';
                        }

                        embedDiv.innerHTML = embedCode;
                        previewDiv.style.display = 'block';
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    } else {
                        throw new Error('Invalid platform');
                    }
                } catch (e) {
                    previewDiv.style.display = 'none';
                    this.classList.remove('is-valid');
                    this.classList.add('is-invalid');
                }
            } else {
                previewDiv.style.display = 'none';
                this.classList.remove('is-valid', 'is-invalid');
            }
        });

        // Dynamic subject loading based on curriculum
        document.getElementById('exam_body').addEventListener('change', function() {
            const curriculum = this.value;
            const subjectSelect = document.getElementById('subject');

            subjectSelect.innerHTML = '<option value="">Loading...</option>';
            subjectSelect.disabled = true;

            if (!curriculum) {
                subjectSelect.innerHTML = '<option value="">Select Curriculum First</option>';
                return;
            }

            // Get subjects for this curriculum
            fetch('<?= site_url('admin/library/get-subjects') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: 'curriculum=' + encodeURIComponent(curriculum) + '&<?= csrf_token() ?>=' + encodeURIComponent('<?= csrf_hash() ?>')
            })
            .then(response => response.json())
            .then(data => {
                subjectSelect.innerHTML = '<option value="">Select Subject</option>';
                if (data.success && data.subjects) {
                    data.subjects.forEach(subject => {
                        const option = document.createElement('option');
                        option.value = subject;
                        option.textContent = subject;
                        subjectSelect.appendChild(option);
                    });
                }
                subjectSelect.disabled = false;
            })
            .catch(error => {
                console.error('Error loading subjects:', error);
                subjectSelect.innerHTML = '<option value="">Error loading subjects</option>';
            });
        });

        // Form submission
        document.getElementById('videoUploadForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.innerHTML;

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Uploading...';

            const formData = new FormData(this);

            fetch('<?= site_url('trainer/resources/process-video-solution-upload') ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message and redirect
                    alert('Video solution uploaded successfully! It will be reviewed by our team.');
                    window.location.href = data.redirect || '<?= site_url('trainer/resources/my-video-solutions') ?>';
                } else {
                    alert('Error: ' + (data.message || 'Failed to upload video solution'));
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while uploading the video solution.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    </script>
</body>
</html>
