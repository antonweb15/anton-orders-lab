<?php
namespace App\Services;

use App\Models\Order;

class OrderService {
    public function allOrders() {

        return Order::latestFirst()->get();


    }
}
