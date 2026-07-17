<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateServicesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'service_type' => [
                'type' => 'ENUM',
                'constraint' => ['tutoring_package', 'consultation', 'course_material', 'homework_help', 'exam_prep', 'professional_dev', 'other'],
                'default' => 'tutoring_package',
            ],
            'categorization' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'default' => 'General',
            ],
            'base_price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
            ],
            'currency' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'default' => 'MWK',
            ],
            'pricing_type' => [
                'type' => 'ENUM',
                'constraint' => ['fixed', 'hourly', 'package'],
                'default' => 'fixed',
            ],
            'duration_hours' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
            ],
            'max_participants' => [
                'type' => 'INT',
                'default' => 1,
            ],
            'features' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'is_active' => [
                'type' => 'BOOLEAN',
                'default' => true,
            ],
            'sort_order' => [
                'type' => 'INT',
                'default' => 0,
            ],
            'created_by' => [
                'type' => 'INT',
                'unsigned' => true,
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
        $this->forge->addForeignKey('created_by', 'users', 'id', 'SET NULL', 'SET NULL');
        $this->forge->createTable('services');

        // Create service categories lookup table
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
            'parent_category' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
            ],
            'sort_order' => [
                'type' => 'INT',
                'default' => 0,
            ],
            'is_active' => [
                'type' => 'BOOLEAN',
                'default' => true,
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
        $this->forge->addForeignKey('parent_category', 'service_categories', 'id', 'SET NULL', 'SET NULL');
        $this->forge->createTable('service_categories');
    }

    public function down()
    {
        $this->forge->dropTable('service_categories');
        $this->forge->dropTable('services');
    }
}
