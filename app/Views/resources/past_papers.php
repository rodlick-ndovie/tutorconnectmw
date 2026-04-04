<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<section class="bg-gradient-to-br from-orange-600 via-orange-700 to-red-800 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                <i class="fas fa-file-pdf mr-4 text-yellow-300"></i>
                Past Papers Library
            </h1>
            <p class="text-xl text-orange-100 max-w-3xl mx-auto">
                Access thousands of past exam papers to help you prepare for MANEB, Cambridge, and GCSE examinations
            </p>
        </div>
    </div>
</section>

<!-- Copyright Notice -->
<div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mx-4 md:mx-8 -mt-8 relative z-10 rounded-r-lg shadow-md">
    <div class="flex items-start">
        <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-3 flex-shrink-0"></i>
        <div>
            <h3 class="text-sm font-semibold text-yellow-800 mb-1">Important Copyright Notice</h3>
            <p class="text-sm text-yellow-700">
                Past papers are property of their respective exam boards (MANEB, Cambridge, GCSE). They are shared here for educational purposes only.
                TutorConnect Malawi does not claim ownership. Always refer to official sources for the most current information.
            </p>
        </div>
    </div>
</div>

<!-- Main Content -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-filter mr-3 text-orange-600"></i>
                Filter Papers
            </h2>

            <form method="GET" action="<?= site_url('resources/past-papers') ?>" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <!-- Exam Body Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Exam Body</label>
                    <select name="exam_body" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500">
                        <option value="">All Exam Bodies</option>
                        <?php if (!empty($filter_options['exam_bodies'])): ?>
                            <?php foreach ($filter_options['exam_bodies'] as $body): ?>
                                <option value="<?= esc($body) ?>" <?= ($filters['exam_body'] ?? '') === $body ? 'selected' : '' ?>>
                                    <?= esc($body) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <!-- Exam Level Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Exam Level</label>
                    <select name="exam_level" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500">
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

                <!-- Year Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Year</label>
                    <select name="year" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500">
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
                           placeholder="Paper title, code..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500">

                    <div class="flex space-x-2 pt-6">
                        <button type="submit" class="flex-1 bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700 transition">
                            <i class="fas fa-search mr-2"></i>Search
                        </button>
                        <a href="<?= site_url('resources/past-papers') ?>" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
                            <i class="fas fa-times mr-2"></i>Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Results Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">
                <?php if (!empty($papers)): ?>
                    Found <?= count($papers) ?> paper<?= count($papers) !== 1 ? 's' : '' ?>
                <?php else: ?>
                    Past Papers
                <?php endif; ?>
            </h2>
        </div>

        <!-- Papers Grid -->
        <?php if (!empty($papers)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($papers as $paper): ?>
                    <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden">
                        <!-- Header -->
                        <div class="bg-gradient-to-r from-orange-600 to-red-600 text-white p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="font-bold text-lg"><?= esc($paper['paper_title']) ?></h3>
                                    <p class="text-orange-100 text-sm">
                                        <?= esc($paper['exam_body']) ?> • <?= esc($paper['exam_level']) ?>
                                    </p>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold"><?= esc($paper['year']) ?></div>
                                    <div class="text-xs text-orange-100">Year</div>
                                </div>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-4">
                            <?php
                                $requiresPayment = !empty($paper['is_paid']);
                                $hasPaidAccess = !empty($paper['has_paid_access']);
                            ?>
                            <div class="flex items-center justify-between mb-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
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

                            <div class="mb-3 flex items-center justify-between gap-3">
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

                            <div class="text-sm text-gray-500 mb-3">
                                <i class="fas fa-user mr-1"></i>
                                <?php if (!empty($paper['first_name']) || !empty($paper['last_name'])): ?>
                                    Added by <?= esc($paper['first_name'] . ' ' . $paper['last_name']) ?>
                                <?php else: ?>
                                    Added by TutorConnect Malawi
                                <?php endif; ?>
                            </div>

                            <!-- Download Button -->
                            <a href="<?= ($requiresPayment && !$hasPaidAccess)
                                ? site_url('resources/past-papers/pay/' . $paper['id'])
                                : site_url('resources/past-papers/download/' . $paper['id']) ?>"
                               class="w-full <?= ($requiresPayment && !$hasPaidAccess) ? 'bg-amber-600 hover:bg-amber-700' : 'bg-green-600 hover:bg-green-700' ?> text-white py-3 px-4 rounded-lg transition flex items-center justify-center font-medium">
                                <i class="fas <?= ($requiresPayment && !$hasPaidAccess) ? 'fa-credit-card' : 'fa-download' ?> mr-2"></i>
                                <?php if ($requiresPayment && !$hasPaidAccess): ?>
                                    Pay MK <?= number_format((float) ($paper['price'] ?? 0), 0) ?> to Download
                                <?php else: ?>
                                    Download PDF
                                <?php endif; ?>
                            </a>
                            <?php if ($requiresPayment && !$hasPaidAccess): ?>
                                <p class="mt-3 text-xs text-amber-700">
                                    Use the same email again later and we will restore access without charging twice.
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <!-- No Results -->
            <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-file-pdf text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No Past Papers Found</h3>
                <p class="text-gray-600 mb-6">
                    <?php if (!empty(array_filter($filters))): ?>
                        No papers match your current filters. Try adjusting your search criteria.
                    <?php else: ?>
                        We're currently building our past papers collection. Check back soon for more resources!
                    <?php endif; ?>
                </p>
                <?php if (!empty(array_filter($filters))): ?>
                    <a href="<?= site_url('resources/past-papers') ?>" class="bg-orange-600 text-white px-6 py-3 rounded-lg hover:bg-orange-700 transition">
                        <i class="fas fa-times mr-2"></i>Clear Filters
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Call to Action -->
        <div class="mt-12 bg-gradient-to-r from-orange-600 to-red-600 rounded-xl p-8 text-center text-white">
            <h3 class="text-2xl font-bold mb-4">Need Personalized Help?</h3>
            <p class="text-orange-100 mb-6 max-w-2xl mx-auto">
                Connect with verified tutors who specialize in your subjects and exam preparation.
                Get one-on-one guidance tailored to your learning needs.
            </p>
            <a href="<?= site_url('find-tutors') ?>" class="inline-flex items-center bg-white text-orange-600 px-6 py-3 rounded-lg hover:bg-gray-100 transition font-semibold">
                <i class="fas fa-search mr-2"></i>Find a Tutor
            </a>
        </div>

    </div>
</section>

<style>
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
