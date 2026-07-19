<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            if (! Schema::hasColumn('suppliers', 'product_items')) {
                $table->text('product_items')->nullable()->after('category');
            }
        });

        Schema::table('purchase_orders', function (Blueprint $table) {
            if (! Schema::hasColumn('purchase_orders', 'uom')) {
                $table->string('uom')->nullable()->after('qty');
            }
        });

        Schema::table('requisitions', function (Blueprint $table) {
            if (! Schema::hasColumn('requisitions', 'amount')) {
                $table->decimal('amount', 14, 2)->default(0)->after('qty');
            }
        });

        Schema::table('deliveries', function (Blueprint $table) {
            if (! Schema::hasColumn('deliveries', 'timer_minutes')) {
                $table->unsignedInteger('timer_minutes')->nullable()->after('qty_expected');
            }

            if (! Schema::hasColumn('deliveries', 'started_at')) {
                $table->timestamp('started_at')->nullable()->after('timer_minutes');
            }
        });
    }

    public function down(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            if (Schema::hasColumn('deliveries', 'started_at')) {
                $table->dropColumn('started_at');
            }

            if (Schema::hasColumn('deliveries', 'timer_minutes')) {
                $table->dropColumn('timer_minutes');
            }
        });

        Schema::table('requisitions', function (Blueprint $table) {
            if (Schema::hasColumn('requisitions', 'amount')) {
                $table->dropColumn('amount');
            }
        });

        Schema::table('purchase_orders', function (Blueprint $table) {
            if (Schema::hasColumn('purchase_orders', 'uom')) {
                $table->dropColumn('uom');
            }
        });

        Schema::table('suppliers', function (Blueprint $table) {
            if (Schema::hasColumn('suppliers', 'product_items')) {
                $table->dropColumn('product_items');
            }
        });
    }
};
