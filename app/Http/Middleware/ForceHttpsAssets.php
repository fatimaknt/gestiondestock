<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttpsAssets
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Forcer HTTPS sur tous les assets en production
        if (app()->environment('production') && $response instanceof \Illuminate\Http\Response) {
            $content = $response->getContent();

            // Remplacer les URLs HTTP par HTTPS
            $content = str_replace('http://gestiondestock-z434.onrender.com', 'https://gestiondestock-z434.onrender.com', $content);

            $response->setContent($content);
        }

        return $response;
    }
}
