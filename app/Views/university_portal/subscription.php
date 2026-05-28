<?php
$maxBillingMonths = isset($max_billing_months) ? max(1, (int) $max_billing_months) : 120;

if (!function_exists('universityPlanLimitText')) {
    function universityPlanLimitText(array $plan, string $field, string $label, string $unlimitedLabel, string $suffix = ''): ?string
    {
        if (!array_key_exists($field, $plan) || $plan[$field] === null || $plan[$field] === '') {
            return null;
        }

        $value = (int) $plan[$field];

        if ($value === 0) {
            return $unlimitedLabel;
        }

        if ($value > 0) {
            return 'Up to ' . number_format($value) . ' ' . $label . $suffix;
        }

        return null;
    }
}

if (!function_exists('universityPlanCustomFeatureLists')) {
    function universityPlanCustomFeatureLists(array $plan): array
    {
        $rawFeatures = trim((string) ($plan['features'] ?? ''));
        $lists = [
            'included' => [],
            'not_included' => [],
            'has_custom' => false,
        ];

        if ($rawFeatures === '') {
            return $lists;
        }

        $decoded = json_decode($rawFeatures, true);

        if (is_array($decoded)) {
            $isList = $decoded === [] || array_keys($decoded) === range(0, count($decoded) - 1);
            $lists['included'] = $isList ? $decoded : (is_array($decoded['included'] ?? null) ? $decoded['included'] : []);
            $lists['not_included'] = $isList ? [] : (is_array($decoded['not_included'] ?? null) ? $decoded['not_included'] : []);
        } else {
            $lists['included'] = preg_split('/\r\n|\r|\n/', $rawFeatures) ?: [];
        }

        $lists['included'] = array_values(array_filter(array_map(static fn ($feature) => trim((string) $feature), $lists['included']), static fn ($feature) => $feature !== ''));
        $lists['not_included'] = array_values(array_filter(array_map(static fn ($feature) => trim((string) $feature), $lists['not_included']), static fn ($feature) => $feature !== ''));
        $lists['has_custom'] = $lists['included'] !== [] || $lists['not_included'] !== [];

        return $lists;
    }
}

if (!function_exists('buildUniversityPlanIncludedFeatures')) {
    function buildUniversityPlanIncludedFeatures(array $plan): array
    {
        $customFeatures = universityPlanCustomFeatureLists($plan);
        if ($customFeatures['has_custom']) {
            return $customFeatures['included'] ?: ['Contact support for this plan feature list'];
        }

        $features = [];

        $profileViews = universityPlanLimitText($plan, 'max_profile_views', 'profile views', 'Unlimited profile views', ' per month');
        if ($profileViews !== null) {
            $features[] = $profileViews;
        }

        $contactClicks = universityPlanLimitText($plan, 'max_clicks', 'contact clicks', 'Unlimited contact clicks', ' per month');
        if ($contactClicks !== null) {
            $features[] = $contactClicks;
        }

        $subjects = universityPlanLimitText($plan, 'max_subjects', 'subjects', 'Unlimited subjects');
        if ($subjects !== null) {
            $features[] = $subjects;
        }

        $reviewsField = array_key_exists('max_reviews', $plan) ? 'max_reviews' : (array_key_exists('max_reviews_display', $plan) ? 'max_reviews_display' : null);
        if ($reviewsField !== null) {
            $reviews = universityPlanLimitText($plan, $reviewsField, 'reviews display', 'Unlimited reviews display');
            if ($reviews !== null) {
                $features[] = $reviews;
            }
        }

        $messages = universityPlanLimitText($plan, 'max_messages', 'messages', 'Unlimited messages', ' per month');
        if ($messages !== null) {
            $features[] = $messages;
        }

        if ((int) ($plan['show_whatsapp'] ?? 0) === 1) {
            $features[] = 'WhatsApp contact visible to students';
        }

        if ((int) ($plan['email_marketing_access'] ?? 0) === 1) {
            $features[] = 'Email marketing access';
        }

        if ((int) ($plan['allow_video_upload'] ?? 0) === 1) {
            $features[] = 'Bio video display capability';
        }

        if ((int) ($plan['allow_pdf_upload'] ?? 0) === 1) {
            $features[] = 'Past Papers PDF upload capability';
        }

        if ((int) ($plan['allow_video_solution'] ?? 0) === 1) {
            $features[] = 'Video solution upload & sharing capability';
        }

        if ((int) ($plan['allow_announcements'] ?? 0) === 1) {
            $features[] = 'School announcements posting access';
        }

        if (!empty($plan['search_ranking']) && $plan['search_ranking'] !== 'low') {
            $rankingLabels = [
                'normal' => 'Normal',
                'priority' => 'Priority',
                'top' => 'Top',
            ];
            $rankingLabel = $rankingLabels[$plan['search_ranking']] ?? ucfirst((string) $plan['search_ranking']);
            $features[] = $rankingLabel . ' search ranking priority';
        }

        if ((int) ($plan['district_spotlight_days'] ?? 0) > 0) {
            $features[] = (int) $plan['district_spotlight_days'] . ' days district spotlight feature';
        }

        return $features ?: ['Basic features included'];
    }
}

