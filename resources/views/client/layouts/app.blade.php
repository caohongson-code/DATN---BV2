<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>PowPow - Trang chủ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
    @stack('scripts')
    @yield('scripts')
<body>
    @include('client.layouts.header')
    <main class="container py-4">
        @yield('content')
    </main>
    @include('client.layouts.footer')
    
</body>
</html>
