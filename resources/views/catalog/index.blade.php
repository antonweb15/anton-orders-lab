@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Catalog</h1>

        <div class="mb-3 d-flex gap-2">
            <form action="{{ route('catalog.import') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">Import from Supplier</button>
            </form>

            <form action="{{ route('catalog.clear') }}" method="POST" onsubmit="return confirm('Are you sure you want to clear the catalog?')">
                @csrf
                <button type="submit" class="btn btn-danger">Clear Catalog</button>
            </form>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Supplier ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Stock</th>
            </tr>
            </thead>
            <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->supplier_id }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->price }}</td>
                    <td>{{ $product->stock }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $products->links() }}
    </div>
@endsection
