<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddApprovedAtToUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'approved_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'deleted_at',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'approved_at');
    }
}
