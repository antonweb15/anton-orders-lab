<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Product;

class SupplierImportService
{
    protected $supplierUrl;

    public function __construct()
    {
        // URL вашего REST API поставщика
        $this->supplierUrl = 'http://127.0.0.1:8000/api/supplier/products';
    }

    /**
     * Import all products from supplier API
     */
    public function importAll()
    {
        set_time_limit(300); // Set 5 minutes limit

        $page = 1;
        $hasMore = true;

        while ($hasMore) {
            // Internal call to avoid HTTP timeout in single-threaded artisan serve
            $request = Request::create($this->supplierUrl, 'GET', ['page' => $page]);
            $response = app()->handle($request);

            if (!$response->isSuccessful()) {
                throw new \Exception('Failed to fetch supplier catalog at page ' . $page);
            }

            $data = json_decode($response->getContent(), true);
            $products = $data['data'] ?? [];

            foreach ($products as $item) {
                // Using Eloquent ORM to update or create product
                Product::updateOrCreate(
                    ['supplier_id' => $item['id']], // external ID from supplier
                    [
                        'name'  => $item['name'],
                        'price' => $item['price'],
                        'stock' => $item['stock'],
                    ]
                );
            }

            $lastPage = $data['meta']['last_page'] ?? 1;
            if ($page >= $lastPage) {
                $hasMore = false;
            } else {
                $page++;
            }
        }

        return true;
    }
}
