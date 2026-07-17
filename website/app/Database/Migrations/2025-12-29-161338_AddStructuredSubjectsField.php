<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStructuredSubjectsField extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'structured_subjects' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'JSON data storing subjects organized by curriculum and level'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'structured_subjects');
    }
}
