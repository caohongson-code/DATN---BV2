@extends('client.user.dashboard')

@section('dashboard-content')
@push('styles')
<style>
    .nav-tabs>li>a {
        font-weight: bold;
        color: #333;
        padding: 10px 16px;
    }
    .nav-tabs>li.active>a {
        background-color: #337ab7;
        color: #fff !important;
        border-radius: 4px 4px 0 0;
    }
    .panel-order {
        border: 1px solid #ddd;
        border-radius: 5px;
        overflow: hidden;
        transition: box-shadow 0.2s ease-in-out;
    }
    .panel-order:hover {
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .panel-order .panel-heading {
        background-color: #f7f7f7;
        font-weight: bold;
        font-size: 14px;
        padding: 12px 15px;
        border-bottom: 1px solid #ddd;
    }
    .panel-order .panel-body {
        padding: 15px;
    }
    .media-object {
        border-radius: 4px;
        box-shadow: 0 1px 5px rgba(0,0,0,0.05);
    }
    .media-body h4 {
        font-size: 16px;
        font-weight: 600;
    }
    .btn-xs {
        padding: 5px 10px;
        font-size: 12px;
        border-radius: 3px;
    }
    .btn-review-product {
        margin-right: 5px;
    }
    .tab-content {
        margin-top: 20px;
    }
    .show-more-btn, .hide-more-btn {
        color: #337ab7;
        font-weight: bold;
    }
</style>
@endpush

<h3 class="mb-4">🍚 Quản lý đơn hàng</h3>

@php
    $statusLabels = ['Tất cả', 'Chờ xác nhận', 'Đã xác nhận', 'Đang chuẩn bị', 'Đang giao', 'Đã giao', 'Trả hàng/Hoàn tiền', 'Đã hủy'];
    $statusMap = [0 => null, 1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7];
@endphp

<ul class="nav nav-tabs" role="tablist" id="orderTabs">
    @foreach ($statusLabels as $index => $label)
        <li role="presentation" class="{{ $index === 0 ? 'active' : '' }}">
            <a href="#tab-{{ $index }}" aria-controls="tab-{{ $index }}" role="tab" data-toggle="tab">
                {{ $label }}
            </a>
        </li>
    @endforeach
</ul>

<div class="tab-content" style="margin-top: 15px;">
    @foreach ($statusLabels as $index => $label)
        @php
            $statusId = $statusMap[$index];
            $filteredOrders = (is_null($statusId) ? $orders : $orders->where('order_status_id', $statusId))->sortByDesc('created_at');
        @endphp
        <div role="tabpanel" class="tab-pane fade {{ $index === 0 ? 'in active' : '' }}" id="tab-{{ $index }}">
            @if ($filteredOrders->count())
                @foreach ($filteredOrders as $key => $order)
                    <div class="panel panel-default panel-order {{ $key >= 10 ? 'd-none extra-order' : '' }}" data-id="{{ $order->id }}" style="margin-bottom: 15px;">
                        <div class="panel-heading">
                            <strong>Mã đơn hàng:</strong> Đơn #{{ $key + 1 }} |
                            <strong>Trạng thái:</strong> <span class="text-primary status-label">{{ $order->orderStatus->status_name ?? 'Không rõ' }}</span> |
                            <strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}
                            @if ($order->order_status_id == 7 && in_array($index, [0, 7]))
                                <br><strong>Ngày huỷ:</strong>
                                <span class="text-danger cancel-time">{{ $order->updated_at->format('d/m/Y H:i') }}</span>
                            @endif
                        </div>
                        <div class="panel-body">
                            @foreach ($order->orderDetails as $item)
                                @php
                                    $variant = $item->productVariant;
                                    $product = $variant?->product;
                                    $image = $product?->image ? asset('storage/' . $product->image) : asset('images/default.jpg');
                                @endphp
                                <div class="media" style="border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 10px;">
                                    <div class="media-left">
                                        <img class="media-object img-thumbnail" src="{{ $image }}" alt="Ảnh sản phẩm" style="width: 80px; height: 80px; object-fit: cover;">
                                    </div>
                                    <div class="media-body">
                                        <h4 class="media-heading">{{ $product->product_name ?? 'Không rõ sản phẩm' }}</h4>
                                        <p>Số lượng: {{ $item->quantity ?? 0 }}</p>
                                        @if ($order->order_status_id == 5)
                                            <button
                                                class="btn btn-success btn-xs btn-review"
                                                data-variant-id="{{ $item->product_variant_id }}"
                                                data-product-name="{{ $product->product_name ?? 'Sản phẩm' }}"
                                                data-order-id="{{ $order->id }}"
                                            >
                                                Đánh giá
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                            <div class="text-right">
                                <a href="{{ route('user.orders.detail', $order->id) }}" class="btn btn-primary btn-xs">Xem chi tiết</a>
                                @if ($order->order_status_id == 1)
                                    <button class="btn btn-danger btn-xs cancel-order-btn">Huỷ đơn</button>
                                @endif
                                @if ($order->order_status_id == 5)
                                    <a href="#" class="btn btn-warning btn-xs">Trả hàng/Hoàn tiền</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
                @if ($filteredOrders->count() > 10)
                    <div class="text-center mt-2">
                        <button class="btn btn-link btn-sm show-more-btn" data-tab="{{ $index }}">Xem thêm</button>
                        <button class="btn btn-link btn-sm hide-more-btn d-none" data-tab="{{ $index }}">Ẩn bớt</button>
                    </div>
                @endif
            @else
                <p class="text-muted">Chưa có đơn hàng trong mục này.</p>
            @endif
        </div>
    @endforeach
