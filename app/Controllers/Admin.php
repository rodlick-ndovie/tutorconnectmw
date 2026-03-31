<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\TutorModel;
use App\Models\SiteSettingModel;
use App\Models\ContactMessageModel;
use App\Models\JapanApplicationModel;
use App\Models\JapanApplicationAccessModel;
use App\Models\ResourceModel;
use App\Models\PastPapersModel;
use App\Models\TutorVideosModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use Config\Database;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Admin extends BaseController
{
    protected $userModel;
    protected $tutorModel;
    protected \App\Models\SubscriptionPlanModel $subscriptionPlanModel;
    protected \App\Models\TutorSubscriptionModel $tutorSubscriptionModel;
    protected \App\Models\CurriculumSubjectsModel $curriculumSubjectsModel;
    protected SiteSettingModel $siteSettingModel;
    protected ContactMessageModel $contactMessageModel;
    protected JapanApplicationModel $japanApplicationModel;
    protected JapanApplicationAccessModel $japanApplicationAccessModel;
    protected ResourceModel $resourceModel;
    protected PastPapersModel $pastPapersModel;
    protected TutorVideosModel $tutorVideosModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->tutorModel = new TutorModel();
        helper(['form', 'url']);

        // Load subscription models
        $this->subscriptionPlanModel = new \App\Models\SubscriptionPlanModel();
        $this->tutorSubscriptionModel = new \App\Models\TutorSubscriptionModel();
        $this->curriculumSubjectsModel = new \App\Models\CurriculumSubjectsModel();
        $this->siteSettingModel = new SiteSettingModel();
        $this->contactMessageModel = new ContactMessageModel();
        $this->japanApplicationModel = new JapanApplicationModel();
        $this->japanApplicationAccessModel = new JapanApplicationAccessModel();
        $this->resourceModel = new ResourceModel();
        $this->pastPapersModel = new PastPapersModel();
        $this->tutorVideosModel = new TutorVideosModel();
    }

    private function requireAdminAccess(): void
    {
        $role = session()->get('role');
        if (!in_array($role, ['admin', 'sub-admin'], true)) {
            throw PageNotFoundException::forPageNotFound();
        }
    }

    protected function getUnreadMessageCount()
    {
        return $this->contactMessageModel->getUnreadCount();
    }

    protected function ensureJapanApplicationsTable(): void
    {
        $this->japanApplicationModel->ensureTable();
    }

    protected function ensureJapanApplicationAccessTable(): void
    {
        $this->japanApplicationAccessModel->ensureTable();
    }

    protected function getAdminData(&$data = []): array
    {
        $data['unreadMessageCount'] = $this->getUnreadMessageCount();
        return $data;
    }

    private function sheetSetValue($sheet, int $column, int $row, $value): void
    {
        $cell = Coordinate::stringFromColumnIndex($column) . $row;
        $sheet->setCellValue($cell, $value);
    }

    public function users()
    {
        $data = [
            'title' => 'User Management - TutorConnect Malawi',
        ];

        // Add unread message count
        $data = $this->getAdminData($data);

        // Get all users for admin management
        $data['all_users'] = $this->userModel->findAll();
        $data['total_users'] = count($data['all_users']);
        $data['active_users'] = count(array_filter($data['all_users'], function($user) {
            return $user['is_active'] == 1;
        }));
        $data['inactive_users'] = $data['total_users'] - $data['active_users'];

        // Get user role breakdown
        $data['user_roles'] = [
            'trainers' => count(array_filter($data['all_users'], function($user) {
                return $user['role'] == 'trainer';
            })),
            'admins' => count(array_filter($data['all_users'], function($user) {
                return $user['role'] == 'admin';
            })),
            'customers' => count(array_filter($data['all_users'], function($user) {
                return $user['role'] == 'customer';
            }))
        ];

        // Get recent users (last 10)
        $data['recent_users'] = array_slice(array_reverse($data['all_users']), 0, 10);

        return view('admin/users', $data);
    }

    public function exportUsersExcel()
    {
        $this->requireAdminAccess();

        $users = $this->userModel->orderBy('created_at', 'DESC')->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            'No.',
            'First Name',
            'Last Name',
            'Username',
            'Email',
            'Phone',
            'Role',
            'Active',
            'Verified',
            'Tutor Status',
            'District',
            'Location',
            'Gender',
            'Created At',
        ];

        foreach ($headers as $colIndex => $header) {
            $this->sheetSetValue($sheet, $colIndex + 1, 1, $header);
        }

        $row = 2;
        $no = 1;
        foreach ($users as $user) {
            $this->sheetSetValue($sheet, 1, $row, $no);
            $this->sheetSetValue($sheet, 2, $row, (string) ($user['first_name'] ?? ''));
            $this->sheetSetValue($sheet, 3, $row, (string) ($user['last_name'] ?? ''));
            $this->sheetSetValue($sheet, 4, $row, (string) ($user['username'] ?? ''));
            $this->sheetSetValue($sheet, 5, $row, (string) ($user['email'] ?? ''));
            $this->sheetSetValue($sheet, 6, $row, (string) ($user['phone'] ?? ''));
            $this->sheetSetValue($sheet, 7, $row, (string) ($user['role'] ?? ''));
            $this->sheetSetValue($sheet, 8, $row, ((int) ($user['is_active'] ?? 0)) === 1 ? 'Yes' : 'No');
            $this->sheetSetValue($sheet, 9, $row, ((int) ($user['is_verified'] ?? 0)) === 1 ? 'Yes' : 'No');
            $this->sheetSetValue($sheet, 10, $row, (string) ($user['tutor_status'] ?? ''));
            $this->sheetSetValue($sheet, 11, $row, (string) ($user['district'] ?? ''));
            $this->sheetSetValue($sheet, 12, $row, (string) ($user['location'] ?? ''));
            $this->sheetSetValue($sheet, 13, $row, (string) ($user['gender'] ?? ''));
            $this->sheetSetValue($sheet, 14, $row, (string) ($user['created_at'] ?? ''));
            $row++;
            $no++;
        }

        $sheet->freezePane('A2');

        $writer = new Xlsx($spreadsheet);
        ob_start();
        $writer->save('php://output');
        $binary = ob_get_clean();

        $filename = 'users_' . date('Ymd_His') . '.xlsx';

        return $this->response
            ->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setHeader('Cache-Control', 'max-age=0')
            ->setBody($binary);
    }

    public function exportUsersPdf()
    {
        $this->requireAdminAccess();

        $users = $this->userModel->orderBy('created_at', 'DESC')->findAll();

        $html = view('admin/users_export_pdf', [
            'users' => $users,
        ]);

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $pdf = $dompdf->output();
        $filename = 'users_' . date('Ymd_His') . '.pdf';

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setHeader('Cache-Control', 'max-age=0')
            ->setBody($pdf);
    }

    public function exportTutorsExcel()
    {
        $this->requireAdminAccess();

        $tutors = $this->userModel->where('role', 'trainer')->orderBy('created_at', 'DESC')->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            'No.',
            'Full Name',
            'Email',
            'Phone',
            'District',
            'Teaching Mode',
            'Experience Years',
            'Tutor Status',
            'Active',
            'Verified',
            'Rating',
            'Reviews',
            'Joined',
        ];

        foreach ($headers as $colIndex => $header) {
            $this->sheetSetValue($sheet, $colIndex + 1, 1, $header);
        }

        $row = 2;
        $no = 1;
        foreach ($tutors as $tutor) {
            $fullName = trim((string) (($tutor['first_name'] ?? '') . ' ' . ($tutor['last_name'] ?? '')));
            $this->sheetSetValue($sheet, 1, $row, $no);
            $this->sheetSetValue($sheet, 2, $row, $fullName);
            $this->sheetSetValue($sheet, 3, $row, (string) ($tutor['email'] ?? ''));
            $this->sheetSetValue($sheet, 4, $row, (string) ($tutor['phone'] ?? ''));
            $this->sheetSetValue($sheet, 5, $row, (string) ($tutor['district'] ?? ''));
            $this->sheetSetValue($sheet, 6, $row, (string) ($tutor['teaching_mode'] ?? ''));
            $this->sheetSetValue($sheet, 7, $row, (string) ($tutor['experience_years'] ?? ''));
            $this->sheetSetValue($sheet, 8, $row, (string) ($tutor['tutor_status'] ?? ''));
            $this->sheetSetValue($sheet, 9, $row, ((int) ($tutor['is_active'] ?? 0)) === 1 ? 'Yes' : 'No');
            $this->sheetSetValue($sheet, 10, $row, ((int) ($tutor['is_verified'] ?? 0)) === 1 ? 'Yes' : 'No');
            $this->sheetSetValue($sheet, 11, $row, (string) ($tutor['rating'] ?? ''));
            $this->sheetSetValue($sheet, 12, $row, (string) ($tutor['review_count'] ?? ''));
            $this->sheetSetValue($sheet, 13, $row, (string) ($tutor['created_at'] ?? ''));
            $row++;
            $no++;
        }

        $sheet->freezePane('A2');

        $writer = new Xlsx($spreadsheet);
        ob_start();
        $writer->save('php://output');
        $binary = ob_get_clean();

        $filename = 'tutors_' . date('Ymd_His') . '.xlsx';

        return $this->response
            ->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setHeader('Cache-Control', 'max-age=0')
            ->setBody($binary);
    }

    public function exportTutorsPdf()
    {
        $this->requireAdminAccess();

        $tutors = $this->userModel->where('role', 'trainer')->orderBy('created_at', 'DESC')->findAll();

        $html = view('admin/trainers_export_pdf', [
            'tutors' => $tutors,
        ]);

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $pdf = $dompdf->output();
        $filename = 'tutors_' . date('Ymd_His') . '.pdf';

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setHeader('Cache-Control', 'max-age=0')
            ->setBody($pdf);
    }

    public function checkField()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['exists' => false]);
        }

        $json = $this->request->getJSON();
        $field = $json->field ?? '';
        $value = $json->value ?? '';

        if (empty($field) || empty($value)) {
            return $this->response->setJSON(['exists' => false]);
        }

        // Check if the field value already exists in database
        $exists = $this->userModel->where($field, $value)->first();

        $messages = [
            'email' => 'This email is already registered in the system',
            'username' => 'This username is already taken',
            'phone' => 'This phone number is already registered in the system'
        ];

        return $this->response->setJSON([
            'exists' => $exists ? true : false,
            'message' => $exists ? ($messages[$field] ?? 'This value already exists') : ''
        ]);
    }

    public function createAdmin()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'first_name' => [
                'rules' => 'required|min_length[2]|max_length[100]',
                'errors' => [
                    'required' => 'First name is required',
                    'min_length' => 'First name must be at least 2 characters'
                ]
            ],
            'last_name' => [
                'rules' => 'required|min_length[2]|max_length[100]',
                'errors' => [
                    'required' => 'Last name is required',
                    'min_length' => 'Last name must be at least 2 characters'
                ]
            ],
            'email' => [
                'rules' => 'required|valid_email|is_unique[users.email]',
                'errors' => [
                    'required' => 'Email is required',
                    'valid_email' => 'Please enter a valid email address',
                    'is_unique' => 'This email is already registered in the system'
                ]
            ],
            'username' => [
                'rules' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
                'errors' => [
                    'required' => 'Username is required',
                    'min_length' => 'Username must be at least 3 characters',
                    'is_unique' => 'This username is already taken'
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[8]',
                'errors' => [
                    'required' => 'Password is required',
                    'min_length' => 'Password must be at least 8 characters'
                ]
            ]
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => implode(', ', $validation->getErrors())
            ]);
        }

        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'email' => $this->request->getPost('email'),
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => 'admin',
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ];

        try {
            if ($this->userModel->insert($data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Admin created successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to create admin'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error creating admin: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ]);
        }
    }

    public function createTrainer()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'first_name' => [
                'rules' => 'required|min_length[2]|max_length[100]',
                'errors' => [
                    'required' => 'First name is required',
                    'min_length' => 'First name must be at least 2 characters'
                ]
            ],
            'last_name' => [
                'rules' => 'required|min_length[2]|max_length[100]',
                'errors' => [
                    'required' => 'Last name is required',
                    'min_length' => 'Last name must be at least 2 characters'
                ]
            ],
            'email' => [
                'rules' => 'required|valid_email|is_unique[users.email]',
                'errors' => [
                    'required' => 'Email is required',
                    'valid_email' => 'Please enter a valid email address',
                    'is_unique' => 'This email is already registered in the system'
                ]
            ],
            'username' => [
                'rules' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
                'errors' => [
                    'required' => 'Username is required',
                    'min_length' => 'Username must be at least 3 characters',
                    'is_unique' => 'This username is already taken'
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[8]',
                'errors' => [
                    'required' => 'Password is required',
                    'min_length' => 'Password must be at least 8 characters'
                ]
            ],
            'phone' => [
                'rules' => 'required|min_length[9]|max_length[15]|is_unique[users.phone]',
                'errors' => [
                    'required' => 'Phone number is required',
                    'min_length' => 'Phone number must be at least 9 digits',
                    'is_unique' => 'This phone number is already registered in the system'
                ]
            ],
            'district' => [
                'rules' => 'required',
                'errors' => ['required' => 'District is required']
            ],
            'location' => [
                'rules' => 'required',
                'errors' => ['required' => 'Location is required']
            ],
            'gender' => [
                'rules' => 'required|in_list[Male,Female,Other]',
                'errors' => [
                    'required' => 'Gender is required',
                    'in_list' => 'Please select a valid gender'
                ]
            ]
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => implode(', ', $validation->getErrors())
            ]);
        }

        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'email' => $this->request->getPost('email'),
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'phone' => $this->request->getPost('phone'),
            'district' => $this->request->getPost('district'),
            'location' => $this->request->getPost('location'),
            'gender' => $this->request->getPost('gender'),
            'role' => 'trainer',
            'is_active' => 1,
            'uploaded_by' => session()->get('user_id') ?: 1, // Admin user ID
            'uploaded_at' => date('Y-m-d H:i:s'),
            'copyright_notice' => $description ?: null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        try {
            if ($this->userModel->insert($data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Trainer created successfully. They can log in and complete their profile.'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to create trainer'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error creating trainer: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ]);
        }
    }

    public function courses()
    {
        $data = [
            'title' => 'Course Management - TutorConnect Malawi',
        ];

        // Since we don't have a courses table yet, this will be empty
        $data['courses'] = [];
        $data['courses_summary'] = [
            'total_courses' => 0,
            'active_courses' => 0,
            'total_students' => 0,
            'avg_rating' => 0.0
        ];

        return view('admin/courses', $data);
    }

    public function trainers()
    {
        $data = [
            'title' => 'Trainer Management - TutorConnect Malawi',
        ];

        // Get all trainers from consolidated users table
        $trainers = $this->userModel->where('role', 'trainer')->findAll();

        $data['trainers'] = $trainers;
        $data['total_trainers'] = count($trainers);

        // Count active trainers (both approved AND not pending status)
        $data['active_trainers'] = count(array_filter($trainers, function($trainer) {
            return $trainer['is_active'] == 1 && $trainer['tutor_status'] === 'approved';
        }));

        // Count verified trainers (tutor_status = 'approved')
        $approvedCount = count(array_filter($trainers, function($trainer) {
            return ($trainer['tutor_status'] ?? '') === 'approved';
        }));

        // Debug logging with more details
        log_message('info', '=== TRAINER STATISTICS ===');
        log_message('info', 'Total trainers: ' . $data['total_trainers']);
        log_message('info', 'Active trainers: ' . $data['active_trainers']);
        log_message('info', 'Approved trainers: ' . $approvedCount);

        // Log details of each trainer
        foreach ($trainers as $trainer) {
            log_message('info', sprintf(
                'Tutor ID %d: %s %s - Status: %s, is_verified: %s, tutor_status: %s',
                $trainer['id'],
                $trainer['first_name'] ?? 'Unknown',
                $trainer['last_name'] ?? 'Unknown',
                $trainer['is_active'] ? 'Active' : 'Inactive',
                $trainer['is_verified'] ?? 'NULL',
                $trainer['tutor_status'] ?? 'NULL'
            ));
        }

        $data['stats'] = [
            'total_trainers' => $data['total_trainers'],
            'approved_trainers' => $approvedCount,
            'avg_rating' => round(array_sum(array_column($trainers, 'rating')) / max(1, $data['total_trainers']), 1),
            'pending_verification' => count(array_filter($trainers, function($trainer) {
                return $trainer['tutor_status'] === 'pending';
            }))
        ];

        return view('admin/trainers', $data);
    }

    // View tutor details - now from consolidated users table
    public function viewTutor($userId)
    {
        $tutor = $this->userModel->where('id', $userId)->where('role', 'trainer')->first();
        if (!$tutor) {
            return redirect()->to('admin/trainers')->with('error', 'Tutor not found.');
        }

        // Process JSON fields from the consolidated table
        $structuredSubjects = json_decode($tutor['structured_subjects'] ?? '[]', true) ?: [];
        $availability = json_decode($tutor['availability'] ?? '[]', true) ?: [];
        $verificationDocuments = json_decode($tutor['verification_documents'] ?? '[]', true) ?: [];

        // Prepare all documents array (fix the key name to match what view expects)
        $allDocuments = [];
        if (!empty($tutor['profile_picture'])) {
            $allDocuments[] = [
                'document_type' => 'profile_photo',
                'name' => 'Profile Picture',
                'file_path' => $tutor['profile_picture'],
                'original_filename' => basename($tutor['profile_picture']),
                'uploaded_at' => $tutor['created_at'],
                'download_url' => base_url($tutor['profile_picture'])
            ];
        }
        if (!empty($verificationDocuments)) {
            foreach ($verificationDocuments as $doc) {
                $allDocuments[] = [
                    'document_type' => $doc['document_type'] ?? 'document',
                    'name' => ucfirst(str_replace('_', ' ', $doc['document_type'] ?? 'document')),
                    'file_path' => $doc['file_path'] ?? '',
                    'original_filename' => $doc['original_filename'] ?? 'Unknown',
                    'uploaded_at' => $doc['uploaded_at'] ?? $tutor['created_at'],
                    'download_url' => base_url($doc['file_path'] ?? '')
                ];
            }
        }

        // Structure the tutor data from consolidated users table
        $tutorData = [
            'id' => $tutor['id'],
            'district' => $tutor['district'] ?? 'Not specified',
            'area' => $tutor['area'] ?? '',
            'experience_years' => $tutor['experience_years'] ?? 0,
            'rate' => $tutor['rate'] ?? 0,
            'hourly_rate' => $tutor['hourly_rate'] ?? 0,
            'teaching_mode' => $tutor['teaching_mode'] ?? 'Not specified',
            'bio' => $tutor['bio'] ?? '',
            'whatsapp_number' => $tutor['whatsapp_number'],
            'phone_visible' => $tutor['phone_visible'] ?? 1,
            'email_visible' => $tutor['email_visible'] ?? 0,
            'best_call_time' => $tutor['best_call_time'] ?? 'Anytime',
            'preferred_contact_method' => $tutor['preferred_contact_method'] ?? 'phone',
            'is_verified' => $tutor['is_verified'] ?? 0,
            'registration_completed' => $tutor['registration_completed'] ?? 1,
            'is_employed' => $tutor['is_employed'] ?? 0,
            'school_name' => $tutor['school_name'] ?? null,
            'subscription_plan' => $tutor['subscription_plan'] ?? 'Basic',
            'subscription_expires_at' => $tutor['subscription_expires_at'],
            'rating' => $tutor['rating'] ?? 0.0,
            'review_count' => $tutor['review_count'] ?? 0,
            'search_count' => $tutor['search_count'] ?? 0,
            'featured' => $tutor['featured'] ?? 0,
            'status' => $tutor['tutor_status'] ?? 'pending',
            'created_at' => $tutor['created_at'],
            'updated_at' => $tutor['updated_at'],
            'user' => $tutor,
            'structured_subjects' => $structuredSubjects,
            'documents' => $allDocuments,
            'availability' => $availability,
            'approved_at' => $tutor['approved_at'],
            'contact_preferences' => [
                'phone_visible' => $tutor['phone_visible'] ?? 1,
                'email_visible' => $tutor['email_visible'] ?? 0,
                'preferred_contact_method' => $tutor['preferred_contact_method'] ?? 'phone',
                'whatsapp_number' => $tutor['whatsapp_number'],
                'best_call_time' => $tutor['best_call_time'],
            ],
            'additional_info' => [
                'experience_years' => $tutor['experience_years'],
                'subscription_plan' => $tutor['subscription_plan'],
                'is_employed' => $tutor['is_employed'],
                'school_name' => $tutor['school_name'],
                'bio' => $tutor['bio'],
            ],
            'intro_video' => $tutor['bio_video'] ?? null,
            'cover_photo' => $tutor['cover_photo'] ?? null,
        ];

        $data = [
            'title' => 'Tutor Details - ' . ($tutor['first_name'] . ' ' . $tutor['last_name']),
            'tutor' => $tutorData,
        ];

        return view('admin/view_tutor', $data);
    }

    // Approve tutor application - now from consolidated users table
    public function approveTutor($userId)
    {
        log_message('info', 'Admin approveTutor called for userId: ' . $userId);

        // Temporarily commented out to debug form submission
        // if ($this->request->getMethod() != 'post') {
        //     log_message('error', 'approveTutor: Not a POST request');
        //     return redirect()->to('admin/trainers');
        // }

        log_message('info', 'approveTutor: POST method confirmed, looking up tutor...');
        $tutor = $this->userModel->where('id', $userId)->where('role', 'trainer')->first();
        if (!$tutor) {
            log_message('error', 'approveTutor: Tutor not found, userId: ' . $userId);
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Tutor not found.']);
            }
            return redirect()->to('admin/trainers')->with('error', 'Tutor not found.');
        }

        log_message('info', 'approveTutor: Tutor found, updating status...');

        // Update tutor status to approved in consolidated users table
        $updateData = [
            'tutor_status' => 'approved',
            'is_verified' => 1,
            'registration_completed' => 1,
            'approved_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Only activate user if they currently have is_active = 0 (pending approval)
        if ($tutor['is_active'] == 0) {
            $updateData['is_active'] = 1; // Activate the user account
        }

        if ($this->userModel->update($userId, $updateData)) {
            // Log approval action
            log_message('info', 'Admin approved tutor ' . $userId);

            // Send email confirmation to tutor
            $this->sendTutorApprovalEmail($tutor);

            // Automatically assign free trial plan for 30 days
            $tutorSubscriptionModel = new \App\Models\TutorSubscriptionModel();
            $existingSubscription = $tutorSubscriptionModel->where('user_id', $userId)->first();

            // Get the free trial plan (price_monthly = 0)
            $subscriptionPlanModel = new \App\Models\SubscriptionPlanModel();
            $freePlan = $subscriptionPlanModel->where('price_monthly', 0)->where('is_active', 1)->first();

            if ($freePlan) {
                $subscriptionData = [
                    'user_id' => $userId,
                    'plan_id' => $freePlan['id'],
                    'status' => 'active',
                    'current_period_start' => date('Y-m-d H:i:s'),
                    'current_period_end' => date('Y-m-d H:i:s', strtotime('+30 days')),
                    'cancel_at_period_end' => false,
                    'payment_method' => 'free_trial',
                    'payment_amount' => 0,
                    'payment_date' => date('Y-m-d H:i:s'),
                    'payment_status' => 'verified',
                    'trial_end' => date('Y-m-d H:i:s', strtotime('+30 days')),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                if ($existingSubscription) {
                    // Update existing subscription
                    $subscriptionData['created_at'] = $existingSubscription['created_at'];
                    $tutorSubscriptionModel->update($existingSubscription['id'], $subscriptionData);
                    log_message('info', 'Updated existing subscription for tutor ' . $userId . ' with free trial plan');
                } else {
                    // Create new subscription
                    $subscriptionData['created_at'] = date('Y-m-d H:i:s');
                    $tutorSubscriptionModel->insert($subscriptionData);
                    log_message('info', 'Created free trial subscription for tutor ' . $userId);
                }
            } else {
                log_message('warning', 'Free trial plan not found - tutor approved but no subscription created');
            }

            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => true, 'message' => 'Tutor approved successfully! Email confirmation sent. They now have 30 days of free access.']);
            }
            return redirect()->to('admin/trainers')->with('success', 'Tutor approved successfully! Email confirmation sent. They now have 30 days of free access.');
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to approve tutor.']);
        }
        return redirect()->to('admin/trainers')->with('error', 'Failed to approve tutor.');
    }

    // Send approval email to tutor
    private function sendTutorApprovalEmail($tutor)
    {
        try {
            $emailService = \Config\Services::email();

            $emailService->setFrom('info@uprisemw.com', 'TutorConnect Malawi');
            $emailService->setTo($tutor['email']);
            $emailService->setSubject('🎉 Congratulations! Your Tutor Application Has Been Approved!');

            $dashboardUrl = base_url('dashboard');
            $profileUrl = base_url('trainer/profile');

            $htmlMessage = "
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Tutor Application Approved</title>
    <style>
        .email-container { max-width: 600px; margin: 0 auto; font-family: Arial, sans-serif; }
        .header { background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 30px; text-align: center; }
        .content { padding: 30px; background-color: #ffffff; }
        .success-icon { font-size: 48px; margin-bottom: 20px; }
        .action-button { background: #3B82F6; color: white; padding: 15px 30px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block; margin: 20px 10px; }
        .action-button:hover { background-color: #2563EB; }
        .features-list { background: #ECFDF5; border: 1px solid #10b981; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .features-list strong { color: #059669; display: block; margin-bottom: 10px; }
        .features-list ul { margin: 0; padding-left: 20px; color: #065f46; }
        .footer { background-color: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class='email-container'>
        <div class='header'>
            <h1>🎉 Congratulations!</h1>
            <p>Your tutor application has been approved</p>
        </div>
        <div class='content'>
            <h2>Welcome to TutorConnect Malawi, " . htmlspecialchars($tutor['first_name']) . "!</h2>
            <p>Your application has been reviewed and <strong>approved</strong>! You are now officially part of our tutoring community.</p>

            <div class='features-list'>
                <strong>✅ What's Available Now:</strong>
                <ul>
                    <li>Complete your profile setup</li>
                    <li>Access to student requests</li>
                    <li>30-day free trial subscription</li>
                    <li>Basic platform features</li>
                    <li>Professional dashboard</li>
                </ul>
            </div>

            <div style='background: #FEF3C7; border: 2px solid #F59E0B; padding: 15px; border-radius: 8px; margin: 20px 0;'>
                <strong>🚀 Next Steps:</strong><br>
                1. Log in to your dashboard<br>
                2. Complete your profile (if not done)<br>
                3. Set your availability and subjects<br>
                4. Start receiving student requests<br>
                5. Upgrade to premium for more features
            </div>

            <a href='" . htmlspecialchars($dashboardUrl) . "' class='action-button'>Go to Dashboard →</a>
            <a href='" . htmlspecialchars($profileUrl) . "' class='action-button'>Update Profile →</a>

            <p><em>Thank you for joining TutorConnect Malawi. We're excited to have you as part of our educational community!</em></p>

            <p>If you have any questions, feel free to contact us at <a href='mailto:info@tutorconnectmw.com'>info@tutorconnectmw.com</a></p>
        </div>
        <div class='footer'>
            <p>&copy; 2026 TutorConnect Malawi<br>
            <a href='mailto:info@tutorconnectmw.com'>info@tutorconnectmw.com</a> | +265 992 313 978</p>
        </div>
    </div>
</body>
</html>";

            $plainMessage = "Tutor Application Approved - TutorConnect Malawi

Congratulations {$tutor['first_name']}!

Your tutor application has been approved! You are now officially part of our tutoring community.

What's Available Now:
- Complete your profile setup
- Access to student requests
- 30-day free trial subscription
- Basic platform features
- Professional dashboard

Next Steps:
1. Log in to your dashboard
2. Complete your profile (if not done)
3. Set your availability and subjects
4. Start receiving student requests
5. Upgrade to premium for more features

Go to Dashboard: {$dashboardUrl}
Update Profile: {$profileUrl}

Thank you for joining TutorConnect Malawi. We're excited to have you as part of our educational community!

---
TutorConnect Malawi
info@tutorconnectmw.com | +265 992 313 978";

            $emailService->setMessage($htmlMessage);
            $emailService->setAltMessage($plainMessage);

            if ($emailService->send()) {
                log_message('info', 'Tutor approval email sent successfully to: ' . $tutor['email']);
                return true;
            } else {
                log_message('error', 'Failed to send tutor approval email to: ' . $tutor['email']);
                return false;
            }

        } catch (\Exception $e) {
            log_message('error', 'Exception sending tutor approval email: ' . $e->getMessage());
            return false;
        }
    }

    // Reject tutor application - now from consolidated users table
    public function rejectTutor($userId)
    {
        if ($this->request->getMethod() != 'post') {
            return redirect()->to('admin/trainers');
        }

        $tutor = $this->userModel->where('id', $userId)->where('role', 'trainer')->first();
        if (!$tutor) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Tutor not found.']);
            }
            return redirect()->to('admin/trainers')->with('error', 'Tutor not found.');
        }

        // Update tutor status to rejected in consolidated users table
        $updateData = [
            'tutor_status' => 'rejected',
            'is_verified' => 0,
            'registration_completed' => 1, // Mark registration as completed but rejected
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if ($this->userModel->update($userId, $updateData)) {
            // Log rejection action
            log_message('info', 'Admin rejected tutor ' . $userId . ' - Reason: Application not approved');

            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => true, 'message' => 'Tutor application rejected. They have been notified.']);
            }
            return redirect()->to('admin/trainers')->with('success', 'Tutor application rejected. They have been notified.');
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to reject tutor application.']);
        }
        return redirect()->to('admin/trainers')->with('error', 'Failed to reject tutor application.');
    }

    // Suspend tutor - deactivate their account
    public function suspendTutor($userId)
    {
        log_message('info', 'Admin suspendTutor called for userId: ' . $userId);

        // Temporarily commented out to debug form submission
        // if ($this->request->getMethod() != 'post') {
        //     log_message('error', 'suspendTutor: Not a POST request');
        //     return redirect()->to('admin/trainers');
        // }

        log_message('info', 'suspendTutor: POST method confirmed, looking up tutor...');
        $tutor = $this->userModel->where('id', $userId)->where('role', 'trainer')->first();
        if (!$tutor) {
            log_message('error', 'suspendTutor: Tutor not found, userId: ' . $userId);
            return redirect()->to('admin/trainers')->with('error', 'Tutor not found.');
        }

        log_message('info', 'suspendTutor: Updating tutor is_active to 0, current status: ' . $tutor['is_active']);
        if ($this->userModel->update($userId, [
            'is_active' => 0,
            'updated_at' => date('Y-m-d H:i:s'),
        ])) {
            log_message('info', 'Admin suspended tutor ' . $userId . ' successfully');
            return redirect()->to('admin/trainers')->with('success', 'Tutor account suspended successfully.');
        }

        log_message('error', 'suspendTutor: Failed to update database for userId: ' . $userId);
        return redirect()->to('admin/trainers')->with('error', 'Failed to suspend tutor account.');
    }

    // Activate tutor - reactivate their account
    public function activateTutor($userId)
    {
        // Temporarily commented out to debug form submission
        // if ($this->request->getMethod() != 'post') {
        //     return redirect()->to('admin/trainers');
        // }

        $tutor = $this->userModel->where('id', $userId)->where('role', 'trainer')->first();
        if (!$tutor) {
            return redirect()->to('admin/trainers')->with('error', 'Tutor not found.');
        }

        if ($this->userModel->update($userId, [
            'is_active' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
        ])) {
            log_message('info', 'Admin activated tutor ' . $userId);
            return redirect()->to('admin/trainers')->with('success', 'Tutor account activated successfully.');
        }

        return redirect()->to('admin/trainers')->with('error', 'Failed to activate tutor account.');
    }

    // Permanently delete tutor - complete removal
    public function deleteTutorPermanently($userId)
    {
        log_message('info', 'deleteTutorPermanently called for userId: ' . $userId . ', method: ' . $this->request->getMethod());
        $this->requireAdminAccess();
        log_message('info', 'Admin access verified for deleteTutorPermanently');

        $tutor = $this->userModel->withDeleted()->where('id', $userId)->where('role', 'trainer')->first();
        if (!$tutor) {
            return redirect()->to('admin/trainers')->with('error', 'Tutor not found.');
        }

        log_message('info', 'deleteTutorPermanently: Starting permanent deletion process for tutor: ' . $tutor['first_name'] . ' ' . $tutor['last_name']);

        // Delete associated files (profile picture, certificates, etc.)
        if (!empty($tutor['profile_picture'])) {
            $filePath = FCPATH . $tutor['profile_picture'];
            log_message('info', 'deleteTutorPermanently: Attempting to delete profile picture: ' . $filePath);
            if (file_exists($filePath)) {
                if (unlink($filePath)) {
                    log_message('info', 'deleteTutorPermanently: Successfully deleted profile picture');
                } else {
                    log_message('error', 'deleteTutorPermanently: Failed to delete profile picture');
                }
            }
        }

        // Delete verification documents if they exist
        $verificationDocs = json_decode($tutor['verification_documents'] ?? '[]', true) ?: [];
        log_message('info', 'deleteTutorPermanently: Found ' . count($verificationDocs) . ' verification documents');
        foreach ($verificationDocs as $doc) {
            if (!empty($doc['file_path'])) {
                $filePath = FCPATH . $doc['file_path'];
                log_message('info', 'deleteTutorPermanently: Attempting to delete verification document: ' . $filePath);
                if (file_exists($filePath)) {
                    if (unlink($filePath)) {
                        log_message('info', 'deleteTutorPermanently: Successfully deleted verification document');
                    } else {
                        log_message('error', 'deleteTutorPermanently: Failed to delete verification document');
                    }
                }
            }
        }

        // Delete tutor videos if any
        $tutorVideosModel = new \App\Models\TutorVideosModel();
        $tutorVideos = $tutorVideosModel->where('tutor_id', $userId)->findAll();
        log_message('info', 'deleteTutorPermanently: Found ' . count($tutorVideos) . ' tutor videos to delete');
        foreach ($tutorVideos as $video) {
            log_message('info', 'deleteTutorPermanently: Deleting video ID: ' . $video['id']);
            $tutorVideosModel->delete($video['id']);
        }

        // Delete tutor subscriptions
        log_message('info', 'deleteTutorPermanently: Deleting tutor subscriptions');
        $deletedSubscriptions = $this->tutorSubscriptionModel->where('user_id', $userId)->delete();
        log_message('info', 'deleteTutorPermanently: Deleted ' . $deletedSubscriptions . ' subscription records');

        // Finally delete the tutor account permanently
        log_message('info', 'deleteTutorPermanently: Attempting to permanently delete tutor account from database');
        if ($this->userModel->delete($userId, true)) { // Force permanent deletion
            log_message('info', 'Admin permanently deleted tutor ' . $userId . ' (' . $tutor['first_name'] . ' ' . $tutor['last_name'] . ')');
            log_message('info', 'deleteTutorPermanently: Permanent deletion successful, returning success response');

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Tutor account permanently deleted and removed from database.'
                ]);
            }
            return redirect()->to('admin/trainers')->with('success', 'Tutor account permanently deleted and removed from database.');
        }

        log_message('error', 'deleteTutorPermanently: Permanent database deletion failed for tutor ' . $userId);
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to permanently delete tutor account.'
            ]);
        }
        return redirect()->to('admin/trainers')->with('error', 'Failed to permanently delete tutor account.');
    }

    // Restore tutor - undo soft delete
    public function restoreTutor($userId)
    {
        log_message('info', 'restoreTutor called for userId: ' . $userId . ', method: ' . $this->request->getMethod());
        $this->requireAdminAccess();
        log_message('info', 'Admin access verified for restoreTutor');

        $tutor = $this->userModel->withDeleted()->where('id', $userId)->where('role', 'trainer')->first();
        if (!$tutor) {
            return redirect()->to('admin/trainers')->with('error', 'Tutor not found.');
        }

        // Check if tutor is actually soft deleted
        if (empty($tutor['deleted_at'])) {
            log_message('info', 'restoreTutor: Tutor ' . $userId . ' is not soft deleted');
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Tutor is not deleted.'
                ]);
            }
            return redirect()->to('admin/trainers')->with('error', 'Tutor is not deleted.');
        }

        log_message('info', 'restoreTutor: Starting tutor restoration process');

        // Restore the tutor account by clearing deleted_at
        if ($this->userModel->update($userId, ['deleted_at' => null])) {
            log_message('info', 'Admin restored tutor ' . $userId . ' (' . $tutor['first_name'] . ' ' . $tutor['last_name'] . ')');
            log_message('info', 'restoreTutor: Restoration successful, returning success response');

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Tutor account restored successfully.'
                ]);
            }
            return redirect()->to('admin/trainers')->with('success', 'Tutor account restored successfully.');
        }

        log_message('error', 'restoreTutor: Database restoration failed for tutor ' . $userId);
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to restore tutor account.'
            ]);
        }
        return redirect()->to('admin/trainers')->with('error', 'Failed to restore tutor account.');
    }

    // Delete tutor - soft delete (marks as deleted but keeps record)
    public function deleteTutor($userId)
    {
        log_message('info', 'deleteTutor called for userId: ' . $userId . ', method: ' . $this->request->getMethod());
        $this->requireAdminAccess();
        log_message('info', 'Admin access verified for deleteTutor');

        $tutor = $this->userModel->where('id', $userId)->where('role', 'trainer')->first();
        if (!$tutor) {
            return redirect()->to('admin/trainers')->with('error', 'Tutor not found.');
        }

        // Check if tutor is already soft deleted
        if (!empty($tutor['deleted_at'])) {
            log_message('info', 'deleteTutor: Tutor ' . $userId . ' is already soft deleted');
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Tutor is already deleted.'
                ]);
            }
            return redirect()->to('admin/trainers')->with('error', 'Tutor is already deleted.');
        }

        // Check if tutor has active subscriptions (we'll delete them too)
        $subscriptionModel = new \App\Models\TutorSubscriptionModel();
        $activeSubscription = $subscriptionModel->getActiveSubscription($userId);
        log_message('info', 'deleteTutor: Checking active subscriptions for userId: ' . $userId . ', found: ' . ($activeSubscription ? 'YES' : 'NO'));

        log_message('info', 'deleteTutor: Starting file deletion process');

        // Delete associated documents (profile picture, certificates, etc.)
        if (!empty($tutor['profile_picture'])) {
            $filePath = FCPATH . $tutor['profile_picture'];
            log_message('info', 'deleteTutor: Attempting to delete profile picture: ' . $filePath);
            if (file_exists($filePath)) {
                if (unlink($filePath)) {
                    log_message('info', 'deleteTutor: Successfully deleted profile picture');
                } else {
                    log_message('error', 'deleteTutor: Failed to delete profile picture');
                }
            } else {
                log_message('info', 'deleteTutor: Profile picture file does not exist');
            }
        }

        // Delete verification documents if they exist
        $verificationDocs = json_decode($tutor['verification_documents'] ?? '[]', true) ?: [];
        log_message('info', 'deleteTutor: Found ' . count($verificationDocs) . ' verification documents');
        foreach ($verificationDocs as $doc) {
            if (!empty($doc['file_path'])) {
                $filePath = FCPATH . $doc['file_path'];
                log_message('info', 'deleteTutor: Attempting to delete verification document: ' . $filePath);
                if (file_exists($filePath)) {
                    if (unlink($filePath)) {
                        log_message('info', 'deleteTutor: Successfully deleted verification document');
                    } else {
                        log_message('error', 'deleteTutor: Failed to delete verification document');
                    }
                } else {
                    log_message('info', 'deleteTutor: Verification document file does not exist');
                }
            }
        }

        // Delete tutor videos if any
        $tutorVideosModel = new \App\Models\TutorVideosModel();
        $tutorVideos = $tutorVideosModel->where('tutor_id', $userId)->findAll();
        log_message('info', 'deleteTutor: Found ' . count($tutorVideos) . ' tutor videos to delete');
        foreach ($tutorVideos as $video) {
            log_message('info', 'deleteTutor: Deleting video ID: ' . $video['id']);
            $tutorVideosModel->delete($video['id']);
        }

        // Delete tutor subscriptions
        log_message('info', 'deleteTutor: Deleting tutor subscriptions');
        $deletedSubscriptions = $subscriptionModel->where('user_id', $userId)->delete();
        log_message('info', 'deleteTutor: Deleted ' . $deletedSubscriptions . ' subscription records');

        // Finally delete the tutor account
        log_message('info', 'deleteTutor: Attempting to delete tutor account from database');
        if ($this->userModel->delete($userId)) {
            log_message('info', 'Admin permanently deleted tutor ' . $userId . ' (' . $tutor['first_name'] . ' ' . $tutor['last_name'] . ')');
            log_message('info', 'deleteTutor: Deletion successful, returning success response');

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Tutor account permanently deleted.'
                ]);
            }
            return redirect()->to('admin/trainers')->with('success', 'Tutor account permanently deleted.');
        }

        log_message('error', 'deleteTutor: Database deletion failed for tutor ' . $userId);
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to delete tutor account.'
            ]);
        }
        return redirect()->to('admin/trainers')->with('error', 'Failed to delete tutor account.');
    }

    // Request document resubmission from tutor
    public function requestDocumentResubmission($userId)
    {
        // Temporarily commented out to debug form submission
        // if ($this->request->getMethod() != 'post') {
        //     return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method.']);
        // }

        $tutor = $this->userModel->where('id', $userId)->where('role', 'trainer')->first();
        if (!$tutor) {
            return $this->response->setJSON(['success' => false, 'message' => 'Tutor not found.']);
        }

        $documents = $this->request->getPost('documents');
        $message = $this->request->getPost('message');

        if (empty($documents) || !is_array($documents)) {
            return $this->response->setJSON(['success' => false, 'message' => 'No documents selected for resubmission.']);
        }

        if (empty(trim($message))) {
            return $this->response->setJSON(['success' => false, 'message' => 'Message is required.']);
        }

        // Get current verification documents
        $verificationDocuments = json_decode($tutor['verification_documents'] ?? '[]', true) ?: [];

        // Special handling for profile_photo (stored separately from verification_documents)
        // If profile_photo is selected, we need to create a verification document entry for it
        $documentsToProcess = $documents; // Copy to modify

        if (in_array('profile_photo', $documents)) {
            // Check if profile_photo already exists in verification_documents
            $profilePhotoExists = false;
            foreach ($verificationDocuments as $doc) {
                if ($doc['document_type'] === 'profile_photo') {
                    $profilePhotoExists = true;
                    break;
                }
            }

            // If profile_photo doesn't exist in verification_documents, add it
            if (!$profilePhotoExists) {
                $verificationDocuments[] = [
                    'document_type' => 'profile_photo',
                    'file_path' => $tutor['profile_picture'] ?? '',
                    'original_filename' => $tutor['profile_picture'] ? basename($tutor['profile_picture']) : 'profile_picture.jpg',
                    'uploaded_at' => $tutor['created_at'],
                ];
                log_message('info', 'Added profile_photo to verification_documents array for resubmission');
            }
        }

        // Update documents to mark them as needing resubmission
        $documentsRequiringResubmission = [];
        $specialDocs = ['intro_video', 'profile_picture', 'cover_photo'];
        $specialDocsRequested = [];

        foreach ($documents as $docType) {
            if (in_array($docType, $specialDocs)) {
                // These are special docs not in verification_documents
                $specialDocsRequested[] = $docType;
                $documentsRequiringResubmission[] = $docType;
            }
        }

        foreach ($verificationDocuments as $key => $doc) {
            if (in_array($doc['document_type'], $documents)) {
                // Mark this document for resubmission
                $verificationDocuments[$key]['needs_resubmission'] = true;
                $verificationDocuments[$key]['resubmission_message'] = $message;
                $verificationDocuments[$key]['resubmission_requested_at'] = date('Y-m-d H:i:s');
                $documentsRequiringResubmission[] = $doc['document_type'];
            } else {
                // Clear resubmission status for other documents
                unset($verificationDocuments[$key]['needs_resubmission']);
                unset($verificationDocuments[$key]['resubmission_message']);
                unset($verificationDocuments[$key]['resubmission_requested_at']);
            }
        }

        // Generate resubmission token (valid for 5 hours)
        $token = bin2hex(random_bytes(32));
        $tokenExpiry = date('Y-m-d H:i:s', strtotime('+5 hours'));

        log_message('info', 'Generated resubmission token for user ' . $userId . ': ' . $token);
        log_message('info', 'Token expires at: ' . $tokenExpiry);

        // Use direct database query to bypass model issues
        $db = \Config\Database::connect();
        $builder = $db->table('users');

        $updateResult = $builder->where('id', $userId)->update([
            'verification_documents' => json_encode($verificationDocuments),
            'resubmission_token' => $token,
            'resubmission_token_expires' => $tokenExpiry,
            'resubmission_special_docs' => json_encode($specialDocsRequested),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        if (!$updateResult) {
            log_message('error', 'Failed to update user with resubmission token using direct query');
            log_message('error', 'Database error: ' . $db->error());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to save resubmission request. Please try again.'
            ]);
        }

        log_message('info', 'Successfully updated user ' . $userId . ' with resubmission token using direct DB query');

        // Send email notification to tutor with token link
        $emailSent = false;
        $emailError = '';
        try {
            $emailSent = $this->sendResubmissionRequestEmail($tutor, $documentsRequiringResubmission, $message, $token);
        } catch (\Exception $e) {
            $emailError = $e->getMessage();
            log_message('error', 'Exception sending resubmission email: ' . $emailError);
        }

        log_message('info', 'Admin requested resubmission of documents for tutor ' . $userId . ': ' . implode(', ', $documentsRequiringResubmission));

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Document resubmission request sent successfully. ' . ($emailSent ? 'Email notification sent.' : 'Warning: Email notification failed to send.'),
            'documents_requested' => $documentsRequiringResubmission,
            'email_sent' => $emailSent,
            'email_error' => $emailError
        ]);
    }

    public function settings()
    {
        $this->requireAdminAccess();

        $data = [
            'title' => 'System Settings - TutorConnect Malawi',
        ];

        $defaults = [
            'site_name' => 'TutorConnect Malawi',
            'site_logo' => '',
            'site_favicon' => '',
            'contact_email' => 'info@tutorconnectmw.com',
            'support_phone' => '+265 992 313 978',
            'support_address' => '',
            'timezone' => 'Africa/Blantyre',
            'refunds_email' => 'refunds@tutorconnectmw.com',
            'refund_policy_last_updated' => 'January 1, 2026',
            'social_facebook_url' => '',
            'social_twitter_url' => '',
            'social_instagram_url' => '',
            'japan_application_fee' => '10000',
            'japan_processing_fee' => '350000',
            'japan_applications_open' => '1',
            'japan_applications_closed_message' => 'Japan applications are currently closed. Please check back soon or contact support for help.',
        ];

        $settings = [];
        $data['settings_table_missing'] = false;
        try {
            foreach ($defaults as $key => $default) {
                $settings[$key] = $this->siteSettingModel->getValue($key, $default);
            }
        } catch (\Throwable $e) {
            $settings = $defaults;
            $data['settings_table_missing'] = true;
        }

        $data['settings'] = $settings;

        return view('admin/settings', $data);
    }

    public function updateSettings()
    {
        $this->requireAdminAccess();

        if (strtolower($this->request->getMethod()) !== 'post') {
            return redirect()->to('admin/settings');
        }

        $rules = [
            'site_name' => 'required|max_length[120]',
            'contact_email' => 'required|valid_email|max_length[255]',
            'support_phone' => 'permit_empty|max_length[40]',
            'support_address' => 'permit_empty|max_length[500]',
            'timezone' => 'required|max_length[60]',
            'social_facebook_url' => 'permit_empty|max_length[255]',
            'social_twitter_url' => 'permit_empty|max_length[255]',
            'social_instagram_url' => 'permit_empty|max_length[255]',
            'japan_application_fee' => 'required|numeric|greater_than_equal_to[0]',
            'japan_processing_fee' => 'required|numeric|greater_than_equal_to[0]',
            'japan_applications_open' => 'permit_empty|in_list[0,1]',
            'japan_applications_closed_message' => 'permit_empty|max_length[1000]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Please correct the form errors.')->with('validation', $this->validator)->withInput();
        }

        $keys = array_keys($rules);
        try {
            foreach ($keys as $key) {
                $value = $this->request->getPost($key);

                // For checkboxes/switches, unchecked means "not present" in POST.
                if ($key === 'japan_applications_open' && $value === null) {
                    $value = '0';
                }

                if ($value !== null) {
                    $this->siteSettingModel->setValue($key, (string) $value);
                }
            }

            // Optional logo upload
            $logo = $this->request->getFile('site_logo');
            if ($logo && $logo->isValid() && !$logo->hasMoved()) {
                $ext = strtolower((string) $logo->getClientExtension());
                $allowedExt = ['png', 'jpg', 'jpeg', 'webp'];
                if (!in_array($ext, $allowedExt, true)) {
                    return redirect()->back()->with('error', 'Logo must be a PNG, JPG, or WebP image.')->withInput();
                }

                $targetDir = rtrim(ROOTPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'uploads';
                if (!is_dir($targetDir)) {
                    @mkdir($targetDir, 0755, true);
                }

                $filename = 'site-logo-' . date('YmdHis') . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
                $logo->move($targetDir, $filename);
                $this->siteSettingModel->setValue('site_logo', $filename);
            }

            // Optional favicon upload
            $favicon = $this->request->getFile('site_favicon');
            if ($favicon && $favicon->isValid() && !$favicon->hasMoved()) {
                $ext = strtolower((string) $favicon->getClientExtension());
                $allowedExt = ['ico', 'png', 'jpg', 'jpeg', 'webp'];
                if (!in_array($ext, $allowedExt, true)) {
                    return redirect()->back()->with('error', 'Favicon must be an ICO, PNG, JPG, or WebP image.')->withInput();
                }

                $targetDir = rtrim(ROOTPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'uploads';
                if (!is_dir($targetDir)) {
                    @mkdir($targetDir, 0755, true);
                }

                $filename = 'site-favicon-' . date('YmdHis') . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
                $favicon->move($targetDir, $filename);
                $this->siteSettingModel->setValue('site_favicon', $filename);
            }
        } catch (\Throwable $e) {
            return redirect()->to('admin/settings')->with('error', 'Settings could not be saved because the site settings table is missing. Create the table (SQL) or fix migrations, then try again.');
        }

        return redirect()->to('admin/settings')->with('success', 'Settings updated successfully.');
    }

    // Subscription Plans Management
    public function subscriptions()
    {
        $data = [
            'title' => 'Subscription Plans Management - TutorConnect Malawi',
        ];

        // Get all subscription plans
        $data['plans'] = $this->subscriptionPlanModel->orderBy('sort_order', 'ASC')->findAll();
        $data['total_plans'] = count($data['plans']);
        $data['active_plans'] = count(array_filter($data['plans'], function($plan) {
            return $plan['is_active'] == 1;
        }));

        // Get subscription statistics
        $data['stats'] = [
            'total_plans' => $data['total_plans'],
            'active_plans' => $data['active_plans'],
            'inactive_plans' => $data['total_plans'] - $data['active_plans'],
            'total_revenue_potential' => array_sum(array_column($data['plans'], 'price_monthly')),
        ];

        return view('admin/subscriptions', $data);
    }

    // Add new subscription plan form
    public function addPlan()
    {
        $data = [
            'title' => 'Add Subscription Plan - TutorConnect Malawi',
        ];

        return view('admin/add_subscription_plan', $data);
    }

    // Edit subscription plan form
    public function editPlan($planId)
    {
        $plan = $this->subscriptionPlanModel->find($planId);

        if (!$plan) {
            return redirect()->to('admin/subscriptions')->with('error', 'Subscription plan not found.');
        }

        $data = [
            'title' => 'Edit Subscription Plan - TutorConnect Malawi',
            'plan' => $plan,
        ];

        return view('admin/edit_subscription_plan', $data);
    }

    // Create new subscription plan
    public function createPlan()
    {
        log_message('info', '=== SUBSCRIPTION CREATE ATTEMPT ===');
        log_message('info', 'Method: ' . $this->request->getMethod());
        log_message('info', 'POST data: ' . json_encode($this->request->getPost()));

        if (strtolower($this->request->getMethod()) != 'post') {
            log_message('error', 'createPlan: Not a POST request, redirecting');
            return redirect()->to('admin/subscriptions');
        }

        $isAjax = $this->request->isAJAX();

        $rules = [
            'name' => 'required|max_length[100]',
            'description' => 'permit_empty|max_length[500]',
            'price_monthly' => 'required|decimal|greater_than_equal_to[0]',
            'max_profile_views' => 'required|integer|greater_than_equal_to[0]',
            'max_clicks' => 'required|integer|greater_than_equal_to[0]',
            'max_subjects' => 'required|integer|greater_than_equal_to[0]',
            'sort_order' => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            log_message('error', 'Subscription plan creation failed: Validation errors - ' . json_encode($this->validator->getErrors()));
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $this->validator->getErrors()
                ]);
            }
            return redirect()->back()->with('error', 'Please correct the form errors.')->withInput();
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'price_monthly' => $this->request->getPost('price_monthly'),
            'max_profile_views' => $this->request->getPost('max_profile_views'),
            'max_clicks' => $this->request->getPost('max_clicks'),
            'max_subjects' => $this->request->getPost('max_subjects'),
            'district_spotlight_days' => $this->request->getPost('district_spotlight_days') ?? 0,
            'badge_level' => $this->request->getPost('badge_level') ?? 'none',
            'search_ranking' => $this->request->getPost('search_ranking') ?? 'low',
            'show_whatsapp' => $this->request->getPost('show_whatsapp') ? 1 : 0,
            'email_marketing_access' => $this->request->getPost('email_marketing_access') ? 1 : 0,
            'allow_video_upload' => $this->request->getPost('allow_video_upload') ? 1 : 0,
            'allow_pdf_upload' => $this->request->getPost('allow_pdf_upload') ? 1 : 0,
            'allow_announcements' => $this->request->getPost('allow_announcements') ? 1 : 0,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
            'sort_order' => $this->request->getPost('sort_order'),
        ];

        try {
            $result = $this->subscriptionPlanModel->insert($data);
            if ($result) {
                log_message('info', 'Subscription plan created successfully: ID=' . $result . ', Name="' . $data['name'] . '", Price=MK' . $data['price_monthly']);
                if ($isAjax) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Subscription plan created successfully!',
                        'redirect' => site_url('admin/subscriptions')
                    ]);
                }
                return redirect()->to('admin/subscriptions')->with('success', 'Subscription plan created successfully!');
            } else {
                log_message('error', 'Subscription plan creation failed: Database insert returned false for plan "' . $data['name'] . '"');
                if ($isAjax) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Failed to create subscription plan.'
                    ]);
                }
                return redirect()->back()->with('error', 'Failed to create subscription plan.')->withInput();
            }
        } catch (\Exception $e) {
            log_message('error', 'Subscription plan creation failed with exception: ' . $e->getMessage() . ' for plan "' . $data['name'] . '"');
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to create subscription plan: ' . $e->getMessage()
                ]);
            }
            return redirect()->back()->with('error', 'Failed to create subscription plan.')->withInput();
        }
    }

    // Update subscription plan
    public function updatePlan($planId)
    {
        log_message('info', '=== UPDATE PLAN ATTEMPT ===');
        log_message('info', 'Plan ID: ' . $planId);
        log_message('info', 'Method: ' . $this->request->getMethod());

        if (strtolower($this->request->getMethod()) != 'post') {
            log_message('error', 'updatePlan: Not a POST request, redirecting');
            return redirect()->to('admin/subscriptions');
        }

        $isAjax = $this->request->isAJAX();

        $rules = [
            'name' => 'required|max_length[100]',
            'description' => 'permit_empty|max_length[500]',
            'price_monthly' => 'required|decimal|greater_than_equal_to[0]',
            'max_profile_views' => 'required|integer|greater_than_equal_to[0]',
            'max_clicks' => 'required|integer|greater_than_equal_to[0]',
            'max_subjects' => 'required|integer|greater_than_equal_to[0]',
            'sort_order' => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            log_message('error', 'Subscription plan update failed: Validation errors for plan ID ' . $planId . ' - ' . json_encode($this->validator->getErrors()));
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $this->validator->getErrors()
                ]);
            }
            return redirect()->back()->with('error', 'Please correct the form errors.')->withInput();
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'price_monthly' => $this->request->getPost('price_monthly'),
            'max_profile_views' => $this->request->getPost('max_profile_views'),
            'max_clicks' => $this->request->getPost('max_clicks'),
            'max_subjects' => $this->request->getPost('max_subjects'),
            'district_spotlight_days' => $this->request->getPost('district_spotlight_days') ?? 0,
            'badge_level' => $this->request->getPost('badge_level') ?? 'none',
            'search_ranking' => $this->request->getPost('search_ranking') ?? 'low',
            'show_whatsapp' => $this->request->getPost('show_whatsapp') ? 1 : 0,
            'email_marketing_access' => $this->request->getPost('email_marketing_access') ? 1 : 0,
            'allow_video_upload' => $this->request->getPost('allow_video_upload') ? 1 : 0,
            'allow_pdf_upload' => $this->request->getPost('allow_pdf_upload') ? 1 : 0,
            'allow_announcements' => $this->request->getPost('allow_announcements') ? 1 : 0,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
            'sort_order' => $this->request->getPost('sort_order'),
        ];

        try {
            if ($this->subscriptionPlanModel->update($planId, $data)) {
                log_message('info', 'Subscription plan updated successfully: ID=' . $planId . ', Name="' . $data['name'] . '", Price=MK' . $data['price_monthly']);
                if ($isAjax) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Subscription plan updated successfully!',
                        'redirect' => site_url('admin/subscriptions')
                    ]);
                }
                return redirect()->to('admin/subscriptions')->with('success', 'Subscription plan updated successfully!');
            } else {
                log_message('error', 'Subscription plan update failed: Database update returned false for plan ID ' . $planId . ', Name="' . $data['name'] . '"');
                if ($isAjax) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Failed to update subscription plan.'
                    ]);
                }
                return redirect()->back()->with('error', 'Failed to update subscription plan.')->withInput();
            }
        } catch (\Exception $e) {
            log_message('error', 'Subscription plan update failed with exception: ' . $e->getMessage() . ' for plan ID ' . $planId . ', Name="' . $data['name'] . '"');
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update subscription plan: ' . $e->getMessage()
                ]);
            }
            return redirect()->back()->with('error', 'Failed to update subscription plan.')->withInput();
        }
    }

    // Get subscription plan data (for AJAX editing)
    public function getPlan($planId)
    {
        // Allow both AJAX and regular requests for flexibility
        $plan = $this->subscriptionPlanModel->find($planId);

        if (!$plan) {
            return $this->response->setJSON(['success' => false, 'message' => 'Plan not found']);
        }

        return $this->response->setJSON(['success' => true, 'plan' => $plan]);
    }

    // Delete subscription plan
    public function deletePlan($planId)
    {
        log_message('info', '=== DELETE PLAN ATTEMPT ===');
        log_message('info', 'Plan ID: ' . $planId);
        log_message('info', 'Method: ' . $this->request->getMethod());

        if (strtolower($this->request->getMethod()) != 'post') {
            log_message('error', 'deletePlan: Not a POST request, redirecting');
            return redirect()->to('admin/subscriptions');
        }

        // Check if plan has active subscriptions
        $activeSubscriptions = $this->tutorSubscriptionModel->where('plan_id', $planId)->where('status', 'active')->countAllResults();
        if ($activeSubscriptions > 0) {
            return redirect()->to('admin/subscriptions')->with('error', 'Cannot delete plan with active subscriptions.');
        }

        if ($this->subscriptionPlanModel->delete($planId)) {
            return redirect()->to('admin/subscriptions')->with('success', 'Subscription plan deleted successfully!');
        }

        return redirect()->to('admin/subscriptions')->with('error', 'Failed to delete subscription plan.');
    }

    // Tutor Subscriptions Management
    public function tutorSubscriptions()
    {
        $data = [
            'title' => 'Tutor Subscriptions Management - TutorConnect Malawi',
        ];

        // Get all tutor subscriptions with details
        $data['subscriptions'] = $this->tutorSubscriptionModel->getAllWithDetails();
        $data['total_subscriptions'] = count($data['subscriptions']);
        $data['active_subscriptions'] = count(array_filter($data['subscriptions'], function($sub) {
            return $sub['status'] == 'active';
        }));

        // Debug logging
        log_message('info', 'Tutor Subscriptions Debug:');
        log_message('info', 'Total subscriptions: ' . $data['total_subscriptions']);
        log_message('info', 'Active subscriptions: ' . $data['active_subscriptions']);
        log_message('info', 'Subscriptions data: ' . json_encode($data['subscriptions']));

        // Calculate revenue from active subscriptions only
        $activeSubscriptions = array_filter($data['subscriptions'], function($sub) {
            return $sub['status'] == 'active';
        });
        $totalMonthlyRevenue = array_sum(array_column($activeSubscriptions, 'price_monthly'));

        // Get subscription statistics
        $data['stats'] = [
            'total_subscriptions' => $data['total_subscriptions'],
            'active_subscriptions' => $data['active_subscriptions'],
            'inactive_subscriptions' => count(array_filter($data['subscriptions'], function($sub) {
                return $sub['status'] == 'inactive';
            })),
            'cancelled_subscriptions' => count(array_filter($data['subscriptions'], function($sub) {
                return $sub['status'] == 'cancelled';
            })),
            'total_monthly_revenue' => $totalMonthlyRevenue,
        ];

        log_message('info', 'Revenue calculation: ' . $totalMonthlyRevenue);
        log_message('info', 'Stats: ' . json_encode($data['stats']));

        // Get available plans for dropdown
        $data['available_plans'] = $this->subscriptionPlanModel->getActivePlans();

        return view('admin/tutor_subscriptions', $data);
    }

    // Assign subscription to tutor
    public function assignSubscription()
    {
        if ($this->request->getMethod() != 'post') {
            return redirect()->to('admin/tutor_subscriptions');
        }

        $rules = [
            'user_id' => 'required|integer',
            'plan_id' => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Invalid data provided.')->withInput();
        }

        $userId = $this->request->getPost('user_id');
        $planId = $this->request->getPost('plan_id');

        // Check if user already has an active subscription
        $existingSubscription = $this->tutorSubscriptionModel->getActiveSubscription($userId);
        if ($existingSubscription) {
            return redirect()->back()->with('error', 'User already has an active subscription.')->withInput();
        }

        // Check if plan exists and is active
        $plan = $this->subscriptionPlanModel->find($planId);
        if (!$plan || $plan['is_active'] != 1) {
            return redirect()->back()->with('error', 'Invalid or inactive subscription plan.')->withInput();
        }

        // Create new subscription
        $subscriptionData = [
            'user_id' => $userId,
            'plan_id' => $planId,
            'status' => 'active',
            'current_period_start' => date('Y-m-d H:i:s'),
            'current_period_end' => date('Y-m-d H:i:s', strtotime('+30 days')),
            'cancel_at_period_end' => false,
        ];

        if ($this->tutorSubscriptionModel->insert($subscriptionData)) {
            return redirect()->to('admin/tutor_subscriptions')->with('success', 'Subscription assigned successfully!');
        }

        return redirect()->back()->with('error', 'Failed to assign subscription.')->withInput();
    }

    // Update tutor subscription status
    public function updateSubscriptionStatus($subscriptionId)
    {
        if ($this->request->getMethod() != 'post') {
            return redirect()->to('admin/tutor_subscriptions');
        }

        $status = $this->request->getPost('status');
        $validStatuses = ['active', 'inactive', 'cancelled', 'expired'];

        if (!in_array($status, $validStatuses)) {
            return redirect()->back()->with('error', 'Invalid status provided.');
        }

        if ($this->tutorSubscriptionModel->update($subscriptionId, ['status' => $status])) {
            return redirect()->to('admin/tutor_subscriptions')->with('success', 'Subscription status updated successfully!');
        }

        return redirect()->to('admin/tutor_subscriptions')->with('error', 'Failed to update subscription status.');
    }

    // Remove tutor subscription
    public function removeSubscription($subscriptionId)
    {
        if ($this->request->getMethod() != 'post') {
            return redirect()->to('admin/tutor-subscriptions');
        }

        if ($this->tutorSubscriptionModel->delete($subscriptionId)) {
            return redirect()->to('admin/tutor-subscriptions')->with('success', 'Subscription removed successfully!');
        }

        return redirect()->to('admin/tutor-subscriptions')->with('error', 'Failed to remove subscription.');
    }





    // View payment proof for subscription
    public function viewPaymentProof($subscriptionId)
    {
        // Get subscription details
        $subscription = $this->tutorSubscriptionModel->find($subscriptionId);
        if (!$subscription) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Subscription not found.']);
            }
            return redirect()->to('admin/tutor-subscriptions')->with('error', 'Subscription not found.');
        }

        // Get user details
        $user = $this->userModel->find($subscription['user_id']);
        if (!$user) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'User not found.']);
            }
            return redirect()->to('admin/tutor-subscriptions')->with('error', 'User not found.');
        }

        // Get plan details
        $plan = $this->subscriptionPlanModel->find($subscription['plan_id']);

        $data = [
            'title' => 'Payment Proof Review - TutorConnect Malawi',
            'subscription' => $subscription,
            'user' => $user,
            'plan' => $plan,
            'proof_file' => $subscription['payment_proof_file']
        ];

        // Return just content for AJAX requests (modal)
        if ($this->request->isAJAX()) {
            return view('admin/view_payment_proof_content', $data);
        }

        // Return full page for regular requests
        return view('admin/view_payment_proof', $data);
    }

    // Admin dashboard method (using existing Dashboard controller)
    public function index()
    {
        // This will use the general dashboard routing through Dashboard::index
        // The admin role will be detected there and redirected to admin/dashboard
        return redirect()->to('admin/dashboard');
    }

    // Library Management - Past Papers and Resources
    public function library()
    {
        $pastPapersModel = new \App\Models\PastPapersModel();

        // Get all papers
        $papers = $pastPapersModel->getAllPapers();

        // Calculate stats
        $totalDownloads = 0;
        foreach ($papers as $paper) {
            $totalDownloads += (int)$paper['download_count'];
        }

        $data = [
            'title' => 'Library Management - TutorConnect Malawi',
            'papers' => $papers,
            'stats' => [
                'total_papers' => count($papers),
                'active_papers' => count(array_filter($papers, function($p) { return $p['is_active'] == 1; })),
                'inactive_papers' => count(array_filter($papers, function($p) { return $p['is_active'] == 0; })),
                'total_downloads' => $totalDownloads,
            ]
        ];

        return view('admin/library', $data);
    }

