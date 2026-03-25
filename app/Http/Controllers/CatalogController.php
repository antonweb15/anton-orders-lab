<?php

namespace App\Http\Controllers;

use App\Services\SupplierImportService;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CatalogController extends Controller
{
    protected $importService;

    public function __construct(SupplierImportService $importService)
    {
        $this->importService = $importService;
    }

    // API: list catalog products
    public function apiIndex()
    {
        // Get current page to use in cache key
        $page = request('page', 1);
        $cacheKey = "catalog_products_page_{$page}";

        // Cache for 600 seconds (10 minutes)
        // To use Redis: change CACHE_STORE=redis in .env or config/cache.php
        $products = Cache::remember($cacheKey, 600, function () {
            // Using Eloquent ORM to get paginated products
            return Product::latest()->paginate(10);
        });

        return response()->json($products);
    }

    // Web: list catalog products
    public function index()
    {
        // Using Eloquent ORM to get paginated products
        $products = Product::latest()->paginate(10);
        return view('catalog.index', compact('products'));
    }

    // Web: Import products from supplier
    public function import()
    {
        try {
            $this->importService->importAll();
            // Invalidate catalog cache after import
            Cache::flush();
            return back()->with('success', 'Catalog imported successfully!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // Web: Clear catalog table
    public function clear()
    {
        Product::truncate();
        // Invalidate catalog cache after clear
        Cache::flush();
        return back()->with('success', 'Catalog cleared successfully!');
    }

    // API: Import products from supplier
    public function apiImport()
    {
        try {
            $this->importService->importAll();
            // Invalidate catalog cache after import
            // To clear specific keys: Cache::forget('catalog_products_page_1')
            Cache::flush();
            return response()->json(['message' => 'Catalog imported successfully!']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    // API: Clear catalog table
    public function apiClear()
    {
        Product::truncate();
        // Invalidate catalog cache after clear
        Cache::flush();
        return response()->json(['message' => 'Catalog cleared successfully!']);
    }
}
