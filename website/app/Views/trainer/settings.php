<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Settings - TutorConnect Malawi' ?></title>

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
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #818cf8;
            --secondary: #06b6d4;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #1e293b;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;

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
            display: none;
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

        /* Cards */
        .card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            border: 1px solid var(--gray-200);
            transition: var(--transition);
        }

        .card:hover {
            box-shadow: var(--card-shadow-hover);
        }

        /* Settings specific styles */
        .settings-nav .nav-link {
            padding: 0.75rem 1rem;
            color: var(--gray-600);
            border-radius: var(--border-radius-sm);
            margin-bottom: 0.5rem;
            transition: var(--transition);
        }

        .settings-nav .nav-link:hover,
        .settings-nav .nav-link.active {
            background: var(--primary);
            color: white;
        }

        .integration-item {
            padding: 1rem;
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: var(--border-radius);
        }

        .payment-method-item {
            transition: var(--transition);
        }

        .payment-method-item:hover {
            border-color: var(--primary);
        }

        /* Bottom Navigation - Mobile Only */
        .bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            border-top: 1px solid var(--gray-200);
            padding: 0.5rem 0;
            z-index: 100;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
        }

        .nav-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0.25rem;
            max-width: 768px;
            margin: 0 auto;
        }

        .nav-item-mobile {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0.5rem;
            text-decoration: none;
            color: var(--gray-500);
            border-radius: var(--border-radius-sm);
            transition: var(--transition);
        }

        .nav-item-mobile:hover,
        .nav-item-mobile.active {
            color: var(--primary);
            background-color: rgba(99, 102, 241, 0.08);
        }

        .nav-item-mobile i {
            font-size: 1.25rem;
            margin-bottom: 0.25rem;
        }

        .nav-item-mobile span {
            font-size: 0.75rem;
            font-weight: 500;
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
                display: flex;
            }
        }

        @media (max-width: 768px) {
            :root {
                --header-height: 60px;
            }

            .content-wrapper {
                padding: 1rem;
            }

            .sidebar {
                width: 280px;
            }

            .bottom-nav {
                display: block;
            }

            .settings-nav {
                margin-bottom: 1rem;
            }

            .settings-nav .nav {
                flex-direction: row;
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .settings-nav .nav-link {
                flex: 1;
                min-width: calc(50% - 0.25rem);
                text-align: center;
                font-size: 0.875rem;
            }
        }

        @media (max-width: 480px) {
            .content-wrapper {
                padding: 0.5rem;
            }

            .header-right {
                gap: 0.5rem;
            }

            .notification-btn,
            .user-avatar {
                width: 36px;
                height: 36px;
            }

            .settings-nav .nav-link {
                padding: 0.5rem;
                font-size: 0.8rem;
            }
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
                    <a href="<?= site_url('trainer/inquiries') ?>" class="nav-item">
                        <i class="fas fa-envelope"></i>
                        <span>Inquiries</span>
                        <span class="nav-badge">0</span>
                    </a>
                    <a href="<?= site_url('trainer/analytics') ?>" class="nav-item">
                        <i class="fas fa-chart-line"></i>
                        <span>Analytics</span>
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
                    </a>
                    <a href="<?= site_url('trainer/settings') ?>" class="nav-item active">
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
                        <p>Account Settings</p>
                    </div>
                </div>

                <div class="header-right">
                    <button class="notification-btn" title="Notifications">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">3</span>
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
                <!-- Settings Header -->
                <div class="settings-header mb-4" data-aos="fade-up">
                    <h1 class="h3 fw-bold">Settings</h1>
                    <p class="text-muted">Manage your account settings and preferences</p>
                </div>

    <div class="row">
        <!-- Settings Navigation -->
        <div class="col-lg-3 mb-4">
            <div class="card settings-nav">
                <div class="card-body">
                    <nav class="nav flex-column">
                        <a class="nav-link active" data-bs-toggle="tab" href="#general">
                            <i class="fas fa-cog me-2"></i>General
                        </a>
                        <a class="nav-link" data-bs-toggle="tab" href="#notifications">
                            <i class="fas fa-bell me-2"></i>Notifications
                        </a>
                        <a class="nav-link" data-bs-toggle="tab" href="#privacy">
                            <i class="fas fa-shield-alt me-2"></i>Privacy & Security
                        </a>
                        <a class="nav-link" data-bs-toggle="tab" href="#integrations">
                            <i class="fas fa-plug me-2"></i>Integrations
                        </a>
                        <a class="nav-link" data-bs-toggle="tab" href="#billing">
                            <i class="fas fa-credit-card me-2"></i>Billing & Payments
                        </a>
                        <a class="nav-link" data-bs-toggle="tab" href="#advanced">
                            <i class="fas fa-sliders-h me-2"></i>Advanced
                        </a>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Settings Content -->
        <div class="col-lg-9">
            <div class="tab-content">
                <!-- General Settings -->
                <div class="tab-pane fade show active" id="general">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-4">General Settings</h5>
                            
                            <form id="generalSettingsForm">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Default Language</label>
                                        <select class="form-select" name="language">
                                            <option value="en" selected>English</option>
                                            <option value="ny">Chichewa</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Timezone</label>
                                        <select class="form-select" name="timezone">
                                            <option value="Africa/Blantyre" selected>Africa/Blantyre (GMT+2)</option>
                                            <option value="UTC">UTC</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Date Format</label>
                                        <select class="form-select" name="date_format">
                                            <option value="Y-m-d">YYYY-MM-DD</option>
                                            <option value="m/d/Y" selected>MM/DD/YYYY</option>
                                            <option value="d/m/Y">DD/MM/YYYY</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Time Format</label>
                                        <select class="form-select" name="time_format">
                                            <option value="12" selected>12-hour</option>
                                            <option value="24">24-hour</option>
                                        </select>
                                    </div>

                                    <div class="col-12">
                                        <div class="d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Notification Settings -->
                <div class="tab-pane fade" id="notifications">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Notification Settings</h5>
                            
                            <form id="notificationSettingsForm">
                                <div class="mb-4">
                                    <h6 class="mb-3">Email Notifications</h6>
                                    <div class="card bg-light">
                                        <div class="card-body">

                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="email_marketing" id="emailMarketing">
                                                <label class="form-check-label" for="emailMarketing">
                                                    Marketing emails & platform updates
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <h6 class="mb-3">Push Notifications</h6>
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="push_new_messages" id="pushNewMessages" checked>
                                                <label class="form-check-label" for="pushNewMessages">
                                                    New messages from students
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="push_payments" id="pushPayments" checked>
                                                <label class="form-check-label" for="pushPayments">
                                                    Payment notifications
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <h6 class="mb-3">SMS Notifications</h6>
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="sms_urgent" id="smsUrgent">
                                                <label class="form-check-label" for="smsUrgent">
                                                    Urgent notifications only
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">Save Preferences</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Privacy & Security -->
                <div class="tab-pane fade" id="privacy">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Privacy & Security</h5>
                            
                            <!-- Data Privacy -->
                            <div class="mb-4">
                                <h6 class="mb-3">Data Privacy</h6>
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="profile_visibility" id="profileVisibility" checked>
                                            <label class="form-check-label" for="profileVisibility">
                                                Make my profile visible to students
                                            </label>
                                        </div>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="show_ratings" id="showRatings" checked>
                                            <label class="form-check-label" for="showRatings">
                                                Display student ratings on my profile
                                            </label>
                                        </div>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="allow_messaging" id="allowMessaging" checked>
                                            <label class="form-check-label" for="allowMessaging">
                                                Allow students to message me directly
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="data_sharing" id="dataSharing">
                                            <label class="form-check-label" for="dataSharing">
                                                Share anonymous usage data to improve the platform
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Security -->
                            <div class="mb-4">
                                <h6 class="mb-3">Security</h6>
                                <div class="d-grid gap-2">
                                    <button class="btn btn-outline-primary text-start" 
                                            data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                        <i class="fas fa-key me-2"></i>Change Password
                                    </button>
                                    <button class="btn btn-outline-primary text-start"
                                            data-bs-toggle="modal" data-bs-target="#twoFactorModal">
                                        <i class="fas fa-shield-alt me-2"></i>Two-Factor Authentication
                                    </button>

                                </div>
                            </div>

                            <!-- Data Management -->
                            <div>
                                <h6 class="mb-3">Data Management</h6>
                                <div class="d-grid gap-2">
                                    <button class="btn btn-outline-info text-start"
                                            onclick="exportData()">
                                        <i class="fas fa-download me-2"></i>Export My Data
                                    </button>
                                    <button class="btn btn-outline-danger text-start"
                                            data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                        <i class="fas fa-trash me-2"></i>Delete Account
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Integrations -->
                <div class="tab-pane fade" id="integrations">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Integrations</h5>
                            
                            <!-- Calendar Integrations -->
                            <div class="mb-4">
                                <h6 class="mb-3">Calendar Sync</h6>
                                <div class="integration-item mb-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <i class="fab fa-google text-danger fa-2x me-3"></i>
                                            <span class="fw-bold">Google Calendar</span>
                                        </div>
                                        <div>
                                            <button class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-plug me-1"></i>Connect
                                            </button>
                                        </div>
                                    </div>
                                    <p class="text-muted small mt-2 mb-0">
                                        Sync your tutoring sessions with Google Calendar
                                    </p>
                                </div>
                                <div class="integration-item">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <i class="fas fa-calendar text-primary fa-2x me-3"></i>
                                            <span class="fw-bold">Outlook Calendar</span>
                                        </div>
                                        <div>
                                            <button class="btn btn-outline-secondary btn-sm" disabled>
                                                Coming Soon
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Integrations -->
                            <div class="mb-4">
                                <h6 class="mb-3">Payment Methods</h6>
                                <div class="integration-item mb-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <i class="fas fa-mobile-alt text-success fa-2x me-3"></i>
                                            <span class="fw-bold">Mobile Money</span>
                                        </div>
                                        <div>
                                            <span class="badge bg-success">Connected</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="integration-item">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <i class="fas fa-credit-card text-primary fa-2x me-3"></i>
                                            <span class="fw-bold">Bank Transfer</span>
                                        </div>
                                        <div>
                                            <button class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-plus me-1"></i>Add Account
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Billing & Payments -->
                <div class="tab-pane fade" id="billing">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Billing & Payments</h5>
                            
                            <!-- Payment Methods -->
                            <div class="mb-4">
                                <h6 class="mb-3">Payment Methods</h6>
                                <div class="payment-methods">
                                    <?php foreach($payment_methods as $method): ?>
                                        <div class="payment-method-item card mb-2">
                                            <div class="card-body py-2">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-credit-card fa-2x me-3 text-primary"></i>
                                                        <div>
                                                            <h6 class="mb-0"><?= htmlspecialchars($method['type']) ?></h6>
                                                            <small class="text-muted">
                                                                **** **** **** <?= substr($method['last_four'], -4) ?>
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <?php if($method['is_default']): ?>
                                                            <span class="badge bg-primary">Default</span>
                                                        <?php endif; ?>
                                                        <button class="btn btn-sm btn-outline-danger ms-2">
                                                            Remove
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <button class="btn btn-outline-primary mt-2" data-bs-toggle="modal" data-bs-target="#addPaymentMethodModal">
                                    <i class="fas fa-plus me-2"></i>Add Payment Method
                                </button>
                            </div>

                            <!-- Billing Address -->
                            <div class="mb-4">
                                <h6 class="mb-3">Billing Address</h6>
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <address class="mb-0">
                                            <?= nl2br(htmlspecialchars($billing_address ?? 'No billing address set')) ?>
                                        </address>
                                        <button class="btn btn-sm btn-outline-primary mt-2">
                                            <i class="fas fa-edit me-1"></i>Edit Address
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Invoices -->
                            <div>
                                <h6 class="mb-3">Recent Invoices</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($invoices as $invoice): ?>
                                                <tr>
                                                    <td><?= date('M d, Y', strtotime($invoice['date'])) ?></td>
                                                    <td>MWK <?= number_format($invoice['amount']) ?></td>
                                                    <td>
                                                        <span class="badge bg-<?= $invoice['status'] === 'paid' ? 'success' : 'warning' ?>">
                                                            <?= ucfirst($invoice['status']) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="<?= $invoice['download_url'] ?>" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Advanced Settings -->
                <div class="tab-pane fade" id="advanced">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Advanced Settings</h5>
                            
                            <!-- API Access -->
                            <div class="mb-4">
                                <h6 class="mb-3">API Access</h6>
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <p class="mb-3">Access TutorConnect data through our API</p>
                                        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#apiKeyModal">
                                            <i class="fas fa-key me-2"></i>Generate API Key
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Developer Options -->
                            <div class="mb-4">
                                <h6 class="mb-3">Developer Options</h6>
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="devMode">
                                    <label class="form-check-label" for="devMode">
                                        Developer Mode
                                    </label>
                                </div>
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="debugLogs">
                                    <label class="form-check-label" for="debugLogs">
                                        Enable Debug Logs
                                    </label>
                                </div>
                            </div>

                            <!-- Reset Options -->
                            <div>
                                <h6 class="mb-3">Reset Options</h6>
                                <div class="d-grid gap-2">
                                    <button class="btn btn-outline-warning text-start"
                                            onclick="resetPreferences()">
                                        <i class="fas fa-redo me-2"></i>Reset All Preferences
                                    </button>
                                    <button class="btn btn-outline-danger text-start"
                                            data-bs-toggle="modal" data-bs-target="#clearDataModal">
                                        <i class="fas fa-eraser me-2"></i>Clear All Data
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals for Settings -->
<div class="modal fade" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="changePasswordForm">
                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="changePasswordForm" class="btn btn-primary">Change Password</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteAccountModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    This action cannot be undone!
                </div>
                <p>All your data, including students, and payment history will be permanently deleted.</p>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="confirmDelete">
                    <label class="form-check-label" for="confirmDelete">
                        I understand that all my data will be permanently deleted
                    </label>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="confirmBackup">
                    <label class="form-check-label" for="confirmBackup">
                        I have backed up any important data
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn" disabled>
                    Delete Account Permanently
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .settings-nav .nav-link {
        padding: 0.75rem 1rem;
        color: var(--gray-600);
        border-radius: var(--border-radius-sm);
        margin-bottom: 0.5rem;
        transition: var(--transition);
    }

    .settings-nav .nav-link:hover,
    .settings-nav .nav-link.active {
        background: var(--primary);
        color: white;
    }

    .integration-item {
        padding: 1rem;
        background: white;
        border: 1px solid var(--gray-200);
        border-radius: var(--border-radius);
    }

    .payment-method-item {
        transition: var(--transition);
    }

    .payment-method-item:hover {
        border-color: var(--primary);
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form submissions
    const forms = ['generalSettingsForm', 'notificationSettingsForm'];
    forms.forEach(formId => {
        const form = document.getElementById(formId);
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                showToast('Settings saved successfully!', 'success');
            });
        }
    });

    // Change password form
    const changePasswordForm = document.getElementById('changePasswordForm');
    if (changePasswordForm) {
        changePasswordForm.addEventListener('submit', function(e) {
            e.preventDefault();
            showToast('Password changed successfully!', 'success');
            bootstrap.Modal.getInstance(document.getElementById('changePasswordModal')).hide();
            this.reset();
        });
    }

    // Delete account confirmation
    const confirmDelete = document.getElementById('confirmDelete');
    const confirmBackup = document.getElementById('confirmBackup');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    
    if (confirmDelete && confirmBackup && confirmDeleteBtn) {
        function updateDeleteButton() {
            confirmDeleteBtn.disabled = !(confirmDelete.checked && confirmBackup.checked);
        }
        
        confirmDelete.addEventListener('change', updateDeleteButton);
        confirmBackup.addEventListener('change', updateDeleteButton);
        
        confirmDeleteBtn.addEventListener('click', function() {
            // Implement account deletion
            showToast('Account deletion initiated', 'warning');
            bootstrap.Modal.getInstance(document.getElementById('deleteAccountModal')).hide();
        });
    }

    // Integrations
    document.querySelectorAll('.integration-item button').forEach(button => {
        button.addEventListener('click', function() {
            if (!this.disabled) {
                const service = this.closest('.integration-item').querySelector('.fw-bold').textContent;
                showToast(`Connecting to ${service}...`, 'info');
            }
        });
    });
});



function exportData() {
    // Implement data export
    showToast('Data export initiated', 'info');
}

function resetPreferences() {
    if (confirm('Reset all preferences to default?')) {
        showToast('Preferences reset to default', 'success');
    }
}

function showToast(message, type = 'success') {
    // Toast implementation
}
</script>
<?= $this->endSection() ?>
