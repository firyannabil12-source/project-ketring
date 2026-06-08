<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'customer_name', 'customer_phone', 'event_date',
        'event_address', 'total_price', 'status', 'notes',
        'payment_method', 'payment_status', 'payment_expires_at', 'estimation_time',
        'payment_url', 'reference', 'latitude', 'longitude',
    ];

    protected $casts = [
        'event_date' => 'date',
        'payment_expires_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
