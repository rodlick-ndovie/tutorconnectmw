<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReviewsTable extends Migration
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
            'booking_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'rating' => [
                'type' => 'TINYINT',
                'constraint' => 1,
            ],
            'comment' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'approved', 'rejected'],
                'default' => 'pending',
            ],
            'is_anonymous' => [
                'type' => 'BOOLEAN',
                'default' => false,
            ],
            'helpful_count' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => 0,
            ],
            'moderator_notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'reviewed_at' => [
                'type' => 'DATETIME',
            ],
            'moderated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'moderated_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
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
        $this->forge->addForeignKey('student_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('tutor_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('booking_id', 'bookings', 'id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('moderated_by', 'users', 'id', 'CASCADE', 'SET NULL');

        // Index for efficient querying
        $this->forge->addKey(['tutor_id', 'status']);
        $this->forge->addKey(['rating']);

        if (!$this->db->tableExists('reviews')) {
            $this->forge->createTable('reviews');
        }
    }

    public function down()
    {
        $this->forge->dropTable('reviews');
    }
}
