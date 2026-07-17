<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<div class="bg-gradient-to-r from-primary to-red-600 py-12 md:py-20">
    <div class="max-w-4xl mx-auto px-4">
        <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-4">Refund Policy</h1>
        <p class="text-red-100 text-lg">Last updated: January 1, 2026</p>
    </div>
</div>

<!-- Content Section -->
<div class="max-w-4xl mx-auto px-4 py-12 md:py-16">
    <!-- Introduction -->
    <section class="mb-12">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">Refund Policy Overview</h2>
        <p class="text-gray-700 leading-relaxed mb-4">
            At TutorConnect Malawi, we aim to ensure customer satisfaction with our tutoring services. This Refund Policy outlines the conditions under which refunds may be issued and the process for requesting them.
        </p>
        <p class="text-gray-700 leading-relaxed">
            Please read this policy carefully to understand your refund eligibility and rights.
        </p>
    </section>

    <!-- Refundable Services -->
    <section class="mb-12">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">1. Refundable Services</h2>
        <p class="text-gray-700 leading-relaxed mb-4">
            The following services are eligible for refunds under specific conditions:
        </p>
        <ul class="space-y-3 text-gray-700">
            <li class="flex gap-3">
                <span class="text-primary font-bold">•</span>
                <span><strong>Tutoring Sessions:</strong> Paid sessions that were not completed or delivered</span>
            </li>
            <li class="flex gap-3">
                <span class="text-primary font-bold">•</span>
                <span><strong>Subscription Plans:</strong> Monthly or annual tutoring subscriptions</span>
            </li>
            <li class="flex gap-3">
                <span class="text-primary font-bold">•</span>
                <span><strong>Packages:</strong> Pre-purchased session packages</span>
            </li>
            <li class="flex gap-3">
                <span class="text-primary font-bold">•</span>
                <span><strong>Course Materials:</strong> Paid educational resources</span>
            </li>
        </ul>
    </section>

    <!-- Non-Refundable Items -->
    <section class="mb-12">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">2. Non-Refundable Items</h2>
        <p class="text-gray-700 leading-relaxed mb-4">
            The following are generally non-refundable:
        </p>
        <ul class="space-y-3 text-gray-700">
            <li class="flex gap-3">
                <span class="text-primary font-bold">•</span>
                <span>Sessions completed by the tutor (unless tutor quality issues are substantiated)</span>
            </li>
            <li class="flex gap-3">
                <span class="text-primary font-bold">•</span>
                <span>Sessions where the student did not attend or cancelled within 24 hours</span>
            </li>
            <li class="flex gap-3">
                <span class="text-primary font-bold">•</span>
                <span>Completed digital materials or resources that have been accessed</span>
            </li>
            <li class="flex gap-3">
                <span class="text-primary font-bold">•</span>
                <span>Promotional credits or discounts</span>
            </li>
            <li class="flex gap-3">
                <span class="text-primary font-bold">•</span>
                <span>Referral bonuses or rewards already redeemed</span>
            </li>
        </ul>
    </section>

    <!-- Refund Eligibility -->
    <section class="mb-12">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">3. Refund Eligibility</h2>

        <div class="mb-8">
            <h3 class="text-xl font-semibold text-gray-800 mb-3">Sessions Due to Service Issues</h3>
            <p class="text-gray-700 leading-relaxed mb-4">
                You may request a refund if:
            </p>
            <ul class="space-y-2 text-gray-700">
                <li class="flex gap-3">
                    <span class="text-primary font-bold">•</span>
                    <span>The tutor failed to appear for a scheduled session</span>
                </li>
                <li class="flex gap-3">
                    <span class="text-primary font-bold">•</span>
                    <span>Technical issues prevented service delivery (documented)</span>
                </li>
                <li class="flex gap-3">
                    <span class="text-primary font-bold">•</span>
                    <span>The service quality was significantly below standards</span>
                </li>
            </ul>
        </div>

        <div>
            <h3 class="text-xl font-semibold text-gray-800 mb-3">Cancellation Refunds</h3>
            <p class="text-gray-700 leading-relaxed mb-4">
                Refunds for cancellations depend on timing:
            </p>
            <ul class="space-y-2 text-gray-700">
                <li class="flex gap-3">
                    <span class="text-primary font-bold">•</span>
                    <span><strong>48+ hours before session:</strong> Full refund minus platform fee (5%)</span>
                </li>
                <li class="flex gap-3">
                    <span class="text-primary font-bold">•</span>
                    <span><strong>24-48 hours before:</strong> 75% refund minus platform fee</span>
                </li>
                <li class="flex gap-3">
                    <span class="text-primary font-bold">•</span>
                    <span><strong>Less than 24 hours:</strong> No refund available</span>
                </li>
            </ul>
        </div>
    </section>

    <!-- Refund Request Process -->
    <section class="mb-12">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">4. How to Request a Refund</h2>
        <p class="text-gray-700 leading-relaxed mb-6">
            To request a refund, follow these steps:
        </p>

        <div class="space-y-4">
            <div class="border-l-4 border-primary pl-4">
                <h3 class="font-semibold text-gray-800 mb-2">Step 1: Contact Support</h3>
                <p class="text-gray-700">Email refunds@tutorconnectmw.com with your account details and transaction ID</p>
            </div>
            <div class="border-l-4 border-primary pl-4">
                <h3 class="font-semibold text-gray-800 mb-2">Step 2: Provide Documentation</h3>
                <p class="text-gray-700">Include proof of payment, session details, and reason for refund request</p>
            </div>
            <div class="border-l-4 border-primary pl-4">
                <h3 class="font-semibold text-gray-800 mb-2">Step 3: Review</h3>
                <p class="text-gray-700">Our team will review your request within 5-7 business days</p>
            </div>
            <div class="border-l-4 border-primary pl-4">
                <h3 class="font-semibold text-gray-800 mb-2">Step 4: Processing</h3>
                <p class="text-gray-700">Approved refunds are processed within 7-14 business days to your original payment method</p>
            </div>
        </div>
    </section>

    <!-- Refund Timeline -->
    <section class="mb-12">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">5. Refund Timeline</h2>
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <ul class="space-y-3 text-gray-700">
                <li class="flex gap-3">
                    <span class="text-primary font-bold">•</span>
                    <span><strong>Refund Decision:</strong> 5-7 business days after submission</span>
                </li>
                <li class="flex gap-3">
                    <span class="text-primary font-bold">•</span>
                    <span><strong>Processing:</strong> 7-14 business days once approved</span>
                </li>
                <li class="flex gap-3">
                    <span class="text-primary font-bold">•</span>
                    <span><strong>Bank Processing:</strong> Additional 2-5 days depending on your bank</span>
                </li>
                <li class="flex gap-3">
                    <span class="text-primary font-bold">•</span>
                    <span><strong>Total Expected:</strong> 14-26 business days</span>
                </li>
            </ul>
        </div>
    </section>

    <!-- Partial Refunds -->
    <section class="mb-12">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">6. Partial Session Refunds</h2>
        <p class="text-gray-700 leading-relaxed mb-4">
            For partial session completion:
        </p>
        <ul class="space-y-3 text-gray-700">
            <li class="flex gap-3">
                <span class="text-primary font-bold">•</span>
                <span>If the session was less than 25% completed: 100% refund minus fees</span>
            </li>
            <li class="flex gap-3">
                <span class="text-primary font-bold">•</span>
                <span>If the session was 25-50% completed: 75% refund minus fees</span>
            </li>
            <li class="flex gap-3">
                <span class="text-primary font-bold">•</span>
                <span>If the session was 50%+ completed: 25% refund minus fees</span>
            </li>
            <li class="flex gap-3">
                <span class="text-primary font-bold">•</span>
                <span>If 100% completed: No refund available</span>
            </li>
        </ul>
    </section>

    <!-- Subscription Refunds -->
    <section class="mb-12">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">7. Subscription Refunds</h2>
        <p class="text-gray-700 leading-relaxed mb-4">
            For monthly or annual subscriptions:
        </p>
        <ul class="space-y-3 text-gray-700">
            <li class="flex gap-3">
                <span class="text-primary font-bold">•</span>
                <span><strong>30-Day Trial:</strong> Full refund if cancelled within first 30 days</span>
            </li>
            <li class="flex gap-3">
                <span class="text-primary font-bold">•</span>
                <span><strong>Monthly Plans:</strong> Pro-rata refund for unused portion</span>
            </li>
            <li class="flex gap-3">
                <span class="text-primary font-bold">•</span>
                <span><strong>Annual Plans:</strong> 50% refund if cancelled before half usage</span>
            </li>
            <li class="flex gap-3">
                <span class="text-primary font-bold">•</span>
                <span>Cancellation must be submitted before the next billing cycle</span>
            </li>
        </ul>
    </section>

    <!-- Special Circumstances -->
    <section class="mb-12">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">8. Special Circumstances</h2>
        <p class="text-gray-700 leading-relaxed mb-4">
            In exceptional cases, we may approve refunds outside standard policy:
        </p>
        <ul class="space-y-3 text-gray-700">
            <li class="flex gap-3">
                <span class="text-primary font-bold">•</span>
                <span>Documented tutor misconduct or violations</span>
            </li>
            <li class="flex gap-3">
                <span class="text-primary font-bold">•</span>
                <span>Systemic platform failures affecting service delivery</span>
            </li>
            <li class="flex gap-3">
                <span class="text-primary font-bold">•</span>
                <span>Billing errors or duplicate charges</span>
            </li>
            <li class="flex gap-3">
                <span class="text-primary font-bold">•</span>
                <span>Account closure due to our error</span>
            </li>
        </ul>
        <p class="text-gray-700 leading-relaxed mt-4">
            Each case will be evaluated individually by our management team.
        </p>
    </section>

    <!-- Denied Refunds -->
    <section class="mb-12">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">9. Refund Denial</h2>
        <p class="text-gray-700 leading-relaxed mb-4">
            Refund requests may be denied if:
        </p>
        <ul class="space-y-3 text-gray-700">
            <li class="flex gap-3">
                <span class="text-primary font-bold">•</span>
                <span>Outside the refund eligibility window</span>
            </li>
            <li class="flex gap-3">
                <span class="text-primary font-bold">•</span>
                <span>Insufficient documentation provided</span>
            </li>
            <li class="flex gap-3">
                <span class="text-primary font-bold">•</span>
                <span>Evidence of dispute/chargeback filing</span>
            </li>
            <li class="flex gap-3">
                <span class="text-primary font-bold">•</span>
                <span>Account violates Terms of Service</span>
            </li>
            <li class="flex gap-3">
                <span class="text-primary font-bold">•</span>
                <span>Multiple refund requests from the same account</span>
            </li>
        </ul>
    </section>

    <!-- Contact for Refunds -->
    <section class="mb-12">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">10. Contact & Support</h2>
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
            <p class="text-gray-700 mb-3"><strong>Refund Requests:</strong> refunds@tutorconnectmw.com</p>
            <p class="text-gray-700 mb-3"><strong>Payment Issues:</strong> billing@tutorconnectmw.com</p>
            <p class="text-gray-700 mb-3"><strong>General Support:</strong> support@tutorconnectmw.com</p>
            <p class="text-gray-700"><strong>Response Time:</strong> 24-48 hours for acknowledgment, 5-7 days for decision</p>
        </div>
    </section>

    <!-- CTA -->
    <section class="mt-16 bg-gradient-to-r from-primary to-red-600 rounded-2xl shadow-lg p-8 md:p-12 text-white">
        <h2 class="text-2xl md:text-3xl font-bold mb-4">Need a Refund?</h2>
        <p class="text-red-100 mb-6">Our team is ready to assist you with your refund request. Contact us today.</p>
        <a href="mailto:refunds@tutorconnectmw.com" class="inline-block bg-white text-primary px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition transform hover:scale-105 duration-300">
            Request a Refund
        </a>
    </section>
</div>

<?= $this->endSection() ?>
