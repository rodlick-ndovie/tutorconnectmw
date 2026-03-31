<?php
$supportPhone = site_setting('support_phone', '+265 992 313 978');
$supportEmail = site_setting('contact_email', 'info@tutorconnectmw.com');
$whatsAppLink = 'https://wa.me/265992313978?text=' . rawurlencode('Hello TutorConnect Malawi, I want to apply for the Japan teaching opportunity.');
$applicationFee = (int) ($applicationFee ?? site_setting('japan_application_fee', '10000'));
$applicationFeeFormatted = number_format((float) $applicationFee, 0);
$requiredChecklist = [
    'Use the same email address for payment and the final application form.',
    'Prepare your passport details and your completed degree or transcript.',
    'Have three referees ready, including at least one relative.',
    'Confirm you understand the MK ' . $applicationFeeFormatted . ' application fee is non-refundable.',
];
$recommendedChecklist = [
    'Have your teaching certificate ready if you already have one.',
    'Prepare a short, accurate summary of your teaching background.',
    'Keep your phone available for follow-up and WhatsApp communication.',
    'If you already shared documents before, note that clearly in the form.',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#E55C0D">
    <title><?= esc($title ?? 'Unlock Japan Application - TutorConnect Malawi') ?></title>
    <meta name="description" content="<?= esc($description ?? 'Pay and unlock the Japan teaching opportunity application form.') ?>">
    <link rel="icon" type="image/x-icon" href="<?= base_url('favicon.ico') ?>">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Text:wght@400;600&display=swap" rel="stylesheet">
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
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Crimson Text', 'serif']
                    }
                }
            }
        };
    </script>
    <style>
        :root {
            --primary-color: #E55C0D;
            --secondary-color: #C94609;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --bg-primary: #f8fafc;
            --bg-secondary: #ffffff;
            --border-color: #e2e8f0;
            --success-bg: #ecfdf5;
            --success-text: #065f46;
            --error-bg: #fef2f2;
            --error-text: #991b1b;
            --info-bg: #eff6ff;
            --info-text: #1e40af;
            --shadow: 0 10px 25px rgba(15, 23, 42, 0.08);
            --radius: 20px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: var(--bg-primary);
            color: var(--text-dark);
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .hero {
            position: relative;
            overflow: hidden;
            color: #fff;
            background-image:
                linear-gradient(90deg, rgba(124, 26, 26, 0.86), rgba(201, 70, 9, 0.74)),
                url('<?= base_url('assets/japan.jpg') ?>');
            background-size: cover;
            background-position: center;
        }

        .hero-inner,
        .section,
        .footer {
            width: min(1120px, calc(100% - 32px));
            margin: 0 auto;
        }

        .hero-inner {
            padding: 28px 0 84px;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 48px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
        }

        .brand-badge {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.16);
            border: 1px solid rgba(255, 255, 255, 0.28);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.9);
        }

        .hero-grid {
            max-width: 760px;
        }

        .hero h1 {
            margin: 0;
            font-size: clamp(36px, 5vw, 60px);
            line-height: 1.05;
            letter-spacing: -0.03em;
        }

        .hero p {
            margin: 20px 0 0;
            max-width: 760px;
            font-size: 19px;
            line-height: 1.7;
            color: rgba(255, 255, 255, 0.92);
        }

        .cta-row {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin-top: 28px;
        }

        .btn,
        .btn-outline,
        .pill {
            border-radius: 14px;
            padding: 14px 20px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn {
            background: #fff;
            color: var(--primary-color);
        }

        .btn-outline {
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.28);
            background: rgba(255, 255, 255, 0.06);
        }

        .pill {
            color: #fff;
            background: rgba(127, 29, 29, 0.52);
            border: 1px solid rgba(255, 255, 255, 0.22);
        }

        .hero-card {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.22);
            border-radius: 24px;
            padding: 24px;
            backdrop-filter: blur(8px);
        }

        .hero-card .eyebrow,
        .card .eyebrow {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.18em;
            font-weight: 800;
        }

        .hero-card .price {
            margin-top: 12px;
            font-size: 42px;
            font-weight: 800;
        }

        .hero-card p {
            margin-top: 10px;
            font-size: 15px;
            line-height: 1.7;
        }

        .section {
            padding: 28px 0 40px;
        }

        .grid-3,
        .grid-2 {
            display: grid;
            gap: 20px;
        }

        .grid-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .grid-2 {
            grid-template-columns: 1.12fr 0.88fr;
        }

        .card {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: var(--radius);
            padding: 28px;
            box-shadow: var(--shadow);
        }

        .card.red {
            background: #fff7f5;
            border-color: #fed7d7;
        }

        .card.orange {
            background: #fffaf0;
            border-color: #fdba74;
        }

        .card.gray {
            background: #f8fafc;
        }

        .card h2,
        .card h3 {
            margin: 0;
        }

        .card p {
            color: var(--text-light);
            line-height: 1.75;
        }

        .list {
            list-style: none;
            padding: 0;
            margin: 18px 0 0;
            display: grid;
            gap: 12px;
        }

        .list li {
            display: flex;
            gap: 12px;
            line-height: 1.7;
            color: var(--text-dark);
        }

        .list i {
            margin-top: 5px;
            color: var(--primary-color);
        }

        .flash {
            border-radius: 16px;
            padding: 16px 18px;
            margin-bottom: 20px;
            line-height: 1.6;
            border: 1px solid transparent;
        }

        .flash.error {
            background: var(--error-bg);
            color: var(--error-text);
            border-color: #fecaca;
        }

        .flash.success {
            background: var(--success-bg);
            color: var(--success-text);
            border-color: #a7f3d0;
        }

        .flash.info {
            background: var(--info-bg);
            color: var(--info-text);
            border-color: #bfdbfe;
        }

        .label {
            display: block;
            margin-bottom: 16px;
        }

        .label span {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 700;
        }

        .input,
        .textarea {
            width: 100%;
            border-radius: 14px;
            border: 1px solid #cbd5e1;
            background: #fff;
            color: var(--text-dark);
            padding: 14px 16px;
            font: inherit;
        }

        .input:focus,
        .textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(229, 92, 13, 0.12);
        }

        .checkbox {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            border-radius: 16px;
            border: 1px solid var(--border-color);
            background: #f8fafc;
            padding: 16px;
            line-height: 1.7;
            color: var(--text-dark);
        }

        .checkbox input {
            margin-top: 4px;
        }

        .submit-btn {
            width: 100%;
            border: 0;
            cursor: pointer;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: #fff;
            font: inherit;
            font-weight: 800;
            border-radius: 16px;
            padding: 16px 20px;
        }

        .feedback {
            display: none;
            border-radius: 16px;
            border: 1px solid transparent;
            padding: 14px 16px;
            line-height: 1.7;
            font-size: 14px;
        }

        .feedback.info {
            display: block;
            background: var(--info-bg);
            color: var(--info-text);
            border-color: #bfdbfe;
        }

        .feedback.success {
            display: block;
            background: var(--success-bg);
            color: var(--success-text);
            border-color: #a7f3d0;
        }

        .feedback.error {
            display: block;
            background: var(--error-bg);
            color: var(--error-text);
            border-color: #fecaca;
        }

        .steps {
            list-style: none;
            padding: 0;
            margin: 18px 0 0;
            display: grid;
            gap: 12px;
        }

        .steps li {
            color: var(--text-dark);
            line-height: 1.7;
        }

        .steps strong {
            color: var(--primary-color);
        }

        .footer {
            padding: 0 0 40px;
            color: var(--text-light);
            font-size: 14px;
            line-height: 1.7;
        }

        @media (max-width: 980px) {
            .hero-grid,
            .grid-2,
            .grid-3 {
                grid-template-columns: 1fr;
            }

            .topbar {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body class="bg-neutral font-sans">
    <div id="wrapper"></div>

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

                <div class="hidden md:flex md:items-center md:space-x-8">
                    <a href="<?= site_url('/') ?>" class="text-secondary hover:text-primary px-3 py-2 font-medium transition-colors">Home</a>
                    <a href="<?= site_url('resources') ?>" class="text-secondary hover:text-primary px-3 py-2 font-medium transition-colors">Resources</a>
                    <a href="<?= site_url('teach-in-japan') ?>" class="text-secondary hover:text-primary px-3 py-2 font-medium transition-colors">Teach in Japan</a>
                    <a href="<?= site_url('pricing') ?>" class="text-secondary hover:text-primary px-3 py-2 font-medium transition-colors">Pricing</a>
                    <a href="<?= site_url('notice') ?>" class="text-secondary hover:text-primary px-3 py-2 font-medium transition-colors">Notices</a>
                    <a href="<?= site_url('find-tutors') ?>" class="text-secondary hover:text-primary px-3 py-2 font-medium transition-colors">Find Teachers</a>
                    <a href="<?= site_url('login') ?>" class="text-primary hover:text-red-600 px-3 py-2 font-medium flex items-center">
                        <i class="fas fa-sign-in-alt mr-1"></i> Login
                    </a>
                </div>

                <div class="md:hidden">
                    <button onclick="toggleOffcanvas()" class="text-secondary hover:text-primary transition-colors p-2">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <div id="offcanvasBackdrop" class="hidden fixed inset-0 bg-black bg-opacity-70 z-40 md:hidden transition-opacity duration-300" onclick="toggleOffcanvas()"></div>

    <div id="offcanvasMenu" class="fixed inset-0 bg-white transform -translate-x-full transition-transform duration-300 z-50 md:hidden overflow-y-auto">
        <div class="bg-gradient-to-r from-secondary to-accent text-white p-6 flex items-center justify-between sticky top-0">
            <div class="flex items-center">
                <?php if (!empty($siteLogo)): ?>
                    <img src="<?= esc(base_url('uploads/' . $siteLogo)) ?>" alt="<?= esc(site_setting('site_name', 'TutorConnect Malawi')) ?>" class="h-14 w-auto" />
                <?php else: ?>
                    <i class="fas fa-graduation-cap text-2xl"></i>
                    <span class="ml-2 text-lg font-bold"><?= esc(site_setting('site_name', 'TutorConnect Malawi')) ?></span>
                <?php endif; ?>
            </div>
            <button onclick="toggleOffcanvas()" class="text-white hover:bg-white/20 p-2 rounded-lg transition">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

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
            <div class="w-full max-w-xs border-t border-slate-300 my-4"></div>
            <a href="<?= site_url('login') ?>" onclick="toggleOffcanvas()" class="w-full max-w-xs px-4 py-2.5 text-white bg-primary hover:bg-red-600 rounded-lg font-medium transition-all duration-200 flex items-center justify-center shadow-sm">
                <i class="fas fa-sign-in-alt mr-2 text-sm"></i>
                <span class="text-sm">Login</span>
            </a>
        </div>
    </div>

    <main>
    <section class="hero">
        <div class="hero-inner">
            <div class="topbar">
                <div class="brand">
                    <span class="brand-badge"><i class="fas fa-plane-departure"></i></span>
                    <span>TutorConnect Malawi</span>
                </div>
                <a href="<?= site_url('teach-in-japan') ?>" class="back-link"><i class="fas fa-arrow-left"></i> Back to Japan Opportunity</a>
            </div>

            <div class="hero-grid">
                <div>
                    <h1>Unlock the Japan Application Form</h1>
                    <p>Pay the MK <?= number_format((float) $applicationFee, 0) ?> application fee first, then continue to the full application form. If you refresh or come back later, the same paid email can restore access without paying again.</p>
                    <div class="cta-row">
                        <span class="pill">Application Fee: MK <?= number_format((float) $applicationFee, 0) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="grid-3">
            <div class="card red">
                <div class="eyebrow">Required Before You Start</div>
                <ul class="list">
                    <?php foreach ($requiredChecklist as $item): ?>
                        <li><i class="fas fa-check-circle"></i><span><?= esc($item) ?></span></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="card orange">
                <div class="eyebrow">Recommended to Prepare</div>
                <ul class="list">
                    <?php foreach ($recommendedChecklist as $item): ?>
                        <li><i class="fas fa-star"></i><span><?= esc($item) ?></span></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="card gray">
                <div class="eyebrow">Already Paid?</div>
                <p>Use the same email address you paid with and we will restore your access to the form without asking you to pay again.</p>
                <form method="post" action="<?= site_url('teach-in-japan/access/restore') ?>">
                    <?= csrf_field() ?>
                    <label class="label">
                        <span>Paid email address</span>
                        <input type="email" name="restore_email" value="<?= esc(old('restore_email')) ?>" class="input" placeholder="you@example.com" required>
                    </label>
                    <button type="submit" class="submit-btn" style="background:#fff;color:var(--primary-color);border:1px solid var(--primary-color);">Restore Access</button>
                </form>
            </div>
        </div>
    </section>

    <section class="section" style="padding-top:0;">
        <div class="grid-2">
            <div class="card">
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="flash error"><?= esc(session()->getFlashdata('error')) ?></div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="flash success"><?= esc(session()->getFlashdata('success')) ?></div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('info')): ?>
                    <div class="flash info"><?= esc(session()->getFlashdata('info')) ?></div>
                <?php endif; ?>

                <div class="eyebrow">Step 1 of 2</div>
                <h2 style="margin-top:10px;font-size:34px;">Confirm Payment Details</h2>
                <p>Complete the application fee payment below to unlock the full Japan application form.</p>

                <form id="unlockPaymentForm" style="margin-top:28px;">
                    <?= csrf_field() ?>

                    <label class="label">
                        <span>Applicant full name</span>
                        <input type="text" name="payer_full_name" value="<?= esc(old('payer_full_name')) ?>" class="input" placeholder="Full name as used for the application" required>
                    </label>

                    <label class="label">
                        <span>Email address</span>
                        <input type="email" name="payer_email" value="<?= esc(old('payer_email')) ?>" class="input" placeholder="Email used for payment and form access" required>
                    </label>

                    <label class="label">
                        <span>Phone / WhatsApp</span>
                        <input type="text" name="payer_phone" value="<?= esc(old('payer_phone')) ?>" class="input" placeholder="+265..." required>
                    </label>

                    <label class="checkbox">
                        <input type="checkbox" name="unlock_terms" value="1" required>
                        <span>I understand the MK <?= number_format((float) $applicationFee, 0) ?> application fee is non-refundable and only unlocks access to the application form. Employment is not guaranteed.</span>
                    </label>

                    <button type="submit" id="unlockPaymentBtn" class="submit-btn" style="margin-top:18px;">
                        <span id="unlockPaymentBtnText">Pay & Unlock Application Form</span>
                    </button>

                    <div id="unlockPaymentFeedback" class="feedback" style="margin-top:16px;"></div>
                </form>
            </div>

            <div style="display:grid;gap:20px;">
                <div class="card">
                    <div class="eyebrow">What Happens Next</div>
                    <ol class="steps">
                        <li><strong>1.</strong> Pay the application fee using PayChangu.</li>
                        <li><strong>2.</strong> Your access is unlocked for the Japan form.</li>
                        <li><strong>3.</strong> Refreshing does not require another payment while access is active.</li>
                        <li><strong>4.</strong> If needed later, restore access using the same paid email.</li>
                    </ol>
                </div>

                <div class="card">
                    <div class="eyebrow">Need Help?</div>
                    <p>Contact TutorConnect Malawi before paying if you want help checking your eligibility or confirming what documents to prepare.</p>
                    <div class="steps">
                        <li><strong>Call / WhatsApp:</strong> <?= esc($supportPhone) ?></li>
                        <li><strong>Email:</strong> <?= esc($supportEmail) ?></li>
                    </div>
                    <div class="cta-row" style="margin-top:20px;">
                        <a href="<?= esc($whatsAppLink) ?>" target="_blank" rel="noopener" class="btn-outline" style="background:var(--primary-color);border-color:var(--primary-color);"><i class="fab fa-whatsapp"></i> Ask on WhatsApp</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    </main>

    <footer class="bg-white text-gray-900 py-12 mt-auto border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <?php if (!empty($siteLogo)): ?>
                            <img src="<?= esc(base_url('uploads/' . $siteLogo)) ?>" alt="<?= esc(site_setting('site_name', 'TutorConnect Malawi')) ?>" class="h-14 w-auto" />
                        <?php else: ?>
                            <i class="fas fa-graduation-cap text-2xl text-primary"></i>
                            <span class="ml-2 text-xl font-bold text-gray-900"><?= esc(site_setting('site_name', 'TutorConnect Malawi')) ?></span>
                        <?php endif; ?>
                    </div>
                    <p class="text-gray-600">Connecting students with verified Teachers across Malawi for primary and secondary education.</p>
                    <div class="mt-4 flex space-x-4">
                        <?php $fb = site_setting('social_facebook_url', ''); ?>
                        <?php $tw = site_setting('social_twitter_url', ''); ?>
                        <?php $ig = site_setting('social_instagram_url', ''); ?>
                        <?php if (!empty($fb)): ?>
                            <a href="<?= esc($fb) ?>" target="_blank" rel="noopener" class="text-gray-600 hover:text-primary" aria-label="Facebook"><i class="fab fa-facebook-f h-6 w-6"></i></a>
                        <?php endif; ?>
                        <?php if (!empty($tw)): ?>
                            <a href="<?= esc($tw) ?>" target="_blank" rel="noopener" class="text-gray-600 hover:text-primary" aria-label="Twitter/X"><i class="fab fa-twitter h-6 w-6"></i></a>
                        <?php endif; ?>
                        <?php if (!empty($ig)): ?>
                            <a href="<?= esc($ig) ?>" target="_blank" rel="noopener" class="text-gray-600 hover:text-primary" aria-label="Instagram"><i class="fab fa-instagram h-6 w-6"></i></a>
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
                            <span class="text-gray-600"><?= esc(site_setting('support_address', 'C/O Visual Space Consulting, Bingu National Stadium, E16, Gulliver, Lilongwe')) ?></span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="mt-12 pt-8 border-t border-gray-200">
                <p class="text-center text-gray-600">&copy; <?= date('Y') ?> <?= esc(site_setting('site_name', 'TutorConnect Malawi')) ?>. All rights reserved.</p>
                <p class="text-center text-gray-500 mt-2 text-sm">
                    Payment is handled securely by PayChangu. TutorConnect Malawi uses this fee only to unlock and process the Japan application workflow.
                </p>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://in.paychangu.com/js/popup.js"></script>
    <script>
        function toggleOffcanvas() {
            const menu = document.getElementById('offcanvasMenu');
            const backdrop = document.getElementById('offcanvasBackdrop');

            if (!menu || !backdrop) {
                return;
            }

            menu.classList.toggle('-translate-x-full');
            backdrop.classList.toggle('hidden');
        }

        const originalJapanConsoleError = console.error;
        const originalJapanConsoleWarn = console.warn;

        console.error = function (...args) {
            if (args[0] && typeof args[0] === 'string') {
                const message = args[0].toLowerCase();
                if (message.includes('unsafe attempt to initiate navigation') ||
                    message.includes('failed to set a named property') ||
                    message.includes('minified react error') ||
                    message.includes('client-side exception') ||
                    message.includes('paychangu') ||
                    (args[0].includes('4bd1b696') && args[0].includes('js'))) {
                    return;
                }
            }

            originalJapanConsoleError.apply(console, args);
        };

        console.warn = function (...args) {
            if (args[0] && typeof args[0] === 'string') {
                const message = args[0].toLowerCase();
                if (message.includes('slow network') ||
                    message.includes('fallback font') ||
                    message.includes('paychangu')) {
                    return;
                }
            }

            originalJapanConsoleWarn.apply(console, args);
        };

        document.addEventListener('DOMContentLoaded', function () {
            const unlockForm = document.getElementById('unlockPaymentForm');
            const unlockBtn = document.getElementById('unlockPaymentBtn');
            const unlockBtnText = document.getElementById('unlockPaymentBtnText');
            const feedbackBox = document.getElementById('unlockPaymentFeedback');

            function setFeedback(message, tone) {
                feedbackBox.className = 'feedback ' + (tone || 'info');
                feedbackBox.textContent = message;
            }

            function resetButton() {
                unlockBtn.disabled = false;
                unlockBtnText.textContent = 'Pay & Unlock Application Form';
            }

            function checkJapanPaymentStatus(txRef, attempt) {
                const currentAttempt = typeof attempt === 'number' ? attempt : 0;

                if (currentAttempt >= 20) {
                    setFeedback('We are still waiting for payment confirmation. If you were charged already, use Restore Access with the same email instead of paying again.', 'info');
                    resetButton();
                    return;
                }

                fetch('<?= site_url('teach-in-japan/payment/status') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: 'tx_ref=' + encodeURIComponent(txRef)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'verified' && data.redirect) {
                        setFeedback('Payment confirmed. Opening the application form...', 'success');
                        window.location.href = data.redirect;
                        return;
                    }

                    if (data.status === 'failed') {
                        setFeedback(data.message || 'Payment was not successful. Please try again.', 'error');
                        resetButton();
                        return;
                    }

                    setFeedback('Payment received. We are still confirming your access. Please wait a moment...', 'info');
                    setTimeout(function () {
                        checkJapanPaymentStatus(txRef, currentAttempt + 1);
                    }, 2000);
                })
                .catch(function () {
                    setFeedback('We could not confirm the payment automatically just now. If you already paid, use Restore Access with the same email.', 'info');
                    resetButton();
                });
            }

            unlockForm.addEventListener('submit', async function (event) {
                event.preventDefault();

                try {
                    unlockBtn.disabled = true;
                    unlockBtnText.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right:8px;"></i>Preparing secure payment...';
                    setFeedback('Preparing your secure PayChangu checkout. Please wait...', 'info');

                    const response = await fetch('<?= site_url('teach-in-japan/access/initiate') ?>', {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: new FormData(unlockForm)
                    });

                    const result = await response.json();

                    if (!result.success) {
                        setFeedback(result.message || 'Could not start payment. Please try again.', 'error');
                        resetButton();
                        return;
                    }

                    if (result.already_paid && result.redirect) {
                        setFeedback('Payment already confirmed. Opening your application access now...', 'success');
                        window.location.href = result.redirect;
                        return;
                    }

                    if (typeof PaychanguCheckout !== 'function') {
                        console.error('PayChangu SDK not loaded');
                        setFeedback('Payment system is currently unavailable. Please refresh and try again.', 'error');
                        resetButton();
                        return;
                    }

                    setFeedback('Secure payment window opened. Complete the payment, then wait while we confirm your access.', 'info');

                    PaychanguCheckout({
                        public_key: result.paychangu_config.public_key,
                        tx_ref: result.paychangu_config.tx_ref,
                        amount: result.paychangu_config.amount,
                        currency: result.paychangu_config.currency,
                        callback_url: result.paychangu_config.callback_url,
                        return_url: result.paychangu_config.return_url,
                        customer: {
                            email: result.paychangu_config.customer.email,
                            first_name: result.paychangu_config.customer.first_name,
                            last_name: result.paychangu_config.customer.last_name
                        },
                        customizations: {
                            title: result.paychangu_config.customizations.title,
                            description: result.paychangu_config.customizations.description,
                            logo: result.paychangu_config.customizations.logo
                        },
                        callback: function (response) {
                            console.log('Japan application payment completed:', response);
                            setFeedback('Payment submitted. Confirming your access now...', 'info');
                            checkJapanPaymentStatus(result.paychangu_config.tx_ref);
                        },
                        onClose: function () {
                            console.log('Japan application payment modal closed');
                            setFeedback('Checking payment status. If you were charged, access will unlock automatically or you can restore it with your paid email.', 'info');
                            checkJapanPaymentStatus(result.paychangu_config.tx_ref);
                        }
                    });
                } catch (error) {
                    console.error('Japan application submission error:', error);
                    setFeedback('Network error. Please try again.', 'error');
                    resetButton();
                }
            });
        });
    </script>
</body>
</html>
