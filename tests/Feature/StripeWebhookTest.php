<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class StripeWebhookTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function webhook_returns_400_for_invalid_json_payload(): void
    {
        $response = $this->post('/api/stripe/webhook', [], ['CONTENT_TYPE' => 'application/json']);

        $response->assertStatus(400)->assertJson([
            'error' => 'Invalid payload',
        ]);
    }

    #[Test]
    public function webhook_saves_payment_for_checkout_session_completed(): void
    {
        $payload = [
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'id' => 'cs_test_123',
                    'amount_total' => 2599,
                    'currency' => 'usd',
                ],
            ],
        ];

        $response = $this->postJson('/api/stripe/webhook', $payload);

        $response->assertOk()->assertJson(['status' => 'ok']);

        $this->assertDatabaseHas('payments', [
            'stripe_id' => 'cs_test_123',
            'amount' => 25.99,
            'currency' => 'USD',
            'status' => 'success',
        ]);
    }

    #[Test]
    public function webhook_is_idempotent_for_duplicate_events(): void
    {
        $payload = [
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'id' => 'cs_test_duplicate',
                    'amount_total' => 1000,
                    'currency' => 'usd',
                ],
            ],
        ];

        $this->postJson('/api/stripe/webhook', $payload)->assertOk();
        $this->postJson('/api/stripe/webhook', $payload)->assertOk();

        $this->assertSame(
            1,
            DB::table('payments')->where('stripe_id', 'cs_test_duplicate')->count()
        );
    }

    #[Test]
    public function webhook_ignores_unhandled_event_types(): void
    {
        $payload = [
            'type' => 'payment_intent.created',
            'data' => [
                'object' => [
                    'id' => 'pi_test_123',
                ],
            ],
        ];

        $response = $this->postJson('/api/stripe/webhook', $payload);

        $response->assertOk()->assertJson(['status' => 'ok']);
        $this->assertSame(0, DB::table('payments')->count());
    }
}
