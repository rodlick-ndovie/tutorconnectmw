<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - TutorConnect Malawi</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root { --primary: #E74C3C; --secondary: #2C3E50; --accent: #34495E; --gray: #666; }
        * { box-sizing: border-box; }
        body, html { height: 100%; margin: 0; font-family: 'Inter', sans-serif; background: white; }

        .verify-wrapper {
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

        /* Right: Compact OTP Form */
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
            font-size: 1.8rem;
            font-weight: 800;
            text-align: center;
            margin-bottom: 1.2rem;
        }
        .logo span:first-child { color: var(--primary); }
        .logo span:last-child { color: #000; }

        .otp-container {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 1.5rem 0;
        }
        .otp-input {
            width: 48px;
            height: 54px;
            text-align: center;
            font-size: 1.4rem;
            font-weight: 700;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            background: white;
            color: #1f2937;
            transition: all 0.25s;
        }
        .otp-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(231,76,60,0.15);
        }

        .btn-primary {
            background: var(--primary);
            border: none;
            border-radius: 10px;
            padding: 0.68rem;
            font-weight: 600;
            font-size: 0.92rem;
            width: 100%;
        }

        .text-muted { font-size: 0.84rem; color: #777 !important; }
        .text-primary { color: var(--primary) !important; font-weight: 600; }
        .btn-link { color: var(--primary); text-decoration: none; font-weight: 600; }

        @media (max-width: 991px) {
            .verify-wrapper { grid-template-columns: 1fr; }
            .image-side { display: none; }
            .form-side { padding: 2rem 1rem; }
        }
    </style>
</head>
<body>

<div class="verify-wrapper">

    <!-- Left: Professional Message -->
    <div class="image-side d-none d-lg-flex">
        <div>
            <h1>Verify Your Email</h1>
            <p>We sent a 6-digit code to your inbox. Enter it below to continue.</p>
        </div>
    </div>

    <!-- Right: OTP Form -->
    <div class="form-side">
        <div class="form-box">

            <div class="logo"><span>TutorConnect</span> <span>Malawi</span></div>
            <h6 class="text-center fw-semibold mb-1">Email Verification</h6>
            <p class="text-center text-muted mb-3">
                Code sent to <strong><?php echo htmlspecialchars($email ?? 'your email'); ?></strong>
            </p>

            <div id="alertMessage"></div>

            <form id="verifyForm" novalidate>
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" />
                <input type="hidden" name="otp" id="otp" value="">

                <div class="otp-container">
                    <input type="text" maxlength="1" class="otp-input" id="d1">
                    <input type="text" maxlength="1" class="otp-input" id="d2">
                    <input type="text" maxlength="1" class="otp-input" id="d3">
                    <input type="text" maxlength="1" class="otp-input" id="d4">
                    <input type="text" maxlength="1" class="otp-input" id="d5">
                    <input type="text" maxlength="1" class="otp-input" id="d6">
                </div>

                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <span id="submitBtnText">Verify Email</span>
                    <span id="submitSpinner" class="d-none"><i class="fas fa-spinner fa-spin me-2"></i>Verifying...</span>
                </button>

                <div class="text-center mt-3">
                    <small class="text-muted">
                        Didn’t receive it?
                        <a href="javascript:resendOTP()" class="btn-link">Resend Code</a>
                    </small>
                </div>

                <div class="text-center mt-3">
                    <small class="text-muted">
                        Wrong email? <a href="<?= base_url('register') ?>" class="text-primary">Register again</a>
                    </small>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const inputs = document.querySelectorAll('.otp-input');
    const otpField = document.getElementById('otp');
    const form = document.getElementById('verifyForm');
    const btn = document.getElementById('submitBtn');
    const btnText = document.getElementById('submitBtnText');
    const spinner = document.getElementById('submitSpinner');
    const alertDiv = document.getElementById('alertMessage');

    inputs.forEach((input, i) => {
        input.addEventListener('input', () => {
            if (input.value.length === 1 && i < 5) inputs[i + 1].focus();
            updateOTP();
            if (otpField.value.length === 6) setTimeout(() => form.dispatchEvent(new Event('submit')), 300);
        });
        input.addEventListener('keydown', e => {
            if (e.key === 'Backspace' && !input.value && i > 0) inputs[i - 1].focus();
        });
    });

    function updateOTP() {
        otpField.value = Array.from(inputs).map(i => i.value).join('');
    }

    form.onsubmit = function(e) {
        e.preventDefault();
        if (otpField.value.length !== 6) return;

        btn.disabled = true;
        btnText.classList.add('d-none');
        spinner.classList.remove('d-none');

        fetch("<?= base_url('verify-email') ?>", {
            method: 'POST',
            body: new FormData(this),
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(d => {
            if (d.success) {
                alertDiv.innerHTML = `<div class="alert alert-success small p-2 mt-3 rounded">Email verified! Redirecting...</div>`;
                setTimeout(() => location.href = d.redirect || "<?= base_url('login') ?>", 2000);
            } else {
                alertDiv.innerHTML = `<div class="alert alert-danger small p-2 mt-3 rounded">${d.message || 'Invalid code'}</div>`;
            }
        })
        .finally(() => {
            btn.disabled = false;
            btnText.classList.remove('d-none');
            spinner.classList.add('d-none');
        });
    };

    function resendOTP() {
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Resending...';
        fetch("<?= base_url('resend-otp') ?>", {
            method: 'POST',
            body: new FormData(form),
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(d => {
            alertDiv.innerHTML = `<div class="alert alert-success small p-2 mt-3 rounded">${d.message || 'Code resent!'}</div>`;
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" style="display:none;"></span>Verify Email';
        });
    }

    // Auto-focus first digit
    document.getElementById('d1').focus();
</script>
</body>
</html>
