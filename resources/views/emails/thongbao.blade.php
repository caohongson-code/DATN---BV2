<!DOCTYPE html>
<html>
<head>
    <title>Thông báo tài khoản</title>
</head>
<body>
    <h2>Xin chào {{ $account->full_name }}!</h2>
    <p>Bạn đã đăng ký tài khoản thành công trên hệ thống.</p>
    <p>Email đăng nhập: {{ $account->email }}</p>
    <p>Password đăng nhập: {{ $plainPassword }}</p>
    <p>Cảm ơn bạn đã tham gia!</p>
</body>
</html>
