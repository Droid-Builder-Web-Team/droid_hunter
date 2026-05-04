<?php

namespace App\Http\Controllers;

use App\Models\DroidScan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class ScanController
 * 
 * Handles the secure ingestion of droid scans from the Core Portal.
 */
class ScanController extends Controller
{
    /**
     * Process a scan request from the Core Portal.
     * 
     * Validates the request using an HMAC signature to prevent spoofing,
     * then records the encounter if it's the first one today for this user/device.
     *
     * @param Request $request
     * @param int|string $id The Droid ID being scanned
     * @return \Illuminate\Http\RedirectResponse
     */
    public function process(Request $request, $id)
    {
        $id = (int) $id;
        $user = auth()->user();
        
        // Identify the user via their account or their unique visitor cookie
        $visitorId = $request->cookie('visitor_id') ?? $request->get('visitor_id') ?? session('visitor_id');
        $signature = $request->query('signature');

        // SECURITY: Verify the HMAC signature matches the droid ID + shared secret.
        // This ensures the scan originated from an authorized QR code/Portal link.
        $secret = config('services.core_portal.secret');
        $expectedSignature = hash_hmac('sha256', $id, $secret);
        
        if (!hash_equals($expectedSignature, (string) $signature)) {
            return redirect()->route('registry.index')->with('error', 'Invalid scan signature. Encounter rejected.');
        }

        // DATABASE OPTIMIZATION: Only record one scan per droid, per day, per user/device.
        // This keeps the database lean while still providing a chronological history.
        $existing = DroidScan::where('droid_id', $id)
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
                'droid_id' => $id,
                'event_name' => $request->query('event'), // Capture event from Portal redirect
            ]);
        }

        return redirect()->route('registry.show', ['id' => $id, 'visitor_id' => $visitorId])
            ->with('success', 'Droid spotted! Database updated.');
    }
}
