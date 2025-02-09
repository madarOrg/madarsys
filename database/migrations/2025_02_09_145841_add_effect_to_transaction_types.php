<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('transaction_types', function (Blueprint $table) {
            $table->enum('effect', ['+', '-','0'])->after('description')->default('+')->comment('تحديد ما إذا كانت العملية المخزنية إضافة (+) أو خصم (-)');
        });

        // تحديث البيانات الحالية
        DB::table('transaction_types')
            ->whereIn('name', ['شراء', 'إرجاع من عميل', 'إنتاج داخلي', 'استرجاع من الإنتاج'])
            ->update(['effect' => '+']);

        DB::table('transaction_types')
            ->whereIn('name', ['بيع', 'إرجاع لمورد', 'تلف', 'سرقة', 'صرف', 'تحويل مخزني', 'انتهاء الصلاحية'])
            ->update(['effect' => '-']);
     DB::table('transaction_types')
            ->whereIn('name', ['شراء', 'إرجاع من عميل', 'إنتاج داخلي', 'استرجاع من الإنتاج'])
            ->update(['effect' => '+']);
    }

    public function down(): void
    {
        Schema::table('transaction_types', function (Blueprint $table) {
            $table->dropColumn('effect');
        });
    }
};
