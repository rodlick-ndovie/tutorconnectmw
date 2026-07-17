<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo esc($title); ?> - TutorConnect Malawi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        body {
            background: linear-gradient(135deg, #E74C3C 0%, #C0392B 50%, #922B21 100%);
            min-height: 100vh;
            padding: 40px 20px;
            position: relative;
        }
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="100" height="100" patternUnits="userSpaceOnUse"><path d="M 100 0 L 0 0 0 100" fill="none" stroke="rgba(255,255,255,0.03)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.5;
        }
        .resubmit-container {
            max-width: 900px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }
        .logo-section {
            text-align: center;
            margin-bottom: 30px;
            animation: fadeInDown 0.8s ease-out;
        }
        .logo-section h1 {
            color: white;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 8px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        .logo-section p {
            color: rgba(255,255,255,0.9);
            font-size: 16px;
        }
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            animation: fadeInUp 0.8s ease-out;
            background: white;
        }
        .card-header {
            background: linear-gradient(135deg, #2C3E50 0%, #34495E 100%);
            color: white;
            padding: 35px 40px;
            border: none;
        }
        .card-header h2 {
            font-size: 28px;
            font-weight: 600;
            margin: 0 0 10px 0;
        }
        .card-header p {
            margin: 0;
            opacity: 0.9;
            font-size: 16px;
        }
        .card-body {
            padding: 40px;
        }
        .timer-box {
            background: linear-gradient(135deg, #FFF3E0 0%, #FFE0B2 100%);
            border-left: 5px solid #FF9800;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(255, 152, 0, 0.15);
        }
        .timer-box strong {
            color: #E65100;
            font-weight: 600;
        }
        #countdown {
            font-weight: 700;
            color: #E65100;
            font-size: 18px;
        }
        .info-alert {
            background: linear-gradient(135deg, #E3F2FD 0%, #BBDEFB 100%);
            border-left: 5px solid #2196F3;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
            color: #0D47A1;
            box-shadow: 0 4px 15px rgba(33, 150, 243, 0.15);
        }
        .info-alert i {
            font-size: 20px;
            margin-right: 10px;
        }
        .doc-item {
            background: linear-gradient(135deg, #FAFAFA 0%, #F5F5F5 100%);
            border-left: 5px solid #E55C0D;
            padding: 25px;
            margin-bottom: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }
        .doc-item:hover {
            box-shadow: 0 8px 25px rgba(231, 76, 60, 0.15);
            transform: translateY(-2px);
        }
        .doc-item h5 {
            color: #2C3E50;
            font-weight: 600;
            margin-bottom: 15px;
            font-size: 18px;
        }
        .doc-item h5 i {
            color: #E55C0D;
            margin-right: 10px;
        }
        .reason-box {
            background: #FFF3E0;
            border: 1px solid #FFB74D;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }
        .reason-box strong {
            color: #E65100;
        }
        .form-label {
            font-weight: 600;
            color: #2C3E50;
            margin-bottom: 8px;
        }
        .form-control {
            border: 2px solid #E0E0E0;
            border-radius: 8px;
            padding: 12px;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #E55C0D;
            box-shadow: 0 0 0 0.2rem rgba(229, 92, 13, 0.15);
        }
        .btn-submit {
            background: linear-gradient(135deg, #E55C0D 0%, #C94609 100%);
            border: none;
            padding: 16px 50px;
            font-weight: 600;
            font-size: 18px;
            border-radius: 12px;
            color: white;
            box-shadow: 0 8px 20px rgba(231, 76, 60, 0.3);
            transition: all 0.3s ease;
        }
        .btn-submit:hover {
            background: linear-gradient(135deg, #C94609 0%, #A93706 100%);
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(231, 76, 60, 0.4);
        }
        .btn-submit:active {
            transform: translateY(0);
        }
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="container resubmit-container">
        <div class="logo-section">
            <h1><i class="fas fa-graduation-cap"></i> TutorConnect Malawi</h1>
            <p>Document Verification Portal</p>
        </div>

        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-file-upload me-2"></i> Document Resubmission</h2>
                <p>Hello, <?php echo esc($user['first_name'] . ' ' . $user['last_name']); ?></p>
            </div>
            <div class="card-body">
                <div class="timer-box">
                    <i class="fas fa-clock"></i>
                    <strong>Link Expires:</strong> <?php echo date('F j, Y g:i A', strtotime($expiresAt)); ?>
                    <span id="countdown" class="float-end"></span>
                </div>

                <div class="info-alert">
                    <i class="fas fa-info-circle"></i>
                    <strong>Action Required:</strong> Please resubmit the following documents as requested. Ensure all files are clear, readable, and meet the specified requirements.
                </div>

                <form id="resubmitForm" enctype="multipart/form-data">
                    <input type="hidden" name="token" value="<?php echo esc($token); ?>">

                    <?php foreach ($requiredDocs as $doc): ?>
                    <div class="doc-item">
                        <h5><i class="fas fa-file-alt"></i> <?php echo esc($doc['name']); ?></h5>
                        <?php if ($doc['message']): ?>
                        <div class="reason-box">
                            <strong>Admin Comments:</strong> <?php echo esc($doc['message']); ?>
                        </div>
                        <?php endif; ?>

                        <?php if ($doc['current_file']): ?>
                        <p class="text-muted small mb-3">
                            <i class="fas fa-link"></i> Current file:
                            <a href="<?php echo base_url($doc['current_file']); ?>" target="_blank" style="color: #E74C3C; font-weight: 600;">View Current</a>
                        </p>
                        <?php endif; ?>

                        <div class="mb-0">
                            <label class="form-label">
                                Upload New File
                                <?php if (strpos($doc['type'], 'video') !== false): ?>
                                    <small class="text-muted">(MP4, WebM, OGV - Max 50MB)</small>
                                <?php else: ?>
                                    <small class="text-muted">(PDF, JPG, PNG - Max 10MB)</small>
                                <?php endif; ?>
                            </label>
                            <input type="file" class="form-control" name="<?php echo esc($doc['type']); ?>" required
                                <?php if (strpos($doc['type'], 'video') !== false): ?>
                                    accept="video/mp4,video/webm,video/ogg,video/avi,video/quicktime"
                                <?php elseif (strpos($doc['type'], 'photo') !== false || strpos($doc['type'], 'picture') !== false): ?>
                                    accept="image/jpeg,image/jpg,image/png,image/gif"
                                <?php else: ?>
                                    accept=".pdf,.jpg,.jpeg,.png"
                                <?php endif; ?>
                            >
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-submit btn-lg">
                            <i class="fas fa-paper-plane"></i> Submit Documents
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Countdown timer
        const expiresAt = new Date('<?php echo $expiresAt; ?>').getTime();
        const countdownEl = document.getElementById('countdown');

        function updateCountdown() {
            const now = new Date().getTime();
            const distance = expiresAt - now;

            if (distance < 0) {
                countdownEl.innerHTML = '<span class="text-danger">EXPIRED</span>';
                document.getElementById('resubmitForm').innerHTML = '<div class="alert alert-danger">This link has expired. Please contact the admin.</div>';
                return;
            }

            const hours = Math.floor(distance / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            countdownEl.innerHTML = `${hours}h ${minutes}m ${seconds}s remaining`;
        }

        updateCountdown();
        setInterval(updateCountdown, 1000);

        // Form submission
        document.getElementById('resubmitForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';

            try {
                const response = await fetch('<?php echo base_url('resubmit-documents/submit'); ?>', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    document.querySelector('.card-body').innerHTML = `
                        <div class="alert alert-success text-center py-5">
                            <i class="fas fa-check-circle fa-3x mb-3"></i>
                            <h4>${result.message}</h4>
                            <p class="mb-0">You will be notified once your documents are reviewed.</p>
                        </div>
                    `;
                } else {
                    alert('Error: ' + (result.message || 'Failed to submit documents'));
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;

                    if (result.errors) {
                        result.errors.forEach(error => {
                            console.error(error);
                        });
                    }
                }
            } catch (error) {
                alert('Failed to submit documents. Please try again.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    </script>
</body>
</html>
