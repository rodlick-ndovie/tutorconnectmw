<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TutorConnectSeeder extends Seeder
{
    public function run()
    {
        // Sample data for users
        $users = [
            [
                'username' => 'admin',
                'email' => 'info@tutorconnectmw.com',
                'password_hash' => password_hash('password123', PASSWORD_ARGON2ID),
                'role' => 'admin',
                'first_name' => 'Admin',
                'last_name' => 'TutorConnect',
                'phone' => '+265992313978',
                'is_active' => 1,
                'email_verified_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username' => 'trainer1',
                'email' => 'trainer1@tutorconnectmalawi.com',
                'password_hash' => password_hash('password123', PASSWORD_ARGON2ID),
                'role' => 'trainer',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'phone' => '+265883790001',
                'is_active' => 1,
                'email_verified_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username' => 'customer1',
                'email' => 'customer1@tutorconnectmalawi.com',
                'password_hash' => password_hash('password123', PASSWORD_ARGON2ID),
                'role' => 'customer',
                'first_name' => 'Bob',
                'last_name' => 'Johnson',
                'phone' => '+265666666666',
                'is_active' => 1,
                'email_verified_at' => date('Y-m-d H:i:s'),
            ],
        ];

        foreach ($users as $user) {
            $this->db->table('users')->insert($user);
        }
    }
}
