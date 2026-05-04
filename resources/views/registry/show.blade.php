<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $droid['name'] }} - Droid Hunter</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
</head>
<body>
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <a href="{{ route('registry.index') }}" class="btn-galactic text-decoration-none">&larr; NAV_BACK</a>
            <button onclick="shareDroid()" class="btn-galactic" style="background: var(--secondary); color: var(--bg-color); border-color: var(--secondary);">SHARE_INTEL</button>
        </div>
        
        <div style="background: var(--panel-bg); border: 1px solid var(--panel-border); padding: 3rem; display: flex; flex-direction: column; align-items: center; gap: 2.5rem; position: relative; box-shadow: inset 0 0 20px var(--primary-glow);">
            <!-- Datapad Frame -->
            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 1px solid var(--primary); opacity: 0.2; pointer-events: none;"></div>
            <div style="position: relative;">
                <!-- Scanning Brackets -->
                <div style="position: absolute; top: -10px; left: -10px; width: 40px; height: 40px; border-top: 3px solid var(--primary); border-left: 3px solid var(--primary);"></div>
                <div style="position: absolute; top: -10px; right: -10px; width: 40px; height: 40px; border-top: 3px solid var(--primary); border-right: 3px solid var(--primary);"></div>
                <div style="position: absolute; bottom: -10px; left: -10px; width: 40px; height: 40px; border-bottom: 3px solid var(--primary); border-left: 3px solid var(--primary);"></div>
                <div style="position: absolute; bottom: -10px; right: -10px; width: 40px; height: 40px; border-bottom: 3px solid var(--primary); border-right: 3px solid var(--primary);"></div>
                
                <img src="{{ route('proxy.image', ['url' => rtrim(config('services.core_portal.url'), '/') . '/droid_image/' . $droid['id'] . '/photo_front/480']) }}" 
                     onerror="this.src='{{ $droid['placeholder'] }}'; this.classList.add('placeholder-silhouette');"
                     alt="{{ $droid['name'] }}" style="width: 320px; height: 320px; object-fit: contain; position: relative; z-index: 5;">
            </div>
            
            <div style="text-align: center;">
                <h1 style="margin: 0; font-size: 3.5rem; color: var(--primary); letter-spacing: 2px; text-shadow: 0 0 20px var(--primary-glow);">{{ $droid['name'] }}</h1>
                <p style="color: var(--text-secondary); text-transform: uppercase; letter-spacing: 2px; margin-top: 0.5rem; display: flex; align-items: center; justify-content: center; gap: 1rem;">
                    <span>SCAN_FREQ: {{ $encounters }}x</span>
                    <div class="rank-badge rank-{{ strtolower($droid['rank']) }}" style="position: static;"
                         title="{{ $droid['rank'] }} RANK: You have recorded {{ $encounters }} encounters with this unit.">
                        <div class="crystal-icon" style="margin: 0 auto;"></div>
                        <span class="rank-text" style="font-size: 0.7rem;">{{ $droid['rank'] }} RANK</span>
                    </div>
                    <span>LAST_LOCAL: {{ $scan->created_at->format('Y.m.d') }}</span>
                </p>
            </div>

            <div style="width: 100%;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1px; margin-bottom: 2rem; background: var(--panel-border); border: 1px solid var(--panel-border);">
                    <div style="background: var(--panel-bg); padding: 1.5rem; text-align: center;">
                        <div style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 1px;">Personal Spots</div>
                        <div style="font-size: 2rem; font-weight: 700; color: var(--primary);">{{ $encounters }}x</div>
                    </div>
                    <div style="background: var(--panel-bg); padding: 1.5rem; text-align: center;">
                        <div style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 1px;">Community Intel</div>
                        <div style="font-size: 2rem; font-weight: 700; color: var(--secondary);">{{ $globalSpottedCount }}x</div>
                        <div style="font-size: 0.65rem; color: var(--text-secondary); margin-top: 0.5rem; text-transform: uppercase;">Total Global Encounters</div>
                    </div>
                </div>

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

    <!-- Hidden Share Template (Rendered off-screen but visible to the engine) -->
    <div id="share-card-wrapper" style="position: absolute; left: -2000px; top: 0;">
        <div id="share-card" style="width: 400px; padding: 40px; background: #05070a; color: #e0faff; font-family: 'Rajdhani', sans-serif; position: relative; border: 2px solid #00f2ff; box-shadow: inset 0 0 50px rgba(0, 242, 255, 0.1);">
            <div style="text-align: center; color: #00f2ff; letter-spacing: 4px; font-weight: 700; font-size: 0.7rem; margin-bottom: 25px; opacity: 0.6;">DROID_HUNTER // SECURE_INTEL_LOG</div>
            
            <div style="width: 100%; height: 320px; background: rgba(0, 242, 255, 0.05); border: 1px solid rgba(0, 242, 255, 0.1); display: flex; align-items: center; justify-content: center; margin-bottom: 30px; position: relative;">
                <img id="capture-image" src="{{ route('proxy.image', ['url' => rtrim(config('services.core_portal.url'), '/') . '/droid_image/' . $droid['id'] . '/photo_front/480']) }}" 
                     style="max-width: 90%; max-height: 90%; object-fit: contain;">
                
                <div style="position: absolute; top: 15px; left: 15px; display: flex; flex-direction: column; align-items: center; gap: 4px;">
                    <!-- SVG Crystal (Better compatibility) -->
                    <svg width="20" height="30" viewBox="0 0 24 36" fill="{{ $droid['rank'] == 'GOLD' ? '#ffd700' : ($droid['rank'] == 'SILVER' ? '#00f2ff' : '#ff8800') }}">
                        <path d="M12 0L24 9V27L12 36L0 27V9L12 0Z" />
                        <path d="M12 4L20 10V26L12 32L4 26V10L12 4Z" fill-opacity="0.3" />
                    </svg>
                    <div style="font-size: 0.6rem; font-weight: 800; color: #fff;">{{ $droid['rank'] }}</div>
                </div>
            </div>

            <h1 style="margin: 0; color: #00f2ff; text-align: center; font-size: 2.8rem; letter-spacing: 2px; text-transform: uppercase; text-shadow: 0 0 15px rgba(0, 242, 255, 0.5);">{{ $droid['name'] }}</h1>
            
            <div style="margin-top: 30px; border-top: 1px solid rgba(0, 242, 255, 0.1); padding-top: 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                 <div>
                    <div style="font-size: 0.65rem; color: #708ea0; text-transform: uppercase; letter-spacing: 1px;">Encounter Site</div>
                    <div style="font-size: 1.1rem; color: #ffaa00; font-weight: 600; text-transform: uppercase;">{{ $currentEvent }}</div>
                 </div>
                 <div style="text-align: right;">
                    <div style="font-size: 0.65rem; color: #708ea0; text-transform: uppercase; letter-spacing: 1px;">Source Sector</div>
                    <div style="font-size: 1.1rem; color: #fff; font-weight: 600; text-transform: uppercase;">{{ $droid['location']['county'] ?? 'UK' }}</div>
                 </div>
            </div>

            <div style="margin-top: 40px; text-align: center; border: 1px dashed rgba(0, 242, 255, 0.2); padding: 10px; opacity: 0.5;">
                <div style="font-size: 0.6rem; color: #708ea0; text-transform: uppercase; letter-spacing: 3px;">Join the hunt at hunters.droidbuilders.uk</div>
            </div>
        </div>
    </div>

    <script>
        function shareDroid() {
            const btn = event.currentTarget;
            const originalText = btn.innerText;
            btn.innerText = 'GENERATING...';
            btn.disabled = true;

            // Give the browser a moment to ensure the hidden template is rendered
            setTimeout(() => {
                const captureElement = document.querySelector("#share-card");
                
                html2canvas(captureElement, {
                    backgroundColor: "#05070a",
                    scale: 2,
                    useCORS: true,
                    allowTaint: true,
                    logging: true
                }).then(canvas => {
                    canvas.toBlob(blob => {
                        const file = new File([blob], 'droid-capture.png', { type: 'image/png' });
                        
                        if (navigator.share && navigator.canShare && navigator.canShare({ files: [file] })) {
                            navigator.share({
                                files: [file],
                                title: '{{ $droid['name'] }} Spotted!',
                                text: 'I just spotted {{ $droid['name'] }} using the Droid Hunter app!'
                            }).then(() => {
                                btn.innerText = originalText;
                                btn.disabled = false;
                            }).catch(err => {
                                downloadFallback(canvas, btn, originalText);
                            });
                        } else {
                            downloadFallback(canvas, btn, originalText);
                        }
                    });
                });
            }, 100);
        }

        function downloadFallback(canvas, btn, originalText) {
            const link = document.createElement('a');
            link.download = '{{ $droid['name'] }}-capture.png';
            link.href = canvas.toDataURL();
            link.click();
            btn.innerText = originalText;
            btn.disabled = false;
        }
    </script>
</body>
</html>
