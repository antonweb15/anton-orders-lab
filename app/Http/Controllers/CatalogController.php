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

    // Показываем нашу таблицу каталога
    public function index()
    {
        // Using Eloquent ORM to get paginated products
        $products = Product::latest()->paginate(10); // все импортированные товары
        return view('catalog.index', compact('products'));
    }

    // Импортируем товары от поставщика
    public function import()
    {
        // Using Eloquent ORM via service import
        $this->importService->importAll(); // метод в сервисе, который делает REST-запрос и сохраняет
        return redirect()->route('catalog.index')->with('success', 'Catalog imported successfully!');
    }

    // Очищаем таблицу товаров
    public function clear()
    {
        // Using Eloquent ORM to truncate table
        Product::truncate();
        return redirect()->route('catalog.index')->with('success', 'Catalog cleared successfully!');
    }
}
