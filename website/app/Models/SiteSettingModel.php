<?php

namespace App\Models;

use CodeIgniter\Model;

class SiteSettingModel extends Model
{
    protected $table            = 'site_settings';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'setting_key',
        'setting_value',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getValue(string $key, ?string $default = null): ?string
    {
        $row = $this->where('setting_key', $key)->first();
        if (!$row) {
            return $default;
        }

        $value = $row['setting_value'];
        return $value === null ? $default : (string) $value;
    }

    public function setValue(string $key, ?string $value): bool
    {
        $existing = $this->where('setting_key', $key)->first();

        if ($existing) {
            return (bool) $this->update($existing['id'], ['setting_value' => $value]);
        }

        return (bool) $this->insert([
            'setting_key'   => $key,
            'setting_value' => $value,
        ]);
    }
}
