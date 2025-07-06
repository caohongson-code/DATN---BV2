@extends('admin.layouts.app')

@section('title', 'Thêm sản phẩm mới')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">

            {{-- Tiêu đề + nút quay lại --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0 text-primary">Thêm sản phẩm mới</h4>
                <a href="{{ route('products.index') }}" class="btn btn-sm btn-light border shadow-sm text-dark d-flex align-items-center">
                    <i class="fas fa-arrow-left me-2 text-muted"></i>
                    <span class="fw-normal">Quay lại danh sách</span>
                </a>
            </div>
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

            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="product_name" class="form-label">Tên sản phẩm</label>
                    <input type="text" class="form-control" id="product_name" name="product_name" value="{{ old('product_name') }}" required>
                </div>

                {{-- Danh mục --}}
                <div class="mb-4">
                    <label for="category_id" class="form-label fw-semibold text-primary">
                        <i class="fas fa-list-alt me-2"></i>Danh mục sản phẩm
                    </label>
                    <div class="custom-select-wrapper">
                        <select class="form-select beautiful-select" id="category_id" name="category_id" required>
                            <option value="">-- Chọn danh mục --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->category_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Giá</label>
                    <input type="number" class="form-control" id="price" name="price" value="{{ old('price') }}" step="0.01" required>
                </div>

                <div class="mb-3">
                    <label for="discount_price" class="form-label">Giá khuyến mãi</label>
                    <input type="number" class="form-control" id="discount_price" name="discount_price" value="{{ old('discount_price') }}" step="0.01">
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Ảnh sản phẩm</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                </div>

                <div class="mb-3">
                    <label for="quantity" class="form-label">Số lượng</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" value="{{ old('quantity') }}" required>
                </div>

                            {{-- Trạng thái --}}
                            <div class="mb-4">
                                <label for="status" class="form-label fw-semibold text-success">
                                    <i class="fas fa-toggle-on me-2"></i>Trạng thái hiển thị
                                </label>
                                <div class="custom-select-wrapper">
                                    <select class="form-select beautiful-select" id="status" name="status" required>
                                        <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>✅ Hiển thị</option>
                                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>❌ Ẩn</option>
                                    </select>
                                </div>
                            </div>
                <hr>
                <h4>Thêm biến thể</h4>

                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card p-3">
                            <h6 class="fw-bold mb-2">Chọn màu</h6>
                            @foreach($colors as $color)
                                <div class="form-check">
                                    <input class="form-check-input attr-checkbox" type="checkbox" value="{{ $color->id }}" data-type="color" id="color-{{ $color->id }}">
                                    <label class="form-check-label" for="color-{{ $color->id }}">{{ $color->value }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card p-3">
                            <h6 class="fw-bold mb-2">Chọn RAM</h6>
                            @foreach($rams as $ram)
                                <div class="form-check">
                                    <input class="form-check-input attr-checkbox" type="checkbox" value="{{ $ram->id }}" data-type="ram" id="ram-{{ $ram->id }}">
                                    <label class="form-check-label" for="ram-{{ $ram->id }}">{{ $ram->value }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card p-3">
                            <h6 class="fw-bold mb-2">Chọn dung lượng</h6>
                            @foreach($storages as $storage)
                                <div class="form-check">
                                    <input class="form-check-input attr-checkbox" type="checkbox" value="{{ $storage->id }}" data-type="storage" id="storage-{{ $storage->id }}">
                                    <label class="form-check-label" for="storage-{{ $storage->id }}">{{ $storage->value }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <hr>
                <h4>Hình ảnh theo màu</h4>
                <div id="colorImageContainer"></div>

                <hr>
                <h4>Danh sách biến thể</h4>
                <div id="variantTableContainer"></div>

                <button type="submit" class="btn btn-primary mt-3">Thêm sản phẩm và biến thể</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const getCombinations = (colors, rams, storages) => {
            let combinations = [];
            colors.forEach(color => {
                rams.forEach(ram => {
                    storages.forEach(storage => {
                        combinations.push({ color, ram, storage });
                    });
                });
            });
            return combinations;
        };

        const generateColorImages = () => {
            let selectedColors = Array.from(document.querySelectorAll('input[data-type="color"]:checked')).map(i => ({
                id: i.value,
                value: i.nextElementSibling.textContent
            }));

            let colorImageHtml = '';
            if (selectedColors.length > 0) {
                colorImageHtml = '<div class="mb-3">';
                selectedColors.forEach((color, index) => {
                    colorImageHtml += `
                        <div class="mb-2">
                            <label for="color_image_${color.id}" class="form-label">Hình ảnh cho màu ${color.value}</label>
                            <input type="file" class="form-control" name="color_images[${color.id}]" id="color_image_${color.id}">
                        </div>`;
                });
                colorImageHtml += '</div>';
            }
            document.getElementById('colorImageContainer').innerHTML = colorImageHtml;
        };

        const generateVariantTable = () => {
            let selectedColors = Array.from(document.querySelectorAll('input[data-type="color"]:checked')).map(i => ({
                id: i.value,
                value: i.nextElementSibling.textContent
            }));
            let selectedRams = Array.from(document.querySelectorAll('input[data-type="ram"]:checked')).map(i => ({
                id: i.value,
                value: i.nextElementSibling.textContent
            }));
            let selectedStorages = Array.from(document.querySelectorAll('input[data-type="storage"]:checked')).map(i => ({
                id: i.value,
                value: i.nextElementSibling.textContent
            }));

            if (selectedColors.length === 0 || selectedRams.length === 0 || selectedStorages.length === 0) {
                document.getElementById('variantTableContainer').innerHTML = "<p class='text-danger'>Hãy chọn đủ Màu, RAM và Bộ nhớ để sinh biến thể.</p>";
                document.getElementById('colorImageContainer').innerHTML = '';
                return;
            }

            generateColorImages();

            let combinations = getCombinations(selectedColors, selectedRams, selectedStorages);
            let maxCombinations = 20;
            if (combinations.length > maxCombinations) {
                alert(`Số lượng biến thể vượt quá giới hạn ${maxCombinations}. Vui lòng giảm số lựa chọn.`);
                return;
            }

            let table = `<table class="table table-bordered mt-3"><thead><tr>
                            <th>Màu sắc</th>
                            <th>RAM</th>
                            <th>Bộ nhớ</th>
                            <th>Giá</th>
                            <th>Số lượng</th>
                        </tr></thead><tbody>`;

            combinations.forEach((combo, index) => {
                table += `<tr>
                            <td><input type="hidden" name="variants[${index}][color_id]" value="${combo.color.id}">${combo.color.value}</td>
                            <td><input type="hidden" name="variants[${index}][ram_id]" value="${combo.ram.id}">${combo.ram.value}</td>
                            <td><input type="hidden" name="variants[${index}][storage_id]" value="${combo.storage.id}">${combo.storage.value}</td>
                            <td><input type="number" class="form-control" name="variants[${index}][price]" min="0" required></td>
                            <td><input type="number" class="form-control" name="variants[${index}][quantity]" min="0" required></td>
                        </tr>`;
            });

            table += "</tbody></table>";
            document.getElementById('variantTableContainer').innerHTML = table;
        };

        document.querySelectorAll('.attr-checkbox').forEach(el => {
            el.addEventListener('change', generateVariantTable);
        });

        generateVariantTable();
    });
</script>
@endsection
<style>
.slim-select {
    height: 38px;
    padding: 4px 14px;
    font-size: 0.95rem;
    border: 1px solid #ced4da;
    border-radius: 25px;
    background-color: #fdfdfd;
    transition: all 0.3s ease-in-out;
}

.slim-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 6px rgba(13, 110, 253, 0.25);
    outline: none;
    background-color: #fff;
}

.slim-select:hover {
    background-color: #f1f8ff;
    cursor: pointer;
}

.small-label {
    font-size: 0.875rem;
}
.btn-back-internal {
    border-radius: 8px;
    padding: 6px 14px;
    font-size: 14px;
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    transition: all 0.2s ease-in-out;
}

.btn-back-internal:hover {
    background-color: #e9ecef;
    text-decoration: none;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
}


</style>
