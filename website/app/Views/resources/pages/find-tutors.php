<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="bg-gradient-to-r from-primary to-orange-600 text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-5xl md:text-6xl font-extrabold mb-4">Find Qualified Tutors</h1>
        <p class="text-xl text-red-100 max-w-3xl mx-auto">Search and filter verified tutors across Malawi. Connect directly with the perfect tutor for your learning needs.</p>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 py-12">

        <!-- Search and Filter Section -->
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-4 mb-8">
            <div class="flex items-center gap-2 mb-4">
                <i class="fas fa-sliders-h text-primary text-lg"></i>
                <h2 class="text-lg font-semibold text-gray-800">Find Your Perfect Tutor</h2>
            </div>
            <form method="get" action="<?= site_url('find-tutors') ?>" class="space-y-3">
                <!-- Search Bar -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div class="md:col-span-2">
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="<?= esc($filters['name'] ?? '') ?>"
                            placeholder="Search by name..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary text-sm"
                        >
                    </div>
                    <div>
                        <select id="district" name="district" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                            <option value="">All Districts</option>
                            <?php if (!empty($districts)): ?>
                                <?php foreach ($districts as $district): ?>
                                    <option value="<?= esc($district) ?>" <?= ($filters['district'] ?? '') == $district ? 'selected' : '' ?>><?= esc($district) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <!-- Second Row -->
                <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                    <div>
                        <select id="curriculum" name="curriculum" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                            <option value="">Curriculum</option>
                            <?php if (!empty($curricula)): ?>
                                <?php foreach ($curricula as $curr): ?>
                                    <option value="<?= esc($curr) ?>" <?= ($filters['curriculum'] ?? '') == $curr ? 'selected' : '' ?>><?= esc($curr) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div>
                        <select id="subject" name="subject" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                            <option value="">Subject</option>
                            <?php if (!empty($subjects)): ?>
                                <?php foreach ($subjects as $subject): ?>
                                    <option value="<?= esc($subject) ?>" <?= ($filters['subject'] ?? '') == $subject ? 'selected' : '' ?>><?= esc($subject) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div>
                        <select id="level" name="level" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                            <option value="">Level</option>
                            <?php if (!empty($levels)): ?>
                                <?php foreach ($levels as $level): ?>
                                    <option value="<?= esc($level) ?>" <?= ($filters['level'] ?? '') == $level ? 'selected' : '' ?>><?= esc($level) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div>
                        <select id="teaching_mode" name="teaching_mode" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                            <option value="">Teaching Mode</option>
                            <?php if (!empty($teachingModes)): ?>
                                <?php foreach ($teachingModes as $mode): ?>
                                    <?php
                                        $displayMode = match(strtolower($mode)) {
                                            'online' => 'Online',
                                            'in-person', 'physical' => 'In-Person',
                                            'both', 'both online & physical' => 'Online & In-Person',
                                            default => $mode
                                        };
                                    ?>
                                    <option value="<?= esc($mode) ?>" <?= ($filters['teaching_mode'] ?? '') == $mode ? 'selected' : '' ?>><?= esc($displayMode) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div>
                        <select id="sort_by" name="sort_by" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                            <option value="rating" <?= ($filters['sort_by'] ?? 'rating') == 'rating' ? 'selected' : '' ?>>Sort: Rating</option>
                            <option value="experience" <?= ($filters['sort_by'] ?? '') == 'experience' ? 'selected' : '' ?>>Sort: Experience</option>
                        </select>
                    </div>
                </div>

                <!-- Filter Actions -->
                <div class="flex flex-col sm:flex-row justify-end items-center gap-2 pt-1">
                    <button
                        type="button"
                        onclick="clearFilters()"
                        class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 text-xs font-medium transition-colors"
                    >
                        <i class="fas fa-times-circle mr-1"></i>Clear
                    </button>
                    <button
                        type="submit"
                        id="filterSearchBtn"
                        class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-red-600 transition-colors text-sm font-medium shadow-sm relative"
                    >
                        <span id="filterSearchBtnText"><i class="fas fa-search mr-1.5"></i>Search</span>
                        <span id="filterSearchSpinner" class="hidden"><i class="fas fa-spinner fa-spin mr-1.5"></i>Searching...</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Results Header -->
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-slate-900">
                <?php if (!empty($tutors)): ?>
                    <?= count($tutors) ?> Tutor<?= count($tutors) != 1 ? 's' : '' ?> Found
                <?php else: ?>
                    No Tutors Found
                <?php endif; ?>
            </h2>
        </div>

        <!-- Tutor Cards -->
        <?php if (!empty($tutors)): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($tutors as $tutor): ?>
                    <a href="<?= site_url('tutor/' . (!empty($tutor['username']) ? $tutor['username'] : $tutor['id'])) ?>" class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-500 block group overflow-hidden transform hover:-translate-y-1 relative">
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
                                    <h3 class="text-xl font-bold text-slate-800 group-hover:text-primary transition-colors duration-300 leading-tight">
                                        <?= esc($tutor['first_name'] . ' ' . $tutor['last_name']) ?>
                                    </h3>
                                    <p class="text-sm text-slate-500 font-medium capitalize mt-1">
                                        Verified Tutor
                                    </p>
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
                                </div>

                                <!-- CTA Button -->
                                <div class="mt-6 pt-4 border-t border-slate-100">
                                    <div class="bg-gradient-to-r from-primary to-orange-600 text-white px-4 py-2 rounded-lg font-semibold text-sm shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-300 flex items-center justify-center w-full">
                                        <i class="fas fa-arrow-right mr-2"></i>
                                        View Profile
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>



        <?php else: ?>
            <!-- No Results -->
            <div class="text-center py-16">
                <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user-slash text-3xl text-slate-400"></i>
                </div>
                <h3 class="text-lg font-semibold text-slate-900 mb-2">No tutors found</h3>
                <p class="text-slate-600 mb-6 text-sm">
                    Try adjusting your filters or search criteria
                </p>
                <button
                    onclick="clearFilters()"
                    class="px-5 py-2.5 bg-primary text-white rounded-lg hover:bg-red-600 transition-colors text-sm font-medium"
                >
                    Clear Filters
                </button>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function clearFilters() {
            // Reset all form inputs
            document.getElementById('name').value = '';
            document.getElementById('district').selectedIndex = 0;
            document.getElementById('curriculum').selectedIndex = 0;
            document.getElementById('subject').selectedIndex = 0;
            document.getElementById('level').selectedIndex = 0;
            document.getElementById('teaching_mode').selectedIndex = 0;
            document.getElementById('sort_by').selectedIndex = 0;
        }

        // Add spinner functionality to search button
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const searchBtn = document.getElementById('filterSearchBtn');
            const btnText = document.getElementById('filterSearchBtnText');
            const spinner = document.getElementById('filterSearchSpinner');

            if (form && searchBtn) {
                form.addEventListener('submit', function(e) {
                    // Show spinner and disable button
                    searchBtn.disabled = true;
                    btnText.classList.add('hidden');
                    spinner.classList.remove('hidden');
                });
            }
        });
    </script>
