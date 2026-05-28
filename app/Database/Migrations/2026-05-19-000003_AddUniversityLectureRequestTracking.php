<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUniversityLectureRequestTracking extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('university_lecture_requests')) {
            $columns = [];

            if (!$this->db->fieldExists('matched_tutor_count', 'university_lecture_requests')) {
                $columns['matched_tutor_count'] = [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'default' => 0,
                    'after' => 'status',
                ];
            }

            if (!$this->db->fieldExists('emailed_tutor_count', 'university_lecture_requests')) {
                $columns['emailed_tutor_count'] = [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'default' => 0,
                    'after' => 'matched_tutor_count',
                ];
            }

            if ($columns !== []) {
                $this->forge->addColumn('university_lecture_requests', $columns);
            }
        }

        if (!$this->db->tableExists('university_lecture_request_applications')) {
            $this->forge->addField([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'university_lecture_request_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                ],
                'university_tutor_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                ],
                'tutor_email' => [
                    'type' => 'VARCHAR',
                    'constraint' => 150,
                ],
                'status' => [
                    'type' => 'VARCHAR',
                    'constraint' => 30,
                    'default' => 'accepted',
                ],
                'accepted_at' => [
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
            $this->forge->addKey('university_lecture_request_id');
            $this->forge->addKey('university_tutor_id');
            $this->forge->addKey('status');
            $this->forge->addUniqueKey(['university_lecture_request_id', 'university_tutor_id']);
            $this->forge->createTable('university_lecture_request_applications', true);
        }
    }

    public function down()
    {
        if ($this->db->tableExists('university_lecture_request_applications')) {
            $this->forge->dropTable('university_lecture_request_applications', true);
        }

        if ($this->db->tableExists('university_lecture_requests')) {
            if ($this->db->fieldExists('emailed_tutor_count', 'university_lecture_requests')) {
                $this->forge->dropColumn('university_lecture_requests', 'emailed_tutor_count');
            }

            if ($this->db->fieldExists('matched_tutor_count', 'university_lecture_requests')) {
                $this->forge->dropColumn('university_lecture_requests', 'matched_tutor_count');
            }
        }
    }
}
