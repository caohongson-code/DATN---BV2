@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm border-0 rounded-3">
        {{-- Header --}}
        <div class="card-header text-dark d-flex align-items-center justify-content-between"
            style="background: rgba(241, 243, 245, 0.6); backdrop-filter: blur(6px); padding: 12px 20px; border-top-left-radius: .5rem; border-top-right-radius: .5rem;">
            <h5 class="mb-0 d-flex align-items-center">
                <i class="bx bx-edit-alt me-2" style="font-size: 1.3rem;"></i>
                <span class="fw-bold">Cập nhật khuyến mãi</span>
            </h5>
            <a href="{{ route('promotions.index') }}" class="btn btn-light btn-sm shadow-sm">
                <i class="bx bx-arrow-back me-1"></i> Quay lại
            </a>
        </div>

        {{-- Body --}}
        <div class="card-body">

            {{-- Hiển thị lỗi --}}
            @if ($errors->any())
                <div class="alert alert-danger rounded-3 shadow-sm">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li><i class="bx bx-error-circle"></i> {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('promotions.update', $promotion) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-bold">Mã khuyến mãi</label>
                    <input type="text" name="code" class="form-control" placeholder="Nhập mã"
                        value="{{ old('code', $promotion->code) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Mô tả</label>
                    <textarea name="description" class="form-control" placeholder="Nhập mô tả">{{ old('description', $promotion->description) }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Loại giảm giá</label>
                        <select name="discount_type" class="form-control">
                            <option value="percentage" {{ old('discount_type', $promotion->discount_type) == 'percentage' ? 'selected' : '' }}>Phần trăm</option>
                            <option value="fixed" {{ old('discount_type', $promotion->discount_type) == 'fixed' ? 'selected' : '' }}>Số tiền cố định</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Giá trị giảm</label>
                        <input type="text" name="discount_value" class="form-control" placeholder="Ví dụ: 100000"
                            value="{{ old('discount_value', number_format($promotion->discount_value, 0, ',', '.')) }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Ngày bắt đầu</label>
                        <input type="datetime-local" name="start_date" class="form-control"
                            value="{{ old('start_date', date('Y-m-d\TH:i', strtotime($promotion->start_date))) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Ngày kết thúc</label>
                        <input type="datetime-local" name="end_date" class="form-control"
                            value="{{ old('end_date', date('Y-m-d\TH:i', strtotime($promotion->end_date))) }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Giới hạn lượt dùng</label>
                    <input type="number" name="usage_limit" class="form-control" placeholder="Nhập số lượt"
                        value="{{ old('usage_limit', $promotion->usage_limit) }}">
                </div>

                <input type="hidden" name="is_active" value="0">
                <div class="form-check form-switch mb-4">
                    <input type="checkbox" name="is_active" class="form-check-input" value="1"
                        {{ old('is_active', $promotion->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label fw-bold">Kích hoạt</label>
                </div>

                {{-- Chọn sản phẩm --}}
                <div class="mb-3">
                    <label for="product_ids" class="form-label fw-bold">Chọn sản phẩm được áp dụng</label>
                    <select name="product_ids[]" id="product_ids" class="form-control select2" multiple>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}"
                                {{ in_array($product->id, old('product_ids', $selectedProductIds ?? [])) ? 'selected' : '' }}>
                                {{ $product->product_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Chọn danh mục --}}
                <div class="mb-3">
                    <label for="category_ids" class="form-label fw-bold">Chọn danh mục được áp dụng</label>
                    <select name="category_ids[]" id="category_ids" class="form-control select2" multiple>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ in_array($category->id, old('category_ids', $selectedCategoryIds ?? [])) ? 'selected' : '' }}>
                                {{ $category->category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="text-start">
                    <button class="btn btn-success px-4">
                        <i class="bx bx-save"></i> Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

{{-- Select2 --}}
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
