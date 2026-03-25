<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ApiCachingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that catalog products are cached.
     */
    public function test_catalog_products_are_cached()
    {
        // 1. Create some products
        Product::factory()->count(5)->create();

        // 2. Clear cache to start fresh
        Cache::flush();

        // 3. Make first request - should populate cache
        $response1 = $this->getJson('/api/catalog');
        $response1->assertStatus(200);

        $this->assertTrue(Cache::has('catalog_products_page_1'), 'Cache should have catalog_products_page_1 key');

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
        Cache::remember('catalog_products_page_1', 600, function() {
            return ['cached_data'];
        });

        $this->assertTrue(Cache::has('catalog_products_page_1'));

        // Call apiImport (which calls Cache::flush())
        $this->postJson('/api/catalog/import');

        $this->assertFalse(Cache::has('catalog_products_page_1'), 'Cache should be cleared after import');
    }

    /**
     * Test that catalog cache is invalidated when cleared.
     */
    public function test_catalog_cache_is_invalidated_on_clear()
    {
        Product::factory()->count(3)->create();
        Cache::remember('catalog_products_page_1', 600, function() {
            return ['cached_data'];
        });

        $this->assertTrue(Cache::has('catalog_products_page_1'));

        // Call apiClear
        $this->postJson('/api/catalog/clear');

        $this->assertFalse(Cache::has('catalog_products_page_1'), 'Cache should be cleared after clearing catalog');
    }
}
