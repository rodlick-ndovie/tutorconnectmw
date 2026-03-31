<?= $this->extend('layouts/admin') ?>

<?php $active_page = 'trainers'; ?>
<?php $title = $title ?? 'Tutor Details - TutorConnect Malawi'; ?>

<?= $this->section('content') ?>

<!-- Header -->
<div class="header-bar">
    <div>
        <h1 class="page-title">Tutor Details</h1>
        <p class="page-subtitle"><?php echo esc($tutor['user']['first_name'] . ' ' . $tutor['user']['last_name']); ?> - <?php echo esc($tutor['district']); ?></p>
    </div>
    <div style="display: flex; gap: 12px;">
        <a href="<?php echo base_url('admin/trainers'); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Tutors
        </a>

        <?php if ($tutor['status'] == 'pending'): ?>
            <button class="btn btn-outline-success btn-sm" onclick="approveTutor(<?php echo $tutor['id']; ?>)">
                <i class="fas fa-check"></i> Approve
            </button>
            <button class="btn btn-outline-danger btn-sm" onclick="rejectTutor(<?php echo $tutor['id']; ?>)">
                <i class="fas fa-times"></i> Reject
            </button>
        <?php endif; ?>

        <!-- Suspend/Activate buttons for approved tutors -->
        <?php if ($tutor['status'] == 'approved' || $tutor['status'] == 'active'): ?>
            <?php if ($tutor['user']['is_active'] == 1): ?>
                <button class="btn btn-outline-warning btn-sm" onclick="suspendTutor(<?php echo $tutor['id']; ?>)">
                    <i class="fas fa-ban"></i> Suspend
                </button>
            <?php else: ?>
                <button class="btn btn-outline-success btn-sm" onclick="activateTutor(<?php echo $tutor['id']; ?>)">
                    <i class="fas fa-check"></i> Activate
                </button>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Delete button for all tutors -->
        <button class="btn btn-outline-dark btn-sm" onclick="deleteTutor(<?php echo $tutor['id']; ?>, '<?php echo esc($tutor['user']['first_name'] . ' ' . $tutor['user']['last_name']); ?>')">
            <i class="fas fa-trash"></i> Delete
        </button>
    </div>
</div>

<!-- Quick Summary Overview -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 12px; margin-bottom: 24px;">
    <div style="background: white; border-radius: 8px; padding: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="font-size: 12px; color: #666; margin-bottom: 4px;">Status</div>
        <div style="font-size: 16px; font-weight: 600; color: <?php echo $tutor['status'] == 'approved' ? '#10b981' : ($tutor['status'] == 'pending' ? '#f59e0b' : '#ef4444'); ?>;">
            <?php echo ucfirst($tutor['status']); ?>
        </div>
    </div>
    <div style="background: white; border-radius: 8px; padding: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="font-size: 12px; color: #666; margin-bottom: 4px;">Rating</div>
        <div style="font-size: 16px; font-weight: 600; color: #f59e0b;">
            ⭐ <?php echo number_format($tutor['rating'] ?? 0, 1); ?> (<?php echo number_format($tutor['review_count'] ?? 0); ?> reviews)
        </div>
    </div>
    <div style="background: white; border-radius: 8px; padding: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="font-size: 12px; color: #666; margin-bottom: 4px;">Experience</div>
        <div style="font-size: 16px; font-weight: 600; color: #3b82f6;">
            <?php echo number_format($tutor['experience_years'] ?? 0, 1); ?> years
        </div>
    </div>
    <div style="background: white; border-radius: 8px; padding: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="font-size: 12px; color: #666; margin-bottom: 4px;">Views</div>
        <div style="font-size: 16px; font-weight: 600; color: #8b5cf6;">
            <?php echo number_format($tutor['search_count'] ?? 0); ?>
        </div>
    </div>
    <div style="background: white; border-radius: 8px; padding: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="font-size: 12px; color: #666; margin-bottom: 4px;">Mode</div>
        <div style="font-size: 16px; font-weight: 600; color: #06b6d4;">
            <?php echo esc($tutor['teaching_mode']); ?>
        </div>
    </div>
    <div style="background: white; border-radius: 8px; padding: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="font-size: 12px; color: #666; margin-bottom: 4px;">Joined</div>
        <div style="font-size: 14px; font-weight: 600; color: #666;">
            <?php echo date('M j, Y', strtotime($tutor['created_at'])); ?>
        </div>
    </div>
