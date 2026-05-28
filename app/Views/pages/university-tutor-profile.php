<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?php
$tutor = $tutor ?? [];
$displayName = trim((string) ($tutor['full_name'] ?? ''));
$displayName = $displayName !== '' ? $displayName : 'University Tutor';
$profilePicture = trim((string) ($tutor['profile_picture'] ?? ''));
$coverPhoto = 'uploads/slider/pexels-vlada-karpovich-7368295.jpg';
$bio = trim((string) ($tutor['bio'] ?? ''));
$city = trim((string) ($tutor['city_location'] ?? ''));
$mode = trim((string) ($tutor['teaching_mode'] ?? ''));
$year = trim((string) ($tutor['year_of_study_or_graduation'] ?? ''));
$initial = strtoupper(substr($displayName, 0, 1));

$serviceCategoryMap = [
    'Research & Dissertation Support' => [
        'Methodology guidance',
        'Data cleaning',
        'Proposal structuring',
        'Referencing support',
        'Data analysis interpretation',
    ],
    'ICT & Programming' => [
        'Python',
        'Java',
        'Web Development',
        'Database Systems',
        'Microsoft Excel',
        'Power BI Basics',
        'Data Science Basics',
        'Introduction to AI Tools',
        'Data Visualization',
    ],
    'Accounting & Finance' => [
        'Financial Accounting',
        'Cost Accounting',
        'Taxation Basics',
        'Economics',
        'Finance',
    ],
    'Mathematics' => [
        'Calculus',
        'Algebra',
        'Engineering Mathematics',
        'Business Mathematics',
    ],
    'Statistics & Data Analysis' => [
        'SPSS',
        'STATA',
        'Excel Data Analysis',
        'Quantitative Methods',
        'Research Methods',
        'Data Analysis for Dissertations',
    ],
];

$serviceGroups = [];
foreach ($serviceCategoryMap as $category => $services) {
    $matches = array_values(array_intersect($services, $service_areas ?? []));
    if ($matches !== []) {
        $serviceGroups[$category] = $matches;
    }
}

$knownServices = [];
foreach ($serviceCategoryMap as $services) {
    $knownServices = array_merge($knownServices, $services);
}
$otherServices = array_values(array_diff($service_areas ?? [], $knownServices));
if ($otherServices !== []) {
    $serviceGroups['Other Services'] = $otherServices;
}

$pricing = [
    'Hourly Tutoring' => (float) ($tutor['hourly_rate'] ?? 0),
    'Consultation Package' => (float) ($tutor['consultation_package_rate'] ?? 0),
    'Dissertation Package' => (float) ($tutor['dissertation_package_rate'] ?? 0),
    'Exam Preparation Package' => (float) ($tutor['exam_preparation_rate'] ?? 0),
];
?>

