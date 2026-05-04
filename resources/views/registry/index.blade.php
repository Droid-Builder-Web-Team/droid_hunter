<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Droid Registry - Droid Hunter</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="manifest" href="/manifest.webmanifest">
</head>
<body>
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            @auth
                <div style="color: var(--text-secondary); font-size: 0.9rem;">Logged in as: <span style="color: var(--primary); font-weight: 600;">{{ Auth::user()->name }}</span></div>
                <div style="display: flex; gap: 0.5rem;">
                    <a href="{{ route('profile.show') }}" style="color: var(--text-secondary); text-decoration: none; font-size: 0.8rem; border: 1px solid var(--accent); padding: 0.4rem 0.8rem; border-radius: 0.4rem;">Profile & Security</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" style="background: none; border: 1px solid var(--accent); color: var(--text-secondary); padding: 0.4rem 0.8rem; border-radius: 0.4rem; cursor: pointer; font-size: 0.8rem;">Logout</button>
                    </form>
                </div>
            @else
                <div style="color: var(--text-secondary); font-size: 0.9rem;">Guest Mode <span style="font-size: 0.7rem; opacity: 0.7;">(Device tracking active)</span></div>
                <a href="{{ route('login') }}" style="background: var(--primary); color: var(--bg-color); padding: 0.5rem 1rem; border-radius: 0.5rem; text-decoration: none; font-weight: 800; font-size: 0.75rem; letter-spacing: 0.5px;">SIGN IN TO SYNC</a>
            @endauth
        </div>
        <header class="header">
            <h1>Droid Registry</h1>
            <p>Your collection of droids spotted in the wild.</p>
        </header>

        <div class="grid">
            @forelse($allDroids as $droid)
                @if($droid['found'])
                    <a href="{{ route('registry.show', $droid['id']) }}" class="droid-card found text-decoration-none">
                        <span class="found-badge">Spotted {{ $droid['encounters'] }}x</span>
                        
                        <img src="{{ rtrim(config('services.core_portal.url'), '/') }}/droid_image/{{ $droid['id'] }}/photo_front/240" 
                             onerror="this.src='{{ $droid['placeholder'] }}'; this.classList.add('placeholder-silhouette');"
                             alt="{{ $droid['name'] }}" 
                             class="droid-image">
                        
                        <div class="droid-name">{{ $droid['name'] }}</div>
                        
                        @if(isset($droid['rarity']))
                            <div class="rarity-tag {{ strtolower($droid['rarity']) }}">{{ $droid['rarity'] }}</div>
                        @endif
                    </a>
                @else
                    <div class="droid-card locked">
                        <img src="{{ $droid['placeholder'] ?? '/images/placeholders/astromech.png' }}" 
                             alt="Locked" 
                             class="droid-image placeholder">
                        <div class="droid-name">{{ $droid['name'] }}</div>
                        @if(isset($droid['rarity']))
                            <div class="rarity-tag {{ strtolower($droid['rarity']) }}">{{ $droid['rarity'] }}</div>
                        @endif
                    </div>
                @endif
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 4rem; background: var(--card-bg); border-radius: 1rem; border: 1px dashed var(--accent);">
                    <p style="color: var(--text-secondary);">No public droids found in the registry.</p>
                    <p style="font-size: 0.8rem; color: var(--text-secondary);">Make sure droids are marked as "Public" in the Core Portal.</p>
                </div>
            @endforelse
        </div>

        <footer style="margin-top: 4rem; text-align: center; border-top: 1px solid var(--accent); padding-top: 2rem; padding-bottom: 2rem;">
            <div style="display: flex; gap: 2rem; justify-content: center; font-size: 0.8rem;">
                <a href="{{ route('privacy') }}" style="color: var(--text-secondary); text-decoration: none;">Privacy Policy</a>
                <a href="{{ route('gdpr') }}" style="color: var(--text-secondary); text-decoration: none;">GDPR & Data</a>
            </div>
            <p style="font-size: 0.7rem; color: var(--text-secondary); margin-top: 1rem; opacity: 0.5;">
                Droid Hunter &bull; Built for Droid Builders UK<br>
                <span style="opacity: 0.3;">COOKIE: {{ request()->cookie('visitor_id') ?? 'MISSING' }} | SESS: {{ session('visitor_id') ?? 'MISSING' }} | REQ: {{ request()->get('visitor_id') ?? 'NONE' }}</span>
            </p>
        </footer>
    </div>
</body>
</html>
