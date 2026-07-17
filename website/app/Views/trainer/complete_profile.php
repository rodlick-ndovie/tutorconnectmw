<?php
// Check if user has already completed profile setup
if (isset($user['registration_completed']) && $user['registration_completed'] == 1) {
    // User has uploaded documents and completed profile, redirect to dashboard
    header('Location: ' . base_url('trainer/dashboard'));
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Profile - TutorConnect Malawi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #E55C0D;
            --secondary-color: #C94609;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --accent-color: #0ea5e9;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --bg-primary: #f8fafc;
            --bg-secondary: #ffffff;
            --bg-accent: #f0f9ff;
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --border-radius: 16px;
            --border-radius-lg: 20px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            background-color: var(--bg-primary);
            font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'SF Pro Text', 'Segoe UI', Roboto, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            margin: 0;
            padding: 0;
        }

        .app-container {
            width: 100%;
            max-width: 480px;
            margin: 0 auto;
            min-height: 100vh;
            background: var(--bg-primary);
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
        }

        @media (min-width: 768px) {
            .app-container {
                max-width: 100%;
                box-shadow: none;
            }
        }

        .status-bar {
            height: 44px;
            background: var(--bg-secondary);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        @media (min-width: 768px) {
            .status-bar {
                display: none;
            }
        }

        .navbar {
            background: var(--bg-secondary);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            padding: 0;
            height: 64px;
            position: sticky;
            top: 44px;
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

        @media (min-width: 768px) {
            .navbar {
                height: 80px;
                top: 0;
            }
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 16px;
        }

        .screen {
            padding: 20px;
            padding-bottom: 100px;
            max-width: 1200px;
            margin: 0 auto;
        }

        @media (min-width: 768px) {
            .screen {
                padding: 32px 40px 120px 40px;
            }
        }

        @media (min-width: 1024px) {
            .screen {
                max-width: 1400px;
                padding: 40px 60px 120px 60px;
            }
        }

        .progress-container {
            background: var(--bg-secondary);
            border-radius: var(--border-radius);
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: var(--shadow);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .steps-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 16px;
            position: relative;
        }

        .steps-indicator::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 25px;
            right: 25px;
            height: 2px;
            background: #e5e7eb;
            z-index: 0;
        }

        .step {
            flex: 1;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e5e7eb;
            color: #9ca3af;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 8px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .step.active .step-circle {
            background: var(--primary-color);
            color: white;
            box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.2);
        }

        .step.completed .step-circle {
            background: var(--success-color);
            color: white;
        }

        .step-label {
            font-size: 11px;
            color: var(--text-light);
            font-weight: 500;
        }

        .step.active .step-label {
            color: var(--primary-color);
            font-weight: 600;
        }

        .progress-bar-container {
            background: #e5e7eb;
            height: 8px;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 16px;
        }

        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-color), var(--success-color));
            transition: width 0.5s ease;
        }

        .step-content {
            background: var(--bg-secondary);
            border-radius: var(--border-radius);
            padding: 24px;
            box-shadow: var(--shadow);
            border: 1px solid rgba(0, 0, 0, 0.05);
            margin-bottom: 24px;
        }

        @media (min-width: 768px) {
            .step-content {
                padding: 32px;
            }
        }

        .step-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .step-description {
            color: var(--text-light);
            margin-bottom: 24px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            color: var(--text-dark);
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .form-label .required {
            color: var(--danger-color);
        }

        .form-control, .form-select {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
            background: var(--bg-secondary);
        }

        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
        }

        .form-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23334155' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px 12px;
            padding-right: 40px;
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        .upload-area {
            border: 2px dashed rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            padding: 32px;
            text-align: center;
            transition: all 0.3s;
            cursor: pointer;
            background: var(--bg-primary);
        }

        .upload-area:hover {
            border-color: var(--primary-color);
            background: rgba(5, 150, 105, 0.05);
        }

        .upload-area.has-file {
            border-color: var(--success-color);
            border-style: solid;
            background: rgba(16, 185, 129, 0.05);
        }

        .upload-icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 12px;
        }

        .upload-area.has-file .upload-icon {
            color: var(--success-color);
        }

        .upload-text {
            color: var(--text-dark);
            font-weight: 500;
            margin-bottom: 4px;
        }

        .upload-hint {
            color: var(--text-light);
            font-size: 0.85rem;
        }

        .file-preview {
            margin-top: 12px;
            padding: 12px;
            background: white;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .checkbox-group {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 12px;
        }

        .checkbox-item {
            position: relative;
        }

        .checkbox-item label {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 8px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .checkbox-item input[type="checkbox"] {
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        .checkbox-item input[type="checkbox"]:checked + label,
        .checkbox-item label:has(input[type="checkbox"]:checked) {
            background: rgba(5, 150, 105, 0.1);
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .checkbox-input {
            display: none;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px;
            border: 2px solid rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            background: var(--bg-secondary);
            font-weight: 500;
            font-size: 0.9rem;
            text-align: center;
        }

        .checkbox-input:checked + .checkbox-label {
            border-color: var(--primary-color);
            background: rgba(5, 150, 105, 0.1);
            color: var(--primary-color);
        }

        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
        }

        .btn-primary:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        .btn-secondary {
            background: transparent;
            border: 1px solid rgba(0, 0, 0, 0.1);
            color: var(--text-dark);
        }

        .btn-secondary:hover {
            background: var(--bg-primary);
        }

        .button-group {
            display: flex;
            gap: 12px;
            margin-top: 24px;
        }

        .button-group .btn {
            flex: 1;
        }

        .success-message {
            background: var(--bg-secondary);
            border-radius: var(--border-radius);
            padding: 48px 24px;
            text-align: center;
            box-shadow: var(--shadow);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .success-icon {
            font-size: 4rem;
            color: var(--success-color);
            margin-bottom: 24px;
        }

        .success-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 12px;
        }

        .success-text {
            color: var(--text-light);
            margin-bottom: 32px;
        }

        .hidden {
            display: none;
        }

        .animate-step {
            animation: fadeInUp 0.35s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 480px;
            background: var(--bg-secondary);
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-around;
            padding: 8px 0 20px;
            z-index: 100;
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
            padding: 8px;
            border-radius: 12px;
        }

        .nav-item:hover {
            color: var(--primary-color);
            background: var(--bg-accent);
        }

        .nav-item.active {
            color: var(--primary-color);
        }

        .nav-item i {
            font-size: 20px;
            margin-bottom: 4px;
        }

        .nav-label {
            font-size: 10px;
            color: currentColor;
            font-weight: 500;
            text-align: center;
        }

        .selected-items {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 12px;
        }

        .selected-item {
            background: rgba(5, 150, 105, 0.1);
            color: var(--primary-color);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Mobile Status Bar -->
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

        <!-- Screen Content -->
        <div class="screen">
            <!-- Requirements Section -->
            <div id="requirementsBlock" class="step-content" style="background: linear-gradient(135deg, #fff5f0 0%, #ffe8e0 100%); border-left: 4px solid var(--primary-color);">
                <h2 class="step-title" style="color: var(--primary-color); font-size: 1.4rem; font-weight: 700;">
                    Before You Begin - Step 1
                </h2>
                <p class="step-description" style="margin-bottom: 24px;">Please prepare everything needed for all steps before you start:</p>

                <div style="display: grid; gap: 16px;">
                    <!-- Teaching Info -->
                    <div style="display: flex; gap: 12px; align-items: start;">
                        <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--primary-color); color: white; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fas fa-chalkboard-teacher" style="font-size: 0.9rem;"></i>
                        </div>
                        <div>
                            <h4 style="font-size: 1rem; font-weight: 600; margin-bottom: 4px; color: var(--text-dark);">Teaching Details <span class="required">*</span></h4>
                            <p style="font-size: 0.9rem; color: var(--text-light); margin: 0;">Teaching mode (online/in-person/both), years of experience, and your professional bio.</p>
                        </div>
                    </div>

                    <!-- Cover & Profile Photos -->
                    <div style="display: flex; gap: 12px; align-items: start;">
                        <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--primary-color); color: white; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fas fa-image" style="font-size: 0.9rem;"></i>
                        </div>
                        <div>
                            <h4 style="font-size: 1rem; font-weight: 600; margin-bottom: 4px; color: var(--text-dark);">Photos <span class="required">*</span></h4>
                            <p style="font-size: 0.9rem; color: var(--text-light); margin: 0;">Cover photo (1200x400px recommended, max 10MB) and profile picture (max 5MB).</p>
                        </div>
                    </div>

                    <!-- Intro Video Upload -->
                    <div style="display: flex; gap: 12px; align-items: start;">
                        <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--primary-color); color: white; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fas fa-video" style="font-size: 0.9rem;"></i>
                        </div>
                        <div>
                            <h4 style="font-size: 1rem; font-weight: 600; margin-bottom: 4px; color: var(--text-dark);">Introduction Video <span class="optional">(Optional)</span></h4>
                            <p style="font-size: 0.9rem; color: var(--text-light); margin: 0;">Upload a short intro video (MP4/MOV, max 50MB) about you and your teaching style.</p>
                        </div>
                    </div>

                    <!-- Contact Info -->
                    <div style="display: flex; gap: 12px; align-items: start;">
                        <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--primary-color); color: white; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fas fa-phone" style="font-size: 0.9rem;"></i>
                        </div>
                        <div>
                            <h4 style="font-size: 1rem; font-weight: 600; margin-bottom: 4px; color: var(--text-dark);">Contact Details <span class="required">*</span></h4>
                            <p style="font-size: 0.9rem; color: var(--text-light); margin: 0;">Phone number, WhatsApp number, and your location/district.</p>
                        </div>
                    </div>

                    <!-- Verification Docs -->
                    <div style="display: flex; gap: 12px; align-items: start;">
                        <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--primary-color); color: white; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fas fa-id-card" style="font-size: 0.9rem;"></i>
                        </div>
                        <div>
                            <h4 style="font-size: 1rem; font-weight: 600; margin-bottom: 4px; color: var(--text-dark);">Verification Documents <span class="required">*</span></h4>
                            <p style="font-size: 0.9rem; color: var(--text-light); margin: 0;">National ID, educational certificates, and teaching credentials (scans, max 5MB each).</p>
                        </div>
                    </div>
                </div>

                <div style="margin-top: 24px; padding: 16px; background: white; border-radius: 12px; border: 1px solid rgba(0,0,0,0.1);">
                    <div style="display: flex; gap: 12px; align-items: start;">
                        <i class="fas fa-info-circle" style="color: var(--accent-color); font-size: 1.2rem; margin-top: 2px;"></i>
                        <div>
                            <p style="margin: 0; font-size: 0.9rem; color: var(--text-dark);"><strong>Note:</strong> All fields marked with <span class="required">*</span> are required. You'll complete your profile in 7 easy steps.</p>
                        </div>
                    </div>
                </div>

                <div style="margin-top: 20px;">
                    <button type="button" class="btn btn-primary w-100" onclick="startProfile()">
                        <i class="fas fa-rocket me-2"></i> Start Profile Setup
                    </button>
                </div>
            </div>

            <!-- Progress Container -->
            <div id="progressContainer" class="progress-container d-none">
                <div class="steps-indicator">
                    <div class="step active" data-step="1">
                        <div class="step-circle">1</div>
                        <div class="step-label">Teaching</div>
                    </div>
                    <div class="step" data-step="2">
                        <div class="step-circle">2</div>
                        <div class="step-label">Cover</div>
                    </div>
                    <div class="step" data-step="3">
                        <div class="step-circle">3</div>
                        <div class="step-label">Photo</div>
                    </div>
                    <div class="step" data-step="4">
                        <div class="step-circle">4</div>
                        <div class="step-label">Video</div>
                    </div>
                    <div class="step" data-step="5">
                        <div class="step-circle">5</div>
                        <div class="step-label">Contact</div>
                    </div>
                    <div class="step" data-step="6">
                        <div class="step-circle">6</div>
                        <div class="step-label">Docs</div>
                    </div>
                    <div class="step" data-step="7">
                        <div class="step-circle">7</div>
                        <div class="step-label">Done</div>
                    </div>
                </div>
                <div class="progress-bar-container">
                    <div class="progress-bar-fill" id="progressBar" style="width: 12.5%"></div>
                </div>
            </div>

            <!-- Step 1: Teaching Information -->
            <div class="step-content hidden" id="step1">
                <h2 class="step-title">Teaching Information</h2>
                <p class="step-description">Tell us about your teaching experience and preferences</p>

                <form id="step1Form">
                    <div class="form-group">
                        <label class="form-label">Teaching Mode <span class="required">*</span></label>
                        <select class="form-control form-select" name="teaching_mode" required>
                            <option value="">Select Teaching Mode</option>
                            <option value="online">Online Only</option>
                            <option value="in-person">In-Person Only</option>
                            <option value="both">Both Online & In-Person</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Years of Experience <span class="required">*</span></label>
                        <input type="number" class="form-control" name="experience_years" min="0" max="50" placeholder="e.g., 5" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Bio <span class="required">*</span></label>
                        <textarea class="form-control" name="bio" placeholder="Tell students about yourself, your teaching style, and qualifications..." required></textarea>
                        <small class="text-muted">This will be displayed on your public profile</small>
                    </div>

                    <div class="button-group">
                        <button type="submit" class="btn btn-primary">
                            Next Step <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Step 2: Cover Photo -->
            <div class="step-content hidden" id="step2">
                <h2 class="step-title">Cover Photo</h2>
                <p class="step-description">Upload a cover photo that represents your teaching style</p>

                <form id="step2Form">
                    <div class="form-group">
                        <div class="upload-area" id="coverUploadArea" onclick="document.getElementById('coverPhoto').click()">
                            <div class="upload-icon" id="coverIcon">
                                <i class="fas fa-image"></i>
                            </div>
                            <div class="upload-text" id="coverText">Click to upload cover photo</div>
                            <div class="upload-hint">JPG, PNG or GIF (max. 10MB, recommended 1200x400px)</div>
                            <div id="coverPreview" class="file-preview hidden"></div>
                        </div>
                        <input type="file" id="coverPhoto" name="cover_photo" accept="image/*" class="d-none" required>
                    </div>

                    <div class="button-group">
                        <button type="button" class="btn btn-secondary" onclick="previousStep()">
                            <i class="fas fa-arrow-left me-2"></i> Previous
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Next Step <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Step 3: Profile Picture -->
            <div class="step-content hidden" id="step3">
                <h2 class="step-title">Profile Picture</h2>
                <p class="step-description">Upload a professional photo of yourself (this will be visible to students)</p>

                <form id="step3Form">
                    <div class="form-group">
                        <div class="upload-area" id="photoUploadArea" onclick="document.getElementById('profilePicture').click()">
                            <div class="upload-icon" id="photoIcon">
                                <i class="fas fa-camera"></i>
                            </div>
                            <div class="upload-text" id="photoText">Click to upload profile picture</div>
                            <div class="upload-hint">JPG, PNG or GIF (max. 5MB)</div>
                            <div id="photoPreview" class="file-preview hidden"></div>
                        </div>
                        <input type="file" id="profilePicture" name="profile_picture" accept="image/*" class="d-none" required>
                    </div>

                    <div class="button-group">
                        <button type="button" class="btn btn-secondary" onclick="previousStep()">
                            <i class="fas fa-arrow-left me-2"></i> Previous
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Next Step <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Step 4: Introduction Video -->
            <div class="step-content hidden" id="step4">
                <h2 class="step-title">Introduction Video</h2>
                <p class="step-description">Upload a short introduction video (30-60 seconds) so students can see you teach. This helps build trust and improves your profile visibility.</p>

                <form id="step4Form">
                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Important:</strong> Video file must be between 1MB and 50MB. Recommended length: 30-60 seconds.
                    </div>

                    <div class="form-group">
                        <label class="form-label">Introduction Video <span class="optional">(Optional)</span></label>
                        <div class="upload-area" id="videoUploadArea" onclick="document.getElementById('introVideo').click()">
                            <div class="upload-icon" id="videoIcon">
                                <i class="fas fa-video"></i>
                            </div>
                            <div class="upload-text" id="videoText">Click to upload video</div>
                            <div class="upload-hint">MP4, WebM, or OGV (Max 50MB, 30-60 seconds recommended)</div>
                            <div id="videoPreview" class="file-preview hidden"></div>
                        </div>
                        <input type="file" id="introVideo" name="intro_video" accept="video/*" class="d-none">
                    </div>

                    <div class="button-group">
                        <button type="button" class="btn btn-secondary" onclick="previousStep()">
                            <i class="fas fa-arrow-left me-2"></i> Previous
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Next Step <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Step 5: Contact & Availability -->
            <div class="step-content hidden" id="step5">
                <h2 class="step-title">Contact & Availability</h2>
                <p class="step-description">Share your preferred contact method and teaching availability.</p>

                <form id="step5Form">
                     <!-- WhatsApp Number -->
                    <div class="form-group">
                    <label for="whatsappNumber" class="form-label">WhatsApp Number</label>
                    <input
                        type="tel"
                        id="whatsappNumber"
                        name="whatsapp_number"
                        class="form-control"
                        placeholder="99 XXX XXXX"
                        pattern="^(99|88)\d{7}$"
                        title="Enter a valid WhatsApp number starting with 99 or 88 followed by 7 digits"
                        required
                    >
                    <small class="text-muted">Start with 99 or 88, followed by 7 digits</small>
                </div>

                    <!-- Availability -->
                    <div class="form-group">
                        <label class="form-label">Availability <span class="required">*</span></label>
                        <p class="text-muted" style="font-size: 0.9rem;">Select the days and times you're available to teach</p>

                        <div style="margin-top: 15px;">
                            <div style="margin-bottom: 12px;">
                                <strong style="font-size: 0.9rem;">Days of Week</strong>
                            </div>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(80px, 1fr)); gap: 8px;">
                                <label class="checkbox-item">
                                    <input type="checkbox" class="availability-day" value="Monday"> Monday
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" class="availability-day" value="Tuesday"> Tuesday
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" class="availability-day" value="Wednesday"> Wednesday
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" class="availability-day" value="Thursday"> Thursday
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" class="availability-day" value="Friday"> Friday
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" class="availability-day" value="Saturday"> Saturday
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" class="availability-day" value="Sunday"> Sunday
                                </label>
                            </div>
                        </div>

                        <div style="margin-top: 20px;">
                            <div style="margin-bottom: 12px;">
                                <strong style="font-size: 0.9rem;">Time Preference</strong>
                            </div>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(100px, 1fr)); gap: 8px;">
                                <label class="checkbox-item">
                                    <input type="checkbox" class="availability-time" value="Morning (8AM-12PM)"> Morning
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" class="availability-time" value="Afternoon (12PM-5PM)"> Afternoon
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" class="availability-time" value="Evening (5PM-9PM)"> Evening
                                </label>
                            </div>
                        </div>

                        <input type="hidden" id="availabilityData" name="availability">
                    </div>

                    <div class="button-group">
                        <button type="button" class="btn btn-secondary" onclick="previousStep()">
                            <i class="fas fa-arrow-left me-2"></i> Previous
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Next Step <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Step 6: Documents -->
            <div class="step-content hidden" id="step6">
                <h2 class="step-title">Verification Documents</h2>
                <p class="step-description">Upload required documents for verification (clear scans or photos). These are reviewed by our team and not shown to students.</p>

                <form id="step6Form">
                    <!-- National ID -->
                    <div class="form-group">
                        <label class="form-label">National ID <span class="required">*</span></label>
                        <div class="upload-area" id="idUploadArea" onclick="document.getElementById('nationalId').click()">
                            <div class="upload-icon" id="idIcon">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <div class="upload-text" id="idText">Click to upload National ID</div>
                            <div class="upload-hint">Front and back sides (PDF or Image)</div>
                            <div id="idPreview" class="file-preview hidden"></div>
                        </div>
                        <input type="file" id="nationalId" name="national_id" accept="image/*,application/pdf" class="d-none" required>
                    </div>

                    <!-- Academic Certificates -->
                    <div class="form-group">
                        <label class="form-label">Academic Certificates <span class="required">*</span></label>
                        <div class="upload-area" id="certUploadArea" onclick="document.getElementById('certificates').click()">
                            <div class="upload-icon" id="certIcon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div class="upload-text" id="certText">Click to upload certificates</div>
                            <div class="upload-hint">Degrees, diplomas, or relevant certifications</div>
                            <div id="certPreview" class="file-preview hidden"></div>
                        </div>
                        <input type="file" id="certificates" name="academic_certificates" accept="image/*,application/pdf" class="d-none" required>
                    </div>

                    <!-- Police Clearance -->
                    <div class="form-group">
                        <label class="form-label">Police Clearance <span class="optional">(Optional)</span></label>
                        <div class="upload-area" id="policeUploadArea" onclick="document.getElementById('policeClearance').click()">
                            <div class="upload-icon" id="policeIcon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div class="upload-text" id="policeText">Click to upload police clearance</div>
                            <div class="upload-hint">Valid police clearance certificate (optional)</div>
                            <div id="policePreview" class="file-preview hidden"></div>
                        </div>
                        <input type="file" id="policeClearance" name="police_clearance" accept="image/*,application/pdf" class="d-none">
                    </div>

                    <!-- Teaching Qualification -->
                    <div class="form-group">
                        <label class="form-label">Teaching Qualification <span class="required">*</span></label>
                        <div class="upload-area" id="qualUploadArea" onclick="document.getElementById('qualification').click()">
                            <div class="upload-icon" id="qualIcon">
                                <i class="fas fa-certificate"></i>
                            </div>
                            <div class="upload-text" id="qualText">Click to upload teaching qualification</div>
                            <div class="upload-hint">Teaching certificate, TEFL, PGCE, or professional credentials</div>
                            <div id="qualPreview" class="file-preview hidden"></div>
                        </div>
                        <input type="file" id="qualification" name="teaching_qualification" accept="image/*,application/pdf" class="d-none" required>
                    </div>

                    <div class="button-group">
                        <button type="button" class="btn btn-secondary" onclick="previousStep()">
                            <i class="fas fa-arrow-left me-2"></i> Previous
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-spinner fa-spin me-2 d-none" id="submitSpinner"></i>
                            Complete Setup <i class="fas fa-check ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Step 7: Success -->
            <div class="success-message hidden" id="step7">
                <div class="success-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h2 class="success-title">Profile Complete!</h2>
                <p class="success-text">Your profile has been submitted successfully. Our team will review your documents and activate your account within 24-48 hours. You can now access your dashboard.</p>
                <button class="btn btn-primary" onclick="window.location.href='<?= site_url('trainer/dashboard') ?>'">
                    Go to Dashboard <i class="fas fa-arrow-right ms-2"></i>
                </button>
            </div>
        </div>

        <!-- Bottom Navigation -->
        <nav class="bottom-nav">
            <div class="nav-item disabled" style="opacity: 0.5; cursor: not-allowed;">
                <i class="fas fa-home"></i>
                <span class="nav-label">Home</span>
            </div>
            <div class="nav-item disabled" style="opacity: 0.5; cursor: not-allowed;">
                <i class="fas fa-book"></i>
                <span class="nav-label">Subjects</span>
            </div>
            <div class="nav-item active">
                <i class="fas fa-user"></i>
                <span class="nav-label">Profile</span>
            </div>
            <div class="nav-item disabled" style="opacity: 0.5; cursor: not-allowed;">
                <i class="fas fa-crown"></i>
                <span class="nav-label">Premium</span>
            </div>
        </nav>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentStep = 1;
        const totalSteps = 7;
        const formData = new FormData();

        // File upload handlers
        document.getElementById('coverPhoto').addEventListener('change', function(e) {
            handleFilePreview(e.target, 'coverUploadArea', 'coverIcon', 'coverText', 'coverPreview');
        });

        document.getElementById('profilePicture').addEventListener('change', function(e) {
            handleFilePreview(e.target, 'photoUploadArea', 'photoIcon', 'photoText', 'photoPreview');
        });

        document.getElementById('nationalId').addEventListener('change', function(e) {
            handleFilePreview(e.target, 'idUploadArea', 'idIcon', 'idText', 'idPreview');
        });

        document.getElementById('certificates').addEventListener('change', function(e) {
            handleFilePreview(e.target, 'certUploadArea', 'certIcon', 'certText', 'certPreview');
        });

        document.getElementById('policeClearance').addEventListener('change', function(e) {
            handleFilePreview(e.target, 'policeUploadArea', 'policeIcon', 'policeText', 'policePreview');
        });

        document.getElementById('introVideo').addEventListener('change', function(e) {
            handleFilePreview(e.target, 'videoUploadArea', 'videoIcon', 'videoText', 'videoPreview');
        });

        document.getElementById('qualification').addEventListener('change', function(e) {
            handleFilePreview(e.target, 'qualUploadArea', 'qualIcon', 'qualText', 'qualPreview');
        });

        // Re-enable submit button when files are changed
        function enableSubmitButton() {
            const submitBtn = document.querySelector('#step6Form button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = false;
                document.getElementById('submitSpinner').classList.add('d-none');
            }
        }

        // Add change listeners to all document inputs to re-enable button
        ['nationalId', 'certificates', 'policeClearance', 'qualification'].forEach(id => {
            document.getElementById(id).addEventListener('change', enableSubmitButton);
        });

        function handleFilePreview(input, areaId, iconId, textId, previewId) {
            const file = input.files[0];
            if (file) {
                const area = document.getElementById(areaId);
                const preview = document.getElementById(previewId);

                area.classList.add('has-file');
                document.getElementById(iconId).innerHTML = '<i class="fas fa-check-circle"></i>';
                document.getElementById(textId).textContent = 'File uploaded successfully';

                preview.innerHTML = `
                    <i class="fas fa-file text-success"></i>
                    <span>${file.name}</span>
                `;
                preview.classList.remove('hidden');
            }
        }

        function startProfile() {
            const reqBlock = document.getElementById('requirementsBlock');
            if (reqBlock) {
                reqBlock.classList.add('d-none');
            }
            const progress = document.getElementById('progressContainer');
            if (progress) {
                progress.classList.remove('d-none');
            }
            const step1 = document.getElementById('step1');
            if (step1) {
                showStep(1);
            }
        }

        function showStep(stepNumber) {
            const stepEl = document.getElementById('step' + stepNumber);
            if (!stepEl) return;
            stepEl.classList.remove('hidden');
            stepEl.classList.add('animate-step');
            setTimeout(() => stepEl.classList.remove('animate-step'), 400);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Step 1 Form Submit
        document.getElementById('step1Form').addEventListener('submit', function(e) {
            e.preventDefault();

            const formElements = e.target.elements;
            formData.set('teaching_mode', formElements.teaching_mode.value);
            formData.set('experience_years', formElements.experience_years.value);
            formData.set('bio', formElements.bio.value);

            nextStep();
        });

        // Step 2 Form Submit
        document.getElementById('step2Form').addEventListener('submit', function(e) {
            e.preventDefault();

            const file = document.getElementById('coverPhoto').files[0];
            if (file) {
                formData.set('cover_photo', file);
                nextStep();
            } else {
                alert('Please upload a cover photo');
            }
        });

        // Step 3 Form Submit
        document.getElementById('step3Form').addEventListener('submit', function(e) {
            e.preventDefault();

            const file = document.getElementById('profilePicture').files[0];
            if (file) {
                formData.set('profile_picture', file);
                nextStep();
            } else {
                alert('Please upload a profile picture');
            }
        });

        // Step 4 Form Submit (Video - Optional)
        document.getElementById('step4Form').addEventListener('submit', function(e) {
            e.preventDefault();

            const file = document.getElementById('introVideo').files[0];
            console.log('Step 4 - Video file selected (optional):', file);

            if (file) {
                console.log('Setting intro_video in formData:', file.name);
                formData.set('intro_video', file);
                console.log('FormData contents after video:', Array.from(formData.entries()).map(e => [e[0], e[1].name || e[1]]));
            } else {
                console.log('No video uploaded - skipping (optional)');
            }

            // Always proceed to next step since video is optional
            nextStep();
        });

        // Step 5 Form Submit (Contact & Availability)
        document.getElementById('step5Form').addEventListener('submit', function(e) {
            e.preventDefault();

            const whatsapp = document.getElementById('whatsappNumber').value;
            formData.set('whatsapp_number', whatsapp);

            // Collect availability data
            const selectedDays = Array.from(document.querySelectorAll('.availability-day:checked')).map(cb => cb.value);
            const selectedTimes = Array.from(document.querySelectorAll('.availability-time:checked')).map(cb => cb.value);

            const availability = {
                days: selectedDays,
                times: selectedTimes
            };

            formData.set('availability', JSON.stringify(availability));

            nextStep();
        });

        // Step 6 Form Submit (Documents)
        document.getElementById('step6Form').addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = e.target.querySelector('button[type="submit"]');
            const spinner = document.getElementById('submitSpinner');

            submitBtn.disabled = true;
            spinner.classList.remove('d-none');

            formData.set('national_id', document.getElementById('nationalId').files[0]);
            formData.set('academic_certificates', document.getElementById('certificates').files[0]);
            formData.set('teaching_qualification', document.getElementById('qualification').files[0]);

            // Police clearance is optional
            const policeClearanceFile = document.getElementById('policeClearance').files[0];
            if (policeClearanceFile) {
                formData.set('police_clearance', policeClearanceFile);
            }

            submitAllData();
        });

        function submitAllData() {
            fetch('<?= site_url('trainer/dashboard/submit-complete-profile') ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    nextStep();
                } else {
                    console.log('Validation errors:', data.errors);
                    const errorMessage = data.errors && data.errors.length > 0
                        ? 'Errors:\n' + data.errors.join('\n')
                        : (data.message || 'Failed to submit profile');
                    alert(errorMessage);
                    document.querySelector('button[type="submit"]').disabled = false;
                    document.getElementById('submitSpinner').classList.add('d-none');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while submitting your profile');
                document.querySelector('button[type="submit"]').disabled = false;
                document.getElementById('submitSpinner').classList.add('d-none');
            });
        }

        function nextStep() {
            if (currentStep < totalSteps) {
                document.getElementById('step' + currentStep).classList.add('hidden');

                document.querySelector(`.step[data-step="${currentStep}"]`).classList.remove('active');
                document.querySelector(`.step[data-step="${currentStep}"]`).classList.add('completed');
                document.querySelector(`.step[data-step="${currentStep}"] .step-circle`).innerHTML = '<i class="fas fa-check"></i>';

                currentStep++;

                showStep(currentStep);
                document.querySelector(`.step[data-step="${currentStep}"]`).classList.add('active');

                const progress = (currentStep / totalSteps) * 100;
                document.getElementById('progressBar').style.width = progress + '%';

                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        }

        function previousStep() {
            if (currentStep > 1) {
                document.getElementById('step' + currentStep).classList.add('hidden');
                document.querySelector(`.step[data-step="${currentStep}"]`).classList.remove('active');

                currentStep--;

                showStep(currentStep);
                document.querySelector(`.step[data-step="${currentStep}"]`).classList.remove('completed');
                document.querySelector(`.step[data-step="${currentStep}"]`).classList.add('active');

                if (currentStep < totalSteps) {
                    document.querySelector(`.step[data-step="${currentStep}"] .step-circle`).textContent = currentStep;
                }

                const progress = (currentStep / totalSteps) * 100;
                document.getElementById('progressBar').style.width = progress + '%';

                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        }
    </script>
</body>
</html>                const progress = (currentStep / totalSteps) * 100;
