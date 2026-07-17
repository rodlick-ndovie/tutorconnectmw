<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#E55C0D">
    <title>School Announcements - TutorConnect Malawi</title>
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
            margin-bottom: 24px;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            font-size: 0.95rem;
            color: var(--text-secondary);
        }

        .stats-card {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
        }

        .stats-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .stats-value {
            font-size: 2rem;
            font-weight: 700;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .btn {
            padding: 0.875rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
            font-size: 0.95rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(229, 92, 13, 0.3);
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

        .notice-card {
            background: var(--bg-secondary);
            border-radius: var(--border-radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
        }

        .notice-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            border-color: rgba(229, 92, 13, 0.2);
        }

        .notice-header {
            background: linear-gradient(135deg, var(--bg-primary), rgba(229, 92, 13, 0.02));
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        .notice-title-section {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .notice-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--border-radius);
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
            margin-right: 1rem;
            flex-shrink: 0;
        }

        .notice-title-info {
            flex: 1;
        }

        .notice-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            line-height: 1.3;
        }

        .notice-type-badge {
            display: inline-block;
            padding: 0.375rem 0.875rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .type-announcement {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
        }

        .type-notice {
            background: linear-gradient(135deg, var(--accent-color), #0284c7);
            color: white;
        }

        .type-vacancy {
            background: linear-gradient(135deg, var(--success-color), #059669);
            color: white;
        }

        .notice-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        .meta-item i {
            color: var(--primary-color);
            font-size: 0.875rem;
        }

        .notice-content-section {
            padding: 1.5rem;
        }

        .notice-content {
            color: var(--text-light);
            line-height: 1.6;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }

        .notice-stats {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.5rem;
            background: var(--bg-primary);
            border-top: 1px solid var(--border-color);
        }

        .stats-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .stat-item i {
            color: var(--accent-color);
        }

        .notice-actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .btn {
            padding: 0.625rem 1.25rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
            font-size: 0.875rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(229, 92, 13, 0.3);
        }

        .btn-secondary {
            background: var(--bg-secondary);
            color: var(--text-dark);
            border: 1px solid var(--border-color);
        }

        .btn-secondary:hover {
            background: var(--text-light);
            color: white;
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

        .btn-danger {
            background: linear-gradient(135deg, var(--danger-color), #dc2626);
            color: white;
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--text-secondary);
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .screen {
                padding: 15px;
            }

            .page-header {
                padding: 1.5rem;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .notice-header {
                flex-direction: column;
                gap: 1rem;
            }

            .notice-actions {
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
                <h1 class="nav-title mb-0 me-3">Announcements</h1>
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
            <h1 class="page-title">School Announcements</h1>
            <p class="page-subtitle">Create and manage your school notices and announcements</p>
        </div>

        <!-- Stats -->
        <div class="stats-card">
            <div class="stats-title">Your Announcements</div>
            <div class="stats-value"><?php echo count($notices); ?></div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="<?php echo base_url('trainer/notices/create'); ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create New Announcement
            </a>
            <a href="<?php echo base_url('trainer/dashboard'); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <!-- Notices List -->
        <?php if (!empty($notices)): ?>
            <?php foreach ($notices as $notice): ?>
                <div class="notice-card">
                    <div class="notice-header">
                        <div>
                            <h3 class="notice-title"><?php echo htmlspecialchars($notice['notice_title']); ?></h3>
                            <div class="notice-meta">
                                <span class="status-badge status-<?php echo $notice['status']; ?>">
                                    <?php echo ucfirst($notice['status']); ?>
                                </span>
                                <span>•</span>
                                <span><?php echo htmlspecialchars($notice['notice_type']); ?></span>
                                <span>•</span>
                                <span><?php echo htmlspecialchars($notice['school_name']); ?></span>
                                <span>•</span>
                                <span><?php echo date('M j, Y', strtotime($notice['created_at'])); ?></span>
                            </div>
                        </div>
                        <div class="notice-actions">
                            <a href="<?php echo base_url('notice/view/' . $notice['id']); ?>" class="btn btn-secondary btn-sm" target="_blank">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a href="<?php echo base_url('trainer/notices/edit/' . $notice['id']); ?>" class="btn btn-secondary btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form method="POST" action="<?php echo base_url('trainer/notices/delete/' . $notice['id']); ?>" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this announcement?')">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="notice-content">
                        <?php echo nl2br(htmlspecialchars(substr($notice['notice_content'], 0, 200))); ?>
                        <?php if (strlen($notice['notice_content']) > 200): ?>
                            ...
                        <?php endif; ?>
                    </div>

                    <?php if ($notice['views_count'] > 0): ?>
                        <div class="notice-meta">
                            <i class="fas fa-eye"></i> <?php echo $notice['views_count']; ?> views
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-bullhorn"></i>
                <h3>No Announcements Yet</h3>
                <p>You haven't created any school announcements yet. Create your first announcement to reach potential students and parents.</p>
                <a href="<?php echo base_url('trainer/notices/create'); ?>" class="btn btn-primary" style="margin-top: 1rem;">
                    <i class="fas fa-plus"></i> Create Your First Announcement
                </a>
            </div>
        <?php endif; ?>
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
</body>
</html>
