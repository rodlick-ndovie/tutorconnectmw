<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Serve uploaded media from public/uploads when the app is reached from the project root.
$routes->add('uploads/(.*)', static function (string $path) {
    $relativePath = trim(str_replace('\\', '/', $path), '/');

    if ($relativePath === '' || str_contains($relativePath, '..')) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    $uploadsRoot = realpath(ROOTPATH . 'public/uploads');
    $requestedPath = $uploadsRoot
        ? realpath($uploadsRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath))
        : false;

    if (
        $uploadsRoot !== false
        && $requestedPath !== false
        && str_starts_with($requestedPath, $uploadsRoot . DIRECTORY_SEPARATOR)
        && is_file($requestedPath)
    ) {
        return \Config\Services::response()
            ->setHeader('Content-Type', mime_content_type($requestedPath))
            ->setHeader('Content-Length', (string) filesize($requestedPath))
            ->setBody(file_get_contents($requestedPath));
    }

    throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
});

// Serve public assets when the app is reached from the project root.
$routes->add('assets/(.*)', static function (string $path) {
    $relativePath = trim(str_replace('\\', '/', $path), '/');

    if ($relativePath === '' || str_contains($relativePath, '..')) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    $assetsRoot = realpath(ROOTPATH . 'public/assets');
    $requestedPath = $assetsRoot
        ? realpath($assetsRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath))
        : false;

    if (
        $assetsRoot !== false
        && $requestedPath !== false
        && str_starts_with($requestedPath, $assetsRoot . DIRECTORY_SEPARATOR)
        && is_file($requestedPath)
    ) {
        return \Config\Services::response()
            ->setHeader('Content-Type', mime_content_type($requestedPath))
            ->setHeader('Content-Length', (string) filesize($requestedPath))
            ->setBody(file_get_contents($requestedPath));
    }

    throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
});
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');

$routes->get('/', 'Home::index');

// TutorConnect specific routes
$routes->get('how-it-works', 'Home::howItWorks');
$routes->get('pricing', 'Home::pricing');
$routes->get('find-tutors', 'Home::findTutors');
$routes->get('request-teacher', 'ParentRequests::index');
$routes->post('request-teacher', 'ParentRequests::store');
$routes->get('request-teacher/success/(:segment)', 'ParentRequests::success/$1');
$routes->get('parent-requests/apply/(:segment)', 'ParentRequests::apply/$1');
$routes->get('tutor/(:any)', 'Home::tutorProfile/$1');
$routes->get('login', 'Home::login');
$routes->post('login', 'Auth::attemptLogin');

// Auth routes - Two-step registration
$routes->get('register', 'Auth::register');
$routes->post('register/step1', 'Auth::registerStep1');
$routes->post('register/step2', 'Auth::registerStep2');
$routes->get('register/back', 'Auth::registerBack');

// Real-time validation endpoints for registration
$routes->post('register/check-email', 'Register::checkEmail');
$routes->post('register/check-phone', 'Register::checkPhone');
$routes->post('register/check-username', 'Register::checkUsername');

$routes->get('verify-email', 'Auth::verifyEmail');
$routes->post('confirm-email', 'Auth::confirmEmail');
$routes->post('resend-otp', 'Auth::resendOtp');

// Notice Board routes
$routes->get('notice', 'Notice::index');
$routes->get('notice/create', 'Notice::create');
$routes->post('notice/store', 'Notice::store');
$routes->get('notice/success', 'Notice::success');
$routes->get('notice/view/(:num)', 'Notice::view/$1');

// Admin notice management routes
$routes->get('notice/admin/pending', 'Notice::adminPending');
$routes->get('notice/admin/all', 'Notice::adminAll');
$routes->post('notice/approve/(:num)', 'Notice::approve/$1');
$routes->post('notice/reject/(:num)', 'Notice::reject/$1');
$routes->post('notice/delete/(:num)', 'Notice::delete/$1');

// Multi-step tutor registration
$routes->get('tutor-register', 'Auth::tutorRegister');
$routes->post('auth/process-tutor-registration-step', 'Auth::processTutorRegistrationStep');

$routes->post('register/tutor', 'Auth::attemptTutorRegister');

$routes->get('logout', 'Auth::logout');

