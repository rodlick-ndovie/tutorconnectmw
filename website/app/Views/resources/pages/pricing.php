<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="bg-gradient-to-r from-primary to-red-600 text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-5xl md:text-6xl font-extrabold mb-4">Simple & Affordable Pricing</h1>
        <p class="text-xl text-red-100 max-w-3xl mx-auto">Choose the perfect plan for your tutoring business. Start earning today!</p>
    </div>
</section>

<!-- Pricing Plans Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Our Plans</h2>
            <p class="text-xl text-gray-600">Flexible subscription plans designed for tutors of all levels</p>
        </div>

        <!-- Pricing Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Dynamic Plans -->
            <?php
            $plans = array_filter($pricing_plans ?? [], function($plan) {
                return $plan['is_active'] == 1;
            });

            // Sort by sort_order
            usort($plans, function($a, $b) {
                return $a['sort_order'] <=> $b['sort_order'];
            });

            $popularIndex = 1; // Mark the Professional plan as popular (index 1)
            ?>

            <?php if (!empty($plans)): ?>
                <?php foreach ($plans as $index => $plan):
                    $isPopular = ($index === $popularIndex);
                    $cardClass = $isPopular ? 'relative border-2 border-primary hover:shadow-2xl scale-105' : 'hover:shadow-lg';
                ?>
                    <!-- <?php echo esc($plan['name']); ?> Plan -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-200 <?php echo $cardClass; ?> transition-all duration-300">
                        <?php if ($isPopular): ?>
                            <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                                <span class="bg-primary text-white px-4 py-1 rounded-full text-sm font-medium">Most Popular</span>
                            </div>
                        <?php endif; ?>

                        <div class="text-center">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2"><?php echo esc($plan['name']); ?> Plan</h3>
                            <div class="text-4xl font-extrabold text-gray-900 mb-2">
                                <?php if ($plan['price_monthly'] > 0): ?>
                                    MWK <?php echo number_format($plan['price_monthly']); ?>
                                <?php else: ?>
                                    FREE
                                <?php endif; ?>
                            </div>
                            <?php if ($plan['price_monthly'] > 0): ?>
                                <p class="text-gray-600 mb-6">per month</p>
                            <?php endif; ?>

                            <?php if ($plan['price_monthly'] > 0): ?>
                                <p class="text-sm text-gray-500 mb-8 bg-green-50 text-green-600 px-3 py-1 rounded-full inline-block">
                                    Join our growing community!
                                </p>
                            <?php else: ?>
                                <p class="text-sm text-gray-500 mb-8 bg-blue-50 text-blue-600 px-3 py-1 rounded-full inline-block">
                                    Free Trial Plan
                                </p>
                            <?php endif; ?>

                            <!-- What's Included -->
                            <div class="mb-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-check-circle text-primary mr-2"></i>
                                    What's Included
                                </h4>
                                <ul class="space-y-3 text-left">
                                    <!-- Profile Views -->
                                    <li class="flex items-center">
                                        <i class="fas fa-eye text-primary mr-3 flex-shrink-0 text-sm"></i>
                                        <span class="text-gray-700 text-sm">
                                            <?php if (($plan['max_profile_views'] ?? 0) == 0): ?>
                                                <strong>Unlimited</strong> profile views
                                            <?php else: ?>
                                                Up to <strong><?= number_format($plan['max_profile_views']) ?></strong> profile views per month
                                            <?php endif; ?>
                                        </span>
                                    </li>

                                    <!-- Contact Clicks -->
                                    <li class="flex items-center">
                                        <i class="fas fa-mouse-pointer text-primary mr-3 flex-shrink-0 text-sm"></i>
                                        <span class="text-gray-700 text-sm">
                                            <?php if (($plan['max_clicks'] ?? 0) == 0): ?>
                                                <strong>Unlimited</strong> contact clicks
                                            <?php else: ?>
                                                Up to <strong><?= number_format($plan['max_clicks']) ?></strong> contact clicks per month
                                            <?php endif; ?>
                                        </span>
                                    </li>

                                    <!-- Subjects -->
                                    <li class="flex items-center">
                                        <i class="fas fa-book text-primary mr-3 flex-shrink-0 text-sm"></i>
                                        <span class="text-gray-700 text-sm">
                                            <?php if (($plan['max_subjects'] ?? 0) == 0): ?>
                                                <strong>Unlimited</strong> subjects
                                            <?php else: ?>
                                                Up to <strong><?= $plan['max_subjects'] ?></strong> subjects
                                            <?php endif; ?>
                                        </span>
                                    </li>

                                    <!-- Reviews -->
                                    <li class="flex items-center">
                                        <i class="fas fa-star text-primary mr-3 flex-shrink-0 text-sm"></i>
                                        <span class="text-gray-700 text-sm">
                                            <?php if (($plan['max_reviews'] ?? 0) == 0): ?>
                                                <strong>Unlimited</strong> reviews display
                                            <?php else: ?>
                                                Up to <strong><?= $plan['max_reviews'] ?></strong> reviews display
                                            <?php endif; ?>
                                        </span>
                                    </li>

                                    <!-- Messages -->
                                    <li class="flex items-center">
                                        <i class="fas fa-envelope text-primary mr-3 flex-shrink-0 text-sm"></i>
                                        <span class="text-gray-700 text-sm">
                                            <?php if (($plan['max_messages'] ?? 0) == 0): ?>
                                                <strong>Unlimited</strong> messages
                                            <?php else: ?>
                                                Up to <strong><?= number_format($plan['max_messages']) ?></strong> messages per month
                                            <?php endif; ?>
                                        </span>
                                    </li>

                                    <!-- WhatsApp Contact -->
                                    <?php if (($plan['show_whatsapp'] ?? 0) == 1): ?>
                                        <li class="flex items-center">
                                            <i class="fab fa-whatsapp text-primary mr-3 flex-shrink-0 text-sm"></i>
                                            <span class="text-gray-700 text-sm"><strong>WhatsApp</strong> contact visible to students</span>
                                        </li>
                                    <?php endif; ?>

                                    <!-- Email Marketing -->
                                    <?php if (($plan['email_marketing_access'] ?? 0) == 1): ?>
                                        <li class="flex items-center">
                                            <i class="fas fa-envelope text-primary mr-3 flex-shrink-0 text-sm"></i>
                                            <span class="text-gray-700 text-sm"><strong>Email marketing</strong> access & tools</span>
                                        </li>
                                    <?php endif; ?>

                                    <!-- Video Upload -->
                                    <?php if (($plan['allow_video_upload'] ?? 0) == 1): ?>
                                        <li class="flex items-center">
                                            <i class="fas fa-video text-primary mr-3 flex-shrink-0 text-sm"></i>
                                            <span class="text-gray-700 text-sm"><strong>Bio video</strong> display capability</span>
                                        </li>
                                    <?php endif; ?>

                                    <!-- PDF Upload -->
                                    <?php if (($plan['allow_pdf_upload'] ?? 0) == 1): ?>
                                        <li class="flex items-center">
                                            <i class="fas fa-file-pdf text-primary mr-3 flex-shrink-0 text-sm"></i>
                                            <span class="text-gray-700 text-sm"><strong>Past Papers PDF</strong> upload capability</span>
                                        </li>
                                    <?php endif; ?>

                                    <!-- Video Solution Upload -->
                                    <?php if (($plan['allow_video_solution'] ?? 0) == 1): ?>
                                        <li class="flex items-center">
                                            <i class="fas fa-video text-primary mr-3 flex-shrink-0 text-sm"></i>
                                            <span class="text-gray-700 text-sm"><strong>Video solution</strong> upload & sharing capability</span>
                                        </li>
                                    <?php endif; ?>

                                    <!-- Announcements -->
                                    <?php if (($plan['allow_announcements'] ?? 0) == 1): ?>
                                        <li class="flex items-center">
                                            <i class="fas fa-bullhorn text-primary mr-3 flex-shrink-0 text-sm"></i>
                                            <span class="text-gray-700 text-sm"><strong>School announcements</strong> posting access</span>
                                        </li>
                                    <?php endif; ?>

                                    <!-- Search Ranking -->
                                    <?php if (!empty($plan['search_ranking']) && $plan['search_ranking'] !== 'low'): ?>
                                        <li class="flex items-center">
                                            <i class="fas fa-arrow-up text-primary mr-3 flex-shrink-0 text-sm"></i>
                                            <span class="text-gray-700 text-sm"><strong><?php echo ucfirst($plan['search_ranking']); ?></strong> search ranking priority</span>
                                        </li>
                                    <?php endif; ?>

                                    <!-- District Spotlight -->
                                    <?php if (($plan['district_spotlight_days'] ?? 0) > 0): ?>
                                        <li class="flex items-center">
                                            <i class="fas fa-map-marker-alt text-primary mr-3 flex-shrink-0 text-sm"></i>
                                            <span class="text-gray-700 text-sm"><strong><?php echo $plan['district_spotlight_days']; ?> days</strong> district spotlight feature</span>
                                        </li>
                                    <?php endif; ?>

                                    <!-- Badge Level -->
                                    <?php if (!empty($plan['badge_level']) && $plan['badge_level'] !== 'none'): ?>
                                        <li class="flex items-center">
                                            <i class="fas fa-trophy text-primary mr-3 flex-shrink-0 text-sm"></i>
                                            <span class="text-gray-700 text-sm"><strong><?php echo ucfirst(str_replace('_', ' ', $plan['badge_level'])); ?></strong> profile badge & visibility</span>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>

                            <!-- What's Not Included -->
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-times-circle text-gray-400 mr-2"></i>
                                    What's Not Included
                                </h4>
                                <ul class="space-y-2 text-left">
                                    <!-- WhatsApp Contact (if not included) -->
                                    <?php if (($plan['show_whatsapp'] ?? 0) == 0): ?>
                                        <li class="flex items-center">
                                            <i class="fab fa-whatsapp text-gray-400 mr-3 flex-shrink-0 text-sm"></i>
                                            <span class="text-gray-500 text-sm">WhatsApp contact visibility</span>
                                        </li>
                                    <?php endif; ?>

                                    <!-- Email Marketing (if not included) -->
                                    <?php if (($plan['email_marketing_access'] ?? 0) == 0): ?>
                                        <li class="flex items-center">
                                            <i class="fas fa-envelope text-gray-400 mr-3 flex-shrink-0 text-sm"></i>
                                            <span class="text-gray-500 text-sm">Email marketing access</span>
                                        </li>
                                    <?php endif; ?>

                                    <!-- Video Upload (if not included) -->
                                    <?php if (($plan['allow_video_upload'] ?? 0) == 0): ?>
                                        <li class="flex items-center">
                                            <i class="fas fa-times text-gray-400 mr-3 flex-shrink-0 text-sm"></i>
                                            <span class="text-gray-500 text-sm">Bio video display capability</span>
                                        </li>
                                    <?php endif; ?>

                                    <!-- PDF Upload (if not included) -->
                                    <?php if (($plan['allow_pdf_upload'] ?? 0) == 0): ?>
                                        <li class="flex items-center">
                                            <i class="fas fa-times text-gray-400 mr-3 flex-shrink-0 text-sm"></i>
                                            <span class="text-gray-500 text-sm">Past Papers PDF upload capability</span>
                                        </li>
                                    <?php endif; ?>

                                    <!-- Announcements (if not included) -->
                                    <?php if (($plan['allow_announcements'] ?? 0) == 0): ?>
                                        <li class="flex items-center">
                                            <i class="fas fa-times text-gray-400 mr-3 flex-shrink-0 text-sm"></i>
                                            <span class="text-gray-500 text-sm">School announcements posting</span>
                                        </li>
                                    <?php endif; ?>

                                    <!-- If no premium features are missing, show a note -->
                                    <?php if (
                                        ($plan['show_whatsapp'] ?? 0) == 1 &&
                                        ($plan['email_marketing_access'] ?? 0) == 1 &&
                                        ($plan['allow_video_upload'] ?? 0) == 1 &&
                                        ($plan['allow_pdf_upload'] ?? 0) == 1 &&
                                        ($plan['allow_announcements'] ?? 0) == 1
                                    ): ?>
                                        <li class="flex items-center">
                                            <i class="fas fa-check text-green-500 mr-3 flex-shrink-0 text-sm"></i>
                                            <span class="text-gray-600 text-sm italic">All premium features included!</span>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>

                            <!-- Button removed per request -->
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Fallback when no plans are available -->
                <div class="col-span-full bg-yellow-50 border border-yellow-200 rounded-2xl p-8 text-center">
                    <i class="fas fa-info-circle text-yellow-500 text-4xl mb-4"></i>
                    <h3 class="text-xl font-semibold text-yellow-800 mb-2">Subscription Plans Coming Soon</h3>
                    <p class="text-yellow-700">We are currently setting up our subscription plans. Please check back soon or contact us for early access!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<!-- Payment Methods Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-gray-900 mb-12">Secure Payment Methods</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-2xl mx-auto">
             <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition text-center">
                <img
                    src="<?= base_url('uploads/slider/airtel-money.png') ?>"
                    alt="Airtel Money"
                    class="w-19 h-19 mx-auto mb-4 object-contain"
                >
                <h3 class="text-xl font-bold text-gray-900 mb-2">Airtel Money</h3>
            </div>

            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition text-center">
                <img
                    src="<?= base_url('uploads/slider/mpamba.png') ?>"
                    alt="Mpamba"
                    class="w-19 h-19 mx-auto mb-4 object-contain"
                >
                <h3 class="text-xl font-bold text-gray-900 mb-2">Mpamba</h3>
            </div>

        </div>
        <p class="text-gray-600 mt-12 max-w-2xl mx-auto">
            All transactions are processed safely and instantly.
        </p>
    </div>
