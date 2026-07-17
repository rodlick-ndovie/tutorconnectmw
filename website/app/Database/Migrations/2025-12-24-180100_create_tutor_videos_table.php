<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTutorVideosTable extends Migration
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
            'tutor_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'video_embed_code' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'video_platform' => [
                'type'       => 'ENUM',
                'constraint' => ['youtube', 'vimeo'],
                'null'       => false,
            ],
            'video_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => false,
            ],
            'exam_body' => [
                'type'       => 'ENUM',
                'constraint' => ['MANEB', 'Cambridge', 'GCSE', 'Other'],
                'null'       => false,
            ],
            'subject' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'topic' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'problem_year' => [
                'type'       => 'INT',
                'constraint' => 4,
                'null'       => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending_review', 'approved', 'rejected'],
                'default'    => 'pending_review',
                'null'       => false,
            ],
            'featured_level' => [
                'type'       => 'ENUM',
                'constraint' => ['none', 'standard', 'premium_featured'],
                'default'    => 'none',
                'null'       => false,
            ],
            'view_count' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 0,
            ],
            'submitted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'approved_at' => [
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
        $this->forge->addForeignKey('tutor_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tutor_videos');
    }

    public function down()
    {
        $this->forge->dropTable('tutor_videos');
    }
}
