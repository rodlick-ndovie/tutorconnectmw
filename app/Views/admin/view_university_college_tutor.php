<?= $this->extend('layouts/admin') ?>

<?php
$active_page = 'university_tutors';
$title = $title ?? 'University Tutor Details - TutorConnect Malawi';
$tutor = $tutor ?? [];
$status = strtolower((string) ($tutor['status'] ?? 'draft'));
$isReady = !empty($tutor['is_ready_for_review']);
$displayName = trim((string) ($tutor['full_name'] ?? 'University Tutor'));
$displayName = $displayName !== '' ? $displayName : 'University Tutor';
$profilePicture = trim((string) ($tutor['profile_picture'] ?? ''));
$statusTone = [
    'approved' => 'success',
    'pending_review' => 'warning',
    'rejected' => 'danger',
    'draft' => 'secondary',
][$status] ?? 'secondary';
$statusLabel = ucwords(str_replace('_', ' ', $status));
$formatMoney = static fn ($amount): string => (float) $amount > 0 ? 'MWK ' . number_format((float) $amount) : 'Not set';
$linkedUser = is_array($tutor['linked_user'] ?? null) ? $tutor['linked_user'] : [];
$nameParts = preg_split('/\s+/', $displayName, 2) ?: [];
$firstName = trim((string) ($linkedUser['first_name'] ?? ($nameParts[0] ?? '')));
$lastName = trim((string) ($linkedUser['last_name'] ?? ($nameParts[1] ?? '')));
$emailAddress = trim((string) ($tutor['email'] ?? ($linkedUser['email'] ?? '')));
$phoneNumber = trim((string) ($tutor['phone'] ?? ($linkedUser['phone'] ?? '')));
$workStatus = (string) ($tutor['work_status'] ?? '');
$employmentDisplay = $workStatus === 'Employed' ? 'Yes' : ($workStatus === 'Not Employed' ? 'No' : 'Not specified');
?>

<?= $this->section('content') ?>

