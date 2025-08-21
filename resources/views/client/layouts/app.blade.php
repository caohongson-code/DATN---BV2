<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PowPow - Trang chủ</title>

    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- CSS chính --}}
    <link rel="stylesheet" href="{{ asset('client/css/main.css') }}">

    {{-- CSS bổ sung từ các view con --}}
    @stack('styles')
</head>
<body>
    {{-- Header --}}
    @include('client.layouts.header')

    {{-- Nội dung chính --}}
    <main class="container py-4">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('client.layouts.footer')

    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    {{-- Bootstrap Bundle JS (kèm Popper) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- JS chính --}}
    <script src="{{ asset('client/js/main.js') }}"></script>

    {{-- JS bổ sung từ các view con --}}
    @stack('scripts')
</body>
</html>
