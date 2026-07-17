<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestWelcome extends BaseCommand
{
    protected $group = 'Testing';
    protected $name = 'test:welcome';
    protected $description = 'Test welcome email sending to verify SMTP is working';
    protected $usage = 'test:welcome';

    public function run(array $params)
    {
        $testEmail = 'rodlickndovie7@gmail.com';
        $testName = 'Rodlick';

        CLI::write('═══════════════════════════════════════', 'cyan');
        CLI::write('   Testing Welcome Email Delivery', 'yellow');
        CLI::write('═══════════════════════════════════════', 'cyan');
        CLI::newLine();
        CLI::write('To: ' . $testEmail, 'white');
        CLI::newLine();

        try {
            $emailService = \Config\Services::email();
            $emailService->setFrom('info@uprisemw.com', 'TutorConnect Malawi');
            $emailService->setTo($testEmail);
            $emailService->setSubject('Welcome to TutorConnect Malawi! 🎉');

            $welcomeMessage = "
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <style>
                    body { font-family: 'Arial', sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; margin: 0; padding: 0; }
                    .container { max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
                    .header { background: linear-gradient(135deg, #E74C3C, #C0392B); padding: 40px 30px; text-align: center; color: white; }
                    .header h1 { margin: 0; font-size: 32px; font-weight: 700; }
                    .content { padding: 40px 30px; }
                    .btn { display: inline-block; background: #E74C3C; color: white; padding: 15px 40px; text-decoration: none; border-radius: 5px; font-weight: 600; margin: 20px 0; }
                    .feature-box { background: #f8f9fa; border-left: 4px solid #E74C3C; padding: 15px 20px; margin: 15px 0; border-radius: 5px; }
                    .footer { background: #2C3E50; color: white; padding: 20px; text-align: center; font-size: 14px; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h1>🎓 Welcome to TutorConnect!</h1>
                        <p style='margin: 10px 0 0 0; font-size: 18px;'>Your Journey Begins Here</p>
                    </div>
                    <div class='content'>
                        <h2 style='color: #2C3E50;'>Hello {$testName}!</h2>
                        <p>Congratulations! Your email has been successfully verified. You're now part of Malawi's leading tutoring network.</p>

                        <div style='background: linear-gradient(135deg, #27ae60, #229954); color: white; padding: 25px; margin: 30px 0; border-radius: 8px; text-align: center;'>
                            <h3 style='margin: 0 0 10px 0; font-size: 24px;'>🎁 Special Welcome Gift!</h3>
                            <p style='margin: 0; font-size: 18px; font-weight: bold;'>Enjoy 1 Month FREE Premium Access</p>
                            <p style='margin: 10px 0 0 0; font-size: 14px; opacity: 0.9;'>Full access to all features • No credit card required</p>
                        </div>

                        <div style='text-align: center; margin: 30px 0;'>
                            <a href='" . base_url('login') . "' class='btn'>Login to Your Account</a>
                        </div>

                        <h3 style='color: #2C3E50; margin-top: 30px;'>Next Steps:</h3>

                        <div class='feature-box'>
                            <strong>1. Complete Your Profile</strong>
                            <p style='margin: 5px 0 0 0; color: #666;'>Add your bio, qualifications, and teaching experience</p>
                        </div>

                        <div class='feature-box'>
                            <strong>2. Upload Documents</strong>
                            <p style='margin: 5px 0 0 0; color: #666;'>Submit your ID, certificates, and clearances for verification</p>
                        </div>

                        <div class='feature-box'>
                            <strong>3. Set Your Availability</strong>
                            <p style='margin: 5px 0 0 0; color: #666;'>Define your subjects, rates, and teaching hours</p>
                        </div>

                        <div class='feature-box'>
                            <strong>4. Start Teaching</strong>
                            <p style='margin: 5px 0 0 0; color: #666;'>Connect with students and begin your tutoring journey</p>
                        </div>

                        <p style='margin-top: 30px; padding: 20px; background: #e8f4f8; border-radius: 5px; color: #0066a1;'>
                            <strong>💡 Pro Tip:</strong> Tutors with complete profiles and verified documents get 10x more student inquiries!
                        </p>

                        <p style='margin-top: 30px; color: #666;'>Need help? Our support team is here for you at <a href='mailto:info@uprisemw.com' style='color: #E74C3C;'>info@uprisemw.com</a></p>
                    </div>
                    <div class='footer'>
                        <p style='margin: 0;'>&copy; 2025 TutorConnect Malawi. All rights reserved.</p>
                        <p style='margin: 10px 0 0 0;'>Connecting tutors and students across Malawi</p>
                    </div>
                </div>
            </body>
            </html>
            ";

            $emailService->setMessage($welcomeMessage);

            CLI::write('Sending email...', 'yellow');

            if ($emailService->send(false)) {
                CLI::newLine();
                CLI::write('✓ Welcome email sent successfully!', 'green');
                CLI::write('  Timestamp: ' . date('Y-m-d H:i:s'), 'white');
                CLI::newLine(2);

                CLI::write('═══════════════════════════════════════', 'cyan');
                CLI::write('   Where to Check for the Email', 'yellow');
                CLI::write('═══════════════════════════════════════', 'cyan');
                CLI::newLine();
                CLI::write('1. Primary Inbox', 'white');
                CLI::write('2. Promotions Tab (Gmail)', 'white');
                CLI::write('3. Spam/Junk Folder ⚠️', 'white');
                CLI::write('4. Search: from:info@uprisemw.com', 'white');
                CLI::write('5. All Mail folder', 'white');
                CLI::newLine();

                CLI::write('SMTP Debug Output:', 'cyan');
                CLI::write($emailService->printDebugger(), 'dark_gray');
            } else {
                CLI::newLine();
                CLI::write('✗ Failed to send email!', 'red');
                CLI::newLine();
                CLI::write('SMTP Debug Output:', 'cyan');
                CLI::write($emailService->printDebugger(), 'red');
            }
        } catch (\Exception $e) {
            CLI::error('Exception occurred: ' . $e->getMessage());
            CLI::write($e->getTraceAsString(), 'red');
        }

        CLI::newLine();
    }
}
