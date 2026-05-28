<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUniversityCollegeSupportTables extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('university_college_tutors')) {
            $this->forge->addField([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'user_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'null' => true,
                ],
                'username' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'null' => true,
                ],
                'reference_code' => [
                    'type' => 'VARCHAR',
                    'constraint' => 30,
                ],
                'full_name' => [
                    'type' => 'VARCHAR',
                    'constraint' => 150,
                ],
                'email' => [
                    'type' => 'VARCHAR',
                    'constraint' => 150,
                ],
                'phone' => [
                    'type' => 'VARCHAR',
                    'constraint' => 30,
                ],
                'profile_picture' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                ],
                'national_id_file' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                ],
                'certification_files_json' => [
                    'type' => 'TEXT',
                ],
                'institutions_json' => [
                    'type' => 'TEXT',
                ],
                'specializations_json' => [
                    'type' => 'TEXT',
                ],
                'service_areas_json' => [
                    'type' => 'TEXT',
                ],
                'year_of_study_or_graduation' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                ],
                'bio' => [
                    'type' => 'TEXT',
                ],
                'references_json' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'work_status' => [
                    'type' => 'VARCHAR',
                    'constraint' => 30,
                    'null' => true,
                ],
                'employer_name' => [
                    'type' => 'VARCHAR',
                    'constraint' => 150,
                    'null' => true,
                ],
                'employer_contact' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'null' => true,
                ],
                'available_days_json' => [
                    'type' => 'TEXT',
                ],
                'preferred_times_json' => [
                    'type' => 'TEXT',
                ],
                'teaching_mode' => [
                    'type' => 'VARCHAR',
                    'constraint' => 20,
                ],
                'city_location' => [
                    'type' => 'VARCHAR',
                    'constraint' => 150,
                ],
                'hourly_rate' => [
                    'type' => 'DECIMAL',
                    'constraint' => '12,2',
                    'null' => true,
                ],
                'consultation_package_rate' => [
                    'type' => 'DECIMAL',
                    'constraint' => '12,2',
                    'null' => true,
                ],
                'dissertation_package_rate' => [
                    'type' => 'DECIMAL',
                    'constraint' => '12,2',
                    'null' => true,
                ],
                'exam_preparation_rate' => [
                    'type' => 'DECIMAL',
                    'constraint' => '12,2',
                    'null' => true,
                ],
                'subscription_plan' => [
                    'type' => 'VARCHAR',
                    'constraint' => 30,
                    'default' => 'Basic',
                ],
                'status' => [
                    'type' => 'VARCHAR',
                    'constraint' => 30,
                    'default' => 'pending_review',
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
            $this->forge->addKey('user_id');
            $this->forge->addKey('username');
            $this->forge->addUniqueKey('reference_code');
            $this->forge->addKey('email');
            $this->forge->addKey('phone');
            $this->forge->addKey('teaching_mode');
            $this->forge->addKey('status');
            $this->forge->createTable('university_college_tutors', true);
        }

        if (!$this->db->tableExists('university_lecture_requests')) {
            $this->forge->addField([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'reference_code' => [
                    'type' => 'VARCHAR',
                    'constraint' => 30,
                ],
                'full_name' => [
                    'type' => 'VARCHAR',
                    'constraint' => 150,
                ],
                'email' => [
                    'type' => 'VARCHAR',
                    'constraint' => 150,
                ],
                'phone' => [
                    'type' => 'VARCHAR',
                    'constraint' => 30,
                ],
                'institution' => [
                    'type' => 'VARCHAR',
                    'constraint' => 150,
                ],
                'service_category' => [
                    'type' => 'VARCHAR',
                    'constraint' => 80,
                ],
                'topic' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                ],
                'delivery_mode' => [
                    'type' => 'VARCHAR',
                    'constraint' => 20,
                ],
                'city_location' => [
                    'type' => 'VARCHAR',
                    'constraint' => 150,
                ],
                'preferred_date' => [
                    'type' => 'DATE',
                    'null' => true,
                ],
                'preferred_time' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'null' => true,
                ],
                'budget_range' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'null' => true,
                ],
                'notes' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'status' => [
                    'type' => 'VARCHAR',
                    'constraint' => 30,
                    'default' => 'open',
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
            $this->forge->addUniqueKey('reference_code');
            $this->forge->addKey('service_category');
            $this->forge->addKey('delivery_mode');
            $this->forge->addKey('status');
            $this->forge->createTable('university_lecture_requests', true);
        }
    }

    public function down()
    {
        if ($this->db->tableExists('university_lecture_requests')) {
            $this->forge->dropTable('university_lecture_requests', true);
        }

        if ($this->db->tableExists('university_college_tutors')) {
            $this->forge->dropTable('university_college_tutors', true);
        }
    }
}
