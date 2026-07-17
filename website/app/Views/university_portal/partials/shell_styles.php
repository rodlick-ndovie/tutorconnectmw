        .app-container {
            width: 100%;
            max-width: 480px;
            min-height: 100vh;
            margin: 0 auto;
            background: var(--bg, #f8fafc);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
        }

        @media (min-width: 768px) {
            .app-container {
                max-width: 100%;
                box-shadow: none;
            }
        }

        .status-bar {
            height: 0;
            background: var(--card, #ffffff);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
            display: none;
        }

        .portal-navbar {
            background: var(--card, #ffffff);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            padding: 12px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            position: sticky;
            top: 0;
            z-index: 99;
        }

        .portal-nav-left {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .portal-nav-meta {
            min-width: 0;
        }

        .portal-nav-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-dark, #1f2937);
            margin: 0;
        }

        .portal-nav-subtitle {
            margin-top: 2px;
            font-size: 12px;
            color: var(--text-muted, #6b7280);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .portal-nav-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        .portal-nav-button {
            background: none;
            border: none;
            color: var(--primary, var(--primary-color, #e55c0d));
            font-size: 16px;
            padding: 8px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: background 0.2s ease;
        }

        .portal-nav-button:hover {
            background: rgba(0, 0, 0, 0.05);
            color: var(--primary, var(--primary-color, #e55c0d));
        }

        .portal-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary, var(--primary-color, #e55c0d)), var(--primary-dark, var(--secondary-color, #c94609)));
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-weight: 600;
            font-size: 16px;
            flex-shrink: 0;
        }

        .portal-main {
            padding-bottom: 100px;
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
            max-width: 100%;
            background: var(--card, #ffffff);
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            gap: 8px;
            padding: 10px 14px;
            z-index: 100;
        }

        @media (min-width: 768px) {
            .bottom-nav {
                gap: 12px;
            }
        }

        .nav-item {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: var(--text-muted, var(--text-light, #6b7280));
            transition: all 0.3s ease;
            padding: 6px 4px;
            border-radius: 12px;
            font-size: 13px;
        }

        .nav-item i {
            font-size: 18px;
            margin-bottom: 4px;
        }

        .nav-item span {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
        }

        .nav-item:hover {
            background: rgba(0, 0, 0, 0.05);
            color: var(--primary, var(--primary-color, #e55c0d));
        }

        .nav-item.active {
            color: var(--primary, var(--primary-color, #e55c0d));
        }
