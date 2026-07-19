<?php

namespace Modules\Inventory\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToClient
{
    protected $connection = 'inventory';

    protected static function bootBelongsToClient(): void
    {
        static::addGlobalScope('client', function (Builder $query): void {
            if ($clientId = session('employee_client_id')) {
                $query->where($query->getModel()->getTable().'.client_id', $clientId);
            }
        });

        static::creating(function ($model): void {
            if (! $model->client_id && ($clientId = session('employee_client_id'))) {
                $model->client_id = $clientId;
            }
        });
    }
}
