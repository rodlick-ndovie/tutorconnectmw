<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<?php
    $errors = session('errors') ?? [];
    $oldSubjects = old('subjects') ?: [];
    if (!is_array($oldSubjects)) {
        $oldSubjects = [$oldSubjects];
    }
?>

<section class="bg-white border-b border-gray-200">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="max-w-3xl">
            <p class="text-sm font-semibold text-primary uppercase tracking-wide">Parent Request</p>
            <h1 class="mt-2 text-3xl md:text-4xl font-extrabold text-secondary">Tell us the teacher your child needs</h1>
            <p class="mt-3 text-gray-600 leading-7">Your request will go only to verified teachers whose active subscriptions, subjects, and teaching mode match what you submit.</p>
        </div>
    </div>
</section>

<section class="bg-gray-50">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <?php if (session('error')): ?>
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <?= esc(session('error')) ?>
            </div>
        <?php endif; ?>

        <?php if (empty($curricula)): ?>
            <div class="mb-6 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                Curriculum subjects are not available yet. Please contact TutorConnect Malawi directly.
            </div>
        <?php endif; ?>

        <form method="post" action="<?= site_url('request-teacher') ?>" class="bg-white border border-gray-200 rounded-lg shadow-sm p-5 sm:p-7 space-y-6">
            <?= csrf_field() ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <label class="block">
                    <span class="block text-sm font-semibold text-gray-800 mb-2">Curriculum</span>
                    <select name="curriculum" id="curriculum" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-primary focus:ring-primary" required>
                        <option value="">Select curriculum</option>
                        <?php foreach ($curricula as $curriculum): ?>
                            <option value="<?= esc($curriculum) ?>" <?= old('curriculum') === $curriculum ? 'selected' : '' ?>>
                                <?= esc($curriculum) ?>
                            </option>
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
                            <option value="<?= esc($district) ?>" <?= old('district') === $district ? 'selected' : '' ?>>
                                <?= esc($district) ?>
                            </option>
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
                            <option value="<?= esc($key) ?>" <?= old('budget_range') === $key ? 'selected' : '' ?>>
                                <?= esc($option['label']) ?>
                            </option>
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
                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-primary px-6 py-3 text-white font-bold hover:bg-red-600 transition">
                    Submit Request
                </button>
            </div>
        </form>
    </div>
</section>

<script>
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
</script>

<?= $this->endSection() ?>
