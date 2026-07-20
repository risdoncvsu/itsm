<?php

namespace Modules\OrderFulfillment\Models;

use Modules\OrderFulfillment\Models\Concerns\BelongsToClient;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use BelongsToClient;
    protected $table = 'shipments';

    protected $fillable = [
        'shipment_id',
        'order_id',
        'customer_name',
        'product_name',
        'qty',
        'amount',
        'courier',
        'box_used',
        'tracking_number',
        'status',
        'address',
        'due_date',
        'delivery_man_id',
        'shipped_at',
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
    ];

    public function deliveryMan()
    {
        return $this->belongsTo(DeliveryMan::class, 'delivery_man_id');
    }

    protected static function booted(): void
    {
        // Requirement #5: a driver only becomes available again once the
        // shipment they were carrying is delivered. This fires no matter
        // where the status change comes from ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€šÃ‚Â this controller, an API,
        // a queue job, artisan tinker, etc.
        static::updating(function (Shipment $shipment) {
            if (
                $shipment->isDirty('status') &&
                strtoupper($shipment->status) === 'DELIVERED' &&
                $shipment->delivery_man_id
            ) {
                DeliveryMan::where('id', $shipment->delivery_man_id)
                    ->update(['status' => DeliveryMan::STATUS_AVAILABLE]);
            }
        });

        // Requirement #6: the 1-day SHIPPED -> READY_TO_SHIP timer starts
        // the moment a shipment's status becomes SHIPPED.
        static::saving(function (Shipment $shipment) {
            if (
                $shipment->isDirty('status') &&
                strtoupper($shipment->status) === 'SHIPPED' &&
                ! $shipment->shipped_at
            ) {
                $shipment->shipped_at = now();
            }
        });
    }
}



