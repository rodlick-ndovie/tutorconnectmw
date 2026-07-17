<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Payment Proof Review - TutorConnect Malawi</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('favicon.ico') ?>">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
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

            --header-height: 70px;
            --bottom-nav-height: 70px;
            --border-radius: 16px;
            --border-radius-sm: 12px;
            --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            --card-shadow-hover: 0 10px 25px rgba(0, 0, 0, 0.12);
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
            min-height: 100vh;
            padding-bottom: env(safe-area-inset-bottom);
        }

        .app-container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            padding-top: var(--header-height);
            padding-bottom: var(--bottom-nav-height);
        }

        .top-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--header-height);
            background: white;
            border-bottom: 1px solid var(--gray-200);
            padding: 0 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 100;
            backdrop-filter: blur(12px);
            background-color: rgba(255, 255, 255, 0.95);
            padding-top: env(safe-area-inset-top);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .app-logo {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: var(--border-radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
        }

        .page-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--dark);
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .notification-btn {
            position: relative;
            background: none;
            border: none;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            color: var(--gray-600);
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .notification-btn:hover {
            background-color: var(--gray-100);
        }

        .notification-badge {
            position: absolute;
            top: 6px;
            right: 6px;
            background-color: var(--danger);
            color: white;
            font-size: 0.65rem;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1rem;
            border: 3px solid white;
            box-shadow: var(--card-shadow);
        }

        .main-content {
            flex: 1;
            padding: 1rem;
            overflow-y: auto;
        }

        .section-title {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }

        .section-subtitle {
            font-size: 0.95rem;
            color: var(--gray-500);
            margin-bottom: 1.5rem;
        }

        .info-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--gray-200);
        }

        .info-card-header {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .info-card-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.25rem;
        }

        .info-card-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--dark);
            margin: 0;
        }

        .info-card-description {
            font-size: 0.9rem;
            color: var(--gray-600);
        }

        .payment-proof-container {
            background: white;
            border-radius: var(--border-radius);
            padding: 2rem;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--gray-200);
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .payment-proof-image {
            max-width: 100%;
            max-height: 500px;
            border-radius: var(--border-radius-sm);
            box-shadow: var(--card-shadow);
            margin-bottom: 1rem;
        }

        .payment-proof-pdf {
            width: 100%;
            height: 500px;
            border: 1px solid var(--gray-300);
            border-radius: var(--border-radius-sm);
        }

        .btn-group-custom {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
        }

        .btn-primary {
            padding: 0.875rem 1.5rem;
            background: var(--primary);
            color: white;
            border: 2px solid var(--primary);
            border-radius: var(--border-radius-sm);
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: var(--transition);
            cursor: pointer;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .btn-success {
            padding: 0.875rem 1.5rem;
            background: var(--success);
            color: white;
            border: 2px solid var(--success);
            border-radius: var(--border-radius-sm);
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: var(--transition);
            cursor: pointer;
            text-decoration: none;
        }

        .btn-success:hover {
            background: #059669;
            border-color: #059669;
        }

        .btn-danger {
            padding: 0.875rem 1.5rem;
            background: var(--danger);
            color: white;
            border: 2px solid var(--danger);
            border-radius: var(--border-radius-sm);
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: var(--transition);
            cursor: pointer;
            text-decoration: none;
        }

        .btn-danger:hover {
            background: #dc2626;
            border-color: #dc2626;
        }

        .btn-secondary {
            padding: 0.875rem 1.5rem;
            background: var(--gray-100);
            color: var(--gray-700);
            border: 2px solid var(--gray-200);
            border-radius: var(--border-radius-sm);
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: var(--transition);
        }

        .btn-secondary:hover {
            background: var(--gray-200);
            border-color: var(--gray-300);
        }

        .payment-details {
            background: var(--gray-50);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid var(--gray-200);
        }

        .detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .detail-label {
            font-weight: 600;
            color: var(--gray-700);
        }

        .detail-value {
            color: var(--dark);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.375rem 0.75rem;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .status-info { background-color: #dbeafe; color: #1e40af; }
        .status-success { background-color: #d1fae5; color: #065f46; }
        .status-warning { background-color: #fef3c7; color: #92400e; }
        .status-danger { background-color: #fee2e2; color: #991b1b; }

        @media (max-width: 768px) {
            .main-content {
                padding: 0.75rem;
            }
            .btn-group-custom {
                flex-direction: column;
            }
            .btn-primary, .btn-success, .btn-danger, .btn-secondary {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Top Header -->
        <header class="top-header">
            <div class="header-left">
                <a href="javascript:history.back()" class="text-gray-600 mr-3">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div class="app-logo">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <div class="page-title">Payment Proof Review</div>
            </div>

            <div class="header-right">
                <button class="notification-btn">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">3</span>
                </button>
                <div class="user-avatar">
                    A
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <h1 class="section-title">Payment Proof Review</h1>
            <p class="section-subtitle">Review subscription payment proof and take action</p>

            <!-- User & Plan Information -->
            <div class="info-card">
                <div class="info-card-header">
                    <div class="info-card-icon" style="background-color: #dbeafe;">
                        <i class="fas fa-user text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="info-card-title">Subscriber Information</h3>
                        <p class="info-card-description">
                            <?= esc($user['first_name'] . ' ' . $user['last_name']) ?> -
                            <?= esc($user['email']) ?>
                        </p>
                    </div>
                </div>

                <div class="payment-details">
                    <div class="detail-row">
                        <span class="detail-label">Plan:</span>
                        <span class="detail-value">
                            <?= esc($plan['name']) ?> (MK<?= number_format($plan['price_monthly']) ?>/month)
                        </span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Payment Method:</span>
                        <span class="detail-value">
                            <?= esc(ucwords(str_replace('_', ' ', $subscription['payment_method']))) ?>
                        </span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Amount Paid:</span>
                        <span class="detail-value">MK<?= number_format($subscription['payment_amount']) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Payment Date:</span>
                        <span class="detail-value">
                            <?= date('M j, Y \a\t g:i A', strtotime($subscription['payment_date'])) ?>
                        </span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Reference:</span>
                        <span class="detail-value">
                            <?= esc($subscription['payment_reference'] ?: 'Not provided') ?>
                        </span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Current Status:</span>
                        <span class="status-badge status-info">
                            <i class="fas fa-clock mr-1"></i>
                            Pending Approval
                        </span>
                    </div>
                </div>
            </div>

            <!-- Payment Proof Display -->
            <?php if ($proof_file): ?>
                <div class="payment-proof-container">
                    <h3 class="mb-4">Payment Proof Document</h3>

                    <?php
                    $file_path = FCPATH . $proof_file;
                    $file_extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));

                    if (file_exists($file_path)):
                        if (in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif'])):
                    ?>
                        <img src="<?= base_url($proof_file) ?>" alt="Payment Proof" class="payment-proof-image">
                        <p class="text-muted mt-3">
                            <i class="fas fa-image mr-1"></i>
                            Image proof uploaded on <?= date('M j, Y', strtotime($subscription['created_at'])) ?>
                        </p>
                    <?php elseif ($file_extension === 'pdf'): ?>
                        <iframe src="<?= base_url($proof_file) ?>" class="payment-proof-pdf"></iframe>
                        <p class="text-muted mt-3">
                            <i class="fas fa-file-pdf mr-1"></i>
                            PDF proof uploaded on <?= date('M j, Y', strtotime($subscription['created_at'])) ?>
                        </p>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-file text-6xl text-gray-400 mb-4"></i>
                            <p class="text-muted">File type: <?= strtoupper($file_extension) ?></p>
                            <a href="<?= base_url($proof_file) ?>" target="_blank" class="btn btn-primary mt-3">
                                <i class="fas fa-download mr-2"></i>Download File
                            </a>
                        </div>
                    <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-exclamation-triangle text-6xl text-warning mb-4"></i>
                            <h4 class="text-warning mb-3">Payment Proof Not Found</h4>
                            <p class="text-muted">The payment proof file could not be located on the server.</p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="info-card">
                    <div class="info-card-header">
                        <div class="info-card-icon" style="background-color: #fef3c7;">
                            <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                        </div>
                        <div>
                            <h3 class="info-card-title">No Payment Proof Uploaded</h3>
                            <p class="info-card-description">This subscription does not have an associated payment proof file.</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Action Buttons -->
            <div class="btn-group-custom">
                <form method="POST" action="<?= site_url('admin/tutor-subscriptions/update-status/' . $subscription['id']) ?>" style="display: inline;">
                    <input type="hidden" name="status" value="active">
                    <button type="submit" class="btn-success" onclick="return confirm('Are you sure you want to approve this payment? The subscription will become active immediately.')">
                        <i class="fas fa-check"></i>
                        Approve Payment
                    </button>
                </form>

                <form method="POST" action="<?= site_url('admin/tutor-subscriptions/update-status/' . $subscription['id']) ?>" style="display: inline;">
                    <input type="hidden" name="status" value="cancelled">
                    <button type="submit" class="btn-danger" onclick="return confirm('Are you sure you want to reject this payment? The subscription will be cancelled.')">
                        <i class="fas fa-times"></i>
                        Reject Payment
                    </button>
                </form>

                <a href="<?= site_url('admin/tutor-subscriptions') ?>" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Back to Subscriptions
                </a>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
