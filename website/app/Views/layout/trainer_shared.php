<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'TutorConnect Malawi - Trainer' ?></title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('favicon.ico') ?>">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            /* Modern App Colors */
            --primary: #059669;
            --primary-dark: #047857;
            --primary-light: #10b981;
            --secondary: #06b6d4;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #1f2937;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;

            /* App Variables */
            --sidebar-width: 280px;
            --sidebar-collapsed: 80px;
            --header-height: 64px;
            --bottom-nav-height: 60px;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --card-shadow-hover: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --border-radius-sm: 8px;
            --border-radius: 12px;
            --border-radius-lg: 16px;
            --border-radius-xl: 20px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--gray-50);
            color: var(--gray-700);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            overflow-x: hidden;
        }

        /* App Container */
        .app-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar - Desktop */
        .sidebar {
            width: var(--sidebar-width);
            background: white;
            border-right: 1px solid var(--gray-200);
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 100;
            display: flex;
            flex-direction: column;
            transition: var(--transition);
            box-shadow: var(--card-shadow);
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .logo {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
        }

        .app-name h1 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--dark);
            margin: 0;
        }

        .app-name p {
            font-size: 0.75rem;
            color: var(--gray-500);
            margin: 0;
        }

        .sidebar-nav {
            flex: 1;
            padding: 1.5rem 0;
            overflow-y: auto;
        }

        .nav-section {
            margin-bottom: 1.5rem;
        }

        .nav-section-title {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0 1.5rem;
            margin-bottom: 0.75rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1.5rem;
            color: var(--gray-600);
            text-decoration: none;
            transition: var(--transition);
            border-left: 3px solid transparent;
            margin: 0.25rem 0;
        }

        .nav-item:hover {
            background-color: var(--gray-50);
            color: var(--primary);
            border-left-color: var(--primary-light);
        }

        .nav-item.active {
            background-color: rgba(99, 102, 241, 0.08);
            color: var(--primary);
            border-left-color: var(--primary);
            font-weight: 500;
        }

        .nav-item.disabled {
            pointer-events: none;
            opacity: 0.6;
        }

        .nav-item i {
            width: 20px;
            text-align: center;
            font-size: 1rem;
        }

        .nav-badge {
            margin-left: auto;
            background-color: var(--primary);
            color: white;
            font-size: 0.7rem;
            padding: 0.125rem 0.5rem;
            border-radius: 10px;
            font-weight: 600;
        }

        /* Main Content Area */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: var(--transition);
        }

        /* Top Header */
        .top-header {
            height: var(--header-height);
            background: white;
            border-bottom: 1px solid var(--gray-200);
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
            backdrop-filter: blur(8px);
            background-color: rgba(255, 255, 255, 0.95);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .sidebar-toggle {
            background: none;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: var(--border-radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-600);
            cursor: pointer;
            transition: var(--transition);
            display: none !important; /* Hidden on desktop */
        }

        .sidebar-toggle:hover {
            background-color: var(--gray-100);
        }

        .user-welcome h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--dark);
            margin: 0;
        }

        .user-welcome p {
            font-size: 0.875rem;
            color: var(--gray-500);
            margin: 0;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        /* Notification Button */
        .notification-btn {
            position: relative;
            background: none;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: var(--border-radius-sm);
            color: var(--gray-600);
            cursor: pointer;
            transition: var(--transition);
        }

        .notification-btn:hover {
            background-color: var(--gray-100);
            color: var(--primary);
        }

        .notification-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            background-color: var(--danger);
            color: white;
            font-size: 0.7rem;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            border: 2px solid white;
        }

        /* User Menu */
        .user-menu {
            position: relative;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: var(--border-radius);
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            border: 2px solid white;
            box-shadow: var(--card-shadow);
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow-hover);
            min-width: 200px;
            padding: 0.5rem 0;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: var(--transition);
            z-index: 1000;
        }

        .user-menu:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: var(--gray-600);
            text-decoration: none;
            transition: var(--transition);
        }

        .dropdown-item:hover {
            background-color: var(--gray-50);
            color: var(--primary);
        }

        .dropdown-divider {
            height: 1px;
            background-color: var(--gray-200);
            margin: 0.5rem 0;
        }

        /* Content Area */
        .content-wrapper {
            padding: 1.5rem;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }

        /* Bottom Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
            max-width: 100%;
            background: var(--gray-50);
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

        .nav-item-mobile {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: var(--gray-500);
            transition: all 0.3s ease;
            padding: 6px 4px;
            border-radius: 12px;
        }

        .nav-item-mobile:hover {
            background: rgba(0, 0, 0, 0.05);
        }

        .nav-item-mobile.active {
            color: var(--primary);
        }

        /* Animations */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar-toggle {
                display: flex !important;
            }

            .content-wrapper {
                padding: 1.25rem;
            }
        }

        @media (max-width: 768px) {
            :root {
                --header-height: 60px;
                --sidebar-width: 280px;
            }

            .top-header {
                padding: 0 1rem;
            }

            .content-wrapper {
                padding: 1rem 0.75rem;
            }

            .user-welcome h3 {
                font-size: 1rem;
            }

            .user-welcome p {
                font-size: 0.8rem;
            }

            .bottom-nav {
                display: block;
            }

            .sidebar {
                width: 100%;
                max-width: 320px;
            }

            /* Mobile-first grid adjustments */
            .action-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .card-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
        }

        @media (max-width: 640px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .welcome-card {
                padding: 1.5rem;
            }

            .welcome-title {
                font-size: 1.4rem;
            }

            .action-card {
                padding: 1.25rem;
            }

            .action-card-header {
                margin-bottom: 1rem;
            }

            .action-icon {
                width: 44px;
                height: 44px;
            }
        }

        @media (max-width: 480px) {
            .header-right {
                gap: 0.5rem;
            }

            .notification-btn,
            .user-avatar {
                width: 36px;
                height: 36px;
            }

            .notification-btn i,
            .user-avatar {
                font-size: 0.9rem;
            }

            .top-header {
                padding: 0 0.75rem;
            }

            .content-wrapper {
                padding: 0.75rem 0.5rem;
            }

            .welcome-card {
                padding: 1.25rem;
                margin-bottom: 1.5rem;
            }

            .action-grid,
            .stats-grid,
            .card-grid {
                gap: 0.75rem;
            }
        }

        /* Large desktop screens */
        @media (min-width: 1440px) {
            .content-wrapper {
                max-width: 1600px;
                padding: 2rem;
            }

            .sidebar {
                width: 320px;
            }

            .main-content {
                margin-left: 320px;
            }
        }

        /* Tablet landscape */
        @media (min-width: 768px) and (max-width: 1024px) {
            .action-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .stats-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        /* Professional Grid Systems */
        .action-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.25rem;
            margin-bottom: 2rem;
        }

        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.25rem;
            margin-bottom: 2rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        /* Professional Cards */
        .professional-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.75rem;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--gray-200);
            transition: var(--transition);
        }

        .professional-card:hover {
            box-shadow: var(--card-shadow-hover);
            transform: translateY(-1px);
        }

        .card-header-professional {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--gray-100);
        }

        .card-title-professional {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--dark);
            margin: 0;
        }

        .card-subtitle-professional {
            font-size: 0.875rem;
            color: var(--gray-600);
            margin: 0.25rem 0 0 0;
        }

        /* Professional Form Elements */
        .form-group-professional {
            margin-bottom: 1.5rem;
        }

        .form-label-professional {
            display: block;
            font-weight: 500;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .form-control-professional {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid var(--gray-200);
            border-radius: var(--border-radius-sm);
            font-size: 0.875rem;
            transition: var(--transition);
            background-color: white;
        }

        .form-control-professional:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .form-control-professional::placeholder {
            color: var(--gray-400);
        }

        /* Professional Buttons */
        .btn-professional {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            border-radius: var(--border-radius-sm);
            font-weight: 500;
            font-size: 0.875rem;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: var(--transition);
            gap: 0.5rem;
        }

        .btn-professional-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-professional-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: var(--card-shadow);
        }

        .btn-professional-secondary {
            background-color: white;
            color: var(--gray-700);
            border: 2px solid var(--gray-200);
        }

        .btn-professional-secondary:hover {
            background-color: var(--gray-50);
            border-color: var(--gray-300);
        }

        .btn-professional-success {
            background-color: var(--success);
            color: white;
        }

        .btn-professional-success:hover {
            background-color: #059669;
            transform: translateY(-1px);
            box-shadow: var(--card-shadow);
        }

        /* Professional Stats Cards */
        .stats-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--gray-200);
            text-align: center;
            transition: var(--transition);
        }

        .stats-card:hover {
            box-shadow: var(--card-shadow-hover);
            transform: translateY(-2px);
        }

        .stats-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.25rem;
        }

        .stats-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.25rem;
        }

        .stats-label {
            font-size: 0.875rem;
            color: var(--gray-600);
            font-weight: 500;
        }

        /* Loading States */
        .loading-shimmer {
            background: linear-gradient(90deg, var(--gray-200) 25%, var(--gray-100) 50%, var(--gray-200) 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        /* Empty States */
        .empty-state {
            text-align: center;
            padding: 3rem 1.5rem;
            color: var(--gray-500);
        }

        .empty-state-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-state-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
        }

        .empty-state-text {
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Sidebar Navigation (Desktop) -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="app-name">
                    <h1>TutorConnect</h1>
                    <p>Professional Platform</p>
                </div>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-section-title">Main</div>
                    <a href="<?= site_url('trainer/dashboard') ?>" class="nav-item">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>


                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Tools</div>
                    <a href="<?= site_url('trainer/availability') ?>" class="nav-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Availability</span>
                    </a>
                    <a href="<?= site_url('trainer/inquiries') ?>" class="nav-item <?= ($subscription_status ?? '' !== 'subscribed') ? 'disabled' : '' ?>">
                        <i class="fas fa-envelope"></i>
                        <span>Inquiries</span>
                        <?php if(($subscription_status ?? '') !== 'subscribed'): ?>
                            <span class="nav-badge"><i class="fas fa-lock"></i></span>
                        <?php endif; ?>
                    </a>
                    <a href="<?= site_url('trainer/analytics') ?>" class="nav-item">
                        <i class="fas fa-chart-line"></i>
                        <span>Analytics</span>
                    </a>
                    <a href="<?= site_url('trainer/reviews') ?>" class="nav-item">
                        <i class="fas fa-star"></i>
                        <span>Reviews</span>
                        <span class="nav-badge">0</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Account</div>
                    <a href="<?= site_url('trainer/profile') ?>" class="nav-item">
                        <i class="fas fa-user"></i>
                        <span>Profile</span>
                    </a>
                    <a href="<?= site_url('trainer/subscription') ?>" class="nav-item">
                        <i class="fas fa-crown"></i>
                        <span>Subscription</span>
                        <?php if(($subscription_status ?? '') === 'subscribed'): ?>
                            <span class="nav-badge">Active</span>
                        <?php endif; ?>
                    </a>
                    <a href="<?= site_url('trainer/settings') ?>" class="nav-item">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                </div>

                <div class="nav-section mt-auto">
                    <a href="<?= site_url('logout') ?>" class="nav-item text-danger">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            <!-- Top Header -->
            <header class="top-header">
                <div class="header-left">
                    <button class="sidebar-toggle" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="user-welcome">
                        <h3>Welcome, <?= esc($user['first_name'] ?? 'Tutor') ?>!</h3>
                        <p><?= $page_description ?? 'Tutor Dashboard' ?></p>
                    </div>
                </div>

                <div class="header-right">
                    <button class="notification-btn" title="Notifications">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge" style="display: none;">0</span>
                    </button>

                    <div class="user-menu">
                        <div class="user-avatar">
                            <?= strtoupper(substr($user['first_name'] ?? 'T', 0, 1)) ?>
                        </div>
                        <div class="dropdown-menu">
                            <a href="<?= site_url('trainer/profile') ?>" class="dropdown-item">
                                <i class="fas fa-user"></i>
                                <span>My Profile</span>
                            </a>
                            <a href="<?= site_url('trainer/settings') ?>" class="dropdown-item">
                                <i class="fas fa-cog"></i>
                                <span>Settings</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="<?= site_url('logout') ?>" class="dropdown-item text-danger">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Logout</span>
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Wrapper -->
            <div class="content-wrapper">
                <?= $this->renderSection('content') ?>
            </div>
        </main>
    </div>

    <!-- Bottom Navigation (Mobile Only) -->
    <nav class="bottom-nav">
        <a href="<?= site_url('trainer/dashboard') ?>" class="nav-item-mobile">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
        <a href="<?= site_url('trainer/subjects') ?>" class="nav-item-mobile">
            <i class="fas fa-book"></i>
            <span>Subjects</span>
        </a>
        <a href="<?= site_url('trainer/profile') ?>" class="nav-item-mobile">
            <i class="fas fa-user"></i>
            <span>Profile</span>
        </a>
        <a href="<?= site_url('trainer/subscription') ?>" class="nav-item-mobile">
            <i class="fas fa-crown"></i>
            <span>Premium</span>
        </a>
    </nav>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        // Pass subscription status to JavaScript
        const subscriptionStatus = '<?= $subscription_status ?? 'no_subscription' ?>';

        // Wait for DOM to be fully loaded before attaching event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize AOS animations
            if (typeof AOS !== 'undefined') {
                AOS.init({
                    duration: 600,
                    once: true,
                    offset: 100
                });
            }

            // Sidebar functionality for mobile
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');

            if (sidebarToggle && sidebar) {
                // Toggle sidebar when hamburger menu is clicked
                sidebarToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    sidebar.classList.toggle('show');
                    console.log('Sidebar toggled: ', sidebar.classList.contains('show'));
                });

                // Close sidebar when clicking outside on mobile
                document.addEventListener('click', function(e) {
                    if (window.innerWidth <= 1024) {
                        if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target) && sidebar.classList.contains('show')) {
                            sidebar.classList.remove('show');
                        }
                    }
                });

                // Handle window resize
                let resizeTimeout;
                window.addEventListener('resize', function() {
                    clearTimeout(resizeTimeout);
                    resizeTimeout = setTimeout(function() {
                        if (window.innerWidth > 1024 && sidebar.classList.contains('show')) {
                            sidebar.classList.remove('show');
                        }
                    }, 250);
                });
            } else {
                console.error('Sidebar elements not found');
            }

            // Set active navigation item based on current URL
            const currentPath = window.location.pathname;
            const navItems = document.querySelectorAll('.nav-item');
            const mobileNavItems = document.querySelectorAll('.nav-item-mobile');

            // Helper function to check if navigation item should be active
            function shouldBeActive(item) {
                const href = item.getAttribute('href');
                if (!href) return false;

                // Check if href contains key parts of current path
                if (href.includes('/trainer/dashboard') && currentPath.includes('/trainer/dashboard')) return true;
                if (href.includes('/trainer/subjects') && currentPath.includes('/trainer/subjects')) return true;
                if (href.includes('/trainer/profile') && currentPath.includes('/trainer/profile')) return true;
                if (href.includes('/trainer/subscription') && currentPath.includes('/trainer/subscription')) return true;

                return false;
            }

            navItems.forEach(item => {
                if (shouldBeActive(item)) {
                    item.classList.add('active');
                } else {
                    item.classList.remove('active');
                }
            });

            mobileNavItems.forEach(item => {
                if (shouldBeActive(item)) {
                    item.classList.add('active');
                } else {
                    item.classList.remove('active');
                }
            });

            // Update notification count
            fetchNotificationCount();

            // Handle subscription-required navigation items (only on subscription page)
            if (currentPath.includes('trainer/subscription')) {
                setupSubscriptionAlerts();
            }
        });

        // Function to handle alerts for subscription-required features
        function setupSubscriptionAlerts() {
            if (subscriptionStatus !== 'subscribed') {
                // Define navigation items that require subscription
                const subscriptionRequiredPaths = [
                    'trainer/sessions',
                    'trainer/bookings',
                    'trainer/inquiries'
                ];

                // Add click event listeners to both sidebar and mobile nav items
                const allNavItems = document.querySelectorAll('.nav-item, .nav-item-mobile');

                allNavItems.forEach(item => {
                    const href = item.getAttribute('href');
                    if (href && subscriptionRequiredPaths.some(path => href.includes(path))) {
                        item.addEventListener('click', function(e) {
                            e.preventDefault();
                            alert('This feature requires an active subscription. Please subscribe to access premium tutor features.');
                            // Optionally redirect to subscription page
                            window.location.href = '<?= site_url('trainer/subscription') ?>';
                            return false;
                        });
                    }
                });
            }
        }

        // Mock notification count - replace with real API call
        function fetchNotificationCount() {
            setTimeout(() => {
                const badge = document.querySelector('.notification-badge');
                const count = Math.floor(Math.random() * 5);
                if (count > 0) {
                    badge.textContent = count;
                    badge.style.display = 'flex';
                } else {
                    badge.style.display = 'none';
                }
            }, 1000);
        }

        // Handle action card clicks
        document.querySelectorAll('.action-card:not(.disabled)').forEach(card => {
            card.style.cursor = 'pointer';
            card.addEventListener('click', function() {
                if (this.onclick) {
                    this.onclick();
                } else if (this.getAttribute('onclick')) {
                    eval(this.getAttribute('onclick'));
                }
            });
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Handle window resize
        let resizeTimeout;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                if (window.innerWidth > 1024) {
                    sidebar.classList.remove('show');
                }
            }, 250);
        });

        // Active navigation highlighting for bottom nav
        const currentPath = window.location.pathname;
        const navItemsMobile = document.querySelectorAll('.nav-item-mobile');

        // Helper function to check if navigation item should be active
        function shouldBeActive(item) {
            const href = item.getAttribute('href');
            if (!href) return false;

            // Check if href contains key parts of current path
            if (href.includes('/trainer/dashboard') && currentPath.includes('/trainer/dashboard')) return true;
            if (href.includes('/trainer/subjects') && currentPath.includes('/trainer/subjects')) return true;
            if (href.includes('/trainer/profile') && currentPath.includes('/trainer/profile')) return true;
            if (href.includes('/trainer/subscription') && currentPath.includes('/trainer/subscription')) return true;

            return false;
        }

        navItemsMobile.forEach(item => {
            if (shouldBeActive(item)) {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
        });

        // Page-specific JavaScript can be added via sections
        <?= $this->renderSection('javascript') ?>
    </script>
</body>
</html>
