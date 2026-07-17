<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Subscription Plan - Admin - TutorConnect Malawi</title>
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
            justify-content-between;
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

        .content-card {
            background: var(--bg-secondary);
            border-radius: var(--border-radius-lg);
            padding: 24px;
            box-shadow: var(--shadow);
            border: 1px solid rgba(0, 0, 0, 0.05);
            margin-bottom: 24px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e5e7eb;
            border-radius: var(--border-radius);
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--admin-primary);
            box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.1);
        }

        .form-textarea {
            resize: vertical;
            min-height: 120px;
        }

        .features-container {
            border: 1px solid #e5e7eb;
            border-radius: var(--border-radius);
            padding: 16px;
            background: #f9fafb;
        }

        .features-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 12px;
        }

        .features-textarea {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #d1d5db;
            border-radius: var(--border-radius);
            font-size: 14px;
            min-height: 120px;
            font-family: 'SF Mono', 'Monaco', 'Inconsolata', 'Ubuntu Mono', monospace;
        }

        .features-textarea:focus {
            outline: none;
            border-color: var(--admin-primary);
            box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.1);
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
            border: none;
            cursor: pointer;
        }

        .btn-admin:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(30, 64, 175, 0.3);
            color: white;
        }

        .btn-secondary {
            background: #6b7280;
            border: none;
            border-radius: var(--border-radius);
            color: white;
            padding: 12px 24px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-secondary:hover {
            background: #4b5563;
            color: white;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            margin-bottom: 16px;
        }

        .checkbox-input {
            width: 20px;
            height: 20px;
            margin-right: 12px;
        }

        .checkbox-label {
            font-size: 14px;
            color: var(--text-dark);
            user-select: none;
        }

        .help-text {
            background: #e0f2fe;
            border: 1px solid #06b6d4;
            border-radius: var(--border-radius);
            padding: 16px;
            margin-bottom: 16px;
        }

        .help-text .help-title {
            font-weight: 600;
            color: #0e7490;
            margin-bottom: 8px;
        }

        .help-text ul {
            margin: 0;
            padding-left: 20px;
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
                <a href="<?php echo base_url('admin/tutor-subscriptions'); ?>" class="nav-item">
                    <span class="nav-icon"><i class="fas fa-receipt"></i></span>
                    Tutor Subscriptions
                </a>
                <a href="<?php echo base_url('admin/services'); ?>" class="nav-item active">
                    <span class="nav-icon"><i class="fas fa-cogs"></i></span>
                    Services & Plans
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
                <h1 class="page-title">Add New Subscription Plan</h1>
                <p class="page-subtitle">Create a new subscription plan for tutors</p>
            </div>
            <a href="<?php echo base_url('admin/subscriptions'); ?>" class="btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Plans
            </a>
        </div>

        <!-- Help Text -->
        <div class="help-text">
            <div class="help-title">
                <i class="fas fa-info-circle me-2"></i>Tips for Creating Subscription Plans
            </div>
            <ul>
                <li><strong>Choose a clear plan name</strong> that describes the level of service (e.g., "Basic", "Premium", "Enterprise")</li>
                <li><strong>Set competitive pricing</strong> based on features and target market. Consider MK 5,000 - 15,000 per month</li>
                <li><strong>Define student limits</strong> clearly - use 999999 for unlimited students</li>
                <li><strong>List features clearly</strong> - one feature per line in the features box</li>
                <li><strong>Use sort order</strong> to control display order (lower numbers appear first)</li>
                <li><strong>Test the plan</strong> by keeping it inactive until you verify all settings</li>
            </ul>
        </div>

        <!-- Create Form -->
        <div class="content-card">
            <form action="<?php echo base_url('admin/subscriptions/create'); ?>" method="post">
                <?= csrf_field() ?>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name" class="form-label">Plan Name *</label>
                            <input type="text" class="form-control" id="name" name="name"
                                   placeholder="e.g., Premium Tutor Plan" required>
                            <small class="text-muted">Choose a name that clearly identifies this plan tier</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="price_monthly" class="form-label">Monthly Price (MK) *</label>
                            <input type="number" class="form-control" id="price_monthly" name="price_monthly"
                                   placeholder="10000" step="0.01" min="0" required>
                            <small class="text-muted">Price in Malawian Kwacha, charged monthly</small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="max_students" class="form-label">Maximum Students *</label>
                            <input type="number" class="form-control" id="max_students" name="max_students"
                                   placeholder="50" min="1" required>
                            <small class="text-muted">Enter 999999 for unlimited students</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sort_order" class="form-label">Display Order</label>
                            <input type="number" class="form-control" id="sort_order" name="sort_order"
                                   placeholder="1" min="0" value="0">
                            <small class="text-muted">Lower numbers appear first in plan lists</small>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control form-textarea" id="description" name="description"
                              rows="3" placeholder="Brief description of what this plan includes and its benefits..."></textarea>
                    <small class="text-muted">Describe the value proposition and key benefits of this plan</small>
                </div>

                <div class="form-group">
                    <label class="form-label">Plan Features</label>
                    <div class="features-container">
                        <div class="features-title">
                            <i class="fas fa-star text-warning me-2"></i>Features (one per line)
                        </div>
                        <textarea class="features-textarea" id="features" name="features" rows="8"
                                  placeholder="Enhanced profile listing with premium placement
Direct contact with unlimited students
Priority search results in all districts
Advanced analytics and reporting dashboard
Dedicated customer support
Monthly performance reports">Enhanced profile listing with premium placement
Direct contact with unlimited students
Priority search results in all districts
Advanced analytics and reporting dashboard</textarea>
                        <small class="text-muted mt-2 d-block">
                            Enter each feature on a new line. These will be displayed with checkmarks on the pricing page.
                        </small>
                    </div>
                </div>

                <div class="checkbox-container">
                    <input type="checkbox" class="checkbox-input" id="is_active" name="is_active" value="1">
                    <label for="is_active" class="checkbox-label">
                        <i class="fas fa-toggle-on text-success me-2"></i>
                        Plan is Active (visible to tutors)
                    </label>
                    <small class="text-muted d-block mt-1">Keep unchecked during testing, enable when plan is ready for use</small>
                </div>

                <div class="d-flex gap-3 mt-4">
                    <button type="submit" class="btn-admin">
                        <i class="fas fa-plus me-2"></i>Create Plan
                    </button>
                    <a href="<?php echo base_url('admin/subscriptions'); ?>" class="btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-format features textarea to show line numbers
        const featuresTextarea = document.getElementById('features');
        const originalPlaceholder = featuresTextarea.placeholder;

        function updateLineNumbers() {
            const lines = featuresTextarea.value.split('\n');
            // This is a simple visual trick - in a real implementation you'd add proper line numbers
        }

        featuresTextarea.addEventListener('input', updateLineNumbers);
        featuresTextarea.addEventListener('scroll', updateLineNumbers);

        // Validate form before submission
        document.querySelector('form').addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const price = parseFloat(document.getElementById('price_monthly').value);
            const maxStudents = parseInt(document.getElementById('max_students').value);

            if (!name) {
                alert('Plan name is required.');
                e.preventDefault();
                document.getElementById('name').focus();
                return false;
            }

            if (isNaN(price) || price < 0) {
                alert('Please enter a valid monthly price.');
                e.preventDefault();
                document.getElementById('price_monthly').focus();
                return false;
            }

            if (isNaN(maxStudents) || maxStudents < 1) {
                alert('Please enter a valid maximum number of students.');
                e.preventDefault();
                document.getElementById('max_students').focus();
                return false;
            }

            return true;
        });

        // Auto-generate sort order based on existing plans (optional enhancement)
        document.addEventListener('DOMContentLoaded', function() {
            fetch('<?= site_url('admin/subscriptions/get-next-sort-order') ?>', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.nextSortOrder && !document.getElementById('sort_order').value) {
                    document.getElementById('sort_order').value = data.nextSortOrder;
                }
            })
            .catch(err => {
                // Silently fail, user can set manually
            });
        });
    </script>
</body>
</html>
