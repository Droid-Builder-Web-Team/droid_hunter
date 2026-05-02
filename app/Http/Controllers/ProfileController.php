<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function destroy(Request $request)
    {
        $user = auth()->user();

        // Delete all scans for this user
        \App\Models\DroidScan::where('user_id', $user->id)->delete();

        // Delete the user
        $user->delete();

        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('registry.index')->with('success', 'Your account and all associated data have been permanently deleted.');
    }
}
