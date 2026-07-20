<?php

namespace Modules\OrderFulfillment\Models;

use Modules\OrderFulfillment\Models\Concerns\BelongsToClient;

use Illuminate\Database\Eloquent\Model;

class DeliveryMan extends Model
{
    use BelongsToClient;
    protected $table = 'delivery_men';

    // IDs are strings like "DM-FLASH-001", not auto-increment integers.
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    // delivery_men has created_at/updated_at columns (confirmed in schema),
    // but nothing in the app currently relies on Eloquent auto-managing them.
    // Leave this false unless you start using ->create() / ->save() and want
    // those columns populated automatically.
    public $timestamps = false;

    const STATUS_AVAILABLE = 'AVAILABLE';
    const STATUS_UNAVAILABLE = 'UNAVAILABLE';

    protected $fillable = [
        'id',
        'name',
        'age',
        'license_num',
        'plate_number',
        'vehicle_type',
        'courier_provider', // confirmed column name in delivery_men table
        'status',
    ];

    /**
     * Scope: only drivers currently free to take a shipment.
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_AVAILABLE);
    }

    /**
     * Scope: only drivers who work for the given courier.
     * Matched against shipments.courier in ShippingController.
     */
    public function scopeForCourier($query, string $courier)
    {
        return $query->where('courier_provider', $courier);
    }
}



