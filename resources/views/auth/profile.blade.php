<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hunter Profile - Droid Hunter</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="container">
        <header class="header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem;">
            <a href="{{ route('registry.index') }}" style="color: var(--primary); text-decoration: none;">&larr; Back to Registry</a>
            <h1 style="margin: 0; font-size: 1.5rem;">Security Vault</h1>
        </header>

        <div class="grid" style="display: flex; flex-direction: column; gap: 2rem; max-width: 600px; margin: 0 auto;">
            <!-- Profile Info -->
            <div class="droid-card" style="padding: 2rem;">
                <h3 style="margin-top: 0; color: var(--primary);">Hunter Identity</h3>
                <div style="margin-bottom: 1rem;">
                    <label style="font-size: 0.8rem; color: var(--text-secondary);">Nickname</label>
                    <div style="font-size: 1.1rem; font-weight: 600;">{{ auth()->user()->name }}</div>
                </div>
                <div>
                    <label style="font-size: 0.8rem; color: var(--text-secondary);">Data Link (Email)</label>
                    <div style="font-size: 1.1rem; font-weight: 600;">{{ auth()->user()->email }}</div>
                </div>
            </div>

            <!-- Two Factor Auth -->
            <div class="droid-card" style="padding: 2rem; border-left: 4px solid {{ auth()->user()->two_factor_confirmed_at ? 'var(--primary)' : 'var(--accent)' }};">
                <h3 style="margin-top: 0; color: var(--primary);">Multi-Factor Authentication (MFA)</h3>
                <p style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 1.5rem;">
                    Add an extra layer of security to your collection using an authenticator app.
                </p>

                @if(! auth()->user()->two_factor_secret)
                    {{-- Enable MFA --}}
                    <form method="POST" action="{{ route('two-factor.enable') }}">
                        @csrf
                        <button type="submit" style="background: var(--primary); color: var(--bg-color); border: none; padding: 0.8rem 1.5rem; border-radius: 0.5rem; font-weight: 800; cursor: pointer;">
                            ENABLE MFA
                        </button>
                    </form>
                @else
                    {{-- MFA is Enabled --}}
                    @if(! auth()->user()->two_factor_confirmed_at)
                        {{-- Confirm MFA --}}
                        <div style="background: rgba(0,0,0,0.2); padding: 1.5rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                            <p style="font-size: 0.85rem; margin-bottom: 1rem;">Scan this QR code with your authenticator app and enter the code to confirm.</p>
                            <div style="background: white; padding: 1rem; display: inline-block; border-radius: 0.5rem; margin-bottom: 1rem;">
                                {!! auth()->user()->twoFactorQrCodeSvg() !!}
                            </div>
                            
                            <form method="POST" action="{{ route('two-factor.confirm') }}">
                                @csrf
                                <input type="text" name="code" required placeholder="Enter 6-digit code" 
                                       style="width: 100%; max-width: 200px; padding: 0.6rem; border-radius: 0.3rem; border: 1px solid var(--accent); background: var(--bg-color); color: white; margin-bottom: 1rem; display: block;">
                                <button type="submit" style="background: var(--primary); color: var(--bg-color); border: none; padding: 0.6rem 1rem; border-radius: 0.3rem; font-weight: 700; cursor: pointer;">
                                    CONFIRM MFA
                                </button>
                            </form>
                        </div>
                    @else
                        {{-- MFA is Confirmed --}}
                        <div style="color: #4ade80; font-weight: 600; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                            <span style="font-size: 1.2rem;">✓</span> MFA ACTIVE
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <details style="cursor: pointer;">
                                <summary style="font-size: 0.85rem; color: var(--text-secondary);">Show Recovery Codes</summary>
                                <div style="margin-top: 1rem; background: rgba(0,0,0,0.2); padding: 1rem; border-radius: 0.5rem; font-family: monospace; font-size: 0.8rem;">
                                    @foreach (auth()->user()->recoveryCodes() as $code)
                                        <div>{{ $code }}</div>
                                    @endforeach
                                </div>
                            </details>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('two-factor.disable') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background: none; border: 1px solid #ef4444; color: #ef4444; padding: 0.6rem 1rem; border-radius: 0.5rem; font-size: 0.8rem; cursor: pointer;">
                            DISABLE MFA
                        </button>
                    </form>
                @endif
            </div>

            <!-- Password Update -->
            <div class="droid-card" style="padding: 2rem;">
                <h3 style="margin-top: 0; color: var(--primary);">Update Secret Key</h3>
                    <form method="POST" action="{{ route('user-password.update') }}">
                        @csrf
                        @method('PUT')
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; font-size: 0.8rem; color: var(--text-secondary);">Current Key</label>
                            <input type="password" name="current_password" required style="width: 100%; padding: 0.6rem; border-radius: 0.3rem; border: 1px solid var(--accent); background: var(--bg-color); color: white;">
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; font-size: 0.8rem; color: var(--text-secondary);">New Key</label>
                            <input type="password" name="password" required style="width: 100%; padding: 0.6rem; border-radius: 0.3rem; border: 1px solid var(--accent); background: var(--bg-color); color: white;">
                        </div>
                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: block; font-size: 0.8rem; color: var(--text-secondary);">Confirm New Key</label>
                            <input type="password" name="password_confirmation" required style="width: 100%; padding: 0.6rem; border-radius: 0.3rem; border: 1px solid var(--accent); background: var(--bg-color); color: white;">
                        </div>
                        <button type="submit" style="background: var(--accent); color: white; border: none; padding: 0.8rem 1.5rem; border-radius: 0.5rem; font-weight: 700; cursor: pointer;">
                            UPDATE KEY
                        </button>
                    </form>
                </div>

                <!-- Danger Zone -->
                <div class="droid-card" style="padding: 2rem; border: 1px solid #ef4444; background: rgba(239, 68, 68, 0.05);">
                    <h3 style="margin-top: 0; color: #ef4444;">Danger Zone</h3>
                    <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 1.5rem;">
                        Permanently delete your account and all your collected droids. This cannot be undone.
                    </p>
                    <a href="{{ route('gdpr') }}" style="color: #ef4444; font-size: 0.9rem; font-weight: 600;">Request Data Erasure &larr;</a>
                </div>
            </div>

            <footer style="margin-top: 4rem; text-align: center; border-top: 1px solid var(--accent); padding-top: 2rem; padding-bottom: 2rem;">
                <div style="display: flex; gap: 2rem; justify-content: center; font-size: 0.8rem;">
                    <a href="{{ route('privacy') }}" style="color: var(--text-secondary); text-decoration: none;">Privacy Policy</a>
                    <a href="{{ route('gdpr') }}" style="color: var(--text-secondary); text-decoration: none;">GDPR & Data</a>
                </div>
                <p style="font-size: 0.7rem; color: var(--text-secondary); margin-top: 1rem; opacity: 0.5;">Droid Hunter &bull; Built for Droid Builders UK</p>
            </footer>
        </div>
</body>
</html>
