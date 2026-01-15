<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
    protected $service;

    public function __construct(OrderService $service)
    {
        $this->service = $service;
    }

    // Web page
    public function index()
    {
        $sort = request('sort', 'latest');
        $orders = $this->service->allOrders($sort);
        return view('orders.index', ['orders' => $orders]);
    }

    // API: all orders
    public function apiIndex()
    {
        $sort = request('sort', 'latest'); // get ?sort= from query
        $orders = $this->service->allOrders($sort);
        return OrderResource::collection($orders);
    }

    // API filtered orders
    public function apiFiltered()
    {
        $filters = request()->only(['status', 'user_id', 'from', 'to']);
        $sort = request('sort', 'latest');
        $orders = $this->service->filteredOrders($filters, $sort);
        return OrderResource::collection($orders);
    }

    // Web page for API table view
    public function indexApiPage()
    {
        return view('orders.index-api');
    }
}
