<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-16">
    <div class="max-w-2xl mx-auto text-center">
        <!-- Error Illustration -->
        <div class="mb-8">
            <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-info-circle text-4xl text-blue-600"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Action Required</h1>
        </div>

        <!-- Error Message -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8 border-l-4 border-blue-500">
            <div class="text-center">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Access Information</h2>
                <p class="text-lg text-gray-700 leading-relaxed mb-6">
                    <?= esc($message ?? 'This page is not directly accessible. Please use the appropriate form to perform this action.') ?>
                </p>

                <?php if (isset($action) && isset($action_text)): ?>
                    <a href="<?= site_url($action) ?>"
                       class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-arrow-right mr-2"></i>
                        <?= esc($action_text) ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Help Section -->
        <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Need Help?</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-left">
                <div>
                    <h4 class="font-medium text-gray-900 mb-2">To Book a Tutor:</h4>
                    <ol class="text-sm text-gray-600 space-y-1">
                        <li>• Visit our <a href="<?= site_url('find-tutors') ?>" class="text-blue-600 hover:underline">Find Tutors</a> page</li>
                        <li>• Browse available tutors</li>
                        <li>• Click "View Profile" on any tutor</li>
                        <li>• Use the green booking form on their profile</li>
                    </ol>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900 mb-2">To Contact a Tutor:</h4>
                    <ol class="text-sm text-gray-600 space-y-1">
                        <li>• Visit tutor's profile page</li>
                        <li>• Find the contact form in sidebar</li>
                        <li>• Fill out and submit the form</li>
                        <li>• We'll forward your message</li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- Navigation Links -->
        <div class="mt-8">
            <div class="flex flex-wrap justify-center gap-4">
                <a href="<?= site_url('/') ?>" class="text-blue-600 hover:text-blue-800">← Back to Home</a>
                <span class="text-gray-400">•</span>
                <a href="<?= site_url('find-tutors') ?>" class="text-blue-600 hover:text-blue-800">Find Tutors</a>
                <span class="text-gray-400">•</span>
                <a href="<?= site_url('how-it-works') ?>" class="text-blue-600 hover:text-blue-800">How It Works</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
