@extends('layouts.error')

@section('content')
    <style>
        :root {
            --color-bg-primary: #fcfcf9;
            --color-bg-secondary: #ffffff;
            --color-text-primary: #134252;
            --color-text-secondary: #626c71;
            --color-accent: #208088;
            --spacing-md: 16px;
            --spacing-lg: 20px;
            --spacing-2xl: 32px;
            --radius: 8px;
        }

        .error-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: var(--spacing-lg);
            background-color: var(--color-bg-primary);
        }

        .error-code {
            font-size: 120px;
            font-weight: 800;
            color: var(--color-accent);
            margin: 0;
            line-height: 1;
            animation: fadeInDown 0.6s ease;
        }

        .error-message {
            font-size: 28px;
            font-weight: 700;
            color: var(--color-text-primary);
            margin: var(--spacing-lg) 0 var(--spacing-md) 0;
            text-align: center;
            animation: fadeInUp 0.6s ease 0.1s both;
        }

        .error-description {
            font-size: 16px;
            color: var(--color-text-secondary);
            text-align: center;
            max-width: 500px;
            margin: var(--spacing-lg) 0 var(--spacing-2xl) 0;
            line-height: 1.6;
            animation: fadeInUp 0.6s ease 0.2s both;
        }

        .home-button {
            padding: var(--spacing-md) var(--spacing-lg);
            background-color: var(--color-accent);
            color: #ffffff;
            text-decoration: none;
            border-radius: var(--radius);
            font-weight: 600;
            transition: all 0.3s ease;
            animation: fadeInUp 0.6s ease 0.3s both;
        }

        .home-button:hover {
            background-color: #1a6f7a;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(32, 128, 136, 0.3);
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .error-code {
                font-size: 80px;
            }

            .error-message {
                font-size: 20px;
            }

            .error-description {
                font-size: 14px;
            }
        }
    </style>

    <div class="error-container">
        <div class="error-code">404</div>
        <div class="error-message">Oops! Page Pulled a Houdini</div>
        <div class="error-description">
            This page has vanished like socks in a dryer! We've searched high and low, checked under the digital couch cushions, but it seems to have mastered the art of disappearing. Maybe it's on vacation with all those missing left socks?
        </div>
        <a href="{{ route('home') }}" class="home-button">Return to {{ config('app.name') }}</a>
    </div>
@endsection
