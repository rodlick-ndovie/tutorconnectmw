<?php

namespace App\Controllers;

use App\Models\UserModel;

class Resubmit extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $token = $this->request->getGet('token');

        if (!$token) {
            return view('errors/html/error_404');
        }

        // Find user with this token
        $user = $this->userModel->where('resubmission_token', $token)->first();

        if (!$user) {
            return view('resubmit/expired', ['message' => 'Invalid token. This link may have been used or is incorrect.']);
        }

        // Check if token is expired (5 hours)
        if (strtotime($user['resubmission_token_expires']) < time()) {
            return view('resubmit/expired', ['message' => 'This link has expired. Please contact the admin to request a new link.']);
        }

        // Get documents requiring resubmission
        $verificationDocuments = json_decode($user['verification_documents'] ?? '[]', true) ?: [];
        $specialDocs = json_decode($user['resubmission_special_docs'] ?? '[]', true) ?: [];

        $requiredDocs = [];
        foreach ($verificationDocuments as $doc) {
            if (isset($doc['needs_resubmission']) && $doc['needs_resubmission']) {
                $requiredDocs[] = [
                    'type' => $doc['document_type'],
                    'name' => $this->getDocumentName($doc['document_type']),
                    'message' => $doc['resubmission_message'] ?? '',
                    'current_file' => $doc['file_path'] ?? null
                ];
            }
        }

        // Add special docs
        foreach ($specialDocs as $docType) {
            $requiredDocs[] = [
                'type' => $docType,
                'name' => $this->getDocumentName($docType),
                'message' => $verificationDocuments[0]['resubmission_message'] ?? '',
                'current_file' => $user[$docType] ?? null
            ];
        }

        $data = [
            'title' => 'Resubmit Documents',
            'user' => $user,
            'token' => $token,
            'requiredDocs' => $requiredDocs,
            'expiresAt' => $user['resubmission_token_expires']
        ];

        return view('resubmit/form', $data);
    }

    public function submit()
    {
        $token = $this->request->getPost('token');

        if (!$token) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        // Find user with this token
        $user = $this->userModel->where('resubmission_token', $token)->first();

        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid token']);
        }

        // Check if token is expired
        if (strtotime($user['resubmission_token_expires']) < time()) {
            return $this->response->setJSON(['success' => false, 'message' => 'This link has expired']);
        }

        $userId = $user['id'];
        $errors = [];
        $updateData = [];

        // Get documents requiring resubmission
        $verificationDocuments = json_decode($user['verification_documents'] ?? '[]', true) ?: [];
        $specialDocs = json_decode($user['resubmission_special_docs'] ?? '[]', true) ?: [];

        // Process special documents (video, profile photo, cover photo)
        if (in_array('intro_video', $specialDocs)) {
            $video = $this->request->getFile('intro_video');
            if ($video && $video->isValid() && !$video->hasMoved()) {
                $videoExt = strtolower($video->getExtension());
                if (!in_array($videoExt, ['mp4', 'webm', 'ogv', 'avi', 'mov'])) {
                    $errors[] = 'Invalid video type';
                } elseif ($video->getSize() > 50 * 1024 * 1024) {
                    $errors[] = 'Video exceeds 50MB limit';
                } else {
                    $newName = 'intro_video_' . $userId . '_' . time() . '.' . $videoExt;
                    $video->move(WRITEPATH . '../public/uploads/videos', $newName);
                    $updateData['bio_video'] = 'uploads/videos/' . $newName;
                }
            } else {
                $errors[] = 'Introduction video is required';
            }
        }

        if (in_array('profile_picture', $specialDocs)) {
            $profilePic = $this->request->getFile('profile_picture');
            if ($profilePic && $profilePic->isValid() && !$profilePic->hasMoved()) {
                if (!in_array($profilePic->getExtension(), ['jpg', 'jpeg', 'png', 'gif'])) {
                    $errors[] = 'Invalid profile picture type';
                } elseif ($profilePic->getSize() > 5 * 1024 * 1024) {
                    $errors[] = 'Profile picture exceeds 5MB limit';
                } else {
                    $newName = 'profile_' . $userId . '_' . time() . '.' . $profilePic->getExtension();
                    $profilePic->move(WRITEPATH . '../public/uploads/profile_photos', $newName);
                    $updateData['profile_picture'] = 'uploads/profile_photos/' . $newName;
                }
            } else {
                $errors[] = 'Profile picture is required';
            }
        }

        if (in_array('cover_photo', $specialDocs)) {
            $coverPhoto = $this->request->getFile('cover_photo');
            if ($coverPhoto && $coverPhoto->isValid() && !$coverPhoto->hasMoved()) {
                if (!in_array($coverPhoto->getExtension(), ['jpg', 'jpeg', 'png', 'gif'])) {
                    $errors[] = 'Invalid cover photo type';
                } elseif ($coverPhoto->getSize() > 10 * 1024 * 1024) {
                    $errors[] = 'Cover photo exceeds 10MB limit';
                } else {
                    $newName = 'cover_' . $userId . '_' . time() . '.' . $coverPhoto->getExtension();
                    $coverPhoto->move(WRITEPATH . '../public/uploads/profile_photos', $newName);
                    $updateData['cover_photo'] = 'uploads/profile_photos/' . $newName;
                }
            } else {
                $errors[] = 'Cover photo is required';
            }
        }

        // Process regular verification documents
        foreach ($verificationDocuments as $key => $doc) {
            if (isset($doc['needs_resubmission']) && $doc['needs_resubmission']) {
                $docType = $doc['document_type'];
                $file = $this->request->getFile($docType);

                if (!$file || !$file->isValid() || $file->hasMoved()) {
                    $errors[] = ucfirst(str_replace('_', ' ', $docType)) . ' is required';
                    continue;
                }

                if (!in_array($file->getExtension(), ['pdf', 'jpg', 'jpeg', 'png'])) {
                    $errors[] = ucfirst(str_replace('_', ' ', $docType)) . ' must be PDF or image';
                    continue;
                }

                if ($file->getSize() > 10 * 1024 * 1024) {
                    $errors[] = ucfirst(str_replace('_', ' ', $docType)) . ' exceeds 10MB limit';
                    continue;
                }

                $newName = $docType . '_' . $userId . '_' . time() . '.' . $file->getExtension();
                $file->move(WRITEPATH . '../public/uploads/documents', $newName);

                // Update this document in the array
                $verificationDocuments[$key]['file_path'] = 'uploads/documents/' . $newName;
                $verificationDocuments[$key]['original_filename'] = $file->getClientName();
                $verificationDocuments[$key]['uploaded_at'] = date('Y-m-d H:i:s');
                unset($verificationDocuments[$key]['needs_resubmission']);
                unset($verificationDocuments[$key]['resubmission_message']);
                unset($verificationDocuments[$key]['resubmission_requested_at']);
            }
        }

        if (!empty($errors)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $errors
            ]);
        }

        // Update user record
        $updateData['verification_documents'] = json_encode($verificationDocuments);
        $updateData['resubmission_token'] = null;
        $updateData['resubmission_token_expires'] = null;
        $updateData['resubmission_special_docs'] = null;
        $updateData['tutor_status'] = 'pending';
        $updateData['updated_at'] = date('Y-m-d H:i:s');

        $this->userModel->update($userId, $updateData);

        log_message('info', 'Tutor ' . $userId . ' successfully resubmitted documents');

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Documents submitted successfully! Your profile is now under review.'
        ]);
    }

    private function getDocumentName($type)
    {
        $names = [
            'intro_video' => 'Introduction Video',
            'profile_picture' => 'Profile Photo',
            'cover_photo' => 'Cover Photo',
            'national_id' => 'National ID Card',
            'academic_certificates' => 'Academic Certificates',
            'teaching_qualification' => 'Teaching Qualification',
            'police_clearance' => 'Police Clearance'
        ];

        return $names[$type] ?? ucwords(str_replace('_', ' ', $type));
    }
}
