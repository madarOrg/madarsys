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
            $table->foreignId('partner_id')->nullable()->constrained('partners')->onDelete('set null')->after('payment_type_id');
            
            // إضافة حقل لحفظ رقم أمر الشراء
            $table->string('purchase_order_number')->nullable()->after('partner_id');
            
            // إضافة حقل لتتبع حالة طباعة أمر الشراء
            $table->boolean('is_printed')->default(false)->after('purchase_order_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['partner_id']);
            $table->dropColumn(['partner_id', 'purchase_order_number', 'is_printed']);
        });
    }
};
