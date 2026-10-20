<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_code', 'user_id', 'customer_name', 'order_type', 'table_number',
        'total_amount', 'status', 'payment_method', 'payment_status',
        'cash_amount', 'change_amount',
    ];

    protected $casts = [
        'total_amount'  => 'integer',
        'cash_amount'   => 'integer',
        'change_amount' => 'integer',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}