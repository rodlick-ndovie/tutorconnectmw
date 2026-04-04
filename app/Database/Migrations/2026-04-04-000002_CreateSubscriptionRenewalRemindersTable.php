<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSubscriptionRenewalRemindersTable extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('subscription_renewal_reminders')) {
            return;
        }

        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'subscription_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'reminder_type' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'target_period_end' => [
                'type' => 'DATETIME',
            ],
            'recipient_email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['sent', 'failed', 'skipped'],
                'default' => 'sent',
            ],
            'error_message' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'sent_at' => [
                'type' => 'DATETIME',
                'null' => true,
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
        $this->forge->addKey('subscription_id');
        $this->forge->addKey('user_id');
        $this->forge->addKey(['reminder_type', 'target_period_end']);
        $this->forge->addUniqueKey(['subscription_id', 'reminder_type', 'target_period_end'], 'uniq_subscription_reminder_window');
        $this->forge->addForeignKey('subscription_id', 'tutor_subscriptions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('subscription_renewal_reminders');
    }

    public function down()
    {
        $this->forge->dropTable('subscription_renewal_reminders', true);
    }
}
