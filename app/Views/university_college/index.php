<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<section class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 lg:py-18">
        <div class="grid grid-cols-1 lg:grid-cols-[1.05fr_0.95fr] gap-10 items-center">
            <div>
                <div class="inline-flex items-center rounded-md bg-orange-50 px-3 py-1.5 text-xs font-bold uppercase tracking-wide text-primary border border-orange-100">
                    <i class="fas fa-building-columns mr-2 text-primary"></i>University & College Support
                </div>
                <h1 class="mt-5 text-4xl md:text-5xl font-extrabold leading-tight text-secondary">Academic guidance for higher education work that needs care.</h1>
                <p class="mt-5 text-lg text-gray-700 leading-8 max-w-3xl">
                    TutorConnect Malawi connects university and college learners with approved specialists for research planning, technical subjects, data analysis, dissertation support, and structured academic consultations.
                </p>

                <div class="mt-8 flex flex-col sm:flex-row gap-3">
                    <a href="<?= site_url('request-tutor?type=university') ?>" class="inline-flex items-center justify-center rounded-md bg-primary px-6 py-3 text-sm font-bold text-white hover:bg-red-600 transition">
                        <i class="fas fa-calendar-check mr-2"></i>Request Academic Support
                    </a>
                    <a href="<?= site_url('university-college-support/register') ?>" class="inline-flex items-center justify-center rounded-md border border-secondary px-6 py-3 text-sm font-bold text-secondary hover:bg-secondary hover:text-white transition">
                        <i class="fas fa-user-graduate mr-2"></i>Register as a Specialist
                    </a>
                </div>
            </div>

            <div class="rounded-lg overflow-hidden border border-gray-200 bg-white shadow-lg">
                <img src="https://images.unsplash.com/photo-1523580846011-d3a5bc25702b?w=900&h=720&fit=crop&crop=center" alt="University students studying together" class="h-72 lg:h-96 w-full object-cover">
                <div class="grid grid-cols-3 divide-x divide-gray-200 border-t border-gray-200">
                    <div class="p-4">
                        <div class="text-2xl font-extrabold text-secondary">5</div>
                        <div class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Core Areas</div>
                    </div>
                    <div class="p-4">
                        <div class="text-2xl font-extrabold text-secondary">4</div>
                        <div class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Rate Types</div>
                    </div>
                    <div class="p-4">
                        <div class="text-2xl font-extrabold text-secondary">100%</div>
                        <div class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Admin Review</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="rounded-lg border border-amber-200 bg-amber-50 p-5 flex flex-col md:flex-row gap-4">
            <div class="w-12 h-12 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-shield-halved"></i>
            </div>
            <div>
                <h2 class="text-lg font-extrabold text-slate-900">Academic integrity policy</h2>
                <p class="mt-2 text-slate-700 leading-7">
                    TutorConnect provides academic guidance and technical support only. Tutors are strictly prohibited from completing dissertations, assignments, tests, or examinations on behalf of students.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="mb-8">
            <div class="text-sm font-bold text-primary uppercase tracking-wide">Support Areas</div>
            <h2 class="mt-2 text-3xl font-extrabold text-secondary">Specialist help by academic need</h2>
            <p class="mt-3 text-slate-600 max-w-3xl leading-7">Choose support that matches the subject, level, and type of academic work involved.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
            <?php foreach ($serviceCategories as $category => $items): ?>
                <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
                    <h3 class="text-lg font-extrabold text-secondary mb-4"><?= esc($category) ?></h3>
                    <ul class="space-y-2 text-sm text-slate-700">
                        <?php foreach ($items as $item): ?>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-primary mt-1"></i>
                                <span><?= esc($item) ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-5 mb-8">
            <div class="max-w-3xl">
                <div class="text-sm font-bold text-primary uppercase tracking-wide">Approved Specialists</div>
                <h2 class="mt-2 text-3xl font-extrabold text-secondary">University tutors ready to support academic work</h2>
                <p class="mt-3 text-slate-600 leading-7">Browse approved university and college support tutors by service area, teaching mode, and location.</p>
            </div>
            <a href="<?= site_url('request-tutor?type=university') ?>" class="inline-flex items-center justify-center rounded-md bg-primary px-5 py-2.5 text-sm font-bold text-white hover:bg-red-600 transition">
                Request Support <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>

        <?php if (!empty($approvedTutors)): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($approvedTutors as $tutor): ?>
                    <?php
                        $displayName = trim((string) ($tutor['full_name'] ?? ''));
                        $displayName = $displayName !== '' ? $displayName : 'University Tutor';
                        $initials = strtoupper(substr($displayName, 0, 1));
                        $profilePicture = trim((string) ($tutor['profile_picture'] ?? ''));
                        $institutions = $tutor['institutions_list'] ?? [];
                        $serviceAreas = $tutor['service_areas_list'] ?? [];
                        $visibleServices = array_slice($serviceAreas, 0, 3);
                        $remainingServices = max(0, count($serviceAreas) - count($visibleServices));
                    ?>
                    <article class="h-full rounded-lg border border-slate-200 bg-white shadow-sm hover:border-primary transition overflow-hidden">
                        <div class="p-5 h-full flex flex-col">
                            <div class="flex items-start gap-4 mb-5">
                                <div class="w-16 h-16 rounded-lg overflow-hidden bg-orange-50 flex items-center justify-center border border-orange-100 text-primary font-bold text-xl flex-shrink-0">
                                    <?php if ($profilePicture !== ''): ?>
                                        <img src="<?= base_url($profilePicture) ?>" alt="<?= esc($displayName) ?>" class="w-full h-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="w-full h-full hidden items-center justify-center"><?= esc($initials) ?></div>
                                    <?php else: ?>
                                        <?= esc($initials) ?>
                                    <?php endif; ?>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h3 class="text-lg font-semibold text-slate-900 leading-snug"><?= esc($displayName) ?></h3>
                                    <p class="mt-1 inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                        <i class="fas fa-circle-check mr-1.5"></i>Approved specialist
                                    </p>
                                </div>
                            </div>

                            <div class="grid gap-3 text-sm text-slate-600 mb-5">
                                <div class="flex items-start gap-2">
                                    <i class="fas fa-map-marker-alt text-primary mt-1 w-4"></i>
                                    <span class="leading-6"><?= esc($tutor['city_location'] ?? 'Location not specified') ?></span>
                                </div>
                                <div class="flex items-start gap-2">
                                    <i class="fas fa-laptop text-primary mt-1 w-4"></i>
                                    <span class="leading-6"><?= esc($tutor['teaching_mode'] ?? 'Mode not specified') ?></span>
                                </div>
                                <div class="flex items-start gap-2">
                                    <i class="fas fa-graduation-cap text-primary mt-1 w-4"></i>
                                    <span class="leading-6"><?= esc(!empty($institutions) ? implode(', ', array_slice($institutions, 0, 2)) : 'Institution not listed') ?></span>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-2 mb-5">
                                <?php if (!empty($visibleServices)): ?>
                                    <?php foreach ($visibleServices as $serviceArea): ?>
                                        <span class="inline-flex items-center rounded-md bg-slate-100 text-slate-700 text-xs font-medium px-2.5 py-1.5 leading-snug"><?= esc($serviceArea) ?></span>
                                    <?php endforeach; ?>
                                    <?php if ($remainingServices > 0): ?>
                                        <span class="inline-flex items-center rounded-md bg-orange-50 text-primary text-xs font-semibold px-2.5 py-1.5">+<?= $remainingServices ?> more</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="inline-flex items-center rounded-md bg-slate-100 text-slate-600 text-xs font-medium px-2.5 py-1.5">Academic Support</span>
                                <?php endif; ?>
                            </div>

                            <a href="<?= site_url('university-tutor/' . (int) ($tutor['id'] ?? 0)) ?>" class="mt-auto inline-flex items-center justify-center w-full rounded-md bg-secondary text-white text-sm font-semibold px-4 py-2.5 hover:bg-primary transition">
                                View Academic Profile <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="rounded-lg border border-orange-100 bg-orange-50/60 p-8 lg:p-10">
                <div class="max-w-3xl mx-auto text-center">
                    <div class="w-16 h-16 bg-white rounded-lg flex items-center justify-center mx-auto mb-5 border border-orange-100 text-primary">
                        <i class="fas fa-user-shield text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-extrabold text-slate-900">Approved university tutor profiles are being prepared</h3>
                    <p class="mt-3 text-slate-700 leading-7">
                        We only publish university and college specialists after profile verification, document review, and subscription activation. You can still submit your academic support request, and our team will route it to suitable approved tutors as they become available.
                    </p>
                    <div class="mt-6 flex flex-col sm:flex-row justify-center gap-3">
                        <a href="<?= site_url('request-tutor?type=university') ?>" class="inline-flex items-center justify-center rounded-md bg-primary px-5 py-2.5 text-sm font-bold text-white hover:bg-red-600 transition">
                            <i class="fas fa-calendar-check mr-2"></i>Request Academic Support
                        </a>
                        <a href="<?= site_url('university-college-support/register') ?>" class="inline-flex items-center justify-center rounded-md border border-primary bg-white px-5 py-2.5 text-sm font-bold text-primary hover:bg-primary hover:text-white transition">
                            <i class="fas fa-user-graduate mr-2"></i>Register as a Specialist
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 lg:grid-cols-[0.95fr_1.05fr] gap-8">
            <div>
                <div class="text-sm font-bold text-primary uppercase tracking-wide">For Tutors</div>
                <h2 class="mt-2 text-3xl font-extrabold text-secondary">A separate specialist profile and review flow</h2>
                <p class="mt-3 text-slate-600 leading-7">
                    University tutors submit academic background, service areas, documents, availability, and pricing before admin approval.
                </p>
                <div class="mt-6">
                    <a href="<?= site_url('university-college-support/register') ?>" class="inline-flex items-center justify-center rounded-md bg-secondary px-5 py-2.5 text-sm font-bold text-white hover:bg-primary transition">
                        Start Specialist Registration <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-5">
                    <h3 class="font-extrabold text-secondary mb-3">Required Profile Details</h3>
                    <ul class="space-y-2 text-sm text-slate-700">
                        <li>Profile picture and national ID</li>
                        <li>Academic transcript or certification proof</li>
                        <li>Institutions attended or currently attending</li>
                        <li>Service areas and competency bio</li>
                    </ul>
                </div>
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-5">
                    <h3 class="font-extrabold text-secondary mb-3">Availability & Pricing</h3>
                    <ul class="space-y-2 text-sm text-slate-700">
                        <li>Online, physical, or blended support</li>
                        <li>Available teaching days and times</li>
                        <li>City or service location</li>
                        <li>Hourly and package rates</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="bg-gray-50 border-y border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-xl font-extrabold text-secondary mb-4">Suggested Service Pricing</h3>
                <ul class="space-y-3 text-sm text-slate-700">
                    <?php foreach ($pricingGuidelines as $item): ?>
                        <li class="flex items-start gap-3">
                            <span class="mt-1 w-2 h-2 rounded-full bg-primary flex-shrink-0"></span>
                            <span><?= esc($item) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <p class="text-xs text-slate-500 mt-4">Final pricing may vary by tutor experience, subject complexity, and support level.</p>
            </div>

            <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-xl font-extrabold text-secondary mb-4">Tutor Subscription Plans</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left border-b border-slate-200">
                                <th class="py-3 pr-4 text-slate-500 uppercase text-xs tracking-wide">Plan</th>
                                <th class="py-3 pr-4 text-slate-500 uppercase text-xs tracking-wide">Monthly Fee</th>
                                <th class="py-3 text-slate-500 uppercase text-xs tracking-wide">Recommended For</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($subscriptionPlans as $plan): ?>
                                <tr class="border-b border-slate-100">
                                    <td class="py-3 pr-4 font-bold text-slate-900"><?= esc($plan['name']) ?></td>
                                    <td class="py-3 pr-4 text-slate-700"><?= esc($plan['fee']) ?></td>
                                    <td class="py-3 text-slate-700"><?= esc($plan['for']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="bg-white border-t border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-5">
            <div>
                <h2 class="text-2xl font-extrabold text-secondary">Need a specialist for a lecture, project, or consultation?</h2>
                <p class="mt-2 text-gray-600">Submit the request and TutorConnect will route it to suitable approved tutors.</p>
            </div>
            <a href="<?= site_url('request-tutor?type=university') ?>" class="inline-flex items-center justify-center rounded-md bg-primary px-6 py-3 text-sm font-bold text-white hover:bg-red-600 transition">
                Request Support <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
