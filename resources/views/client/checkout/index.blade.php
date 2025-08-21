@extends('client.layouts.app')

@section('content')
    <div class="container py-5">
        <h3 class="mb-4">🛒 Xác nhận đơn hàng</h3>

        {{-- Hiển thị thông báo lỗi --}}
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        {{-- Hiển thị thông báo thành công --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('buy_now') || (isset($cartItems) && count($cartItems)))
            <form method="POST" action="{{ route('checkout.store') }}" id="checkout-form"
                data-phone="{{ Auth::user()->phone }}" data-address="{{ Auth::user()->address }}">
                @csrf

                @if (!$buyNow && isset($cartItems))
                    @foreach ($cartItems as $item)
                        <input type="hidden" name="selected_items[]" value="{{ $item['cart_detail_id'] }}">
                    @endforeach
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">📌 Thông tin người nhận</div>
                            <div class="card-body">
                                <p><strong>Họ tên:</strong> {{ Auth::user()->full_name }}</p>
                                <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                                <p><strong>Số điện thoại:</strong> {{ Auth::user()->phone ?? 'Chưa có' }}</p>
                                <p><strong>Địa chỉ:</strong> {{ Auth::user()->address ?? 'Chưa có' }}</p>
                                <a href="{{ route('user.profile') }}" class="btn btn-sm btn-warning mt-2">✏️ Cập nhật thông
                                    tin</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header bg-success text-white">📦 Thông tin sản phẩm</div>
                            <div class="card-body">
                                @if (session('buy_now') && isset($product))
                                    <p><strong>Tên sản phẩm:</strong> {{ $product->name }}</p>
                                    @if ($variant)
                                        <p><strong>Phiên bản:</strong> {{ $variant->ram->value ?? '' }} /
                                            {{ $variant->storage->value ?? '' }} / {{ $variant->color->value ?? '' }}</p>
                                        @php $price = $variant->price; @endphp
                                    @else
                                        <p><strong>Phiên bản:</strong> Không chọn</p>
                                        @php $price = $product->price; @endphp
                                    @endif
                                    <p><strong>Giá:</strong> {{ number_format($price, 0, ',', '.') }} VND</p>
                                    <p><strong>Số lượng:</strong> {{ $buyNow['quantity'] }}</p>
                                @elseif (isset($cartItems) && count($cartItems))
                                    @foreach ($cartItems as $item)
                                        <hr>
                                        <p><strong>Tên sản phẩm:</strong> {{ $item['product']->name }}</p>
                                        @if ($item['variant'])
                                            <p><strong>Phiên bản:</strong> {{ $item['variant']->ram->value ?? '' }} /
                                                {{ $item['variant']->storage->value ?? '' }} /
                                                {{ $item['variant']->color->value ?? '' }}</p>
                                        @endif
                                        <p><strong>Giá:</strong> {{ number_format($item['price'], 0, ',', '.') }} VND</p>
                                        <p><strong>Số lượng:</strong> {{ $item['quantity'] }}</p>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-warning">🏱 Chọn voucher (nếu có)</div>
                    <div class="card-body">
                        <select name="voucher_id" class="form-select" id="voucher-select">
                            <option value="" data-type="" data-value="0">-- Không sử dụng --</option>
                            @foreach ($vouchers as $voucher)
                                <option value="{{ $voucher->id }}" data-type="{{ $voucher->discount_type }}"

                                    data-value="{{ $voucher->discount_value }}">
                                    {{ $voucher->name }} - Mã: {{ $voucher->code }}
                                    ({{ $voucher->discount_type == 'percentage' ? $voucher->discount_value . '%' : number_format($voucher->discount_value, 0, ',', '.') . ' ₫' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-dark text-white">💰 Tổng tiền</div>
                    <div class="card-body">
                        <p><strong>Tạm tính:</strong> <span
                                id="subtotal">{{ number_format($subtotal, 0, ',', '.') }}</span> VND</p>
                        <p><strong>Phí vận chuyển:</strong> <span
                                id="shipping">{{ number_format($shippingFee, 0, ',', '.') }}</span> VND</p>
                        <p>Giảm giá: <span id="discount"></span> (<span id="discount-amount">{{ number_format($discount) }}</span> VND)</p>


                        <hr>
                        <h5><strong>Thanh toán:</strong> <span
                                id="total">{{ number_format($subtotal + $shippingFee, 0, ',', '.') }}</span>
                            VND</h5>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-info text-white">💳 Phương thức thanh toán</div>
                    <div class="card-body">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" value="cod"
                                id="cod">
                            <label class="form-check-label" for="cod">Thanh toán khi nhận hàng (COD)</label>
                        </div>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="radio" name="payment_method" value="momo"
                                id="momo">
                            <label class="form-check-label" for="momo">Ví MoMo</label>
                        </div>

                        <div id="momo-qr-container" class="mt-3" style="display: none;">
                            <img id="momo-qr" src="" alt="QR MoMo" style="max-width: 200px;">
                            <p><strong>Số tiền:</strong> <span id="momo-amount">0</span> VND</p>
                        </div>

                        <div class="form-check mt-2">
    @php
        $walletBalance = Auth::user()->wallet->balance ?? 0;
    @endphp
    <input class="form-check-input" type="radio" name="payment_method" value="wallet"
           id="wallet" {{ $walletBalance < $total ? 'disabled' : '' }}>
    <label class="form-check-label" for="wallet">
        Ví Của Tôi ({{ number_format($walletBalance, 0, ',', '.') }} VND)
        @if($walletBalance < $total)
            - Không đủ số dư
        @endif
    </label>
</div>


                    </div>
                </div>

                <div id="cod-info-confirmation" class="card mb-4" style="display: none;">
                    <div class="card-header bg-secondary text-white">✅ Xác nhận thông tin giao hàng</div>
                    <div class="card-body">
                        <p><strong>Họ tên:</strong> {{ Auth::user()->full_name }}</p>
                        <p><strong>Số điện thoại:</strong> {{ Auth::user()->phone ?? 'Chưa có' }}</p>
                        <p><strong>Địa chỉ:</strong> {{ Auth::user()->address ?? 'Chưa có' }}</p>
                        <p class="text-danger mt-2">🚎 Vui lòng đảm bảo thông tin trên là chính xác để giao hàng.</p>
                    </div>
                </div>
                <div id="wallet-info-confirmation" class="card mb-4" style="display: none;">
                    <div class="card-header bg-secondary text-white">✅ Xác nhận thanh toán qua ví</div>
                    <div class="card-body">
                        <p><strong>Số dư hiện tại:</strong>
                            {{ number_format(Auth::user()->wallet->balance ?? 0, 0, ',', '.') }} VND</p>
                        <p class="text-danger mt-2">🧾 Số tiền sẽ bị trừ trực tiếp từ ví nếu đặt hàng.</p>
                    </div>
                </div>

                <div class="text-end">
                  <button type="submit" class="btn btn-success btn-lg"
        onclick="return confirm('Bạn có chắc chắn muốn đặt hàng không?')">
    Xác nhận đặt hàng
</button>

                </div>
            </form>
        @else
            <div class="alert alert-warning">Không có sản phẩm nào để thanh toán.</div>
        @endif
    </div>
@endsection
@section('scripts')
    <script>
        
        document.addEventListener('DOMContentLoaded', function() {
            const voucherSelect = document.getElementById('voucher-select');
            const momoQRContainer = document.getElementById('momo-qr-container');
            const momoQR = document.getElementById('momo-qr');
            const momoAmount = document.getElementById('momo-amount');
            const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
            const codInfoBox = document.getElementById('cod-info-confirmation');
            const form = document.getElementById('checkout-form');

            const subtotal = {{ $subtotal }};
            const shipping = {{ $shippingFee }};


            function calculateTotal() {
                const option = voucherSelect.options[voucherSelect.selectedIndex];
                const type = option.getAttribute('data-type') || '';
                const value = parseFloat(option.getAttribute('data-value')) || 0;

                let discountAmount = 0;
                let discountText = '0';

                if (type === 'percentage') {
                    discountAmount = subtotal * value / 100;
                    discountText = value + '%';
                } else if (type === 'fixed') {
                    discountAmount = value;
                    discountText = new Intl.NumberFormat('vi-VN').format(value) + ' VND';
                }

                const total = Math.max(0, subtotal + shipping - discountAmount);

                document.getElementById('discount').innerText = discountText;
                document.getElementById('discount-amount').innerText = new Intl.NumberFormat('vi-VN').format(
                    discountAmount);
                document.getElementById('total').innerText = new Intl.NumberFormat('vi-VN').format(total) + ' VND';
                // ✅ Test giá trị
                console.log("=== Debug Voucher ===");
                console.log("Subtotal:", subtotal);
                console.log("Shipping:", shipping);
                console.log("Discount type:", type);
                console.log("Discount value:", value);
                console.log("Discount amount:", discountAmount);
                console.log("Total after discount:", total);
                return total;
            }

<<<<<<< HEAD
            // Gọi ngay khi load
            calculateTotal();

            // Khi đổi voucher thì tính lại
            voucherSelect.addEventListener('change', function() {
                const total = calculateTotal();
                const selectedPayment = document.querySelector('input[name="payment_method"]:checked');
                if (selectedPayment?.value === 'momo') {
                    momoAmount.innerText = new Intl.NumberFormat('vi-VN').format(total);
                    momoQR.src = "{{ url('/generate-momo-qr') }}?amount=" + total;
                }
            });
        

            document.querySelector('input[name="payment_method"]:checked')?.dispatchEvent(new Event('change'));

            form.addEventListener('submit', function(e) {
                const phone = form.dataset.phone;
                const address = form.dataset.address;
                const selectedPayment = document.querySelector('input[name="payment_method"]:checked');

                if (!selectedPayment) {
                    e.preventDefault();
                    alert('Vui lòng chọn phương thức thanh toán.');
                    return;
                }

                if (!phone || !address) {
                    e.preventDefault();
                    alert('Vui lòng cập nhật số điện thoại và địa chỉ trước khi đặt hàng.');
                    return;
                }

                if (!confirm('Bạn chắc chắn muốn xác nhận đặt hàng?')) {
                    e.preventDefault();
                    return;
                }
                if (selectedPayment.value === 'wallet') {
                    const walletBalance = {{ Auth::user()->wallet->balance ?? 0 }};
                    const total = calculateTotal();

                    if (walletBalance < total) {
                        e.preventDefault();
                        alert('❌ Số dư ví không đủ để thanh toán. Vui lòng nạp thêm.');
                        return;
                    }
                }


                // ❌ KHÔNG submit form momo ở đây nữa, vì đã được xử lý sau khi controller redirect sang momo_redirect.blade.php
                // ✅ Form sẽ post về route checkout.store như bình thường
            });
        });
    </script>
@endsection

