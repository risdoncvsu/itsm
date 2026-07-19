<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('shipment_number')->unique();
            $table->foreignId('purchase_order_id')->nullable()->constrained('purchase_orders')->nullOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedTinyInteger('stage')->default(0); // 0..4 progress steps
            $table->string('status')->default('shipment'); // shipment|intransit|delivered|delayed|cancel|complete
            $table->string('received_by')->nullable();
            $table->unsignedInteger('qty')->nullable();
            $table->unsignedInteger('qty_expected')->nullable();
            $table->string('items')->nullable();
            $table->text('remarks')->nullable();
            $table->date('delivery_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
