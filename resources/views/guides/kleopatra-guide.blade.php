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
        padding: 8px 0 8px 16px;
        border-left: 3px solid var(--color-accent-light);
        margin-bottom: 8px;
        color: var(--color-text-secondary);
    }

    .guides-ordered-list {
        list-style: decimal;
        padding-left: 32px;
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
</style>

<div class="guides-container">
    <div class="guides-card">
        <div class="guides-header">
            <h1 class="guides-title">Kleopatra User Guide</h1>
            <p style="color: var(--color-text-secondary); margin: 0;">PGP Encryption and Digital Signatures Made Simple</p>
        </div>

        <div class="guides-content">
            <h2 class="guides-section-title">Understanding PGP Encryption</h2>
            <p>PGP (Pretty Good Privacy) represents one of the most trusted and reliable methods for securing digital communications. By using public key cryptography, PGP allows you to communicate securely, verify message authenticity, and protect sensitive files from unauthorized access.</p>

            <h3 class="guides-subtitle">What is Kleopatra?</h3>
            <p>Kleopatra is a certificate manager and graphical user interface (GUI) for GnuPG. It simplifies PGP operations and makes encryption accessible to everyone.</p>

            <h3 class="guides-subtitle">Core Functions</h3>
            <ul class="guides-list">
                <li><strong>Message Encryption:</strong> Ensures only intended recipients can read messages</li>
                <li><strong>Digital Signatures:</strong> Verifies sender identity and ensures message integrity</li>
                <li><strong>File Protection:</strong> Secures sensitive documents and enables safe file sharing</li>
            </ul>

            <h2 class="guides-section-title">Installing Kleopatra</h2>

            <h3 class="guides-subtitle">Windows Installation</h3>
            <ol class="guides-ordered-list">
                <li>Download Gpg4win from <code>https://www.gpg4win.org/</code></li>
                <li>Run the installer with administrative privileges</li>
                <li>Ensure Kleopatra is selected during component selection</li>
                <li>Complete the installation process</li>
                <li>Launch Kleopatra to begin your setup</li>
            </ol>

            <h3 class="guides-subtitle">Linux Installation</h3>
            <p>For Ubuntu/Debian systems, open your terminal and run:</p>
            <div class="guides-code-block">
                <pre>sudo apt-get update
sudo apt-get install kleopatra</pre>
            </div>

            <h2 class="guides-section-title">Creating Your First Key Pair</h2>

            <h3 class="guides-subtitle">Step-by-Step Key Generation</h3>
            <ol class="guides-ordered-list">
                <li>Launch Kleopatra</li>
                <li>Click the "New Key Pair" button or navigate to File → New OpenPGP Key Pair</li>
                <li>Enter your identity information:
                    <ul class="guides-list">
                        <li>Full Name (use a pseudonym if privacy is crucial)</li>
                        <li>Email Address (consider using a dedicated email)</li>
                        <li>Optional Comment (to help identify the key's purpose)</li>
                    </ul>
                </li>
                <li>Click Next to proceed</li>
                <li>Create a strong passphrase to protect your private key</li>
                <li>Complete the key generation process</li>
            </ol>

            <h3 class="guides-subtitle">Securing Your Private Key</h3>
            <p>Create a strong passphrase with:</p>
            <ul class="guides-list">
                <li>Mix of uppercase and lowercase letters</li>
                <li>Numbers and special characters</li>
                <li>At least 12 characters in length</li>
                <li>Something memorable but complex</li>
            </ul>

            <div class="guides-highlight">
                Store your private key securely. Use encrypted storage or offline backups. Never share or expose your private key.
            </div>

            <h2 class="guides-section-title">Managing Your Keys</h2>

            <h3 class="guides-subtitle">Importing Keys</h3>
            <ol class="guides-ordered-list">
                <li>Click "Import" on the main interface</li>
                <li>Select the key file or paste the key text</li>
                <li>Verify the key fingerprint</li>
                <li>The key is now imported and ready to use</li>
            </ol>

            <h3 class="guides-subtitle">Exporting Your Public Key</h3>
            <ol class="guides-ordered-list">
                <li>Select your key pair in the main interface</li>
                <li>Click "Export" from the menu</li>
                <li>Choose the ASCII armor format</li>
                <li>Save or copy the key text</li>
                <li>Share this public key with others securely</li>
            </ol>

            <h2 class="guides-section-title">Encrypting Messages</h2>

            <h3 class="guides-subtitle">Using the Notepad Feature</h3>
            <ol class="guides-ordered-list">
                <li>Open Kleopatra's Notepad</li>
                <li>Type or paste your message</li>
                <li>Click the "Recipients" tab</li>
                <li>Select encryption options:
                    <ul class="guides-list">
                        <li>"Encrypt for me" (retain your ability to read the message)</li>
                        <li>"Encrypt for others" (choose recipients)</li>
                        <li>"Sign as" (add your digital signature)</li>
                    </ul>
                </li>
                <li>Click "Sign/Encrypt Notepad" to process your message</li>
                <li>Copy or save the encrypted message</li>
            </ol>

            <h2 class="guides-section-title">Decrypting Messages</h2>

            <ol class="guides-ordered-list">
                <li>Open Kleopatra's Notepad</li>
                <li>Paste the encrypted message</li>
                <li>Click "Decrypt/Verify"</li>
                <li>Enter your passphrase when prompted</li>
                <li>Review the decrypted content</li>
            </ol>

            <h2 class="guides-section-title">File Encryption</h2>

            <ol class="guides-ordered-list">
                <li>Select "Sign/Encrypt" from the main menu</li>
                <li>Choose the file you wish to protect</li>
                <li>Select the intended recipients</li>
                <li>Add your signature if desired</li>
                <li>Click Encrypt to save the encrypted file</li>
            </ol>

            <h2 class="guides-section-title">Creating Digital Signatures</h2>

            <ol class="guides-ordered-list">
                <li>Compose your message in Notepad</li>
                <li>Click the "Recipients" tab</li>
                <li>Select "Sign as" without choosing encryption</li>
                <li>Pick your signing key</li>
                <li>Click "Sign/Encrypt" to generate the signed message</li>
            </ol>

            <div class="guides-highlight">
                Digital signatures prove that a message came from you and hasn't been modified. They don't hide the message content, only authenticate it.
            </div>

            <h2 class="guides-section-title">Privacy Recommendations</h2>

            <h3 class="guides-subtitle">Key Management</h3>
            <ul class="guides-list">
                <li>Create separate key pairs for different purposes</li>
                <li>Keep your identities separated for maximum privacy</li>
                <li>Rotate keys periodically for enhanced security</li>
                <li>Maintain secure offline backups of your keys</li>
                <li>Consider using hardware security keys</li>
            </ul>

            <h3 class="guides-subtitle">Secure Communication</h3>
            <ul class="guides-list">
                <li>Exchange keys over encrypted channels</li>
                <li>Always verify recipient identities before encrypting</li>
                <li>Use secure deletion practices for sensitive data</li>
                <li>Never share your private key with anyone</li>
                <li>Keep your software updated</li>
            </ul>

            <div class="guides-highlight">
                Security is an ongoing process. Stay updated on best practices and protect your private keys at all times.
            </div>

            <h2 class="guides-section-title">Troubleshooting</h2>

            <h3 class="guides-subtitle">Forgotten Passphrase</h3>
            <p>If you forget your passphrase, you cannot decrypt messages meant for that key. Write it down or store it safely.</p>

            <h3 class="guides-subtitle">Key Not Found</h3>
            <p>Ensure you have imported the recipient's public key before trying to encrypt for them.</p>

            <h3 class="guides-subtitle">Need Help?</h3>
            <p>For additional support, visit the GnuPG documentation at <code>https://gnupg.org/documentation/</code></p>
        </div>
    </div>
</div>
@endsection