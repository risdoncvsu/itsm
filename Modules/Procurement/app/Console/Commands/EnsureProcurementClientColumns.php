<?php

namespace Modules\Procurement\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EnsureProcurementClientColumns extends Command
{
    protected $signature = 'procurement:ensure-client-columns';

    protected $description = 'Add client boundaries to existing Procurement tables on the dedicated Procurement database';

    public function handle(): int
    {
        $schema = Schema::connection('procurement');

        foreach ([
            'suppliers', 'supplier_products', 'requisitions', 'requisition_items',
            'purchase_orders', 'purchase_order_items', 'deliveries',
        ] as $tableName) {
            if (! $schema->hasTable($tableName) || $schema->hasColumn($tableName, 'client_id')) {
                continue;
            }

            $schema->table($tableName, function (Blueprint $table): void {
                $table->unsignedBigInteger('client_id')->nullable()->index();
            });

            $this->info("Added client_id to Procurement {$tableName}.");
        }

        return self::SUCCESS;
    }
}
