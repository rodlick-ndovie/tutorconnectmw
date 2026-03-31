<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Payment Failed - TutorConnect Malawi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #ef4444;
            --primary-light: #f87171;
            --primary-dark: #dc2626;
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
            --bg-accent: #fef2f2;
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
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
        }

        .result-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--danger-color);
        }

        .result-message {
            font-size: 1rem;
            color: var(--text-light);
            margin-bottom: 2rem;
            line-height: 1.5;
        }

        .error-details {
            background: var(--bg-accent);
            border: 1px solid #fecaca;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 2rem;
            text-align: left;
        }

        .error-details .error-icon {
            color: var(--danger-color);
            margin-right: 0.5rem;
        }

        .error-details .error-title {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
        }

        .error-details .error-text {
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .subscription-info {
            background: var(--bg-primary);
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

        .help-section {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            text-align: left;
        }

        .help-section .help-icon {
            color: var(--warning-color);
            margin-right: 0.5rem;
        }

        .help-section .help-title {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }

        .help-section .help-list {
            margin: 0;
            padding-left: 1rem;
        }

        .help-section .help-list li {
            margin-bottom: 0.5rem;
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
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
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

        .support-notice {
            background: var(--bg-primary);
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1.5rem;
            text-align: center;
        }

        .support-notice i {
            color: var(--accent-color);
            margin-right: 0.5rem;
        }

        .security-notice {
            background: rgba(16, 185, 129, 0.1);
            border-radius: 8px;
            padding: 0.75rem;
            margin-top: 1rem;
            text-align: center;
        }

        .security-notice i {
            color: var(--success-color);
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
                        <div class="fw-semibold">Payment Failed</div>
                        <small class="text-muted">Transaction unsuccessful</small>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="screen">
            <div class="page-header">
                <h1 class="page-title">❌ Payment Failed</h1>
                <p class="page-subtitle">Unable to process your payment</p>
            </div>

            <div class="result-card">
                <div class="status-icon">
                    <i class="fas fa-times-circle"></i>
                </div>

                <h2 class="result-title">Payment Unsuccessful</h2>
                <p class="result-message">We're sorry, but your payment could not be processed at this time. Please try again or contact support.</p>

                <div class="error-details">
                    <div class="d-flex align-items-start">
                        <i class="fas fa-exclamation-triangle error-icon"></i>
                        <div>
                            <div class="error-title">Payment Error</div>
                            <div class="error-text"><?php echo htmlspecialchars($message ?? 'Your payment could not be processed at this time.'); ?></div>
                        </div>
                    </div>
                </div>

                <?php if ($subscription): ?>
                    <div class="subscription-info">
                        <h4>Payment Details</h4>
                        <div class="info-row">
                            <span class="info-label">Plan:</span>
                            <span class="info-value">
                                <i class="fas fa-tag" style="color: var(--text-secondary); margin-right: 0.5rem;"></i>
                                <?php echo htmlspecialchars($subscription['plan_name'] ?? 'Subscription Plan'); ?>
                            </span>
                        </div>
                        <?php if (isset($subscription['payment_amount']) && $subscription['payment_amount'] > 0): ?>
                            <div class="info-row">
                                <span class="info-label">Amount:</span>
                                <span class="info-value" style="color: var(--text-dark);">
                                    MWK <?= number_format($subscription['payment_amount'], 0, ',', ',') ?>
                                </span>
                            </div>
                        <?php endif; ?>
                        <div class="info-row">
                            <span class="info-label">Status:</span>
                            <span class="info-value" style="color: var(--danger-color);">
                                <i class="fas fa-times-circle" style="margin-right: 0.25rem;"></i>
                                Failed
                            </span>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="help-section">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-question-circle help-icon"></i>
                        <div class="help-title">What can I do?</div>
                    </div>
                    <ul class="help-list">
                        <li>Try the payment again with the same or different payment method</li>
                        <li>Check that you have sufficient funds in your account</li>
                        <li>Ensure your mobile money PIN is correct</li>
                        <li>Contact your bank or mobile money provider if issues persist</li>
                        <li>Try using a different payment method (card, bank transfer, etc.)</li>
                    </ul>
                </div>

                <div class="action-buttons">
                    <a href="<?= base_url('trainer/subscription') ?>" class="btn btn-primary">
                        <i class="fas fa-redo"></i> Try Again
                    </a>
                    <a href="mailto:info@uprisemw.com" class="btn btn-secondary">
                        <i class="fas fa-envelope"></i> Contact Support
                    </a>
                </div>

                <div class="support-notice">
                    <i class="fas fa-headset"></i>
                    Need help? Contact our support team at <strong>info@uprisemw.com</strong>
                </div>

                <div class="security-notice">
                    <i class="fas fa-shield-alt"></i>
                    Your payment information is secure and encrypted.
                </div>
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
