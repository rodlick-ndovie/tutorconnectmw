<h2>University Lecture Requests Export</h2>
<table border="1" cellpadding="4" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>No.</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Institution</th>
            <th>Service Category</th>
            <th>Topic</th>
            <th>Matched</th>
            <th>Emailed</th>
            <th>Accepted</th>
            <th>Accepted Tutors</th>
            <th>Status</th>
            <th>Created At</th>
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
            <td><?= number_format((int) ($req['matched_tutor_count'] ?? 0)) ?></td>
            <td><?= number_format((int) ($req['emailed_tutor_count'] ?? 0)) ?></td>
            <td><?= number_format((int) ($req['accepted_tutor_count'] ?? 0)) ?></td>
            <td><?= esc($req['accepted_tutor_names'] ?? '') ?></td>
            <td><?= esc($req['status']) ?></td>
            <td><?= esc($req['created_at']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
