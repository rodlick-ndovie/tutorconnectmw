<h2>University Tutors Export</h2>
<table border="1" cellpadding="4" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>No.</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Status</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tutors as $i => $tutor): ?>
        <tr>
            <td><?= $i+1 ?></td>
            <td><?= esc($tutor['full_name']) ?></td>
            <td><?= esc($tutor['email']) ?></td>
            <td><?= esc($tutor['phone']) ?></td>
            <td><?= esc($tutor['status']) ?></td>
            <td><?= esc($tutor['created_at']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>