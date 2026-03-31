<?= $this->extend('layouts/admin') ?>

<?php $active_page = 'settings'; ?>
<?php $title = $title ?? 'System Settings - TutorConnect Malawi'; ?>

<?= $this->section('content') ?>

<style>
.settings-section {
    background: var(--bg-secondary);
    border-radius: var(--border-radius-lg);
    padding: 24px;
    box-shadow: var(--shadow);
    border: 1px solid rgba(0, 0, 0, 0.05);
    margin-bottom: 24px;
}

.section-title {
    font-size: 20px;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 8px;
    display: block;
}

.form-control {
    border: 1px solid rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    padding: 12px 16px;
    font-size: 14px;
}

.form-control:focus {
    border-color: var(--admin-primary);
    box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.1);
}

.form-text {
    color: var(--text-light);
    font-size: 12px;
    margin-top: 4px;
}

.switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 24px;
}

.slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}

input:checked + .slider {
    background-color: var(--admin-primary);
}

input:checked + .slider:before {
    transform: translateX(26px);
}

.action-buttons {
    display: flex;
    gap: 12px;
    margin-top: 24px;
}
</style>

<!-- Header -->
<div class="header-bar">
    <div>
        <h1 class="page-title">System Settings</h1>
        <p class="page-subtitle">Configure platform settings and preferences</p>
    </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="content-card" style="border-left: 4px solid var(--success-color);">
        <strong style="color: var(--text-dark);">Success:</strong>
        <span style="color: var(--text-light);"><?= esc(session()->getFlashdata('success')) ?></span>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="content-card" style="border-left: 4px solid var(--danger-color);">
        <strong style="color: var(--text-dark);">Error:</strong>
        <span style="color: var(--text-light);"><?= esc(session()->getFlashdata('error')) ?></span>
    </div>
<?php endif; ?>

<?php if (!empty($settings_table_missing)): ?>
    <div class="content-card" style="border-left: 4px solid var(--warning-color);">
        <strong style="color: var(--text-dark);">Action required:</strong>
        <span style="color: var(--text-light);">
            The <code>site_settings</code> table is not available yet.
            Create it using <code>create_site_settings_table.sql</code> (recommended for your current setup), then refresh this page.
        </span>
    </div>
<?php endif; ?>

<?php $validation = session('validation'); ?>

