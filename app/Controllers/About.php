<?php

namespace App\Controllers;

class About extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'About Us - TutorConnect Malawi',
            'team' => [
                [
                    'name' => 'John Banda',
                    'role' => 'CEO & Founder',
                    'bio' => 'Visionary leader with 15+ years in ICT across East Africa',
                    'image' => 'john-banda.jpg',
                    'linkedin' => '#'
                ],
                [
                    'name' => 'Grace Munthali',
                    'role' => 'Director of Operations',
                    'bio' => 'Operations expert driving excellence in project delivery',
                    'image' => 'grace-munthali.jpg',
                    'linkedin' => '#'
                ],
                [
                    'name' => 'Peter Chikanga',
                    'role' => 'Lead Developer',
                    'bio' => 'Full-stack developer specializing in modern web technologies',
                    'image' => 'peter-chikanga.jpg',
                    'linkedin' => '#'
                ],
                [
                    'name' => 'Martha Kamaza',
                    'role' => 'Senior Designer',
                    'bio' => 'Creative designer with expertise in branding and digital media',
                    'image' => 'martha-kamaza.jpg',
                    'linkedin' => '#'
                ]
            ],
            'stats' => [
                'projects' => 150,
                'clients' => 80,
                'experience' => 8,
                'expertise' => 50
            ]
        ];

        return view('about/index', $data);
    }
}

