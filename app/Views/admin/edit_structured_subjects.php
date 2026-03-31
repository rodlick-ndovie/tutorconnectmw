<?= $this->extend('layouts/admin') ?>

<?php $active_page = 'users'; ?>
<?php $title = $title ?? 'Edit Structured Subjects - TutorConnect Malawi'; ?>

<?= $this->section('content') ?>

<!-- Header -->
<div class="header-bar">
    <div>
        <h1 class="page-title">Edit Structured Subjects</h1>
        <p class="page-subtitle"><?php echo esc($user['first_name'] . ' ' . $user['last_name']); ?> - <?php echo esc($user['email']); ?></p>
    </div>
    <div style="display: flex; gap: 12px;">
        <a href="<?php echo base_url('admin/trainers/view/' . $user['id']); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Tutor Details
        </a>
        <a href="<?php echo base_url('admin/users'); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-users me-2"></i>Back to Users
        </a>
    </div>
</div>

<!-- Form Container -->
<div class="content-card">
    <form id="structuredSubjectsForm" method="post" action="<?php echo base_url('admin/structured-subjects/update/' . $user['id']); ?>">
        <?= csrf_field() ?>

        <div style="margin-bottom: 24px;">
            <h5 style="margin-bottom: 16px;">Manage Teaching Subjects by Curriculum</h5>
            <p style="color: var(--text-light); margin-bottom: 20px;">
                Organize the subjects this tutor teaches by curriculum and education level. Each curriculum can have multiple levels, and each level can have multiple subjects.
            </p>
        </div>

        <!-- Curriculum Selection -->
        <div style="margin-bottom: 24px;">
            <label style="font-weight: 600; font-size: 14px; color: var(--text-dark); margin-bottom: 12px; display: block;">
                Select Curricula to Manage:
            </label>
            <div id="curriculumSelection" style="display: flex; flex-wrap: wrap; gap: 12px;">
                <?php foreach ($curricula as $curriculum): ?>
                <label style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; border: 2px solid #e0e0e0; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; background: white;">
                    <input type="checkbox" class="curriculum-checkbox" value="<?php echo esc($curriculum); ?>"
                           style="margin: 0;" <?php echo in_array($curriculum, array_keys($structured_subjects ?? [])) ? 'checked' : ''; ?>>
                    <span style="font-weight: 500;"><?php echo esc($curriculumNames[$curriculum] ?? $curriculum); ?></span>
                </label>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Dynamic Curriculum Sections -->
        <div id="curriculumSections">
            <?php if (!empty($structured_subjects)): ?>
                <?php foreach ($structured_subjects as $curriculum => $curriculumData): ?>
                <div class="curriculum-section" data-curriculum="<?php echo esc($curriculum); ?>" style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px; margin-bottom: 20px; background: #fafbfc;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                        <h6 style="margin: 0; color: var(--primary); font-weight: 600;">
                            <i class="fas fa-book me-2"></i><?php echo esc($curriculumNames[$curriculum] ?? $curriculum); ?>
                        </h6>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-curriculum" data-curriculum="<?php echo esc($curriculum); ?>">
                            <i class="fas fa-trash me-1"></i>Remove Curriculum
                        </button>
                    </div>

                    <div class="levels-container">
                        <?php if (!empty($curriculumData['levels'])): ?>
                            <?php foreach ($curriculumData['levels'] as $level => $subjects): ?>
                            <div class="level-section" style="border-left: 3px solid #007bff; padding-left: 16px; margin-bottom: 16px; background: white; padding: 12px; border-radius: 6px;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                    <input type="text" class="form-control form-control-sm level-name" value="<?php echo esc($level); ?>"
                                           placeholder="Education Level (e.g., Form 1, IGCSE, etc.)" style="max-width: 300px;" required>
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-level">
                                        <i class="fas fa-trash me-1"></i>Remove Level
                                    </button>
                                </div>

                                <div class="subjects-container">
                                    <label style="font-weight: 500; font-size: 14px; color: var(--text-dark); margin-bottom: 8px; display: block;">
                                        Subjects for this level:
                                    </label>
                                    <div class="subjects-list" style="display: flex; flex-wrap: wrap; gap: 8px;">
                                        <?php if (!empty($subjects) && is_array($subjects)): ?>
                                            <?php foreach ($subjects as $subject): ?>
                                            <span class="subject-tag" style="display: inline-flex; align-items: center; gap: 6px; background: #e3f2fd; color: #1976d2; padding: 4px 10px; border-radius: 16px; font-size: 13px; font-weight: 500;">
                                                <span><?php echo esc($subject); ?></span>
                                                <button type="button" class="btn btn-sm btn-link p-0 text-danger remove-subject" style="font-size: 12px; line-height: 1;">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </span>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>

                                    <div style="margin-top: 12px;">
                                        <div style="display: flex; gap: 8px; align-items: center;">
                                            <select class="form-select form-select-sm available-subjects" style="max-width: 250px;">
                                                <option value="">Select subject to add...</option>
                                            </select>
                                            <button type="button" class="btn btn-sm btn-outline-primary add-subject">
                                                <i class="fas fa-plus me-1"></i>Add Subject
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <div style="margin-top: 16px;">
                            <button type="button" class="btn btn-sm btn-outline-secondary add-level">
                                <i class="fas fa-plus me-1"></i>Add Education Level
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Hidden input for structured subjects data -->
        <input type="hidden" name="structured_subjects" id="structuredSubjectsInput" value="<?php echo esc(json_encode($structured_subjects ?? [])); ?>">

        <!-- Form Actions -->
        <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 20px; border-top: 1px solid #e0e0e0; margin-top: 24px;">
            <div style="color: var(--text-light); font-size: 14px;">
                Changes will be saved as JSON data in the structured_subjects field.
            </div>
            <div style="display: flex; gap: 12px;">
                <a href="<?php echo base_url('admin/trainers/view/' . $user['id']); ?>" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Save Structured Subjects
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Templates for dynamic content -->
<template id="curriculumTemplate">
    <div class="curriculum-section" data-curriculum="{{curriculum}}" style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px; margin-bottom: 20px; background: #fafbfc;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h6 style="margin: 0; color: var(--primary); font-weight: 600;">
                <i class="fas fa-book me-2"></i>{{curriculumName}}
            </h6>
            <button type="button" class="btn btn-sm btn-outline-danger remove-curriculum" data-curriculum="{{curriculum}}">
                <i class="fas fa-trash me-1"></i>Remove Curriculum
            </button>
        </div>

        <div class="levels-container">
            <div style="margin-top: 16px;">
                <button type="button" class="btn btn-sm btn-outline-secondary add-level">
                    <i class="fas fa-plus me-1"></i>Add Education Level
                </button>
            </div>
        </div>
    </div>
