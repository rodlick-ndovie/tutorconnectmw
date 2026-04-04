<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<section class="bg-gradient-to-br from-orange-600 via-orange-700 to-red-800 py-16">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                <i class="fas fa-lock-open mr-4 text-yellow-300"></i>
                Unlock Paid Past Paper
            </h1>
            <p class="text-xl text-orange-100 max-w-3xl mx-auto">
                Complete one secure payment to download this past paper immediately.
            </p>
        </div>
    </div>
</section>

<section class="py-12 bg-gray-50">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-orange-600 to-red-600 text-white p-6">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="text-xs uppercase tracking-[0.2em] text-orange-100 font-semibold">Paid Paper</div>
                                <h2 class="text-2xl font-bold mt-2"><?= esc($paper['paper_title'] ?? 'Past Paper') ?></h2>
                                <p class="text-orange-100 text-sm mt-2">
                                    <?= esc($paper['exam_body'] ?? '') ?> &bull; <?= esc($paper['exam_level'] ?? '') ?>
                                </p>
                            </div>
                            <div class="text-right">
                                <div class="text-xs uppercase tracking-[0.2em] text-orange-100 font-semibold">Amount</div>
                                <div class="text-3xl font-extrabold mt-2">MK <?= number_format((float) ($paper['price'] ?? 0), 0) ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between text-sm text-gray-600">
                            <span><i class="fas fa-book mr-2 text-orange-500"></i><?= esc($paper['subject'] ?? '') ?></span>
                            <span><i class="fas fa-calendar mr-2 text-orange-500"></i><?= esc($paper['year'] ?? '') ?></span>
                        </div>

                        <?php if (!empty($paper['paper_code'])): ?>
                            <div class="text-sm text-gray-600">
                                <i class="fas fa-hashtag mr-2 text-orange-500"></i><?= esc($paper['paper_code']) ?>
                            </div>
                        <?php endif; ?>

                        <div class="text-sm text-gray-600">
                            <i class="fas fa-file-pdf mr-2 text-orange-500"></i><?= esc($paper['file_size'] ?? 'Unknown size') ?>
                        </div>

                        <div class="rounded-2xl bg-amber-50 border border-amber-200 px-4 py-4 text-sm text-amber-800">
                            <strong>How this works:</strong> pay once, download immediately, then use the same email later to restore access without paying again.
                        </div>

                        <a href="<?= site_url('resources/past-papers') ?>" class="inline-flex items-center text-sm font-semibold text-orange-700 hover:text-orange-800">
                            <i class="fas fa-arrow-left mr-2"></i>Back to past papers
                        </a>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-3">
                <div class="bg-white rounded-3xl shadow-xl p-6 md:p-8">
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 mb-5">
                            <?= esc(session()->getFlashdata('error')) ?>
                        </div>
                    <?php endif; ?>
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 mb-5">
                            <?= esc(session()->getFlashdata('success')) ?>
                        </div>
                    <?php endif; ?>
                    <?php if (session()->getFlashdata('info')): ?>
                        <div class="rounded-2xl border border-orange-200 bg-orange-50 px-4 py-3 text-sm text-orange-700 mb-5">
                            <?= esc(session()->getFlashdata('info')) ?>
                        </div>
                    <?php endif; ?>

                    <div class="mb-6">
                        <h3 class="text-2xl font-bold text-gray-900">Payment Details</h3>
                        <p class="text-gray-600 mt-2">Use the same email address later if you need to restore this paid download on another device.</p>
                    </div>

                    <div class="mb-6 rounded-3xl border border-orange-200 bg-orange-50 p-5">
                        <h4 class="text-lg font-bold text-gray-900">Already Paid?</h4>
                        <p class="text-sm text-gray-600 mt-2">Use the same paid email and we will restore this download without asking you to pay again.</p>

                        <form method="post" action="<?= site_url('resources/past-papers/access/restore') ?>" class="mt-4 space-y-3">
                            <?= csrf_field() ?>
                            <input type="hidden" name="paper_id" value="<?= (int) ($paper['id'] ?? 0) ?>">

                            <div>
                                <label for="restore_email" class="block text-sm font-semibold text-gray-700 mb-2">Paid Email Address</label>
                                <input type="email" id="restore_email" name="restore_email" value="<?= esc(old('restore_email', $current_user['email'] ?? '')) ?>" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200" placeholder="you@example.com" required>
                            </div>

                            <button type="submit" class="inline-flex items-center justify-center rounded-2xl border border-orange-600 bg-white px-5 py-3 font-semibold text-orange-700 transition hover:bg-orange-100">
                                <i class="fas fa-unlock-alt mr-2"></i>Restore Download Access
                            </button>
                        </form>
                    </div>

                    <div id="paperPaymentFeedback" class="hidden rounded-2xl border px-4 py-3 text-sm mb-5"></div>

                    <form id="paperPaymentForm" class="space-y-5">
                        <?= csrf_field() ?>
                        <input type="hidden" name="paper_id" value="<?= (int) ($paper['id'] ?? 0) ?>">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="buyer_full_name" class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                                <input type="text" id="buyer_full_name" name="buyer_full_name" value="<?= esc($current_user['full_name'] ?? '') ?>" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200" required>
                            </div>
                            <div>
                                <label for="buyer_phone" class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
                                <input type="text" id="buyer_phone" name="buyer_phone" value="<?= esc($current_user['phone'] ?? '') ?>" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200" required>
                            </div>
                        </div>

                        <div>
                            <label for="buyer_email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                            <input type="email" id="buyer_email" name="buyer_email" value="<?= esc($current_user['email'] ?? '') ?>" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200" required>
                        </div>

                        <label class="flex items-start gap-3 rounded-2xl border border-gray-200 bg-gray-50 px-4 py-4 text-sm text-gray-700">
                            <input type="checkbox" name="paper_terms" value="1" class="mt-1 h-4 w-4 rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                            <span>I understand this is a paid download and access will be linked to the email I provide here.</span>
                        </label>

                        <button type="submit" id="paperPaymentBtn" class="w-full rounded-2xl bg-orange-600 px-6 py-4 text-lg font-semibold text-white transition hover:bg-orange-700">
                            <span id="paperPaymentBtnText">Pay MK <?= number_format((float) ($paper['price'] ?? 0), 0) ?> & Download</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<div id="wrapper"></div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://in.paychangu.com/js/popup.js"></script>
