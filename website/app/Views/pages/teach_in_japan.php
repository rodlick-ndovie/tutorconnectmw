<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<?php
$supportPhone = site_setting('support_phone', '+265 992 313 978');
$supportEmail = site_setting('contact_email', 'info@tutorconnectmw.com');
$whatsAppLink = 'https://wa.me/265992313978?text=' . rawurlencode('Hello TutorConnect Malawi, I want to apply for the Japan teaching opportunity.');
$applicationFee = (int) ($applicationFee ?? site_setting('japan_application_fee', '10000'));
$processingFee = (int) ($processingFee ?? site_setting('japan_processing_fee', '350000'));
$applicationFeeFormatted = number_format((float) $applicationFee, 0);
$processingFeeFormatted = number_format((float) $processingFee, 0);
$applicationsOpen = isset($applicationsOpen) ? (bool) $applicationsOpen : in_array(strtolower(trim((string) site_setting('japan_applications_open', '1'))), ['1', 'true', 'yes', 'on'], true);
$applicationsClosedMessage = trim((string) ($applicationsClosedMessage ?? site_setting('japan_applications_closed_message', '')));
if ($applicationsClosedMessage === '') {
    $applicationsClosedMessage = 'Japan applications are currently closed. Please check back soon or contact support.';
}
$requirements = [
    ['icon' => 'fas fa-graduation-cap', 'title' => "Bachelor's Degree", 'body' => 'Any field of study. Transcript accepted. Degree must be completed.'],
    ['icon' => 'fas fa-cake-candles', 'title' => 'Age 20-40', 'body' => 'Applicants must be between 20 and 40 years old based on placement requirements.'],
    ['icon' => 'fas fa-passport', 'title' => 'Valid Passport', 'body' => 'A valid passport is required. Applicants must be willing to renew if needed.'],
    ['icon' => 'fas fa-earth-africa', 'title' => 'All Nationalities', 'body' => 'This opportunity is open to all nationalities with visa support from employers.'],
    ['icon' => 'fas fa-book-open-reader', 'title' => 'Willing to Teach', 'body' => 'Applicants must be willing to teach English to children and/or adults in Japan.'],
    ['icon' => 'fas fa-wallet', 'title' => 'Financial Awareness', 'body' => 'Air ticket and accommodation may or may not be provided depending on the employer.'],
];
$processSteps = [
    ['title' => 'Document Submission', 'body' => 'Submit your degree, passport copy, and any teaching certificates.'],
    ['title' => 'Referees', 'body' => 'Provide three referees, including at least one relative.'],
    ['title' => 'Initial Review', 'body' => 'TutorConnect Malawi reviews your application within 48 hours.'],
    ['title' => 'Reference Checks', 'body' => 'Your referees are contacted and interviewed for verification.'],
    ['title' => 'Forwarded to Japan', 'body' => 'Verified profiles are sent to the Japan-based partner team.'],
    ['title' => 'Employer Matching', 'body' => 'Employers are identified and candidates are shortlisted based on demand.'],
    ['title' => 'Interview Scheduling', 'body' => 'Interview dates are arranged with guidance and coaching support.'],
    ['title' => 'Processing Fee', 'body' => 'MK ' . $processingFeeFormatted . ' is only charged after successful initial screening.'],
    ['title' => 'Job Offer', 'body' => 'Successful employers issue offer letters and visa-support documents.'],
    ['title' => 'Embassy Preparation', 'body' => 'TutorConnect Malawi helps you prepare for the visa interview.'],
    ['title' => 'Travel & Arrival', 'body' => 'Travel, accommodation, and arrival support are coordinated after hiring.'],
];
$supportItems = [
    ['icon' => 'fas fa-house', 'title' => 'Finding Accommodation', 'body' => 'Employers provide guidance to help successful candidates secure housing in Japan.'],
    ['icon' => 'fas fa-notes-medical', 'title' => 'Health Insurance', 'body' => 'Support is available for arranging the right medical insurance from arrival.'],
    ['icon' => 'fas fa-train-subway', 'title' => 'Transportation', 'body' => 'Commuter routes, transport passes, and setup guidance are provided.'],
    ['icon' => 'fas fa-file-signature', 'title' => 'Visas, Taxes & Insurance', 'body' => 'Employers support visa renewals, tax filing, and insurance documentation.'],
];
$applicationNotes = [
    'Submit your main application and supporting documents through the online form.',
    'If an introductory video is needed, it will be shared separately on WhatsApp.',
    'Application fee is MK ' . $applicationFeeFormatted . ' and is non-refundable.',
    'Processing fee is only paid after successful initial screening.',
];
$familyItems = [
    'Spouses and children may join you in Japan subject to immigration rules and employer terms.',
    'Dependent spouses may work part-time up to 28 hours per week.',
    'Many employers provide apartments, but some families may need private rentals.',
    'Public schools are lower cost, while international schools are English-speaking but more expensive.',
];
?>

