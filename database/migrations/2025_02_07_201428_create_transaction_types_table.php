<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * تشغيل الـ Migration.
     */
    public function up(): void
    {
        Schema::create('transaction_types', function (Blueprint $table) {
            $table->id(); // معرف نوع العملية
            $table->string('name')->unique()->comment('اسم نوع العملية المخزنية');
            $table->text('description')->nullable()->comment('وصف لنوع العملية');
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->timestamps();
        });

        // إدراج الأنواع الأساسية
// إدراج جميع أنواع العمليات المخزنية الأساسية والمتقدمة
DB::table('transaction_types')->insert([
    ['name' => 'شراء', 'description' => 'عملية شراء المنتجات من الموردين وإضافتها إلى المخزون.'],
    ['name' => 'إرجاع من عميل', 'description' => 'إرجاع المنتجات من العملاء إلى المخزون بعد البيع.'],
    ['name' => 'إنتاج داخلي', 'description' => 'إضافة المنتجات المصنَّعة داخليًا إلى المخزون.'],
    ['name' => 'صرف', 'description' => 'صرف المنتجات للأقسام أو الجهات الداخلية داخل الشركة.'],
    ['name' => 'تحويل مخزني', 'description' => 'نقل المنتجات بين المستودعات أو الأرفف المختلفة.'],
    ['name' => 'إرجاع لمورد', 'description' => 'إرجاع المنتجات إلى المورد بسبب التلف أو الخطأ في الطلبية.'],
    ['name' => 'بيع', 'description' => 'بيع المنتجات للعملاء وإخراجها من المخزون.'],
    ['name' => 'جرد مخزني', 'description' => 'عملية تدقيق لمقارنة الكمية الفعلية مع الكمية المسجلة في النظام.'],
    ['name' => 'تلف', 'description' => 'تسجيل المنتجات التالفة وإخراجها من المخزون.'],
    ['name' => 'سرقة', 'description' => 'تسجيل نقص المخزون الناتج عن فقدان غير مبرر أو سرقة.'],
    ['name' => 'تعديل يدوي', 'description' => 'تصحيح المخزون يدويًا بسبب أخطاء تسجيل سابقة.'],
    ['name' => 'حجز المخزون', 'description' => 'تحديد كمية من المخزون لطلبية قبل تنفيذ عملية البيع.'],
    ['name' => 'انتهاء الصلاحية', 'description' => 'إخراج المنتجات من المخزون بسبب انتهاء فترة صلاحيتها.'],
    ['name' => 'استرجاع من الإنتاج', 'description' => 'إرجاع المواد الخام غير المستخدمة إلى المخزون بعد التصنيع.']
]);
    }

    /**
     * التراجع عن الـ Migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_types');
    }
};
