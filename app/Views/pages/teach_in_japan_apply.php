<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<?php
$supportPhone = site_setting('support_phone', '+265 992 313 978');
$supportEmail = site_setting('contact_email', 'info@tutorconnectmw.com');
$whatsAppLink = 'https://wa.me/265992313978?text=' . rawurlencode('Hello TutorConnect Malawi, I want to apply for the Japan teaching opportunity.');
$viewState = $viewState ?? 'unlock';
$accessRecord = $accessRecord ?? null;
$submittedApplication = $submittedApplication ?? null;
$applicationErrors = session()->getFlashdata('application_errors') ?? [];
$applicationFeeFormatted = number_format((float) ($applicationFee ?? 0), 0);
$applicationFeeFormatted2dp = number_format((float) ($applicationFee ?? 0), 2);
$requiredChecklist = [
    'Use the same email address for payment and the final application form.',
    'Prepare your passport details and your completed degree or transcript.',
    'Have three referees ready, including at least one relative.',
    'Confirm you understand the MK ' . $applicationFeeFormatted . ' application fee is non-refundable.',
];
$recommendedChecklist = [
    'Have your teaching certificate ready if you already have one.',
    'Prepare a short, accurate summary of your teaching background.',
    'Keep your phone available for follow-up and WhatsApp communication.',
    'If you already shared documents before, note that clearly in the form.',
];
$oldRefereeTypes = old('referee_type') ?: ['', '', ''];
$oldRefereeRelationships = old('referee_relationship') ?: ['', '', ''];
$oldRefereeNames = old('referee_name') ?: ['', '', ''];
$oldRefereeOrganisations = old('referee_organisation') ?: ['', '', ''];
$oldRefereePhones = old('referee_phone') ?: ['', '', ''];
$oldRefereeEmails = old('referee_email') ?: ['', '', ''];
$declarations = [
    'confirm_age_range' => 'I confirm that I am between 20 and 40 years old.',
    'confirm_valid_passport' => 'I confirm that I hold a valid passport.',
    'acknowledge_application_fee' => 'I understand the application fee is MK ' . $applicationFeeFormatted2dp . ' and is non-refundable.',
    'acknowledge_processing_fee' => 'I understand processing fees may apply after screening and do not guarantee employment.',
    'consent_data_sharing' => 'I consent to TutorConnect Malawi sharing my information with the Japan partner agent for placement.',
    'confirm_truthfulness' => 'I confirm that all information provided is true and correct.',
    'confirm_voluntary_participation' => 'I understand participation is voluntary and that I have been informed of fees and responsibilities.',
];
$prefillName = old('full_name');
if ($prefillName === null || $prefillName === '') {
    $prefillName = $accessRecord['full_name'] ?? '';
}
$prefillEmail = old('email');
if ($prefillEmail === null || $prefillEmail === '') {
    $prefillEmail = $accessRecord['email'] ?? '';
}
$prefillPhone = old('phone');
if ($prefillPhone === null || $prefillPhone === '') {
    $prefillPhone = $accessRecord['phone'] ?? '';
}
?>

