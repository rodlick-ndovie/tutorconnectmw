<?php

namespace App\Models;

use CodeIgniter\Model;

class ParentRequestApplicationModel extends Model
{
    protected $table            = 'parent_request_applications';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'parent_request_id',
        'tutor_id',
        'tutor_email',
        'status',
        'applied_at',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function findExisting(int $parentRequestId, int $tutorId): ?array
    {
        return $this->where('parent_request_id', $parentRequestId)
            ->where('tutor_id', $tutorId)
            ->first();
    }
}
