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
            $table->string('item')->nullable()->after('category');
        });
    }

    public function down(): void
    {
        Schema::connection('procurement')->table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn('item');
        });
    }
};

