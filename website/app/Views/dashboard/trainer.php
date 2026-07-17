<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Trainer Dashboard - TutorConnect Malawi'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #059669;
            --secondary-color: #047857;
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
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --border-radius: 16px;
            --border-radius-lg: 20px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            background-color: var(--bg-primary);
            font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'SF Pro Text', 'Segoe UI', Roboto, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
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
            .app-container {
                max-width: 100%;
                box-shadow: none;
            }
        }

        .status-bar {
            height: 44px;
            background: var(--bg-secondary);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        @media (min-width: 768px) {
            .status-bar {
                display: none;
            }
        }

        .navbar {
            background: var(--bg-secondary);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            padding: 0;
            height: 64px;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar .px-4 {
            width: 100%;
        }

        .navbar .d-flex.gap-2 {
            margin-left: auto !important;
            flex-shrink: 0;
        }

        .navbar button {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            transition: background 0.3s;
            flex-shrink: 0;
        }

        .navbar button:hover {
            background: var(--bg-primary);
        }

        @media (min-width: 768px) {
            .navbar {
                height: 80px;
            }
        }

        .screen {
            padding: 20px;
            padding-bottom: 100px; /* Space for bottom nav */
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

        .welcome-header {
            text-align: center;
            margin-bottom: 24px;
        }

        @media (min-width: 768px) {
            .welcome-header {
                text-align: left;
                margin-bottom: 32px;
            }
        }

        .welcome-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 4px;
        }

        @media (min-width: 768px) {
            .welcome-title {
                font-size: 36px;
                margin-bottom: 8px;
            }
        }

        .welcome-subtitle {
            font-size: 16px;
            color: var(--text-light);
        }

        @media (min-width: 768px) {
            .welcome-subtitle {
                font-size: 18px;
            }
        }

        .status-banner {
            display: flex;
            gap: 16px;
            align-items: center;
            background: var(--bg-secondary);
            border: 1px solid rgba(0,0,0,0.05);
            border-left: 6px solid var(--primary-color);
            box-shadow: var(--shadow);
            border-radius: var(--border-radius);
            padding: 18px 20px;
            margin-bottom: 20px;
        }

        .status-banner .status-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            font-size: 20px;
            color: white;
            background: linear-gradient(135deg, #10b981, #059669);
            flex-shrink: 0;
        }

        .status-banner .status-title {
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 4px;
        }

        .status-banner .status-text {
            color: var(--text-light);
            margin: 0;
            font-size: 14px;
        }

        .status-banner .status-pill {
            padding: 6px 12px;
            border-radius: 999px;
            font-weight: 600;
            font-size: 12px;
            text-transform: capitalize;
            margin-left: auto;
        }

        .status-banner.success { border-left-color: var(--success-color); }
        .status-banner.success .status-icon { background: linear-gradient(135deg, #22c55e, #16a34a); }
        .status-banner.success .status-pill { background: rgba(34,197,94,0.1); color: #15803d; }

        .status-banner.warning { border-left-color: var(--warning-color); }
        .status-banner.warning .status-icon { background: linear-gradient(135deg, #fbbf24, #f59e0b); }
        .status-banner.warning .status-pill { background: rgba(245,158,11,0.12); color: #b45309; }

        .status-banner.danger { border-left-color: var(--danger-color); }
        .status-banner.danger .status-icon { background: linear-gradient(135deg, #f87171, #ef4444); }
        .status-banner.danger .status-pill { background: rgba(239,68,68,0.12); color: #b91c1c; }

        .status-banner .status-action {
            margin-top: 10px;
            font-weight: 600;
            color: var(--primary-color);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 14px;
        }

        .action-cards-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
            margin-bottom: 24px;
        }

        @media (min-width: 768px) {
            .action-cards-grid {
                grid-template-columns: 1fr 1fr;
                gap: 24px;
                margin-bottom: 32px;
            }
        }

        .action-card {
            background: var(--bg-secondary);
            border-radius: var(--border-radius);
            padding: 24px;
            box-shadow: var(--shadow);
            border: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            gap: 20px;
            align-items: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .action-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .card-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            flex-shrink: 0;
        }

        .card-content {
            flex: 1;
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-dark);
            margin: 0 0 4px 0;
        }

        .card-description {
            font-size: 14px;
            color: var(--text-light);
            margin: 0 0 12px 0;
        }

        .card-action {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 600;
            color: var(--primary-color);
        }

        .card-action i {
            font-size: 12px;
        }

        .plan-card {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: var(--border-radius);
            padding: 20px;
            margin-bottom: 20px;
            color: white;
            box-shadow: var(--shadow);
        }

        .plan-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 12px;
        }

        .plan-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .plan-info {
            flex: 1;
        }

        .plan-name {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .plan-status {
            font-size: 14px;
            opacity: 0.9;
        }

        .plan-description {
            font-size: 14px;
            opacity: 0.8;
            line-height: 1.4;
        }

        .activity-list {
            margin-bottom: 24px;
        }

        .activity-item {
            background: var(--bg-secondary);
            border-radius: var(--border-radius);
            padding: 16px;
            box-shadow: var(--shadow);
            border: 1px solid rgba(0, 0, 0, 0.05);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
        }

        @media (min-width: 768px) {
            .activity-item {
                padding: 20px;
                margin-bottom: 16px;
            }
        }

        .activity-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
            margin-right: 12px;
            flex-shrink: 0;
        }

        @media (min-width: 768px) {
            .activity-avatar {
                width: 48px;
                height: 48px;
                font-size: 16px;
            }
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
            padding: 10px 14px calc(16px + env(safe-area-inset-bottom, 0px));
            z-index: 100;
        }

        @media (min-width: 768px) {
            .bottom-nav {
                gap: 12px;
            }
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

        .nav-icon {
            font-size: 22px;
            margin-bottom: 4px;
        }

        .navbar {
            background: var(--bg-secondary);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            padding: 0;
            height: 64px;
        }

        .sidebar {
            background: white;
            min-height: calc(100vh - 76px);
            box-shadow: var(--shadow);
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 16px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow);
            border: none;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0;
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--text-light);
            margin: 0;
        }

        .course-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .course-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .course-image {
            height: 150px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
        }

        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 8px;
            padding: 8px 24px;
        }

        .btn-primary:hover {
            background: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .quick-action-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow);
            border-left: 5px solid var(--primary-color);
            transition: all 0.3s ease;
        }

        .quick-action-card:hover {
            transform: translateX(4px);
        }

        .action-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        .screen {
            padding: 20px;
            padding-bottom: calc(120px + env(safe-area-inset-bottom, 0px)); /* Space for bottom nav + safe area */
        }

        .welcome-header {
            text-align: center;
            margin-bottom: 24px;
        }

        .welcome-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 4px;
        }

        .welcome-subtitle {
            font-size: 16px;
            color: var(--text-light);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--bg-secondary);
            border-radius: var(--border-radius);
            padding: 20px;
            box-shadow: var(--shadow);
            border: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            font-size: 20px;
        }

        .stat-number {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 12px;
            color: var(--text-light);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .section-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 16px;
        }

        .quick-actions {
            margin-bottom: 24px;
        }

        .quick-action-card {
            background: transparent;
            border-radius: var(--border-radius);
            padding: 16px;
            box-shadow: none;
            border: 1px solid transparent;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
        }

        .action-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            margin-right: 12px;
            flex-shrink: 0;
        }

        .action-content h6 {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 2px;
        }

        .action-content p {
            font-size: 12px;
            color: var(--text-light);
            margin: 0;
        }

        .activity-list {
            margin-bottom: 24px;
        }

        .activity-item {
            background: var(--bg-secondary);
            border-radius: var(--border-radius);
            padding: 16px;
            box-shadow: var(--shadow);
            border: 1px solid rgba(0, 0, 0, 0.05);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
        }

        .activity-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
            margin-right: 12px;
            flex-shrink: 0;
        }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-size: 14px;
            font-weight: 500;
            color: var(--text-dark);
            margin-bottom: 2px;
            line-height: 1.4;
        }

        .activity-time {
            font-size: 12px;
            color: var(--text-light);
        }

        .activity-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-completed {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }

        .badge-submitted {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
        }

        .badge-enrolled {
            background: rgba(59, 130, 246, 0.1);
            color: var(--accent-color);
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: var(--bg-secondary);
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            padding: 8px 0;
            z-index: 100;
        }

        .nav-container {
            max-width: 414px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .nav-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 8px;
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 8px 4px;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .nav-item:hover {
            background: var(--bg-accent);
        }

        .nav-item.active {
            background: rgba(5, 150, 105, 0.1);
        }

        .nav-icon {
            width: 24px;
            height: 24px;
            border-radius: 6px;
            background: var(--text-light);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            margin-bottom: 4px;
        }

        .nav-item.active .nav-icon {
            background: var(--primary-color);
        }

        .nav-label {
            font-size: 11px;
            color: var(--text-light);
            font-weight: 600;
            text-align: center;
            white-space: nowrap;
        }

        .nav-item.active .nav-label {
            color: var(--primary-color);
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Mobile Status Bar Simulation -->
        <div class="status-bar"></div>

        <!-- Header -->
        <header class="navbar">
            <div class="px-4 py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="avatar me-3">
                        <?php echo strtoupper(substr($user['username'] ?? 'T', 0, 1)); ?>
                    </div>
                    <div>
                        <div class="fw-semibold"><?php echo $user['first_name'] . ' ' . $user['last_name'] ?? $user['username']; ?></div>
                        <small class="text-muted">Trainer</small>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn p-0 border-0 bg-transparent">
                        <i class="fas fa-bell text-muted" style="font-size: 20px;"></i>
                    </button>
                    <button class="btn p-0 border-0 bg-transparent" onclick="window.location.href='<?php echo base_url('logout'); ?>'">
                        <i class="fas fa-sign-out-alt text-muted" style="font-size: 20px;"></i>
                    </button>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="screen">
            <!-- Welcome Header -->
            <div class="welcome-header">
                <h1 class="welcome-title">Good morning! 👋</h1>
                <p class="welcome-subtitle">Ready to educate and inspire today?</p>
            </div>

            <!-- Current Plan Display -->
            <?php if (isset($plan) && $plan): ?>
                <div class="plan-card">
                    <div class="plan-header">
                        <div class="plan-icon">
                            <i class="fas fa-crown"></i>
                        </div>
                        <div class="plan-info">
                            <div class="plan-name"><?php echo esc($plan['name'] ?? 'Current Plan'); ?></div>
                            <div class="plan-status">Active Subscription</div>
                        </div>
                    </div>
                    <?php if (isset($plan['description']) && $plan['description']): ?>
                        <div class="plan-description">
                            <?php echo esc($plan['description']); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php
                $statusVariant = $profile_status['variant'] ?? 'warning';
                $statusIcon = [
                    'success' => 'fa-check-circle',
                    'danger' => 'fa-times-circle',
                    'warning' => 'fa-clock'
                ][$statusVariant] ?? 'fa-info-circle';
            ?>

            <?php if (!empty($profile_status)): ?>
                <div class="status-banner <?php echo esc($statusVariant); ?>">
                    <div class="status-icon">
                        <i class="fas <?php echo esc($statusIcon); ?>"></i>
                    </div>
                    <div>
                        <div class="status-title"><?php echo esc($profile_status['title'] ?? 'Profile status'); ?></div>
                        <p class="status-text"><?php echo esc($profile_status['message'] ?? ''); ?></p>

                        <?php if (($profile_status['state'] ?? '') === 'incomplete'): ?>
                            <a class="status-action" href="<?php echo base_url('dashboard/complete-profile'); ?>">
                                Complete your profile <i class="fas fa-arrow-right"></i>
                            </a>
                        <?php elseif (($profile_status['state'] ?? '') === 'rejected'): ?>
                            <a class="status-action" href="<?php echo base_url('dashboard/complete-profile'); ?>">
                                Update and resubmit <i class="fas fa-arrow-right"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                    <span class="status-pill"><?php echo esc($profile_status['state'] ?? 'pending'); ?></span>
                </div>
            <?php endif; ?>

            </div>
        </main>

        <!-- Bottom Navigation -->
        <nav class="bottom-nav">
            <a href="<?php echo base_url('dashboard'); ?>" class="nav-item active">
                <div class="nav-icon">
                    <i class="fas fa-home"></i>
                </div>
                <div class="nav-label">Home</div>
            </a>
            <a href="<?php echo base_url('trainer/subjects'); ?>" class="nav-item">
                <div class="nav-icon">
                    <i class="fas fa-book-open"></i>
                </div>
                <div class="nav-label">Subjects</div>
            </a>
            <a href="<?php echo base_url('trainer/profile'); ?>" class="nav-item">
                <div class="nav-icon">
                    <i class="fas fa-user"></i>
                </div>
                <div class="nav-label">Profile</div>
            </a>
        </nav>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

