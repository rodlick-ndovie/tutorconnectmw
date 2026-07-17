<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= htmlspecialchars($course['title']) ?> - Professional ICT Training in Malawi">
    <title><?php echo $title ?? htmlspecialchars($course['title']) . ' - TutorConnect Malawi'; ?></title>

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

        /* Navbar - Identical to Contact page */
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
            text-align: left;
        }

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

        .tag {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 0.5rem 1.2rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-block;
            backdrop-filter: blur(10px);
        }

        .price-large {
            font-size: 2.8rem;
            font-weight: 800;
        }

        .course-img {
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }

        .module-item {
            display: flex;
            align-items: center;
            padding: 1.4rem 0;
            border-bottom: 1px solid #e9ecef;
        }
        .module-number {
            width: 48px; height: 48px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.2rem;
            margin-right: 1.2rem;
            flex-shrink: 0;
        }

        .sidebar-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            padding: 2rem;
            height: fit-content;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.9rem 0;
            border-bottom: 1px dashed #dee2e6;
            font-size: 1rem;
        }
        .detail-row:last-child { border: none; }

        .btn-enroll {
            background: var(--primary);
            border: none;
            padding: 16px 40px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.1rem;
        }
        .btn-enroll:hover {
            background: #0b5ed7;
            transform: translateY(-2px);
        }

        .cta-section {
            background: white;
            padding: 80px 0;
            text-align: center;
        }

        /* FOOTER: NO UNDERLINES ANYWHERE */
        footer a,
        footer a:hover,
        footer a:focus,
        footer a:active {
            text-decoration: none !important;
            color: inherit !important;
        }
        footer .nav-link {
            padding: 0.5rem 1rem !important;
        }
    </style>
</head>
<body>

    <!-- Navigation - Identical to Contact page -->
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
                    <li class="nav-item mx-2"><a class="nav-link fw-medium active" href="<?php echo base_url('training'); ?>">Training</a></li>
                    <li class="nav-item mx-2"><a class="nav-link fw-medium" href="<?php echo base_url('portfolio'); ?>">Portfolio</a></li>
                    <li class="nav-item mx-2"><a class="nav-link fw-medium" href="<?php echo base_url('about'); ?>">About</a></li>
                    <li class="nav-item mx-2"><a class="nav-link fw-medium" href="<?php echo base_url('contact'); ?>">Contact</a></li>
                </ul>
                <div class="navbar-nav">
                    <a class="btn btn-outline-primary me-3 px-4 fw-medium" href="<?php echo base_url('login'); ?>">Client Portal</a>
                    <a class="btn btn-primary px-4 fw-medium" href="<?php echo base_url('register'); ?>">Get Started</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <span class="tag"><?= htmlspecialchars($course['level']) ?> Level</span>
                    <h1 class="display-4 fw-bold mt-3 mb-4"><?= htmlspecialchars($course['title']) ?></h1>
                    <p class="lead opacity-90 mb-4"><?= htmlspecialchars($course['description']) ?></p>

                    <div class="d-flex flex-wrap align-items-center gap-4 mb-4">
                        <span><i class="fas fa-clock me-2"></i><?= htmlspecialchars($course['duration']) ?></span>
                        <span><i class="fas fa-graduation-cap me-2"></i><?= htmlspecialchars($course['level']) ?></span>
                        <span><i class="fas fa-book me-2"></i><?= count($course['modules']) ?> Modules</span>
                    </div>

                    <div class="d-flex align-items-center gap-4 flex-wrap">
                        <div class="price-large">MWK <?= number_format($course['price']) ?></div>
                        <div>
                            <a href="<?php echo base_url('register'); ?>" class="btn btn-light btn-lg px-5 fw-semibold">Enroll Now</a>
                            <a href="<?php echo base_url('training'); ?>" class="btn btn-outline-light ms-3">Back to Courses</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 text-center mt-5 mt-lg-0">
                    <img src="https://via.placeholder.com/700x450/0d6efd/ffffff?text=<?= urlencode($course['title']) ?>" 
                         alt="<?= htmlspecialchars($course['title']) ?>" class="img-fluid course-img">
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-5">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-8">
                    <h2 class="fw-bold display-5 section-title mx-auto text-center mb-5">Course Curriculum</h2>
                    <p class="lead text-muted text-center col-lg-10 mx-auto mb-5">
                        This hands-on course includes <?= count($course['modules']) ?> modules designed for real-world application and career readiness.
                    </p>

                    <div class="bg-white rounded-4 shadow-sm p-4">
                        <?php foreach ($course['modules'] as $i => $module): ?>
                        <div class="module-item">
                            <div class="module-number"><?= $i + 1 ?></div>
                            <div class="module-title"><?= htmlspecialchars($module) ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="sidebar-card">
                        <h4 class="fw-bold mb-4">Course Summary</h4>
                        <div class="detail-row"><span>Duration</span> <strong><?= htmlspecialchars($course['duration']) ?></strong></div>
                        <div class="detail-row"><span>Level</span> <strong><?= htmlspecialchars($course['level']) ?></strong></div>
                        <div class="detail-row"><span>Modules</span> <strong><?= count($course['modules']) ?></strong></div>
                        <div class="detail-row"><span>Price</span> <strong class="text-primary fs-5">MWK <?= number_format($course['price']) ?></strong></div>

                        <?php if (!empty($course['technologies'])): ?>
                        <div class="mt-4 pt-3 border-top">
                            <h6 class="fw-bold">Technologies Covered</h6>
                            <div class="mt-3">
                                <?php foreach ($course['technologies'] as $tech): ?>
                                    <span class="badge bg-primary fs-6 px-3 py-2 me-2 mb-2"><?= htmlspecialchars($tech) ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="mt-5">
                            <a href="<?php echo base_url('register'); ?>" class="btn btn-enroll text-white w-100 mb-3">Enroll in This Course</a>
                            <a href="<?php echo base_url('contact'); ?>" class="btn btn-outline-primary w-100">Have Questions? Contact Us</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="cta-section">
        <div class="container text-center">
            <h3 class="fw-bold display-5 mb-4">Ready to Transform Your Career?</h3>
            <p class="lead text-muted col-lg-8 mx-auto mb-5">
                Join thousands of students who have launched successful tech careers with TutorConnect Malawi.
            </p>
            <div>
                <a href="<?php echo base_url('register'); ?>" class="btn btn-primary btn-lg px-5 me-3">Create Free Account</a>
                <a href="<?php echo base_url('training'); ?>" class="btn btn-outline-primary btn-lg px-5">Browse All Courses</a>
            </div>
        </div>
    </section>

    <!-- Footer - EXACT same as Contact page (no underlines) -->
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
                        <a href="#" class="btn btn-sm btn-light me-2 rounded-circle p-2"><i class="fab fa-facebook-f text-primary"></i></a>
                        <a href="#" class="btn btn-sm btn-light me-2 rounded-circle p-2"><i class="fab fa-twitter text-primary"></i></a>
                        <a href="#" class="btn btn-sm btn-light me-2 rounded-circle p-2"><i class="fab fa-linkedin-in text-primary"></i></a>
                        <a href="#" class="btn btn-sm btn-light me-2 rounded-circle p-2"><i class="fab fa-instagram text-primary"></i></a>
                        <a href="#" class="btn btn-sm btn-light me-2 rounded-circle p-2"><i class="fab fa-youtube text-primary"></i></a>
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

