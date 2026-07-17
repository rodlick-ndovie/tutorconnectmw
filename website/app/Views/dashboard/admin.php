<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Admin Dashboard - TutorConnect Malawi'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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

        .nav-icon {
            width: 20px;
            text-align: center;
            margin-right: 12px;
        }

        .nav-item.logout-item {
            color: var(--danger-color) !important;
            margin-top: auto;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            margin-top: 40px;
            padding-top: 20px;
        }

        .nav-item.logout-item:hover {
            color: #dc2626 !important;
            background: rgba(239, 68, 68, 0.1);
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

        .dashboard-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .dashboard-title {
            font-size: 32px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .dashboard-subtitle {
            font-size: 16px;
            color: var(--text-light);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: var(--bg-secondary);
            border-radius: var(--border-radius-lg);
            padding: 24px;
            box-shadow: var(--shadow);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px -5px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            font-size: 20px;
        }

        .stat-number {
            font-size: 32px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 14px;
            color: var(--text-light);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .action-card {
            background: var(--bg-secondary);
            border-radius: var(--border-radius-lg);
            padding: 24px;
            box-shadow: var(--shadow);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
        }

        .action-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px -5px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .action-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--border-radius);
            background: linear-gradient(135deg, var(--admin-primary), var(--admin-secondary));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 16px;
        }

        .action-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 4px;
        }

        .action-description {
            font-size: 14px;
            color: var(--text-light);
            margin: 0;
        }

        .recent-activity {
            background: var(--bg-secondary);
            border-radius: var(--border-radius-lg);
            padding: 24px;
            box-shadow: var(--shadow);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .section-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 20px;
        }

        .activity-item {
            display: flex;
            align-items: center;
            padding: 16px 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--admin-primary), var(--admin-secondary));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
            margin-right: 16px;
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
        }

        .activity-time {
            font-size: 12px;
            color: var(--text-light);
        }

        .header-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
        }

        .logout-btn {
            background: linear-gradient(135deg, var(--danger-color), #dc2626);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: var(--border-radius);
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px -2px rgba(239, 68, 68, 0.3);
            color: white;
            text-decoration: none;
        }

        .logout-btn i {
            font-size: 16px;
        }
    </style>
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
                <a href="<?php echo base_url('admin/dashboard'); ?>" class="nav-item active">
                    <span class="nav-icon"><i class="fas fa-chart-line"></i></span>
                    Dashboard
                </a>
            </div>
            <div class="nav-section">
                <div class="nav-section-title">Management</div>
                <a href="<?php echo base_url('admin/users'); ?>" class="nav-item">
                    <span class="nav-icon"><i class="fas fa-users"></i></span>
                    Users
                </a>
                <a href="<?php echo base_url('admin/courses'); ?>" class="nav-item">
                    <span class="nav-icon"><i class="fas fa-book"></i></span>
                    Courses
                </a>
                <a href="<?php echo base_url('admin/trainers'); ?>" class="nav-item">
                    <span class="nav-icon"><i class="fas fa-chalkboard-teacher"></i></span>
                    Trainers
                </a>
                <a href="<?php echo base_url('admin/students'); ?>" class="nav-item">
                    <span class="nav-icon"><i class="fas fa-user-graduate"></i></span>
                    Students
                </a>
            </div>
            <div class="nav-section">
                <div class="nav-section-title">Business</div>
                <a href="<?php echo base_url('admin/services'); ?>" class="nav-item">
                    <span class="nav-icon"><i class="fas fa-cogs"></i></span>
                    Services
                </a>
                <a href="<?php echo base_url('admin/orders'); ?>" class="nav-item">
                    <span class="nav-icon"><i class="fas fa-shopping-cart"></i></span>
                    Orders
                </a>
            </div>
            <div class="nav-section">
                <div class="nav-section-title">Content</div>
                <a href="<?php echo base_url('admin/blog'); ?>" class="nav-item">
                    <span class="nav-icon"><i class="fas fa-blog"></i></span>
                    Blog
                </a>
                <a href="<?php echo base_url('admin/settings'); ?>" class="nav-item">
                    <span class="nav-icon"><i class="fas fa-cog"></i></span>
                    Settings
                </a>
            </div>
            <div class="nav-section">
                <a href="<?php echo base_url('logout'); ?>" class="nav-item logout-item">
                    <span class="nav-icon"><i class="fas fa-sign-out-alt"></i></span>
                    Logout
                </a>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header-bar">
            <div>
                <h1 class="page-title"><?php echo $dashboard_title; ?></h1>
                <p class="page-subtitle">Welcome back, <?php echo $user['first_name'] ?? $user['username']; ?>! Here's what's happening with your platform.</p>
            </div>
            <a href="<?php echo base_url('logout'); ?>" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </a>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6, #1e40af);">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-number"><?php echo number_format($stats['total_users'] ?? 0); ?></div>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="stat-number"><?php echo number_format($stats['total_students'] ?? 0); ?></div>
                <div class="stat-label">Students</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="stat-number"><?php echo number_format($stats['total_trainers'] ?? 0); ?></div>
                <div class="stat-label">Trainers</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stat-number"><?php echo number_format($stats['active_users'] ?? 0); ?></div>
                <div class="stat-label">Active Users</div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <a href="<?php echo base_url('admin/users'); ?>" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="action-title">Manage Users</div>
                <div class="action-description">View and manage all platform users</div>
            </a>
            <a href="<?php echo base_url('admin/courses'); ?>" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-book"></i>
                </div>
                <div class="action-title">Course Management</div>
                <div class="action-description">Oversee courses and curriculum</div>
            </a>
            <a href="<?php echo base_url('admin/orders'); ?>" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="action-title">Order Management</div>
                <div class="action-description">Handle service orders and payments</div>
            </a>
            <a href="<?php echo base_url('admin/settings'); ?>" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-cog"></i>
                </div>
                <div class="action-title">System Settings</div>
                <div class="action-description">Configure platform settings</div>
            </a>
        </div>

        <!-- Recent Activity -->
        <div class="recent-activity">
            <h3 class="section-title">Recent Activity</h3>
            <div class="activity-item">
                <div class="activity-avatar">N</div>
                <div class="activity-content">
                    <div class="activity-title">New user registration: Peter Johnson joined as a student</div>
                    <div class="activity-time">2 hours ago</div>
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-avatar">C</div>
                <div class="activity-content">
                    <div class="activity-title">Course enrollment: Sarah Wilson enrolled in "Web Development"</div>
                    <div class="activity-time">4 hours ago</div>
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-avatar">P</div>
                <div class="activity-content">
                    <div class="activity-title">Payment received: MWK 35,000 for Database Training</div>
                    <div class="activity-time">1 day ago</div>
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-avatar">A</div>
                <div class="activity-content">
                    <div class="activity-title">Assignment submitted: Mike Thompson submitted final project</div>
                    <div class="activity-time">2 days ago</div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

