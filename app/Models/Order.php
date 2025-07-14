<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;




class Order extends Model
{

    protected $fillable = [
        'account_id',
        'payment_method_id',
        'payment_status_id',
        'order_status_id',
        'shipping_zone_id',
        'shipping_fee',
        'voucher_id',
        'voucher_code',
        'recipient_name',
        'recipient_email',
        'recipient_phone',
        'recipient_address',
        'note',
        'order_date',
        'total_amount',
        "cart_id",
        'user_confirmed_delivery',
    ];

    // 👇 Đây là phần bạn thiếu để dùng format() với order_date
    protected $casts = [
        'order_date' => 'datetime',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }
    public function paymentStatus(): BelongsTo
    {
        return $this->belongsTo(PaymentStatus::class, 'payment_status_id');
    }
    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Promotion::class, 'voucher_id');
    }
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
    public function statusModel()
    {
        return $this->belongsTo(CartStatus::class, 'cart_status_id');
    }
    public function shippingZone()
    {
        return $this->belongsTo(ShippingZone::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'account_id');
    }
    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class, 'order_status_id');
    }
    public function returnRequest()
    {
        return $this->hasOne(ReturnRequest::class);
    }
    public function deliveryIssues()
    {
        return $this->hasMany(OrderDeliveryIssue::class);
    }
}
