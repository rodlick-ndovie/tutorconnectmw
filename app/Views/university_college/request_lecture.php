<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<?php
$errors = session('errors') ?? [];
$serviceCategoryMap = $serviceCategoryMap ?? [];
$oldCategory = old('service_category');
$oldTopic = old('topic');
$isCustomOldTopic = $oldCategory !== null
    && $oldTopic !== null
    && $oldTopic !== ''
    && !in_array($oldTopic, $serviceCategoryMap[$oldCategory] ?? [], true);
?>

<section class="bg-slate-50 border-b border-slate-200">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <a href="<?= site_url('university-college-support') ?>" class="inline-flex items-center text-sm font-semibold text-slate-600 hover:text-primary mb-6">
            <i class="fas fa-arrow-left mr-2"></i>University Support
        </a>
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_360px] gap-8 items-start">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold text-secondary leading-tight">Request Academic Support</h1>
                <p class="mt-4 text-slate-600 leading-7 max-w-3xl">Share the support you need and TutorConnect will review the request, then help route it to a suitable university or college specialist.</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-base font-semibold text-slate-900 mb-3">How Matching Works</h2>
                <div class="space-y-3 text-sm text-slate-600">
                    <div class="flex gap-3"><i class="fas fa-circle-check text-primary mt-1"></i><span>We review your subject, topic, mode, location, and timing.</span></div>
                    <div class="flex gap-3"><i class="fas fa-circle-check text-primary mt-1"></i><span>Only approved university support tutors are considered.</span></div>
                    <div class="flex gap-3"><i class="fas fa-circle-check text-primary mt-1"></i><span>Academic integrity rules apply to all requests.</span></div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <?php if (session('error')): ?>
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"><?= esc(session('error')) ?></div>
        <?php endif; ?>

        <form method="post" action="<?= site_url('university-college-support/request-lecture') ?>" class="border border-slate-200 rounded-lg shadow-sm p-5 sm:p-7 space-y-8">
            <?= csrf_field() ?>

            <div>
                <h2 class="text-lg font-semibold text-slate-900 mb-1">Requester Details</h2>
                <p class="text-sm text-slate-500">Use contact details where the team can reach you about this request.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <label class="block">
                    <span class="block text-sm font-semibold text-slate-800 mb-2">Full Name</span>
                    <input type="text" name="full_name" value="<?= esc(old('full_name')) ?>" class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:border-primary focus:ring-primary" required>
                    <?php if (!empty($errors['full_name'])): ?><span class="mt-1 block text-sm text-red-600"><?= esc($errors['full_name']) ?></span><?php endif; ?>
                </label>
                <label class="block">
                    <span class="block text-sm font-semibold text-slate-800 mb-2">Email Address</span>
                    <input type="email" name="email" value="<?= esc(old('email')) ?>" class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:border-primary focus:ring-primary" required>
                    <?php if (!empty($errors['email'])): ?><span class="mt-1 block text-sm text-red-600"><?= esc($errors['email']) ?></span><?php endif; ?>
                </label>
                <label class="block">
                    <span class="block text-sm font-semibold text-slate-800 mb-2">Phone Number</span>
                    <input type="text" name="phone" value="<?= esc(old('phone')) ?>" class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:border-primary focus:ring-primary" required>
                    <?php if (!empty($errors['phone'])): ?><span class="mt-1 block text-sm text-red-600"><?= esc($errors['phone']) ?></span><?php endif; ?>
                </label>
                <label class="block">
                    <span class="block text-sm font-semibold text-slate-800 mb-2">Institution</span>
                    <input type="text" name="institution" value="<?= esc(old('institution')) ?>" class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:border-primary focus:ring-primary" placeholder="University, college, or organisation" required>
                    <?php if (!empty($errors['institution'])): ?><span class="mt-1 block text-sm text-red-600"><?= esc($errors['institution']) ?></span><?php endif; ?>
                </label>
            </div>

            <div class="border-t border-slate-200 pt-7">
                <h2 class="text-lg font-semibold text-slate-900 mb-1">Support Requirements</h2>
                <p class="text-sm text-slate-500">Describe the academic area and the outcome you want support with.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <label class="block">
                    <span class="block text-sm font-semibold text-slate-800 mb-2">Service Category</span>
                    <select name="service_category" id="service_category" class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:border-primary focus:ring-primary" required>
                        <option value="">Select category</option>
                        <?php foreach ($serviceCategories as $category): ?>
                            <option value="<?= esc($category) ?>" <?= $oldCategory === $category ? 'selected' : '' ?>><?= esc($category) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!empty($errors['service_category'])): ?><span class="mt-1 block text-sm text-red-600"><?= esc($errors['service_category']) ?></span><?php endif; ?>
                </label>
                <label class="block">
                    <span class="block text-sm font-semibold text-slate-800 mb-2">Topic / Focus Area</span>
                    <select name="topic" id="topic" class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:border-primary focus:ring-primary" required data-old-topic="<?= esc($isCustomOldTopic ? '__other__' : (string) $oldTopic) ?>">
                        <option value="">Select a category first</option>
                    </select>
                    <?php if (!empty($errors['topic'])): ?><span class="mt-1 block text-sm text-red-600"><?= esc($errors['topic']) ?></span><?php endif; ?>
                </label>
            </div>

            <label class="block hidden" id="customTopicWrap">
                <span class="block text-sm font-semibold text-slate-800 mb-2">Specific Topic</span>
                <input type="text" name="custom_topic" id="custom_topic" value="<?= esc(old('custom_topic', $isCustomOldTopic ? $oldTopic : '')) ?>" class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:border-primary focus:ring-primary" placeholder="Example: Python loops, SPSS regression, proposal methodology">
            </label>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <label class="block">
                    <span class="block text-sm font-semibold text-slate-800 mb-2">Delivery Mode</span>
                    <select name="delivery_mode" class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:border-primary focus:ring-primary" required>
                        <option value="">Select mode</option>
                        <option value="Online" <?= old('delivery_mode') === 'Online' ? 'selected' : '' ?>>Online</option>
                        <option value="Physical" <?= old('delivery_mode') === 'Physical' ? 'selected' : '' ?>>Physical</option>
                        <option value="Both" <?= old('delivery_mode') === 'Both' ? 'selected' : '' ?>>Both</option>
                    </select>
                    <?php if (!empty($errors['delivery_mode'])): ?><span class="mt-1 block text-sm text-red-600"><?= esc($errors['delivery_mode']) ?></span><?php endif; ?>
                </label>
                <label class="block">
                    <span class="block text-sm font-semibold text-slate-800 mb-2">City / Location</span>
                    <input type="text" name="city_location" value="<?= esc(old('city_location')) ?>" class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:border-primary focus:ring-primary" required>
                    <?php if (!empty($errors['city_location'])): ?><span class="mt-1 block text-sm text-red-600"><?= esc($errors['city_location']) ?></span><?php endif; ?>
                </label>
                <label class="block">
                    <span class="block text-sm font-semibold text-slate-800 mb-2">Budget Range <span class="font-normal text-slate-500">(optional)</span></span>
                    <input type="text" name="budget_range" value="<?= esc(old('budget_range')) ?>" class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:border-primary focus:ring-primary" placeholder="MK20,000 - MK50,000">
                </label>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <label class="block">
                    <span class="block text-sm font-semibold text-slate-800 mb-2">Preferred Date <span class="font-normal text-slate-500">(optional)</span></span>
                    <input type="date" name="preferred_date" value="<?= esc(old('preferred_date')) ?>" class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:border-primary focus:ring-primary">
                    <?php if (!empty($errors['preferred_date'])): ?><span class="mt-1 block text-sm text-red-600"><?= esc($errors['preferred_date']) ?></span><?php endif; ?>
                </label>
                <label class="block">
                    <span class="block text-sm font-semibold text-slate-800 mb-2">Preferred Time <span class="font-normal text-slate-500">(optional)</span></span>
                    <input type="text" name="preferred_time" value="<?= esc(old('preferred_time')) ?>" class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:border-primary focus:ring-primary" placeholder="Example: 18:00 - 20:00">
                </label>
            </div>

            <label class="block">
                <span class="block text-sm font-semibold text-slate-800 mb-2">Additional Notes <span class="font-normal text-slate-500">(optional)</span></span>
                <textarea name="notes" rows="4" class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:border-primary focus:ring-primary" placeholder="Share deadlines, level of study, files you can provide later, or specific support expectations."><?= esc(old('notes')) ?></textarea>
            </label>

            <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                TutorConnect supports guidance, tutoring, review, and technical assistance. We do not support requests to complete assignments, exams, or dissertations on behalf of students.
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-t border-slate-200 pt-5">
                <p class="text-sm text-slate-600">Requests are reviewed and matched based on expertise and availability.</p>
                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-primary px-6 py-3 text-white font-semibold hover:bg-red-600 transition">
                    Submit Request <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </form>
    </div>