<section class="bg-slate-50 min-h-screen pb-14">
    <div class="relative">
        <div class="relative h-64 md:h-80 overflow-hidden bg-slate-200">
            <img src="<?= base_url($coverPhoto) ?>" alt="University academic support cover" class="h-full w-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/55 via-black/10 to-transparent"></div>
            <div class="absolute top-4 left-4">
                <a href="<?= site_url('university-college-support') ?>" class="inline-flex items-center rounded-lg bg-white/95 px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm hover:bg-white">
                    <i class="fas fa-arrow-left mr-2"></i>Back
                </a>
            </div>
        </div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative -mt-20 md:-mt-24 z-10">
                <div class="flex flex-col md:flex-row md:items-end gap-6">
                    <div class="w-40 h-40 md:w-48 md:h-48 rounded-2xl overflow-hidden bg-white ring-4 ring-white shadow-xl flex items-center justify-center text-5xl font-extrabold text-primary">
                        <?php if ($profilePicture !== ''): ?>
                            <img src="<?= base_url($profilePicture) ?>" alt="<?= esc($displayName) ?>" class="w-full h-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="hidden w-full h-full items-center justify-center"><?= esc($initial) ?></div>
                        <?php else: ?>
                            <?= esc($initial) ?>
                        <?php endif; ?>
                    </div>

                    <div class="flex-1 md:pb-5">
                        <div class="flex flex-wrap gap-2 mb-3">
                            <span class="inline-flex items-center rounded-full bg-emerald-500 px-3 py-1 text-xs font-bold text-white shadow-sm">
                                <i class="fas fa-circle-check mr-1.5"></i>Approved Specialist
                            </span>
                            <span class="inline-flex items-center rounded-full bg-white px-3 py-1 text-xs font-bold text-slate-800 shadow-sm border border-slate-200">
                                <i class="fas fa-user-graduate text-primary mr-1.5"></i>University Support
                            </span>
                        </div>
                        <h1 class="text-3xl md:text-5xl font-extrabold text-slate-950 leading-tight"><?= esc($displayName) ?></h1>
                        <div class="mt-3 flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 text-sm md:text-base text-slate-700 font-semibold">
                            <span><i class="fas fa-map-marker-alt text-primary mr-2"></i><?= esc($city !== '' ? $city : 'Location not specified') ?></span>
                            <span><i class="fas fa-laptop text-primary mr-2"></i><?= esc($mode !== '' ? $mode : 'Mode not specified') ?></span>
                        </div>
                    </div>

                    <div class="md:pb-5 flex flex-col gap-2 w-full md:w-auto">
                        <a href="<?= site_url('request-tutor?type=university') ?>" class="inline-flex items-center justify-center rounded-lg bg-primary px-5 py-3 text-sm font-bold text-white hover:bg-red-600 transition">
                            <i class="fas fa-calendar-check mr-2"></i>Request Support
                        </a>
                        <button type="button" onclick="navigator.share ? navigator.share({title: document.title, url: window.location.href}) : navigator.clipboard.writeText(window.location.href)" class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-5 py-3 text-sm font-bold text-slate-800 hover:border-primary hover:text-primary transition">
                            <i class="fas fa-share-alt mr-2"></i>Share Profile
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-[1.8fr_1fr] gap-6">
            <main class="space-y-6">
                <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-lg bg-orange-50 text-primary flex items-center justify-center">
                            <i class="fas fa-user"></i>
                        </div>
                        <h2 class="text-2xl font-extrabold text-slate-950">Professional Summary</h2>
                    </div>
                    <?php if ($bio !== ''): ?>
                        <p class="text-slate-700 leading-8"><?= nl2br(esc($bio)) ?></p>
                    <?php else: ?>
                        <p class="text-slate-600">No professional summary has been provided yet.</p>
                    <?php endif; ?>
                </section>

                <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-lg bg-orange-50 text-primary flex items-center justify-center">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <h2 class="text-2xl font-extrabold text-slate-950">Service Areas</h2>
                    </div>
                    <?php if (!empty($serviceGroups)): ?>
                        <div class="grid gap-4">
                            <?php foreach ($serviceGroups as $category => $items): ?>
                                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                                    <h3 class="text-base font-extrabold text-slate-900 mb-3"><?= esc($category) ?></h3>
                                    <div class="flex flex-wrap gap-2">
                                        <?php foreach ($items as $item): ?>
                                            <span class="inline-flex items-center rounded-md bg-white border border-orange-100 px-3 py-1.5 text-sm font-semibold text-slate-700">
                                                <i class="fas fa-check-circle text-primary mr-2"></i><?= esc($item) ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-slate-600">No service areas listed.</p>
                    <?php endif; ?>
                </section>

                <section class="rounded-lg border border-amber-200 bg-amber-50 p-6">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-shield-halved"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-extrabold text-slate-950">Academic Integrity</h2>
                            <p class="mt-2 text-slate-700 leading-7">This tutor provides academic guidance, review, tutoring, and technical support. TutorConnect does not permit tutors to complete assignments, tests, examinations, or dissertations on behalf of students.</p>
                        </div>
                    </div>
                </section>

                <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-lg bg-orange-50 text-primary flex items-center justify-center">
                            <i class="fas fa-building-columns"></i>
                        </div>
                        <h2 class="text-2xl font-extrabold text-slate-950">Institutions</h2>
                    </div>
                    <?php if (!empty($institutions)): ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <?php foreach ($institutions as $item): ?>
                                <div class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700">
                                    <i class="fas fa-graduation-cap text-primary mr-2"></i><?= esc($item) ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-slate-600">No institutions listed.</p>
                    <?php endif; ?>
                </section>
            </main>

            <aside class="space-y-6">
                <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-xl font-extrabold text-slate-950 mb-4">Profile Details</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Location</p>
                            <p class="mt-1 text-slate-800 font-semibold"><?= esc($city !== '' ? $city : 'Not specified') ?></p>
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Support Mode</p>
                            <p class="mt-1 text-slate-800 font-semibold"><?= esc($mode !== '' ? $mode : 'Not specified') ?></p>
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Year of Study / Graduation</p>
                            <p class="mt-1 text-slate-800 font-semibold"><?= esc($year !== '' ? $year : 'Not specified') ?></p>
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Service Areas</p>
                            <p class="mt-1 text-slate-800 font-semibold"><?= number_format(count($service_areas ?? [])) ?> listed</p>
                        </div>
                    </div>
                </section>

                <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-xl font-extrabold text-slate-950 mb-4">Availability</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Days</p>
                            <p class="mt-1 text-slate-800 font-semibold"><?= esc(!empty($available_days) ? implode(', ', $available_days) : 'Not specified') ?></p>
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Preferred Times</p>
                            <p class="mt-1 text-slate-800 font-semibold"><?= esc(!empty($preferred_times) ? implode(', ', $preferred_times) : 'Not specified') ?></p>
                        </div>
                    </div>
                </section>

                <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-xl font-extrabold text-slate-950 mb-4">Pricing</h3>
                    <div class="divide-y divide-slate-100">
                        <?php foreach ($pricing as $label => $amount): ?>
                            <div class="flex items-center justify-between gap-4 py-3">
                                <span class="text-sm font-semibold text-slate-600"><?= esc($label) ?></span>
                                <strong class="text-sm text-slate-950 whitespace-nowrap"><?= $amount > 0 ? 'MWK ' . number_format($amount) : 'Not set' ?></strong>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>

                <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-xl font-extrabold text-slate-950 mb-3">Request Support</h3>
                    <p class="text-sm text-slate-600 leading-6">Submit a university support request and TutorConnect will route it through the approved specialist matching flow.</p>
                    <a href="<?= site_url('request-tutor?type=university') ?>" class="mt-5 inline-flex w-full items-center justify-center rounded-lg bg-primary px-5 py-3 text-sm font-bold text-white hover:bg-red-600 transition">
                        Request This Specialist <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </section>

            </aside>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
