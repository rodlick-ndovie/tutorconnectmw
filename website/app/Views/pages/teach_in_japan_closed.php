<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<?php
$supportPhone = site_setting('support_phone', '+265 992 313 978');
$supportEmail = site_setting('contact_email', 'info@tutorconnectmw.com');
$whatsAppLink = 'https://wa.me/265992313978?text=' . rawurlencode('Hello TutorConnect Malawi, I want to inquire about the Japan teaching opportunity.');
$closedMessage = trim((string) ($applicationsClosedMessage ?? ''));
if ($closedMessage === '') {
    $closedMessage = 'Japan applications are currently closed. Please check back soon or contact support.';
}
?>

<section class="relative overflow-hidden text-white py-20">
    <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('<?= base_url('assets/japan.jpg') ?>');"></div>
    <div class="absolute inset-0 bg-gradient-to-r from-primary/90 via-primary/80 to-orange-600/75"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl">
            <a href="<?= site_url('teach-in-japan') ?>" class="inline-flex items-center text-red-100 hover:text-white font-medium transition">
                <i class="fas fa-arrow-left mr-2"></i>Back to Japan Opportunity Details
            </a>
            <h1 class="mt-6 text-5xl md:text-6xl font-extrabold leading-tight">Applications Closed</h1>
            <p class="mt-6 text-xl text-red-50 leading-relaxed">
                <?= esc($closedMessage) ?>
            </p>
            <div class="mt-8 flex flex-col sm:flex-row gap-4">
                <a href="<?= site_url('teach-in-japan/status') ?>" class="block px-6 py-3 bg-white/10 text-white font-bold rounded-lg border border-white/30 hover:bg-white/15 transition text-center">
                    <i class="fas fa-search mr-2"></i>Check Status
                </a>
                <a href="<?= esc($whatsAppLink) ?>" target="_blank" rel="noopener" class="block px-6 py-3 bg-white text-primary font-bold rounded-lg hover:bg-red-50 transition text-center">
                    <i class="fab fa-whatsapp mr-2"></i>Contact on WhatsApp
                </a>
            </div>
            <div class="mt-6 text-sm text-red-100">Call / WhatsApp: <?= esc($supportPhone) ?> | Email: <?= esc($supportEmail) ?></div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>

