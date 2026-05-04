<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DroidScan;

/**
 * Class ProfileController
 * 
 * Manages user profile actions, including account deletion and GDPR compliance.
 */
class ProfileController extends Controller
{
    /**
     * Delete the user's account and all associated scan data.
     * 
     * Implements GDPR "Right to be Forgotten" by permanently purging 
     * all encounter history linked to the user.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $user = auth()->user();

        // GDPR Purge: Delete all scans tied to this user account
        DroidScan::where('user_id', $user->id)->delete();

        // Delete the user record itself
        $user->delete();

        // Clear the session and log out
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('registry.index')
            ->with('success', 'Your account and all associated encounter data have been permanently deleted from this sector.');
    }
}
