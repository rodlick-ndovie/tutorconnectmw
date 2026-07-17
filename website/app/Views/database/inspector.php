<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        h1 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
        .status { padding: 10px; background: #d4edda; border: 1px solid #c3e6cb; border-radius: 4px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #007bff; color: white; }
        tr:hover { background-color: #f5f5f5; }
        .table-name { font-weight: bold; color: #007bff; cursor: pointer; }
        .field-list { font-size: 0.9em; color: #666; }
        .badge { display: inline-block; padding: 3px 8px; background: #007bff; color: white; border-radius: 3px; font-size: 0.8em; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🗄️ Database Inspector</h1>

        <div class="status">
            <strong>Status:</strong> <?= esc($connection_status) ?> |
            <strong>Database:</strong> <?= esc($database) ?> |
            <strong>Tables:</strong> <?= count($tables) ?>
        </div>

        <h2>Tables Overview</h2>
        <table>
            <thead>
                <tr>
                    <th>Table Name</th>
                    <th>Record Count</th>
                    <th>Fields</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tables as $table): ?>
                <tr>
                    <td class="table-name"><?= esc($table['name']) ?></td>
                    <td><span class="badge"><?= esc($table['record_count']) ?> records</span></td>
                    <td class="field-list">
                        <?php
                        $fieldNames = array_map(function($field) { return $field->name; }, $table['fields']);
                        echo implode(', ', array_slice($fieldNames, 0, 5));
                        if (count($fieldNames) > 5) echo ' ... (' . count($fieldNames) . ' total)';
                        ?>
                    </td>
                    <td>
                        <a href="<?= base_url('database-inspector/table/' . $table['name']) ?>">View Details</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2>Table Structures</h2>
        <?php foreach ($tables as $table): ?>
        <details style="margin: 20px 0; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            <summary style="cursor: pointer; font-weight: bold; padding: 5px;">
                📋 <?= esc($table['name']) ?> (<?= count($table['fields']) ?> fields)
            </summary>
            <table style="margin-top: 10px;">
                <thead>
                    <tr>
                        <th>Field</th>
                        <th>Type</th>
                        <th>Max Length</th>
                        <th>Nullable</th>
                        <th>Primary Key</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($table['fields'] as $field): ?>
                    <tr>
                        <td><?= esc($field->name) ?></td>
                        <td><?= esc($field->type) ?></td>
                        <td><?= esc($field->max_length ?? 'N/A') ?></td>
                        <td><?= $field->nullable ? 'Yes' : 'No' ?></td>
                        <td><?= $field->primary_key ? '🔑 Yes' : 'No' ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </details>
        <?php endforeach; ?>
    </div>
</body>
</html>
