<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Facebook-Style Hero Section with Cover Photo -->
<div class="relative">
    <!-- Cover Photo -->
    <div class="relative w-full h-64 md:h-80 bg-gradient-to-br from-primary/20 to-red-600/20 overflow-hidden group">
        <?php if (!empty($tutor['cover_photo'])): ?>
            <img src="<?= base_url($tutor['cover_photo']) ?>" alt="Cover Photo" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent"></div>
        <?php else: ?>
            <div class="w-full h-full bg-gradient-to-br from-primary to-red-700"></div>
        <?php endif; ?>

        <!-- Back Button in Cover -->
        <div class="absolute top-4 left-4">
            <a href="<?= site_url('find-tutors') ?>" class="inline-flex items-center px-3 py-2 bg-white/90 hover:bg-white text-gray-900 rounded-lg font-medium text-sm transition shadow-lg">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
        </div>
    </div>

    <!-- Profile Section (Facebook-style, overlapping cover) -->
    <div class="max-w-6xl mx-auto px-4">
        <div class="relative -mt-20 md:-mt-24 mb-8 z-10">
            <div class="flex flex-col md:flex-row md:items-end gap-6">
                <!-- Profile Image -->
                <div class="flex-shrink-0">
                    <div class="w-40 h-40 md:w-48 md:h-48 rounded-2xl overflow-hidden shadow-2xl ring-4 ring-white bg-white">
                        <?php if (!empty($tutor['profile_picture'])): ?>
                            <img src="<?= base_url($tutor['profile_picture']) ?>" alt="Profile" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full bg-gradient-to-br from-primary to-red-600 flex items-center justify-center">
                                <i class="fas fa-user-graduate text-6xl text-white/80"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <!-- Floating Badges -->
                    <div class="flex gap-2 mt-3">
                        <!-- Subscription Badge Level (from subscription plan) -->
                        <?php if (!empty($subscription_plan['badge_level']) && $subscription_plan['badge_level'] !== 'none'): ?>
                            <div class="text-white px-2.5 py-1.5 rounded-full text-xs font-bold shadow-lg flex items-center <?php
                                $badgeColors = [
                                    'beginner' => 'bg-gray-500',
                                    'intermediate' => 'bg-blue-500',
                                    'advanced' => 'bg-purple-500',
                                    'expert' => 'bg-amber-500',
                                    'master' => 'bg-red-500'
                                ];
                                $badgeIcons = [
                                    'beginner' => 'fas fa-seedling',
                                    'intermediate' => 'fas fa-chart-line',
                                    'advanced' => 'fas fa-star',
                                    'expert' => 'fas fa-trophy',
                                    'master' => 'fas fa-crown'
                                ];
                                echo $badgeColors[$subscription_plan['badge_level']] ?? 'bg-blue-500';
                            ?>">
                                <i class="<?php echo $badgeIcons[$subscription_plan['badge_level']] ?? 'fas fa-certificate'; ?> mr-1"></i>
                                <?php
                                    $badgeLabels = [
                                        'beginner' => 'Beginner',
                                        'intermediate' => 'Intermediate',
                                        'advanced' => 'Advanced',
                                        'expert' => 'Expert',
                                        'master' => 'Master'
                                    ];
                                    echo $badgeLabels[$subscription_plan['badge_level']] ?? ucfirst(str_replace('_', ' ', $subscription_plan['badge_level']));
                                ?>
                            </div>
                        <?php endif; ?>

                        <div class="bg-emerald-500 text-white px-2.5 py-1.5 rounded-full text-xs font-bold shadow-lg flex items-center">
                            <i class="fas fa-shield-check mr-1"></i>Verified
                        </div>
                        <div class="bg-amber-400 text-slate-900 px-2.5 py-1.5 rounded-full text-xs font-bold shadow-lg flex items-center">
                            <i class="fas fa-star mr-1 text-xs"></i><?= number_format($tutor['rating'] ?? 0, 1) ?>
                        </div>
                    </div>
                </div>

                <!-- Profile Info -->
                <div class="flex-1 pb-4">
                    <!-- Name and Title -->
                    <div class="mb-6">
                        <h1 class="text-3xl md:text-5xl font-extrabold bg-gradient-to-r from-primary via-red-500 to-red-600 bg-clip-text text-transparent leading-tight mb-3">
                            <?= esc(($tutor['user']['first_name'] ?? 'Unknown') . ' ' . ($tutor['user']['last_name'] ?? 'Teacher')) ?>
                        </h1>
                        <div class="flex flex-col md:flex-row md:items-center gap-1 md:gap-3">
                            <span class="text-base md:text-lg font-bold text-primary">Professional Teacher</span>
                            <span class="hidden md:inline text-gray-300">•</span>
                            <span class="text-base md:text-lg text-gray-600 font-semibold"><?= esc($tutor['experience_years'] ?? 0) ?> Years Experience</span>
                        </div>
                    </div>

                    <!-- Key Stats -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <p class="text-2xl font-bold text-gray-900"><?= intval($tutor['experience_years'] ?? 0) ?>+</p>
                            <p class="text-sm text-gray-600">Years</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900"><?= intval($tutor['review_count'] ?? 0) ?></p>
                            <p class="text-sm text-gray-600">Reviews</p>
                        </div>
                    </div>

                    <!-- Quick Info -->
                    <div class="space-y-2">
                        <div class="flex items-center text-gray-700 text-sm">
                            <i class="fas fa-map-pin text-primary mr-2 w-4"></i>
                            <span><?= esc(($tutor['district'] ?? '') . ((!empty($tutor['district']) && !empty($tutor['location'])) ? ', ' : '') . ($tutor['location'] ?? 'N/A')) ?></span>
                        </div>
                        <div class="flex items-center text-gray-700 text-sm">
                            <i class="fas fa-laptop text-primary mr-2 w-4"></i>
                            <span>
                                <?php
                                    $teachingMode = $tutor['teaching_mode'] ?? 'Online';
                                    $modeDisplay = match(strtolower($teachingMode)) {
                                        'online' => 'Online Teaching',
                                        'in-person', 'physical' => 'In-Person Teaching',
                                        'both', 'both online & physical' => 'Hybrid (Online & In-Person)',
                                        default => $teachingMode
                                    };
                                    echo esc($modeDisplay);
                                ?>
                            </span>
                        </div>
                        <!-- Employment Status Badge -->
                        <div class="flex items-center text-gray-700 text-sm">
                            <i class="fas fa-briefcase text-primary mr-2 w-4"></i>
                            <span>
                                <?php if (!empty($tutor['is_employed']) && $tutor['is_employed'] == 1): ?>
                                    <?php if (!empty($tutor['school_name'])): ?>
                                        Employed at <?= esc($tutor['school_name']) ?>
                                    <?php else: ?>
                                        School-employed teacher
                                    <?php endif; ?>
                                <?php else: ?>
                                    Self-employed teacher
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Contact Buttons -->
                <div class="flex flex-col gap-2 md:pb-4">
                    <!-- Send Message Button -->
                    <button onclick="openMessageModal()" class="contact-btn inline-flex items-center justify-center px-6 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition text-sm font-medium" data-contact-type="message">
                        <i class="fas fa-envelope mr-2"></i>Send Message
                    </button>

                    <!-- WhatsApp - Only show if plan allows it and teacher has WhatsApp -->
                    <?php if (!empty($tutor['whatsapp_number']) && isset($plan_features['show_whatsapp']) && $plan_features['show_whatsapp']): ?>
                    <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $tutor['whatsapp_number']) ?>" target="_blank" class="contact-btn inline-flex items-center justify-center px-6 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition text-sm font-medium" data-contact-type="whatsapp">
                        <i class="fab fa-whatsapp mr-2"></i>WhatsApp
                    </a>
                    <?php endif; ?>

                    <!-- Phone - Always available if tutor has phone -->
                    <?php if (!empty($tutor['phone'])): ?>
                    <a href="tel:<?= preg_replace('/[^0-9]/', '', $tutor['phone']) ?>" class="contact-btn inline-flex items-center justify-center px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition text-sm font-medium" data-contact-type="phone">
                        <i class="fas fa-phone mr-2"></i>Call Now
                    </a>
                    <?php endif; ?>

                    <!-- Email - Always available if tutor has email -->
                    <?php if (!empty($tutor['email'])): ?>
                    <a href="mailto:<?= $tutor['email'] ?>" class="contact-btn inline-flex items-center justify-center px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition text-sm font-medium" data-contact-type="email">
                        <i class="fas fa-envelope mr-2"></i>Email
                    </a>
                    <?php endif; ?>

                    <!-- Share Profile Button -->
                    <button onclick="openShareModal()" class="inline-flex items-center justify-center px-6 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition text-sm font-medium">
                        <i class="fas fa-share-alt mr-2"></i>Share Profile
                    </button>
                </div>
            </div>
        </div>

        <!-- Subject Tags (Limited by subscription plan - no upgrade prompts) -->
        <?php if (!empty($tutor_subjects ?? [])): ?>
        <div class="mb-8">
            <p class="text-sm font-semibold text-gray-900 mb-3">Specializes in:</p>
            <div class="flex flex-wrap gap-2">
                <?php
                $colorClass = ['bg-primary/10 text-primary', 'bg-red-100 text-red-700', 'bg-purple-100 text-purple-700', 'bg-pink-100 text-pink-700', 'bg-emerald-100 text-emerald-700'];
                $colorIndex = 0;

                foreach($tutor_subjects as $subject) {
                    if (is_array($subject)) {
                        $subjectText = $subject['subject_name'] ?? $subject['name'] ?? '';
                    } else {
                        $subjectText = $subject;
                    }

                    if (!empty(trim($subjectText))) {
                        echo '<span class="px-3 py-1.5 rounded-full text-xs font-semibold ' . $colorClass[$colorIndex % count($colorClass)] . '">';
                        echo esc(trim($subjectText));
                        echo '</span>';
                        $colorIndex++;
                    }
                }
                ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Professional Content Sections (No Tabs) -->
