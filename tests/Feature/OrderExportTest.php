<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Order;
use App\Models\SupplierOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class OrderExportTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_export_order_to_supplier()
    {
        // 1. Create a local order
        $order = Order::factory()->create([
            'name' => 'John Doe',
            'product' => 'Tesla Model 3',
            'quantity' => 1,
            'price' => 45000.00,
        ]);

        // 2. Ensure supplier table is empty
        $this->assertEquals(0, SupplierOrder::count());

        // 3. Call export endpoint
        $response = $this->from('/orders')
            ->post("/orders/{$order->id}/export");

        // 4. Check response
        if ($response->status() === 200) {
            $response->assertJson(['message' => 'Order received successfully']);
        } else {
            $response->assertRedirect('/orders');
            $response->assertSessionHas('success');
        }

        // 5. Verify order appeared in supplier table
        $this->assertEquals(1, SupplierOrder::count());
        $supplierOrder = SupplierOrder::first();

        $this->assertEquals($order->id, $supplierOrder->external_id);
        $this->assertEquals('John Doe', $supplierOrder->customer_name);
        $this->assertEquals('Tesla Model 3', $supplierOrder->product);
        $this->assertEquals(45000.00, $supplierOrder->price);
        $this->assertEquals('received', $supplierOrder->status);
    }

    #[Test]
    public function it_returns_json_response_for_api_export()
    {
        $order = Order::factory()->create();

        $response = $this->postJson("/orders/{$order->id}/export");

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Order received successfully',
        ]);

        $this->assertEquals(1, SupplierOrder::count());
    }
}
