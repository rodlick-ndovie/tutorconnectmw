<!-- Payment Proof Review Content for Modal -->
<main class="main-content">

<!-- User & Plan Information -->
<div class="info-card">
    <div class="info-card-header">
        <div class="info-card-icon" style="background-color: #dbeafe;">
            <i class="fas fa-user text-blue-600"></i>
        </div>
        <div>
            <h3 class="info-card-title">Subscriber Information</h3>
            <p class="info-card-description">
                <?= esc($user['first_name'] . ' ' . $user['last_name']) ?> -
                <?= esc($user['email']) ?>
            </p>
        </div>
    </div>

    <div class="payment-details">
        <?php if ($user['role'] !== 'trainer'): // Only show plan info for non-trainers ?>
        <div class="detail-row">
            <span class="detail-label">Plan:</span>
            <span class="detail-value">
                <?= esc($plan['name']) ?> (MK<?= number_format($plan['price_monthly']) ?>/month)
            </span>
        </div>
        <?php endif; ?>
        <div class="detail-row">
            <span class="detail-label">Payment Method:</span>
            <span class="detail-value">
                <?= esc(ucwords(str_replace('_', ' ', $subscription['payment_method']))) ?>
            </span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Amount Paid:</span>
            <span class="detail-value">MK<?= number_format($subscription['payment_amount']) ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Payment Date:</span>
            <span class="detail-value">
                <?= date('M j, Y \a\t g:i A', strtotime($subscription['payment_date'])) ?>
            </span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Reference:</span>
            <span class="detail-value">
                <?= esc($subscription['payment_reference'] ?: 'Not provided') ?>
            </span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Current Status:</span>
            <span class="status-badge status-info">
                <i class="fas fa-clock mr-1"></i>
                Pending Approval
            </span>
        </div>
    </div>
</div>

<!-- Payment Proof Display -->
<?php if ($proof_file): ?>
    <div class="payment-proof-container">
        <h3 class="mb-4">Payment Proof Document</h3>

        <?php
        $file_path = FCPATH . $proof_file;
        $file_extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
        $file_url = base_url($proof_file); // Use base_url for proper URL generation

        if (file_exists($file_path)):
            if (in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif'])):
        ?>
            <img src="<?= $file_url ?>" alt="Payment Proof" class="payment-proof-image">
            <p class="text-muted mt-3">
                <i class="fas fa-image mr-1"></i>
                Image proof uploaded on <?= date('M j, Y', strtotime($subscription['created_at'])) ?>
            </p>
        <?php elseif ($file_extension === 'pdf'): ?>
            <iframe src="<?= $file_url ?>" class="payment-proof-pdf"></iframe>
            <p class="text-muted mt-3">
                <i class="fas fa-file-pdf mr-1"></i>
                PDF proof uploaded on <?= date('M j, Y', strtotime($subscription['created_at'])) ?>
            </p>
            <p class="text-xs text-muted">Path: <?= $proof_file ?></p>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-file text-6xl text-gray-400 mb-4"></i>
                <p class="text-muted">File type: <?= strtoupper($file_extension) ?></p>
                <a href="<?= $file_url ?>" target="_blank" class="btn btn-primary mt-3">
                    <i class="fas fa-download mr-2"></i>Download File
                </a>
                <p class="text-xs text-muted">Path: <?= $proof_file ?></p>
            </div>
        <?php endif; ?>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-exclamation-triangle text-6xl text-warning mb-4"></i>
                <h4 class="text-warning mb-3">Payment Proof Not Found</h4>
                <p class="text-muted">The payment proof file could not be located on the server.</p>
                <p class="text-sm text-muted mt-2">Expected path: <?= $proof_file ?></p>
            </div>
        <?php endif; ?>
    </div>
<?php else: ?>
    <div class="info-card">
        <div class="info-card-header">
            <div class="info-card-icon" style="background-color: #fef3c7;">
                <i class="fas fa-exclamation-triangle text-yellow-600"></i>
            </div>
            <div>
                <h3 class="info-card-title">No Payment Proof Uploaded</h3>
                <p class="info-card-description">This subscription does not have an associated payment proof file.</p>
            </div>
        </div>
    </div>
<?php endif; ?>

<style>
/* Modal-specific styles */
.info-card {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    border: 1px solid #e2e8f0;
}

.info-card-header {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
}

.info-card-icon {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    font-size: 1.25rem;
}

.info-card-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0;
}

.info-card-description {
    font-size: 0.9rem;
    color: #64748b;
}

.payment-details {
    background: #f8fafc;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.75rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #e2e8f0;
}

.detail-row:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.detail-label {
    font-weight: 600;
    color: #374151;
}

.detail-value {
    color: #1e293b;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.375rem 0.75rem;
    border-radius: 50px;
    font-size: 0.875rem;
    font-weight: 500;
}

.status-info { background-color: #dbeafe; color: #1e40af; }

.payment-proof-container {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    border: 1px solid #e2e8f0;
    text-align: center;
    margin-bottom: 1.5rem;
}

.payment-proof-image {
    max-width: 100%;
    max-height: 400px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    margin-bottom: 1rem;
}

.payment-proof-pdf {
    width: 100%;
    height: 400px;
    border: 1px solid #cbd5e1;
    border-radius: 12px;
}
</style>
</main>
