<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class ShopPersonalization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->shop) {
            $shop = Auth::user()->shop;

            // Partager les données de personnalisation avec toutes les vues
            View::share('shopPersonalization', [
                'name' => $shop->name,
                'logo' => $shop->logo,
                'primary_color' => $shop->primary_color ?? '#007bff',
                'secondary_color' => $shop->secondary_color ?? '#6c757d',
                'currency' => $shop->currency ?? 'XOF',
                'timezone' => $shop->timezone ?? 'Africa/Dakar'
            ]);
        }

        return $next($request);
    }
}
