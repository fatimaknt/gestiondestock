<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttpsRedirects
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Forcer HTTPS sur toutes les redirections en production
        if (app()->environment('production') && $response instanceof \Illuminate\Http\RedirectResponse) {
            $url = $response->getTargetUrl();

            // Remplacer HTTP par HTTPS dans les URLs de redirection
            if (strpos($url, 'http://gestiondestock-z434.onrender.com') === 0) {
                $url = str_replace('http://', 'https://', $url);
                $response->setTargetUrl($url);
            }
        }

        return $response;
    }
}
