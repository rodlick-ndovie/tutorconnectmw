<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTutorsTables extends Migration
{
    public function up()
    {
        // Tutors profile table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
            ],
            'district' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => false,
            ],
            'area' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'default'    => '',
            ],
            'experience_years' => [
                'type'       => 'INT',
                'constraint' => 2,
                'default'    => 1,
            ],
            'rate' => [
                'type'       => 'DECIMAL',
                'constraint' => '8,2',
                'null'       => false,
            ],
            'rate_type' => [
                'type'       => 'ENUM',
                'constraint' => ['per session', 'per week', 'per month'],
                'default'    => 'per session',
            ],
            'teaching_mode' => [
                'type'       => 'ENUM',
                'constraint' => ['Online Only', 'Physical Only', 'Both Online & Physical'],
                'default'    => 'Both Online & Physical',
            ],
            'bio' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'bio_video' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'cv_file' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'training_papers' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'whatsapp_number' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'best_call_time' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => 'Morning (8AM-12PM)',
            ],
            'is_verified' => [
                'type'       => 'TINYINT',
                'constraint' => '1',
                'default'    => '0',
            ],
            'is_employed' => [
                'type'       => 'TINYINT',
                'constraint' => '1',
                'default'    => '0',
            ],
            'school_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'subscription_plan' => [
                'type'       => 'ENUM',
                'constraint' => ['Basic', 'Standard', 'Premium'],
                'default'    => 'Basic',
            ],
            'subscription_expires_at' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'rating' => [
                'type'       => 'DECIMAL',
                'constraint' => '2,1',
                'default'    => '0.0',
            ],
            'review_count' => [
                'type'       => 'INT',
                'constraint' => 10,
                'default'    => 0,
            ],
            'search_count' => [
                'type'       => 'INT',
                'constraint' => 10,
                'default'    => 0,
            ],
            'featured' => [
                'type'       => 'TINYINT',
                'constraint' => '1',
                'default'    => '0',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'active', 'suspended', 'inactive'],
                'default'    => 'pending',
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
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tutors');

        // Tutor subjects relationship table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'tutor_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
            ],
            'subject_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('tutor_id', 'tutors', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tutor_subjects');

        // Tutor education levels relationship table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'tutor_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
            ],
            'level_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('tutor_id', 'tutors', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tutor_levels');

        // Tutor curricula relationship table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'tutor_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
            ],
            'curriculum_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('tutor_id', 'tutors', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tutor_curricula');

        // Tutor certificates table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'tutor_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
            ],
            'certificate_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
                'null'       => false,
            ],
            'file_path' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('tutor_id', 'tutors', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tutor_certificates');

        // Tutor references table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'tutor_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
            ],
            'referee_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'position' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => false,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('tutor_id', 'tutors', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tutor_references');

        // Tutor availability table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'tutor_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
            ],
            'day_of_week' => [
                'type'       => 'ENUM',
                'constraint' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
            ],
            'time_slot' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('tutor_id', 'tutors', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tutor_availability');

        // Bookings table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'tutor_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
            ],
            'student_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
            ],
            'parent_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'parent_phone' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => false,
            ],
            'parent_email' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => false,
            ],
            'student_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'student_level' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => false,
            ],
            'subjects_needed' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'booking_date' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'booking_time' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => false,
            ],
            'session_type' => [
                'type'       => 'ENUM',
                'constraint' => ['trial', 'package', 'one-off'],
                'default'    => 'one-off',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'confirmed', 'completed', 'cancelled', 'rescheduled'],
                'default'    => 'pending',
            ],
            'notes' => [
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
        $this->forge->addForeignKey('tutor_id', 'tutors', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('student_id', 'users', 'id', 'SET NULL', 'SET NULL');
        $this->forge->createTable('bookings');

        // Reviews table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'booking_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
            ],
            'reviewer_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'rating' => [
                'type'       => 'DECIMAL',
                'constraint' => '2,1',
                'null'       => false,
            ],
            'comment' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('booking_id', 'bookings', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('reviews');
    }

    public function down()
    {
        $this->forge->dropTable('reviews');
        $this->forge->dropTable('bookings');
        $this->forge->dropTable('tutor_availability');
        $this->forge->dropTable('tutor_references');
        $this->forge->dropTable('tutor_certificates');
        $this->forge->dropTable('tutor_curricula');
        $this->forge->dropTable('tutor_levels');
        $this->forge->dropTable('tutor_subjects');
        $this->forge->dropTable('tutors');
    }
}
