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

        // Process pending scan if exists
        if (session()->has('pending_scan')) {
            $droidId = session()->pull('pending_scan');
            
            DroidScan::updateOrCreate([
                'user_id' => $user->id,
                'droid_id' => $droidId,
            ]);

            return redirect()->route('registry.show', $droidId)->with('success', 'Logged in and droid added!');
        }

        return redirect()->route('registry.index');
    }
}
