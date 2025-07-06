@extends('admin.layouts.app')

@section('title', 'Chi tiết giỏ hàng')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Chi tiết giỏ hàng của: {{ $cart->account->full_name ?? 'Không rõ' }}</h2>

    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Ảnh</th>
                <th>Sản phẩm</th>
                <th>Biến thể</th>
                <th>Đơn giá</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @forelse($cart->details as $item)
                @php
                    $price = $item->productVariant->price ?? 0;
                    $subtotal = $price * $item->quantity;
                    $total += $subtotal;
                @endphp
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>
                        @if($item->productVariant?->image)
                            <img src="{{ asset('storage/' . $item->productVariant->image) }}" width="60">
                        @elseif($item->product?->thumbnail)
                            <img src="{{ asset('storage/' . $item->product->thumbnail) }}" width="60">
                        @else
                            <span class="text-muted">Không ảnh</span>
                        @endif
                    </td>
                    <td>{{ $item->product->product_name ?? 'Không rõ' }}</td>
                    <td>
    @if ($item->productVariant)
        <div 
            data-bs-toggle="tooltip" 
            data-bs-placement="top"
            title="RAM: {{ $item->productVariant->ram->value ?? '---' }}, Storage: {{ $item->productVariant->storage->value ?? '---' }}, Màu: {{ $item->productVariant->color->value ?? '---' }}">
            
            <strong>RAM:</strong> {{ $item->productVariant->ram->value ?? '---' }}<br>
            <strong>Storage:</strong> {{ $item->productVariant->storage->value ?? '---' }}<br>
            <strong>Màu:</strong> {{ $item->productVariant->color->value ?? '---' }}
        </div>
    @else
        <span class="text-muted">Không có</span>
    @endif
</td>

                    <td>{{ number_format($price) }}₫</td>
                    <td>{{ $item->productVariant->quantity }}</td>
                    <td>{{ number_format($subtotal) }}₫</td>
                    <td>
                        <form action="{{ route('cart-details.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Xoá sản phẩm này?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Giỏ hàng trống.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" class="text-end fw-bold">Tổng tiền:</td>
                <td colspan="2" class="fw-bold text-danger">{{ number_format($total) }}₫</td>
            </tr>
        </tfoot>
    </table>

    <a href="{{ route('carts.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Quay lại
    </a>
    @if ($cart->status === 'active')
    <a href="{{ route('admin.orders.place', $cart->id) }}" class="btn btn-success ms-2">
        <i class="fas fa-shopping-cart me-1"></i> Đặt hàng từ giỏ này
    </a>
@endif

</div>
@endsection
