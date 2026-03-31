<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Analytics - TutorConnect Malawi</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('favicon.ico') ?>">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #E55C0D;
            --secondary-color: #C94609;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --bg-primary: #f8fafc;
            --bg-secondary: #ffffff;
            --border-color: #e2e8f0;
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --border-radius: 12px;
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
            line-height: 1.6;
        }

        .analytics-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .page-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            font-size: 1.1rem;
            color: var(--text-light);
            max-width: 600px;
            margin: 0 auto;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: var(--bg-secondary);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
            transition: var(--transition);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .stat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .stat-icon.profile-views { background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; }
        .stat-icon.clicks { background: linear-gradient(135deg, #10b981, #059669); color: white; }
        .stat-icon.messages { background: linear-gradient(135deg, #f59e0b, #d97706); color: white; }
        .stat-icon.uploads { background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: white; }

        .stat-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0.5rem 0;
        }

        .stat-progress {
            width: 100%;
            height: 8px;
            background: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 1rem;
        }

        .stat-progress-bar {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            border-radius: 4px;
            transition: width 0.3s ease;
        }

        .stat-status {
            font-size: 0.8rem;
            font-weight: 600;
            margin-top: 0.5rem;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            text-align: center;
        }

        .stat-status.normal { background: #dcfce7; color: #166534; }
        .stat-status.warning { background: #fef3c7; color: #92400e; }
        .stat-status.exceeded { background: #fee2e2; color: #991b1b; }

        .charts-section {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
            margin-bottom: 3rem;
        }

        @media (min-width: 768px) {
            .charts-section {
                grid-template-columns: 1fr 1fr;
            }
        }

        .chart-card {
            background: var(--bg-secondary);
            border-radius: var(--border-radius);
            padding: 2rem;
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
        }

        .chart-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .chart-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .chart-subtitle {
            font-size: 0.9rem;
            color: var(--text-light);
            margin-top: 0.25rem;
        }

        .activity-section {
            margin-bottom: 3rem;
        }

        .activity-card {
            background: var(--bg-secondary);
            border-radius: var(--border-radius);
            padding: 2rem;
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
        }

        .activity-list {
            space-y: 1rem;
        }

        .activity-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            background: var(--bg-primary);
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 0.9rem;
        }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
        }

        .activity-meta {
            font-size: 0.85rem;
            color: var(--text-light);
        }

        .upgrade-prompt {
            background: linear-gradient(135deg, var(--warning-color), #f59e0b);
            border-radius: var(--border-radius);
            padding: 2rem;
            text-align: center;
            color: white;
            margin-bottom: 2rem;
        }

        .upgrade-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .upgrade-text {
            font-size: 1rem;
            margin-bottom: 1.5rem;
            opacity: 0.9;
        }

        .upgrade-button {
            background: white;
            color: var(--warning-color);
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: var(--transition);
        }

        .upgrade-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--text-light);
        }

        .empty-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .empty-text {
            font-size: 1rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .analytics-container {
                padding: 1rem;
            }

            .page-title {
                font-size: 2rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .charts-section {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="analytics-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Profile Analytics</h1>
            <p class="page-subtitle">Track your profile performance and subscription usage</p>
        </div>

        <!-- Upgrade Prompt (if no active subscription or basic plan) -->
        <?php if (!isset($analytics['subscription_plan']) || $analytics['subscription_plan']['price_monthly'] < 1500): ?>
        <div class="upgrade-prompt">
            <h2 class="upgrade-title">🚀 Unlock Advanced Analytics</h2>
            <p class="upgrade-text">Upgrade to Standard or Premium plan to access detailed analytics, unlimited profile views, and advanced insights.</p>
            <a href="<?= base_url('trainer/subscription') ?>" class="upgrade-button">Upgrade Now</a>
        </div>
        <?php endif; ?>

        <!-- Statistics Cards - Only show features available in active plan -->
        <?php
        // Define plan limits at the top for all cards to use
        $viewsLimit = $analytics['plan_limits']['max_profile_views'] ?? 0;
        $clicksLimit = $analytics['plan_limits']['max_clicks'] ?? 0;
        $reviewsLimit = $analytics['plan_limits']['max_reviews'] ?? 0;
        ?>
        <div class="stats-grid">
            <!-- Profile Views (Always shown) -->
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon profile-views">
                        <i class="fas fa-eye"></i>
                    </div>
                    <div>
                        <div class="stat-title">Profile Views</div>
                        <div class="stat-value"><?= number_format($analytics['current_usage']['profile_views'] ?? 0) ?></div>
                    </div>
                </div>
                <?php
                $viewsPercentage = $viewsLimit > 0 ? min(100, (($analytics['current_usage']['profile_views'] ?? 0) / $viewsLimit) * 100) : 0;
                $viewsStatus = $analytics['usage_stats']['profile_views']['status'] ?? 'normal';
                ?>
                <?php if ($viewsLimit > 0): ?>
                <div class="stat-progress">
                    <div class="stat-progress-bar" style="width: <?= $viewsPercentage ?>%"></div>
                </div>
                <div class="stat-status <?= $viewsStatus ?>">
                    <?= number_format($analytics['current_usage']['profile_views'] ?? 0) ?> out of <?= number_format($viewsLimit) ?>
                </div>
                <?php else: ?>
                <div class="stat-status normal">Unlimited</div>
                <?php endif; ?>
            </div>

            <!-- WhatsApp Clicks (Only if plan allows WhatsApp) -->
            <?php if (isset($analytics['subscription_plan']['show_whatsapp']) && $analytics['subscription_plan']['show_whatsapp']): ?>
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon clicks">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <div>
                        <div class="stat-title">WhatsApp Clicks</div>
                        <div class="stat-value"><?= number_format($analytics['current_usage']['whatsapp_clicks'] ?? 0) ?></div>
                    </div>
                </div>
                <?php
                $whatsappPercentage = $clicksLimit > 0 ? min(100, (($analytics['current_usage']['whatsapp_clicks'] ?? 0) / $clicksLimit) * 100) : 0;
                $whatsappStatus = $analytics['usage_stats']['whatsapp_clicks']['status'] ?? 'normal';
                ?>
                <?php if ($clicksLimit > 0): ?>
                <div class="stat-progress">
                    <div class="stat-progress-bar" style="width: <?= $whatsappPercentage ?>%"></div>
                </div>
                <div class="stat-status <?= $whatsappStatus ?>">
                    <?= number_format($analytics['current_usage']['whatsapp_clicks'] ?? 0) ?> out of <?= number_format($clicksLimit) ?>
                </div>
                <?php else: ?>
                <div class="stat-status normal">Unlimited</div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <!-- Call Clicks (Always shown as phone contact is basic) -->
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon messages">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div>
                        <div class="stat-title">Call Clicks</div>
                        <div class="stat-value"><?= number_format($analytics['current_usage']['call_clicks'] ?? 0) ?></div>
                    </div>
                </div>
                <?php
                $callsPercentage = $clicksLimit > 0 ? min(100, (($analytics['current_usage']['call_clicks'] ?? 0) / $clicksLimit) * 100) : 0;
                $callsStatus = $analytics['usage_stats']['call_clicks']['status'] ?? 'normal';
                ?>
                <?php if ($clicksLimit > 0): ?>
                <div class="stat-progress">
                    <div class="stat-progress-bar" style="width: <?= $callsPercentage ?>%"></div>
                </div>
                <div class="stat-status <?= $callsStatus ?>">
                    <?= number_format($analytics['current_usage']['call_clicks'] ?? 0) ?> out of <?= number_format($clicksLimit) ?>
                </div>
                <?php else: ?>
                <div class="stat-status normal">Unlimited</div>
                <?php endif; ?>
            </div>

            <!-- Email Clicks (Always shown as email contact is basic) -->
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon uploads">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <div class="stat-title">Email Clicks</div>
                        <div class="stat-value"><?= number_format($analytics['current_usage']['email_clicks'] ?? 0) ?></div>
                    </div>
                </div>
                <?php
                $emailPercentage = $clicksLimit > 0 ? min(100, (($analytics['current_usage']['email_clicks'] ?? 0) / $clicksLimit) * 100) : 0;
                $emailStatus = $analytics['usage_stats']['email_clicks']['status'] ?? 'normal';
                ?>
                <?php if ($clicksLimit > 0): ?>
                <div class="stat-progress">
                    <div class="stat-progress-bar" style="width: <?= $emailPercentage ?>%"></div>
                </div>
                <div class="stat-status <?= $emailStatus ?>">
                    <?= number_format($analytics['current_usage']['email_clicks'] ?? 0) ?> out of <?= number_format($clicksLimit) ?>
                </div>
                <?php else: ?>
                <div class="stat-status normal">Unlimited</div>
                <?php endif; ?>
            </div>

            <!-- Reviews Listed (Always shown) -->
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon profile-views">
                        <i class="fas fa-star"></i>
                    </div>
                    <div>
                        <div class="stat-title">Reviews Listed</div>
                        <div class="stat-value"><?= number_format($analytics['current_usage']['reviews_listed'] ?? 0) ?></div>
                    </div>
                </div>
                <?php
                $reviewsPercentage = $reviewsLimit > 0 ? min(100, (($analytics['current_usage']['reviews_listed'] ?? 0) / $reviewsLimit) * 100) : 0;
                $reviewsStatus = $analytics['usage_stats']['reviews_listed']['status'] ?? 'normal';
                ?>
                <?php if ($reviewsLimit > 0): ?>
                <div class="stat-progress">
                    <div class="stat-progress-bar" style="width: <?= $reviewsPercentage ?>%"></div>
                </div>
                <div class="stat-status <?= $reviewsStatus ?>">
                    <?= number_format($analytics['current_usage']['reviews_listed'] ?? 0) ?> out of <?= number_format($reviewsLimit) ?>
                </div>
                <?php else: ?>
                <div class="stat-status normal">Unlimited</div>
                <?php endif; ?>
            </div>

            <!-- Content Uploads (Show available upload types based on plan) -->
            <?php
            $hasVideoUploads = isset($analytics['subscription_plan']['allow_video_upload']) && $analytics['subscription_plan']['allow_video_upload'];
            $hasPdfUploads = isset($analytics['subscription_plan']['allow_pdf_upload']) && $analytics['subscription_plan']['allow_pdf_upload'];
            $hasAnnouncements = isset($analytics['subscription_plan']['allow_announcements']) && $analytics['subscription_plan']['allow_announcements'];

            $totalUploads = 0;
            if ($hasVideoUploads) $totalUploads += $analytics['current_usage']['video_uploads'] ?? 0;
            if ($hasPdfUploads) $totalUploads += $analytics['current_usage']['pdf_uploads'] ?? 0;
            if ($hasAnnouncements) $totalUploads += $analytics['current_usage']['announcements'] ?? 0;

            if ($hasVideoUploads || $hasPdfUploads || $hasAnnouncements):
            ?>
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon clicks">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <div>
                        <div class="stat-title">Content Uploads</div>
                        <div class="stat-value"><?= number_format($totalUploads) ?></div>
                    </div>
                </div>
                <div class="stat-status normal">
                    <?php if ($hasVideoUploads): ?>Videos: <?= $analytics['current_usage']['video_uploads'] ?? 0 ?><?php endif; ?>
                    <?php if ($hasVideoUploads && ($hasPdfUploads || $hasAnnouncements)): ?> | <?php endif; ?>
                    <?php if ($hasPdfUploads): ?>PDFs: <?= $analytics['current_usage']['pdf_uploads'] ?? 0 ?><?php endif; ?>
                    <?php if ($hasPdfUploads && $hasAnnouncements): ?> | <?php endif; ?>
                    <?php if ($hasAnnouncements): ?>Posts: <?= $analytics['current_usage']['announcements'] ?? 0 ?><?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Charts Section -->
        <div class="charts-section">
            <!-- Monthly Trends Chart -->
            <div class="chart-card">
                <div class="chart-header">
                    <div>
                        <h3 class="chart-title">Monthly Trends</h3>
                        <p class="chart-subtitle">Profile views and contact clicks over the last 6 months</p>
                    </div>
                </div>
                <div style="height: 300px;">
                    <canvas id="trendsChart"></canvas>
                </div>
            </div>

            <!-- Contact Methods Breakdown -->
            <div class="chart-card">
                <div class="chart-header">
                    <div>
                        <h3 class="chart-title">Contact Methods</h3>
                        <p class="chart-subtitle">Breakdown of contact button clicks this month</p>
                    </div>
                </div>
                <div class="space-y-4">
                    <?php
                    $totalClicks = 0;
                    $availableMethods = [];

                    // Phone and Email are always available
                    $totalClicks += $analytics['current_usage']['call_clicks'] ?? 0;
                    $totalClicks += $analytics['current_usage']['email_clicks'] ?? 0;
                    $availableMethods[] = 'phone';
                    $availableMethods[] = 'email';

                    // WhatsApp only if plan allows it
                    if (isset($analytics['subscription_plan']['show_whatsapp']) && $analytics['subscription_plan']['show_whatsapp']) {
                        $totalClicks += $analytics['current_usage']['whatsapp_clicks'] ?? 0;
                        $availableMethods[] = 'whatsapp';
                    }
                    ?>
                    <?php if ($totalClicks > 0): ?>
                        <?php if (in_array('whatsapp', $availableMethods)): ?>
                        <!-- WhatsApp -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-green-500 rounded mr-3"></div>
                                <span class="text-sm font-medium text-gray-700">WhatsApp</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-24 bg-gray-200 rounded-full h-2 mr-3">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: <?= ($analytics['current_usage']['whatsapp_clicks'] ?? 0) / $totalClicks * 100 ?>%"></div>
                                </div>
                                <span class="text-sm font-semibold text-gray-900 w-12 text-right">
                                    <?= $analytics['current_usage']['whatsapp_clicks'] ?? 0 ?>
                                </span>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Phone Calls -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-blue-500 rounded mr-3"></div>
                                <span class="text-sm font-medium text-gray-700">Phone Calls</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-24 bg-gray-200 rounded-full h-2 mr-3">
                                    <div class="bg-blue-500 h-2 rounded-full" style="width: <?= ($analytics['current_usage']['call_clicks'] ?? 0) / $totalClicks * 100 ?>%"></div>
                                </div>
                                <span class="text-sm font-semibold text-gray-900 w-12 text-right">
                                    <?= $analytics['current_usage']['call_clicks'] ?? 0 ?>
                                </span>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-gray-500 rounded mr-3"></div>
                                <span class="text-sm font-medium text-gray-700">Email</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-24 bg-gray-200 rounded-full h-2 mr-3">
                                    <div class="bg-gray-500 h-2 rounded-full" style="width: <?= ($analytics['current_usage']['email_clicks'] ?? 0) / $totalClicks * 100 ?>%"></div>
                                </div>
                                <span class="text-sm font-semibold text-gray-900 w-12 text-right">
                                    <?= $analytics['current_usage']['email_clicks'] ?? 0 ?>
                                </span>
                            </div>
                        </div>
                    <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-icon">📞</div>
                        <div class="empty-title">No Contact Clicks Yet</div>
                        <div class="empty-text">Contact click data will appear here once students start clicking your contact buttons.</div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="activity-section">
            <div class="activity-card">
                <div class="chart-header">
                    <div>
                        <h3 class="chart-title">Recent Activity</h3>
                        <p class="chart-subtitle">Your latest profile interactions</p>
                    </div>
                </div>

                <?php if (!empty($analytics['recent_activity'])): ?>
                <div class="activity-list">
                    <?php foreach (array_slice($analytics['recent_activity'], 0, 10) as $activity): ?>
                    <div class="activity-item">
                        <div class="activity-icon
                            <?php
                            switch ($activity['type']) {
                                case 'Profile Views': echo 'profile-views'; break;
                                case 'Clicks': echo 'clicks'; break;
                                case 'Messages': echo 'messages'; break;
                                default: echo 'uploads';
                            }
                            ?>">
                            <?php
                            switch ($activity['type']) {
                                case 'Profile Views': echo '<i class="fas fa-eye"></i>'; break;
                                case 'Clicks': echo '<i class="fas fa-mouse-pointer"></i>'; break;
                                case 'Messages': echo '<i class="fas fa-envelope"></i>'; break;
                                case 'Video Uploads': echo '<i class="fas fa-video"></i>'; break;
                                case 'Pdf Uploads': echo '<i class="fas fa-file-pdf"></i>'; break;
                                case 'Announcements': echo '<i class="fas fa-bullhorn"></i>'; break;
                                default: echo '<i class="fas fa-circle"></i>';
                            }
                            ?>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">
                                <?php
                                $action = '';
                                switch ($activity['type']) {
                                    case 'Profile Views': $action = 'Profile viewed'; break;
                                    case 'Clicks': $action = 'Contact clicked'; break;
                                    case 'Messages': $action = 'Message sent'; break;
                                    case 'Video Uploads': $action = 'Video uploaded'; break;
                                    case 'Pdf Uploads': $action = 'PDF uploaded'; break;
                                    case 'Announcements': $action = 'Announcement posted'; break;
                                    default: $action = $activity['type'];
                                }
                                echo $action;
                                ?>
                            </div>
                            <div class="activity-meta">
                                <?= $activity['date'] ?>
                                <?php if (!empty($activity['metadata'])): ?>
                                <?php if (isset($activity['metadata']['contact_type'])): ?>
                                • <?= ucfirst($activity['metadata']['contact_type']) ?> contact
                                <?php elseif (isset($activity['metadata']['subject'])): ?>
                                • Subject: <?= $activity['metadata']['subject'] ?>
                                <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">📈</div>
                    <div class="empty-title">No Activity Yet</div>
                    <div class="empty-text">Your profile activity will appear here once students start viewing and interacting with your profile.</div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Chart.js for visualizations -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Monthly Trends Chart
        document.addEventListener('DOMContentLoaded', function() {
            const trendsData = <?= json_encode($analytics['monthly_trends'] ?? []) ?>;

            if (trendsData.length > 0) {
                const ctx = document.getElementById('trendsChart').getContext('2d');

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: trendsData.map(item => item.month),
                        datasets: [
                            {
                                label: 'Profile Views',
                                data: trendsData.map(item => item.profile_views),
                                borderColor: '#3b82f6',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                tension: 0.4,
                                fill: true
                            },
                            {
                                label: 'Contact Clicks',
                                data: trendsData.map(item => item.clicks),
                                borderColor: '#10b981',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                tension: 0.4,
                                fill: true
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        },
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>
