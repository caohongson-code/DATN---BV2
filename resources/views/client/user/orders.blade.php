@extends('client.user.dashboard')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

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
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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
                box-shadow: 0 1px 5px rgba(0, 0, 0, 0.05);
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

            .tab-content {
                margin-top: 20px;
            }

            .rating-stars {
                font-size: 22px;
                color: #ccc;
                /* Màu sao mặc định */
                cursor: pointer;
            }

            .rating-stars .star {
                margin-right: 5px;
                transition: color 0.2s;
                color: #ccc;
                /* màu xám mặc định */
            }

            .rating-stars .star.hovered,
            .rating-stars .star.selected,
            .rating-stars .star.fa-star {
                color: #f5b301 !important;
                /* vàng nổi bật */
            }
        </style>
    @endpush

    <h3 class="mb-4">🍚 Quản lý đơn hàng</h3>

    @php
        $statusLabels = [
            'Tất cả',
            'Chờ xác nhận',
            'Đã xác nhận',
            'Đang chuẩn bị',
            'Đang giao',
            'Đã giao',
            'Trả hàng/Hoàn tiền',
            'Đã hủy',
        ];
        $statusMap = [0 => null, 1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7];
        $maxVisible = 5;
    @endphp

    <ul class="nav nav-tabs" role="tablist" id="orderTabs">
        @foreach ($statusLabels as $index => $label)
            <li role="presentation" class="{{ $index === 0 ? 'active' : '' }}">
                <a href="#tab-{{ $index }}" data-toggle="tab">{{ $label }}</a>
            </li>
        @endforeach
    </ul>

    <div class="tab-content">
        @foreach ($statusLabels as $index => $label)
            @php
                $statusId = $statusMap[$index];
                $filteredOrders = is_null($statusId) ? $orders : $orders->where('order_status_id', $statusId);
            @endphp
            <div role="tabpanel" class="tab-pane fade {{ $index === 0 ? 'in active' : '' }}" id="tab-{{ $index }}">
                @if ($filteredOrders->count())
                    @foreach ($filteredOrders as $key => $order)
                        <div class="panel panel-default panel-order order-item {{ $key >= $maxVisible ? 'd-none' : '' }}"
                            data-id="{{ $order->id }}" style="margin-bottom: 15px;">
                            <div class="panel-heading">
                                <strong>Mã đơn hàng:</strong> Đơn #{{ $key + 1 }} |
                                <strong>Trạng thái:</strong> <span
                                    class="text-primary status-label">{{ $order->orderStatus->status_name ?? 'Không rõ' }}</span>
                                |
                                <strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}
                            </div>
                            <div class="panel-body">
                                @foreach ($order->orderDetails as $item)
                                    @php
                                        $variant = $item->productVariant;
                                        $product = $variant?->product;
                                        $image = $product?->image
                                            ? asset('storage/' . $product->image)
                                            : asset('images/default.jpg');
                                    @endphp
                                    <div class="media"
                                        style="border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 10px;">
                                        <div class="media-left">
                                            <img class="media-object img-thumbnail" src="{{ $image }}"
                                                style="width: 80px; height: 80px; object-fit: cover;">
                                        </div>
                                        <div class="media-body">
                                            <h4 class="media-heading">{{ $product->product_name ?? 'Không rõ sản phẩm' }}
                                            </h4>
                                            <p>Số lượng: {{ $item->quantity }}</p>
                                            @if ($order->order_status_id == 5)
                                                @php
                                                    $key = $order->id . '-' . $item->product_variant_id;
                                                    $alreadyReviewed = isset($reviewedMap[$key]);
                                                @endphp

                                                @if ($alreadyReviewed)
                                                    <span class="label label-default">Đã đánh giá</span>
                                                @else
                                                    <button class="btn btn-success btn-xs btn-review"
                                                        data-variant-id="{{ $item->product_variant_id }}"
                                                        data-product-name="{{ $product->product_name }}"
                                                        data-order-id="{{ $order->id }}">
                                                        Đánh giá
                                                    </button>
                                                @endif
                                            @endif

                                        </div>
                                    </div>
                                @endforeach
                                <div class="text-right">
                                    <a href="{{ route('user.orders.detail', $order->id) }}"
                                        class="btn btn-primary btn-xs">Xem chi tiết</a>
                                    @if ($order->order_status_id == 1)
                                        <button class="btn btn-danger btn-xs cancel-order-btn">Huỷ đơn</button>
                                    @endif
                                    @if ($order->order_status_id == 5)
                                        <button class="btn btn-warning btn-xs return-order-btn">Trả hàng/Hoàn tiền</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if ($filteredOrders->count() > $maxVisible)
                        <div class="text-center">
                            <button class="btn btn-link btn-show-more">Xem thêm</button>
                        </div>
                    @endif
                @else
                    <p class="text-muted">Chưa có đơn hàng trong mục này.</p>
                @endif
            </div>
        @endforeach
    </div>
    <!-- Modal đánh giá -->
    <div id="reviewModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="reviewModalLabel">
        <div class="modal-dialog" role="document">
            <form method="POST" action="{{ route('client.reviews.store') }}">
                @csrf
                <input type="hidden" name="product_variant_id" id="reviewVariantId">
                <input type="hidden" name="order_id" id="reviewOrderId">
                <input type="hidden" name="rating" id="selectedRating" value="0">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title" id="reviewModalLabel">Đánh giá sản phẩm: <span
                                id="reviewProductName"></span></h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Chọn số sao</label>
                            <!-- Phần star icons trong modal -->
                            <div id="starRating" class="rating-stars">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="fa fa-star-o star" data-value="{{ $i }}"
                                        style="font-size: 24px; cursor: pointer;"></i>
                                @endfor
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Nội dung đánh giá</label>
                            <textarea name="comment" class="form-control" rows="4" placeholder="Nội dung đánh giá..." required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Ảnh đánh giá (tùy chọn)</label>
                            <input type="file" name="image" accept="image/*" class="form-control">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Gửi đánh giá</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <!-- Modal trả hàng -->
    <div id="returnModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="returnModalLabel">
        <div class="modal-dialog" role="document">
            <form id="returnRefundForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="order_id" id="returnOrderId">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title" id="returnModalLabel">Trả hàng & Hoàn tiền</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Lý do trả hàng</label>
                            <textarea name="reason" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Ảnh sản phẩm lỗi</label>
                            <input type="file" name="images[]" multiple class="form-control" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning">Gửi yêu cầu</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <!-- jQuery tương thích Bootstrap 3 -->
        <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <script>
            $(document).ready(function() {
                // Huỷ đơn
                $('.cancel-order-btn').click(function() {
                    const panel = $(this).closest('.panel-order');
                    const orderId = panel.data('id');
                    if (!confirm('Bạn có chắc muốn huỷ đơn hàng này?')) return;

                    $.post(`/client/orders/${orderId}/cancel`, {
                        _token: '{{ csrf_token() }}'
                    }, function(res) {
                        alert(res.message);
                        if (res.success) location.reload();
                    });
                });

                // Mở modal đánh giá
                $('.btn-review').click(function() {
                    $('#reviewVariantId').val($(this).data('variant-id'));
                    $('#reviewOrderId').val($(this).data('order-id'));
                    $('#reviewProductName').text($(this).data('product-name'));
                    $('#reviewModal').modal('show');

                    // Reset sao khi mở lại modal
                    currentRating = 0;
                    $('#selectedRating').val(0);
                    $('#starRating .star')
                        .removeClass('fa-star selected hovered')
                        .addClass('fa-star-o');
                });

                // Mở modal trả hàng
                $('.return-order-btn').click(function() {
                    const orderId = $(this).closest('.panel-order').data('id');
                    $('#returnOrderId').val(orderId);
                    $('#returnModal').modal('show');
                });

                // Gửi yêu cầu trả hàng
                $('#returnRefundForm').submit(function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    const orderId = $('#returnOrderId').val();

                    $.ajax({
                        url: `/orders/return-refund/${orderId}`,
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(res) {
                            alert(res.message);
                            if (res.success) location.reload();
                        },
                        error: function(xhr) {
                            alert(xhr.responseJSON?.message || 'Lỗi khi gửi yêu cầu trả hàng.');
                        }
                    });
                });

                // Xem thêm / Ẩn bớt đơn hàng
                $('.tab-content').on('click', '.btn-show-more', function() {
                    const $btn = $(this);
                    const $tabPane = $btn.closest('.tab-pane');
                    const hiddenOrders = $tabPane.find('.order-item.d-none');

                    if (hiddenOrders.length > 0) {
                        hiddenOrders.removeClass('d-none');
                        $btn.text('Ẩn bớt');
                    } else {
                        $tabPane.find('.order-item').each(function(index) {
                            if (index >= {{ $maxVisible }}) $(this).addClass('d-none');
                        });
                        $btn.text('Xem thêm');
                    }
                });

                // ⭐ Hiệu ứng đánh giá sao
                let currentRating = 0;

                // Hover sao
                $('#starRating').on('mouseenter', '.star', function() {
                    const hoverValue = $(this).data('value');
                    $('#starRating .star').each(function() {
                        const val = $(this).data('value');
                        if (val <= hoverValue) {
                            $(this).addClass('hovered');
                        } else {
                            $(this).removeClass('hovered');
                        }
                    });
                }).on('mouseleave', '.star', function() {
                    $('#starRating .star').removeClass('hovered');
                });

                // Click chọn sao
                $('#starRating').on('click', '.star', function() {
                    currentRating = $(this).data('value');
                    $('#selectedRating').val(currentRating);

                    $('#starRating .star').each(function() {
                        const val = $(this).data('value');
                        if (val <= currentRating) {
                            $(this)
                                .removeClass('fa-star-o')
                                .addClass('fa-star selected');
                        } else {
                            $(this)
                                .removeClass('fa-star selected')
                                .addClass('fa-star-o');
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
