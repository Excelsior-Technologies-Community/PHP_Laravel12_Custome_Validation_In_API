<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Rules\ValidSKU;
use App\Rules\ValidCategory;
use App\Rules\ValidProductStatus;

class ProductController extends Controller
{
    /**
     * Display Products
     */
    public function index(Request $request): JsonResponse
    {
        $query = Product::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Category Filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Price Filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Stock Filter
        if ($request->filled('stock')) {
            if ($request->stock == 'available') {
                $query->where('stock', '>', 0);
            }

            if ($request->stock == 'out_of_stock') {
                $query->where('stock', 0);
            }
        }

        // Sorting
        $allowedSorts = [
            'id',
            'name',
            'price',
            'stock',
            'category',
            'created_at'
        ];

        $sortBy = $request->get('sort_by', 'created_at');

        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'created_at';
        }

        $sortOrder = strtolower($request->get('sort_order', 'desc'));

        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }

        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 3);

        $products = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Products retrieved successfully.',
            'data' => $products
        ]);
    }

    /**
     * Store Product
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = Product::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully.',
            'data' => $product
        ], 201);
    }

    /**
     * Show Product
     */
    public function show(Product $product): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Product details',
            'data' => $product
        ]);
    }

    /**
     * Update Product
     */
    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $product->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully.',
            'data' => $product->fresh()
        ]);
    }

    /**
     * Delete Product
     */
    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully.'
        ]);
    }

    /**
     * Bulk Create
     */
    public function bulkStore(Request $request): JsonResponse
    {
        $request->validate([
            'products' => 'required|array|min:1|max:10',
            'products.*.name' => 'required|string|max:255',
            'products.*.sku' => [
                'required',
                'unique:products,sku',
                new ValidSKU
            ],
            'products.*.price' => 'required|numeric|min:0.01',
            'products.*.stock' => 'required|integer|min:0',
            'products.*.category' => [
                'required',
                new ValidCategory
            ],

            'products.*.status' => [
                'required',
                new ValidProductStatus
            ],
        ]);

        $createdProducts = [];

        foreach ($request->products as $product) {
            $createdProducts[] = Product::create($product);
        }

        return response()->json([
            'success' => true,
            'message' => 'Products created successfully.',
            'data' => $createdProducts
        ], 201);
    }

    /**
     * Dashboard Statistics API
     */
    public function statistics()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'total_products' => Product::count(),
                'available_products' => Product::where('stock', '>', 0)->count(),
                'out_of_stock' => Product::where('stock', 0)->count(),
                'total_stock' => Product::sum('stock'),
                'categories' => Product::distinct('category')->count('category'),
                'average_price' => Product::avg('price'),
            ]
        ]);
    }
    /**
     * Low Stock Products
     */
    public function lowStock(): JsonResponse
    {
        $products = Product::where('stock', '<', 20)
            ->orderBy('stock')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Low stock products.',
            'data' => $products
        ]);
    }

    /**
     * Expired Products
     */
    public function expiredProducts(): JsonResponse
    {
        $products = Product::whereDate('expiry_date', '<', now())
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Expired products.',
            'data' => $products
        ]);
    }

    /**
     * Category Wise Count
     */
    public function categoryCount(): JsonResponse
    {
        $categories = Product::select('category')
            ->selectRaw('COUNT(*) as total_products')
            ->groupBy('category')
            ->orderBy('category')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Category wise product count.',
            'data' => $categories
        ]);
    }

    /**
     * Product Status Count
     */
    public function statusCount(): JsonResponse
    {
        $status = Product::select('status')
            ->selectRaw('COUNT(*) as total_products')
            ->groupBy('status')
            ->orderBy('status')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Product status count.',
            'data' => $status
        ]);
    }
}
