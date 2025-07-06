@extends('admin.layouts.app')

@section('title', 'Chi tiết sản phẩm')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Chi tiết sản phẩm: {{ $product->product_name }}</h2>

    <a href="{{ route('products.index') }}" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left me-2"></i> Quay lại danh sách
    </a>

    <div class="card">
        <div class="card-body">
            <div class="row">
                {{-- Ảnh đại diện --}}
                <div class="col-md-4 text-center">
                    <img id="main-image"
                         src="{{ asset('storage/' . $product->image) }}"
                         alt="Ảnh sản phẩm"
                         class="img-fluid rounded border"
                         style="max-height: 250px; object-fit: contain;">
                </div>

                {{-- Thông tin sản phẩm --}}
                <div class="col-md-8">
                    <table class="table table-borderless">
                        <tr><th>ID:</th><td>{{ $product->id }}</td></tr>
                        <tr><th>Tên sản phẩm:</th><td>{{ $product->product_name }}</td></tr>
                        <tr><th>Giá:</th><td id="variant-price">{{ number_format($product->price, 0, ',', '.') }} đ</td></tr>
                        <tr><th>Số lượng:</th><td id="variant-quantity">{{ $product->quantity }}</td></tr>
                        <tr><th>RAM:</th><td id="variant-ram">-</td></tr>
                        <tr><th>Bộ nhớ:</th><td id="variant-storage">-</td></tr>
                        <tr><th>Màu sắc:</th><td id="variant-color">-</td></tr>
                        <tr>
                            <th>Trạng thái:</th>
                            <td>
                                @if($product->status)
                                    <span class="badge bg-success">Hiển thị</span>
                                @else
                                    <span class="badge bg-secondary">Ẩn</span>
                                @endif
                            </td>
                        </tr>
                        <tr><th>Mô tả:</th><td>{!! nl2br(e($product->description)) !!}</td></tr>
                    </table>

                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning me-2">
                        <i class="fas fa-edit me-1"></i> Sửa sản phẩm
                    </a>

                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash-alt me-1"></i> Xóa sản phẩm
                        </button>
                    </form>
                </div>
            </div>

            <hr>
            <h5 class="fw-bold mb-3">Chọn biến thể:</h5>
            <div class="d-flex flex-wrap gap-2">
                @foreach ($product->variants as $variant)
                    <button type="button"
                            class="btn btn-outline-dark variant-btn px-3 py-2"
                            style="border: 2px solid {{ $variant->color->code }}; background-color: {{ $variant->color->code }}22;"
                            data-variant="{{ json_encode([
                                'image' => $variant->image,
                                'price' => number_format($variant->price, 0, ',', '.') . ' đ',
                                'quantity' => $variant->quantity,
                                'ram' => $variant->ram->value,
                                'storage' => $variant->storage->value,
                                'color' => $variant->color->value
                            ]) }}">
                        {{ $variant->color->value }} - {{ $variant->ram->value }} - {{ $variant->storage->value }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- JS xử lý khi chọn biến thể --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const buttons = document.querySelectorAll('.variant-btn');

        buttons.forEach(button => {
            button.addEventListener('click', function () {
                const data = JSON.parse(this.getAttribute('data-variant'));

                // Cập nhật ảnh nếu có
                const imageTag = document.getElementById('main-image');
                if (data.image) {
                    imageTag.src = `/storage/${data.image}`;
                }

                document.getElementById('variant-price').textContent = data.price;
                document.getElementById('variant-quantity').textContent = data.quantity;
                document.getElementById('variant-ram').textContent = data.ram;
                document.getElementById('variant-storage').textContent = data.storage;
                document.getElementById('variant-color').textContent = data.color;
            });
        });
    });
</script>
@endsection
