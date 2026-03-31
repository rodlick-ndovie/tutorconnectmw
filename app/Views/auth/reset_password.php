<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Reset Password - TutorConnect Malawi'; ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root { --primary: #E55C0D; --secondary: #2C3E50; --accent: #34495E; --gray: #666; }
        * { box-sizing: border-box; }
        body, html { height: 100%; margin: 0; font-family: 'Inter', sans-serif; background: white; }

        .login-wrapper {
            height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
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
        }
        .image-side h1 {
            font-size: 2.1rem;
            font-weight: 800;
            margin-bottom: 1rem;
            line-height: 1.2;
        }
        .image-side p {
            font-size: 1.05rem;
            max-width: 340px;
            opacity: 0.96;
            font-weight: 500;
        }

        /* Right: Reset Password Form */
        .form-side {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            background: white;
        }
        .form-box {
            width: 100%;
            max-width: 380px;
        }

        .logo {
            text-align: center;
            margin-bottom: 1.2rem;
        }
        .logo img {
            max-width: 120px;
            height: auto;
        }
        .logo i {
            font-size: 2.5rem;
            color: var(--primary);
        }

        .input-group {
            position: relative;
            margin-bottom: 1rem;
        }
        .input-group input {
            width: 100%;
            padding: 0.8rem 0 0.35rem;
            font-size: 0.92rem;
            border: none;
            border-bottom: 1.8px solid #e2e8f0;
            background: transparent;
            outline: none;
        }
        .input-group label {
            position: absolute;
            top: 0.8rem;
            left: 0;
            font-size: 0.92rem;
            color: var(--gray);
            pointer-events: none;
            transition: all 0.25s;
            font-weight: 500;
        }
        .input-group input:focus ~ label,
        .input-group input:not(:placeholder-shown) ~ label {
            top: 0.15rem;
            font-size: 0.7rem;
            color: var(--primary);
            font-weight: 600;
        }
        .input-group input:focus { border-bottom-color: var(--primary); }

        .password-strength {
            margin-top: 0.25rem;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .password-strength.weak { color: #dc3545; }
        .password-strength.medium { color: #ffc107; }
        .password-strength.strong { color: #28a745; }

        .input-group-text {
            position: absolute;
            right: 0;
            top: 0.5rem;
            background: transparent;
            border: none;
            color: var(--gray);
            cursor: pointer;
            z-index: 5;
        }

        .btn-primary {
            background: var(--primary);
            border: none;
            border-radius: 8px;
            padding: 0.6rem;
            font-weight: 600;
            font-size: 0.875rem;
            width: 100%;
            margin-top: 1rem;
        }

        .text-muted { font-size: 0.84rem; color: #777 !important; }
        .text-primary { color: var(--primary) !important; font-weight: 600; }

        @media (max-width: 991px) {
            .login-wrapper { grid-template-columns: 1fr; }
            .image-side { display: none; }
            .form-side { padding: 2rem 1rem; }
        }
    </style>
</head>
<body>

<div class="login-wrapper">

    <!-- Left: Professional Message -->
    <div class="image-side d-none d-lg-flex">
        <div>
            <h1>Reset Your Password</h1>
            <p>Choose a strong, secure password to protect your TutorConnect Malawi account</p>
        </div>
    </div>

    <!-- Right: Reset Password Form -->
    <div class="form-side">
        <div class="form-box">

            <div class="logo">
                <?php $siteLogo = site_setting('site_logo', ''); ?>
                <?php if (!empty($siteLogo)): ?>
                    <img src="<?= esc(base_url('uploads/' . $siteLogo)) ?>" alt="<?= esc(site_setting('site_name', 'TutorConnect Malawi')) ?>" />
                <?php else: ?>
                    <i class="fas fa-graduation-cap"></i>
                <?php endif; ?>
            </div>
            <h6 class="text-center fw-semibold mb-1">Reset Password</h6>
            <p class="text-center text-muted mb-3">Enter your new secure password</p>

            <div id="alertContainer"></div>

            <form id="resetPasswordForm" method="post" novalidate>
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <input type="hidden" name="token" value="<?= esc($token) ?>" />

                <div class="input-group">
                    <input type="password" name="password" id="password" placeholder=" " required>
                    <label>New Password</label>
                    <span class="input-group-text" onclick="togglePassword('password')">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
                <div id="passwordStrength" class="password-strength"></div>

                <div class="input-group">
                    <input type="password" name="confirm_password" id="confirm_password" placeholder=" " required>
                    <label>Confirm Password</label>
                    <span class="input-group-text" onclick="togglePassword('confirm_password')">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
                <div id="passwordMatch" class="password-strength"></div>

                <div class="text-muted small mt-2">
                    Password must be at least 8 characters with at least one number
                </div>

                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <span id="submitBtnText"><i class="fas fa-save me-2"></i>Reset Password</span>
                    <span id="submitSpinner" class="d-none"><i class="fas fa-spinner fa-spin me-2"></i>Resetting...</span>
                </button>

                <p class="text-center mt-3 text-muted">
                    Remember your password? <a href="<?= base_url('login') ?>" class="text-primary">Sign in</a>
                </p>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = input.parentElement.querySelector('.input-group-text i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    function checkPasswordStrength(password) {
        let strength = 0;
        const strengthIndicator = document.getElementById('passwordStrength');

        if (password.length >= 8) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;

        strengthIndicator.className = 'password-strength';

        if (password.length === 0) {
            strengthIndicator.textContent = '';
            return;
        }

        if (strength <= 2) {
            strengthIndicator.textContent = 'Weak password';
            strengthIndicator.classList.add('weak');
        } else if (strength <= 3) {
            strengthIndicator.textContent = 'Medium strength';
            strengthIndicator.classList.add('medium');
        } else {
            strengthIndicator.textContent = 'Strong password';
            strengthIndicator.classList.add('strong');
        }
    }

    function checkPasswordMatch() {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        const matchIndicator = document.getElementById('passwordMatch');

        if (confirmPassword.length === 0) {
            matchIndicator.textContent = '';
            return;
        }

        if (password === confirmPassword) {
            matchIndicator.textContent = 'Passwords match';
            matchIndicator.style.color = '#28a745';
        } else {
            matchIndicator.textContent = 'Passwords do not match';
            matchIndicator.style.color = '#dc3545';
        }
    }

    // Event listeners
    document.getElementById('password').addEventListener('input', function() {
        checkPasswordStrength(this.value);
        checkPasswordMatch();
    });

    document.getElementById('confirm_password').addEventListener('input', function() {
        checkPasswordMatch();
    });

    // Show success message if present
    <?php if (session('success')): ?>
        document.getElementById('alertContainer').innerHTML =
            '<div class="alert alert-success small p-2 mb-3 rounded"><i class="fas fa-check-circle me-2"></i><?= session('success') ?></div>';
    <?php endif; ?>

    // Show error message if present
    <?php if (session('error')): ?>
        document.getElementById('alertContainer').innerHTML =
            '<div class="alert alert-danger small p-2 mb-3 rounded"><i class="fas fa-exclamation-circle me-2"></i><?= session('error') ?></div>';
    <?php endif; ?>

    document.getElementById('resetPasswordForm').onsubmit = function(e) {
        e.preventDefault();

        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;

        if (password !== confirmPassword) {
            document.getElementById('alertContainer').innerHTML =
                '<div class="alert alert-danger small p-2 mb-3 rounded"><i class="fas fa-exclamation-circle me-2"></i>Passwords do not match</div>';
            return;
        }

        if (password.length < 8) {
            document.getElementById('alertContainer').innerHTML =
                '<div class="alert alert-danger small p-2 mb-3 rounded"><i class="fas fa-exclamation-circle me-2"></i>Password must be at least 8 characters long</div>';
            return;
        }

        if (!/\d/.test(password)) {
            document.getElementById('alertContainer').innerHTML =
                '<div class="alert alert-danger small p-2 mb-3 rounded"><i class="fas fa-exclamation-circle me-2"></i>Password must contain at least one number</div>';
            return;
        }

        const btn = document.getElementById('submitBtn');
        const btnText = document.getElementById('submitBtnText');
        const spinner = document.getElementById('submitSpinner');

        btn.disabled = true;
        btnText.classList.add('d-none');
        spinner.classList.remove('d-none');

        fetch("<?= site_url('reset-password') ?>", {
            method: 'POST',
            body: new FormData(this),
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                location.href = "<?= site_url('login') ?>";
            } else {
                document.getElementById('alertContainer').innerHTML =
                    `<div class="alert alert-danger small p-2 mb-3 rounded"><i class="fas fa-exclamation-circle me-2"></i>${data.message || 'Reset failed'}</div>`;
            }
        })
        .catch(() => {
            document.getElementById('alertContainer').innerHTML =
                `<div class="alert alert-danger small p-2 mb-3 rounded"><i class="fas fa-exclamation-circle me-2"></i>Connection error. Try again.</div>`;
        })
        .finally(() => {
            btn.disabled = false;
            btnText.classList.remove('d-none');
            spinner.classList.add('d-none');
        });
    };
</script>
</body>
</html>
