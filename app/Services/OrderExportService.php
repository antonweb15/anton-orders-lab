<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderExportService
{
    protected $supplierUrl;

    public function __construct()
    {
        // URL for supplier REST API to receive orders
        $this->supplierUrl = 'http://127.0.0.1:8000/api/supplier/orders';
    }

    /**
     * Export a single order to supplier
     */
    public function exportOrder(Order $order)
    {
        $payload = [
            'external_id'   => $order->id,
            'customer_name' => $order->name,
            'product'       => $order->product,
            'quantity'      => $order->quantity,
            'price'         => $order->price,
        ];

        // Use internal call to avoid deadlocks in single-threaded artisan serve
        $request = Request::create($this->supplierUrl, 'POST', $payload);
        $request->headers->set('Accept', 'application/json');

        $response = app()->handle($request);

        if (!$response->isSuccessful()) {
            $error = json_decode($response->getContent(), true)['message'] ?? 'Unknown error';
            throw new \Exception('Failed to export order: ' . $error);
        }

        return json_decode($response->getContent(), true);
    }
}