if (!function_exists('buildUniversityPlanNotIncludedFeatures')) {
    function buildUniversityPlanNotIncludedFeatures(array $plan): array
    {
        $customFeatures = universityPlanCustomFeatureLists($plan);
        if ($customFeatures['has_custom']) {
            return $customFeatures['not_included'];
        }

        $features = [];

        if ((int) ($plan['show_whatsapp'] ?? 0) === 0) {
            $features[] = 'WhatsApp contact visible to students';
        }

        if ((int) ($plan['email_marketing_access'] ?? 0) === 0) {
            $features[] = 'Email marketing access';
        }

        if ((int) ($plan['allow_video_upload'] ?? 0) === 0) {
            $features[] = 'Bio video display capability';
        }

        if ((int) ($plan['allow_pdf_upload'] ?? 0) === 0) {
            $features[] = 'Past Papers PDF upload capability';
        }

        if ((int) ($plan['allow_video_solution'] ?? 0) === 0) {
            $features[] = 'Video solution upload & sharing capability';
        }

        if ((int) ($plan['allow_announcements'] ?? 0) === 0) {
            $features[] = 'School announcements posting access';
        }

        if ((int) ($plan['district_spotlight_days'] ?? 0) === 0) {
            $features[] = 'District spotlight feature';
        }

        return $features;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'University Subscription Plans') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
            --success: #047857;
            --success-bg: #ecfdf5;
        }

        body {
            margin: 0;
            font-family: Inter, "Segoe UI", Arial, sans-serif;
            background: var(--bg);
            color: var(--text-dark);
        }

        .page {
            max-width: 1180px;
            margin: 0 auto;
            padding: 26px 20px 40px;
        }

        .hero,
        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 20px;
        }

        .hero {
            margin-bottom: 18px;
        }

        .hero-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 16px;
        }

        .btn-soft,
        .btn-primary-soft,
        .plan-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 14px;
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

        .btn-primary-soft,
        .plan-btn {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            border-color: transparent;
        }

        .current-plan {
            background: var(--success-bg);
            border: 1px solid #a7f3d0;
            color: var(--success);
            margin-bottom: 18px;
        }

        .count-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 18px;
        }

        .count-value {
            font-size: 1.55rem;
            font-weight: 800;
            color: var(--text-dark);
            line-height: 1.1;
        }

        .count-label {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 8px;
        }

        .plans-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
        }

        .plan-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 20px;
            display: grid;
            gap: 16px;
        }

        .plan-card.featured {
            border-color: #fdba74;
            box-shadow: 0 12px 30px rgba(229, 92, 13, 0.08);
        }

        .plan-price {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary);
        }

        .plan-meta {
            color: var(--text-muted);
            font-size: 0.92rem;
        }

        .usage-counts {
            display: grid;
            gap: 8px;
            padding: 12px;
            border: 1px solid #f1f5f9;
            border-radius: 12px;
            background: #f8fafc;
        }

        .usage-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            color: #374151;
            font-size: 0.9rem;
        }

        .usage-row strong {
            color: var(--text-dark);
            white-space: nowrap;
        }

        .feature-section-title {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0 0 10px;
            font-size: 0.95rem;
            font-weight: 800;
            color: var(--text-dark);
        }

        .feature-section-title.included i {
            color: var(--success);
        }

        .feature-section-title.excluded i {
            color: #dc2626;
        }

        .feature-list,
        .not-included-list {
            margin: 0;
            padding: 0;
            list-style: none;
            display: grid;
            gap: 9px;
            color: #374151;
            line-height: 1.6;
        }

        .feature-list li,
        .not-included-list li {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            font-size: 0.92rem;
        }

        .feature-list i {
            color: var(--success);
            margin-top: 4px;
        }

        .not-included-list {
            color: #6b7280;
        }

        .not-included-list i {
            color: #dc2626;
            margin-top: 4px;
        }

        .all-included i,
        .all-included span {
            color: var(--success);
        }

        .billing-row {
            display: grid;
            gap: 8px;
        }

        .months-input {
            width: 100%;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 10px 12px;
            font: inherit;
        }

        @media (max-width: 980px) {
            .count-grid,
            .plans-grid {
                grid-template-columns: 1fr;
            }
        }
