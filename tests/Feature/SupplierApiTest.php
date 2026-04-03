<?php

namespace Tests\Feature;

use App\Models\SupplierProduct;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SupplierApiTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function supplier_orders_endpoint_creates_order_for_valid_payload(): void
    {
        $payload = [
            'external_id' => 42,
            'customer_name' => 'Anton',
            'product' => 'MacBook',
            'quantity' => 2,
            'price' => 1999.99,
        ];

        $response = $this->postJson('/api/supplier/orders', $payload);

        $response->assertStatus(201)->assertJson([
            'message' => 'Order received successfully',
        ]);

        $this->assertDatabaseHas('supplier_orders', [
            'external_id' => 42,
            'customer_name' => 'Anton',
            'status' => 'received',
        ]);
    }

    #[Test]
    public function supplier_orders_endpoint_validates_required_fields(): void
    {
        $response = $this->postJson('/api/supplier/orders', [
            'external_id' => 'wrong',
            'quantity' => 'abc',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors([
            'external_id',
            'customer_name',
            'product',
            'quantity',
            'price',
        ]);
    }

    #[Test]
    public function supplier_products_endpoint_returns_expected_contract(): void
    {
        SupplierProduct::factory()->count(3)->create();

        $response = $this->getJson('/api/supplier/products');

        $response->assertOk()->assertJsonStructure([
            'data' => [
                '*' => ['id', 'external_id', 'name', 'price', 'stock'],
            ],
            'meta' => ['current_page', 'last_page', 'total'],
        ]);
    }

    #[Test]
    public function supplier_products_endpoint_supports_oldest_sort(): void
    {
        $first = SupplierProduct::factory()->create();
        SupplierProduct::factory()->create();

        $response = $this->getJson('/api/supplier/products?sort=oldest');

        $response->assertOk();
        $data = $response->json('data');

        $this->assertSame($first->id, $data[0]['id']);
    }
}
