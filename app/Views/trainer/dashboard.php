<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#E55C0D">
    <title>Dashboard - TutorConnect Malawi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #E55C0D;
            --secondary-color: #C94609;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --accent-color: #0ea5e9;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --bg-primary: #f8fafc;
            --bg-secondary: #ffffff;
            --bg-accent: #f0f9ff;
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --border-radius: 16px;
            --border-radius-lg: 20px;
        }

        * { box-sizing: border-box; }

        body {
            background: var(--bg-primary);
            font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'SF Pro Text', 'Segoe UI', Roboto, sans-serif;
            margin: 0;
            padding: 0;
        }

        .app-container {
            width: 100%;
            max-width: 480px;
            margin: 0 auto;
            min-height: 100vh;
            background: var(--bg-primary);
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
        }

        @media (min-width: 768px) {
            .app-container { max-width: 100%; box-shadow: none; }
        }

        .status-bar { height: 0; background: var(--bg-secondary); border-bottom: 1px solid rgba(0,0,0,0.1); position: sticky; top: 0; z-index: 100; }
        @media (min-width: 768px) { .status-bar { display: none; } }
        @media (max-width: 767px) { .status-bar { display: none; } }

        .main-content {
            padding: 16px;
            padding-bottom: 100px; /* Space for bottom nav */
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
            max-width: 100%;
            background: var(--bg-secondary);
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            gap: 8px;
            padding: 10px 14px;
            z-index: 100;
        }

        @media (min-width: 768px) {
            .bottom-nav { gap: 12px; }
        }

        .nav-item {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: var(--text-light);
            transition: all 0.3s ease;
            padding: 6px 4px;
            border-radius: 12px;
        }

        .nav-item:hover {
            background: rgba(0, 0, 0, 0.05);
        }

        .nav-item.active {
            color: var(--primary-color);
        }

        .navbar {
            background: var(--bg-secondary);
            border-bottom: 1px solid rgba(0,0,0,0.1);
            padding: 12px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 99;
        }

        .nav-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-dark);
            margin: 0;
        }

        .nav-button {
            background: none;
            border: none;
            color: var(--primary-color);
            font-size: 16px;
            padding: 8px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .nav-button:hover {
            background: rgba(5, 150, 105, 0.1);
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 16px;
        }

        .content {
            padding: 20px 16px;
            padding-bottom: 100px;
        }

        .card {
            background: var(--bg-secondary);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            margin-bottom: 16px;
            overflow: hidden;
        }

        .card-header {
            padding: 16px;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-dark);
            margin: 0;
        }

        .card-content {
            padding: 16px;
        }

        .page-header {
            margin-bottom: 20px;
        }

        .page-title {
            font-size: 26px;
            font-weight: 800;
            color: var(--text-dark);
            margin: 0;
        }

        .page-subtitle {
            color: var(--text-light);
            margin: 6px 0 0;
            font-size: 14px;
        }

        .welcome-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: var(--border-radius-lg);
            padding: 2rem 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow);
        }

        .welcome-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .welcome-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1rem;
        }

        .stats-section {
            margin-bottom: 2rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        .stats-card {
            background: var(--bg-secondary);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--shadow);
            border: 1px solid rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
        }

        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px -8px rgba(0, 0, 0, 0.15);
        }

        .stats-icon {
            width: 56px;
            height: 56px;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
            background: linear-gradient(135deg, rgba(229, 92, 13, 0.1), rgba(14, 165, 233, 0.1));
            color: var(--primary-color);
            position: relative;
        }

        .stats-icon::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            background: linear-gradient(135deg, rgba(229, 92, 13, 0.05), rgba(14, 165, 233, 0.05));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .stats-card:hover .stats-icon::after {
            opacity: 1;
        }

        .stats-value {
            font-size: 2rem;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            line-height: 1;
        }

        .stats-label {
            font-size: 0.9rem;
            color: var(--text-light);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stats-change {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 0.5rem;
            font-size: 0.8rem;
        }

        .stats-change.positive {
            color: var(--success-color);
        }

        .stats-change.negative {
            color: var(--danger-color);
        }

        .stats-change i {
            margin-right: 0.25rem;
        }

        /* Performance metrics section */
        .performance-section {
            background: linear-gradient(135deg, var(--bg-secondary), rgba(229, 92, 13, 0.02));
            border-radius: var(--border-radius-lg);
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(229, 92, 13, 0.1);
        }

        .performance-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .performance-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0;
        }

        .performance-subtitle {
            color: var(--text-light);
            font-size: 0.9rem;
            margin: 0.25rem 0 0 0;
        }

        .performance-period {
            font-size: 0.8rem;
            color: var(--text-light);
            background: var(--bg-accent);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
        }

        .performance-metrics {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 1.5rem;
        }

        .metric-item {
            text-align: center;
        }

        .metric-value {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--primary-color);
            line-height: 1;
        }

        .metric-label {
            font-size: 0.8rem;
            color: var(--text-light);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 0.25rem;
        }

        .metric-change {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 0.5rem;
            font-size: 0.75rem;
        }

        .metric-change.positive {
            color: var(--success-color);
        }

        .metric-change.negative {
            color: var(--danger-color);
        }

        .metric-change i {
            margin-right: 0.25rem;
        }

        .actions-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }

        .action-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .action-card {
            background: var(--bg-secondary);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--shadow);
            text-align: center;
            border: 1px solid rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .action-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px -8px rgba(0, 0, 0, 0.1);
        }

        .action-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.25rem;
        }

        .action-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .action-description {
            font-size: 0.875rem;
            color: var(--text-light);
            margin-bottom: 1rem;
        }

        .btn-action {
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-action:hover {
            background: var(--secondary-color);
            transform: translateY(-1px);
        }

        /* Toast notifications */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }

        .toast {
            background: var(--bg-secondary);
            border-radius: var(--border-radius);
            padding: 1rem;
            margin-bottom: 0.5rem;
            box-shadow: var(--shadow);
            border-left: 4px solid var(--primary-color);
            max-width: 300px;
        }

        .toast.success { border-left-color: var(--success-color); }
        .toast.error { border-left-color: var(--danger-color); }
        .toast.warning { border-left-color: var(--warning-color); }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .action-grid {
                grid-template-columns: 1fr;
            }
        }

        .status-card {
            border-left: 4px solid var(--primary-color);
            margin-bottom: 1.5rem;
        }

        .status-display {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px;
            border-radius: 12px;
        }

        .status-success {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.05));
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .status-warning {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(245, 158, 11, 0.05));
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .status-info {
            background: linear-gradient(135deg, rgba(14, 165, 233, 0.1), rgba(14, 165, 233, 0.05));
            border: 1px solid rgba(14, 165, 233, 0.2);
        }

        .status-danger {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(239, 68, 68, 0.05));
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .status-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .status-success .status-icon {
            background: rgba(16, 185, 129, 0.15);
            color: var(--success-color);
        }

        .status-warning .status-icon {
            background: rgba(245, 158, 11, 0.15);
            color: var(--warning-color);
        }

        .status-info .status-icon {
            background: rgba(14, 165, 233, 0.15);
            color: var(--accent-color);
        }

        .status-danger .status-icon {
            background: rgba(239, 68, 68, 0.15);
            color: var(--danger-color);
        }

        .status-content {
            flex: 1;
        }

        .status-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 4px;
        }

        .status-message {
            font-size: 0.9rem;
            color: var(--text-light);
            line-height: 1.4;
        }

        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Modern Plan Status Styles */
        .plan-status-modern {
            background: linear-gradient(135deg, var(--bg-secondary), rgba(229, 92, 13, 0.02));
            border-radius: var(--border-radius-lg);
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(229, 92, 13, 0.1);
            box-shadow: var(--shadow);
        }

        .plan-status-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .plan-status-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            box-shadow: 0 8px 25px rgba(229, 92, 13, 0.3);
        }

        .plan-status-content {
            flex: 1;
        }

        .plan-status-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0 0 0.25rem 0;
        }

        .plan-status-subtitle {
            font-size: 0.9rem;
            color: var(--text-light);
            margin: 0;
        }

        .plan-status-badge {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, var(--success-color), #059669);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-size: 0.8rem;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        .plan-status-badge i {
            font-size: 0.9rem;
        }

        .plan-details-card {
            background: var(--bg-secondary);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(0,0,0,0.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .plan-name-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .plan-name {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        .plan-name i {
            color: var(--primary-color);
            font-size: 1.2rem;
        }

        .plan-tag {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .plan-metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 1rem;
        }

        .metric-card {
            background: linear-gradient(135deg, var(--bg-primary), rgba(255, 255, 255, 0.8));
            border-radius: var(--border-radius);
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 0.875rem;
            border: 1px solid rgba(0,0,0,0.03);
            transition: all 0.3s ease;
        }

        .metric-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .metric-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .metric-content {
            flex: 1;
        }

        .metric-value {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-dark);
            line-height: 1.2;
            margin-bottom: 0.25rem;
        }

        .metric-label {
            font-size: 0.8rem;
            color: var(--text-light);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .usage-metrics {
            background: var(--bg-secondary);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .usage-metrics h5 {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }

        .usage-item {
            margin-bottom: 1rem;
        }

        .usage-item:last-child {
            margin-bottom: 0;
        }

        .usage-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .usage-label i {
            font-size: 1rem;
        }

        .usage-count {
            font-size: 0.9rem;
            color: var(--text-light);
        }

        .plan-features {
            background: var(--bg-secondary);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .plan-features h5 {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }

        .feature-item {
            font-size: 0.85rem;
            color: var(--text-light);
            margin-bottom: 0.75rem;
        }

        .feature-item:last-child {
            margin-bottom: 0;
        }

        .feature-item i {
            margin-right: 0.5rem;
            width: 16px;
            text-align: center;
        }

        @media (max-width: 480px) {
            .plan-status-header {
                flex-direction: column;
                text-align: center;
                gap: 0.75rem;
            }

            .plan-metrics-grid {
                grid-template-columns: 1fr;
            }

            .plan-name-section {
                flex-direction: column;
                gap: 0.75rem;
                text-align: center;
            }
        }

        /* Rating Bar Styles */
        .rating-bar {
            width: 100%;
            height: 8px;
            background: rgba(0,0,0,0.1);
            border-radius: 4px;
            overflow: hidden;
            position: relative;
        }

        .rating-fill {
            height: 100%;
            background: linear-gradient(90deg, #f59e0b, #f97316, #ea580c);
            border-radius: 4px;
            transition: width 0.3s ease;
        }

        /* What's Not Included section styles */
        .not-included-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .not-included-title i {
            color: var(--danger-color);
            font-size: 0.9rem;
        }

        .not-included-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .not-included-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
        }

        .not-included-item i {
            color: var(--danger-color);
            font-size: 0.8rem;
            flex-shrink: 0;
        }

        .not-included-item span {
            color: var(--text-secondary);
        }

        .not-included-item.all-included i {
            color: var(--success-color);
        }

        .not-included-item.all-included span {
            color: var(--success-color);
            font-style: italic;
        }

        /* Subjects Display Styles */
        .subjects-display {
            max-width: 100%;
        }

        .curriculum-section {
            border: 1px solid rgba(0,0,0,0.05);
            border-radius: var(--border-radius);
            padding: 1rem;
            margin-bottom: 1rem;
            background: var(--bg-secondary);
        }

        .curriculum-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .curriculum-title i {
            color: var(--secondary-color);
        }

        .level-section {
            margin-bottom: 1rem;
        }

        .level-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .level-title i {
            color: var(--accent-color);
            font-size: 0.9rem;
        }

        .subjects-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .subject-tag {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.25rem;
            box-shadow: 0 2px 8px rgba(229, 92, 13, 0.2);
            transition: all 0.3s ease;
        }

        .subject-tag:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(229, 92, 13, 0.3);
        }

        .subject-tag i {
            font-size: 0.7rem;
            opacity: 0.9;
        }

        @media (max-width: 480px) {
            .subjects-tags {
                gap: 0.375rem;
            }

            .subject-tag {
                font-size: 0.75rem;
                padding: 0.2rem 0.6rem;
            }
        }

        /* Feature Upgrade Cards Styles */
        .feature-upgrade-card {
            background: var(--bg-secondary);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--shadow);
            text-align: center;
            border: 1px solid rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }

        .feature-upgrade-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px -8px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            width: 56px;
            height: 56px;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
        }

        .feature-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.75rem;
        }

        .feature-description {
            font-size: 0.9rem;
            color: var(--text-light);
            margin-bottom: 1.25rem;
            line-height: 1.4;
        }

        /* Feature icon theme colors */
        .pdf-feature {
            background: rgba(229, 92, 13, 0.1);
            color: var(--primary-color);
        }

        .announcement-feature {
            background: rgba(14, 165, 233, 0.1);
            color: var(--accent-color);
        }

        /* Theme-based action icons */
        .theme-primary {
            background: rgba(229, 92, 13, 0.1);
            color: var(--primary-color);
        }

        .theme-secondary {
            background: rgba(201, 70, 9, 0.1);
            color: var(--secondary-color);
        }

        .theme-success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }

        .theme-warning {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
        }

        .theme-accent {
            background: rgba(14, 165, 233, 0.1);
            color: var(--accent-color);
        }

        /* Theme-based buttons */
        .btn-theme-primary {
            background: var(--primary-color);
            color: white;
            border: none;
        }

        .btn-theme-primary:hover {
            background: var(--secondary-color);
        }

        .btn-theme-secondary {
            background: var(--secondary-color);
            color: white;
            border: none;
        }

        .btn-theme-secondary:hover {
            background: #C94609;
        }

        .btn-theme-success {
            background: var(--success-color);
            color: white;
            border: none;
        }

        .btn-theme-success:hover {
            background: #059669;
        }

        .btn-theme-warning {
            background: var(--warning-color);
            color: white;
            border: none;
        }

        .btn-theme-warning:hover {
            background: #f59e0b;
        }

        .btn-theme-accent {
            background: var(--accent-color);
            color: white;
            border: none;
        }

        .btn-theme-accent:hover {
            background: #0ea5e9;
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Mobile Status Bar Simulation -->
        <div class="status-bar"></div>

        <!-- Navigation Bar -->
        <nav class="navbar">
            <div class="d-flex align-items-center justify-content-between w-100 px-3">
                <div class="d-flex align-items-center">
                    <h1 class="nav-title mb-0 me-3">Dashboard</h1>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn p-0 border-0 bg-transparent nav-button" onclick="window.location.href='<?php echo base_url('logout'); ?>'">
                        <i class="fas fa-sign-out-alt text-muted" style="font-size: 20px;"></i>
                    </button>
                    <div class="avatar">
                        <?= strtoupper(substr(session()->get('first_name') ?? 'T', 0, 1)) ?>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="content">
            <div class="fade-in">
                <!-- Page Header -->
                <div class="page-header">
                    <h1 class="page-title">Welcome back!</h1>
                    <p class="page-subtitle">Here's an overview of your tutoring activity</p>
                </div>

                <!-- Welcome Section -->
                <div class="welcome-section">
                    <h2 class="welcome-title">Hello <?= esc($user['first_name'] ?? 'Tutor') ?>! 👋</h2>
                    <p class="welcome-subtitle">Ready to make a difference in education today?</p>
                </div>

                <!-- Status Display (moved to top) -->
                <?php
                $statusMessage = '';
                $statusColor = '';
                $statusIcon = '';

                $tutorStatus = $user['tutor_status'] ?? null;
                $isVerified = $user['is_verified'] ?? 0;

                if ($tutorStatus === 'pending') {
                    $statusMessage = 'Your application is under review. The system will start working once approved.';
                    $statusColor = 'info';
                    $statusIcon = 'fa-clock';
                } elseif ($tutorStatus === 'suspended') {
                    $statusMessage = 'Your account is temporarily suspended. Contact support for details.';
                    $statusColor = 'danger';
                    $statusIcon = 'fa-exclamation-triangle';
                } elseif ($tutorStatus === 'rejected') {
                    $statusMessage = 'Your application was not approved. Contact support for feedback.';
                    $statusColor = 'danger';
                    $statusIcon = 'fa-times-circle';
                } elseif ($tutorStatus === 'approved' || $tutorStatus === 'active') {
                    // Don't show status message for active accounts to save space
                    $statusMessage = '';
                    $statusColor = '';
                    $statusIcon = '';
                } elseif ($isVerified != 1) {
                    $statusMessage = 'Your account is approved but requires final verification. Please complete your profile setup.';
                    $statusColor = 'warning';
                    $statusIcon = 'fa-exclamation-triangle';
                } else {
                    $statusMessage = 'Your application is being processed. You will be notified once approved.';
                    $statusColor = 'warning';
                    $statusIcon = 'fa-info-circle';
                }
                ?>

                <?php if ($statusMessage): ?>
                <div class="card status-card">
                    <div class="card-content">
                        <div class="status-display status-<?= $statusColor ?>">
                            <div class="status-icon">
                                <i class="fas <?= $statusIcon ?>"></i>
                            </div>
                            <div class="status-content">
                                <div class="status-title">
                                    <?php
                                    switch($statusColor) {
                                        case 'success': echo 'Account Active'; break;
                                        case 'warning': echo 'Application Status'; break;
                                        case 'info': echo 'Under Review'; break;
                                        case 'danger': echo 'Account Issue'; break;
                                        default: echo 'Status';
                                    }
                                    ?>
                                </div>
                                <div class="status-message"><?= $statusMessage ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>







                <!-- Professional Plan Status (only for approved tutors) -->
                <?php if (($tutorStatus === 'approved' || $tutorStatus === 'active') && $isVerified == 1): ?>
                <!-- Modern Plan Status Card -->
                <div class="plan-status-modern">
                    <div class="plan-status-header">
                        <div class="plan-status-icon">
                            <i class="fas fa-crown"></i>
                        </div>
                        <div class="plan-status-content">
                            <h3 class="plan-status-title">Professional Plan Status</h3>
                            <p class="plan-status-subtitle">Your premium subscription overview</p>
                        </div>
                        <div class="plan-status-badge">
                            <i class="fas fa-check-circle"></i>
                            <span>Active</span>
                        </div>
                    </div>

                    <?php if (isset($subscription_status) && $subscription_status === 'subscribed' && isset($current_subscription) && $current_subscription): ?>
                        <!-- Premium Plan Details -->
                        <div class="plan-details-card">
                            <div class="plan-name-section">
                                <div class="plan-name">
                                    <i class="fas fa-star"></i>
                                    <span><?= esc($current_subscription['plan_name']) ?> Plan</span>
                                </div>
                                <div class="plan-tag">Premium</div>
                            </div>

                            <div class="plan-metrics-grid">
                                <div class="metric-card">
                                    <div class="metric-icon">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <div class="metric-content">
                                        <div class="metric-value"><?= date('M j', strtotime($current_subscription['current_period_end'])) ?></div>
                                        <div class="metric-label">Next Billing</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                            <!-- Usage Progress Bars -->
                            <?php if (isset($usage_stats)): ?>
                            <div class="usage-metrics mb-4">
                                <h5 class="text-dark mb-3 fw-semibold">
                                    <i class="fas fa-chart-line text-primary me-2"></i>
                                    Monthly Usage (<?= date('M Y', strtotime($usage_stats['profile_views']['period_start'])) ?>)
                                </h5>

                                <!-- Profile Views Progress -->
                                <div class="usage-item mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="usage-label">
                                            <i class="fas fa-eye me-2" style="color: #E55C0D;"></i>
                                            Profile Views
                                        </span>
                                        <span class="usage-count fw-semibold">
                                            <?php
                                            $profileViews = $usage_stats['profile_views']['current'] ?? 0;
                                            $maxViews = $current_subscription['max_profile_views'] ?? 0;
                                            if ($maxViews == 0) {
                                                echo number_format($profileViews) . ' <small class="text-muted">of Unlimited</small>';
                                            } else {
                                                echo number_format($profileViews) . ' <small class="text-muted">of ' . number_format($maxViews) . '</small>';
                                            }
                                            ?>
                                        </span>
                                    </div>
                                    <?php if ($maxViews > 0): ?>
                                        <?php $progressPercent = min(100, ($profileViews / $maxViews) * 100); ?>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-primary" role="progressbar"
                                                 style="width: <?= $progressPercent ?>%;"
                                                 aria-valuenow="<?= $progressPercent ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    <?php else: ?>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%;"></div>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Contact Clicks Progress -->
                                <div class="usage-item mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="usage-label">
                                            <i class="fas fa-mouse-pointer me-2" style="color: #E55C0D;"></i>
                                            Contact Clicks
                                        </span>
                                        <span class="usage-count fw-semibold">
                                            <?php
                                            $clicksUsed = $usage_stats['contact_clicks']['current'] ?? 0;
                                            $maxClicks = $current_subscription['max_clicks'] ?? 0;
                                            if ($maxClicks == 0) {
                                                echo number_format($clicksUsed) . ' <small class="text-muted">of Unlimited</small>';
                                            } else {
                                                echo number_format($clicksUsed) . ' <small class="text-muted">of ' . number_format($maxClicks) . '</small>';
                                            }
                                            ?>
                                        </span>
                                    </div>
                                    <?php if ($maxClicks > 0): ?>
                                        <?php $clicksPercent = min(100, ($clicksUsed / $maxClicks) * 100); ?>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-info" role="progressbar"
                                                 style="width: <?= $clicksPercent ?>%;"
                                                 aria-valuenow="<?= $clicksPercent ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    <?php else: ?>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%;"></div>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Messages Progress -->
                                <div class="usage-item mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="usage-label">
                                            <i class="fas fa-envelope me-2" style="color: #E55C0D;"></i>
                                            Messages
                                        </span>
                                        <span class="usage-count fw-semibold">
                                            <?php
                                            $messagesUsed = $usage_stats['messages']['current'] ?? 0;
                                            $maxMessages = $current_subscription['max_messages'] ?? 0;
                                            if ($maxMessages == 0) {
                                                echo number_format($messagesUsed) . ' <small class="text-muted">of Unlimited</small>';
                                            } else {
                                                echo number_format($messagesUsed) . ' <small class="text-muted">of ' . number_format($maxMessages) . '</small>';
                                            }
                                            ?>
                                        </span>
                                    </div>
                                    <?php if ($maxMessages > 0): ?>
                                        <?php $messagesPercent = min(100, ($messagesUsed / $maxMessages) * 100); ?>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-warning" role="progressbar"
                                                 style="width: <?= $messagesPercent ?>%;"
                                                 aria-valuenow="<?= $messagesPercent ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    <?php else: ?>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%;"></div>
                                        </div>
                                    <?php endif; ?>
                                </div>



                                <!-- Reviews Progress -->
                                <div class="usage-item mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="usage-label">
                                            <i class="fas fa-star me-2" style="color: #E55C0D;"></i>
                                            Reviews
                                        </span>
                                        <span class="usage-count fw-semibold">
                                            <?php
                                            $reviewsUsed = $review_count;
                                            $maxReviews = $current_subscription['max_reviews'] ?? 0;
                                            if ($maxReviews == 0) {
                                                echo number_format($reviewsUsed) . ' <small class="text-muted">of Unlimited</small>';
                                            } else {
                                                echo number_format($reviewsUsed) . ' <small class="text-muted">of ' . number_format($maxReviews) . '</small>';
                                            }
                                            ?>
                                        </span>
                                    </div>
                                    <?php if ($maxReviews > 0): ?>
                                        <?php $reviewsPercent = min(100, ($reviewsUsed / $maxReviews) * 100); ?>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-warning" role="progressbar"
                                                 style="width: <?= $reviewsPercent ?>%;"
                                                 aria-valuenow="<?= $reviewsPercent ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    <?php else: ?>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%;"></div>
                                        </div>
                                    <?php endif; ?>
                                </div>


                            </div>
                            <?php endif; ?>

                            <!-- What's Not Included -->
                            <div class="mt-3">
                                <h6 class="not-included-title">
                                    <i class="fas fa-times-circle"></i>
                                    What's Not Included
                                </h6>
                                <ul class="not-included-list">
                                    <?php
                                    $notIncludedFeatures = [];

                                    // WhatsApp contact (if not included)
                                    if (($current_subscription['show_whatsapp'] ?? 0) == 0) {
                                        $notIncludedFeatures[] = 'WhatsApp contact visibility';
                                    }

                                    // Email marketing (if not included)
                                    if (($current_subscription['email_marketing_access'] ?? 0) == 0) {
                                        $notIncludedFeatures[] = 'Email marketing access & tools';
                                    }

                                    // Video upload (if not included)
                                    if (($current_subscription['allow_video_upload'] ?? 0) == 0) {
                                        $notIncludedFeatures[] = 'Bio video display capability';
                                    }

                                    // PDF upload (if not included)
                                    if (($current_subscription['allow_pdf_upload'] ?? 0) == 0) {
                                        $notIncludedFeatures[] = 'Past Papers PDF upload capability';
                                    }

                                    // Announcements (if not included)
                                    if (($current_subscription['allow_announcements'] ?? 0) == 0) {
                                        $notIncludedFeatures[] = 'School announcements posting';
                                    }

                                    // District spotlight (if not included)
                                    if (($current_subscription['district_spotlight_days'] ?? 0) == 0) {
                                        $notIncludedFeatures[] = 'District spotlight feature';
                                    }

                                    // Display not included features
                                    if (!empty($notIncludedFeatures)) {
                                        foreach ($notIncludedFeatures as $feature) {
                                            echo '<li class="not-included-item"><i class="fas fa-times"></i><span>' . htmlspecialchars($feature) . '</span></li>';
                                        }
                                    } else {
                                        echo '<li class="not-included-item all-included"><i class="fas fa-check"></i><span>All premium features included!</span></li>';
                                    }
                                    ?>
                                </ul>
                            </div>



                        <?php elseif ($subscription_status === 'waiting_verification'): ?>
                            <!-- Free Plan Features -->
                            <div class="plan-status-header mb-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div>
                                        <h4 class="text-success mb-1 fw-bold">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            Free Plan Active
                                        </h4>
                                        <p class="text-muted mb-0 small">Basic features available</p>
                                    </div>
                                    <div class="text-end">
                                        <div class="badge bg-success fs-6 px-3 py-2">
                                            <i class="fas fa-check-circle me-1"></i>Active
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Free Plan Features -->
                            <div class="plan-features">
                                <h5 class="text-dark mb-3 fw-semibold">
                                    <i class="fas fa-gift text-success me-2"></i>
                                    Free Plan Features
                                </h5>
                                <div class="row g-3">
                                    <div class="col-6">
                                        <div class="feature-item d-flex align-items-center mb-2">
                                            <i class="fas fa-check text-success me-2"></i>
                                            <small class="text-dark">Basic profile</small>
                                        </div>
                                        <div class="feature-item d-flex align-items-center mb-2">
                                            <i class="fas fa-check text-success me-2"></i>
                                            <small class="text-dark">Student search</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="feature-item d-flex align-items-center mb-2">
                                            <i class="fas fa-check text-success me-2"></i>
                                            <small class="text-dark">Contact info</small>
                                        </div>
                                        <div class="feature-item d-flex align-items-center mb-2">
                                            <i class="fas fa-times text-muted me-2"></i>
                                            <small class="text-muted">Premium features</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 text-center">
                                    <p class="text-muted mb-3">Upgrade to unlock advanced features and reach more students</p>
                                    <a href="<?= site_url('trainer/subscription') ?>" class="btn btn-primary px-4 py-2">
                                        <i class="fas fa-crown me-2"></i>View Premium Plans
                                    </a>
                                </div>
                            </div>

                        <?php else: ?>
                            <!-- No Subscription -->
                            <div class="text-center py-4">
                                <div class="mb-3">
                                    <i class="fas fa-info-circle text-info" style="font-size: 3rem;"></i>
                                </div>
                                <h5 class="text-dark mb-3">Ready to Go Premium?</h5>
                                <p class="text-muted mb-4">Unlock advanced features and reach more students with a professional plan.</p>
                                <a href="<?= site_url('trainer/subscription') ?>" class="btn btn-primary px-4 py-2">
                                    <i class="fas fa-crown me-2"></i>View Subscription Plans
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Premium Features Cards (for missing features) -->
                <?php if (isset($subscription_status) && $subscription_status === 'subscribed' && isset($current_subscription) && $current_subscription): ?>
                    <?php
                    // Debug: Show current subscription features
                    echo '<!-- DEBUG: PDF Upload: ' . ($current_subscription['allow_pdf_upload'] ?? 'not set') . ' -->';
                    echo '<!-- DEBUG: Video Solutions: ' . ($current_subscription['allow_video_solution'] ?? 'not set') . ' -->';
                    echo '<!-- DEBUG: Announcements: ' . ($current_subscription['allow_announcements'] ?? 'not set') . ' -->';

                    $missingFeatures = [];

                    if (($current_subscription['allow_pdf_upload'] ?? 0) == 0) {
                        $missingFeatures[] = [
                            'title' => 'Past Papers PDF Upload',
                            'description' => 'Upload and share educational resources with students',
                            'icon' => 'fas fa-file-pdf',
                            'iconClass' => 'pdf-feature'
                        ];
                    }

                    if (($current_subscription['allow_video_solution'] ?? 0) == 0) {
                        $missingFeatures[] = [
                            'title' => 'Video Solution Upload',
                            'description' => 'Share video explanations and step-by-step solutions',
                            'icon' => 'fas fa-video',
                            'iconClass' => 'video-feature'
                        ];
                    }

                    if (($current_subscription['allow_announcements'] ?? 0) == 0) {
                        $missingFeatures[] = [
                            'title' => 'School Announcements',
                            'description' => 'Post important announcements and reach your community',
                            'icon' => 'fas fa-bullhorn',
                            'iconClass' => 'announcement-feature',
                            'iconColor' => '#E55C0D'
                        ];
                    }

                    if (!empty($missingFeatures)):
                    ?>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-star text-warning me-2"></i>
                                Unlock Premium Features
                            </h3>
                        </div>
                        <div class="card-content">
                            <div class="action-grid">
                                <?php foreach ($missingFeatures as $feature): ?>
                                    <div class="feature-upgrade-card">
                                        <div class="feature-icon" style="background: rgba(229, 92, 13, 0.1); color: #E55C0D;">
                                            <i class="<?= $feature['icon'] ?>"></i>
                                        </div>
                                        <h4 class="feature-title"><?= $feature['title'] ?></h4>
                                        <p class="feature-description"><?= $feature['description'] ?></p>
                                        <a href="<?= site_url('trainer/subscription') ?>" class="btn btn-sm w-100" style="background: #E55C0D; color: white; border: none;">
                                            <i class="fas fa-crown me-1" style="color: white;"></i>Upgrade Now
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- Quick Actions - Plan Based -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Quick Actions</h3>
                    </div>
                    <div class="card-content">
                        <div class="action-grid">
                            <!-- Always Available Actions -->
                            <div class="action-card" onclick="window.location.href='<?= site_url('trainer/profile') ?>'">
                                <div class="action-icon" style="background: rgba(229, 92, 13, 0.1); color: #E55C0D;">
                                    <i class="fas fa-user-edit"></i>
                                </div>
                                <h3 class="action-title">Update Profile</h3>
                                <p class="action-description">Keep your profile current</p>
                                <button class="btn-action btn-sm" style="background: #E55C0D; color: white; border: none;">Go to Profile</button>
                            </div>

                            <div class="action-card" onclick="window.location.href='<?= site_url('trainer/subjects') ?>'">
                                <div class="action-icon" style="background: rgba(201, 70, 9, 0.1); color: #C94609;">
                                    <i class="fas fa-book"></i>
                                </div>
                                <h3 class="action-title">Manage Subjects</h3>
                                <p class="action-description">Update your expertise</p>
                                <button class="btn-action btn-sm" style="background: #C94609; color: white; border: none;">Manage Subjects</button>
                            </div>

                            <!-- Plan-Specific Actions -->
                            <?php if (isset($subscription_status) && $subscription_status === 'subscribed' && isset($current_subscription) && $current_subscription): ?>

                                <!-- PDF Upload Action (if enabled) -->
                                <?php if (($current_subscription['allow_pdf_upload'] ?? 0) == 1): ?>
                                    <div class="action-card" onclick="window.location.href='<?= site_url('trainer/resources/my-papers') ?>'">
                                        <div class="action-icon" style="background: rgba(229, 92, 13, 0.1); color: #E55C0D;">
                                            <i class="fas fa-file-pdf"></i>
                                        </div>
                                        <h3 class="action-title">View Past Papers</h3>
                                        <p class="action-description">Manage your uploaded resources</p>
                                        <button class="btn-action btn-sm" style="background: #E55C0D; color: white; border: none;">View Papers</button>
                                    </div>
                                <?php endif; ?>

                                <!-- Video Solutions Action (if enabled) -->
                                <?php if (($current_subscription['allow_video_solution'] ?? 0) == 1): ?>
                                    <div class="action-card" onclick="window.location.href='<?= site_url('trainer/resources/my-video-solutions') ?>'">
                                        <div class="action-icon" style="background: rgba(239, 68, 68, 0.1); color: #EF4444;">
                                            <i class="fas fa-video"></i>
                                        </div>
                                        <h3 class="action-title">My Video Solutions</h3>
                                        <p class="action-description">View and manage your uploaded videos</p>
                                        <button class="btn-action btn-sm" style="background: #EF4444; color: white; border: none;">View Videos</button>
                                    </div>
                                <?php endif; ?>

                                <!-- Announcements Action (if enabled) -->
                                <?php if (($current_subscription['allow_announcements'] ?? 0) == 1): ?>
                                    <div class="action-card" onclick="window.location.href='<?= site_url('trainer/notices') ?>'">
                                        <div class="action-icon theme-warning">
                                            <i class="fas fa-bullhorn"></i>
                                        </div>
                                        <h3 class="action-title">Announcements</h3>
                                        <p class="action-description">Post school announcements</p>
                                        <button class="btn-theme-warning btn-sm">Post Announcement</button>
                                    </div>
                                <?php endif; ?>

                            <?php else: ?>
                                <!-- Free Trial / No Subscription Actions -->
                                <div class="action-card" onclick="window.location.href='<?= site_url('trainer/subscription') ?>'">
                                    <div class="action-icon theme-primary">
                                        <i class="fas fa-crown"></i>
                                    </div>
                                    <h3 class="action-title">Go Premium</h3>
                                    <p class="action-description">Unlock advanced features</p>
                                    <button class="btn-theme-primary btn-sm">View Plans</button>
                                </div>

                                <!-- Free Trial Specific Actions -->
                                <?php if (isset($subscription_status) && $subscription_status === 'waiting_verification'): ?>
                                    <div class="action-card" onclick="window.location.href='<?= site_url('trainer/profile') ?>'">
                                        <div class="action-icon theme-warning">
                                            <i class="fas fa-shield-check"></i>
                                        </div>
                                        <h3 class="action-title">Complete Setup</h3>
                                        <p class="action-description">Finish your verification</p>
                                        <button class="btn-theme-warning btn-sm">Complete Profile</button>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <nav class="bottom-nav">
            <a href="<?= site_url('trainer/dashboard') ?>" class="nav-item active">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>
            <a href="<?= site_url('trainer/subjects') ?>" class="nav-item">
                <i class="fas fa-book"></i>
                <span>Subjects</span>
            </a>
            <a href="<?= site_url('trainer/profile') ?>" class="nav-item">
                <i class="fas fa-user"></i>
                <span>Profile</span>
            </a>
            <a href="<?= site_url('trainer/subscription') ?>" class="nav-item">
                <i class="fas fa-crown"></i>
                <span>Premium</span>
            </a>
        </nav>
    </div>

    <div class="toast-container" id="toastContainer"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Active navigation highlighting
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const navItems = document.querySelectorAll('.nav-item');

            // Helper function to check if navigation item should be active
            function shouldBeActive(item) {
                const href = item.getAttribute('href');
                if (!href) return false;

                // Check if href contains key parts of current path
                if (href.includes('/trainer/dashboard') && currentPath.includes('/trainer/dashboard')) return true;
                if (href.includes('/trainer/analytics') && currentPath.includes('/trainer/analytics')) return true;
                if (href.includes('/trainer/subjects') && currentPath.includes('/trainer/subjects')) return true;
                if (href.includes('/trainer/profile') && currentPath.includes('/trainer/profile')) return true;
                if (href.includes('/trainer/subscription') && currentPath.includes('/trainer/subscription')) return true;

                return false;
            }

            navItems.forEach(item => {
                if (shouldBeActive(item)) {
                    item.classList.add('active');
                } else {
                    item.classList.remove('active');
                }
            });
        });

        // Toast notification system
        function showToast(type, title, message) {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;

            const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
            const iconColor = type === 'success' ? 'var(--success-color)' : type === 'error' ? 'var(--danger-color)' : 'var(--warning-color)';

            toast.innerHTML = `
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <i class="fas ${iconClass}" style="color: ${iconColor}; font-size: 1.25rem;"></i>
                    <div>
                        <div style="font-weight: 600; color: var(--text-dark);">${title}</div>
                        <div style="font-size: 0.875rem; color: var(--text-light);">${message}</div>
                    </div>
                </div>
            `;

            container.appendChild(toast);

            // Auto remove after 5 seconds
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }
    </script>
</body>
</html>
