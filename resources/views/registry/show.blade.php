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
                    Spotted {{ $encounters }}x (Last seen: {{ $scan->created_at->format('M j, Y') }})
                </p>
            </div>

            <div style="background: var(--accent); padding: 1.5rem; border-radius: 1rem; width: 100%;">
                <div class="specs-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 2rem; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 1.5rem;">
                    <div>
                        <div style="font-size: 0.7rem; color: var(--text-secondary); text-transform: uppercase;">Weight</div>
                        <div style="font-weight: 600;">{{ $droid['specs']['weight'] ?? '?' }} kg</div>
                    </div>
                    <div>
                        <div style="font-size: 0.7rem; color: var(--text-secondary); text-transform: uppercase;">Speed</div>
                        <div style="font-weight: 600;">{{ $droid['specs']['top_speed'] ?? '?' }} m/s</div>
                    </div>
                    <div>
                        <div style="font-size: 0.7rem; color: var(--text-secondary); text-transform: uppercase;">Material</div>
                        <div style="font-weight: 600;">{{ $droid['specs']['material'] ?? 'Unknown' }}</div>
                    </div>
                </div>

                <div class="lore-section" style="margin-bottom: 2rem;">
                    <h3 style="margin-top: 0; color: var(--primary); font-size: 1.1rem;">Backstory</h3>
                    <p style="line-height: 1.6; font-size: 0.95rem;">{{ $droid['back_story'] ?: 'This droid has a mysterious past that is yet to be told...' }}</p>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: flex-end;">
                    <div>
                        <div style="font-size: 0.7rem; color: var(--text-secondary); text-transform: uppercase;">Origin</div>
                        <div style="font-weight: 600;">{{ $droid['location']['county'] }}, {{ $droid['location']['country'] }}</div>
                        <div style="font-size: 0.8rem; color: var(--primary);">{{ $droid['club']['name'] }}</div>
                    </div>
                    
                    <details style="cursor: pointer;">
                        <summary style="font-size: 0.8rem; color: var(--text-secondary); list-style: none;">[ View Builder Notes ]</summary>
                        <div style="margin-top: 1rem; font-size: 0.85rem; color: var(--text-secondary); background: rgba(0,0,0,0.2); padding: 1rem; border-radius: 0.5rem; border-left: 3px solid var(--primary);">
                            {!! nl2br(e($droid['description'])) !!}
                        </div>
                    </details>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
