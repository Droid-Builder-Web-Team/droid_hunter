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
        $coreUrl = config('services.core_portal.url', 'http://localhost:8001');
        $response = Http::get($coreUrl . '/api/v1/droids');
        
        $allDroids = $response->json() ?? [];
        
        // Mark which ones are found
        foreach ($allDroids as &$droid) {
            $droid['found'] = in_array($droid['id'], $userScans);
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

        $coreUrl = config('services.core_portal.url', 'http://localhost:8001');
        $response = Http::get($coreUrl . '/api/v1/droids/' . $id);
        
        $droid = $response->json();

        return view('registry.show', compact('droid', 'scan'));
    }
}
