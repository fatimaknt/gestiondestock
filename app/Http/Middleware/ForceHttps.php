<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Forcer HTTPS en production ou si FORCE_HTTPS est activé
        if (!$request->secure() && (app()->environment('production') || env('FORCE_HTTPS', false))) {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}
