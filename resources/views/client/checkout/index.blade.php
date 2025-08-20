@extends('client.layouts.app')

@section('content')
<div class="container py-5">
    <h3 class="mb-4">🛒 Xác nhận đơn hàng</h3>

    {{-- Thông báo lỗi --}}
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @php $outOfStock = false; @endphp

    @if(session('buy_now') || (!empty($cartItems) && count($cartItems)))
        <form method="POST" action="{{ route('checkout.store') }}" id="checkout-form"
              data-phone="{{ Auth::check() ? Auth::user()->phone : '' }}"
              data-address="{{ Auth::check() ? Auth::user()->address : '' }}">
            @csrf

            {{-- hidden selected items nếu từ giỏ hàng --}}
            @if(!$buyNow && !empty($cartItems))
                @foreach ($cartItems as $item)
                    <input type="hidden" name="selected_items[]" value="{{ $item['cart_detail_id'] }}">
                @endforeach
            @endif

            <div class="row">
                {{-- Thông tin người nhận --}}
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">📌 Thông tin người nhận</div>
                        <div class="card-body">
                            @auth
                                <p><strong>Họ tên:</strong> {{ Auth::user()->full_name }}</p>
                                <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                                <p><strong>Số điện thoại:</strong> {{ Auth::user()->phone ?? 'Chưa có' }}</p>
                                <p><strong>Địa chỉ:</strong> {{ Auth::user()->address ?? 'Chưa có' }}</p>
                                <a href="{{ route('user.profile') }}" class="btn btn-sm btn-warning mt-2">
                                    ✏️ Cập nhật thông tin
                                </a>
                            @else
                                <p class="text-danger">
                                    Bạn chưa đăng nhập. <a href="{{ route('login') }}">Đăng nhập ngay</a>
                                </p>
                            @endauth
                        </div>
                    </div>
                </div>

                {{-- Thông tin sản phẩm --}}
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">📦 Thông tin sản phẩm</div>
                        <div class="card-body">

                            {{-- Nếu mua ngay --}}
                            @if(session('buy_now') && isset($product))
                                @php
                                    $availableQty = $variant ? $variant->quantity : $product->quantity;
                                    $price = $variant
