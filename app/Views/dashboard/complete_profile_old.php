<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= esc($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #E55C0D;
            --primary-light: #F07D3D;
            --primary-dark: #C94609;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --text-light: #94a3b8;
            --bg-primary: #f8fafc;
            --bg-secondary: #ffffff;
            --border-color: #e2e8f0;
            --success: #27ae60;
            --danger: #dc2626;
            --warning: #f59e0b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            overflow-x: hidden;
        }

        .app-container {
            max-width: 480px;
            margin: 0 auto;
            background: var(--bg-secondary);
            min-height: 100vh;
            position: relative;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
        }

        .status-bar {
            height: 44px;
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border-color);
        }

        .navbar {
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar .px-4 {
            width: 100%;
        }

        .navbar .d-flex.gap-2 {
            margin-left: auto !important;
            flex-shrink: 0;
        }

        .navbar button {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            transition: background 0.3s;
            flex-shrink: 0;
        }

        .navbar button:hover {
            background: var(--bg-primary);
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 16px;
        }

        .screen {
            padding: 0 0 80px 0;
            background: var(--bg-primary);
            min-height: calc(100vh - 130px);
        }

        .profile-card {
            background: var(--bg-secondary);
            padding: 24px;
            margin: 0;
        }

        .header-section {
            text-align: center;
            margin-bottom: 24px;
        }

        .header-section h1 {
            color: var(--text-primary);
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .header-section p {
            color: var(--text-secondary);
            font-size: 0.95rem;
        }

        .gift-banner {
            background: linear-gradient(135deg, var(--success), #229954);
            color: white;
            padding: 16px;
            border-radius: 12px;
            text-align: center;
            margin-bottom: 24px;
        }

        .gift-banner h3 {
            margin: 0 0 4px 0;
            font-size: 1.2rem;
        }

        .gift-banner p {
            margin: 0;
            font-size: 0.9rem;
            opacity: 0.95;
        }

        .document-item {
            background: var(--bg-primary);
            border: 2px dashed var(--border-color);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 16px;
            transition: all 0.3s;
        }

        .document-item.uploaded {
            border-color: var(--success);
            background: #e8f8f0;
            border-style: solid;
        }

        .document-item:hover {
            border-color: var(--primary-color);
        }

        .document-icon {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 8px;
        }

        .document-item.uploaded .document-icon {
            color: var(--success);
        }

        .upload-label {
            font-weight: 600;
            font-size: 1rem;
            color: var(--text-primary);
            margin-bottom: 4px;
        }

        .file-input-wrapper {
            position: relative;
            display: inline-block;
        }

        .file-input-wrapper input[type="file"] {
            opacity: 0;
            position: absolute;
            width: 100%;
            height: 100%;
            cursor: pointer;
            z-index: 2;
        }

        .custom-file-label {
            display: inline-block;
            padding: 8px 16px;
            background: var(--primary-color);
            color: white;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s;
        }

        .custom-file-label:hover {
            background: var(--primary-dark);
        }

        .file-selected {
            color: var(--success);
            font-size: 0.85rem;
            margin-top: 6px;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-badge.complete {
            background: #d4edda;
            color: #155724;
        }

        .submit-btn {
            background: var(--primary-color);
            border: none;
            padding: 14px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 12px;
            margin-top: 24px;
            width: 100%;
            color: white;
        }

        .submit-btn:hover {
            background: var(--primary-dark);
        }

        .skip-link {
            text-align: center;
            margin-top: 16px;
            padding-bottom: 20px;
        }

        .skip-link a {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.9rem;
        }

        .skip-link a:hover {
            color: var(--primary-color);
        }

        .progress-bar {
            background: var(--success);
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 480px;
            background: var(--bg-secondary);
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: space-around;
            padding: 8px 0 20px;
            z-index: 1000;
        }

        @media (min-width: 768px) {
            .bottom-nav {
                max-width: 100%;
                left: 0;
                transform: none;
            }
        }

        .nav-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: var(--text-light);
            transition: all 0.3s ease;
            padding: 4px;
            border-radius: 12px;
        }

        .nav-item:hover {
            background: rgba(0, 0, 0, 0.05);
        }

        .nav-item.active {
            color: var(--primary-color);
        }

        .nav-icon {
            font-size: 22px;
            margin-bottom: 4px;
        }

        .nav-label {
            font-size: 10px;
            color: var(--text-light);
            font-weight: 500;
            text-align: center;
        }

        .nav-item.active .nav-label {
            color: var(--primary-color);
        }

        @media (min-width: 768px) {
            .status-bar {
                display: none;
            }
            .app-container {
                max-width: 100%;
                box-shadow: none;
            }
            .bottom-nav {
                max-width: 100%;
                border-top: 1px solid var(--border-color);
            }
            .profile-card {
                max-width: 900px;
                margin: 0 auto;
                padding: 40px;
            }
            .screen {
                padding: 24px 24px 80px 24px;
            }
            .header-section h1 {
                font-size: 1.75rem;
            }
            .document-item {
                padding: 20px;
            }
        }

        @media (min-width: 1024px) {
            .profile-card {
                max-width: 1100px;
            }
        }
    </style>
</head>
<body>

<div class="app-container">
    <!-- Mobile Status Bar Simulation -->
    <div class="status-bar"></div>

    <!-- Header -->
    <header class="navbar">
        <div class="px-4 py-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <div class="avatar me-3">
                    <?php echo strtoupper(substr($user['username'] ?? 'T', 0, 1)); ?>
                </div>
                <div>
                    <div class="fw-semibold"><?php echo $user['first_name'] . ' ' . $user['last_name'] ?? $user['username']; ?></div>
                    <small class="text-muted">Profile Setup</small>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button class="btn p-0 border-0 bg-transparent">
                    <i class="fas fa-bell text-muted" style="font-size: 20px;"></i>
                </button>
                <button class="btn p-0 border-0 bg-transparent" onclick="window.location.href='<?php echo base_url('logout'); ?>'">
                    <i class="fas fa-sign-out-alt text-muted" style="font-size: 20px;"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="screen">
        <div class="profile-card">

        <!-- Header -->
        <div class="header-section">
            <h1>🎯 Complete Your Tutor Profile</h1>
            <p>Upload required documents to activate your account and start connecting with students</p>
        </div>

        <!-- Gift Banner -->
        <div class="gift-banner">
            <h3>🎁 Welcome Gift Active!</h3>
            <p>You have <strong>1 Month FREE Premium Access</strong> • Upload documents to unlock all features</p>
        </div>

        <!-- Progress Bar -->
        <?php
            $totalRequired = 4; // 3 documents + 1 profile picture
            $completed = 0;
            foreach ($required_documents as $doc) {
                if ($doc['uploaded']) $completed++;
            }
            if ($has_profile_picture) $completed++;
            $progress = ($completed / $totalRequired) * 100;
        ?>
        <div class="mb-4">
            <div class="d-flex justify-content-between mb-2">
                <span class="fw-bold">Profile Completion</span>
                <span class="text-muted"><?= $completed ?> of <?= $totalRequired ?> items completed</span>
            </div>
            <div class="progress" style="height: 10px;">
                <div class="progress-bar" role="progressbar" style="width: <?= $progress ?>%" aria-valuenow="<?= $progress ?>" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>

        <!-- Alert Container -->
        <div id="alertContainer"></div>

        <!-- Upload Form -->
        <form id="uploadForm" enctype="multipart/form-data">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />

            <!-- Profile Picture -->
            <div class="document-item <?= $has_profile_picture ? 'uploaded' : '' ?>">
                <div class="text-center">
                    <div class="document-icon"><i class="fas fa-user-circle"></i></div>
                    <div class="upload-label">Profile Picture</div>
                    <p class="text-muted mb-3">Upload a professional photo (JPG, PNG • Max 2MB)</p>

                    <?php if ($has_profile_picture): ?>
                        <span class="status-badge complete"><i class="fas fa-check-circle me-1"></i> Uploaded</span>
                    <?php else: ?>
                        <div class="file-input-wrapper">
                            <input type="file" name="profile_picture" accept="image/*" onchange="showFileName(this, 'profile-selected')">
                            <label class="custom-file-label"><i class="fas fa-cloud-upload-alt me-2"></i>Choose Photo</label>
                        </div>
                        <div id="profile-selected" class="file-selected"></div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Required Documents -->
            <?php foreach ($required_documents as $type => $doc): ?>
                <div class="document-item <?= $doc['uploaded'] ? 'uploaded' : '' ?>">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center">
                            <div class="document-icon"><i class="fas <?= $doc['icon'] ?>"></i></div>
                        </div>
                        <div class="col-md-6">
                            <div class="upload-label"><?= esc($doc['label']) ?></div>
                            <p class="text-muted mb-0">Required for verification</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <?php if ($doc['uploaded']): ?>
                                <span class="status-badge complete"><i class="fas fa-check-circle me-1"></i> Uploaded</span>
                            <?php else: ?>
                                <div class="file-input-wrapper">
                                    <input type="file" name="documents[]" data-type="<?= $type ?>" accept="image/*,.pdf" onchange="showFileName(this, '<?= $type ?>-selected')">
                                    <label class="custom-file-label"><i class="fas fa-upload me-2"></i>Upload</label>
                                </div>
                                <div id="<?= $type ?>-selected" class="file-selected"></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary submit-btn" id="submitBtn">
                <span class="spinner-border spinner-border-sm me-2" style="display:none;"></span>
                <span class="btn-text">Save & Continue to Dashboard</span>
            </button>
        </form>

        <!-- Skip Link -->
        <div class="skip-link">
            <a href="<?= base_url('dashboard') ?>">Skip for now (complete later)</a>
        </div>

        </div>
    </main>

    <!-- Bottom Navigation -->
    <nav class="bottom-nav">
        <a href="<?= base_url('dashboard') ?>" class="nav-item">
            <div class="nav-icon"><i class="fas fa-home"></i></div>
            <div class="nav-label">Home</div>
        </a>
        <a href="<?= base_url('dashboard/messages') ?>" class="nav-item">
            <div class="nav-icon"><i class="fas fa-comment-dots"></i></div>
            <div class="nav-label">Messages</div>
        </a>
        <a href="<?= base_url('dashboard/profile') ?>" class="nav-item active">
            <div class="nav-icon"><i class="fas fa-user"></i></div>
            <div class="nav-label">Profile</div>
        </a>
    </nav>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function showFileName(input, targetId) {
        const target = document.getElementById(targetId);
        if (input.files && input.files[0]) {
            target.innerHTML = '<i class="fas fa-check-circle me-1"></i>' + input.files[0].name;
        } else {
            target.innerHTML = '';
        }
    }

    document.getElementById('uploadForm').onsubmit = function(e) {
        e.preventDefault();

        const btn = document.getElementById('submitBtn');
        const btnText = btn.querySelector('.btn-text');
        const spinner = btn.querySelector('.spinner-border');

        btn.disabled = true;
        spinner.style.display = 'inline-block';
        btnText.textContent = 'Uploading...';

        const formData = new FormData(this);

        // Add document types
        const fileInputs = this.querySelectorAll('input[type="file"][data-type]');
        fileInputs.forEach(input => {
            if (input.files && input.files[0]) {
                formData.append('doc_type_' + input.files[0].name, input.dataset.type);
            }
        });

        fetch("<?= site_url('dashboard/upload-documents') ?>", {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                document.getElementById('alertContainer').innerHTML =
                    '<div class="alert alert-success"><i class="fas fa-check-circle me-2"></i>' + data.message + '</div>';

                setTimeout(() => {
                    window.location.href = data.redirect || "<?= site_url('dashboard') ?>";
                }, 1500);
            } else {
                document.getElementById('alertContainer').innerHTML =
                    '<div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i>' + data.message + '</div>';

                btn.disabled = false;
                spinner.style.display = 'none';
                btnText.textContent = 'Save & Continue to Dashboard';
            }
        })
        .catch(() => {
            document.getElementById('alertContainer').innerHTML =
                '<div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i>Upload failed. Please try again.</div>';

            btn.disabled = false;
            spinner.style.display = 'none';
            btnText.textContent = 'Save & Continue to Dashboard';
        });
    };
</script>
</body>
</html>
