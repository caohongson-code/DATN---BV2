<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md bg-white border rounded p-6 shadow-md">
        <h3 class="text-lg font-semibold mb-4 text-center">Quên mật khẩu</h3>

        {{-- Thông báo thành công --}}
        @if (session('success'))
            <div class="mb-4 text-green-600 text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        {{-- Thông báo lỗi --}}
        @if ($errors->has('email'))
            <div class="mb-4 text-red-600 text-sm font-medium">
                {{ $errors->first('email') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-4">
                <input type="email" name="email" placeholder="Nhập email"
                       class="w-full border px-4 py-2 rounded text-sm focus:ring-2 focus:ring-black" required>
            </div>
            <button type="submit"
                    class="w-full bg-black text-white py-2 rounded text-sm font-semibold hover:bg-gray-800">
                Gửi link đặt lại mật khẩu
            </button>
        </form>

        <div class="text-center mt-4">
            <a href="{{ route('login') }}" class="text-sm text-blue-500 hover:underline">← Quay lại đăng nhập</a>
        </div>
    </div>

</body>
</html>
