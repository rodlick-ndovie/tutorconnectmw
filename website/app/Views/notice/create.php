<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<?php
$validation = \Config\Services::validation();
?>

<div class="max-w-4xl mx-auto px-4 py-12">
    <div class="bg-white rounded-2xl shadow-xl p-8">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Post a School Notice</h1>
            <p class="text-gray-600">Share announcements, vacancies, and exam results with students and parents</p>
            <p class="text-sm text-green-600 mt-2">
                <i class="fas fa-gift mr-1"></i> Free until June 2026 | After: MK5,000/month for unlimited posts
            </p>
        </div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <?php if ($validation->getErrors()): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    <?php foreach ($validation->getErrors() as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form id="noticeForm" action="<?= site_url('notice/store') ?>" method="post" class="space-y-6">
            <?= csrf_field() ?>

            <!-- Honeypot for bot protection -->
            <div style="display:none;">
                <input type="text" name="website" id="website" placeholder="Website (do not fill)" />
            </div>

            <!-- School Information -->
            <div class="border-b pb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">
                    <i class="fas fa-school text-primary mr-2"></i>School Information
                </h2>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            School Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="school_name"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                               placeholder="Enter school name"
                               value="<?= old('school_name') ?>" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Type of School <span class="text-red-500">*</span>
                        </label>
                        <select name="school_type"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" required>
                            <option value="">Select type...</option>
                            <option value="Nursery/Preschool" <?= old('school_type') == 'Nursery/Preschool' ? 'selected' : '' ?>>Nursery/Preschool</option>
                            <option value="Primary School" <?= old('school_type') == 'Primary School' ? 'selected' : '' ?>>Primary School</option>
                            <option value="Secondary School" <?= old('school_type') == 'Secondary School' ? 'selected' : '' ?>>Secondary School</option>
                            <option value="Community Day Secondary School" <?= old('school_type') == 'Community Day Secondary School' ? 'selected' : '' ?>>Community Day Secondary School</option>
                            <option value="Conventional Secondary School" <?= old('school_type') == 'Conventional Secondary School' ? 'selected' : '' ?>>Conventional Secondary School</option>
                            <option value="Technical/Vocational School" <?= old('school_type') == 'Technical/Vocational School' ? 'selected' : '' ?>>Technical/Vocational School</option>
                            <option value="College" <?= old('school_type') == 'College' ? 'selected' : '' ?>>College</option>
                            <option value="University" <?= old('school_type') == 'University' ? 'selected' : '' ?>>University</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Phone Number <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" name="phone"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                               placeholder="e.g., 0999123456"
                               value="<?= old('phone') ?>" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                               placeholder="school@example.com"
                               value="<?= old('email') ?>" required>
                    </div>
                </div>
            </div>

            <!-- Notice Details -->
            <div class="border-b pb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">
                    <i class="fas fa-bullhorn text-primary mr-2"></i>Notice Details
                </h2>

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Type of Notice <span class="text-red-500">*</span>
                        </label>
                        <select name="notice_type"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" required>
                            <option value="">Select type...</option>
                            <option value="Vacancy" <?= old('notice_type') == 'Vacancy' ? 'selected' : '' ?>>Job Vacancy</option>
                            <option value="Notice" <?= old('notice_type') == 'Notice' ? 'selected' : '' ?>>General Notice</option>
                            <option value="Announcement" <?= old('notice_type') == 'Announcement' ? 'selected' : '' ?>>Announcement</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Notice Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="notice_title"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                               placeholder="e.g., Teaching Position Available, End of Term Results, etc."
                               value="<?= old('notice_title') ?>" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Notice Content <span class="text-red-500">*</span>
                        </label>
                        <textarea name="notice_content" rows="8"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                  placeholder="Enter the full details of your notice, announcement, or vacancy. You can include exam results, job requirements, event details, etc."
                                  required><?= old('notice_content') ?></textarea>
                        <p class="text-sm text-gray-500 mt-1">Minimum 20 characters required</p>
                    </div>
                </div>
            </div>

            <!-- Important Information -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h3 class="font-semibold text-blue-900 mb-3">
                    <i class="fas fa-info-circle mr-2"></i>Important Information
                </h3>
                <ul class="text-sm text-blue-800 space-y-2">
                    <li><i class="fas fa-check-circle text-blue-600 mr-2"></i>Your notice will be reviewed by our admin team before being published</li>
                    <li><i class="fas fa-check-circle text-blue-600 mr-2"></i>We will verify the authenticity with your school</li>
                    <li><i class="fas fa-check-circle text-blue-600 mr-2"></i>Approved notices will be displayed on the notice board</li>
                    <li><i class="fas fa-check-circle text-blue-600 mr-2"></i>This service is FREE until June 2026</li>
                </ul>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end gap-4">
                <a href="<?= site_url('notice') ?>"
                   class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button id="submitBtn" type="submit"
                        class="px-8 py-3 bg-primary text-white rounded-lg hover:bg-red-700 transition shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
                        onclick="handleSubmit(event)">
                    <i class="fas fa-paper-plane mr-2"></i>
                    <span id="btnText">Submit Notice</span>
                    <span id="spinner" class="hidden">
                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<script>
function handleSubmit(event) {
    const submitBtn = document.getElementById('submitBtn');
    const spinner = document.getElementById('spinner');
    const btnText = document.getElementById('btnText');
    const websiteField = document.getElementById('website');

    // Check honeypot (bot protection)
    if (websiteField && websiteField.value) {
        event.preventDefault();
        console.log('Bot detected');
        return false;
    }

    // Prevent double submission
    if (submitBtn.disabled) {
        event.preventDefault();
        return false;
    }

    // Show spinner and disable button
    submitBtn.disabled = true;
    spinner.classList.remove('hidden');
    btnText.classList.add('hidden');

    // Submit form after a short delay
    setTimeout(() => {
        document.getElementById('noticeForm').submit();
    }, 300);
}

// Prevent form resubmission on page reload
if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}
</script>

<style>
    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    .animate-spin {
        animation: spin 1s linear infinite;
    }
</style>