</template>

<template id="levelTemplate">
    <div class="level-section" style="border-left: 3px solid #007bff; padding-left: 16px; margin-bottom: 16px; background: white; padding: 12px; border-radius: 6px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
            <input type="text" class="form-control form-control-sm level-name" placeholder="Education Level (e.g., Form 1, IGCSE, etc.)" style="max-width: 300px;" required>
            <button type="button" class="btn btn-sm btn-outline-danger remove-level">
                <i class="fas fa-trash me-1"></i>Remove Level
            </button>
        </div>

        <div class="subjects-container">
            <label style="font-weight: 500; font-size: 14px; color: var(--text-dark); margin-bottom: 8px; display: block;">
                Subjects for this level:
            </label>
            <div class="subjects-list" style="display: flex; flex-wrap: wrap; gap: 8px;">
                <!-- Subjects will be added here -->
            </div>

            <div style="margin-top: 12px;">
                <div style="display: flex; gap: 8px; align-items: center;">
                    <select class="form-select form-select-sm available-subjects" style="max-width: 250px;">
                        <option value="">Select subject to add...</option>
                    </select>
                    <button type="button" class="btn btn-sm btn-outline-primary add-subject">
                        <i class="fas fa-plus me-1"></i>Add Subject
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<template id="subjectTagTemplate">
    <span class="subject-tag" style="display: inline-flex; align-items: center; gap: 6px; background: #e3f2fd; color: #1976d2; padding: 4px 10px; border-radius: 16px; font-size: 13px; font-weight: 500;">
        <span>{{subject}}</span>
        <button type="button" class="btn btn-sm btn-link p-0 text-danger remove-subject" style="font-size: 12px; line-height: 1;">
            <i class="fas fa-times"></i>
        </button>
    </span>
