<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('transaction_types', function (Blueprint $table) {
            $table->tinyInteger('effect')->after('description')->default(1)->comment('تحديد ما إذا كانت العملية المخزنية إضافة (1) أو خصم (-1) أو محايدة (0)');
        });

        // تحديث البيانات الحالية
        DB::table('transaction_types')
            ->whereIn('name', ['شراء', 'إرجاع من عميل', 'إنتاج داخلي', 'استرجاع من الإنتاج'])
            ->update(['effect' => 1]);

        DB::table('transaction_types')
            ->whereIn('name', ['بيع', 'إرجاع لمورد', 'تلف', 'سرقة', 'صرف', 'انتهاء الصلاحية'])
            ->update(['effect' => -1]);

        // العمليات المحايدة
        DB::table('transaction_types')
            ->whereIn('name', ['تعديل يدوي', 'جرد مخزني','تحويل مخزني']) // أضف أي عمليات محايدة هنا
            ->update(['effect' => 0]);
    }

    public function down(): void
    {
        Schema::table('transaction_types', function (Blueprint $table) {
            $table->dropColumn('effect');
        });
    }
};
