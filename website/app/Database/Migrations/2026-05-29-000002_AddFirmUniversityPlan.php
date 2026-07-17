<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFirmUniversityPlan extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('subscription_plans') || !$this->db->fieldExists('portal_type', 'subscription_plans')) {
            return;
        }

        $now = date('Y-m-d H:i:s');
        $plan = [
            'name' => 'Firm',
            'description' => 'Firm-only university support subscription',
            'price_monthly' => 15000.00,
            'features' => json_encode([
                'Approved firm listing in the university portal',
                'Company logo display',
                'Business certificate verification',
                'Priority eligibility for institutional support requests',
                'Firm profile placement in university support listings',
            ]),
            'portal_type' => 'university',
            'is_active' => 1,
            'sort_order' => 4,
        ];

        $existing = $this->db->table('subscription_plans')
            ->where('portal_type', 'university')
            ->where('name', 'Firm')
            ->get()
            ->getRowArray();

        if ($existing) {
            $this->db->table('subscription_plans')
                ->where('id', (int) $existing['id'])
                ->update(array_merge($plan, ['updated_at' => $now]));
            return;
        }

        $this->db->table('subscription_plans')->insert(array_merge($plan, [
            'created_at' => $now,
            'updated_at' => $now,
        ]));
    }

    public function down()
    {
        if (!$this->db->tableExists('subscription_plans') || !$this->db->fieldExists('portal_type', 'subscription_plans')) {
            return;
        }

        $this->db->table('subscription_plans')
            ->where('portal_type', 'university')
            ->where('name', 'Firm')
            ->delete();
    }
}
