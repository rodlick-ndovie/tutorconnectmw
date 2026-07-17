<?= $this->extend('layout/trainer_shared') ?>

<?= $this->section('content') ?>

    <style>
        /* Profile Header Card */
        .profile-header-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .profile-picture {
            position: relative;
            display: inline-block;
            margin-bottom: 1rem;
        }

        .profile-picture img,
        .profile-picture .avatar-placeholder {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: var(--card-shadow);
        }

        .profile-picture .avatar-placeholder {
            background: var(--primary);
            color: white;
            font-size: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .camera-btn {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 36px;
            height: 36px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            border: none;
            box-shadow: var(--card-shadow);
        }

        .bio-video-section {
            margin-top: 1.5rem;
            padding: 1rem;
            background: var(--gray-50);
            border-radius: var(--border-radius);
        }

        .video-upload-btn {
            position: relative;
            display: inline-block;
        }

        .video-upload-btn input[type="file"] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .video-preview {
            margin-top: 1rem;
            max-width: 300px;
        }

        .video-preview video {
            width: 100%;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
        }

        .video-placeholder {
            width: 100%;
            max-width: 300px;
            height: 200px;
            background: var(--gray-200);
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-500);
            border: 2px dashed var(--gray-300);
        }

        .profile-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin: 1.5rem 0;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--dark);
        }

        .stat-label {
            font-size: 0.85rem;
            color: var(--gray-500);
        }

        .progress {
            height: 8px;
            border-radius: 4px;
            background: var(--gray-200);
        }

        .progress-bar {
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }

        /* Tabs */
        .profile-tabs {
            display: flex;
            overflow-x: auto;
            gap: 0.75rem;
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
            white-space: nowrap;
        }

        .tab-btn {
            padding: 0.75rem 1rem;
            border-radius: 50px;
            background: var(--gray-100);
            color: var(--gray-600);
            border: none;
            font-size: 0.95rem;
            flex-shrink: 0;
        }

        .tab-btn.active {
            background: var(--primary);
            color: white;
        }

        /* Cards */
        .info-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 1.5rem;
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        @media (max-width: 480px) {
            .profile-stats {
                grid-template-columns: 1fr 1fr 1fr;
            }
        }
    </style>

    <h1 class="section-title">Profile Settings</h1>
    <p class="section-subtitle">Manage your account, profile, and preferences</p>

    <!-- Profile Header Card -->
    <div class="profile-header-card">
        <div class="profile-picture">
            <?php if($user['profile_picture']): ?>
                <img src="<?= base_url($user['profile_picture']) ?>" alt="Profile">
            <?php else:
            ?>
                <div class="avatar-placeholder">
                    <?= strtoupper(substr($user['first_name'] ?? 'T', 0, 1)) ?>
                </div>
            <?php endif; ?>
            <button class="camera-btn" onclick="document.getElementById('profilePictureInput').click()">
                <i class="fas fa-camera"></i>
            </button>
            <input type="file" id="profilePictureInput" class="d-none" accept="image/*">
        </div>

        <!-- Bio Video Section -->
        <div class="bio-video-section">
            <h6 class="mb-2"><i class="fas fa-video text-primary me-2"></i>Bio Video</h6>
            <p class="small text-muted mb-3">Upload a short video introducing yourself (max 60 seconds, 50MB)</p>

            <div class="video-upload-btn">
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('bioVideoInput').click()">
                    <i class="fas fa-upload me-2"></i>Upload Bio Video
                </button>
                <input type="file" id="bioVideoInput" class="d-none" accept="video/mp4,video/mov,video/avi,video/webm">
            </div>

            <div class="video-preview">
                <?php if(!empty($user['bio_video'])): ?>
                    <video controls style="width: 100%; max-width: 300px;">
                        <source src="<?= base_url('uploads/videos/' . $user['bio_video']) ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                    <div class="mt-2">
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="deleteBioVideo()">
                            <i class="fas fa-trash me-2"></i>Delete Video
                        </button>
                    </div>
                <?php else: ?>
                    <div class="video-placeholder">
                        <div class="text-center">
                            <i class="fas fa-video fa-2x mb-2"></i>
                            <p class="mb-0">No bio video uploaded</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <h4><?= esc($user['first_name'] . ' ' . $user['last_name']) ?></h4>
        <p class="text-muted"><?= $user['title'] ?? 'Tutor' ?></p>

        <div class="rating mb-3">
            <?php for($i = 1; $i <= 5; $i++):
            ?>
                <i class="fas fa-star <?= $i <= ($user['avg_rating'] ?? 0) ? 'text-warning' : 'text-muted' ?>"></i>
            <?php endfor;
            ?>
            <span class="ms-2 small"><?= number_format($user['avg_rating'] ?? 0, 1) ?> (<?= $user['total_reviews'] ?? 0 ?>)</span>
        </div>

        <div class="profile-stats">
            <div class="stat-item">
                <div class="stat-value"><?= $stats['students'] ?? 0 ?></div>
                <div class="stat-label">Students</div>
            </div>

            <div class="stat-item">
                <div class="stat-value"><?= $stats['response_rate'] ?? 0 ?>%</div>
                <div class="stat-label">Response</div>
            </div>
        </div>

        <div>
            <div class="d-flex justify-content-between mb-1">
                <small>Profile Strength</small>
                <small class="fw-bold"><?= $profile_completion ?? 65 ?>%</small>
            </div>
            <div class="progress">
                <div class="progress-bar" style="width: <?= $profile_completion ?? 65 ?>%"></div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="profile-tabs">
        <button class="tab-btn active" data-tab="personal">Personal</button>
        <button class="tab-btn" data-tab="professional">Professional</button>
        <button class="tab-btn" data-tab="preferences">Preferences</button>
        <button class="tab-btn" data-tab="security">Security</button>
    </div>

    <!-- Personal Info -->
    <div class="info-card tab-content active" id="personal">
        <h5 class="card-title">Personal Information</h5>
        <form action="<?= site_url('trainer/updatePersonal') ?>" method="post">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label">First Name</label>
                <input type="text" class="form-control" name="first_name" value="<?= esc($user['first_name'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Last Name</label>
                <input type="text" class="form-control" name="last_name" value="<?= esc($user['last_name'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" value="<?= esc($user['email'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Phone (+265)</label>
                <input type="tel" class="form-control" name="phone" value="<?= esc($user['phone'] ?? '') ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Bio</label>
                <textarea class="form-control" name="bio" rows="4"><?= esc($user['bio'] ?? '') ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary w-100">Save Changes</button>
        </form>
    </div>

    <!-- Professional Info -->
    <div class="info-card tab-content" id="professional" style="display:none;">
        <h5 class="card-title">Professional Information</h5>
        <form action="<?= site_url('trainer/updateProfessional') ?>" method="post">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label">District</label>
                <select class="form-control" name="district">
                    <option value="">Select District</option>
                    <?php
                    $districts = ['Blantyre', 'Lilongwe', 'Zomba', 'Mzuzu', 'Kasungu', 'Mangochi', 'Karonga', 'Salima', 'Nkhotakota', 'Nsanje'];
                    foreach ($districts as $district) {
                        $selected = ($user['district'] ?? '') === $district ? 'selected' : '';
                        echo "<option value=\"$district\" $selected>$district</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Teaching Mode</label>
                <select class="form-control" name="teaching_mode">
                    <option value="">Select Teaching Mode</option>
                    <option value="Online Only" <?= ($user['teaching_mode'] ?? '') === 'Online Only' ? 'selected' : '' ?>>Online Only</option>
                    <option value="In-Person Only" <?= ($user['teaching_mode'] ?? '') === 'In-Person Only' ? 'selected' : '' ?>>In-Person Only</option>
                    <option value="Both" <?= ($user['teaching_mode'] ?? '') === 'Both' ? 'selected' : '' ?>>Both Online & In-Person</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Years of Experience</label>
                <select class="form-control" name="experience_years">
                    <option value="">Select Experience</option>
                    <option value="1" <?= ($user['experience_years'] ?? '') == '1' ? 'selected' : '' ?>>1 year</option>
                    <option value="3" <?= ($user['experience_years'] ?? '') == '3' ? 'selected' : '' ?>>3 years</option>
                    <option value="5" <?= ($user['experience_years'] ?? '') == '5' ? 'selected' : '' ?>>5 years</option>
                    <option value="10" <?= ($user['experience_years'] ?? '') == '10' ? 'selected' : '' ?>>10+ years</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Hourly Rate (MWK)</label>
                <input type="number" class="form-control" name="hourly_rate" value="<?= esc($user['hourly_rate'] ?? '') ?>" placeholder="e.g. 25000">
            </div>
            <div class="mb-3">
                <label class="form-label">WhatsApp Number</label>
                <input type="tel" class="form-control" name="whatsapp_number" value="<?= esc($user['whatsapp_number'] ?? '') ?>" placeholder="+265 XXX XXX XXX">
            </div>

            <div class="mb-4">
                <label class="form-label">Teaching Curriculum & Subjects</label>

                <?php
                // Curriculum data structure
                $curriculumData = [
                    'MANEB' => [
                        'name' => 'MANEB (Malawi National Curriculum)',
                        'levels' => [
                            'Primary (Standards 1-8)' => ['Chichewa', 'English', 'Mathematics', 'Expressive Arts', 'Life Skills', 'Social and Environmental Sciences', 'Science and Technology', 'Agriculture', 'Religious Education'],
                            'JCE Preparation' => ['English Language', 'Mathematics', 'Chichewa', 'Social Studies', 'Biology', 'Physics', 'Chemistry', 'Geography', 'History', 'Agriculture', 'Computer Studies', 'Life Skills', 'Religious Education'],
                            'MSCE Preparation' => ['English Language', 'Mathematics', 'Chichewa', 'Social Studies', 'Biology', 'Physics', 'Chemistry', 'Geography', 'History', 'Agriculture', 'Computer Studies', 'Life Skills', 'Religious Education']
                        ]
                    ],
                    'GCSE' => [
                        'name' => 'GCSE (General Certificate of Secondary Education)',
                        'levels' => [
                            'Key Stage 4 (Years 10-11)' => ['English Language', 'English Literature', 'Mathematics', 'Combined Science', 'Biology', 'Chemistry', 'Physics', 'Art and Design', 'Computer Science', 'Geography', 'History', 'Modern Foreign Languages', 'Music', 'Physical Education', 'Religious Studies', 'Business', 'Economics', 'Psychology', 'Sociology']
                        ]
                    ],
                    'Cambridge' => [
                        'name' => 'Cambridge (Cambridge International Curriculum)',
                        'levels' => [
                            'Cambridge IGCSE' => ['English Language', 'Mathematics', 'Additional Mathematics', 'Biology', 'Chemistry', 'Physics', 'Combined Science', 'Accounting', 'Business Studies', 'Economics', 'Geography', 'History', 'Computer Science', 'ICT'],
                            'Cambridge AS/A Level' => ['English Language', 'Literature in English', 'Mathematics', 'Further Mathematics', 'Biology', 'Chemistry', 'Physics', 'Computer Science', 'Accounting', 'Business', 'Economics', 'Geography', 'History', 'Psychology', 'Sociology']
                        ]
                    ]
                ];

                $selectedCurricula = json_decode($user['curriculum'] ?? '[]', true) ?: [];
                $selectedSubjects = $user['subjects'] ?? [];
                $selectedLevels = $user['education_levels'] ?? [];

                foreach ($curriculumData as $curriculumKey => $curriculumInfo):
                    $isCurriculumSelected = in_array($curriculumKey, $selectedCurricula);
                ?>
                <div class="curriculum-section mb-4" style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 1rem; background: #fafbfc;">
                    <div class="curriculum-header mb-3">
                        <div class="form-check">
                            <input class="form-check-input curriculum-toggle" type="checkbox" name="curriculum[]" value="<?= $curriculumKey ?>" id="curriculum_<?= $curriculumKey ?>" <?= $isCurriculumSelected ? 'checked' : '' ?>/>
                            <label class="form-check-label fw-bold text-primary" for="curriculum_<?= $curriculumKey ?>">
                                <i class="fas fa-book me-2"></i><?= $curriculumInfo['name'] ?>
                            </label>
                        </div>
                    </div>

                    <?php if ($isCurriculumSelected): ?>
                    <div class="curriculum-content" id="content_<?= $curriculumKey ?>">
                        <?php foreach ($curriculumInfo['levels'] as $levelName => $subjects): ?>
                        <div class="level-section mb-3" style="border-left: 3px solid #007bff; padding-left: 1rem; background: white; padding: 0.75rem; border-radius: 6px;">
                            <div class="level-header mb-2">
                                <div class="form-check">
                                    <input class="form-check-input level-toggle" type="checkbox" name="education_levels[]" value="<?= $levelName ?>" id="level_<?= $curriculumKey ?>_<?= md5($levelName) ?>" <?= in_array($levelName, $selectedLevels) ? 'checked' : '' ?>/>
                                    <label class="form-check-label fw-semibold text-dark" for="level_<?= $curriculumKey ?>_<?= md5($levelName) ?>">
                                        <i class="fas fa-graduation-cap me-2 text-info"></i><?= $levelName ?>
                                    </label>
                                </div>
                            </div>

                            <?php
                            $isLevelSelected = in_array($levelName, $selectedLevels);
                            if ($isLevelSelected):
                            ?>
                            <div class="subjects-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 0.5rem; margin-top: 0.5rem;">
                                <?php foreach ($subjects as $subject): ?>
                                <div class="form-check" style="margin-bottom: 0.25rem;">
                                    <input class="form-check-input" type="checkbox" name="subjects[]" value="<?= $subject ?>" id="subject_<?= $curriculumKey ?>_<?= md5($levelName . $subject) ?>" <?= in_array($subject, $selectedSubjects) ? 'checked' : '' ?>/>
                                    <label class="form-check-label small" for="subject_<?= $curriculumKey ?>_<?= md5($levelName . $subject) ?>" style="font-size: 0.85rem;">
                                        <?= $subject ?>
                                    </label>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_employed" value="1" id="is_employed" <?= ($user['is_employed'] ?? 0) ? 'checked' : '' ?>/>
                    <label class="form-check-label" for="is_employed">
                        Currently employed at a school
                    </label>
                </div>
            </div>
            <div class="mb-3 school-name-field" style="display: <?= ($user['is_employed'] ?? 0) ? 'block' : 'none' ?>;">
                <label class="form-label">School Name</label>
                <input type="text" class="form-control" name="school_name" value="<?= esc($user['school_name'] ?? '') ?>" placeholder="Enter school name">
            </div>
            <button type="submit" class="btn btn-primary w-100">Save Professional Info</button>
        </form>
    </div>

    <div class="info-card tab-content" id="preferences" style="display:none;">
        <h5 class="card-title">Preferences</h5>
        <form action="<?= site_url('trainer/updatePreferences') ?>" method="post">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label">Phone Visibility</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="phone_visible" value="1" id="phone_visible" <?= ($user['phone_visible'] ?? 1) ? 'checked' : '' ?>/>
                    <label class="form-check-label" for="phone_visible">
                        Make phone number visible to students
                    </label>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Email Visibility</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="email_visible" value="1" id="email_visible" <?= ($user['email_visible'] ?? 0) ? 'checked' : '' ?>/>
                    <label class="form-check-label" for="email_visible">
                        Make email address visible to students
                    </label>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Preferred Contact Method</label>
                <select class="form-control" name="preferred_contact_method">
                    <option value="phone" <?= ($user['preferred_contact_method'] ?? 'phone') === 'phone' ? 'selected' : '' ?>>Phone</option>
                    <option value="whatsapp" <?= ($user['preferred_contact_method'] ?? '') === 'whatsapp' ? 'selected' : '' ?>>WhatsApp</option>
                    <option value="email" <?= ($user['preferred_contact_method'] ?? '') === 'email' ? 'selected' : '' ?>>Email</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Best Call Time</label>
                <select class="form-control" name="best_call_time">
                    <option value="">Select preferred time</option>
                    <option value="Morning (8AM-12PM)" <?= ($user['best_call_time'] ?? '') === 'Morning (8AM-12PM)' ? 'selected' : '' ?>>Morning (8AM-12PM)</option>
                    <option value="Afternoon (12PM-5PM)" <?= ($user['best_call_time'] ?? '') === 'Afternoon (12PM-5PM)' ? 'selected' : '' ?>>Afternoon (12PM-5PM)</option>
                    <option value="Evening (5PM-9PM)" <?= ($user['best_call_time'] ?? '') === 'Evening (5PM-9PM)' ? 'selected' : '' ?>>Evening (5PM-9PM)</option>
                    <option value="Flexible" <?= ($user['best_call_time'] ?? '') === 'Flexible' ? 'selected' : '' ?>>Flexible</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Notification Preferences</label>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="email_notifications" value="1" id="email_notifications" checked/>
                    <label class="form-check-label" for="email_notifications">
                        Email notifications for new inquiries
                    </label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="sms_notifications" value="1" id="sms_notifications"/>
                    <label class="form-check-label" for="sms_notifications">
                        SMS notifications for urgent messages
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="marketing_emails" value="1" id="marketing_emails" checked/>
                    <label class="form-check-label" for="marketing_emails">
                        Receive marketing emails and updates
                    </label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Save Preferences</button>
        </form>
    </div>

    <div class="info-card tab-content" id="security" style="display:none;">
        <h5 class="card-title">Change Password</h5>
        <form action="<?= site_url('trainer/changePassword') ?>" method="post">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label">Current Password</label>
                <input type="password" class="form-control" name="current_password" required placeholder="Enter your current password">
            </div>
            <div class="mb-3">
                <label class="form-label">New Password</label>
                <input type="password" class="form-control" name="new_password" required placeholder="Enter your new password" minlength="8">
                <div class="form-text">Password must be at least 8 characters long.</div>
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm New Password</label>
                <input type="password" class="form-control" name="confirm_password" required placeholder="Confirm your new password">
            </div>
            <button type="submit" class="btn btn-primary w-100">Change Password</button>
        </form>

        <hr class="my-4">

        <h6 class="text-muted mb-3">Password Requirements</h6>
        <ul class="small text-muted">
            <li>At least 8 characters long</li>
            <li>Contains at least one uppercase letter</li>
            <li>Contains at least one lowercase letter</li>
            <li>Contains at least one number</li>
        </ul>
    </div>

    <script>
        // Simple tab switching
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.style.display = 'none');

                btn.classList.add('active');
                document.getElementById(btn.dataset.tab).style.display = 'block';
            });
        });

        // Profile picture preview
        document.getElementById('profilePictureInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    let img = document.querySelector('.profile-picture img');
                    if (!img) {
                        img = document.createElement('img');
                        document.querySelector('.profile-picture').insertBefore(img, document.querySelector('.camera-btn'));
                        document.querySelector('.avatar-placeholder')?.remove();
                    }
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        // Curriculum toggle functionality
        document.querySelectorAll('.curriculum-toggle').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const curriculumKey = this.value;
                const contentDiv = document.getElementById('content_' + curriculumKey);
                const curriculumSection = this.closest('.curriculum-section');

                if (this.checked) {
                    // Show curriculum content
                    contentDiv.style.display = 'block';
                    curriculumSection.style.background = '#e8f4fd';
                    curriculumSection.style.borderColor = '#007bff';
                } else {
                    // Hide curriculum content and uncheck all levels and subjects
                    contentDiv.style.display = 'none';
                    curriculumSection.style.background = '#fafbfc';
                    curriculumSection.style.borderColor = '#e0e0e0';

                    // Uncheck all levels and subjects in this curriculum
                    curriculumSection.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                        if (cb !== this) cb.checked = false;
                    });

                    // Hide all subject grids
                    curriculumSection.querySelectorAll('.subjects-grid').forEach(grid => {
                        grid.style.display = 'none';
                    });
                }
            });
        });

        // Level toggle functionality
        document.querySelectorAll('.level-toggle').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const levelSection = this.closest('.level-section');
                const subjectsGrid = levelSection.querySelector('.subjects-grid');

                if (this.checked) {
                    // Show subjects grid
                    subjectsGrid.style.display = 'grid';
                    levelSection.style.background = '#f0f8ff';
                } else {
                    // Hide subjects grid and uncheck all subjects in this level
                    subjectsGrid.style.display = 'none';
                    levelSection.style.background = 'white';

                    // Uncheck all subjects in this level
                    subjectsGrid.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                        cb.checked = false;
                    });
                }
            });
        });

        // Employment status toggle
        document.getElementById('is_employed').addEventListener('change', function() {
            const schoolField = document.querySelector('.school-name-field');
            schoolField.style.display = this.checked ? 'block' : 'none';
        });

        // Bio video delete functionality
        window.deleteBioVideo = async function() {
            if (!confirm('Are you sure you want to delete your bio video? This action cannot be undone.')) {
                return;
            }

            try {
                const response = await fetch('<?= site_url('trainer/deleteBioVideo') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: '<?= csrf_token() ?>=<?= csrf_hash() ?>'
                });

                const result = await response.json();

                if (result.success) {
                    // Hide video and show placeholder
                    document.getElementById('bioVideoPreview').style.display = 'none';
                    document.querySelector('.video-placeholder').style.display = 'flex';

                    // Remove delete button
                    const deleteBtn = document.querySelector('.video-preview .btn-outline-danger');
                    if (deleteBtn) {
                        deleteBtn.remove();
                    }

                    alert('Bio video deleted successfully!');
                } else {
                    alert('Failed to delete bio video. Please try again.');
                }
            } catch (error) {
                console.error('Error deleting bio video:', error);
                alert('An error occurred while deleting the video. Please try again.');
            }
        };

        // Bio video upload functionality
        document.getElementById('bioVideoInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file type
                const allowedTypes = ['video/mp4', 'video/mov', 'video/avi', 'video/webm'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Please select a valid video file (MP4, MOV, AVI, or WebM).');
                    return;
                }

                // Validate file size (50MB)
                if (file.size > 50 * 1024 * 1024) {
                    alert('Video file size must be less than 50MB.');
                    return;
                }

                // Show loading state
                const uploadBtn = document.querySelector('.video-upload-btn button');
                const originalText = uploadBtn.innerHTML;
                uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Uploading...';
                uploadBtn.disabled = true;

                // Create FormData
                const formData = new FormData();
                formData.append('bio_video', file);
                formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

                // Upload video
                fetch('<?= site_url('trainer/uploadBioVideo') ?>', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update video preview
                        const videoPreview = document.querySelector('.video-preview');
                        videoPreview.innerHTML = `
                            <video controls style="width: 100%; max-width: 300px;">
                                <source src="${data.video_url}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                            <div class="mt-2">
                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="deleteBioVideo()">
                                    <i class="fas fa-trash me-2"></i>Delete Video
                                </button>
                            </div>
                        `;

                        alert('Bio video uploaded successfully!');
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Upload error:', error);
                    alert('An error occurred while uploading the video. Please try again.');
                })
                .finally(() => {
                    // Reset button state
                    uploadBtn.innerHTML = originalText;
                    uploadBtn.disabled = false;
                });
            }
        });

        // Delete bio video function
        function deleteBioVideo() {
            if (!confirm('Are you sure you want to delete your bio video? This action cannot be undone.')) {
                return;
            }

            // Show loading state
            const deleteBtn = event.target;
            const originalText = deleteBtn.innerHTML;
            deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Deleting...';
            deleteBtn.disabled = true;

            // Create FormData with CSRF token
            const formData = new FormData();
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

            fetch('<?= site_url('trainer/deleteBioVideo') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update video preview to show placeholder
                    const videoPreview = document.querySelector('.video-preview');
                    videoPreview.innerHTML = `
                        <div class="video-placeholder">
                            <div class="text-center">
                                <i class="fas fa-video fa-2x mb-2"></i>
                                <p class="mb-0">No bio video uploaded</p>
                            </div>
                        </div>
                    `;

                    alert('Bio video deleted successfully!');
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Delete error:', error);
                alert('An error occurred while deleting the video. Please try again.');
            })
            .finally(() => {
                // Reset button state
                deleteBtn.innerHTML = originalText;
                deleteBtn.disabled = false;
            });
        }

        // Profile photo upload functionality (existing code enhanced)
        let profilePhotoFormData = null;

        document.getElementById('profilePictureInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    let img = document.querySelector('.profile-picture img');
                    if (!img) {
                        img = document.createElement('img');
                        document.querySelector('.profile-picture').insertBefore(img, document.querySelector('.camera-btn'));
                        document.querySelector('.avatar-placeholder')?.remove();
                    }
                    img.src = e.target.result;

                    // Prepare for upload
                    profilePhotoFormData = new FormData();
                    profilePhotoFormData.append('profile_photo', file);
                    profilePhotoFormData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

                    // Auto-upload after preview
                    uploadProfilePhoto();
                };
                reader.readAsDataURL(file);
            }
        });

        function uploadProfilePhoto() {
            if (!profilePhotoFormData) return;

            fetch('<?= site_url('trainer/uploadProfilePhoto') ?>', {
                method: 'POST',
                body: profilePhotoFormData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update with server URL
                    const img = document.querySelector('.profile-picture img');
                    if (img) {
                        img.src = data.photo_url;
                    }
                    console.log('Profile photo uploaded successfully');
                } else {
                    alert('Error uploading profile photo: ' + data.message);
                    // Revert to original state (you might want to store original src)
                }
            })
            .catch(error => {
                console.error('Upload error:', error);
                alert('An error occurred while uploading the profile photo.');
            });
        }
    </script>
<?= $this->endSection() ?>