</div>

<!-- Call to Action - Full Width -->
<section class="overflow-hidden mt-12">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-0 items-stretch">
        <!-- Left Side - Image -->
        <div class="hidden lg:block relative bg-cover bg-center h-full min-h-[400px]" style="background-image: url('<?= base_url('uploads/slider/pexels-vlada-karpovich-7368295.jpg') ?>'); background-position: center; background-size: cover;">
            <div class="absolute inset-0 bg-gradient-to-r from-primary/10 to-transparent"></div>
        </div>

        <!-- Right Side - Content -->
        <div class="bg-gradient-to-r from-primary to-orange-600 text-white flex items-center px-8 sm:px-12 lg:px-16 py-16">
            <div class="w-full">
                <h2 class="text-3xl lg:text-4xl font-extrabold mb-4 leading-tight">Are You a Tutor?</h2>
                <p class="text-lg text-red-100 mb-8 leading-relaxed">
                    Join thousands of verified tutors on TutorConnect Malawi. Grow your tutoring business, connect with students, and earn directly.
                </p>

                <div class="space-y-3 lg:space-y-0 lg:flex lg:gap-4">
                    <a href="<?= site_url('register') ?>" class="block px-8 py-3 bg-white text-primary font-bold rounded-lg hover:bg-red-50 transition text-center lg:inline-block text-sm">
                        <i class="fas fa-user-plus mr-2"></i>Register Now
                    </a>
                    <a href="<?= site_url('how-it-works') ?>" class="block px-8 py-3 bg-red-800 hover:bg-red-900 text-white font-bold rounded-lg transition text-center lg:inline-block text-sm">
                        <i class="fas fa-info-circle mr-2"></i>Learn More
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
