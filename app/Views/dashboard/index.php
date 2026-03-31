<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Dashboard - TutorConnect Malawi'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #7c3aed;
            --secondary-color: #6d28d9;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --bg-primary: #f8fafc;
            --bg-secondary: #ffffff;
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

        .navbar .d-flex.gap-2,
        .navbar > div > div:last-child {
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

        @media (min-width: 768px) {
            .navbar {
                height: 80px;
            }
        }

        .screen {
            padding: 20px;
            padding-bottom: 100px;
            min-height: 100vh;
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
            margin-bottom: 32px;
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

        .sections-grid {
            display: grid;
            gap: 16px;
            margin-bottom: 24px;
        }

        @media (min-width: 768px) {
            .sections-grid {
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 20px;
                margin-bottom: 32px;
            }
        }

        .section-card {
            background: var(--bg-secondary);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow);
            border: 1px solid rgba(0, 0, 0, 0.05);
            overflow: hidden;
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .section-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .section-header {
            padding: 20px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .section-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin: 0 auto 16px auto;
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 8px;
            text-align: center;
        }

        .section-description {
            font-size: 14px;
            color: var(--text-light);
            text-align: center;
            margin-bottom: 0;
        }

        .section-content {
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .btn-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: var(--border-radius);
            color: white;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 10px 20px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-section:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(124, 58, 237, 0.3);
            color: white;
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 480px;
            background: var(--bg-secondary);
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            padding: 8px 0 20px;
            z-index: 100;
        }

        @media (min-width: 768px) {
            .bottom-nav {
                max-width: 100%;
                left: 0;
                transform: none;
            }
        }

        .nav-container {
            width: 100%;
        }

        .nav-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
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
            background: rgba(0, 0, 0, 0.05);
        }

        .nav-item.active {
            background: rgba(124, 58, 237, 0.1);
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
            font-size: 10px;
            color: var(--text-light);
            font-weight: 500;
            text-align: center;
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
                <button class="btn p-0 border-0 bg-transparent me-3" onclick="window.location.href='<?php echo base_url('home'); ?>'">
                    <i class="fas fa-arrow-left text-muted" style="font-size: 20px;"></i>
                </button>
                <div>
                    <div class="fw-semibold"><?php echo $dashboard_title ?? 'Dashboard'; ?></div>
                    <small class="text-muted">Welcome back, <?php echo $user['first_name'] ?? $user['username']; ?></small>
                </div>
            </div>
            <button class="btn p-0 border-0 bg-transparent" onclick="window.location.href='<?php echo base_url('logout'); ?>'">
                <i class="fas fa-sign-out-alt text-muted" style="font-size: 20px;"></i>
            </button>
        </div>
    </header>

    <!-- Main Content -->
    <main class="screen">
        <!-- Welcome Header -->
        <div class="welcome-header">
            <h1 class="welcome-title">Welcome back! 👋</h1>
            <p class="welcome-subtitle"><?php echo $dashboard_title ?? 'Your dashboard overview'; ?></p>
        </div>

        <!-- Sections Grid -->
        <div class="sections-grid">
            <?php if (isset($sections) && is_array($sections)): ?>
                <?php foreach ($sections as $section): ?>
                    <a href="<?php echo (!empty($section['link'])) ? base_url($section['link']) : '#'; ?>" class="section-card">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-<?php
                                    $iconMap = [
                                        'cog' => 'cog',
                                        'shopping-cart' => 'shopping-cart',
                                        'help-circle' => 'question-circle',
                                        'book' => 'book',
                                        'users' => 'users',
                                        'certificate' => 'certificate',
                                        'tasks' => 'tasks',
                                        'profile' => 'user',
                                        'dashboard' => 'tachometer-alt'
                                    ];
                                    echo $iconMap[$section['icon']] ?? 'circle';
                                ?>"></i>
                            </div>
                            <h3 class="section-title"><?php echo $section['title'] ?? 'Section'; ?></h3>
                            <p class="section-description">
                                <?php
                                $descriptions = [
                                    'Book Services' => 'Browse and book our training services',
                                    'My Orders' => 'View and manage your service orders',
                                    'Support' => 'Get help and support for your needs',
                                    'My Courses' => 'Access your enrolled courses',
                                    'Assignments' => 'View and submit your assignments',
                                    'Certificates' => 'Download your earned certificates',
                                    'Profile' => 'Manage your account settings'
                                ];
                                echo $descriptions[$section['title']] ?? 'Access this section';
                                ?>
                            </p>
                        </div>
                        <div class="section-content">
                            <button class="btn-section">Access</button>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="section-card" style="text-align: center; padding: 40px;">
                    <div class="section-icon" style="margin-bottom: 16px;">
                        <i class="fas fa-circle-notch"></i>
                    </div>
                    <h3 class="section-title">Welcome!</h3>
                    <p class="section-description">Your dashboard sections will appear here when available.</p>
                    <button class="btn-section" onclick="window.location.reload()">Refresh</button>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Bottom Navigation -->
    <nav class="bottom-nav">
        <div class="nav-container">
            <div class="nav-grid">
                <a href="<?php echo base_url('dashboard'); ?>" class="nav-item active">
                    <div class="nav-icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <div class="nav-label">Home</div>
                </a>
                <a href="#" class="nav-item">
                    <div class="nav-icon">
                        <i class="fas fa-cog"></i>
                    </div>
                    <div class="nav-label">Settings</div>
                </a>
                <a href="#" class="nav-item">
                    <div class="nav-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="nav-label">Support</div>
                </a>
                <a href="<?php echo base_url('profile'); ?>" class="nav-item">
                    <div class="nav-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="nav-label">Profile</div>
                </a>
            </div>
        </div>
    </nav>

    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
