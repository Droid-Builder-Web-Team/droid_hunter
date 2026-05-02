<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $droid['name'] }} - Droid Hunter</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="container">
        <a href="{{ route('registry.index') }}" style="color: var(--primary); text-decoration: none; margin-bottom: 2rem; display: inline-block;">&larr; Back to Registry</a>
        
        <div class="droid-card" style="padding: 2rem; display: flex; flex-direction: column; align-items: center; gap: 2rem;">
            <img src="{{ rtrim(config('services.core_portal.url'), '/') }}/storage/droids/{{ $droid['id'] }}/image.jpg" 
                 onerror="this.src='{{ $droid['placeholder'] }}'; this.classList.add('placeholder-silhouette');"
                 alt="{{ $droid['name'] }}" style="width: 300px; height: 300px; object-fit: contain;">
            
            <div style="text-align: center;">
                <h1 style="margin: 0; font-size: 2.5rem; color: var(--primary);">{{ $droid['name'] }}</h1>
                <p style="color: var(--text-secondary);">
                    @php
                        $encounters = \App\Models\DroidScan::where('user_id', Auth::id())
                            ->where('droid_id', $droid['id'])
                            ->count();
                    @endphp
                    Spotted {{ $encounters }}x (Last seen: {{ $scan->created_at->format('M j, Y') }})
                </p>
            </div>

            <div style="background: var(--accent); padding: 1.5rem; border-radius: 1rem; width: 100%;">
                <h3 style="margin-top: 0;">Description</h3>
                <p>{{ $droid['description'] ?? 'No description available for this droid.' }}</p>
                
                <div style="display: flex; gap: 2rem; margin-top: 1.5rem;">
                    <div>
                        <div style="font-size: 0.8rem; color: var(--text-secondary);">Club</div>
                        <div style="font-weight: 600;">{{ $droid['club']['name'] ?? 'Unknown' }}</div>
                    </div>
                    <div>
                        <div style="font-size: 0.8rem; color: var(--text-secondary);">Rarity</div>
                        <div style="font-weight: 600; color: var(--secondary);">{{ $droid['rarity'] ?? 'Common' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
