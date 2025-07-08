<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>PowPow - Trang chủ</title>
</head>
    @stack('scripts')
    @yield('scripts')
<body>
    @include('client.layouts.header')
    {{-- khong can 2 cai duoi ko can mo ra dau --}}
    
     <main class="container py-4">
        @yield('content')
    </main> 
   

     @include('client.layouts.footer')
    
</body>
</html>