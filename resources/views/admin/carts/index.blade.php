@extends('admin.layouts.app')

@section('title', 'Danh sách giỏ hàng')

@section('content')
<div class="container-fluid py-4">
    <h2 class="mb-4">Danh sách giỏ hàng</h2>

    <form action="{{ route('carts.index') }}" method="GET" class="row g-2 mb-4">
        <div class="col-md-4">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Tìm theo tên, email...">
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">-- Trạng thái --</option>
                @foreach(\App\Models\CartStatus::all() as $status)
                    <option value="{{ $status->id }}" {{ request('status') == $status->id ? 'selected' : '' }}>
                        {{ $status->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100">
                <i class="fas fa-search me-1"></i> Tìm
            </button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>ID</th>
                    <th>Người dùng</th>
                    <th>Email</th>
                    <th>Số SP</th>
                    <th>Tổng SL</th>
                    <th>Tổng tiền</th>
                    <th>Ngày tạo</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($carts as $cart)
                    <tr>
                        <td class="text-center">{{ $cart->id }}</td>
                        <td>{{ $cart->account->full_name ?? 'Không rõ' }}</td>
                        <td>{{ $cart->account->email ?? 'Không rõ' }}</td>
                        <td class="text-center">{{ $cart->details->count() }}</td>
                        <td class="text-center">{{ $cart->details->sum('quantity') }}</td>
                        <td>{{ number_format($cart->details->sum(fn($d) => $d->quantity * ($d->productVariant->price ?? 0))) }}₫</td>
                        <td>
                            {{ $cart->created_at ? $cart->created_at->format('d/m/Y') : 'Chưa xác định' }}
                        </td>
                      <td class="text-center">
    @php
        $badgeMap = [
            'active' => 'bg-success',
            'ordered' => 'bg-primary',
            'abandoned' => 'bg-secondary',
            // fallback
            'default' => 'bg-dark'
        ];
        $statusSlug = $status->slug ?? 'default'; // nếu bạn có cột slug, nếu không thì map theo id
        $badgeClass = $badgeMap[$statusSlug] ?? $badgeMap['default'];
    @endphp

    @if ($status)
        <span class="badge {{ $badgeClass }} px-3 py-2 rounded-pill">
            <i class="fas fa-circle me-1"></i> {{ $status->name }}
        </span>
    @else
        <span class="badge bg-dark">Không rõ</span>
    @endif
</td>

                        <td class="text-center">
                            <a href="{{ route('carts.show', $cart->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye me-1"></i> Chi tiết
                            </a>
                            <form action="{{ route('carts.destroy', $cart->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xoá giỏ hàng này?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">Không có giỏ hàng nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
