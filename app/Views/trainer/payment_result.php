<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Payment Status - TutorConnect Malawi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #E55C0D;
            --primary-light: #10b981;
            --primary-dark: #C94609;
            --secondary-color: #C94609;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --accent-color: #0ea5e9;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --text-secondary: #64748b;
            --bg-primary: #f8fafc;
            --bg-secondary: #ffffff;
            --bg-accent: #f0f9ff;
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
            padding-bottom: 100px; /* Space for bottom nav */
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
        }

        .status-success { background: rgba(16, 185, 129, 0.1); color: var(--success-color); }
        .status-processing { background: rgba(245, 158, 11, 0.1); color: var(--warning-color); }
        .status-failed { background: rgba(239, 68, 68, 0.1); color: var(--danger-color); }
        .status-error { background: rgba(239, 68, 68, 0.1); color: var(--danger-color); }

        .result-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .result-message {
            font-size: 1rem;
            color: var(--text-light);
            margin-bottom: 2rem;
            line-height: 1.5;
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
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(229, 92, 13, 0.3);
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

        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
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
                        <div class="fw-semibold">Payment Status</div>
                        <small class="text-muted">Transaction result</small>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="screen">
            <div class="page-header">
                <h1 class="page-title">Payment Status</h1>
                <p class="page-subtitle">Your transaction result</p>
            </div>

            <div class="result-card">
                <?php if ($status === 'success'): ?>
                    <div class="status-icon status-success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h2 class="result-title">Payment Successful!</h2>
                    <p class="result-message">🎉 Congratulations! Your payment has been processed successfully. Welcome to TutorConnect Premium!</p>

                    <?php if ($subscription): ?>
                        <div class="subscription-info">
                            <h4>Subscription Details</h4>
                            <div class="info-row">
                                <span class="info-label">Plan:</span>
                                <span class="info-value">Premium Subscription</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Amount:</span>
                                <span class="info-value">MWK <?= number_format($subscription['payment_amount'], 0, ',', ',') ?></span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Status:</span>
                                <span class="info-value" style="color: var(--success-color);">Active</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Valid Until:</span>
                                <span class="info-value"><?= date('M j, Y', strtotime($subscription['current_period_end'])) ?></span>
                            </div>
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

                <?php elseif ($status === 'processing'): ?>
                    <div class="status-icon status-processing">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h2 class="result-title">Processing Payment</h2>
                    <p class="result-message">Your payment is being processed. This may take a few moments...</p>

                    <div id="status-checker" style="margin: 2rem 0;">
                        <div class="loading-spinner"></div>
                        <p style="margin-top: 1rem; color: var(--text-light); font-size: 0.9rem;">
                            Checking payment status...
                        </p>
                    </div>

                    <div class="action-buttons">
                        <a href="<?= base_url('trainer/dashboard') ?>" class="btn btn-secondary">
                            <i class="fas fa-home"></i> Return to Dashboard
                        </a>
                    </div>

                <?php elseif ($status === 'failed'): ?>
                    <div class="status-icon status-failed">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <h2 class="result-title">Payment Failed</h2>
                    <p class="result-message">We're sorry, but your payment could not be processed. Please try again or contact support.</p>

                    <div class="action-buttons">
                        <a href="<?= base_url('trainer/subscription') ?>" class="btn btn-primary">
                            <i class="fas fa-redo"></i> Try Again
                        </a>
                        <a href="mailto:info@uprisemw.com" class="btn btn-secondary">
                            <i class="fas fa-envelope"></i> Contact Support
                        </a>
                    </div>

                <?php else: // error ?>
                    <div class="status-icon status-error">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h2 class="result-title">Payment Error</h2>
                    <p class="result-message">An error occurred while processing your payment. Please contact support for assistance.</p>

                    <div class="action-buttons">
                        <a href="<?= base_url('trainer/subscription') ?>" class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i> Back to Plans
                        </a>
                        <a href="mailto:info@uprisemw.com" class="btn btn-secondary">
                            <i class="fas fa-envelope"></i> Contact Support
                        </a>
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

    <?php if ($enablePolling && $txRef): ?>
    <script>
        // Poll for payment status updates
        let pollInterval;

        function checkPaymentStatus() {
            fetch('<?= base_url('trainer/checkout/checkPaymentStatus') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: 'tx_ref=<?= urlencode($txRef) ?>'
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'verified' && data.subscription_status === 'active') {
                    // Payment confirmed - reload page to show success
                    clearInterval(pollInterval);
                    location.reload();
                } else if (data.status === 'failed') {
                    // Payment failed - reload page to show error
                    clearInterval(pollInterval);
                    location.reload();
                }
                // Continue polling for other statuses
            })
            .catch(error => {
                console.error('Status check error:', error);
            });
        }

        // Start polling every 5 seconds
        pollInterval = setInterval(checkPaymentStatus, 5000);

        // Initial check
        checkPaymentStatus();

        // Clear interval when page unloads
        window.addEventListener('beforeunload', function() {
            if (pollInterval) {
                clearInterval(pollInterval);
            }
        });
    </script>
    <?php endif; ?>
</body>
</html>