<style>
.review-hero,
.review-card,
.review-stat,
.reference-card,
.document-card {
    font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'SF Pro Text', 'Segoe UI', Roboto, sans-serif;
}
.review-hero {
    background: #E55C0D;
    border-radius: 10px;
    color: #fff;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    position: relative;
    overflow: hidden;
}
.review-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: rgba(255, 255, 255, 0.08);
}
.review-hero-inner {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.25rem;
    flex-wrap: wrap;
}
.review-profile {
    display: flex;
    align-items: center;
    gap: 1rem;
    min-width: 0;
    flex: 1 1 420px;
}
.review-avatar {
    width: 96px;
    height: 96px;
    border-radius: 12px;
    overflow: hidden;
    background: rgba(255,255,255,0.16);
    border: 2px solid rgba(255,255,255,0.35);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.review-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.review-kicker {
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: #fff7ed;
    margin-bottom: 0.35rem;
}
.review-title {
    margin: 0;
    font-size: clamp(1.6rem, 3vw, 2.2rem);
    font-weight: 700;
    overflow-wrap: anywhere;
}
.review-meta {
    margin-top: 0.45rem;
    color: rgba(255,255,255,0.78);
    overflow-wrap: anywhere;
}
.review-actions {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
    flex: 0 1 auto;
}
.review-action {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    color: #fff;
    text-decoration: none;
    font-weight: 600;
    border: 1px solid rgba(255,255,255,0.18);
    white-space: normal;
    text-align: center;
}
.review-action:hover {
    color: #fff;
    transform: translateY(-1px);
}
.review-action-form {
    margin: 0;
}
.review-action.danger-action {
    background: #991b1b;
}
.review-action.danger-action:hover {
    background: #7f1d1d;
}
.review-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 1.25rem;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
    height: 100%;
}
.review-card-title {
    margin: 0 0 1rem;
    color: #2C3E50;
    font-weight: 700;
    font-size: 1.05rem;
    display: flex;
    align-items: center;
    gap: 0.55rem;
}
.review-card-title i {
    color: #E55C0D;
}
.review-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}
.review-stat {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 1rem;
}
.review-stat-label {
    color: #64748b;
    font-size: 0.72rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    font-weight: 600;
}
.review-stat-value {
    margin-top: 0.35rem;
    color: #2C3E50;
    font-weight: 700;
    font-size: 1.05rem;
}
.status-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    border-radius: 999px;
    padding: 0.35rem 0.65rem;
    font-size: 0.82rem;
    font-weight: 600;
    background: #f8fafc;
    color: #334155;
    border: 1px solid #e2e8f0;
}
.status-chip.success {
    color: #047857;
    background: #ecfdf5;
    border-color: #a7f3d0;
}
.status-chip.warning {
    color: #92400e;
    background: #fffbeb;
    border-color: #fde68a;
}
.detail-list {
    display: grid;
    gap: 0.75rem;
}
.detail-row {
    display: grid;
    grid-template-columns: minmax(150px, 0.38fr) minmax(0, 1fr);
    gap: 0.75rem 1rem;
    align-items: start;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #f1f5f9;
    min-width: 0;
}
.detail-row:last-child {
    border-bottom: none;
    padding-bottom: 0;
}
.detail-label {
    color: #64748b;
    font-weight: 600;
    min-width: 0;
    line-height: 1.45;
}
.detail-value {
    color: #2C3E50;
    font-weight: 600;
    text-align: left;
    min-width: 0;
    overflow-wrap: anywhere;
    word-break: break-word;
    line-height: 1.55;
}
.chip-list {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}
.service-category-list {
    display: grid;
    gap: 0.85rem;
    margin-bottom: 1rem;
}
.service-category {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 0.85rem;
    background: #f8fafc;
}
.service-category-title {
    margin: 0 0 0.55rem;
    color: #2C3E50;
    font-size: 0.9rem;
    font-weight: 700;
}
.review-chip {
    border-radius: 6px;
    border: 1px solid #fed7aa;
    background: #fff7ed;
    color: #E55C0D;
    padding: 0.35rem 0.6rem;
    font-size: 0.82rem;
    font-weight: 600;
    max-width: 100%;
    overflow-wrap: anywhere;
    line-height: 1.45;
}
.wide-detail-card .detail-row {
    grid-template-columns: minmax(180px, 0.28fr) minmax(0, 1fr);
}
.wide-detail-card .detail-value {
    text-align: left;
}
.documents-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 0.9rem;
}
.document-card {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 0.85rem;
    background: #fff;
}
.document-card-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
    min-width: 0;
}
.document-icon {
    width: 38px;
    height: 38px;
    border-radius: 8px;
    background: #fff7ed;
    color: #E55C0D;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.document-title {
    margin: 0;
    color: #2C3E50;
    font-weight: 700;
    line-height: 1.2;
    overflow-wrap: anywhere;
}
.document-meta {
    margin: 0.15rem 0 0;
    color: #64748b;
    font-size: 0.82rem;
    overflow-wrap: anywhere;
}
.document-preview {
    width: 100%;
    max-height: 170px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #f1f5f9;
    margin-bottom: 0.75rem;
    background: #f8fafc;
}
.document-section-title {
    color: #2C3E50;
    font-size: 0.82rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    margin: 0.25rem 0 0.75rem;
}
.doc-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    width: 100%;
    border: 1px solid #fed7aa;
    border-radius: 8px;
    padding: 0.65rem 0.75rem;
    text-decoration: none;
    color: #E55C0D;
    font-weight: 600;
    background: #fff7ed;
}
.doc-link:hover {
    border-color: #E55C0D;
    color: #fff;
    background: #E55C0D;
}
.empty-text {
    color: #64748b;
    margin: 0;
}
.supporting-section {
    margin-top: 1.5rem;
}
.reference-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 0.9rem;
}
.reference-card {
    display: flex;
    align-items: flex-start;
    gap: 0.85rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 0.9rem;
    background: #fff;
    min-width: 0;
}
.reference-icon {
    width: 38px;
    height: 38px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #fff7ed;
    color: #E55C0D;
    flex-shrink: 0;
}
.reference-title {
    margin: 0 0 0.25rem;
    color: #2C3E50;
    font-weight: 700;
}
.reference-body {
    margin: 0;
    color: #475569;
    line-height: 1.55;
    overflow-wrap: anywhere;
}
.compact-card {
    padding: 1rem;
}
.compact-card .review-card-title {
    margin-bottom: 0.75rem;
    font-size: 0.98rem;
}
.compact-alert {
    border-radius: 8px;
    padding: 0.7rem 0.8rem;
    font-size: 0.9rem;
    margin-bottom: 0;
}
.compact-list {
    margin: 0.65rem 0 0;
    padding-left: 1rem;
    color: #475569;
    font-size: 0.88rem;
}
.compact-list li {
    margin-bottom: 0.25rem;
}
.checklist-panel {
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 0.95rem;
    background: #f8fafc;
}
.checklist-panel.ready {
    border-color: #a7f3d0;
    background: #ecfdf5;
}
.checklist-panel.pending {
    border-color: #fde68a;
    background: #fffbeb;
}
.checklist-head {
    display: flex;
    align-items: flex-start;
    gap: 0.85rem;
}
.checklist-icon {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    background: #fff;
    color: #E55C0D;
    border: 1px solid rgba(15, 23, 42, 0.08);
}
.checklist-panel.ready .checklist-icon {
    color: #047857;
}
.checklist-panel.pending .checklist-icon {
    color: #92400e;
}
.checklist-status {
    margin: 0 0 0.25rem;
    color: #2C3E50;
    font-weight: 700;
}
.checklist-copy {
    margin: 0;
    color: #475569;
    line-height: 1.55;
    font-size: 0.9rem;
}
.checklist-items {
    display: grid;
    gap: 0.5rem;
    margin-top: 0.85rem;
}
.checklist-item {
    display: flex;
    align-items: flex-start;
    gap: 0.55rem;
    padding: 0.55rem 0.65rem;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.72);
    color: #475569;
    font-size: 0.88rem;
    font-weight: 600;
}
.checklist-item i {
    color: #92400e;
    margin-top: 0.12rem;
}
@media (max-width: 992px) {
    .review-grid {
        grid-template-columns: 1fr;
    }
    .detail-row {
        grid-template-columns: 1fr;
    }
    .detail-value {
        text-align: left;
    }
}
</style>

