@extends('layouts.app')

@section('title', 'Manage Users - Command Center')

@section('content')
<div class="container">
    <div class="header" style="margin-bottom: 2rem;">
        <h1>User Registry</h1>
        <p>Manage Hunter Clearance Levels</p>
    </div>

    <!-- Navigation & Alerts -->
    <div style="margin-bottom: 2rem; display: flex; gap: 1rem; flex-wrap: wrap;">
        <a href="{{ route('admin.stats') }}" class="btn-galactic">← Back to Command Center</a>
    </div>

    @if(session('success'))
        <div style="background: rgba(0, 255, 170, 0.1); border: 1px solid var(--success); color: var(--success); padding: 1rem; margin-bottom: 2rem; text-align: center;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background: rgba(255, 60, 60, 0.1); border: 1px solid var(--danger); color: var(--danger); padding: 1rem; margin-bottom: 2rem; text-align: center;">
            {{ session('error') }}
        </div>
    @endif

    <!-- Search Form -->
    <div class="specs-grid" style="padding: 1.5rem; margin-bottom: 2rem; position: relative;">
        <div style="position: absolute; top: 0; left: 0; width: 30px; height: 30px; border-top: 2px solid var(--primary); border-left: 2px solid var(--primary); opacity: 0.5;"></div>
        
        <form action="{{ route('admin.users') }}" method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: center;">
            <input type="text" name="search" value="{{ $search }}" placeholder="Search by name or email..." 
                   style="flex: 1; min-width: 250px; background: var(--bg-color); border: 1px solid var(--panel-border); color: var(--text-primary); padding: 0.8rem; font-family: 'Rajdhani', sans-serif; font-size: 1.1rem; outline: none;">
            <button type="submit" class="btn-galactic" style="padding: 0.8rem 1.5rem;">Initialize Search</button>
            @if($search)
                <a href="{{ route('admin.users') }}" class="btn-galactic" style="border-color: var(--text-secondary); color: var(--text-secondary);">Clear</a>
            @endif
        </form>
    </div>

    <!-- Users List -->
    <div class="specs-grid" style="padding: 1.5rem; position: relative;">
        <div style="position: absolute; bottom: 0; right: 0; width: 30px; height: 30px; border-bottom: 2px solid var(--primary); border-right: 2px solid var(--primary); opacity: 0.5;"></div>
        
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--primary); color: var(--primary); text-transform: uppercase; font-size: 0.9rem; letter-spacing: 1px;">
                        <th style="padding: 1rem 0.5rem;">Hunter</th>
                        <th style="padding: 1rem 0.5rem;">Contact Intel</th>
                        <th style="padding: 1rem 0.5rem;">Clearance Level</th>
                        <th style="padding: 1rem 0.5rem; text-align: right;">Directives</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.05); transition: background 0.2s;">
                            <td style="padding: 1rem 0.5rem; font-weight: 600;">{{ $user->name }}</td>
                            <td style="padding: 1rem 0.5rem; color: var(--text-secondary);">{{ $user->email }}</td>
                            <td style="padding: 1rem 0.5rem;">
                                @if($user->is_admin)
                                    <span class="rarity-tag legendary">Commander (Admin)</span>
                                @else
                                    <span class="rarity-tag common">Standard Hunter</span>
                                @endif
                            </td>
                            <td style="padding: 1rem 0.5rem; text-align: right;">
                                <form action="{{ route('admin.users.toggle-admin', $user->id) }}" method="POST" onsubmit="return confirm('Change clearance level for {{ $user->name }}?');">
                                    @csrf
                                    <button type="submit" class="btn-galactic" style="font-size: 0.7rem; padding: 0.3rem 0.8rem; {{ $user->is_admin ? 'border-color: var(--danger); color: var(--danger);' : 'border-color: var(--success); color: var(--success);' }}" {{ $user->id === auth()->id() ? 'disabled title="Cannot change own level"' : '' }}>
                                        {{ $user->is_admin ? 'Revoke Admin' : 'Grant Admin' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="padding: 2rem; text-align: center; color: var(--text-secondary); font-style: italic;">
                                No hunters found matching those parameters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div style="margin-top: 2rem; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--panel-border); padding-top: 1rem;">
                <div>
                    @if($users->onFirstPage())
                        <span class="btn-galactic" style="opacity: 0.5; cursor: not-allowed; border-color: var(--text-secondary); color: var(--text-secondary);">← Previous</span>
                    @else
                        <a href="{{ $users->previousPageUrl() }}&search={{ urlencode($search) }}" class="btn-galactic">← Previous</a>
                    @endif
                </div>
                
                <div style="color: var(--text-secondary); font-size: 0.9rem;">
                    Page {{ $users->currentPage() }} of {{ $users->lastPage() }}
                </div>

                <div>
                    @if($users->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}&search={{ urlencode($search) }}" class="btn-galactic">Next →</a>
                    @else
                        <span class="btn-galactic" style="opacity: 0.5; cursor: not-allowed; border-color: var(--text-secondary); color: var(--text-secondary);">Next →</span>
                    @endif
                </div>
            </div>
        @endif

    </div>
</div>
@endsection
