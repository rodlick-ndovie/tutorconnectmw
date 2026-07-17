<?php $this->extend('layout/main'); $this->section('content'); ?>
<h1>University Lecture Requests Management</h1>
<?php if (session('success')): ?><div class="alert alert-success"><?= session('success') ?></div><?php endif; ?>
<?php if (session('error')): ?><div class="alert alert-danger"><?= session('error') ?></div><?php endif; ?>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>No.</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Institution</th>
            <th>Service Category</th>
            <th>Topic</th>
            <th>Matching</th>
            <th>Accepted</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($requests as $i => $req): ?>
        <tr>
            <td><?= $i+1 ?></td>
            <td><?= esc($req['full_name']) ?></td>
            <td><?= esc($req['email']) ?></td>
            <td><?= esc($req['phone']) ?></td>
            <td><?= esc($req['institution']) ?></td>
            <td><?= esc($req['service_category']) ?></td>
            <td><?= esc($req['topic']) ?></td>
            <td>
                <strong><?= number_format((int) ($req['matched_tutor_count'] ?? 0)) ?></strong> matched<br>
                <span class="text-muted small"><?= number_format((int) ($req['emailed_tutor_count'] ?? 0)) ?> emailed</span>
            </td>
            <td>
                <strong><?= number_format((int) ($req['accepted_tutor_count'] ?? 0)) ?></strong> accepted<br>
                <?php if (!empty($req['accepted_tutor_names'])): ?>
                    <span class="text-muted small"><?= esc($req['accepted_tutor_names']) ?></span>
                <?php else: ?>
                    <span class="text-muted small">No response yet</span>
                <?php endif; ?>
            </td>
            <td><?= esc($req['status']) ?></td>
            <td><?= esc($req['created_at']) ?></td>
            <td>
                <a href="<?= site_url('admin/view-university-lecture-request/'.$req['id']) ?>" class="btn btn-sm btn-info">View</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<a href="<?= site_url('admin/export-university-lecture-requests-excel') ?>" class="btn btn-primary">Export Excel</a>
<a href="<?= site_url('admin/export-university-lecture-requests-pdf') ?>" class="btn btn-secondary">Export PDF</a>
<?php $this->endSection(); ?>
