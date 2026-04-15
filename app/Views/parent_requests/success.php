<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<section class="bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6 sm:p-8">
            <div class="inline-flex h-12 w-12 items-center justify-center rounded-lg bg-green-100 text-green-700">
                <i class="fas fa-check text-xl"></i>
            </div>

            <h1 class="mt-5 text-3xl font-extrabold text-secondary">Your request has been submitted</h1>
            <p class="mt-3 text-gray-600 leading-7">
                We found <?= (int) ($request['matched_tutor_count'] ?? 0) ?> matching teacher<?= ((int) ($request['matched_tutor_count'] ?? 0)) === 1 ? '' : 's' ?> and sent <?= (int) ($request['emailed_tutor_count'] ?? 0) ?> email notification<?= ((int) ($request['emailed_tutor_count'] ?? 0)) === 1 ? '' : 's' ?>.
            </p>

            <div class="mt-6 rounded-lg border border-gray-200 bg-gray-50 p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <div class="font-semibold text-gray-900">Reference</div>
                        <div class="text-gray-700"><?= esc($request['reference_code']) ?></div>
                    </div>
                    <div>
                        <div class="font-semibold text-gray-900">Curriculum</div>
                        <div class="text-gray-700"><?= esc($request['curriculum']) ?></div>
                    </div>
                    <div>
                        <div class="font-semibold text-gray-900">Grade / Class</div>
                        <div class="text-gray-700"><?= esc($request['grade_class']) ?></div>
                    </div>
                    <div>
                        <div class="font-semibold text-gray-900">Subject(s)</div>
                        <div class="text-gray-700"><?= esc(implode(', ', $request['subjects'])) ?></div>
                    </div>
                    <div>
                        <div class="font-semibold text-gray-900">Location</div>
                        <div class="text-gray-700"><?= esc($request['district']) ?>, <?= esc($request['specific_location']) ?></div>
                    </div>
                    <div>
                        <div class="font-semibold text-gray-900">Mode</div>
                        <div class="text-gray-700"><?= esc($modeLabel) ?></div>
                    </div>
                    <div>
                        <div class="font-semibold text-gray-900">Budget</div>
                        <div class="text-gray-700"><?= esc($budgetLabel) ?></div>
                    </div>
                    <div>
                        <div class="font-semibold text-gray-900">Contact</div>
                        <div class="text-gray-700"><?= esc($request['parent_phone']) ?> / <?= esc($request['parent_email']) ?></div>
                    </div>
                </div>
            </div>

            <?php if ((int) ($request['emailed_tutor_count'] ?? 0) === 0): ?>
                <div class="mt-5 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                    No matching active teachers could be emailed immediately. TutorConnect Malawi has still received your request.
                </div>
            <?php else: ?>
                <div class="mt-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    Teachers who apply will send their details through TutorConnect Malawi.
                </div>
            <?php endif; ?>

            <div class="mt-7 flex flex-col sm:flex-row gap-3">
                <a href="<?= site_url('request-teacher') ?>" class="inline-flex justify-center rounded-lg bg-primary px-5 py-3 text-white font-bold hover:bg-red-600 transition">
                    Submit Another Request
                </a>
                <a href="<?= site_url('find-tutors') ?>" class="inline-flex justify-center rounded-lg border border-gray-300 px-5 py-3 text-gray-700 font-semibold hover:bg-gray-50 transition">
                    Browse Teachers
                </a>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
