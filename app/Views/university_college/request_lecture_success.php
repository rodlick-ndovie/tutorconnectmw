<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<section class="bg-gray-50 min-h-[70vh]">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="bg-white border border-green-200 rounded-xl shadow-sm p-8 text-center">
            <div class="w-14 h-14 rounded-full bg-green-100 text-green-700 mx-auto flex items-center justify-center text-2xl">
                <i class="fas fa-check"></i>
            </div>
            <h1 class="mt-4 text-3xl font-extrabold text-secondary">Academic Support Request Submitted</h1>
            <p class="mt-3 text-gray-700">
                We found <?= (int) ($record['matched_tutor_count'] ?? 0) ?> matching tutor<?= ((int) ($record['matched_tutor_count'] ?? 0)) === 1 ? '' : 's' ?>
                and sent <?= (int) ($record['emailed_tutor_count'] ?? 0) ?> email notification<?= ((int) ($record['emailed_tutor_count'] ?? 0)) === 1 ? '' : 's' ?>.
            </p>

            <div class="mt-6 rounded-lg border border-gray-200 bg-gray-50 p-4 text-left">
                <p class="text-sm text-gray-600">Reference Code</p>
                <p class="text-xl font-bold text-secondary"><?= esc($record['reference_code']) ?></p>

                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    <div class="rounded-lg bg-white border border-gray-200 p-3">
                        <p class="text-sm text-gray-600">Service Category</p>
                        <p class="font-semibold text-gray-900"><?= esc($record['service_category']) ?></p>
                    </div>
                    <div class="rounded-lg bg-white border border-gray-200 p-3">
                        <p class="text-sm text-gray-600">Topic</p>
                        <p class="font-semibold text-gray-900"><?= esc($record['topic']) ?></p>
                    </div>
                </div>

                <p class="text-sm text-gray-600 mt-3">Current Status</p>
                <p class="font-semibold text-amber-700"><?= esc(ucwords(str_replace('_', ' ', $record['status'] ?? 'open'))) ?></p>
            </div>

            <?php if ((int) ($record['emailed_tutor_count'] ?? 0) === 0): ?>
                <div class="mt-5 rounded-lg border border-amber-200 bg-amber-50 p-4 text-left text-sm text-amber-900">
                    Your request has still been saved. Our team will review it and follow up if an immediate tutor match is not available.
                </div>
            <?php endif; ?>

            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <a href="<?= site_url('university-college-support') ?>" class="inline-flex items-center rounded-lg border border-primary px-6 py-3 text-primary font-bold hover:bg-primary hover:text-white transition">Back to Module</a>
                <a href="<?= site_url('request-tutor?type=university') ?>" class="inline-flex items-center rounded-lg bg-primary px-6 py-3 text-white font-bold hover:bg-red-600 transition">Submit Another Request</a>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
