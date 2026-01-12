<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Anton Orders Lab')</title>
</head>
<body>

@include('partials.header')

<main style="padding: 20px;">
    @yield('content')
</main>

@include('partials.footer')

</body>
</html>
