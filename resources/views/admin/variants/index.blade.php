@extends('admin.layouts.app')


@section('title', 'Quản lý biến thể sản phẩm')


@section('title', 'Danh sách biến thể sản phẩm')


@section('content')
<div class="container py-4">

    <h2 class="mb-4">Biến thể sản phẩm</h2>

    <h2 class="mb-4">Danh sách biến thể sản phẩm</h2>

@section('title', 'Quản lý biến thể sản phẩm')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Biến thể sản phẩm</h2>


    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" class="mb-3 row g-3">
        <div class="col-md-3">
            <select name="color_id" class="form-select">
                <option value="">Tất cả màu</option>
                @foreach ($colors as $color)
                    <option value="{{ $color->id }}" {{ $request->input('color_id') == $color->id ? 'selected' : '' }}>
                        {{ $color->value }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="ram_id" class="form-select">
                <option value="">Tất cả RAM</option>
                @foreach ($rams as $ram)
                    <option value="{{ $ram->id }}" {{ $request->input('ram_id') == $ram->id ? 'selected' : '' }}>
                        {{ $ram->value }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="storage_id" class="form-select">
                <option value="">Tất cả dung lượng</option>
                @foreach ($storages as $storage)
                    <option value="{{ $storage->id }}" {{ $request->input('storage_id') == $storage->id ? 'selected' : '' }}>
                        {{ $storage->value }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm tên sản phẩm..." value="{{ $request->input('search', '') }}">
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary w-100">Lọc</button>
        </div>
    </form>


    <div class="accordion" id="variantAccordion">
        @forelse($products as $product)
            <div class="accordion-item mb-2">
                <h2 class="accordion-header" id="heading-{{ $product->id }}">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $product->id }}" aria-expanded="false">
                        {{ $product->product_name }}
                        <span class="badge bg-primary ms-2">{{ $product->variants->count() }} biến thể</span>
                    </button>
                </h2>
                <div id="collapse-{{ $product->id }}" class="accordion-collapse collapse" data-bs-parent="#variantAccordion">
                    <div class="accordion-body">
                        @php
                            $filteredVariants = $product->variants;
                            if ($request->input('color_id')) {
                                $filteredVariants = $filteredVariants->where('color_id', $request->input('color_id'));
                            }
                            if ($request->input('ram_id')) {
                                $filteredVariants = $filteredVariants->where('ram_id', $request->input('ram_id'));
                            }
                            if ($request->input('storage_id')) {
                                $filteredVariants = $filteredVariants->where('storage_id', $request->input('storage_id'));
                            }
                        @endphp
                        @if($filteredVariants->count())
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle table-striped table-hover">
                                    <thead class="table-dark text-center">
                                        <tr>
                                            <th>ID</th>
                                            <th>RAM</th>
                                            <th>Dung lượng</th>
                                            <th>Màu</th>
                                            <th>Ảnh</th>
                                            <th>Giá</th>
                                            <th>KM</th>
                                            <th>SL</th>
                                            <th>Trạng thái</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($filteredVariants as $variant)
                                            <tr class="text-center">
                                                <td>{{ $variant->id }}</td>
                                                <td>{{ $variant->ram->value ?? '---' }}</td>
                                                <td>{{ $variant->storage->value ?? '---' }}</td>
                                                <td>{{ $variant->color->value ?? '---' }}</td>
                                                <td>
                                                    @if($variant->image)
                                                        <img src="{{ asset('storage/' . $variant->image) }}" width="60" class="rounded shadow-sm" title="Ảnh sản phẩm">
                                                    @else
                                                        <span class="text-muted">Không có ảnh</span>
                                                    @endif
                                                </td>
                                                <td>{{ number_format($variant->price, 0, ',', '.') }} đ</td>
                                                <td>
                                                    @if($variant->discount_price)
                                                        <span class="text-danger fw-bold">{{ number_format($variant->discount_price, 0, ',', '.') }} đ</span>
                                                        <br>
                                                        <small class="text-muted fst-italic">Giảm {{ round(100 - ($variant->discount_price / $variant->price * 100)) }}%</small>
                                                    @else
                                                        <span class="text-muted fst-italic">--</span>
                                                    @endif
                                                </td>
                                                <td>{{ $variant->quantity }}</td>
                                                <td>{{ $variant->quantity > 0 ? 'Còn hàng' : 'Hết hàng' }}</td>
                                                <td>
                                                    <a href="{{ route('variants.edit', $variant->id) }}" class="btn btn-warning btn-sm me-1" title="Chỉnh sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('variants.destroy', $variant->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa biến thể này?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" title="Xóa">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted fst-italic">Không có biến thể nào khớp với bộ lọc.</p>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center text-muted">Chưa có sản phẩm nào.</p>
        @endforelse
    </div>
</div>
@endsection

    <a href="{{ route('variants.create') }}" class="btn btn-success mb-3">
        <i class="fas fa-plus me-2"></i> Thêm biến thể mới
    </a>

    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Sản phẩm</th>
                <th>RAM</th>
                <th>Storage</th>
                <th>Màu</th>
                <th>Ảnh</th>
                <th>Giá</th>
                <th>Giá KM</th>
                <th>Số lượng</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
@forelse($variants as $variant)
    <tr>
        <td>{{ $variant->id }}</td>
        <td>{{ $variant->product->product_name ?? 'Không có' }}</td>
        <td>{{ $variant->ram->value ?? '---' }}</td>
        <td>{{ $variant->storage->value ?? '---' }}</td>
        <td>{{ $variant->color->value ?? '---' }}</td>
        <td>
            @if($variant->image)
                <img src="{{ asset('storage/' . $variant->image) }}" width="70px" height="70px" alt="Ảnh biến thể" />
            @else
                <span class="text-muted">Chưa có ảnh</span>
            @endif
        </td>
<td>
    <strong style="font-size: 15px;">
        {{ number_format($variant->price, 0, ',', '.') }} đ
    </strong>
</td>
<td>
    @if($variant->discount_price)
        <strong style="color: red; font-size: 15px;">
            {{ number_format($variant->discount_price, 0, ',', '.') }} đ
        </strong>
    @else
        <span class="text-muted fst-italic">Không có</span>
    @endif
</td>

        <td>{{ $variant->quantity }}</td>
        <td>
            <a href="{{ route('variants.edit', $variant->id) }}" class="btn btn-warning btn-sm me-1" title="Sửa biến thể">
                <i class="fas fa-edit"></i>
            </a>
            <form action="{{ route('variants.destroy', $variant->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa biến thể này?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm" title="Xóa biến thể">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </form>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="10" class="text-center">Chưa có biến thể nào.</td>
    </tr>
@endforelse

        </tbody>
    </table>
</div>

@endsection

