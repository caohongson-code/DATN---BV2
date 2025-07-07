<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>PowPow - Trang chủ</title>
</head>
<body>
    @include('client.layouts.header')

    <main class="container py-4">
        @yield('content')
    </main>

    @include('client.layouts.footer')

   


    
</body>
</html>
