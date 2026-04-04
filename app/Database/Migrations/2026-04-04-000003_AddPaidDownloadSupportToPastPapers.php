<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPaidDownloadSupportToPastPapers extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('past_papers')) {
            $fields = $this->db->getFieldNames('past_papers');

            if (!in_array('is_paid', $fields, true)) {
                $this->forge->addColumn('past_papers', [
                    'is_paid' => [
                        'type' => 'TINYINT',
                        'constraint' => 1,
                        'default' => 0,
                        'after' => 'is_active',
                    ],
                ]);
            }

            if (!in_array('price', $fields, true)) {
                $this->forge->addColumn('past_papers', [
                    'price' => [
                        'type' => 'DECIMAL',
                        'constraint' => '10,2',
                        'default' => '0.00',
                        'after' => 'is_paid',
                    ],
                ]);
            }
        }

        if (!$this->db->tableExists('past_paper_purchases')) {
            $this->forge->addField([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'past_paper_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                ],
                'user_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'null' => true,
                ],
                'tx_ref' => [
                    'type' => 'VARCHAR',
                    'constraint' => 80,
                ],
                'buyer_name' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                ],
                'buyer_email' => [
                    'type' => 'VARCHAR',
                    'constraint' => 150,
                ],
                'buyer_phone' => [
                    'type' => 'VARCHAR',
                    'constraint' => 30,
                    'null' => true,
                ],
                'amount' => [
                    'type' => 'DECIMAL',
                    'constraint' => '10,2',
                    'default' => '0.00',
                ],
                'currency' => [
                    'type' => 'VARCHAR',
                    'constraint' => 10,
                    'default' => 'MWK',
                ],
                'payment_method' => [
                    'type' => 'VARCHAR',
                    'constraint' => 30,
                    'default' => 'paychangu',
                ],
                'payment_status' => [
                    'type' => 'VARCHAR',
                    'constraint' => 30,
                    'default' => 'pending',
                ],
                'access_token' => [
                    'type' => 'VARCHAR',
                    'constraint' => 80,
                ],
                'paid_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'download_granted_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'last_downloaded_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'download_count' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
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
            $this->forge->addUniqueKey('tx_ref');
            $this->forge->addUniqueKey('access_token');
            $this->forge->addKey('past_paper_id');
            $this->forge->addKey('user_id');
            $this->forge->addKey('buyer_email');
            $this->forge->addKey('payment_status');
            $this->forge->createTable('past_paper_purchases', true);
        }
    }

    public function down()
    {
        if ($this->db->tableExists('past_paper_purchases')) {
            $this->forge->dropTable('past_paper_purchases', true);
        }

        if ($this->db->tableExists('past_papers')) {
            $fields = $this->db->getFieldNames('past_papers');

            if (in_array('price', $fields, true)) {
                $this->forge->dropColumn('past_papers', 'price');
            }

            if (in_array('is_paid', $fields, true)) {
                $this->forge->dropColumn('past_papers', 'is_paid');
            }
        }
    }
}
