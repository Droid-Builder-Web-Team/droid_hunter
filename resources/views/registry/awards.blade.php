@extends('layouts.app')

@section('title', 'Galactic Awards - Droid Hunter')

@section('content')
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <a href="{{ route('registry.index') }}" class="btn-galactic text-decoration-none">&larr; NAV_BACK</a>
        </div>

        <header class="header">
            <h1>Galactic Awards</h1>
            <p>Hunters Dossier // Achievements Unlocked</p>
        </header>

        <div class="awards-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem; margin-top: 3rem;">
            @foreach($badges as $badge)
                <div class="award-card {{ $badge['unlocked'] ? 'unlocked' : 'locked' }}" 
                     style="background: var(--panel-bg); border: 1px solid var(--panel-border); padding: 2rem; position: relative; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); display: flex; flex-direction: column; align-items: center; text-align: center; gap: 1rem;">
                    
                    <div class="award-icon" style="font-size: 3rem; margin-bottom: 0.5rem; filter: {{ $badge['unlocked'] ? 'drop-shadow(0 0 10px var(--primary-glow))' : 'grayscale(1) opacity(0.3)' }}; transition: all 0.5s;">
                        {{ $badge['icon'] }}
                    </div>
                    
                    <h3 style="margin: 0; color: {{ $badge['unlocked'] ? 'var(--primary)' : 'var(--text-secondary)' }}; letter-spacing: 2px; text-transform: uppercase;">
                        {{ $badge['title'] }}
                    </h3>
                    
                    <p style="font-size: 0.9rem; color: var(--text-secondary); margin: 0; line-height: 1.4;">
                        {{ $badge['description'] }}
                    </p>

                    @if($badge['unlocked'])
                        <div style="position: absolute; top: 10px; right: 10px; color: var(--success); font-size: 0.7rem; font-weight: 800; letter-spacing: 1px;">[ UNLOCKED ]</div>
                        <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 3px; background: var(--primary); box-shadow: 0 0 10px var(--primary-glow);"></div>
                    @else
                        <div style="position: absolute; top: 10px; right: 10px; color: var(--text-secondary); font-size: 0.7rem; font-weight: 800; letter-spacing: 1px; opacity: 0.5;">[ ENCRYPTED ]</div>
                    @endif
                </div>
            @endforeach
        </div>

        <footer style="margin-top: 4rem; text-align: center; border-top: 1px solid var(--panel-border); padding-top: 2rem; padding-bottom: 4rem;">
            <p style="font-size: 0.75rem; color: var(--text-secondary); opacity: 0.6; text-transform: uppercase; letter-spacing: 1px;">
                ACHIEVEMENT_ENGINE_V1 &bull; SECTOR_7_AWARDS
            </p>
        </footer>
    </div>
@endsection

@push('head')
    <style>
        .award-card.unlocked:hover {
            transform: translateY(-10px) scale(1.02);
            border-color: var(--primary);
            box-shadow: 0 15px 30px rgba(0, 242, 255, 0.1), inset 0 0 15px var(--primary-glow);
        }
        .award-card.locked {
            opacity: 0.7;
            background: repeating-linear-gradient(
                45deg,
                rgba(30, 38, 49, 0.2),
                rgba(30, 38, 49, 0.2) 10px,
                rgba(22, 29, 39, 0.2) 10px,
                rgba(22, 29, 39, 0.2) 20px
            );
        }
    </style>
@endpush
