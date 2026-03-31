<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTutorRegistrationFields extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tutors', [
            'hourly_rate' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
                'after' => 'rate'
            ],
            'national_id_file' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
                'after' => 'training_papers'
            ],
            'academic_certificates_files' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'national_id_file'
            ],
            'police_clearance_file' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
                'after' => 'academic_certificates_files'
            ],
            'reference_files' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'police_clearance_file'
            ],
            'phone_visible' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
                'after' => 'whatsapp_number'
            ],
            'email_visible' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'phone_visible'
            ],
            'preferred_contact_method' => [
                'type' => 'ENUM',
                'constraint' => ['phone', 'whatsapp', 'email'],
                'default' => 'phone',
                'null' => true,
                'after' => 'best_call_time'
            ],
            'registration_completed' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'is_verified'
            ]
        ]);

        // Create tutor_subjects table if it doesn't exist
        if (!$this->db->tableExists('tutor_subjects')) {
            $this->forge->addField([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true
                ],
                'tutor_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true
                ],
                'subject_name' => [
                    'type' => 'VARCHAR',
                    'constraint' => '100'
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true
                ]
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addKey('tutor_id');
            $this->forge->createTable('tutor_subjects');

            // Add foreign key constraint
            $this->db->query('ALTER TABLE tutor_subjects ADD CONSTRAINT tutor_subjects_tutor_id_foreign FOREIGN KEY (tutor_id) REFERENCES tutors(id) ON DELETE CASCADE');
        }

        // Create tutor_levels table if it doesn't exist
        if (!$this->db->tableExists('tutor_levels')) {
            $this->forge->addField([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true
                ],
                'tutor_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true
                ],
                'level_name' => [
                    'type' => 'VARCHAR',
                    'constraint' => '50'
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true
                ]
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addKey('tutor_id');
            $this->forge->createTable('tutor_levels');

            // Add foreign key constraint
            $this->db->query('ALTER TABLE tutor_levels ADD CONSTRAINT tutor_levels_tutor_id_foreign FOREIGN KEY (tutor_id) REFERENCES tutors(id) ON DELETE CASCADE');
        }

        // Create tutor_documents table for storing uploaded file paths
        if (!$this->db->tableExists('tutor_documents')) {
            $this->forge->addField([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true
                ],
                'tutor_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true
                ],
                'document_type' => [
                    'type' => 'ENUM',
                    'constraint' => ['national_id', 'academic_certificate', 'police_clearance', 'reference_letter']
                ],
                'file_path' => [
                    'type' => 'VARCHAR',
                    'constraint' => '255'
                ],
                'original_filename' => [
                    'type' => 'VARCHAR',
                    'constraint' => '255'
                ],
                'uploaded_at' => [
                    'type' => 'DATETIME',
                    'null' => true
                ]
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addKey('tutor_id');
            $this->forge->createTable('tutor_documents');

            // Add foreign key constraint
            $this->db->query('ALTER TABLE tutor_documents ADD CONSTRAINT tutor_documents_tutor_id_foreign FOREIGN KEY (tutor_id) REFERENCES tutors(id) ON DELETE CASCADE');
        }
    }

    public function down()
    {
        // Drop columns from tutors table
        $this->forge->dropColumn('tutors', 'hourly_rate');
        $this->forge->dropColumn('tutors', 'national_id_file');
        $this->forge->dropColumn('tutors', 'academic_certificates_files');
        $this->forge->dropColumn('tutors', 'police_clearance_file');
        $this->forge->dropColumn('tutors', 'reference_files');
        $this->forge->dropColumn('tutors', 'phone_visible');
        $this->forge->dropColumn('tutors', 'email_visible');
        $this->forge->dropColumn('tutors', 'preferred_contact_method');
        $this->forge->dropColumn('tutors', 'registration_completed');

        // Drop tables
        if ($this->db->tableExists('tutor_subjects')) {
            $this->forge->dropTable('tutor_subjects');
        }
        if ($this->db->tableExists('tutor_levels')) {
            $this->forge->dropTable('tutor_levels');
        }
        if ($this->db->tableExists('tutor_documents')) {
            $this->forge->dropTable('tutor_documents');
        }
    }
}
