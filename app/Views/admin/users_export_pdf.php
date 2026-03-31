<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Users Export</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 10px; color: #111827; }
        .header { padding: 10px 0; border-bottom: 2px solid #e5e7eb; margin-bottom: 12px; }
        .row { display: table; width: 100%; }
        .col { display: table-cell; vertical-align: middle; }
        .title { font-size: 15px; font-weight: 700; margin: 0; }
        .meta { margin: 3px 0 0; color: #6b7280; }
        .brand { text-align: right; }
        .logo { height: 38px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 6px 7px; border: 1px solid #e5e7eb; vertical-align: top; }
        th { background: #f9fafb; font-weight: 700; }
        .muted { color: #6b7280; }
    </style>
</head>
<body>
<?php
$siteName = function_exists('site_setting') ? (site_setting('site_name', 'TutorConnect Malawi') ?? 'TutorConnect Malawi') : 'TutorConnect Malawi';
$supportPhone = function_exists('site_setting') ? (site_setting('support_phone', '+265 992 313 978') ?? '+265 992 313 978') : '+265 992 313 978';
$supportEmail = function_exists('site_setting') ? (site_setting('contact_email', 'info@tutorconnectmw.com') ?? 'info@tutorconnectmw.com') : 'info@tutorconnectmw.com';
$supportAddress = function_exists('site_setting') ? (site_setting('support_address', '') ?? '') : '';
$siteLogo = function_exists('site_setting') ? (site_setting('site_logo', '') ?? '') : '';

$logoDataUri = '';
if ($siteLogo !== '') {
    $logoPath = ROOTPATH . 'public/uploads/' . ltrim($siteLogo, '/\\');
    if (is_file($logoPath)) {
        $ext = strtolower(pathinfo($logoPath, PATHINFO_EXTENSION));
        $mime = match ($ext) {
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'svg' => 'image/svg+xml',
            'webp' => 'image/webp',
            default => ''
        };
        if ($mime !== '') {
            $logoDataUri = 'data:' . $mime . ';base64,' . base64_encode((string) file_get_contents($logoPath));
        }
    }
}

$users = $users ?? [];
?>

<div class="header">
    <div class="row">
        <div class="col">
            <p class="title">Manage Users — Export</p>
            <p class="meta">
                Generated: <strong><?= esc(date('Y-m-d H:i:s')) ?></strong>
                <span class="muted">| Total users: <?= esc((string) count($users)) ?></span>
            </p>
            <p class="meta">
                <strong><?= esc($siteName) ?></strong>
                <span class="muted">| <?= esc($supportPhone) ?> | <?= esc($supportEmail) ?><?= $supportAddress !== '' ? ' | ' . esc($supportAddress) : '' ?></span>
            </p>
        </div>
        <div class="col brand">
            <?php if ($logoDataUri !== ''): ?>
                <img class="logo" src="<?= esc($logoDataUri) ?>" alt="<?= esc($siteName) ?>">
            <?php else: ?>
                <span class="muted"><?= esc($siteName) ?></span>
            <?php endif; ?>
        </div>
    </div>
</div>

<table>
    <thead>
        <tr>
            <th style="width: 4%;">#</th>
            <th style="width: 14%;">Name</th>
            <th style="width: 14%;">Username</th>
            <th style="width: 18%;">Email</th>
            <th style="width: 12%;">Phone</th>
            <th style="width: 8%;">Role</th>
            <th style="width: 7%;">Active</th>
            <th style="width: 7%;">Verified</th>
            <th style="width: 10%;">Joined</th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1; ?>
        <?php foreach ($users as $user): ?>
            <?php $fullName = trim((string) (($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''))); ?>
            <tr>
                <td><?= esc((string) $i) ?></td>
                <td><?= esc($fullName !== '' ? $fullName : 'N/A') ?></td>
                <td><?= esc((string) ($user['username'] ?? '')) ?></td>
                <td><?= esc((string) ($user['email'] ?? '')) ?></td>
                <td><?= esc((string) ($user['phone'] ?? '')) ?></td>
                <td><?= esc((string) ($user['role'] ?? '')) ?></td>
                <td><?= ((int) ($user['is_active'] ?? 0)) === 1 ? 'Yes' : 'No' ?></td>
                <td><?= ((int) ($user['is_verified'] ?? 0)) === 1 ? 'Yes' : 'No' ?></td>
                <td><?= esc((string) ($user['created_at'] ?? '')) ?></td>
            </tr>
            <?php $i++; ?>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>

