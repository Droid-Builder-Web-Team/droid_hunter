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
        $userScans = DroidScan::where('user_id', Auth::id())->pluck('droid_id')->toArray();
        
        // Fetch all droids from Core Portal API
        $coreUrl = rtrim(config('services.core_portal.url', 'http://localhost:8001'), '/');
        $response = Http::get($coreUrl . '/api/v1/droids');
        
        $allDroids = [];
        if ($response->successful()) {
            $allDroids = $response->json() ?? [];
            \Log::debug('Registry API Success:', ['count' => count($allDroids)]);
        } else {
            \Log::error('Registry Index API Failure:', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
        }
        
        // Mark which ones are found and assign placeholders
        foreach ($allDroids as &$droid) {
            $droid['found'] = in_array($droid['id'], $userScans);
            
            if (!$droid['found']) {
                $clubName = $droid['club']['name'] ?? 'Generic';
                $droid['placeholder'] = match(true) {
                    str_contains($clubName, 'R2 Builders') || str_contains($clubName, '39.1%') => asset('images/placeholders/astromech.png'),
                    str_contains($clubName, 'BB-8') => asset('images/placeholders/bb8.png'),
                    str_contains($clubName, 'MSE-6') => asset('images/placeholders/mouse.png'),
                    str_contains($clubName, 'Protocol') => asset('images/placeholders/protocol.png'),
                    str_contains($clubName, 'A-LT') => asset('images/placeholders/alt.png'),
                    default => asset('images/placeholders/astromech.png'), // Default to astromech
                };
            }
        }

        return view('registry.index', compact('allDroids'));
    }

    /**
     * Show details for a specific droid in the registry.
     */
    public function show($id)
    {
        $scan = DroidScan::where('user_id', Auth::id())->where('droid_id', $id)->first();
        
        if (!$scan) {
            return redirect()->route('registry.index')->with('error', 'You haven\'t found this droid yet!');
        }

        $coreUrl = rtrim(config('services.core_portal.url', 'http://localhost:8001'), '/');
        $response = Http::get($coreUrl . '/api/v1/droids/' . $id);
        
        if ($response->failed()) {
            return redirect()->route('registry.index')->with('error', 'Could not fetch droid details from the portal.');
        }

        $droid = $response->json();

        return view('registry.show', compact('droid', 'scan'));
    }
}
