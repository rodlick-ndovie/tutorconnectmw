<?php
$userId = session()->get('user_id');
$userModel = new \App\Models\User();
$user = $userModel->find($userId);

// Check if user is active and approved
if (!$user ||
    !$user['is_active'] ||
    !$user['email_verified_at'] ||
    $user['tutor_status'] === 'pending' ||
    ($user['tutor_status'] !== 'approved' && $user['tutor_status'] !== 'active') ||
    ($user['is_verified'] ?? 0) != 1) {
    // Redirect unapproved/unverified users to dashboard
    header('Location: ' . base_url('trainer/dashboard'));
    exit;
}

// Ensure $subscription is always defined for upgrade logic
if (!isset($subscription)) {
    $subscription = isset($current_subscription) && is_array($current_subscription) ? $current_subscription : [];
}

// Defensive: ensure all plan keys exist and avoid undefined index errors
function safe($arr, $key, $default = '') {
    return isset($arr[$key]) ? $arr[$key] : $default;
}

// Defensive: ensure $current_subscription plan info is available
$current_plan_id = isset($current_subscription['plan_id']) ? $current_subscription['plan_id'] : null;
$current_plan = null;
foreach ($available_plans as $plan) {
    if ($plan['id'] == $current_plan_id) {
        $current_plan = $plan;
        break;
    }
}
$current_plan_name = $current_plan ? safe($current_plan, 'name', 'Current Plan') : 'Current Plan';
$current_plan_price = $current_plan ? floatval(safe($current_plan, 'price_monthly', 0)) : 0;

// Add current plan name and price to subscription array
$subscription['current_plan'] = $current_plan_name;
$subscription['price_monthly'] = $current_plan_price;

