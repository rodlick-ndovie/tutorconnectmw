<?= $this->extend('layout/trainer_shared') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Reviews Header -->
    <div class="reviews-header mb-4" data-aos="fade-up">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="h3 fw-bold">Reviews & Ratings</h1>
                <p class="text-muted">What your students are saying about your tutoring</p>
            </div>
            <div class="col-md-4 text-md-end">
                <button class="btn btn-outline-primary" onclick="exportReviews()">
                    <i class="fas fa-download me-2"></i>Export Reviews
                </button>
            </div>
        </div>
    </div>

    <!-- Overall Rating -->
    <div class="card mb-4" data-aos="fade-up" data-aos-delay="100">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-4 text-center">
                    <div class="overall-rating">
                        <div class="rating-number"><?= number_format($overall_rating, 1) ?></div>
                        <div class="stars mb-3">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star fa-2x <?= $i <= floor($overall_rating) ? 'text-warning' : ($i <= $overall_rating ? 'text-warning' : 'text-muted') ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <p class="text-muted mb-0">Based on <?= $total_reviews ?> reviews</p>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="rating-distribution">
                        <?php for($i = 5; $i >= 1; $i--): 
                            $percentage = isset($rating_distribution[$i]) ? ($rating_distribution[$i] / $total_reviews * 100) : 0;
                        ?>
                            <div class="rating-bar mb-2">
                                <div class="d-flex align-items-center">
                                    <span class="me-2" style="width: 20px;"><?= $i ?></span>
                                    <i class="fas fa-star text-warning me-2"></i>
                                    <div class="progress flex-grow-1" style="height: 8px;">
                                        <div class="progress-bar bg-warning" style="width: <?= $percentage ?>%"></div>
                                    </div>
                                    <span class="ms-2 text-muted small" style="width: 40px; text-align: right;">
                                        <?= $rating_distribution[$i] ?? 0 ?> (<?= number_format($percentage, 1) ?>%)
                                    </span>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Review Filters -->
    <div class="card mb-4" data-aos="fade-up" data-aos-delay="150">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6 mb-3 mb-md-0">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-primary active" onclick="filterReviews('all')">
                            All Reviews (<?= $total_reviews ?>)
                        </button>
                        <button type="button" class="btn btn-outline-primary" onclick="filterReviews('replied')">
                            Replied (<?= $replied_count ?>)
                        </button>
                        <button type="button" class="btn btn-outline-primary" onclick="filterReviews('unreplied')">
                            Unreplied (<?= $unreplied_count ?>)
                        </button>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-md-end gap-2">
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-sort me-2"></i>Sort by
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="#" onclick="sortReviews('newest')">
                                        <i class="fas fa-sort-amount-down me-2"></i>Newest First
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" onclick="sortReviews('oldest')">
                                        <i class="fas fa-sort-amount-up me-2"></i>Oldest First
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" onclick="sortReviews('highest')">
                                        <i class="fas fa-star me-2"></i>Highest Rating
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" onclick="sortReviews('lowest')">
                                        <i class="fas fa-star me-2"></i>Lowest Rating
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-filter me-2"></i>Rating
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="#" onclick="filterByRating('all')">
                                        <i class="fas fa-star me-2"></i>All Ratings
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <?php for($i = 5; $i >= 1; $i--): ?>
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="filterByRating(<?= $i ?>)">
                                            <?php for($j = 1; $j <= $i; $j++): ?>
                                                <i class="fas fa-star text-warning"></i>
                                            <?php endfor; ?>
                                            <?php for($j = $i + 1; $j <= 5; $j++): ?>
                                                <i class="far fa-star text-muted"></i>
                                            <?php endfor; ?>
                                            <span class="ms-2">(<?= $rating_distribution[$i] ?? 0 ?>)</span>
                                        </a>
                                    </li>
                                <?php endfor; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews List -->
    <div class="reviews-list" id="reviewsList">
        <?php foreach($reviews as $review): ?>
            <div class="card review-item mb-4" data-aos="fade-up" 
                 data-rating="<?= $review['rating'] ?>" 
                 data-replied="<?= $review['reply'] ? 'true' : 'false' ?>">
                <div class="card-body">
                    <!-- Review Header -->
                    <div class="review-header mb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="d-flex align-items-center">
                                <!-- Student Avatar -->
                                <div class="me-3">
                                    <?php if($review['student_avatar']): ?>
                                        <img src="<?= base_url($review['student_avatar']) ?>" 
                                             class="rounded-circle" width="48" height="48"
                                             alt="<?= $review['student_name'] ?>">
                                    <?php else: ?>
                                        <div class="avatar-initials rounded-circle" 
                                             style="width: 48px; height: 48px; background: #3b82f6; 
                                                    color: white; display: flex; align-items: center; 
                                                    justify-content: center; font-weight: 600;">
                                            <?= strtoupper(substr($review['student_name'], 0, 1)) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Student Info -->
                                <div>
                                    <h6 class="mb-1"><?= esc($review['student_name']) ?></h6>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="stars me-2">
                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                <i class="fas fa-star <?= $i <= $review['rating'] ? 'text-warning' : 'text-muted' ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                        <small class="text-muted">
                                            <?= date('M d, Y', strtotime($review['created_at'])) ?>
                                        </small>
                                    </div>
                                    <?php if($review['session_subject']): ?>
                                        <small class="text-muted">
                                            <i class="fas fa-book me-1"></i>
                                            <?= esc($review['session_subject']) ?>
                                        </small>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <?php if(!$review['reply']): ?>
                                        <li>
                                            <a class="dropdown-item" href="#" 
                                               onclick="showReplyForm(<?= $review['id'] ?>)">
                                                <i class="fas fa-reply me-2"></i>Reply
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <li>
                                        <a class="dropdown-item" href="#" 
                                           onclick="reportReview(<?= $review['id'] ?>)">
                                            <i class="fas fa-flag me-2"></i>Report
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="#" 
                                           onclick="deleteReview(<?= $review['id'] ?>)">
                                            <i class="fas fa-trash me-2"></i>Delete
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Review Content -->
                    <div class="review-content mb-3">
                        <p class="mb-0"><?= nl2br(esc($review['content'])) ?></p>
                    </div>

                    <!-- Tags -->
                    <?php if($review['tags']): ?>
                        <div class="review-tags mb-3">
                            <?php foreach(json_decode($review['tags'], true) as $tag): ?>
                                <span class="badge bg-light text-dark me-1 mb-1"><?= $tag ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Reply -->
                    <?php if($review['reply']): ?>
                        <div class="reply-section">
                            <div class="reply-content p-3 bg-light rounded">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="reply-icon me-2">
                                            <i class="fas fa-reply text-primary"></i>
                                        </div>
                                        <h6 class="mb-0">Your Response</h6>
                                    </div>
                                    <small class="text-muted">
                                        <?= date('M d, Y', strtotime($review['reply_created_at'])) ?>
                                    </small>
                                </div>
                                <p class="mb-0"><?= nl2br(esc($review['reply']['content'])) ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- No Reviews -->
    <?php if(empty($reviews)): ?>
        <div class="card" data-aos="fade-up">
            <div class="card-body text-center py-5">
                <i class="fas fa-star fa-4x text-muted mb-4"></i>
                <h3 class="text-muted">No Reviews Yet</h3>
                <p class="text-muted mb-4">Your reviews will appear here once students rate their sessions</p>
                <button class="btn btn-primary" onclick="requestReviews()">
                    <i class="fas fa-envelope me-2"></i>Request Reviews from Students
                </button>
            </div>
        </div>
    <?php endif; ?>

    <!-- Pagination -->
    <?php if($pager && $total_reviews > 0): ?>
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div>
                <small class="text-muted">
                    Showing <?= $pager->getCurrentPage() ?> to <?= $pager->getPerPage() ?> of <?= $total_reviews ?> reviews
                </small>
            </div>
            <div>
                <?= $pager->links() ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Reply Modal -->
