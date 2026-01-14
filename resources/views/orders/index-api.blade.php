@extends('layouts.app')

@section('title', 'Orders API')

@section('content')

    <h2>Orders API</h2>
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
        <tbody id="orders-table">
        <tr>
            <td colspan="6">Loading...</td>
        </tr>
        </tbody>
    </table>

    <script>
        fetch('/api/orders')
            .then(response => response.json())
            .then(res => {
                const tbody = document.getElementById('orders-table');
                tbody.innerHTML = '';

                // Обработка случая, если данные обернуты в { data: [...] }
                const orders = Array.isArray(res) ? res : (res.data || []);

                if (orders.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="6">No orders found</td></tr>';
                    return;
                }

                orders.forEach(order => {
                    const row = document.createElement('tr');

                    row.innerHTML = `
                <td>${order.id ?? '-'}</td>
                <td>${order.name ?? '-'}</td>
                <td>${order.product ?? '-'}</td>
                <td>${order.quantity ?? '-'}</td>
                <td>${order.price ?? '-'}</td>
                <td>${order.created_at ?? '-'}</td>
            `;

                    tbody.appendChild(row);
                });
            })
            .catch(error => {
                console.error('Fetch error:', error);
                document.getElementById('orders-table').innerHTML =
                    '<tr><td colspan="6">Error loading orders</td></tr>';
            });
    </script>

@endsection
