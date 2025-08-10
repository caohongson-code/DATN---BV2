@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Thêm khuyến mãi</h2>

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

        {{-- Trạng thái --}}
        <input type="hidden" name="is_active" value="0">
        <div class="form-check mb-3">
            <input type="checkbox" name="is_active" class="form-check-input" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
            <label class="form-check-label">Kích hoạt</label>
        </div>

           {{-- ✅ Chọn sản phẩm (checkbox) --}}
<div class="mb-4">
    <label class="form-label d-block">Chọn sản phẩm được áp dụng:</label>
    <div class="row" style="max-height: 400px; overflow-y: auto; border: 1px solid #ccc; padding: 10px;">
        @foreach($products as $product)
            <div class="col-md-4 mb-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="product_ids[]" 
                           value="{{ $product->id }}"
                           id="product_{{ $product->id }}"
                           {{ in_array($product->id, old('product_ids', $selectedProductIds ?? [])) ? 'checked' : '' }}>
                    <label class="form-check-label" for="product_{{ $product->id }}">
                        {{ $product->product_name }}
                    </label>
                </div>
            </div>
        @endforeach
    </div>
</div>

       {{-- ✅ Chọn danh mục áp dụng --}}
<div class="mb-3">
    <label class="form-label">Chọn danh mục được áp dụng:</label>
    <div class="row">
        @foreach($categories as $category)
            <div class="col-md-4">
                <div class="form-check">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="category_ids[]"
                        value="{{ $category->id }}"
                        id="category_{{ $category->id }}"
                        {{ in_array($category->id, old('category_ids', $selectedCategoryIds ?? [])) ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="category_{{ $category->id }}">
                        {{ $category->category_name }}
                    </label>
                </div>
            </div>
        @endforeach
    </div>
</div>


        {{-- ✅ Giá trị đơn hàng tối thiểu/tối đa --}}
        <div class="mb-3">
            <input type="number" step="0.01" name="min_order_amount" class="form-control" placeholder="Giá trị đơn hàng tối thiểu (tuỳ chọn)" value="{{ old('min_order_amount') }}">
        </div>

        <div class="mb-3">
            <input type="number" step="0.01" name="max_order_amount" class="form-control" placeholder="Giá trị đơn hàng tối đa (tuỳ chọn)" value="{{ old('max_order_amount') }}">
        </div>

        <button class="btn btn-success">Tạo mới</button>
    </form>
</div>
@endsection

{{-- ✅ Thêm Select2 --}}
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
