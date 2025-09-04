<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Shop;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $shop = Shop::create([
            'name' => 'Boutique Aurya',
            'description' => 'Boutique de mode et accessoires',
            'primary_color' => '#3b82f6',
            'secondary_color' => '#1f2937',
            'is_active' => true,
        ]);

        $admin = User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@aurya.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'shop_id' => $shop->id,
            'is_active' => true,
        ]);

        $admin->assignRole('admin');
    }
}