<div class="max-w-6xl mx-auto px-4 py-12">
    <div class="space-y-12">

        <!-- About Me Section -->
        <section>
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user text-primary text-lg"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-900">About Me</h2>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-6">
                <!-- Bio Text -->
                <div class="bg-white rounded-xl p-8 border border-gray-100 shadow-sm">
                    <p class="text-gray-700 leading-relaxed mb-6 text-base">
                        <?= nl2br(esc($tutor['bio'] ?? 'Professional teacher dedicated to student success. I specialize in personalized learning experiences tailored to each student\'s unique needs and learning style.')) ?>
                    </p>
                    <div class="pt-6 border-t border-gray-200">
                        <p class="text-sm text-gray-600 italic">
                            <i class="fas fa-quote-left text-primary mr-3"></i>"Every student has the potential to excel. My role is to unlock that potential through patient, engaging, and effective teaching."
                        </p>
                    </div>
                </div>

                <!-- Bio Video - Only show if plan allows video upload AND video exists -->
                <?php if (isset($plan_features['allow_video_upload']) && $plan_features['allow_video_upload'] && !empty($tutor['bio_video'])): ?>
                <div class="bg-white rounded-xl overflow-hidden border border-gray-100 shadow-sm">
                    <div class="relative w-full bg-gray-900" style="padding-bottom: 56.25%;">
                        <?php
                        $bioVideo = $tutor['bio_video'];
                        // Check if it's a YouTube URL
                        if (strpos($bioVideo, 'youtube.com') !== false || strpos($bioVideo, 'youtu.be') !== false) {
                            // Extract video ID from YouTube URL
                            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $bioVideo, $match);
                            $videoId = $match[1] ?? null;
                            if ($videoId) {
                                echo '<iframe class="absolute top-0 left-0 w-full h-full" src="https://www.youtube.com/embed/' . esc($videoId) . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                            }
                        } else {
                            // Local video file
                            echo '<video class="absolute top-0 left-0 w-full h-full" controls><source src="' . base_url($bioVideo) . '" type="video/mp4">Your browser does not support the video tag.</video>';
                        }
                        ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Key Highlights -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-check-circle text-primary text-lg"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900">Personalized</h4>
                    </div>
                    <p class="text-sm text-gray-600">Customized lessons tailored to your learning goals</p>
                </div>
                <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-calendar text-primary text-lg"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900">Flexible</h4>
                    </div>
                    <p class="text-sm text-gray-600">Lessons scheduled at your convenience</p>
                </div>
                <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-star text-primary text-lg"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900">Proven Results</h4>
                    </div>
                    <p class="text-sm text-gray-600">Track record of student success</p>
                </div>
                <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-headset text-primary text-lg"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900">Support</h4>
                    </div>
                    <p class="text-sm text-gray-600">Always here to help you succeed</p>
                </div>
            </div>
        </section>

        <!-- Teaching Expertise Section -->
        <section>
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-book text-primary text-lg"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-900">Teaching Expertise</h2>
            </div>
            <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                <?php
                // Get structured subjects data from the database
                $structuredSubjectsRaw = $tutor['user']['structured_subjects'] ?? $tutor['structured_subjects'] ?? '[]';
                $structuredSubjects = is_string($structuredSubjectsRaw) ? json_decode($structuredSubjectsRaw, true) : $structuredSubjectsRaw;
                $structuredSubjects = is_array($structuredSubjects) ? $structuredSubjects : [];

                // Curriculum names mapping
                $curriculumNames = [
                    'MANEB' => 'MANEB (Malawi National Curriculum)',
                    'GCSE' => 'GCSE (General Certificate of Secondary Education)',
                    'Cambridge' => 'Cambridge (Cambridge International Curriculum)'
                ];

                // Display structured subjects or fallback
                if (!empty($structuredSubjects)) {
                    echo '<div class="space-y-8">';
                    foreach ($structuredSubjects as $curriculumKey => $curriculumData) {
                        echo '<div>';
                        echo '<h4 class="text-lg font-semibold text-gray-900 mb-4">' . esc($curriculumNames[$curriculumKey] ?? $curriculumKey) . '</h4>';

                        echo '<div class="space-y-4">';
                        // Handle both data structures: with 'levels' key and direct level->subjects mapping
                        $levelsData = isset($curriculumData['levels']) ? $curriculumData['levels'] : $curriculumData;

                        if (!empty($levelsData)) {
                            foreach ($levelsData as $levelName => $levelSubjects) {
                                echo '<div>';
                                echo '<h5 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-2">' . esc($levelName) . '</h5>';
                                echo '<div class="flex flex-wrap gap-2">';

                                if (!empty($levelSubjects) && is_array($levelSubjects)) {
                                    foreach ($levelSubjects as $subject) {
                                        echo '<span class="px-3 py-1.5 text-sm text-gray-700 bg-gray-100 rounded">' . esc($subject) . '</span>';
                                    }
                                } else {
                                    echo '<span class="text-gray-500 italic text-sm">No subjects specified</span>';
                                }

                                echo '</div>';
                                echo '</div>';
                            }
                        } else {
                            echo '<p class="text-gray-500 text-sm">No education levels specified for this curriculum</p>';
                        }

                        echo '</div>';
                        echo '</div>';
                    }
                    echo '</div>';
                } else {
                    echo '<div class="text-center py-8">';
                    echo '<div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">';
                    echo '<i class="fas fa-book text-3xl text-gray-400"></i>';
                    echo '</div>';
                    echo '<p class="text-gray-500">Teaching subjects not configured yet</p>';
                    echo '</div>';
                }
                ?>
            </div>
        </section>

        <!-- Qualifications Section -->
        <section>
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-certificate text-primary text-lg"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-900">Qualifications & Credentials</h2>
            </div>
            <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="border border-gray-200 rounded-lg p-6 hover:border-primary/30 hover:shadow-md transition">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-certificate text-gray-700 text-lg"></i>
                            </div>
                            <h4 class="font-semibold text-gray-900">Teaching License</h4>
                        </div>
                        <p class="text-sm text-gray-600">Certified educator with professional teaching qualifications</p>
                    </div>
                    <div class="border border-gray-200 rounded-lg p-6 hover:border-primary/30 hover:shadow-md transition">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-university text-gray-700 text-lg"></i>
                            </div>
                            <h4 class="font-semibold text-gray-900">Subject Expertise</h4>
                        </div>
                        <p class="text-sm text-gray-600">Advanced degree with ongoing professional development</p>
                    </div>
                    <div class="border border-gray-200 rounded-lg p-6 hover:border-primary/30 hover:shadow-md transition">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-users text-gray-700 text-lg"></i>
                            </div>
                            <h4 class="font-semibold text-gray-900">Student Success</h4>
                        </div>
                        <p class="text-sm text-gray-600">Proven track record of helping students achieve excellence</p>
                    </div>
                    <div class="border border-gray-200 rounded-lg p-6 hover:border-primary/30 hover:shadow-md transition">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-award text-gray-700 text-lg"></i>
                            </div>
                            <h4 class="font-semibold text-gray-900">Verified Professional</h4>
                        </div>
                        <p class="text-sm text-gray-600">Background checked and verified by TutorConnect Malawi</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact & Availability Section -->
        <section>
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-primary text-lg"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-900">Contact & Availability</h2>
            </div>
            <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                <!-- Contact Information - Only show if plan allows email or WhatsApp -->
                <?php if ((isset($plan_features['email_marketing_access']) && $plan_features['email_marketing_access']) ||
                          (isset($plan_features['show_whatsapp']) && $plan_features['show_whatsapp']) ||
                          !empty($tutor['phone'])): ?>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-6">Contact Details</h3>
                        <div class="space-y-4">

                            <!-- Phone Number -->
                            <?php if (!empty($tutor['phone'])): ?>
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-phone text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Phone</p>
                                    <p class="font-medium text-gray-900"><?= esc($tutor['phone']) ?></p>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- WhatsApp Number -->
                            <?php if (!empty($tutor['whatsapp_number']) && isset($plan_features['show_whatsapp']) && $plan_features['show_whatsapp']): ?>
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fab fa-whatsapp text-green-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">WhatsApp</p>
                                    <p class="font-medium text-gray-900"><?= esc($tutor['whatsapp_number']) ?></p>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- Email -->
                            <?php if (!empty($tutor['email']) && isset($plan_features['email_marketing_access']) && $plan_features['email_marketing_access']): ?>
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-envelope text-gray-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Email</p>
                                    <p class="font-medium text-gray-900"><?= esc($tutor['email']) ?></p>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- Preferred Contact Method -->
                            <?php if (!empty($tutor['best_call_time'])): ?>
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-clock text-purple-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Best Call Time</p>
                                    <p class="font-medium text-gray-900"><?= esc($tutor['best_call_time']) ?></p>
                                </div>
                            </div>
                            <?php endif; ?>

                        </div>
                    </div>
                <?php endif; ?>

                    <!-- Availability Schedule -->
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-6">Availability Schedule</h3>
                        <?php
                        // Parse availability data in the format: {"days": [...], "times": [...]}
                        $availability = $tutor['availability'] ?? [];
                        if (!is_array($availability)) {
                            $availability = json_decode($availability, true) ?: [];
                        }

                        $availableDays = $availability['days'] ?? [];
                        $availableTimes = $availability['times'] ?? [];
                        ?>

                        <?php if (!empty($availableDays) && !empty($availableTimes)): ?>
                        <div class="space-y-6">
                            <!-- Available Days -->
                            <div>
                                <h4 class="text-lg font-medium text-gray-900 mb-3">Available Days</h4>
                                <div class="flex flex-wrap gap-2">
                                    <?php foreach ($availableDays as $day): ?>
                                    <span class="px-3 py-2 bg-primary/10 text-primary text-sm font-medium rounded-lg">
                                        <?= esc($day) ?>
                                    </span>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Preferred Time Slots -->
                            <div>
                                <h4 class="text-lg font-medium text-gray-900 mb-3">Preferred Time Slots</h4>
                                <div class="grid grid-cols-1 gap-3">
                                    <?php foreach ($availableTimes as $timeSlot): ?>
                                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                        <div class="w-8 h-8 bg-primary/10 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-clock text-primary text-sm"></i>
                                        </div>
                                        <span class="text-gray-900 font-medium"><?= esc($timeSlot) ?></span>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Summary -->
                            <div class="mt-4 p-4 bg-primary/5 rounded-lg border border-primary/10">
                                <p class="text-sm text-gray-700">
                                    <i class="fas fa-info-circle text-primary mr-2"></i>
                                    Available on <strong><?= count($availableDays) ?> days</strong> during <strong><?= count($availableTimes) ?> time slots</strong>
                                </p>
                            </div>
                        </div>
                        <?php elseif (!empty($availableDays) || !empty($availableTimes)): ?>
                        <div class="space-y-4">
                            <!-- Partial availability -->
                            <?php if (!empty($availableDays)): ?>
                            <div>
                                <h4 class="text-lg font-medium text-gray-900 mb-3">Available Days</h4>
                                <div class="flex flex-wrap gap-2">
                                    <?php foreach ($availableDays as $day): ?>
                                    <span class="px-3 py-2 bg-primary/10 text-primary text-sm font-medium rounded-lg">
                                        <?= esc($day) ?>
                                    </span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if (!empty($availableTimes)): ?>
                            <div>
                                <h4 class="text-lg font-medium text-gray-900 mb-3">Preferred Time Slots</h4>
                                <div class="grid grid-cols-1 gap-3">
                                    <?php foreach ($availableTimes as $timeSlot): ?>
                                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                        <div class="w-8 h-8 bg-primary/10 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-clock text-primary text-sm"></i>
                                        </div>
                                        <span class="text-gray-900 font-medium"><?= esc($timeSlot) ?></span>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php else: ?>
                        <div class="text-center py-8">
                            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-calendar-times text-gray-400 text-lg"></i>
                            </div>
                            <p class="text-gray-500 text-sm">No availability schedule set</p>
                        </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </section>

        <!-- Professional Experience Section -->
        <section>
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-briefcase text-primary text-lg"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-900">Professional Experience</h2>
            </div>
            <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                <div class="space-y-6">
                    <div class="flex items-start">
                        <div class="w-4 h-4 bg-primary rounded-full mt-2 mr-4 flex-shrink-0"></div>
                        <div class="flex-1">
                            <h4 class="text-lg font-semibold text-gray-900">Teaching Experience</h4>
                            <p class="text-gray-600 mt-1"><?= $tutor['experience_years'] ?? 0 ?> years of professional teaching experience</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="w-4 h-4 bg-primary rounded-full mt-2 mr-4 flex-shrink-0"></div>
                        <div class="flex-1">
                            <h4 class="text-lg font-semibold text-gray-900">Teaching Mode</h4>
                            <p class="text-gray-600 mt-1">Offers <?php
                                $teachingMode = $tutor['teaching_mode'] ?? 'Online';
                                $modeText = match(strtolower($teachingMode)) {
                                    'online' => 'online',
                                    'in-person', 'physical' => 'in-person',
                                    'both', 'both online & physical' => 'online and in-person',
                                    default => strtolower($teachingMode)
                                };
                                echo esc($modeText);
                            ?> teaching sessions</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="w-4 h-4 bg-primary rounded-full mt-2 mr-4 flex-shrink-0"></div>
                        <div class="flex-1">
                            <h4 class="text-lg font-semibold text-gray-900">Location</h4>
                            <p class="text-gray-600 mt-1">Based in <?= esc($tutor['district'] ?? 'Malawi') ?>, available for teaching sessions</p>
                        </div>
                    </div>

                    <!-- Employment Status -->
                    <div class="flex items-start">
                        <div class="w-4 h-4 bg-primary rounded-full mt-2 mr-4 flex-shrink-0"></div>
                        <div class="flex-1">
                            <h4 class="text-lg font-semibold text-gray-900">Employment Status</h4>
                            <p class="text-gray-600 mt-1">
                                <?php if (!empty($tutor['is_employed']) && $tutor['is_employed'] == 1): ?>
                                    <?php if (!empty($tutor['school_name'])): ?>
                                        Currently employed at <?= esc($tutor['school_name']) ?>
                                    <?php else: ?>
                                        Currently employed (school name not specified)
                                    <?php endif; ?>
                                <?php else: ?>
                                    Self-employed teacher / Not currently employed at a school
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="w-4 h-4 bg-primary rounded-full mt-2 mr-4 flex-shrink-0"></div>
                        <div class="flex-1">
                            <h4 class="text-lg font-semibold text-gray-900">Verified Professional</h4>
                            <p class="text-gray-600 mt-1">Background checked and verified by TutorConnect Malawi</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Student Reviews Section -->
        <section>
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-star text-primary text-lg"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-900">Student Reviews</h2>
            </div>

            <!-- Add Review Button -->
            <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100 mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Share Your Experience</h3>
                        <p class="text-gray-600 mt-2">Help other students make informed decisions</p>
                    </div>
                    <button onclick="openReviewModal()" class="bg-gradient-to-r from-primary to-red-600 text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-star mr-2"></i>Write Review
                    </button>
                </div>
            </div>

            <!-- Reviews List -->
            <div class="space-y-6">
                <?php if (!empty($reviews ?? [])): ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100 hover:shadow-xl transition">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-gradient-to-br from-primary to-red-600 rounded-full flex items-center justify-center text-white font-bold mr-4">
                                        <?= strtoupper(substr($review['reviewer_name'] ?? 'A', 0, 1)) ?>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900"><?= esc($review['reviewer_name'] ?? 'Anonymous') ?></h4>
                                        <div class="flex items-center mt-1">
                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                <i class="fas fa-star text-sm <?= $i <= ($review['rating'] ?? 0) ? 'text-yellow-400' : 'text-gray-300' ?>"></i>
                                            <?php endfor; ?>
                                            <span class="ml-2 text-sm text-gray-600">
                                                <?= date('M j, Y', strtotime($review['created_at'] ?? 'now')) ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php if (!empty($review['comment'])): ?>
                                <p class="text-gray-700 leading-relaxed"><?= esc($review['comment']) ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="bg-white rounded-2xl shadow-lg p-12 border border-gray-100 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-comments text-2xl text-gray-400"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No Reviews Yet</h3>
                        <p class="text-gray-600 mb-6">Be the first to leave a review for this teacher!</p>
                        <button onclick="openReviewModal()" class="bg-gradient-to-r from-primary to-red-600 text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                            <i class="fas fa-star mr-2"></i>Write First Review
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>

