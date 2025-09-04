<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Shop;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $shop = Shop::first();
        if (!$shop) {
            $this->command->error('Aucune boutique trouvée. Exécutez d\'abord AdminUserSeeder.');
            return;
        }

        $categories = Category::all();
        $suppliers = Supplier::all();

        if ($categories->isEmpty()) {
            $this->command->error('Aucune catégorie trouvée. Exécutez d\'abord CategorySeeder.');
            return;
        }

        $products = [
            [
                'name' => 'Parfum Chanel N°5',
                'description' => 'Parfum féminin emblématique aux notes florales et poudrées',
                'sku' => 'CHN-001',
                'barcode' => '1234567890123',
                'category_id' => $categories->where('name', 'Parfums')->first()->id,
                'supplier_id' => $suppliers->first()?->id,
                'purchase_price' => 45.00,
                'selling_price' => 89.99,
                'quantity' => 25,
                'min_quantity' => 5,
                'unit' => 'flacon',
                'shop_id' => $shop->id,
            ],
            [
                'name' => 'T-shirt Basic Blanc',
                'description' => 'T-shirt en coton bio, coupe classique',
                'sku' => 'TSH-001',
                'barcode' => '1234567890124',
                'category_id' => $categories->where('name', 'Vêtements')->first()->id,
                'supplier_id' => $suppliers->first()?->id,
                'purchase_price' => 8.50,
                'selling_price' => 19.99,
                'quantity' => 50,
                'min_quantity' => 10,
                'unit' => 'pièce',
                'shop_id' => $shop->id,
            ],
            [
                'name' => 'Crème Hydratante Visage',
                'description' => 'Crème hydratante 24h pour tous types de peau',
                'sku' => 'CRM-001',
                'barcode' => '1234567890125',
                'category_id' => $categories->where('name', 'Cosmétiques')->first()->id,
                'supplier_id' => $suppliers->first()?->id,
                'purchase_price' => 12.00,
                'selling_price' => 24.99,
                'quantity' => 30,
                'min_quantity' => 8,
                'unit' => 'tube',
                'shop_id' => $shop->id,
            ],
            [
                'name' => 'Smartphone Samsung Galaxy',
                'description' => 'Smartphone Android avec écran 6.1" et appareil photo 48MP',
                'sku' => 'PHN-001',
                'barcode' => '1234567890126',
                'category_id' => $categories->where('name', 'Électronique')->first()->id,
                'supplier_id' => $suppliers->first()?->id,
                'purchase_price' => 299.00,
                'selling_price' => 449.99,
                'quantity' => 8,
                'min_quantity' => 3,
                'unit' => 'appareil',
                'shop_id' => $shop->id,
            ],
            [
                'name' => 'Bracelet en Argent',
                'description' => 'Bracelet élégant en argent 925, design minimaliste',
                'sku' => 'BRC-001',
                'barcode' => '1234567890127',
                'category_id' => $categories->where('name', 'Accessoires')->first()->id,
                'supplier_id' => $suppliers->first()?->id,
                'purchase_price' => 35.00,
                'selling_price' => 69.99,
                'quantity' => 15,
                'min_quantity' => 5,
                'unit' => 'pièce',
                'shop_id' => $shop->id,
            ],
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }

        $this->command->info('Produits de test créés avec succès !');
    }
}
