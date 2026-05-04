<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', config('app.name', 'Droid Hunter'))</title>
    
    <!-- PWA Manifest & Meta -->
    <meta name="theme-color" content="#05070a">
    <link rel="manifest" href="/manifest.webmanifest">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body>
    @yield('content')

    <!-- PWA Install Banner -->
    <div id="pwa-install-banner" style="display: none; position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%); width: 90%; max-width: 400px; background: var(--panel-bg); border: 1px solid var(--primary); padding: 1rem; z-index: 9999; box-shadow: 0 0 20px var(--primary-glow); clip-path: polygon(5% 0, 100% 0, 100% 80%, 95% 100%, 0 100%, 0 20%);">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="width: 40px; height: 40px; background: var(--primary); display: flex; align-items: center; justify-content: center; clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--bg-color)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
            </div>
            <div style="flex: 1;">
                <h4 style="margin: 0; color: var(--primary); font-size: 1rem; text-transform: uppercase;">Install Droid Hunter</h4>
                <p style="margin: 0; font-size: 0.8rem; color: var(--text-secondary);">Add to home screen for the full experience</p>
            </div>
            <button onclick="window.dispatchEvent(new CustomEvent('pwa-install-triggered'))" class="btn-galactic" style="font-size: 0.7rem; padding: 0.4rem 0.8rem;">Install</button>
            <button onclick="document.getElementById('pwa-install-banner').style.display = 'none'" style="background: none; border: none; color: var(--text-secondary); cursor: pointer; font-size: 1.2rem;">&times;</button>
        </div>
    </div>

    <script>
        window.addEventListener('pwa-installable', () => {
            const banner = document.getElementById('pwa-install-banner');
            if (banner) {
                banner.style.display = 'block';
                // Slide in animation if needed
            }
        });

        window.addEventListener('pwa-installed', () => {
            const banner = document.getElementById('pwa-install-banner');
            if (banner) {
                banner.style.display = 'none';
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
