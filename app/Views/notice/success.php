<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="max-w-4xl mx-auto px-4 py-12 text-center">
    <div class="bg-white rounded-2xl shadow-xl p-12">
        <div class="mb-6">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check-circle text-5xl text-green-500"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-3">Notice Submitted Successfully!</h1>
            <p class="text-lg text-gray-600">
                Thank you for submitting your notice. Our admin team will review and verify it with your school.
            </p>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
            <h3 class="font-semibold text-blue-900 mb-3">What happens next?</h3>
            <ul class="text-left text-sm text-blue-800 space-y-2">
                <li><i class="fas fa-arrow-right text-blue-600 mr-2"></i>Our team will verify your submission</li>
                <li><i class="fas fa-arrow-right text-blue-600 mr-2"></i>We may contact your school for confirmation</li>
                <li><i class="fas fa-arrow-right text-blue-600 mr-2"></i>Once approved, your notice will appear on the notice board</li>
                <li><i class="fas fa-arrow-right text-blue-600 mr-2"></i>You will receive an email notification</li>
            </ul>
        </div>

        <div class="flex justify-center gap-4">
            <a href="<?= site_url('notice') ?>"
               class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-red-700 transition">
                <i class="fas fa-eye mr-2"></i>View Notice Board
            </a>
            <a href="<?= site_url('notice/create') ?>"
               class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                <i class="fas fa-plus mr-2"></i>Submit Another Notice
            </a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
