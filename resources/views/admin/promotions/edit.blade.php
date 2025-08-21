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
                        <input type="number" step="0.01" name="discount_value" class="form-control"
                            value="{{ old('discount_value', $promotion->discount_value) }}">
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
                    <input type="number" name="usage_limit" class="form-control"
                        value="{{ old('usage_limit', $promotion->usage_limit) }}">
                </div>

                {{-- ✅ Chọn sản phẩm (checkbox) --}}
                <div class="mb-4">
                    <label class="form-label fw-bold">Chọn sản phẩm được áp dụng:</label>
                    <div class="row" style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; padding: 10px;">
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

                {{-- ✅ Chọn danh mục (checkbox) --}}
                <div class="mb-4">
                    <label class="form-label fw-bold">Chọn danh mục được áp dụng:</label>
                    <div class="row">
                        @foreach($categories as $category)
                            <div class="col-md-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="category_ids[]"
                                           value="{{ $category->id }}"
                                           id="category_{{ $category->id }}"
                                           {{ in_array($category->id, old('category_ids', $selectedCategoryIds ?? [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="category_{{ $category->id }}">
                                        {{ $category->category_name }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
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
