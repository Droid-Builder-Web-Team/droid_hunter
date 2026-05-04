@extends('layouts.app')

@section('title', 'Encounter History - Droid Hunter')

@section('content')
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <a href="{{ route('registry.index') }}" class="btn-galactic text-decoration-none">&larr; NAV_BACK</a>
            @auth
                <div style="color: var(--text-secondary); font-size: 0.9rem; letter-spacing: 1px;">
                    USER: <span style="color: var(--primary);">{{ Auth::user()->name }}</span>
                </div>
            @endauth
        </div>

        <header class="header">
            <h1>Encounter History</h1>
            <p>Chronological Log // Transmission Received</p>
        </header>

        <div class="history-list">
            @forelse($scans as $scan)
                <div class="history-item">
                    <div class="scan-time">
                        <div style="font-size: 0.8rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 1px;">Encounter Date</div>
                        <div style="font-weight: 700; color: var(--primary); font-size: 1.3rem; letter-spacing: 1px;">{{ $scan->created_at->format('Y.m.d') }}</div>
                    </div>

                    @if($scan->droid)
                        <img src="{{ rtrim(config('services.core_portal.url'), '/') }}/droid_image/{{ $scan->droid_id }}/photo_front/240" 
                             onerror="this.src='{{ $scan->droid['placeholder'] }}'; this.classList.add('placeholder-silhouette');"
                             alt="{{ $scan->droid['name'] }}" 
                             style="width: 60px; height: 60px; object-fit: contain;">
                        
                        <div style="flex-grow: 1;">
                            <div style="font-size: 0.8rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 1px;">Droid Identification</div>
                            <h3 style="margin: 0; color: var(--text-primary); letter-spacing: 1px;">{{ $scan->droid['name'] }}</h3>
                            <div style="font-size: 0.85rem; color: var(--text-secondary);">Sector: {{ $scan->droid['club']['name'] ?? 'Unknown' }}</div>
                        </div>

                        <a href="{{ route('registry.show', $scan->droid_id) }}" class="btn-galactic text-decoration-none" style="font-size: 0.75rem; padding: 0.5rem 1rem;">VIEW_DETAILS</a>
                    @else
                        <div style="flex-grow: 1; color: var(--text-secondary); font-style: italic;">
                            DATABASE_CORRUPTION: Droid data for ID {{ $scan->droid_id }} unavailable.
                        </div>
                    @endif
                </div>
            @empty
                <div style="text-align: center; padding: 4rem; background: var(--panel-bg); border: 1px solid var(--panel-border); border-radius: 4px;">
                    <p style="color: var(--text-secondary); letter-spacing: 1px;">NO ENCOUNTERS RECORDED IN THIS SECTOR.</p>
                    <a href="{{ route('registry.index') }}" class="btn-galactic text-decoration-none" style="margin-top: 1.5rem; display: inline-block;">GO HUNTING</a>
                </div>
            @endforelse
        </div>

        <footer style="margin-top: 4rem; text-align: center; border-top: 1px solid var(--panel-border); padding-top: 2rem; padding-bottom: 4rem;">
            <p style="font-size: 0.75rem; color: var(--text-secondary); opacity: 0.6; text-transform: uppercase; letter-spacing: 1px;">
                DROID_HUNTER_OS &bull; LOG_VERSION: 2.1.0-STABLE
            </p>
        </footer>
    </div>
@endsection