<!-- Call To Action Section - Full Width -->
<section class="mt-16 w-screen relative left-1/2 right-1/2 -mx-[50vw]">
    <div class="bg-gradient-to-r from-primary to-red-600 shadow-lg overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-0 items-center">
            <!-- CTA Profile Image -->
            <div class="hidden lg:flex items-center justify-center overflow-hidden relative px-8 py-12">
                <div class="w-80 h-80 rounded-2xl overflow-hidden shadow-2xl">
                    <?php if (!empty($tutor['profile_picture'])): ?>
                        <img src="<?= base_url($tutor['profile_picture']) ?>" alt="Profile" class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="w-full h-full bg-gradient-to-br from-primary to-red-700 flex items-center justify-center">
                            <i class="fas fa-user-graduate text-8xl text-white/80"></i>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- CTA Content -->
            <div class="flex flex-col justify-center text-white p-8 md:p-12">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Ready to Start Learning?</h2>
                <p class="text-base md:text-lg text-red-100 mb-8 leading-relaxed">
                    Connect with <?= esc($tutor['user']['first_name'] ?? 'this teacher') ?> today and begin your learning journey. Achieve your academic goals with personalized, professional teaching.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 mb-8">
                    <?php if (!empty($tutor['whatsapp_number'])): ?>
                    <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $tutor['whatsapp_number']) ?>" target="_blank" class="bg-green-500 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-600 transition transform hover:scale-105 duration-300 flex items-center justify-center">
                        <i class="fab fa-whatsapp mr-2"></i>WhatsApp Chat
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modals -->
<!-- Review Modal -->
<div id="reviewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full max-h-screen overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold text-gray-900">Rate & Review teacher</h3>
                    <button onclick="closeReviewModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <form id="reviewForm" action="<?= base_url('home/submitReview') ?>" method="post" class="space-y-6">
                    <?= csrf_field() ?>
                    <input type="hidden" name="tutor_id" value="<?= $tutor['id'] ?>">

                    <!-- Rating Stars -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Your Rating *</label>
                        <div class="flex items-center space-x-1">
                            <input type="hidden" name="rating" id="ratingInput" value="5">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <button type="button" class="star-btn text-2xl text-gray-300 hover:text-yellow-400 transition-colors" data-rating="<?= $i ?>">
                                    <i class="fas fa-star"></i>
                                </button>
                            <?php endfor; ?>
                            <span class="ml-3 text-sm text-gray-600" id="ratingText">Excellent (5 stars)</span>
                        </div>
                    </div>

                    <!-- Reviewer Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Your Name *</label>
                            <input type="text" name="reviewer_name" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email (optional)</label>
                            <input type="email" name="reviewer_email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                        </div>
                    </div>

                    <!-- Review Comment -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Your Review (Optional)</label>
                        <textarea name="comment" rows="5" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Share your experience with this teacher..."></textarea>
                        <p class="text-xs text-gray-500 mt-1">Please be honest and constructive. Your review helps other students.</p>
                    </div>

                    <!-- Anonymous Option -->
                    <div class="flex items-center">
                        <input type="checkbox" name="is_anonymous" id="is_anonymous" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                        <label for="is_anonymous" class="ml-2 block text-sm text-gray-700">
                            Post this review anonymously
                        </label>
                    </div>

                    <button type="submit" class="w-full bg-primary text-white py-4 px-6 rounded-lg hover:bg-red-600 font-bold text-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-star mr-2"></i>
                        Submit Review
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Message Modal -->
<div id="messageModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full max-h-screen overflow-y-auto shadow-2xl">
            <!-- Header with theme gradient -->
            <div class="bg-gradient-to-r from-primary to-red-600 text-white p-6 rounded-t-2xl">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold">Send Message to teacher</h3>
                        <p class="text-red-100 text-sm mt-1">Connect directly with this educator</p>
                    </div>
                    <button onclick="closeMessageModal()" class="text-white/80 hover:text-white hover:bg-white/20 rounded-full p-2 transition-all">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
            </div>

            <div class="p-6">
                <form id="messageForm" class="space-y-6">
                    <input type="hidden" name="tutor_id" value="<?= $tutor['id'] ?>">
                    <input type="hidden" name="tutor_email" value="<?= $tutor['email'] ?? '' ?>">

                    <!-- Sender Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-2">
                                <i class="fas fa-user text-primary mr-1"></i>
                                Your Name *
                            </label>
                            <input type="text" name="sender_name" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all" placeholder="Enter your full name">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-2">
                                <i class="fas fa-envelope text-primary mr-1"></i>
                                Your Email *
                            </label>
                            <input type="email" name="sender_email" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all" placeholder="your.email@example.com">
                        </div>
                    </div>

                    <!-- Subject -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-800 mb-2">
                            <i class="fas fa-tag text-primary mr-1"></i>
                            Subject *
                        </label>
                        <input type="text" name="subject" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all" placeholder="e.g., Math teaching inquiry">
                    </div>

                    <!-- Message -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-800 mb-2">
                            <i class="fas fa-comment-alt text-primary mr-1"></i>
                            Message *
                        </label>
                        <textarea name="message" rows="5" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all resize-none" placeholder="Tell the teacher about your learning goals and availability..."></textarea>
                        <p class="text-xs text-gray-500 mt-2 flex items-center">
                            <i class="fas fa-info-circle text-primary mr-1"></i>
                            Your message will be sent directly to the teacher's email
                        </p>
                    </div>

                    <!-- Contact Preference -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-800 mb-3">
                            <i class="fas fa-phone text-primary mr-1"></i>
                            Preferred Contact Method *
                        </label>

                        <!-- Dynamic Contact Input - Appears Immediately Below Label -->
                        <div id="contactInputContainer" class="mb-4">
                            <!-- Dynamic contact input will appear here based on selection -->
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <label class="relative">
                                <input type="radio" name="contact_preference" value="phone" required class="sr-only peer">
                                <div class="flex flex-col items-center justify-center p-3 border-2 border-gray-200 rounded-lg hover:border-primary hover:bg-primary/5 peer-checked:border-primary peer-checked:bg-primary/10 transition-all cursor-pointer">
                                    <i class="fas fa-phone text-lg text-gray-600 peer-checked:text-primary mb-1"></i>
                                    <span class="text-xs font-medium text-gray-700 peer-checked:text-primary">Phone</span>
                                </div>
                            </label>
                            <label class="relative">
                                <input type="radio" name="contact_preference" value="whatsapp" required class="sr-only peer">
                                <div class="flex flex-col items-center justify-center p-3 border-2 border-gray-200 rounded-lg hover:border-primary hover:bg-primary/5 peer-checked:border-primary peer-checked:bg-primary/10 transition-all cursor-pointer">
                                    <i class="fab fa-whatsapp text-lg text-gray-600 peer-checked:text-primary mb-1"></i>
                                    <span class="text-xs font-medium text-gray-700 peer-checked:text-primary">WhatsApp</span>
                                </div>
                            </label>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">How would you prefer the teacher to contact you?</p>
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-primary to-red-600 text-white py-4 px-6 rounded-xl hover:from-red-600 hover:to-primary font-bold text-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center justify-center">
                        <i class="fas fa-paper-plane mr-3"></i>
                        Send Message to teacher
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Share Modal -->
<div id="shareModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl max-w-md w-full max-h-screen overflow-y-auto shadow-2xl">
            <!-- Header with theme gradient -->
            <div class="bg-gradient-to-r from-primary to-red-600 text-white p-6 rounded-t-2xl">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold">Share Profile</h3>
                        <p class="text-red-100 text-sm mt-1">Help others discover this teacher</p>
                    </div>
                    <button onclick="closeShareModal()" class="text-white/80 hover:text-white hover:bg-white/20 rounded-full p-2 transition-all">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
            </div>

            <div class="p-6">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-primary to-red-600 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-share-alt text-2xl text-white"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900">Share this teacher's profile</h4>
                    <p class="text-gray-600 text-sm">Help students find the perfect teacher</p>
                </div>

                <!-- Share Options Grid -->
                <div class="grid grid-cols-2 gap-3 mb-6">
                    <!-- Facebook -->
                    <button onclick="shareToFacebook()" class="flex flex-col items-center justify-center p-4 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all transform hover:scale-105">
                        <i class="fab fa-facebook-f text-2xl mb-2"></i>
                        <span class="text-sm font-medium">Facebook</span>
                    </button>

                    <!-- Twitter/X -->
                    <button onclick="shareToTwitter()" class="flex flex-col items-center justify-center p-4 bg-sky-500 text-white rounded-xl hover:bg-sky-600 transition-all transform hover:scale-105">
                        <i class="fab fa-twitter text-2xl mb-2"></i>
                        <span class="text-sm font-medium">Twitter</span>
                    </button>

                    <!-- WhatsApp -->
                    <button onclick="shareToWhatsApp()" class="flex flex-col items-center justify-center p-4 bg-green-500 text-white rounded-xl hover:bg-green-600 transition-all transform hover:scale-105">
                        <i class="fab fa-whatsapp text-2xl mb-2"></i>
                        <span class="text-sm font-medium">WhatsApp</span>
                    </button>

                    <!-- LinkedIn -->
                    <button onclick="shareToLinkedIn()" class="flex flex-col items-center justify-center p-4 bg-blue-700 text-white rounded-xl hover:bg-blue-800 transition-all transform hover:scale-105">
                        <i class="fab fa-linkedin-in text-2xl mb-2"></i>
                        <span class="text-sm font-medium">LinkedIn</span>
                    </button>
                </div>

                <!-- Copy Link Section -->
                <div class="border-t border-gray-200 pt-6">
                    <label class="block text-sm font-semibold text-gray-800 mb-3">
                        <i class="fas fa-link text-primary mr-1"></i>
                        Copy Link
                    </label>
                    <div class="flex gap-2">
                        <input type="text" id="shareLink" readonly
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm bg-gray-50"
                               value="<?= current_url() ?>">
                        <button onclick="copyShareLink()" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition text-sm font-medium">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                    <p id="copyMessage" class="text-xs text-green-600 mt-2 opacity-0 transition-opacity">Link copied to clipboard!</p>
                </div>

                <!-- Close Button -->
                <button onclick="closeShareModal()" class="w-full mt-6 bg-gray-100 text-gray-700 py-3 px-6 rounded-xl hover:bg-gray-200 transition font-medium">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.prose {
    max-width: none;
}

