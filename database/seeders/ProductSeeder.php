<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'Nasi Goreng', 'categories_id' => 1, 'price' => 18000, 'unit' => 'porsi', 'stock' => 50, 'min_stock' => 10],
            ['name' => 'Mie Goreng', 'categories_id' => 1, 'price' => 15000, 'unit' => 'porsi', 'stock' => 45, 'min_stock' => 10],
            ['name' => 'Ayam Geprek', 'categories_id' => 1, 'price' => 20000, 'unit' => 'porsi', 'stock' => 40, 'min_stock' => 10],
            ['name' => 'Es Teh Manis', 'categories_id' => 2, 'price' => 5000, 'unit' => 'gelas', 'stock' => 100, 'min_stock' => 20],
            ['name' => 'Es Jeruk', 'categories_id' => 2, 'price' => 6000, 'unit' => 'gelas', 'stock' => 80, 'min_stock' => 20],
            ['name' => 'Kopi Hitam', 'categories_id' => 2, 'price' => 8000, 'unit' => 'gelas', 'stock' => 60, 'min_stock' => 15],
            ['name' => 'Pisang Goreng', 'categories_id' => 3, 'price' => 10000, 'unit' => 'porsi', 'stock' => 35, 'min_stock' => 10],
            ['name' => 'Tahu Crispy', 'categories_id' => 3, 'price' => 8000, 'unit' => 'porsi', 'stock' => 3, 'min_stock' => 10],
        ];
        foreach ($products as $product) {
            DB::table('products')->updateOrInsert(
                ['name' => $product['name']],
                array_merge($product, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }
}