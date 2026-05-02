<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - Droid Hunter</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="container" style="max-width: 800px; padding: 2rem; line-height: 1.6;">
        <header class="header">
            <h1>Privacy Policy</h1>
            <p>Transparency is key to our hunt.</p>
        </header>

        <div class="droid-card" style="padding: 2rem; margin-top: 2rem;">
            <h2 style="color: var(--primary);">1. Data We Collect</h2>
            <p>We believe in minimal data collection. We only store:</p>
            <ul>
                <li><strong>Identity</strong>: Your nickname and email address (if you choose to create an account).</li>
                <li><strong>Scans</strong>: The IDs of droids you have spotted and the time of the encounter.</li>
                <li><strong>Device ID</strong>: A random string stored on your device to keep your collection synced without an account.</li>
            </ul>

            <h2 style="color: var(--primary);">2. How We Use Data</h2>
            <p>Your data is used <strong>exclusively</strong> to provide the Droid Hunter experience. Specifically:</p>
            <ul>
                <li>To show you which droids you have collected.</li>
                <li>To sync your collection across different devices if you log in.</li>
            </ul>

            <h2 style="color: var(--primary);">3. No Marketing or Sharing</h2>
            <p>We do not use your data for marketing. We do not sell or share your information with any third parties. This project is purely for the enjoyment of the Droid Builders community.</p>

            <h2 style="color: var(--primary);">4. Security</h2>
            <p>We use industry-standard encryption and security practices (like Multi-Factor Authentication) to ensure your "Hunter Vault" remains secure.</p>

            <h2 style="color: var(--primary);">5. Your Rights</h2>
            <p>You have the right to access, update, or delete your data at any time. See our <a href="{{ route('gdpr') }}" style="color: var(--primary);">GDPR page</a> for details on how to wipe your history.</p>
        </div>

        <div style="margin-top: 2rem; text-align: center;">
            <a href="{{ route('registry.index') }}" style="color: var(--text-secondary); text-decoration: none;">&larr; Back to Registry</a>
        </div>
    </div>
</body>
</html>
