<?php

namespace App\Http\Controllers;

use App\Services\SupplierImportService;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    protected $importService;

    public function __construct(SupplierImportService $importService)
    {
        $this->importService = $importService;
    }

    // API: list catalog products
    public function apiIndex()
    {
        // Using Eloquent ORM to get paginated products
        $products = Product::latest()->paginate(10);
        return response()->json($products);
    }

    // Web: list catalog products
    public function index()
    {
        // Using Eloquent ORM to get paginated products
        $products = Product::latest()->paginate(10);
        return view('catalog.index', compact('products'));
    }

    // Web: Import products from supplier
    public function import()
    {
        try {
            $this->importService->importAll();
            return back()->with('success', 'Catalog imported successfully!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // Web: Clear catalog table
    public function clear()
    {
        Product::truncate();
        return back()->with('success', 'Catalog cleared successfully!');
    }

    // API: Import products from supplier
    public function apiImport()
    {
        try {
            $this->importService->importAll();
            return response()->json(['message' => 'Catalog imported successfully!']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    // API: Clear catalog table
    public function apiClear()
    {
        Product::truncate();
        return response()->json(['message' => 'Catalog cleared successfully!']);
    }
}
