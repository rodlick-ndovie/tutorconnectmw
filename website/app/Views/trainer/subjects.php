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

// Ensure structured_subjects is always an array
if (isset($user['structured_subjects']) && is_string($user['structured_subjects'])) {
    $user['structured_subjects'] = json_decode($user['structured_subjects'], true) ?? [];
}

// Build an array of selected subject keys for checkbox logic
$selected_subject_keys = [];
$selected_subjects_display = [];
if (isset($user['structured_subjects']) && is_array($user['structured_subjects'])) {
    foreach ($user['structured_subjects'] as $curriculumKey => $curriculumData) {
        if (isset($curriculumData['levels']) && is_array($curriculumData['levels'])) {
            foreach ($curriculumData['levels'] as $levelName => $subjectsArr) {
                foreach ($subjectsArr as $subjectName) {
                    $selected_subject_keys[] = $curriculumKey . '|' . $levelName . '|' . $subjectName;
                    $selected_subjects_display[] = $subjectName;
                }
            }
        }
    }
} elseif (isset($selected_subjects) && is_array($selected_subjects)) {
    // fallback for legacy
    foreach ($selected_subjects as $subjectName) {
        $selected_subject_keys[] = $subjectName;
        $selected_subjects_display[] = $subjectName;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#E55C0D">
    <title>Subjects - TutorConnect Malawi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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
            --bg-primary: #f8fafc;
            --bg-secondary: #ffffff;
            --bg-accent: #f0f9ff;
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --border-radius: 16px;
            --border-radius-lg: 20px;
        }

        * { box-sizing: border-box; }

        body {
            background: var(--bg-primary);
            font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'SF Pro Text', 'Segoe UI', Roboto, sans-serif;
            margin: 0;
            padding: 0;
        }

        .app-container {
            width: 100%;
            max-width: 480px;
            margin: 0 auto;
            min-height: 100vh;
            background: var(--bg-primary);
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
        }

        @media (min-width: 768px) {
            .app-container { max-width: 100%; box-shadow: none; }
        }

        .status-bar { height: 0; background: var(--bg-secondary); border-bottom: 1px solid rgba(0,0,0,0.1); position: sticky; top: 0; z-index: 100; }
        @media (min-width: 768px) { .status-bar { display: none; } }
        @media (max-width: 767px) { .status-bar { display: none; } }

        .navbar {
            background: var(--bg-secondary);
            border-bottom: 1px solid rgba(0,0,0,0.1);
            padding: 12px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 99;
        }

        .nav-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-dark);
            margin: 0;
        }

        .nav-button {
            background: none;
            border: none;
            color: var(--primary-color);
            font-size: 16px;
            padding: 8px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .nav-button:hover {
            background: rgba(5, 150, 105, 0.1);
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

        .content {
            padding: 20px 16px;
            padding-bottom: 100px;
        }

        .card {
            background: var(--bg-secondary);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            margin-bottom: 16px;
            overflow: hidden;
        }

        .card-header {
            padding: 16px;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-dark);
            margin: 0;
        }

        .card-content {
            padding: 16px;
        }

        .page-header {
            margin-bottom: 20px;
        }

        .page-title {
            font-size: 26px;
            font-weight: 800;
            color: var(--text-dark);
            margin: 0;
        }

        .page-subtitle {
            color: var(--text-light);
            margin: 6px 0 0;
            font-size: 14px;
        }

        .pill-row { display: flex; flex-wrap: wrap; gap: 8px; }
        .pill { background: var(--bg-secondary); border: 1px solid rgba(0,0,0,0.05); border-radius: 999px; padding: 8px 12px; font-weight: 600; color: var(--text-dark); box-shadow: var(--shadow); font-size: 12px; }

        .category-title { font-weight: 700; color: var(--text-dark); margin-bottom: 10px; font-size: 15px; }
        .subject-item { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 10px; background: var(--bg-primary); border: 1px solid rgba(0,0,0,0.05); margin-bottom: 8px; }
        .subject-icon { width: 32px; height: 32px; border-radius: 10px; background: linear-gradient(135deg, #10b981, #059669); color: white; display: grid; place-items: center; font-size: 14px; flex-shrink: 0; }
        .subject-name { font-weight: 600; color: var(--text-dark); }
        .subject-desc { margin: 0; color: var(--text-light); font-size: 12px; }

        .empty-state { text-align: center; padding: 32px; color: var(--text-light); }
        .empty-icon { width: 64px; height: 64px; border-radius: 16px; background: var(--bg-secondary); display: grid; place-items: center; margin: 0 auto 12px; font-size: 26px; color: var(--primary-color); box-shadow: var(--shadow); }

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
            padding: 10px 14px calc(16px + env(safe-area-inset-bottom, 0px));
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

        /* Toast notifications */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }

        .toast {
            background: var(--bg-secondary);
            border-radius: var(--border-radius);
            padding: 1rem;
            margin-bottom: 0.5rem;
            box-shadow: var(--shadow);
            border-left: 4px solid var(--primary-color);
            max-width: 300px;
        }

        .toast.success { border-left-color: var(--success-color); }
        .toast.error { border-left-color: var(--danger-color); }
        .toast.warning { border-left-color: var(--warning-color); }

        /* Form styling */
        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        /* Save status */
        .save-status { min-width: 90px; text-align: right; font-weight: 700; font-size: 12px; }
        .save-status.badge { display: inline-flex; align-items: center; gap: 6px; padding: 6px 10px; border-radius: 999px; border: 1px solid rgba(0,0,0,0.08); }
        .save-status.success { background: #ecfdf3; color: #166534; border-color: #bbf7d0; }
        .save-status.error { background: #fef2f2; color: #b91c1c; border-color: #fecdd3; }
        .save-status.idle { background: #f8fafc; color: #475569; border-color: #e2e8f0; }

        /* Plan limits alert */
        .alert-info {
            border-left: 4px solid var(--primary-color);
            background: linear-gradient(135deg, #e0f2fe, #f0f9ff);
        }

        /* Curriculum sections */
        .curriculum-section {
            border: 1px solid rgba(0,0,0,0.05);
            border-radius: var(--border-radius);
            background: var(--bg-secondary);
            margin-bottom: 16px;
            overflow: hidden;
        }

        .curriculum-header {
            padding: 16px;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }

        .curriculum-title {
            font-size: 18px;
            font-weight: 600;
            margin: 0;
        }

        .curriculum-content {
            padding: 16px;
        }

        .level-card {
            border: 1px solid rgba(0,0,0,0.05);
            border-radius: 12px;
            background: var(--bg-secondary);
            margin-bottom: 12px;
            overflow: hidden;
        }

        .level-header {
            padding: 12px 16px;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .level-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-dark);
            margin: 0;
        }

        .level-content {
            padding: 12px 16px;
        }

        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
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
                    <h1 class="nav-title mb-0 me-3">Subjects</h1>
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
        <main class="content">
            <div class="fade-in">
                <!-- Page Header -->
                <div class="page-header">
                    <p class="page-subtitle mb-1">Subject & Level Management</p>
                    <h1 class="page-title">Manage Your Expertise</h1>
                </div>

                <!-- Success/Error Messages -->
                <?php if (session()->has('success')): ?>
                <div class="alert alert-success mb-3">
                    <i class="fas fa-check-circle text-success me-2"></i>
                    <?= session()->get('success') ?>
                </div>
                <?php endif; ?>

                <?php if (session()->has('error')): ?>
                <div class="alert alert-danger mb-3">
                    <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                    <?= session()->get('error') ?>
                </div>
                <?php endif; ?>

                <!-- Plan Limits Alert -->
                <?php if (isset($subscription_status) && $subscription_status !== 'no_subscription'): ?>
                    <div class="alert alert-info d-flex align-items-start mb-3" style="border-radius: 12px; border-left: none; border: none; box-shadow: none; background: linear-gradient(90deg,#f0f9ff 80%,#fff);">
                        <div>
                            <i class="fas fa-info-circle text-info me-3 mt-1" style="font-size: 2rem;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-2 fw-bold" style="color: #0ea5e9;">Plan Limits Information</h5>
                            <?php if ($subscription_status === 'subscribed' && isset($current_subscription)): ?>
                                <p class="mb-2">
                                    For your <strong><?= esc($current_subscription['plan_name']) ?> plan</strong>, you can only select up to
                                    <strong>
                                        <?php if ($current_subscription['max_subjects'] == 0): ?>
                                            unlimited subjects
                                        <?php else: ?>
                                            <?= $current_subscription['max_subjects'] ?> subjects
                                        <?php endif; ?>
                                    </strong> according to your active plan.
                                </p>
                            <?php else: ?>
                                <p class="mb-2">
                                    For your <strong>Free Trial plan</strong>, you can only select up to
                                    <strong>4 subjects</strong> according to your active plan.
                                    <span class="badge bg-success ms-1">Professional</span>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php elseif (isset($subscription_status) && $subscription_status === 'no_subscription' && ($user['tutor_status'] === 'approved' || $user['tutor_status'] === 'active')): ?>
                <div class="alert alert-warning mb-3">
                    <div class="d-flex align-items-start">
                        <i class="fas fa-exclamation-triangle text-warning me-3 mt-1"></i>
                        <div class="flex-grow-1">
                            <h6 class="alert-heading mb-2">
                                <strong>No Active Subscription</strong>
                            </h6>
                            <p class="mb-2">
                                You currently have no active subscription. Basic limits apply: you can select up to <strong>2 subjects</strong> with limited profile visibility.
                            </p>
                            <div class="row text-center mt-3">
                                <div class="col-6">
                                    <div class="p-2">
                                        <i class="fas fa-book fa-2x text-warning mb-2"></i>
                                        <h6 class="text-warning mb-1">Subjects</h6>
                                        <p class="mb-0"><strong>Up to 2</strong></p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-2">
                                        <i class="fas fa-eye fa-2x text-muted mb-2"></i>
                                        <h6 class="text-muted mb-1">Profile Views</h6>
                                        <p class="mb-0"><strong>Limited</strong></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <form id="subjectsForm" method="POST" action="<?= site_url('trainer/subjects/update') ?>">
                    <?= csrf_field() ?>
                    <!-- Selected Subjects Display -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Selected Subjects
                            </h3>
                        </div>
                        <div class="card-content">
                            <div class="pill-row mb-2" id="selectedSubjectsPills">
                                <?php if (!empty($selected_subjects_display)): ?>
                                    <?php foreach ($selected_subjects_display as $subject): ?>
                                        <span class="pill"><i class="fas fa-book me-2"></i><?= esc($subject) ?></span>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <div class="empty-state <?= !empty($selected_subjects_display) ? 'd-none' : '' ?>" id="noSubjectsState">
                                <div class="empty-icon"><i class="fas fa-book"></i></div>
                                <p class="mb-1">No subjects selected yet.</p>
                                <small>Pick subjects below to show here.</small>
                            </div>
                        </div>
                    </div>



                    <!-- Complete Subjects Overview - All Curricula, Levels & Subjects -->
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <i class="fas fa-graduation-cap text-success me-2"></i>
                            <h3 class="card-title mb-0">All Available Subjects by Curriculum</h3>
                            <span class="badge bg-info ms-auto">Complete Overview</span>
                        </div>
                        <div class="card-content">
                            <?php if (!empty($available_curricula)): ?>
                                <?php foreach ($available_curricula as $curriculumKey => $curriculumLabel): ?>
                                    <?php
                                    $curriculumLevels = $levels_by_curriculum[$curriculumKey] ?? [];
                                    $isCurriculumSelected = in_array($curriculumKey, $selected_curricula ?? []);
                                    ?>

                                    <div class="curriculum-section" data-curriculum="<?= esc($curriculumKey) ?>">
                                        <div class="curriculum-header">
                                            <h4 class="curriculum-title">
                                                <i class="fas fa-atlas me-2"></i>
                                                <?= esc($curriculumLabel) ?>
                                                <?php if ($isCurriculumSelected): ?>
                                                    <span class="badge bg-success ms-2">Selected</span>
                                                <?php endif; ?>
                                            </h4>
                                        </div>
                                        <div class="curriculum-content">
                                            <?php if (!empty($curriculumLevels)): ?>
                                                <?php foreach ($curriculumLevels as $levelName): ?>
                                                    <?php
                                                    $levelSubjects = $curriculum_subjects[$curriculumKey][$levelName] ?? [];
                                                    $isLevelSelected = in_array($levelName, $selected_levels ?? []);
                                                    ?>

                                                    <div class="level-card">
                                                            <div class="level-header">
                                                            <div>
                                                                <h5 class="level-title">
                                                                    <i class="fas fa-layer-group me-2"></i>
                                                                    <?= esc($levelName) ?>
                                                                </h5>
                                                                <small class="text-muted">
                                                                    <?= count($levelSubjects) ?> subject<?= count($levelSubjects) > 1 ? 's' : '' ?> available
                                                                </small>
                                                            </div>
                                                        </div>

                                                        <?php if (!empty($levelSubjects)): ?>
                                                            <div class="level-content">
                                                                <div class="row">
                                                                    <?php foreach ($levelSubjects as $subject): ?>
                                                                        <?php
                                                                        $subjectName = $subject['subject_name'] ?? ($subject['name'] ?? '');
                                                                        $subjectId = 'subject_' . md5($curriculumKey . $levelName . $subjectName);
                                                                        $subjectKey = $curriculumKey . '|' . $levelName . '|' . $subjectName;
                                                                        $isChecked = in_array($subjectKey, $selected_subject_keys);
                                                                        ?>

                                                                        <div class="col-md-6 col-lg-4 mb-2">
                                                                            <label class="subject-item" for="<?= $subjectId ?>">
                                                                                <div class="subject-icon">
                                                                                    <i class="fas fa-book-open"></i>
                                                                                </div>
                                                                                <div class="flex-grow-1">
                                                                                    <div class="subject-name">
                                                                                        <?= esc($subjectName) ?>
                                                                                        <?php if ($isChecked): ?>
                                                                                            <i class="fas fa-check text-success ms-1"></i>
                                                                                        <?php endif; ?>
                                                                                    </div>
                                                                                    <?php if (!empty($subject['subject_category'])): ?>
                                                                                        <p class="subject-desc mb-0"><?= esc($subject['subject_category']) ?></p>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                                <input type="checkbox" class="form-check-input" name="subjects[]" id="<?= $subjectId ?>" value="<?= esc($curriculumKey . '|' . $levelName . '|' . $subjectName) ?>" <?= $isChecked ? 'checked' : '' ?> style="margin-left:auto;">
                                                                            </label>
                                                                        </div>
                                                                    <?php endforeach; ?>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <div class="empty-state">
                                                    <div class="empty-icon"><i class="fas fa-layer-group"></i></div>
                                                    <p class="mb-1">No levels available for this curriculum.</p>
                                                    <small>Please contact admin to add levels.</small>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="empty-state">
                                    <div class="empty-icon"><i class="fas fa-atlas"></i></div>
                                    <h5 class="text-muted mb-2">No Curricula Available</h5>
                                    <p class="mb-3">No educational curricula are currently available.</p>
                                    <small>Please contact admin to add curricula.</small>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>



                    <!-- Save Button -->
                    <div class="d-flex align-items-center gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1" id="saveSubjectsBtn">
                            <i class="fas fa-save me-2"></i>Save Subjects & Levels
                        </button>
                        <div id="saveStatus" class="text-muted small" aria-live="polite" style="display:none;"></div>
                    </div>
                </form>
            </div>
        </main>

        <!-- Bottom Navigation -->
        <nav class="bottom-nav">
            <a href="<?= site_url('trainer/dashboard') ?>" class="nav-item">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>
            <a href="<?= site_url('trainer/subjects') ?>" class="nav-item active">
                <i class="fas fa-book"></i>
                <span>Subjects</span>
            </a>
            <a href="<?= site_url('trainer/profile') ?>" class="nav-item">
                <i class="fas fa-user"></i>
                <span>Profile</span>
            </a>
            <a href="<?= site_url('trainer/subscription') ?>" class="nav-item">
                <i class="fas fa-crown"></i>
                <span>Premium</span>
            </a>
        </nav>
    </div>

    <div class="toast-container" id="toastContainer"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php
        // Determine subject limit for JS
        $max_subjects_js = 2;
        if (isset($subscription_status) && $subscription_status === 'subscribed' && isset($current_subscription)) {
            $max_subjects_js = (int)($current_subscription['max_subjects'] ?? 2);
            if ($max_subjects_js === 0) $max_subjects_js = 9999; // Unlimited
        }
    ?>
    <script>
        // Only navigation highlighting remains; all AJAX subject logic removed for normal form submission
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const navItems = document.querySelectorAll('.nav-item');
            function shouldBeActive(item) {
                const href = item.getAttribute('href');
                if (!href) return false;
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

            // Subject selection limit logic
            const maxSubjects = <?php echo json_encode($max_subjects_js); ?>;
            const checkboxes = document.querySelectorAll('input[type="checkbox"][name="subjects[]"]');
            function updateCheckboxStates() {
                const checked = Array.from(checkboxes).filter(cb => cb.checked);
                if (checked.length >= maxSubjects) {
                    checkboxes.forEach(cb => {
                        if (!cb.checked) cb.disabled = true;
                    });
                } else {
                    checkboxes.forEach(cb => {
                        cb.disabled = false;
                    });
                }
            }
            checkboxes.forEach(cb => {
                cb.addEventListener('change', updateCheckboxStates);
            });
            updateCheckboxStates();
        });
    </script>
</body>
</html>