</template>

<style>
.subject-tag:hover {
    background: #bbdefb;
}

.curriculum-checkbox:checked + span {
    color: var(--primary);
    font-weight: 600;
}

.form-select-sm {
    font-size: 13px;
}

.btn-link {
    text-decoration: none;
}
</style>

<script>
// Available subjects data (passed from controller)
const allSubjects = <?php echo json_encode($all_subjects ?? []); ?>;
const curriculumNames = <?php echo json_encode($curriculumNames ?? []); ?>;
let structuredSubjects = <?php echo json_encode($structured_subjects ?? []); ?>;

// Initialize the form
document.addEventListener('DOMContentLoaded', function() {
    initializeCurriculumSelection();
    initializeDynamicContent();
    updateStructuredSubjectsInput();

    // Form submission
    document.getElementById('structuredSubjectsForm').addEventListener('submit', function(e) {
        updateStructuredSubjectsInput();
    });
});

function initializeCurriculumSelection() {
    const checkboxes = document.querySelectorAll('.curriculum-checkbox');

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const curriculum = this.value;
            const isChecked = this.checked;

            if (isChecked) {
                addCurriculumSection(curriculum);
            } else {
                removeCurriculumSection(curriculum);
            }
        });
    });
}

function addCurriculumSection(curriculum) {
    if (document.querySelector(`[data-curriculum="${curriculum}"]`)) {
        return; // Already exists
    }

    const template = document.getElementById('curriculumTemplate');
    const clone = template.content.cloneNode(true);

    // Replace placeholders
    clone.querySelector('.curriculum-section').setAttribute('data-curriculum', curriculum);
    clone.querySelector('h6').innerHTML = `<i class="fas fa-book me-2"></i>${curriculumNames[curriculum] || curriculum}`;
    clone.querySelector('.remove-curriculum').setAttribute('data-curriculum', curriculum);

    // Initialize levels for this curriculum if it exists in structuredSubjects
    if (structuredSubjects[curriculum] && structuredSubjects[curriculum].levels) {
        const levelsContainer = clone.querySelector('.levels-container');
        Object.keys(structuredSubjects[curriculum].levels).forEach(level => {
            const levelElement = createLevelElement(curriculum, level, structuredSubjects[curriculum].levels[level]);
            levelsContainer.insertBefore(levelElement, levelsContainer.lastElementChild);
        });
    }

    document.getElementById('curriculumSections').appendChild(clone);

    // Initialize the new section
    initializeCurriculumSection(curriculum);
}

function removeCurriculumSection(curriculum) {
    const section = document.querySelector(`[data-curriculum="${curriculum}"]`);
    if (section) {
        section.remove();
    }

    // Remove from structured subjects
    delete structuredSubjects[curriculum];
    updateStructuredSubjectsInput();
}

function initializeCurriculumSection(curriculum) {
    const section = document.querySelector(`[data-curriculum="${curriculum}"]`);

    // Remove curriculum button
    section.querySelector('.remove-curriculum').addEventListener('click', function() {
        const curriculum = this.getAttribute('data-curriculum');
        removeCurriculumSection(curriculum);
        // Uncheck the checkbox
        document.querySelector(`.curriculum-checkbox[value="${curriculum}"]`).checked = false;
    });

    // Add level button
    section.querySelector('.add-level').addEventListener('click', function() {
        addLevelToCurriculum(curriculum);
    });

    // Initialize existing levels
    section.querySelectorAll('.level-section').forEach(levelSection => {
        initializeLevelSection(levelSection, curriculum);
    });
}

function addLevelToCurriculum(curriculum) {
    const section = document.querySelector(`[data-curriculum="${curriculum}"]`);
    const levelsContainer = section.querySelector('.levels-container');
    const levelElement = createLevelElement(curriculum);

    levelsContainer.insertBefore(levelElement, levelsContainer.lastElementChild);
    initializeLevelSection(levelElement, curriculum);
}

