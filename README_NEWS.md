# Hệ thống Quản lý Tin tức - Admin Panel

## Tổng quan
Hệ thống quản lý tin tức được xây dựng cho trang web bán điện thoại, cho phép admin tạo, chỉnh sửa, xóa và quản lý các bài viết tin tức về sản phẩm và thông tin liên quan.

## Tính năng chính

### 1. Quản lý tin tức (CRUD)
- **Tạo tin tức mới**: Form tạo tin tức với đầy đủ thông tin
- **Xem danh sách**: Hiển thị danh sách tin tức với phân trang và tìm kiếm
- **Chỉnh sửa**: Cập nhật thông tin tin tức
- **Xóa**: Xóa tin tức (soft delete)
- **Xem chi tiết**: Hiển thị đầy đủ thông tin tin tức

### 2. Tìm kiếm và lọc
- Tìm kiếm theo tiêu đề, tóm tắt, tác giả
- Lọc theo trạng thái (draft, published, archived)
- Lọc theo featured/hot status
- Sắp xếp theo thứ tự tùy chỉnh

### 3. Quản lý hình ảnh
- Upload hình ảnh đại diện
- Hỗ trợ định dạng: JPEG, PNG, JPG, GIF, WEBP
- Giới hạn kích thước: 2MB
- Tự động xóa hình ảnh cũ khi cập nhật

### 4. Trạng thái và phân loại
- **Trạng thái**: Draft, Published, Archived
- **Featured**: Đánh dấu tin tức nổi bật
- **Hot**: Đánh dấu tin tức hot
- **Thứ tự sắp xếp**: Tùy chỉnh thứ tự hiển thị

### 5. SEO và Meta
- Meta title
- Meta description
- Meta keywords
- Slug tự động từ tiêu đề

### 6. Hành động hàng loạt
- Xóa nhiều tin tức cùng lúc
- Xuất bản nhiều tin tức
- Chuyển về draft nhiều tin tức

## Cấu trúc cơ sở dữ liệu

### Bảng `news`
```sql
- id (Primary Key)
- title (Tiêu đề)
- slug (URL thân thiện, unique)
- summary (Tóm tắt)
- content (Nội dung chi tiết)
- featured_image (Hình ảnh đại diện)
- author (Tác giả)
- status (Trạng thái: draft/published/archived)
- published_at (Thời gian xuất bản)
- view_count (Số lượt xem)
- meta_title (Meta title)
- meta_description (Meta description)
- meta_keywords (Meta keywords)
- is_featured (Đánh dấu nổi bật)
- is_hot (Đánh dấu hot)
- sort_order (Thứ tự sắp xếp)
- timestamps (created_at, updated_at)
- softDeletes (deleted_at)
```

## Cài đặt và sử dụng

### 1. Chạy migration
```bash
php artisan migrate
```

### 2. Chạy seeder (tạo dữ liệu mẫu)
```bash
php artisan db:seed --class=NewsSeeder
```

### 3. Truy cập admin panel
- URL: `/admin/news`
- Menu: "Quản lý tin tức" trong sidebar

## Routes

### Admin Routes
```php
// Resource routes
Route::resource('news', NewsController::class);

// Custom routes
Route::post('/news/{news}/toggle-featured', [NewsController::class, 'toggleFeatured']);
Route::post('/news/{news}/toggle-hot', [NewsController::class, 'toggleHot']);
Route::post('/news/bulk-action', [NewsController::class, 'bulkAction']);
```

## Model News

### Scopes
- `published()`: Lấy tin tức đã xuất bản
- `featured()`: Lấy tin tức nổi bật
- `hot()`: Lấy tin tức hot
- `orderBySort()`: Sắp xếp theo thứ tự

### Accessors
- `statusText`: Văn bản trạng thái
- `statusBadge`: Badge HTML cho trạng thái
- `isPublished`: Kiểm tra đã xuất bản

### Mutators
- `setTitleAttribute`: Tự động tạo slug từ tiêu đề
- `setPublishedAtAttribute`: Xử lý thời gian xuất bản

### Methods
- `incrementViewCount()`: Tăng số lượt xem
- `toggleFeatured()`: Chuyển đổi trạng thái featured
- `toggleHot()`: Chuyển đổi trạng thái hot

## Validation

### NewsRequest
- Tiêu đề: Bắt buộc, tối đa 255 ký tự
- Tóm tắt: Bắt buộc, tối đa 500 ký tự
- Nội dung: Bắt buộc
- Hình ảnh: Tùy chọn, định dạng hình ảnh, tối đa 2MB
- Tác giả: Tùy chọn, tối đa 100 ký tự
- Trạng thái: Bắt buộc, enum: draft/published/archived
- Thời gian xuất bản: Tùy chọn, định dạng ngày
- Meta fields: Tùy chọn, giới hạn ký tự
- Featured/Hot: Boolean
- Thứ tự sắp xếp: Tùy chọn, số nguyên >= 0

## Giao diện

### Views
- `index.blade.php`: Danh sách tin tức với tìm kiếm và lọc
- `create.blade.php`: Form tạo tin tức mới
- `edit.blade.php`: Form chỉnh sửa tin tức
- `show.blade.php`: Hiển thị chi tiết tin tức

### Tính năng giao diện
- Responsive design
- Image preview
- Auto-generate meta fields
- AJAX cho toggle featured/hot
- Bulk actions với checkbox
- Pagination
- Search và filter forms

## JavaScript

### Chức năng
- Image preview khi upload
- Auto-populate meta title/description
- Toggle featured/hot status (AJAX)
- Select all checkbox cho bulk actions
- Form validation

## Bảo mật

### Middleware
- Admin authentication
- CSRF protection
- Form validation

### Quyền truy cập
- Chỉ admin có thể truy cập
- Validation server-side
- Sanitize input data

## Tùy chỉnh

### Thêm trường mới
1. Tạo migration mới
2. Cập nhật model News
3. Cập nhật NewsRequest validation
4. Cập nhật views
5. Cập nhật controller

### Thay đổi giao diện
- Chỉnh sửa Blade templates
- Cập nhật CSS/JS
- Thêm tính năng mới

## Troubleshooting

### Lỗi thường gặp
1. **Migration failed**: Kiểm tra database connection
2. **Image upload failed**: Kiểm tra storage permissions
3. **Validation errors**: Kiểm tra NewsRequest rules
4. **Route not found**: Kiểm tra web.php routes

### Debug
- Kiểm tra Laravel logs: `storage/logs/laravel.log`
- Sử dụng `dd()` hoặc `Log::info()` trong controller
- Kiểm tra database với `php artisan tinker`

## Hỗ trợ

Nếu gặp vấn đề, vui lòng:
1. Kiểm tra logs
2. Xác nhận cài đặt đúng
3. Kiểm tra quyền truy cập
4. Liên hệ developer

---

**Lưu ý**: Hệ thống này được thiết kế cho Laravel 10+ và yêu cầu PHP 8.1+ 