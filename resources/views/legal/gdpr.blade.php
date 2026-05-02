<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GDPR & Data Control - Droid Hunter</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="container" style="max-width: 800px; padding: 2rem; line-height: 1.6;">
        <header class="header">
            <h1>Your Data Rights</h1>
            <p>Full control over your Hunter history.</p>
        </header>

        <div class="droid-card" style="padding: 2rem; margin-top: 2rem;">
            <h2 style="color: var(--primary);">Data Portability</h2>
            <p>Under GDPR, you have the right to request a copy of your data. Since we only store your scan history, you can see all your data directly on your <a href="{{ route('registry.index') }}" style="color: var(--primary);">Registry</a> page.</p>

            <h2 style="color: var(--primary);">Right to Erasure (Delete Data)</h2>
            <p>You can delete all your data and scans at any time. This action is permanent and cannot be undone.</p>
            
            <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid #ef4444; padding: 1.5rem; border-radius: 0.5rem; margin-top: 1rem;">
                <h4 style="color: #ef4444; margin-top: 0;">Danger Zone</h4>
                <p style="font-size: 0.9rem; margin-bottom: 1.5rem;">Deleting your account will remove your profile, all spotted droid records, and security settings from our vault.</p>
                
                @auth
                    <form action="{{ route('profile.delete') }}" method="POST" onsubmit="return confirm('Are you absolutely sure? This will delete all your scans and your account forever.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background: #ef4444; color: white; border: none; padding: 0.8rem 1.5rem; border-radius: 0.5rem; font-weight: 800; cursor: pointer;">
                            DELETE MY ACCOUNT & DATA
                        </button>
                    </form>
                @else
                    <p style="font-size: 0.85rem; font-style: italic;">To delete an account, please sign in first. To clear guest data, simply clear your browser's cookies and local storage.</p>
                @endauth
            </div>

            <h2 style="color: var(--primary); margin-top: 2rem;">Data Processing</h2>
            <p>Your data is processed within the UK and EU. We do not transfer your data to third countries without adequate protection.</p>
        </div>

        <div style="margin-top: 2rem; text-align: center;">
            <a href="{{ route('registry.index') }}" style="color: var(--text-secondary); text-decoration: none;">&larr; Back to Registry</a>
        </div>
    </div>
</body>
</html>
