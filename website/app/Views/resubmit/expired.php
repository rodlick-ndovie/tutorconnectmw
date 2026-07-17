<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link Expired - TutorConnect Malawi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        body {
            background: linear-gradient(135deg, #E55C0D 0%, #C94609 50%, #A93706 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
        }
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="100" height="100" patternUnits="userSpaceOnUse"><path d="M 100 0 L 0 0 0 100" fill="none" stroke="rgba(255,255,255,0.03)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.5;
        }
        .error-card {
            max-width: 550px;
            width: 100%;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 50px 40px;
            text-align: center;
            position: relative;
            z-index: 1;
            animation: fadeInUp 0.8s ease-out;
        }
        .error-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #FFF3E0 0%, #FFE0B2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
        }
        .error-icon i {
            font-size: 50px;
            color: #E74C3C;
        }
        h2 {
            color: #2C3E50;
            font-weight: 700;
            margin-bottom: 15px;
            font-size: 28px;
        }
        .message {
            color: #546E7A;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 25px;
        }
        .contact-info {
            background: linear-gradient(135deg, #E3F2FD 0%, #BBDEFB 100%);
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            border-left: 5px solid #2196F3;
        }
        .contact-info p {
            margin: 0;
            color: #0D47A1;
            font-weight: 500;
        }
        .contact-info strong {
            color: #E74C3C;
            font-weight: 700;
        }
        .btn-home {
            background: linear-gradient(135deg, #E74C3C 0%, #C0392B 100%);
            color: white;
            padding: 14px 40px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 8px 20px rgba(231, 76, 60, 0.3);
            transition: all 0.3s ease;
        }
        .btn-home:hover {
            background: linear-gradient(135deg, #C0392B 0%, #A93226 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(231, 76, 60, 0.4);
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h2>Link Expired</h2>
        <p class="message"><?php echo esc($message); ?></p>
        <div class="contact-info">
            <p>Please contact our admin team at <strong>info@uprisemw.com</strong> to request a new resubmission link.</p>
        </div>
        <a href="<?php echo base_url(); ?>" class="btn-home">
            <i class="fas fa-home me-2"></i> Return to Home
        </a>
    </div>
</body>
</html>
