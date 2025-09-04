<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Parfums',
                'description' => 'Parfums et eaux de toilette',
                'color' => '#8b5cf6',
                'icon' => 'sparkles',
            ],
            [
                'name' => 'Vêtements',
                'description' => 'Vêtements et accessoires vestimentaires',
                'color' => '#06b6d4',
                'icon' => 'tshirt',
            ],
            [
                'name' => 'Accessoires',
                'description' => 'Accessoires de mode et bijoux',
                'color' => '#f59e0b',
                'icon' => 'gift',
            ],
            [
                'name' => 'Cosmétiques',
                'description' => 'Produits de beauté et soins',
                'color' => '#ec4899',
                'icon' => 'heart',
            ],
            [
                'name' => 'Électronique',
                'description' => 'Appareils électroniques et gadgets',
                'color' => '#10b981',
                'icon' => 'device-mobile',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
