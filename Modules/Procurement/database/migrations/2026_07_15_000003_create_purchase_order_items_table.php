<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('requisition_item_id')->nullable()->constrained('requisition_items')->nullOnDelete();
            $table->foreignId('supplier_product_id')->nullable()->constrained('supplier_products')->nullOnDelete();
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
        Schema::dropIfExists('purchase_order_items');
    }
};
