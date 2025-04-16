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
            // إضافة حقل لربط الفاتورة بالطلب
            if (!Schema::hasColumn('invoices', 'order_id')) {
                $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('set null')->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // التحقق من وجود العمود قبل حذفه
            if (Schema::hasColumn('invoices', 'order_id')) {
                $table->dropForeign(['order_id']);
                $table->dropColumn('order_id');
            }
        });
    }
};
