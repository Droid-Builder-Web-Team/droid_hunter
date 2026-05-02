<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Security Key - Droid Hunter</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="container" style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 80vh;">
        <header class="header">
            <h1>Security Check</h1>
            <p>Please confirm your secret key to continue.</p>
        </header>

        <div class="droid-card" style="padding: 2.5rem; width: 100%; max-width: 400px;">
            <form action="{{ route('password.confirm') }}" method="POST">
                @csrf
                <div style="margin-bottom: 2rem;">
                    <label for="password" style="display: block; margin-bottom: 0.5rem; color: var(--text-secondary); text-align: left;">Password</label>
                    <input type="password" name="password" id="password" required autofocus
                           style="width: 100%; padding: 0.8rem; border-radius: 0.5rem; border: 1px solid var(--accent); background: var(--bg-color); color: white; font-size: 1rem;">
                </div>

                <button type="submit" style="width: 100%; padding: 1rem; border-radius: 0.5rem; border: none; background: var(--primary); color: var(--bg-color); font-weight: 800; cursor: pointer; font-size: 1rem;">
                    CONFIRM & CONTINUE
                </button>
            </form>
        </div>
    </div>
</body>
</html>
