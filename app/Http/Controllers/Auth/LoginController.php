<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\DroidScan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /**
     * Quick login with just a nickname.
     */
    public function quick(Request $request)
    {
        $request->validate([
            'nickname' => 'required|string|max:50',
        ]);

        // Create a guest user
        $user = User::create([
            'name' => $request->nickname,
            'email' => Str::random(10) . '@guest.droidhunter.uk',
            'password' => bcrypt(Str::random(16)),
        ]);

        Auth::login($user);

        // Claim any guest scans from this device
        $visitorId = $request->cookie('visitor_id');
        if ($visitorId) {
            \App\Models\DroidScan::where('visitor_id', $visitorId)
                ->whereNull('user_id')
                ->update(['user_id' => $user->id]);
        }

        // Process pending scan if exists
        if (session()->has('pending_scan')) {
            $droidId = session()->pull('pending_scan');
            
            \App\Models\DroidScan::updateOrCreate([
                'user_id' => $user->id,
                'droid_id' => $droidId,
            ], [
                'visitor_id' => $visitorId
            ]);

            return redirect()->route('registry.show', $droidId)->with('success', 'Welcome, ' . $user->name . '! Your collection is synced.');
        }

        return redirect()->route('registry.index')->with('success', 'Welcome! Your device history has been synced.');
    }
}
