<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SupplierProduct;
use Illuminate\Http\Request;

class SupplierProductController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'latest');

        $products = SupplierProduct::query()
            ->when($sort === 'latest', fn ($q) => $q->orderByDesc('id'))
            ->when($sort === 'oldest', fn ($q) => $q->orderBy('id'))
            ->paginate(10);

        return response()->json([
            'data' => $products->items(),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'total' => $products->total(),
            ],
        ]);
    }
}
