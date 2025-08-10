<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Tiêu đề tin tức
            $table->string('slug')->unique(); // URL slug
            $table->text('summary'); // Tóm tắt tin tức
            $table->longText('content'); // Nội dung chi tiết
            $table->string('featured_image')->nullable(); // Hình ảnh đại diện
            $table->string('author')->nullable(); // Tác giả
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft'); // Trạng thái
            $table->timestamp('published_at')->nullable(); // Thời gian xuất bản
            $table->integer('view_count')->default(0); // Số lượt xem
            $table->string('meta_title')->nullable(); // Meta title cho SEO
            $table->text('meta_description')->nullable(); // Meta description cho SEO
            $table->string('meta_keywords')->nullable(); // Meta keywords cho SEO
            $table->boolean('is_featured')->default(false); // Tin tức nổi bật
            $table->boolean('is_hot')->default(false); // Tin tức hot
            $table->integer('sort_order')->default(0); // Thứ tự sắp xếp
            $table->timestamps();
            $table->softDeletes(); // Soft delete
            
            // Indexes
            $table->index(['status', 'published_at']);
            $table->index(['is_featured', 'published_at']);
            $table->index(['is_hot', 'published_at']);
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
}; 