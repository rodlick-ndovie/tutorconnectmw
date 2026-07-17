<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;

class Api extends BaseController
{
    use ResponseTrait;

    /**
     * Get curriculum levels
     */
    public function getCurriculumLevels()
    {
        $curriculum = $this->request->getVar('curriculum');

        if (!$curriculum) {
            return $this->fail('Curriculum is required', 400);
        }

        $db = \Config\Database::connect();
        $builder = $db->table('curriculum_subjects');

        $levels = $builder
            ->where('curriculum', $curriculum)
            ->where('is_active', 1)
            ->distinct()
            ->select('level_name')
            ->orderBy('level_name', 'ASC')
            ->get()
            ->getResultArray();

        $levelNames = array_column($levels, 'level_name');

        return $this->respond([
            'success' => true,
            'levels' => $levelNames
        ]);
    }

    /**
     * Get subjects for curriculum and level
     */
    public function getCurriculumSubjects()
    {
        $curriculum = $this->request->getVar('curriculum');
        $level = $this->request->getVar('level');

        if (!$curriculum || !$level) {
            return $this->fail('Curriculum and level are required', 400);
        }

        $db = \Config\Database::connect();
        $builder = $db->table('curriculum_subjects');

        $subjects = $builder
            ->where('curriculum', $curriculum)
            ->where('level_name', $level)
            ->where('is_active', 1)
            ->distinct()
            ->select('subject_name')
            ->orderBy('subject_name', 'ASC')
            ->get()
            ->getResultArray();

        $subjectNames = array_column($subjects, 'subject_name');

        return $this->respond([
            'success' => true,
            'subjects' => $subjectNames
        ]);
    }
}
