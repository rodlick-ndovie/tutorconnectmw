<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<?php
    $errors = session('errors') ?? [];
    $oldSubjects = old('subjects') ?: [];
    if (!is_array($oldSubjects)) {
        $oldSubjects = [$oldSubjects];
    }

    $serviceCategoryMap = $serviceCategoryMap ?? [];
    $oldCategory = old('service_category');
    $oldTopic = old('topic');
    $isCustomOldTopic = $oldCategory !== null
        && $oldTopic !== null
        && $oldTopic !== ''
        && !in_array($oldTopic, $serviceCategoryMap[$oldCategory] ?? [], true);
    $activeType = old('request_type', $selectedRequestType ?? 'school');
    $activeType = $activeType === 'university' ? 'university' : 'school';
?>

<section class="bg-white border-b border-gray-200">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="max-w-3xl">
            <p class="text-sm font-semibold text-primary uppercase tracking-wide">Tutor Request</p>
            <h1 class="mt-2 text-3xl md:text-4xl font-extrabold text-secondary">Tell us the type of support you need</h1>
            <p class="mt-3 text-gray-600 leading-7">Choose the request type first. The form will show only the fields needed for that teacher category.</p>
        </div>
    </div>
</section>

<section class="bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <?php if (session('error')): ?>
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <?= esc(session('error')) ?>
            </div>
        <?php endif; ?>

        <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4" role="tablist" aria-label="Request type">
            <button type="button" data-request-tab="school" class="request-tab text-left rounded-lg border p-5 transition <?= $activeType === 'school' ? 'border-primary bg-white shadow-sm' : 'border-gray-200 bg-white hover:border-primary' ?>">
                <span class="inline-flex items-center text-sm font-bold text-secondary"><i class="fas fa-chalkboard-teacher text-primary mr-2"></i>School Teacher</span>
                <span class="mt-2 block text-sm text-gray-600 leading-6">For primary, secondary, curriculum, grade/class, and subject-based tutoring.</span>
            </button>
            <button type="button" data-request-tab="university" class="request-tab text-left rounded-lg border p-5 transition <?= $activeType === 'university' ? 'border-primary bg-white shadow-sm' : 'border-gray-200 bg-white hover:border-primary' ?>">
                <span class="inline-flex items-center text-sm font-bold text-secondary"><i class="fas fa-user-graduate text-primary mr-2"></i>University Specialist</span>
                <span class="mt-2 block text-sm text-gray-600 leading-6">For college, dissertation, ICT, statistics, research, finance, and academic consultations.</span>
            </button>
        </div>

        <div id="schoolRequestPanel" class="<?= $activeType === 'school' ? '' : 'hidden' ?>">
            <?php if (empty($curricula)): ?>
                <div class="mb-6 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                    Curriculum subjects are not available yet. Please contact TutorConnect Malawi directly.
                </div>
            <?php endif; ?>

            <form method="post" action="<?= site_url('request-tutor') ?>" class="bg-white border border-gray-200 rounded-lg shadow-sm p-5 sm:p-7 space-y-6">
                <?= csrf_field() ?>
                <input type="hidden" name="request_type" value="school">

                <div class="border-b border-gray-200 pb-5">
                    <h2 class="text-xl font-extrabold text-secondary">School teacher request</h2>
                    <p class="mt-2 text-sm text-gray-600">Your request will go only to verified teachers whose active subscriptions, subjects, and teaching mode match what you submit.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <label class="block">
                        <span class="block text-sm font-semibold text-gray-800 mb-2">Curriculum</span>
                        <select name="curriculum" id="curriculum" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary" required>
                            <option value="">Select curriculum</option>
                            <?php foreach ($curricula as $curriculum): ?>
                                <option value="<?= esc($curriculum) ?>" <?= old('curriculum') === $curriculum ? 'selected' : '' ?>><?= esc($curriculum) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (!empty($errors['curriculum'])): ?><span class="mt-1 block text-sm text-red-600"><?= esc($errors['curriculum']) ?></span><?php endif; ?>
                    </label>

                    <label class="block">
                        <span class="block text-sm font-semibold text-gray-800 mb-2">Grade / Class</span>
                        <select name="grade_class" id="gradeClass" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary" required>
                            <option value="">Select grade / class</option>
                        </select>
                        <?php if (!empty($errors['grade_class'])): ?><span class="mt-1 block text-sm text-red-600"><?= esc($errors['grade_class']) ?></span><?php endif; ?>
                    </label>
                </div>

                <div>
                    <div class="flex items-center justify-between gap-3 mb-2">
                        <span class="block text-sm font-semibold text-gray-800">Subject(s)</span>
                        <span class="text-xs text-gray-500">Choose one or more</span>
                    </div>
                    <div id="subjectsContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 rounded-lg border border-gray-300 bg-gray-50 p-3 min-h-24">
                        <p class="text-sm text-gray-500">Select a curriculum and grade/class first.</p>
                    </div>
                    <?php if (!empty($errors['subjects'])): ?><span class="mt-1 block text-sm text-red-600"><?= esc($errors['subjects']) ?></span><?php endif; ?>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <label class="block">
                        <span class="block text-sm font-semibold text-gray-800 mb-2">District</span>
                        <select name="district" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary" required>
                            <option value="">Select district</option>
                            <?php foreach ($districts as $district): ?>
                                <option value="<?= esc($district) ?>" <?= old('district') === $district ? 'selected' : '' ?>><?= esc($district) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (!empty($errors['district'])): ?><span class="mt-1 block text-sm text-red-600"><?= esc($errors['district']) ?></span><?php endif; ?>
                    </label>

                    <label class="block">
                        <span class="block text-sm font-semibold text-gray-800 mb-2">Specific location</span>
                        <input type="text" name="specific_location" value="<?= esc(old('specific_location')) ?>" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary" placeholder="Area, township, school area, or landmark" required>
                        <?php if (!empty($errors['specific_location'])): ?><span class="mt-1 block text-sm text-red-600"><?= esc($errors['specific_location']) ?></span><?php endif; ?>
                    </label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <label class="block">
                        <span class="block text-sm font-semibold text-gray-800 mb-2">Mode</span>
                        <select name="mode" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary" required>
                            <option value="">Select mode</option>
                            <option value="online" <?= old('mode') === 'online' ? 'selected' : '' ?>>Online</option>
                            <option value="physical" <?= old('mode') === 'physical' ? 'selected' : '' ?>>Physical</option>
                        </select>
                        <?php if (!empty($errors['mode'])): ?><span class="mt-1 block text-sm text-red-600"><?= esc($errors['mode']) ?></span><?php endif; ?>
                    </label>

                    <label class="block">
                        <span class="block text-sm font-semibold text-gray-800 mb-2">Budget</span>
                        <select name="budget_range" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary" required>
                            <option value="">Select budget range</option>
                            <?php foreach ($budgetOptions as $key => $option): ?>
                                <option value="<?= esc($key) ?>" <?= old('budget_range') === $key ? 'selected' : '' ?>><?= esc($option['label']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (!empty($errors['budget_range'])): ?><span class="mt-1 block text-sm text-red-600"><?= esc($errors['budget_range']) ?></span><?php endif; ?>
                    </label>
                </div>

                <label class="block">
                    <span class="block text-sm font-semibold text-gray-800 mb-2">Notes / special requirements</span>
                    <textarea name="notes" rows="4" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary" placeholder="Preferred lesson times, exam goals, learning needs, or anything the teacher should know."><?= esc(old('notes')) ?></textarea>
                    <?php if (!empty($errors['notes'])): ?><span class="mt-1 block text-sm text-red-600"><?= esc($errors['notes']) ?></span><?php endif; ?>
                </label>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <label class="block">
                        <span class="block text-sm font-semibold text-gray-800 mb-2">Phone</span>
                        <input type="tel" name="parent_phone" value="<?= esc(old('parent_phone')) ?>" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary" placeholder="+265..." required>
                        <?php if (!empty($errors['parent_phone'])): ?><span class="mt-1 block text-sm text-red-600"><?= esc($errors['parent_phone']) ?></span><?php endif; ?>
                    </label>

                    <label class="block">
                        <span class="block text-sm font-semibold text-gray-800 mb-2">Email</span>
                        <input type="email" name="parent_email" value="<?= esc(old('parent_email')) ?>" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary" placeholder="you@example.com" required>
                        <?php if (!empty($errors['parent_email'])): ?><span class="mt-1 block text-sm text-red-600"><?= esc($errors['parent_email']) ?></span><?php endif; ?>
                    </label>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-t border-gray-200 pt-5">
                    <p class="text-sm text-gray-600">Only matching active teachers will receive this request.</p>
                    <button type="submit" data-loading-button data-loading-text="Submitting school request..." class="inline-flex items-center justify-center rounded-lg bg-primary px-6 py-3 text-white font-bold hover:bg-red-600 transition disabled:cursor-not-allowed disabled:opacity-75">
                        <span data-button-label>Submit School Request</span>
                        <span data-button-loading class="hidden items-center">
                            <i class="fas fa-spinner fa-spin mr-2"></i>Submitting school request...
                        </span>
                    </button>
                </div>
            </form>
        </div>

        <div id="universityRequestPanel" class="<?= $activeType === 'university' ? '' : 'hidden' ?>">
            <form method="post" action="<?= site_url('university-college-support/request-lecture') ?>" class="bg-white border border-slate-200 rounded-lg shadow-sm p-5 sm:p-7 space-y-8">
                <?= csrf_field() ?>
                <input type="hidden" name="request_type" value="university">

                <div class="border-b border-slate-200 pb-5">
                    <h2 class="text-xl font-extrabold text-secondary">University specialist request</h2>
                    <p class="mt-2 text-sm text-slate-600">Share the academic support you need. We route suitable requests to approved university and college specialists.</p>
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
                    <h3 class="text-lg font-semibold text-slate-900 mb-1">Support Requirements</h3>
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
                        <input type="text" name="budget_range" value="<?= esc($activeType === 'university' ? old('budget_range') : '') ?>" class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:border-primary focus:ring-primary" placeholder="MK20,000 - MK50,000">
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
                    <textarea name="notes" rows="4" class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:border-primary focus:ring-primary" placeholder="Share deadlines, level of study, files you can provide later, or specific support expectations."><?= esc($activeType === 'university' ? old('notes') : '') ?></textarea>
                </label>

                <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                    TutorConnect supports guidance, tutoring, review, and technical assistance. We do not support requests to complete assignments, exams, or dissertations on behalf of students.
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-t border-slate-200 pt-5">
                    <p class="text-sm text-slate-600">Requests are reviewed and matched based on expertise and availability.</p>
                    <button type="submit" data-loading-button data-loading-text="Submitting university request..." class="inline-flex items-center justify-center rounded-lg bg-primary px-6 py-3 text-white font-semibold hover:bg-red-600 transition disabled:cursor-not-allowed disabled:opacity-75">
                        <span data-button-label>Submit University Request <i class="fas fa-arrow-right ml-2"></i></span>
                        <span data-button-loading class="hidden items-center">
                            <i class="fas fa-spinner fa-spin mr-2"></i>Submitting university request...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
    const requestTabs = document.querySelectorAll('[data-request-tab]');
    const schoolPanel = document.getElementById('schoolRequestPanel');
    const universityPanel = document.getElementById('universityRequestPanel');

    function setRequestType(type) {
        const isUniversity = type === 'university';
        schoolPanel.classList.toggle('hidden', isUniversity);
        universityPanel.classList.toggle('hidden', !isUniversity);
        requestTabs.forEach((tab) => {
            const active = tab.dataset.requestTab === type;
            tab.classList.toggle('border-primary', active);
            tab.classList.toggle('shadow-sm', active);
            tab.classList.toggle('border-gray-200', !active);
        });
        const url = new URL(window.location.href);
        url.searchParams.set('type', type);
        window.history.replaceState({}, '', url);
    }

    requestTabs.forEach((tab) => {
        tab.addEventListener('click', () => setRequestType(tab.dataset.requestTab));
    });

    document.querySelectorAll('form').forEach((form) => {
        form.addEventListener('submit', () => {
            const button = form.querySelector('[data-loading-button]');
            if (!button) {
                return;
            }

            const label = button.querySelector('[data-button-label]');
            const loading = button.querySelector('[data-button-loading]');

            button.disabled = true;
            button.setAttribute('aria-busy', 'true');

            if (label) {
                label.classList.add('hidden');
            }

            if (loading) {
                loading.classList.remove('hidden');
                loading.classList.add('inline-flex');
            }
        });
    });

    const curriculumSubjects = <?= json_encode($curriculumSubjects, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
    const oldCurriculum = <?= json_encode(old('curriculum') ?? '') ?>;
    const oldGradeClass = <?= json_encode(old('grade_class') ?? '') ?>;
    const oldSubjects = <?= json_encode(array_values($oldSubjects)) ?>;

    const curriculumSelect = document.getElementById('curriculum');
    const gradeSelect = document.getElementById('gradeClass');
    const subjectsContainer = document.getElementById('subjectsContainer');

    function updateGrades() {
        const curriculum = curriculumSelect.value;
        const levels = curriculumSubjects[curriculum] ? Object.keys(curriculumSubjects[curriculum]) : [];

        gradeSelect.innerHTML = '<option value="">Select grade / class</option>';
        levels.forEach((level) => {
            const option = document.createElement('option');
            option.value = level;
            option.textContent = level;
            if (level === oldGradeClass) {
                option.selected = true;
            }
            gradeSelect.appendChild(option);
        });

        updateSubjects();
    }

    function updateSubjects() {
        const curriculum = curriculumSelect.value;
        const grade = gradeSelect.value;
        const subjects = curriculumSubjects[curriculum] && curriculumSubjects[curriculum][grade]
            ? curriculumSubjects[curriculum][grade]
            : [];

        subjectsContainer.innerHTML = '';

        if (!subjects.length) {
            const message = document.createElement('p');
            message.className = 'text-sm text-gray-500';
            message.textContent = curriculum ? 'Select a grade/class to load subjects.' : 'Select a curriculum and grade/class first.';
            subjectsContainer.appendChild(message);
            return;
        }

        subjects.forEach((subject, index) => {
            const id = 'subject_' + index;
            const label = document.createElement('label');
            label.className = 'flex items-center gap-2 rounded-md bg-white border border-gray-200 px-3 py-2 text-sm text-gray-700';

            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.name = 'subjects[]';
            checkbox.value = subject;
            checkbox.id = id;
            checkbox.className = 'rounded border-gray-300 text-primary focus:ring-primary';
            checkbox.checked = oldSubjects.includes(subject);

            const span = document.createElement('span');
            span.textContent = subject;

            label.appendChild(checkbox);
            label.appendChild(span);
            subjectsContainer.appendChild(label);
        });
    }

    curriculumSelect.addEventListener('change', updateGrades);
    gradeSelect.addEventListener('change', updateSubjects);

    if (oldCurriculum) {
        curriculumSelect.value = oldCurriculum;
    }
    updateGrades();

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

    categorySelect.addEventListener('change', () => {
        topicSelect.dataset.oldTopic = '';
        syncTopicOptions();
    });
    topicSelect.addEventListener('change', syncCustomTopic);
    syncTopicOptions();
</script>

<?= $this->endSection() ?>
