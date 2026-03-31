<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subjects - TutorConnect Malawi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #059669;
            --secondary-color: #047857;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --accent-color: #0ea5e9;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --bg-primary: #f8fafc;
            --bg-secondary: #ffffff;
            --bg-accent: #f0f9ff;
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --border-radius: 16px;
            --border-radius-lg: 20px;
        }

        * { box-sizing: border-box; }

        body {
            background: var(--bg-primary);
            font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'SF Pro Text', 'Segoe UI', Roboto, sans-serif;
            margin: 0;
            padding: 0;
        }

        .app-container {
            width: 100%;
            max-width: 480px;
            margin: 0 auto;
            min-height: 100vh;
            background: var(--bg-primary);
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
        }

        @media (min-width: 768px) {
            .app-container { max-width: 100%; box-shadow: none; }
        }

        .status-bar { height: 44px; background: var(--bg-secondary); border-bottom: 1px solid rgba(0,0,0,0.1); position: sticky; top: 0; z-index: 100; }
        @media (min-width: 768px) { .status-bar { display: none; } }

        .navbar {
            background: var(--bg-secondary);
            border-bottom: 1px solid rgba(0,0,0,0.1);
            padding: 0;
            height: 64px;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .navbar .px-4 { width: 100%; }
        .navbar .d-flex.gap-2 { margin-left: auto !important; flex-shrink: 0; }
        .navbar button { width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 10px; transition: background 0.3s; flex-shrink: 0; }
        .navbar button:hover { background: var(--bg-primary); }
        @media (min-width: 768px) { .navbar { height: 80px; } }

        .avatar { width: 40px; height: 40px; border-radius: 12px; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 16px; }

        .screen { padding: 20px; padding-bottom: calc(120px + env(safe-area-inset-bottom, 0px)); max-width: 1200px; margin: 0 auto; }
        @media (min-width: 768px) { .screen { padding: 32px 40px 120px 40px; } }
        @media (min-width: 1024px) { .screen { max-width: 1400px; padding: 40px 60px 120px 60px; } }

        .page-header { margin-bottom: 20px; }
        .page-title { font-size: 26px; font-weight: 800; color: var(--text-dark); margin: 0; }
        .page-subtitle { color: var(--text-light); margin: 6px 0 0; font-size: 14px; }

        .pill-row { display: flex; flex-wrap: wrap; gap: 8px; }
        .pill { background: var(--bg-secondary); border: 1px solid rgba(0,0,0,0.05); border-radius: 999px; padding: 8px 12px; font-weight: 600; color: var(--text-dark); box-shadow: var(--shadow); font-size: 12px; }

        .card { border: 1px solid rgba(0,0,0,0.05); border-radius: var(--border-radius); box-shadow: var(--shadow); }
        .card-header { background: var(--bg-secondary); border-bottom: 1px solid rgba(0,0,0,0.05); font-weight: 700; color: var(--text-dark); }
        .card-body { background: var(--bg-secondary); }

        .category-title { font-weight: 700; color: var(--text-dark); margin-bottom: 10px; font-size: 15px; }
        .subject-item { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 10px; background: var(--bg-primary); border: 1px solid rgba(0,0,0,0.05); margin-bottom: 8px; }
        .subject-icon { width: 32px; height: 32px; border-radius: 10px; background: linear-gradient(135deg, #10b981, #059669); color: white; display: grid; place-items: center; font-size: 14px; flex-shrink: 0; }
        .subject-name { font-weight: 600; color: var(--text-dark); }
        .subject-desc { margin: 0; color: var(--text-light); font-size: 12px; }

        .empty-state { text-align: center; padding: 32px; color: var(--text-light); }
        .empty-icon { width: 64px; height: 64px; border-radius: 16px; background: var(--bg-secondary); display: grid; place-items: center; margin: 0 auto 12px; font-size: 26px; color: var(--primary-color); box-shadow: var(--shadow); }

        .bottom-nav { position: fixed; bottom: 0; left: 0; right: 0; width: 100%; max-width: 100%; background: var(--bg-secondary); border-top: 1px solid rgba(0, 0, 0, 0.1); display: flex; justify-content: space-between; gap: 8px; padding: 10px 14px calc(16px + env(safe-area-inset-bottom, 0px)); z-index: 100; }
        @media (min-width: 768px) { .bottom-nav { gap: 12px; } }

        .nav-item { flex: 1; min-width: 0; display: flex; flex-direction: column; align-items: center; text-decoration: none; color: var(--text-light); transition: all 0.3s ease; padding: 6px 4px; border-radius: 12px; }
        .nav-item:hover { background: rgba(0, 0, 0, 0.05); }
        .nav-item.active { color: var(--primary-color); }
        .nav-icon { font-size: 22px; margin-bottom: 4px; }
        .nav-label { font-size: 11px; font-weight: 600; text-align: center; white-space: nowrap; }

        .toast-container { position: fixed; top: 20px; right: 20px; z-index: 99999; max-width: 400px; }
        @media (max-width: 768px) { .toast-container { top: 60px; left: 20px; right: 20px; max-width: none; } }
        .toast { background: white; border-radius: 12px; box-shadow: 0 8px 24px rgba(0,0,0,0.2); padding: 16px 20px; margin-bottom: 12px; display: flex; align-items: center; gap: 12px; animation: slideIn 0.3s ease; border: 1px solid rgba(0,0,0,0.1); }
        .toast.success { border-left: 4px solid var(--success-color); }
        .toast.error { border-left: 4px solid var(--danger-color); }
        .toast-icon { width: 40px; height: 40px; border-radius: 10px; display: grid; place-items: center; font-size: 18px; flex-shrink: 0; }
        .toast.success .toast-icon { background: #d1fae5; color: var(--success-color); }
        .toast.error .toast-icon { background: #fee2e2; color: var(--danger-color); }
        .toast-content { flex: 1; }
        .toast-title { font-weight: 700; color: var(--text-dark); margin: 0 0 4px; font-size: 14px; }
        .toast-message { color: var(--text-light); margin: 0; font-size: 13px; }
        .toast-close { background: none; border: none; color: var(--text-light); cursor: pointer; font-size: 18px; padding: 0; width: 24px; height: 24px; display: grid; place-items: center; border-radius: 6px; transition: all 0.2s; }
        .toast-close:hover { background: rgba(0,0,0,0.05); color: var(--text-dark); }
        @keyframes slideIn { from { transform: translateX(400px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        @keyframes slideOut { from { transform: translateX(0); opacity: 1; } to { transform: translateX(400px); opacity: 0; } }
        .toast.hiding { animation: slideOut 0.3s ease forwards; }

        .save-status { min-width: 90px; text-align: right; font-weight: 700; font-size: 12px; }
        .save-status.badge { display: inline-flex; align-items: center; gap: 6px; padding: 6px 10px; border-radius: 999px; border: 1px solid rgba(0,0,0,0.08); }
        .save-status.success { background: #ecfdf3; color: #166534; border-color: #bbf7d0; }
        .save-status.error { background: #fef2f2; color: #b91c1c; border-color: #fecdd3; }
        .save-status.idle { background: #f8fafc; color: #475569; border-color: #e2e8f0; }

        .inline-toast { display: none; margin-bottom: 10px; border-radius: 10px; padding: 12px 14px; font-weight: 600; font-size: 13px; border: 1px solid rgba(0,0,0,0.08); box-shadow: 0 6px 18px rgba(0,0,0,0.12); transform: translateY(10px); opacity: 0; transition: all 0.25s ease; }
        .inline-toast.show { display: block; transform: translateY(0); opacity: 1; }
        .inline-toast.success { background: #ecfdf3; color: #166534; border-color: #bbf7d0; }
        .inline-toast.error { background: #fef2f2; color: #b91c1c; border-color: #fecdd3; }
    </style>
</head>
<body>
    <div class="app-container">
        <div class="status-bar"></div>

        <nav class="navbar">
            <div class="d-flex align-items-center justify-content-between px-4 py-3">
                <button onclick="window.location.href='<?= site_url('dashboard') ?>'" class="btn-back border-0 bg-transparent">
                    <i class="fas fa-arrow-left text-dark"></i>
                </button>
                <div class="d-flex gap-2">
                    <div class="avatar">
                        <?= strtoupper(substr(session()->get('first_name') ?? 'T', 0, 1)) ?>
                    </div>
                </div>
            </div>
        </nav>

        <main class="screen">
            <div class="page-header">
                <p class="page-subtitle mb-1">Subjects & Levels</p>
                <h1 class="page-title">Your teaching focus</h1>
            </div>

            <form id="subjectsForm">
                <div class="card mb-3">
                    <div class="card-header">Selected subjects</div>
                    <div class="card-body">
                        <div class="pill-row mb-2" id="selectedSubjectsPills">
                            <?php if (!empty($selected_subjects)): ?>
                                <?php foreach ($selected_subjects as $subject): ?>
                                    <span class="pill"><i class="fas fa-book me-2"></i><?= esc($subject) ?></span>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <div class="empty-state <?= !empty($selected_subjects) ? 'd-none' : '' ?>" id="noSubjectsState">
                            <div class="empty-icon"><i class="fas fa-book"></i></div>
                            <p class="mb-1">No subjects selected yet.</p>
                            <small>Pick subjects below to show here.</small>
                        </div>
                    </div>
                    <div class="card mb-0">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Curricula</span>
                            <div id="curriculaState" class="pill-row mb-0 <?= empty($selected_curricula) ? 'd-none' : '' ?>"></div>
                        </div>
                        <div class="card-body">
                            <?php foreach ($available_curricula as $key => $label): ?>
                                <?php $checked = in_array($key, $selected_curricula ?? []); ?>
                                <label class="subject-item" for="curriculum_<?= esc($key) ?>">
                                    <div class="subject-icon" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);"><i class="fas fa-atlas"></i></div>
                                    <div class="flex-grow-1">
                                        <div class="subject-name"><?= esc($label) ?></div>
                                    </div>
                                    <input type="checkbox" class="form-check-input" name="curriculum[]" id="curriculum_<?= esc($key) ?>" value="<?= esc($key) ?>" <?= $checked ? 'checked' : '' ?> style="margin-left:auto;">
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">Education levels</div>
                    <div class="card-body">
                        <div class="pill-row mb-2" id="selectedLevelsPills">
                            <?php if (!empty($selected_levels)): ?>
                                <?php foreach ($selected_levels as $level): ?>
                                    <span class="pill"><i class="fas fa-layer-group me-2"></i><?= esc($level) ?></span>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <?php if (empty($selected_levels)): ?>
                            <div class="empty-state" id="noLevelsState">
                                <div class="empty-icon"><i class="fas fa-layer-group"></i></div>
                                <p class="mb-1">No levels selected yet.</p>
                                <small>Select levels you teach to improve matching.</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">Curriculum → Levels → Subjects</div>
                    <div class="card-body">
                        <?php if (!empty($available_curricula)): ?>
                            <?php foreach ($available_curricula as $curriculumKey => $curriculumLabel): ?>
                                <?php 
                                    $levels = $levels_by_curriculum[$curriculumKey] ?? []; 
                                    $isCurriculumSelected = in_array($curriculumKey, $selected_curricula ?? []);
                                ?>
                                <div class="curriculum-section mb-4" data-curriculum="<?= esc($curriculumKey) ?>" style="border: 1px solid #e0e0e0; border-radius: 10px; padding: 12px; background: #fafbfc; <?= !$isCurriculumSelected ? 'display: none;' : '' ?>">
                                    <div class="category-title mb-2">
                                        <i class="fas fa-atlas me-2"></i><?= esc($curriculumLabel) ?>
                                    </div>

                                    <?php if (!empty($levels)): ?>
                                        <?php foreach ($levels as $levelName): ?>
                                            <?php
                                                $levelSubjects = $curriculum_subjects[$curriculumKey][$levelName] ?? [];
                                                $levelChecked = in_array($levelName, $selected_levels ?? []);
                                            ?>
                                            <div class="level-section mb-3" style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 10px;">
                                                <div class="d-flex align-items-center mb-2">
                                                    <div class="subject-icon" style="background: linear-gradient(135deg, #2563eb, #1d4ed8); width: 32px; height: 32px;"><i class="fas fa-graduation-cap"></i></div>
                                                    <div class="flex-grow-1 ms-2">
                                                        <div class="subject-name" style="font-size: 14px;"><?= esc($levelName) ?></div>
                                                    </div>
                                                    <input type="checkbox" class="form-check-input" name="education_levels[]" value="<?= esc($levelName) ?>" <?= $levelChecked ? 'checked' : '' ?>>
                                                </div>

                                                <?php if (!empty($levelSubjects)): ?>
                                                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 8px;">
                                                        <?php foreach ($levelSubjects as $subject): ?>
                                                            <?php
                                                                $subjectName = $subject['subject_name'] ?? ($subject['name'] ?? 'Subject');
                                                                $subjectId = $subject['id'] ?? $subjectName;
                                                                $isChecked = in_array($subjectName, $selected_subjects ?? []);
                                                            ?>
                                                            <label class="subject-item" for="sub_<?= esc($curriculumKey . '_' . $levelName . '_' . $subjectId) ?>">
                                                                <div class="subject-icon"><i class="fas fa-book-open"></i></div>
                                                                <div class="flex-grow-1">
                                                                    <div class="subject-name"><?= esc($subjectName) ?></div>
                                                                    <?php if (!empty($subject['subject_category'])): ?>
                                                                        <p class="subject-desc mb-0"><?= esc($subject['subject_category']) ?></p>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <input type="checkbox" class="form-check-input" name="subjects[]" id="sub_<?= esc($curriculumKey . '_' . $levelName . '_' . $subjectId) ?>" value="<?= esc($subjectName) ?>" <?= $isChecked ? 'checked' : '' ?> style="margin-left:auto;">
                                                            </label>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="empty-state">
                                                        <div class="empty-icon"><i class="fas fa-list"></i></div>
                                                        <p class="mb-1">No subjects for this level.</p>
                                                        <small>Ask admin to add subjects for <?= esc($levelName) ?>.</small>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="empty-state">
                                            <div class="empty-icon"><i class="fas fa-layer-group"></i></div>
                                            <p class="mb-1">No levels available for this curriculum.</p>
                                            <small>Ask admin to add levels for <?= esc($curriculumKey) ?>.</small>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-state">
                                <div class="empty-icon"><i class="fas fa-atlas"></i></div>
                                <p class="mb-1">No curricula available.</p>
                                <small>Please contact admin.</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div id="inlineToast" class="inline-toast" aria-live="polite"></div>
                <div class="d-flex align-items-center gap-2">
                    <button type="submit" class="btn btn-success flex-grow-1" id="saveSubjectsBtn">
                        <i class="fas fa-save me-2"></i>Save subjects & levels
                    </button>
                    <div id="saveStatus" class="text-muted small" aria-live="polite" style="display:none;"></div>
                </div>
            </form>
        </main>

        <nav class="bottom-nav">
            <a href="<?= site_url('dashboard') ?>" class="nav-item">
                <i class="fas fa-home nav-icon"></i>
                <span class="nav-label">Home</span>
            </a>
            <a href="<?= site_url('trainer/subjects') ?>" class="nav-item active">
                <i class="fas fa-book-open nav-icon"></i>
                <span class="nav-label">Subjects</span>
            </a>
            <a href="<?= site_url('trainer/profile') ?>" class="nav-item">
                <i class="fas fa-user nav-icon"></i>
                <span class="nav-label">Profile</span>
            </a>
        </nav>
    </div>

    <div class="toast-container" id="toastContainer"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toast notification system
        function showToast(type, title, message) {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            
            const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
            
            toast.innerHTML = `
                <div class="toast-icon">
                    <i class="fas ${iconClass}"></i>
                </div>
                <div class="toast-content">
                    <div class="toast-title">${title}</div>
                    <div class="toast-message">${message}</div>
                </div>
                <button class="toast-close" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            container.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.add('hiding');
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }

        const subjectsForm = document.getElementById('subjectsForm');
        const saveBtn = document.getElementById('saveSubjectsBtn');
        const selectedPills = document.getElementById('selectedSubjectsPills');
        const selectedLevelsPills = document.getElementById('selectedLevelsPills');
        const noSubjectsState = document.getElementById('noSubjectsState');
        const noLevelsState = document.getElementById('noLevelsState');
        const curriculaState = document.getElementById('curriculaState');

        function setSaveStatus(text, state = 'idle') {
            const status = document.getElementById('saveStatus');
            if (!status) return;
            status.textContent = text || '';
            status.className = `save-status badge ${state}`;
            status.style.display = text ? 'inline-flex' : 'none';
        }

        function showInlineToast(type, message) {
            const toast = document.getElementById('inlineToast');
            if (!toast) return;
            toast.textContent = message || '';
            toast.className = `inline-toast ${type} show`;

            clearTimeout(window.__inlineToastTimer);
            window.__inlineToastTimer = setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => { toast.style.display = 'none'; }, 250);
            }, 3000);
        }

        function refreshPills() {
            const subjectChecks = subjectsForm.querySelectorAll('input[name="subjects[]"]:checked');
            const levelChecks = subjectsForm.querySelectorAll('input[name="education_levels[]"]:checked');
            const curriculumChecks = subjectsForm.querySelectorAll('input[name="curriculum[]"]:checked');

            selectedPills.innerHTML = '';
            subjectChecks.forEach(cb => {
                const pill = document.createElement('span');
                pill.className = 'pill';
                pill.innerHTML = '<i class="fas fa-book me-2"></i>' + cb.value;
                selectedPills.appendChild(pill);
            });

            selectedLevelsPills.innerHTML = '';
            levelChecks.forEach(cb => {
                const pill = document.createElement('span');
                pill.className = 'pill';
                pill.innerHTML = '<i class="fas fa-layer-group me-2"></i>' + cb.value;
                selectedLevelsPills.appendChild(pill);
            });

            curriculaState.innerHTML = '';
            curriculumChecks.forEach(cb => {
                const pill = document.createElement('span');
                pill.className = 'pill';
                pill.innerHTML = '<i class="fas fa-atlas me-2"></i>' + cb.value;
                curriculaState.appendChild(pill);
            });

            noSubjectsState?.classList.toggle('d-none', subjectChecks.length > 0);
            noLevelsState?.classList.toggle('d-none', levelChecks.length > 0);
            curriculaState?.classList.toggle('d-none', curriculumChecks.length === 0);

            // Clear inline status when changing selections
            setSaveStatus('');
        }

        subjectsForm?.addEventListener('change', (e) => {
            if (e.target.matches('input[type="checkbox"]')) {
                refreshPills();
                
                // Show/hide curriculum sections when curriculum checkbox is toggled
                if (e.target.matches('input[name="curriculum[]"]')) {
                    const curriculumValue = e.target.value;
                    const curriculumSection = document.querySelector(`.curriculum-section[data-curriculum="${curriculumValue}"]`);
                    if (curriculumSection) {
                        curriculumSection.style.display = e.target.checked ? 'block' : 'none';
                    }
                }
            }
        });

        subjectsForm?.addEventListener('submit', async (e) => {
            e.preventDefault();
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
            setSaveStatus('Saving...', 'idle');

            const formData = new FormData(subjectsForm);

            try {
                const res = await fetch('<?= site_url('trainer/subjects/update') ?>', {
                    method: 'POST',
                    body: formData
                });

                const data = await res.json();

                if (data.success) {
                    refreshPills();
                    showToast('success', 'Saved Successfully', 'Your subjects and levels have been updated');
                    showInlineToast('success', 'Saved successfully');
                    setSaveStatus('', 'success');
                } else {
                    showToast('error', 'Update Failed', data.message || 'Failed to update subjects');
                    showInlineToast('error', data.message || 'Failed to save');
                    setSaveStatus('Failed to save', 'error');
                }
            } catch (err) {
                console.error(err);
                showToast('error', 'Error Occurred', 'An error occurred while saving your changes');
                showInlineToast('error', 'Error saving changes');
                setSaveStatus('Error saving', 'error');
            } finally {
                saveBtn.disabled = false;
                saveBtn.innerHTML = '<i class="fas fa-save me-2"></i>Save subjects & levels';
            }
        });

        // Initial pill render
        refreshPills();
        setSaveStatus('', 'idle');
    </script>
</body>
</html>