<div class="modal fade" id="replyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reply to Review</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="replyForm">
                    <input type="hidden" name="review_id" id="replyReviewId">
                    <div class="mb-3">
                        <label class="form-label">Your Response *</label>
                        <textarea class="form-control" name="reply" rows="4" required 
                                  placeholder="Thank the student for their feedback..."></textarea>
                        <small class="text-muted">
                            Your response will be visible to the student and other potential students
                        </small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="replyForm" class="btn btn-primary">Post Reply</button>
            </div>
        </div>
    </div>
</div>

<!-- Request Reviews Modal -->
<div class="modal fade" id="requestReviewsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Request Reviews from Students</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="requestReviewsForm">
                    <div class="mb-3">
                        <label class="form-label">Select Students *</label>
                        <div class="students-list border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                            <?php foreach($eligible_students as $student): ?>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" 
                                           name="students[]" value="<?= $student['id'] ?>" 
                                           id="student_<?= $student['id'] ?>">
                                    <label class="form-check-label" for="student_<?= $student['id'] ?>">
                                        <?= esc($student['name']) ?>
                                        <small class="text-muted">
                                            • Last session: <?= date('M d', strtotime($student['last_session'])) ?>
                                        </small>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message Template</label>
                        <select class="form-select" name="template" onchange="loadTemplate(this.value)">
                            <option value="">Custom Message</option>
                            <option value="friendly">Friendly Request</option>
                            <option value="professional">Professional Request</option>
                            <option value="simple">Simple Request</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Custom Message</label>
                        <textarea class="form-control" name="message" rows="5" 
                                  placeholder="Dear [Student Name], I hope you found our recent [Subject] session helpful..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="requestReviewsForm" class="btn btn-primary">Send Review Requests</button>
            </div>
        </div>
    </div>
</div>

