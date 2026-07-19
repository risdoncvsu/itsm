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
            $table->string('requisition_reference')->nullable()->after('remarks');
        });
    }

    public function down(): void
    {
        Schema::connection('procurement')->table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn('requisition_reference');
        });
    }
};