</section>

<script>
    const serviceTopicMap = <?= json_encode($serviceCategoryMap, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
    const categorySelect = document.getElementById('service_category');
    const topicSelect = document.getElementById('topic');
    const customTopicWrap = document.getElementById('customTopicWrap');
    const customTopicInput = document.getElementById('custom_topic');

    function syncTopicOptions() {
        if (!categorySelect || !topicSelect) {
            return;
        }

        const selectedCategory = categorySelect.value;
        const oldTopic = topicSelect.dataset.oldTopic || '';
        const topics = serviceTopicMap[selectedCategory] || [];

        topicSelect.innerHTML = '';
        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = topics.length > 0 ? 'Select topic' : 'Select a category first';
        topicSelect.appendChild(placeholder);

        topics.forEach((topic) => {
            const option = document.createElement('option');
            option.value = topic;
            option.textContent = topic;
            if (oldTopic === topic) {
                option.selected = true;
            }
            topicSelect.appendChild(option);
        });

        const otherOption = document.createElement('option');
        otherOption.value = '__other__';
        otherOption.textContent = 'Other / Specific topic';
        if (oldTopic === '__other__') {
            otherOption.selected = true;
        }
        topicSelect.appendChild(otherOption);

        syncCustomTopic();
    }

    function syncCustomTopic() {
        if (!topicSelect || !customTopicWrap || !customTopicInput) {
            return;
        }

        const isOther = topicSelect.value === '__other__';
        customTopicWrap.classList.toggle('hidden', !isOther);
        customTopicInput.required = isOther;
        if (!isOther) {
            customTopicInput.value = '';
        }
    }

    if (categorySelect) {
        categorySelect.addEventListener('change', () => {
            if (topicSelect) {
                topicSelect.dataset.oldTopic = '';
            }
            syncTopicOptions();
        });
    }

    if (topicSelect) {
        topicSelect.addEventListener('change', syncCustomTopic);
    }

    syncTopicOptions();
</script>

<?= $this->endSection() ?>
