<?php $this->extend('layout/main'); $this->section('content'); ?>
<h1>Lecture Request Details</h1>
<a href="<?= site_url('admin/university-lecture-requests') ?>" class="btn btn-light">Back to List</a>
<table class="table table-bordered">
    <tr><th>Full Name</th><td><?= esc($request['full_name']) ?></td></tr>
    <tr><th>Email</th><td><?= esc($request['email']) ?></td></tr>
    <tr><th>Phone</th><td><?= esc($request['phone']) ?></td></tr>
    <tr><th>Institution</th><td><?= esc($request['institution']) ?></td></tr>
    <tr><th>Service Category</th><td><?= esc($request['service_category']) ?></td></tr>
    <tr><th>Topic</th><td><?= esc($request['topic']) ?></td></tr>
    <tr><th>Matched Tutors</th><td><?= number_format((int) ($request['matched_tutor_count'] ?? 0)) ?></td></tr>
    <tr><th>Email Notifications Sent</th><td><?= number_format((int) ($request['emailed_tutor_count'] ?? 0)) ?></td></tr>
    <tr><th>Accepted Tutors</th><td><?= number_format(count($acceptances ?? [])) ?></td></tr>
    <tr><th>Status</th><td><?= esc($request['status']) ?></td></tr>
    <tr><th>Created At</th><td><?= esc($request['created_at']) ?></td></tr>
    <tr><th>Updated At</th><td><?= esc($request['updated_at']) ?></td></tr>
    <!-- Add more fields as needed -->
</table>

<h2 class="mt-4">Tutor Follow Up</h2>
<?php if (!empty($acceptances)): ?>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Tutor</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Location</th>
                <th>Mode</th>
                <th>Accepted At</th>
                <th>Profile</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($acceptances as $acceptance): ?>
                <tr>
                    <td><?= esc($acceptance['full_name'] ?? 'University Tutor') ?></td>
                    <td><a href="mailto:<?= esc($acceptance['email'] ?? '') ?>"><?= esc($acceptance['email'] ?? '') ?></a></td>
                    <td><a href="tel:<?= esc($acceptance['phone'] ?? '') ?>"><?= esc($acceptance['phone'] ?? '') ?></a></td>
                    <td><?= esc($acceptance['city_location'] ?? '') ?></td>
                    <td><?= esc($acceptance['teaching_mode'] ?? '') ?></td>
                    <td><?= esc($acceptance['accepted_at'] ?? $acceptance['created_at'] ?? '') ?></td>
                    <td>
                        <?php if (!empty($acceptance['tutor_id'])): ?>
                            <a href="<?= site_url('admin/view-university-college-tutor/' . (int) $acceptance['tutor_id']) ?>" class="btn btn-sm btn-info">View Tutor</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="alert alert-warning">No tutor has accepted this request yet. Check the matched and emailed counts above for outreach status.</div>
<?php endif; ?>

<form method="post" action="<?= site_url('admin/update-university-lecture-request-status/'.$request['id']) ?>">
    <div class="form-group">
        <label for="status">Update Status</label>
        <select name="status" id="status" class="form-control">
            <option value="pending" <?= $request['status']==='pending'?'selected':'' ?>>Pending</option>
            <option value="approved" <?= $request['status']==='approved'?'selected':'' ?>>Approved</option>
            <option value="rejected" <?= $request['status']==='rejected'?'selected':'' ?>>Rejected</option>
            <option value="completed" <?= $request['status']==='completed'?'selected':'' ?>>Completed</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Update Status</button>
</form>
<?php $this->endSection(); ?>
