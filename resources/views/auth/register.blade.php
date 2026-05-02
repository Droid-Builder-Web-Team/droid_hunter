<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Hunter Account - Droid Hunter</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="container" style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 80vh;">
        <header class="header">
            <h1>Join the Hunt</h1>
            <p>Create a secure account to sync your collection across devices.</p>
        </header>

        <div class="droid-card" style="padding: 2.5rem; width: 100%; max-width: 400px;">
            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div style="margin-bottom: 1.5rem;">
                    <label for="name" style="display: block; margin-bottom: 0.5rem; color: var(--text-secondary); text-align: left;">Nickname</label>
                    <input type="text" name="name" id="name" required placeholder="e.g. Skywalker" 
                           style="width: 100%; padding: 0.8rem; border-radius: 0.5rem; border: 1px solid var(--accent); background: var(--bg-color); color: white; font-size: 1rem;">
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label for="email" style="display: block; margin-bottom: 0.5rem; color: var(--text-secondary); text-align: left;">Email Address</label>
                    <input type="email" name="email" id="email" required placeholder="hunter@example.com" 
                           style="width: 100%; padding: 0.8rem; border-radius: 0.5rem; border: 1px solid var(--accent); background: var(--bg-color); color: white; font-size: 1rem;">
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label for="password" style="display: block; margin-bottom: 0.5rem; color: var(--text-secondary); text-align: left;">Secret Key (Password)</label>
                    <input type="password" name="password" id="password" required placeholder="Min 8 characters" 
                           style="width: 100%; padding: 0.8rem; border-radius: 0.5rem; border: 1px solid var(--accent); background: var(--bg-color); color: white; font-size: 1rem;">
                </div>

                <div style="margin-bottom: 2rem;">
                    <label for="password_confirmation" style="display: block; margin-bottom: 0.5rem; color: var(--text-secondary); text-align: left;">Confirm Key</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required 
                           style="width: 100%; padding: 0.8rem; border-radius: 0.5rem; border: 1px solid var(--accent); background: var(--bg-color); color: white; font-size: 1rem;">
                </div>

                <button type="submit" style="width: 100%; padding: 1rem; border-radius: 0.5rem; border: none; background: var(--primary); color: var(--bg-color); font-weight: 800; cursor: pointer; font-size: 1rem;">
                    CREATE ACCOUNT
                </button>
            </form>

            <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--accent); text-align: center;">
                <a href="{{ route('login') }}" style="color: var(--text-secondary); text-decoration: none; font-size: 0.9rem;">
                    Already a hunter? <span style="color: var(--primary);">Login here</span>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
