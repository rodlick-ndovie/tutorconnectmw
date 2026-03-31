<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AdditionalTables extends Migration
{
    public function up()
    {
        // Create past_papers table for Resources section
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'subject' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'education_level' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'exam_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'comment' => 'MSCE, JCE, etc.',
            ],
            'year' => [
                'type'       => 'YEAR',
            ],
            'file_path' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'file_size' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
            ],
            'downloads_count' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'default'    => 0,
            ],
            'uploaded_by' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
            ],
            'is_approved' => [
                'type'       => 'TINYINT',
                'constraint' => '1',
                'default'    => 0,
            ],
            'approved_by' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
            ],
            'approved_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => '1',
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
        $this->forge->addKey('subject');
        $this->forge->addKey('education_level');
        $this->forge->addKey('exam_type');
        $this->forge->addKey('year');
        $this->forge->addKey('uploaded_by');
        $this->forge->addKey('is_approved');
        $this->forge->createTable('past_papers');

        // Create tutor_videos table for Video Solutions
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'video_url' => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
                'comment' => 'YouTube, Vimeo, or direct video URL',
            ],
            'video_type' => [
                'type'       => 'ENUM',
                'constraint' => ['youtube', 'vimeo', 'direct'],
                'default'    => 'youtube',
            ],
            'subject' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'education_level' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'topic' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'duration' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'comment' => 'e.g., 15:30',
                'null'       => true,
            ],
            'uploaded_by' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
            ],
            'is_approved' => [
                'type'       => 'TINYINT',
                'constraint' => '1',
                'default'    => 0,
            ],
            'approved_by' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
            ],
            'approved_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'views_count' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'default'    => 0,
            ],
            'likes_count' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'default'    => 0,
            ],
            'is_featured' => [
                'type'       => 'TINYINT',
                'constraint' => '1',
                'default'    => 0,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => '1',
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
        $this->forge->addKey('subject');
        $this->forge->addKey('education_level');
        $this->forge->addKey('uploaded_by');
        $this->forge->addKey('is_approved');
        $this->forge->addKey('is_featured');
        $this->forge->createTable('tutor_videos');

        // Create announcements table for premium tutors
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'content' => [
                'type' => 'TEXT',
            ],
            'announcement_type' => [
                'type'       => 'ENUM',
                'constraint' => ['school_services', 'exam_prep', 'tuition_services', 'other'],
                'default'    => 'tuition_services',
            ],
            'target_audience' => [
                'type'       => 'ENUM',
                'constraint' => ['schools', 'ministry', 'parents', 'students', 'general'],
                'default'    => 'general',
            ],
            'posted_by' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
            ],
            'district' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'contact_info' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'is_approved' => [
                'type'       => 'TINYINT',
                'constraint' => '1',
                'default'    => 0,
            ],
            'approved_by' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
            ],
            'approved_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'expires_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'views_count' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'default'    => 0,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => '1',
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
        $this->forge->addKey('announcement_type');
        $this->forge->addKey('target_audience');
        $this->forge->addKey('posted_by');
        $this->forge->addKey('district');
        $this->forge->addKey('is_approved');
        $this->forge->createTable('announcements');

        // Create bookings table for tutor bookings
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
            ],
            'parent_phone' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
            ],
            'parent_email' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'student_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'student_level' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'subjects_needed' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'booking_date' => [
                'type' => 'DATE',
            ],
            'booking_time' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'session_type' => [
                'type'       => 'ENUM',
                'constraint' => ['trial', 'package', 'one-off'],
                'default'    => 'one-off',
            ],
            'amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
            'currency' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'default'    => 'MWK',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'confirmed', 'completed', 'cancelled', 'rescheduled'],
                'default'    => 'pending',
            ],
            'payment_status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'paid', 'refunded', 'failed'],
                'default'    => 'pending',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'meeting_link' => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
                'null'       => true,
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
        $this->forge->addKey('student_id');
        $this->forge->addKey('booking_date');
        $this->forge->addKey('status');
        $this->forge->addKey('payment_status');
        $this->forge->createTable('bookings');

        // Create messages table for communication
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'sender_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
            ],
            'receiver_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
            ],
            'booking_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
            ],
            'subject' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'message' => [
                'type' => 'TEXT',
            ],
            'message_type' => [
                'type'       => 'ENUM',
                'constraint' => ['inquiry', 'booking_request', 'confirmation', 'reschedule', 'cancellation', 'feedback', 'general'],
                'default'    => 'general',
            ],
            'is_read' => [
                'type'       => 'TINYINT',
                'constraint' => '1',
                'default'    => 0,
            ],
            'is_system_message' => [
                'type'       => 'TINYINT',
                'constraint' => '1',
                'default'    => 0,
            ],
            'parent_message_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
            ],
            'sent_at' => [
                'type' => 'DATETIME',
            ],
            'read_at' => [
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

        $this->forge->addKey('id', true);
        $this->forge->addKey(['sender_id', 'receiver_id']);
        $this->forge->addKey('booking_id');
        $this->forge->addKey('is_read');
        $this->forge->addKey('sent_at');
        $this->forge->createTable('messages');

        // Create reviews table for feedback
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
            'reviewer_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
            ],
            'tutor_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
            ],
            'rating' => [
                'type'       => 'DECIMAL',
                'constraint' => '2,1',
            ],
            'comment' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'review_type' => [
                'type'       => 'ENUM',
                'constraint' => ['session', 'overall', 'communication', 'punctuality', 'teaching_quality'],
                'default'    => 'session',
            ],
            'is_anonymous' => [
                'type'       => 'TINYINT',
                'constraint' => '1',
                'default'    => 0,
            ],
            'is_verified' => [
                'type'       => 'TINYINT',
                'constraint' => '1',
                'default'    => 1,
                'comment' => 'Verified booking completion',
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
        $this->forge->addKey('booking_id');
        $this->forge->addKey('reviewer_id');
        $this->forge->addKey('tutor_id');
        $this->forge->addKey('rating');
        $this->forge->createTable('reviews');
    }

    public function down()
    {
        $this->forge->dropTable('past_papers', true);
        $this->forge->dropTable('tutor_videos', true);
        $this->forge->dropTable('announcements', true);
        $this->forge->dropTable('bookings', true);
        $this->forge->dropTable('messages', true);
        $this->forge->dropTable('reviews', true);
    }
}
