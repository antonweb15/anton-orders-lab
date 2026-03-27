<?php

namespace App\Http\Controllers;

use App\Services\SupplierImportService;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class CatalogController extends Controller
{
    private const CATALOG_CACHE_VERSION_KEY = 'catalog_products_version';

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
        $version = $this->getCatalogCacheVersion();
        $cacheKey = "catalog_products_v{$version}_page_{$page}";

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
            $this->invalidateCatalogCache();
            return back()->with('success', 'Catalog imported successfully!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // Web: Clear catalog table
    public function clear()
    {
        Product::truncate();
        $this->invalidateCatalogCache();
        return back()->with('success', 'Catalog cleared successfully!');
    }

    // API: Import products from supplier
    public function apiImport()
    {
        try {
            $this->importService->importAll();
            $this->invalidateCatalogCache();
            return response()->json(['message' => 'Catalog imported successfully!']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    // API: Clear catalog table
    public function apiClear()
    {
        Product::truncate();
        $this->invalidateCatalogCache();
        return response()->json(['message' => 'Catalog cleared successfully!']);
    }

    private function getCatalogCacheVersion(): int
    {
        return (int) Cache::get(self::CATALOG_CACHE_VERSION_KEY, 1);
    }

    private function invalidateCatalogCache(): void
    {
        Cache::increment(self::CATALOG_CACHE_VERSION_KEY);
    }
}
