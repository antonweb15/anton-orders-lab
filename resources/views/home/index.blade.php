@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <h2>Welcome 👋</h2>

    <p>This is a demo Laravel project to understand:</p>

    <ul>
        <li>Request lifecycle</li>
        <li>Controllers</li>
        <li>Services</li>
        <li>Models (Eloquent)</li>
        <li>Middleware</li>
    </ul>

    <p>
        👉 <a href="/orders">Go to Orders</a>
    </p>
@endsection
