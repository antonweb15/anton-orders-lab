@extends('layouts.app')

@section('title', 'Orders')

@section('content')

    <h2>Orders</h2>

    <div style="margin-bottom: 20px;">
        <strong>Sort by:</strong>
        <a href="{{ request()->fullUrlWithQuery(['sort' => 'latest']) }}">Latest</a> |
        <a href="{{ request()->fullUrlWithQuery(['sort' => 'oldest']) }}">Oldest</a> |
        <a href="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}">Price (Asc)</a> |
        <a href="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}">Price (Desc)</a> |
        <a href="{{ request()->fullUrlWithQuery(['sort' => 'reverse']) }}">Reverse (ID desc)</a>
    </div>

    @if(session('success'))
        <div style="color: green; margin-bottom: 20px;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div style="color: red; margin-bottom: 20px;">{{ session('error') }}</div>
    @endif

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Created</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->name }}</td>
                <td>{{ $order->product ?? '-' }}</td>
                <td>{{ $order->quantity ?? '-' }}</td>
                <td>{{ $order->price ?? '-' }}</td>
                <td>{{ $order->created_at }}</td>
                <td>
                    <form action="{{ route('orders.export', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit">Export</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        {{ $orders->appends(request()->query())->links() }}
    </div>

@endsection
