<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBillingMonthsToTutorSubscriptions extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('billing_months', 'tutor_subscriptions')) {
            $this->forge->addColumn('tutor_subscriptions', [
                'billing_months' => [
                    'type' => 'INT',
                    'unsigned' => true,
                    'default' => 1,
                    'after' => 'plan_id',
                ],
            ]);
        }

        if (!$this->db->fieldExists('upgrading_from', 'tutor_subscriptions')) {
            $this->forge->addColumn('tutor_subscriptions', [
                'upgrading_from' => [
                    'type' => 'INT',
                    'unsigned' => true,
                    'null' => true,
                    'after' => 'payment_proof_file',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('billing_months', 'tutor_subscriptions')) {
            $this->forge->dropColumn('tutor_subscriptions', 'billing_months');
        }

        if ($this->db->fieldExists('upgrading_from', 'tutor_subscriptions')) {
            $this->forge->dropColumn('tutor_subscriptions', 'upgrading_from');
        }
    }
}
