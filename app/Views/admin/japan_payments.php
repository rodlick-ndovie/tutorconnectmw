<?= $this->extend('layouts/admin') ?>

<?php $active_page = 'japan_applications'; ?>
<?php $title = $title ?? 'Japan Payments'; ?>

<?= $this->section('content') ?>

<div class="header-bar">
    <div>
        <h1 class="page-title">Japan Payments</h1>
        <p class="page-subtitle">Monitor application fee payments (pending/verified/failed) and totals</p>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6, #1e40af);">
            <i class="fas fa-receipt"></i>
        </div>
        <div class="stat-number"><?= number_format($totals['total_count'] ?? 0) ?></div>
        <div class="stat-label">Total Records</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
            <i class="fas fa-circle-check"></i>
        </div>
        <div class="stat-number"><?= number_format($totals['verified_count'] ?? 0) ?></div>
        <div class="stat-label">Verified</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-number"><?= number_format($totals['pending_count'] ?? 0) ?></div>
        <div class="stat-label">Pending</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
            <i class="fas fa-triangle-exclamation"></i>
        </div>
        <div class="stat-number"><?= number_format($totals['failed_count'] ?? 0) ?></div>
        <div class="stat-label">Failed</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6, #6d28d9);">
            <i class="fas fa-money-bill-wave"></i>
        </div>
        <div class="stat-number">MK <?= number_format($totals['verified_amount_total'] ?? 0) ?></div>
        <div class="stat-label">Verified Amount Total</div>
    </div>
</div>

<div class="content-card">
    <form method="get" action="<?= site_url('admin/japan-payments') ?>" class="row g-3 align-items-end" style="margin-bottom: 18px;">
        <div class="col-md-6">
            <label class="form-label">Search</label>
            <input type="text" name="q" value="<?= esc($searchQuery ?? '') ?>" class="form-control" placeholder="Tx ref, name, email, application ref">
        </div>
        <div class="col-md-3">
            <label class="form-label">Payment Status</label>
            <select name="payment_status" class="form-control">
                <option value="">All</option>
                <?php foreach (['verified' => 'Verified', 'pending' => 'Pending', 'failed' => 'Failed'] as $value => $label): ?>
                    <option value="<?= esc($value) ?>" <?= ($paymentStatusFilter ?? '') === $value ? 'selected' : '' ?>><?= esc($label) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter me-2"></i>Filter</button>
        </div>
    </form>

    <?php if (!empty($payments)): ?>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 70px;">No.</th>
                        <th>Tx Ref</th>
                        <th>Applicant</th>
                        <th>Email</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Paid At</th>
                        <th>Application Ref</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach ($payments as $p): ?>
                        <tr>
                            <td><strong><?= esc((string) $i) ?></strong></td>
                            <td><?= esc($p['tx_ref'] ?? '') ?></td>
                            <td><?= esc($p['full_name'] ?? '') ?></td>
                            <td><?= esc($p['email'] ?? '') ?></td>
                            <td>MK <?= number_format((float) ($p['amount'] ?? 0), 0) ?></td>
                            <td>
                                <?php
                                $ps = $p['payment_status'] ?? 'pending';
                                $badge = $ps === 'verified' ? 'bg-success' : ($ps === 'failed' ? 'bg-danger' : 'bg-warning text-dark');
                                ?>
                                <span class="badge <?= esc($badge) ?>"><?= esc(ucfirst($ps)) ?></span>
                            </td>
                            <td><?= esc($p['paid_at'] ?? '-') ?></td>
                            <td><?= esc($p['application_reference'] ?? '-') ?></td>
                        </tr>
                    <?php $i++; endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if (!empty($pager)): ?>
            <div style="margin-top: 16px; text-align: center;">
                <?= $pager->links() ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div style="text-align: center; padding: 50px 20px; color: var(--text-light);">
            <i class="fas fa-receipt fa-3x mb-3" style="opacity: 0.4;"></i>
            <p style="margin: 0;">No payment records found.</p>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

