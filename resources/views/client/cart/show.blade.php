@extends('client.layouts.app')

@section('content')
<style>
    .cart-table img {
        max-width: 80px;
        height: auto;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .cart-summary {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        padding: 20px;
        border-radius: 8px;
    }
    .cart-summary h4 {
        margin-bottom: 20px;
    }
    .btn-checkout {
        min-width: 200px;
    }
</style>

<div class="container my-5">
    <h2 class="fw-bold mb-4">🛒 Giỏ hàng của bạn</h2>

    @if ($cart && $cart->details->count())
        <form id="checkout-form" action="{{ route('checkout') }}" method="GET">
            <div class="table-responsive">
                <table class="table table-bordered align-middle cart-table">
                    <thead class="table-light text-center">
                        <tr>
                            <th><input type="checkbox" id="select-all"></th>
                            <th>Ảnh</th>
                            <th>Sản phẩm</th>
                            <th>Phiên bản</th>
                            <th>Giá</th>
                            <th>Số lượng</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cart->details as $item)
                            @php
                                $price = $item->variant?->price ?? $item->product->discount_price ?? $item->product->price;
                                $subtotal = $item->quantity * $price;
                            @endphp
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" class="item-checkbox" name="selected_items[]" value="{{ $item->id }}" data-subtotal="{{ $subtotal }}" checked>
                                </td>
                                <td class="text-center">
                                    <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->product_name }}">
                                </td>
                                <td>{{ $item->product->product_name }}</td>
                                <td>
                                    @if ($item->variant)
                                        {{ $item->variant->ram->value ?? '?' }} / 
                                        {{ $item->variant->storage->value ?? '?' }} / 
                                        {{ $item->variant->color->value ?? '?' }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ number_format($price, 0, ',', '.') }} đ</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td>{{ number_format($subtotal, 0, ',', '.') }} đ</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="cart-summary mt-4 text-end">
                <h4 class="fw-bold">Tổng cộng: <span id="total-price" class="text-danger">0 đ</span></h4>
                <button type="submit" class="btn btn-success btn-checkout mt-3">
                    <i class="fa fa-credit-card"></i> Tiến hành thanh toán
                </button>
            </div>
        </form>
    @else
        <div class="alert alert-info mt-4">
            <i class="fa fa-info-circle"></i> Giỏ hàng của bạn hiện đang trống.
        </div>
    @endif
</div>

<script>
    function formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN').format(amount) + ' đ';
    }

    function updateTotal() {
        let total = 0;
        document.querySelectorAll('.item-checkbox:checked').forEach(checkbox => {
            total += parseInt(checkbox.dataset.subtotal);
        });
        document.getElementById('total-price').innerText = formatCurrency(total);
    }

    document.addEventListener('DOMContentLoaded', function () {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        const selectAll = document.getElementById('select-all');

        // Tính tổng ban đầu
        updateTotal();

        // Checkbox từng dòng
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateTotal);
        });

        // Checkbox chọn tất cả
        selectAll.addEventListener('change', function () {
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
            updateTotal();
        });
    });
</script>
@endsection
