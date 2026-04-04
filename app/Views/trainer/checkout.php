<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#E55C0D">
    <title><?= $title ?? 'Checkout - TutorConnect Malawi' ?></title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('favicon.ico') ?>">

    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">



    <style>
        :root {
            --primary-color: #E55C0D;
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

            --header-height: 70px;
            --bottom-nav-height: 70px;
            --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-dark);
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
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border-color);
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
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
        }

        .page-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        .main-content {
            flex: 1;
            padding: 1rem;
            overflow-y: auto;
        }

        .section-title {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .section-subtitle {
            font-size: 0.95rem;
            color: var(--text-light);
            margin-bottom: 1.5rem;
        }

        .plan-card {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            border: 2px solid var(--warning-color);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 1.5rem;
        }

        .plan-name {
            font-size: 1.4rem;
            font-weight: 700;
            color: #92400e;
        }

        .plan-price {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary-color);
            margin: 1rem 0;
        }

        .feature-list {
            list-style: none;
            padding: 0;
            margin: 1rem 0 0;
        }

        .feature-list li {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
            color: var(--text-secondary);
        }

        .payment-methods {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .payment-method {
            background: var(--bg-secondary);
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: var(--transition);
        }

        .payment-method:hover {
            border-color: var(--primary-color);
            transform: translateY(-4px);
            box-shadow: var(--card-shadow);
        }

        .payment-method.selected {
            border-color: var(--primary-color);
            background: rgba(229, 92, 13, 0.05);
            box-shadow: 0 0 0 4px rgba(229, 92, 13, 0.15);
        }

        .payment-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .bank-icon { color: #1e40af; }
        .mobile-icon { color: #dc2626; }
        .cash-icon { color: #059669; }

        .file-upload {
            border: 2px dashed var(--border-color);
            border-radius: var(--border-radius);
            padding: 2rem;
            text-align: center;
            background: var(--bg-primary);
            cursor: pointer;
            transition: var(--transition);
        }

        .file-upload:hover {
            border-color: var(--primary-color);
            background: rgba(229, 92, 13, 0.05);
        }

        .file-upload.has-file {
            border-color: var(--success-color);
            background: rgba(16, 185, 129, 0.05);
        }

        .terms-box {
            background: var(--bg-primary);
            border-radius: var(--border-radius);
            padding: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .terms-checkbox {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .terms-checkbox input {
            margin-top: 0.25rem;
        }

        .terms-checkbox a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
        }

        .terms-checkbox a:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }

        .checkout-btn {
            width: 100%;
            padding: 0.875rem 1rem;
            font-size: 1rem;
            font-weight: 600;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .checkout-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: var(--bg-secondary);
            border-top: 1px solid var(--border-color);
            padding: 0.5rem 0;
            z-index: 1000;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.08);
            padding-bottom: env(safe-area-inset-bottom);
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
        }

        .nav-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            max-width: 500px;
            margin: 0 auto;
        }

        .nav-item-mobile {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0.75rem 0.5rem;
            text-decoration: none;
            color: var(--text-light);
            border-radius: 12px;
            transition: var(--transition);
            font-size: 0.8rem;
        }

        .nav-item-mobile.active {
            color: var(--primary-color);
        }

        .nav-item-mobile i {
            font-size: 1.4rem;
            margin-bottom: 0.4rem;
        }

        .nav-item-mobile span {
            font-weight: 500;
        }

        .back-link {
            text-align: center;
            margin-top: 1rem;
        }

        .back-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        /* Payment Summary Styles */
        .payment-summary-card {
            background: var(--bg-secondary);
            border-radius: var(--border-radius);
            padding: 1.25rem;
            box-shadow: var(--shadow);
            margin-bottom: 1.5rem;
            border: 1px solid var(--border-color);
        }

        .payment-summary-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .payment-summary-title i {
            color: var(--primary-color);
            font-size: 1rem;
        }

        .payment-summary-content {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--border-color);
        }

        .summary-row:last-child {
            border-bottom: none;
        }

        .summary-label {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text-secondary);
        }

        .summary-value {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .summary-price {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .billing-control {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
        }

        .billing-input {
            width: 92px;
            padding: 0.55rem 0.7rem;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-dark);
            text-align: center;
            background: var(--bg-secondary);
        }

        .billing-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(229, 92, 13, 0.12);
        }

        .billing-suffix {
            font-size: 0.9rem;
            color: var(--text-light);
            font-weight: 600;
        }

        .renewal-note {
            background: rgba(14, 165, 233, 0.08);
            border: 1px solid rgba(14, 165, 233, 0.2);
            border-radius: 12px;
            padding: 0.9rem 1rem;
            margin-top: 1rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            color: var(--text-secondary);
            font-size: 0.9rem;
            line-height: 1.45;
        }

        .renewal-note i {
            color: var(--accent-color);
            margin-top: 0.15rem;
            flex-shrink: 0;
        }

        /* Payment Methods Styles */
        .payment-methods-section {
            margin-bottom: 1.5rem;
        }

        .payment-methods-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }

        .payment-methods-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        @media (min-width: 480px) {
            .payment-methods-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1.25rem;
            }
        }

        .payment-method-item {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 0.875rem;
            transition: var(--transition);
        }

        .payment-method-item:hover {
            border-color: var(--primary-color);
            box-shadow: var(--shadow);
            transform: translateY(-2px);
        }

        .payment-method-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-dark);
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .payment-method-details h6 {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-dark);
            margin: 0 0 0.25rem 0;
        }

        .payment-method-details small {
            font-size: 0.8rem;
            color: var(--text-light);
        }

        /* Security Notice Styles */
        .security-notice {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.05));
            border: 1px solid rgba(16, 185, 129, 0.2);
            border-radius: 12px;
            padding: 1rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .security-notice i {
            color: var(--success-color);
            font-size: 1.1rem;
            margin-top: 0.125rem;
            flex-shrink: 0;
        }

        .security-notice span {
            font-size: 0.9rem;
            color: var(--text-secondary);
            line-height: 1.4;
        }
    </style>
