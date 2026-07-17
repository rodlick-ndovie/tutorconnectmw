<?php

namespace App\Models;

use CodeIgniter\Model;

class PastPapersModel extends Model
{
    protected $table            = 'past_papers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = false;
    protected $allowedFields    = [
        'exam_body', 'exam_level', 'subject', 'year', 'paper_title',
        'paper_code', 'file_url', 'file_size', 'download_count',
        'is_active', 'is_paid', 'price', 'copyright_notice', 'uploaded_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [];
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

    // Simple method to get all papers
    public function getAllPapers()
    {
        return $this->orderBy('year', 'DESC')
                    ->orderBy('exam_body', 'ASC')
                    ->orderBy('subject', 'ASC')
                    ->findAll();
    }

    // Get papers with filters
    public function getFilteredPapers($filters = [])
    {
        $builder = $this->where('is_active', 1);

        if (!empty($filters['exam_body'])) {
            $builder->where('exam_body', $filters['exam_body']);
        }

        if (!empty($filters['exam_level'])) {
            $builder->where('exam_level', $filters['exam_level']);
        }

        if (!empty($filters['subject'])) {
            $builder->where('subject', $filters['subject']);
        }

        if (!empty($filters['year'])) {
            $builder->where('year', $filters['year']);
        }

        if (!empty($filters['search'])) {
            $builder->groupStart()
                ->like('paper_title', $filters['search'])
                ->orLike('paper_code', $filters['search'])
                ->orLike('subject', $filters['search'])
                ->groupEnd();
        }

        return $builder->orderBy('year', 'DESC')
                      ->orderBy('exam_body', 'ASC')
                      ->orderBy('subject', 'ASC')
                      ->findAll();
    }

    // Get papers with filters and uploader information
    public function getFilteredPapersWithUploader($filters = [])
    {
        $builder = $this->select('past_papers.*, users.first_name, users.last_name')
                       ->join('users', 'users.id = past_papers.uploaded_by', 'left')
                       ->where('past_papers.is_active', 1);

        if (!empty($filters['exam_body'])) {
            $builder->where('past_papers.exam_body', $filters['exam_body']);
        }

        if (!empty($filters['exam_level'])) {
            $builder->where('past_papers.exam_level', $filters['exam_level']);
        }

        if (!empty($filters['subject'])) {
            $builder->where('past_papers.subject', $filters['subject']);
        }

        if (!empty($filters['year'])) {
            $builder->where('past_papers.year', $filters['year']);
        }

        if (!empty($filters['search'])) {
            $builder->groupStart()
                ->like('past_papers.paper_title', $filters['search'])
                ->orLike('past_papers.paper_code', $filters['search'])
                ->orLike('past_papers.subject', $filters['search'])
                ->groupEnd();
        }

        return $builder->orderBy('past_papers.year', 'DESC')
                      ->orderBy('past_papers.exam_body', 'ASC')
                      ->orderBy('past_papers.subject', 'ASC')
                      ->findAll();
    }

    // Get unique values for filter dropdowns
    public function getFilterOptions()
    {
        return [
            'exam_bodies' => array_unique(array_column($this->select('exam_body')->findAll(), 'exam_body')),
            'exam_levels' => array_unique(array_column($this->select('exam_level')->findAll(), 'exam_level')),
            'subjects' => $this->distinct()->select('subject')->where('is_active', 1)->findAll(),
            'years' => $this->distinct()->select('year')->where('is_active', 1)->orderBy('year', 'DESC')->findAll(),
        ];
    }

    // Increment download count
    public function incrementDownloadCount($paperId)
    {
        return $this->where('id', $paperId)->set('download_count', 'download_count + 1', false)->update();
    }

    // Get unique exam bodies
    public function getExamBodies()
    {
        return $this->distinct()->select('exam_body')->findAll();
    }

    // Get unique exam levels
    public function getExamLevels()
    {
        return $this->distinct()->select('exam_level')->findAll();
    }

    // Get unique subjects
    public function getSubjects()
    {
        return $this->distinct()->select('subject')->findAll();
    }
}
