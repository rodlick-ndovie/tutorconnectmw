<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCurriculumToUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'curriculum' => [
                'type'       => 'JSON',
                'null'       => true,
                'comment'    => 'Curriculum types the tutor teaches (MANEB, GCSE, Cambridge, etc.)',
                'after'      => 'education_levels',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'curriculum');
    }
}
