<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * تشغيل المهاجرة
     */
    public function up(): void
    {
        Schema::table('transaction_types', function (Blueprint $table) {
            $table->integer('inventory_movement_count')->default(1)->comment('عدد الحركات المخزنية لهذا النوع: 1 للإدخال أو الإخراج، 2 للتحويل المخزني');
        });

        // تحديث القيم بناءً على نوع الحركة المخزنية
        DB::table('transaction_types')->updateOrInsert(['id' => 1], ['inventory_movement_count' => 1]); // شراء
        DB::table('transaction_types')->updateOrInsert(['id' => 2], ['inventory_movement_count' => 1]); // إرجاع من عميل
        DB::table('transaction_types')->updateOrInsert(['id' => 3], ['inventory_movement_count' => 1]); // إنتاج داخلي
        DB::table('transaction_types')->updateOrInsert(['id' => 4], ['inventory_movement_count' => 1]); // صرف
        DB::table('transaction_types')->updateOrInsert(['id' => 5], ['inventory_movement_count' => 2]); // تحويل مخزني
        DB::table('transaction_types')->updateOrInsert(['id' => 6], ['inventory_movement_count' => 1]); // إرجاع لمورد
        DB::table('transaction_types')->updateOrInsert(['id' => 7], ['inventory_movement_count' => 1]); // بيع
        DB::table('transaction_types')->updateOrInsert(['id' => 8], ['inventory_movement_count' => 0]); // جرد مخزني
        DB::table('transaction_types')->updateOrInsert(['id' => 9], ['inventory_movement_count' => 1]); // تلف
        DB::table('transaction_types')->updateOrInsert(['id' => 10], ['inventory_movement_count' => 1]); // سرقة
        DB::table('transaction_types')->updateOrInsert(['id' => 11], ['inventory_movement_count' => 0]); // تعديل يدوي
        DB::table('transaction_types')->updateOrInsert(['id' => 12], ['inventory_movement_count' => 1]); // حجز المخزون
        DB::table('transaction_types')->updateOrInsert(['id' => 13], ['inventory_movement_count' => 1]); // انتهاء الصلاحية
        DB::table('transaction_types')->updateOrInsert(['id' => 14], ['inventory_movement_count' => 1]); // استرجاع من الإنتاج
    }

    /**
     * التراجع عن المهاجرة
     */
    public function down(): void
    {
        Schema::table('transaction_types', function (Blueprint $table) {
            $table->dropColumn('inventory_movement_count');
        });
    }
};
