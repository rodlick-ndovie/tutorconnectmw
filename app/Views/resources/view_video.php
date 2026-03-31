<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Video Player Section -->
<section class="py-8 bg-gray-900">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Main Video Player -->
            <div class="lg:col-span-2">
                <div class="bg-black rounded-xl overflow-hidden shadow-2xl">
                    <!-- Video Embed -->
                    <div class="aspect-video">
                        <?= $video['video_embed_code'] ?>
                    </div>
                </div>

                <!-- Video Info -->
                <div class="mt-6 bg-white rounded-xl p-6 shadow-lg">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h1 class="text-2xl font-bold text-gray-900 mb-2">
                                <?= esc($video['title']) ?>
                            </h1>

                            <div class="flex items-center space-x-4 mb-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                    <i class="fas fa-graduation-cap mr-2"></i>
                                    <?= esc($video['exam_body']) ?> • <?= esc($video['exam_level'] ?? $video['problem_year'] ?? 'General') ?>
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-book mr-2"></i>
                                    <?= esc($video['subject']) ?>
                                </span>
                                <?php if (!empty($video['topic'])): ?>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-tag mr-1"></i>
                                        <?= esc($video['topic']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- View Count & Uploader -->
                        <div class="text-right">
                            <div class="flex items-center text-gray-600 mb-2">
                                <i class="fas fa-eye mr-2"></i>
                                <span class="font-medium"><?= number_format($video['view_count'] ?? 0) ?> views</span>
                            </div>
                            <div class="text-sm text-gray-500 mb-2">
                                Uploaded <?= date('M j, Y', strtotime($video['created_at'])) ?>
                            </div>
                            <!-- Added By Information -->
                            <div class="text-sm">
                                <span class="text-gray-600">Added by:</span>
                                <span class="font-medium text-gray-900">
                                    <?php if ($addedByAdmin ?? false): ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-shield-alt mr-1"></i>Admin
                                        </span>
                                    <?php else: ?>
                                        <span class="text-blue-600 hover:text-blue-800">
                                            <?= esc($video['first_name'] . ' ' . $video['last_name']) ?>
                                        </span>
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <?php if (!empty($video['description'])): ?>
                        <div class="border-t border-gray-200 pt-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Description</h3>
                            <div class="text-gray-700 leading-relaxed whitespace-pre-line">
                                <?= nl2br(esc($video['description'])) ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Tags -->
                    <div class="border-t border-gray-200 pt-4 mt-4">
                        <div class="flex flex-wrap gap-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                <i class="fab fa-<?= esc($video['video_platform']) ?> mr-2"></i>
                                <?= ucfirst(esc($video['video_platform'])) ?>
                            </span>
                            <?php if (!empty($video['problem_year'])): ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-calendar mr-2"></i>
                                    <?= esc($video['problem_year']) ?> Question
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">

                <!-- Professional Info Card - For All Content Creators -->
                <div class="bg-white rounded-xl p-6 shadow-lg mb-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <?php if ($addedByAdmin ?? false): ?>
                            <i class="fas fa-shield-alt mr-2 text-blue-600"></i>
                            Content Administrator
                        <?php else: ?>
                            <i class="fas fa-user-graduate mr-2 text-blue-600"></i>
                            Professional Tutor
                        <?php endif; ?>
                    </h3>

                    <div class="text-center mb-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-3">
                            <?php if ($addedByAdmin ?? false): ?>
                                <i class="fas fa-user-shield text-white text-xl"></i>
                            <?php else: ?>
                                <i class="fas fa-user-graduate text-white text-xl"></i>
                            <?php endif; ?>
                        </div>
                        <h4 class="font-semibold text-gray-900">
                            <?= esc($video['first_name'] . ' ' . $video['last_name']) ?>
                        </h4>
                        <p class="text-sm text-gray-600">
                            <?php if ($addedByAdmin ?? false): ?>
                                <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-medium">
                                    <i class="fas fa-crown mr-1"></i>Administrator
                                </span>
                            <?php else: ?>
                                <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-medium">
                                    <i class="fas fa-check-circle mr-1"></i>Verified Tutor
                                </span>
                            <?php endif; ?>
                        </p>
                    </div>

                    <!-- Professional Details -->
                    <div class="space-y-3 mb-4">
                        <?php if (!empty($video['district'])): ?>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-map-marker-alt mr-2 text-gray-400 w-4"></i>
                                <span><strong>Location:</strong> <?= esc($video['district']) ?><?php if (!empty($video['area'])): ?>, <?= esc($video['area']) ?><?php endif; ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($video['experience_years'])): ?>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-briefcase mr-2 text-gray-400 w-4"></i>
                                <span><strong>Experience:</strong> <?= esc($video['experience_years']) ?> years</span>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($video['bio'])): ?>
                            <div class="text-sm text-gray-600">
                                <strong>About:</strong>
                                <p class="mt-1 text-xs leading-relaxed line-clamp-3">
                                    <?= esc(substr($video['bio'], 0, 150)) ?><?php if (strlen($video['bio']) > 150): ?>...<?php endif; ?>
                                </p>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($video['rating'])): ?>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-star mr-2 text-yellow-400 w-4"></i>
                                <span><strong>Rating:</strong> <?= number_format($video['rating'], 1) ?>/5.0
                                <?php if (!empty($video['review_count'])): ?>
                                    (<?= esc($video['review_count']) ?> reviews)
                                <?php endif; ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Contact Actions -->
                    <div class="space-y-3">
                        <?php if (!($addedByAdmin ?? false)): ?>
                            <!-- Tutor-specific actions -->
                            <a href="<?= site_url('tutor/' . $video['tutor_id']) ?>"
                               class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition flex items-center justify-center font-medium">
                                <i class="fas fa-user mr-2"></i>View Full Profile
                            </a>

                            <a href="mailto:<?= esc($video['email']) ?>"
                               class="w-full bg-indigo-600 text-white py-3 px-4 rounded-lg hover:bg-indigo-700 transition flex items-center justify-center font-medium">
                                <i class="fas fa-envelope mr-2"></i>Email Tutor
                            </a>

                            <?php if (!empty($video['phone'])): ?>
                            <a href="tel:<?= esc($video['phone']) ?>"
                               class="w-full bg-green-600 text-white py-3 px-4 rounded-lg hover:bg-green-700 transition flex items-center justify-center font-medium">
                                <i class="fas fa-phone mr-2"></i>Call Tutor
                            </a>
                            <?php endif; ?>

                            <?php if (!empty($video['whatsapp_number'])): ?>
                            <a href="https://wa.me/<?= esc($video['whatsapp_number']) ?>"
                               class="w-full bg-green-500 text-white py-3 px-4 rounded-lg hover:bg-green-600 transition flex items-center justify-center font-medium">
                                <i class="fab fa-whatsapp mr-2"></i>WhatsApp
                            </a>
                            <?php endif; ?>
                        <?php else: ?>
                            <!-- Admin-specific actions -->
                            <a href="mailto:<?= esc($video['email']) ?>"
                               class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition flex items-center justify-center font-medium">
                                <i class="fas fa-envelope mr-2"></i>Email Administrator
                            </a>

                            <?php if (!empty($video['phone'])): ?>
                            <a href="tel:<?= esc($video['phone']) ?>"
                               class="w-full bg-green-600 text-white py-3 px-4 rounded-lg hover:bg-green-700 transition flex items-center justify-center font-medium">
                                <i class="fas fa-phone mr-2"></i>Call Administrator
                            </a>
                            <?php endif; ?>

                            <?php if (!empty($video['whatsapp_number'])): ?>
                            <a href="https://wa.me/<?= esc($video['whatsapp_number']) ?>"
                               class="w-full bg-green-500 text-white py-3 px-4 rounded-lg hover:bg-green-600 transition flex items-center justify-center font-medium">
                                <i class="fab fa-whatsapp mr-2"></i>WhatsApp
                            </a>
                            <?php endif; ?>

                            <!-- Fallback support contacts -->
                            <div class="pt-3 border-t border-gray-200">
                                <p class="text-xs text-gray-500 text-center mb-2">Need additional support?</p>
                                <a href="mailto:info@uprisemw.com"
                                   class="w-full bg-gray-600 text-white py-2 px-4 rounded-lg hover:bg-gray-700 transition flex items-center justify-center font-medium text-sm">
                                    <i class="fas fa-envelope mr-2"></i>System Support
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Related Videos -->
                <?php if (!empty($other_videos)): ?>
                    <div class="bg-white rounded-xl p-6 shadow-lg">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-video mr-2 text-purple-600"></i>
                            More from this Tutor
                        </h3>

                        <div class="space-y-4">
                            <?php foreach ($other_videos as $related_video): ?>
                                <a href="<?= site_url('resources/video/' . $related_video['id']) ?>" class="group block">
                                    <div class="flex space-x-3">
                                        <!-- Thumbnail -->
                                        <div class="relative w-16 h-12 bg-gray-200 rounded overflow-hidden flex-shrink-0">
                                            <?php if ($related_video['video_platform'] === 'youtube'): ?>
                                                <img src="https://img.youtube.com/vi/<?= esc($related_video['video_id']) ?>/default.jpg"
                                                     alt="<?= esc($related_video['title']) ?>"
                                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                            <?php endif; ?>
                                            <div class="absolute inset-0 bg-black bg-opacity-20 flex items-center justify-center">
                                                <i class="fas fa-play text-white text-xs"></i>
                                            </div>
                                        </div>

                                        <!-- Info -->
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-sm font-medium text-gray-900 group-hover:text-purple-600 transition-colors line-clamp-2 leading-tight">
                                                <?= esc($related_video['title']) ?>
                                            </h4>
                                            <div class="flex items-center text-xs text-gray-500 mt-1">
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 mr-1">
                                                    <?= esc($related_video['subject']) ?>
                                                </span>
                                                <span>
                                                    <i class="fas fa-eye mr-1"></i>
                                                    <?= number_format($related_video['view_count'] ?? 0) ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>

                        <!-- View All Link -->
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <a href="<?= site_url('resources/video-solutions?subject=' . urlencode($video['subject'])) ?>"
                               class="text-purple-600 hover:text-purple-800 font-medium text-sm flex items-center">
                                <i class="fas fa-arrow-right mr-2"></i>View all videos by this tutor
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Related Videos Section -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Similar Video Solutions</h2>
            <p class="text-gray-600">More helpful content in <?= esc($video['subject']) ?> and related topics</p>
        </div>

        <!-- We'll implement a related videos section here later -->
        <div class="text-center py-12">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-video text-2xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">More Videos Coming Soon</h3>
            <p class="text-gray-600">Check back later for more video solutions in this subject.</p>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-lg p-8 sm:p-12 border-l-4 border-primary">
            <div class="flex items-center mb-6">
                <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-gradient-to-br from-primary to-red-600 text-white flex-shrink-0">
                    <i class="fas fa-comment-dots text-lg"></i>
                </div>
                <div class="ml-4">
                    <h2 class="text-3xl font-bold text-gray-900">Need More Help?</h2>
                </div>
            </div>

            <p class="text-gray-600 mb-8 text-lg leading-relaxed">
                Can't find what you're looking for? Contact us directly or connect with one of our verified tutors for personalized assistance.
            </p>

            <div class="flex flex-col sm:flex-row gap-4">
                <a href="<?= site_url('find-tutors') ?>" class="inline-flex items-center justify-center bg-gradient-to-r from-primary to-red-600 text-white px-8 py-3 rounded-lg hover:shadow-lg transition font-semibold">
                    <i class="fas fa-search mr-3"></i>Find a Tutor
                </a>
                <a href="<?= site_url('contact') ?>" class="inline-flex items-center justify-center border-2 border-primary text-primary px-8 py-3 rounded-lg hover:bg-primary hover:text-white transition font-semibold">
                    <i class="fas fa-envelope mr-3"></i>Contact Us
                </a>
            </div>
        </div>
    </div>
