<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\DroidScan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        $socialUser = Socialite::driver($provider)->user();

        $user = User::updateOrCreate([
            'email' => $socialUser->getEmail(),
        ], [
            'name' => $socialUser->getName(),
            'password' => bcrypt(Str::random(16)), // Dummy password for social users
        ]);

        Auth::login($user);

        // Claim any guest scans from this device
        $visitorId = request()->cookie('visitor_id');
        if ($visitorId) {
            \App\Models\DroidScan::where('visitor_id', $visitorId)
                ->whereNull('user_id')
                ->update(['user_id' => $user->id]);
        }

        // Check if there was a pending scan before login
        if (session()->has('pending_scan')) {
            $droidId = session()->pull('pending_scan');
            
            \App\Models\DroidScan::updateOrCreate([
                'user_id' => $user->id,
                'droid_id' => $droidId,
            ], [
                'visitor_id' => $visitorId
            ]);

            return redirect()->route('registry.show', $droidId)->with('success', 'Logged in and droid added!');
        }

        return redirect()->route('registry.index')->with('success', 'Logged in! Your collection has been synced.');
    }
}
