<?php

namespace App\Http\Controllers;

use App\Models\DroidScan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Class RegistryController
 * 
 * Handles the display of the droid registry, individual droid details, 
 * and the user's scan history.
 */
class RegistryController extends Controller
{
    /**
     * Show the user's droid collection (The Registry).
     * 
     * Fetches all scanned droids for the current user or guest session,
     * cross-references them with the full droid list from the Core Portal,
     * and handles guest-to-user data synchronization.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $visitorId = $request->cookie('visitor_id') ?? session('visitor_id');

        // Safety Sync: If a user has just logged in, migrate any guest scans 
        // from this device/session to their permanent user account.
        if ($userId && $visitorId) {
            DroidScan::where('visitor_id', $visitorId)
                ->whereNull('user_id')
                ->update(['user_id' => $userId]);
        }

        $user = auth()->user();
        
        // Fetch all local scan records for this user or device
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
        
        // Fetch the master list of public droids from the Core Portal API
        $coreUrl = rtrim(config('services.core_portal.url', 'http://localhost:8001'), '/');
        $response = Http::get($coreUrl . '/api/v1/droids');
        
        $allDroids = [];
        if ($response->successful()) {
            $allDroids = $response->json() ?? [];
        }
        
        // Process each droid: mark if found and assign appropriate UI placeholders/ranks
        foreach ($allDroids as &$droid) {
            $droid['found'] = in_array($droid['id'], $userScansIds);
            $droid['encounters'] = $droid['found'] ? count($scans[$droid['id']]) : 0;
            
            // Evolution Logic: Determine rank based on encounter count
            $droid['rank'] = match(true) {
                $droid['encounters'] >= 5 => 'GOLD',
                $droid['encounters'] >= 3 => 'SILVER',
                $droid['encounters'] >= 1 => 'BRONZE',
                default => 'LOCKED',
            };
            
            $droid['placeholder'] = $this->getDroidPlaceholder($droid['club']['name'] ?? 'Generic');
        }

        $nearbyIntel = $this->calculateNearbyIntel($allDroids, $user, $visitorId);

        return view('registry.index', compact('allDroids', 'nearbyIntel'));
    }

    /**
     * API Endpoint for real-time intelligence updates.
     */
    public function getIntel(Request $request)
    {
        $user = auth()->user();
        $visitorId = $request->cookie('visitor_id') ?? session('visitor_id');

        // Fetch droids from API for names
        $coreUrl = rtrim(config('services.core_portal.url', 'http://localhost:8001'), '/');
        $response = Http::get($coreUrl . '/api/v1/droids');
        $allDroids = $response->successful() ? ($response->json() ?? []) : [];

        $intel = $this->calculateNearbyIntel($allDroids, $user, $visitorId);

        return response()->json($intel);
    }

    /**
     * Shared logic to calculate the latest 'Nearby' or 'Global' intelligence.
     */
    private function calculateNearbyIntel($allDroids, $user, $visitorId)
    {
        $latestUserScan = DroidScan::where(function($query) use ($user, $visitorId) {
                if ($user) $query->where('user_id', $user->id);
                if ($visitorId) $query->orWhere('visitor_id', $visitorId);
            })
            ->whereNotNull('event_name')
            ->latest()
            ->first();

        $currentEvent = $latestUserScan->event_name ?? null;

        $latestGlobalScan = DroidScan::when($currentEvent, function($q) use ($currentEvent) {
                return $q->where('event_name', $currentEvent);
            })
            ->latest()
            ->first();
        
        if (!$latestGlobalScan) {
            $latestGlobalScan = DroidScan::latest()->first();
        }

        if ($latestGlobalScan) {
            $matchedDroid = collect($allDroids)->firstWhere('id', $latestGlobalScan->droid_id);
            if ($matchedDroid) {
                $minutesAgo = $latestGlobalScan->created_at->diffInMinutes(now());
                $timeString = $minutesAgo === 0 ? 'JUST NOW' : ($minutesAgo . 'm AGO');
                
                return [
                    'droid_name' => $matchedDroid['name'],
                    'event_name' => $latestGlobalScan->event_name ?? 'SECTOR_UNKNOWN',
                    'time' => $timeString
                ];
            }
        }

        return null;
    }

