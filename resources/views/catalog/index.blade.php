@extends('layouts.app')

@section('title', 'Product Catalog')

@section('content')
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <p class="mt-2 text-sm text-gray-700">Manage your local product catalog. You can import products from the supplier or clear the current list.</p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none flex gap-3">
            <form action="{{ route('catalog.import') }}" method="POST">
                @csrf
                <button type="submit" class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Import from Supplier
                </button>
            </form>

            <form action="{{ route('catalog.clear') }}" method="POST" onsubmit="return confirm('Are you sure you want to clear the catalog?')">
                @csrf
                <button type="submit" class="block rounded-md bg-red-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                    Clear Catalog
                </button>
            </form>
        </div>
    </div>

    <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead>
                    <tr>
                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Supplier ID</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Product Name</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 text-center">Stock</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Price</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                    @foreach($products as $product)
                        <tr>
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">{{ $product->supplier_id }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $product->name }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-center">{{ $product->stock }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 font-mono">${{ number_format($product->price, 2) }}</td>
                        </tr>
                    @endforeach
                    @if($products->isEmpty())
                        <tr>
                            <td colspan="4" class="py-10 text-center text-sm text-gray-500">
                                Catalog is empty. Click "Import from Supplier" to get products.
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-8 border-t border-gray-100 pt-6">
        {{ $products->links() }}
    </div>
@endsection