</div>

<!-- User Table Data Summary -->
<div style="background: white; border-radius: 8px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 24px;">
    <h5 style="margin-bottom: 16px; font-weight: 600;">Contact & Professional Details</h5>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px; font-size: 13px;">
        <!-- Email -->
        <div style="border: 1px solid #e5e7eb; border-radius: 6px; padding: 12px;">
            <div style="color: #666; font-weight: 600; margin-bottom: 4px;">Email</div>
            <div style="color: #333;"><?php echo esc($tutor['user']['email']); ?></div>
        </div>
        <!-- Phone -->
        <div style="border: 1px solid #e5e7eb; border-radius: 6px; padding: 12px;">
            <div style="color: #666; font-weight: 600; margin-bottom: 4px;">Phone</div>
            <div style="color: #333;"><?php echo esc($tutor['user']['phone'] ?? 'Not provided'); ?></div>
        </div>
        <!-- WhatsApp -->
        <div style="border: 1px solid #e5e7eb; border-radius: 6px; padding: 12px;">
            <div style="color: #666; font-weight: 600; margin-bottom: 4px;">WhatsApp</div>
            <div style="color: #333;"><?php echo esc($tutor['whatsapp_number'] ?? 'Not provided'); ?></div>
        </div>
        <!-- Employment Status -->
        <div style="border: 1px solid #e5e7eb; border-radius: 6px; padding: 12px;">
            <div style="color: #666; font-weight: 600; margin-bottom: 4px;">Employment Status</div>
            <div style="color: #333;">
                <?php
                    if (($tutor['is_employed'] ?? 0) == 1) {
                        echo 'Employed at ' . esc($tutor['school_name'] ?? 'Unknown Institution');
                    } else {
                        echo 'Not Employed';
                    }
                ?>
            </div>
        </div>
        <!-- Preferred Contact -->
        <div style="border: 1px solid #e5e7eb; border-radius: 6px; padding: 12px;">
            <div style="color: #666; font-weight: 600; margin-bottom: 4px;">Preferred Contact</div>
            <div style="color: #333;"><?php echo ucfirst(esc($tutor['preferred_contact_method'] ?? 'phone')); ?></div>
        </div>
        <!-- Best Call Time -->
        <div style="border: 1px solid #e5e7eb; border-radius: 6px; padding: 12px;">
            <div style="color: #666; font-weight: 600; margin-bottom: 4px;">Best Call Time</div>
            <div style="color: #333;"><?php echo esc($tutor['best_call_time'] ?? 'Anytime'); ?></div>
        </div>
        <!-- Subscription Plan -->
        <div style="border: 1px solid #e5e7eb; border-radius: 6px; padding: 12px;">
            <div style="color: #666; font-weight: 600; margin-bottom: 4px;">Subscription Plan</div>
            <div style="color: #333;"><?php echo esc($tutor['subscription_plan'] ?? 'Basic'); ?></div>
        </div>
    </div>
</div>

