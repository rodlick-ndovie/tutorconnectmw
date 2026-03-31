<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPaymentFieldsToTutorSubscriptionsTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tutor_subscriptions', [
            'payment_method' => [
                'type' => 'ENUM',
                'constraint' => ['bank_transfer', 'mobile_money', 'card_payment', 'cash'],
                'null' => true,
                'after' => 'cancel_at_period_end'
            ],
            'payment_reference' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'payment_method'
            ],
            'payment_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
                'after' => 'payment_reference'
            ],
            'payment_date' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'payment_amount'
            ],
            'payment_status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'verified', 'rejected'],
                'default' => 'pending',
                'after' => 'payment_date'
            ],
            'payment_proof_file' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'payment_status'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('tutor_subscriptions', [
            'payment_method',
            'payment_reference',
            'payment_amount',
            'payment_date',
            'payment_status',
            'payment_proof_file'
        ]);
    }
}
