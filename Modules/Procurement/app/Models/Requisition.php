<?php

namespace Modules\Procurement\Models;

use Modules\Procurement\Models\Concerns\BelongsToClient;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Requisition extends Model
{
    use HasFactory, BelongsToClient;

    protected $fillable = [
        'req_number', 'item', 'qty', 'amount', 'uom', 'delivery_status',
        'department', 'requested_by', 'status', 'date_requested', 'notes',
    ];

    protected $casts = [
        'date_requested' => 'date',
        'amount' => 'decimal:2',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(RequisitionItem::class);
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class, 'requisition_id');
    }

    public function getSupplierNameAttribute(): string
    {
        return $this->parseNotesSegment('Supplier:');
    }

    public function getSupplierItemAttribute(): string
    {
        return $this->parseNotesSegment('Item:');
    }

    private function parseNotesSegment(string $label): string
    {
        $notes = trim((string) $this->notes);
        if ($notes === '') {
            return '';
        }

        $segments = preg_split('/\s*\|\s*/', $notes);
        foreach ($segments as $segment) {
            if (str_starts_with($segment, $label)) {
                return trim(substr($segment, strlen($label)));
            }
        }

        return '';
    }
}


