<?php

namespace Tests\Feature;

use App\Models\SupplierProduct;
use App\Services\SupplierImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CatalogImportTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function api_import_transfers_supplier_products_into_catalog(): void
    {
        $supplierProduct = SupplierProduct::factory()->create([
            'name' => 'Imported Product',
            'price' => 149.99,
            'stock' => 12,
        ]);

        $response = $this->postJson('/api/catalog/import');

        $response->assertOk()->assertJson([
            'message' => 'Catalog imported successfully!',
        ]);

        $this->assertDatabaseHas('products', [
            'supplier_id' => $supplierProduct->id,
            'name' => 'Imported Product',
            'price' => 149.99,
            'stock' => 12,
        ]);
    }

    #[Test]
    public function api_import_returns_500_when_supplier_import_fails(): void
    {
        $this->mock(SupplierImportService::class, function (MockInterface $mock) {
            $mock->shouldReceive('importAll')
                ->once()
                ->andThrow(new \Exception('Supplier API error'));
        });

        $response = $this->postJson('/api/catalog/import');

        $response->assertStatus(500)->assertJson([
            'message' => 'Supplier API error',
        ]);
    }
}
