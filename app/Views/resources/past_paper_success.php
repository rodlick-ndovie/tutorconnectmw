<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<section class="bg-gradient-to-br from-emerald-600 via-green-600 to-teal-700 py-16">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="inline-flex h-20 w-20 items-center justify-center rounded-full bg-white/15 text-white">
            <i class="fas fa-check text-4xl"></i>
        </div>
        <h1 class="mt-6 text-4xl md:text-5xl font-bold text-white">Payment Successful</h1>
        <p class="mt-4 text-lg md:text-xl text-emerald-50 max-w-3xl mx-auto">
            Your payment has been confirmed. We are starting your past paper download now.
        </p>
    </div>
</section>

<section class="py-12 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
            <div class="lg:col-span-3">
                <div class="bg-white rounded-3xl shadow-xl p-6 md:p-8">
                    <div class="flex items-start gap-4">
                        <div class="h-14 w-14 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-2xl">
                            <i class="fas fa-file-arrow-down"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Your download is ready</h2>
                            <p class="text-gray-600 mt-2">
                                If the file does not start automatically, use the button below to download it again.
                            </p>
                        </div>
                    </div>

                    <div id="downloadStatus" class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-4 text-sm text-emerald-800">
                        Preparing your file download...
                    </div>

                    <div class="mt-8 flex flex-col sm:flex-row gap-3">
                        <a
                            href="<?= esc($download_url) ?>"
                            id="downloadAgainLink"
                            class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-6 py-4 text-white font-semibold transition hover:bg-emerald-700"
                        >
                            <i class="fas fa-download mr-2"></i>
                            Download Again
                        </a>
                        <a
                            href="<?= site_url('resources/past-papers') ?>"
                            class="inline-flex items-center justify-center rounded-2xl border border-gray-300 bg-white px-6 py-4 text-gray-700 font-semibold transition hover:bg-gray-50"
                        >
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Past Papers
                        </a>
                    </div>

                    <div class="mt-6 text-sm text-gray-500">
                        Payment reference: <strong><?= esc($purchase['tx_ref'] ?? '') ?></strong>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-emerald-600 to-teal-600 text-white p-6">
                        <div class="text-xs uppercase tracking-[0.2em] text-emerald-100 font-semibold">Paid Paper</div>
                        <h3 class="text-2xl font-bold mt-2"><?= esc($paper['paper_title'] ?? 'Past Paper') ?></h3>
                        <p class="text-emerald-100 text-sm mt-2">
                            <?= esc($paper['exam_body'] ?? '') ?> &bull; <?= esc($paper['exam_level'] ?? '') ?>
                        </p>
                    </div>

                    <div class="p-6 space-y-4 text-sm">
                        <div class="flex items-center justify-between gap-4">
                            <span class="text-gray-500">Subject</span>
                            <span class="font-semibold text-gray-900 text-right"><?= esc($paper['subject'] ?? 'N/A') ?></span>
                        </div>
                        <div class="flex items-center justify-between gap-4">
                            <span class="text-gray-500">Year</span>
                            <span class="font-semibold text-gray-900 text-right"><?= esc($paper['year'] ?? 'N/A') ?></span>
                        </div>
                        <div class="flex items-center justify-between gap-4">
                            <span class="text-gray-500">Amount Paid</span>
                            <span class="font-semibold text-gray-900 text-right">MK <?= number_format((float) ($paper['price'] ?? 0), 0) ?></span>
                        </div>
                        <div class="flex items-center justify-between gap-4">
                            <span class="text-gray-500">Email Used</span>
                            <span class="font-semibold text-gray-900 text-right break-all"><?= esc($purchase['buyer_email'] ?? '') ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<iframe id="pastPaperDownloadFrame" title="Past paper download" class="hidden"></iframe>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const downloadUrl = <?= json_encode($download_url, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
        const downloadFrame = document.getElementById('pastPaperDownloadFrame');
        const downloadStatus = document.getElementById('downloadStatus');

        if (downloadFrame && downloadUrl) {
            downloadFrame.src = downloadUrl;
            if (downloadStatus) {
                downloadStatus.textContent = 'Your download should begin automatically. If it does not, use "Download Again".';
            }
        } else if (downloadStatus) {
            downloadStatus.textContent = 'We could not start the automatic download. Please use "Download Again".';
        }
    });
</script>
<?= $this->endSection() ?>
