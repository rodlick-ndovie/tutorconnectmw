<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<section class="bg-gray-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6 sm:p-8">
            <?php if (!empty($closed)): ?>
                <div class="inline-flex h-12 w-12 items-center justify-center rounded-lg bg-amber-100 text-amber-700">
                    <i class="fas fa-lock text-xl"></i>
                </div>
                <h1 class="mt-5 text-3xl font-extrabold text-secondary">This request is closed</h1>
                <p class="mt-3 text-gray-600 leading-7">This university support request is no longer accepting tutor responses.</p>
            <?php else: ?>
                <div class="inline-flex h-12 w-12 items-center justify-center rounded-lg bg-green-100 text-green-700">
                    <i class="fas fa-circle-check text-xl"></i>
                </div>
                <h1 class="mt-5 text-3xl font-extrabold text-secondary">
                    <?= !empty($alreadyAccepted) ? 'You already accepted this request' : 'Request accepted' ?>
                </h1>
                <p class="mt-3 text-gray-600 leading-7">
                    <?= !empty($alreadyAccepted) ? 'Your interest in this academic support request was already recorded.' : 'The requester has been sent your registered contact details and profile link.' ?>
                </p>
            <?php endif; ?>

            <div class="mt-6 rounded-lg border border-gray-200 bg-gray-50 p-5">
                <h2 class="text-lg font-bold text-secondary mb-4">Request summary</h2>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between gap-4">
                        <span class="font-semibold text-gray-900">Reference</span>
                        <span class="text-gray-700 text-right"><?= esc($request['reference_code']) ?></span>
                    </div>
                    <div class="flex justify-between gap-4">
                        <span class="font-semibold text-gray-900">Institution</span>
                        <span class="text-gray-700 text-right"><?= esc($request['institution']) ?></span>
                    </div>
                    <div class="flex justify-between gap-4">
                        <span class="font-semibold text-gray-900">Service Category</span>
                        <span class="text-gray-700 text-right"><?= esc($request['service_category']) ?></span>
                    </div>
                    <div class="flex justify-between gap-4">
                        <span class="font-semibold text-gray-900">Topic</span>
                        <span class="text-gray-700 text-right"><?= esc($request['topic']) ?></span>
                    </div>
                    <div class="flex justify-between gap-4">
                        <span class="font-semibold text-gray-900">Mode</span>
                        <span class="text-gray-700 text-right"><?= esc(($request['delivery_mode'] ?? '') === 'Both' ? 'Online or physical' : ($request['delivery_mode'] ?? '')) ?></span>
                    </div>
                    <div class="flex justify-between gap-4">
                        <span class="font-semibold text-gray-900">Location</span>
                        <span class="text-gray-700 text-right"><?= esc($request['city_location']) ?></span>
                    </div>
                </div>
            </div>

            <div class="mt-7">
                <a href="<?= site_url('university-portal/dashboard') ?>" class="inline-flex justify-center rounded-lg bg-primary px-5 py-3 text-white font-bold hover:bg-red-600 transition">
                    Go to University Portal
                </a>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
