<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripeController extends Controller
{
    public function pay()
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Demo Payment',
                    ],
                    'unit_amount' => 1000, // 10 USD
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => url('/success'),
            'cancel_url' => url('/cancel'),
        ]);

        return redirect($session->url);
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $event = json_decode($payload);

        if (! $event) {
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            DB::table('payments')->updateOrInsert(
                ['stripe_id' => $session->id],
                [
                    'amount' => $session->amount_total / 100,
                    'currency' => strtoupper($session->currency),
                    'status' => 'success',
                    'payload' => json_encode($event),
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            logger()->info('Payment success', [
                'session' => $session->id,
            ]);
        }

        return response()->json(['status' => 'ok']);
    }
}
