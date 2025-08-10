@extends('admin.layouts.app')

@section('title', 'Chỉnh sửa tin tức')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Chỉnh sửa tin tức: {{ $news->title }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('news.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('news.update', $news->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-8">
                                <!-- Basic Information -->
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Thông tin cơ bản</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="title">Tiêu đề <span class="text-danger">*</span></label>
                                            <input type="text" 
                                                   class="form-control @error('title') is-invalid @enderror" 
                                                   id="title" 
                                                   name="title" 
                                                   value="{{ old('title', $news->title) }}" 
                                                   placeholder="Nhập tiêu đề tin tức"
                                                   required>
                                            @error('title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="summary">Tóm tắt <span class="text-danger">*</span></label>
                                            <textarea class="form-control @error('summary') is-invalid @enderror" 
                                                      id="summary" 
                                                      name="summary" 
                                                      rows="3" 
                                                      placeholder="Nhập tóm tắt tin tức"
                                                      required>{{ old('summary', $news->summary) }}</textarea>
                                            <small class="form-text text-muted">Tối đa 500 ký tự</small>
                                            @error('summary')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="content">Nội dung <span class="text-danger">*</span></label>
                                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                                      id="content" 
                                                      name="content" 
                                                      rows="15" 
                                                      placeholder="Nhập nội dung tin tức"
                                                      required>{{ old('content', $news->content) }}</textarea>
                                            @error('content')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- SEO Information -->
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5 class="card-title">Thông tin SEO</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="meta_title">Meta Title</label>
                                            <input type="text" 
                                                   class="form-control @error('meta_title') is-invalid @enderror" 
                                                   id="meta_title" 
                                                   name="meta_title" 
                                                   value="{{ old('meta_title', $news->meta_title) }}" 
                                                   placeholder="Meta title cho SEO">
                                            @error('meta_title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="meta_description">Meta Description</label>
                                            <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                                      id="meta_description" 
                                                      name="meta_description" 
                                                      rows="3" 
                                                      placeholder="Meta description cho SEO">{{ old('meta_description', $news->meta_description) }}</textarea>
                                            <small class="form-text text-muted">Tối đa 500 ký tự</small>
                                            @error('meta_description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="meta_keywords">Meta Keywords</label>
                                            <input type="text" 
                                                   class="form-control @error('meta_keywords') is-invalid @enderror" 
                                                   id="meta_keywords" 
                                                   name="meta_keywords" 
                                                   value="{{ old('meta_keywords', $news->meta_keywords) }}" 
                                                   placeholder="Từ khóa SEO, phân cách bằng dấu phẩy">
                                            @error('meta_keywords')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <!-- Sidebar Settings -->
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Cài đặt</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="status">Trạng thái <span class="text-danger">*</span></label>
                                            <select class="form-control @error('status') is-invalid @enderror" 
                                                    id="status" 
                                                    name="status" 
                                                    required>
                                                <option value="draft" {{ old('status', $news->status) == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                                                <option value="published" {{ old('status', $news->status) == 'published' ? 'selected' : '' }}>Xuất bản</option>
                                                <option value="archived" {{ old('status', $news->status) == 'archived' ? 'selected' : '' }}>Lưu trữ</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="published_at">Thời gian xuất bản</label>
                                            <input type="datetime-local" 
                                                   class="form-control @error('published_at') is-invalid @enderror" 
                                                   id="published_at" 
                                                   name="published_at" 
                                                   value="{{ old('published_at', $news->published_at ? $news->published_at->format('Y-m-d\TH:i') : '') }}">
                                            <small class="form-text text-muted">Để trống để xuất bản ngay</small>
                                            @error('published_at')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="author">Tác giả</label>
                                            <input type="text" 
                                                   class="form-control @error('author') is-invalid @enderror" 
                                                   id="author" 
                                                   name="author" 
                                                   value="{{ old('author', $news->author) }}" 
                                                   placeholder="Tên tác giả">
                                            @error('author')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="sort_order">Thứ tự sắp xếp</label>
                                            <input type="number" 
                                                   class="form-control @error('sort_order') is-invalid @enderror" 
                                                   id="sort_order" 
                                                   name="sort_order" 
                                                   value="{{ old('sort_order', $news->sort_order) }}" 
                                                   min="0">
                                            @error('sort_order')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" 
                                                       class="custom-control-input" 
                                                       id="is_featured" 
                                                       name="is_featured" 
                                                       value="1" 
                                                       {{ old('is_featured', $news->is_featured) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="is_featured">
                                                    Đánh dấu nổi bật
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" 
                                                       class="custom-control-input" 
                                                       id="is_hot" 
                                                       name="is_hot" 
                                                       value="1" 
                                                       {{ old('is_hot', $news->is_hot) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="is_hot">
                                                    Đánh dấu hot
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Featured Image -->
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5 class="card-title">Hình ảnh đại diện</h5>
                                    </div>
                                    <div class="card-body">
                                        @if($news->featured_image)
                                            <div class="mb-3">
                                                <label>Hình ảnh hiện tại:</label>
                                                <div class="mb-2">
                                                    <img src="{{ asset('storage/' . $news->featured_image) }}" 
                                                         alt="{{ $news->title }}" 
                                                         class="img-fluid rounded" 
                                                         style="max-width: 100%; height: auto;">
                                                </div>
                                            </div>
                                        @endif

                                        <div class="form-group">
                                            <label for="featured_image">Thay đổi hình ảnh</label>
                                            <input type="file" 
                                                   class="form-control-file @error('featured_image') is-invalid @enderror" 
                                                   id="featured_image" 
                                                   name="featured_image" 
                                                   accept="image/*">
                                            <small class="form-text text-muted">
                                                Định dạng: JPG, PNG, GIF, WEBP. Kích thước tối đa: 2MB
                                            </small>
                                            @error('featured_image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div id="image-preview" class="mt-2" style="display: none;">
                                            <label>Xem trước:</label>
                                            <img id="preview-img" src="" alt="Preview" class="img-fluid rounded">
                                        </div>
                                    </div>
                                </div>

                                <!-- Statistics -->
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5 class="card-title">Thống kê</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-info">
                                                        <i class="fas fa-eye"></i>
                                                    </span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Lượt xem</span>
                                                        <span class="info-box-number">{{ number_format($news->view_count) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-success">
                                                        <i class="fas fa-calendar"></i>
                                                    </span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Ngày tạo</span>
                                                        <span class="info-box-number">{{ $news->created_at->format('d/m/Y') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Cập nhật tin tức
                                    </button>
                                    <button type="button" class="btn btn-secondary ml-2" onclick="history.back()">
                                        <i class="fas fa-times"></i> Hủy
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Image preview
document.getElementById('featured_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('image-preview').style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        document.getElementById('image-preview').style.display = 'none';
    }
});

// Auto-generate meta title if empty
document.getElementById('title').addEventListener('input', function() {
    const metaTitle = document.getElementById('meta_title');
    if (!metaTitle.value) {
        metaTitle.value = this.value;
    }
});

// Auto-generate meta description if empty
document.getElementById('summary').addEventListener('input', function() {
    const metaDesc = document.getElementById('meta_description');
    if (!metaDesc.value) {
        metaDesc.value = this.value;
    }
});
</script>
@endpush 