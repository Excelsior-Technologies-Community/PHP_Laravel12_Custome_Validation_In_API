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

    /**
     * Quick Stock Adjustment (+ / -)
     */
    public function quickStock(Request $request, Product $product): JsonResponse
    {
        $request->validate([
            'adjustment' => 'required|integer',
        ]);

        $newStock = $product->stock + $request->adjustment;
        if ($newStock < 0) {
            return response()->json([
                'success' => false,
                'message' => 'Stock cannot be reduced below zero.'
            ], 422);
        }

        $product->stock = $newStock;
        $product->save();

        return response()->json([
            'success' => true,
            'message' => "Stock updated to {$product->stock}.",
            'data' => $product
        ]);
    }

    /**
     * Visual Analytics Data (Charts & LifeCycle Radar)
     */
    public function analytics(): JsonResponse
    {
        // Category Breakdown
        $categories = Product::select('category')
            ->selectRaw('COUNT(*) as count, SUM(stock) as total_stock, SUM(price * stock) as total_value')
            ->groupBy('category')
            ->get();

        // Price Tier Breakdown
        $priceTiers = [
            'Budget (< $50)' => Product::where('price', '<', 50)->count(),
            'Mid-Range ($50 - $200)' => Product::whereBetween('price', [50, 200])->count(),
            'Premium ($200 - $500)' => Product::whereBetween('price', [200, 500])->count(),
            'Luxury (> $500)' => Product::where('price', '>', 500)->count(),
        ];

        // Top Valuable Assets (Price * Stock)
        $topAssets = Product::where('stock', '>', 0)
            ->select('id', 'name', 'sku', 'price', 'stock', 'category')
            ->selectRaw('(price * stock) as inventory_value')
            ->orderByDesc('inventory_value')
            ->take(5)
            ->get();

        // Stock Depletion Alerts (Critical < 5, Low < 20, Healthy >= 20)
        $criticalStock = Product::where('stock', '<', 5)->count();
        $lowStock = Product::whereBetween('stock', [5, 20])->count();
        $healthyStock = Product::where('stock', '>', 20)->count();

        // Expiry Countdown (Expired, Expiring within 30 days, Safe)
        $expiredCount = Product::whereNotNull('expiry_date')
            ->whereDate('expiry_date', '<', now())
            ->count();

        $expiringSoon = Product::whereNotNull('expiry_date')
            ->whereBetween('expiry_date', [now(), now()->addDays(30)])
            ->select('id', 'name', 'sku', 'expiry_date', 'stock')
            ->orderBy('expiry_date')
            ->take(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'categories' => $categories,
                'price_tiers' => $priceTiers,
                'top_assets' => $topAssets,
                'depletion' => [
                    'critical' => $criticalStock,
                    'low' => $lowStock,
                    'healthy' => $healthyStock,
                ],
                'expiry' => [
                    'expired_count' => $expiredCount,
                    'expiring_soon' => $expiringSoon,
                ]
            ]
        ]);
    }
}
