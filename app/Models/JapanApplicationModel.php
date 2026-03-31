<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class JapanApplicationModel extends Model
{
    protected $table = 'japan_teaching_applications';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'application_reference',
        'full_name',
        'email',
        'phone',
        'nationality',
        'gender',
        'date_of_birth',
        'age',
        'current_address',
        'has_valid_passport',
        'passport_number',
        'passport_expiry_date',
        'willing_to_renew_passport',
        'highest_qualification',
        'degree_obtained',
        'field_of_study',
        'institution_name',
        'year_of_completion',
        'has_teaching_certificate',
        'teaching_certificate_details',
        'has_teaching_experience',
        'teaching_experience_location',
        'subjects_taught',
        'level_taught',
        'teaching_experience_duration',
        'documents_already_shared',
        'shared_documents_note',
        'financial_readiness_json',
        'referees_json',
        'declarations_json',
        'degree_document_path',
        'transcript_document_path',
        'passport_copy_path',
        'teaching_certificate_path',
        'cv_document_path',
        'intro_video_path',
        'application_fee_amount',
        'processing_fee_amount',
        'status',
        'admin_notes',
        'submitted_at',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $dateFormat = 'datetime';

    public function ensureTable(): void
    {
        if ($this->db->tableExists($this->table)) {
            $forge = Database::forge();
            $columnsToAdd = [];

            if (!$this->db->fieldExists('cv_document_path', $this->table)) {
                $columnsToAdd['cv_document_path'] = [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => true,
                    'after' => 'teaching_certificate_path',
                ];
            }

            if ($columnsToAdd !== []) {
                $forge->addColumn($this->table, $columnsToAdd);
            }

            return;
        }

        $forge = Database::forge();

        $forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'application_reference' => [
                'type' => 'VARCHAR',
                'constraint' => 40,
            ],
            'full_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
                'null' => true,
            ],
            'nationality' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'gender' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'date_of_birth' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'age' => [
                'type' => 'INT',
                'constraint' => 3,
                'null' => true,
            ],
            'current_address' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'has_valid_passport' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'passport_number' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'passport_expiry_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'willing_to_renew_passport' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'highest_qualification' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => true,
            ],
            'degree_obtained' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => true,
            ],
            'field_of_study' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => true,
            ],
            'institution_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'year_of_completion' => [
                'type' => 'INT',
                'constraint' => 4,
                'null' => true,
            ],
            'has_teaching_certificate' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'teaching_certificate_details' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'has_teaching_experience' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'teaching_experience_location' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'subjects_taught' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'level_taught' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'teaching_experience_duration' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'documents_already_shared' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'shared_documents_note' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'financial_readiness_json' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'referees_json' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'declarations_json' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'degree_document_path' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'transcript_document_path' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'passport_copy_path' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'teaching_certificate_path' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'cv_document_path' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'intro_video_path' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'application_fee_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => '10000.00',
            ],
            'processing_fee_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => '350000.00',
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
                'default' => 'submitted',
            ],
            'admin_notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'submitted_at' => [
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

        $forge->addKey('id', true);
        $forge->addUniqueKey('application_reference');
        $forge->addKey('email');
        $forge->addKey('status');
        $forge->addKey('submitted_at');
        $forge->createTable($this->table, true);
    }

    public function getStatusCounts(): array
    {
        return [
            'total' => $this->countAll(),
            'submitted' => $this->where('status', 'submitted')->countAllResults(),
            'under_review' => $this->where('status', 'under_review')->countAllResults(),
            'approved' => $this->where('status', 'approved')->countAllResults(),
            'rejected' => $this->where('status', 'rejected')->countAllResults(),
        ];
    }
}