<div class="header-bar">
    <div>
        <h1 class="page-title">University Tutor Review</h1>
        <p class="page-subtitle">Preview the specialist profile, documents, account state, and approval readiness.</p>
    </div>
    <a href="<?= site_url('admin/university-college-tutors') ?>" class="btn-admin" style="background: linear-gradient(135deg, #64748b, #475569);">
        <i class="fas fa-arrow-left me-2"></i>Back to List
    </a>
</div>

<section class="review-hero">
    <div class="review-hero-inner">
        <div class="review-profile">
            <div class="review-avatar">
                <?php if ($profilePicture !== ''): ?>
                    <img src="<?= base_url($profilePicture) ?>" alt="<?= esc($displayName) ?>" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div style="display:none;align-items:center;justify-content:center;width:100%;height:100%;"><i class="fas fa-user-graduate fa-2x"></i></div>
                <?php else: ?>
                    <i class="fas fa-user-graduate fa-2x"></i>
                <?php endif; ?>
            </div>
            <div>
                <div class="review-kicker">Reference <?= esc($tutor['reference_code'] ?? 'N/A') ?></div>
                <h2 class="review-title"><?= esc($displayName) ?></h2>
                <div class="review-meta">
                    <?= esc($emailAddress !== '' ? $emailAddress : '-') ?> &bull; <?= esc($phoneNumber !== '' ? $phoneNumber : '-') ?> &bull; <?= esc($tutor['city_location'] ?? 'Location not specified') ?>
                </div>
            </div>
        </div>

        <div class="review-actions">
            <span class="review-action bg-<?= esc($statusTone) ?>">
                <i class="fas fa-circle-info"></i><?= esc($statusLabel) ?>
            </span>
            <?php if ($status !== 'approved' && $isReady): ?>
                <a href="<?= site_url('admin/approve-university-college-tutor/' . (int) ($tutor['id'] ?? 0)) ?>" class="review-action" style="background:#059669;">
                    <i class="fas fa-check"></i>Approve
                </a>
            <?php endif; ?>
            <?php if ($status !== 'rejected' && $isReady): ?>
                <a href="<?= site_url('admin/reject-university-college-tutor/' . (int) ($tutor['id'] ?? 0)) ?>" class="review-action" style="background:#dc2626;">
                    <i class="fas fa-times"></i>Reject
                </a>
            <?php endif; ?>
            <form
                method="post"
                action="<?= site_url('admin/delete-university-college-tutor/' . (int) ($tutor['id'] ?? 0)) ?>"
                class="review-action-form"
                onsubmit="return confirm('Delete this university tutor account? This removes the profile, linked login account, subscriptions, and uploaded files.');"
            >
                <?= csrf_field() ?>
                <button type="submit" class="review-action danger-action">
                    <i class="fas fa-trash"></i>Delete Account
                </button>
            </form>
        </div>
    </div>