// Redirect /dashboard to /trainer/dashboard for authenticated users
$routes->get('dashboard', function() {
    if (!session()->get('user_id')) {
        return redirect()->to('/login');
    }
    return redirect()->to('/trainer/dashboard');
});

// Document resubmission (no login required - token-based)
$routes->get('resubmit-documents', 'Resubmit::index');
$routes->post('resubmit-documents/submit', 'Resubmit::submit');

$routes->get('verify-email', 'Auth::verifyEmail');
$routes->post('verify-email', 'Auth::confirmEmail');
$routes->post('resend-otp', 'Auth::resendOtp');

// API endpoint for curriculum data
$routes->get('api/curriculum-data', 'Auth::getCurriculumData');
$routes->get('api/curriculum/levels', 'Api::getCurriculumLevels');
$routes->get('api/curriculum/subjects', 'Api::getCurriculumSubjects');

// Bio video upload for registration
$routes->post('auth/uploadBioVideoForRegistration', 'Auth::uploadBioVideoForRegistration');

// Real-time validation endpoint
$routes->post('auth/check-availability', 'Auth::checkAvailability');

$routes->get('forgot-password', 'Auth::forgotPassword');
$routes->post('forgot-password', 'Auth::requestPasswordReset');

$routes->get('reset-password', 'Auth::resetPassword');
$routes->post('reset-password', 'Auth::updatePassword');



$routes->get('training', 'Training::index');
$routes->get('training/courses', 'Training::courses');
$routes->get('training/course/(:num)', 'Training::viewCourse/$1');

$routes->get('portfolio', 'Portfolio::index');
$routes->get('portfolio/project/(:num)', 'Portfolio::project/$1');
$routes->get('teach-in-japan', 'Japan::index');
$routes->get('teach-in-japan/apply', 'Japan::application');
$routes->post('teach-in-japan/access/initiate', 'Japan::initiatePayment');
$routes->get('teach-in-japan/access/reset', 'Japan::resetAccess');
$routes->post('teach-in-japan/access/restore', 'Japan::restoreAccess');
$routes->match(['GET', 'POST'], 'teach-in-japan/status', 'Japan::status');
$routes->post('teach-in-japan/payment/status', 'Japan::checkPaymentStatus');
$routes->add('teach-in-japan/payment/callback', 'Japan::paymentCallback');
$routes->get('teach-in-japan/payment/return', 'Japan::paymentReturn');
$routes->post('teach-in-japan/apply', 'Japan::submit');

$routes->get('about', 'Home::about');

$routes->get('about', 'About::index');

$routes->get('contact', 'Contact::index');
$routes->post('contact/send', 'Contact::send');

$routes->post('home/submitReview', 'Home::submitReview');
$routes->post('home/sendMessage', 'Home::sendMessage');

$routes->get('privacy-policy', 'Home::privacyPolicy');
$routes->get('terms-of-service', 'Home::termsOfService');
$routes->get('refund-policy', 'Home::refundPolicy');
$routes->get('verification-process', 'Home::verificationProcess');
$routes->get('child-safeguarding', 'Home::childSafeguarding');
$routes->get('document-resubmission/(:num)', 'Auth::documentResubmission/$1');
$routes->post('document-resubmission/submit', 'Auth::submitResubmission');
$routes->post('contact/send', 'Contact::send');

