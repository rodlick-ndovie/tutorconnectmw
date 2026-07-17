<?php

namespace App\Models;

use CodeIgniter\Model;

class CurriculumSubjectsModel extends Model
{
    protected $table            = 'curriculum_subjects';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['curriculum', 'level_name', 'subject_name', 'subject_category', 'is_active'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'curriculum' => 'required|in_list[MANEB,GCSE,Cambridge]',
        'level_name' => 'required|max_length[100]',
        'subject_name' => 'required|max_length[100]',
        'subject_category' => 'permit_empty|max_length[50]',
        'is_active' => 'permit_empty|in_list[0,1]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Get all unique curricula
     */
    public function getCurricula()
    {
        return $this->distinct()
                    ->select('curriculum')
                    ->where('is_active', 1)
                    ->findAll();
    }

    /**
     * Get levels for a specific curriculum
     */
    public function getLevelsByCurriculum($curriculum)
    {
        return $this->distinct()
                    ->select('level_name')
                    ->where('curriculum', $curriculum)
                    ->where('is_active', 1)
                    ->findAll();
    }

    /**
     * Get subjects for a specific curriculum and level
     */
    public function getSubjectsByCurriculumAndLevel($curriculum, $levelName)
    {
        return $this->where('curriculum', $curriculum)
                    ->where('level_name', $levelName)
                    ->where('is_active', 1)
                    ->findAll();
    }

    /**
     * Get all subjects grouped by curriculum and level (with full objects for trainer/subjects)
     */
    public function getSubjectsGrouped()
    {
        $subjects = $this->where('is_active', 1)
                         ->orderBy('curriculum', 'ASC')
                         ->orderBy('level_name', 'ASC')
                         ->orderBy('subject_name', 'ASC')
                         ->findAll();

        $grouped = [];
        foreach ($subjects as $subject) {
            $curriculum = $subject['curriculum'];
            $level = $subject['level_name'];

            if (!isset($grouped[$curriculum])) {
                $grouped[$curriculum] = [];
            }

            if (!isset($grouped[$curriculum][$level])) {
                $grouped[$curriculum][$level] = [];
            }

            $grouped[$curriculum][$level][] = $subject;
        }

        return $grouped;
    }

    /**
     * Get subject names grouped by curriculum and level (for upload forms)
     */
    public function getSubjectNamesGrouped()
    {
        $subjects = $this->where('is_active', 1)
                         ->orderBy('curriculum', 'ASC')
                         ->orderBy('level_name', 'ASC')
                         ->orderBy('subject_name', 'ASC')
                         ->findAll();

        $grouped = [];
        foreach ($subjects as $subject) {
            $curriculum = $subject['curriculum'];
            $level = $subject['level_name'];

            if (!isset($grouped[$curriculum])) {
                $grouped[$curriculum] = [];
            }

            if (!isset($grouped[$curriculum][$level])) {
                $grouped[$curriculum][$level] = [];
            }

            // Store just the subject name as string, not the full object
            $grouped[$curriculum][$level][] = $subject['subject_name'];
        }

        return $grouped;
    }

    /**
     * Get subjects by category
     */
    public function getSubjectsByCategory($category = null)
    {
        $builder = $this->where('is_active', 1);

        if ($category) {
            $builder->where('subject_category', $category);
        }

        return $builder->orderBy('subject_name', 'ASC')->findAll();
    }

    /**
     * Search subjects by name
     */
    public function searchSubjects($searchTerm)
    {
        return $this->like('subject_name', $searchTerm)
                    ->orLike('level_name', $searchTerm)
                    ->where('is_active', 1)
                    ->orderBy('subject_name', 'ASC')
                    ->findAll();
    }

    /**
     * Get filtered subjects with search and sorting
     */
    public function getFilteredSubjects($filters = [])
    {
        $builder = $this;

        // Apply filters
        if (!empty($filters['curriculum'])) {
            $builder = $builder->where('curriculum', $filters['curriculum']);
        }

        if (!empty($filters['level_name'])) {
            $builder = $builder->where('level_name', $filters['level_name']);
        }

        if (!empty($filters['subject_category'])) {
            $builder = $builder->where('subject_category', $filters['subject_category']);
        }

        if (isset($filters['is_active']) && $filters['is_active'] !== null) {
            $builder = $builder->where('is_active', $filters['is_active']);
        }

        if (!empty($filters['search'])) {
            $builder = $builder->groupStart()
                               ->like('subject_name', $filters['search'])
                               ->orLike('level_name', $filters['search'])
                               ->orLike('curriculum', $filters['search'])
                               ->groupEnd();
        }

        // Apply sorting
        $sortBy = $filters['sort_by'] ?? 'curriculum';
        $sortOrder = $filters['sort_order'] ?? 'ASC';

        // Validate sort fields
        $allowedSortFields = ['curriculum', 'level_name', 'subject_name', 'subject_category', 'is_active', 'created_at'];
        if (in_array($sortBy, $allowedSortFields)) {
            $builder = $builder->orderBy($sortBy, $sortOrder);
        }

        // Add secondary sort for consistent ordering
        if ($sortBy !== 'subject_name') {
            $builder = $builder->orderBy('subject_name', 'ASC');
        }

        return $builder->findAll();
    }

    /**
     * Get unique level names for filter dropdown
     */
    public function getLevelNames()
    {
        return $this->distinct()
                    ->select('level_name')
                    ->where('is_active', 1)
                    ->orderBy('level_name', 'ASC')
                    ->findAll();
    }

    /**
     * Get unique subject categories for filter dropdown
     */
    public function getCategories()
    {
        return $this->distinct()
                    ->select('subject_category')
                    ->where('subject_category IS NOT NULL')
                    ->where('subject_category !=', '')
                    ->where('is_active', 1)
                    ->orderBy('subject_category', 'ASC')
                    ->findAll();
    }
}
