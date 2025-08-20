@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-white border-bottom">
            <h4 class="mb-0 fw-bold">Thêm khuyến mãi</h4>
        </div>
        <div class="card-body">

            {{-- Hiển thị lỗi validate --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('promotions.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <input type="text" name="code" class="form-control" placeholder="Mã khuyến mãi" value="{{ old('code') }}">
                </div>

                <div class="mb-3">
                    <textarea name="description" class="form-control" placeholder="Mô tả">{{ old('description') }}</textarea>
                </div>

                <div class="mb-3">
                    <select name="discount_type" class="form-control">
                        <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>Phần trăm</option>
                        <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>Số tiền cố định</option>
                    </select>
                </div>

                <div class="mb-3">
                    <input type="number" step="0.01" name="discount_value" class="form-control" placeholder="Giá trị giảm" value="{{ old('discount_value') }}">
                </div>

                <div class="mb-3">
                    <input type="datetime-local" name="start_date" class="form-control" value="{{ old('start_date') }}">
                </div>

                <div class="mb-3">
                    <input type="datetime-local" name="end_date" class="form-control" value="{{ old('end_date') }}">
                </div>

                <div class="mb-3">
                    <input type="number" name="usage_limit" class="form-control" placeholder="Giới hạn lượt dùng (tuỳ chọn)" value="{{ old('usage_limit') }}">
                </div>

                <input type="hidden" name="is_active" value="0">
                <div class="form-check mb-3">
                    <input type="checkbox" name="is_active" class="form-check-input" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label">Kích hoạt</label>
                </div>

                {{-- Chọn sản phẩm áp dụng --}}
                <div class="mb-3">
                    <label for="product_ids" class="form-label">Chọn sản phẩm được áp dụng:</label>
                    <select name="product_ids[]" id="product_ids" class="form-control select2" multiple>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}"
                                {{ in_array($product->id, old('product_ids', [])) ? 'selected' : '' }}>
                                {{ $product->product_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Chọn danh mục áp dụng --}}
                <div class="mb-3">
                    <label for="category_ids" class="form-label">Chọn danh mục được áp dụng:</label>
                    <select name="category_ids[]" id="category_ids" class="form-control select2" multiple>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ in_array($category->id, old('category_ids', [])) ? 'selected' : '' }}>
                                {{ $category->category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button class="btn btn-success">Tạo mới</button>
            </form>
        </div>
    </div>
</div>
@endsection

{{-- Thêm Select2 --}}
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Chọn mục...",
                allowClear: true,
                width: '100%'
            });
        });
    </script>
@endpush