<?= view('university_portal/partials/shell_styles') ?>
    </style>
</head>
<body>
    <?= view('university_portal/partials/shell_start', [
        'nav_title' => 'Premium',
        'nav_subtitle' => 'University Subscription Plans',
        'nav_user' => trim((string) (($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''))),
        'show_home_shortcut' => true,
    ]) ?>
    <div class="page">
        <section class="hero">
            <h1 style="margin:0 0 8px;">University Subscription Plans</h1>
            <p style="margin:0; color:var(--text-muted); line-height:1.6;">This uses the same payment logic as the tutor portal. Choose a plan below and continue through the shared subscription checkout flow.</p>

            <div class="hero-actions">
                <a href="<?= esc($dashboard_url) ?>" class="btn-soft">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to Dashboard</span>
                </a>
                <a href="<?= esc($complete_profile_url) ?>" class="btn-soft">
                    <i class="fas fa-pen"></i>
                    <span>Edit University Profile</span>
                </a>
                <a href="<?= esc($public_module_url) ?>" class="btn-primary-soft">
                    <i class="fas fa-arrow-up-right-from-square"></i>
                    <span>Public Module</span>
                </a>
            </div>
        </section>

        <?php if ($current_subscription): ?>
            <section class="hero current-plan">
                <strong>Current active plan:</strong>
                <?= esc($current_subscription['plan_name'] ?? 'Subscription') ?>
                <?php if (!empty($current_subscription['current_period_end'])): ?>
                    until <?= date('M j, Y', strtotime($current_subscription['current_period_end'])) ?>
                <?php endif; ?>
            </section>
        <?php endif; ?>

        <section class="plans-grid">
            <?php foreach ($available_plans as $plan): ?>
                <?php
                $includedFeatures = buildUniversityPlanIncludedFeatures($plan);
                $notIncludedFeatures = buildUniversityPlanNotIncludedFeatures($plan);
                $isCurrentPlan = !empty($current_subscription) && (int) ($current_subscription['plan_id'] ?? 0) === (int) $plan['id'];
                $planCounts = $plan['subscription_counts'] ?? [];
                ?>
                <article class="plan-card <?= !empty($plan['is_featured']) ? 'featured' : '' ?>">
                    <div>
                        <div style="font-size:1.35rem; font-weight:800;"><?= esc($plan['name']) ?></div>
                        <div class="plan-price">MWK <?= number_format((float) ($plan['price_monthly'] ?? 0), 0, ',', ',') ?></div>
                        <div class="plan-meta">per month</div>
                    </div>

                    <?php if (!empty($plan['description'])): ?>
                        <div class="plan-meta"><?= esc($plan['description']) ?></div>
                    <?php endif; ?>

                    <div>
                        <h3 class="feature-section-title included">
                            <i class="fas fa-circle-check"></i>
                            <span>What's Included</span>
                        </h3>
                        <ul class="feature-list">
                            <?php foreach ($includedFeatures as $feature): ?>
                                <li><i class="fas fa-check"></i><span><?= esc((string) $feature) ?></span></li>
                            <?php endforeach ?>
                        </ul>
                    </div>

                    <div>
                        <h3 class="feature-section-title excluded">
                            <i class="fas fa-circle-xmark"></i>
                            <span>What's Not Included</span>
                        </h3>
                        <ul class="not-included-list">
                            <?php if ($notIncludedFeatures !== []): ?>
                                <?php foreach ($notIncludedFeatures as $feature): ?>
                                    <li><i class="fas fa-times"></i><span><?= esc((string) $feature) ?></span></li>
                                <?php endforeach ?>
                            <?php else: ?>
                                <li class="all-included"><i class="fas fa-check"></i><span>All premium features included!</span></li>
                            <?php endif ?>
                        </ul>
                    </div>

                    <div class="usage-counts">
                        <div class="usage-row">
                            <span>University active</span>
                            <strong><?= number_format((int) ($planCounts['university_active_subscriptions'] ?? 0)) ?></strong>
                        </div>
                        <div class="usage-row">
                            <span>Tutor portal active</span>
                            <strong><?= number_format((int) ($planCounts['regular_active_subscriptions'] ?? 0)) ?></strong>
                        </div>
                        <div class="usage-row">
                            <span>Total on this plan</span>
                            <strong><?= number_format((int) ($planCounts['total_subscriptions'] ?? 0)) ?></strong>
                        </div>
                    </div>

                    <div class="billing-row">
                        <label for="months-plan-<?= (int) $plan['id'] ?>">Billing Months</label>
                        <input
                            class="months-input"
                            id="months-plan-<?= (int) $plan['id'] ?>"
                            type="number"
                            min="1"
                            max="<?= (int) $maxBillingMonths ?>"
                            value="1"
                        >
                    </div>

                    <button type="button" class="plan-btn choose-plan-btn" data-plan-id="<?= (int) $plan['id'] ?>">
                        <i class="fas <?= $isCurrentPlan ? 'fa-rotate-right' : 'fa-arrow-right' ?>"></i>
                        <span><?= $isCurrentPlan ? 'Renew / Extend Plan' : 'Choose This Plan' ?></span>
                    </button>
                </article>
            <?php endforeach; ?>
        </section>
    </div>

    <script>
        const checkoutBaseUrl = <?= json_encode($checkout_base_url) ?>;
        const maxBillingMonths = <?= json_encode($maxBillingMonths) ?>;

        function normalizeMonths(value) {
            let months = parseInt(value, 10);

            if (Number.isNaN(months) || months < 1) {
                months = 1;
            }

            if (months > maxBillingMonths) {
                months = maxBillingMonths;
            }

            return months;
        }

        document.querySelectorAll('.choose-plan-btn').forEach((button) => {
            button.addEventListener('click', function () {
                const planId = this.getAttribute('data-plan-id');
                const monthsInput = document.getElementById(`months-plan-${planId}`);
                const months = normalizeMonths(monthsInput ? monthsInput.value : 1);

                if (monthsInput) {
                    monthsInput.value = months;
                }

                this.disabled = true;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Loading...</span>';

                window.location.href = `${checkoutBaseUrl}${planId}?months=${months}`;
            });
        });
    </script>
    <?= view('university_portal/partials/shell_end', ['active_nav' => 'subscription']) ?>
</body>
</html>
