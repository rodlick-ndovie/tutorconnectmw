<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

    <!-- Hero Section -->
    <section class="hero-section py-16 relative overflow-hidden">
        <!-- Background images slideshow -->
        <div class="hero-bg-slideshow absolute inset-0">
            <div class="hero-slide slide-active"
                 style="background-image: url('https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=1200&h=800&fit=crop&crop=center');"></div>
            <div class="hero-slide"
                 style="background-image: url('https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=1200&h=800&fit=crop&crop=center');"></div>
            <div class="hero-slide"
                 style="background-image: url('https://images.unsplash.com/photo-1571260899304-425eee4c7efc?w=1200&h=800&fit=crop&crop=center');"></div>
            <div class="hero-slide"
                 style="background-image: url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=1200&h=800&fit=crop&crop=center');"></div>
        </div>

        <!-- Overlay -->
        <div class="absolute inset-0 bg-gradient-to-br from-blue-900/90 to-blue-700/80"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center animate-fade-in">
                <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-6 leading-tight">
                    Connect with Verified Teachers Across Malawi
                </h1>
               <p class="text-xl text-white/90 max-w-3xl mx-auto mb-10 leading-relaxed">
                    Find the perfect teacher for your child's academic journey. All teachers are verified and specialize in
                    <strong>MANEB, GCSE, Cambridge, ABEKA, Montessori, Languages and Rafiki</strong>
                    curricula for primary and secondary students.
                </p>


                <div class="bg-white rounded-xl shadow-xl p-6 max-w-4xl mx-auto">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div class="md:col-span-1">
                            <input type="text" id="searchName" placeholder="Teacher name" class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-primary focus:border-primary">
                        </div>
                        <div class="md:col-span-1">
                            <select id="searchDistrict" class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-primary focus:border-primary">
                                <option value="">Select District</option>
                                <?php foreach ($districts as $district): ?>
                                    <option value="<?= esc($district) ?>"><?= esc($district) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="md:col-span-1">
                            <select id="searchCurriculum" class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-primary focus:border-primary">
                                <option value="">Curriculum</option>
                                <?php foreach ($curricula as $curriculum): ?>
                                    <option value="<?= esc($curriculum) ?>"><?= esc($curriculum) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="md:col-span-1">
                            <select id="searchSubject" class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-primary focus:border-primary">
                                <option value="">Select Subject</option>
                                <!-- Subjects will be populated dynamically based on curriculum selection -->
                            </select>
                        </div>
                        <div class="md:col-span-1">
                            <button onclick="handleSearch()" id="searchBtn" class="w-full btn-primary text-white py-3 rounded-md font-medium relative">
                                <span id="searchBtnText">Find Teachers</span>
                                <span id="searchSpinner" class="hidden">
                                    <i class="fas fa-spinner fa-spin mr-2"></i>Searching...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php
        $applicationsOpen = in_array(strtolower(trim((string) site_setting('japan_applications_open', '1'))), ['1', 'true', 'yes', 'on'], true);
        $applicationsClosedMessage = trim((string) site_setting('japan_applications_closed_message', 'Japan applications are currently closed. Please check back soon or contact support.'));
        $japanApplicationFee = (int) preg_replace('/[^0-9]/', '', (string) site_setting('japan_application_fee', '10000'));
        $japanApplicationFeeFormatted = number_format((float) $japanApplicationFee, 0);
    ?>

    <section class="bg-white border-b border-gray-100">
        <!-- Full-width (edge-to-edge) banner -->
        <div class="w-full bg-gradient-to-r from-primary to-orange-600">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="grid grid-cols-1 lg:grid-cols-[1.2fr_0.8fr] gap-8 items-center">
                    <div class="text-white">
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="inline-flex items-center rounded-full bg-white/15 px-3 py-1.5 text-xs font-semibold text-red-50 ring-1 ring-white/20">
                                <?php if ($applicationsOpen): ?>
                                    <i class="fas fa-plane-departure mr-2"></i>Now Accepting Applications
                                <?php else: ?>
                                    <i class="fas fa-circle-xmark mr-2"></i>Applications Closed
                                <?php endif; ?>
                            </span>
                            <span class="text-xs text-white/80">Teach English in Japan</span>
                        </div>

                        <h2 class="mt-4 text-2xl lg:text-3xl font-extrabold leading-tight">Japan Teaching Opportunity</h2>
                        <p class="mt-3 text-sm sm:text-base text-red-100 leading-relaxed max-w-2xl">
                            Any degree. All nationalities welcome. Employer-sponsored visa support available.
                        </p>

                        <?php if (!$applicationsOpen): ?>
                            <div class="mt-4 rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-sm text-red-50 max-w-2xl">
                                <strong class="text-white">Notice:</strong> <?= esc($applicationsClosedMessage) ?>
                            </div>
                        <?php endif; ?>

                        <div class="mt-6 flex flex-col sm:flex-row gap-3">
                            <a href="<?= site_url('teach-in-japan') ?>" class="inline-flex items-center justify-center px-5 py-2.5 bg-white text-primary font-bold rounded-lg hover:bg-red-50 transition text-sm">
                                <i class="fas fa-circle-info mr-2"></i>View Details
                            </a>

                            <?php if ($applicationsOpen): ?>
                                <a href="<?= site_url('teach-in-japan/apply') ?>" class="inline-flex items-center justify-center px-5 py-2.5 bg-red-700 hover:bg-red-800 text-white font-bold rounded-lg transition border border-red-600 text-sm">
                                    <i class="fas fa-file-signature mr-2"></i>Apply Now - MK <?= esc($japanApplicationFeeFormatted) ?>
                                </a>
                            <?php else: ?>
                                <a href="<?= site_url('teach-in-japan/status') ?>" class="inline-flex items-center justify-center px-5 py-2.5 bg-white/10 hover:bg-white/15 text-white font-bold rounded-lg transition border border-white/25 text-sm">
                                    <i class="fas fa-search mr-2"></i>Check Status
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Compact KPIs (small + professional) -->
                    <div class="grid grid-cols-2 gap-3">
                        <div class="rounded-xl bg-white/95 border border-white/20 p-4">
                            <div class="text-[11px] font-semibold text-primary uppercase tracking-wide">Salary</div>
                            <div class="mt-1 text-xl font-extrabold text-secondary">JPY 225,000</div>
                            <div class="mt-0.5 text-xs text-gray-600">Entry-level monthly pay</div>
                        </div>
                        <div class="rounded-xl bg-white/95 border border-white/20 p-4">
                            <div class="text-[11px] font-semibold text-primary uppercase tracking-wide">Equivalent</div>
                            <div class="mt-1 text-xl font-extrabold text-secondary">~MK 2.5M</div>
                            <div class="mt-0.5 text-xs text-gray-600">Per month in MWK</div>
                        </div>
                        <div class="rounded-xl bg-white/95 border border-white/20 p-4">
                            <div class="text-[11px] font-semibold text-primary uppercase tracking-wide">Age</div>
                            <div class="mt-1 text-xl font-extrabold text-secondary">20-40</div>
                            <div class="mt-0.5 text-xs text-gray-600">Requirement</div>
                        </div>
                        <div class="rounded-xl bg-white/95 border border-white/20 p-4">
                            <div class="text-[11px] font-semibold text-primary uppercase tracking-wide">Degree</div>
                            <div class="mt-1 text-xl font-extrabold text-secondary">Any</div>
                            <div class="mt-0.5 text-xs text-gray-600">Field accepted</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>





    <!-- Pricing Section -->
    <section id="pricingSection" class="py-16 bg-gray-50 hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 animate-fade-in">
                <h2 class="text-3xl font-extrabold text-secondary mb-4">teacher Subscription Plans</h2>
                <p class="text-lg text-accent max-w-3xl mx-auto">
                    Choose the perfect plan for your teaching business. All plans include the first 2 months FREE!
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                <!-- Basic Plan -->
                <div class="pricing-card bg-white rounded-xl shadow-lg p-8 border border-gray-200">
                    <div class="text-center">
                        <h3 class="text-2xl font-bold text-secondary mb-2">Basic</h3>
                        <div class="text-4xl font-extrabold text-secondary mb-2">MK750</div>
                        <p class="text-gray-600 mb-6">per month</p>
                        <p class="text-sm text-gray-500 mb-8">First 2 months FREE</p>

                        <ul class="space-y-4 text-left">
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                <span>Basic profile listing</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                <span>Contact with students</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                <span>Basic support</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-times text-red-500 mr-3"></i>
                                <span class="text-gray-400">No bio video</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-times text-red-500 mr-3"></i>
                                <span class="text-gray-400">No CV upload</span>
                            </li>
                        </ul>

                        <button class="plan-btn w-full mt-8 bg-secondary text-white py-3 rounded-md hover:bg-accent transition relative" data-plan="basic">
                            <span class="plan-btn-text">Choose Basic</span>
                            <span class="plan-spinner hidden"><i class="fas fa-spinner fa-spin mr-2"></i>Loading...</span>
                        </button>
                    </div>
                </div>

                <!-- Standard Plan -->
                <div class="pricing-card featured bg-white rounded-xl shadow-xl p-8 border-2 border-primary">
                    <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                        <span class="bg-primary text-white px-4 py-1 rounded-full text-sm font-medium">Most Popular</span>
                    </div>
                    <div class="text-center">
                        <h3 class="text-2xl font-bold text-secondary mb-2">Standard</h3>
                        <div class="text-4xl font-extrabold text-secondary mb-2">MK1,500</div>
                        <p class="text-gray-600 mb-6">per month</p>
                        <p class="text-sm text-gray-500 mb-8">First 2 months FREE</p>

                        <ul class="space-y-4 text-left">
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                <span>Enhanced profile listing</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                <span>Contact with students</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                <span>Priority support</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                <span>Bio video upload</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                <span>CV upload access</span>
                            </li>
                        </ul>

                        <button class="plan-btn w-full mt-8 bg-primary text-white py-3 rounded-md hover:bg-red-600 transition relative" data-plan="standard">
                            <span class="plan-btn-text">Choose Standard</span>
                            <span class="plan-spinner hidden"><i class="fas fa-spinner fa-spin mr-2"></i>Loading...</span>
                        </button>
                    </div>
                </div>

                <!-- Premium Plan -->
                <div class="pricing-card bg-white rounded-xl shadow-lg p-8 border border-gray-200">
                    <div class="text-center">
                        <h3 class="text-2xl font-bold text-secondary mb-2">Premium</h3>
                        <div class="text-4xl font-extrabold text-secondary mb-2">MK2,000</div>
                        <p class="text-gray-600 mb-6">per month</p>
                        <p class="text-sm text-gray-500 mb-8">First 2 months FREE</p>

                        <ul class="space-y-4 text-left">
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                <span>Premium profile listing</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                <span>Priority in search results</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                <span>24/7 premium support</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                <span>Advanced analytics</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                <span>All features included</span>
                            </li>
                        </ul>

                        <button class="plan-btn w-full mt-8 bg-secondary text-white py-3 rounded-md hover:bg-accent transition relative" data-plan="premium">
                            <span class="plan-btn-text">Choose Premium</span>
                            <span class="plan-spinner hidden"><i class="fas fa-spinner fa-spin mr-2"></i>Loading...</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="mt-12 text-center">
                <h3 class="text-xl font-bold text-secondary mb-4">Payment Methods</h3>
                <div class="flex justify-center items-center space-x-8">
                    <div class="flex items-center">
                        <i class="fas fa-mobile-alt text-primary text-2xl mr-2"></i>
                        <span class="font-medium">Airtel Money</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-money-bill-wave text-primary text-2xl mr-2"></i>
                        <span class="font-medium">Mpamba</span>
                    </div>
                </div>
                <p class="text-gray-600 mt-4">Secure mobile money payments for teacher subscriptions</p>
            </div>
        </div>
    </section>

    <!-- Most Searched Teachers -->
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10 animate-fade-in">
                <h2 class="text-3xl font-extrabold text-secondary mb-4">Most Searched Teachers</h2>
                <p class="text-lg text-accent max-w-3xl mx-auto">
                    These Teachers are highly sought after by parents and students across Malawi
                </p>
            </div>

            <?php if (!empty($mostSearched)): ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($mostSearched as $tutor): ?>
                    <a href="<?= site_url('tutor/' . (!empty($tutor['username']) ? $tutor['username'] : $tutor['id'])) ?>" class="relative bg-white rounded-xl shadow-md hover:shadow-xl border border-slate-100 transition-all duration-300 block group overflow-hidden flex flex-col h-full">
                            <!-- Badge Level Badge (from subscription plan) -->
                            <?php
                                // Debug: Show what badge_level we have
                                $currentBadgeLevel = $tutor['badge_level'] ?? 'none';
                                // Only show badge if it's not 'none' and not empty
                                if (!empty($currentBadgeLevel) && $currentBadgeLevel !== 'none'):
                            ?>
                                <div class="absolute top-3 left-3 z-10">
                                    <?php
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
                                        $badgeLabels = [
                                            'beginner' => 'Beginner',
                                            'intermediate' => 'Intermediate',
                                            'advanced' => 'Advanced',
                                            'expert' => 'Expert',
                                            'master' => 'Master'
                                        ];
                                        $badgeColor = $badgeColors[$currentBadgeLevel] ?? 'bg-blue-500';
                                        $badgeIcon = $badgeIcons[$currentBadgeLevel] ?? 'fas fa-certificate';
                                        $badgeLabel = $badgeLabels[$currentBadgeLevel] ?? ucfirst(str_replace('_', ' ', $currentBadgeLevel));
                                    ?>
                                    <div class="<?= $badgeColor ?> text-white px-3 py-1.5 rounded-lg font-bold text-xs shadow-lg flex items-center">
                                        <i class="<?= $badgeIcon ?> mr-1.5"></i>
                                        <span><?= $badgeLabel ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Verified Badge -->
                            <div class="absolute top-3 right-3 z-10 bg-emerald-500 text-white px-3 py-1.5 rounded-lg font-bold text-xs shadow-lg flex items-center">
                                <i class="fas fa-shield-check mr-1.5"></i>
                                <span>Verified</span>
                            </div>

                            <!-- Tutor Image -->
                            <div class="relative h-48 bg-slate-100 flex items-center justify-center overflow-hidden" style="<?php if (!empty($tutor['cover_photo'])): ?>background-image: url('<?= base_url($tutor['cover_photo']) ?>'); background-size: cover; background-position: center; <?php elseif (!empty($tutor['profile_picture'])): ?>background-image: url('<?= base_url($tutor['profile_picture']) ?>'); background-size: cover; background-position: center; <?php endif; ?>">
                                <div class="absolute inset-0 bg-black/50 group-hover:bg-black/40 transition-all duration-300"></div>
                                <div class="absolute inset-0 bg-gradient-to-br from-purple-500/10 to-pink-600/10"></div>
                                <!-- Profile Picture -->
                                <div class="relative w-24 h-24 rounded-full overflow-hidden shadow-xl z-10">
                                    <img src="<?= base_url($tutor['profile_picture']) ?>" alt="Profile Picture" class="w-full h-full object-cover">
                                </div>
                            </div>

                            <!-- Tutor Info -->
                            <div class="p-4 flex flex-col flex-grow">
                                <!-- Rating and Name -->
                                <div class="flex items-start justify-between mb-2">
                                    <div class="flex-grow">
                                        <h3 class="text-base font-bold text-slate-800 group-hover:text-blue-600 transition-colors">
                                            <?= esc($tutor['first_name'] . ' ' . $tutor['last_name']) ?>
                                        </h3>
                                    </div>
                                    <div class="bg-amber-400 text-slate-900 px-2 py-1 rounded-full text-xs font-bold flex items-center ml-2 flex-shrink-0">
                                        <i class="fas fa-star mr-0.5 text-xs"></i>
                                        <?= number_format($tutor['rating'] ?? 0, 1) ?>
                                    </div>
                                </div>

                                <!-- Subjects from structured_subjects (limited by max_subjects) -->
                                <div class="mb-3">
                                    <div class="flex flex-wrap gap-1">
                                        <?php
                                            $subjects = $tutor['structured_subjects_array'] ?? [];
                                            $maxSubjects = (int) ($tutor['max_subjects'] ?? 3);
                                            $displaySubjects = array_slice($subjects, 0, min(count($subjects), $maxSubjects));

                                            foreach($displaySubjects as $subject) {
                                                if (!empty(trim($subject))) {
                                                    echo '<span class="inline-block bg-slate-100 text-slate-700 text-xs px-2.5 py-1 rounded-full font-medium">' . esc(trim($subject)) . '</span>';
                                                }
                                            }
                                            if (count($subjects) > $maxSubjects) {
                                                echo '<span class="inline-block text-slate-600 text-xs px-2.5 py-1 font-medium">+' . (count($subjects) - $maxSubjects) . '</span>';
                                            }
                                        ?>
                                    </div>
                                </div>

                                <!-- Location and Details -->
                                <div class="mb-3 text-sm text-slate-600 space-y-1">
                                    <div class="flex items-center">
                                        <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                                        <span class="font-medium"><?= esc($tutor['district'] ?? 'Location not specified') ?></span>
                                    </div>
                                    <?php if (!empty($tutor['location'])): ?>
                                        <div class="text-xs text-slate-500 ml-6"><?= esc($tutor['location']) ?></div>
                                    <?php endif; ?>

                                    <div class="flex items-center mt-1">
                                        <i class="fas fa-laptop mr-2"></i>
                                        <span class="font-medium">
                                            <?php
                                                $teachingMode = $tutor['teaching_mode'] ?? 'Online';
                                                $modeDisplay = match(strtolower($teachingMode)) {
                                                    'online' => 'Online',
                                                    'in-person', 'physical' => 'In-Person',
                                                    'both', 'both online & physical' => 'Online & In-Person',
                                                    default => $teachingMode
                                                };
                                                echo esc($modeDisplay);
                                            ?>
                                        </span>
                                    </div>

                                    <?php if (($tutor['hourly_rate'] ?? 0) > 0): ?>
                                        <div class="flex items-center mt-1 text-emerald-700 font-semibold">
                                            <i class="fas fa-money-bill mr-2"></i>
                                            MWK <?= number_format($tutor['hourly_rate']) ?>/hr
                                        </div>
                                    <?php endif; ?>

                                    <!-- Search Ranking Indicator (for premium plans) -->
                                    <?php if (isset($tutor['search_ranking']) && $tutor['search_ranking'] !== 'low'): ?>
                                        <div class="flex items-center mt-1 text-purple-700 font-semibold">
                                            <i class="fas fa-arrow-up mr-2"></i>
                                            <span class="text-xs capitalize"><?= esc(str_replace('_', ' ', $tutor['search_ranking'])) ?> Priority</span>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- CTA Button (use non-button to avoid nested interactive element) -->
                                <div class="mt-auto bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors text-sm text-center">
                                    View Profile
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-search text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No Featured Teachers Yet</h3>
                    <p class="text-gray-600 mb-8">
                        Teachers will be featured here once they complete their profiles and gain ratings.
                    </p>
                </div>
            <?php endif; ?>

            <div class="mt-12 text-center">
                <a href="<?= site_url('find-tutors') ?>" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-primary hover:bg-red-600 transition">
                    View All Teachers <i class="fas fa-chevron-right ml-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Become a Tutor CTA Section (Professional) -->
    <section class="py-16 bg-gradient-to-r from-primary to-red-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row items-center gap-12">
                <!-- Left Side - Image Slideshow (Mobile Small, Desktop Large) -->
                <div class="w-full lg:w-1/2 lg:flex lg:justify-center mb-6 lg:mb-0">
                    <div class="relative w-64 h-64 lg:w-full lg:max-w-3xl lg:h-[32rem] rounded-2xl overflow-hidden shadow-2xl mx-auto">
                        <div class="tutor-slideshow relative w-full h-full">
                            <img class="tutor-slide slide-active absolute w-full h-full object-cover transition-transform duration-700" style="transform: translateX(0);" src="<?= base_url('uploads/slider/pexels-katerina-holmes-5905718.jpg') ?>" alt="Tutor 1">
                            <img class="tutor-slide absolute w-full h-full object-cover transition-transform duration-700" style="transform: translateX(100%);" src="<?= base_url('uploads/slider/pexels-katerina-holmes-5905706.jpg') ?>" alt="Tutor 2">
                            <img class="tutor-slide absolute w-full h-full object-cover transition-transform duration-700" style="transform: translateX(100%);" src="<?= base_url('uploads/slider/pexels-vlada-karpovich-7368295.jpg') ?>" alt="Tutor 3">
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>

                        <!-- Slide Indicators -->
                        <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-2 z-10">
                            <button class="slide-indicator active w-3 h-3 rounded-full bg-white transition-all duration-300 cursor-pointer" data-slide="0"></button>
                            <button class="slide-indicator w-3 h-3 rounded-full bg-white/50 transition-all duration-300 cursor-pointer" data-slide="1"></button>
                            <button class="slide-indicator w-3 h-3 rounded-full bg-white/50 transition-all duration-300 cursor-pointer" data-slide="2"></button>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Content -->
                <div class="w-full lg:w-1/2 text-white">
                    <h2 class="text-4xl md:text-5xl font-extrabold mb-4">Ready to Transform Lives Through Teaching?</h2>
                    <p class="text-lg text-red-100 mb-6">
                        Join thousands of verified Teachers across Malawi and start earning while making a real difference in students' lives. Our platform connects you with motivated learners seeking your expertise.
                    </p>

                    <div class="space-y-3 mb-8">
                        <div class="flex items-center text-base">
                            <i class="fas fa-check-circle text-green-300 mr-3 text-xl"></i>
                            <span>Build your teaching business with our proven platform</span>
                        </div>
                        <div class="flex items-center text-base">
                            <i class="fas fa-check-circle text-green-300 mr-3 text-xl"></i>
                            <span>Reach thousands of students actively seeking Teachers</span>
                        </div>
                        <div class="flex items-center text-base">
                            <i class="fas fa-check-circle text-green-300 mr-3 text-xl"></i>
                            <span>Get verified badge to build trust and credibility</span>
                        </div>
                    </div>

                    <div class="mb-8">
                        <p class="text-lg font-bold text-white">
                            <i class="fas fa-gift text-yellow-300 mr-2"></i>
                            Get 1 MONTH FREE when you sign up today!
                        </p>
                        <p class="text-base text-red-100 mt-2">No credit card required - Start immediately</p>
                    </div>

                    <a href="<?= site_url('register') ?>" id="ctaRegisterBtn" class="inline-block px-6 py-2.5 bg-white text-primary rounded-lg font-bold hover:bg-red-50 transition-all text-sm shadow-lg hover:shadow-xl relative">
                        <span id="ctaRegisterText"><i class="fas fa-user-plus mr-2"></i>Create Your Free Account</span>
                        <span id="ctaRegisterSpinner" class="hidden"><i class="fas fa-spinner fa-spin mr-2"></i>Loading...</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="howItWorksSection" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 animate-fade-in">
                <h2 class="text-3xl font-extrabold text-secondary mb-4">How TutorConnect Works</h2>
                <p class="text-lg text-accent max-w-3xl mx-auto">
                    A complete guide for Teachers and students from registration to successful teaching sessions
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- For Teachers -->
                <div class="bg-white rounded-xl p-8">
                    <h3 class="text-2xl font-bold text-secondary mb-6 flex items-center">
                        <i class="fas fa-chalkboard-teacher text-primary mr-3"></i>
                        For Teachers
                    </h3>
                    <p class="text-lg text-gray-600 mb-6">Start earning and help students succeed</p>

                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center font-bold mr-4">1</div>
                            <div>
                                <h4 class="text-lg font-semibold text-secondary mb-2">Register Profile</h4>
                                <p class="text-gray-600">Complete your teacher profile with qualifications, subjects, and rates.</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center font-bold mr-4">2</div>
                            <div>
                                <h4 class="text-lg font-semibold text-secondary mb-2">Get Verified</h4>
                                <p class="text-gray-600">Submit documents for verification to build trust with students.</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center font-bold mr-4">3</div>
                            <div>
                                <h4 class="text-lg font-semibold text-secondary mb-2">Receive Inquiries</h4>
                                <p class="text-gray-600">Start receiving messages from students interested in your services.</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center font-bold mr-4">4</div>
                            <div>
                                <h4 class="text-lg font-semibold text-secondary mb-2">Start Earning</h4>
                                <p class="text-gray-600">Schedule lessons and earn money directly from your students.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- For Students/Parents -->
                <div class="bg-white rounded-xl p-8">
                    <h3 class="text-2xl font-bold text-secondary mb-6 flex items-center">
                        <i class="fas fa-user-graduate text-primary mr-3"></i>
                        For Students & Parents
                    </h3>
                    <p class="text-lg text-gray-600 mb-6">Find your perfect teacher in 3 simple steps - no registration required!</p>

                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center font-bold mr-4">1</div>
                            <div>
                                <h4 class="text-lg font-semibold text-secondary mb-2">Browse Teachers</h4>
                                <p class="text-gray-600">No registration needed! Browse verified teachers by subject, curriculum, location, and reviews.</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center font-bold mr-4">2</div>
                            <div>
                                <h4 class="text-lg font-semibold text-secondary mb-2">Contact Teacher</h4>
                                <p class="text-gray-600">Send a direct message to your preferred teacher to arrange lessons and discuss your learning goals.</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center font-bold mr-4">3</div>
                            <div>
                                <h4 class="text-lg font-semibold text-secondary mb-2">Start Learning</h4>
                                <p class="text-gray-600">Begin your personalized teaching sessions at times that work for you. Arrange payment directly with your teacher.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Teachers -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 animate-fade-in">
                <h2 class="text-3xl font-extrabold text-secondary mb-4">Featured Teachers</h2>
                <p class="text-lg text-accent max-w-3xl mx-auto">
                    Our top-rated verified Teachers with excellent student outcomes
                </p>
            </div>

            <?php if (!empty($featured)): ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    <?php foreach ($featured as $tutor): ?>
                        <a href="<?= site_url('tutor/' . (!empty($tutor['username']) ? $tutor['username'] : $tutor['id'])) ?>" class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-500 block group overflow-hidden transform hover:-translate-y-1 relative">
                            <!-- Subscription Badge Level (from subscription plan) -->
                            <?php
                                $currentBadgeLevel = $tutor['badge_level'] ?? 'none';
                                if (!empty($currentBadgeLevel) && $currentBadgeLevel !== 'none'):
                            ?>
                                <div class="absolute top-4 left-4 z-10">
                                    <?php
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
                                        $badgeLabels = [
                                            'beginner' => 'Beginner',
                                            'intermediate' => 'Intermediate',
                                            'advanced' => 'Advanced',
                                            'expert' => 'Expert',
                                            'master' => 'Master'
                                        ];
                                        $badgeColor = $badgeColors[$currentBadgeLevel] ?? 'bg-purple-500';
                                        $badgeIcon = $badgeIcons[$currentBadgeLevel] ?? 'fas fa-star';
                                        $badgeLabel = $badgeLabels[$currentBadgeLevel] ?? 'Featured';
                                    ?>
                                    <div class="<?= $badgeColor ?> text-white px-3 py-1 rounded-full text-xs font-bold flex items-center shadow-lg">
                                        <i class="<?= $badgeIcon ?> mr-1"></i>
                                        <?= $badgeLabel ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <!-- Default Featured Badge for Teachers without subscription badges -->
                                <div class="absolute top-4 left-4 z-10">
                                    <div class="bg-gradient-to-r from-purple-500 to-pink-500 text-white px-3 py-1 rounded-full text-xs font-bold flex items-center shadow-lg">
                                        <i class="fas fa-star mr-1"></i>
                                        Featured
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Verified Badge -->
                            <div class="absolute top-4 right-4 z-10">
                                <div class="bg-emerald-500 text-white px-2 py-1 rounded-full text-xs font-semibold flex items-center shadow-lg">
                                    <i class="fas fa-shield-check mr-1"></i>
                                    Verified
                                </div>
                            </div>

                            <!-- Tutor Image -->
                            <div class="relative h-48 bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center overflow-hidden" style="<?php if (!empty($tutor['cover_photo'])): ?>background-image: url('<?= base_url($tutor['cover_photo']) ?>'); background-size: cover; background-position: center; <?php elseif (!empty($tutor['profile_picture'])): ?>background-image: url('<?= base_url($tutor['profile_picture']) ?>'); background-size: cover; background-position: center; <?php endif; ?>">
                                <div class="absolute inset-0 bg-black/40 group-hover:bg-black/30 transition-all duration-500"></div>
                                <div class="absolute inset-0 bg-gradient-to-br from-purple-500/10 to-pink-600/10 group-hover:from-purple-500/20 group-hover:to-pink-600/20 transition-all duration-500"></div>
                                <?php if (!empty($tutor['profile_picture'])): ?>
                                    <div class="relative w-24 h-24 rounded-full overflow-hidden shadow-xl group-hover:scale-110 transition-transform duration-500 z-10">
                                        <img src="<?= base_url($tutor['profile_picture']) ?>" alt="Profile Picture" class="w-full h-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    </div>
                                    <div class="relative w-24 h-24 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center shadow-xl group-hover:scale-110 transition-transform duration-500 z-10" style="display: none;">
                                        <i class="fas fa-user-graduate text-white text-3xl"></i>
                                    </div>
                                <?php else: ?>
                                    <div class="relative w-24 h-24 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center shadow-xl group-hover:scale-110 transition-transform duration-500 z-10">
                                        <i class="fas fa-user-graduate text-white text-3xl"></i>
                                    </div>
                                <?php endif; ?>
                                <!-- Decorative elements -->
                                <div class="absolute top-4 left-4 w-8 h-8 bg-white/20 rounded-full"></div>
                                <div class="absolute bottom-4 right-4 w-6 h-6 bg-white/20 rounded-full"></div>
                            </div>

                            <!-- Tutor Info -->
                            <div class="p-6 relative">
                                <!-- Rating Badge -->
                                <div class="absolute -top-3 left-6">
                                    <div class="bg-amber-400 text-slate-900 px-3 py-1 rounded-full text-xs font-bold flex items-center shadow-md">
                                        <i class="fas fa-star mr-1 text-xs"></i>
                                        <?= number_format($tutor['rating'] ?? 0, 1) ?>
                                        <span class="ml-1 text-slate-700">(<?= intval($tutor['review_count'] ?? 0) ?> reviews)</span>
                                    </div>
                                </div>

                                <div class="pt-2">
                                    <!-- Name and Title -->
                                    <div class="mb-4">
                                        <h3 class="text-xl font-bold text-slate-800 group-hover:text-purple-600 transition-colors duration-300 leading-tight">
                                            <?= esc($tutor['first_name'] . ' ' . $tutor['last_name']) ?>
                                        </h3>
                                        <p class="text-sm text-slate-500 font-medium capitalize mt-1">
                                            Top-Rated teacher
                                        </p>
                                    </div>

                                    <!-- Location -->
                                    <div class="flex items-center text-sm text-slate-600">
                                        <i class="fas fa-map-marker-alt text-red-400 mr-1"></i>
                                        <span class="font-medium"><?= esc($tutor['district'] ?? 'Location not specified') ?></span>
                                    </div>

                                    <!-- CTA Button -->
                                    <div class="mt-6 pt-4 border-t border-slate-100">
                                        <div class="bg-gradient-to-r from-purple-500 to-pink-600 text-white px-4 py-2 rounded-lg font-semibold text-sm shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-300 flex items-center justify-center w-full">
                                            <i class="fas fa-arrow-right mr-2"></i>
                                            View teacher
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="mt-12 text-center">
                <a href="<?= site_url('find-tutors') ?>" id="viewAllTutorsBtn" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-primary hover:bg-red-600 transition relative">
                    <span id="viewAllTutorsText">View All Teachers <i class="fas fa-chevron-right ml-2"></i></span>
                    <span id="viewAllTutorsSpinner" class="hidden"><i class="fas fa-spinner fa-spin mr-2"></i>Loading...</span>
                </a>
            </div>
        </div>
    </section>



    <!-- CTA Section -->
    <section class="overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-0 items-stretch">
            <!-- Left Side - Image -->
            <div class="hidden lg:block relative bg-cover bg-center h-full min-h-[350px]" style="background-image: url('<?= base_url('uploads/slider/pexels-vlada-karpovich-7368295.jpg') ?>'); background-position: center; background-size: cover;">
                <div class="absolute inset-0 bg-gradient-to-r from-primary/10 to-transparent"></div>
            </div>

            <!-- Right Side - Content -->
            <div class="bg-gradient-to-r from-primary to-orange-600 text-white flex items-center px-8 sm:px-12 lg:px-16 py-12">
                <div class="w-full">
                    <h2 class="text-3xl lg:text-4xl font-extrabold mb-4 leading-tight">Ready to Get Started?</h2>
                    <p class="text-lg text-red-100 mb-8 leading-relaxed">Join on TutorConnect Malawi.</p>

                    <div class="space-y-3 lg:space-y-0 lg:flex lg:gap-4">
                        <a href="<?= site_url('register') ?>" class="block px-6 py-3 bg-white text-primary font-bold rounded-lg hover:bg-red-50 transition text-center lg:inline-block text-sm">
                            <i class="fas fa-user-plus mr-2"></i>Become a Teacher
                        </a>
                        <a href="<?= site_url('find-tutors') ?>" class="block px-6 py-3 bg-red-700 hover:bg-red-800 text-white font-bold rounded-lg transition border border-red-600 text-center lg:inline-block text-sm">
                            <i class="fas fa-search mr-2"></i>Find a Teacher
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- School Notices Scrolling Banner -->
    <section class="py-12 bg-gradient-to-r from-primary to-red-600 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <i class="fas fa-bullhorn text-3xl text-white mr-3"></i>
                    <h2 class="text-2xl font-bold text-white">School Notices & Announcements</h2>
                </div>
                <a href="<?= site_url('notice/create') ?>"
                   class="px-4 py-2 bg-white text-primary rounded-lg font-semibold hover:bg-red-50 transition text-sm">
                    <i class="fas fa-plus mr-2"></i>Post Notice
                </a>
            </div>

            <?php
            // Get recent approved notices for scrolling display
            $noticeModel = new \App\Models\NoticeModel();
            $recentNotices = $noticeModel->getRecentNotices(10);
            ?>

            <?php if (!empty($recentNotices)): ?>
                <div class="relative">
                    <div class="notice-scroll-container overflow-x-hidden overflow-y-visible">
                        <div class="notice-scroll-wrapper flex gap-6">
                            <?php foreach ($recentNotices as $notice): ?>
                                <div class="notice-card flex-shrink-0 w-96 bg-white rounded-xl shadow-lg p-6">
                                    <div class="flex items-start justify-between mb-3">
                                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                                            <?= $notice['notice_type'] == 'Vacancy' ? 'bg-green-100 text-green-800' :
                                                ($notice['notice_type'] == 'Announcement' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') ?>">
                                            <?= esc($notice['notice_type']) ?>
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            <i class="far fa-clock mr-1"></i>
                                            <?= date('M d, Y', strtotime($notice['approved_at'])) ?>
                                        </span>
                                    </div>

                                    <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                                        <?= esc($notice['notice_title']) ?>
                                    </h3>

                                    <div class="flex items-center text-sm text-gray-700 mb-3">
                                        <i class="fas fa-school text-primary mr-2"></i>
                                        <span class="font-semibold"><?= esc($notice['school_name']) ?></span>
                                    </div>

                                    <p class="text-sm text-gray-600 mb-4 line-clamp-3">
                                        <?= esc(substr($notice['notice_content'], 0, 150)) ?>...
                                    </p>

                                    <?php if (!empty($notice['attached_image'])): ?>
                                        <div class="mb-3">
                                            <img src="<?= base_url($notice['attached_image']) ?>"
                                             alt="Notice image"
                                             class="w-full h-32 object-cover rounded-lg">
                                        </div>
                                    <?php endif; ?>

                                    <div class="flex items-center justify-between pt-3 border-t">
                                        <div class="text-xs text-gray-500">
                                            <i class="fas fa-phone mr-1"></i><?= esc($notice['phone']) ?>
                                        </div>
                                        <a href="<?= site_url('notice/view/' . $notice['id']) ?>"
                                           class="text-primary hover:text-red-600 font-semibold text-sm">
                                            Read More <i class="fas fa-arrow-right ml-1"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Navigation Arrows for Speed Control -->
                    <button onclick="boostScroll('left')"
                            class="absolute left-0 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-primary rounded-full p-3 shadow-lg z-10 transition hover:scale-110">
                        <i class="fas fa-chevron-left text-lg"></i>
                    </button>
                    <button onclick="boostScroll('right')"
                            class="absolute right-0 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-primary rounded-full p-3 shadow-lg z-10 transition hover:scale-110">
                        <i class="fas fa-chevron-right text-lg"></i>
                    </button>
                </div>

                <div class="text-center mt-6">
                    <a href="<?= site_url('notice') ?>"
                       class="inline-block px-6 py-2.5 bg-white text-primary rounded-lg font-semibold hover:bg-red-50 transition">
                        View All Notices <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            <?php else: ?>
                <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                    <i class="fas fa-bullhorn text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">No Notices Yet</h3>
                    <p class="text-gray-500 mb-4">Be the first to post a school notice or announcement</p>
                    <a href="<?= site_url('notice/create') ?>"
                       class="inline-block px-6 py-2.5 bg-primary text-white rounded-lg font-semibold hover:bg-red-700 transition">
                        <i class="fas fa-plus mr-2"></i>Post Your First Notice
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <style>
            .notice-scroll-container {
                overflow-x: hidden;
                position: relative;
            }

            .notice-scroll-wrapper {
                animation: scroll-notices 15s linear infinite;
                will-change: transform;
                display: flex;
            }

            .notice-scroll-wrapper.speed-boost {
                animation: scroll-notices 6s linear infinite;
            }

            .notice-scroll-container:hover .notice-scroll-wrapper {
                animation-play-state: paused;
            }

            @keyframes scroll-notices {
                0% {
                    transform: translateX(0);
                }
                100% {
                    transform: translateX(calc(-50% - 1.5rem));
                }
            }

            .notice-scroll-wrapper {
                animation: scroll-notices 15s linear infinite;
            }

            .notice-scroll-wrapper.speed-boost {
                animation: scroll-notices 6s linear infinite;
            }

            .line-clamp-2 {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .line-clamp-3 {
                display: -webkit-box;
                -webkit-line-clamp: 3;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
        </style>

        <script>
            // Duplicate items for seamless loop
            document.addEventListener('DOMContentLoaded', function() {
                const wrapper = document.querySelector('.notice-scroll-wrapper');
                if (wrapper) {
                    const items = Array.from(wrapper.querySelectorAll('.notice-card'));
                    items.forEach(item => {
                        wrapper.appendChild(item.cloneNode(true));
                    });
                }
            });

            function boostScroll(direction) {
                const wrapper = document.querySelector('.notice-scroll-wrapper');
                const container = document.querySelector('.notice-scroll-container');
                if (!wrapper) return;

                const scrollAmount = 400;
                if (direction === 'left') {
                    container.scrollLeft -= scrollAmount;
                } else {
                    container.scrollLeft += scrollAmount;
                }
            }
        </script>
    </section>

</div>

<style>
.hero-section {
    min-height: 600px;
    position: relative;
}

.hero-bg-slideshow {
    position: absolute;
    inset: 0;
}

.hero-slide {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    opacity: 0;
    transition: opacity 1.5s ease-in-out;
}

.hero-slide.slide-active {
    opacity: 1;
}

.btn-primary {
    background: linear-gradient(135deg, #E74C3C 0%, #c0392b 100%);
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 10px 20px rgba(231, 76, 60, 0.3);
}

.animate-fade-in {
    animation: fadeIn 0.6s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<script>
// Hero slideshow functionality
let currentSlide = 0;
const slides = document.querySelectorAll('.hero-slide');
let slideInterval;

function showSlide(index) {
    slides.forEach(slide => slide.classList.remove('slide-active'));
    slides[index].classList.add('slide-active');
}

function nextSlide() {
    currentSlide = (currentSlide + 1) % slides.length;
    showSlide(currentSlide);
}

function startSlideshow() {
    slideInterval = setInterval(nextSlide, 5000); // Change slide every 5 seconds
}

function stopSlideshow() {
    clearInterval(slideInterval);
}

// Initialize slideshow when page loads
document.addEventListener('DOMContentLoaded', function() {
    if (slides.length > 0) {
        showSlide(0);
        startSlideshow();

        // Pause on hover
        document.querySelector('.hero-section').addEventListener('mouseenter', stopSlideshow);
        document.querySelector('.hero-section').addEventListener('mouseleave', startSlideshow);
    }
});

// Subject options organized by curriculum
// All subjects from database and curriculum mapping
const allSubjects = <?= json_encode($subjects) ?>;
const curriculumSubjects = <?= json_encode($curriculumSubjects ?? []) ?>;

function updateSubjectOptions(selectedCurriculum = '') {
    const subjectSelect = document.getElementById('searchSubject');

    // Clear current subject options
    subjectSelect.innerHTML = '<option value="">Select Subject</option>';

    let subjectsToShow = [];

    if (selectedCurriculum && curriculumSubjects[selectedCurriculum]) {
        // Show only subjects for the selected curriculum
        subjectsToShow = curriculumSubjects[selectedCurriculum];
    } else {
        // Show all subjects if no curriculum selected
        subjectsToShow = allSubjects;
    }

    // Add subjects to dropdown
    subjectsToShow.forEach(subject => {
        if (subject && subject.trim() !== '') {
            const option = document.createElement('option');
            option.value = subject;
            option.textContent = subject;
            subjectSelect.appendChild(option);
        }
    });
}

function handleSearch() {
    // Show spinner and disable button
    const searchBtn = document.getElementById('searchBtn');
    const btnText = document.getElementById('searchBtnText');
    const spinner = document.getElementById('searchSpinner');

    searchBtn.disabled = true;
    btnText.classList.add('hidden');
    spinner.classList.remove('hidden');

    // Build search parameters from form fields
    const params = new URLSearchParams();
    const name = document.getElementById('searchName').value.trim();
    const district = document.getElementById('searchDistrict').value;
    const curriculum = document.getElementById('searchCurriculum').value;
    const subject = document.getElementById('searchSubject').value;

    if (name) params.append('name', name);
    if (district) params.append('district', district);
    if (curriculum) params.append('curriculum', curriculum);
    if (subject) params.append('subject', subject);

    // Redirect to find-tutors page with search parameters
    const queryString = params.toString();
    const url = '<?= site_url('find-tutors') ?>' + (queryString ? '?' + queryString : '');
    window.location.href = url;
}

function showHowItWorks() {
    window.location.href = '<?= site_url('how-it-works') ?>';
}

function showPricing() {
    window.location.href = '<?= site_url('pricing') ?>';
}

function showTutorRegistration() {
    window.location.href = '<?= site_url('register') ?>';
}

// Handle pricing plan buttons with spinner
document.addEventListener('DOMContentLoaded', function() {
    const planButtons = document.querySelectorAll('.plan-btn');
    planButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const text = btn.querySelector('.plan-btn-text');
            const spinner = btn.querySelector('.plan-spinner');
            btn.disabled = true;
            if (text) text.classList.add('hidden');
            if (spinner) spinner.classList.remove('hidden');
            const plan = btn.dataset.plan || '';
            const url = '<?= site_url('pricing') ?>' + (plan ? '?plan=' + encodeURIComponent(plan) : '');
            window.location.href = url;
        });
    });
    const ctaRegisterBtn = document.getElementById('ctaRegisterBtn');
    if (ctaRegisterBtn) {
        ctaRegisterBtn.addEventListener('click', function(e) {
            const text = document.getElementById('ctaRegisterText');
            const spinner = document.getElementById('ctaRegisterSpinner');
            ctaRegisterBtn.classList.add('pointer-events-none', 'opacity-80');
            if (text) text.classList.add('hidden');
            if (spinner) spinner.classList.remove('hidden');
        });
    }
    const viewAllBtn = document.getElementById('viewAllTutorsBtn');
    if (viewAllBtn) {
        viewAllBtn.addEventListener('click', function(e) {
            const text = document.getElementById('viewAllTutorsText');
            const spinner = document.getElementById('viewAllTutorsSpinner');
            viewAllBtn.classList.add('pointer-events-none', 'opacity-80');
            if (text) text.classList.add('hidden');
            if (spinner) spinner.classList.remove('hidden');
        });
    }
});

// Initialize subject updates when curriculum changes
document.addEventListener('DOMContentLoaded', function() {
    // Initialize subjects with all options
    updateSubjectOptions();

    // Add event listener for curriculum change
    const curriculumSelect = document.getElementById('searchCurriculum');
    if (curriculumSelect) {
        curriculumSelect.addEventListener('change', function() {
            const selectedCurriculum = this.value;
            updateSubjectOptions(selectedCurriculum);
        });
    }

    // Initialize tutor image slideshow with sliding animation
    let currentSlide = 0;
    const tutorSlides = document.querySelectorAll('.tutor-slide');
    const slideIndicators = document.querySelectorAll('.slide-indicator');

    if (tutorSlides.length > 0) {
        function showSlide(index) {
            tutorSlides.forEach((slide, i) => {
                if (i === index) {
                    slide.style.transform = 'translateX(0)';
                    slide.classList.add('slide-active');
                } else {
                    slide.style.transform = 'translateX(100%)';
                    slide.classList.remove('slide-active');
                }
            });

            // Update indicators
            slideIndicators.forEach((indicator, i) => {
                if (i === index) {
                    indicator.classList.add('active');
                    indicator.style.backgroundColor = 'white';
                    indicator.style.opacity = '1';
                } else {
                    indicator.classList.remove('active');
                    indicator.style.backgroundColor = 'white';
                    indicator.style.opacity = '0.5';
                }
            });
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % tutorSlides.length;
            showSlide(currentSlide);
        }

        // Add click handlers for indicators
        slideIndicators.forEach(indicator => {
            indicator.addEventListener('click', function() {
                currentSlide = parseInt(this.dataset.slide);
                showSlide(currentSlide);
            });
        });

        // Change slide every 5 seconds
        setInterval(nextSlide, 5000);
    }
});
</script>

<?= $this->endSection() ?>
