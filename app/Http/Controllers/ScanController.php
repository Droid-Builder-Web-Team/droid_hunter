<?php

namespace App\Http\Controllers;

use App\Models\DroidScan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScanController extends Controller
{
    /**
     * Process the scan from the Core Portal.
     */
    public function process(Request $request, $droidId, $hash)
    {
        $user = auth()->user();
        $visitorId = $request->cookie('visitor_id');

        // Verify hash matches droidId + secret
        $expectedHash = hash_hmac('sha256', $droidId, config('services.core_portal.tag_secret'));
        
        if (!hash_equals($expectedHash, $hash)) {
            return redirect()->route('registry.index')->with('error', 'Invalid scan tag.');
        }

        // Check if already scanned today (by user or device)
        $existing = DroidScan::where('droid_id', $droidId)
            ->where(function($query) use ($user, $visitorId) {
                if ($user) {
                    $query->where('user_id', $user->id);
                }
                
                if ($visitorId) {
                    $query->orWhere('visitor_id', $visitorId);
                }
            })
            ->whereDate('created_at', now()->toDateString())
            ->exists();

        if (!$existing) {
            DroidScan::create([
                'user_id' => $user->id ?? null,
                'visitor_id' => $visitorId,
                'droid_id' => $droidId,
            ]);
        }

        return redirect()->route('registry.show', $droidId)
            ->with('success', 'Droid spotted!');
    }
}