.prose p {
    margin-bottom: 1rem;
}

.prose blockquote {
    font-style: italic;
    border-left: 4px solid #e74c3c;
    padding-left: 1.5rem;
    margin: 2rem 0;
}
</style>

<script>
function openReviewModal() {
    document.getElementById('reviewModal').classList.remove('hidden');
    updateStarRating(5);
}

function closeReviewModal() {
    document.getElementById('reviewModal').classList.add('hidden');
}

function openMessageModal() {
    document.getElementById('messageModal').classList.remove('hidden');
}

function closeMessageModal() {
    document.getElementById('messageModal').classList.add('hidden');
}

function openShareModal() {
    document.getElementById('shareModal').classList.remove('hidden');
}

function closeShareModal() {
    document.getElementById('shareModal').classList.add('hidden');
}

// Share functions
function shareToFacebook() {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent("Check out this teacher on TutorConnect Malawi!");
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}&quote=${title}`, '_blank', 'width=600,height=400');
}

function shareToTwitter() {
    const url = encodeURIComponent(window.location.href);
    const text = encodeURIComponent("Check out this amazing teacher on TutorConnect Malawi! 👨‍🏫📚");
    window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank', 'width=600,height=400');
}

function shareToWhatsApp() {
    const url = encodeURIComponent(window.location.href);
    const text = encodeURIComponent("Check out this teacher on TutorConnect Malawi: ");
    window.open(`https://wa.me/?text=${text}${url}`, '_blank', 'width=600,height=400');
}

