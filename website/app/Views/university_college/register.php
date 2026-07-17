<?php
$errors = session('errors') ?? [];
$step = (int) ($step ?? 1);
$formData = $form_data ?? [];
$workStatus = old('work_status', $formData['work_status'] ?? '');
$accountType = old('account_type', $formData['account_type'] ?? 'individual');
$isFirm = $accountType === 'firm';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Register - University & College Support') ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #E74C3C;
            --secondary: #2C3E50;
            --accent: #34495E;
            --gray: #666;
            --light-gray: #e2e8f0;
            --success: #27ae60;
            --danger: #e74c3c;
        }

        * {
            box-sizing: border-box;
        }

        body, html {
            min-height: 100%;
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: white;
            overflow-x: hidden;
        }

        .register-wrapper {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        .image-side {
            background: linear-gradient(rgba(231, 76, 60, 0.92), rgba(231, 76, 60, 0.96)),
                        url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=1200&h=1200&fit=crop&crop=center') center/cover;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            padding: 2.5rem;
            position: relative;
        }

        .image-side::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.12);
        }

        .image-content {
            position: relative;
            z-index: 1;
            max-width: 520px;
        }

        .image-side h1 {
            font-size: 2.1rem;
            font-weight: 700;
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .image-side p {
            font-size: 1rem;
            opacity: 0.96;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .feature-list {
            text-align: left;
            display: inline-flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-top: 1rem;
        }

        .feature-list span {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 500;
        }

        .form-side {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: white;
        }

        .form-box {
            width: 100%;
            max-width: 460px;
            animation: fadeIn 0.4s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .logo {
            font-size: 1.25rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 0.75rem;
            letter-spacing: 0;
        }

        .logo span:first-child {
            color: var(--primary);
        }

        .logo span:last-child {
            color: var(--secondary);
        }

        .intro {
            text-align: center;
            color: var(--gray);
            margin-bottom: 1.25rem;
            font-size: 0.95rem;
        }

        .step-indicator {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 1.5rem 0;
            gap: 1rem;
        }

        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--light-gray);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: var(--gray);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .step-circle.active {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 12px rgba(231, 76, 60, 0.25);
        }

        .step-circle.completed {
            background: var(--success);
            color: white;
        }

        .step-line {
            width: 60px;
            height: 3px;
            background: var(--light-gray);
        }

        .step-line.completed {
            background: var(--success);
        }

        .step-label {
            text-align: center;
            font-size: 0.75rem;
            color: var(--gray);
            margin-top: 0.5rem;
            font-weight: 500;
        }

        .alert {
            border-radius: 10px;
            border: none;
            margin-bottom: 1rem;
        }

        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .input-group input,
        .input-group select,
        .input-group textarea {
            width: 100%;
            padding: 0.95rem 0 0.45rem;
            font-size: 0.95rem;
            border: none;
            border-bottom: 2px solid var(--light-gray);
            background: transparent;
            outline: none;
            transition: all 0.3s ease;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            border-radius: 0;
            resize: vertical;
        }

        .input-group select {
            cursor: pointer;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23666' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6,9 12,15 18,9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.5rem center;
            background-size: 1rem;
            padding-right: 2rem;
        }

        .input-group label {
            position: absolute;
            top: 0.95rem;
            left: 0;
            font-size: 0.95rem;
            color: var(--gray);
            pointer-events: none;
            transition: all 0.25s ease;
            font-weight: 500;
        }

        .input-group input:focus,
        .input-group select:focus,
        .input-group textarea:focus {
            border-bottom-color: var(--primary);
        }

        .input-group input:focus ~ label,
        .input-group input:not(:placeholder-shown) ~ label,
        .input-group textarea:focus ~ label,
        .input-group textarea:not(:placeholder-shown) ~ label,
        .input-group select.has-value ~ label,
        .input-group select:focus ~ label {
            top: -0.55rem;
            font-size: 0.75rem;
            color: var(--primary);
            font-weight: 600;
            background: white;
            padding: 0 4px;
        }

        .input-group textarea {
            min-height: 96px;
        }

        .input-group .tooltip-hint {
            margin-top: 0.35rem;
            font-size: 0.8rem;
            color: var(--gray);
        }

        .radio-title {
            color: var(--secondary);
            font-weight: 600;
            margin-bottom: 0.75rem;
            display: block;
            font-size: 0.95rem;
        }

        .radio-row {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .radio-group {
            position: relative;
            min-height: 52px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--light-gray);
            border-radius: 10px;
            background: #fff;
            color: var(--secondary);
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: center;
        }

        .radio-group input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .radio-group span {
            width: 100%;
            padding: 0.78rem 0.9rem;
            font-weight: 700;
            font-size: 0.9rem;
            line-height: 1.25;
        }

        .radio-group:has(input:checked) {
            border-color: var(--primary);
            background: rgba(231, 76, 60, 0.06);
            box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.08);
        }

        .radio-group:has(input:focus-visible) {
            outline: 3px solid rgba(231, 76, 60, 0.18);
            outline-offset: 2px;
        }

        .radio-group:has(input:checked) span {
            color: var(--primary);
        }

        .terms-card {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            border: 1px solid var(--light-gray);
            border-radius: 10px;
            padding: 0.9rem 1rem;
            background: #fff;
            color: var(--secondary);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .terms-card:has(input:checked) {
            border-color: var(--primary);
            background: rgba(231, 76, 60, 0.05);
        }

        .terms-card input {
            width: 18px;
            height: 18px;
            margin-top: 0.1rem;
            accent-color: var(--primary);
            flex-shrink: 0;
        }

        .terms-card span {
            font-size: 0.9rem;
            line-height: 1.5;
            font-weight: 500;
        }

        @media (max-width: 480px) {
            .radio-row {
                grid-template-columns: 1fr;
            }
        }

        .btn-primary {
            background: var(--primary);
            border: none;
            border-radius: 8px;
            padding: 0.75rem;
            font-weight: 600;
            font-size: 0.92rem;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #c0392b;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(231, 76, 60, 0.25);
        }

        .btn-secondary-custom {
            background: var(--light-gray);
            color: var(--secondary);
            border: none;
            border-radius: 8px;
            padding: 0.75rem;
            font-weight: 600;
            font-size: 0.92rem;
            text-decoration: none;
            width: 48%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-secondary-custom:hover {
            background: #d1d5db;
            color: var(--secondary);
        }

        .btn-group-custom {
            display: flex;
            gap: 4%;
        }

        .invalid-feedback {
            display: block;
            font-size: 0.8rem;
            color: var(--danger);
            margin-top: 0.25rem;
        }

        .d-none {
            display: none !important;
        }

        .account-link {
            text-align: center;
            margin-top: 1.25rem;
            color: var(--gray);
            font-size: 0.92rem;
        }

        .account-link a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
        }

        @media (max-width: 991px) {
            .register-wrapper {
                grid-template-columns: 1fr;
            }

            .image-side {
                min-height: 320px;
            }
        }

        @media (max-width: 575px) {
            .form-side {
                padding: 1.25rem;
            }

            .image-side {
                padding: 2rem 1.25rem;
            }

            .btn-group-custom {
                flex-direction: column;
                gap: 0.75rem;
            }

            .btn-secondary-custom,
            #submitBtn2 {
                width: 100% !important;
            }
        }
    </style>
</head>
<body>
<div class="register-wrapper">
    <div class="image-side">
        <div class="image-content">
            <h1>Join the University Expert Portal</h1>
            <p>Register as an individual expert or a firm using the same guided account flow, while keeping the university support details this module needs.</p>
            <p>Your login account, verification flow, and review status now follow the main tutor system.</p>

            <div class="feature-list">
                <span><i class="fas fa-check-circle"></i> Real tutor account creation</span>
                <span><i class="fas fa-check-circle"></i> Email verification before access</span>
                <span><i class="fas fa-check-circle"></i> University profile tracked for admin review</span>
            </div>
        </div>
    </div>

    <div class="form-side">
        <div class="form-box">
            <div class="logo">
                <span>TutorConnect</span> <span>Malawi</span>
            </div>
            <p class="intro">University & college expert registration</p>

            <div class="step-indicator">
                <div>
                    <div class="step-circle <?= $step >= 1 ? 'active' : '' ?> <?= $step > 1 ? 'completed' : '' ?>">
                        <?= $step > 1 ? '<i class="fas fa-check"></i>' : '1' ?>
                    </div>
                    <div class="step-label">Personal Info</div>
                </div>
                <div class="step-line <?= $step > 1 ? 'completed' : '' ?>"></div>
                <div>
                    <div class="step-circle <?= $step >= 2 ? 'active' : '' ?>">2</div>
                    <div class="step-label">Account Setup</div>
                </div>
            </div>

            <?php if (session()->has('success')): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i><?= esc(session('success')) ?>
                </div>
            <?php endif; ?>

            <?php if (session()->has('error')): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i><?= esc(session('error')) ?>
                </div>
            <?php endif; ?>

            <form id="step1Form" class="<?= $step !== 1 ? 'd-none' : '' ?>" method="post" action="<?= base_url('university-college-support/registerStep1') ?>">
                <?= csrf_field() ?>

                <h6 class="mb-4" style="color: var(--secondary);">Basic Information</h6>

                <div class="mb-3">
                    <span class="radio-title">Account Type</span>
                    <div class="radio-row">
                        <label class="radio-group" for="account_type_individual">
                            <input type="radio" name="account_type" id="account_type_individual" value="individual" <?= $accountType !== 'firm' ? 'checked' : '' ?>>
                            <span>Individual Expert</span>
                        </label>
                        <label class="radio-group" for="account_type_firm">
                            <input type="radio" name="account_type" id="account_type_firm" value="firm" <?= $isFirm ? 'checked' : '' ?>>
                            <span>Firm</span>
                        </label>
                    </div>
                    <?php if (!empty($errors['account_type'])): ?>
                        <div class="invalid-feedback d-block"><?= esc($errors['account_type']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="input-group" id="firmNameGroup" style="<?= $isFirm ? '' : 'display: none;' ?>">
                    <input type="text" name="firm_name" placeholder=" " value="<?= esc(old('firm_name', $formData['firm_name'] ?? '')) ?>">
                    <label>Firm / Company Name</label>
                    <div class="tooltip-hint">This is the public name shown in the university expert listings</div>
                    <?php if (!empty($errors['firm_name'])): ?>
                        <div class="invalid-feedback"><?= esc($errors['firm_name']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" name="first_name" placeholder=" " value="<?= esc(old('first_name', $formData['first_name'] ?? '')) ?>" required pattern="[A-Za-z\s]{2,50}" title="First name should contain letters only">
                            <label data-individual-label="First Name" data-firm-label="Contact First Name"><?= $isFirm ? 'Contact First Name' : 'First Name' ?></label>
                            <?php if (!empty($errors['first_name'])): ?>
                                <div class="invalid-feedback"><?= esc($errors['first_name']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" name="last_name" placeholder=" " value="<?= esc(old('last_name', $formData['last_name'] ?? '')) ?>" required pattern="[A-Za-z\s]{2,50}" title="Last name should contain letters only">
                            <label data-individual-label="Last Name" data-firm-label="Contact Last Name"><?= $isFirm ? 'Contact Last Name' : 'Last Name' ?></label>
                            <?php if (!empty($errors['last_name'])): ?>
                                <div class="invalid-feedback"><?= esc($errors['last_name']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="input-group">
                    <input type="email" name="email" id="email" placeholder=" " value="<?= esc(old('email', $formData['email'] ?? '')) ?>" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                    <label>Email Address</label>
                    <div class="tooltip-hint" id="email-hint">We'll use this for verification and account access</div>
                    <?php if (!empty($errors['email'])): ?>
                        <div class="invalid-feedback"><?= esc($errors['email']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="input-group">
                    <input type="tel" name="phone" id="phone" placeholder=" " value="<?= esc(old('phone', $formData['phone'] ?? '')) ?>" required pattern="(\+265|0)?[1-9][0-9]{7,8}" title="Please enter a valid Malawi phone number">
                    <label>Phone Number</label>
                    <div class="tooltip-hint" id="phone-hint">Format: 0991234567 or +265991234567</div>
                    <?php if (!empty($errors['phone'])): ?>
                        <div class="invalid-feedback"><?= esc($errors['phone']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="input-group">
                    <input type="text" name="year_of_study_or_graduation" placeholder=" " value="<?= esc(old('year_of_study_or_graduation', $formData['year_of_study_or_graduation'] ?? '')) ?>" required>
                    <label data-individual-label="Year of Study or Graduation" data-firm-label="Year Established / Registered"><?= $isFirm ? 'Year Established / Registered' : 'Year of Study or Graduation' ?></label>
                    <?php if (!empty($errors['year_of_study_or_graduation'])): ?>
                        <div class="invalid-feedback"><?= esc($errors['year_of_study_or_graduation']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="input-group">
                    <select name="teaching_mode" id="teaching_mode" required>
                        <option value=""></option>
                        <?php foreach (($teachingModes ?? []) as $mode): ?>
                            <option value="<?= esc($mode) ?>" <?= old('teaching_mode', $formData['teaching_mode'] ?? '') === $mode ? 'selected' : '' ?>><?= esc($mode) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <label>Preferred Teaching Mode</label>
                    <?php if (!empty($errors['teaching_mode'])): ?>
                        <div class="invalid-feedback"><?= esc($errors['teaching_mode']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="input-group">
                    <input type="text" name="city_location" placeholder=" " value="<?= esc(old('city_location', $formData['city_location'] ?? '')) ?>" required>
                    <label>City / Location</label>
                    <div class="tooltip-hint">Where you normally teach or support learners from</div>
                    <?php if (!empty($errors['city_location'])): ?>
                        <div class="invalid-feedback"><?= esc($errors['city_location']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3" id="workStatusBlock" style="<?= $isFirm ? 'display: none;' : '' ?>">
                    <span class="radio-title">Currently Employed?</span>
                    <div class="radio-row">
                        <label class="radio-group" for="work_status_employed">
                            <input type="radio" name="work_status" id="work_status_employed" value="Employed" <?= $workStatus === 'Employed' ? 'checked' : '' ?>>
                            <span>Yes</span>
                        </label>
                        <label class="radio-group" for="work_status_not_employed">
                            <input type="radio" name="work_status" id="work_status_not_employed" value="Not Employed" <?= $workStatus === 'Not Employed' ? 'checked' : '' ?>>
                            <span>No</span>
                        </label>
                    </div>
                    <?php if (!empty($errors['work_status'])): ?>
                        <div class="invalid-feedback"><?= esc($errors['work_status']) ?></div>
                    <?php endif; ?>
                </div>

                <div id="employerFields" style="<?= (!$isFirm && $workStatus === 'Employed') ? '' : 'display: none;' ?>">
                    <div class="input-group">
                        <input type="text" name="employer_name" placeholder=" " value="<?= esc(old('employer_name', $formData['employer_name'] ?? '')) ?>">
                        <label>Employer / Institution Name</label>
                        <?php if (!empty($errors['employer_name'])): ?>
                            <div class="invalid-feedback"><?= esc($errors['employer_name']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="input-group">
                        <input type="text" name="employer_contact" placeholder=" " value="<?= esc(old('employer_contact', $formData['employer_contact'] ?? '')) ?>">
                        <label>Employer Contact</label>
                        <?php if (!empty($errors['employer_contact'])): ?>
                            <div class="invalid-feedback"><?= esc($errors['employer_contact']) ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="input-group">
                    <textarea name="bio" placeholder=" " required><?= esc(old('bio', $formData['bio'] ?? '')) ?></textarea>
                    <label data-individual-label="Short Competency Profile / Bio" data-firm-label="Company Profile"><?= $isFirm ? 'Company Profile' : 'Short Competency Profile / Bio' ?></label>
                    <div class="tooltip-hint">Minimum 40 characters</div>
                    <?php if (!empty($errors['bio'])): ?>
                        <div class="invalid-feedback"><?= esc($errors['bio']) ?></div>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-primary" id="submitBtn1">
                    <span class="spinner-border spinner-border-sm me-2 d-none"></span>
                    Next Step <i class="fas fa-arrow-right ms-2"></i>
                </button>
            </form>

            <form id="step2Form" class="<?= $step !== 2 ? 'd-none' : '' ?>" method="post" action="<?= base_url('university-college-support/registerStep2') ?>">
                <?= csrf_field() ?>

                <h6 class="mb-4" style="color: var(--secondary);">Account Setup</h6>

                <div class="input-group">
                    <input type="text" name="username" id="username" placeholder=" " value="<?= esc(old('username', $formData['username'] ?? '')) ?>" required pattern="[A-Za-z0-9_]{4,20}" title="Username must be 4-20 characters">
                    <label>Username</label>
                    <div class="tooltip-hint" id="username-hint">Used for login only</div>
                    <?php if (!empty($errors['username'])): ?>
                        <div class="invalid-feedback"><?= esc($errors['username']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="input-group">
                    <input type="password" name="password" id="password" placeholder=" " required minlength="8" pattern="^(?=.*\d).{8,}$" title="Password must be at least 8 characters and contain at least one number">
                    <label>Password</label>
                    <div class="tooltip-hint" id="password-hint">At least 8 characters with one number</div>
                    <?php if (!empty($errors['password'])): ?>
                        <div class="invalid-feedback"><?= esc($errors['password']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="input-group">
                    <input type="password" name="confirm_password" id="confirm_password" placeholder=" " required>
                    <label>Confirm Password</label>
                    <div class="tooltip-hint" id="confirm-password-hint">Passwords must match</div>
                    <?php if (!empty($errors['confirm_password'])): ?>
                        <div class="invalid-feedback"><?= esc($errors['confirm_password']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="terms-card" for="termsCheckbox">
                        <input type="checkbox" id="termsCheckbox" name="accept_terms" value="1" <?= old('accept_terms', '1') ? 'checked' : '' ?>>
                        <span>I confirm the information I provided is accurate and I agree to the portal terms.</span>
                    </label>
                    <?php if (!empty($errors['accept_terms'])): ?>
                        <div class="invalid-feedback d-block"><?= esc($errors['accept_terms']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="btn-group-custom">
                    <a href="<?= base_url('university-college-support/register/back') ?>" class="btn-secondary-custom">
                        <i class="fas fa-arrow-left me-2"></i> Back
                    </a>
                    <button type="submit" class="btn btn-primary" id="submitBtn2" style="width: 48%;">
                        <span class="spinner-border spinner-border-sm me-2 d-none"></span>
                        Complete <i class="fas fa-check ms-2"></i>
                    </button>
                </div>
            </form>

            <p class="account-link">
                Already have an account?
                <a href="<?= base_url('login') ?>">Sign in here</a>
            </p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function syncSelectLabels() {
        document.querySelectorAll('select').forEach((select) => {
            if (select.value) {
                select.classList.add('has-value');
            } else {
                select.classList.remove('has-value');
            }
            select.addEventListener('change', function () {
                this.classList.toggle('has-value', !!this.value);
            });
        });
    }

    function syncAccountTypeLabels() {
        const selected = document.querySelector('input[name="account_type"]:checked');
        const isFirm = selected && selected.value === 'firm';

        document.querySelectorAll('[data-individual-label][data-firm-label]').forEach((label) => {
            label.textContent = isFirm ? label.dataset.firmLabel : label.dataset.individualLabel;
        });

        const firmNameGroup = document.getElementById('firmNameGroup');
        if (firmNameGroup) {
            firmNameGroup.style.display = isFirm ? 'block' : 'none';
        }

        const workStatusBlock = document.getElementById('workStatusBlock');
        const employerFields = document.getElementById('employerFields');

        if (workStatusBlock) {
            workStatusBlock.style.display = isFirm ? 'none' : 'block';
        }

        if (isFirm) {
            document.querySelectorAll('input[name="work_status"]').forEach((radio) => {
                radio.checked = false;
            });

            if (employerFields) {
                employerFields.style.display = 'none';
            }
        }
    }

    function setAvailabilityState(input, hint, available, okText, badText) {
        if (!input || !hint) return;
        input.dataset.available = available ? 'true' : 'false';
        input.style.borderBottomColor = available ? 'var(--success)' : 'var(--danger)';
        hint.style.color = available ? 'var(--success)' : 'var(--danger)';
        hint.textContent = available ? okText : badText;
    }

    syncSelectLabels();
    syncAccountTypeLabels();

    document.querySelectorAll('input[name="account_type"]').forEach((radio) => {
        radio.addEventListener('change', syncAccountTypeLabels);
    });

    document.querySelectorAll('input[name="work_status"]').forEach((radio) => {
        radio.addEventListener('change', function () {
            const selectedAccountType = document.querySelector('input[name="account_type"]:checked');
            if (selectedAccountType && selectedAccountType.value === 'firm') {
                return;
            }

            const employerFields = document.getElementById('employerFields');
            if (!employerFields) return;
            employerFields.style.display = this.value === 'Employed' ? 'block' : 'none';
        });
    });

    const step1Form = document.getElementById('step1Form');
    if (step1Form) {
        step1Form.addEventListener('submit', function (e) {
            const requiredFields = step1Form.querySelectorAll('input[required], textarea[required], select[required]');
            let isValid = true;

            requiredFields.forEach((field) => {
                if (!field.checkValidity()) {
                    field.style.borderBottomColor = 'var(--danger)';
                    isValid = false;
                } else {
                    field.style.borderBottomColor = 'var(--success)';
                }
            });

            const emailInput = document.getElementById('email');
            if (emailInput && emailInput.dataset.available === 'false') {
                e.preventDefault();
                emailInput.focus();
                return false;
            }

            const phoneInput = document.getElementById('phone');
            if (phoneInput && phoneInput.dataset.available === 'false') {
                e.preventDefault();
                phoneInput.focus();
                return false;
            }

            if (!isValid) {
                e.preventDefault();
                return false;
            }

            const submitBtn = document.getElementById('submitBtn1');
            const spinner = submitBtn?.querySelector('.spinner-border');
            if (spinner) {
                spinner.classList.remove('d-none');
                submitBtn.disabled = true;
            }
        });
    }

    const step2Form = document.getElementById('step2Form');
    if (step2Form) {
        step2Form.addEventListener('submit', function (e) {
            const usernameInput = document.getElementById('username');
            if (usernameInput && usernameInput.dataset.available === 'false') {
                e.preventDefault();
                usernameInput.focus();
                return false;
            }

            const submitBtn = document.getElementById('submitBtn2');
            const spinner = submitBtn?.querySelector('.spinner-border');
            if (spinner) {
                spinner.classList.remove('d-none');
                submitBtn.disabled = true;
            }
        });
    }

    const emailInput = document.getElementById('email');
    if (emailInput) {
        let emailTimeout;
        emailInput.addEventListener('input', function () {
            clearTimeout(emailTimeout);
            const email = this.value;
            const hint = document.getElementById('email-hint');

            if (email.length > 3 && this.checkValidity()) {
                emailTimeout = setTimeout(() => {
                    fetch('<?= base_url('university-college-support/check-email') ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ email: email })
                    })
                    .then((response) => response.json())
                    .then((data) => {
                        setAvailabilityState(emailInput, hint, !data.exists, 'Email address is available', 'Email address is already registered');
                    })
                    .catch(() => {
                        hint.textContent = 'We could not check email availability right now';
                    });
                }, 450);
            }
        });
    }

    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        let phoneTimeout;
        phoneInput.addEventListener('input', function () {
            clearTimeout(phoneTimeout);
            const phone = this.value;
            const hint = document.getElementById('phone-hint');

            if (phone.length > 8 && this.checkValidity()) {
                phoneTimeout = setTimeout(() => {
                    fetch('<?= base_url('university-college-support/check-phone') ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ phone: phone })
                    })
                    .then((response) => response.json())
                    .then((data) => {
                        setAvailabilityState(phoneInput, hint, !data.exists, 'Phone number is available', 'Phone number is already registered');
                    })
                    .catch(() => {
                        hint.textContent = 'We could not check phone availability right now';
                    });
                }, 450);
            }
        });
    }

    const usernameInput = document.getElementById('username');
    if (usernameInput) {
        let usernameTimeout;
        usernameInput.addEventListener('input', function () {
            clearTimeout(usernameTimeout);
            const username = this.value;
            const hint = document.getElementById('username-hint');

            if (username.length > 3 && this.checkValidity()) {
                usernameTimeout = setTimeout(() => {
                    fetch('<?= base_url('university-college-support/check-username') ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ username: username })
                    })
                    .then((response) => response.json())
                    .then((data) => {
                        setAvailabilityState(usernameInput, hint, !data.exists, 'Username is available', 'Username is already taken');
                    })
                    .catch(() => {
                        hint.textContent = 'We could not check username availability right now';
                    });
                }, 450);
            }
        });
    }

    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const passwordHint = document.getElementById('password-hint');
    const confirmPasswordHint = document.getElementById('confirm-password-hint');

    if (passwordInput && passwordHint) {
        passwordInput.addEventListener('input', function () {
            const hasMinLength = this.value.length >= 8;
            const hasNumber = /\d/.test(this.value);
            const isValid = hasMinLength && hasNumber;

            if (!this.value.length) {
                this.style.borderBottomColor = 'var(--light-gray)';
                passwordHint.style.color = 'var(--gray)';
                passwordHint.textContent = 'At least 8 characters with one number';
                return;
            }

            this.style.borderBottomColor = isValid ? 'var(--success)' : 'var(--danger)';
            passwordHint.style.color = isValid ? 'var(--success)' : 'var(--danger)';
            passwordHint.textContent = isValid
                ? 'Password is strong'
                : (!hasMinLength ? 'At least 8 characters required' : 'Must contain at least one number');
        });
    }

    if (confirmPasswordInput && confirmPasswordHint && passwordInput) {
        confirmPasswordInput.addEventListener('input', function () {
            if (!this.value.length) {
                this.style.borderBottomColor = 'var(--light-gray)';
                confirmPasswordHint.style.color = 'var(--gray)';
                confirmPasswordHint.textContent = 'Passwords must match';
                return;
            }

            const matches = this.value === passwordInput.value;
            this.style.borderBottomColor = matches ? 'var(--success)' : 'var(--danger)';
            confirmPasswordHint.style.color = matches ? 'var(--success)' : 'var(--danger)';
            confirmPasswordHint.textContent = matches ? 'Passwords match' : 'Passwords do not match';
        });
    }
</script>
</body>
</html>
