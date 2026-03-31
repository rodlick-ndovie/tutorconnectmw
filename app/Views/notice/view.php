<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="max-w-4xl mx-auto px-4 py-12">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <!-- Notice Header -->
        <div class="bg-gradient-to-r from-primary to-red-600 px-8 py-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <span class="inline-block px-4 py-1 bg-white/20 text-white text-sm font-semibold rounded-full">
                    <?= esc($notice['notice_type']) ?>
                </span>
                <span class="text-sm">
                    <i class="far fa-clock mr-1"></i>
                    <?= date('F d, Y', strtotime($notice['approved_at'])) ?>
                </span>
            </div>
            <h1 class="text-3xl font-bold mb-2"><?= esc($notice['notice_title']) ?></h1>
        </div>

        <!-- School Information -->
        <div class="px-8 py-6 bg-gray-50 border-b">
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <div class="flex items-center text-gray-700 mb-3">
                        <i class="fas fa-school text-2xl text-primary mr-3"></i>
                        <div>
                            <div class="text-xs text-gray-500">School Name</div>
                            <div class="font-bold text-lg"><?= esc($notice['school_name']) ?></div>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center text-gray-700 mb-3">
                        <i class="fas fa-tag text-2xl text-blue-600 mr-3"></i>
                        <div>
                            <div class="text-xs text-gray-500">School Type</div>
                            <div class="font-bold text-lg"><?= esc($notice['school_type']) ?></div>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center text-gray-700 mb-3">
                        <i class="fas fa-phone text-2xl text-green-600 mr-3"></i>
                        <div>
                            <div class="text-xs text-gray-500">Contact Phone</div>
                            <div class="font-bold text-lg">
                                <a href="tel:<?= esc($notice['phone']) ?>" class="hover:text-primary">
                                    <?= esc($notice['phone']) ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center text-gray-700 mb-3">
                        <i class="fas fa-envelope text-2xl text-purple-600 mr-3"></i>
                        <div>
                            <div class="text-xs text-gray-500">Email Address</div>
                            <div class="font-bold text-lg">
                                <a href="mailto:<?= esc($notice['email']) ?>" class="hover:text-primary">
                                    <?= esc($notice['email']) ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attached Image -->
        <?php if (!empty($notice['attached_image'])): ?>
            <div class="px-8 py-6 bg-white">
                <img src="<?= base_url($notice['attached_image']) ?>"
                     alt="<?= esc($notice['notice_title']) ?>"
                     class="w-full rounded-lg shadow-lg">
            </div>
        <?php endif; ?>

        <!-- Notice Content -->
        <div class="px-8 py-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Notice Details</h2>
            <div class="prose max-w-none text-gray-700 whitespace-pre-wrap leading-relaxed">
                <?= nl2br(esc($notice['notice_content'])) ?>
            </div>
        </div>

        <!-- Actions -->
        <div class="px-8 py-6 bg-gray-50 border-t flex flex-wrap gap-4">
            <a href="tel:<?= esc($notice['phone']) ?>"
               class="flex-1 px-6 py-3 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition text-center">
                <i class="fas fa-phone mr-2"></i>Call School
            </a>
            <a href="mailto:<?= esc($notice['email']) ?>"
               class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition text-center">
                <i class="fas fa-envelope mr-2"></i>Send Email
            </a>
            <a href="<?= site_url('notice') ?>"
               class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition text-center">
                <i class="fas fa-arrow-left mr-2"></i>Back to Notices
            </a>
        </div>

        <!-- Stats Footer -->
        <div class="px-8 py-4 bg-white border-t text-center text-sm text-gray-500">
            <i class="fas fa-eye mr-2"></i><?= number_format($notice['views_count']) ?> views
        </div>
    </div>

    <!-- Related Notices -->
    <div class="mt-12">
        <h3 class="text-2xl font-bold text-gray-900 mb-6">More from <?= esc($notice['school_name']) ?></h3>
        <!-- You can add related notices here later -->
    </div>
</div>

<?= $this->endSection() ?>