<?php if ($viewState === 'unlock'): ?>
    <section class="relative overflow-hidden text-white py-20">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('<?= base_url('assets/japan.jpg') ?>');"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-primary/90 via-primary/80 to-orange-600/75"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl">
                <a href="<?= site_url('teach-in-japan') ?>" class="inline-flex items-center text-red-100 hover:text-white font-medium transition">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Japan Opportunity Details
                </a>
                <h1 class="mt-6 text-5xl md:text-6xl font-extrabold leading-tight">Unlock the Japan Application Form</h1>
                <p class="mt-6 text-xl text-red-50 leading-relaxed">
                    Pay the MK <?= number_format((float) $applicationFee, 0) ?> application fee first, then continue to the full application form. If you refresh or come back later, the same paid email can restore access without paying again. You can also
                    <a href="<?= site_url('teach-in-japan/status') ?>" class="underline font-semibold hover:text-white">check your application status</a>
                    anytime using your email or reference.
                </p>
                <div class="mt-8 flex flex-col sm:flex-row gap-4">
                    <a href="<?= esc($whatsAppLink) ?>" target="_blank" rel="noopener" class="block px-6 py-3 bg-white text-primary font-bold rounded-lg hover:bg-red-50 transition text-center">
                        <i class="fab fa-whatsapp mr-2"></i>Ask on WhatsApp
                    </a>
                    <a href="<?= site_url('teach-in-japan/status') ?>" class="block px-6 py-3 bg-white/10 text-white font-bold rounded-lg border border-white/30 hover:bg-white/15 transition text-center">
                        <i class="fas fa-search mr-2"></i>Check Status
                    </a>
                    <div class="block px-6 py-3 bg-red-700/80 text-white font-bold rounded-lg border border-red-400 text-center">
                        Pay First: MK <?= number_format((float) $applicationFee, 0) ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-12 bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-3 gap-6">
            <div class="rounded-2xl border border-red-100 bg-red-50 p-6">
                <div class="text-sm font-semibold uppercase tracking-wide text-primary">Required Before You Start</div>
                <ul class="mt-4 space-y-3 text-gray-700 leading-7">
                    <?php foreach ($requiredChecklist as $item): ?>
                        <li class="flex items-start"><i class="fas fa-check-circle text-primary mt-1 mr-3"></i><span><?= esc($item) ?></span></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="rounded-2xl border border-orange-100 bg-orange-50 p-6">
                <div class="text-sm font-semibold uppercase tracking-wide text-primary">Recommended to Prepare</div>
                <ul class="mt-4 space-y-3 text-gray-700 leading-7">
                    <?php foreach ($recommendedChecklist as $item): ?>
                        <li class="flex items-start"><i class="fas fa-star text-primary mt-1 mr-3"></i><span><?= esc($item) ?></span></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-gray-50 p-6">
                <div class="text-sm font-semibold uppercase tracking-wide text-primary">Already Paid?</div>
                <p class="mt-4 text-gray-700 leading-7">Use the same email address you paid with and we will restore your form access without asking you to pay again.</p>
                <form id="restoreAccessForm" method="post" action="<?= site_url('teach-in-japan/access/restore') ?>" class="mt-5 space-y-4">
                    <?= csrf_field() ?>
                    <label class="block">
                        <span class="block text-sm font-semibold text-gray-800 mb-2">Paid email address</span>
                        <input type="email" name="restore_email" value="<?= esc(old('restore_email')) ?>" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary" placeholder="you@example.com" required>
                    </label>
                    <button id="restoreAccessBtn" type="submit" class="w-full rounded-xl bg-white px-4 py-3 font-bold text-primary border border-primary hover:bg-red-50 transition">
                        <span id="restoreAccessBtnText">Restore Access</span>
                    </button>
                </form>
                <div class="mt-4 text-sm">
                    <a href="<?= site_url('teach-in-japan/status') ?>" class="font-semibold text-primary hover:underline">
                        <i class="fas fa-search mr-1"></i>Check application status
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <?php if (session()->getFlashdata('error')): ?>
                <div class="mb-8 rounded-2xl border border-red-200 bg-red-50 p-6 text-red-900"><?= esc(session()->getFlashdata('error')) ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('success')): ?>
                <div class="mb-8 rounded-2xl border border-emerald-200 bg-emerald-50 p-6 text-emerald-900"><?= esc(session()->getFlashdata('success')) ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('info')): ?>
                <div class="mb-8 rounded-2xl border border-blue-200 bg-blue-50 p-6 text-blue-900"><?= esc(session()->getFlashdata('info')) ?></div>
            <?php endif; ?>

            <div class="grid lg:grid-cols-[1.1fr_0.9fr] gap-8">
                <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
                    <p class="text-sm font-semibold uppercase tracking-wide text-primary">Step 1 of 2</p>
                    <h2 class="mt-3 text-3xl font-extrabold text-gray-900">Confirm Payment Details</h2>
                    <p class="mt-3 text-gray-600 leading-7">
                        This fee unlocks one Japan application flow. If you refresh or return later using the same email, access can be restored without paying again.
                    </p>

                    <form id="unlockPaymentForm" class="mt-8 space-y-5">
                        <?= csrf_field() ?>
                        <label class="block">
                            <span class="block text-sm font-semibold text-gray-800 mb-2">Applicant full name</span>
                            <input type="text" name="payer_full_name" value="<?= esc(old('payer_full_name')) ?>" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary" placeholder="Full name as used for the application" required>
                        </label>
                        <label class="block">
                            <span class="block text-sm font-semibold text-gray-800 mb-2">Email address</span>
                            <input type="email" name="payer_email" value="<?= esc(old('payer_email')) ?>" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary" placeholder="Email used for payment and form access" required>
                        </label>
                        <label class="block">
                            <span class="block text-sm font-semibold text-gray-800 mb-2">Phone / WhatsApp</span>
                            <input type="text" name="payer_phone" value="<?= esc(old('payer_phone')) ?>" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary" placeholder="+265..." required>
                        </label>
                        <label class="flex items-start gap-3 rounded-xl border border-gray-200 bg-gray-50 p-4 text-sm text-gray-700">
                            <input type="checkbox" name="unlock_terms" value="1" class="mt-1" required>
                            <span>I understand the MK <?= number_format((float) $applicationFee, 0) ?> application fee is non-refundable and only unlocks access to the application form. Employment is not guaranteed.</span>
                        </label>

                        <button type="submit" id="unlockPaymentBtn" class="w-full rounded-xl bg-gradient-to-r from-primary to-orange-600 px-6 py-4 font-bold text-white hover:opacity-95 transition">
                            <span id="unlockPaymentBtnText">Pay & Unlock Application Form</span>
                        </button>
                        <div id="unlockPaymentFeedback" class="hidden rounded-xl border px-4 py-3 text-sm leading-6"></div>
                    </form>
                </div>

                <div class="space-y-6">
                    <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
                        <div class="text-sm font-semibold uppercase tracking-wide text-primary">Fee Summary</div>
                        <div class="mt-4 text-4xl font-extrabold text-gray-900">MK <?= number_format((float) $applicationFee, 0) ?></div>
                        <p class="mt-3 text-gray-600 leading-7">Non-refundable application fee. Processing fee of MK <?= number_format((float) $processingFee, 0) ?> is only charged later if your profile passes screening.</p>
                    </div>
                    <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
                        <div class="text-sm font-semibold uppercase tracking-wide text-primary">What Happens Next</div>
                        <ol class="mt-4 space-y-3 text-gray-700 leading-7">
                            <li><strong>1.</strong> Pay the application fee using PayChangu.</li>
                            <li><strong>2.</strong> Your access is unlocked and remembered for this browser session.</li>
                            <li><strong>3.</strong> If the page refreshes, you can continue without paying again.</li>
                            <li><strong>4.</strong> If needed later, restore access using the same paid email.</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php elseif ($viewState === 'submitted'): ?>
    <?php $submittedSuccessMessage = session()->getFlashdata('success'); ?>
    <section class="relative overflow-hidden text-white py-20">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('<?= base_url('assets/japan.jpg') ?>');"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-primary/90 via-primary/80 to-orange-600/75"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl">
                <a href="<?= site_url('teach-in-japan') ?>" class="inline-flex items-center text-red-100 hover:text-white font-medium transition">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Japan Opportunity Details
                </a>
                <h1 class="mt-6 text-5xl md:text-6xl font-extrabold leading-tight">
                    <?= $submittedSuccessMessage ? 'Application Submitted' : 'Application Already Submitted' ?>
                </h1>
                <p class="mt-6 text-xl text-red-50 leading-relaxed">
                    <?= $submittedSuccessMessage
                        ? 'We have received your application. You do not need to pay again. This page will keep showing your submission summary.'
                        : 'You do not need to pay again. This page will keep showing your submission summary.' ?>
                    <span class="block mt-3 text-base text-red-100">
                        Want to check progress later?
                        <a href="<?= site_url('teach-in-japan/status') ?>" class="underline font-semibold hover:text-white">Check application status</a>.
                    </span>
                </p>
            </div>
        </div>
    </section>

    <section class="py-20 bg-gray-50">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <?php if ($submittedSuccessMessage): ?>
                <div class="mb-8 rounded-2xl border border-emerald-200 bg-emerald-50 p-6 text-emerald-900"><?= esc($submittedSuccessMessage) ?></div>
            <?php endif; ?>
            <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <div class="text-sm font-semibold uppercase tracking-wide text-primary">Application Reference</div>
                        <div class="mt-2 text-3xl font-extrabold text-gray-900"><?= esc($submittedApplication['application_reference'] ?? 'Pending') ?></div>
                    </div>
                    <div>
                        <div class="text-sm font-semibold uppercase tracking-wide text-primary">Paid Email</div>
                        <div class="mt-2 text-xl font-bold text-gray-900"><?= esc($accessRecord['email'] ?? 'N/A') ?></div>
                    </div>
                    <div>
                        <div class="text-sm font-semibold uppercase tracking-wide text-primary">Submission Status</div>
                        <div class="mt-2 text-xl font-bold text-gray-900">Submitted</div>
                    </div>
                    <div>
                        <div class="text-sm font-semibold uppercase tracking-wide text-primary">Payment Reference</div>
                        <div class="mt-2 text-xl font-bold text-gray-900"><?= esc($accessRecord['tx_ref'] ?? 'N/A') ?></div>
                    </div>
                </div>

                <div class="mt-8 rounded-2xl border border-gray-200 bg-gray-50 p-6">
                    <h2 class="text-2xl font-extrabold text-gray-900">What Happens Next</h2>
                    <ul class="mt-4 space-y-3 text-gray-700 leading-7">
                        <li><strong>1.</strong> TutorConnect Malawi reviews the application within 48 hours.</li>
                        <li><strong>2.</strong> Referees may be contacted for verification.</li>
                        <li><strong>3.</strong> Any additional follow-up, including video requests, will be handled on WhatsApp.</li>
                        <li><strong>4.</strong> If approved, next-stage processing instructions will follow.</li>
                    </ul>
                </div>

                <div class="mt-8 flex flex-col sm:flex-row gap-4">
                    <a href="<?= esc($whatsAppLink) ?>" target="_blank" rel="noopener" class="block px-6 py-3 bg-primary text-white font-bold rounded-lg hover:bg-red-600 transition text-center">
                        <i class="fab fa-whatsapp mr-2"></i>Continue on WhatsApp
                    </a>
                    <a href="<?= site_url('teach-in-japan/status') ?>" class="block px-6 py-3 bg-white text-gray-800 font-bold rounded-lg border border-gray-300 hover:bg-gray-50 transition text-center">
                        <i class="fas fa-search mr-2"></i>Check Status
                    </a>
                    <a href="<?= site_url('teach-in-japan/access/reset') ?>" class="block px-6 py-3 bg-red-50 text-primary font-bold rounded-lg border border-primary hover:bg-red-100 transition text-center">
                        <i class="fas fa-redo mr-2"></i>Submit Another Application
                    </a>
                    <a href="<?= site_url('teach-in-japan') ?>" class="block px-6 py-3 bg-white text-primary font-bold rounded-lg border border-primary hover:bg-red-50 transition text-center">
                        Back to Opportunity Page
                    </a>
                </div>
            </div>
        </div>
    </section>
