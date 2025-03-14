<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInventoryTransactionIdAndQuantityToInventoryProducts extends Migration
{
    /**
     * تشغيل الميجرشن.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventory_products', function (Blueprint $table) {
            // إضافة الحقل وربطه بجدول inventory_transaction_items بدلاً من inventory_transactions
            $table->foreignId('inventory_transaction_item_id')->constrained('inventory_transaction_items')->onDelete('cascade'); 
        
            // إضافة حقل quantity مع قيمة افتراضية 0
            $table->integer('quantity')->default(0);
            
            // التحويلات المؤقتة (Temporary Transfers)
            $table->timestamp('temporary_transfer_expiry_date')->nullable();
        });
        
    }

    /**
     * التراجع عن الميجرشن.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventory_products', function (Blueprint $table) {
            // حذف الحقول إذا تم التراجع عن الميجرشن
            $table->dropForeign(['inventory_transaction_id']);
            $table->dropColumn('inventory_transaction_id');
            $table->dropColumn('quantity');
        });
    }
}
