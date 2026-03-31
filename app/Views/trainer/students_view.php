<?= $this->extend('templates/trainer_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Student Header -->
    <div class="student-header mb-4" data-aos="fade-up">
        <div class="row align-items-center">
            <div class="col-md-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page"><?= esc($student['name']) ?></li>
                    </ol>
                </nav>
                <div class="d-flex align-items-center">
                    <div class="student-avatar me-3">
                        <?php if($student['profile_pic']): ?>
                            <img src="<?= base_url($student['profile_pic']) ?>" 
                                 class="rounded-circle border border-3 border-white shadow" 
                                 width="80" height="80" 
                                 alt="<?= $student['name'] ?>">
                        <?php else: ?>
                            <div class="avatar-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center">
                                <span style="font-size: 32px; font-weight: 700;">
                                    <?= strtoupper(substr($student['name'], 0, 1)) ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div>
                        <h1 class="h3 fw-bold mb-1"><?= esc($student['name']) ?></h1>
                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            <span class="badge bg-primary">Student ID: <?= $student['student_id'] ?></span>
                            <span class="badge bg-<?= $student['status'] === 'active' ? 'success' : 'warning' ?>">
                                <?= ucfirst($student['status']) ?>
                            </span>
                            <span class="text-muted">
                                <i class="fas fa-calendar-alt me-1"></i>
                                Joined <?= date('M d, Y', strtotime($student['created_at'])) ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="d-flex gap-2 justify-content-md-end">
                    <button class="btn btn-outline-primary" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Print Profile
                    </button>
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-cog me-2"></i>Actions
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editStudentModal">
                                    <i class="fas fa-edit me-2"></i>Edit Student
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#sendMessageModal">
                                    <i class="fas fa-message me-2"></i>Send Message
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#removeStudentModal">
                                    <i class="fas fa-trash me-2"></i>Remove Student
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column - Student Info -->
        <div class="col-lg-4 mb-4">
            <!-- Contact Card -->
            <div class="card mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card-body">
                    <h5 class="card-title mb-4">Contact Information</h5>
                    <div class="contact-info">
                        <div class="info-item mb-3">
                            <div class="d-flex align-items-center">
                                <div class="info-icon bg-primary">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="ms-3">
                                    <small class="text-muted d-block">Email</small>
                                    <a href="mailto:<?= esc($student['email']) ?>" class="text-decoration-none">
                                        <?= esc($student['email']) ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="info-item mb-3">
                            <div class="d-flex align-items-center">
                                <div class="info-icon bg-success">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div class="ms-3">
                                    <small class="text-muted d-block">Phone</small>
                                    <a href="tel:<?= esc($student['phone'] ?? '') ?>" class="text-decoration-none">
                                        <?= esc($student['phone'] ?? 'Not provided') ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="info-item mb-3">
                            <div class="d-flex align-items-center">
                                <div class="info-icon bg-warning">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="ms-3">
                                    <small class="text-muted d-block">Location</small>
                                    <span><?= esc($student['location'] ?? 'Not specified') ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="d-flex align-items-center">
                                <div class="info-icon bg-info">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <div class="ms-3">
                                    <small class="text-muted d-block">Grade Level</small>
                                    <span><?= esc($student['grade_level'] ?? 'Not specified') ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Student Stats -->
            <div class="card mb-4" data-aos="fade-up" data-aos-delay="150">
                <div class="card-body">
                    <h5 class="card-title mb-4">Student Statistics</h5>
                    <div class="stats-grid">
                        <div class="stat-item text-center">
                            <div class="stat-value"><?= $stats['attendance_rate'] ?? 0 ?>%</div>
                            <div class="stat-label">Attendance</div>
                        </div>
                        <div class="stat-item text-center">
                            <div class="stat-value"><?= $stats['avg_rating'] ?? 0 ?></div>
                            <div class="stat-label">Avg Rating</div>
                        </div>
                        <div class="stat-item text-center">
                            <div class="stat-value">MWK <?= number_format($stats['total_paid'] ?? 0) ?></div>
                            <div class="stat-label">Total Paid</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subjects -->
            <div class="card" data-aos="fade-up" data-aos-delay="200">
                <div class="card-body">
                    <h5 class="card-title mb-4">Subjects & Skills</h5>
                    <div class="subjects-list">
                        <?php foreach($subjects as $subject): ?>
                            <div class="subject-item mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0"><?= esc($subject['name']) ?></h6>
                                    <span class="badge bg-primary">Level <?= $subject['level'] ?? 'Beginner' ?></span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-primary" style="width: <?= $subject['progress'] ?? 0 ?>%"></div>
                                </div>
                                <small class="text-muted mt-2 d-block">
                                    <?= $subject['notes'] ?? 'No additional notes' ?>
                                </small>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Sessions & Activity -->
        <div class="col-lg-8">


            <!-- Notes & Assessments -->
            <div class="card mb-4" data-aos="fade-up" data-aos-delay="150">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">Notes & Assessments</h5>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addNoteModal">
                            <i class="fas fa-plus me-2"></i>Add Note
                        </button>
                    </div>

                    <?php if(!empty($notes)): ?>
                        <div class="notes-list">
                            <?php foreach($notes as $note): ?>
                                <div class="note-item mb-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 class="mb-1"><?= esc($note['title']) ?></h6>
                                            <small class="text-muted">
                                                <i class="far fa-calendar me-1"></i>
                                                <?= date('M d, Y', strtotime($note['created_at'])) ?>
                                            </small>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="#" onclick="editNote(<?= $note['id'] ?>)">
                                                        <i class="fas fa-edit me-2"></i>Edit
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item text-danger" href="#" onclick="deleteNote(<?= $note['id'] ?>)">
                                                        <i class="fas fa-trash me-2"></i>Delete
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <p class="mb-0"><?= nl2br(esc($note['content'])) ?></p>
                                    <?php if($note['tags']): ?>
                                        <div class="mt-2">
                                            <?php foreach(explode(',', $note['tags']) as $tag): ?>
                                                <span class="badge bg-light text-dark me-1"><?= trim($tag) ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <hr class="my-3">
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-sticky-note fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">No notes yet</h6>
                            <p class="text-muted small">Add notes to track student progress</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Payment History -->
            <div class="card" data-aos="fade-up" data-aos-delay="200">
                <div class="card-body">
                    <h5 class="card-title mb-4">Payment History</h5>
                    
                    <?php if(!empty($payments)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Session</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                        <th>Receipt</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($payments as $payment): ?>
                                        <tr>
                                            <td><?= date('M d, Y', strtotime($payment['date'])) ?></td>
                                            <td>
                                                <?= date('M d', strtotime($payment['session_date'])) ?> - 
                                                <?= $payment['subject'] ?>
                                            </td>
                                            <td>MWK <?= number_format($payment['amount']) ?></td>
                                            <td>
                                                <span class="badge bg-light text-dark">
                                                    <?= ucfirst($payment['method']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= $payment['status'] === 'paid' ? 'success' : 'warning' ?>">
                                                    <?= ucfirst($payment['status']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if($payment['receipt_url']): ?>
                                                    <a href="<?= $payment['receipt_url'] ?>" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-muted">N/A</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">No payment history</h6>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Add Note Modal -->
<div class="modal fade" id="addNoteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Student Note</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addNoteForm">
                    <input type="hidden" name="student_id" value="<?= $student['id'] ?>">
                    <div class="mb-3">
                        <label class="form-label">Title *</label>
                        <input type="text" class="form-control" name="title" placeholder="e.g., Progress Review, Assessment" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Content *</label>
                        <textarea class="form-control" name="content" rows="4" required placeholder="Add your notes here..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tags (Optional)</label>
                        <input type="text" class="form-control" name="tags" placeholder="e.g., progress, assessment, homework">
                        <small class="text-muted">Separate tags with commas</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="addNoteForm" class="btn btn-primary">Save Note</button>
            </div>
        </div>
    </div>
</div>

<!-- Send Message Modal -->
<div class="modal fade" id="sendMessageModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send Message to <?= esc($student['name']) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="sendMessageForm">
                    <div class="mb-3">
                        <label class="form-label">Subject</label>
                        <input type="text" class="form-control" name="subject" placeholder="Message subject...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message *</label>
                        <textarea class="form-control" name="message" rows="5" required placeholder="Type your message here..."></textarea>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="send_email" id="sendEmail" checked>
                        <label class="form-check-label" for="sendEmail">
                            Also send as email to <?= esc($student['email']) ?>
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="sendMessageForm" class="btn btn-primary">Send Message</button>
            </div>
        </div>
    </div>
</div>

<style>
    .student-avatar img {
        border: 3px solid white;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .avatar-lg {
        width: 80px;
        height: 80px;
        font-size: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .info-icon {
        width: 40px;
        height: 40px;
        border-radius: var(--border-radius);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .stat-item {
        padding: 1rem;
        background: var(--gray-50);
        border-radius: var(--border-radius);
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark);
    }

    .stat-label {
        font-size: 0.875rem;
        color: var(--gray-500);
    }

    .subject-item {
        padding: 0.75rem;
        background: white;
        border-radius: var(--border-radius-sm);
        border: 1px solid var(--gray-200);
    }

    .timeline-item {
        padding: 1rem;
        background: var(--gray-50);
        border-radius: var(--border-radius);
        border-left: 4px solid var(--primary);
    }

    .note-item {
        padding: 1rem;
        background: white;
        border-radius: var(--border-radius-sm);
        border: 1px solid var(--gray-200);
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {


    // Add note form
    document.getElementById('addNoteForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        console.log('Adding note:', Object.fromEntries(formData));
        showToast('Note added successfully!', 'success');
        bootstrap.Modal.getInstance(document.getElementById('addNoteModal')).hide();
        this.reset();
        location.reload();
    });

    // Send message form
    document.getElementById('sendMessageForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        console.log('Sending message:', Object.fromEntries(formData));
        showToast('Message sent successfully!', 'success');
        bootstrap.Modal.getInstance(document.getElementById('sendMessageModal')).hide();
        this.reset();
    });

    // Note actions
    window.editNote = function(id) {
        // Implement edit note functionality
        console.log('Editing note:', id);
        showToast('Opening note editor...', 'info');
    };

    window.deleteNote = function(id) {
        if (confirm('Are you sure you want to delete this note?')) {
            console.log('Deleting note:', id);
            showToast('Note deleted!', 'warning');
            // Reload to reflect changes
            setTimeout(() => location.reload(), 1000);
        }
    };
});

function showToast(message, type = 'success') {
    // Toast implementation
}
</script>
<?= $this->endSection() ?>