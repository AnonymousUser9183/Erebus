@extends('layouts.error')

@section('content')
    <style>
        :root {
            --color-bg-primary: #fcfcf9;
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
        }

        .error-message {
            font-size: 28px;
            font-weight: 700;
            color: var(--color-text-primary);
            margin: var(--spacing-lg) 0 var(--spacing-md) 0;
            text-align: center;
        }

        .error-description {
            font-size: 16px;
            color: var(--color-text-secondary);
            text-align: center;
            max-width: 500px;
            margin: var(--spacing-lg) 0 var(--spacing-2xl) 0;
            line-height: 1.6;
        }

        .home-button {
            padding: var(--spacing-md) var(--spacing-lg);
            background-color: var(--color-accent);
            color: #ffffff;
            text-decoration: none;
            border-radius: var(--radius);
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .home-button:hover {
            background-color: #1a6f7a;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .error-code { font-size: 80px; }
            .error-message { font-size: 20px; }
            .error-description { font-size: 14px; }
        }
    </style>

    <div class="error-container">
        <div class="error-code">500</div>
        <div class="error-message">Our Server is Having a Monday Moment</div>
        <div class="error-description">
            Even our mighty server sometimes needs a coffee break! Right now it's having what we call a "technical brain freeze" - you know, like when you eat ice cream too fast, but with code. Our tech team is already brewing a fresh pot of coffee and working on perking things up. Maybe try again in a bit when the server has had its caffeine fix?
        </div>
        <a href="{{ route('login') }}" class="home-button">Return to {{ config('app.name') }}</a>
    </div>
@endsection
