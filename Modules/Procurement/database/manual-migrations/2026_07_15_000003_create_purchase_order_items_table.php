<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;
    public function up(): void
    {
        if (Schema::connection('procurement')->hasTable('purchase_order_items')) {
            return;
        }

        Schema::connection('procurement')->create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_order_id');
            $table->unsignedBigInteger('requisition_item_id')->nullable();
            $table->unsignedBigInteger('supplier_product_id')->nullable();
            $table->string('name');
            $table->unsignedInteger('qty')->default(1);
            $table->string('uom')->nullable();
            $table->decimal('unit_price', 14, 2)->default(0);
            $table->decimal('amount', 14, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('procurement')->dropIfExists('purchase_order_items');
    }
};


