<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTutorAdditionalFieldsToUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            // Add location column immediately after district
            'location' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
                'after' => 'district',
            ],
            // Add gender column
            'gender' => [
                'type' => 'ENUM',
                'constraint' => ['Male', 'Female', 'Other', 'Prefer not to say'],
                'null' => true,
                'after' => 'first_name',
            ],
            // Add charge_type column
            'charge_type' => [
                'type' => 'ENUM',
                'constraint' => ['Hourly', 'Daily', 'Session', 'Weekly', 'Monthly', 'Term'],
                'default' => 'Hourly',
                'after' => 'hourly_rate',
            ],
            // Add charge_rate column
            'charge_rate' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
                'after' => 'charge_type',
                'comment' => 'Price based on the selected charge_type',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['location', 'gender', 'charge_type', 'charge_rate']);
    }
}
