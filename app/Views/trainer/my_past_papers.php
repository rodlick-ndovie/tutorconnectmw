<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#E55C0D">
    <title>My Past Papers - TutorConnect Malawi</title>
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

        .stats-section {
            margin-bottom: 2rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        .stats-card {
            background: var(--bg-secondary);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--shadow);
            border: 1px solid rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
        }

        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px -8px rgba(0, 0, 0, 0.15);
        }

        .stats-icon {
            width: 56px;
            height: 56px;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
            background: linear-gradient(135deg, rgba(229, 92, 13, 0.1), rgba(14, 165, 233, 0.1));
            color: var(--primary-color);
            position: relative;
        }

        .stats-icon::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            background: linear-gradient(135deg, rgba(229, 92, 13, 0.05), rgba(14, 165, 233, 0.05));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .stats-card:hover .stats-icon::after {
            opacity: 1;
        }

        .stats-value {
            font-size: 2rem;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            line-height: 1;
        }

        .stats-label {
            font-size: 0.9rem;
            color: var(--text-light);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .paper-card {
            background: var(--bg-secondary);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--shadow);
            border: 1px solid rgba(0,0,0,0.05);
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .paper-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px -8px rgba(0, 0, 0, 0.1);
        }

        .paper-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .paper-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .paper-info {
            flex: 1;
            margin-left: 1rem;
        }

        .paper-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
        }

        .paper-meta {
            font-size: 0.85rem;
            color: var(--text-light);
            margin-bottom: 0.25rem;
        }

        .paper-details {
            font-size: 0.8rem;
            color: var(--text-light);
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
        }

        .paper-tag {
            background: var(--bg-primary);
            color: var(--text-light);
            padding: 0.25rem 0.5rem;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .paper-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-action {
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-weight: 500;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
        }

        .btn-action:hover {
            background: var(--secondary-color);
            transform: translateY(-1px);
        }

        .btn-outline {
            background: transparent;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
        }

        .btn-outline:hover {
            background: var(--primary-color);
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
        }

        .empty-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(229, 92, 13, 0.1), rgba(14, 165, 233, 0.1));
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: var(--primary-color);
        }

        .empty-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .empty-subtitle {
            font-size: 1rem;
            color: var(--text-light);
            margin-bottom: 2rem;
            line-height: 1.5;
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

        @media (max-width: 480px) {
            .paper-actions {
                flex-direction: column;
            }

            .btn-action {
                width: 100%;
                justify-content: center;
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
                    <a href="<?= site_url('trainer/dashboard') ?>" class="nav-button">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1 class="nav-title mb-0 ms-2">My Papers</h1>
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
                <h1 class="page-title">My Past Papers</h1>
                <p class="page-subtitle">Manage your uploaded educational resources</p>
            </div>

            <!-- Stats Section -->
            <div class="stats-section">
                <div class="stats-grid">
                    <div class="stats-card">
                        <div class="stats-icon">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <div class="stats-value"><?= number_format($total_papers) ?></div>
                        <div class="stats-label">Papers Uploaded</div>
                    </div>
                    <div class="stats-card">
                        <div class="stats-icon">
                            <i class="fas fa-download"></i>
                        </div>
                        <div class="stats-value"><?= number_format($total_downloads) ?></div>
                        <div class="stats-label">Total Downloads</div>
                    </div>
                </div>
            </div>

            <!-- Upload Action Card -->
            <div class="card">
                <div class="card-content">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1" style="color: var(--text-dark);">
                                <i class="fas fa-file-pdf me-2" style="color: var(--primary-color);"></i>
                                Want to Upload More Papers?
                            </h4>
                            <p class="mb-0" style="color: var(--text-light);">
                                Share additional educational resources with students
                            </p>
                        </div>
                        <a href="<?= site_url('trainer/resources/upload') ?>" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Upload Paper
                        </a>
                    </div>
                </div>
            </div>

            <!-- Papers List -->
            <?php if (!empty($papers)): ?>
                <div class="papers-list">
                    <?php foreach ($papers as $paper): ?>
                        <div class="paper-card">
                            <div class="paper-header">
                                <div class="paper-icon">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <div class="paper-info">
                                    <div class="paper-title">
                                        <?= esc($paper['paper_title'] ?: 'Untitled Paper') ?>
                                    </div>
                                    <div class="paper-meta">
                                        <?= esc($paper['exam_body']) ?> • <?= esc($paper['exam_level']) ?> • <?= esc($paper['subject']) ?> • <?= esc($paper['year']) ?>
                                    </div>
                                    <div class="paper-details">
                                        <span class="paper-tag">
                                            <i class="fas fa-file"></i>
                                            <?= esc($paper['file_size']) ?>
                                        </span>
                                        <span class="paper-tag">
                                            <i class="fas fa-download"></i>
                                            <?= number_format($paper['download_count'] ?? 0) ?> downloads
                                        </span>
                                        <span class="paper-tag">
                                            <i class="fas fa-calendar"></i>
                                            <?= date('M j, Y', strtotime($paper['created_at'])) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <?php if (!empty($paper['copyright_notice'])): ?>
                                <div class="mb-3">
                                    <small class="text-muted">
                                        <i class="fas fa-copyright me-1"></i>
                                        <?= esc($paper['copyright_notice']) ?>
                                    </small>
                                </div>
                            <?php endif; ?>

                            <div class="paper-actions">
                                <a href="<?= site_url('resources/past-papers/download/' . $paper['id']) ?>"
                                   class="btn-action" target="_blank">
                                    <i class="fas fa-download"></i>
                                    Download
                                </a>
                                <a href="<?= site_url('resources') ?>"
                                   class="btn-action btn-outline">
                                    <i class="fas fa-eye"></i>
                                    View Public
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <!-- Empty State -->
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <h3 class="empty-title">No Past Papers Yet</h3>
                    <p class="empty-subtitle">
                        You haven't uploaded any past papers yet. Start sharing educational resources with students.
                    </p>
                    <a href="<?= site_url('trainer/resources/upload') ?>" class="btn-action">
                        <i class="fas fa-plus me-2"></i>
                        Upload Your First Paper
                    </a>
                </div>
            <?php endif; ?>
        </main>
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
</body>
</html>
