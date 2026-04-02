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

    .guides-divider {
        border: none;
        border-top: 1px solid var(--color-border);
        margin: var(--spacing-xl) 0;
    }
</style>

<div class="guides-container">
    <div class="guides-card">
        <div class="guides-header">
            <h1 class="guides-title">KeePassXC User Guide</h1>
            <p style="color: var(--color-text-secondary); margin: 0;">Secure Password Management Made Easy</p>
        </div>

        <div class="guides-content">
            <h2 class="guides-section-title">What is KeePassXC?</h2>
            <p>KeePassXC is a free, open-source password manager that helps you create and manage strong passwords for all your online accounts. It uses advanced encryption to protect your passwords securely on your computer.</p>

            <h2 class="guides-section-title">Downloading KeePassXC</h2>
            <p>KeePassXC is available for Windows and Linux. Go to <code>https://keepassxc.org/download</code> to download the appropriate version for your system.</p>

            <h3 class="guides-subtitle">Windows Installation</h3>
            <ol class="guides-ordered-list">
                <li>Visit <code>https://keepassxc.org/download/windows</code></li>
                <li>Download the Windows MSI installer</li>
                <li>Double-click the KeePassXC installer file</li>
                <li>Follow the on-screen installation wizard</li>
                <li>Select your preferred installation location</li>
                <li>Choose additional options (desktop shortcut, startup option)</li>
                <li>Click Install and wait for completion</li>
                <li>Launch KeePassXC when installation is finished</li>
            </ol>

            <h3 class="guides-subtitle">Linux Installation</h3>
            <p>You have multiple options to install KeePassXC on Linux systems:</p>

            <h4 style="font-weight: 500; color: var(--color-text-primary); margin-top: 16px;">AppImage Method</h4>
            <ol class="guides-ordered-list">
                <li>Go to <code>https://keepassxc.org/download/#linux</code></li>
                <li>Download the AppImage version</li>
                <li>Right-click the downloaded file and select Properties</li>
                <li>Go to the Permissions tab</li>
                <li>Check "Allow executing file as program"</li>
                <li>Double-click the AppImage to launch it</li>
            </ol>

            <h4 style="font-weight: 500; color: var(--color-text-primary); margin-top: 16px;">Flatpak Method</h4>
            <div class="guides-code-block">
                <pre>flatpak remote-add --user --if-not-exists flathub https://flathub.org/repo/flathub.flatpakrepo
flatpak install --user flathub org.keepassxc.KeePassXC</pre>
            </div>

            <h4 style="font-weight: 500; color: var(--color-text-primary); margin-top: 16px;">Snap Method</h4>
            <div class="guides-code-block">
                <pre>sudo snap install keepassxc</pre>
            </div>

            <h4 style="font-weight: 500; color: var(--color-text-primary); margin-top: 16px;">Ubuntu PPA Method</h4>
            <div class="guides-code-block">
                <pre>sudo add-apt-repository ppa:phoerious/keepassxc
