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
    public function process(Request $request, $id)
    {
        $id = (int) $id;
        $user = auth()->user();
        $visitorId = $request->cookie('visitor_id') ?? $request->get('visitor_id') ?? session('visitor_id');
        $signature = $request->query('signature');

        // Verify signature matches droid ID + secret
        $secret = config('services.core_portal.secret');
        $expectedSignature = hash_hmac('sha256', $id, $secret);
        
        if (!hash_equals($expectedSignature, (string) $signature)) {
            return redirect()->route('registry.index')->with('error', 'Invalid scan signature.');
        }

        // Check if already scanned today (by user or device)
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
            ]);
        }

        return redirect()->route('registry.show', ['id' => $id, 'visitor_id' => $visitorId])
            ->with('success', 'Droid spotted!');
    }
}
