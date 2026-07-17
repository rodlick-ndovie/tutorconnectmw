<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CurriculumSubjectsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // MANEB Curriculum
            // Primary (Standards 1-8)
            ['curriculum' => 'MANEB', 'level_name' => 'Primary (Standards 1-8)', 'subject_name' => 'Chichewa', 'subject_category' => 'Language'],
            ['curriculum' => 'MANEB', 'level_name' => 'Primary (Standards 1-8)', 'subject_name' => 'English', 'subject_category' => 'Language'],
            ['curriculum' => 'MANEB', 'level_name' => 'Primary (Standards 1-8)', 'subject_name' => 'Mathematics', 'subject_category' => 'Mathematics'],
            ['curriculum' => 'MANEB', 'level_name' => 'Primary (Standards 1-8)', 'subject_name' => 'Expressive Arts', 'subject_category' => 'Arts'],
            ['curriculum' => 'MANEB', 'level_name' => 'Primary (Standards 1-8)', 'subject_name' => 'Life Skills', 'subject_category' => 'Social Studies'],
            ['curriculum' => 'MANEB', 'level_name' => 'Primary (Standards 1-8)', 'subject_name' => 'Social and Environmental Sciences', 'subject_category' => 'Social Studies'],
            ['curriculum' => 'MANEB', 'level_name' => 'Primary (Standards 1-8)', 'subject_name' => 'Science and Technology', 'subject_category' => 'Science'],
            ['curriculum' => 'MANEB', 'level_name' => 'Primary (Standards 1-8)', 'subject_name' => 'Agriculture', 'subject_category' => 'Agriculture'],

            // JCE Preparation
            ['curriculum' => 'MANEB', 'level_name' => 'JCE Preparation', 'subject_name' => 'English Language', 'subject_category' => 'Language'],
            ['curriculum' => 'MANEB', 'level_name' => 'JCE Preparation', 'subject_name' => 'Mathematics', 'subject_category' => 'Mathematics'],
            ['curriculum' => 'MANEB', 'level_name' => 'JCE Preparation', 'subject_name' => 'Chichewa', 'subject_category' => 'Language'],
            ['curriculum' => 'MANEB', 'level_name' => 'JCE Preparation', 'subject_name' => 'Social Studies', 'subject_category' => 'Social Studies'],
            ['curriculum' => 'MANEB', 'level_name' => 'JCE Preparation', 'subject_name' => 'Biology', 'subject_category' => 'Science'],
            ['curriculum' => 'MANEB', 'level_name' => 'JCE Preparation', 'subject_name' => 'Physics', 'subject_category' => 'Science'],
            ['curriculum' => 'MANEB', 'level_name' => 'JCE Preparation', 'subject_name' => 'Chemistry', 'subject_category' => 'Science'],
            ['curriculum' => 'MANEB', 'level_name' => 'JCE Preparation', 'subject_name' => 'Geography', 'subject_category' => 'Social Studies'],
            ['curriculum' => 'MANEB', 'level_name' => 'JCE Preparation', 'subject_name' => 'History', 'subject_category' => 'Social Studies'],
            ['curriculum' => 'MANEB', 'level_name' => 'JCE Preparation', 'subject_name' => 'Agriculture', 'subject_category' => 'Agriculture'],
            ['curriculum' => 'MANEB', 'level_name' => 'JCE Preparation', 'subject_name' => 'Computer Studies', 'subject_category' => 'Technology'],
            ['curriculum' => 'MANEB', 'level_name' => 'JCE Preparation', 'subject_name' => 'Life Skills', 'subject_category' => 'Social Studies'],
            ['curriculum' => 'MANEB', 'level_name' => 'JCE Preparation', 'subject_name' => 'Religious Education', 'subject_category' => 'Religion'],

            // MSCE Preparation
            ['curriculum' => 'MANEB', 'level_name' => 'MSCE Preparation', 'subject_name' => 'English Language', 'subject_category' => 'Language'],
            ['curriculum' => 'MANEB', 'level_name' => 'MSCE Preparation', 'subject_name' => 'Mathematics', 'subject_category' => 'Mathematics'],
            ['curriculum' => 'MANEB', 'level_name' => 'MSCE Preparation', 'subject_name' => 'Chichewa', 'subject_category' => 'Language'],
            ['curriculum' => 'MANEB', 'level_name' => 'MSCE Preparation', 'subject_name' => 'Social Studies', 'subject_category' => 'Social Studies'],
            ['curriculum' => 'MANEB', 'level_name' => 'MSCE Preparation', 'subject_name' => 'Biology', 'subject_category' => 'Science'],
            ['curriculum' => 'MANEB', 'level_name' => 'MSCE Preparation', 'subject_name' => 'Physics', 'subject_category' => 'Science'],
            ['curriculum' => 'MANEB', 'level_name' => 'MSCE Preparation', 'subject_name' => 'Chemistry', 'subject_category' => 'Science'],
            ['curriculum' => 'MANEB', 'level_name' => 'MSCE Preparation', 'subject_name' => 'Geography', 'subject_category' => 'Social Studies'],
            ['curriculum' => 'MANEB', 'level_name' => 'MSCE Preparation', 'subject_name' => 'History', 'subject_category' => 'Social Studies'],
            ['curriculum' => 'MANEB', 'level_name' => 'MSCE Preparation', 'subject_name' => 'Agriculture', 'subject_category' => 'Agriculture'],
            ['curriculum' => 'MANEB', 'level_name' => 'MSCE Preparation', 'subject_name' => 'Computer Studies', 'subject_category' => 'Technology'],
            ['curriculum' => 'MANEB', 'level_name' => 'MSCE Preparation', 'subject_name' => 'Life Skills', 'subject_category' => 'Social Studies'],
            ['curriculum' => 'MANEB', 'level_name' => 'MSCE Preparation', 'subject_name' => 'Religious Education', 'subject_category' => 'Religion'],

            // GCSE Curriculum
            // Key Stage 4 (Years 10-11)
            ['curriculum' => 'GCSE', 'level_name' => 'Key Stage 4 (Years 10-11)', 'subject_name' => 'English Language', 'subject_category' => 'Language'],
            ['curriculum' => 'GCSE', 'level_name' => 'Key Stage 4 (Years 10-11)', 'subject_name' => 'English Literature', 'subject_category' => 'Language'],
            ['curriculum' => 'GCSE', 'level_name' => 'Key Stage 4 (Years 10-11)', 'subject_name' => 'Mathematics', 'subject_category' => 'Mathematics'],
            ['curriculum' => 'GCSE', 'level_name' => 'Key Stage 4 (Years 10-11)', 'subject_name' => 'Combined Science', 'subject_category' => 'Science'],
            ['curriculum' => 'GCSE', 'level_name' => 'Key Stage 4 (Years 10-11)', 'subject_name' => 'Biology', 'subject_category' => 'Science'],
            ['curriculum' => 'GCSE', 'level_name' => 'Key Stage 4 (Years 10-11)', 'subject_name' => 'Chemistry', 'subject_category' => 'Science'],
            ['curriculum' => 'GCSE', 'level_name' => 'Key Stage 4 (Years 10-11)', 'subject_name' => 'Physics', 'subject_category' => 'Science'],
            ['curriculum' => 'GCSE', 'level_name' => 'Key Stage 4 (Years 10-11)', 'subject_name' => 'Art and Design', 'subject_category' => 'Arts'],
            ['curriculum' => 'GCSE', 'level_name' => 'Key Stage 4 (Years 10-11)', 'subject_name' => 'Computer Science', 'subject_category' => 'Technology'],
            ['curriculum' => 'GCSE', 'level_name' => 'Key Stage 4 (Years 10-11)', 'subject_name' => 'Geography', 'subject_category' => 'Social Studies'],
            ['curriculum' => 'GCSE', 'level_name' => 'Key Stage 4 (Years 10-11)', 'subject_name' => 'History', 'subject_category' => 'Social Studies'],
            ['curriculum' => 'GCSE', 'level_name' => 'Key Stage 4 (Years 10-11)', 'subject_name' => 'Modern Foreign Languages', 'subject_category' => 'Language'],
            ['curriculum' => 'GCSE', 'level_name' => 'Key Stage 4 (Years 10-11)', 'subject_name' => 'Music', 'subject_category' => 'Arts'],
            ['curriculum' => 'GCSE', 'level_name' => 'Key Stage 4 (Years 10-11)', 'subject_name' => 'Physical Education', 'subject_category' => 'Sports'],
            ['curriculum' => 'GCSE', 'level_name' => 'Key Stage 4 (Years 10-11)', 'subject_name' => 'Religious Studies', 'subject_category' => 'Religion'],
            ['curriculum' => 'GCSE', 'level_name' => 'Key Stage 4 (Years 10-11)', 'subject_name' => 'Business', 'subject_category' => 'Business'],
            ['curriculum' => 'GCSE', 'level_name' => 'Key Stage 4 (Years 10-11)', 'subject_name' => 'Economics', 'subject_category' => 'Business'],
            ['curriculum' => 'GCSE', 'level_name' => 'Key Stage 4 (Years 10-11)', 'subject_name' => 'Psychology', 'subject_category' => 'Social Studies'],
            ['curriculum' => 'GCSE', 'level_name' => 'Key Stage 4 (Years 10-11)', 'subject_name' => 'Sociology', 'subject_category' => 'Social Studies'],

            // Cambridge Curriculum
            // Cambridge IGCSE
            ['curriculum' => 'Cambridge', 'level_name' => 'Cambridge IGCSE', 'subject_name' => 'English Language', 'subject_category' => 'Language'],
            ['curriculum' => 'Cambridge', 'level_name' => 'Cambridge IGCSE', 'subject_name' => 'Mathematics', 'subject_category' => 'Mathematics'],
            ['curriculum' => 'Cambridge', 'level_name' => 'Cambridge IGCSE', 'subject_name' => 'Additional Mathematics', 'subject_category' => 'Mathematics'],
            ['curriculum' => 'Cambridge', 'level_name' => 'Cambridge IGCSE', 'subject_name' => 'Biology', 'subject_category' => 'Science'],
            ['curriculum' => 'Cambridge', 'level_name' => 'Cambridge IGCSE', 'subject_name' => 'Chemistry', 'subject_category' => 'Science'],
            ['curriculum' => 'Cambridge', 'level_name' => 'Cambridge IGCSE', 'subject_name' => 'Physics', 'subject_category' => 'Science'],
            ['curriculum' => 'Cambridge', 'level_name' => 'Cambridge IGCSE', 'subject_name' => 'Combined Science', 'subject_category' => 'Science'],
            ['curriculum' => 'Cambridge', 'level_name' => 'Cambridge IGCSE', 'subject_name' => 'Accounting', 'subject_category' => 'Business'],
            ['curriculum' => 'Cambridge', 'level_name' => 'Cambridge IGCSE', 'subject_name' => 'Business Studies', 'subject_category' => 'Business'],
            ['curriculum' => 'Cambridge', 'level_name' => 'Cambridge IGCSE', 'subject_name' => 'Economics', 'subject_category' => 'Business'],
            ['curriculum' => 'Cambridge', 'level_name' => 'Cambridge IGCSE', 'subject_name' => 'Geography', 'subject_category' => 'Social Studies'],
            ['curriculum' => 'Cambridge', 'level_name' => 'Cambridge IGCSE', 'subject_name' => 'History', 'subject_category' => 'Social Studies'],
            ['curriculum' => 'Cambridge', 'level_name' => 'Cambridge IGCSE', 'subject_name' => 'Computer Science', 'subject_category' => 'Technology'],
            ['curriculum' => 'Cambridge', 'level_name' => 'Cambridge IGCSE', 'subject_name' => 'ICT', 'subject_category' => 'Technology'],

            // Cambridge AS/A Level
            ['curriculum' => 'Cambridge', 'level_name' => 'Cambridge AS/A Level', 'subject_name' => 'English Language', 'subject_category' => 'Language'],
            ['curriculum' => 'Cambridge', 'level_name' => 'Cambridge AS/A Level', 'subject_name' => 'Literature in English', 'subject_category' => 'Language'],
            ['curriculum' => 'Cambridge', 'level_name' => 'Cambridge AS/A Level', 'subject_name' => 'Mathematics', 'subject_category' => 'Mathematics'],
            ['curriculum' => 'Cambridge', 'level_name' => 'Cambridge AS/A Level', 'subject_name' => 'Further Mathematics', 'subject_category' => 'Mathematics'],
            ['curriculum' => 'Cambridge', 'level_name' => 'Cambridge AS/A Level', 'subject_name' => 'Biology', 'subject_category' => 'Science'],
            ['curriculum' => 'Cambridge', 'level_name' => 'Cambridge AS/A Level', 'subject_name' => 'Chemistry', 'subject_category' => 'Science'],
            ['curriculum' => 'Cambridge', 'level_name' => 'Cambridge AS/A Level', 'subject_name' => 'Physics', 'subject_category' => 'Science'],
            ['curriculum' => 'Cambridge', 'level_name' => 'Cambridge AS/A Level', 'subject_name' => 'Computer Science', 'subject_category' => 'Technology'],
            ['curriculum' => 'Cambridge', 'level_name' => 'Cambridge AS/A Level', 'subject_name' => 'Accounting', 'subject_category' => 'Business'],
            ['curriculum' => 'Cambridge', 'level_name' => 'Cambridge AS/A Level', 'subject_name' => 'Business', 'subject_category' => 'Business'],
            ['curriculum' => 'Cambridge', 'level_name' => 'Cambridge AS/A Level', 'subject_name' => 'Economics', 'subject_category' => 'Business'],
            ['curriculum' => 'Cambridge', 'level_name' => 'Cambridge AS/A Level', 'subject_name' => 'Geography', 'subject_category' => 'Social Studies'],
            ['curriculum' => 'Cambridge', 'level_name' => 'Cambridge AS/A Level', 'subject_name' => 'History', 'subject_category' => 'Social Studies'],
            ['curriculum' => 'Cambridge', 'level_name' => 'Cambridge AS/A Level', 'subject_name' => 'Psychology', 'subject_category' => 'Social Studies'],
            ['curriculum' => 'Cambridge', 'level_name' => 'Cambridge AS/A Level', 'subject_name' => 'Sociology', 'subject_category' => 'Social Studies'],
        ];

        // Insert data
        $this->db->table('curriculum_subjects')->insertBatch($data);
    }
}
