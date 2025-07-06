<?php
namespace App\Console\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Console\Command;
use App\Models\Cart;
use Carbon\Carbon;

class CartUpdateStatus extends Command
{
    protected $signature = 'cart:update-status';
    protected $description = 'Cập nhật trạng thái giỏ hàng (abandoned nếu lâu không hoạt động)';

    // 👇 Đây là cách định nghĩa lịch chạy ngay trong command
    public function schedule(Schedule $schedule): void
    {
        $schedule->daily();
    }

    public function handle()
    {
        Cart::where('status', 'active')
            ->where('updated_at', '<', now()->subDays(7))
            ->update(['status' => 'abandoned']);

        $this->info('Đã cập nhật trạng thái giỏ hàng.');
    }
}
