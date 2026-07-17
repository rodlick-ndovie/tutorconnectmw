<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAccountTypeToUniversityCollegeTutors extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('university_college_tutors') && !$this->db->fieldExists('account_type', 'university_college_tutors')) {
            $this->forge->addColumn('university_college_tutors', [
                'account_type' => [
                    'type' => 'VARCHAR',
                    'constraint' => 20,
                    'default' => 'individual',
                    'after' => 'user_id',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->tableExists('university_college_tutors') && $this->db->fieldExists('account_type', 'university_college_tutors')) {
            $this->forge->dropColumn('university_college_tutors', 'account_type');
        }
    }
}
