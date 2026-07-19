<?php

namespace Modules\Procurement\Models;

use Modules\Procurement\Models\Concerns\BelongsToClient;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierProduct extends Model
{
    use HasFactory, BelongsToClient;

    protected $fillable = [
        'supplier_id',
        'name',
        'sku',
        'unit_price',
        'uom',
        'metadata',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'metadata' => 'array',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}


