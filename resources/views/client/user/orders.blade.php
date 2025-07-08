@extends('client.user.dashboard')

@section('dashboard-content')
    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

        <style>
            .nav-tabs {
                gap: 0;
                /* Xóa khoảng cách nếu dùng flex */
                margin-bottom: 0;
            }

            .nav-tabs .nav-item {
                margin-right: 2px;
                /* Giảm khoảng cách giữa các tab */
            }

            .nav-tabs .nav-link {
                font-weight: bold;
                color: #333;
                padding: 8px 12px;
                /* Giảm padding ngang */
                border: 1px solid #dee2e6;
                border-bottom: none;
                border-radius: 0.375rem 0.375rem 0 0;
                background-color: #f9f9f9;
            }

            .nav-tabs .nav-link.active {
                background-color: #337ab7;
                color: #fff !important;
                border-color: #337ab7 #337ab7 transparent;
            }

            .card.order-item {
                transition: box-shadow 0.2s ease-in-out;
            }

            .card.order-item:hover {
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }

            .rating-stars {
                font-size: 22px;
                color: #ccc;
                cursor: pointer;
            }

            .rating-stars .star {
                margin-right: 5px;
                transition: color 0.2s;
                color: #ccc;
            }

            .rating-stars .star.hovered,
            .rating-stars .star.selected,
            .rating-stars .star.fa-star {
                color: #f5b301 !important;
            }

            .order-action-buttons .btn {
                min-width: 130px;
                /* hoặc width: 130px nếu muốn tuyệt đối */
                margin: 0 4px 6px 0;
                text-align: center;
                padding: 6px 12px;
                font-size: 14px;
            }

            .rating-stars .star {
                margin-right: 5px;
                color: #ccc;
                transition: color 0.3s, transform 0.2s ease-in-out;
            }

            .rating-stars .star.hovered,
            .rating-stars .star.selected,
            .rating-stars .star.fa-star {
                color: #f5b301 !important;
                transform: scale(1.2);
            }

            .btn:hover {
                transform: translateY(-1px);
                box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
                transition: all 0.2s ease-in-out;
            }

            .card.order-item {
                border: 1px solid #dee2e6;
                border-radius: 0.5rem;
                overflow: hidden;
            }

            .card.order-item .card-header {
                background-color: #f1f1f1;
                font-size: 15px;
                font-weight: 500;
            }

            .nav-tabs {
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
                border-bottom: none;
            }

            .nav-tabs .nav-link.active {
                border-bottom: 2px solid transparent;
                border-radius: 0.5rem 0.5rem 0 0;
            }

            .modal-content {
                border-radius: 0.75rem;
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
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
        $maxVisible = 10;
    @endphp

    <ul class="nav nav-tabs" id="orderTabs" role="tablist">
        @foreach ($statusLabels as $index => $label)
            <li class="nav-item" role="presentation">
                <button class="nav-link @if ($index === 0) active @endif" data-bs-toggle="tab"
                    data-bs-target="#tab-{{ $index }}" type="button" role="tab">{{ $label }}</button>
            </li>
        @endforeach
    </ul>

    <div class="tab-content pt-3">
        @foreach ($statusLabels as $index => $label)
            @php
                $statusId = $statusMap[$index];
                $filteredOrders = is_null($statusId) ? $orders : $orders->where('order_status_id', $statusId);
            @endphp
            <div class="tab-pane fade @if ($index === 0) show active @endif" id="tab-{{ $index }}"
                role="tabpanel">
                @if ($filteredOrders->count())
                    @foreach ($filteredOrders->values() as $key => $order)
                        <div class="card mb-3 order-item {{ $key >= $maxVisible ? 'd-none' : '' }}"
                            data-id="{{ $order->id }}">

                            <div class="card-header">
                                <strong>Mã đơn hàng:</strong> Đơn #{{ $key + 1 }} |
                                <strong>Trạng thái:</strong> <span
                                    class="text-primary">{{ $order->orderStatus->status_name ?? 'Không rõ' }}</span> |
                                <strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}
                            </div>
                            <div class="card-body">
                                @foreach ($order->orderDetails as $item)
                                    @php
                                        $variant = $item->productVariant;
                                        $product = $variant?->product;
                                        $image = $product?->image
                                            ? asset('storage/' . $product->image)
                                            : asset('images/default.jpg');
                                    @endphp
                                    <div class="d-flex mb-3 border-bottom pb-2">
                                        <img src="{{ $image }}" class="img-thumbnail me-3"
                                            style="width: 80px; height: 80px; object-fit: cover;">
                                        <div>
                                            <h5>{{ $product->product_name ?? 'Không rõ sản phẩm' }}</h5>
                                            <p>Giá: {{ number_format($item->unit_price, 0, ',', '.') }}₫ x
                                                {{ $item->quantity }}</p>

                                            @if ($order->order_status_id == 5)
                                                @php
                                                    $key = $order->id . '-' . $item->product_variant_id;
                                                    $alreadyReviewed = isset($reviewedMap[$key]);
                                                @endphp

                                                @if ($alreadyReviewed)
                                                    <span class="badge bg-secondary">Đã đánh giá</span>
                                                @else
                                                    <button class="btn btn-success btn-sm btn-review"
                                                        data-variant-id="{{ $item->product_variant_id }}"
                                                        data-product-name="{{ $product->product_name }}"
                                                        data-order-id="{{ $order->id }}">Đánh giá</button>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <span
                                            class="badge 
            @switch($order->payment_status_id)
                @case(1) bg-warning text-dark @break
                @case(2) bg-success @break
                @case(3) bg-danger @break
                @case(4) bg-info text-dark @break
                @default bg-secondary
            @endswitch">
                                            {{ $order->paymentStatus->name ?? 'Không rõ' }}
                                        </span>
                                    </div>
                                    <div>
                                        <strong>Tổng tiền:</strong>
                                        {{ number_format($order->total_amount, 0, ',', '.') }}₫
                                    </div>
                                </div>

                                <div class="text-end order-action-buttons d-flex flex-wrap justify-content-end">



                                    <a href="{{ route('user.orders.detail', $order->id) }}"
                                        class="btn btn-primary btn-sm">Xem chi tiết</a>
                                    @if ($order->order_status_id == 1)
                                        <button class="btn btn-danger btn-sm cancel-order-btn">Huỷ đơn</button>
                                    @endif
                                    @if ($order->order_status_id == 5)
                                        <button class="btn btn-warning btn-sm return-order-btn">Trả hàng/Hoàn tiền</button>
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

    {{-- Modal đánh giá --}}
    <div id="reviewModal" class="modal fade" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('client.reviews.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="product_variant_id" id="reviewVariantId">
                <input type="hidden" name="order_id" id="reviewOrderId">
                <input type="hidden" name="rating" id="selectedRating" value="0">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reviewModalLabel">Đánh giá sản phẩm: <span
                                id="reviewProductName"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Chọn số sao</label>
                            <div id="starRating" class="rating-stars">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="far fa-star star" data-value="{{ $i }}"
                                        style="font-size: 24px; cursor: pointer;"></i>
                                @endfor
                            </div>

                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nội dung đánh giá</label>
                            <textarea name="comment" class="form-control" rows="4" placeholder="Nội dung đánh giá..." required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ảnh đánh giá (tùy chọn)</label>
                            <input type="file" name="image" accept="image/*" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Gửi đánh giá</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal trả hàng --}}
    <div id="returnModal" class="modal fade" tabindex="-1" aria-labelledby="returnModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="returnRefundForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="order_id" id="returnOrderId">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="returnModalLabel">Trả hàng & Hoàn tiền</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Lý do trả hàng</label>
                            <textarea name="reason" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ảnh sản phẩm lỗi</label>
                            <input type="file" name="images[]" multiple class="form-control" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning">Gửi yêu cầu</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Show more toggle
            document.querySelectorAll('.btn-show-more').forEach(button => {
                button.addEventListener('click', function() {
                    const tabPane = this.closest('.tab-pane');
                    const hiddenOrders = tabPane.querySelectorAll('.order-item.d-none');
                    if (hiddenOrders.length) {
                        hiddenOrders.forEach(el => el.classList.remove('d-none'));
                        this.textContent = 'Ẩn bớt';
                    } else {
                        tabPane.querySelectorAll('.order-item').forEach((el, index) => {
                            if (index >= {{ $maxVisible }}) el.classList.add('d-none');
                        });
                        this.textContent = 'Xem thêm';
                    }
                });
            });

            // Cancel order
            document.querySelectorAll('.cancel-order-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const card = this.closest('.order-item');
                    const orderId = card.dataset.id;
                    if (confirm('Bạn có chắc muốn huỷ đơn hàng này?')) {
                        fetch(`/client/orders/${orderId}/cancel`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json',
                                },
                            })
                            .then(res => res.json())
                            .then(res => {
                                alert(res.message);
                                if (res.success) location.reload();
                            });
                    }
                });
            });

            // Open review modal
            document.querySelectorAll('.btn-review').forEach(button => {
                button.addEventListener('click', function() {
                    document.getElementById('reviewVariantId').value = this.dataset.variantId;
                    document.getElementById('reviewOrderId').value = this.dataset.orderId;
                    document.getElementById('reviewProductName').textContent = this.dataset
                        .productName;
                    new bootstrap.Modal(document.getElementById('reviewModal')).show();

                    // Reset stars
                    document.getElementById('selectedRating').value = 0;
                    document.querySelectorAll('#starRating .star').forEach(star => {
                        star.classList.remove('fas', 'selected', 'hovered');
                        star.classList.add('far');
                    });

                });
            });

            // Star rating
            let currentRating = 0;
            const stars = document.querySelectorAll('#starRating .star');

            stars.forEach(star => {
                star.addEventListener('mouseenter', () => {
                    const val = parseInt(star.dataset.value);
                    stars.forEach(s => {
                        if (parseInt(s.dataset.value) <= val) {
                            s.classList.add('hovered');
                            s.classList.remove('far');
                            s.classList.add('fas');
                        } else {
                            s.classList.remove('hovered');
                            s.classList.remove('fas');
                            s.classList.add('far');
                        }
                    });
                });

                star.addEventListener('mouseleave', () => {
                    stars.forEach(s => {
                        s.classList.remove('hovered');
                        if (!s.classList.contains('selected')) {
                            s.classList.remove('fas');
                            s.classList.add('far');
                        }
                    });
                });

                star.addEventListener('click', () => {
                    currentRating = parseInt(star.dataset.value);
                    document.getElementById('selectedRating').value = currentRating;
                    stars.forEach(s => {
                        if (parseInt(s.dataset.value) <= currentRating) {
                            s.classList.add('selected');
                            s.classList.remove('far');
                            s.classList.add('fas');
                        } else {
                            s.classList.remove('selected');
                            s.classList.remove('fas');
                            s.classList.add('far');
                        }
                    });
                });
            });

            // Return modal
            document.querySelectorAll('.return-order-btn').forEach(button => {
                button.addEventListener('click', function() {
                    document.getElementById('returnOrderId').value = this.closest('.order-item')
                        .dataset.id;
                    new bootstrap.Modal(document.getElementById('returnModal')).show();
                });
            });

            // Submit return form
            const returnForm = document.getElementById('returnRefundForm');
            returnForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const orderId = document.getElementById('returnOrderId').value;
                fetch(`/orders/return-refund/${orderId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(res => {
                        alert(res.message);
                        if (res.success) location.reload();
                    })
                    .catch(err => {
                        alert('Đã xảy ra lỗi.');
                    });
            });
        });
    </script>
@endpush
