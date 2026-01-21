<nav class="bg-white shadow-sm border-b border-gray-200">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-between">
            <div class="flex">
                <div class="flex flex-shrink-0 items-center">
                    <span class="text-xl font-bold text-gray-800">Anton Orders Lab</span>
                </div>
                <div class="hidden sm:-my-px sm:ml-6 sm:flex sm:space-x-8">
                    <a href="/" class="{{ request()->is('/') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center border-b-2 px-1 pt-1 text-sm font-medium">Home</a>
                    <a href="/orders" class="{{ request()->is('orders') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center border-b-2 px-1 pt-1 text-sm font-medium">Orders</a>
                    <a href="/catalog" class="{{ request()->is('catalog') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center border-b-2 px-1 pt-1 text-sm font-medium">Catalog</a>
                    <a href="/orders-api" class="{{ request()->is('orders-api') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center border-b-2 px-1 pt-1 text-sm font-medium">Orders API</a>
                    <a href="/pay" class="{{ request()->is('pay') ? 'border-green-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center border-b-2 px-1 pt-1 text-sm font-medium">Pay Demo</a>
                </div>
            </div>
            <div class="hidden sm:ml-6 sm:flex sm:items-center">
                <a href="/admin" class="rounded-md bg-gray-100 px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm hover:bg-gray-200">Admin Panel</a>
            </div>
        </div>
    </div>
</nav>
