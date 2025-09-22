@extends('client.user.dashboard')

@section('dashboard-content')
    <div class="container mt-4">
        <h4 class="mb-4">📦 Nhập thông tin trả hàng </h4>

        {{-- Hiển thị lỗi --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Hiển thị thông báo thành công --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Thông tin yêu cầu trả hàng --}}
        <div class="mb-4 p-3 border rounded bg-light">
            <h5 class="mb-3">📄 Thông tin yêu cầu trả hàng</h5>
            <p><strong>Lý do trả hàng:</strong> {{ $returnRequest->reason ?? 'Không có lý do' }}</p>

            @if (!empty($returnRequest->images))
                @php
                    $images = json_decode($returnRequest->images, true);
                @endphp
                @if (is_array($images))
                    <div class="mb-2">
                        <strong>Ảnh minh họa:</strong><br>
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            @foreach ($images as $image)
                                <img src="{{ asset('storage/' . $image) }}" alt="Ảnh trả hàng"
                                    style="width: 100px; height: 100px; object-fit: cover;" class="border rounded">
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif

            <p class="mt-2"><strong>Trạng thái:</strong>
                @php
                    $statusLabels = [
                        'pending' => '⏳ Đang chờ xử lý',
                        'approved' => '✅ Đã chấp nhận',
                        'rejected' => '❌ Đã từ chối',
                        'canceled' => '🚫 Đã hủy',
                        'completed' => '✅ Đã hoàn tất',
                    ];
                @endphp
                <span
                    class="badge bg-secondary">{{ $statusLabels[$returnRequest->status] ?? ucfirst($returnRequest->status) }}</span>
            </p>
        </div>

        {{-- Địa chỉ người gửi (Shop) --}}
        <div class="mb-4 p-3 border rounded">
            <h5>🏢 Địa chỉ người gửi (Shop)</h5>
            <p>
                <strong>{{ $shopInfo->name ?? 'Không có thông tin shop' }}</strong><br>
                {{ $shopInfo->address ?? 'Chưa cập nhật địa chỉ' }}<br>
                <strong>Điện thoại:</strong> {{ $shopInfo->phone ?? 'Chưa có' }}<br>
                @if ($shopInfo->email)
                    <strong>Email:</strong> {{ $shopInfo->email }}<br>
                @endif
                @if ($shopInfo->support_time)
                    <small><em>Giờ hỗ trợ: {{ $shopInfo->support_time }}</em></small>
                @endif
            </p>
        </div>

        {{-- Thông tin người nhận (người mua) --}}
        <div class="mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white fw-bold">
                    📌 Thông tin người nhận
                </div>
                <div class="card-body">
                    <p><strong>Họ tên:</strong> {{ Auth::user()->full_name }}</p>
                    <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                    <p><strong>Số điện thoại:</strong> {{ Auth::user()->phone ?? 'Chưa có' }}</p>
                    <p><strong>Địa chỉ:</strong> {{ Auth::user()->address ?? 'Chưa có' }}</p>
                    <a href="{{ route('user.profile') }}" class="btn btn-sm btn-warning mt-2">
                        ✏️ Cập nhật thông tin
                    </a>
                </div>
            </div>
        </div>

        {{-- Danh sách sản phẩm trong đơn hàng --}}
 <div class="mb-4 p-3 border rounded bg-light">
    <h5>🛒 Sản phẩm trong đơn hàng</h5>

    @php
        $subtotal = 0;
    @endphp

    @foreach ($returnRequest->order->orderDetails as $item)
        @php
            $variant = $item->productVariant;
            $product = $variant->product ?? null;
            $image = $variant->image
                ? asset('storage/' . $variant->image)
                : ($product && $product->image
                    ? asset('storage/' . $product->image)
                    : asset('images/default.jpg'));

            // ✅ Tính giá từng sản phẩm (ưu tiên giá giảm)
            $price = $variant->discount_price && $variant->discount_price < $variant->price
                ? $variant->discount_price
                : $variant->price;

            $lineTotal = $price * $item->quantity;
            $subtotal += $lineTotal;
        @endphp

        <div class="d-flex mb-3 align-items-center border-bottom pb-2">
            <img src="{{ $image }}" alt="Ảnh sản phẩm"
                style="width: 80px; height: 80px; object-fit: cover; margin-right: 15px;" class="rounded border">

            <div>
                <strong>{{ $product->product_name ?? 'Không rõ sản phẩm' }}</strong><br>

                {{-- Chi tiết biến thể --}}
                <span class="text-muted small">
                    {{ $variant->ram->value ?? '' }}
                    {{ $variant->storage->value ?? '' }}
                    {{ $variant->color->value ?? '' }}
                </span><br>

                Số lượng: {{ $item->quantity }}<br>

                Giá:
                @if ($variant->discount_price && $variant->discount_price < $variant->price)
                    <span class="text-danger fw-bold">{{ number_format($variant->discount_price, 0, ',', '.') }}₫</span>
                    <del class="text-muted">{{ number_format($variant->price, 0, ',', '.') }}₫</del>
                @else
                    {{ number_format($variant->price ?? 0, 0, ',', '.') }}₫
                @endif
            </div>

            <div class="ms-auto fw-bold">
                {{ number_format($lineTotal, 0, ',', '.') }}₫
            </div>
        </div>
    @endforeach

    {{-- ✅ Phần tổng tiền --}}
    @php
        $shippingFee = 30000;

        // Lấy khuyến mãi nếu đơn hàng có (giả sử có cột promotion_id trong bảng orders)
        $discountAmount = 0;
        if ($returnRequest->order->promotion) {
            $promotion = $returnRequest->order->promotion;
            if ($promotion->discount_type === 'percentage') {
                $discountAmount = $subtotal * ($promotion->discount_value / 100);
            } elseif ($promotion->discount_type === 'fixed') {
                $discountAmount = $promotion->discount_value;
            }
        }

        $total = max(0, $subtotal - $discountAmount + $shippingFee);
    @endphp

    <div class="mt-3 p-3 bg-white rounded border">
        <div class="d-flex justify-content-between">
            <span>Tạm tính:</span>
            <strong>{{ number_format($subtotal, 0, ',', '.') }}₫</strong>
        </div>
        <div class="d-flex justify-content-between">
            <span>Phí vận chuyển:</span>
            <strong>{{ number_format($shippingFee, 0, ',', '.') }}₫</strong>
        </div>
        @if ($discountAmount > 0)
            <div class="d-flex justify-content-between text-success">
                <span>Khuyến mãi ({{ $promotion->code }}):</span>
                <strong>-{{ number_format($discountAmount, 0, ',', '.') }}₫</strong>
            </div>
        @endif
        <hr>
        <div class="d-flex justify-content-between">
            <span class="fw-bold">Tổng thanh toán:</span>
            <span class="fw-bold text-danger fs-5">{{ number_format($total, 0, ',', '.') }}₫</span>
        </div>
    </div>