</section>

<div class="review-grid">
    <div class="review-stat">
        <div class="review-stat-label">Review State</div>
        <div class="review-stat-value"><?= $isReady ? 'Ready for approval' : 'Missing information' ?></div>
    </div>
    <div class="review-stat">
        <div class="review-stat-label">Linked Account</div>
        <div class="review-stat-value"><?= !empty($linkedUser) ? 'Linked' : 'Not linked' ?></div>
    </div>
    <div class="review-stat">
        <div class="review-stat-label">Subscription</div>
        <div class="review-stat-value"><?= esc($tutor['linked_subscription_plan'] ?? 'Pending selection') ?></div>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-8">
        <div class="row g-4">
            <div class="col-12">
                <div class="review-card">
                    <h3 class="review-card-title">Professional Summary</h3>
                    <?php if (!empty($tutor['bio'])): ?>
                        <p style="line-height:1.75;margin:0;"><?= nl2br(esc($tutor['bio'])) ?></p>
                    <?php else: ?>
                        <p class="empty-text">No bio submitted yet.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-12">
                <div class="review-card wide-detail-card">
                    <h3 class="review-card-title"><i class="fas fa-address-card"></i>Account Identity</h3>
                    <div class="detail-list">
                        <div class="detail-row"><span class="detail-label">First Name</span><span class="detail-value"><?= esc($firstName !== '' ? $firstName : '-') ?></span></div>
                        <div class="detail-row"><span class="detail-label">Last Name</span><span class="detail-value"><?= esc($lastName !== '' ? $lastName : '-') ?></span></div>
                        <div class="detail-row"><span class="detail-label">Email Address</span><span class="detail-value"><?= esc($emailAddress !== '' ? $emailAddress : '-') ?></span></div>
                        <div class="detail-row"><span class="detail-label">Phone Number</span><span class="detail-value"><?= esc($phoneNumber !== '' ? $phoneNumber : '-') ?></span></div>
                        <div class="detail-row"><span class="detail-label">Username</span><span class="detail-value"><?= esc($tutor['linked_username'] ?? $tutor['username'] ?? '-') ?></span></div>
                        <div class="detail-row"><span class="detail-label">Linked User ID</span><span class="detail-value"><?= esc($tutor['user_id'] ?? '-') ?></span></div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="review-card wide-detail-card">
                    <h3 class="review-card-title"><i class="fas fa-user-graduate"></i>Academic Profile</h3>
                    <div class="detail-list">
                        <div class="detail-row"><span class="detail-label">Preferred Teaching Mode</span><span class="detail-value"><?= esc($tutor['teaching_mode'] ?? '-') ?></span></div>
                        <div class="detail-row"><span class="detail-label">City / Location</span><span class="detail-value"><?= esc($tutor['city_location'] ?? '-') ?></span></div>
                        <div class="detail-row"><span class="detail-label">Year of Study or Graduation</span><span class="detail-value"><?= esc($tutor['year_of_study_or_graduation'] ?? '-') ?></span></div>
                        <div class="detail-row"><span class="detail-label">Currently Employed?</span><span class="detail-value"><?= esc($employmentDisplay) ?></span></div>
                        <?php if ($workStatus === 'Employed'): ?>
                            <div class="detail-row"><span class="detail-label">Employer / Institution Name</span><span class="detail-value"><?= esc($tutor['employer_name'] ?? '-') ?></span></div>
                            <div class="detail-row"><span class="detail-label">Employer Contact</span><span class="detail-value"><?= esc($tutor['employer_contact'] ?? '-') ?></span></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="review-card">
                    <h3 class="review-card-title"><i class="fas fa-building-columns"></i>Academic Background</h3>
                    <p style="margin:0 0 0.5rem;font-weight:600;">Institutions</p>
                    <div class="chip-list mb-3">
                        <?php foreach (($tutor['institutions'] ?? []) as $item): ?>
                            <span class="review-chip"><?= esc($item) ?></span>
                        <?php endforeach; ?>
                        <?php if (empty($tutor['institutions'])): ?><p class="empty-text">No institutions listed.</p><?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="review-card wide-detail-card">
                    <h3 class="review-card-title"><i class="fas fa-layer-group"></i>Services & Availability</h3>
                    <p style="margin:0 0 0.5rem;font-weight:600;">Service Areas</p>
                    <div class="service-category-list">
                        <?php foreach (($tutor['service_area_groups'] ?? []) as $category => $services): ?>
                            <div class="service-category">
                                <p class="service-category-title"><?= esc($category) ?></p>
                                <div class="chip-list">
                                    <?php foreach ($services as $item): ?>
                                        <span class="review-chip" style="background:#ffffff;color:#334155;border-color:#e2e8f0;"><?= esc($item) ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <?php if (empty($tutor['service_areas'])): ?><p class="empty-text">No service areas listed.</p><?php endif; ?>
                    </div>
                    <div class="detail-list">
                        <div class="detail-row"><span class="detail-label">Available Days</span><span class="detail-value"><?= esc(!empty($tutor['available_days']) ? implode(', ', $tutor['available_days']) : '-') ?></span></div>
                        <div class="detail-row"><span class="detail-label">Preferred Times</span><span class="detail-value"><?= esc(!empty($tutor['preferred_times']) ? implode(', ', $tutor['preferred_times']) : '-') ?></span></div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="review-card wide-detail-card">
                    <h3 class="review-card-title"><i class="fas fa-briefcase"></i>University Professional Rates</h3>
                    <div class="detail-list">
                        <div class="detail-row"><span class="detail-label">Hourly Rate</span><span class="detail-value"><?= esc($formatMoney($tutor['hourly_rate'] ?? 0)) ?></span></div>
                        <div class="detail-row"><span class="detail-label">Consultation Package Rate</span><span class="detail-value"><?= esc($formatMoney($tutor['consultation_package_rate'] ?? 0)) ?></span></div>
                        <div class="detail-row"><span class="detail-label">Dissertation Guidance Rate</span><span class="detail-value"><?= esc($formatMoney($tutor['dissertation_package_rate'] ?? 0)) ?></span></div>
                        <div class="detail-row"><span class="detail-label">Exam Preparation Rate</span><span class="detail-value"><?= esc($formatMoney($tutor['exam_preparation_rate'] ?? 0)) ?></span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="review-card compact-card mb-4">
            <h3 class="review-card-title"><i class="fas fa-user-shield"></i>Account Status</h3>
            <div class="detail-list">
                <div class="detail-row">
                    <span class="detail-label">Email Verified</span>
                    <span class="detail-value">
                        <span class="status-chip <?= !empty($tutor['linked_is_verified']) ? 'success' : 'warning' ?>">
                            <i class="fas <?= !empty($tutor['linked_is_verified']) ? 'fa-circle-check' : 'fa-clock' ?>"></i>
                            <?= !empty($tutor['linked_is_verified']) ? 'Yes' : 'No' ?>
                        </span>
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Account Active</span>
                    <span class="detail-value">
                        <span class="status-chip <?= !empty($tutor['linked_is_active']) ? 'success' : 'warning' ?>">
                            <i class="fas <?= !empty($tutor['linked_is_active']) ? 'fa-circle-check' : 'fa-clock' ?>"></i>
                            <?= !empty($tutor['linked_is_active']) ? 'Yes' : 'No' ?>
                        </span>
                    </span>
                </div>
                <div class="detail-row"><span class="detail-label">Tutor Status</span><span class="detail-value"><?= esc(ucwords(str_replace('_', ' ', (string) ($tutor['linked_tutor_status'] ?? 'pending')))) ?></span></div>
                <div class="detail-row"><span class="detail-label">Subscription Plan</span><span class="detail-value"><?= esc($tutor['linked_subscription_plan'] ?? 'Pending selection') ?></span></div>
            </div>

            <?php if ($isReady): ?>
                <div class="checklist-panel ready mt-3">
                    <div class="checklist-head">
                        <span class="checklist-icon"><i class="fas fa-circle-check"></i></span>
                        <div>
                            <p class="checklist-status">Ready for Admin Decision</p>
                            <p class="checklist-copy">All required profile details and verification files are present. The profile can now be approved or rejected after your review.</p>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="checklist-panel pending mt-3">
                    <div class="checklist-head">
                        <span class="checklist-icon"><i class="fas fa-triangle-exclamation"></i></span>
                        <div>
                            <p class="checklist-status">Additional Information Required</p>
                            <p class="checklist-copy">This profile is not ready for approval yet. Ask the applicant to complete the items below before making a final decision.</p>
                        </div>
                    </div>
                    <div class="checklist-items">
                        <?php foreach (($tutor['completion_gaps'] ?? []) as $gap): ?>
                            <div class="checklist-item">
                                <i class="fas fa-circle-exclamation"></i>
                                <span><?= esc($gap) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<div class="supporting-section">
    <div class="review-card">
        <h3 class="review-card-title">Professional References</h3>
        <?php if (!empty($tutor['references'])): ?>
            <div class="reference-grid">
                <?php foreach ($tutor['references'] as $index => $reference): ?>
                    <div class="reference-card">
                        <span class="reference-icon"><i class="fas fa-user-check"></i></span>
                        <div>
                            <p class="reference-title">Reference <?= $index + 1 ?></p>
                            <p class="reference-body"><?= nl2br(esc($reference)) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="empty-text">No professional references submitted.</p>
        <?php endif; ?>
    </div>
