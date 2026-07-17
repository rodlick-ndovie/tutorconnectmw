<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Management - Admin - TutorConnect Malawi</title>
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

        .table-responsive {
            background: var(--bg-secondary);
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            background: var(--bg-dropdown);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            font-weight: 600;
            font-size: 14px;
            padding: 16px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-light);
        }

        .table td {
            padding: 16px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background: rgba(0, 0, 0, 0.02);
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 14px;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
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
                <a href="<?php echo base_url('admin/dashboard'); ?>" class="nav-item">
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
                <a href="<?php echo base_url('admin/courses'); ?>" class="nav-item active">
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
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header-bar">
            <div>
                <h1 class="page-title">Course Management</h1>
                <p class="page-subtitle">Manage courses, curriculum, and course assignments</p>
            </div>
            <a href="#" class="btn-admin">
                <i class="fas fa-plus me-2"></i>Add New Course
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6, #1e40af);">
                    <i class="fas fa-book-open"></i>
                </div>
                <div class="stat-number"><?php echo number_format($stats['total_courses'] ?? 0); ?></div>
                <div class="stat-label">Total Courses</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                    <i class="fas fa-play"></i>
                </div>
                <div class="stat-number"><?php echo number_format($stats['active_courses'] ?? 0); ?></div>
                <div class="stat-label">Active Courses</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-number"><?php echo number_format($stats['total_enrollments'] ?? 0); ?></div>
                <div class="stat-label">Enrollments</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-number"><?php echo number_format($stats['avg_rating'] ?? 0, 1); ?></div>
                <div class="stat-label">Avg Rating</div>
            </div>
        </div>

        <!-- Courses Table -->
        <div class="content-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">All Courses</h4>
                <div class="d-flex gap-2">
                    <input type="text" class="form-control" placeholder="Search courses..." style="width: 250px;">
                    <select class="form-select" style="width: 150px;">
                        <option>All Categories</option>
                        <option>Mathematics</option>
                        <option>Science</option>
                        <option>Languages</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Course</th>
                            <th>Instructor</th>
                            <th>Students</th>
                            <th>Rating</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div style="width: 48px; height: 48px; background: var(--admin-primary); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; margin-right: 12px;">
                                        <i class="fas fa-calculator"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight: 600; color: var(--text-dark);">Advanced Mathematics</div>
                                        <div style="font-size: 12px; color: var(--text-light);">Grade 10-12</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <div style="font-weight: 600;">Dr. John Banda</div>
                                    <div style="font-size: 12px; color: var(--text-light);">Mathematics Professor</div>
                                </div>
                            </td>
                            <td><?php echo number_format(45); ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-star text-warning me-1"></i>
                                    <?php echo number_format(4.5, 1); ?>
                                </div>
                            </td>
                            <td><strong>MWK <?php echo number_format(25000); ?></strong></td>
                            <td><span class="badge bg-success">Active</span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div style="width: 48px; height: 48px; background: var(--success-color); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; margin-right: 12px;">
                                        <i class="fas fa-atom"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight: 600; color: var(--text-dark);">Chemistry Fundamentals</div>
                                        <div style="font-size: 12px; color: var(--text-light);">Grade 11-12</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <div style="font-weight: 600;">Ms. Mary Phiri</div>
                                    <div style="font-size: 12px; color: var(--text-light);">Chemistry Teacher</div>
                                </div>
                            </td>
                            <td><?php echo number_format(32); ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-star text-warning me-1"></i>
                                    <?php echo number_format(4.2, 1); ?>
                                </div>
                            </td>
                            <td><strong>MWK <?php echo number_format(30000); ?></strong></td>
                            <td><span class="badge bg-success">Active</span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <nav aria-label="Course pagination" class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
