<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $schema = Schema::connection('procurement');

        if (! $schema->hasTable('purchase_orders')) {
            return;
        }

        if (! $schema->hasColumn('purchase_orders', 'warehouse_id')) {
            $schema->table('purchase_orders', function (Blueprint $table): void {
                $table->unsignedBigInteger('warehouse_id')->nullable()->index();
            });
        }

        if (! $schema->hasColumn('purchase_orders', 'delivery_address')) {
            $schema->table('purchase_orders', function (Blueprint $table): void {
                $table->string('delivery_address')->nullable();
            });
        }
    }

    public function down(): void
    {
        // Existing purchase orders retain their selected destination warehouse
        // on rollback; do not remove operational data automatically.
    }
};
