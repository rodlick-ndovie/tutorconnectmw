<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDurationToBookingsTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('bookings', [
            'duration' => [
                'type'       => 'DECIMAL',
                'constraint' => '4,2',
                'default'    => 1.00,
                'comment'    => 'Session duration in hours',
                'after'      => 'booking_time',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('bookings', 'duration');
    }
}
