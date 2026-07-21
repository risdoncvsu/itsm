<?php

namespace Modules\OrderFulfillment\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\OrderFulfillment\Models\Concerns\BelongsToClient;

class Order extends Model
{
    use HasFactory, BelongsToClient;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = ['id'];
}


