<?php

namespace App\Http\Controllers;

use App\Models\DroidScan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class AdminController extends Controller
{
    public function index()
    {
        // General stats
        $totalScans = DroidScan::count();
        
        $totalRegisteredUsers = User::count();
        $totalGuestUsers = DroidScan::whereNull('user_id')->distinct('visitor_id')->count('visitor_id');
        $totalPlayers = $totalRegisteredUsers + $totalGuestUsers;

        $newPlayersThisMonth = User::where('created_at', '>=', now()->subDays(30))->count();

        // Scans by event
        $scansByEvent = DroidScan::select('event_name', \DB::raw('count(*) as total'))
            ->whereNotNull('event_name')
            ->groupBy('event_name')
            ->orderBy('total', 'desc')
            ->get();

        // Most scanned droids
        $mostScannedDroids = DroidScan::select('droid_id', \DB::raw('count(*) as total'))
            ->groupBy('droid_id')
            ->orderBy('total', 'desc')
            ->take(10)
            ->get();

        // Fetch master list to map droid IDs to names
        $coreUrl = rtrim(config('services.core_portal.url', 'http://localhost:8001'), '/');
        
        $allDroids = Cache::remember('all_droids_list', 3600, function() use ($coreUrl) {
            try {
                $response = Http::timeout(10)->get($coreUrl . '/api/v1/droids');
                return $response->successful() ? ($response->json() ?? []) : [];
            } catch (\Exception $e) {
                return [];
            }
        });

        $droidsMap = [];
        foreach ($allDroids as $d) {
            $droidsMap[$d['id']] = $d;
        }

        foreach ($mostScannedDroids as $scan) {
            $droidData = $droidsMap[$scan->droid_id] ?? null;
            if ($droidData) {
                $scan->droid_name = $droidData['name'] ?? 'Unknown Droid';
            } else {
                $scan->droid_name = 'Droid #' . $scan->droid_id;
            }
        }

        return view('admin.index', compact(
            'totalScans',
            'totalPlayers',
            'totalRegisteredUsers',
            'totalGuestUsers',
            'newPlayersThisMonth',
            'scansByEvent',
            'mostScannedDroids'
        ));
    }

    public function users(Request $request)
    {
        $search = $request->input('search');

        $users = User::when($search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        })->orderBy('name')->paginate(20);

        return view('admin.users', compact('users', 'search'));
    }

    public function toggleAdmin($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent users from removing their own admin status
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot remove your own admin status.');
        }

        $user->is_admin = !$user->is_admin;
        $user->save();

        return redirect()->back()->with('success', "Admin status for {$user->name} updated successfully.");
    }
}
