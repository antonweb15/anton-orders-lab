@extends('layouts.app')

@section('title', 'Orders API')

@section('content')

    <h2>Orders API</h2>

    <div style="margin-bottom: 20px;">
        <strong>Sort by:</strong>
        <button onclick="updateSort('latest')">Latest</button>
        <button onclick="updateSort('oldest')">Oldest</button>
        <button onclick="updateSort('price_asc')">Price (Asc)</button>
        <button onclick="updateSort('price_desc')">Price (Desc)</button>
        <button onclick="updateSort('reverse')">Reverse (ID desc)</button>
    </div>

    <div style="margin-bottom: 20px;">
        <strong>Filter by Status:</strong>
        <button onclick="updateFilter('status', '')">All</button>
        <button onclick="updateFilter('status', 'paid')">Paid</button>
        <button onclick="updateFilter('status', 'pending')">Pending</button>
    </div>

    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Created</th>
        </tr>
        </thead>
        <tbody id="orders-table">
        <tr>
            <td colspan="6">Loading...</td>
        </tr>
        </tbody>
    </table>

    <div id="pagination" style="margin-top: 20px;"></div>

    <script>
        function fetchOrders() {
            const urlParams = new URLSearchParams(window.location.search);
            // If filters are present, use /search endpoint
            const isSearch = urlParams.has('status') || urlParams.has('user_id') || urlParams.has('from') || urlParams.has('to');
            const baseUrl = isSearch ? '/api/orders/search' : '/api/orders';

            const fetchUrl = baseUrl + '?' + urlParams.toString();

            document.getElementById('orders-table').innerHTML = '<tr><td colspan="6">Loading...</td></tr>';

            fetch(fetchUrl)
                .then(response => response.json())
                .then(res => {
                    const tbody = document.getElementById('orders-table');
                    tbody.innerHTML = '';

                    const orders = Array.isArray(res) ? res : (res.data || []);
                    const meta = res.meta || {};
                    const links = res.links || {};

                    if (orders.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="6">No orders found</td></tr>';
                        renderPagination(null);
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

                    renderPagination(res);
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    document.getElementById('orders-table').innerHTML =
                        '<tr><td colspan="6">Error loading orders</td></tr>';
                });
        }

        function renderPagination(res) {
            const nav = document.getElementById('pagination');
            nav.innerHTML = '';

            if (!res || !res.meta || !res.links) return;

            const meta = res.meta;
            const links = res.links;

            if (meta.last_page <= 1) return;

            meta.links.forEach(link => {
                if (link.url) {
                    const btn = document.createElement('button');
                    btn.innerHTML = link.label;
                    btn.disabled = link.active;
                    btn.style.marginRight = '5px';
                    btn.onclick = () => {
                        const url = new URL(link.url);
                        const page = url.searchParams.get('page');
                        updateParam('page', page);
                    };
                    nav.appendChild(btn);
                } else {
                    const span = document.createElement('span');
                    span.innerHTML = link.label;
                    span.style.marginRight = '5px';
                    span.style.color = '#ccc';
                    nav.appendChild(span);
                }
            });
        }

        function updateSort(sort) {
            updateParam('sort', sort);
        }

        function updateFilter(key, value) {
            const urlParams = new URLSearchParams(window.location.search);
            if (value) {
                urlParams.set(key, value);
            } else {
                urlParams.delete(key);
            }
            urlParams.delete('page'); // reset page on filter change
            window.history.pushState({}, '', window.location.pathname + '?' + urlParams.toString());
            fetchOrders();
        }

        function updateParam(key, value) {
            const urlParams = new URLSearchParams(window.location.search);
            if (value) {
                urlParams.set(key, value);
            } else {
                urlParams.delete(key);
            }
            window.history.pushState({}, '', window.location.pathname + '?' + urlParams.toString());
            fetchOrders();
        }

        // Initial fetch
        fetchOrders();

        // Handle back/forward buttons
        window.addEventListener('popstate', fetchOrders);
    </script>

@endsection
