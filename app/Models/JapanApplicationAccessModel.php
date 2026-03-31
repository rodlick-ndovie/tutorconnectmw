<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class JapanApplicationAccessModel extends Model
{
    protected $table = 'japan_application_access';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'tx_ref',
        'full_name',
        'email',
        'phone',
        'amount',
        'currency',
        'payment_method',
        'payment_status',
        'access_status',
        'access_token',
        'paid_at',
        'last_accessed_at',
        'application_id',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $dateFormat = 'datetime';

    public function ensureTable(): void
    {
        if ($this->db->tableExists($this->table)) {
            return;
        }

        $forge = Database::forge();
        $forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'tx_ref' => [
                'type' => 'VARCHAR',
                'constraint' => 80,
            ],
            'full_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
                'null' => true,
            ],
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => '10000.00',
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
            'access_status' => [
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
            'last_accessed_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'application_id' => [
                'type' => 'INT',
                'constraint' => 11,
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

        $forge->addKey('id', true);
        $forge->addUniqueKey('tx_ref');
        $forge->addUniqueKey('access_token');
        $forge->addKey('email');
        $forge->addKey('payment_status');
        $forge->addKey('access_status');
        $forge->createTable($this->table, true);
    }

    public function findActiveByToken(string $token): ?array
    {
        return $this->where('access_token', $token)
            ->where('payment_status', 'verified')
            ->whereIn('access_status', ['active', 'submitted'])
            ->first();
    }

    public function findLatestVerifiedByEmail(string $email): ?array
    {
        return $this->where('email', $email)
            ->where('payment_status', 'verified')
            ->whereIn('access_status', ['active', 'submitted'])
            ->orderBy('paid_at', 'DESC')
            ->first();
    }
}
