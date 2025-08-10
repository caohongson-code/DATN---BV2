@extends('admin.layouts.app')

@section('title', 'Thêm tin tức mới')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Thêm tin tức mới</h3>
                    <div class="card-tools">
                        <a href="{{ route('news.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('news.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
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
                                                   value="{{ old('title') }}" 
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
                                                      required>{{ old('summary') }}</textarea>
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
                                                      required>{{ old('content') }}</textarea>
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
                                                   value="{{ old('meta_title') }}" 
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
                                                      placeholder="Meta description cho SEO">{{ old('meta_description') }}</textarea>
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
                                                   value="{{ old('meta_keywords') }}" 
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
                                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                                                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Xuất bản</option>
                                                <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Lưu trữ</option>
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
                                                   value="{{ old('published_at') }}">
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
                                                   value="{{ old('author') }}" 
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
                                                   value="{{ old('sort_order', 0) }}" 
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
                                                       {{ old('is_featured') ? 'checked' : '' }}>
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
                                                       {{ old('is_hot') ? 'checked' : '' }}>
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
                                        <div class="form-group">
                                            <label for="featured_image">Chọn hình ảnh</label>
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
                                            <img id="preview-img" src="" alt="Preview" class="img-fluid rounded">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Lưu tin tức
                                    </button>
                                    <button type="button" class="btn btn-secondary ml-2" onclick="history.back()">
                                        <i class="fas fa-times"></i> Hủy
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Test Upload Form -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Test Upload (Debug)</h5>
                                    </div>
                                    <div class="card-body">
                                        <form id="testUploadForm" enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group">
                                                <label for="test_image">Chọn hình ảnh test</label>
                                                <input type="file" class="form-control-file" id="test_image" name="featured_image" accept="image/*">
                                            </div>
                                            <button type="submit" class="btn btn-warning">Test Upload</button>
                                        </form>
                                        <div id="testResult" class="mt-2"></div>
                                    </div>
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

// Test upload form
document.getElementById('testUploadForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const resultDiv = document.getElementById('testResult');
    
    resultDiv.innerHTML = '<div class="alert alert-info">Đang test upload...</div>';
    
                            fetch('{{ route("admin.news.test-upload") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            resultDiv.innerHTML = '<div class="alert alert-success">Upload thành công! File được lưu tại: ' + data.path + '</div>';
        } else {
            resultDiv.innerHTML = '<div class="alert alert-danger">Upload thất bại: ' + (data.error || data.message) + '</div>';
        }
    })
    .catch(error => {
        resultDiv.innerHTML = '<div class="alert alert-danger">Lỗi: ' + error.message + '</div>';
    });
});
</script>
@endpush 