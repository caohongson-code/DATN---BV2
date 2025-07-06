@extends('client.layouts.app')

@section('content')
<style>
    .dashboard-card {
        max-width: 500px;
        margin: 40px auto;
        padding: 30px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        font-family: 'Segoe UI', sans-serif;
    }

    .dashboard-card h2 {
        font-size: 26px;
        margin-bottom: 25px;
        font-weight: 600;
        color: #333;
        text-align: center;
    }

    .dashboard-links a {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        margin-bottom: 10px;
        border-radius: 8px;
        background: #f8f9fa;
        text-decoration: none;
        color: #333;
        font-size: 16px;
        transition: all 0.3s;
    }

    .dashboard-links a:hover {
        background: #e9ecef;
        color: #d70018;
        text-decoration: none;
    }

    .dashboard-links i {
        margin-right: 10px;
        font-size: 18px;
    }
</style>

<div class="dashboard-card">
    <h2>Xin chào, {{ Auth::user()->name }}</h2>
    <div class="dashboard-links">
        <a href="{{ route('user.profile') }}">
            <i class="fa fa-user"></i> Thông tin cá nhân
        </a>
        <a href="{{ route('user.orders') }}">
            <i class="fa fa-shopping-bag"></i> Quản lý đơn hàng
        </a>
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fa fa-sign-out-alt"></i> Đăng xuất
        </a>
        <form id="logout-form" action="{{ route('taikhoan.logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</div>
@endsection
