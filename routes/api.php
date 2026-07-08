<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\ProductController;
use App\Rules\ValidSKU;
use App\Rules\ValidCategory;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Custom Product Routes (MUST COME FIRST)

Route::get('products/statistics', [ProductController::class, 'statistics']);

Route::get('products/low-stock', [ProductController::class, 'lowStock']);

Route::get('products/expired', [ProductController::class, 'expiredProducts']);

Route::get('products/category-count', [ProductController::class, 'categoryCount']);

Route::post('products/bulk', [ProductController::class, 'bulkStore']);

// Product CRUD (MUST COME LAST)

Route::apiResource('products', ProductController::class);

// Validation Test

Route::post('/validate-product', function (Request $request) {

    $validator = Validator::make($request->all(), [

        'name' => 'required|product_name_format',

        'sku' => [
            'required',
            new ValidSKU
        ],

        'stock' => 'required|integer|stock_availability',

        'category' => [
            'required',
            new ValidCategory
        ],

    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed.',
            'errors' => $validator->errors()
        ], 422);
    }

    return response()->json([
        'success' => true,
        'message' => 'Validation passed successfully.'
    ]);

});