sudo apt update
sudo apt install keepassxc</pre>
            </div>

            <h2 class="guides-section-title">Understanding the Interface</h2>

            <h3 class="guides-subtitle">Main Layout Areas</h3>
            <ul class="guides-list">
                <li><strong>Groups Panel:</strong> Organize your passwords into logical groups and subgroups</li>
                <li><strong>Tags Panel:</strong> Create custom tags for quick filtering and searching</li>
                <li><strong>Entries Panel:</strong> View all passwords for the selected group</li>
                <li><strong>Preview Panel:</strong> Quick view of username and password for selected entry</li>
            </ul>

            <h3 class="guides-subtitle">Toolbar Functions</h3>
            <p>The toolbar provides quick access to common tasks:</p>
            <ul class="guides-list">
                <li>Database operations (open, save, lock database)</li>
                <li>Entry management (create, edit, delete entries)</li>
                <li>Password data (copy username, password, URL)</li>
                <li>Tools (password generator, settings)</li>
                <li>Search functionality</li>
            </ul>

            <h2 class="guides-section-title">Creating Your First Database</h2>

            <ol class="guides-ordered-list">
                <li>Open KeePassXC</li>
                <li>Click File → New Database</li>
                <li>Choose a location and filename for your database</li>
                <li>Set a strong master password (your most important password)</li>
                <li>Optionally add a key file for additional security</li>
                <li>Click Save to create your database</li>
            </ol>

            <div class="guides-highlight">
                Your master password is the only key to your entire password database. Choose something strong and memorable, but don't write it down.
            </div>

            <h2 class="guides-section-title">Managing Passwords</h2>

            <h3 class="guides-subtitle">Creating a New Entry</h3>
            <ol class="guides-ordered-list">
                <li>Select the group where you want to add the password</li>
                <li>Click the "New Entry" button in the toolbar</li>
                <li>Fill in the entry details:
                    <ul class="guides-list">
                        <li>Title (website or service name)</li>
                        <li>Username or email</li>
                        <li>Password (use the password generator)</li>
                        <li>URL (optional, for auto-fill)</li>
                        <li>Notes (any additional information)</li>
                    </ul>
                </li>
                <li>Click OK to save the entry</li>
            </ol>

            <h3 class="guides-subtitle">Using the Password Generator</h3>
            <ol class="guides-ordered-list">
                <li>Click the Tools menu → Password Generator</li>
                <li>Configure password options:
                    <ul class="guides-list">
                        <li>Length (12+ characters recommended)</li>
                        <li>Character types (uppercase, lowercase, numbers, symbols)</li>
                        <li>Exclude similar characters option</li>
                    </ul>
                </li>
                <li>Click Generate to create a random password</li>
                <li>Click Copy or Accept to use the password</li>
            </ol>

            <h2 class="guides-section-title">Password Generator Tips</h2>
            <ul class="guides-list">
                <li>Use at least 12 characters for strong passwords</li>
                <li>Include a mix of uppercase, lowercase, numbers, and symbols</li>
                <li>Enable "Exclude similar characters" to avoid confusion</li>
                <li>Generate new passwords for each new account</li>
                <li>Never reuse passwords across different sites</li>
            </ul>

            <h2 class="guides-section-title">Security Best Practices</h2>
            <ul class="guides-list">
                <li>Use a strong master password with at least 12 characters</li>
                <li>Enable key file authentication for additional security</li>
                <li>Backup your database regularly to a secure location</li>
                <li>Keep KeePassXC updated to the latest version</li>
                <li>Lock your database when stepping away from your computer</li>
                <li>Use unique passwords for every online account</li>
                <li>Never share your master password with anyone</li>
                <li>Store sensitive information only in your database</li>
            </ul>

            <h2 class="guides-section-title">Backing Up Your Database</h2>
            <ol class="guides-ordered-list">
                <li>Go to File → Export</li>
                <li>Choose your export format (KDBX recommended)</li>
                <li>Select a secure location for the backup</li>
                <li>Consider storing backups on encrypted external drives</li>
                <li>Keep multiple backup copies in different locations</li>
            </ol>

            <div class="guides-highlight">
                Regular backups protect you against accidental data loss. Store them securely and keep them updated.
            </div>

            <h2 class="guides-section-title">Troubleshooting</h2>

            <h3 class="guides-subtitle">Forgotten Master Password</h3>
            <p>Unfortunately, if you forget your master password, your database is inaccessible. This is by design for security reasons. Keep your password in a safe place.</p>

            <h3 class="guides-subtitle">Database Won't Open</h3>
            <p>If your database won't open, try the following:</p>
            <ul class="guides-list">
                <li>Ensure you're entering the correct master password</li>
                <li>Check that the database file hasn't been corrupted</li>
                <li>Verify you have a recent backup</li>
                <li>Try opening your backup copy</li>
            </ul>

            <h3 class="guides-subtitle">Need Help?</h3>
            <p>For additional support, visit the KeePassXC documentation at <code>https://keepassxc.org/docs/</code></p>
        </div>
    </div>
</div>
@endsection