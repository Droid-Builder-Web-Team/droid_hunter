<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureVisitorId
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $visitorId = $request->cookie('visitor_id') ?? session('visitor_id');

        if (!$visitorId) {
            $visitorId = (string) \Illuminate\Support\Str::uuid();
            
            // Store in session for immediate persistence across redirects
            session(['visitor_id' => $visitorId]);
            
            // Inject into the current request so controllers can see it immediately
            $request->cookies->add(['visitor_id' => $visitorId]);
            $request->offsetSet('visitor_id', $visitorId);
            
            $response = $next($request);
            
            // Set cookie for 10 years as long-term backup
            return $response->withCookie(cookie()->forever('visitor_id', $visitorId));
        }

        // Ensure session has it if cookie had it
        if (!session()->has('visitor_id')) {
            session(['visitor_id' => $visitorId]);
        }

        return $next($request);
    }
}
