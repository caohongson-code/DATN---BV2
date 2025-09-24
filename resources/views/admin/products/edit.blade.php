@extends('admin.layouts.app')

@section('title', 'Sửa sản phẩm')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold mb-0">Sửa sản phẩm: {{ $product->product_name }}</h4>
                    <a href="{{ route('products.index') }}"
                        class="btn btn-sm btn-light border shadow-sm text-dark d-flex align-items-center">
                        <i class="fas fa-arrow-left me-2 text-muted"></i>
                        <span class="fw-normal">Quay lại danh sách</span>
                    </a>
                </div>

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
                        <label for="description" class="form-label fw-semibold">Mô tả chi tiết</label>
                        <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label fw-semibold">Ảnh sản phẩm</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        @if ($product->image)
                            <div class="mt-2">
                                <p class="text-muted mb-1">Ảnh hiện tại:</p>
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->product_name }}"
                                    style="max-height: 150px; object-fit: contain;" class="border rounded">
                                <input type="hidden" name="current_image" value="{{ $product->image }}">
                            </div>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label fw-semibold">
                                <i class="fas fa-toggle-on text-info me-1"></i>Trạng thái
                            </label>
                            <select class="form-select border-info shadow-sm" id="status" name="status" required>
                                <option value="1" {{ old('status', $product->status) == '1' ? 'selected' : '' }}>✅
                                    Hiển thị</option>
                                <option value="0" {{ old('status', $product->status) == '0' ? 'selected' : '' }}>❌ Ẩn
                                </option>
                            </select>
                        </div>
                    </div>

                    <hr>
                    <h5 class="fw-bold mt-4 mb-3">Biến thể sản phẩm</h5>

                    <div id="variants-container">
                        @if (old('variants'))
                            {{-- Hiển thị dữ liệu từ old() khi có lỗi validation --}}
                            @foreach (old('variants') as $index => $oldVariant)
                                <div class="card p-3 mb-4 border shadow-sm variant-item">
                                    <div class="row g-3 align-items-end">
                                        <div class="col-md-2">
                                            <label class="form-label">Màu sắc</label>
                                            <select name="variants[{{ $index }}][color_id]" class="form-select"
                                                required>
                                                <option value="">-- Chọn màu --</option>
                                                @foreach ($colors as $color)
                                                    <option value="{{ $color->id }}"
                                                        {{ $oldVariant['color_id'] == $color->id ? 'selected' : '' }}>
                                                        {{ $color->value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">RAM</label>
                                            <select name="variants[{{ $index }}][ram_id]" class="form-select"
                                                required>
                                                <option value="">-- Chọn RAM --</option>
                                                @foreach ($rams as $ram)
                                                    <option value="{{ $ram->id }}"
                                                        {{ $oldVariant['ram_id'] == $ram->id ? 'selected' : '' }}>
                                                        {{ $ram->value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Dung lượng</label>
                                            <select name="variants[{{ $index }}][storage_id]" class="form-select"
                                                required>
                                                <option value="">-- Chọn --</option>
                                                @foreach ($storages as $storage)
                                                    <option value="{{ $storage->id }}"
                                                        {{ $oldVariant['storage_id'] == $storage->id ? 'selected' : '' }}>
                                                        {{ $storage->value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Giá</label>
                                            <input type="number" name="variants[{{ $index }}][price]"
                                                class="form-control" value="{{ $oldVariant['price'] ?? '' }}" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Giá khuyến mãi</label>
                                            <input type="number" name="variants[{{ $index }}][discount_price]"
                                                class="form-control" value="{{ $oldVariant['discount_price'] ?? '' }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Số lượng</label>
                                            <input type="number" name="variants[{{ $index }}][quantity]"
                                                class="form-control" value="{{ $oldVariant['quantity'] ?? '' }}"
                                                required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Ảnh (nếu thay)</label>
                                            <input type="file" name="variants[{{ $index }}][image]"
                                                class="form-control" accept="image/*">
                                            @if (isset($oldVariant['id']))
                                                <input type="hidden" name="variants[{{ $index }}][id]"
                                                    value="{{ $oldVariant['id'] }}">
                                            @endif
                                            @if (isset($oldVariant['old_image']))
                                                <input type="hidden" name="variants[{{ $index }}][old_image]"
                                                    value="{{ $oldVariant['old_image'] }}">
                                                <img src="{{ asset('storage/' . $oldVariant['old_image']) }}"
                                                    alt="variant image" class="img-thumbnail mt-1"
                                                    style="max-height: 60px;">
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
                        @else
                            {{-- Hiển thị dữ liệu gốc từ database --}}
                            @foreach ($product->variants as $index => $variant)
                                <div class="card p-3 mb-4 border shadow-sm variant-item">
                                    <div class="row g-3 align-items-end">
                                        <div class="col-md-2">
                                            <label class="form-label">Màu sắc</label>
                                            <select name="variants[{{ $index }}][color_id]" class="form-select"
                                                required>
                                                <option value="">-- Chọn màu --</option>
                                                @foreach ($colors as $color)
                                                    <option value="{{ $color->id }}"
                                                        {{ $variant->color_id == $color->id ? 'selected' : '' }}>
                                                        {{ $color->value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">RAM</label>
                                            <select name="variants[{{ $index }}][ram_id]" class="form-select"
                                                required>
                                                <option value="">-- Chọn RAM --</option>
                                                @foreach ($rams as $ram)
                                                    <option value="{{ $ram->id }}"
                                                        {{ $variant->ram_id == $ram->id ? 'selected' : '' }}>
                                                        {{ $ram->value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Dung lượng</label>
                                            <select name="variants[{{ $index }}][storage_id]" class="form-select"
                                                required>
                                                <option value="">-- Chọn --</option>
                                                @foreach ($storages as $storage)
                                                    <option value="{{ $storage->id }}"
                                                        {{ $variant->storage_id == $storage->id ? 'selected' : '' }}>
                                                        {{ $storage->value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Giá</label>
                                            <input type="number" name="variants[{{ $index }}][price]"
                                                class="form-control" value="{{ $variant->price }}" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Giá khuyến mãi</label>
                                            <input type="number" name="variants[{{ $index }}][discount_price]"
                                                class="form-control" value="{{ $variant->discount_price }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Số lượng</label>
                                            <input type="number" name="variants[{{ $index }}][quantity]"
                                                class="form-control" value="{{ $variant->quantity }}" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Ảnh (nếu thay)</label>
                                            <input type="file" name="variants[{{ $index }}][image]"
                                                class="form-control" accept="image/*">
                                            <input type="hidden" name="variants[{{ $index }}][id]"
                                                value="{{ $variant->id }}">
                                            <input type="hidden" name="variants[{{ $index }}][old_image]"
                                                value="{{ $variant->image }}">
                                            @if ($variant->image)
                                                <img src="{{ asset('storage/' . $variant->image) }}" alt="variant image"
                                                    class="img-thumbnail mt-1" style="max-height: 60px;">
                                            @endif
                                            @error("variants.$index.image")
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
{{-- Album ảnh --}}
<div class="col-md-12">
    <label class="form-label">Album ảnh</label>

    {{-- Upload ảnh mới --}}
    <input type="file" name="variants[{{ $index }}][images][]" class="form-control" multiple accept="image/*">

    @if ($variant->images && $variant->images->count() > 0)
        <div class="d-flex flex-wrap gap-2 mt-2 variant-images">
            @foreach ($variant->images as $img)
                <div class="position-relative border rounded p-1" style="width: 80px; height: 80px;">
                    <img src="{{ asset('storage/' . $img->image) }}" 
     alt="Ảnh phụ" 
     class="variant-album-img"
     data-image="{{ asset('storage/' . $img->image) }}"
     style="width: 80px; height: 80px; object-fit: cover; border: 1px solid #ddd; border-radius: 5px; padding: 2px;">


                    
                    {{-- Hidden để giữ ảnh khi submit --}}
                    <input type="hidden" name="variants[{{ $index }}][existing_images][]" value="{{ $img->id }}">
                    
                    {{-- Nút xóa ảnh --}}
                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 remove-image-btn"
                            style="transform: translate(25%, -25%); border-radius: 50%; padding: 0 6px;">&times;</button>
                </div>
            @endforeach
        </div>
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
                        @endif
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

    @php
        // Tính toán index cho biến thể mới dựa trên old() hoặc variants hiện tại
        $variantIndex = old('variants') ? count(old('variants')) : count($product->variants);
    @endphp

    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
                <label class="form-label">Giá khuyến mãi</label>
                <input type="number" name="variants[\${index}][discount_price]" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label">Số lượng</label>
                <input type="number" name="variants[\${index}][quantity]" class="form-control" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Ảnh <span class="text-danger">*</span></label>
                <input type="file" name="color_images[new_\${index}]" class="form-control" accept="image/*" required>
                <small class="text-muted">Ảnh bắt buộc cho biến thể mới</small>
            </div>
            <div class="col-12 text-end">
                <button type="button" class="btn btn-outline-danger btn-sm remove-variant">
                    <i class="fas fa-times"></i> Xoá biến thể
                </button>
            </div>
        </div>
    </div>`;

            document.getElementById('add-variant-btn').addEventListener('click', function() {
                const container = document.getElementById('variants-container');
                const wrapper = document.createElement('div');
                wrapper.innerHTML = template().replace(/\${index}/g, index);
                container.appendChild(wrapper.firstElementChild);
                index++;
            });

            document.addEventListener('click', function(e) {
                if (e.target.closest('.remove-variant')) {
                    e.target.closest('.variant-item').remove();
                }
            });
        });
    </script>

    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#description'))
            .catch(error => {
                console.error(error);
            });
    </script>
<script>
document.addEventListener('click', function (e) {
    // Xóa ảnh album khi click nút ×
    if (e.target.closest('.remove-image-btn')) {
        const wrapper = e.target.closest('div');
        const hiddenInput = wrapper.querySelector('input[type="hidden"]');
        if (hiddenInput) hiddenInput.remove(); // xóa hidden để backend không giữ
        wrapper.remove();
    }
});
</script>
@endsection
