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
        --spacing-xs: 8px;
        --spacing-sm: 12px;
        --spacing-md: 16px;
        --spacing-lg: 20px;
        --spacing-xl: 24px;
        --spacing-2xl: 32px;
        --radius: 8px;
        --transition: 0.3s ease;
    }

    .guides-container {
        max-width: 900px;
        margin: var(--spacing-2xl) auto;
        padding: var(--spacing-lg);
    }

    .guides-card {
        background: var(--color-bg-secondary);
        border-radius: var(--radius);
        border: 1px solid var(--color-border);
        padding: var(--spacing-2xl);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
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

    .guides-section-title {
        font-size: 1.5em;
        font-weight: 600;
        color: var(--color-accent);
        margin: var(--spacing-xl) 0 var(--spacing-md) 0;
        border-left: 4px solid var(--color-accent);
        padding-left: var(--spacing-md);
    }

    .guides-subtitle {
        font-size: 1.2em;
        font-weight: 500;
        color: var(--color-text-primary);
        margin: var(--spacing-lg) 0 var(--spacing-md) 0;
    }

    .guides-content {
        color: var(--color-text-primary);
        line-height: 1.8;
    }

    .guides-content p {
        margin-bottom: var(--spacing-md);
        color: var(--color-text-secondary);
    }

    .guides-list {
        list-style: none;
        padding-left: 0;
        margin: var(--spacing-md) 0;
    }

    .guides-list li {
        padding: var(--spacing-sm) 0 var(--spacing-sm) var(--spacing-lg);
        border-left: 3px solid var(--color-accent-light);
        margin-bottom: var(--spacing-sm);
        color: var(--color-text-secondary);
    }

    .guides-ordered-list {
        list-style: decimal;
        padding-left: var(--spacing-2xl);
        margin: var(--spacing-md) 0;
    }

    .guides-ordered-list li {
        margin-bottom: var(--spacing-md);
        color: var(--color-text-secondary);
    }

    .guides-highlight {
        background: rgba(32, 128, 136, 0.08);
        border-left: 4px solid var(--color-accent);
        padding: var(--spacing-md);
        border-radius: var(--radius);
        margin: var(--spacing-md) 0;
        color: var(--color-text-primary);
        font-weight: 500;
    }

    .guides-code-block {
        background: var(--color-bg-primary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-md);
        overflow-x: auto;
        margin: var(--spacing-md) 0;
    }

    .guides-code-block pre {
        margin: 0;
        color: var(--color-text-primary);
        font-family: 'Monaco', 'Courier New', monospace;
        font-size: 0.9em;
    }

    .guides-image-container {
        margin: var(--spacing-lg) 0;
        text-align: center;
    }

    .guides-image {
        max-width: 100%;
        height: auto;
        border-radius: var(--radius);
        border: 1px solid var(--color-border);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .guides-divider {
        border: none;
        border-top: 1px solid var(--color-border);
        margin: var(--spacing-xl) 0;
    }

    @media (max-width: 768px) {
        .guides-container { padding: var(--spacing-md); }
        .guides-card { padding: var(--spacing-lg); }
        .guides-title { font-size: 1.8em; }
        .guides-section-title { font-size: 1.2em; }
    }
</style>

<div class="guides-container">
    <div class="guides-card">
        <div class="guides-header">
            <h1 class="guides-title">The Tor Network Guide</h1>
            <p style="color: var(--color-text-secondary); margin: 0;">Understanding and Using Tor for Privacy and Security</p>
        </div>

        <div class="guides-content">
            <p>Tor, short for "The Onion Router," refers to a network and software system designed to provide anonymity and privacy on the internet. By encrypting user traffic and routing it through multiple servers, Tor enables individuals to conceal their identity and online activities.</p>

            <h2 class="guides-section-title">What is Tor?</h2>
            <p>Tor is a valuable tool for privacy-conscious individuals, activists, journalists, and those affected by censorship. The system effectively prevents online tracking, circumvents censorship, and enhances security.</p>

            <h3 class="guides-subtitle">Core Purpose</h3>
            <p>The primary purpose of Tor is to offer users an anonymous internet experience. Typically, information such as your IP address, browser fingerprint, and browsing history is tracked and logged by various platforms while you browse online. Tor prevents this data from being collected and monitored.</p>

            <h3 class="guides-subtitle">How It Works</h3>
            <p>Once your internet traffic enters the Tor network, it passes through three different nodes: the entry node, the relay node, and the exit node. During this process, the data is protected by layered encryption, and each node knows only about the previous and next node. This structure makes tracking user identity and data extremely difficult.</p>

            <h2 class="guides-section-title">Common Misconceptions About Tor</h2>

            <h3 class="guides-subtitle">Tor is Illegal</h3>
            <div class="guides-highlight">
                Using Tor is entirely legal in most countries. It is a legitimate tool for enhancing privacy and security. While some individuals may misuse Tor, its primary purpose is to protect user anonymity and support freedom of expression.
            </div>

            <h3 class="guides-subtitle">Tor Guarantees Absolute Anonymity</h3>
            <p>While Tor significantly enhances privacy by hiding your IP address and encrypting your internet traffic, it does not guarantee absolute anonymity. Users can still compromise their privacy through actions like logging into personal accounts or sharing identifiable information.</p>

            <h3 class="guides-subtitle">Tor Makes Internet Unusably Slow</h3>
            <p>While Tor's layered encryption and routing through multiple nodes can reduce connection speeds, the impact is often manageable for most browsing activities. The slight trade-off in speed is a result of the robust security and privacy measures Tor provides.</p>

            <h3 class="guides-subtitle">Tor is Only for the Dark Web</h3>
            <p>While Tor does allow access to .onion sites, it is not limited to such use. The majority of Tor users leverage the network for everyday browsing, especially in regions with heavy censorship or surveillance.</p>

            <h2 class="guides-section-title">Understanding Tor's Structure</h2>

            <h3 class="guides-subtitle">Entry Node</h3>
            <p>The entry node is the first point where the user connects to the Tor network. This node is the only part of the network that can see the user's real IP address. However, it does not share this information with the rest of the Tor network or the destination site. Instead, it encrypts the data and forwards it to the next node.</p>

            <div class="guides-highlight">
                <strong>Example:</strong> A user wants to access a news website using Tor. When the user's computer connects to the Tor network, it selects an entry node (e.g., a server located in Germany). This node knows the user's real IP address but has no knowledge that the user is trying to connect to a news website.
            </div>

            <h3 class="guides-subtitle">Relay Node</h3>
            <p>The relay node is an intermediate point that anonymizes and routes the data traffic. Relay nodes do not know where the data originated or where it is ultimately headed. Their sole function is to pass the data to the next node.</p>

            <div class="guides-highlight">
                <strong>Example:</strong> After leaving the entry node in Germany, the user's traffic reaches a relay node in France. This node knows that the data came from Germany but does not identify the user.
            </div>

            <h3 class="guides-subtitle">Exit Node</h3>
            <p>The exit node is the final point in the Tor network where the traffic exits and reaches its intended destination. This node decrypts the last layer of encryption and sends the data to its final destination on the internet.</p>

            <h2 class="guides-section-title">Downloading Tor Browser</h2>

            <h3 class="guides-subtitle">Step-by-Step Installation</h3>
            <ol class="guides-ordered-list">
                <li>Visit the official Tor Project website at <code>https://www.torproject.org/download/</code></li>
                <li>Select the download option that matches your operating system (Windows, macOS, or Linux)</li>
                <li>Once downloaded, open the installer</li>
                <li>Follow the on-screen installation instructions</li>
                <li>Complete the installation process and launch Tor Browser</li>
            </ol>

            <h3 class="guides-subtitle">Initial Setup</h3>
            <p>When you first launch Tor Browser, it will automatically establish a connection to the Tor network. You will see a series of screens indicating the connection progress. Once connected, the browser is ready to use.</p>

            <h2 class="guides-section-title">Configuring Tor Browser Security</h2>

            <h3 class="guides-subtitle">Security Level Settings</h3>
            <p>For additional protection, we recommend using the pre-configured security settings in Tor Browser:</p>
            <ol class="guides-ordered-list">
                <li>Locate the shield icon on the Tor Browser's toolbar</li>
                <li>Click on this icon to open the menu</li>
                <li>Select "Settings" from the options</li>
                <li>Navigate to the Security Level settings page</li>
                <li>Choose the appropriate security level for your needs</li>
            </ol>

            <div class="guides-highlight">
                The "Safest" setting disables JavaScript, which can potentially be used to compromise your anonymity. This is recommended for maximum security.
            </div>

            <h2 class="guides-section-title">Best Practices for Tor Usage</h2>

            <ul class="guides-list">
                <li>Always use the latest version of Tor Browser</li>
                <li>Enable the "Safest" security level for maximum protection</li>
                <li>Avoid maximizing your browser window (fingerprinting prevention)</li>
                <li>Disable plugins and extensions that could compromise anonymity</li>
                <li>Never enable JavaScript on untrusted sites</li>
                <li>Do not open downloads in Tor Browser without caution</li>
                <li>Do not use personal information while using Tor</li>
                <li>Use HTTPS whenever possible for additional encryption</li>
                <li>Keep your operating system updated</li>
            </ul>

            <h2 class="guides-section-title">Troubleshooting Tor Connection</h2>

            <h3 class="guides-subtitle">Connection Issues</h3>
            <p>If you have difficulty connecting to Tor, try the following:</p>
            <ul class="guides-list">
                <li>Check your internet connection</li>
                <li>Verify that Tor is not blocked by your ISP</li>
                <li>Try using a bridge to connect to the Tor network</li>
                <li>Ensure you have the latest version of Tor Browser</li>
            </ul>

            <h3 class="guides-subtitle">Performance Issues</h3>
            <p>Slow connections are normal when using Tor. If you experience extremely slow speeds, consider using a bridge or checking your system resources.</p>

            <div class="guides-highlight">
                For more help, visit the Tor Project support page at <code>https://www.torproject.org/support/</code>
            </div>
        </div>
    </div>
</div>
@endsection