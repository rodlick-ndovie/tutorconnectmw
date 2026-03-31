<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePastPapersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'exam_body' => [
                'type'       => 'ENUM',
                'constraint' => ['MANEB', 'Cambridge', 'GCSE', 'Other'],
                'null'       => false,
            ],
            'exam_level' => [
                'type'       => 'ENUM',
                'constraint' => ['JCE', 'MSCE', 'IGCSE', 'AS-Level', 'A-Level', 'Primary'],
                'null'       => false,
            ],
            'subject' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'year' => [
                'type'       => 'INT',
                'constraint' => 4,
                'null'       => false,
            ],
            'paper_title' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'paper_code' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'file_url' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'file_size' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'download_count' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 0,
            ],
            'is_active' => [
                'type'       => 'BOOLEAN',
                'default'    => true,
            ],
            'copyright_notice' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('past_papers');
    }

    public function down()
    {
        $this->forge->dropTable('past_papers');
    }
}
