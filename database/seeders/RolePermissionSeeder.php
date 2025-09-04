<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view_dashboard',
            'manage_products',
            'manage_categories',
            'manage_suppliers',
            'manage_stock',
            'manage_sales',
            'manage_purchases',
            'manage_users',
            'manage_shops',
            'view_reports',
            'export_data',
            'manage_settings',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $vendeurRole = Role::create(['name' => 'vendeur']);
        $vendeurRole->givePermissionTo([
            'view_dashboard',
            'manage_products',
            'manage_categories',
            'manage_suppliers',
            'manage_stock',
            'manage_sales',
            'manage_purchases',
            'view_reports',
            'export_data',
        ]);

        $caissierRole = Role::create(['name' => 'caissier']);
        $caissierRole->givePermissionTo([
            'view_dashboard',
            'manage_sales',
            'view_reports',
        ]);

        $gestionnaireRole = Role::create(['name' => 'gestionnaire']);
        $gestionnaireRole->givePermissionTo([
            'view_dashboard',
            'manage_products',
            'manage_stock',
            'manage_purchases',
            'view_reports',
            'export_data',
        ]);
    }
}
