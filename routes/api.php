<?php

use App\Http\Controllers\API\ProductController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function () {
    // Product CRUD routes
    Route::apiResource('products', ProductController::class);
    
    // Bulk operations
    Route::post('products/bulk', [ProductController::class, 'bulkStore']);
    
    // Custom validation test endpoint
    Route::post('validate-product', function (\Illuminate\Http\Request $request) {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name' => 'required|product_name_format',
            'sku' => ['required', new \App\Rules\ValidSKU],
            'stock' => 'required|integer|stock_availability',
            'category' => ['required', new \App\Rules\ValidCategory],
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Validation failed'
            ], 422);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Validation passed'
        ]);
    });
});