<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>

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
            --warning: #f39c12;
            --danger: #e74c3c;
        }

        * {
            box-sizing: border-box;
        }

        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: white;
            overflow-x: hidden;
        }

        .register-wrapper {
            height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 700px;
        }

        /* Left: Professional Message */
        .image-side {
            background: linear-gradient(rgba(231,76,60,0.95), rgba(231,76,60,0.98)),
                        url('https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=1200&h=800&fit=crop&crop=center') center/cover;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            padding: 2rem;
            position: relative;
        }

        .image-side::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.1);
        }

        .image-content {
            position: relative;
            z-index: 1;
            max-width: 500px;
        }

        .image-side h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            line-height: 1.2;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .image-side p {
            font-size: 1rem;
            opacity: 0.95;
            font-weight: 500;
            line-height: 1.5;
            margin-bottom: 1.5rem;
        }

        /* Right: Form Section */
        .form-side {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: white;
            overflow-y: auto;
        }

        .form-box {
            width: 100%;
            max-width: 400px;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Logo */
        .logo {
            font-size: 1.25rem;
            font-weight: 600;
            text-align: center;
            margin-bottom: 0.75rem;
            letter-spacing: -0.5px;
        }

        .logo span:first-child {
            color: var(--primary);
        }

        .logo span:last-child {
            color: var(--secondary);
        }

        /* Step Indicator */
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
            box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
        }

        .step-circle.completed {
            background: var(--success);
            color: white;
        }

        .step-line {
            width: 60px;
            height: 3px;
            background: var(--light-gray);
            transition: all 0.3s ease;
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

        /* Form Elements */
        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .input-group input,
        .input-group select,
        .input-group textarea {
            width: 100%;
            padding: 0.9rem 0 0.4rem;
            font-size: 0.95rem;
            border: none;
            border-bottom: 2px solid var(--light-gray);
            background: transparent;
            outline: none;
            transition: all 0.3s ease;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
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
            top: 0.9rem;
            left: 0;
            font-size: 0.95rem;
            color: var(--gray);
            pointer-events: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .input-group input:focus ~ label,
        .input-group input:not(:placeholder-shown) ~ label,
        .input-group select:focus ~ label,
        .input-group select:not([value=""]) ~ label {
            top: -0.5rem;
            font-size: 0.75rem;
            color: var(--primary);
            font-weight: 600;
            background: white;
            padding: 0 4px;
        }

        .input-group input:focus,
        .input-group select:focus {
            border-bottom-color: var(--primary);
        }

        .radio-group {
            display: inline-block;
            margin-right: 1.5rem;
        }

        .radio-group input[type="radio"] {
            margin-right: 0.5rem;
        }

        /* Buttons */
        .btn-primary {
            background: var(--primary);
            border: none;
            border-radius: 8px;
            padding: 0.65rem;
            font-weight: 600;
            font-size: 0.875rem;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #c0392b;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
        }

        .btn-secondary-custom {
            background: var(--light-gray);
            color: var(--secondary);
            border: none;
            border-radius: 8px;
            padding: 0.65rem;
            font-weight: 600;
            font-size: 0.875rem;
            width: 48%;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-secondary-custom:hover {
            background: #d1d5db;
            color: var(--secondary);
        }

        .btn-group-custom {
            display: flex;
            gap: 4%;
            margin-top: 1.5rem;
        }

        /* Alerts */
        .alert {
            border: none;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        /* Validation */
        .invalid-feedback {
            font-size: 0.8rem;
            color: var(--danger);
            margin-top: 0.25rem;
        }

        .tooltip-hint {
            font-size: 0.75rem;
            color: var(--gray);
            margin-top: 0.25rem;
        }

        /* Hidden class */
        .d-none {
            display: none !important;
        }

        /* Responsive */
        @media (max-width: 991px) {
            .register-wrapper {
                grid-template-columns: 1fr;
                height: auto;
                min-height: 100vh;
            }

            .image-side {
                display: none;
            }

            .form-side {
                padding: 1.5rem;
            }

            .form-box {
                max-width: 100%;
            }
        }

        @media (max-width: 576px) {
            .form-side {
                padding: 1rem;
            }

            .btn-group-custom {
                flex-direction: column;
                gap: 0.75rem;
            }

            .btn-secondary-custom {
                width: 100%;
            }
        }

        .spinner-border {
            width: 1rem;
            height: 1rem;
            border-width: 0.15em;
        }
    </style>
</head>
<body>

<div class="register-wrapper">
    <!-- Left: Professional Message -->
    <div class="image-side">
        <div class="image-content">
            <h1>Join Malawi's Leading<br>Tutoring Network</h1>
            <p>Connect with students across Malawi, earn competitive rates, and enjoy flexible working hours. Build your teaching career with us.</p>
            <div style="margin-top: 2rem; display: flex; flex-direction: column; gap: 1rem;">
                <div style="display: flex; align-items: center; justify-content: center; gap: 10px;">
                    <i class="fas fa-check-circle" style="font-size: 1.2rem;"></i>
                    <span>Verified tutors only</span>
                </div>
                <div style="display: flex; align-items: center; justify-content: center; gap: 10px;">
                    <i class="fas fa-shield-alt" style="font-size: 1.2rem;"></i>
                    <span>Secure & confidential</span>
                </div>
                <div style="display: flex; align-items: center; justify-content: center; gap: 10px;">
                    <i class="fas fa-headset" style="font-size: 1.2rem;"></i>
                    <span>24/7 support</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Right: Form -->
    <div class="form-side">
        <div class="form-box">
            <div class="logo">
                <span>TutorConnect</span> <span>Malawi</span>
            </div>

            <h6 class="text-center fw-semibold mb-0" style="color: var(--secondary); font-size: 0.85rem;">Tutor Registration</h6>
            <p class="text-center text-muted mb-2" style="font-size: 0.8rem;">Complete your profile to get started</p>

            <!-- Step Indicator -->
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
                    <i class="fas fa-check-circle"></i> <?= session('success') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->has('error')): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> <?= session('error') ?>
                </div>
            <?php endif; ?>

            <!-- Step 1: Personal Information -->
            <form id="step1Form" class="<?= $step != 1 ? 'd-none' : '' ?>" method="post" action="<?= base_url('register/step1') ?>">
                <?= csrf_field() ?>

                <h6 class="mb-4" style="color: var(--secondary);">Basic Information</h6>

                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" name="first_name" placeholder=" " value="<?= $form_data['first_name'] ?? '' ?>" required pattern="[A-Za-z\s]{2,50}" title="Name should only contain letters (2-50 characters)">
                            <label>First Name</label>
                            <?php if (session('errors.first_name')): ?>
                                <div class="invalid-feedback"><?= session('errors.first_name') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" name="last_name" placeholder=" " value="<?= $form_data['last_name'] ?? '' ?>" required pattern="[A-Za-z\s]{2,50}" title="Name should only contain letters (2-50 characters)">
                            <label>Last Name</label>
                            <?php if (session('errors.last_name')): ?>
                                <div class="invalid-feedback"><?= session('errors.last_name') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="input-group">
                    <input type="email" name="email" id="email" placeholder=" " value="<?= $form_data['email'] ?? '' ?>" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="Please enter a valid email address">
                    <label>Email Address</label>
                    <div class="tooltip-hint" id="email-hint">We'll never share your email</div>
                    <?php if (session('errors.email')): ?>
                        <div class="invalid-feedback"><?= session('errors.email') ?></div>
                    <?php endif; ?>
                </div>

                <div class="input-group">
                    <input type="tel" name="phone" id="phone" placeholder=" " value="<?= $form_data['phone'] ?? '' ?>" required pattern="(\+265|0)?[1-9][0-9]{7,8}" title="Please enter a valid Malawi phone number (e.g., 0991234567 or +265991234567)">
                    <label>Phone Number</label>
                    <div class="tooltip-hint" id="phone-hint">Format: 0991234567 or +265991234567</div>
                    <?php if (session('errors.phone')): ?>
                        <div class="invalid-feedback"><?= session('errors.phone') ?></div>
                    <?php endif; ?>
                </div>

                <div class="input-group">
                    <select name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="Male" <?= ($form_data['gender'] ?? '') == 'Male' ? 'selected' : '' ?>>Male</option>
                        <option value="Female" <?= ($form_data['gender'] ?? '') == 'Female' ? 'selected' : '' ?>>Female</option>
                        <option value="Other">Other</option>
                    </select>
                    <label>Gender</label>
                    <?php if (session('errors.gender')): ?>
                        <div class="invalid-feedback"><?= session('errors.gender') ?></div>
                    <?php endif; ?>
                </div>

                <div class="input-group">
                    <select name="district" required>
                        <option value="">Select District</option>
                        <option value="Balaka" <?= ($form_data['district'] ?? '') == 'Balaka' ? 'selected' : '' ?>>Balaka</option>
                        <option value="Blantyre" <?= ($form_data['district'] ?? '') == 'Blantyre' ? 'selected' : '' ?>>Blantyre</option>
                        <option value="Chikwawa" <?= ($form_data['district'] ?? '') == 'Chikwawa' ? 'selected' : '' ?>>Chikwawa</option>
                        <option value="Chiradzulu" <?= ($form_data['district'] ?? '') == 'Chiradzulu' ? 'selected' : '' ?>>Chiradzulu</option>
                        <option value="Chitipa" <?= ($form_data['district'] ?? '') == 'Chitipa' ? 'selected' : '' ?>>Chitipa</option>
                        <option value="Dedza" <?= ($form_data['district'] ?? '') == 'Dedza' ? 'selected' : '' ?>>Dedza</option>
                        <option value="Dowa" <?= ($form_data['district'] ?? '') == 'Dowa' ? 'selected' : '' ?>>Dowa</option>
                        <option value="Karonga" <?= ($form_data['district'] ?? '') == 'Karonga' ? 'selected' : '' ?>>Karonga</option>
                        <option value="Kasungu" <?= ($form_data['district'] ?? '') == 'Kasungu' ? 'selected' : '' ?>>Kasungu</option>
                        <option value="Likoma" <?= ($form_data['district'] ?? '') == 'Likoma' ? 'selected' : '' ?>>Likoma</option>
                        <option value="Lilongwe" <?= ($form_data['district'] ?? '') == 'Lilongwe' ? 'selected' : '' ?>>Lilongwe</option>
                        <option value="Machinga" <?= ($form_data['district'] ?? '') == 'Machinga' ? 'selected' : '' ?>>Machinga</option>
                        <option value="Mangochi" <?= ($form_data['district'] ?? '') == 'Mangochi' ? 'selected' : '' ?>>Mangochi</option>
                        <option value="Mchinji" <?= ($form_data['district'] ?? '') == 'Mchinji' ? 'selected' : '' ?>>Mchinji</option>
                        <option value="Mulanje" <?= ($form_data['district'] ?? '') == 'Mulanje' ? 'selected' : '' ?>>Mulanje</option>
                        <option value="Mwanza" <?= ($form_data['district'] ?? '') == 'Mwanza' ? 'selected' : '' ?>>Mwanza</option>
                        <option value="Mzimba" <?= ($form_data['district'] ?? '') == 'Mzimba' ? 'selected' : '' ?>>Mzimba</option>
                        <option value="Neno" <?= ($form_data['district'] ?? '') == 'Neno' ? 'selected' : '' ?>>Neno</option>
                        <option value="Nkhata Bay" <?= ($form_data['district'] ?? '') == 'Nkhata Bay' ? 'selected' : '' ?>>Nkhata Bay</option>
                        <option value="Nkhotakota" <?= ($form_data['district'] ?? '') == 'Nkhotakota' ? 'selected' : '' ?>>Nkhotakota</option>
                        <option value="Nsanje" <?= ($form_data['district'] ?? '') == 'Nsanje' ? 'selected' : '' ?>>Nsanje</option>
                        <option value="Ntcheu" <?= ($form_data['district'] ?? '') == 'Ntcheu' ? 'selected' : '' ?>>Ntcheu</option>
                        <option value="Ntchisi" <?= ($form_data['district'] ?? '') == 'Ntchisi' ? 'selected' : '' ?>>Ntchisi</option>
                        <option value="Phalombe" <?= ($form_data['district'] ?? '') == 'Phalombe' ? 'selected' : '' ?>>Phalombe</option>
                        <option value="Rumphi" <?= ($form_data['district'] ?? '') == 'Rumphi' ? 'selected' : '' ?>>Rumphi</option>
                        <option value="Salima" <?= ($form_data['district'] ?? '') == 'Salima' ? 'selected' : '' ?>>Salima</option>
                        <option value="Thyolo" <?= ($form_data['district'] ?? '') == 'Thyolo' ? 'selected' : '' ?>>Thyolo</option>
                        <option value="Zomba" <?= ($form_data['district'] ?? '') == 'Zomba' ? 'selected' : '' ?>>Zomba</option>
                    </select>
                    <label>District</label>
                    <?php if (session('errors.district')): ?>
                        <div class="invalid-feedback"><?= session('errors.district') ?></div>
                    <?php endif; ?>
                </div>

                <div class="input-group">
                    <input type="text" name="location" placeholder=" " value="<?= $form_data['location'] ?? '' ?>" required>
                    <label>Location/Area</label>
                    <div class="tooltip-hint">Specific area or neighborhood within your district</div>
                    <?php if (session('errors.location')): ?>
                        <div class="invalid-feedback"><?= session('errors.location') ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label style="color: var(--secondary); font-weight: 600; margin-bottom: 10px; display: block;">Currently Employed?</label>
                    <div class="radio-group">
                        <input type="radio" name="is_employed" value="1" id="employed_yes" <?= ($form_data['is_employed'] ?? '0') == '1' ? 'checked' : '' ?>>
                        <label for="employed_yes">Yes</label>
                    </div>
                    <div class="radio-group">
                        <input type="radio" name="is_employed" value="0" id="employed_no" <?= ($form_data['is_employed'] ?? '0') == '0' ? 'checked' : '' ?>>
                        <label for="employed_no">No</label>
                    </div>
                </div>

                <div id="school_name_group" style="<?= ($form_data['is_employed'] ?? '0') == '1' ? '' : 'display: none;' ?>">
                    <div class="input-group">
                        <input type="text" name="school_name" placeholder=" " value="<?= $form_data['school_name'] ?? '' ?>">
                        <label>School/Institution Name</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" id="submitBtn1">
                    <span class="spinner-border spinner-border-sm me-2 d-none"></span>
                    Next Step <i class="fas fa-arrow-right ms-2"></i>
                </button>
            </form>

            <!-- Step 2: Account Setup -->
            <form id="step2Form" class="<?= $step != 2 ? 'd-none' : '' ?>" method="post" action="<?= base_url('register/step2') ?>">
                <?= csrf_field() ?>

                <h6 class="mb-4" style="color: var(--secondary);">Account Setup</h6>

                <div class="input-group">
                    <input type="text" name="username" id="username" placeholder=" " required pattern="[a-zA-Z0-9_]{4,20}" title="Username must be 4-20 characters (letters, numbers, underscore only)">
                    <label>Username</label>
                    <div class="tooltip-hint" id="username-hint">Used for login only (for privacy)</div>
                    <?php if (session('errors.username')): ?>
                        <div class="invalid-feedback"><?= session('errors.username') ?></div>
                    <?php endif; ?>
                </div>

                <div class="input-group">
                    <input type="password" name="password" id="password" placeholder=" " required minlength="8" pattern="^(?=.*\d).{8,}$" title="Password must be at least 8 characters and contain at least one number">
                    <label>Password</label>
                    <div class="tooltip-hint" id="password-hint">At least 8 characters with one number</div>
                    <?php if (session('errors.password')): ?>
                        <div class="invalid-feedback"><?= session('errors.password') ?></div>
                    <?php endif; ?>
                </div>

                <div class="input-group">
                    <input type="password" name="confirm_password" id="confirm_password" placeholder=" " required>
                    <label>Confirm Password</label>
                    <div class="tooltip-hint" id="confirm-password-hint">Passwords must match</div>
                    <?php if (session('errors.confirm_password')): ?>
                        <div class="invalid-feedback"><?= session('errors.confirm_password') ?></div>
                    <?php endif; ?>
                </div>

                <!-- Terms and Conditions -->
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="termsCheckbox" name="accept_terms" required checked="checked">
                    <label class="form-check-label" for="termsCheckbox" style="font-size: 0.85rem; color: var(--gray); line-height: 1.4;">
                        I agree to the <a href="<?= base_url('terms-of-service') ?>" target="_blank" style="color: var(--primary); text-decoration: none; font-weight: 500;">Terms of Service</a>,
                        <a href="<?= base_url('privacy-policy') ?>" target="_blank" style="color: var(--primary); text-decoration: none; font-weight: 500;">Privacy Policy</a>,
                        <a href="<?= base_url('child-safeguarding') ?>" target="_blank" style="color: var(--primary); text-decoration: none; font-weight: 500;">Child Safeguarding Policy</a>, and
                        <a href="<?= base_url('terms-and-conditions') ?>" target="_blank" style="color: var(--primary); text-decoration: none; font-weight: 500;">Terms and Conditions</a>
                    </label>
                    <?php if (session('errors.accept_terms')): ?>
                        <div class="invalid-feedback d-block" style="font-size: 0.8rem; margin-top: 0.25rem;"><?= session('errors.accept_terms') ?></div>
                    <?php endif; ?>
                </div>

                <div class="btn-group-custom">
                    <a href="<?= base_url('register/back') ?>" class="btn-secondary-custom">
                        <i class="fas fa-arrow-left me-2"></i> Back
                    </a>
                    <button type="submit" class="btn btn-primary" id="submitBtn2" style="width: 48%;">
                        <span class="spinner-border spinner-border-sm me-2 d-none"></span>
                        Complete <i class="fas fa-check ms-2"></i>
                    </button>
                </div>
            </form>

            <p class="text-center mt-4 text-muted">
                Already have an account?
                <a href="<?= base_url('login') ?>" style="color:var(--primary);font-weight:600; text-decoration: none;">Sign in here</a>
            </p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Employment toggle
    document.querySelectorAll('input[name="is_employed"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const schoolGroup = document.getElementById('school_name_group');
            schoolGroup.style.display = this.value === '1' ? 'block' : 'none';
        });
    });

    // Clear form data on successful completion redirect
    if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_RELOAD) {
        const hasErrors = document.querySelector('.invalid-feedback');
        if (!hasErrors && <?= $step ?> === 1) {
            document.getElementById('step1Form')?.reset();
        }
    }

    // Real-time validation feedback
    const step1Form = document.getElementById('step1Form');
    if (step1Form) {
        const inputs = step1Form.querySelectorAll('input[required]');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (!this.validity.valid) {
                    this.style.borderBottomColor = 'var(--danger)';
                } else {
                    this.style.borderBottomColor = 'var(--success)';
                }
            });

            input.addEventListener('input', function() {
                if (this.validity.valid) {
                    this.style.borderBottomColor = 'var(--success)';
                }
            });
        });

        // Form submit validation
        step1Form.addEventListener('submit', function(e) {
            let isValid = true;
            let errorMessages = [];

            inputs.forEach(input => {
                if (!input.validity.valid) {
                    input.style.borderBottomColor = 'var(--danger)';
                    isValid = false;
                    errorMessages.push(input.name + ' is invalid');
                }
            });

            // Check if email is already taken
            const emailInput = document.getElementById('email');
            if (emailInput && emailInput.dataset.available === 'false') {
                e.preventDefault();
                alert('❌ Email is already registered. Please use a different email.');
                emailInput.focus();
                return false;
            }

            // Check if phone is already taken
            const phoneInput = document.getElementById('phone');
            if (phoneInput && phoneInput.dataset.available === 'false') {
                e.preventDefault();
                alert('❌ Phone number is already registered. Please use a different phone number.');
                phoneInput.focus();
                return false;
            }

            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields correctly.');
                return false;
            }

            // Show spinner
            const submitBtn = document.getElementById('submitBtn1');
            const spinner = submitBtn.querySelector('.spinner-border');
            if (spinner) {
                spinner.classList.remove('d-none');
                submitBtn.disabled = true;
            }
        });
    }

    // Step 2 form submission
    const step2Form = document.getElementById('step2Form');
    if (step2Form) {
        step2Form.addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn2');
            const spinner = submitBtn.querySelector('.spinner-border');
            if (spinner) {
                spinner.classList.remove('d-none');
                submitBtn.disabled = true;
            }
        });
    }

    // Check if email exists in database
    const emailInput = document.getElementById('email');
    if (emailInput) {
        let emailTimeout;
        emailInput.addEventListener('input', function() {
            clearTimeout(emailTimeout);
            const email = this.value;
            const hint = document.getElementById('email-hint');

            if (email.length > 3 && this.validity.valid) {
                emailTimeout = setTimeout(() => {
                    fetch('<?= base_url('register/check-email') ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ email: email })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            emailInput.style.borderBottomColor = 'var(--danger)';
                            emailInput.dataset.available = 'false';
                            hint.style.color = 'var(--danger)';
                            hint.textContent = '✗ Email already registered';
                        } else {
                            emailInput.style.borderBottomColor = 'var(--success)';
                            emailInput.dataset.available = 'true';
                            hint.style.color = 'var(--success)';
                            hint.textContent = '✓ Email available';
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }, 500);
            } else {
                emailInput.dataset.available = '';
                hint.style.color = 'var(--gray)';
                hint.textContent = "We'll never share your email";
            }
        });
    }

    // Check if phone exists in database
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        let phoneTimeout;
        phoneInput.addEventListener('input', function() {
            clearTimeout(phoneTimeout);
            const phone = this.value;
            const hint = document.getElementById('phone-hint');

            if (phone.length > 8 && this.validity.valid) {
                phoneTimeout = setTimeout(() => {
                    fetch('<?= base_url('register/check-phone') ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ phone: phone })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            phoneInput.style.borderBottomColor = 'var(--danger)';
                            phoneInput.dataset.available = 'false';
                            hint.style.color = 'var(--danger)';
                            hint.textContent = '✗ Phone number already registered';
                        } else {
                            phoneInput.style.borderBottomColor = 'var(--success)';
                            phoneInput.dataset.available = 'true';
                            hint.style.color = 'var(--success)';
                            hint.textContent = '✓ Phone number available';
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }, 500);
            } else {
                phoneInput.dataset.available = '';
            }
        });
    }

    // Check if username exists in database (Step 2)
    const usernameInput = document.getElementById('username');
    if (usernameInput) {
        let usernameTimeout;
        usernameInput.addEventListener('input', function() {
            clearTimeout(usernameTimeout);
            const username = this.value;
            const hint = document.getElementById('username-hint');

            if (username.length > 3 && this.validity.valid) {
                usernameTimeout = setTimeout(() => {
                    fetch('<?= base_url('register/check-username') ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ username: username })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            usernameInput.style.borderBottomColor = 'var(--danger)';
                            hint.style.color = 'var(--danger)';
                            hint.textContent = '✗ Username already taken';
                        } else {
                            usernameInput.style.borderBottomColor = 'var(--success)';
                            hint.style.color = 'var(--success)';
                            hint.textContent = '✓ Username available';
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }, 500);
            }
        });
    }

    // Password validation
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const passwordHint = document.getElementById('password-hint');
    const confirmPasswordHint = document.getElementById('confirm-password-hint');

    if (passwordInput && passwordHint) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const hasMinLength = password.length >= 8;
            const hasNumber = /\d/.test(password);
            const isValid = hasMinLength && hasNumber;

            if (password.length > 0) {
                if (isValid) {
                    this.style.borderBottomColor = 'var(--success)';
                    passwordHint.style.color = 'var(--success)';
                    passwordHint.textContent = '✓ Password is strong';
                } else {
                    this.style.borderBottomColor = 'var(--danger)';
                    passwordHint.style.color = 'var(--danger)';
                    if (!hasMinLength) {
                        passwordHint.textContent = '✗ At least 8 characters required';
                    } else if (!hasNumber) {
                        passwordHint.textContent = '✗ Must contain at least one number';
                    }
                }
            } else {
                this.style.borderBottomColor = '';
                passwordHint.style.color = 'var(--gray)';
                passwordHint.textContent = 'At least 8 characters with one number';
            }

            // Also validate confirm password if it has value
            if (confirmPasswordInput.value.length > 0) {
                validatePasswordMatch();
            }
        });
    }

    if (confirmPasswordInput && confirmPasswordHint) {
        confirmPasswordInput.addEventListener('input', validatePasswordMatch);
    }

    function validatePasswordMatch() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;

        if (confirmPassword.length > 0) {
            if (password === confirmPassword) {
                confirmPasswordInput.style.borderBottomColor = 'var(--success)';
                confirmPasswordHint.style.color = 'var(--success)';
                confirmPasswordHint.textContent = '✓ Passwords match';
            } else {
                confirmPasswordInput.style.borderBottomColor = 'var(--danger)';
                confirmPasswordHint.style.color = 'var(--danger)';
                confirmPasswordHint.textContent = '✗ Passwords do not match';
            }
        } else {
            confirmPasswordInput.style.borderBottomColor = '';
            confirmPasswordHint.style.color = 'var(--gray)';
            confirmPasswordHint.textContent = 'Passwords must match';
        }
    }
</script>
</body>
</html>
