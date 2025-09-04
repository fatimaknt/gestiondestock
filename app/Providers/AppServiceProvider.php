<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use App\Models\Sale;
use App\Observers\SaleObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Forcer la langue française
        App::setLocale('fr');
        Config::set('app.locale', 'fr');

        // Enregistrer l'observateur pour les ventes
        Sale::observe(SaleObserver::class);
    }
}
