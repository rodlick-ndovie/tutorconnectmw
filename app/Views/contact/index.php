<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="bg-gradient-to-r from-primary to-red-600 text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-5xl md:text-6xl font-extrabold mb-4">Get In Touch</h1>
        <p class="text-xl text-red-100 max-w-3xl mx-auto">Have questions? We'd love to hear from you. Reach out and let's start a conversation.</p>
    </div>
</section>

<!-- Contact Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Left Side - Contact Information -->
            <div class="space-y-6">
                <!-- Email -->
                <div class="rounded-2xl p-8 hover:shadow-lg transition-shadow">
                    <div class="flex items-start">
                        <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-gradient-to-br from-primary to-red-600 text-white flex-shrink-0">
                            <i class="fas fa-envelope text-lg"></i>
                        </div>
                        <div class="ml-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Email</h3>
                            <a href="mailto:info@tutorconnectmw.com" class="text-gray-600 hover:text-primary font-medium transition">
                                info@tutorconnectmw.com
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Phone -->
                <div class="rounded-2xl p-8 hover:shadow-lg transition-shadow">
                    <div class="flex items-start">
                        <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-gradient-to-br from-primary to-red-600 text-white flex-shrink-0">
                            <i class="fas fa-phone text-lg"></i>
                        </div>
                        <div class="ml-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Phone</h3>
                            <div class="space-y-1">
                                <a href="tel:+265992313978" class="text-gray-600 hover:text-primary font-medium transition block">
                                    +265 992 313 978
                                </a>
                                <a href="tel:+265883790001" class="text-gray-600 hover:text-primary font-medium transition block">
                                    +265 883 790 001
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address -->
                <div class="rounded-2xl p-8 hover:shadow-lg transition-shadow">
                    <div class="flex items-start">
                        <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-gradient-to-br from-primary to-red-600 text-white flex-shrink-0">
                            <i class="fas fa-map-marker-alt text-lg"></i>
                        </div>
                        <div class="ml-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Address</h3>
                            <p class="text-gray-600 leading-relaxed text-sm">
                                C/O Visual Space Consulting<br>
                                Bingu National Stadium<br>
                                E16, Gulliver<br>
                                Lilongwe, Malawi
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Hours -->
                <div class="rounded-2xl p-8 hover:shadow-lg transition-shadow">
                    <div class="flex items-start">
                        <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-gradient-to-br from-primary to-red-600 text-white flex-shrink-0">
                            <i class="fas fa-clock text-lg"></i>
                        </div>
                        <div class="ml-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Hours</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                Mon - Fri: 8AM - 5PM<br>
                                Saturday: 9AM - 1PM<br>
                                Sunday: Closed
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Contact Form -->
            <div>
                <div class="bg-white rounded-2xl shadow-xl p-12 border-2 border-primary">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Send us a Message</h2>
                    <p class="text-gray-600 mb-8">Fill out the form below and we'll get back to you shortly.</p>

                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="mb-8 p-4 bg-green-50 border border-green-200 rounded-lg flex items-start">
                            <i class="fas fa-check-circle text-green-600 text-lg mr-3 mt-0.5 flex-shrink-0"></i>
                            <p class="text-green-800 font-medium"><?= esc(session()->getFlashdata('success')) ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="mb-8 p-4 bg-red-50 border border-red-200 rounded-lg flex items-start">
                            <i class="fas fa-exclamation-circle text-red-600 text-lg mr-3 mt-0.5 flex-shrink-0"></i>
                            <div>
                                <p class="text-red-800 font-medium mb-1"><?= esc(session()->getFlashdata('error')) ?></p>
                                <?php if ($errors = session()->getFlashdata('validation_errors')): ?>
                                    <ul class="list-disc list-inside text-red-700 text-sm">
                                        <?php foreach ($errors as $err): ?>
                                            <li><?= esc($err) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form action="<?= site_url('contact/send') ?>" method="POST" id="contactForm" class="space-y-5">
                        <?= csrf_field() ?>
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-900 mb-3">Full Name *</label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition"
                                placeholder="Your name"
                            >
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-900 mb-3">Email Address *</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition"
                                placeholder="your@email.com"
                            >
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-gray-900 mb-3">Phone Number <span class="text-gray-400 font-normal">(Optional)</span></label>
                            <input
                                type="tel"
                                id="phone"
                                name="phone"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition"
                                placeholder="+265 xxx xxx xxx"
                            >
                        </div>

                        <!-- Subject -->
                        <div>
                            <label for="subject" class="block text-sm font-semibold text-gray-900 mb-3">Subject *</label>
                            <input
                                type="text"
                                id="subject"
                                name="subject"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition"
                                placeholder="What is this about?"
                            >
                        </div>

                        <!-- Message -->
                        <div>
                            <label for="message" class="block text-sm font-semibold text-gray-900 mb-3">Message *</label>
                            <textarea
                                id="message"
                                name="message"
                                required
                                rows="5"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition resize-none"
                                placeholder="Tell us more..."
                            ></textarea>
                            <p class="text-xs text-gray-500 mt-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                Minimum 10 characters required
                            </p>
                        </div>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            id="submitBtn"
                            class="w-full px-6 py-3 bg-gradient-to-r from-primary to-red-600 text-white font-bold rounded-lg hover:shadow-lg transition"
                        >
                            <span id="submitText">
                                <i class="fas fa-paper-plane mr-2"></i>Send Message
                            </span>
                            <span id="submitSpinner" class="hidden">
                                <i class="fas fa-spinner fa-spin mr-2"></i>Sending...
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Frequently Asked Questions</h2>
            <p class="text-xl text-gray-600">Find answers to common questions</p>
        </div>

        <div class="space-y-4">
            <!-- FAQ Item 1 -->
            <div class="border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-shadow">
                <button class="w-full flex items-center justify-between px-8 py-6 text-left hover:bg-gray-50 transition" onclick="toggleFAQ(this)">
                    <h3 class="text-lg font-bold text-gray-900">How do I become a tutor on TutorConnect?</h3>
                    <i class="fas fa-chevron-down text-primary flex-shrink-0 transition-transform"></i>
                </button>
                <p class="text-gray-600 px-8 pb-6 hidden leading-relaxed">
                    Visit our registration page and complete the tutor sign-up form. Submit your qualifications, teaching experience, and necessary documents for verification. Once approved, you can create your profile and start connecting with students.
                </p>
            </div>

            <!-- FAQ Item 2 -->
            <div class="border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-shadow">
                <button class="w-full flex items-center justify-between px-8 py-6 text-left hover:bg-gray-50 transition" onclick="toggleFAQ(this)">
                    <h3 class="text-lg font-bold text-gray-900">What fees does TutorConnect charge?</h3>
                    <i class="fas fa-chevron-down text-primary flex-shrink-0 transition-transform"></i>
                </button>
                <p class="text-gray-600 px-8 pb-6 hidden leading-relaxed">
                    TutorConnect charges no commission on tutoring fees. Payments go directly between students and tutors. We only charge for optional premium features like featured listings and profile boosts.
                </p>
            </div>

            <!-- FAQ Item 3 -->
            <div class="border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-shadow">
                <button class="w-full flex items-center justify-between px-8 py-6 text-left hover:bg-gray-50 transition" onclick="toggleFAQ(this)">
                    <h3 class="text-lg font-bold text-gray-900">How do I find a tutor?</h3>
                    <i class="fas fa-chevron-down text-primary flex-shrink-0 transition-transform"></i>
                </button>
                <p class="text-gray-600 px-8 pb-6 hidden leading-relaxed">
                    Use our search filters to find tutors by district, subject, curriculum, and ratings. View their profiles, read reviews from other students, and contact them directly through the platform.
                </p>
            </div>

            <!-- FAQ Item 4 -->
            <div class="border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-shadow">
                <button class="w-full flex items-center justify-between px-8 py-6 text-left hover:bg-gray-50 transition" onclick="toggleFAQ(this)">
                    <h3 class="text-lg font-bold text-gray-900">Is TutorConnect verified and safe?</h3>
                    <i class="fas fa-chevron-down text-primary flex-shrink-0 transition-transform"></i>
                </button>
                <p class="text-gray-600 px-8 pb-6 hidden leading-relaxed">
                    Yes. All tutors go through a verification process including document checks. The platform includes review systems, secure communication, and user ratings to ensure trust and safety.
                </p>
            </div>
        </div>
    </div>
</section>

<script>
    function toggleFAQ(button) {
        const answer = button.nextElementSibling;
        const icon = button.querySelector('i');

        answer.classList.toggle('hidden');
        icon.style.transform = answer.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
    }

    // Contact form submission - show loading spinner
    document.getElementById('contactForm')?.addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submitBtn');
        const submitText = document.getElementById('submitText');
        const submitSpinner = document.getElementById('submitSpinner');

        submitText.classList.add('hidden');
        submitSpinner.classList.remove('hidden');
        submitBtn.disabled = true;
    });
</script>

<?= $this->endSection() ?>
