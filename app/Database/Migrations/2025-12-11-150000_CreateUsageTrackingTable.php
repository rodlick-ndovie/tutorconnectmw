<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsageTrackingTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'metric_type' => [
                'type' => 'ENUM',
                'constraint' => ['profile_views', 'clicks', 'messages', 'video_uploads', 'pdf_uploads', 'announcements'],
            ],
            'metric_value' => [
                'type' => 'INT',
                'default' => 1,
            ],
            'reference_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
            ],
            'metadata' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'tracked_at' => [
                'type' => 'DATETIME',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
            'period_start' => [
                'type' => 'DATETIME',
            ],
            'period_end' => [
                'type' => 'DATETIME',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');

        // Add indexes for performance
        $this->forge->addKey(['user_id', 'metric_type']);
        $this->forge->addKey('tracked_at');
        $this->forge->addKey(['period_start', 'period_end']);

        $this->forge->createTable('usage_tracking');
    }

    public function down()
    {
        $this->forge->dropTable('usage_tracking');
    }
}
