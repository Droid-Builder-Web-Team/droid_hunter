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
        <a href="{{ route('registry.index') }}" class="btn-galactic text-decoration-none" style="margin-bottom: 2rem;">&larr; NAV_BACK</a>
        
        <div style="background: var(--panel-bg); border: 1px solid var(--panel-border); padding: 3rem; display: flex; flex-direction: column; align-items: center; gap: 2.5rem; position: relative; box-shadow: inset 0 0 20px var(--primary-glow);">
            <!-- Datapad Frame -->
            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 1px solid var(--primary); opacity: 0.2; pointer-events: none;"></div>
            <div style="position: relative;">
                <!-- Scanning Brackets -->
                <div style="position: absolute; top: -10px; left: -10px; width: 40px; height: 40px; border-top: 3px solid var(--primary); border-left: 3px solid var(--primary);"></div>
                <div style="position: absolute; top: -10px; right: -10px; width: 40px; height: 40px; border-top: 3px solid var(--primary); border-right: 3px solid var(--primary);"></div>
                <div style="position: absolute; bottom: -10px; left: -10px; width: 40px; height: 40px; border-bottom: 3px solid var(--primary); border-left: 3px solid var(--primary);"></div>
                <div style="position: absolute; bottom: -10px; right: -10px; width: 40px; height: 40px; border-bottom: 3px solid var(--primary); border-right: 3px solid var(--primary);"></div>
                
                <img src="{{ rtrim(config('services.core_portal.url'), '/') }}/droid_image/{{ $droid['id'] }}/photo_front/480" 
                     onerror="this.src='{{ $droid['placeholder'] }}'; this.classList.add('placeholder-silhouette');"
                     alt="{{ $droid['name'] }}" style="width: 320px; height: 320px; object-fit: contain; position: relative; z-index: 5;">
            </div>
            
            <div style="text-align: center;">
                <h1 style="margin: 0; font-size: 3.5rem; color: var(--primary); letter-spacing: 2px; text-shadow: 0 0 20px var(--primary-glow);">{{ $droid['name'] }}</h1>
                <p style="color: var(--text-secondary); text-transform: uppercase; letter-spacing: 2px; margin-top: 0.5rem;">
                    SCAN_FREQ: {{ $encounters }}x // LAST_LOCAL: {{ $scan->created_at->format('Y.m.d') }}
                </p>
            </div>

            <div style="width: 100%;">
                <div class="specs-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1px; padding: 1px; margin-bottom: 2.5rem; text-align: center;">
                    <div style="background: var(--bg-color); padding: 1.5rem;">
                        <div style="font-size: 0.8rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">Mass</div>
                        <div style="font-weight: 700; font-size: 1.2rem; color: var(--primary);">{{ $droid['specs']['weight'] ?? '?' }} KG</div>
                    </div>
                    <div style="background: var(--bg-color); padding: 1.5rem;">
                        <div style="font-size: 0.8rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">Velocity</div>
                        <div style="font-weight: 700; font-size: 1.2rem; color: var(--primary);">{{ $droid['specs']['top_speed'] ?? '?' }} M/S</div>
                    </div>
                    <div style="background: var(--bg-color); padding: 1.5rem;">
                        <div style="font-size: 0.8rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">Chassis</div>
                        <div style="font-weight: 700; font-size: 1.2rem; color: var(--primary);">{{ $droid['specs']['material'] ?? 'UNKNOWN' }}</div>
                    </div>
                </div>

                <div style="margin-bottom: 3rem; background: rgba(0,0,0,0.4); padding: 2rem; border-left: 4px solid var(--primary); position: relative;">
                    <h3 style="margin-top: 0; color: var(--primary); font-size: 1.2rem; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 1rem;">// DROID_BACKSTORY</h3>
                    <p style="line-height: 1.8; font-size: 1.1rem; color: var(--text-primary);">{{ $droid['back_story'] ?: 'DATABASE_ERROR: Information restricted or unavailable in this sector.' }}</p>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: flex-end; padding: 0 1rem;">
                    <div>
                        <div style="font-size: 0.8rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 1px;">Planet_Origin</div>
                        <div style="font-weight: 700; font-size: 1.1rem; color: var(--secondary);">{{ $droid['location']['county'] }}, {{ $droid['location']['country'] }}</div>
                        <div style="font-size: 0.9rem; color: var(--text-secondary); margin-top: 0.2rem;">Registry: {{ $droid['club']['name'] }}</div>
                    </div>
                    
                    <details style="cursor: pointer; width: 50%;">
                        <summary style="font-size: 0.9rem; color: var(--text-secondary); list-style: none; text-align: right; text-transform: uppercase; letter-spacing: 1px;">[ DECRYPT_BUILDER_NOTES ]</summary>
                        <div style="margin-top: 1rem; font-size: 1rem; color: var(--text-secondary); background: var(--bg-color); padding: 1.5rem; border: 1px solid var(--panel-border); border-radius: 2px;">
                            {!! nl2br(e($droid['description'])) !!}
                        </div>
                    </details>
                </div>

                <div style="margin-top: 3rem; border-top: 1px solid var(--panel-border); padding-top: 2rem;">
                    <h3 style="color: var(--primary); font-size: 1.2rem; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 1.5rem;">// ENCOUNTER_LOGS</h3>
                    <div class="encounter-logs-grid">
                        @foreach($scanHistory as $h)
                            <div class="encounter-log-card">
                                <div style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase;">Observation</div>
                                <div style="font-weight: 700; color: var(--primary);">{{ $h->created_at->format('Y.m.d') }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
