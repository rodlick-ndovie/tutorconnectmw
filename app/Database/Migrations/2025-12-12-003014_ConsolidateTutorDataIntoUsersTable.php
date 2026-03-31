<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ConsolidateTutorDataIntoUsersTable extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();

        // Add all tutor-related columns to users table
        $this->forge->addColumn('users', [
            // Basic tutor information
            'district' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
                'after' => 'deleted_at',
            ],
            'area' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
                'after' => 'district',
            ],
            'experience_years' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'default' => 1,
                'after' => 'area',
            ],
            'rate' => [
                'type' => 'DECIMAL',
                'constraint' => '8,2',
                'null' => true,
                'after' => 'experience_years',
            ],
            'hourly_rate' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
                'after' => 'rate',
            ],
            'rate_type' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true,
                'default' => 'per session',
                'after' => 'hourly_rate',
            ],
            'teaching_mode' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
                'default' => 'Both Online & Physical',
                'after' => 'rate_type',
            ],
            'bio' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'teaching_mode',
            ],
            'whatsapp_number' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true,
                'after' => 'profile_picture',
            ],
            'phone_visible' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'null' => true,
                'default' => 1,
                'after' => 'whatsapp_number',
            ],
            'email_visible' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'null' => true,
                'default' => 0,
                'after' => 'phone_visible',
            ],
            'best_call_time' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
                'default' => 'Morning (8AM-12PM)',
                'after' => 'email_visible',
            ],
            'preferred_contact_method' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true,
                'default' => 'phone',
                'after' => 'best_call_time',
            ],
            'is_verified' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'null' => true,
                'default' => 0,
                'after' => 'preferred_contact_method',
            ],
            'registration_completed' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'null' => true,
                'default' => 0,
                'after' => 'is_verified',
            ],
            'is_employed' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'null' => true,
                'default' => 0,
                'after' => 'registration_completed',
            ],
            'school_name' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
                'after' => 'is_employed',
            ],
            'subscription_plan' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true,
                'default' => 'Basic',
                'after' => 'school_name',
            ],
            'subscription_expires_at' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'subscription_plan',
            ],
            'rating' => [
                'type' => 'DECIMAL',
                'constraint' => '2,1',
                'null' => true,
                'default' => '0.0',
                'after' => 'subscription_expires_at',
            ],
            'review_count' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'default' => 0,
                'after' => 'rating',
            ],
            'search_count' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'default' => 0,
                'after' => 'review_count',
            ],
            'featured' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'null' => true,
                'default' => 0,
                'after' => 'search_count',
            ],
            'tutor_status' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true,
                'default' => 'pending',
                'after' => 'featured',
            ],
            'verification_documents' => [
                'type' => 'JSON',
                'null' => true,
                'after' => 'tutor_status',
            ],
            'subjects' => [
                'type' => 'JSON',
                'null' => true,
                'after' => 'verification_documents',
            ],
            'education_levels' => [
                'type' => 'JSON',
                'null' => true,
                'after' => 'subjects',
            ],
            'availability' => [
                'type' => 'JSON',
                'null' => true,
                'after' => 'education_levels',
            ],
        ]);

        // Migrate existing tutor data to users table
        $this->migrateTutorData();

        // Log the migration
        log_message('info', 'Tutor data consolidation migration completed');
    }

    public function down()
    {
        // Remove all added tutor columns
        $columnsToDrop = [
            'district', 'area', 'experience_years', 'rate', 'hourly_rate', 'rate_type',
            'teaching_mode', 'bio', 'profile_picture', 'whatsapp_number', 'phone_visible',
            'email_visible', 'best_call_time', 'preferred_contact_method', 'is_verified',
            'registration_completed', 'is_employed', 'school_name', 'subscription_plan',
            'subscription_expires_at', 'rating', 'review_count', 'search_count', 'featured',
            'tutor_status', 'verification_documents', 'subjects', 'education_levels', 'availability'
        ];

        $this->forge->dropColumn('users', $columnsToDrop);

        log_message('info', 'Tutor data consolidation migration rolled back');
    }

    private function migrateTutorData()
    {
        $db = \Config\Database::connect();

        // Get all tutors with their associated user data
        $tutors = $db->table('tutors')
            ->join('users', 'users.id = tutors.user_id')
            ->where('users.role', 'trainer')
            ->get()
            ->getResultArray();

        foreach ($tutors as $tutor) {
            // Gather subjects
            $subjects = $db->table('tutor_subjects')
                ->where('tutor_id', $tutor['id'])
                ->get()
                ->getResultArray();

            // Gather education levels
            $levels = $db->table('tutor_levels')
                ->where('tutor_id', $tutor['id'])
                ->get()
                ->getResultArray();

            // Gather documents
            $documents = $db->table('tutor_documents')
                ->where('tutor_id', $tutor['id'])
                ->get()
                ->getResultArray();

            // Gather availability
            $availability = $db->table('tutor_availability')
                ->where('tutor_id', $tutor['id'])
                ->get()
                ->getResultArray();

            // Prepare update data for users table
            $updateData = [
                'district' => $tutor['district'],
                'area' => $tutor['area'],
                'experience_years' => $tutor['experience_years'],
                'rate' => $tutor['rate'],
                'hourly_rate' => $tutor['hourly_rate'],
                'rate_type' => $tutor['rate_type'],
                'teaching_mode' => $tutor['teaching_mode'],
                'bio' => $tutor['bio'],
                'profile_picture' => $tutor['profile_picture'],
                'whatsapp_number' => $tutor['whatsapp_number'],
                'phone_visible' => $tutor['phone_visible'],
                'email_visible' => $tutor['email_visible'],
                'best_call_time' => $tutor['best_call_time'],
                'preferred_contact_method' => $tutor['preferred_contact_method'],
                'is_verified' => $tutor['is_verified'],
                'registration_completed' => $tutor['registration_completed'] ?? 1,
                'is_employed' => $tutor['is_employed'],
                'school_name' => $tutor['school_name'],
                'subscription_plan' => $tutor['subscription_plan'],
                'subscription_expires_at' => $tutor['subscription_expires_at'],
                'rating' => $tutor['rating'],
                'review_count' => $tutor['review_count'],
                'search_count' => $tutor['search_count'],
                'featured' => $tutor['featured'],
                'tutor_status' => $tutor['status'],
                'subjects' => json_encode(array_column($subjects, 'subject_name')),
                'education_levels' => json_encode(array_column($levels, 'level_name')),
                'availability' => json_encode($availability),
                'verification_documents' => json_encode($documents),
            ];

            // Update the user record
            $db->table('users')
                ->where('id', $tutor['user_id'])
                ->update($updateData);
        }

        log_message('info', 'Migrated ' . count($tutors) . ' tutor records to users table');
    }
}