<form method="post" action="<?= base_url('admin/settings') ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <!-- Basic Information -->
    <div class="settings-section">
        <h4 class="section-title">Basic Information</h4>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="form-group">
                <label class="form-label" for="site_name">Platform Name</label>
                <input
                    id="site_name"
                    name="site_name"
                    type="text"
                    class="form-control w-full"
                    value="<?= esc($settings['site_name'] ?? '') ?>"
                    placeholder="Enter platform name"
                >
                <?php if ($validation && $validation->hasError('site_name')): ?>
                    <div class="form-text" style="color: var(--danger-color);"><?= esc($validation->getError('site_name')) ?></div>
                <?php else: ?>
                    <div class="form-text">This name will appear throughout the platform</div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label" for="site_logo">Site Logo (optional)</label>
                <input id="site_logo" name="site_logo" type="file" class="form-control w-full" accept="image/png,image/jpeg,image/webp">
                <div class="form-text">PNG/JPG/WebP recommended. Shown in the public header and footer.</div>

                <?php if (!empty($settings['site_logo'])): ?>
                    <div style="margin-top: 10px; display: flex; align-items: center; gap: 12px;">
                        <img
                            src="<?= esc(base_url('uploads/' . $settings['site_logo'])) ?>"
                            alt="Current site logo"
                            style="height: 40px; width: auto; border-radius: 8px; border: 1px solid rgba(0,0,0,0.08); background: #fff; padding: 6px;"
                        >
                        <div class="form-text" style="margin: 0;">Current logo: <?= esc($settings['site_logo']) ?></div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label" for="site_favicon">Favicon (optional)</label>
                <input id="site_favicon" name="site_favicon" type="file" class="form-control w-full" accept="image/x-icon,image/vnd.microsoft.icon,image/png,image/jpeg,image/webp">
                <div class="form-text">ICO recommended. Shown in the browser tab/address bar.</div>

                <?php if (!empty($settings['site_favicon'])): ?>
                    <div style="margin-top: 10px; display: flex; align-items: center; gap: 12px;">
                        <img
                            src="<?= esc(base_url('uploads/' . $settings['site_favicon'])) ?>"
                            alt="Current favicon"
                            style="height: 24px; width: 24px; border-radius: 6px; border: 1px solid rgba(0,0,0,0.08); background: #fff; padding: 4px;"
                        >
                        <div class="form-text" style="margin: 0;">Current favicon: <?= esc($settings['site_favicon']) ?></div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label" for="timezone">Timezone</label>
                <select id="timezone" name="timezone" class="form-control w-full">
                    <?php $tz = $settings['timezone'] ?? 'Africa/Blantyre'; ?>
                    <option value="Africa/Blantyre" <?= $tz === 'Africa/Blantyre' ? 'selected' : '' ?>>Africa/Blantyre (UTC+2)</option>
                    <option value="UTC" <?= $tz === 'UTC' ? 'selected' : '' ?>>UTC</option>
                </select>
                <?php if ($validation && $validation->hasError('timezone')): ?>
                    <div class="form-text" style="color: var(--danger-color);"><?= esc($validation->getError('timezone')) ?></div>
                <?php else: ?>
                    <div class="form-text">Used for dates/times shown in the platform</div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label" for="contact_email">Contact Email</label>
                <input
                    id="contact_email"
                    name="contact_email"
                    type="email"
                    class="form-control w-full"
                    value="<?= esc($settings['contact_email'] ?? '') ?>"
                    placeholder="Enter contact email"
                >
                <?php if ($validation && $validation->hasError('contact_email')): ?>
                    <div class="form-text" style="color: var(--danger-color);"><?= esc($validation->getError('contact_email')) ?></div>
                <?php else: ?>
                    <div class="form-text">Primary contact email for the platform</div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label" for="support_phone">Contact Phone</label>
                <input
                    id="support_phone"
                    name="support_phone"
                    type="text"
                    class="form-control w-full"
                    value="<?= esc($settings['support_phone'] ?? '') ?>"
                    placeholder="Enter contact phone"
                >
                <?php if ($validation && $validation->hasError('support_phone')): ?>
                    <div class="form-text" style="color: var(--danger-color);"><?= esc($validation->getError('support_phone')) ?></div>
                <?php else: ?>
                    <div class="form-text">Primary contact phone number</div>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-group" style="margin-top: 12px;">
            <label class="form-label" for="support_address">Business Address (optional)</label>
            <textarea
                id="support_address"
                name="support_address"
                class="form-control w-full"
                rows="3"
                placeholder="Enter business address"
            ><?= esc($settings['support_address'] ?? '') ?></textarea>
            <?php if ($validation && $validation->hasError('support_address')): ?>
                <div class="form-text" style="color: var(--danger-color);"><?= esc($validation->getError('support_address')) ?></div>
            <?php else: ?>
                <div class="form-text">Shown on legal pages when needed</div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Social Media -->
    <div class="settings-section">
        <h4 class="section-title">Social Media</h4>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="form-group">
                <label class="form-label" for="social_facebook_url">Facebook URL (optional)</label>
                <input
                    id="social_facebook_url"
                    name="social_facebook_url"
                    type="url"
                    class="form-control w-full"
                    value="<?= esc($settings['social_facebook_url'] ?? '') ?>"
                    placeholder="https://facebook.com/yourpage"
                >
                <div class="form-text">If blank, the Facebook icon will be hidden.</div>
            </div>

            <div class="form-group">
                <label class="form-label" for="social_twitter_url">Twitter/X URL (optional)</label>
                <input
                    id="social_twitter_url"
                    name="social_twitter_url"
                    type="url"
                    class="form-control w-full"
                    value="<?= esc($settings['social_twitter_url'] ?? '') ?>"
                    placeholder="https://x.com/yourhandle"
                >
                <div class="form-text">If blank, the Twitter/X icon will be hidden.</div>
            </div>

            <div class="form-group">
                <label class="form-label" for="social_instagram_url">Instagram URL (optional)</label>
                <input
                    id="social_instagram_url"
                    name="social_instagram_url"
                    type="url"
                    class="form-control w-full"
                    value="<?= esc($settings['social_instagram_url'] ?? '') ?>"
                    placeholder="https://instagram.com/yourhandle"
                >
                <div class="form-text">If blank, the Instagram icon will be hidden.</div>
            </div>
        </div>

    </div>

    <!-- Japan Application Fees -->
    <div class="settings-section">
        <h4 class="section-title">Teach in Japan Fees</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="form-group md:col-span-2">
                <label class="form-label" for="japan_applications_open">Japan Applications Status</label>
                <div style="display:flex; align-items:center; gap: 14px; flex-wrap: wrap;">
                    <label class="switch" title="Toggle to open/close Japan applications">
                        <input
                            id="japan_applications_open"
                            name="japan_applications_open"
                            type="checkbox"
                            value="1"
                            <?= ((string) ($settings['japan_applications_open'] ?? '1')) === '1' ? 'checked' : '' ?>
                        >
                        <span class="slider"></span>
                    </label>
                    <div>
                        <div style="font-weight: 700; color: var(--text-dark);">
                            <?= ((string) ($settings['japan_applications_open'] ?? '1')) === '1' ? 'Open (Accepting Applications)' : 'Closed (Not Accepting New Applications)' ?>
                        </div>
                        <div class="form-text" style="margin: 0;">When closed, payment initiation is blocked and the public website shows the message below.</div>
                    </div>
                </div>
                <?php if ($validation && $validation->hasError('japan_applications_open')): ?>
                    <div class="form-text" style="color: var(--danger-color);"><?= esc($validation->getError('japan_applications_open')) ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group md:col-span-2">
                <label class="form-label" for="japan_applications_closed_message">Public Message (shown when applications are closed)</label>
                <textarea
                    id="japan_applications_closed_message"
                    name="japan_applications_closed_message"
                    class="form-control w-full"
                    rows="3"
                    placeholder="Enter the message to display on the public Japan pages when applications are closed"
                ><?= esc($settings['japan_applications_closed_message'] ?? '') ?></textarea>
                <?php if ($validation && $validation->hasError('japan_applications_closed_message')): ?>
                    <div class="form-text" style="color: var(--danger-color);"><?= esc($validation->getError('japan_applications_closed_message')) ?></div>
                <?php else: ?>
                    <div class="form-text">Example: “Applications are currently closed. Please check back on Monday.”</div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label" for="japan_application_fee">Japan Application Fee (MWK)</label>
                <input
                    id="japan_application_fee"
                    name="japan_application_fee"
                    type="number"
                    min="0"
                    step="1"
                    class="form-control w-full"
                    value="<?= esc($settings['japan_application_fee'] ?? '10000') ?>"
                    placeholder="10000"
                    required
                >
                <?php if ($validation && $validation->hasError('japan_application_fee')): ?>
                    <div class="form-text" style="color: var(--danger-color);"><?= esc($validation->getError('japan_application_fee')) ?></div>
                <?php else: ?>
                    <div class="form-text">This is the amount paid to unlock the Japan application form.</div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label" for="japan_processing_fee">Japan Processing Fee (MWK)</label>
                <input
                    id="japan_processing_fee"
                    name="japan_processing_fee"
                    type="number"
                    min="0"
                    step="1"
                    class="form-control w-full"
                    value="<?= esc($settings['japan_processing_fee'] ?? '350000') ?>"
                    placeholder="350000"
                    required
                >
                <?php if ($validation && $validation->hasError('japan_processing_fee')): ?>
                    <div class="form-text" style="color: var(--danger-color);"><?= esc($validation->getError('japan_processing_fee')) ?></div>
                <?php else: ?>
                    <div class="form-text">Displayed as the later-stage processing cost (not paid at unlock).</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="action-buttons">
        <button type="submit" class="btn-admin">Save Settings</button>
        <a href="<?= base_url('admin/settings') ?>" class="btn-admin" style="background: #6b7280;">Cancel</a>
    </div>
</form>
<?= $this->endSection() ?>
