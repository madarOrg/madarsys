<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // في ملف المهاجرة "add_warehouse_fields_to_inventory_tables.php"
    public function up()
    {
        // إضافة حقل رقم مستودع آخر في جدول inventory_transactions
        Schema::table('inventory_transactions', function (Blueprint $table) {
            $table->foreignId('secondary_warehouse_id')
                ->nullable()
                ->constrained('warehouses')
                ->onDelete('set null'); // رقم المستودع الآخر
        });

        // إضافة حقل رقم المستودع المستهدف في جدول inventory_transaction_items
        Schema::table('inventory_transaction_items', function (Blueprint $table) {
            $table->foreignId('target_warehouse_id')
                ->nullable()
                ->constrained('warehouses')
                ->onDelete('set null'); // رقم المستودع المستهدف بالحركة
        });
    }

    public function down()
    {
        // إزالة الحقل من جدول inventory_transactions
        Schema::table('inventory_transactions', function (Blueprint $table) {
            $table->dropForeign(['secondary_warehouse_id']);
            $table->dropColumn('secondary_warehouse_id');
        });

        // إزالة الحقل من جدول inventory_transaction_items
        Schema::table('inventory_transaction_items', function (Blueprint $table) {
            $table->dropForeign(['target_warehouse_id']);
            $table->dropColumn('target_warehouse_id');
        });
    }
};
