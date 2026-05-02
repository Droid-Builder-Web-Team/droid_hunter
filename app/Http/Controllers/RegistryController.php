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
    public function index()
    {
        $user = auth()->user();
        $visitorId = request()->cookie('visitor_id') ?? request()->get('visitor_id');
        
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
            
            $clubName = $droid['club']['name'] ?? 'Generic';
            $droid['placeholder'] = match(true) {
                str_contains($clubName, 'R2 Builders') || str_contains($clubName, '39.1%') => asset('images/placeholders/astromech.png'),
                str_contains($clubName, 'BB-8') => asset('images/placeholders/bb8.png'),
                str_contains($clubName, 'MSE-6') => asset('images/placeholders/mouse.png'),
                str_contains($clubName, 'Protocol') => asset('images/placeholders/protocol.png'),
                str_contains($clubName, 'A-LT') => asset('images/placeholders/alt.png'),
                default => asset('images/placeholders/astromech.png'),
            };
        }

        return view('registry.index', compact('allDroids'));
    }

    /**
     * Show details for a specific droid in the registry.
     */
    public function show($id)
    {
        $user = auth()->user();
        $visitorId = request()->cookie('visitor_id') ?? request()->get('visitor_id');

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
            return redirect()->route('registry.index')->with('error', 'You haven\'t spotted this droid yet!');
        }

        // Fetch rich data from Core Portal API
        $response = Http::get(config('services.core_portal.url') . '/api/v1/droids/' . $id);
        $droid = $response->json();

        // Assign placeholder for fallback
        $clubName = $droid['club']['name'] ?? 'Generic';
        $droid['placeholder'] = match(true) {
            str_contains($clubName, 'R2 Builders') || str_contains($clubName, '39.1%') => asset('images/placeholders/astromech.png'),
            str_contains($clubName, 'BB-8') => asset('images/placeholders/bb8.png'),
            str_contains($clubName, 'MSE-6') => asset('images/placeholders/mouse.png'),
            str_contains($clubName, 'Protocol') => asset('images/placeholders/protocol.png'),
            str_contains($clubName, 'A-LT') => asset('images/placeholders/alt.png'),
            default => asset('images/placeholders/astromech.png'),
        };

        return view('registry.show', compact('droid', 'scan'));
    }
}
