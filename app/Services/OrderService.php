<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Cache;

class OrderService
{
    /**
     * Get all orders with optional sorting.
     * $sort can be: 'latest', 'oldest', 'price_asc', 'price_desc', etc.
     */
    public function allOrders(?string $sort = 'latest')
    {
        $page = request('page', 1);
        $cacheKey = "orders_all_{$sort}_page_{$page}";

        // Cache for 10 minutes (600 seconds)
        // Switch to Redis by updating CACHE_STORE=redis in .env
        return Cache::remember($cacheKey, 600, function () use ($sort) {
            // Using Eloquent ORM to initialize query
            $query = Order::query();

            // Special case: latest sort only select limited fields
            if ($sort === 'latest') {
                $query->latestFirst(true);
            } elseif ($sort === 'oldest') {
                $query->oldestFirst();
            } elseif ($sort === 'price_asc') {
                $query->sortBy('price', 'asc');
            } elseif ($sort === 'price_desc') {
                $query->sortBy('price', 'desc');
            } elseif ($sort === 'reverse') {
                $query->idDesc();
            } else {
                $query->latestFirst(); // default
            }

            // Using Eloquent ORM to return paginated results
            return $query->paginate(10);
        });
    }

    /**
     * Filtered orders for API
     * $filters = ['status' => 'paid', 'user_id' => 1, 'from' => '2026-01-01', 'to' => '2026-01-15']
     */
    public function filteredOrders(array $filters, string $sort = 'latest')
    {
        // For filtered orders, caching is trickier because of many filter combinations.
        // We can use a hash of filters to create a unique key.
        $filterHash = md5(serialize($filters) . $sort . request('page', 1));
        $cacheKey = "orders_filtered_{$filterHash}";

        return Cache::remember($cacheKey, 600, function () use ($filters, $sort) {
            // Using Eloquent ORM to filter orders
            $query = Order::query()
                ->filter($filters);

            // Apply sorting after filtering
            if ($sort === 'latest') {
                $query->latestFirst();
            } elseif ($sort === 'oldest') {
                $query->oldestFirst();
            } elseif ($sort === 'price_asc') {
                $query->sortBy('price', 'asc');
            } elseif ($sort === 'price_desc') {
                $query->sortBy('price', 'desc');
            } elseif ($sort === 'reverse') {
                $query->idDesc();
            } else {
                $query->latestFirst();
            }

            // Using Eloquent ORM to return paginated results
            return $query->paginate(10);
        });
    }
}