// Resources Section - Unified Page with Filtering
$routes->group('resources', static function ($routes) {
    $routes->get('/', 'Resources::index');
    $routes->get('past-papers', 'Resources::pastPapers');
    $routes->get('past-papers/pay/(:num)', 'Resources::pastPaperCheckout/$1');
    $routes->get('past-papers/download/(:num)', 'Resources::downloadPaper/$1');
    $routes->post('past-papers/access/restore', 'Resources::restorePastPaperAccess');
    $routes->post('past-papers/payment/initiate', 'Resources::initiatePastPaperPayment');
    $routes->post('past-papers/payment/status', 'Resources::checkPastPaperPaymentStatus');
    $routes->get('past-papers/payment/return', 'Resources::pastPaperPaymentReturn');
    $routes->get('past-papers/payment/success', 'Resources::pastPaperPaymentSuccess');
    $routes->match(['GET', 'POST'], 'past-papers/payment/callback', 'Resources::pastPaperPaymentCallback');
    $routes->get('video-solutions', 'Resources::videoSolutions');
    $routes->get('video/(:num)', 'Resources::viewVideo/$1');
    $routes->post('trackContactClick', 'Resources::trackContactClick');
});

    // Trainer Resources
    $routes->get('trainer/resources/upload', 'Trainer::uploadResources', ['filter' => 'auth']);
    $routes->post('upload-paper', 'Trainer::processResourceUpload', ['filter' => 'auth']);
    $routes->get('trainer/resources/my-papers', 'Trainer::myPapers', ['filter' => 'auth']);

    // Trainer Video Solutions
    $routes->get('trainer/resources/upload-video-solutions', 'Trainer::uploadVideoSolutions', ['filter' => 'auth']);
    $routes->post('trainer/resources/process-video-solution-upload', 'Trainer::processVideoSolutionUpload', ['filter' => 'auth']);
    $routes->get('trainer/resources/my-video-solutions', 'Trainer::myVideoSolutions', ['filter' => 'auth']);

    // Trainer Notices/Announcements
    $routes->get('trainer/notices', 'Notice::trainerIndex', ['filter' => 'auth']);
    $routes->get('trainer/notices/create', 'Notice::trainerCreate', ['filter' => 'auth']);
    $routes->post('trainer/notices/store', 'Notice::trainerStore', ['filter' => 'auth']);
    $routes->get('trainer/notices/edit/(:num)', 'Notice::trainerEdit/$1', ['filter' => 'auth']);
    $routes->post('trainer/notices/update/(:num)', 'Notice::trainerUpdate/$1', ['filter' => 'auth']);
    $routes->post('trainer/notices/delete/(:num)', 'Notice::trainerDelete/$1', ['filter' => 'auth']);

// Legacy routes for backward compatibility
$routes->get('resources/past-papers', 'Resources::pastPapers');
$routes->get('resources/video-solutions', 'Resources::videoSolutions');

    // Trainer Routes
    $routes->group('trainer', ['filter' => 'auth'], static function ($routes) {
        $routes->post('send-message', 'Trainer::sendMessage');
        $routes->get('dashboard', 'Dashboard::index');
        $routes->get('dashboard/complete-profile', 'Dashboard::completeProfile');
        $routes->post('dashboard/submit-complete-profile', 'Dashboard::submitCompleteProfile');
        $routes->post('dashboard/upload-documents', 'Dashboard::uploadDocuments');
        $routes->get('profile', 'Trainer::profile');
        $routes->get('profile/edit', 'Trainer::editProfile');
        $routes->post('profile/update', 'Trainer::updateProfile');
        $routes->post('updatePersonal', 'Trainer::updatePersonal');
        $routes->post('updateProfessional', 'Trainer::updateProfessional');
        $routes->post('updatePreferences', 'Trainer::updatePreferences');
        $routes->post('changePassword', 'Trainer::changePassword');
        $routes->post('updatePassword', 'Trainer::changePassword');
        $routes->get('availability', 'Trainer::availability');
        $routes->post('availability/update', 'Trainer::updateAvailability');
        $routes->get('pricing', 'Trainer::pricing');
        $routes->post('pricing/update', 'Trainer::updatePricing');
        $routes->get('inquiries', 'Trainer::inquiries');
        $routes->get('analytics', 'Trainer::analytics');
        $routes->get('credentials', 'Trainer::credentials');
        $routes->post('credentials/upload', 'Trainer::uploadCredential');
        $routes->get('subscription', 'Trainer::subscription');
        $routes->post('subscription/update', 'Trainer::updateSubscription');
        $routes->post('subscribe/(:num)', 'Trainer::subscribe/$1');
        $routes->get('subjects', 'Trainer::subjects');
        $routes->post('subjects/update', 'Trainer::updateSubjects');

        // Video submission routes (subscription-tiered access)
        $routes->get('submit-video', 'Trainer::submitVideo');
        $routes->post('submit-video', 'Trainer::processVideoSubmission');

        $routes->get('reports', 'Trainer::reports');

        // Checkout routes within trainer group
        $routes->get('checkout/subscription/(:num)', 'Checkout::subscription/$1');
        $routes->post('checkout/process-subscription', 'Checkout::processSubscription');

        // PayChangu payment routes within trainer group
        $routes->get('checkout/paychangu/return', 'Checkout::paychanguReturn');
        $routes->post('checkout/checkPaymentStatus', 'Checkout::checkPaymentStatus');
    });