// Add new past paper
    public function addPaper()
    {
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('admin/library');
        }

        log_message('info', '========= ADD PAPER STARTED =========');

        // Get form data
        $title = $this->request->getPost('title');
        $examBody = $this->request->getPost('exam_body');
        $examLevel = $this->request->getPost('exam_level');
        $subject = $this->request->getPost('subject');
        $year = $this->request->getPost('year');
        $paperCode = $this->request->getPost('paper_code');
        $description = $this->request->getPost('description');

        log_message('info', "Form data: Title=$title, Body=$examBody, Level=$examLevel, Subject=$subject, Year=$year");

        // Validate required fields
        if (empty($title) || empty($examBody) || empty($examLevel) || empty($subject) || empty($year)) {
            log_message('error', 'Missing required fields');
            return redirect()->back()->with('error', 'All fields marked * are required')->withInput();
        }

        // Get file
        $file = $this->request->getFile('pdf_file');
        log_message('info', 'File check: ' . ($file ? 'FILE EXISTS' : 'NO FILE'));

        if (!$file || !$file->isValid()) {
            log_message('error', 'File invalid: ' . ($file ? $file->getErrorString() : 'No file uploaded'));
            return redirect()->back()->with('error', 'Please select a PDF file')->withInput();
        }

        log_message('info', 'File info: ' . $file->getName() . ' (' . $file->getSize() . ' bytes, ' . $file->getMimeType() . ')');

        // Validate file type
        if ($file->getMimeType() !== 'application/pdf') {
            log_message('error', 'Wrong file type: ' . $file->getMimeType());
            return redirect()->back()->with('error', 'Only PDF files allowed')->withInput();
        }

        // Validate file size (10MB)
        if ($file->getSize() > 10485760) {
            log_message('error', 'File too large: ' . $file->getSize());
            return redirect()->back()->with('error', 'File must be under 10MB')->withInput();
        }

        // Create upload directory
        $uploadDir = FCPATH . 'uploads/past_papers/';
        log_message('info', 'Upload directory: ' . $uploadDir);

        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0777, true)) {
                log_message('error', 'Cannot create directory');
                return redirect()->back()->with('error', 'Cannot create upload directory')->withInput();
            }
            log_message('info', 'Directory created');
        }

        // Generate filename
        $filename = time() . '_' . uniqid() . '.pdf';
        $filepath = $uploadDir . $filename;
        log_message('info', 'Target filepath: ' . $filepath);

        // Move file
        if (!$file->move($uploadDir, $filename)) {
            log_message('error', 'Failed to move file');
            return redirect()->back()->with('error', 'Failed to upload file')->withInput();
        }

        log_message('info', 'File moved successfully to: ' . $filepath);

        // Prepare database data
        $dbData = [
            'paper_title' => $title,
            'exam_body' => $examBody,
            'exam_level' => $examLevel,
            'subject' => $subject,
            'year' => (int)$year,
            'paper_code' => $paperCode ?: null,
            'file_url' => base_url('uploads/past_papers/' . $filename),
            'file_size' => (int)($file->getSize() / 1024) . 'KB',
            'download_count' => 0,
            'is_active' => 1,
            'uploaded_at' => date('Y-m-d H:i:s'),
            'copyright_notice' => $description ?: null,
        ];

        log_message('info', 'Database data prepared: ' . json_encode($dbData));

        // Insert into database
        $model = new \App\Models\PastPapersModel();
        $result = $model->insert($dbData);

        if ($result) {
            log_message('info', 'Database insert successful, ID: ' . $result);
            log_message('info', '========= ADD PAPER SUCCESS =========');
            return redirect()->to('admin/library')->with('success', 'Past paper added successfully!');
        } else {
            log_message('error', 'Database insert failed: ' . json_encode($model->errors()));
            // Delete uploaded file on database error
            if (file_exists($filepath)) {
                unlink($filepath);
                log_message('info', 'Deleted uploaded file due to database error');
            }
            return redirect()->back()->with('error', 'Failed to save paper to database')->withInput();
        }
    }

    // Test method to insert a sample past paper
    public function testInsertPaper()
    {
        $pastPapersModel = new \App\Models\PastPapersModel();

        // Test data matching the database schema
        $testData = [
            'paper_title' => 'Mathematics Paper 1 - Sample',
            'paper_code' => '1234/1',
            'exam_body' => 'Cambridge',
            'exam_level' => 'IGCSE',
            'subject' => 'Mathematics',
            'year' => 2025,
            'file_url' => base_url('uploads/past_papers/sample.pdf'),
            'file_size' => 2048576, // 2MB
            'download_count' => 0,
            'is_active' => 1,
            'uploaded_at' => date('Y-m-d H:i:s'),
            'copyright_notice' => 'Sample paper for testing purposes',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        log_message('info', 'Test insert data: ' . json_encode($testData));

        $result = $pastPapersModel->insert($testData);

        if ($result) {
            log_message('info', 'Test insert successful, ID: ' . $result);
            return redirect()->to('admin/library')->with('success', 'Test paper inserted successfully! ID: ' . $result);
        } else {
            log_message('error', 'Test insert failed');
            return redirect()->to('admin/library')->with('error', 'Test insert failed');
        }
    }


    // Delete past paper
    public function deletePaper($paperId)
    {
        if ($this->request->getMethod() != 'post') {
            return redirect()->to('admin/library');
        }

        $pastPapersModel = new \App\Models\PastPapersModel();
        $paper = $pastPapersModel->find($paperId);

        if (!$paper) {
            return redirect()->to('admin/library')->with('error', 'Past paper not found.');
        }

        // Delete the physical file if it exists
        if (!empty($paper['file_url'])) {
            // Convert URL to file path correctly
            $filePath = str_replace(base_url(), FCPATH . 'public/', $paper['file_url']);
            // Remove any double slashes and ensure proper path
            $filePath = str_replace(['//', '///'], '/', $filePath);
            log_message('info', 'Attempting to delete file at: ' . $filePath);

            if (file_exists($filePath)) {
                if (unlink($filePath)) {
                    log_message('info', 'Successfully deleted file: ' . $filePath);
                } else {
                    log_message('error', 'Failed to delete file: ' . $filePath);
                }
            } else {
                log_message('warning', 'File does not exist: ' . $filePath);
            }
        }

        if ($pastPapersModel->delete($paperId)) {
            return redirect()->to('admin/library')->with('success', 'Past paper deleted successfully!');
        }

        return redirect()->to('admin/library')->with('error', 'Failed to delete past paper.');
    }

    // Toggle paper active status
    public function togglePaperStatus($paperId)
    {
        if ($this->request->getMethod() != 'post') {
            return redirect()->to('admin/library');
        }

        $pastPapersModel = new \App\Models\PastPapersModel();
        $paper = $pastPapersModel->find($paperId);

        if (!$paper) {
            return redirect()->to('admin/library')->with('error', 'Past paper not found.');
        }

        $newStatus = $paper['is_active'] ? 0 : 1;

        if ($pastPapersModel->update($paperId, ['is_active' => $newStatus])) {
            $statusText = $newStatus ? 'activated' : 'deactivated';
            return redirect()->to('admin/library')->with('success', 'Past paper ' . $statusText . ' successfully!');
        }

        return redirect()->to('admin/library')->with('error', 'Failed to update paper status.');
    }

    // Video Queue Management
    public function videoQueue()
    {
        $data = [
            'title' => 'Video Submissions Queue - TutorConnect Malawi',
        ];

        // Get all pending video submissions
        $tutorVideosModel = new \App\Models\TutorVideosModel();
        $data['pending_videos'] = $tutorVideosModel->getPendingVideos();

        // Get statistics
        $data['stats'] = [
            'pending_count' => count($data['pending_videos']),
            'approved_today' => $tutorVideosModel->where('status', 'approved')
                                                ->where('approved_at >=', date('Y-m-d 00:00:00'))
                                                ->countAllResults(),
            'rejected_today' => $tutorVideosModel->where('status', 'rejected')
                                                ->where('updated_at >=', date('Y-m-d 00:00:00'))
                                                ->countAllResults(),
        ];

        return view('admin/video_queue', $data);
    }

    // Approve video submission
    public function approveVideo($videoId)
    {
        try {
            // Validate video ID
            if (!is_numeric($videoId) || $videoId <= 0) {
                $message = 'Invalid video ID provided.';
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $message
                ]);
            }

            $tutorVideosModel = new \App\Models\TutorVideosModel();
            $video = $tutorVideosModel->find($videoId);

            if (!$video) {
                $message = 'Video not found in database.';
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $message
                ]);
            }

            // Determine featured level based on tutor's subscription plan
            $subscriptionModel = new \App\Models\TutorSubscriptionModel();
            $subscription = $subscriptionModel->getActiveSubscription($video['tutor_id']);

            $featuredLevel = 'standard'; // Default for Standard plan
            if ($subscription) {
                $planModel = new \App\Models\SubscriptionPlanModel();
                $plan = $planModel->find($subscription['plan_id']);
                if ($plan && strtolower($plan['name']) === 'premium') {
                    $featuredLevel = 'premium_featured';
                }
            }

            // Approve the video
            try {
                $result = $tutorVideosModel->approveVideo($videoId, $featuredLevel);

                if ($result) {
                    // Send notification to tutor
                    $this->notifyTutorVideoApproval($video['tutor_id'], $video);

                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Video approved successfully!'
                    ]);
                }

                // Check for model errors
                $modelErrors = $tutorVideosModel->errors();
                $errorMessage = 'Database update failed. Video could not be approved.';

                if (!empty($modelErrors)) {
                    $errorMessage .= ' Errors: ' . implode(', ', $modelErrors);
                    log_message('error', 'TutorVideosModel errors during approval: ' . json_encode($modelErrors));
                }

                return $this->response->setJSON([
                    'success' => false,
                    'message' => $errorMessage
                ]);

            } catch (\Exception $e) {
                log_message('error', 'Exception during video approval for ID ' . $videoId . ': ' . $e->getMessage());
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Unexpected error occurred during approval: ' . $e->getMessage()
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error approving video ID ' . $videoId . ': ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error occurred while approving video.'
            ]);
        }
    }

    // Reject video submission
    public function rejectVideo($videoId)
    {
        if ($this->request->getMethod() != 'post') {
            return redirect()->to('admin/video-queue');
        }

        $reason = $this->request->getPost('reason');
        if (empty(trim($reason))) {
            return redirect()->to('admin/video-queue')->with('error', 'Rejection reason is required.');
        }

        $tutorVideosModel = new \App\Models\TutorVideosModel();
        $video = $tutorVideosModel->find($videoId);

        if (!$video) {
            return redirect()->to('admin/video-queue')->with('error', 'Video not found.');
        }

        // Reject the video
        if ($tutorVideosModel->rejectVideo($videoId)) {
            // Send notification to tutor
            $this->notifyTutorVideoRejection($video['tutor_id'], $video, $reason);

            return redirect()->to('admin/video-queue')->with('success', 'Video rejected successfully.');
        }

        return redirect()->to('admin/video-queue')->with('error', 'Failed to reject video.');
    }

    // Notify tutor about video approval
    private function notifyTutorVideoApproval($tutorId, $video)
    {
        try {
            $tutor = $this->userModel->find($tutorId);

            if (!$tutor) return;

            $emailService = \Config\Services::email();

            $emailService->setFrom('info@uprisemw.com', 'TutorConnect Malawi');
            $emailService->setTo($tutor['email']);
            $emailService->setSubject('🎉 Your Video Solution Has Been Approved!');

            $videoUrl = base_url('resources/video/' . $video['id']);

            $htmlMessage = "
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Video Solution Approved</title>
    <style>
        .email-container { max-width: 600px; margin: 0 auto; font-family: Arial, sans-serif; }
        .header { background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 30px; text-align: center; }
        .content { padding: 30px; background-color: #ffffff; }
        .video-info { background: #ECFDF5; border: 1px solid #10b981; padding: 20px; margin: 20px 0; border-radius: 8px; }
        .action-button { background: #3B82F6; color: white; padding: 15px 30px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block; margin: 20px 0; }
        .action-button:hover { background-color: #2563EB; }
        .footer { background-color: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class='email-container'>
        <div class='header'>
            <h1>🎉 Video Solution Approved!</h1>
            <p>Your educational content is now live</p>
        </div>
        <div class='content'>
            <h2>Congratulations, " . htmlspecialchars($tutor['first_name']) . "!</h2>
            <p>Your video solution has been reviewed and approved for publication. Students can now access it on our platform.</p>

            <div class='video-info'>
                <h3>Video Details:</h3>
                <p><strong>Title:</strong> " . htmlspecialchars($video['title']) . "</p>
                <p><strong>Subject:</strong> " . htmlspecialchars($video['subject']) . "</p>
                <p><strong>Exam Body:</strong> " . htmlspecialchars($video['exam_body']) . "</p>
                <p><strong>Status:</strong> <span style='color: #059669; font-weight: bold;'>APPROVED & PUBLISHED</span></p>
            </div>

            <div style='background: #FEF3C7; border: 2px solid #F59E0B; padding: 15px; border-radius: 8px; margin: 20px 0;'>
                <strong>📈 What's Next?</strong><br>
                • Your video is now visible to students searching for solutions<br>
                • Monitor engagement through your dashboard<br>
                • Submit more videos to help more students<br>
                • Featured videos get priority placement on our platform
            </div>

            <a href='" . htmlspecialchars($videoUrl) . "' class='action-button'>View Your Video →</a>

            <p><em>Thank you for contributing to educational excellence in Malawi!</em></p>
        </div>
        <div class='footer'>
            <p>&copy; 2025 TutorConnect Malawi. All rights reserved.<br>
            <a href='mailto:info@tutorconnectmw.com'>info@tutorconnectmw.com</a> |
            Lilongwe, Malawi | +265 992 313 978</p>
        </div>
    </div>
</body>
</html>";

            $plainMessage = "Video Solution Approved - TutorConnect Malawi

Congratulations {$tutor['first_name']}!

Your video solution has been reviewed and approved for publication.

Video Details:
Title: {$video['title']}
Subject: {$video['subject']}
Exam Body: {$video['exam_body']}
Status: APPROVED & PUBLISHED

View your video: {$videoUrl}

Thank you for contributing to educational excellence in Malawi!

---
TutorConnect Malawi
info@uprisemw.com | +265 992 313 978";

            $emailService->setMessage($htmlMessage);
            $emailService->setAltMessage($plainMessage);

            if ($emailService->send()) {
                log_message('info', 'Approval notification sent to tutor ID: ' . $tutorId);
            } else {
                log_message('error', 'Failed to send approval notification to tutor ID: ' . $tutorId);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error sending approval notification: ' . $e->getMessage());
        }
    }

    // Notify tutor about video rejection
    private function notifyTutorVideoRejection($tutorId, $video, $reason)
    {
        try {
            $tutor = $this->userModel->find($tutorId);

            if (!$tutor) return;

            $emailService = \Config\Services::email();

            $emailService->setFrom('info@uprisemw.com', 'TutorConnect Malawi');
            $emailService->setTo($tutor['email']);
            $emailService->setSubject('Video Solution Review - TutorConnect Malawi');

            $resubmitUrl = base_url('trainer/submit-video');

            $htmlMessage = "
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Video Solution Review</title>
    <style>
        .email-container { max-width: 600px; margin: 0 auto; font-family: Arial, sans-serif; }
        .header { background: linear-gradient(135deg, #ef4444, #dc2626); color: white; padding: 30px; text-align: center; }
        .content { padding: 30px; background-color: #ffffff; }
        .video-info { background: #FEF2F2; border: 1px solid #ef4444; padding: 20px; margin: 20px 0; border-radius: 8px; }
        .rejection-reason { background: #FFF5F5; border: 2px solid #FCA5A5; padding: 15px; border-radius: 8px; margin: 15px 0; }
        .action-button { background: #3B82F6; color: white; padding: 15px 30px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block; margin: 20px 0; }
        .action-button:hover { background-color: #2563EB; }
        .footer { background-color: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class='email-container'>
        <div class='header'>
            <h1>Video Solution Review</h1>
            <p>Feedback on your submission</p>
        </div>
        <div class='content'>
            <h2>Hello " . htmlspecialchars($tutor['first_name']) . ",</h2>
            <p>Thank you for submitting your video solution. Our team has reviewed it, but unfortunately, it doesn't meet our current publication standards.</p>

            <div class='video-info'>
                <h3>Submission Details:</h3>
                <p><strong>Title:</strong> " . htmlspecialchars($video['title']) . "</p>
                <p><strong>Subject:</strong> " . htmlspecialchars($video['subject']) . "</p>
                <p><strong>Status:</strong> <span style='color: #dc2626; font-weight: bold;'>NOT APPROVED</span></p>
            </div>

            <div class='rejection-reason'>
                <h4>Review Feedback:</h4>
                <p>" . nl2br(htmlspecialchars($reason)) . "</p>
            </div>

            <div style='background: #ECFDF5; border: 2px solid #10b981; padding: 15px; border-radius: 8px; margin: 20px 0;'>
                <strong>💡 How to Improve:</strong><br>
                • Ensure clear audio quality<br>
                • Provide step-by-step explanations<br>
                • Focus on educational content without external links<br>
                • Keep videos concise and focused<br>
                • Review our content guidelines
            </div>

            <a href='" . htmlspecialchars($resubmitUrl) . "' class='action-button'>Submit Revised Video →</a>

            <p><em>We encourage you to revise and resubmit. We're here to help you create great educational content!</em></p>
        </div>
        <div class='footer'>
            <p>&copy; 2025 TutorConnect Malawi. All rights reserved.<br>
            <a href='mailto:info@tutorconnectmw.com'>info@tutorconnectmw.com</a> |
            Blantyre, Malawi | +265 992 313 978</p>
        </div>
    </div>
</body>
</html>";

            $plainMessage = "Video Solution Review - TutorConnect Malawi

Hello {$tutor['first_name']},

Thank you for submitting your video solution. Our team has reviewed it, but unfortunately, it doesn't meet our current publication standards.

Submission Details:
Title: {$video['title']}
Subject: {$video['subject']}
Status: NOT APPROVED

Review Feedback:
{$reason}

We encourage you to revise and resubmit. Submit a revised video at: {$resubmitUrl}

---
TutorConnect Malawi
info@uprisemw.com | +265 992 313 978";

            $emailService->setMessage($htmlMessage);
            $emailService->setAltMessage($plainMessage);

            if ($emailService->send()) {
                log_message('info', 'Rejection notification sent to tutor ID: ' . $tutorId);
            } else {
                log_message('error', 'Failed to send rejection notification to tutor ID: ' . $tutorId);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error sending rejection notification: ' . $e->getMessage());
        }
    }

    // Email methods for document resubmission
    private function sendResubmissionRequestEmail($tutor, $documents, $message, $token)
    {
        log_message('info', "Sending document resubmission email to tutor: " . $tutor['email']);

        try {
            $emailService = \Config\Services::email();

            $emailService->setFrom('info@uprisemw.com', 'TutorConnect Malawi');
            $emailService->setTo($tutor['email']);
            $emailService->setSubject('Document Resubmission Required - TutorConnect Malawi');

            $resubmissionUrl = base_url('resubmit-documents?token=' . $token);

            $htmlMessage = $this->getResubmissionRequestEmailTemplate($tutor, $documents, $message, $resubmissionUrl);
            $plainMessage = "Dear " . $tutor['first_name'] . ",\n\n" .
                "Our verification team has reviewed your documents and found that some require updates.\n\n" .
                "Documents requiring resubmission: " . implode(', ', $documents) . "\n\n" .
                "Reason: " . $message . "\n\n" .
                "Please visit: " . $resubmissionUrl . "\n\n" .
                "This link expires in 5 hours.\n\n" .
                "Best regards,\nTutorConnect Malawi Team";

            $emailService->setMessage($htmlMessage);
            $emailService->setAltMessage($plainMessage);

            if ($emailService->send()) {
                log_message('info', "✓ Document resubmission email sent successfully to " . $tutor['email']);
                log_message('debug', "Email debugger output: " . $emailService->printDebugger(['headers', 'subject', 'body']));
                return true;
            } else {
                log_message('error', "✗ Failed to send resubmission email to " . $tutor['email']);
                log_message('error', "Email debugger: " . $emailService->printDebugger(['headers', 'subject', 'body']));
                return false;
            }
        } catch (\Exception $e) {
            log_message('error', "Exception in sendResubmissionRequestEmail: " . $e->getMessage());
            log_message('error', "Stack trace: " . $e->getTraceAsString());
            return false;
        }
    }

    // ============================================================================
    // STRUCTURED SUBJECTS MANAGEMENT
    // ============================================================================



    // ============================================================================
    // CURRICULUM MANAGEMENT
    // ============================================================================

    // Curriculum Subjects Management
    public function curriculum()
    {
        $data = [
            'title' => 'Curriculum Management - TutorConnect Malawi',
        ];

        // Get filters from request
        $filters = [
            'curriculum' => $this->request->getGet('curriculum'),
            'level_name' => $this->request->getGet('level_name'),
            'subject_category' => $this->request->getGet('subject_category'),
            'is_active' => $this->request->getGet('status') === null ? null : ($this->request->getGet('status') === 'active' ? 1 : 0),
            'search' => $this->request->getGet('search'),
            'sort_by' => $this->request->getGet('sort_by') ?? 'curriculum',
            'sort_order' => $this->request->getGet('sort_order') ?? 'ASC',
        ];

        // Get curriculum subjects with filters
        $data['curriculum_subjects'] = $this->curriculumSubjectsModel->getFilteredSubjects($filters);
        $data['total_subjects'] = count($data['curriculum_subjects']);
        $data['active_subjects'] = count(array_filter($data['curriculum_subjects'], function($subject) {
            return $subject['is_active'] == 1;
        }));

        // Get filter options
        $data['curricula'] = ['MANEB', 'GCSE', 'Cambridge'];
        $data['subject_categories'] = ['Language', 'Mathematics', 'Science', 'Social Studies', 'Arts', 'Technology', 'Business', 'Agriculture', 'Religion', 'Sports'];

        // Get level names for filter
        $data['level_names'] = $this->curriculumSubjectsModel->getLevelNames();

        // Current filters for form
        $data['current_filters'] = $filters;

        // Get statistics
        $data['stats'] = [
            'total_subjects' => $data['total_subjects'],
            'active_subjects' => $data['active_subjects'],
            'inactive_subjects' => $data['total_subjects'] - $data['active_subjects'],
            'curricula_count' => count($data['curricula']),
        ];

        return view('admin/curriculum', $data);
    }

    // Add new curriculum subject
    public function addCurriculum()
    {
        $data = [
            'title' => 'Add Curriculum Subject - TutorConnect Malawi',
            'curricula' => ['MANEB', 'GCSE', 'Cambridge'],
            'subject_categories' => [
                'Language' => 'Language',
                'Mathematics' => 'Mathematics',
                'Science' => 'Science',
                'Social Studies' => 'Social Studies',
                'Arts' => 'Arts',
                'Technology' => 'Technology',
                'Business' => 'Business',
                'Agriculture' => 'Agriculture',
                'Religion' => 'Religion',
                'Sports' => 'Sports'
            ],
        ];

        return view('admin/add_curriculum', $data);
    }

    // Create curriculum subject
    public function createCurriculum()
    {
        if ($this->request->getMethod() != 'post') {
            return redirect()->to('admin/curriculum');
        }

        $rules = [
            'curriculum' => 'required|in_list[MANEB,GCSE,Cambridge]',
            'level_name' => 'required|max_length[100]',
            'subject_name' => 'required|max_length[100]',
            'subject_category' => 'permit_empty|max_length[50]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Please correct the form errors.')->withInput();
        }

        $data = [
            'curriculum' => $this->request->getPost('curriculum'),
            'level_name' => $this->request->getPost('level_name'),
            'subject_name' => $this->request->getPost('subject_name'),
            'subject_category' => $this->request->getPost('subject_category') ?: null,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if ($this->curriculumSubjectsModel->insert($data)) {
            return redirect()->to('admin/curriculum')->with('success', 'Curriculum subject created successfully!');
        }

        return redirect()->back()->with('error', 'Failed to create curriculum subject.')->withInput();
    }

    // Edit curriculum subject
    public function editCurriculum($subjectId)
    {
        $subject = $this->curriculumSubjectsModel->find($subjectId);

        if (!$subject) {
            return redirect()->to('admin/curriculum')->with('error', 'Curriculum subject not found.');
        }

        $data = [
            'title' => 'Edit Curriculum Subject - TutorConnect Malawi',
            'subject' => $subject,
            'curricula' => ['MANEB', 'GCSE', 'Cambridge'],
            'subject_categories' => [
                'Language' => 'Language',
                'Mathematics' => 'Mathematics',
                'Science' => 'Science',
                'Social Studies' => 'Social Studies',
                'Arts' => 'Arts',
                'Technology' => 'Technology',
                'Business' => 'Business',
                'Agriculture' => 'Agriculture',
                'Religion' => 'Religion',
                'Sports' => 'Sports'
            ],
        ];

        return view('admin/edit_curriculum', $data);
    }

    // Update curriculum subject
    public function updateCurriculum($subjectId)
    {
        if ($this->request->getMethod() != 'post') {
            return redirect()->to('admin/curriculum');
        }

        $rules = [
            'curriculum' => 'required|in_list[MANEB,GCSE,Cambridge]',
            'level_name' => 'required|max_length[100]',
            'subject_name' => 'required|max_length[100]',
            'subject_category' => 'permit_empty|max_length[50]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Please correct the form errors.')->withInput();
        }

        $data = [
            'curriculum' => $this->request->getPost('curriculum'),
            'level_name' => $this->request->getPost('level_name'),
            'subject_name' => $this->request->getPost('subject_name'),
            'subject_category' => $this->request->getPost('subject_category') ?: null,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if ($this->curriculumSubjectsModel->update($subjectId, $data)) {
            return redirect()->to('admin/curriculum')->with('success', 'Curriculum subject updated successfully!');
        }

        return redirect()->back()->with('error', 'Failed to update curriculum subject.')->withInput();
    }

    // Delete curriculum subject
    public function deleteCurriculum($subjectId)
    {
        if ($this->request->getMethod() != 'post') {
            return redirect()->to('admin/curriculum');
        }

        $subject = $this->curriculumSubjectsModel->find($subjectId);
        if (!$subject) {
            return redirect()->to('admin/curriculum')->with('error', 'Curriculum subject not found.');
        }

        if ($this->curriculumSubjectsModel->delete($subjectId)) {
            return redirect()->to('admin/curriculum')->with('success', 'Curriculum subject deleted successfully!');
        }

        return redirect()->to('admin/curriculum')->with('error', 'Failed to delete curriculum subject.');
    }

    // Toggle curriculum subject status
    public function toggleCurriculumStatus($subjectId)
    {
        if ($this->request->getMethod() != 'post') {
            return redirect()->to('admin/curriculum');
        }

        $subject = $this->curriculumSubjectsModel->find($subjectId);
        if (!$subject) {
            return redirect()->to('admin/curriculum')->with('error', 'Curriculum subject not found.');
        }

        $newStatus = $subject['is_active'] ? 0 : 1;

        if ($this->curriculumSubjectsModel->update($subjectId, ['is_active' => $newStatus])) {
            $statusText = $newStatus ? 'activated' : 'deactivated';
            return redirect()->to('admin/curriculum')->with('success', 'Curriculum subject ' . $statusText . ' successfully!');
        }

        return redirect()->to('admin/curriculum')->with('error', 'Failed to update curriculum subject status.');
    }

    // Bulk operations for curriculum subjects
    public function bulkCurriculumAction()
    {
        if ($this->request->getMethod() != 'post') {
            return redirect()->to('admin/curriculum');
        }

        $action = $this->request->getPost('action');
        $subjectIds = $this->request->getPost('subject_ids');

        if (empty($subjectIds) || !is_array($subjectIds)) {
            return redirect()->to('admin/curriculum')->with('error', 'No subjects selected.');
        }

        $validActions = ['activate', 'deactivate', 'delete'];
        if (!in_array($action, $validActions)) {
            return redirect()->to('admin/curriculum')->with('error', 'Invalid action specified.');
        }

        $successCount = 0;
        $errorCount = 0;

        foreach ($subjectIds as $subjectId) {
            $subject = $this->curriculumSubjectsModel->find($subjectId);
            if (!$subject) {
                $errorCount++;
                continue;
            }

            switch ($action) {
                case 'activate':
                    $result = $this->curriculumSubjectsModel->update($subjectId, ['is_active' => 1]);
                    break;
                case 'deactivate':
                    $result = $this->curriculumSubjectsModel->update($subjectId, ['is_active' => 0]);
                    break;
                case 'delete':
                    $result = $this->curriculumSubjectsModel->delete($subjectId);
                    break;
            }

            if ($result) {
                $successCount++;
            } else {
                $errorCount++;
            }
        }

        $actionText = ucfirst($action) . 'd';
        $message = "Successfully {$actionText} {$successCount} curriculum subjects.";
        if ($errorCount > 0) {
            $message .= " {$errorCount} operations failed.";
        }

        return redirect()->to('admin/curriculum')->with('success', $message);
    }

    private function getResubmissionRequestEmailTemplate($tutor, $documents, $message, $resubmissionUrl)
    {
        $documentNames = [
            'intro_video' => 'Introduction Video',
            'profile_picture' => 'Profile Photo',
            'cover_photo' => 'Cover Photo',
            'national_id' => 'National ID Card',
            'academic_certificates' => 'Academic Certificates',
            'teaching_qualification' => 'Teaching Qualification',
            'police_clearance' => 'Police Clearance'
        ];

        $documentList = '';
        foreach ($documents as $doc) {
            $name = $documentNames[$doc] ?? ucwords(str_replace('_', ' ', $doc));
            $documentList .= "<li>$name</li>";
        }

        return "
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Document Resubmission Required - TutorConnect Malawi</title>
    <style>
        body { margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4; }
        .email-container { max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #E74C3C, #C0392B); color: white; padding: 30px; text-align: center; }
        .header h1 { margin: 0 0 10px 0; font-size: 24px; }
        .header p { margin: 0; opacity: 0.9; }
        .content { padding: 30px; }
        .content h2 { margin-top: 0; color: #333; font-size: 20px; }
        .content p { color: #555; line-height: 1.6; }
        .alert-box { background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; border-radius: 4px; }
        .alert-box strong { color: #856404; display: block; margin-bottom: 5px; }
        .document-list { background: #f8f9fa; padding: 20px; border-radius: 6px; margin: 20px 0; }
        .document-list strong { color: #333; display: block; margin-bottom: 10px; }
        .document-list ul { margin: 0; padding-left: 20px; color: #555; }
        .document-list li { margin: 5px 0; }
        .comments-box { background: #e7f3ff; border-left: 4px solid #007bff; padding: 15px; margin: 20px 0; border-radius: 4px; }
        .comments-box strong { color: #004085; display: block; margin-bottom: 8px; }
        .action-button { display: block; width: fit-content; margin: 30px auto; background: #007bff; color: white !important; padding: 15px 40px; text-decoration: none; border-radius: 6px; font-weight: bold; text-align: center; }
        .action-button:hover { background: #0056b3; }
        .note { background: #f8f9fa; padding: 15px; border-radius: 4px; margin: 20px 0; font-size: 13px; color: #666; }
        .note strong { color: #333; }
        .footer { background-color: #2c3e50; color: white; padding: 20px; text-align: center; font-size: 12px; }
        .footer a { color: #3498db; text-decoration: none; }
    </style>
</head>
<body>
    <div class='email-container'>
        <div class='header'>
            <h1>📋 Document Resubmission Required</h1>
            <p>TutorConnect Malawi</p>
        </div>
        <div class='content'>
            <h2>Hello " . htmlspecialchars($tutor['first_name']) . ",</h2>
            <p>Our verification team has reviewed your application. Some documents need to be updated before we can approve your profile.</p>

            <div class='alert-box'>
                <strong>⚠️ Action Required</strong>
                Please resubmit the documents listed below.
            </div>

            <div class='document-list'>
                <strong>Documents to Resubmit:</strong>
                <ul>
                    " . $documentList . "
                </ul>
            </div>

            <div class='comments-box'>
                <strong>Admin Comments:</strong>
                " . nl2br(htmlspecialchars($message)) . "
            </div>

            <a href='" . htmlspecialchars($resubmissionUrl) . "' class='action-button'>
                📤 Upload Documents Now
            </a>

            <div class='note'>
                <strong>⏱️ Important:</strong> This link expires in 5 hours. Make sure files are clear, readable, and under 10MB each.
            </div>

            <p style='margin-top: 30px;'>If you need help, contact us at <a href='mailto:info@uprisemw.com' style='color: #007bff;'>info@uprisemw.com</a></p>

            <p style='margin-top: 20px;'>Best regards,<br><strong>TutorConnect Malawi Team</strong></p>
        </div>
        <div class='footer'>
            <p>&copy; 2026 TutorConnect Malawi<br>
            <a href='mailto:info@uprisemw.com'>info@uprisemw.com</a> | +265 992 313 978</p>
        </div>
    </div>
</body>
</html>";
    }

    public function contact_messages()
    {
        $this->requireAdminAccess();

        $page = $this->request->getVar('page') ?? 1;
        $perPage = 20;

        $data = [
            'title' => 'Contact Messages - TutorConnect Malawi',
            'messages' => $this->contactMessageModel->orderBy('created_at', 'DESC')
                                                     ->paginate($perPage),
            'pager' => $this->contactMessageModel->pager,
            'unreadCount' => $this->contactMessageModel->getUnreadCount(),
            'totalCount' => $this->contactMessageModel->countAllResults()
        ];

        // Add unread message count for sidebar
        $data = $this->getAdminData($data);

        return view('admin/contact_messages', $data);
    }

    public function japan_applications()
    {
        $this->requireAdminAccess();
        $this->ensureJapanApplicationsTable();
        $statsModel = new JapanApplicationModel();
        $statsModel->ensureTable();

        $status = trim((string) $this->request->getGet('status'));
        $search = trim((string) $this->request->getGet('q'));

        $builder = $this->japanApplicationModel->orderBy('submitted_at', 'DESC');

        if ($status !== '') {
            $builder = $builder->where('status', $status);
        }

        if ($search !== '') {
            $builder = $builder->groupStart()
                ->like('application_reference', $search)
                ->orLike('full_name', $search)
                ->orLike('email', $search)
                ->orLike('phone', $search)
                ->groupEnd();
        }

        $data = [
            'title' => 'Japan Applications - TutorConnect Malawi',
            'applications' => $builder->paginate(20),
            'pager' => $this->japanApplicationModel->pager,
            'statusCounts' => $statsModel->getStatusCounts(),
            'statusFilter' => $status,
            'searchQuery' => $search,
        ];

        $data = $this->getAdminData($data);

        return view('admin/japan_applications', $data);
    }

    public function japan_payments()
    {
        $this->requireAdminAccess();
        $this->ensureJapanApplicationsTable();
        $this->ensureJapanApplicationAccessTable();

        $status = trim((string) $this->request->getGet('payment_status'));
        $search = trim((string) $this->request->getGet('q'));

        $builder = $this->japanApplicationAccessModel
            ->select('japan_application_access.*, japan_teaching_applications.application_reference')
            ->join('japan_teaching_applications', 'japan_teaching_applications.id = japan_application_access.application_id', 'left')
            ->orderBy('japan_application_access.paid_at', 'DESC')
            ->orderBy('japan_application_access.created_at', 'DESC');

        if ($status !== '') {
            $builder = $builder->where('japan_application_access.payment_status', $status);
        }

        if ($search !== '') {
            $builder = $builder->groupStart()
                ->like('japan_application_access.tx_ref', $search)
                ->orLike('japan_application_access.email', $search)
                ->orLike('japan_application_access.full_name', $search)
                ->orLike('japan_teaching_applications.application_reference', $search)
                ->groupEnd();
        }

        $db = \Config\Database::connect();
        $totalsRow = $db->table('japan_application_access')
            ->select('COUNT(*) as total_count,
                      SUM(CASE WHEN payment_status = "verified" THEN amount ELSE 0 END) as verified_amount_total,
                      SUM(CASE WHEN payment_status = "verified" THEN 1 ELSE 0 END) as verified_count,
                      SUM(CASE WHEN payment_status = "pending" THEN 1 ELSE 0 END) as pending_count,
                      SUM(CASE WHEN payment_status = "failed" THEN 1 ELSE 0 END) as failed_count')
            ->get()
            ->getRow();

        $data = [
            'title' => 'Japan Payments - TutorConnect Malawi',
            'payments' => $builder->paginate(25),
            'pager' => $this->japanApplicationAccessModel->pager,
            'paymentStatusFilter' => $status,
            'searchQuery' => $search,
            'totals' => [
                'total_count' => (int) ($totalsRow->total_count ?? 0),
                'verified_amount_total' => (int) ($totalsRow->verified_amount_total ?? 0),
                'verified_count' => (int) ($totalsRow->verified_count ?? 0),
                'pending_count' => (int) ($totalsRow->pending_count ?? 0),
                'failed_count' => (int) ($totalsRow->failed_count ?? 0),
            ],
        ];

        $data = $this->getAdminData($data);

        return view('admin/japan_payments', $data);
    }

    public function exportJapanApplicationsExcel()
    {
        $this->requireAdminAccess();
        $this->ensureJapanApplicationsTable();

        $status = trim((string) $this->request->getGet('status'));
        $search = trim((string) $this->request->getGet('q'));

        $builder = $this->japanApplicationModel->orderBy('submitted_at', 'DESC');

        if ($status !== '') {
            $builder = $builder->where('status', $status);
        }

        if ($search !== '') {
            $builder = $builder->groupStart()
                ->like('application_reference', $search)
                ->orLike('full_name', $search)
                ->orLike('email', $search)
                ->orLike('phone', $search)
                ->groupEnd();
        }

        $applications = $builder->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            'No.',
            'Reference',
            'Full Name',
            'Email',
            'Phone',
            'Nationality',
            'Gender',
            'Date of Birth',
            'Age',
            'Current Address',
            'Passport Number',
            'Passport Expiry',
            'Highest Qualification',
            'Degree Obtained',
            'Field of Study',
            'Institution',
            'Year of Completion',
            'Has Teaching Certificate',
            'Teaching Certificate Details',
            'Has Teaching Experience',
            'Teaching Experience Location',
            'Subjects Taught',
            'Level Taught',
            'Teaching Experience Duration',
            'Documents Already Shared',
            'Shared Documents Note',
            'Degree Document Path',
            'Transcript Document Path',
            'Passport Copy Path',
            'CV Document Path',
            'Teaching Certificate Path',
            'Status',
            'Admin Notes',
            'Submitted At',
            'Created At',
        ];

        foreach ($headers as $colIndex => $header) {
            $this->sheetSetValue($sheet, $colIndex + 1, 1, $header);
        }

        $row = 2;
        $no = 1;
        foreach ($applications as $application) {
            $this->sheetSetValue($sheet, 1, $row, $no);
            $this->sheetSetValue($sheet, 2, $row, (string) ($application['application_reference'] ?? ''));
            $this->sheetSetValue($sheet, 3, $row, (string) ($application['full_name'] ?? ''));
            $this->sheetSetValue($sheet, 4, $row, (string) ($application['email'] ?? ''));
            $this->sheetSetValue($sheet, 5, $row, (string) ($application['phone'] ?? ''));
            $this->sheetSetValue($sheet, 6, $row, (string) ($application['nationality'] ?? ''));
            $this->sheetSetValue($sheet, 7, $row, (string) ($application['gender'] ?? ''));
            $this->sheetSetValue($sheet, 8, $row, (string) ($application['date_of_birth'] ?? ''));
            $this->sheetSetValue($sheet, 9, $row, (string) ($application['age'] ?? ''));
            $this->sheetSetValue($sheet, 10, $row, (string) ($application['current_address'] ?? ''));
            $this->sheetSetValue($sheet, 11, $row, (string) ($application['passport_number'] ?? ''));
            $this->sheetSetValue($sheet, 12, $row, (string) ($application['passport_expiry_date'] ?? ''));
            $this->sheetSetValue($sheet, 13, $row, (string) ($application['highest_qualification'] ?? ''));
            $this->sheetSetValue($sheet, 14, $row, (string) ($application['degree_obtained'] ?? ''));
            $this->sheetSetValue($sheet, 15, $row, (string) ($application['field_of_study'] ?? ''));
            $this->sheetSetValue($sheet, 16, $row, (string) ($application['institution_name'] ?? ''));
            $this->sheetSetValue($sheet, 17, $row, (string) ($application['year_of_completion'] ?? ''));
            $this->sheetSetValue($sheet, 18, $row, !empty($application['has_teaching_certificate']) ? 'Yes' : 'No');
            $this->sheetSetValue($sheet, 19, $row, (string) ($application['teaching_certificate_details'] ?? ''));
            $this->sheetSetValue($sheet, 20, $row, !empty($application['has_teaching_experience']) ? 'Yes' : 'No');
            $this->sheetSetValue($sheet, 21, $row, (string) ($application['teaching_experience_location'] ?? ''));
            $this->sheetSetValue($sheet, 22, $row, (string) ($application['subjects_taught'] ?? ''));
            $this->sheetSetValue($sheet, 23, $row, (string) ($application['level_taught'] ?? ''));
            $this->sheetSetValue($sheet, 24, $row, (string) ($application['teaching_experience_duration'] ?? ''));
            $this->sheetSetValue($sheet, 25, $row, !empty($application['documents_already_shared']) ? 'Yes' : 'No');
            $this->sheetSetValue($sheet, 26, $row, (string) ($application['shared_documents_note'] ?? ''));
            $this->sheetSetValue($sheet, 27, $row, (string) ($application['degree_document_path'] ?? ''));
            $this->sheetSetValue($sheet, 28, $row, (string) ($application['transcript_document_path'] ?? ''));
            $this->sheetSetValue($sheet, 29, $row, (string) ($application['passport_copy_path'] ?? ''));
            $this->sheetSetValue($sheet, 30, $row, (string) ($application['cv_document_path'] ?? ''));
            $this->sheetSetValue($sheet, 31, $row, (string) ($application['teaching_certificate_path'] ?? ''));
            $this->sheetSetValue($sheet, 32, $row, (string) ($application['status'] ?? ''));
            $this->sheetSetValue($sheet, 33, $row, (string) ($application['admin_notes'] ?? ''));
            $this->sheetSetValue($sheet, 34, $row, (string) ($application['submitted_at'] ?? ''));
            $this->sheetSetValue($sheet, 35, $row, (string) ($application['created_at'] ?? ''));
            $row++;
            $no++;
        }

        $sheet->freezePane('A2');

        $writer = new Xlsx($spreadsheet);
        ob_start();
        $writer->save('php://output');
        $binary = ob_get_clean();

        $filename = 'japan_applications_' . date('Ymd_His') . '.xlsx';

        return $this->response
            ->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setHeader('Cache-Control', 'max-age=0')
            ->setBody($binary);
    }

    public function exportJapanApplicationsPdf()
    {
        $this->requireAdminAccess();
        $this->ensureJapanApplicationsTable();

        $status = trim((string) $this->request->getGet('status'));
        $search = trim((string) $this->request->getGet('q'));

        $builder = $this->japanApplicationModel->orderBy('submitted_at', 'DESC');

        if ($status !== '') {
            $builder = $builder->where('status', $status);
        }

        if ($search !== '') {
            $builder = $builder->groupStart()
                ->like('application_reference', $search)
                ->orLike('full_name', $search)
                ->orLike('email', $search)
                ->orLike('phone', $search)
                ->groupEnd();
        }

        $applications = $builder->findAll();

        $html = view('admin/japan_applications_export_pdf', [
            'applications' => $applications,
            'statusFilter' => $status,
            'searchQuery' => $search,
        ]);

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $pdf = $dompdf->output();
        $filename = 'japan_applications_' . date('Ymd_His') . '.pdf';

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setHeader('Cache-Control', 'max-age=0')
            ->setBody($pdf);
    }

    public function downloadJapanApplicationPdf(int $id)
    {
        $this->requireAdminAccess();
        $this->ensureJapanApplicationsTable();

        $application = $this->japanApplicationModel->find($id);
        if (!$application) {
            return redirect()->to(site_url('admin/japan-applications'))
                ->with('error', 'Japan application not found.');
        }

        $application['financial_readiness'] = json_decode($application['financial_readiness_json'] ?? '[]', true) ?: [];
        $application['referees'] = json_decode($application['referees_json'] ?? '[]', true) ?: [];
        $application['declarations'] = json_decode($application['declarations_json'] ?? '[]', true) ?: [];

        $html = view('admin/japan_application_pdf', [
            'application' => $application,
        ]);

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $pdf = $dompdf->output();
        $ref = (string) ($application['application_reference'] ?? ('application_' . $id));
        $safeRef = preg_replace('/[^a-zA-Z0-9_\\-]+/', '_', $ref) ?: ('application_' . $id);
        $filename = $safeRef . '_' . date('Ymd_His') . '.pdf';

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setHeader('Cache-Control', 'max-age=0')
            ->setBody($pdf);
    }

    public function view_japan_application($id)
    {
        $this->requireAdminAccess();
        $this->ensureJapanApplicationsTable();

        $application = $this->japanApplicationModel->find($id);
        if (!$application) {
            throw PageNotFoundException::forPageNotFound();
        }

        $application['financial_readiness'] = json_decode($application['financial_readiness_json'] ?? '[]', true) ?: [];
        $application['referees'] = json_decode($application['referees_json'] ?? '[]', true) ?: [];
        $application['declarations'] = json_decode($application['declarations_json'] ?? '[]', true) ?: [];

        $data = [
            'title' => 'Japan Application Details - TutorConnect Malawi',
            'application' => $application,
            'statusOptions' => [
                'submitted' => 'Submitted',
                'under_review' => 'Under Review',
                'approved' => 'Approved',
                'rejected' => 'Rejected',
                'forwarded_to_japan' => 'Forwarded to Japan Agent',
                'interview_scheduled' => 'Interview Scheduled',
                'placed' => 'Placed',
            ],
        ];

        $data = $this->getAdminData($data);

        return view('admin/view_japan_application', $data);
    }

    public function update_japan_application($id)
    {
        $this->requireAdminAccess();
        $this->ensureJapanApplicationsTable();

        $application = $this->japanApplicationModel->find($id);
        if (!$application) {
            throw PageNotFoundException::forPageNotFound();
        }

        $status = trim((string) $this->request->getPost('status'));
        $adminNotes = trim((string) $this->request->getPost('admin_notes'));
        $allowedStatuses = [
            'submitted',
            'under_review',
            'approved',
            'rejected',
            'forwarded_to_japan',
            'interview_scheduled',
            'placed',
        ];

        if (!in_array($status, $allowedStatuses, true)) {
            return redirect()->back()->with('error', 'Please choose a valid application status.');
        }

        $this->japanApplicationModel->update($id, [
            'status' => $status,
            'admin_notes' => $adminNotes,
        ]);

        return redirect()->to(site_url('admin/japan-applications/' . $id))
            ->with('success', 'Japan application updated successfully.');
    }

    public function view_message($id)
    {
        $this->requireAdminAccess();

        $message = $this->contactMessageModel->find($id);
        if (!$message) {
            throw PageNotFoundException::forPageNotFound();
        }

        // Mark as read
        $this->contactMessageModel->markAsRead($id);

        $data = [
            'title' => 'View Message - TutorConnect Malawi',
            'message' => $message
        ];

        // Add unread message count for sidebar
        $data = $this->getAdminData($data);

        return view('admin/view_message', $data);
    }

    public function delete_message($id)
    {
        $this->requireAdminAccess();

        if (!$this->request->isAJAX()) {
            return redirect()->to(base_url('admin/contact-messages'));
        }

        $this->contactMessageModel->delete($id);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Message deleted successfully'
        ]);
    }

    // ==================== RESOURCES MANAGEMENT ====================

    public function resources()
    {
        $this->requireAdminAccess();

        $page = $this->request->getVar('page') ?? 1;
        $perPage = 20;
        $type_filter = $this->request->getVar('type');

        $resources = [];

        // Get past papers from database with uploader info
        $papers = $this->pastPapersModel->select('past_papers.*, users.first_name, users.last_name')
                                       ->join('users', 'users.id = past_papers.uploaded_by', 'left')
                                       ->orderBy('past_papers.created_at', 'DESC')
                                       ->findAll();

        foreach ($papers as $paper) {
            $resources[] = [
                'id' => $paper['id'],
                'resource_type' => 'past_paper',
                'title' => $paper['paper_title'] ?? "{$paper['exam_body']} {$paper['exam_level']} {$paper['subject']} {$paper['year']}",
                'subject' => $paper['subject'],
                'curriculum' => $paper['exam_body'],
                'grade_level' => $paper['exam_level'],
                'is_approved' => $paper['is_active'],
                'is_featured' => false,
                'view_count' => 0,
                'download_count' => $paper['download_count'] ?? 0,
                'created_at' => $paper['created_at'],
                'uploaded_by_name' => (!empty($paper['first_name']) && !empty($paper['last_name'])) ?
                    $paper['first_name'] . ' ' . $paper['last_name'] : 'Admin'
            ];
        }

        // Get videos from database with uploader info
        $videos = $this->tutorVideosModel->select('tutor_videos.*, users.first_name, users.last_name, users.role')
                                        ->join('users', 'users.id = tutor_videos.tutor_id', 'left')
                                        ->orderBy('tutor_videos.created_at', 'DESC')
                                        ->findAll();

        foreach ($videos as $video) {
            $uploaderName = 'Unknown';

            // Check if this is admin-added (tutor_id = 1) or admin user
            if ($video['tutor_id'] == 1 || $video['role'] === 'admin' || $video['role'] === 'sub-admin') {
                $uploaderName = 'Admin';
            } elseif (!empty($video['first_name']) && !empty($video['last_name'])) {
                $uploaderName = $video['first_name'] . ' ' . $video['last_name'];
            }

            $resources[] = [
                'id' => $video['id'],
                'resource_type' => 'video',
                'title' => $video['title'],
                'subject' => $video['subject'],
                'curriculum' => $video['exam_body'],
                'grade_level' => $video['topic'] ?? '-',
                'is_approved' => $video['status'] === 'approved',
                'is_featured' => $video['featured_level'] !== 'none',
                'view_count' => $video['view_count'] ?? 0,
                'download_count' => 0,
                'created_at' => $video['created_at'],
                'uploaded_by_name' => $uploaderName,
                'video_embed_code' => $video['video_embed_code'] ?? ''
            ];
        }

        // Sort all resources by created_at
        usort($resources, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        // Apply type filter if specified
        if (!empty($type_filter)) {
            $resources = array_filter($resources, function($resource) use ($type_filter) {
                return $resource['resource_type'] === $type_filter;
            });
            // Re-index array after filtering
            $resources = array_values($resources);
        }

        // Apply pagination manually
        $totalResources = count($resources);
        $resources = array_slice($resources, ($page - 1) * $perPage, $perPage);

        // Create simple pager
        $pager = null;

        // Count pending items
        $pendingCount = $this->pastPapersModel->where('is_active', 0)->countAllResults(false)
                      + $this->tutorVideosModel->where('status', 'pending_review')->countAllResults(false);

        // Get only unique curriculums from database
        $curriculumData = $this->curriculumSubjectsModel
            ->select('curriculum')
            ->where('is_active', 1)
            ->groupBy('curriculum')
            ->orderBy('curriculum', 'ASC')
            ->findAll();

        $curriculums = array_column($curriculumData, 'curriculum');

        $data = [
            'title' => 'Library Management - TutorConnect Malawi',
            'resources' => $resources,
            'pager' => $pager,
            'pendingCount' => $pendingCount,
            'type_filter' => $type_filter,
            'stats' => [
                'total_papers' => $this->pastPapersModel->countAll(),
                'total_videos' => $this->tutorVideosModel->countAll(),
                'active_papers' => $this->pastPapersModel->where('is_active', 1)->countAllResults(),
                'approved_videos' => $this->tutorVideosModel->where('status', 'approved')->countAllResults()
            ],
            'curriculums' => $curriculums
        ];

        $data = $this->getAdminData($data);

        return view('admin/library', $data);
    }

    public function add_resource()
    {
        // Check if it's an AJAX request first, before any authentication
        $isAjax = $this->request->isAJAX();

        // Force JSON response for AJAX requests
        if ($isAjax) {
            $this->response->setContentType('application/json');
        }

        try {
            $this->requireAdminAccess();
        } catch (\Exception $e) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Access denied: ' . $e->getMessage()
                ]);
            }
            throw $e;
        }

        if ($this->request->getMethod() === 'POST') {
            try {
                // Get valid curriculums, levels, and subjects from database
                $validCurriculums = $this->curriculumSubjectsModel
                    ->select('curriculum')
                    ->where('is_active', 1)
                    ->groupBy('curriculum')
                    ->findColumn('curriculum');

                $validLevels = $this->curriculumSubjectsModel
                    ->select('level_name')
                    ->where('is_active', 1)
                    ->groupBy('level_name')
                    ->findColumn('level_name');

                $validation = \Config\Services::validation();

                $rules = [
                    'exam_body' => 'required|in_list[' . implode(',', $validCurriculums) . ']',
                    'exam_level' => 'required|in_list[' . implode(',', $validLevels) . ']',
                    'subject' => 'required|max_length[100]',
                    'year' => 'required|integer',
                    'paper_title' => 'required|max_length[200]',
                    'file' => 'uploaded[file]|max_size[file,10240]|ext_in[file,pdf]'
                ];

                $validation->setRules($rules);

                if (!$validation->withRequest($this->request)->run()) {
                    if ($isAjax) {
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Validation failed',
                            'errors' => $validation->getErrors()
                        ]);
                    }
                    return redirect()->back()->withInput()->with('error', 'Validation failed')->with('errors', $validation->getErrors());
                }

                $file = $this->request->getFile('file');
                $filePath = '';
                $fileSize = '';

                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $uploadPath = WRITEPATH . 'uploads/past_papers/';

                    // Ensure directory exists
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }

                    $file->move($uploadPath, $newName);

                    $filePath = 'writable/uploads/past_papers/' . $newName;
                    $fileSize = $this->formatBytes($file->getSize());
                } else {
                    if ($isAjax) {
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Invalid file upload'
                        ]);
                    }
                    return redirect()->back()->withInput()->with('error', 'Invalid file upload');
                }

                $paperData = [
                    'exam_body' => $this->request->getPost('exam_body'),
                    'exam_level' => $this->request->getPost('exam_level'),
                    'subject' => $this->request->getPost('subject'),
                    'year' => $this->request->getPost('year'),
                    'paper_title' => $this->request->getPost('paper_title'),
                    'paper_code' => $this->request->getPost('paper_code'),
                    'file_url' => $filePath,
                    'file_size' => $fileSize,
                    'is_active' => $this->request->getPost('is_active') ? 1 : 0,
                    'copyright_notice' => $this->request->getPost('copyright_notice')
                ];

                if ($this->pastPapersModel->insert($paperData)) {
                    if ($isAjax) {
                        return $this->response->setJSON([
                            'success' => true,
                            'message' => 'Past paper added successfully'
                        ]);
                    }
                    return redirect()->to(base_url('admin/library'))->with('success', 'Past paper added successfully');
                }

                if ($isAjax) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Failed to add past paper'
                    ]);
                }
                return redirect()->back()->withInput()->with('error', 'Failed to add past paper');

            } catch (\Exception $e) {
                log_message('error', 'Add resource error: ' . $e->getMessage());
                log_message('error', 'Stack trace: ' . $e->getTraceAsString());
                if ($isAjax) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Error: ' . $e->getMessage()
                    ]);
                }
                return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
            }
        }

        $data = [
            'title' => 'Add Past Paper - TutorConnect Malawi'
        ];

        $data = $this->getAdminData($data);

        return view('admin/add_resource', $data);
    }

    public function add_video()
    {
        // Check if it's an AJAX request first
        $isAjax = $this->request->isAJAX();

        // Set JSON content type for AJAX
        if ($isAjax) {
            $this->response->setContentType('application/json');
        }

        // Check authentication
        try {
            $this->requireAdminAccess();
        } catch (\Exception $e) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Access denied'
                ]);
            }
            throw $e;
        }

        if ($this->request->getMethod() === 'POST') {
            try {
                // Get valid curriculums from database
                $validCurriculums = $this->curriculumSubjectsModel
                    ->select('curriculum')
                    ->where('is_active', 1)
                    ->groupBy('curriculum')
                    ->findColumn('curriculum');

                $validation = \Config\Services::validation();

                $rules = [
                    'video_platform' => 'required|in_list[youtube,vimeo]',
                    'video_id' => 'required|max_length[200]',
                    'title' => 'required|max_length[255]',
                    'exam_body' => 'required|in_list[' . implode(',', $validCurriculums) . ']',
                    'subject' => 'required|max_length[100]',
                    'status' => 'required|in_list[pending_review,approved]'
                ];

                $validation->setRules($rules);

                if (!$validation->withRequest($this->request)->run()) {
                    if ($isAjax) {
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Validation failed',
                            'errors' => $validation->getErrors()
                        ]);
                    }
                    return redirect()->back()->withInput()->with('error', 'Validation failed')->with('errors', $validation->getErrors());
                }

                // Handle video embed code - check if it's a full iframe or URL
                $videoInput = $this->request->getPost('video_id');
                $embedCode = '';

                // If it's a full iframe HTML, use it directly
                if (stripos($videoInput, '<iframe') !== false) {
                    $embedCode = $videoInput;
                } else {
                    // Otherwise, extract video ID and generate embed code
                    $videoId = $this->extractVideoId($videoInput, $this->request->getPost('video_platform'));
                    $embedCode = $this->generateEmbedCode($videoId, $this->request->getPost('video_platform'));
                }

                $videoData = [
                    'tutor_id' => 1, // Admin added, using default admin ID
                    'title' => $this->request->getPost('title'),
                    'description' => $this->request->getPost('description'),
                    'video_embed_code' => $embedCode,
                    'video_platform' => $this->request->getPost('video_platform'),
                    'video_id' => stripos($videoInput, '<iframe') === false ? $this->extractVideoId($videoInput, $this->request->getPost('video_platform')) : '',
                    'exam_body' => $this->request->getPost('exam_body'),
                    'subject' => $this->request->getPost('subject'),
                    'topic' => $this->request->getPost('topic'),
                    'problem_year' => $this->request->getPost('problem_year'),
                    'status' => $this->request->getPost('status'),
                    'approved_at' => $this->request->getPost('status') === 'approved' ? date('Y-m-d H:i:s') : null
                ];

                if ($this->tutorVideosModel->insert($videoData)) {
                    if ($isAjax) {
                        return $this->response->setJSON([
                            'success' => true,
                            'message' => 'Video added successfully'
                        ]);
                    }
                    return redirect()->to(base_url('admin/library'))->with('success', 'Video added successfully');
                }

                if ($isAjax) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Failed to add video'
                    ]);
                }
                return redirect()->back()->withInput()->with('error', 'Failed to add video');

            } catch (\Exception $e) {
                log_message('error', 'Add video error: ' . $e->getMessage());
                log_message('error', 'Stack trace: ' . $e->getTraceAsString());
                if ($isAjax) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Error: ' . $e->getMessage()
                    ]);
                }
                return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
            }
        }

        $data = [
            'title' => 'Add Video - TutorConnect Malawi'
        ];

        $data = $this->getAdminData($data);

        return view('admin/add_video', $data);
    }

    private function extractVideoId($input, $platform)
    {
        // If it's already just an ID (no URL patterns), return as is
        if (strlen($input) < 50 && !str_contains($input, '/') && !str_contains($input, '.')) {
            return $input;
        }

        // Extract YouTube ID
        if ($platform === 'youtube') {
            // Match various YouTube URL formats
            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/', $input, $matches)) {
                return $matches[1];
            }
        }

        // Extract Vimeo ID
        if ($platform === 'vimeo') {
            if (preg_match('/vimeo\.com\/(\d+)/', $input, $matches)) {
                return $matches[1];
            }
        }

        // Return the input as is if no pattern matched
        return $input;
    }

    private function generateEmbedCode($videoId, $platform)
    {
        if ($platform === 'youtube') {
            return '<iframe width="560" height="315" src="https://www.youtube.com/embed/' . $videoId . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
        }

        if ($platform === 'vimeo') {
            return '<iframe src="https://player.vimeo.com/video/' . $videoId . '" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>';
        }

        return '';
    }

    public function edit_resource($id)
    {
        $this->requireAdminAccess();

        $resource = $this->resourceModel->find($id);
        if (!$resource) {
            throw PageNotFoundException::forPageNotFound();
        }

        if ($this->request->getMethod() === 'post') {
            $resourceData = [
                'title' => $this->request->getPost('title'),
                'subject' => $this->request->getPost('subject'),
                'curriculum' => $this->request->getPost('curriculum'),
                'grade_level' => $this->request->getPost('grade_level'),
                'year' => $this->request->getPost('year'),
                'paper_type' => $this->request->getPost('paper_type'),
                'description' => $this->request->getPost('description'),
                'tags' => $this->request->getPost('tags'),
                'is_approved' => $this->request->getPost('is_approved') ? 1 : 0,
                'is_featured' => $this->request->getPost('is_featured') ? 1 : 0
            ];

            if ($resource['resource_type'] === 'video') {
                $resourceData['video_url'] = $this->request->getPost('video_url');
                $resourceData['video_thumbnail'] = $this->request->getPost('video_thumbnail');
                $resourceData['video_duration'] = $this->request->getPost('video_duration');
            }

            if ($this->resourceModel->update($id, $resourceData)) {
                return redirect()->to(base_url('admin/library'))->with('success', 'Resource updated successfully');
            }

            return redirect()->back()->withInput()->with('error', 'Failed to update resource');
        }

        $data = [
            'title' => 'Edit Resource - TutorConnect Malawi',
            'resource' => $resource
        ];

        $data = $this->getAdminData($data);

        return view('admin/edit_resource', $data);
    }

    public function delete_resource($id, $type = 'past_paper')
    {
        $this->requireAdminAccess();

        if (!$this->request->isAJAX()) {
            throw PageNotFoundException::forPageNotFound();
        }

        $deleted = false;
        if ($type === 'video') {
            $deleted = $this->tutorVideosModel->delete($id);
        } else {
            $deleted = $this->pastPapersModel->delete($id);
        }

        if ($deleted) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Resource deleted successfully'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to delete resource'
        ]);
    }

    // AJAX endpoint to get levels for a curriculum
    public function get_levels()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $curriculum = $this->request->getPost('curriculum');

        if (!$curriculum) {
            return $this->response->setJSON(['success' => false, 'message' => 'Curriculum required']);
        }

        $levels = $this->curriculumSubjectsModel
            ->select('level_name')
            ->where('curriculum', $curriculum)
            ->where('is_active', 1)
            ->groupBy('level_name')
            ->orderBy('level_name', 'ASC')
            ->findAll();

        $levelList = array_column($levels, 'level_name');

        return $this->response->setJSON([
            'success' => true,
            'levels' => $levelList
        ]);
    }

    // AJAX endpoint to get subjects for curriculum and level
    public function get_subjects()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $curriculum = $this->request->getPost('curriculum');
        $level = $this->request->getPost('level');

        if (!$curriculum) {
            return $this->response->setJSON(['success' => false, 'message' => 'Curriculum required']);
        }

        $query = $this->curriculumSubjectsModel
            ->select('subject_name')
            ->where('curriculum', $curriculum)
            ->where('is_active', 1);

        if ($level) {
            $query->where('level_name', $level);
        }

        $subjects = $query
            ->groupBy('subject_name')
            ->orderBy('subject_name', 'ASC')
            ->findAll();

        $subjectList = array_column($subjects, 'subject_name');

        return $this->response->setJSON([
            'success' => true,
            'subjects' => $subjectList
        ]);
    }

    public function toggle_resource_status($id)
    {
        $this->requireAdminAccess();

        if (!$this->request->isAJAX()) {
            return redirect()->to(base_url('admin/library'));
        }

        $resource = $this->pastPapersModel->find($id);

        if (!$resource) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Resource not found'
            ]);
        }

        $newStatus = $resource['is_active'] ? 0 : 1;

        if ($this->pastPapersModel->update($id, ['is_active' => $newStatus])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Status updated successfully',
                'new_status' => $newStatus
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to update status'
        ]);
    }

    public function delete_resource_old($id)
    {
        $this->requireAdminAccess();

        if (!$this->request->isAJAX()) {
            return redirect()->to(base_url('admin/library'));
        }

        $resource = $this->pastPapersModel->find($id);

        if ($resource && $resource['file_url']) {
            // Extract filename from URL and delete file
            $fileName = basename(parse_url($resource['file_url'], PHP_URL_PATH));
            $filePath = WRITEPATH . 'uploads/past_papers/' . $fileName;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        if ($this->pastPapersModel->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Resource deleted successfully'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to delete resource'
        ]);
    }

    public function get_resource($id, $type = 'past_paper')
    {
        try {
            $this->requireAdminAccess();

            // Set JSON response header
            $this->response->setContentType('application/json');

            if (!$this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid request method'
                ]);
            }

            if ($type === 'video') {
                $resource = $this->tutorVideosModel->find($id);
            } else {
                $resource = $this->pastPapersModel->find($id);
            }

            if ($resource) {
                return $this->response->setJSON([
                    'success' => true,
                    'resource' => $resource,
                    'type' => $type
                ]);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Resource not found'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error in get_resource: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    public function update_resource()
    {
        $this->requireAdminAccess();

        if ($this->request->getMethod() !== 'post') {
            return redirect()->to(base_url('admin/library'));
        }

        $isAjax = $this->request->isAJAX();
        if ($isAjax) {
            $this->response->setContentType('application/json');
        }

        try {
            $id = $this->request->getPost('id');
            $resourceType = $this->request->getPost('resource_type') ?? ($this->request->getPost('status') !== null ? 'video' : 'past_paper');

            if (empty($id)) {
                $message = 'Missing resource identifier.';
                if ($isAjax) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => $message
                    ]);
                }
                return redirect()->back()->with('error', $message);
            }

            if ($resourceType === 'video') {
                return $this->updateVideo($id, $isAjax);
            }
            return $this->updatePaper($id, $isAjax);
        } catch (\Exception $e) {
            log_message('error', 'Update resource error: ' . $e->getMessage());
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Server error: ' . $e->getMessage()
                ]);
            }
            return redirect()->back()->with('error', 'Server error: ' . $e->getMessage());
        }
    }

    private function updatePaper($id, bool $isAjax)
    {
        $validation = \Config\Services::validation();

        $rules = [
            'exam_body' => 'required',
            'exam_level' => 'required',
            'subject' => 'required|max_length[100]',
            'year' => 'required|integer',
            'paper_title' => 'required|max_length[200]'
        ];

        // Only validate file if uploaded
        $file = $this->request->getFile('file');
        if ($file && $file->isValid()) {
            $rules['file'] = 'max_size[file,10240]|ext_in[file,pdf]';
        }

        $validation->setRules($rules);

        if (!$validation->withRequest($this->request)->run()) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validation->getErrors()
                ]);
            }
            return redirect()->back()->with('error', 'Validation failed')->with('errors', $validation->getErrors());
        }

        $paperData = [
            'exam_body' => $this->request->getPost('exam_body'),
            'exam_level' => $this->request->getPost('exam_level'),
            'subject' => $this->request->getPost('subject'),
            'year' => $this->request->getPost('year'),
            'paper_title' => $this->request->getPost('paper_title'),
            'paper_code' => $this->request->getPost('paper_code'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
            'copyright_notice' => $this->request->getPost('copyright_notice')
        ];

        // Handle file upload if new file provided
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Delete old file
            $oldResource = $this->pastPapersModel->find($id);
            if ($oldResource && $oldResource['file_url']) {
                $oldFileName = basename(parse_url($oldResource['file_url'], PHP_URL_PATH));
                $oldFilePath = WRITEPATH . 'uploads/past_papers/' . $oldFileName;
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            // Upload new file
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads/past_papers/', $newName);

            $paperData['file_url'] = base_url('uploads/past_papers/' . $newName);
            $paperData['file_size'] = $this->formatBytes($file->getSize());
        }

        if ($this->pastPapersModel->update($id, $paperData)) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Past paper updated successfully'
                ]);
            }
            return redirect()->to(base_url('admin/library'))->with('success', 'Past paper updated successfully');
        }

        if ($isAjax) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update past paper'
            ]);
        }
        return redirect()->back()->with('error', 'Failed to update past paper');
    }

    private function updateVideo($id, bool $isAjax)
    {
        $validation = \Config\Services::validation();

        $rules = [
            'title' => 'required|max_length[200]',
            'exam_body' => 'required',
            'subject' => 'required',
            'status' => 'required|in_list[approved,pending_review]'
        ];

        $validation->setRules($rules);

        if (!$validation->withRequest($this->request)->run()) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validation->getErrors()
                ]);
            }
            return redirect()->back()->with('error', 'Validation failed')->with('errors', $validation->getErrors());
        }

        // Handle video embed code - check if it's a full iframe or URL
        $videoUrl = $this->request->getPost('video_url');
        $embedCode = '';
        $platform = '';
        $videoId = '';

        // If it's a full iframe HTML, use it directly
        if (stripos($videoUrl, '<iframe') !== false) {
            $embedCode = $videoUrl;
            $platform = 'youtube'; // Default, will be overridden if Vimeo detected
            $videoId = '';

            // Try to extract platform from iframe
            if (stripos($videoUrl, 'vimeo.com') !== false) {
                $platform = 'vimeo';
            }
        } else {
            // Process as regular URL
            $videoData = $this->extractVideoInfo($videoUrl);

            if (!$videoData) {
                $message = 'Invalid video URL. Please use YouTube or Vimeo links, or paste the full iframe embed code.';
                if ($isAjax) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => $message
                    ]);
                }
                return redirect()->back()->with('error', $message)->withInput();
            }

            $embedCode = $videoData['embed_code'];
            $platform = $videoData['platform'];
            $videoId = $videoData['id'];
        }

        $updateData = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'exam_body' => $this->request->getPost('exam_body'),
            'subject' => $this->request->getPost('subject'),
            'topic' => $this->request->getPost('topic'),
            'problem_year' => $this->request->getPost('problem_year'),
            'status' => $this->request->getPost('status'),
            'video_platform' => $platform,
            'video_id' => $videoId,
            'video_embed_code' => $embedCode
        ];

        // If status changed to approved, set approval time
        $resource = $this->tutorVideosModel->find($id);
        if ($resource && $resource['status'] !== 'approved' && $updateData['status'] === 'approved') {
            $updateData['approved_at'] = date('Y-m-d H:i:s');
        }

        if ($this->tutorVideosModel->update($id, $updateData)) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Video updated successfully'
                ]);
            }
            return redirect()->to(base_url('admin/library'))->with('success', 'Video updated successfully');
        }

        if ($isAjax) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update video'
            ]);
        }
        return redirect()->back()->with('error', 'Failed to update video');
    }

    private function extractVideoInfo($url)
    {
        // YouTube
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $url, $matches)) {
            $videoId = $matches[1];
            return [
                'platform' => 'youtube',
                'id' => $videoId,
                'embed_code' => '<iframe width="100%" height="400" src="https://www.youtube.com/embed/' . $videoId . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>'
            ];
        }

        // Vimeo
        if (preg_match('/vimeo\.com\/(\d+)/', $url, $matches)) {
            $videoId = $matches[1];
            return [
                'platform' => 'vimeo',
                'id' => $videoId,
                'embed_code' => '<iframe width="100%" height="400" src="https://player.vimeo.com/video/' . $videoId . '" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>'
            ];
        }

        return null;
    }

    // ============================
    // DATABASE BACKUP MANAGEMENT
    // ============================

    // Show backup management page
    public function backup()
    {
        $data = [
            'title' => 'Database Backup - TutorConnect Malawi',
        ];

        // Get list of existing backups
        $backupDir = WRITEPATH . 'backups';
        $data['backups'] = [];

        if (is_dir($backupDir)) {
            $files = scandir($backupDir);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
                    $filePath = $backupDir . DIRECTORY_SEPARATOR . $file;
                    $data['backups'][] = [
                        'filename' => $file,
                        'size' => filesize($filePath),
                        'created' => date('Y-m-d H:i:s', filemtime($filePath)),
                        'size_human' => $this->formatBytes(filesize($filePath))
                    ];
                }
            }
            // Sort by creation date (newest first)
            usort($data['backups'], function($a, $b) {
                return strtotime($b['created']) - strtotime($a['created']);
            });
        }

        // Get database info
        $dbConfig = config('Database');
        $data['db_info'] = [
            'database' => $dbConfig->default['database'] ?? 'tutorconnectmw',
            'total_backups' => count($data['backups']),
            'backup_dir' => $backupDir
        ];

        $data = $this->getAdminData($data);
        return view('admin/backup', $data);
    }

    // Create database backup
    public function createBackup()
    {
        try {
            log_message('info', 'Starting database backup creation');

            // Get database configuration
            $dbConfig = config('Database');
            $dbName = $dbConfig->default['database'] ?? 'tutorconnectmw';
            $dbHost = $dbConfig->default['hostname'] ?? 'localhost';
            $dbUser = $dbConfig->default['username'] ?? 'root';
            $dbPass = $dbConfig->default['password'] ?? '';

            // Create backup directory if it doesn't exist
            $backupDir = WRITEPATH . 'backups';
            if (!is_dir($backupDir)) {
                if (!mkdir($backupDir, 0755, true)) {
                    log_message('error', 'Failed to create backup directory: ' . $backupDir);
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Failed to create backup directory'
                    ]);
                }
            }

            // Generate filename with timestamp
            $timestamp = date('Y-m-d_H-i-s');
            $filename = 'backup_' . $dbName . '_' . $timestamp . '.sql';
            $filepath = $backupDir . DIRECTORY_SEPARATOR . $filename;

            log_message('info', 'Creating backup file: ' . $filepath);

            // Use mysqldump command to create backup
            $command = sprintf(
                'mysqldump --host=%s --user=%s --password=%s %s > %s 2>&1',
                escapeshellarg($dbHost),
                escapeshellarg($dbUser),
                escapeshellarg($dbPass),
                escapeshellarg($dbName),
                escapeshellarg($filepath)
            );

            // Execute the command
            $output = [];
            $returnCode = 0;
            exec($command, $output, $returnCode);

            if ($returnCode !== 0) {
                $errorMessage = implode("\n", $output);
                log_message('error', 'mysqldump failed with code ' . $returnCode . ': ' . $errorMessage);

                // Try alternative method using PHP
                log_message('info', 'Attempting alternative backup method using PHP');
                $result = $this->createBackupWithPHP($filepath, $dbConfig->default);

                if (!$result['success']) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Backup failed: ' . $result['message']
                    ]);
                }
            } else {
                log_message('info', 'Database backup created successfully: ' . $filename);
            }

            // Verify the file was created and has content
            if (!file_exists($filepath) || filesize($filepath) === 0) {
                log_message('error', 'Backup file was not created or is empty: ' . $filepath);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Backup file was not created properly'
                ]);
            }

            $fileSize = filesize($filepath);
            log_message('info', 'Backup completed successfully. File size: ' . $this->formatBytes($fileSize));

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Database backup created successfully!',
                'filename' => $filename,
                'size' => $this->formatBytes($fileSize),
                'created' => date('Y-m-d H:i:s')
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Exception during backup creation: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Backup failed: ' . $e->getMessage()
            ]);
        }
    }

    // Alternative backup method using PHP (fallback)
    private function createBackupWithPHP($filepath, $dbConfig)
    {
        try {
            // Connect to database
            $pdo = new \PDO(
                "mysql:host={$dbConfig['hostname']};dbname={$dbConfig['database']};charset=utf8mb4",
                $dbConfig['username'],
                $dbConfig['password']
            );

            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            // Get all tables
            $tables = $pdo->query('SHOW TABLES')->fetchAll(\PDO::FETCH_COLUMN);

            $sql = "-- Database backup created on " . date('Y-m-d H:i:s') . "\n";
            $sql .= "-- Database: {$dbConfig['database']}\n\n";
            $sql .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
            $sql .= "START TRANSACTION;\n";
            $sql .= "SET time_zone = \"+00:00\";\n\n";

            foreach ($tables as $table) {
                // Get table structure
                $sql .= "-- Table structure for table `$table`\n";
                $sql .= "DROP TABLE IF EXISTS `$table`;\n";

                $createTable = $pdo->query("SHOW CREATE TABLE `$table`")->fetch(\PDO::FETCH_ASSOC);
                $sql .= $createTable['Create Table'] . ";\n\n";

                // Get table data
                $rows = $pdo->query("SELECT * FROM `$table`")->fetchAll(\PDO::FETCH_ASSOC);

                if (!empty($rows)) {
                    $sql .= "-- Dumping data for table `$table`\n";
                    $sql .= "INSERT INTO `$table` (" . implode(', ', array_map(function($col) {
                        return "`$col`";
                    }, array_keys($rows[0]))) . ") VALUES\n";

                    $values = [];
                    foreach ($rows as $row) {
                        $rowValues = array_map(function($value) use ($pdo) {
                            if ($value === null) {
                                return 'NULL';
                            }
                            return $pdo->quote($value);
                        }, array_values($row));
                        $values[] = "(" . implode(', ', $rowValues) . ")";
                    }

                    $sql .= implode(",\n", $values) . ";\n\n";
                }
            }

            $sql .= "COMMIT;\n";

            // Write to file
            if (file_put_contents($filepath, $sql) === false) {
                return [
                    'success' => false,
                    'message' => 'Failed to write backup file'
                ];
            }

            return [
                'success' => true,
                'message' => 'Backup created successfully with PHP method'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'PHP backup method failed: ' . $e->getMessage()
            ];
        }
    }

    // Download backup file
    public function downloadBackup($filename)
    {
        $backupDir = WRITEPATH . 'backups';
        $filepath = $backupDir . DIRECTORY_SEPARATOR . $filename;

        // Security check - only allow .sql files
        if (pathinfo($filename, PATHINFO_EXTENSION) !== 'sql') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Check if file exists
        if (!file_exists($filepath)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Return file for download
        return $this->response
            ->setHeader('Content-Type', 'application/sql')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setHeader('Content-Length', filesize($filepath))
            ->setBody(file_get_contents($filepath));
    }

    // Delete backup file
    public function deleteBackup($filename)
    {
        $backupDir = WRITEPATH . 'backups';
        $filepath = $backupDir . DIRECTORY_SEPARATOR . $filename;

        // Security check - only allow .sql files
        if (pathinfo($filename, PATHINFO_EXTENSION) !== 'sql') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid file type'
            ]);
        }

        // Check if file exists
        if (!file_exists($filepath)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Backup file not found'
            ]);
        }

        // Delete the file
        if (unlink($filepath)) {
            log_message('info', 'Backup file deleted: ' . $filename);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Backup file deleted successfully'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to delete backup file'
        ]);
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
