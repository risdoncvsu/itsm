<?php

namespace Modules\OrderFulfillment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\OrderFulfillment\Models\Concerns\BelongsToClient;

class OrderItem extends Model
{
    use HasFactory, BelongsToClient;
    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'product_name',
        'qty',
        'product_amount',
    ];

    protected $casts = [
        'qty' => 'integer',
        'product_amount' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function getLineTotalAttribute()
    {
        return $this->qty * $this->product_amount;
    }
}
