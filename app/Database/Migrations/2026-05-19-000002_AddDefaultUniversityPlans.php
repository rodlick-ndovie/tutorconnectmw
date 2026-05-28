<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDefaultUniversityPlans extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('subscription_plans')) {
            return;
        }

        if (!$this->db->fieldExists('portal_type', 'subscription_plans')) {
            return;
        }

        $now = date('Y-m-d H:i:s');

        $plans = [
            [
                'name' => 'Basic',
                'description' => 'New tutors joining the platform',
                'price_monthly' => 2000.00,
                'features' => json_encode([
                    'Profile listing in university portal',
                    'Core visibility for new tutors',
                    'Access to subscription management',
                ]),
                'portal_type' => 'university',
                'is_active' => 1,
                'sort_order' => 1,
            ],
            [
                'name' => 'Standard',
                'description' => 'Active tutors seeking more visibility',
                'price_monthly' => 5000.00,
                'features' => json_encode([
                    'Enhanced visibility in university portal',
                    'Improved placement over Basic',
                    'Higher exposure to potential clients',
                ]),
                'portal_type' => 'university',
                'is_active' => 1,
                'sort_order' => 2,
            ],
            [
                'name' => 'Premium',
                'description' => 'Highly active tutors requiring priority placement and enhanced exposure',
                'price_monthly' => 10000.00,
                'features' => json_encode([
                    'Priority placement for qualified tutors',
                    'Maximum exposure in university portal',
                    'Best rank among university plans',
                ]),
                'portal_type' => 'university',
                'is_active' => 1,
                'sort_order' => 3,
            ],
        ];

        foreach ($plans as $plan) {
            $existing = $this->db->table('subscription_plans')
                ->where('portal_type', 'university')
                ->where('name', $plan['name'])
                ->get()
                ->getRowArray();

            if ($existing) {
                $this->db->table('subscription_plans')
                    ->where('id', (int) $existing['id'])
                    ->update(array_merge($plan, ['updated_at' => $now]));
            } else {
                $this->db->table('subscription_plans')
                    ->insert(array_merge($plan, [
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]));
            }
        }
    }

    public function down()
    {
        if (!$this->db->tableExists('subscription_plans')) {
            return;
        }

        if (!$this->db->fieldExists('portal_type', 'subscription_plans')) {
            return;
        }

        $this->db->table('subscription_plans')
            ->where('portal_type', 'university')
            ->whereIn('name', ['Basic', 'Standard', 'Premium'])
            ->delete();
    }
}
