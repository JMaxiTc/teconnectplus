<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddDebugHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        if ($request->ajax()) {
            // Add debug headers for AJAX requests
            $response->header('X-Debug-Request-Type', 'AJAX');
            $response->header('X-Debug-Content-Type', $response->headers->get('Content-Type'));
        }
        
        return $response;
    }
}