function shareToLinkedIn() {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent("teacher Profile - TutorConnect Malawi");
    const summary = encodeURIComponent("Discover this qualified teacher on TutorConnect Malawi - your gateway to quality education!");
    window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${url}&title=${title}&summary=${summary}`, '_blank', 'width=600,height=400');
}

function copyShareLink() {
    const shareLink = document.getElementById('shareLink');
    const copyMessage = document.getElementById('copyMessage');

    shareLink.select();
    shareLink.setSelectionRange(0, 99999); // For mobile devices

    try {
        document.execCommand('copy');
        copyMessage.classList.remove('opacity-0');
        copyMessage.classList.add('opacity-100');

        // Hide message after 3 seconds
        setTimeout(() => {
            copyMessage.classList.remove('opacity-100');
            copyMessage.classList.add('opacity-0');
        }, 3000);
    } catch (err) {
        console.error('Failed to copy link: ', err);
        alert('Failed to copy link. Please copy manually.');
    }
}

// Star rating functionality
function updateStarRating(rating) {
    document.getElementById('ratingInput').value = rating;
    const stars = document.querySelectorAll('.star-btn i');
    const ratingText = document.getElementById('ratingText');

    stars.forEach((star, index) => {
        if (index < rating) {
            star.className = 'fas fa-star text-yellow-400';
        } else {
            star.className = 'fas fa-star text-gray-300';
        }
    });

    const ratingTexts = {
        1: 'Poor (1 star)',
        2: 'Fair (2 stars)',
        3: 'Good (3 stars)',
        4: 'Very Good (4 stars)',
        5: 'Excellent (5 stars)'
    };
    ratingText.textContent = ratingTexts[rating] || 'Excellent (5 stars)';
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.star-btn').forEach((btn, index) => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            updateStarRating(index + 1);
        });

        btn.addEventListener('mouseenter', function() {
            const previewRating = index + 1;
            const stars = document.querySelectorAll('.star-btn i');
            stars.forEach((star, starIndex) => {
                if (starIndex <= index) {
                    star.className = 'fas fa-star text-yellow-400';
                } else {
                    star.className = 'fas fa-star text-gray-300';
                }
            });
        });

        btn.addEventListener('mouseleave', function() {
            const currentRating = parseInt(document.getElementById('ratingInput').value) || 5;
            updateStarRating(currentRating);
        });
    });
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('bg-black')) {
        closeReviewModal();
    }
});

document.getElementById('reviewForm')?.addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;

    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Submitting...';
    submitBtn.disabled = true;

    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAjaxSuccess('Review submitted successfully!');
            setTimeout(() => {
                closeReviewModal();
                location.reload();
            }, 2000);
        } else {
            showAjaxError(data.message || 'Failed to submit review.');
        }
    })
    .catch(error => {
        showAjaxError('An error occurred. Please try again.');
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

// Dynamic contact input based on preference selection
document.addEventListener('DOMContentLoaded', function() {
    const contactRadios = document.querySelectorAll('input[name="contact_preference"]');
    const contactInputContainer = document.getElementById('contactInputContainer');

    function updateContactInput() {
        const selectedValue = document.querySelector('input[name="contact_preference"]:checked')?.value;

        if (!selectedValue) {
            contactInputContainer.innerHTML = '';
            return;
        }

        let inputHtml = '';
        let placeholder = '';
        let inputType = 'text';
        let iconClass = '';

        switch (selectedValue) {
            case 'phone':
                placeholder = '+265 XXX XXX XXX';
                inputType = 'tel';
                iconClass = 'fas fa-phone text-primary mr-1';
                break;
            case 'whatsapp':
                placeholder = '+265 XXX XXX XXX';
                inputType = 'tel';
                iconClass = 'fab fa-whatsapp text-green-600 mr-1';
                break;
        }

        inputHtml = `
            <div>
                <label class="block text-sm font-semibold text-gray-800 mb-2">
                    <i class="${iconClass}"></i>
                    Your ${selectedValue.charAt(0).toUpperCase() + selectedValue.slice(1)} *
                </label>
                <input type="${inputType}" name="contact_detail" required
                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                       placeholder="${placeholder}">
            </div>
        `;

        contactInputContainer.innerHTML = inputHtml;
    }

    // Listen for changes on contact preference radios
    contactRadios.forEach(radio => {
        radio.addEventListener('change', updateContactInput);
    });

    // Initial check in case one is already selected
    updateContactInput();
});

// Form submission validation
document.getElementById('messageForm')?.addEventListener('submit', function(e) {
    const contactPreference = document.querySelector('input[name="contact_preference"]:checked');
    const contactDetail = document.querySelector('input[name="contact_detail"]');

    // Check if contact preference is selected
    if (!contactPreference) {
        e.preventDefault();
        alert('Please select a preferred contact method.');
        return false;
    }

    // Check if contact detail input exists and has value
    if (!contactDetail || !contactDetail.value.trim()) {
        e.preventDefault();
        alert('Please provide your contact details for the selected method.');
        return false;
    }

    // Continue with normal submission
});

// Message form submission
document.getElementById('messageForm')?.addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;

    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Sending...';
    submitBtn.disabled = true;

    // First track the message click
    fetch('<?= base_url('resources/trackContactClick') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'tutor_id=' + encodeURIComponent(<?= $tutor['id'] ?>) + '&contact_type=message'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Message click tracked');
        }
    })
    .catch(error => {
        console.log('Error tracking message click:', error);
    });

    // Then send the message
    fetch('<?= base_url('home/sendMessage') ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAjaxSuccess('Message sent successfully! The teacher will respond to your preferred contact method.');
            setTimeout(() => {
                closeMessageModal();
                // Reset form
                document.getElementById('messageForm').reset();
                // Clear dynamic input
                document.getElementById('contactInputContainer').innerHTML = '';
            }, 2000);
        } else {
            showAjaxError(data.message || 'Failed to send message.');
        }
    })
    .catch(error => {
        showAjaxError('An error occurred. Please try again.');
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

function showAjaxSuccess(message) {
    const successDiv = document.createElement('div');
    successDiv.className = 'fixed top-4 right-4 bg-emerald-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300';
    successDiv.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-3 text-xl"></i>
            <span class="font-medium">${message}</span>
        </div>
    `;

    document.body.appendChild(successDiv);
    setTimeout(() => successDiv.classList.remove('translate-x-full'), 100);
    setTimeout(() => {
        successDiv.classList.add('translate-x-full');
        setTimeout(() => successDiv.parentNode?.removeChild(successDiv), 300);
    }, 3000);
}

