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
                'name' => 'Apple iPhone 15 Pro Max',
                'sku' => 'PROD-100001',
                'price' => 1199.00,
                'stock' => 15,
                'expiry_date' => now()->addYears(2),
                'category' => 'electronics',
                'description' => 'Flagship smartphone with titanium design and A17 Pro chip',
                'status' => 'active'
            ],
            [
                'name' => 'Sony WH-1000XM5 Wireless Headphones',
                'sku' => 'PROD-100002',
                'price' => 399.99,
                'stock' => 28,
                'expiry_date' => now()->addYears(3),
                'category' => 'electronics',
                'description' => 'Industry-leading noise canceling wireless headphones',
                'status' => 'active'
            ],
            [
                'name' => 'Ultra HD 4K Smart Monitor 27 inch',
                'sku' => 'PROD-100003',
                'price' => 349.50,
                'stock' => 3, // Critical low stock
                'expiry_date' => now()->addYears(3),
                'category' => 'electronics',
                'description' => 'IPS color calibrated 4K productivity display',
                'status' => 'active'
            ],
            [
                'name' => 'Premium Cotton Crewneck T-Shirt',
                'sku' => 'PROD-100004',
                'price' => 24.99,
                'stock' => 150,
                'expiry_date' => now()->addYears(4),
                'category' => 'clothing',
                'description' => '100% organic breathable cotton everyday tee',
                'status' => 'active'
            ],
            [
                'name' => 'Denim Slim-Fit Stretch Jeans',
                'sku' => 'PROD-100005',
                'price' => 69.95,
                'stock' => 85,
                'expiry_date' => now()->addYears(4),
                'category' => 'clothing',
                'description' => 'Comfortable stretch denim classic 5-pocket design',
                'status' => 'active'
            ],
            [
                'name' => 'Winter Thermal Hooded Parka',
                'sku' => 'PROD-100006',
                'price' => 189.00,
                'stock' => 8, // Low stock warning
                'expiry_date' => now()->addYears(3),
                'category' => 'clothing',
                'description' => 'Waterproof insulated extreme cold weather parka',
                'status' => 'inactive'
            ],
            [
                'name' => 'Clean Code: Handbook of Agile Software',
                'sku' => 'PROD-100007',
                'price' => 44.99,
                'stock' => 45,
                'expiry_date' => now()->addYears(5),
                'category' => 'books',
                'description' => 'A handbook of agile software craftsmanship by Robert C. Martin',
                'status' => 'active'
            ],
            [
                'name' => 'Designing Data-Intensive Applications',
                'sku' => 'PROD-100008',
                'price' => 52.00,
                'stock' => 30,
                'expiry_date' => now()->addYears(5),
                'category' => 'books',
                'description' => 'The big ideas behind reliable scalable and maintainable systems',
                'status' => 'active'
            ],
            [
                'name' => 'Ergonomic Memory Foam Lumbar Cushion',
                'sku' => 'PROD-100009',
                'price' => 38.50,
                'stock' => 60,
                'expiry_date' => now()->addYears(3),
                'category' => 'home',
                'description' => 'Orthopedic high-density foam chair backrest',
                'status' => 'active'
            ],
            [
                'name' => 'Aroma Ultrasonic Essential Oil Diffuser',
                'sku' => 'PROD-100010',
                'price' => 29.99,
                'stock' => 4, // Critical stock
                'expiry_date' => now()->addDays(12), // Expiring soon in 12 days!
                'category' => 'home',
                'description' => '500ml ultrasonic cool mist diffuser with ambient LED',
                'status' => 'active'
            ],
            [
                'name' => 'Non-Slip Eco-Friendly Pro Yoga Mat',
                'sku' => 'PROD-100011',
                'price' => 48.00,
                'stock' => 70,
                'expiry_date' => now()->addYears(3),
                'category' => 'sports',
                'description' => '6mm high density alignment line workout fitness mat',
                'status' => 'active'
            ],
            [
                'name' => 'Adjustable Quick-Select Dumbbell 24kg',
                'sku' => 'PROD-100012',
                'price' => 229.00,
                'stock' => 12,
                'expiry_date' => now()->addYears(5),
                'category' => 'sports',
                'description' => 'Space saving dialed weight resistance training dumbbell',
                'status' => 'active'
            ],
            [
                'name' => 'Hyaluronic Acid Hydrating Facial Serum',
                'sku' => 'PROD-100013',
                'price' => 32.50,
                'stock' => 25,
                'expiry_date' => now()->addDays(20), // Expiring in 20 days!
                'category' => 'beauty',
                'description' => 'Deep moisture replenishment intense anti-aging formula',
                'status' => 'active'
            ],
            [
                'name' => 'Organic Vitamin C Glow Day Cream',
                'sku' => 'PROD-100014',
                'price' => 27.90,
                'stock' => 0, // Out of stock
                'expiry_date' => now()->subDays(5), // Already expired!
                'category' => 'beauty',
                'description' => 'Brightening antioxidant SPF 30 daily moisturizer',
                'status' => 'discontinued'
            ]
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(['sku' => $product['sku']], $product);
        }
    }
}