<section class="relative overflow-hidden text-white py-20">
    <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('<?= base_url('assets/japan.jpg') ?>');"></div>
    <div class="absolute inset-0 bg-gradient-to-r from-primary/90 via-primary/80 to-orange-600/75"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl">
            <span class="inline-flex items-center rounded-full bg-white/15 px-4 py-2 text-sm font-semibold text-red-50 ring-1 ring-white/20">
                <?php if ($applicationsOpen): ?>
                    <i class="fas fa-plane-departure mr-2"></i>Now Accepting Applications
                <?php else: ?>
                    <i class="fas fa-circle-xmark mr-2"></i>Applications Closed
                <?php endif; ?>
            </span>
            <h1 class="mt-6 text-5xl md:text-6xl font-extrabold leading-tight">
                Teach English.
                <span class="block">Live &amp; Thrive in Japan.</span>
            </h1>
            <p class="mt-6 max-w-3xl text-xl text-red-50 leading-relaxed">
                TutorConnect Malawi connects qualified graduates with English teaching positions across Japan. Employer-sponsored visa support, any degree, and all nationalities welcome. Please read the information below first before continuing to the application form.
            </p>
            <?php if (!$applicationsOpen): ?>
                <div class="mt-6 max-w-3xl rounded-2xl border border-white/20 bg-white/10 p-5 text-red-50">
                    <div class="font-extrabold text-white">Notice</div>
                    <div class="mt-1 leading-relaxed"><?= esc($applicationsClosedMessage) ?></div>
                </div>
            <?php endif; ?>
            <div class="mt-8 flex flex-col sm:flex-row gap-4">
                <a href="#who-can-apply" class="block px-6 py-3 bg-white text-primary font-bold rounded-lg hover:bg-red-50 transition text-center">
                    <i class="fas fa-circle-info mr-2"></i>Read First Before Applying
                </a>
                <a href="<?= esc($whatsAppLink) ?>" target="_blank" rel="noopener" class="block px-6 py-3 bg-red-700 hover:bg-red-800 text-white font-bold rounded-lg transition border border-red-600 text-center">
                    <i class="fab fa-whatsapp mr-2"></i>WhatsApp Us
                </a>
                <a href="<?= site_url('teach-in-japan/status') ?>" class="block px-6 py-3 bg-white/10 text-white font-bold rounded-lg transition border border-white/25 text-center hover:bg-white/15">
                    <i class="fas fa-search mr-2"></i>Check Status
                </a>
            </div>
        </div>
    </div>
</section>

