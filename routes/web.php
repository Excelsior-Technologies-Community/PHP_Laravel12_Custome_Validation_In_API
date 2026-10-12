<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/labels', function () {
    $products = \App\Models\Product::latest()->get();
    return view('products.labels', compact('products'));
})->name('products.labels');

Route::get('/seed-products', function () {
    $seeder = new \Database\Seeders\ProductSeeder();
    $seeder->run();
    return redirect('/')->with('success', 'Sample products seeded successfully!');
})->name('products.seed');

Route::get('/products/create', function () {
    return view('products.create');
})->name('products.create');

// Optional: Add more web routes if needed
Route::get('/api-test', function () {
    return view('api-test');
});