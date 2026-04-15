<?php

namespace App\Models;

use CodeIgniter\Model;

class ParentRequestModel extends Model
{
    protected $table            = 'parent_requests';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'reference_code',
        'curriculum',
        'grade_class',
        'subjects_json',
        'district',
        'specific_location',
        'mode',
        'budget_min',
        'budget_max',
        'budget_period',
        'notes',
        'parent_phone',
        'parent_email',
        'status',
        'matched_tutor_count',
        'emailed_tutor_count',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function findByReference(string $referenceCode): ?array
    {
        return $this->where('reference_code', $referenceCode)->first();
    }
}
