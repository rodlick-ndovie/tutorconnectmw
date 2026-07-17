<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateParentRequestWorkflow extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('parent_requests')) {
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
                'curriculum' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                ],
                'grade_class' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                ],
                'subjects_json' => [
                    'type' => 'TEXT',
                ],
                'district' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                ],
                'specific_location' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                ],
                'mode' => [
                    'type' => 'VARCHAR',
                    'constraint' => 20,
                ],
                'budget_min' => [
                    'type' => 'DECIMAL',
                    'constraint' => '12,2',
                ],
                'budget_max' => [
                    'type' => 'DECIMAL',
                    'constraint' => '12,2',
                ],
                'budget_period' => [
                    'type' => 'VARCHAR',
                    'constraint' => 20,
                ],
                'notes' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'parent_phone' => [
                    'type' => 'VARCHAR',
                    'constraint' => 30,
                ],
                'parent_email' => [
                    'type' => 'VARCHAR',
                    'constraint' => 150,
                ],
                'status' => [
                    'type' => 'VARCHAR',
                    'constraint' => 20,
                    'default' => 'open',
                ],
                'matched_tutor_count' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'default' => 0,
                ],
                'emailed_tutor_count' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'default' => 0,
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
            $this->forge->addKey('curriculum');
            $this->forge->addKey('district');
            $this->forge->addKey('mode');
            $this->forge->addKey('status');
            $this->forge->createTable('parent_requests', true);
        }

        if (!$this->db->tableExists('parent_request_applications')) {
            $this->forge->addField([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'parent_request_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                ],
                'tutor_id' => [
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
                    'constraint' => 20,
                    'default' => 'applied',
                ],
                'applied_at' => [
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
            $this->forge->addUniqueKey(['parent_request_id', 'tutor_id']);
            $this->forge->addKey('tutor_id');
            $this->forge->addKey('status');
            $this->forge->createTable('parent_request_applications', true);
        }
    }

    public function down()
    {
        if ($this->db->tableExists('parent_request_applications')) {
            $this->forge->dropTable('parent_request_applications', true);
        }

        if ($this->db->tableExists('parent_requests')) {
            $this->forge->dropTable('parent_requests', true);
        }
    }
}
