<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\OrderFulfillment\Models\Concerns\BelongsToClient;

class ReturnItem extends Model
{
    use HasFactory, BelongsToClient;
    protected $table = 'returns';

    protected $fillable = [
        'id',
        'order_id',
        'customer_name',
        'product_name',
        'reason',
        'status',
        'resolution',
        'due_date',
        'address',
        'refund_amount'
    ];

    public $incrementing = false;
    protected $keyType = 'string';
}