<?php else: ?>
    <section class="relative overflow-hidden text-white py-20">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('<?= base_url('assets/japan.jpg') ?>');"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-primary/90 via-primary/80 to-orange-600/75"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-5xl">
                <a href="<?= site_url('teach-in-japan') ?>" class="inline-flex items-center text-red-100 hover:text-white font-medium transition">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Japan Opportunity Details
                </a>
                <h1 class="mt-6 text-5xl md:text-6xl font-extrabold leading-tight">Complete Your Japan Application</h1>
                <p class="mt-6 text-xl text-red-50 leading-relaxed">
                    Payment confirmed for <strong><?= esc($accessRecord['email'] ?? '') ?></strong>. This guided form clearly separates what is required from what is recommended so applicants can finish with confidence.
                </p>
                <div class="mt-6 flex flex-col sm:flex-row gap-3">
                    <a href="<?= site_url('teach-in-japan/status') ?>" class="inline-flex items-center justify-center px-6 py-3 bg-white/10 text-white font-bold rounded-lg border border-white/30 hover:bg-white/15 transition text-center">
                        <i class="fas fa-search mr-2"></i>Check Status
                    </a>
                    <a href="<?= esc($whatsAppLink) ?>" target="_blank" rel="noopener" class="inline-flex items-center justify-center px-6 py-3 bg-white text-primary font-bold rounded-lg hover:bg-red-50 transition text-center">
                        <i class="fab fa-whatsapp mr-2"></i>WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="py-12 bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-3 gap-6">
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-6">
                <div class="text-sm font-semibold uppercase tracking-wide text-emerald-700">Access Unlocked</div>
                <p class="mt-4 text-gray-700 leading-7">Payment reference: <strong><?= esc($accessRecord['tx_ref'] ?? 'N/A') ?></strong>. Refreshing this page will not ask for payment again while your access remains active.</p>
            </div>
            <div class="rounded-2xl border border-red-100 bg-red-50 p-6">
                <div class="text-sm font-semibold uppercase tracking-wide text-primary">Required</div>
                <ul class="mt-4 space-y-3 text-gray-700 leading-7">
                    <?php foreach ($requiredChecklist as $item): ?>
                        <li class="flex items-start"><i class="fas fa-check-circle text-primary mt-1 mr-3"></i><span><?= esc($item) ?></span></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="rounded-2xl border border-orange-100 bg-orange-50 p-6">
                <div class="text-sm font-semibold uppercase tracking-wide text-primary">Recommended</div>
                <ul class="mt-4 space-y-3 text-gray-700 leading-7">
                    <?php foreach ($recommendedChecklist as $item): ?>
                        <li class="flex items-start"><i class="fas fa-star text-primary mt-1 mr-3"></i><span><?= esc($item) ?></span></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </section>

    <section id="application-form" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="mb-8 rounded-2xl border border-emerald-200 bg-emerald-50 p-6 text-emerald-900"><?= esc(session()->getFlashdata('success')) ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="mb-8 rounded-2xl border border-red-200 bg-red-50 p-6 text-red-900">
                    <p class="font-bold"><?= esc(session()->getFlashdata('error')) ?></p>
                    <?php if (!empty($applicationErrors)): ?>
                        <ul class="mt-3 list-disc list-inside text-sm space-y-1">
                            <?php foreach ($applicationErrors as $error): ?><li><?= esc($error) ?></li><?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('info')): ?>
                <div class="mb-8 rounded-2xl border border-blue-200 bg-blue-50 p-6 text-blue-900"><?= esc(session()->getFlashdata('info')) ?></div>
            <?php endif; ?>

            <div id="clientValidationBox" class="hidden mb-8 rounded-2xl border border-red-200 bg-red-50 p-6 text-red-900">
                <p class="font-bold">Please fix the following before submitting:</p>
                <ul id="clientValidationList" class="mt-3 list-disc list-inside text-sm space-y-1"></ul>
            </div>

            <form id="japanApplicationForm" action="<?= site_url('teach-in-japan/apply') ?>" method="POST" enctype="multipart/form-data" class="space-y-8">
                <?= csrf_field() ?>

                <div class="grid gap-5 md:grid-cols-2 rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
                    <div class="md:col-span-2">
                        <h2 class="text-2xl font-extrabold text-gray-900">1. Identity & Contact Details</h2>
                        <p class="mt-2 text-gray-600">Required. The email field is locked to the paid email so payment access stays linked to this application.</p>
                    </div>
                    <label class="block md:col-span-2">
                        <span class="block text-sm font-semibold text-gray-800 mb-2">Full name as per passport <span class="text-primary">(Required)</span></span>
                        <input type="text" name="full_name" value="<?= esc($prefillName) ?>" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary" required>
                    </label>
                    <label class="block">
                        <span class="block text-sm font-semibold text-gray-800 mb-2">Date of birth <span class="text-primary">(Required)</span></span>
                        <input type="date" name="date_of_birth" id="date_of_birth" value="<?= esc(old('date_of_birth')) ?>" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary" required>
                    </label>
                    <label class="block">
                        <span class="block text-sm font-semibold text-gray-800 mb-2">Calculated age</span>
                        <input type="text" id="calculated_age" class="w-full rounded-xl border border-gray-300 px-4 py-3 bg-gray-100 text-gray-600" readonly>
                    </label>
                    <label class="block">
                        <span class="block text-sm font-semibold text-gray-800 mb-2">Gender <span class="text-primary">(Required)</span></span>
                        <select name="gender" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary" required>
                            <option value="">Select gender</option>
                            <?php foreach (['Male', 'Female', 'Other', 'Prefer not to say'] as $gender): ?><option value="<?= esc($gender) ?>" <?= old('gender') === $gender ? 'selected' : '' ?>><?= esc($gender) ?></option><?php endforeach; ?>
                        </select>
                    </label>
                    <label class="block">
                        <span class="block text-sm font-semibold text-gray-800 mb-2">Nationality <span class="text-primary">(Required)</span></span>
                        <input type="text" name="nationality" value="<?= esc(old('nationality')) ?>" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary" required>
                    </label>
                    <label class="block">
                        <span class="block text-sm font-semibold text-gray-800 mb-2">Phone / WhatsApp <span class="text-primary">(Required)</span></span>
                        <input type="text" name="phone" value="<?= esc($prefillPhone) ?>" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary" required>
                    </label>
                    <label class="block">
                        <span class="block text-sm font-semibold text-gray-800 mb-2">Email address</span>
                        <input type="email" name="email" value="<?= esc($prefillEmail) ?>" class="w-full rounded-xl border border-gray-300 px-4 py-3 bg-gray-100 text-gray-600" readonly>
                    </label>
                    <label class="block md:col-span-2">
                        <span class="block text-sm font-semibold text-gray-800 mb-2">Current residential address <span class="text-primary">(Required)</span></span>
                        <textarea name="current_address" rows="3" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary" required><?= esc(old('current_address')) ?></textarea>
                    </label>
                </div>

                <div class="grid gap-5 md:grid-cols-2 rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
                    <div class="md:col-span-2">
                        <h2 class="text-2xl font-extrabold text-gray-900">2. Passport & Education</h2>
                        <p class="mt-2 text-gray-600">Required. Complete these exactly and carefully to avoid screening delays.</p>
                    </div>
                    <label class="block"><span class="block text-sm font-semibold text-gray-800 mb-2">Do you have a valid passport? <span class="text-primary">(Required)</span></span><select name="has_valid_passport" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary" required><option value="">Select</option><option value="yes" <?= old('has_valid_passport') === 'yes' ? 'selected' : '' ?>>Yes</option><option value="no" <?= old('has_valid_passport') === 'no' ? 'selected' : '' ?>>No</option></select></label>
                    <label class="block"><span class="block text-sm font-semibold text-gray-800 mb-2">Passport number <span class="text-primary">(Required)</span></span><input type="text" name="passport_number" value="<?= esc(old('passport_number')) ?>" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary" required></label>
                    <label class="block"><span class="block text-sm font-semibold text-gray-800 mb-2">Passport expiry date <span class="text-primary">(Required)</span></span><input type="date" name="passport_expiry_date" value="<?= esc(old('passport_expiry_date')) ?>" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary" required></label>
                    <label class="block"><span class="block text-sm font-semibold text-gray-800 mb-2">Willing to renew if required? <span class="text-primary">(Required)</span></span><select name="willing_to_renew_passport" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary" required><option value="">Select</option><option value="yes" <?= old('willing_to_renew_passport') === 'yes' ? 'selected' : '' ?>>Yes</option><option value="no" <?= old('willing_to_renew_passport') === 'no' ? 'selected' : '' ?>>No</option></select></label>
                    <label class="block"><span class="block text-sm font-semibold text-gray-800 mb-2">Highest qualification <span class="text-primary">(Required)</span></span><input type="text" name="highest_qualification" value="<?= esc(old('highest_qualification')) ?>" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary" required></label>
                    <label class="block"><span class="block text-sm font-semibold text-gray-800 mb-2">Degree obtained <span class="text-primary">(Required)</span></span><input type="text" name="degree_obtained" value="<?= esc(old('degree_obtained')) ?>" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary" required></label>
                    <label class="block"><span class="block text-sm font-semibold text-gray-800 mb-2">Field of study <span class="text-primary">(Required)</span></span><input type="text" name="field_of_study" value="<?= esc(old('field_of_study')) ?>" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary" required></label>
                    <label class="block"><span class="block text-sm font-semibold text-gray-800 mb-2">Year of completion <span class="text-primary">(Required)</span></span><input type="number" min="1900" max="<?= date('Y') ?>" name="year_of_completion" value="<?= esc(old('year_of_completion')) ?>" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary" required></label>
                    <label class="block md:col-span-2"><span class="block text-sm font-semibold text-gray-800 mb-2">Institution name <span class="text-primary">(Required)</span></span><input type="text" name="institution_name" value="<?= esc(old('institution_name')) ?>" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary" required></label>
                </div>

                <div class="grid gap-5 md:grid-cols-2 rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
                    <div class="md:col-span-2">
                        <h2 class="text-2xl font-extrabold text-gray-900">3. Teaching Background</h2>
                        <p class="mt-2 text-gray-600">Recommended. Complete as much as applies to you. If you answer “Yes”, give enough detail to support screening.</p>
                    </div>
                    <label class="block"><span class="block text-sm font-semibold text-gray-800 mb-2">Do you have a teaching certificate?</span><select name="has_teaching_certificate" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary" required><option value="">Select</option><option value="yes" <?= old('has_teaching_certificate') === 'yes' ? 'selected' : '' ?>>Yes</option><option value="no" <?= old('has_teaching_certificate') === 'no' ? 'selected' : '' ?>>No</option></select></label>
                    <label class="block"><span class="block text-sm font-semibold text-gray-800 mb-2">Certificate details <span class="text-gray-500">(Recommended)</span></span><input type="text" name="teaching_certificate_details" value="<?= esc(old('teaching_certificate_details')) ?>" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary" placeholder="TEFL, TESOL, CELTA, etc."></label>
                    <label class="block"><span class="block text-sm font-semibold text-gray-800 mb-2">Do you have teaching experience?</span><select name="has_teaching_experience" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary" required><option value="">Select</option><option value="yes" <?= old('has_teaching_experience') === 'yes' ? 'selected' : '' ?>>Yes</option><option value="no" <?= old('has_teaching_experience') === 'no' ? 'selected' : '' ?>>No</option></select></label>
                    <label class="block"><span class="block text-sm font-semibold text-gray-800 mb-2">Level taught <span class="text-gray-500">(Recommended)</span></span><select name="level_taught" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary"><option value="">Select</option><?php foreach (['Children', 'Adults', 'Both'] as $level): ?><option value="<?= esc($level) ?>" <?= old('level_taught') === $level ? 'selected' : '' ?>><?= esc($level) ?></option><?php endforeach; ?></select></label>
                    <label class="block md:col-span-2"><span class="block text-sm font-semibold text-gray-800 mb-2">Where have you taught? <span class="text-gray-500">(Recommended)</span></span><textarea name="teaching_experience_location" rows="3" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary"><?= esc(old('teaching_experience_location')) ?></textarea></label>
                    <label class="block"><span class="block text-sm font-semibold text-gray-800 mb-2">Subjects taught <span class="text-gray-500">(Recommended)</span></span><input type="text" name="subjects_taught" value="<?= esc(old('subjects_taught')) ?>" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary"></label>
                    <label class="block"><span class="block text-sm font-semibold text-gray-800 mb-2">Duration of teaching experience <span class="text-gray-500">(Recommended)</span></span><input type="text" name="teaching_experience_duration" value="<?= esc(old('teaching_experience_duration')) ?>" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary"></label>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
                    <h2 class="text-2xl font-extrabold text-gray-900">4. Referees</h2>
                    <p class="mt-2 text-gray-600">Required. All three referee profiles must be complete, and one must be a relative.</p>
                    <div class="mt-6 space-y-5">
                        <?php for ($i = 0; $i < 3; $i++): ?>
                            <div class="grid gap-4 md:grid-cols-2 rounded-2xl border border-gray-200 bg-gray-50 p-5">
                                <label class="block"><span class="block text-sm font-semibold text-gray-800 mb-2">Reference type</span><select name="referee_type[]" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary" required><option value="">Select</option><?php foreach (['Relative', 'Professional', 'Academic', 'Employer', 'Community'] as $type): ?><option value="<?= esc($type) ?>" <?= ($oldRefereeTypes[$i] ?? '') === $type ? 'selected' : '' ?>><?= esc($type) ?></option><?php endforeach; ?></select></label>
                                <label class="block"><span class="block text-sm font-semibold text-gray-800 mb-2">Relationship / position</span><input type="text" name="referee_relationship[]" value="<?= esc($oldRefereeRelationships[$i] ?? '') ?>" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary"></label>
                                <label class="block"><span class="block text-sm font-semibold text-gray-800 mb-2">Full name</span><input type="text" name="referee_name[]" value="<?= esc($oldRefereeNames[$i] ?? '') ?>" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary" required></label>
                                <label class="block"><span class="block text-sm font-semibold text-gray-800 mb-2">Organisation</span><input type="text" name="referee_organisation[]" value="<?= esc($oldRefereeOrganisations[$i] ?? '') ?>" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary"></label>
                                <label class="block"><span class="block text-sm font-semibold text-gray-800 mb-2">Phone</span><input type="text" name="referee_phone[]" value="<?= esc($oldRefereePhones[$i] ?? '') ?>" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary" required></label>
                                <label class="block"><span class="block text-sm font-semibold text-gray-800 mb-2">Email</span><input type="email" name="referee_email[]" value="<?= esc($oldRefereeEmails[$i] ?? '') ?>" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary" required></label>
                            </div>
                        <?php endfor; ?>
                    </div>
                    <label class="mt-5 flex items-start gap-3 text-sm text-gray-700"><input type="checkbox" name="referee_consent" value="1" <?= old('referee_consent') ? 'checked' : '' ?> class="mt-1">I consent to TutorConnect Malawi contacting my referees for verification.</label>
                </div>

                <div class="grid gap-5 md:grid-cols-2 rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
                    <div class="md:col-span-2">
                        <h2 class="text-2xl font-extrabold text-gray-900">5. Financial Readiness & Documents</h2>
                        <p class="mt-2 text-gray-600">Required and recommended items are clearly labelled below. Any introductory video follow-up will happen separately on WhatsApp.</p>
                    </div>
                    <label class="block"><span class="block text-sm font-semibold text-gray-800 mb-2">Aware some employers may not provide flights or accommodation? <span class="text-primary">(Required)</span></span><select name="aware_ticket_or_accommodation_optional" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary" required><option value="">Select</option><option value="yes" <?= old('aware_ticket_or_accommodation_optional') === 'yes' ? 'selected' : '' ?>>Yes</option><option value="no" <?= old('aware_ticket_or_accommodation_optional') === 'no' ? 'selected' : '' ?>>No</option></select></label>
                    <label class="block"><span class="block text-sm font-semibold text-gray-800 mb-2">Willing to pay your own air ticket if required? <span class="text-primary">(Required)</span></span><select name="willing_to_pay_air_ticket" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary" required><option value="">Select</option><option value="yes" <?= old('willing_to_pay_air_ticket') === 'yes' ? 'selected' : '' ?>>Yes</option><option value="no" <?= old('willing_to_pay_air_ticket') === 'no' ? 'selected' : '' ?>>No</option></select></label>
                    <label class="block"><span class="block text-sm font-semibold text-gray-800 mb-2">Willing to arrange accommodation if required? <span class="text-primary">(Required)</span></span><select name="willing_to_arrange_accommodation" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary" required><option value="">Select</option><option value="yes" <?= old('willing_to_arrange_accommodation') === 'yes' ? 'selected' : '' ?>>Yes</option><option value="no" <?= old('willing_to_arrange_accommodation') === 'no' ? 'selected' : '' ?>>No</option></select></label>
                    <label class="flex items-start gap-3 rounded-xl border border-orange-200 bg-orange-50 px-4 py-3 text-sm text-gray-700"><input type="checkbox" name="documents_already_shared" value="1" <?= old('documents_already_shared') ? 'checked' : '' ?> class="mt-1">I have already shared my supporting documents with TutorConnect Malawi.</label>
                    <label class="block md:col-span-2"><span class="block text-sm font-semibold text-gray-800 mb-2">Shared document note <span class="text-gray-500">(Recommended)</span></span><textarea name="shared_documents_note" rows="2" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary"><?= esc(old('shared_documents_note')) ?></textarea></label>
                    <label class="block"><span class="block text-sm font-semibold text-gray-800 mb-2">Degree document or transcript <span class="text-primary">(Required unless already shared)</span></span><input type="file" name="degree_document" accept=".pdf,.jpg,.jpeg,.png,.webp" class="w-full rounded-xl border border-gray-300 px-4 py-3"></label>
                    <label class="block"><span class="block text-sm font-semibold text-gray-800 mb-2">Additional transcript <span class="text-gray-500">(Optional)</span></span><input type="file" name="transcript_document" accept=".pdf,.jpg,.jpeg,.png,.webp" class="w-full rounded-xl border border-gray-300 px-4 py-3"></label>
                    <label class="block"><span class="block text-sm font-semibold text-gray-800 mb-2">Passport copy <span class="text-primary">(Required unless already shared)</span></span><input type="file" name="passport_copy" accept=".pdf,.jpg,.jpeg,.png,.webp" class="w-full rounded-xl border border-gray-300 px-4 py-3"></label>
                    <label class="block"><span class="block text-sm font-semibold text-gray-800 mb-2">CV / Resume <span class="text-primary">(Required unless already shared)</span></span><input type="file" name="cv_document" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.webp" class="w-full rounded-xl border border-gray-300 px-4 py-3"></label>
                    <label class="block"><span class="block text-sm font-semibold text-gray-800 mb-2">Teaching certificate document <span class="text-gray-500">(Optional unless certificate applies)</span></span><input type="file" name="teaching_certificate_document" accept=".pdf,.jpg,.jpeg,.png,.webp" class="w-full rounded-xl border border-gray-300 px-4 py-3"></label>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
                    <h2 class="text-2xl font-extrabold text-gray-900">6. Final Declarations</h2>
                    <p class="mt-2 text-gray-600">Required. Review every statement carefully before submitting.</p>
                    <div class="mt-6 grid gap-4">
                        <?php foreach ($declarations as $name => $label): ?>
                            <label class="flex items-start gap-3 rounded-xl border border-gray-200 bg-gray-50 p-4 text-sm text-gray-700"><input type="checkbox" name="<?= esc($name) ?>" value="1" <?= old($name) ? 'checked' : '' ?> class="mt-1"><span><?= esc($label) ?></span></label>
                        <?php endforeach; ?>
                    </div>
                    <div class="mt-6 grid gap-5 md:grid-cols-2">
                        <label class="block"><span class="block text-sm font-semibold text-gray-800 mb-2">Applicant signature (full name) <span class="text-primary">(Required)</span></span><input type="text" name="signature_name" value="<?= esc(old('signature_name')) ?>" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary" required></label>
                        <label class="block"><span class="block text-sm font-semibold text-gray-800 mb-2">Signature date <span class="text-primary">(Required)</span></span><input type="date" name="signature_date" value="<?= esc(old('signature_date')) ?>" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary" required></label>
                    </div>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
                    <div class="grid lg:grid-cols-[1fr_auto] gap-6 items-center">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-wide text-primary">7. Review & Submit</p>
                            <h2 class="mt-3 text-3xl font-extrabold text-gray-900">Submit Your Application</h2>
                            <p class="mt-3 text-gray-600 leading-7">
                                TutorConnect Malawi facilitates connections to potential employers in Japan and is not the employer. This form will only submit once the required sections are complete and valid.
                            </p>
                            <ul class="mt-5 space-y-3 text-sm text-gray-700">
                                <li class="flex items-start"><i class="fas fa-check-circle text-primary mt-1 mr-3"></i><span>Required fields and declarations must be completed before submission.</span></li>
                                <li class="flex items-start"><i class="fas fa-check-circle text-primary mt-1 mr-3"></i><span>Required documents must be uploaded unless you mark them as already shared.</span></li>
                                <li class="flex items-start"><i class="fas fa-check-circle text-primary mt-1 mr-3"></i><span>Any extra follow-up, including introductory video requests, will be handled on WhatsApp after this form is received.</span></li>
                            </ul>
                            <div class="mt-5 text-sm text-gray-500">Call / WhatsApp: <?= esc($supportPhone) ?> | Email: <?= esc($supportEmail) ?></div>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-4 lg:justify-end">
                            <button id="submitJapanApplicationBtn" type="submit" class="rounded-xl bg-gradient-to-r from-primary to-orange-600 px-8 py-4 font-bold text-white shadow-sm hover:opacity-95 transition">
                                <span id="submitJapanApplicationBtnText">Submit Application</span>
                            </button>
                            <a href="<?= esc($whatsAppLink) ?>" target="_blank" rel="noopener" class="rounded-xl border border-primary px-8 py-4 font-semibold text-primary hover:bg-red-50 transition text-center">WhatsApp</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
