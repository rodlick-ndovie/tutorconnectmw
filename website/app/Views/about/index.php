<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="About TutorConnect Malawi - Platform that connects verified tutors with students across Malawi">
    <title><?php echo $title ?? 'About Us - TutorConnect Malawi'; ?></title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Inter Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #0d6efd;
            --dark: #212529;
            --light: #f8f9fa;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }

        /* Navbar - 100% same as other pages */
        .navbar {
            background: white !important;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
        }
        .navbar-brand {
            font-size: 1.8rem;
            font-weight: 800;
        }
        .nav-link {
            font-weight: 500;
            color: #495057 !important;
            transition: color 0.3s;
        }
        .nav-link:hover, .nav-link.active {
            color: var(--primary) !important;
        }

        /* Hero */
        .hero-section {
            background: linear-gradient(135deg, var(--primary) 0%, #0dcaf0 100%);
            color: white;
            padding: 120px 0 80px;
            text-align: center;
        }

        /* Section Title */
        .section-title {
            position: relative;
            padding-bottom: 15px;
            display: inline-block;
        }
        .section-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 70px;
            height: 4px;
            background: var(--primary);
            border-radius: 2px;
        }

        /* Cards */
        .info-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            padding: 2.5rem;
            height: 100%;
            transition: transform 0.3s ease;
        }
        .info-card:hover {
            transform: translateY(-8px);
        }

        .stat-card {
            background: var(--primary);
            color: white;
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
            transition: transform 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-10px);
        }
        .stat-card h3 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .team-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            text-align: center;
        }
        .team-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        .team-card img {
            width: 140px;
            height: 140px;
            object-fit: cover;
            margin: 0 auto 1.5rem;
        }

        .cta-section {
            background: linear-gradient(135deg, var(--primary), #0dcaf0);
            color: white;
            border-radius: 20px;
            padding: 4rem 2rem;
            text-align: center;
        }

        /* Footer - NO underlines */
        footer a,
        footer a:hover,
        footer a:focus {
            text-decoration: none !important;
            color: inherit !important;
        }
        .social-links a {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #f8f9fa;
            color: var(--primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            margin: 0 0.4rem;
        }
        .social-links a:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-4px);
        }
    </style>
