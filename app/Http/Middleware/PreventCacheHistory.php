<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventCacheHistory
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // 1. Let the application process the request and get the response
        $response = $next($request);

        // 2. Only apply to standard HTML web pages (skip file downloads, JSON APIs, etc.)
        if ($response instanceof Response && str_contains($response->headers->get('Content-Type'), 'text/html')) {

            // Core headers to prevent caching and stop the Back/Forward history cache (BFCache)
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT'); // Date in the past
        }

        return $response;

    }
}
