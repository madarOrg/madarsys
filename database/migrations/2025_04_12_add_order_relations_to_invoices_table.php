<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // إضافة حقول لربط الفواتير بأوامر الشراء والصرف
            if (!Schema::hasColumn('invoices', 'purchase_order_id')) {
                $table->foreignId('purchase_order_id')->nullable()->after('order_id')->constrained('purchase_orders')->nullOnDelete();
            }
            
            if (!Schema::hasColumn('invoices', 'sales_order_id')) {
                $table->foreignId('sales_order_id')->nullable()->after('purchase_order_id')->constrained('sales_orders')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasColumn('invoices', 'purchase_order_id')) {
                $table->dropForeign(['purchase_order_id']);
                $table->dropColumn('purchase_order_id');
            }
            
            if (Schema::hasColumn('invoices', 'sales_order_id')) {
                $table->dropForeign(['sales_order_id']);
                $table->dropColumn('sales_order_id');
            }
        });
    }
};