</div>

<!-- Modal đánh giá -->
<div id="reviewModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form method="POST" action="{{ route('client.reviews.store') }}">
      @csrf
      <input type="hidden" name="product_variant_id" id="reviewVariantId">
      <input type="hidden" name="order_id" id="reviewOrderId">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Đánh giá sản phẩm: <span id="reviewProductName"></span></h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <textarea name="content" class="form-control" rows="4" placeholder="Nội dung đánh giá..." required></textarea>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Gửi đánh giá</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
        </div>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>


<script>
$(document).ready(function () {
    $('.cancel-order-btn').click(function () {
        const panel = $(this).closest('.panel-order');
        const orderId = panel.data('id');
        const token = '{{ csrf_token() }}';
        if (!confirm('Bạn có chắc muốn huỷ đơn hàng này?')) return;
        $.post(`/client/orders/${orderId}/cancel`, {_token: token}, function (res) {
            alert(res.message);
            panel.find('.status-label').text('Đã huỷ');
            panel.find('.cancel-order-btn').remove();
        });
    });

    $('.btn-review').click(function () {
        const variantId = $(this).data('variant-id');
        const productName = $(this).data('product-name');
        const orderId = $(this).data('order-id');

        $('#reviewVariantId').val(variantId);
        $('#reviewOrderId').val(orderId);
        $('#reviewProductName').text(productName);

        $('#reviewModal').modal('show');
    });

    $('.show-more-btn').click(function () {
        const tabIndex = $(this).data('tab');
        const tab = $('#tab-' + tabIndex);
        tab.find('.extra-order.d-none').slice(0, 10).removeClass('d-none');
        if (tab.find('.extra-order.d-none').length === 0) $(this).hide();
        $('.hide-more-btn[data-tab="' + tabIndex + '"]').removeClass('d-none');
    });

    $('.hide-more-btn').click(function () {
        const tabIndex = $(this).data('tab');
        const tab = $('#tab-' + tabIndex);
        tab.find('.extra-order:not(.d-none)').slice(-10).addClass('d-none');
        if (tab.find('.extra-order:not(.d-none)').length <= 10) $(this).addClass('d-none');
        $('.show-more-btn[data-tab="' + tabIndex + '"]').show();
    });
});
</script>
@endpush
@endsection
