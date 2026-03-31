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
    header('Location: ' . base_url('trainer/dashboard'));
    exit;
}

// Parse availability data
$availability = json_decode($user['availability'] ?? '{}', true);
$selectedDays = $availability['days'] ?? [];
$selectedTimes = $availability['times'] ?? [];

// Check subscription status for feature restrictions
$subscriptionModel = new \App\Models\TutorSubscriptionModel();
$subscription = $subscriptionModel->getActiveSubscription($userId);
$hasActiveSubscription = $subscription !== null;
$subscriptionStatus = $hasActiveSubscription ? 'subscribed' : 'no_subscription';

// Get subscription plan details if subscribed
$currentPlan = null;
if ($hasActiveSubscription) {
    $planModel = new \App\Models\SubscriptionPlanModel();
    $currentPlan = $planModel->find($subscription['plan_id']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Edit Profile - TutorConnect Malawi</title>
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

        .status-bar {
            height: 44px;
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border-color);
        }

        @media (min-width: 768px) {
            .status-bar {
                display: none;
            }
        }

        .main-content {
            padding: 16px;
            padding-bottom: 100px;
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

        .form-section {
            background: var(--bg-secondary);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 24px;
            margin-bottom: 24px;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 6px;
            display: block;
            font-size: 0.9rem;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: var(--bg-secondary);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(229, 88, 13, 0.1);
            outline: none;
        }

        .form-control.is-invalid {
            border-color: var(--danger-color);
        }

        .invalid-feedback {
            color: var(--danger-color);
            font-size: 0.85rem;
            margin-top: 4px;
        }

        .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 8px;
        }

        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 8px;
            background: var(--bg-accent);
            padding: 8px 12px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .checkbox-item:hover {
            background: var(--accent-color);
            color: white;
        }

        .checkbox-item.active {
            background: var(--success-color);
            color: white;
            border-color: var(--success-color);
        }

        .checkbox-item input {
            display: none;
        }

        .file-upload-area {
            border: 2px dashed var(--border-color);
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            background: var(--bg-accent);
        }

        .file-upload-area:hover {
            border-color: var(--primary-color);
            background: rgba(229, 88, 13, 0.05);
        }

        .file-upload-area.dragover {
            border-color: var(--primary-color);
            background: rgba(229, 88, 13, 0.1);
        }

        .file-upload-icon {
            font-size: 2rem;
            color: var(--text-light);
            margin-bottom: 8px;
        }

        .file-upload-text {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .file-preview {
            margin-top: 12px;
            padding: 8px;
            background: var(--bg-secondary);
            border-radius: 8px;
            border: 1px solid var(--border-color);
            display: none;
        }

        .btn-primary {
            background: var(--primary-color);
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .btn-outline-secondary {
            border: 2px solid var(--border-color);
            background: transparent;
            color: var(--text-light);
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-outline-secondary:hover {
            background: var(--text-light);
            color: white;
        }

        .alert {
            border-radius: 12px;
            border: none;
            margin-bottom: 20px;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
        }

        .radio-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 8px;
        }

        .radio-item {
            position: relative;
        }

        .radio-item input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            margin: 0;
        }

        .radio-item label {
            display: block;
            padding: 12px 16px;
            background: var(--bg-accent);
            border: 2px solid var(--border-color);
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .radio-item input[type="radio"]:checked + label {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .radio-item label:hover {
            background: var(--accent-color);
            color: white;
            border-color: var(--accent-color);
        }

        .plan-features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 16px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 16px;
            background: var(--bg-accent);
            padding: 16px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .feature-item:hover {
            background: rgba(229, 88, 13, 0.05);
            border-color: var(--primary-color);
        }

        .feature-item.restricted-plan {
            background: rgba(239, 68, 68, 0.05);
            border-color: var(--danger-color);
        }

        .feature-icon {
            width: 50px;
            height: 50px;
            background: var(--primary-color);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .feature-item.restricted-plan .feature-icon {
            background: var(--danger-color);
        }

        .feature-content {
            flex: 1;
        }

        .feature-content h6 {
            margin: 0 0 4px 0;
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        .feature-content p {
            margin: 0;
            font-size: 0.9rem;
            color: var(--text-secondary);
            line-height: 1.4;
        }

        .feature-status {
            flex-shrink: 0;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .feature-status.available {
            background: var(--success-color);
            color: white;
        }

        .feature-status.restricted {
            background: var(--danger-color);
            color: white;
        }

        @media (max-width: 576px) {
            .plan-features-grid {
                grid-template-columns: 1fr;
            }

            .feature-item {
                flex-direction: column;
                text-align: center;
                gap: 12px;
            }

            .feature-icon {
                align-self: center;
            }

            .feature-status {
                align-self: center;
            }
        }
    </style>
</head>
<body>
<div class="app-container">
    <div class="status-bar"></div>
    <div class="navbar">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center">
                <a href="<?= base_url('trainer/profile') ?>" class="d-flex align-items-center text-decoration-none">
                    <i class="fas fa-arrow-left fa-lg me-2"></i>
                    <span class="fs-5">Back to Profile</span>
                </a>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-light">
                    <i class="fas fa-question-circle"></i>
                </button>
                <div class="avatar">
                    <?= strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="main-content container-fluid px-4">
        <div class="page-header">
            <h1 class="page-title">Edit Profile</h1>
            <p class="page-subtitle">Update your profile information</p>
        </div>

        <!-- Subscription Notice -->
        <?php if (!$hasActiveSubscription): ?>
            <div class="alert alert-warning">
                <i class="fas fa-info-circle me-2"></i>
                <strong>No Active Subscription:</strong> Some features may not be available or visible according to your current subscription plan.
                <a href="<?= base_url('trainer/subscription') ?>" class="alert-link">Upgrade your plan</a> to access all features.
            </div>
        <?php elseif ($currentPlan && strtolower($currentPlan['name']) === 'free trial'): ?>
            <div class="alert alert-info">
                <i class="fas fa-crown me-2"></i>
                <strong>Free Trial Plan:</strong> You're currently on the Free Trial plan with limited features. Upgrade to unlock premium features like video uploads, advanced analytics, and more visibility options.
                <a href="<?= base_url('trainer/subscription') ?>" class="alert-link">View Plans</a>
            </div>
        <?php elseif ($currentPlan && strtolower($currentPlan['name']) === 'basic'): ?>
            <div class="alert alert-info">
                <i class="fas fa-star me-2"></i>
                <strong>Basic Plan:</strong> You're on the Basic plan. Upgrade to Standard or Premium for video uploads, email marketing access, and enhanced visibility.
                <a href="<?= base_url('trainer/subscription') ?>" class="alert-link">Upgrade Now</a>
            </div>
        <?php endif; ?>

        <!-- Restricted Features Notice -->
        <?php
        $restrictedFeatures = [];
        if ($hasActiveSubscription && $currentPlan) {
            if (!$currentPlan['show_whatsapp']) $restrictedFeatures[] = 'WhatsApp contact visibility';
            if (!$currentPlan['email_marketing_access']) $restrictedFeatures[] = 'Email marketing tools';
            if (!$currentPlan['allow_video_upload']) $restrictedFeatures[] = 'Video uploads';
            if (!$currentPlan['allow_pdf_upload']) $restrictedFeatures[] = 'PDF uploads';
            if (!$currentPlan['allow_announcements']) $restrictedFeatures[] = 'Announcements posting';
        } else {
            $restrictedFeatures = ['Video uploads', 'PDF uploads', 'Email marketing tools', 'WhatsApp contact visibility', 'Announcements posting'];
        }

        if (!empty($restrictedFeatures)):
        ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Features not available with your current plan:</strong>
            <span class="restricted-features-list">
                <?= implode(', ', $restrictedFeatures) ?>
            </span>
            <?php if (!$hasActiveSubscription || strtolower($currentPlan['name'] ?? '') !== 'premium'): ?>
                <a href="<?= base_url('trainer/subscription') ?>" class="alert-link ms-2">
                    <strong>Upgrade to access these features →</strong>
                </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('trainer/profile/update') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <!-- Personal Information -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-user"></i>
                    Personal Information
                </div>

                <div class="form-group">
                    <label class="form-label">First Name *</label>
                    <input type="text" name="first_name" class="form-control <?= isset(session('errors')['first_name']) ? 'is-invalid' : '' ?>"
                           value="<?= old('first_name', esc($user['first_name'])) ?>" required>
                    <?php if (isset(session('errors')['first_name'])): ?>
                        <div class="invalid-feedback"><?= session('errors')['first_name'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label class="form-label">Last Name *</label>
                    <input type="text" name="last_name" class="form-control <?= isset(session('errors')['last_name']) ? 'is-invalid' : '' ?>"
                           value="<?= old('last_name', esc($user['last_name'])) ?>" required>
                    <?php if (isset(session('errors')['last_name'])): ?>
                        <div class="invalid-feedback"><?= session('errors')['last_name'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-control <?= isset(session('errors')['email']) ? 'is-invalid' : '' ?>"
                           value="<?= old('email', esc($user['email'])) ?>" required>
                    <?php if (isset(session('errors')['email'])): ?>
                        <div class="invalid-feedback"><?= session('errors')['email'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label class="form-label">Phone</label>
                    <input type="tel" name="phone" class="form-control <?= isset(session('errors')['phone']) ? 'is-invalid' : '' ?>"
                           value="<?= old('phone', esc($user['phone'] ?? '')) ?>">
                    <?php if (isset(session('errors')['phone'])): ?>
                        <div class="invalid-feedback"><?= session('errors')['phone'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label class="form-label">WhatsApp Number</label>
                    <input type="tel" name="whatsapp_number" class="form-control <?= isset(session('errors')['whatsapp_number']) ? 'is-invalid' : '' ?>"
                           value="<?= old('whatsapp_number', esc($user['whatsapp_number'] ?? '')) ?>">
                    <?php if (isset(session('errors')['whatsapp_number'])): ?>
                        <div class="invalid-feedback"><?= session('errors')['whatsapp_number'] ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Location Information -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-map-marker-alt"></i>
                    Location
                </div>

                <div class="form-group">
                    <label class="form-label">District</label>
                    <select name="district" class="form-control <?= isset(session('errors')['district']) ? 'is-invalid' : '' ?>">
                        <option value="">Select District</option>
                        <?php foreach ($districts as $district): ?>
                            <option value="<?= esc($district) ?>" <?= old('district', $user['district'] ?? '') === $district ? 'selected' : '' ?>>
                                <?= esc($district) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset(session('errors')['district'])): ?>
                        <div class="invalid-feedback"><?= session('errors')['district'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label class="form-label">Specific Location</label>
                    <input type="text" name="location" class="form-control <?= isset(session('errors')['location']) ? 'is-invalid' : '' ?>"
                           value="<?= old('location', esc($user['location'] ?? '')) ?>" placeholder="e.g., Area 49, Gulliver">
                    <?php if (isset(session('errors')['location'])): ?>
                        <div class="invalid-feedback"><?= session('errors')['location'] ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Professional Information -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-briefcase"></i>
                    Professional Information
                </div>

                <div class="form-group">
                    <label class="form-label">Years of Experience</label>
                    <input type="number" name="experience_years" class="form-control <?= isset(session('errors')['experience_years']) ? 'is-invalid' : '' ?>"
                           value="<?= old('experience_years', $user['experience_years'] ?? '') ?>" min="0" max="50">
                    <?php if (isset(session('errors')['experience_years'])): ?>
                        <div class="invalid-feedback"><?= session('errors')['experience_years'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label class="form-label">Teaching Mode</label>
                    <select name="teaching_mode" class="form-control <?= isset(session('errors')['teaching_mode']) ? 'is-invalid' : '' ?>">
                        <option value="">Select Teaching Mode</option>
                        <option value="Online Only" <?= old('teaching_mode', $user['teaching_mode'] ?? '') === 'Online Only' ? 'selected' : '' ?>>Online Only</option>
                        <option value="In-Person Only" <?= old('teaching_mode', $user['teaching_mode'] ?? '') === 'In-Person Only' ? 'selected' : '' ?>>In-Person Only</option>
                        <option value="Both Online & Physical" <?= old('teaching_mode', $user['teaching_mode'] ?? '') === 'Both Online & Physical' ? 'selected' : '' ?>>Both Online & Physical</option>
                    </select>
                    <?php if (isset(session('errors')['teaching_mode'])): ?>
                        <div class="invalid-feedback"><?= session('errors')['teaching_mode'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label class="form-label">Are you currently employed?</label>
                    <div class="radio-group">
                        <div class="radio-item">
                            <input type="radio" id="employed_no" name="is_employed" value="0" <?= old('is_employed', $user['is_employed'] ?? 0) == 0 ? 'checked' : '' ?>>
                            <label for="employed_no">No, I'm a freelance tutor</label>
                        </div>
                        <div class="radio-item">
                            <input type="radio" id="employed_yes" name="is_employed" value="1" <?= old('is_employed', $user['is_employed'] ?? 0) == 1 ? 'checked' : '' ?>>
                            <label for="employed_yes">Yes, I work at a school</label>
                        </div>
                    </div>
                </div>

                <div class="form-group" id="school_name_group" style="display: <?= old('is_employed', $user['is_employed'] ?? 0) == 1 ? 'block' : 'none' ?>;">
                    <label class="form-label">School Name</label>
                    <input type="text" name="school_name" class="form-control <?= isset(session('errors')['school_name']) ? 'is-invalid' : '' ?>"
                           value="<?= old('school_name', esc($user['school_name'] ?? '')) ?>">
                    <?php if (isset(session('errors')['school_name'])): ?>
                        <div class="invalid-feedback"><?= session('errors')['school_name'] ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Bio -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-file-alt"></i>
                    Bio & Media
                </div>

                <div class="form-group">
                    <label class="form-label">Bio</label>
                    <textarea name="bio" class="form-control <?= isset(session('errors')['bio']) ? 'is-invalid' : '' ?>" rows="4"
                              placeholder="Tell students about yourself, your teaching experience, and what makes you a great tutor..."><?= old('bio', esc($user['bio'] ?? '')) ?></textarea>
                    <?php if (isset(session('errors')['bio'])): ?>
                        <div class="invalid-feedback"><?= session('errors')['bio'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label class="form-label">Profile Picture</label>
                    <div class="file-upload-area" onclick="document.getElementById('profile_picture').click()">
                        <div class="file-upload-icon">
                            <i class="fas fa-camera"></i>
                        </div>
                        <div class="file-upload-text">
                            Click to upload profile picture<br>
                            <small>JPG, PNG, GIF up to 5MB</small>
                        </div>
                        <input type="file" id="profile_picture" name="profile_picture" accept="image/*" style="display: none;">
                    </div>
                    <div class="file-preview" id="profile_preview" style="display: <?= !empty($user['profile_picture']) ? 'block' : 'none' ?>;">
                        <?php if (!empty($user['profile_picture'])): ?>
                            <img src="<?= base_url($user['profile_picture']) ?>" alt="Current profile picture" style="max-width: 100px; max-height: 100px; border-radius: 8px;">
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Cover Photo</label>
                    <div class="file-upload-area" onclick="document.getElementById('cover_photo').click()">
                        <div class="file-upload-icon">
                            <i class="fas fa-image"></i>
                        </div>
                        <div class="file-upload-text">
                            Click to upload cover photo<br>
                            <small>JPG, PNG, GIF</small>
                        </div>
                        <input type="file" id="cover_photo" name="cover_photo" accept="image/*" style="display: none;">
                    </div>
                    <div class="file-preview" id="cover_preview" style="display: <?= !empty($user['cover_photo']) ? 'block' : 'none' ?>;">
                        <?php if (!empty($user['cover_photo'])): ?>
                            <img src="<?= base_url($user['cover_photo']) ?>" alt="Current cover photo" style="max-width: 200px; max-height: 100px; border-radius: 8px;">
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Intro Video (Optional)</label>
                    <div class="file-upload-area" onclick="document.getElementById('bio_video').click()">
                        <div class="file-upload-icon">
                            <i class="fas fa-video"></i>
                        </div>
                        <div class="file-upload-text">
                            Click to upload intro video<br>
                            <small>MP4, MOV, AVI, WebM up to 50MB</small>
                        </div>
                        <input type="file" id="bio_video" name="bio_video" accept="video/*" style="display: none;">
                    </div>
                    <div class="file-preview" id="video_preview">
                        <?php if (!empty($user['bio_video'])): ?>
                            <video controls style="max-width: 200px; max-height: 150px; border-radius: 8px;">
                                <source src="<?= base_url($user['bio_video']) ?>" type="video/mp4">
                            </video>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Availability -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-calendar-alt"></i>
                    Availability
                </div>

                <div class="form-group">
                    <label class="form-label">Available Days</label>
                    <div class="checkbox-group">
                        <?php
                        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                        foreach ($days as $day):
                            $isChecked = in_array($day, old('days', $selectedDays));
                        ?>
                            <div class="checkbox-item <?= $isChecked ? 'active' : '' ?>" onclick="toggleCheckbox(this)">
                                <input type="checkbox" name="days[]" value="<?= $day ?>" <?= $isChecked ? 'checked' : '' ?>>
                                <span><?= substr($day, 0, 3) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Available Times</label>
                    <div class="checkbox-group">
                        <?php
                        $times = ['Morning (8AM-12PM)', 'Afternoon (12PM-5PM)', 'Evening (5PM-9PM)'];
                        foreach ($times as $time):
                            $isChecked = in_array($time, old('times', $selectedTimes));
                        ?>
                            <div class="checkbox-item <?= $isChecked ? 'active' : '' ?>" onclick="toggleCheckbox(this)">
                                <input type="checkbox" name="times[]" value="<?= $time ?>" <?= $isChecked ? 'checked' : '' ?>>
                                <span><?= $time ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Preferences -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-sliders-h"></i>
                    Preferences
                </div>

                <div class="form-group">
                    <label class="form-label">Preferred Contact Method</label>
                    <select name="preferred_contact_method" class="form-control <?= isset(session('errors')['preferred_contact_method']) ? 'is-invalid' : '' ?>">
                        <option value="phone" <?= old('preferred_contact_method', $user['preferred_contact_method'] ?? 'phone') === 'phone' ? 'selected' : '' ?>>Phone</option>
                        <option value="email" <?= old('preferred_contact_method', $user['preferred_contact_method'] ?? 'phone') === 'email' ? 'selected' : '' ?>>Email</option>
                        <option value="whatsapp" <?= old('preferred_contact_method', $user['preferred_contact_method'] ?? 'phone') === 'whatsapp' ? 'selected' : '' ?>>WhatsApp</option>
                    </select>
                    <?php if (isset(session('errors')['preferred_contact_method'])): ?>
                        <div class="invalid-feedback"><?= session('errors')['preferred_contact_method'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label class="form-label">Best Call Time</label>
                    <select name="best_call_time" class="form-control <?= isset(session('errors')['best_call_time']) ? 'is-invalid' : '' ?>">
                        <option value="Morning (8AM-12PM)" <?= old('best_call_time', $user['best_call_time'] ?? 'Morning (8AM-12PM)') === 'Morning (8AM-12PM)' ? 'selected' : '' ?>>Morning (8AM-12PM)</option>
                        <option value="Afternoon (12PM-5PM)" <?= old('best_call_time', $user['best_call_time'] ?? 'Morning (8AM-12PM)') === 'Afternoon (12PM-5PM)' ? 'selected' : '' ?>>Afternoon (12PM-5PM)</option>
                        <option value="Evening (5PM-9PM)" <?= old('best_call_time', $user['best_call_time'] ?? 'Morning (8AM-12PM)') === 'Evening (5PM-9PM)' ? 'selected' : '' ?>>Evening (5PM-9PM)</option>
                    </select>
                    <?php if (isset(session('errors')['best_call_time'])): ?>
                        <div class="invalid-feedback"><?= session('errors')['best_call_time'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label class="form-label">Privacy Settings</label>
                    <div class="checkbox-group">
                        <div class="checkbox-item <?= old('phone_visible', $user['phone_visible'] ?? 1) ? 'active' : '' ?>" onclick="toggleCheckbox(this)">
                            <input type="checkbox" name="phone_visible" value="1" <?= old('phone_visible', $user['phone_visible'] ?? 1) ? 'checked' : '' ?>>
                            <span>Show phone number to students</span>
                        </div>
                        <div class="checkbox-item <?= old('email_visible', $user['email_visible'] ?? 0) ? 'active' : '' ?>" onclick="toggleCheckbox(this)">
                            <input type="checkbox" name="email_visible" value="1" <?= old('email_visible', $user['email_visible'] ?? 0) ? 'checked' : '' ?>>
                            <span>Show email to students</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="form-section">
                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Changes
                    </button>
                    <a href="<?= base_url('trainer/profile') ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="bottom-nav">
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
        <a href="<?= site_url('trainer/subscription') ?>" class="nav-item">
            <i class="fas fa-crown"></i>
            <span>Premium</span>
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle employment status change
    const employmentRadios = document.querySelectorAll('input[name="is_employed"]');
    const schoolNameGroup = document.getElementById('school_name_group');

    employmentRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === '1') {
                schoolNameGroup.style.display = 'block';
            } else {
                schoolNameGroup.style.display = 'none';
            }
        });
    });

    // Handle file uploads
    function handleFilePreview(inputId, previewId, isImage = true) {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);

        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                preview.style.display = 'block';
                if (isImage) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.innerHTML = `<img src="${e.target.result}" alt="Preview" style="max-width: ${inputId === 'cover_photo' ? '200px' : '100px'}; max-height: 100px; border-radius: 8px;">`;
                    };
                    reader.readAsDataURL(file);
                } else {
                    preview.innerHTML = `<div class="text-muted">Video selected: ${file.name}</div>`;
                }
            }
        });
    }

    handleFilePreview('profile_picture', 'profile_preview', true);
    handleFilePreview('cover_photo', 'cover_preview', true);
    handleFilePreview('bio_video', 'video_preview', false);

    // Toggle checkbox items
    window.toggleCheckbox = function(element) {
        const checkbox = element.querySelector('input[type="checkbox"]');
        checkbox.checked = !checkbox.checked;
        element.classList.toggle('active', checkbox.checked);
    };

    // Active navigation highlighting
    const currentPath = window.location.pathname;
    const navItems = document.querySelectorAll('.nav-item');

    function shouldBeActive(item) {
        const href = item.getAttribute('href');
        if (!href) return false;
        if (href.includes('/trainer/profile') && currentPath.includes('/trainer/profile')) return true;
        return false;
    }

    navItems.forEach(item => {
        if (shouldBeActive(item)) {
            item.classList.add('active');
        } else {
            item.classList.remove('active');
        }
    });
});
</script>
</body>
</html>
