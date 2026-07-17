<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTeachingModeToBookingsTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('bookings', [
            'teaching_mode' => [
                'type'       => 'ENUM',
                'constraint' => ['online', 'in-person'],
                'default'    => 'online',
                'comment'    => 'Preferred teaching mode: online or in-person',
                'after'      => 'amount',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('bookings', 'teaching_mode');
    }
}
