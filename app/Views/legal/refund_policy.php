<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<!-- Hero Section -->
<div class="bg-gradient-to-r from-primary to-red-600 py-12 md:py-20">
    <div class="max-w-4xl mx-auto px-4">
        <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-4">Refund Policy</h1>
        <p class="text-red-100 text-lg">Last updated: January 5, 2026</p>
    </div>
</div>
<!-- Content Section -->
<div class="max-w-4xl mx-auto px-4 py-12 md:py-16">
    <section class="mb-12">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6">Refund Policy</h2>
        <p class="text-gray-700 leading-relaxed text-xl bg-yellow-50 p-6 border-l-4 border-yellow-400">
            <strong>We do not offer refunds once a subscription is purchased and activated.</strong>
        </p>
        <p class="text-gray-700 leading-relaxed mt-6">
            All subscriptions are non-refundable. Please review your purchase carefully before completing it.
        </p>
    </section>

    <!-- CTA -->
    <section class="mt-16 bg-gradient-to-r from-primary to-red-600 rounded-2xl shadow-lg p-8 md:p-12 text-white">
        <h2 class="text-2xl md:text-3xl font-bold mb-4">Questions?</h2>
        <p class="text-red-100 mb-6">Contact our support team for any inquiries.</p>
        <a href="mailto:support@tutorconnectmw.com" class="inline-block bg-white text-primary px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition transform hover:scale-105 duration-300">
            Contact Support
        </a>
    </section>
</div>
<?= $this->endSection() ?>