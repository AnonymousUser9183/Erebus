<nav class="navbar">
    <style>
        :root {
            --color-primary-dark: #0d5b7c;
            --color-primary: #1a7a99;
            --color-primary-light: #2a9db8;
            --color-text-dark: #1a1a1a;
            --color-text-primary: #333333;
            --color-text-secondary: #666666;
            --color-bg-secondary: #ffffff;
            --color-border: #e0e0e0;
            --color-danger: #dc2626;
            --spacing-sm: 8px;
            --spacing-md: 12px;
            --spacing-lg: 16px;
            --radius-md: 6px;
        }

        /* Sponsorship Banner */
        .sponsor-banner {
            background: linear-gradient(90deg, #1a1a2e 0%, #16213e 50%, #1a1a2e 100%);
            border-bottom: 2px solid #32b8c6;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1001;
            height: 40px;
            overflow: hidden;
            display: flex;
            align-items: center;
        }

        .sponsor-banner a {
            display: flex;
            align-items: center;
            gap: 16px;
            text-decoration: none;
            white-space: nowrap;
            animation: scroll-banner 20s linear infinite;
            padding: 0 40px;
        }

        @keyframes scroll-banner {
            0% {
                transform: translateX(100%);
            }
            100% {
                transform: translateX(-100%);
            }
        }

        .sponsor-banner a:hover {
            animation-play-state: paused;
        }

        .sponsor-banner img {
            height: 28px;
            width: auto;
            object-fit: contain;
            flex-shrink: 0;
        }

        .sponsor-banner-text {
            color: #32b8c6;
            font-size: 13px;
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        .sponsor-banner-text strong {
            color: #ffffff;
            font-weight: 600;
        }

        .navbar {
            background-color: var(--color-bg-secondary);
            border-bottom: 1px solid var(--color-border);
            position: fixed;
            top: 40px;
            left: 0;
            right: 0;
            z-index: 1000;
            height: 60px;
        }

        .navbar-container {
            max-width: 1400px;
            margin: 0 auto;
            height: 100%;
            padding: 0 var(--spacing-lg);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: var(--spacing-lg);
        }

        .navbar-left {
            display: flex;
            align-items: center;
            gap: var(--spacing-lg);
            min-width: 0;
        }

        .navbar-logo {
            font-size: 23px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-decoration: none;
            background: linear-gradient(90deg, #1f3a45 0%, #32b8c6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            transition: all 0.3s ease;
            display: inline-block;
            padding: 8px 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            animation: subtle-shimmer 3s ease-in-out infinite;
        }

        @keyframes subtle-shimmer {
            0%, 100% {
                background-position: 0%;
            }
            50% {
                background-position: 100%;
            }
        }

        .navbar-logo:hover {
            letter-spacing: 1px;
            opacity: 0.9;
            filter: drop-shadow(0 0 8px rgba(50, 184, 198, 0.4));
            letter-spacing: 1px;
        }

        .navbar-logo:focus {
            outline: 2px solid #32b8c6;
            outline-offset: 4px;
            border-radius: 4px;
        }

        .navbar-center {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--spacing-lg);
            min-width: 0;
        }

        .navbar-currency-rates {
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
            font-size: 11px;
            color: var(--color-text-secondary);
            white-space: nowrap;
            flex-wrap: wrap;
            justify-content: center;
        }

        .navbar-currency-rate {
            padding: 4px 8px;
            background-color: #f5f5f5;
            border-radius: var(--radius-md);
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
            flex-shrink: 0;
        }

        .navbar-btn {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            background: none;
            border: none;
            color: #1c767d;
            text-decoration: none;
            font-weight: 500;
            font-size: 13px;
            cursor: pointer;
            border-radius: var(--radius-md);
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .navbar-btn:hover {
            background-color: #f5f5f5;
            color: var(--color-primary);
        }

        .navbar-icon-btn {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            padding: 0;
            background: none;
            border: none;
            cursor: pointer;
            border-radius: var(--radius-md);
            transition: all 0.2s ease;
        }

        .navbar-icon-btn:hover {
            background-color: #f5f5f5;
        }

        .navbar-icon-btn img {
            width: 20px;
            height: 20px;
        }

        .navbar-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: var(--color-danger);
            color: white;
            font-size: 10px;
            font-weight: bold;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
        }

        .navbar-btn-logout {
            background-color: #f5f5f5;
            padding: var(--spacing-sm) var(--spacing-md);
        }

        .navbar-btn-logout:hover {
            background-color: #e8e8e8;
            color: var(--color-primary);
        }

        @media (max-width: 1024px) {
            .navbar-center {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .sponsor-banner {
                height: 36px;
            }

            .sponsor-banner a {
                gap: 12px;
                padding: 0 30px;
            }

            .sponsor-banner img {
                height: 24px;
            }

            .sponsor-banner-text {
                font-size: 12px;
            }

            .navbar {
                top: 36px;
                height: 50px;
            }

            .navbar-container {
                padding: 0 var(--spacing-md);
                gap: var(--spacing-md);
            }

            .navbar-logo img {
                height: 30px;
            }

            .navbar-btn {
                padding: var(--spacing-xs) var(--spacing-sm);
                font-size: 12px;
            }

            .navbar-icon-btn {
                width: 32px;
                height: 32px;
            }

            .navbar-icon-btn img {
                width: 18px;
                height: 18px;
            }
        }

        @media (max-width: 480px) {
            .sponsor-banner a {
                gap: 10px;
                padding: 0 20px;
            }

            .sponsor-banner img {
                height: 22px;
            }

            .sponsor-banner-text {
                font-size: 11px;
            }

            .navbar-logo {
                font-size: 20px;
            }
        }
    </style>

    <!-- Sponsorship Banner -->
    <div class="sponsor-banner">
        <a href="/home">
            <img src="{{ asset('images/logo.png') }}" alt="Erebus Marketplace Script">
            <span class="sponsor-banner-text">Welcome to <strong>Erebus Marketplace Script</strong> - A rewritten and upgraded version of Kabus Marketplace Script by Sukunetsiz. Running on the latest variant of Laravel 12 updated from Laravel 11 and a completely rewritten design.</span>
        </a>
    </div>

    <div class="navbar-container">
        <div class="navbar-left">
            <a href="{{ route('home') }}" class="navbar-logo">
            Erebus Marketplace Script
            </a>
            
            @auth
                <a href="{{ route('home') }}" class="navbar-btn">
                    <img src="{{ asset('icons/home.png') }}" alt="Home" style="width: 16px; height: 16px;">
                    HOME
                </a>
                <a href="{{ route('products.index') }}" class="navbar-btn">
                🔎 SEARCH
                </a>
                <a href="{{ route('orders.index') }}" class="navbar-btn">
                <img src="{{ asset('icons/orders.png') }}" alt="Orders" style="width: 16px; height: 16px;">
                ORDERS
            </a>
            <a href="{{ route('profile') }}" class="navbar-btn">
            <img src="{{ asset('icons/account.png') }}" alt="Account" style="width: 16px; height: 16px;">
                ACCOUNT
            </a>
            <a href="{{ route('support.index') }}" class="navbar-btn">
            <img src="{{ asset('icons/support.png') }}" alt="Support" style="width: 16px; height: 16px;">
                SUPPORT
            </a>
            @endauth
        </div>

        <div class="navbar-right">
            @auth
                <a href="{{ route('cart.index') }}" class="navbar-icon-btn" title="Shopping Cart">
                    <img src="{{ asset('icons/cart.png') }}" alt="Cart">
                    @if(auth()->user()->cartItems()->count() > 0)
                        <span class="navbar-badge">{{ auth()->user()->cartItems()->count() }}</span>
                    @endif
                </a>
                <a href="{{ route('messages.index') }}" class="navbar-btn">
            	    <img src="{{ asset('icons/messages.png') }}" style="width: 16px; height: 16px;">
                </a>
		<a href="{{ route('wishlist.index') }}" class="navbar-btn">
                    <img src="{{ asset('icons/wishlist.png') }}" style="width: 16px; height: 16px;">
                </a>
                <a href="{{ route('notifications.index') }}" class="navbar-icon-btn" title="Notifications">
                    <img src="{{ asset('icons/notifications.png') }}" alt="Notifications">
                    @if(auth()->user()->unread_notifications_count > 0)
                        <span class="navbar-badge">{{ auth()->user()->unread_notifications_count }}</span>
                    @endif
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="navbar-btn navbar-btn-logout" title="Logout">
                        LOGOUT
                        <img src="{{ asset('icons/logout.png') }}" alt="Logout" style="width: 14px; height: 14px;">
                    </button>
                </form>
            @endauth
        </div>
    </div>
</nav>

<style>
    /* Add margin to account for fixed navbar + banner */
    main {
        margin-top: 100px;
    }

    @media (max-width: 768px) {
        main {
            margin-top: 86px;
        }
    }

    @media (max-width: 480px) {
        main {
            margin-top: 86px;
        }
    }
</style>