// Also add price_monthly to current_subscription for comparison logic
if ($current_subscription) {
    $current_subscription['price_monthly'] = $current_plan_price;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#E55C0D">
    <title>Subscription Plans - TutorConnect Malawi</title>
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

        .status-bar { height: 0; background: var(--bg-secondary); border-bottom: 1px solid rgba(0,0,0,0.1); position: sticky; top: 0; z-index: 100; }
        @media (min-width: 768px) { .status-bar { display: none; } }
        @media (max-width: 767px) { .status-bar { display: none; } }

        .main-content {
            padding: 16px;
            padding-bottom: 100px; /* Space for bottom nav */
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
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
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
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
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

        /* Professional Badge Styles */
        .plan-badge-overlay {
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10;
            display: flex;
            align-items: center;
            gap: 6px;
            background: linear-gradient(135deg, #ff6b35, #f7931e);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
            font-weight: 600;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            animation: badgePulse 2s ease-in-out infinite;
        }

        .plan-badge-overlay.most-popular {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            box-shadow: 0 4px 12px rgba(229, 88, 13, 0.3);
        }

        .plan-badge-overlay.free-badge {
            background: linear-gradient(135deg, var(--success-color), #059669);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .badge-icon {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .badge-text {
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-arrow {
            position: absolute;
            bottom: -6px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 6px solid transparent;
            border-right: 6px solid transparent;
            border-top: 6px solid rgba(255, 107, 53, 0.8);
        }

        .most-popular .badge-arrow {
            border-top-color: rgba(229, 88, 13, 0.8);
        }

        .free-badge .badge-arrow {
            border-top-color: rgba(16, 185, 129, 0.8);
        }

        @keyframes badgePulse {
            0%, 100% {
                transform: translateX(-50%) scale(1);
            }
            50% {
                transform: translateX(-50%) scale(1.05);
            }
        }

        /* Enhanced plan card styling for badges */
        .plan-card {
            position: relative;
            overflow: visible;
            margin-top: 20px; /* Space for badge */
        }

        /* What's Not Included section styles */
        .not-included-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .not-included-title i {
            color: var(--danger-color);
            font-size: 0.9rem;
        }

        .not-included-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .not-included-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
        }

        .not-included-item i {
            color: var(--danger-color);
            font-size: 0.8rem;
            flex-shrink: 0;
        }

        .not-included-item span {
            color: var(--text-secondary);
        }

        .not-included-item.all-included i {
            color: var(--success-color);
        }

        .not-included-item.all-included span {
            color: var(--success-color);
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Mobile Status Bar Simulation -->
        <div class="status-bar"></div>

        <!-- Navigation Bar -->
        <nav class="navbar">
            <div class="d-flex align-items-center justify-content-between w-100 px-3">
                <div class="d-flex align-items-center">
                    <h1 class="nav-title mb-0 me-3">Premium</h1>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn p-0 border-0 bg-transparent nav-button" onclick="window.location.href='<?php echo base_url('logout'); ?>'">
                        <i class="fas fa-sign-out-alt text-muted" style="font-size: 20px;"></i>
                    </button>
                    <div class="avatar">
                        <?= strtoupper(substr(session()->get('first_name') ?? 'T', 0, 1)) ?>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="screen">
            <div class="page-header">
                <h1 class="page-title">Subscription Plans</h1>
                <p class="page-subtitle">Choose the plan that best fits your tutoring needs</p>
            </div>

            <!-- Available Plans -->
            <div class="plans-grid">
                <?php foreach($available_plans as $index => $plan): ?>
                <?php
                    $is_current = isset($current_subscription) && $current_subscription &&
                                $current_subscription['plan_id'] == $plan['id'];
                    $is_free = $plan['price_monthly'] == 0;

                    // Check if user has already used a free trial (only for free plans)
                    $hasUsedFreeTrial = false;
                    if ($is_free) {
                        $subscriptionModel = new \App\Models\TutorSubscriptionModel();
                        $planModel = new \App\Models\SubscriptionPlanModel();
                        $existingSubscriptions = $subscriptionModel->where('user_id', $userId)->findAll();

                        foreach ($existingSubscriptions as $existingSub) {
                            $existingPlan = $planModel->find($existingSub['plan_id']);
                            if ($existingPlan && $existingPlan['price_monthly'] == 0) {
                                $hasUsedFreeTrial = true;
                                break;
                            }
                        }
                    }
                ?>
                <div class="plan-card <?= $index === 1 ? 'featured' : '' ?> <?= $is_free ? 'free-plan' : '' ?>">
                    <?php if($index === 1): ?>
                        <div class="plan-badge-overlay most-popular">
                            <span class="badge-text">Most Popular</span>
                            <div class="badge-arrow"></div>
                        </div>
                    <?php elseif($is_free): ?>
                        <div class="plan-badge-overlay free-badge">
                            <i class="fas fa-gift badge-icon"></i>
                            <span class="badge-text">FREE</span>
                            <div class="badge-arrow"></div>
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
                        <?php
                        // Display features based on individual database fields
                        $features = [];

                        // Profile views
                        if (($plan['max_profile_views'] ?? 0) == 0) {
                            $features[] = 'Unlimited profile views';
                        } elseif (($plan['max_profile_views'] ?? 0) > 0) {
                            $features[] = 'Up to ' . number_format($plan['max_profile_views']) . ' profile views per month';
                        }

                        // Contact clicks
                        if (($plan['max_clicks'] ?? 0) == 0) {
                            $features[] = 'Unlimited contact clicks';
                        } elseif (($plan['max_clicks'] ?? 0) > 0) {
                            $features[] = 'Up to ' . number_format($plan['max_clicks']) . ' contact clicks per month';
                        }

                        // Subjects
                        if (($plan['max_subjects'] ?? 0) == 0) {
                            $features[] = 'Unlimited subjects';
                        } elseif (($plan['max_subjects'] ?? 0) > 0) {
                            $features[] = 'Up to ' . $plan['max_subjects'] . ' subjects';
                        }

                        // Messages
                        if (($plan['max_messages'] ?? 0) == 0) {
                            $features[] = 'Unlimited messages';
                        } elseif (($plan['max_messages'] ?? 0) > 0) {
                            $features[] = 'Up to ' . number_format($plan['max_messages']) . ' messages per month';
                        }

                        // WhatsApp contact
                        if (($plan['show_whatsapp'] ?? 0) == 1) {
                            $features[] = 'WhatsApp contact visible to students';
                        }

                        // Email marketing
                        if (($plan['email_marketing_access'] ?? 0) == 1) {
                            $features[] = 'Email marketing access & tools';
                        }

                        // Video upload
                        if (($plan['allow_video_upload'] ?? 0) == 1) {
                            $features[] = 'Bio video display capability';
                        }

                        // PDF upload
                        if (($plan['allow_pdf_upload'] ?? 0) == 1) {
                            $features[] = 'Past Papers PDF upload capability';
                        }

                        // Video solution upload
                        if (($plan['allow_video_solution'] ?? 0) == 1) {
                            $features[] = 'Video solution upload & sharing capability';
                        }

                        // Announcements
                        if (($plan['allow_announcements'] ?? 0) == 1) {
                            $features[] = 'School announcements posting access';
                        }

                        // Search ranking
                        if (!empty($plan['search_ranking']) && $plan['search_ranking'] !== 'low') {
                            $rankingLabels = [
                                'normal' => 'Normal',
                                'priority' => 'Priority',
                                'top' => 'Top'
                            ];
                            $rankingLabel = $rankingLabels[$plan['search_ranking']] ?? ucfirst($plan['search_ranking']);
                            $features[] = $rankingLabel . ' search ranking priority';
                        }

                        // District spotlight
                        if (($plan['district_spotlight_days'] ?? 0) > 0) {
                            $features[] = $plan['district_spotlight_days'] . ' days district spotlight feature';
                        }

                        // Badge level
                        if (!empty($plan['badge_level']) && $plan['badge_level'] !== 'none') {
                            $badgeLabels = [
                                'beginner' => 'Beginner',
                                'intermediate' => 'Intermediate',
                                'advanced' => 'Advanced',
                                'expert' => 'Expert',
                                'master' => 'Master'
                            ];
                            $badgeLabel = $badgeLabels[$plan['badge_level']] ?? ucfirst(str_replace('_', ' ', $plan['badge_level']));
                            $features[] = $badgeLabel . ' profile badge & visibility';
                        }

                        // Display features
                        if (!empty($features)) {
                            foreach ($features as $feature) {
                                echo '<li><i class="fas fa-check text-success"></i> ' . htmlspecialchars($feature) . '</li>';
                            }
                        } else {
                            echo '<li><i class="fas fa-check text-success"></i> Basic features included</li>';
                        }
                        ?>
                    </ul>

                    <!-- What's Not Included -->
                    <div class="mt-4">
                        <h6 class="not-included-title">
                            <i class="fas fa-times-circle"></i>
                            What's Not Included
                        </h6>
                        <ul class="not-included-list">
                            <?php
                            $notIncludedFeatures = [];

                            // Only show key missing features without repeating what's already shown in "What's Included"

                            // WhatsApp contact (if not included)
                            if (($plan['show_whatsapp'] ?? 0) == 0) {
                                $notIncludedFeatures[] = 'WhatsApp contact visibility';
                            }

                            // Email marketing (if not included)
                            if (($plan['email_marketing_access'] ?? 0) == 0) {
                                $notIncludedFeatures[] = 'Email marketing access & tools';
                            }

                            // Video upload (if not included)
                            if (($plan['allow_video_upload'] ?? 0) == 0) {
                                $notIncludedFeatures[] = 'Bio video display capability';
                            }

                            // PDF upload (if not included)
                            if (($plan['allow_pdf_upload'] ?? 0) == 0) {
                                $notIncludedFeatures[] = 'Past Papers PDF upload capability';
                            }

                            // Announcements (if not included)
                            if (($plan['allow_announcements'] ?? 0) == 0) {
                                $notIncludedFeatures[] = 'School announcements posting';
                            }

                            // District spotlight (if not included)
                            if (($plan['district_spotlight_days'] ?? 0) == 0) {
                                $notIncludedFeatures[] = 'District spotlight feature';
                            }

                            // Display not included features
                            if (!empty($notIncludedFeatures)) {
                                foreach ($notIncludedFeatures as $feature) {
                                    echo '<li class="not-included-item"><i class="fas fa-times"></i><span>' . htmlspecialchars($feature) . '</span></li>';
                                }
                            } else {
                                echo '<li class="not-included-item all-included"><i class="fas fa-check"></i><span>All premium features included!</span></li>';
                            }
                            ?>
                        </ul>
                    </div>

                    <?php if($is_current): ?>
                        <button class="plan-button btn btn-success" disabled>
                            <i class="fas fa-check me-2"></i>Active Plan
                        </button>
                    <?php elseif($is_free && $hasUsedFreeTrial): ?>
                        <button class="plan-button btn btn-secondary" disabled>
                            <i class="fas fa-times me-2"></i>Already Used
                        </button>
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
        </main>

        <!-- Modals -->
        <!-- Upgrade Modal -->
        <div class="modal fade" id="upgradeModal" tabindex="-1" role="dialog" aria-labelledby="upgradeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="upgradeModalLabel">
                            <i class="fas fa-arrow-up text-primary me-2"></i>Upgrade Subscription
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-3">Choose a higher-tier plan to get more features and benefits.</p>

                        <div class="upgrade-options">
                            <?php if(isset($available_plans)): ?>
                                <?php foreach($available_plans as $plan): ?>
                                    <?php
                                        $is_current = isset($current_subscription) && $current_subscription &&
                                                    $current_subscription['plan_id'] == $plan['id'];
                                        $is_higher_tier = isset($current_subscription) && $current_subscription &&
                                                        $plan['price_monthly'] > $current_subscription['price_monthly'];
                                        $is_free = $plan['price_monthly'] == 0;

                                        if ($is_current || !$is_higher_tier) continue; // Skip current plan and lower tiers
                                    ?>
                                    <div class="upgrade-option-card mb-3 p-3 border rounded" data-plan-id="<?= $plan['id'] ?>">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1"><?= htmlspecialchars($plan['name']) ?> Plan</h6>
                                                <p class="mb-1 text-muted">MWK <?= number_format($plan['price_monthly']) ?>/month</p>
                                                <small class="text-success">
                                                    <i class="fas fa-arrow-up me-1"></i>
                                                    Upgrade from <?= htmlspecialchars($subscription['current_plan']) ?>
                                                </small>
                                            </div>
                                            <button type="button" class="btn btn-primary upgrade-btn"
                                                    data-plan-id="<?= $plan['id'] ?>"
                                                    data-plan-name="<?= htmlspecialchars($plan['name']) ?>">
                                                Upgrade
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Upgrade Benefits:</strong> You'll get immediate access to new features. Your billing will be prorated for this month.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cancel Modal -->
        <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-danger" id="cancelModalLabel">
                            <i class="fas fa-exclamation-triangle text-danger me-2"></i>Cancel Subscription
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger">
                            <h6><i class="fas fa-exclamation-triangle me-2"></i>Are you sure you want to cancel?</h6>
                            <p class="mb-2">This action cannot be undone. You will lose access to premium features:</p>
                            <ul class="mb-0">
                                <li>Limited profile visibility</li>
                                <li>Reduced contact clicks</li>
                                <li>Basic plan restrictions</li>
                                <li>No video upload access</li>
                            </ul>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="confirmCancel">
                            <label class="form-check-label" for="confirmCancel">
                                I understand that my subscription will be cancelled at the end of the current billing period.
                            </label>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Keep Subscription</button>
                            <button type="button" class="btn btn-danger" id="confirmCancelBtn" disabled>
                                <i class="fas fa-times me-2"></i>Cancel Subscription
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
    <script>
        // Subscription Page JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            // Active navigation highlighting
            const currentPath = window.location.pathname;
            const navItems = document.querySelectorAll('.nav-item');

            // Helper function to check if navigation item should be active
            function shouldBeActive(item) {
                const href = item.getAttribute('href');
                if (!href) return false;

                // Check if href contains key parts of current path
                if (href.includes('/trainer/dashboard') && currentPath.includes('/trainer/dashboard')) return true;
                if (href.includes('/trainer/subjects') && currentPath.includes('/trainer/subjects')) return true;
                if (href.includes('/trainer/profile') && currentPath.includes('/trainer/profile')) return true;
                if (href.includes('/trainer/subscription') && currentPath.includes('/trainer/subscription')) return true;

                return false;
            }

            navItems.forEach(item => {
                if (shouldBeActive(item)) {
                    item.classList.add('active');
                } else {
                    item.classList.remove('active');
                }
            });

            const subscribeButtons = document.querySelectorAll('.subscribe-btn');
            const upgradeButtons = document.querySelectorAll('.upgrade-btn');
            const confirmCancelBtn = document.getElementById('confirmCancelBtn');
            const confirmCancelCheckbox = document.getElementById('confirmCancel');

            // Handle subscribe buttons - redirect to checkout page
            subscribeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const planId = this.getAttribute('data-plan-id');

                    // Show loading spinner
                    this.disabled = true;
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Loading...';

                    // Redirect after a brief delay to show spinner
                    setTimeout(() => {
                        window.location.href = `<?= base_url('trainer/checkout/subscription/') ?>${planId}`;
                    }, 300);
                });
            });

            // Handle upgrade buttons in modal - redirect to checkout page
            upgradeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const planId = this.getAttribute('data-plan-id');

                    // Show loading spinner
                    this.disabled = true;
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Loading...';

                    // Redirect after a brief delay to show spinner
                    setTimeout(() => {
                        window.location.href = `<?= base_url('trainer/checkout/subscription/') ?>${planId}`;
                    }, 300);
                });
            });

            // Handle cancel confirmation checkbox
            if (confirmCancelCheckbox && confirmCancelBtn) {
                confirmCancelCheckbox.addEventListener('change', function() {
                    confirmCancelBtn.disabled = !this.checked;
                });
            }

            // Handle cancel subscription
            if (confirmCancelBtn) {
                confirmCancelBtn.addEventListener('click', async function() {
                    this.disabled = true;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Cancelling...';

                    try {
                        const response = await fetch('<?= base_url('trainer/cancel-subscription') ?>', {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const result = await response.json();

                        if (result.success) {
                            // Close modal and redirect
                            const cancelModal = bootstrap.Modal.getInstance(document.getElementById('cancelModal'));
                            cancelModal.hide();

                            alert('Subscription cancelled successfully. You will retain access until the end of your billing period.');
                            window.location.reload();
                        } else {
                            alert(result.message || 'Failed to cancel subscription. Please try again.');
                            this.disabled = false;
                            this.innerHTML = '<i class="fas fa-times me-2"></i>Cancel Subscription';
                        }
                    } catch (error) {
                        console.error('Cancel error:', error);
                        alert('Network error. Please check your connection and try again.');
                        this.disabled = false;
                        this.innerHTML = '<i class="fas fa-times me-2"></i>Cancel Subscription';
                    }
                });
            }
        });
    </script>

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
</body>
</html>