</section>

<!-- CTA Section -->
<section class="overflow-hidden">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-0 items-stretch">
        <!-- Left Side - Image -->
        <div class="hidden lg:block relative bg-cover bg-center h-full min-h-[350px]" style="background-image: url('<?= base_url('uploads/slider/pexels-katerina-holmes-5905718.jpg') ?>'); background-position: center; background-size: cover;">
            <div class="absolute inset-0 bg-gradient-to-r from-primary/10 to-transparent"></div>
        </div>

        <!-- Right Side - Content -->
        <div class="bg-gradient-to-r from-primary to-red-600 text-white flex items-center px-8 sm:px-12 lg:px-16 py-12">
            <div class="w-full">
                <h2 class="text-3xl lg:text-4xl font-extrabold mb-4 leading-tight">Ready to Start Earning?</h2>
                <p class="text-lg text-red-100 mb-8 leading-relaxed">Join over 200 verified tutors already earning on TutorConnect Malawi.</p>

                <div class="space-y-3 lg:space-y-0 lg:flex lg:gap-4">
                    <a href="<?= site_url('register') ?>" class="block px-6 py-3 bg-white text-primary font-bold rounded-lg hover:bg-red-50 transition text-center lg:inline-block text-sm">
                        <i class="fas fa-user-plus mr-2"></i>Register Now
                    </a>
                    <a href="<?= site_url('how-it-works') ?>" class="block px-6 py-3 bg-red-700 hover:bg-red-800 text-white font-bold rounded-lg transition border border-red-600 text-center lg:inline-block text-sm">
                        <i class="fas fa-info-circle mr-2"></i>Learn More
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function selectPlan(planId, planName, planPrice) {
        const confirmed = confirm(`You've selected the ${planName} plan (${planPrice}/month).\n\nStart building your tutoring business today!\n\nWould you like to proceed to registration?`);

        if (confirmed) {
            window.location.href = '<?= site_url('register') ?>';
        }
    }
</script>

<?= $this->endSection() ?>
