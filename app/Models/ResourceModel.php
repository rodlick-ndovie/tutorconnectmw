<?php

namespace App\Models;

use CodeIgniter\Model;

class ResourceModel extends Model
{
    protected $table = 'resources';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'title',
        'resource_type',
        'subject',
        'curriculum',
        'grade_level',
        'year',
        'paper_type',
        'video_url',
        'video_thumbnail',
        'video_duration',
        'file_path',
        'file_name',
        'file_size',
        'description',
        'tags',
        'uploaded_by',
        'is_approved',
        'is_featured',
        'view_count',
        'download_count'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'title' => 'required|min_length[3]|max_length[255]',
        'resource_type' => 'required|in_list[video,past_paper]',
        'subject' => 'required|max_length[100]',
        'curriculum' => 'required|max_length[50]',
        'grade_level' => 'required|max_length[50]'
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;

    public function getApprovedResources($type = null, $limit = null)
    {
        $builder = $this->where('is_approved', 1);
        
        if ($type) {
            $builder->where('resource_type', $type);
        }
        
        $builder->orderBy('is_featured', 'DESC')
                ->orderBy('created_at', 'DESC');
        
        if ($limit) {
            $builder->limit($limit);
        }
        
        return $builder->findAll();
    }

    public function getFeaturedResources($limit = 6)
    {
        return $this->where('is_approved', 1)
                    ->where('is_featured', 1)
                    ->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    public function searchResources($filters = [])
    {
        $builder = $this->where('is_approved', 1);
        
        if (!empty($filters['type'])) {
            $builder->where('resource_type', $filters['type']);
        }
        
        if (!empty($filters['subject'])) {
            $builder->where('subject', $filters['subject']);
        }
        
        if (!empty($filters['curriculum'])) {
            $builder->where('curriculum', $filters['curriculum']);
        }
        
        if (!empty($filters['grade'])) {
            $builder->where('grade_level', $filters['grade']);
        }
        
        if (!empty($filters['year'])) {
            $builder->where('year', $filters['year']);
        }
        
        if (!empty($filters['search'])) {
            $builder->groupStart()
                    ->like('title', $filters['search'])
                    ->orLike('description', $filters['search'])
                    ->orLike('tags', $filters['search'])
                    ->groupEnd();
        }
        
        return $builder->orderBy('is_featured', 'DESC')
                       ->orderBy('created_at', 'DESC')
                       ->findAll();
    }

    public function incrementViewCount($id)
    {
        return $this->set('view_count', 'view_count + 1', false)
                    ->where('id', $id)
                    ->update();
    }

    public function incrementDownloadCount($id)
    {
        return $this->set('download_count', 'download_count + 1', false)
                    ->where('id', $id)
                    ->update();
    }

    public function getPendingCount()
    {
        return $this->where('is_approved', 0)->countAllResults();
    }
}
