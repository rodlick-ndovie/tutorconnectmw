<?php

namespace App\Controllers;

class DatabaseInspector extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // Get all tables in the database
        $tables = $this->db->listTables();

        $data = [
            'title' => 'Database Inspector - TutorConnect Malawi',
            'database' => $this->db->database,
            'tables' => [],
            'connection_status' => 'Connected'
        ];

        // Get details for each table
        foreach ($tables as $table) {
            $fields = $this->db->getFieldData($table);
            $recordCount = $this->db->table($table)->countAllResults();

            $data['tables'][$table] = [
                'name' => $table,
                'record_count' => $recordCount,
                'fields' => $fields
            ];
        }

        return view('database/inspector', $data);
    }

    public function showTable($tableName)
    {
        if (!$this->db->tableExists($tableName)) {
            return redirect()->to('/database-inspector')->with('error', 'Table not found');
        }

        $data = [
            'title' => "Table: {$tableName}",
            'table_name' => $tableName,
            'fields' => $this->db->getFieldData($tableName),
            'records' => $this->db->table($tableName)->limit(100)->get()->getResultArray(),
            'total_records' => $this->db->table($tableName)->countAllResults()
        ];

        return view('database/table_view', $data);
    }

    public function checkConnection()
    {
        try {
            $this->db->initialize();

            $info = [
                'status' => 'success',
                'database' => $this->db->database,
                'host' => $this->db->hostname,
                'driver' => $this->db->DBDriver,
                'tables' => $this->db->listTables(),
                'table_count' => count($this->db->listTables())
            ];

            return $this->response->setJSON($info);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