<?php endif; ?>

<?php if ($viewState === 'unlock'): ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://in.paychangu.com/js/popup.js"></script>
    <script>
    const originalJapanConsoleError = console.error;
    const originalJapanConsoleWarn = console.warn;

    console.error = function (...args) {
        if (args[0] && typeof args[0] === 'string') {
            const message = args[0].toLowerCase();
            if (message.includes('unsafe attempt to initiate navigation') ||
                message.includes('failed to set a named property') ||
                message.includes('minified react error') ||
                message.includes('client-side exception') ||
                message.includes('paychangu') ||
                (args[0].includes('4bd1b696') && args[0].includes('js'))) {
                return;
            }
        }

        originalJapanConsoleError.apply(console, args);
    };

    console.warn = function (...args) {
        if (args[0] && typeof args[0] === 'string') {
            const message = args[0].toLowerCase();
            if (message.includes('slow network') ||
                message.includes('fallback font') ||
                message.includes('paychangu')) {
                return;
            }
        }

        originalJapanConsoleWarn.apply(console, args);
    };

    document.addEventListener('DOMContentLoaded', function () {
        const unlockForm = document.getElementById('unlockPaymentForm');
        const unlockBtn = document.getElementById('unlockPaymentBtn');
        const unlockBtnText = document.getElementById('unlockPaymentBtnText');
        const feedbackBox = document.getElementById('unlockPaymentFeedback');
        const restoreForm = document.getElementById('restoreAccessForm');
        const restoreBtn = document.getElementById('restoreAccessBtn');
        const restoreBtnText = document.getElementById('restoreAccessBtnText');

        if (!unlockForm || !unlockBtn) {
            return;
        }

        function setFeedback(message, tone) {
            if (!feedbackBox) {
                return;
            }

            const tones = {
                info: 'border-blue-200 bg-blue-50 text-blue-900',
                success: 'border-emerald-200 bg-emerald-50 text-emerald-900',
                error: 'border-red-200 bg-red-50 text-red-900'
            };

            feedbackBox.className = 'rounded-xl border px-4 py-3 text-sm leading-6 ' + (tones[tone] || tones.info);
            feedbackBox.textContent = message;
        }

        unlockForm.addEventListener('submit', async function (event) {
            event.preventDefault();

            try {
                unlockBtn.disabled = true;
                unlockBtnText.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Preparing secure payment...';
                setFeedback('Preparing your secure PayChangu checkout. Please wait...', 'info');

                const response = await fetch('<?= site_url('teach-in-japan/access/initiate') ?>', {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    body: new FormData(unlockForm)
                });

                const result = await response.json();

                if (!result.success) {
                    setFeedback(result.message || 'Could not start payment. Please try again.', 'error');
                    unlockBtn.disabled = false;
                    unlockBtnText.textContent = 'Pay & Unlock Application Form';
                    return;
                }

                if (result.already_paid && result.redirect) {
                    setFeedback('Payment already confirmed. Opening your application access now...', 'success');
                    window.location.href = result.redirect;
                    return;
                }

                if (typeof PaychanguCheckout !== 'function') {
                    console.error('PayChangu SDK not loaded');
                    setFeedback('Payment system is unavailable right now. Please refresh and try again.', 'error');
                    unlockBtn.disabled = false;
                    unlockBtnText.textContent = 'Pay & Unlock Application Form';
                    return;
                }

                setFeedback('Secure payment window opened. Complete the payment, then wait while we confirm your access.', 'info');
                try {
                    PaychanguCheckout({
                        public_key: result.paychangu_config.public_key,
                        tx_ref: result.paychangu_config.tx_ref,
                        amount: result.paychangu_config.amount,
                        currency: result.paychangu_config.currency,
                        callback_url: result.paychangu_config.callback_url,
                        return_url: result.paychangu_config.return_url,
                        customer: {
                            email: result.paychangu_config.customer.email,
                            first_name: result.paychangu_config.customer.first_name,
                            last_name: result.paychangu_config.customer.last_name
                        },
                        customizations: {
                            title: result.paychangu_config.customizations.title,
                            description: result.paychangu_config.customizations.description,
                            logo: result.paychangu_config.customizations.logo
                        },
                        callback: function (response) {
                            console.log('Japan application payment completed:', response);
                            setFeedback('Payment submitted. Confirming your access now...', 'info');
                            checkJapanPaymentStatus(result.paychangu_config.tx_ref);
                        },
                        onClose: function () {
                            console.log('Japan application payment modal closed');
                            setFeedback('Checking payment status. If you were charged, access will unlock automatically or you can restore it with your paid email.', 'info');
                            checkJapanPaymentStatus(result.paychangu_config.tx_ref);
                        }
                    });
                } catch (popupError) {
                    console.error('PayChangu popup error:', popupError);
                    setFeedback('Payment system error. Please try again.', 'error');
                    unlockBtn.disabled = false;
                    unlockBtnText.textContent = 'Pay & Unlock Application Form';
                }
            } catch (error) {
                console.error('Japan application submission error:', error);
                setFeedback('Network error. Please try again.', 'error');
                unlockBtn.disabled = false;
                unlockBtnText.textContent = 'Pay & Unlock Application Form';
            }
        });

        if (restoreForm) {
            restoreForm.addEventListener('submit', function () {
                if (restoreBtn) {
                    restoreBtn.disabled = true;
                }
                if (restoreBtnText) {
                    restoreBtnText.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Restoring...';
                }
            });
        }

        function checkJapanPaymentStatus(txRef, attempt) {
            const currentAttempt = typeof attempt === 'number' ? attempt : 0;

            if (currentAttempt >= 20) {
                setFeedback('We are still waiting for payment confirmation. If you were charged already, use "Restore Access" with the same email instead of paying again.', 'info');
                unlockBtn.disabled = false;
                unlockBtnText.textContent = 'Pay & Unlock Application Form';
                return;
            }

            fetch('<?= site_url('teach-in-japan/payment/status') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: 'tx_ref=' + encodeURIComponent(txRef)
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'verified' && data.redirect) {
                    setFeedback('Payment confirmed. Opening the application form...', 'success');
                    window.location.href = data.redirect;
                    return;
                }

                if (data.status === 'failed') {
                    setFeedback(data.message || 'Payment was not successful. Please try again.', 'error');
                    unlockBtn.disabled = false;
                    unlockBtnText.textContent = 'Pay & Unlock Application Form';
                    return;
                }

                setFeedback('Payment received. We are still confirming your access. Please wait a moment...', 'info');
                setTimeout(function () {
                    checkJapanPaymentStatus(txRef, currentAttempt + 1);
                }, 2000);
            })
            .catch(() => {
                setFeedback('We could not confirm the payment automatically just now. If you already paid, use "Restore Access" with the same email.', 'info');
                unlockBtn.disabled = false;
                unlockBtnText.textContent = 'Pay & Unlock Application Form';
            });
        }
    });
    </script>
