<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="bg-gradient-to-r from-primary to-red-600 text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-5xl md:text-6xl font-extrabold mb-4">About TutorConnect Malawi</h1>
        <p class="text-xl text-red-100 max-w-3xl mx-auto">Connecting educators and learners for a brighter, more equitable future</p>
    </div>
</section>

<!-- About Us Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-2xl shadow-xl p-12 mb-12">
                <div class="flex items-start mb-8">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-16 w-16 rounded-xl bg-gradient-to-br from-primary to-red-600">
                            <i class="fas fa-graduation-cap text-2xl text-white"></i>
                        </div>
                    </div>
                    <div class="ml-6">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">Who We Are</h2>
                        <p class="text-lg text-gray-700 mb-4 leading-relaxed">
                            TutorConnect Malawi is a digital platform that connects teachers with parents and learners in a trusted, transparent, and professional way. We enable teachers to create professional profiles, set their own fees and availability, and connect directly with families seeking academic support.
                        </p>
                        <p class="text-lg text-gray-700 mb-4 leading-relaxed">
                            The platform builds credibility through reviews, increases visibility through featured listings, and keeps engagements fair by allowing payments to happen directly between teachers and parents, with no commissions charged.
                        </p>
                        <p class="text-lg text-gray-700 leading-relaxed">
                            TutorConnect Malawi is a product of <span class="font-semibold">Visual Space Consulting</span>, developed to make teaching opportunities more visible, accessible, and sustainable across Malawi.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission and Vision Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <!-- Mission -->
            <div class="rounded-2xl p-10 hover:shadow-lg transition-shadow">
                <div class="flex items-center mb-6">
                    <div class="flex items-center justify-center h-14 w-14 rounded-xl bg-blue-600">
                        <i class="fas fa-target text-xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 ml-4">Our Mission</h3>
                </div>
                <p class="text-lg text-gray-700 leading-relaxed">
                    To make it easy for parents and learners across Malawi to find qualified, trusted teachers, while enabling teachers to access fair, visible, and flexible opportunities to offer their services.
                </p>
            </div>

            <!-- Vision -->
            <div class="rounded-2xl p-10 hover:shadow-lg transition-shadow">
                <div class="flex items-center mb-6">
                    <div class="flex items-center justify-center h-14 w-14 rounded-xl bg-purple-600">
                        <i class="fas fa-eye text-xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 ml-4">Our Vision</h3>
                </div>
                <p class="text-lg text-gray-700 leading-relaxed">
                    A Malawi where every learner can easily connect with the right teacher, and where teaching skills are valued, visible, and accessible regardless of location.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Our Core Values</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">What drives us every day</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <div class="rounded-xl p-8 hover:shadow-lg transition-shadow text-center">
                <div class="mb-4 flex justify-center">
                    <i class="fas fa-handshake text-4xl text-primary"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Trust</h3>
                <p class="text-gray-600">Building credible connections through verified profiles, transparent reviews, and secure transactions.</p>
            </div>

            <div class="rounded-xl p-8 hover:shadow-lg transition-shadow text-center">
                <div class="mb-4 flex justify-center">
                    <i class="fas fa-lightbulb text-4xl text-primary"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Accessibility</h3>
                <p class="text-gray-600">Making quality education and teaching opportunities accessible to everyone, regardless of location.</p>
            </div>

            <div class="rounded-xl p-8 hover:shadow-lg transition-shadow text-center">
                <div class="mb-4 flex justify-center">
                    <i class="fas fa-balance-scale text-4xl text-primary"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Fairness</h3>
                <p class="text-gray-600">Ensuring fair compensation for teachers and affordable access to quality tutoring for families.</p>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
