@extends('layouts.app')

@section('content')
<style>
    :root {
        --color-bg-primary: #fcfcf9;
        --color-bg-secondary: #ffffff;
        --color-text-primary: #134252;
        --color-text-secondary: #626c71;
        --color-border: #e8e8e6;
        --color-accent: #208088;
        --color-accent-light: #32b8c6;
        --spacing-md: 16px;
        --spacing-lg: 20px;
        --spacing-xl: 24px;
        --spacing-2xl: 32px;
        --radius: 8px;
    }

    .guides-container {
        max-width: 1000px;
        margin: var(--spacing-2xl) auto;
        padding: var(--spacing-lg);
    }

    .guides-card {
        background: var(--color-bg-secondary);
        border-radius: var(--radius);
        border: 1px solid var(--color-border);
        padding: var(--spacing-2xl);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    	margin-left: -80px;
	margin-right: -80px;
    }

    .guides-header {
        border-bottom: 2px solid var(--color-accent);
        padding-bottom: var(--spacing-lg);
        margin-bottom: var(--spacing-xl);
    }

    .guides-title {
        font-size: 2.5em;
        font-weight: 600;
        color: var(--color-text-primary);
        margin-bottom: var(--spacing-md);
    }

    .guides-description {
        color: var(--color-text-secondary);
        font-size: 1.1em;
        margin-bottom: 0;
    }

    .guides-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: var(--spacing-lg);
        margin-top: var(--spacing-xl);
    }

    .guides-item {
        background: var(--color-bg-primary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .guides-item:hover {
        box-shadow: 0 4px 12px rgba(32, 128, 136, 0.15);
        transform: translateY(-2px);
        border-color: var(--color-accent);
    }

    .guides-item-title {
        font-size: 1.3em;
        font-weight: 600;
        color: var(--color-accent);
        margin-bottom: var(--spacing-md);
        line-height: 1.4;
    }

    .guides-item-description {
        color: var(--color-text-secondary);
        margin-bottom: var(--spacing-lg);
        flex-grow: 1;
        font-size: 0.95em;
        line-height: 1.6;
    }

    .guides-item-link {
        display: inline-block;
        background: var(--color-accent);
        color: white;
        padding: 10px 16px;
        border-radius: var(--radius);
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        text-align: center;
        align-self: flex-start;
    }

    .guides-item-link:hover {
        background: #1a6a73;
        transform: translateX(2px);
    }

    @media (max-width: 768px) {
        .guides-container { padding: var(--spacing-md); }
        .guides-card { padding: var(--spacing-lg); }
        .guides-title { font-size: 1.8em; }
        .guides-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="guides-container">
    <div class="guides-card">
        <div class="guides-header">
            <h1 class="guides-title">Security Guides</h1>
            <p class="guides-description">
                Explore our comprehensive guides to help you securely use the {{ config('app.name') }} marketplace and protect your privacy.
            </p>
        </div>

        <div class="guides-grid">
            <div class="guides-item">
                <h3 class="guides-item-title">💰 Monero Guide</h3>
                <p class="guides-item-description">
                    Learn how to create and safely use a Monero wallet for private and untraceable cryptocurrency transactions.
                </p>
                <a href="{{ route('guides.monero') }}" class="guides-item-link">View Guide</a>
            </div>

            <div class="guides-item">
                <h3 class="guides-item-title">🧅 Tor Browser Guide</h3>
                <p class="guides-item-description">
                    Understand how to use Tor Browser for anonymous browsing and protecting your online privacy.
                </p>
                <a href="{{ route('guides.tor') }}" class="guides-item-link">View Guide</a>
            </div>

            <div class="guides-item">
                <h3 class="guides-item-title">🔐 KeePassXC Guide</h3>
                <p class="guides-item-description">
                    Master the art of secure password management with KeePassXC and protect your accounts.
                </p>
                <a href="{{ route('guides.keepassxc') }}" class="guides-item-link">View Guide</a>
            </div>

            <div class="guides-item">
                <h3 class="guides-item-title">🔑 Kleopatra Guide</h3>
                <p class="guides-item-description">
                    Learn PGP encryption and digital signatures for secure communication with Kleopatra.
                </p>
                <a href="{{ route('guides.kleopatra') }}" class="guides-item-link">View Guide</a>
            </div>
        </div>
    </div>
</div>
@endsection
