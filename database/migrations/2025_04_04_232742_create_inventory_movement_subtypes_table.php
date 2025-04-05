<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventory_transaction_subtypes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_type_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable()->default(2);
            $table->timestamp('created_at')->nullable();
            $table->unsignedBigInteger('created_user')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('transaction_type_id')
                ->references('id')
                ->on('transaction_types')
                ->onDelete('cascade');

            $table->foreign('branch_id')
                ->references('id')
                ->on('branches')
                ->onDelete('set null');

            $table->foreign('created_user')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });

        DB::table('inventory_transaction_subtypes')->insert([
            // 1. شراء
            ['transaction_type_id' => 1, 'name' => 'شراء محلي', 'branch_id' => 2],
            ['transaction_type_id' => 1, 'name' => 'شراء مستورد', 'branch_id' => 2],
            ['transaction_type_id' => 1, 'name' => 'شراء نقدي', 'branch_id' => 2],
            ['transaction_type_id' => 1, 'name' => 'شراء آجل', 'branch_id' => 2],
            ['transaction_type_id' => 1, 'name' => 'عينة مجانية', 'branch_id' => 2],

            // 2. إرجاع من عميل
            ['transaction_type_id' => 2, 'name' => 'إرجاع بسبب عيب', 'branch_id' => 2],
            ['transaction_type_id' => 2, 'name' => 'إرجاع زائد عن الحاجة', 'branch_id' => 2],
            ['transaction_type_id' => 2, 'name' => 'إرجاع بسبب خطأ في الطلب', 'branch_id' => 2],
            ['transaction_type_id' => 2, 'name' => 'إرجاع ضمن فترة الضمان', 'branch_id' => 2],

            // 3. إنتاج داخلي
            ['transaction_type_id' => 3, 'name' => 'منتج تام', 'branch_id' => 2],
            ['transaction_type_id' => 3, 'name' => 'منتج نصف مصنع', 'branch_id' => 2],
            ['transaction_type_id' => 3, 'name' => 'منتج تجريبي', 'branch_id' => 2],

            // 4. صرف
            ['transaction_type_id' => 4, 'name' => 'صرف للإنتاج', 'branch_id' => 2],
            ['transaction_type_id' => 4, 'name' => 'صرف لأغراض الصيانة', 'branch_id' => 2],
            ['transaction_type_id' => 4, 'name' => 'صرف للتجربة', 'branch_id' => 2],
            ['transaction_type_id' => 4, 'name' => 'صرف للتالف', 'branch_id' => 2],
            ['transaction_type_id' => 4, 'name' => 'صرف للموظفين', 'branch_id' => 2],

            // 5. تحويل مخزني
            ['transaction_type_id' => 5, 'name' => 'تحويل بين مستودعات', 'branch_id' => 2],
            ['transaction_type_id' => 5, 'name' => 'تحويل بين أرفف', 'branch_id' => 2],
            ['transaction_type_id' => 5, 'name' => 'تحويل للصيانة', 'branch_id' => 2],
            ['transaction_type_id' => 5, 'name' => 'تحويل لفرع آخر', 'branch_id' => 2],
            ['transaction_type_id' => 5, 'name' => 'تحويل للتجهيز', 'branch_id' => 2],

            // 6. إرجاع لمورد
            ['transaction_type_id' => 6, 'name' => 'بسبب تلف', 'branch_id' => 2],
            ['transaction_type_id' => 6, 'name' => 'بسبب عدم مطابقة', 'branch_id' => 2],
            ['transaction_type_id' => 6, 'name' => 'بسبب نقص جودة', 'branch_id' => 2],
            ['transaction_type_id' => 6, 'name' => 'بسبب زيادة في الطلبية', 'branch_id' => 2],

            // 7. بيع
            ['transaction_type_id' => 7, 'name' => 'بيع نقدي', 'branch_id' => 2],
            ['transaction_type_id' => 7, 'name' => 'بيع آجل', 'branch_id' => 2],
            ['transaction_type_id' => 7, 'name' => 'بيع جملة', 'branch_id' => 2],
            ['transaction_type_id' => 7, 'name' => 'بيع تجزئة', 'branch_id' => 2],
            ['transaction_type_id' => 7, 'name' => 'عرض ترويجي', 'branch_id' => 2],

            // 8. جرد مخزني
            ['transaction_type_id' => 8, 'name' => 'جرد سنوي', 'branch_id' => 2],
            ['transaction_type_id' => 8, 'name' => 'جرد دوري', 'branch_id' => 2],
            ['transaction_type_id' => 8, 'name' => 'جرد مفاجئ', 'branch_id' => 2],
            ['transaction_type_id' => 8, 'name' => 'جرد عند تغيير مسؤول', 'branch_id' => 2],
            ['transaction_type_id' => 8, 'name' => 'جرد عند النقل', 'branch_id' => 2],

            // 9. تلف
            ['transaction_type_id' => 9, 'name' => 'تلف أثناء التخزين', 'branch_id' => 2],
            ['transaction_type_id' => 9, 'name' => 'تلف أثناء النقل', 'branch_id' => 2],
            ['transaction_type_id' => 9, 'name' => 'تلف بسبب حرارة', 'branch_id' => 2],
            ['transaction_type_id' => 9, 'name' => 'تلف بسبب ماء', 'branch_id' => 2],
            ['transaction_type_id' => 9, 'name' => 'تلف نتيجة إهمال', 'branch_id' => 2],

            // 10. سرقة
            ['transaction_type_id' => 10, 'name' => 'سرقة داخلية', 'branch_id' => 2],
            ['transaction_type_id' => 10, 'name' => 'سرقة خارجية', 'branch_id' => 2],
            ['transaction_type_id' => 10, 'name' => 'فقدان غير مبرر', 'branch_id' => 2],

            // 11. تعديل يدوي
            ['transaction_type_id' => 11, 'name' => 'تعديل خطأ تسجيل', 'branch_id' => 2],
            ['transaction_type_id' => 11, 'name' => 'تعديل نتيجة جرد', 'branch_id' => 2],
            ['transaction_type_id' => 11, 'name' => 'تعديل ناتج عن إرجاع سابق', 'branch_id' => 2],

            // 12. حجز المخزون
            ['transaction_type_id' => 12, 'name' => 'حجز لطلبية', 'branch_id' => 2],
            ['transaction_type_id' => 12, 'name' => 'حجز لمشروع داخلي', 'branch_id' => 2],
            ['transaction_type_id' => 12, 'name' => 'حجز مؤقت', 'branch_id' => 2],

            // 13. انتهاء الصلاحية
            ['transaction_type_id' => 13, 'name' => 'صلاحية منتهية بالمخزن', 'branch_id' => 2],
            ['transaction_type_id' => 13, 'name' => 'صلاحية منتهية أثناء النقل', 'branch_id' => 2],
            ['transaction_type_id' => 13, 'name' => 'صلاحية منتهية أثناء العرض', 'branch_id' => 2],

            // 14. استرجاع من الإنتاج
            ['transaction_type_id' => 14, 'name' => 'فائض مواد خام', 'branch_id' => 2],
            ['transaction_type_id' => 14, 'name' => 'مواد غير مطابقة', 'branch_id' => 2],
            ['transaction_type_id' => 14, 'name' => 'فائض بعد تجريب', 'branch_id' => 2],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_transaction_subtypes');
    }
};
