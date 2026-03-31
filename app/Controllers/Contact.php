<?php

namespace App\Controllers;

use App\Models\ContactMessageModel;

class Contact extends BaseController
{
    protected $contactModel;

    public function __construct()
    {
        $this->contactModel = new ContactMessageModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Contact Us - TutorConnect Malawi'
        ];

        return view('contact/index', $data);
    }

    public function send()
    {
        log_message('info', 'Contact send method called');
        
        // Accept both normal form posts and AJAX submissions
        if (strtolower($this->request->getMethod()) !== 'post') {
            log_message('info', 'Not POST method, redirecting');
            return redirect()->to(base_url('contact'));
        }

        log_message('info', 'POST data received: ' . json_encode($this->request->getPost()));
        
        $isAjax = $this->request->isAJAX();

        // Get data from either JSON or POST
        if ($isAjax && strpos($this->request->getHeaderLine('Content-Type'), 'application/json') !== false) {
            $jsonData = $this->request->getJSON(true);
            $postData = $jsonData ?? [];
        } else {
            $postData = $this->request->getPost();
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => 'required|min_length[2]|max_length[100]',
            'email' => 'required|valid_email|max_length[150]',
            'phone' => 'permit_empty|min_length[8]|max_length[20]',
            'subject' => 'required|min_length[5]|max_length[200]',
            'message' => 'required|min_length[10]|max_length[2000]'
        ]);

        if (!$validation->run($postData)) {
            $errors = $validation->getErrors();
            log_message('error', 'Validation failed: ' . json_encode($errors));

            if ($isAjax) {
                return $this->response
                    ->setContentType('application/json')
                    ->setStatusCode(200)
                    ->setBody(json_encode([
                        'success' => false,
                        'errors' => $errors
                    ]));
            }

            session()->setFlashdata('error', 'Please fix the highlighted errors.');
            session()->setFlashdata('validation_errors', $errors);
            return redirect()->back()->withInput();
        }

        log_message('info', 'Validation passed, preparing data');
        
        // Get form data
        $data = [
            'name' => $postData['name'] ?? '',
            'email' => $postData['email'] ?? '',
            'subject' => $postData['subject'] ?? '',
            'message' => $postData['message'] ?? '',
            'phone' => $postData['phone'] ?? null,
            'service' => $postData['service'] ?? null,
            'is_read' => 0
        ];

        log_message('info', 'Data to insert: ' . json_encode($data));

        // Save to database
        try {
            $messageId = $this->contactModel->insert($data);
            log_message('info', 'Insert result: ' . ($messageId ? $messageId : 'false'));

            if ($messageId) {
                // Log the submission with key identifiers for audit
                log_message('info', 'Contact message stored', [
                    'id' => $messageId,
                    'email' => $data['email'],
                    'name' => $data['name'],
                    'subject' => $data['subject']
                ]);

                if ($isAjax) {
                    return $this->response
                        ->setContentType('application/json')
                        ->setStatusCode(200)
                        ->setBody(json_encode([
                            'success' => true,
                            'message' => 'Thank you for contacting us! We\'ll respond within 24 hours.'
                        ]));
                }

                session()->setFlashdata('success', 'Thank you for contacting us! We\'ll respond within 24 hours.');
                log_message('info', 'Redirecting with success message');
                return redirect()->to(base_url('contact'));
            }
        } catch (\Exception $e) {
            log_message('error', 'Contact message insert failed: ' . $e->getMessage());
            
            if ($isAjax) {
                return $this->response
                    ->setContentType('application/json')
                    ->setStatusCode(200)
                    ->setBody(json_encode([
                        'success' => false,
                        'message' => 'Failed to save your message. Please try again.'
                    ]));
            }
        }

        if ($isAjax) {
            return $this->response
                ->setContentType('application/json')
                ->setStatusCode(200)
                ->setBody(json_encode([
                    'success' => false,
                    'message' => 'Failed to save your message. Please try again.'
                ]));
        }

        session()->setFlashdata('error', 'Failed to save your message. Please try again.');
        return redirect()->back()->withInput();
    }
}



