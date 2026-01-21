@extends('layouts.app')

@section('title', 'Payment Cancelled')

@section('content')
<div class="flex items-center justify-center py-12">
    <div class="max-w-md w-full text-center">
        <div class="mb-6 flex justify-center">
            <div class="rounded-full bg-red-100 p-3">
                <svg class="h-12 w-12 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
        </div>

        <h2 class="text-3xl font-extrabold text-gray-900 mb-2">Payment Cancelled</h2>
        <p class="text-lg text-gray-600 mb-8">
            Your payment process was cancelled. No funds were debited from your account.
        </p>

        <div class="space-y-4">
            <a href="/pay" class="w-full inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                Try Again
            </a>
            <a href="/" class="w-full inline-flex justify-center items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                Back to Home
            </a>
        </div>

        <p class="mt-8 text-sm text-gray-500">
            If you encountered any issues during the checkout process, please let us know.
        </p>
    </div>
</div>
@endsection
