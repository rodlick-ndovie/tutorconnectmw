<!DOCTYPE html>
<html lang="en">
<?php
$resultStatus = (string) ($status ?? 'processing');
$isSuccess = $resultStatus === 'success';
$isProcessing = $resultStatus === 'processing';
$isFailed = $resultStatus === 'failed';

$iconClass = $isSuccess ? 'fa-check' : ($isFailed ? 'fa-times' : 'fa-clock');
$iconToneClass = $isSuccess ? 'icon-success' : ($isFailed ? 'icon-failed' : 'icon-processing');
$pageTitle = $isSuccess ? 'Payment Successful' : ($isFailed ? 'Payment Failed' : 'Processing Payment');
$pageSubtitle = $isSuccess ? 'Subscription activated' : ($isFailed ? 'Payment unsuccessful' : 'Transaction result');
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($pageTitle) ?> - University Portal</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: Inter, "Segoe UI", Arial, sans-serif;
            background: #f8fafc;
            color: #1f2937;
        }

        .page {
            max-width: 760px;
            margin: 0 auto;
            padding: 32px 20px 40px;
        }

        .card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 18px;
            padding: 26px;
            text-align: center;
        }

        .icon {
            width: 86px;
            height: 86px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            margin-bottom: 18px;
        }

        .icon-success { background: #ecfdf5; color: #047857; }
        .icon-processing { background: #fff7ed; color: #c94609; }
        .icon-failed { background: #fef2f2; color: #b91c1c; }

        .details {
            text-align: left;
            margin-top: 20px;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 16px;
        }

        .row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .row:last-child { border-bottom: none; }

        .actions {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 20px;
        }

        .btn-soft,
        .btn-primary-soft {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 16px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            border: 1px solid #e5e7eb;
        }

        .btn-soft { color: #1f2937; background: #fff; }

        .btn-primary-soft {
            color: #fff;
            border-color: transparent;
            background: linear-gradient(135deg, #e55c0d, #c94609);
        }

        .spinner {
            width: 24px;
            height: 24px;
            border: 3px solid #fed7aa;
            border-top-color: #e55c0d;
            border-radius: 50%;
            margin: 20px auto 8px;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
<?= view('university_portal/partials/shell_styles') ?>
    </style>
</head>
<body>
    <?= view('university_portal/partials/shell_start', [
        'nav_title' => 'Premium',
        'nav_subtitle' => $pageSubtitle,
        'nav_user' => trim((string) ($portal_display_name ?? ((session()->get('first_name') ?? '') . ' ' . (session()->get('last_name') ?? '')))),
        'show_home_shortcut' => true,
    ]) ?>
    <div class="page">
        <section class="card">
            <div class="icon <?= esc($iconToneClass) ?>"><i class="fas <?= esc($iconClass) ?>"></i></div>
            <h1 style="margin:0 0 8px;"><?= esc($pageTitle) ?></h1>
            <p style="margin:0; color:#6b7280; line-height:1.6;"><?= esc($message ?? 'Your payment status is being checked.') ?></p>

            <?php if ($isProcessing): ?>
                <div id="status-checker">
                    <div class="spinner"></div>
                    <p style="margin:0; color:#6b7280;">Checking payment status...</p>
                </div>
            <?php endif; ?>

            <?php if ($subscription): ?>
                <div class="details">
                    <div class="row">
                        <span>Plan</span>
                        <strong><?= esc($subscription['plan_name'] ?? 'Subscription') ?></strong>
                    </div>
                    <?php if (!empty($subscription['payment_amount'])): ?>
                        <div class="row">
                            <span>Amount</span>
                            <strong>MWK <?= number_format((float) $subscription['payment_amount'], 0, ',', ',') ?></strong>
                        </div>
                    <?php endif; ?>
                    <div class="row">
                        <span>Status</span>
                        <strong><?= $isSuccess ? 'Active' : ($isFailed ? 'Failed' : 'Pending confirmation') ?></strong>
                    </div>
                    <?php if ($isSuccess && !empty($subscription['current_period_end'])): ?>
                        <div class="row">
                            <span>Valid Until</span>
                            <strong><?= date('M j, Y', strtotime($subscription['current_period_end'])) ?></strong>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="actions">
                <?php if ($isFailed): ?>
                    <a href="<?= esc($subscription_url) ?>" class="btn-primary-soft">
                        <i class="fas fa-rotate-right"></i>
                        <span>Try Again</span>
                    </a>
                <?php else: ?>
                    <a href="<?= esc($dashboard_url) ?>" class="btn-primary-soft">
                        <i class="fas fa-house"></i>
                        <span>Back to University Portal</span>
                    </a>
                <?php endif; ?>
                <a href="<?= esc($subscription_url) ?>" class="btn-soft">
                    <i class="fas fa-credit-card"></i>
                    <span>Manage Subscription</span>
                </a>
            </div>
        </section>
    </div>

    <?php if ($isProcessing && !empty($enablePolling) && !empty($txRef)): ?>
        <script>
            let pollAttempts = 0;
            const maxPollAttempts = 24;
            const txRef = <?= json_encode($txRef) ?>;
            const statusUrl = <?= json_encode($check_payment_status_url ?? '') ?>;

            function checkPaymentStatus() {
                if (!statusUrl) {
                    return;
                }

                pollAttempts += 1;

                fetch(statusUrl, {
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
                        window.location.reload();
                        return;
                    }

                    if (data.status === 'failed') {
                        window.location.reload();
                        return;
                    }

                    if (pollAttempts >= maxPollAttempts) {
                        const checker = document.getElementById('status-checker');
                        if (checker) {
                            checker.innerHTML = '<p style="color:#6b7280;">We could not confirm this payment yet. If money was deducted, please contact support with your transaction reference.</p>';
                        }
                        return;
                    }

                    setTimeout(checkPaymentStatus, 5000);
                })
                .catch(() => {
                    if (pollAttempts >= maxPollAttempts) {
                        return;
                    }

                    setTimeout(checkPaymentStatus, 5000);
                });
            }

            checkPaymentStatus();
        </script>
    <?php endif; ?>
    <?= view('university_portal/partials/shell_end', ['active_nav' => 'subscription']) ?>
</body>
</html>