<style>
    .overall-rating {
        padding: 2rem;
    }

    .rating-number {
        font-size: 3rem;
        font-weight: 700;
        color: var(--dark);
        line-height: 1;
        margin-bottom: 0.5rem;
    }

    .rating-distribution .progress {
        background-color: var(--gray-200);
    }

    .review-item {
        border-left: 4px solid var(--primary);
        transition: var(--transition);
    }

    .review-item:hover {
        transform: translateY(-2px);
        box-shadow: var(--card-shadow-hover);
    }

    .review-header .stars {
        color: #ffc107;
    }

    .review-content {
        font-size: 1rem;
        line-height: 1.6;
    }

    .reply-section {
        border-top: 1px solid var(--gray-200);
        padding-top: 1rem;
        margin-top: 1rem;
    }

    .reply-content {
        border-left: 3px solid var(--primary);
    }

    .reply-icon {
        width: 24px;
        height: 24px;
        background: var(--primary);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
    }

    .review-tags .badge {
        font-size: 0.75rem;
        font-weight: 500;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize event listeners
    initializeReviewEvents();
});

function initializeReviewEvents() {
    // Reply form submission
    const replyForm = document.getElementById('replyForm');
    if (replyForm) {
        replyForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            console.log('Submitting reply:', Object.fromEntries(formData));
            showToast('Reply posted successfully!', 'success');
            bootstrap.Modal.getInstance(document.getElementById('replyModal')).hide();
            this.reset();
            location.reload();
        });
    }

    // Request reviews form
    const requestReviewsForm = document.getElementById('requestReviewsForm');
    if (requestReviewsForm) {
        requestReviewsForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            console.log('Sending review requests:', Object.fromEntries(formData));
            showToast('Review requests sent!', 'success');
            bootstrap.Modal.getInstance(document.getElementById('requestReviewsModal')).hide();
            this.reset();
        });
    }
}

// Review Filtering & Sorting
function filterReviews(filter) {
    const items = document.querySelectorAll('.review-item');
    const buttons = document.querySelectorAll('.btn-group .btn');
    
    // Update active button
    buttons.forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    
    // Filter items
    items.forEach(item => {
        switch(filter) {
            case 'all':
                item.style.display = '';
                break;
            case 'replied':
                item.style.display = item.dataset.replied === 'true' ? '' : 'none';
                break;
            case 'unreplied':
                item.style.display = item.dataset.replied === 'false' ? '' : 'none';
                break;
        }
    });
}

function sortReviews(sortBy) {
    console.log('Sorting reviews by:', sortBy);
    showToast(`Sorted by ${sortBy.replace('_', ' ')}`, 'info');
    // In real app, this would reorder reviews via AJAX
}

function filterByRating(rating) {
    const items = document.querySelectorAll('.review-item');
    
    if (rating === 'all') {
        items.forEach(item => item.style.display = '');
    } else {
        items.forEach(item => {
            item.style.display = item.dataset.rating == rating ? '' : 'none';
        });
    }
}

// Review Actions
function showReplyForm(reviewId) {
    document.getElementById('replyReviewId').value = reviewId;
    const modal = new bootstrap.Modal(document.getElementById('replyModal'));
    modal.show();
}

function reportReview(reviewId) {
    if (confirm('Report this review as inappropriate?')) {
        console.log('Reporting review:', reviewId);
        showToast('Review reported. Our team will review it shortly.', 'warning');
    }
}

function deleteReview(reviewId) {
    if (confirm('Delete this review? This action cannot be undone.')) {
        console.log('Deleting review:', reviewId);
        showToast('Review deleted', 'success');
        // In real app, this would delete via AJAX
        setTimeout(() => location.reload(), 1000);
    }
}

// Template Functions
function loadTemplate(template) {
    const textarea = document.querySelector('#requestReviewsForm textarea[name="message"]');
    const templates = {
        'friendly': 'Hi [Student Name],\n\nI hope you\'re doing well! I really enjoyed our recent [Subject] session together and was wondering if you could share your experience by leaving a review?\n\nYour feedback helps me improve and also helps other students find quality tutoring. Thanks so much!\n\nBest regards,\n[Your Name]',
        'professional': 'Dear [Student Name],\n\nI hope this message finds you well. Following our recent tutoring session in [Subject], I would greatly appreciate it if you could take a moment to share your feedback by leaving a review.\n\nYour insights are valuable for my professional development and help maintain the quality of my tutoring services.\n\nThank you for your consideration.\n\nSincerely,\n[Your Name]',
        'simple': 'Hi [Student Name],\n\nCould you please leave a review for our recent [Subject] session? Your feedback is important to me.\n\nThanks,\n[Your Name]'
    };
    
    if (template && templates[template]) {
        textarea.value = templates[template];
    }
}

function requestReviews() {
    const modal = new bootstrap.Modal(document.getElementById('requestReviewsModal'));
    modal.show();
}

function exportReviews() {
    console.log('Exporting reviews...');
    showToast('Exporting reviews to CSV...', 'info');
    // In real app, this would trigger a file download
}
</script>
<?= $this->endSection() ?>