</head>
<body>

    <!-- Navigation - Same as all pages -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top bg-white shadow-sm py-3">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3" href="<?php echo base_url(); ?>">
                <span class="text-primary">Uprise</span> Malawi
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item mx-2"><a class="nav-link fw-medium" href="<?php echo base_url(); ?>#services">Solutions</a></li>
                    <li class="nav-item mx-2"><a class="nav-link fw-medium" href="<?php echo base_url(); ?>#training">Training</a></li>
                    <li class="nav-item mx-2"><a class="nav-link fw-medium" href="<?php echo base_url('portfolio'); ?>">Portfolio</a></li>
                    <li class="nav-item mx-2"><a class="nav-link fw-medium active" href="<?php echo base_url('about'); ?>">About</a></li>
                    <li class="nav-item mx-2"><a class="nav-link fw-medium" href="<?php echo base_url('contact'); ?>">Contact</a></li>
                </ul>
                <div class="navbar-nav">
                    <a class="btn btn-outline-primary me-3 px-4 fw-medium" href="<?php echo base_url('login'); ?>">Client Portal</a>
                    <a class="btn btn-primary px-4 fw-medium" href="<?php echo base_url('register'); ?>">Get Started</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="hero-section">
        <div class="container">
            <h1 class="display-4 fw-bold mb-4">About TutorConnect Malawi</h1>
            <p class="lead col-lg-8 mx-auto opacity-90">
                Empowering Malawi’s digital future through innovative ICT solutions,<br>
                world-class training, and unwavering commitment to excellence.
            </p>
        </div>
    </section>

    <!-- Mission & Vision -->
    <section class="py-5">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-6">
                    <div class="info-card text-center">
                        <i class="fas fa-eye fa-4x text-primary mb-4"></i>
                        <h3 class="fw-bold mb-3">Our Vision</h3>
                        <p class="text-muted fs-5">
                            To be Malawi's premier digital innovation partner, bridging technology gaps and driving sustainable development through ICT excellence.
                        </p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="info-card text-center">
                        <i class="fas fa-bullseye fa-4x text-primary mb-4"></i>
                        <h3 class="fw-bold mb-3">Our Mission</h3>
                        <p class="text-muted fs-5">
                            Deliver world-class ICT training, software solutions, and digital services tailored to Malawi’s unique business and developmental needs.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Story -->
    <section class="py-5 bg-light">
        <div class="container py-5">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <h2 class="fw-bold mb-4 section-title mx-auto text-center text-lg-start">Our Story</h2>
                    <p class="lead text-muted">
                        Founded in 2017, TutorConnect Malawi was born from a passion to transform the nation’s digital landscape.
                    </p>
                    <p class="text-muted">
                        Witnessing the growing technology gap and its impact on businesses and education, our founding team of ICT experts came together with one goal: to build locally relevant, high-quality digital solutions.
                    </p>
                    <p class="text-muted">
                        Today, we proudly serve over <strong>80 clients</strong> and have trained more than <strong>500 professionals</strong>. Our journey is a testament to innovation, resilience, and a deep commitment to Malawi’s digital growth.
                    </p>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="https://via.placeholder.com/600x400/0d6efd/ffffff?text=Uprise+Malawi+Team" alt="TutorConnect Malawi Team" class="img-fluid rounded shadow-lg">
                </div>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section class="py-5">
        <div class="container py-5">
            <div class="row g-4">
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <h3><?php echo $stats['projects'] ?? '150'; ?>+</h3>
                        <p class="mb-0 fw-medium">Projects Delivered</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <h3><?php echo $stats['clients'] ?? '80'; ?>+</h3>
                        <p class="mb-0 fw-medium">Happy Clients</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <h3><?php echo $stats['experience'] ?? '8'; ?>+</h3>
                        <p class="mb-0 fw-medium">Years of Excellence</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <h3><?php echo $stats['expertise'] ?? '50'; ?>+</h3>
                        <p class="mb-0 fw-medium">Expert Certifications</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team -->
    <section class="py-5 bg-light">
        <div class="container py-5">
            <h2 class="fw-bold text-center mb-5 section-title">Meet Our Expert Team</h2>
            <div class="row g-4">
                <?php foreach ($team as $member): ?>
                <div class="col-lg-3 col-md-6">
                    <div class="team-card p-4">
                        <img src="https://via.placeholder.com/140/0d6efd/ffffff?text=<?php echo substr($member['name'], 0, 2); ?>" alt="<?php echo $member['name']; ?>" class="rounded-circle mb-3">
                        <h5 class="fw-bold"><?php echo $member['name']; ?></h5>
                        <p class="text-primary fw-medium"><?php echo $member['role']; ?></p>
                        <p class="text-muted small"><?php echo $member['bio']; ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-5">
        <div class="container">
            <div class="cta-section">
                <h3 class="fw-bold display-5 mb-4">Ready to Transform Your Future?</h3>
                <p class="lead mb-4">Join hundreds of businesses and professionals who’ve grown with TutorConnect Malawi.</p>
                <a href="<?php echo base_url('contact'); ?>" class="btn btn-outline-light btn-lg px-5 me-3">Contact Us</a>
                <a href="<?php echo base_url('register'); ?>" class="btn btn-light btn-lg px-5">Get Started Now</a>
            </div>
        </div>
    </section>

    <!-- Footer - Same as all pages, NO underlines -->
    <footer class="bg-white border-top py-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col-lg-5 col-md-6 mb-4">
                    <h5 class="fw-bold fs-4 mb-3"><span class="text-primary">Uprise</span> Malawi</h5>
                    <p class="text-muted">Leading provider of innovative technology solutions and professional ICT training in Malawi. Empowering businesses through digital transformation.</p>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-6 mb-4">
                    <h6 class="fw-bold text-primary mb-3">Solutions</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="<?php echo base_url(); ?>#services" class="text-muted small">ICT Training</a></li>
                        <li class="mb-2"><a href="<?php echo base_url(); ?>#services" class="text-muted small">Web Development</a></li>
                        <li class="mb-2"><a href="<?php echo base_url(); ?>#services" class="text-muted small">Mobile Apps</a></li>
                        <li class="mb-2"><a href="<?php echo base_url(); ?>#services" class="text-muted small">Digital Marketing</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-6 mb-4">
                    <h6 class="fw-bold text-primary mb-3">Company</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="<?php echo base_url('about'); ?>" class="text-muted small">About Us</a></li>
                        <li class="mb-2"><a href="<?php echo base_url('portfolio'); ?>" class="text-muted small">Portfolio</a></li>
                        <li class="mb-2"><a href="<?php echo base_url('contact'); ?>" class="text-muted small">Contact</a></li>
                        <li class="mb-2"><a href="<?php echo base_url('register'); ?>" class="text-muted small">Get Started</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h6 class="fw-bold text-primary mb-3">Connect With Us</h6>
                    <p class="small mb-2"><i class="fas fa-envelope text-primary me-2"></i><a href="mailto:info@tutorconnectmw.com" class="text-muted">info@tutorconnectmw.com</a></p>
                    <p class="small mb-3"><i class="fas fa-phone text-primary me-2"></i><a href="tel:+265992313978" class="text-muted">+265 992 313 978</a></p>
                    <div class="mt-3 social-links">
                        <a href="#" class="me-2"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="me-2"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="me-2"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="me-2"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="text-muted small mb-0">© <?php echo date('Y'); ?> TutorConnect Malawi. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <nav class="nav justify-content-md-end">
                        <a href="#" class="nav-link px-2 small text-muted">Privacy Policy</a>
                        <a href="#" class="nav-link px-2 small text-muted">Terms of Service</a>
                    </nav>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


