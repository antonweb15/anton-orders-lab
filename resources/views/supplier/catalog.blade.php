<!DOCTYPE html>
<html>
<head>
    <title>Supplier Catalog</title>
</head>
<body>

<h1>Supplier Catalog</h1>

<table border="1" cellpadding="8">
    <thead>
    <tr>
        <th>ID</th>
        <th>External ID</th>
        <th>Name</th>
        <th>Price</th>
        <th>Stock</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($products as $product)
        <tr>
            <td>{{ $product->id }}</td>
            <td>{{ $product->external_id }}</td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->price }}</td>
            <td>{{ $product->stock }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<br>

{{ $products->links() }}

</body>
</html>
