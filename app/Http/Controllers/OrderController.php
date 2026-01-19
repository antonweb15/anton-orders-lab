<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use App\Services\OrderExportService;
use App\Http\Resources\OrderResource;
use App\Models\Order;

class OrderController extends Controller
{
    protected $service;
    protected $exportService;

    public function __construct(OrderService $service, OrderExportService $exportService)
    {
        $this->service = $service;
        $this->exportService = $exportService;
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

    /**
     * Export order to supplier (REST API)
     */
    public function export(Order $order)
    {
        try {
            $result = $this->exportService->exportOrder($order);

            if (request()->expectsJson()) {
                return response()->json($result);
            }

            return back()->with('success', "Order #{$order->id} exported successfully!");
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json(['message' => $e->getMessage()], 500);
            }
            return back()->with('error', $e->getMessage());
        }
    }
}
