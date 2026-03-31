<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddProfilePictureColumnToTutorsTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tutors', [
            'profile_picture' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
                'after' => 'bio',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('tutors', 'profile_picture');
    }
}
