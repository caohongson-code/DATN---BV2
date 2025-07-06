@extends('client.layouts.app')

@section('title', 'Chuyển hướng tới MoMo')

@section('content')
    <div class="container text-center py-5">
        <h2 class="text-primary mb-4">🔄 Đang chuyển hướng đến cổng thanh toán MoMo...</h2>
        <p class="mb-3">Vui lòng chờ giây lát. Bạn sẽ được chuyển đến trang thanh toán của MoMo để hoàn tất đơn hàng.</p>
        <p class="text-muted">Nếu trình duyệt không tự động chuyển, vui lòng bấm nút bên dưới.</p>

        <form method="POST" action="{{ route('momo.payment') }}" id="momo-form">
            @csrf
            <input type="hidden" name="request_id" value="{{ $request_id }}">
            <input type="hidden" name="total_momo" value="{{ $total }}">
            <input type="hidden" name="order_id" value="{{ $orderId }}">

            <button type="submit" class="btn btn-primary mt-3">👉 Bấm vào đây nếu không được chuyển tự động</button>
        </form>
    </div>
@endsection

@section('scripts')
<script>
    // Tự động submit form sau 1 giây
    setTimeout(() => {
        document.getElementById('momo-form').submit();
    }, 1000);
</script>
@endsection