function showAjaxError(message) {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300';
    errorDiv.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle mr-3 text-xl"></i>
            <span class="font-medium">${message}</span>
        </div>
    `;

    document.body.appendChild(errorDiv);
    setTimeout(() => errorDiv.classList.remove('translate-x-full'), 100);
    setTimeout(() => {
        errorDiv.classList.add('translate-x-full');
        setTimeout(() => errorDiv.parentNode?.removeChild(errorDiv), 300);
    }, 5000);
}

// Upgrade modal function
function showUpgradeModal(feature) {
    // Create upgrade modal
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="bg-white rounded-2xl max-w-md w-full mx-4 p-6 text-center">
            <div class="w-16 h-16 bg-gradient-to-r from-primary to-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-crown text-2xl text-white"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Upgrade Required</h3>
            <p class="text-gray-600 mb-6">This feature is not available in your current plan. Upgrade to unlock ${feature} and more premium features.</p>
            <div class="flex gap-3">
                <button onclick="this.closest('.fixed').remove()" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </button>
                <a href="<?= base_url('trainer/subscription') ?>" class="flex-1 px-4 py-2 bg-gradient-to-r from-primary to-red-600 text-white rounded-lg hover:shadow-lg transition">
                    Upgrade Now
                </a>
            </div>
        </div>
    `;
    document.body.appendChild(modal);

    // Close on background click
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.remove();
        }
    });
}

