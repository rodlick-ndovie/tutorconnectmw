<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#E55C0D">
    <title>My Video Solutions - TutorConnect Malawi</title>
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

        .stats-card {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(239, 68, 68, 0.05));
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 1rem;
            border: 1px solid rgba(239, 68, 68, 0.1);
        }

        .stats-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: rgba(239, 68, 68, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--danger-color);
            margin: 0 auto 1rem;
        }

        .stats-value {
            font-size: 2rem;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            line-height: 1;
            text-align: center;
        }

        .stats-label {
            font-size: 0.9rem;
            color: var(--text-light);
            font-weight: 600;
            text-align: center;
        }

        .video-card {
            background: var(--bg-secondary);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            margin-bottom: 16px;
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }

        .video-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .video-header {
            padding: 16px;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .video-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
            line-height: 1.4;
        }

        .video-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 8px;
        }

        .meta-item {
            font-size: 12px;
            color: var(--text-light);
            background: rgba(0,0,0,0.05);
            padding: 4px 8px;
            border-radius: 12px;
        }

        .video-status {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 12px;
        }

        .status-approved { background: rgba(16, 185, 129, 0.1); color: var(--success-color); }
        .status-pending_review { background: rgba(245, 158, 11, 0.1); color: var(--warning-color); }
        .status-rejected { background: rgba(239, 68, 68, 0.1); color: var(--danger-color); }

        .video-stats {
            display: flex;
            gap: 16px;
            margin-top: 12px;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            color: var(--text-light);
        }

        .video-actions {
            padding: 12px 16px;
            border-top: 1px solid rgba(0,0,0,0.05);
            display: flex;
            gap: 8px;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
            border-radius: 6px;
        }

        .btn-outline-primary {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
        }

        .empty-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(239, 68, 68, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--danger-color);
            margin: 0 auto 1.5rem;
            font-size: 2rem;
        }

        .empty-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .empty-text {
            color: var(--text-light);
            margin-bottom: 2rem;
            line-height: 1.5;
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

        .fab {
            position: fixed;
            bottom: 90px;
            right: 20px;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: var(--danger-color);
            color: white;
            border: none;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            z-index: 101;
            transition: all 0.3s ease;
        }

        .fab:hover {
            background: #dc2626;
            transform: scale(1.1);
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Navigation Bar -->
        <nav class="navbar">
            <div class="d-flex align-items-center justify-content-between w-100 px-3">
                <h1 class="nav-title mb-0">My Video Solutions</h1>
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
            <!-- Success/Error Messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success" style="border-radius: 8px; border: none;">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= esc(session()->getFlashdata('success')) ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger" style="border-radius: 8px; border: none;">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?= esc(session()->getFlashdata('error')) ?>
                </div>
            <?php endif; ?>

            <!-- Stats Card -->
            <div class="stats-card">
                <div class="stats-icon">
                    <i class="fas fa-video"></i>
                </div>
                <div class="stats-value"><?= $total_videos ?? 0 ?></div>
                <div class="stats-label">Total Video Solutions</div>
                <div class="row mt-3 g-2">
                    <div class="col-4 text-center">
                        <div style="font-size: 1.1rem; font-weight: 700; color: var(--success-color);">
                            <?= $approved_videos ?? 0 ?>
                        </div>
                        <div style="font-size: 0.75rem; color: var(--text-light);">Approved</div>
                    </div>
                    <div class="col-4 text-center">
                        <div style="font-size: 1.1rem; font-weight: 700; color: var(--warning-color);">
                            <?= $pending_videos ?? 0 ?>
                        </div>
                        <div style="font-size: 0.75rem; color: var(--text-light);">Pending</div>
                    </div>
                    <div class="col-4 text-center">
                        <div style="font-size: 1.1rem; font-weight: 700; color: var(--text-light);">
                            <?= $total_views ?? 0 ?>
                        </div>
                        <div style="font-size: 0.75rem; color: var(--text-light);">Views</div>
                    </div>
                </div>
            </div>

            <!-- Video Solutions List -->
            <?php if (!empty($videos)): ?>
                <?php foreach ($videos as $video): ?>
                    <div class="video-card">
                        <div class="video-header">
                            <h3 class="video-title">
                                <i class="fas fa-play-circle text-danger me-2"></i>
                                <?= esc($video['title']) ?>
                            </h3>

                            <div class="video-meta">
                                <span class="meta-item">
                                    <i class="fas fa-graduation-cap me-1"></i>
                                    <?= esc($video['exam_body']) ?>
                                </span>
                                <span class="meta-item">
                                    <i class="fas fa-book me-1"></i>
                                    <?= esc($video['subject']) ?>
                                </span>
                                <?php if (!empty($video['topic'])): ?>
                                    <span class="meta-item">
                                        <i class="fas fa-tag me-1"></i>
                                        <?= esc($video['topic']) ?>
                                    </span>
                                <?php endif; ?>
                                <?php if (!empty($video['problem_year'])): ?>
                                    <span class="meta-item">
                                        <i class="fas fa-calendar me-1"></i>
                                        <?= esc($video['problem_year']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <span class="video-status status-<?= esc($video['status']) ?>">
                                    <i class="fas fa-<?= $video['status'] === 'approved' ? 'check-circle' : ($video['status'] === 'pending_review' ? 'clock' : 'times-circle') ?> me-1"></i>
                                    <?php
                                    switch($video['status']) {
                                        case 'approved': echo 'Approved'; break;
                                        case 'pending_review': echo 'Under Review'; break;
                                        case 'rejected': echo 'Rejected'; break;
                                        default: echo 'Unknown';
                                    }
                                    ?>
                                </span>

                                <small class="text-muted">
                                    <?= date('M j, Y', strtotime($video['created_at'])) ?>
                                </small>
                            </div>

                            <?php if (!empty($video['description'])): ?>
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <?= esc(substr($video['description'], 0, 100)) ?>
                                        <?= strlen($video['description']) > 100 ? '...' : '' ?>
                                    </small>
                                </div>
                            <?php endif; ?>

                            <div class="video-stats">
                                <div class="stat-item">
                                    <i class="fas fa-eye"></i>
                                    <span><?= number_format($video['view_count'] ?? 0) ?> views</span>
                                </div>
                            </div>
                        </div>

                        <div class="video-actions">
                            <?php if ($video['status'] === 'approved' && !empty($video['video_embed_code'])): ?>
                                <button class="btn btn-sm btn-outline-primary" onclick="previewVideo('<?= esc($video['video_embed_code']) ?>', '<?= esc($video['title']) ?>')">
                                    <i class="fas fa-play me-1"></i>Preview
                                </button>
                            <?php endif; ?>

                            <?php if ($video['status'] === 'pending_review'): ?>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Awaiting admin review
                                </small>
                            <?php elseif ($video['status'] === 'rejected'): ?>
                                <small class="text-danger">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    Video was not approved
                                </small>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Empty State -->
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-video"></i>
                    </div>
                    <h3 class="empty-title">No Video Solutions Yet</h3>
                    <p class="empty-text">
                        You haven't uploaded any video solutions yet. Share your knowledge by uploading educational video content!
                    </p>
                    <a href="<?= site_url('trainer/resources/upload-video-solutions') ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Upload Your First Video
                    </a>
                </div>
            <?php endif; ?>
        </main>

        <!-- Upload Modal -->
        <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content" style="border-radius: var(--border-radius);">
                    <div class="modal-header" style="border-bottom: 1px solid rgba(0,0,0,0.05);">
                        <h5 class="modal-title" id="uploadModalLabel">
                            <i class="fas fa-video text-danger me-2"></i>
                            Upload New Video Solution
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="videoUploadForm" enctype="multipart/form-data">
                            <?= csrf_field() ?>

                            <!-- Video URL -->
                            <div class="form-group mb-3">
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
                            <div class="form-group mb-3">
                                <label for="title" class="form-label">
                                    <i class="fas fa-heading me-1"></i>
                                    Video Title <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="title" name="title"
                                       placeholder="e.g., Solving Quadratic Equations Step by Step" required>
                            </div>

                            <!-- Description -->
                            <div class="form-group mb-3">
                                <label for="description" class="form-label">
                                    <i class="fas fa-align-left me-1"></i>
                                    Description
                                </label>
                                <textarea class="form-control" id="description" name="description" rows="3"
                                          placeholder="Brief description of what this video covers..."></textarea>
                            </div>

                            <!-- Curriculum -->
                            <div class="form-group mb-3">
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
                            <div class="form-group mb-3">
                                <label for="subject" class="form-label">
                                    <i class="fas fa-book me-1"></i>
                                    Subject <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" id="subject" name="subject" required>
                                    <option value="">Select Curriculum First</option>
                                </select>
                            </div>

                            <!-- Topic (Optional) -->
                            <div class="form-group mb-3">
                                <label for="topic" class="form-label">
                                    <i class="fas fa-tag me-1"></i>
                                    Topic
                                </label>
                                <input type="text" class="form-control" id="topic" name="topic"
                                       placeholder="e.g., Linear Equations, Chemical Reactions">
                            </div>

                            <!-- Problem Year (Optional) -->
                            <div class="form-group mb-3">
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
            </div>
        </div>

        <!-- Floating Action Button -->
        <button class="fab" onclick="openUploadModal()" title="Upload Video Solution">
            <i class="fas fa-plus"></i>
        </button>

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

    <!-- Video Preview Modal -->
    <div class="modal fade" id="videoPreviewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="videoPreviewTitle">Video Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="videoPreviewContent"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function previewVideo(embedCode, title) {
            document.getElementById('videoPreviewTitle').textContent = title;
            document.getElementById('videoPreviewContent').innerHTML = embedCode;

            const modal = new bootstrap.Modal(document.getElementById('videoPreviewModal'));
            modal.show();
        }

        function openUploadModal() {
            const modal = new bootstrap.Modal(document.getElementById('uploadModal'));
            modal.show();
        }

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
                    // Close modal and reload page
                    const modal = bootstrap.Modal.getInstance(document.getElementById('uploadModal'));
                    modal.hide();

                    alert('Video solution uploaded successfully! It will be reviewed by our team.');
                    window.location.reload();
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
