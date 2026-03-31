<?php

namespace App\Controllers;

class Training extends BaseController
{
    protected $courseModel;

    public function __construct()
    {
        helper(['url', 'form']);
    }

    public function index()
    {
        $data = [
            'title' => 'ICT Training Programs - TutorConnect Malawi',
            'courses' => [
                [
                    'id' => 1,
                    'title' => 'Web Development Fundamentals',
                    'description' => 'Learn modern web technologies including HTML, CSS, JavaScript, and PHP basics.',
                    'duration' => '8 weeks',
                    'level' => 'Beginner',
                    'price' => 25000, // MWK
                    'image' => 'web-dev.jpg'
                ],
                [
                    'id' => 2,
                    'title' => 'Data Analysis with Python',
                    'description' => 'Master data manipulation, visualization, and analysis using Python and its libraries.',
                    'duration' => '6 weeks',
                    'level' => 'Intermediate',
                    'price' => 35000,
                    'image' => 'python-data.jpg'
                ],
                [
                    'id' => 3,
                    'title' => 'Cybersecurity Essentials',
                    'description' => 'Understand network security, encryption, and digital protection strategies.',
                    'duration' => '10 weeks',
                    'level' => 'Advanced',
                    'price' => 45000,
                    'image' => 'cybersecurity.jpg'
                ],
                [
                    'id' => 4,
                    'title' => 'Mobile App Development',
                    'description' => 'Build cross-platform mobile applications using React Native.',
                    'duration' => '12 weeks',
                    'level' => 'Advanced',
                    'price' => 50000,
                    'image' => 'mobile-dev.jpg'
                ]
            ]
        ];

        return view('training/index', $data);
    }

    public function courses()
    {
        // For authenticated students, show enrolled courses
        // For public, show available courses
        return $this->index(); // Same as index for now
    }

    public function viewCourse($id)
    {
        $courses = [
            1 => [
                'title' => 'Web Development Fundamentals',
                'description' => 'Leverage modern web technologies including HTML5, CSS3, JavaScript ES6+, and PHP fundamentals.',
                'duration' => '8 weeks',
                'level' => 'Beginner',
                'price' => 25000,
                'modules' => [
                    'HTML5 & Semantic Markup',
                    'CSS3 & Responsive Design',
                    'JavaScript Fundamentals',
                    'DOM Manipulation',
                    'Async Programming',
                    'PHP Basics',
                    'MySQL Integration',
                    'Project: Personal Portfolio'
                ]
            ],
            2 => [
                'title' => 'Data Analysis with Python',
                'description' => 'Comprehensive data analysis skills using Python, NumPy, Pandas, and visualization libraries.',
                'duration' => '6 weeks',
                'level' => 'Intermediate',
                'price' => 35000,
                'modules' => [
                    'Python Programming Basics',
                    'NumPy for Scientific Computing',
                    'Pandas for Data Manipulation',
                    'Data Visualization with Matplotlib',
                    'Statistical Analysis',
                    'Real-world Case Studies'
                ]
            ],
            3 => [
                'title' => 'Cybersecurity Essentials',
                'description' => 'Critical cybersecurity knowledge including threat analysis, encryption, and security protocols.',
                'duration' => '10 weeks',
                'level' => 'Advanced',
                'price' => 45000,
                'modules' => [
                    'Networking Fundamentals',
                    'Threat Landscape',
                    'Cryptography & Encryption',
                    'Web Security',
                    'Network Security',
                    'Incident Response',
                    'Ethical Hacking'
                ]
            ],
            4 => [
                'title' => 'Mobile App Development',
                'description' => 'Create powerful mobile applications for iOS and Android using React Native.',
                'duration' => '12 weeks',
                'level' => 'Advanced',
                'price' => 50000,
                'modules' => [
                    'React Native Fundamentals',
                    'Components & State Management',
                    'Navigation & Routing',
                    'Mobile UI/UX Design',
                    'Native APIs Integration',
                    'App Store Publishing',
                    'Advanced Features'
                ]
            ]
        ];

        if (!isset($courses[$id])) {
            return redirect()->to(base_url('training'));
        }

        $data = [
            'title' => 'Course Detail - ' . $courses[$id]['title'],
            'course' => $courses[$id]
        ];

        return view('training/detail', $data);
    }
}

