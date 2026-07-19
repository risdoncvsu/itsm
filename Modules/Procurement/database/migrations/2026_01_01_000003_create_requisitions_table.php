<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requisitions', function (Blueprint $table) {
            $table->id();
            $table->string('req_number')->unique();
            $table->string('item');
            $table->unsignedInteger('qty')->default(1);
            $table->string('uom')->nullable();
            $table->string('delivery_status')->default('pending');
            $table->string('department')->nullable();
            $table->string('requested_by')->nullable();
            $table->string('status')->default('pending'); // pending|approved|rejected
            $table->date('date_requested');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requisitions');
    }
};
