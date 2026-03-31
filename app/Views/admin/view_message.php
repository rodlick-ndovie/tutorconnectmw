<?= $this->extend('layouts/admin') ?>

<?php $active_page = 'messages'; ?>
<?php $title = $title ?? 'Message Details'; ?>

<?= $this->section('content') ?>

<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 text-gray-800"><i class="fas fa-envelope-open mr-2"></i>Message Details</h1>
        </div>
        <div class="col-md-4 text-right">
            <a href="<?= site_url('admin/contact-messages') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Back to Messages
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><?= esc($message['subject']) ?></h5>
                </div>
                <div class="card-body">
                    <!-- From -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <strong>Name:</strong><br>
                            <?= esc($message['name']) ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Email:</strong><br>
                            <a href="mailto:<?= esc($message['email']) ?>"><?= esc($message['email']) ?></a>
                        </div>
                    </div>

                    <!-- Contact Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <strong>Phone:</strong><br>
                            <?= !empty($message['phone']) ? esc($message['phone']) : 'Not provided' ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Service:</strong><br>
                            <?= !empty($message['service']) ? esc($message['service']) : 'Not specified' ?>
                        </div>
                    </div>

                    <!-- Date -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <strong>Date Received:</strong><br>
                            <?= date('F d, Y \a\t H:i A', strtotime($message['created_at'])) ?>
                        </div>
                    </div>

                    <!-- Message -->
                    <hr>
                    <div class="mb-4">
                        <strong>Message:</strong>
                        <div class="bg-light p-4 rounded mt-3 border-left border-primary" style="border-left-width: 4px;">
                            <?= nl2br(esc($message['message'])) ?>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light">
                    <button type="button" class="btn btn-danger" onclick="deleteMessage(<?= $message['id'] ?>)">
                        <i class="fas fa-trash mr-2"></i>Delete Message
                    </button>
                    <a href="mailto:<?= esc($message['email']) ?>?subject=Re: <?= urlencode($message['subject']) ?>" class="btn btn-primary">
                        <i class="fas fa-reply mr-2"></i>Reply via Email
                    </a>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <div class="card shadow mb-3">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <a href="mailto:<?= esc($message['email']) ?>" class="btn btn-block btn-primary mb-2">
                        <i class="fas fa-envelope mr-2"></i>Send Email
                    </a>
                    <?php if (!empty($message['phone'])): ?>
                        <a href="tel:<?= esc($message['phone']) ?>" class="btn btn-block btn-success mb-2">
                            <i class="fas fa-phone mr-2"></i>Call
                        </a>
                    <?php endif; ?>
                    <button type="button" class="btn btn-block btn-warning" onclick="copyToClipboard('<?= addslashes($message['message']) ?>')">
                        <i class="fas fa-copy mr-2"></i>Copy Message
                    </button>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">Message Status</h6>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>Status:</strong> <span class="badge badge-success">Read</span></p>
                    <p class="mb-0"><strong>Received:</strong><br><small><?= date('M d, Y H:i', strtotime($message['created_at'])) ?></small></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deleteMessage(id) {
    if (confirm('Are you sure you want to delete this message?')) {
        fetch(`<?= site_url('admin/delete-message') ?>/${id}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                window.location = '<?= site_url('admin/contact-messages') ?>';
            } else {
                alert('Error: ' + data.message);
            }
        });
    }
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('Message copied to clipboard!');
    });
}
</script>

<?= $this->endSection() ?>
