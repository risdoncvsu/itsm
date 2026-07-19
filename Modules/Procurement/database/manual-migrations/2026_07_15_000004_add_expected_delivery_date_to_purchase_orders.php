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
            if (! Schema::connection('procurement')->hasColumn('purchase_orders', 'expected_delivery_date')) {
                $table->date('expected_delivery_date')->nullable()->after('order_date');
            }
        });
    }

    public function down(): void
    {
        Schema::connection('procurement')->table('purchase_orders', function (Blueprint $table) {
            if (Schema::connection('procurement')->hasColumn('purchase_orders', 'expected_delivery_date')) {
                $table->dropColumn('expected_delivery_date');
            }
        });
    }
};


