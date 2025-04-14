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
        Schema::table('orders', function (Blueprint $table) {
            // إضافة حقل لربط الطلب بالمورد/الشريك
            if (!Schema::hasColumn('orders', 'partner_id')) {
                $table->foreignId('partner_id')->nullable()->constrained('partners')->onDelete('set null')->after('payment_type_id');
            }
            
            // إضافة حقل لحفظ رقم أمر الشراء
            if (!Schema::hasColumn('orders', 'purchase_order_number')) {
                $table->string('purchase_order_number')->nullable()->after('partner_id');
            }
            
            // إضافة حقل لتتبع حالة طباعة أمر الشراء
            if (!Schema::hasColumn('orders', 'is_printed')) {
                $table->boolean('is_printed')->default(false)->after('purchase_order_number');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // التحقق من وجود الأعمدة قبل حذفها
            if (Schema::hasColumn('orders', 'partner_id')) {
                $table->dropForeign(['partner_id']);
                $table->dropColumn('partner_id');
            }
            
            if (Schema::hasColumn('orders', 'purchase_order_number')) {
                $table->dropColumn('purchase_order_number');
            }
            
            if (Schema::hasColumn('orders', 'is_printed')) {
                $table->dropColumn('is_printed');
            }
        });
    }
};
