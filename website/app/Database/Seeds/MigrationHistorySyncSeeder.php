<?php

namespace App\Database\Seeds;

use CodeIgniter\CLI\CLI;
use CodeIgniter\Database\Seeder;

class MigrationHistorySyncSeeder extends Seeder
{
    public function run()
    {
        if (!$this->db->tableExists('migrations')) {
            CLI::error('The migrations table does not exist.');
            return;
        }

        $existingRows = $this->db->table('migrations')->select('id, version')->get()->getResultArray();
        $existingVersions = [];
        foreach ($existingRows as $row) {
            $existingVersions[(string) ($row['version'] ?? '')] = (int) ($row['id'] ?? 0);
        }

        $batchRow = $this->db->table('migrations')->selectMax('batch')->get()->getRowArray();
        $batch = max(1, (int) ($batchRow['batch'] ?? 0));
        if (!empty($existingRows)) {
            $batch++;
        }

        $migrationFiles = glob(APPPATH . 'Database/Migrations/*.php') ?: [];
        sort($migrationFiles, SORT_STRING);

        $inserted = 0;
        $now = time();

        foreach ($migrationFiles as $filePath) {
            $fileName = basename($filePath);
            if (!preg_match('/^([0-9-]+)_/', $fileName, $versionMatch)) {
                continue;
            }

            $version = $versionMatch[1];
            $content = file_get_contents($filePath);
            if ($content === false) {
                continue;
            }

            if (!preg_match('/namespace\s+([^;]+);/', $content, $namespaceMatch)) {
                continue;
            }

            if (!preg_match('/class\s+([A-Za-z0-9_]+)/', $content, $classMatch)) {
                continue;
            }

            $className = trim($classMatch[1]);
            $classNamespace = trim($namespaceMatch[1]);
            $className = $classNamespace . '\\' . $className;

            if (isset($existingVersions[$version])) {
                $this->db->table('migrations')->where('id', $existingVersions[$version])->update([
                    'class' => $className,
                    'group' => 'default',
                    'namespace' => 'App',
                ]);

                continue;
            }

            $this->db->table('migrations')->insert([
                'version' => $version,
                'class' => $className,
                'group' => 'default',
                'namespace' => 'App',
                'time' => $now,
                'batch' => $batch,
            ]);

            $inserted++;
        }

        CLI::write('Migration history sync complete. Inserted rows: ' . $inserted, 'green');
    }
}
