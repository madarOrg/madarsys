<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // إضافة warehouse_id إلى جدول orders
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('warehouse_id')
                  ->nullable()
                  ->constrained('warehouses')
                  ->nullOnDelete();
        });

        // إضافة unit_id إلى جدول order_details
        Schema::table('order_details', function (Blueprint $table) {
            $table->foreignId('unit_id')
                  ->constrained('units')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        // إزالة warehouse_id من orders
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['warehouse_id']);
            $table->dropColumn('warehouse_id');
        });

        // إزالة unit_id من order_details
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropColumn('unit_id');
        });
    }
};
