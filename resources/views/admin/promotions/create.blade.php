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

                <div class="row">
                    <div class="col-md-6">
                        {{-- Mã khuyến mãi --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Mã khuyến mãi</label>
                            <input type="text" name="code" class="form-control" value="{{ old('code') }}">
                        </div>

                        {{-- Mô tả --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Mô tả</label>
                            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                        </div>

                        {{-- Loại giảm giá --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Loại giảm giá</label>
                            <select name="discount_type" class="form-control">
                                <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>Phần trăm</option>
                                <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>Số tiền cố định</option>
                            </select>
                        </div>

                        {{-- Giá trị giảm --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Giá trị giảm</label>
                            <input type="number" step="0.01" name="discount_value" class="form-control" value="{{ old('discount_value') }}">
                        </div>

                        {{-- Ngày bắt đầu --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Ngày bắt đầu</label>
                            <input type="datetime-local" name="start_date" class="form-control" value="{{ old('start_date') }}">
                        </div>

                        {{-- Ngày kết thúc --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Ngày kết thúc</label>
                            <input type="datetime-local" name="end_date" class="form-control" value="{{ old('end_date') }}">
                        </div>

                        {{-- Giới hạn lượt dùng --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Giới hạn lượt dùng</label>
                            <input type="number" name="usage_limit" class="form-control" value="{{ old('usage_limit') }}">
                        </div>

                        {{-- Trạng thái --}}
                        <div class="form-check mb-3">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" class="form-check-input" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label">Kích hoạt</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        {{-- Chọn sản phẩm --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Chọn sản phẩm áp dụng</label>
                            <div class="border rounded p-2" style="max-height: 300px; overflow-y: auto;">
                                <div class="row">
                                    @foreach($products as $product)
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input"
                                                    type="checkbox"
                                                    name="product_ids[]"
                                                    id="product_{{ $product->id }}"
                                                    value="{{ $product->id }}"
                                                    {{ in_array($product->id, old('product_ids', $selectedProductIds ?? [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="product_{{ $product->id }}">
                                                    {{ $product->product_name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Chọn danh mục --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Chọn danh mục áp dụng</label>
                            <div class="border rounded p-2" style="max-height: 200px; overflow-y: auto;">
                                <div class="row">
                                    @foreach($categories as $category)
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input"
                                                    type="checkbox"
                                                    name="category_ids[]"
                                                    id="category_{{ $category->id }}"
                                                    value="{{ $category->id }}"
                                                    {{ in_array($category->id, old('category_ids', $selectedCategoryIds ?? [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="category_{{ $category->id }}">
                                                    {{ $category->category_name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Giá trị đơn hàng tối thiểu --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Đơn hàng tối thiểu</label>
                            <input type="number" step="0.01" name="min_order_amount" class="form-control" value="{{ old('min_order_amount') }}">
                        </div>

                        {{-- Giá trị đơn hàng tối đa --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Đơn hàng tối đa</label>
                            <input type="number" step="0.01" name="max_order_amount" class="form-control" value="{{ old('max_order_amount') }}">
                        </div>
                    </div>
                </div>

                {{-- Nút hành động --}}
                <div class="d-flex justify-content-end mt-3">
                    <a href="{{ route('promotions.index') }}" class="btn btn-secondary me-2">Quay lại</a>
                    <button class="btn btn-success">Tạo mới</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