<script>
    const originalPaperConsoleError = console.error;
    const originalPaperConsoleWarn = console.warn;

    console.error = function (...args) {
        if (args[0] && typeof args[0] === 'string') {
            const message = args[0].toLowerCase();
            if (
                message.includes('unsafe attempt to initiate navigation') ||
                message.includes('failed to set a named property') ||
                message.includes('minified react error') ||
                message.includes('client-side exception') ||
                message.includes('paychangu') ||
                (args[0].includes('4bd1b696') && args[0].includes('js'))
            ) {
                return;
            }
        }

        originalPaperConsoleError.apply(console, args);
    };

    console.warn = function (...args) {
        if (args[0] && typeof args[0] === 'string') {
            const message = args[0].toLowerCase();
            if (message.includes('slow network') || message.includes('fallback font') || message.includes('paychangu')) {
                return;
            }
        }

        originalPaperConsoleWarn.apply(console, args);
    };

    document.addEventListener('DOMContentLoaded', function () {
        let checkoutWrapper = document.getElementById('wrapper');
        if (!checkoutWrapper) {
            checkoutWrapper = document.createElement('div');
            checkoutWrapper.id = 'wrapper';
            document.body.appendChild(checkoutWrapper);
        }

        const paymentForm = document.getElementById('paperPaymentForm');
        const paymentBtn = document.getElementById('paperPaymentBtn');
        const paymentBtnText = document.getElementById('paperPaymentBtnText');
        const feedbackBox = document.getElementById('paperPaymentFeedback');
        const callbackPath = new URL('<?= site_url('resources/past-papers/payment/callback') ?>', window.location.href).pathname;
        const returnPath = new URL('<?= site_url('resources/past-papers/payment/return') ?>', window.location.href).pathname;
        const logoPath = new URL('<?= base_url('favicon.ico') ?>', window.location.href).pathname;

        function normalizeCheckoutUrl(rawUrl, fallbackPath) {
            try {
                const parsed = new URL(rawUrl || fallbackPath, window.location.href);
                const host = (parsed.hostname || '').toLowerCase();

                if (!host || host === 'localhost' || host === '127.0.0.1' || parsed.origin !== window.location.origin) {
                    return window.location.origin + (parsed.pathname || fallbackPath) + (parsed.search || '');
                }

                return parsed.toString();
            } catch (error) {
                return window.location.origin + fallbackPath;
            }
        }

        function setFeedback(message, tone) {
            const styles = {
                success: 'rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 mb-5',
                error: 'rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 mb-5',
                info: 'rounded-2xl border border-orange-200 bg-orange-50 px-4 py-3 text-sm text-orange-700 mb-5'
            };

            feedbackBox.className = styles[tone || 'info'];
            feedbackBox.textContent = message;
            feedbackBox.style.display = 'block';
        }

        function resetButton() {
            paymentBtn.disabled = false;
            paymentBtnText.textContent = 'Pay MK <?= number_format((float) ($paper['price'] ?? 0), 0) ?> & Download';
        }

        function checkPaperPaymentStatus(txRef, attempt) {
            const currentAttempt = typeof attempt === 'number' ? attempt : 0;

            if (currentAttempt >= 20) {
                setFeedback('We are still waiting for payment confirmation. If you were charged already, try this page again with the same email instead of paying twice.', 'info');
                resetButton();
                return;
            }

            fetch('<?= site_url('resources/past-papers/payment/status') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: 'tx_ref=' + encodeURIComponent(txRef)
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'verified' && data.redirect) {
                    setFeedback('Payment confirmed. Starting your download now...', 'success');
                    window.location.href = data.redirect;
                    return;
                }

                if (data.status === 'failed') {
                    setFeedback(data.message || 'Payment was not successful. Please try again.', 'error');
                    resetButton();
                    return;
                }

                setFeedback('Payment received. We are still confirming your access. Please wait a moment...', 'info');
                setTimeout(function () {
                    checkPaperPaymentStatus(txRef, currentAttempt + 1);
                }, 2000);
            })
            .catch(function () {
                setFeedback('We could not confirm the payment automatically just now. If you already paid, reload this page and submit the same email again to restore access.', 'info');
                resetButton();
            });
        }

        paymentForm.addEventListener('submit', async function (event) {
            event.preventDefault();

            try {
                paymentBtn.disabled = true;
                paymentBtnText.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right:8px;"></i>Preparing secure payment...';
                setFeedback('Preparing your secure PayChangu checkout. Please wait...', 'info');

                const response = await fetch('<?= site_url('resources/past-papers/payment/initiate') ?>', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: new FormData(paymentForm)
                });

                const result = await response.json();

                if (!result.success) {
                    setFeedback(result.message || 'Could not start payment. Please try again.', 'error');
                    resetButton();
                    return;
                }

                if (result.already_paid && result.redirect) {
                    setFeedback('Payment already confirmed. Starting your download now...', 'success');
                    window.location.href = result.redirect;
                    return;
                }

                if (typeof PaychanguCheckout !== 'function') {
                    console.error('PayChangu SDK not loaded');
                    setFeedback('Payment system is currently unavailable. Please refresh and try again.', 'error');
                    resetButton();
                    return;
                }

                setFeedback('Secure payment window opened. Complete the payment, then wait while we confirm your download.', 'info');

                const checkoutConfig = result.paychangu_config || {};
                const checkoutCustomization = checkoutConfig.customization || checkoutConfig.customizations || {};
                const callbackUrl = normalizeCheckoutUrl(checkoutConfig.callback_url, callbackPath);
                const returnUrl = normalizeCheckoutUrl(checkoutConfig.return_url, returnPath);
                const logoUrl = normalizeCheckoutUrl(checkoutCustomization.logo, logoPath);

                PaychanguCheckout({
                    public_key: checkoutConfig.public_key,
                    tx_ref: checkoutConfig.tx_ref,
                    amount: checkoutConfig.amount,
                    currency: checkoutConfig.currency,
                    callback_url: callbackUrl,
                    return_url: returnUrl,
                    customer: {
                        email: checkoutConfig.customer.email,
                        first_name: checkoutConfig.customer.first_name,
                        last_name: checkoutConfig.customer.last_name
                    },
                    customization: {
                        title: checkoutCustomization.title,
                        description: checkoutCustomization.description,
                        logo: logoUrl
                    },
                    callback: function (popupResponse) {
                        console.log('Past paper payment completed:', popupResponse);
                        setFeedback('Payment submitted. Confirming your access now...', 'info');
                        checkPaperPaymentStatus(checkoutConfig.tx_ref);
                    },
                    onClose: function () {
                        console.log('Past paper payment modal closed');
                        setFeedback('Checking payment status. If you were charged, access will unlock automatically or you can try again with the same paid email.', 'info');
                        checkPaperPaymentStatus(checkoutConfig.tx_ref);
                    }
                });
            } catch (error) {
                console.error('Past paper payment submission error:', error);
                setFeedback('Network error. Please try again.', 'error');
                resetButton();
            }
        });
    });
</script>
<?= $this->endSection() ?>