</div>

<div class="supporting-section">
    <div class="review-card">
        <h3 class="review-card-title">Submitted Files</h3>
        <?php if (empty($tutor['profile_picture']) && empty($tutor['national_id_file']) && empty($tutor['certification_files'])): ?>
            <p class="empty-text">No files submitted.</p>
        <?php else: ?>
            <div class="documents-grid">
                <?php if (!empty($tutor['profile_picture'])): ?>
                    <div class="document-card">
                        <p class="document-section-title">Identity & Profile</p>
                        <div class="document-card-header">
                            <span class="document-icon"><i class="fas fa-image"></i></span>
                            <div>
                                <p class="document-title">Profile Picture</p>
                                <p class="document-meta">Public display image</p>
                            </div>
                        </div>
                        <img class="document-preview" src="<?= base_url($tutor['profile_picture']) ?>" alt="Profile picture preview" onerror="this.style.display='none';">
                        <a class="doc-link" href="<?= base_url($tutor['profile_picture']) ?>" target="_blank" rel="noopener">
                            Open File <i class="fas fa-arrow-up-right-from-square"></i>
                        </a>
                    </div>
                <?php endif; ?>

                <?php if (!empty($tutor['national_id_file'])): ?>
                    <div class="document-card">
                        <p class="document-section-title">Identity & Profile</p>
                        <div class="document-card-header">
                            <span class="document-icon"><i class="fas fa-id-card"></i></span>
                            <div>
                                <p class="document-title">National ID</p>
                                <p class="document-meta">Identity verification document</p>
                            </div>
                        </div>
                        <a class="doc-link" href="<?= base_url($tutor['national_id_file']) ?>" target="_blank" rel="noopener">
                            Open National ID <i class="fas fa-arrow-up-right-from-square"></i>
                        </a>
                    </div>
                <?php endif; ?>

                <?php foreach (($tutor['certification_files'] ?? []) as $index => $file): ?>
                    <div class="document-card">
                        <p class="document-section-title">Academic Evidence</p>
                        <div class="document-card-header">
                            <span class="document-icon"><i class="fas fa-file-lines"></i></span>
                            <div>
                                <p class="document-title">Certification File <?= $index + 1 ?></p>
                                <p class="document-meta">Transcript, certificate, or academic proof</p>
                            </div>
                        </div>
                        <a class="doc-link" href="<?= base_url($file) ?>" target="_blank" rel="noopener">
                            Open Certification <?= $index + 1 ?> <i class="fas fa-arrow-up-right-from-square"></i>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
