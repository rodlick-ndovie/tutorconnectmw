<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">
            <i class="fas fa-tasks text-primary mr-3"></i>Pending Notices - Admin Review
        </h1>
        <p class="text-gray-600">Review and approve school notices before publication</p>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-600 mb-1">Total Notices</div>
            <div class="text-3xl font-bold text-gray-900"><?= $statistics['total'] ?></div>
        </div>
        <div class="bg-yellow-50 rounded-lg shadow p-6">
            <div class="text-sm text-yellow-800 mb-1">Pending Review</div>
            <div class="text-3xl font-bold text-yellow-600"><?= $statistics['pending'] ?></div>
        </div>
        <div class="bg-green-50 rounded-lg shadow p-6">
            <div class="text-sm text-green-800 mb-1">Approved</div>
            <div class="text-3xl font-bold text-green-600"><?= $statistics['approved'] ?></div>
        </div>
        <div class="bg-red-50 rounded-lg shadow p-6">
            <div class="text-sm text-red-800 mb-1">Rejected</div>
            <div class="text-3xl font-bold text-red-600"><?= $statistics['rejected'] ?></div>
        </div>
    </div>

    <?php if (session()->getFlashdata('message')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?= session()->getFlashdata('message') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <!-- Pending Notices List -->
    <?php if (empty($notices)): ?>
        <div class="bg-white rounded-lg shadow-xl p-12 text-center">
            <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">No Pending Notices</h3>
            <p class="text-gray-500">All notices have been reviewed</p>
        </div>
    <?php else: ?>
        <div class="space-y-6">
            <?php foreach ($notices as $notice): ?>
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full mb-2">
                                    <?= esc($notice['notice_type']) ?>
                                </span>
                                <h3 class="text-2xl font-bold text-gray-900"><?= esc($notice['notice_title']) ?></h3>
                            </div>
                            <div class="text-right">
                                <div class="text-sm text-gray-500">
                                    <i class="far fa-clock mr-1"></i>
                                    <?= date('M d, Y', strtotime($notice['created_at'])) ?>
                                </div>
                            </div>
                        </div>

                        <!-- School Information -->
                        <div class="grid md:grid-cols-2 gap-4 mb-4 p-4 bg-gray-50 rounded-lg">
                            <div>
                                <div class="text-sm text-gray-600 mb-1">School Name</div>
                                <div class="font-semibold text-gray-900">
                                    <i class="fas fa-school text-primary mr-2"></i><?= esc($notice['school_name']) ?>
                                </div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600 mb-1">School Type</div>
                                <div class="font-semibold text-gray-900"><?= esc($notice['school_type']) ?></div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600 mb-1">Phone</div>
                                <div class="font-semibold text-gray-900">
                                    <i class="fas fa-phone text-green-600 mr-2"></i><?= esc($notice['phone']) ?>
                                </div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600 mb-1">Email</div>
                                <div class="font-semibold text-gray-900">
                                    <i class="fas fa-envelope text-blue-600 mr-2"></i><?= esc($notice['email']) ?>
                                </div>
                            </div>
                        </div>

                        <!-- Notice Content -->
                        <div class="mb-4">
                            <div class="text-sm font-semibold text-gray-700 mb-2">Notice Content:</div>
                            <div class="text-gray-700 bg-white border border-gray-200 rounded p-4 whitespace-pre-wrap">
                                <?= esc($notice['notice_content']) ?>
                            </div>
                        </div>

                        <!-- Attached Image -->
                        <?php if (!empty($notice['attached_image'])): ?>
                            <div class="mb-4">
                                <div class="text-sm font-semibold text-gray-700 mb-2">Attached Image:</div>
                                <img src="<?= base_url($notice['attached_image']) ?>"
                                     alt="Notice attachment"
                                     class="max-w-md rounded-lg border border-gray-300">
                            </div>
                        <?php endif; ?>

                        <!-- Action Buttons -->
                        <div class="flex gap-3 pt-4 border-t">
                            <form method="post" action="<?= site_url('notice/approve/' . $notice['id']) ?>"
                                  onsubmit="return confirm('Are you sure you want to approve this notice?')">
                                <?= csrf_field() ?>
                                <button type="submit"
                                        class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                                    <i class="fas fa-check-circle mr-2"></i>Approve Notice
                                </button>
                            </form>

                            <button type="button"
                                    onclick="showRejectModal(<?= $notice['id'] ?>)"
                                    class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                                <i class="fas fa-times-circle mr-2"></i>Reject
                            </button>

                            <a href="tel:<?= esc($notice['phone']) ?>"
                               class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                                <i class="fas fa-phone mr-2"></i>Call School
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Reject Notice</h3>
        <form id="rejectForm" method="post">
            <?= csrf_field() ?>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Reason for Rejection</label>
                <textarea name="rejection_reason" rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
                          placeholder="Provide a reason for rejection..." required></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="hideRejectModal()"
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Reject Notice
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showRejectModal(noticeId) {
    document.getElementById('rejectForm').action = '<?= site_url('notice/reject/') ?>' + noticeId;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function hideRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}
</script>
