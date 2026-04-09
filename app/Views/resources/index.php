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
        <div class="w-full max-w-4xl rounded-2xl border border-gray-200 bg-white p-2 shadow-sm">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-center">
            <a href="<?= site_url('resources?type=all') ?>"
               class="inline-flex items-center justify-center rounded-xl px-6 py-3 text-sm font-semibold transition <?= $resource_type === 'all' ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-gray-700 hover:bg-gray-100' ?>">
                <i class="fas fa-th-large mr-2"></i>All Resources
            </a>
                <a href="<?= site_url('resources?type=papers') ?>"
                   class="inline-flex items-center justify-center rounded-xl px-6 py-3 text-sm font-semibold transition <?= $resource_type === 'papers' ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-gray-700 hover:bg-gray-100' ?>">
                    <i class="fas fa-file-pdf mr-2"></i>Past Papers
                </a>
                <a href="<?= site_url('resources?type=videos') ?>"
                   class="inline-flex items-center justify-center rounded-xl px-6 py-3 text-sm font-semibold transition <?= $resource_type === 'videos' ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-gray-700 hover:bg-gray-100' ?>">
                    <i class="fas fa-video mr-2"></i>Video Solutions
                </a>
            </div>
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
        $papersPagination = $papers_pagination ?? [];
        $videosPagination = $videos_pagination ?? [];
        $paperCount = $papersPagination['total_items'] ?? count($papers ?? []);
        $videoCount = $videosPagination['total_items'] ?? count($videos ?? []);
        ?>

        <?php if ($showPapers || $showVideos): ?>

            <!-- Past Papers Section -->
            <?php if ($showPapers): ?>
                <div class="mb-12">
                    <div class="mb-6 flex items-center justify-between gap-4">
                        <h2 class="flex items-center text-2xl font-bold text-gray-900">
                            <i class="fas fa-file-pdf mr-3 text-primary"></i>
                            Past Papers
                            <span class="ml-3 rounded-full bg-red-100 px-3 py-1 text-sm font-semibold text-red-800">
                                <?= $paperCount ?> available
                            </span>
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach (($papers ?? []) as $paper): ?>
                            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                                <div class="border-b border-red-100 bg-gradient-to-br from-white via-red-50 to-red-100 p-5">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="line-clamp-2 text-lg font-bold text-gray-900"><?= esc($paper['paper_title']) ?></h3>
                                            <p class="mt-1 text-sm text-gray-600">
                                                <?= esc($paper['exam_body']) ?> • <?= esc($paper['exam_level']) ?>
                                            </p>
                                        </div>
                                        <div class="rounded-2xl bg-white px-4 py-3 text-right shadow-sm ring-1 ring-red-100">
                                            <div class="text-2xl font-bold text-primary"><?= esc($paper['year']) ?></div>
                                            <div class="text-xs font-medium uppercase tracking-wide text-gray-500">Year</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-5">
                                    <?php
                                    $requiresPayment = !empty($paper['is_paid']);
                                    $hasPaidAccess = !empty($paper['has_paid_access']);
                                    ?>
                                    <div class="mb-4 flex flex-wrap items-center gap-2">
                                        <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700 ring-1 ring-blue-100">
                                            <i class="fas fa-book mr-1"></i>
                                            <?= esc($paper['subject']) ?>
                                        </span>
                                        <?php if (!empty($paper['paper_code'])): ?>
                                            <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700 ring-1 ring-gray-200">
                                                <i class="fas fa-hashtag mr-1"></i>
                                                <?= esc($paper['paper_code']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="mb-4 grid grid-cols-2 gap-3 rounded-2xl bg-gray-50 p-3 text-sm text-gray-600">
                                        <div>
                                            <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">File Size</div>
                                            <div class="mt-1 font-medium text-gray-800">
                                                <i class="fas fa-file mr-1 text-primary"></i>
                                                <?= esc($paper['file_size'] ?? 'Unknown size') ?>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">Downloads</div>
                                            <div class="mt-1 font-medium text-gray-800">
                                                <i class="fas fa-download mr-1 text-primary"></i>
                                                <?= number_format($paper['download_count'] ?? 0) ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-4 flex flex-wrap items-center gap-2">
                                        <?php if ($requiresPayment): ?>
                                            <span class="inline-flex items-center rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-800">
                                                <i class="fas fa-lock mr-1"></i>
                                                Paid • MK <?= number_format((float) ($paper['price'] ?? 0), 0) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Free Download
                                            </span>
                                        <?php endif; ?>

                                        <?php if ($requiresPayment && $hasPaidAccess): ?>
                                            <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800">
                                                <i class="fas fa-unlock mr-1"></i>
                                                Access unlocked
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="mb-4 border-t border-gray-100 pt-4 text-sm text-gray-500">
                                        <i class="fas fa-user mr-1 text-primary"></i>
                                        <?php if (!empty($paper['first_name']) || !empty($paper['last_name'])): ?>
                                            Added by <?= esc($paper['first_name'] . ' ' . $paper['last_name']) ?>
                                        <?php else: ?>
                                            Added by TutorConnect Malawi
                                        <?php endif; ?>
                                    </div>

                                    <a href="<?= ($requiresPayment && !$hasPaidAccess)
                                        ? site_url('resources/past-papers/pay/' . $paper['id'])
                                        : site_url('resources/past-papers/download/' . $paper['id']) ?>"
                                       class="flex w-full items-center justify-center rounded-xl py-3 px-4 font-semibold text-white transition <?= ($requiresPayment && !$hasPaidAccess) ? 'bg-amber-600 hover:bg-amber-700 shadow-lg shadow-amber-100' : 'bg-green-600 hover:bg-green-700 shadow-lg shadow-green-100' ?>">
                                        <i class="fas <?= ($requiresPayment && !$hasPaidAccess) ? 'fa-credit-card' : 'fa-download' ?> mr-2"></i>
                                        <?php if ($requiresPayment && !$hasPaidAccess): ?>
                                            Pay MK <?= number_format((float) ($paper['price'] ?? 0), 0) ?> to Download
                                        <?php else: ?>
                                            Download PDF
                                        <?php endif; ?>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?php if (($papersPagination['total_pages'] ?? 0) > 1): ?>
                        <?php
                        $paperCurrentPage = (int) ($papersPagination['current_page'] ?? 1);
                        $paperTotalPages = (int) ($papersPagination['total_pages'] ?? 1);
                        $paperStartPage = max(1, $paperCurrentPage - 2);
                        $paperEndPage = min($paperTotalPages, $paperCurrentPage + 2);
                        $paperQuery = $_GET ?? [];
                        ?>
                        <div class="mt-8 rounded-2xl border border-gray-200 bg-white px-4 py-5 shadow-sm">
                            <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
                                <p class="text-sm text-gray-600">
                                    Showing <?= $papersPagination['start_item'] ?? 0 ?>-<?= $papersPagination['end_item'] ?? 0 ?> of <?= $paperCount ?> past papers
                                </p>
                                <nav class="flex flex-wrap items-center justify-center gap-2" aria-label="Past papers pagination">
                                    <?php if ($paperCurrentPage > 1): ?>
                                        <?php $paperPrevQuery = array_merge($paperQuery, ['papers_page' => $paperCurrentPage - 1]); ?>
                                        <a href="<?= current_url() . '?' . http_build_query($paperPrevQuery) ?>" class="inline-flex items-center rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-600 transition hover:border-primary hover:text-primary">
                                            <i class="fas fa-chevron-left mr-2 text-xs"></i>Previous
                                        </a>
                                    <?php endif; ?>

                                    <?php if ($paperStartPage > 1): ?>
                                        <?php $paperFirstQuery = array_merge($paperQuery, ['papers_page' => 1]); ?>
                                        <a href="<?= current_url() . '?' . http_build_query($paperFirstQuery) ?>" class="inline-flex h-10 w-10 items-center justify-center rounded-lg border text-sm font-semibold transition <?= $paperCurrentPage === 1 ? 'border-primary bg-primary text-white' : 'border-gray-300 text-gray-700 hover:border-primary hover:text-primary' ?>">1</a>
                                        <?php if ($paperStartPage > 2): ?>
                                            <span class="px-1 text-gray-400">…</span>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <?php for ($page = $paperStartPage; $page <= $paperEndPage; $page++): ?>
                                        <?php $paperPageQuery = array_merge($paperQuery, ['papers_page' => $page]); ?>
                                        <a href="<?= current_url() . '?' . http_build_query($paperPageQuery) ?>" class="inline-flex h-10 w-10 items-center justify-center rounded-lg border text-sm font-semibold transition <?= $page === $paperCurrentPage ? 'border-primary bg-primary text-white shadow-sm' : 'border-gray-300 text-gray-700 hover:border-primary hover:text-primary' ?>" <?= $page === $paperCurrentPage ? 'aria-current="page"' : '' ?>><?= $page ?></a>
                                    <?php endfor; ?>

                                    <?php if ($paperEndPage < $paperTotalPages): ?>
                                        <?php if ($paperEndPage < $paperTotalPages - 1): ?>
                                            <span class="px-1 text-gray-400">…</span>
                                        <?php endif; ?>
                                        <?php $paperLastQuery = array_merge($paperQuery, ['papers_page' => $paperTotalPages]); ?>
                                        <a href="<?= current_url() . '?' . http_build_query($paperLastQuery) ?>" class="inline-flex h-10 w-10 items-center justify-center rounded-lg border text-sm font-semibold transition <?= $paperCurrentPage === $paperTotalPages ? 'border-primary bg-primary text-white' : 'border-gray-300 text-gray-700 hover:border-primary hover:text-primary' ?>"><?= $paperTotalPages ?></a>
                                    <?php endif; ?>

                                    <?php if ($paperCurrentPage < $paperTotalPages): ?>
                                        <?php $paperNextQuery = array_merge($paperQuery, ['papers_page' => $paperCurrentPage + 1]); ?>
                                        <a href="<?= current_url() . '?' . http_build_query($paperNextQuery) ?>" class="inline-flex items-center rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-600 transition hover:border-primary hover:text-primary">
                                            Next<i class="fas fa-chevron-right ml-2 text-xs"></i>
                                        </a>
                                    <?php endif; ?>
                                </nav>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($resource_type === 'all'): ?>
                        <div class="mt-8 flex justify-center">
                            <a href="<?= site_url('resources?type=papers') ?>" class="inline-flex items-center rounded-full border border-primary bg-white px-6 py-3 text-sm font-semibold text-primary transition hover:bg-primary hover:text-white">
                                View all past papers <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Video Solutions Section -->
            <?php if ($showVideos): ?>
                <div class="mb-12">
                    <div class="mb-6 flex items-center justify-between gap-4">
                        <h2 class="flex items-center text-2xl font-bold text-gray-900">
                            <i class="fas fa-video mr-3 text-primary"></i>
                            Video Solutions
                            <span class="ml-3 rounded-full bg-red-100 px-3 py-1 text-sm font-semibold text-red-800">
                                <?= $videoCount ?> available
                            </span>
                        </h2>
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
                                        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl">
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

                                                <div class="absolute top-3 left-3">
                                                    <span class="flex items-center rounded-full bg-gradient-to-r from-primary to-red-600 px-3 py-1 text-xs font-bold text-white shadow-lg">
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

                                            <div class="p-5">
                                                <h3 class="mb-3 line-clamp-2 text-lg font-bold text-gray-900 transition-colors group-hover:text-primary">
                                                    <?= esc($video['title']) ?>
                                                </h3>

                                                <div class="mb-4 flex flex-wrap items-center gap-2">
                                                    <span class="inline-flex items-center rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700 ring-1 ring-red-100">
                                                        <i class="fas fa-graduation-cap mr-1"></i>
                                                        <?= esc($video['exam_body']) ?>
                                                    </span>
                                                    <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700 ring-1 ring-blue-100">
                                                        <i class="fas fa-book mr-1"></i>
                                                        <?= esc($video['subject']) ?>
                                                    </span>
                                                </div>

                                                <div class="mb-4 flex items-center text-sm text-gray-600">
                                                    <i class="fas fa-user mr-2 text-primary"></i>
                                                    <span class="font-medium"><?= esc($video['first_name'] . ' ' . $video['last_name']) ?></span>
                                                </div>

                                                <div class="flex items-center justify-center rounded-xl bg-secondary py-3 px-3 text-sm font-semibold text-white transition hover:bg-accent">
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
                        <?php foreach (($videos ?? []) as $video): ?>
                            <a href="<?= site_url('resources/video/' . $video['id']) ?>" class="group">
                                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl">
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

                                        <div class="absolute top-2 right-2">
                                            <span class="bg-black bg-opacity-70 text-white px-2 py-1 rounded-full text-xs font-medium flex items-center">
                                                <i class="fas fa-eye mr-1"></i>
                                                <?= number_format($video['view_count'] ?? 0) ?>
                                            </span>
                                        </div>

                                        <?php if (($video['featured_level'] ?? '') === 'standard'): ?>
                                            <div class="absolute top-2 left-2">
                                                <span class="rounded-full bg-secondary px-3 py-1 text-xs font-bold text-white shadow-sm">
                                                    Verified
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="p-5">
                                        <h3 class="mb-3 line-clamp-2 leading-tight text-base font-bold text-gray-900 transition-colors group-hover:text-primary">
                                            <?= esc($video['title']) ?>
                                        </h3>

                                        <div class="mb-3 flex flex-wrap items-center gap-2">
                                            <span class="inline-flex items-center rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700 ring-1 ring-red-100">
                                                <?= esc($video['exam_body']) ?>
                                            </span>
                                            <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700 ring-1 ring-blue-100">
                                                <?= esc($video['subject']) ?>
                                            </span>
                                        </div>

                                        <div class="mb-4 flex items-center text-xs text-gray-600">
                                            <i class="fas fa-user mr-1 text-primary"></i>
                                            <span class="font-medium truncate"><?= esc($video['first_name'] . ' ' . $video['last_name']) ?></span>
                                        </div>

                                        <div class="flex items-center justify-center rounded-xl bg-secondary py-3 px-3 text-sm font-semibold text-white transition hover:bg-accent">
                                            <i class="fas fa-play mr-2"></i>Watch Solution
                                        </div>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>

                    <?php if (($videosPagination['total_pages'] ?? 0) > 1): ?>
                        <?php
                        $videoCurrentPage = (int) ($videosPagination['current_page'] ?? 1);
                        $videoTotalPages = (int) ($videosPagination['total_pages'] ?? 1);
                        $videoStartPage = max(1, $videoCurrentPage - 2);
                        $videoEndPage = min($videoTotalPages, $videoCurrentPage + 2);
                        $videoQuery = $_GET ?? [];
                        ?>
                        <div class="mt-8 rounded-2xl border border-gray-200 bg-white px-4 py-5 shadow-sm">
                            <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
                                <p class="text-sm text-gray-600">
                                    Showing <?= $videosPagination['start_item'] ?? 0 ?>-<?= $videosPagination['end_item'] ?? 0 ?> of <?= $videoCount ?> video solutions
                                </p>
                                <nav class="flex flex-wrap items-center justify-center gap-2" aria-label="Video solutions pagination">
                                    <?php if ($videoCurrentPage > 1): ?>
                                        <?php $videoPrevQuery = array_merge($videoQuery, ['videos_page' => $videoCurrentPage - 1]); ?>
                                        <a href="<?= current_url() . '?' . http_build_query($videoPrevQuery) ?>" class="inline-flex items-center rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-600 transition hover:border-secondary hover:text-secondary">
                                            <i class="fas fa-chevron-left mr-2 text-xs"></i>Previous
                                        </a>
                                    <?php endif; ?>

                                    <?php if ($videoStartPage > 1): ?>
                                        <?php $videoFirstQuery = array_merge($videoQuery, ['videos_page' => 1]); ?>
                                        <a href="<?= current_url() . '?' . http_build_query($videoFirstQuery) ?>" class="inline-flex h-10 w-10 items-center justify-center rounded-lg border text-sm font-semibold transition <?= $videoCurrentPage === 1 ? 'border-secondary bg-secondary text-white' : 'border-gray-300 text-gray-700 hover:border-secondary hover:text-secondary' ?>">1</a>
                                        <?php if ($videoStartPage > 2): ?>
                                            <span class="px-1 text-gray-400">…</span>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <?php for ($page = $videoStartPage; $page <= $videoEndPage; $page++): ?>
                                        <?php $videoPageQuery = array_merge($videoQuery, ['videos_page' => $page]); ?>
                                        <a href="<?= current_url() . '?' . http_build_query($videoPageQuery) ?>" class="inline-flex h-10 w-10 items-center justify-center rounded-lg border text-sm font-semibold transition <?= $page === $videoCurrentPage ? 'border-secondary bg-secondary text-white shadow-sm' : 'border-gray-300 text-gray-700 hover:border-secondary hover:text-secondary' ?>" <?= $page === $videoCurrentPage ? 'aria-current="page"' : '' ?>><?= $page ?></a>
                                    <?php endfor; ?>

                                    <?php if ($videoEndPage < $videoTotalPages): ?>
                                        <?php if ($videoEndPage < $videoTotalPages - 1): ?>
                                            <span class="px-1 text-gray-400">…</span>
                                        <?php endif; ?>
                                        <?php $videoLastQuery = array_merge($videoQuery, ['videos_page' => $videoTotalPages]); ?>
                                        <a href="<?= current_url() . '?' . http_build_query($videoLastQuery) ?>" class="inline-flex h-10 w-10 items-center justify-center rounded-lg border text-sm font-semibold transition <?= $videoCurrentPage === $videoTotalPages ? 'border-secondary bg-secondary text-white' : 'border-gray-300 text-gray-700 hover:border-secondary hover:text-secondary' ?>"><?= $videoTotalPages ?></a>
                                    <?php endif; ?>

                                    <?php if ($videoCurrentPage < $videoTotalPages): ?>
                                        <?php $videoNextQuery = array_merge($videoQuery, ['videos_page' => $videoCurrentPage + 1]); ?>
                                        <a href="<?= current_url() . '?' . http_build_query($videoNextQuery) ?>" class="inline-flex items-center rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-600 transition hover:border-secondary hover:text-secondary">
                                            Next<i class="fas fa-chevron-right ml-2 text-xs"></i>
                                        </a>
                                    <?php endif; ?>
                                </nav>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($resource_type === 'all'): ?>
                        <div class="mt-8 flex justify-center">
                            <a href="<?= site_url('resources?type=videos') ?>" class="inline-flex items-center rounded-full border border-secondary bg-white px-6 py-3 text-sm font-semibold text-secondary transition hover:bg-secondary hover:text-white">
                                View all video solutions <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    <?php endif; ?>
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
