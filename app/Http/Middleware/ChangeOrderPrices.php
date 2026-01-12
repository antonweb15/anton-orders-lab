<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ChangeOrderPrices
{
    public function handle(Request $request, Closure $next)
    {
        // get response
        $response = $next($request);

        // check that is HTML response
        if ($response instanceof Response && strpos($response->headers->get('Content-Type'), 'text/html') !== false) {
            // get HTML
            $content = $response->getContent();

            // replacement using middleware
            $content = str_replace('Price', 'Replacement using middlewares', $content);

            // We put it back in response
            $response->setContent($content);
        }

        return $response;
    }
}
