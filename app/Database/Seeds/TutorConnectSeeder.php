<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TutorConnectSeeder extends Seeder
{
    public function run()
    {
        // Clear existing data
        $this->db->table('reviews')->truncate();
        $this->db->table('bookings')->truncate();
        $this->db->table('tutor_availability')->truncate();
        $this->db->table('tutor_references')->truncate();
        $this->db->table('tutor_certificates')->truncate();
        $this->db->table('tutor_curricula')->truncate();
        $this->db->table('tutor_levels')->truncate();
        $this->db->table('tutor_subjects')->truncate();
        $this->db->table('tutors')->truncate();
        $this->db->table('users')->truncate();

        // Users data
        $users = [
            [
                'username' => 'admin',
                'email' => 'info@tutorconnectmw.com',
                'password_hash' => password_hash('admin123', PASSWORD_DEFAULT),
                'role' => 'admin',
                'first_name' => 'Admin',
                'last_name' => 'TutorConnect',
                'phone' => '+265992313978',
                'is_active' => 1,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username' => 'grace_mwase',
                'email' => 'grace.mwase@tutorconnectmw.com',
                'password_hash' => password_hash('password123', PASSWORD_DEFAULT),
                'role' => 'trainer',
                'first_name' => 'Grace',
                'last_name' => 'Mwase',
                'phone' => '+265999123456',
                'is_active' => 1,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username' => 'thomas_banda',
                'email' => 'thomas.banda@tutorconnectmw.com',
                'password_hash' => password_hash('password123', PASSWORD_DEFAULT),
                'role' => 'trainer',
                'first_name' => 'Thomas',
                'last_name' => 'Banda',
                'phone' => '+265999234567',
                'is_active' => 1,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username' => 'esther_kamwendo',
                'email' => 'esther.kamwendo@tutorconnectmw.com',
                'password_hash' => password_hash('password123', PASSWORD_DEFAULT),
                'role' => 'trainer',
                'first_name' => 'Esther',
                'last_name' => 'Kamwendo',
                'phone' => '+265999345678',
                'is_active' => 1,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username' => 'patricia_jere',
                'email' => 'patricia.jere@tutorconnectmw.com',
                'password_hash' => password_hash('password123', PASSWORD_DEFAULT),
                'role' => 'trainer',
                'first_name' => 'Patricia',
                'last_name' => 'Jere',
                'phone' => '+265999456789',
                'is_active' => 1,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username' => 'james_manda',
                'email' => 'james.manda@tutorconnectmw.com',
                'password_hash' => password_hash('password123', PASSWORD_DEFAULT),
                'role' => 'trainer',
                'first_name' => 'James',
                'last_name' => 'Manda',
                'phone' => '+265999567890',
                'is_active' => 1,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Insert users
        $userIds = [];
        foreach ($users as $user) {
            $this->db->table('users')->insert($user);
            $userIds[$user['username']] = $this->db->insertID();
        }

        // Tutors data
        $tutors = [
            [
                'user_id' => $userIds['grace_mwase'],
                'district' => 'Lilongwe',
                'area' => 'Area 18',
                'experience_years' => 7,
                'rate' => 25000.00,
                'rate_type' => 'per session',
                'teaching_mode' => 'Both Online & Physical',
                'bio' => 'PhD in Mathematics with 8 years of teaching experience. Specializes in MSCE exam preparation with a 95% pass rate.',
                'bio_video' => 'grace_mwase_bio.mp4',
                'cv_file' => 'grace_mwase_cv.pdf',
                'training_papers' => 'grace_mwase_training.pdf',
                'whatsapp_number' => '+265888123456',
                'best_call_time' => 'Morning (8AM-12PM)',
                'is_verified' => 1,
                'is_employed' => 1,
                'school_name' => 'Lilongwe Secondary School',
                'subscription_plan' => 'Premium',
                'subscription_expires_at' => date('Y-m-d', strtotime('+30 days')),
                'rating' => 4.8,
                'review_count' => 42,
                'search_count' => 150,
                'featured' => 1,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id' => $userIds['thomas_banda'],
                'district' => 'Blantyre',
                'area' => 'Chichiri',
                'experience_years' => 5,
                'rate' => 18000.00,
                'rate_type' => 'per session',
                'teaching_mode' => 'Physical Only',
                'bio' => 'Native Chichewa speaker with expertise in language acquisition. Creates engaging lessons for primary students.',
                'whatsapp_number' => '+265888234567',
                'best_call_time' => 'Afternoon (2PM-5PM)',
                'is_verified' => 1,
                'is_employed' => 1,
                'school_name' => 'Blantyre Primary School',
                'subscription_plan' => 'Standard',
                'subscription_expires_at' => date('Y-m-d', strtotime('+25 days')),
                'rating' => 4.5,
                'review_count' => 28,
                'search_count' => 120,
                'featured' => 0,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id' => $userIds['esther_kamwendo'],
                'district' => 'Zomba',
                'area' => 'Mandala',
                'experience_years' => 15,
                'rate' => 32000.00,
                'rate_type' => 'per session',
                'teaching_mode' => 'Online Only',
                'bio' => 'Former Cambridge examiner with extensive experience in laboratory-based science education.',
                'bio_video' => 'esther_kamwendo_bio.mp4',
                'cv_file' => 'esther_kamwendo_cv.pdf',
                'training_papers' => 'esther_kamwendo_training.pdf',
                'whatsapp_number' => '+265888345678',
                'best_call_time' => 'Evening (6PM-9PM)',
                'is_verified' => 1,
                'is_employed' => 0,
                'subscription_plan' => 'Premium',
                'subscription_expires_at' => date('Y-m-d', strtotime('+15 days')),
                'rating' => 4.9,
                'review_count' => 56,
                'search_count' => 200,
                'featured' => 1,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id' => $userIds['patricia_jere'],
                'district' => 'Mzuzu',
                'area' => 'Chitipa Road',
                'experience_years' => 3,
                'rate' => 22000.00,
                'rate_type' => 'per month',
                'teaching_mode' => 'Both Online & Physical',
                'bio' => 'Chartered accountant specializing in practical business applications for secondary students.',
                'bio_video' => 'patricia_jere_bio.mp4',
                'cv_file' => 'patricia_jere_cv.pdf',
                'whatsapp_number' => '+265888456789',
                'best_call_time' => 'Afternoon (2PM-5PM)',
                'is_verified' => 0,
                'is_employed' => 1,
                'school_name' => 'Mzuzu Secondary School',
                'subscription_plan' => 'Basic',
                'subscription_expires_at' => date('Y-m-d', strtotime('+60 days')),
                'rating' => 4.3,
                'review_count' => 19,
                'search_count' => 80,
                'featured' => 0,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id' => $userIds['james_manda'],
                'district' => 'Lilongwe',
                'area' => 'Area 3',
                'experience_years' => 5,
                'rate' => 15000.00,
                'rate_type' => 'per week',
                'teaching_mode' => 'Physical Only',
                'bio' => 'Specializes in making complex mathematical concepts simple and engaging for primary students.',
                'bio_video' => 'james_manda_bio.mp4',
                'cv_file' => 'james_manda_cv.pdf',
                'training_papers' => 'james_manda_training.pdf',
                'whatsapp_number' => '+265888567890',
                'best_call_time' => 'Morning (8AM-12PM)',
                'is_verified' => 1,
                'is_employed' => 1,
                'school_name' => 'Area 3 Primary School',
                'subscription_plan' => 'Standard',
                'subscription_expires_at' => date('Y-m-d', strtotime('+45 days')),
                'rating' => 4.6,
                'review_count' => 32,
                'search_count' => 95,
                'featured' => 0,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Insert tutors
        $tutorIds = [];
        foreach ($tutors as $tutor) {
            $this->db->table('tutors')->insert($tutor);
            $tutorIds[$tutor['user_id']] = $this->db->insertID();
        }

        // Tutor subjects
        $tutorSubjects = [
            ['tutor_id' => $tutorIds[$userIds['grace_mwase']], 'subject_name' => 'Mathematics'],
            ['tutor_id' => $tutorIds[$userIds['grace_mwase']], 'subject_name' => 'Physics'],
            ['tutor_id' => $tutorIds[$userIds['thomas_banda']], 'subject_name' => 'English Language'],
            ['tutor_id' => $tutorIds[$userIds['thomas_banda']], 'subject_name' => 'Chichewa'],
            ['tutor_id' => $tutorIds[$userIds['esther_kamwendo']], 'subject_name' => 'Chemistry'],
            ['tutor_id' => $tutorIds[$userIds['esther_kamwendo']], 'subject_name' => 'Biology'],
            ['tutor_id' => $tutorIds[$userIds['patricia_jere']], 'subject_name' => 'Business Studies'],
            ['tutor_id' => $tutorIds[$userIds['patricia_jere']], 'subject_name' => 'Accounting'],
            ['tutor_id' => $tutorIds[$userIds['james_manda']], 'subject_name' => 'Mathematics'],
            ['tutor_id' => $tutorIds[$userIds['james_manda']], 'subject_name' => 'Computer Studies'],
        ];

        foreach ($tutorSubjects as $subject) {
            $subject['created_at'] = date('Y-m-d H:i:s');
            $this->db->table('tutor_subjects')->insert($subject);
        }

        // Tutor levels
        $levelMappings = [
            ['tutor_id' => $tutorIds[$userIds['grace_mwase']], 'level_name' => 'Form 3'],
            ['tutor_id' => $tutorIds[$userIds['grace_mwase']], 'level_name' => 'Form 4'],
            ['tutor_id' => $tutorIds[$userIds['grace_mwase']], 'level_name' => 'MSCE Preparation'],
            ['tutor_id' => $tutorIds[$userIds['thomas_banda']], 'level_name' => 'Standard 5-8'],
            ['tutor_id' => $tutorIds[$userIds['thomas_banda']], 'level_name' => 'Form 1-2'],
            ['tutor_id' => $tutorIds[$userIds['thomas_banda']], 'level_name' => 'JCE Preparation'],
            ['tutor_id' => $tutorIds[$userIds['esther_kamwendo']], 'level_name' => 'Form 3-4'],
            ['tutor_id' => $tutorIds[$userIds['esther_kamwendo']], 'level_name' => 'MSCE Preparation'],
            ['tutor_id' => $tutorIds[$userIds['esther_kamwendo']], 'level_name' => 'A Levels'],
            ['tutor_id' => $tutorIds[$userIds['patricia_jere']], 'level_name' => 'Form 3-4'],
            ['tutor_id' => $tutorIds[$userIds['patricia_jere']], 'level_name' => 'MSCE Preparation'],
            ['tutor_id' => $tutorIds[$userIds['james_manda']], 'level_name' => 'Standard 5-8'],
            ['tutor_id' => $tutorIds[$userIds['james_manda']], 'level_name' => 'Form 1-2'],
        ];

        foreach ($levelMappings as $level) {
            $level['created_at'] = date('Y-m-d H:i:s');
            $this->db->table('tutor_levels')->insert($level);
        }

        // Tutor curricula
        $curriculumMappings = [
            ['tutor_id' => $tutorIds[$userIds['grace_mwase']], 'curriculum_name' => 'MANEB'],
            ['tutor_id' => $tutorIds[$userIds['grace_mwase']], 'curriculum_name' => 'Cambridge'],
            ['tutor_id' => $tutorIds[$userIds['thomas_banda']], 'curriculum_name' => 'MANEB'],
            ['tutor_id' => $tutorIds[$userIds['thomas_banda']], 'curriculum_name' => 'GCSE'],
            ['tutor_id' => $tutorIds[$userIds['esther_kamwendo']], 'curriculum_name' => 'Cambridge'],
            ['tutor_id' => $tutorIds[$userIds['patricia_jere']], 'curriculum_name' => 'MANEB'],
            ['tutor_id' => $tutorIds[$userIds['patricia_jere']], 'curriculum_name' => 'GCSE'],
            ['tutor_id' => $tutorIds[$userIds['james_manda']], 'curriculum_name' => 'MANEB'],
        ];

        foreach ($curriculumMappings as $curriculum) {
            $curriculum['created_at'] = date('Y-m-d H:i:s');
            $this->db->table('tutor_curricula')->insert($curriculum);
        }

        // Tutor certificates
        $certificates = [
            ['tutor_id' => $tutorIds[$userIds['grace_mwase']], 'certificate_name' => 'Teaching Certificate'],
            ['tutor_id' => $tutorIds[$userIds['grace_mwase']], 'certificate_name' => 'Cambridge Certification'],
            ['tutor_id' => $tutorIds[$userIds['thomas_banda']], 'certificate_name' => 'Teaching Diploma'],
            ['tutor_id' => $tutorIds[$userIds['esther_kamwendo']], 'certificate_name' => 'PhD in Chemistry'],
            ['tutor_id' => $tutorIds[$userIds['esther_kamwendo']], 'certificate_name' => 'Cambridge Examiner Certificate'],
            ['tutor_id' => $tutorIds[$userIds['patricia_jere']], 'certificate_name' => 'Accounting Diploma'],
            ['tutor_id' => $tutorIds[$userIds['james_manda']], 'certificate_name' => 'Primary Education Certificate'],
        ];

        foreach ($certificates as $cert) {
            $cert['created_at'] = date('Y-m-d H:i:s');
            $this->db->table('tutor_certificates')->insert($cert);
        }

        // Tutor references
        $references = [
            ['tutor_id' => $tutorIds[$userIds['grace_mwase']], 'referee_name' => 'Mr. James Banda', 'position' => 'Head Teacher', 'phone' => '+265999111222'],
            ['tutor_id' => $tutorIds[$userIds['grace_mwase']], 'referee_name' => 'Mrs. Susan Chilumba', 'position' => 'Senior Teacher', 'phone' => '+265888222333'],
            ['tutor_id' => $tutorIds[$userIds['grace_mwase']], 'referee_name' => 'Mr. Thomas Moyo', 'position' => 'Education Officer', 'phone' => '+265999333444'],
            ['tutor_id' => $tutorIds[$userIds['thomas_banda']], 'referee_name' => 'Ms. Patricia Jere', 'position' => 'Head Teacher', 'phone' => '+265999444555'],
            ['tutor_id' => $tutorIds[$userIds['james_manda']], 'referee_name' => 'Ms. Grace Mwase', 'position' => 'Head Teacher', 'phone' => '+265999123456'],
        ];

        foreach ($references as $ref) {
            $this->db->table('tutor_references')->insert($ref);
        }

        // Tutor availability
        $availabilitySlots = [
            ['tutor_id' => $tutorIds[$userIds['grace_mwase']], 'day_of_week' => 'Monday', 'time_slot' => 'Morning, Afternoon'],
            ['tutor_id' => $tutorIds[$userIds['grace_mwase']], 'day_of_week' => 'Wednesday', 'time_slot' => 'Evening'],
            ['tutor_id' => $tutorIds[$userIds['grace_mwase']], 'day_of_week' => 'Saturday', 'time_slot' => 'Flexible'],
            ['tutor_id' => $tutorIds[$userIds['thomas_banda']], 'day_of_week' => 'Tuesday', 'time_slot' => 'Afternoon'],
            ['tutor_id' => $tutorIds[$userIds['thomas_banda']], 'day_of_week' => 'Thursday', 'time_slot' => 'Morning'],
            ['tutor_id' => $tutorIds[$userIds['esther_kamwendo']], 'day_of_week' => 'Monday', 'time_slot' => 'Evening'],
            ['tutor_id' => $tutorIds[$userIds['esther_kamwendo']], 'day_of_week' => 'Tuesday', 'time_slot' => 'Evening'],
            ['tutor_id' => $tutorIds[$userIds['esther_kamwendo']], 'day_of_week' => 'Wednesday', 'time_slot' => 'Evening'],
            ['tutor_id' => $tutorIds[$userIds['esther_kamwendo']], 'day_of_week' => 'Thursday', 'time_slot' => 'Evening'],
            ['tutor_id' => $tutorIds[$userIds['esther_kamwendo']], 'day_of_week' => 'Friday', 'time_slot' => 'Evening'],
        ];

        foreach ($availabilitySlots as $slot) {
            $slot['created_at'] = date('Y-m-d H:i:s');
            $this->db->table('tutor_availability')->insert($slot);
        }

        // Sample bookings
        $bookings = [
            [
                'tutor_id' => $tutorIds[$userIds['grace_mwase']],
                'student_id' => $userIds['student_parent'],
                'parent_name' => 'Mary Chibambo',
                'parent_phone' => '+265999678901',
                'parent_email' => 'parent@tutorconnectmw.com',
                'student_name' => 'John Chibambo',
                'student_level' => 'Form 4',
                'subjects_needed' => 'Mathematics, Physics',
                'booking_date' => date('Y-m-d', strtotime('+7 days')),
                'booking_time' => '2:00 PM',
                'session_type' => 'one-off',
                'status' => 'confirmed',
                'notes' => 'MSCE preparation tutoring needed',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'tutor_id' => $tutorIds[$userIds['thomas_banda']],
                'student_id' => $userIds['student_parent'],
                'parent_name' => 'Mary Chibambo',
                'parent_phone' => '+265999678901',
                'parent_email' => 'parent@tutorconnectmw.com',
                'student_name' => 'Jane Chibambo',
                'student_level' => 'Standard 7',
                'subjects_needed' => 'English Language, Chichewa',
                'booking_date' => date('Y-m-d', strtotime('+3 days')),
                'booking_time' => '3:30 PM',
                'session_type' => 'package',
                'status' => 'pending',
                'notes' => 'Weekly English tutoring',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        foreach ($bookings as $booking) {
            $this->db->table('bookings')->insert($booking);
        }

        // Sample reviews
        $reviews = [
            [
                'booking_id' => 1,
                'reviewer_name' => 'Mary Chibambo',
                'rating' => 4.8,
                'comment' => 'Dr. Mwase helped my son improve his mathematics grade from D to A in just 3 months. Her teaching methods are exceptional!',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'booking_id' => 1,
                'reviewer_name' => 'Susan Chilumba',
                'rating' => 4.9,
                'comment' => 'Professional and knowledgeable. My son feels much more confident in his physics exams now.',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        foreach ($reviews as $review) {
            $this->db->table('reviews')->insert($review);
        }
    }
}
