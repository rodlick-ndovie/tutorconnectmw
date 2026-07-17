<?php

namespace App\Models;

use CodeIgniter\Model;

class User extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        // Basic user fields
        'username',
        'email',
        'password',
        'password_hash',
        'role',
        'first_name',
        'last_name',
        'phone',
        'email_verified_at',
        'otp_code',
        'otp_expires_at',
        'reset_token',
        'reset_expires_at',
        'is_active',
        'profile_picture',
        'cover_photo',
        'approved_at',
        'gender',

        // Tutor-specific fields (same as regular registration)
        'district',
        'location',
        'bio_video',
        'charge_type',
        'charge_rate',
        'experience_years',
        'rate',
        'hourly_rate',
        'rate_type',
        'teaching_mode',
        'bio',
        'whatsapp_number',
        'phone_visible',
        'email_visible',
        'preferred_contact_method',
        'best_call_time',
        'is_verified',
        'registration_completed',
        'is_employed',
        'school_name',
        'subscription_plan',
        'subscription_expires_at',
        'rating',
        'review_count',
        'search_count',
        'featured',
        'tutor_status',
        'verification_documents',
        'subjects',
        'education_levels',
        'curriculum',
        'availability',
        'structured_subjects',
        'terms_accepted',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'username' => 'required|is_unique[users.username]|alpha_dash|min_length[3]|max_length[100]',
        'password' => 'required|min_length[8]|max_length[255]',
        'role'     => 'required|in_list[admin,sub-admin,trainer,customer]',
        'first_name' => 'required|max_length[50]',
        'last_name'  => 'required|max_length[50]',
        'phone'      => 'required|min_length[10]|max_length[20]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['hashPassword'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['hashPassword'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    // Method to hash password before insert/update
    protected function hashPassword(array $data)
    {
        log_message('info', 'Callback data structure: ' . print_r($data, true));

        // Try different possible locations
        $passwordFound = false;
        $passwordValue = null;

        // Check if it's wrapped in 'data' key
        if (isset($data['data']['password'])) {
            $passwordFound = true;
            $passwordValue = $data['data']['password'];
            log_message('info', 'Found password in data[data][password]');
        }
        // Check if it's directly in data
        elseif (isset($data['password'])) {
            $passwordFound = true;
            $passwordValue = $data['password'];
            log_message('info', 'Found password in data[password]');
        }
        // Check other possible locations
        else {
            // Password not found - this is normal for updates that don't change password
            // Only log as info, not error, since this is expected behavior
            if (isset($data['id']) || (isset($data['data']['id']) || (isset($data['data']) && isset($data['data'][0])))) {
                // This is an update operation, password not required
                log_message('debug', 'Update operation - password not provided (this is normal)');
            } else {
                // This might be an insert without password - log as warning
                log_message('warning', 'Password not found in insert data. Available keys: ' . implode(', ', array_keys($data)));
            }
        }

        if ($passwordFound && !empty($passwordValue)) {
            $hashed = password_hash($passwordValue, PASSWORD_ARGON2ID);
            log_message('info', 'Hashing password: ' . substr($passwordValue, 0, 3) . '*** to: ' . $hashed);

            // Remove password and set password_hash
            if (isset($data['data']['password'])) {
                unset($data['data']['password']);
                $data['data']['password_hash'] = $hashed;
            } elseif (isset($data['password'])) {
                unset($data['password']);
                $data['password_hash'] = $hashed;
            }

            log_message('info', 'Password hashed successfully, set password_hash');
        } elseif ($passwordFound) {
            log_message('error', 'Password field exists but is empty');
        }

        return $data;
    }

    // Verify password
    public function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

    // Get user by email or username
    public function getByEmailOrUsername($login)
    {
        return $this->where('email', $login)->orWhere('username', $login)->first();
    }

    // Check if user is active
    public function isActive($userId)
    {
        $user = $this->find($userId);
        return $user && $user['is_active'] == 1;
    }
}
