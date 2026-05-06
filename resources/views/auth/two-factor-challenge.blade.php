<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two-Factor Authentication - Droid Hunter</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        function toggleRecovery() {
            const codeInput = document.getElementById('code-section');
            const recoveryInput = document.getElementById('recovery-section');
            if (codeInput.style.display === 'none') {
                codeInput.style.display = 'block';
                recoveryInput.style.display = 'none';
                document.getElementById('code').focus();
            } else {
                codeInput.style.display = 'none';
                recoveryInput.style.display = 'block';
                document.getElementById('recovery_code').focus();
            }
        }
    </script>
</head>
<body>
    <div class="container" style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 80vh;">
        <header class="header">
            <h1>Security Check</h1>
            <p>Additional clearance required.</p>
        </header>

        <div class="droid-card" style="padding: 2.5rem; width: 100%; max-width: 400px;">
            @if ($errors->any())
                <div style="background: rgba(255, 60, 60, 0.1); border: 1px solid var(--danger); color: var(--danger); padding: 1rem; margin-bottom: 1.5rem; border-radius: 0.5rem; font-size: 0.9rem;">
                    <ul style="margin: 0; padding-left: 1.2rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ url('/two-factor-challenge') }}" method="POST">
                @csrf

                <div id="code-section">
                    <p style="color: var(--text-secondary); text-align: left; margin-bottom: 1.5rem; font-size: 0.9rem;">
                        Please confirm access to your account by entering the authentication code provided by your authenticator application.
                    </p>
                    <div style="margin-bottom: 2rem;">
                        <label for="code" style="display: block; margin-bottom: 0.5rem; color: var(--text-secondary); text-align: left;">Authentication Code</label>
                        <input type="text" name="code" id="code" autofocus autocomplete="one-time-code"
                               style="width: 100%; padding: 0.8rem; border-radius: 0.5rem; border: 1px solid var(--accent); background: var(--bg-color); color: white; font-size: 1rem; letter-spacing: 2px; text-align: center;">
                    </div>
                </div>

                <div id="recovery-section" style="display: none;">
                    <p style="color: var(--text-secondary); text-align: left; margin-bottom: 1.5rem; font-size: 0.9rem;">
                        Please confirm access to your account by entering one of your emergency recovery codes.
                    </p>
                    <div style="margin-bottom: 2rem;">
                        <label for="recovery_code" style="display: block; margin-bottom: 0.5rem; color: var(--text-secondary); text-align: left;">Recovery Code</label>
                        <input type="text" name="recovery_code" id="recovery_code" autocomplete="one-time-code"
                               style="width: 100%; padding: 0.8rem; border-radius: 0.5rem; border: 1px solid var(--accent); background: var(--bg-color); color: white; font-size: 1rem; letter-spacing: 1px; text-align: center;">
                    </div>
                </div>

                <button type="submit" style="width: 100%; padding: 1rem; border-radius: 0.5rem; border: none; background: var(--primary); color: var(--bg-color); font-weight: 800; cursor: pointer; font-size: 1rem; margin-bottom: 1rem;">
                    VERIFY
                </button>
            </form>

            <div style="text-align: center; margin-top: 1rem;">
                <button type="button" onclick="toggleRecovery()" style="background: none; border: none; color: var(--text-secondary); cursor: pointer; text-decoration: underline; font-family: 'Rajdhani', sans-serif; font-size: 0.9rem;">
                    Use a recovery code
                </button>
            </div>
        </div>
    </div>
</body>
</html>
