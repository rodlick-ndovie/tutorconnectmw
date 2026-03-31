<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="bg-gradient-to-r from-primary to-orange-600 text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-5xl md:text-6xl font-extrabold mb-4">How It Works</h1>
        <p class="text-xl text-red-100 max-w-3xl mx-auto">Simple steps to connect with qualified tutors or start earning as a tutor</p>
    </div>
</section>

<!-- For Students Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">For Students & Parents</h2>
            <p class="text-xl text-gray-600">Find your perfect tutor in 3 simple steps - no registration required!</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Step 1 -->
            <div class="text-center">
                <div class="flex items-center justify-center h-16 w-16 rounded-full bg-gradient-to-br from-primary to-orange-600 text-white mx-auto mb-6">
                    <span class="text-3xl font-bold">1</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Browse Tutors</h3>
                <p class="text-gray-600">No registration needed! Browse verified tutors by subject, curriculum, location, and reviews.</p>
            </div>

            <!-- Step 2 -->
            <div class="text-center">
                <div class="flex items-center justify-center h-16 w-16 rounded-full bg-gradient-to-br from-primary to-orange-600 text-white mx-auto mb-6">
                    <span class="text-3xl font-bold">2</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Contact Tutor</h3>
                <p class="text-gray-600">Send a direct message to your preferred tutor to arrange lessons and discuss your learning goals.</p>
            </div>

            <!-- Step 3 -->
            <div class="text-center">
                <div class="flex items-center justify-center h-16 w-16 rounded-full bg-gradient-to-br from-primary to-orange-600 text-white mx-auto mb-6">
                    <span class="text-3xl font-bold">3</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Start Learning</h3>
                <p class="text-gray-600">Begin your personalized tutoring sessions at times that work for you. Arrange payment directly with your tutor.</p>
            </div>
        </div>
    </div>
</section>

<!-- For Tutors Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">For Tutors</h2>
            <p class="text-xl text-gray-600">Start earning and help students succeed</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Step 1 -->
            <div class="text-center">
                <div class="flex items-center justify-center h-16 w-16 rounded-full bg-gradient-to-br from-blue-600 to-indigo-600 text-white mx-auto mb-6">
                    <span class="text-3xl font-bold">1</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Register Profile</h3>
                <p class="text-gray-600">Complete your tutor profile with qualifications, subjects, and rates.</p>
            </div>

            <!-- Step 2 -->
            <div class="text-center">
                <div class="flex items-center justify-center h-16 w-16 rounded-full bg-gradient-to-br from-blue-600 to-indigo-600 text-white mx-auto mb-6">
                    <span class="text-3xl font-bold">2</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Get Verified</h3>
                <p class="text-gray-600">Submit documents for verification to build trust with students.</p>
            </div>

            <!-- Step 3 -->
            <div class="text-center">
                <div class="flex items-center justify-center h-16 w-16 rounded-full bg-gradient-to-br from-blue-600 to-indigo-600 text-white mx-auto mb-6">
                    <span class="text-3xl font-bold">3</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Receive Inquiries</h3>
                <p class="text-gray-600">Start receiving messages from students interested in your services.</p>
            </div>

            <!-- Step 4 -->
            <div class="text-center">
                <div class="flex items-center justify-center h-16 w-16 rounded-full bg-gradient-to-br from-blue-600 to-indigo-600 text-white mx-auto mb-6">
                    <span class="text-3xl font-bold">4</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Start Earning</h3>
                <p class="text-gray-600">Schedule lessons and earn money directly from your students.</p>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose TutorConnect -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Why Choose TutorConnect?</h2>
            <p class="text-xl text-gray-600">Benefits that make us the best choice</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Benefit 1 -->
            <div class="rounded-2xl p-8 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-center h-14 w-14 rounded-xl bg-gradient-to-br from-primary to-orange-600 text-white mx-auto mb-6">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3 text-center">Verified Tutors</h3>
                <p class="text-gray-600 text-center">All tutors are verified and qualified to ensure quality education.</p>
            </div>

            <!-- Benefit 2 -->
            <div class="rounded-2xl p-8 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-center h-14 w-14 rounded-xl bg-gradient-to-br from-primary to-orange-600 text-white mx-auto mb-6">
                    <i class="fas fa-shield-alt text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3 text-center">Safe & Secure</h3>
                <p class="text-gray-600 text-center">Secure communication and direct payments with built-in protections.</p>
            </div>

            <!-- Benefit 3 -->
            <div class="rounded-2xl p-8 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-center h-14 w-14 rounded-xl bg-gradient-to-br from-primary to-orange-600 text-white mx-auto mb-6">
                    <i class="fas fa-user-tie text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3 text-center">Professional Support</h3>
                <p class="text-gray-600 text-center">Dedicated support team to help you succeed on the platform.</p>
            </div>

            <!-- Benefit 4 -->
            <div class="rounded-2xl p-8 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-center h-14 w-14 rounded-xl bg-gradient-to-br from-primary to-orange-600 text-white mx-auto mb-6">
                    <i class="fas fa-globe text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3 text-center">Nationwide Coverage</h3>
                <p class="text-gray-600 text-center">Connect with students and tutors across all of Malawi.</p>
            </div>

            <!-- Benefit 5 -->
            <div class="rounded-2xl p-8 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-center h-14 w-14 rounded-xl bg-gradient-to-br from-primary to-orange-600 text-white mx-auto mb-6">
                    <i class="fas fa-star text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3 text-center">Quality Assurance</h3>
                <p class="text-gray-600 text-center">Reviews and ratings ensure accountability and continuous improvement.</p>
            </div>

            <!-- Benefit 6 -->
            <div class="rounded-2xl p-8 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-center h-14 w-14 rounded-xl bg-gradient-to-br from-primary to-orange-600 text-white mx-auto mb-6">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3 text-center">Flexible Scheduling</h3>
                <p class="text-gray-600 text-center">Choose your own schedule and learn/teach at your convenience.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="overflow-hidden">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-0 items-stretch">
        <!-- Left Side - Image -->
        <div class="hidden lg:block relative bg-cover bg-center h-full min-h-[350px]" style="background-image: url('<?= base_url('uploads/slider/pexels-vlada-karpovich-7368295.jpg') ?>'); background-position: center; background-size: cover;">
            <div class="absolute inset-0 bg-gradient-to-r from-primary/10 to-transparent"></div>
        </div>

        <!-- Right Side - Content -->
        <div class="bg-gradient-to-r from-primary to-orange-600 text-white flex items-center px-8 sm:px-12 lg:px-16 py-12">
            <div class="w-full">
                <h2 class="text-3xl lg:text-4xl font-extrabold mb-4 leading-tight">Ready to Get Started?</h2>
                <p class="text-lg text-red-100 mb-8 leading-relaxed">Join thousands of students and tutors on TutorConnect Malawi.</p>

                <div class="space-y-3 lg:space-y-0 lg:flex lg:gap-4">
                    <a href="<?= site_url('register') ?>" class="block px-6 py-3 bg-white text-primary font-bold rounded-lg hover:bg-red-50 transition text-center lg:inline-block text-sm">
                        <i class="fas fa-user-plus mr-2"></i>Become a Tutor
                    </a>
                    <a href="<?= site_url('find-tutors') ?>" class="block px-6 py-3 bg-red-700 hover:bg-red-800 text-white font-bold rounded-lg transition border border-red-600 text-center lg:inline-block text-sm">
                        <i class="fas fa-search mr-2"></i>Find a Tutor
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
