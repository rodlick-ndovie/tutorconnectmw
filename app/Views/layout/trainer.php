<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Trainer Dashboard - TutorConnect Malawi' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --trainer-primary: #3b82f6;
            --trainer-secondary: #1e40af;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --sidebar-bg: #1f2937;
            --sidebar-active: #3b82f6;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }

        .top-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 64px;
            background: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            z-index: 1050;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .sidebar {
            position: fixed;
            top: 64px; /* Below top header */
            left: 0;
            width: 260px;
            height: calc(100vh - 64px);
            background: var(--sidebar-bg);
            color: white;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-item {
            display: block;
            padding: 0.75rem 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .nav-item:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
        }

        .nav-item.active {
            color: white;
            background: var(--sidebar-active);
            border-left-color: white;
        }

        .logout-btn {
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            color: #475569;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .logout-btn:hover {
            background: #e2e8f0;
            color: #334155;
            border-color: #94a3b8;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .logout-btn:active {
            transform: translateY(0);
        }

        .logout-btn i {
            font-size: 14px;
        }

        .logout-btn span {
            display: none;
        }

        @media (min-width: 768px) {
            .logout-btn span {
                display: inline;
            }
        }

        .notification-btn {
            background: none;
            border: 1px solid #e2e8f0;
            color: #64748b;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            transition: all 0.3s ease;
            font-size: 16px;
        }

        .notification-btn:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #475569;
            transform: translateY(-1px);
        }

        .notification-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: var(--danger-color);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: 600;
            border: 2px solid white;
            min-width: 20px;
        }

        .main-content {
            margin-left: 260px;
            padding: 5rem 2rem 2rem 2rem; /* Add top padding for header */
            min-height: 100vh;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 260px;
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
        }

        .user-info {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <!-- Top Header -->
    <header class="top-header">
        <div class="d-flex align-items-center">
            <button class="btn me-3" onclick="toggleSidebar()" style="background: none; border: none; color: #666;">
                <i class="fas fa-bars"></i>
            </button>
            <div>
                <div class="fw-semibold" style="color: #333;"><?= esc(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?></div>
                <small class="text-muted">Tutor Panel</small>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button class="notification-btn" title="Notifications">
                <i class="fas fa-bell"></i>
                <?php if (!empty($unreadMessageCount) && $unreadMessageCount > 0): ?>
                    <span class="notification-badge"><?= $unreadMessageCount ?></span>
                <?php else: ?>
                    <span class="notification-badge" style="display: none;">0</span>
                <?php endif; ?>
            </button>
            <button class="logout-btn" onclick="window.location.href='<?= site_url('logout') ?>'">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </div>
    </header>

    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="sidebar-header">
            <h3 class="mb-0">
                <i class="fas fa-chalkboard-teacher me-2"></i>
                Tutor Panel
            </h3>
            <p class="mb-0 text-muted small">TutorConnect Malawi</p>
        </div>

        <ul class="sidebar-nav">
            <?php if (session()->get('role') === 'admin' || session()->get('role') === 'sub-admin'): ?>
                <!-- Admin Menu -->
                <li>
                    <a href="<?= site_url('admin/dashboard') ?>" class="nav-item">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                </li>
                <li>
                    <a href="<?= site_url('admin/users') ?>" class="nav-item">
                        <i class="fas fa-users me-2"></i>Users
                    </a>
                </li>
                <li>
                    <a href="<?= site_url('admin/tutors') ?>" class="nav-item">
                        <i class="fas fa-chalkboard-teacher me-2"></i>Teachers
                    </a>
                </li>
                <li>
                    <a href="<?= site_url('admin/contact-messages') ?>" class="nav-item position-relative">
                        <i class="fas fa-envelope me-2"></i>Messages
                        <?php if (!empty($unreadMessageCount) && $unreadMessageCount > 0): ?>
                            <span class="badge bg-danger rounded-pill position-absolute" style="top: 8px; right: 15px; font-size: 10px;"><?= $unreadMessageCount ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="mt-3">
                    <a href="<?= site_url('admin/settings') ?>" class="nav-item">
                        <i class="fas fa-cog me-2"></i>Settings
                    </a>
                </li>
            <?php else: ?>
                <!-- Trainer Menu -->
                <li>
                    <a href="<?= site_url('trainer/dashboard') ?>" class="nav-item">
                        <i class="fas fa-home me-2"></i>Home
                    </a>
                </li>
                <li>
                    <a href="<?= site_url('trainer/sessions') ?>" class="nav-item">
                        <i class="fas fa-calendar-alt me-2"></i>Sessions
                    </a>
                </li>
                <li class="mt-3">
                    <a href="<?= site_url('trainer/profile') ?>" class="nav-item">
                        <i class="fas fa-user me-2"></i>Profile
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- User Info Bar -->
        <div class="user-info">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="mb-0">Welcome back, <?= esc(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?>!</h5>
                    <p class="mb-0 text-muted">Manage your tutoring services and bookings</p>
                </div>
                <div class="col-md-4 text-end">
                    <small class="text-muted">
                        <i class="fas fa-clock me-1"></i>Last login: Today
                    </small>
                </div>
            </div>
        </div>

        <?= $this->renderSection('content') ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Add active class to current nav item and sidebar toggle -->
    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('show');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const navItems = document.querySelectorAll('.nav-item');

            navItems.forEach(item => {
                if (item.getAttribute('href') === currentPath) {
                    item.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>
