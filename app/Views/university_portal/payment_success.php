<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success - University Portal</title>
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
            background: #ecfdf5;
            color: #047857;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            margin-bottom: 18px;
        }

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

        .row:last-child {
            border-bottom: none;
        }

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

        .btn-soft {
            color: #1f2937;
            background: #fff;
        }

        .btn-primary-soft {
            color: #fff;
            border-color: transparent;
            background: linear-gradient(135deg, #e55c0d, #c94609);
        }
<?= view('university_portal/partials/shell_styles') ?>
    </style>
</head>
<body>
    <?= view('university_portal/partials/shell_start', [
        'nav_title' => 'Premium',
        'nav_subtitle' => 'Payment Successful',
        'nav_user' => trim((string) ((session()->get('first_name') ?? '') . ' ' . (session()->get('last_name') ?? ''))),
        'show_home_shortcut' => true,
    ]) ?>
    <div class="page">
        <section class="card">
            <div class="icon"><i class="fas fa-check"></i></div>
            <h1 style="margin:0 0 8px;">Payment Successful</h1>
            <p style="margin:0; color:#6b7280; line-height:1.6;"><?= esc($message ?? 'Your subscription is now active.') ?></p>

            <?php if ($subscription): ?>
                <div class="details">
                    <div class="row">
                        <span>Plan</span>
                        <strong><?= esc($subscription['plan_name'] ?? 'Subscription') ?></strong>
                    </div>
                    <div class="row">
                        <span>Status</span>
                        <strong>Active</strong>
                    </div>
                    <?php if (!empty($subscription['payment_amount'])): ?>
                        <div class="row">
                            <span>Amount</span>
                            <strong>MWK <?= number_format((float) $subscription['payment_amount'], 0, ',', ',') ?></strong>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($subscription['current_period_end'])): ?>
                        <div class="row">
                            <span>Valid Until</span>
                            <strong><?= date('M j, Y', strtotime($subscription['current_period_end'])) ?></strong>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="actions">
                <a href="<?= esc($dashboard_url) ?>" class="btn-primary-soft">
                    <i class="fas fa-house"></i>
                    <span>Back to University Portal</span>
                </a>
                <a href="<?= esc($subscription_url) ?>" class="btn-soft">
                    <i class="fas fa-credit-card"></i>
                    <span>Manage Subscription</span>
                </a>
            </div>
        </section>
    </div>
    <?= view('university_portal/partials/shell_end', ['active_nav' => 'subscription']) ?>
</body>
</html>
