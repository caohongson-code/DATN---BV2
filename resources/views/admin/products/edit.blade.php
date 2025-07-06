@extends('admin.layouts.app')

@section('title', 'Sửa sản phẩm')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0 rounded-3">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">Sửa sản phẩm: {{ $product->product_name }}</h4>
                <a href="{{ route('products.index') }}" class="btn btn-sm btn-light border shadow-sm text-dark d-flex align-items-center">
                    <i class="fas fa-arrow-left me-2 text-muted"></i>
                    <span class="fw-normal">Quay lại danh sách</span>
                </a>
            </div>

            <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="product_name" class="form-label fw-semibold">
                        <i class="fas fa-tag text-primary me-1"></i>Tên sản phẩm
                    </label>
                    <input type="text" class="form-control" id="product_name" name="product_name"
                           value="{{ old('product_name', $product->product_name) }}" required>
                </div>

                <div class="mb-3">
                    <label for="category_id" class="form-label fw-semibold">
                        <i class="fas fa-list-alt text-success me-1"></i>Danh mục sản phẩm
                    </label>
                    <select class="form-select border-success shadow-sm" id="category_id" name="category_id" required>
                        <option value="">-- Chọn danh mục --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label fw-semibold">Giá</label>
                        <input type="number" class="form-control" id="price" name="price"
                               value="{{ old('price', $product->price) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="discount_price" class="form-label fw-semibold">Giá khuyến mãi</label>
                        <input type="number" class="form-control" id="discount_price" name="discount_price"
                               value="{{ old('discount_price', $product->discount_price) }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label fw-semibold">Ảnh sản phẩm</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    @if($product->image)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->product_name }}"
                                 style="max-height: 150px; object-fit: contain;">
                        </div>
                    @endif
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="quantity" class="form-label fw-semibold">Số lượng</label>
                        <input type="number" class="form-control" id="quantity" name="quantity"
                               value="{{ old('quantity', $product->quantity) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label fw-semibold">
                            <i class="fas fa-toggle-on text-info me-1"></i>Trạng thái
                        </label>
                        <select class="form-select border-info shadow-sm" id="status" name="status" required>
                            <option value="1" {{ old('status', $product->status) == '1' ? 'selected' : '' }}>✅ Hiển thị</option>
                            <option value="0" {{ old('status', $product->status) == '0' ? 'selected' : '' }}>❌ Ẩn</option>
                        </select>
                    </div>
                </div>

                <hr>
                <h5 class="fw-bold mt-4 mb-3">Biến thể sản phẩm</h5>

                <div id="variants-container">
                    @foreach ($product->variants as $index => $variant)
                        <div class="card p-3 mb-4 border shadow-sm variant-item">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-2">
                                    <label class="form-label">Màu sắc</label>
                                    <select name="variants[{{ $index }}][color_id]" class="form-select" required>
                                        <option value="">-- Chọn màu --</option>
                                        @foreach ($colors as $color)
                                            <option value="{{ $color->id }}"
                                                {{ old("variants.$index.color_id", $variant->color_id) == $color->id ? 'selected' : '' }}>
                                                {{ $color->value }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">RAM</label>
                                    <select name="variants[{{ $index }}][ram_id]" class="form-select" required>
                                        <option value="">-- Chọn RAM --</option>
                                        @foreach ($rams as $ram)
                                            <option value="{{ $ram->id }}"
                                                {{ old("variants.$index.ram_id", $variant->ram_id) == $ram->id ? 'selected' : '' }}>
                                                {{ $ram->value }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Dung lượng</label>
                                    <select name="variants[{{ $index }}][storage_id]" class="form-select" required>
                                        <option value="">-- Chọn --</option>
                                        @foreach ($storages as $storage)
                                            <option value="{{ $storage->id }}"
                                                {{ old("variants.$index.storage_id", $variant->storage_id) == $storage->id ? 'selected' : '' }}>
                                                {{ $storage->value }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Giá</label>
                                    <input type="number" name="variants[{{ $index }}][price]" class="form-control"
                                        value="{{ old("variants.$index.price", $variant->price) }}" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Số lượng</label>
                                    <input type="number" name="variants[{{ $index }}][quantity]" class="form-control"
                                        value="{{ old("variants.$index.quantity", $variant->quantity) }}" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Ảnh (nếu thay)</label>
                                    <input type="file" name="color_images[{{ $variant->color_id }}]" class="form-control" accept="image/*">
                                    <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant->id }}">
                                    <input type="hidden" name="variants[{{ $index }}][old_image]" value="{{ $variant->image }}">
                                    @if($variant->image)
                                        <img src="{{ asset('storage/' . $variant->image) }}" alt="variant image"
                                            class="img-thumbnail mt-1" style="max-height: 60px;">
                                    @endif
                                </div>
                                <div class="col-12 text-end">
                                    <button type="button" class="btn btn-outline-danger btn-sm remove-variant">
                                        <i class="fas fa-times"></i> Xoá biến thể
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="button" class="btn btn-outline-primary btn-sm" id="add-variant-btn">
                    <i class="fas fa-plus-circle me-1"></i> Thêm biến thể
                </button>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary px-4 py-2 shadow-sm">
                        <i class="fas fa-save me-1"></i> Cập nhật sản phẩm
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@php $variantIndex = count($product->variants); @endphp

<script>
document.addEventListener('DOMContentLoaded', function () {
    let index = {{ $variantIndex }};

    const template = () => `
    <div class="card p-3 mb-4 border shadow-sm variant-item">
        <div class="row g-3 align-items-end">
            <div class="col-md-2">
                <label class="form-label">Màu sắc</label>
                <select name="variants[\${index}][color_id]" class="form-select" required>
                    <option value="">-- Chọn màu --</option>
                    @foreach ($colors as $color)
                        <option value="{{ $color->id }}">{{ $color->value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">RAM</label>
                <select name="variants[\${index}][ram_id]" class="form-select" required>
                    <option value="">-- Chọn RAM --</option>
                    @foreach ($rams as $ram)
                        <option value="{{ $ram->id }}">{{ $ram->value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Dung lượng</label>
                <select name="variants[\${index}][storage_id]" class="form-select" required>
                    <option value="">-- Chọn --</option>
                    @foreach ($storages as $storage)
                        <option value="{{ $storage->id }}">{{ $storage->value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Giá</label>
                <input type="number" name="variants[\${index}][price]" class="form-control" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Số lượng</label>
                <input type="number" name="variants[\${index}][quantity]" class="form-control" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Ảnh</label>
                <input type="file" name="color_images[new_\${index}]" class="form-control" accept="image/*">
            </div>
            <div class="col-12 text-end">
                <button type="button" class="btn btn-outline-danger btn-sm remove-variant">
                    <i class="fas fa-times"></i> Xoá biến thể
                </button>
            </div>
        </div>
    </div>`;

    document.getElementById('add-variant-btn').addEventListener('click', function () {
        const container = document.getElementById('variants-container');
        const wrapper = document.createElement('div');
        wrapper.innerHTML = template().replace(/\${index}/g, index);
        container.appendChild(wrapper.firstElementChild);
        index++;
    });

    document.addEventListener('click', function (e) {
        if (e.target.closest('.remove-variant')) {
            e.target.closest('.variant-item').remove();
        }
    });
});
</script>

@endsection
