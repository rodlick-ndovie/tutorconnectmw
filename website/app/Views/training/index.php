<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Professional ICT Training Programs - TutorConnect Malawi - Web Development, Mobile Apps, Graphics Design, Digital Marketing and more">
    <title><?php echo $title ?? 'ICT Training Programs - TutorConnect Malawi'; ?></title>

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
            text-align: center;
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

        /* Course Cards - same feel as contact cards */
        .course-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            overflow: hidden;
            transition: transform 0.3s ease;
            height: 100%;
        }
        .course-card:hover {
            transform: translateY(-8px);
        }
        .course-img {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }
        .level-badge {
            background: #e3f2fd;
            color: var(--primary);
            padding: 0.45rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
        }
        .price {
            font-size: 1.7rem;
            font-weight: 800;
            color: var(--primary);
        }

        .btn-primary, .btn-outline-primary {
            border-radius: 12px;
            padding: 12px 32px;
            font-weight: 600;
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
                <span class="text-primary">TutorConnect</span> Malawi
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

    <!-- Hero -->
    <section class="hero-section">
        <div class="container">
            <h1 class="display-4 fw-bold mb-4">Professional ICT Training</h1>
            <p class="lead col-lg-8 mx-auto opacity-90">
                Industry-recognized courses designed to launch and advance your tech career in Malawi.<br>
                From beginner to expert level — we have the right program for you.
            </p>
            <div class="mt-4">
                <a href="<?php echo base_url('register'); ?>" class="btn btn-light btn-lg px-5 me-3">Enroll Now</a>
                <a href="#courses" class="btn btn-outline-light btn-lg px-5">View All Courses</a>
            </div>
        </div>
    </section>

    <!-- Courses Grid -->
    <section id="courses" class="py-5">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="fw-bold display-5 section-title">Available Courses</h2>
                <p class="lead text-muted col-lg-8 mx-auto">Choose from our range of beginner to advanced professional programs</p>
            </div>

            <div class="row g-5">
                <?php foreach ($courses as $course): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="course-card h-100 d-flex flex-column">
                        <img src="https://via.placeholder.com/600x400/0d6efd/ffffff?text=<?= urlencode($course['title']) ?>" 
                             class="course-img" alt="<?= htmlspecialchars($course['title']) ?>">
                        <div class="p-4 flex-grow-1 d-flex flex-column">
                            <div class="mb-3">
                                <span class="level-badge"><?= htmlspecialchars($course['level']) ?> Level</span>
                            </div>
                            <h4 class="fw-bold mb-3"><?= htmlspecialchars($course['title']) ?></h4>
                            <p class="text-muted flex-grow-1">
                                <?= substr(htmlspecialchars($course['description']), 0, 130) ?>...
                            </p>

                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <small class="text-muted"><i class="fas fa-clock me-2"></i><?= htmlspecialchars($course['duration']) ?></small>
                                    <div class="price">MWK <?= number_format($course['price']) ?></div>
                                </div>
                                <a href="<?= base_url('training/course/' . $course['id']) ?>" 
                                   class="btn btn-primary w-100 fw-semibold">View Details →</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Footer - EXACT same as Contact page (no underlines) -->
    <footer class="bg-white border-top py-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col-lg-5 col-md-6 mb-4">
                    <h5 class="fw-bold fs-4 mb-3"><span class="text-primary">TutorConnect</span> Malawi</h5>
                    <p class="text-muted">Connecting students with verified tutors across Malawi. Providing quality education for primary and secondary students.</p>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-6 mb-4">
                    <h6 class="fw-bold text-primary mb-3">Services</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-muted small">Tutor Search</a></li>
                        <li class="mb-2"><a href="#" class="text-muted small">Online Tutoring</a></li>
                        <li class="mb-2"><a href="#" class="text-muted small">Academic Support</a></li>
                        <li class="mb-2"><a href="#" class="text-muted small">Subject Specialists</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-6 mb-4">
                    <h6 class="fw-bold text-primary mb-3">Legal</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-muted small">Privacy Policy</a></li>
                        <li class="mb-2"><a href="#" class="text-muted small">Terms of Service</a></li>
                        <li class="mb-2"><a href="#" class="text-muted small">Refund Policy</a></li>
                        <li class="mb-2"><a href="#" class="text-muted small">Verification Process</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h6 class="fw-bold text-primary mb-3">Contact Information</h6>
                    <p class="small mb-2"><i class="fas fa-envelope text-primary me-2"></i><a href="mailto:info@tutorconnectmw.com" class="text-muted">info@tutorconnectmw.com</a></p>
                    <p class="small mb-2"><i class="fas fa-phone text-primary me-2"></i><a href="tel:+265992313978" class="text-muted">+265 992 313 978</a></p>
                    <p class="small mb-2"><i class="fas fa-phone text-primary me-2"></i><a href="tel:+265883790001" class="text-muted">+265 883 790 001</a></p>
                    <p class="small mb-3"><i class="fas fa-map-marker-alt text-primary me-2"></i>C/O Visual Space Consulting<br>E16, Gulliver, Lilongwe</p>
                    <div class="mt-3 social-links">
                        <a href="#" class="btn btn-sm btn-light me-2 rounded-circle p-2"><i class="fab fa-facebook-f text-primary"></i></a>
                        <a href="#" class="btn btn-sm btn-light me-2 rounded-circle p-2"><i class="fab fa-twitter text-primary"></i></a>
                        <a href="#" class="btn btn-sm btn-light me-2 rounded-circle p-2"><i class="fab fa-instagram text-primary"></i></a>
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
