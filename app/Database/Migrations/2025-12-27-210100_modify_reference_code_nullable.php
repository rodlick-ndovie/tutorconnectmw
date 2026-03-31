<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyReferenceCodeNullable extends Migration
{
    public function up()
    {
        // Modify the reference_code column to allow NULL values
        $this->db->query('ALTER TABLE bookings MODIFY COLUMN reference_code VARCHAR(20) NULL');
    }

    public function down()
    {
        // Revert back to NOT NULL
        $this->db->query('ALTER TABLE bookings MODIFY COLUMN reference_code VARCHAR(20) NOT NULL');
    }
}
