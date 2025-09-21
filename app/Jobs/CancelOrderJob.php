<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CancelOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $orderId;

    /**
     * Tạo job với ID đơn hàng
     */
    public function __construct($orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * Xử lý job
     */
    public function handle()
    {
        $order = Order::find($this->orderId);

        if (!$order) {
            return;
        }

        // Chỉ hủy nếu đơn vẫn ở trạng thái "Chờ thanh toán"
        if ($order->order_status_id === 1 && $order->payment_status_id === 1) {
            $order->order_status_id = 7; // 7 = Đã hủy
            $order->save();

            Log::info("Đơn hàng #{$order->id} đã được tự động hủy do quá hạn thanh toán.");
        }
    }
}
