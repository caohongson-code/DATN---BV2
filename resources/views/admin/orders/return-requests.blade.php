@extends('admin.layouts.app')

@section('title', 'Yêu cầu trả hàng')

@section('content')
    <div class="container-fluid px-4">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <h4 class="fw-bold mb-0"><i class="fas fa-undo-alt me-2 text-danger"></i> Yêu cầu trả hàng</h4>
            </div>

            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th>Mã đơn</th>
                                <th>Khách hàng</th>
                                <th>Lý do</th>
                                <th>Ảnh</th>
                                <th>Trạng thái</th>
                                <th>Tiến trình</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $statusLabel = [
                                    // Trạng thái yêu cầu
                                    'pending' => 'Đang chờ',
                                    'approved' => 'Đã duyệt',
                                    'rejected' => 'Từ chối',

                                    // Trạng thái tiến trình
                                    'shipping_pending' => 'Chờ gửi hàng',
                                    'shop_received' => 'Shop đã nhận hàng',
                                    'checking' => 'Đang kiểm tra',
                                    'refunded' => 'Đã hoàn tiền',
                                ];

                                $progressLabel = [
                                    'approved' => 'Đã duyệt',
                                    'shipping_pending' => 'Chờ gửi hàng',
                                    'shop_received' => 'Shop đã nhận hàng',
                                    'checking' => 'Đang kiểm tra',
                                    'refunded' => 'Đã hoàn tiền',
                                ];

                                $statusViMap = [
                                    'shipping_pending' => 'Chờ gửi hàng',
                                    'shop_received' => 'Đã nhận hàng',
                                    'checking' => 'Đã kiểm hàng',
                                    'refunded' => 'Đã hoàn tiền',
                                ];
                            @endphp

                            @forelse($requests as $request)
                                <tr>
                                    <td>#{{ $request->order_id }}</td>
                                    <td class="text-start">
                                        <strong>{{ $request->order->account->full_name ?? 'Không rõ' }}</strong><br>
                                        <small class="text-muted">{{ $request->order->account->email ?? '' }}</small>
                                    </td>
                                    <td>{{ $request->reason }}</td>
                                    <td>
                                        @foreach (json_decode($request->images, true) ?? [] as $img)
                                            <img src="{{ asset('storage/' . $img) }}" width="60">
                                        @endforeach
                                    </td>
                                    <td>
                                        <span
                                            class="badge
                                        @if ($request->status == 'pending') bg-warning
                                        @elseif($request->status == 'approved') bg-success
                                        @else bg-danger @endif">
                                            {{ $statusLabel[$request->status] ?? ucfirst($request->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $latestProgress = $request->progresses->last();
                                        @endphp
                                        @if ($latestProgress)
                                            <div class="text-start small">
                                                <div>
                                                    <strong>{{ $progressLabel[$latestProgress->status] ?? ucfirst($latestProgress->status) }}</strong>
                                                </div>
                                                <div class="text-muted">
                                                    {{ \Carbon\Carbon::parse($latestProgress->completed_at)->format('d/m/Y H:i') }}
                                                </div>
                                                <div>{{ $latestProgress->note }}</div>
                                            </div>
                                        @else
                                            <span class="text-muted">Chưa cập nhật</span>
                                        @endif
                                    </td>
                                   <td>
    @if ($request->status == 'pending')
        {{-- Chưa duyệt: Hiện nút Duyệt / Từ chối --}}
        <a href="{{ route('admin.return_requests.approve', $request->id) }}"
            class="btn btn-sm btn-success"
            onclick="return confirm('Duyệt yêu cầu này?')">
            <i class="fas fa-check"></i>
        </a>
        <a href="{{ route('admin.return_requests.reject', $request->id) }}"
            class="btn btn-sm btn-danger"
            onclick="return confirm('Từ chối yêu cầu này?')">
            <i class="fas fa-times"></i>
        </a>

    @elseif(in_array($request->status, ['approved', 'refunded']))
        {{-- Luôn hiện nút Chi tiết khi đã duyệt hoặc đã hoàn tiền --}}
        <a href="{{ route('admin.orders.refund_detail', $request->id) }}"
            class="btn btn-sm btn-info mb-1">
            <i class="fas fa-eye me-1"></i> Chi tiết
        </a>

        @php
            $allStatuses = ['shipping_pending', 'shop_received', 'checking', 'refunded'];
            $latestProgress = $request->progresses->last();
            $currentIndex = $latestProgress ? array_search($latestProgress->status, $allStatuses) : -1;
            $nextStatus = $currentIndex !== false && isset($allStatuses[$currentIndex + 1])
                ? $allStatuses[$currentIndex + 1]
                : null;
        @endphp

        @if ($latestProgress && $latestProgress->status === 'checking')
            {{-- Nếu tiến trình đang kiểm tra → hiện nút Hoàn tiền --}}
            <a href="{{ route('admin.orders.refund_form', $request->id) }}"
                class="btn btn-sm btn-warning confirm-refund">
                <i class="fas fa-money-bill-wave me-1"></i> Hoàn tiền
            </a>

        @elseif($nextStatus)
            {{-- Nếu tiến trình chưa xong → hiện nút cập nhật bước tiếp theo --}}
            <form method="POST"
                action="{{ route('admin.orders.progress', $request->id) }}"
                class="confirm-progress mt-1">
                @csrf
                <input type="hidden" name="status" value="{{ $nextStatus }}">
                <input type="text" name="note" class="form-control form-control-sm mb-1"
                    placeholder="Ghi chú (tuỳ chọn)">
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus-circle me-1"></i>
                    {{ $statusViMap[$nextStatus] ?? ucfirst($nextStatus) }}
                </button>
            </form>

        
        @endif

    @else
        {{-- rejected hoặc trạng thái khác --}}
        <em class="text-muted">Không khả dụng</em>
    @endif
</td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-muted text-center">Chưa có yêu cầu trả hàng.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Xác nhận khi submit form tiến trình
            document.querySelectorAll('form.confirm-progress').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    if (confirm("Bạn có chắc chắn muốn cập nhật tiến trình không?")) {
                        form.submit();
                    }
                });
            });

            // Xác nhận khi click nút Hoàn tiền
            document.querySelectorAll('.confirm-refund').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault(); // ⛔ chặn chuyển trang ngay
                    const url = btn.getAttribute('href');
                    if (confirm("Bạn có chắc chắn muốn hoàn tiền cho đơn hàng này không?")) {
                        window.location.href = url; // ✅ chỉ chuyển khi bấm OK
                    }
                });
            });
        });
    </script>
@endpush