// PayChangu callback - No auth required (called by PayChangu servers)
$routes->match(['GET', 'POST'], 'checkout/paychangu/callback', 'Checkout::paychanguCallback');

// Admin Routes
$routes->group('admin', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'Dashboard::index');
    $routes->get('dashboard', 'Dashboard::index');


    $routes->get('users', 'Admin::users');
    $routes->get('users/export', 'Admin::exportUsersExcel');
    $routes->get('users/export-pdf', 'Admin::exportUsersPdf');
    $routes->post('check-field', 'Admin::checkField');
    $routes->post('create-admin', 'Admin::createAdmin');
    $routes->post('create-trainer', 'Admin::createTrainer');
    $routes->get('trainers', 'Admin::trainers');
    $routes->get('renewal-management', 'Admin::renewalManagement');
    $routes->get('services', 'Admin::services');

    $routes->get('settings', 'Admin::settings');
    $routes->post('settings', 'Admin::updateSettings');

    // Contact message management
    $routes->get('contact-messages', 'Admin::contact_messages');
    $routes->get('parent-requests', 'Admin::parentRequests');
    $routes->get('parent-requests/(:num)', 'Admin::viewParentRequest/$1');
    $routes->get('japan-applications', 'Admin::japan_applications');
    $routes->get('japan-payments', 'Admin::japan_payments');
    $routes->get('past-paper-payments', 'Admin::pastPaperPayments');
    $routes->get('japan-applications/export', 'Admin::exportJapanApplicationsExcel');
    $routes->get('japan-applications/export-pdf', 'Admin::exportJapanApplicationsPdf');
    $routes->get('japan-applications/(:num)/pdf', 'Admin::downloadJapanApplicationPdf/$1');
    $routes->get('japan-applications/(:num)', 'Admin::view_japan_application/$1');
    $routes->post('japan-applications/update/(:num)', 'Admin::update_japan_application/$1');
    $routes->get('view-message/(:num)', 'Admin::view_message/$1');
    $routes->post('delete-message/(:num)', 'Admin::delete_message/$1');

    // Library/Resources management
    $routes->get('library', 'Admin::resources');
    $routes->get('library/add', 'Admin::add_resource');
    $routes->post('library/add', 'Admin::add_resource');
    $routes->post('library/add-video', 'Admin::add_video');
    $routes->post('library/get-levels', 'Admin::get_levels');
    $routes->post('library/get-subjects', 'Admin::get_subjects');
    $routes->get('library/get/(:num)/(:segment)', 'Admin::get_resource/$1/$2');
    $routes->get('library/get/(:num)', 'Admin::get_resource/$1');
    $routes->post('library/update', 'Admin::update_resource');
    $routes->get('library/edit/(:num)', 'Admin::edit_resource/$1');
    $routes->post('library/edit/(:num)', 'Admin::edit_resource/$1');
    $routes->post('library/delete/(:num)/(:segment)', 'Admin::delete_resource/$1/$2');
    $routes->post('library/delete/(:num)', 'Admin::delete_resource/$1');

    // Video approval queue
    $routes->get('video-queue', 'Admin::videoQueue');
    $routes->post('video/approve/(:num)', 'Admin::approveVideo/$1');
    $routes->post('video/reject/(:num)', 'Admin::rejectVideo/$1');

    // Video approval routes for library table (shorter URLs)
    $routes->post('approve-video/(:num)', 'Admin::approveVideo/$1');
    $routes->post('reject-video/(:num)', 'Admin::rejectVideo/$1');

    // Subscription management routes
    $routes->get('subscriptions', 'Admin::subscriptions');
    $routes->get('subscriptions/add', 'Admin::addPlan');
    $routes->post('subscriptions/create', 'Admin::createPlan');
    $routes->get('subscriptions/edit/(:num)', 'Admin::editPlan/$1');
    $routes->get('subscriptions/get/(:num)', 'Admin::getPlan/$1');
    $routes->post('subscriptions/update/(:num)', 'Admin::updatePlan/$1');
    $routes->post('subscriptions/delete/(:num)', 'Admin::deletePlan/$1');

    // Database backup routes
    $routes->get('backup', 'Admin::backup');
    $routes->post('backup/create', 'Admin::createBackup');
    $routes->get('backup/download/(:segment)', 'Admin::downloadBackup/$1');
    $routes->post('backup/delete/(:segment)', 'Admin::deleteBackup/$1');

    // Tutor subscriptions management
    $routes->get('tutor-subscriptions', 'Admin::tutorSubscriptions');
    $routes->post('tutor-subscriptions/assign', 'Admin::assignSubscription');
    $routes->post('tutor-subscriptions/update-status/(:num)', 'Admin::updateSubscriptionStatus/$1');
    $routes->post('tutor-subscriptions/remove/(:num)', 'Admin::removeSubscription/$1');
    $routes->get('tutor-subscriptions/view-proof/(:num)', 'Admin::viewPaymentProof/$1');
    $routes->get('subscriptions/edit/(:num)', 'Admin::editPlan/$1');
    $routes->get('subscriptions/plan-subscribers/(:num)', 'Admin::planSubscribers/$1');



    // Tutor management routes
    $routes->get('trainers/view/(:num)', 'Admin::viewTutor/$1');
    $routes->get('trainers/export', 'Admin::exportTutorsExcel');
    $routes->get('trainers/export-pdf', 'Admin::exportTutorsPdf');
    $routes->post('trainers/approve/(:num)', 'Admin::approveTutor/$1');
    $routes->post('trainers/reject/(:num)', 'Admin::rejectTutor/$1');
    $routes->post('trainers/suspend/(:num)', 'Admin::suspendTutor/$1');
    $routes->post('trainers/activate/(:num)', 'Admin::activateTutor/$1');
    $routes->post('trainers/delete/(:num)', 'Admin::deleteTutor/$1');
    $routes->post('trainers/delete-permanently/(:num)', 'Admin::deleteTutorPermanently/$1');
    $routes->post('trainers/request-resubmission/(:num)', 'Admin::requestDocumentResubmission/$1');

    // Structured subjects management routes


    // Video management routes
    $routes->get('video-queue', 'Admin::videoQueue');
    $routes->post('videos/approve/(:num)', 'Admin::approveVideo/$1');
    $routes->post('videos/reject/(:num)', 'Admin::rejectVideo/$1');



    // Library management routes (Past Papers)
    $routes->get('library', 'Admin::library');
    $routes->post('library/add', 'Admin::addPaper');
    $routes->post('library/update/(:num)', 'Admin::updatePaper/$1');
    $routes->post('library/delete/(:num)', 'Admin::deletePaper/$1');
    $routes->post('library/toggle-status/(:num)', 'Admin::togglePaperStatus/$1');
    $routes->get('library/test-insert', 'Admin::testInsertPaper');

    // Curriculum management routes
    $routes->get('curriculum', 'Admin::curriculum');
    $routes->get('curriculum/add', 'Admin::addCurriculum');
    $routes->post('curriculum/create', 'Admin::createCurriculum');
    $routes->get('curriculum/edit/(:num)', 'Admin::editCurriculum/$1');
    $routes->post('curriculum/update/(:num)', 'Admin::updateCurriculum/$1');
    $routes->post('curriculum/delete/(:num)', 'Admin::deleteCurriculum/$1');
    $routes->post('curriculum/toggle-status/(:num)', 'Admin::toggleCurriculumStatus/$1');
    $routes->post('curriculum/bulk-action', 'Admin::bulkCurriculumAction');
});

// Database Inspector Routes (REMOVE IN PRODUCTION!)
$routes->get('database-inspector', 'DatabaseInspector::index');
$routes->get('database-inspector/table/(:segment)', 'DatabaseInspector::showTable/$1');
$routes->get('database-inspector/check', 'DatabaseInspector::checkConnection');

// Show all database data (REMOVE IN PRODUCTION!)
$routes->get('show-all-data', 'Auth::showAllData');
$routes->get('test-registration', 'Auth::testRegistration');
$routes->get('show-all-data', 'Auth::showAllData');
$routes->get('test-registration', 'Auth::testRegistration');
