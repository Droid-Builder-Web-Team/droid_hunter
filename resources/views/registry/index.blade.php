@extends('layouts.app')

@section('title', 'Droid Registry - Droid Hunter')

@section('content')
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem;">
            @auth
                <div style="color: var(--text-secondary); font-size: 1rem; letter-spacing: 1px; font-weight: 500;">
                    USER_IDENT: <span style="color: var(--primary);">{{ Auth::user()->name }}</span>
                </div>
                <div style="display: flex; gap: 1rem;">
                    @if(Auth::user()->is_admin)
                        <a href="{{ route('admin.stats') }}" class="btn-galactic text-decoration-none" style="font-size: 0.8rem; background: rgba(0, 255, 170, 0.1); border-color: var(--success); color: var(--success);">COMMAND CENTER</a>
                    @endif
                    <a href="{{ route('registry.awards') }}" class="btn-galactic text-decoration-none" style="font-size: 0.8rem; background: rgba(255, 170, 0, 0.1); border-color: var(--secondary); color: var(--secondary);">AWARDS</a>
                    <a href="{{ route('registry.history') }}" class="btn-galactic text-decoration-none" style="font-size: 0.8rem; background: rgba(0, 255, 255, 0.1);">HISTORY</a>
                    <a href="{{ route('profile.show') }}" class="btn-galactic text-decoration-none" style="font-size: 0.8rem;">PROFILE</a>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-galactic" style="font-size: 0.8rem; border-color: var(--danger); color: var(--danger);">DISCONNECT</button>
                    </form>
                </div>
            @else
                <div style="color: var(--text-secondary); font-size: 1rem; letter-spacing: 1px; font-weight: 500;">
                    GUEST_MODE: <span style="color: var(--secondary);">ANONYMOUS_ENVOY</span>
                </div>
                <div style="display: flex; gap: 1rem;">
                    <a href="{{ route('registry.history') }}" class="btn-galactic text-decoration-none" style="font-size: 0.8rem; background: rgba(0, 255, 255, 0.1);">HISTORY</a>
                    <a href="{{ route('login') }}" class="btn-galactic text-decoration-none" style="font-size: 0.8rem;">ESTABLISH_LINK</a>
                </div>
            @endauth
        </div>

        <div class="header">
            <h1>Droid Registry</h1>
            <p>Scanning local frequency for active droid units...</p>
        </div>

        @if($nearbyIntel)
            <div id="intel-ticker" style="margin-bottom: 2.5rem; background: rgba(0, 242, 255, 0.05); border: 1px solid var(--panel-border); padding: 0.8rem 1.5rem; display: flex; align-items: center; gap: 1rem; border-left: 3px solid var(--primary);">
                <div style="color: var(--primary); font-weight: 700; font-size: 0.75rem; letter-spacing: 2px; text-transform: uppercase; white-space: nowrap;">
                    <span style="display: inline-block; width: 8px; height: 8px; background: var(--primary); border-radius: 50%; margin-right: 0.5rem; animation: pulse 2s infinite;"></span>
                    Nearby Intelligence:
                </div>
                <div id="intel-content" style="font-size: 0.9rem; color: var(--text-primary); letter-spacing: 0.5px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                    <span style="color: var(--secondary);">{{ $nearbyIntel['droid_name'] }}</span> was spotted at <span style="color: var(--primary);">{{ $nearbyIntel['event_name'] }}</span> <span style="color: var(--text-secondary);">({{ $nearbyIntel['time'] }})</span>
                </div>
            </div>
        @endif

        <div class="grid">
            @foreach($allDroids as $droid)
                @php
                    $isFound = $droid['found'] ?? false;
                    $rank = $droid['rank'] ?? null;
                @endphp
                
                <a href="{{ route('registry.show', $droid['id']) }}" class="droid-card {{ $isFound ? 'found' : 'locked' }}">
                    @if($isFound)
                        <div class="found-badge">ACQUIRED</div>
                        
                        @if($rank && $rank !== 'LOCKED')
                            <div class="rank-badge rank-{{ strtolower($rank) }}" title="Rank: {{ ucfirst(strtolower($rank)) }}">
                                <div class="crystal-icon"></div>
                                <div class="rank-text">{{ $rank }}</div>
                            </div>
                        @endif
                    @endif
                    
                    @if($isFound)
                        <img src="{{ rtrim(config('services.core_portal.url'), '/') }}/droid_image/{{ $droid['id'] }}/photo_front/240" 
                             onerror="this.src='{{ $droid['placeholder'] }}'; this.classList.add('placeholder-silhouette');"
                             alt="{{ $droid['name'] }}" class="droid-image">
                    @else
                        <img src="{{ $droid['placeholder'] }}" alt="{{ $droid['name'] }}" class="droid-image placeholder-silhouette">
                    @endif
                    
                    <div class="droid-name">{{ $droid['name'] }}</div>
                    
                    <div class="rarity-tag {{ strtolower($droid['rarity'] ?? 'common') }}">
                        {{ $droid['rarity'] ?? 'COMMON' }}
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endsection