</section>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.aspect-video {
    aspect-ratio: 16 / 9;
}

/* Custom scrollbar for video descriptions */
.video-description {
    max-height: 200px;
    overflow-y: auto;
}

/* Ensure video iframe is responsive */
iframe {
    width: 100%;
    height: 100%;
    border: none;
}
</style>

<script>
// Contact click tracking for video page
document.addEventListener('DOMContentLoaded', function() {
    // Track video views
    console.log('Video loaded:', <?= $video['id'] ?>);

    // Track contact clicks on video page
    const contactLinks = document.querySelectorAll('a[href^="mailto:"], a[href^="tel:"], a[href*="wa.me"]');

    contactLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            const tutorId = <?= $video['tutor_id'] ?>;
            let contactType = 'unknown';

            // Determine contact type
            if (href.startsWith('mailto:')) {
                contactType = 'email';
            } else if (href.startsWith('tel:')) {
                contactType = 'phone';
            } else if (href.includes('wa.me')) {
                contactType = 'whatsapp';
            }

            // Track the click asynchronously (don't block the navigation)
            fetch('<?= base_url('resources/trackContactClick') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: 'tutor_id=' + encodeURIComponent(tutorId) + '&contact_type=' + encodeURIComponent(contactType) + '&reference_type=video&reference_id=' + encodeURIComponent(<?= $video['id'] ?>)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Contact click tracked:', contactType, 'for video:', <?= $video['id'] ?>);
                } else {
                    console.log('Failed to track contact click:', data.message);
                }
            })
            .catch(error => {
                console.log('Error tracking contact click:', error);
            });
        });
    });
});
</script>

<?= $this->endSection() ?>
