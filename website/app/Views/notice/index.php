<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="bg-gradient-to-r from-primary to-red-600 text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-5xl md:text-6xl font-extrabold mb-4">
            <i class="fas fa-bullhorn mr-3"></i>School Notices & Announcements
        </h1>
        <p class="text-xl text-red-100 max-w-3xl mx-auto">Browse the latest notices, vacancies, and announcements from schools across Malawi</p>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 py-12">
    <!-- Filter Tabs - Centered -->
    <div class="mb-8 flex flex-wrap gap-3 justify-center">
        <a href="<?= site_url('notice') ?>"
           class="px-6 py-2 rounded-lg font-semibold transition <?= !isset($currentType) || empty($currentType) ? 'bg-primary text-white' : 'bg-white text-gray-700 hover:bg-gray-50 shadow' ?>">
            All Notices
        </a>
        <a href="<?= site_url('notice?type=Vacancy') ?>"
           class="px-6 py-2 rounded-lg font-semibold transition <?= isset($currentType) && $currentType == 'Vacancy' ? 'bg-primary text-white' : 'bg-white text-gray-700 hover:bg-gray-50 shadow' ?>">
            <i class="fas fa-briefcase mr-2"></i>Job Vacancies
        </a>
        <a href="<?= site_url('notice?type=Announcement') ?>"
           class="px-6 py-2 rounded-lg font-semibold transition <?= isset($currentType) && $currentType == 'Announcement' ? 'bg-primary text-white' : 'bg-white text-gray-700 hover:bg-gray-50 shadow' ?>">
            <i class="fas fa-megaphone mr-2"></i>Announcements
        </a>
        <a href="<?= site_url('notice?type=Notice') ?>"
           class="px-6 py-2 rounded-lg font-semibold transition <?= isset($currentType) && $currentType == 'Notice' ? 'bg-primary text-white' : 'bg-white text-gray-700 hover:bg-gray-50 shadow' ?>">
            <i class="fas fa-file-alt mr-2"></i>General Notices
        </a>
    </div>

    <!-- Post Notice CTA -->
    <div class="bg-gradient-to-r from-primary to-red-600 rounded-2xl p-8 mb-8 text-white">
        <div class="flex flex-col md:flex-row items-center justify-between">
            <div class="mb-4 md:mb-0">
                <h3 class="text-2xl font-bold mb-2">Want to post a notice?</h3>
                <p class="text-red-100">Share your school's announcements, vacancies, and exam results</p>
                <p class="text-sm text-yellow-300 mt-2">
                    <i class="fas fa-gift mr-1"></i> FREE until June 2026!
                </p>
            </div>
            <a href="<?= site_url('notice/create') ?>"
               class="px-8 py-3 bg-white text-primary rounded-lg font-bold hover:bg-red-50 transition shadow-lg">
                <i class="fas fa-plus mr-2"></i>Post a Notice
            </a>
        </div>
    </div>

    <!-- Notices Grid -->
    <?php if (empty($notices)): ?>
        <div class="bg-white rounded-xl shadow-lg p-12 text-center">
            <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">No Notices Found</h3>
            <p class="text-gray-500">No notices available at the moment. Check back soon!</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($notices as $notice): ?>
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all transform hover:-translate-y-1">
                    <?php if (!empty($notice['attached_image'])): ?>
                        <img src="<?= base_url($notice['attached_image']) ?>"
                             alt="<?= esc($notice['notice_title']) ?>"
                             class="w-full h-48 object-cover">
                    <?php endif; ?>

                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
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

                        <h3 class="text-xl font-bold text-gray-900 mb-2">
                            <?= esc($notice['notice_title']) ?>
                        </h3>

                        <div class="flex items-center text-sm text-gray-700 mb-3">
                            <i class="fas fa-school text-primary mr-2"></i>
                            <span class="font-semibold"><?= esc($notice['school_name']) ?></span>
                        </div>

                        <div class="text-sm text-gray-600 mb-2">
                            <i class="fas fa-tag mr-2"></i><?= esc($notice['school_type']) ?>
                        </div>

                        <p class="text-gray-600 mb-4 line-clamp-3">
                            <?= esc(substr($notice['notice_content'], 0, 120)) ?>...
                        </p>

                        <div class="flex items-center justify-between pt-4 border-t">
                            <div class="text-xs text-gray-500">
                                <i class="fas fa-eye mr-1"></i><?= number_format($notice['views_count']) ?> views
                            </div>
                            <a href="<?= site_url('notice/view/' . $notice['id']) ?>"
                               class="text-primary hover:text-red-600 font-semibold text-sm">
                                Read More <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Call to Action - Full Width -->
<section class="overflow-hidden mt-12">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-0 items-stretch">
        <!-- Left Side - Image -->
        <div class="hidden lg:block relative bg-cover bg-center h-full min-h-[400px]" style="background-image: url('<?= base_url('uploads/slider/pexels-katerina-holmes-5905740.jpg') ?>'); background-position: center; background-size: cover;">
            <div class="absolute inset-0 bg-gradient-to-r from-primary/10 to-transparent"></div>
        </div>

        <!-- Right Side - Content -->
        <div class="bg-gradient-to-r from-primary to-red-600 text-white flex items-center px-8 sm:px-12 lg:px-16 py-16">
            <div class="w-full">
                <h2 class="text-3xl lg:text-4xl font-extrabold mb-4 leading-tight">Connect with the Education Community</h2>
                <p class="text-lg text-red-100 mb-8 leading-relaxed">
                    <strong>For Tutors:</strong> Post job opportunities and announcements to reach qualified educators.<br>
                    <strong>For Students & Parents:</strong> Stay updated with the latest school notices and opportunities.
                </p>

                <div class="space-y-3 lg:space-y-0 lg:flex lg:gap-4">
                    <a href="<?= site_url('notice/create') ?>" class="block px-8 py-3 bg-white text-primary font-bold rounded-lg hover:bg-red-50 transition text-center lg:inline-block text-sm">
                        <i class="fas fa-plus mr-2"></i>Post a Notice
                    </a>
                    <a href="tel:+265992313978" class="block px-8 py-3 bg-red-800 hover:bg-red-900 text-white font-bold rounded-lg transition text-center lg:inline-block text-sm">
                        <i class="fas fa-phone mr-2"></i>Contact Us
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<?= $this->endSection() ?>
