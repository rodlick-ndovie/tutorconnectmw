<?= $this->extend('layout/trainer_shared') ?>

<?= $this->section('content') ?>

    <style>
        /* Form Styles */
        .form-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--gray-200);
        }

        .form-section {
            margin-bottom: 1.5rem;
        }

        .form-section:last-child {
            margin-bottom: 0;
        }

        .form-label {
            display: block;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }

        .form-label.required::after {
            content: ' *';
            color: var(--danger);
        }

        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid var(--gray-200);
            border-radius: var(--border-radius-sm);
            font-size: 1rem;
            transition: var(--transition);
            background: white;
        }

        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
        }

        .form-textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-help {
            font-size: 0.8rem;
            color: var(--gray-500);
            margin-top: 0.375rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        @media (min-width: 640px) {
            .form-row {
                grid-template-columns: 1fr 1fr;
            }
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: space-between;
            align-items: center;
            margin-top: 2rem;
        }

        .btn-secondary {
            padding: 0.875rem 1.5rem;
            background: var(--gray-100);
            color: var(--gray-700);
            border: 2px solid var(--gray-200);
            border-radius: var(--border-radius-sm);
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: var(--transition);
        }

        .btn-secondary:hover {
            background: var(--gray-200);
            border-color: var(--gray-300);
        }

        .btn-primary {
            padding: 0.875rem 1.5rem;
            background: var(--primary);
            color: white;
            border: 2px solid var(--primary);
            border-radius: var(--border-radius-sm);
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: var(--transition);
            cursor: pointer;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Info Cards */
        .info-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--gray-200);
        }

        .info-card-header {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .info-card-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
        }

        .info-card-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--dark);
            margin: 0;
        }

        .info-card-description {
            font-size: 0.9rem;
            color: var(--gray-600);
        }

        /* Process Steps */
        .process-steps {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        @media (min-width: 640px) {
            .process-steps {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        .process-step {
            text-align: center;
            padding: 1.5rem;
            background: var(--gray-50);
            border-radius: var(--border-radius);
        }

        .process-step-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.25rem;
        }

        .process-step-title {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }

        .process-step-description {
            font-size: 0.875rem;
            color: var(--gray-600);
        }

        /* Status Alerts */
        .status-alert {
            border-radius: var(--border-radius);
            padding: 1rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.95rem;
        }

        .status-alert.success { background-color: #d1fae5; color: #065f46; }
        .status-alert.warning { background-color: #fef3c7; color: #92400e; }
        .status-alert.info { background-color: #dbeafe; color: #1e40af; }
    </style>
    
    <!-- Status Alert for Subscription -->
    <?php if (!empty($subscription) && !empty($plan)): ?>
        <div class="status-alert success">
            <i class="fas fa-crown"></i>
            <div>
                <strong><?= esc($plan['name']) ?> Plan Active</strong><br>
                You can submit up to <?= $plan['name'] === 'Premium' ? '4' : '1' ?> approved video<?= $plan['name'] === 'Premium' ? 's' : '' ?> per month.
                <?php if ($plan['name'] === 'Premium'): ?>
                    Your videos are eligible for featured placement.
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Welcome Section -->
    <div class="welcome-card">
        <div class="welcome-content">
            <h1 class="text-2xl font-bold mb-2">Share Your Expertise! 🎬</h1>
            <p class="text-blue-100">Create educational video content to help students excel in their exams</p>
        </div>
    </div>

    <!-- Video Submission Form -->
    <div class="form-card">
        <form id="videoSubmissionForm" action="<?= site_url('trainer/submit-video') ?>" method="POST" enctype="multipart/form-data">

            <!-- Video URL -->
            <div class="form-section">
                <label for="video_url" class="form-label required">Video URL</label>
                <div class="relative">
                    <input type="url"
                           id="video_url"
                           name="video_url"
                           placeholder="Paste YouTube or Vimeo Video URL here"
                           class="form-input pr-12"
                           required>
                    <div class="absolute right-3 top-3 text-gray-400 flex items-center gap-1">
                        <i class="fab fa-youtube text-red-500"></i>
                        <i class="fab fa-vimeo text-blue-500"></i>
                    </div>
                </div>
                <p class="form-help">Paste the full URL from YouTube or Vimeo. The video must be public and embeddable.</p>
            </div>

            <!-- Title -->
            <div class="form-section">
                <label for="title" class="form-label required">Title</label>
                <input type="text"
                       id="title"
                       name="title"
                       placeholder="e.g., Solving 2023 MSCE Mathematics Paper 2 Question 5"
                       class="form-input"
                       maxlength="255"
                       required>
                <p class="form-help">Use a clear, descriptive title that explains what the video covers.</p>
            </div>

            <!-- Description -->
            <div class="form-section">
                <label for="description" class="form-label">Description</label>
                <textarea id="description"
                          name="description"
                          rows="4"
                          placeholder="Provide a detailed description of what the video covers, key learning points, and how it helps students..."
                          class="form-textarea"
                          maxlength="1000"></textarea>
                <p class="form-help">Optional but recommended: Help students understand what they'll learn from this video.</p>
            </div>

            <!-- Exam Body -->
            <div class="form-section">
                <label for="exam_body" class="form-label required">Exam Body</label>
                <select id="exam_body" name="exam_body" class="form-select" required>
                    <option value="">Select Exam Body</option>
                    <option value="MANEB">MANEB (Malawi)</option>
                    <option value="Cambridge">Cambridge International</option>
                    <option value="GCSE">GCSE</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <!-- Subject & Topic Row -->
            <div class="form-row">
                <div class="form-section">
                    <label for="subject" class="form-label required">Subject</label>
                    <input type="text"
                           id="subject"
                           name="subject"
                           placeholder="e.g., Mathematics, Physics, Chemistry"
                           class="form-input"
                           maxlength="100"
                           required>
                </div>

                <div class="form-section">
                    <label for="topic" class="form-label">Specific Topic</label>
                    <input type="text"
                           id="topic"
                           name="topic"
                           placeholder="e.g., Quadratic Equations, Chemical Bonding"
                           class="form-input"
                           maxlength="255">
                    <p class="form-help">Optional: Specify the exact topic or concept covered.</p>
                </div>
            </div>

            <!-- Problem Year -->
            <div class="form-section">
                <label for="problem_year" class="form-label">Problem Year</label>
                <input type="number"
                       id="problem_year"
                       name="problem_year"
                       placeholder="e.g., 2023"
                       min="2000"
                       max="2030"
                       class="form-input">
                <p class="form-help">Optional: Year of the exam question (if applicable).</p>
            </div>

            <!-- Content Guidelines -->
            <div class="info-card">
                <div class="info-card-header">
                    <div class="info-card-icon" style="background-color: #dbeafe;">
                        <i class="fas fa-info-circle text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="info-card-title">Content Guidelines</h3>
                        <p class="info-card-description">Please follow these guidelines for successful video approval</p>
                    </div>
                </div>
                <ul class="text-sm text-gray-700 space-y-1 ml-4">
                    <li>• Video must be educational and directly related to exam preparation</li>
                    <li>• Content should be original and appropriately credited</li>
                    <li>• Avoid copyrighted material without permission</li>
                    <li>• Keep language professional and appropriate for students</li>
                    <li>• Ensure clear audio and good video quality</li>
                </ul>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="<?= site_url('trainer/dashboard') ?>" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Back to Dashboard
                </a>

                <button type="submit" id="submitBtn" class="btn-primary">
                    <i class="fas fa-upload"></i>
                    Submit Video for Review
                </button>
            </div>
        </form>
    </div>

    <!-- Process Information -->
    <div class="info-card">
        <h3 class="info-card-title mb-4 flex items-center">
            <i class="fas fa-clock mr-2 text-blue-600"></i>
            What Happens Next?
        </h3>

        <div class="process-steps">
            <div class="process-step">
                <div class="process-step-icon" style="background-color: #dbeafe;">
                    <i class="fas fa-upload text-blue-600"></i>
                </div>
                <h4 class="process-step-title">1. Submission</h4>
                <p class="process-step-description">Your video is submitted and queued for review.</p>
            </div>

            <div class="process-step">
                <div class="process-step-icon" style="background-color: #fef3c7;">
                    <i class="fas fa-user-check text-yellow-600"></i>
                </div>
                <h4 class="process-step-title">2. Review</h4>
                <p class="process-step-description">Admin reviews content quality and educational value.</p>
            </div>

            <div class="process-step">
                <div class="process-step-icon" style="background-color: #d1fae5;">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <h4 class="process-step-title">3. Publication</h4>
                <p class="process-step-description">Approved videos are published and become visible to students.</p>
            </div>
        </div>

        <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
            <div class="flex items-start">
                <i class="fas fa-lightbulb text-yellow-600 mt-0.5 mr-3"></i>
                <div>
                    <h5 class="text-sm font-medium text-yellow-800">Pro Tip</h5>
                    <p class="text-sm text-yellow-700 mt-1">
                        High-quality videos with clear explanations and good production values are more likely to be approved and featured.
                        <?php if (!empty($plan) && $plan['name'] === 'Premium'): ?>
                            As a Premium member, your approved videos are automatically eligible for featured placement.
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Form submission handling
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('videoSubmissionForm');
            const submitBtn = document.getElementById('submitBtn');

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // Show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-upload"></i>Submitting...<div class="loading-spinner"></div>';

                // Submit form via AJAX
                const formData = new FormData(form);

                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        showNotification('success', data.message);

                        // Reset form
                        form.reset();

                        // Redirect to dashboard after delay
                        setTimeout(() => {
                            window.location.href = '<?= site_url('trainer/dashboard') ?>';
                        }, 2000);
                    } else {
                        // Show error message
                        showNotification('error', data.message || 'An error occurred. Please try again.');

                        // Show field errors if available
                        if (data.errors) {
                            Object.keys(data.errors).forEach(field => {
                                const input = document.querySelector(`[name="${field}"]`);
                                if (input) {
                                    input.classList.add('border-red-500');
                                    // Add error message below field
                                    let errorDiv = input.parentNode.querySelector('.field-error');
                                    if (!errorDiv) {
                                        errorDiv = document.createElement('div');
                                        errorDiv.className = 'field-error text-red-600 text-sm mt-1';
                                        input.parentNode.appendChild(errorDiv);
                                    }
                                    errorDiv.textContent = data.errors[field];
                                }
                            });
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('error', 'Network error. Please check your connection and try again.');
                })
                .finally(() => {
                    // Reset button state
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-upload"></i>Submit Video for Review';
                });
            });

            // Clear field errors on input
            document.querySelectorAll('input, select, textarea').forEach(field => {
                field.addEventListener('input', function() {
                    this.classList.remove('border-red-500');
                    const errorDiv = this.parentNode.querySelector('.field-error');
                    if (errorDiv) {
                        errorDiv.remove();
                    }
                });
            });
        });

        // Notification toast function
        function showNotification(type, message) {
            // Remove existing notifications
            const existingNotifications = document.querySelectorAll('.notification-toast');
            existingNotifications.forEach(notification => notification.remove());

            // Create notification element
            const notification = document.createElement('div');
            notification.className = `notification-toast ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
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
    </script>
<?= $this->endSection() ?>