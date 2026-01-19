<?php

namespace App\Http\Controllers;

use App\Models\SupplierProduct;

class SupplierCatalogController extends Controller
{
    /**
     * Display supplier catalog page
     */
    public function index()
    {
        $products = SupplierProduct::query()
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('supplier.catalog', [
            'products' => $products,
        ]);
    }
}
