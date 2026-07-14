<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DisableBrowserAutocomplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Check if the response contains HTML text
        if ($response instanceof \Illuminate\Http\Response || $response->headers->get('Content-Type') === 'text/html') {
            $content = $response->getContent();

            // Inject autocomplete="off" into all <form> tags that don't already have it
            $content = preg_replace('/<form(?![^>]*autocomplete=)/i', '<form autocomplete="off"', $content);

            // OPTIONAL: If you want to force it onto all text inputs specifically instead:
            // $content = preg_replace('/<input\s+(?![^>]*autocomplete=)([^>]*type=["\']text["\'])/i', '<input autocomplete="off" $1', $content);

            $response->setContent($content);
        }

        return $response;
    }
}