<!-- Tutor Profile Card -->
<div class="content-card">
    <div style="display: grid; grid-template-columns: 270px 1fr; gap: 24px; align-items: start;">

        <!-- Profile Picture & Basic Info -->
        <div style="text-align: center;">
            <?php
            // Get profile photo URL
            $profilePhotoUrl = null;
            $profilePhotoFilename = null;
            foreach ($tutor['documents'] ?? [] as $doc) {
                if ($doc['document_type'] === 'profile_photo') {
                    // Clean the file path - remove any leading slashes
                    $file_path = ltrim($doc['file_path'], '/');
                    $profilePhotoUrl = base_url($file_path);
                    $profilePhotoFilename = $doc['original_filename'] ?? 'Profile Photo';
                    break;
                }
            }
            ?>

            <!-- Profile Photo Display in Avatar -->
            <?php if ($profilePhotoUrl): ?>
                <a href="#" data-bs-toggle="modal"
                   data-bs-target="#profilePhotoModal"
                   data-file-path="<?php echo $profilePhotoUrl; ?>"
                   data-filename="<?php echo esc($profilePhotoFilename); ?>"
                   class="profile-photo-link"
                   style="display: inline-block; margin-bottom: 16px;">
                    <img src="<?php echo $profilePhotoUrl; ?>"
                         alt="<?php echo esc($tutor['user']['first_name'] . ' ' . $tutor['user']['last_name']); ?> Profile Photo"
                         style="width: 160px; height: 160px; border-radius: 50%; border: 3px solid var(--primary-color);
                                object-fit: cover; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); cursor: pointer;"
                         onerror="this.onerror=null; this.style.display='none'; this.parentElement.innerHTML='<div style=\'width:160px;height:160px;border-radius:50%;border:3px solid var(--primary-color);background:#f0f0f0;display:flex;align-items:center;justify-content:center;color:#666;font-size:14px;\'>Photo not found</div>';">
                </a>
            <?php else: ?>
                <div style="width: 160px; height: 160px; border-radius: 50%; border: 3px solid var(--primary-color);
                            background: #f0f0f0; display: flex; align-items: center; justify-content: center;
                            margin: 0 auto 16px; color: #666; font-size: 14px;">
                    No Profile Photo Available
                </div>
            <?php endif; ?>

            <h4 style="margin-bottom: 16px;"><?php echo esc($tutor['user']['first_name'] . ' ' . $tutor['user']['last_name']); ?></h4>
        </div>

        <!-- Bio Section -->
        <div>

            <!-- Bio -->
            <?php if (!empty($tutor['bio'])): ?>
            <div style="margin-bottom: 24px;">
                <h5 style="margin-bottom: 12px;">Bio</h5>
                <p style="color: var(--text-light); line-height: 1.5;"><?php echo nl2br(esc($tutor['bio'])); ?></p>
            </div>
            <?php endif; ?>

            <!-- Teaching Curriculum, Levels & Subjects -->
            <?php
            // Get structured subjects data from the database
            $structuredSubjects = json_decode($tutor['user']['structured_subjects'] ?? '[]', true) ?: [];

            // Fallback to old format if structured_subjects is empty
            if (empty($structuredSubjects)) {
                $selectedCurricula = json_decode($tutor['user']['curriculum'] ?? '[]', true) ?: [];
                $selectedSubjects = $tutor['subjects'] ?? [];
                $selectedLevels = $tutor['levels'] ?? [];

                // Try to create basic structure from old data
                $structuredSubjects = [];
                if (!empty($selectedCurricula)) {
                    foreach ($selectedCurricula as $curriculum) {
                        $structuredSubjects[$curriculum] = ['levels' => []];

                        // If we have levels, distribute subjects across them
                        if (!empty($selectedLevels)) {
                            $subjectsPerLevel = ceil(count($selectedSubjects) / count($selectedLevels));
                            $subjectIndex = 0;
                            foreach ($selectedLevels as $level) {
                                $levelSubjects = array_slice($selectedSubjects, $subjectIndex, $subjectsPerLevel);
                                if (!empty($levelSubjects)) {
                                    $structuredSubjects[$curriculum]['levels'][$level] = $levelSubjects;
                                    $subjectIndex += $subjectsPerLevel;
                                }
                            }
                        } elseif (!empty($selectedSubjects)) {
                            // No levels, put all subjects under a generic level
                            $structuredSubjects[$curriculum]['levels']['General'] = $selectedSubjects;
                        }
                    }
                }
            }

            if (!empty($structuredSubjects)): ?>
            <div style="margin-bottom: 24px;">
                <h5 style="margin-bottom: 16px;">Teaching Qualifications</h5>

                <?php
                // Get curriculum display names
                $curriculumNames = [
                    'MANEB' => 'MANEB (Malawi National Curriculum)',
                    'GCSE' => 'GCSE (General Certificate of Secondary Education)',
                    'Cambridge' => 'Cambridge (Cambridge International Curriculum)'
                ];

                $totalCurricula = 0;
                $totalLevels = 0;
                $totalSubjects = 0;
                ?>

                <!-- Structured display with subjects grouped by curriculum and level -->
                <?php foreach ($structuredSubjects as $curriculumKey => $curriculumData): ?>
                <div class="curriculum-section mb-4" style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 1rem; background: #fafbfc;">
                    <div class="curriculum-header mb-3">
                        <h6 style="color: var(--primary); font-weight: 600; margin-bottom: 0.5rem;">
                            <i class="fas fa-book me-2"></i><?= esc($curriculumNames[$curriculumKey] ?? $curriculumKey) ?>
                        </h6>
                    </div>

                    <div class="curriculum-content">
                        <?php
                        // Handle both data structures: with 'levels' key and direct level->subjects mapping
                        $levelsData = isset($curriculumData['levels']) ? $curriculumData['levels'] : $curriculumData;
                        ?>
                        <?php if (!empty($levelsData)): ?>
                            <?php foreach ($levelsData as $levelName => $levelSubjects): ?>
                            <div class="level-section mb-3" style="border-left: 3px solid #007bff; padding-left: 1rem; background: white; padding: 0.75rem; border-radius: 6px;">
                                <div class="level-header mb-2">
                                    <h6 style="color: var(--secondary); font-weight: 600; font-size: 0.9rem; margin-bottom: 0.5rem;">
                                        <i class="fas fa-graduation-cap me-2 text-info"></i><?= esc($levelName) ?>
                                    </h6>
                                </div>

                                <div class="subjects-list">
                                    <?php if (!empty($levelSubjects) && is_array($levelSubjects)): ?>
                                    <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                                        <?php foreach ($levelSubjects as $subject): ?>
                                        <span style="background: #e3f2fd; color: #1976d2; padding: 3px 8px; border-radius: 10px; font-size: 12px; font-weight: 500;">
                                            <i class="fas fa-book me-1"></i><?= esc($subject) ?>
                                        </span>
                                        <?php endforeach; ?>
                                        <?php $totalSubjects += count($levelSubjects); ?>
                                    </div>
                                    <?php else: ?>
                                    <span style="color: var(--text-light); font-style: italic; font-size: 12px;">No subjects specified</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php $totalLevels++; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                        <div style="color: var(--text-light); font-style: italic;">No levels specified for this curriculum</div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php $totalCurricula++; ?>
                <?php endforeach; ?>

                <!-- Summary section -->
                <div style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); border: 1px solid #e9ecef; border-radius: 8px; padding: 1rem;">
                    <h6 style="color: var(--secondary); font-weight: 600; margin-bottom: 0.5rem;">
                        <i class="fas fa-chart-pie me-2"></i>Teaching Summary
                    </h6>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 12px;">
                        <div style="text-align: center; background: white; padding: 8px; border-radius: 6px; border: 1px solid #e0e0e0;">
                            <div style="font-size: 18px; font-weight: bold; color: #667eea;"><?= $totalCurricula ?></div>
                            <div style="font-size: 12px; color: var(--text-light);">Curricula</div>
                        </div>
                        <div style="text-align: center; background: white; padding: 8px; border-radius: 6px; border: 1px solid #e0e0e0;">
                            <div style="font-size: 18px; font-weight: bold; color: #f39c12;"><?= $totalLevels ?></div>
                            <div style="font-size: 12px; color: var(--text-light);">Education Levels</div>
                        </div>
                        <div style="text-align: center; background: white; padding: 8px; border-radius: 6px; border: 1px solid #e0e0e0;">
                            <div style="font-size: 18px; font-weight: bold; color: #27ae60;"><?= $totalSubjects ?></div>
                            <div style="font-size: 12px; color: var(--text-light);">Subjects</div>
                        </div>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div style="margin-bottom: 24px;">
                <h5 style="margin-bottom: 16px;">Teaching Qualifications</h5>
                <div style="color: var(--text-light); font-style: italic;">No teaching qualifications specified</div>
            </div>
            <?php endif; ?>

            <!-- Availability -->
            <?php if (!empty($tutor['availability'])): ?>
            <div style="margin-bottom: 24px;">
                <h5 style="margin-bottom: 12px;">Availability</h5>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 12px;">
                    <?php
                    $availability = $tutor['availability'];
                    if (is_array($availability)) {
                        // Check if it's an array of objects with day_of_week
                        if (isset($availability[0]) && is_array($availability[0]) && isset($availability[0]['day_of_week'])) {
                            // Format: array of {'day_of_week', 'time_slot'}
                            foreach ($availability as $avail): ?>
                            <div style="background: var(--bg-primary); padding: 12px; border-radius: 8px;">
                                <div style="font-weight: 600; color: var(--text-dark);"><?php echo esc($avail['day_of_week']); ?></div>
                                <div style="font-size: 14px; color: var(--text-light);"><?php echo esc($avail['time_slot']); ?></div>
                            </div>
                            <?php endforeach;
                        } else if (isset($availability['days']) && isset($availability['times'])) {
                            // Format: {'days': [...], 'times': [...]}
                            $days = $availability['days'] ?? [];
                            $times = $availability['times'] ?? [];
                            foreach ($days as $day):
                                foreach ($times as $time): ?>
                                <div style="background: var(--bg-primary); padding: 12px; border-radius: 8px;">
                                    <div style="font-weight: 600; color: var(--text-dark);"><?php echo esc($day); ?></div>
                                    <div style="font-size: 14px; color: var(--text-light);"><?php echo esc($time); ?></div>
                                </div>
                                <?php endforeach;
                            endforeach;
                        } else {
                            // Fallback for unknown format
                            echo '<div style="color: var(--text-light); padding: 12px;">Availability data format not recognized</div>';
                        }
                    }
                    ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Introduction Video -->
            <?php if (!empty($tutor['intro_video'])): ?>
            <div style="margin-bottom: 24px;">
                <h5 style="margin-bottom: 12px;">Introduction Video</h5>
                <div style="background: var(--bg-primary); border-radius: 8px; padding: 16px; max-width: 500px;">
                    <video width="100%" height="auto" controls style="border-radius: 8px;">
                        <source src="<?php echo base_url($tutor['intro_video']); ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
            <?php endif; ?>

            <!-- Cover Photo -->
            <?php if (!empty($tutor['cover_photo'])): ?>
            <div style="margin-bottom: 24px;">
                <h5 style="margin-bottom: 12px;">Cover Photo</h5>
                <div style="background: var(--bg-primary); border-radius: 8px; padding: 16px; max-width: 500px;">
                    <img src="<?php echo base_url($tutor['cover_photo']); ?>"
                         alt="Cover Photo"
                         style="width: 100%; height: 250px; border-radius: 8px; object-fit: cover;"
                         onerror="this.style.display='none';">
                </div>
            </div>
            <?php endif; ?>
            <!-- Documents -->
            <?php if (!empty($tutor['documents'])): ?>
            <div style="margin-bottom: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                    <h5 style="margin-bottom: 0;">Documents & Certifications</h5>
                    <button class="btn btn-outline-warning btn-sm" onclick="requestDocumentResubmission(<?php echo $tutor['id']; ?>)">
                        <i class="fas fa-upload me-1"></i>Request Resubmission
                    </button>
                </div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 12px;">
                    <?php
                    // Define professional document names
                    $documentNames = [
                        'profile_photo' => 'Profile Photo',
                        'national_id' => 'National ID Card',
                        'academic_certificates' => 'Academic Certificate',
                        'teaching_qualification' => 'Teaching Qualification',
                        'certificate' => 'Academic Certificate',
                        'cv' => 'Curriculum Vitae',
                        'recommendation' => 'Recommendation Letter',
                        'degree' => 'Degree Certificate',
                        'diploma' => 'Diploma Certificate',
                        'license' => 'Teaching License',
                        'police_clearance' => 'Police Clearance',
                        'other' => 'Additional Document'
                    ];

                    foreach ($tutor['documents'] as $doc):
                        // Skip profile_photo since it's already displayed prominently in the avatar section
                        if ($doc['document_type'] === 'profile_photo') {
                            continue;
                        }

                        // Get professional document name or create one from document_type
                        $docType = $doc['document_type'];
                        $docName = $documentNames[$docType] ?? ucwords(str_replace('_', ' ', $docType));

                        // Clean the file path - remove any leading slashes
                        $file_path = ltrim($doc['file_path'], '/');
                        $fileUrl = base_url($file_path);
                    ?>
                    <div style="background: white; padding: 14px; border-radius: 8px; border: 1px solid #e5e7eb; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                        <div style="font-weight: 600; color: var(--text-dark); margin-bottom: 10px; font-size: 14px;">
                            <i class="fas fa-file-pdf me-2" style="color: #ef4444;"></i><?php echo esc($docName); ?>
                        </div>
                        <a href="#" data-bs-toggle="modal"
                           data-bs-target="#documentModal"
                           data-file-path="<?php echo $fileUrl; ?>"
                           data-filename="<?php echo esc($docName); ?>"
                           class="document-link"
                           style="color: #3b82f6; text-decoration: none; font-weight: 500; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
                           <i class="fas fa-eye"></i>View Document
                        </a>
                        <?php if (!empty($doc['uploaded_at'])): ?>
                        <div style="font-size: 12px; color: #999; margin-top: 8px;">
                            📅 <?php echo date('M j, Y', strtotime($doc['uploaded_at'])); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

        </div>

    </div>
