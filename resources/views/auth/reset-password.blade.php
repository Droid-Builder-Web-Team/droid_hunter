<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Droid Hunter</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="container" style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 80vh;">
        <header class="header">
            <h1>New Password</h1>
            <p>Establish new hunter credentials.</p>
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

            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div style="margin-bottom: 1.5rem;">
                    <label for="email" style="display: block; margin-bottom: 0.5rem; color: var(--text-secondary); text-align: left;">Email Address</label>
                    <input type="email" name="email" id="email" required value="{{ old('email', $request->email) }}" autofocus
                           style="width: 100%; padding: 0.8rem; border-radius: 0.5rem; border: 1px solid var(--accent); background: var(--bg-color); color: white; font-size: 1rem;">
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label for="password" style="display: block; margin-bottom: 0.5rem; color: var(--text-secondary); text-align: left;">New Password</label>
                    <input type="password" name="password" id="password" required 
                           style="width: 100%; padding: 0.8rem; border-radius: 0.5rem; border: 1px solid var(--accent); background: var(--bg-color); color: white; font-size: 1rem;">
                </div>

                <div style="margin-bottom: 2rem;">
                    <label for="password_confirmation" style="display: block; margin-bottom: 0.5rem; color: var(--text-secondary); text-align: left;">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required 
                           style="width: 100%; padding: 0.8rem; border-radius: 0.5rem; border: 1px solid var(--accent); background: var(--bg-color); color: white; font-size: 1rem;">
                </div>

                <button type="submit" style="width: 100%; padding: 1rem; border-radius: 0.5rem; border: none; background: var(--primary); color: var(--bg-color); font-weight: 800; cursor: pointer; font-size: 1rem;">
                    RESET PASSWORD
                </button>
            </form>
        </div>
    </div>
</body>
</html>
