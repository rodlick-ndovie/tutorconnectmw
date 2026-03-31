<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ServicesSeeder extends Seeder
{
    public function run()
    {
        // Sample service categories
        $categories = [
            [
                'name' => 'Tutoring Services',
                'description' => 'Individual and group tutoring services',
                'parent_category' => null,
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Professional Development',
                'description' => 'Career advancement and skill development services',
                'parent_category' => null,
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Educational Materials',
                'description' => 'Books, resources, and learning materials',
                'parent_category' => null,
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Insert categories
        foreach ($categories as $category) {
            $this->db->table('service_categories')->insert($category);
        }

        // Get category IDs
        $tutoringCatId = $this->db->table('service_categories')
                                 ->where('name', 'Tutoring Services')
                                 ->get()
                                 ->getRowArray()['id'];

        $professionalCatId = $this->db->table('service_categories')
                                    ->where('name', 'Professional Development')
                                    ->get()
                                    ->getRowArray()['id'];

        $materialsCatId = $this->db->table('service_categories')
                                  ->where('name', 'Educational Materials')
                                  ->get()
                                  ->getRowArray()['id'];

        // Sample services
        $services = [
            [
                'name' => 'One-on-One Mathematics Tutoring',
                'description' => 'Personalized mathematics tutoring for primary and secondary students focusing on MSCE and IGCSE preparation.',
                'service_type' => 'tutoring_package',
                'categorization' => 'Tutoring Services',
                'base_price' => 25000.00,
                'currency' => 'MWK',
                'pricing_type' => 'hourly',
                'duration_hours' => 2.0,
                'max_participants' => 1,
                'features' => json_encode([
                    'Individual attention',
                    'Customized lesson plans',
                    'Progress tracking',
                    'Homework assistance',
                    'Exam preparation',
                    'Flexible scheduling'
                ]),
                'is_active' => true,
                'sort_order' => 1,
                'created_by' => 1, // Admin user
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Science Lab Sessions',
                'description' => 'Hands-on science experiments and demonstrations for Chemistry and Physics students.',
                'service_type' => 'exam_prep',
                'categorization' => 'Tutoring Services',
                'base_price' => 35000.00,
                'currency' => 'MWK',
                'pricing_type' => 'package',
                'duration_hours' => 3.0,
                'max_participants' => 5,
                'features' => json_encode([
                    'Laboratory access',
                    'Safe experiment demonstrations',
                    'Practical exam preparation',
                    'Scientific method understanding',
                    'Safety training included',
                    'Small group learning'
                ]),
                'is_active' => true,
                'sort_order' => 2,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'English Language Immersion',
                'description' => 'Comprehensive English language training for non-native speakers focusing on speaking, writing, and comprehension.',
                'service_type' => 'tutoring_package',
                'categorization' => 'Tutoring Services',
                'base_price' => 20000.00,
                'currency' => 'MWK',
                'pricing_type' => 'hourly',
                'duration_hours' => 1.5,
                'max_participants' => 2,
                'features' => json_encode([
                    'Conversation practice',
                    'Grammar workshops',
                    'Reading comprehension',
                    'Writing skills development',
                    'Pronunciation training',
                    'Cultural context learning'
                ]),
                'is_active' => true,
                'sort_order' => 3,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Professional Teacher Training',
                'description' => 'Advanced training program for educators focusing on modern teaching methodologies and classroom management.',
                'service_type' => 'professional_dev',
                'categorization' => 'Professional Development',
                'base_price' => 75000.00,
                'currency' => 'MWK',
                'pricing_type' => 'package',
                'duration_hours' => 20.0,
                'max_participants' => 15,
                'features' => json_encode([
                    'Modern teaching techniques',
                    'Classroom management skills',
                    'Assessment and evaluation',
                    'Curriculum development',
                    'Technology integration',
                    'Professional certification',
                    'Ongoing support',
                    'Resource materials included'
                ]),
                'is_active' => true,
                'sort_order' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Business Studies Curriculum Package',
                'description' => 'Complete study materials and resources for business studies covering accounting, economics, and entrepreneurship.',
                'service_type' => 'course_material',
                'categorization' => 'Educational Materials',
                'base_price' => 15000.00,
                'currency' => 'MWK',
                'pricing_type' => 'package',
                'duration_hours' => null,
                'max_participants' => 1,
                'features' => json_encode([
                    'Comprehensive study guides',
                    'Practice examination papers',
                    'Model answers and explanations',
                    'Case study materials',
                    'Digital access included',
                    'Regular updates',
                    'Online support access'
                ]),
                'is_active' => true,
                'sort_order' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Consultation Services',
                'description' => 'Expert consultation services for educational planning, career guidance, and academic development.',
                'service_type' => 'consultation',
                'categorization' => 'Professional Development',
                'base_price' => 50000.00,
                'currency' => 'MWK',
                'pricing_type' => 'hourly',
                'duration_hours' => 1.0,
                'max_participants' => 4,
                'features' => json_encode([
                    'Educational planning consultation',
                    'Career guidance services',
                    'Academic performance analysis',
                    'Study strategy development',
                    'Parent-teacher conferencing',
                    'Curriculum selection advice',
                    'School transition support'
                ]),
                'is_active' => true,
                'sort_order' => 2,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Homework Support Program',
                'description' => 'Weekly homework assistance and study support for primary and secondary students.',
                'service_type' => 'homework_help',
                'categorization' => 'Tutoring Services',
                'base_price' => 80000.00,
                'currency' => 'MWK',
                'pricing_type' => 'package',
                'duration_hours' => 12.0,
                'max_participants' => 1,
                'features' => json_encode([
                    'Weekly homework assistance',
                    'Subject-specific help',
                    'Study skill development',
                    'Progress monitoring',
                    'Parent updates',
                    'Flexible scheduling',
                    'Online and in-person options',
                    'Regular progress reports'
                ]),
                'is_active' => true,
                'sort_order' => 4,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Insert services
        foreach ($services as $service) {
            $this->db->table('services')->insert($service);
        }
    }
}
