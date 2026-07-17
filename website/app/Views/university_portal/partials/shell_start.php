<?php
$navTitle = trim((string) ($nav_title ?? 'University Portal'));
$navSubtitle = trim((string) ($nav_subtitle ?? 'TutorConnect Malawi'));
$navUser = trim((string) ($nav_user ?? ''));

if ($navUser === '') {
    $navUser = trim((string) ((session()->get('first_name') ?? '') . ' ' . (session()->get('last_name') ?? '')));
}

if ($navUser === '') {
    $navUser = 'University Tutor';
}

$navInitial = strtoupper(substr($navUser, 0, 1));
$showHomeShortcut = !empty($show_home_shortcut);
?>
    <div class="app-container">
        <div class="status-bar"></div>
        <nav class="portal-navbar">
            <div class="portal-nav-left">
                <div class="portal-nav-meta">
                    <h1 class="portal-nav-title"><?= esc($navTitle) ?></h1>
                    <div class="portal-nav-subtitle"><?= esc($navSubtitle) ?></div>
                </div>
            </div>

            <div class="portal-nav-actions">
                <?php if ($showHomeShortcut): ?>
                    <a href="<?= site_url('university-portal/dashboard') ?>" class="portal-nav-button" aria-label="Dashboard">
                        <i class="fas fa-house"></i>
                    </a>
                <?php endif; ?>
                <a href="<?= site_url('logout') ?>" class="portal-nav-button" aria-label="Logout">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
                <div class="portal-avatar"><?= esc($navInitial) ?></div>
            </div>
        </nav>

        <main class="portal-main">
