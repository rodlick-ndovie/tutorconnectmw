<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCurriculumSubjectsTable extends Migration
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
            'curriculum' => [
                'type'       => 'ENUM',
                'constraint' => ['MANEB', 'GCSE', 'Cambridge'],
                'null'       => false,
            ],
            'level_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'subject_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'subject_category' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'default'    => null,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
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
        $this->forge->addKey(['curriculum', 'level_name']);
        $this->forge->addKey('subject_name');

        $this->forge->createTable('curriculum_subjects');
    }

    public function down()
    {
        $this->forge->dropTable('curriculum_subjects');
    }
}
