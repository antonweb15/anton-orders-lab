<?php

namespace App\Http\Controllers;

use App\Models\SupplierOrder;
use Illuminate\Http\Request;

class SupplierOrderController extends Controller
{
    /**
     * Display received orders for supplier (Web)
     */
    public function index()
    {
        // Using Eloquent ORM to get paginated supplier orders
        $orders = SupplierOrder::latest()->paginate(10);
        return view('supplier.orders', compact('orders'));
    }

    /**
     * Receive order via API (REST API)
     */
    public function store(Request $request)
    {
        // Validate incoming order data from external system
        $request->validate([
            'external_id'   => 'required|integer',
            'customer_name' => 'required|string',
            'product'       => 'required|string',
            'quantity'      => 'required|integer',
            'price'         => 'required|numeric',
        ]);

        // Using Eloquent ORM to create a new supplier order
        $order = SupplierOrder::create([
            'external_id'   => $request->external_id,
            'customer_name' => $request->customer_name,
            'product'       => $request->product,
            'quantity'      => $request->quantity,
            'price'         => $request->price,
            'status'        => 'received',
        ]);

        return response()->json([
            'message' => 'Order received successfully',
            'order'   => $order
        ], 201);
    }
}
