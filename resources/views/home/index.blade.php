@extends('layouts.app')

@section('title', 'Welcome to Anton Orders Lab')

@section('content')
    <div class="text-center py-10">
        <h2 class="text-2xl font-semibold text-gray-900 mb-6">Explore the Project 👋</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto">
            <div class="p-6 bg-gray-50 rounded-xl border border-gray-100 text-left hover:shadow-md transition-shadow">
                <h3 class="font-bold text-gray-800 mb-2 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    Order Management
                </h3>
                <p class="text-sm text-gray-600 mb-4">View, sort and export customer orders to the supplier API.</p>
                <a href="/orders" class="text-indigo-600 text-sm font-medium hover:text-indigo-500">Go to Orders &rarr;</a>
            </div>

            <div class="p-6 bg-gray-50 rounded-xl border border-gray-100 text-left hover:shadow-md transition-shadow">
                <h3 class="font-bold text-gray-800 mb-2 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.58 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.58 4 8 4s8-1.79 8-4M4 7c0-2.21 3.58-4 8-4s8 1.79 8 4m0 5c0 2.21-3.58 4-8 4s-8-1.79-8-4"></path></svg>
                    Catalog Sync
                </h3>
                <p class="text-sm text-gray-600 mb-4">Import products from the supplier's external API into your local database.</p>
                <a href="/catalog" class="text-indigo-600 text-sm font-medium hover:text-indigo-500">Go to Catalog &rarr;</a>
            </div>

            <div class="p-6 bg-gray-50 rounded-xl border border-gray-100 text-left hover:shadow-md transition-shadow">
                <h3 class="font-bold text-gray-800 mb-2 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                    API Driven
                </h3>
                <p class="text-sm text-gray-600 mb-4">Explore the dynamic table powered by native JavaScript and REST API.</p>
                <a href="/orders-api" class="text-indigo-600 text-sm font-medium hover:text-indigo-500">View API Table &rarr;</a>
            </div>

            <div class="p-6 bg-gray-50 rounded-xl border border-gray-100 text-left hover:shadow-md transition-shadow">
                <h3 class="font-bold text-gray-800 mb-2 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                    Admin Control
                </h3>
                <p class="text-sm text-gray-600 mb-4">Manage everything via the modern Filament v3 dashboard.</p>
                <a href="/admin" class="text-indigo-600 text-sm font-medium hover:text-indigo-500">Open Admin &rarr;</a>
            </div>
        </div>

        <div class="mt-12 pt-8 border-t border-gray-100">
            <h4 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Project Highlights</h4>
            <div class="flex flex-wrap justify-center gap-4">
                <span class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600">Laravel 11</span>
                <span class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600">Tailwind CSS</span>
                <span class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600">Filament v3</span>
                <span class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600">Livewire</span>
                <span class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600">REST API</span>
            </div>
        </div>
    </div>
@endsection
