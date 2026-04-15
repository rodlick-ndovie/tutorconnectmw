<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <title><?= esc($title ?? 'Admin Panel - TutorConnect Malawi') ?></title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#E55C0D',
                        secondary: '#2C3E50',
                        accent: '#34495E',
                        neutral: '#ECF0F1'
                    },
                    fontFamily: {
                        'sans': ['Inter', 'sans-serif']
                    }
                }
            }
        }
    </script>

    <style>
        :root {
            --admin-primary: #1e40af;
            --admin-secondary: #1e293b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --bg-primary: #f8fafc;
            --bg-secondary: #ffffff;
            --bg-dropdown: #f1f5f9;
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
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

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            height: 100vh;
            background: var(--bg-secondary);
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .sidebar-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 4px;
        }

        .sidebar-subtitle {
            font-size: 14px;
            color: var(--text-light);
        }

        .sidebar-nav {
            padding: 20px 0;
        }

        .nav-section {
            margin-bottom: 24px;
        }

        .nav-section-title {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 0 20px;
            margin-bottom: 8px;
        }

        .nav-item {
            display: block;
            padding: 12px 20px;
            color: var(--text-light);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .nav-item:hover {
            color: var(--admin-primary);
            background: rgba(30, 64, 175, 0.1);
        }

        .nav-item.active {
            color: var(--admin-primary);
            background: rgba(30, 64, 175, 0.1);
            border-left-color: var(--admin-primary);
            font-weight: 600;
        }

        .badge-unread {
            float: right;
            background: var(--danger-color);
            color: #ffffff;
            border-radius: 999px;
            padding: 2px 8px;
            font-size: 12px;
            font-weight: 700;
            line-height: 1.4;
        }

        .nav-icon {
            width: 20px;
            text-align: center;
            margin-right: 12px;
        }

        .main-content {
            margin-left: 280px;
            padding: 32px;
            min-height: 100vh;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
                padding: 20px;
            }
        }

        .header-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
        }

        .page-title {
            font-size: 32px;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0;
        }

        .page-subtitle {
            font-size: 16px;
            color: var(--text-light);
            margin: 0;
        }

        .btn-admin {
            background: linear-gradient(135deg, var(--admin-primary), var(--admin-secondary));
            border: none;
            border-radius: var(--border-radius);
            color: white;
            padding: 12px 24px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-admin:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(30, 64, 175, 0.3);
            color: white;
        }

        .content-card {
            background: var(--bg-secondary);
            border-radius: var(--border-radius-lg);
            padding: 24px;
            box-shadow: var(--shadow);
            border: 1px solid rgba(0, 0, 0, 0.05);
            margin-bottom: 24px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--bg-secondary);
            border-radius: var(--border-radius);
            padding: 20px;
            box-shadow: var(--shadow);
            border: 1px solid rgba(0, 0, 0, 0.05);
            text-align: center;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: var(--border-radius);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            font-size: 16px;
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
    </style>

    <!-- Page-specific styles -->
    <?= $this->renderSection('styles') ?>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-title">Admin Panel</div>
            <div class="sidebar-subtitle">TutorConnect Malawi</div>
        </div>
        <nav class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-section-title">Overview</div>
                <a href="<?php echo base_url('admin/dashboard'); ?>" class="nav-item <?php echo (isset($active_page) && $active_page === 'dashboard') ? 'active' : ''; ?>">
                    <span class="nav-icon"><i class="fas fa-chart-line"></i></span>
                    Dashboard
                </a>
            </div>
            <div class="nav-section">
                <div class="nav-section-title">Management</div>
                <a href="<?php echo base_url('admin/users'); ?>" class="nav-item <?php echo (isset($active_page) && $active_page === 'users') ? 'active' : ''; ?>">
                    <span class="nav-icon"><i class="fas fa-users"></i></span>
                    Manage Users
                </a>
                <a href="<?php echo base_url('admin/trainers'); ?>" class="nav-item <?php echo (isset($active_page) && $active_page === 'trainers') ? 'active' : ''; ?>">
                    <span class="nav-icon"><i class="fas fa-chalkboard-teacher"></i></span>
                    Tutors
                </a>
                <a href="<?php echo base_url('admin/renewal-management'); ?>" class="nav-item <?php echo (isset($active_page) && $active_page === 'renewal_management') ? 'active' : ''; ?>">
                    <span class="nav-icon"><i class="fas fa-bell"></i></span>
                    Renewal Management
                </a>
                <a href="<?php echo base_url('admin/curriculum'); ?>" class="nav-item <?php echo (isset($active_page) && $active_page === 'curriculum') ? 'active' : ''; ?>">
                    <span class="nav-icon"><i class="fas fa-graduation-cap"></i></span>
                    Curriculum
                </a>

            </div>
            <div class="nav-section">
                <div class="nav-section-title">Communication</div>
                <a href="<?php echo base_url('admin/contact-messages'); ?>" class="nav-item <?php echo (isset($active_page) && $active_page === 'messages') ? 'active' : ''; ?>">
                    <span class="nav-icon"><i class="fas fa-envelope"></i></span>
                    Messages
                    <?php if (($unreadMessageCount ?? 0) > 0): ?>
                        <span class="badge-unread"><?php echo $unreadMessageCount; ?></span>
                    <?php endif; ?>
                </a>
                <a href="<?php echo base_url('admin/parent-requests'); ?>" class="nav-item <?php echo (isset($active_page) && $active_page === 'parent_requests') ? 'active' : ''; ?>">
                    <span class="nav-icon"><i class="fas fa-list-check"></i></span>
                    Parent Requests
                </a>
                <a href="<?php echo base_url('admin/japan-applications'); ?>" class="nav-item <?php echo (isset($active_page) && $active_page === 'japan_applications') ? 'active' : ''; ?>">
                    <span class="nav-icon"><i class="fas fa-plane-departure"></i></span>
                    Japan Applications
                </a>
            </div>
            <div class="nav-section">
                <div class="nav-section-title">Business</div>

                <a href="<?php echo base_url('admin/subscriptions'); ?>" class="nav-item <?php echo (isset($active_page) && $active_page === 'subscriptions') ? 'active' : ''; ?>">
                    <span class="nav-icon"><i class="fas fa-tags"></i></span>
                    Subscription Plans
                </a>
                <a href="<?php echo base_url('admin/tutor-subscriptions'); ?>" class="nav-item <?php echo (isset($active_page) && $active_page === 'tutor_subscriptions') ? 'active' : ''; ?>">
                    <span class="nav-icon"><i class="fas fa-users-cog"></i></span>
                    Tutor Subscriptions
                </a>
                <a href="<?php echo base_url('admin/past-paper-payments'); ?>" class="nav-item <?php echo (isset($active_page) && $active_page === 'past_paper_payments') ? 'active' : ''; ?>">
                    <span class="nav-icon"><i class="fas fa-file-invoice-dollar"></i></span>
                    PDF Download Payments
                </a>


            </div>
            <div class="nav-section">
                <div class="nav-section-title">Content</div>

                <a href="<?php echo base_url('notice/admin/all'); ?>" class="nav-item <?php echo (isset($active_page) && $active_page === 'notices') ? 'active' : ''; ?>">
                    <span class="nav-icon"><i class="fas fa-bullhorn"></i></span>
                    Notices
                </a>
                <a href="<?php echo base_url('admin/library'); ?>" class="nav-item <?php echo (isset($active_page) && $active_page === 'library') ? 'active' : ''; ?>">
                    <span class="nav-icon"><i class="fas fa-book"></i></span>
                    Library
                </a>
                <a href="<?php echo base_url('admin/settings'); ?>" class="nav-item <?php echo (isset($active_page) && $active_page === 'settings') ? 'active' : ''; ?>">
                    <span class="nav-icon"><i class="fas fa-cog"></i></span>
                    Settings
                </a>
            </div>
            <div class="nav-section">
                <div class="nav-section-title">System</div>

                <a href="<?php echo base_url('admin/backup'); ?>" class="nav-item <?php echo (isset($active_page) && $active_page === 'backup') ? 'active' : ''; ?>">
                    <span class="nav-icon"><i class="fas fa-database"></i></span>
                    Database Backup
                </a>
            </div>
            <div class="nav-section">
                <div class="nav-section-title">Account</div>
                <a href="<?php echo base_url('logout'); ?>" class="nav-item" style="color: var(--danger-color);">
                    <span class="nav-icon"><i class="fas fa-sign-out-alt"></i></span>
                    Logout
                </a>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Flash Messages -->
        <?php if (session()->has('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo session('success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if (session()->has('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i><?php echo session('error'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Page Content -->
        <?= $this->renderSection('content') ?>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Additional Scripts Section -->
    <?= $this->renderSection('scripts') ?>

    <!-- Notification System -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1060;">
        <div id="notificationToast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="notificationMessage">
                    <!-- Message will be inserted here -->
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script>
        // Professional notification system
        function showNotification(message, type = 'success') {
            const toast = document.getElementById('notificationToast');
            const messageEl = document.getElementById('notificationMessage');

            // Set message
            messageEl.textContent = message;

            // Set styling based on type
            toast.className = 'toast align-items-center text-white border-0';
            if (type === 'success') {
                toast.classList.add('bg-success');
            } else if (type === 'error') {
                toast.classList.add('bg-danger');
            } else if (type === 'warning') {
                toast.classList.add('bg-warning');
            } else if (type === 'info') {
                toast.classList.add('bg-info');
            }

            // Show toast
            const bsToast = new bootstrap.Toast(toast, {
                delay: 5000 // Auto-hide after 5 seconds
            });
            bsToast.show();
        }

        // Make it globally available
        window.showNotification = showNotification;
    </script>

    <!-- Footer -->
    <footer style="background-color: #f8f9fa; border-top: 1px solid #e9ecef; padding: 20px 0; margin-top: 50px; text-align: center; font-size: 13px; color: #6c757d;">
        <div class="container">
            <div style="display: flex; justify-content: center; align-items: center; flex-direction: column; gap: 8px;">
                <p style="margin: 0; font-weight: 500;">
                    <i class="fas fa-code" style="color: #667eea; margin-right: 8px;"></i>
                    <strong> <?php echo date('Y'); ?>  <span style="margin: 0 10px;">•</span> Developed with  <i class="fas fa-heart" style="color: #ef4444; margin-right: 5px;"></i> by Uprise Malawi</strong>
                </p>
            </div>
        </div>
    </footer>
</body>
</html>
                </div>
