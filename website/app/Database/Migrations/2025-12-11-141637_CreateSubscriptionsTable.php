<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSubscriptionsTable extends Migration
{
    public function up()
    {
        // Create subscription plans table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'price_monthly' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
            ],
            'features' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'max_students' => [
                'type' => 'INT',
                'default' => 50,
            ],
            'is_active' => [
                'type' => 'BOOLEAN',
                'default' => true,
            ],
            'sort_order' => [
                'type' => 'INT',
                'default' => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('subscription_plans');

        // Create tutor subscriptions table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'plan_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'inactive', 'cancelled', 'expired', 'pending'],
                'default' => 'active',
            ],
            'current_period_start' => [
                'type' => 'DATETIME',
            ],
            'current_period_end' => [
                'type' => 'DATETIME',
            ],
            'trial_end' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'cancel_at_period_end' => [
                'type' => 'BOOLEAN',
                'default' => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('plan_id', 'subscription_plans', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tutor_subscriptions');
    }

    public function down()
    {
        $this->forge->dropTable('tutor_subscriptions');
        $this->forge->dropTable('subscription_plans');
    }
}
