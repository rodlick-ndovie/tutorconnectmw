<?php

namespace App\Controllers;

class Portfolio extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Project Portfolio - TutorConnect Malawi',
            'projects' => [
                [
                    'id' => 1,
                    'title' => 'E-commerce Platform for Malawi Retail',
                    'category' => 'Web Development',
                    'description' => 'Complete online shopping platform with secure payments and inventory management',
                    'technologies' => ['PHP', 'MySQL', 'Bootstrap', 'Stripe API'],
                    'client' => 'Local Retailer',
                    'completion_date' => 'November 2025',
                    'images' => ['ecommerce-1.jpg', 'ecommerce-2.jpg'],
                    'results' => '300% increase in online sales'
                ],
                [
                    'id' => 2,
                    'title' => 'Corporate Website Redesign',
                    'category' => 'Web Design',
                    'description' => 'Modern, responsive website for an established Malawi company',
                    'technologies' => ['HTML5', 'CSS3', 'JavaScript', 'CMS'],
                    'client' => 'Corporate Client',
                    'completion_date' => 'October 2025',
                    'images' => ['corporate-1.jpg', 'corporate-2.jpg'],
                    'results' => '50% improvement in user engagement'
                ],
                [
                    'id' => 3,
                    'title' => 'Mobile Banking App Prototype',
                    'category' => 'Mobile Development',
                    'description' => 'Prototype mobile application for secure banking operations',
                    'technologies' => ['React Native', 'Node.js', 'MongoDB'],
                    'client' => 'Banking Institution',
                    'completion_date' => 'September 2025',
                    'images' => ['banking-1.jpg', 'banking-2.jpg'],
                    'results' => 'Approved for development phase'
                ],
                [
                    'id' => 4,
                    'title' => 'Graphics Package for NGO Campaign',
                    'category' => 'Graphic Design',
                    'description' => 'Complete branding and marketing materials for NGO awareness campaign',
                    'technologies' => ['Adobe Creative Suite', 'Print Design'],
                    'client' => 'NGO Organization',
                    'completion_date' => 'August 2025',
                    'images' => ['ngo-1.jpg', 'ngo-2.jpg'],
                    'results' => 'Campaign reached 50K+ social media impressions'
                ]
            ],
            'testimonials' => [
                [
                    'client' => 'Mary Banda',
                    'company' => 'Ashe Wama Business',
                    'text' => 'TutorConnect Malawi transformed our online presence completely. Their e-commerce platform has revolutionized how we serve our customers.',
                    'rating' => 5
                ],
                [
                    'client' => 'David Ngwira',
                    'company' => 'TechMalawi Solutions',
                    'text' => 'Professional service with outstanding results. The mobile app prototype exceeded all expectations and timelines.',
                    'rating' => 5
                ]
            ]
        ];

        return view('portfolio/index', $data);
    }

    public function project($id)
    {
        $projects = [
            1 => [
                'title' => 'E-commerce Platform for Malawi Retail',
                'description' => 'A comprehensive e-commerce solution featuring secure payment processing, inventory management, and customer relationship management.',
                'challenge' => 'Create a scalable online platform to digitalize traditional retail operations in Malawi.',
                'solution' => 'Custom-built PHP/MySQL platform with responsive design and payment integration.',
                'features' => ['Product catalog', 'Shopping cart', 'Payment gateway', 'Order tracking', 'Admin dashboard'],
                'technologies' => ['PHP 8.1', 'MySQL', 'Bootstrap 5', 'Stripe API', 'AdminLTE'],
                'images' => ['ecommerce-1.jpg', 'ecommerce-2.jpg', 'ecommerce-3.jpg']
            ]
        ];

        if (!isset($projects[$id])) {
            return redirect()->to(base_url('portfolio'));
        }

        $data = [
            'title' => 'Project: ' . $projects[$id]['title'],
            'project' => $projects[$id]
        ];

        return view('portfolio/project', $data);
    }
}

