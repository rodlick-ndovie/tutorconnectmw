<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#E55C0D">
    <title>Create School Announcement - TutorConnect Malawi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #E55C0D;
            --primary-light: #10b981;
            --primary-dark: #C94609;
            --secondary-color: #C94609;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --accent-color: #0ea5e9;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --text-secondary: #64748b;
            --bg-primary: #f8fafc;
            --bg-secondary: #ffffff;
            --bg-accent: #f0f9ff;
            --border-color: #e2e8f0;
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --border-radius: 16px;
            --border-radius-lg: 20px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--bg-primary);
            color: var(--text-dark);
            overflow-x: hidden;
        }

        .app-container {
            max-width: 480px;
            margin: 0 auto;
            background: var(--bg-secondary);
            min-height: 100vh;
            position: relative;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
        }

        @media (min-width: 768px) {
            .app-container {
                max-width: 100%;
                box-shadow: none;
            }
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
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 100;
            padding: 0;
            height: 64px;
        }

        .navbar .px-4 {
            width: 100%;
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

        .screen {
            padding: 20px;
            padding-bottom: 100px;
            background: var(--bg-primary);
            min-height: calc(100vh - 130px);
            max-width: 1200px;
            margin: 0 auto;
        }

        @media (min-width: 768px) {
            .screen {
                padding: 32px 40px 120px 40px;
            }
        }

        @media (min-width: 1024px) {
            .screen {
                max-width: 1400px;
                padding: 40px 60px 120px 60px;
            }
        }

        .page-header {
            background: var(--bg-secondary);
            border-radius: var(--border-radius);
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
            text-align: center;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: var(--text-secondary);
            font-size: 1.1rem;
        }

        .form-card {
            background: var(--bg-secondary);
            border-radius: var(--border-radius);
            padding: 2rem;
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
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

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        .form-control::placeholder {
            color: var(--text-light);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 120px;
        }

        .form-text {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin-top: 0.25rem;
        }

        .radio-group {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .radio-option {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: var(--bg-primary);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .radio-option:hover {
            border-color: var(--primary-color);
            background: rgba(16, 185, 129, 0.05);
        }

        .radio-option.selected {
            border-color: var(--primary-color);
            background: rgba(16, 185, 129, 0.1);
            color: var(--primary-color);
        }

        .radio-option input[type="radio"] {
            display: none;
        }

        .file-upload {
            border: 2px dashed var(--border-color);
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .file-upload:hover {
            border-color: var(--primary-color);
            background: rgba(16, 185, 129, 0.05);
        }

        .file-upload.dragover {
            border-color: var(--primary-color);
            background: rgba(16, 185, 129, 0.1);
        }

        .file-upload-icon {
            font-size: 2rem;
            color: var(--text-secondary);
            margin-bottom: 1rem;
        }

        .file-upload-text {
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
        }

        .file-upload-hint {
            font-size: 0.875rem;
            color: var(--text-light);
        }

        .file-preview {
            display: none;
            margin-top: 1rem;
            padding: 1rem;
            background: var(--bg-primary);
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        .file-preview img {
            max-width: 100%;
            max-height: 200px;
            border-radius: 4px;
        }

        .btn {
            padding: 0.875rem 2rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
            font-size: 1rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-secondary {
            background: var(--bg-secondary);
            color: var(--text-dark);
            border: 1px solid var(--border-color);
        }

        .btn-secondary:hover {
            background: var(--text-light);
            color: white;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        .error-message {
            color: var(--danger-color);
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .premium-badge {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            .page-header {
                padding: 1.5rem;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .form-card {
                padding: 1.5rem;
            }

            .radio-group {
                flex-direction: column;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
<div class="app-container">
    <div class="status-bar"></div>
    <div class="navbar">
        <div class="d-flex align-items-center justify-content-between w-100 px-3">
            <div class="d-flex align-items-center">
                <h1 class="nav-title mb-0 me-3">Create Announcement</h1>
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
    </div>

    <div class="main-content">
        <div class="page-header">
            <h1 class="page-title">Create School Announcement</h1>
            <p class="page-subtitle">Share important notices with students and parents</p>
            <div class="premium-badge">
                <i class="fas fa-crown"></i> Premium Feature
            </div>
        </div>

        <!-- Form -->
        <div class="form-card">
            <?php if (session()->has('errors')): ?>
                <div class="alert alert-danger">
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        <?php foreach (session('errors') as $error): ?>
                            <li><?php echo esc($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (session()->has('error')): ?>
                <div class="alert alert-danger">
                    <?php echo session('error'); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo base_url('trainer/notices/store'); ?>">
                <?php echo csrf_field(); ?>

                <!-- School Information -->
                <div class="form-group">
                    <label class="form-label">School Information</label>
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="school_name" placeholder="School Name" required>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="school_type" placeholder="School Type (e.g., Secondary School)" required>
                        </div>
                    </div>
                </div>

                <!-- Notice Type -->
                <div class="form-group">
                    <label class="form-label">Notice Type</label>
                    <div class="radio-group">
                        <label class="radio-option selected" for="type-announcement">
                            <input type="radio" name="notice_type" value="Announcement" id="type-announcement" checked>
                            <i class="fas fa-bullhorn"></i>
                            <span>Announcement</span>
                        </label>
                        <label class="radio-option" for="type-notice">
                            <input type="radio" name="notice_type" value="Notice" id="type-notice">
                            <i class="fas fa-info-circle"></i>
                            <span>Notice</span>
                        </label>
                        <label class="radio-option" for="type-vacancy">
                            <input type="radio" name="notice_type" value="Vacancy" id="type-vacancy">
                            <i class="fas fa-user-plus"></i>
                            <span>Vacancy</span>
                        </label>
                    </div>
                </div>

                <!-- Notice Title -->
                <div class="form-group">
                    <label class="form-label" for="notice_title">Notice Title</label>
                    <input type="text" class="form-control" id="notice_title" name="notice_title" placeholder="Enter a clear, descriptive title" required>
                    <div class="form-text">Keep it concise and informative</div>
                </div>

                <!-- Notice Content -->
                <div class="form-group">
                    <label class="form-label" for="notice_content">Notice Content</label>
                    <textarea class="form-control" id="notice_content" name="notice_content" placeholder="Provide detailed information about the notice..." rows="6" required></textarea>
                    <div class="form-text">Include all relevant details, dates, requirements, and contact information</div>
                </div>



                <!-- Action Buttons -->
                <div class="action-buttons">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Publish Announcement
                    </button>
                    <a href="<?php echo base_url('trainer/notices'); ?>" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="bottom-nav">
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
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Radio button selection
        document.querySelectorAll('.radio-option').forEach(option => {
            option.addEventListener('click', function() {
                // Remove selected class from all options
                document.querySelectorAll('.radio-option').forEach(opt => {
                    opt.classList.remove('selected');
                });

                // Add selected class to clicked option
                this.classList.add('selected');

                // Check the radio button
                this.querySelector('input[type="radio"]').checked = true;
            });
        });


    </script>
</body>
</html>
