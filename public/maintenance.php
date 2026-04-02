<!--
 * =========================================================================
 * © 2026 The Erebus Development Team
 * Author: AnonymousUser9183
 * =========================================================================
 * Erebus Marketplace Maintenance Page (Javascript Required for Timer)
 * =========================================================================
 -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Mode</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --color-accent: #208088;
            --color-accent-light: #32b8c6;
            --color-text-primary: #134252;
            --color-text-secondary: #62746e;
            --color-card-bg: #ffffff;
            --color-border: #d4d8d6;
            --color-input-bg: #f5f7f6;
            --spacing-md: 12px;
            --spacing-lg: 16px;
            --spacing-xl: 24px;
            --radius-base: 8px;
            --radius-lg: 12px;
            --color-warning: #ffc107;
        }

        html {
            height: 100%;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen',
                'Ubuntu', 'Cantarell', 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif;
            background: linear-gradient(135deg, #f5f7f6 0%, #ffffff 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100%;
            color: var(--color-text-primary);
        }

        .maintenance-container {
            width: 100%;
            max-width: 600px;
            padding: var(--spacing-xl);
        }

        .maintenance-card {
            background: var(--color-card-bg);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-lg);
            padding: var(--spacing-xl);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            text-align: center;
        }

        .maintenance-icon {
            font-size: 64px;
            margin-bottom: var(--spacing-lg);
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.6;
            }
        }

        .maintenance-title {
            font-size: 32px;
            font-weight: 600;
            color: var(--color-text-primary);
            margin-bottom: var(--spacing-md);
            line-height: 1.2;
        }

        .maintenance-subtitle {
            font-size: 16px;
            color: var(--color-text-secondary);
            margin-bottom: var(--spacing-lg);
            line-height: 1.6;
        }

        .maintenance-message {
            background: rgba(255, 193, 7, 0.08);
            border: 1px solid rgba(255, 193, 7, 0.15);
            border-radius: var(--radius-base);
            padding: var(--spacing-lg);
            margin-bottom: var(--spacing-lg);
        }

        .maintenance-message-title {
            font-size: 14px;
            font-weight: 600;
            color: #f57f17;
            margin-bottom: var(--spacing-md);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--spacing-md);
        }

        .maintenance-message-text {
            font-size: 13px;
            color: var(--color-text-secondary);
            line-height: 1.6;
        }

        .maintenance-details {
            background: var(--color-input-bg);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-base);
            padding: var(--spacing-lg);
            margin-bottom: var(--spacing-lg);
            text-align: left;
        }

        .maintenance-details-item {
            margin-bottom: var(--spacing-md);
            font-size: 13px;
        }

        .maintenance-details-item:last-child {
            margin-bottom: 0;
        }

        .maintenance-details-label {
            font-weight: 600;
            color: var(--color-text-primary);
            margin-bottom: 4px;
            display: block;
        }

        .maintenance-details-value {
            color: var(--color-text-secondary);
            font-size: 12px;
            font-family: 'Monaco', 'Courier New', monospace;
        }

        .maintenance-contact {
            font-size: 13px;
            color: var(--color-text-secondary);
            line-height: 1.6;
            margin-bottom: var(--spacing-lg);
        }

        .maintenance-contact strong {
            color: var(--color-text-primary);
        }

        .maintenance-contact a {
            color: var(--color-accent);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .maintenance-contact a:hover {
            color: var(--color-accent-light);
        }

        .maintenance-progress {
            margin: var(--spacing-lg) 0;
        }

        .maintenance-progress-bar {
            width: 100%;
            height: 4px;
            background: var(--color-border);
            border-radius: 2px;
            overflow: hidden;
        }

        .maintenance-progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--color-accent), var(--color-accent-light));
            animation: progress 3s ease-in-out infinite;
            border-radius: 2px;
        }

        @keyframes progress {
            0% {
                width: 0%;
            }
            50% {
                width: 100%;
            }
            100% {
                width: 0%;
            }
        }

        .maintenance-footer {
            font-size: 12px;
            color: var(--color-text-secondary);
            margin-top: var(--spacing-lg);
            padding-top: var(--spacing-lg);
            border-top: 1px solid var(--color-border);
        }

        .maintenance-status {
            display: inline-block;
            padding: 4px 12px;
            background: rgba(255, 193, 7, 0.1);
            border: 1px solid rgba(255, 193, 7, 0.2);
            border-radius: var(--radius-base);
            color: #f57f17;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: var(--spacing-lg);
        }

        @media (max-width: 600px) {
            .maintenance-container {
                padding: var(--spacing-lg);
            }

            .maintenance-card {
                padding: var(--spacing-lg);
            }

            .maintenance-title {
                font-size: 24px;
            }

            .maintenance-icon {
                font-size: 48px;
            }

            .maintenance-subtitle {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <div class="maintenance-card">
            <div class="maintenance-icon">🔧</div>

            <div class="maintenance-status">Maintenance Mode</div>

            <h1 class="maintenance-title">We'll Be Back Soon!</h1>

            <p class="maintenance-subtitle">
                Our marketplace is currently undergoing scheduled maintenance to improve your experience and security. We appreciate your patience!
            </p>

            <div class="maintenance-message">
                <div class="maintenance-message-title">
                    ⏱️ Expected Downtime
                </div>
                <div class="maintenance-message-text">
                    We expect to complete maintenance within 2-4 hours. Exact duration may vary depending on the complexity of updates being deployed.
                </div>
            </div>

            <div class="maintenance-details">
                <div class="maintenance-details-item">
                    <span class="maintenance-details-label">Status</span>
                    <span class="maintenance-details-value">Maintenance in Progress</span>
                </div>
                <div class="maintenance-details-item">
                    <span class="maintenance-details-label">Expected Return</span>
                    <span class="maintenance-details-value">{{ $retval ?? 'Shortly' }}</span>
                </div>
                <div class="maintenance-details-item">
                    <span class="maintenance-details-label">Last Updated</span>
                    <span class="maintenance-details-value" id="last-updated">Just now</span>
                </div>
            </div>

            <div class="maintenance-progress">
                <div class="maintenance-progress-bar">
                    <div class="maintenance-progress-fill"></div>
                </div>
            </div>

            <div class="maintenance-contact">
                <strong>Need Help?</strong><br>
                If you need immediate assistance, please contact our support team at:<br>
                <a href="mailto:support@marketplace.local">support@marketplace.local</a>
            </div>

            <div class="maintenance-footer">
                Thank you for your understanding. We're working hard to make the marketplace better for you.
            </div>
        </div>
    </div>

    <script>
        // Update "last updated" timestamp
        function updateTimestamp() {
            const element = document.getElementById('last-updated');
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', {
                hour: 'numeric',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            });
            element.textContent = timeString;
        }

        // Initial update
        updateTimestamp();

        // Update every 10 seconds
        setInterval(updateTimestamp, 10000);

        // Optional: Auto-refresh page every 30 seconds to check if maintenance is over
        // Uncomment the line below to enable auto-refresh
        // setInterval(() => location.reload(), 30000);
    </script>
</body>
</html>
