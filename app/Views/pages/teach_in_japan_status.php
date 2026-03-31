<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<?php
$supportPhone = site_setting('support_phone', '+265 992 313 978');
$supportEmail = site_setting('contact_email', 'info@tutorconnectmw.com');
$whatsAppLink = 'https://wa.me/265992313978?text=' . rawurlencode('Hello TutorConnect Malawi, I want to check my Japan application status.');
$results = $results ?? [];
$reference = $reference ?? '';
$email = $email ?? '';
$error = $error ?? null;
$applicationsOpen = isset($applicationsOpen) ? (bool) $applicationsOpen : in_array(strtolower(trim((string) site_setting('japan_applications_open', '1'))), ['1', 'true', 'yes', 'on'], true);
$applicationsClosedMessage = trim((string) ($applicationsClosedMessage ?? site_setting('japan_applications_closed_message', '')));
if ($applicationsClosedMessage === '') {
    $applicationsClosedMessage = 'Japan applications are currently closed. Please check back soon or contact support.';
}
?>

<section class="relative overflow-hidden text-white py-16">
    <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('<?= base_url('assets/japan.jpg') ?>');"></div>
    <div class="absolute inset-0 bg-gradient-to-r from-primary/90 via-primary/80 to-orange-600/75"></div>
    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="<?= site_url('teach-in-japan') ?>" class="inline-flex items-center text-red-100 hover:text-white font-medium transition">
            <i class="fas fa-arrow-left mr-2"></i>Back to Japan Opportunity
        </a>
        <h1 class="mt-6 text-4xl md:text-5xl font-extrabold leading-tight">Check Your Application Status</h1>
        <p class="mt-4 text-lg text-red-50 leading-relaxed max-w-3xl">
            Enter your <strong>application reference</strong> or your <strong>paid email</strong> to view your current status.
        </p>
    </div>
</section>

<section class="py-12 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-[1fr_0.55fr] gap-8">
            <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
                <?php if (!empty($error)): ?>
                    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-900">
                        <?= esc($error) ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('info')): ?>
                    <div class="mb-6 rounded-xl border border-blue-200 bg-blue-50 p-4 text-blue-900">
                        <?= esc(session()->getFlashdata('info')) ?>
                    </div>
                <?php endif; ?>

                <h2 class="text-2xl font-extrabold text-gray-900">Lookup</h2>
                <p class="mt-2 text-gray-600">You can provide either field. If you provide both, reference takes priority.</p>
                <?php if (!$applicationsOpen): ?>
                    <div class="mt-5 rounded-xl border border-orange-200 bg-orange-50 p-4 text-orange-900">
                        <strong>Notice:</strong> <?= esc($applicationsClosedMessage) ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="<?= site_url('teach-in-japan/status') ?>" class="mt-6 grid gap-5">
                    <?= csrf_field() ?>
                    <label class="block">
                        <span class="block text-sm font-semibold text-gray-800 mb-2">Application reference</span>
                        <input type="text" name="application_reference" value="<?= esc($reference) ?>" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary" placeholder="JP-20260326-ABC123">
                    </label>
                    <label class="block">
                        <span class="block text-sm font-semibold text-gray-800 mb-2">Email address</span>
                        <input type="email" name="email" value="<?= esc($email) ?>" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary" placeholder="you@example.com">
                    </label>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button type="submit" class="rounded-xl bg-gradient-to-r from-primary to-orange-600 px-6 py-3 font-bold text-white hover:opacity-95 transition">
                            Check Status
                        </button>
                        <a href="<?= site_url('teach-in-japan/status') ?>" class="rounded-xl border border-gray-300 px-6 py-3 font-semibold text-gray-700 hover:bg-gray-50 transition text-center">
                            Clear
                        </a>
                    </div>
                </form>

                <?php if (!empty($results)): ?>
                    <div class="mt-10">
                        <h3 class="text-xl font-extrabold text-gray-900">Results</h3>
                        <div class="mt-4 overflow-x-auto">
                            <table class="min-w-full border border-gray-200 rounded-xl overflow-hidden">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="text-left text-sm font-bold text-gray-700 px-4 py-3 border-b">No.</th>
                                        <th class="text-left text-sm font-bold text-gray-700 px-4 py-3 border-b">Reference</th>
                                        <th class="text-left text-sm font-bold text-gray-700 px-4 py-3 border-b">Status</th>
                                        <th class="text-left text-sm font-bold text-gray-700 px-4 py-3 border-b">Submitted</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; foreach ($results as $row): ?>
                                        <tr class="bg-white">
                                            <td class="px-4 py-3 border-b text-sm font-semibold text-gray-900"><?= $i ?></td>
                                            <td class="px-4 py-3 border-b text-sm text-gray-800"><?= esc($row['application_reference'] ?? '') ?></td>
                                            <td class="px-4 py-3 border-b text-sm text-gray-800"><?= esc(ucwords(str_replace('_', ' ', (string) ($row['status'] ?? 'submitted')))) ?></td>
                                            <td class="px-4 py-3 border-b text-sm text-gray-800"><?= esc($row['submitted_at'] ?? '') ?></td>
                                        </tr>
                                    <?php $i++; endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <p class="mt-4 text-sm text-gray-500">
                            For detailed follow-up, contact support: <?= esc($supportPhone) ?> / <?= esc($supportEmail) ?>.
                        </p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="space-y-6">
                <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
                    <div class="text-sm font-semibold uppercase tracking-wide text-primary">Need help?</div>
                    <p class="mt-4 text-gray-700 leading-7">If you paid and cannot find your application, use WhatsApp and include your paid email and any PayChangu reference.</p>
                    <a href="<?= esc($whatsAppLink) ?>" target="_blank" rel="noopener" class="mt-5 inline-flex items-center justify-center w-full rounded-xl bg-primary px-6 py-3 font-bold text-white hover:bg-red-600 transition">
                        <i class="fab fa-whatsapp mr-2"></i>WhatsApp Support
                    </a>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
                    <div class="text-sm font-semibold uppercase tracking-wide text-primary">Start a new application</div>
                    <p class="mt-4 text-gray-700 leading-7">If you are applying for another person on the same device/browser, reset access first so a new payment flow can start.</p>
                    <a href="<?= site_url('teach-in-japan/access/reset') ?>" class="mt-5 inline-flex items-center justify-center w-full rounded-xl border border-primary px-6 py-3 font-bold text-primary hover:bg-red-50 transition">
                        Reset Access
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>

