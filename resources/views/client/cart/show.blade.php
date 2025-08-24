@extends('client.layouts.app')

@section('content')
<style>
    .cart-wrapper {max-width: 1000px; margin: 40px auto; background: #fff; border-radius: 12px; box-shadow: 0 8px 24px rgba(0,0,0,0.06); padding: 24px;}
    .cart-table th, .cart-table td { vertical-align: middle !important; }
    .cart-table img { max-width: 80px; border-radius: 8px; }
    .cart-summary { background: #f8f9fa; border-radius: 8px; padding: 20px; }
    .cart-summary h4 { font-weight: 600; color: #333; }
    .btn-checkout { font-size: 16px; padding: 12px 24px; border-radius: 8px; min-width: 220px; }
    .table thead th { background: #e9ecef; }
</style>

<div class="container my-5">
    <div class="cart-wrapper">
        <h2 class="fw-bold mb-4 text-center">🛒 Giỏ hàng của bạn</h2>

        @if ($cart && $cart->details->count())
        <form id="checkout-form" action="{{ route('cart.updateBeforeCheckout') }}" method="POST">
            @csrf

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
                            <th>Xóa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cart->details as $item)
                            @php
                                $price = $item->variant
                                    ? (($item->variant->discount_price && $item->variant->discount_price < $item->variant->price)
                                        ? $item->variant->discount_price : $item->variant->price)
                                    : (($item->product->discount_price && $item->product->discount_price < $item->product->price)
                                        ? $item->product->discount_price : $item->product->price);
                                $subtotal = $item->quantity * $price;
                            @endphp
                            <tr data-id="{{ $item->id }}" data-price="{{ $price }}">
                                <td class="text-center">
                                    <input type="checkbox" class="item-checkbox" name="selected_items[]" value="{{ $item->id }}" checked>
                                </td>
                                <td class="text-center">
                                    <img src="{{ asset('storage/' . $item->product->image) }}" width="80">
                                </td>
                                <td>{{ $item->product->product_name }}</td>
<td>
                                    @if ($item->variant)
                                        {{ $item->variant->ram->value ?? '?' }} /
                                        {{ $item->variant->storage->value ?? '?' }} /
                                        {{ $item->variant->color->value ?? '?' }}
                                    @else - @endif
                                </td>
                                <td class="text-danger fw-bold">{{ number_format($price,0,',','.') }} đ</td>
                                <td class="text-center">
    <input type="number"
           class="form-control form-control-sm quantity-input text-center"
           name="quantities[{{ $item->id }}]"
           value="{{ $item->quantity }}"
           min="1"
           max="{{ $item->variant ? $item->variant->quantity - 1 : $item->product->quantity - 1 }}"
           style="width: 100px;">
</td>

                                <td class="fw-bold subtotal">{{ number_format($subtotal,0,',','.') }} đ</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm btn-delete-item" data-id="{{ $item->id }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="cart-summary mt-4 text-end">
                <h4>Tổng cộng: <span id="total-price" class="text-danger fw-bold">0 đ</span></h4>
                <button type="submit" class="btn btn-success btn-checkout mt-3">
                    <i class="fa fa-credit-card"></i> Tiến hành thanh toán
                </button>
            </div>
        </form>
        @else
            <div class="alert alert-info">Giỏ hàng của bạn đang trống!</div>
        @endif
    </div>
</div>

<script>
function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN').format(amount) + ' đ';
}

function updateTotal() {
    let total = 0;
    document.querySelectorAll('tbody tr').forEach(row => {
        const checkbox = row.querySelector('.item-checkbox');
        if (checkbox && checkbox.checked) {
            const price = parseInt(row.dataset.price);
            const quantity = parseInt(row.querySelector('.quantity-input').value);
            const subtotal = price * quantity;
            row.querySelector('.subtotal').innerText = formatCurrency(subtotal);
            total += subtotal;
        }
    });
    document.getElementById('total-price').innerText = formatCurrency(total);
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.item-checkbox').forEach(cb => cb.addEventListener('change', updateTotal));
    document.querySelectorAll('.quantity-input').forEach(input => input.addEventListener('input', updateTotal));
document.getElementById('select-all').addEventListener('change', function() {
        document.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = this.checked);
        updateTotal();
    });

    // ==== XÓA SẢN PHẨM BẰNG AJAX ====
    document.querySelectorAll('.btn-delete-item').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            if (!confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) return;

            fetch(`/cart/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                if (!res.ok) throw new Error('Lỗi từ server');
                return res.json();
            })
            .then(data => {
                if (data.success) {
                    // Xóa dòng trong bảng
                    const row = document.querySelector(`tr[data-id="${id}"]`);
                    if (row) row.remove();

                    // Cập nhật tổng tiền
                    updateTotal();

                    // Thông báo thành công
                    alert(data.message);
                } else {
                    // Thông báo lỗi từ server
                    alert(data.message || 'Không thể xóa sản phẩm.');
                }
            })
            .catch((err) => {
                console.error(err);
                alert('Đã xảy ra lỗi khi xóa sản phẩm.');
            });
        });
    });

    updateTotal(); // Gọi hàm cập nhật tổng tiền
});
</script>
@endsection
