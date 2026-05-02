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

        <div class="droid-card" style="padding: 3rem; width: 100%; max-width: 400px;">
            <form action="{{ route('login.quick') }}" method="POST">
                @csrf
                <div style="margin-bottom: 2rem;">
                    <label for="nickname" style="display: block; margin-bottom: 0.5rem; color: var(--text-secondary); text-align: left;">Hunter Nickname</label>
                    <input type="text" name="nickname" id="nickname" required placeholder="e.g. Skywalker" 
                           style="width: 100%; padding: 0.8rem; border-radius: 0.5rem; border: 1px solid var(--accent); background: var(--bg-color); color: white; font-size: 1rem;">
                </div>

                <button type="submit" style="width: 100%; padding: 1rem; border-radius: 0.5rem; border: none; background: var(--primary); color: var(--bg-color); font-weight: 800; cursor: pointer; font-size: 1rem;">
                    START HUNTING
                </button>
            </form>

            <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--accent);">
                <p style="font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 1rem;">Already have an account?</p>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <a href="{{ route('social.redirect', 'google') }}" style="color: var(--text-secondary); text-decoration: none; font-size: 0.8rem;">Continue with Google</a>
                    <a href="{{ route('social.redirect', 'apple') }}" style="color: var(--text-secondary); text-decoration: none; font-size: 0.8rem;">Continue with Apple</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
