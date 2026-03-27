<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ApiCachingTest extends TestCase
{
    use RefreshDatabase;

    private const CATALOG_CACHE_VERSION_KEY = 'catalog_products_version';

    /**
     * Test that catalog products are cached.
     */
    public function test_catalog_products_are_cached()
    {
        // 1. Create some products
        Product::factory()->count(5)->create();

        // 2. Reset only catalog cache namespace to start fresh
        Cache::forget(self::CATALOG_CACHE_VERSION_KEY);

        // 3. Make first request - should populate cache
        $response1 = $this->getJson('/api/catalog');
        $response1->assertStatus(200);

        $version = (int) Cache::get(self::CATALOG_CACHE_VERSION_KEY, 1);
        $this->assertTrue(Cache::has("catalog_products_v{$version}_page_1"), 'Cache should have versioned catalog key');

        // 4. Modify database directly (bypass cache)
        $newProduct = Product::create([
            'name' => 'Should not see this in cache',
            'price' => 100,
            'description' => 'Test',
            'supplier_id' => 999999, // Unique ID
            'stock' => 10,
        ]);

        // 5. Make second request - should still return cached data (without the new product)
        $response2 = $this->getJson('/api/catalog');
        $response2->assertStatus(200);

        $data2 = $response2->json();
        $productNames = collect($data2['data'])->pluck('name');

        $this->assertNotContains('Should not see this in cache', $productNames);
    }

    /**
     * Test that catalog cache is invalidated when importing.
     */
    public function test_catalog_cache_is_invalidated_on_import()
    {
        Product::factory()->count(3)->create();
        Cache::put(self::CATALOG_CACHE_VERSION_KEY, 1);
        Cache::remember('catalog_products_v1_page_1', 600, function () {
            return ['cached_data'];
        });

        $this->assertTrue(Cache::has('catalog_products_v1_page_1'));

        // Call apiImport (which invalidates catalog cache namespace by incrementing version)
        $this->postJson('/api/catalog/import');

        $this->assertSame(2, (int) Cache::get(self::CATALOG_CACHE_VERSION_KEY));
        $this->assertTrue(Cache::has('catalog_products_v1_page_1'), 'Old entries may exist but must become unreachable by new version');
    }

    /**
     * Test that catalog cache is invalidated when cleared.
     */
    public function test_catalog_cache_is_invalidated_on_clear()
    {
        Product::factory()->count(3)->create();
        Cache::put(self::CATALOG_CACHE_VERSION_KEY, 1);
        Cache::remember('catalog_products_v1_page_1', 600, function () {
            return ['cached_data'];
        });

        $this->assertTrue(Cache::has('catalog_products_v1_page_1'));

        // Call apiClear
        $this->postJson('/api/catalog/clear');

        $this->assertSame(2, (int) Cache::get(self::CATALOG_CACHE_VERSION_KEY));
        $this->assertTrue(Cache::has('catalog_products_v1_page_1'), 'Old entries may exist but must become unreachable by new version');
    }
}
