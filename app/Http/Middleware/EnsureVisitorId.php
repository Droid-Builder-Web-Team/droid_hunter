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
        $visitorId = $request->cookie('visitor_id');

        if (!$visitorId) {
            $visitorId = (string) \Illuminate\Support\Str::uuid();
            $response = $next($request);
            // Set cookie for 10 years (5,256,000 minutes)
            return $response->withCookie(cookie()->forever('visitor_id', $visitorId));
        }

        return $next($request);
    }
}
