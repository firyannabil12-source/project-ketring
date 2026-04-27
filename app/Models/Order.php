<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'customer_name', 'customer_phone', 'event_date',
        'event_address', 'total_price', 'status', 'notes'
    ];

    protected $casts = [
        'event_date' => 'date',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
