<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md bg-white border rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold mb-6 text-center">🔑 Đặt lại mật khẩu</h2>

        @if(session('error'))
            <div class="mb-4 text-red-600 text-sm font-medium">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-4">
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Email"
                       class="w-full border px-4 py-2 rounded text-sm focus:ring-2 focus:ring-black">
                @error('email') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <input type="password" name="password" placeholder="Mật khẩu mới"
                       class="w-full border px-4 py-2 rounded text-sm focus:ring-2 focus:ring-black">
                @error('password') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <input type="password" name="password_confirmation" placeholder="Nhập lại mật khẩu"
                       class="w-full border px-4 py-2 rounded text-sm focus:ring-2 focus:ring-black">
                @error('password_confirmation') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit"
                    class="w-full bg-black text-white py-2 rounded text-sm font-semibold hover:bg-gray-800">
                Cập nhật mật khẩu
            </button>
        </form>

        <div class="text-center mt-4">
            <a href="{{ route('login') }}" class="text-sm text-blue-500 hover:underline">← Quay lại đăng nhập</a>
        </div>
    </div>

</body>
</html>
