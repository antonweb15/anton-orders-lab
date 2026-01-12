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
            <td colspan="5">Loading...</td>
        </tr>
        </tbody>
    </table>

    <script>
        fetch('/api/orders')
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('orders-table');
                tbody.innerHTML = '';

                data.forEach(order => {
                    const row = document.createElement('tr');

                    row.innerHTML = `
                <td>${order.id ?? '-'}</td>
                <td>${order.name ?? '-'}</td>
                <td>${order.product ?? '-'}</td>
                <td>${order.quantity ?? '-'}</td>
                <td>${order.price ?? '-'}</td>
            `;

                    tbody.appendChild(row);
                });
            })
            .catch(error => {
                document.getElementById('orders-table').innerHTML =
                    '<tr><td colspan="5">Error loading orders</td></tr>';
            });
    </script>

@endsection
