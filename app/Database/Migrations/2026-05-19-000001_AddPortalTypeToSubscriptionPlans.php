<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPortalTypeToSubscriptionPlans extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('features', 'subscription_plans')) {
            $this->forge->addColumn('subscription_plans', [
                'features' => [
                    'type' => 'TEXT',
                    'null' => true,
                    'after' => 'price_monthly',
                ],
            ]);
        }

        if (!$this->db->fieldExists('portal_type', 'subscription_plans')) {
            $this->forge->addColumn('subscription_plans', [
                'portal_type' => [
                    'type' => 'VARCHAR',
                    'constraint' => 30,
                    'default' => 'trainer',
                    'after' => 'features',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('portal_type', 'subscription_plans')) {
            $this->forge->dropColumn('subscription_plans', 'portal_type');
        }

        if ($this->db->fieldExists('features', 'subscription_plans')) {
            $this->forge->dropColumn('subscription_plans', 'features');
        }
    }
}