function createLevelElement(curriculum, levelName = '', subjects = []) {
    const template = document.getElementById('levelTemplate');
    const clone = template.content.cloneNode(true);

    const levelSection = clone.querySelector('.level-section');

    if (levelName) {
        levelSection.querySelector('.level-name').value = levelName;
    }

    // Add subjects if provided
    if (subjects && subjects.length > 0) {
        const subjectsList = levelSection.querySelector('.subjects-list');
        subjects.forEach(subject => {
            const subjectTag = createSubjectTag(subject);
            subjectsList.appendChild(subjectTag);
        });
    }

    return levelSection;
}

function initializeLevelSection(levelSection, curriculum) {
    // Level name change
    const levelNameInput = levelSection.querySelector('.level-name');
    levelNameInput.addEventListener('input', function() {
        updateStructuredSubjectsInput();
    });

    // Remove level button
    levelSection.querySelector('.remove-level').addEventListener('click', function() {
        levelSection.remove();
        updateStructuredSubjectsInput();
    });

    // Add subject functionality
    const addSubjectBtn = levelSection.querySelector('.add-subject');
    const subjectSelect = levelSection.querySelector('.available-subjects');

    // Populate subject select
    populateSubjectSelect(subjectSelect, curriculum);

    addSubjectBtn.addEventListener('click', function() {
        const selectedSubject = subjectSelect.value;
        if (selectedSubject) {
            addSubjectToLevel(levelSection, selectedSubject, curriculum);
            subjectSelect.value = '';
        }
    });

    // Initialize existing subject removal
    levelSection.querySelectorAll('.remove-subject').forEach(btn => {
        btn.addEventListener('click', function() {
            this.closest('.subject-tag').remove();
            updateStructuredSubjectsInput();
        });
    });
}

function populateSubjectSelect(selectElement, curriculum) {
    // Clear existing options except the first
    while (selectElement.children.length > 1) {
        selectElement.removeChild(selectElement.lastChild);
    }

    // Add subjects for this curriculum
    if (allSubjects[curriculum] && allSubjects[curriculum].length > 0) {
        allSubjects[curriculum].forEach(subject => {
            const option = document.createElement('option');
            option.value = subject.subject_name;
            option.textContent = subject.subject_name;
            selectElement.appendChild(option);
        });
    }
}

function addSubjectToLevel(levelSection, subject, curriculum) {
    // Check if subject already exists in this level
    const existingSubjects = Array.from(levelSection.querySelectorAll('.subject-tag span:first-child')).map(span => span.textContent);
    if (existingSubjects.includes(subject)) {
        alert('This subject is already added to this level.');
        return;
    }

    const subjectsList = levelSection.querySelector('.subjects-list');
    const subjectTag = createSubjectTag(subject);
    subjectsList.appendChild(subjectTag);

    // Initialize the remove button
    subjectTag.querySelector('.remove-subject').addEventListener('click', function() {
        subjectTag.remove();
        updateStructuredSubjectsInput();
    });

    updateStructuredSubjectsInput();
}

function createSubjectTag(subject) {
    const template = document.getElementById('subjectTagTemplate');
    const clone = template.content.cloneNode(true);
    clone.querySelector('span').textContent = subject;
    return clone.querySelector('.subject-tag');
}

function updateStructuredSubjectsInput() {
    const curriculumSections = document.querySelectorAll('.curriculum-section');
    const result = {};

    curriculumSections.forEach(section => {
        const curriculum = section.getAttribute('data-curriculum');
        result[curriculum] = { levels: {} };

        const levelSections = section.querySelectorAll('.level-section');
        levelSections.forEach(levelSection => {
            const levelName = levelSection.querySelector('.level-name').value.trim();
            if (levelName) {
                const subjects = Array.from(levelSection.querySelectorAll('.subject-tag span:first-child')).map(span => span.textContent);
                if (subjects.length > 0) {
                    result[curriculum].levels[levelName] = subjects;
                }
            }
        });
    });

    structuredSubjects = result;
    document.getElementById('structuredSubjectsInput').value = JSON.stringify(result);
}

function initializeDynamicContent() {
    // Initialize existing curriculum sections
    document.querySelectorAll('.curriculum-section').forEach(section => {
        const curriculum = section.getAttribute('data-curriculum');
        initializeCurriculumSection(curriculum);
    });
}
</script>

<?= $this->endSection() ?>
