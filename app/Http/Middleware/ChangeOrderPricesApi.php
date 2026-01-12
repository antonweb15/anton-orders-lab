<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ChangeOrderPricesApi
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Check JSON response
        if ($response instanceof JsonResponse) {
            $data = $response->getData(true);

            // replacement
            foreach ($data as &$order) {
                if (isset($order['price'])) {
                    $order['price'] = 'replacement using Middleware';
                }
            }

            $response->setData($data);
        }

        return $response;
    }
}
