{{-- resources/views/client/user/dashboard.blade.php --}}
@extends('client.layouts.app')

@section('content')
@push('styles')
<style>
    .tab-pane {
        display: block !important;
        opacity: 1 !important;
    }
</style>
@endpush

<div class="container py-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm rounded-3">
                <div class="card-header bg-dark text-white fw-bold">
                    <li class="dropdown-item">
                        Xin chào, <span class="fw-bold">{{ Auth::check() ? Auth::user()->full_name : 'Khách' }}</span>
                    </li>

                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('user.profile') }}" class="list-group-item list-group-item-action">👉 Thông tin cá nhân</a>
                    <a href="{{ route('user.orders') }}" class="list-group-item list-group-item-action">👉 Quản lý đơn hàng</a>
                    <a href="{{ route('user.wallet') }}" class="list-group-item list-group-item-action {{ request()->routeIs('user.wallet') ? 'active' : '' }}">

    👉 Ví của tôi
</a>
                    <a href="{{ route('client.promotions.index') }}" class="list-group-item list-group-item-action">👉 Mã giảm giá</a>

                    <a href="#" class="list-group-item list-group-item-action text-danger"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        👉 Đăng xuất
                    </a>
                    <form id="logout-form" action="{{ route('taikhoan.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>

        <!-- Nội dung động -->
        <div class="col-md-9">
            <div class="card shadow-sm rounded-3">
                <div class="card-body">
                    @yield('dashboard-content')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
