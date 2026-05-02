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
        $signature = $request->query('signature');
        
        // Validate signature using the shared secret
        $secret = config('services.core_portal.secret');
        $expectedSignature = hash_hmac('sha256', $id, $secret);
        
        if ($signature !== $expectedSignature) {
            abort(403, 'Invalid scan signature');
        }

        if (Auth::check()) {
            // Log the encounter (once per day)
            $existingScan = DroidScan::where('user_id', Auth::id())
                ->where('droid_id', $id)
                ->whereDate('created_at', now()->toDateString())
                ->first();

            if (!$existingScan) {
                DroidScan::create([
                    'user_id' => Auth::id(),
                    'droid_id' => $id,
                ]);
                $message = 'Droid encountered!';
            } else {
                $message = 'You already spotted this droid today!';
            }

            return redirect()->route('registry.show', $id)->with('success', $message);
        }

        // If not logged in, store the scan in session and redirect to login
        session(['pending_scan' => $id]);
        
        return redirect()->route('login')->with('info', 'Please log in to add this droid to your collection.');
    }
}