? (($variant->discount_price !== null && $variant->discount_price < $variant->price) ? $variant->discount_price : $variant->price)
    : (($product->discount_price !== null && $product->discount_price < $product->price) ? $product->discount_price : $product->price);

                                    if($availableQty < $buyNow['quantity']) $outOfStock = true;
                                @endphp

                                <p><strong>Tên sản phẩm:</strong> {{ $product->product_name }}</p>
                                @if($variant)
                                    <p><strong>Phiên bản:</strong>
                                        {{ $variant->ram->value ?? '' }} /
                                        {{ $variant->storage->value ?? '' }} /
                                        {{ $variant->color->value ?? '' }}
                                    </p>
                                @endif
                                <p><strong>Giá:</strong> {{ number_format($price, 0, ',', '.') }} VND</p>
                                <p><strong>Số lượng:</strong> {{ $buyNow['quantity'] }}</p>

                                @if($availableQty < $buyNow['quantity'])
                                    <div class="alert alert-danger mt-2">
                                        Sản phẩm này đã hết hàng hoặc không đủ số lượng!
                                    </div>
                                @endif

                            {{-- Nếu từ giỏ hàng --}}
                            @elseif(!empty($cartItems))
                            @php
                                $totalProducts = 0;
                                $totalItems = count($cartItems);
                            @endphp
                            @foreach($cartItems as $item)
                                @php
                                    $availableQty = $item['variant']
                                        ? $item['variant']->quantity
                                        : $item['product']->quantity;

                                    $itemPrice = $item['variant']
                                        ? (($item['variant']->discount_price !== null && $item['variant']->discount_price < $item['variant']->price)
                                            ? $item['variant']->discount_price
                                            : $item['variant']->price)
                                        : (($item['product']->discount_price !== null && $item['product']->discount_price < $item['product']->price)
                                            ? $item['product']->discount_price
                                            : $item['product']->price);

                                    $totalProducts += $item['quantity'];

                                    if($availableQty < $item['quantity']) $outOfStock = true;
                                @endphp
                                <hr>
                                <p><strong>Tên sản phẩm:</strong> {{ $item['product']->product_name }}</p>
                                @if($item['variant'])
                                    <p><strong>Phiên bản:</strong>
                                        {{ $item['variant']->ram->value ?? '' }} /
                                        {{ $item['variant']->storage->value ?? '' }} /
                                        {{ $item['variant']->color->value ?? '' }}
                                    </p>
                                @endif
                                <p><strong>Giá:</strong> {{ number_format($itemPrice, 0, ',', '.') }} VND</p>
                                <p><strong>Số lượng:</strong> {{ $item['quantity'] }}</p>

                                @if($availableQty < $item['quantity'])
                                    <div class="alert alert-danger mt-2">
                                        Sản phẩm này đã hết hàng hoặc không đủ số lượng!
                                    </div>
                                @endif
                            @endforeach

                            <hr>
                            <p><strong>🛒 Tổng loại sản phẩm:</strong> {{ $totalItems }}</p>
                            <p><strong>📦 Tổng số lượng sản phẩm:</strong> {{ $totalProducts }}</p>
                        @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Voucher --}}
            <div class="card mb-4">
                <div class="card-header bg-warning">🏱 Chọn voucher (nếu có)</div>
                <div class="card-body">
                    <select name="voucher_id" class="form-select" id="voucher-select">
                        <option value="" data-type="" data-value="0">-- Không sử dụng --</option>
                        @foreach($vouchers as $voucher)
                            <option value="{{ $voucher->id }}"
                                    data-type="{{ $voucher->discount_type }}"
                                    data-value="{{ $voucher->discount_value }}">
                                {{ $voucher->name }} - Mã: {{ $voucher->code }}
                                ({{ $voucher->discount_type == 'percent'
                                    ? $voucher->discount_value.'%' :
                                      number_format($voucher->discount_value,0,',','.') . ' VND' }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Tổng tiền --}}
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">💰 Tổng tiền</div>
                <div class="card-body">
                    <p><strong>Tạm tính:</strong> <span id="subtotal">{{ number_format($subtotal,0,',','.') }}</span> VND</p>
                    <p><strong>Phí vận chuyển:</strong> <span id="shipping">{{ number_format($shippingFee,0,',','.') }}</span> VND</p>
                    <p><strong>Giảm giá:</strong> <span id="discount">{{ $discount > 0 ? number_format($discount,0,',','.') : 0 }}</span> VND</p>
                    <hr>
                    <h5><strong>Thanh toán:</strong> <span id="total">{{ number_format($total,0,',','.') }}</span> VND</h5>
                </div>
            </div>

            {{-- Phương thức thanh toán --}}
            <div class="card mb-4">
                <div class="card-header bg-info text-white">💳 Phương thức thanh toán</div>
                <div class="card-body">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_method" value="cod" id="cod" checked>
                        <label class="form-check-label" for="cod">Thanh toán khi nhận hàng (COD)</label>
                    </div>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="radio" name="payment_method" value="momo" id="momo">
                        <label class="form-check-label" for="momo">Ví MoMo</label>
                    </div>
                    <div id="momo-qr-container" class="mt-3" style="display:none;">
                        <img id="momo-qr" src="" alt="QR MoMo" style="max-width:200px;">
                        <p><strong>Số tiền:</strong> <span id="momo-amount">0</span> VND</p>
                    </div>
                </div>
            </div>

            {{-- Xác nhận --}}
            <div class="text-end">
                @if($outOfStock)
                    <button type="button" class="btn btn-secondary btn-lg" disabled>Sản phẩm đã hết hàng</button>
                @else
                    <button type="submit" class="btn btn-success btn-lg">Xác nhận đặt hàng</button>
                @endif
            </div>
        </form>
    @else
        <div class="alert alert-warning">Không có sản phẩm nào để thanh toán.</div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const voucherSelect   = document.getElementById('voucher-select');
    const momoQRContainer = document.getElementById('momo-qr-container');
    const momoQR          = document.getElementById('momo-qr');
    const momoAmount      = document.getElementById('momo-amount');
    const paymentRadios   = document.querySelectorAll('input[name="payment_method"]');

    const shipping = {{ $shippingFee }};
    const cartItems = @json($cartItems);
    // $cartItems phải được truyền từ controller ra Blade, gồm: id, name, price, discount_price, quantity

    function calculateSubtotal() {
        let subtotal = 0;
        cartItems.forEach(item => {
            let price = item.discount_price > 0 ? item.discount_price : item.price;
            subtotal += price * item.quantity;
        });
        return subtotal;
    }

    function calculateTotal() {
        const subtotal = calculateSubtotal();
        const option   = voucherSelect.options[voucherSelect.selectedIndex];
        const type     = option ? option.dataset.type : null;
        const value    = parseFloat(option ? option.dataset.value : 0);

        const discountAmount = type === 'percent' ? subtotal * value / 100 : value;
        const total = subtotal + shipping - discountAmount;

        document.getElementById('subtotal').innerText =
            new Intl.NumberFormat('vi-VN').format(subtotal);
        document.getElementById('discount').innerText =
            new Intl.NumberFormat('vi-VN').format(discountAmount);
        document.getElementById('total').innerText =
            new Intl.NumberFormat('vi-VN').format(total) + ' VND';

        return total;
    }

    voucherSelect.addEventListener('change', calculateTotal);

    paymentRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            const total = calculateTotal();
            momoQRContainer.style.display = this.value === 'momo' ? 'block' : 'none';
            if (this.value === 'momo') {
                momoAmount.innerText = new Intl.NumberFormat('vi-VN').format(total);
                momoQR.src = "{{ url('/generate-momo-qr') }}?amount=" + total;
            }
        });
    });

    calculateTotal();
});
</script>
@endpush
