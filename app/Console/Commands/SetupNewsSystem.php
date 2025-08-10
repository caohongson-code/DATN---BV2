<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class SetupNewsSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:setup {--fresh : Fresh migration}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Thiết lập hệ thống tin tức (migration + seeder)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Bắt đầu thiết lập hệ thống tin tức...');

        // Kiểm tra xem bảng news đã tồn tại chưa
        if (Schema::hasTable('news')) {
            if ($this->option('fresh')) {
                $this->warn('⚠️  Bảng news đã tồn tại. Sẽ xóa và tạo lại...');
                $this->call('migrate:fresh');
            } else {
                $this->warn('⚠️  Bảng news đã tồn tại. Bỏ qua migration.');
            }
        } else {
            $this->info('📊 Tạo bảng news...');
            $this->call('migrate');
        }

        // Chạy seeder
        $this->info('🌱 Tạo dữ liệu mẫu...');
        $this->call('db:seed', ['--class' => 'NewsSeeder']);

        $this->info('✅ Hoàn thành thiết lập hệ thống tin tức!');
        $this->info('📝 Truy cập: /admin/news');
        $this->info('📚 Xem hướng dẫn: README_NEWS.md');
    }
} 