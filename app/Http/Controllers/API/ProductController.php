<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Product::query();

        // Search functionality
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Sort functionality
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 10);
        $products = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $products,
            'message' => 'Products retrieved successfully.'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = Product::create($request->validated());

        return response()->json([
            'success' => true,
            'data' => $product,
            'message' => 'Product created successfully.'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $product,
            'message' => 'Product retrieved successfully.'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $product->update($request->validated());

        return response()->json([
            'success' => true,
            'data' => $product->fresh(),
            'message' => 'Product updated successfully.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully.'
        ], 200);
    }

    /**
     * Bulk product creation with custom validation
     */
    public function bulkStore(Request $request): JsonResponse
    {
        $request->validate([
            'products' => 'required|array|min:1|max:10',
            'products.*.name' => 'required|string|max:255',
            'products.*.sku' => ['required', 'unique:products,sku', new \App\Rules\ValidSKU],
            'products.*.price' => 'required|numeric|min:0.01',
            'products.*.category' => ['required', new \App\Rules\ValidCategory],
        ], [
            'products.*.sku.unique' => 'The SKU :input already exists.',
            'products.*.price.min' => 'Price for product :position must be at least 0.01',
        ]);

        $createdProducts = [];
        foreach ($request->products as $productData) {
            $createdProducts[] = Product::create($productData);
        }

        return response()->json([
            'success' => true,
            'data' => $createdProducts,
            'message' => 'Products created successfully.'
        ], 201);
    }
}