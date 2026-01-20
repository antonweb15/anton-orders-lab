@extends('layouts.app')

@section('title', 'Orders')

@section('content')
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <p class="mt-2 text-sm text-gray-700">A list of all orders in the system including their status, product details and price.</p>
        </div>
    </div>

    <div class="mt-6 flex flex-wrap items-center gap-4 text-sm">
        <span class="font-semibold text-gray-900">Sort by:</span>
        <div class="flex gap-2">
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'latest']) }}" class="rounded-full px-3 py-1 {{ request('sort', 'latest') === 'latest' ? 'bg-indigo-100 text-indigo-700 font-medium' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">Latest</a>
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'oldest']) }}" class="rounded-full px-3 py-1 {{ request('sort') === 'oldest' ? 'bg-indigo-100 text-indigo-700 font-medium' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">Oldest</a>
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}" class="rounded-full px-3 py-1 {{ request('sort') === 'price_asc' ? 'bg-indigo-100 text-indigo-700 font-medium' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">Price (Asc)</a>
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}" class="rounded-full px-3 py-1 {{ request('sort') === 'price_desc' ? 'bg-indigo-100 text-indigo-700 font-medium' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">Price (Desc)</a>
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'reverse']) }}" class="rounded-full px-3 py-1 {{ request('sort') === 'reverse' ? 'bg-indigo-100 text-indigo-700 font-medium' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">Reverse</a>
        </div>
    </div>

    <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead>
                    <tr>
                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">ID</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Customer</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Product</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 text-center">Qty</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Price</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Created At</th>
                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                    @foreach($orders as $order)
                        <tr>
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">{{ $order->id }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $order->name }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $order->product ?? '-' }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-center">{{ $order->quantity ?? '-' }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 font-mono">${{ number_format($order->price, 2) }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $order->created_at->format('Y-m-d H:i') }}</td>
                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                <form action="{{ route('orders.export', $order->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                        Export
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-8 border-t border-gray-100 pt-6">
        {{ $orders->appends(request()->query())->links() }}
    </div>

@endsection
