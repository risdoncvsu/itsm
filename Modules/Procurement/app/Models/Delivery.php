<?php

namespace Modules\Procurement\Models;

use Modules\Procurement\Models\Concerns\BelongsToClient;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Delivery extends Model
{
    use HasFactory, BelongsToClient;

    protected $fillable = [
        'shipment_number', 'purchase_order_id', 'supplier_id', 'stage',
        'status', 'received_by', 'received_at', 'condition', 'qty', 'qty_expected', 'items',
        'timer_minutes', 'remarks', 'delivery_date', 'started_at',
    ];

    protected $casts = [
        'delivery_date' => 'date',
        'started_at' => 'datetime',
    ];

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}


