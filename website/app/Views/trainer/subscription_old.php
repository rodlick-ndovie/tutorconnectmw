<?= $this->extend('layout/trainer_mobile') ?>

<?= $this->section('content') ?>

    <style>
        .section-title {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }

        .section-subtitle {
            font-size: 0.95rem;
            color: var(--gray-500);
            margin-bottom: 1.5rem;
        }

        .current-plan-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 1.5rem;
            border-left: 5px solid var(--primary);
        }

        .plan-badge {
            width: 60px;
            height: 60px;
            background: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.8rem;
        }

        .benefits-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .benefit-item {
            text-align: center;
            padding: 1rem;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
        }

        .benefit-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            color: white;
            margin: 0 auto 1rem;
        }

        .plans-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        /* Desktop: 2 columns */
        @media (min-width: 992px) {
            .plans-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1.5rem;
            }
        }

        .plan-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            text-align: center;
            position: relative;
            transition: var(--transition);
        }

        .plan-card:hover {
            box-shadow: var(--card-shadow-hover);
            transform: translateY(-2px);
        }

        .plan-card.featured {
            border: 2px solid var(--primary);
            transform: scale(1.02);
        }

        .plan-card.featured:hover {
            transform: scale(1.04) translateY(-2px);
        }

        /* Free plan special styling */
        .plan-card.free-plan {
            border: 2px solid var(--success);
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.05), rgba(16, 185, 129, 0.02));
        }

        .plan-card.free-plan .plan-price {
            color: var(--success);
        }

        .plan-card.free-plan .plan-button {
            background: var(--success);
            border-color: var(--success);
        }

        .plan-card.free-plan .plan-button:hover {
            background: #059669;
            border-color: #059669;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .plan-name {
            font-size: 1.4rem;
            font-weight: 700;
            margin: 1rem 0 0.5rem;
        }

        .plan-price {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary);
            margin: 0.5rem 0;
        }

        .plan-period {
            color: var(--gray-500);
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        .plan-features {
            text-align: left;
            margin: 1.5rem 0;
        }

        .plan-features li {
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }

        .plan-button {
            width: 100%;
            padding: 0.875rem;
            font-weight: 600;
            border-radius: var(--border-radius-sm);
            transition: var(--transition);
        }

        .plan-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .billing-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
        }

        @media (max-width: 480px) {
            .benefits-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <h1 class="section-title">Subscription Plans</h1>
    <p class="section-subtitle">Choose the plan that best fits your tutoring needs</p>

    <!-- Current Plan -->
    <?php if(isset($current_subscription) && $current_subscription): ?>
    <div class="current-plan-card">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="plan-badge">
                    <i class="fas fa-crown"></i>
                </div>
                <div>
                    <h4 class="mb-1"><?= htmlspecialchars($current_subscription['plan_name']) ?></h4>
                    <p class="text-muted mb-0">Renews on <?= date('M d, Y', strtotime($current_subscription['next_billing_date'])) ?></p>
                </div>
            </div>
            <div class="text-end">
                <p class="mb-1"><strong>MWK <?= number_format($current_subscription['amount']) ?> / month</strong></p>
                <div class="d-flex gap-2 flex-wrap justify-content-end">
                    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#upgradeModal">
                        Upgrade
                    </button>
                    <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#cancelModal">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Available Plans -->
    <div class="plans-grid">
        <?php foreach($available_plans as $index => $plan): ?>
        <?php
            $is_current = isset($current_subscription) && $current_subscription &&
                        $current_subscription['plan_id'] == $plan['id'];
            $is_free = $plan['price_monthly'] == 0;
        ?>
        <div class="plan-card <?= $index === 1 ? 'featured' : '' ?> <?= $is_free ? 'free-plan' : '' ?>">
            <?php if($index === 1): ?>
                <div class="position-absolute top-0 start-50 translate-middle-x badge rounded-pill bg-primary" style="top: -12px;">
                    Most Popular
                </div>
            <?php elseif($is_free): ?>
                <div class="position-absolute top-0 start-50 translate-middle-x badge rounded-pill bg-success" style="top: -12px;">
                    <i class="fas fa-gift me-1"></i>FREE
                </div>
            <?php endif; ?>
            <h4 class="plan-name"><?= htmlspecialchars($plan['name']) ?></h4>
            <div class="plan-price">
                <?php if($plan['price_monthly'] > 0): ?>
                    MWK <?= number_format($plan['price_monthly']) ?>
                <?php else: ?>
                    FREE
                <?php endif; ?>
            </div>
            <?php if($plan['price_monthly'] > 0): ?>
                <div class="plan-period">per month</div>
            <?php endif; ?>

            <ul class="plan-features list-unstyled">
                <li><i class="fas fa-check text-success"></i> <?= $plan['features']['students'] ?? 'Up to 5 students' ?></li>
                <li><i class="fas fa-check text-success"></i> <?= $plan['features']['sessions'] ?? 'Up to 20 sessions' ?></li>
                <li><i class="fas fa-check text-success"></i> <?= $plan['features']['inquiries'] ? 'Student Inquiries' : 'Limited Inquiries' ?></li>
                <li><i class="fas fa-check text-success"></i> <?= $plan['features']['analytics'] ? 'Advanced Analytics' : 'Basic Analytics' ?></li>
                <li><i class="fas fa-check text-success"></i> <?= $plan['features']['support'] ? 'Priority Support' : 'Standard Support' ?></li>
            </ul>

            <?php if($is_current): ?>
                <button class="plan-button btn btn-secondary" disabled>Current Plan</button>
            <?php else: ?>
                <button type="button" class="plan-button btn btn-primary subscribe-btn"
                        data-plan-id="<?= $plan['id'] ?>"
                        data-plan-name="<?= htmlspecialchars($plan['name']) ?>">
                    <?= isset($current_subscription) ? 'Switch to this Plan' : 'Choose Plan' ?>
                </button>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Billing History -->
    <div class="billing-card">
        <h5 class="mb-3">Billing History</h5>
        <?php if(!empty($billing_history)): ?>
            <?php foreach($billing_history as $invoice): ?>
                <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                    <div>
                        <div class="fw-semibold"><?= date('M d, Y', strtotime($invoice['date'])) ?></div>
                        <div class="small text-muted"><?= htmlspecialchars($invoice['description']) ?></div>
                    </div>
                    <div class="text-end">
                        <div class="fw-semibold">MWK <?= number_format($invoice['amount']) ?></div>
                        <div class="small">
                            <span class="badge bg-<?= $invoice['status'] === 'paid' ? 'success' : 'warning' ?>">
                                <?= ucfirst($invoice['status']) ?>
                            </span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center py-5 text-muted">
                <i class="fas fa-receipt fa-3x mb-3"></i>
                <p>No billing history yet</p>
            </div>
        <?php endif; ?>
    </div>
    
    <script>
        // PayChangu Inline Checkout Integration
        document.addEventListener('DOMContentLoaded', function() {
            const subscribeButtons = document.querySelectorAll('.subscribe-btn');

            // Function to get CSRF token from cookie
            function getCsrfToken() {
                const name = 'csrf_cookie_name';
                const decodedCookie = decodeURIComponent(document.cookie);
                const cookies = decodedCookie.split(';');
                for (let cookie of cookies) {
                    cookie = cookie.trim();
                    if (cookie.indexOf(name + '=') === 0) {
                        return cookie.substring(name.length + 1);
                    }
                }
                return '';
            }

            subscribeButtons.forEach(button => {
                button.addEventListener('click', async function() {
                    const planId = this.getAttribute('data-plan-id');
                    const planName = this.getAttribute('data-plan-name');

                    // Disable button and show loading
                    this.disabled = true;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

                    try {
                        // Get CSRF token
                        const csrfToken = getCsrfToken();

                        // Make AJAX call to get PayChangu configuration
                        const response = await fetch(`<?= base_url('trainer/subscribe/') ?>${planId}`, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Content-Type': 'application/x-www-form-urlencoded',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: `csrf_test_name=${encodeURIComponent(csrfToken)}`
                        });

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const result = await response.json();

                        if (result.success) {
                            if (result.redirect) {
                                // Free plan - redirect to dashboard
                                window.location.href = result.redirect;
                            } else if (result.paychangu_config) {
                                // Wait for PayChangu SDK to be ready
                                const checkPayChanguReady = () => {
                                    if (typeof PaychanguCheckout !== 'undefined') {
                                        console.log('PayChangu SDK ready, initializing checkout...');
                                        // Initialize PayChangu inline checkout
                                        PaychanguCheckout({
                                            ...result.paychangu_config,
                                            inline: true, // Enable inline popup mode
                                            callback: function(response) {
                                                console.log('Payment completed:', response);

                                                if (response.status === 'success') {
                                                    // Payment successful - redirect to success page
                                                    window.location.href = '<?= base_url('checkout/paychangu/success?tx_ref=') ?>' + response.tx_ref;
                                                } else {
                                                    // Payment failed
                                                    alert('Payment failed. Please try again.');
                                                    location.reload();
                                                }
                                            },
                                            onClose: function() {
                                                console.log('Payment modal closed');
                                                // Re-enable button
                                                button.disabled = false;
                                                button.innerHTML = 'Choose Plan';
                                            }
                                        });
                                    } else {
                                        console.log('PayChangu SDK not ready, waiting...');
                                        // Wait a bit and try again
                                        setTimeout(checkPayChanguReady, 200);
                                    }
                                };

                                // Start checking if PayChangu is ready
                                checkPayChanguReady();

                                // Timeout after 5 seconds - fallback to redirect
                                setTimeout(() => {
                                    if (typeof PaychanguCheckout === 'undefined') {
                                        console.log('PayChangu SDK not available, redirecting to checkout page...');
                                        // Fallback: redirect to standard checkout page
                                        window.location.href = `<?= base_url('checkout/subscription/') ?>${planId}`;
                                    }
                                }, 5000);
                            }
                        } else {
                            alert(result.message || 'An error occurred. Please try again.');
                            // Re-enable button
                            button.disabled = false;
                            button.innerHTML = 'Choose Plan';
                        }
                    } catch (error) {
                        console.error('Subscription error:', error);
                        alert('Network error. Please check your connection and try again.');
                        // Re-enable button
                        button.disabled = false;
                        button.innerHTML = 'Choose Plan';
                    }
                });
            });
        });
    </script>
<?= $this->endSection() ?>