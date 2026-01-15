<?php

namespace App\Services;

use App\Models\Order;

class OrderService
{
    /**
     * Get all orders with optional sorting.
     * $sort can be: 'latest', 'oldest', 'price_asc', 'price_desc', etc.
     */
    public function allOrders(?string $sort = 'latest')
    {
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

        return $query->paginate(10);
    }

    /**
     * Filtered orders for API
     * $filters = ['status' => 'paid', 'user_id' => 1, 'from' => '2026-01-01', 'to' => '2026-01-15']
     */
    public function filteredOrders(array $filters, string $sort = 'latest')
    {
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

        return $query->paginate(10);
    }
}
