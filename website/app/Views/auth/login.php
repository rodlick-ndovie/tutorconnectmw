<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TutorConnect Malawi</title>

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

        /* Right: Compact Login Form */
        .form-side {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            background: white;
        }
        .form-box {
            width: 100%;
            max-width: 330px;
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

        .form-check {
            margin: 0.8rem 0 1.2rem;
        }
        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary {
            background: var(--primary);
            border: none;
            border-radius: 8px;
            padding: 0.6rem;
            font-weight: 600;
            font-size: 0.875rem;
            width: 100%;
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
            <h1>Welcome Back</h1>
            <p>Connect with students across Malawi and grow your tutoring career</p>
        </div>
    </div>

    <!-- Right: Login Form -->
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
            <h6 class="text-center fw-semibold mb-1">Sign In</h6>
            <p class="text-center text-muted mb-3">Access your tutor dashboard</p>

            <div id="alertContainer"></div>

            <form id="loginForm" method="post" novalidate>
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />

                <div class="input-group">
                    <input type="text" name="login" placeholder=" " required>
                    <label>Username or Email</label>
                </div>

                <div class="input-group">
                    <input type="password" name="password" placeholder=" " required>
                    <label>Password</label>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label text-muted" for="remember">Remember me</label>
                    </div>
                    <a href="<?= base_url('forgot-password') ?>" class="text-primary text-decoration-none small">Forgot password?</a>
                </div>

                <button type="submit" class="btn btn-primary" id="loginBtn">
                    <span id="loginBtnText">Sign In</span>
                    <span id="loginSpinner" class="d-none"><i class="fas fa-spinner fa-spin me-2"></i>Signing in...</span>
                </button>

                <p class="text-center mt-3 text-muted">
                    New tutor? <a href="<?= base_url('register') ?>" class="text-primary">Register here</a>
                </p>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
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

    document.getElementById('loginForm').onsubmit = function(e) {
        e.preventDefault();

        const btn = document.getElementById('loginBtn');
        const btnText = document.getElementById('loginBtnText');
        const spinner = document.getElementById('loginSpinner');

        btn.disabled = true;
        btnText.classList.add('d-none');
        spinner.classList.remove('d-none');

        fetch("<?= site_url('login') ?>", {
            method: 'POST',
            body: new FormData(this),
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                location.href = data.redirect || "<?= site_url('dashboard') ?>";
            } else {
                document.getElementById('alertContainer').innerHTML =
                    `<div class="alert alert-danger small p-2 mt-3 rounded">${data.message || 'Invalid credentials'}</div>`;
            }
        })
        .catch(() => {
            document.getElementById('alertContainer').innerHTML =
                `<div class="alert alert-danger small p-2 mt-3 rounded">Connection error. Try again.</div>`;
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
