<?php

namespace App\Models;

use CodeIgniter\Model;

class UniversityCollegeTutorModel extends Model
{
    protected $table            = 'university_college_tutors';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'user_id',
        'account_type',
        'username',
        'reference_code',
        'full_name',
        'email',
        'phone',
        'profile_picture',
        'national_id_file',
        'certification_files_json',
        'institutions_json',
        'specializations_json',
        'service_areas_json',
        'year_of_study_or_graduation',
        'bio',
        'references_json',
        'work_status',
        'employer_name',
        'employer_contact',
        'available_days_json',
        'preferred_times_json',
        'teaching_mode',
        'city_location',
        'hourly_rate',
        'consultation_package_rate',
        'dissertation_package_rate',
        'exam_preparation_rate',
        'subscription_plan',
        'status',
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

    public function findLinkedProfile(?int $userId = null, ?string $email = null, ?string $username = null): ?array
    {
        $builder = $this->groupStart();

        $hasCondition = false;

        if (!empty($userId)) {
            $builder->orWhere('user_id', $userId);
            $hasCondition = true;
        }

        if (!empty($email)) {
            $builder->orWhere('email', strtolower(trim($email)));
            $hasCondition = true;
        }

        if (!empty($username)) {
            $builder->orWhere('username', trim($username));
            $hasCondition = true;
        }

        $builder->groupEnd();

        if (!$hasCondition) {
            return null;
        }

        return $builder->orderBy('id', 'DESC')->first();
    }

    public function decodeJsonList($value): array
    {
        if (empty($value)) {
            return [];
        }

        $decoded = json_decode((string) $value, true);

        if (!is_array($decoded)) {
            return [];
        }

        return array_values(array_filter(array_map(static fn ($item) => trim((string) $item), $decoded), static fn ($item) => $item !== ''));
    }

    public function getProfileCompletionGaps(array $profile): array
    {
        $gaps = [];

        if (trim((string) ($profile['full_name'] ?? '')) === '') {
            $gaps[] = 'Full name';
        }

        if (trim((string) ($profile['email'] ?? '')) === '') {
            $gaps[] = 'Email address';
        }

        if (trim((string) ($profile['phone'] ?? '')) === '') {
            $gaps[] = 'Phone number';
        }

        $isFirm = ($profile['account_type'] ?? 'individual') === 'firm';

        if (trim((string) ($profile['profile_picture'] ?? '')) === '') {
            $gaps[] = $isFirm ? 'Company logo' : 'Profile picture';
        }

        if (trim((string) ($profile['national_id_file'] ?? '')) === '') {
            $gaps[] = $isFirm ? 'Business registration certificate' : 'National ID';
        }

        if ($this->decodeJsonList($profile['certification_files_json'] ?? null) === []) {
            $gaps[] = $isFirm ? 'Supporting business or professional certificate' : 'Academic certifications or transcript';
        }

        if ($this->decodeJsonList($profile['institutions_json'] ?? null) === []) {
            $gaps[] = $isFirm ? 'Institutional or business background' : 'Institutions';
        }

        if ($this->decodeJsonList($profile['service_areas_json'] ?? null) === []) {
            $gaps[] = 'Service areas';
        }

        if ($this->decodeJsonList($profile['available_days_json'] ?? null) === []) {
            $gaps[] = 'Available days';
        }

        if ($this->decodeJsonList($profile['preferred_times_json'] ?? null) === []) {
            $gaps[] = 'Preferred teaching times';
        }

        $bio = trim((string) ($profile['bio'] ?? ''));
        if ($bio === '' || mb_strlen($bio) < 40) {
            $gaps[] = 'Profile bio';
        }

        if (trim((string) ($profile['year_of_study_or_graduation'] ?? '')) === '') {
            $gaps[] = $isFirm ? 'Year established or registration year' : 'Year of study or graduation';
        }

        if (trim((string) ($profile['teaching_mode'] ?? '')) === '') {
            $gaps[] = 'Teaching mode';
        }

        if (trim((string) ($profile['city_location'] ?? '')) === '') {
            $gaps[] = 'City / location';
        }

        if (($profile['work_status'] ?? '') === 'Employed' && trim((string) ($profile['employer_name'] ?? '')) === '') {
            $gaps[] = 'Employer name';
        }

        if (($profile['work_status'] ?? '') === 'Employed' && trim((string) ($profile['employer_contact'] ?? '')) === '') {
            $gaps[] = 'Employer contact';
        }

        $references = $this->decodeJsonList($profile['references_json'] ?? null);
        if ($references !== [] && count($references) < 3) {
            $gaps[] = 'At least three references when references are included';
        }

        return array_values(array_unique($gaps));
    }

    public function isProfileReadyForReview(array $profile): bool
    {
        return $this->getProfileCompletionGaps($profile) === [];
    }
}
