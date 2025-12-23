<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Smartphone X',
                'sku' => 'PROD-100001',
                'price' => 999.99,
                'stock' => 50,
                'expiry_date' => now()->addYears(2),
                'category' => 'electronics',
                'description' => 'Latest smartphone with advanced features'
            ],
            [
                'name' => 'Cotton T-Shirt',
                'sku' => 'PROD-100002',
                'price' => 29.99,
                'stock' => 200,
                'expiry_date' => now()->addYears(3),
                'category' => 'clothing',
                'description' => '100% cotton t-shirt'
            ],
            [
                'name' => 'Programming Book',
                'sku' => 'PROD-100003',
                'price' => 49.99,
                'stock' => 100,
                'expiry_date' => now()->addYears(5),
                'category' => 'books',
                'description' => 'Learn programming with this comprehensive guide'
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}