    /**
     * Proxies images from the Core Portal to bypass CORS restrictions during share card generation.
     */
    public function proxyImage(Request $request)
    {
        $url = $request->query('url');
        if (!$url) return response('Missing URL', 400);

        try {
            // Use follow_redirects and a proper timeout
            $response = Http::withOptions([
                'allow_redirects' => true,
                'verify' => false, // Sometimes needed for local dev environments
            ])->get($url);

            if (!$response->successful()) {
                \Log::error("Proxy failed for URL: {$url} (Status: " . $response->status() . ")");
                return response('Failed to fetch image', 500);
            }

            return response($response->body())
                ->header('Content-Type', $response->header('Content-Type'))
                ->header('Cache-Control', 'public, max-age=86400');
        } catch (\Exception $e) {
            \Log::error("Proxy exception: " . $e->getMessage());
            return response('Error: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Show detailed information for a specific droid.
     * 
     * Verifies the user has scanned the droid before showing rich details 
     * fetched from the Core Portal.
     *
     * @param int|string $id The Droid ID
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function show($id)
    {
        $id = (int) $id;
        $user = auth()->user();
        $visitorId = request()->cookie('visitor_id') ?? request()->get('visitor_id') ?? session('visitor_id');

        // Verify the droid has been scanned by this user/device
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
            Log::warning("Unauthorized detail view attempt", ['droid_id' => $id]);
            return redirect()->route('registry.index')->with('error', 'You haven\'t spotted this droid yet!');
        }

        // Fetch rich data from Core Portal API (specifications, backstory, etc)
        $coreUrl = rtrim(config('services.core_portal.url'), '/');
        $response = Http::get($coreUrl . '/api/v1/droids/' . $id);
        
        if ($response->failed()) {
            Log::error("API Request Failed", ['url' => $coreUrl . '/api/v1/droids/' . $id, 'status' => $response->status()]);
            return redirect()->route('registry.index')->with('error', 'Droid data could not be retrieved from the Portal.');
        }

        $droid = $response->json();
        
        if (!isset($droid['name'])) {
            Log::error("API Response Incomplete", ['data' => $droid]);
            return redirect()->route('registry.index')->with('error', 'Droid data is incomplete.');
        }

        // Calculate total historical encounters for this droid
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

        $droid['placeholder'] = $this->getDroidPlaceholder($droid['club']['name'] ?? 'Generic');

        // Evolution Logic: Determine rank based on encounter count
        $droid['rank'] = match(true) {
            $encounters >= 5 => 'GOLD',
            $encounters >= 3 => 'SILVER',
            $encounters >= 1 => 'BRONZE',
            default => 'LOCKED',
        };

        // Fetch full chronological encounter history for this specific droid
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

        // Calculate total global encounters for this droid (Builder Recognition)
        $globalSpottedCount = DroidScan::where('droid_id', $id)->count();

        // Get the current event for the share card context
        $latestUserScan = DroidScan::where(function($query) use ($user, $visitorId) {
                if ($user) $query->where('user_id', $user->id);
                if ($visitorId) $query->orWhere('visitor_id', $visitorId);
            })
            ->whereNotNull('event_name')
            ->latest()
            ->first();
        $currentEvent = $latestUserScan->event_name ?? 'SECTOR_UNKNOWN';

        // Base64 encode the droid image to guarantee capture in the share card (bypasses all CORS)
        $photoUrl = rtrim(config('services.core_portal.url'), '/') . '/droid_image/' . $id . '/photo_front/480';
        $photoBase64 = null;
        try {
            $imageResponse = Http::get($photoUrl);
            if ($imageResponse->successful()) {
                $photoBase64 = 'data:' . $imageResponse->header('Content-Type') . ';base64,' . base64_encode($imageResponse->body());
                \Log::info("Share Card: Photo fetch successful for droid {$id}");
            } else {
                \Log::warning("Share Card: Photo fetch failed (Status: " . $imageResponse->status() . ") for droid {$id}");
            }
        } catch (\Exception $e) {
            \Log::error("Share Card: Photo fetch exception: " . $e->getMessage());
        }

        return view('registry.show', compact('droid', 'scan', 'encounters', 'scanHistory', 'globalSpottedCount', 'currentEvent', 'photoBase64'));
    }

    /**
     * Show the user's full chronological scan history.
     * 
     * Displays every droid encounter recorded for the current user/device.
     *
     * @param Request $request
     * @return \Illuminate\View\View
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

        // Fetch master list to map droid IDs to names and images
        $coreUrl = rtrim(config('services.core_portal.url', 'http://localhost:8001'), '/');
        $response = Http::get($coreUrl . '/api/v1/droids');
        
        $droids = [];
        if ($response->successful()) {
            $allDroids = $response->json() ?? [];
            foreach ($allDroids as $d) {
                $droids[$d['id']] = $d;
            }
        }

        // Decorate scan objects with droid metadata
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
     * Show the user's earned achievement badges (Awards).
     * 
     * Calculates badges based on scan history, such as first scan, 
     * day streaks, and time-based challenges.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function awards(Request $request)
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
            })->get();

        $totalScans = $scans->count();
        $uniqueDroids = $scans->unique('droid_id')->count();
        $uniqueDays = $scans->map(fn($s) => $s->created_at->toDateString())->unique()->count();
        
        $badges = [
            [
                'id' => 'first_scan',
                'title' => 'First Contact',
                'description' => 'Scanned your very first droid.',
                'unlocked' => $totalScans >= 1,
                'icon' => '🛰️'
            ],
            [
                'id' => 'veteran',
                'title' => 'Event Veteran',
                'description' => 'Spotted droids on 3 different days.',
                'unlocked' => $uniqueDays >= 3,
                'icon' => '🎖️'
            ],
            [
                'id' => 'collector',
                'title' => 'Master Collector',
                'description' => 'Registered 10 unique droids in your registry.',
                'unlocked' => $uniqueDroids >= 10,
                'icon' => '📦'
            ],
            [
                'id' => 'early_bird',
                'title' => 'Early Bird',
                'description' => 'Scanned a droid before 10:00 AM.',
                'unlocked' => $scans->contains(fn($s) => $s->created_at->hour < 10),
                'icon' => '🌅'
            ],
            [
                'id' => 'night_owl',
                'title' => 'Night Owl',
                'description' => 'Scanned a droid after 8:00 PM.',
                'unlocked' => $scans->contains(fn($s) => $s->created_at->hour >= 20),
                'icon' => '🌙'
            ]
        ];

        return view('registry.awards', compact('badges'));
    }

    /**
     * Proxies images from the Core Portal to bypass CORS restrictions during share card generation.
     */
    public function proxyImage(Request $request)
    {
        $url = $request->query('url');
        if (!$url) return response('Missing URL', 400);

        // Security: Only allow proxying from the configured Core Portal URL
        $coreUrl = rtrim(config('services.core_portal.url'), '/');
        if (!str_starts_with($url, $coreUrl)) {
            return response('Unauthorized source', 403);
        }

        try {
            $response = Http::get($url);
            if (!$response->successful()) {
                return response('Failed to fetch image', 500);
            }

            return response($response->body())
                ->header('Content-Type', $response->header('Content-Type'))
                ->header('Cache-Control', 'public, max-age=86400');
        } catch (\Exception $e) {
            return response('Error: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get the appropriate placeholder image for a droid based on its club/type.
     * 
     * Ensures consistent fallback silhouettes when primary photos are missing.
     * 
     * @param string $clubName
     * @return string URL to the placeholder image
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
