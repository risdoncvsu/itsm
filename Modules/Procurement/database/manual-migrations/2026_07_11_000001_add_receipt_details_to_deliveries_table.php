<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;
    public function up(): void
    {
        Schema::connection('procurement')->table('deliveries', function (Blueprint $table) {
            $table->string('received_at')->nullable()->after('received_by');
            $table->string('condition')->default('good')->after('received_at');
        });
    }

    public function down(): void
    {
        Schema::connection('procurement')->table('deliveries', function (Blueprint $table) {
            $table->dropColumn(['received_at', 'condition']);
        });
    }
};


