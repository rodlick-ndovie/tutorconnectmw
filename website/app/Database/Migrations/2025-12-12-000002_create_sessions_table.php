<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSessionsTable extends Migration
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
            'booking_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'student_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'tutor_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'service_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'session_date' => [
                'type' => 'DATE',
            ],
            'start_time' => [
                'type' => 'TIME',
            ],
            'end_time' => [
                'type' => 'TIME',
            ],
            'actual_duration' => [
                'type' => 'INT', // Duration in minutes
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['scheduled', 'in_progress', 'completed', 'cancelled', 'no_show'],
                'default' => 'scheduled',
            ],
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
            ],
            'currency' => [
                'type' => 'VARCHAR',
                'constraint' => 3,
                'default' => 'MWK',
            ],
            'payment_status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'paid', 'refunded', 'failed'],
                'default' => 'pending',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'student_feedback' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'tutor_notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'started_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'completed_at' => [
                'type' => 'DATETIME',
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

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('booking_id', 'bookings', 'id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('student_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('tutor_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('service_id', 'services', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('sessions');
    }

    public function down()
    {
        $this->forge->dropTable('sessions');
    }
}
