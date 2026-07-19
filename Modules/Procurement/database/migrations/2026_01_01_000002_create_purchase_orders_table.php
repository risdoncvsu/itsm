<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique();
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->string('category')->nullable();
            $table->unsignedInteger('qty')->default(1);
            $table->decimal('amount', 14, 2)->default(0);
            $table->string('delivery_status')->default('pending'); // pending|shipment|intransit|delivered|delayed|cancel|complete
            $table->string('status')->default('pending'); // pending|processing|approved|rejected|completed
            $table->date('order_date');
            $table->string('created_by')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