<?php endif; ?>

<?php if ($viewState === 'form'): ?>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('japanApplicationForm');
        const dobInput = document.getElementById('date_of_birth');
        const ageOutput = document.getElementById('calculated_age');
        const validationBox = document.getElementById('clientValidationBox');
        const validationList = document.getElementById('clientValidationList');
        const submitBtn = document.getElementById('submitJapanApplicationBtn');
        const submitBtnText = document.getElementById('submitJapanApplicationBtnText');

        function updateAge() {
            if (!dobInput || !ageOutput || !dobInput.value) {
                if (ageOutput) {
                    ageOutput.value = '';
                }
                return;
            }

            const dob = new Date(dobInput.value);
            if (Number.isNaN(dob.getTime())) {
                ageOutput.value = '';
                return;
            }

            const today = new Date();
            let age = today.getFullYear() - dob.getFullYear();
            const monthDifference = today.getMonth() - dob.getMonth();
            if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < dob.getDate())) {
                age--;
            }

            ageOutput.value = age > 0 ? age : '';
        }

        function getField(name) {
            return form ? form.querySelector('[name="' + name + '"]') : null;
        }

        function getFieldValue(name) {
            const field = getField(name);
            return field ? field.value.trim() : '';
        }

        function getFileInput(name) {
            return getField(name);
        }

        function clearClientValidation() {
            if (!validationBox || !validationList) {
                return;
            }

            validationBox.classList.add('hidden');
            validationList.innerHTML = '';
        }

        function showClientValidation(errors) {
            if (!validationBox || !validationList) {
                return;
            }

            validationList.innerHTML = '';
            errors.forEach(function (message) {
                const item = document.createElement('li');
                item.textContent = message;
                validationList.appendChild(item);
            });

            validationBox.classList.remove('hidden');
            validationBox.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        function collectClientValidationErrors() {
            const errors = [];
            const documentsShared = !!(getField('documents_already_shared') && getField('documents_already_shared').checked);
            const hasTeachingCertificate = getFieldValue('has_teaching_certificate') === 'yes';
            const hasTeachingExperience = getFieldValue('has_teaching_experience') === 'yes';
            const hasValidPassport = getFieldValue('has_valid_passport');
            const calculatedAge = parseInt(ageOutput ? ageOutput.value : '', 10);

            if (form && !form.checkValidity()) {
                errors.push('Please complete all required fields marked as required before submitting.');
            }

            if (dobInput && dobInput.value && (!Number.isFinite(calculatedAge) || calculatedAge < 20 || calculatedAge > 40)) {
                errors.push('Applicants must be between 20 and 40 years old.');
            }

            if (hasValidPassport === 'no') {
                errors.push('A valid passport is required for this opportunity.');
            }

            if (hasTeachingCertificate && getFieldValue('teaching_certificate_details') === '') {
                errors.push('Please specify your teaching certificate details.');
            }

            if (hasTeachingExperience && getFieldValue('teaching_experience_location') === '') {
                errors.push('Please tell us where you have taught.');
            }

            const refereeTypes = form ? Array.from(form.querySelectorAll('[name="referee_type[]"]')) : [];
            const refereeNames = form ? Array.from(form.querySelectorAll('[name="referee_name[]"]')) : [];
            const refereePhones = form ? Array.from(form.querySelectorAll('[name="referee_phone[]"]')) : [];
            const refereeEmails = form ? Array.from(form.querySelectorAll('[name="referee_email[]"]')) : [];

            for (let index = 0; index < 3; index++) {
                if (
                    !(refereeNames[index] && refereeNames[index].value.trim()) ||
                    !(refereePhones[index] && refereePhones[index].value.trim()) ||
                    !(refereeEmails[index] && refereeEmails[index].value.trim())
                ) {
                    errors.push('All three referees must be fully completed.');
                    break;
                }
            }

            const hasRelativeReferee = refereeTypes.some(function (field) {
                return field.value.trim().toLowerCase() === 'relative';
            });

            if (!hasRelativeReferee) {
                errors.push('At least one referee must be marked as a relative.');
            }

            const degreeDocument = getFileInput('degree_document');
            const passportCopy = getFileInput('passport_copy');
            const cvDocument = getFileInput('cv_document');
            const teachingCertificateDocument = getFileInput('teaching_certificate_document');

            if (!documentsShared && degreeDocument && degreeDocument.files.length === 0) {
                errors.push('Please upload your degree document or transcript.');
            }

            if (!documentsShared && passportCopy && passportCopy.files.length === 0) {
                errors.push('Please upload your passport copy.');
            }

            if (!documentsShared && cvDocument && cvDocument.files.length === 0) {
                errors.push('Please upload your CV / resume.');
            }

            if (hasTeachingCertificate && !documentsShared && teachingCertificateDocument && teachingCertificateDocument.files.length === 0) {
                errors.push('Please upload your teaching certificate document or mark your documents as already shared.');
            }

            return Array.from(new Set(errors));
        }

        if (dobInput) {
            dobInput.addEventListener('change', updateAge);
            dobInput.addEventListener('input', updateAge);
        }

        if (form) {
            form.addEventListener('submit', function (event) {
                clearClientValidation();
                updateAge();

                const errors = collectClientValidationErrors();
                if (errors.length > 0) {
                    event.preventDefault();
                    showClientValidation(errors);

                    if (!form.checkValidity()) {
                        form.reportValidity();
                    }

                    return;
                }

                if (submitBtn) {
                    submitBtn.disabled = true;
                }

                if (submitBtnText) {
                    submitBtnText.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Submitting Application...';
                }
            });
        }

        updateAge();
    });
    </script>
<?php endif; ?>

<?= $this->endSection() ?>
