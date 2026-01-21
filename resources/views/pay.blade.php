@extends('layouts.app')

@section('title', 'Demo Payment')

@section('content')
<div class="flex items-center justify-center">
    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-900">Secure Checkout</h2>
            <p class="text-gray-500 mt-2">This is a demonstration of Stripe integration.</p>
        </div>


        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <div class="flex justify-between items-center text-sm mb-2">
                <span class="text-gray-600">Product Subscription</span>
                <span class="font-medium text-gray-900">$10.00</span>
            </div>
            <div class="flex justify-between items-center text-sm mb-2">
                <span class="text-gray-600">Tax</span>
                <span class="font-medium text-gray-900">$0.00</span>
            </div>
            <div class="border-t border-gray-200 mt-2 pt-2 flex justify-between items-center">
                <span class="text-base font-semibold text-gray-900">Total</span>
                <span class="text-base font-bold text-indigo-600">$10.00 USD</span>
            </div>
        </div>

        <form method="POST" action="{{ route('stripe.pay') }}">
            @csrf
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200 shadow-md hover:shadow-lg flex items-center justify-center space-x-2">
                <span>Pay 10 USD (Demo)</span>
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </button>
        </form>

        <p class="mt-4 text-center text-xs text-gray-400">
            Secure payment processing powered by Demo Stripe.
        </p>
    </div>
</div>
@endsection
