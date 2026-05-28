<?php

namespace App\Models;

use CodeIgniter\Model;

class UniversityLectureRequestModel extends Model
{
    protected $table            = 'university_lecture_requests';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'reference_code',
        'full_name',
        'email',
        'phone',
        'institution',
        'service_category',
        'topic',
        'delivery_mode',
        'city_location',
        'preferred_date',
        'preferred_time',
        'budget_range',
        'notes',
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
