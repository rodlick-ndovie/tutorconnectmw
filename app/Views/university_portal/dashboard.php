<?php
$firstName = trim((string) ($user['first_name'] ?? ''));
$lastName = trim((string) ($user['last_name'] ?? ''));
$displayName = trim($firstName . ' ' . $lastName);
if ($displayName === '') {
    $displayName = (string) ($profile['full_name'] ?? 'University Tutor');
}

$applicationTone = (string) ($application_status['tone'] ?? 'info');
$applicationIcon = [
    'success' => 'fa-check-circle',
    'warning' => 'fa-clock',
    'danger' => 'fa-triangle-exclamation',
    'info' => 'fa-circle-info',
][$applicationTone] ?? 'fa-circle-info';

$subscriptionName = $current_subscription['plan_name'] ?? null;
$subscriptionEnd = trim((string) ($current_subscription['current_period_end'] ?? ''));
$subscriptionEndText = $subscriptionEnd !== '' ? date('M j, Y', strtotime($subscriptionEnd)) : '';
$completionTotal = count($profile_completion_gaps ?? []);
$profileProgress = $profile_ready_for_review ? 100 : max(15, min(95, 100 - ($completionTotal * 8)));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#E55C0D">
    <title><?= esc($title ?? 'University Professional Dashboard') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #E55C0D;
            --primary-dark: #C94609;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --accent: #0ea5e9;
            --text-dark: #1f2937;
            --text-muted: #6b7280;
            --bg: #f8fafc;
            --card: #ffffff;
            --border: #e5e7eb;
            --radius: 16px;
            --radius-lg: 20px;
            --shadow: 0 1px 2px rgba(15, 23, 42, 0.06);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--text-dark);
            font-family: -apple-system, BlinkMacSystemFont, "SF Pro Display", "SF Pro Text", "Segoe UI", Roboto, Arial, sans-serif;
        }

        .page {
            width: 100%;
            max-width: 1180px;
            margin: 0 auto;
            padding: 18px 16px 28px;
        }

        .welcome-section {
            background: var(--primary);
            color: #fff;
            border-radius: var(--radius-lg);
            padding: 26px 22px;
            margin-bottom: 18px;
            box-shadow: var(--shadow);
        }

        .welcome-kicker {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.78rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0;
            color: rgba(255, 255, 255, 0.86);
            margin-bottom: 10px;
        }

        .welcome-title {
            margin: 0;
            font-size: clamp(1.65rem, 3vw, 2.2rem);
            font-weight: 800;
            line-height: 1.15;
        }

        .welcome-subtitle {
            margin: 8px 0 0;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.55;
            max-width: 760px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 18px;
        }

        .content-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.1fr) minmax(320px, 0.9fr);
            gap: 18px;
            align-items: start;
        }

        .stack {
            display: grid;
            gap: 18px;
        }

        .dashboard-card {
            background: var(--card);
            border: 1px solid rgba(15, 23, 42, 0.06);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .card-header-lite {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 16px;
            border-bottom: 1px solid rgba(15, 23, 42, 0.06);
        }

        .card-title-lite {
            margin: 0;
            font-size: 1.05rem;
            font-weight: 800;
            color: var(--text-dark);
        }

        .card-content-lite {
            padding: 16px;
        }

        .stat-card {
            position: relative;
            padding: 16px;
        }

        .stat-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary);
        }

        .stat-icon {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            background: rgba(229, 92, 13, 0.10);
            color: var(--primary);
            font-size: 1.2rem;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 850;
            line-height: 1.1;
            color: var(--text-dark);
        }

        .stat-label {
            margin-top: 6px;
            font-size: 0.78rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0;
            color: var(--text-muted);
        }

        .status-display {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 15px;
            border-radius: 14px;
            border: 1px solid transparent;
        }

        .status-success { background: #ecfdf5; border-color: #a7f3d0; color: #047857; }
        .status-warning { background: #fffbeb; border-color: #fde68a; color: #92400e; }
        .status-danger { background: #fef2f2; border-color: #fecaca; color: #b91c1c; }
        .status-info { background: #eff6ff; border-color: #bfdbfe; color: #1d4ed8; }

        .status-icon {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            background: rgba(255, 255, 255, 0.65);
            font-size: 1.2rem;
        }

        .status-title {
            margin: 0 0 4px;
            font-weight: 800;
            color: var(--text-dark);
        }

        .status-message {
            margin: 0;
            color: inherit;
            line-height: 1.45;
            font-size: 0.92rem;
        }

        .progress-wrap {
            height: 10px;
            border-radius: 999px;
            background: #e5e7eb;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            width: <?= (int) $profileProgress ?>%;
            background: var(--primary);
        }

        .pill-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 10px;
            border-radius: 999px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #334155;
            font-size: 0.86rem;
            font-weight: 650;
        }

        .muted {
            color: var(--text-muted);
        }

        .setup-card {
            border-color: #fed7aa;
            background: #fff;
        }

        .setup-head {
            display: flex;
            align-items: flex-start;
            gap: 14px;
        }

        .setup-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(229, 92, 13, 0.1);
            color: var(--primary);
            flex: 0 0 auto;
        }

        .dashboard-cta {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 42px;
            border-radius: 12px;
            padding: 10px 14px;
            background: var(--primary);
            color: #fff;
            text-decoration: none;
            font-weight: 800;
            font-size: 0.92rem;
            border: 1px solid var(--primary);
        }

        .dashboard-cta:hover {
            color: #fff;
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .plan-status-modern {
            background: var(--card);
            border: 1px solid #fed7aa;
        }

        .plan-head {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .plan-icon {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            background: var(--primary);
            box-shadow: none;
            flex: 0 0 auto;
        }

        .next-steps {
            margin: 0;
            padding-left: 20px;
        }

        .next-steps li {
            margin-bottom: 8px;
            color: #374151;
            line-height: 1.45;
        }

        @media (max-width: 980px) {
            .stats-grid,
            .content-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 640px) {
            .page {
                padding-left: 14px;
                padding-right: 14px;
            }

            .stats-grid,
            .content-grid {
                grid-template-columns: 1fr;
            }

            .welcome-section {
                padding: 22px 18px;
            }
        }

<?= view('university_portal/partials/shell_styles') ?>
    </style>
</head>
<body>
    <?= view('university_portal/partials/shell_start', [
        'nav_title' => 'Dashboard',
        'nav_subtitle' => 'University Professional',
        'nav_user' => $displayName,
    ]) ?>

    <div class="page">
        <?php if (session('success')): ?>
            <div class="alert alert-success"><?= esc(session('success')) ?></div>
        <?php endif; ?>

        <?php if (session('error')): ?>
            <div class="alert alert-danger"><?= esc(session('error')) ?></div>
        <?php endif; ?>

        <?php if (session('info')): ?>
            <div class="alert alert-info"><?= esc(session('info')) ?></div>
        <?php endif; ?>

        <section class="welcome-section">
            <div class="welcome-kicker">
                <i class="fas fa-building-columns"></i>
                <span>University Professional Portal</span>
            </div>
            <h1 class="welcome-title">Welcome back, <?= esc($firstName !== '' ? $firstName : $displayName) ?>.</h1>
            <p class="welcome-subtitle">Manage your university tutor profile, review status, availability, and professional subscription from one dashboard.</p>
        </section>

        <section class="stats-grid">
            <article class="dashboard-card stat-card">
                <div class="stat-icon"><i class="fas fa-circle-check"></i></div>
                <div class="stat-value"><?= esc($application_status['label'] ?? 'Pending') ?></div>
                <div class="stat-label">Application</div>
            </article>

            <article class="dashboard-card stat-card">
                <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
                <div class="stat-value"><?= (int) $profileProgress ?>%</div>
                <div class="stat-label">Profile Ready</div>
            </article>

            <article class="dashboard-card stat-card">
                <div class="stat-icon"><i class="fas fa-crown"></i></div>
                <div class="stat-value"><?= esc($subscriptionName ?? 'None') ?></div>
                <div class="stat-label">Professional Plan</div>
            </article>
        </section>

        <section class="dashboard-card plan-status-modern" style="margin-bottom: 18px;">
            <div class="card-content-lite">
                <div class="plan-head">
                    <div class="plan-icon"><i class="fas fa-crown"></i></div>
                    <div class="flex-grow-1">
                        <h2 class="card-title-lite"><?= esc($subscriptionName ?? 'No Professional Plan Active') ?></h2>
                        <p class="muted mb-0">
                            <?php if ($current_subscription): ?>
                                <?= $subscriptionEndText !== '' ? 'Active until ' . esc($subscriptionEndText) : 'Your university professional plan is active.' ?>
                            <?php elseif ($can_access_subscription): ?>
                                Choose Basic, Standard, or Premium to increase visibility.
                            <?php else: ?>
                                Admin approval is required before premium plan selection.
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="content-grid">
            <div class="stack">
                <?php if (!$profile_ready_for_review): ?>
                    <article class="dashboard-card setup-card">
                        <div class="card-content-lite">
                            <div class="setup-head">
                                <div class="setup-icon"><i class="fas fa-user-pen"></i></div>
                                <div class="flex-grow-1">
                                    <h2 class="card-title-lite">Profile Setup Required</h2>
                                    <p class="muted mt-2 mb-3">Complete the remaining university professional profile sections so admin can review and approve your application.</p>
                                    <a href="<?= esc($profile_completion_url . '?intro=1') ?>" class="dashboard-cta">
                                        <i class="fas fa-arrow-right"></i>
                                        <span>Continue Profile</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </article>
                <?php endif; ?>

                <article class="dashboard-card">
                    <div class="card-header-lite">
                        <h2 class="card-title-lite">Review Status</h2>
                    </div>
                    <div class="card-content-lite">
                        <div class="status-display status-<?= esc($applicationTone) ?>">
                            <div class="status-icon"><i class="fas <?= esc($applicationIcon) ?>"></i></div>
                            <div>
                                <h3 class="status-title"><?= esc($application_status['label'] ?? 'Application Status') ?></h3>
                                <p class="status-message"><?= esc($application_status['message'] ?? 'Your university profile status will appear here.') ?></p>
                            </div>
                        </div>

                        <div class="mt-3">
                            <div class="d-flex justify-content-between gap-3 mb-2">
                                <strong>Profile completion</strong>
                                <span class="muted"><?= (int) $profileProgress ?>%</span>
                            </div>
                            <div class="progress-wrap"><div class="progress-fill"></div></div>
                        </div>

                        <?php if (!$profile_ready_for_review && !empty($profile_completion_gaps)): ?>
                            <div class="pill-list mt-3">
                                <?php foreach ($profile_completion_gaps as $gap): ?>
                                    <span class="pill"><i class="fas fa-circle-exclamation text-warning"></i><?= esc($gap) ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </article>
            </div>

            <div class="stack">
                <article class="dashboard-card">
                    <div class="card-header-lite">
                        <h2 class="card-title-lite">Next Steps</h2>
                    </div>
                    <div class="card-content-lite">
                        <ol class="next-steps">
                            <?php foreach ($next_steps as $step): ?>
                                <li><?= esc($step) ?></li>
                            <?php endforeach; ?>
                        </ol>
                    </div>
                </article>
            </div>
        </section>
    </div>

    <?= view('university_portal/partials/shell_end', ['active_nav' => 'home']) ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
