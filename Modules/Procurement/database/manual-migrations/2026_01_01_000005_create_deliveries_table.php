<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;
    public function up(): void
    {
        if (Schema::connection('procurement')->hasTable('deliveries')) {
            return;
        }

        Schema::connection('procurement')->create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('shipment_number');
            $table->unsignedBigInteger('purchase_order_id')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
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
        Schema::connection('procurement')->dropIfExists('deliveries');
    }
};


