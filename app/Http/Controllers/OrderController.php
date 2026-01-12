<?php

namespace App\Http\Controllers;

use App\Services\OrderService;

// for change the response
//use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    protected $service;

    public function __construct(OrderService $service)
    {
        $this->service = $service;
    }

// change the response
    /*
    public function index(): JsonResponse
        {
            $orders = $this->service->allOrders();

            return response()->json([
                'orders' => $orders
            ]);
        }
    */


    // No change the response
    public function index()
    {
        $orders = $this->service->allOrders();
        return view('orders.index', ['orders' => $orders]);
    }

    // print array result
    /*
    public function index()
    {
        $orders = $this->service->allOrders();

        return response()->json([
            'orders' => $orders
        ]);
    } */

    public function apiIndex()
    {
        $orders = $this->service->allOrders();
        return response()->json($orders);
    }

    public function indexApiPage()
    {
        return view('orders.index-api');
    }


}
