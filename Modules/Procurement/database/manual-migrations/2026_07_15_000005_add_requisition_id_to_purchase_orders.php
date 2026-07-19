<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;
    public function up(): void
    {
        Schema::connection('procurement')->table('purchase_orders', function (Blueprint $table) {
            if (! Schema::connection('procurement')->hasColumn('purchase_orders', 'requisition_id')) {
                $table->unsignedBigInteger('requisition_id')->nullable()->after('supplier_id');
            }
        });
    }

    public function down(): void
    {
        Schema::connection('procurement')->table('purchase_orders', function (Blueprint $table) {
            if (Schema::connection('procurement')->hasColumn('purchase_orders', 'requisition_id')) {
                $table->dropColumn('requisition_id');
            }
        });
    }
};


