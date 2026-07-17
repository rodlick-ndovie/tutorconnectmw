<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SubjectEducationSeeder extends Seeder
{
    public function run()
    {
        $this->seedSubjectCategories();
        $this->seedEducationLevels();
    }

    private function seedSubjectCategories()
    {
        $subjectCategories = [
            // Core Subjects
            ['name' => 'Mathematics', 'category' => 'Core Subjects', 'description' => 'Mathematics including algebra, geometry, calculus', 'sort_order' => 1],
            ['name' => 'English', 'category' => 'Core Subjects', 'description' => 'English language and literature', 'sort_order' => 2],
            ['name' => 'Chichewa', 'category' => 'Core Subjects', 'description' => 'Chichewa language and literature', 'sort_order' => 3],
            ['name' => 'Biology', 'category' => 'Core Subjects', 'description' => 'Biology and life sciences', 'sort_order' => 4],
            ['name' => 'Chemistry', 'category' => 'Core Subjects', 'description' => 'Chemistry and chemical sciences', 'sort_order' => 5],
            ['name' => 'Physics', 'category' => 'Core Subjects', 'description' => 'Physics and physical sciences', 'sort_order' => 6],
            ['name' => 'Social Studies', 'category' => 'Core Subjects', 'description' => 'Social studies and history', 'sort_order' => 7],
            ['name' => 'Agriculture', 'category' => 'Core Subjects', 'description' => 'Agriculture and farming sciences', 'sort_order' => 8],

            // Secondary Level Focus
            ['name' => 'MSCE Preparation', 'category' => 'Secondary Level Focus', 'description' => 'Malawi School Certificate of Education preparation', 'sort_order' => 9],
            ['name' => 'JCE Preparation', 'category' => 'Secondary Level Focus', 'description' => 'Junior Certificate of Education preparation', 'sort_order' => 10],
            ['name' => 'Form 1-4 Support', 'category' => 'Secondary Level Focus', 'description' => 'General support for Forms 1 through 4', 'sort_order' => 11],

            // Tertiary/Professional
            ['name' => 'Accounting', 'category' => 'Tertiary/Professional', 'description' => 'Accounting and financial management', 'sort_order' => 12],
            ['name' => 'Business Studies', 'category' => 'Tertiary/Professional', 'description' => 'Business management and administration', 'sort_order' => 13],
            ['name' => 'Computer Studies', 'category' => 'Tertiary/Professional', 'description' => 'Computer science and IT', 'sort_order' => 14],
            ['name' => 'Entrepreneurship', 'category' => 'Tertiary/Professional', 'description' => 'Business startup and entrepreneurship', 'sort_order' => 15],
            ['name' => 'Professional Skills Development', 'category' => 'Tertiary/Professional', 'description' => 'Professional development and soft skills', 'sort_order' => 16],

            // Languages
            ['name' => 'French', 'category' => 'Languages', 'description' => 'French language instruction', 'sort_order' => 17],
            ['name' => 'Portuguese', 'category' => 'Languages', 'description' => 'Portuguese language instruction', 'sort_order' => 18],
            ['name' => 'Chinese', 'category' => 'Languages', 'description' => 'Chinese language instruction', 'sort_order' => 19],
            ['name' => 'English as a Second Language', 'category' => 'Languages', 'description' => 'ESL for non-native speakers', 'sort_order' => 20],
        ];

        foreach ($subjectCategories as $subject) {
            $data = array_merge($subject, [
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            $this->db->table('subject_categories')->insert($data);
        }

        echo "✓ Seeded " . count($subjectCategories) . " subject categories\n";
    }

    private function seedEducationLevels()
    {
        $educationLevels = [
            // Primary
            ['name' => 'Standard 1-4', 'category' => 'Primary', 'level_order' => 1, 'description' => 'Primary school grades 1 through 4'],
            ['name' => 'Standard 5-8', 'category' => 'Primary', 'level_order' => 2, 'description' => 'Primary school grades 5 through 8'],

            // Secondary
            ['name' => 'Form 1', 'category' => 'Secondary', 'level_order' => 3, 'description' => 'Secondary school Form 1'],
            ['name' => 'Form 2', 'category' => 'Secondary', 'level_order' => 4, 'description' => 'Secondary school Form 2'],
            ['name' => 'Form 3', 'category' => 'Secondary', 'level_order' => 5, 'description' => 'Secondary school Form 3'],
            ['name' => 'Form 4', 'category' => 'Secondary', 'level_order' => 6, 'description' => 'Secondary school Form 4'],
            ['name' => 'JCE Preparation', 'category' => 'Secondary', 'level_order' => 7, 'description' => 'Junior Certificate of Education exam preparation'],
            ['name' => 'MSCE Preparation', 'category' => 'Secondary', 'level_order' => 8, 'description' => 'Malawi School Certificate of Education exam preparation'],

            // Tertiary
            ['name' => 'Diploma Level', 'category' => 'Tertiary', 'level_order' => 9, 'description' => 'Diploma and certificate programs'],
            ['name' => 'Degree Level', 'category' => 'Tertiary', 'level_order' => 10, 'description' => 'Bachelor\'s and Master\'s degree programs'],
            ['name' => 'Professional Certification', 'category' => 'Tertiary', 'level_order' => 11, 'description' => 'Professional certifications and licenses'],
            ['name' => 'Adult Education', 'category' => 'Tertiary', 'level_order' => 12, 'description' => 'Continuing education for adults'],
        ];

        foreach ($educationLevels as $level) {
            $data = array_merge($level, [
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            $this->db->table('education_levels')->insert($data);
        }

        echo "✓ Seeded " . count($educationLevels) . " education levels\n";
    }
}
