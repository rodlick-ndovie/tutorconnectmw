<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<section class="bg-gradient-to-br from-orange-600 via-orange-700 to-red-800 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                <i class="fas fa-video mr-4 text-yellow-300"></i>
                Video Solutions
            </h1>
            <p class="text-xl text-orange-100 max-w-3xl mx-auto">
                Watch step-by-step video solutions from verified tutors to master difficult exam questions
            </p>
        </div>
    </div>
</section>

<!-- Featured Videos Carousel -->
<?php if (!empty($featured_videos)): ?>
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Featured Solutions</h2>
            <p class="text-gray-600">Premium video solutions from top-rated tutors</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($featured_videos as $video): ?>
                <a href="<?= site_url('resources/video/' . $video['id']) ?>" class="group">
                    <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1">
                        <!-- Video Thumbnail -->
                        <div class="relative bg-gradient-to-br from-gray-100 to-gray-200 h-48 flex items-center justify-center overflow-hidden">
                            <?php if ($video['video_platform'] === 'youtube'): ?>
                                <img src="https://img.youtube.com/vi/<?= esc($video['video_id']) ?>/mqdefault.jpg"
                                     alt="<?= esc($video['title']) ?>"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <?php else: ?>
                                <div class="flex items-center justify-center w-full h-full bg-gray-200">
                                    <i class="fab fa-vimeo text-4xl text-gray-400"></i>
                                </div>
                            <?php endif; ?>

                            <!-- Play Button Overlay -->
                            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <div class="w-16 h-16 bg-white bg-opacity-90 rounded-full flex items-center justify-center">
                                    <i class="fas fa-play text-2xl text-gray-900 ml-1"></i>
                                </div>
                            </div>

                            <!-- Featured Badge -->
                            <div class="absolute top-3 left-3">
                                <span class="bg-gradient-to-r from-orange-500 to-red-500 text-white px-3 py-1 rounded-full text-xs font-bold flex items-center shadow-lg">
                                    <i class="fas fa-star mr-1"></i>Featured
                                </span>
                            </div>

                            <!-- View Count -->
                            <div class="absolute top-3 right-3">
                                <span class="bg-black bg-opacity-70 text-white px-2 py-1 rounded-full text-xs font-medium flex items-center">
                                    <i class="fas fa-eye mr-1"></i>
                                    <?= number_format($video['view_count'] ?? 0) ?>
                                </span>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-4">
                            <h3 class="font-bold text-lg text-gray-900 group-hover:text-orange-600 transition-colors mb-2 line-clamp-2">
                                <?= esc($video['title']) ?>
                            </h3>

                            <div class="flex items-center justify-between mb-3">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                    <i class="fas fa-graduation-cap mr-1"></i>
                                    <?= esc($video['exam_body']) ?>
                                </span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                    <i class="fas fa-book mr-1"></i>
                                    <?= esc($video['subject']) ?>
                                </span>
                            </div>

                            <div class="flex items-center text-sm text-gray-600 mb-3">
                                <i class="fas fa-user mr-2 text-gray-400"></i>
                                <span class="font-medium"><?= esc($video['first_name'] . ' ' . $video['last_name']) ?></span>
                            </div>

                            <?php if (!empty($video['description'])): ?>
                                <p class="text-sm text-gray-600 line-clamp-2 mb-3">
                                    <?= esc(substr($video['description'], 0, 100)) ?>...
                                </p>
                            <?php endif; ?>

                            <!-- CTA Button -->
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Click to watch</span>
                                <div class="bg-orange-600 text-white px-4 py-2 rounded-lg font-semibold text-sm hover:bg-orange-700 transition flex items-center">
                                    <i class="fas fa-play mr-2"></i>Watch Now
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Filters and All Videos -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-filter mr-3 text-orange-600"></i>
                Filter Videos
            </h2>

            <form method="GET" action="<?= site_url('resources/video-solutions') ?>" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Subject Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                    <select name="subject" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500">
                        <option value="">All Subjects</option>
                        <?php if (!empty($filter_options['subjects'])): ?>
                            <?php foreach ($filter_options['subjects'] as $subject): ?>
                                <option value="<?= esc($subject['subject']) ?>" <?= ($filters['subject'] ?? '') === $subject['subject'] ? 'selected' : '' ?>>
                                    <?= esc($subject['subject']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <!-- Exam Body Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Exam Body</label>
                    <select name="exam_body" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500">
                        <option value="">All Exam Bodies</option>
                        <option value="MANEB" <?= ($filters['exam_body'] ?? '') === 'MANEB' ? 'selected' : '' ?>>MANEB</option>
                        <option value="Cambridge" <?= ($filters['exam_body'] ?? '') === 'Cambridge' ? 'selected' : '' ?>>Cambridge</option>
                        <option value="GCSE" <?= ($filters['exam_body'] ?? '') === 'GCSE' ? 'selected' : '' ?>>GCSE</option>
                        <option value="Other" <?= ($filters['exam_body'] ?? '') === 'Other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>

                <!-- Search and Buttons -->
                <div class="flex flex-col space-y-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" name="search" value="<?= esc($filters['search'] ?? '') ?>"
                           placeholder="Video title..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500">

                    <div class="flex space-x-2 pt-6">
                        <button type="submit" class="flex-1 bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700 transition">
                            <i class="fas fa-search mr-2"></i>Search
                        </button>
                        <a href="<?= site_url('resources/video-solutions') ?>" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
                            <i class="fas fa-times mr-2"></i>Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Results Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">
                <?php if (!empty($videos)): ?>
                    Found <?= count($videos) ?> video<?= count($videos) !== 1 ? 's' : '' ?>
                <?php else: ?>
                    All Video Solutions
                <?php endif; ?>
            </h2>
            <div class="flex items-center space-x-2 bg-white shadow px-4 py-2 rounded-lg text-sm text-gray-700">
                <i class="fas fa-list-ol text-orange-600"></i>
                <span><strong>Total:</strong> <?= isset($videos) ? count($videos) : 0 ?></span>
            </div>
        </div>

        <!-- Videos Grid -->
        <?php if (!empty($videos)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php foreach ($videos as $video): ?>
                    <a href="<?= site_url('resources/video/' . $video['id']) ?>" class="group">
                        <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1">
                            <!-- Video Thumbnail -->
                            <div class="relative bg-gradient-to-br from-gray-100 to-gray-200 h-40 flex items-center justify-center overflow-hidden">
                                <?php if ($video['video_platform'] === 'youtube'): ?>
                                    <img src="https://img.youtube.com/vi/<?= esc($video['video_id']) ?>/mqdefault.jpg"
                                         alt="<?= esc($video['title']) ?>"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                <?php else: ?>
                                    <div class="flex items-center justify-center w-full h-full bg-gray-200">
                                        <i class="fab fa-vimeo text-3xl text-gray-400"></i>
                                    </div>
                                <?php endif; ?>

                                <!-- Play Button Overlay -->
                                <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <div class="w-12 h-12 bg-white bg-opacity-90 rounded-full flex items-center justify-center">
                                        <i class="fas fa-play text-xl text-gray-900 ml-0.5"></i>
                                    </div>
                                </div>

                                <!-- View Count -->
                                <div class="absolute top-2 right-2">
                                    <span class="bg-black bg-opacity-70 text-white px-2 py-1 rounded-full text-xs font-medium flex items-center">
                                        <i class="fas fa-eye mr-1"></i>
                                        <?= number_format($video['view_count'] ?? 0) ?>
                                    </span>
                                </div>

                            <!-- Featured Badge (for non-carousel videos) -->
                            <?php if (($video['featured_level'] ?? '') === 'standard'): ?>
                                    <div class="absolute top-2 left-2">
                                        <span class="bg-orange-500 text-white px-2 py-1 rounded-full text-xs font-bold">
                                            Verified
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Content -->
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-900 group-hover:text-orange-600 transition-colors mb-2 line-clamp-2 leading-tight">
                                    <?= esc($video['title']) ?>
                                </h3>

                                <div class="flex items-center justify-between mb-2">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        <?= esc($video['exam_body']) ?>
                                    </span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        <?= esc($video['subject']) ?>
                                    </span>
                                </div>

                                <div class="flex items-center text-xs text-gray-600 mb-3">
                                    <i class="fas fa-user mr-1 text-gray-400"></i>
                                    <span class="font-medium truncate"><?= esc($video['first_name'] . ' ' . $video['last_name']) ?></span>
                                </div>

                                <!-- CTA Button -->
                                <div class="bg-orange-600 text-white py-2 px-3 rounded-lg font-medium text-sm hover:bg-orange-700 transition flex items-center justify-center">
                                    <i class="fas fa-play mr-2"></i>Watch Solution
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <!-- No Results -->
            <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-video text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No Video Solutions Found</h3>
                <p class="text-gray-600 mb-6">
                    <?php if (!empty(array_filter($filters))): ?>
                        No videos match your current filters. Try adjusting your search criteria.
                    <?php else: ?>
                        Our tutors are working on creating video solutions. Check back soon for helpful content!
                    <?php endif; ?>
                </p>
                <?php if (!empty(array_filter($filters))): ?>
                    <a href="<?= site_url('resources/video-solutions') ?>" class="bg-orange-600 text-white px-6 py-3 rounded-lg hover:bg-orange-700 transition">
                        <i class="fas fa-times mr-2"></i>Clear Filters
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Call to Action -->
        <div class="mt-12 bg-gradient-to-r from-orange-600 to-red-600 rounded-xl p-8 text-center text-white">
            <h3 class="text-2xl font-bold mb-4">Become a Tutor & Share Your Knowledge</h3>
            <p class="text-orange-100 mb-6 max-w-2xl mx-auto">
                Help students excel by creating video solutions. Join our community of verified tutors and earn from your expertise.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?= site_url('register') ?>" class="inline-flex items-center bg-white text-orange-600 px-6 py-3 rounded-lg hover:bg-gray-100 transition font-semibold">
                    <i class="fas fa-user-plus mr-2"></i>Join as Tutor
                </a>
                <a href="<?= site_url('find-tutors') ?>" class="inline-flex items-center bg-orange-800 text-white px-6 py-3 rounded-lg hover:bg-orange-900 transition font-semibold">
                    <i class="fas fa-search mr-2"></i>Find a Tutor
                </a>
            </div>
        </div>

    </div>
</section>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Custom animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
    animation: fadeIn 0.6s ease-out;
}
</style>

<script>
// Auto-submit form on filter change (optional enhancement)
document.addEventListener('DOMContentLoaded', function() {
    const filterSelects = document.querySelectorAll('select[name]');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            // Optional: Auto-submit on change
            // this.closest('form').submit();
        });
    });
});
</script>

<?= $this->endSection() ?>
