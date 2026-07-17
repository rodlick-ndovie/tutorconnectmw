<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields    = [
        'username', 'email', 'password', 'role', 'first_name', 'last_name',
        'phone', 'profile_picture', 'bio', 'district', 'location',
        'experience_years', 'teaching_mode', 'cv_file', 'bio_video',
        'whatsapp_number', 'phone_visible', 'email_visible', 'best_call_time',
        'preferred_contact_method', 'is_verified', 'is_active', 'tutor_status',
        'registration_completed', 'is_employed', 'school_name',
        'subscription_plan', 'subscription_expires_at', 'verification_documents',
        'rating', 'review_count', 'search_count', 'featured',
        'curriculum', 'subjects', 'education_levels', 'availability',
        'resubmission_token', 'resubmission_token_expires', 'resubmission_special_docs',
        'cover_photo', 'gender',
        'created_at', 'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
