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
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem;">
            @auth
                <div style="color: var(--text-secondary); font-size: 1rem; letter-spacing: 1px; font-weight: 500;">
                    USER_IDENT: <span style="color: var(--primary);">{{ Auth::user()->name }}</span>
                </div>
                <div style="display: flex; gap: 1rem;">
                    <a href="{{ route('registry.history') }}" class="btn-galactic text-decoration-none" style="font-size: 0.8rem; background: rgba(0, 255, 255, 0.1);">HISTORY</a>
                    <a href="{{ route('profile.show') }}" class="btn-galactic text-decoration-none" style="font-size: 0.8rem;">PROFILE</a>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-galactic" style="font-size: 0.8rem;">LOGOUT</button>
                    </form>
                </div>
            @else
                <div style="color: var(--text-secondary); font-size: 1rem; letter-spacing: 1px; font-weight: 500;">GUEST_SESSION</div>
                <div style="display: flex; gap: 1rem; align-items: center;">
                    <a href="{{ route('registry.history') }}" class="btn-galactic text-decoration-none" style="font-size: 0.8rem; background: rgba(0, 255, 255, 0.1);">HISTORY</a>
                    <a href="{{ route('login') }}" class="btn-galactic text-decoration-none" style="background: var(--primary); color: var(--bg-color);">SIGN IN TO SYNC</a>
                </div>
            @endauth
        </div>
        <header class="header">
            <h1>Droid Registry</h1>
            <p>Scanning Sector: Local Registry // Database Online</p>
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

        <footer style="margin-top: 4rem; text-align: center; border-top: 1px solid var(--panel-border); padding-top: 2rem; padding-bottom: 4rem;">
            <div style="display: flex; gap: 2.5rem; justify-content: center; font-size: 0.85rem; letter-spacing: 1px;">
                <a href="{{ route('privacy') }}" style="color: var(--text-secondary); text-decoration: none; text-transform: uppercase;">// PRIVACY_PROTOCOL</a>
                <a href="{{ route('gdpr') }}" style="color: var(--text-secondary); text-decoration: none; text-transform: uppercase;">// DATA_PURGE</a>
            </div>
            <p style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 1.5rem; opacity: 0.6; text-transform: uppercase; letter-spacing: 1px;">
                DROID_HUNTER_OS &bull; SECTOR: UK_R2_BUILDERS<br>
                <span style="color: var(--primary); opacity: 0.8;">ENCRYPTION_ACTIVE // SECURE_LINK_ESTABLISHED</span>
            </p>
        </footer>
    </div>
</body>
</html>
