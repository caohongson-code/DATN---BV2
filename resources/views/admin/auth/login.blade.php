<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POW POW - Đăng nhập / Đăng ký</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.materialdesignicons.com/7.2.96/css/materialdesignicons.min.css" rel="stylesheet">
</head>
<body class="bg-white text-gray-800">
    <!-- Header -->
    <header class="text-center py-6 text-3xl font-bold tracking-wider border-b">POW POW</header>

    <!-- Navigation -->
    <nav class="flex justify-center space-x-8 py-3 border-b font-medium text-sm">
        <a href="#" class="hover:underline">TRANG CHỦ</a>
        <a href="#" class="hover:underline">SẢN PHẨM </a>
        <a href="#" class="hover:underline">GIỚI THIỆU</a>
        <a href="#" class="hover:underline">PHỤ KIỆN</a>
        <a href="#" class="hover:underline">LIÊN HỆ</a>
        <a href="#" class="hover:underline">CUSTOMER</a>
    </nav>

    <!-- Đây là tabs  Login/Register -->
    <div class="flex justify-center mt-8">
        <div class="w-full max-w-md border rounded px-6 py-8">
            <div class="flex justify-center space-x-8 text-sm font-semibold mb-6">
                <button id="tab-login" class="pb-1">ĐĂNG NHẬP</button>
                <button id="tab-register" class="text-gray-400 hover:text-black">ĐĂNG KÝ</button>
            </div>


            <!-- Form Login -->
            <form id="form-login" action="{{ route('taikhoan.login') }}" method="POST">
                @csrf
                @if (session('success'))
            <div class="mb-4 text-green-600 text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 text-red-600 text-sm font-medium">
                {{ session('error') }}
            </div>
        @endif
                <div class="mb-4">
                    <input type="text" name="email" placeholder="Nhập email hoặc Tên đăng nhập" class="w-full border px-4 py-2 rounded text-sm focus:ring-2 focus:ring-black">
                    @error('email')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="mb-4">
                    <input type="password" name="password" placeholder="Mật khẩu" class="w-full border px-4 py-2 rounded text-sm focus:ring-2 focus:ring-black">
                    @error('password')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="w-full bg-black text-white py-2 rounded text-sm font-semibold hover:bg-gray-800">
                    ĐĂNG NHẬP
                </button>
                <div class="text-right mt-2">
                    <a href="{{ route('password.request') }}" class="text-sm text-blue-500 hover:underline">Quên mật khẩu?</a>
                </div>
                <div class="text-center text-gray-400 text-sm my-4">Hoặc đăng nhập với</div>
                <div class="flex gap-2">
                    <a href="#" class="flex-1 bg-blue-700 text-white py-2 rounded flex items-center justify-center text-sm">
                        <i class="mdi mdi-facebook text-lg mr-2"></i> Facebook
                    </a>
                    <a href="#" class="flex-1 bg-black text-white py-2 rounded flex items-center justify-center text-sm">
                        <i class="mdi mdi-google text-lg mr-2"></i> Google
                    </a>
                </div>
            </form>

            <!-- Form Register -->
            <form id="form-register" action="{{route('taikhoan.register')}}" method="POST">
                @csrf
                <input type="hidden" name="_tab" value="register">
                <div class="mb-4">
                    <input type="text" name="full_name" placeholder="Họ và tên" class="w-full border px-4 py-2 rounded text-sm focus:ring-2 focus:ring-black">
                    @error('full_name')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="mb-4">
                    <input type="email" name="email" placeholder="Email" class="w-full border px-4 py-2 rounded text-sm focus:ring-2 focus:ring-black">
                    @error('email')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="mb-4">
                    <input type="password" name="password" placeholder="Mật khẩu" class="w-full border px-4 py-2 rounded text-sm focus:ring-2 focus:ring-black">
                    @error('password')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="mb-4">
                    <input type="password" name="password_confirmation" placeholder="Nhập lại mật khẩu" class="w-full border px-4 py-2 rounded text-sm focus:ring-2 focus:ring-black">
                    @error('password_confirmation')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="w-full bg-black text-white py-2 rounded text-sm font-semibold hover:bg-gray-800">
                    ĐĂNG KÝ
                </button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="mt-16 px-8 py-12 bg-gray-100 text-sm">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-6">
            <div><h3 class="font-semibold mb-2">HỖ TRỢ KHÁCH HÀNG</h3><ul class="space-y-1"><li>Hướng dẫn chọn size</li><li>Chính sách thành viên</li><li>Chính sách đổi/Trả</li><li>Chính sách giao nhận</li><li>FAQ</li></ul></div>
            <div><h3 class="font-semibold mb-2">VỀ CHÚNG TÔI</h3><ul class="space-y-1"><li>Giới thiệu</li><li>Liên hệ</li><li>Tìm đại lý</li></ul></div>
            <div><h3 class="font-semibold mb-2">HỆ THỐNG CỬA HÀNG</h3><ul class="space-y-1"><li>Facebook</li><li>Pinterest</li><li>Instagram</li><li>Spotify</li></ul></div>
            <div><h3 class="font-semibold mb-2">THEO DÕI CHÚNG TÔI</h3><div class="flex items-center space-x-3"><i class="mdi mdi-facebook text-lg"></i><i class="mdi mdi-instagram text-lg"></i></div></div>
        </div>
        <div class="text-center text-xs text-gray-500 mt-10">Thiết kế website bởi Nhanh.vn</div>
    </footer>

    <!-- Toggle Script -->
    <script>
        const loginBtn = document.getElementById('tab-login');
        const registerBtn = document.getElementById('tab-register');
        const formLogin = document.getElementById('form-login');
        const formRegister = document.getElementById('form-register');

        const oldTab = '{{ old('_tab') }}';

        function showTab(tab) {
            if (tab === 'register') {
                registerBtn.classList.add('border-b-2', 'border-gray-900');
                loginBtn.classList.remove('border-b-2', 'border-gray-900');
                loginBtn.classList.add('text-gray-400');

                formRegister.classList.remove('hidden');
                formLogin.classList.add('hidden');
            } else {
                loginBtn.classList.add('border-b-2', 'border-gray-900');
                registerBtn.classList.remove('border-b-2', 'border-gray-900');
                registerBtn.classList.add('text-gray-400');

                formLogin.classList.remove('hidden');
                formRegister.classList.add('hidden');
            }
        }

        loginBtn.addEventListener('click', () => showTab('login'));
        registerBtn.addEventListener('click', () => showTab('register'));

        // Hiển thị đúng tab nếu có lỗi
        document.addEventListener('DOMContentLoaded', function () {
            if (oldTab === 'register') {
                showTab('register');
            } else {
                showTab('login');
            }
        });
    </script>
</body>
</html>
