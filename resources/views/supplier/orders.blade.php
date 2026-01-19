<!DOCTYPE html>
<html>
<head>
    <title>Supplier Orders</title>
</head>
<body>

<h1>Supplier Received Orders</h1>

<div style="margin-bottom: 20px;">
    <a href="{{ route('supplier.catalog') }}">Back to Supplier Catalog</a>
</div>

<table border="1" cellpadding="8">
    <thead>
    <tr>
        <th>ID</th>
        <th>Our Order ID</th>
        <th>Customer</th>
        <th>Product</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Status</th>
        <th>Received At</th>
    </tr>
    </thead>
    <tbody>
    @forelse ($orders as $order)
        <tr>
            <td>{{ $order->id }}</td>
            <td>{{ $order->external_id }}</td>
            <td>{{ $order->customer_name }}</td>
            <td>{{ $order->product }}</td>
            <td>{{ $order->quantity }}</td>
            <td>{{ $order->price }}</td>
            <td>{{ $order->status }}</td>
            <td>{{ $order->created_at }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="8">No orders received yet.</td>
        </tr>
    @endforelse
    </tbody>
</table>

<br>

{{ $orders->links() }}

</body>
</html>
