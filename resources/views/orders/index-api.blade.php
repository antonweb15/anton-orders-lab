@extends('layouts.app')

@section('title', 'Orders API (Dynamic)')

@section('content')
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <p class="mt-2 text-sm text-gray-700">This table is powered by native JavaScript and fetches data from the REST API asynchronously.</p>
        </div>
    </div>

    <div class="mt-6 space-y-4">
        <div class="flex flex-wrap items-center gap-4 text-sm">
            <span class="font-semibold text-gray-900">Sort:</span>
            <div class="flex gap-2" id="sort-buttons">
                <button onclick="updateSort('latest')" class="rounded-full px-3 py-1 bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors">Latest</button>
                <button onclick="updateSort('oldest')" class="rounded-full px-3 py-1 bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors">Oldest</button>
                <button onclick="updateSort('price_asc')" class="rounded-full px-3 py-1 bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors">Price (Asc)</button>
                <button onclick="updateSort('price_desc')" class="rounded-full px-3 py-1 bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors">Price (Desc)</button>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-4 text-sm">
            <span class="font-semibold text-gray-900">Filter:</span>
            <div class="flex gap-2">
                <button onclick="updateFilter('status', '')" class="rounded-md border border-gray-300 bg-white px-3 py-1 text-xs font-medium text-gray-700 hover:bg-gray-50">All Statuses</button>
                <button onclick="updateFilter('status', 'paid')" class="rounded-md border border-gray-300 bg-white px-3 py-1 text-xs font-medium text-gray-700 hover:bg-gray-50">Paid</button>
                <button onclick="updateFilter('status', 'pending')" class="rounded-md border border-gray-300 bg-white px-3 py-1 text-xs font-medium text-gray-700 hover:bg-gray-50">Pending</button>
            </div>
        </div>
    </div>

    <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead>
                    <tr>
                        <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">ID</th>
                        <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Customer</th>
                        <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Product</th>
                        <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 text-center">Qty</th>
                        <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Price</th>
                        <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Created At</th>
                        <th class="relative py-3.5 pl-3 pr-4 sm:pr-0"></th>
                    </tr>
                    </thead>
                    <tbody id="orders-table" class="divide-y divide-gray-200">
                    <tr>
                        <td colspan="7" class="py-10 text-center text-sm text-gray-500 italic">
                            Initializing...
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="pagination" class="mt-8 border-t border-gray-100 pt-6 flex justify-center gap-1"></div>

    <script>
        function fetchOrders() {
            const urlParams = new URLSearchParams(window.location.search);
            const isSearch = urlParams.has('status') || urlParams.has('user_id') || urlParams.has('from') || urlParams.has('to');
            const baseUrl = isSearch ? '/api/orders/search' : '/api/orders';
            const fetchUrl = baseUrl + '?' + urlParams.toString();

            const tbody = document.getElementById('orders-table');
            tbody.innerHTML = '<tr><td colspan="7" class="py-10 text-center text-sm text-gray-500 italic">Loading data from API...</td></tr>';

            fetch(fetchUrl)
                .then(response => response.json())
                .then(res => {
                    tbody.innerHTML = '';
                    const orders = Array.isArray(res) ? res : (res.data || []);

                    if (orders.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="7" class="py-10 text-center text-sm text-gray-500">No orders found matching criteria.</td></tr>';
                        renderPagination(null);
                        return;
                    }

                    orders.forEach(order => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">${order.id ?? '-'}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">${order.name ?? '-'}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">${order.product ?? '-'}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-center">${order.quantity ?? '-'}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 font-mono text-indigo-600 font-medium">$${order.price ?? '-'}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">${order.created_at ?? '-'}</td>
                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                <button onclick="exportOrder(${order.id}, this)" class="inline-flex items-center rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-all">
                                    Export
                                </button>
                            </td>
                        `;
                        tbody.appendChild(row);
                    });

                    renderPagination(res);
                    updateActiveButtons();
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    tbody.innerHTML = '<tr><td colspan="7" class="py-10 text-center text-sm text-red-500">Failed to load data. Please try again.</td></tr>';
                });
        }

        function exportOrder(id, btn) {
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="animate-pulse">Exporting...</span>';

            fetch('/orders/' + id + '/export', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.message && data.message.includes('successfully')) {
                    btn.innerHTML = '✓ Success';
                    btn.classList.add('text-green-600', 'ring-green-300', 'bg-green-50');
                    setTimeout(() => {
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                        btn.classList.remove('text-green-600', 'ring-green-300', 'bg-green-50');
                    }, 3000);
                } else {
                    alert('Error: ' + (data.message || 'Failed to export'));
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Export error:', error);
                alert('System error during export');
                btn.disabled = false;
                btn.innerHTML = originalText;
            });
        }

        function renderPagination(res) {
            const nav = document.getElementById('pagination');
            nav.innerHTML = '';
            if (!res || !res.meta || !res.links || res.meta.last_page <= 1) return;

            res.meta.links.forEach(link => {
                const btn = document.createElement('button');
                btn.innerHTML = link.label.replace('&laquo;', '←').replace('&raquo;', '→');
                btn.disabled = !link.url || link.active;

                let baseClasses = 'relative inline-flex items-center px-4 py-2 text-sm font-semibold ';
                if (link.active) {
                    baseClasses += 'z-10 bg-indigo-600 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 rounded-md';
                } else if (!link.url) {
                    baseClasses += 'text-gray-300 rounded-md cursor-not-allowed';
                } else {
                    baseClasses += 'text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 rounded-md';
                }
                btn.className = baseClasses;

                if (link.url) {
                    btn.onclick = () => {
                        const url = new URL(link.url);
                        updateParam('page', url.searchParams.get('page'));
                    };
                }
                nav.appendChild(btn);
            });
        }

        function updateActiveButtons() {
            const urlParams = new URLSearchParams(window.location.search);
            const currentSort = urlParams.get('sort') || 'latest';

            document.querySelectorAll('#sort-buttons button').forEach(btn => {
                if (btn.getAttribute('onclick').includes(`'${currentSort}'`)) {
                    btn.className = 'rounded-full px-3 py-1 bg-indigo-600 text-white font-medium transition-colors';
                } else {
                    btn.className = 'rounded-full px-3 py-1 bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors';
                }
            });
        }

        function updateSort(sort) { updateParam('sort', sort); }

        function updateFilter(key, value) {
            const urlParams = new URLSearchParams(window.location.search);
            if (value) urlParams.set(key, value); else urlParams.delete(key);
            urlParams.delete('page');
            syncAndFetch(urlParams);
        }

        function updateParam(key, value) {
            const urlParams = new URLSearchParams(window.location.search);
            if (value) urlParams.set(key, value); else urlParams.delete(key);
            syncAndFetch(urlParams);
        }

        function syncAndFetch(params) {
            window.history.pushState({}, '', window.location.pathname + '?' + params.toString());
            fetchOrders();
        }

        fetchOrders();
        window.addEventListener('popstate', fetchOrders);
    </script>
@endsection
