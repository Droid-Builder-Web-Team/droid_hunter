@extends('layouts.app')

@section('title', 'Admin Dashboard - Droid Hunter')

@section('content')
<div class="container">
    <div class="header">
        <h1>Command Center</h1>
        <p>System Telemetry & Activity Feed</p>
    </div>

    <!-- Navigation -->
    <div style="margin-bottom: 2rem; display: flex; gap: 1rem; flex-wrap: wrap;">
        <a href="{{ route('registry.index') }}" class="btn-galactic">← Back to Registry</a>
        <a href="{{ route('admin.users') }}" class="btn-galactic" style="border-color: var(--secondary); color: var(--secondary);">Manage Hunters (Admins)</a>
    </div>

    <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); margin-bottom: 3rem;">
        <div class="droid-card" style="clip-path: polygon(10% 0, 100% 0, 100% 90%, 90% 100%, 0 100%, 0 10%);">
            <h3 style="color: var(--primary); font-size: 2.5rem; margin: 0; text-shadow: 0 0 10px var(--primary-glow);">{{ number_format($totalScans) }}</h3>
            <p style="color: var(--text-secondary); text-transform: uppercase; font-weight: 600; font-size: 0.9rem; letter-spacing: 1px;">Total Droids Spotted</p>
        </div>

        <div class="droid-card" style="clip-path: polygon(10% 0, 100% 0, 100% 90%, 90% 100%, 0 100%, 0 10%);">
            <h3 style="color: var(--secondary); font-size: 2.5rem; margin: 0; text-shadow: 0 0 10px var(--secondary-glow);">{{ number_format($totalPlayers) }}</h3>
            <p style="color: var(--text-secondary); text-transform: uppercase; font-weight: 600; font-size: 0.9rem; letter-spacing: 1px;">Total Hunters</p>
            <div style="font-size: 0.7rem; color: #555; margin-top: 0.5rem;">Registered: {{ $totalRegisteredUsers }} | Guest: {{ $totalGuestUsers }}</div>
        </div>

        <div class="droid-card" style="clip-path: polygon(10% 0, 100% 0, 100% 90%, 90% 100%, 0 100%, 0 10%);">
            <h3 style="color: var(--success); font-size: 2.5rem; margin: 0; text-shadow: 0 0 10px rgba(0,255,170,0.3);">{{ number_format($newPlayersThisMonth) }}</h3>
            <p style="color: var(--text-secondary); text-transform: uppercase; font-weight: 600; font-size: 0.9rem; letter-spacing: 1px;">New Recruits (30d)</p>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 2rem;">
        
        <!-- Events Breakdown -->
        <div class="specs-grid" style="padding: 1.5rem; position: relative;">
            <div style="position: absolute; top: 0; left: 0; width: 30px; height: 30px; border-top: 2px solid var(--primary); border-left: 2px solid var(--primary); opacity: 0.5;"></div>
            <div style="position: absolute; bottom: 0; right: 0; width: 30px; height: 30px; border-bottom: 2px solid var(--primary); border-right: 2px solid var(--primary); opacity: 0.5;"></div>
            
            <h2 style="color: var(--primary); text-transform: uppercase; font-size: 1.2rem; border-bottom: 1px solid var(--panel-border); padding-bottom: 0.5rem; margin-top: 0;">Event Activity</h2>
            
            @if($scansByEvent->count() > 0)
                <div style="display: flex; flex-direction: column; gap: 0.8rem; margin-top: 1rem;">
                    @foreach($scansByEvent as $event)
                        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 0.5rem;">
                            <span style="color: var(--text-primary); font-weight: 500;">{{ $event->event_name }}</span>
                            <span class="rarity-tag uncommon">{{ $event->total }} scans</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p style="color: var(--text-secondary); font-style: italic; font-size: 0.9rem;">No event data available.</p>
            @endif
        </div>

        <!-- Most Scanned Droids -->
        <div class="specs-grid" style="padding: 1.5rem; position: relative;">
            <div style="position: absolute; top: 0; left: 0; width: 30px; height: 30px; border-top: 2px solid var(--secondary); border-left: 2px solid var(--secondary); opacity: 0.5;"></div>
            <div style="position: absolute; bottom: 0; right: 0; width: 30px; height: 30px; border-bottom: 2px solid var(--secondary); border-right: 2px solid var(--secondary); opacity: 0.5;"></div>
            
            <h2 style="color: var(--secondary); text-transform: uppercase; font-size: 1.2rem; border-bottom: 1px solid var(--panel-border); padding-bottom: 0.5rem; margin-top: 0;">Top Droids (Global)</h2>
            
            @if($mostScannedDroids->count() > 0)
                <div style="display: flex; flex-direction: column; gap: 0.8rem; margin-top: 1rem;">
                    @foreach($mostScannedDroids as $index => $droid)
                        <div style="display: flex; align-items: center; gap: 1rem; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 0.5rem;">
                            <span style="color: var(--text-secondary); font-size: 1.2rem; font-weight: 700; width: 20px;">#{{ $index + 1 }}</span>
                            <span style="color: var(--text-primary); font-weight: 500; flex: 1;">{{ $droid->droid_name }}</span>
                            <span class="rarity-tag {{ $index == 0 ? 'legendary' : ($index < 3 ? 'rare' : 'common') }}">{{ $droid->total }} spots</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p style="color: var(--text-secondary); font-style: italic; font-size: 0.9rem;">No droid scan data available.</p>
            @endif
        </div>
        
    </div>
</div>
@endsection
