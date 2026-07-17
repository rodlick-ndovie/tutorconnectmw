<?php if (!empty($paper_checkout_catalog)): ?>
<div id="pastPaperPaymentModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/70 px-4 py-6">
    <div class="w-full max-w-2xl overflow-hidden rounded-3xl bg-white shadow-2xl">
        <div class="flex items-start justify-between border-b border-slate-200 bg-gradient-to-r from-orange-600 to-red-600 px-6 py-5 text-white">
            <div>
                <h3 class="text-2xl font-bold">Unlock Paid Past Paper</h3>
                <p class="mt-1 text-sm text-orange-100">Pay once, then download immediately. Using the same email restores access without charging again.</p>
            </div>
            <button type="button" id="pastPaperPaymentCloseBtn" class="rounded-full bg-white/15 px-3 py-1 text-sm font-semibold text-white hover:bg-white/25">
                Close
            </button>
        </div>

        <div class="px-6 py-6">
            <div id="pastPaperPaymentFeedback" class="mb-4 hidden rounded-2xl border px-4 py-3 text-sm"></div>

            <div class="mb-6 rounded-2xl bg-orange-50 px-4 py-4">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-wide text-orange-700">Selected Paper</div>
                        <div id="pastPaperPaymentTitle" class="mt-1 text-lg font-bold text-slate-900">Past Paper</div>
                    </div>
                    <div class="text-right">
                        <div class="text-xs font-semibold uppercase tracking-wide text-orange-700">Amount</div>
                        <div id="pastPaperPaymentAmount" class="mt-1 text-2xl font-extrabold text-orange-700">MK 0</div>
                    </div>
                </div>
            </div>

            <form id="pastPaperPaymentForm" class="space-y-4">
                <?= csrf_field() ?>
                <input type="hidden" name="paper_id" id="pastPaperPaymentPaperId">

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label for="pastPaperBuyerName" class="mb-2 block text-sm font-semibold text-slate-700">Full Name</label>
                        <input type="text" name="buyer_full_name" id="pastPaperBuyerName" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200" required>
                    </div>
                    <div>
                        <label for="pastPaperBuyerPhone" class="mb-2 block text-sm font-semibold text-slate-700">Phone Number</label>
                        <input type="text" name="buyer_phone" id="pastPaperBuyerPhone" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200" required>
                    </div>
                </div>

                <div>
                    <label for="pastPaperBuyerEmail" class="mb-2 block text-sm font-semibold text-slate-700">Email Address</label>
                    <input type="email" name="buyer_email" id="pastPaperBuyerEmail" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200" required>
                    <p class="mt-2 text-xs text-slate-500">Use the same email again later if you want to restore this paid download on another device.</p>
                </div>

                <label class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4 text-sm text-slate-700">
                    <input type="checkbox" name="paper_terms" id="pastPaperTerms" value="1" class="mt-1 h-4 w-4 rounded border-slate-300 text-orange-600 focus:ring-orange-500">
                    <span>I understand this paper is a paid download and access will be granted to the email used for payment.</span>
                </label>

                <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:items-center sm:justify-end">
                    <button type="button" id="pastPaperPaymentCancelBtn" class="rounded-xl border border-slate-300 px-5 py-3 font-semibold text-slate-700 transition hover:bg-slate-100">
                        Cancel
                    </button>
                    <button type="submit" id="pastPaperPaymentSubmitBtn" class="rounded-xl bg-orange-600 px-6 py-3 font-semibold text-white transition hover:bg-orange-700">
                        Pay and Download
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://in.paychangu.com/js/popup.js"></script>
<script>
const paperCheckoutCatalog = <?= json_encode($paper_checkout_catalog, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
const pastPaperCurrentUser = <?= json_encode($current_user ?? [], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
let activePastPaperId = null;

function setPastPaperFeedback(message, type = 'info') {
    const feedback = document.getElementById('pastPaperPaymentFeedback');
    if (!feedback) {
        return;
    }

    if (!message) {
        feedback.className = 'mb-4 hidden rounded-2xl border px-4 py-3 text-sm';
        feedback.textContent = '';
        return;
    }

    const styles = {
        success: 'mb-4 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700',
        error: 'mb-4 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700',
        info: 'mb-4 rounded-2xl border border-orange-200 bg-orange-50 px-4 py-3 text-sm text-orange-700'
    };

    feedback.className = styles[type] || styles.info;
    feedback.textContent = message;
}

function togglePastPaperModal(show) {
    const modal = document.getElementById('pastPaperPaymentModal');
    if (!modal) {
        return;
    }

    modal.classList.toggle('hidden', !show);
    modal.classList.toggle('flex', show);

    if (!show) {
        setPastPaperFeedback('');
    }
}

function resetPastPaperSubmitButton() {
    const submitBtn = document.getElementById('pastPaperPaymentSubmitBtn');
    if (!submitBtn) {
        return;
    }

    submitBtn.disabled = false;
    submitBtn.innerHTML = 'Pay and Download';
}

function openPastPaperPaymentModal(paperId) {
    const paper = paperCheckoutCatalog[paperId];
    if (!paper) {
        return;
    }

    activePastPaperId = paperId;
    document.getElementById('pastPaperPaymentPaperId').value = paperId;
    document.getElementById('pastPaperPaymentTitle').textContent = paper.title || 'Past Paper';
    document.getElementById('pastPaperPaymentAmount').textContent = 'MK ' + (paper.formatted_price || '0');
    document.getElementById('pastPaperBuyerName').value = pastPaperCurrentUser.full_name || '';
    document.getElementById('pastPaperBuyerEmail').value = pastPaperCurrentUser.email || '';
    document.getElementById('pastPaperBuyerPhone').value = pastPaperCurrentUser.phone || '';
    document.getElementById('pastPaperTerms').checked = false;
    resetPastPaperSubmitButton();
    setPastPaperFeedback('');
    togglePastPaperModal(true);
}

function closePastPaperPaymentModal() {
    activePastPaperId = null;
    togglePastPaperModal(false);
}

function handlePaperDownload(paperId) {
    const paper = paperCheckoutCatalog[paperId];
    if (!paper) {
        return;
    }

    if (!paper.is_paid || paper.has_access) {
        window.location.href = paper.download_url;
        return;
    }

    openPastPaperPaymentModal(paperId);
}

function markPaperAsUnlocked(paperId, downloadUrl) {
    if (!paperCheckoutCatalog[paperId]) {
        return;
    }

    paperCheckoutCatalog[paperId].has_access = true;
    paperCheckoutCatalog[paperId].download_url = downloadUrl;
}

function checkPastPaperPaymentStatus(txRef, paperId, attempt = 0) {
    fetch('<?= site_url('resources/past-papers/payment/status') ?>', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: new URLSearchParams({
            tx_ref: txRef,
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        })
    })
    .then(response => response.json())
    .then(result => {
        if (result.status === 'verified' && result.download_url) {
            markPaperAsUnlocked(paperId, result.download_url);
            setPastPaperFeedback('Payment confirmed. Starting your download now...', 'success');
            window.location.href = result.download_url;
            return;
        }

        if (result.status === 'failed') {
            setPastPaperFeedback(result.message || 'Payment was not successful. Please try again.', 'error');
            resetPastPaperSubmitButton();
            return;
        }

        if (attempt >= 8) {
            setPastPaperFeedback('We are still confirming your payment. If you already paid, try the same email again in a moment.', 'info');
            resetPastPaperSubmitButton();
            return;
        }

        setPastPaperFeedback('Payment received. We are still confirming your access. Please wait a moment...', 'info');
        setTimeout(function() {
            checkPastPaperPaymentStatus(txRef, paperId, attempt + 1);
        }, 2000);
    })
    .catch(() => {
        setPastPaperFeedback('We could not confirm the payment automatically just now. If you already paid, try again with the same email to restore access.', 'info');
        resetPastPaperSubmitButton();
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('pastPaperPaymentForm');
    const submitBtn = document.getElementById('pastPaperPaymentSubmitBtn');
    const closeBtn = document.getElementById('pastPaperPaymentCloseBtn');
    const cancelBtn = document.getElementById('pastPaperPaymentCancelBtn');
    const modal = document.getElementById('pastPaperPaymentModal');

    if (!form || !submitBtn || !modal) {
        return;
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', closePastPaperPaymentModal);
    }

    if (cancelBtn) {
        cancelBtn.addEventListener('click', closePastPaperPaymentModal);
    }

    modal.addEventListener('click', function(event) {
        if (event.target === modal) {
            closePastPaperPaymentModal();
        }
    });

    form.addEventListener('submit', async function(event) {
        event.preventDefault();

        if (!activePastPaperId) {
            return;
        }

        try {
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Preparing secure payment...';
            setPastPaperFeedback('Preparing your secure PayChangu checkout. Please wait...', 'info');

            const response = await fetch('<?= site_url('resources/past-papers/payment/initiate') ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new FormData(form)
            });

            const result = await response.json();

            if (!result.success) {
                setPastPaperFeedback(result.message || 'Could not start payment. Please try again.', 'error');
                resetPastPaperSubmitButton();
                return;
            }

            if (result.already_paid && result.download_url) {
                markPaperAsUnlocked(activePastPaperId, result.download_url);
                setPastPaperFeedback('Access restored. Starting your download now...', 'success');
                window.location.href = result.download_url;
                return;
            }

            if (typeof PaychanguCheckout !== 'function') {
                setPastPaperFeedback('Payment system is currently unavailable. Please refresh and try again.', 'error');
                resetPastPaperSubmitButton();
                return;
            }

            setPastPaperFeedback('Secure payment window opened. Complete the payment, then wait while we confirm your download.', 'info');

            const paperId = activePastPaperId;
            const checkoutCustomization = result.paychangu_config.customizations || result.paychangu_config.customization || {};

            if (!checkoutCustomization.title || !checkoutCustomization.description) {
                console.error('Past paper payment config missing customization details:', result.paychangu_config);
                setPastPaperFeedback('Payment configuration is incomplete. Please refresh and try again.', 'error');
                resetPastPaperSubmitButton();
                return;
            }

            PaychanguCheckout({
                public_key: result.paychangu_config.public_key,
                tx_ref: result.paychangu_config.tx_ref,
                amount: result.paychangu_config.amount,
                currency: result.paychangu_config.currency,
                callback_url: result.paychangu_config.callback_url,
                return_url: result.paychangu_config.return_url,
                customer: {
                    email: result.paychangu_config.customer.email,
                    first_name: result.paychangu_config.customer.first_name,
                    last_name: result.paychangu_config.customer.last_name
                },
                customizations: {
                    title: checkoutCustomization.title,
                    description: checkoutCustomization.description,
                    logo: checkoutCustomization.logo
                },
                callback: function() {
                    checkPastPaperPaymentStatus(result.paychangu_config.tx_ref, paperId);
                },
                onClose: function() {
                    checkPastPaperPaymentStatus(result.paychangu_config.tx_ref, paperId);
                }
            });
        } catch (error) {
            console.error('Past paper payment error:', error);
            setPastPaperFeedback('Network error. Please try again.', 'error');
            resetPastPaperSubmitButton();
        }
    });
});
</script>
<?php endif; ?>
