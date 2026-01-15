<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class OrderApiTest extends TestCase
{
    use RefreshDatabase; // Reset DB after each test

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test user
        $this->user = User::factory()->create();

        // Create test orders
        Order::factory()->create([
            'name' => 'Order A',
            'price' => 100,
            'user_id' => $this->user->id,
            'status' => 'paid',
            'created_at' => now()->subDays(3),
        ]);

        Order::factory()->create([
            'name' => 'Order B',
            'price' => 50,
            'user_id' => $this->user->id,
            'status' => 'pending',
            'created_at' => now()->subDays(2),
        ]);

        Order::factory()->create([
            'name' => 'Order C',
            'price' => 200,
            'user_id' => $this->user->id,
            'status' => 'paid',
            'created_at' => now()->subDay(),
        ]);
    }

    /** @test */
    #[Test]
    public function it_returns_orders_sorted_latest_first_by_default()
    {
        $response = $this->getJson('/api/orders');

        $response->assertStatus(200);

        $data = $response->json('data');

        $this->assertEquals('Order C', $data[0]['name']); // newest first
        $this->assertEquals('Order B', $data[1]['name']);
        $this->assertEquals('Order A', $data[2]['name']);
    }

    /** @test */
    #[Test]
    public function it_can_sort_orders_oldest_first()
    {
        $response = $this->getJson('/api/orders?sort=oldest');

        $data = $response->json('data');

        $this->assertEquals('Order A', $data[0]['name']); // oldest first
        $this->assertEquals('Order B', $data[1]['name']);
        $this->assertEquals('Order C', $data[2]['name']);
    }

    /** @test */
    #[Test]
    public function it_can_sort_by_price_asc_and_desc()
    {
        $asc = $this->getJson('/api/orders?sort=price_asc')->json('data');
        $desc = $this->getJson('/api/orders?sort=price_desc')->json('data');

        $this->assertEquals([50, 100, 200], array_column($asc, 'price'));
        $this->assertEquals([200, 100, 50], array_column($desc, 'price'));
    }

    /** @test */
    #[Test]
    public function it_can_sort_by_id_reverse()
    {
        $response = $this->getJson('/api/orders?sort=reverse');

        $data = $response->json('data');

        // Order C was created last, but let's assume they have IDs 1, 2, 3 in order of creation
        // We need to check if IDs are descending
        $ids = array_column($data, 'id');
        $sortedIds = $ids;
        rsort($sortedIds);

        $this->assertEquals($sortedIds, $ids);
    }

    /** @test */
    #[Test]
    public function it_can_filter_by_status()
    {
        $response = $this->getJson('/api/orders/filtered?status=paid');

        $data = $response->json('data');

        $this->assertCount(2, $data); // paid = 2
        foreach ($data as $order) {
            $this->assertEquals('paid', $order['status']);
        }
    }

    /** @test */
    #[Test]
    public function it_can_filter_by_user_and_date_range()
    {
        $from = now()->subDays(2)->format('Y-m-d');
        $to = now()->format('Y-m-d');

        $response = $this->getJson("/api/orders/filtered?user_id={$this->user->id}&from={$from}&to={$to}&sort=oldest");

        $data = $response->json('data');

        $this->assertCount(2, $data); // Order B + Order C
        $this->assertEquals('Order B', $data[0]['name']);
        $this->assertEquals('Order C', $data[1]['name']);
    }
}
