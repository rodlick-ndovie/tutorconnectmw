<?php

namespace App\Models;

use CodeIgniter\Model;

class UniversityLectureRequestApplicationModel extends Model
{
    protected $table            = 'university_lecture_request_applications';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'university_lecture_request_id',
        'university_tutor_id',
        'tutor_email',
        'status',
        'accepted_at',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function findExisting(int $requestId, int $tutorId): ?array
    {
        return $this->where('university_lecture_request_id', $requestId)
            ->where('university_tutor_id', $tutorId)
            ->first();
    }
}
