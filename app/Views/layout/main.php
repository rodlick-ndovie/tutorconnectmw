<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? site_setting('site_name', 'TutorConnect Malawi')) ?></title>
    <meta name="description" content="<?= esc($description ?? 'Connecting students with verified Teachers across Malawi for primary and secondary education.') ?>">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= current_url() ?>">
    <meta property="og:title" content="<?= !empty($tutor) ? esc(($tutor['user']['first_name'] ?? 'Unknown') . ' ' . ($tutor['user']['last_name'] ?? 'Tutor') . ' - Professional Tutor') : esc($title ?? site_setting('site_name', 'TutorConnect Malawi')) ?>">
    <meta property="og:description" content="<?= !empty($tutor) ? esc(substr($tutor['bio'] ?? 'Professional tutor dedicated to student success. I specialize in personalized learning experiences tailored to each student\'s unique needs and learning style.', 0, 160) . '...') : esc($description ?? 'Connecting students with verified Teachers across Malawi for primary and secondary education.') ?>">
    <meta property="og:image" content="<?= !empty($tutor['profile_picture']) ? base_url($tutor['profile_picture']) : base_url('uploads/profile_photos/default-profile.png') ?>">
    <meta property="og:site_name" content="<?= esc(site_setting('site_name', 'TutorConnect Malawi')) ?>">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?= current_url() ?>">
    <meta property="twitter:title" content="<?= !empty($tutor) ? esc(($tutor['user']['first_name'] ?? 'Unknown') . ' ' . ($tutor['user']['last_name'] ?? 'Tutor') . ' - Professional Tutor') : esc($title ?? site_setting('site_name', 'TutorConnect Malawi')) ?>">
    <meta property="twitter:description" content="<?= !empty($tutor) ? esc(substr($tutor['bio'] ?? 'Professional tutor dedicated to student success. I specialize in personalized learning experiences tailored to each student\'s unique needs and learning style.', 0, 160) . '...') : esc($description ?? 'Connecting students with verified Teachers across Malawi for primary and secondary education.') ?>">
    <meta property="twitter:image" content="<?= !empty($tutor['profile_picture']) ? base_url($tutor['profile_picture']) : base_url('uploads/profile_photos/default-profile.png') ?>">

    <!-- Additional SEO Meta Tags -->
    <meta name="author" content="UPrise Malawi">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="keywords" content="Malawi Teachers, private Teachers, online tutoring, home Teachers, exam preparation, primary education, secondary education, Cambridge curriculum, GCSE, MANEB">
    <link rel="canonical" href="<?= current_url() ?>">

    <!-- Structured Data for Organization -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "TutorConnect Malawi",
        "url": "<?= base_url() ?>",
        "logo": "<?= base_url('uploads/' . site_setting('site_logo', '')) ?>",
        "description": "Connecting students with verified Teachers across Malawi for primary and secondary education.",
        "foundingDate": "2024",
        "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "<?= site_setting('support_phone', '+265 992313978 / 883790001') ?>",
            "email": "<?= site_setting('contact_email', 'info@tutorconnectmw.com') ?>",
            "contactType": "customer service",
            "areaServed": "MW",
            "availableLanguage": "English"
        },
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "C/O Visual Space Consulting, Bingu National Stadium, E16",
            "addressLocality": "Gulliver",
            "addressRegion": "Lilongwe",
            "addressCountry": "Malawi"
        },
        "sameAs": [
            <?php
            $socialUrls = [];
            if (!empty(site_setting('social_facebook_url', ''))) $socialUrls[] = '"' . site_setting('social_facebook_url', '') . '"';
            if (!empty(site_setting('social_twitter_url', ''))) $socialUrls[] = '"' . site_setting('social_twitter_url', '') . '"';
            if (!empty(site_setting('social_instagram_url', ''))) $socialUrls[] = '"' . site_setting('social_instagram_url', '') . '"';
            echo implode(',', $socialUrls);
            ?>
        ],
        "knowsAbout": [
            "Education",
            "Tutoring",
            "Online Learning",
            "Academic Excellence",
            "Malawi Education System",
            "Cambridge Curriculum",
            "GCSE",
            "MANEB"
        ],
        "creator": {
            "@type": "Organization",
            "name": "UPrise Malawi",
            "url": "https://www.uprisemw.com",
            "description": "Digital solutions and web development company that developed TutorConnect Malawi platform"
        }
    }
    </script>

    <?php if (!empty($tutor)): ?>
    <!-- Structured Data for Tutor Profile -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "ProfilePage",
        "mainEntity": {
            "@type": "Person",
            "name": "<?= esc(($tutor['user']['first_name'] ?? 'Unknown') . ' ' . ($tutor['user']['last_name'] ?? 'Tutor')) ?>",
            "description": "<?= esc($tutor['bio'] ?? 'Professional tutor dedicated to student success') ?>",
            "image": "<?= !empty($tutor['profile_picture']) ? base_url($tutor['profile_picture']) : base_url('uploads/profile_photos/default-profile.png') ?>",
            "jobTitle": "Professional Tutor",
            "worksFor": {
                "@type": "Organization",
                "name": "<?= !empty($tutor['is_employed']) && $tutor['is_employed'] == 1 ? esc($tutor['school_name'] ?? 'School-employed') : 'Self-employed' ?>"
            },
            "address": {
                "@type": "PostalAddress",
                "addressLocality": "<?= esc($tutor['district'] ?? 'Malawi') ?>",
                "addressRegion": "<?= esc($tutor['district'] ?? '') ?>",
                "addressCountry": "Malawi"
            },
            "knowsAbout": [
                "Education",
                "Tutoring",
                "Academic Excellence"
            ],
            "hasCredential": {
                "@type": "EducationalOccupationalCredential",
                "name": "Teaching Qualification",
                "description": "Professional teaching certification and experience"
            },
            "experienceInPlaceOfEducation": true,
            "yearsInOperation": "<?= intval($tutor['experience_years'] ?? 0) ?>",
            "aggregateRating": {
                "@type": "AggregateRating",
                "ratingValue": "<?= number_format($tutor['rating'] ?? 0, 1) ?>",
                "reviewCount": "<?= intval($tutor['review_count'] ?? 0) ?>",
                "bestRating": "5",
                "worstRating": "1"
            },
            "contactPoint": [
                <?php
                $contactPoints = [];
                if (!empty($tutor['phone'])) {
                    $contactPoints[] = '{
                        "@type": "ContactPoint",
                        "telephone": "' . esc($tutor['phone']) . '",
                        "contactType": "customer service",
                        "areaServed": "MW"
                    }';
                }
                if (!empty($tutor['email'])) {
                    $contactPoints[] = '{
                        "@type": "ContactPoint",
                        "email": "' . esc($tutor['email']) . '",
                        "contactType": "customer service",
                        "areaServed": "MW"
                    }';
                }
                if (!empty($tutor['whatsapp_number'])) {
                    $contactPoints[] = '{
                        "@type": "ContactPoint",
                        "telephone": "' . esc($tutor['whatsapp_number']) . '",
                        "contactType": "customer service",
                        "areaServed": "MW",
                        "description": "WhatsApp contact"
                    }';
                }
                echo implode(',', $contactPoints);
                ?>
            ],
            "teaches": "<?= esc(implode(', ', array_column($tutor_subjects ?? [], 'subject_name') ?: ['Various Subjects'])) ?>",
            "serviceType": [
                "Private Tutoring",
                "Academic Support",
                "Exam Preparation",
                "Homework Help"
            ],
            "areaServed": {
                "@type": "Country",
                "name": "Malawi"
            },
            "priceRange": "$$",
            "paymentAccepted": [
                "Cash",
                "Mobile Money",
                "Bank Transfer"
            ],
            "currenciesAccepted": "MWK"
        },
        "breadcrumb": {
            "@type": "BreadcrumbList",
            "itemListElement": [
                {
                    "@type": "ListItem",
                    "position": 1,
                    "name": "Home",
                    "item": "<?= base_url() ?>"
                },
                {
                    "@type": "ListItem",
                    "position": 2,
                    "name": "Find Teachers",
                    "item": "<?= base_url('find-tutors') ?>"
                },
                {
                    "@type": "ListItem",
                    "position": 3,
                    "name": "<?= esc(($tutor['user']['first_name'] ?? 'Unknown') . ' ' . ($tutor['user']['last_name'] ?? 'Tutor')) ?>",
                    "item": "<?= current_url() ?>"
                }
            ]
        }
    }
    </script>
    <?php endif; ?>

    <?php $siteFavicon = site_setting('site_favicon', ''); ?>
    <?php if (!empty($siteFavicon)): ?>
        <link rel="icon" href="<?= esc(base_url('uploads/' . $siteFavicon)) ?>">
    <?php endif; ?>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Text:wght@400;600&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#E55C0D',
                        secondary: '#2C3E50',
                        accent: '#34495E',
                        neutral: '#ECF0F1'
                    },
                    fontFamily: {
                        'sans': ['Inter', 'sans-serif'],
                        'serif': ['Crimson Text', 'serif']
                    }
                }
            }
        }
    </script>

    <!-- Custom Styles -->
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #2C3E50 0%, #34495E 100%);
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .btn-primary {
            background: linear-gradient(135deg, #E55C0D 0%, #c94609 100%);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 20px rgba(229, 92, 13, 0.3);
        }
        .animate-fade-in {
            animation: fadeIn 0.6s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-neutral font-sans">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <?php $siteLogo = site_setting('site_logo', ''); ?>
                        <?php if (!empty($siteLogo)): ?>
                            <img src="<?= esc(base_url('uploads/' . $siteLogo)) ?>" alt="<?= esc(site_setting('site_name', 'TutorConnect Malawi')) ?>" class="h-16 w-auto" />
                        <?php else: ?>
                            <i class="fas fa-graduation-cap text-2xl text-primary"></i>
                            <span class="ml-2 text-xl font-bold text-secondary"><?= esc(site_setting('site_name', 'TutorConnect Malawi')) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex md:items-center md:space-x-8">
                    <a href="<?= site_url('/') ?>" class="text-secondary hover:text-primary px-3 py-2 font-medium transition-colors">Home</a>
                    <a href="<?= site_url('resources') ?>" class="text-secondary hover:text-primary px-3 py-2 font-medium transition-colors">Resources</a>
                    <a href="<?= site_url('teach-in-japan') ?>" class="text-secondary hover:text-primary px-3 py-2 font-medium transition-colors">Teach in Japan</a>
                    <!--<a href="<?= site_url('how-it-works') ?>" class="text-secondary hover:text-primary px-3 py-2 font-medium transition-colors">How It Works</a>-->
                    <a href="<?= site_url('pricing') ?>" class="text-secondary hover:text-primary px-3 py-2 font-medium transition-colors">Pricing</a>
                    <a href="<?= site_url('notice') ?>" class="text-secondary hover:text-primary px-3 py-2 font-medium transition-colors">Notices</a>
                    <a href="<?= site_url('find-tutors') ?>" class="text-secondary hover:text-primary px-3 py-2 font-medium transition-colors">Find Teachers</a>

                    <a href="<?= site_url('login') ?>" class="text-primary hover:text-red-600 px-3 py-2 font-medium flex items-center">
                        <i class="fas fa-sign-in-alt mr-1"></i> Login
                    </a>
                </div>

                <!-- Mobile Menu Toggle Button -->
                <div class="md:hidden">
                    <button onclick="toggleOffcanvas()" class="text-secondary hover:text-primary transition-colors p-2">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Off-Canvas Mobile Menu (Full Page) -->
    <div id="offcanvasBackdrop" class="hidden fixed inset-0 bg-black bg-opacity-70 z-40 md:hidden transition-opacity duration-300" onclick="toggleOffcanvas()"></div>

    <div id="offcanvasMenu" class="fixed inset-0 bg-white transform -translate-x-full transition-transform duration-300 z-50 md:hidden overflow-y-auto">
        <!-- Off-Canvas Header -->
        <div class="bg-white text-secondary p-6 flex items-center justify-between sticky top-0 border-b border-slate-200 shadow-sm">
            <div class="flex items-center">
                <?php $siteLogo = site_setting('site_logo', ''); ?>
                <?php if (!empty($siteLogo)): ?>
                    <img src="<?= esc(base_url('uploads/' . $siteLogo)) ?>" alt="<?= esc(site_setting('site_name', 'TutorConnect Malawi')) ?>" class="h-14 w-auto" />
                <?php else: ?>
                    <i class="fas fa-graduation-cap text-2xl"></i>
                    <span class="ml-2 text-lg font-bold"><?= esc(site_setting('site_name', 'TutorConnect Malawi')) ?></span>
                <?php endif; ?>
            </div>
            <button onclick="toggleOffcanvas()" class="text-secondary hover:bg-slate-100 p-2 rounded-lg transition">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <!-- Off-Canvas Menu Items -->
        <div class="flex flex-col items-center justify-center min-h-[calc(100vh-88px)] p-8 space-y-3">
            <a href="<?= site_url('/') ?>" onclick="toggleOffcanvas()" class="w-full max-w-xs px-4 py-2.5 text-secondary hover:bg-slate-50 hover:text-primary rounded-lg font-medium transition-all duration-200 flex items-center justify-center border border-slate-200">
                <i class="fas fa-home mr-2 text-sm text-slate-600"></i>
                <span class="text-sm">Home</span>
            </a>
            <a href="<?= site_url('resources') ?>" onclick="toggleOffcanvas()" class="w-full max-w-xs px-4 py-2.5 text-secondary hover:bg-slate-50 hover:text-primary rounded-lg font-medium transition-all duration-200 flex items-center justify-center border border-slate-200">
                <i class="fas fa-book mr-2 text-sm text-slate-600"></i>
                <span class="text-sm">Resources</span>
            </a>
            <a href="<?= site_url('teach-in-japan') ?>" onclick="toggleOffcanvas()" class="w-full max-w-xs px-4 py-2.5 text-secondary hover:bg-slate-50 hover:text-primary rounded-lg font-medium transition-all duration-200 flex items-center justify-center border border-slate-200">
                <i class="fas fa-plane-departure mr-2 text-sm text-slate-600"></i>
                <span class="text-sm">Teach in Japan</span>
            </a>
            <a href="<?= site_url('how-it-works') ?>" onclick="toggleOffcanvas()" class="w-full max-w-xs px-4 py-2.5 text-secondary hover:bg-slate-50 hover:text-primary rounded-lg font-medium transition-all duration-200 flex items-center justify-center border border-slate-200">
                <i class="fas fa-cogs mr-2 text-sm text-slate-600"></i>
                <span class="text-sm">How It Works</span>
            </a>
            <a href="<?= site_url('pricing') ?>" onclick="toggleOffcanvas()" class="w-full max-w-xs px-4 py-2.5 text-secondary hover:bg-slate-50 hover:text-primary rounded-lg font-medium transition-all duration-200 flex items-center justify-center border border-slate-200">
                <i class="fas fa-tag mr-2 text-sm text-slate-600"></i>
                <span class="text-sm">Pricing</span>
            </a>
            <a href="<?= site_url('notice') ?>" onclick="toggleOffcanvas()" class="w-full max-w-xs px-4 py-2.5 text-secondary hover:bg-slate-50 hover:text-primary rounded-lg font-medium transition-all duration-200 flex items-center justify-center border border-slate-200">
                <i class="fas fa-bullhorn mr-2 text-sm text-slate-600"></i>
                <span class="text-sm">Notices</span>
            </a>
            <a href="<?= site_url('find-tutors') ?>" onclick="toggleOffcanvas()" class="w-full max-w-xs px-4 py-2.5 text-secondary hover:bg-slate-50 hover:text-primary rounded-lg font-medium transition-all duration-200 flex items-center justify-center border border-slate-200">
                <i class="fas fa-search mr-2 text-sm text-slate-600"></i>
                <span class="text-sm">Find Teachers</span>
            </a>

            <!-- Divider -->
            <div class="w-full max-w-xs border-t border-slate-300 my-4"></div>

            <!-- Login Button -->
            <a href="<?= site_url('login') ?>" onclick="toggleOffcanvas()" class="w-full max-w-xs px-4 py-2.5 text-white bg-primary hover:bg-red-600 rounded-lg font-medium transition-all duration-200 flex items-center justify-center shadow-sm">
                <i class="fas fa-sign-in-alt mr-2 text-sm"></i>
                <span class="text-sm">Login</span>
            </a>
        </div>
    </div>

    <script>
        function toggleOffcanvas() {
            const menu = document.getElementById('offcanvasMenu');
            const backdrop = document.getElementById('offcanvasBackdrop');

            menu.classList.toggle('-translate-x-full');
            backdrop.classList.toggle('hidden');
        }

        // Close off-canvas when clicking backdrop
        document.getElementById('offcanvasBackdrop')?.addEventListener('click', toggleOffcanvas);
    </script>

    <!-- Main Content -->
    <main>
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="bg-white text-gray-900 py-12 mt-auto border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <?php $siteLogo = site_setting('site_logo', ''); ?>
                        <?php if (!empty($siteLogo)): ?>
                            <img src="<?= esc(base_url('uploads/' . $siteLogo)) ?>" alt="<?= esc(site_setting('site_name', 'TutorConnect Malawi')) ?>" class="h-14 w-auto" />
                        <?php else: ?>
                            <i class="fas fa-graduation-cap text-2xl text-primary"></i>
                            <span class="ml-2 text-xl font-bold text-gray-900"><?= esc(site_setting('site_name', 'TutorConnect Malawi')) ?></span>
                        <?php endif; ?>
                    </div>
                    <p class="text-gray-600">
                        Connecting students with verified Teachers across Malawi for primary and secondary education.
                    </p>
                    <div class="mt-4 flex space-x-4">
                        <?php $fb = site_setting('social_facebook_url', ''); ?>
                        <?php $tw = site_setting('social_twitter_url', ''); ?>
                        <?php $ig = site_setting('social_instagram_url', ''); ?>

                        <?php if (!empty($fb)): ?>
                            <a href="<?= esc($fb) ?>" target="_blank" rel="noopener" class="text-gray-600 hover:text-primary" aria-label="Facebook">
                                <i class="fab fa-facebook-f h-6 w-6"></i>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($tw)): ?>
                            <a href="<?= esc($tw) ?>" target="_blank" rel="noopener" class="text-gray-600 hover:text-primary" aria-label="Twitter/X">
                                <i class="fab fa-twitter h-6 w-6"></i>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($ig)): ?>
                            <a href="<?= esc($ig) ?>" target="_blank" rel="noopener" class="text-gray-600 hover:text-primary" aria-label="Instagram">
                                <i class="fab fa-instagram h-6 w-6"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-semibold uppercase tracking-wider mb-4 text-gray-900">Resources</h3>
                    <ul class="space-y-3">
                        <li><a href="<?= site_url('resources/past-papers') ?>" class="text-gray-600 hover:text-primary">Past Papers</a></li>
                        <li><a href="<?= site_url('resources/video-solutions') ?>" class="text-gray-600 hover:text-primary">Video Solutions</a></li>
                        <li><a href="<?= site_url('teach-in-japan') ?>" class="text-gray-600 hover:text-primary">Teach in Japan</a></li>
                        <li><a href="<?= site_url('find-tutors') ?>" class="text-gray-600 hover:text-primary">Find Teachers</a></li>
                        <li><a href="<?= site_url('pricing') ?>" class="text-gray-600 hover:text-primary">Pricing</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-sm font-semibold uppercase tracking-wider mb-4 text-gray-900">Quick Links</h3>
                    <ul class="space-y-3">
                        <li><a href="<?= site_url('/') ?>" class="text-gray-600 hover:text-primary">Home</a></li>
                        <li><a href="<?= site_url('how-it-works') ?>" class="text-gray-600 hover:text-primary">How It Works</a></li>
                        <li><a href="<?= site_url('about') ?>" class="text-gray-600 hover:text-primary">About Us</a></li>
                        <li><a href="<?= site_url('contact') ?>" class="text-gray-600 hover:text-primary">Contact</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-sm font-semibold uppercase tracking-wider mb-4 text-gray-900">Legal</h3>
                    <ul class="space-y-3">
                        <li><a href="<?= site_url('privacy-policy') ?>" class="text-gray-600 hover:text-primary">Privacy Policy</a></li>
                        <li><a href="<?= site_url('terms-of-service') ?>" class="text-gray-600 hover:text-primary">Terms of Service</a></li>
                        <li><a href="<?= site_url('refund-policy') ?>" class="text-gray-600 hover:text-primary">Refund Policy</a></li>
                        <li><a href="<?= site_url('verification-process') ?>" class="text-gray-600 hover:text-primary">Verification Process</a></li>
                        <li><a href="<?= site_url('child-safeguarding') ?>" class="text-gray-600 hover:text-primary">Child Safeguarding</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-sm font-semibold uppercase tracking-wider mb-4 text-gray-900">Contact Us</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <i class="fas fa-envelope text-primary mr-2 mt-1"></i>
                            <span class="text-gray-600"><?= esc(site_setting('contact_email', 'info@tutorconnectmw.com')) ?></span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-phone text-primary mr-2 mt-1"></i>
                            <span class="text-gray-600"><?= esc(site_setting('support_phone', '+265 992313978 / 883790001')) ?></span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt text-primary mr-2 mt-1"></i>
                            <span class="text-gray-600">
                                <?= esc(site_setting('support_address', 'C/O Visual Space Consulting, Bingu National Stadium, E16, Gulliver, Lilongwe')) ?>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="mt-12 pt-8 border-t border-gray-200">
                <p class="text-center text-gray-600">© <?= date('Y') ?> <?= esc(site_setting('site_name', 'TutorConnect Malawi')) ?>. All rights reserved.</p>
                <p class="text-center text-gray-500 mt-2 text-sm">
                    Developed with ❤️ by <a href="https://www.uprisemw.com" target="_blank" class="text-primary hover:text-red-600 font-medium">UPrise Malawi</a>
                </p>
            </div>
        </div>
    </footer>

    <?= $this->renderSection('scripts') ?>
</body>
</html>
