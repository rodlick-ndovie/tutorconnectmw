<?php

namespace App\Controllers;

use App\Models\TutorModel;

class Register extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Register - TutorConnect Malawi',
            'step' => 1,
            'form_data' => [],
        ];

        return view('auth/register', $data);
    }

    public function step1()
    {
        $validation = \Config\Services::validation();

        $validation->setRules([
            'first_name' => 'required|min_length[2]|max_length[50]',
            'last_name' => 'required|min_length[2]|max_length[50]',
            'email' => 'required|valid_email|max_length[100]',
            'phone' => 'required|max_length[15]',
            'gender' => 'required',
            'district' => 'required',
            'location' => 'required|max_length[100]',
            'is_employed' => 'required',
            'school_name' => 'permit_empty|max_length[100]',
        ]);

        $data = $this->request->getPost();

        if ($this->request->getMethod() === 'post' && $validation->run($data)) {
            // Additional check: Verify email and phone are not already used by a trainer
            $tutorModel = new \App\Models\TutorModel();

            $existingEmail = $tutorModel->where('email', $data['email'])->where('role', 'trainer')->first();
            if ($existingEmail) {
                return redirect()->back()->withInput()->with('error', 'This email is already registered. Please use a different email.');
            }

            $existingPhone = $tutorModel->where('phone', $data['phone'])->where('role', 'trainer')->first();
            if ($existingPhone) {
                return redirect()->back()->withInput()->with('error', 'This phone number is already registered. Please use a different phone number.');
            }

            // Save to session or proceed to next step
            return redirect()->to('register/step2')->withInput()->with('form_data', $data);
        }

        return redirect()->back()->withInput()->with('errors', $validation->getErrors());
    }

    public function step2()
    {
        $validation = \Config\Services::validation();

        $validation->setRules([
            'username' => 'required|min_length[3]|max_length[20]',
            'password' => 'required|min_length[8]',
            'confirm_password' => 'required|matches[password]',
        ]);

        $data = $this->request->getPost();

        if ($this->request->getMethod() === 'post' && $validation->run($data)) {
            // Additional check: Verify username is not already used by a trainer
            $tutorModel = new \App\Models\TutorModel();

            $existingUsername = $tutorModel->where('username', $data['username'])->where('role', 'trainer')->first();
            if ($existingUsername) {
                return redirect()->back()->withInput()->with('error', 'This username is already taken. Please choose a different username.');
            }

            // Save to database and register the user
            $tutorModel->save([
                'first_name' => $this->request->getVar('first_name'),
                'last_name' => $this->request->getVar('last_name'),
                'email' => $this->request->getVar('email'),
                'phone' => $this->request->getVar('phone'),
                'gender' => $this->request->getVar('gender'),
                'district' => $this->request->getVar('district'),
                'location' => $this->request->getVar('location'),
                'is_employed' => $this->request->getVar('is_employed'),
                'school_name' => $this->request->getVar('school_name'),
                'username' => $this->request->getVar('username'),
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
                'role' => 'trainer',
                'terms_accepted' => 1, // User agreed to terms during registration
            ]);

            return redirect()->to('login')->with('success', 'Registration successful! You can now log in.');
        }

        return redirect()->back()->withInput()->with('errors', $validation->getErrors());
    }

    public function checkAvailability()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['available' => true]);
        }

        $json = $this->request->getJSON();
        $field = $json->field ?? '';
        $value = $json->value ?? '';

        if (empty($field) || empty($value)) {
            return $this->response->setJSON(['available' => true]);
        }

        $tutorModel = new \App\Models\TutorModel();

        $available = true;

        switch ($field) {
            case 'email':
                $existing = $tutorModel->where('email', $value)->first();
                $available = empty($existing);
                break;

            case 'phone':
                $existing = $tutorModel->where('phone', $value)->first();
                $available = empty($existing);
                break;

            case 'username':
                $existing = $tutorModel->where('username', $value)->first();
                $available = empty($existing);
                break;
        }

        return $this->response->setJSON(['available' => $available]);
    }

    public function checkEmail()
    {
        if ($this->request->isAJAX()) {
            $json = $this->request->getJSON();
            $email = $json->email ?? '';

            $tutorModel = new \App\Models\TutorModel();
            $exists = $tutorModel->where('email', $email)->where('role', 'trainer')->first() !== null;

            return $this->response->setJSON(['exists' => $exists]);
        }
    }

    public function checkPhone()
    {
        if ($this->request->isAJAX()) {
            $json = $this->request->getJSON();
            $phone = $json->phone ?? '';

            $tutorModel = new \App\Models\TutorModel();
            $exists = $tutorModel->where('phone', $phone)->where('role', 'trainer')->first() !== null;

            return $this->response->setJSON(['exists' => $exists]);
        }
    }

    public function checkUsername()
    {
        if ($this->request->isAJAX()) {
            $json = $this->request->getJSON();
            $username = $json->username ?? '';

            $tutorModel = new \App\Models\TutorModel();
            $exists = $tutorModel->where('username', $username)->where('role', 'trainer')->first() !== null;

            return $this->response->setJSON(['exists' => $exists]);
        }
    }
}
