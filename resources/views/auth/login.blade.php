<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Droid Hunter</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="container" style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 80vh;">
        <header class="header">
            <h1>Droid Hunter</h1>
            <p>Enter a nickname to start your collection.</p>
        </header>

        <div class="droid-card" style="padding: 2.5rem; width: 100%; max-width: 400px;">
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div style="margin-bottom: 1.5rem;">
                    <label for="email" style="display: block; margin-bottom: 0.5rem; color: var(--text-secondary); text-align: left;">Email Address</label>
                    <input type="email" name="email" id="email" required placeholder="hunter@example.com" 
                           style="width: 100%; padding: 0.8rem; border-radius: 0.5rem; border: 1px solid var(--accent); background: var(--bg-color); color: white; font-size: 1rem;">
                </div>

                <div style="margin-bottom: 2rem;">
                    <label for="password" style="display: block; margin-bottom: 0.5rem; color: var(--text-secondary); text-align: left;">Password</label>
                    <input type="password" name="password" id="password" required 
                           style="width: 100%; padding: 0.8rem; border-radius: 0.5rem; border: 1px solid var(--accent); background: var(--bg-color); color: white; font-size: 1rem;">
                </div>

                <button type="submit" style="width: 100%; padding: 1rem; border-radius: 0.5rem; border: none; background: var(--primary); color: var(--bg-color); font-weight: 800; cursor: pointer; font-size: 1rem; margin-bottom: 1rem;">
                    SIGN IN
                </button>
            </form>

            <div style="text-align: center; margin-bottom: 2rem;">
                <a href="{{ route('register') }}" style="color: var(--text-secondary); text-decoration: none; font-size: 0.9rem;">
                    New hunter? <span style="color: var(--primary);">Create account</span>
                </a>
            </div>

            <div style="margin-top: 1rem; padding-top: 1.5rem; border-top: 1px solid var(--accent);">
                <p style="font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 1px;">Or use a data link</p>
                <div style="display: flex; gap: 1rem; justify-content: center;">
                    <a href="{{ route('social.redirect', 'google') }}" style="flex: 1; text-align: center; padding: 0.7rem; border-radius: 0.5rem; background: #fff; color: #000; text-decoration: none; font-size: 0.8rem; font-weight: 600;">Google</a>
                    <a href="{{ route('social.redirect', 'apple') }}" style="flex: 1; text-align: center; padding: 0.7rem; border-radius: 0.5rem; background: #000; color: #fff; border: 1px solid var(--accent); text-decoration: none; font-size: 0.8rem; font-weight: 600;">Apple</a>
                </div>
            </div>

            <div style="margin-top: 2rem; padding-top: 1rem; border-top: 1px solid var(--accent);">
                <details style="cursor: pointer;">
                    <summary style="font-size: 0.8rem; color: var(--text-secondary); list-style: none;">[ Anonymous Quick Start ]</summary>
                    <form action="{{ route('login.quick') }}" method="POST" style="margin-top: 1rem;">
                        @csrf
                        <input type="text" name="nickname" placeholder="Choose a temporary handle" required
                               style="width: 100%; padding: 0.6rem; border-radius: 0.3rem; border: 1px solid var(--accent); background: rgba(0,0,0,0.3); color: white; font-size: 0.9rem; margin-bottom: 0.5rem;">
                        <button type="submit" style="width: 100%; padding: 0.6rem; border-radius: 0.3rem; border: none; background: var(--accent); color: white; font-size: 0.8rem; font-weight: 600;">ENTER THE HUNT</button>
                    </form>
                </details>
            </div>
        </div>
    </div>
</body>
</html>
