<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\News;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $news = [
            [
                'title' => 'iPhone 15 Pro Max - Điện thoại cao cấp nhất của Apple',
                'summary' => 'iPhone 15 Pro Max với chip A17 Pro mạnh mẽ, camera 48MP và thiết kế titan cao cấp',
                'content' => '<p>iPhone 15 Pro Max là flagship mới nhất của Apple với nhiều cải tiến đáng kể:</p>
                <ul>
                    <li>Chip A17 Pro với 6 nhân CPU và 6 nhân GPU</li>
                    <li>Camera chính 48MP với khả năng zoom quang học 5x</li>
                    <li>Thiết kế khung titan Grade 5 siêu bền</li>
                    <li>Màn hình Super Retina XDR 6.7 inch</li>
                    <li>Hỗ trợ USB-C với tốc độ truyền dữ liệu cao</li>
                </ul>
                <p>Đây là chiếc iPhone mạnh mẽ nhất từng được Apple sản xuất, phù hợp cho những người dùng đòi hỏi hiệu suất cao và trải nghiệm camera chuyên nghiệp.</p>',
                'author' => 'Admin',
                'status' => 'published',
                'published_at' => now(),
                'meta_title' => 'iPhone 15 Pro Max - Điện thoại cao cấp nhất của Apple',
                'meta_description' => 'Khám phá iPhone 15 Pro Max với chip A17 Pro, camera 48MP và thiết kế titan cao cấp. Điện thoại mạnh mẽ nhất của Apple.',
                'meta_keywords' => 'iPhone 15 Pro Max, Apple, A17 Pro, camera 48MP, titan',
                'is_featured' => true,
                'is_hot' => true,
                'sort_order' => 1
            ],
            [
                'title' => 'Samsung Galaxy S24 Ultra - AI thông minh, hiệu suất vượt trội',
                'summary' => 'Galaxy S24 Ultra tích hợp AI Galaxy mạnh mẽ, S Pen tích hợp và camera 200MP',
                'content' => '<p>Samsung Galaxy S24 Ultra là smartphone Android hàng đầu với những tính năng AI tiên tiến:</p>
                <ul>
                    <li>AI Galaxy với khả năng dịch thuật real-time</li>
                    <li>S Pen tích hợp với độ trễ thấp</li>
                    <li>Camera chính 200MP với zoom quang học 5x</li>
                    <li>Chip Snapdragon 8 Gen 3 mạnh mẽ</li>
                    <li>Màn hình Dynamic AMOLED 2X 6.8 inch</li>
                    <li>Pin 5000mAh với sạc nhanh 45W</li>
                </ul>
                <p>Galaxy S24 Ultra không chỉ là smartphone thông thường mà còn là công cụ AI mạnh mẽ giúp tăng hiệu suất công việc và sáng tạo.</p>',
                'author' => 'Admin',
                'status' => 'published',
                'published_at' => now()->subDays(1),
                'meta_title' => 'Samsung Galaxy S24 Ultra - AI thông minh, hiệu suất vượt trội',
                'meta_description' => 'Khám phá Galaxy S24 Ultra với AI Galaxy, S Pen tích hợp và camera 200MP. Smartphone Android hàng đầu với hiệu suất vượt trội.',
                'meta_keywords' => 'Samsung Galaxy S24 Ultra, AI Galaxy, S Pen, camera 200MP',
                'is_featured' => true,
                'is_hot' => false,
                'sort_order' => 2
            ],
            [
                'title' => 'Xiaomi 14 Ultra - Camera chuyên nghiệp, thiết kế độc đáo',
                'summary' => 'Xiaomi 14 Ultra với hệ thống camera Leica 4 camera, thiết kế camera module tròn độc đáo',
                'content' => '<p>Xiaomi 14 Ultra là smartphone camera chuyên nghiệp với sự hợp tác của Leica:</p>
                <ul>
                    <li>Camera chính 50MP với cảm biến IMX989</li>
                    <li>Camera góc rộng 50MP với ống kính Leica</li>
                    <li>Camera tele 50MP với zoom quang học 3.2x</li>
                    <li>Camera tele 50MP với zoom quang học 5x</li>
                    <li>Thiết kế camera module tròn độc đáo</li>
                    <li>Chip Snapdragon 8 Gen 3 mạnh mẽ</li>
                </ul>
                <p>Xiaomi 14 Ultra là lựa chọn hoàn hảo cho những nhiếp ảnh gia muốn có smartphone camera chuyên nghiệp với chất lượng hình ảnh Leica.</p>',
                'author' => 'Admin',
                'status' => 'published',
                'published_at' => now()->subDays(2),
                'meta_title' => 'Xiaomi 14 Ultra - Camera chuyên nghiệp, thiết kế độc đáo',
                'meta_description' => 'Khám phá Xiaomi 14 Ultra với hệ thống camera Leica 4 camera, thiết kế camera module tròn độc đáo. Smartphone camera chuyên nghiệp.',
                'meta_keywords' => 'Xiaomi 14 Ultra, Leica, camera 4 camera, thiết kế độc đáo',
                'is_featured' => false,
                'is_hot' => true,
                'sort_order' => 3
            ],
            [
                'title' => 'OPPO Find X7 Ultra - Camera AI thông minh, thiết kế sang trọng',
                'summary' => 'OPPO Find X7 Ultra tích hợp AI camera mạnh mẽ, thiết kế sang trọng với camera module hình chữ nhật',
                'content' => '<p>OPPO Find X7 Ultra là smartphone cao cấp với camera AI thông minh:</p>
                <ul>
                    <li>Camera chính 50MP với AI Super HDR</li>
                    <li>Camera góc rộng 50MP với ống kính ultra-wide</li>
                    <li>Camera tele 50MP với zoom quang học 3x</li>
                    <li>Camera tele 50MP với zoom quang học 6x</li>
                    <li>AI Portrait Mode với hiệu ứng bokeh tự nhiên</li>
                    <li>Thiết kế camera module hình chữ nhật sang trọng</li>
                </ul>
                <p>OPPO Find X7 Ultra không chỉ có camera AI mạnh mẽ mà còn có thiết kế sang trọng, phù hợp với người dùng cao cấp.</p>',
                'author' => 'Admin',
                'status' => 'published',
                'published_at' => now()->subDays(3),
                'meta_title' => 'OPPO Find X7 Ultra - Camera AI thông minh, thiết kế sang trọng',
                'meta_description' => 'Khám phá OPPO Find X7 Ultra với camera AI thông minh, thiết kế sang trọng. Smartphone cao cấp với camera AI mạnh mẽ.',
                'meta_keywords' => 'OPPO Find X7 Ultra, AI camera, thiết kế sang trọng, Super HDR',
                'is_featured' => false,
                'is_hot' => false,
                'sort_order' => 4
            ],
            [
                'title' => 'Hướng dẫn chọn điện thoại phù hợp với nhu cầu',
                'summary' => 'Bài viết hướng dẫn chi tiết cách chọn điện thoại phù hợp với nhu cầu sử dụng và ngân sách',
                'content' => '<p>Việc chọn điện thoại phù hợp không chỉ dựa vào giá cả mà còn cần xem xét nhiều yếu tố:</p>
                <h3>1. Xác định nhu cầu sử dụng</h3>
                <ul>
                    <li><strong>Chụp ảnh:</strong> Ưu tiên camera chất lượng cao</li>
                    <li><strong>Chơi game:</strong> Cần chip mạnh và RAM lớn</li>
                    <li><strong>Công việc:</strong> Cần pin trâu và hiệu suất ổn định</li>
                    <li><strong>Thời trang:</strong> Ưu tiên thiết kế đẹp</li>
                </ul>
                
                <h3>2. Ngân sách</h3>
                <ul>
                    <li><strong>Dưới 5 triệu:</strong> Điện thoại tầm trung, hiệu suất cơ bản</li>
                    <li><strong>5-10 triệu:</strong> Điện thoại tầm trung cao, camera tốt</li>
                    <li><strong>10-20 triệu:</strong> Flagship tầm trung, hiệu suất cao</li>
                    <li><strong>Trên 20 triệu:</strong> Flagship cao cấp, tính năng đầy đủ</li>
                </ul>
                
                <h3>3. Thương hiệu</h3>
                <p>Mỗi thương hiệu có ưu điểm riêng: Apple (iOS ổn định), Samsung (Android đa dạng), Xiaomi (giá tốt), OPPO (camera đẹp).</p>
                
                <p>Hãy cân nhắc kỹ lưỡng trước khi quyết định để có lựa chọn phù hợp nhất!</p>',
                'author' => 'Admin',
                'status' => 'published',
                'published_at' => now()->subDays(4),
                'meta_title' => 'Hướng dẫn chọn điện thoại phù hợp với nhu cầu',
                'meta_description' => 'Hướng dẫn chi tiết cách chọn điện thoại phù hợp với nhu cầu sử dụng và ngân sách. Tư vấn chọn điện thoại theo nhu cầu.',
                'meta_keywords' => 'chọn điện thoại, tư vấn mua điện thoại, điện thoại phù hợp',
                'is_featured' => false,
                'is_hot' => false,
                'sort_order' => 5
            ]
        ];

        foreach ($news as $item) {
            $item['slug'] = Str::slug($item['title']);
            News::create($item);
        }
    }
} 