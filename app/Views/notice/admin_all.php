<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">
            <i class="fas fa-bullhorn text-primary mr-3"></i>School Notices
        </h1>
        <p class="text-gray-600">Manage all notices from schools across the platform</p>
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
            <i class="fas fa-check-circle mr-2"></i><?= session()->getFlashdata('message') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <i class="fas fa-exclamation-triangle mr-2"></i><?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <!-- Filter Tabs -->
    <div class="mb-6 flex flex-wrap gap-3">
        <a href="<?= site_url('notice/admin/all?status=pending') ?>"
           class="px-6 py-2 rounded-lg font-semibold transition <?= $current_status === 'pending' ? 'bg-primary text-white' : 'bg-white text-gray-700 hover:bg-gray-50 shadow' ?>">
            Pending (<?= $statistics['pending'] ?>)
        </a>
        <a href="<?= site_url('notice/admin/all?status=approved') ?>"
           class="px-6 py-2 rounded-lg font-semibold transition <?= $current_status === 'approved' ? 'bg-primary text-white' : 'bg-white text-gray-700 hover:bg-gray-50 shadow' ?>">
            Approved (<?= $statistics['approved'] ?>)
        </a>
        <a href="<?= site_url('notice/admin/all?status=rejected') ?>"
           class="px-6 py-2 rounded-lg font-semibold transition <?= $current_status === 'rejected' ? 'bg-primary text-white' : 'bg-white text-gray-700 hover:bg-gray-50 shadow' ?>">
            Rejected (<?= $statistics['rejected'] ?>)
        </a>
        <a href="<?= site_url('notice/admin/all?status=all') ?>"
           class="px-6 py-2 rounded-lg font-semibold transition <?= $current_status === 'all' ? 'bg-primary text-white' : 'bg-white text-gray-700 hover:bg-gray-50 shadow' ?>">
            All (<?= $statistics['total'] ?>)
        </a>
    </div>

    <!-- Notices List -->
    <?php if (empty($notices)): ?>
        <div class="bg-white rounded-lg shadow-xl p-12 text-center">
            <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">No Notices Found</h3>
            <p class="text-gray-500">No notices match the selected filter</p>
        </div>
    <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($notices as $notice): ?>
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                                        <?= $notice['notice_type'] == 'Vacancy' ? 'bg-green-100 text-green-800' :
                                            ($notice['notice_type'] == 'Announcement' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') ?>">
                                        <?= esc($notice['notice_type']) ?>
                                    </span>
                                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                                        <?= $notice['status'] == 'approved' ? 'bg-green-100 text-green-800' :
                                            ($notice['status'] == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') ?>">
                                        <?= ucfirst(esc($notice['status'])) ?>
                                    </span>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900"><?= esc($notice['notice_title']) ?></h3>
                            </div>
                            <div class="text-right">
                                <div class="text-sm text-gray-500">
                                    <i class="far fa-clock mr-1"></i>
                                    <?= date('M d, Y', strtotime($notice['created_at'])) ?>
                                </div>
                                <div class="text-xs text-gray-400 mt-1">
                                    <i class="fas fa-eye mr-1"></i>
                                    <?= number_format($notice['views_count']) ?> views
                                </div>
                            </div>
                        </div>

                        <!-- School Information -->
                        <div class="grid md:grid-cols-3 gap-4 mb-4 p-4 bg-gray-50 rounded-lg">
                            <div>
                                <div class="text-xs text-gray-600 mb-1">School</div>
                                <div class="font-semibold text-gray-900 text-sm">
                                    <i class="fas fa-school text-primary mr-2"></i><?= esc($notice['school_name']) ?>
                                </div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-600 mb-1">Type</div>
                                <div class="font-semibold text-gray-900 text-sm"><?= esc($notice['school_type']) ?></div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-600 mb-1">Contact</div>
                                <div class="font-semibold text-gray-900 text-sm">
                                    <i class="fas fa-phone text-green-600 mr-2"></i><?= esc($notice['phone']) ?>
                                </div>
                            </div>
                        </div>

                        <!-- Notice Content Preview -->
                        <div class="mb-4">
                            <div class="text-gray-700 text-sm line-clamp-2">
                                <?= esc(substr($notice['notice_content'], 0, 200)) ?>...
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-wrap gap-3 pt-4 border-t">
                            <?php if ($notice['status'] !== 'approved'): ?>
                                <form method="post" action="<?= site_url('notice/approve/' . $notice['id']) ?>"
                                      onsubmit="return confirm('Approve this notice?')">
                                    <?= csrf_field() ?>
                                    <button type="submit"
                                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm">
                                        <i class="fas fa-check-circle mr-1"></i>Approve
                                    </button>
                                </form>
                            <?php endif; ?>

                            <?php if ($notice['status'] !== 'rejected'): ?>
                                <button type="button"
                                        onclick="showRejectModal(<?= $notice['id'] ?>)"
                                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm">
                                    <i class="fas fa-times-circle mr-1"></i>Reject
                                </button>
                            <?php endif; ?>

                            <a href="<?= site_url('notice/view/' . $notice['id']) ?>"
                               target="_blank"
                               class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition text-sm">
                                <i class="fas fa-eye mr-1"></i>View Full
                            </a>

                            <a href="tel:<?= esc($notice['phone']) ?>"
                               class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition text-sm">
                                <i class="fas fa-phone mr-1"></i>Call
                            </a>

                            <form method="post" action="<?= site_url('notice/delete/' . $notice['id']) ?>"
                                  onsubmit="return confirm('Are you sure you want to delete this notice? This action cannot be undone.')"
                                  class="ml-auto">
                                <?= csrf_field() ?>
                                <button type="submit"
                                        class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition text-sm">
                                    <i class="fas fa-trash mr-1"></i>Delete
                                </button>
                            </form>
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

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<script>
function showRejectModal(noticeId) {
    document.getElementById('rejectForm').action = '<?= site_url('notice/reject/') ?>' + noticeId;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function hideRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}
</script>

<?= $this->endSection() ?>
