<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddReferenceCodeToBookingsTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('bookings', [
            'reference_code' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'unique' => true,
                'null' => true, // Allow NULL initially
                'after' => 'id'
            ]
        ]);

        // Create index for faster lookups
        $this->db->query('CREATE INDEX idx_bookings_reference_code ON bookings(reference_code)');
    }

    public function down()
    {
        $this->forge->dropColumn('bookings', 'reference_code');
    }
}
