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
            @foreach ($returnRequest->order->orderDetails as $item)
                @php
                    $product = $item->productVariant->product ?? null;
                    $image =
                        $product && $product->image ? asset('storage/' . $product->image) : asset('images/default.jpg');
                @endphp
                <div class="d-flex mb-3 align-items-center border-bottom pb-2">
                    <img src="{{ $image }}" alt="Ảnh sản phẩm"
                        style="width: 80px; height: 80px; object-fit: cover; margin-right: 15px;" class="rounded border">
                    <div>
                        <strong>{{ $product->product_name ?? 'Không rõ sản phẩm' }}</strong><br>
                        Số lượng: {{ $item->quantity }}<br>
                        Giá: {{ number_format($item->productVariant->price ?? 0, 0, ',', '.') }}₫
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Form gửi mã vận đơn --}}
        <form action="{{ route('user.return.submit_tracking', $returnRequest->id) }}" method="POST"
            enctype="multipart/form-data" class="p-3 border rounded shadow-sm bg-white">
            @csrf

            <div class="mb-3">
                <label for="tracking_number" class="form-label fw-bold">🔁 Nhập mã vận đơn trả hàng</label>
                <input type="text" name="tracking_number" class="form-control" required
                    placeholder="Nhập mã vận đơn (ví dụ: PPGH34567890)">
            </div>

            <div class="mb-3">
                <label for="shipping_images" class="form-label fw-bold">📷 Ảnh gói hàng đã gửi</label>
                <input type="file" name="shipping_images[]" class="form-control" multiple accept="image/*" required>
                <small class="text-muted">Chọn 1 hoặc nhiều ảnh chứng minh bạn đã gửi hàng</small>
            </div>
          <div class="mb-3">
    <label for="bank_name" class="form-label fw-bold">🏦 Chọn ngân hàng</label>
    <select name="bank_name" class="form-select" required>
        <option value="">-- Chọn ngân hàng --</option>
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
        <option value="MoMo">MoMo</option>
    </select>
</div>

<div class="mb-3">
    <label for="bank_account" class="form-label fw-bold">🔢 Số tài khoản ngân hàng hoặc SĐT MoMo</label>
    <input type="text" name="bank_account" class="form-control"
        value="{{ old('bank_account', Auth::user()->bank_account ?? '') }}"
        placeholder="Nhập số tài khoản hoặc SĐT MoMo" required>
    <small class="text-muted">Đảm bảo nhập đúng để nhận hoàn tiền</small>
</div>


            <button type="submit" class="btn btn-primary">
                📤 Gửi yêu cầu xác nhận gửi hàng
            </button>
        </form>

    </div>
@endsection
