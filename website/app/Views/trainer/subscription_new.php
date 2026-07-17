<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Subscription Plans - TutorConnect Malawi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #059669;
            --primary-light: #10b981;
            --primary-dark: #047857;
            --secondary-color: #047857;
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

        .navbar .d-flex.gap-2 {
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
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 16px;
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

        .current-plan-card {
            background: var(--bg-secondary);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--shadow);
            margin-bottom: 1.5rem;
            border-left: 5px solid var(--primary-color);
        }

        .plan-badge {
            width: 60px;
            height: 60px;
            background: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.8rem;
        }

        .plans-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        @media (min-width: 768px) {
            .plans-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1.5rem;
            }
        }

        .plan-card {
            background: var(--bg-secondary);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--shadow);
            text-align: center;
            position: relative;
            transition: all 0.3s ease;
        }

        .plan-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }

        .plan-card.featured {
            border: 2px solid var(--primary-color);
        }

        .plan-card.free-plan {
            border: 2px solid var(--success-color);
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.05), rgba(16, 185, 129, 0.02));
        }

        .plan-name {
            font-size: 1.4rem;
            font-weight: 700;
            margin: 1rem 0 0.5rem;
        }

        .plan-price {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary-color);
            margin: 0.5rem 0;
        }

        .plan-card.free-plan .plan-price {
            color: var(--success-color);
        }

        .plan-period {
            color: var(--text-light);
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        .plan-features {
            text-align: left;
            margin: 1.5rem 0;
            padding: 0;
            list-style: none;
        }

        .plan-features li {
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }

        .plan-button {
            width: 100%;
            padding: 0.875rem;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s ease;
            border: none;
        }

        .plan-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .billing-card {
            background: var(--bg-secondary);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--shadow);
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 480px;
            background: var(--bg-secondary);
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: space-around;
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

        .nav-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: var(--text-light);
            transition: all 0.3s ease;
            padding: 4px;
            border-radius: 12px;
        }

        .nav-item:hover {
            background: rgba(0, 0, 0, 0.05);
        }

        .nav-item.active {
            color: var(--primary-color);
        }

        .nav-icon {
            font-size: 22px;
            margin-bottom: 4px;
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
                    <button class="btn p-0 border-0 bg-transparent me-3" onclick="window.location.href='<?php echo base_url('dashboard'); ?>'">
                        <i class="fas fa-arrow-left text-muted" style="font-size: 20px;"></i>
                    </button>
                    <div>
                        <div class="fw-semibold">Subscription Plans</div>
                        <small class="text-muted">Manage your subscription</small>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn p-0 border-0 bg-transparent">
                        <i class="fas fa-bell text-muted" style="font-size: 20px;"></i>
                    </button>
                    <button class="btn p-0 border-0 bg-transparent" onclick="window.location.href='<?php echo base_url('logout'); ?>'">
                        <i class="fas fa-sign-out-alt text-muted" style="font-size: 20px;"></i>
                    </button>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="screen">
            <div class="page-header">
                <h1 class="page-title">Subscription Plans</h1>
                <p class="page-subtitle">Choose the plan that best fits your tutoring needs</p>
            </div>

            <!-- Current Plan -->
            <?php if(isset($current_subscription) && $current_subscription): ?>
            <div class="current-plan-card">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="plan-badge">
                            <i class="fas fa-crown"></i>
                        </div>
                        <div>
                            <h4 class="mb-1"><?= htmlspecialchars($current_subscription['plan_name']) ?></h4>
                            <p class="text-muted mb-0">Renews on <?= date('M d, Y', strtotime($current_subscription['next_billing_date'])) ?></p>
                        </div>
                    </div>
                    <div class="text-end">
                        <p class="mb-1"><strong>MWK <?= number_format($current_subscription['amount']) ?> / month</strong></p>
                        <div class="d-flex gap-2 flex-wrap justify-content-end">
                            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#upgradeModal">
                                Upgrade
                            </button>
                            <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#cancelModal">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Available Plans -->
            <div class="plans-grid">
                <?php foreach($available_plans as $index => $plan): ?>
                <?php
                    $is_current = isset($current_subscription) && $current_subscription &&
                                $current_subscription['plan_id'] == $plan['id'];
                    $is_free = $plan['price_monthly'] == 0;
                ?>
                <div class="plan-card <?= $index === 1 ? 'featured' : '' ?> <?= $is_free ? 'free-plan' : '' ?>">
                    <?php if($index === 1): ?>
                        <div class="position-absolute top-0 start-50 translate-middle-x badge rounded-pill bg-primary" style="top: -12px;">
                            Most Popular
                        </div>
                    <?php elseif($is_free): ?>
                        <div class="position-absolute top-0 start-50 translate-middle-x badge rounded-pill bg-success" style="top: -12px;">
                            <i class="fas fa-gift me-1"></i>FREE
                        </div>
                    <?php endif; ?>
                    <h4 class="plan-name"><?= htmlspecialchars($plan['name']) ?></h4>
                    <div class="plan-price">
                        <?php if($plan['price_monthly'] > 0): ?>
                            MWK <?= number_format($plan['price_monthly']) ?>
                        <?php else: ?>
                            FREE
                        <?php endif; ?>
                    </div>
                    <?php if($plan['price_monthly'] > 0): ?>
                        <div class="plan-period">per month</div>
                    <?php endif; ?>

                    <ul class="plan-features">
                        <li><i class="fas fa-check text-success"></i> <?= $plan['features']['students'] ?? 'Up to 5 students' ?></li>
                        <li><i class="fas fa-check text-success"></i> <?= $plan['features']['sessions'] ?? 'Up to 20 sessions' ?></li>
                        <li><i class="fas fa-check text-success"></i> <?= $plan['features']['inquiries'] ? 'Student Inquiries' : 'Limited Inquiries' ?></li>
                        <li><i class="fas fa-check text-success"></i> <?= $plan['features']['analytics'] ? 'Advanced Analytics' : 'Basic Analytics' ?></li>
                        <li><i class="fas fa-check text-success"></i> <?= $plan['features']['support'] ? 'Priority Support' : 'Standard Support' ?></li>
                    </ul>

                    <?php if($is_current): ?>
                        <button class="plan-button btn btn-secondary" disabled>Current Plan</button>
                    <?php else: ?>
                        <button type="button" class="plan-button btn btn-primary subscribe-btn"
                                data-plan-id="<?= $plan['id'] ?>"
                                data-plan-name="<?= htmlspecialchars($plan['name']) ?>">
                            <?= isset($current_subscription) ? 'Switch to this Plan' : 'Choose Plan' ?>
                        </button>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Billing History -->
            <div class="billing-card">
                <h5 class="mb-3">Billing History</h5>
                <?php if(!empty($billing_history)): ?>
                    <?php foreach($billing_history as $invoice): ?>
                        <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                            <div>
                                <div class="fw-semibold"><?= date('M d, Y', strtotime($invoice['date'])) ?></div>
                                <div class="small text-muted"><?= htmlspecialchars($invoice['description']) ?></div>
                            </div>
                            <div class="text-end">
                                <div class="fw-semibold">MWK <?= number_format($invoice['amount']) ?></div>
                                <div class="small">
                                    <span class="badge bg-<?= $invoice['status'] === 'paid' ? 'success' : 'warning' ?>">
                                        <?= ucfirst($invoice['status']) ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-receipt fa-3x mb-3"></i>
                        <p>No billing history yet</p>
                    </div>
                <?php endif; ?>
            </div>
        </main>

        <!-- Bottom Navigation -->
        <nav class="bottom-nav">
            <a href="<?php echo base_url('dashboard'); ?>" class="nav-item">
                <div class="nav-icon">
                    <i class="fas fa-home"></i>
                </div>
                <div class="nav-label">Home</div>
            </a>
            <a href="<?php echo base_url('trainer/profile'); ?>" class="nav-item">
                <div class="nav-icon">
                    <i class="fas fa-user"></i>
                </div>
                <div class="nav-label">Profile</div>
            </a>
        </nav>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // PayChangu Inline Checkout Integration
        document.addEventListener('DOMContentLoaded', function() {
            const subscribeButtons = document.querySelectorAll('.subscribe-btn');

            // Function to get CSRF token from cookie
            function getCsrfToken() {
                const name = 'csrf_cookie_name';
                const decodedCookie = decodeURIComponent(document.cookie);
                const cookies = decodedCookie.split(';');
                for (let cookie of cookies) {
                    cookie = cookie.trim();
                    if (cookie.indexOf(name + '=') === 0) {
                        return cookie.substring(name.length + 1);
                    }
                }
                return '';
            }

            subscribeButtons.forEach(button => {
                button.addEventListener('click', async function() {
                    const planId = this.getAttribute('data-plan-id');
                    const planName = this.getAttribute('data-plan-name');

                    // Disable button and show loading
                    this.disabled = true;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

                    try {
                        // Get CSRF token
                        const csrfToken = getCsrfToken();

                        // Make AJAX call to get PayChangu configuration
                        const response = await fetch(`<?= base_url('trainer/subscribe/') ?>${planId}`, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Content-Type': 'application/x-www-form-urlencoded',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: `csrf_test_name=${encodeURIComponent(csrfToken)}`
                        });

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const result = await response.json();

                        if (result.success) {
                            if (result.redirect) {
                                // Free plan - redirect to dashboard
                                window.location.href = result.redirect;
                            } else if (result.paychangu_config) {
                                // Wait for PayChangu SDK to be ready
                                const checkPayChanguReady = () => {
                                    if (typeof PaychanguCheckout !== 'undefined') {
                                        console.log('PayChangu SDK ready, initializing checkout...');
                                        // Initialize PayChangu inline checkout
                                        PaychanguCheckout({
                                            ...result.paychangu_config,
                                            inline: true, // Enable inline popup mode
                                            callback: function(response) {
                                                console.log('Payment completed:', response);

                                                if (response.status === 'success') {
                                                    // Payment successful - redirect to success page
                                                    window.location.href = '<?= base_url('checkout/paychangu/success?tx_ref=') ?>' + response.tx_ref;
                                                } else {
                                                    // Payment failed
                                                    alert('Payment failed. Please try again.');
                                                    location.reload();
                                                }
                                            },
                                            onClose: function() {
                                                console.log('Payment modal closed');
                                                // Re-enable button
                                                button.disabled = false;
                                                button.innerHTML = 'Choose Plan';
                                            }
                                        });
                                    } else {
                                        console.log('PayChangu SDK not ready, waiting...');
                                        // Wait a bit and try again
                                        setTimeout(checkPayChanguReady, 200);
                                    }
                                };

                                // Start checking if PayChangu is ready
                                checkPayChanguReady();

                                // Timeout after 5 seconds - fallback to redirect
                                setTimeout(() => {
                                    if (typeof PaychanguCheckout === 'undefined') {
                                        console.log('PayChangu SDK not available, redirecting to checkout page...');
                                        // Fallback: redirect to standard checkout page
                                        window.location.href = `<?= base_url('checkout/subscription/') ?>${planId}`;
                                    }
                                }, 5000);
                            }
                        } else {
                            alert(result.message || 'An error occurred. Please try again.');
                            // Re-enable button
                            button.disabled = false;
                            button.innerHTML = 'Choose Plan';
                        }
                    } catch (error) {
                        console.error('Subscription error:', error);
                        alert('Network error. Please check your connection and try again.');
                        // Re-enable button
                        button.disabled = false;
                        button.innerHTML = 'Choose Plan';
                    }
                });
            });
        });
    </script>
</body>
</html>
