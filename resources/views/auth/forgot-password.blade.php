<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Droid Hunter</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="container" style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 80vh;">
        <header class="header">
            <h1>Reset Access</h1>
            <p>Recover your hunter credentials.</p>
        </header>

        <div class="droid-card" style="padding: 2.5rem; width: 100%; max-width: 400px;">
            @if (session('status'))
                <div style="background: rgba(0, 255, 170, 0.1); border: 1px solid var(--success); color: var(--success); padding: 1rem; margin-bottom: 1.5rem; text-align: center; border-radius: 0.5rem; font-size: 0.9rem;">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div style="background: rgba(255, 60, 60, 0.1); border: 1px solid var(--danger); color: var(--danger); padding: 1rem; margin-bottom: 1.5rem; border-radius: 0.5rem; font-size: 0.9rem;">
                    <ul style="margin: 0; padding-left: 1.2rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST">
                @csrf
                <div style="margin-bottom: 2rem;">
                    <label for="email" style="display: block; margin-bottom: 0.5rem; color: var(--text-secondary); text-align: left;">Email Address</label>
                    <input type="email" name="email" id="email" required placeholder="hunter@example.com" value="{{ old('email') }}" autofocus
                           style="width: 100%; padding: 0.8rem; border-radius: 0.5rem; border: 1px solid var(--accent); background: var(--bg-color); color: white; font-size: 1rem;">
                </div>

                <button type="submit" style="width: 100%; padding: 1rem; border-radius: 0.5rem; border: none; background: var(--primary); color: var(--bg-color); font-weight: 800; cursor: pointer; font-size: 1rem; margin-bottom: 1rem;">
                    SEND RESET LINK
                </button>
            </form>

            <div style="text-align: center; margin-top: 1rem;">
                <a href="{{ route('login') }}" style="color: var(--text-secondary); text-decoration: none; font-size: 0.9rem;">
                    ← Back to Login
                </a>
            </div>
        </div>
    </div>
</body>
</html>