</head>
<body>
    <!-- PayChangu required wrapper (as per documentation) -->
    <div id="wrapper"></div>

    <div class="app-container">
        <!-- Top Header -->
        <header class="top-header">
            <div class="header-left">
                <div class="app-logo">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="page-title">Checkout</div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <h1 class="section-title">Complete Subscription</h1>
            <p class="section-subtitle">Secure checkout for your selected plan</p>

            <form id="checkoutForm" enctype="multipart/form-data">
                <input type="hidden" name="plan_id" value="<?= $plan['id'] ?>">

                <?php $isFreePlan = $plan['price_monthly'] <= 0; ?>
                <?php $defaultBillingMonths = (int) ($default_billing_months ?? 1); ?>
                <?php $maxBillingMonths = (int) ($max_billing_months ?? 120); ?>
                <?php $isSamePlanRenewal = !empty($current_subscription) && (int) ($current_subscription['plan_id'] ?? 0) === (int) $plan['id']; ?>

                <?php if (!$isFreePlan): ?>
                    <!-- Payment Summary Card -->
                    <div class="payment-summary-card">
                        <h4 class="payment-summary-title">
                            <i class="fas fa-credit-card"></i> Payment Summary
                        </h4>
                        <div class="payment-summary-content">
                            <div class="summary-row">
                                <span class="summary-label">Plan:</span>
                                <span class="summary-value"><?= htmlspecialchars($plan['name']) ?> Plan</span>
                            </div>
                            <div class="summary-row">
                                <span class="summary-label">Monthly price:</span>
                                <span class="summary-price">MWK <?= $plan['formatted_price'] ?>/month</span>
                            </div>
                            <div class="summary-row">
                                <label class="summary-label" for="billingMonths">Months:</label>
                                <div class="billing-control">
                                    <input
                                        type="number"
                                        id="billingMonths"
                                        name="billing_months"
                                        class="billing-input"
                                        value="<?= $defaultBillingMonths ?>"
                                        min="1"
                                        max="<?= $maxBillingMonths ?>"
                                        inputmode="numeric"
                                    >
                                    <span class="billing-suffix">months</span>
                                </div>
                            </div>
                            <div class="summary-row">
                                <span class="summary-label">Coverage:</span>
                                <span class="summary-value" id="coverageLabel"><?= $defaultBillingMonths === 1 ? '1 month' : $defaultBillingMonths . ' months' ?></span>
                            </div>
                            <div class="summary-row">
                                <span class="summary-label">Total:</span>
                                <span class="summary-price" id="totalAmount">MWK <?= number_format(((float) $plan['price_monthly']) * $defaultBillingMonths, 0, ',', ',') ?></span>
                            </div>
                        </div>

                        <?php if ($isSamePlanRenewal && !empty($current_subscription['current_period_end'])): ?>
                            <div class="renewal-note">
                                <i class="fas fa-layer-group"></i>
                                <span>Any extra months you pay now will be added after your current plan ends on <?= date('M j, Y', strtotime($current_subscription['current_period_end'])) ?>.</span>
                            </div>
                        <?php elseif (!empty($current_subscription['current_period_end'])): ?>
                            <div class="renewal-note">
                                <i class="fas fa-bolt"></i>
                                <span>Switching to this plan starts the new subscription immediately after payment. Your current plan will stop once the new one takes over.</span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Available Payment Methods -->
                    <div class="payment-methods-section">
                        <h5 class="payment-methods-title">Available Payment Methods</h5>
                        <div class="payment-methods-grid">
                            <div class="payment-method-item">
                                <div class="payment-method-icon">
                                    <img src="<?= base_url('uploads/slider/airtel-money-logo.png') ?>" alt="Airtel Money" style="width: 100%; height: 100%; object-fit: contain;">
                                </div>
                                <div class="payment-method-details">
                                    <h6>Airtel Money</h6>
                                    <small>Mobile Money Payment</small>
                                </div>
                            </div>
                            <div class="payment-method-item">
                                <div class="payment-method-icon">
                                    <img src="<?= base_url('uploads/slider/TNM-Mpamba-Logo.png') ?>" alt="TNM Mpamba" style="width: 100%; height: 100%; object-fit: contain;">
                                </div>
                                <div class="payment-method-details">
                                    <h6>TNM Mpamba</h6>
                                    <small>Mobile Money Payment</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Security Notice -->
                    <div class="security-notice">
                        <i class="fas fa-shield-alt"></i>
                        <span><strong>Secure Payment:</strong> You will be redirected to PayChangu's secure payment gateway to complete your transaction.</span>
                    </div>
                <?php else: ?>
                    <input type="hidden" name="billing_months" value="1">
                    <div class="text-center py-5 bg-success text-white rounded" style="border-radius:var(--border-radius);">
                        <i class="fas fa-gift" style="font-size:3rem;"></i>
                        <h4 class="mt-3">FREE Plan Activated!</h4>
                        <p>No payment required. Enjoy your premium features!</p>
                    </div>
                <?php endif; ?>

                <!-- Terms -->
                <div class="terms-box">
                    <div class="terms-checkbox">
                        <input type="checkbox" id="terms" name="terms_accepted" required>
                        <label for="terms" class="small text-muted">
                            I agree to the <a href="<?= base_url('terms-of-service') ?>" class="text-primary" target="_blank">Terms of Service</a> and
                            <a href="<?= base_url('refund-policy') ?>" class="text-primary" target="_blank">Refund Policy</a>.
                            Payment will be processed immediately through PayChangu, and account verification is completed once payment succeeds.
                        </label>
                    </div>
                </div>

                <!-- Submit -->
                <button type="submit" class="checkout-btn" id="submitBtn">
                    <span id="btnText">
                        <?php if ($isFreePlan): ?>
                            <i class="fas fa-check"></i> Activate Free Plan
                        <?php else: ?>
                            <i class="fas fa-lock"></i> Submit Payment
                        <?php endif; ?>
                    </span>
                </button>

                <div class="back-link">
                    <a href="<?= base_url('trainer/subscription') ?>"><i class="fas fa-arrow-left"></i> Back to Plans</a>
                </div>
            </form>
        </main>
    </div>

    <!-- Bottom Navigation -->
    <nav class="bottom-nav">
        <div class="nav-grid">
            <a href="<?= base_url('trainer/dashboard') ?>" class="nav-item-mobile">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>
            <a href="<?= base_url('trainer/subjects') ?>" class="nav-item-mobile">
                <i class="fas fa-book"></i>
                <span>Subjects</span>
            </a>
            <a href="<?= base_url('trainer/profile') ?>" class="nav-item-mobile">
                <i class="fas fa-user"></i>
                <span>Profile</span>
            </a>
            <a href="<?= base_url('trainer/subscription') ?>" class="nav-item-mobile active">
                <i class="fas fa-crown"></i>
                <span>Premium</span>
            </a>
        </div>
    </nav>

    <!-- jQuery (required by PayChangu SDK) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- PayChangu Inline Checkout SDK (from official documentation) -->
    <script src="https://in.paychangu.com/js/popup.js"></script>

    <script>
        // Suppress PayChangu errors that don't affect functionality
        const originalConsoleError = console.error;
        const originalConsoleWarn = console.warn;

        console.error = function(...args) {
            // Suppress PayChangu-related errors
            if (args[0] && typeof args[0] === 'string') {
                const message = args[0].toLowerCase();
                if (message.includes('unsafe attempt to initiate navigation') ||
                    message.includes('failed to set a named property') ||
                    message.includes('minified react error') ||
                    message.includes('client-side exception') ||
                    message.includes('paychangu') ||
                    (args[0].includes('4bd1b696') && args[0].includes('js'))) {
                    return; // Suppress PayChangu errors
                }
            }
            // Call original console.error for other errors
            originalConsoleError.apply(console, args);
        };

        console.warn = function(...args) {
            // Suppress PayChangu iframe warnings
            if (args[0] && typeof args[0] === 'string') {
                const message = args[0].toLowerCase();
                if (message.includes('slow network') ||
                    message.includes('fallback font') ||
                    message.includes('paychangu')) {
                    return; // Suppress PayChangu warnings
                }
            }
            // Call original console.warn for other warnings
            originalConsoleWarn.apply(console, args);
        };

        document.addEventListener('DOMContentLoaded', function() {
            const isFreePlan = <?= $isFreePlan ? 'true' : 'false' ?>;
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const billingMonthsInput = document.getElementById('billingMonths');
            const coverageLabel = document.getElementById('coverageLabel');
            const totalAmount = document.getElementById('totalAmount');
            const monthlyPrice = <?= json_encode((float) $plan['price_monthly']) ?>;
            const maxBillingMonths = <?= json_encode($maxBillingMonths) ?>;

            function normalizeBillingMonths() {
                if (!billingMonthsInput) {
                    return 1;
                }

                let months = parseInt(billingMonthsInput.value, 10);

                if (Number.isNaN(months) || months < 1) {
                    months = 1;
                }

                if (months > maxBillingMonths) {
                    months = maxBillingMonths;
                }

                billingMonthsInput.value = months;
                return months;
            }

            function formatMwK(amount) {
                return 'MWK ' + Math.round(amount).toLocaleString('en-US');
            }

            function updateBillingSummary() {
                if (!billingMonthsInput || !coverageLabel || !totalAmount) {
                    return;
                }

                const months = normalizeBillingMonths();
                coverageLabel.textContent = months === 1 ? '1 month' : `${months} months`;
                totalAmount.textContent = formatMwK(monthlyPrice * months);
            }

            if (billingMonthsInput) {
                billingMonthsInput.addEventListener('input', updateBillingSummary);
                billingMonthsInput.addEventListener('change', updateBillingSummary);
                updateBillingSummary();
            }

            // Form submission
            const form = document.getElementById('checkoutForm');
            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                try {
                    normalizeBillingMonths();
                    const formData = new FormData(form);

                    // Show loading spinner immediately
                    submitBtn.disabled = true;
                    if (isFreePlan) {
                        btnText.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Activating...';
                    } else {
                        btnText.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
                    }

                    // For free plans, process directly
                    if (isFreePlan) {
                        const response = await fetch('<?= base_url('trainer/checkout/process-subscription') ?>', {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: formData
                        });

                        const result = await response.json();

                        if (result.success) {
                            btnText.innerHTML = '<i class="fas fa-check me-2"></i>Success!';
                            setTimeout(() => {
                                window.location.href = result.redirect || '<?= base_url('trainer/dashboard') ?>';
                            }, 500);
                        } else {
                            alert(result.message || 'An error occurred. Please try again.');
                            submitBtn.disabled = false;
                            btnText.innerHTML = '<i class="fas fa-check"></i> Activate Free Plan';
                        }
                        return;
                    }

                    // For paid plans, check if PayChangu SDK is loaded
                    if (typeof PaychanguCheckout !== 'function') {
                        console.error('PayChangu SDK not loaded');
                        alert('Payment system is currently unavailable. Please refresh the page and try again.');
                        submitBtn.disabled = false;
                        btnText.innerHTML = '<i class="fas fa-lock"></i> Submit Payment';
                        return;
                    }

                    // Get PayChangu configuration
                    const response = await fetch('<?= base_url('trainer/checkout/process-subscription') ?>', {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    });

                    const result = await response.json();

                    if (result.success && result.paychangu_config) {
                        console.log('PayChangu config received, initializing inline popup...');

                        try {
                            // Initialize PayChangu inline popup as per documentation
                            PaychanguCheckout({
                                public_key: result.paychangu_config.public_key,
                                tx_ref: result.paychangu_config.tx_ref,
                                amount: result.paychangu_config.amount,
                                currency: result.paychangu_config.currency,
                                callback_url: result.paychangu_config.callback_url, // Required by PayChangu SDK
                                return_url: result.paychangu_config.return_url, // Required by PayChangu SDK
                                customer: {
                                    email: result.paychangu_config.customer.email,
                                    first_name: result.paychangu_config.customer.first_name,
                                    last_name: result.paychangu_config.customer.last_name
                                },
                                customizations: {
                                    title: result.paychangu_config.customizations.title,
                                    description: result.paychangu_config.customizations.description,
                                    logo: result.paychangu_config.customizations.logo
                                },
                                callback: function(response) {
                                    console.log('Payment completed:', response);
                                    // PayChangu inline checkout may not properly trigger callback
                                    // We'll handle success detection in onClose
                                },
                                onClose: function() {
                                    console.log('Payment modal closed');
                                    // Check if payment was successful by polling our backend
                                    // Since PayChangu may not properly trigger callback in inline mode
                                    const txRef = result.paychangu_config.tx_ref;

                                    if (txRef) {
                                        // Poll for payment status immediately after modal closes
                                        checkPaymentStatusAfterModal(txRef);
                                    } else {
                                        // Re-enable button if no transaction reference
                                        submitBtn.disabled = false;
                                        btnText.innerHTML = '<i class="fas fa-lock"></i> Submit Payment';
                                    }
                                }
                            });
                        } catch (popupError) {
                            console.error('PayChangu popup error:', popupError);
                            alert('Payment system error. Please try again.');
                            submitBtn.disabled = false;
                            btnText.innerHTML = '<i class="fas fa-lock"></i> Submit Payment';
                        }
                    } else {
                        // API failed - show error message
                        alert(result.message || 'Payment system is currently under maintenance. Please contact support at info@uprisemw.com or try again later.');
                        submitBtn.disabled = false;
                        btnText.innerHTML = '<i class="fas fa-lock"></i> Submit Payment';
                    }
                } catch (error) {
                    console.error('Submission error:', error);
                    alert('Network error. Please check your connection and try again.');
                    submitBtn.disabled = false;
                    btnText.innerHTML = '<i class="fas fa-lock"></i> Submit Payment';
                }
            });
        });

        // Function to check payment status after modal closes
        function checkPaymentStatusAfterModal(txRef) {
            console.log('Checking payment status after modal close for tx_ref:', txRef);
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');

            // Check immediately
            fetch('<?= base_url('trainer/checkout/checkPaymentStatus') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: 'tx_ref=' + encodeURIComponent(txRef)
            })
            .then(response => response.json())
            .then(data => {
                console.log('Payment status check result:', data);

                if (data.status === 'verified' && data.subscription_status === 'active') {
                    // Payment was successful - redirect to success page
                    console.log('Payment verified! Redirecting to success page...');
                    window.location.href = '<?= base_url('checkout/paychangu/success?tx_ref=') ?>' + encodeURIComponent(txRef);
                } else if (data.status === 'failed') {
                    // Payment failed
                    console.log('Payment failed');
                    alert('Payment was not successful. Please try again.');
                    submitBtn.disabled = false;
                    btnText.innerHTML = '<i class="fas fa-lock"></i> Submit Payment';
                } else {
                    // Still processing - check again in 2 seconds
                    console.log('Payment still processing, checking again in 2 seconds...');
                    setTimeout(() => checkPaymentStatusAfterModal(txRef), 2000);
                }
            })
            .catch(error => {
                console.error('Error checking payment status:', error);
                // If we can't check status, assume payment might have succeeded and redirect
                // This is safer than keeping user in limbo
                console.log('Assuming payment success due to check error, redirecting...');
                window.location.href = '<?= base_url('checkout/paychangu/success?tx_ref=') ?>' + encodeURIComponent(txRef);
            });
        }
    </script>
</body>
</html>
