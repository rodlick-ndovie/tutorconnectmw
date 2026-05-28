<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed - University Portal</title>
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
            background: #fef2f2;
            color: #b91c1c;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            margin-bottom: 18px;
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
        'nav_subtitle' => 'Payment Unsuccessful',
        'nav_user' => trim((string) ((session()->get('first_name') ?? '') . ' ' . (session()->get('last_name') ?? ''))),
        'show_home_shortcut' => true,
    ]) ?>
    <div class="page">
        <section class="card">
            <div class="icon"><i class="fas fa-times"></i></div>
            <h1 style="margin:0 0 8px;">Payment Could Not Be Completed</h1>
            <p style="margin:0; color:#6b7280; line-height:1.6;"><?= esc($message ?? 'Please try the payment again or contact support if the issue continues.') ?></p>

            <div class="actions">
                <a href="<?= esc($subscription_url) ?>" class="btn-primary-soft">
                    <i class="fas fa-rotate-right"></i>
                    <span>Try Again</span>
                </a>
                <a href="<?= esc($dashboard_url) ?>" class="btn-soft">
                    <i class="fas fa-house"></i>
                    <span>Back to University Portal</span>
                </a>
            </div>
        </section>
    </div>
    <?= view('university_portal/partials/shell_end', ['active_nav' => 'subscription']) ?>
</body>
</html>
