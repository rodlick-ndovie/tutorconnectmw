<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTutorSessionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'tutor_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'student_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
            'parent_email' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => false,
            ],
            'parent_phone' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'subject' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
            'grade_level' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'session_date' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'session_time' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => false,
            ],
            'duration_hours' => [
                'type' => 'DECIMAL',
                'constraint' => '4,2',
                'default' => 1.00,
                'null' => false,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['scheduled', 'completed', 'cancelled', 'rescheduled', 'in_progress'],
                'default' => 'scheduled',
                'null' => false,
            ],
            'session_type' => [
                'type' => 'ENUM',
                'constraint' => ['one-time', 'recurring'],
                'default' => 'one-time',
                'null' => false,
            ],
            'recurrence_pattern' => [
                'type' => 'ENUM',
                'constraint' => ['daily', 'weekly', 'bi-weekly', 'monthly'],
                'null' => true,
            ],
            'recurrence_end_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'reminder_sent' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'null' => false,
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
        $this->forge->addKey('tutor_id');
        $this->forge->addKey('parent_email');
        $this->forge->addKey('session_date');
        $this->forge->addKey('status');

        // Add foreign key constraint
        $this->forge->addForeignKey('tutor_id', 'users', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('tutor_sessions');
    }

    public function down()
    {
        $this->forge->dropTable('tutor_sessions');
    }
}
