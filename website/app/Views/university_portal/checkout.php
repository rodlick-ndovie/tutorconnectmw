<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'University Subscription Checkout') ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #e55c0d;
            --primary-dark: #c94609;
            --text-dark: #1f2937;
            --text-muted: #6b7280;
            --bg: #f8fafc;
            --card: #ffffff;
            --border: #e5e7eb;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Inter, "Segoe UI", Arial, sans-serif;
            background: var(--bg);
            color: var(--text-dark);
        }

        .page {
            max-width: 960px;
            margin: 0 auto;
            padding: 26px 20px 40px;
        }

        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 20px;
        }

        .grid {
            display: grid;
            grid-template-columns: minmax(0, 1.2fr) minmax(280px, 0.9fr);
            gap: 18px;
        }

        .btn-soft,
        .btn-primary-soft {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 16px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            border: 1px solid var(--border);
            cursor: pointer;
        }

        .btn-soft {
            background: #fff;
            color: var(--text-dark);
        }

        .btn-primary-soft {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            border-color: transparent;
        }

        .field,
        .checkbox-row {
            width: 100%;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 12px 14px;
            font: inherit;
            background: #fff;
        }

        .checkbox-row {
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .checkbox-row a {
            color: var(--primary);
            font-weight: 700;
            text-decoration: none;
        }

        .checkbox-row a:hover {
            text-decoration: underline;
        }

        .feature-list {
            margin: 14px 0 0;
            padding-left: 18px;
            color: #374151;
            line-height: 1.6;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .summary-row:last-child {
            border-bottom: none;
        }

        .muted {
            color: var(--text-muted);
            line-height: 1.6;
        }

        .helper {
            margin-top: 8px;
            color: var(--text-muted);
            font-size: 0.84rem;
        }

        @media (max-width: 900px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }
<?= view('university_portal/partials/shell_styles') ?>
    </style>
</head>
<body>
    <div id="wrapper"></div>

    <?= view('university_portal/partials/shell_start', [
        'nav_title' => 'Premium',
        'nav_subtitle' => 'Subscription Checkout',
        'nav_user' => trim((string) ($portal_display_name ?? (($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')))),
        'show_home_shortcut' => true,
    ]) ?>
    <div class="page">
        <div class="card" style="margin-bottom: 18px;">
            <h1 style="margin:0 0 8px;">Subscription Checkout</h1>
            <p class="muted" style="margin:0;">This checkout uses the same subscription payment logic as the tutor portal, but returns you to the university workspace after payment.</p>
            <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:16px;">
                <a href="<?= esc($subscription_url) ?>" class="btn-soft">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to Plans</span>
                </a>
                <a href="<?= esc($dashboard_url) ?>" class="btn-soft">
                    <i class="fas fa-house"></i>
                    <span>Dashboard</span>
                </a>
            </div>
        </div>

        <form id="checkoutForm">
            <input type="hidden" name="plan_id" value="<?= (int) ($plan['id'] ?? 0) ?>">

            <div class="grid">
                <section class="card">
                    <h2 style="margin:0 0 10px;"><?= esc($plan['name'] ?? 'Plan') ?></h2>
                    <div style="font-size:2rem; font-weight:800; color:var(--primary);">MWK <?= esc($plan['formatted_price'] ?? '0') ?></div>
                    <div class="muted">per month</div>

                    <?php if (!empty($plan['description'])): ?>
                        <p class="muted" style="margin-top:14px;"><?= esc($plan['description']) ?></p>
                    <?php endif; ?>

                    <?php if (!empty($plan['features'])): ?>
                        <ul class="feature-list">
                            <?php foreach ($plan['features'] as $feature): ?>
                                <li><?= esc((string) $feature) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <div style="margin-top:18px;">
                        <label for="billing_months" style="display:block; font-weight:700; margin-bottom:8px;">Billing Months</label>
                        <input
                            class="field"
                            id="billing_months"
                            name="billing_months"
                            type="number"
                            min="1"
                            max="<?= (int) ($max_billing_months ?? 120) ?>"
                            value="<?= (int) ($default_billing_months ?? 1) ?>"
                        >
                        <div class="helper">You can pay for multiple months at once. Maximum: <?= (int) ($max_billing_months ?? 120) ?> months.</div>
                    </div>
                </section>

                <section class="card">
                    <h2 style="margin:0 0 12px;">Order Summary</h2>

                    <?php if (!empty($current_subscription)): ?>
                        <div class="summary-row">
                            <span class="muted">Current subscription</span>
                            <strong><?= esc($current_subscription['plan_name'] ?? 'Active plan') ?></strong>
                        </div>
                    <?php endif; ?>

                    <div class="summary-row">
                        <span class="muted">Selected plan</span>
                        <strong><?= esc($plan['name'] ?? 'Plan') ?></strong>
                    </div>
                    <div class="summary-row">
                        <span class="muted">Monthly price</span>
                        <strong id="monthlyPrice">MWK <?= esc($plan['formatted_price'] ?? '0') ?></strong>
                    </div>
                    <div class="summary-row">
                        <span class="muted">Coverage</span>
                        <strong id="coverageLabel"><?= (int) ($default_billing_months ?? 1) ?> month<?= ((int) ($default_billing_months ?? 1) === 1) ? '' : 's' ?></strong>
                    </div>
                    <div class="summary-row">
                        <span class="muted">Total</span>
                        <strong id="totalAmount">MWK <?= number_format(((float) ($plan['price_monthly'] ?? 0)) * ((int) ($default_billing_months ?? 1)), 0, ',', ',') ?></strong>
                    </div>

                    <div style="margin-top:18px;">
                        <label class="checkbox-row">
                            <input type="checkbox" name="terms_accepted" value="1" required>
                            <span>
                                I confirm that I want to activate this subscription and that I have read and agree to the
                                <a href="<?= base_url('terms-of-service') ?>" target="_blank" rel="noopener">Terms of Service</a>,
                                <a href="<?= base_url('privacy-policy') ?>" target="_blank" rel="noopener">Privacy Policy</a>, and
                                <a href="<?= base_url('refund-policy') ?>" target="_blank" rel="noopener">Refund Policy</a>.
                            </span>
                        </label>
                    </div>

                    <div style="display:grid; gap:10px; margin-top:18px;">
                        <button type="submit" id="submitBtn" class="btn-primary-soft">
                            <span id="btnText"><i class="fas fa-credit-card"></i> Continue to Payment</span>
                        </button>
                        <a href="<?= esc($subscription_url) ?>" class="btn-soft">
                            <i class="fas fa-arrow-left"></i>
                            <span>Back to Plans</span>
                        </a>
                    </div>
                </section>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://in.paychangu.com/js/popup.js"></script>
    <script>
        const originalConsoleError = console.error;
        const originalConsoleWarn = console.warn;

        console.error = function(...args) {
            if (args[0] && typeof args[0] === 'string') {
                const message = args[0].toLowerCase();
                if (message.includes('unsafe attempt to initiate navigation') ||
                    message.includes('failed to set a named property') ||
                    message.includes('minified react error') ||
                    message.includes('client-side exception') ||
                    message.includes('paychangu')) {
                    return;
                }
            }

            originalConsoleError.apply(console, args);
        };

        console.warn = function(...args) {
            if (args[0] && typeof args[0] === 'string') {
                const message = args[0].toLowerCase();
                if (message.includes('slow network') ||
                    message.includes('fallback font') ||
                    message.includes('paychangu')) {
                    return;
                }
            }

            originalConsoleWarn.apply(console, args);
        };

        document.addEventListener('DOMContentLoaded', function () {
            const monthlyPrice = <?= json_encode((float) ($plan['price_monthly'] ?? 0)) ?>;
            const isFreePlan = monthlyPrice === 0;
            const billingMonthsInput = document.getElementById('billing_months');
            const coverageLabel = document.getElementById('coverageLabel');
            const totalAmount = document.getElementById('totalAmount');
            const form = document.getElementById('checkoutForm');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const maxBillingMonths = <?= json_encode((int) ($max_billing_months ?? 120)) ?>;
            const dashboardUrl = <?= json_encode($dashboard_url) ?>;
            const checkoutProcessUrl = <?= json_encode($checkout_process_url) ?>;
            const checkPaymentStatusUrl = <?= json_encode($check_payment_status_url) ?>;
            const checkoutReturnUrl = <?= json_encode($checkout_return_url) ?>;
            const callbackPath = new URL('<?= site_url('checkout/paychangu/callback') ?>', window.location.href).pathname;
            const returnPath = new URL(checkoutReturnUrl, window.location.href).pathname;
            const logoPath = new URL('<?= base_url('favicon.ico') ?>', window.location.href).pathname;

            function normalizeCheckoutUrl(rawUrl, fallbackPath) {
                try {
                    const parsed = new URL(rawUrl || fallbackPath, window.location.href);
                    const host = (parsed.hostname || '').toLowerCase();

                    if (!host || host === 'localhost' || host === '127.0.0.1' || parsed.origin !== window.location.origin) {
                        return window.location.origin + (parsed.pathname || fallbackPath) + (parsed.search || '');
                    }

                    return parsed.toString();
                } catch (error) {
                    return window.location.origin + fallbackPath;
                }
            }

            function normalizeBillingMonths() {
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
                const months = normalizeBillingMonths();
                coverageLabel.textContent = months === 1 ? '1 month' : `${months} months`;
                totalAmount.textContent = formatMwK(monthlyPrice * months);
            }

            billingMonthsInput.addEventListener('input', updateBillingSummary);
            billingMonthsInput.addEventListener('change', updateBillingSummary);
            updateBillingSummary();

            form.addEventListener('submit', async function (e) {
                e.preventDefault();

                const formData = new FormData(form);
                normalizeBillingMonths();
                submitBtn.disabled = true;
                btnText.innerHTML = isFreePlan
                    ? '<i class="fas fa-spinner fa-spin"></i> Activating...'
                    : '<i class="fas fa-spinner fa-spin"></i> Processing...';

                try {
                    const response = await fetch(checkoutProcessUrl, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    });

                    const result = await response.json();

                    if (!result.success) {
                        alert(result.message || 'We could not process the subscription request.');
                        submitBtn.disabled = false;
                        btnText.innerHTML = '<i class="fas fa-credit-card"></i> Continue to Payment';
                        return;
                    }

                    if (isFreePlan) {
                        window.location.href = result.redirect || dashboardUrl;
                        return;
                    }

                    if (typeof PaychanguCheckout !== 'function' || !result.paychangu_config) {
                        if (result.hosted_checkout_url) {
                            window.location.href = result.hosted_checkout_url;
                            return;
                        }

                        alert(result.message || 'Payment system is currently unavailable. Please try again.');
                        submitBtn.disabled = false;
                        btnText.innerHTML = '<i class="fas fa-credit-card"></i> Continue to Payment';
                        return;
                    }

                    try {
                        const normalizedCallbackUrl = normalizeCheckoutUrl(result.paychangu_config.callback_url, callbackPath);
                        const normalizedReturnUrl = normalizeCheckoutUrl(result.paychangu_config.return_url, returnPath);
                        const normalizedLogoUrl = normalizeCheckoutUrl(result.paychangu_config.customizations.logo, logoPath);

                        PaychanguCheckout({
                            public_key: result.paychangu_config.public_key,
                            tx_ref: result.paychangu_config.tx_ref,
                            amount: result.paychangu_config.amount,
                            currency: result.paychangu_config.currency,
                            callback_url: normalizedCallbackUrl,
                            return_url: normalizedReturnUrl,
                            customer: {
                                email: result.paychangu_config.customer.email,
                                first_name: result.paychangu_config.customer.first_name,
                                last_name: result.paychangu_config.customer.last_name
                            },
                            customizations: {
                                title: result.paychangu_config.customizations.title,
                                description: result.paychangu_config.customizations.description,
                                logo: normalizedLogoUrl
                            },
                            callback: function () {},
                            onClose: function () {
                                if (result.paychangu_config.tx_ref) {
                                    checkPaymentStatusAfterModal(result.paychangu_config.tx_ref);
                                } else {
                                    submitBtn.disabled = false;
                                    btnText.innerHTML = '<i class="fas fa-credit-card"></i> Continue to Payment';
                                }
                            }
                        });
                    } catch (popupError) {
                        console.error(popupError);

                        if (result.hosted_checkout_url) {
                            window.location.href = result.hosted_checkout_url;
                            return;
                        }

                        alert('Payment system error. Please try again.');
                        submitBtn.disabled = false;
                        btnText.innerHTML = '<i class="fas fa-credit-card"></i> Continue to Payment';
                    }
                } catch (error) {
                    console.error(error);
                    alert('Network error. Please check your connection and try again.');
                    submitBtn.disabled = false;
                    btnText.innerHTML = '<i class="fas fa-credit-card"></i> Continue to Payment';
                }
            });

            function checkPaymentStatusAfterModal(txRef, attempt = 1) {
                const maxAttempts = 24;

                fetch(checkPaymentStatusUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: 'tx_ref=' + encodeURIComponent(txRef)
                })
                .then((response) => response.json())
                .then((data) => {
                    if (data.status === 'verified' && data.subscription_status === 'active') {
                        window.location.href = `${checkoutReturnUrl}?tx_ref=${encodeURIComponent(txRef)}`;
                        return;
                    }

                    if (data.status === 'failed') {
                        alert('Payment was not successful. Please try again.');
                        submitBtn.disabled = false;
                        btnText.innerHTML = '<i class="fas fa-credit-card"></i> Continue to Payment';
                        return;
                    }

                    if (attempt >= maxAttempts) {
                        alert('We could not confirm this payment yet. If money was deducted, please contact support with your transaction reference: ' + txRef);
                        submitBtn.disabled = false;
                        btnText.innerHTML = '<i class="fas fa-credit-card"></i> Continue to Payment';
                        return;
                    }

                    setTimeout(() => checkPaymentStatusAfterModal(txRef, attempt + 1), 2000);
                })
                .catch(() => {
                    if (attempt >= maxAttempts) {
                        alert('We could not confirm this payment yet. If money was deducted, please contact support with your transaction reference: ' + txRef);
                        submitBtn.disabled = false;
                        btnText.innerHTML = '<i class="fas fa-credit-card"></i> Continue to Payment';
                        return;
                    }

                    setTimeout(() => checkPaymentStatusAfterModal(txRef, attempt + 1), 2000);
                });
            }
        });
    </script>
    <?= view('university_portal/partials/shell_end', ['active_nav' => 'subscription']) ?>
</body>
</html>
