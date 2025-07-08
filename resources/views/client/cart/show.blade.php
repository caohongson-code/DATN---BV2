@extends('client.layouts.app')

@section('content')
    <style>
        .cart-wrapper {
            max-width: 1000px;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
            padding: 24px;
            font-family: 'Segoe UI', sans-serif;
        }

        .cart-table th,
        .cart-table td {
            vertical-align: middle !important;
        }

        .cart-table img {
            max-width: 80px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .cart-summary {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
        }

        .cart-summary h4 {
            font-weight: 600;
            color: #333;
        }

        .btn-checkout {
            font-size: 16px;
            padding: 12px 24px;
            border-radius: 8px;
            min-width: 220px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .btn-checkout i {
            margin-right: 6px;
        }

        .table thead th {
            background: #e9ecef;
        }

        .fw-bold {
            font-weight: 600 !important;
        }

        @media (max-width: 768px) {
            .cart-table img {
                max-width: 60px;
            }

            .btn-checkout {
                width: 100%;
            }
        }
    </style>

    <div class="container my-5">
        <div class="cart-wrapper">
            <h2 class="fw-bold mb-4 text-center">🛒 Giỏ hàng của bạn</h2>

            <<<<<<< HEAD @if ($cart && $cart->details->count())
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
                                        $price =
                                            $item->variant?->price ??
                                            ($item->product->discount_price ?? $item->product->price);
                                        $subtotal = $item->quantity * $price;
                                    @endphp

                                    @if ($cart && $cart->details->count())
                                        <form id="checkout-form" action="{{ route('checkout') }}" method="GET">
                                            <div class="table-responsive">
                                                <table class="table table-bordered align-middle cart-table">
                                                    <thead class="text-center">

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
                                                                $price =
                                                                    $item->product->discount_price ??
                                                                    $item->product->price;
                                                                $subtotal = $item->quantity * $price;
                                                            @endphp
                                                            <tr>
                                                                <td class="text-center">
                                                                    <input type="checkbox" class="item-checkbox"
                                                                        name="selected_items[]" value="{{ $item->id }}"
                                                                        data-subtotal="{{ $subtotal }}" checked>
                                                                </td>
                                                                <td class="text-center">
                                                                    <img src="{{ asset('storage/' . $item->product->image) }}"
                                                                        alt="{{ $item->product->product_name }}">
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
                                                                <td class="text-danger fw-bold">
                                                                    {{ number_format($price, 0, ',', '.') }} đ</td>
                                                                <td class="text-center">
                                                                    <span
                                                                        class="badge bg-secondary">{{ $item->quantity }}</span>
                                                                </td>
                                                                <td class="fw-bold">
                                                                    {{ number_format($subtotal, 0, ',', '.') }} đ</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="cart-summary mt-4 text-end">
                                                <h4>Tổng cộng: <span id="total-price" class="text-danger">0 đ</span></h4>
                                                <button type="submit" class="btn btn-success btn-checkout mt-3">
                                                    <i class="fa fa-credit-card"></i> Tiến hành thanh toán
                                                </button>
                                            </div>
                                        </form>
                                    @else
                                        <div class="alert alert-info mt-4 text-center">
                                            <i class="fa fa-info-circle"></i> Giỏ hàng của bạn hiện đang trống.
                                        </div>
                                    @endif
                    </div>
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

            document.addEventListener('DOMContentLoaded', function() {
                const checkboxes = document.querySelectorAll('.item-checkbox');
                const selectAll = document.getElementById('select-all');

                updateTotal();

                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', updateTotal);
                });

                selectAll.addEventListener('change', function() {
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = selectAll.checked;
                    });
                    updateTotal();
                });
            });
        </script>
    @endsection
