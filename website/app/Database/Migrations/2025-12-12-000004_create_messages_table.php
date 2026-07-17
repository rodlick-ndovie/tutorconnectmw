<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMessagesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'sender_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'receiver_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'booking_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'subject' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'message' => [
                'type' => 'TEXT',
            ],
            'message_type' => [
                'type' => 'ENUM',
                'constraint' => ['inquiry', 'booking_request', 'confirmation', 'reschedule', 'cancellation', 'feedback', 'general'],
                'default' => 'general',
            ],
            'is_read' => [
                'type' => 'BOOLEAN',
                'default' => false,
            ],
            'is_system_message' => [
                'type' => 'BOOLEAN',
                'default' => false,
            ],
            'parent_message_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'sent_at' => [
                'type' => 'DATETIME',
            ],
            'read_at' => [
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

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('sender_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('receiver_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('booking_id', 'bookings', 'id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('parent_message_id', 'messages', 'id', 'CASCADE', 'SET NULL');

        // Indexes for better performance
        $this->forge->addKey(['sender_id', 'receiver_id']);
        $this->forge->addKey(['booking_id']);
        $this->forge->addKey(['is_read']);
        $this->forge->addKey(['sent_at']);

        if (!$this->db->tableExists('messages')) {
            $this->forge->createTable('messages');
        }
    }

    public function down()
    {
        $this->forge->dropTable('messages');
    }
}
