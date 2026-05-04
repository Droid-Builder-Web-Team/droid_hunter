<?php

namespace App\Http\Controllers;

use App\Models\DroidScan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class RegistryController extends Controller
{
    /**
     * Show the user's droid collection.
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $visitorId = $request->cookie('visitor_id') ?? session('visitor_id');

        // Safety Sync: If logged in, migrate any guest scans from this device
        if ($userId && $visitorId) {
            \App\Models\DroidScan::where('visitor_id', $visitorId)
                ->whereNull('user_id')
                ->update(['user_id' => $userId]);
        }

        $user = auth()->user();
        
        \Log::error("DEBUG: Registry Index Hit", [
            'user_id' => $user->id ?? 'GUEST',
            'visitor_id' => $visitorId ?? 'NONE'
        ]);
        
        // Fetch all scans for this user or device
        $scans = DroidScan::where(function($query) use ($user, $visitorId) {
                if ($user) {
                    $query->where('user_id', $user->id);
                }
                
                if ($visitorId) {
                    $query->orWhere('visitor_id', $visitorId);
                }
            })
            ->get()
            ->groupBy('droid_id');

        $userScansIds = $scans->keys()->toArray();
        
        // Fetch all droids from Core Portal API
        $coreUrl = rtrim(config('services.core_portal.url', 'http://localhost:8001'), '/');
        $response = Http::get($coreUrl . '/api/v1/droids');
        
        $allDroids = [];
        if ($response->successful()) {
            $allDroids = $response->json() ?? [];
        }
        
        // Mark which ones are found and assign placeholders
        foreach ($allDroids as &$droid) {
            $droid['found'] = in_array($droid['id'], $userScansIds);
            $droid['encounters'] = $droid['found'] ? count($scans[$droid['id']]) : 0;
            
            $droid['placeholder'] = $this->getDroidPlaceholder($droid['club']['name'] ?? 'Generic');
        }

        return view('registry.index', compact('allDroids'));
    }

    /**
     * Show details for a specific droid in the registry.
     */
    public function show($id)
    {
        $id = (int) $id;
        $user = auth()->user();
        $visitorId = request()->cookie('visitor_id') ?? request()->get('visitor_id') ?? session('visitor_id');

        \Log::error("DEBUG: Viewing Droid Details", [
            'droid_id' => $id,
            'user_id' => $user->id ?? 'GUEST',
            'visitor_id' => $visitorId ?? 'NONE'
        ]);

        $scan = DroidScan::where('droid_id', $id)
            ->where(function($query) use ($user, $visitorId) {
                if ($user) {
                    $query->where('user_id', $user->id);
                }
                if ($visitorId) {
                    $query->orWhere('visitor_id', $visitorId);
                }
            })
            ->latest()
            ->first();
        
        if (!$scan) {
            \Log::warning("Scan not found for user/guest", ['droid_id' => $id]);
            return redirect()->route('registry.index')->with('error', 'You haven\'t spotted this droid yet!');
        }

        // Fetch rich data from Core Portal API
        $coreUrl = rtrim(config('services.core_portal.url'), '/');
        $response = Http::get($coreUrl . '/api/v1/droids/' . $id);
        
        if ($response->failed()) {
            \Log::error("API Request Failed", ['url' => $coreUrl . '/api/v1/droids/' . $id, 'status' => $response->status()]);
            return redirect()->route('registry.index')->with('error', 'Droid data could not be retrieved from the Portal.');
        }

        if (!isset($response->json()['name'])) {
            \Log::error("API Response Missing Name", ['data' => $response->json()]);
            return redirect()->route('registry.index')->with('error', 'Droid data is incomplete.');
        }

        $droid = $response->json();

        // Calculate encounters once here to keep the view clean
        $encounters = DroidScan::where('droid_id', $id)
            ->where(function($query) use ($user, $visitorId) {
                if ($user) {
                    $query->where('user_id', $user->id);
                }
                if ($visitorId) {
                    $query->orWhere('visitor_id', $visitorId);
                }
            })
            ->count();

        // Assign placeholder for fallback
        $droid['placeholder'] = $this->getDroidPlaceholder($droid['club']['name'] ?? 'Generic');

        $scanHistory = DroidScan::where('droid_id', $id)
            ->where(function($query) use ($user, $visitorId) {
                if ($user) {
                    $query->where('user_id', $user->id);
                }
                if ($visitorId) {
                    $query->orWhere('visitor_id', $visitorId);
                }
            })
            ->latest()
            ->get();

        return view('registry.show', compact('droid', 'scan', 'encounters', 'scanHistory'));
    }

    /**
     * Show the user's scan history.
     */
    public function history(Request $request)
    {
        $user = auth()->user();
        $visitorId = $request->cookie('visitor_id') ?? session('visitor_id');

        $scans = DroidScan::where(function($query) use ($user, $visitorId) {
                if ($user) {
                    $query->where('user_id', $user->id);
                }
                if ($visitorId) {
                    $query->orWhere('visitor_id', $visitorId);
                }
            })
            ->latest()
            ->get();

        // Fetch all droids to get names/images
        $coreUrl = rtrim(config('services.core_portal.url', 'http://localhost:8001'), '/');
        $response = Http::get($coreUrl . '/api/v1/droids');
        
        $droids = [];
        if ($response->successful()) {
            $allDroids = $response->json() ?? [];
            foreach ($allDroids as $d) {
                $droids[$d['id']] = $d;
            }
        }

        // Attach droid info to scans
        foreach ($scans as $scan) {
            $droidData = $droids[$scan->droid_id] ?? null;
            if ($droidData) {
                $droidData['placeholder'] = $this->getDroidPlaceholder($droidData['club']['name'] ?? 'Generic');
                $scan->droid = $droidData;
            }
        }

        return view('registry.history', compact('scans'));
    }

    /**
     * Get the appropriate placeholder image for a droid based on its club.
     */
    private function getDroidPlaceholder($clubName)
    {
        return match(true) {
            str_contains($clubName, 'R2 Builders') || str_contains($clubName, '39.1%') => asset('images/placeholders/astromech.png'),
            str_contains($clubName, 'BB-8') => asset('images/placeholders/bb8.png'),
            str_contains($clubName, 'MSE-6') => asset('images/placeholders/mouse.png'),
            str_contains($clubName, 'Protocol') => asset('images/placeholders/protocol.png'),
            str_contains($clubName, 'A-LT') => asset('images/placeholders/alt.png'),
            default => asset('images/placeholders/astromech.png'),
        };
    }
}