</div>

<style>
.btn-sm {
    padding: 8px 16px;
    font-size: 14px;
}

.profile-photo-link:hover img {
    transform: scale(1.05);
    transition: transform 0.3s ease;
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
}
</style>

<script>
function approveTutor(tutorId) {
    if (confirm('Are you sure you want to approve this tutor? They will be able to access the platform.')) {
        // Create a form and submit it (more reliable than fetch)
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `<?= base_url('admin/trainers/approve') ?>/${tutorId}`;
        form.style.display = 'none';

        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '<?= csrf_token() ?>';
        csrfInput.value = '<?= csrf_hash() ?>';
        form.appendChild(csrfInput);

        document.body.appendChild(form);
        form.submit();
    }
}

function suspendTutor(tutorId) {
    if (confirm('Are you sure you want to suspend this tutor? They will lose access to the platform temporarily.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `<?= base_url('admin/trainers/suspend') ?>/${tutorId}`;
        form.style.display = 'none';

        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '<?= csrf_token() ?>';
        csrfInput.value = '<?= csrf_hash() ?>';
        form.appendChild(csrfInput);

        document.body.appendChild(form);
        form.submit();
    }
}

function activateTutor(tutorId) {
    if (confirm('Are you sure you want to activate this tutor? They will regain access to the platform.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `<?= base_url('admin/trainers/activate') ?>/${tutorId}`;
        form.style.display = 'none';

        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '<?= csrf_token() ?>';
        csrfInput.value = '<?= csrf_hash() ?>';
        form.appendChild(csrfInput);

        document.body.appendChild(form);
        form.submit();
    }
}

function deleteTutor(tutorId, tutorName) {
    if (confirm(`Are you sure you want to permanently delete ${tutorName}? This action cannot be undone and will remove all associated data including videos and subscriptions.`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `<?= base_url('admin/trainers/delete') ?>/${tutorId}`;
        form.style.display = 'none';

        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '<?= csrf_token() ?>';
        csrfInput.value = '<?= csrf_hash() ?>';
        form.appendChild(csrfInput);

        document.body.appendChild(form);
        form.submit();
    }
}

function rejectTutor(tutorId) {
    const reason = prompt('Please provide a reason for rejection (optional):', 'Application not approved');
    if (reason !== null) {
        const formData = new FormData();
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
        formData.append('reason', reason);

        fetch(`<?= base_url('admin/trainers/reject') ?>/${tutorId}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Tutor application rejected.');
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Failed to reject tutor'));
            }
        })
        .catch(error => {
            console.error('Rejection error:', error);
            alert('Network error occurred. Please check your connection and try again.');
        });
    }
}

// Handle document modal display
document.addEventListener('DOMContentLoaded', function() {
    const documentLinks = document.querySelectorAll('.document-link, .profile-photo-link');

    documentLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const filePath = this.getAttribute('data-file-path');
            const filename = this.getAttribute('data-filename');
            const modalId = this.getAttribute('data-bs-target').replace('#', '');

            // Update modal title
            document.getElementById(modalId + 'Label').textContent = filename;

            const isImage = /\.(png|jpg|jpeg|gif|webp|svg|bmp)$/i.test(filePath);
            let modalBodyContent;

            if (isImage) {
                modalBodyContent = `
                    <div style="text-align: center; padding: 20px;">
                        <img src="${filePath}" alt="${filename}"
                             style="max-width: 100%; max-height: 80vh; object-fit: contain;"
                             onerror="this.onerror=null; this.src='<?= base_url('assets/images/no-image.jpg') ?>'; this.alt='Image not found';">
                    </div>
                    <div style="text-align: center; margin-top: 10px;">
                        <a href="${filePath}" target="_blank" class="btn btn-sm btn-primary">
                            <i class="fas fa-external-link-alt me-1"></i>Open in New Tab
                        </a>
                        <a href="${filePath}" download class="btn btn-sm btn-secondary">
                            <i class="fas fa-download me-1"></i>Download
                        </a>
                    </div>
                `;
            } else {
                modalBodyContent = `
                    <div style="height: 600px;">
                        <iframe src="${filePath}"
                                style="width: 100%; height: 100%; border: none;"
                                title="${filename}"
                                onerror="this.onerror=null; this.contentDocument.body.innerHTML='<div style=\\\"padding:20px;text-align:center;\\\"><h4>Unable to load document</h4><p>The document could not be displayed.</p><a href=\\\"${filePath}\\\" download class=\\\"btn btn-primary\\\">Download Instead</a></div>';">
                        </iframe>
                    </div>
                    <div style="text-align: center; margin-top: 10px;">
                        <a href="${filePath}" target="_blank" class="btn btn-sm btn-primary">
                            <i class="fas fa-external-link-alt me-1"></i>Open in New Tab
                        </a>
                        <a href="${filePath}" download class="btn btn-sm btn-secondary">
                            <i class="fas fa-download me-1"></i>Download
                        </a>
                    </div>
                `;
            }

            document.getElementById(modalId + 'Body').innerHTML = modalBodyContent;
        });
    });
});

// Debug function to check image URLs
function requestDocumentResubmission(tutorId) {
    console.log('Opening document resubmission modal for tutor:', tutorId);

    // Check if Bootstrap is available
    if (typeof bootstrap === 'undefined') {
        alert('Bootstrap is not loaded. Please refresh the page.');
        return;
    }

    // Create modal content
    let modalContent = `
        <div class="modal fade" id="resubmissionModal" tabindex="-1" role="dialog" aria-labelledby="resubmissionModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="resubmissionModalLabel">Request Document Resubmission</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Select the documents that need to be resubmitted by the tutor:</p>
                        <form id="resubmissionForm">
                            <div class="mb-3">
                                <?php
                                $documentNames = [
                                    'intro_video' => 'Introduction Video',
                                    'profile_picture' => 'Profile Photo',
                                    'profile_photo' => 'Profile Photo',
                                    'cover_photo' => 'Cover Photo',
                                    'national_id' => 'National ID Card',
                                    'academic_certificates' => 'Academic Certificates',
                                    'teaching_qualification' => 'Teaching Qualification',
                                    'police_clearance' => 'Police Clearance'
                                ];

                                // Always show intro video, profile picture, and cover photo options
                                $alwaysShowDocs = ['intro_video', 'profile_picture', 'profile_photo', 'cover_photo'];
                                $shownDocs = []; // Track what we've already shown

                                // Show special docs first
                                foreach (['intro_video', 'profile_picture', 'cover_photo'] as $docType) {
                                    $docName = $documentNames[$docType];
                                    $shownDocs[] = $docType;
                                ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="documents[]" value="<?php echo esc($docType); ?>" id="doc_<?php echo esc($docType); ?>">
                                    <label class="form-check-label" for="doc_<?php echo esc($docType); ?>">
                                        <?php echo esc($docName); ?>
                                    </label>
                                </div>
                                <?php } ?>

                                <?php
                                // Show regular documents (skip special docs to avoid duplicates)
                                if (!empty($tutor['documents'])) {
                                    foreach ($tutor['documents'] as $doc):
                                        $docType = $doc['document_type'];
                                        // Skip if already shown or is a special doc variation
                                        if (in_array($docType, $shownDocs) || in_array($docType, $alwaysShowDocs)) continue;

                                        $docName = $documentNames[$docType] ?? ucwords(str_replace('_', ' ', $docType));
                                        $shownDocs[] = $docType;
                                ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="documents[]" value="<?php echo esc($docType); ?>" id="doc_<?php echo esc($docType); ?>">
                                    <label class="form-check-label" for="doc_<?php echo esc($docType); ?>">
                                        <?php echo esc($docName); ?>
                                    </label>
                                </div>
                                <?php endforeach;
                                } ?>
                            </div>
                            <div class="mb-3">
                                <label for="resubmissionMessage" class="form-label">Message to Tutor:</label>
                                <textarea class="form-control" id="resubmissionMessage" name="message" rows="3" placeholder="Please provide a message explaining what issues were found and what the tutor needs to correct..."></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-warning" id="sendRequestBtn" onclick="submitResubmissionRequest(<?php echo $tutor['id']; ?>)">
                <span class="btn-text">Send Request</span>
                <span class="spinner-border spinner-border-sm d-none ms-2" role="status" aria-hidden="true"></span>
            </button>
        </div>
                </div>
            </div>
        </div>
    `;

    // Create modal element
    const modalContainer = document.createElement('div');
    modalContainer.innerHTML = modalContent;

    // Add to body
    document.body.appendChild(modalContainer.firstElementChild);

    // Get the modal element and initialize Bootstrap modal
    const modalElement = document.getElementById('resubmissionModal');
    const bsModal = new bootstrap.Modal(modalElement);

    // Show the modal
    bsModal.show();

    // Remove modal from DOM when hidden
    modalElement.addEventListener('hidden.bs.modal', function () {
        modalElement.remove();
    });
}

function submitResubmissionRequest(tutorId) {
    const form = document.getElementById('resubmissionForm');
    const formData = new FormData(form);
    const selectedDocs = formData.getAll('documents[]');
    const message = formData.get('message');
    const sendBtn = document.getElementById('sendRequestBtn');
    const btnText = sendBtn.querySelector('.btn-text');
    const spinner = sendBtn.querySelector('.spinner-border');

    if (selectedDocs.length === 0) {
        alert('Please select at least one document that needs resubmission.');
        return;
    }

    if (!message.trim()) {
        alert('Please provide a message explaining the issues.');
        return;
    }

    // Show loading state
    sendBtn.disabled = true;
    btnText.textContent = 'Sending...';
    spinner.classList.remove('d-none');

    // Add CSRF token
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
    formData.append('tutor_id', tutorId);

    fetch(`<?= base_url('admin/trainers/request-resubmission') ?>/${tutorId}`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // Hide loading state
        sendBtn.disabled = false;
        btnText.textContent = 'Send Request';
        spinner.classList.add('d-none');

        if (data.success) {
            alert('Document resubmission request sent successfully!');
            // Close modal and reload page
            bootstrap.Modal.getInstance(document.getElementById('resubmissionModal')).hide();
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to send request'));
        }
    })
    .catch(error => {
        // Hide loading state on error
        sendBtn.disabled = false;
        btnText.textContent = 'Send Request';
        spinner.classList.add('d-none');

        console.error('Request error:', error);
        alert('Network error occurred. Please check your connection and try again.');
    });
}

function debugImage(url) {
    console.log('Testing image URL:', url);
    const img = new Image();
    img.onload = function() {
        console.log('Image loaded successfully:', url);
    };
    img.onerror = function() {
        console.log('Image failed to load:', url);
    };
    img.src = url;
}

// Toggle subject display between selected only and all subjects
function toggleSubjectDisplay(mode) {
    const showSelectedBtn = document.getElementById('showSelectedBtn');
    const showAllBtn = document.getElementById('showAllBtn');

    // Update button states
    if (mode === 'selected') {
        showSelectedBtn.classList.add('active', 'btn-primary');
        showSelectedBtn.classList.remove('btn-outline-primary');
        showAllBtn.classList.remove('active', 'btn-primary');
        showAllBtn.classList.add('btn-outline-primary');
    } else {
        showAllBtn.classList.add('active', 'btn-primary');
        showAllBtn.classList.remove('btn-outline-primary');
        showSelectedBtn.classList.remove('active', 'btn-primary');
        showSelectedBtn.classList.add('btn-outline-primary');
    }

    // Store current mode
    window.currentSubjectDisplayMode = mode;

    // Update all subject displays
    const subjectLists = document.querySelectorAll('.subjects-list');
    subjectLists.forEach(list => {
        updateSubjectListDisplay(list, mode);
    });
}

// Update individual subject list display
function updateSubjectListDisplay(subjectList, mode) {
    if (!subjectList) return;

    const levelSection = subjectList.closest('.level-section');
    const levelName = levelSection.querySelector('.level-header h6').textContent.trim();

    // Get curriculum data from PHP (we'll need to pass this differently)
    // For now, we'll use a simpler approach with data attributes

    // This would need to be enhanced with proper data passing from PHP
    // For now, just toggle visibility based on existing selected subjects
    if (mode === 'selected') {
        // Show only selected subjects (current behavior)
        // This is already the default behavior
    } else {
        // Show all subjects for this level
        // This would require fetching all subjects for the level
        // For now, we'll show a message
        if (!subjectList.querySelector('.all-subjects-note')) {
            const note = document.createElement('div');
            note.className = 'all-subjects-note';
            note.innerHTML = '<small style="color: var(--text-light);">All available subjects for this level would be shown here.</small>';
            subjectList.appendChild(note);
        }
    }
}

// Initialize subject display mode
document.addEventListener('DOMContentLoaded', function() {
    // Set default mode
    window.currentSubjectDisplayMode = 'selected';
});
</script>

<!-- Profile Photo Modal -->
<div class="modal fade" id="profilePhotoModal" tabindex="-1" aria-labelledby="profilePhotoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="profilePhotoModalLabel">Profile Photo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="profilePhotoModalBody">
                <!-- Profile photo content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Document Modal -->
<div class="modal fade" id="documentModal" tabindex="-1" aria-labelledby="documentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="documentModalLabel">Document Viewer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="documentModalBody">
                <!-- Document content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