// Contact click tracking
document.addEventListener('DOMContentLoaded', function() {
    const contactButtons = document.querySelectorAll('.contact-btn');

    contactButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const contactType = this.getAttribute('data-contact-type');
            const tutorId = <?= $tutor['id'] ?>;
            const href = this.getAttribute('href');

            console.log('Contact button clicked:', contactType, 'Href:', href);

            // For ALL external links (phone, email, WhatsApp), prevent default and track synchronously
            if (href && (href.startsWith('tel:') || href.startsWith('mailto:') || href.startsWith('https://wa.me/') || href.includes('wa.me'))) {
                e.preventDefault();
                console.log('Preventing default navigation for:', contactType);

                // Track the click using fetch with Promise-based approach
                const trackUrl = '<?= base_url('resources/trackContactClick') ?>';
                const formData = new FormData();
                formData.append('tutor_id', tutorId);
                formData.append('contact_type', contactType);

                console.log('Sending tracking request to:', trackUrl);

                // Use Promise to ensure tracking completes before navigation
                fetch(trackUrl, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    console.log('Tracking response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Tracking response data:', data);
                    if (data.success) {
                        console.log('✅ Contact click tracked successfully:', contactType);
                    } else {
                        console.log('❌ Failed to track contact click:', data.message);
                    }
                })
                .catch(error => {
                    console.log('❌ Error tracking contact click:', error);
                })
                .finally(() => {
                    // Always navigate after tracking attempt
                    console.log('Navigating to:', href);
                    if (href.startsWith('https://wa.me/') || href.includes('wa.me')) {
                        // For WhatsApp, open in new tab
                        window.open(href, '_blank');
                    } else {
                        // For phone and email, use regular navigation
                        window.location.href = href;
                    }
                });
            }
        });
    });
});
</script>

<?= $this->endSection() ?>
