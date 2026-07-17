<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Video Queue - Admin Panel') ?></title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#E74C3C',
                        secondary: '#2C3E50',
                        accent: '#34495E'
                    },
                    fontFamily: {
                        'sans': ['Inter', 'sans-serif']
                    }
                }
            }
        }
    </script>

    <style>
        .video-preview {
            max-width: 100%;
            height: auto;
        }
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-approved { background: #d1fae5; color: #065f46; }
        .status-rejected { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body class="bg-gray-50 font-sans">

    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <i class="fas fa-graduation-cap text-2xl text-primary"></i>
                        <span class="ml-2 text-xl font-bold text-secondary">TutorConnect Admin</span>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">
                        Welcome, <span class="font-medium">Admin</span>
                    </span>
                    <a href="<?= site_url('admin/dashboard') ?>" class="text-secondary hover:text-primary px-3 py-2 text-sm font-medium">
                        Dashboard
                    </a>
                    <a href="<?= site_url('logout') ?>" class="bg-primary text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-red-600 transition">
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Video Submissions Queue</h1>
                    <p class="text-gray-600 mt-2">Review and approve educational video content from tutors</p>
                </div>
                <a href="<?= site_url('admin/dashboard') ?>" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pending Review</p>
                        <p class="text-2xl font-bold text-gray-900"><?= esc($stats['pending_count'] ?? 0) ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Approved Today</p>
                        <p class="text-2xl font-bold text-gray-900"><?= esc($stats['approved_today'] ?? 0) ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100">
                        <i class="fas fa-times-circle text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Rejected Today</p>
                        <p class="text-2xl font-bold text-gray-900"><?= esc($stats['rejected_today'] ?? 0) ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100">
                        <i class="fas fa-video text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Videos</p>
                        <p class="text-2xl font-bold text-gray-900"><?= esc($stats['total_videos'] ?? 0) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Video Queue -->
        <?php if (!empty($pending_videos)): ?>
            <div class="space-y-6">
                <?php foreach ($pending_videos as $video): ?>
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="md:flex">

                            <!-- Video Preview -->
                            <div class="md:w-1/3 p-6 bg-gray-50">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Video Preview</h3>

                                <!-- Embedded Video -->
                                <div class="aspect-video bg-black rounded-lg overflow-hidden mb-4">
                                    <iframe src="<?= esc($video['video_embed_code']) ?>"
                                            frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen
                                            class="w-full h-full">
                                    </iframe>
                                </div>

                                <!-- Video Info -->
                                <div class="space-y-2 text-sm">
                                    <div class="flex items-center text-gray-600">
                                        <i class="fab fa-<?= esc($video['video_platform']) ?> mr-2"></i>
                                        <span class="capitalize"><?= esc($video['video_platform']) ?> Video</span>
                                    </div>
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-clock mr-2"></i>
                                        <span>Submitted <?= date('M j, Y g:i A', strtotime($video['submitted_at'])) ?></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Video Details & Actions -->
                            <div class="md:w-2/3 p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1">
                                        <h2 class="text-xl font-bold text-gray-900 mb-2">
                                            <?= esc($video['title']) ?>
                                        </h2>
                                        <div class="flex items-center space-x-4 text-sm text-gray-600 mb-3">
                                            <span class="flex items-center">
                                                <i class="fas fa-user mr-1"></i>
                                                <?= esc($video['first_name'] . ' ' . $video['last_name']) ?>
                                            </span>
                                            <span class="flex items-center">
                                                <i class="fas fa-graduation-cap mr-1"></i>
                                                <?= esc($video['exam_body']) ?>
                                            </span>
                                            <span class="flex items-center">
                                                <i class="fas fa-book mr-1"></i>
                                                <?= esc($video['subject']) ?>
                                            </span>
                                            <?php if (!empty($video['problem_year'])): ?>
                                                <span class="flex items-center">
                                                    <i class="fas fa-calendar mr-1"></i>
                                                    <?= esc($video['problem_year']) ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <span class="status-badge status-pending">
                                        <i class="fas fa-clock mr-1"></i>Pending Review
                                    </span>
                                </div>

                                <!-- Description -->
                                <?php if (!empty($video['description'])): ?>
                                    <div class="mb-6">
                                        <h4 class="text-sm font-semibold text-gray-900 mb-2">Description</h4>
                                        <div class="bg-gray-50 p-4 rounded-lg text-sm text-gray-700 max-h-32 overflow-y-auto">
                                            <?= nl2br(esc($video['description'])) ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <!-- Topic & Additional Info -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                    <?php if (!empty($video['topic'])): ?>
                                        <div>
                                            <h4 class="text-sm font-semibold text-gray-900 mb-1">Specific Topic</h4>
                                            <p class="text-sm text-gray-600 bg-blue-50 px-3 py-2 rounded-lg">
                                                <i class="fas fa-tag mr-2 text-blue-500"></i>
                                                <?= esc($video['topic']) ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>

                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-900 mb-1">Tutor Contact</h4>
                                        <div class="space-y-1">
                                            <p class="text-sm text-gray-600">
                                                <i class="fas fa-envelope mr-2 text-gray-400"></i>
                                                <?= esc($video['email']) ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                    <div class="text-sm text-gray-500">
                                        Video ID: #<?= esc($video['id']) ?>
                                    </div>

                                    <div class="flex space-x-3">
                                        <!-- Reject Button -->
                                        <button onclick="rejectVideo(<?= esc($video['id']) ?>, '<?= esc(addslashes($video['title'])) ?>')"
                                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition flex items-center text-sm font-medium">
                                            <i class="fas fa-times mr-2"></i>Reject
                                        </button>

                                        <!-- Approve Button -->
                                        <button onclick="approveVideo(<?= esc($video['id']) ?>, '<?= esc(addslashes($video['title'])) ?>')"
                                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center text-sm font-medium">
                                            <i class="fas fa-check mr-2"></i>Approve
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <!-- No pending videos -->
            <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-check-circle text-green-600 text-4xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">All Caught Up!</h3>
                <p class="text-gray-600 mb-6">
                    There are no video submissions pending review at this time.
                    All submitted videos have been processed.
                </p>
                <a href="<?= site_url('admin/dashboard') ?>" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                </a>
            </div>
        <?php endif; ?>

    </div>

    <!-- Modals -->
    <!-- Approve Modal -->
    <div id="approveModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="p-3 rounded-full bg-green-100 mr-4">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Approve Video</h3>
                        <p class="text-sm text-gray-600">Confirm approval of this video submission</p>
                    </div>
                </div>

                <div id="approveVideoInfo" class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <!-- Video info will be populated by JavaScript -->
                </div>

                <div class="flex justify-end space-x-3">
                    <button onclick="closeModal('approveModal')"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    <button id="confirmApproveBtn"
                            onclick="confirmApprove()"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-check mr-2"></i>Approve Video
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="p-3 rounded-full bg-red-100 mr-4">
                        <i class="fas fa-times-circle text-red-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Reject Video</h3>
                        <p class="text-sm text-gray-600">Provide a reason for rejecting this submission</p>
                    </div>
                </div>

                <div id="rejectVideoInfo" class="mb-4 p-4 bg-gray-50 rounded-lg">
                    <!-- Video info will be populated by JavaScript -->
                </div>

                <div class="mb-6">
                    <label for="rejectionReason" class="block text-sm font-medium text-gray-700 mb-2">
                        Rejection Reason <span class="text-red-500">*</span>
                    </label>
                    <textarea id="rejectionReason"
                              rows="4"
                              placeholder="Please explain why this video is being rejected. Be specific about what needs to be improved..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500"
                              required></textarea>
                    <p class="text-sm text-gray-500 mt-1">
                        This feedback will be sent to the tutor to help them improve future submissions.
                    </p>
                </div>

                <div class="flex justify-end space-x-3">
                    <button onclick="closeModal('rejectModal')"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    <button id="confirmRejectBtn"
                            onclick="confirmReject()"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        <i class="fas fa-times mr-2"></i>Reject Video
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentVideoId = null;
        let currentAction = null;

        function approveVideo(videoId, title) {
            currentVideoId = videoId;
            currentAction = 'approve';

            document.getElementById('approveVideoInfo').innerHTML = `
                <div class="text-sm">
                    <strong>Title:</strong> ${title}<br>
                    <strong>Video ID:</strong> #${videoId}<br>
                    <span class="text-green-600 font-medium">This video will be published and made available to students.</span>
                </div>
            `;

            document.getElementById('approveModal').classList.remove('hidden');
        }

        function rejectVideo(videoId, title) {
            currentVideoId = videoId;
            currentAction = 'reject';

            document.getElementById('rejectVideoInfo').innerHTML = `
                <div class="text-sm">
                    <strong>Title:</strong> ${title}<br>
                    <strong>Video ID:</strong> #${videoId}<br>
                    <span class="text-red-600 font-medium">The tutor will be notified with your feedback.</span>
                </div>
            `;

            document.getElementById('rejectionReason').value = '';
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
            currentVideoId = null;
            currentAction = null;
        }

        function confirmApprove() {
            if (!currentVideoId) return;

            const btn = document.getElementById('confirmApproveBtn');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Approving...';
            btn.disabled = true;

            fetch(`<?= site_url('admin/videos/approve/') ?>${currentVideoId}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: '_method=POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('success', 'Video approved successfully!');
                    setTimeout(() => location.reload(), 2000);
                } else {
                    showNotification('error', data.message || 'Failed to approve video.');
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('error', 'Network error. Please try again.');
                btn.innerHTML = originalText;
                btn.disabled = false;
            });

            closeModal('approveModal');
        }

        function confirmReject() {
            if (!currentVideoId) return;

            const reason = document.getElementById('rejectionReason').value.trim();
            if (!reason) {
                showNotification('error', 'Please provide a rejection reason.');
                return;
            }

            const btn = document.getElementById('confirmRejectBtn');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Rejecting...';
            btn.disabled = true;

            const formData = new FormData();
            formData.append('_method', 'POST');
            formData.append('reason', reason);

            fetch(`<?= site_url('admin/videos/reject/') ?>${currentVideoId}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('success', 'Video rejected successfully.');
                    setTimeout(() => location.reload(), 2000);
                } else {
                    showNotification('error', data.message || 'Failed to reject video.');
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('error', 'Network error. Please try again.');
                btn.innerHTML = originalText;
                btn.disabled = false;
            });

            closeModal('rejectModal');
        }

        function showNotification(type, message) {
            // Remove existing notifications
            const existingNotifications = document.querySelectorAll('.notification-toast');
            existingNotifications.forEach(notification => notification.remove());

            // Create notification element
            const notification = document.createElement('div');
            notification.className = `notification-toast fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-md ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-3"></i>
                    <span>${message}</span>
                </div>
            `;

            // Add to page
            document.body.appendChild(notification);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 5000);
        }

        // Close modals when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('bg-black')) {
                closeModal('approveModal');
                closeModal('rejectModal');
            }
        });

        // Close modals with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal('approveModal');
                closeModal('rejectModal');
            }
        });
    </script>

</body>
</html>
