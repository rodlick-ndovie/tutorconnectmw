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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#E55C0D">
    <title>My Profile - TutorConnect Malawi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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

        .profile-container {
            background: var(--bg-secondary);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 24px;
            margin-bottom: 24px;
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 24px;
            margin-bottom: 24px;
        }

        .profile-avatar {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--success-color);
            background: var(--bg-accent);
        }

        .profile-name {
            font-size: 1.7rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 4px;
        }

        .profile-role {
            font-size: 1rem;
            color: var(--accent-color);
            font-weight: 600;
        }

        .profile-badge {
            display: inline-block;
            background: linear-gradient(90deg, var(--success-color), var(--accent-color));
            color: #fff;
            font-size: 0.85rem;
            font-weight: 600;
            border-radius: 999px;
            padding: 4px 16px;
            margin-left: 8px;
        }

        .profile-section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-top: 24px;
            margin-bottom: 10px;
        }

        .profile-info-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .profile-info-list li {
            margin-bottom: 10px;
            font-size: 1rem;
            color: #374151;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .profile-info-list i {
            color: var(--accent-color);
            min-width: 20px;
            text-align: center;
        }

        .profile-bio {
            background: var(--bg-accent);
            border-radius: 12px;
            padding: 16px;
            font-size: 1rem;
            color: #374151;
            margin-bottom: 16px;
        }

        .profile-actions {
            margin-top: 24px;
            display: flex;
            gap: 12px;
        }

        .btn-primary {
            background: var(--primary-color);
            border: none;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .profile-label {
            font-size: 0.95rem;
            color: var(--text-light);
            font-weight: 500;
            margin-bottom: 2px;
        }

        .profile-value {
            font-size: 1.05rem;
            color: var(--text-dark);
            font-weight: 600;
        }

        .profile-status {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--success-color);
            margin-left: 8px;
        }

        .profile-status.inactive {
            color: var(--danger-color);
        }

        .profile-status.pending {
            color: var(--warning-color);
        }

        .cover-photo-container {
            margin-bottom: 24px;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .cover-photo {
            width: 100%;
            height: 150px;
            object-fit: cover;
            display: block;
        }

        @media (min-width: 768px) {
            .cover-photo {
                height: 200px;
            }
        }

        .availability-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 8px;
        }

        .availability-item {
            background: var(--bg-accent);
            padding: 12px;
            border-radius: 8px;
            font-size: 0.9rem;
            text-align: center;
            border: 1px solid var(--border-color);
        }

        .availability-item.active {
            background: var(--success-color);
            color: white;
            border-color: var(--success-color);
        }

        .documents-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px;
            margin-top: 8px;
        }

        .document-item {
            display: flex;
            align-items: center;
            gap: 12px;
            background: var(--bg-accent);
            padding: 12px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        .document-icon {
            width: 40px;
            height: 40px;
            background: var(--primary-color);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .document-info h6 {
            margin: 0;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .document-info small {
            color: var(--text-light);
            font-size: 0.8rem;
        }

        .subjects-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
            margin-top: 8px;
        }

        .subject-item {
            background: var(--bg-accent);
            border-radius: 12px;
            padding: 16px;
            border: 1px solid var(--border-color);
        }

        .subject-header {
            margin-bottom: 12px;
        }

        .curriculum-name {
            color: var(--primary-color);
            font-size: 1rem;
            font-weight: 700;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .subject-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .subject-tag {
            background: var(--success-color);
            color: white;
            font-size: 0.85rem;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 20px;
            display: inline-block;
            text-transform: capitalize;
        }
    </style>
</head>
<body>
<div class="app-container">
    <div class="status-bar"></div>
    <div class="navbar">
        <div class="d-flex align-items-center justify-content-between w-100 px-3">
            <div class="d-flex align-items-center">
                <h1 class="nav-title mb-0 me-3">Profile</h1>
            </div>
            <div class="d-flex gap-2">
                <button class="btn p-0 border-0 bg-transparent nav-button" onclick="window.location.href='<?php echo base_url('logout'); ?>'">
                    <i class="fas fa-sign-out-alt text-muted" style="font-size: 20px;"></i>
                </button>
                <div class="avatar">
                    <?= strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="main-content container-fluid px-4">
        <div class="page-header">
            <h1 class="page-title">My Profile</h1>
            <p class="page-subtitle">Manage your profile information</p>
        </div>

        <?php if (!empty($user['cover_photo'])): ?>
        <div class="cover-photo-container">
            <img src="<?= base_url($user['cover_photo']) ?>" alt="Cover Photo" class="cover-photo">
        </div>
        <?php endif; ?>

        <div class="profile-header">
            <img src="<?= !empty($user['profile_picture']) ? base_url($user['profile_picture']) : 'https://ui-avatars.com/api/?name=' . urlencode($user['first_name'] ?? 'T') ?>"
                 alt="Profile Photo" class="profile-avatar">
            <div>
                <div class="profile-name">
                    <?= esc($user['first_name'] . ' ' . $user['last_name']) ?>
                    <?php if (($user['is_verified'] ?? 0) == 1): ?>
                        <span class="profile-badge"><i class="fas fa-check-circle me-1"></i>Verified</span>
                    <?php endif; ?>
                </div>
                <div class="profile-role">
                    <i class="fas fa-chalkboard-teacher me-1"></i>
                    <?= ucfirst($user['role']) ?>
                    <?php if ($user['tutor_status'] === 'approved'): ?>
                        <span class="profile-status">Active</span>
                    <?php elseif ($user['tutor_status'] === 'pending'): ?>
                        <span class="profile-status pending">Pending</span>
                    <?php else: ?>
                        <span class="profile-status inactive">Inactive</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="profile-section-title">Contact Information</div>
        <ul class="profile-info-list">
            <li><i class="fas fa-envelope"></i> <?= esc($user['email']) ?></li>
            <li><i class="fas fa-phone"></i> <?= esc($user['phone']) ?></li>
            <?php if (!empty($user['whatsapp_number'])): ?>
                <li><i class="fab fa-whatsapp"></i> <?= esc($user['whatsapp_number']) ?></li>
            <?php endif; ?>
            <li><i class="fas fa-map-marker-alt"></i> <?= esc($user['district']) ?><?= $user['location'] ? ', ' . esc($user['location']) : '' ?></li>
        </ul>

        <div class="profile-section-title">Professional Details</div>
        <ul class="profile-info-list">
            <li><i class="fas fa-briefcase"></i> <?= esc($user['experience_years']) ?> years experience</li>
            <li><i class="fas fa-chalkboard"></i> Teaching Mode: <?= esc(ucfirst($user['teaching_mode'])) ?></li>
            <?php if (!empty($user['school_name'])): ?>
                <li><i class="fas fa-school"></i> <?= esc($user['school_name']) ?></li>
            <?php endif; ?>
        </ul>

        <div class="profile-section-title">Bio</div>
        <div class="profile-bio">
            <?= nl2br(esc($user['bio'])) ?>
        </div>

        <?php if (!empty($user['bio_video'])): ?>
            <div class="profile-section-title">Intro Video</div>
            <video src="<?= base_url($user['bio_video']) ?>" controls style="width:100%;border-radius:12px;margin-bottom:16px;"></video>
        <?php endif; ?>

        <div class="profile-section-title">Preferences</div>
        <ul class="profile-info-list">
            <li><i class="fas fa-clock"></i> Best Call Time: <?= esc($user['best_call_time']) ?></li>
            <li><i class="fas fa-comments"></i> Preferred Contact: <?= esc(ucfirst($user['preferred_contact_method'])) ?></li>
            <li><i class="fas fa-eye<?= $user['phone_visible'] ? '' : '-slash' ?>"></i> Phone Visible: <?= $user['phone_visible'] ? 'Yes' : 'No' ?></li>
            <li><i class="fas fa-envelope<?= $user['email_visible'] ? '' : '-slash' ?>"></i> Email Visible: <?= $user['email_visible'] ? 'Yes' : 'No' ?></li>
        </ul>

        <?php
        $availability = json_decode($user['availability'] ?? '{}', true);
        if (!empty($availability)):
        ?>
        <div class="profile-section-title">Availability</div>
        <div class="availability-grid">
            <?php
            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            $times = ['Morning (8AM-12PM)', 'Afternoon (12PM-5PM)', 'Evening (5PM-9PM)'];

            foreach ($days as $day):
                $isActive = in_array($day, $availability['days'] ?? []);
            ?>
                <div class="availability-item <?= $isActive ? 'active' : '' ?>">
                    <i class="fas fa-calendar-day me-1"></i>
                    <?= substr($day, 0, 3) ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-3">
            <small class="text-muted">Available times: <?= implode(', ', $availability['times'] ?? []) ?></small>
        </div>
        <?php endif; ?>

        <?php
        $structuredSubjects = json_decode($user['structured_subjects'] ?? '{}', true);
        if (!empty($structuredSubjects)):
        ?>
        <div class="profile-section-title">Teaching Subjects</div>
        <div class="subjects-grid">
            <?php foreach ($structuredSubjects as $curriculum => $curriculumData): ?>
                <?php if (isset($curriculumData['levels'])): ?>
                    <?php foreach ($curriculumData['levels'] as $level => $subjects): ?>
                        <?php if (!empty($subjects)): ?>
                            <div class="subject-item">
                                <div class="subject-header">
                                    <h6 class="curriculum-name"><?= esc($curriculum) ?> - <?= esc($level) ?></h6>
                                </div>
                                <div class="subject-tags">
                                    <?php foreach ($subjects as $subject): ?>
                                        <span class="subject-tag"><?= esc($subject) ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>



        <div class="profile-actions">
            <a href="<?= site_url('trainer/profile/edit') ?>" class="btn btn-primary">
                <i class="fas fa-edit me-1"></i>Edit Profile
            </a>
            <a href="<?= site_url('trainer/dashboard') ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
            </a>
        </div>
        </div>
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
        <a href="<?= site_url('trainer/profile') ?>" class="nav-item active">
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
</body>
</html>
