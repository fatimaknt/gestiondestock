<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Enregistrer les permissions Spatie
        $this->registerPermissions();
    }

    /**
     * Enregistrer toutes les permissions
     */
    protected function registerPermissions(): void
    {
        // Attendre que la base de données soit prête
        try {
            $permissions = Permission::all();

            foreach ($permissions as $permission) {
                Gate::define($permission->name, function ($user) use ($permission) {
                    return $user->hasPermissionTo($permission->name);
                });
            }
        } catch (\Exception $e) {
            // La base de données n'est pas encore prête
        }
    }
}