</div>


        {{-- Form gửi mã vận đơn --}}
        <form action="{{ route('user.return.submit_tracking', $returnRequest->id) }}" method="POST"
            enctype="multipart/form-data" class="p-3 border rounded shadow-sm bg-white">
            @csrf

            {{-- <div class="mb-3">
                <label for="tracking_number" class="form-label fw-bold">🔁 Nhập mã vận đơn trả hàng</label>
                <input type="text" name="tracking_number" class="form-control" required
                    placeholder="Nhập mã vận đơn (ví dụ: PPGH34567890)">
            </div> --}}

            {{-- <div class="mb-3">
                <label for="shipping_images" class="form-label fw-bold">📷 Ảnh gói hàng đã gửi</label>
                <input type="file" name="shipping_images[]" class="form-control" multiple accept="image/*" required>
                <small class="text-muted">Chọn 1 hoặc nhiều ảnh chứng minh bạn đã gửi hàng</small>
            </div> --}}
            <div class="mb-3">
    <label for="require_images" class="form-label fw-bold">📷 Ảnh chứng minh gửi hàng</label>
    <select name="require_images" id="require_images" class="form-select">
        <option value="yes" selected>Yêu cầu gửi ảnh chứng minh đã gửi hàng</option>
        <option value="no">Không cần ảnh (shop đã hủy đơn – hoàn tiền trực tiếp)</option>
    </select>
</div>

<div class="mb-3" id="shipping_images_wrapper">
    <label for="shipping_images" class="form-label fw-bold">📦 Ảnh gói hàng đã gửi</label>
    <input type="file" name="shipping_images[]" class="form-control" multiple accept="image/*" >
    <small class="text-muted">Chọn 1 hoặc nhiều ảnh chứng minh bạn đã gửi hàng</small>
</div>


            <div class="mb-3">
                <label for="bank_name" class="form-label fw-bold">🏦 Chọn phương thức hoàn tiền</label>
                <select name="bank_name" class="form-select" id="bank_name_select" required>
                    <option value="">-- Chọn phương thức --</option>
                    <option value="Vietcombank">Vietcombank</option>
                    <option value="VietinBank">VietinBank</option>
                    <option value="BIDV">BIDV</option>
                    <option value="Techcombank">Techcombank</option>
                    <option value="MB Bank">MB Bank</option>
                    <option value="ACB">ACB</option>
                    <option value="TPBank">TPBank</option>
                    <option value="Sacombank">Sacombank</option>
                    <option value="Agribank">Agribank</option>
                    <option value="VPBank">VPBank</option>
                    <option value="MoMo" {{ old('bank_name') == 'MoMo' ? 'selected' : '' }}>MoMo</option>
                </select>
            </div>

            <div class="mb-3" id="bank_account_wrapper">
                <label for="bank_account" class="form-label fw-bold" id="bank_account_label">
                    🔢 Số tài khoản ngân hàng / SĐT MoMo
                </label>
                <input type="text" name="bank_account" class="form-control"
                    id="bank_account_input"
                    value="{{ old('bank_account') }}"
                    placeholder="Nhập số tài khoản hoặc SĐT MoMo" required>
                <small class="text-muted" id="bank_account_hint">Vui lòng nhập đúng thông tin để nhận hoàn tiền</small>
                <div class="invalid-feedback" id="bank_account_error"></div>
            </div>

            <button type="submit" class="btn btn-primary">
                📤 Gửi yêu cầu xác nhận gửi hàng
            </button>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const select = document.getElementById('bank_name_select');
        const label = document.getElementById('bank_account_label');
        const input = document.getElementById('bank_account_input');
        const hint = document.getElementById('bank_account_hint');

        function updateFieldDisplay() {
            const value = select.value;

            if (value === 'MoMo') {
                label.textContent = '📱 Số điện thoại MoMo';
                input.placeholder = 'Nhập SĐT MoMo';
                hint.textContent = 'SĐT MoMo phải chính xác để nhận hoàn tiền';
            } else {
                label.textContent = '🔢 Số tài khoản ngân hàng';
                input.placeholder = 'Nhập số tài khoản ngân hàng';
                hint.textContent = 'Số tài khoản cần đúng và đầy đủ';
            }
        }

        select.addEventListener('change', updateFieldDisplay);
        updateFieldDisplay();
    });
</script>
@endpush
