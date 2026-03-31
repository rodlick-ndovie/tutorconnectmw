<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<section class="bg-gradient-to-r from-primary to-orange-600 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                <i class="fas fa-graduation-cap mr-4 text-yellow-300"></i>
                Educational Resources
            </h1>
            <p class="text-xl text-red-100 max-w-3xl mx-auto">
                Access past exam papers and video solutions to excel in your studies
            </p>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-12 bg-gray-50">

    <!-- Resource Type Tabs -->
    <div class="mb-8 flex justify-center px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-1 flex">
            <a href="<?= site_url('resources?type=all') ?>"
               class="px-6 py-3 rounded-md font-medium transition <?= $resource_type === 'all' ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' ?>">
                <i class="fas fa-th-large mr-2"></i>All Resources
            </a>
                <a href="<?= site_url('resources?type=papers') ?>"
                   class="px-6 py-3 rounded-md font-medium transition <?= $resource_type === 'papers' ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' ?>">
                    <i class="fas fa-file-pdf mr-2"></i>Past Papers
                </a>
                <a href="<?= site_url('resources?type=videos') ?>"
                   class="px-6 py-3 rounded-md font-medium transition <?= $resource_type === 'videos' ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' ?>">
                    <i class="fas fa-video mr-2"></i>Video Solutions
                </a>
            </div>
        </div>

    <!-- Advanced Filters -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-filter mr-3 text-primary"></i>
                Filter Resources
            </h2>

            <form method="GET" action="<?= site_url('resources') ?>" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <!-- Hidden type field -->
                <input type="hidden" name="type" value="<?= esc($resource_type) ?>">

                <!-- Exam Body Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Exam Body</label>
                    <select name="exam_body" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary">
                        <option value="">All Exam Bodies</option>
                        <option value="MANEB" <?= ($filters['exam_body'] ?? '') === 'MANEB' ? 'selected' : '' ?>>MANEB</option>
                        <option value="Cambridge" <?= ($filters['exam_body'] ?? '') === 'Cambridge' ? 'selected' : '' ?>>Cambridge</option>
                        <option value="GCSE" <?= ($filters['exam_body'] ?? '') === 'GCSE' ? 'selected' : '' ?>>GCSE</option>
                        <option value="Other" <?= ($filters['exam_body'] ?? '') === 'Other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>

                <!-- Exam Level Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Exam Level</label>
                    <select name="exam_level" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary">
                        <option value="">All Levels</option>
                        <option value="JCE" <?= ($filters['exam_level'] ?? '') === 'JCE' ? 'selected' : '' ?>>JCE</option>
                        <option value="MSCE" <?= ($filters['exam_level'] ?? '') === 'MSCE' ? 'selected' : '' ?>>MSCE</option>
                        <option value="IGCSE" <?= ($filters['exam_level'] ?? '') === 'IGCSE' ? 'selected' : '' ?>>IGCSE</option>
                        <option value="AS-Level" <?= ($filters['exam_level'] ?? '') === 'AS-Level' ? 'selected' : '' ?>>AS-Level</option>
                        <option value="A-Level" <?= ($filters['exam_level'] ?? '') === 'A-Level' ? 'selected' : '' ?>>A-Level</option>
                        <option value="Primary" <?= ($filters['exam_level'] ?? '') === 'Primary' ? 'selected' : '' ?>>Primary</option>
                    </select>
                </div>

                <!-- Subject Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                    <select name="subject" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary">
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

                <!-- Year Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Year</label>
                    <select name="year" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary">
                        <option value="">All Years</option>
                        <?php if (!empty($filter_options['years'])): ?>
                            <?php foreach ($filter_options['years'] as $year): ?>
                                <option value="<?= esc($year['year']) ?>" <?= ($filters['year'] ?? '') == $year['year'] ? 'selected' : '' ?>>
                                    <?= esc($year['year']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <!-- Search and Buttons -->
                <div class="flex flex-col space-y-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" name="search" value="<?= esc($filters['search'] ?? '') ?>"
                           placeholder="Title, subject..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary">
                </div>

                <div class="flex flex-col space-y-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">&nbsp;</label>
                    <div class="flex space-x-2">
                        <button type="submit" class="px-3 py-2 bg-primary text-white rounded-md hover:bg-red-700 transition text-sm font-medium">
                            <i class="fas fa-search mr-1"></i>Search
                        </button>
                        <a href="<?= site_url('resources?type=' . $resource_type) ?>" class="px-3 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition text-sm font-medium">
                            <i class="fas fa-times mr-1"></i>Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Results Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php
        $showPapers = ($resource_type === 'all' || $resource_type === 'papers') && !empty($papers);
        $showVideos = ($resource_type === 'all' || $resource_type === 'videos') && !empty($videos);
        ?>

        <?php if ($showPapers || $showVideos): ?>

            <!-- Past Papers Section -->
            <?php if ($showPapers): ?>
                <div class="mb-12">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                            <i class="fas fa-file-pdf mr-3 text-primary"></i>
                            Past Papers
                            <span class="ml-2 bg-red-100 text-red-800 text-sm font-medium px-2.5 py-0.5 rounded">
                                <?= count($papers) ?> available
                            </span>
                        </h2>
                        <?php if ($resource_type === 'all'): ?>
                            <a href="<?= site_url('resources?type=papers') ?>" class="text-primary hover:text-red-700 font-medium">
                                View all papers <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        <?php endif; ?>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php
                        $displayPapers = $resource_type === 'all' ? array_slice($papers, 0, 6) : $papers;
                        foreach ($displayPapers as $paper):
                        ?>
                            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden">
                                <!-- Header -->
                                <div class="bg-gradient-to-r from-primary to-red-600 text-white p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold text-lg line-clamp-2"><?= esc($paper['paper_title']) ?></h3>
                                            <p class="text-red-100 text-sm">
                                                <?= esc($paper['exam_body']) ?> • <?= esc($paper['exam_level']) ?>
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-2xl font-bold"><?= esc($paper['year']) ?></div>
                                            <div class="text-xs text-red-100">Year</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="p-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-book mr-1"></i>
                                            <?= esc($paper['subject']) ?>
                                        </span>
                                        <?php if (!empty($paper['paper_code'])): ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <i class="fas fa-hashtag mr-1"></i>
                                                <?= esc($paper['paper_code']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="flex items-center justify-between text-sm text-gray-600 mb-3">
                                        <span>
                                            <i class="fas fa-file mr-1"></i>
                                            <?= esc($paper['file_size'] ?? 'Unknown size') ?>
                                        </span>
                                        <span>
                                            <i class="fas fa-download mr-1"></i>
                                            <?= number_format($paper['download_count'] ?? 0) ?> downloads
                                        </span>
                                    </div>

                                    <div class="text-sm text-gray-500 mb-3">
                                        <i class="fas fa-user mr-1"></i>
                                        <?php if (!empty($paper['first_name']) || !empty($paper['last_name'])): ?>
                                            Added by <?= esc($paper['first_name'] . ' ' . $paper['last_name']) ?>
                                        <?php else: ?>
                                            Added by TutorConnect Malawi
                                        <?php endif; ?>
                                    </div>

                                    <!-- Download Button -->
                                    <a href="<?= site_url('resources/past-papers/download/' . $paper['id']) ?>"
                                       class="w-full bg-green-600 text-white py-3 px-4 rounded-lg hover:bg-green-700 transition flex items-center justify-center font-medium"
                                       target="_blank">
                                        <i class="fas fa-download mr-2"></i>
                                        Download PDF
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Video Solutions Section -->
            <?php if ($showVideos): ?>
                <div class="mb-12">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                            <i class="fas fa-video mr-3 text-primary"></i>
                            Video Solutions
                            <span class="ml-2 bg-red-100 text-red-800 text-sm font-medium px-2.5 py-0.5 rounded">
                                <?= count($videos) ?> available
                            </span>
                        </h2>
                        <?php if ($resource_type === 'all'): ?>
                            <a href="<?= site_url('resources?type=videos') ?>" class="text-primary hover:text-red-700 font-medium">
                                View all videos <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- Featured Videos Carousel (only show on 'all' or 'videos' page) -->
                    <?php if (!empty($featured_videos) && ($resource_type === 'all' || $resource_type === 'videos')): ?>
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-star mr-2 text-yellow-500"></i>
                                Featured Solutions
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <?php
                                $displayFeatured = array_slice($featured_videos, 0, 3);
                                foreach ($displayFeatured as $video):
                                ?>
                                    <a href="<?= site_url('resources/video/' . $video['id']) ?>" class="group">
                                        <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1">
                                            <!-- Video Thumbnail -->
                                            <div class="relative bg-gradient-to-br from-gray-100 to-gray-200 h-48 flex items-center justify-center overflow-hidden">
                                                <?php if ($video['video_platform'] === 'youtube'): ?>
                                                    <img src="https://img.youtube.com/vi/<?= esc($video['video_id']) ?>/mqdefault.jpg"
                                                         alt="<?= esc($video['title']) ?>"
                                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                                <?php endif; ?>

                                                <!-- Play Button Overlay -->
                                                <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                    <div class="w-16 h-16 bg-white bg-opacity-90 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-play text-2xl text-gray-900 ml-1"></i>
                                                    </div>
                                                </div>

                                                <!-- Featured Badge -->
                                                <div class="absolute top-3 left-3">
                                                    <span class="bg-gradient-to-r from-primary to-red-600 text-white px-3 py-1 rounded-full text-xs font-bold flex items-center shadow-lg">
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
                                                <h3 class="font-bold text-lg text-gray-900 group-hover:text-primary transition-colors mb-2 line-clamp-2">
                                                    <?= esc($video['title']) ?>
                                                </h3>

                                                <div class="flex items-center justify-between mb-3">
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        <i class="fas fa-graduation-cap mr-1"></i>
                                                        <?= esc($video['exam_body']) ?>
                                                    </span>
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        <i class="fas fa-book mr-1"></i>
                                                        <?= esc($video['subject']) ?>
                                                    </span>
                                                </div>

                                                <div class="flex items-center text-sm text-gray-600 mb-3">
                                                    <i class="fas fa-user mr-2 text-gray-400"></i>
                                                    <span class="font-medium"><?= esc($video['first_name'] . ' ' . $video['last_name']) ?></span>
                                                </div>

                                                <!-- CTA Button -->
                                                <div class="bg-purple-600 text-white py-2 px-3 rounded-lg font-medium text-sm hover:bg-purple-700 transition flex items-center justify-center">
                                                    <i class="fas fa-play mr-2"></i>Watch Now
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Regular Videos Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        <?php
                        $displayVideos = $resource_type === 'all' ? array_slice($videos, 0, 8) : $videos;
                        foreach ($displayVideos as $video):
                        ?>
                            <a href="<?= site_url('resources/video/' . $video['id']) ?>" class="group">
                                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1">
                                    <!-- Video Thumbnail -->
                                    <div class="relative bg-gradient-to-br from-gray-100 to-gray-200 h-40 flex items-center justify-center overflow-hidden">
                                        <?php if ($video['video_platform'] === 'youtube'): ?>
                                            <img src="https://img.youtube.com/vi/<?= esc($video['video_id']) ?>/mqdefault.jpg"
                                                 alt="<?= esc($video['title']) ?>"
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
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
                                                <span class="bg-blue-500 text-white px-2 py-1 rounded-full text-xs font-bold">
                                                    Verified
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Content -->
                                    <div class="p-4">
                                        <h3 class="font-semibold text-gray-900 group-hover:text-purple-600 transition-colors mb-2 line-clamp-2 leading-tight">
                                            <?= esc($video['title']) ?>
                                        </h3>

                                        <div class="flex items-center justify-between mb-2">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                <?= esc($video['exam_body']) ?>
                                            </span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <?= esc($video['subject']) ?>
                                            </span>
                                        </div>

                                        <div class="flex items-center text-xs text-gray-600 mb-3">
                                            <i class="fas fa-user mr-1 text-gray-400"></i>
                                            <span class="font-medium truncate"><?= esc($video['first_name'] . ' ' . $video['last_name']) ?></span>
                                        </div>

                                        <!-- CTA Button -->
                                        <div class="bg-purple-600 text-white py-2 px-3 rounded-lg font-medium text-sm hover:bg-purple-700 transition flex items-center justify-center">
                                            <i class="fas fa-play mr-2"></i>Watch Solution
                                        </div>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <!-- No Results -->
            <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <?php if ($resource_type === 'papers'): ?>
                        <i class="fas fa-file-pdf text-4xl text-gray-400"></i>
                    <?php elseif ($resource_type === 'videos'): ?>
                        <i class="fas fa-video text-4xl text-gray-400"></i>
                    <?php else: ?>
                        <i class="fas fa-graduation-cap text-4xl text-gray-400"></i>
                    <?php endif; ?>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">
                    <?php if (!empty(array_filter($filters))): ?>
                        No resources match your filters
                    <?php else: ?>
                        Resources coming soon
                    <?php endif; ?>
                </h3>
                <p class="text-gray-600 mb-6">
                    <?php if (!empty(array_filter($filters))): ?>
                        Try adjusting your search criteria or clearing the filters to see all available resources.
                    <?php else: ?>
                        We're building our educational resource library. Check back soon for past papers and video solutions!
                    <?php endif; ?>
                </p>
                <?php if (!empty(array_filter($filters))): ?>
                    <a href="<?= site_url('resources?type=' . $resource_type) ?>" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-times mr-2"></i>Clear Filters
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Call to Action - Full Width -->
<section class="overflow-hidden">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-0 items-stretch">
        <!-- Left Side - Image -->
        <div class="hidden lg:block relative bg-cover bg-center h-full min-h-[400px]" style="background-image: url('<?= base_url('uploads/slider/pexels-katerina-holmes-5905718.jpg') ?>'); background-position: center; background-size: cover;">
            <div class="absolute inset-0 bg-gradient-to-r from-primary/10 to-transparent"></div>
        </div>

        <!-- Right Side - Content -->
        <div class="bg-gradient-to-r from-primary to-red-600 text-white flex items-center px-8 sm:px-12 lg:px-16 py-16">
            <div class="w-full">
                <h2 class="text-3xl lg:text-4xl font-extrabold mb-4 leading-tight">Ready to Get Ahead?</h2>
                <p class="text-lg text-red-100 mb-8 leading-relaxed">
                    Connect with verified tutors who specialize in your subjects and exam preparation.
                    Get one-on-one guidance tailored to your learning needs.
                </p>

                <div class="space-y-3 lg:space-y-0 lg:flex lg:gap-4">
                    <a href="<?= site_url('find-tutors') ?>" class="block px-8 py-3 bg-white text-primary font-bold rounded-lg hover:bg-red-50 transition text-center lg:inline-block text-sm">
                        <i class="fas fa-search mr-2"></i>Find a Tutor
                    </a>
                    <a href="tel:+265992313978" class="block px-8 py-3 bg-red-800 hover:bg-red-900 text-white font-bold rounded-lg transition text-center lg:inline-block text-sm">
                        <i class="fas fa-phone mr-2"></i>Call for Help
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

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
