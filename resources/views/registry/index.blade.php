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
            <div style="color: var(--text-secondary);">Logged in as: <span style="color: var(--primary); font-weight: 600;">{{ Auth::user()->name }}</span></div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" style="background: none; border: 1px solid var(--accent); color: var(--text-secondary); padding: 0.4rem 0.8rem; border-radius: 0.4rem; cursor: pointer; font-size: 0.8rem;">Logout</button>
            </form>
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
                        
                        <img src="{{ rtrim(config('services.core_portal.url'), '/') }}/storage/droids/{{ $droid['id'] }}/image.jpg" 
                             onerror="this.src='{{ $droid['placeholder'] }}'; this.classList.add('placeholder');"
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
    </div>
</body>
</html>
