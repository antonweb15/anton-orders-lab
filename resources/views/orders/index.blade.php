@extends('layouts.app')

@section('title', 'Orders')

@section('content')

    <h2>Orders</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Сreated</th>
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
            </tr>
            @endforeach
        </tbody>
    </table>

@endsection
