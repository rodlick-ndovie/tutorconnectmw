<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Payment Successful - TutorConnect Malawi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #10b981;
            --primary-light: #34d399;
            --primary-dark: #059669;
            --secondary-color: #E55C0D;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --accent-color: #0ea5e9;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --text-secondary: #64748b;
            --bg-primary: #f8fafc;
            --bg-secondary: #ffffff;
            --bg-accent: #ecfdf5;
            --border-color: #e2e8f0;
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --border-radius: 16px;
            --border-radius-lg: 20px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--bg-primary);
            color: var(--text-dark);
            overflow-x: hidden;
        }

        .app-container {
            max-width: 480px;
            margin: 0 auto;
            background: var(--bg-secondary);
            min-height: 100vh;
            position: relative;
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
            border-bottom: 1px solid var(--border-color);
        }

        @media (min-width: 768px) {
            .status-bar {
                display: none;
            }
        }

        .main-content {
            padding: 16px;
            padding-bottom: 100px;
        }

        .navbar {
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 100;
            padding: 0;
            height: 64px;
        }

        .navbar .px-4 {
            width: 100%;
        }

        .screen {
            padding: 20px;
            padding-bottom: 100px;
            background: var(--bg-primary);
            min-height: calc(100vh - 130px);
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

        .page-header {
            margin-bottom: 24px;
            text-align: center;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            font-size: 0.95rem;
            color: var(--text-secondary);
        }

        .result-card {
            background: var(--bg-secondary);
            border-radius: var(--border-radius);
            padding: 2rem;
            box-shadow: var(--shadow);
            text-align: center;
            max-width: 500px;
            margin: 0 auto;
        }

        .status-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }

        .result-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--success-color);
        }

        .result-message {
            font-size: 1rem;
            color: var(--text-light);
            margin-bottom: 2rem;
            line-height: 1.5;
        }

        .subscription-info {
            background: var(--bg-accent);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            text-align: left;
        }

        .subscription-info h4 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--text-dark);
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .info-label {
            font-weight: 500;
            color: var(--text-secondary);
        }

        .info-value {
            font-weight: 600;
            color: var(--text-dark);
        }

        .upgrade-notice {
            background: linear-gradient(135deg, #ecfdf5, #d1fae5);
            border: 1px solid #a7f3d0;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        .upgrade-notice .upgrade-icon {
            font-size: 1.5rem;
            color: var(--success-color);
            margin-bottom: 0.5rem;
        }

        .upgrade-notice .upgrade-title {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
        }

        .upgrade-notice .upgrade-text {
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            flex-direction: column;
        }

        .btn {
            padding: 0.875rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
            font-size: 1rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-secondary {
            background: var(--bg-primary);
            color: var(--text-dark);
            border: 1px solid var(--border-color);
        }

        .btn-secondary:hover {
            background: var(--text-light);
            color: white;
        }

        .email-notice {
            background: var(--bg-primary);
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1.5rem;
            text-align: center;
        }

        .email-notice i {
            color: var(--primary-color);
            margin-right: 0.5rem;
        }

        .validity-notice {
            background: rgba(245, 158, 11, 0.1);
            border-radius: 8px;
            padding: 0.75rem;
            margin-top: 1rem;
            text-align: center;
        }

        .validity-notice i {
            color: var(--warning-color);
            margin-right: 0.5rem;
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
            padding: 10px 14px;
            z-index: 100;
        }

        @media (min-width: 768px) {
            .bottom-nav { gap: 12px; }
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
                    <button class="btn p-0 border-0 bg-transparent me-3" onclick="window.location.href='<?php echo base_url('trainer/dashboard'); ?>'">
                        <i class="fas fa-arrow-left text-muted" style="font-size: 20px;"></i>
                    </button>
                    <div>
                        <div class="fw-semibold">Payment Success</div>
                        <small class="text-muted">Subscription activated</small>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="screen">
            <div class="page-header">
                <h1 class="page-title">🎉 Payment Successful!</h1>
                <p class="page-subtitle">Your subscription has been activated</p>
            </div>

            <div class="result-card">
                <div class="status-icon">
                    <i class="fas fa-check-circle"></i>
                </div>

                <h2 class="result-title">Payment Confirmed!</h2>
                <p class="result-message">Congratulations! Your payment has been processed successfully. Welcome to TutorConnect Premium!</p>

                <?php if (isset($subscription['upgrading_from']) && !empty($subscription['upgrading_from'])): ?>
                    <div class="upgrade-notice">
                        <div class="upgrade-icon">
                            <i class="fas fa-arrow-up"></i>
                        </div>
                        <div class="upgrade-title">Upgrade Complete!</div>
                        <div class="upgrade-text">
                            Your previous plan has been cancelled and you're now on the <?php echo htmlspecialchars($subscription['plan_name'] ?? 'new'); ?> plan.
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($subscription): ?>
                    <div class="subscription-info">
                        <h4>Subscription Details</h4>
                        <div class="info-row">
                            <span class="info-label">Plan:</span>
                            <span class="info-value">
                                <i class="fas fa-crown" style="color: var(--primary-color); margin-right: 0.5rem;"></i>
                                <?php echo htmlspecialchars($subscription['plan_name'] ?? 'Premium Plan'); ?>
                            </span>
                        </div>
                        <?php if (isset($subscription['payment_amount']) && $subscription['payment_amount'] > 0): ?>
                            <div class="info-row">
                                <span class="info-label">Amount Paid:</span>
                                <span class="info-value" style="color: var(--success-color); font-weight: 700;">
                                    MWK <?= number_format($subscription['payment_amount'], 0, ',', ',') ?>
                                </span>
                            </div>
                        <?php endif; ?>
                        <div class="info-row">
                            <span class="info-label">Status:</span>
                            <span class="info-value" style="color: var(--success-color);">
                                <i class="fas fa-check-circle" style="margin-right: 0.25rem;"></i>
                                Active
                            </span>
                        </div>
                        <?php if (isset($subscription['current_period_end'])): ?>
                            <div class="info-row">
                                <span class="info-label">Valid Until:</span>
                                <span class="info-value">
                                    <i class="fas fa-calendar-alt" style="color: var(--warning-color); margin-right: 0.25rem;"></i>
                                    <?= date('M j, Y', strtotime($subscription['current_period_end'])) ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <div class="action-buttons">
                    <a href="<?= base_url('trainer/dashboard') ?>" class="btn btn-primary">
                        <i class="fas fa-home"></i> Go to Dashboard
                    </a>
                    <a href="<?= base_url('trainer/subscription') ?>" class="btn btn-secondary">
                        <i class="fas fa-crown"></i> Manage Subscription
                    </a>
                </div>

                <div class="email-notice">
                    <i class="fas fa-envelope"></i>
                    A confirmation email has been sent to your registered email address.
                </div>

                <?php if (isset($subscription['current_period_end'])): ?>
                    <div class="validity-notice">
                        <i class="fas fa-clock"></i>
                        Your subscription is valid until <strong><?= date('M j, Y', strtotime($subscription['current_period_end'])) ?></strong>
                    </div>
                <?php endif; ?>
            </div>
        </main>

        <!-- Bottom Navigation -->
        <nav class="bottom-nav">
            <a href="<?= site_url('trainer/dashboard') ?>" class="nav-item">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>
            <a href="<?= site_url('trainer/subjects') ?>" class="nav-item">
                <i class="fas fa-book"></i>
                <span>Subjects</span>
            </a>
            <a href="<?= site_url('trainer/profile') ?>" class="nav-item">
                <i class="fas fa-user"></i>
                <span>Profile</span>
            </a>
            <a href="<?= site_url('trainer/subscription') ?>" class="nav-item active">
                <i class="fas fa-crown"></i>
                <span>Premium</span>
            </a>
        </nav>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