<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div id="who-can-apply" class="text-center max-w-3xl mx-auto mb-14 scroll-mt-24">
            <h2 class="text-4xl font-extrabold text-gray-900 mb-4">Who Can Apply</h2>
            <p class="text-xl text-gray-600">This opportunity is open to all nationalities. You do not need prior Japan experience, but you must meet the basic placement requirements below.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
            <?php foreach ($requirements as $requirement): ?>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                    <div class="flex items-center justify-center h-14 w-14 rounded-xl bg-gradient-to-br from-primary to-orange-600 text-white mb-6">
                        <i class="<?= esc($requirement['icon']) ?>"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3"><?= esc($requirement['title']) ?></h3>
                    <p class="text-gray-600 leading-7"><?= esc($requirement['body']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-2 gap-10">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <p class="text-sm font-bold uppercase tracking-[0.25em] text-primary">Transparent, Two-Step Fees</p>
            <div class="mt-8 grid gap-5">
                <div class="rounded-2xl bg-red-50 border border-red-100 p-6">
                    <div class="text-sm font-semibold text-primary uppercase">Step 1</div>
                    <h3 class="mt-2 text-2xl font-extrabold text-gray-900">Application Fee</h3>
                    <p class="mt-2 text-4xl font-extrabold text-primary">MK <?= esc($applicationFeeFormatted) ?></p>
                    <p class="mt-4 text-gray-700 leading-7">Non-refundable under any circumstances. Covers administrative processing, document verification, and coordination with the Japan partner team.</p>
                </div>
                <div class="rounded-2xl bg-orange-50 border border-orange-100 p-6">
                    <div class="text-sm font-semibold text-primary uppercase">Step 2</div>
                    <h3 class="mt-2 text-2xl font-extrabold text-gray-900">Processing Fee</h3>
                    <p class="mt-2 text-4xl font-extrabold text-primary">MK <?= esc($processingFeeFormatted) ?></p>
                    <p class="mt-4 text-gray-700 leading-7">Only charged after your profile passes initial screening. Covers matching, interview coordination, coaching, and pre-departure guidance.</p>
                </div>
            </div>
            <p class="mt-5 text-sm text-gray-600">Payment does not guarantee interview selection or employment. Final hiring decisions are made solely by the employer.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <p class="text-sm font-bold uppercase tracking-[0.25em] text-primary">Salary & Cost of Living</p>
            <div class="mt-8 space-y-5">
                <div class="rounded-2xl bg-gray-50 border border-gray-200 p-5">
                    <div class="text-3xl font-extrabold text-gray-900">~USD 1,452 / month</div>
                    <div class="mt-2 text-gray-600">Minimum equivalent based on official bank exchange rates.</div>
                </div>
                <ul class="space-y-4 text-gray-700 leading-7">
                    <li><strong>Outside major cities:</strong> about JPY 120,000 per month.</li>
                    <li><strong>Tokyo & major cities:</strong> significantly higher living costs.</li>
                    <li><strong>Transport pass:</strong> around JPY 10,000 to 20,000 per month.</li>
                    <li><strong>Basic groceries:</strong> around JPY 20,000 to 30,000 per month.</li>
                    <li><strong>Flight budget:</strong> around MK 3,000,000.</li>
                    <li><strong>Arrival cash recommended:</strong> around USD 1,200.</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-14">
            <h2 class="text-4xl font-extrabold text-gray-900 mb-4">Application &amp; Placement Process</h2>
            <p class="text-xl text-gray-600">Highly prepared applicants often receive a job offer in under one month. Moderately prepared applicants may take up to 90 days.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            <?php foreach ($processSteps as $index => $step): ?>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-center h-12 w-12 rounded-full bg-gradient-to-br from-primary to-orange-600 text-white font-extrabold mb-5">
                        <?= $index + 1 ?>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3"><?= esc($step['title']) ?></h3>
                    <p class="text-gray-600 leading-7"><?= esc($step['body']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-2 gap-10">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <p class="text-sm font-bold uppercase tracking-[0.25em] text-primary">Support on Arrival</p>
            <h2 class="mt-4 text-4xl font-extrabold text-gray-900">Employer Benefits</h2>
            <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-5">
                <?php foreach ($supportItems as $item): ?>
                    <div class="rounded-2xl bg-gray-50 border border-gray-200 p-5">
                        <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-gradient-to-br from-primary to-orange-600 text-white mb-4">
                            <i class="<?= esc($item['icon']) ?>"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2"><?= esc($item['title']) ?></h3>
                        <p class="text-gray-600 leading-7"><?= esc($item['body']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="space-y-8">
            <div id="before-you-apply" class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 scroll-mt-24">
                <p class="text-sm font-bold uppercase tracking-[0.25em] text-primary">Application Notes</p>
                <h2 class="mt-4 text-4xl font-extrabold text-gray-900">Before You Apply</h2>
                <ul class="mt-8 space-y-4">
                    <?php foreach ($applicationNotes as $note): ?>
                        <li class="flex items-start text-gray-700 leading-7">
                            <i class="fas fa-check-circle text-primary mt-1 mr-3"></i>
                            <span><?= esc($note) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                <p class="text-sm font-bold uppercase tracking-[0.25em] text-primary">Relocation</p>
                <h2 class="mt-4 text-4xl font-extrabold text-gray-900">Bringing Your Family to Japan</h2>
                <ul class="mt-8 space-y-4 text-gray-700 leading-7">
                    <?php foreach ($familyItems as $item): ?>
                        <li class="flex items-start">
                            <i class="fas fa-circle text-primary text-[10px] mt-3 mr-3"></i>
                            <span><?= esc($item) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="overflow-hidden">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-0 items-stretch">
        <div class="hidden lg:block relative bg-cover bg-center min-h-[320px]" style="background-image: url('<?= base_url('assets/japan.jpg') ?>');">
            <div class="absolute inset-0 bg-gradient-to-r from-primary/20 to-transparent"></div>
        </div>
        <div class="bg-gradient-to-r from-primary to-orange-600 text-white flex items-center px-8 sm:px-12 lg:px-16 py-12">
            <div class="w-full">
                <h2 class="text-3xl lg:text-4xl font-extrabold mb-4 leading-tight">Your Career in Japan Starts Here</h2>
                <p class="text-lg text-red-100 mb-8 leading-relaxed">
                    <?= $applicationsOpen
                        ? 'Applications are open throughout the year. After you have read the details above, continue to the separate application form when you are ready.'
                        : esc($applicationsClosedMessage) ?>
                </p>
                <div class="space-y-3 lg:space-y-0 lg:flex lg:gap-4">
                    <?php if ($applicationsOpen): ?>
                        <a href="<?= site_url('teach-in-japan/apply') ?>" class="block px-6 py-3 bg-white text-primary font-bold rounded-lg hover:bg-red-50 transition text-center lg:inline-block text-sm">
                            <i class="fas fa-file-signature mr-2"></i>I Have Read the Details - Apply Now
                        </a>
                    <?php else: ?>
                        <a href="<?= site_url('teach-in-japan/status') ?>" class="block px-6 py-3 bg-white text-primary font-bold rounded-lg hover:bg-red-50 transition text-center lg:inline-block text-sm">
                            <i class="fas fa-search mr-2"></i>Check Status
                        </a>
                    <?php endif; ?>
                    <a href="<?= esc($whatsAppLink) ?>" target="_blank" rel="noopener" class="block px-6 py-3 bg-red-700 hover:bg-red-800 text-white font-bold rounded-lg transition border border-red-600 text-center lg:inline-block text-sm">
                        <i class="fab fa-whatsapp mr-2"></i>Ask on WhatsApp
                    </a>
                </div>
                <div class="mt-6 text-sm text-red-100">Call / WhatsApp: <?= esc($supportPhone) ?> | Email: <?= esc($supportEmail) ?></div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
